<?php
	/**
	  * Nombre del Módulo: Compras                                              
	  * Nombre Programador: Armando Ayala Alvarado
	  * Fecha: 01/Octubre/2015
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");
			include("../../../../includes/func_fechas.php");
	  
	if(isset($_GET['pedido'])){
		$pedido = $_GET["pedido"];
		$conn = conecta("bd_compras");
		$sql_stm = "SELECT * 
					FROM pedidos_recibidos
					JOIN bitacora_movimientos ON id_recibidos = id_operacion
					WHERE tipo_operacion =  'RecibirPedidos'
					AND id_pedido LIKE '$pedido'";
		
		$rs = mysql_query($sql_stm);
		
		header("Content-type: text/xml");	 
		if($datos=mysql_fetch_array($rs)){
			echo "<existe>
					<valor>true</valor>";
			echo 	utf8_encode("<fecha>".modFecha($datos['fecha'],7)."</fecha>");
			echo 	utf8_encode("<hora>$datos[hora]</hora>");
			echo 	utf8_encode("<id_pedido>$datos[id_pedido]</id_pedido>");
			echo "</existe>";
		} else {
			echo "<valor>false</valor>";
		}
		mysql_close($conn);
	}
?>