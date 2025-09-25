<?php
	function mostrarPersonalCuadrilla($personalCuadrilla){
		echo "<table cellpadding='5' width='100%'>";
		echo "<caption><p class='msje_correcto'><strong>Personal en la Cuadrilla ".$_POST["txt_IDCuadrilla"]."</strong></p></caption>";
		echo "      			
			<tr>
				<td class='nombres_columnas' align='center'>RFC</td>
        		<td class='nombres_columnas' align='center'>NOMBRE</td>
			    <td class='nombres_columnas' align='center'>PUESTO</td>
			    <td class='nombres_columnas' align='center'>ELIMINAR</td>
      		</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		foreach ($personalCuadrilla as $ind => $persona) {
			echo "<tr>";
			foreach ($persona as $key => $value) {
				switch($key){
					case "rfc":
						echo "<td class='nombres_filas' align='center'>$value</td>";
					break;
					case "nombre":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "puesto":
						echo "<td class='$nom_clase' align='center'>$value</td>";
						echo "<td class='$nom_clase' align='center'>";
						?>
						<form action="frm_agregarCuadrilla2.php" name="frm_intCuadrilla" id="frm_intCuadrilla" method="post">
							<input type="hidden" name="txt_IDCuadrilla" id="txt_IDCuadrilla" value="<?php echo $_POST["txt_IDCuadrilla"]; ?>" />
							<input type="hidden" name="txt_ubicacion" id="txt_ubicacion" value="<?php echo $_POST["txt_ubicacion"]; ?>" />
							<input type="hidden" name="txt_rfc" id="txt_rfc" value="<?php echo $persona["rfc"]; ?>" />
							<input type="hidden" name="btn_eliminar" id="btn_eliminar"/>
							<input type="image"  src="images/eliminar.png" style="width:25px; height:25px; cursor:hand;" />
						</form>
						<?php
						echo "</td>";
					break;
				}				
			}
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
			echo "</tr>";			
		}
		echo "</table>";
	}
		
	//Generar el ID de la nueva ubicacion seleccionada
	function generarIdNvaUbicacion(){
		//Realizar la conexion a la BD de Gerencia
		$conn = conecta("bd_gerencia");
		$id="";
		//Crear la sentencia para obtener el presupuesto Reciente
		$stm_sql = "SELECT COUNT(id_ubicacion) AS num, MAX(id_ubicacion)+1 AS cant FROM catalogo_ubicaciones";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
		
			if($datos['num']>0){
				$id .= ($datos['cant']);
			}
			else {
				$id .= "1";
			}
		}
		else 
			echo"error".mysql_error();		
		//Cerrar la conexion con la BD		
		mysql_close($conn); 
		return $id;
	}//Fin de function generarIdNvaUbicacion

	//Funcion para guardar la Ubicacion
	function guardarUbicacion($id,$ubicacion){
		//Conectar se a la Base de Datos
		$conn = conecta("bd_gerencia");
		//Crear la Sentencia SQL para Alamcenar la nueva ubicacion 
		$stm_sql= "INSERT INTO catalogo_ubicaciones(id_ubicacion,ubicacion)	VALUES ('$id','$ubicacion')";				
		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);
		//Cerrar la conexion con la BD		
		mysql_close($conn); 
	}//Fin de guardarUbicacion
	
	function verificarPersona($rfc,$puesto){
		
		$repetido = 0;
		
		if (isset($_SESSION['personalCuadrilla'])){
			foreach ($_SESSION['personalCuadrilla'] as $ind => $info) {
				foreach ($info as $key => $value) {
					switch($key){
						case "rfc":
							if($value==$rfc)
								$repetido = 1;
							else{
								$verBD=obtenerDato("bd_gerencia", "integrantes_cuadrilla", "id_cuadrilla", "rfc_trabajador", $rfc);
								if($verBD!="")
									$repetido = $verBD;
							}
						break;
						case "puesto":
							if($puesto=="LANZADOR" || $puesto=="AYUDANTE"){
								if($value==$puesto)
									$repetido = 2;
							}
						break;
					}
				}
			}
		} else {
			$verBD = obtenerDato("bd_gerencia", "integrantes_cuadrilla", "id_cuadrilla", "rfc_trabajador", $rfc);
			if($verBD!="")
				$repetido = $verBD;
		}
		
		return $repetido;
	}
	
	
	function registrarCuadrilla(){
		$stm_sql="";
		
		$id = $_SESSION["cuadrilla"]["id_cuadrilla"];
		$comentario = $_SESSION["cuadrilla"]["comentarios"];
		$ubicacion = $_SESSION["cuadrilla"]["ubicacion"];
		
		$aplicacion = "";
		if(isset($_SESSION["cuadrilla"]["via_seca"]))
			$aplicacion .= $_SESSION["cuadrilla"]["via_seca"].", ";
		if(isset($_SESSION["cuadrilla"]["via_humeda"]))
			$aplicacion .= $_SESSION["cuadrilla"]["via_humeda"].", ";
		
		$aplicacion = substr($aplicacion,0,(strlen($aplicacion)-2));
		
		$stm_sql = "INSERT INTO cuadrillas (
						id_cuadrilla,
						id_control_costos,
						comentarios,
						aplicacion
					) VALUES (
						'$id',
						'$ubicacion',
						'$comentario',
						'$aplicacion'
					)";
		
		$conn=conecta("bd_gerencia");
		
		$rs=mysql_query($stm_sql);
		if($rs){
			mysql_close($conn);
			registrarPersonal($_SESSION["cuadrilla"]["id_cuadrilla"]);
		}
		else{
			unset ($_SESSION['cuadrilla']);
			unset ($_SESSION['personalCuadrilla']);
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			mysql_close($conn);
		}	
	}
	
	function registrarPersonal($idCuadrilla){
		$conn=conecta("bd_gerencia");
		foreach($_SESSION['personalCuadrilla'] as $ind => $concepto){
			$stm_sql = "INSERT INTO integrantes_cuadrilla (
							id_cuadrilla,
							rfc_trabajador,
							nombre_emp,
							puesto
						) VALUES (
							'$idCuadrilla',
							'$concepto[rfc]',
							'$concepto[nombre]',
							'$concepto[puesto]'
						)";
			
			$rs=mysql_query($stm_sql);
			
			if ($rs){
				$band=1;
			}
			if (!$rs){
				$band=0;
				$error = mysql_error();
			}
		}
		
		if ($band==1){
			unset ($_SESSION['personalCuadrilla']);
			unset ($_SESSION['cuadrilla']);
			
			registrarOperacion("bd_gerencia",$idCuadrilla,"AgregarCuadrilla",$_SESSION['usr_reg']);																			
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		
		else{
			unset ($_SESSION['personalCuadrilla']);
			unset ($_SESSION['cuadrilla']);
			mysql_query("DELETE FROM cuadrillas WHERE id_cuadrilla = '$id_cuadrilla'");
			mysql_close($conn);
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}
	
	function borrarPersonal($rfc_empl){
		foreach ($_SESSION['personalCuadrilla'] as $ind => $personal) {
			if($personal["rfc"]==$rfc_empl){
				unset($_SESSION['personalCuadrilla'][$ind]);
			}
		}
	}
?>