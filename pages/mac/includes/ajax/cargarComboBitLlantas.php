<?php
	/**
	  * Nombre del Módulo: Mantenimiento                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas                            
	  * Fecha: 28/Marzo/2012
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
	if(isset($_GET['opc'])){	
		if($_GET["opc"]==1){
			$conn=conecta("bd_mantenimiento");
			$anio=$_GET["anioBitLlanta"];
			//Crear la Sentencia SQL
			$sql_stm = "SELECT DISTINCT(SUBSTRING(fecha,6,2)) AS mes FROM bitacora_llantas WHERE SUBSTRING(fecha,1,4)='$anio' 
						AND equipo=ANY(SELECT id_equipo FROM equipos WHERE area='CONCRETO') AND tipo_mov='S' ORDER BY fecha";
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
					switch($datos["mes"]){
						case "01":
							$mes="ENERO";
						break;
						case "02":
							$mes="FEBRERO";
						break;
						case "03":
							$mes="MARZO";
						break;
						case "04":
							$mes="ABRIL";
						break;
						case "05":
							$mes="MAYO";
						break;
						case "06":
							$mes="JUNIO";
						break;
						case "07":
							$mes="JULIO";
						break;
						case "08":
							$mes="AGOSTO";
						break;
						case "09":
							$mes="SEPTIEMBRE";
						break;
						case "10":
							$mes="OCTUBRE";
						break;
						case "11":
							$mes="NOVIEMBRE";
						break;
						case "12":
							$mes="DICIEMBRE";
						break;
					}
					//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
					echo utf8_encode("
						<id$cont>$datos[mes]</id$cont>
						<nombre$cont>$mes</nombre$cont>
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
		}
		elseif($_GET["opc"]==2){
			//Recuperar datos del GET
			$anio=$_GET["anioBitLlanta"];
			$mes=$_GET["mesBitLlanta"];
			$fecha=$anio."-".$mes;
			//Conectar a la BD de Mtto
			$conn=conecta("bd_mantenimiento");
			//Crear la Sentencia SQL
			$sql_stm = "SELECT DISTINCT(equipo) AS equipo FROM bitacora_llantas WHERE SUBSTRING(fecha,1,7)='$fecha' 
						AND equipo=ANY(SELECT id_equipo FROM equipos WHERE area='CONCRETO') AND tipo_mov='S' ORDER BY fecha";
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
					//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
					echo utf8_encode("
						<equipo$cont>$datos[equipo]</equipo$cont>
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
		}
	}//FIN del else if(isset($_GET['nomCampoOrd'))	
?>
