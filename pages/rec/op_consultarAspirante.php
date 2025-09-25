<?php
	/**
	  * Nombre del M�dulo: Recursos Humanos
	  * Nombre Programador: Nadia Madah� L�pez Hern�ndez
	  * Fecha: 12/Abril/2011
	  * Descripci�n: Este archivo contiene funciones para consultar la informaci�n relacionada con el formulario de Consultar Aspirantes a Empleo 
	**/
	
	function mostrarAspirantes(){
		//Conectar a la BD de Recursos Humanos
		$conn = conecta("bd_recursos"); 
	
		if(isset($_POST["sbt_consultarPuesto"])){ 		
			//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
			$f1 = modFecha($_POST['txt_fechaPuestoIni'],3);
			$f2 = modFecha($_POST['txt_fechaPuestoFin'],3);
			
			$puesto = "";
			if(isset($_POST['cmb_puesto']))
				$puesto = $_POST['cmb_puesto'];
				if ($puesto == "") {
					//Creamos la sentencia SQL para mostrar los datos de los Aspirantes de acuerdo al puesto seleccionado
					$stm_sql="SELECT  folio_aspirante, CONCAT(nombre,' ',ap_paterno,' ',ap_materno) AS nombre_aspirante, curp, edad ,estado_civil, nacionalidad, fecha_solicitud, lugar_nac, 
					bolsa_trabajo.telefono, tel_referencia, experiencia_laboral, observaciones, puesto
					FROM bolsa_trabajo JOIN area_puesto ON folio_aspirante = bolsa_trabajo_folio_aspirante
					WHERE fecha_solicitud>='$f1' AND fecha_solicitud<='$f2' ORDER BY folio_aspirante";
				} else {
					//Creamos la sentencia SQL para mostrar los datos de los Aspirantes de acuerdo al puesto seleccionado
					$stm_sql="SELECT  folio_aspirante, CONCAT(nombre,' ',ap_paterno,' ',ap_materno) AS nombre_aspirante, curp, edad ,estado_civil, nacionalidad, fecha_solicitud, lugar_nac, 
					bolsa_trabajo.telefono, tel_referencia, experiencia_laboral, observaciones, puesto
					FROM bolsa_trabajo JOIN area_puesto ON folio_aspirante = bolsa_trabajo_folio_aspirante
					WHERE puesto='$puesto'  AND fecha_solicitud>='$f1' AND fecha_solicitud<='$f2' ORDER BY folio_aspirante";
				}
				
			//Crear el mensaje que se mostrara en el titulo de la tabla de Aspirantes
			$msg = "Aspirantes Registrados en el Periodo del <em><u>$_POST[txt_fechaPuestoIni]</u></em> al <em><u> $_POST[txt_fechaPuestoFin]</u></em> en el Puesto <em><u>'$puesto' </em></u>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ning�n resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontrar&oacute;n  Aspirates Registrados en las Fechas del <em><u>$_POST[txt_fechaPuestoIni]
			</u></em> al <em><u>$_POST[txt_fechaPuestoFin]</u></em></label>";		
									
		}
		else if(isset($_POST["sbt_consultarAspirante"])){//Segunda Consulta para mostrar todos los aspirantes registrados en el sistema	
					
			//Creamos la sentencia SQL para mostrar los datoa de todos los aspirantes
			$stm_sql="SELECT  folio_aspirante, CONCAT(nombre,' ',ap_paterno,' ',ap_materno) AS nombre_aspirante, curp, edad ,estado_civil, nacionalidad, fecha_solicitud, lugar_nac, 
				bolsa_trabajo.telefono, tel_referencia, experiencia_laboral, observaciones
				FROM bolsa_trabajo ORDER BY folio_aspirante";

			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Datos de los Aspirantes Registrados";
			
			//Crear el Mensaje en caso de que la consulta no arroje ning�n resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontrar&oacute;n Aspirantes Registrados </label>";										
		}
		
		
		//Ejecutamos la sentencia SQL
		$rs=mysql_query($stm_sql);
		
		if ($datos=mysql_fetch_array($rs)){
			echo "
			<br />								
			<table class='tabla_frm' cellpadding='5' cellspacing='5' id='tablaAspirantes'>
				<caption class='titulo_etiqueta'>$msg</caption>
				<thead>
				<br />	
				<tr>
					<td class='nombres_columnas' align='center'>VER DETALLE</td>
					<th class='nombres_columnas' align='center'>FOLIO ASPIRANTE</th>
					<th class='nombres_columnas' align='center'>NOMBRE ASPIRANTE</th>
					<th class='nombres_columnas' align='center'>CURP</th>
					<td class='nombres_columnas' align='center'>EDAD</td>
					<td class='nombres_columnas' align='center'>ESTADO CIVIL</td>
					<td class='nombres_columnas' align='center'>NACIONALIDAD</td>
					<td class='nombres_columnas' align='center'>FECHA SOLICITUD</td>
					<td class='nombres_columnas' align='center'>LUGAR NACIMIENTO</td>
					<td class='nombres_columnas' align='center'>TELEFONO</td>
					<td class='nombres_columnas' align='center'>TELEFONO REFERENCIA </td>						
					<td class='nombres_columnas' align='center'>EXPERIENCIA LABORAL </td>
					<td class='nombres_columnas' align='center'>OBSERVACIONES</td>
					<td class='nombres_columnas' align='center'>CONTRATAR</td>						
				</tr>
				</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			echo "<tbody>";
			do{										
				echo "						
					<tr>
						<td class='nombres_filas' align='center'>
							<input type='checkbox' name='ckb_folioAspirante' value='$datos[folio_aspirante]'
							onClick='javascript:document.frm_resultadosConsultaAspirante.submit();'/>
						</td>				
						<td class='nombres_filas' align='center'>$datos[folio_aspirante]</td>
						<td class='$nom_clase' align='left'>$datos[nombre_aspirante]</td>
						<td class='$nom_clase' align='center'>$datos[curp]</td>
						<td class='$nom_clase' align='center'>$datos[edad]</td>
						<td class='$nom_clase' align='left'>$datos[estado_civil]</td>
						<td class='$nom_clase' align='left'>$datos[nacionalidad]</td>
						<td class='$nom_clase'>".modFecha($datos['fecha_solicitud'],1)."</td>																		
						<td class='$nom_clase' align='center'>$datos[lugar_nac]</td>
						<td class='$nom_clase' align='center'>$datos[telefono]</td>
						<td class='$nom_clase' align='center'>$datos[tel_referencia]</td>
						<td class='$nom_clase' align='left'>$datos[experiencia_laboral]</td>
						<td class='$nom_clase' align='left'>$datos[observaciones]</td>";?>
												
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_contratar" class="botones" value="Contratar" onMouseOver="window.estatus='';return true" 
							title="Contratar al Aspirante Seleccionado <?php echo $datos['folio_aspirante'];?>" 
							onClick="location.href='frm_agregarEmpleado.php?folioAspirante=<?php echo $datos['folio_aspirante']; ?>';"/>							
						<?php /*frm_agregarEmpleado.php?folioAspirante === Esto es para indicarle que una variable va tomar el valor que se encuentre en $datos['folio_aspirante'], 
							y si queremos mas datos se tienen que poner && $datos['folio_aspirante']&& $datos['folio_aspirante']*/ ?>
						</td>
				<?php		
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs)); 
			echo "
				</tbody>
			</table>";
		}
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;					
		}
		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);
	}//Fin de la funcion de mostrarAspirantes
	
	
	//Funcion que permite mostrar las �reas y puestos en los que se encuentra registrado un aspirante
	function detalleAspirante($folioAspirante){
		
		//Realizar la conexion a la BD de Recuros Humanos
		$conn = conecta("bd_recursos");
		
		
		//MOSTRAR LAS AREAS Y PUESTOS RECOMENDADOS PARA EL ASPIRANTE
		//Realizar la consulta para obtener el detalle de los puestos recomandados para el Aspirante que haya sido seleccionado
		$stm_sql ="SELECT CONCAT(nombre,' ',ap_paterno,' ',ap_materno) AS nombre, area, puesto FROM area_puesto JOIN bolsa_trabajo ON folio_aspirante = bolsa_trabajo_folio_aspirante
					WHERE folio_aspirante = '$folioAspirante' ORDER BY area";

		//Ejecutar la consulta y dibujar la tabla para mostrar el detalle
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			echo "
			<table cellpadding='5' width='50%'>
				<caption class='titulo_etiqueta'>Areas y Puestos Recomendados para el Aspirante:<br><em><u>$datos[nombre]</u></em> Con el Folio <em><u>$folioAspirante</em></u></caption>
				<tr>
					<td class='nombres_columnas' align='center'>&Aacute;REA</td>
					<td class='nombres_columnas' align='center'>PUESTO</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "
					<tr>		
						<td class='$nom_clase' align='left'>$datos[area]</td>
						<td class='$nom_clase' align='left'>$datos[puesto]</td>
					</tr>";	 
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "</table><br><br>";
		}
		
		
		//MOSTRAR LOS CONTACTOS ASOCIADOS AL ASPIRANTE
		//Realizar la consulta para obtener el detalle de los puestos recomandados para el Aspirante que haya sido seleccionado
		$stm_sql ="SELECT CONCAT(nombre,' ',ap_paterno,' ',ap_materno) AS nombre, nom_contacto, calle, num_ext, num_int, colonia, estado, pais, contacto.telefono
					FROM contacto JOIN bolsa_trabajo ON folio_aspirante = bolsa_trabajo_folio_aspirante
					WHERE folio_aspirante = '$folioAspirante' ORDER BY nom_contacto";

		//Ejecutar la consulta y dibujar la tabla para mostrar el detalle
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			echo "
			<table cellpadding='5' width='80%'>
				<caption class='titulo_etiqueta'>Contactos del Aspirante:<br><em><u>$datos[nombre]</u></em> Con el Folio <em><u>$folioAspirante</em></u></caption>
				<tr>
					<td class='nombres_columnas' align='center'>NOMBRE</td>
					<td class='nombres_columnas' align='center'>CALLE</td>
					<td class='nombres_columnas' align='center'>NO. EXTERIOR</td>
					<td class='nombres_columnas' align='center'>NO. INTERIOR</td>
					<td class='nombres_columnas' align='center'>COLONIA</td>
					<td class='nombres_columnas' align='center'>ESTADO</td>
					<td class='nombres_columnas' align='center'>PAIS</td>
					<td class='nombres_columnas' align='center'>TELEFONO</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "
					<tr>		
						<td class='$nom_clase' align='left'>$datos[nom_contacto]</td>
						<td class='$nom_clase' align='left'>$datos[calle]</td>
						<td class='$nom_clase' align='left'>$datos[num_ext]</td>
						<td class='$nom_clase' align='left'>$datos[num_int]</td>
						<td class='$nom_clase' align='left'>$datos[colonia]</td>
						<td class='$nom_clase' align='left'>$datos[estado]</td>
						<td class='$nom_clase' align='left'>$datos[pais]</td>
						<td class='$nom_clase' align='left'>$datos[telefono]</td>
					</tr>";	 
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "</table>";
		}
						
		
		//Cerrar la conexion con la BD
		mysql_close($conn); 
	}
?>