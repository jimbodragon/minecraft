<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MinecraftServerWrapper\WebsiteObject\UserState;

/**
 * Description of Test
 *
 * @author jimmy.provencher
 */
class Exemple extends AbstractState {
    
    private static $_exemple;
    
    public static function &get_exemple(): Exemple {
        if(is_null(self::$_exemple)){
            self::$_exemple = new Exemple();
        }
        
        return self::$_exemple;
    }
    
    protected function __construct() {
    }
    
    public function generate_code() {        
        $db = new \MineCraftServerWrapper\DataObject\Database();
        $ssh = new \MinecraftServerWrapper\SystemObject\MinecraftSSHServer();
        $form = new \MinecraftServerWrapper\WebsiteObject\Web\Form\Exemple();
        
        $text = 'exemple page open at '. $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['PHP_AUTH_USER'] . '@'. $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['PHP_SELF'];
        if($_SERVER['QUERY_STRING'] <> ""){
            $text = $text . '?' . $_SERVER['QUERY_STRING'];
        }
        $text = $text . ' for session ' . $_COOKIE['PHPSESSID'];
        $this->print_message('Number of Get = ' . count($_GET));
        $this->print_message('Number of Post = ' . count($_POST));
        $this->print_message($ssh->minecraftexec('msg jimbodragon ' . $text));
        $this->print_message($ssh->exec('wall ' .$text));
        $this->print_message($ssh->getLog());
        
        $this->print_message(\MinecraftServerWrapper\WebsiteObject\Web\Form\Exemple::get_exemple()->getHtmlstring());

        #phpinfo();

        #print_r($_GET);
        #print_r($_POST);

        unset($db);
        
        return "";
    }
}
