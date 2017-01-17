# jvscript.github.io

<p align="center">
<img src='http://puu.sh/tjAVC/4574a31cbf.png' /> 
</p>
<p align="center">
Le site regroupant les scripts JVC
</p>

## Comment déployer l'appli (laravel) en local

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

### Controller

Le controller principal (`JvscriptController.php`) est dans `App\Http\Controllers\`

## Todolist (dev du site)

### Web Design 

- [ ] Maquetage du site

### Vue 

- [x] Faire la page html (base thème boostrap dark)
- [ ] Finir le design de toute les pages

### Contenu du site

- [ ] Référencer les scripts JVC les plus utiles (Risibank, JVCStickers++, SpawnKill...)

### Communication

- [ ] Inviter les développeurs de scripts à mettre leur code dans l'organization : https://github.com/jvscript 

### Modèles de données

- [x] users
- [x] scripts
- [x] tags
- [ ] skins
- [ ] commentaires
- [ ] categories

### Fonctionnalités 

- [x] Ajout de script 
- [x] Notation de script
- [ ] Recherche de script/skin
- [ ] Connexion (avec FB,Twitter, Github connect ?)
- [ ] Inscription
- [ ] Ajout de Skin JVC
- [ ] Administration (validation des scripts/style)
- [ ] Commentaires 
- [ ] Tags
- [ ] Sensibilité du script (safe, danger, bannable)
