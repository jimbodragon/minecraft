<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MinecraftServerWrapper\WebsiteObject\Web\Form\Helper;

/**
 * Description of AdvancedWelcome
 *
 * @author jimmy.provencher
 */
class AdvancedWelcome {
    private $welcome_form;
    function __construct(\MinecraftServerWrapper\WebsiteObject\Web\Form\Accueil $welcome_form) {
        $this->welcome_form = $welcome_form;
    }

    public function to_login() {
        
    }
    
    public function choose_with_radio() {
        $this->welcome_form->add_element(new \MinecraftServerWrapper\WebsiteObject\Web\Element\RadioLabel(
                "itemcreator", "itemcreator_radio", "get_to_item_creator_page"
                , "Créer des items"));
        $this->welcome_form->add_element(new \MinecraftServerWrapper\WebsiteObject\Web\Element\RadioLabel(
                "executecommand", "execute_a_command", "get_to_command_page"
                , "Exécuter des commandes"));
        
    }
}
