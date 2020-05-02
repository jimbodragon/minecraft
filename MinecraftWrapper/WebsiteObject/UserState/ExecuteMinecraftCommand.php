<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MinecraftServerWrapper\WebsiteObject\UserState;

/**
 * Description of ExecuteMinecraftCommand
 *
 * @author jimmy.provencher
 */
final class ExecuteMinecraftCommand extends StateWithForm {
    private static $_executestate;
    
    public static function &get_executeminecraftcommand(): ExecuteMinecraftCommand {
        if(is_null(self::$_executestate)){
            self::$_executestate = new ExecuteMinecraftCommand();
        }
        
        return self::$_executestate;
    }
    
    protected function __construct() {
        parent::__construct(\MinecraftServerWrapper\WebsiteObject\Web\Form\ExecuteCommand::get_executeform());
    }
    
    public function switch_state(\MineCraftServerWrapper\WebsiteObject\UserState\State &$state): \MineCraftServerWrapper\WebsiteObject\UserState\State
    {    
        return $this->generate_error('No more state', 100003);
    }
}
