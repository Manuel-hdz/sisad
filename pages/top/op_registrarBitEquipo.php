<?php
	/**
	  * Nombre del Módulo: Topografía
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 15/Agosto/2012
	  * Descripción: Este archivo contiene las funciones para guardar los datos de Traspaleo de las Obras seleccionadas
	  **/

	  
	//Esta función se encarga de generar el Id de los Registros Traspaleo
	function obtenerIdEquipoPesado(){
		//Realizar la conexion a la BD de Topografia
		$conn = conecta("bd_topografia");
		//Crear la sentencia para obtener los Traspaleos Reciente acorde a la fecha
		$stm_sql = "SELECT MAX(idbitacora)+1 AS cant FROM bitacora_eq_pesado";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			if($datos["cant"]!=NULL)
				$id_cadena=$datos["cant"];
			else
				$id_cadena=1;
		}
		else
			$id_cadena=1;
		//Cerrar la conexion con la BD		
		mysql_close($conn);
		return $id_cadena;
	}//Fin de la function obtenerIdTraspaleo()
	
	//Funcion que registra en la bitacora de Equipo
	function guardarRegistro(){
		//Obtener la Tasa de Cambio Original
	 	$tCambioOriginal=obtenerdato("bd_topografia","tasa_cambio","t_cambio","id",1);
		$band=0;
		$idBitacora=obtenerIdEquipoPesado();
		$idRegEP=$_POST["hdn_idReg"];
		$tasaCambio=$_POST["txt_tasaCambio"];
		$fecha=modFecha($_POST["txt_fechaRegistro"],3);
		$quincena=$_POST['cmb_noQuincena']." ".$_POST['cmb_Mes']." ".$_POST['cmb_Anio'];
		//Realizar la conexion a la BD de Topografia
		$conn = conecta("bd_topografia");
		//Verificar si el valor de la Tasa cambio para actualizar el dato
		if($tasaCambio!=$tCambioOriginal)
			mysql_query("UPDATE tasa_cambio SET t_cambio='$tasaCambio' WHERE id='1'");
		//Sentencia de insercion
		$sql_stm="INSERT INTO bitacora_eq_pesado (idbitacora,equipo_pesado_id_registro,t_cambio,fecha_registro,no_quincena) VALUES ('$idBitacora','$idRegEP','$tasaCambio','$fecha','$quincena')";
		$rs=mysql_query($sql_stm);
		if($rs){
			foreach($_SESSION["registroEquipos"] as $ind => $value){
				$sql_stm="INSERT INTO detalle_eq_pesado (bitacora_eq_pesado_idbitacora,id_equipo,cantidad) VALUES ('$idBitacora','$ind','$value')";
				$rs=mysql_query($sql_stm);
				if(!$rs){
					$band=1;
					break;
				}
			}
		}
		else
			$band=1;
		//Si la bandera se activo ejecutar el rollback
		if($band==0){
			//Cerrar la BD
			mysql_close($conn);
			//Registrar la Operacion en la Bitácora de Movimientos
			registrarOperacion("bd_topografia",$idBitacora,"AgregarRegBitEquipo",$_SESSION['usr_reg']);
			//Redireccionar a la pantalla de Exito
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			//Obtener el error generado
			$error=mysql_error();
			//Borrar los registros en caso de error
			mysql_query("DELETE FROM bitacora_eq_pesado WHERE idbitacora = '$idBitacora'");
			mysql_query("DELETE FROM detalle_eq_pesado WHERE bitacora_eq_pesado_idbitacora = '$idBitacora'");
			//Cerrar la conexion con la BD
			mysql_close($conn);
			//Redireccionar a Error
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}
?>