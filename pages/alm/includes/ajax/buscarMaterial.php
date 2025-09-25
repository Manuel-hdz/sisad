<?php
	/**
	  * Nombre del Módulo: Almacén                                               
	  * Nombre Programador: Miguel Angel Garay Castro                            
	  * Fecha: 18/Enero/2011                                      			
	  * Descripción: Este archivo se encarga de consultar la BD en busqueda del dato indicado para obtener sus datos
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");
			include("../../../../includes/func_fechas.php");			
	/**   Código en: pages\alm\includes\buscarMaterial.php                                   
      **/	
	  
	//Conectarse a la BD
	$conn = conecta("bd_almacen");
	
	//Verificar que venga la Clave del material en la URL	 
	if(isset($_GET["claveMaterial"])){
		//Recuperar los datos a buscar de la URL
		$claveMaterial = $_GET["claveMaterial"];
		//Crear la Sentencia SQL
		$sql_stm = "SELECT id_material, nom_material, existencia, unidad_medida, costo_unidad, IFNULL( UPPER( descripcion ) ,  'SIN CATEGORIA' ) AS linea_articulo
					FROM materiales
					JOIN unidad_medida ON id_material = materiales_id_material
					LEFT JOIN categorias_mat ON categoria = id_categoria
					WHERE id_material='$claveMaterial'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<clave>$datos[id_material]</clave>
					<nombre>$datos[nom_material]</nombre>
					<existencia>$datos[existencia]</existencia>
					<unidad>$datos[unidad_medida]</unidad>
					<costo>$datos[costo_unidad]</costo>
					<categoria>$datos[linea_articulo]</categoria>
				</existe>");
		}
		else{
			echo "<valor>false</valor>";
		}
	}
	else if(isset($_GET["vale"])){
		//Recuperar los datos a buscar de la URL
		$noVale = $_GET["vale"];
		$fecha = modFecha($_GET["fechaActual"],3);	
		
		//Crear la Sentencia SQL
		$sql_stm = "SELECT id_salida FROM salidas WHERE fecha_salida = '$fecha' AND no_vale = '$noVale'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<noVale>$noVale</noVale>
					<noSalida>$datos[id_salida]</noSalida>										
				</existe>");
		}
		else{
			echo "<valor>false</valor>";
		}
	}else if(isset($_GET["equipoSeg"])){
		//Recuperar los datos a buscar de la URL
		$noVale = $_GET["noVale"];
		$equipoSeg = $_GET['equipoSeg'];
		$rfcEmp = $_GET['rfcEmp'];
		$nomCkb = $_GET['nomCkb'];
		
		//Crear la Sentencia SQL
		$sql_stm = "SELECT detalle_salidas.materiales_id_material, id_salida FROM ((detalle_salidas JOIN salidas ON salidas_id_salida=id_salida)JOIN detalle_es ON
					detalle_es.no_vale=salidas.no_vale) WHERE detalle_es.no_vale = '$noVale' 
					AND detalle_es.materiales_id_material = '$equipoSeg' AND detalle_es.empleados_rfc_empleado='$rfcEmp'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<noVale>$noVale</noVale>
					<claveMat>$datos[materiales_id_material]</claveMat>
					<salida>$datos[id_salida]</salida>										
					<nomCkb>$nomCkb</nomCkb>
				</existe>");
		}
		else{
			echo "<valor>false</valor>";
		}
	}
	else if(isset($_GET["codigoBarras"])){
		if(isset($_GET["equipoID"])){
			session_start();
			$_SESSION["id_equipo"] = $_GET["equipoID"];;
		}
		//Recuperar el codigo de Barras
		$codBar=$_GET["codigoBarras"];
		//Sustituir los tags por el apostrofe
		$codBar=str_replace("<>","'",$codBar);
		//Crear la Sentencia SQL
		$sql_stm = "SELECT id_material, nom_material, existencia, unidad_medida, costo_unidad, IFNULL( UPPER( descripcion ) ,  'SIN CATEGORIA' ) AS linea_articulo
					FROM materiales
					JOIN unidad_medida ON id_material = materiales_id_material
					LEFT JOIN categorias_mat ON categoria = id_categoria
					WHERE codigo_barras=\"$codBar\"";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<clave>$datos[id_material]</clave>
					<nombre>$datos[nom_material]</nombre>
					<existencia>$datos[existencia]</existencia>
					<unidad>$datos[unidad_medida]</unidad>
					<costo>$datos[costo_unidad]</costo>
					<categoria>$datos[linea_articulo]</categoria>
				</existe>");
		}
		else{
			echo "<valor>false</valor>";
		}
	}
	//Cerrar la conexion a la BD
	mysql_close($conn);
	
	$conn2 = conecta("bd_mantenimiento");
	
	if(isset($_GET["equipo"])){
		//Recuperar el codigo de Barras
		$equipo=$_GET["equipo"];
		//Sustituir los tags por el apostrofe
		$equipo=str_replace("<>","'",$equipo);
		//Crear la Sentencia SQL
		$sql_stm = "SELECT `id_equipo` 
					FROM  `equipos` 
					WHERE `id_equipo`=\"$equipo\"";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<equipo>$datos[id_equipo]</equipo>
				</existe>");
		}
		else{
			echo "<valor>false</valor>";
		}
	}
	//Cerrar la conexion a la BD
	mysql_close($conn2);
	
	if(isset($_GET["pedido"])){
		$conn_ped = conecta("bd_compras");
		//Recuperar el pedido
		$pedido=$_GET["pedido"];
		//Sustituir los tags por el apostrofe
		$pedido=str_replace("<>","'",$pedido);
		//Crear la Sentencia SQL
		$sql_stm = "SELECT DISTINCT id_pedido, requisiciones_id_requisicion
					FROM pedido
					JOIN detalles_pedido ON id_pedido = pedido_id_pedido
					WHERE detalles_pedido.estado =1 AND pedido.id_pedido = '$pedido'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<pedido>$datos[id_pedido]</pedido>
				</existe>");
		}
		else{
			echo "<valor>false</valor>";
		}
		//Cerrar la conexion a la BD
		mysql_close($conn_ped);
	}
	
	if(isset($_GET["requisicion"])){
		$base = obtenerNomBD($_GET["depto"]);
		$conn_req = conecta("$base");
		//Recuperar el requisicion
		$requisicion=$_GET["requisicion"];
		//Sustituir los tags por el apostrofe
		$requisicion=str_replace("<>","'",$requisicion);
		//Crear la Sentencia SQL
		$sql_stm = "SELECT DISTINCT id_requisicion
					FROM requisiciones
					JOIN detalle_requisicion ON id_requisicion = requisiciones_id_requisicion
					WHERE detalle_requisicion.estado=1 AND requisiciones.id_requisicion = '$requisicion'
					ORDER BY id_requisicion, fecha_req";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<requisicion>$datos[id_requisicion]</requisicion>
				</existe>");
		}
		else{
			echo "<valor>false</valor>";
		}
		//Cerrar la conexion a la BD
		mysql_close($conn_req);
	}
	
	/*Esta funcion recibe como parametro el Nombre del Departamento y determina cual es el nombre de la BD correspondiente*/
	function obtenerNomBD($departamento){
		$base = "";
		switch ($departamento){
			case "almacen":				
				$base="bd_almacen";
			break;
			case "gerenciatecnica":
				$base="bd_gerencia";
			break;
			case "recursoshumanos":
				$base="bd_recursos";
			break;
			case "produccion":
				$base="bd_produccion";
			break;
			case "aseguramientodecalidad":
				$base="bd_aseguramiento";
			break;
			case "desarrollo":
				$base="bd_desarrollo";
			break;
			case "mantenimiento":
				$base="bd_mantenimiento";
			break;
			case "topografia":
				$base="bd_topografia";
			break;
			case "laboratorio":
				$base="bd_laboratorio";
			break;
			case "seguridadindustrial":
				$base="bd_seguridad";
			break;
			case "paileria":
				$base="bd_paileria";
			break;
			case "mttoE":
				$base="bd_mantenimientoE";
			break;
			case "clinica":
				$base="bd_clinica";
			break;
		}
		//Retornar el Nombre de la Base de Datos
		return $base;
	}
?>
