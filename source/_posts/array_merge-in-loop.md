---
extends: _layouts.post
section: content
title: Evitez d'utiliser la fonction array_merge dans une boucle en PHP
date: 2019-10-12
ressources:
    - name: "Nouveautés PHP 5.6"
      link_url: "https://php.net/manual/fr/migration56.new-features.php"
---

Je vois assez souvent dans du code PHP l'utilisation de la fonction `array_merge` dans des boucles `for`/`foreach`/`while` 😱 :

```php
$arraysToMerge = [ [1, 2], [2, 3], [5, 8] ];

$arraysMerged = [];
foreach($arraysToMerge as $array) {
    $arraysMerged = array_merge($arraysMerged, $array);
}
```
Cette habitude est particulièrement mauvaise car **les performances peuvent devenir désastreuses** (surtout sur l'utilisation mémoire).

Depuis PHP 5.6, il y a un nouvel opérateur : **l'opérateur de décomposition** (ou spread operator).

```php
$arraysToMerge = [ [1, 2], [2, 3], [5,8] ];

$arraysMerged = array_merge([], ...$arraysToMerge);
```

* Plus de problème de performance
* BONUS : Plus de boucle `for`/`foreach`/`while`
* BONUS : Traitement effectué en **une ligne**

Regardez maintenant votre base de code, je suis sur que vous pourrez trouver des endroits à améliorer 👩‍💻👨‍💻 !
