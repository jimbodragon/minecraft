<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MinecraftServerWrapper\WebsiteObject\Web\Main;

/**
 * Description of HTMLString
 *
 * @author jimmy.provencher
 */
abstract class HTMLString implements WebObject {
    protected function __construct(){
    }
    
    protected function print_debug(string $message){
        echo '<br>[DEBUG]: ' . $message . '</br>';
    }
    
    public function generatecode(){
        return $this->getHtmlstring();
    }
}
