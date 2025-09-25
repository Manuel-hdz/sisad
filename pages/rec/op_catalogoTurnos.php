<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 25/Enero/2012
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con los formularios de agregarTurno en la BD
	**/

	//Funcion que agrega un nuevo turno
	function agregarTurno(){
		$clave=1;
		$res=0;
		//Conectarse a la BD de Recursos
		$conn = conecta("bd_recursos");
		//Obtener la clave del turno que le sera asignada
		$stm_sql = "SELECT MAX(id_turno)+1 AS claveTurno FROM turnos";
		$rs = mysql_query($stm_sql);
		if($datos = mysql_fetch_array($rs)){	
			if ($datos[0]!=NULL)
				$clave=$datos[0];
		}
		//Obtener el nombre del Turno
		$turno=strtoupper($_POST["txt_nuevoTurno"]);
		//Manejamos la Hora en una cadena de tipo hh:mm AM/PM para que se convierta al formato soportado por MySQL
		$horaE=$_POST["txt_horaE"]." ".$_POST["cmb_horaE"];
		//ESPACIO IMPORTANTE--------^
		//Modificar la hora a formato 24hrs
		$horaE=modHora24($horaE);
		//Manejamos la Hora en una cadena de tipo hh:mm AM/PM para que se convierta al formato soportado por MySQL
		$horaS=$_POST["txt_horaS"]." ".$_POST["cmb_horaS"];
		//ESPACIO IMPORTANTE--------^
		//Modificar la hora a formato 24hrs
		$horaS=modHora24($horaS);
		$comentarios=strtoupper($_POST["txa_comentarios"]);
		//Sentencia SQL para insertar los datos
		$stm_sql="INSERT INTO turnos (id_turno,nom_turno,hora_entrada,hora_salida,comentarios) VALUES ('$clave','$turno','$horaE','$horaS','$comentarios')";
		$rs=mysql_query($stm_sql);
		//Si la consulta se ejecuto correctamente, modificar el valor a la variable de referencia $res
		if ($rs){
			$res=1;
			//Registrar la Operacion en la Bitácora de Movimientos
			registrarOperacion("bd_recursos","$clave","AgregarTurno",$_SESSION['usr_reg']);
		}
		else
			//Cerrar la conexion con la BD
			mysql_close($conn);
		//Regresar el resultado de la consulta
		return $res;
	}//fin de agregarTurno()
	
	//Funcion que modifica un turno seleccionado
	function modificarTurno(){
		$res=0;
		//Conectarse a la BD de Recursos
		$conn = conecta("bd_recursos");
		//Obtener el nombre del Turno
		$turno=$_POST["cmb_turnos"];
		//Manejamos la Hora en una cadena de tipo hh:mm AM/PM para que se convierta al formato soportado por MySQL
		$horaE=$_POST["txt_horaE"]." ".$_POST["cmb_horaE"];
		//ESPACIO IMPORTANTE--------^
		//Modificar la hora a formato 24hrs
		$horaE=modHora24($horaE);
		//Manejamos la Hora en una cadena de tipo hh:mm AM/PM para que se convierta al formato soportado por MySQL
		$horaS=$_POST["txt_horaS"]." ".$_POST["cmb_horaS"];
		//ESPACIO IMPORTANTE--------^
		//Modificar la hora a formato 24hrs
		$horaS=modHora24($horaS);
		$comentarios=strtoupper($_POST["txa_comentarios"]);
		//Sentencia SQL para insertar los datos
		$stm_sql="UPDATE turnos SET hora_entrada='$horaE',hora_salida='$horaS',comentarios='$comentarios' WHERE nom_turno='$turno'";
		$rs=mysql_query($stm_sql);
		//Si la consulta se ejecuto correctamente, modificar el valor a la variable de referencia $res
		if ($rs){
			$res=2;
			//Registrar la Operacion en la Bitácora de Movimientos
			registrarOperacion("bd_recursos","$turno","ModificarTurno",$_SESSION['usr_reg']);
		}
		else
			//Cerrar la conexion con la BD
			mysql_close($conn);
		//Regresar el resultado de la consulta
		return $res;
	}//fin de modificarTurno
	
	//Funcion que modifica un turno seleccionado
	function borrarTurno(){
		$res=0;
		//Conectarse a la BD de Recursos
		$conn = conecta("bd_recursos");
		//Obtener el nombre del Turno
		$turno=$_GET["borrar"];
		//Sentencia SQL para borrar los datos
		$stm_sql="DELETE FROM turnos WHERE nom_turno='$turno'";
		$rs=mysql_query($stm_sql);
		//Si la consulta se ejecuto correctamente, modificar el valor a la variable de referencia $res
		if ($rs){
			$res=3;
			//Registrar la Operacion en la Bitácora de Movimientos
			registrarOperacion("bd_recursos","$turno","EliminarTurno",$_SESSION['usr_reg']);
			//Borrar los Turnos asignados a los Trabajadores en la Tabla de Roles
			verificarRoles($turno);
		}
		else{
			//Cerrar la conexion con la BD
			mysql_close($conn);
		}
		//Regresar el resultado de la consulta
		return $res;
	}//fin de modificarTurno
	
	function verificarRoles($turno){
		$id_turno=obtenerDato("bd_recursos","turnos","id_turno","nom_turno",$turno);
		//Conectarse a la BD de Recursos
		$conn = conecta("bd_recursos");
		//Sentencia SQL para borrar los datos
		$stm_sql="DELETE FROM roles WHERE turnos_id_turno='$id_turno'";
		$rs=mysql_query($stm_sql);
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}
?>