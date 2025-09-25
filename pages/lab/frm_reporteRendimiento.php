<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion de laboratorio
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_reporteRendimiento.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js" ></script>
	<script type="text/javascript" src="../../includes/validacionLaboratorio.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-generar {position:absolute; left:30px;top:146px;width:341px;height:20px;z-index:11;}
		#tabla-generarReporte {position:absolute; left:30px;top:190px;width:510px;height:155px;z-index:14;}
		#tabla-reporte-fecha{position:absolute; left:600px;top:190px;width:350px;height:155px;z-index:12;}
		#detalle_mezcla {position:absolute; left:30px;top:380px;width:906px;height:240px;z-index:17; overflow:scroll;}
		#detalle_mezclaSel {position:absolute; left:30px;top:190px;width:900px;height:110px;z-index:21;}
		#detalle_materiales {position:absolute; left:30px;top:350px;width:900px;height:200px;z-index:21; overflow:scroll; }
		#btn-regresardetalle {position:absolute;left:290px;top:603px;width:343px;height:40px;z-index:9;}
		#btns-regpdf { position: absolute; left:30px; top:600px; width:946px; height:40px; z-index:23; }
		#calendarioIni {position:absolute; left:791px; top:232px; width:30px; height:26px; z-index:18; }
		#calendarioFin {position:absolute; left:791px; top:268px; width:30px; height:26px; z-index:18; }
		-->
    </style>
</head>
<body>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-generar">Generar Reporte Rendimiento </div><?php 
	
	
	if(!isset($_POST['ckb_idRegRend'])){?>
        <fieldset class="borde_seccion" id="tabla-generarReporte" name="tabla-generarReporte">
        <legend class="titulo_etiqueta">Generar Reporte de Mezclas por Clave</legend>	
        <br>
        <form onSubmit="return valFormRepRendimiento(this);" name="frm_reporteRendimiento" method="post" action="frm_reporteRendimiento.php">
        <table cellpadding="5" cellspacing="5" class="tabla_frm">
            <tr>         
				<td width="143"><div align="right">*Clave de Mezcla</div></td>
				<td width="388"><?php 
				$res = cargarCombo("cmb_idMezcla","mezclas_id_mezcla","rendimiento","bd_laboratorio","Clave Mezcla","");
				if($res==0){ 
					echo "<label class='msje_correcto'>No Hay Resultados de Rendimientos de Mezclas Registrados</label>";
				}?>              
				</td>			
			</tr>  
			<tr>
				<td colspan="2" align="center">
					<input name="sbt_continuar" id="sbt_continuar" type="submit" class="botones" value="Continuar" onmouseover="window.status='';return true" 
                    title="Continuar Mezclas"/>
                    &nbsp;&nbsp;&nbsp;
                    <input name="btn_regresar" type="button" class="botones" value="Regresar" onclick="location.href='menu_reportes.php';"
                    title="Regresar al men&uacute; de Reportes"/>
                </td>
            </tr>
        </table>
        </form>
        </fieldset><?php
		//Fieldset para la manipulación por medio de fecha?>
		
		<fieldset class="borde_seccion" id="tabla-reporte-fecha">
		<legend class="titulo_etiqueta">Reporte por Fecha</legend>	
		<br>
		<form  onsubmit="return valFormRepRendimientoFecha(this);" name="frm_reporteFecha"  method="post" action="frm_reporteRendimiento.php">
		<table cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td><div align="right">Fecha</div></td>
				<td width="116">
					<input name="txt_fechaIni" id="txt_fechaIni" type="text"  readonly="readonly" 
					value="<?php echo date("d/m/Y", strtotime("-30 day"));  ?>" size="10" width="90"/>					
				</td>
			</tr>
			<tr>
				<td><div align="right">Fecha</div></td>
				<td width="116">
					<input name="txt_fechaFin" id="txt_fechaFin" type="text"  readonly="readonly" 
					value="<?php echo date("d/m/Y"); ?>" size="10" width="90"/>					
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<div align="center"> 
						<input name="sbt_continuar2" id="sbt_continuar2" type="submit" class="botones" value="Continuar" title="Continuar"
						onMouseOver="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;
						<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Reportes" 
						onmouseover="window.status='';return true" onclick="location.href='menu_reportes.php'" />
					</div>
				</td>
		  </tr>
		</table>
		</form>
		</fieldset>
		<div id="calendarioIni">
			<input name="fechaIni" type="image" id="fechaIni" onclick="displayCalendar(document.frm_reporteFecha.txt_fechaIni,'dd/mm/yyyy',this)"
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"
			width="25" height="25" border="0" />
		</div>
		<div id="calendarioFin">
			<input name="fechaIni" type="image" id="fechaIni" onclick="displayCalendar(document.frm_reporteFecha.txt_fechaFin,'dd/mm/yyyy',this)"
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"
			width="25" height="25" border="0" />
		</div><?php
		
		if(isset($_POST['sbt_continuar']) || isset($_POST['sbt_continuar2'])){?>
            <form onSubmit="return valFormConsultarDetMezcla(this);" name="frm_detalleMezcla" method="post" action="frm_reporteRendimiento.php"><?php
				if(isset($_POST['sbt_continuar'])){?>
					<input type="hidden" name="cmb_idMezcla" value="<?php echo $_POST['cmb_idMezcla'];?>"/>
					<input type="hidden" name="sbt_continuar" value="Continuar"/><?php
				}	
				if(isset($_POST['sbt_continuar2'])){?>
					<input type="hidden" name="txt_fechaIni" value="<?php echo $_POST['txt_fechaIni'];?>"/>
					<input type="hidden" name="txt_fechaFin" value="<?php echo $_POST['txt_fechaFin'];?>"/>
					<input type="hidden" name="sbt_continuar2" value="Continuar"/><?php
				}?>		
								
                <div id='detalle_mezcla' class='borde_seccion2' align="center"><?php
                    $control = mostrarRendMezclas();?>
                </div>
            </form><?php
		}// FIN f(isset($_POST['sbt_continuar']) || isset($_POST['sbt_continuar2']))
	}//FIN if(!isset($_POST['ckb_idRegRend']))	
	else{
		//El valor del atributo action se define en la funcion solicitarDatos(this)?>
		<form onsubmit="return solicitarDatos(this);" name="frm_exportarDatos" method="post" action="">			    
			<div id="detalle_mezclaSel" class="borde_seccion2" align="center"><?php
				//Mostrar el detalle  Seleccionado
				mostrarDetalleMezcla();?>
			</div>
			<div id="detalle_materiales" class="borde_seccion2" align="center"><?php
				mostrarDetalleMateriales();?> 
			</div>
		</form><?php
			
		// recuperar los valores de la consulta para que el boton que nos permite regresar a la consulta de las mezclas?>
		<div id="btn-regresardetalle" align="left">
            <form name="" action="frm_reporteRendimiento.php" method="post"> <?php
                if (isset($_POST['hdn_continuar'])){?>
					<input type="hidden" name="cmb_idMezcla" value="<?php echo $_POST['hdn_idMezcla'] ?>"/>
                    <input type="hidden" name="sbt_continuar" value="<?php echo $_POST['hdn_continuar'] ?>"/><?php
                }
				else {?>
					<input type="hidden" name="txt_fechaIni" value="<?php echo $_POST['hdn_idFechaIni'];?>"/>
					<input type="hidden" name="txt_fechaFin" value="<?php echo $_POST['hdn_idFechaFin'];?>"/>
					<?php if(isset($_POST['hdn_continuar2']))
					$boton=$_POST['hdn_continuar2'];
					else 
					$boton=$_POST['sbt_continuar2'];?>
                    <input type="hidden" name="sbt_continuar2" value="<?php echo $boton; ?>"/><?php
                }?>				
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="sbt_regresarRepMez" type="submit" class="botones" value="Regresar" title="Regresar a la Consulta de Mezclas" 
				onmouseover="window.estatus='';return true" id="sbt_regresarRepMez"/>
			
            </form>
</div><?php		
	}?>
</body><?php
 }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>