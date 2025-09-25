<?php
	function obtenerIdRequisicion(){
		$conn = conecta("bd_comaro");
		
		$id_cadena = "MAI";
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);	
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		$stm_sql = "SELECT COUNT(id_requisicion) AS cant FROM requisiciones WHERE id_requisicion LIKE 'MAI$mes$anio%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant = $datos['cant'] + 1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}
			
		mysql_close($conn);
		return $id_cadena;
	}
	
	function guardarRequisicion($txa_justificacionReq,$hdn_fecha,$txt_areaSolicitante,$txt_solicitanteReq,$txt_elaboradorReq){
		
		$id_requisicion = obtenerIdRequisicion();
		$conn = conecta("bd_comaro");					
		
		$txa_justificacionReq = strtoupper($txa_justificacionReq); $txt_areaSolicitante = strtoupper($txt_areaSolicitante); 
		$txt_solicitanteReq = strtoupper($txt_solicitanteReq); $correo = $_POST["txt_correo"];
		$txt_elaboradorReq = strtoupper($txt_elaboradorReq);
		
		if($_SESSION["tipo_usr"] == "administrador")
			$autorizada = 1;
		else
			$autorizada = 0;
		
		$autorizada = 1;
		
		$cmb_prioridad=$_POST["cmb_prioridad"];
			
		$comentario="";
		if(isset($_SESSION['comentario']))
			$comentario=$_SESSION['comentario'];
		
		$stm_sql = "INSERT INTO requisiciones (id_requisicion, area_solicitante, fecha_req, justificacion_tec, elaborador_req, solicitante_req, estado, comentario_compras, 
					prioridad,observaciones,autorizada,correo)
					VALUES('$id_requisicion','$txt_areaSolicitante','$hdn_fecha','$txa_justificacionReq','$txt_elaboradorReq','$txt_solicitanteReq','ENVIADA', 'N/A',
					'$cmb_prioridad','$comentario',$autorizada,'$correo')";
		$rs = mysql_query($stm_sql);		
		
		if($rs){			
			$band = 0;
			$cont = 1;
			foreach ($_SESSION['datosRequisicion'] as $ind => $material) {			
				$stm_sql = "INSERT INTO detalle_requisicion (
								requisiciones_id_requisicion, 
								materiales_id_material, 
								cant_req, 
								unidad_medida, 
								descripcion, 
								aplicacion, 
								id_control_costos, 
								id_cuentas, 
								id_subcuentas, 
								precio_unit, 
								tipo_moneda, 
								partida
							) VALUES(
								'$id_requisicion',
								'$material[clave]', 
								$material[cantReq], 
								'$material[unidad]', 
								'$material[material]', 
								'$material[aplicacionReq]', 
								'$material[cc]', 
								'$material[cuenta]', 
								'$material[subcuenta]', 
								'$material[costoU]', 
								'$material[moneda]', 
								$cont
							)";
				$rs = mysql_query($stm_sql);
				if(!$rs)
					$band = 1;
				$cont++;
			}
			if($band==0){
				registrarOperacion("bd_comaro",$id_requisicion,"GenerarRequisicion",$_SESSION['usr_reg']);
				
				?>
				<script type='text/javascript' language='javascript'>
					setTimeout("window.open('../../includes/generadorPDF/requisicion.php?id=<?php echo $id_requisicion; ?>', '_blank','top=0, left=0, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')",4000);
				</script>
				
				<?php
				echo "<meta http-equiv='refresh' content='5;url=exito.php'>";
				unset($_SESSION['datosRequisicion']);
				if(isset($_SESSION['comentario']))
					unset($_SESSION['comentario']);
			} else {
				mysql_query("DELETE FROM requisiciones WHERE id_requisicion='".$id_requisicion."'");
				mysql_query("DELETE FROM detalle_requisicion WHERE requisiciones_id_requisicion='".$id_requisicion."'");
				$error = "No se pudo guardar la Requisici&oacute;n";
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}
	
	function agregarMatRequi(){
		$id_material = $_POST['txt_clave'];
		$nombre = strtoupper($_POST['cmb_material']);
		$unidad = strtoupper($_POST['txt_unidadMedida']);
		$cantReq = $_POST['txt_cantReq'];
		$aplicacion = $_POST['cmb_cat'];
		$equipo = $_POST['cmb_equipos'];
		$cc = $_POST['cmb_con_cos'];
		$cuenta = $_POST['cmb_cuenta'];
		$subcuenta = $_POST['cmb_subcuenta'];
		$costoU = $_POST['txt_costoUnit'];
		$moneda = $_POST['txt_moneda'];
		if(isset($_SESSION['datosRequisicion'])){
			if(!verRegDuplicado($_SESSION['datosRequisicion'], "clave", $id_material)){
				$_SESSION['datosRequisicion'][] = array(
										"clave"=>$id_material, 
										"material"=>$nombre, 
										"unidad"=>$unidad, 
										"cantReq"=>$cantReq, 
										"aplicacionReq"=>$aplicacion,
										"equipo"=>$equipo,
										"cc"=>$cc,
										"cuenta"=>$cuenta,
										"subcuenta"=>$subcuenta,
										"costoU"=>$costoU,
										"moneda"=>$moneda,
										"nuevo_con_clave"=>1
									  );
			} else {
				?>
				<script type="text/javascript" language="javascript">
					setTimeout("alert('El Material ya fue Agregado a la Requisición');",500);
				</script>
				<?php
			}
		} else {
			$datosRequisicion = array(
									array(
										"clave"=>$id_material,
										"material"=>$nombre, 
										"unidad"=>$unidad, 
										"cantReq"=>$cantReq, 
										"aplicacionReq"=>$aplicacion,
										"equipo"=>$equipo,
										"cc"=>$cc,
										"cuenta"=>$cuenta,
										"subcuenta"=>$subcuenta,
										"costoU"=>$costoU,
										"moneda"=>$moneda,
										"nuevo_con_clave"=>1
									)
								);
			$_SESSION['datosRequisicion'] = $datosRequisicion;	
			$_SESSION['id_requisicion'] = obtenerIdRequisicion();
		}
	}
	
	function mostrarRegistros($datosRequisicion){
		?>
		<table cellpadding='5' align='center' width="100%">
			<tr>
				<td width='80' class='nombres_columnas_comaro' align='center'>CLAVE</td>
        		<td width='180' class='nombres_columnas_comaro' align='center'>NOMBRE (DESCRIPCI&Oacute;N)</td>
				<td width='100' class='nombres_columnas_comaro' align='center'>UNIDAD DE MEDIDA</td>
			    <td width='70' class='nombres_columnas_comaro' align='center'>CANT.</td>
				<td width='120' class='nombres_columnas_comaro' align='center'>APLICACI&Oacute;N</td>
				<td width='120' class='nombres_columnas_comaro' align='center'>CENTRO DE COSTOS</td>
				<td width='120' class='nombres_columnas_comaro' align='center'>CUENTA</td>
				<td width='120' class='nombres_columnas_comaro' align='center'>SUBCUENTA</td>
				<td width='30' class='nombres_columnas_comaro'></td>
			</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			$aux="";
			$claveMat="";
			foreach ($datosRequisicion as $ind => $material) {
				echo "<tr>";
				foreach ($material as $key => $value) {
					switch($key){
						case "clave":
							echo "<td class='nombres_filas_comaro' align='center'>$value</td>";
							$claveMat=$value;
						break;
						case "material":
							echo "<td class='$nom_clase'>$value</td>";
							$aux=$value;
						break;
						case "unidad":
							echo "<td class='$nom_clase' align='center'>$value</td>";
						break;
						case "cantReq":
							echo "<td class='$nom_clase' align='center'>$value</td>";
						break;
						case "aplicacionReq":
							/*
							$dato = strtoupper(obtenerDatoTabla('categorias_mat','id_categoria',$value,"bd_almacen"));
							echo "<td class='$nom_clase' align='center'>$dato</td>";
							*/
							echo "<td class='$nom_clase' align='center'>$value</td>";
						break;
						case "cc":
							$dato = obtenerDatoTabla('control_costos','id_control_costos',$value,"bd_recursos");
							echo "<td class='$nom_clase' align='center'>$dato</td>";
						break;
						case "cuenta":
							$dato = obtenerDatoTabla('cuentas','id_cuentas',$value,"bd_recursos");
							echo "<td class='$nom_clase' align='center'>$dato</td>";
						break;
						case "subcuenta":
							$dato = obtenerDatoTabla('subcuentas','id_subcuentas',$value,"bd_recursos");
							echo "<td class='$nom_clase' align='center'>$dato</td>";
						break;
					}
				}
				?>
				<td class="<?php echo $nom_clase;?>">
					<input type="image" src="../../images/editar.png" width="30" height="25" border="0" title="Modificar Registro" 
					onclick="location.href='frm_editarRegistros.php?origen=requisicion&pos=<?php echo $cont-1; ?>'" />
				</td>
				<?php
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
				echo "</tr>";
			}
		?>
		</table>
		<?php
	}
	
	function verRegDuplicado($arr,$campo_clave,$campo_ref){
		$existe = false;
		foreach ($arr as $ind => $material) {
			if($material[$campo_clave] == $campo_ref && $material[$campo_clave] != "N/A"){
				$existe = true;
			}
		}
		return $existe;
	}
	
	function obtenerCentroCosto($tabla,$busq,$valor){
		$dat = "N/A"; 
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
		mysql_close($con);
		return $dat;
	}
	
	function obtenerDatoTabla($tabla,$busq,$valor,$bd){
		$dat = "N/A"; 
		$con = conecta("$bd");
		$stm_sql = "SELECT descripcion
					FROM  `$tabla` 
					WHERE  `$busq` LIKE  '$valor'";
		$rs = mysql_query($stm_sql);
		if($rs){
			if($datos = mysql_fetch_array($rs)){
				$dat = $datos[0];
			}
		}
		mysql_close($con);
		return $dat;
	}
?>