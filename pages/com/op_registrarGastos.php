<?php
	function obtenerIdGasto(){
		$conn = conecta("bd_compras");
		$id_cadena = "GAST";
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		$stm_sql = "SELECT MAX( CAST( SUBSTR( id_gastos, 9 ) AS UNSIGNED ) ) AS cant
					FROM gastos
					WHERE id_gastos LIKE  'GAST$mes$anio%'";
		
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
	
	function registrarGasto(){
		$id_gasto = obtenerIdGasto();
		$fecha_gasto = modFecha($_POST["txt_fecha"],3);
		$desc = strtoupper($_POST["txt_descripcion"]);
		$factura = strtoupper($_POST["txt_factura"]);
		$importe = str_replace(",","",$_POST["txt_importe"]);
		
		$conn = conecta("bd_compras");
		$stm_sql = "INSERT INTO gastos(
						id_gastos,
						fecha_gasto,
						descripcion,
						importe,
						factura
					) VALUES (
						'$id_gasto',
						'$fecha_gasto',
						'$desc',
						'$importe',
						'$factura'
					)";
		$rs = mysql_query($stm_sql);
		
		if($rs){
			mysql_close($conn);
			registrarOperacion("bd_compras",$id_gasto,"RegistrarGasto",$_SESSION['usr_reg']);
			?>
			<script>
				setTimeout("alert('SE REALIZO EL REGISTRO CORRECTAMENTE');",1000);
			</script>
			<?php
		} else {
			?>
			<script>
				setTimeout("alert('SE PRESENTARON PROBLEMAS AL MOMENTO DE REALIZAR EL REGISTO\n SI EL PROBLEMA PERSITE FAVOR DE NOTIFICARLO');",1000);
			</script>
			<?php	
		}
	}
?>