<?php
	/**
	  * Nombre del Módulo: Desarrollo
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 21/Octubre/2011
	  * Descripción: Este archivo contiene funciones para Realizar consultas al Catalogo de Sueldos
	**/
	
	//Funcion que agregar el sueldo al registro de la Tabla correspondiente
	function agregarSueldo(){
		//Recuperar las variables de los Sueldos
		$sueldoB=str_replace(",","",$_POST["txt_sueldoBase"]);
		$puesto=strtoupper($_POST["txt_nuevoPuesto"]);
		if(isset($_POST["cmb_area"]))
			$area=$_POST["cmb_area"];
		else
			$area=strtoupper($_POST["txt_nuevaArea"]);
		//Si el area es igual a Jumbo, Voladuras o Scoop, recoger los valores asignados, de otra manera, asignar el valor -1
		if ($area=="JUMBO" || $area=="VOLADURAS" || $area=="SCOOP"){
			$actividad=str_replace(",","",$_POST["txt_porcActividad"]);
			$metro=str_replace(",","",$_POST["txt_porcMetro"]);
		}
		else{
			$actividad=-1;
			$metro=-1;
		}
		//Escribimos la consulta que ingresa los salarios al catalogo
		$stm_sql = "INSERT INTO catalogo_salarios(puesto,sueldo_base,area,pctje_inc_act,pctje_inc_mts) VALUES ('$puesto',$sueldoB,'$area','$actividad','$metro')";
		//Realizar la conexion a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		//Ejecutar la Sentencia creada
		$rs = mysql_query($stm_sql);
		//Verificar si la consulta se realizo de manera correcta
		if ($rs){
			//Registrar la Operacion en la tabla de movimientos
			registrarOperacion("bd_desarrollo",$area."-".substr($puesto,0,3),"agregarSueldo",$_SESSION['usr_reg']);	
			//Redireccionar a la Pagina de exito				
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			//Si los datos no se agregaron correctamente, guardar el error generado
			$error = mysql_error();
			//Cerrar la conexion con la BD, solo se cierra en este caso ya que en caso de Exito, la conexion la cierra el Registrar Operacion
			mysql_close($conn);
			//Redireccionar a la pantalla de Error
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}//Fin de la funcion agregarSueldo()
	
	//Funcion que modifica los Sueldos de los trabajadores
	function modificarSueldo(){
		//Realizar la conexion a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		//Recuperar las variables de Sueldos
		$sueldoB=str_replace(",","",$_POST["txt_sueldoBase"]);
		$puesto=$_POST["cmb_puestos"];
		$area=$_POST["cmb_area"];
		if ($area=="JUMBO" || $area=="VOLADURAS" || $area=="SCOOP"){
			$actividad=str_replace(",","",$_POST["txt_porcActividad"]);
			$metro=str_replace(",","",$_POST["txt_porcMetro"]);
		}
		else{
			$actividad=-1;
			$metro=-1;
		}
		//Escribimos la consulta que actualiza el salario del catalogo
		$stm_sql = "UPDATE catalogo_salarios SET sueldo_base='$sueldoB',pctje_inc_act='$actividad',pctje_inc_mts='$metro' WHERE puesto='$puesto' AND area='$area'";
		//Ejecutar la Sentencia creada
		$rs = mysql_query($stm_sql);
		//Verificar si la consulta se realizo de manera correcta
		if ($rs){
			//Registrar la Operacion en la tabla de movimientos
			registrarOperacion("bd_desarrollo",$area."-".substr($puesto,0,3),"modificarSueldo",$_SESSION['usr_reg']);	
			//Redireccionar a la Pagina de exito
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			//Si los datos no se agregaron correctamente, guardar el error generado
			$error = mysql_error();
			//Cerrar la conexion con la BD, solo se cierra en este caso ya que en caso de Exito, la conexion la cierra el Registrar Operacion
			mysql_close($conn);
			//Redireccionar a la pantalla de Error
			echo "<meta http-equiv='refresh' content='5;url=error.php?err=$error'>";
		}
	}//Fin de la funcion modificarSueldo()	
?>