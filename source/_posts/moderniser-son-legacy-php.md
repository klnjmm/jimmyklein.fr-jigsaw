---
extends: _layouts.post
section: content
title: Moderniser son application legacy PHP
date: 2019-09-05
ressources:
    - name: "L'auto prepend file"
      link_url: https://www.php.net/manual/fr/ini.core.php#ini.auto-prepend-file
    - name: Composer
      link_url: https://getcomposer.org
---

Je travail depuis plus de 8 ans sur un produit dont la partie web est développée en PHP. Lorsque je suis arrivé, nous étions en PHP 5.2 et le code était très procédurale. Au fil des années nous avons fait évoluer le produit : mise en place d'un développement plus orientée objet, passage en PHP 5.3. Certains points techniques nous bloquaient pour monter en version supérieur à PHP 5.3. Nous nous retrouvions donc assez rapidement dans l'impossibilité d'utiliser bons nombres de modules et librairies développés par la communauté. Et avec la fin de vie de PHP 5.3 puis l'arrivée de PHP 7.0, il nous fallait absolument pouvoir résoudre ces problèmes.

# Les bloqueurs

Pour pouvoir être compatible avec PHP 5.4+, nous devions trouver un moyen :
* d'émuler l'activation des magic quotes : le produit était développé dès le début avec une fausse bonne directive PHP activée : les `magic quotes`.
* de redéfinir les fonctions mysql_* qui ont été supprimées : nous utilisions cette extension pour toutes nos requêtes en base de données. Mais elle a été rendue obsolète en PHP 5.5.0 puis a été supprimée en PHP 7.0.

# La solution
La seule solution qui nous ait venu à l'esprit au début était de repasser sur l'intégralité du code et de corriger ce qui nous bloquait. Mais autant dire que la charge en terme de développements et de tests de non régression était énorme et même pas envisageable !
Et en cherchant sur le net, un de mes collègues a trouvé une directive PHP qui allait résoudre tous ces problèmes.

# L'auto prepend de PHP
PHP propose une directive qui permet de charger un fichier automatiquement avant chaque exécution d'un script PHP : [`auto_prepend_file`](https://www.php.net/manual/fr/ini.core.php#ini.auto-prepend-file)
C'est grace à cette directive que nous allons pouvoir contourner tous nos problèmes de compatibilité et monter de version PHP.

<div class="my-4 bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
  <p class="text-sm font-bold">Cette solution n'est évidemment pas la plus propre possible mais nous a permis d'avoir un très bon retour sur investissement ! Donc je suis sûr qu'elle peut intéresser certains d'entre vous &#128512;.</p>
</div>

## Ce que peut nous permettre l'`auto_prepend_file`
* Mettre en place facilement un autoloader. Si vous n'utilisez pas encore de namespace et n'avez pas d'autoloader, alors c'est le moment de se lancer. [Composer permet d'en mettre facilement en place](https://getcomposer.org/doc/01-basic-usage.md#autoloading).

Fichier `composer.json`
```json
{
    "autoload": {
        "psr-4": {"MyProject\\": "src/"}
    }
}
```

et notre polyfill :
```php
<?php

require_once(__DIR__.'/../vendor/autoload.php');
```

* Emuler le fonctionnement des magic_quotes. Il existe plusieurs projets sur github permettant d'émuler les magic quotes. Exemple : https://github.com/yidas/php-magic-quotes-gpc.
  Ayant désormais utiliser composer, nous pouvons inclure cette librairie en éxécutant la commande suivante à la racine de notre projet :
```bash
composer require yidas/magic-quotes
```

Aperçu de notre polyfill
```php
<?php

require_once(__DIR__.'/../vendor/autoload.php');

MagicQuotesGpc::init();
```

* Si vous avez un fichier de configuration de l'application que vous incluez sur toutes les pages, vous pouvez l'inclure ici

```php
<?php

require_once(__DIR__.'/../vendor/autoload.php');

MagicQuotesGpc::init();

require_once(__DIR__.'/config.php');
```

* Redéfinition des méthodes mysql_* : nous pouvons redéfinir toutes les méthodes existantes en utilisant un driver encore disponible (mysqli ou PDO)

Exemple (la variable `$link` correspond à une ressource retournée par `mysqli_connect`):
```php
function mysql_query($query, $link)
{
    return mysqli_query($link, $query);
}
```

```php
<?php

require_once(__DIR__.'/../vendor/autoload.php');

MagicQuotesGpc::init();

// Toutes les fonctions mysql_* peuvent être redéclarées dans un fichier à inclure ici
function mysql_query($query, $link)
{
    return mysqli_query($link, $query);
}
```


Nous avons désormais résolu tout nos problèmes de compatibilité. Une fois validé, nous pouvons monter en version de PHP (jusqu'à 7.1 dans notre cas à ce moment là) et moderniser encore notre legacy.


# A nous le web !!
Si vous utilisez composer (et je vous le recommande fortement), vous pouvez désormais importer et utiliser toutes les librairies présentes sur le net. Quelques exemples :

* Un module d'injection de dépendances. Il en existe plusieurs, pour l'exemple ici j'utilise celui de symfony

```bash
composer require symfony/dependency-injection
```

Vous pouvez désormais définir toutes vos dépendances dans un fichier [services.yml](https://symfony.com/doc/current/components/dependency_injection.html#setting-up-the-container-with-configuration-files)


Aperçu de notre polyfill
```php
<?php

require_once(__DIR__.'/../vendor/autoload.php');

MagicQuotesGpc::init();

// Toutes les fonctions mysql_* peuvent être redéclarées dans un fichier à inclure ici
function mysql_query($query, $link)
{
    return mysqli_query($link, $query);
}

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

$container = new ContainerBuilder();
$loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/..'));
$loader->load('services.yml');
```

Nous pouvons donc utiliser notre variable `$container` dans tous nos fichiers de notre projet legacy.

* [Guzzle](https://github.com/guzzle/guzzle) pour les requête HTTP : plus besoin d'écrire à la main ses requêtes HTTP avec curl.

* [Ramsey/UUID](https://github.com/ramsey/uuid) pour générer des UUID.

Et bien d'autres...

# Mise en place sur un projet web
Afin que cette clause soit prise en compte, elle peut-être appliquée :
* globalement dans le fichier de configuration `php.ini` de votre serveur
* localement (depuis PHP 5.3) en définissant un fichier `.user.ini` à la racine de votre projet (exemple : `/var/www/my_project/.user.ini`)

```bash
auto_prepend_file = /var/www/my_project/polyfill-php-compatibility.php
```

Le fichier `polyfill-php-compatibility.php` sera donc exécuté avant tout autre fichier PHP situé dans le projet my_project


# Ligne de commande
Il est possible de spécifier cette directive lors de l'exécution en ligne de commande d'un script php
```bash
php -d auto_prepend_file="/var/www/my_project/polyfill-php-compatibility.php" mon_script.php
```

J'espère que cet article vous donnera des idées pour moderniser vos vieux projets PHP. Et si vous avez des questions, contactez-moi sur [twitter](https://twitter.com/frjimmyklein).
