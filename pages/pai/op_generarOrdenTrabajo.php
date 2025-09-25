<?php
	/**
	  * Nombre del Módulo: Mantenimiento
	  * Nombre Programador: Maurilio Hernandez Correa
	  * Fecha: 21/Febrero/2011                                      			
	  * Descripción: Este archivo contiene funciones dpara generar la orden de trabajo, el detalle del vale y el vale
	  **/
	// include para poder generar el id de la bitacora
	//include ("op_registrarBitacora.php");


	 //Funcion para guardar las gamas
	 function guardarGamas(){
		//Conectar se a la Base de Datos
		$conn = conecta("bd_paileria");
	 	$band =0;
		$conceptoOT = $_SESSION['datosOT'];
		//Recorrer el arreglo que contiene las gamas
		
		foreach($_SESSION['gamasOT'] as $ind => $concepto){
		//Creamos la sentencia SQL para insertar los datos en la tabla Mecanico
		$stm_sql="INSERT INTO actividades_ot (orden_trabajo_id_orden_trabajo, gama_id_gama)
		VALUES('$conceptoOT[orden_trabajo]',  '$concepto[id_gama]')";
					
		//Ejecutar la sentencia previamente creadas
		$rs = mysql_query($stm_sql);
		if(!$rs)
			$band = 1;						
	
		//Romper el proceso de registro del detalle de la entrada en el caso de que existan errores	
		if($band==1)
			break;	
		}
		
		if ($band==1){
			$error = mysql_error();
			echo "</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>$error";
			echo "<meta http-equiv='refresh' content='5;url=error.php?err=$error'>";
		}
		else{
			generarOrdenTrabajo();								
			//echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}	
	 
	 }// Fin function guardarGamas()

	
	//Funcion que se encarga de guardar la orden de trabajo en la talba orden_trabajo	
	function generarOrdenTrabajo(){
		//Realizar la conexion a la BD de mantenimiento
		$conn = conecta("bd_paileria");
				
		$cmb_servicio = $_SESSION['datosOT']['servicio'];
		$cmb_area = $_SESSION['datosOT']['area'];
		$cmb_familia = $_SESSION['datosOT']['familia'];
		$cmb_claveEquipo = $_SESSION['datosOT']['claveEquipo'];
		$txt_ordenTrabajo = $_SESSION['datosOT']['orden_trabajo'];
		$txt_fechaOrdenTrabajo = $_SESSION['datosOT']['fechaOrdenTrabajo'];
		$txt_fechaProgramada = $_SESSION['datosOT']['fechaProgramada'];
		$cmb_metrica = $_SESSION['datosOT']['metrica'];
		$txt_cantidadMetrica = $_SESSION['datosOT']['cantidadMetrica'];
		$txt_operadorEquipo = $_SESSION['datosOT']['operadorEquipo'];
		$cmb_turno = $_SESSION['datosOT']['turno'];
		$txa_comentarios = $_SESSION['datosOT']['comentarios'];	
		$cmb_autorizoOT=$_SESSION['datosOT']['autorizoOT'];
		$txt_proveedor=$_SESSION['datosOT']['proveedor'];

		$supervisor = $_SESSION['datosOT']['supervisor'];
		$generador = $_SESSION['datosOT']['generador'];
		$revisor = $_SESSION['datosOT']['revisor'];

		// variable inicializada en 0 ya que al crear una orden de trabajo su estado por default es creada o iniciada que corresponde al 0
		$estado= 0;

		if ((isset($_SESSION['datosOT']['metrica'])) && ($cmb_metrica=='HOROMETRO')){		
			//Crear la Sentecnia SQL para insertar los datos
			$stm_sql = "INSERT INTO orden_trabajo (id_orden_trabajo,servicio,fecha_creacion,fecha_prog,turno,horometro,odometro,operador_equipo,comentarios,
			estado, supervisor, generador, revisor, autorizo_ot, proveedor_servicio)
			VALUES('$txt_ordenTrabajo','$cmb_servicio','$txt_fechaOrdenTrabajo','$txt_fechaProgramada','$cmb_turno','$txt_cantidadMetrica','0',
			'$txt_operadorEquipo','$txa_comentarios', $estado, '$supervisor', '$generador', '$revisor', '$cmb_autorizoOT','$txt_proveedor')";
		}
		else{
			//Crear la Sentecnia SQL para insertar los datos 
			$stm_sql = "INSERT INTO orden_trabajo (id_orden_trabajo,servicio,fecha_creacion,fecha_prog,turno,horometro,odometro,operador_equipo,comentarios,
			estado, supervisor, generador, revisor, autorizo_ot, proveedor_servicio)
			VALUES('$txt_ordenTrabajo','$cmb_servicio','$txt_fechaOrdenTrabajo','$txt_fechaProgramada','$cmb_turno', '0' ,'$txt_cantidadMetrica',
			'$txt_operadorEquipo','$txa_comentarios', $estado, '$supervisor', '$generador', '$revisor', '$cmb_autorizoOT','$txt_proveedor')";
		}	
	
		//Ejecutar la Sentencia SQL
		$rs = mysql_query($stm_sql);
		
		if($rs){
			//Guardar el registro de movimientos
			registrarOperacion("bd_paileria",$_SESSION['datosOT']['orden_trabajo'],"GenerarOT",$_SESSION['usr_reg']);
			//Si se agrega corectamente generar el pdf de la orden de trabajo?>
			<script type='text/javascript' language='javascript'>
				var codigoPopUp = "window.open('../../includes/generadorPDF/ordenTrabajoGomar.php? id=<?php echo $_SESSION['datosOT']['orden_trabajo'];  ?>', ";
				codigoPopUp += "'_blank','top=100, left=100, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, ";
				codigoPopUp += "toolbar=no, location=no, directories=no');";
				setTimeout(codigoPopUp,4000);
			</script><?php
			//Si los datos fueron agregados correctamente llamar la funcion bitacora() para en ella registrar el id_bitacora, id_orden_trabajo y id_equipo
			bitacora();			
		}	
		else{ //Si los datos no se agregaron correctamente, se redirecciona a la pagina de error	
							
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}		
	}//Cierre de función generarOrdenTrabajo()

	
	/*Esta funcion se encarga de hacer el Registro que relaciona la Orden de Trabajo con el Equipo en la Bitacora de Mtto.*/
	function bitacora(){
		// Obtener el Id de la bitacora llamando la funcion que se encuentra en el archivo op_registrarBitacora.php
		$id_bitacora = obtenerIdRegBitacora();

		//Conectarse a la BD de Mantenimiento
		$conn = conecta("bd_paileria");
		
		//Recuperar el id de la orden de trabajo y el id del equipo del arreglo de session datosOT
		$conceptoOT = $_SESSION['datosOT'];
		
		//asignarle el valor preventio a la variable ya que por default al hacer la OT este es el tipo de mtto.
		$tipo_mtto= "PREVENTIVO";
		
		//Definimos el turno
		$cmb_turno = $_SESSION['datosOT']['turno'];
		
		//crear la sentencia SQL para insertar los datos que ya vienen cargados a la table de bitacora_mtto
		$stm_sql = "INSERT INTO bitacora_mtto(id_bitacora, orden_trabajo_id_orden_trabajo, equipos_id_equipo, tipo_mtto, turno)
		VALUES ('$id_bitacora', '$conceptoOT[orden_trabajo]', '$conceptoOT[claveEquipo]', '$tipo_mtto', '$cmb_turno' )";
								
		//Ejecutar la Sentencia SQL
		$rs = mysql_query($stm_sql);
		if ($rs){
			//Actualizar el estado de la Alerta de 1 a 2
			regEstadoAlerta($_SESSION['datosOT']['claveEquipo']);
			
			//Cerrar la Conexion con la BD
			mysql_close($conn);
			
			echo "<meta http-equiv='refresh' content='5;url=exito.php'>";
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}					
	 }
	 
	 
	 /*Esta funcion se ecnarga de actualizar el estado de la alerta en la BD para el Equipo que se esta registrando*/
	 function regEstadoAlerta($id_equipo){
		//Crear consluta para verificar si el material que se esta registrado genero una alerta
		$stm_sql = "SELECT * FROM alertas WHERE equipos_id_equipo = '$id_equipo' AND estado = 1";
		//Ejecutar la consulta
		$rs = mysql_query($stm_sql);
		//Evaluar los resultados y si se encuentra el material, cambiar el estado de la alerta de 1 a 2
		if($datos=mysql_fetch_array($rs)){
			mysql_query("UPDATE alertas SET estado = 2 WHERE equipos_id_equipo = '$id_equipo'");
		}
			
	}
	 
	 		
	/*Esta funcion genera la Clave de la orden de trabajo de acuerdo a los registros en la BD*/
	function obtenerIdOrdenTrabajo(){
		//Realizar la conexion a la BD de Mantenimiento
		$conn = conecta("bd_paileria");
		
		//Definir las tres letras en la Id de la Orden de Trabajo
		$id_cadena = "ORTG";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		//Obtener el mes actual y el año actual 
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener la Orden de Trabajo Reciente acorde a la fecha
		$stm_sql = "SELECT MAX(id_orden_trabajo) AS cant FROM orden_trabajo WHERE id_orden_trabajo LIKE 'ORTG$mes$anio%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			//Obtener las ultimas 3 cifras de la orden de trabajo registrada en la BD y sumarle 1
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
	}//Fin de la Funcion obtenerIdOrdenTrabajo()	
	
	//Esta funcion genera la Clave de la Bitacora de acuerdo a los registros en la BD
	function obtenerIdRegBitacora(){
		//Realizar la conexion a la BD de Mantenimiento
		$conn = conecta("bd_paileria");
		
		//Definir las tres letras la clave de la Bitacora
		$id_cadena = "BIT";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		//Obtener el mes actual y el año actual 
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener la Clave reciente acorde a la fecha
		$stm_sql = "SELECT MAX(id_bitacora) AS cant FROM bitacora_mtto WHERE id_bitacora LIKE 'BIT$mes$anio%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			//Obtener las ultimas 3 cifras de la Bitacora Registrado en la BD y sumarle 1
			$cant = substr($datos['cant'],-3)+1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}
			
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
		
		return $id_cadena;
	}//Fin de la Funcion obtenerId()	

	
	//Funcion que se encarga de mostrar las gamas que se van asociando a la Orden de Trabajo
	function gamasAgregadas($msg_tabla){
		echo "<table cellpadding='5' width='100%'>";
		echo "<caption class='titulo_etiqueta'>Gamas Agregada a la Orden de Trabajo: <u>".$_SESSION['datosOT']['orden_trabajo']."</u>
		<p class='msje_incorrecto'>$msg_tabla</p>
		</caption>";
		
		echo "      			
			<tr>
				<td class='nombres_columnas_gomar' align='center' width='10%'>ELIMINAR</td>
				<td class='nombres_columnas_gomar'>NO.</td>
        		<td class='nombres_columnas_gomar'>ID DE LA GAMA</td>
        		<td class='nombres_columnas_gomar'>NOMBRE DE LA GAMA</td>
      		</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;

		foreach ($_SESSION['gamasOT'] as $ind => $gama) {
			echo "<tr>
					<td class='nombres_filas_gomar' ><input type='radio' name='rdb_gama' value='$ind' /></td>
					<td class='$nom_clase' width='10%'>".($ind+1)."</td>";

			foreach ($gama as $key => $value) {
				switch($key){
					case "id_gama":
						echo "<td class='$nom_clase'>$value</td>";
					break;
					case "nom_gama":
						echo "<td class='$nom_clase'>$value</td>";
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
	}// Fin de la function gamasAgregadas(){
	
	
?>