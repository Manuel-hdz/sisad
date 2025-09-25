<?php

	/**
	  * Nombre del Módulo: Compras
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 29/Diciembre/2010                                      			
	  * Descripción: Este archivo contiene funciones desplegar la informacion de los Reportes REA publicados por Almacén
	  **/
	
	//Funcion para mostrar los Reportes REA publicados por Almacen
	//REA -> Reporte de Entradas al Almacén
	function mostrarREA(){
		//Conectar a la BD de Almacén
		$conn=conecta("bd_almacen");
		//Obtener la fecha limite de los Reportes
		//Tomamos las fechas del post y las convertimos a formato necesario para la consulta		
		$fechaIni=modFecha($_POST["txt_fechaIni"],3);
		$fechaFin=modFecha($_POST["txt_fechaFin"],3);
		
		//Consultas que se mostraran dependiendo del elemento seleccionado
		//Crear la Sentencia SQL
		$sql_stm = "SELECT id_reporte_rea,fecha_creacion,hora FROM reporte_rea WHERE fecha_creacion>='$fechaIni' AND fecha_creacion<='$fechaFin' ORDER BY fecha_creacion";

		//Ejecutar la Sentencia para obtener los datos del proveedor seleccionado
		$rs = mysql_query($sql_stm);
		if($datos = mysql_fetch_array($rs)){
			echo "<table cellpadding='5' width='850' align='center'> 
				<caption class='titulo_etiqueta'>REPORTES REA PUBLICADOS POR ALMAC&Eacute;N DE ".strtoupper(modFecha($fechaIni,2))." A ".strtoupper(modFecha($fechaFin,2))."</caption></br>";
			echo "<tr>
						<td class='nombres_columnas' align='center'>DETALLE</td>
						<td class='nombres_columnas' align='center'>NO.</td>
						<td class='nombres_columnas' align='center'>PROVEEDOR</td>
						<td class='nombres_columnas' align='center'>FECHA ENTRADA</td>
						<td class='nombres_columnas' align='center'>HORA ENTRADA</td>
						<td class='nombres_columnas' align='center'>ORIGEN</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			//Variable para almacenar el origen
			$origen = "";
			//Variables para almacenar los datos de los materiales
			$material = "";
			$claveMat = "";
			do{
				$idEntrada=obtenerDato("bd_almacen","detalle_reporte_rea","entradas_id_entrada","reporte_rea_id_reporte_rea",$datos['id_reporte_rea']);
				$proveedor=obtenerDato("bd_almacen","entradas","proveedor","id_entrada",$idEntrada);
				//REalozamos la siguiente consulta para verificar el origen del material
				$origenArr = "SELECT requisiciones_id_requisicion, orden_compra_id_orden_compra,comp_directa FROM entradas WHERE id_entrada='$idEntrada'";
				$origenAux =mysql_fetch_array(mysql_query($origenArr));
				if($origenAux['requisiciones_id_requisicion']!="")
					$origen = "REQUISICI&Oacute;N";	
				if($origenAux['orden_compra_id_orden_compra']!="")
					$origen = "ORDEN DE COMPRA";
				if($origenAux['comp_directa']!="")
					$origen = "COMPRA DIRECTA";	
				echo "
					<tr>
						<td class='nombres_filas'><input type='checkbox' name='ckb_id' value='$idEntrada'
						onclick='document.frm_mostrarDetalleREA.submit();'/></td>
						<td class='$nom_clase' align='center'>$cont</td>
						<td class='$nom_clase' align='center'>$proveedor</td>					
						<td class='$nom_clase' align='center'>".modFecha($datos["fecha_creacion"],2)."</td>
						<td class='$nom_clase' align='center'>$datos[hora]</td>
						<td class='$nom_clase' align='center'>$origen</td>";	
						echo "</td>
					</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs));
			echo "
			</table>";
		}
		else{
			echo "</br></br></br></br></br></br></br></br></br><p align='center' class='msje_correcto'>NO EXISTEN REPORTES REA PUBLICADOS</p>";
		}
		//Cerar conexion a BD
		mysql_close($conn);		
	}	
	
	//Funcion que nos permite mostrar el detalle del registro seleccionado
	function mostrarDetalleREA($id){
		//Conectar a la BD de Almacén
		$conn=conecta("bd_almacen");
		
		//Crear la Sentencia SQL
		$sql_stm = "SELECT  materiales_id_material, nom_material FROM detalle_entradas WHERE entradas_id_entrada='$id'";
		
		
		//Ejecutar la Sentencia para obtener los datos del proveedor seleccionado
		$rs = mysql_query($sql_stm);
		if($datos = mysql_fetch_array($rs)){
			echo "<table cellpadding='5' width='100%' align='center'> 
				<caption class='titulo_etiqueta'>MATERIALES CONTENIDOS EN EL REGISTRO SELECCIONADO</caption></br>";
			echo "<tr>
						<td class='nombres_columnas' align='center'>NO.</td>
						<td class='nombres_columnas' align='center'>CLAVE MATERIAL</td>
						<td class='nombres_columnas' align='center'>NOMBRE MATERIAL</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "
					<tr>
						<td class='$nom_clase' align='center'>$cont</td>";
						if($datos["materiales_id_material"]=="¬NOVALE")
							$idMat="NO APLICA";
						else
							$idMat=$datos["materiales_id_material"];
				echo "
						<td class='$nom_clase' align='center'>$idMat</td>
						<td class='$nom_clase' align='center'>$datos[nom_material]</td>";	
						echo "</td>
					</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs));
			echo "
			</table>";
		}
		else{
			echo "</br></br></br></br></br></br></br></br></br><p align='center' class='msje_correcto'>NO EXISTEN MATERIALES VINCULADOS CON EL REGISTRO SELECCIONADO</p>";
		}
		//Cerar conexion a BD
		mysql_close($conn);		
	}	
?>