<?php
	
	include './Include/Clases/design.php';

class index {

	public function index(){
		design::header();
		if(isset($_SESSION['userBibliotecaria'])){
				if($_GET['q']=='LogOut') 			usuario::LogOut(usuario::getCurrentSession('tipo'));
			elseif($_GET['q']=='catalogacion') 		usuario::catalogacion();
			elseif($_GET['q']=='nuevoLibro') 		usuario::nuevoLibro();
			elseif($_GET['q']=='ejemplares') 		usuario::ejemplares();
			elseif($_GET['q']=='consultarLibro') 	usuario::consultarLibro();
			else usuario::LoggEd(usuario::getCurrentSession('tipo'));
		} else {
			if(isset($_GET['q'])){
				if($_GET['q']=='LogIn') usuario::LogIn($_GET['formLoginUsuario'], $_GET['formLoginPass']);
				else header('Location: ./');
			} else usuario::inicio();
			
			
			
			
		}
		design::footer();
	}
	
}

$index = new index();
unset($index);

?>
