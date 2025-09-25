<?php
	/**
	  * Nombre del Módulo: Almacén                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas                            
	  * Fecha: 28/Octubre/2010                                      			
	  * Descripción: Este archivo contiene las funciones para almacenar los reportes REA
	  **/	 		  	  	
	  
	/**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos
			2. Modulo de operaiones generales en la BD*/
			include("../../includes/conexion.inc");
			include("../../includes/op_operacionesBD.php");			
	/**   Código en: pages\alm\publicar_reporteREA.php                                   
      **/

	if(isset($_POST['hdn_clave'])){
		guardarReporte($hdn_clave,$hdn_fechaIni,$hdn_fechaFin,$hdn_hora,$hdn_fechaCrea);
	}
	else{
		//La ventana se redirecciona a la ventana de advertencia indicando que la consulta no generó resultados
		echo "<meta http-equiv='refresh' content='0;url=advertencia.php'>";
	}

	
	function guardarReporte($clave,$fechaIni,$fechaFin,$horaSer,$fechaCreado){
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_almacen");
		$claveEntrada=array();
		
		//Abrir la SESSION
		session_start();
		foreach($_SESSION['claves_entrada'] as $key => $value){
			$claveEntrada[]=$value;
		}
		
		//Crear la sentencia para insertar los datos
		$stm_sql_rea = "INSERT INTO reporte_rea VALUES('".$clave."','".$horaSer."','".$fechaCreado."','".$fechaIni."','".$fechaFin."')";
		//Ejecutar las sentencia previamente creadas
		$rs = mysql_query($stm_sql_rea);
		$i=0;
		do{
			$stm_sql_detrea = "INSERT INTO detalle_reporte_rea VALUES('".$claveEntrada[$i]."','".$clave."')";
			//Ejecutar la sentencia para insertar el detalle de inventario
			$rs = mysql_query($stm_sql_detrea);	
			$i++;
		}while($i<count($claveEntrada));
		
		//Registrar la Operacion en la Bitácora de Movimientos
		registrarOperacion("bd_almacen",$clave,"PublicarReporteREA",$_SESSION['usr_reg']);
		
		//Quitar de la SESSION las claves de las entradas incluidas en el reporte REA que se esta generando
		unset($_SESSION['claves_entrada']);
		if ($i>0)
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";		
		else
			echo "<meta http-equiv='refresh' content='0;url=error.php'>";
	}
?>