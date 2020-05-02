<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MineCraftServerWrapper\WebsiteObject\UserState;

/**
 * Description of EnchantItemForm
 *
 * @author jimmy.provencher
 */
class EnchantItem extends StateWithForm {
    private $enchantform;
    
    private static $_EnchantItem;
    
    public static function &get_enchantitem(): EnchantItem {
        if(is_null(self::$_EnchantItem)){
            self::$_EnchantItem = new EnchantItem();
        }
        
        return self::$_EnchantItem;
    }
    
    protected function __construct() {
        $this->enchantform = new \MinecraftServerWrapper\WebsiteObject\Web\Form\Accueil();
    }
    
    public function generate_code() {
        return $this->enchantform->generatecode();
    }
}