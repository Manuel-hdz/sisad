<?php
	include("../../../../includes/conexion.inc");			
	
	if(isset($_GET['id_costos'])){	
		$id_costos = $_GET["id_costos"];		
		
		$conn = conecta("bd_gerencia");
		$sql_stm = "SELECT * 
					FROM  `presupuesto` 
					WHERE  `id_control_costos` LIKE  '$id_costos'";
		
		$rs = mysql_query($sql_stm);
		$tam = mysql_num_rows($rs);
		$cont = 1;
		header("Content-type: text/xml");	 
		if($datos=mysql_fetch_array($rs)){
			echo "<existe>
					<valor>true</valor>
					<tam>$tam</tam>";
			do{
				$presupuesto = substr($datos['periodo'],5);
				echo utf8_encode("<idPresupuesto$cont>$datos[id_presupuesto]</idPresupuesto$cont>");
				echo utf8_encode("<periodo$cont>$presupuesto</periodo$cont>");
				echo utf8_encode("<fecha_inicio$cont>$datos[fecha_inicio]</fecha_inicio$cont>");
				$cont++;
			}while($datos=mysql_fetch_array($rs));
			echo "</existe>";
		} else {
			echo "<valor>false</valor>";
		}
		mysql_close($conn);
	}
?>