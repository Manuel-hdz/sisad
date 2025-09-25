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
		include ("op_consultarEmpleado.php");
		//Archivo que incluye las operaciones para registrar a los Beneficiarios
		include ("op_agregarEmpleado.php");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
    <style type="text/css">
		<!--				
		#titulo-consultar {position:absolute; left:30px; top:146px; width:263px; height:25px; z-index:11; }
		#consultar-empleado {position:absolute; left:30px; top:190px; width:592px; height:150px; z-index:12;}
		#res-spider {position:absolute;z-index:13;}
		#empleado {position:absolute; left:30px; top:190px; width:746px; height:210px; z-index:12;}
		#tabla-becarios {position:absolute;left:30px;top:430px;width:746px;height:240px;z-index:13;overflow:scroll;}
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultar">Registrar Becarios de Empleados</div>	
	
	<?php 
	//Verificar que no se haya registrado ningun becario y no se haya llegado aqui desde la pantalla de empleadoAgregado
	if(!isset($_POST["txt_nombre"]) && !isset($_GET["rfc"])){ 
		//Elimina el Arreglo de Sesion en caso de estar definido y haber presionado el boton de Cancelar
		if (isset($_GET["cancela"]) && isset($_SESSION["becarios"]))
			unset($_SESSION["becarios"]);
	?>
		<fieldset class="borde_seccion" id="consultar-empleado">
			<legend class="titulo_etiqueta">Consultar Trabajador por Nombre</legend>	
			<br>		
			<form onSubmit="return valFormConsultarEmpleadoBeneficiario(this);" name="frm_obtenerEmpleado" method="post" action="frm_agregarEmpleadoBecarios.php">
				<table width="587" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
					<tr>
					  <td width="90"><div align="right">&Aacute;rea</div></td>
						<td>
							<?php 
								$conn = conecta("bd_recursos");		
								$stm_sql = "SELECT DISTINCT area FROM empleados ORDER BY area";
								$rs = mysql_query($stm_sql);
								//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
								if($datos = mysql_fetch_array($rs)){			
									?>
									<select name="cmb_area" id="cmb_area" class="combo_box" onchange="txt_nombre.value='';lookup(txt_nombre,'empleados',cmb_area.value,'1');">
									<?php
									//Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
									echo "<option value=''>Seleccionar</option>";
									do{
										echo "<option value='$datos[area]'>$datos[area]</option>";
									}while($datos = mysql_fetch_array($rs));
									?>
									</select>
									<?php
								}
								else{
									echo "<label class='msje_correcto'> No hay &Aacute;reas Registradas</label>
									<input type='hidden' name='cmb_area' id='cmb_area'/>";
								}
								//Cerrar la conexion con la BD		
								mysql_close($conn);	
							?>
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
							title="Consultar Informaci&oacute;n del Empleado Seleccionado"  onmouseover="window.status='';return true" value="Consultar" />&nbsp;&nbsp;&nbsp;
							<input name="btn_cancelar" type="button" value="Cancelar" class="botones" title="Regresar al Men&uacute; de Empleados"
							onclick="location.href='menu_empleados.php'" />&nbsp;&nbsp;&nbsp;
							<input type="reset" class="botones" value="Reestablecer" title="Borra los criterios de b&uacute;squeda y reestablece el formulario"/>
						</td>
					</tr>
				</table>    
			</form>    			 		
		</fieldset>	
	<?php }
	else{
		//Si se presiono el boton de Agregar, cargar al becario a la session
		if (isset($_POST["sbt_agregar"])){
			$cant=0;
			//Determinar el valor de la beca en base al grado de estudio
			switch ($cmb_grado){
				case "PRIMARIA":
					$cant=400;
					break;
				case "SECUNDARIA":
					$cant=600;
					break;
				case "PREPARATORIA":
					$cant=800;
					break;
				case "UNIVERSIDAD":
					$cant=1000;
					break;
			}
			//Si ya esta definido el arreglo $becarios, entonces agregar el siguiente registro a el
			if(isset($_SESSION['becarios'])){
				$becarios[] = array("nombre"=>strtoupper($txt_nombreBec), "parentesco"=>strtoupper($txt_parentesco), "grado_estudio"=>strtoupper($txt_grado."° ".$cmb_grado), "promedio"=>$txt_promedio, "cantidad"=>$cant);
			}
			//Si no esta definido el arreglo $becarios definirlo y agregar el primer registro
			else{			
				//Guardar los datos en el arreglo
				$becarios = array(array("nombre"=>strtoupper($txt_nombreBec), "parentesco"=>strtoupper($txt_parentesco), "grado_estudio"=>strtoupper($txt_grado."° ".$cmb_grado), "promedio"=>$txt_promedio, "cantidad"=>$cant));
				$_SESSION['becarios'] = $becarios;
			}	
		}
		
		//Verificar que este definido el Arreglo de Becarios, si es asi, lo mostramos en el formulario
		if (isset($_SESSION["becarios"])){
			echo "<div id='tabla-becarios' class='borde_seccion2'>";
				mostrarBecariosReg($becarios);
			echo "</div>";
		}		
		
		//Si esta definido el rfc en el GET, entonces se llego aqui desde que se agrego a un Empleado
		if (isset($_GET["rfc"]))
			//Creamos la sentencia SQL para obtener el nombre completo ademas del RFC del empleado
			$stm_sql="SELECT CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre,rfc_empleado FROM empleados WHERE rfc_empleado='$_GET[rfc]'";
		else
			//Creamos la sentencia SQL para obtener el nombre completo ademas del RFC del empleado
			$stm_sql="SELECT CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre,rfc_empleado FROM empleados WHERE CONCAT(nombre,' ',ape_pat,' ',ape_mat)='$_POST[txt_nombre]'";
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_recursos");
		//Ejecutamos la sentencia SQL
		$rs=mysql_query($stm_sql);
		//Contamos la cantidad de Registros que nos regresa la sentencia
		$reg=mysql_num_rows($rs);
		//Obtener los datos del Trabajador en un arreglo
		$datos=mysql_fetch_array($rs);
		//Verificar que se haya presionado el boton de Finalizar
		if (isset($_POST["sbt_finalizar"])){
			registrarBecarios($datos["rfc_empleado"],$becarios);
		}

		//Verificamos que el empleado exista en la BD si reg es mayor a 0
		if ($reg>0){
		?>
			<fieldset class="borde_seccion" id="empleado">
				<legend class="titulo_etiqueta">Registrar Becarios de <?php echo $datos["nombre"];?></legend>
				<?php 
				if (!isset($_GET["rfc"])) {?>
					<form name="frm_regBecarios" onsubmit="return valFormBecarios(this);" method="post" action="frm_agregarEmpleadoBecarios.php">
				<?php }
				//Si esta definido el RFC pero no la opcion de Modificacion, se llego aqui desde la ventana de resultados tras guardar a un empleado
				if (isset($_GET["rfc"]) && !isset($_GET["mod"])){?>
					<form name="frm_regBecarios" onsubmit="return valFormBecarios(this);" method="post" action="frm_agregarEmpleadoBecarios.php?rfc=<?php echo $_GET["rfc"];?>">
				<?php }
				//Si esta definido el RFC y la opcion de Modificacion, entonces se llego aqui desde la pantalla de modificaciones
				if (isset($_GET["rfc"]) && isset($_GET["mod"])){?>
					<form name="frm_regBecarios" onsubmit="return valFormBecarios(this);" method="post" action="frm_agregarEmpleadoBecarios.php?rfc=<?php echo $_GET["rfc"];?>&mod=<?php echo $_GET["mod"];?>">
				<?php 
					}
				?>
				<table width="100%" cellspacing="5" border="0" class="tabla_frm">
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr>
						<td width="15%">Nombre Becario</td>
						<td width="85%">
							<input type="text" name="txt_nombreBec" id="txt_nombreBec" value="" size="60" maxlength="60" onkeypress="return permite(event,'car',0);"/>
							<input type="hidden" name="txt_nombre" value="<?php echo $datos["nombre"];?>"/>
						</td>
					</tr>
					<tr>
						<td>Parentesco</td>
						<td>
							<input type="text" name="txt_parentesco" id="txt_parentesco" value="" size="20" maxlength="20" onkeypress="return permite(event,'car',0);"/>
						</td>
					</tr>
					<tr>
						<td>Promedio</td>
						<td>
							<input type="text" name="txt_promedio" id="txt_promedio" value="" size="5" maxlength="5" onkeypress="return permite(event,'num',3);"
							onchange="if(this.value<95){if (!confirm('El Promedio es Menor de 95.\n¿Continuar?')) this.value='';};"/>
						</td>
					</tr>
					<tr>
						<td>Grado de Estudio</td>
						<td>
							<input type="text" name="txt_grado" id="txt_grado" value="" size="2" maxlength="2" onkeypress="return permite(event,'num',3);" onchange="if(this.value>12){if (!confirm('El Grado es Mayor a 12.\n¿Continuar?')) this.value='';};"/>
							<select class="combo_box" name="cmb_grado" id="cmb_grado">
								<option value="" selected="selected">Grado de Estudio</option>
								<option value="PRIMARIA" >PRIMARIA</option>
								<option value="SECUNDARIA" >SECUNDARIA</option>
								<option value="PREPARATORIA" >PREPARATORIA</option>
								<option value="UNIVERSIDAD" >UNIVERSIDAD</option>
							</select>
 							<input type="hidden" name="hdn_valida" id="hdn_validar" value="si"/>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<input type="submit" class="botones" value="Agregar" name="sbt_agregar" title="Registra los datos del Becario" onmouseover="window.status='';return true;"/>&nbsp;&nbsp;&nbsp;&nbsp;
							<?php if (isset($_SESSION["becarios"])){?>
								<input type="submit" class="botones" value="Finalizar" name="sbt_finalizar" title="Termina el Proceso de Registrar Becarios" onmouseover="window.status='';return true;" onclick="hdn_validar.value='no';"/>&nbsp;&nbsp;&nbsp;&nbsp;
							<?php }?>
							<input type="reset" class="botones" value="Limpiar" name="btn_limpiar" title="Limpiar los Campos del Formulario"/>&nbsp;&nbsp;&nbsp;&nbsp;
							<?php 
							//Si esta definido el rfc en el GET, entonces se llego aqui desde que se agrego a un Empleado
							if (!isset($_GET["rfc"])) {	?>
								<input type="button" class="botones" value="Cancelar" name="btn_cancelar" title="Cancela el Proceso de Agregado y Regresa a Buscar otro Empleado" 
								onclick="confirmarSalida('frm_agregarEmpleadoBecarios.php?cancela');" onmouseover="window.status='';return true;"/>
							<?php }
							if (isset($_GET["rfc"]) && !isset($_GET["mod"])) {?>
								<input type="button" class="botones" value="Cancelar" name="btn_cancelar" title="Cancela el Proceso de Agregado y Regresa a Seleccionar otra Opci&oacute;n" 
								onclick="confirmarSalida('frm_empleadoAgregado.php?rfc=<?php echo $_GET["rfc"];?>');" onmouseover="window.status='';return true;"/>
							<?php }
							if (isset($_GET["rfc"]) && isset($_GET["mod"])) {?>
								<input type="button" class="botones" value="Cancelar" name="btn_cancelar" title="Cancela el Proceso de Agregado y Regresa a la Ventana de Modificaciones" 
								onclick="confirmarSalida('frm_modificarBeneficiarios.php?rfc=<?php echo $_GET["rfc"];?>&mod=<?php echo $_GET["mod"];?>&cancela');" onmouseover="window.status='';return true;"/>
							<?php }
							?>
						</td>
					</tr>
				</table>
				</form>
			</fieldset>
		<?php
		}else{
			?>
			<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
			<p align='center' class='msje_correcto'>No hay Trabajadores Registrados con el Nombre <em><u><?php echo $_POST["txt_nombre"];?></u></em>
				<br><br><input type="button" class="botones" value="Regresar" name="btn_regresar" title="Regresa a Buscar otro Empleado" onclick="location.href='frm_agregarEmpleadoBecarios.php?cancela';" onmouseover="window.status='';return true;"/>
			</p>
			<?php
		}
	}
	?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>