<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MinecraftServerWrapper\WebsiteObject\Web\Main\Navigator;

/**
 *
 * @author jimmy.provencher
 */
abstract class Navigator extends \MinecraftServerWrapper\WebsiteObject\Web\Form\UserChoiceSelector {
    
    protected function __construct() {
        parent::__construct("navigator", 'choice_selector', "POST", "", "Où souhaitez-vous aller?");
    
        $this->add_choice('welcome_page', 'go_to_welcome_page', 'Accueil');
        $this->add_choice('executecommand', 'go_to_item_creator_page', 'Créer des items');
        $this->add_choice('choice_selector', 'go_to_command_page', 'Exécuter des commandes');
        $this->add_choice('changenickname', 'go_to_nickname_page', "Changer le nom d'usager");
        
        $this->choose_with_button();
    }
}
