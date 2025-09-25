<?php
	/**
	  * Nombre del Módulo: Topografía                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández                           
	  * Fecha: 26/Mayo/2011                                      			
	  * Descripción: Este archivo permite consultar los planos en el servidor asi como en la Base de datos
	  **/
	 	
		
	//Funcion que permite mostrar las fotografías registradas al presionar el boton
	function mostrarPlanos(){
		//Arcivos que se incluyen para obtener informacion de la bitácora
		include_once ("../../includes/conexion.inc");
		include_once ("../../includes/op_operacionesBD.php");
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Realizar la conexion con la BD
		$conn = conecta("bd_topografia");
		
		//Tomamos los datos que vienen del post y las modificamos para la consulta
		$fechaIni=modFecha($_POST["txt_fechaIni"],3);
		$fechaFin=modFecha($_POST["txt_fechaFin"],3);
		
		//Ruta donde se almacenan los documentos
		$carpeta="documentos/";
		
		//Revisar si se han agregado fotos a la Base de Datos
		$stm_sql="SELECT id_plano, nom_plano, descripcion, fecha, hora, nom_archivo FROM planos WHERE fecha>='$fechaIni' AND fecha<='$fechaFin'";
		//Ejecutar sentencia SQL
		$rs=mysql_query($stm_sql);
		//Verificar que se hayan encontrado resultados
		if ($datos=mysql_fetch_array($rs)){
			echo "				
				<table cellpadding='5' width='100%' align='center'> 
				<caption class='titulo_etiqueta'>Planos Registrados de <em><u>".modFecha($fechaIni,1)."</em></u> A <em><u>".modFecha($fechaFin,1)."</em></u> </caption></br>";
			echo "
				<tr>
					<td class='nombres_columnas' align='center'>ID PLANO</td>
					<td class='nombres_columnas' align='center'>NOMBRE PLANO</td>
					<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>FECHA</td>
					<td class='nombres_columnas' align='center'>HORA</td>
					<td class='nombres_columnas' align='center'>NOMBRE ARCHIVO</td>
					<td class='nombres_columnas' align='center'>PLANO</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			//Creamos la variable que permitira saber si los archivos de la BD corresponden con los del servidor
			$contArchivos=0;
			//Contador para saber el numero de revisiones que hace dentro de la carpeta seleccionada
			$contador=0;
			do{										
				echo "
					<tr>
						<td class='$nom_clase' align='center'>$datos[id_plano]</td>
						<td class='$nom_clase' align='center'>$datos[nom_plano]</td>
						<td class='$nom_clase' align='center'>$datos[descripcion]</td>
						<td class='$nom_clase' align='center'>".modFecha($datos['fecha'],1)."</td>
						<td class='$nom_clase' align='center'>$datos[hora]</td>
						<td class='$nom_clase' align='center'>$datos[nom_archivo]</td>";?>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_Archivo" class="botones" value="Ver Plano" onMouseOver="window.estatus='';return true" 
							title="Ver Plano<?php echo $datos['nom_archivo'];?>" 
							onClick="javascript:window.open('verPlano.php?id_plano=<?php echo $datos['nom_archivo'];?>&fecha=<?php echo $datos['fecha'];?>&hora=<?php echo $datos['hora'];?>',
							'_blank','top=50, left=50, width=200, height=200, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>							
						</td>
						<?php
												
				echo "</tr>";			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";			
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</table>";
		}
		else{
			echo "<label class='msje_correcto' align='center'><b>No Hay Planos Registrados de <em><u>".modFecha($fechaIni,1)."</em></u> A <em><u>".modFecha($fechaFin,1)."</em></u></b></label>";
		}
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}

	
	
?>