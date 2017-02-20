<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;

class scriptsTest extends TestCase {
    /*
     * - connexion -> admin 
     * - ajout script
     * - visualiser le script guest 404
     * - visualiser le script > admin
     * - créate compte owner
     * - éditer le script en admin : edit and change owner     
     * - accès/edition du script par owner
     * - accès page admin (guest) > 404 
     * - valider le script 
     * - visualiser le script (guest)
     * - noter le script
     * - installer le script 
     * - refuser le script (admin)     
     * - supprimer le script (admin)     
     * _TODO : page mescripts owner
     */

    public function testHomepage() {
        $this->visit('/')
                ->see('jvscript.io');
    }

    /**
     * Connexion superadmin
     */
    public function testConnexion($login = 'superadmin', $password = 'superadmin') {
        $this->visit('/')
                ->click('Connexion')
                ->seePageIs('/login')
                ->type($login, 'name')
                ->type($password, 'password')
                ->press('Se connecter')
                ->seePageIs('/')
                ->see("Bonjour $login");
    }

    public function testAjoutScript() {
        $this->testConnexion();
        $this->visit('/script/ajout')
                ->type('nom du script', 'name')
                ->type('description', 'description')
                ->type('auteur du script', 'autor')
                ->type('https://github.com/vitoo/jvc-mp-plus/raw/master/jvc-mp-plus.user.js', 'js_url')
                ->type('https://github.com/jvscript/jvscript.github.io', 'repo_url')
                ->type('https://www.jeuxvideo.com/forums/42-51-49907271-1-0-1-0-si-vous-avez-la-possibilite-d-etre-un-animal.htm', 'topic_url')
                ->type('https://arteriesshaking.bandcamp.com/album/burning-streets', 'website_url')
                ->type('http://image.noelshack.com/fichiers/2016/39/1475401891-valls2.gif', 'photo_url')
                ->type('https://www.paypal.me/vplancke/', 'don_url')
                ->press('Ajouter')
                ->seePageIs('/script/ajout')
                ->see('Merci, votre script est en attente de validation.');
    }

    /**
     * Script non validé en guest
     */
    public function testVoirScript404() {
        $response = $this->call('GET', '/script/nom-du-script');
        $this->assertEquals(404, $response->status());
    }

    /**
     * Voir Script non validé avaec les droits admin
     */
    public function testVoirScriptAdmin() {
        $this->testConnexion();
        $this->visit('/script/nom-du-script')
                ->see('nom du script')
                ->see('auteur du script')
                ->see('https://github.com/jvscript/jvscript.github.io')
                ->see('https://www.jeuxvideo.com/forums/42-51-49907271-1-0-1-0-si-vous-avez-la-possibilite-d-etre-un-animal.htm')
                ->see('https://arteriesshaking.bandcamp.com/album/burning-streets')
                ->see('http://image.noelshack.com/fichiers/2016/39/1475401891-valls2.gif')
                ->see('https://www.paypal.me/vplancke/');
    }

    /**
     * Inscription compte owner
     */
    public function testInscriptionOwner() {
        $this->visit('/')
                ->click('Inscription')
                ->type('owner', 'name')
                ->type('owner@fakemail.com', 'email')
                ->type('password', 'password')
                ->type('password', 'password_confirmation')
                ->press('S\'inscrire')
                ->seePageIs('/');
    }

    /**
     * Editer le script en admin & changer l'owner
     */
    public function testEditerScriptAdmin() {
        $this->testConnexion();
        $this->visit('/script/nom-du-script')
                ->click('Editer')
                ->seePageIs('/script/nom-du-script/edit')
                ->type('desc_edit', 'description')
                ->type('2', 'user_id')
                ->type('2.0', 'version')
                ->type('31/12/2016', 'last_update')
                ->type('owner', 'autor')
                ->press('Editer')
                ->seePageIs('/script/nom-du-script')
                ->see('owner')
                ->see('2.0')
                ->see('31/12/2016')
                ->see('desc_edit')
                ->dontSee('auteur du script')
                ->click('Valider')
                ->see('Ce script a été validé.');
    }

    /**
     * accès au script par owner
     */
    public function testVoirEditerScriptOwner() {
        $this->testConnexion('owner', 'password');
        $this->visit('/script/nom-du-script')
                ->click('Editer')
                ->seePageIs('/script/nom-du-script/edit')
                ->dontSee('Auteur du script')
                ->type('desc_edit_owner', 'description')
                ->press('Editer')
                ->seePageIs('/script/nom-du-script')
                ->see('owner')
                ->see('desc_edit_owner');
    }

    /**
     * Script non validé Sans les droits admin
     */
    public function testGuestAdmin404() {
        $this->visit('/admin')
                ->seePageIs('/login');
    }

    /**
     * accès au script par guest
     */
    public function testVoirScriptGuest() {
        $this->visit('/script/nom-du-script')
                ->seePageIs('/script/nom-du-script')
                ->dontSee('Editer')
                ->dontSee('Valider')
                ->see('owner')
                ->see('desc_edit_owner');
    }

    /**
     * accès au script par guest
     */
    public function testNoterInstallerScriptGuest() {
        $this->visit('/script/note/nom-du-script/5')
                ->seePageIs('/script/nom-du-script')
                ->see('1 votes');

        $this->call('GET', '/script/install/nom-du-script');
        $this->visit('/script/nom-du-script')
                ->see('1 fois');
    }

    public function testRefuserScriptAdmin() {
        $this->testConnexion();
        $this->visit('/script/nom-du-script')
                ->click('Refuser')
                ->seePageIs('/script/nom-du-script')
                ->see('Ce script a été refusé.');
    }

    public function test404Again() {
        $this->testVoirScript404();
    }

    public function testSupprimerScriptAdmin() {
        $this->testConnexion();
        $this->visit('/script/nom-du-script')
                ->click('Supprimer')
                ->seePageIs('/admin');
        $this->testVoirScript404();
    }

}
