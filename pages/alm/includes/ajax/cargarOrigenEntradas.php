<?php
	/**
	  * Nombre del Módulo: Almacen                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 31/Marzo/2012
	  * Descripción: Este archivo se encarga de consultar la BD en busqueda de los datos necesarios para llenar un comboBox
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");
	/**   Código en: \includes\ajax\cargarCombo.php                                   
      **/	
	  
	//Funciones para ordenar un combo mediante un campo
	if(isset($_GET['opcion'])){	
		//Recuperar los datos a buscar de la URL
		$datoBusq = $_GET["opcion"];
		//Verificar la BD a la que se debe conectar al usuario
		switch($datoBusq){
			case "oc":
				$bd="bd_almacen";
				//Crear la Sentencia SQL
				$sql_stm = "SELECT DISTINCT id_orden_compra AS valor FROM orden_compra JOIN detalle_oc ON id_orden_compra=orden_compra_id_orden_compra WHERE estado = 1";
			break;
			case "pedido":
				$bd="bd_compras";
				//Sentencia SQL que trae todos los Pedidos
				$sql_stm = "SELECT DISTINCT id_pedido AS valor,requisiciones_id_requisicion FROM pedido JOIN detalles_pedido ON id_pedido=pedido_id_pedido 
							WHERE detalles_pedido.estado = 1";
				/*Para Pedidos sin Requisicion asociada
				$sql_stm = "SELECT DISTINCT id_pedido AS valor,requisiciones_id_requisicion FROM pedido JOIN detalles_pedido ON id_pedido=pedido_id_pedido 
							WHERE requisiciones_id_requisicion NOT LIKE 'ALM%' AND requisiciones_id_requisicion NOT LIKE 'MAN%' 
							AND requisiciones_id_requisicion NOT LIKE 'MAC%' AND requisiciones_id_requisicion NOT LIKE 'MAM%' 
							AND requisiciones_id_requisicion NOT LIKE 'ASE%' AND requisiciones_id_requisicion NOT LIKE 'DES%' 
							AND requisiciones_id_requisicion NOT LIKE 'GER%' AND requisiciones_id_requisicion NOT LIKE 'LAB%' 
							AND requisiciones_id_requisicion NOT LIKE 'PAI%' AND requisiciones_id_requisicion NOT LIKE 'PRO%' 
							AND requisiciones_id_requisicion NOT LIKE 'REC%' AND requisiciones_id_requisicion NOT LIKE 'SEG%' 
							AND requisiciones_id_requisicion NOT LIKE 'TOP%' AND detalles_pedido.estado = 1";
				*/
			break;
			case "requisicion":
				//Recuperar el depto a donde se realiza la conexion
				$depto=$_GET["depto"];
				//Verificar la BD a la que se debe conectar
				switch($depto){
					case "almacen":
						$bd="bd_almacen";
					break;
					case "desarrollo":
						$bd="bd_desarrollo";
					break;
					case "topografia":
						$bd="bd_topografia";
					break;
					case "gerenciatecnica":
						$bd="bd_gerencia";
					break;
					case "laboratorio":
						$bd="bd_laboratorio";
					break;
					case "produccion":
						$bd="bd_produccion";
					break;
					case "mantenimiento":
						$bd="bd_mantenimiento";
					break;
					case "seguridadindustrial":
						$bd="bd_seguridad";
					break;
					case "recursoshumanos":
						$bd="bd_recursos";
					break;
					case "aseguramientodecalidad":
						$bd="bd_aseguramiento";
					break;
					case "paileria":
						$bd="bd_paileria";
					break;
					case "mttoE":
						$bd="bd_mantenimientoE";
					break;
					case "clinica":
						$bd="bd_clinica";
					break;
				}
				//Sentencia SQL para extraer las requisiciones del Depto seleccionado
				$sql_stm = "SELECT DISTINCT id_requisicion AS valor FROM requisiciones JOIN detalle_requisicion ON id_requisicion=requisiciones_id_requisicion WHERE detalle_requisicion.estado = 1 ORDER BY id_requisicion,fecha_req";
			break;
		}
		//Conectarse a la BD
		$conn = conecta($bd);
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		$tam = mysql_num_rows($rs);
		$cont = 1;
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			echo "<existe><valor>true</valor><tam>$tam</tam>";
			do{
				if($datoBusq=="oc" || $datoBusq=="requisicion"){
					//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
					echo utf8_encode("<dato$cont>$datos[valor]</dato$cont>");
				}
				if($datoBusq=="pedido"){
					//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
					echo utf8_encode("<dato$cont>$datos[valor] - $datos[requisiciones_id_requisicion]</dato$cont>");
				}
				$cont++;
			}while($datos=mysql_fetch_array($rs));
			echo "</existe>";
		}
		else{
			echo "<valor>false</valor>";
		}
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}
?>
