<?php
	/**
	  * Nombre del Módulo: Compras                                              
	  * Nombre Programador: Armando Ayala Alvarado
	  * Fecha: 27/Enero/2015
	  * Descripción: Este archivo se encarga de consultar la BD en busqueda de los datos necesarios para llenar un comboBox
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");			
	/**   Código en: ../man/includes/ajax/cargarComboPersonalRH.php
      **/	
	 
	
	
	if(isset($_GET['nominaC'])){
		include_once("../../../../includes/func_fechas.php");
		//Recuperar los datos a buscar de la URL
		$nominaC = $_GET["nominaC"];
		//Conectarse a la BD
		$conn = conecta("$_GET[bd]");
		//Crear la Sentencia SQL
		$sql_stm = "SELECT DISTINCT id_nomina, fecha_inicio, fecha_fin, area FROM nominas
					WHERE id_nomina = '$nominaC'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		$tam = mysql_num_rows($rs);
		$cont = 1;
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			echo "<existe>
					<valor>true</valor>
					<tam>$tam</tam>";
			do{
				//Prevenir errores en el archivo XML generado, cuando el texto contenga
				//acentos o algun caracter especial
				echo utf8_encode("<id$cont>$datos[id_nomina]</id$cont>");
				echo utf8_encode("<fechaI$cont>".modFecha($datos["fecha_inicio"],1)."</fechaI$cont>");
				echo utf8_encode("<fechaF$cont>".modFecha($datos["fecha_fin"],1)."</fechaF$cont>");
				echo utf8_encode("<area$cont>$datos[area]</area$cont>");
				$cont++;
			}while($datos=mysql_fetch_array($rs));
			echo "</existe>";
		}//Cierre if($datos=mysql_fetch_array($rs))
		else{
			echo "<valor>false</valor>";
		}
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}//Cierre if(isset($_GET['nominaC']))
	
	if(isset($_GET['prodC'])){
		include_once("../../../../includes/func_fechas.php");
		
		$prod_fre = cargarProduccion(obtenerIdLugar("ZARPEO MINERA FRESNILLO"), modFecha($_GET["fechaI"],3), modFecha($_GET["fechaF"],3));
		$prod_sau = cargarProduccion(obtenerIdLugar("ZARPEO MINERA SAUCITO"), modFecha($_GET["fechaI"],3), modFecha($_GET["fechaF"],3));
				
		$cont = 1;
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		echo "<existe>
				<valor>true</valor>";
		
		//Prevenir errores en el archivo XML generado, cuando el texto contenga
		//acentos o algun caracter especial
		echo utf8_encode("<pro_fre$cont>$prod_fre</pro_fre$cont>");
		echo utf8_encode("<pro_sau$cont>$prod_sau</pro_sau$cont>");
		
		echo "</existe>";
		
	}//Cierre if(isset($_GET['prodC']))
	
	if(isset($_GET['area'])){
		include_once("../../../../includes/func_fechas.php");
		$area = $_GET["area"];
		$fecha_ini = $_GET["fecha_ini"];
		$fecha_fin = $_GET["fecha_fin"];
		
		$fecha_ini = modFecha($fecha_ini,3);
		$fecha_fin = modFecha($fecha_fin,3);
		$conn = conecta("$_GET[bd]");
		$sql_stm = "SELECT DISTINCT * 
					FROM nominas
					WHERE fecha_registro
					BETWEEN  '$fecha_ini'
					AND  '$fecha_fin'
					AND id_control_costos =  '$area'
					AND finalizada = '$_GET[stat]'";
		$rs = mysql_query($sql_stm);
		$tam = mysql_num_rows($rs);
		$cont = 1;
		header("Content-type: text/xml");	 
		if($datos=mysql_fetch_array($rs)){
			echo "<existe>
					<valor>true</valor>
					<tam>$tam</tam>";
			do{
				echo utf8_encode("<id$cont>$datos[id_nomina]</id$cont>");
				echo utf8_encode("<descripcion$cont>$datos[id_nomina]......$datos[fecha_registro]</descripcion$cont>");
				$cont++;
			}while($datos=mysql_fetch_array($rs));
			echo "</existe>";
		} else {
			echo "<valor>false</valor>";
		}
		mysql_close($conn);
	}
	
	function obtenerIdLugar($lugar){
		$id = "0";
		$conn = conecta("bd_gerencia");
		$stm_sql = "SELECT  `id_ubicacion` 
					FROM  `catalogo_ubicaciones` 
					WHERE  `ubicacion` LIKE  '$lugar'";
		
		$rs = mysql_query($stm_sql);
		
		if($datos=mysql_fetch_array($rs)){
			$id = $datos[0];
		}
		mysql_close($conn);
		return $id;
	}
	
	function obtenerNumeroCuadrillas($lugar){
		$num = 0;
		$stm_sql = "SELECT COUNT(  `id_cuadrillas` ) 
					FROM  `cuadrillas` 
					WHERE  `catalogo_ubicaciones_id_ubicacion` =  '$lugar'";
		
		$rs = mysql_query($stm_sql);
		
		if($datos=mysql_fetch_array($rs)){
			$num = $datos[0];
		}
		return $num;
	}
	
	function cargarProduccion($lugar ,$fecha_ini, $fecha_fin){
		$prod = 0;
		$conn = conecta("bd_gerencia");
		$stm_sql = "SELECT  `vol_ppto_dia` 
					FROM  `presupuesto` 
					WHERE  `catalogo_ubicaciones_id_ubicacion` = '$lugar'
					AND  `fecha_inicio` <=  '$fecha_ini'
					AND  `fecha_fin` >=  '$fecha_fin'";
		
		$rs = mysql_query($stm_sql);
		
		if($datos=mysql_fetch_array($rs)){
			$prod = $datos[0] * 6 / obtenerNumeroCuadrillas($lugar);
		}
		mysql_close($conn);
		return $prod;
	}
?>