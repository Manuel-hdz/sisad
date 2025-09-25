<?php
	/**
	  * Nombre del Módulo: Gerencia Técnica
	  * Nombre Programador: Armando Ayala Alvarado
	  * Descripción: Este archivo contiene las funciones necesarias para obtener el id de una cuadrilla
	**/
	
	include("../../../../includes/conexion.inc");
	include("../../../../includes/op_operacionesBD.php");
	
	$band = true;
	
	if(isset($_GET["id_area"])){
		$id_area = $_GET["id_area"];
		
		$conn = conecta("bd_recursos");
		$sql_stm="SELECT descripcion FROM control_costos WHERE id_control_costos LIKE '$id_area'";
		$rs = mysql_query($sql_stm);
		if($rs){
			$datos=mysql_fetch_array($rs);
			
			$lugar = strtoupper($datos["descripcion"]);
			$lugarCompuesto=split(" ",$lugar);
			$tam=count($lugarCompuesto)-1;
			$lugar = "";
			for($i = 0; $i <= $tam; $i++){
				if($i == $tam){
					$lugar .= substr($lugarCompuesto[$i],0,3);
				} else {
					$lugar .= substr($lugarCompuesto[$i],0,1);
				}
			}
		} else {
			$band = false;
		}
		mysql_close($conn);
		
		$conn = conecta("bd_gerencia");
		$sql_stm="SELECT MAX(id_cuadrilla) AS id FROM cuadrillas WHERE id_cuadrilla LIKE 'CDR-$lugar-%'";
		$rs = mysql_query($sql_stm);
		if($rs){
			$datos=mysql_fetch_array($rs);
			$id=substr($datos["id"],-3)+1;
			if ($id<10)
				$id="00".$id;
			if ($id>10 && $id<100)
				$id="0".$id;
		} else {
			$band = false;
		}
		mysql_close($conn);
	}
	
	header("Content-type: text/xml");
	if($band){
		$id="CDR-".$lugar."-".$id;
		echo utf8_encode("
			<existe>
				<valor>true</valor>
				<clave>$id</clave>
			</existe>"
		);
	} else {
		echo utf8_encode("
			<existe>
				<valor>false</valor>
			</existe>"
		);
	}
?>
