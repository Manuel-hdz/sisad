<?php
	/**
	  * Nombre del Módulo: Mantenimiento
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 12/Abril/2012
	  * Descripción: Este archivo contiene funciones para generar la orden de trabajo para servicios externos.
	  **/	  	


	/***********************************************************************************************************************************************/
	/*********************************************REGISTRAR ORDEN DE TRABAJO PARA SERVICIOS EXTERNOS************************************************/
	/***********************************************************************************************************************************************/

	/*Esta funcion genera la Clave de la orden de trabajo para servicios externos de acuerdo a los registros en la BD*/
	function obtenerIdOrdenTrabajoSE($area){
		//Realizar la conexion a la BD de Mantenimiento
		$conn = conecta("bd_paileria");
		
		//Variable para almacenar el ID de la Orden para Servicios Externos creada
		$id_cadena = "";
		
		//Definir las tres letras en la Id de la Orden de Trabajo para Servicios Externos
		$prefijo = "";
		if($area=="CONCRETO")		
			$prefijo = "SEC";
		else if($area=="MINA")
			$prefijo = "SEM";
		else if($area=="GOMAR")
			$prefijo = "SEG";
			
		
		//Iniciar a crear la clave
		$id_cadena = $prefijo;
			
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		
		//Obtener el mes actual y el año actual 
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener la Orden de Trabajo Reciente acorde a la fecha
		$stm_sql = "SELECT MAX(id_orden) AS cant FROM orden_servicios_externos WHERE id_orden LIKE '$prefijo$mes$anio%'";
		//Ejecutar la Sentencia
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			//Obtener las ultimas 3 cifras de la orden de trabajo registrada en la BD y sumarle 1
			$cant = substr($datos['cant'],-3)+1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
		
		return $id_cadena;
	}//Fin de la Funcion obtenerIdOrdenTrabajoSE($area)
	

	/*Esta funcion muestra las actividades agregas a la Orden de Trabajo para Servicios Externos en la Pagina de Registrar Actividades*/
	function mostrarActividadesRealizar($actividadesRealizar){
		echo "<table width='100%' class='tabla_frm' cellpadding='5'>";
		echo "<caption class='msje_correcto'><strong>Actividades Agregados a la Orden ".$_SESSION['ordenServicioExterno']['idOrdenTrabajo']."</strong></caption>";
		echo "      			
			<tr>
				<td width='10%' class='nombres_columnas_gomar' align='center'>PARTIDA</td>
				<td width='15%' class='nombres_columnas_gomar' align='center'>SISTEMA</td>
        		<td width='15%' class='nombres_columnas_gomar' align='center'>APLICACION</td>
			    <td width='40%' class='nombres_columnas_gomar' align='center'>ACTIVIDAD</td>
				<td width='10%' class='nombres_columnas_gomar' align='center'>FAMILIA</td>
				<td width='10%' class='nombres_columnas_gomar' align='center'>EQUIPO</td>
      		</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		foreach ($actividadesRealizar as $ind => $registro) {
			echo "
			<tr>
				<td align='center' class='nombres_filas_gomar'>$registro[partida]</td>
				<td align='center' class='$nom_clase'>$registro[sistema]</td>
				<td align='center' class='$nom_clase'>$registro[aplicacion]</td>
				<td align='center' class='$nom_clase'>$registro[actividad]</td>
				<td align='center' class='$nom_clase'>$registro[familia]</td>
				<td align='center' class='$nom_clase'>$registro[claveEquipo]</td>
			</tr>";
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
		}
		echo "</table>";		
	}//Cierre de la función mostrarActividadesRealizar($actividadesRealizar)
	
	
	/*Esta funcion muestra los Materiales agregados a la Orden de Trabajo para Servicios Externos en la Pagina de Registrar Materiales*/
	function mostrarMaterialesUtilizar($materialesUtilizar){
		echo "<table width='100%' class='tabla_frm' cellpadding='5'>";
		echo "<caption class='msje_correcto'><strong>Materiales Agregados a la Orden ".$_SESSION['ordenServicioExterno']['idOrdenTrabajo']."</strong></caption>";
		echo "      			
			<tr>
				<td width='20%' class='nombres_columnas_gomar' align='center'>PARTIDA</td>
				<td width='60%' class='nombres_columnas_gomar' align='center'>MATERIAL</td>
				<td width='20%' class='nombres_columnas_gomar' align='center'>CANTIDAD</td>
      		</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		foreach ($materialesUtilizar as $ind => $registro) {
			echo "
			<tr>
				<td align='center' class='nombres_filas_gomar'>$registro[partida]</td>
				<td align='center' class='$nom_clase'>$registro[material]</td>
				<td align='center' class='$nom_clase'>$registro[cantidad]</td>
			</tr>";
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
		}
		echo "</table>";		
	}//Cierre de la función mostrarMaterialesUtilizar($materialesUtilizar)

	
	//Funcion que se encarga de guardar la orden de trabajo en la tabla de orden_servicios_externos
	function generarOrdenTrabajoServicioExterno(){
		
		//Realizar la conexion a la BD de mantenimiento
		$conn = conecta("bd_paileria");				
		
		//Recuperar datos del POST, ya que antes de generar la orden se valida que todos los datos sean proporcionados
		$idOrdenTrabajo = $_POST['txt_ordenTrabajo'];
		$fechaRegistro = modFecha($_POST['txt_fechaRegistro'],3);
		$clasificacion = $_POST['cmb_clasificacion'];
		$fechaSolicitud = modFecha($_POST['txt_fechaSolicitud'],3);
		$fechaRecepcion = modFecha($_POST['txt_fechaRecepcion'],3);
		$comboProveedor = $_POST['cmb_proveedor'];
		$nomProveedor = strtoupper($_POST['txt_proveedor']);
		$direccion = strtoupper($_POST['txt_direccion']);
		$repProveedor = strtoupper($_POST['txt_repProveedor']);
		$encCompras = strtoupper($_POST['txt_encCompras']);
		$solicito = strtoupper($_POST['txt_solicito']);
		$autorizo = strtoupper($_POST['txt_autorizo']);
		$nomDepto = $_SESSION['depto'];
		
		
		$proveedor = "";
		if($comboProveedor=="NVO_PROVEEDOR")
			$proveedor = $nomProveedor;
		else if($comboProveedor!="NVO_PROVEEDOR")
			$proveedor = $comboProveedor;
		
		//Crear la Sentecnia SQL para insertar los datos
		$stm_sql = "INSERT INTO orden_servicios_externos(id_orden,fecha_creacion, clasificacion, fecha_entrega, fecha_recepcion, nom_proveedor, direccion, rep_proveedor,
		encargado_compras, solicito, autorizo, depto, costo_total, factura, complementada)
		VALUES('$idOrdenTrabajo', '$fechaRegistro', '$clasificacion', '$fechaSolicitud', '$fechaRecepcion', '$proveedor', '$direccion', '$repProveedor', '$encCompras',
		'$solicito','$autorizo','$nomDepto',0,'','')";
	
		//Ejecutar la Sentencia SQL
		$rs = mysql_query($stm_sql);
		
		if($rs){
			//Registrar Actividades y si fueron agregadas correctamente, proceder a agregar los materiales en el caso que existan
			$resultado = 1;
			if(registrarActividades()){
				if(isset($_SESSION['materialesUtilizar'])){
					$resultado = registrarMateriales();
				}
			}
			else{//Cambiar el valor de la variable para inidicar que no se pudieron agregar las Actividades
				$resultado = 0;
			}
			
			
			//El valor de 1 indica que los datos fueron agregados éxitosamente
			if($resultado==1){
				//Guardar el registro de movimientos
				registrarOperacion("bd_paileria",$idOrdenTrabajo,"GenerarOrdenServicioExterno",$_SESSION['usr_reg']);
												
				//Si se agrega corectamente generar el pdf de la orden de trabajo?>
				<script type="text/javascript" language="javascript">															
					var codigoPopUp = "window.open('../../includes/generadorPDF/ordenServicioExternoGomar.php?";
					codigoPopUp += "id_orden=<?php echo $idOrdenTrabajo; ?>&nom_depto=<?php echo $_POST['txt_area']; ?>&fecha_reg=<?php echo $_POST['txt_fechaRegistro']; ?>', ";
					codigoPopUp += "'_blank', 'top=100, left=100, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, ";
					codigoPopUp += "toolbar=no, location=no, directories=no');";
					setTimeout(codigoPopUp,4000);
				</script><?php				
				
				echo "<meta http-equiv='refresh' content='5;url=exito.php'>";
			}
			else if($resultado==0){
				//Si los datos no se agregaron correctamente, borrar los datos guardados y redireccionar a la pagina de error
				mysql_query("DELETE FROM orden_servicios_externos WHERE id_orden = '$idOrdenTrabajo'");
				mysql_query("DELETE FROM actividades_realizadas WHERE orden_servicios_externos_id_orden = '$idOrdenTrabajo'");
				
				//Cerrar la Conexion con la BD
				$error = "Error al Tratar de Agregadar los Datos para la Orden de Trabajo para Servicios Externos";
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}			
		}//Cierre if($rs)	
		else{ 
			//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error								
			$error = mysql_error();
			echo "</br></br></br></br></br></br></br></br></br></br></br></br></br></br>$error";
			break;
			
			//Cerrar la Conexion con la BD
			mysql_close($conn);
			
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}		
	}//Cierre de función generarOrdenTrabajoServicioExterno()
	
	
	//Permite registrar las actividades en los mantenimientos preventivo y correctivo
	function registrarActividades(){
	
		//Variable que nos ayudará a identificar problemas de inserción de datos en la BD
		$band = 0;
		
		//Recuperar el Id de la Orden de Trabajo de la SESSION
		$idOrdenTrabajo = $_SESSION['ordenServicioExterno']['idOrdenTrabajo'];
		
		foreach($_SESSION['actividadesRealizar'] as $ind => $act){
			$noAct = $ind + 1;
			//Creamos la sentencia SQL para insertar los datos en la tabla Mecanico
			$stm_sql = "INSERT INTO actividades_realizadas (orden_servicios_externos_id_orden, no_actividad, sistema, aplicacion, descripcion, familia, equipo, costo_actividad)
						VALUES('$idOrdenTrabajo', $noAct, '$act[sistema]', '$act[aplicacion]', '$act[actividad]', '$act[familia]', '$act[claveEquipo]', 0)";			
			
			//Ejecutar la sentencia previamente creadas
			$rs = mysql_query($stm_sql);
			if(!$rs)
				$band = 1;						
		
			//Romper el proceso de registro del detalle de la entrada en el caso de que existan errores	
			if($band==1)
				break;	
		}//Cierre foreach($_SESSION['actividadesRealizar'] as $ind => $act)
		
		
		//Si la bandera esta activa, significa que hubo error en la inserción de datos
		if ($band==1){			
			return false;
		}
		else{
			//Regresar verdadero cuando las actividades se haya registrado correctamente
			return true;
		}					
		
	}//Fin de la funcion registrarAcciones()	
	
	
	/*Esta función registra los materiales a utilziar en la Orden de Trabajo para Servicios Externos*/
	function registrarMateriales(){
		//Variable que nos ayudará a identificar problemas de inserción de datos en la BD
		$band = 0;
		//Recuperar el Id de la Orden de Trabajo de la SESSION
		$idOrdenTrabajo = $_SESSION['ordenServicioExterno']['idOrdenTrabajo'];
		
		foreach($_SESSION['materialesUtilizar'] as $ind => $regMaterial){
			//Creamos la sentencia SQL para insertar los datos en la tabla Mecanico
			$stm_sql = "INSERT INTO materiales_usados (orden_servicios_externos_id_orden, descripcion, cantidad)
						VALUES('$idOrdenTrabajo','$regMaterial[material]', $regMaterial[cantidad])";			
			
			//Ejecutar la sentencia previamente creadas
			$rs = mysql_query($stm_sql);
			if(!$rs)
				$band = 1;
		
			//Romper el proceso de registro del detalle de la entrada en el caso de que existan errores	
			if($band==1)
				break;	
		}//Cierre foreach($_SESSION['actividadesRealizar'] as $ind => $act)
		
		
		//Si la bandera esta activa, significa que hubo error en la inserción de datos
		if ($band==1){			
			return 0;
		}
		else{
			//Regresar 1 cuando los materiales se hayan registrado correctamente
			return 1;
		}		
	}//Cierre registrarMateriales()
	
	
	/***********************************************************************************************************************************************/
	/*********************************************CONSULTAR ORDEN DE TRABAJO PARA SERVICIOS EXTERNOS************************************************/
	/***********************************************************************************************************************************************/	
	
	
	//Esta función muestra las Ordenes de Trabajo para Servivios Externos de acuerdo a los parámetros seleccionados por el usuario
	function mostrarOrdenesServiciosExternos(){
	
		//Conectar a la BD de Mantenimiento
		$conn = conecta("bd_paileria");
	
		//Recuperar parámetros de busqueda del POST		
		$area = $_POST['txt_area'];
		$familia = $_POST['cmb_familia'];
		$equipo = $_POST['cmb_equipo'];
		$proveedor = $_POST['cmb_nomProveedor'];
		
		//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
		$fechaIni = modFecha($_POST['txt_fechaInicio'],3);
		$fechaFin = modFecha($_POST['txt_fechaFin'],3);	
		
		
		//Crear la Sentencia SQL
		$sql_stm_OTSE = "SELECT DISTINCT id_orden FROM orden_servicios_externos JOIN actividades_realizadas ON id_orden=orden_servicios_externos_id_orden WHERE ";		
		//Si la Familia esta incluida, agregar el parámetro de busqueda
		if($familia!=""){
			$sql_stm_OTSE .= "familia = '$familia' AND ";
		}
		//Si el Equipo esta incluido, agregar el parámetro de busqueda
		if($equipo!=""){
			$sql_stm_OTSE .= "equipo = '$equipo' AND ";
		}
		//Si el Proveedor esta incluido, agregar el parámetro de busqueda
		if($proveedor!=""){
			$sql_stm_OTSE .= "nom_proveedor = '$proveedor' AND ";
		}
		//Complementar la Sentencia con las Fechas y el Departamento
		$sql_stm_OTSE .= "fecha_creacion BETWEEN '$fechaIni' AND '$fechaFin' AND depto = '".$_SESSION['depto']."'";
		
		
		//Crear el mensaje que se mostrara en el titulo de la tabla
		$msg = "Ordenes de Trabajo para Servicios Externos en el Periodo del <em><u>$_POST[txt_fechaInicio]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
		
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "<label class='msje_correcto' align='center'>No se Encontraron Ordenes de Trabajo para Servicios Externos 
						en las Fechas del <em><u>$_POST[txt_fechaInicio]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em></label>";

		
		//Ejecutar la sentencia previamente creada
		$rs_OTSE = mysql_query($sql_stm_OTSE);
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos_OTSE=mysql_fetch_array($rs_OTSE)){
	
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='150%'>
				<tr>
					<td colspan='13' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas_gomar'>DETALLE</td>
					<td class='nombres_columnas_gomar'>COMPLEMENTAR</td>
					<td class='nombres_columnas_gomar'>ID ORDEN</td>
					<td class='nombres_columnas_gomar'>FECHA REGISTRO</td>
					<td class='nombres_columnas_gomar'>PROVEEDOR</td>
					<td class='nombres_columnas_gomar'>DIRECCI&Oacute;N</td>
					<td class='nombres_columnas_gomar'>FECHA ENTREGA</td>
					<td class='nombres_columnas_gomar'>FECHA RECEPCI&Oacute;N</td>
					<td class='nombres_columnas_gomar'>REPRESENTANTE PROVEEDOR</td>
					<td class='nombres_columnas_gomar'>ENCARGADO COMPRASAS</td>
					<td class='nombres_columnas_gomar'>SOLICIT&Oacute;</td>
					<td class='nombres_columnas_gomar'>AUTORIZ&Oacute;</td>
					<td class='nombres_columnas_gomar'>COSTO</td>
					<td class='nombres_columnas_gomar'>FACTURA</td>
				</tr>
				<form name='frm_mostrarDetalleOTSE' method='post' action='frm_consultarOrdenServiciosE.php'>";

			$nom_clase = "renglon_gris";
			$cont = 1;			
			do{							
				
				//Extraer los de cada orden de trabajo que coinsidio con los parametros de busqueda seleccionados
				$detalle_OTSE = mysql_fetch_array(mysql_query("SELECT * FROM orden_servicios_externos WHERE id_orden = '$datos_OTSE[id_orden]'"));
				
				//Verificar si esta incluido el Costo, en el caso de que no lo tenga, colocamos la Leyenda N/R
				$costo = "N/R";
				if($detalle_OTSE['costo_total']!=0)
					$costo = "$".number_format($detalle_OTSE['costo_total'],2,".",",");
				
				//Verificar si esta registrada el No. de Factura
				$factura = "N/R";
				if($detalle_OTSE['factura']!="")
					$factura = $detalle_OTSE['factura'];
				
				//Mostrar todos los registros que han sido completados
				echo "
				<tr>
					<td class='nombres_filas_gomar'>
						<input type='checkbox' name='ckb_detalleOTSE' id='ckb_detalleOTSE' value='$datos_OTSE[id_orden]' 
						onClick='document.frm_mostrarDetalleOTSE.submit();'/>
					</td>
					<td class='$nom_clase'>";?>
						<input type="button" name="btn_materialesOTSE" id="btn_materialesOTSE" class="botones" value="Materiales" title="Registrar Materiales"
						onclick="materialesOTSE('<?php echo $datos_OTSE['id_orden']; ?>');" /><?php
				echo "	
					</td>
					<td class='$nom_clase' align='center'>$datos_OTSE[id_orden]</td>
					<td class='$nom_clase' align='center'>".modFecha($detalle_OTSE['fecha_creacion'],1)."</td>
					<td class='$nom_clase' align='center'>$detalle_OTSE[nom_proveedor]</td>
					<td class='$nom_clase' align='center'>$detalle_OTSE[direccion]</td>
					<td class='$nom_clase' align='center'>".modFecha($detalle_OTSE['fecha_entrega'],1)."</td>
					<td class='$nom_clase' align='center'>".modFecha($detalle_OTSE['fecha_recepcion'],1)."</td>
					<td class='$nom_clase' align='center'>$detalle_OTSE[rep_proveedor]</td>
					<td class='$nom_clase' align='center'>$detalle_OTSE[encargado_compras]</td>
					<td class='$nom_clase' align='center'>$detalle_OTSE[solicito]</td>
					<td class='$nom_clase' align='center'>$detalle_OTSE[autorizo]</td>
					<td class='$nom_clase' align='center'>$costo</td>
					<td class='$nom_clase' align='center'>$factura</td>
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
		}// fin  if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;
		}
	}//Cierre de la función mostrarOrdenesServiciosExternos()
	
	
	/*Esta función Muestra el Detalle de la Orde de Trabajo para Servicios Externos Seleccionada*/
	function mostrarDetalleOTSE(){
		//Realizar la conexion a la BD de Mantenimeinto
		$conn = conecta("bd_paileria");
		
		//Recuperar el Id de la Orden Seleccionada del POST
		$idOrdenSE = $_POST['ckb_detalleOTSE'];
		
		$datosFecha = mysql_fetch_array(mysql_query("SELECT fecha_creacion FROM orden_servicios_externos WHERE id_orden = '$idOrdenSE'"));
		
		//Guardar en la SESSION los datos complementarios(Fecha y Id de la Orden Selecccionada) para poder generar nuevamente el archivo PDF
		$_SESSION['datosConsultaOTSE']['fechaCreacion'] = modFecha($datosFecha['fecha_creacion'],1);
		$_SESSION['datosConsultaOTSE']['idOrden'] = $idOrdenSE;
		
		
		/*************************************************** DESPLEGAR LAS ACTIVIDADES REGISTRADAS **************************************************/
		//Crear la Sentencia SQL para obtener las actividades registradas en la orden
		$stm_sql = "SELECT sistema, aplicacion, descripcion, familia, equipo, costo_actividad FROM actividades_realizadas WHERE orden_servicios_externos_id_orden = '$idOrdenSE'";		
										
		//Ejecutar la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		if($datos=mysql_fetch_array($rs)){
			echo "					
			<label class='titulo_etiqueta'>ACTIVIDADES A EFECTUAR EN LA ORDEN <em><u>$idOrdenSE</u></em></label>								
			<br><br>								
			<table width='100%' cellpadding='5'>				
				<tr>
					<td class='nombres_columnas_gomar'>NO.</td>
					<td class='nombres_columnas_gomar'>SISTEMA</td>
					<td class='nombres_columnas_gomar'>APLICACI&Oacute;N</td>						
					<td class='nombres_columnas_gomar'>DESCRIPCI&Oacute;N</td>
					<td class='nombres_columnas_gomar'>FAMILIA</td>
					<td class='nombres_columnas_gomar'>EQUIPO</td>
					<td class='nombres_columnas_gomar'>COSTO</td>
				</tr>";
				
			//Manejar el estilo de los renglones de la tabla creada
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{				
				
				//Verificar si esta incluido el Costo, en el caso de que no lo tenga, colocamos la Leyenda N/R
				$costo = "N/R";
				if($datos['costo_actividad']!=0)
					$costo = "$".number_format($datos['costo_actividad'],2,".",",");
					
				echo "<tr>		
						<td class='nombres_filas_gomar'>$cont</td>	
						<td class='$nom_clase'>$datos[sistema]</td>	
						<td class='$nom_clase'>$datos[aplicacion]</td>	
						<td class='$nom_clase'>$datos[descripcion]</td>	
						<td class='$nom_clase'>$datos[familia]</td>	
						<td class='$nom_clase'>$datos[equipo]</td>	
						<td class='$nom_clase'>$costo</td>							
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
		else{
			echo "<label class='msje_correcto' align='center'>NO hay Actividades Registradas en la Orden <em><u>$idOrdenSE</u></em></label>";
		}
		
		
		
		
		/*************************************************** DESPLEGAR LOS MATERIALES REGISTRADOS **************************************************/
		//Crear la Sentencia SQL para obtener las actividades registradas en la orden
		$stm_sql = "SELECT * FROM materiales_usados WHERE orden_servicios_externos_id_orden = '$idOrdenSE'";		
										
		//Ejecutar la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		if($datos=mysql_fetch_array($rs)){
			echo "					
			<label class='titulo_etiqueta'>MATERIALES REGISTRADOS EN LA ORDEN <em><u>$idOrdenSE</u></em></label>								
			<br><br>								
			<table width='100%' cellpadding='5'>				
				<tr>
					<td class='nombres_columnas_gomar'>NO.</td>
					<td class='nombres_columnas_gomar'>DESCRIPCI&Oacute;N</td>
					<td class='nombres_columnas_gomar'>CANTIDAD</td>
				</tr>";
				
			//Manejar el estilo de los renglones de la tabla creada
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{				
				echo "<tr>		
						<td class='nombres_filas_gomar'>$cont</td>	
						<td class='$nom_clase'>$datos[descripcion]</td>	
						<td class='$nom_clase'>$datos[cantidad]</td>	
					</tr>";
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "</table><br>";
		}
		else{
			echo "<label class='msje_correcto' align='center'>NO hay Materiales Registrados en la Orden <em><u>$idOrdenSE</u></em></label>";
		}
		
				
		//Cerrar la conexion con la BD
		mysql_close($conn);			
		
	}//Cierre de la función mostrarDetalleOTSE()
		
	
	/***********************************************************************************************************************************************/
	/*********************************************************** COMPLEMENTAR MATERIALES ***********************************************************/
	/***********************************************************************************************************************************************/
	
	//Est funcion guarda los materiales desde la pantalla de Complementar materialess
	function registrarMaterial(){
		//Conectarse con la Base de Datos
		$conn = conecta("bd_paileria");
		
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
		$conn = conecta("bd_paileria");
		
		//Crear la Sentencia SQL para obtener los materiales registrados en la OTSE
		$sql_stm = "SELECT * FROM materiales_usados WHERE orden_servicios_externos_id_orden = '$_GET[idOrden]'";
		
		//Ejecutar la Sentencia SQL
		$rs = mysql_query($sql_stm);
		
		if($datos=mysql_fetch_array($rs)){
	
			echo "<table width='100%' class='tabla_frm' cellpadding='5'>";
			echo "<caption class='msje_correcto'><strong>Materiales Agregados a la Orden ".$_GET['idOrden']."</strong></caption>";
			echo "
				<tr>
					<td width='20%' class='nombres_columnas_gomar' align='center'>PARTIDA</td>
					<td width='60%' class='nombres_columnas_gomar' align='center'>MATERIAL</td>
					<td width='20%' class='nombres_columnas_gomar' align='center'>CANTIDAD</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{
				echo "
				<tr>
					<td align='center' class='nombres_filas_gomar'>$cont</td>
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