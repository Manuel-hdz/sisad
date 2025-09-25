<?php
	/**
	  * Nombre del Módulo: Topografía
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 14/Septiembre/2012
	  * Descripción: Este archivo contiene las funciones para guardar los datos de Anclas
	  **/

	  
	//Esta función se encarga de generar el Id de los Registros de Anclas
	function obtenerIdAnclas(){
		//Realizar la conexion a la BD de Topografia
		$conn = conecta("bd_topografia");
		
		//Definir las tres letras en la Id de la Obra
		$id_cadena = "BIA";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		//Obtener el mes actual y el año actual 
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener las Obras Reciente acorde a la fecha
		$stm_sql = "SELECT MAX(id_bitacora) AS cant FROM bitacora_anclas WHERE id_bitacora LIKE 'BIA$mes$anio%'";
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
	}//Fin de la function obtenerIdTraspaleo()
	
	//Funcion que registra en la bitacora de Anclas
	function guardarRegistro(){
		//Obtener la Tasa de Cambio Original
	 	$tCambioOriginal=obtenerdato("bd_topografia","tasa_cambio","t_cambio","id",1);
		$band=0;
		$idBitacora=obtenerIdAnclas();
		$idObra=$_POST["hdn_idObra"];
		$quincena=$_POST['cmb_noQuincena']." ".$_POST['cmb_Mes']." ".$_POST['cmb_Anio'];
		$cantidad=$_POST["txt_cantidadTotal"];
		$tasaCambio=$_POST["txt_tasaCambio"];
		$fecha=modFecha($_POST["txt_fechaRegistro"],3);
		
		//Realizar la conexion a la BD de Topografia
		$conn = conecta("bd_topografia");
		//Verificar si el valor de la Tasa cambio para actualizar el dato
		if($tasaCambio!=$tCambioOriginal)
			mysql_query("UPDATE tasa_cambio SET t_cambio='$tasaCambio' WHERE id='1'");
		//Sentencia de insercion
		$sql_stm="INSERT INTO bitacora_anclas (id_bitacora, obras_id_obra, no_quincena, cantidad, t_cambio, fecha_registro) VALUES('$idBitacora','$idObra','$quincena','$cantidad','$tasaCambio','$fecha')";
		//Ejecutar la sentencia SQL
		$rs=mysql_query($sql_stm);
		if($rs){
			//Cerrar la BD
			mysql_close($conn);
			//Registrar la Operacion en la Bitácora de Movimientos
			registrarOperacion("bd_topografia",$idBitacora,"AgregarRegBitAnclas",$_SESSION['usr_reg']);
			//Redireccionar a la pantalla de Exito
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			//Obtener el error generado
			$error=mysql_error();
			//Cerrar la conexion con la BD
			mysql_close($conn);
			//Redireccionar a Error
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}
?>