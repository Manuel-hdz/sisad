<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">
<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del M�dulo de Almac�n
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Este archivo contiene las operaciones para registar la salida de Material en la BD de Almacen
		include ("op_salidaMaterial.php");
		
			
		//Manejo de la Salidad de Material
		if(isset($_SESSION['datosSalida'])){
			unset($_SESSION['datosSalida']);
			unset($_SESSION['id_salida']);
		}
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionAlmacen.js" ></script>
	<script type="text/javascript" src="includes/ajax/buscarMaterial.js" ></script>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	
    <style type="text/css">
		<!--
		#equipo-seguridad {position:absolute; left:30px; top:146px; width:146px; height:23px; z-index:11;}
		#tabla {position:absolute; left:30px; top:190px; width:420px; height:440px; z-index:12;}
		#foto-empleado { position:absolute; left:350px; top:300px; width:120px; height:200px; z-index:13; border:solid;}
		#botones {position:absolute; width:924px; height:36px; z-index:13; left: 30px; top: 660px;}
		#calendario{position:absolute;left:276px;top:376px;width:30px;height:26px;z-index:14;}
		#materiales{position:absolute; width:465px; height:420px; z-index:13; left: 494px; top: 190px; overflow:scroll;}	
		#procesando { position:absolute; left:406px; top:274px; width:133px; height:86px; z-index:17; }	
		-->
	</style>
</head>
<body>
	<?php
	if(isset($_POST["id_foto"])){
		$id_empl = $_POST["id_foto"];
		?>
		<div id="foto-empleado">
			<img src="verImagenEmpl.php?id_empleado=<?php echo $id_empl; ?>" width="100%" height="100%"/>
		</div>
		<?php
	}
	$nom_seleccionado = '';
	$rfc_seleccionado = '';
	if (isset($_POST["txt_codigo"])){
		$rfc_seleccionado=obtenerDato("bd_recursos", "empleados", "rfc_empleado", "id_empleados_empresa", $_POST["txt_codigo"]);
		$nom_seleccionado=obtenerDato("bd_recursos", "empleados", "CONCAT(nombre,' ',ape_pat,' ',ape_mat)", "id_empleados_empresa", $_POST["txt_codigo"]);
	} 
	if($rfc_seleccionado == "" && isset($_POST["txt_codigo"])){ ?>
		<script>
		alert("No hay trabajadores con ese codigo");
		setTimeout("txt_codigo.focus()",400);
		setTimeout("txt_codigo.value=''",420);
		</script>
	<?php } ?>
	<script>
		setTimeout("txt_codigo.focus()",400);
		setTimeout("txt_codigo.value=txt_codigo.value",500);
	</script>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="equipo-seguridad">Equipo Seguridad </div><?php
	
	
	//Si el valor registrar no esta definido, desplegar el formulario para registrar el equipo de seguridad 
	if(!isset($_POST['registrar'])){
		$idSalida=obtenerIdSalida();
		
		if ($rfc_seleccionado != ""){
			?>
			<script type="text/javascript" language="javascript">
				setTimeout("mostrarEquipoSegInd()",300);
				function mostrarEquipoSegInd(){
					document.getElementById('txt_destino').focus();
					document.getElementById('materiales').style.visibility='visible';
				}
			</script>
			<?php
		}
	?>	
	
	<div id="calendario">
		<input type="image" name="txt_fechaElaborado" id="txt_fechaElaborado" src="../../images/calendar.png"
		onclick="displayCalendar(document.frm_verEquipoSeguridad.txt_fechaEntrega,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
		title="Seleccionar la Fecha de Salida"/> 
	</div>
	
	<form name="frm_verEquipoSeguridad" onsubmit="return valFormSeguridad(this);" method="post" action="frm_equipoSeguridad.php">
	<fieldset class="borde_seccion" id="tabla" name="tabla">
	<legend class="titulo_etiqueta">Seleccionar los Datos del Trabajador</legend>
	<table width="420" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td width="174" align="right">C&oacute;digo del Trabajador</td>
        	<td width="160">
				<input type="text" name="txt_codigo" id="txt_codigo" class="caja_de_texto"
				value="<?php if(isset($_POST["txt_codigo"])) echo  $_POST["txt_codigo"];?>" 
				onchange="frm_verEquipoSeguridad.submit()" onfocus="this.oldvalue = this.value"/>
				<?php
				if(isset($_POST["id_kiosco"])){
					echo "<input type='hidden' name='id_kiosco' id='id_kiosco' value='$_POST[id_kiosco]' />";
				}
				if(isset($_POST["id_empl"])){
					echo "<input type='hidden' name='id_foto' id='id_foto' value='$_POST[id_empl]' />";
				}
				?>
			</td>
		</tr>
		<tr>
			<td width="174" align="right">Nombre del Trabajador</td>
        	<td width="160">
				<input type="hidden" name="cmb_nombre" id="cmb_nombre" class="caja_de_texto" value="<?php echo $rfc_seleccionado;?>"/>
				<input type="text" name="cmb_nombre_emp" id="cmb_nombre_emp" class="caja_de_texto" value="<?php echo $nom_seleccionado;?>"
				readonly="readonly" size="40"/>
			</td>			
		</tr>
		<tr>
			<td align="right">Categor&iacute;a</td>
			<td><?php
				$categoria='';
				if ($rfc_seleccionado != ""){
					$categoria=obtenerDato("bd_recursos", "empleados", "area", "rfc_empleado", $rfc_seleccionado);
				}?>
				<input type="text" name="txt_categoria" id="txt_categoria" disabled="disabled" class="caja_de_texto" value="<?php echo $categoria?>"/>
				<input type="hidden" name="hdn_categoria" id="hdn_categoria" value="<?php echo $categoria?>"/>
			</td>
		</tr>
		<tr>
			<td align="right">Fecha de Ingreso</td>
		   	<td><?php
				$fecha='';
				if ($rfc_seleccionado != ""){
					$fecha=modFecha(obtenerDato("bd_recursos", "empleados", "fecha_ingreso", "rfc_empleado", $rfc_seleccionado),1);
				}?> 
					<input name="txt_fecha" type="text" disabled="disabled" class="caja_de_texto" value="<?php echo $fecha?>" size="10" maxlength="10"/>
			</td>
		</tr>
		<tr>
			<td><div align="right">Fecha Entrega</div></td>
			<td>
				<input name="txt_fechaEntrega" type="text" readonly="readonly" class="caja_de_texto" id="txt_fechaEntrega" value="<?php echo date("d/m/Y")?>" size="10" maxlength="10" />
			</td>
		</tr>
		<tr>
			<td><div align="right">N&deg; de Vale</div></td>
			<td><input name="txt_noVale" type="text" id="txt_noVale" class="caja_de_num" onkeypress="return permite(event,'num_car');" value="<?php echo $idSalida;?>"/></td>
		</tr>
		<tr>
			<td><div align="right">Destino</div></td>
			<td><input name="txt_destino" type="text" id="txt_destino" class="caja_de_texto" onkeypress="return permite(event,'num_car');"
						readonly="readonly" value="EPP"/></td>
		</tr>
		<tr>
			<td><div align="right">Turno  </div></td>
			<td>
				<?php
					$horaActual=date("H");
				?>
				<select name="cmb_turno" class="combo_box">
            		<option value="">Seleccionar Turno</option>
            		<option value="PRIMERA"<?php if($horaActual>=6 && $horaActual<14) echo " selected='selected'";?>>Turno de Primera</option>
            		<option value="SEGUNDA"<?php if($horaActual>=14 && $horaActual<22) echo " selected='selected'";?>>Turno de Segunda</option>
					<option value="TERCERA"<?php if($horaActual>=22 || $horaActual<6) echo " selected='selected'";?>>Turno de Tercera</option>
				</select>	
			</td>
		</tr>
	</table>			
	</fieldset>
	
	<div align="center" class="borde_seccion2" id="materiales" name="materiales" style="visibility:hidden">
	<p class="titulo_etiqueta" >Seleccionar el Material que ser&aacute; entregado al Trabajador</p>
	<table width="460" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr class="nombres_filas">
			<td colspan="3" align="center" class="nombres_columnas">Equipo de Seguridad</td>
		</tr>
		<tr>
			<td colspan="3" align="center" class="renglon_blanco">
				<input name="ckbTodo" type="checkbox" id="ckbTodo" onclick="checarTodos(this);" value="Todo"/><strong>Seleccionar Todo</strong>
			</td>
		</tr><?php 
			$conec=conecta("bd_almacen");
			$stm_sql = "SELECT id_material, nom_material, IFNULL( descripcion,  'SIN CATEGORIA' ) AS descripcion
						FROM materiales
						LEFT JOIN categorias_mat ON categoria = id_categoria
						WHERE id_material LIKE  'SEGURIDAD%'
						OR linea_articulo =  'EQUIPO DE SEGURIDAD'
						ORDER BY  `categorias_mat`.`descripcion` ASC ";
			$rs=mysql_query($stm_sql);
			if($row = mysql_fetch_array($rs)){
				$cont=1;
				$nom_clase="renglon_gris";
				do {
					//Verificamos la existencia del material
					$existencia=obtenerDato("bd_almacen", "materiales", "existencia", "id_material", $row['id_material']);
					//Variable que nos permitira asignar el atributo disabled
					$atributo = "";
					//Variable para Mostrar mensaje dependiendo de la existencia va por default material con cambio en caso de que la existencia sea cero entrara a la
					//condici�n posterior y cambiara su valor
					$msg = "MATERIAL C/CAMBIO";
					//Variable para controlar el tipo de elemento
					$elemento = "checkbox";
					//Verificamos la existencia
					if($existencia==0){
						$atributo="disabled='disabled'";
						$msg = "<label class='msje_incorrecto'>MATERIAL SIN EXISTENCIA</label>";
						$elemento = "hidden";
					}
					if(isset($_POST["id_kiosco"])){
						$existe = comprobarEPPVale($_POST["id_kiosco"],$row["id_material"]);
						if($existe){
							echo "
							<tr>
								<td align='left' class='$nom_clase' width='127'>
									<input ".$atributo." name='ckb".$cont."' type='checkbox' onclick='desSeleccionar(this);verificarValEquipoSeguridad(this);' id='ckb".$cont."' value='".$row['id_material']."' checked />".$row['nom_material']."
								</td>
								<td class='$nom_clase' width='127'>
									<input ".$atributo." name='ckb_".$cont."' type='$elemento' onclick='desSeleccionar(this);verificarValEquipoSeguridad(this);' id='ckb_".$cont."' value='SI'/>".$msg."
								</td>
								<td align='left' class='$nom_clase'>";
									$clave_cat = strtoupper(obtenerDatoTabla("materiales","categoria","id_material",$row['id_material'],"bd_almacen"));
									if($clave_cat != "SIN CATEGORIA"){
										$cat = strtoupper(obtenerDatoTabla("categorias_mat","descripcion","id_categoria",$clave_cat,"bd_almacen"));
										echo "<input type='hidden' name='cmb_catMat$cont' id='cmb_catMat$cont' value='$clave_cat'/>$cat";
									}
									else{
										echo "
										<select name='cmb_catMat$cont' id='cmb_catMat$cont'>
											<option value=''>Categorias</option>
										";
											$conn1 = conecta("bd_almacen");
											$rs_cat = mysql_query("SELECT * FROM categorias_mat WHERE habilitado='SI' ORDER BY descripcion");
											if($catMat = mysql_fetch_array($rs_cat)){
												do{
													if($row['id_material'] == $catMat["id_categoria"]){
														echo "
														<option value='$catMat[id_categoria]' selected='selected' >$catMat[descripcion]</option>
														";
													} else {
														echo "
														<option value='$catMat[id_categoria]'>$catMat[descripcion]</option>
														";
													}
												}while($catMat = mysql_fetch_array($rs_cat));
											}
										echo "
										</select>
										";
							}
							echo "
								</td>
							</tr>";
						} else{
							echo "
							<input name='ckb$cont' id='ckb$cont' type='hidden' value='no_valido'/>
							<input name='ckb_$cont' id='ckb_$cont' type='hidden'/>
							<input name='cmb_catMat$cont' id='cmb_catMat$cont' type='hidden'/>
							";
						}/*else {
							echo "
							<tr>
								<td align='left' class='$nom_clase' width='127'>
									<input ".$atributo." name='ckb".$cont."' type='checkbox' onclick='desSeleccionar(this);verificarValEquipoSeguridad(this);' id='ckb".$cont."' value='".$row['id_material']."'/>".$row['nom_material']."
								</td>
								<td class='$nom_clase' width='127'>
									<input ".$atributo." name='ckb_".$cont."' type='$elemento' onclick='desSeleccionar(this);verificarValEquipoSeguridad(this);' id='ckb_".$cont."' value='SI'/>".$msg."
								</td>
							</tr>";
						}*/
					} else {
						echo "
							<tr>
								<td align='left' class='$nom_clase' width='127'>
									<input ".$atributo." name='ckb".$cont."' type='checkbox' onclick='desSeleccionar(this);verificarValEquipoSeguridad(this);' id='ckb".$cont."' value='".$row['id_material']."'/>".$row['nom_material']."
								</td>
								<td class='$nom_clase' width='127'>
									<input ".$atributo." name='ckb_".$cont."' type='$elemento' onclick='desSeleccionar(this);verificarValEquipoSeguridad(this);' id='ckb_".$cont."' value='SI'/>".$msg."
								</td>
								<td align='left' class='$nom_clase'>";
									$clave_cat = strtoupper(obtenerDatoTabla("materiales","categoria","id_material",$row['id_material'],"bd_almacen"));
									if($clave_cat != "SIN CATEGORIA"){
										$cat = strtoupper(obtenerDatoTabla("categorias_mat","descripcion","id_categoria",$clave_cat,"bd_almacen"));
										echo "<input type='hidden' name='cmb_catMat$cont' id='cmb_catMat$cont' value='$clave_cat'/>$cat";
									}
									else{
										echo "
										<select name='cmb_catMat$cont' id='cmb_catMat$cont'>
											<option value=''>Categorias</option>
										";
											$conn1 = conecta("bd_almacen");
											$rs_cat = mysql_query("SELECT * FROM categorias_mat WHERE habilitado='SI' ORDER BY descripcion");
											if($catMat = mysql_fetch_array($rs_cat)){
												do{
													if($row['id_material'] == $catMat["id_categoria"]){
														echo "
														<option value='$catMat[id_categoria]' selected='selected' >$catMat[descripcion]</option>
														";
													} else {
														echo "
														<option value='$catMat[id_categoria]'>$catMat[descripcion]</option>
														";
													}
												}while($catMat = mysql_fetch_array($rs_cat));
											}
										echo "
										</select>
										";
							}
							echo "
								</td>
							</tr>";
					}
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
				}while ($row = mysql_fetch_array($rs));
				echo "<input type='hidden' name='num_mat' id='num_mat' value='$cont' />";
			}?>
    </table>
	</div>
	
	<div id="botones">
	<table align="center">
		<tr>
			<td><?php 
				if($rfc_seleccionado!=""){?>
					<input type="button" name="registrar" id="registrar" class="botones" value="Registrar" onMouseOver="window.status='';return true" 
                    title="Registra Equipo de Seguridad" onclick="document.getElementById('registrar').type='submit'"/><?php
                 }?>
				&nbsp;&nbsp;&nbsp;
				<input type="reset" class="botones" value="Limpiar" title="Limpiar Formulario" />
				&nbsp;&nbsp;&nbsp;
				<input type="button" onclick="location.href='frm_salidaMaterial.php'" class="botones" value="Cancelar" title="Regresar a la P�gina de Salida de Material" />
			</td>
		</tr>
	</table>
	</div>
	</form><?php
	if(isset($_POST["id_empl"])){
		?>
		<script>
			setTimeout("txt_codigo.focus()",400);
			setTimeout("txt_codigo.value=<?php echo $_POST["id_empl"]; ?>",500);
			setTimeout("txt_codigo.onchange()",600);
		</script>
		<?php
	}
	}//Fin if(!isset($_POST['registrar']))
	else{
		?>
		<div class="titulo_etiqueta" id="procesando">
      		<div align="center">
				<?php regSalidaMatSeguridad(); ?>
        		<p><img src="../../images/loading.gif" width="70" height="70"  /></p>
        		<p>Procesando...</p>
      		</div>
		</div>
		<?php
	}?>	
</body>
<?php  }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>