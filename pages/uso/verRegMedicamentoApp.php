<?php
	/**
	  * Nombre del M�dulo: USO                                               
	  * Nombre Programador: Antonio de Jes�s Jim�nez Cuevas
	  * Fecha: 07/Julio/2012
	  * Descripci�n: En este archivo estan las funciones para asignar los Medicamentos
	  **/ 
	
	//M�dulo de conexi�n a la BD
	include ("../../includes/conexion.inc");
	//Operaciones con la Base de Datos
	include("../../includes/op_operacionesBD.php");
	//Manejo de fechas
	include ("../../includes/func_fechas.php");
	//Abrir la Sesion para guardar los Medicamentos entregados al Empleado
	session_start();
	
	if(!isset($_SESSION["medicamento"])){
		if(isset($_GET["id"])){
			//Recuperar el ID de la bitacora
			$idBitacora=$_GET["id"];
			//Conectar a la BD de la Clinica
			$conn=conecta("bd_clinica");
			//Sentencia SQL
			$sql="SELECT id_med,nombre_med,cant_salida,unidad_despacho FROM catalogo_medicamento JOIN bitacora_medicamentos ON id_med=catalogo_medicamento_id_med WHERE bitacora_consultas_id_bit_consultas='$idBitacora'";
			$rs2=mysql_query($sql);
			if($datosMed=mysql_fetch_array($rs2)){
				do{
					$_SESSION["medicamento"][$datosMed["id_med"]]=$datosMed["cant_salida"];
				}while($datosMed=mysql_fetch_array($rs2));
			}
			//Cerrar la conexion a la BD
			mysql_close($conn);
		}
	}
	/*if(isset($_SESSION["medicamento"])){
		if(isset($_GET["idMed"])){
			unset($_SESSION["medicamento"][$_GET["idMed"]]);
			if(count($_SESSION["medicamento"])==0)
				unset($_SESSION["medicamento"]);
		}
	}
	*/
	?>
	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css"/>	
	<script type="text/javascript" src="../../includes/validacionClinica.js"></script>
	<script type="text/javascript" src="../../includes/formatoNumeros.js"></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="includes/ajax/cargarMedicamento.js"></script>

	<script type="text/javascript" language="javascript">
		<!--
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, �Concreto Lanzado de Fresnillo MARCA ');
			}
		}
		//-->
	</script>
	
	<script type="text/javascript" language="javascript">
		setTimeout("document.getElementById('cmb_clasificacion').focus()",500);
		
		function habilitarBoton(){
			<?php
			//Funcion que permite verificar si ya se agregaron Medicamentos al registro de consultas medicas
			if(isset($_SESSION["medicamento"]) || isset($_POST["sbt_registrar"])){
				echo "window.opener.document.getElementById('hdn_medicamento').value=1;";
				if(isset($_SESSION["medicamento"])){
					if(count($_SESSION["medicamento"])==1 && isset($_GET["idMed"]))
						echo "window.opener.document.getElementById('hdn_medicamento').value=0;";
				}
			}
			?>
			if(document.getElementById("hdn_cerrar").value=="1"){
				<?php
				if(isset($_SESSION["medicamento"]) && count($_SESSION["medicamento"])==1 && isset($_GET["idMed"]))
					echo "window.opener.document.getElementById('hdn_borrarMed').value=1;";
				?>
				window.opener.document.getElementById("<?php echo $_GET["btn"]?>").disabled=false;
				window.close();
			}
		}
		
		function valFormSelMedicamentos(frm_registrarMedicamento){
			var res=1;
			
			if(frm_registrarMedicamento.cmb_clasificacion.value==""){
				res=0;
				alert("Seleccionar la Clasificaci�n del Medicamento");
			}
			
			if(res==1 && frm_registrarMedicamento.cmb_medicamento.value==""){
				res=0;
				alert("Seleccionar el Medicamento");
			}
			
			if(res==1 && frm_registrarMedicamento.txt_entregado.value=="" || frm_registrarMedicamento.txt_entregado.value=="0"){
				res=0;
				alert("Ingresar la Cantidad Entregada por Unidad");
			}
			
			if(res==1 && parseInt(frm_registrarMedicamento.txt_entregado.value)>parseInt(frm_registrarMedicamento.txt_total.value)){
				res=0;
				alert("La Existencia Actual no Alcanza a cubrir la Demanda de salida");
				document.getElementById("txt_entregado").value="";
				document.getElementById("txt_entregado").focus();
			}
			
			if(res==1){
				frm_registrarMedicamento.hdn_cerrar.value='0';
				return true;
			}
			else
				return false;
		}
	</script>
	
	<style type="text/css">
		<!--
		#tabla-resultados{position:absolute; overflow:scroll; width:90%; height:410px;}
		#botones{position:absolute; left:30px;top:569px; width:90%;}
		-->
	</style>
	
</head>
		<body onunload="habilitarBoton()">
		<br />
		<label class="titulo_etiqueta">Registrar Medicamentos Suministrados en la Consulta M&eacute;dica</label>
		<br />
		<form name="frm_registrarMedicamento" method="post" onsubmit="return valFormSelMedicamentos(this);" action="verRegMedicamentoApp.php?btn=<?php echo $_GET["btn"]?>">
		<table class="tabla_frm" cellpadding="5" cellspacing="5" width="100%">
			<tr>
				<td width="73"><div align="right">Clasificaci&oacute;n</div></td>
				<td width="488">
					<?php 
					$conn = conecta("bd_clinica");		
					$stm_sql = "SELECT DISTINCT clasificacion_med FROM catalogo_medicamento ORDER BY clasificacion_med";
					$rs = mysql_query($stm_sql);
					//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
					if($datos = mysql_fetch_array($rs)){?>
						<select name="cmb_clasificacion" class="combo_box" id="cmb_clasificacion" tabindex="1" 
						onchange="cargarComboConId(this.value,'bd_clinica','catalogo_medicamento','nombre_med','id_med','clasificacion_med','cmb_medicamento','Medicamento','');obtenerMedicamentoDatos('')">
							<option value="" selected="selected">Clasificaci&oacute;n</option>
							<?php
							do{
								echo "<option value='$datos[clasificacion_med]'>$datos[clasificacion_med]</option>";
							}while($datos = mysql_fetch_array($rs));?>
						</select>
						<?php
					}
					else{
						echo "<label class='msje_correcto'>No hay Medicamento Registrado</label>
							<input type='hidden' name='cmb_clasificacion' id='cmb_clasificacion'/>";
					}
					//Cerrar la conexion con la BD		
					mysql_close($conn);	
				?>
				</td>
				<td width="137"><div align="right">Medicamento</div></td>
				<td width="866">
					<select name="cmb_medicamento" class="combo_box" id="cmb_medicamento" onchange="obtenerMedicamentoDatos(this.value);" tabindex="2">
						<option value="" selected="selected">Medicamento</option>
					</select>
				</td>
			</tr>
			<tr>
				<td width="137" valign="top"><div align="right">C&oacute;digo Medicamento</div></td>
				<td width="866" valign="top">
					<input type="text" class="caja_de_texto" name="txt_codigo" id="txt_codigo" readonly="readonly" size="5" />
				</td>
				<td valign="top"><div align="right">Existencia</div></td>
				<td>
					<textarea class="caja_de_texto" name="txa_existencia" id="txa_existencia" readonly="readonly" cols="40" rows="5"></textarea>
				</td>
			</tr>
			<tr>
				<td><div align="right">*Cantidad Suminstrada</div></td>
				<td>
					<input type="text" class="caja_de_num" name="txt_entregado" id="txt_entregado" size="10" maxlength="5" onkeypress="return permite(event,'num',3);" tabindex="3"/><span id="etiquetaUnidadDespacho1"></span>
				</td>
				<td><div align="right">*Cantidad Restante</div></td>
				<td>
					<input type="text" class="caja_de_num" name="txt_total" id="txt_total" readonly="readonly" size="10"/><span id="etiquetaUnidadDespacho2"></span>
				</td>
			</tr>
			<tr>
				<td colspan="4" align="center">
					<input type="hidden" name="hdn_cerrar" id="hdn_cerrar" value="1"/>
					<input type="submit" name="sbt_registrar" id="sbt_registrar" class="botones" value="Registrar" title="Registrar Otro Medicamento" tabindex="4"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<?php if(isset($_POST["sbt_registrar"])){?>
					<input type="button" name="btn_finalizar" id="btn_finalizar" class="botones" value="Finalizar" title="Terminar el Registro de Medicamento" onclick="habilitarBoton();" tabindex="5"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<?php }?>
					<input type="button" class="botones" value="Cerrar" title="Cerrar la Ventana Actual" onclick="habilitarBoton();" tabindex="6"/>
				</td>
			</tr>
		</table>
		</form>
		
		<?php
			$boton=$_GET["btn"];
			//unset($_SESSION["medicamento"]);
			if(isset($_POST["sbt_registrar"]))
				$_SESSION["medicamento"][$_POST["cmb_medicamento"]]=$_POST["txt_entregado"];
			
			if(isset($_SESSION["medicamento"])){
				if(isset($_GET["idMed"])){
					unset($_SESSION["medicamento"][$_GET["idMed"]]);
					if(count($_SESSION["medicamento"])==0)
						unset($_SESSION["medicamento"]);
				}
				//Volver a verificar si existe el arreglo de medicamento para comprobar que no se haya borrado todo
				if(isset($_SESSION["medicamento"])){
					//Conectarse a la BD
					$conn = conecta("bd_clinica");
					//Desplegar los resultados de la consulta en una tabla
					echo "
						<br>		
						<table cellpadding='5' width='100%'>
						<caption class='titulo_etiqueta'>Medicamentos Entregados</caption>
							<tr>
								<th class='nombres_columnas' align='center'>C&Oacute;DIGO<br>MEDICAMENTO</th>
								<th class='nombres_columnas' align='center'>NOMBRE<br>MEDICAMENTO</th>
								<th class='nombres_columnas' align='center'>CANTIDAD ENTREGADA REGISTRADA</th>
								<th class='nombres_columnas' align='center'>BORRAR</th>
							</tr>";
					$nom_clase = "renglon_gris";
					$cont = 1;
					foreach($_SESSION["medicamento"] as $ind=>$value){
						//Sentencia SQL
						$sql_stm="SELECT codigo_med,nombre_med,unidad_despacho FROM catalogo_medicamento WHERE id_med='$ind'";
						$rs=mysql_query($sql_stm);
						if($datos=mysql_fetch_array($rs)){
							echo " 
							<tr>
								<td class='$nom_clase' align='center'>$datos[codigo_med]</td>
								<td class='$nom_clase' align='center'>$datos[nombre_med]</td>
								<td class='$nom_clase' align='center'>$value</td>
								<td class='$nom_clase' align='center'>
									<input type=\"image\" src=\"../../images/borrar.png\" width=\"30\" height=\"25\" onclick=\"document.getElementById('hdn_cerrar').value='0';location.href='verRegMedicamentoApp.php?btn=$boton&idMed=$ind'\"/>
								</td>
							</tr>";
							//Determinar el color del siguiente renglon a dibujar
							$cont++;
							if($cont%2==0)
								$nom_clase = "renglon_blanco";
							else
								$nom_clase = "renglon_gris";
						}
					}
					mysql_close($conn);
				}
			}
		?>
</body>
</html>