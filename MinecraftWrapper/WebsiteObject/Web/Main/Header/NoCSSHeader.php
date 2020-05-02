<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MinecraftServerWrapper\WebsiteObject\Web\Main\Header;

/**
 * Description of Header
 *
 * @author jimmy.provencher
 */
final class NoCSSHeader extends Header {
    private static $_header;
    
    public static function get_header(): NoCSSHeader {
        if(is_null(self::$_header)){
            self::$_header = new NoCSSHeader();
        }
        
        return self::$_header;
    }
    
    protected function __construct() {
    }

    public function getHtmlstring(){
        return '<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>' . $this->get_websiteName() .'</title>
</head>';
    }
}
