<?php
	/**
	  * Nombre del Módulo: Recursos Humanos                                               
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 07/Junio/2011
	  * Descripción: Este archivo se encarga de buscar en la BD el ultimo prestamo otorgado al empleado
	  **/
	  
	  /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Módulo de conexion con la base de datos 
			2. Módulo de manipulación de fechas*/
			include("../../../../includes/conexion.inc");
			include("../../../../includes/func_fechas.php");

	
	//Verificar si viene la fecha de registro del prestamo para realizar los calculos correspondientes
	if(isset($_GET["fechaReg"])){
		//Obtener el nombre y rfc del Empleado de la URL				
		$fechaReg = $_GET["fechaReg"];		
		$periodo = $_GET["periodo"];
		$montoPrestamo = $_GET["montoPrestamo"];		
		$pagoPorPeriodo = $_GET["pagoPorPeriodo"];
		
		
		//Separar la fecha que viene en formato dd/mm/aaaa
		$partesFecha = explode("/",$fechaReg);
		//Crear la fecha con mktime(hour,minute,second,month,day,year) para saber el numero de semana del año con el metodo date
		$fechaCreada = mktime(00,00,00,$partesFecha[1],$partesFecha[0],$partesFecha[2]);
		//Obtener el Numero de la semana en la cual esta siendo registrado el prestamo
		$noSemana = date("W", $fechaCreada);
			
	
		//Calcular la cantidad de pagos de acuerdo al Monto Total del Préstamo y la cantidad a pagar por periodo
		$partesCantPagos = explode(".",strval($montoPrestamo / $pagoPorPeriodo));
		$cantPagos = intval($partesCantPagos[0]);
		$ultimoPago = $montoPrestamo % $pagoPorPeriodo;
		
		
		//Obtener la cantidad de dias que durará el prestamo de acuerdo a la cantidad de pagos y al periodo (SEMANAL = 7 días y QUINCENAL = 15 días)
		$diasPrestamo = 0;
		if($periodo=="SEMANAL") $diasPrestamo = $cantPagos * 7;	
		else if($periodo=="QUINCENAL") $diasPrestamo = $cantPagos * 15;		
		
		//Obtener el numero del día en el año actual
		$diaActual = date("z", $fechaCreada);
		$totalDiasAnio = 0;
		if(date("L", $fechaCreada)==1) $totalDiasAnio = 365;//Año Bisiesto, la cantidad de dias va de 0 a 365 (366 días en total)
		else if(date("L", $fechaCreada)==0) $totalDiasAnio = 364;//Año regular, la cantidad de dias va de 0 a 364 (365 días en total)		
		
		//Esta variable indicará si el tiempo de vida del prestamo excede el ejercicio fiscal en curso
		$excedeEjerFiscal = "NO";
		if(($diaActual+$diasPrestamo)>$totalDiasAnio)
			$excedeEjerFiscal = "SI";
		
		
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");
		//Imprimir los resultados obtenidos en la estructura del archivo XML
		echo utf8_encode("
			<existe>
				<valor>true</valor>
				<cantPagos>$cantPagos</cantPagos>
				<pagoPorPeriodo>".number_format($pagoPorPeriodo,2,".",",")."</pagoPorPeriodo>
				<ultimoPago>".number_format($ultimoPago,2,".",",")."</ultimoPago>
				<ejerFiscalExcedido>$excedeEjerFiscal</ejerFiscalExcedido>
				<diasAnio>$totalDiasAnio</diasAnio>
				<noDiaFechaRegistro>$diaActual</noDiaFechaRegistro>
				<diasVidaPrestamo>$diasPrestamo</diasVidaPrestamo>
				<ejercicioFiscal>$partesFecha[2]</ejercicioFiscal>
			</existe>");
	}//Cierre if(isset($_GET["fechaReg"]))
	else if(isset($_GET['idPrestamo'])){
		
		//Recuperar datos del GET
		$idPrestamo = $_GET['idPrestamo'];
		
		//Conectarse a la Base de Datos de Recursos Humanos
		$conn = conecta("bd_recursos");
		
		//Crear sentencia SQL para obtener los datos del Prestamo
		$sql_stm = "SELECT fecha_alta, total, saldo_final, fecha_abono FROM deducciones JOIN detalle_abonos ON id_deduccion=deducciones_id_deduccion
					WHERE id_deduccion = '$idPrestamo' AND estado = 'ACTIVO' 
					AND fecha_abono = (SELECT MAX(fecha_abono) FROM detalle_abonos WHERE deducciones_id_deduccion = '$idPrestamo')";
		//Ejecutar la Sentencia SQL
		$rs = mysql_query($sql_stm);
		
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");
		
		//Verificar si hay registros
		if($datosPrestamo=mysql_fetch_array($rs)){
			//Imprimir los resultados obtenidos en la estructura del archivo XML
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<total>".number_format($datosPrestamo['total'],2,".",",")."</total>
					<saldoFinal>".number_format($datosPrestamo['saldo_final'],2,".",",")."</saldoFinal>
					<fechaRegistro>".modFecha($datosPrestamo['fecha_alta'],1)."</fechaRegistro>
					<fechaUltimoAbono>".modFecha($datosPrestamo['fecha_abono'],1)."</fechaUltimoAbono>
				</existe>");
		}
		else{
			//Obtener el monto total del préstamo
			$datoMontoTotal = mysql_fetch_array(mysql_query("SELECT fecha_alta, total FROM deducciones WHERE id_deduccion = '$idPrestamo'"));
			//Imprimir los resultados obtenidos en la estructura del archivo XML
			echo utf8_encode("
				<existe>
					<valor>false</valor>
					<descripcion>PrimerAbono</descripcion>
					<total>".number_format($datoMontoTotal['total'],2,".",",")."</total>
					<fechaRegistro>".modFecha($datoMontoTotal['fecha_alta'],1)."</fechaRegistro>
				</existe>");
		}
		
		
		//Cierre de la conexion
		mysql_close($conn);
		
	}//Cierre else if(isset($_GET['idPrestamo']))

		
?>