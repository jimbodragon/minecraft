<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MinecraftServerWrapper\WebsiteObject\Web\Main\Footer;

/**
 * Description of Footer
 *
 * @author jimmy.provencher
 */
final class NoCSSFooter extends Footer {
    private static $_footer;
    
    public static function get_footer(): NoCSSFooter {
        if(is_null(self::$_footer)){
            self::$_footer = new NoCSSFooter();
        }
        
        return self::$_footer;
    }
    
    protected function __construct() {
    }

    public function getHtmlstring(){
        return '<img id="bottom" src="bottom.png" alt="">
    </body>
</html>';
    }
}
