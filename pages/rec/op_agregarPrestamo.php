<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 18/Abril/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de Agregar Préstamo en la BD
	**/
			
		
	/*Esta funcion genera el id del bono de acuerdo a los registros en la BD*/
	function obtenerIdPrestamo(){
		//Realizar la conexion a la BD de Recursos Humanos
		$conn = conecta("bd_recursos");
		
		//Definir las tres letras del Id del Prestamo
		$id_cadena = "PRE";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		//Obtener el mes actual y el año actual 
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener la Capacitacion Reciente acorde a la fecha
		$stm_sql = "SELECT MAX(id_deduccion) AS cant FROM deducciones WHERE id_deduccion LIKE 'PRE$mes$anio%'";
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
	}//Fin de la function obtenerIdPrestamo()


	//Funcion para guardar el prestamo otorgado al empleado
	function guardarPrestamo(){	
		
		//Realizar la conexion a la BD de Recursos Humanos
		$conn = conecta("bd_recursos");
		
		//Recoger los datos del Vector POST		
		$idDeduccion = $_POST['txt_idPrestamo'];
		$rfcEmpleado = $_POST['txt_RFCEmpleado'];
		
		//Obtener Nombre del prestamo, el cual viene en el ComboBox o en la caja de texto cuando es nuevo
		if(isset($_POST["cmb_nomPrestamo"]))
			$nomPrestamo = $_POST['cmb_nomPrestamo'];
		else if(isset($_POST["txt_nuevoPrestamo"]))
			$nomPrestamo = strtoupper($_POST['txt_nuevoPrestamo']);
					
		$descripcion = strtoupper($_POST['txa_descripcion']);
		$autorizo = strtoupper($_POST['txt_autorizo']);
		$fecha_registro = modfecha($_POST['txt_fechaRegistro'],3);
		$periodo = $_POST['cmb_periodo'];
		$cantPagos = $_POST['txt_cantPagos'];
		$pagoPorPeriodo = str_replace(",","",$_POST['txt_pagoPorPeriodo']);
		$cantPrestamo = str_replace(",","",$_POST['txt_cantidadPrestamo']);								
		 
		//Crear la Sentencias SQL para Alamcenar los datos de la capacitacion
		$stm_sql = "INSERT INTO deducciones (id_deduccion, empleados_rfc_empleado, nom_deduccion, descripcion, autorizo, fecha_alta, estado, justificacion, periodo, cant_pagos,
					pago_periodo, total) VALUES('$idDeduccion','$rfcEmpleado','$nomPrestamo','$descripcion','$autorizo','$fecha_registro','ACTIVO','','$periodo',
					$cantPagos,$pagoPorPeriodo,$cantPrestamo)";
		
		//Ejecutar la Sentencia
		$rs = mysql_query($stm_sql);
		//Verificar Resultado, Si no es favorable, activar la bandera
		if($rs){			
			
			//Guardar el registro de movimientos
			registrarOperacion("bd_recursos",$idDeduccion,"RegistrarPrestamo",$_SESSION['usr_reg']);
			
			//Abrir Archivo PDF con los datos del prestamo recien registrado?>
			<script type="text/javascript" language="javascript">
				var codigo = "window.open('../../includes/generadorPDF/contratoPrestamo.php?idPrestamo=<?php echo $idDeduccion; ?>', ";
				codigo += "'_blank','top=0, left=0, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')";
				setTimeout(codigo,3000);
			</script><?php
			
			//Redireccionar a la pagina de exito
			echo "<meta http-equiv='refresh' content='4;url=exito.php'>";
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			//Cerrar la conexion con la BD		
			mysql_close($conn);
		}
		
	}//Cierre de la funcion function guardarPrestamo()	

	
?>