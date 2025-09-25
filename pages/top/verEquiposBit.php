<?php
	/**
	  * Nombre del Módulo: Topografía                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 15/Agosto/2012
	  * Descripción: En este archivo estan las funciones para mostrar los Equipos y su cantidad
	  **/ 
	
	//Módulo de conexión a la BD
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
				alert ('Contenido Protegido, ©Concreto Lanzado de Fresnillo S.A. de C.V.');
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
		<label class="titulo_etiqueta">Equipos y Cantidades</label>
		<br />
		<?php
			$idBit=$_GET["idBit"];
			//Obtener la familia asociada al Equipo
			$familia=obtenerDato("bd_topografia","bitacora_eq_pesado","equipo_pesado_id_registro","idbitacora",$idBit);
			$familia=obtenerDato("bd_topografia","equipo_pesado","fam_equipo","id_registro",$familia);
			//Si el id de bitacora existe, extraemos los ID de las proyecciones guardadas para mostrarlas en la ventana emergente
			$conn=conecta("bd_topografia");
			$stm_sql="SELECT id_equipo,cantidad FROM detalle_eq_pesado WHERE bitacora_eq_pesado_idbitacora='$idBit'";
			$rs=mysql_query($stm_sql);
			//Arreglo que contendra los ID de los equipos
			$idEquipo=array();
			//Extraccion de los Id de Radiografias
			if($regEquipos=mysql_fetch_array($rs)){
				do{
					$idEquipo[$regEquipos["id_equipo"]]=$regEquipos["cantidad"];
				}while($regEquipos=mysql_fetch_array($rs));
			}
			//Cerrar la conexion con la BD
			mysql_close($conn);
		?>
		<form name="frm_asignarRadiografia" method="post">
			<?php
				//Abrir la conexion para buscar las proyecciones registradas en la Clinica
				$conn=conecta("bd_mantenimiento");
				$stm_sql="SELECT id_equipo FROM equipos WHERE familia='$familia' AND estado='ACTIVO'";
				$rs=mysql_query($stm_sql);
				if ($datos=mysql_fetch_array($rs)){
					?>
					<table class="tabla_frm" cellpadding="5" cellspacing="5" width="100%">
					<tr>
						<td class='nombres_columnas' align='center'>CLAVE DE EQUIPO</td>
						<td class='nombres_columnas' align='center'>CANTIDAD</td>
					</tr>
					<?php
					$nom_clase = "renglon_gris";
					$cont = 1;
					do{
						?>
						<tr>
							<td class='<?php echo $nom_clase;?>' align='center'>
								<?php echo $datos["id_equipo"];?>
							</td>
							<td class='<?php echo $nom_clase;?>' align='center'>
								<?php
								$band=0;
								foreach($idEquipo as $ind =>$value){
									if($datos["id_equipo"]==$ind){
										$band=1;
										break;
									}
								}
								if($band==1){
									?>
									<input type="text" name="txt_cantidad<?php echo $datos["id_equipo"];?>" id="txt_cantidad<?php echo $datos["id_equipo"];?>" class="caja_de_num"
									size="7" maxlength="15" onkeypress="return permite(event,'num',2);" value="<?php echo number_format($value,2,".",",")?>" onchange="formatCurrency(this.value,this.name)" />
								<?php
								}
								else{
								?>
									<input type="text" name="txt_cantidad<?php echo $datos["id_equipo"];?>" id="txt_cantidad<?php echo $datos["id_equipo"];?>" class="caja_de_num"
									size="7" maxlength="15" onkeypress="return permite(event,'num',2);" value="0.00" onchange="formatCurrency(this.value,this.name)" />
								<?php
								}?>
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
					echo "<br><br><br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No Hay Equipos Registrados en el Sistema</p>";
				}
			?>
			<br>
			<p align="center">
			<input type="hidden" name="hdn_cerrar" id="hdn_cerrar" value="1"/>
			<input type="submit" name="sbt_registrar" id="sbt_registrar" class="botones" value="Registrar" title="Registrar las Horas de los Equipo" onclick="hdn_cerrar.value='0'"/>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="button" class="botones" value="Cancelar" title="Cancelar y Cerrar la Ventana" onclick="habilitarBoton();"/>
			<input type="hidden" name="hdn_temp" id="hdn_temp" />
			</p>
		</form>
		
		<?php
			if(isset($_POST["sbt_registrar"])){
				$total=0;
				$idBit=$_GET["idBit"];
				foreach($_POST as $ind => $value){
					if(substr($ind,0,12)=="txt_cantidad"){
						$total+=$value;
						//Si el id de bitacora existe, extraemos los ID de las proyecciones guardadas para mostrarlas en la ventana emergente
						$conn=conecta("bd_topografia");
						//Obtener el Id del Equipo
						$idEquipo=str_replace("txt_cantidad","",$ind);
						//Ejecutar la actualizacion
						mysql_query("UPDATE detalle_eq_pesado SET cantidad=$value WHERE bitacora_eq_pesado_idbitacora='$idBit' AND id_equipo='$idEquipo'");
						//Si el registro no se actualizo, agregarlo
						if(mysql_affected_rows()==0){
							//Si el equipo ya existe en la BD No agregarlo
							$existe=mysql_fetch_array(mysql_query("SELECT id_equipo FROM detalle_eq_pesado WHERE bitacora_eq_pesado_idbitacora='$idBit' AND id_equipo='$idEquipo'"));
							if(!$existe["id_equipo"])
								mysql_query("INSERT INTO detalle_eq_pesado (bitacora_eq_pesado_idbitacora,id_equipo,cantidad) VALUES ('$idBit','$idEquipo','$value')");
						}
						//Remover todos los registros que tengan 0 como cantidad trabajada
						mysql_query("DELETE FROM detalle_eq_pesado WHERE bitacora_eq_pesado_idbitacora='$idBit' AND id_equipo='$idEquipo' AND cantidad=0");
						mysql_close($conn);
					}
				}
				?>
				<script type="text/javascript" language="javascript">
					//Asignar el importe de Pesos
					var precioUMN=window.opener.document.getElementById("txt_precioUMN").value.replace(/,/g,'');
					var precioMN=precioUMN*<?php echo $total?>;
					formatCurrency(precioMN,"hdn_temp");
					window.opener.document.getElementById("txt_totalMN").value=document.getElementById("hdn_temp").value;
					//Asignar el importe de Dolares
					var precioUUSD=window.opener.document.getElementById("txt_precioUUSD").value.replace(/,/g,'');
					var tasa=window.opener.document.getElementById("txt_tasaCambio").value;
					var precioUSD=precioUUSD*<?php echo $total?>*(tasa*1);
					formatCurrency(precioUSD,"hdn_temp");
					window.opener.document.getElementById("txt_totalUSD").value=document.getElementById("hdn_temp").value;
					//Obtener el Gran Importe
					formatCurrency(((precioMN*1)+(precioUSD*1)),"hdn_temp");
					window.opener.document.getElementById("txt_totalImporte").value=document.getElementById("hdn_temp").value;
					//continuar
					window.opener.document.getElementById("txt_cantidadTotal").value="<?php echo number_format($total,2,".",",")?>";
					window.opener.document.getElementById("<?php echo $_GET["btn"]?>").disabled=false;
					window.close();
				</script>
				<?php
			}
		?>
</body>
</html>