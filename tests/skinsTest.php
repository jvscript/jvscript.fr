<?php

class skinsTest extends BrowserKitTestCase
{
    /*
     * - connexion -> admin 
     * - ajout skin
     * - visualiser le skin guest 404
     * - visualiser le skin > admin
     * - créate compte owner
     * - éditer le skin en admin : edit and change owner     
     * - accès/edition du skin par owner
     * - accès page admin (guest) > 404 
     * - valider le skin 
     * - visualiser le skin (guest)
     * - noter le skin
     * - installer le skin 
     * - refuser le skin (admin)     
     * - supprimer le skin (admin)     
     */

    public function testHomepage()
    {
        $this->visit('/')
                ->see('jvscript.fr');
    }

    /**
     * Connexion superadmin
     */
    public function testConnexion($login = 'superadmin', $password = 'superadmin')
    {
        $this->visit('/')
                ->click('Connexion')
                ->seePageIs('/login')
                ->type($login, 'name')
                ->type($password, 'password')
                ->press('Se connecter')
                ->seePageIs('/')
                ->see("Bonjour $login");
    }

    public function testAjoutSkin()
    {
        $this->testConnexion();
        $this->visit('/skin/ajout')
                ->type('nom du skin', 'name')
                ->type('description', 'description')
                ->type('auteur du skin', 'autor')
                ->type('https://userstyles.org/styles/78695/skin-jvc-rouge-noir-by-tiger', 'skin_url')
                ->type('https://github.com/jvscript/jvscript.github.io', 'repo_url')
                ->type('https://www.jeuxvideo.com/forums/42-51-49907271-1-0-1-0-si-vous-avez-la-possibilite-d-etre-un-animal.htm', 'topic_url')
                ->type('https://arteriesshaking.bandcamp.com/album/burning-streets', 'website_url')
                ->type('http://image.noelshack.com/fichiers/2016/39/1475401891-valls2.gif', 'photo_url')
                ->type('https://www.paypal.me/vplancke/', 'don_url')
                ->press('Ajouter')
                ->seePageIs('/skin/ajout')
                ->see('Merci, votre skin est en attente de validation.');
    }

    /**
     * Skin non validé en guest
     */
    public function testVoirSkin404()
    {
        $response = $this->call('GET', '/skin/nom-du-skin');
        $this->assertEquals(404, $response->status());
    }

    /**
     * Voir Skin non validé avaec les droits admin
     */
    public function testVoirSkinAdmin()
    {
        $this->testConnexion();
        $this->visit('/skin/nom-du-skin')
                ->see('nom du skin')
                ->see('auteur du skin')
                ->see('https://github.com/jvscript/jvscript.github.io')
                ->see('https://www.jeuxvideo.com/forums/42-51-49907271-1-0-1-0-si-vous-avez-la-possibilite-d-etre-un-animal.htm')
                ->see('https://arteriesshaking.bandcamp.com/album/burning-streets')
//                ->see('http://image.noelshack.com/fichiers/2016/39/1475401891-valls2.gif')
                ->see('https://www.paypal.me/vplancke/');
    }

    /**
     * Inscription compte owner
     */
//    public function testInscriptionOwner() {
//        $this->visit('/')
//                ->click('Inscription')
//                ->type('owner', 'name')
//                ->type('owner@fakemail.com', 'email')
//                ->type('password', 'password')
//                ->type('password', 'password_confirmation')
//                ->press('S\'inscrire')
//                ->seePageIs('/');
//    }

    /**
     * Editer le skin en admin & changer l'owner
     */
    public function testEditerSkinAdmin()
    {
        $this->testConnexion();
        $this->visit('/skin/nom-du-skin')
                ->click('Editer')
                ->seePageIs('/skin/nom-du-skin/edit')
                ->type('desc_edit', 'description')
                ->type('2', 'user_id')
                ->type('owner', 'autor')
                ->type('2.0', 'version')
                ->type('31/12/2016', 'last_update')
                ->press('Editer')
                ->seePageIs('/skin/nom-du-skin')
                ->see('owner')
                ->see('desc_edit')
                ->see('31/12/2016')
                ->dontSee('auteur du skin')
                ->click('Valider')
                ->see('Ce skin a été validé.');
    }

    /**
     * accès au skin par owner
     */
    public function testVoirEditerSkinOwner()
    {
        $this->testConnexion('owner', 'password');
        $this->visit('/skin/nom-du-skin')
                ->click('Editer')
                ->seePageIs('/skin/nom-du-skin/edit')
                ->dontSee('Auteur du skin')
                ->type('desc_edit_owner', 'description')
                ->press('Editer')
                ->seePageIs('/skin/nom-du-skin')
                ->see('owner')
                ->see('desc_edit_owner');
    }

    /**
     * Commenter skin owner
     */
    public function testCommenterScriptOwner()
    {
        $this->testConnexion('owner', 'password');
        $this->visit('/skin/nom-du-skin')
                ->type('Ceci est un commentaire', 'comment')
                ->press('Commenter')
                ->seePageIs('/skin/nom-du-skin')
                ->see('Ceci est un commentaire')
                ->type('2eme commentaire', 'comment')
                ->press('Commenter')
                ->see('Veuillez attendre 30 secondes entre chaque commentaire svp')
                ->click('delete-comment')
                ->seePageIs('/skin/nom-du-skin')
                ->dontSee('Ceci est un commentaire')
                ->dontSee('2eme commentaire')
        ;
    }

    /**
     * Skin non validé Sans les droits admin
     */
    public function testGuestAdmin404()
    {
        $this->visit('/admin')
                ->seePageIs('/login');
    }

    /**
     * accès au skin par guest
     */
    public function testVoirSkinGuest()
    {
        $this->visit('/skin/nom-du-skin')
                ->seePageIs('/skin/nom-du-skin')
                ->dontSee('Editer')
                ->dontSee('Valider')
                ->see('owner')
                ->see('desc_edit_owner');
    }

    /**
     * accès au skin par guest
     */
    public function testNoterInstallerScriptGuest()
    {
        $note = rand(1, 5);
        $this->visit('/skin/nom-du-skin')
                ->press("note-$note")
                ->seePageIs('/skin/nom-du-skin')
                ->see('1 votes');

        $this->call('GET', '/skin/install/nom-du-skin');
        $this->visit('/skin/nom-du-skin')
                ->see('0 fois');

        $this->call('POST', '/skin/install/nom-du-skin', $parameters = ['_token' => csrf_token()], $cookies = [], $files = [], $server = ['HTTP_REFERER' => 'nom-du-skin']);
        $this->visit('/skin/nom-du-skin')
                ->see('1 fois');
    }

    public function testRefuserSkinAdmin()
    {
        $this->testConnexion();
        $this->visit('/skin/nom-du-skin')
                ->click('Refuser')
                ->seePageIs('/skin/nom-du-skin')
                ->see('Ce skin a été refusé.');
    }

    public function test404Again()
    {
        $this->testVoirSkin404();
    }

    public function testSupprimerSkinAdmin()
    {
        $this->testConnexion();
        $this->visit('/skin/nom-du-skin')
                ->click('Supprimer')
                ->seePageIs('/admin');
        $this->testVoirSkin404();
    }

}
