<?php
	
	include './design.php';

class funciones {
	
	public static function LogIn($user,$pass){
		sleep(2);
		$salidaJson['estado'] = false;
		$salidaJson['enviarGet'] = false;
		$salidaJson['mensaje'] = '<b>Error x54: </b>';
		$consulta = "select * from tbUsuario where (usu_login='$user' and usu_psw='".md5($pass)."')";
		if(design::consultaDB('num_rows_auto',$consulta)>0) {
			$salidaJson['estado'] = true;
			$salidaJson['enviarGet'] = true;
			$salidaJson['mensaje'] = 'Bienvenido!';
		} else $salidaJson['mensaje'] .= 'Usuario o contraseña incorrecta!';
		
		echo json_encode($salidaJson);
	}
	
	
/* ***************************************************************** */
/* ******************* A D M I N I S T R A D O R ******************* */
/* ***************************************************************** */
	public static function nuevoUsuario($ci,$name,$cumple,$email,$telf,$user,$perfil,$pass){
		$cumple = self::fechaEspanol($cumple);
		
		echo json_encode(usuario::saveNuevoUsuario($ci,$name,$cumple,$email,$telf,$user,$perfil,$pass));
	}
	
	public static function listadeUsuarios($campo,$valor){
		$tabla = 'tbUsuario';
		$salidaJson['mensaje'] = 'No hay reultados en la búsqueda!';
		
		if($campo=='perso_cedula' || $campo=='perso_nombre'){
			$tabla = 'tbPersona';
			$consulta = "select * from ".$tabla." where upper(".$campo.") LIKE upper('%".$valor."%')";
			if(design::consultaDB('num_rows_auto',$consulta)>0){
				$persona = design::consultaDB('fetch_array_auto',$consulta);
				$tabla = 'tbUsuario';
				$valor = $persona['perso_id'];
				$usuario = design::consultaDB('fetch_array_auto',"select * from ".$tabla." where (fk_persoid=".$valor.")");
			} else goto fin;
		} else {
			$consulta = "select * from ".$tabla." where upper(".$campo.") LIKE upper('%".$valor."%')";
			if(design::consultaDB('num_rows_auto',$consulta)>0){
				$usuario = design::consultaDB('fetch_array_auto',$consulta);
				$tabla = 'tbPersona';
				$valor = $usuario['fk_persoid'];
				$persona = design::consultaDB('fetch_array_auto',"select * from ".$tabla." where (perso_id=".$valor.")");
			} else goto fin;
		}
		$salidaJson['mensaje'] = '<span>Nombre: </span><span>'.$persona['perso_nombre'].'</span><br>
								<span>Cédula: </span><span>'.$persona['perso_cedula'].'</span><br>
								<span>Telefono: </span><span>'.$persona['perso_telefono'].'</span><br>
								<span>Fecha de nacimiento: </span><span>'.$persona['perso_fechanacimiento'].'</span><br>
								<span>Email: </span><span>'.$persona['perso_email'].'</span><br>
								<span>Usuario: </span><span>'.$usuario['usu_login'].'</span><br>
								<span>Estado: </span><span>'.$usuario['usu_estado'].'</span>';
		fin:
		echo json_encode($salidaJson);
	}
	
	public static function setUsuario($campo,$valor,$id){
		$salidaJson['estado'] = false;
		$salidaJson['mensaje'] = '<b>Error: </b>Función editar usuario';
		$tabla = 'tbUsuario';
			$consulta = design::consultaDB('consulta',"update ".$tabla." set ".$campo."='".$valor."' where usu_id=".$id);
			if($consulta){
				$salidaJson['estado'] = true;
				$salidaJson['mensaje'] = '<b>Ok: </b>Update successful!<br><b>Field: </b>Profile';
			} else $salidaJson['mensaje'] .= '<br><b>Problem: </b>Error in the server!';
			
		echo json_encode($salidaJson);
	}
	
	
/* ***************************************************************** */
/* ******************* B I B L I O T E C A R I A ******************* */
/* ***************************************************************** */
	public static function nuevoLibro($titulo,$autor,$editorial,$fecha,$descripcion,$tipo,$categoria){
		$fecha = self::fechaEspanol($fecha);
		echo json_encode(usuario::saveNuevoLibro($titulo,$autor,$editorial,$fecha,$descripcion,$tipo,$categoria));
	}
	
	public static function nuevoEjemplar($libroID,$codigo){
		echo json_encode(usuario::saveNuevoEjemplar($libroID,$codigo));
	}
	
	
/* ***************************************************************** */
/* ************************** E X T R A S ************************** */
/* ***************************************************************** */
	public static function getSome($opcion,$valor){
		if($opcion=='listaLibros' || $opcion=='formNuevoEjemplarAutor' || $opcion=='formNuevoEjemplarID') {
			$tabla = 'tbLibro';
			$consulta = "select * from ".$tabla." order by libro_autor asc";
			if($opcion=='formNuevoEjemplarID') $consulta = "select * from ".$tabla." where libro_autor='".$valor."'";
			$consulta = design::consultaDB('consulta',$consulta);
			
			if(design::consultaDB('num_rows',$consulta)>0){
				while($row = design::consultaDB('fetch_array',$consulta)) {
					$estado = 'Agotado';
					$fk_tipoid = design::consultaDB('fetch_array_auto',"select * from tbTipo where tipo_id=".$row['fk_tipoid'])['tipo_nombre'];
					$fk_categoriaid = design::consultaDB('fetch_array_auto',"select * from tbCategoria where categoria_id=".$row['fk_categoriaid'])['categoria_nombre'];
					if(design::consultaDB('num_rows_auto',"select * from tbEjemplar where fk_libroid=".$row['libro_id'])>0) $estado = 'En stock';
					$salidaJson[]=array('id'=>$row['libro_id'], 'titulo'=>$row['libro_titulo'],
										'autor'=>$row['libro_autor'], 'fecha'=>$row['libro_fechapublicacion'],
										'editorial'=>$row['libro_editorial'], 'comentario'=>$row['libro_comentario'],
										'tipo'=>$fk_tipoid, 'categoria'=>$fk_categoriaid, 'estado'=>$estado);
				}
			} else $salidaJson['mensaje']= 'No existe ningun dato por el momento.';
			
		} elseif($opcion=='formNuevoLibro' || $opcion=='catalogacion') {
		
			$consulta = design::consultaDB('consulta',"select * from tbTipo");
			if(design::consultaDB('num_rows',$consulta)>0){
				while($row = design::consultaDB('fetch_array',$consulta)) {
					$salidaJson[]=array('tipoid'=>$row['tipo_id'], 'tiponombre'=>$row['tipo_nombre'], 'tipocomentario'=>$row['tipo_comentario']);
				}
			} else $salidaJson['mensaje']= 'No existe ningun dato por el momento, haga <a href="./?q=catalogacion">click aquí</a> para ingresar.';
			$consulta = design::consultaDB('consulta',"select * from tbCategoria");
			if(design::consultaDB('num_rows',$consulta)>0){
				while($row = design::consultaDB('fetch_array',$consulta)) {
					$salidaJson[]=array('categoriaid'=>$row['categoria_id'], 'categorianombre'=>$row['categoria_nombre'], 'categoriacomentario'=>$row['categoria_comentario']);
				}
			} else $salidaJson['mensaje']= 'No existe ningun dato por el momento, haga <a href="./?q=catalogacion">click aquí</a> para ingresar.';
		
		} elseif($opcion=='usuarios') {
			
			$consulta = design::consultaDB('consulta',"select * from tbUsuario where(fk_perid<>1) order by usu_login asc");
			if(design::consultaDB('num_rows',$consulta)>0){
				while($row = design::consultaDB('fetch_array',$consulta)) {
					$perfil = design::consultaDB('fetch_array_auto',"select * from tbPerfil where (per_id=".$row['fk_perid'].")")['per_nombre'];
					$salidaJson[]=array('id'=>$row['usu_id'], 'login'=>$row['usu_login'],
										'perfil'=>$perfil, 'estado'=>$row['usu_estado']);
				}
			} else $salidaJson['mensaje']= 'No existe ningun dato por el momento.';
			
		} else $salidaJson['mensaje']= 'No existe ninguna opción.';
		
		echo json_encode($salidaJson);
	}
	
	public static function addSome($opcion,$nombre,$comentario){
		$salidaJson['estado'] = false;
		$salidaJson['mensaje'] = 'No se pudo guardar el registro';
		
		if($opcion=='tipo') {
			$tabla = 'tbTipo';
		} elseif($opcion=='categoria') {
			$tabla = 'tbCategoria';
		}
		
		if(design::consultaDB('num_rows_auto',"select * from ".$tabla." where (".$opcion."_nombre='".$nombre."')")<1){
			$campos = $opcion."_nombre, ".$opcion."_comentario";
			$valores = "'".$nombre."','".$comentario."'";
			
			if(design::consultaDB('consulta',"insert into ".$tabla." (".$campos.") values(".$valores.")")){
				$salidaJson['estado'] = true;
				$salidaJson['mensaje'] = 'Su '.$opcion.' ha sido ingresada :)';
			}
		} else $salidaJson['mensaje'] = 'Este valor ya está registrado!';
		
		echo json_encode($salidaJson);
	}
	
	public static function setSome($id,$campo,$valor){
		$salidaJson['estado'] = false;
		$salidaJson['mensaje'] = 'No se pudo editar el campo';
		
		if($campo=='tipo_nombre' || $campo=='tipo_comentario') {
			$tabla = 'tbTipo';
			$tbid = 'tipo_id';
		} elseif($campo=='categoria_nombre' || $campo=='categoria_comentario') {
			$tabla = 'tbCategoria';
			$tbid = 'categoria_id';
		} elseif($campo=='libro_titulo' || $campo=='libro_autor' || $campo=='libro_fechapublicacion' || $campo=='libro_editorial') {
			$tabla = 'tbLibro';
			$tbid = 'libro_id';
			if($campo=='libro_fechapublicacion') $valor = self::fechaEspanol($valor);
		}
		
		if(design::consultaDB('num_rows_auto',"select * from ".$tabla." where (".$campo."='".$valor."')")<1){
			
			if(design::consultaDB('consulta',"update ".$tabla." set ".$campo."='".$valor."' where ".$tbid."=".$id)){
				$salidaJson['estado'] = true;
				$salidaJson['mensaje'] = 'Campo actualizado con éxito :)';
			}
			
		} else $salidaJson['mensaje'] = 'Este valor ya está registrado!';
		
		echo json_encode($salidaJson);
	}
	
	public static function findSome($campotabla,$valor){
		$salidaJson['estado'] = false;
		$salidaJson['mensaje'] = 'Este '.$campotabla.' ya esta registrado';
		
		if($campotabla=='email') {
			$tabla = 'tbPersona';
			$campotabla = 'perso_email';
		} elseif($campotabla=='usuario') {
			$tabla = 'tbUsuario';
			$campotabla = 'usu_login';
		} elseif($campotabla=='tipo') {
			$tabla = 'tbTipo';
			$campotabla = 'tipo_nombre';
		} elseif($campotabla=='categoria') {
			$tabla = 'tbCategoria';
			$campotabla = 'categoria_nombre';
		} elseif($campotabla=='codigo') {
			$tabla = 'tbEjemplar';
			$campotabla = 'ejemplar_codigo';
		}/* else {
			$salidaJson['mensaje'] = 'Las consultas no coinciden';
			goto fin;
		}*/
		
		$existe = design::consultaDB('num_rows_auto',"select * from ".$tabla." where (".$campotabla."='".$valor."')");
		
		if($existe==0) $salidaJson['estado'] = true;
		
		//fin:
		echo json_encode($salidaJson);
	}
	
	public static function fechaEspanol($fecha){
		$find   = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday", 
						"January,", "February,", "March,", "April,", "June,", "July,", "August,", "September,", "October,", "November,", "December,");
		$replace = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo",
						"de Enero de", "de Febrero de", "de Marzo de", "de Abril de", "", "de Mayo de",
						"de Junio de", "de Julio de", "de Agosto de", "de Septiembre de", "de Octubre de",
						"de Noviembre de", "de Diciembre de");
		$fecha = str_replace($find, $replace, $fecha);
		return $fecha;
	}
	
	public static function validaCI($ci){
		$salidaJson['estado'] = false;
		$salidaJson['mensaje'] = 'Cédula errónea';
		
		$nro_region=substr($ci, 0,2);
		if($nro_region>=1 && $nro_region<=24){
			$ult_digito=substr($ci, -1,1);
			//extraigo los valores pares//
			$valor2=substr($ci, 1, 1);
			$valor4=substr($ci, 3, 1);
			$valor6=substr($ci, 5, 1);
			$valor8=substr($ci, 7, 1);
			$suma_pares=($valor2 + $valor4 + $valor6 + $valor8);
			//extraigo los valores impares//
			$valor1=substr($ci, 0, 1);
			$valor1=($valor1 * 2);
			if($valor1>9){ $valor1=($valor1 - 9); }
			$valor3=substr($ci, 2, 1);
			$valor3=($valor3 * 2);
			if($valor3>9){ $valor3=($valor3 - 9); }
			$valor5=substr($ci, 4, 1);
			$valor5=($valor5 * 2);
			if($valor5>9){ $valor5=($valor5 - 9); }
			$valor7=substr($ci, 6, 1);
			$valor7=($valor7 * 2);
			if($valor7>9){ $valor7=($valor7 - 9); }
			$valor9=substr($ci, 8, 1);
			$valor9=($valor9 * 2);
			if($valor9>9){ $valor9=($valor9 - 9); }

			$suma_impares=($valor1 + $valor3 + $valor5 + $valor7 + $valor9);
			$suma=($suma_pares + $suma_impares);
			$dis=substr($suma, 0,1);//extraigo el primer numero de la suma
			$dis=(($dis + 1)* 10);//luego ese numero lo multiplico x 10, consiguiendo asi la decena inmediata superior
			$digito=($dis - $suma);
			if($digito==10){ $digito='0'; }//si la suma nos resulta 10, el decimo digito es cero
			if ($digito==$ult_digito){//comparo los digitos final y ultimo
				$existe = design::consultaDB('num_rows_auto',"select * from tbPersona where (perso_cedula='".$ci."')");
				if($existe<1){
					$salidaJson['estado'] = true;
				} else $salidaJson['mensaje'] = 'Este número de cédula ya se ha registrado anteriormente :('; 
			}
		}
		
		echo json_encode($salidaJson);
	}
	
	public static function errorX54(){
		$salidaJson['estado'] = false;
		$salidaJson['mensaje'] = 'Query no declarada!<br>Revisa la sentencia en funciones.js';
		echo json_encode($salidaJson);
	}
	
}


if(isset($_GET['q'])){
	
		if($_GET['q']=='LogIn') 			funciones::LogIn($_POST['formLoginUsuario'],$_POST['formLoginPass']);
	elseif($_GET['q']=='nuevoUsuario') 		funciones::nuevoUsuario($_POST['formNuevoUsuarioCI'],$_POST['formNuevoUsuarioNombre'],$_POST['formNuevoUsuarioCumpleanos'],$_POST['formNuevoUsuarioEmail'],$_POST['formNuevoUsuarioTelefono'],$_POST['formNuevoUsuarioUsuarioLogin'],$_POST['formNuevoUsuarioPerfil'],$_POST['formNuevoUsuarioUsuarioPass']);
	elseif($_GET['q']=='listadeUsuarios') 	funciones::listadeUsuarios($_GET['campo'],$_GET['valor']);
	elseif($_GET['q']=='setUsuario') 		funciones::setUsuario($_GET['campo'],$_GET['valor'],$_GET['id']);
	elseif($_GET['q']=='validaCI') 			funciones::validaCI($_GET['ci']);
	elseif($_GET['q']=='nuevoLibro')		funciones::nuevoLibro($_POST['formNuevoLibroTitulo'],$_POST['formNuevoLibroAutor'],$_POST['formNuevoLibroEditorial'],$_POST['formNuevoLibroFechaPublic'],$_POST['formNuevoLibroDescripcion'],$_POST['formNuevoLibroTipo'],$_POST['formNuevoLibroCategoria']);
	elseif($_GET['q']=='nuevoEjemplar')		funciones::nuevoEjemplar($_POST['formNuevoEjemplarID'],$_POST['formNuevoEjemplarCodigo']);
	elseif($_GET['q']=='getSome')			funciones::getSome($_GET['opcion'],$_GET['valor']);
	elseif($_GET['q']=='setSome')			funciones::setSome($_GET['id'],$_GET['campo'],$_GET['valor']);
	elseif($_GET['q']=='addSome')			funciones::addSome($_GET['opcion'],$_GET['nombre'],$_GET['comentario']);
	elseif($_GET['q']=='findSome')			funciones::findSome($_GET['campo'],$_GET['valor']);
	elseif($_GET['q']=='fechaEspanol')		echo json_encode(funciones::fechaEspanol($_GET['fecha']));
	else funciones::errorX54();

} else funciones::errorX54();
	
?>