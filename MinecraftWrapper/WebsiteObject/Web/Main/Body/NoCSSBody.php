<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MinecraftServerWrapper\WebsiteObject\Web\Main\Body;

/**
 * Description of Body
 *
 * @author jimmy.provencher
 */
final class NoCSSBody extends Body {
    private static $_body;
    
    public static function get_body(): NoCSSBody {
        if(is_null(self::$_body)){
            self::$_body = new NoCSSBody();
        }
        
        return self::$_body;
    }
    
    protected function __construct() {
    }

    public function getHtmlstring() {
        return '<body id="main_body" >'
        . ' <img id="top" src="top.png" alt="">'
        . ' <div id="form_container">'
        . ' <h1><a>Minecraft Wrapper</a></h1>';
    }

}
