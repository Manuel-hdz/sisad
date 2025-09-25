<?php
	function mostrarPlatillosDia(){
		$conn=conecta("bd_comaro");
		$stm_sql="SELECT * FROM platillos_dia WHERE fecha = '".date('Y-m-d')."' ORDER BY turno, id_menu";
		$rs=mysql_query($stm_sql);
		if ($datos=mysql_fetch_array($rs)){
			echo "<table class='tabla_frm' cellpadding='5' width='100%'>";
			echo "<caption class='titulo_etiqueta'>Platillos del D&iacute;a ".modFecha(date('Y-m-d'),2)."</caption>";
			echo "	<tr>
						<td class='nombres_columnas_comaro' align='center'>CLAVE</td>
						<td class='nombres_columnas_comaro' align='center'>DESCRIPCION</td>
						<td class='nombres_columnas_comaro' align='center'>COSTO UNITARIO</td>
						<td class='nombres_columnas_comaro' align='center'>TURNO</td>
						<td class='nombres_columnas_comaro' align='center'>DISPONIBLES</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				echo "<tr>					
						<td class='nombres_filas_comaro' align='center'>$datos[id_menu]</td>
						<td class='$nom_clase' align='center'>".obtenerDatoMenu('descripcion', $datos['id_menu'], 'id_menu')."</td>
						<td class='$nom_clase' align='center'>$ ".number_format($datos['costo_unit'],2,".",",")."</td>
						<td class='$nom_clase' align='center'>$datos[turno]</td>
						<td class='$nom_clase' align='center'>$datos[cantidad]</td>
					  </tr>";			
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</table>";
		} else {
			echo "<br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No hay Platillos del D&iacute;a ".modFecha(date('Y-m-d'),2)." Registrados en el Men&uacute;</p>";
		}
		mysql_close($conn);
	}
	
	function agregarPlatilloDia(){
		$conn = conecta("bd_comaro");
		$id_menu = $_POST["cmb_plat"];
		$fecha = date("Y-m-d");
		$turno = $_POST["cmb_turno"];
		$costo = obtenerDatoMenu("costo_unit",$id_menu,"id_menu");
		$cantidad = $_POST["txt_cantidad"];
		$existe = comprobarExiste($id_menu,$fecha,$turno);
		if($existe){
			?>
			<script type="text/javascript" language="javascript">
				setTimeout("alert('El platillo ya se encuentra registrado en este turno');",500);
			</script>
			<?php
			mysql_close($conn);
		} else {
			$stm_sql = "INSERT INTO platillos_dia (id_menu,fecha,turno,costo_unit,cantidad,cantidad_real) VALUES ('$id_menu','$fecha','$turno',$costo,$cantidad,$cantidad)";
			$rs = mysql_query($stm_sql);
			if($rs){
				//Cerramos la conexion con la Base de Datos
				mysql_close($conn);
				//Registrar el movimiento en la bitácora de Movimientos
				registrarOperacion("bd_comaro","$id_menu","AgregarPlatilloDia",$_SESSION['usr_reg']);
				?>
				<script type="text/javascript" language="javascript">
					setTimeout("alert('El Platillo del Día se agrego existosamente! <?php echo $existe ?>');",500);
				</script>
				<?php
			}
			else{
				?>
				<script type="text/javascript" language="javascript">
					setTimeout("alert('Hubo conflictos al momento de registrar el Platillo');",500);
				</script>
				<?php
				mysql_close($conn);
			}
		}
	}
	
	function obtenerDatoMenu($campo, $param, $busq){
		$valor = 0;
		//$conn = conecta("bd_comaro");
		$sm_sql_dato = "SELECT $campo FROM menu WHERE $busq = '$param'";
		$rs_dato = mysql_query($sm_sql_dato);
		if($datos = mysql_fetch_array($rs_dato)){
			$valor = $datos[0];
		}
		return $valor;
	}
	
	function comprobarExiste($id_menu,$fecha,$turno){
		$conn = conecta("bd_comaro");
		$stm_sql = "SELECT * FROM platillos_dia WHERE id_menu = '$id_menu' AND fecha = '$fecha' AND turno = '$turno'";
		$rs = mysql_query($stm_sql);
		if($datos = mysql_fetch_array($rs))
			return true;
		else
			return false;
	}
?>