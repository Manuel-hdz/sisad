<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Topografía
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_consultasExternas.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionTopografia.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
			#titulo-consultar {position:absolute;left:30px;top:146px; width:210px;height:20px;z-index:11;}
			#tabla-consultarPlano {position:absolute;left:30px;top:190px;width:436px;height:162px;z-index:12;}
			#calendario-uno{position:absolute;left:265px;top:235px;width:30px;height:26px;z-index:13;}
			#calendario-dos{position:absolute;left:265px;top:271px;width:30px;height:26px;z-index:13;}
			#tabla-planos { position:absolute; left:30px; top:380px; width:945px; height:170px; z-index:21; overflow:scroll; }
			#btns-regpdf { position: absolute; left:30px; top:600px; width:945px; height:40px; z-index:23; }
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-consultar">Topograf&iacute;a - Consultar Planos</div>
	<fieldset class="borde_seccion" id="tabla-consultarPlano" name="tabla-consultarPlano">
	<legend class="titulo_etiqueta">Seleccione Fechas del Plano</legend>	
	<br>
	<form name="frm_consultarPlanoFecha" method="post" action="frm_consultarTopografia.php"  onsubmit="return valFormConsultarPlano(this);">
		<table width="444"  cellpadding="5" cellspacing="5" class="tabla_frm">
      		<tr>
		  		<td width="104"><div align="right">Fecha Inicio</div></td>
       	  		<td width="276">
					<input name="txt_fechaIni" type="text" id="txt_fechaIni" size="10" maxlength="15" 
		  			value="<?php echo date("d/m/Y", strtotime("-30 day"));?>" readonly="readonly"/>
				</td>
        	</tr>
          		<td width="104"><div align="right">Fecha Fin </div></td>
          		<td><input name="txt_fechaFin" type="text" id="txt_fechaFin" size="10" maxlength="15" value="<?php echo date("d/m/Y");?>" readonly="readonly"/></td>
        	</tr>
			<tr>
       	  		<td colspan="4">
					<div align="center"> 
						<input name="sbt_consultar" type="submit" class="botones" id= "sbt_guardar" value="Consultar" title="Consultar Planos"
						onMouseOver="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="reset" name="btn_limpiar" class="botones" value="Restablecer" title="Restablece el Formulario"/>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Inicio" 
						onmouseover="window.status='';return true" onclick="location.href='inicio_desarrollo.php'" />
					</div>
				</td>
			</tr>
   	  </table>
	</form>
</fieldset>
   <div id="calendario-uno">
   		<input name="calendario_planos" type="image" id="calendario_planos" onclick="displayCalendar (document.frm_consultarPlanoFecha.txt_fechaIni,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" 
		title="Seleccione Fecha de Inicio" />
	</div>
	<div id="calendario-dos">
   		<input name="calendario_planos" type="image" id="calendario_planos" onclick="displayCalendar (document.frm_consultarPlanoFecha.txt_fechaFin,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" title="Seleccione Fecha de Fin" />
	</div>
<?php 
//Verificamos que el boton consultar sea presionado; si es asi mostrar los Planos
	if(isset($_POST["sbt_consultar"])){?>
		<form name="frm_consultarPlanos" method="post" ><?php 
			echo"<div id='tabla-planos' class='borde_seccion2' align='center'>";
					mostrarPlanos();
				echo "</div>";?>
				<div id="btns-regpdf" align="center">
					<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Inicio" 
        	        onMouseOver="window.status='';return true" onclick="location.href='inicio_desarrollo.php'" />
				</div>
		</form><?php }?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>