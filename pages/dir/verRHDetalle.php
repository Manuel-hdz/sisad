<?php
	/**
	  * Nombre del Módulo: Direccion General                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 02/Marzo/2011
	  * Descripción: En este archivo se muestra el Detalle de Altas, Bajas y Cambio de Puesto en RH
	  **/ 
	
	//Módulo de conexión a la BD
	include ("../../includes/conexion.inc");
	//Operaciones con la Base de Datos
	include("../../includes/op_operacionesBD.php");
	//Manejo de fechas
	include ("../../includes/func_fechas.php");
	?>
	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="includes/estiloGerencia.css" />
	<script type="text/javascript" language="javascript">
		<!--
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, ©Concreto Lanzado de Fresnillo S.A. de C.V.');
			}
		}
		//-->
	</script>
	
	<style type="text/css">
		<!--
		#tabla-resultados{position:absolute; overflow:scroll; width:90%; height:410px;}
		#botones{position:absolute; left:30px;top:569px; width:90%;}
		-->
	</style>
	
</head>
<body>
	<?php		
	//Realizar la conexion a la BD de Almacen
	$conn = conecta('bd_recursos');

	//Convertir Formato Fecha Sistema a formato Fecha Base de Datos para consultas
	$fechaIni = modFecha($_GET["fechaI"],3);
	$fechaFin = modFecha($_GET["fechaF"],3);
	$area=$_GET["depto"];

	if($area=="")
		//Crear la sentencia para mostrar el Reporte
		$stm_sql = "SELECT rfc_empleado,id_empleados_empresa,CONCAT_WS(' ',nombre,ape_pat,ape_mat) AS nombre, fecha_ingreso, area, puesto, observaciones FROM empleados 
					WHERE fecha_ingreso>='$fechaIni' AND fecha_ingreso<='$fechaFin' ORDER BY area";
	else
		//Crear la sentencia para mostrar el Reporte
		$stm_sql = "SELECT rfc_empleado,id_empleados_empresa,CONCAT_WS(' ',nombre,ape_pat,ape_mat) AS nombre, fecha_ingreso, area, puesto, observaciones FROM empleados 
					WHERE fecha_ingreso>='$fechaIni' AND fecha_ingreso<='$fechaFin' AND area='$area' ORDER BY area";

	//Ejecutar la consulta
	$rs = mysql_query($stm_sql);
	//Mostrar los resultados obtenidos
	if($datos = mysql_fetch_array($rs)){
		//Variable para verificar si la consulta ejecutada arrojo resultados
		$flag = 1;
		echo "								
			<table align='center' class='tabla_frm' cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta' style='color:#FFFFFF'>Altas Registradas</caption>					
				<tr>
					<td align='center' class='nombres_columnas'>NO.</td>
					<td align='center' class='nombres_columnas'>RFC</td>
					<td align='center' class='nombres_columnas'>NOMBRE</td>
					<td align='center' class='nombres_columnas'>FECHA INGRESO</td>
					<td align='center' class='nombres_columnas'>&Aacute;REA</td>
					<td align='center' class='nombres_columnas'>PUESTO</td>
					<td align='center' class='nombres_columnas'>OBSERVACIONES</td>
				</tr>";
									
		$nom_clase = "renglon_gris";
		$cont = 1;
					
		$arrAreas = array();			
		//Declarar la Primera Area como indice del Arreglo y colocarle el valor de 0
		$arrAreas[$datos['area']] = 0;
		$areaActual = $datos['area'];
		
		do{				
			echo "	
				<tr>
					<td align='center' class='$nom_clase'>$datos[id_empleados_empresa]</td>		
					<td align='center' class='$nom_clase'>$datos[rfc_empleado]</td>
					<td align='center' class='$nom_clase'>$datos[nombre]</td>
					<td align='center' class='$nom_clase'>".modFecha($datos['fecha_ingreso'],1)."</td>
					<td align='center' class='$nom_clase'>$datos[area]</td>
					<td align='center' class='$nom_clase'>$datos[puesto]</td>
					<td align='center' class='$nom_clase'>$datos[observaciones]</td>
				</tr>";
			//Acumular la cantidad de Altas por Area								
			if($areaActual==$datos["area"]){					
				$arrAreas[$areaActual] += 1; 
			}
			else{				
				$arrAreas[$datos["area"]] = 0; 	
				$areaActual = $datos["area"];	
				$arrAreas[$areaActual] += 1;
			}
		
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
					
		}while($datos=mysql_fetch_array($rs));
		echo "	
		</table>
		<br>";
	}
	/****************************************************************************************/
	/*********************************BAJAS DE PERSONAL**************************************/
	/****************************************************************************************/
	if($area=="")
		//Crear la sentencia para mostrar el Reporte
		$stm_sql = "SELECT empleados_rfc_empleado,CONCAT_WS(' ',nombre,ape_pat,ape_mat) AS nombre, fecha_baja, area, puesto, observaciones FROM bajas_modificaciones 
					WHERE fecha_baja>='$fechaIni' AND fecha_baja<='$fechaFin' AND fecha_baja!='0000-00-00' AND fecha_mod_puesto='0000-00-00' ORDER BY area";
	else
		//Crear la sentencia para mostrar el Reporte
		$stm_sql = "SELECT empleados_rfc_empleado,CONCAT_WS(' ',nombre,ape_pat,ape_mat) AS nombre, fecha_baja, area, puesto, observaciones FROM bajas_modificaciones 
					WHERE fecha_baja>='$fechaIni' AND fecha_baja<='$fechaFin' AND fecha_baja!='0000-00-00' AND fecha_mod_puesto='0000-00-00' AND area='$area' ORDER BY area";
	//Ejecutar la consulta
	$rs = mysql_query($stm_sql);
	echo mysql_error();
	//Mostrar los resultados obtenidos
	if($datos = mysql_fetch_array($rs)){
		//Variable para verificar si la consulta ejecutada arrojo resultados
		$flag = 1;
		echo "								
			<table align='center' class='tabla_frm' cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta' style='color:#FFFFFF'>Bajas Registradas</caption>					
				<tr>
					<td align='center' class='nombres_columnas'>NO.</td>
					<td align='center' class='nombres_columnas'>RFC</td>
					<td align='center' class='nombres_columnas'>NOMBRE</td>
					<td align='center' class='nombres_columnas'>FECHA BAJA</td>
					<td align='center' class='nombres_columnas'>&Aacute;REA</td>
					<td align='center' class='nombres_columnas'>PUESTO</td>
					<td align='center' class='nombres_columnas'>OBSERVACIONES</td>
				</tr>";
									
		$nom_clase = "renglon_gris";
		$cont = 1;
					
		$arrAreas = array();			
		//Declarar la Primera Area como indice del Arreglo y colocarle el valor de 0
		$arrAreas[$datos['area']] = 0;
		$areaActual = $datos['area'];
		
		do{				
			echo "	
				<tr>
					<td align='center' class='$nom_clase'>$cont</td>		
					<td align='center' class='$nom_clase'>$datos[empleados_rfc_empleado]</td>
					<td align='center' class='$nom_clase'>$datos[nombre]</td>
					<td align='center' class='$nom_clase'>".modFecha($datos['fecha_baja'],1)."</td>
					<td align='center' class='$nom_clase'>$datos[area]</td>
					<td align='center' class='$nom_clase'>$datos[puesto]</td>
					<td align='center' class='$nom_clase'>$datos[observaciones]</td>
				</tr>";
			//Acumular la cantidad de Altas por Area								
			if($areaActual==$datos["area"]){					
				$arrAreas[$areaActual] += 1; 
			}
			else{				
				$arrAreas[$datos["area"]] = 0; 	
				$areaActual = $datos["area"];	
				$arrAreas[$areaActual] += 1;
			}
		
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
					
		}while($datos=mysql_fetch_array($rs));
		echo "	
		</table>
		<br>";
	}
	/****************************************************************************************/
	/*********************************CAMBIOS DE PUESTOS*************************************/
	/****************************************************************************************/
	if($area=="")
		//Crear la sentencia para mostrar el Reporte
		$stm_sql = "SELECT empleados_rfc_empleado,CONCAT_WS(' ',nombre,ape_pat,ape_mat) AS nombre, fecha_mod_puesto, area, puesto, observaciones FROM bajas_modificaciones 
					WHERE fecha_mod_puesto>='$fechaIni' AND fecha_mod_puesto<='$fechaFin' AND fecha_mod_puesto!='0000-00-00' AND fecha_baja='0000-00-00' ORDER BY area";
	else
		//Crear la sentencia para mostrar el Reporte
		$stm_sql = "SELECT empleados_rfc_empleado,CONCAT_WS(' ',nombre,ape_pat,ape_mat) AS nombre, fecha_mod_puesto, area, puesto, observaciones FROM bajas_modificaciones 
					WHERE fecha_mod_puesto>='$fechaIni' AND fecha_mod_puesto<='$fechaFin' AND fecha_mod_puesto!='0000-00-00' AND fecha_baja='0000-00-00' AND area='$area' ORDER BY area";
	//Ejecutar la consulta
	$rs = mysql_query($stm_sql);
	echo mysql_error();
	//Mostrar los resultados obtenidos
	if($datos = mysql_fetch_array($rs)){
		//Variable para verificar si la consulta ejecutada arrojo resultados
		$flag = 1;
		echo "								
			<table align='center' class='tabla_frm' cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta' style='color:#FFFFFF'>Cambios de Puestos</caption>					
				<tr>
					<td align='center' class='nombres_columnas'>NO.</td>
					<td align='center' class='nombres_columnas'>RFC</td>
					<td align='center' class='nombres_columnas'>NOMBRE</td>
					<td align='center' class='nombres_columnas'>FECHA CAMBIO PUESTO</td>
					<td align='center' class='nombres_columnas'>&Aacute;REA</td>
					<td align='center' class='nombres_columnas'>PUESTO</td>
					<td align='center' class='nombres_columnas'>OBSERVACIONES</td>
				</tr>";
									
		$nom_clase = "renglon_gris";
		$cont = 1;
					
		$arrAreas = array();			
		//Declarar la Primera Area como indice del Arreglo y colocarle el valor de 0
		$arrAreas[$datos['area']] = 0;
		$areaActual = $datos['area'];
		
		do{				
			echo "	
				<tr>
					<td align='center' class='$nom_clase'>$cont</td>		
					<td align='center' class='$nom_clase'>$datos[empleados_rfc_empleado]</td>
					<td align='center' class='$nom_clase'>$datos[nombre]</td>
					<td align='center' class='$nom_clase'>".modFecha($datos['fecha_mod_puesto'],1)."</td>
					<td align='center' class='$nom_clase'>$datos[area]</td>
					<td align='center' class='$nom_clase'>$datos[puesto]</td>
					<td align='center' class='$nom_clase'>$datos[observaciones]</td>
				</tr>";
			//Acumular la cantidad de Altas por Area								
			if($areaActual==$datos["area"]){					
				$arrAreas[$areaActual] += 1; 
			}
			else{				
				$arrAreas[$datos["area"]] = 0; 	
				$areaActual = $datos["area"];	
				$arrAreas[$areaActual] += 1;
			}
		
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
					
		}while($datos=mysql_fetch_array($rs));
		echo "	
		</table>
		<br>";
	}
	?>
</body>
</html>