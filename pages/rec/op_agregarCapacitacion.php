<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 04/Abril/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de Agregar Capacitacion en la BD
	**/
	
	// si viene definido sbt_agregar quiere decir que se han agregado los datos para proceder a guardar la capacitacion
	if (isset($_POST['sbt_agregar'])) {
		guardarCapacitacion(); 
	}
	
	/*Esta funcion genera el id de la capacitacion de acuerdo a los registros en la BD*/
	function obtenerIdCapacitacion(){
		//Realizar la conexion a la BD de Recursos Humanos
		$conn = conecta("bd_recursos");
		//Definir las tres letras en la Id de la Capacitación
		$id_cadena = "CAP";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		//Obtener el mes actual y el año actual 
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		//Crear la sentencia para obtener la Capacitacion Reciente acorde a la fecha
		$stm_sql = "SELECT MAX(id_capacitacion) AS cant FROM capacitaciones WHERE id_capacitacion LIKE 'CAP$mes$anio%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			//Obtener las ultimas 3 cifras de la capacitacion registrada en la BD y sumarle 1
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
	}//Fin de la function obtenerIdCapacitacion()

	 //Funcion para guardar la capacitacion
	function guardarCapacitacion(){
		//Recoger los datos
		$id_capacitacion=($_POST['txt_claveCapacitacion']);
		$nom_capacitacion= strtoupper ($_POST['txt_nomCapacitacion']);
		$hrs_capacitacion=($_POST['txt_hrsCapacitacion']);
		$instructor= strtoupper($_POST['txt_instructor']);
		$fecha_ini= modfecha($_POST['txt_fechaIni'],3);
		$fecha_fin= modfecha($_POST['txt_fechaFin'],3);
		$descripcion= strtoupper($_POST['txa_descripcion']);
	 	//Formato DC-4
		$norma=strtoupper($_POST["txt_normaCapacitacion"]);
		$tema=strtoupper($_POST["txt_tema"]);
		$modalidad=$_POST["cmb_modo"];
		$objetivo=$_POST["cmb_objetivo"];
		$tipoInstructor=$_POST["rdb_tipoIns"];
		$regInsSTPS=strtoupper($_POST["txt_numRegSTPS"]);
 		//Realizar la conexion a la BD de Recursos Humanos
		$conn = conecta("bd_recursos");
		//Crear la Sentencias SQL para Almacenar los datos de la capacitacion
		$stm_sql= "INSERT INTO capacitaciones (id_capacitacion,norma,nom_capacitacion,hrs_capacitacion,tema,descripcion,modalidad,objetivo,fecha_inicio,fecha_fin,tipo_instructor,instructor,reg_instructor_stps)
		VALUES ('$id_capacitacion','$norma','$nom_capacitacion','$hrs_capacitacion','$tema','$descripcion','$modalidad','$objetivo','$fecha_ini', '$fecha_fin','$tipoInstructor','$instructor','$regInsSTPS')";
		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);
		//Verificar Resultado
		if ($rs){
		//Guardar la operacion realizada
		registrarOperacion("bd_recursos",$id_capacitacion,"AgregarCapacitacion",$_SESSION['usr_reg']);
		$conn = conecta("bd_recursos");																			
		echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
 		//Cerrar la conexion con la BD		
		mysql_close($conn);
	 }// Fin function guardarCapacitacion()
?>