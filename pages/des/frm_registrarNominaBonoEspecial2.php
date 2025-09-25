<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Desarrollo
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_registrarNomina.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionDesarrollo.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="includes/ajax/cargarCatalogoSueldos.js"></script>

    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>    
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

   <style type="text/css">
		<!--
		#titulo-detalle {position:absolute;left:30px;top:146px;width:194px;height:23px;z-index:11;}
		#tabla-pedido-detalles{position:absolute;left:30px;top:525px;width:900px;height:150px;z-index:12; overflow:scroll;}
		#botones{position:absolute;left:30px;top:650px;width:900px;height:37px;z-index:13;}		
		#detalles_pedido{position:absolute;left:30px;top:190px;width:940px;height:400px;z-index:15; overflow:scroll;}
		#tabla-nomina {position:absolute;left:30px;top:190px;width:900px;height:308px;z-index:16;}		
		#lista-proveedores { position:absolute; left:540px; top:210px; width:321px; height:104px; z-index:17; }
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-detalle">N&oacute;mina de Desarrollo</div>
	<?php if(!isset($_POST['btn_continuar']) && !isset($_POST['btn_avance'])){ ?>
	<form name="registrar_nomina" action="frm_registrarNominaBonoEspecial2.php" method="post">
	<div id="detalles_pedido" class="borde_seccion2" style="height:400px;">
    <?php
		//Realizar la conexion a la BD de Recursos Humanos
		$conn = conecta("bd_recursos");	
		//Escribimos la consulta a realizarse 
		$stm_sql = "SELECT DISTINCT  `rfc_empleado` ,  `id_empleados_empresa` , CONCAT(  `nombre` ,  ' ',  `ape_pat` ,  ' ',  `ape_mat` ) AS nombre,  `sueldo_diario` ,  `puesto` 
					FROM  `empleados` 
					WHERE  `area` =  'DESARROLLO FRESNILLO'
					AND  `id_cuentas` =  'CUEN001'
					AND  `estado_actual` = 'ALTA'
					ORDER BY nombre";
		
		$msje="Registro de N&oacute;mina Desarrollo del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
		//Ejecutar la Sentencia 
		$rs = mysql_query($stm_sql);
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			$_SESSION["destajos"] = array();
			echo "<table cellpadding='5' width='100%' align='center'>
					<caption class='titulo_etiqueta'>$msje</caption></br>";
				echo "
					<thead>
						<tr>
							<th class='nombres_columnas' align='center' rowspan='2'>Nº EMPLEADO</th>
							<th class='nombres_columnas' align='center' rowspan='2'>NOMBRE DEL COLABORADOR</th>
							<th class='nombres_columnas' align='center' rowspan='2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;J&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
							<th class='nombres_columnas' align='center' rowspan='2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;V&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
							<th class='nombres_columnas' align='center' rowspan='2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;S&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
							<th class='nombres_columnas' align='center' rowspan='2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;D&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
							<th class='nombres_columnas' align='center' rowspan='2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;L&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
							<th class='nombres_columnas' align='center' rowspan='2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;M&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
							<th class='nombres_columnas' align='center' rowspan='2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;M&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
							<th class='nombres_columnas' align='center' rowspan='2'>E</th>
							<th class='nombres_columnas' align='center' rowspan='2'>SUELDO BASE</th>
							<th class='nombres_columnas' align='center' rowspan='2'>SUELDO DIARIO</th>
							<th class='nombres_columnas' align='center' rowspan='2'>DESTAJO</th>
							<th class='nombres_columnas' align='center' rowspan='2'>TOTAL</th>
							<th class='nombres_columnas' align='center' rowspan='2'>COMENTARIOS</th>
							<th class='nombres_columnas' align='center' rowspan='2'>HORA EXTRA</th>
							<th class='nombres_columnas' align='center' colspan='2'>GUARDIA</th>
						</tr>
						<tr>
							<th class='nombres_columnas' align='center'>8 HORAS</th>
							<th class='nombres_columnas' align='center'>12 HORAS</th>
						</tr>
					</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{	 
				$destajo = 0;
				$destajo = obtenerDestajoEmpleado($datos["id_empleados_empresa"], modFecha($_POST["txt_fechaIni"],3), modFecha($_POST["txt_fechaFin"],3));
				/*$puesto = obtenerDatoEmpleado("puesto",$datos["id_empleados_empresa"]);
				$area = obtenerDatoEmpleado("area",$datos["id_empleados_empresa"]);
				if($puesto == "OPERADOR"){
					if($area == "VOLADURAS"){
						$destajo = obtenerDestajo("avance", "voladuras", "VOLADURAS", $puesto, $datos["id_empleados_empresa"], modFecha($_POST["txt_fechaIni"],3), modFecha($_POST["txt_fechaFin"],3));
						$destajo = $destajo * obtenerDatoBonificacion($puesto, $area, "AVANCE");
					} else if($area == "JUMBO"){
						$destajo = obtenerDestajo("avance", "barrenacion_jumbo", "JUMBO", $puesto, $datos["id_empleados_empresa"], modFecha($_POST["txt_fechaIni"],3), modFecha($_POST["txt_fechaFin"],3));
						$destajo = $destajo * obtenerDatoBonificacion($puesto, $area, "AVANCE");
					} else if($area == "SCOOP"){
						$destajo = obtenerDestajo("tope_limpio", "rezagado", "SCOOP", $puesto, $datos["id_empleados_empresa"], modFecha($_POST["txt_fechaIni"],3), modFecha($_POST["txt_fechaFin"],3));
						$destajo_aux = obtenerDestajoSCOOP("rezagado", "SCOOP", $puesto, $datos["id_empleados_empresa"], modFecha($_POST["txt_fechaIni"],3), modFecha($_POST["txt_fechaFin"],3));
						$destajo = $destajo * obtenerDatoBonificacion($puesto, $area, "TOPE REZAGADO") + $destajo_aux * obtenerDatoBonificacion($puesto, $area, "CUCHARON TRASPALEO");
					}
				} else if($puesto == "AYUDANTE"){
					$destajo_vol = obtenerDestajo("avance", "voladuras", "VOLADURAS", $puesto, $datos["id_empleados_empresa"], modFecha($_POST["txt_fechaIni"],3), modFecha($_POST["txt_fechaFin"],3));
					$destajo_barr = obtenerDestajo("avance", "barrenacion_jumbo", "JUMBO", $puesto, $datos["id_empleados_empresa"], modFecha($_POST["txt_fechaIni"],3), modFecha($_POST["txt_fechaFin"],3));
					$destajo_anclas = obtenerDestajo("reanclaje", "barrenacion_jumbo", "JUMBO", $puesto, $datos["id_empleados_empresa"], modFecha($_POST["txt_fechaIni"],3), modFecha($_POST["txt_fechaFin"],3));
					$destajo = $destajo_vol * obtenerDatoBonificacion($puesto, "VOLADURAS", "AVANCE") + $destajo_barr * obtenerDatoBonificacion($puesto, "JUMBO", "AVANCE") + $destajo_anclas *obtenerDatoBonificacion($puesto, "JUMBO", "ANCLAS");
				}*/ ?>
				<tr>
					<td class=<?php echo $nom_clase; ?> align="center">
						<input name="txt_id_emp<?php echo $cont;?>" type="text" id="txt_id_emp<?php echo $cont;?>" class="caja_de_num" size="10" maxlength="10" 
						value="<?php echo $datos["id_empleados_empresa"]; ?>" readonly="readonly"/>
					</td>
					<td class=<?php echo $nom_clase; ?> align="center"><?php echo $datos["nombre"]; ?></td>
					<td class=<?php echo $nom_clase; ?> align="center">
						A <input type="checkbox" name="ckb_jueves<?php echo $cont?>" id="ckb_jueves<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,0,'ckb_juevesI','ckb_juevesAL');" tabindex="<?php echo $cont?>" checked="true"/>
						I <input type="checkbox" name="ckb_juevesI<?php echo $cont?>" id="ckb_juevesI<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,1,'ckb_jueves','ckb_juevesAL');" tabindex="<?php echo $cont?>"/>
						<font style="color:darkred;">AL</font> <input type="checkbox" name="ckb_juevesAL<?php echo $cont?>" id="ckb_juevesAL<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,'ckb_jueves<?php echo $cont?>',2,'ckb_juevesI','ckb_juevesAL');" tabindex="<?php echo $cont?>"/>
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						A <input type="checkbox" name="ckb_viernes<?php echo $cont?>" id="ckb_viernes<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,0,'ckb_viernesI','ckb_viernesAL');" tabindex="<?php echo $cont?>" checked="true"/>
						I <input type="checkbox" name="ckb_viernesI<?php echo $cont?>" id="ckb_viernesI<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,1,'ckb_viernes','ckb_viernesAL');" tabindex="<?php echo $cont?>"/>
						<font style="color:darkred;">AL</font> <input type="checkbox" name="ckb_viernesAL<?php echo $cont?>" id="ckb_viernesAL<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,'ckb_viernes<?php echo $cont?>',2,'ckb_viernesI','ckb_viernesAL');" tabindex="<?php echo $cont?>"/>
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						A <input type="checkbox" name="ckb_sabado<?php echo $cont?>" id="ckb_sabado<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,0,'ckb_sabadoI','ckb_sabadoAL');" tabindex="<?php echo $cont?>" checked="true"/>
						I <input type="checkbox" name="ckb_sabadoI<?php echo $cont?>" id="ckb_sabadoI<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,1,'ckb_sabado','ckb_sabadoAL');" tabindex="<?php echo $cont?>"/>
						<font style="color:darkred;">AL</font> <input type="checkbox" name="ckb_sabadoAL<?php echo $cont?>" id="ckb_sabadoAL<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,'ckb_sabado<?php echo $cont?>',2,'ckb_sabadoI','ckb_sabadoAL');" tabindex="<?php echo $cont?>"/>
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						A <input type="checkbox" name="ckb_domingo<?php echo $cont?>" id="ckb_domingo<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,0,'ckb_domingoI','ckb_domingoAL');" tabindex="<?php echo $cont?>" checked="true"/>
						I <input type="checkbox" name="ckb_domingoI<?php echo $cont?>" id="ckb_domingoI<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,1,'ckb_domingo','ckb_domingoAL');" tabindex="<?php echo $cont?>"/>
						<font style="color:darkred;">AL</font> <input type="checkbox" name="ckb_domingoAL<?php echo $cont?>" id="ckb_domingoAL<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,'ckb_domingo<?php echo $cont?>',2,'ckb_domingoI','ckb_domingoAL');" tabindex="<?php echo $cont?>"/>
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						A <input type="checkbox" name="ckb_lunes<?php echo $cont?>" id="ckb_lunes<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,0,'ckb_lunesI','ckb_lunesAL');" tabindex="<?php echo $cont?>" checked="true"/>
						I <input type="checkbox" name="ckb_lunesI<?php echo $cont?>" id="ckb_lunesI<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,1,'ckb_lunes','ckb_lunesAL');" tabindex="<?php echo $cont?>"/>
						<font style="color:darkred;">AL</font> <input type="checkbox" name="ckb_lunesAL<?php echo $cont?>" id="ckb_lunesAL<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,'ckb_lunes<?php echo $cont?>',2,'ckb_lunesI','ckb_lunesAL');" tabindex="<?php echo $cont?>"/>
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						A <input type="checkbox" name="ckb_martes<?php echo $cont?>" id="ckb_martes<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,0,'ckb_martesI','ckb_martesAL');" tabindex="<?php echo $cont?>" checked="true"/>
						I <input type="checkbox" name="ckb_martesI<?php echo $cont?>" id="ckb_martesI<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,1,'ckb_martes','ckb_martesAL');" tabindex="<?php echo $cont?>"/>
						<font style="color:darkred;">AL</font> <input type="checkbox" name="ckb_martesAL<?php echo $cont?>" id="ckb_martesAL<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,'ckb_martes<?php echo $cont?>',2,'ckb_martesI','ckb_martesAL');" tabindex="<?php echo $cont?>"/>
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						A <input type="checkbox" name="ckb_miercoles<?php echo $cont?>" id="ckb_miercoles<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,0,'ckb_miercolesI','ckb_miercolesAL');" tabindex="<?php echo $cont?>" checked="true"/>
						I <input type="checkbox" name="ckb_miercolesI<?php echo $cont?>" id="ckb_miercolesI<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,1,'ckb_miercoles','ckb_miercolesAL');" tabindex="<?php echo $cont?>"/>
						<font style="color:darkred;">AL</font> <input type="checkbox" name="ckb_miercolesAL<?php echo $cont?>" id="ckb_miercolesAL<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,'ckb_miercoles<?php echo $cont?>',2,'ckb_miercolesI','ckb_miercolesAL');" tabindex="<?php echo $cont?>"/>
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						<input type="checkbox" name="ckb_extra<?php echo $cont?>" id="ckb_extra<?php echo $cont?>" onclick="desbloquearCamposNomina(this.id,<?php echo $cont?>);" tabindex="<?php echo $cont?>"/>
					</td>
					<?php $sueldo_base = $datos["sueldo_diario"] * 7; ?>
					<?php $sueldo_base = obtenerSueldoEmpleado($datos["id_empleados_empresa"],modFecha($_POST["txt_fechaIni"],3), modFecha($_POST["txt_fechaFin"],3),$datos["sueldo_diario"],$datos["puesto"]) * 7;?>
					<td class=<?php echo $nom_clase; ?> align="center">
						<input name="txt_sb<?php echo $cont;?>" type="text" id="txt_sb<?php echo $cont;?>" class="caja_de_num" size="10" maxlength="10" value="<?php echo $sueldo_base; ?>"
						onChange="formatCurrency(this.value.replace(/,/g,''),this.name);operacionesPedido(<?php echo $cont;?>,'imp');" readonly="readonly"/>
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						<input name="txt_sd<?php echo $cont;?>" type="text" id="txt_sd<?php echo $cont;?>" class="caja_de_num" size="10" maxlength="10" value="<?php echo obtenerSueldoEmpleado($datos["id_empleados_empresa"],modFecha($_POST["txt_fechaIni"],3), modFecha($_POST["txt_fechaFin"],3),$datos["sueldo_diario"],$datos["puesto"]); ?>"
						onChange="formatCurrency(this.value.replace(/,/g,''),this.name);operacionesPedido(<?php echo $cont;?>,'imp');" readonly="readonly"/>
					</td>
					<?php $_SESSION["destajos"][] = $destajo; ?>
					<td class=<?php echo $nom_clase; ?> align="center">
						<input name="txt_destajo<?php echo $cont;?>" type="text" id="txt_destajo<?php echo $cont;?>" class="caja_de_num" size="10" maxlength="10" value="<?php echo $destajo; ?>"
						onChange="formatCurrency(this.value.replace(/,/g,''),this.name);operacionesPedido(<?php echo $cont;?>,'imp');" readonly="readonly"/>
					</td>
					<?php $total = $sueldo_base + $_SESSION["destajos"][$cont - 1]; ?>
					<td class=<?php echo $nom_clase; ?> align="center">
						<input name="txt_total<?php echo $cont;?>" type="text" id="txt_total<?php echo $cont;?>" class="caja_de_num" size="10" maxlength="10" value="<?php echo $total; ?>"
						onChange="formatCurrency(this.value.replace(/,/g,''),this.name);operacionesPedido(<?php echo $cont;?>,'imp');" readonly="readonly"/>
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						<textarea name="txt_comentario<?php echo $cont;?>" id="txt_comentario<?php echo $cont;?>" class="caja_texto" 
						rows="2" col="60" maxlength="300"></textarea>
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						<input name="txt_he<?php echo $cont;?>" type="text" id="txt_he<?php echo $cont;?>" class="caja_de_num" size="10" maxlength="10" readonly="readonly"
						onChange="agregarBonificacion(<?php echo $cont?>,this.id,<?php echo $_SESSION["destajos"][$cont - 1]; ?>);" onkeypress="return permite(event,'num');"/>
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						<input type="checkbox" name="ckb_8hrs<?php echo $cont?>" id="ckb_8hrs<?php echo $cont?>" onclick="agregarBonificacion(<?php echo $cont?>,this.id);" tabindex="<?php echo $cont?>"/>
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						<input type="checkbox" name="ckb_12hrs<?php echo $cont?>" id="ckb_12hrs<?php echo $cont?>" onclick="agregarBonificacion(<?php echo $cont?>,this.id);" tabindex="<?php echo $cont?>"/>
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						<input name="txt_bonificaciones<?php echo $cont;?>" type="hidden" id="txt_bonificaciones<?php echo $cont;?>" value="0"
						class="caja_de_num" size="10" maxlength="10" onkeypress="return permite(event,'num',2);" onchange="agregarBonifRH(<?php echo $cont?>)"/>
					</td>
				</tr>
				<?php
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "</table>";
			mysql_close($conn);
		}
	?>
	</div>
	<div id='botones' align='center'>
		<input type="hidden" name="hdn_cont" id="hdn_cont" value="<?php echo $cont - 1; ?>"/>
		<input type='hidden' name='txt_fechaIni' value='<?php echo $_POST["txt_fechaIni"]; ?>'/>
		<input type='hidden' name='txt_fechaFin' value='<?php echo $_POST["txt_fechaFin"]; ?>'/>
		<input name="btn_continuar" type="submit" class="botones" value="Finalizar Nomina" onmouseover="window.status='';return true;" title="Registrar Nomina"/>
		<input name="btn_avance" type="submit" class="botones" value="Guardar Avance" onmouseover="window.status='';return true;" title="Registrar Nomina"/>
		<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Cancelar Registro de Nomina" onclick="location.href='frm_registrarNomina.php'"/>
	</div>
	</form>
</body>
<?php 
	} else{
		guardarNomina2();
	}
}//Cierre del Else donde se comprueba el usuario que esta registrado 
	
	//Funcion que obtiene ciertos datos en especifico
	/*function obtenerDestajo($campo, $tabla, $area, $puesto, $nombre, $fechaI, $fechaF) {
		//Conectarse con la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		$stm_sql = "";
		
		if($area == "SCOOP" || $campo == "reanclaje"){
		$stm_sql = "SELECT SUM( T3.$campo ) AS TOTAL
					FROM bitacora_avance AS T1
					JOIN personal AS T2 ON T1.id_bitacora = T2.bitacora_avance_id_bitacora
					JOIN $tabla AS T3 ON T1.id_bitacora = T3.bitacora_avance_id_bitacora
					JOIN bd_recursos.empleados AS T4 ON T4.id_empleados_empresa = T2.nombre
					AND T2.area =  '$area'
					AND T2.puesto =  '$puesto'
					AND T2.nombre =  '$nombre'
					AND T3.fecha
					BETWEEN  '$fechaI'
					AND  '$fechaF'";
		} 
		else{
		$stm_sql = "SELECT SUM( T1.$campo ) AS TOTAL
					FROM bitacora_avance AS T1
					JOIN personal AS T2 ON T1.id_bitacora = T2.bitacora_avance_id_bitacora
					JOIN $tabla AS T3 ON T1.id_bitacora = T3.bitacora_avance_id_bitacora
					JOIN bd_recursos.empleados AS T4 ON T4.id_empleados_empresa = T2.nombre
					AND T2.area =  '$area'
					AND T2.puesto =  '$puesto'
					AND T2.nombre =  '$nombre'
					AND T3.fecha BETWEEN '$fechaI' AND '$fechaF'";
		}
		
		$rs = mysql_query($stm_sql);
		$datos = mysql_fetch_array($rs);
		return $datos[0];
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}//Fin de la funcion function obtenerDestajo($campo, $tabla, $area, $puesto, $nombre, $fechaI, $fechaF)
	
	//Funcion que obtiene ciertos datos en especifico
	function obtenerDestajoSCOOP($tabla, $area, $puesto, $nombre, $fechaI, $fechaF) {
		//Conectarse con la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		$stm_sql = "";
		
		$stm_sql = "SELECT SUM( 
					CASE WHEN T3.traspaleo =  '1'
					THEN T3.cuch
					ELSE 0 
					END ) AS TOTAL
					FROM bitacora_avance AS T1
					JOIN personal AS T2 ON T1.id_bitacora = T2.bitacora_avance_id_bitacora
					JOIN $tabla AS T3 ON T1.id_bitacora = T3.bitacora_avance_id_bitacora
					JOIN bd_recursos.empleados AS T4 ON T4.id_empleados_empresa = T2.nombre
					AND T2.area =  '$area'
					AND T2.puesto =  '$puesto'
					AND T2.nombre =  '$nombre'
					AND T3.fecha
					BETWEEN  '$fechaI'
					AND  '$fechaF'";
		
		$rs = mysql_query($stm_sql);
		$datos = mysql_fetch_array($rs);
		return $datos[0];
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}//Fin de la funcion function obtenerDestajoSCOOP($tabla, $area, $puesto, $nombre, $fechaI, $fechaF)
	
	//Funcion que obtiene ciertos datos en especifico
	function obtenerDatoEmpleado($campo, $dato) {
		//Conectarse con la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		$stm_sql = "SELECT $campo
					FROM personal
					WHERE nombre =  '$dato'
					GROUP BY $campo";
		$rs = mysql_query($stm_sql);
		$datos = mysql_fetch_array($rs);
		
		return $datos[0];
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}//Fin de la funcion function obtenerDatoEmpleado($campo, $dato)
	*/
	
	function obtenerDatoBonificacion($puesto, $area, $concpeto){
		//Conectarse con la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		$stm_sql = "SELECT bonificacion
					FROM catalogo_bonificacion
					WHERE puesto =  '$puesto'
					AND area =  '$area'
					AND concepto =  '$concpeto'";
		$rs = mysql_query($stm_sql);
		$datos = mysql_fetch_array($rs);
		return $datos[0];
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}//Fin de la funcion obtenerDatoBonificacion($puesto, $area, $concpeto)
	
	function obtenerDestajoEmpleado($id_empl,$fecha_ini,$fecha_fin){
		$destajo = 0;
		
		$conn = conecta("bd_desarrollo");
		
		$stm_sql = "SELECT T1.id_bitacora, T2.puesto, T2.area
					FROM bitacora_avance AS T1
					JOIN personal AS T2 ON T1.id_bitacora = T2.bitacora_avance_id_bitacora
					WHERE fecha_registro
					BETWEEN  '$fecha_ini'
					AND  '$fecha_fin'
					AND nombre =  '$id_empl'";
					
		$rs = mysql_query($stm_sql);
		
		if($rs){
			while($datos = mysql_fetch_array($rs)){
				
				/*if($datos["area"] == "VOLADURAS" && $voladuras == 0){
					$destajo += obtenerDestajoAcividad($id_empl,$fecha_ini,$fecha_fin,$datos["area"],$datos["puesto"],"voladuras","topes_cargados");
					$voladuras = 1;
				}
				else if($datos["area"] == "JUMBO" && $jumbo == 0){
					$destajo += obtenerDestajoAcividad($id_empl,$fecha_ini,$fecha_fin,$datos["area"],$datos["puesto"],"barrenacion_jumbo","reanclaje");
					$destajo += obtenerDestajoAcividad($id_empl,$fecha_ini,$fecha_fin,$datos["area"],$datos["puesto"],"barrenacion_jumbo","barrenos_dados");
					$jumbo = 1;
				}
				else if($datos["area"] == "SCOOP" && $scoop == 0){
					$destajo += obtenerDestajoAcividad($id_empl,$fecha_ini,$fecha_fin,$datos["area"],$datos["puesto"],"rezagado","traspaleo");
					$destajo += obtenerDestajoAcividad($id_empl,$fecha_ini,$fecha_fin,$datos["area"],$datos["puesto"],"rezagado","tope_limpio");
					$scoop = 1;
				}*/
				$puesto_empl = "";
				if($id_empl == '271' || $id_empl == '593' || $id_empl == '541' || $id_empl == '516')
					$puesto_empl = "OPERADOR";
				else
					$puesto_empl = $datos["puesto"];
				$destajo += obtenerDestajoAcividad($id_empl,$fecha_ini,$fecha_fin,$datos["area"],$puesto_empl,$datos["id_bitacora"]);
			}
		}
		
		return $destajo;
	}//Fin de la funcion obtenerDestajoEmpleado($id_empl,$fecha_ini,$fecha_fin)
	
	function obtenerDestajoAcividad($id_empl,$fecha_ini,$fecha_fin,$area,$puesto,$id_bitacora){
		$concepto = "";
		$destajo_act = 0;
		
		$conn = conecta("bd_desarrollo");
		
		/*$stm_sql = "SELECT *
					FROM bitacora_avance AS T1
					JOIN personal AS T2 ON T1.id_bitacora = T2.bitacora_avance_id_bitacora
					JOIN $tabla AS T3 ON T1.id_bitacora = T3.bitacora_avance_id_bitacora
					WHERE T1.fecha_registro
					BETWEEN  '$fecha_ini'
					AND  '$fecha_fin'
					AND T2.nombre =  '$id_empl'
					AND T2.area =  '$area'";*/
		
		if($area == "JUMBO"){
			$stm_sql = "SELECT T1.avance, T3.reanclaje, T3.barrenos_dados, T3.topes_barrenados ";
			$tabla = "barrenacion_jumbo";
		}
		else if($area == "VOLADURAS"){
			$stm_sql = "SELECT T1.avance, T3.topes_cargados ";
			$tabla = "voladuras";
		}
		else if($area == "SCOOP"){
			$stm_sql = "SELECT T1.avance, T3.traspaleo, T3.tope_limpio, T3.cuch ";
			$tabla = "rezagado";
		}
			
		$stm_sql .= "FROM bitacora_avance AS T1
					 JOIN personal AS T2 ON T1.id_bitacora = T2.bitacora_avance_id_bitacora
					 JOIN $tabla AS T3 ON T1.id_bitacora = T3.bitacora_avance_id_bitacora
					 WHERE T1.fecha_registro
					 BETWEEN  '$fecha_ini'
					 AND  '$fecha_fin'
					 AND T2.nombre =  '$id_empl'
					 AND T2.area =  '$area'
					 AND T1.id_bitacora =  '$id_bitacora'";
					
		$rs = mysql_query($stm_sql);
		
		if($rs){
			/*if($bus == "topes_cargados" || $bus == "reanclaje")
				$concepto = "REANCLAJE";
			else if($bus == "barrenos_dados")
				$concepto = "BARRENO";
			else if($bus == "traspaleo")
				$concepto = "CUCHARON TRASPALEO";
			else if($bus == "tope_limpio")
				$concepto = "TOPE REZAGADO";
			$datos = mysql_fetch_array($rs);
			$destajo_act += obtenerDatoBonificacion($puesto, $area, $concepto) * $datos["cantidad"];
			*/
			$jumbo = 0; $voladuras = 0; $scoop = 0;
			
			$datos = mysql_fetch_array($rs);
			do{
				if($area == "JUMBO"){
					if($id_empl == '115')
						$destajo_act += obtenerDatoBonificacion("OPERADOR", $area, "REANCLAJE") * $datos["reanclaje"];
					else
						$destajo_act += obtenerDatoBonificacion($puesto, $area, "REANCLAJE") * $datos["reanclaje"];
					if($datos["topes_barrenados"] >= 1 && $jumbo == 0){
						$destajo_act += obtenerDatoBonificacion($puesto, $area, "AVANCE") * $datos["avance"];
						$jumbo = 1;
					}
					else{
						if($id_empl == '115')
							$destajo_act += obtenerDatoBonificacion("OPERADOR", $area, "BARRENO") * $datos["barrenos_dados"];
						else
							$destajo_act += obtenerDatoBonificacion($puesto, $area, "BARRENO") * $datos["barrenos_dados"];
					}
				}
				else if($area == "VOLADURAS"){
					if($datos["topes_cargados"] >= 1 && $voladuras == 0){
						$destajo_act += obtenerDatoBonificacion($puesto, $area, "AVANCE") * $datos["avance"];
						$voladuras = 1;
					}
				}
				else if($area == "SCOOP"){
					if($datos["traspaleo"] > 0)
						$destajo_act += obtenerDatoBonificacion($puesto, $area, "CUCHARON TRASPALEO") * $datos["cuch"];
					if($datos["tope_limpio"] > 0)
						$destajo_act += obtenerDatoBonificacion($puesto, $area, "TOPE REZAGADO") * $datos["tope_limpio"];
				}
			}while($datos = mysql_fetch_array($rs));
		}
		
		return $destajo_act;
	}
	
	function obtenerSueldoEmpleado($id_empleado,$fecha_ini,$fecha_fin,$sueldo,$pues_empl){
		$conn = conecta("bd_desarrollo");
		
		$stm_sql = "SELECT DISTINCT T2.puesto, T2.area
					FROM bitacora_avance AS T1
					JOIN personal AS T2 ON T1.id_bitacora = T2.bitacora_avance_id_bitacora
					WHERE fecha_registro
					BETWEEN  '$fecha_ini'
					AND  '$fecha_fin'
					AND nombre =  '$id_empleado'";
		$rs = mysql_query($stm_sql);
		
		if($rs){
			$area = ""; $puesto ="";
			$num_reg = 0;
			while($datos = mysql_fetch_array($rs)){
				$area = $datos["area"];; 
				$puesto = $datos["puesto"];;
				$num_reg++;
			}
			if($num_reg > 1){
				return $sueldo;
			}
			else{
				$puesto_emp = "";
				$datos = mysql_fetch_array($rs,1);
				if($area == "JUMBO"){
					if($puesto == "OPERADOR")
						$puesto_emp = "OP. JUMBO";
					if($puesto == "AYUDANTE")
						$puesto_emp = "AYUDANTE DE JUMBO";
				}
				else if($area == "VOLADURAS"){
					if($puesto == "OPERADOR")
						$puesto_emp = "VOLADURAS";
					if($puesto == "AYUDANTE")
						$puesto_emp = "AYUDANTE VOLADURAS";
				}
				else if($area == "SCOOP"){
					$puesto_emp = "OP. SCOOP-TRAM";
				}
				
				if($puesto_emp == $pues_empl || $area == ""){
					return $sueldo;
				} else {
					$conn = conecta("bd_recursos");
					$stm_sql = "SELECT DISTINCT sueldo_diario
								FROM  `empleados` 
								WHERE  `puesto` LIKE  '%$puesto_emp%'
								AND  `estado_actual` =  'ALTA'
								ORDER BY  `empleados`.`puesto` ASC ";
					$rs = mysql_query($stm_sql);
					if($rs){
						$datos = mysql_fetch_array($rs);
						$sueldo = $datos["sueldo_diario"];
					}
					return $sueldo;
				}
			}
		}
	}
?>
</html>