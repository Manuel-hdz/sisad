<?php
	/**
	  * Nombre del Módulo: Seguridad
	  * Nombre Programador: Nadia Madahí López Hernández
	  * Fecha:17/Marzo/2012
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el Tiempo de Vida Util de los Equipos de Seguridad
	**/
	if(isset($_POST['sbt_guardar']) || isset($_POST['sbt_modificar'])){
		registrarTiempoVidaUtilES();	
	}

	//Funcion para guardar la informacion del Equipo de Seguridad de acuerdo al Tiempo de Vida Útil que tienmen
	function registrarTiempoVidaUtilES(){
	
		include_once("../../includes/conexion.inc");
		include_once("../../includes/op_operacionesBD.php");	
		include_once("../../includes/func_fechas.php");	
		
		//Conectar se a la Base de Datos de Seguridad
		$conn = conecta("bd_seguridad");
		
		//OBtener las variables que vienen en el POST		
		$claveMaterial = ($_POST['txt_claveMaterial']);
		$fechaReg = modFecha($_POST['txt_fechaReg'],3);
		$tiempoVida = $_POST['txt_tiempoVida'];
		$tipoTiempo = $_POST['cmb_tipoTiempo'];
		$observaciones = strtoupper($_POST['txa_observaciones']);
		
		//Variable que se utiliza para verificar que tipo de sentencia sql se esta ejecutando de acuerdo al boton que se le da un clic
		$concepto = "";		
	
		if(isset($_POST['sbt_guardar'])){
			//Crear la Sentencia SQL para Alamcenar los datos del Equipo de Protección Personal
			$stm_sql= "INSERT INTO vida_util_es (materiales_id_material, fecha_reg, tiempo_vida, tipo_tiempo, observaciones)
					VALUES ('$claveMaterial', '$fechaReg', '$tiempoVida', '$tipoTiempo', '$observaciones')";
			//Si viene el boton sbt_guardar se ejecutara la sentencia anterior y la variable $concepto, tendra un valor de  RegistrarVidaUtilES
			$concepto = 'RegistrarVidaUtilES';
		}
		else if(isset($_POST['sbt_modificar'])){
			$stm_sql = "UPDATE vida_util_es SET materiales_id_material = '$claveMaterial' , fecha_reg = '$fechaReg', tiempo_vida = '$tiempoVida',
			tipo_tiempo = '$tipoTiempo', observaciones = '$observaciones' WHERE materiales_id_material = '$claveMaterial'";
			//Al contrario si la sentencia ejecutada es la de modificar la variable tomara el siguiente valor => $concepto = 'ModificarVidaUtilES'
			$concepto = 'ModificarVidaUtilES';
		}				
		//Ejecutar laS Sentencias previamente Creadas
		$rs=mysql_query($stm_sql);
		
			//Verificar Resultado 
			if ($rs){ 
				/*Guardar la operacion realizad0, ademas de acuerdo a la consulta ejecutada se guardara dentro de la
				 bitacora de movimientos el valor que contenga la variable $concepto */
				registrarOperacion("bd_seguridad",$claveMaterial,$concepto,$_SESSION['usr_reg']);	
				echo "<meta http-equiv='refresh' content='0;url=exito.php'>";	
			}
			else{
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}
	 }// Fin function registrarTiempoVidaUtilES()	
?>	