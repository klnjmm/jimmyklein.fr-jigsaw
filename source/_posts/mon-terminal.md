---
extends: _layouts.post
section: content
title: Boostez votre terminal !
date: 2021-04-16
---

Le terminal est un élément central dans mon travail de tous les jours. Il était donc important qu'il m'aide à être plus productif.
Je vous présente donc mes trucs et astuces qui améliorent mon quotidien.

<iframe width="960" height="450" src="https://www.youtube-nocookie.com/embed/3ZZ6449yink" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

# Un terminal aux multiples possibilités !

Pendant longtemps, je suis resté sur le terminal par défaut de Mac OS X. Mais au fil du temps, certaines fonctionnalités me manquaient. Mon choix s'est donc porté sur [iterm2](https://iterm2.com).

## Split pane
Simple fonctionnalité très pratique, pouvoir diviser son terminal en plusieurs (`CMD+D` pour une séparation verticale, `SHIFT+CMD+D` pour une séparation horizontale)

![Split pane](/assets/img/posts/mon-terminal/split-pane.png)

Vous pouvez ensuite naviguer entre les panneaux en appuyant sur `OPT+CMD+Flèches` et fermer un panneau avec `CMD+W`.

## Hotkey Window
Le "Hotkey Window" vous permet d'avoir toujours sous la main un terminal de disponible. Vous définissez un raccourci, qui une fois actionné, vous affichera un terminal en haut de votre écran.

![Hotkey](/assets/img/posts/mon-terminal/hotkey.png)

## Historique de copier / coller
Chose que j'ai découvert assez récemment, iterm2 propose un gestionnaire de copier/coller. Tout texte que vous copiez dans le terminal est disponible ensuite dans ce même terminal via `CMD+SHIFT+H`. Un must !

![Copy / Paste](/assets/img/posts/mon-terminal/copy-paste.png)

## Themes
[De nombreux thèmes sont disponibles](https://iterm2colorschemes.com) pour iterm2. Personnellement j'utilise [un thème light](https://github.com/jeffkreeftmeijer/appsignal.terminal) (après plusieurs années à avoir un thème dark sur mon terminal ou mon IDE).

iterm2 propose de nombreuses autres fonctionnalités que je n'utilise pas mais qui pourraient peut-être vous être utile. Je vous laisse les découvrir [directement sur le site](https://iterm2.com).

# Un shell sur-vitaminée et extensible
Une fois l'application de terminal choisie, il a fallu choisir son shell. Et après un peu de recherche, le choix a été une évidence : `zsh` et [oh-my-zsh](https://ohmyz.sh).

oh-my-zsh est un framework qui va permettre de faciliter la configuration de `zsh` : ajout de thèmes, de plug-ins, etc...

## Thèmes
Vous pouvez passer [des heures à chercher et essayer des thèmes](https://github.com/ohmyzsh/ohmyzsh/wiki/Themes) (c'est ce que j'ai fait ^^). Personnellement j'ai pour le moment choisi d'utiliser [spaceship-prompt](https://github.com/denysdovhan/spaceship-prompt) (principalement pour ces petits icônes en fonction des technos :)).

## Alias
oh-my-zsh vient avec un [très grand nombre d'alias et de petit outil très pratique](https://github.com/ohmyzsh/ohmyzsh/wiki/Cheatsheet) :
* `take` : permet de créer un répertoire et de se déplacer directement dans ce nouveau répertoire
* `git` : beaucoup d'alias autour de `git` (`ga` pour `git add`, `gco` pour `git checkout` et de nombreux autres)
* Aide à la saisie pour certaines commandes (`ls -` + `TAB`, `ssh` + `TAB`, etc...)

## Plug-ins
Il existe [un très grand nombre de plug-ins](https://github.com/ohmyzsh/ohmyzsh/wiki/Plugins). Ci-dessous la liste de ceux que j'utilise le plus.

* **[colorize](https://github.com/ohmyzsh/ohmyzsh/tree/master/plugins/colorize)** : colorise la sortie de `cat` et `less` via `ccat` et `cless`

![Colorize](/assets/img/posts/mon-terminal/colorize.png)

* **[jsontools](https://github.com/ohmyzsh/ohmyzsh/tree/master/plugins/jsontools)** : quelques utilitaires autour du `json` comme le fait d'afficher de manière "lisible" une chaîne `json` dans le terminal

* **[zsh-interactive-cd](https://github.com/ohmyzsh/ohmyzsh/tree/master/plugins/zsh-interactive-cd)** : facilite la navigation dans les répertoires en affichant un explorateur directement dans votre terminal.
  ![zsh-interactive-cd](/assets/img/posts/mon-terminal/zsh-interactive-cd.png)

* **[web-search](https://github.com/ohmyzsh/ohmyzsh/tree/master/plugins/web-search)** : ne sortez plus de votre terminal pour lancer une recherche dans votre navigateur !

* **[zsh-autosuggestions](https://github.com/zsh-users/zsh-autosuggestions)** : suggère des commandes lors de la saisie en se basant sur l'historique et l'autocomplétion.
  ![zsh-autosuggestion](/assets/img/posts/mon-terminal/zsh-autosuggestions.png)

* **[chucknorris](https://github.com/ohmyzsh/ohmyzsh/tree/master/plugins/chucknorris)** : plug-in ~~indispensable~~ qui vous affichera une phrase sur Chuck Norris quand vous en aurez besoin.
  ![Chuck Norris](/assets/img/posts/mon-terminal/chucknorris.png)
