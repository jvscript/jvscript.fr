<?php


class scriptsTest extends BrowserKitTestCase
{
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
     * _TODO : do file upload, update js_url, skin_url
     *
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

    public function testAjoutScript()
    {
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
                ->see('Merci d\'avoir poster un script mon khey.');
    }



    /**
     * Voir Script non validé avaec les droits admin
     */
    public function testVoirScriptAdmin()
    {
        $this->testConnexion();
        $this->visit('/script/nom-du-script')
                ->see('nom du script')
                ->see('auteur du script')
                ->see('https://github.com/jvscript/jvscript.github.io')
                ->see('https://www.jeuxvideo.com/forums/42-51-49907271-1-0-1-0-si-vous-avez-la-possibilite-d-etre-un-animal.htm')
                ->see('https://arteriesshaking.bandcamp.com/album/burning-streets')
                ->see('nom-du-script.jpg')
                ->see('https://www.paypal.me/vplancke/');
    }

    /**
     * Inscription compte owner
     */
    public function testInscription($username = 'owner', $password = 'password')
    {
        $this->visit('/')
                ->click('Inscription')
                ->type($username, 'name')
                ->type($username . '@fakemail.com', 'email')
                ->type($password, 'password')
                ->type($password, 'password_confirmation')
                ->press('S\'inscrire')
                ->seePageIs('/');
    }

    /**
     * Editer le script en admin & changer l'owner
     */
    public function testEditerScriptAdmin()
    {
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
    public function testVoirEditerScriptOwner()
    {
        $this->testConnexion('owner', 'password');
        $this->visit('/script/nom-du-script')
                ->click('Editer')
                ->seePageIs('/script/nom-du-script/edit')
                ->dontSee('Auteur du script')
                ->type('nom du script edited', 'name')
                ->type('desc_edit_owner', 'description')
                ->press('Editer')
                ->seePageIs('/script/nom-du-script-edited')
                ->see('owner')
                ->see('desc_edit_owner')
                ->click('Editer')
                ->seePageIs('/script/nom-du-script-edited/edit')
                ->type('nom du script', 'name')
                ->press('Editer')
                ->seePageIs('/script/nom-du-script');
    }

    /**
     * Commenter script owner
     */
    public function testCommenterScriptOwner()
    {
        $this->testConnexion('owner', 'password');
        $this->visit('/script/nom-du-script')
                ->type('Ceci est un commentaire', 'comment')
                ->press('Commenter')
                ->seePageIs('/script/nom-du-script')
                ->see('Ceci est un commentaire')
                ->type('2eme commentaire', 'comment')
                ->press('Commenter')
                ->see('Veuillez attendre 30 secondes entre chaque commentaire svp')
                ->click('delete-comment')
                ->seePageIs('/script/nom-du-script')
                ->dontSee('Ceci est un commentaire')
                ->dontSee('2eme commentaire')
        ;
    }

    /**
     * Random user can't edit owner script
     */
    public function testRandomUserCantEditOwnerScript()
    {
        $this->testInscription('random', 'password');
        $this->visit('/script/nom-du-script')
                ->dontSee('Editer');
    }

    /**
     * Page admin en guest
     */
    public function testGuestAdmin404()
    {
        $this->visit('/admin')
                ->seePageIs('/login');
    }

    /**
     * accès au script par guest
     */
    public function testVoirScriptGuest()
    {
        $this->visit('/script/nom-du-script')
                ->seePageIs('/script/nom-du-script')
                ->dontSee('Editer')
                ->dontSee('Valider')
                ->see('nom-du-script.jpg')
                ->see('owner')
                ->see('desc_edit_owner');
    }

    /**
     * accès au script par guest
     */
    public function testNoterInstallerScriptGuest()
    {
        $note = rand(1, 5);
        $this->visit('/script/nom-du-script')
                ->press("note-$note")
                ->seePageIs('/script/nom-du-script')
                ->see('1 votes');

        $this->call('GET', '/script/install/nom-du-script');
        $this->visit('/script/nom-du-script')
                ->see('1 fois');

        $this->call('POST', '/script/install/nom-du-script', $parameters = ['_token' => csrf_token()], $cookies = [], $files = [], $server = ['HTTP_REFERER' => 'nom-du-script']);
        $this->visit('/script/nom-du-script')
                ->see('1 fois');
    }

    public function testRefuserScriptAdmin()
    {
        $this->testConnexion();
        $this->visit('/script/nom-du-script')
                ->click('Refuser')
                ->seePageIs('/script/nom-du-script')
                ->see('Ce script a été refusé.');
    }

    /**
     * Script non validé en guest
     */
    public function testGuestTryingToSeeScriptShouldGet404()
    {
        $response = $this->call('GET', '/script/nom-du-script');
        $this->assertEquals(404, $response->status());
    }

    public function testSupprimerScriptAdmin()
    {
        $this->testConnexion();
        $this->visit('/script/nom-du-script')
                ->click('Supprimer')
                ->seePageIs('/admin');
        $this->testGuestTryingToSeeScriptShouldGet404();
    }
}
