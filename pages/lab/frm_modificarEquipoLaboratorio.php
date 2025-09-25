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
		include ("op_modificarEquipoLaboratorio.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionLaboratorio.js" ></script>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
			#titulo-Modificar {position:absolute;left:30px;top:146px;	width:210px;height:20px;z-index:11;}
			#tabla-ModificarEquipoMarca {position:absolute;left:30px;top:190px;width:425px;height:133px;z-index:12;}
			#tabla-ModificarEquipoNumero{position:absolute;left:507px;top:190px;width:425px;height:133px;z-index:12;}
			#tabla-Equipo { position:absolute; left:30px; top:380px; width:945px; height:170px; z-index:21; overflow:scroll; }
			#btns-regpdf { position: absolute; left:30px; top:600px; width:945px; height:40px; z-index:23; }
			#titulo-registrar {position:absolute;left:30px;top:146px;	width:210px;height:20px;z-index:11;}
			#tabla-agregarEquipo {position:absolute;left:30px;top:190px;width:750px;height:316px;z-index:12;}
		-->
    </style>
</head>
<body><?php 
if (isset($_POST['sbt_modificar'])){
		modificarEquipoSeleccionado();
	}else{?>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-Modificar">Modificar  Equipo </div>
	<fieldset class="borde_seccion" id="tabla-ModificarEquipoMarca" name="tabla-ModificarEquipoMarca">
	<legend class="titulo_etiqueta">Selecciona Marca del Equipo </legend>	
	<br>
	<form name="frm_modificarEquipoMarca" method="post" action="frm_modificarEquipoLaboratorio.php" onsubmit="return valFormModificarMarca(this);">
		<table width="415"  cellpadding="5" cellspacing="5" class="tabla_frm">
      		<tr>
				<td width="63"><div align="right">Marca</div></td>
		  		<td colspan="4"><?php 
						$cmb_marca="";
						$conn = conecta("bd_laboratorio");
						$result=mysql_query("SELECT DISTINCT marca FROM equipo_lab WHERE estado=1 ORDER BY marca ");
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
			<td colspan="4">
					<div align="center"> 
						<input name="sbt_Modificar" type="submit" class="botones" id= "sbt_guardar" value="Consultar" title="Modificar Equipo"
						onMouseOver="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; Equipo" 
						onmouseover="window.status='';return true" onclick="location.href='menu_equipoLaboratorio.php'" />
					</div>
			  </td>
			</tr>
   	  </table>
	</form>
</fieldset>
<fieldset class="borde_seccion" id="tabla-ModificarEquipoNumero" name="tabla-ModificarEquipoNumero">
	<legend class="titulo_etiqueta">Introducir Clave del Equipo </legend>	
	<br>
	<form  name="frm_modificarEquipoClave" method="post" action="frm_ModificarEquipoLaboratorio.php" onsubmit="return valFormModificarClave(this);" >
		<table width="415"  cellpadding="5" cellspacing="5" class="tabla_frm">
      		<tr>
			<td width="120"><div align="right">No Interno </div></td>
          	<td><input name="txt_noInterno" id="txt_noInterno" type="text" class="caja_de_texto" size="6" maxlength="4" onkeypress="return permite(event,'num', 3);" /></td>
			</tr>
			<tr>
				<td colspan="4">
					<div align="center"> 
						<input name="sbt_Modificar" type="submit" class="botones" id= "sbt_guardar" value="Consultar" title="Modificar Equipo"
						onMouseOver="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; Equipo" 
						onmouseover="window.status='';return true" onclick="location.href='menu_equipoLaboratorio.php'" />
					</div>
				</td>
			</tr>
   	  </table>
	</form>
</fieldset>
   <?php 
//Verificamos que el boton Modificar sea presionado; si es asi mostrar los Empleados
	if(isset($_POST["sbt_Modificar"])){?>
<form name="frm_modificarEquipo"  onsubmit="return valFormModificar(this);"method="post" ><?php 
			echo"<div id='tabla-Equipo' class='borde_seccion2' align='center'>";
				//Si $ band regresa 0 la consulta no trajo resultados por loc ual no se debe de mostrar el boton de Modificar
				$band=mostrarEquipos();
				echo "</div>";?>
				<div id="btns-regpdf" align="center">
				<?php if($band!=0){?>
				<input type="submit" name="sbt_modificar" value="Modificar" class="botones" title="Modificar Equipo" 
	        			onmouseover="window.estatus='';return true"/>
				<?php }?>
						&nbsp;&nbsp;&nbsp;&nbsp;
				    	<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Equipo" 
        	        	onMouseOver="window.status='';return true" onclick="location.href='menu_EquipoLaboratorio.php'" />
  </div>
</form><?php }}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>