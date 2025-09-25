<?php
	/**
	  * Nombre del Módulo: Seguridad
	  * Nombre Programador: Nadia Madahí López Hernández
	  * Fecha: 16/Enero/2012
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de Agregar  en la BD
	**/

	if(isset($_POST['sbt_guardar'])){
		registrarBitacora();
	}
		
	
	 //Funcion para guardar la informacion de la Bitacora 
	function registrarBitacora(){
		//Iniciamos la sesion
		session_start();
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");

		//Conectar se a la Base de Datos
		$conn = conecta("bd_seguridad");
		
		//Recuperar la informacion del post
		
		$residuo = strtoupper($_POST['cmb_residuo']);
		$clasificacion = strtoupper($_POST['txt_clasificacionSol']);
		$area = strtoupper($_POST['txt_area']);
		$cantidad = strtoupper($_POST['txt_cantGenerada']);
		$unidad = str_replace(",","",$_POST['txt_unidad']);
		$nomEntrega = strtoupper($_POST['txt_nomEntrega']);
		$nomRecibe = strtoupper($_POST['txt_nomRecibe']);
		$fechaIngreso = modFecha($_POST['txt_fechaIng'],3);
		$fechaSalida = modFecha($_POST['txt_fechaSal'],3);
		$razSocial = strtoupper($_POST['txt_razSocial']);
		$numMan = $_POST['txt_numManifiesto'];
		$numAut = strtoupper($_POST['txt_numAutorizacion']);
		$desBit = strtoupper($_POST['txa_descripcion']);
		$resBit = strtoupper($_POST['txt_responsableBit']);
		$nomTrans = strtoupper($_POST['txt_nomTransportista']);
	
		$clave = $_POST['txt_claveBitacora'];
		
		//Revisamos los checkBox pra verificar cuales fueron seleccionados
		if(isset($_POST['ckb_peligrosidadC']))
		 	$ckb_peligrosidadC = 1;
		else
			$ckb_peligrosidadC = 0;
		if(isset($_POST['ckb_peligrosidadR']))
		 	$ckb_peligrosidadR = 1;
		else
			$ckb_peligrosidadR = 0;
		if(isset($_POST['ckb_peligrosidadE']))
		 	$ckb_peligrosidadE = 1;
		else
			$ckb_peligrosidadE = 0;
		if(isset($_POST['ckb_peligrosidadT']))
		 	$ckb_peligrosidadT = 1;
		else
			$ckb_peligrosidadT = 0;
    	if(isset($_POST['ckb_peligrosidadI']))
		 	$ckb_peligrosidadI = 1;
		else
			$ckb_peligrosidadI = 0;
    
 
	
		//Crear la Sentencia SQL para Alamcenar los materiales agregados 
		$stm_sql= "INSERT INTO bitacora_residuos (id_bitacora_residuos, tipo_residuo, clasificacion_solido, nom_firm_entrega, nom_firm_recibe, fecha_ingreso,
				 fecha_salida,	razon_social, num_manifiesto, num_autorizacion, nom_transportista, pel_corrosivo, pel_reactivo, pel_explosivo, pel_toxico,
				 pel_inflamable, fase_salida, area, cantidad, tipo_unidad, responsable_bit )
				VALUES ('$clave', '$residuo', '$clasificacion', '$nomEntrega', '$nomRecibe', '$fechaIngreso', '$fechaSalida', '$razSocial',	'$numMan',
				'$numAut', '$nomTrans', '$ckb_peligrosidadC', '$ckb_peligrosidadR', '$ckb_peligrosidadE', '$ckb_peligrosidadT', '$ckb_peligrosidadI',
				'$desBit', '$area',	'$cantidad', '$unidad',	'$resBit')";
		
		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);
		//Verificar Resultado
		if ($rs){
			//Guardar la operacion realizada
			registrarOperacion("bd_seguridad",$clave,"RegistrarBitacora",$_SESSION['usr_reg']);
			$conn = conecta("bd_seguridad");
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	 }// Fin function registrarBitacora()	
?>