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
		include ("op_regAsistencia.php");?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>

    <style type="text/css">
		<!--				
		#titulo-consultar {position:absolute;left:30px;top:146px;width:389px;height:25px;z-index:11;}
		#consultar-empleado {position:absolute; left:30px; top:191px; width:592px; height:150px; z-index:14;}
		#consultar-empleado3 {position:absolute; left:30px; top:380px; width:592px; height:150px; z-index:12;}
		#res-spider {position:absolute;z-index:15;}
		#botones{position:absolute;left:296px;top:660px;width:594px;height:37px;z-index:13;}
		#tabla-resultados{position:absolute; left:30px; top:190px; width:950px; height:420px; z-index:12;  overflow:scroll;}
		#btn-registrar {position:absolute;left:390px;top:660px;width:72px;height:40px;z-index:29;}
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultar">Seleccionar  Empleados que Recibieron Capacitaci&oacute;n</div><?php

	if (!isset($_POST["sbt_consultar"]) && !isset($_POST["sbt_consultarArea"])){?>
	<fieldset class="borde_seccion" id="consultar-empleado">
		<legend class="titulo_etiqueta">Consultar Trabajador por Nombre</legend>	
		<br>		
		<form onSubmit="return valFormRegAsistEmp(this);" name="frm_valFormRegAsistEmp" method="post" action="frm_regAsistencia.php">
			<table width="587" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
                    <td width="90"><div align="right">&Aacute;rea</div></td>
                    <td><?php 
						$conn = conecta("bd_recursos");		
						$stm_sql = "SELECT DISTINCT area FROM empleados ORDER BY area";
						$rs = mysql_query($stm_sql);
						//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
						if($datos = mysql_fetch_array($rs)){?>
							<select name="cmb_area" id="cmb_area" class="combo_box" 
								onchange="txt_nombre.value='';lookup(txt_nombre,'bd_recursos',cmb_area.value,'1');">
							<?php //Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
							echo "<option value='todo'>Seleccionar</option>";
							do{
								echo "<option value='$datos[area]'>$datos[area]</option>";
							}while($datos = mysql_fetch_array($rs));?>
							</select><?php
						}
						else{
							echo "Error: ".mysql_error();
						}
						//Cerrar la conexion con la BD		
						mysql_close($conn);?>
                    </td>
				</tr>
				<tr valign="top">
                    <td width="90"><div align="right">Trabajador</div></td>
                    <td width="462">
                        <input type="text" name="txt_nombre" id="txt_nombre" onkeyup="lookup(this,'empleados',cmb_area.value,'1');" 
                        value="" size="60" maxlength="80" onkeypress="return permite(event,'car',0);"/>
                        <div id="res-spider">
                            <div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
                                <img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
                                <div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
                            </div>
                        </div>
                    </td>
				</tr>
				<tr>
					<td align="center" colspan="2">
						<input name="sbt_consultar" type="submit" class="botones" id="sbt_consultar" 
                            title="Consultar Informaci&oacute;n del Empleado Seleccionado"  onmouseover="window.status='';return true" value="Consultar" />
                        &nbsp;&nbsp;&nbsp;
                        <input name="btn_regresar" type="button" value="Regresar" class="botones" title="Regresar al Men&uacute; de Capacitaci&oacute;nes"
							onclick="location.href='menu_capacitaciones.php'" />
                        &nbsp;&nbsp;&nbsp;
						<input type="reset" class="botones" value="Reestablecer" title="Borra los criterios de b&uacute;squeda y reestablece el formulario"/>
					</td>
                </tr>
            </table>    
		</form>    			 		
	</fieldset>	
			
	<fieldset id="consultar-empleado3" class="borde_seccion">
		<legend class="titulo_etiqueta">Consultar los Trabajadores por &Aacute;rea</legend>	
		<br>
		<form onSubmit="return valFormRegAsistEmp2(this);" name="frm_valFormRegAsistEmp2" method="post" action="frm_regAsistencia.php">
			<table align="center" border="0" width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td width="90"><div align="right">&Aacute;rea</div></td>
    	       		<td width="63">
                		<?php cargarCombo("cmb_area","area","empleados","bd_recursos","Seleccionar","");?>
					</td>
					<td width="389">
						<input name="sbt_consultarArea" type="submit" class="botones" id="sbt_consultar" 
						title="Consultar Informaci&oacute;n del Empleado Seleccionado"  onmouseover="window.status='';return true" value="Consultar" />
                        &nbsp;&nbsp;&nbsp;
				  	</td>
				</tr>
			</table>
   		</form>	   
	</fieldset><?php 
	}
	else{?>
		<form onSubmit="return valFormGuardarAsistencia(this);" name="frm_guardarAsistencia" method="post" action="frm_regAsistencia.php"><?php
			echo "<div id='tabla-resultados' class='borde_seccion2'>";
				if (isset($_POST["sbt_consultar"])){
					mostrarEmpleados(1);
				}
				if (isset($_POST["sbt_consultarArea"])){
					mostrarEmpleados(2);
				}
			echo "</div>";?>
            <div id='btn-registrar' align="center">
                <input name="sbt_registrar" type="submit" class="botones" value="Registrar" title="Registrar Asistencia a Empleados Seleccionados" onmouseover="window.status='';return true;"/>
            </div>
        </form>    
		<div align="center" id="botones">
			<input type="button" value="Regresar" onclick="history.back();" title="Regresar a Consultar otro Empleado" class="botones" name="btn_regresar"/>
		</div><?php
	}
    
	// Si ya esta definido sbt_registrar llamar la funcion guardarAsistencias() para registrar las asistencias del personal seleccionado
	if (isset($_POST["sbt_registrar"])){
		guardarAsistencias();
	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>