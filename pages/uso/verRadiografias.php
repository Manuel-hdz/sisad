<?php
	/**
	  * Nombre del M�dulo: Desarrollo                                               
	  * Nombre Programador: Antonio de Jes�s Jim�nez Cuevas
	  * Fecha: 07/Noviembre/2011
	  * Descripci�n: En este archivo estan las funciones para asignar los Bonos
	  **/ 
	
	//M�dulo de conexi�n a la BD
	include ("../../includes/conexion.inc");
	//Operaciones con la Base de Datos
	include("../../includes/op_operacionesBD.php");
	//Manejo de fechas
	include ("../../includes/func_fechas.php");
	//Abrir la Sesion para guardar las Radiografias ejecutadas al empleado
	session_start();
	?>
	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css"/>	
	<script type="text/javascript" src="../../includes/validacionClinica.js"></script>
	<script type="text/javascript" src="../../includes/formatoNumeros.js"></script>
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
		function habilitarBoton(){
			if(document.getElementById("hdn_cerrar").value=="1"){
				window.opener.document.getElementById("<?php echo $_GET["btn"]?>").disabled=false;
				window.close();
			}
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
		<label class="titulo_etiqueta">Registrar Radiograf&iacute;as Realizadas</label>
		<br />
		<?php
			$idBit=$_GET["idBitacora"];
			$nomEmpleado=strtoupper($_GET["nombre"]);
			$numEmpleado=$_GET["num"];
			//Usar el ID de bitacora para ver si ya fue guardado el registro
			$existe=obtenerDato("bd_clinica","bitacora_radiografias","id_bit_radiografias","id_bit_radiografias",$idBit);
			if($existe!=""){
				//Si el id de bitacora existe, extraemos los ID de las proyecciones guardadas para mostrarlas en la ventana emergente
				$conn=conecta("bd_clinica");
				$stm_sql="SELECT catalogo_radiografias_id_proyeccion FROM detalle_radiografia WHERE bitacora_radiografias_id_bit_radiografias='$idBit'";
				$rs=mysql_query($stm_sql);
				//Arreglo que contendra los ID de las radiografias
				$idRadio=array();
				//Extraccion de los Id de Radiografias
				if($regRadiografias=mysql_fetch_array($rs)){
					do{
						$idRadio[]=$regRadiografias["catalogo_radiografias_id_proyeccion"];
					}while($regRadiografias=mysql_fetch_array($rs));
				}
				//Cerrar la conexion con la BD
				mysql_close($conn);
			}
		?>
		<form name="frm_asignarRadiografia" method="post" onsubmit="return valFormSelRadiografias(this);">
		<table class="tabla_frm" cellpadding="5" cellspacing="5" width="100%">
			<tr>
				<td><div align="right">Id Bit&aacute;cora</div></td>
				<td><input type="text" class="caja_de_texto" readonly="readonly" value="<?php echo $idBit?>" name="txt_idBit" id="txt_idBit" size="10"/></td>
				<td><div align="right">Nombre Empleado</div></td>
				<td><input type="text" class="caja_de_texto" readonly="readonly" value="<?php echo $nomEmpleado?>" name="txt_idBit" id="txt_idBit" size="50"/></td>
				<td><div align="right">N&uacute;mero Empleado</div></td>
				<td><input type="text" class="caja_de_texto" readonly="readonly" value="<?php echo $numEmpleado?>" name="txt_idBit" id="txt_idBit" size="10"/></td>
			</tr>
		</table>
		<div id='tabla-resultados' class="borde_seccion2">
			<?php
				//Abrir la conexion para buscar las proyecciones registradas en la Clinica
				$conn=conecta("bd_clinica");
				$stm_sql="SELECT id_proyeccion,nom_proyeccion,comentarios FROM catalogo_radiografias";
				$rs=mysql_query($stm_sql);
				if ($datos=mysql_fetch_array($rs)){
					?>
					<table class="tabla_frm" cellpadding="5" cellspacing="5" width="100%">
					<tr>
						<td class='nombres_columnas' width="15%" align='center'>ASIGNAR<input type="checkbox" id="ckbTodo" name="ckbTodo" onclick="checarTodos(this);"/></td>
						<td class='nombres_columnas' align='center'>CLAVE DE PROYECCI&Oacute;N </td>
						<td class='nombres_columnas' align='center'>NOMBRE DE PROYECCI&Oacute;N </td>
						<td class='nombres_columnas' align='center'>COMENTARIOS</td>
					</tr>
					<?php
					$nom_clase = "renglon_gris";
					$cont = 1;
					do{
						//Variable para saber si se debe checar o no un Checkbox
						$prop="";;
						//Verificar si el arreglo de radiografias desde la BD existe para saber que checkbox's se deben checar
						if(isset($idRadio)){
							foreach($idRadio as $ind => $value){
								if($datos["id_proyeccion"]==$value){
									$prop=" checked='checked'";
									break;
								}
							}
						}
						//Verificar si el arreglo de radiografias existe para saber que checkbox's se deben checar
						if(isset($_SESSION["radiografias"])){
							foreach($_SESSION["radiografias"] as $ind => $value){
								if($datos["id_proyeccion"]==$value){
									$prop=" checked='checked'";
									break;
								}
							}
						}
						?>
						<tr>	
							<td class='<?php echo $nom_clase;?>' align='center'>
								<input type="checkbox" name="ckb_radiografia<?php echo $cont;?>" id="ckb_radiografia<?php echo $cont;?>" value="<?php echo $datos["id_proyeccion"];?>"<?php echo $prop?>/>
							</td>
							<td class='<?php echo $nom_clase;?>' align='center'>
								<?php echo $datos["id_proyeccion"];?>
							</td>
							<td class='<?php echo $nom_clase;?>' align='center'>
								<?php echo $datos["nom_proyeccion"];?>
							</td>
							<td class='<?php echo $nom_clase;?>' align='center'>
								<?php echo $datos["comentarios"];?>
							</td>
						</tr>
						<?php
						//Determinar el color del siguiente renglon a dibujar
						$cont++;
						if($cont%2==0)
							$nom_clase = "renglon_blanco";
						else
							$nom_clase = "renglon_gris";
					}while($datos=mysql_fetch_array($rs));
					echo "</table>";
				}
				else{
					$cont=0;
					echo "<br><br><br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No Hay Radiograf&iacute;as Registradas en el Sistema</p>";
				}
			?>
		</div>
		<div id="botones" align="center">
			<input type="hidden" name="cant_ckbs" id="cant_ckbs" value="<?php echo $cont?>"/>
			<input type="hidden" name="hdn_cerrar" id="hdn_cerrar" value="1"/>
			<?php if($cont>0){?>
				<input type="submit" name="sbt_registrar" id="sbt_registrar" class="botones" value="Registrar" title="Registrar las Radiograf&iacute;as al Trabajador"/>
			<?php }
			else{?>
				<input type="submit" name="sbt_registrar" id="sbt_registrar" class="botones" value="Registrar" title="No Hay Radiograf&iacute;as que Registrar al Trabajador" disabled="disabled"/>
			<?php }?>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="button" class="botones" value="Cancelar" title="Cancelar y Cerrar la Ventana" onclick="habilitarBoton();"/>
		</div>
		</form>
		
		<?php
			if(isset($_POST["sbt_registrar"])){
				//Si el arreglo de Radiografias ya esta declarado borrarlo
				if(isset($_SESSION["radiografias"]))
					unset($_SESSION["radiografias"]);
				$cont=1;
				$cant=0;
				do{
					if(isset($_POST["ckb_radiografia$cont"])){
						$_SESSION["radiografias"][]=$_POST["ckb_radiografia$cont"];
						$cant++;
					}
					$cont++;
				}while($cont<=count($_POST));
				?>
				<script type="text/javascript" language="javascript">
					window.opener.document.getElementById("txt_cantProy").value=<?php echo $cant?>;
					window.opener.document.getElementById("<?php echo $_GET["btn"]?>").disabled=false;
					<?php if($cant>0){?>
						window.opener.document.getElementById("sbt_guardar").disabled=false;
					<?php }
					else{?>
						window.opener.document.getElementById("sbt_guardar").disabled=true;
					<?php }?>
					window.close();
				</script>
				<?php
			}
		?>
</body>
</html>