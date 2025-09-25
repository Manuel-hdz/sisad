<?php
	function mostrarConsumibles(){
		$conn=conecta("bd_sistemas");
		$stm_sql="SELECT * FROM consumibles";
		$rs=mysql_query($stm_sql);
		if ($datos=mysql_fetch_array($rs)){
			echo "<table class='tabla_frm' cellpadding='5' width='100%'>";
			echo "<caption class='titulo_etiqueta'>Consumibles</caption>";
			echo "	<tr>
						<td class='nombres_columnas' align='center'>CLAVE</td>
						<td class='nombres_columnas' align='center'>DESCRIPCION</td>
						<td class='nombres_columnas' align='center'>COLOR</td>
						<td class='nombres_columnas' align='center'>TIPO</td>
						<td class='nombres_columnas' align='center'>IMPRESORA</td>
						<td class='nombres_columnas' align='center'>CANTIDAD</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{	
				echo "<tr>					
						<td class='nombres_filas' align='center'>$datos[id_consumibles]</td>
						<td class='$nom_clase' align='center'>$datos[descripcion]</td>
						<td class='$nom_clase' align='center'>$datos[color]</td>
						<td class='$nom_clase' align='center'>$datos[tipo]</td>
						<td class='$nom_clase' align='center'>$datos[impresora]</td>";?>
						<td class='<?php echo $nom_clase; ?>' align='center' <?php if($datos['cantidad'] <= 0) echo "style='background-color:#FF0000;'"; ?> ><?php echo $datos['cantidad']; ?></td>
					  </tr>
				<?php
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</table>";
		} else {
			echo "<br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No hay consumibles de impresion</p>";
		}
		mysql_close($conn);
	}
	
	function agregarConsumibles(){
		$id_cons = obtenerIdConsumible();
		$conn = conecta("bd_sistemas");
		$descripcion = strtoupper($_POST["txt_descripcion"]);
		$color = strtoupper($_POST["txt_color"]);
		$tipo = strtoupper($_POST["txt_tipo"]);
		$impresora = strtoupper($_POST["txt_impresora"]);
		$stm_sql = "INSERT INTO consumibles (id_consumibles,descripcion,color,tipo,impresora,cantidad) VALUES ('$id_cons','$descripcion','$color','$tipo','$impresora',0)";
		$rs = mysql_query($stm_sql);
		if($rs){
			//Cerramos la conexion con la Base de Datos
			mysql_close($conn);
			//Registrar el movimiento en la bitácora de Movimientos
			registrarOperacion("bd_sistemas","$id_cons","AgregarConsumible",$_SESSION['usr_reg']);
			?>
			<script type="text/javascript" language="javascript">
				setTimeout("alert('!El <?php echo $tipo." ".$descripcion; ?> se agrego exitosamente!');",1000);
			</script>
			<?php
		}
		else{
			?>
			<script type="text/javascript" language="javascript">
				setTimeout("alert('Hubo conflictos al momento de registrar el <?php echo $tipo." ".$descripcion; ?>');",1000);
			</script>
			<?php
			mysql_close($conn);
		}
	}
	
	function obtenerIdConsumible(){
		$conn = conecta("bd_sistemas");
		
		$id_cadena = "CONS";
		
		$stm_sql = "SELECT MAX(id_consumibles) AS cant FROM consumibles WHERE id_consumibles LIKE 'CONS%'";
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