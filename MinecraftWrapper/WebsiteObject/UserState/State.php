<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MinecraftServerWrapper\WebsiteObject\UserState;

class MyException extends \Exception { }

/**
 *
 * @author jimmy.provencher
 */
interface State {
    public function generate_code();
    public function create_web_page();
    public function switch_state(\MineCraftServerWrapper\WebsiteObject\UserState\State &$state): \MineCraftServerWrapper\WebsiteObject\UserState\State;
    
    public function getHeader(): \MinecraftServerWrapper\WebsiteObject\Web\Main\Header\Header;
    public function getFooter(): \MinecraftServerWrapper\WebsiteObject\Web\Main\Footer\Footer;
    public function getBody(): \MinecraftServerWrapper\WebsiteObject\Web\Main\Body\Body;
    public function getNavigator(): \MinecraftServerWrapper\WebsiteObject\Web\Main\Navigator\Navigator;
    
    public function setHeader(\MinecraftServerWrapper\WebsiteObject\Web\Main\Header\Header $header);
    public function setBody(\MinecraftServerWrapper\WebsiteObject\Web\Main\Body\Body $body);
    public function setFooter(\MinecraftServerWrapper\WebsiteObject\Web\Main\Footer\Footer $footer);
    public function setNavigator(\MinecraftServerWrapper\WebsiteObject\Web\Main\Navigator\Navigator $navigator);
}
