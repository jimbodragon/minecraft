<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MinecraftServerWrapper\WebsiteObject\UserState;

/**
 * Description of Error
 *
 * @author jimmy.provencher
 */
class Error extends AbstractState{
    public $ownexception;
    
    function __construct(\Exception $exeption) {
        $this->ownexception = $exeption;
    }
    
    private function get_trace($tracekey, $treacevalue){
        if(is_array($treacevalue)){
            $tracestr = 'trace[' . $tracekey . '] =';
            foreach ($treacevalue as $key => $valuearr) {
                $tracestr = $tracestr . "\t" . $this->get_trace($key, $valuearr) . "\n";
            }
        }
        else{
            $value = $treacevalue;
            if(is_object($treacevalue)){
                $value = get_class($treacevalue);
            }
            return 'trace[' . $tracekey . '] = ' . $value . "\n";
        }
    }

    public function generate_code() {
        $this->print_message("Exception code = " . $this->ownexception->getCode());
        $this->print_message("Exception from file " . $this->ownexception->getFile());
        $this->print_message("Inside the line " .$this->ownexception->getLine());
        $this->print_message("Error message is: " .$this->ownexception->getMessage());
        #$this->print_message($this->ownexception->getPrevious());
        #$this->print_message("Trace is: " . $this->ownexception->getTraceAsString());
        
        $tracestr = "";
        $tracepos = 0;
        foreach ($this->ownexception->getTrace() as $trace) {
            $tracestr = $tracestr . '<pre>' . $tracepos . ': ' . "\n";
            foreach ($trace as $key => $value) {
                $tracestr = $tracestr . $this->get_trace($key, $value);
            }
            $tracepos += 1;
            $tracestr = $tracestr . '<pre>';
        }
        $this->print_message("Trace is: " . $tracestr);
        
        $this->print_array($_REQUEST, "Request");
        $this->print_array($_GET, "Get");
        $this->print_array($_POST, "Post");
        $this->print_array($_SERVER, "Server");
        $this->print_array($_COOKIE, "Cookie");
        $this->print_array($_ENV, "Env");
        $this->print_array($_FILES, "Files");
    }
    
    private function return_array($arrvalue, $arrname){
        if(is_array($arrvalue)){
            $text = '';
            foreach ($arrvalue as $key => $value) {
                if($text != ''){
                    $text .= ', ';
                }
                $text .= $arrname . '[' . $this->return_array($value, $key) . ']';
            }
            return $text;
        }
        return $arrvalue;
    }
    
    private function print_array($array, $arrayname){
        $this->print_message($arrayname . '[' . print_r($array, true) . ']');
        #foreach ($array as $key => $value) {
        #    $this->print_message($arrayname . '[' . $this->return_array($value, $key) . ']');
        #}
    }
}
