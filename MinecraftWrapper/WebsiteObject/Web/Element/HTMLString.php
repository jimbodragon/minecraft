<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MinecraftServerWrapper\WebsiteObject\Web\Element;

/**
 * Description of HTMLString
 *
 * @author jimmy.provencher
 */
class HTMLString extends Element {
    private $htmlstring;
    
    function __construct($htmlstring) {
        $this->htmlstring = $htmlstring;
    }
    
    function getHtmlstring() {
        return $this->htmlstring;
    }
}
