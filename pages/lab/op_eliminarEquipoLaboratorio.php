<?php
	/**
	  * Nombre del Módulo: Laboratorio                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández                           
	  * Fecha: 18/Junio/2011                                      			
	  * Descripción: Este archivo permite eliminar los Equipos de la Base de datos
	  **/
	 	
	//Verificamos que este presente el botón eliminar; es decir que haya sido presionado
	if(isset($_POST["sbt_eliminar"])){
		//Si es asi llamamos la función de eliminarEquipo()
		eliminarEquipo();
	}
	
	//Función que permite mostrar los Equipos Registrados en las fechas especificadas
	function mostrarEquipos(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Realizar la conexion a la BD de Laboratorio
		$conn = conecta("bd_Laboratorio");
	
		if(isset($_POST["cmb_marca"])){
			//Creamos la sentencia SQL
			$stm_sql ="SELECT * FROM equipo_lab WHERE marca='$_POST[cmb_marca]' AND estado=1 ORDER BY no_interno";
			
			//Variable que guarda el titulo de la tabla
			$titulo="Equipos Registrados con la Marca <u><em>".$_POST["cmb_marca"] ."</u></em>";
			
			//Variable que almacena el msj de error
			$error="No existen Equipos Registrados con la Marca <u><em>". $_POST["cmb_marca"]."</u></em>";
		}
		else{
			//Creamos la sentencia SQL
			$stm_sql ="SELECT * FROM equipo_lab WHERE no_interno='$_POST[txt_noInterno]' AND estado=1 ORDER BY no_interno";
			
			//Variable que guarda el titulo de la tabla
			$titulo="Equipos Registrados con el N&uacute;mero Interno <u><em>".$_POST["txt_noInterno"]."</u></em>";
			
			//Variable que almacena el msj de error
			$error="No existen Equipos Registrados con el N&uacute;mero Interno <u><em>". $_POST["txt_noInterno"]."</u></em>";
		}
			
					
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos=mysql_fetch_array($rs)){						
			echo "								
			<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'>$titulo</caption>					
				<tr>
					<td  class='nombres_columnas'>SELECCIONAR</td>
					<td class='nombres_columnas'>NO INTERNO</td>
					<td class='nombres_columnas'>NOMBRE</td>
					<td class='nombres_columnas'>MARCA</td>
					<td class='nombres_columnas'>NO SERIE</td>
					<td class='nombres_columnas'>RESOLUCI&Oacute;N</td>
					<td class='nombres_columnas'>ESCALA</td>
					<td class='nombres_columnas'>EXACTITUD</td>
					<td class='nombres_columnas'>ENCARGADO</td>
					<td class='nombres_columnas'>APLICACI&Oacute;N</td>
					<td class='nombres_columnas'>CALIBRABLE</td>
			</tr>";		
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "<tr>
						<td class='nombres_filas' align='center'>
							<input type='radio' id='rdb_equipo' name='rdb_equipo' value='$datos[no_interno]'/>
						</td>		
						<td align='center' class='$nom_clase'>$datos[no_interno]</td>
						<td align='center' class='$nom_clase'>$datos[nombre]</td>
						<td align='center' class='$nom_clase'>$datos[marca]</td>
						<td align='center' class='$nom_clase'>$datos[no_serie]</td>
						<td align='center' class='$nom_clase'>$datos[resolucion]</td>					
						<td align='center' class='$nom_clase'>$datos[escala]</td>
						<td align='center' class='$nom_clase'>$datos[exactitud]</td>
						<td align='center' class='$nom_clase'>$datos[encargado]</td>
						<td align='center' class='$nom_clase'>$datos[aplicacion]</td>";
						if($datos['calibrable']== '1'){
							echo "<td align='center' class='$nom_clase'>SI</td>";
						}
						else{
							echo "<td align='center' class='$nom_clase'>NO</td>";
						}
				echo "</tr>";															
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));

			echo "</table>";
			return 1;
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'>$error</label>";
			return 0;
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion
	
	
	 //Esta funcion genera la Clave del de acuerdo a los registros en la BD
	function obtenerIdEquipoBaja(){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_laboratorio");
		
		$id_cadena="";
		//Crear la sentencia para obtener la Clave reciente acorde a la fecha
		$stm_sql = "SELECT MAX(no_interno)-1 AS cant FROM equipo_lab WHERE no_interno LIKE  '-%' ";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			if($datos['cant']=="")
				$id_cadena="-001";
			$cant = $datos['cant']*-1;
			if($cant>0 && $cant<10)
				$id_cadena .= "-00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "-0".$cant;
			if($cant>=100)
				$id_cadena .= '-'.$cant;
		}

		//Cerrar la conexion con la BD		
		//mysql_close($conn);
		
		return $id_cadena;
	}//Fin de la Funcion obtenerId()	
	
	//Función que permite eliminar el Equipo segun sea seleccionado
	function eliminarEquipo(){
		//Incluimos archivo de conexión
		include_once("../../includes/conexion.inc");
		
		//Realizar la conexion a la BD de Laboratorio
		$conn = conecta("bd_Laboratorio");
		
		//Recuperamos el id del Equipo
		$id=$_POST["rdb_equipo"];
		
		//Obtenemos el id del equipo eliminado
		$id_EquipoEliminado=obtenerIdEquipoBaja();
		
			//Creamos la conslulta SQL que permite eliminar el Equipo de la BD
			$stm_sql2 ="UPDATE equipo_lab SET no_interno='$id_EquipoEliminado',estado=0 WHERE no_interno='$_POST[rdb_equipo]'";
			
			//Ejecutamos la consulta
			$rs2=mysql_query($stm_sql2);
			
			//Registramos la operación en la bitacora de movimientos
			registrarOperacion("bd_Laboratorio",$_POST["rdb_equipo"],"EliminarEquipo",$_SESSION['usr_reg']);
			//Redireccionamos a la pantalla de éxito
		if($rs2){
			//Comprobar tabla cronograma_actividades
			if(obtenerDato("bd_laboratorio","cronograma_servicios","equipo_lab_no_interno","equipo_lab_no_interno", $id)!=''){
				//Creamos la sentencia para actualizar los registros de la tabla cronograma de servicios
				$stm_actualiza= "UPDATE cronograma_servicios SET equipo_lab_no_interno='$id_EquipoEliminado', estado=1 WHERE equipo_lab_no_interno='$id'";
				//Ejecutamos la consulta
				$rsAct=mysql_query($stm_actualiza);
			}
		echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}	
		//Cerramos la conexion con la Base de Datos
		//mysql_close($conn);	
	}
	
	
	
	
?>