<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Topografía
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include("head_menu.php");
		include("op_registrarBitEquipo.php");
		
	?>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionTopografia.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

	<style type="text/css">
		<!--
		#titulo-modificar {position:absolute;left:30px;top:146px;width:362px;height:20px;z-index:11;}
		#tabla-seleccionarObra {position:absolute;left:30px;top:190px;width:676px;height:160px;z-index:12;}
		#calendarioObra {position:absolute;left:733px;top:268px;width:30px;height:26px;z-index:13;}
		#tabla-registrarObra {position:absolute;left:30px;top:190px;width:723px;height:263px;z-index:14;}
		#mostrar-Equipos {position:absolute;left:30px;top:385px;width:676px;height:267px;z-index:15; overflow:scroll;}
		-->
    </style>
</head>
<body>	
	
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-modificar">Registrar Obras con Equipo Pesado </div>
	
	<?php
	if(isset($_POST["sbt_agregar"])){
		$equipo=$_POST["cmb_equipo"];
		$cantidad=$_POST["txt_cantidad"];
		//Si ya esta definido el arreglo $registroEquipos, entonces agregar el siguiente registro a el
		if(isset($_SESSION['registroEquipos'])){
			if(!isset($_SESSION['registroEquipos'][$equipo]))
				//Guardar los datos en el arreglo
				$_SESSION['registroEquipos'][$equipo] = $cantidad;
			else{
				?>
				<script type="text/javascript" language="javascript">
					setTimeout("mensaje();",1000);							
					function mensaje(){
						alert("El Equipo <?php echo $equipo;?> ya se Encuentra en el Registro");
					}
				</script><?php
			}
		}
		//Si no esta definido el arreglo $registroEquipos definirlo y agregar el primer registro
		else
			$_SESSION['registroEquipos'][$equipo] = $cantidad;
	}
	
	if(!isset($_POST["sbt_seleccionarObra"]) && !isset($_POST["sbt_agregar"]) && !isset($_GET["idReg"])){
		if(isset($_SESSION["registroEquipos"]))
			unset($_SESSION["registroEquipos"]);
	?>			
		<fieldset class="borde_seccion" id="tabla-seleccionarObra" name="tabla-seleccionarObra">
		<legend class="titulo_etiqueta">Seleccionar la Obra de Equipo a Registrar</legend>	
		<br>
		<form onSubmit="return valFormSeleccionarObraEq(this);" name="frm_modificarObraEqP" method="post" action="frm_registrarEquipoPesado.php">
		<table cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
			<tr>
			  <td width="122"><div align="right">*Tipo de Equipo </div></td>
				<td width="517"><?php									
					$res = cargarComboConId("cmb_tipoObraEqP","fam_equipo","fam_equipo","equipo_pesado","bd_topografia","Tipo Equipo","",
											"cargarComboConId(this.value,'bd_topografia','equipo_pesado','concepto','id_registro','fam_equipo','cmb_nomObraEq','Obras Equipo Pesado','')");									
					if($res==0){?>
						<label class="msje_correcto"><u><strong>NO</strong></u> Hay Tipos de Obras Registradas</label>
			  <input type="hidden" name="cmb_tipoObraEqP" id="cmb_tipoObraEqP" value="" /><?php 
					} ?>		  	</td>
			</tr>
			<tr>
				<td><div align="right">*Nombre de la Obra de Equipo </div></td>
				<td>
					<select name="cmb_nomObraEq" id="cmb_nomObraEq" class="combo_box" >
						<option value="">Obras Equipo Pesado</option>
					</select>
				</td>
			</tr>
	
			<tr>
				<td colspan="4" align="center">
					<input name="sbt_seleccionarObra" type="submit" class="botones" id="sbt_seleccionarObra"  value="Seleccionar" 
					title="Seleccionar la Información de la Obra a ser Modificada" 
					onmouseover="window.status='';return true" />
					&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" title="Cancelar y Regresar al Men&uacute; de Obras con Equipo Pesado" 
					onmouseover="window.status='';return true" onclick="location.href='menu_equipoPesado.php';"/>
					&nbsp;&nbsp;&nbsp;
			</td>
			</tr>
		</table>
		</form>
		</fieldset>
	<?php
	}
	else{
		if(isset($_POST["cmb_nomObraEq"])){
			$idReg=$_POST["cmb_nomObraEq"];
			$tipoEquipo=$_POST["cmb_tipoObraEqP"];
		}
		if(isset($_GET["idReg"])){
			$idReg=$_GET["idReg"];
			$tipoEquipo=$_GET["familia"];
		}
		//Obtener la unidad de Medida
		$unidad=obtenerDato("bd_topografia","equipo_pesado","unidad","id_registro",$idReg);
		?>
		<fieldset class="borde_seccion" id="tabla-seleccionarObra" name="tabla-seleccionarObra">
		<legend class="titulo_etiqueta">Seleccionar los Equipos Utilizados</legend>	
		<br>
		<form onsubmit="return vslFormSeleccionarEquipo(this);" name="frm_registrarDatosEquipo" method="post" action="frm_registrarEquipoPesado.php">
		<input type="hidden" name="cmb_nomObraEq" id="cmb_nomObraEq" value="<?php echo $idReg?>"/>
		<input type="hidden" name="cmb_tipoObraEqP" id="cmb_tipoObraEqP" value="<?php echo $tipoEquipo?>"/>
		<table cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
			<tr>
				<td><div align="right">Tipo de Equipo</div></td>
				<td>
					<input type="text" name="txt_familia" id="txt_familia" value="<?php echo $tipoEquipo?>" class="caja_de_texto" readonly="readonly" size="20"/>
				</td>
				<td><div align="right">Unidad Medida</div></td>
				<td>
					<input type="text" name="txt_unidad" id="txt_unidad" value="<?php echo $unidad?>" class="caja_de_texto" readonly="readonly" size="10"/>
				</td>
			</tr>
			<tr>
				<td><div align="right">*Equipo</div></td>
				<td>
					<?php								
					$conn = conecta("bd_mantenimiento");//Conectarse a la Base de Datos
					$result = mysql_query("SELECT id_equipo FROM equipos WHERE familia = '$tipoEquipo' AND disponibilidad = 'ACTIVO' 
											ORDER BY id_equipo");
					if($registro=mysql_fetch_array($result)){?>				
						<select name="cmb_equipo" id="cmb_equipo" class="combo_box" tabindex="4">
							<option value="">Equipo</option><?php															 
							do{?>
								<option value="<?php echo $registro['id_equipo']; ?>" title="<?php echo $registro['id_equipo']; ?>">
									<?php echo $registro['id_equipo']; ?>
								</option>
							<?php
							}while($registro=mysql_fetch_array($result))?>
						</select><?php
					} else {?>
						<span class="msje_correcto">No Hay Equipos Registrados</span><?php
					}
					mysql_close($conn);//Cerrar la conexion con la BD ?>
				</td>
				<td><div align="right">*Cantidad</div></td>
				<td>
					<input type="text" name="txt_cantidad" id="txt_cantidad" class="caja_de_texto" size="7" maxlength="15" onkeypress="return permite(event,'num',2);"
					value="" onchange="formatCurrency(this.value,'txt_cantidad')" />
				</td>
			</tr>
			<tr>
				<td colspan="4" align="center">
					<?php if(isset($_SESSION["registroEquipos"])){?>
					<input name="sbt_finalizar" type="button" class="botones" id="sbt_finalizar"  value="Finalizar" 
					title="Agregar el Registro" onclick="location.href='frm_registrarEquipoPesado2.php?familia=<?php echo $tipoEquipo?>&idReg=<?php echo $idReg?>'"/>
					&nbsp;&nbsp;&nbsp;
					<?php }?>
					<input name="sbt_agregar" type="submit" class="botones" id="sbt_agregar"  value="Agregar" 
					title="Agregar el Registro" onmouseover="window.status='';return true" />
					&nbsp;&nbsp;&nbsp;
					<input type="reset" name="btn_reset" id="btn_reset" class="botones" value="Limpiar" title="Limpia el Formulario"/>
					&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" title="Cancelar y Regresar al Men&uacute; de Obras con Equipo Pesado" 
					onmouseover="window.status='';return true" onclick="confirmarSalida('frm_registrarEquipoPesado.php');"/>
					&nbsp;&nbsp;&nbsp;
			</td>
			</tr>
		</table>
		</form>
		</fieldset>
		
		<?php if(isset($_SESSION["registroEquipos"])){?>
		<div id="mostrar-Equipos" class="borde_seccion2" align="center">
			<?php
				echo "<table width='80%' cellpadding='5'>";
				echo "<caption class='titulo_etiqueta'>EQUIPOS Y CANTIDAD REGISTRADA</caption>";
				echo "      			
					<tr>
						<td class='nombres_columnas' align='center'>EQUIPO</td>
						<td class='nombres_columnas' align='center'>CANTIDAD</td>
					</tr>";
				$nom_clase = "renglon_gris";
				$cont = 1;
				$total=0;
				foreach($_SESSION["registroEquipos"] as $ind => $value){
					echo "<tr>
							<td class='$nom_clase' align='center'>$ind</td>
							<td class='$nom_clase' align='center'>$value</td>
						</tr>
					";
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
					$total+=$value;
				}
				echo "<tr>
							<td class='nombres_columnas' align='right'><strong>TOTAL</strong></td>
							<td class='nombres_filas' align='center'>".number_format($total,2,".",",")."</td>
						</tr>
					";
			?>
		</div>
		<?php
		}
	}?>

</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>