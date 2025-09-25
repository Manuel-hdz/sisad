<?php
	/**
	  * Nombre del Módulo: Topografía                                               
	  * Nombre Programador: Nadia Madahí López Hernández                           
	  * Fecha: 02/Junio/2011                                     			
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de Generar Obras 
	  **/

	//Cuando el usuario de modificar, los datos seran tomados del $_POST para almacenarlos en la BD
	if(isset($_POST['sbt_modificarObra'])){
		guardarCambiosObra();			
	}	
	//Declarar las variables que sern necesarias para mostrar los datos de las Obras
	$msj_resultados = "";
	$txt_idObra = "";
	$cmb_idPrecios = "";	
	$cmb_tipoObra = "";
	$cmb_nomObra = "";
	$txt_seccion = "";
	$txt_area = "";
	$txt_unidad = "";
	$txt_fechaRegistro = "";
	$txt_precioEstimacionMN = "";
	$txt_precioEstimacionUSD = "";
	$txt_subCategoria="";
	
	
	//Si el usuario quiere modificar el registro de una obra, hacer la consulta en la BD y extraer los datos para mostrarlos en el Formulario de Modificar Obra
	if(isset($_POST['sbt_seleccionarObra'])){

		//Obtener los datos de la Obra Seleccionada
		$datosObra = buscarObras($_POST['cmb_tipoObra'],$_POST['cmb_nomObra']);

		if($datosObra!="Error"){//Cargar los datos de la Obra en las variables para ser mostrados en el formulario			
			$txt_idObra = $datosObra['id_obra'];
			$cmb_idPrecios = $datosObra['precios_traspaleo_id_precios'];			
			$cmb_categoria = $datosObra['categoria'];			
			$cmb_tipoObra = $datosObra['tipo_obra'];
			$cmb_nomObra = $datosObra['nombre_obra'];
			$txt_seccion = $datosObra['seccion'];
			$txt_area = $datosObra['area'];
			$txt_unidad = $datosObra['unidad'];			
			$txt_fechaRegistro = modFecha($datosObra['fecha_registro'],1);
			$txt_precioEstimacionMN = $datosObra['pumn_estimacion'];
			$txt_precioEstimacionUSD = $datosObra['puusd_estimacion'];	
			$txt_subCategoria = $datosObra['subcategorias_id'];
		}
		else
			$msj_resultados = "No hay datos registrados para la Obra ".$_POST['cmb_nomObra'];
	}
	
	
		
	/*Esta funcion consulta los datos de la Obra en la Base de Datos y los carga al formulario*/	
	function buscarObras($tipoObra,$nomObra){
		//Realizar la conexion a la BD de Topografia
		$conn = conecta("bd_topografia");

		//Crear sentencia SQL
		$stm_sql = "SELECT * FROM obras WHERE tipo_obra = '$tipoObra' AND nombre_obra = '$nomObra'";
		//Ejecutar la sentencia creada
		$rs = mysql_query($stm_sql);
		//Si se obtiene un resultado de la busqueda, cargar los datos a las variables para ser mostradas en el formulario de Modificar Obras
		if($datos_obra=mysql_fetch_array($rs)){
			return $datos_obra;
		}
		else{
			return "Error";
		}
		
		//Cerrar la Conexion con la Base de Datos
		mysql_close($conn);
	}
	
	
	 //Funcion para guardar la obra
	 function guardarCambiosObra(){

		//Recoger los datos
		$id_obra = $_POST['txt_idObra'];
		$idPrecios = $_POST['cmb_idPrecios'];
		$tipoObra = $_POST['cmb_tipoObra'];
		$nombreObra = strtoupper($_POST['txt_nombreObra']);
		$seccion = strtoupper($_POST['txt_seccion']);
		$area = strtoupper($_POST['txt_area']);
		$unidad = strtoupper($_POST['txt_unidad']);
		$precioEstimacionMN = str_replace(",","",$_POST['txt_precioEstimacionMN']);
		$precioEstimacionUSD = str_replace(",","",$_POST['txt_precioEstimacionUSD']);
		$fechaRegistro = modfecha($_POST['txt_fechaRegistro'],3);
		$idSubcategoria = $_POST["cmb_subtipo"];

 		//Realizar la conexion a la BD de Topografía
		$conn = conecta("bd_topografia");

		//Crear la Sentencias SQL para Almacenar los datos de la Obra
		$stm_sql= "UPDATE obras SET id_obra='$id_obra', subcategorias_id='$idSubcategoria', precios_traspaleo_id_precios='$idPrecios', tipo_obra='$tipoObra', nombre_obra='$nombreObra', 
				seccion='$seccion', area='$area', unidad='$unidad', pumn_estimacion='$precioEstimacionMN', 
				puusd_estimacion='$precioEstimacionUSD', fecha_registro='$fechaRegistro' WHERE id_obra = '$id_obra'";
			
		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);
		//Verificar Resultado
		if ($rs){
			//Guardar la operacion realizada
			registrarOperacion("bd_topografia",$id_obra,"ModificarObra",$_SESSION['usr_reg']);																		
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='10;url=error.php?err=$error'>";
		} 		
	 }// Fin function generarObra()	
?>