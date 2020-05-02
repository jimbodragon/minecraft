<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MinecraftServerWrapper\WebsiteObject\Web\Form;

/**
 * Description of Form
 *
 * @author jimmy.provencher
 */
abstract class Form extends \MinecraftServerWrapper\WebsiteObject\Web\HTMLString {
    private $id;
    private $method;
    private $action;
    private $elements;
    private $legend_text;
    
    protected function __construct($id, $method, $action, $legend_text) {
        $this->id = $id;
        $this->method = $method;
        $this->action = $action;
        $this->legend_text = $legend_text;
        $this->elements = array();
    }
    
    public function __destruct() {
        unset($this->id);
        unset($this->method);
        unset($this->action);
        unset($this->elements);
    }
    
    protected function clear_element(){
        unset($this->elements);
        $this->elements = array();
    }
    
    public function get_legend_text($param) {
        return $this->legend_text;
    }

    public function getHtmlstring(){
        $html_str = $this->getFormHeader();
        foreach ($this->getElements() as $value) {
            $html_str = $html_str . "\n\t" . $value->getHtmlstring();
        }
        $html_str = $html_str . "\n\t" . $this->getFormFooter();
        return $html_str;
    }
    
    protected function getElements() {
        return $this->elements;
    }


    public function add_element(\MinecraftServerWrapper\WebsiteObject\Web\Element\Element $element) {
        $this->elements[] = $element;
    }

    function getId() {
        return $this->id;
    }

    public function getMethod() {
        return $this->method;
    }

    public function getAction() {
        return $this->action;
    }

    public function getFormHeader(){
        $header_str = '<form id="' . $this->GetID() . '" method="' . $this->getMethod() . '"';
        if($this->action != ""){
            $header_str .= 'action="' . $this->action . '"';
        }
        $header_str .= '>';
        $header_str = $header_str . "\n\t" . '<fieldset>';
        $header_str = $header_str . "\n\t\n\t" . '<legend>' . $this->legend_text . ':</legend>';
        return $header_str;
    }

    public function getFormFooter() {
        return '<br/><input type="submit"></fieldset></form>';
    }

    public function setId(string $id): void {
        $this->id = $id;
    }

    public function setMethod(string $method): void {
        $this->method = $method;
    }

    public function setAction(string $action): void {
        $this->action = $action;
    }
    
    public function add_radio_button() {
        
    }
    
    public function add_dropdownlist() {
        
    }
    
    public function add_fieldset() {
        
    }
    
    public function add_select() {
        
    }
    
    public function add_label_input() {
        
    }
}
