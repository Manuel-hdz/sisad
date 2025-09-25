<?php
	/**
	  * Nombre del Módulo: Direccion General
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 23/Febrero/2012                                      			
	  * Descripción: Este archivo contiene la función que muestra registros previos y siguientes
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");
			include("../../../../includes/op_operacionesBD.php");
		 
	//Recuperar los datos a buscar de la URL
	if (isset($_GET["bd"])){
		//Recoger los datos
		$accion = $_GET["accion"];
		$label=$_GET["etiqueta"];
		//Conectarse a la BD correspondiente
		if($_GET["bd"]=="gt"){
			//Convertir la etiqueta a arreglo para obtener la segunda posicion que es donde se encuentra el ID del ppto
			$arrLabel=split("_",$label);
			//Obtener el ID de la ubicacion segun el id del presupuesto
			$ubicacion=obtenerDato("bd_gerencia","presupuesto","catalogo_ubicaciones_id_ubicacion","id_presupuesto",$arrLabel[1]);
			//Abrir la conexion a la BD
			$conn = conecta("bd_gerencia");
			if($accion=="prev"){
				$band=0;
				//Obtener la posicion anterior
				$rs=mysql_query($sql_sum="SELECT id_presupuesto FROM presupuesto WHERE catalogo_ubicaciones_id_ubicacion='$ubicacion'");
				if($datos=mysql_fetch_array){
					//Arreglo con los id de presupuestos para la misma ubicacion
					$reg=array();
					do{
						$reg[]=$datos["id_presupuesto"];
						if ($datos["id_presupuesto"]==$arrLabel[1])
							break;
					}while($datos=mysql_fetch_array);
					//Si el tamaño del arreglo es mayor a 1, hay mas registros para mostrar
					if(count($reg)>1){
						$pos=count($reg)-1;
						//Sentencia SQL para extraer la posicion anterior a la mostrada en pantalla
						$sql_stm="SELECT ubicacion,periodo FROM presupuesto JOIN catalogo_ubicaciones ON catalogo_ubicaciones_id_ubicacion=id_ubicacion WHERE id_presupuesto='$reg[$pos]'";
						//Ejecutar la sentencia SQL
						$rs=mysql_query($sql_stm);
						if ($datos=mysql_fetch_array($rs)){
						
						}
					}
				}
			}
			//Sentencia SQL
			$sql_stm="SELECT sueldo_base,pctje_inc_act,pctje_inc_mts FROM catalogo_salarios WHERE puesto='$puesto' AND area='$area'";
		}
		else{
			$conn = conecta("bd_desarrollo");
		}
		
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Obtener los datos para manejarlos
		if($datos=mysql_fetch_array($rs)){
			$sueldo=number_format($datos["sueldo_base"],2,".",",");
			//Definir el tipo de contenido que tendra el archivo creado
			header("Content-type: text/xml");	 
			//Crear XML de la clave Generada
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<sueldo>$sueldo</sueldo>
					<incAct>$datos[pctje_inc_act]</incAct>
					<incMts>$datos[pctje_inc_mts]</incMts>
				</existe>");
		}else{
			//Definir el tipo de contenido que tendra el archivo creado
			header("Content-type: text/xml");	 
			//Crear XML de la clave Generada
			echo utf8_encode("
				<existe>
					<valor>false</valor>
				</existe>");
		}
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}
?>
