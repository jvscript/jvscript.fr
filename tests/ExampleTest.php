<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase {

    use DatabaseMigrations;

    /*
     * _TODO : writes tests :
     * - ajout script
     * - connexion -> admin 
     * - visualiser le script
     * - éditer le script
     * - valider le script
     * - visualiser le script (guest)
     * - accès page admin (guest)
     * - noter le script
     * - installer le script 
     * - refuser le script (admin)     * 
     */

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testHomepage() {
        $this->visit('/')
                ->see('jvscript.io');
    }

    /**
     * connexion admin
     */
    public function testConnexion() {
        $this->visit('/')
                ->click('Connexion')
                ->seePageIs('/login')
                ->type('superadmin', 'name')
                ->type('superadmin', 'password')
                ->press('Se connecter')
                ->seePageIs('/')
                ->see('Bonjour superadmin');
    }

    public function testAjoutScript() {
        $this->testConnexion();
        $this->visit('/script/ajout')
                ->type('nom du script', 'name')
                ->type('description', 'description')
                ->type('auteur du script', 'autor')
                ->type('https://github.com/jvscript/jvscript.github.io/truc.js', 'js_url')
                ->type('https://github.com/jvscript/jvscript.github.io', 'repo_url')
                ->type('https://www.jeuxvideo.com/forums/42-51-49907271-1-0-1-0-si-vous-avez-la-possibilite-d-etre-un-animal.htm', 'topic_url')
                ->type('https://arteriesshaking.bandcamp.com/album/burning-streets', 'website_url')
                ->type('http://image.noelshack.com/fichiers/2016/39/1475401891-valls2.gif', 'photo_url')
                ->type('https://www.paypal.me/vplancke/', 'don_url')
                ->press('Ajouter')
                ->see('Merci, votre script est en attente de validation.')
                ->seePageIs('/script/ajout');
    }

}
