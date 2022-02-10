---
extends: _layouts.post
section: content
title: Comment je démarre un projet en PHP
date: 2018-01-18
ressources:
    - name: Template-PHP
      link_url: https://github.com/klnjmm/template-php.git
    - name: Git
      link_url: https://git-scm.com
    - name: PHP Code Sniffer
      link_url: https://github.com/squizlabs/PHP_CodeSniffer
    - name: Atoum
      link_url: https://github.com/atoum/atoum
    - name: Composer
      link_url: https://getcomposer.org
---

Lorsque je commence à développer une nouvelle application, j’ai pris l’habitude de tout de suite effectuer quelques actions et de mettre en place des outils. Je vous propose donc de les partager avec vous.

# Versionner avec Git
Première chose que je fait, je versionne mon projet en local. L’avantage avec git est que cela se fait en une seule commande. Rien de plus simple donc pour initialiser mon projet sous git : je me place dans le répertoire de mon projet et j’exécute la commande suivante :

```
git init
```

Voilà c’est fait ! Je peux désormais versionner mon application au fur et à mesure et revenir en arrière si besoin. Et si je le souhaite, je pourrai le sauvegarder ou le partager plus tard sur un serveur (comme GitHub, par exemple).

# Choisir une norme d’écriture de code

Après quelques années à écrire du code sans réelle règle, je me suis aperçu que lorsqu’on travaillait en équipe, les manières d’écrire le code variaient beaucoup d’une personne à l’autre. Et cela s’est beaucoup plus ressenti lorsque, dans mon job, nous avons commencé à faire de la review de code. Les différentes façons d'écrire rendait la relecture un peu plus difficile. Nous avons donc choisi une norme d’écriture (par langage / techno) que nous suivons désormais.

L’autre avantage est que si vous développez une application open-source, vous pourrez indiquer à vos éventuels contributeurs de respecter la norme choisie afin d'avoir un code uniforme.

J’applique donc désormais tout le temps pour chaque nouveau projet une norme d’écriture. Je passe par plusieurs moyens pour la contrôler :
* Je configure mon IDE pour qu'il analyse mon code en temps réel (j'utilise intelliJ ou PHPStorm)
* Je lance aussi manuellement avant de commiter mon développement un outil qui scanne tout mon code source pour vérifier si la norme choisie est bien appliquée. Exemple en PHP avec PHP_CodeSniffer
```
phpcs src -n --colors --error-severity=1 --standard=vendor/m6web/symfony2-coding-standard/Symfony2
```

L'avantage d'avoir un outil en ligne de commande est qu'il peut être lancé automatiquement via un hook git ou par un serveur d'intégration continue.

# Choisir un outil de tests unitaires

Même si je ne pratique pas strictement le TDD, je mets tout de suite en place un outil de tests unitaires. Tant qu’à être dans la configuration de mon projet, autant le faire même si mes premiers développement ne sont pas forcément testés. Au moins je n’ai aucune excuse pour ne pas écrire de tests.
De la même manière que pour la norme d'écriture, j'exécute mes tests unitaires en ligne de commande et pourrais donc facilement le lancer automatiquement.
Pour le PHP, j'utilise principalement atoum.

# Spécifique pour le PHP : mise en place d'un autoloader de classes

PHP, comme d'autres langages, possède une notion d'espace de nom. Pour citer la documentation :
> Les espaces de noms PHP fournissent un moyen pour regrouper des classes, interfaces, fonctions ou constantes.

Afin de pouvoir utiliser facilement ces espaces de nom, il est nécessaire d'utiliser un autoloader. Et ça tombe bien, un outil permet d'en générer un très facilement : composer.

Composer est un outil de gestion de dépendances qui apporte aussi d'autres fonctionnalités comme la mise en place d'un autoloader. Pour ça, rien de plus simple.

* Initialisation d'un projet avec composer
```
composer init
```

* Ajout de l'autoloader dans le fichier de conf
```
vim composer.json
```
```
"autoload": {
        "psr-4": {
            "Mon\\Namespace\\": "src"
        }
    }
```

* Génération de l'autoloader
```
composer install
```

* Il suffira juste ensuite d'inclure le script généré dans le point d'entrée de notre application
```
include_once('./vendor/autoload.php');
```

# Template prêt à l'emploi
Afin d'éviter à refaire à chaque fois toute cette configuration, j'ai créé un projet template pour PHP : https://github.com/klnjmm/template-php

```
git clone https://github.com/klnjmm/template-php.git
cd template-php
```

Modifier dans le fichier composer.json :
* le nom du namespace (à deux endroits, pour le code source et pour les tests unitaires)
* Les différentes informations générales : nom et description du projet

Puis lancer
```
make init
make up
```

Voilà il ne reste plus qu'à coder !
