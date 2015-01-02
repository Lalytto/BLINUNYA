<?php
	
	/* *********************************************** Querys *********************************************** */
	
	/* *********************************************** Style *********************************************** */
	define('myWebPage','BLINUNYA');
	
	define('oculto','class="oculto"');
	define('centrar','class="centrar"');
	
	define('style','<link rel="stylesheet" href="');
	define('script','<script src="');
	define('c_script','"></script>');
	
	/* *********************************************** Formularios *********************************************** */
	define('o_Numeros','onKeyDown="return numeros(this);"');
	define('o_Letras','onKeyDown="return letras(this);"');
	
	define('o_form','<form');
		define('o_formGroup_InputGroup','<div class="form-group"><div class="input-group">');
			define('o_InputGroupAddon','<span class="input-group-addon">');
				define('o_sprite','<span class="');
				define('c_sprite','"></span>');
				
				define('o_spriteText','<span class="spriteText fontBold">');
				define('c_spriteText','</span>');
			define('c_InputGroupAddon','</span>');
			
			define('o_InputGroupBtn','<span class="input-group-btn">');
				//define('o_btnAddon','<button class="btn btn-default"');
				//define('m_btnAddon','">');
				//define('c_btnAddon','</button>');
				// BOTONES ABAJO
			define('c_InputGroupBtn','</span>');
			
			define('o_input','<input type="text"');
			define('o_input_PWD','<input type="password"');
			define('o_input_Hide','<input type="hidden"');
			
			define('c_input','class="form-control">');
			define('c_input_Num','class="form-control" '.o_Numeros.'>');
			define('c_input_Let','class="form-control" '.o_Letras.'>');
			define('c_input_Date','class="form-control fontBold datepicker" readonly >');
			define('c_input_NOedit','class="form-control fontBold" readonly >');
			
			define('o_select','<select class="form-control"');
			define('c_select','</select>');
			
			define('o_txtarea','<textarea rows="4"');
			define('c_txtarea','class="form-control"></textarea>');
		define('c_formGroup_InputGroup','</div></div>');
		
		define('jQueryLoading','<div class="jQueryLoading"></div>');
		
		define('o_botonera','<div class="formDivBotones">');
			define('o_btn','<button class="btn btn-default"');
			define('o_btn_Info','<button class="btn btn-info"');
			define('c_btn','</button>');
		
			define('o_btnjQuery','<span class="btnjQueryIcon ');
			define('m_btnjQuery','"></span><span class="btnjQueryText">');
			define('c_btnjQuery','</span>');
		define('c_botonera','</div>');
	define('c_form','</form>');
	
	define('o_table','<table class="table table-hover');
	define('c_table','</table>');
	
	
?>