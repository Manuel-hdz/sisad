<?php
	/**
	  * Nombre del Módulo: Topografía                                               
	  * Nombre Programador: Maurilio Hernández Correa                            
	  * Fecha: 24/Mayo/2011                                     			
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de Generar estimacion 
	  **/
	if(isset ($_POST['sbt_guardar']))
		generarEstimacion();

	//Esta función se encarga de generar el Id de la estimacion de acurdo a los registros existentes en la BD
	function obtenerIdEstimacion(){
		//Realizar la conexion a la BD de Topografia
		$conn = conecta("bd_topografia");
		
		//Definir las  letras en la Id de la Estimación
		$id_cadena = "EST";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);	
		//Obtener el mes actual y el año actual para ser agregado en la consulta y asi obtener las Estimaciónes del mes en curso del año en curso
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		//Crear la sentencia para obtener el numero de Estimaciónes registradas 
		$stm_sql = "SELECT MAX(id_estimacion) AS cant FROM estimaciones WHERE id_estimacion LIKE 'EST$mes$anio%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant = intval(substr($datos['cant'],7,3));
			$cant += 1;
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
	}//Fin de la Funcion obtenerIdEstimacion()			


	 //Funcion para guardar la estimación
	 function generarEstimacion(){
	 	//Obtener la Tasa de Cambio Original
	 	$tCambioOriginal=obtenerdato("bd_topografia","tasa_cambio","t_cambio","id",1);
		//Recoger los datos
		$id_obra = $_POST['hdn_idObra'];
		$id_estimacion = $_POST['hdn_idEstimacion'];
		$cantidad = str_replace(",","",$_POST['txt_cantidad']);
		$tasaCambio = str_replace(",","",$_POST['txt_tasaCambio']);
		$fechaElaborado = modfecha($_POST['txt_fechaElaborado'],3);
		$totalMN = str_replace(",","",$_POST['txt_totalMN']);
		$totalUSD = str_replace(",","",$_POST['txt_totalUSD']);
		$importe = str_replace(",","",$_POST['txt_importe']);
		$no_quincena = $_POST['cmb_noQuincena'].' '.$_POST['cmb_Mes'].' '.$_POST['cmb_Anio'];

 		//Realizar la conexion a la BD de Topografía
		$conn = conecta("bd_topografia");

		//Verificar si el valor de la Tasa cambio para actualizar el dato
		if($tasaCambio!=$tCambioOriginal)
			mysql_query("UPDATE tasa_cambio SET t_cambio='$tasaCambio' WHERE id='1'");

		//Crear la Sentencias SQL para Alamcenar los datos de la estimacion
		$stm_sql= "INSERT INTO estimaciones (id_estimacion, obras_id_obra, cantidad, t_cambio, total_mn, total_usd, importe,fecha_registro, 
			no_quincena)
		VALUES ('$id_estimacion','$id_obra',$cantidad,$tasaCambio,$totalMN,$totalUSD,$importe,'$fechaElaborado', '$no_quincena')";
		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);
		//Verificar Resultado
		if ($rs){
			//Guardar la operacion realizada
			registrarOperacion("bd_topografia",$id_obra,"GenerarEstimacion",$_SESSION['usr_reg']);
			$conn = conecta("bd_topografia");																			
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			echo $error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
 		//Cerrar la conexion con la BD		
		mysql_close($conn); 
	 }// Fin function generarEstimacion()	
?>