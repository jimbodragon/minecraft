<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MinecraftServerWrapper\SystemObject;

set_include_path(get_include_path() . PATH_SEPARATOR . '3rdLibrary/phpseclib/phpseclib' . PATH_SEPARATOR . '3rdLibrary/constant_time_encoding/src');

include_once 'SystemObject/include_phpseclib.php';

/**
 * Description of MinecraftSSHServer
 *
 * @author jimmy.provencher
 */
class MinecraftCommander {
    private $ssh;
    private $key;
    private $rconpassword = 'CedStrongPassword';
    
    private static $_commander;
    
    public static function get_commander(): MinecraftCommander {
        if(is_null(self::$_database)){
            self::$_commander = new MinecraftCommander();
        }
        
        return self::$_commander;
    }
    
    private function __construct() {
        $this->key = \phpseclib3\Crypt\PublicKeyLoader::load(file_get_contents('SystemObject/.ssh/id_rsa'));
        $this->ssh = new \phpseclib3\Net\SSH2('192.168.2.89');
        if (!$this->ssh->login('root', $this->key)) {
            exit('Login Failed');
        }
    }
    
    public function exec($remote_command) {
        if($_SERVER['HTTP_ACCEPT'] <> '*/*') {
            return $this->ssh->exec($remote_command);
        }
        else{
            return $this->local_exec('echo')['output'];
        }
    }
    
    public function getLog() {
        return $this->ssh->getLog();
    }
    
    public function minecraftexec($cmd) {
        $remote_command = '/opt/minecraft/tools/mcrcon -H 127.0.0.1 -P 23888 -p ' . $this->rconpassword . " '$cmd'";
        return $this->exec($remote_command);
    }
    
    /**
	Method to execute a command in the terminal
	Uses :
	
	1. system
	2. passthru
	3. exec
	4. shell_exec

    */
    private function local_exec($command)
    {
        if($_SERVER['HTTP_ACCEPT'] <> '*/*') {
            //system
            if(function_exists('system'))
            {
                echo "Execute as system";
                ob_start();
                system($command , $return_var);
                $output = ob_get_contents();
                ob_end_clean();
            }
            //passthru
            else if(function_exists('passthru'))
            {
                echo "Execute as passthru";
                ob_start();
                passthru($command , $return_var);
                $output = ob_get_contents();
                ob_end_clean();
            }

            //exec
            else if(function_exists('exec'))
            {
                echo "Execute as exec";
                exec($command , $output , $return_var);
                $output = implode("\n" , $output);
            }

            //shell_exec
            else if(function_exists('shell_exec'))
            {
                echo "Execute as exec";
                $output = shell_exec($command) ;
            }

            else
            {
                $output = 'Command execution not possible on this system';
                $return_var = 1;
            }
        }
        else {
            $output = 'Command execution not possible on this system';
            $return_var = 2;
        }
        return array('output' => $output , 'status' => $return_var);
    }
}
