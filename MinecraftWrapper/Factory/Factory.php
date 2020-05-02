<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MinecraftServerWrapper\Factory;

include_once 'DataObject/Database.php';

include_once 'SystemObject/MinecraftCommander.php';

include_once 'WebsiteObject/Web/WebObject.php';
include_once 'WebsiteObject/Web/HTMLString.php';

include_once 'WebsiteObject/Web/Element/Element.php';
include_once 'WebsiteObject/Web/Element/HTMLString.php';
include_once 'WebsiteObject/Web/Element/RadioLabel.php';
include_once 'WebsiteObject/Web/Element/SelectList.php';
include_once 'WebsiteObject/Web/Element/Input.php';

include_once 'WebsiteObject/Web/Form/Helper/AdvancedWelcome.php';

include_once 'WebsiteObject/Web/Form/Form.php';
include_once 'WebsiteObject/Web/Form/UserChoiceSelector.php';
include_once 'WebsiteObject/Web/Form/Exemple.php';
include_once 'WebsiteObject/Web/Form/Accueil.php';
include_once 'WebsiteObject/Web/Form/ExecuteCommand.php';
include_once 'WebsiteObject/Web/Form/AdminCommand.php';

include_once 'WebsiteObject/Authentification.php';

include_once 'WebsiteObject/Web/Main/WebObject.php';
include_once 'WebsiteObject/Web/Main/HTMLString.php';
include_once 'WebsiteObject/Web/Main/Form.php';

include_once 'WebsiteObject/Web/Main/Header/Header.php';
include_once 'WebsiteObject/Web/Main/Body/Body.php';
include_once 'WebsiteObject/Web/Main/Footer/Footer.php';
include_once 'WebsiteObject/Web/Main/Navigator/Navigator.php';

include_once 'WebsiteObject/Web/Main/Header/NoCSSHeader.php';
include_once 'WebsiteObject/Web/Main/Body/NoCSSBody.php';
include_once 'WebsiteObject/Web/Main/Footer/NoCSSFooter.php';
include_once 'WebsiteObject/Web/Main/Navigator/NoCSSNavigator.php';

include_once 'WebsiteObject/UserState/State.php';
include_once 'WebsiteObject/UserState/AbstractState.php';
include_once 'WebsiteObject/UserState/StateWithForm.php';
include_once 'WebsiteObject/UserState/UserState.php';

include_once 'WebsiteObject/UserState/Initialize.php';
include_once 'WebsiteObject/UserState/Error.php';
include_once 'WebsiteObject/UserState/Accueil.php';
include_once 'WebsiteObject/UserState/EnchantItem.php';
include_once 'WebsiteObject/UserState/Exemple.php';
include_once 'WebsiteObject/UserState/ExecuteMinecraftCommand.php';
include_once 'WebsiteObject/UserState/ExecuteAdminMinecraftCommand.php';

/**
 * Description of Factory
 *
 * @author jimmy.provencher
 */
class Factory {
    private static $_factory;
    
    private function __construct() {
    }
    
    public static function getFactory(){
        if(is_null(self::$_factory)){
            self::$_factory = new Factory();
        }
        
        return self::$_factory;
    }
    
    private static function get_selectorid(\MinecraftServerWrapper\WebsiteObject\Web\Form\UserChoiceSelector $form):string {
        if(array_key_exists($form->get_selectorid(), $_POST)){
            return $_POST[$form->get_selectorid()];
        }
        return "";
    }
    
    public static function get_state_as_per_user_selection(): \MineCraftServerWrapper\WebsiteObject\UserState\State{
        if(self::get_selectorid(\MinecraftServerWrapper\WebsiteObject\Web\Main\Navigator\NoCSSNavigator::get_navigator()) != ""){
            return self::get_state_as_per_choice_selector();
        }
        if(self::get_selectorid(\MinecraftServerWrapper\WebsiteObject\Web\Form\ExecuteCommand::get_executeform()) != ""){
            return self::get_state_as_per_execution_selector();
        }
        
        return \MinecraftServerWrapper\WebsiteObject\UserState\Accueil::get_accueil();
    }
    
    public static function get_state_as_per_choice_selector(): \MineCraftServerWrapper\WebsiteObject\UserState\State{
        switch($_POST[\MinecraftServerWrapper\WebsiteObject\Web\Main\Navigator\NoCSSNavigator::get_navigator()->get_selectorid()]){
            case 'go_to_welcome_page':
            case 'Accueil':
                return \MinecraftServerWrapper\WebsiteObject\UserState\Accueil::get_accueil();
                break;
            case 'go_to_command_page':
            case 'Exécuter des commandes':
                return \MinecraftServerWrapper\WebsiteObject\UserState\ExecuteMinecraftCommand::get_executeminecraftcommand();
                break;
            case 'go_to_item_creator_page':
            case 'Créer des items':
                break;
        }
        
        return self::generate_error('$_POST['. \MinecraftServerWrapper\WebsiteObject\Web\Main\Navigator\NoCSSNavigator::get_navigator()->get_selectorid()
                . '] = '. $_POST[\MinecraftServerWrapper\WebsiteObject\Web\Main\Navigator\NoCSSNavigator::get_navigator()->get_selectorid()]
                . ' not created', 100005);
    }
    
    public static function get_state_as_per_execution_selector(): \MineCraftServerWrapper\WebsiteObject\UserState\State{
        switch($_POST[\MinecraftServerWrapper\WebsiteObject\Web\Form\ExecuteCommand::get_executeform()->get_selectorid()]){
            case 'execute_admin_command_selector':
            case 'Éxécuter des commandes Administrateurs':
                return \MinecraftServerWrapper\WebsiteObject\UserState\ExecuteAdminMinecraftCommand::get_executeminecraftcommand();
                break;
            case 'go_to_command_page':
            case 'Exécuter des commandes':
                return \MinecraftServerWrapper\WebsiteObject\UserState\ExecuteMinecraftCommand::get_executeminecraftcommand();
                break;
            case 'go_to_item_creator_page':
            case 'Créer des items':
                break;
        }
        
        return self::generate_post_not_create_error(\MinecraftServerWrapper\WebsiteObject\Web\Form\ExecuteCommand::get_executeform(), 100006);
    }
    
    public static function generate_error(string $errormessage, int $errorcode){
        $error = new \MinecraftServerWrapper\WebsiteObject\UserState\StateNotInitialize($errormessage, $errorcode);
        $errorstate = new \MinecraftServerWrapper\WebsiteObject\UserState\Error($error);
        return $errorstate;
    }
    
    public static function generate_post_not_create_error(\MinecraftServerWrapper\WebsiteObject\Web\Form\UserChoiceSelector $selectorchoice, int $errorcode){
        return self::generate_error('$_POST['. $selectorchoice->get_selectorid()
                . '] = '. $_POST[$selectorchoice->get_selectorid()]
                . ' not created', 100005);
    }
}
