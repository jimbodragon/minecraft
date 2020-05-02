<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MinecraftServerWrapper\WebsiteObject\UserState;

/**
 * Description of Accueil
 *
 * @author jimmy.provencher
 */
final class Accueil extends StateWithForm {
    private static $_accueil;
    
    public static function &get_accueil(): Accueil {
        if(is_null(self::$_accueil)){
            self::$_accueil = new Accueil();
        }
        
        return self::$_accueil;
    }
    
    protected function __construct() {
        parent::__construct(\MinecraftServerWrapper\WebsiteObject\Web\Form\Accueil::get_accueil());
    }
    
    public function switch_state(\MineCraftServerWrapper\WebsiteObject\UserState\State &$state): \MineCraftServerWrapper\WebsiteObject\UserState\State {
        switch(get_class($state)) {
            case 'MinecraftServerWrapper\WebsiteObject\UserState\Initialize':
                return \MinecraftServerWrapper\Factory\Factory::get_state_as_per_user_selection();
                break;
            default:
                return $this->generate_error('No more initial state', 100003);
                break;
        }
        return $this->generate_error('No more state', 100003);
    }
}
