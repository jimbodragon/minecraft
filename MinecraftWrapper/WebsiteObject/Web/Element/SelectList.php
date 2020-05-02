<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MinecraftServerWrapper\WebsiteObject\Web\Element;

/**
 * Description of SelectList
 *
 * @author jimmy.provencher
 */
class SelectList extends \MinecraftServerWrapper\WebsiteObject\Web\Element\Element {
    private $name;
    private $options;
    
    function __construct($name) {
        $this->name = $name;
    }
    function getName() {
        return $this->name;
    }

    function setName($name): void {
        $this->name = $name;
    }

    public function add_option($name, $value){
        $this->options[$name] = $value;
    }
        
    public function getHtmlstring(){
        $html_str = '<select name="' . $this->name . '" size="' . count($this->options) . '">';
        foreach ($this->options as $name => $option) {
            $html_str = $html_str . "\n\t" . '<option value="' . $name . '">' . $option . '</option>';
        }
        $html_str = $html_str . "\n\t" . '</select>';
        return $html_str;
    }
}
