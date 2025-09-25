<?php
	include("../../../../includes/conexion.inc");
	include("../../../../includes/func_fechas.php");
	
	if(isset($_GET['fecha'])){	
		$fecha = $_GET["fecha"];
		$id_presupuesto = $_GET["presupuesto"];
		$fecha = modFecha($fecha,3);
		
		$conn = conecta("bd_gerencia");
		$sql_stm = "SELECT * 
					FROM  `presupuesto` 
					WHERE  `fecha_inicio` <=  '$fecha'
					AND  `fecha_fin` >=  '$fecha'
					AND  `id_presupuesto` LIKE  '$id_presupuesto'";
		
		$rs = mysql_query($sql_stm);
		$tam = mysql_num_rows($rs);
		$cont = 1;
		header("Content-type: text/xml");	 
		if($datos=mysql_fetch_array($rs)){
			echo "
			<existe>
				<valor>true</valor>
			</existe>";
		} else {
			echo "<valor>false</valor>";
		}
		mysql_close($conn);
	}
?>