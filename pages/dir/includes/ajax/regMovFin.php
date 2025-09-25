<?php
	/**
	  * Nombre del Módulo: Direccion General
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 12/Marzo/2012                                      			
	  * Descripción: Este archivo contiene la función que muestra registros previos y siguientes
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");
			include("../../../../includes/op_operacionesBD.php");
			include("../../../../includes/func_fechas.php");
		 
	//Recuperar los datos a buscar de la URL
	if (isset($_GET["accion"])){
		$tipoRep=$_GET["accion"];
		$clasificacion=$_GET["clasificacion"];
		switch($tipoRep){
			case "show":
				$fecha=modFecha($_GET["fecha"],3);
				$titulo="MOVIMIENTOS DE ".obtenerDato("bd_direccion","finanzas","clasificacion","id_pto",$clasificacion)." EN ".modFecha($fecha,6);
				$tabla=crearTablaMensual($fecha,$clasificacion);
				header("Content-type: text/xml");	
				if ($tabla!=""){
					$tabla=str_replace("<","¬",$tabla);
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
			case "add":
				$fecha=modFecha($_GET["fecha"],3);
				$tipo=$_GET["tipo"];
				$cantidad=str_replace(",","",$_GET["cantidad"]);
				$concepto=strtoupper($_GET["concepto"]);
				$responsable=strtoupper($_GET["responsable"]);
				$res=guardarRegBit($fecha,$tipo,$cantidad,$concepto,$responsable,$clasificacion);
				header("Content-type: text/xml");
				if($res==1){
					//Crear XML de exito
					echo utf8_encode("
					<existe>
						<valor>true</valor>
						<descto>$cantidad</descto>
						<tipo>$tipo</tipo>
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
	
	/*Esta funcion genera el reporte mensual y regresa el periodo para indicar que los datos mostrados pueden ser exportados*/
	function crearTablaMensual($fecha,$clasificacion){
		//Conectarse a la Base de Datos de Produccion
		$conn = conecta("bd_direccion");
		$fechaI=substr($fecha,0,7)."-01";
		$fechaF=diasMes(substr($fechaI,5,2), substr($fechaI,0,4));
		$fechaF=substr($fechaI,0,8).$fechaF;
		$stm_sql="SELECT fecha,tipo_mov,concepto,responsable,cantidad FROM bitacora_finanzas JOIN finanzas ON finanzas_id_pto=id_pto WHERE 
				fecha BETWEEN '$fechaI' AND '$fechaF' AND id_pto='$clasificacion' ORDER BY fecha,tipo_mov";
		$rs=mysql_query($stm_sql);
		$tabla="";
		if($datos=mysql_fetch_array($rs)){
			$tabla="<table border='0' cellpadding='5' class='tabla_frm' width='80%'>
			<tr>
				<td class='nombres_columnas'>FECHA</td>
				<td class='nombres_columnas' align='center'>TIPO MOVIMIENTO</td>
				<td class='nombres_columnas' align='center'>CONCEPTO</td>
				<td class='nombres_columnas' align='center'>RESPONSABLE</td>
				<td class='nombres_columnas' align='center'>CANTIDAD</td>
			<tr>";
			//Manipular el color de los renglones de cada ubicación
			$nom_clase = "renglon_gris";
			$cont = 1;
			$sumIngreso=0;
			$sumEgreso=0;
			do{
				$tabla.="
							<tr>
							<td align='center' class='$nom_clase'><strong>".modFecha($datos['fecha'],1)."</strong></td>
							<td align='center' class='$nom_clase'><strong>$datos[tipo_mov]</strong></td>
							<td align='center' class='$nom_clase'><strong>$datos[concepto]</strong></td>
							<td align='center' class='$nom_clase'><strong>$datos[responsable]</strong></td>
							<td align='center' class='$nom_clase'><strong>$".number_format($datos["cantidad"],2,".",",")."</strong></td>
							</tr>";
				if($datos["tipo_mov"]=="INGRESO")
					$sumIngreso+=$datos["cantidad"];
				else
					$sumEgreso+=$datos["cantidad"];
			}while($datos=mysql_fetch_array($rs));
			$tabla.="
				<tr>
					<td colspan='3'></td>
					<td class='nombres_columnas'>TOTAL INGRESO</td>
					<td class='nombres_filas'>$".number_format($sumIngreso,2,".",",")."</td>
				</tr>
				<tr>
					<td colspan='3'></td>
					<td class='nombres_columnas'>TOTAL EGRESO</td>
					<td class='nombres_filas'>$".number_format($sumEgreso,2,".",",")."</td>
				</tr>
				";
			$tabla.="</table>";
		}
		//Cerrar la BD
		mysql_close($conn);
		return $tabla;
	}//Cierre de la funcion verReporteMensual()
	
	function guardarRegbit($fecha,$tipo,$cantidad,$concepto,$responsable,$clasificacion){
		$idBit=obtenerIdBitDG();
		//Variable para control de resultados
		$band=1;
		//Conectarse a la Base de Datos de Produccion
		$conn = conecta("bd_direccion");
		//Sentencia SQL para guardar el registro
		$sql_stm="INSERT INTO bitacora_finanzas(id_mov,finanzas_id_pto,fecha,tipo_mov,cantidad,concepto,responsable) VALUES ('$idBit','$clasificacion','$fecha','$tipo','$cantidad','$concepto','$responsable')";
		$rs=mysql_query($sql_stm);
		if($rs){
			if($tipo=="EGRESO")
				//Sentencia para guardar el restante
				$sql_stm="UPDATE finanzas SET presupuesto=presupuesto-$cantidad WHERE id_pto='$clasificacion'";
			if($tipo=="INGRESO")
				//Sentencia para guardar el restante
				$sql_stm="UPDATE finanzas SET presupuesto=presupuesto+$cantidad WHERE id_pto='$clasificacion'";
			//ejecucion de la consulta
			$rs=mysql_query($sql_stm);
			//Cerrar la conexion a la BD
			mysql_close($conn);
			//Si hubo errores desactivar la bandera
			if(!$rs)
				$band=0;
			else{
				session_start();
				//Guardar el Movimiento realizado en la tabla de Movimientos
				registrarOperacion("bd_direccion","$idBit","Registrar$tipo - $clasificacion",$_SESSION['usr_reg']);
			}
		}
		//Si hubo errores desactivar la bandera
		else
			$band=0;
		//Retornar la bandera
		return $band;
	}
	
	function obtenerIdBitDG(){
		//Realizar la conexion a la BD de USO
		$conn = conecta("bd_direccion");
		//Crear la sentencia para obtener el numero de registros en la Bitacora
		$stm_sql = "SELECT MAX(id_mov) AS cant FROM bitacora_finanzas";
		//Ejecucion de la consulta
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			if($datos['cant']==NULL)
				$cant = 1;
			else
				$cant = $datos['cant'] + 1;
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
		//Retornar el id de la Bitacora
		return $cant;
	}
?>