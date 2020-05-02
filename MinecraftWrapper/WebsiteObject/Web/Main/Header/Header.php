<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MinecraftServerWrapper\WebsiteObject\Web\Main\Header;

/**
 *
 * @author jimmy.provencher
 */
abstract class Header extends \MinecraftServerWrapper\WebsiteObject\Web\Main\HTMLString {
    protected function get_websiteName(){
        return 'Minecraft Server Wrapper';
    }
}
