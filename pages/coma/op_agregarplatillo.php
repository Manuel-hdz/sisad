<?php
	function mostrarPlatillos(){
		$conn=conecta("bd_comaro");
		$stm_sql="SELECT * FROM menu";
		$rs=mysql_query($stm_sql);
		if ($datos=mysql_fetch_array($rs)){
			echo "<table class='tabla_frm' cellpadding='5' width='100%'>";
			echo "<caption class='titulo_etiqueta'>Men&uacute; Comaro</caption>";
			echo "	<tr>
						<td class='nombres_columnas_comaro' align='center'>CLAVE</td>
						<td class='nombres_columnas_comaro' align='center'>DESCRIPCION</td>
						<td class='nombres_columnas_comaro' align='center'>COSTO UNITARIO</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				echo "<tr>					
						<td class='nombres_filas_comaro' align='center'>$datos[id_menu]</td>
						<td class='$nom_clase' align='center'>$datos[descripcion]</td>
						<td class='$nom_clase' align='center'>$ ".number_format($datos['costo_unit'],2,".",",")."</td>
					  </tr>";			
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</table>";
		} else {
			echo "<br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No hay Platillos Registrados en el Men&uacute;</p>";
		}
		mysql_close($conn);
	}
	
	function agregarPlatillo(){
		$id_menu = obtenerIdMenu();
		$conn = conecta("bd_comaro");
		$descripcion = strtoupper($_POST["txa_descripcion"]);
		$descripcion = trim($descripcion);
		$costo = str_replace(",","",$_POST["txt_costo"]);
		$stm_sql = "INSERT INTO menu (id_menu,descripcion,costo_unit) VALUES ('$id_menu','$descripcion',$costo)";
		$rs = mysql_query($stm_sql);
		if($rs){
			//Cerramos la conexion con la Base de Datos
			mysql_close($conn);
			//Registrar el movimiento en la bitácora de Movimientos
			registrarOperacion("bd_comaro","$id_menu","AgregarPlatilloMenu",$_SESSION['usr_reg']);
			?>
			<script type="text/javascript" language="javascript">
				setTimeout("alert('El Platillo se agrego al Menú existosamente!');",1000);
			</script>
			<?php
		}
		else{
			?>
			<script type="text/javascript" language="javascript">
				setTimeout("alert('Hubo conflictos al momento de registrar el Platillo');",1000);
			</script>
			<?php
			mysql_close($conn);
		}
	}
	
	function obtenerIdMenu(){
		$conn = conecta("bd_comaro");
		
		$id_cadena = "PLAT";
		
		$stm_sql = "SELECT MAX(id_menu) AS cant FROM menu WHERE id_menu LIKE 'PLAT%'";
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