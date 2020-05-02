<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MineCraftServerWrapper\DataObject;

include_once 'Enchant.php';
include_once 'Enchantable_item.php';

/**
 * Description of database
 *
 * @author jimmy.provencher
 */

class Database {
    private $db;
    private $enchants;
    private $items;
    
    private static $_database;
    
    public static function get_database(): Database {
        if(is_null(self::$_database)){
            self::$_database = new UserState();
        }
        
        return self::$_database;
    }
    
    private function __construct() {
        $dbhost = 'localhost';
        $dbusername = 'my_webapp';
        $dbpasswd = 'tFCXS6Pw3Ql8UG9MZeW0InUf';
        $dbname = 'my_webapp';
        $this->db = mysqli_connect($dbhost, $dbusername, $dbpasswd, $dbname)
     or die('Error connecting to MySQL server.');
        mysqli_query($this->db, 'SET NAMES \'utf8\'');
        
        $this->enchants = array();
        $this->items = array();
        
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
        if(count($this->enchants) == 0){
            $query = "SELECT * FROM enchantments";
            $result = mysqli_query($this->db, $query) or die('Error querying database.');

            while ($row = mysqli_fetch_array($result)) {
                $this->enchants[] = new Enchant($this, $row);
            }
            mysqli_free_result($result);
        }
        return $this->enchants;
    }
    
    public function get_items() {
        if(count($this->items) == 0){
            $query = "SELECT * FROM enchantable_items";
            $result = mysqli_query($this->db, $query) or die('Error querying database.');

            while ($row = mysqli_fetch_array($result)) {
                $this->items[] = new Enchantable_item($this, $row);
            }
            mysqli_free_result($result);
        }
        return $this->items;
    }
    
    public function show_table() {
        
        echo '<table>';
        foreach ($this->get_enchants() as $enchant) {
            echo '<tr>';
            echo '<th>' . $enchant->name .'</th>';

            #' ' . $enchant->description() . ': ' . $enchant->enchant_name() . ' ' . $enchant->max_level() . ' -> ' . $enchant->url()
            echo '</tr>';
        }
        foreach ($this->get_items() as $item) {
            echo '<tr>';
            echo '<td>' . $item->name .'</td>';
            echo '<td>' . $item->use_material .'</td>';
            echo '</tr>';
        }
        echo '</table>';
    }
}
