---
extends: _layouts.post
section: content
title: 5 mauvaises habitudes à perdre en PHP
date: 2020-06-13
---

Je fais quotidiennement de la revue de code. Et je vois assez fréquemment les mêmes erreurs.

# Tester qu'un tableau n'est pas vide avant de boucler dessus

```php
$items = [];
// ...
if (count($items) > 0) {
    foreach ($items as $item) {
        // process on $item ...
    }
}
```

Les boucles `foreach` ou les méthodes sur les tableaux (`array_*`) savent gérer les cas où un tableaux est vide.
* Inutile de tester son contenu avant
* Un niveau d'indentation en moins

```php
$items = [];
// ...
foreach ($items as $item) {
    // process on $item ...
}
```

*D'ailleurs, si vous voulez découvrir comment coder sans faire de boucle `for`/`foreach`/`while`, je vous recommande [mon article sur les collections](/refactoring-to-collections/).*

# Encapsuler tout le contenu d'une méthode dans un if

```php
function foo(User $user) {
    if (!$user->isDisabled()) {
        // ...
        // long process
        // ...
    }
}
```

Celui-ci n'est pas spécifique à PHP mais je le vois tellement souvent que je me devais de le mettre. J'avais déjà parlé [dans mon article sur les objets calisthenics](/les-objets-calisthenics/) et [dans celui sur mon code minimaliste](/mon-code-minimaliste/) de la possibilité de réduire le niveau d'indentation du code en utilisant les **early return**.
Tout le corps "utile" de la fonction est maintenant au **premier niveau d'indentation**.

```php
function foo(User $user) {
    if ($user->isDisabled()) {
        return;
    }

    // ...
    // long process
    // ...
}
```

# Appeler plusieurs fois la méthode isset

```php
$a = null;
$b = null;
$c = null;
// ...

if (!isset($a) || !isset($b) || !isset($c)) {
    throw new Exception("undefined variable");
}

// or

if (isset($a) && isset($b) && isset($c) {
    // process with $a, $b et $c
}

// or 

$items = [];
//...
if (isset($items['user']) && isset($items['user']['id']) {
    // process with $items['user']['id']
}
```

Il est fréquent de devoir vérifier qu'une variable est définie (et non égale à `null`). En PHP la méthode `isset` est là pour ça. Et, magie magie, **elle peut prendre plusieurs valeurs en argument** !

```php
$a = null;
$b = null;
$c = null;
// ...

if (!isset($a, $b, $c)) {
    throw new Exception("undefined variable");
}

// or

if (isset($a, $b, $c)) {
    // process with $a, $b et $c
}

// or 

$items = [];
//...
if (isset($items['user'], $items['user']['id'])) {
    // process with $items['user']['id']
}
```

# Combiner la méthode echo avec sprintf

```php
$name = "John Doe";
echo sprintf('Bonjour %s', $name);
```

Ce bout de code peut prêter à sourire mais il m'arrivait de l'écrire il y a encore quelques temps. Et je le vois encore pas mal ! Au lieu de combiner `echo` et `sprintf`, on peut tout simplement utiliser la méthode `printf`.

```php
$name = "John Doe";
printf('Bonjour %s', $name);
```

# Vérifier la présence d'une clé dans un tableau en combinant deux méthodes
```php
$items = [
    'one_key' => 'John',
    'search_key' => 'Jane',
];

if (in_array('search_key', array_keys($items))) {
    // process
}
```

Dernière erreur que je vois assez fréquemment, c'est l'utilisation conjointe de `in_array` et de `array_keys`. Tout cela peut être remplacé en utilisant la méthode `array_key_exists`.

```php
$items = [
    'one_key' => 'John',
    'search_key' => 'Jane',
];

if (array_key_exists('search_key', $items)) {
    // process
}
```

On peut aussi utiliser `isset` qui vérifiera en plus que la valeur n'est pas `null`.
```php 
if (isset($items['search_key'])) {
    // process
}
```
