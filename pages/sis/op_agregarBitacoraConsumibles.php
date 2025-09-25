<?php
	function agregarBitacoraConsumibles(){
		$id_bit = obtenerIdConsumible();
		$conn = conecta("bd_sistemas");
		$consumibles = strtoupper($_POST["cmb_consumibles"]);
		$nombre = strtoupper($_POST["txt_nombre"]);
		$tipo = strtoupper($_POST["cmb_tipo"]);
		$departamento = strtoupper($_POST["txt_dep"]);
		$cantidad = $_POST["txt_cantidad"];
		$fecha = modFecha($_POST["txt_fecha"],3);
		$stm_sql = "INSERT INTO bitacora_consumibles (id_bitacoraCons,id_consumibles,fecha,tipo,departamento,empleado,cantidad) VALUES ('$id_bit','$consumibles','$fecha','$tipo','$departamento','$nombre',$cantidad)";
		$rs = mysql_query($stm_sql);
		if($rs){
			$band = 0;
			//Registrar el movimiento en la bitácora de Movimientos
			if($tipo == "E"){
				$rs_tipo = mysql_query("UPDATE consumibles SET cantidad=cantidad+$cantidad WHERE id_consumibles='$consumibles'");
				if(!$rs_tipo)
					$band = 1;
			}
			else if($tipo == "S"){
				$rs_tipo = mysql_query("UPDATE consumibles SET cantidad=cantidad-$cantidad WHERE id_consumibles='$consumibles'");
				if(!$rs_tipo)
					$band = 1;
			}
			//Cerramos la conexion con la Base de Datos
			mysql_close($conn);
			if($band == 0){
				registrarOperacion("bd_sistemas","$id_bit","AgregarBitacoraConsumible",$_SESSION['usr_reg']);
				?>
				<script type="text/javascript" language="javascript">
					setTimeout("alert('!El registro se realizo correctamente!');",1000);
				</script>
				<?php
			} else {
				mysql_query("DELETE FROM bitacora_consumibles WHERE id_bitacoraCons = '$id_bit'");
				?>
				<script type="text/javascript" language="javascript">
					setTimeout("alert('Hubo conflictos al momento de registrar la bitacora');",1000);
				</script>
				<?php
				mysql_close($conn);
			}
		}
		else{
			?>
			<script type="text/javascript" language="javascript">
				setTimeout("alert('Hubo conflictos al momento de registrar la bitacora');",1000);
			</script>
			<?php
			mysql_close($conn);
		}
	}
	
	function obtenerIdConsumible(){
		$conn = conecta("bd_sistemas");
		
		$id_cadena = "BIT";
		
		$stm_sql = "SELECT MAX(id_bitacoraCons) AS cant FROM bitacora_consumibles WHERE id_bitacoraCons LIKE 'BIT%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant = substr($datos['cant'],-4)+1;
			if($cant>0 && $cant<10)
				$id_cadena .= "000".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "00".$cant;
			if($cant>=100 && $cant<1000)
				$id_cadena .= "0".$cant;
			if($cant>=1000)
				$id_cadena .= $cant;
		}	
		mysql_close($conn);
		
		return $id_cadena;
	}
?>