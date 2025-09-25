<?php
	/**
	  * Nombre del Módulo: Direccion General
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 27/Septiembre/2012
	  * Descripción: Este archivo contiene las operaciones de Reportes de Laboratorio
	  **/
	 	
	 /**
      * Listado del contenido del programa
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");
			include("../../../../includes/op_operacionesBD.php");
			include("../../../../includes/func_fechas.php");
	
	//Recuperar los datos a buscar de la URL
	if (isset($_GET["noRep"])){
		$tipoRep=$_GET["noRep"];
		switch($tipoRep){
			case 2:
				$idMuestra=$_GET["idMuestra"];
				$idPrueba=reporteResistencias($idMuestra);
				header("Content-type: text/xml");	
				if ($idPrueba!=""){
					//Crear XML de la clave Generada
					echo utf8_encode("
						<existe>
							<valor>true</valor>
							<idPrueba>$idPrueba</idPrueba>
							</existe>
						");
				}
				else{
					//Crear XML de error
					echo utf8_encode("
					<existe>
						<valor>false</valor>
					</existe>");
				}
			break;
			case 3:
				//Recuperar los datos
				$idAgregado=$_GET["idAgregado"];
				$fechaI=$_GET["fechaI"];
				$fechaF=$_GET["fechaF"];
				//Ensamblar el titulo
				if($idAgregado!=""){
					$nomAgregado=obtenerDato("bd_almacen","materiales","nom_material","id_material",$idAgregado);
					$titulo="REPORTE DEL AGREGADO: $nomAgregado DEL $fechaI AL $fechaF";
				}
				else
					$titulo="REPORTE DE AGREGADOS DEL $fechaI AL $fechaF";
				//Modificar la fecha formato legible por MySQL
				$fechaI=modFecha($fechaI,3);
				$fechaF=modFecha($fechaF,3);
				//Obtener la Tabla a traves de la funcion que la crea
				$tabla=reporteAgregados($idAgregado,$fechaI,$fechaF,$titulo);
				header("Content-type: text/xml");	
				if ($tabla!=""){
					//Remplazar el tag de apertura "menor que" por un simbolo menos usado, en este caso "¬"
					$tabla=str_replace("<","¬",$tabla);
					//Remplazar el "&" por un simbolo menos usado, en este caso "^^"
					$tabla=str_replace("&","°",$tabla);
					//Crear XML de la clave Generada
					echo utf8_encode("
						<existe>
							<valor>true</valor>
							<titulo>$titulo</titulo>
							<tabla>$tabla</tabla>
						</existe>");
				}
				else{
					//Crear XML de error
					echo utf8_encode("
					<existe>
						<valor>false</valor>
					</existe>");
				}
			break;
		}
	}	
	
	/*Esta funcion genera el reporte de incidencias de Kardex*/
	function reporteResistencias($idMuestra){
		$clave="";
		//Conectarse a la BD de Recursos Humanos
		$conn = conecta("bd_laboratorio");
		//Sentencia SQL
		$sql_stm="SELECT id_prueba_calidad FROM prueba_calidad WHERE muestras_id_muestra='$idMuestra'";
		//Ejecutar Sentencia SQL
		$rs=mysql_query($sql_stm);
		//Extraer los datos a otro arreglo que permita un mejor manejo de la informacion
		if($datos=mysql_fetch_array($rs))
			$clave=$datos["id_prueba_calidad"];
		//Cerrar la conexion
		mysql_close($conn);
		//Retornar vacio
		return $clave;
	}//Cierre de la funcion reporteKardex($fechaI,$fechaF,$area,$titulo)
	
	function reporteAgregados($idAgregado,$fechaIni,$fechaFin,$titulo){
		//Realizar la conexion a la BD de ALMACEN, ya que a su vez, se conecta a la BD de LABORATORIO
		$conn = conecta("bd_almacen");
		//Crear sentencia SQL acorde los parametros
		if($idAgregado=="")
			$stm_sql ="SELECT id_material, nom_material, origen_material, pruebas_agregados.fecha,pruebas_agregados.hora,id_pruebas_agregados FROM (materiales JOIN bd_laboratorio.pruebas_agregados
			           ON id_material=catalogo_materiales_id_material)WHERE fecha>='$fechaIni' AND fecha<='$fechaFin' 
					   ORDER BY nom_material";
		else
			$stm_sql ="SELECT id_material, nom_material, origen_material, pruebas_agregados.fecha, pruebas_agregados.hora,id_pruebas_agregados FROM (materiales JOIN bd_laboratorio.pruebas_agregados
			           ON id_material=catalogo_materiales_id_material)WHERE id_material='$idAgregado' AND fecha>='$fechaIni' AND fecha<='$fechaFin' 
					   ORDER BY nom_material";
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		$tabla="";
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos=mysql_fetch_array($rs)){						
			$tabla.= "								
			<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta' style='color:#FFF'>$titulo</caption>					
				<tr>
					<td class='nombres_columnas' align='center'>NOMBRE</td>
					<td class='nombres_columnas' align='center'>OR&Iacute;GEN MATERIAL</td>
					<td class='nombres_columnas' align='center'>FECHA DE REGISTRO</td>
					<td class='nombres_columnas' align='center'>HORA DE REGISTRO</td>
					<td class='nombres_columnas' align='center'>VER REPORTE</td>
			</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				$tabla.= "<tr>
						<td align='center' class='$nom_clase'>$datos[nom_material]</td>
						<td align='center' class='$nom_clase'>$datos[origen_material]</td>
						<td align='center' class='$nom_clase'>".modFecha($datos['fecha'],1)."</td>
						<td align='center' class='$nom_clase'>$datos[hora]</td>
						<td align='center' class='$nom_clase'>
						<input type='button' name='btn_archivo' id='btn_archivo' class='botones' value='Descargar' title='Descargar Reporte del Agregado $datos[nom_material]'
						onclick=\"window.open('../../includes/generadorPDF/reporteAgregados.php?id=$datos[id_pruebas_agregados]&nombre=ING. JOSE GUILLERMO MARTINEZ ROMAN&puesto=DIRECTOR GENERAL&empresa=CONCRETO LANZADO DE FRESNILLO', '_blank','top=100, left=100, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')\"/>
						</td>
				</tr>";									
					 
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			$tabla.= "</table>";
		}
		return $tabla;
	}
?>