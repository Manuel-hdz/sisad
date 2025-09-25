<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a estos Reportes
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_gestionarBitUtilitario.php");
		?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validaciondesarrollo.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
			#titulo-consultar{position:absolute;left:30px;top:146px; width:387px;height:20px;z-index:11;}
			#tabla-fechaReporte{position:absolute;left:30px;top:190px;width:436px;height:175px;z-index:12;}
			#calendario-uno{position:absolute;left:262px;top:233px;width:30px;height:26px;z-index:13;}
			#calendario-dos{position:absolute;left:262px;top:269px;width:30px;height:26px;z-index:13;}
			#tabla-resultados{position:absolute; left:30px; top:190px; width:950px; height:420px; z-index:12; padding:15px; padding-top:0px; overflow:scroll; z-index:14;}
			#btns{position:absolute;left:30px;top:650px;width:950px;height:37px;z-index:15;}
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-consultar">Modificar Registro de la Bit&aacute;cora de Equipo Utilitario</div><?php 
	
	
	//Si no se ha presionado Boton, mostrar el Formulario
	if (!isset($_POST["sbt_consultar"])){?>
		<fieldset class="borde_seccion" id="tabla-fechaReporte" name="tabla-fechaReporte">
		<legend class="titulo_etiqueta">Seleccionar Fechas</legend>	
		<br>
		<form name="frm_consultarBitUtilitario" method="post" action="frm_modBitUtilitario.php"  onsubmit="return valFormConsultarReporte(this);">
			<table width="444"  cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td width="104"><div align="right">Fecha Inicio</div></td>
					<td width="276">
						<input name="txt_fechaIni" type="text" id="txt_fechaIni" size="10" maxlength="15" 
						value="<?php echo date("d/m/Y", strtotime("-30 day"));?>" readonly="readonly"/>
					</td>
				</tr>
					<td width="104"><div align="right">Fecha Fin</div></td>
					<td><input name="txt_fechaFin" type="text" id="txt_fechaFin" size="10" maxlength="15" value="<?php echo date("d/m/Y");?>" readonly="readonly"/></td>
				</tr>
				<tr>
					<td><div align="right">Equipo</div></td>
					<td><?php								
						$conn = conecta("bd_mantenimiento");//Conectarse a la Base de Datos
						$result = mysql_query("SELECT id_equipo, nom_equipo FROM equipos WHERE (familia = 'BULLDOZER' OR  familia = 'RETROS') AND disponibilidad = 'ACTIVO' 
												ORDER BY id_equipo");						
						if($registro=mysql_fetch_array($result)){?>				
							<select name="cmb_equipos" id="cmb_equipos" class="combo_box" tabindex="4">
								<option value="">Equipo</option><?php															 
								do{?>
									<option value="<?php echo $registro['id_equipo']; ?>" title="<?php echo $registro['id_equipo']; ?>"><?php 
										echo $registro['id_equipo']; ?>
									</option><?php
								}while($registro=mysql_fetch_array($result))?>
							</select><?php
						} else {?>
							<span class="msje_correcto">No Hay Equipos Registrados</span><?php
						}
						mysql_close($conn);//Cerrar la conexion con la BD ?>
					</td>
				</tr>
				<tr>
					<td colspan="4">
						<div align="center"> 
							<input name="sbt_consultar" type="submit" class="botones" id= "sbt_guardar" value="Consultar" 
							title="Consultar Registros de la Bit&aacute;cora de Equipo Utilitario"
							onMouseOver="window.status='';return true"/>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="reset" name="btn_limpiar" class="botones" value="Restablecer" title="Restablece el Formulario"/>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Equipo Utilitario" 
							onmouseover="window.status='';return true" onclick="location.href='menu_bitUtilitario.php'" />
						</div>
					</td>
				</tr>
		  </table>
		</form>
		</fieldset>
	   <div id="calendario-uno">
			<input name="calendario_uno" type="image" id="calendario_uno" onclick="displayCalendar (document.frm_consultarBitUtilitario.txt_fechaIni,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" 
			title="Seleccione Fecha de Inicio" />
		</div>
		<div id="calendario-dos">
			<input name="calendario_dos" type="image" id="calendario_dos" onclick="displayCalendar (document.frm_consultarBitUtilitario.txt_fechaFin,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" title="Seleccione Fecha de Fin" />
		</div><?php 
	}
	else{?>
		<div id="tabla-resultados" class="borde_seccion2">
			<form name="frm_seleccionarRegistro" method="post" action="frm_modBitUtilitario2.php"><?php
				mostrarRegUtilitario();?>
			</form>
		</div>
		<div id="btns" align="center">
			<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar a Seleccionar otras Fechas" 
			onMouseOver="window.status='';return true" onclick="location.href='frm_modBitUtilitario.php'" />
		</div><?php 
	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>