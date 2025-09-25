<?php
	/**
	  * Nombre del Módulo: Laboratorio                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández                           
	  * Fecha: 18/Junio/2011                                      			
	  * Descripción: Este archivo permite consultar los Agregados de Laboratorio pero contenidos en  la Base de Datos de Almacén
	  **/
	 	

	//Función que permite mostrar las Mezclas con los parametros seleccionados en frm_reporteAgregados.php
	function mostrarAgregados(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Realizar la conexion a la BD de Almacén
		$conn = conecta("bd_almacen");
		
		//Recuperamos las fechas del POST y las convertimos al formato de la Base de datos
		$fechaIni = modFecha($_POST["txt_fechaIni"],3);
		$fechaFin = modFecha($_POST["txt_fechaFin"],3);
	
		if(isset($_POST["cmb_agregado"])){
			//Creamos la sentencia SQL
			$stm_sql ="SELECT id_material, nom_material, pruebas_agregados.fecha, pruebas_agregados.hora FROM (materiales JOIN bd_laboratorio.pruebas_agregados
			           ON id_material=catalogo_materiales_id_material)WHERE nom_material='$_POST[cmb_agregado]' AND fecha>='$fechaIni' AND fecha<='$fechaFin' 
					   ORDER BY nom_material";
			
			//Variable que guarda el titulo de la tabla
			$titulo="Agregados Registrados <u><em>".$_POST["cmb_agregado"] ."</u></em> de <u><em>".$_POST['txt_fechaIni']."</u></em> a"; 
			$titulo.= " <u><em>".$_POST['txt_fechaFin']."</u></em>";
			
			//Variable que almacena el msj de error
			$error="No existen Agregados Registrados con el nombre <u><em>". $_POST["cmb_agregado"]."</u></em> de"; 
			$error.=" <u><em>".$_POST['txt_fechaIni']."</u></em> a <u><em>".$_POST['txt_fechaFin']."</u></em>";
		}
		else{
			//Creamos la sentencia SQL
			$stm_sql ="SELECT id_material, nom_material, pruebas_agregados.fecha,pruebas_agregados.hora FROM (materiales JOIN bd_laboratorio.pruebas_agregados
			           ON id_material=catalogo_materiales_id_material)WHERE fecha>='$fechaIni' AND fecha<='$fechaFin' 
					   ORDER BY nom_material";
			
			//Variable que guarda el titulo de la tabla
			$titulo="Agregados Registrados de <u><em>".$_POST['txt_fechaIni']."</u></em> a <u><em>".$_POST['txt_fechaFin']."</u></em>";
			
			//Variable que almacena el msj de error
			$error="No existen Agregados Registrados de <u><em>".$_POST['txt_fechaIni']."</u></em> a <u><em>".$_POST['txt_fechaFin']."</u></em>";
		}
			
					
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos=mysql_fetch_array($rs)){						
			echo "								
			<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'>$titulo</caption>					
				<tr>
					<td class='nombres_columnas'>SELECCIONAR</td>
					<td class='nombres_columnas'>NOMBRE</td>
					<td class='nombres_columnas'>FECHA DE REGISTRO</td>
					<td class='nombres_columnas'>HORA DE REGISTRO</td>
			</tr>";
			echo "<form name='frm_detalleAgregado' method='post' action='frm_reporteAgregados.php'>
					<input type='hidden' name='verDetalle' value='si' />
					<input type='hidden' name='hdn_nombre' value='$datos[nom_material]'/>
					<input type='hidden' name='hdn_fecha' id='hdn_fecha' value='$datos[fecha]'/>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "<tr>
						<td class='nombres_filas' align='center'>
							<input type='checkbox' name='ckb_detalleAgregado' value='$datos[id_material].$datos[fecha].$datos[nom_material].$datos[hora]'
							onClick='javascript:document.frm_detalleAgregado.submit();'/>
						</td>		
						<td align='center' class='$nom_clase'>$datos[nom_material]</td>
						<td align='center' class='$nom_clase'>".modFecha($datos['fecha'],1)."</td>
						<td align='center' class='$nom_clase'>$datos[hora]</td>
				</tr>";									
					 
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));

			echo "</table>
				<input type='hidden' name='hdn_titulo' value='$titulo' />
				<input type='hidden' name='txt_fechaIni' />
			</form>";
			return 1;
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'>$error</label>";
			return 0;
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion
	
	
	
	//Funcion que permite mostrar el detalle de los prestanmos  de acuerdo al Empleado
	function detalleAgregado($idAgregado){
		//Realizar la conexion a la BD de Laboratorio
		$conn = conecta("bd_laboratorio");
		
		//Variable que permite verificar si la consulta arrojo resultados
		$flag=0;
		
		//Seccionamos los datos para mostrar en el reporte
		$seccCheck=explode(".",$_POST["ckb_detalleAgregado"]);
		$id_material=$seccCheck[0];
		$fecha=$seccCheck[1];
		$nombre=$seccCheck[2];
		$hora=$seccCheck[3];
		$error="No existe Detalle para el Agregado <u><em>". $nombre."</u></em>";
		
		//Realizar la consulta para obtener  
		$stm_sql ="SELECT id_pruebas_agregados, concepto, limite_superior, limite_inferior, retenido, numero FROM (detalle_prueba_agregados JOIN pruebas_agregados 
					ON id_pruebas_agregados=pruebas_agregados_id_pruebas_agregados)  WHERE  catalogo_materiales_id_material = '$id_material'  AND fecha='$fecha' AND hora='$hora' ORDER BY numero";

		//Ejecutar la consulta y dibujar la tabla para mostrar el detalle de la tabla Deducciones
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
		$flag=1;
			echo "
			<table cellpadding='5' width='80%'>
				<caption class='titulo_etiqueta'>Detalle Agregado <em><u>$nombre</u></em></caption>
				<tr>					
					<td class='nombres_columnas' align='center'>N&Uacute;MERO</td>
					<td class='nombres_columnas' align='center'>CONCEPTO</td>
					<td class='nombres_columnas' align='center'>L&Iacute;MITE SUPERIOR</td>
					<td class='nombres_columnas' align='center'>L&Iacute;MITE INFERIOR</td>					
					<td class='nombres_columnas' align='center'>RETENIDO</td>					
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "<tr>		
						<td class='$nom_clase' align='center'>$datos[numero]</td>
						<td class='$nom_clase' align='center'>$datos[concepto]</td>
						<td class='$nom_clase' align='center'>$datos[limite_superior]</td>
						<td class='$nom_clase' align='center'>$datos[limite_inferior]</td>
						<td class='$nom_clase' align='center'>$datos[retenido]</td>					
					</tr>";	 
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				$idPBM=$datos['id_pruebas_agregados'];
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "</table>";
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'>$error</label>";
		}?>
		</div>			
		<div id="btns-regpdf" align="center">
		<table align="center" >
			<tr>			
				<td align="center">
				  	<input type="button" name="btns_regresar"  value="Regresar" class="botones" title="Regresar al Men&uacute; Reportes" 
					onMouseOver="window.estatus='';return true" 
				  	onclick="location.href='frm_reporteAgregados.php?band=si'" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</td>
					<?php 
					if($flag==1){ //Declaramos las variables para guardar el resultado de las consultas y mostrarlo en Excel?>			
						<td align="center">
							<form method="post">
								<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>"/>
								<input name="hdn_nomReporte" type="hidden" 
								value="Reporte_Agregado<?php echo $_POST['hdn_nombre'];?>" />
								<input name="hdn_msg" type="hidden" value="<?php echo $_POST['hdn_titulo']; ?>"/>
								<input name="hdn_idAgregado" type="hidden" value="<?php echo $idAgregado; ?>"/>
								<input name="hdn_PBM" type="hidden" value="<?php echo $idPBM; ?>"/>
								<input name="hdn_tituloTabla" type="hidden" value="<?php echo $_POST['hdn_nombre']; ?>"/>
								<input name="hdn_origen" type="hidden" value="reporteAgregado"/>
								<?php if($cont-1>1){ ?>	
									<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" 
									title="Exportar a Excel los Datos de la Consulta Realizada" onMouseOver="window.estatus='';return true"/>
								<?php }?>
							</form>
						</td>
				<?php }?>
			</tr>
		</table>			
		</div><?php 
		//Cerrar la conexion con la BD
		mysql_close($conn); 
	}
	
	//Funcion Borrar Temproales
	function borrarTemporales(){
		//Borrar los ficheros temporales
		$h=opendir('tmp/');
		while ($file=readdir($h)){
			if (substr($file,-4)=='.png'){
				@unlink("tmp/".$file);
				echo $file;
			}
		}
		closedir($h);
	}
	
?>