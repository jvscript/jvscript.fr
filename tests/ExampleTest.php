<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testHomepage()
    {
        $this->visit('/')
             ->see('jvscript.io');
    }
    
    /*
     * _TODO : writes some tests :
     * - ajout script
     * - connexion -> admin 
     * - visualiser le script
     * - éditer le script
     * - valider le script
     * - visualiser le script (guest)
     * - accès page admin (guest)
     * - noter le script
     * - installer le script 
     * - refuser le script (admin)
     * 
     */
}
