<?php

namespace MinecraftWrapper\Database;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once 'Enchant.php';
include_once 'Enchantable_item.php';

/**
 * Description of database
 *
 * @author jimmy.provencher
 */

class Database {
    //put your code here
    
    private $db;
    private $enchants;
    private $items;
    
    public function __construct() {
        $dbhost = 'localhost';
        $dbusername = 'my_webapp';
        $dbpasswd = 'tFCXS6Pw3Ql8UG9MZeW0InUf';
        $dbname = 'my_webapp';
        $this->$db = mysqli_connect($dbhost, $dbusername, $dbpasswd, $dbname)
     or die('Error connecting to MySQL server.');
        echo "Connection susscessful";
        mysqli_query($this->db, 'SET NAMES \'utf8\'');
        
        $this->enchants = null;
        $this->items = null;
        
        $this->get_enchants();
        $this->get_items();
    }

    public function __destruct() {
        unset($this->enchants);
        unset($this->items);
        mysqli_close($this->db);
    }
    
    public function set_incompatibility() {
        $points[]=array(0,6);
        $points[]=array(0,7);
        $points[]=array(6,7);
        $points[]=array(5,11);
        $points[]=array(15,17);
        $points[]=array(15,18);
        $points[]=array(15,19);
        $points[]=array(17,18);
        $points[]=array(17,19);
        $points[]=array(18,19);
        $points[]=array(22,24);
        $points[]=array(28,29);
        $points[]=array(30,33);
        $points[]=array(32,33);
        $points[]=array(34,35);
        
        for ($i = 0; $i < count($points); $i++) {
            echo '<tr>';
            $point_str = serialize($points[$i]);
            echo '<td>' . $point_str . '</td>';
            $sql = "INSERT INTO incompatible_enchantment(id,enchants) VALUES('".$i."','".$point_str."')";
            mysqli_query($this->db,$sql); 
        }
    }
    
    public function get_enchants() {
        if($this->enchants = null){
            $query = "SELECT * FROM enchantments";
            $result = mysqli_query($this->db, $query) or die('Error querying database.');

            while ($row = mysqli_fetch_array($result)) {
                $this->enchants[] = new \MinecraftWrapper\Enchant\Enchant($this, $row);
            }
        }
        return $this->enchants;
    }
    
    public function get_items() {
        if($this->items = null){
            $query = "SELECT * FROM enchantable_items";
            $result = mysqli_query($this->db, $query) or die('Error querying database.');

            while ($row = mysqli_fetch_array($result)) {
                $this->enchants[] = new \MinecraftWrapper\Item\Enchantable_item($this, $row);
            }
        }
        return $this->items;
    }
}
