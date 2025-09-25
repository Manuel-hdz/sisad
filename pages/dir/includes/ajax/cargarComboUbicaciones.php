<?php
	/**
	  * Nombre del Módulo: Dirección General                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas                            
	  * Fecha: 27/Febrero/2012
	  * Descripción: Este archivo se encarga de consultar la BD en busqueda de los datos necesarios para llenar un comboBox
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");	
			include("../../../../includes/op_operacionesBD.php");		
	/**   Código en: \includes\ajax\cargarCombo.php                                   
      **/	
	  
	//Funciones para ordenar un combo mediante un campo
	if(isset($_GET['periodo'])){	
		//Recuperar los datos a buscar de la URL
		$periodo = $_GET["periodo"];
		//Recuperar la Base de Datos de la URL
		$base = $_GET["bd"];
		//Conectarse a la BD
		$conn = conecta($base);
		if($base=="bd_gerencia")
			//Crear la Sentencia SQL
			$sql_stm = "SELECT DISTINCT catalogo_ubicaciones_id_ubicacion AS id_ubicaciones FROM presupuesto WHERE periodo='$periodo' ORDER BY catalogo_ubicaciones_id_ubicacion";
		if($base=="bd_produccion")
			//Crear la Sentencia SQL
			$sql_stm = "SELECT DISTINCT catalogo_destino_id_destino AS id_ubicaciones FROM presupuesto WHERE periodo='$periodo' ORDER BY catalogo_destino_id_destino";
		if($base=="bd_desarrollo")
			//Crear la Sentencia SQL
			$sql_stm = "SELECT DISTINCT catalogo_clientes_id_cliente AS id_ubicaciones FROM presupuesto WHERE periodo='$periodo' ORDER BY catalogo_clientes_id_cliente";
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
				if($base=="bd_gerencia")
					$ubicacion=obtenerDato("bd_gerencia","catalogo_ubicaciones","ubicacion","id_ubicacion",$datos["id_ubicaciones"]);
				if($base=="bd_produccion")
					$ubicacion=obtenerDato("bd_produccion","catalogo_destino","destino","id_destino",$datos["id_ubicaciones"]);
				if($base=="bd_desarrollo")
					$ubicacion=obtenerDato("bd_desarrollo","catalogo_clientes","nom_cliente","id_cliente",$datos["id_ubicaciones"]);
				//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
				echo utf8_encode("
					<id$cont>$datos[id_ubicaciones]</id$cont>
					<nombre$cont>$ubicacion</nombre$cont>
				");
				$cont++;
			}while($datos=mysql_fetch_array($rs));
			echo "</existe>";
		}
		else{
			echo "<valor>false</valor>";
		}
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}//FIN del else if(isset($_GET['nomCampoOrd'))
?>
