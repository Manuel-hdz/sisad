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
		//Archivo que incluye la operación de consultar y eliminar Empleado
		include ("op_eliminarEmpleado.php");?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <style type="text/css">
		<!--				
		#titulo-consultar {position:absolute; left:30px; top:146px; width:173px; height:25px; z-index:11; }
		#consultar-empleado {position:absolute; left:30px; top:191px; width:592px; height:150px; z-index:14;}
		#res-spider {position:absolute;z-index:15;}
		#botones{position:absolute;left:30px;top:650px;width:950px;height:37px;z-index:13;}
		#tabla-resultados{position:absolute; left:30px; top:190px; width:950px; height:420px; z-index:12; padding:15px; padding-top:0px; overflow:scroll;}
		#calendario {position:absolute;left:570px;top:340px;width:30px;height:26px;z-index:15;}
		#eliminar-empleado {position:absolute; left:30px; top:191px; width:631px; height:280px; z-index:14;}
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultar">Baja de Empleados</div><?php 	

	//Si no esta definido ningun boton en el POST, mostrar el formulario de Entrada
	if (!isset($_POST["sbt_consultar"]) && !isset($_POST["sbt_continuar"]) && !isset($_GET["rfc"]) && !isset($_POST["sbt_eliminar"])){?>
		<fieldset class="borde_seccion" id="consultar-empleado">
		<legend class="titulo_etiqueta">Consultar Trabajador por Nombre</legend>	
		<br>		
		<form onSubmit="return valFormconsultarEmpleado1(this);" name="frm_consultarEmpleado1" method="post" action="frm_eliminarEmpleado.php">
			<table width="587" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
				  <td width="90"><div align="right">&Aacute;rea</div></td>
					<td><?php
						$conn = conecta("bd_recursos");		
						$stm_sql = "SELECT DISTINCT area FROM empleados WHERE estado_actual = 'ALTA' ORDER BY area";
						$rs = mysql_query($stm_sql);
						//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
						if($datos = mysql_fetch_array($rs)){?>			
							<select name="cmb_area" id="cmb_area" class="combo_box" onchange="txt_nombre.value='';lookup(txt_nombre,'empleados',cmb_area.value,'1');"><?php
							//Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
							echo "<option value='todo'>Seleccionar</option>";
							do{
								echo "<option value='$datos[area]'>$datos[area]</option>";
							}while($datos = mysql_fetch_array($rs));?>
							</select><?php
						//Cerrar la conexion con la BD		
						mysql_close($conn);	
						}
						else{
							echo "<label class='msje_correcto'> No hay &Aacute;reas Registradas</label>
							<input type='hidden' name='cmb_area' id='cmb_area'/>";
						}?>
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
						title="Continuar a Llenar el Formulario de Baja"  onmouseover="window.status='';return true" value="Consultar" />&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" value="Regresar" class="botones" title="Regresar al Men&uacute; de Empleados"
						onclick="location.href='menu_empleados.php'" />&nbsp;&nbsp;&nbsp;
						<input type="reset" class="botones" value="Reestablecer" title="Borra los criterios de b&uacute;squeda y reestablece el formulario"/>
					</td>
                </tr>
            </table>    
		</form>    			 		
	</fieldset><?php 		
	
	}else{
		//Verificar que no se haya presionado el boton de Eliminar 
		if (!isset($_POST["sbt_eliminar"])){
			//Verificar que el boton de Eliminar se haya presionado para llenar el formulario de Baja del Empleado
			if (!isset($_POST["sbt_continuar"])){
				echo "<form name='frm_eliminarTrabajador1' onsubmit='return valFormEliminarTrabajador1(this);' method='post' action='frm_eliminarEmpleado.php'>";
				echo "<div id='tabla-resultados' class='borde_seccion2'>";
					if (isset($_POST["sbt_consultar"]) || isset($_GET["rfc"])){
						if (isset($_POST["sbt_consultar"]))
							//Parametro 1 para buscar por el nombre concatenado
							$reg=mostrarEmpleados(1);
						if (isset($_GET["rfc"]))
							//Parametro 2 para buscar por el RFC
							$reg=mostrarEmpleados(2);
					}
				echo "</div>";?>
				<div align="center" id="botones"><?php 
					//Si reg vale 1, regreso resultados y por tanto se debe mostrar el boton de Eliminar
					if ($reg==1){ ?>
					<input type="submit" value="Continuar" title="Dar de Baja el Empleado Seleccionado" class="botones" name="sbt_continuar" onmouseover="window.status='';return true;"/>&nbsp;&nbsp;&nbsp;&nbsp;
					<?php }?>
					<input type="button" value="Regresar" onclick="location.href='frm_eliminarEmpleado.php'" title="Regresar a Consultar otro Empleado" class="botones" name="btn_regresar"/>
				</div>
				<?php
				echo "</form>";
			}
			else{
				/*
				**Este formulario es para recopilar los datos restantes de los trabajadoes en Baja
				*/
				//Conectar a la BD de Recursos
				$conn=conecta("bd_recursos");
				//Ejecutar la sentencia que trae los datos del empleado
				$rs=mysql_query("SELECT nombre,ape_pat,ape_mat,area,puesto,fecha_ingreso FROM empleados WHERE rfc_empleado='$_POST[rdb_rfc]'");
				//Vaciar los resultados de la consulta en un arreglo que permita su manejo
				$datos=mysql_fetch_array($rs);
				//Cerrar la conexion con la BD
				mysql_close($conn);
				?>
				<fieldset class="borde_seccion" id="eliminar-empleado">
				<legend class="titulo_etiqueta">Forma de Baja para <?php echo $datos["nombre"]." ".$datos["ape_pat"]." ".$datos["ape_mat"];?></legend>	
				<br>		
				<form onSubmit="return valFormEliminarTrabajador2(this);" name="frm_completarEliminado" method="post" action="frm_eliminarEmpleado.php">
					<table  width="100%" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
						<tr>
							<td width="20%"><div align="right">RFC</div></td>
							<td width="30%"><input type="text" readonly="readonly" id="txt_rfc" size="15" class="caja_de_texto" value="<?php echo $_POST["rdb_rfc"];?>" name="txt_rfc"/></td>
							<td width="20%"><div align="right">&Aacute;rea</div></td>
							<td width="30%"><input type="text" readonly="readonly" id="txt_area" size="20" class="caja_de_texto" value="<?php echo $datos["area"];?>" name="txt_area"/></td>
						</tr>
						<tr>
							<td><div align="right">Nombre</div></td>
							<td><input type="text" readonly="readonly" id="txt_nombre" size="25" class="caja_de_texto" value="<?php echo $datos["nombre"];?>" name="txt_nombre"/></td>
							<td><div align="right">Puesto</div></td>
							<td><input type="text" readonly="readonly" id="txt_puesto" size="30" class="caja_de_texto" value="<?php echo $datos["puesto"];?>" name="txt_puesto"/></td>
						</tr>
						<tr>
							<td><div align="right">Apellido Paterno</div></td>
							<td><input type="text" readonly="readonly" id="txt_apePat" size="25" class="caja_de_texto" value="<?php echo $datos["ape_pat"];?>" name="txt_apePat"/></td>
							<td><div align="right">Fecha Ingreso</div></td>
							<td><input type="text" readonly="readonly" id="txt_fechaIng" size="10" class="caja_de_texto" value="<?php echo modFecha($datos["fecha_ingreso"],1);?>" name="txt_fechaIng"/></td>
						</tr>
						<tr>
							<td><div align="right">Apellido Materno</div></td>
							<td><input type="text" readonly="readonly" id="txt_apeMat" size="25" class="caja_de_texto" value="<?php echo $datos["ape_mat"];?>" name="txt_apeMat"/></td>
							<td><div align="right">Fecha Baja</div></td>
							<td><input type="text" readonly="readonly" id="txt_fechaBaja" size="10" class="caja_de_texto" value="<?php echo date("d/m/Y");?>" name="txt_fechaBaja"/></td>
						</tr>
						<tr>
							<td valign="top"><div align="right" title="Es Altamente Recomendable Agregar Observaciones">Observaciones</div></td>
							<td colspan="3">
								<textarea name="txa_observaciones" id="txa_observaciones" maxlength="120" onkeyup="return ismaxlength(this)" class="caja_de_texto" 
								rows="2" cols="80" onkeypress="return permite(event,'num_car', 0);" title="Es Altamente Recomendable Agregar Observaciones"></textarea>
							</td>
						</tr>
						<tr>
							<td align="center" colspan="4">
								<input name="sbt_eliminar" type="submit" class="botones" id="sbt_eliminar" 
								title="Dar de Baja a <?php echo  $datos["nombre"]." ".$datos["ape_pat"]." ".$datos["ape_mat"]; ?>"  onmouseover="window.status='';return true" value="Eliminar" />&nbsp;&nbsp;&nbsp;
								<input name="btn_cancelar" type="button" value="Regresar" class="botones" title="Regresar a Verificar los datos"
								onclick="location.href='frm_eliminarEmpleado.php?rfc=<?php echo $_POST["rdb_rfc"]; ?>'" />&nbsp;&nbsp;&nbsp;
								<input type="reset" class="botones" value="Reestablecer" title="Reestablece los datos del Formulario de Baja"/>
							</td>
						</tr>
					</table>    
				</form>    			 		
</fieldset>
				
				<div id="calendario">
					<input type="image" name="fechaIngreso" id="fechaIngreso" src="../../images/calendar.png"
					onclick="displayCalendar(document.frm_completarEliminado.txt_fechaBaja,'dd/mm/yyyy',this)" 
					onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" title="Seleccionar Fecha de Baja de Empleado"/> 
				</div><?php
			}
		}//Fin del IF para sbt_eliminar
		else{
			//Funcion que registra la Baja del Empleado
			registrarBaja();
		}
	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>