<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MinecraftServerWrapper\WebsiteObject\Web\Main\Navigator;

/**
 * Description of Navigator
 *
 * @author jimmy.provencher
 */
final class NoCSSNavigator extends Navigator {
    private static $_navigator;
    
    public static function get_navigator(): NoCSSNavigator {
        if(is_null(self::$_navigator)){
            self::$_navigator = new NoCSSNavigator();
        }
        
        return self::$_navigator;
    }
    
    public function __construct() {
        parent::__construct();
    }

    #public function getHtmlstring() {
    #    return '<span>
#<a href="?Enchant_items=true">Enchant item</a>
#</span>
#<span>
#<a href="?Execute_command=true">Execute command</a>
#</span>
#<br/>
#<span>
#<a href="?Execute_command=true">Execute command</a>
#</span>';

#    }
}
