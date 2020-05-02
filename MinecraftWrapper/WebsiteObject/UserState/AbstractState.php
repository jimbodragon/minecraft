<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MinecraftServerWrapper\WebsiteObject\UserState;

/**
 * Description of AbstractState
 *
 * @author jimmy.provencher
 */
abstract class AbstractState implements State{
    private static $header;
    private static $body;
    private static $footer;
    private static $navigator;
    
    private $internal_html_code;
    
    protected function __construct(){;
    }
    
    protected function print_message(string $message){
        $this->adding_code('<br>' . $message . '</br>');
    }
    
    protected function print_debug(string $message){
        echo '<br>[DEBUG]: ' . $message . '</br>';
    }
    
    protected function adding_code(string $code){
        $this->internal_html_code = $this->internal_html_code . $code;
    }
    
    protected function get_internal_html_code(){
        return $this->internal_html_code;
    }
    
    protected function generate_error(string $errormessage, int $errorcode){
        $error = new \MinecraftServerWrapper\WebsiteObject\UserState\StateNotInitialize($errormessage, $errorcode);
        $errorstate = new \MinecraftServerWrapper\WebsiteObject\UserState\Error($error);
        return $errorstate;
    }
    
    public function create_web_page() {
        echo $this->getHeader()->getHtmlstring()
            . $this->getBody()->getHtmlstring()
            . $this->getNavigator()->getHtmlstring()
            . $this->generate_code()
            . $this->internal_html_code
            . $this->getFooter()->getHtmlstring();
    }
    
    public function getHeader(): \MinecraftServerWrapper\WebsiteObject\Web\Main\Header\Header{
        return self::$header;
    }
    public function getFooter(): \MinecraftServerWrapper\WebsiteObject\Web\Main\Footer\Footer{
        return self::$footer;
    }
    public function getBody(): \MinecraftServerWrapper\WebsiteObject\Web\Main\Body\Body{
        return self::$body;
    }
    public function getNavigator(): \MinecraftServerWrapper\WebsiteObject\Web\Main\Navigator\Navigator{
        return self::$navigator;
    }
    
    public function setHeader(\MinecraftServerWrapper\WebsiteObject\Web\Main\Header\Header $header){
        self::$header = $header;
    }
    public function setBody(\MinecraftServerWrapper\WebsiteObject\Web\Main\Body\Body $body){
        self::$body = $body;
    }
    public function setFooter(\MinecraftServerWrapper\WebsiteObject\Web\Main\Footer\Footer $footer){
        self::$footer = $footer;
    }
    public function setNavigator(\MinecraftServerWrapper\WebsiteObject\Web\Main\Navigator\Navigator $navigator){
        self::$navigator = $navigator;
    }
    
    public function switch_state(\MineCraftServerWrapper\WebsiteObject\UserState\State &$state): \MineCraftServerWrapper\WebsiteObject\UserState\State{
        return $state;
    }
}
