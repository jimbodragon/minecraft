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
final class ExecuteCommand extends UserChoiceSelector {
    private static $_executecommand;
    
    public static function get_executeform(): ExecuteCommand {
        if(is_null(self::$_executecommand)){
            self::$_executecommand = new ExecuteCommand();
        }
        
        return self::$_executecommand;
    }
    
    protected function __construct() {
        parent::__construct("execute_form", 'execute_command_selector', "POST", "", "Que souhaitez-vous exécuter?");
    
        $this->add_choice('admin_command', 'execute_admin_command_selector', 'Éxécuter des commandes Administrateurs');
        $this->add_choice('get_Seed', 'get_seed_of_world', 'Afficher le seed');
        $this->add_choice('send_message', 'send_message_to_someone', "Envoyer un message");
        
        $this->choose_with_selectlist();
    }
}
