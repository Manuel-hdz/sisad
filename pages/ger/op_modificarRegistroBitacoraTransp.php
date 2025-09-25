<?php
	/**
	  * Nombre del Módulo: Gerencia Técnica
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 12/Agosto/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de Modificar Registro Bitacora Trasporte
	**/

	 //Funcion para mostrar los registros de la bitacora consultada
	function mostrarRegBitacoraTrans(){

		//Conectar a la BD de gerencia
		$conn = conecta("bd_gerencia");
		
		//Verificar si el periodo proviene del post que es en el primer caso o en el get que es cuando se regresa desde la pagina de agregar bitacra
		if(isset($_GET['cmb_ubicacion']) || isset($_GET['txt_fecha'])){
			$ubicacion= $_GET['cmb_ubicacion'];
			$fecha= modFecha($_GET['txt_fecha'],3);
		}
		else{
			$ubicacion= $_POST['cmb_ubicacion'];
			$fecha= modFecha($_POST['txt_fecha'],3);
		}		
		
		//Obtener a partir del id del destino proporcionado para poder realiza la consulta
		$NomUbicacion= obtenerDato("bd_gerencia","catalogo_ubicaciones","ubicacion","id_ubicacion",$ubicacion);
		
		//Crear sentencia SQL para obtener la informacion de las tablas de bitacora y bitacora_zarpeo
		$sql_stm_Trans ="SELECT * FROM bitacora_transporte WHERE fecha='$fecha' AND destino='$NomUbicacion' ORDER BY fecha";
			
		//Crear el mensaje que se mostrara en el titulo de la tabla
		$msg= "Registros de fecha<em><u>  $fecha </u></em> en <em><u>  $NomUbicacion </u></em> ";
		
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Registros  en la fecha <em><u>  
		$fecha </u></em> en <em><u>  $NomUbicacion </u></em>";
		
		//Ejecutar la sentencia previamente creada
		$rsTransp = mysql_query($sql_stm_Trans);
		
		//Verificar si arroja resultado la consulta
		$datosTransp=mysql_num_rows($rsTransp);
	
		//verificar si las consultas arrojaron resultados 
		if($datosTransp>0){
	
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='140%'>				
				<tr>
					<td colspan='8' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center'>SELECCIONAR</td>
					<td class='nombres_columnas' align='center'>FECHA</td>
					<td class='nombres_columnas' align='center'>CHOFER</td>
					<td class='nombres_columnas' align='center'>PUESTO</td>
					<td class='nombres_columnas' align='center'>DESTINO</td>
					<td class='nombres_columnas' align='center'>CANTIDAD</td>
					<td class='nombres_columnas' align='center'>COMENTARIOS</td>
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;	
			
				
			//Verificar si la consulta de la bitacora de zarpeo arrojo resultados 			
			if($datosTransp=mysql_fetch_array($rsTransp)){

				do{	
					//Mostrar todos los registros que han sido completados
					echo "
						<tr>
							<td class='nombres_filas' width='5%' align='center'>
								<input type='radio' name='rdb_idBitacora' value='$datosTransp[id_bitacora_transporte]'>
							</td>
							<td class='$nom_clase' align='center'>".modFecha($datosTransp['fecha'],1)."</td>
							<td class='$nom_clase' align='center'>$datosTransp[nombre]</td>
							<td class='$nom_clase' align='center'>$datosTransp[puesto]</td>
							<td class='$nom_clase' align='center'>$datosTransp[destino]</td>
							<td class='$nom_clase' align='center'>".number_format($datosTransp['cantidad'],2,".",",")."</td>
							<td class='$nom_clase' align='center'>$datosTransp[comentarios]</td>
						</tr>";
						
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";				
				}while($datosTransp=mysql_fetch_array($rsTransp));
			}//FIN if($datosTransp=mysql_fetch_array($sql_stm_Trans)){
				
			//Fin de la tabla donde se muestran los resultados de ambas consultas
			echo "</table>";
			return 1;
		}//FIN if( $datosTransp>0)
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;		
			return 0;		
		}
	}
	
	//Funcion que se encarga de eliminar el Registro seleccionado
	function eliminarRegSeleccionado(){
		
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_gerencia");
		
		//Recuperar  valor del $_POST['rdb_idBitacora'] 
		$idBitacora=$_POST['rdb_idBitacora'];

		//Creamos la sentencia SQL para borrar el registro seleccionado de a bitacora de Transporte
		$stm_sql="DELETE FROM  bitacora_transporte WHERE id_bitacora_transporte = '$idBitacora'";

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);									
									
		//Verificar si la sentencia ejecutada se genero con exito
		if ($rs){
			registrarOperacion("bd_gerencia",$idBitacora,"ElimRegBitacoraTransp",$_SESSION['usr_reg']);
			$conn = conecta("bd_gerencia");
		}
		else{
			$error=mysql_error();
			//Cerrar la conexion con la BD de gerencia
			mysql_close($conn);
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}//Fin de la funcion eliminarBonoSeleccionado
	
?>