<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    include_once 'Factory/Factory.php';
    use \MineCraftServerWrapper\WebsiteObject\UserState\UserState;
    
    try {
        UserState::getUserState()->create_web_page();
    }
    catch (MinecraftServerWrapper\WebsiteObject\Web\WebObjectNotImplement $ex){
        #echo 'Get an exemple as the website ' . $ex->getTrace()[0]['class'] . '->' . $ex->getTrace()[0]['function'] . ' is not ready yet';
        UserState::getUserState()->switch_state(\MinecraftServerWrapper\WebsiteObject\UserState\Exemple::get_exemple());
        UserState::getUserState()->create_web_page();
    }
    catch (Exception $ex) {
        #echo 'Catch the exception';
        UserState::getUserState()>switch_state(new \MinecraftServerWrapper\WebsiteObject\UserState\Error($ex));
        UserState::getUserState()->create_web_page();
    }
?>