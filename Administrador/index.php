<?php
	
	include '../Include/Clases/design.php';

class index {

	public function index(){
		design::header('Administrador');
		
		if(isset($_SESSION['SU']) || isset($_SESSION['userAdministrador'])){
				design::$addjs = true;
			    if($_GET['q']=='LogOut') usuario::LogOut(usuario::getCurrentSession('tipo'));
			elseif($_GET['q']=='nuevoUsuario') usuario::nuevoUsuario();
			elseif($_GET['q']=='modificarUsuario') usuario::modificarUsuario();
			elseif($_GET['q']=='buscarUsuario') usuario::buscarUsuario();
			elseif($_GET['q']=='reporteUsuario') usuario::reporteUsuario();
			else usuario::LoggEd(usuario::getCurrentSession('tipo'));
		} else {
			if(isset($_GET['q'])){
				if($_GET['q']=='LogIn') usuario::LogIn($_GET['formLoginUsuario'], $_GET['formLoginPass']);
				else header('Location: ../');
			} else header('Location: ../');
		}
		
		
		design::footer();
	}

}

$index = new index();


 
?>
