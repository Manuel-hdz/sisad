<?php
	function obtenerIdCotizacion(){
		$conn = conecta("bd_compras");
		
		$id_cadena = "COTI";
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		$stm_sql = "SELECT MAX( CAST( SUBSTR( id_cotizacion, 9 ) AS UNSIGNED ) ) AS cant
					FROM cotizacion_proveedores
					WHERE id_cotizacion LIKE  'COTI$mes$anio%'";
		
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant = $datos['cant'] + 1;
			if($cant>0 && $cant<10)
				$id_cadena .= "000".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "00".$cant;
			if($cant>99 && $cant<1000)
				$id_cadena .= "0".$cant;
			if($cant>=1000)
				$id_cadena .= $cant;
		}
			
		mysql_close($conn);
		
		return $id_cadena;
	}
	
	function consultarDatoMaterial($id_req,$partida,$valor){
		$dato = "";
		$base = obtenerBD();
		$conn = conecta($base);
		$stm_sql = "SELECT $valor 
					FROM  `detalle_requisicion` 
					WHERE  `requisiciones_id_requisicion` LIKE  '$id_req'
					AND  `partida` =$partida";
		
		$rs = mysql_query($stm_sql);
		if($rs){
			if($datos=mysql_fetch_array($rs)){
				$dato = $datos[0];
			}
		}
		return $dato;
	}
	
	function mostrarProveedores(){
		$hay_datos = false;
		if($_POST['txt_material'] == "MATERIAL NUEVO"){
			$requisicion = $_POST["rdb_req"];
			$partida = $_POST["partida"];
			$stm_sql = "SELECT T2.rfc, T2.razon_social, T1.precio_unit, T1.tipo_moneda
						FROM cotizacion_proveedores AS T1
						JOIN proveedores AS T2 ON T1.rfc_proveedor = T2.rfc
						WHERE requisiciones_id_requisicion LIKE  '$requisicion'
						AND partida =$partida 
						AND materiales_id_material LIKE 'N/A'
						ORDER BY  `T1`.`precio_unit` ASC ";
		} else {
			$clave = $_POST["txt_clave"];
			$stm_sql = "SELECT T2.rfc, T2.razon_social, T1.precio_unit, T1.tipo_moneda
						FROM cotizacion_proveedores AS T1
						JOIN proveedores AS T2 ON T1.rfc_proveedor = T2.rfc
						WHERE materiales_id_material LIKE  '$clave'
						ORDER BY  `T1`.`precio_unit` ASC ";
		}
		
		$conn = conecta("bd_compras");
			
		$rs = mysql_query($stm_sql);
		if($rs){
			if($datos = mysql_fetch_array($rs)){
				$hay_datos = true;
				echo "								
				<table width='100%' class='tabla_frm' cellpadding='5' cellspacing='5'>
					<caption class='titulo_etiqueta'></caption>
					<tr>
						<td class='nombres_columnas' align='center'>SELECCIONAR</td>
						<td class='nombres_columnas' align='center'>RFC</td>
						<td class='nombres_columnas' align='center'>PROVEEDOR</td>
						<td class='nombres_columnas' align='center'>COSTO UNITARIO</td>
						<td class='nombres_columnas' align='center'>MONEDA</td>
					</tr>";
					
				$nom_clase = "renglon_gris";
				$cont = 1;	
				
				do{
					if($cont == 1){
						echo "	
						<tr>	
							<td class='nombres_filas' align='center'><input type='radio' name='id_prov' value='$datos[rfc]' checked/></td>";
					} else {
						echo "	
						<tr>	
							<td class='nombres_filas' align='center'><input type='radio' name='id_prov' value='$datos[rfc]'/></td>";
					}
					echo "
						<td class='$nom_clase' align='center'>$datos[rfc]</td>
						<td class='$nom_clase' align='center'>$datos[razon_social]</td>
						<td class='$nom_clase' align='center'>$datos[precio_unit]</td>
						<td class='$nom_clase' align='center'>$datos[tipo_moneda]</td>
					</tr>";
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
				}while($datos = mysql_fetch_array($rs));
			} else {
				echo "<br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No hay cotizaciones disponibles para este material</p>";
			}
		} else {
			echo "<br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No hay cotizaciones disponibles para este material</p>";
		}
		
		return $hay_datos;
	}
	
	function agregarCotizacion(){
		$id_coti = obtenerIdCotizacion();
		
		$conn = conecta("bd_compras");
		
		$requisicion=$_POST["rdb_req"];
		$id_mat=$_POST["txt_clave"];
		if($_POST["txt_material"] != "MATERIAL NUEVO")
			$desc_mat=$_POST["txt_material"];
		else
			$desc_mat=$_POST["nombre_mat"];
		$costo_unit=str_replace(",","",$_POST["costo_mat"]);
		$moneda=$_POST["txt_moneda"];
		$partida_req=$_POST["partida"];
		$prov=$_POST["txt_rfc_prov"];
		
		if($_POST["txt_material"] == "MATERIAL NUEVO")
			$existeC = existeCotizacion(1,$requisicion,$partida_req,$id_mat,$prov);
		else
			$existeC = existeCotizacion(2,$requisicion,$partida_req,$id_mat,$prov);
		
		if($existeC){
			$tipo = 2;
			$stm_sql = "UPDATE cotizacion_proveedores SET 
							precio_unit = $costo_unit,
							tipo_moneda = '$moneda' 
						WHERE rfc_proveedor = '$prov' ";
			if($_POST["txt_material"] == "MATERIAL NUEVO")
				$stm_sql .= "AND requisiciones_id_requisicion = '$requisicion' 
							 AND partida = $partida_req";
			else
				$stm_sql .= "AND materiales_id_material = '$id_mat'";
		} else {
			$tipo = 1;
			$stm_sql = "INSERT INTO cotizacion_proveedores (
							id_cotizacion,
							requisiciones_id_requisicion,
							materiales_id_material,
							descripcion,
							precio_unit,
							tipo_moneda,
							partida,
							rfc_proveedor
						) VALUES (
							'$id_coti',
							'$requisicion',
							'$id_mat',
							'$desc_mat',
							$costo_unit,
							'$moneda',
							$partida_req,
							'$prov'
						)";
		}
		$rs = mysql_query($stm_sql);
		if($rs){
			?>
			<script>
				setTimeout("alert('Cotización Agregada Correctamente');",1000);
			</script>
			<?php
			if($tipo == 1)
				registrarOperacion("bd_compras",$id_coti,"AgregarCotizacion",$_SESSION['usr_reg']);
			else{
				$id_coti = obtenerIdCoti($requisicion,$partida_req,$id_mat);
				registrarOperacion("bd_compras",$id_coti,"ModificarCotizacion",$_SESSION['usr_reg']);
			}
		} else {
			?>
			<script>
				setTimeout("alert('ERROR! Cotización no Agregada');",1000);
			</script>
			<?php
		}
	}
	
	function existeCotizacion($tipo,$requisicion,$partida,$clave,$rfc_proveedor){
		$existe = false;
		if($tipo == 1){
			$stm_sql_bus = "SELECT * 
							FROM  `cotizacion_proveedores` 
							WHERE  `requisiciones_id_requisicion` LIKE  '$requisicion'
							AND  `partida` =$partida 
							AND rfc_proveedor = '$rfc_proveedor'";
		} else {
			$stm_sql_bus = "SELECT * 
							FROM  `cotizacion_proveedores` 
							WHERE  `materiales_id_material` LIKE  '$clave' 
							AND rfc_proveedor = '$rfc_proveedor'";
		}
		
		$rs_bus = mysql_query($stm_sql_bus);
		if($rs_bus){
			if($datos = mysql_fetch_array($rs_bus)){
				$existe = true;
			}
		}
		
		return $existe;
	}
	
	function asignarPrecio(){
		$requisicion=$_POST["rdb_req"];
		$partida_req=$_POST["partida"];
		$id_mat=$_POST["txt_clave"];
		
		$datos_prov = obtenerCostoProveedor($requisicion,$partida_req,$id_mat);
		$costo_unit = $datos_prov[0];
		$moneda = $datos_prov[1];
		
		$base = obtenerBD();
		$conn = conecta($base);
		
		$stm_sql = "UPDATE detalle_requisicion SET
						precio_unit = $costo_unit,
						tipo_moneda = '$moneda'
					WHERE requisiciones_id_requisicion = '$requisicion' 
					AND partida = $partida_req";
		
		$rs = mysql_query($stm_sql);
		if($rs){
			?>
			<script>
				setTimeout("alert('Asignaicion de precio realizada correctamente');",1000);
			</script>
			<?php
		} else {
			?>
			<script>
				setTimeout("alert('ERROR! No se pudo realizar asignacion');",1000);
			</script>
			<?php
		}
	}
	
	function obtenerCostoProveedor($requisicion,$partida_req,$id_mat){
		
		$conn = conecta("bd_compras");
		$proveedor = $_POST["id_prov"];
		
		if($_POST["txt_material"] == "MATERIAL NUEVO"){
			$stm_sql_pro = "SELECT  `precio_unit` ,  `tipo_moneda` 
							FROM  `cotizacion_proveedores` 
							WHERE  `requisiciones_id_requisicion` LIKE  '$requisicion'
							AND  `partida` =$partida_req
							AND  `rfc_proveedor` LIKE  '$proveedor'";
		} else {
			$stm_sql_pro = "SELECT  `precio_unit` ,  `tipo_moneda` 
							FROM  `cotizacion_proveedores` 
							WHERE  `materiales_id_material` LIKE  '$id_mat'
							AND  `rfc_proveedor` LIKE  '$proveedor'";
		}
		
		$rs_prov = mysql_query($stm_sql_pro);
		$datos = mysql_fetch_array($rs_prov);
		mysql_close($conn);
		
		return array($datos['precio_unit'],$datos['tipo_moneda']);
	}
	
	function obtenerIdCoti($requisicion,$partida_req,$id_mat){
		
		$conn = conecta("bd_compras");
		$proveedor = $_POST["id_prov"];
		
		if($_POST["txt_material"] == "MATERIAL NUEVO"){
			$stm_sql_cot = "SELECT  `id_cotizacion` 
							FROM  `cotizacion_proveedores` 
							WHERE  `requisiciones_id_requisicion` LIKE  '$requisicion'
							AND  `partida` =$partida_req
							AND  `rfc_proveedor` LIKE  '$proveedor'";
		} else {
			$stm_sql_cot = "SELECT  `id_cotizacion` 
							FROM  `cotizacion_proveedores` 
							WHERE  `materiales_id_material` LIKE  '$id_mat'
							AND  `rfc_proveedor` LIKE  '$proveedor'";
		}
		echo "asdasdasd";
		$rs_coti = mysql_query($stm_sql_cot);
		$datos = mysql_fetch_array($rs_coti);
		mysql_close($conn);
		
		return $datos[0];
	}
	
	function obtenerBD(){
		switch ($_POST["depto"]){
			case "almacen":
				$departamento="ALMACEN";
				$base="bd_almacen";
				break;
			case "gerenciatecnica":
				$departamento="GERENCIA TECNICA";
				$base="bd_gerencia";
				break;
			case "recursoshumanos":
				$departamento="RECURSOS HUMANOS";
				$base="bd_recursos";
				break;
			case "produccion":
				$departamento="PRODUCCION";
				$base="bd_produccion";
				break;
			case "aseguramientodecalidad":
				$departamento="ASEGURAMIENTO DE CALIDAD";
				$base="bd_aseguramiento";
				break;
			case "desarrollo":
				$departamento="DESARROLLO";
				$base="bd_desarrollo";
				break;
			case "mantenimiento":
				$departamento="MANTENIMIENTO";
				$base="bd_mantenimiento";
				break;
			case "topografia":
				$departamento="TOPOGRAFIA";
				$base="bd_topografia";
				break;
			case "laboratorio":
				$departamento="LABORATORIO";
				$base="bd_laboratorio";
				break;
			case "seguridadindustrial":
				$departamento="SEGURIDAD INDUSTRIAL";
				$base="bd_seguridad";
				break;
			case "paileria":
				$departamento="PAILERIA";
				$base="bd_paileria";
				break;
			case "mttoElectrico":
				$departamento="MANTENIMIENTO ELECTRICO";
				$base="bd_mantenimientoE";
				break;
			case "clinica":
				$departamento="UNIDAD DE SALUD OCUPACIONAL";
				$base="bd_clinica";
				break;
		}
		
		return $base;
	}
?>