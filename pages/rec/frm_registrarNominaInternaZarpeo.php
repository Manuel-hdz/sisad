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
		include ("op_registrarNominas.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionGerencia.js" ></script>
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
	<div class="titulo_barra" id="titulo-detalle">N&oacute;mina de Zarpeo</div>
	<?php if(!isset($_POST['btn_continuar'])){ ?>
	<form name="registrar_nomina" action="frm_registrarNominaInternaZarpeo.php" method="post">
	<div id="detalles_pedido" class="borde_seccion2" style="height:400px;">
    <?php
		//Realizar la conexion a la BD de Gerencia
		$conn = conecta("bd_gerencia");	
		//Escribimos la consulta a realizarse 
		$stm_sql = "SELECT T1. * , CONCAT( T2.nombre,  ' ', T2.ape_pat,  ' ', T2.ape_mat ) AS nombre, T3.prod_fre, T3.prod_sau
					FROM detalle_nominas AS T1
					JOIN bd_recursos.empleados AS T2
					USING ( id_empleados_empresa ) 
					JOIN nominas AS T3
					USING ( id_nomina ) 
					WHERE T1.id_nomina =  '$_POST[cmb_nomina]'";
					
		//modFecha($_POST["txt_fechaIni"],3)
		$msje="Registro de N&oacute;mina Zarpeo del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
		$fecha_ant = date("d/m/Y", strtotime("-1 month -3 day"));
		//Ejecutar la Sentencia 
		$rs = mysql_query($stm_sql);
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
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
							<th class='nombres_columnas' align='center' rowspan='2'>CUMPLIMIENTO</th>
							<th class='nombres_columnas' align='center' rowspan='2'>CALIDAD EN OBRA</th>
							<th class='nombres_columnas' align='center' rowspan='2'>BONIFICACI&Oacute;N</th>
							<th class='nombres_columnas' align='center' rowspan='2'>TOTAL</th>
							<th class='nombres_columnas' align='center' rowspan='2'>COMENTARIOS</th>
							<th class='nombres_columnas' align='center' rowspan='2'>HORA EXTRA</th>
							<th class='nombres_columnas' align='center' colspan='2'>GUARDIA</th>
							<th class='nombres_columnas' align='center' rowspan='2'>BONO</th>
						</tr>
						<tr>
							<th class='nombres_columnas' align='center'>8 HORAS</th>
							<th class='nombres_columnas' align='center'>12 HORAS</th>
						</tr>
					</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{	 
				$cumplimientos = obtenerDatos($datos["id_empleados_empresa"], modFecha($_POST["txt_fechaIni"],3), modFecha($_POST["txt_fechaFin"],3));
				$calidad_on = obtenerDatoBonificacion("M3", $cumplimientos["puesto"]);
				$bonif = obtenerDatoBonificacion("CALIDAD", $cumplimientos["puesto"]);
				$alcoholizado = obtenerAlcoholimetro(modFecha($fecha_ant,3), modFecha($_POST["txt_fechaFin"],3), $datos["id_empleados_empresa"]);
				
				?>
				<input type='hidden' id='txt_alcohol<?php echo $cont;?>' name='txt_alcohol<?php echo $cont;?>' value='<?php echo $alcoholizado; ?>'/>
				<tr>
					<td class=<?php echo $nom_clase; ?> align="center">
						<input name="txt_id_emp<?php echo $cont;?>" type="text" id="txt_id_emp<?php echo $cont;?>" class="caja_de_num" size="10" maxlength="10" 
						value="<?php echo $datos["id_empleados_empresa"]; ?>" readonly="readonly"/>
					</td>
					<td class=<?php echo $nom_clase; ?> align="center"><?php echo $datos["nombre"]; ?></td>
					<td class=<?php echo $nom_clase; ?> align="center">
						A <input type="checkbox" name="ckb_jueves<?php echo $cont?>" id="ckb_jueves<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,0,'ckb_juevesI','ckb_juevesAL');" tabindex="<?php echo $cont?>"
						<?php if($datos["jueves"]=="A") echo "checked='true'" ?> />
						I <input type="checkbox" name="ckb_juevesI<?php echo $cont?>" id="ckb_juevesI<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,1,'ckb_jueves','ckb_juevesAL');" tabindex="<?php echo $cont?>"
						<?php if($datos["jueves"]=="I") echo "checked='true'" ?> />
						<font style="color:darkred;">AL</font> <input type="checkbox" name="ckb_juevesAL<?php echo $cont?>" id="ckb_juevesAL<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,'ckb_jueves<?php echo $cont?>',2,'ckb_juevesI','ckb_juevesAL');" tabindex="<?php echo $cont?>"
						<?php if($datos["jueves"]=="B") echo "checked='true'" ?> />
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						A <input type="checkbox" name="ckb_viernes<?php echo $cont?>" id="ckb_viernes<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,0,'ckb_viernesI','ckb_viernesAL');" tabindex="<?php echo $cont?>"
						<?php if($datos["viernes"]=="A") echo "checked='true'" ?> />
						I <input type="checkbox" name="ckb_viernesI<?php echo $cont?>" id="ckb_viernesI<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,1,'ckb_viernes','ckb_viernesAL');" tabindex="<?php echo $cont?>"
						<?php if($datos["viernes"]=="I") echo "checked='true'" ?> />
						<font style="color:darkred;">AL</font> <input type="checkbox" name="ckb_viernesAL<?php echo $cont?>" id="ckb_viernesAL<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,'ckb_viernes<?php echo $cont?>',2,'ckb_viernesI','ckb_viernesAL');" tabindex="<?php echo $cont?>"
						<?php if($datos["viernes"]=="B") echo "checked='true'" ?> />
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						A <input type="checkbox" name="ckb_sabado<?php echo $cont?>" id="ckb_sabado<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,0,'ckb_sabadoI','ckb_sabadoAL');" tabindex="<?php echo $cont?>"
						<?php if($datos["sabado"]=="A") echo "checked='true'" ?> />
						I <input type="checkbox" name="ckb_sabadoI<?php echo $cont?>" id="ckb_sabadoI<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,1,'ckb_sabado','ckb_sabadoAL');" tabindex="<?php echo $cont?>"
						<?php if($datos["sabado"]=="I") echo "checked='true'" ?> />
						<font style="color:darkred;">AL</font> <input type="checkbox" name="ckb_sabadoAL<?php echo $cont?>" id="ckb_sabadoAL<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,'ckb_sabado<?php echo $cont?>',2,'ckb_sabadoI','ckb_sabadoAL');" tabindex="<?php echo $cont?>"
						<?php if($datos["sabado"]=="B") echo "checked='true'" ?> />
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						A <input type="checkbox" name="ckb_domingo<?php echo $cont?>" id="ckb_domingo<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,0,'ckb_domingoI','ckb_domingoAL');" tabindex="<?php echo $cont?>"
						<?php if($datos["domingo"]=="A") echo "checked='true'" ?> />
						I <input type="checkbox" name="ckb_domingoI<?php echo $cont?>" id="ckb_domingoI<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,1,'ckb_domingo','ckb_domingoAL');" tabindex="<?php echo $cont?>"
						<?php if($datos["domingo"]=="I") echo "checked='true'" ?> />
						<font style="color:darkred;">AL</font> <input type="checkbox" name="ckb_domingoAL<?php echo $cont?>" id="ckb_domingoAL<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,'ckb_domingo<?php echo $cont?>',2,'ckb_domingoI','ckb_domingoAL');" tabindex="<?php echo $cont?>"
						<?php if($datos["domingo"]=="B") echo "checked='true'" ?> />
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						A <input type="checkbox" name="ckb_lunes<?php echo $cont?>" id="ckb_lunes<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,0,'ckb_lunesI','ckb_lunesAL');" tabindex="<?php echo $cont?>"
						<?php if($datos["lunes"]=="A") echo "checked='true'" ?> />
						I <input type="checkbox" name="ckb_lunesI<?php echo $cont?>" id="ckb_lunesI<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,1,'ckb_lunes','ckb_lunesAL');" tabindex="<?php echo $cont?>"
						<?php if($datos["lunes"]=="I") echo "checked='true'" ?> />
						<font style="color:darkred;">AL</font> <input type="checkbox" name="ckb_lunesAL<?php echo $cont?>" id="ckb_lunesAL<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,'ckb_lunes<?php echo $cont?>',2,'ckb_lunesI','ckb_lunesAL');" tabindex="<?php echo $cont?>"
						<?php if($datos["lunes"]=="B") echo "checked='true'" ?> />
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						A <input type="checkbox" name="ckb_martes<?php echo $cont?>" id="ckb_martes<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,0,'ckb_martesI','ckb_martesAL');" tabindex="<?php echo $cont?>"
						<?php if($datos["martes"]=="A") echo "checked='true'" ?> />
						I <input type="checkbox" name="ckb_martesI<?php echo $cont?>" id="ckb_martesI<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,1,'ckb_martes','ckb_martesAL');" tabindex="<?php echo $cont?>"
						<?php if($datos["martes"]=="I") echo "checked='true'" ?> />
						<font style="color:darkred;">AL</font> <input type="checkbox" name="ckb_martesAL<?php echo $cont?>" id="ckb_martesAL<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,'ckb_martes<?php echo $cont?>',2,'ckb_martesI','ckb_martesAL');" tabindex="<?php echo $cont?>"
						<?php if($datos["martes"]=="B") echo "checked='true'" ?> />
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						A <input type="checkbox" name="ckb_miercoles<?php echo $cont?>" id="ckb_miercoles<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,0,'ckb_miercolesI','ckb_miercolesAL');" tabindex="<?php echo $cont?>"
						<?php if($datos["miercoles"]=="A") echo "checked='true'" ?> />
						I <input type="checkbox" name="ckb_miercolesI<?php echo $cont?>" id="ckb_miercolesI<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,1,'ckb_miercoles','ckb_miercolesAL');" tabindex="<?php echo $cont?>"
						<?php if($datos["miercoles"]=="I") echo "checked='true'" ?> />
						<font style="color:darkred;">AL</font> <input type="checkbox" name="ckb_miercolesAL<?php echo $cont?>" id="ckb_miercolesAL<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,'ckb_miercoles<?php echo $cont?>',2,'ckb_miercolesI','ckb_miercolesAL');" tabindex="<?php echo $cont?>"
						<?php if($datos["miercoles"]=="B") echo "checked='true'" ?> />
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						<input type="checkbox" name="ckb_extra<?php echo $cont?>" id="ckb_extra<?php echo $cont?>" onclick="desbloquearCamposNomina(this.id,<?php echo $cont?>);" tabindex="<?php echo $cont?>"
						<?php if($datos["horas_extra"]!="") echo "checked='true'"; ?> />
					</td>
					<?php $sueldo_base = $datos["sueldo_diario"] * 7; ?>
					<td class=<?php echo $nom_clase; ?> align="center">
						<input name="txt_sb<?php echo $cont;?>" type="text" id="txt_sb<?php echo $cont;?>" class="caja_de_num" size="10" maxlength="10" value="<?php echo $datos["sueldo_base"]; ?>"
						onChange="formatCurrency(this.value.replace(/,/g,''),this.name);operacionesPedido(<?php echo $cont;?>,'imp');" readonly="readonly"/>
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						<input name="txt_sd<?php echo $cont;?>" type="text" id="txt_sd<?php echo $cont;?>" class="caja_de_num" size="10" maxlength="10" value="<?php echo $datos["sueldo_diario"]; ?>"
						onChange="formatCurrency(this.value.replace(/,/g,''),this.name);operacionesPedido(<?php echo $cont;?>,'imp');" readonly="readonly"/>
					</td>
					<?php if(obtenerLugar($cumplimientos["catalogo_ubicaciones_id_ubicacion"]) == "ZARPEO MINERA FRESNILLO") {?>
					<td class=<?php echo $nom_clase; ?> align="center">
						<input name="txt_cumplimiento<?php echo $cont;?>" type="text" id="txt_cumplimiento<?php echo $cont;?>" class="caja_de_num" size="10" maxlength="10" value="<?php echo $datos["cumpl"];?>"
						onChange="establecerCumplimientos(<?php echo $cont;?>,<?php echo $bonif;?>,<?php echo $cumplimientos["cantidad"];?>,<?php echo $calidad_on;?>,<?php echo $datos["prod_fre"];?>);" onkeypress="return permite(event,'num');"/>
					</td>
					<?php } else if(obtenerLugar($cumplimientos["catalogo_ubicaciones_id_ubicacion"]) == "ZARPEO MINERA SAUCITO") { ?>
					<td class=<?php echo $nom_clase; ?> align="center">
						<input name="txt_cumplimiento<?php echo $cont;?>" type="text" id="txt_cumplimiento<?php echo $cont;?>" class="caja_de_num" size="10" maxlength="10" value="<?php echo $datos["cumpl"];?>"
						onChange="establecerCumplimientos(<?php echo $cont;?>,<?php echo $bonif;?>,<?php echo $cumplimientos["cantidad"];?>,<?php echo $calidad_on;?>,<?php echo $datos["prod_sau"];?>);" onkeypress="return permite(event,'num');"/>
					</td>
					<?php } else { ?>
					<td class=<?php echo $nom_clase; ?> align="center">
						<input name="txt_cumplimiento<?php echo $cont;?>" type="text" id="txt_cumplimiento<?php echo $cont;?>" class="caja_de_num" size="10" maxlength="10" value="<?php echo $datos["cumpl"];?>"
						onChange="establecerCumplimientos(<?php echo $cont;?>,<?php echo $bonif;?>,<?php echo $cumplimientos["cantidad"];?>,<?php echo $calidad_on;?>,<?php echo $cumplimientos["vol_ppto_mes"];?>);" onkeypress="return permite(event,'num');"/>
					</td>
					<?php } ?>
					<td class=<?php echo $nom_clase; ?> align="center">
						<input name="txt_calidadObra<?php echo $cont;?>" type="text" id="txt_calidadObra<?php echo $cont;?>" class="caja_de_num" size="10" maxlength="10" value="<?php echo $datos["calidad"]; ?>"
						onChange="formatCurrency(this.value.replace(/,/g,''),this.name);operacionesPedido(<?php echo $cont;?>,'imp');" readonly="readonly"/>
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						<input name="txt_bonificacion<?php echo $cont;?>" type="text" id="txt_bonificacion<?php echo $cont;?>" class="caja_de_num" size="10" maxlength="10" value="<?php echo $datos["bonif"]; ?>"
						onChange="formatCurrency(this.value.replace(/,/g,''),this.name);operacionesPedido(<?php echo $cont;?>,'imp');" readonly="readonly"/>
					</td>
					<?php $total = $sueldo_base; ?>
					<td class=<?php echo $nom_clase; ?> align="center">
						<input name="txt_total<?php echo $cont;?>" type="text" id="txt_total<?php echo $cont;?>" class="caja_de_num" size="10" maxlength="10" value="<?php echo $datos["total_pagado"]; ?>"
						onChange="formatCurrency(this.value.replace(/,/g,''),this.name);operacionesPedido(<?php echo $cont;?>,'imp');" readonly="readonly"/>
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						<textarea name="txt_comentario<?php echo $cont;?>" id="txt_comentario<?php echo $cont;?>" class="caja_texto" 
						rows="2" col="60" maxlength="300"><?php echo $datos["comentarios"]; ?></textarea>
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						<input name="txt_he<?php echo $cont;?>" type="text" id="txt_he<?php echo $cont;?>" class="caja_de_num" size="10" maxlength="10" <?php if($datos["horas_extra"]=="") echo "readonly='readonly'"; ?> 
						onChange="agregarBonificacion(<?php echo $cont?>,this.id);" onkeypress="return permite(event,'num');" value="<?php echo $datos["horas_extra"]; ?>" />
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						<input type="checkbox" name="ckb_8hrs<?php echo $cont?>" id="ckb_8hrs<?php echo $cont?>" onclick="agregarBonificacion(<?php echo $cont?>,this.id);" tabindex="<?php echo $cont?>"
						<?php if($datos["guarda_8hrs"]==1) echo "checked='true'"; ?> />
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						<input type="checkbox" name="ckb_12hrs<?php echo $cont?>" id="ckb_12hrs<?php echo $cont?>" onclick="agregarBonificacion(<?php echo $cont?>,this.id);" tabindex="<?php echo $cont?>"
						<?php if($datos["guarda_12hrs"]==1) echo "checked='true'"; ?> />
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						<input name="txt_bonificaciones<?php echo $cont;?>" type="text" id="txt_bonificaciones<?php echo $cont;?>" value="0" class="caja_de_num" 
						size="10" maxlength="10" onkeypress="return permite(event,'num',2);" onchange="agregarBonificacion(<?php echo $cont?>,'txt_he<?php echo $cont?>');"/>
					</td>
				</tr>
				<?php
				//mysql_close($conn_ger);
				?> <!-- <script type="text/javascript">document.getElementById("txt_cumplimiento<?php echo $cont;?>").onchange();</script> --><?php
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
		<input type='hidden' name='hdn_idnomina' value='<?php echo $_POST["cmb_nomina"]; ?>'/>
		<input type='hidden' name='hdn_area' value='<?php echo $_POST["hdn_area"]; ?>'/>
		<input name="btn_continuar" type="submit" class="botones" value="Continuar" onmouseover="window.status='';return true;" title="Registrar Nomina"/>
		<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Cancelar Registro de Nomina" onclick="location.href='frm_registrarNominaZarpeo.php'"/>
	</div>
	</form>
</body>
<?php 
	} else{
		guardarNomina();
	}
}//Cierre del Else donde se comprueba el usuario que esta registrado 
	
	function obtenerDatoBonificacion($concepto, $puesto){
		//Conectarse con la BD de Gerencia
		$conn = conecta("bd_gerencia");
		
		$bonif=0;
		
		$stm_sql = "SELECT cantidad
					FROM bonificaciones
					WHERE tipo = '$concepto'
					AND puesto = '$puesto'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			if($concepto == "CALIDAD")
				$bonif = $datos[0] / 18;
			else
				$bonif = $datos[0];
		}
		
		return $bonif;
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}//Fin de la funcion obtenerDatoBonificacion($concepto, $puesto)
	
	function obtenerDatos($id_emp, $fechaI, $fechaF){
		//Conectarse con la BD de Gerencia
		$conn = conecta("bd_gerencia");
		
		$stm_sql = "SELECT T3.id_empleados_empresa, SUM( T1.cantidad ) AS cantidad, T6.vol_ppto_mes, T2.puesto, T5.catalogo_ubicaciones_id_ubicacion
					FROM bitacora_zarpeo AS T1
					JOIN cuadrillas_zarpeo AS T2 ON ( T1.cuadrillas_id_cuadrillas = T2.id_cuadrilla
					AND T1.bitacora_id_bitacora = id_bitacora ) 
					JOIN bd_recursos.empleados AS T3 ON CONCAT( T3.nombre,  ' ', T3.ape_pat,  ' ', T3.ape_mat ) = T2.nom_empleado
					JOIN bitacora AS T4 ON T4.id_bitacora = T1.bitacora_id_bitacora
					JOIN cuadrillas AS T5 ON T5.id_cuadrillas = T2.id_cuadrilla
					JOIN presupuesto AS T6 ON ( T6.catalogo_ubicaciones_id_ubicacion = T5.catalogo_ubicaciones_id_ubicacion
					AND T6.periodo = T4.periodo ) 
					WHERE T1.fecha
					BETWEEN  '$fechaI'
					AND  '$fechaF'
					AND T3.id_empleados_empresa =  '$id_emp'
					GROUP BY T3.id_empleados_empresa
					ORDER BY T1.cuadrillas_id_cuadrillas, T1.bitacora_id_bitacora, T2.nom_empleado";
		$rs = mysql_query($stm_sql);
		if($datos = mysql_fetch_array($rs)){
			return $datos;
		} else {
			$datos = array("id_empleados_empresa"=>"0", "cantidad"=>"0", "vol_ppto_mes"=>"0", "puesto"=>"0", "catalogo_ubicaciones_id_ubicacion"=>"0");
			return $datos;
		}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}//Fin de la funcion obtenerDatos($id_emp, $fechaI, $fechaF)
	
	function obtenerAlcoholimetro($fechaI, $fechaF, $nom_emp){
		$alcohol = 0;
		
		$conn_ger = conecta("bd_gerencia");
		
		$stm_sql_ger = "SELECT id_nomina 
						FROM  `nominas` 
						WHERE  `fecha_registro` 
						BETWEEN  '$fechaI'
						AND  '$fechaF'";
		
		$rs_ger = mysql_query($stm_sql_ger);
		if($datos_ger = mysql_fetch_array($rs_ger)){
			do{
				$stm_sql_asis = "SELECT jueves, viernes, sabado, domingo, lunes, martes, miercoles
								FROM  `detalle_nominas` 
								WHERE  `id_nomina` LIKE  '$datos_ger[id_nomina]'
								AND  `id_empleados_empresa` = '$nom_emp'";
				
				$rs_asis = mysql_query($stm_sql_asis);
				if($datos_asis = mysql_fetch_array($rs_asis)){
					do{
						if($datos_asis["lunes"] == "B" || $datos_asis["martes"] == "B" || $datos_asis["miercoles"] == "B" || $datos_asis["jueves"] == "B" || $datos_asis["viernes"] == "B" || $datos_asis["sabado"] == "B" || $datos_asis["domingo"] == "B")
							$alcohol = 1;
					}while($datos_asis = mysql_fetch_array($rs_asis));
				}
			} while($datos_ger = mysql_fetch_array($rs_ger));
		}
		
		return $alcohol;
		
		mysql_close($conn_ger);
	}
	
	function obtenerLugar($id){
		$lugar = "";
		$conn = conecta("bd_gerencia");
		$stm_sql = "SELECT  `ubicacion` 
					FROM  `catalogo_ubicaciones` 
					WHERE  `id_ubicacion` LIKE  '$id'";
		
		$rs = mysql_query($stm_sql);
		
		if($datos=mysql_fetch_array($rs)){
			$lugar = "".$datos[0];
		}
		return $lugar;
		mysql_close($conn);
	}
?>
</html>