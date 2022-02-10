---
extends: _layouts.post
section: content
title: 9 règles pour écrire le code différement
date: 2020-04-11
ressources:
    - name: Article de William Durand
      link_url: https://williamdurand.fr/2013/06/03/object-calisthenics/
    - name: Conférence et slide de Rafael Dohms
      link_url: https://youtu.be/GtB5DAfOWMQ
    - name: Live Coding de Kevin Timmins en Java sur le Checkout Kata
      link_url: https://youtu.be/kBNThogwWYw
---

Comme beaucoup de développeurs, j'aime apprendre de nouvelles choses. Et une chose qui m'intéresse beaucoup est la qualité de code et la manière d'écrire le code.

Afin de m'exercer dans différents domaines, je fais assez souvent des Katas auxquels je me rajoute des contraintes. Et il y a quelque temps, j'ai découvert les **Object Calisthenics**.

Dernière ce nom un peu barbare se cachent 9 règles à suivre lorsque l'on écrit du code. Et autant dire que certaines de ces règles sont bien contraire à tout ce qu'on apprend à l'école ou dans les différents tutoriels et formations en ligne.

L'objectif est d'avoir un code plus **maintenable**, plus **lisible** et plus **facilement testable**.

> Le code est lu beaucoup plus souvent qu'il n'est écrit

<div class="my-4 bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
  <p class="text-sm font-bold">Les règles suivantes ne sont pas à prendre au pied de la lettre. Le principe est de les expérimenter sur des petits projets perso ou lors de code Kata afin de voir ce qu'il serait intéressant d'appliquer en situation réelle.
</div>

# Un seul niveau d'indentation par méthode

Cela permet de faciliter la lecture et la maintenabilité du code.

```php
// 🚫 Cette méthode contient deux niveaux d'indentation
public function notify(array $contacts)
{
    foreach ($contacts as $contact) {
        if ($contact->isEnabled()) {
            $this->mailer->send($contact);
        }
    }
}
```

Plusieurs solutions possibles :

```php
// ✅Extraction du code indenté dans une autre méthode
public function notify(array $contacts)
{
    foreach ($contacts as $contact) {
        $this->notifyContact()
    }
}

private function notifyContact(Contact $contact)
{
    if ($contact->isEnabled()) {
        $this->mailer->send($contact);
    }
}

// ---------------------------------------------------

// ✅ On filtre en amont de la boucle la liste des contacts 
public function notify(array $contacts)
{
    $enabledContacts = array_filter(
      $contacts,
      fn($contact) => $contact->isEnabled()
    );
  
    foreach ($enabledContacts as $contact) {
        $this->mailer->send($contact);
    }
}

// ---------------------------------------------------

// ✅ On demande à l'appelant de nous envoyer directement
// la liste des contacts activés
public function notify(array $enabledContacts)
{
    foreach ($enabledContacts as $contact) {
        $this->mailer->send($contact);
    }
}
```


# Ne pas utiliser le mot clé else

L'utilisation du `else` nous oblige à lire du code imbriqué (avec donc plus de niveaux d'indentation) alors qu'on peut la plupart des cas s'en passer.

## Première solution : utilisation d'early return

```php
class ItemManager
{
    private $cache;
    private $repository;

    public function __construct($cache, $repository)
    {
        $this->cache = $cache;
        $this->repository = $repository;
    }

    public function get($id)
    {
        if (!$this->cache->has($id)) {
            $item = $this->repository->get($id);
            $this->cache->set($id, $item);
        } else {
            $item = $this->cache->get($id);
        }

        return $item;
    }
}
```


```php
class ItemManager
{
    private $cache;
    private $repository;

    public function __construct($cache, $repository)
    {
        $this->cache = $cache;
        $this->repository = $repository;
    }

    // ✅Utilisation d'early returns
    public function get($id)
    {
        if ($this->cache->has($id)) {
            return $this->cache->get($id);
        }

        $item = $this->repository->get($id);
        $this->cache->set($id, $item);

        return $item;
    }
}
```


## Initialisation en amont

```php
public function redirect(User $user)
{
    if ($user->isAuthenticate()) {
        $urlToRedirect = '/dashboard';
    } else {
        $urlToRedirect = '/login';
    }

    return $urlToRedirect;
}
```

```php
// ✅Initialisation en amont de la valeur par défaut
// valide si l'initialisation est peu coûteuse
public function redirect(User $user)
{
    $urlToRedirect = '/login';
    if ($user->isAuthenticate()) {
        $urlToRedirect = '/dashboard';
    }

    return $urlToRedirect;
}
```

## Utilisation du principe du Fail Fast

```php
class MyListener
{
    public function onDelete(Event $event)
    {
        if ($event->getType() === 'OBJECT_DELETE' 
            && $event->getObject instanceOf MyEntity) {
            $this->cache->invalidate($event->getObject());
        } else {
            if ($event->getType() !== 'OBJECT_DELETE') {
                throw new \Exception('Invalid event type');
            } else {
                throw new \Exception('Invalid object instance');
            }
        }
    }
}
```

```php
// ✅Utilisation du principe Fail Fast : on teste tout
// de suite les cas d'erreurs
class MyListener
{
    public function onDelete(Event $event)
    {
        if ($event->getType() !== 'OBJECT_DELETE') {
            throw new \Exception('Invalid event type');
        }

        $myEntity = $event->getObject();
        if (!$myEntity instanceOf MyEntity) {
            throw new \Exception('Invalid object instance');
        }

        $this->cache->invalidate(myEntity);
    }
}
```


# Encapsuler tous les types primitifs dans des objets
(surtout ceux qui ont des comportements particuliers)

Avantages :
* encapsulation des traitements
* type hinting
* validation des paramètres en amont.


```php
public function fizzBuzz(int $integer)
{
    if ($integer <= 0) {
        throw new \Exception('Only positive integer is handled');
    }

    if ($integer%15 === 0) {
        return 'FizzBuzz';
    }

    //...
}
```

```php 
// Remplacement du int par un objet PositiveInteger
public function fizzBuzz(PositiveInteger $integer)
{
    // ✅Plus de test de validation du paramètre en entrée
    if ($integer->isMultipleOf(15)) {
        return 'FizzBuzz';
    }

    // ...
}

// Utilisation d'un Value Object
class PositiveInteger
{
    private $value;

    public function __construct(int $integer)
    {
      	// ✅Le test de validation de l'entier se fait directement ici
        if ($integer <= 0) {
            throw new \Exception('Only positive integer is handled');
        }

        $this->value = $integer;
    }

    // ✅On peut même ajouter des fonctions liés à cet objet
    public function isMultipleOf(int $multiple)
    {
        return $this->valueinteger%$multiple === 0;
    }
}
```

Autre exemple :

```php
// 🚫Le fait de passer un tableau ne nous permet pas d'être sur 
// du contenu et nous oblige à faire des tests supplémentaires
public function notify(array $enabledContacts)
{
    foreach ($contacts as $contact) {
        if ($contact->isEnabled()) {
            $this->mailer->send($contact);
        }
    }
}
```


```php
// ✅On passe ici directement un objet contenant uniquement 
// des contacts activés. 
// On est donc assuré de n'avoir que des contacts actifs
public function notify(EnabledContacts $enabledContacts)
{
    foreach ($enabledContacts as $contact) {
        $this->mailer->send($contact);
    }
}

class EnabledContacts implements \Iterator 
{
    private $contacts;

    public function __construct(array $contacts)
    (
        // ✅On ne garde ici que les contacts actifs
        $this->contacts = array_filter(
          $contacts,
          fn(Contact $contact) => $contact->isEnabled()
        );
    )

    // ... définition des méthode de l'interface \Iterator
}
```

Autre exemple :

```php
// 🚫 Deux paramètres sont ici fortement liés
public function findAll(int $start, int $end)
{
  // récupération paginée des données en BDD
}
```

```php
// ✅ On regroupe ici dans une seule classe deux attributs 
// qui étaient liés
public function findAll(Pagination $pagination)
{
  $start = $pagination->getStart();
  $end = $pagination->getEnd();
  
  ...// récupération paginée des données en BDD
}
```


# First Class Collections : une classe qui contient comme attribut un tableau ne doit contenir aucun autre attribut

Le code lié à cette collection est désormais encapsulé dans sa propre classe.

```php
class Newsletter
{ 
  	private int $id;
    private string $title;
  
    // 🚫L'objet contient déjà deux attributs, il ne peut
    //   donc pas contenir un array. Il faut l'encapsuler
    //   dans un objet
    private array $subscriberCollection;

    public function getNumberOfSubscriberWhoOpen()
    {
        $subscribersWhoOpen = array_filter(
          $this->subscriberCollection,
          fn($subscriber) => $subscriber->hasOpenNewsletter()
        );

        return \count($subscriberWhoOpen);
    }

    // ....
}
```

```php
class Newsletter
{
    private int $id;
    private string $title;
  
    // ✅Le tableau est désormais encapsulé dans sa propre classe
    private SubscriberCollection $subscriberCollection;

    public function getNumberOfSubscriberWhoOpen()
    {
        return $this->subscriberCollection
            ->getSubscriberWhoOpen()
            ->count();
    }

    // ....
}

class SubscriberCollection 
{
    private array $subscriberCollection;

    // ✅On peut déclarer ici des méthodes "métiers" 
    //   liées aux subscribers 
    public function getSubscriberWhoOpen()
    {
      	$subscribersWhoOpen = array_filter(
          $this->subscriberCollection,
          fn($subscriber) => $subscriber->hasOpenNewsletter()
        );
      
        return new static($subscribersWhoOpen);
    }

    // ...
}
```

# Un seul point (ou -> pour le PHP) par ligne (sauf pour les Fluent interface)

L'objectif ici n'est pas d'avoir un code joliment formaté mais de respecter la loi de Demeter : "Ne parlez qu'à vos amis immédiats".


```php
class User
{
    private ?Identity $identity;

    public function getIdentity(): ?Identity
    {
        return $this->identity;
    }
}

class Identity
{
    private string $firstName;
    private string $lastName;

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }
}

$user = new User();
$fullName = sprintf(
  '%s %s',
  // 🚫 Non respect de la loi de demeter
  // 🚫 getIdentity() pourrait très bien retourner null
  //    et cela générerait une erreur
  $user->getIdentity()->getFirstName(), 
  $user->getIdentity()->getLastName()
);
```

```php
class User
{
    private ?Identity $identity;

    public function getFullName(): string
    {
      if ($this->identity === null) {
      	return 'John Doe';
      }
      
      return sprintf(
        '%s %s',
        // La règle d’origine s’applique par exemple au java ou le mot clé « this » 
        // n’a pas besoin d’être spécifié dans les classes.
        // On ne compte donc pas ici la première ->
        // car en PHP $this est obligatoire dans les classes
        // pour utiliser un attribut
        $this->identity->getFirstName(),
        $this->identity->getLastName()
      );
    }
}

class Identity
{
    private string $firstName;
    private string $lastName;

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }
}

$user = new User();
// ✅Respect de la loi de Demeter
// ✅Plus de gestion d'erreur ici
$fullName = $user->getFullName();
```

```php
// ✅Cette règle ne s'applique par pour les fluent interface
//   comme les query builder par exemple 
$query = $queryBuilder
  ->select('user')
  ->where('user.id = :id')
  ->setParameter('id', 1);
  ->getQuery()
;
```

# Ne pas utiliser d'abréviation

Une des règles la plus simple à appliquer et surtout à appliquer de suite !

* Meilleur compréhension
* Meilleur maintenabilité
* Si vous n'arrivez pas à nommer => la fonction fait trop de choses, le contenu de la variable n'est pas claire, etc

```php
🚫 $m->send($contact);

✅ $mailer->send($contact)
```

```php
🚫 $cmpMng->getById($id);

✅ $companyManager->getById($contact)
```

```php
🚫 $translator->trans($input);

✅ $translator->translate($input)
```

# Garder petites toutes les entités (classes, méthodes, packages / namespaces)

```
🔥Contraintes :
* Maximum 10 méthodes par classe
* Maximum 50 lignes par classe
* Maximum 10 classes par namespace

✅Objectif : 
* Limiter les responsabilités des classes
* Faciliter la maintenabilité des classes et méthodes
* Avoir un ensemble de classes (namepace) cohérent
```



# Les classes ne doivent pas contenir plus de deux (ou cinq) variables d'instance

* Moins de dépendances
* Donc plus facile à mocker pour les tests unitaires

Exemple ici avec la limite à 2
```php
class EntityManager
{
    // 🚫 4 attributs
    private EntityRepository $entityRepository;
    private LoggerInterface $logger;
    private MiddlewareInterface $middleware;
    private NotificationService $notificationService;

    public function update(Entity $entity)
    {
        $this->entityRepository->update($entity);

        // 🚫Ces trois traitements pourraient très bien être délocalisés
        //   afin d'éviter de surcharger cette méthode
        //   et pour faciliter l'ajout d'autres traitements plus tard
        $this->logger->debug($entity);
        $this->middleware->sendMessage($entity);
        $this->notificationService->notify($entity);
    }
}
```

```php
class EntityManager
{
    // ✅Moins de dépendances
    // ✅Donc plus facile à mocker pour les tests unitaires 
    private EntityRepository $entityRepository;
    private EventDispatcher $eventDispatcher;

    public function update(Entity $entity)
    {
        $this->entityRepository->update($entity);

        // ✅Il sera très facile d'ajouter un autre traitement 
        // en ajoutant un listener sur cet événement
        $this->eventDispatcher->dispatch(Events::ENTITY_UPDATE, $entity);
    }
}

// ✅Les traitements ont été délocalisés dans 3 listener distincts
// ✅Classes petites et facilement testables
class EntityToLog
{
    private LoggerInterface $logger;

    public function onUpdate(Entity $entity)
    {
        $this->logger->debug($entity);
    }
}

class EntityToMiddleware
{
    private MiddlewareInterface $middleware;

    public function onUpdate(Entity $entity)
    {
        $this->middleware->sendMessage($entity);
    }
}

class EntityNotification
{
    private NotificationService $notificationService;

    public function onUpdate(Entity $entity)
    {
        $this->notificationService->notify($entity);
    }
}
```

# Aucun getter / setter

* Encapsulation des traitements
* Permet de réfléchir sur le principe "Tell, don’t ask"

```php
class Game
{
    private Score $score;

    public function diceRoll(int $score): void
    {
        $actualScore = $this->score->getScore();
        // 🚫 On modifie en dehors de l'objet sa valeur pour ensuite lui "forcer" le résultat
        $newScore = $actualScore + $score;
        $this->score->setScore($newScore);
    }
}

class Score 
{
    private int $score;

    public function getScore(): int
    {
        return $this->score;
    }

    public function setScore(int $score): void
    {
        $this->score = $score;
    }
}
```

```php
class Game
{
    private Score $score;

    public function diceRoll(Score $score): void
    {
        $this->score->addScore($score);
    }
}


class Score 
{
    private int $score;

    public function addScore(Score $score): void
    {
      	// ✅On définit ici la logique
        //   d'addition de score
        $this->score += $score->score;
    }
}
```
