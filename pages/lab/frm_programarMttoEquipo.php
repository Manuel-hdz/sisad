<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Laboratorio
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Manejo de la funciones para Registrar los datos de los equipos que se manejan en el Laboratorio
		include ("op_programarMttoEquipo.php");?>
				
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionLaboratorio.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>


	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-mostrarEquipoLabClave {position:absolute; left:30px; top:146px; width:417px; height:20px; z-index:11; }
		#titulo-mostrarEquipoLabNombre {position:absolute; left:30px; top:146px; width:417px; height:20px; z-index:12; }
		#btn-continuar {position:absolute;left:33px;top:660px;width:987px;height:40px;z-index:9;}
		#detalle-equipo {position:absolute;left:36px;top:432px;width:941px;height:171px;z-index:17;overflow:scroll;}
		#tabla-mostrarEquipoLabClave {position:absolute;left:536px;top:209px;width:446px;height:180px;z-index:15;}
		#tabla-mostrarEquipoLabMarca { position:absolute; left:36px; top:210px; width:448px; height:178px; z-index:17; }
		-->
    </style>
</head>
<body>


	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-mostrarEquipoLabNombre">Programar Mantenimiento a los Equipos de Laboratorio</div><?php	

	if (!isset($_POST['rdb_noEquipo']) ){?>

		<fieldset class="borde_seccion" id="tabla-mostrarEquipoLabMarca">
		<legend class="titulo_etiqueta">Buscar Equipos de Laboratorio por Marca</legend>	
		<br>			
		<form onSubmit="return valFormConsultarNombreEquipo(this);" name="frm_consultarNombreEquipo" method="post" action="frm_programarMttoEquipo.php">
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="5" class="tabla_frm">		
			<tr>
				<td width="38%">Marca Equipo Laboratorio</td>
		  		<td width="62%"><?php 
						$cmb_marca="";
						$conn = conecta("bd_laboratorio");
						$result=mysql_query("SELECT DISTINCT marca FROM equipo_lab WHERE estado=1 ORDER BY marca");
						if($marcas=mysql_fetch_array($result)){?>
							<select name="cmb_marca" id="cmb_marca" size="1" class="combo_box">
								<option value="">Marca</option><?php 
									do{
										if ($marcas['marca'] == $cmb_marca){
											echo "<option value='$marcas[marca]' selected='selected'>$marcas[marca]</option>";
										}
										else{
											echo "<option value='$marcas[marca]'>$marcas[marca]</option>";
										}
									}while($marcas=mysql_fetch_array($result)); 
							//Cerrar la conexion con la BD		
							mysql_close($conn);?>
							</select><?php
						 }
						else{
							echo "<label class='msje_correcto'> No hay Marcas Registradas</label>
							<input type='hidden' name='cmb_marca' id='cmb_marca'/>";
				  		}?>
			  </td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
	  			<td colspan="2">
					<div align="center">
						<input name="sbt_consultarMarcaEquipo" type="submit" class="botones" id="sbt_consultarMarcaEquipo"  value="Consultar" title="Consultar Material de Laboratorio por Nombre" 
						onmouseover="window.status='';return true" />
						&nbsp;&nbsp;&nbsp;
						<input name="btn_regresar" type="button" class="botones" id="btn_regresar" value="Regresar" title="Cancelar y Regresar al Men&uacute; de Equipos de Laboratorio " 
						onmouseover="window.status='';return true" onclick="location.href='menu_equipoLaboratorio.php'" />
					</div>
				</td>
			</tr>	
		</table>	  			
		</form>
</fieldset>
	
		<fieldset class="borde_seccion" id="tabla-mostrarEquipoLabClave">
		<legend class="titulo_etiqueta">Buscar Equipos de Laboratorio por Clave</legend>	
		<br>			
		<form onSubmit="return valFormConsultarClaveEquipo(this);" name="frm_consultarClaveEquipo" method="post" action="frm_programarMttoEquipo.php">
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="5" class="tabla_frm">		
			<tr>
				<td><div align="right">Clave</div></td>
				<td><input type="text" name="txt_claveEquipo" id="txt_claveEquipo" size="10" maxlength="10" onkeypress="return permite(event,'num_car');" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2">
					<div align="center">
						<input name="sbt_consultarClaveEquipo" type="submit" class="botones" id="sbt_consultarClaveEquipo"  value="Consultar" title="Consultar Material de Laboratorio por Clave" 
						onmouseover="window.status='';return true" />
						&nbsp;&nbsp;&nbsp;
						<input name="btn_regresar" type="button" class="botones" id="btn_regresar" value="Regresar" title="Cancelar y Regresar al Men&uacute; de Equipos de Laboratorio " 
						onmouseover="window.status='';return true" onclick="location.href='menu_equipoLaboratorio.php'" />
					</div>
				</td>
			</tr>		
		</table>	  			
		</form>
</fieldset><?php
		
		//Si esta definido los nombres de los siguientes botones que muestre los equipos
		if(isset($_POST['sbt_consultarMarcaEquipo']) || isset ($_POST['sbt_consultarClaveEquipo'])){?>
			<form onSubmit="return valFormSeleccionarEquipoLab(this);" name="frm_seleccionarEquipo" method="post" action="frm_programarMttoEquipo2.php">				
				<div id='detalle-equipo' class='borde_seccion2' align="center"><?php
					$control=buscarEquipoLab();?>
                </div>
				<?php
				
				//Verificar si el resultado de la busqueda arroja resultados para mostrar el boton de continuar
				if ($control==1){?>
					<div id='btn-continuar' align="center">
					<input name="sbt_continuar" type="submit" class="botones" id="sbt_continuar" value="Continuar" 
					onmouseover="window.status='';return true" title="Continuar Programando el Mantenimiento para los Equipo" />
	  		  </div><?php 
				}?>
			</form><?php 
		}
		
	} //fin del if (!isset($_POST['rdb_noEquipo']))?>
	
</body>
<?php  }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>