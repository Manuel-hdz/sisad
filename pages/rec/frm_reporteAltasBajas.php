<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">
<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Recursos Humanos
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
	//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Archivo que incluye las operaciones para realizar el reporte de Altas y Bajas
		include ("op_reporteAltasBajas.php");?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
    <style type="text/css">
		<!--				
			#titulo-consultar-empleado {position:absolute; left:30px; top:146px; width:228px; height:25px; z-index:11; }
			#tabla-consultar-empleados2 {position:absolute; left:23px; top:200px; width:436px; height:136px; z-index:12;}
			#tabla-altas { position:absolute; left:30px; top:190px; width:945px; height:150px; z-index:13; overflow:scroll; }
			#tabla-bajas { position:absolute; left:30px; top:400px; width:945px; height:150px; z-index:14; overflow:scroll; }
			#btns-regpdf { position: absolute; left:30px; top:620px; width:945px; height:40px; z-index:15; }
			#calendar-tres {position:absolute; left:223px; top:242px; width:30px; height:26px; z-index:16; }
			#calendar-cuatro {position:absolute; left:432px; top:242px; width:30px; height:26px; z-index:17; }
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultar-empleado">Reporte de Altas vs Bajas </div>
		<?php 
		
		//Verificamos si viene definido en el post el boton consultar
		if(isset($_POST["sbt_consultar"])){
			echo"<div align='center' id='tabla-altas' class='borde_seccion2' width='100%' >";
				//Si viene definido el boton; mostrar el reporte de Altas de Empleados
				$altas=reporteAltas();
			echo "</div>";
			echo"<div align='center' id='tabla-bajas' class='borde_seccion2' width='100%' >";
				//Si viene definido el boton; mostrar el reporte de Bajas de Empleados
				$bajas=reporteBajas();
			echo "</div>";
			echo"<div id='btns-regpdf' align='center'>
				<table width='22%' cellpadding='12'>
					<tr>
						<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						<td width='18%' align='center'>";
							if(isset($_SESSION['datosGrapAltas'])&&isset($_SESSION['datosGrapBajas'])){?>						
								<input type="button" name="btn_verGrafica" class="botones" value="Ver Grafica" title="Ver Gr&aacute;fica de Altas VS Bajas" 
								onClick="javascript:window.open('verGraficas.php?graph=Altas','_blank','top=100, left=250, width=1035, height=723, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" />
						</td>
					</tr>
				</table>
			</div>
				
	<?php	}
 	}
	  else{ ?> 
			</form>
			<fieldset class="borde_seccion" id="tabla-consultar-empleados2">
			<legend class="titulo_etiqueta">Reporte de Altas vs Bajas por Fecha </legend>	
			<br>
			<form  method="post" name="frm_reporteFecha" id="frm_reporteFecha"  onsubmit=" return valFormRptAltasBajasFecha(this);" >
			<table width="444" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
				<?php
				if(isset($_SESSION["datosGrapBajas"])){
					unset($_SESSION["datosGrapBajas"]);
				}
				if(isset($_SESSION["datosGrapAltas"])){
					unset($_SESSION["datosGrapAltas"]);
				}?>
					<td width="73"><div align="right">Fecha Inicio</div></td>
				 	<td width="90">
						<input name="txt_fechaIni" id="txt_fechaIni" readonly="readonly" type="text" 
						value="<?php echo date("d/m/Y", strtotime("-15 day")); ?>" size="10"  width="90"/>			     
				  	</td>
					<td width="88"><div align="right">Fecha Fin </div></td>
					<td width="128">
						<input name="txt_fechaFin" id="txt_fechaFin" type="text"  readonly="readonly" 
						value="<?php echo date("d/m/Y"); ?>" size="10"  width="90"/>
				  	</td>
				</tr>
			</table>
			<div align="center">
				<p>
					<input name="sbt_consultar" type="submit" class="botones_largos" id="sbt_consultar" value="Generar Reporte"
					onmouseover="window.status='';return true;" title="Generar Reporte Altas VS Bajas"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Reportes" 
					onMouseOver="window.status='';return true" onclick="location.href='menu_reportes.php'" />
				</p>
			</div>
			</form>
			</fieldset>
			<div id="calendar-tres">
				<input name="fechaIni" type="image" id="fechaIni" onclick="displayCalendar(document.frm_reporteFecha.txt_fechaIni,'dd/mm/yyyy',this)"
				onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"
				width="25" height="25" border="0" />
			</div>
            <div id="calendar-cuatro">
				<input name="fechaFin" type="image" id="fechaFin" onclick="displayCalendar(document.frm_reporteFecha.txt_fechaFin,'dd/mm/yyyy',this)"
				onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"
				width="25" height="25" border="0" />
			</div>
	<?php }?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>