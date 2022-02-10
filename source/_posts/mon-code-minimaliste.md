---
extends: _layouts.post
section: content
title: Less is Now
date: 2019-10-28
ressources:
    - name: Travailler avec les tableaux :
      link_name: Refactoring with Collection
      link_url: /refactoring-to-collections
    - name: S'exercer par les contraintes :
      link_name: les objets calisthenics
      link_url: /les-objets-calisthenics
    - name: Le titre de l'article fait référence
      link_name: aux fonds d'écran de The Minimalists
      link_url: https://www.theminimalists.com/wallpapers/
---

Je me suis au fil des années beaucoup intéréssé au clean code, aux objets calisthenics, etc... Et au fur et à mesure du temps, des lectures, des vidéos, des échanges et des katas, mon écriture du code a changé : moins de superflu, code plus parlant (du moins pour moi 😇) et une envie d'aller à l'essentiel. Voici donc ce qui a évolué dans **ma manière d'appréhender le code**.

<div class="my-4 bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
  <p class="text-sm font-bold">Les exemples de cet article sont en PHP, mais la plupart de ce que je décris ci-dessous est applicable à tous les langages.</p>
</div>

# Les commentaires
Commenter son code peut être utile, mais il faut qu'il le soit pour de bonnes raisons. Un commentaire doit dire **pourquoi ça a été fait comme ça** et non pas **ce que le code fait**.

```php
// Si l'utilisateur a au moins 18 ans
if ($user->getAge() >= 18) {
    ...
}
```

Ce commentaire est complètement inutile, c'est une recopie en version litéral de l'instruction `if` qui peut surement vous faire sourir. Mais ça m'arrivait d'écrire ce genre de commentaire et je n'étais pas le seul (il suffisait de voir il y a quelques temps la codebase du projet sur lequel je travail). Regardez dans votre codebase, je suis sur que vous en trouverez.

```php
// Si l'utilisateur est majeur
if ($user->getAge() > 18) {
    ...
}
``` 
Ce commentaire nous en dit un peu plus sur ce que teste cette instruction mais je pense que l'on peut faire encore mieux et supprimer ce commentaire. Plusieurs solutions :
* Mettre une constante à la place de `18`

```php
if ($user->getAge() >= ADULT_AGE) {
    ...
}
```

* Extraire le test dans une fonction.

```php
if (userIsAdult($user)) {
    ...
}
...
...
function userIsAdult(User $user) {
    return $user->getAge() >= ADULT_AGE;
}
```
Cette méthode est utile si vous avez **un test assez compliqué** avec un enchaînement de conditions. Au lieu de mettre un commentaire, **essayez de nommer votre test et de l'extraire dans une fonction.**


* Faire en sorte que ce test soit fait directement dans le code de l'objet.

```php
if ($user->isAdult()) {
    ...
}
...
...
class User
{
    private $age;
    
    const ADULT_AGE = 18;
    ...
    public function isAdult()
    {
        return $this->age >= self::ADULT_AGE;
    }
}
```

# La PHPDoc

PHP devient de plus en plus typé, et honnetêment je n'ai jamais eu à extraire la PHPDoc de mes projets. Désormais pour mes projets **perso**, je ne génère plus la PHPDoc de mes fonctions. Pour reprendre ma classe `User` :

Avant :
```php
class User
{
    private $name;
    private $firstName;
    private $age;
    
    /**
     * User constructor
     * @param string $name;
     * @param string $firstName;
     * @param int $age;
     */
    public function __construct($name, $firstName, $age)
    {
        $this->name = $name;
        $this->firstName = $firstName;
        $this->age = $age;
    }
    
    /**
     * Indique si l'utilisateur est adulte
     * @return bool
     */
    public function isAdult()
    {
         return $this->age >= self::ADULT_AGE;
    }
}
```

Après :
```php
class User
{
    private $name;
    private $firstName;
    private $age;
   
    public function __construct(string $name, string $firstName, int $age)
    {
        $this->name = $name;
        $this->firstName = $firstName;
        $this->age = $age;
    }
    
    public function isAdult(): bool
    {
         return $this->age >= self::ADULT_AGE;
    }
}
```

* **Les noms des méthodes sont assez parlants**, pas besoin de les décrire (surtout quand on voit les commentaires générés par l'IDE du genre `User constructor` !)
* **Les paramètres du constructeur sont typés**, l'IDE pourra très bien indiquer le type de chaque paramètres
* Le type de retour de la méthode est **directement dans le code**
* Et l'avantage est que si un jour un paramètre ou un type de retour doit changer, **on ne risque pas d'oublier de mettre à jour la PHPDoc**. (car oui le problème des commentaires est qu'avec le temps, ils ne correspondent plus au code qui est en dessous, par oubli de mis à jour 😕)

# Les Getter et Setter

L'IDE peut être très pratique mais peut aussi donner de mauvaises habitudes. La génération automatique de getter et setter d'une classe est un bon exemple. On ne sait pas si on va en avoir besoin, mais on va quand même les générer.

![La magie des IDEs](/assets/img/posts/mon-code-minimaliste/getter-setter.jpg)

J'essaye désormais de créer **le minimum possible de getter/setter** (pour ne pas dire aucun si possible):
* Passage des **paramètres directement dans le constructeur**. Au lieu de :

```php
class User
{   // Le constructeur est vide ici pour l'exemple
    public function __construct() {}
    
    public function setName(string $name) {...} 
    public function setFirstName(string $firstName) {...}
    public function setAge(int $age) {...}
}
```
```php
class User
{   
    ...
    
    public function __construct(string $name, string $firstName, int $age)
    {
        $this->name = $name;
        $this->firstName = $firstName;
        $this->age = $age;
    }
}
```

* **L'objet lui même peut avoir ses propres règles métiers**. En reprenant l'exemple du début de l'article sur l'âge de l'utilisateur :

```php
if ($user->getAge() > 18) {
   ...
}
```
```php
class User
{
    ...
    
    public function isAdult(): bool
    {
        return $this->age >= self::ADULT_AGE;
    }
}
```

# Les variables temporaires

Comme tout développeur, j'ai appris à faire des boucles `for`/`foreach`/`while`. Et le plus souvent cela incluait la création de variables temporaires.
Si je reprends le premier exemple de mon article [Refactoring with Collection](/refactoring-to-collections), voici le genre de code que j'avais l'habitude d'écrire avant :
```php
public function doubleAllValue(array $numbers)
{
    $result = [];
    foreach ($numbers as $number) {
        $result[] = $number * 2;
    }

    return $result;
}
```

Nous avons ici une variable temporaire. Si je reprends la définition de wikipédia :

> Une variable temporaire est, dans le domaine de la programmation informatique, une variable dont la durée d'existence est courte (en général limitée à la procédure ou la fonction qui l'utilise). Du fait de sa courte durée de vie, sa portée est souvent limitée.

Et voici maintenant ce que j'ai l'habitude d'écrire :
```php
public function doubleAllValue(array $numbers)
{
    return array_map(function($number) {
        return $number * 2;
    }, $numbers);
}
```

L'utilisation de variables temporaires peut être pratique dans certains cas de debug, c'est après au cas par cas suivant la complexité du code.

# Early return

Lors de mes études, on m'a appris qu'il fallait qu'une méthode n'ait qu'une seule sortie. Mais au fil des années, je me suis aperçu qu'il était possible d'utiliser des **early return** afin d'améliorer la lisibilité du code.

```php
function canDriveACar(User $user)
{
    $canDriveACar = null;
    if ($user->isAdult()) {
        $canDriveACar = true;
    } else {
        if ($user->learnInAccompaniedDriving) {
            $canDriveACar = true;
        } else {
            $canDriveACar = false;
        }
    }
}
```

L'utilisation d'early return va permettre :
* De **réduire l'indentation du code**
* De **supprimer les `else`**
* D'avoir **un code plus lisible**

```php
function canDriveACar(User $user)
{
    if ($user->isAdult()) {
        return true;
    }
    
    if ($user->learnInAccompaniedDriving) {
        return true;
    }
    
    return false;
}
```

# Conclusion
Notre pratique évolue au fil des années, avec parfois des bonnes idées, parfois des moins bonnes (mais on s'en aperçoit bien plus tard !). J'espère que cet article vous aura inspiré. Ou peut-être que vous n'êtes pas d'accord ou en phase avec certaines de mes pratiques. Je vous invite à me retrouver sur [Twitter](https://www.twitter.com/frjimmyklein) pour discuter de tout ça !
