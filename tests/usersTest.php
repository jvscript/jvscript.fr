<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;

class usersTest extends BrowserKitTestCase {
    /*
     * page contact
     * _TODO : page mescripts owner -> edit 
     * _TODO : admin comment delete -> 
     * _TODO : admin index : click edit 
     */

    public function testContact() {
        $this->visit('/contact')
                ->see('Nous contacter par email')
                ->type('jvscript@yopmail.com','email')
                ->type('Ceci est un message','message_body')
                ->press('Envoyer')
                ->see('Merci, votre message a été envoyé.')
            ;
    }

}
