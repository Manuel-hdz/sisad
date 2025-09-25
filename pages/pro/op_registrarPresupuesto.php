<?php
	/**
	  * Nombre del Módulo: Producción
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 12/Julio/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de Agregar Presupuesto en la BD
	**/
	
	 //Funcion para guardar la informacion del presupuesto
	function guardarPresupuesto(){
		//Conectar se a la Base de Datos
		$conn = conecta("bd_produccion");

		//Recuperar la informacion del post
		$fechaInicio = modFecha($_POST['txt_fechaIni'],3);
		$fechaFin = modFecha($_POST['txt_fechaFin'],3);
		$volPresMensual= str_replace(",","",$_POST['txt_volPresupuestado']);
		$volDiario= str_replace(",","",$_POST['txt_presupuestoDiario']);
		$diasLaborables= str_replace(",","",$_POST['txt_diasLaborales']);
		$domingos= str_replace(",","",$_POST['txt_domingos']);
		
		//Obtener el id del presupuesto
		$idPresupuesto=obtenerIdPresupuesto();
		
		//Verificamos si viene el combo Activo de Destino o viene de la caja de texto para proceder a almacenarlo
		if (isset($_POST["cmb_destino"]))
			$destino=$_POST["cmb_destino"];
		else{
			$destino=strtoupper($_POST["txt_nuevoDestino"]);
			$idDestino=generarIdDestino();
			//Mandar llamar la funcion que guarda el nuevo destino
			guardarDestino($idDestino,$destino);
			$destino=$idDestino;
		}
		//Obtener el periodo que corresponde		
		$periodo=substr($_POST['txt_fechaFin'],-4)."-";		
		$periodo.=obtenerPeriodo($fechaInicio,$fechaFin);
		
		//Conectar se a la Base de Datos
		$conn = conecta("bd_produccion");
	
		//Crear la Sentencia SQL para Alamcenar los materiales agregados 
		$stm_sql= "INSERT INTO presupuesto (id_presupuesto, catalogo_destino_id_destino, periodo, fecha_inicio, fecha_fin, vol_ppto_mes, vol_ppto_dia, dias_habiles, dias_inhabiles)
		VALUES ('$idPresupuesto', '$destino', '$periodo', '$fechaInicio', '$fechaFin', $volPresMensual, $volDiario, $diasLaborables, $domingos)";		
		
		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);
		
		//Verificar Resultado
		if ($rs){
			//Guardar la operacion realizada
			registrarOperacion("bd_produccion",$idPresupuesto,"RegistrarPresupuesto",$_SESSION['usr_reg']);
			$conn = conecta("bd_produccion");
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
		//Realizar la conexion a la BD de Producción
		$conn = conecta("bd_produccion");
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
	
	
	//Funcion que permite obtener el id del Destino
	function generarIdDestino(){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_produccion");
		$id="";
		//Crear la sentencia para obtener el maximo id registrado en el catalogo
		$stm_sql = "SELECT COUNT(id_destino) AS num, MAX(id_destino)+1 AS cant FROM catalogo_destino";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			//Si el resultado es menor que cero concatenamos la cantidad
			if($datos['num']>0){
				$id .= ($datos['cant']);
			}
			//De lo contrario concatenamos uno
			else {
				$id .= "1";
			}
		}
		else 
			echo"error".mysql_error();		
		return $id;
	}
	
	//Permite guardar el Destino en la base de datos
	function guardarDestino($id,$destino){
		//Conectar se a la Base de Datos
		$conn = conecta("bd_produccion");

		//Crear la Sentencia SQL para Alamcenar lel nuevo destino 
		$stm_sql= "INSERT INTO catalogo_destino(id_destino,destino)	VALUES ('$id','$destino')";		
		
		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);

		//Cerrar la conexion con la BD		
		mysql_close($conn); 

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