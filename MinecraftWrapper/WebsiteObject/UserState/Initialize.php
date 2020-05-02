<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MinecraftServerWrapper\WebsiteObject\UserState;

class StateNotInitialize extends \MinecraftServerWrapper\WebsiteObject\Web\WebObjectNotImplement {
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = NULL) {
        return parent::__construct($message, $code, $previous);
    }
}

/**
 * Description of Initialize
 *
 * @author jimmy.provencher
 */
class Initialize extends \MineCraftServerWrapper\WebsiteObject\UserState\AbstractState {
    
    private static $_initialize;
    
    public static function &get_initialize(): Initialize {
        if(is_null(self::$_initialize)){
            self::$_initialize = new Initialize();
        }
        
        return self::$_initialize;
    }
    
    protected function __construct() {
        $this->setHeader(\MinecraftServerWrapper\WebsiteObject\Web\Main\Header\NoCSSHeader::get_header());
        $this->setNavigator(\MinecraftServerWrapper\WebsiteObject\Web\Main\Navigator\NoCSSNavigator::get_navigator());
        $this->setBody(\MinecraftServerWrapper\WebsiteObject\Web\Main\Body\NoCSSBody::get_body());
        $this->setFooter(\MinecraftServerWrapper\WebsiteObject\Web\Main\Footer\NoCSSFooter::get_footer());
    }

    public function generate_code(){
        throw new StateNotInitialize("State still in initialize phase", 100002);
    }
    
    public function switch_state(\MineCraftServerWrapper\WebsiteObject\UserState\State &$state): \MineCraftServerWrapper\WebsiteObject\UserState\State {
        return $state->switch_state($this);
    }
}
