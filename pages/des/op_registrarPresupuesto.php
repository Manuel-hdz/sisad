<?php
	/**
	  * Nombre del Módulo: Producción
	  * Nombre Programador: Nadia Madahí López Hernández
	  * Fecha: 16/Diciembre/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de Agregar Presupuesto en la BD de Desarrollo
	**/
	
	 //Funcion para guardar la informacion del presupuesto
	function guardarPresupuesto(){
		//Conectar se a la Base de Datos
		$conn = conecta("bd_desarrollo");
		//Recuperar la informacion del post
		$diasHabiles = ($_POST['txt_diasLaborales']);
		$diasInhabiles = ($_POST['txt_domingos']);		
		$fechaInicio = modFecha($_POST['txt_fechaIni'],3);
		$fechaFin = modFecha($_POST['txt_fechaFin'],3);
		$mtsPres = str_replace(",","",$_POST['txt_mtsPresupuestados']);
		$mtsPresDiario = str_replace(",","",$_POST['txt_mtsPresupuestadosDiarios']);								
		$mtsQuin1 = str_replace(",","",$_POST['txt_mtsQuincena1']);
		$mtsQuin2 = str_replace(",","",$_POST['txt_mtsQuincena2']);
		$disDia = str_replace(",","",$_POST['txt_disparosDia']);
		$disTurno = str_replace(",","",$_POST['txt_disparosTurno']);
	
		
		//Obtener el id del presupuesto
		$idPresupuesto=obtenerIdPresupuesto(); 
		
		//Verificamos si viene el combo Activo de Destino o viene de la caja de texto para proceder a almacenarlo
		if (isset($_POST["cmb_cliente"]))
			$cliente=$_POST["cmb_cliente"];
		else{
			$cliente=strtoupper($_POST["txt_nuevoCliente"]);
			$idCliente=generarIdCliente();
			//Mandar llamar la funcion que guarda el nuevo destino
			guardarCliente($idCliente,$cliente);
			$cliente=$idCliente;
		}	
		
		//Obtener el periodo que corresponde
		$periodo=substr($_POST['txt_fechaFin'],-4)."-";		
		$periodo.=obtenerPeriodo($fechaInicio,$fechaFin);

		//Conectarse a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		//Crear la Sentencia SQL para Alamcenar los datos agregados 
		$stm_sql= "INSERT INTO presupuesto(id_presupuesto, catalogo_clientes_id_cliente, dias_habiles, dias_inhabiles, periodo, fecha_inicio,
		fecha_fin, mts_mes, mts_mes_dia, mts_quincena1, mts_quincena2, disparos_dia, disparos_turno)
		VALUES ('$idPresupuesto', '$cliente','$diasHabiles', '$diasInhabiles', '$periodo', '$fechaInicio', '$fechaFin', '$mtsPres', '$mtsPresDiario',
		'$mtsQuin1', '$mtsQuin2', '$disDia', '$disTurno')";		
		
		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);
		
		//Verificar Resultado
		if ($rs){
			//Guardar la operacion realizada
			registrarOperacion("bd_desarrollo",$idPresupuesto,"RegistrarPresupuesto",$_SESSION['usr_reg']);
			$conn = conecta("bd_desarrollo");
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
 		//Cerrar la conexion con la BD		
		//mysql_close($conn); 
	 }// Fin function guardarMezcla()	
	
	
	/*Esta funcion genera el id del presupuesto de acuerdo a los registros en la BD*/
	function obtenerIdPresupuesto(){
		//Realizar la conexion a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		$id="";
		//Crear la sentencia para obtener el presupuesto Reciente
		$stm_sql = "SELECT MAX(id_presupuesto) AS cant FROM presupuesto";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant = ($datos['cant'])+1;
				$id .= $cant;
		}
		return $id;
	}//Fin de la function obtenerIdPresupuesto()
	
	
	function guardarCliente($id,$cliente){
		//Conectar se a la Base de Datos
		$conn = conecta("bd_desarrollo");

		//Crear la Sentencia SQL para Almacenar la nueva ubicacion 
		$stm_sql= "INSERT INTO catalogo_clientes(id_cliente, nom_cliente)	VALUES ('$id','$cliente')";		
		
		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);

		//Cerrar la conexion con la BD		
		mysql_close($conn); 
	}
	
	function generarIdCliente(){
		//Realizar la conexion a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		$id="";
		//Crear la sentencia para obtener el presupuesto Reciente
		$stm_sql = "SELECT COUNT(id_cliente) AS num, MAX(id_cliente)+1 AS cant FROM catalogo_clientes";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
		
			if($datos['num']>0){
				$id .= ($datos['cant']);
			}
			else {
				$id .= "1";
			}
		}
		else 
			echo"error".mysql_error();		
		return $id;
	}
	
	//Esta funcion Obtiene el periodo correspondiente segun la fecha 
	function obtenerPeriodo($fechaini,$fechafin){
		$inicio=substr($fechaini,5,2);
		switch($inicio){
			case "01":
				$inicio="ENE";
			break;
			case "02":
				$inicio="FEB";
			break;
			case "03":
				$inicio="MAR";
			break;
			case "04":
				$inicio="ABR";
			break;
			case "05":
				$inicio="MAY";
			break;
			case "06":
				$inicio="JUN";
			break;
			case "07":
				$inicio="JUL";
			break;
			case "08":
				$inicio="AGO";
			break;
			case "09":
				$inicio="SEP";
			break;
			case "10":
				$inicio="OCT";
			break;
			case "11":
				$inicio="NOV";
			break;
			case "12":
				$inicio="DIC";
			break;
			default:
				$inicio=""; 
		}
		
		$fin=substr($fechafin,5,2);
			switch($fin){
			case "01":
				$fin="ENE";
			break;
			case "02":
				$fin="FEB";
			break;
			case "03":
				$fin="MAR";
			break;
			case "04":
				$fin="ABR";
			break;
			case "05":
				$fin="MAY";
			break;
			case "06":
				$fin="JUN";
			break;
			case "07":
				$fin="JUL";
			break;
			case "08":
				$fin="AGO";
			break;
			case "09":
				$fin="SEP";
			break;
			case "10":
				$fin="OCT";
			break;
			case "11":
				$fin="NOV";
			break;
			case "12":
				$fin="DIC";
			break;
			default:
				$fin=""; 
		}
		
		return $inicio."-".$fin;
	}// FIN obtenerPeriodo
	
?>