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
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
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
	<div class="titulo_barra" id="titulo-detalle" style="width:300px;">N&oacute;mina de Mantenimiento Superficie</div>
	<?php if(!isset($_POST['btn_continuar']) && !isset($_POST['btn_avance'])){ ?>
	<form name="registrar_nomina" action="frm_registrarNominaBonoEspecial2.php" method="post">
	<div id="detalles_pedido" class="borde_seccion2" style="height:400px;">
    <?php
		//Realizar la conexion a la BD de Gerencia
		$conn = conecta("bd_recursos");	
		//Escribimos la consulta a realizarse
		
		$stm_sql = "SELECT  `id_empleados_empresa` , CONCAT(  `nombre` ,  ' ',  `ape_pat` ,  ' ',  `ape_mat` ) AS nombre,  `sueldo_diario` 
					FROM  `empleados` 
					WHERE  `id_cuentas` =  'CUEN002'
					AND `estado_actual` = 'ALTA'
					AND (`area` LIKE '%ZARPEO FRESNILLO%' OR `area` LIKE '%ZARPEO SAUCITO%')
					ORDER BY `nombre`";
					
		//modFecha($_POST["txt_fechaIni"],3)
		$msje="Registro de N&oacute;mina Mantenimiento Superficie del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
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
							<th class='nombres_columnas' align='center' rowspan='2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;J&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
							<th class='nombres_columnas' align='center' rowspan='2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;V&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
							<th class='nombres_columnas' align='center' rowspan='2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;S&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
							<th class='nombres_columnas' align='center' rowspan='2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;D&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
							<th class='nombres_columnas' align='center' rowspan='2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;L&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
							<th class='nombres_columnas' align='center' rowspan='2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;M&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
							<th class='nombres_columnas' align='center' rowspan='2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;M&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
							<th class='nombres_columnas' align='center' rowspan='2'>E</th>
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
				?>
				<tr>
					<td class=<?php echo $nom_clase; ?> align="center">
						<input name="txt_id_emp<?php echo $cont;?>" type="text" id="txt_id_emp<?php echo $cont;?>" class="caja_de_num" size="10" maxlength="10" 
						value="<?php echo $datos["id_empleados_empresa"]; ?>" readonly="readonly"/>
					</td>
					<td class=<?php echo $nom_clase; ?> align="center"><?php echo $datos["nombre"]; ?></td>
					<td class=<?php echo $nom_clase; ?> align="center">
						A <input type="checkbox" name="ckb_jueves<?php echo $cont?>" id="ckb_jueves<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,0,'ckb_juevesI','ckb_juevesAL');" tabindex="<?php echo $cont?>" checked="true"/>
						I <input type="checkbox" name="ckb_juevesI<?php echo $cont?>" id="ckb_juevesI<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,1,'ckb_jueves','ckb_juevesAL');" tabindex="<?php echo $cont?>"/>
						<br>
						D <input type="checkbox" name="ckb_jueves<?php echo $cont?>D" id="ckb_jueves<?php echo $cont?>D" onclick="establecerAsistencia(<?php echo $cont?>,'ckb_jueves<?php echo $cont?>',3,'ckb_juevesI','ckb_juevesAL');" tabindex="<?php echo $cont?>"/>
						<font style="color:darkred;">AL</font> <input type="checkbox" name="ckb_juevesAL<?php echo $cont?>" id="ckb_juevesAL<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,'ckb_jueves<?php echo $cont?>',2,'ckb_juevesI','ckb_juevesAL');" tabindex="<?php echo $cont?>"/>
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						A <input type="checkbox" name="ckb_viernes<?php echo $cont?>" id="ckb_viernes<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,0,'ckb_viernesI','ckb_viernesAL');" tabindex="<?php echo $cont?>" checked="true"/>
						I <input type="checkbox" name="ckb_viernesI<?php echo $cont?>" id="ckb_viernesI<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,1,'ckb_viernes','ckb_viernesAL');" tabindex="<?php echo $cont?>"/>
						<br>
						D <input type="checkbox" name="ckb_viernes<?php echo $cont?>D" id="ckb_viernes<?php echo $cont?>D" onclick="establecerAsistencia(<?php echo $cont?>,'ckb_viernes<?php echo $cont?>',3,'ckb_viernesI','ckb_viernesAL');" tabindex="<?php echo $cont?>"/>
						<font style="color:darkred;">AL</font> <input type="checkbox" name="ckb_viernesAL<?php echo $cont?>" id="ckb_viernesAL<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,'ckb_viernes<?php echo $cont?>',2,'ckb_viernesI','ckb_viernesAL');" tabindex="<?php echo $cont?>"/>
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						A <input type="checkbox" name="ckb_sabado<?php echo $cont?>" id="ckb_sabado<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,0,'ckb_sabadoI','ckb_sabadoAL');" tabindex="<?php echo $cont?>" checked="true"/>
						I <input type="checkbox" name="ckb_sabadoI<?php echo $cont?>" id="ckb_sabadoI<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,1,'ckb_sabado','ckb_sabadoAL');" tabindex="<?php echo $cont?>"/>
						<br>
						D <input type="checkbox" name="ckb_sabado<?php echo $cont?>D" id="ckb_sabado<?php echo $cont?>D" onclick="establecerAsistencia(<?php echo $cont?>,'ckb_sabado<?php echo $cont?>',3,'ckb_sabadoI','ckb_sabadoAL');" tabindex="<?php echo $cont?>"/>
						<font style="color:darkred;">AL</font> <input type="checkbox" name="ckb_sabadoAL<?php echo $cont?>" id="ckb_sabadoAL<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,'ckb_sabado<?php echo $cont?>',2,'ckb_sabadoI','ckb_sabadoAL');" tabindex="<?php echo $cont?>"/>
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						A <input type="checkbox" name="ckb_domingo<?php echo $cont?>" id="ckb_domingo<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,0,'ckb_domingoI','ckb_domingoAL');" tabindex="<?php echo $cont?>" checked="true"/>
						I <input type="checkbox" name="ckb_domingoI<?php echo $cont?>" id="ckb_domingoI<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,1,'ckb_domingo','ckb_domingoAL');" tabindex="<?php echo $cont?>"/>
						<br>
						D <input type="checkbox" name="ckb_domingo<?php echo $cont?>D" id="ckb_domingo<?php echo $cont?>D" onclick="establecerAsistencia(<?php echo $cont?>,'ckb_domingo<?php echo $cont?>',3,'ckb_domingoI','ckb_domingoAL');" tabindex="<?php echo $cont?>"/>
						<font style="color:darkred;">AL</font> <input type="checkbox" name="ckb_domingoAL<?php echo $cont?>" id="ckb_domingoAL<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,'ckb_domingo<?php echo $cont?>',2,'ckb_domingoI','ckb_domingoAL');" tabindex="<?php echo $cont?>"/>
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						A <input type="checkbox" name="ckb_lunes<?php echo $cont?>" id="ckb_lunes<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,0,'ckb_lunesI','ckb_lunesAL');" tabindex="<?php echo $cont?>" checked="true"/>
						I <input type="checkbox" name="ckb_lunesI<?php echo $cont?>" id="ckb_lunesI<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,1,'ckb_lunes','ckb_lunesAL');" tabindex="<?php echo $cont?>"/>
						<br>
						D <input type="checkbox" name="ckb_lunes<?php echo $cont?>D" id="ckb_lunes<?php echo $cont?>D" onclick="establecerAsistencia(<?php echo $cont?>,'ckb_lunes<?php echo $cont?>',3,'ckb_lunesI','ckb_lunesAL');" tabindex="<?php echo $cont?>"/>
						<font style="color:darkred;">AL</font> <input type="checkbox" name="ckb_lunesAL<?php echo $cont?>" id="ckb_lunesAL<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,'ckb_lunes<?php echo $cont?>',2,'ckb_lunesI','ckb_lunesAL');" tabindex="<?php echo $cont?>"/>
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						A <input type="checkbox" name="ckb_martes<?php echo $cont?>" id="ckb_martes<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,0,'ckb_martesI','ckb_martesAL');" tabindex="<?php echo $cont?>" checked="true"/>
						I <input type="checkbox" name="ckb_martesI<?php echo $cont?>" id="ckb_martesI<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,1,'ckb_martes','ckb_martesAL');" tabindex="<?php echo $cont?>"/>
						<br>
						D <input type="checkbox" name="ckb_martes<?php echo $cont?>D" id="ckb_martes<?php echo $cont?>D" onclick="establecerAsistencia(<?php echo $cont?>,'ckb_martes<?php echo $cont?>',3,'ckb_martesI','ckb_martesAL');" tabindex="<?php echo $cont?>"/>
						<font style="color:darkred;">AL</font> <input type="checkbox" name="ckb_martesAL<?php echo $cont?>" id="ckb_martesAL<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,'ckb_martes<?php echo $cont?>',2,'ckb_martesI','ckb_martesAL');" tabindex="<?php echo $cont?>"/>
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						A <input type="checkbox" name="ckb_miercoles<?php echo $cont?>" id="ckb_miercoles<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,0,'ckb_miercolesI','ckb_miercolesAL');" tabindex="<?php echo $cont?>" checked="true"/>
						I <input type="checkbox" name="ckb_miercolesI<?php echo $cont?>" id="ckb_miercolesI<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,this.id,1,'ckb_miercoles','ckb_miercolesAL');" tabindex="<?php echo $cont?>"/>
						<br>
						D <input type="checkbox" name="ckb_miercoles<?php echo $cont?>D" id="ckb_miercoles<?php echo $cont?>D" onclick="establecerAsistencia(<?php echo $cont?>,'ckb_miercoles<?php echo $cont?>',3,'ckb_miercolesI','ckb_miercolesAL');" tabindex="<?php echo $cont?>"/>
						<font style="color:darkred;">AL</font> <input type="checkbox" name="ckb_miercolesAL<?php echo $cont?>" id="ckb_miercolesAL<?php echo $cont?>" onclick="establecerAsistencia(<?php echo $cont?>,'ckb_miercoles<?php echo $cont?>',2,'ckb_miercolesI','ckb_miercolesAL');" tabindex="<?php echo $cont?>"/>
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						<input type="checkbox" name="ckb_extra<?php echo $cont?>" id="ckb_extra<?php echo $cont?>" onclick="desbloquearCamposNomina(this.id,<?php echo $cont?>);" tabindex="<?php echo $cont?>"/>
					</td>
					<?php $sueldo_base = $datos["sueldo_diario"] * 7; ?>
						<input name="txt_sb<?php echo $cont;?>" type="hidden" id="txt_sb<?php echo $cont;?>" class="caja_de_num" size="10" maxlength="10" value="<?php echo $sueldo_base; ?>"
						onChange="formatCurrency(this.value.replace(/,/g,''),this.name);operacionesPedido(<?php echo $cont;?>,'imp');" readonly="readonly"/>
					
						<input name="txt_sd<?php echo $cont;?>" type="hidden" id="txt_sd<?php echo $cont;?>" class="caja_de_num" size="10" maxlength="10" value="<?php echo $datos["sueldo_diario"]; ?>"
						onChange="formatCurrency(this.value.replace(/,/g,''),this.name);operacionesPedido(<?php echo $cont;?>,'imp');" readonly="readonly"/>
					
					<?php $total = $sueldo_base; ?>
						<input name="txt_total<?php echo $cont;?>" type="hidden" id="txt_total<?php echo $cont;?>" class="caja_de_num" size="10" maxlength="10" value="<?php echo $total; ?>"
						onChange="formatCurrency(this.value.replace(/,/g,''),this.name);operacionesPedido(<?php echo $cont;?>,'imp');" readonly="readonly"/>
					
					<td class=<?php echo $nom_clase; ?> align="center">
						<textarea name="txt_comentario<?php echo $cont;?>" id="txt_comentario<?php echo $cont;?>" class="caja_texto" 
						rows="2" col="60" maxlength="300"></textarea>
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						<input name="txt_he<?php echo $cont;?>" type="text" id="txt_he<?php echo $cont;?>" class="caja_de_num" size="10" maxlength="10" readonly="readonly"
						onChange="agregarBonificacion(<?php echo $cont?>,this.id);" onkeypress="return permite(event,'num');"/>
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						<input type="checkbox" name="ckb_8hrs<?php echo $cont?>" id="ckb_8hrs<?php echo $cont?>" onclick="agregarBonificacion(<?php echo $cont?>,this.id);" tabindex="<?php echo $cont?>"/>
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						<input type="checkbox" name="ckb_12hrs<?php echo $cont?>" id="ckb_12hrs<?php echo $cont?>" onclick="agregarBonificacion(<?php echo $cont?>,this.id);" tabindex="<?php echo $cont?>"/>
					</td>
					<td class=<?php echo $nom_clase; ?> align="center">
						<input name="txt_bonificaciones<?php echo $cont;?>" type="hidden" id="txt_bonificaciones<?php echo $cont;?>" value="0" class="caja_de_num" 
						size="10" maxlength="10" onkeypress="return permite(event,'num',2);" onchange="agregarBonificacion(<?php echo $cont?>,'txt_he<?php echo $cont?>');"/>
					</td>
				</tr>
				<?php
				//mysql_close($conn_ger);
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
		guardarNomina();
	}
}//Cierre del Else donde se comprueba el usuario que esta registrado 
?>
</html>