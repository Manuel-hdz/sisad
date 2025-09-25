<?php
	/**
	  * Nombre del Módulo: Compras                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas                            
	  * Fecha: 30/Octubre/2012
	  * Descripción: Este archivo se encarga de consultar que los pedidos ingresados existan en la BD
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");			
	/**   Código en: pages\alm\includes\validarEstado.php                                   
      **/	
		 
	//Recuperar los datos a buscar de la URL
	$datoBusq = $_GET["idPedido"];
	
	//Conectarse a la BD
	$conn = conecta("bd_compras");
	//Crear la Sentencia SQL
	$sql_stm = "SELECT id_pedido,solicitor FROM pedido WHERE id_pedido='$datoBusq'";
	//Ejecutar la Sentencia previamente creada
	$rs = mysql_query($sql_stm);
	//Definir el tipo de contenido que tendra el archivo creado
	header("Content-type: text/xml");	 
	//Comparar los resultados obtenidos 
	if($datos=mysql_fetch_array($rs)){
		$rsEquipos=mysql_query("SELECT DISTINCT equipo FROM detalles_pedido WHERE pedido_id_pedido='$datoBusq' AND equipo!=''");
		$equipos="";
		if($datosEquipo=mysql_fetch_array($rsEquipos)){
			do{
				$equipos.=$datosEquipo["equipo"].", ";
			}while($datosEquipo=mysql_fetch_array($rsEquipos));
			$equipos=substr($equipos,0,strlen($equipos)-2);
		}
		else{
			$equipos="N/A";
		}
		//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
		echo utf8_encode("
			<existe>
				<valor>true</valor>
				<clave>$datos[id_pedido]</clave>
				<responsable>$datos[solicitor]</responsable>
				<equipos>$equipos</equipos>
			</existe>");
	}
	else{
		echo "<valor>false</valor>";
	}
	//Cerrar la conexion a la BD
	mysql_close($conn);
?>
