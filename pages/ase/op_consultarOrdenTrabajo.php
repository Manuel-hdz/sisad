<?php
	/**
	  * Nombre del Módulo: Mantenimiento
	  * Nombre Programador: Maurilio Hernandez Correa
	  * Fecha: 26/Febrero/2011                                      			
	  * Descripción: Este archivo contiene funciones de frm_consultarOrdenTrabajo
	  **/
	
	// Funcion que se encarga de mostrar la orden de trabajo de acuerdo a un criterio de busqueda
	function mostrarOrdenTrabajo(){
		//Arreglo que permitira llevar la consulta y el mensaje al frm_consultarOrdenTrabajo para de ahi mandarlos por el boton exportar a excel a guardar_reporte	
		$arreglo = array();
	
		//Conectar a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
	
		//Traer el valor de los combos para poder realizar la consulta de acuerdo a los criterios seleccionados
		$servicio= $_POST['cmb_servicio'];
		$area= $_POST['cmb_area'];
		$familia= $_POST['cmb_familia'];
		
		//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
		$f1 = modFecha($_POST['txt_fechaInicio'],3);
		$f2 = modFecha($_POST['txt_fechaFin'],3);

		//Crear sentencia SQL
	/*	$sql_stm = "SELECT id_orden_trabajo, servicio, area, familia, id_equipo FROM (equipos JOIN bitacora_mtto ON id_equipo = equipos_id_equipo) 
		JOIN  orden_trabajo ON orden_trabajo_id_orden_trabajo=id_orden_trabajo WHERE servicio='$servicio' AND area='$area' AND familia='$familia' 
		AND fecha_creacion>='$f1' AND fecha_creacion<='$f2' ORDER BY id_orden_trabajo";	
		*/
		
		$sql_stm ="SELECT id_orden_trabajo,equipos_id_equipo,equipos.estado AS edo,servicio,fecha_creacion,fecha_prog,orden_trabajo.turno,orden_trabajo.horometro,
	   orden_trabajo.odometro,operador_equipo,orden_trabajo.comentarios,orden_trabajo.estado,autorizo_ot
	   FROM (orden_trabajo JOIN bitacora_mtto ON id_orden_trabajo = orden_trabajo_id_orden_trabajo) JOIN equipos ON id_equipo=equipos_id_equipo 
	   WHERE servicio='$servicio' AND area='$area' AND familia='$familia' AND fecha_creacion>='$f1' AND fecha_creacion<='$f2' ORDER BY id_orden_trabajo";	
		
		//Crear sentencia SQL
		//Crear el mensaje que se mostrara en el titulo de la tabla
		$msg= "Orden de Trabajo de la Familia <em><u>$_POST[cmb_familia]</u></em> En el Periodo del <em><u>$_POST[txt_fechaInicio]</u></em> al <em><u>
		$_POST[txt_fechaFin]</u></em>";
		
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ningun Resultado de Servicio: <em><u>$_POST[cmb_servicio]</u></em>	
		&Aacute;rea: <em><u>$_POST[cmb_area]</u></em> Familia: <em><u>$_POST[cmb_familia]</u></em> 
		En las Fechas del <em><u>$_POST[txt_fechaInicio]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em></label>";										

		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
	
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='7' width='1500'>      			
				<tr>
					<td colspan='18' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas'>DETALLE</td>
						<td class='nombres_columnas'>ID ORDEN TRABAJO</td>
						<td class='nombres_columnas'>CLAVE EQUIPO</td>
						<td class='nombres_columnas' width='70'>ESTADO</td>
						<td class='nombres_columnas' width='80'>SERVICIO</td>
						<td class='nombres_columnas'>FECHA CREACION</td>						
						<td class='nombres_columnas'>FECHA PROG.</td>						
						<td class='nombres_columnas' width='150'>TURNO</td>						
						<td class='nombres_columnas'>HOROMETRO/ODOMETRO</td>
						<td class='nombres_columnas' width='90'>OPERADOR</td>						
						<td class='nombres_columnas'>COMENTARIOS</td>	
						<td class='nombres_columnas' width='90'>ESTADO</td>						
						<td class='nombres_columnas'>AUTORIZACION</td>											
				</tr>				
				<form name='frm_mostrarDetalleOT' method='post' action='frm_consultarOrdenTrabajo.php'>
				<input type='hidden' name='verDetalle' value='si' />";

			$nom_clase = "renglon_gris";
			$cont = 1;			
			do{	

				//Obtener el Valor de la Metrica
				$metrica = 0;
				if($datos['horometro']!=0)
					$metrica = number_format($datos['horometro'],0,".",",")." Hrs.";
				else if($datos['odometro']!=0)
					$metrica = number_format($datos['odometro'],0,".",",")." Kms.";
				
				//Determinar el Estado de la Requisicion
				$estado = "";
				if($datos['estado']==0)
					$estado = "PROGRAMADA";
				else
					$estado = "EJECUTADA";
		
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='nombres_filas'>
							<input type='checkbox' name='ckb' value='$datos[id_orden_trabajo]' 
							onClick='javascript:document.frm_mostrarDetalleOT.submit();'/></td>
						<input type='hidden' name='verDetalle' value='si' />
						<td class='$nom_clase' align='center'>$datos[id_orden_trabajo]</td>
						<td class='$nom_clase' align='center'>$datos[equipos_id_equipo]</td>
						<td class='$nom_clase' align='center'>$datos[edo]</td>
						<td class='$nom_clase' align='center'>$datos[servicio]</td>
						<td class='$nom_clase' align='center'>".modFecha($datos['fecha_creacion'],1)."</td>
						<td class='$nom_clase' align='center'>".modFecha($datos['fecha_prog'],1)."</td>
						<td class='$nom_clase' align='center'>$datos[turno]</td>
						<td class='$nom_clase' align='center'>$metrica</td>
						<td class='$nom_clase' align='center'>$datos[operador_equipo]</td>
						<td class='$nom_clase' align='center'>$datos[comentarios]</td>
						<td class='$nom_clase' align='center'>$estado</td>
						<td class='$nom_clase' align='center'>$datos[autorizo_ot]</td>
					</tr>";
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "</form>	</table>";
			
			$arreglo[]=$sql_stm;
			$arreglo[]=$msg;
			
			return $arreglo;			
		}// fin  if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;					
			return $arreglo;
		}
	}//	 fin function mostrarOrdenTrabajo()
	
	
	function cargarComboOT($nom_combo, $msj, $valSeleccionado){
		//Conectarse con la BD indicada
		$conn = conecta("bd_mantenimiento");
		$dpto="";
		$nom_campo="";
		//Creamos la variable $dpto para verificar el usuario registrado
		if($_SESSION["depto"]=='MttoMina')
			$dpto='MINA';
		if($_SESSION["depto"]=='MttoConcreto')
			$dpto='CONCRETO';
		//Creamo la consulta segun el usuario que corresponda
		if($_SESSION["depto"]=='MttoMina'||$_SESSION["depto"]=='MttoConcreto'){
			$stm_sql = "SELECT id_orden_trabajo FROM ((orden_trabajo JOIN bitacora_mtto ON orden_trabajo_id_orden_trabajo=id_orden_trabajo) 
			JOIN equipos ON bitacora_mtto.equipos_id_equipo=equipos.id_equipo)WHERE orden_trabajo.estado='0' AND equipos.area='$dpto'  ORDER BY id_orden_trabajo";
		}
		else{
			$stm_sql = "SELECT id_orden_trabajo FROM orden_trabajo WHERE estado='0' ORDER BY id_orden_trabajo";
		}
		
		$rs = mysql_query($stm_sql);
		//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
		if($datos = mysql_fetch_array($rs)){			
			//Declarar el ComboBox con el nombre especificado en el parametro $nom_combo
			echo "<select name='$nom_combo' id='$nom_combo' class='combo_box'>";
			//Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
			echo "<option value=''>$msj</option>";
			do{
				if($datos['id_orden_trabajo']==$valSeleccionado){//Colocar el valor preseleccionado
					if(substr($nom_campo,0,5)=="fecha")//Desplegar la Fecha en formato dd/mm/aaaa 24/12/2000
						echo "<option value='$datos[id_orden_trabajo]'selected='selected'>".modFecha($datos[$nom_campo],1)."</option>";					
					else
						echo "<option value='$datos[id_orden_trabajo]'selected='selected'>$datos[id_orden_trabajo]</option>";					
				}
				else{
					if(substr($nom_campo,0,5)=="fecha")//Desplegar la Fecha en formato dd/mm/aaaa 24/12/2000
						echo "<option value='$datos[id_orden_trabajo]'>".modFecha($datos[$nom_campo],1)."</option>";
					else
						echo "<option value='$datos[id_orden_trabajo]'>$datos[id_orden_trabajo]</option>";
				}
			}while($datos = mysql_fetch_array($rs));
			echo "</select>";	
			//Regresar 1, si la sentencia si regresa valores
			return 1;		
		}
		else{
			//Regresar 0, si la sentencia no regresa ningun valor
			return 0;
		}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);	
	}//Fin de la funcion cargarCombo($nom_combo,$nom_campo,$nom_tabla,$msj,$valSelecionado)
	
	//Funcion que permite mostrar el detalle de la orden de trabajo seleccionada en un checkbox
	function mostrarDetalleOT($ckb){
		?><div id="detalleOT" class="borde_seccion2" align="center"><?php 
		//Realizar la conexion a la BD de Mantenimeinto
		$conn = conecta("bd_mantenimiento");
		
		//Realizar la consulta para obtener las gamas utilizadas en la OT
		$stm_sql = "SELECT DISTINCT gama_id_gama FROM actividades_ot WHERE orden_trabajo_id_orden_trabajo = '$ckb' ";
		$gamas[]= array();
		//Ejecutar la consulta y dibujar la tabla para mostrar el detalle
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			echo "					
			<label class='titulo_etiqueta'>DETALLE DE LA ORDEN DE TRABAJO <em><u>$ckb</u></em></label>								
			<br><br>								
			<table cellpadding='5'>
				<caption class='titulo_etiqueta'>GAMAS REALIZADAS</caption>
				<tr>
					<td class='nombres_columnas'>GAMA</td>
					<td class='nombres_columnas'>NOMBRE</td>						
					<td class='nombres_columnas'>DESCRIPCION</td>
					<td class='nombres_columnas'>AREA</td>
					<td class='nombres_columnas'>FAMILIA</td>
					<td class='nombres_columnas'>CICLO DE SERVICIO</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				$datos_gama = mysql_fetch_array(mysql_query("SELECT * FROM gama WHERE id_gama = '$datos[gama_id_gama]'"));
				echo "<tr>		
						<td class='nombres_filas'>$datos[gama_id_gama]</td>	
						<td class='$nom_clase'>$datos_gama[nom_gama]</td>	
						<td class='$nom_clase'>$datos_gama[descripcion]</td>	
						<td class='$nom_clase'>$datos_gama[area_aplicacion]</td>	
						<td class='$nom_clase'>$datos_gama[familia_aplicacion]</td>	
						<td class='$nom_clase'>".number_format($datos_gama['ciclo_servicio'],0,".",",")."</td>							
					</tr>";
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "</table><br><br><br>";
		}
		
		//Cerrar la conexion con la BD
		mysql_close($conn);
		?></div> 
		
		 <?php // recuperar los valores de la consulta para que el boton que nos permite regresar a la consulta de OT,?>
		<div id="btn-detalleOT">
			<table align="center">
				<tr>
					<td>
					<form name="" action="frm_consultarOrdenTrabajo.php" method="post">
						<input name="sbt_consultar" type="hidden" value="" />
						<input name="cmb_servicio" type="hidden" value="<?php echo $_SESSION['consultaOT']['servicio']; ?>" />
						<input name="cmb_area" type="hidden" value="<?php echo $_SESSION['consultaOT']['area']; ?>" />
						<input name="cmb_familia" type="hidden" value="<?php echo $_SESSION['consultaOT']['familia']; ?>" />
						<input name="txt_fechaInicio" type="hidden" value="<?php echo $_SESSION['consultaOT']['fecha_ini']; ?>" />
						<input name="txt_fechaFin" type="hidden" value="<?php echo $_SESSION['consultaOT']['fecha_fin']; ?>" />
						<input name="sbt_regresar" type="submit" class="botones" value="Regresar" title="Regresar a la Consulta de las Ordenes de Trabajo" 
						onmouseover="window.estatus='';return true" id="sbt_regresar" />
					</form>
					</td>
					<td>
						<input name="btn_verPDF" type="button" class="botones" value="Ver PDF" title="Ver Archivo PDF de la Requisición Seleccionada" 
						onmouseover="window.status='';return true" 
						onclick="window.open('../../includes/generadorPDF/ordenTrabajoMtto.php? id=<?php echo $ckb;  ?>','_blank','top=100, left=100, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')",4000);  />
					</td>
				</tr>
			</table>
		</div>
	 <?php	
	}
?>
    

