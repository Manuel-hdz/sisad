<?php
	/**
	  * Nombre del Módulo: Topografía                                               
	  * Nombre Programador: Nadia Madahí López Hernández                           
	  * Fecha: 01/Junio/2011                                     			
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de Generar Obras 
	  **/
	if(isset ($_POST['sbt_guardar']))
		generarObra();
			
	//Esta función se encarga de generar el Id de las Obras de acuerdo a los registros existentes en la BD
	function obtenerIdObra(){
		//Realizar la conexion a la BD de Topografia
		$conn = conecta("bd_topografia");
		
		//Definir las tres letras en la Id de la Obra
		$id_cadena = "OBR";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		//Obtener el mes actual y el año actual 
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener las Obras Reciente acorde a la fecha
		$stm_sql = "SELECT MAX(id_obra) AS cant FROM obras WHERE id_obra LIKE 'OBR$mes$anio%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			//Obtener las ultimas 3 cifras de las Obras registrada en la BD y sumarle 1
			$cant = substr($datos['cant'],-3)+1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
		return $id_cadena;
	}//Fin de la function obtenerIdObra()
	
	 //Funcion para guardar la obra
	 function generarObra(){
		//Recoger los datos
		$id_obra = $_POST['txt_idObra'];
		$idSubcategoria = $_POST["cmb_subtipo"];
		$idPrecios = $_POST['cmb_idPrecios'];		
		$categoria = $_POST['cmb_categoria'];		
		$tipoObra = $_POST['cmb_tipoObra'];
		$nombreObra = strtoupper($_POST['txt_nombreObra']);
		$seccion = strtoupper($_POST['txt_seccion']);
		$area = strtoupper($_POST['txt_area']);
		$unidad = strtoupper($_POST['txt_unidad']);
		$precioEstimacionMN = str_replace(",","",$_POST['txt_precioEstimacionMN']);
		$precioEstimacionUSD = str_replace(",","",$_POST['txt_precioEstimacionUSD']);
		$fechaRegistro = modfecha($_POST['txt_fechaRegistro'],3);
		
		if($seccion!=""){		
			//Realizar la conexion a la BD de Topografía
			$conn = conecta("bd_topografia");
	
			//Crear la Sentencias SQL para Almacenar los datos de la Obra
			$stm_sql= "INSERT INTO obras (id_obra, subcategorias_id, precios_traspaleo_id_precios, categoria, tipo_obra, nombre_obra, seccion, area, unidad, pumn_estimacion, puusd_estimacion, fecha_registro)
						VALUES ('$id_obra','$idSubcategoria','$idPrecios','$categoria','$tipoObra','$nombreObra','$seccion','$area','$unidad','$precioEstimacionMN','$precioEstimacionUSD','$fechaRegistro')"; 
			//Ejecutar la Sentencia
			$rs=mysql_query($stm_sql);
			//Verificar Resultado
			if ($rs){
				//Guardar la operacion realizada
				registrarOperacion("bd_topografia",$id_obra,"GenerarObra",$_SESSION['usr_reg']);															
				//echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
				?>
				<script type="text/javascript" language="javascript">
					setTimeout("confirmacion()",500);
					
					function confirmacion(){
						if(confirm("¿Generar Estimación de la Obra Ingresada?"))
							location.href='frm_generarEstimacion.php?tipoObra=<?php echo $tipoObra?>&nomObra=<?php echo $nombreObra?>';
						else
							location.href='exito.php';
					}
				</script>
				<?php
			}
			else{
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}
		}
		else
			guardarObraDesborde($id_obra,$idSubcategoria,$idPrecios,$categoria,$tipoObra,$nombreObra,$unidad,$precioEstimacionMN,$precioEstimacionUSD,$fechaRegistro);
	 }// Fin function generarObra()	
	 
	 function guardarObraDesborde($id_obra,$idSubcategoria,$idPrecios,$categoria,$tipoObra,$nombreObra,$unidad,$precioEstimacionMN,$precioEstimacionUSD,$fechaRegistro){
	 	$id_obra=str_replace("OBR","OBO",$id_obra);
	 	//Realizar la conexion a la BD de Topografía
		$conn = conecta("bd_topografia");
		//Crear la Sentencias SQL para Almacenar los datos de la Obra
		$stm_sql= "INSERT INTO obras_otras (id_obra_otra, subcategorias_id, precios_traspaleo_id_precios, categoria, tipo_obra, nombre_obra, unidad, pumn_estimacion, puusd_estimacion, fecha_registro)
					VALUES ('$id_obra','$idSubcategoria','$idPrecios','$categoria','$tipoObra','$nombreObra','$unidad','$precioEstimacionMN','$precioEstimacionUSD','$fechaRegistro')";
		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);
		//Verificar Resultado
		if ($rs){
			//Guardar la operacion realizada
			registrarOperacion("bd_topografia",$id_obra,"GenerarObra",$_SESSION['usr_reg']);															
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	 }
?>