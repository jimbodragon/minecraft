<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace MineCraftServerWrapper\DataObject;

/**
 * Description of Enchant
 *
 * @author jimmy.provencher
 */
class Enchant {
    private $db;
    private $dbrow;
    
    public $id;
    public $name;
    public $max_level;
    public $description;
    public $command_enchant_name;
    public $url;
    
    public function __construct(Database $db, $dbrow) {
        $this->db = $db;
        $this->id = $dbrow['id'];
        $this->name = $dbrow['enchant_name'];
        $this->max_level = $dbrow['max_level'];
        $this->description = $dbrow['description'];
        $this->command_enchant_name = $dbrow['command_enchant_name'];
        $this->url = $dbrow['url'];
    }
}
