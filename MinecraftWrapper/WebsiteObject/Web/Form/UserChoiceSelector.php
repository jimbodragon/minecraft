<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MinecraftServerWrapper\WebsiteObject\Web\Form;

/**
 * Description of UserChoiceSelector
 *
 * @author jimmy.provencher
 */
abstract class UserChoiceSelector extends \MinecraftServerWrapper\WebsiteObject\Web\Form\Form {
    private $navigable;
    private $use_buttons;
    private $selectorid;
    
    protected function __construct($formid, $selectorid, $method, $action, $legend_text) {
        parent::__construct($formid, $method, $action, $legend_text);
        $this->selectorid = $selectorid;
        $this->use_buttons = false;
    }
    
    public function get_selectorid(){
        return $this->selectorid;
    }
    
    protected function add_choice($id, $value, $text) {
        $this->navigable[] = array('id' => $id
            , 'value' => $value
            , 'text' => $text);
    }
    
    public function getHtmlstring(){
        $html_str = $this->getFormHeader();
        foreach ($this->getElements() as $value) {
            $html_str = $html_str . "\n\t" . $value->getHtmlstring();
        }
        $html_str = $html_str . "\n\t" . $this->getFormFooter();
        return $html_str;
    }
    
    public function choose_with_radio() {
        $this->clear_element();
        foreach ($this->navigable as $value) {
            $this->add_element(new \MinecraftServerWrapper\WebsiteObject\Web\Element\RadioLabel(
                $value['id'], $this->selectorid, $value['value'], $value['text']));
            #$this->add_element(new \MinecraftServerWrapper\WebsiteObject\Web\Element\Input('hidden', 'last_action', 'acceuilform', $value['radioname'] . '[from]', 'AccueilForm'));
        }
    }
    
    public function choose_with_selectlist() {
        $this->clear_element();
        $selectlist = new \MinecraftServerWrapper\WebsiteObject\Web\Element\SelectList($this->selectorid);
        foreach ($this->navigable as $option) {
            $selectlist->add_option($option['value'], $option['text']);
        }
        $this->add_element($selectlist);
    }
    
    public function choose_with_button() {
        $this->clear_element();
        $this->use_buttons = true;
        foreach ($this->navigable as $value) {
            $this->add_element(new \MinecraftServerWrapper\WebsiteObject\Web\Element\Input('submit', 
                $value['id'], $this->getId(), $this->selectorid, $value['text'], $value['value']));
            #$this->add_element(new \MinecraftServerWrapper\WebsiteObject\Web\Element\Input('hidden', 'last_action', 'acceuilform', $value['radioname'] . '[from]', 'AccueilForm'));
        }
    }
    
    public function getFormFooter(): string {
        if($this->use_buttons){
            return '</fieldset></form>';
        }
        else{
            return parent::getFormFooter();
        }
    }
}
