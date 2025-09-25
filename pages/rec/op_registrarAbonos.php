<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 25/Abril/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de registrar Abonos en la BD
	**/		


	//Funcion para guardar las abonos agregad0s al arreglo de session
	function guardarAbonos(){
		//Conectarcs a la Base de Datos
		$conn = conecta("bd_recursos");
		
		//Recuperar Datos del POST
		$idDeduccion = $_POST['cmb_idDeduccion'];
		$saldoInicial = str_replace(",","",$_POST['txt_saldoActual']); 
		$abono = str_replace(",","",$_POST['txt_abono']);
		$saldoFinal = str_replace(",","",$_POST['txt_nuevoSaldo']);
		$fechaAbono = modFecha($_POST['txt_fechaAbono'],3);
						
		
		//Sí el nuevo saldo después de la aplicación del abono es igual a 0, procedemos a cambiar el estado de la Deducción o Prestamo a TERMINADO
		if($saldoFinal==0){
			//Crear la Sentencia SQL para cambiar el estado  
			$stm_sql="UPDATE deducciones SET estado='TERMINADO' WHERE id_deduccion = '$idDeduccion'";
			//Ejecutar la Sentencia 
			$rs = mysql_query($stm_sql);
		}//Cierre if($saldoFinal==0)
		
	
		//Crear la Sentencia SQL para Almacenar los datos del Abono
		$stm_sql = "INSERT INTO detalle_abonos (deducciones_id_deduccion, saldo_inicial, abono, saldo_final, fecha_abono) 
					VALUES ('$idDeduccion', $saldoInicial, $abono, $saldoFinal, '$fechaAbono')";
		
		//Ejecutar la Sentencia 
		$rs=mysql_query($stm_sql);
		
		$mensaje = "";
		
		//Verificar Resultado
		if($rs){		
			//Guardar el registro de movimientos
			registrarOperacion("bd_recursos",$idDeduccion,"RegistrarAbono",$_SESSION['usr_reg']);			
			$mensaje = "Datos Guardados Correctamente";
		}
		else{			
			$error = mysql_error();
			$mensaje = "No se Pudieron Guardar los Datos";
		}
		
		return $mensaje;
		
	}//Cierre de la función guardarAbonos()
	
	
	
	
	
?>