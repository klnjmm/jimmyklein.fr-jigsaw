---
extends: _layouts.post
section: content
title: Quelques astuces pour GIT
date: 2020-05-10
---

Si vous utilisez Git au quotidien, vous savez que cet outil regorge de commandes et de différentes options de configuration. Je vous propose ici de lister quelques astuces que j'utilise.

# Configurer son nom d'utilisateur et son e-mail.



```bash
git config --global user.name "Darth Vader"
git config --global user.email "darth-vader@deathstar.space"
```

Je commence volontairement assez simple mais c'est une manipulation qui m'arrive de faire assez souvent, mon ordinateur me servant à gérer plusieurs projets avec des identités différentes.
Donc si vous avez des informations différentes pour un projet, vous pouvez surchargez cette déclaration au niveau de votre projet :

```bash
cd ~/dev/my_project
git config user.name "Luke Skywalker"
git config user.email "luke-skywalker@jedi.force"
```

# Ignorer globalement certains fichiers

Je vous recommande de mettre en place un fichier `.gitignore` global à votre environnement de développement afin d'ignorer les fichiers liés à votre manière de travailler (IDE utilisé par exemple).
Cela permet d'éviter de retrouver tous les IDE de la terre dans les fichiers .gitignore de vos projets.

```bash
vi ~/.gitignore
```

Exemple avec le répertoire de PHPStorm / Intellij
```bash
.idea
```

Configuration de git
```bash
git config --global core.excludesfile ~/.gitignore
```

Cliquez sur le lien suivant pour retrouver une collection de modèle de fichier `gitignore` :  https://github.com/github/gitignore

# Choix de l'éditeur de commit

Si vous êtes allérgiques à `vi`, demandez à git d'utiliser un autre éditeur pour vos commits

```bash
# Utilisation de nano par exemple
git config --global core.editor nano

# Vous pouvez aussi choisir un outil graphique
# Visual Studio Code
git config --global core.editor "code --wait"

# Sublime text
git config --global core.editor "subl -n -w"

# Atom
git config --global core.editor "atom --wait"

```

# Modifier le format d'affichage des logs par défaut

Si le format de la commande `git log` ne vous convient pas, vous pouvez le changer ou en définir un personnalisé

Il existe plusieurs formats prédéfinis : `oneline` | `short` | `medium` | `full` | `fuller` | `reference` | `email` | `raw`

```bash
# Affichage par défaut sur une ligne
$ git config --global format.pretty oneline

# On peut aussi définir son propre format d'affichage de log
$ git config --global format.pretty "format:%h%x09%an"

# Et l'enregistrer avec un nom
$ git config --global pretty.my-custom-log-format "format:%h%x09%an"


# Ce qui permet de soit de l'utiliser à la demande
$ git log --pretty=my-custom-log-format

# Soit de le définir aussi comme configuration par défaut
$ git config --global format.pretty my-custom-log-format
```

La liste des formats et des placeholders est disponible : https://git-scm.com/docs/pretty-formats



# Utiliser `fixup` pour corriger un commit précédent et avoir un historique plus clean

Vous connaissez surement l'option `--amend` afin de modifier le dernier commit effectuée. Mais connaissez vous l'option `--fixup` et l'`autosquash` pour modifier n'importe quel commit précédent.

<div class="my-4 bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
  <p class="text-sm font-bold">Attention : l'autosquash va modifier les références des commits de votre branche.<br/>A déconseiller si vous n'êtes pas à l'aise avec les rebase et ce que ça induit<br/>Vous pouvez le faire sereinement si vous n'avez encore rien poussé (push) sur des remotes distants.
  </p>
</div>

Prenons l'exemple où vous allez ajouter un fichier README à votre projet.

Liste des commits sur votre branche où vous êtes entrain d'écrire le fichier
```bash
git log
```

```bash
b58faee269c Ajouter la partie contribution dans le README
18a11d43eb2 Ajouter la partie déploiement dans le README
8385fc0cd1a Initial commit
```


Et là vous constatez une erreur dans le commit d'ajout de la partie déploiement (faute d'orthographe par exemple) (ref commit : 18a11d43eb2).

On corrige l'erreur et on commit avec l'option --fixup

```bash
git commit --fixup 18a11d43eb2
94ae1b02f4a fixup! Ajouter la partie déploiement dans le README
```

Le git log ressemble désormais à :
```bash
git log
94ae1b02f4a fixup! Ajouter la partie déploiement dans le README
b58faee269c Ajouter la partie contribution dans le README
18a11d43eb2 Ajouter la partie déploiement dans le README
8385fc0cd1a Initial commit
```

On peut ensuite fusionner le commit d'origine avec le commit de fixup
```bash
git rebase -i --autosquash 8385fc0cd1a
```

Le commit de "fixup" a été squashé dans le commit de la partie déploiement. On voit bien ici la modification des références des commits.

```bash
git log
fc5c36d4183 Ajouter la partie contribution dans le README
0a94be69d2c Ajouter la partie déploiement dans le README
8385fc0cd1a Initial commit
```


# Uniformiser les messages de commit

Git permet de pré-remplir les messages de commit à partir d'un modèle

```bash
vi ~/.gitmessage
```

```bash
Short Title

IssueNumber

Why :
```

```bash
git config --global commit.template "~/.gitmessage"
```


# Nouvelle commande git switch

Depuis la version 2.23, une nouvelle commande est dipsonible pour changer de branche

Habituellement, on utilise `checkout`
```bash
git checkout my_branch
Switched to branch 'my_branch'
```

On peut désormais utiliser la commande `switch`
```bash
git switch my_branch
Switched to branch 'my_branch'
```

Et si on veut créer une nouvelle branche et `switcher` dessus
```bash
git switch -c my_new_branch # équivalent à git checkout -b my_new_branch
Switched to a new branch 'my_new_branch'
```

# Revenir à la branche précédente

Si vous êtes un peu paresseux comme moi, vous pouvez revenir sur la branche précédente utilisant la commande `git switch -`

```bash
(master) $ git switch my_branch
Switched to branch 'my_branch'

(my_branch) $ git switch -
Switched to branch 'master'
```

Cela fonctionne aussi en faisant `git checkout -`

Bonus (merci à [Julien Deniau pour l'astuce](https://twitter.com/j_deniau/status/1259209903077494792)):
Vous pouvez aussi faire un rebase ou un merge de la même manière :

```bash
(master) $ git switch my_branch
(my_branch) $ git rebase -
```

# Nouvelle commande git restore
Si vous voulez restaurer un fichier modifié dans son état d'origine, une nouvelle commande a été ajoutée depuis la version 2.23 de git : `restore`

```bash
# Habituellement on utilise `git checkout -- <file>`
(master) $ git status
Changes not staged for commit:
    modified:   README.md
    
(master) $ git checkout -- README.md
(master) $ git status
nothing to commit, working tree clean

# On peut désormais utiliser la command `git restore`
(master) $ git status
Changes not staged for commit:
    modified:   README.md
    
(master) $ git restore README.md
(master) $ git status
nothing to commit, working tree clean
```
