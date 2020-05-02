<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MineCraftServerWrapper\WebsiteObject\UserState;

/**
 * Description of UserState
 *
 * @author jimmy.provencher
 */
class UserState {
    private static $_userstate; 
    private $state;
    
    private function __construct() {
        $this->state = \MinecraftServerWrapper\WebsiteObject\UserState\Initialize::get_initialize();
        
        $this->switch_state(\MinecraftServerWrapper\WebsiteObject\UserState\Accueil::get_accueil());
    }
    
    public static function getUserState(): UserState {
        if(is_null(self::$_userstate)){
            self::$_userstate = new UserState();
        }
        
        return self::$_userstate;
    }
    
    public function create_web_page() {
        $this->state->create_web_page();
    }
    
    public function switch_state(\MinecraftServerWrapper\WebsiteObject\UserState\State &$state){
        $this->state = $this->state->switch_state($state);
    }
    
    public function get_state(): \MinecraftServerWrapper\WebsiteObject\UserState\State{
        return $this->state;
    }
}
