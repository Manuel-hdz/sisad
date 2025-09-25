<?php
	//Desplegar el personal registrado en las cuadrillas
	function mostrarCuadrillas($parametro,$concepto,$concepto2){
		if ($parametro==1){
			$sql_stm = "SELECT T1.id_cuadrilla, T3.descripcion, T1.comentarios, COUNT( T2.puesto ) AS integrantes
						FROM cuadrillas AS T1
						JOIN integrantes_cuadrilla AS T2
						USING ( id_cuadrilla ) 
						JOIN bd_recursos.control_costos AS T3
						USING ( id_control_costos ) 
						WHERE T1.id_control_costos =  '$concepto'
						GROUP BY T1.id_cuadrilla";
			$msg="CUADRILLAS DE ".obtenerDato("bd_recursos","control_costos", "descripcion", "id_control_costos", $concepto);
		}
		
		if ($parametro==2){
			$sql_stm = "SELECT T1.id_cuadrilla, T3.descripcion, T1.comentarios, COUNT( T2.puesto ) AS integrantes
						FROM cuadrillas AS T1
						JOIN integrantes_cuadrilla AS T2
						USING ( id_cuadrilla ) 
						JOIN bd_recursos.control_costos AS T3
						USING ( id_control_costos ) 
						WHERE T1.id_cuadrilla = '$concepto2'
						GROUP BY T1.id_cuadrilla";
			$msg="CUADRILLA DONDE SE ENCUENTRA EL EMPLEADO ".obtenerDato("bd_gerencia","integrantes_cuadrilla", "nombre_emp", "rfc_trabajador", $concepto);
		}
		
		$conn=conecta("bd_gerencia");
		
		$rs=mysql_query($sql_stm);
		if($datos=mysql_fetch_array($rs)){
			$bandera=1;
			if($datos["id_cuadrilla"]==NULL)
				$bandera=0;
			
			if($bandera==1){
				echo "				
				<table cellpadding='5' width='100%'>      			
					<tr>
						<td colspan='6' align='center' class='titulo_etiqueta'>$msg</td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center'>SELECCIONAR</td>
						<td class='nombres_columnas' align='center'>ID_CUADRILLA</td>
						<td class='nombres_columnas' align='center'>UBICACI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>COMENTARIOS</td>
						<td class='nombres_columnas' align='center'>N&Uacute;MERO DE INTEGRANTES</td>
						<td class='nombres_columnas' align='center'>LANZADOR</td>
					</tr>";
				$nom_clase = "renglon_gris";
				$cont = 1;
				do{	
					echo "
					<tr>";
					?>
						<td class="nombres_filas" align="center">
							<input type="radio" name="rdb_idCuadrilla" id="rdb_idCuadrilla" value="<?php echo $datos["id_cuadrilla"];?>"/>
						</td> 
					<?php
					echo "
						<td class='$nom_clase' align='center'>$datos[id_cuadrilla]</td>
						<td class='$nom_clase' align='left'>$datos[descripcion]</td>
						<td class='$nom_clase' align='left'>$datos[comentarios]</td>
						<td class='$nom_clase' align='center'>$datos[integrantes]</td>
						<td class='$nom_clase' align='center'>".lanzadorCuadrilla($datos['id_cuadrilla'])."</td>
					</tr>";
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
				}while($datos=mysql_fetch_array($rs));
				echo "	
				</table>"; 
				return 1;
			}else{
				echo "<br><br><br><br><br><br><br><br><br><br><br><p class='msje_correcto' align='center'>NO SE ENCONTRARON RESULTADOS</p>";
				return 0;
			}
		}
		else{
			echo "<br><br><br><br><br><br><br><br><br><br><br><p class='msje_correcto' align='center'>NO SE ENCONTRARON RESULTADOS</p>";
			return 0;
		}
		mysql_close($conn);
	}//Fin de la funcion mostrarCuadrillas()	
	
	//Funcion que elimina la Cuadrilla Seleccionada
	function borrarCuadrilla($idCuadrilla){
		$conn=conecta("bd_gerencia");
		$sql_stm="DELETE FROM cuadrillas WHERE id_cuadrillas='$idCuadrilla'";
		//Ejecutar la sentencia que se creo
		$rs=mysql_query($sql_stm);
		if($rs){
			?>
			<script type="text/javascript" language="javascript">
				setTimeout("mensaje();",1000);
				
				function mensaje(){
					alert("Cuadrilla Eliminada");
				}
			</script>
			<?php
			$sql_stm="DELETE FROM integrantes_cuadrilla WHERE cuadrillas_id_cuadrillas='$idCuadrilla'";
			$rs=mysql_query($sql_stm);
		}
		mysql_close($conn);
	}//Fin de borrarCuadrilla($idCuadrilla)
	
	//Funcion que modifca los datos de las Cuadrillas
	function modificarCuadrilla(){
		$id_cuadrillas=$_POST['txt_IDCuadrilla'];
		$comentario=strtoupper($_POST["txa_comentarios"]);
		
		$aplicacion = "";
		if(isset($_POST['ckb_zarpeoViaSeca']))
			$aplicacion .= $_POST['ckb_zarpeoViaSeca'].", ";
		if(isset($_POST['ckb_zarpeoViaHumeda']))
			$aplicacion .= $_POST['ckb_zarpeoViaHumeda'].", ";
		
		$aplicacion = substr($aplicacion,0,(strlen($aplicacion)-2));
		$conn=conecta("bd_gerencia");
								
		$sql_stm="UPDATE cuadrillas SET comentarios='$comentario', aplicacion='$aplicacion' WHERE id_cuadrilla='$id_cuadrillas'";
		$rs=mysql_query($sql_stm);
		if($rs){
			registrarOperacion("bd_gerencia",$id_cuadrillas,"ModificarCuadrilla",$_SESSION['usr_reg']);																			
			?>
			<script>
				setTimeout("alert('CAMBIOS REALIZADOS CORRECTAMENTE EN LA CUADRILLA <?php echo $id_cuadrillas; ?>');",1000);
			</script>
			<?php
		} else {
			?>
			<script>
				setTimeout("alert('ERROR AL REALIZAR CAMBIOS EN LA CUADRILLA <?php echo $id_cuadrillas; ?>');",1000);
			</script>
			<?php
		}
	}
	
	//Generar el ID de la nueva ubicacion seleccionada
	function generarIdNvaUbicacion(){
		//Realizar la conexion a la BD de Gerencia
		$conn = conecta("bd_gerencia");
		$id="";
		//Crear la sentencia para obtener el presupuesto Reciente
		$stm_sql = "SELECT COUNT(id_ubicacion) AS num, MAX(id_ubicacion)+1 AS cant FROM catalogo_ubicaciones";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			if($datos['num']>0)
				$id .= ($datos['cant']);
			else
				$id .= "1";
		}
		else 
			echo"error".mysql_error();		
		//Cerrar la conexion con la BD		
		mysql_close($conn); 
		return $id;
	}//Fin de function generarIdNvaUbicacion

	//Funcion para guardar la Ubicacion
	function guardarUbicacion($id,$ubicacion){
		//Conectar se a la Base de Datos
		$conn = conecta("bd_gerencia");
		//Crear la Sentencia SQL para Alamcenar la nueva ubicacion 
		$stm_sql= "INSERT INTO catalogo_ubicaciones(id_ubicacion,ubicacion)	VALUES ('$id','$ubicacion')";				
		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);
		//Cerrar la conexion con la BD		
		mysql_close($conn); 
	}//Fin de guardarUbicacion
	
	function lanzadorCuadrilla($id_cuadrilla){
		$dato = "NO HAY LANZADOR EN LA CUADRILLA";
		
		$conec = conecta("bd_gerencia");
		$stm_sql_lan = "SELECT nombre_emp 
						FROM  `integrantes_cuadrilla` 
						WHERE  `id_cuadrilla` LIKE  '$id_cuadrilla'
						AND  `puesto` LIKE  'lanzador'";
		
		$rs_lan = mysql_query($stm_sql_lan);
		
		if($rs_lan){
			if($datos_lan = mysql_fetch_array($rs_lan)){
				$dato = $datos_lan[0];
			}
		}
		return $dato;
	}
	
	function mostrarPersonal($idCuadrilla){
		$conn = conecta("bd_gerencia");
		$stm_sql="SELECT * FROM integrantes_cuadrilla WHERE id_cuadrilla='$idCuadrilla'";
		$rs=mysql_query($stm_sql);
		
		if($datos=mysql_fetch_array($rs)){
			$bandera=1;
			if($datos["id_cuadrilla"]==NULL)
				$bandera = 0;
			
			if($bandera==1){
				echo "				
				<table cellpadding='5' width='100%'>      			
					<tr>
						<td colspan='5' align='center' class='titulo_etiqueta'>Cuadrilla $idCuadrilla</td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center'>SELECCIONAR</td>
						<td class='nombres_columnas' align='center'>RFC</td>
						<td class='nombres_columnas' align='center'>NOMBRE</td>
						<td class='nombres_columnas' align='center'>PUESTO</td>
					</tr>";
				$nom_clase = "renglon_gris";
				$cont = 1;
				do{	
					echo "	
					<tr>";
					?>
						<td class="nombres_filas" align="center"><input type="radio" name="rdb_rfcPersona" id="rdb_rfcPersona" value="<?php echo $datos["rfc_trabajador"];?>"/></td><?php
					echo "
						<td class='$nom_clase' align='center'>$datos[rfc_trabajador]</td>
						<td class='$nom_clase' align='center'>$datos[nombre_emp]</td>
						<td class='$nom_clase' align='center'>$datos[puesto]</td>";
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
				}while($datos=mysql_fetch_array($rs));
				echo "
					<tr>
						<td colspan='4' align='center'>";
				echo "		<br>
							<input name='sbt_borrar' type='submit' class='botones' value='Borrar' title='Borrar al Trabajador Seleccionado de la Cuadrilla'/>
							&nbsp;&nbsp;&nbsp;&nbsp;";
				echo "		<input name='btn_cerrar' type='button' class='botones' value='Cerrar' title='Cierra la Ventana' onclick='window.close();'/>";
				echo "	</td>
					</tr>	
				</table>"; 
			}else{
				echo "<br><br><br><br><br><br><br><br><br><br><br><p class='msje_correcto' align='center'>NO SE ENCONTRARON RESULTADOS</p>";
			}
		}
	}
	
	function mostrarFormulario($idCuadrilla){
		
		$id_cc = obtenerDato("bd_gerencia", "cuadrillas", "id_control_costos", "id_cuadrilla", $idCuadrilla);
		$ubicacion = obtenerDato("bd_recursos", "control_costos", "descripcion", "id_control_costos", $id_cc);
		
		$conn = conecta("bd_gerencia");
		$dato=mysql_fetch_array(mysql_query("SELECT COUNT(*) AS cant FROM integrantes_cuadrilla WHERE id_cuadrilla='$idCuadrilla'"));
		mysql_close($conn);
		
		if ($dato["cant"]>=4){
			?>
			<script type="text/javascript" language="javascript">
				window.opener.document.getElementById("sbt_modificarCuadrilla").disabled = false; 
				window.opener.document.getElementById("sbt_modificarCuadrilla").title = "Modificar la Información de la Cuadrilla";
			</script>
			<?php
		}
		
		else{
			?>
			<script type="text/javascript" language="javascript">
				window.opener.document.getElementById("sbt_modificarCuadrilla").disabled = true; 
				window.opener.document.getElementById("sbt_modificarCuadrilla").title = "No Hay Suficiente Personal en la Cuadrilla";
			</script>
			<?php
		}
		?>
		
		<form name="frm_agregarPersonal" method="post" action="verPersonalCuadrilla.php?idCuadrilla=<?php echo $idCuadrilla;?>">
			<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td width="128">
						<div align="right">ID Cuadrilla</div>
					</td>
					<td>
						<input name="txt_IDCuadrilla" id="txt_IDCuadrilla" type="text" class="caja_de_texto" size="15" 
						value="<?php echo $idCuadrilla;?>" readonly="readonly" style="background-color:#888;color:#FFF"/>
					</td>
					<td width="128">
						<div align="right">Ubicaci&oacute;n</div>
					</td>
					<td>
						<input name="txt_ubicacion" id="txt_ubicacion" type="text" class="caja_de_texto" size="20" 
						value="<?php echo $ubicacion;?>" readonly="readonly" style="background-color:#888;color:#FFF"/>
					</td>
				</tr>     
				<tr>
					<td>
						<div align="right">*Nombre</div>
					</td>
					<td>
						<input type="text" name="txt_nombre" id="txt_nombre" onkeyup="lookup(this,'empleados','1','<?php echo $id_cc; ?>');" 
						value="" size="60" maxlength="80" onkeypress="return permite(event,'car',0);" autocomplete="off" required="required"/>
						<div id="res-spider">
							<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
								<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
								<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
							</div>
						</div>
						<input type="hidden" name="hdn_rfc" id="hdn_rfc" value=""/>
					</td>
					<td>
						<div align="right">*Puesto</div>
					</td>
					<td>
						<select name="cmb_puesto" id="cmb_puesto" class="combo_box" onchange="agregarNvoPuesto(this);" required="required">
							<option selected="selected" value="">Puesto</option>
							<option value="LANZADOR">LANZADOR</option>
							<option value="AYUDANTE">AYUDANTE</option>
							<option value="OP. OLLA">OP. OLLA</option>
							<option value="OP. TORNADO">OP. TORNADO</option>
							<?php
							$conn = conecta("bd_gerencia");
							$rs = mysql_query( "SELECT DISTINCT  `puesto` 
												FROM  `integrantes_cuadrilla` 
												WHERE puesto !=  'LANZADOR'
												AND puesto !=  'AYUDANTE'
												AND puesto !=  'OP. OLLA'
												AND puesto !=  'OP. TORNADO'");
							if($rs){
								while($datos = mysql_fetch_array($rs)){
									echo "<option value='$datos[puesto]'>$datos[puesto]</option>";
								}
							}
							?>
							<option value="NUEVO">NUEVO PUESTO</option>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="5"><strong>* Datos marcados con asterisco son <u>obligatorios</u></strong></td>
				</tr>
				<tr>
					<td colspan="6">
						<div align="center">
							<input type="hidden" name="hdn_validar" value="si"/>
							<input name="sbt_agregar" type="submit" class="botones" value="Agregar" title="Agregar Nuevo Integrante a la Cuadrilla" onmouseover="window.status='';return true" />
							&nbsp;&nbsp;&nbsp;					
						</div>
					</td>
				</tr>
			</table>
		</form>
		<?php
	}
	
	function agregarPersonal(){
		$verBD=obtenerDato("bd_gerencia", "integrantes_cuadrilla", "id_cuadrilla", "rfc_trabajador", $_POST["hdn_rfc"]);
		$verPuesto=verificarPuesto($_POST["txt_IDCuadrilla"],$_POST["cmb_puesto"]);
		
		if ($verBD=="" && $verPuesto==""){
			$conn=conecta("bd_gerencia");
			$stm_sql = "INSERT INTO integrantes_cuadrilla (
							id_cuadrilla,
							rfc_trabajador,
							nombre_emp,
							puesto
						) VALUES (
							'$_POST[txt_IDCuadrilla]',
							'$_POST[hdn_rfc]',
							'$_POST[txt_nombre]',
							'$_POST[cmb_puesto]'
						)";
			
			$rs=mysql_query($stm_sql);
			mysql_close($conn);
		} else {
			if($verBD!=""){
				?>
				<script type="text/javascript" language="javascript">
					setTimeout("mensaje();",1000);
					
					function mensaje(){
						alert("El Trabajador <?php echo $_POST["txt_nombre"];?> Pertenece a la Cuadrilla <?php echo $verBD;?>");
					}
				</script>
				<?php
			}
			if($verBD=="" && $verPuesto!=""){
				?>
				<script type="text/javascript" language="javascript">
					setTimeout("mensaje2();",1000);
					
					function mensaje2(){
						alert("El Puesto <?php echo $_POST["cmb_puesto"];?> Pertenece al Trabajador <?php echo $verPuesto;?>");
					}
				</script>
				<?php
			}
		}
	}
	
	function verificarPuesto($cuadrilla,$puesto){
		
		if($puesto=="LANZADOR" || $puesto=="AYUDANTE"){
			$conn=conecta("bd_gerencia");
			$stm_sql="SELECT nombre_emp FROM integrantes_cuadrilla WHERE id_cuadrilla='$cuadrilla' AND puesto='$puesto'";
			$rs=mysql_query($stm_sql);
			if($datos = mysql_fetch_array($rs))		
				return $datos[0];
			else
				return "";
			mysql_close($conn);
		}
		else
			return "";
	}
	
	function borrarPersonal($idCuadrilla,$rfc){
		$conn = conecta("bd_gerencia");
		$stm_sql="DELETE FROM integrantes_cuadrilla WHERE id_cuadrilla='$idCuadrilla' AND rfc_trabajador='$rfc'";
		
		$rs=mysql_query($stm_sql);
		mysql_close($conn);
		
		?>
		<script type="text/javascript" language="javascript">
			setTimeout("mensaje();",1000);
			
			function mensaje(){
				alert("Integrante Borrado");
			}
		</script>
		<?php
	}
?>