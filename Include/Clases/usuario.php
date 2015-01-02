<?php
	
	session_start();

class usuario {

	public static function inicio(){
	echo'
		<div class="page-canvas">
			<form name="formLogin" id="formLogin" style="width:75%;" method="get" action="./" '.centrar.' >
			<input type="hidden" value="LogIn" id="q" name="q">
			
			<fieldset><legend>Login</legend>
				'.o_formGroup_InputGroup.'
					'.o_InputGroupAddon.o_sprite.'icon-user'.c_sprite.c_InputGroupAddon.'
					'.o_input.' id="formLoginUsuario" name="formLoginUsuario" placeholder="Usuario" autocomplete="off" autofocus '.c_input.'
				'.c_formGroup_InputGroup.'
				'.o_formGroup_InputGroup.'
					'.o_InputGroupAddon.o_sprite.'icon-key2'.c_sprite.c_InputGroupAddon.'
					'.o_input_PWD.' id="formLoginPass" name="formLoginPass" placeholder="Contraseña" '.c_input.'
				'.c_formGroup_InputGroup.'
				'.o_botonera.'
					<button type="submit" class="btn btn-primary" id="formLoginSubmit" name="formLoginSubmit">
						'.o_btnjQuery.'icon-login'.m_btnjQuery.'Log In'.c_btnjQuery.'
					</button>
				'.c_botonera.'
				'.jQueryLoading.'
			</fieldset>
			</form>
		</div>
	';
	/* NOTA
		
	*/
	}
	
	public static function LogIn($login, $pass){
		$consulta = design::consultaDB('consulta',"select * from tbUsuario where (usu_login='".$login."' and usu_psw='".md5($pass)."')");
		$perfil = './';
		if(design::consultaDB('num_rows',$consulta)>0){
			$row = design::consultaDB('fetch_array',$consulta);
			if($row['fk_perid']==1 || $row['fk_perid']==2) {
					 if($row['fk_perid']==1) $_SESSION['SU'] = $login;
				elseif($row['fk_perid']==2) $_SESSION['userAdministrador'] = $login;
				$perfil = './Administrador';
			} elseif($row['fk_perid']==3) $_SESSION['userBibliotecaria'] = $login;
		}
		header('Location: '.$perfil);
	}
	
	public static function LoggEd($perfil){
		self::menu($perfil);
		echo'<div class="wrapper"></div>';
	}
	
	public static function menu($perfil='Bibliotecaria'){
	echo'<div class="menuVertical">
		<fieldset class="botoneraAdmin">
	';
		if($perfil=='SU' || $perfil=='Administrador'){
			echo'
			<legend>Administración de usaurios <br>'.self::getCurrentSession('login').'</legend>
				<ul>
					<li><a href="./?q=nuevoUsuario" class="btn btn-default">Ingresar</a></li>
					<li><a href="./?q=modificarUsuario" class="btn btn-default">Modificar</a></li>
					<li><a href="./?q=buscarUsuario" class="btn btn-default">Buscar</a></li>
					<li><a href="./?q=eliminarUsuario" class="btn btn-default">Eliminar</a></li>
					<li><a href="./?q=reporteUsuario" class="btn btn-default">Reportes</a></li>
					<li><a href="./?q=LogOut" class="btn btn-default">Salir</a></li>
				</ul>
			';
		} elseif($perfil=='Bibliotecaria') {
			echo'
			<legend>Administración de usaurios</legend>
				<ul>
					<li><a href="./?q=#" class="btn btn-default">Catalogación</a>
						<ul>
							<li><a href="./?q=catalogacion" class="btn btn-info">Categoria y tipo de articulo</a></li>
						</ul>
					</li>
					<li><a href="#" class="btn btn-default">Gestor de articulo</a>
						<ul>
							<li><a href="./?q=nuevoLibro" class="btn btn-info">Nuevo</a></li>
							<li><a href="./?q=ejemplares" class="btn btn-info">Agregar copia</a></li>
							<li><a href="./?q=consultarLibro" class="btn btn-info">Consultar</a></li>
						</ul>
					</li>
					<li><a href="./?q=#" class="btn btn-default">Reportes</a></li>
					<li><a href="./?q=LogOut" class="btn btn-default">Salir</a></li>
				</ul>
			';
		}
	echo '</fieldset>
	<textarea id="ayuda" disabled>Desplace por los menús de opciones para obtener ayuda :)</textarea>
	</div>';
	}
	
	public static function LogOut($sessionTipo){
			 if($sessionTipo=='SU') unset($_SESSION['SU']);
		elseif($sessionTipo=='Administrador') unset($_SESSION['userAdministrador']);
		elseif($sessionTipo=='Bibliotecaria') unset($_SESSION['userBibliotecaria']);
		header('Location: ./');
	}
	
	
/* ***************************************************************** */
/* ******************* A D M I N I S T R A D O R ******************* */
/* ***************************************************************** */
	public static function nuevoUsuario($perfil='Administrador'){
	self::menu($perfil);
	echo '<div class="wrapper">
	<form id="formNuevaPersona">
	<fieldset><legend>Datos Personales</legend>
		'.o_formGroup_InputGroup.'
			<span class="input-group-addon"><span class=" icon-skull2"></span></span>
			<input maxlength="10" type="text" name="formNuevoUsuarioCI" id="formNuevoUsuarioCI" placeholder="Ingrese su cédula" autofocus '.o_Numeros.' class="form-control" >
		'.c_formGroup_InputGroup.'
		'.o_formGroup_InputGroup.'
			<span class="input-group-addon"><span class="icon-user3"></span></span>
			<input type="text" name="formNuevoUsuarioNombre" id="formNuevoUsuarioNombre" placeholder="Ingrese su nombre" '.o_Letras.' class="form-control" >
		'.c_formGroup_InputGroup.'
		'.o_formGroup_InputGroup.'
			<span class="input-group-addon"><span class="icon-birthday"></span></span>
			<input type="text" id="formNuevoUsuarioCumpleanos" name="formNuevoUsuarioCumpleanos" placeholder="Ingrese su fecha de nacimiento" class="form-control" readonly />
		'.c_formGroup_InputGroup.'
		'.o_formGroup_InputGroup.'
			<span class="input-group-addon"><span class="icon-at"></span></span>
			<input maxlength="50" type="text" id="formNuevoUsuarioEmail" name="formNuevoUsuarioEmail" placeholder="Ingrese su email" class="form-control" >
		'.c_formGroup_InputGroup.'
		'.o_formGroup_InputGroup.'
			<span class="input-group-addon"><span class="icon-phone2"></span></span>
			<input maxlength="10" type="text" id="formNuevoUsuarioTelefono" name="formNuevoUsuarioTelefono" placeholder="Ingrese su telefono" '.o_Numeros.' class="form-control" >
		'.c_formGroup_InputGroup.'
	</fieldset>
	<button class="btn btn-default" id="formNuevaPersonaSubmit" type="Submit">Siguiente</button>
	</form>
	
	<form id="formNuevoUsuario" '.oculto.'>
	<fieldset>
		<legend>Datos de Usuario</legend>
		'.o_formGroup_InputGroup.'
			<span class="input-group-addon"><span class="icon-derek-mui-invader"></span></span>
			<input type="text" name="formNuevoUsuarioUsuarioLogin" id="formNuevoUsuarioUsuarioLogin" placeholder="Ingrese su usuario" class="form-control" >
		'.c_formGroup_InputGroup.'
		'.o_formGroup_InputGroup.'
			<span class="input-group-addon">
				<input type="radio" id="radio1" value="2" name="formNuevoUsuarioPerfil" />
				<label for="radio1"><span class="icon-male2"></span>Administrador</label>
			</span>
			<span class="input-group-addon">
				<label for="radio2">Bibliotecaria<span class="icon-female2"></span></label>
				<input type="radio" id="radio2" value="3" name="formNuevoUsuarioPerfil" />
			</span>
		'.c_formGroup_InputGroup.'
		'.o_formGroup_InputGroup.'
			<span class="input-group-addon"><span class="icon-key2"></span></span>
			<input maxlength="20" type="password" name="formNuevoUsuarioUsuarioPass" id="formNuevoUsuarioUsuarioPass" placeholder="Ingrese su contraseña" class="form-control" >
		'.c_formGroup_InputGroup.'
		'.o_formGroup_InputGroup.'
			<span class="input-group-addon"><span class="icon-key2"></span></span>
			<input maxlength="20" type="password" name="formNuevoUsuarioUsuarioPass_" id="formNuevoUsuarioUsuarioPass_" placeholder="Confirme su contraseña" class="form-control" >
		'.c_formGroup_InputGroup.'
	</fieldset>
	<button class="btn btn-default" id="formNuevoUsuarioBack">Regresar</button>
	<button class="btn btn-default" id="formNuevoUsuarioSubmit" type="Submit">Aceptar</button>
	</form>
	</div> <!-- wrapper -->';
	}
	
	public static function saveNuevoUsuario($ci,$name,$cumple,$email,$telf,$userID,$perfil,$pass){
		$estado = 'Activo';
		$consulta = "select * from tbPersona where (perso_cedula='".$ci."' and perso_email='".$email."')";
		if(design::consultaDB('num_rows_auto',$consulta)<1){
			$consulta = "select * from tbUsuario where (usu_login='".$userID."')";
			if(design::consultaDB('num_rows_auto',$consulta)<1){
			
				$consulta = "INSERT INTO tbPersona(perso_nombre, perso_cedula, perso_fechanacimiento, perso_email, perso_telefono)
										VALUES ('".$name."', '".$ci."', '".$cumple."', '".$email."', '".$telf."');";
				if(design::consultaDB('consulta',$consulta)){
					$lastID = design::consultaDB('fetch_array_auto',"select * from tbPersona where (perso_cedula='".$ci."')");
					$consulta = "INSERT INTO tbUsuario(usu_login, usu_psw, usu_estado, fk_persoid, fk_perid)
													VALUES ('".$userID."', '".md5($pass)."', '".$estado."', ".$lastID['perso_id'].", ".$perfil.");";
					if(design::consultaDB('consulta',$consulta)){
						$salidaJson['estado'] = true;
						$salidaJson['mensaje'] = 'Nuevo usuario guardado con éxito!';
					} else $salidaJson['mensaje'] = '<b>Error </b>No se pudo guardar el usuario!';
				} else $salidaJson['mensaje'] = '<b>Error </b>No se pudo guardar la persona!';
			
			} else $salidaJson['mensaje'] = '<b>Error </b>Este usuario ya se han registrado anteriormente!';
		} else $salidaJson['mensaje'] = '<b>Error </b>El número de cédula o correo electrónico ya se han registrado anteriormente!';
		
		return $salidaJson;
	}
	
	public static function modificarUsuario($perfil='Administrador'){
	self::menu($perfil);
	echo '<div class="wrapper">
		
		'.o_table.' editinUsuarios">'.c_table.'
		
	'.o_input_Hide.' id="opcionjQuery" value="editinUsuarios" '.c_input.'
	</div> <!-- wrapper -->';
	}
	
	public static function buscarUsuario($perfil='Administrador'){
		self::menu($perfil);
		echo '<div class="wrapper">
		
				<div class="input-group">
					<span class="input-group-addon"><span class=" icon-skull2"></span></span>
					<input type="text" name="formbuscarUsuario" id="formbuscarUsuario" placeholder="Ingrese el criterio de busqueda" class="form-control" >
				
					<span class="input-group-btn">
						<button id="btnBusca" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
						  Criterio de busqueda
						  <span class="caret"></span>
						</button>
						<ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="dLabel">
						  <li><a href="#" id="buscarCI" >Cedula</a></li>
						  <li><a href="#" id="buscarNom" >Nombres</a></li>
						  <li><a href="#" id="buscarUser">Usuario</a></li>
						</ul>
					</span>
				</div>
				
				
				<fieldset id="presentaresultado" style="display:none;"><legend>Datos Usuario</legend>
					<div id="resultados">###############</div>
				</fieldset>
			
			</div> <!-- wrapper -->';
	}
	
	public static function reporteUsuario($perfil='Administrador'){
	self::menu($perfil);
	echo '<div class="wrapper">
	
		'.o_table.' reporteUsuario">'.c_table.'
	
	'.o_input_Hide.' id="opcionjQuery" value="reporteUsuario" '.c_input.'
	</div> <!-- wrapper -->';
	}
	
	
/* ***************************************************************** */
/* ******************* B I B L I O T E C A R I A ******************* */
/* ***************************************************************** */
	public static function catalogacion($perfil='Bibliotecaria'){
	self::menu($perfil);
	echo '<div class="wrapper">
	
	<ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
	  <li role="presentation" class="active"><a href="#tipoLibro" aria-controls="tipoLibro" role="tab" data-toggle="tab">Tipo</a></li>
	  <li role="presentation"><a href="#categoriaLibro" aria-controls="categoriaLibro" role="tab" data-toggle="tab">Categoria</a></li>
	</ul>
	<div class="tab-content">
	  <div role="tabpanel" class="tab-pane active" id="tipoLibro">
		
		'.o_table.' showTipo">'.c_table.'
		<form id="formNuevoTipo" '.oculto.'>
		<fieldset><legend>Tipo</legend>
			'.o_formGroup_InputGroup.'
				'.o_InputGroupAddon.o_sprite.'icon-derek-mui-invader'.c_sprite.c_InputGroupAddon.'
				'.o_input.' name="formNuevoTipoNombre" id="formNuevoTipoNombre" placeholder="Tipo" '.c_input.'
			'.c_formGroup_InputGroup.'
			'.o_formGroup_InputGroup.'
				'.o_InputGroupAddon.o_sprite.'icon-derek-mui-invader'.c_sprite.c_InputGroupAddon.'
				'.o_txtarea.' name="formNuevoTipoComentario" id="formNuevoTipoComentario" placeholder="Comentario" '.c_txtarea.'
			'.c_formGroup_InputGroup.'
		</fieldset>
		<button class="btn btn-default" id="formNuevoTipoSubmit" type="Submit">Guardar</button>
		</form>
		'.o_btn_Info.' id="NuevoTipo">Nuevo tipo'.c_btn.'
		
	  </div>
	  <div role="tabpanel" class="tab-pane" id="categoriaLibro">
		
		'.o_table.' showCategoria">'.c_table.'
		<form id="formNuevaCategoria" '.oculto.'>
		<fieldset><legend>Categoria</legend>
			'.o_formGroup_InputGroup.'
				'.o_InputGroupAddon.o_sprite.'icon-derek-mui-invader'.c_sprite.c_InputGroupAddon.'
				'.o_input.' name="formNuevaCategoriaNombre" id="formNuevaCategoriaNombre" placeholder="Categoria" '.c_input.'
			'.c_formGroup_InputGroup.'
			'.o_formGroup_InputGroup.'
				'.o_InputGroupAddon.o_sprite.'icon-derek-mui-invader'.c_sprite.c_InputGroupAddon.'
				'.o_txtarea.' name="formNuevaCategoriaComentario" id="formNuevaCategoriaComentario" placeholder="Comentario" '.c_txtarea.'
			'.c_formGroup_InputGroup.'
		</fieldset>
		<button class="btn btn-default" id="formNuevaCategoriaSubmit" type="Submit">Guardar</button>
		</form>
		'.o_btn_Info.' id="NuevaCategoria">Nueva categoria'.c_btn.'
		
	  </div>
	</div>
	
	'.o_input_Hide.' id="opcionjQuery" value="catalogacion" '.c_input.'
	</div> <!-- wrapper -->';
	}
	
	public static function nuevoLibro($perfil='Bibliotecaria'){
	self::menu($perfil);
	echo '<div class="wrapper">
	<form id="formNuevoLibro">
	<fieldset><legend>Datos de Libro</legend>
		'.o_formGroup_InputGroup.'
			'.o_InputGroupAddon.o_sprite.'icon-derek-mui-invader'.c_sprite.c_InputGroupAddon.'
			'.o_select.' name="formNuevoLibroTipo" id="formNuevoLibroTipo">'.c_select.'
		'.c_formGroup_InputGroup.'
		'.o_formGroup_InputGroup.'
			'.o_InputGroupAddon.o_sprite.'icon-derek-mui-invader'.c_sprite.c_InputGroupAddon.'
			'.o_select.' name="formNuevoLibroCategoria" id="formNuevoLibroCategoria">'.c_select.'
		'.c_formGroup_InputGroup.'
		
		'.o_formGroup_InputGroup.'
			'.o_InputGroupAddon.o_sprite.'icon-derek-mui-invader'.c_sprite.c_InputGroupAddon.'
			'.o_input.' name="formNuevoLibroTitulo" id="formNuevoLibroTitulo" placeholder="Título del libro" '.c_input.'
		'.c_formGroup_InputGroup.'
		'.o_formGroup_InputGroup.'
			'.o_InputGroupAddon.o_sprite.'icon-derek-mui-invader'.c_sprite.c_InputGroupAddon.'
			'.o_input.' name="formNuevoLibroAutor" id="formNuevoLibroAutor" placeholder="Autor del libro" '.c_input_Let.'
		'.c_formGroup_InputGroup.'
		'.o_formGroup_InputGroup.'
			'.o_InputGroupAddon.o_sprite.'icon-derek-mui-invader'.c_sprite.c_InputGroupAddon.'
			'.o_input.' name="formNuevoLibroEditorial" id="formNuevoLibroEditorial" placeholder="Casa editorial" '.c_input.'
		'.c_formGroup_InputGroup.'
		'.o_formGroup_InputGroup.'
			'.o_InputGroupAddon.o_sprite.'icon-derek-mui-invader'.c_sprite.c_InputGroupAddon.'
			'.o_input.' name="formNuevoLibroFechaPublic" id="formNuevoLibroFechaPublic" placeholder="Fecha de publicacion" '.c_input_Date.'
		'.c_formGroup_InputGroup.'
		'.o_formGroup_InputGroup.'
			'.o_InputGroupAddon.o_sprite.'icon-derek-mui-invader'.c_sprite.c_InputGroupAddon.'
			'.o_txtarea.' name="formNuevoLibroDescripcion" id="formNuevoLibroDescripcion" placeholder="Descripcion del libro" '.c_txtarea.'
		'.c_formGroup_InputGroup.'
	</fieldset>
	<button class="btn btn-default" id="formNuevoLibroSubmit" type="Submit">Guardar</button>
	</form>
	
	'.o_input_Hide.' id="opcionjQuery" value="formNuevoLibro" '.c_input.'
	</div> <!-- wrapper -->';
	}
	
	public static function saveNuevoLibro($titulo,$autor,$editorial,$fecha,$descripcion,$tipo,$categoria){
		$consulta = "INSERT INTO tbLibro(libro_titulo, libro_autor, libro_fechapublicacion,
										libro_editorial, libro_comentario, fk_tipoid, fk_categoriaid)
										VALUES ('".$titulo."', '".$autor."', '".$fecha."',
												'".$editorial."', '".$descripcion."', ".$tipo.", ".$categoria.")";
		if(design::consultaDB('consulta',$consulta)){
			$salidaJson['estado'] = true;
			$salidaJson['mensaje'] = 'Libro guardado con éxito!';
		} else $salidaJson['mensaje'] = '<b>Error </b>Error a guardar el dato!';
		
		return $salidaJson;
	}
	
	public static function ejemplares($perfil='Bibliotecaria'){
	self::menu($perfil);
	echo '<div class="wrapper">
	<form id="formNuevoEjemplar">
		<fieldset><legend>Datos de Libro</legend>
			'.o_formGroup_InputGroup.'
				'.o_InputGroupAddon.o_sprite.'icon-derek-mui-invader'.c_sprite.c_InputGroupAddon.'
				'.o_select.' name="formNuevoEjemplarAutor" id="formNuevoEjemplarAutor">'.c_select.'
			'.c_formGroup_InputGroup.'
			'.o_formGroup_InputGroup.'
				'.o_InputGroupAddon.o_sprite.'icon-derek-mui-invader'.c_sprite.c_InputGroupAddon.'
				'.o_select.' name="formNuevoEjemplarID" id="formNuevoEjemplarID">'.c_select.'
			'.c_formGroup_InputGroup.'
			'.o_formGroup_InputGroup.'
				'.o_InputGroupAddon.o_sprite.'icon-derek-mui-invader'.c_sprite.c_InputGroupAddon.'
				'.o_input.' name="formNuevoEjemplarCodigo" id="formNuevoEjemplarCodigo" placeholder="Numero de ejemplares" '.c_input_Num.'
			'.c_formGroup_InputGroup.'
		</fieldset>
		<button class="btn btn-default" id="formNuevoEjemplarSubmit" type="Submit">Agregar</button>
	</form>
	
	'.o_input_Hide.' id="opcionjQuery" value="formNuevoEjemplarAutor" '.c_input.'
	</div> <!-- wrapper -->';
	//
	}
	
	public static function saveNuevoEjemplar($libroID,$codigo){
		$salidaJson['estado'] = false;
		$salidaJson['mensaje'] = '<b>Nota: </b>';
		
		if(design::consultaDB('num_rows_auto',"select * from tbLibro where libro_id=".$libroID)>0){
			if(design::consultaDB('num_rows_auto',"select * from tbEjemplar where ejemplar_id=".$codigo)<1){
			
				if(design::consultaDB('consulta',"insert into tbEjemplar(ejemplar_codigo,ejemplar_estado,fk_libroid)
																values('".$codigo."', 'En stock', ".$libroID.")")){
					$salidaJson['estado'] = true;
					$salidaJson['mensaje'] = 'Copia guardada! :)';
				} else $salidaJson['mensaje'] = 'Error al ingresar los ejemplares, intenta nuevamente';
			
			} else $salidaJson['mensaje'] .= 'Este código de libro ya está registrado';
		} else $salidaJson['mensaje'] .= 'Este libro no es real';
		
		return $salidaJson;
	}
	
	public static function consultarLibro($perfil='Bibliotecaria'){
	self::menu($perfil);
	echo '<div class="wrapper">
		
		'.o_table.' listaLibros">'.c_table.'
		
	'.o_input_Hide.' id="opcionjQuery" value="listaLibros" '.c_input.'
	
	</div> <!-- wrapper -->';
	}
	
	
/* ***************************************************************** */
/* ************************** E X T R A S ************************** */
/* ***************************************************************** */
	public static function getCurrentSession($opcion){
		if($opcion=='tipo'){
				 if(isset($_SESSION['SU'])) return 'SU';
			elseif(isset($_SESSION['userAdministrador'])) return 'Administrador';
			elseif(isset($_SESSION['userBibliotecaria'])) return 'Bibliotecaria';
		} elseif($opcion=='login') {
				 if(isset($_SESSION['SU'])) return $_SESSION['SU'];
			elseif(isset($_SESSION['userAdministrador'])) return $_SESSION['userAdministrador'];
			elseif(isset($_SESSION['userBibliotecaria'])) return $_SESSION['userBibliotecaria'];
		}
	}
	
	public static function detallesUsuario($login){
		return design::consultaDB('fetch_array_auto',"select * from tbUsuario where (usu_login='$login')");
	}
	
	public static function detallesPersona($perso_id){
		return design::consultaDB('fetch_array_auto',"select * from tbPersona where (perso_id=$perso_id)");
	}
	
}

?>