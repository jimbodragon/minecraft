<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MinecraftServerWrapper\WebsiteObject\Web\Form;

/**
 * Description of Exemple
 *
 * @author jimmy.provencher
 */
class Exemple extends Form{
    private static $_exemple;
    
    public static function get_exemple(): Exemple {
        if(is_null(self::$_exemple)){
            self::$_exemple = new Exemple();
        }
        
        return self::$_exemple;
    }
    
    protected function __construct() {
        parent::__construct("exempleform", "POST", "", "Exemple de Formulaire");
        $selectlist = new \MinecraftServerWrapper\WebsiteObject\Web\Element\SelectList("cars");
        $selectlist->add_option('Volvo', 'volvo');
        $selectlist->add_option('Saab', 'volvo');
        $selectlist->add_option('Fiat', 'fiat');
        $selectlist->add_option('audi', 'Audi');
        $this->add_element($selectlist);
        $this->add_element(new \MinecraftServerWrapper\WebsiteObject\Web\Element\HTMLString('<br/>'));
        $this->add_element(new \MinecraftServerWrapper\WebsiteObject\Web\Element\RadioLabel("exemple", "exemple_radio", "get_exemple", "Aller à l'exemple"));
    }
}
