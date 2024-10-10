# jvscript.fr

<p align="center">
<img src='https://jvscript.fr/assets/images/banniere2-min.png' width='450px' /> 
</p>
<p align="center">
Le site regroupant les scripts JVC


## Comment déployer l'appli (laravel) en local

Allez lire le guide [install.md](install.md)

## Comment mettre à jour l'appli
  
      git pull && composer update && php artisan migrate

## Comment contribuer

### Thème CSS

Le thème est présent ici : `/public/assets/stylesheets/jvsticker.css`

Pour le modifier editez ce fichier de variable `/public/assets/stylesheets/bootstrap/_variables.scss`

Et compilez avec sass : 

    sh compile-sass.sh


### Vues html

Les vues sont dans `/ressources/views`

### Les url de l'appli

Elles sont définies dans le routeur : `/routes/web.php` et pointe soit directement vers une vue, ou vers un controller.

### Controller

Le controller principal (`JvscriptController.php`) est dans `App\Http\Controllers\`

## Todolist (dev du site)

### [C'est par ici ](https://github.com/jvscript/jvscript.github.io/projects/1)


## Phpunit

  GRANT ALL PRIVILEGES ON `jvscript\_testing`.* TO 'travis'@'localhost';
