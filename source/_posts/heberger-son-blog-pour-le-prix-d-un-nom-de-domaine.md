---
extends: _layouts.post
section: content
title: Héberger son blog pour le prix d'un nom de domaine
date: 2019-09-29
ressources:
    - name: Gatsby
      link_url: https://www.gatsbyjs.org
    - name: Documentation de Gatsby
      link_url: https://www.gatsbyjs.org/docs
    - name: Starter
      link_url: https://www.gatsbyjs.org/starters/?v=2
    - name: GitHub
      link_url: https://www.github.com
    - name: Netlify
      link_url: https://netlify.com
    - name: Mettre en place son domaine sur Netlify
      link_url: https://www.netlify.com/docs/custom-domains
---

J’ai eu plusieurs blogs, que ce soit pour du perso, de la photo, de l’informatique et je ne me posais pas trop de question pour l’hébergement : serveur dédié (kimsufi ou VPS chez OVH), base mysql et l’incontournable WordPress. J'ai fait le test aussi de Ghost, plateforme open-source de blog tournant sur du NodeJS. Mais **j’en ai eu marre de payer et surtout de gérer ces serveurs** : apache / nginx / mysql / sauvegardes / sécurité / supervision. Alors j’ai commencé à regarder du côté des **générateurs de sites statiques et de leur hébergement**. Et j’ai trouvé (pour le moment) mon bonheur.

Ce n’est pas le choix qui manque : Jekyll, Hugo, Gatsby, Next.js... Jekyll a l’avantage d’être pris en charge nativement par GitHub mais j’avais fait quelques tests à l’époque et je n’avais pas été convaincu (je ne me rappelle plus pourquoi...). Puis j’ai découvert **Gatsby, générateur de site statique basé sur React**. Et après avoir lu un peu la doc et fait le tutoriel, je me suis laissé convaincre.

La documentation et les tutos sont assez bien faits, et il y a même pas mal de **dépôts GitHub avec des bases de projets (appelés Starter)**. Je vous propose ci-dessous **un petit tuto pour mettre en place rapidement un blog**.

<div class="my-4 bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
  <p class="text-sm font-bold">Ce tuto ne traitera pas de la migration du contenu d'un blog WordPress ou autre à Gatsby. Pour mon blog, ayant assez peu de contenu à migrer, j'ai tout fait manuellement (et puis ça permet de faire le tri).</p>
</div>

# Le blog starter de Gatsby

Pour mettre en place un blog, Gatsby propose un starter assez simple.

* Tout d’abord on install la CLI de Gatsby
```bash
npm install -g gatsby-cli
```

* Puis on instancie le starter
```bash
gatsby new my-blog https://github.com/gatsbyjs/gatsby-starter-blog
cd my-blog
rm -rf .git
git init
gatsby develop
```

* Se rendre sur http://localhost:8000

![un starter simple](/assets/img/posts/heberger-son-blog-pour-le-prix-d-un-nom-de-domaine/starter.png)

# Personnalisation du starter
* Modifier le titre, l'auteur, la description et l'url du blog en éditant le fichier `gatsby-config.js`
```js
module.exports = {
  siteMetadata: {
    title: `Gatsby Starter Blog`,
    author: `Kyle Mathews`,
    description: `A starter blog demonstrating what Gatsby can do.`,
    siteUrl: `https://gatsby-starter-blog-demo.netlify.com/`,
    social: {
      twitter: `kylemathews`,
    },
  }
```

* Remplacer la photo de profil situé dans `content/assets/profile-pic.jpg`
* Changer la description du profil

```bash
nano src/components/bio.js
```

Modifier le texte
```js
Written by <strong>{author}</strong> who lives and works in San
        Francisco building useful things.
        {` `}
        <a href={`https://twitter.com/${social.twitter}`}>
          You should follow him on Twitter
        </a>
```

Si vous ne voyez pas les modifications apparaître :
* arrêter l'exécution du `gatsby develop`
* Supprimer le répertoire `public`
* relancer `gatsby develop`


#Ecriture des articles

* Tous les articles présen sur cette page se situent dans le dossier `content/blog`, un répertoire par article.
* Chaque dossier contient un fichier `index.md` qui est le contenu de l’article.

Il suffit donc de modifier ou supprimer les répertoires existant et d’écrire son premier article

```
rm -rf content/blog/*
mkdir content/blog/mon-post
vi content/blog/mon-post/index.md
```

```

---
title: J'ecris mon article
date: "2019-09-20T22:00:00.284Z"
description: "Mon premier article"
---

Voici mon premier article
```

Voilà nous avons une première version très simple mais qui permet déjà de publier du contenu.

![Version simple du blog](/assets/img/posts/heberger-son-blog-pour-le-prix-d-un-nom-de-domaine/mon-blog.png)

**Nous avons déjà une bonne base pour commencer l’écriture d’articles sur notre blog. Nous allons maintenant le versionner sur Github pour pouvoir ensuite le déployer automatiquement.**

# Versionning sur Github

Le plus simple est de créer un dépot sur votre compte [GitHub](https://www.github.com) et de pousser votre code dessus.

![Créer un dépot](/assets/img/posts/heberger-son-blog-pour-le-prix-d-un-nom-de-domaine/github.png)

Revenir à la racine du projet
```
git add .
git commit -m 'Initial commit'
```

Lier votre dépot Git local à celui créer sur Github (changer l'url ci-dessous par la votre)
```
git remote add origin https://github.com/jimfrance/gatsby-develop.git
git push -u origin master
```

# Déploiement sur Netlify

M’intéressant au « serverless » depuis quelques temps, j’ai tout d’abord découvert Now de la société Zeit. Et peu de temps après, je suis tombé sur Netlify, qui permet de déployer des sites statiques super facilement. Il y a en plus un plan gratuit assez généreux :
* Gestion du https avec Let's Encrypt,
* Intégration avec GitHub,
* Déploiement continue,
* Prévisualisation de déploiement
* Possibilité de lié son projet avec un domaine qu’on possèderait déjà.

Donc pour déployer notre blog, rien de plus simple :
* Se créer un compte sur [Netlify](https://www.netlify.com) (personnellement j'ai utilisé l'authentification GitHub)
* Lors de la création du compte, Authoriser Netlify à accéder à votre compte GitHub
  ![Autoriser Netlify](/assets/img/posts/heberger-son-blog-pour-le-prix-d-un-nom-de-domaine/netlify-account-01.png)

* Ajouter un nouveau site depuis GitHub en cliquant sur `New site From Git`
  ![Ajouter un site depuis github](/assets/img/posts/heberger-son-blog-pour-le-prix-d-un-nom-de-domaine/netlify-add-site-01.png)
* Autoriser de nouveau Netlify à accéder à des informations de votre compte GitHub
  ![Autoriser Netlify](/assets/img/posts/heberger-son-blog-pour-le-prix-d-un-nom-de-domaine/netlify-account-02.png)
* Sélectionner le dépôt que vous voulez déployer et cliquer sur Install
  ![Dépôt à déployer](/assets/img/posts/heberger-son-blog-pour-le-prix-d-un-nom-de-domaine/netlify-add-site-02.png)
* Sélectionner le dépôt sur l'interface de Netlify
* Dans la dernière étape `Build options, and deploy!`, Netlify va automatiquement mettre les bonnes informations
  ![Options de build](/assets/img/posts/heberger-son-blog-pour-le-prix-d-un-nom-de-domaine/netlify-add-site-03.png)
* Cliquer sur `Deploy site`

Le site est en cours de déploiement. Une fois terminé, une adresse sera fournie pour voir le site

![Build terminé](/assets/img/posts/heberger-son-blog-pour-le-prix-d-un-nom-de-domaine/netlify-build-01.png)
![Blog online](/assets/img/posts/heberger-son-blog-pour-le-prix-d-un-nom-de-domaine/netlify-build-02.png)


# Mettre son propre nom de domain

Le dashboard de Netlify est tellement bien fait qu'une fois votre site déloyé, il vous propose de mettre votre propre nom de domaine. Vous avez le choix entre en acheter un auprès de Netlify ou d'utiliser un que vous possédez déjà.
![Custom domain](/assets/img/posts/heberger-son-blog-pour-le-prix-d-un-nom-de-domaine/netlify-custom-domain-01.png)

Je vous renvoi vers [la documentation de Netlify](https://www.netlify.com/docs/custom-domains) pour réaliser les différentes étapes. Personnellement, j'ai déjà mon nom de domaine acheté chez OVH.

# Passer en HTTPS

Une fois votre nom de domain personnalisé mise en place, Netlify vous propose de générer pour vous un certificat HTTPS (étape 3 du dashboard). Je vous conseille vraiment de le faire, la procédure est très simple.


# Le mot de la fin

J'ai décrit ici les différentes étapes pour déployer à moindre frais un blog sur internet.

**Pour résumer :**
* Démarrer avec le starter blog de Gatsby et le personnaliser
* Versionner le projet sur GitHub
* Déployer le blog via Netlify
* Mettre en place un nom de domaine personnalisé et un certification HTTPS

**Plus de serveur à maintenir, aucune sauvegarde à faire, vous pouvez vous concentrer sur l’essentiel : le contenu du blog.** Et la seule chose que j'ai payé pour le moment est mon nom de domaine !
