<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MineCraftServerWrapper\DataObject;

/**
 * Description of enchantable_item
 *
 * @author jimmy.provencher
 */
class Enchantable_item {
    private $db;
    private $dbrow;
    
    public $id;
    public $name;
    public $command_name;
    public $use_material;
    
    public function __construct(Database $db, $dbrow) {
        $this->db = $db;
        $this->dbrow = $dbrow;
        $this->id = $dbrow['id'];
        $this->name = $dbrow['name'];
        $this->command_name = $dbrow['command_name'];
        $this->use_material = $dbrow['is_used_material'];
    }
}
