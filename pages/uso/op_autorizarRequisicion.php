<?php
	function mostrarRequisiciones(){
		$ctrl = 1;
		$filtro = "";
		if(isset($_POST["txa_filtro"]) && $_POST["txa_filtro"]!=""){
			$filtro=strtoupper($_POST["txa_filtro"]);
			$tipoFiltro=$_POST["cmb_filtro"];
		}
		$fechaI=modFecha($_POST["txt_fechaIni"],3);
		$fechaF=modFecha($_POST["txt_fechaFin"],3);
		
		$conec=conecta("bd_clinica");
		?>
		<table cellpadding='5' cellspacing='5' class='tabla_frm' width='100%'>
			<tr>
				<td colspan='4' align='center' class='titulo_etiqueta'>Requisiciones Publicadas por CLINICA entre el <?php echo modFecha($fechaI,2)." y el ".modFecha($fechaF,2); ?></td>
			</tr>
			<tr>
				<td colspan='4'>&nbsp;</td>
	      	</tr>
			<tr align='center'>
				<td class='nombres_columnas'>N&Uacute;MERO</td>
	        	<td class='nombres_columnas'>FECHA DE PUBLICACI&Oacute;N</td>
				<td class='nombres_columnas'>ESTADO</td>
				<td class='nombres_columnas'>PRIORIDAD</td>
				<td class='nombres_columnas'>SELECCIONAR</td>
			</tr>
			<?php
			if($filtro==""){
				$stm_sql = "SELECT id_requisicion, fecha_req, hora, estado, prioridad, cant_entrega, tipo_entrega FROM requisiciones JOIN bitacora_movimientos ON id_requisicion = id_operacion 
							WHERE fecha_req>='$fechaI' AND fecha_req<='$fechaF' AND bitacora_movimientos.tipo_operacion =  'GenerarRequisicion' AND autorizada = 0 ORDER BY id_requisicion";
			} else {
				if($tipoFiltro=="descripcion"){
					$stm_sql = "SELECT DISTINCT(id_requisicion), fecha_req, hora, requisiciones.estado, prioridad, requisiciones.cant_entrega, requisiciones.tipo_entrega FROM requisiciones 
								JOIN detalle_requisicion ON id_requisicion=requisiciones_id_requisicion JOIN bitacora_movimientos ON id_requisicion = id_operacion 
								WHERE fecha_req>='$fechaI' AND fecha_req<='$fechaF' AND descripcion LIKE '%$filtro%' AND bitacora_movimientos.tipo_operacion =  'GenerarRequisicion' 
								AND autorizada = 0 ORDER BY id_requisicion";
				}
				
				if($tipoFiltro=="aplicacion"){
					$stm_sql = "SELECT DISTINCT(id_requisicion), fecha_req, hora, requisiciones.estado, prioridad, requisiciones.cant_entrega, requisiciones.tipo_entrega FROM requisiciones 
								JOIN detalle_requisicion ON id_requisicion=requisiciones_id_requisicion JOIN bitacora_movimientos ON id_requisicion = id_operacion 
								WHERE fecha_req>='$fechaI' AND fecha_req<='$fechaF' AND aplicacion LIKE '%$filtro%' AND bitacora_movimientos.tipo_operacion =  'GenerarRequisicion' 
								AND autorizada = 0 ORDER BY id_requisicion";
				}
				
				if($tipoFiltro=="justificacion_tec"){
					$stm_sql = "SELECT DISTINCT(id_requisicion), fecha_req, hora, estado, prioridad, requisiciones.cant_entrega, requisiciones.tipo_entrega FROM requisiciones 
								JOIN bitacora_movimientos ON id_requisicion = id_operacion 
								WHERE fecha_req>='$fechaI' AND fecha_req<='$fechaF' AND justificacion_tec LIKE '%$filtro%' AND bitacora_movimientos.tipo_operacion =  'GenerarRequisicion' 
								AND autorizada = 0 ORDER BY id_requisicion";
				}
			}
			
			$rs=mysql_query($stm_sql);
			if($row = mysql_fetch_array($rs)){
				$cont=1;
				$nom_clase="renglon_gris";
				do{
					$atributo = "";
					if(isset($_POST["id_req"])){
						if($_POST["id_req"] == $row['id_requisicion']){
							?>
							<script type="text/javascript" language="javascript">
								setTimeout("focoReq('<?php echo $_POST["id_req"]?>')",1000);
								
								function focoReq(combo){
									renglon="renglon"+combo;
									renglonOr="renglonOr"+combo;
									prioridad="prioridad"+combo;
									seleccion="seleccion"+combo;
									document.getElementById(renglon).style.background="#5682DD";
									document.getElementById(renglonOr).style.background="#5682DD";
									document.getElementById(prioridad).style.background="#5682DD";
									document.getElementById(seleccion).style.background="#5682DD";
									document.getElementById("cmb_estado"+combo).focus();
								}
							</script>
							<?php
							$atributo = "checked";
						}
					}
					?>
					<tr>
						<td class='nombres_filas' align='center'>
							<?php echo $row['id_requisicion']; ?>
						</td>
						<td class='<?php echo $nom_clase; ?>' align='center' id='renglonOr<?php echo $row['id_requisicion']; ?>'>
							<?php echo modFecha($row['fecha_req'],2)." - ".modHora($row['hora']); ?>
						</td>
						<td class='<?php echo $nom_clase; ?>' align='center' id='renglon<?php echo $row['id_requisicion']; ?>'>
							<?php echo $row["estado"]; ?>
						</td>
						<td class='<?php echo $nom_clase; ?>' align='center' id='prioridad<?php echo $row['id_requisicion']; ?>'>
							<?php echo $row["prioridad"]; ?>
						</td>
						<td class='<?php echo $nom_clase; ?>' align='center' id='seleccion<?php echo $row['id_requisicion']; ?>'>
							<input type='radio' name='rdb_req' id='rdb_req' value='<?php echo $row['id_requisicion']; ?>' required="required" <?php echo $atributo; ?>/>
						</td>
					</tr>
					<?php
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
				}while ($row = mysql_fetch_array($rs));
				?>
				<input type="hidden" name="txt_fechaIni" id="txt_fechaIni" value="<?php echo $_POST['txt_fechaIni']; ?>"/>
				<input type="hidden" name="txt_fechaFin" id="txt_fechaFin" value="<?php echo $_POST['txt_fechaFin']; ?>"/>
				<input type="hidden" name="cmb_filtro" id="cmb_filtro" value="<?php echo $_POST['cmb_filtro']; ?>"/>
				<input type="hidden" name="txa_filtro" id="txa_filtro" value="<?php echo $_POST['txa_filtro']; ?>"/>
				<?php
			} else {
				?>
				<tr>
					<td colspan='5' align='center' class='msje_correcto'><br/>
						No existen Requisiciones por Autorizar del Departamento 
					</td>
				</tr>
				<?php
				$ctrl=0;
			}
			?>
		</table>
		<?php
		return $ctrl;
	}
	
	function mostrarRequisicionDetalle($id_req){
		$conec=conecta("bd_clinica");
		
		$stm_sql="SELECT area_solicitante, solicitante_req, elaborador_req, id_requisicion, observaciones, fecha_req, justificacion_tec, comentario_compras,estado FROM requisiciones WHERE id_requisicion='$id_req'";
		
		$rs=mysql_query($stm_sql);
		$row = mysql_fetch_array($rs);
		?>
		<table width='100%' cellpadding='5' cellspacing='5' class='tabla_frm'>
			<tr>
				<td align='right'>
					&Aacute;rea Solicitante
				</td>
				<td>
					<input name='txt_areaSolicitante' type='text' class='caja_de_texto' size='50' readonly=true value="<?php echo $row["area_solicitante"]; ?>"/></td>
				<td align='right'>
					N&uacute;mero
				</td>
				<td>
					<input name='txt_numero' type='text' class='caja_de_texto' readonly=true value='<?php echo $row["id_requisicion"]; ?>'/>
				</td>
			</tr>
			<tr>
				<td align='right'>
					Solicita
				</td>
				<td>
					<input name='txt_solicita' type='text' class='caja_de_texto' size='50' readonly=true value='<?php echo $row["elaborador_req"]; ?>'/>
				</td>
				<td align='right'>
					Fecha Requisici&oacute;n
				</td>
				<td>
					<input name='txt_fecha' type='text' class='caja_de_texto' size='30' readonly=true value='<?php echo modFecha($row["fecha_req"],2); ?>'/>
				</td>
				<tr>
					<td align='right' valign='top'>
						Justificaci&oacute;n
					</td>
	       			<td>
						<textarea name='txa_justificacion' class='caja_de_texto' rows='4' cols='30' readonly='readonly' style="resize: none;"><?php echo $row['justificacion_tec']; ?></textarea>
					</td>
					<td align='right' valign='top'>
						Comentarios
					</td>
					<td>
						<textarea name='txa_comentarios' readonly="readonly" rows='5' cols='30' class='caja_de_texto' style="resize: none;"><?php echo $row['observaciones']; ?></textarea>
					</td>
					<td valign='top'>
						Estado
					</td>
					<td valign='top'>
						<input name="cmb_estado" id="cmb_estado" class="caja_de_texto" value="<?php echo $row['estado']; ?>" readonly="readonly" size="17"/>
					</td>
				</tr>
			</tr>
		</table>
		<input type="hidden" name="txt_fechaIni" id="txt_fechaIni" value="<?php echo $_POST['txt_fechaIni']; ?>"/>
		<input type="hidden" name="txt_fechaFin" id="txt_fechaFin" value="<?php echo $_POST['txt_fechaFin']; ?>"/>
		<input type="hidden" name="cmb_filtro" id="cmb_filtro" value="<?php echo $_POST['cmb_filtro']; ?>"/>
		<input type="hidden" name="txa_filtro" id="txa_filtro" value="<?php echo $_POST['txa_filtro']; ?>"/>
		<input type="hidden" name="id_req" id="id_req" value="<?php echo $id_req; ?>"/>
		<?php
	}
	
	function dibujarDetalle($clave_req){
		$conn = conecta("bd_clinica");

		$stm_sql = "SELECT cant_req, unidad_medida, descripcion, aplicacion, precio_unit, mat_pedido, id_control_costos, tipo_moneda, estado, partida, cant_entrega, tipo_entrega, comentarios 
					FROM detalle_requisicion WHERE requisiciones_id_requisicion='".$clave_req."'";
					
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			?>
			<table cellpadding='5' width='100%'>
				<tr>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center'>CANTIDAD</td>
        			<td class='nombres_columnas' align='center'>UNIDAD</td>
			        <td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
        			<td class='nombres_columnas' align='center'>APLICACI&Oacute;N</td>
				</tr>
				<?php
				$nom_clase = "renglon_gris";
				$cont = 1;
				do{
					if($datos['aplicacion'] != "")
						$aplicacion = $datos['aplicacion'];
					else
						$aplicacion = obtenerCentroCosto('control_costos','id_control_costos',$datos['id_control_costos']);
					?>
					<tr>
						<td class='nombres_filas' align='center'>
							<?php echo $datos["cant_req"]; ?>
						</td>
						<td class='<?php echo $nom_clase; ?>' align='center'>
							<?php echo $datos["unidad_medida"]; ?>
						</td>
						<td class='<?php echo $nom_clase; ?>' align='center'>
							<?php echo $datos["descripcion"]; ?>
						</td>
						<td class='<?php echo $nom_clase; ?>' align='center'>
							<?php echo $aplicacion; ?>
						</td>
					</tr>
					<?php
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
				}while($datos=mysql_fetch_array($rs));
				?>
			</table>
			<?php
		}
	}
	
	function obtenerCentroCosto($tabla,$busq,$valor){
		$dat = $valor; 
		$con = conecta("bd_recursos");
		$stm_sql = "SELECT descripcion
					FROM  `$tabla` 
					WHERE  `$busq` LIKE  '$valor'";
		$rs = mysql_query($stm_sql);
		if($rs){
			if($datos = mysql_fetch_array($rs)){
				$dat = $datos[0];
			}
		}
		return $dat;
		mysql_close($con);
	}
	
	function autorizarRequisicion($id_req){
		$conn = conecta("bd_clinica");
		
		$stm_sql = "UPDATE requisiciones SET autorizada = 1 WHERE id_requisicion = '$id_req'";
		
		$rs = mysql_query($stm_sql);
		if($rs){
			?>
			<script>
				setTimeout("alert('La Requisicion <?php echo $id_req; ?> ha sido autorizada');",1000);
			</script>
			<?php
			registrarOperacion("bd_clinica",$id_req,"AutorizarRequisicion",$_SESSION['usr_reg']);
		} else {
			?>
			<script>
				setTimeout("alert('Se presentaron fallas al momento de autorizar Requisicion <?php echo $id_req; ?>');",1000);
			</script>
			<?php
		}
	}
?>