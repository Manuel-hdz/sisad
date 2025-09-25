<?php
	/**
	  * Nombre del Módulo: Compras                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández
	  * Fecha: 23/Enero/2012                                      			
	  * Descripción: Este archivo se encarga de consultar la BD en busqueda de los datos necesarios para llenar un comboBox
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");
			//Incluimos archivo para modificar fechas
			include("../../../../includes/func_fechas.php");			
	/**   Código en: includes\ajax\cargarCombo.php                                   
      **/	
	  
	
	//Funciones para ordenar un combo mediante un campo
	if(isset($_GET['combo'])){	
		//REcuperamos la informacion contenida en el GET
		$combo = $_GET['combo'];
		$fechaIni = modFecha($_GET['fechaIni'],3);
		$fechaFin = modFecha($_GET['fechaFin'],3);
		//Declaramos variables para el proceso de llenado del combo
		$bd = "";
		if($combo=="PROVEEDOR"||$combo=="REQUISICION")
			$bd = "bd_almacen";
		if($combo=="PEDIDO")
			$bd = "bd_compras";	
	
		//Conectarse a la BD
		$conn = conecta("$bd");
		
		if($combo=="PROVEEDOR"){
			//Crear la Sentencia SQL
			$sql_stm = "SELECT DISTINCT proveedor FROM ((entradas JOIN detalle_reporte_rea ON entradas_id_entrada=id_entrada)JOIN reporte_rea ON
					   id_reporte_rea=reporte_rea_id_reporte_rea) WHERE proveedor!=''  AND fecha_creacion>='$fechaIni' 
					   AND fecha_creacion<='$fechaFin' ORDER BY proveedor";
		}
		if($combo=="REQUISICION"){
			//Crear la Sentencia SQL
			$sql_stm = "SELECT DISTINCT requisiciones_id_requisicion FROM ((entradas JOIN detalle_reporte_rea ON entradas_id_entrada=id_entrada)JOIN reporte_rea ON
					   id_reporte_rea=reporte_rea_id_reporte_rea) WHERE requisiciones_id_requisicion!=''  AND fecha_creacion>='$fechaIni' 
						AND fecha_creacion<='$fechaFin' ORDER BY requisiciones_id_requisicion";
		}
		if($combo=="PEDIDO"){
			//Crear la Sentencia SQL
			$sql_stm = "SELECT DISTINCT id_pedido FROM (((pedido JOIN bd_almacen.entradas ON 
						bd_compras.pedido.requisiciones_id_requisicion=bd_almacen.entradas.requisiciones_id_requisicion)JOIN bd_almacen.detalle_reporte_rea 
						ON bd_almacen.entradas.id_entrada=bd_almacen.detalle_reporte_rea.entradas_id_entrada)JOIN bd_almacen.reporte_rea ON 
						bd_almacen.reporte_rea.id_reporte_rea=bd_almacen.detalle_reporte_rea.reporte_rea_id_reporte_rea) WHERE id_pedido!=''  
						AND fecha_creacion>='$fechaIni' 
						AND fecha_creacion<='$fechaFin' ORDER BY fecha, id_pedido ";
		}
		
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		$tam = mysql_num_rows($rs);
		$cont = 1;
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			echo utf8_encode("<existe><valor>true</valor><tam>$tam</tam>");
			do{
				if($combo=="PROVEEDOR"){
					//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
					echo utf8_encode("<dato$cont>".str_replace("&","¬Y¬",$datos['proveedor'])."</dato$cont>");
				}
				if($combo=="REQUISICION"){
					//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
					echo utf8_encode("<dato$cont>".$datos['requisiciones_id_requisicion']."</dato$cont>");
				}
				if($combo=="PEDIDO"){
					//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
					echo utf8_encode("<dato$cont>".$datos['id_pedido']."</dato$cont>");
				}
				$cont++;
			}while($datos=mysql_fetch_array($rs));
			echo "</existe>";
		}
		else{
			echo "<valor>false</valor>";
		}
		mysql_close($conn);
	}
?>
