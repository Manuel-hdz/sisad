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
		//Archivo que incluye la operación de consultar Empleado
		include ("op_consultarNominaInterna.php");
		//Archivo requerido para la consulta de Kardex
		include ("op_registrarNominaInterna.php");?>
		
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="includes/ajax/obtenerDatosBonoNomina.js"></script>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
    <style type="text/css">
		<!--				
			#titulo-consultar-nomina {position:absolute; left:30px; top:146px; width:228px; height:25px; z-index:11; }
			#tabla-consultar-nomina {position:absolute; left:30px; top:190px; width:599px; height:154px; z-index:12;}
			#tabla-empleados { position:absolute; left:30px; top:191px; width:945px; height:420px; z-index:13; overflow:scroll; }
			#btns-regpdf { position: absolute; left:30px; top:670px; width:945px; height:40px; z-index:14; }
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultar-nomina">Consultar  N&oacute;mina Interna</div><?php	
	
	//Verificamos que el boton consultar sea presionado; si es asi mostrar los Empleados
	if(isset($_POST["sbt_consultar"])){?>
		<form name="frm_consultarNomina" method="post" action="guardar_reporte.php">
			<div id="tabla-empleados" class="borde_seccion2"><?php
				//Mostrar la Nomina Seleccionada
				$resConsultaNomina = mostrarNomina();
				
				//Mostrar el Detalle de los Prestamos, sí existen registros
				verInfoPrestamos(); ?>
			</div>
			<div id="btns-regpdf" align="center">
				<input type="hidden" name="hdn_origen" id="hdn_origen" value="exportarNomina" />
				<input type="hidden" name="hdn_msg" id="hdn_msg" value="NOMINA INTERNA DEL &Aacute;REA DE " />
				<input type="hidden" name="hdn_idNomina" id="hdn_idNomina" value="<?php echo $_POST['cmb_periodo']; ?>" />
				<input type="hidden" name="hdn_area" id="hdn_area" value="<?php echo $_POST['cmb_area']; ?>" /><?php
				
				if($resConsultaNomina==1){?>
					<input type="submit" name="sbt_guardarNomina" id="sbt_guardarNomina" class="botones" title="Exportar Nomina Consultada a una Hoja de Calculo"
					value="Exportar Nomina" onmouseover="window.status='';return true" />
					&nbsp;&nbsp;<?php
				}?>
				<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar a Seleccionar N&oacute;mina Interna" 
				onMouseOver="window.status='';return true" onclick="location.href='frm_consultarNominaInterna.php'" />
			</div>
		</form><?php 
	}
	else{?>
		<fieldset class="borde_seccion" id="tabla-consultar-nomina" name="tabla-consultar-nomina">
        <legend class="titulo_etiqueta">Consultar N&oacute;mina Interna</legend>
        <br />
        <form onsubmit="return valFormConsultaNomInterna(this);" name="frm_consultarNominaInterna" method="post" action="frm_consultarNominaInterna.php">
        	<table width="100%" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
            	<tr>
              		<td width="25%" align="right">A&ntilde;o</td>
              		<td width="25%"><?php
						$res = cargarComboConId("cmb_anio","anio","anio","nomina_interna","bd_recursos","A&ntilde;o","",
												"cargarCombo(this.value,'bd_recursos','nomina_interna','mes','anio','cmb_mes','Mes','');");
						if($res==0){?>
							<label class="msje_correcto">No Hay Registros de Nomina</label>
							<input type="hidden" name="cmb_anio" id="cmb_anio" value="" /><?php
						}?>
					</td>
			    	<td width="25%" align="right">Mes</td>
			    	<td width="25%">
						<select name="cmb_mes" id="cmb_mes" class="combo_box" onchange="cargarAreasNomina(cmb_anio.value,this.value);">
                			<option value="">Mes</option>
		                </select>					
					</td>
            	</tr>
            	<tr>
              		<td align="right">&Aacute;rea</td>
              		<td>
						<select name="cmb_area" id="cmb_area" class="combo_box" onchange="cargarNominas(cmb_anio.value,cmb_mes.value,this.value);">
                      		<option value="">&Aacute;rea</option>
                    	</select>
					</td>
				  	<td align="right">Periodo</td>
              		<td>
						<select name="cmb_periodo" id="cmb_periodo" class="combo_box">
                      		<option value="">Periodo</option>
                    	</select>
					</td>
            	</tr>
				<tr>					         	
					<td colspan="4" align="center">       
						<input name="sbt_consultar" type="submit" class="botones" id="sbt_consultar" value="Consultar"
						onmouseover="window.status='';return true;" title="Consultar N&oacute;mina Interna"/>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input name="btn_regrear" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; N&oacute;mina Interna" 
						onMouseOver="window.status='';return true" onclick="location.href='menu_nominaInterna.php'" />					
					</td>
				</tr>
			</table>
		</form>	        
		</fieldset><?php 
	}?>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>