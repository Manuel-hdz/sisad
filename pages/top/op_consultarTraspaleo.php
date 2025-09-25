<?php
	/**
	  * Nombre del Módulo: Topografía
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 03/Junio/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de consultar traspaleo en la BD
	**/
	
	//Funcion que se encarga de desplegar los traspaleos según el criterio de busqueda utilizado
	function mostrarTraspaleos(){

		//Conectar a la BD de Topografía
		$conn = conecta("bd_topografia");
		
		//Si viene sbt_consultarObra la buqueda de los traspaleos proviene de seleccionar una obra
		if(isset($_POST["sbt_consultarObra"])){ 
			
			//Crear sentencia SQL
			$sql_stm ="SELECT * FROM traspaleos JOIN obras ON id_obra=obras_id_obra WHERE tipo_obra='$_POST[cmb_obra]' AND nombre_obra='$_POST[cmb_nombreObra]'";	
					
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Traspaleos de <em><u>  $_POST[cmb_obra]    </u></em> de la Obra <em><u>	$_POST[cmb_nombreObra]  </u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ningun Traspaleo de <em><u>  $_POST[cmb_obra]    
			</u></em> de la Obra <em><u>	$_POST[cmb_nombreObra]  </u></em>";	
			
			// hiddens que permiten que al regresar de ver el detalle nos muestre la consulta previamente realizada?>
			<input type="hidden" name="hdn_tipoObra" value="<?php echo $_POST['cmb_obra'] ?>"/>
			<input type="hidden" name="hdn_nombreObra" value="<?php echo $_POST['cmb_nombreObra'] ?>"/>
			<input type="hidden" name="hdn_consultarObra" value="<?php echo $_POST['sbt_consultarObra'] ?>"/><?php
		}
		
		//Si viene sbt_consultarMes la buqueda de los traspaleos proviene de seleccionar un mes y año
		if(isset($_POST["sbt_consultarMes"])){ 
		
			//Crear sentencia SQL
			$sql_stm ="SELECT * FROM traspaleos JOIN obras ON id_obra=obras_id_obra WHERE  no_quincena LIKE'% $_POST[cmb_mes] $_POST[cmb_anios]'";	
					
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Traspaleos del mes de  <em><u>  $_POST[cmb_mes]    </u></em> a&ntilde;o <em><u>	$_POST[cmb_anios]  </u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ningun Traspaleo del mes de  <em><u>  $_POST[cmb_mes]    
			</u></em> a&ntilde;o <em><u>	$_POST[cmb_anios]  </u></em>";
			
			// hiddens que permiten que al regresar de ver el detalle nos muestre la consulta previamente realizada?>
			<input type="hidden" name="hdn_mes" value="<?php echo $_POST['cmb_mes'] ?>"/>
			<input type="hidden" name="hdn_anios" value="<?php echo $_POST['cmb_anios'] ?>"/>
			<input type="hidden" name="hdn_consultarMes" value="<?php echo $_POST['sbt_consultarMes'] ?>"/><?php
		}
		
		//Si viene sbt_consultarQuincena la buqueda de los traspaleos proviene de seleccionar una quincena de una obra específica
		if(isset($_POST["sbt_consultarQuincena"])){ 
					
			//Crear sentencia SQL
			$sql_stm ="SELECT * FROM traspaleos JOIN obras ON id_obra=obras_id_obra  WHERE id_obra='$_POST[cmb_nomObra]' AND no_quincena='$_POST[cmb_numQuincena]'";		
					
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Traspaleos de  la Obra <em><u>	$_POST[cmb_nomObra]  </u></em> de la Quincena 
			<em><u>	$_POST[cmb_numQuincena]  </u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ningun Traspaleo de la Obra <em><u>	$_POST[cmb_nomObra]  
			</u></em> de la Quincena <em><u>	$_POST[cmb_numQuincena]  </u></em>";	
			
			// hiddens que permiten que al regresar de ver el detalle nos muestre la consulta previamente realizada?>
			<input type="hidden" name="hdn_idObra" value="<?php echo $_POST['cmb_nomObra'] ?>"/>
			<input type="hidden" name="hdn_noQuincena" value="<?php echo $_POST['cmb_numQuincena'] ?>"/>
			<input type="hidden" name="hdn_consultarQuincena" value="<?php echo $_POST['sbt_consultarQuincena'] ?>"/><?php
		}
		
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
	
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='1500'>				
				<tr>
					<td colspan='18' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center'>VER DETALLE</td>
					<td class='nombres_columnas' align='center'>TIPO OBRA</td>
					<td class='nombres_columnas' align='center'>NOMBRE OBRA</td>
					<td class='nombres_columnas' align='center'>QUINCENA</td>
					<td class='nombres_columnas' align='center'>ACUMULADO</td>
					<td class='nombres_columnas' align='center'>SECCI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>&Aacute;REA</td>
					<td class='nombres_columnas' align='center'>VOLUMEN</td>
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='nombres_filas' width='6%' align='center'>
							<input type='checkbox' name='ckb' value='$datos[id_traspaleo]' 
							onClick='javascript:document.frm_consultarDetTraspaleo.submit();'/>
						</td>
						<td class='$nom_clase'>$datos[tipo_obra]</td>
						<td class='$nom_clase'>$datos[nombre_obra]</td>
						<td class='$nom_clase'>$datos[no_quincena]</td>
						<td class='$nom_clase'>$datos[acumulado_quincena]</td>
						<td class='$nom_clase'>$datos[seccion]</td>
						<td class='$nom_clase'>$datos[area]</td>
						<td class='$nom_clase'>$datos[volumen]</td>
					</tr>";
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "</table>";
			return 1;
			
		}// fin  if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;		
			return 0;		
		}
	}
	
	//Funcion que permite mostrar el detalle del traspaleo seleccionado en un checkbox
	function mostrarDetalleTrasp($ckb){
		
		//Conectar a la BD de Topografía
		$conn = conecta("bd_topografia");
		
		//Realizar la consulta para obtener el detalle del traspaleo seleccionado
		$stm_sql = "SELECT * FROM detalle_traspaleos WHERE traspaleos_id_traspaleo= '$ckb' ORDER BY no_registro";
		
		//Ejecutar la consulta y dibujar la tabla para mostrar el detalle
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			echo "					
			<label class='titulo_etiqueta'>DETALLE DEL TRASPALEO <em><u>$ckb</u></em></label>								
			<br><br>								
			<table cellpadding='5'>
				<tr>
					<td class='nombres_columnas' align='center'>FECHA REGISTRO</td>
					<td class='nombres_columnas' align='center'>NO.</td>
					<td class='nombres_columnas' align='center'>ORIGEN</td>
					<td class='nombres_columnas' align='center'>DESTINO</td>						
					<td class='nombres_columnas' align='center'>DISTANCIA</td>
					<td class='nombres_columnas' align='center'>PRECIO UNITARIO MN</td>
					<td class='nombres_columnas' align='center'>PRECIO UNITARIO USD</td>
					<td class='nombres_columnas' align='center'>TOTAL MN</td>
					<td class='nombres_columnas' align='center'>TOTAL USD</td>
					<td class='nombres_columnas' align='center'>IMPORTE TOTAL</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			$sumatoria = 0;
			do{
				echo "<tr>	
						<td class='$nom_clase'>".modFecha($datos['fecha_registro'],1)."</td>
						<td class='$nom_clase'>$datos[no_registro]</td>
						<td class='$nom_clase'>$datos[origen]</td>
						<td class='$nom_clase'>$datos[destino]</td>
						<td class='$nom_clase'>$datos[distancia]</td>
						<td class='$nom_clase'>$".number_format($datos['pu_mn'],2,".",",")."</td>
						<td class='$nom_clase'>$".number_format($datos['pu_usd'],2,".",",")."</td>
						<td class='$nom_clase'>$".number_format($datos['total_mn'],2,".",",")."</td>
						<td class='$nom_clase'>$".number_format($datos['total_usd'],2,".",",")."</td>
						<td class='$nom_clase'>$".number_format($datos['importe_total'],2,".",",")."</td>
					</tr>";
				//Acumular el Costo total de los Movimeintos realizados en Traspaleo
				$sumatoria += $datos['importe_total'];	
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "
				<tr>
					<td colspan='9' align='right'><strong>TOTAL</strong></td>
					<td>$ ".number_format($sumatoria,2,".",",")."</td>
				</tr>
			</table><br><br><br>";
		}else{
			echo "<br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No Hay Personal Detalle Registrado del Traspaleo</p>";
		}
		
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}	
	
	
	function cargarAniosDisponible(){
	
		//conectar a bd_topografia
		$conn = conecta('bd_topografia');
		
		$rs_quincenas = mysql_query("SELECT DISTINCT no_quincena FROM traspaleos");
		
		$anios = array();
		
		while($datos_quincenas=mysql_fetch_array($rs_quincenas)){
			$quincena = $datos_quincenas['no_quincena'];
			$anios[] = substr($quincena, -4); 
		}
		
		$anioUnico = array_unique($anios);?>
		
		<select name="cmb_anios" id="cmb_anios" class="combo_box">  
		<option value="">Seleccione A&ntilde;o</option> <?php
		foreach($anioUnico as $ind => $anio){ ?>
			<option value="<?php echo $anio;?>"><?php echo $anio;?></option><?php
		}?>
		</select><?php
		
		//cerrar conexion
		mysql_close($conn);	
	} //Fin function cargarAniosDisponible()
?>