<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MinecraftServerWrapper\WebsiteObject\Web\Element;

/**
 * Description of Input
 *
 * @author jimmy.provencher
 */
class Input extends Element {
    private $type;
    private $id;
    private $formid;
    private $name;
    private $value;
    private $text;
    
    function __construct($type, $id, $formid, $name, $value, $text = "") {
        $this->type = $type;
        $this->id = $id;
        $this->formid = $formid;
        $this->name = $name;
        $this->value = $value;
        $this->text = $text;
    }

        public function getHtmlstring() {
            $htmlstr = '<input type="' . $this->type . '" id="' . $this->id
                . '" form="' . $this->formid . '" name="' . $this->name . '" value="' . $this->value . '"';
            if($this->text == ""){
                $htmlstr .= '/>';
            }
            else {
                $htmlstr .= 'text="' . $this->text . '"/>';
            }
        return $htmlstr;
    }

}
