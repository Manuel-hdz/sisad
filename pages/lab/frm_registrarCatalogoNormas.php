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
		include("head_menu.php");
		include("op_registrarCatalogoNormas.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionLaboratorio.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js" ></script>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-agregar { position:absolute; left:30px; top:146px; width:230px; height:20px; z-index:11; }
		#tabla-agregarNorma {position:absolute;left:30px;top:190px;width:753px;height:281px;z-index:12;}
		#tabla-mostrarMateriales {position:absolute;left:30px;top:500px;width:753px;height:180px;z-index:13; overflow:scroll}
		-->
    </style>
</head>
<body><?php 

	//Comprobamos si existe en el GET la variable noRegistro si es asi quiere decir que se presiono la imagen-boton borrar
	if(isset($_GET["noRegistro"])){
		//Si es asi liberar la sesion
		unset($_SESSION["catNormas"][$_GET["noRegistro"]]);
		//Verificamos que exista la session y el parametro que contiene la ubicacion del registro dentro de la sesion
		if(isset($_SESSION["catNormas"])&&isset($_GET["noRegistro"]))
			//Reacomodamos el Arreglo
			$_SESSION['catNormas'] = array_values($_SESSION['catNormas']);
	
		//Verificamos si exista la sesion
		if(isset($_SESSION["catNormas"])){
			//Si el arreglo de lista_maestra esta vacio, retirarlo de la SESSION
			if(count($_SESSION["catNormas"])==0){
				//Liberamos la sesion
				unset($_SESSION["catNormas"]);
			}
		}
		
	}//Cierre if(isset($_GET["noRegistro"]))
	//Verificamos que exista el boton agregar para poder agregar los datos en la session
	if(isset($_POST["sbt_agregar"])){
		if(isset($_POST['hdn_claveValida'])&&$_POST['hdn_claveValida']!='no'){
			//Verificar que en el POST esten los datos definidos a fin de agregarlos al arreglo de Sesion
			//Si ya esta definido el arreglo $catNormas, entonces agregar el siguiente registro a el
			if(isset($_SESSION['catNormas'])){
				//Comprobar que dentro de la tabla donde se registran servicios de normas no se observen registros duplicados dentro de dicha tabla para el mismo registro
				$regDuplicado = 0;
				
				foreach($_SESSION['catNormas'] as $ind => $registro){
				$concepto =strtoupper($_POST['txt_concepto']);
					if($concepto==$registro['concepto'] && $_POST['cmb_norma']==$registro['norma']&&$_POST['cmb_agregado']==$registro['agregado']){
						$regDuplicado = 1;
						break;	
					}
				}
				if($regDuplicado==0){
					$_SESSION['catNormas'][] = array("concepto"=>strtoupper($_POST['txt_concepto']),"norma"=>$_POST['cmb_norma'], "agregado"=>$_POST['cmb_agregado'], "limiteInf"=>str_replace(",","",$_POST['txt_limInf']),
										"limiteSup"=>str_replace(",","",$_POST['txt_limSup']));						
				}
				else{
					//Declarar variable que va a almacenar el mensaje cuando ya exista un registro ?>
					<script language="javascript" type="text/javascript">
						setTimeout("alert('El Concepto ya se Encuentra Agregado a la Norma <?php echo $_POST['cmb_norma']." con el Agregado ".$_POST['cmb_agregado']?>')",500);
					</script>
					<?php 
				}					
			}
			//Si no esta definido el arreglo $catNormas definirlo y agregar el primer registro
			else{
				$nomMaterial=obtenerDato('bd_almacen', 'materiales', 'nom_material','id_material', $_POST["cmb_agregado"]);
				//Guardar los datos en el arreglo
				$_SESSION['catNormas'] = array(array("concepto"=>strtoupper($_POST['txt_concepto']),"norma"=>$_POST['cmb_norma'], "agregado"=>$nomMaterial, "limiteInf"=>str_replace(",","",$_POST['txt_limInf']),
							"limiteSup"=>str_replace(",","",$_POST['txt_limSup'])));
			}
		}
		else{
			//Declarar variable que va a almacenar el mensaje cuando ya exista un registro?>
			<script language="javascript" type="text/javascript">
				setTimeout("alert('El Concepto ya se Encuentra Registrado con la Norma y Agreado Seleccionados')",500);
			</script>
			<?php 
		}
	}
?>


	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-agregar">Agregar Catalogo de Normas </div>
	
	<fieldset class="borde_seccion" id="tabla-agregarNorma">
	<legend class="titulo_etiqueta">Ingrese Informaci&oacute;n</legend>	
	<br>
	<form name="frm_catalogo"  id="frm_catalogo" method="post" action="frm_registrarCatalogoNormas.php" onsubmit="return valFormCatalogo(this);">
	<table width="749" height="253" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
		<?php if(!isset($_SESSION['catNormas'])){?>
			<td width="125" height="32"><div align="right">*Norma</div></td>	
			<td width="213"><?php 
				$cmb_norma="";
				$conn = conecta("bd_laboratorio");
				$result=mysql_query("SELECT DISTINCT norma FROM catalogo_pruebas WHERE norma NOT LIKE 'N/A' ORDER BY norma");
				if($normas=mysql_fetch_array($result)){?>
					<select name="cmb_norma" id="cmb_norma" size="1" class="combo_box" onchange="verificarNorma(this, cmb_agregado);" >
						<option value="">Normas</option><?php 
							do{
								if ($normas['norma'] == $normas){
									echo "<option value='$normas[norma]' selected='selected'>$normas[norma]</option>";
								}
								else{
									echo "<option value='$normas[norma]'>$normas[norma]</option>";
								}
							}while($normas=mysql_fetch_array($result)); 
						//Cerrar la conexion con la BD		
						mysql_close($conn);?>
					</select>
					</br>
					<span id='error' class="msj_error">Norma Duplicada</span>
				<?php }
				else{
					echo "<label class='msje_correcto'> No hay Normas Registradas</label>
					<input type='hidden' name='cmb_norma' id='cmb_norma'/>";
				}?>	
				</td>
		 	 <td width="136"><div align="right">*Agregado</div></td>
		  	<td width="359" colspan="4"><?php 
				$cmb_agregado="";
				$conn = conecta("bd_almacen");
				$result=mysql_query("SELECT DISTINCT id_material,nom_material FROM materiales WHERE grupo='PLANTA' AND linea_articulo='AGREGADO' ORDER BY nom_material");
				if($agregados=mysql_fetch_array($result)){?>
					<select name="cmb_agregado" id="cmb_agregado" size="1" class="combo_box"  onchange="verificarNorma(cmb_norma, this);">
						<option value="">Agregados</option><?php 
							do{
								if ($agregados['nom_material'] == $agregados){
									echo "<option value='$agregados[id_material]' selected='selected'>$agregados[nom_material]</option>";
								}
								else{
									echo "<option value='$agregados[id_material]'>$agregados[nom_material]</option>";
								}
							}while($agregados=mysql_fetch_array($result)); 
						//Cerrar la conexion con la BD		
						mysql_close($conn);?>
					</select><?php
				}
				else{
					echo "<label class='msje_correcto'> No hay Agregados Registrados</label>
					<input type='hidden' name='cmb_agregado' id='cmb_agregado'/>";
				}?>	
			</td>
	  	</tr>
	  <?php }else{?>
	  <tr>
		  <td height="31"><div align="right">*Norma</div></td>
			<td>
				<input name="txt_norma" id="txt_norma" type="text" class="caja_de_texto" size="40" maxlength="40" readonly="readonly" value="<?php echo $_SESSION["catNormas"][0]['norma']; ?>"/>
				<input type="hidden" name="cmb_norma" id="cmb_norma" class="caja_de_texto" size="40" maxlength="40" readonly="readonly" value="<?php echo $_SESSION["catNormas"][0]['norma']; ?>"/>
			</td>
			<td><div align="right">*Agregado</div></td>
			<td>
				<input name="txt_agregado" id="txt_agregado" type="text" class="caja_de_texto" size="40" maxlength="40"  readonly="readonly" value="<?php echo $_SESSION["catNormas"][0]['agregado']; ?>"/>
				<input  type="hidden" name="cmb_agregado" id="cmb_agregado" class="caja_de_texto" size="40" maxlength="40"  readonly="readonly" value="<?php echo $_SESSION["catNormas"][0]['agregado']; ?>"/>
			</td>
	  </tr>
	  <?php }?>
		<tr>
		  	<td height="31"><div align="right">*Concepto</div></td>
			<td><input name="txt_concepto" id="txt_concepto" type="text" class="caja_de_texto" size="40" maxlength="40" onkeypress="return permite(event,'num_car', 0);"/></td>
			<td><div align="right">*L&iacute;mite Superior</div></td>
			<td><input name="txt_limSup" id="txt_limSup" type="text" class="caja_de_texto" size="40" maxlength="40" onkeypress="return permite(event,'num', 2);" onchange="formatCurrency(value,'txt_limSup')"/></td>
	  	</tr>
		<tr>
		<tr>
		  	<td height="31"><div align="right">*L&iacute;mite Inferior</div></td>
			<td><input name="txt_limInf" id="txt_limInf" type="text" class="caja_de_texto" size="40" maxlength="40" onkeypress="return permite(event,'num', 2);" onchange="formatCurrency(value,'txt_limInf')"/></td>
		</tr>
		<tr>
			<td height="42" colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u>.</strong><br /></td></tr>
		<tr>
			<td colspan="6">
				<div align="center">
					<input type="hidden" name="hdn_claveValida" id="hdn_claveValida" value="si"/> 
					<input type="hidden" name="hdn_botonSeleccionado" id="hdn_botonSeleccionado" value="" />     	    	
					<?php if(isset($_SESSION["catNormas"])){?>
						<input name="sbt_finalizar" type="submit" class="botones"  value="Finalizar" title="Finalizar Registro" onMouseOver="window.status='';return true" onclick="hdn_botonSeleccionado.value='sbt_finalizar';" />
						&nbsp;&nbsp;&nbsp;
					<?php }?>
					<input name="sbt_agregar" type="submit" class="botones"  value="Agregar" title="Agregar Registros" onMouseOver="window.status='';return true"  onclick="hdn_botonSeleccionado.value='sbt_agregar'" />
					&nbsp;&nbsp;&nbsp;
					<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Reestablecer Formulario" 
					onMouseOver="window.status='';return true"/>
                    &nbsp;&nbsp;&nbsp;
                    <input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Pruebas" 
                    onMouseOver="window.status='';return true" onclick="confirmarSalida('frm_opcionesCatalogoNormas.php')" />
                </div>			</td>
		</tr>
	</table>
	</form>
</fieldset>
<?php 
	//Verificar que este definido el Arreglo de Mecanicos, si es asi, lo mostramos en el formulario
	if (isset($_SESSION["catNormas"])){
		echo "<div id='tabla-mostrarMateriales' class='borde_seccion2'>";
		mostrarMateriales();
		echo "</div>";
	}
?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>