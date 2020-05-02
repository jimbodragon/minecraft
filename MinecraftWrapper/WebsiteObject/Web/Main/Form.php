<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$form_interface_file_content = file_get_contents('WebsiteObject/Web/Form/Form.php', FALSE);
str_replace('namespace MinecraftServerWrapper\WebsiteObject\Web\Form;', 'namespace MinecraftServerWrapper\WebsiteObject\Web\Main\Form;', $form_interface_file_content);
str_replace('abstract class Form extends \MinecraftServerWrapper\WebsiteObject\Web\HTMLString',
        'abstract class Form extends \MinecraftServerWrapper\WebsiteObject\Web\Main\HTMLString', $form_interface_file_content);
str_replace('public function __construct', 'protected function __construct', $form_interface_file_content);

str_replace('<?php', '', $form_interface_file_content);
#echo $form_interface_file_content;

#eval("$form_interface_file_content");

#echo system('pwd');