<?php
	
	include 'connectDB.php';
	include 'variables.php';
	include 'usuario.php';
	
class design {
	
	public static $root = './';
	private static $css = null;
	private static $js = null;
	public static $addjs = false;
	
	public static function consultaDB($opcion,$consulta){
		$connectDB = new connectDB();
		return $connectDB->consulta_($opcion,$consulta);
	}
	
	public static function header($title='default'){
	
	if($title=='default') $title= myWebPage;
	else {
		self::$root = '../';
		$title = myWebPage.' | '.$title;
	}
	self::$css = self::$root.'Include/css/';
	self::$js = self::$root.'Include/js/';
		
	echo '
	<!DOCTYPE html>
	<html lang="es">
	<head>
		<title>'.$title.'</title>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
			
		'.style.self::$css.'jquery-ui-1.11.2/jquery-ui.css">
		'.style.self::$css.'bootstrap-3.3.1/bootstrap.css">
		'.style.self::$css.'icons/icons.css">
		'.style.self::$css.'style.css">
		
		'.script.self::$js.'jquery-2.1.1.js'.c_script.'
	</head>
	<body>
	
	<div id="html">
		<header>
			<h1><a href="./">BLINUNYA</a></h1>
		</header>
		<div id="body">
	';
	}
	
	
	
	public static function footer(){
	echo '
		</div> <!-- body -->
	</div> <!-- html -->
		<footer>
			<ul>
				<li>Designed by: Monino7</li>
			</ul>
		</footer>
		
		<div id="myModalBoootstrap" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-sm">
			 <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  <h4 class="modal-title">Alerta</h4>
				</div>
				<div class="modal-body">
					<span id="dialogAlertaMensaje"></span>
				</div>
				<div class="modal-footer">
				  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			 </div>
		  </div>
		</div>
		
		
		'.script.self::$js.'jquery-validation-1.9.0/jquery.validate.min.js'.c_script.'
		'.script.self::$js.'bootstrap.js'.c_script.'
		'.script.self::$js.'jquery-ui.js'.c_script.'
		'.script.self::$js.'funciones.js'.c_script.'
		';
		if(self::$addjs) echo script.'../Include/js/admin.js'.c_script;
		else echo script.'./Include/js/biblio.js'.c_script;
	echo '
	</body>
	</html>
	';
	}

}
 
?>