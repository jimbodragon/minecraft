<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MinecraftServerWrapper\WebsiteObject\Web\Element;

/**
 * Description of RadioButton
 *
 * @author jimmy.provencher
 */
class RadioLabel extends \MinecraftServerWrapper\WebsiteObject\Web\Element\Element{
    private $radioname;
    private $id;
    private $value;
    private $text;
    
    function __construct($id, $radioname, $value, $text) {
        $this->radioname = $radioname;
        $this->id = $id;
        $this->value = $value;
        $this->text = $text;
    }

    public function getHtmlstring() {
        return '<label for="' . $this->id . '">' . $this->text . '</label>'
            . '<input type="radio" name="' . $this->radioname . '" id="' . $this->id . '" value="' . $this->value . '">';
    }
}
