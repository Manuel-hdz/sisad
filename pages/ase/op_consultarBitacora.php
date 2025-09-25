<?php
	/**
	  * Nombre del Módulo: Mantenimiento
	  * Nombre Programador: Daisy Adriana Martinez Fernandez
	  * Fecha: 22/Febrero/2011
	  * Descripción: Permite consultar la informacion de la Bitacora 
	**/

	  
	/******************************************************************************************************************
						FUNCIONES PARA CONSULTAR LA INFORMACION DE LOS MTTO'S PREVENTIVOS
	 ******************************************************************************************************************/
	/*Esta función muestra las ordenes de trabajo realizadas en la bitacora*/
	function generarConsulta(){
		?><div id="reporte" class="borde_seccion2" align="center"><?php
		
		//Realizar la conexion a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
		
		//Variable para verificar si la consulta ejecutada arrojo resultados
		$flag = 0;
		$idOrdenTrabajo = "";
		if(isset($_GET["ot"])){
			$bit = $_GET["bit"];
			$idOrdenTrabajo = $_GET["ot"];
		}
		else{
			$bit = $_POST["txt_bitacora"];
			$idOrdenTrabajo = $_POST["cmb_OT"];
		}


		//Crear la consulta 
		$stm_sql = "SELECT equipos_id_equipo, tipo_mtto, fecha_mtto, turno, horometro, odometro, tiempo_total, costo_mtto, num_factura, comentarios, prox_mtto FROM bitacora_mtto 
					WHERE orden_trabajo_id_orden_trabajo='$idOrdenTrabajo'";		
		//Mensaje para desplegar en el titulo de la tabla
		$msg_titulo = "Bit&aacute;cora <em><u>$bit</u></em> Generada para la Orden de Trabajo <em><u>$idOrdenTrabajo</u></em>";		
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "<label class='msje_correcto' align='center'>No se Encontraron resultados con la Orden de Trabajo <em><u>$idOrdenTrabajo</u></em>";																								
		
		
		//Definir datos de la consulta en la SESSION
		if(isset($_GET["ot"]))
			$datosConsBitacora = array("cmb_OT"=>$_GET['ot'], "id_bitacora"=>$bit);
		else			
			$datosConsBitacora = array("cmb_OT"=>$_POST['cmb_OT'], "id_bitacora"=>$bit);
		//Guardar datos en la SESSION
		$_SESSION['datosConsBitacora'] = $datosConsBitacora;
		
		
		//Ejecutar la consulta
		$rs = mysql_query($stm_sql);
		//Mostrar los resultados obtenidos
		if($datos = mysql_fetch_array($rs)){
			//Variable para verificar si la consulta ejecutada arrojo resultados
			$flag = 1;
			echo "								
				<table cellpadding='5'>
					<caption class='titulo_etiqueta'>$msg_titulo</caption>					
					<tr>
						<td class='nombres_columnas'>VER DETALLE</td>
						<td class='nombres_columnas'>CLAVE EQUIPO</td>
						<td class='nombres_columnas'>TIPO MTTO.</td>
						<td class='nombres_columnas'>FECHA REALIZACION</td>
						<td class='nombres_columnas'>TURNO</td>
						<td class='nombres_columnas'>HOROMETRO/ODOMETRO</td>
						<td class='nombres_columnas'>TIEMPO MATENIMIENTO</td>
						<td class='nombres_columnas'>COSTO MTTO</td>
						<td class='nombres_columnas'>FACTURA</td>
						<td class='nombres_columnas'>COMENTARIOS</td>
						<td class='nombres_columnas'>PROX. MANTENIMIENTO</td>
					</tr>
					
					<form name='frm_mostrarDetalle' method='post' action='frm_consultarBitacoras.php'>
						<input type='hidden' name='verDetalle' value='si'/>
						<input type='hidden' name='hdn_bit' value='$bit'/>";
					
			$nom_clase = "renglon_gris";
			$cont = 1;				
			do{	
				//Determinar la cantidad de la Metrica cuando la OT de la Bitacora consultada haya sido realizada
				$metrica = "No Disponible";
				if($datos['horometro']!="" && $datos['horometro']>0)
					$metrica = number_format($datos['horometro'],2,".",",")." Hrs.";
				else if($datos['odometro']!="" && $datos['odometro']>0)
					$metrica = number_format($datos['odometro'],2,".",",")." Kms.";
										
				//Determinar si la OT de la Bitacora que se estan consultadoya fue realizada
				$fecha = "No Realizada";
				if($datos['fecha_mtto']!="" && $datos['fecha_mtto']!="0000-00-00")
					$fecha = modFecha($datos['fecha_mtto'],1);
					
				//Determinar si la OT de la Bitacora que se estan consultadoya fue realizada
				$prox_mtto = "No Disponible";
				if($datos['prox_mtto']!="" && $datos['prox_mtto']!="0000-00-00")
					$prox_mtto = modFecha($datos['prox_mtto'],1);
				
				echo "	
					<tr>		
						<td class='nombres_filas'><input type='checkbox' name='RC$cont' value='$datos[equipos_id_equipo]' 	
						onClick='javascript:document.frm_mostrarDetalle.submit();'/></td>			
						<td class='$nom_clase'>$datos[equipos_id_equipo]</td>
						<td class='$nom_clase'>$datos[tipo_mtto]</td>
						<td class='$nom_clase'>$fecha</td>
						<td class='$nom_clase'>$datos[turno]</td>
						<td class='$nom_clase'>$metrica</td>
						<td class='$nom_clase'>$datos[tiempo_total]</td>
						<td class='$nom_clase'>$".number_format($datos['costo_mtto'],2,".",",")."</td>
						<td class='$nom_clase'>$datos[num_factura]</td>
						<td class='$nom_clase'>$datos[comentarios]</td>
						<td class='$nom_clase'>$prox_mtto</td>
					</tr>";											
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
						
			}while($datos=mysql_fetch_array($rs));
			echo "	
				</form>					
			</table>";
		
		}//Cierre if($datos = mysql_fetch_array($rs))
		else//Si no se encuentra ningun resultado desplegar un mensaje					
			echo $msg_error;?>			
		</div>				            
		
		
		<div id="btns-regpdf">
		<table width="82%">
			<tr>
				<td width="50%" align="center">
				  	<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar a la P&aacute;gina Consulta de Bitacora" 
                  	onMouseOver="window.estatus='';return true" 
				  	onclick="location.href='frm_consultarBitacoras.php'" />
				</td><?php 
				if($flag==1){//Declaramos nuevamente las consultas para mostrarlas en el resporte de Excel					
					//Mostrar las Activiades
					$stm_sqlAct = "SELECT DISTINCT bitacora_mtto_id_bitacora, sistema, aplicacion, descripcion FROM (actividades_correctivas JOIN bitacora_mtto 
								   ON id_bitacora=bitacora_mtto_id_bitacora) WHERE orden_trabajo_id_orden_trabajo='$idOrdenTrabajo'";
					//Mostrar los Mecanicos					
					$stm_sqlMec = "SELECT DISTINCT bitacora_mtto_id_bitacora, nom_mecanico FROM (mecanicos JOIN bitacora_mtto ON id_bitacora=bitacora_mtto_id_bitacora) 
								   WHERE orden_trabajo_id_orden_trabajo='$idOrdenTrabajo'";					
					//Obtener el ID del Vale
					$idVale = obtenerDato("bd_mantenimiento", "materiales_mtto", "id_vale", "bitacora_mtto_id_bitacora", $bit);
					//Comprobamos que la consulta Arroje Datos
					if($idVale=="")
						$idVale=0;	
					//Obtener el ID del Equipo
					$idEquipo = obtenerDato("bd_mantenimiento", "bitacora_mtto", "equipos_id_equipo", "id_bitacora", $bit);
					//Comprobamos que la consulta Arroje Datos
					if($idEquipo=="")
						$idEquipo=0;	
					//Mostrar los Materiales
					$stm_sqlMat = "SELECT materiales_id_material, cant_salida, costo_unidad, detalle_salidas.costo_total FROM detalle_salidas JOIN salidas ON salidas_id_salida=id_salida 
								   WHERE no_vale = $idVale AND id_equipo_destino = '$idEquipo'";
					//Mostrar las Gamas
					$stm_sqlGam = "SELECT DISTINCT id_bitacora,bitacora_mtto.orden_trabajo_id_orden_trabajo, gama_id_gama, nom_gama, ciclo_servicio
								   FROM (((orden_trabajo JOIN bitacora_mtto ON id_orden_trabajo=bitacora_mtto.orden_trabajo_id_orden_trabajo)
								   JOIN actividades_ot on actividades_ot.orden_trabajo_id_orden_trabajo=id_orden_trabajo) JOIN gama on gama_id_gama=id_gama)
								   WHERE bitacora_mtto.orden_trabajo_id_orden_trabajo='$idOrdenTrabajo'";
				
					//Declaramos las variables para guardar el resultado de las consultas y mostrarlo en Excel?>			
					<td width="50%" align="center">
						<form action="guardar_reporte.php" method="post">
							<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>" />
							<input name="hdn_consultaMec" type="hidden" value="<?php echo $stm_sqlMec; ?>" />
							<input name="hdn_consultaAct" type="hidden" value="<?php echo $stm_sqlAct; ?>" />
							<input name="hdn_consultaMat" type="hidden" value="<?php echo $stm_sqlMat; ?>" />
							<input name="hdn_consultaGam" type="hidden" value="<?php echo $stm_sqlGam; ?>" />
							<input name="hdn_origen" type="hidden" value="bitacoraPrev" />	
							<input name="hdn_nomReporte" type="hidden" value="Bitacora_Orden_Trabajo_<?php echo $idOrdenTrabajo;?>" />	
							<input name="hdn_msg" type="hidden" value="<?php echo $msg_titulo; ?>" />							
							<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" title="Exportar a Excel los Datos de la Consulta Realizada" 
							onMouseOver="window.estatus='';return true"  />
						</form>
					</td><?php 
				} ?>				
			</tr>
		</table>			
		</div><?php
										
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion generarconsulta	
	
	
	//Esta función se encarga de mostrar el detalle del registro seleccionado
	function mostrarDetalle(){
		?><div id="reporte2" class="borde_seccion2" align="center"><?php
		//Realizar la conexion a la BD de mantenimiento
		$conn = conecta("bd_mantenimiento");
		$idOrdenTrabajo = $_SESSION['datosConsBitacora']['cmb_OT'];
		
		$stm_sql = "SELECT id_equipo, nom_equipo, familia, turno, horometro, odometro, fecha_mtto, id_vale 
					FROM (equipos JOIN bitacora_mtto ON id_equipo=equipos_id_equipo) JOIN materiales_mtto ON id_bitacora=bitacora_mtto_id_bitacora
					WHERE orden_trabajo_id_orden_trabajo = '$idOrdenTrabajo'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){						
			echo "								
			<table cellpadding='5'>
				<caption class='titulo_etiqueta'>DETALLE DE LA BIT&Aacute;CORA <em>$_POST[hdn_bit]</em></caption>					
				<tr>
					<td class='nombres_columnas'>NO.</td>
					<td class='nombres_columnas'>ID EQUIPO</td>
					<td class='nombres_columnas'>NOMBRE EQUIPO</td>					
					<td class='nombres_columnas'>FAMILIA</td>
					<td class='nombres_columnas'>TURNO</td>
					<td class='nombres_columnas'>HOR&Oacute;METRO/OR&Oacute;METRO</td>
					<td class='nombres_columnas'>FECHA MTTO</td>
					<td class='nombres_columnas'>CLAVE VALE</td>				
				</tr>";
				
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				
				$metrica = 0;
				if($datos['horometro']!=0)
					$metrica = number_format($datos['horometro'],2,".",",")." Hrs.";
				else
					$metrica = number_format($datos['odometro'],2,".",",")." Kms.";
					
				echo "
					<tr>		
						<td class='nombres_filas'>$cont</td>
						<td class='$nom_clase'>$datos[id_equipo]</td>
						<td class='$nom_clase'>$datos[nom_equipo]</td>
						<td class='$nom_clase'>$datos[familia]</td>
						<td class='$nom_clase'>$datos[turno]</td>
					   	<td class='$nom_clase'>$metrica</td>
						<td class='$nom_clase'>".modFecha($datos['fecha_mtto'],1)."</td>
						<td class='$nom_clase'>$datos[id_vale]</td>
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
		else//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'> No se Encontraron resultados con la Orden de Trabajo <em><u>$idOrdenTrabajo</u></em></label>";?>			
		</div>
        <div id="botonesConsultas" align="center">	
        <table align="center">
        	<tr>
            	<td>
					<input name="sbt_consAct" type="submit" class="botones" value="Actividades" onMouseOver="window.estatus='';return true" 
                	title="Consulta de Actividades" />
					&nbsp;&nbsp;
					<input name="sbt_consMec" type="submit"  onclick=""class="botones" value="Mec&aacute;nicos" onMouseOver="window.estatus='';return true" 
                	title="Consulta de Mec&aacute;nicos"/>
					&nbsp;&nbsp;
					<input name="sbt_consMat" type="submit" class="botones" value="Materiales" onMouseOver="window.estatus='';return true" 
                	title="Consulta de Materiales"/>
					&nbsp;&nbsp;
					<input name="sbt_consGam" type="submit" class="botones" value="Gamas" onMouseOver="window.estatus='';return true" 
					title="Consulta de Gamas"/>
					&nbsp;&nbsp;
					<input name="sbt_consFot" type="submit" class="botones" value="Fotos" onMouseOver="window.estatus='';return true" 
					title="Consulta Registro FotoGr&aacute;fico"/>					
				</td>
            </tr>
        </table>
        </div>		            
		
		
		<div id="btn-regresar" align="center">
		<table width="100%">
			<tr>	
				<td align="center">					
					<input name="hdn_bit" type="hidden" value="<?php echo $_POST["hdn_bit"]; ?>" />
					<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar Consultar Bit&aacute;cora" 
					onMouseOver="window.status='';return true" 
					onclick="location.href='frm_consultarBitacoras.php?ot=<?php echo $idOrdenTrabajo;?>&bit=<?php echo $_POST["hdn_bit"]; ?>'"  />					
				</td>								
			</tr>
		</table>			
		</div><?php
		
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion	mostrarDetalle()				
	
	
	//Esta función se encarga de mostrar el detalle de los Mecanicos al presionar El boton referente al mismo
	function mostrarDetalleMecanico(){
		//Realizar la conexion a la BD de mantenimiento
		$conn = conecta("bd_mantenimiento");
		$idOrdenTrabajo = $_SESSION['datosConsBitacora']['cmb_OT'];
		
		$stm_sql = "SELECT DISTINCT bitacora_mtto_id_bitacora, nom_mecanico FROM (mecanicos JOIN bitacora_mtto ON id_bitacora=bitacora_mtto_id_bitacora) 
					WHERE orden_trabajo_id_orden_trabajo='$idOrdenTrabajo'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){						
			echo "								
			<table cellpadding='5' width='45%'>
				<caption class='titulo_etiqueta'> MEC&Aacute;NICOS ORDEN DE TRABAJO <em>$idOrdenTrabajo</em></caption>					
				<tr>
				
					<td class='nombres_columnas'>NO.</td>
					<td class='nombres_columnas'>CLAVE BIT&Aacute;CORA</td>
					<td class='nombres_columnas'>MEC&Aacute;NICO</td>
				</tr>";
				
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "<tr>		
						<td class='nombres_filas'>$cont</td>
						<td class='$nom_clase'>$datos[bitacora_mtto_id_bitacora]</td>
						<td class='$nom_clase' align='left'>$datos[nom_mecanico]</td>
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
		else//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'>No Existen Mec&aacute;nicos para la Orden <em><u>$idOrdenTrabajo</u></em></label>";
		
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion	 mostrarDetalleMecanico()				
	
	
	//Esta función se encarga de mostrar el detalle de las actividades 
	function mostrarDetalleActividades(){
				
		//Realizar la conexion a la BD de mantenimiento
		$conn = conecta("bd_mantenimiento");
		$idOrdenTrabajo = $_SESSION['datosConsBitacora']['cmb_OT'];
		
		$stm_sql = "SELECT DISTINCT bitacora_mtto_id_bitacora, sistema, aplicacion, descripcion FROM (actividades_correctivas JOIN bitacora_mtto 
					ON id_bitacora=bitacora_mtto_id_bitacora) WHERE orden_trabajo_id_orden_trabajo='$idOrdenTrabajo'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){						
			echo "								
			<table cellpadding='5'>
				<caption class='titulo_etiqueta'> ACTIVIDADES ORDEN DE TRABAJO <em>$idOrdenTrabajo</em></caption>					
				<tr>
				
					<td class='nombres_columnas'>NO.</td>
					<td class='nombres_columnas'>CLAVE BIT&Aacute;CORA</td>
					<td class='nombres_columnas'>SISTEMA</td>
					<td class='nombres_columnas'>APLICACI&Oacute;N</td>
					<td class='nombres_columnas'>DESCRIPCI&Oacute;N</td>
				</tr>";
				
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "<tr>		
						<td class='nombres_filas'>$cont</td>
						<td class='$nom_clase'>$datos[bitacora_mtto_id_bitacora]</td>
						<td class='$nom_clase'>$datos[sistema]</td>
						<td class='$nom_clase'>$datos[aplicacion]</td>
						<td class='$nom_clase' align='left'>$datos[descripcion]</td>
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
		else//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'>No Existen Actividades para la Orden <em><u>$idOrdenTrabajo</u></em></label>";
		
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcionmostrar DetalleActividades()				
	
	
	//Esta función se encarga de mostrar el detalle  de los materiales al presionar el boton
	function mostrarDetalleMateriales(){
		//Obtener la Id de la Orden de Trabajo de la SESSION				
		$idOrdenTrabajo = $_SESSION['datosConsBitacora']['cmb_OT'];
		
		//Obtener el ID del Vale
		$idVale = obtenerDato("bd_mantenimiento", "materiales_mtto", "id_vale", "bitacora_mtto_id_bitacora", $_POST["hdn_bit"]);
		if($idVale=="")
			$idVale=0;
		//Obtener el ID del Equipo
		$idEquipo = obtenerDato("bd_mantenimiento", "bitacora_mtto", "equipos_id_equipo", "id_bitacora", $_POST["hdn_bit"]);
		if($idEquipo=="")
			$idEquipo=0;	
		
		//Realizar la conexion a la BD de Almacén
		$conn = conecta("bd_almacen");
		
		$stm_sql = "SELECT materiales_id_material, cant_salida, costo_unidad, detalle_salidas.costo_total FROM detalle_salidas JOIN salidas ON salidas_id_salida=id_salida 
					WHERE no_vale = '$idVale' AND id_equipo_destino = '$idEquipo'";
					
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){						
			echo "								
			<table cellpadding='5'>
				<caption class='titulo_etiqueta'> MATERIALES ORDEN DE TRABAJO <em>$idOrdenTrabajo</em></caption>					
				<tr>				
					<td class='nombres_columnas'>NO.</td>
					<td class='nombres_columnas'>CLAVE BIT&Aacute;CORA</td>
					<td class='nombres_columnas'>CLAVE MATERIAL</td>
					<td class='nombres_columnas'>MATERIAL</td>
					<td class='nombres_columnas'>CANTIDAD</td>
					<td class='nombres_columnas'>PRECIO UNITARIO</td>
					<td class='nombres_columnas'>UNIDAD MEDIDA</td>
					<td class='nombres_columnas'>IMPORTE</td>
				</tr>";
				
			$nom_clase = "renglon_gris";
			$cont = 1;	
			$costo_total = 0;
			do{
				
				//Obtener el Nombre del Material y la Unidad de Media
				$nomMaterial = obtenerDato("bd_almacen", "materiales", "nom_material", "id_material", $datos["materiales_id_material"]);
				//Comprobamos que la consulta Arroje Datos
				if($nomMaterial=="")
					$nomMaterial=0;				
				$unidadMedida = obtenerDato("bd_almacen", "unidad_medida", "unidad_medida", "materiales_id_material", $datos["materiales_id_material"]);
				//Comprobamos que la consulta Arroje Datos
				if($unidadMedida=="")
					$unidadMedida=0;	
				echo "
					<tr>		
						<td class='nombres_filas'>$cont</td>
						<td class='$nom_clase'>$_POST[hdn_bit]</td>
						<td class='$nom_clase'>$datos[materiales_id_material]</td>
						<td class='$nom_clase' align='left'>$nomMaterial</td>
						<td class='$nom_clase'>$datos[cant_salida]</td>
						<td class='$nom_clase' align='center'>$".number_format($datos['costo_unidad'],2,".",",")."</td>
						<td class='$nom_clase'>$unidadMedida</td>
						<td class='$nom_clase' align='center'>$".number_format($datos['costo_total'],2,".",",")."</td>						
					</tr>";									
				
				//Sumar el costo de los materiales para obtener el total
				$costo_total += $datos['costo_total'];
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));

			echo "
				<td colspan='7'>&nbsp;</td><td class='nombres_columnas'>$".number_format($costo_total,2,".",",")."</td>
			</table>";
		}
		else//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'> No Existen Materiales para la Orden <em><u>$idOrdenTrabajo</u></em></label>";
		
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion	mostrarDetalleMateriales()	
	
	
	//Esta función se encarga de mostrar el detalle  de las gamas al presionar el boton
	function mostrarDetalleGamas(){
				
		//Realizar la conexion a la BD de mantenimiento
		$conn = conecta("bd_mantenimiento");
		$idOrdenTrabajo = $_SESSION['datosConsBitacora']['cmb_OT'];
		
		$stm_sql = "SELECT DISTINCT id_bitacora,bitacora_mtto.orden_trabajo_id_orden_trabajo, gama_id_gama, nom_gama, ciclo_servicio
					FROM (((orden_trabajo JOIN bitacora_mtto ON id_orden_trabajo=bitacora_mtto.orden_trabajo_id_orden_trabajo)
					JOIN actividades_ot on actividades_ot.orden_trabajo_id_orden_trabajo=id_orden_trabajo) JOIN gama on gama_id_gama=id_gama)
					WHERE bitacora_mtto.orden_trabajo_id_orden_trabajo='$idOrdenTrabajo'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){						
			echo "								
			<table cellpadding='5'>
				<caption class='titulo_etiqueta'> GAMAS ORDEN DE TRABAJO <em>$idOrdenTrabajo</em></caption>					
				<tr>
					<td class='nombres_columnas'>NO.</td>
					<td class='nombres_columnas'>CLAVE BIT&Aacute;CORA</td>
					<td class='nombres_columnas'>ORDEN TRABAJO</td>
					<td class='nombres_columnas'>CLAVE GAMA</td>
					<td class='nombres_columnas'>NOMBRE GAMA</td>
					<td class='nombres_columnas'>CICLO SERVICIO</td>
				</tr>";
				
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "<tr>		
						<td class='nombres_filas'>$cont</td>
						<td class='$nom_clase'>$datos[id_bitacora]</td>
						<td class='$nom_clase'>$datos[orden_trabajo_id_orden_trabajo]</td>
						<td class='$nom_clase'>$datos[gama_id_gama]</td>
						<td class='$nom_clase'>$datos[nom_gama]</td>
						<td class='$nom_clase'>$datos[ciclo_servicio]</td>
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
		else//Si no se encuentra ningun resultado desplegar un mensaje					
		echo "<label class='msje_correcto'> No Existen Gamas para la Orden <em><u>$idOrdenTrabajo</u></em></label>";?>							
								
		<?php
		
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion	mostrarDetalleGama()				

	//Funcion que permite mostrar las fotografías registradas al presionar el boton
	function mostrarRegistroFotos($clave){
		//Arcivos que se incluyen para obtener informacion de la bitácora
		include_once ("../../includes/conexion.inc");
		include_once ("../../includes/op_operacionesBD.php");
		//Verificamos que sesion esta definida para verificar de donde viene y ejecutar lo deseado
		if(isset($_SESSION["datosConsBitacora"])){
			$id_bit=$_SESSION["datosConsBitacora"]["id_bitacora"];
		}
		else{
			$id_bit=$_SESSION["datosConsBitacoraCorr"]["idBitacora"];
		}
		//Realizar la conexion con la BD
		$conn = conecta("bd_mantenimiento");
		//Ruta donde se almacenan los documentos
		$carpeta="";
		$carpeta2="documentos/".$id_bit;
		$carpeta3="documentos/".$id_bit;
		$msg;
		//Revisar si se han agregado fotos a la Base de Datos
		$stm_sql="SELECT * FROM registro_fotografico WHERE bitacora_mtto_id_bitacora='$id_bit'";
		//Ejecutar sentencia SQL
		$rs=mysql_query($stm_sql);
		//Verificar que se hayan encontrado resultados
		if ($datos=mysql_fetch_array($rs)){
			echo "				
				<table cellpadding='5' width='100%' align='center'> 
				<caption class='titulo_etiqueta'>Registro Fotogr&aacute;fico de la Bit&aacute;cora <em><u>".$id_bit."</em></u></caption></br>";
			echo "
				<tr>
					<td class='nombres_columnas' align='center'>NO</td>
					<td class='nombres_columnas' align='center'>ESTATUS</td>
					<td class='nombres_columnas' align='center'>FOTOGRAF&Iacute;A</td>
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
						<td class='$nom_clase' align='center'>$cont</td>";
					if($datos['estado']=="ANTES")
						$msg="ANTES DEL SERVICIO";
					elseif($datos['estado']=="DESPUES")
						$msg="DESPUES DEL SERVICIO";					
					echo "<td class='$nom_clase' align='center'>$msg</td>";
				
				if($datos['estado']=="ANTES")
					$carpeta=$carpeta2."/ANTES";
				elseif($datos['estado']=="DESPUES")
					$carpeta=$carpeta3."/DESPUES";
												
				/*************************************************************************/	
				//Verificar que la carpeta buscada exista
				if(is_dir($carpeta)){
					//Abrir la carpeta seleccionada
					if ($gestor = opendir($carpeta)) {
						//Recorrer la carpeta
						while (false !== ($arch = readdir($gestor))) {
							//Incrementar el contador en 1 por cada revision
							$contador++;
							//Excluir los archivos punteros o apuntadores de la busqueda y para el despliegue de informacion
							if ($arch=="$datos[nombre_archivo]") {
								//Variable incializada vacia que contendra la ruta del archivo
								$archivo="";
								//Variable con la ruta del archivo
								$archivo=$carpeta."/".$arch;
								//Verificar si el archivo es jpg, gif, jpeg, gif, png, bmp ya que de ser asi, se forza la descarga
								if (substr($arch,-4)=='.jpg'||substr($arch,-4)=='.gif'||substr($arch,-4)=='.jpeg'||substr($arch,-4)=='.png'||substr($arch,-4)=='.bmp')
									//Mostrar los documentos que corresponden con su respectivo enlace de descarga
									echo "<td class='$nom_clase' align='center'><a href='marco_descarga.php?archivo=$archivo&nom=$arch'&tipo=pdf>$arch</a></td>";
								//Si el archivo es DOC o una imagen se muestra el enlace de esta manera
								else
									echo "<td class='$nom_clase' align='center'><a href='marco_descarga.php?archivo=$archivo&nom=$arch'>$arch</a></td>";
									//Mostrar los documentos a modo de lista con un enlace
									//echo "<td class='$nom_clase' align='center'><a href=\"$carpeta/$arch\" class=\"linkli\">".$arch."</a></td>";
							}
							else{
								//Si no corresponde el archivo con el asignado a la BD, incrementar el contador en 1
								$contArchivos++;
							}
						}
						//Cerrar el directorio
						closedir($gestor);
					}
				}
				//Comprobar los contadores de archivos y revisiones de la carpeta, si son iguales, aunque exista el archivo agregado en la BD, no esta cargado al Servidor
				if ($contArchivos==$contador)
					echo "<td class='$nom_clase' align='center'>Sin Archivo</td>";
				/*************************************************************************/
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
			echo "<label class='msje_correcto' align='center'><b>No Hay Fotograf&iacute;as Cargadas al Sistema correspondientes a la Bit&aacute;cora $id_bit</b></label>";
		}
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}


	/******************************************************************************************************************
						FUNCIONES PARA CONSULTAR LA INFORMACION DE LOS MTTO'S CORRECTIVOS
	 ******************************************************************************************************************/
	/*Esta función muestra las ordenes de trabajo realizadas en la bitacora correctiva*/
	function generarConsultaCorr(){
		?><div id="reporte" class="borde_seccion2" align="center"><?php
		
		//Realizar la conexion a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
		//Variable para verificar si la consulta ejecutada arrojo resultados
		$flag = 0;
		
		if(isset($_SESSION["datosConsBitacoraCorr"])){
			//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
			$f1 = modFecha($_SESSION["datosConsBitacoraCorr"]["txt_fechaIni"],3);
			$f2 = modFecha($_SESSION["datosConsBitacoraCorr"]["txt_fechaFin"],3);
		}
		else{
			//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
			$f1 = modFecha($_POST['txt_fechaIni'],3);
			$f2 = modFecha($_POST['txt_fechaFin'],3);
		}

		//Verificamos que usuario viene definido en la session para dar acceso a las ordenes de trabajo
		if($_SESSION["depto"]=='MttoMina')
			$dpto='MINA';
		if($_SESSION["depto"]=='MttoConcreto')
			$dpto='CONCRETO';
						
		//Creamos la consulta segun el usuario registrado
		if($_SESSION["depto"]=='MttoMina'||$_SESSION["depto"]=='MttoConcreto'){
			$stm_sql = "SELECT id_bitacora, equipos_id_equipo, tipo_mtto, fecha_mtto, turno, horometro, odometro, tiempo_total, costo_mtto, num_factura, comentarios
			FROM (bitacora_mtto JOIN equipos ON equipos.id_equipo=equipos_id_equipo)
			WHERE tipo_mtto='CORRECTIVO' AND fecha_mtto>='$f1' AND fecha_mtto<='$f2' AND area='$dpto' ORDER BY id_bitacora";
		}
		else{	
			//Crear la consulta cuando el AuxMtto esta registrado
			$stm_sql = "SELECT id_bitacora, equipos_id_equipo, tipo_mtto, fecha_mtto, turno, horometro, odometro, tiempo_total, costo_mtto, num_factura, comentarios
			 			FROM bitacora_mtto WHERE tipo_mtto='CORRECTIVO' AND fecha_mtto>='$f1' AND fecha_mtto<='$f2' ORDER BY id_bitacora";
		}
		
		//Mensaje para desplegar en el titulo de la tabla
		$msg_titulo = "Bit&aacute;coras Registradas del <em><u>".modFecha($f1,2)."</em></u> al <em><u>".modFecha($f2,2)."</u></em> para Mantenimientos Correctivos";
		
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "<label class='msje_correcto' align='center'>No se Encontraron resultados en las fechas <em><u>".modFecha($f1,2)."</em></u> al <em><u>".modFecha($f2,2)."</u></em>";																								
		
		
		if(!isset($_SESSION["datosConsBitacoraCorr"])){
			//Definir datos de la consulta en la SESSION
			$datosConsBitacora = array("txt_fechaIni"=>($_POST['txt_fechaIni']),"txt_fechaFin"=>($_POST["txt_fechaFin"]));
			$_SESSION['datosConsBitacoraCorr'] = $datosConsBitacora;
		}
		else{
			$f1 = modFecha($_SESSION["datosConsBitacoraCorr"]["txt_fechaIni"],3);
			$f2 = modFecha($_SESSION["datosConsBitacoraCorr"]["txt_fechaFin"],3);	
		}

		//Ejecutar la consulta
		$rs = mysql_query($stm_sql);
		//Mostrar los resultados obtenidos
		if($datos = mysql_fetch_array($rs)){								
			//Variable para verificar si la consulta ejecutada arrojo resultados
			$flag = 1;
			echo "								
				<table cellpadding='5'>
					<caption class='titulo_etiqueta'>$msg_titulo</caption>					
					<tr>						
						<td class='nombres_columnas'>VER DETALLE</td>
						<td class='nombres_columnas'>NO. BITACORA</td>
						<td class='nombres_columnas'>CLAVE EQUIPO</td>
						<td class='nombres_columnas'>TIPO MTTO.</td>
						<td class='nombres_columnas'>FECHA REALIZACION</td>
						<td class='nombres_columnas'>TURNO</td>
						<td class='nombres_columnas'>HOROMETRO/ODOMETRO</td>
						<td class='nombres_columnas'>TIEMPO MATENIMIENTO</td>
						<td class='nombres_columnas'>COSTO MTTO</td>
						<td class='nombres_columnas'>FACTURA</td>
						<td class='nombres_columnas'>COMENTARIOS</td>
					</tr>
					
					<form name='frm_mostrarDetalle' method='post' action='frm_consultarBitacoraCorr.php'>
					<input type='hidden' name='verDetalle' value='si'/>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			$cant_total = 0;
			do{
				//Determinar la cantidad de la Metrica cuando la OT de la Bitacora consultada haya sido realizada
				$metrica = "No Disponible";
				if($datos['horometro']!="" && $datos['horometro']>0)
					$metrica = number_format($datos['horometro'],2,".",",")." Hrs.";
				else if($datos['odometro']!="" && $datos['odometro']>0)
					$metrica = number_format($datos['odometro'],2,".",",")." Kms.";
										
				//Determinar si la OT de la Bitacora que se estan consultadoya fue realizada
				$fecha = "No Realizada";
				if($datos['fecha_mtto']!="" && $datos['fecha_mtto']!="0000-00-00")
					$fecha = modFecha($datos['fecha_mtto'],1);
							
					
				echo "	
					<tr>		
						<td class='nombres_filas'><input type='checkbox' name='RC$cont' value='$datos[id_bitacora]' 	
						onClick='javascript:document.frm_mostrarDetalle.submit();'/></td>												
						<td class='$nom_clase'>$datos[id_bitacora]</td>
						<td class='$nom_clase'>$datos[equipos_id_equipo]</td>						
						<td class='$nom_clase'>$datos[tipo_mtto]</td>
						<td class='$nom_clase'>$fecha</td>
						<td class='$nom_clase'>$datos[turno]</td>
						<td class='$nom_clase'>$metrica</td>
						<td class='$nom_clase'>$datos[tiempo_total]</td>
						<td class='$nom_clase'>$".number_format($datos['costo_mtto'],2,".",",")."</td>
						<td class='$nom_clase'>$datos[num_factura]</td>
						<td class='$nom_clase'>$datos[comentarios]</td>						
					</tr>";							
				//Acumulamos el costo del mtto para mostrarla como cant_total
				$cant_total += $datos['costo_mtto'];			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
						
			}while($datos=mysql_fetch_array($rs));
			echo "	</form>
					<tr><td colspan='8'>&nbsp;</td><td class='nombres_columnas'>$".number_format($cant_total,2,".",",")."</td></tr>
			</table>";
		
		}
		else//Si no se encuentra ningun resultado desplegar un mensaje					
			echo $msg_error;?>
		</div>
		
		
		<div id="btns-regpdf">
		<table width="82%">
			<tr>
				<td width="50%" align="center">
				  	<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar a la P&aacute;gina Consulta de Bitacora" 
                  	onMouseOver="window.estatus='';return true" onclick="location.href='frm_consultarBitacoraCorr.php'" />
				</td><?php 
				if($flag==1) {
					//Declaramos nuevamente las consultas para mostrarlas en el resporte de Excel					
					
					$stm_sqlAct = "SELECT DISTINCT bitacora_mtto_id_bitacora, sistema, aplicacion, descripcion FROM (actividades_correctivas JOIN bitacora_mtto 
					ON id_bitacora=bitacora_mtto_id_bitacora)WHERE tipo_mtto='CORRECTIVO'
					AND fecha_mtto>='$f1' AND fecha_mtto<='$f2' ORDER BY fecha_mtto";
					
					$stm_sqlMat = "SELECT DISTINCT bd_mantenimiento.materiales_mtto.id_vale, bd_mantenimiento.bitacora_mtto.id_bitacora, bd_almacen.detalle_salidas.materiales_id_material,
					bd_almacen.materiales.nom_material, bd_almacen.detalle_salidas.cant_salida,
					bd_almacen.detalle_salidas.costo_unidad,bd_almacen.unidad_medida.unidad_medida,bd_almacen.detalle_salidas.costo_total  
					FROM ((((((bd_almacen.detalle_salidas JOIN bd_almacen.salidas ON id_salida=salidas_id_salida) JOIN bd_mantenimiento.materiales_mtto ON
					bd_mantenimiento.materiales_mtto.id_vale=bd_almacen.salidas.no_vale) 
					JOIN bd_mantenimiento.bitacora_mtto ON bd_mantenimiento.bitacora_mtto.id_bitacora=bd_mantenimiento.materiales_mtto.bitacora_mtto_id_bitacora AND 
					bd_mantenimiento.bitacora_mtto.equipos_id_equipo=bd_almacen.detalle_salidas.id_equipo_destino)JOIN bd_mantenimiento.equipos ON
					bd_mantenimiento.equipos.id_equipo=bd_almacen.detalle_salidas.id_equipo_destino)
					JOIN bd_almacen.materiales ON bd_almacen.materiales.id_material=bd_almacen.detalle_salidas.materiales_id_material)JOIN bd_almacen.unidad_medida ON 
					bd_almacen.unidad_medida.materiales_id_material=bd_almacen.detalle_salidas.materiales_id_material) WHERE tipo_mtto='CORRECTIVO'
					AND fecha_mtto>='$f1' AND fecha_mtto<='$f2' ORDER BY id_bitacora";
					
					$stm_sqlMec = "SELECT DISTINCT bitacora_mtto_id_bitacora, nom_mecanico FROM mecanicos JOIN bitacora_mtto ON id_bitacora=bitacora_mtto_id_bitacora
								   WHERE tipo_mtto='CORRECTIVO' AND fecha_mtto>='$f1' AND fecha_mtto<='$f2' ORDER BY bitacora_mtto_id_bitacora";
					
					$stm_sqlGam="EN LOS MANTENIMIENTOS CORRECTIVOS LAS GAMAS NO APLICAN";
				
					//Declaramos las variables para guardar el resultado de las consultas y mostrarlo en Excel ?>			
					<td width="50%" align="center">
						<form action="guardar_reporte.php" method="post">
							<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>" />
							<input name="hdn_consultaMec" type="hidden" value="<?php echo $stm_sqlMec; ?>" />
							<input name="hdn_consultaAct" type="hidden" value="<?php echo $stm_sqlAct; ?>" />
							<input name="hdn_consultaMat" type="hidden" value="<?php echo $stm_sqlMat; ?>" />
							<input name="hdn_consultaGam" type="hidden" value="<?php echo $stm_sqlGam; ?>" />
							<input name="hdn_origen" type="hidden" value="bitacoraCorr" />	
							<input name="hdn_nomReporte" type="hidden" 
							value="BitacoraCorrectiva<?php echo modFecha($f1,1)."-".modFecha($f2,1).$datos["equipos_id_equipo"]?>" />	
							<input name="hdn_msg" type="hidden" value="<?php echo $msg_titulo; ?>" />							
							<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" title="Exportar a Excel los Datos de la Consulta Realizada" 
							onMouseOver="window.estatus='';return true"  />
						</form>
					</td><?php 
				}?>				
			</tr>
		</table>					
		</div><?php
										
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion generarconsulta	
	
	
	//Esta función se encarga de mostrar el detalle del registro seleccionado
	function mostrarDetalleCorr(){
		?><div id="reporte2" class="borde_seccion2" align="center"><?php
		
		//Realizar la conexion a la BD de mantenimiento
		$conn = conecta("bd_mantenimiento");
		
		//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa y el ID de la Bitacora
		$f1 = modFecha($_SESSION["datosConsBitacoraCorr"]["txt_fechaIni"],3);
		$f2 = modFecha($_SESSION["datosConsBitacoraCorr"]["txt_fechaFin"],3);
		$id_bitacora = $_SESSION["datosConsBitacoraCorr"]["idBitacora"];
		
		//Creamos la consulta SQL		
		$stm_sql = "SELECT id_equipo, nom_equipo, familia, turno, horometro, odometro, fecha_mtto, id_vale 
					FROM (equipos JOIN bitacora_mtto ON id_equipo=equipos_id_equipo) JOIN materiales_mtto ON id_bitacora=bitacora_mtto_id_bitacora
					WHERE id_bitacora = '$id_bitacora'";					
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){		
			echo "								
			<table cellpadding='5'>
				<caption class='titulo_etiqueta'>DETALLE DE LA BIT&Aacute;CORA <em><u>$id_bitacora</em></caption>					
				<tr>
					<td class='nombres_columnas'>NO.</td>
					<td class='nombres_columnas'>ID EQUIPO</td>
					<td class='nombres_columnas'>NOMBRE EQUIPO</td>
					<td class='nombres_columnas'>CLAVE VALE</td>
					<td class='nombres_columnas'>FAMILIA</td>
					<td class='nombres_columnas'>TURNO</td>
					<td class='nombres_columnas'>HOR&Oacute;METRO/OR&Oacute;METRO</td>
					<td class='nombres_columnas'>FECHA MTTO</td>				
				</tr>";
				
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				
				$metrica = 0;
				if($datos['horometro']!=0)
					$metrica = number_format($datos['horometro'],2,".",",")." Hrs.";
				else
					$metrica = number_format($datos['odometro'],2,".",",")." Kms.";
					
				echo "
					<tr>		
						<td class='nombres_filas'>$cont</td>						
						<td class='$nom_clase'>$datos[id_equipo]</td>
						<td class='$nom_clase'>$datos[nom_equipo]</td>						
						<td class='$nom_clase'>$datos[id_vale]</td>
						<td class='$nom_clase'>$datos[familia]</td>
						<td class='$nom_clase'>$datos[turno]</td>
					   	<td class='$nom_clase'>$metrica</td> 					    					
						<td class='$nom_clase'>".modFecha($datos['fecha_mtto'],1)."</td>							
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
		else//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto' align='center'>No se Encontraron Datos Registrados para la Bit&aacute;cora <em><u>$id_bitacora</u></em></label>";?>			
		</div>
        
		
		<div id="botonesConsultas"align="center">	
        <table align="center">
        	<tr>
            	<td colspan="3">
					<input name="sbt_consActCorr" type="submit" class="botones" value="Actividades" onmouseover="window.estatus='';return true" 
                	title="Consulta de Actividades" />
					&nbsp;&nbsp;
					<input name="sbt_consMecCorr" type="submit"  onclick=""class="botones" value="Mec&aacute;nicos" onMouseOver="window.estatus='';return true" 
                	title="Consulta de Mec&aacute;nicos"/>
					&nbsp;&nbsp;
					<input name="sbt_consMatCorr" type="submit" class="botones" value="Materiales" onMouseOver="window.estatus='';return true" 
                	title="Consulta de Materiales"/>
					&nbsp;&nbsp;
					<input name="sbt_consFot" type="submit" class="botones" value="Fotos" onMouseOver="window.estatus='';return true" 
					title="Consulta Registro FotoGr&aacute;fico"/>	
				</td>
        	</tr>
        </table>
        </div>		            
		
		
		<div id="btns-regpdf" align="center">
		<table width="100%">
			<tr>	
				<td align="center">					
					<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar a la P&aacute;gina Consulta de Bitacora" 
                  	onMouseOver="window.estatus='';return true" onclick="location.href='frm_consultarBitacoraCorr.php?cancelar'" />					
				</td>
			</tr>
		</table>		
		</div><?php
		
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion	mostrarDetalle()				
	
	
	//Esta función se encarga de mostrar el detalle de los Mecanicos al presionar El boton referente al mismo
	function mostrarDetalleMecanicoCorr(){
				
		//Realizar la conexion a la BD de mantenimiento
		$conn = conecta("bd_mantenimiento");
		
		//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa y el ID de la Bitacora
		$f1 = modFecha($_SESSION["datosConsBitacoraCorr"]["txt_fechaIni"],3);
		$f2 = modFecha($_SESSION["datosConsBitacoraCorr"]["txt_fechaFin"],3);
		$id_bitacora = $_SESSION["datosConsBitacoraCorr"]["idBitacora"];

		$stm_sql = "SELECT DISTINCT bitacora_mtto_id_bitacora, nom_mecanico FROM (mecanicos JOIN bitacora_mtto ON id_bitacora=bitacora_mtto_id_bitacora) 
		 			WHERE id_bitacora = '$id_bitacora'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){						
			echo "								
			<table cellpadding='5' width='45%'>
				<caption class='titulo_etiqueta'>MEC&Aacute;NICOS REGISTRADOS EN LA BIT&Aacute;CORA</caption>					
				<tr>			
					<td class='nombres_columnas'>NO.</td>
					<td class='nombres_columnas'>CLAVE BIT&Aacute;CORA</td>
					<td class='nombres_columnas'>MEC&Aacute;NICO</td>
				</tr>";
				
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "<tr>		
						<td class='nombres_filas'>$cont</td>
						<td class='$nom_clase'>$datos[bitacora_mtto_id_bitacora]</td>
						<td class='$nom_clase' align='left'>$datos[nom_mecanico]</td>
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
		else//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto' align='center'>No Hay M&eacute;canicos Registrados en la Bit&aacute;cora <em><u>$id_bitacora</u></em></label>";
			
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion	 mostrarDetalleMecanico($clave)				
	
	
	//Esta función se encarga de mostrar el detalle de las actividades 
	function mostrarDetalleActividadesCorr(){
				
		//Realizar la conexion a la BD de mantenimiento
		$conn = conecta("bd_mantenimiento");
		
		//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa y el ID de la Bitacora
		$f1 = modFecha($_SESSION["datosConsBitacoraCorr"]["txt_fechaIni"],3);
		$f2 = modFecha($_SESSION["datosConsBitacoraCorr"]["txt_fechaFin"],3);
		$id_bitacora = $_SESSION["datosConsBitacoraCorr"]["idBitacora"];
		
		$stm_sql = "SELECT DISTINCT bitacora_mtto_id_bitacora, sistema, aplicacion, descripcion FROM (actividades_correctivas JOIN bitacora_mtto 
					ON id_bitacora=bitacora_mtto_id_bitacora) WHERE id_bitacora = '$id_bitacora'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){						
			echo "								
			<table cellpadding='5'>				
				<caption class='titulo_etiqueta'>ACTIVIDADES REGISTRADAS EN LA BIT&Aacute;CORA</caption>					
				<tr>				
					<td class='nombres_columnas'>NO.</td>
					<td class='nombres_columnas'>CLAVE BIT&Aacute;CORA</td>
					<td class='nombres_columnas'>SISTEMA</td>
					<td class='nombres_columnas'>APLICACI&Oacute;N</td>
					<td class='nombres_columnas'>DESCRIPCI&Oacute;N</td>
				</tr>";
				
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "<tr>		
						<td class='nombres_filas'>$cont</td>
						<td class='$nom_clase'>$datos[bitacora_mtto_id_bitacora]</td>
						<td class='$nom_clase'>$datos[sistema]</td>
						<td class='$nom_clase'>$datos[aplicacion]</td>
						<td class='$nom_clase' align='left'>$datos[descripcion]</td>
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
		else//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto' align='center'>No hay Actividades Registradas en la Bit&aacute;cora <em><u>$id_bitacora</u></em></label>";
			
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcionmostrar DetalleActividades($clave)				
	
	
	//Esta función se encarga de mostrar el detalle  de los materiales al presionar el boton
	function mostrarDetalleMaterialesCorr(){						
		
		//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa y el ID de la Bitacora
		$f1 = modFecha($_SESSION["datosConsBitacoraCorr"]["txt_fechaIni"],3);
		$f2 = modFecha($_SESSION["datosConsBitacoraCorr"]["txt_fechaFin"],3);
		$id_bitacora = $_SESSION["datosConsBitacoraCorr"]["idBitacora"];
		
		//Obtener el ID del Vale Asociado a la Bitacora
		$idVale = obtenerDato("bd_mantenimiento", "materiales_mtto", "id_vale", "bitacora_mtto_id_bitacora", $id_bitacora);
		if($idVale=="")
			$idVale=0;
		//Obtener el ID del Equipo Asociado a la Bitacora
		$idEquipo = obtenerDato("bd_mantenimiento", "bitacora_mtto", "equipos_id_equipo", "id_bitacora", $id_bitacora);
		if($idEquipo=="")
			$idEquipo=0;
		
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_almacen");
				
		$stm_sql = "SELECT materiales_id_material, cant_salida, costo_unidad, detalle_salidas.costo_total FROM detalle_salidas JOIN salidas ON salidas_id_salida=id_salida 
					WHERE no_vale = $idVale AND id_equipo_destino = '$idEquipo'";
		
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){						
			echo "								
			<table cellpadding='5'>
				<caption class='titulo_etiqueta'> MATERIALES REGISTRADADOS EN LA BIT&Aacute;CORA</caption>					
				<tr>				
					<td class='nombres_columnas'>NO.</td>
					<td class='nombres_columnas'>CLAVE BIT&Aacute;CORA</td>
					<td class='nombres_columnas'>CLAVE MATERIAL</td>
					<td class='nombres_columnas'>NOMBRE MATERIAL</td>
					<td class='nombres_columnas'>CANTIDAD</td>
					<td class='nombres_columnas'>PRECIO UNITARIO</td>
					<td class='nombres_columnas'>UNIDAD MEDIDA</td>
					<td class='nombres_columnas'>IMPORTE</td>
				</tr>";
				
			$nom_clase = "renglon_gris";
			$cont = 1;	
			$costo_total = 0;
			do{
			
				//Obtener el Nombre del Material
				$nomMaterial = obtenerDato("bd_almacen", "materiales", "nom_material", "id_material", $datos['materiales_id_material']);
				if($nomMaterial=="")
					$nomMaterial=0;
				//Obtener la Unidad de Media
				$unidadMedida = obtenerDato("bd_almacen", "unidad_medida", "unidad_medida", "materiales_id_material", $datos['materiales_id_material']);
				if($unidadMedida=="")
					$unidadMedida=0;
				
				echo "<tr>		
						<td class='nombres_filas'>$cont</td>
						<td class='$nom_clase'>$id_bitacora</td>
						<td class='$nom_clase'>$datos[materiales_id_material]</td>
						<td class='$nom_clase' align='left'>$nomMaterial</td>
						<td class='$nom_clase'>$datos[cant_salida]</td>
						<td class='$nom_clase' align='center'>$".number_format($datos['costo_unidad'],2,".",",")."</td>
						<td class='$nom_clase'>$unidadMedida</td>
						<td class='$nom_clase' align='center'>$".number_format($datos['costo_total'],2,".",",")."</td>						
					</tr>";									
					
				//Obtener el Costo Total de los Materiales
				$costo_total += $datos['costo_total'];
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "<tr><td colspan='7'>&nbsp;</td><td class='nombres_columnas'>$".number_format($costo_total,2,".",",")."</td></tr>
				</table>";
		}
		else//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto' align='center'>No Hay Materiales Registrados en la Bit&aacute;cora<em><u>$id_bitacora</u></em></label>";
			
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion	mostrarDetalleMateriales($clave)		
	
			
?>