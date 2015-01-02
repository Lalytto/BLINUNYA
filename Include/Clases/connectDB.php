<?php
	
class connectDB{
	
	private $conexion;
	
	public function connectDB(){
		if(!isset($this->conexion)){
			$this->conexion = pg_pconnect("host=127.0.0.1 port=5432 dbname=BLINUNYA user=postgres password=rootblinunya");
		}
	}
	
	public function consulta_($opcion,$consulta){
			if($opcion=='consulta')          return $this->consulta($consulta);
		elseif($opcion=='fetch_array')       return $this->fetch_array($consulta);
		elseif($opcion=='fetch_array_auto')  return $this->fetch_array($this->consulta_('consulta',$consulta));
		elseif($opcion=='num_rows')          return $this->num_rows($consulta);
		elseif($opcion=='num_rows_auto')     return $this->num_rows($this->consulta_('consulta',$consulta));
	}
	
	private function consulta($consulta){
		$resultado = pg_query($this->conexion,$consulta);
		if(!$resultado){
			echo 'Error: connectDB ';
			exit;
		}
		return $resultado;
	}
	
	private function fetch_array($consulta){
		return pg_fetch_array($consulta);
	}
	
	private function num_rows($consulta){
		return pg_num_rows($consulta);
	}
}

?>