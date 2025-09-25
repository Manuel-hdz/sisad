<?php
	
	include("../conexion.inc");			
	include("../func_fechas.php");

	if (isset($_GET['material_clave'])) {
		$id_mat = $_GET['material_clave'];
		$desc_mat = $_GET['material_nombre'];

		$conn = conecta("bd_almacen");
		$sql_stm = "SELECT * 
					FROM  `materiales` 
					WHERE  `id_material` LIKE  '$id_mat'
					AND  `nom_material` LIKE  '$desc_mat'";
		$rs = mysql_query($sql_stm);
		//$tam = mysql_num_rows($rs);
		//$cont = 1;
		header("Content-type: text/xml");
		if($datos=mysql_fetch_array($rs)){
			echo "<existe>
					<valor>true</valor>";
					//<tam>$tam</tam>
			do{
				//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
				echo utf8_encode("<id>$datos[id_material]</id>");
				echo utf8_encode("<descripcion>$datos[nom_material]</descripcion>");
				//$cont++;
			}while($datos=mysql_fetch_array($rs));
			echo "</existe>";
		}
		else{
			echo "<valor>false</valor>";
		}
		mysql_close($conn);
	}

?>