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
		include ("op_consultarEquipoSeguridad.php");?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
    <style type="text/css">
		<!--				
			#titulo-consulta-empleados {position:absolute; left:30px; top:146px; width:479px; height:25px; z-index:11; }
			#tabla-consulta-empleados {position:absolute; left:30px; top:191px; width:465px; height:168px; z-index:25;}
			#tabla-empleados { position:absolute; left:550px; top:191px; width:420px; height:150px; z-index:13; }
			#btns-regpdf { position: absolute; left:30px; top:670px; width:945px; height:40px; z-index:14; }
			#res-spider {position:absolute;z-index:15;}
			#tabla-materiales { position:absolute; left:30px; top:390px; width:945px; height:230px; z-index:16; overflow:scroll; }
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consulta-empleados">Personal que Adeuda Material al Departamento de Almac&eacute;n </div>
	<?php
 		//Verificamos que el boton consultar sea presionado; si es asi mostrar los Empleados
		if(isset($_POST["sbt_consultar"])){
		?>
		<form name="frm_consultarEquipo" method="post"><?php 
			echo"<div id='tabla-empleados' width='100%' class='borde_seccion2' align='center'>";
				//Llamamos a la función mostrar empleados que se encuentra en el op 
				mostrarEmpleados();
			echo "</div>";
			echo"<div id='tabla-materiales' width='100%' class='borde_seccion2' align='center'>";
				//Llamamos a la función mostrarMateriales que se encuentra en el op 
				mostrarMateriales();
			echo "</div>";?>
				<div id="btns-regpdf" align="center">&nbsp;
				    <input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; N&oacute;mina Interna" 
        	        onMouseOver="window.status='';return true" onclick="location.href='inicio_recursos.php'" />
		  		</div>
		</form><?php 
		}?>	
		<fieldset class="borde_seccion" id="tabla-consulta-empleados">
		<legend class="titulo_etiqueta">Consulta de Empleados </legend>	
		<br>
		<form  onsubmit="return valFormConsultarEquipo(this);" method="post" name="frm_consultarEquipo" id="frm_consultarEquipo">
			<table width="471" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
				<td width="61"><div align="right">&Aacute;rea</div></td>
  				 <td width="470"><?php 
					//Se realiza la conexion con la base de datos
					$conn = conecta("bd_recursos");		
					//Se crea la consulta SQL
					$stm_sql = "SELECT DISTINCT area FROM empleados ORDER BY area";
					$rs = mysql_query($stm_sql);
					//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
					if($datos = mysql_fetch_array($rs)){?>
						<select name="cmb_area" id="cmb_area" class="combo_box" 
						onchange="txt_nombre.value='';lookup(txt_nombre,'bd_recursos',cmb_area.value,'1');"><?php
						//Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
						echo "<option value='todo'>Seleccionar</option>";
						do{
							echo "<option value='$datos[area]'>$datos[area]</option>";
						}while($datos = mysql_fetch_array($rs));?>
						</select>
					<?php
					}
					else{
						echo "<label class='msje_correcto'> No hay &Aacute;reas Registradas</label>
						<input type='hidden' name='cmb_area' id='cmb_area'/>";
					}
					//Cerrar la conexion con la BD		
					mysql_close($conn);	?>	
				</td>
			</tr>
			<tr valign="top">
				<td width="61"><div align="right">Trabajador</div></td>
				<td>
					<input type="text" name="txt_nombre" id="txt_nombre" onkeyup="lookup(this,'empleados',cmb_area.value,'1');" 
					value="" size="60" maxlength="80" onkeypress="return permite(event,'car',0);"/>
					<div id="res-spider">
						<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
							<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
							<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
						</div>
					</div>			 
				 </td>
		</table>
		<div align="center">
			<input name="sbt_consultar" type="submit" class="botones" id="sbt_consultar" value="Consultar Equipo de Seguridad"
			onmouseover="window.status='';return true;" title="Consultar Equipo de Seguridad"/>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Inicio" 
        	onMouseOver="window.status='';return true" onclick="location.href='inicio_recursos.php'" />
		</div> 
	</form>
</fieldset>	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>