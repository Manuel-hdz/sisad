<?php
	/**
	  * Nombre del Módulo: Topografía                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas                           
	  * Fecha: 13/Agosto/2012
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de Generar Obras con Equipo Pesado
	  **/
	if(isset ($_POST['sbt_guardar']))
		generarObraEqP();
			
	//Esta función se encarga de generar el Id de las Obras de acuerdo a los registros existentes en la BD
	function obtenerIdObraEq(){
		//Realizar la conexion a la BD de Topografia
		$conn = conecta("bd_topografia");
		
		//Definir las tres letras en la Id de la Obra
		$id_cadena = "OEP";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		//Obtener el mes actual y el año actual 
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener las Obras Reciente acorde a la fecha
		$stm_sql = "SELECT MAX(id_registro) AS cant FROM equipo_pesado WHERE id_registro LIKE 'OEP$mes$anio%'";
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
	 function generarObraEqP(){
	 	include_once("../../includes/conexion.inc");
		include_once("../../includes/op_operacionesBD.php");
		include_once("../../includes/func_fechas.php");
		//Recoger los datos
		$id_obraEq = obtenerIdObraEq();
		$familia = $_POST['cmb_familia'];		
		$concepto = strtoupper($_POST['txt_nombreObraEqP']);
		$unidad = strtoupper($_POST['txt_unidad']);
		$precioEstimacionMN = str_replace(",","",$_POST['txt_precioEstimacionMN']);
		$precioEstimacionUSD = str_replace(",","",$_POST['txt_precioEstimacionUSD']);
		$fechaRegistro = modfecha($_POST['txt_fechaRegistro'],3);
 		//Realizar la conexion a la BD de Topografía
		$conn = conecta("bd_topografia");
		//Crear la Sentencias SQL para Almacenar los datos de la Obra
		$stm_sql= "INSERT INTO equipo_pesado (id_registro, fam_equipo, concepto, unidad, pumn_estimacion, puusd_estimacion, fecha_registro)
					VALUES ('$id_obraEq','$familia','$concepto','$unidad','$precioEstimacionMN','$precioEstimacionUSD','$fechaRegistro')"; 
		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);
		//Verificar Resultado
		if ($rs){
			session_start();
			//Guardar la operacion realizada
			registrarOperacion("bd_topografia",$id_obraEq,"GenerarObraEqPesado",$_SESSION['usr_reg']);															
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	 }// Fin function generarObraEqP()	
?>