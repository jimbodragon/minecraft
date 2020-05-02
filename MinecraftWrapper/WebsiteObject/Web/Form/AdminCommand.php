<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MinecraftServerWrapper\WebsiteObject\Web\Form;

/**
 * Description of AdminCommand
 *
 * @author jimmy.provencher
 */
final class AdminCommand extends UserChoiceSelector {
    private static $_executeadmincommand;
    
    public static function get_executeadminform(): AdminCommand {
        if(is_null(self::$_executeadmincommand)){
            self::$_executeadmincommand = new AdminCommand();
        }
        
        return self::$_executeadmincommand;
    }
    
    protected function __construct() {
        parent::__construct("admin_execute_form", 'execute_admin_command_selector', "POST", "", "Que souhaitez-vous exécuter?");
    
        $this->add_choice('give_op', 'give_op_to_someone', "Donner des droits d'éxécutre des commandes dans le jeu");
        
        $this->choose_with_radio();
    }
}
