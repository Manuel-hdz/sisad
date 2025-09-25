<?php
	/**
		* Nombre del Módulo: Seguridad Industrial
		* Nombre Programador: Daisy Adriana Martínez Fernández -Nadia Madahí López Hernández
		* Fecha:21/Marzo/2012
		* Descripción: Este archivo contiene la funcion que permite Registrar ó Modificar el Tiempo de Vida Util de los Equipos de Seguridad dentro de la tabla vida_util_es 
			en  la BD de SEGURIDAD
	**/	  
	  /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");
			//Incluimos archivo para modificar fechas
			include("../../../../includes/func_fechas.php");
	/**    
      **/	
	//Conectarse a la BD
	$conn = conecta("bd_seguridad");
	
	//Comprobamos que exista la clave en el GET
	if($_GET['clave']){
		
		//Variable que nos permitira almacenar el tipo de Registro del Material que viene definido en el GET
		$clave =$_GET['clave'];
				
		//Creamos la sentencia SQL
		$stm_sql="SELECT * FROM vida_util_es WHERE materiales_id_material = '$clave'";
		
		//Ejecutamos la consulta
		$rs = mysql_query($stm_sql);
		
		//Guardamos el resultado de la consulta en un arreglo de datos
		if($datos=mysql_fetch_array($rs)){
			//Guardamos el valor buscado
			$claveMat =$datos['materiales_id_material'];
			$tiempoVida = $datos['tiempo_vida'];
			$tipoTiempo = $datos['tipo_tiempo'];
			$fechaReg = modFecha($datos['fecha_reg'],1);
			if($datos['observaciones']!="")
				$obs = $datos['observaciones'];
			else
				$obs = "¬ND";
		
			header("Content-type: text/xml");	 
			//Crear XML de la clave Generada
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<claveMat>$claveMat</claveMat>
					<tiempoVida>$tiempoVida</tiempoVida>
					<tipoTiempo>$tipoTiempo</tipoTiempo>
					<fechaReg>$fechaReg</fechaReg>
					<obs>$obs</obs>
				</existe>");
			//Cerrar la conexion a la BD
			mysql_close($conn);
		}
		else{
			header("Content-type: text/xml");	 
			//Crear XML de la clave Generada
			echo utf8_encode("
				<existe>
					<valor>false</valor>
				</existe>");
		}
	}
?>