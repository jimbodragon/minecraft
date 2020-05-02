<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MinecraftServerWrapper\WebsiteObject\UserState;

/**
 * Description of StateWithForm
 *
 * @author jimmy.provencher
 */
abstract class StateWithForm extends AbstractState {
    private $form;
    
    protected function __construct($form) {
        $this->form = $form;
    }
    
    public function generate_code() {
        return $this->form->generatecode();
    }
}
