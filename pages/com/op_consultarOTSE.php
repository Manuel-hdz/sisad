<?php
	/**
	  * Nombre del Módulo: Compras
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 18/Abril/2012
	  * Descripción: Este archivo contiene funciones para Consultar y Complementar las Ordenes de Trabajo para Servicios Externos
	  **/
	
	
	/*************************************************************************************************************************************************/
	/**************************************** CONSULTAR ORDENES DE TRABAJO PARA SERVICIOS EXTERNOS ***************************************************/
	/*************************************************************************************************************************************************/	
	
	
	//Esta función muestra las Ordenes de Trabajo para Servivios Externos de acuerdo a los parámetros seleccionados por el usuario
	function mostrarOrdenesServiciosExternos(){
		
		?> <div class="borde_seccion" id="tabla-ordenesConsultadas" align="center"> <?php
		
		//Conectar a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
	
		//Recuperar parámetros de busqueda del POST		
		$area = $_POST['cmb_area'];
		$estadoOrden = $_POST['cmb_estado'];
		
		//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
		$fechaIni = modFecha($_POST['txt_fechaIni'],3);
		$fechaFin = modFecha($_POST['txt_fechaFin'],3);	
		
		//Titulo del reporte
		$msg_titulo = "Reporte de OTSE Correspondiente al Periodo del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
		
		//Crear la Sentencia SQL
		$sql_stm_OTSE = "SELECT * FROM orden_servicios_externos";
		
		//Armar mensaje para mostrar
		$msgTabla = "Ordenes de Trabajo para Servicios Externos";
		
		//Agregar los parametros de Busqueda cuando solo sea proporcionada el Área
		if($area!="" && $estadoOrden=="" && !isset($_POST['ckb_incluirFechas'])){
			$sql_stm_OTSE .= " WHERE depto = '$area'";
			$msgTabla .= " del &Aacute;rea de <u><em>$area</em></u>";
		}		
		//Agregar los parametros de Busqueda cuando solo sea proporcionado el Estado de las OTSE's
		if($area=="" && $estadoOrden!="" && !isset($_POST['ckb_incluirFechas'])){
			$sql_stm_OTSE .= " WHERE complementada = '$estadoOrden'";
			//Definir el Estado de las Ordenes		
			$estado = "COMPLEMENTADAS";
			if($estadoOrden=="NO")
				$estado = "NO COMPLEMENTADAS";	
			$msgTabla .= " <u><em>$estado</em></u>";
		}
		//Agregar los parametros de Busqueda cuando solo sean proporcionadas las Fechas
		if($area=="" && $estadoOrden=="" && isset($_POST['ckb_incluirFechas'])){
			$sql_stm_OTSE .= " WHERE fecha_creacion BETWEEN '$fechaIni' AND '$fechaFin'";
			$msgTabla .= " en las Fechas del <u><em>$_POST[txt_fechaIni]</em></u> al <u><em>$_POST[txt_fechaFin]</em></u>";
		}
		//Agregar los parametros de Busqueda cuando sean proporcionados todos los datos (Area, Estado y Fechas)
		if($area!="" && $estadoOrden!="" && isset($_POST['ckb_incluirFechas'])){
			$sql_stm_OTSE .= " WHERE depto = '$area' AND complementada = '$estadoOrden' AND fecha_creacion BETWEEN '$fechaIni' AND '$fechaFin'";			
			//Definir el Estado de las Ordenes		
			$estado = "COMPLEMENTADAS";
			if($estadoOrden=="NO")
				$estado = "NO COMPLEMENTADAS";	
			$msgTabla .= "  <u><em>$estado</em></u> del &Aacute;rea <u><em>$area</em></u> en las Fechas del <u><em>$_POST[txt_fechaIni]</em></u> al 
							<u><em>$_POST[txt_fechaFin]</em></u>";
		}		
		//Agregar los parametros de Busqueda cuando sean proporcionados el Área y el Estado
		if($area!="" && $estadoOrden!="" && !isset($_POST['ckb_incluirFechas'])){		
			$sql_stm_OTSE .= " WHERE depto = '$area' AND complementada = '$estadoOrden'";
			//Definir el Estado de las Ordenes		
			$estado = "COMPLEMENTADAS";
			if($estadoOrden=="NO")
				$estado = "NO COMPLEMENTADAS";
			$msgTabla .= "  <u><em>$estado</em></u> del &Aacute;rea <u><em>$area</em></u>";
		}
		//Agregar los parametros de Busqueda cuando sean proporcionados el Área y las Fechas
		if($area!="" && $estadoOrden=="" && isset($_POST['ckb_incluirFechas'])){
			$sql_stm_OTSE .= " WHERE depto = '$area' AND fecha_creacion BETWEEN '$fechaIni' AND '$fechaFin'";
			$msgTabla .= "  del &Aacute;rea <u><em>$area</em></u> en las Fechas del <u><em>$_POST[txt_fechaIni]</em></u> al 
							<u><em>$_POST[txt_fechaFin]</em></u>";							
		}
		//Agregar los parametros de Busqueda cuando sean proporcionados El Estado y las Fechas
		if($area=="" && $estadoOrden!="" && isset($_POST['ckb_incluirFechas'])){
			$sql_stm_OTSE .= " WHERE complementada = '$estadoOrden' AND fecha_creacion BETWEEN '$fechaIni' AND '$fechaFin'";
			//Definir el Estado de las Ordenes		
			$estado = "COMPLEMENTADAS";
			if($estadoOrden=="NO")
				$estado = "NO COMPLEMENTADAS";
			$msgTabla .= "  <u><em>$estado</em></u> en las Fechas del <u><em>$_POST[txt_fechaIni]</em></u> al <u><em>$_POST[txt_fechaFin]</em></u>";
		}
		
		//Complemnetar la sentencia con el parametro de Ordenación
		$sql_stm_OTSE .= " ORDER BY depto";
		
		
		//Ejecutar la sentencia previamente creada
		$rs_OTSE = mysql_query($sql_stm_OTSE);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos_OTSE=mysql_fetch_array($rs_OTSE)){
	
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='200%'>
				<tr>
					<td colspan='13' align='center' class='titulo_etiqueta'>$msgTabla</td>
				</tr>
				<tr>
					<td class='nombres_columnas'>PDF</td>
					<td class='nombres_columnas' colspan='2'>COMPLEMENTAR</td>
					<td class='nombres_columnas'>ID ORDEN</td>
					<td class='nombres_columnas'>FECHA REGISTRO</td>
					<td class='nombres_columnas'>PROVEEDOR</td>
					<td class='nombres_columnas'>DIRECCI&Oacute;N</td>
					<td class='nombres_columnas'>FECHA ENTREGA</td>
					<td class='nombres_columnas'>FECHA RECEPCI&Oacute;N</td>
					<td class='nombres_columnas'>REPRESENTANTE PROVEEDOR</td>
					<td class='nombres_columnas'>ENCARGADO COMPRASAS</td>
					<td class='nombres_columnas'>SOLICIT&Oacute;</td>
					<td class='nombres_columnas'>AUTORIZ&Oacute;</td>
					<td class='nombres_columnas'>IVA</td>
					<td class='nombres_columnas'>COSTO TOTAL</td>
					<td class='nombres_columnas'>MONEDA</td>
					<td class='nombres_columnas'>FACTURA</td>
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;			
			do{											
				
				//Verificar si esta incluido el Costo, en el caso de que no lo tenga, colocamos la Leyenda N/R
				$costo = "N/R";
				$iva = "N/R";
				if($datos_OTSE['costo_total']!=0){
					$costo = "$".number_format($datos_OTSE['costo_total'],2,".",",");
					$iva = $datos_OTSE['costo_total'] - ($datos_OTSE['costo_total'] / (1 + ($_SESSION['porcentajeIVA']/100) ) ) ;
					$iva = "$".number_format($iva,2,".",",");
				}
				
				//Verificar si esta registrada el No. de Factura
				$factura = "N/R";
				if($datos_OTSE['factura']!="")
					$factura = $datos_OTSE['factura'];
					
				//Mostrar todos los registros que han sido completados
				echo "
				<tr>
					<td class='$nom_clase'>";?>
						<input type="button" name="btn_verPDF" id="btn_verPDF" class="botones" title="Ver Formato PDF de la Orden" value="Ver PDF"
						onclick="abrirPdfOtse(<?php echo "'".$datos_OTSE['id_orden']."','".$datos_OTSE['id_orden']."','".modFecha($datos_OTSE['fecha_creacion'],1)."'"; ?>)" /><?php
				echo "						
					</td>
					<td class='$nom_clase'>";?>
						<input type="button" name="btn_actividadesOTSE" id="btn_actividadesOTSE" class="botones" value="Actividades"
						onclick="actividadesOTSE('<?php echo $datos_OTSE['id_orden']; ?>');"<?php 
						if($datos_OTSE['complementada']=="SI"){ echo "disabled='disabled' title='Orden Complementada'"; } else { echo "title='Complementar Orden'"; } ?> /><?php
				echo "
					</td>
					<td class='$nom_clase'>";?>
						<input type="button" name="btn_materialesOTSE" id="btn_materialesOTSE" class="botones" value="Materiales" title="Registrar Materiales"
						onclick="materialesOTSE('<?php echo $datos_OTSE['id_orden']; ?>');" /><?php
				echo "
					</td>
					<td class='$nom_clase'>$datos_OTSE[id_orden]</td>
					<td class='$nom_clase'>".modFecha($datos_OTSE['fecha_creacion'],1)."</td>
					<td class='$nom_clase' align='left'>$datos_OTSE[nom_proveedor]</td>
					<td class='$nom_clase' align='left'>$datos_OTSE[direccion]</td>
					<td class='$nom_clase'>".modFecha($datos_OTSE['fecha_entrega'],1)."</td>
					<td class='$nom_clase'>".modFecha($datos_OTSE['fecha_recepcion'],1)."</td>
					<td class='$nom_clase'>$datos_OTSE[rep_proveedor]</td>
					<td class='$nom_clase'>$datos_OTSE[encargado_compras]</td>
					<td class='$nom_clase'>$datos_OTSE[solicito]</td>
					<td class='$nom_clase'>$datos_OTSE[autorizo]</td>
					<td class='$nom_clase'>$iva</td>
					<td class='$nom_clase'>$costo</td>
					<td class='$nom_clase'>$datos_OTSE[moneda]</td>
					<td class='$nom_clase'>$factura</td>
				</tr>";
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos_OTSE=mysql_fetch_array($rs_OTSE));
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "
				</form>	
			</table>";
			?>
			</div>
			
			<div id="btns-regpdf">
			<table width="100%">
				<tr>						
					<td align="center">
						<form action="guardar_reporte.php" method="post">
							<input name="hdn_consulta" type="hidden" value="<?php echo $sql_stm_OTSE; ?>" />
							<input name="hdn_nomReporte" type="hidden" value="Ordenes de Trabajo para Servicios Externos de Mantenimiento"/>
							<input name="hdn_origen" type="hidden" value="OTSE" />		
							<input name="hdn_msg" type="hidden" value="<?php echo $msgTabla; ?>" />							
							<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" title="Exportar a Excel los Datos de la Consulta Realizada" 
							onMouseOver="window.estatus='';return true"  />
						</form>
					</td>
				</tr>
			</table>			
			</div>
		<?php
		}// fin  if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo "<label class='msje_correcto'>NO se Encontraron Ordenes de Trabajo para Servicios Externos</label>";
			?> </div> <?php
		}
	}//Cierre de la función mostrarOrdenesServiciosExternos()
	
	
	/*************************************************************************************************************************************************/
	/************************************************** CONSULTAR Y REGISTRAR COSTO DE ACTIVIDADES ***************************************************/
	/*************************************************************************************************************************************************/
	
	//Esta función Mostrará las Actividades registradas en la OTSE seleccionada para registrar los costos en la ventana de verComplementarCosotosOTSE.php
	function mostrarActividades($idOrden){
		//Realizar la conexion a la BD de Mantenimeinto
		$conn = conecta("bd_mantenimiento");							
		
		//*************************************************** DESPLEGAR LAS ACTIVIDADES REGISTRADAS **************************************************
		//Crear la Sentencia SQL para obtener las actividades registradas en la orden
		$stm_sql = "SELECT * FROM actividades_realizadas WHERE orden_servicios_externos_id_orden = '$idOrden'";		
										
		//Ejecutar la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		if($datos=mysql_fetch_array($rs)){
			echo "					
			<table width='100%' cellpadding='5'>				
				<caption class='titulo_etiqueta'>ACTIVIDADES A EFECTUAR EN LA ORDEN <em><u>$idOrden</u></em></caption>
				<tr>
					<td class='nombres_columnas'>&nbsp;</td>
					<td class='nombres_columnas'>NO.</td>
					<td class='nombres_columnas'>SISTEMA</td>
					<td class='nombres_columnas'>APLICACI&Oacute;N</td>						
					<td class='nombres_columnas'>DESCRIPCI&Oacute;N</td>
					<td class='nombres_columnas'>FAMILIA</td>
					<td class='nombres_columnas'>EQUIPO</td>
					<td class='nombres_columnas'>COSTO TOTAL</td>
				</tr>";
				
			//Manejar el estilo de los renglones de la tabla creada
			$nom_clase = "renglon_gris";
			$cont = 1;
			$contReg = 0;	
			do{				
									
				echo "<tr>		
						<td class='nombres_filas'>";
						if($datos['estado']==1){//Colocar un espacio vacio en el caso que el registro este complementado
							echo "&nbsp;";							
						}
						else{//Colocar un CheckBox para que el usuario seleccione el registro que quiere Complementar							 
							$contReg++;//Aumentar en 1 el contador de CheckBox para saber cuantos fueron creados en la página para realizar la validacion con Javascript?>							
							<input type="checkbox" name="ckb_reg<?php echo $contReg; ?>" id="ckb_reg<?php echo $contReg; ?>" 
							onclick="activarCajaCosto(this,'txt_costoAct<?php echo $contReg; ?>')" value="<?php echo $datos['no_actividad']; ?>" /><?php
						}
				echo "	</td>
						<td class='nombres_filas'>$cont</td>
						<td class='$nom_clase'>$datos[sistema]</td>
						<td class='$nom_clase'>$datos[aplicacion]</td>
						<td class='$nom_clase' align='left'>$datos[descripcion]</td>
						<td class='$nom_clase'>$datos[familia]</td>
						<td class='$nom_clase'>$datos[equipo]</td>
						<td class='$nom_clase'>";
						if($datos['estado']==1){//Colocar el costo como texto en la celda cuando el registro este complementado
							echo number_format($datos['costo_actividad'],2,".",","); 
						}
						else{//Colocar la Caja de Texto cuando el registro no este complementado?>
							<input type="text" name="txt_costoAct<?php echo $contReg; ?>" id="txt_costoAct<?php echo $contReg; ?>" onkeypress="return permite(event,'num', 2);"
							class="caja_de_num" onchange="formatCurrency(value,'txt_costoAct<?php echo $contReg; ?>');" size="10" maxlength="15" readonly="readonly"
							autocomplete="off" value="<?php echo number_format($datos['costo_actividad'],2,".",","); ?>" /><?php
						}
				echo "	</td>
					</tr>";
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "</table><br>";?>
				<input type="hidden" name="hdn_cantRegistros" id="hdn_cantRegistros" value="<?php echo $contReg; ?>" /><?php
				
		}//Cierre if($datos=mysql_fetch_array($rs))
		else{
			echo "<label class='msje_correcto' align='center'>NO hay Actividades Registradas en la Orden <em><u>$idOrden</u></em></label>";
		}	
				
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre mostrarActividades($idOrden)
	
	
	//Esta función guarda los costos de las actividades seleccionadas de la OTSE seleccionada
	function guardarCostoActividades(){
		
		//Realizar la conexion a la BD de Mantenimeinto
		$conn = conecta("bd_mantenimiento");	
		
		//Obtener los datos de los Vectores POST y GET
		$idOrden = $_GET['idOrden'];
		$cantRegistros = $_POST['hdn_cantRegistros'];
		$factura = strtoupper($_POST['txt_factura']);
		$proveedor = strtoupper($_POST['txt_proveedor']);
		$direccionProv = strtoupper($_POST['txt_direccion']);
		$representante = strtoupper($_POST['txt_repProveedor']);
		$usr_compras = strtoupper($_POST['txt_repCompras']);
		$moneda = strtoupper($_POST['txt_moneda']);
		$cc = $_POST['cmb_con_cos'];
		$cuenta = $_POST['cmb_cuenta'];
		$subcuenta = $_POST['cmb_subcuenta'];
		
		//Esta variable ayudará a saber si hubo errores en la Actualización de Datos
		$sts_upd = 0;
		
		//Esta variable guardará la cantidad total de las actividades complementas
		$cantTotal = 0;
		
		//Crear un ciclo para buscar cuales de los registros fueron complementados
		for($i=1;$i<=$cantRegistros;$i++){
			//Verificar registro por registro, para ver cual esta definido y cual no, los que esten definidos serán guardados en la BD
			if(isset($_POST['ckb_reg'.$i])){
				//Obtener el No. de la Actividad que viene asociada al CheckBox seleccionado
				$noAct = $_POST['ckb_reg'.$i];
				//Obtener la cantidad de la caja de texto del registro seleccionado y retirar las posibles comas que tenga
				$cantidad = str_replace(",","",$_POST['txt_costoAct'.$i]);
				//Crear la Sentencia SQL para Actualizar el Costo y el Estado de la actividad complementada
				$sql_stm = "UPDATE actividades_realizadas SET costo_actividad = $cantidad, estado = '1', moneda='$moneda' 
							WHERE orden_servicios_externos_id_orden = '$idOrden' AND no_actividad = $noAct";
				//Ejecutar la Setencia SQL
				$rs = mysql_query($sql_stm);
				
				//Sumar la cantidad de cada actividad complementada
				$cantTotal += $cantidad;
				
				//Verificar Resultados
				if(!$rs){
					$sts_upd = 1;
					break;//Romper el Ciclo en el caso de que haya errores de inserción
				}
			}//Cierre if(isset($_POST['ckb_reg'.$i]))						
		}//Cierre for($i=1;$i<$cantRegistros;$i++)
		
		
		//Si no hubo errores procedemos a verificar si todos los registros de la OTSE estan complementados para actualizar la variable 'complementada' en la tabla de las OTSE
		if($sts_upd==0){
			//Crear Sentencia SQL para verificar si todos los registros estan complementados
			$sql_stm = "SELECT orden_servicios_externos_id_orden, estado FROM actividades_realizadas WHERE orden_servicios_externos_id_orden = '$idOrden' AND estado = 0";		
			//Ejecutar la Sentencia SQL
			$rs = mysql_query($sql_stm);			
			//Si la cantidad de renglones es 0, significa que todos los registros estan complementados
			if(mysql_num_rows($rs)==0){
				$fecha = date("Y-m-d");
				mysql_query("UPDATE orden_servicios_externos SET complementada = 'SI', fecha_entrega = '$fecha' WHERE id_orden = '$idOrden'");
			}
			
			//Actualizar la Cantidad y el No de Factura, ya que al menos 1 registro fue modificado y la factura puede haber sido cambiada
			mysql_query("UPDATE orden_servicios_externos SET factura = '$factura', costo_total = costo_total + $cantTotal, 
						id_control_costos='$cc', id_cuentas='$cuenta', id_subcuentas='$subcuenta', nom_proveedor='$proveedor', 
						direccion='$direccionProv', rep_proveedor='$representante', encargado_compras='$usr_compras', moneda='$moneda' WHERE id_orden = '$idOrden'");
			
			//Registrar la operacion realizada
			session_start();//Iniciar la SESSION para accesar a los datos registardos en ella
			registrarOperacion("bd_compras",$idOrden,"ComplementarOTSE",$_SESSION['usr_reg']);
			
			//Retornar 1 para indicar que los datos fueron actualizados con exito
			return 1;		
		}
		else{
			//Cerrar la conexion con la BD
			mysql_close($conn);
			
			//Retornar 0 para indicar que los datos NO fueron actualizados con exito			
			return 0;						
		}
								
	}//Cierre de la función guardarCostoActividades()
	
	
	/*************************************************************************************************************************************************/
	/************************************************************* REGISTRAR MATERIALES **************************************************************/
	/*************************************************************************************************************************************************/
	
	//Est funcion guarda los materiales desde la pantalla de Complementar materialess
	function registrarMaterial(){
		//Conectarse con la Base de Datos
		$conn = conecta("bd_mantenimiento");
		
		//Recuperar Datos del POST y del GET
		$idOrden = $_GET['idOrden'];
		$descripcion = strtoupper($_POST['txa_material']);
		$cant = $_POST['txt_cantidad'];
		
		//Crear la Sentencia SQL para almacenar los datos
		$sql_stm = "INSERT INTO materiales_usados (orden_servicios_externos_id_orden, descripcion, cantidad) VALUES('$idOrden','$descripcion',$cant)";
		
		//Ejecutar la Consulta
		$rs = mysql_query($sql_stm);
		
		$error = "";
		if(!$rs)
			$error = "Error al Tratar de Insertar los Datos del Material";
		
		//Cerrar la conexion con la BD
		mysql_close($conn);
		
		return $error;
	}//Cierre de la función registrarMaterial()
	
	
	/*Esta funcion muestra los Materiales agregados a la Orden de Trabajo para Servicios Externos en la Pagina de Complementar Materiales*/
	function mostrarMaterialesRegistrados(){
		//Conectarse con la Base de Datos
		$conn = conecta("bd_mantenimiento");
		
		//Crear la Sentencia SQL para obtener los materiales registrados en la OTSE
		$sql_stm = "SELECT * FROM materiales_usados WHERE orden_servicios_externos_id_orden = '$_GET[idOrden]'";
		
		//Ejecutar la Sentencia SQL
		$rs = mysql_query($sql_stm);
		
		if($datos=mysql_fetch_array($rs)){
	
			echo "<table width='100%' class='tabla_frm' cellpadding='5'>";
			echo "<caption class='msje_correcto'><strong>Materiales Agregados a la Orden ".$_GET['idOrden']."</strong></caption>";
			echo "
				<tr>
					<td width='20%' class='nombres_columnas' align='center'>PARTIDA</td>
					<td width='60%' class='nombres_columnas' align='center'>MATERIAL</td>
					<td width='20%' class='nombres_columnas' align='center'>CANTIDAD</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{
				echo "
				<tr>
					<td align='center' class='nombres_filas'>$cont</td>
					<td align='center' class='$nom_clase'>$datos[descripcion]</td>
					<td align='center' class='$nom_clase'>$datos[cantidad]</td>
				</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "</table>";		
		}//Cierre if($datos=mysql_fetch_array($rs))
		else
			echo "<label class='msje_correcto'>No Hay Materiales Registrados en la Orden ".$_GET['idOrden']."</label>";
		
		//Cerrar la conexion con la BD
		mysql_close($conn);
		
	}//Cierre de la función mostrarMaterialesUtilizar($materialesUtilizar)
?>