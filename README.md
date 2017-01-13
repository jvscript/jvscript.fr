# jvscript.github.io

<p align="center">
<img src='http://puu.sh/tjAVC/4574a31cbf.png' /> 
</p>
<p align="center">
Le site regroupant les scripts JVC
</p>

## Comment déployer l'appli (laravel) en locale

Allez lire le guide [install.md](install.md)

## Comment contribuer

### Thème CSS

Le thème est présent ici : `/public/assets/stylesheets/jvsticker.css`

Pour le modifier editez ce fichier de variable `/public/assets/stylesheets/bootstrap/_variables.scss`

Et compilez avec sass : 

       sass jvscript.scss jvscript.css


### Vues html

Les vues sont dans `/ressources/views`

### Les url de l'appli

Elles sont définies dans le routeur : `/routes/web.php` et pointe soit directement vers une vue, ou vers un controller.

## Todolist (site statique)

### Web Design 

- [ ] Maquetage du site

### Vue 

- [ ] Faire la page html (base thème boostrap dark)
- [ ] Intégration de la maquette (html/css)

### Contenu du site

- [ ] Référencer les scripts JVC les plus utiles (Risibank, JVCStickers++, SpawnKill...)

### Communication

- [ ] Inviter les développeurs de scripts à mettre leur code dans l'organization : https://github.com/jvscript 

----------

## Todolist (site dynamique)

### Prérequis

- [ ] Faire les modèles de données (users, scripts, skins, categories, commentaires, tags)

### Fonctionnalités 

- [ ] Connexion (avec FB,Twitter, Github connect ?)
- [ ] Inscription
- [ ] Ajout de script 
- [ ] Ajout de Skin JVC
- [ ] Administration (validation des scripts/style)
- [ ] Recherche de script/skin
- [ ] Notation de script
- [ ] Commentaires 
- [ ] Tags
- [ ] Sensibilité du script (safe, danger, bannable)
