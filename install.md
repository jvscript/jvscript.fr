# Installer le site sur votre serveur

Petite documentation pour vous aider à installer l'appli web en local. Ca peut-être chiant mais c'est à faire une seule fois !

## 1. Install a WebServer, MySQL, PHP 7

Vous avez donc besoin d'un web serveur (nginx, ou apache)
et de mysql, php.

+Pour windows vous pouvez utiliser XAMPP(https://www.apachefriends.org/) (apache) ou WNMP (https://www.getwnmp.org/) (nginx)

Nginx est recommandé pour de meilleur performance.

## 2. Web driver Config   

Faitre d'abord votre hostname par ex jvscript.local (fichier host du système). Vous pouvez aussi utiliser localhost

Pour Nginx : 

### Nginx on Windows

	 #===  jvscript.local (laravel) ==== #
	server {
	    server_name jvscript.local;
	    root   C:\Wnmp\html\jvscript.github.io\public;  
	    index index.php; 
	    location / {
	                try_files $uri @rewriteapp;
	          }
	    location @rewriteapp {
	              # rewrite all to index.php
	               rewrite ^(.*)$ /index.php/$1 last;
	         }
	
	    location ~ "^(.+\.php)($|/)" {
	        fastcgi_index index.php;
			fastcgi_pass 127.0.0.1:9000;
			fastcgi_split_path_info ^(.+\.php)(.*)$; 	
			fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
			fastcgi_param SCRIPT_NAME $fastcgi_script_name;
			fastcgi_param PATH_INFO $fastcgi_path_info; 
			fastcgi_read_timeout 300;
			 #include        /etc/nginx/fastcgi_params;
			 include        fastcgi_params;
		}
	}	
	 

### Nginx on Linux

	#===  jvscript.local (laravel) ==== #
	server {
	    server_name jvscript.local;
	    root   /var/www/jvscript.github.io/public;  
	    index index.php; 
	    location / {
	                try_files $uri @rewriteapp;
	          }
	    location @rewriteapp {
	              # rewrite all to index.php
	               rewrite ^(.*)$ /index.php/$1 last;
	         }
	
	    location ~ "^(.+\.php)($|/)" {
	        fastcgi_index index.php;
			fastcgi_split_path_info ^(.+\.php)(.*)$; 	
			fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
			fastcgi_param SCRIPT_NAME $fastcgi_script_name;
			fastcgi_param PATH_INFO $fastcgi_path_info; 
			fastcgi_read_timeout 300;   
			include        /etc/nginx/fastcgi_params;
		}
	}	


### WAMP (apache)  :

	<VirtualHost *:80>
	DocumentRoot "C:\wamp\www\jvscript.github.io\public"
	ServerName jvscript.local
	ServerAlias jvscript.local
	ErrorLog "logs/jvscript.local.local-error.log"
	CustomLog "logs/jvscript.local.local-access.log" common
	</VirtualHost>


## 3. BDD mysql (utile quand le site sera dynamique)

Créer votre BDD 'jvscript' et son user 

    CREATE USER 'jvscript'@'%' IDENTIFIED BY 'password';
    GRANT USAGE ON * . * TO 'jvscript'@'%' IDENTIFIED BY 'password';
    GRANT USAGE ON * . * TO 'jvscript'@'localhost' IDENTIFIED BY 'password';
    CREATE DATABASE IF NOT EXISTS `jvscript` ;
    GRANT ALL PRIVILEGES ON `jvscript` . * TO 'jvscript'@'%';


## 4. Installer composer (Dependency Manager for PHP)

[https://getcomposer.org/](https://getcomposer.org/)

On en a besoin pour la suite.

## 5. Install the app
    
    (Placez vous dans votre dossier du server web)
    git clone https://github.com/jvscript/jvscript.github.io.git
    
    ## move to app folder
    cd jvscript.github.io/
	composer install
    composer update
    
    ## write .env file and set APP_URL, DB_USERNAME,DB_PASSWORD inside this file
    cp .env.example .env
    
    php artisan key:generate

	php artisan migrate
 

### Droit d'écriture de l'appli sous linux si besoin
 
	sudo chown -R www-data:www-data /var/www/jvscript.github.io.git
	## chmod for php to write 
	chmod 777 -R /var/www/jvscript.github.io.git/storage/
 
 
## 6. Tester

Aller sous http://jvscript.local  (votre hostname) et vous devriez voir l'application