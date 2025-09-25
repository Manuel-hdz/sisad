<?php
	/**
	  * Nombre del Módulo: Almacén                                               
	  * Nombre Programador: Miguel Angel Garay Castro                           
	  * Fecha: 18/Octubre/2010                                      			
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de Generar Orden de Compra
	  **/
	  	  	  	 
	
	//Esta función se encarga de generar el Id de la Orden de Compra de acuerdo a los registros existentes en la BD
	function obtenerIdOC(){
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_almacen");
		
		//Definir las dos letras en la Id de la Orden de Compra
		$id_cadena = "OC";
	
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		//Obtener el mes actual y el año actual para ser agregado en la consulta y asi obtener las Ordenes de Compra del mes en curso del año en curso
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		//Crear la sentencia para obtener el numero de OC registradas en la BD
		$stm_sql = "SELECT COUNT(id_orden_compra) AS cant FROM orden_compra WHERE id_orden_compra LIKE 'OC$mes$anio%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant = $datos['cant'] + 1;
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
	}//Fin de la Funcion obtenerIdOC() de la Orden de  Compra
	  
	  
	/*
	 * Agregar el registro de la Orden de Compra de Materiales a las tablas de detalle_oc y orden_compra y en el caso de existir nuevos materiales, 
	 * agregarlos al catalogo de Minera Fresnillo
	 */
	function guardarOrdenCompra($hdn_fecha,$txt_areaSolicitante,$txt_solicitanteOC){
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_almacen");					
		
		//Si la bandera se activa significa que hubo errores
		$band = 0;									
		
		//Registrar todos los materiales dados de alta en el arreglo $datosOC
		foreach ($_SESSION['datosOC'] as $ind => $material) {			
			//Crear la sentencia para realizar el registro de los datos del detalle de la Orden de Compra
			$stm_sql = "INSERT INTO detalle_oc (orden_compra_id_orden_compra, catalogo_mf_codigo_mf, cant_oc, descripcion)
			VALUES('$_SESSION[id_ordenOC]', '$material[clave]', $material[cantidad], '$material[descripcion]')";
			//Ejecutar la sentencia previamente creada para agregar cada material a la tabla de detalle_oc
			$rs = mysql_query($stm_sql);
			if(!$rs){
				$band = 1;				
			}
			else{
				//Si el registros del material en la requisición se llevo a cabo con exito, verificar si el material se encuentra registrado en las alertas y cambiar el estado de la misma
				regEstadoAlerta($material['clave']);
				//Guardar los materiales que no estan incluidos en el catalogo de Minera Fresnillo
				if($material['org']=="frm"){
					//Crear la sentencia para realizar el registro de los datos del detalle de la Entrada de Material
					$stm_sql = "INSERT INTO catalogo_mf (codigo_mf, descripcion) VALUES('$material[clave]', '$material[descripcion]')";
					//Ejecutar la sentencia previamente creada para agregar cada material nuevo al catalogo de Minera Fresnillo
					$rs = mysql_query($stm_sql);
					if(!$rs){
						$band = 1;						
					}
				}
			}					
		}
		
		//Pasar a Mayusculas los datos para Generar Orden de Compra
		$txt_areaSolicitante = strtoupper($txt_areaSolicitante); $txt_solicitanteOC = strtoupper($txt_solicitanteOC);
		
		if($band==0){																							
			//Crear la sentencia para almacenar los datos de la entrada en la BD
			$stm_sql = "INSERT INTO orden_compra (id_orden_compra, fecha_oc, a_solicitante_oc, solicitante_oc)
						VALUES('$_SESSION[id_ordenOC]','$hdn_fecha','$txt_areaSolicitante','$txt_solicitanteOC')";
			//Ejecutar la consulta
			$rs = mysql_query($stm_sql);			
			//Confirmar que la insercion de datos fue realizada con exito.
			if($rs){			
				//Registrar la Operacion en la Bitácora de Movimientos
				registrarOperacion("bd_almacen",$_SESSION['id_ordenOC'],"GenerarOrdenCompra",$_SESSION['usr_reg']);
				
				?>								
				<script type='text/javascript' language='javascript'>
					setTimeout("window.open('../../includes/generadorPDF/orden_compra.php?id=<?php echo $_SESSION['id_ordenOC']; ?>', '_blank','top=0, left=0, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')",4000);
				</script>
				<?php
				//Vaciar la información almacenada en la SESSION
				unset($_SESSION['datosOC']);
				unset($_SESSION['id_ordenOC']);
				echo "<meta http-equiv='refresh' content='5;url=exito.php'>";				
			}
			else{
				//Redireccionar a una pagina de error
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}
		}
		else{
			//Redireccionar a una pagina de error
			$error = "No se pudieron almacenar todos los registros del Detalle de la Orden de Compra";			
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
		
		//Cerrar la conexion con la BD		
		//La conexion a la BD se cierra en la funcion registrarOperacion("bd_almacen",$_SESSION['id_ordenOC'],"orden",$_SESSION['usr_reg']);
	}//Fin de la funcion guardarCambios($txt_proveedor,$txt_noRequisicion,$txt_noFactura,$txt_costo,$txt_fecha,$cmb_aceptado,$txa_comentarios)
	
	
	/* Esta funcion se encarga de verificar si alguno de los materiales registrados en la Orden de Compra tiene una alerta en la tabla de alertas y revisar el estado, 
	 * si el estado es 1, entonces cambiarlo a 2, que significa que el material ha sido requicitado y dejar de desplegar la alerta
	 */
	function regEstadoAlerta($id_material){
		//Crear consluta para verificar si el material que se esta registrado genero una alerta
		$stm_sql = "SELECT * FROM alertas WHERE materiales_id_material = '$id_material' AND estado = 1";
		//Ejecutar la consulta
		$rs = mysql_query($stm_sql);
		//Evaluar los resultados y si se encuentra el material, cambiar el estado de la alerta de 1 a 2
		if($datos=mysql_fetch_array($rs)){
			mysql_query("UPDATE alertas SET estado = 2 WHERE materiales_id_material = '$id_material'");
		}
			
	}
	
	//Desplegar los materiales agregados a la Orden de Compra
	function mostrarRegistros($datosOC){
		echo "				
		<table cellpadding='5' align='center'>      			
			<tr>
				<td width='80' class='nombres_columnas' align='center'>CLAVE</td>
        		<td width='180' class='nombres_columnas' align='center'>NOMBRE (DESCRIPCION)</td>
			    <td width='70' class='nombres_columnas' align='center'>CANT.</td>
				<td width='30' class='nombres_columnas' align='center'></td>
      		</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		foreach ($datosOC as $ind => $material) {
			echo "<tr>";
			foreach ($material as $key => $value) {
				switch($key){
					case "clave":
						echo "<td class='nombres_filas' align='center'>$value</td>";
					break;
					case "descripcion":
						echo "<td class='$nom_clase'>$value</td>";
					break;
					case "cantidad":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
				}				
			}
			//Colocar la Imagen para permitir la Edicion del registro seleccionado
			?><td class="<?php echo $nom_clase;?>">
				<input type="image" src="../../images/editar.png" width="30" height="25" border="0" title="Modificar Registro" 
				onclick="location.href='frm_editarRegistros.php?origen=orden compra&pos=<?php echo $cont-1; ?>'" />
			</td><?php
			
			
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
			echo "</tr>";			
		}
		echo "</table>";
	}//Fin de la funcion mostrarRegistros($datosReqisicion)
	
	
	//Esta función verifica que no se duplique un registro en el arreglo que guarda los datos del Detalle de la Orden de Compra
	function verRegDuplicado($arr,$campo_clave,$campo_ref){
		$tam = count($arr);		
		$datos = $arr[$tam-1];
		if($datos[$campo_clave]==$campo_ref)
			return true;
		else 
			return false;
	}
		
?>