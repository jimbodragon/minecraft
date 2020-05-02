<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Minecraft Wrapper</title>
        <link rel="stylesheet" type="text/css" href="view.css" media="all">
        <script type="text/javascript" src="view.js"></script>
        <title></title>
    </head>
    <body id="main_body" >
	
	<img id="top" src="top.png" alt="">
	<div id="form_container">
        <h1><a>Minecraft Wrapper</a></h1>
        <form id="minecraftwrapper" class="appnitro"  method="post" action="">
            <div class="form_description">
                <h2>Enchant Item</h2>
                <p>Enchant an item</p>
            </div>
            <ul >
			
					<li id="li_1" >
		<label class="description" for="element_1">ListUser </label>
		<div>
		<select class="element select medium" id="element_1" name="element_1"> 
			<option value="" selected="selected"></option>
<option value="1" >Jean</option>
<option value="2" >Jacques</option>
<option value="3" >Patate</option>

		</select>
		</div> 
		</li>		<li id="li_2" >
		<label class="description" for="element_2">List Item </label>
		<div>
		<select class="element select medium" id="element_2" name="element_2"> 
			<option value="" selected="selected"></option>
<option value="1" >Sword</option>
<option value="2" >Axe</option>
<option value="3" >Bow</option>

		</select>
		</div> 
		</li>		<li id="li_3" >
		<label class="description" for="element_3">List Material </label>
		<div>
		<select class="element select medium" id="element_3" name="element_3"> 
			<option value="" selected="selected"></option>
<option value="1" >Wood</option>
<option value="2" >Stone</option>
<option value="3" >Diamond</option>

		</select>
		</div> 
		</li>		<li id="li_4" >
		<label class="description" for="element_4">Enchant </label>
		<span>
			<input id="element_4_1" name="element_4_1" class="element checkbox" type="checkbox" value="1" />
<label class="choice" for="element_4_1">Flames</label>
<input id="element_4_2" name="element_4_2" class="element checkbox" type="checkbox" value="1" />
<label class="choice" for="element_4_2">Punch</label>
<input id="element_4_3" name="element_4_3" class="element checkbox" type="checkbox" value="1" />
<label class="choice" for="element_4_3">Mending</label>

		</span> 
		</li>
			
					<li class="buttons">
			    <input type="hidden" name="form_id" value="106884" />
			    
				<input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
		</li>
			</ul>
        <?php
            include_once 'Database.php';
            
            $db = new \MineCraftServerWrapper\Database();
            
            print_r($_GET);
            print_r($_POST);
            
            unset($db);
        ?>
            <button id="saveForm" form="minecraftwrapper" type="submit" formtarget="_self" value="submit" />
        </form>
	<img id="bottom" src="bottom.png" alt="">
    </body>
</html>
