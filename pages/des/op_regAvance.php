<?php
	/**
	  * Nombre del Módulo: Desarrollo                                               
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 01/Diciembre/2011
	  * Descripción: Este archivo contiene las funciones para realizar las operaciones de la Bitacora de Avance
	  **/ 
	  
	  
	//Genera la Id de la Bitácora de Avance
	function obtenerIdBitAvance(){
		//Conectarse a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
				
		//Definir las tres letras en la Id de la Alerta
		$id_cadena = "BAV";
		
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
				
		//Obtener el mes actual y el año actual para ser agregados en la consulta y asi obtener las entradas del mes y año en curso
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener el numero de alertas registradas en la BD
		$stm_sql = "SELECT MAX(id_bitacora) AS clave FROM bitacora_avance WHERE id_bitacora LIKE 'BAV$mes$anio%'";
		//Ejecutar Sentencia
		$rs = mysql_query($stm_sql);		
		//Evaluar Resultados y Generar Id a partir de ellos
		if($datos=mysql_fetch_array($rs)){
			$cant = intval(substr($datos['clave'],7,3));
			$cant += 1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}
		
		//Cerrar la conexion con la BD
		mysql_close($conn);		
		
		return $id_cadena;
	}//Fin de la Funcion obtenerIdBitAvance()
	
	
	
	//Esta funcion guardará los datos de la bitácora de rezagado
	function guardarBitAvance(){
		//Conectarse a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		//Recuperar los datos del POST
		$idBit = $_POST['hdn_idBitacora'];
		$lugar = $_POST['cmb_lugar'];				
		$machote = str_replace(",","",$_POST['txt_machote']);
		$medida = str_replace(",","",$_POST['txt_medida']);
		$avance = str_replace(",","",$_POST['txt_avance']);
		$fechaReg = modFecha($_POST['txt_fechaRegistro'],3);
		$obs = strtoupper($_POST['txa_observaciones']);
		
		//Crear la Sentencia SQL para alamcenar lo datos en la Base de Datos
		$sql_stm = "INSERT INTO bitacora_avance(id_bitacora,catalogo_ubicaciones_id_ubicacion,fecha_registro,machote,medida,avance,observaciones) 
					VALUES('$idBit','$lugar','$fechaReg',$machote,$medida,$avance,'$obs')";
		//Ejecutar la Sentencia SQL
		$rs = mysql_query($sql_stm);
		
		if($rs){
			//Quitar de la SESSION los datos utilizados en el registro de la Bitacora de Avance			
			unset($_SESSION['bitsAgregadas']);
			
			//Guardar el Movimiento realizado en la tabla de Movimientos
			registrarOperacion("bd_desarrollo","$idBit","RegistroBitAvance",$_SESSION['usr_reg']);
			
			//Redireccionar a la pagina de exito
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";	
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";			
			//Cerrar la conexicion con la BD
			mysql_close();
		}												
	}//Cierre de la funcion guardarBitAvance()


?>