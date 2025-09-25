<?php
	function registrarBitacora(){
		$id_bit = obtenerIdBitacora();
		$conn = conecta("bd_comaro");
		$id_empleado = $_POST["txt_codBarTrabajador"];
		$id_menu = $_POST["cmb_plat"];
		$turno = $_POST["cmb_turno"];
		$fecha_reg = date("Y-m-d");
		$estado = $_POST["cmb_estado"];
		$pagado = $_POST["cmb_pag"];
		$descuento = str_replace(",","",$_POST["txt_descuento"]);
		$stm_sql = "INSERT INTO bitacora_comensal (id_bitacora,id_empleados_empresa,id_menu,turno,fecha_registo,estado,pagado,descuento) 
		VALUES ('$id_bit',$id_empleado,'$id_menu','$turno','$fecha_reg','$estado','$pagado',$descuento)";
		$rs = mysql_query($stm_sql);
		if($rs){
			descontarPlatillo($turno,$id_menu,$fecha_reg);
			//Cerramos la conexion con la Base de Datos
			mysql_close($conn);
			//Registrar el movimiento en la bitácora de Movimientos
			registrarOperacion("bd_comaro","$id_bit","RegistrarBitacora",$_SESSION['usr_reg']);
			?>
			<script type="text/javascript" language="javascript">
				setTimeout("alert('El registro se agrego correctamente!');",500);
			</script>
			<?php
		}
		else{
			?>
			<script type="text/javascript" language="javascript">
				setTimeout("alert('Hubo conflictos al momento de registrar la bitacora);",500);
			</script>
			<?php
			mysql_close($conn);
		}
	}
	
	function obtenerIdBitacora(){
		$conn = conecta("bd_comaro");
		
		$id_cadena = "BIT";
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		//Obtener el mes actual y el año actual 
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		$stm_sql = "SELECT MAX(id_bitacora) AS cant FROM bitacora_comensal WHERE id_bitacora LIKE 'BIT$mes$anio%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant = substr($datos['cant'],-4)+1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00000".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0000".$cant;
			if($cant>=100 && $cant<1000)
				$id_cadena .= "000".$cant;
			if($cant>=1000 && $cant<10000)
				$id_cadena .= "00".$cant;
			if($cant>=10000 && $cant<100000)
				$id_cadena .= "0".$cant;
			if($cant>=1000000)
				$id_cadena .= $cant;
		}	
		mysql_close($conn);
		
		return $id_cadena;
	}
	
	function descontarPlatillo($turno,$id_plat,$fecha){
		$conn = conecta("bd_comaro");
		$stm_sql = "UPDATE platillos_dia SET cantidad = cantidad -1 WHERE id_menu =  '$id_plat' AND fecha =  '$fecha' AND turno =  '$turno'";
		mysql_query($stm_sql);
	}
?>