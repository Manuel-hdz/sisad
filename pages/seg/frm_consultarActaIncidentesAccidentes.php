<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Seguridad Industrial
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_consultarActaIncidentesAccidentes.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionSeguridad.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;	width:423px;height:20px;z-index:11;}
		#tabla-consultarActaFecha {position:absolute;left:30px;top:190px;width:347px;height:155px;z-index:12;}
		#tabla-consultarActaId {position:absolute;left:482px;top:190px;width:347px;height:155px;z-index:12;}
		#calendario{position:absolute;left:242px;top:233px;width:30px;height:26px;z-index:13;}
		#calendario2{position:absolute;left:243px;top:271px;width:30px;height:26px;z-index:14;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-registrar">Consultar Informe de Accidentes e Incidentes </div>
	<fieldset class="borde_seccion" id="tabla-consultarActaFecha" name="tabla-agregarActa">
    <legend class="titulo_etiqueta">Consultar Informe por Fecha de Incidente/Accidente </legend>
    <form onsubmit="return valFormFechasRecSeg(this);"name="frm_consultarActa" id="frm_consultarActa" method="post" action="frm_consultarActaIncidentesAccidentes2.php">
    <br />
    <table width="349" height="127"  cellpadding="5" cellspacing="5" class="tabla_frm">
    	<tr>
        	<td><div align="right">*Fecha Inicio </div></td>
          	<td width="219">
				<input name="txt_fechaIni" type="text" id="txt_fechaIni" size="10" maxlength="15" value="<?php echo date("d/m/Y",strtotime("-30 day"));?>" 
				readonly="readonly" class="caja_de_texto"/>
          	</td>
        </tr>
        <tr>
        	<td width="93"><div align="right">*Fecha Fin </div></td>
          	<td>
				<input name="txt_fechaFin" type="text" id="txt_fechaFin" size="10" maxlength="15" value="<?php echo date("d/m/Y");?>" 
				readonly="readonly" class="caja_de_texto"/>
          	</td>
        </tr>
        <tr>
         	<td height="45" colspan="9">
				<div align="center">
            		<input name="sbt_consultar" type="submit" class="botones" id="sbt_consultar" value="Consultar" title="Consultar Informe Accidentes/Incidentes"
					onmouseover="window.status='';return true"/>
            		&nbsp;&nbsp;&nbsp;
            		<input name="btn_regresar" type="button" class="botones"  value="Regresar" title="Regresar al Men&uacute; Incidentes/Accidentes" 
					onmouseover="window.status='';return true" onclick="location.href='menu_actaIncidentesAccidentes.php'"/>
          		</div>
			</td>
        </tr>
     </table>
    </form>
	</fieldset>
	
	<div id="calendario">
		<input name="calendario" type="image" id="calendario5" onclick="displayCalendar(document.frm_consultarActa.txt_fechaIni,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png"  title="Seleccione una fecha" align="absbottom" width="25" height="25" 
		border="0"/>
	</div>
	<div id="calendario2">
		<input name="calendario2" type="image" id="calendario22" onclick="displayCalendar(document.frm_consultarActa.txt_fechaFin,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png"  title="Seleccione una fecha" align="absbottom" width="25" height="25" 
		border="0"/>
	</div>
	
	<fieldset class="borde_seccion" id="tabla-consultarActaId" name="tabla-consultarActaId">
    <legend class="titulo_etiqueta">Consultar  por Tipo de Informe </legend>
    <form onsubmit="return valFormActaTipo(this);" name="frm_consultarActa2" id="frm_consultarActa2" method="post" action="frm_consultarActaIncidentesAccidentes2.php">
     <br />
     <table width="349" height="127"  cellpadding="5" cellspacing="5" class="tabla_frm">
     	<tr>
        	<td><div align="right">*Tipo de Informe </div></td>
          	<td width="184">
            	<select name="cmb_tipo" id="cmb_tipo" size="1" class="combo_box">
                	<option value="">Tipo Informe</option>
					<option value="ACCIDENTE">ACCIDENTE</option>
					<option value="INCIDENTE">INCIDENTE</option>
				</select>
       	  </td>
        </tr>
        <tr>
        	<td width="128">&nbsp;</td>
          	<td>&nbsp;</td>
        </tr>
        <tr>
        	<td height="45" colspan="9">
				<div align="center">
            		<input name="sbt_consultar" type="submit" class="botones" id="sbt_consultar" value="Consultar" title="Consultar Informe Accidentes/Incidentes"
					onmouseover="window.status='';return true"/>
            		&nbsp;&nbsp;&nbsp;
            		<input name="btn_regresar" type="button" class="botones"  value="Regresar" title="Regresar al Men&uacute; Incidentes/Accidentes"
					onmouseover="window.status='';return true"
					onclick="location.href='menu_actaIncidentesAccidentes.php'"/>
          		</div>
			</td>
        </tr>
    </table>
    </form>
</fieldset>
	

</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>