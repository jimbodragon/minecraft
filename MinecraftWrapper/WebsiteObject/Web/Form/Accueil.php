<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MinecraftServerWrapper\WebsiteObject\Web\Form;

/**
 * Description of Accueil
 *
 * @author jimmy.provencher
 */
class Accueil extends Form {
    private static $_accueil;
    
    public static function get_accueil(): Accueil {
        if(is_null(self::$_accueil)){
            self::$_accueil = new Accueil();
        }
        
        return self::$_accueil;
    }
    
    protected function __construct() {
        parent::__construct("acceuilform", "POST", "", "Que souhaitez-vous faire?");
        $this->add_element(new \MinecraftServerWrapper\WebsiteObject\Web\Element\HTMLString('<h2>Accueil Minecraft Wrapper</h2>'));
    }
}
