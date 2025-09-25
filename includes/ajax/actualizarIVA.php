<?php
	/**
	  * Nombre del Módulo: Compras                                               
	  * Nombre Programador: Miguel Angel Garay Castro                            
	  * Fecha: 23/Noviembre/2010                                      			
	  * Descripción: Este archivo se encarga de guardar el porcentaje de IVA introducido por el Usuario
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../conexion.inc");			
	/**   Código en: pages\alm\includes\validarDatoBD.php                                   
      **/	
		 
	//Recuperar los datos a buscar de la URL
	$porcentIVA = $_GET["porcentIVA"];
	
	
	//Conectarse a la BD
	$conn = conecta("bd_compras");
	//Crear la Sentencia SQL
	$sql_stm = "UPDATE impuestos SET porcentaje = '$porcentIVA' WHERE nom_impuesto = 'iva'";
	//Ejecutar la Sentencia previamente creada
	$rs = mysql_query($sql_stm);
	//Definir el tipo de contenido que tendra el archivo creado
	header("Content-type: text/xml");	 
	//Comparar los resultados obtenidos 
	if($rs){		
		//Abrir la SESSION y guardar el nuevo porcentaje de IVA
		session_start();
		$_SESSION['porcentajeIVA'] = $porcentIVA;
		//Guardar los datos del usuario que modificó el porcentaje de IVA
		mysql_query("INSERT INTO bitacora_movimientos (id_operacion,tipo_operacion,usuario,fecha,hora) VALUES('iva','ActualizarIVA','".$_SESSION['usr_reg']."','".date("Y-m-d")."','".date("H:i:s")."')");
										
		//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
		echo utf8_encode("
			<existe>
				<valor>true</valor>
				<porcentaje>$porcentIVA</porcentaje>
			</existe>");
	}
	else{
		echo "<valor>false</valor>";
	}
	//Cerrar la conexion a la BD
	mysql_close($conn);
?>
