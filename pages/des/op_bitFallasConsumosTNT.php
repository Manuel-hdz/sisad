<?php
	/**
	  * Nombre del Módulo: Desarrollo                                               
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 25/Octubre/2011
	  * Descripción: Este archivo contiene las funciones para realizar las operaciones de la Bitacora de Rezagado
	  **/ 
		
	/***********************************************************************************************************************************
	*************************************REGISTRAR BITACORA DE FALLAS, CONSUMOS Y EXPLOSIVOS********************************************
	************************************************************************************************************************************/  
	
	/*********************************************************BITACORA DE FALLAS********************************************************/
	//Esta funcion almacenara en la BD cada registro de falla que sea agregado por cada bitacora
	function guardarRegistroFalla(){
		
		//Conectar a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		//Recuperar datos del POST
		$idBitacora = $_POST['hdn_idBitacora'];
		$tipoBitacora = $_POST['hdn_tipoBitacora'];
		$tipoRegistro = $_POST['hdn_tipoRegistro'];
		$tipo = $_POST['cmb_tipo'];
		$descripcion = strtoupper($_POST['txa_observaciones']);
		$tiempo = $_POST['txt_tiempoHrs'];
		$equipo = $_POST['txt_equipo'];
		
		//Antes de realizar la Inserción de Datos obtener el numero de la última falla registrada
		$noFalla = 1;
		switch($tipoBitacora){
			case "bitAvance":
				//obtener el ultimo no. de falla registrada
				$datosFalla = mysql_fetch_array(mysql_query("SELECT MAX(no_falla) AS no_falla FROM bitacora_fallas WHERE bitacora_avance_id_bitacora = '$idBitacora'"));
				if($datosFalla['no_falla']!="")//Si el valor esta vacio significa que no hay fallas registradas y se deja el $noFalla con valor de 1
					$noFalla = intval($datosFalla['no_falla']) + 1;//Sumar 1 al ultimo registro de falla hecho
			break;
			case "bitRetroBull":
				//obtener el ultimo no. de falla registrada
				$datosFalla = mysql_fetch_array(mysql_query("SELECT MAX(no_falla) AS no_falla FROM bitacora_fallas WHERE bitacora_retro_bull_id_bitacora = '$idBitacora'"));
				if($datosFalla['no_falla']!="")//Si el valor esta vacio significa que no hay fallas registradas y se deja el $noFalla con valor de 1
					$noFalla = intval($datosFalla['no_falla']) + 1;//Sumar 1 al ultimo registro de falla hecho
			break;			
		}//Cierre switch($tipoBitacora)
		
				
		//Crear la Sentencia para agregar el registro a la BD segun la bitacora que esta siendo registrada
		$sql_stm = "";
		switch($tipoBitacora){
			case "bitAvance":
				$sql_stm = "INSERT INTO bitacora_fallas(bitacora_retro_bull_id_bitacora,bitacora_avance_id_bitacora,no_falla,descripcion,tiempo_gastado,tipo,equipo,tipo_registro)
							VALUES('N/A','$idBitacora',$noFalla,'$descripcion',$tiempo,'$tipo','$equipo','$tipoRegistro')";
			break;
			case "bitRetroBull":
				$sql_stm = "INSERT INTO bitacora_fallas(bitacora_retro_bull_id_bitacora,bitacora_avance_id_bitacora,no_falla,descripcion,tiempo_gastado,tipo,equipo,tipo_registro)
							VALUES('$idBitacora','N/A',$noFalla,'$descripcion',$tiempo,'$tipo','$equipo','$tipoRegistro')";
			break;			
		}
		
		//Ejecutar la Sentencia SQL
		$rs = mysql_query($sql_stm);
		
		//Verificar que no se haya presentado algun error
		if(!$rs)		
			return "***Error al Agregar Registro a la Bit&aacute;cora de Fallas";
			
		//Cerrar la conexion con la BD
		mysql_close($conn);
						
	}//Cierre de la funcion guardarRegistroFalla()
	
	
	//Esta función desplegará los registros de falla registrados en la BD
	function verRegistroFallas($idBitacora, $tipoBitacora, $tipoRegistro){
		//Conectarse a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		//Crear la sentencia para obtener los datos de la Bitacora de Fallas segun el tipo de bitacora que vaya a ser registrada
		$sql_stm = "";
		switch($tipoBitacora){
			case "bitAvance":
				$sql_stm = "SELECT * FROM bitacora_fallas WHERE bitacora_avance_id_bitacora = '$idBitacora' AND tipo_registro = '$tipoRegistro'";
			break;
			case "bitRetroBull":
				$sql_stm = "SELECT * FROM bitacora_fallas WHERE bitacora_retro_bull_id_bitacora = '$idBitacora' AND tipo_registro = '$tipoRegistro'";
			break;			
		}//Cierre switch($tipoBitacora)
		
		//Ejecutar la Sentencia
		$rs = mysql_query($sql_stm);
		
		//Colocar la Cantidad de registros en un campo oculto para luego notificar al usuario cuando cancela la operación
		$cantRegistros = mysql_num_rows($rs);?>
		<input type="hidden" name="hdn_cantRegistros" id="hdn_cantRegistros" value="<?php echo $cantRegistros; ?>" /><?php
		
		//Esta variable guardara el nombre del equipo del primer registro para ser colocado en una caja de texto oculta
		$nomEquipo = "";
		
		//Revisar que haya datos para mostrar
		if($datosFallas=mysql_fetch_array($rs)){
			//Guardar el Nombre del Equipo
			$nomEquipo = $datosFallas['equipo'];
						
			//Desplegar el encabezado de la pagina?>		
			<div id="ver-registrosFallas" align="center" class="borde_seccion2">
			<table width="100%" cellpadding="5">      			
				<tr>
					<td class="nombres_columnas" align="center">NO. FALLA</td>
					<td class="nombres_columnas" align="center">TIPO DE FALLA</td>
					<td class="nombres_columnas" align="center">DESCRIPCIÓN</td>
					<td class="nombres_columnas" align="center">TIEMPO GASTADO</td>
				</tr><?php							
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{?>
				<tr>
					<td class="nombres_filas" align="center"><?php echo $datosFallas['no_falla']; ?></td>
					<td class="<?php echo $nom_clase; ?>"><?php echo $datosFallas['tipo']; ?></td>
					<td class="<?php echo $nom_clase; ?>"><?php echo $datosFallas['descripcion']; ?></td>
					<td class="<?php echo $nom_clase; ?>"><?php echo $datosFallas['tiempo_gastado']; ?>&nbsp;Hrs.</td>
				</tr><?php
												
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";										
			}while($datosFallas=mysql_fetch_array($rs));?>
			</table>
			</div>
			
			<?php //Esta variable guarda el equipo que será mostrado en la Ventana donde se Registran las Fallas al Equipo ?>
			<input type="hidden" name="hdn_equipoRegBD" id="hdn_equipoRegBD" value="<?php echo $nomEquipo; ?>" /><?php
			 
			//Retornar 1 para indicar que si hubo datos para mostrar
			return 1;
		}//Cierre if($datosFallas=mysql_fetch_array($rs)) 
		else{
			 //Esta variable guarda el equipo que será mostrado en la Ventana donde se Registran las Fallas al Equipo ?>		
			<input type="hidden" name="hdn_equipoRegBD" id="hdn_equipoRegBD" value="<?php echo $nomEquipo; ?>" /><?php
			
			//Regresar 0 para indicar que no hay datos para mostrar
			return 0;
		}
		
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion verRegistroFallas($idBitacora, $tipoBitacora) 
	
	
	/*********************************************************BITACORA DE CONSUMOS********************************************************/
	/*Esta funcion guarda los consumos en la Bitacora de Consumos*/
	function guardarRegistroConsumo(){
				
		//Recuperar datos del POST
		$idBitacora = $_POST['hdn_idBitacora'];
		$tipoBitacora = $_POST['hdn_tipoBitacora'];
		$tipoRegistro = $_POST['hdn_tipoRegistro'];
		
		$material = ""; $unidadMedida = ""; $cantidad = 0;
		//Si el Checkbox de Nuevo Material esta seleccionado, obtener los datos de las Cajas de Texto
		if(isset($_POST['chk_nvoMaterial'])){			
			$material = strtoupper($_POST['txt_material']);
			$unidadMedida = strtoupper($_POST['txt_unidadMedida']);
			$cantidad = str_replace(",","",$_POST['txt_cant']);	
		}
		else{
			$idMaterial = $_POST['cmb_idMaterial'];
			$material = $_POST['hdn_nomMaterial'];		
			$cantidad = str_replace(",","",$_POST['txt_cantidad']);
			$unidadMedida = obtenerDato("bd_almacen", "unidad_medida", "unidad_medida", "materiales_id_material", $idMaterial);
		}
		
		
		//Conectar a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		
		//Antes de realizar la Inserción de Datos obtener el numero del último consumo registrado
		$noRegistro = 1;
		switch($tipoBitacora){
			case "bitAvance":
				//obtener el ultimo no. de consumo registrado
				$datosConsumos = mysql_fetch_array(mysql_query("SELECT MAX(no_registro) AS no_registro FROM consumos WHERE bitacora_avance_id_bitacora = '$idBitacora'"));
				if($datosConsumos['no_registro']!="")//Si el valor esta vacio significa que no hay consumos registrados y se deja el $noRegistro con valor de 1
					$noRegistro = intval($datosConsumos['no_registro']) + 1;//Sumar 1 al ultimo registro de consumos realizado
			break;
			case "bitRetroBull":
				//obtener el ultimo no. de consumo registrado
				$datosConsumos = mysql_fetch_array(mysql_query("SELECT MAX(no_registro) AS no_registro FROM consumos WHERE bitacora_retro_bull_id_bitacora = '$idBitacora'"));
				if($datosConsumos['no_registro']!="")//Si el valor esta vacio significa que no hay consumos registrados y se deja el $noRegistro con valor de 1
					$noRegistro = intval($datosConsumos['no_registro']) + 1;//Sumar 1 al ultimo registro de consumos realizado
			break;			
		}//Cierre switch($tipoBitacora)
		
		
		$sql_stm = "";							
		//Crear la consulta dependiendo de la bitacora (Avance o Retro Bull) que vaya a ser registrada
		switch($tipoBitacora){
			case "bitAvance":
				//Crear la Sentencia para agregar el registro a la BD de la Bitacora de Avance
				$sql_stm = "INSERT INTO consumos(bitacora_retro_bull_id_bitacora,bitacora_avance_id_bitacora,no_registro,nombre,unidad_medida,cantidad,tipo_registro)
							VALUES('N/A','$idBitacora',$noRegistro,'$material','$unidadMedida',$cantidad,'$tipoRegistro')";
			break;
			case "bitRetroBull":
				//Crear la Sentencia para agregar el registro a la BD de la Bitacora de Avance
				$sql_stm = "INSERT INTO consumos(bitacora_retro_bull_id_bitacora,bitacora_avance_id_bitacora,no_registro,nombre,unidad_medida,cantidad,tipo_registro)
							VALUES('$idBitacora','N/A',$noRegistro,'$material','$unidadMedida',$cantidad,'$tipoRegistro')";
			break;
		}
		
		
		$rs = mysql_query($sql_stm);
		
		if(!$rs)		
			return "***Error al Agregar Registro a la Bit&aacute;cora de Consumos";
			
		//Cerrar la conexion con la BD
		mysql_close($conn);

	}//Cierre de la funcion guardarRegistroConsumo()
	
	
	/*Esta funcion muestra los registros en la bitacora de consumos*/
	function verRegistroConsumos($idBitacora, $tipoBitacora, $tipoRegistro){
		//Conectarse a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		//Crear la sentencia para obtener los datos de la Bitacora de Consumos segun el tipo de bitacora que vaya a ser registrada
		$sql_stm = "";
		switch($tipoBitacora){
			case "bitAvance":
				$sql_stm = "SELECT * FROM consumos WHERE bitacora_avance_id_bitacora = '$idBitacora' AND tipo_registro = '$tipoRegistro'";
			break;
			case "bitRetroBull":
				$sql_stm = "SELECT * FROM consumos WHERE bitacora_retro_bull_id_bitacora = '$idBitacora' AND tipo_registro = '$tipoRegistro'";
			break;
		}//Cierre switch($tipoBitacora)
		
		//Ejecutar la Sentencia
		$rs = mysql_query($sql_stm);
		
		//Colocar la Cantidad de registros en un campo oculto para luego notificar al usuario cuando cancela el registro de Consumos
		$cantRegistros = mysql_num_rows($rs);?>
		<input type="hidden" name="hdn_cantRegistros" id="hdn_cantRegistros" value="<?php echo $cantRegistros; ?>" /><?php				
		
		//Revisar que haya datos para mostrar
		if($datosConsumos=mysql_fetch_array($rs)){			
						
			//Desplegar el encabezado de la pagina?>		
			<div id="ver-registrosConsumos" align="center" class="borde_seccion2">
			<table width="100%" cellpadding="5">      			
				<tr>
					<td class="nombres_columnas" align="center">CANTIDAD</td>
					<td class="nombres_columnas" align="center">UNIDAD DE MEDIDA</td>
					<td class="nombres_columnas" align="center">MATERIAL</td>
				</tr><?php							
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{?>
				<tr>
					<td class="nombres_filas"><?php echo number_format($datosConsumos['cantidad'],2,".",","); ?></td>
					<td class="<?php echo $nom_clase; ?>"><?php echo $datosConsumos['unidad_medida']; ?></td>
					<td class="<?php echo $nom_clase; ?>"><?php echo $datosConsumos['nombre']; ?></td>
				</tr><?php
												
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";										
			}while($datosConsumos=mysql_fetch_array($rs));?>
			</table>
			</div><?php
			 
			//Retornar 1 para indicar que si hubo datos para mostrar
			return 1;
		}//Cierre if($datosFallas=mysql_fetch_array($rs)) 
		else{			
			//Regresar 0 para indicar que no hay datos para mostrar
			return 0;
		}
		
		//Cerrar la conexion con la BD
		mysql_close($conn);
		
	}//Cierre de la funcion verRegistroConsumos($idBitacora, $tipoBitacora)
	
	
	/*********************************************************BITACORA DE EXPLOSIVOS********************************************************/
	/*Esta función guarda los registros de Explosivos Utilizados, relacionados con la Bitácora de Voladura*/
	function guardarRegistroExplosivos(){
		//Conectarse a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
				
		//Recuperar datos del POST
		$idBitacora = $_POST['hdn_idBitacora'];		
		$idExplosivo = $_POST['cmb_explosivo'];
		$cantidad = str_replace(",","",$_POST['txt_cantidad']);
		
		//Crear la Sentencia SQL
		$sql_stm = "INSERT INTO explosivos_empleados(bitacora_avance_id_bitacora,catalogo_explosivos_id_explosivos,cantidad) VALUES('$idBitacora',$idExplosivo,$cantidad)";
		//Ejecutar la Sentencia SQL
		$rs = mysql_query($sql_stm);
		
		if(!$rs)		
			return "***Error al Agregar Registro a la Bit&aacute;cora de Explosivos";
				
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la función guardarRegistroExplosivos()
	
	
	/*Ver los registros de explosivos hechos desde la Bitácora de Voladura*/
	function verRegistroExplosivos($idBitacora){
		//Conectarse a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		//Crear la Sentencia SQL para obtener los datos de los Explosivos Empleados
		$sql_stm = "SELECT nombre,medida,cantidad FROM catalogo_explosivos JOIN explosivos_empleados ON id_explosivos=catalogo_explosivos_id_explosivos 
		WHERE bitacora_avance_id_bitacora = '$idBitacora'";
		//Ejecutar la Sentencia
		$rs = mysql_query($sql_stm);
		
		//Colocar la Cantidad de registros en un campo oculto para luego notificar al usuario cuando decida cancelar la operación
		$cantRegistros = mysql_num_rows($rs);?>
		<input type="hidden" name="hdn_cantRegistros" id="hdn_cantRegistros" value="<?php echo $cantRegistros; ?>" /><?php				
		
		//Revisar que haya datos para mostrar
		if($datosExplosivos=mysql_fetch_array($rs)){			
						
			//Desplegar el encabezado de la pagina?>		
			<div id="ver-registrosExplosivos" align="center" class="borde_seccion2">
			<table width="100%" cellpadding="5">      			
				<tr>
					<td class="nombres_columnas" align="center">NO.</td>
					<td class="nombres_columnas" align="center">NOMBRE</td>
					<td class="nombres_columnas" align="center">MEDIDA</td>
					<td class="nombres_columnas" align="center">CANTIDAD</td>
				</tr><?php							
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{?>
				<tr>
					<td class="nombres_filas"><?php echo $cont; ?></td>
					<td class="<?php echo $nom_clase; ?>"><?php echo $datosExplosivos['nombre']; ?></td>
					<td class="<?php echo $nom_clase; ?>"><?php echo $datosExplosivos['medida']; ?></td>
					<td class="<?php echo $nom_clase; ?>"><?php echo number_format($datosExplosivos['cantidad'],2,".",","); ?></td>
				</tr><?php
												
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";										
			}while($datosExplosivos=mysql_fetch_array($rs));?>
			</table>
			</div><?php
			 
			//Retornar 1 para indicar que si hubo datos para mostrar
			return 1;
		}//Cierre if($datosExplosivos=mysql_fetch_array($rs)) 
		else{			
			//Regresar 0 para indicar que no hay datos para mostrar
			return 0;
		}
				
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la función verRegistroExplosivos($idBitacora)
	
	
	
	/***********************************************************************************************************************************
	*************************************MODIFICAR BITACORA DE FALLAS, CONSUMOS Y EXPLOSIVOS********************************************
	************************************************************************************************************************************/
	
	/*********************************************************BITACORA DE FALLAS********************************************************/
	/*Esta funcion desplegará los registros de Fallas existenctes y dara la opción de seleccionar registros para ser modificados*/
	function seleccionarRegistroFallas(){
		//Conectarse a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		//Recuperar datos del GET
		$idBitacora = $_GET['idBitacora'];
		$tipoBitacora = $_GET['tipoBitacora'];
		$tipoRegistro = $_GET['tipoRegistro'];
		
		
		//Crear la sentencia para obtener los datos de la Bitacora de Fallas segun el tipo de bitacora que vaya a ser registrada
		$sql_stm = "";
		switch($tipoBitacora){
			case "bitAvance":
				$sql_stm = "SELECT * FROM bitacora_fallas WHERE bitacora_avance_id_bitacora = '$idBitacora' AND tipo_registro = '$tipoRegistro'";
			break;
			case "bitRetroBull":
				$sql_stm = "SELECT * FROM bitacora_fallas WHERE bitacora_retro_bull_id_bitacora = '$idBitacora' AND tipo_registro = '$tipoRegistro'";
			break;			
		}//Cierre switch($tipoBitacora)
		
		//Ejecutar la Sentencia
		$rs = mysql_query($sql_stm);		
		
		//Esta variable guardara el nombre del equipo del primer registro para ser colocado en una caja de texto oculta
		$nomEquipo = "";
		
		//Revisar que haya datos para mostrar
		if($datosFallas=mysql_fetch_array($rs)){
			//Guardar el Nombre del Equipo
			$nomEquipo = $datosFallas['equipo'];
						
			//Desplegar el encabezado de la pagina ?>
			<table width="100%" cellpadding="5">      			
				<tr>
					<td class="nombres_columnas" align="center">SELECCIONAR</td>
					<td class="nombres_columnas" align="center">NO. FALLA</td>
					<td class="nombres_columnas" align="center">TIPO DE FALLA</td>
					<td class="nombres_columnas" align="center">DESCRIPCION</td>
					<td class="nombres_columnas" align="center">TIEMPO GASTADO</td>
				</tr><?php							
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{?>
				<tr>
					<td class="nombres_filas">
						<input type="radio" name="rdb_noFalla" id="rdb_noFalla" value="<?php echo $datosFallas['no_falla']; ?>" 
						onclick="cargarDatosRegitro(this.value,frm_regBitFallas.hdn_idBitacora.value,frm_regBitFallas.hdn_tipoBitacora.value,'fallas');" />
					</td>
					<td class="<?php echo $nom_clase; ?>"><?php echo $datosFallas['no_falla']; ?></td>
					<td class="<?php echo $nom_clase; ?>"><?php echo $datosFallas['tipo']; ?></td>
					<td class="<?php echo $nom_clase; ?>"><?php echo $datosFallas['descripcion']; ?></td>
					<td class="<?php echo $nom_clase; ?>"><?php echo $datosFallas['tiempo_gastado']; ?>&nbsp;Hrs.</td>
				</tr><?php
												
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";										
			}while($datosFallas=mysql_fetch_array($rs));?>
			</table>
			
			<?php //Esta variable se utiliza para colocar el nombre del Equipo en la Pantalla de Modificar Fallas ?>
			<input type="hidden" name="hdn_equipoRegBD" id="hdn_equipoRegBD" value="<?php echo $nomEquipo; ?>" /><?php
			 
			//Retornar 1 para indicar que si hubo datos para mostrar
			return 1;
		}//Cierre if($datosFallas=mysql_fetch_array($rs)) 
		else{
			//Esta variable se utiliza para colocar el nombre del Equipo en la Pantalla de Modificar Fallas ?>		
			<input type="hidden" name="hdn_equipoRegBD" id="hdn_equipoRegBD" value="<?php echo $nomEquipo; ?>" /><?php
			
			//Regresar 0 para indicar que no hay datos para mostrar
			return 0;
		}
		
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la función seleccionarRegistroFallas($idBitacora, $tipoBitacora)
	
	
	//Esta función modifica el registro de Falla seleccionado
	function modificarRegistroFalla(){

		//Conectar a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		//Recuperar datos del POST para crear la sentencia que actualizará los datos
		$idBitacora = $_POST['hdn_idBitacora'];
		$tipoBitacora = $_POST['hdn_tipoBitacora'];
		$tipoRegistro = $_POST['hdn_tipoRegistro'];
		$noFalla = $_POST['rdb_noFalla'];
		
		
		//Recuperar los datos del POST que serán actualizado en el registro de falla seleccionado
		$tipo = $_POST['cmb_tipo'];
		$descripcion = strtoupper($_POST['txa_observaciones']);
		$tiempo = $_POST['txt_tiempoHrs'];
		$equipo = $_POST['txt_equipo'];				
		
				
		//Crear la Sentencia para agregar el registro a la BD segun la bitacora que esta siendo registrada
		$sql_stm = "";
		switch($tipoBitacora){
			case "bitAvance":
				$sql_stm = "UPDATE bitacora_fallas SET descripcion = '$descripcion', tiempo_gastado = $tiempo, tipo = '$tipo', equipo = '$equipo'
							WHERE bitacora_avance_id_bitacora = '$idBitacora' AND tipo_registro = '$tipoRegistro' AND no_falla = $noFalla";
			break;
			case "bitRetroBull":
				$sql_stm = "UPDATE bitacora_fallas SET descripcion = '$descripcion', tiempo_gastado = $tiempo, tipo = '$tipo', equipo = '$equipo'
							WHERE bitacora_retro_bull_id_bitacora = '$idBitacora' AND tipo_registro = '$tipoRegistro' AND no_falla = $noFalla";
			break;			
		}
		
		//Ejecutar la Sentencia SQL
		$rs = mysql_query($sql_stm);
		
		//Verificar que no se haya presentado algun error
		if(!$rs)		
			return "***Error al Tratar de Modificar el Registro de Falla No. $noFalla";
			
		//Cerrar la conexion con la BD
		mysql_close($conn);		
		
	}//Cierre de la función modificarRegistroFalla()
	
	
	//Esta función borra el registro de Falla selecionado
	function borrarRegistroFalla(){
		//Conectar a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		//Recuperar datos necesarios para borrar el Registro del POST
		$idBitacora = $_POST['hdn_idBitacora'];
		$tipoBitacora = $_POST['hdn_tipoBitacora'];
		$tipoRegistro = $_POST['hdn_tipoRegistro'];
		$noFalla = $_POST['rdb_noFalla'];
		
		//Formular la Sentencia SQL para borrar el registro dependiendo de la Bitacora que esta siendo Modificda (Avance o Retro Bull)
		$sql_stm = "";
		switch($tipoBitacora){
			case "bitAvance":
				//Crear la Senencia SQL para borrar el No. de Falla cuando se trate de la Bitácora de Avance
				$sql_stm = "DELETE FROM bitacora_Fallas WHERE bitacora_avance_id_bitacora = '$idBitacora' AND tipo_registro = '$tipoRegistro' AND no_falla = $noFalla";				
			break;
			case "bitRetroBull":
				//Crear la Senencia SQL para borrar el No. de Falla cuando se trate de la Bitácora de Retro-Bull
				$sql_stm = "DELETE FROM bitacora_Fallas WHERE bitacora_retro_bull_id_bitacora = '$idBitacora' AND tipo_registro = '$tipoRegistro' AND no_falla = $noFalla";
			break;			
		}
										
		//Ejecutar la Sentencia SQL
		$rs = mysql_query($sql_stm);
		
		//Verificar que no se haya presentado algun error
		if(!$rs)		
			return "***Error al Intentar Borrar la Falla No. $noFalla";
		
				
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la Función borrarRegistroFalla()
	
	
	
	/*********************************************************BITACORA DE CONSUMOS********************************************************/
	//Esta función desplegará los registros de consumos y dará la opción de seleccionar un registro para ser modificado o borrado
	function seleccionarRegistroConsumos(){
		//Conectarse a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		//Recuperar datos del GET
		$idBitacora = $_GET['idBitacora'];
		$tipoBitacora = $_GET['tipoBitacora'];
		$tipoRegistro = $_GET['tipoRegistro'];
		
		
		//Crear la sentencia para obtener los datos de la Bitacora de Consumos segun el tipo de bitacora que vaya a ser registrada
		$sql_stm = "";
		switch($tipoBitacora){
			case "bitAvance":
				$sql_stm = "SELECT * FROM consumos WHERE bitacora_avance_id_bitacora = '$idBitacora' AND tipo_registro = '$tipoRegistro'";
			break;
			case "bitRetroBull":
				$sql_stm = "SELECT * FROM consumos WHERE bitacora_retro_bull_id_bitacora = '$idBitacora' AND tipo_registro = '$tipoRegistro'";
			break;			
		}//Cierre switch($tipoBitacora)
		
		//Ejecutar la Sentencia
		$rs = mysql_query($sql_stm);
			
				
		//Revisar que haya datos para mostrar
		if($datosConsumos=mysql_fetch_array($rs)){			
			
			//Desplegar el encabezado de la pagina ?>
			<table width="100%" cellpadding="5">      			
				<tr>
					<td class="nombres_columnas" align="center">SELECCIONAR</td>
					<td class="nombres_columnas" align="center">NO. REGISTRO</td>
					<td class="nombres_columnas" align="center">NOMBRE</td>
					<td class="nombres_columnas" align="center">UNIDAD</td>
					<td class="nombres_columnas" align="center">CANTIDAD</td>
				</tr><?php							
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{?>
				<tr>
					<td class="nombres_filas">
						<input type="radio" name="rdb_noRegistro" id="rdb_noRegistro" value="<?php echo $datosConsumos['no_registro']; ?>" 
						onclick="cargarDatosRegitro(this.value,frm_regBitConsumos.hdn_idBitacora.value,frm_regBitConsumos.hdn_tipoBitacora.value,'consumos');" />
					</td>
					<td class="<?php echo $nom_clase; ?>"><?php echo $datosConsumos['no_registro']; ?></td>
					<td class="<?php echo $nom_clase; ?>"><?php echo $datosConsumos['nombre']; ?></td>
					<td class="<?php echo $nom_clase; ?>"><?php echo $datosConsumos['unidad_medida']; ?></td>
					<td class="<?php echo $nom_clase; ?>"><?php echo number_format($datosConsumos['cantidad'],2,".",","); ?></td>
				</tr><?php
												
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";										
			}while($datosConsumos=mysql_fetch_array($rs));?>
			</table><?php
			 
			//Retornar 1 para indicar que si hubo datos para mostrar
			return 1;
		}//Cierre if($datosConsumos=mysql_fetch_array($rs)) 
		else{
			//Regresar 0 para indicar que no hay datos para mostrar
			return 0;
		}
		
		//Cerrar la conexion con la BD
		mysql_close($conn);		
	}//Cierre de la función seleccionarRegistroConsumos()
	
	
	//Esta función modifica el registro de consumo seleccionado
	function modificarRegistroConsumo(){

		//Recuperar datos del POST
		$idBitacora = $_POST['hdn_idBitacora'];
		$tipoBitacora = $_POST['hdn_tipoBitacora'];
		$tipoRegistro = $_POST['hdn_tipoRegistro'];
		$noRegistro = $_POST['rdb_noRegistro'];
		
		//Recuperar el Dato que será actualizado
		$cantidad = str_replace(",","",$_POST['txt_cantidad']);
		
		//Conectar a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
				
				
		//Crear la consulta dependiendo de la bitacora (Avance o Retro Bull) que vaya a ser registrada
		$sql_stm = "";											
		switch($tipoBitacora){
			case "bitAvance":
				//Crear la Sentencia para agregar el registro a la BD de la Bitacora de Avance
				$sql_stm = "UPDATE consumos SET cantidad = $cantidad WHERE bitacora_avance_id_bitacora = '$idBitacora' AND tipo_registro = '$tipoRegistro'
							AND no_registro = $noRegistro";
			break;
			case "bitRetroBull":
				//Crear la Sentencia para agregar el registro a la BD de la Bitacora de Avance
				$sql_stm = "UPDATE consumos SET cantidad = $cantidad WHERE bitacora_retro_bull_id_bitacora = '$idBitacora' AND tipo_registro = '$tipoRegistro' 
							AND no_registro = $noRegistro";
			break;
		}
		
		
		$rs = mysql_query($sql_stm);
		
		if(!$rs)		
			return "***Error al Tratar de Modificar el Registro de la Bit&aacute;cora de Consumos No. $noRegistro";
			
		//Cerrar la conexion con la BD
		mysql_close($conn);
			
	}//Cierre de la función modificarRegistroConsumo()
	
	
	//Esta función borra el registro de consumo seleccionado
	function borrarRegistroConsumo(){
		//Conectar a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		//Recuperar datos necesarios para borrar el Registro del POST
		$idBitacora = $_POST['hdn_idBitacora'];
		$tipoBitacora = $_POST['hdn_tipoBitacora'];
		$tipoRegistro = $_POST['hdn_tipoRegistro'];
		$noRegistro = $_POST['rdb_noRegistro'];
		
		//Formular la Sentencia SQL para borrar el registro dependiendo de la Bitacora que esta siendo Modificada (Avance o Retro Bull)
		$sql_stm = "";
		switch($tipoBitacora){
			case "bitAvance":
				//Crear la Senencia SQL para borrar el No. de Falla cuando se trate de la Bitácora de Avance
				$sql_stm = "DELETE FROM consumos WHERE bitacora_avance_id_bitacora = '$idBitacora' AND tipo_registro = '$tipoRegistro' AND no_registro = $noRegistro";				
			break;
			case "bitRetroBull":
				//Crear la Senencia SQL para borrar el No. de Falla cuando se trate de la Bitácora de Retro-Bull
				$sql_stm = "DELETE FROM consumos WHERE bitacora_retro_bull_id_bitacora = '$idBitacora' AND tipo_registro = '$tipoRegistro' AND no_registro = $noRegistro";
			break;			
		}
										
		//Ejecutar la Sentencia SQL
		$rs = mysql_query($sql_stm);
		
		//Verificar que no se haya presentado algun error
		if(!$rs)		
			return "***Error al Intentar Borrar el Consumo No. $noRegistro";
		
				
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la función borrarRegistroConsumo()
	
	
	/*********************************************************BITACORA DE EXPLOSIVOS********************************************************/
	//Esta función desplegará los registros de explosivos y dará la opción de seleccionar un registro para ser modificado o borrado
	function seleccionarRegistroExplosivos(){
		//Conectarse a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		//Recuperar datos del GET
		$idBitacora = $_GET['idBitacora'];
		
		
		//Crear la sentencia para obtener los datos de la Bitacora de Explosivos
		$sql_stm = "SELECT * FROM explosivos_empleados WHERE bitacora_avance_id_bitacora = '$idBitacora' ORDER BY catalogo_explosivos_id_explosivos";
		
		//Ejecutar la Sentencia
		$rs = mysql_query($sql_stm);				
		
		//Arreglo que contendra las Categorias de los explosivos
		$categorias = array(1=>"DISPARO EN TOPE SIN AGUA",2=>"DISPARO EN TOPE CON AGUA",3=>"AMBOS");
				
		//Revisar que haya datos para mostrar
		if($datosExplosivos=mysql_fetch_array($rs)){			
			
			//Desplegar el encabezado de la pagina ?>
			<table width="100%" cellpadding="5">      			
				<tr>
					<td class="nombres_columnas" align="center">SELECCIONAR</td>
					<td class="nombres_columnas" align="center">CLAVE</td>
					<td class="nombres_columnas" align="center">NOMBRE</td>
					<td class="nombres_columnas" align="center">CATEGORIA</td>
					<td class="nombres_columnas" align="center">MEDIDA</td>
					<td class="nombres_columnas" align="center">CANTIDAD</td>
				</tr><?php							
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{
				//Obtener los datos del Explosivo
				$datosExp = mysql_fetch_array(mysql_query("SELECT * FROM catalogo_explosivos WHERE id_explosivos = $datosExplosivos[catalogo_explosivos_id_explosivos]")); ?>
				<tr>
					<td class="nombres_filas">
						<input type="radio" name="rdb_noRegistro" id="rdb_noRegistro" value="<?php echo $datosExplosivos['catalogo_explosivos_id_explosivos']; ?>" 
						onclick="cargarDatosRegitro(this.value,frm_regBitExplosivos.hdn_idBitacora.value,'','explosivos');" />
					</td>
					<td class="<?php echo $nom_clase; ?>"><?php echo $datosExplosivos['catalogo_explosivos_id_explosivos']; ?></td>
					<td class="<?php echo $nom_clase; ?>"><?php echo $datosExp['nombre']; ?></td>
					<td class="<?php echo $nom_clase; ?>"><?php echo $categorias[$datosExp['categoria']]; ?></td>
					<td class="<?php echo $nom_clase; ?>"><?php echo $datosExp['medida']; ?></td>
					<td class="<?php echo $nom_clase; ?>"><?php echo number_format($datosExplosivos['cantidad'],2,".",","); ?></td>
				</tr><?php
												
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";										
			}while($datosExplosivos=mysql_fetch_array($rs));?>
			</table><?php
			 
			//Retornar 1 para indicar que si hubo datos para mostrar
			return 1;
		}//Cierre if($datosConsumos=mysql_fetch_array($rs)) 
		else{
			//Regresar 0 para indicar que no hay datos para mostrar
			return 0;
		}
		
		//Cerrar la conexion con la BD
		mysql_close($conn);			
								
	}//Cierre de la función seleccionarRegistroExplosivos()
	
	//Esta función modifica el registro de explosivo seleccionado
	function modificarRegistroExplosivo(){	

		//Recuperar datos del POST
		$idBitacora = $_POST['hdn_idBitacora'];
		$idExplosivo = $_POST['rdb_noRegistro'];		
		//Recuperar el Dato que será actualizado
		$cantidad = str_replace(",","",$_POST['txt_cantidad']);
		
		//Conectar a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
				
				
		//Crear la consulta dependiendo de la bitacora (Avance o Retro Bull) que vaya a ser registrada
		$sql_stm = "UPDATE explosivos_empleados SET cantidad = $cantidad WHERE bitacora_avance_id_bitacora = '$idBitacora' AND catalogo_explosivos_id_explosivos = '$idExplosivo'";											
		
		$rs = mysql_query($sql_stm);
		
		if(!$rs)		
			return "***Error al Tratar de Modificar el Registro de la Bit&aacute;cora de Explosivos No. $idExplosivo";
			
		//Cerrar la conexion con la BD
		mysql_close($conn);	
	
	}//Cierre de la función modificarRegistroExplosivo()
	
	
	//Esta función borra el registro de explosivo seleccionado
	function borrarRegistroExplosivo(){
		//Conectar a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		//Recuperar datos necesarios para borrar el Registro del POST
		$idBitacora = $_POST['hdn_idBitacora'];
		$idExplosivo = $_POST['rdb_noRegistro'];
		
		//Formular la Sentencia SQL para borrar el registro dependiendo de la Bitacora que esta siendo Modificada (Avance o Retro Bull)
		$sql_stm = "DELETE FROM explosivos_empleados WHERE bitacora_avance_id_bitacora = '$idBitacora' AND catalogo_explosivos_id_explosivos = '$idExplosivo'";

										
		//Ejecutar la Sentencia SQL
		$rs = mysql_query($sql_stm);
		
		//Verificar que no se haya presentado algun error
		if(!$rs)		
			return "***Error al Intentar Borrar el Explosivo con Clave No. $idExplosivo";
		
				
		//Cerrar la conexion con la BD
		mysql_close($conn);	
	}//Cierre de la función borrarRegistroExplosivo()
	
	
?>