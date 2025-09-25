<?php
	/**
	  * Nombre del Módulo: Desarrollo
	  * Nombre Programador: Daisy Adriana Martinez Fernandez
	  * Fecha: 07/Noviembre/2011
	  * Descripción: Permite guardar la información de frm_registrarServicios
	**/
		
	//Función que permite registrar los Servicios
	function registrarServicios(){
		$conn=conecta("bd_desarrollo");
		//Variable de control de insecion de Datos
		$band=0;
		//Registrar al personal del arreglo de Sesion en la Base de Datos para los Servicios
		foreach ($_SESSION['registroServicios'] as $ind => $servicios) {
			$fecha=modFecha($servicios["fecha"],3);			
			//Crear la sentencia para realizar el registro de los datos de la Nómina
			$stm_sql = "INSERT INTO detalle_servicios (id_servicio,fecha,categoria,actividad,turnoOf,turnoAy)
			VALUES('$servicios[id]','$fecha','$servicios[categoria]','$servicios[actividad]','$servicios[turnOf]','$servicios[turnAy]')";
			//Ejecutar la sentencia previamente creada para agregar cada material a la tabla de detalle_servicios
			$rs = mysql_query($stm_sql);
			if(!$rs){
				$band = 1;
				$error = mysql_error();
				break;
			}
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
		unset($_SESSION["registroServicios"]);
		//verificamos que la sentencia sea ejecutada con exito
		if ($band==0){
			//Registrar la Operacion en la tabla de movimientos
			registrarOperacion("bd_desarrollo",date("d/m/Y"),"registroServicio",$_SESSION['usr_reg']);											
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='5;url=error.php?err=$error'>";
		}
	}
	
	//Funcion que permite obtener el id de servicios
	function obtenerId(){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_desarrollo");
		
		$id_cadena="";
		//Crear la sentencia para obtener la Clave reciente acorde al ultimo registro
		$stm_sql = "SELECT MAX(id_servicio)+1 AS cant FROM detalle_servicios";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant=$datos["cant"];
			//Obtener las ultimas 3 cifras de la Bitacora Registrado en la BD y sumarle 1
			if($cant=="")
				$id_cadena=1;
			if($cant>1)
				$id_cadena = $cant;
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
		return $id_cadena;
	}//Fin de la Funcion obtenerId()
	
	function mostrarServicios($servicios){
		echo "<table cellpadding='5' width='100%'>";
		echo "<caption><p class='msje_correcto'><strong>Servicios con Minera Fresnillo</strong></p></caption>";
		echo "      			
			<tr>
				<td class='nombres_columnas' align='center'>FECHA</td>
        		<td class='nombres_columnas' align='center'>CATEGOR&Iacute;A</td>
				<td class='nombres_columnas' align='center'>ACTIVIDAD</td>
				<td class='nombres_columnas' align='center'>TURNOS OFICIAL</td>
				<td class='nombres_columnas' align='center'>TURNOS AYUDANTE</td>
      		</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		foreach ($servicios as $ind => $servicio) {
			echo "<tr>";
			foreach ($servicio as $key => $value) {
				$band=0;
				switch($key){
					case "fecha":
						echo "<td class='nombres_filas' align='center'>$value</td>";
					break;
					case "categoria":
						if ($value=="AMBOS")
							echo "<td class='$nom_clase' align='center'>OFICIAL Y<br>AYUDANTE GENERAL</td>";
						else
							echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "actividad":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "turnOf":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "turnAy":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
				}
			}
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
			echo "</tr>";			
		}
		echo "</table>";
	}
?>
