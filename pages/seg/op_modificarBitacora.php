<?php
	/**
	  * Nombre del Módulo: Seguridad
	  * Nombre Programador: Daisy Adriana Martínez Fernández
	  * Fecha: 16/Enero/2012
	  * Descripción: Este archivo contiene funciones para modificar la información relacionada con el registro de la bitacora de residuos
	**/
	//Verificamos si fue presionado el boton guardar
	if(isset($_POST['sbt_modificar'])){
		modificarBitacora();
	}
		
	//Función que permite mostrar los documentos Registrados en las fechas especificadas
	function mostrarRegistros(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Recuperamos los datos del POST
		$tipoResiduo = $_POST['cmb_residuo'];
		
		//Modificamos las fechas para el uso con la sentencia SQL
		$fechaIni = modFecha($_POST['txt_fechaIni'],3);
		$fechaFin = modFecha($_POST['txt_fechaFin'],3);
		
		//Verificamos el residuo para ver la unidad
		if(isset($_POST['cmb_residuo'])&&$_POST['cmb_residuo']=="ACEITE"){
			$unidad = "Lts";
		}
		else{
			$unidad = "Kgs";
		}
		
		//Realizar la conexion a la BD de Seguridad
		$conn = conecta("bd_seguridad");
		
		//Creamos la sentencia SQL
		$stm_sql ="SELECT * FROM bitacora_residuos WHERE tipo_residuo = '$tipoResiduo' AND fecha_ingreso>='$fechaIni' 
				 		AND fecha_ingreso<='$fechaFin'ORDER BY id_bitacora_residuos";
					
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'>REGISTROS DE LA BIT&Aacute;CORA PARA EL RESIDUO <strong>$tipoResiduo</strong> DE <em>".$_POST['txt_fechaIni']."</em> A <em>".$_POST['txt_fechaFin']."</caption></br>";
			echo "	<tr>
						<td class='nombres_columnas' align='center'>MODIFICAR</td>
						<td class='nombres_columnas' align='center'>NO.</td>
						<td class='nombres_columnas' align='center'>&Aacute;REA</td>
						<td class='nombres_columnas' align='center'>CLASIFICACI&Oacute;N SOLIDO</td>
						<td class='nombres_columnas' align='center'>CANTIDAD GENERADA</td>
						<td class='nombres_columnas' align='center'>EQUIVALENCIA($unidad)</td>
						<td class='nombres_columnas' align='center'>NOMBRE RECIBE</td>
						<td class='nombres_columnas' align='center'>FECHA INGRESO</td>
						<td class='nombres_columnas' align='center'>FECHA SALIDA</td>
						<td class='nombres_columnas' align='center'>RAZ&Oacute;N SOCIAL</td>
						<td class='nombres_columnas' align='center'>NO. MANIFIESTO</th>
						<td class='nombres_columnas' align='center'>NO. AUTORIZACI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>NOMBRE TRANSPORTISTA</td>
						<td class='nombres_columnas' align='center'>C</td>
						<td class='nombres_columnas' align='center'>R</td>
						<td class='nombres_columnas' align='center'>E</td>
						<td class='nombres_columnas' align='center'>T</td>
						<td class='nombres_columnas' align='center'>I</td>
						<td class='nombres_columnas' align='center'>FASE SALIDA</td>
						<td class='nombres_columnas' align='center'>RESPONSABLE BIT&Aacute;CORA</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{				
				echo "	<tr>	
							<td class='$nom_clase' align='center'>";?>
								<input type="radio" id="rdb_id" name="rdb_id" value="<?php echo $datos['id_bitacora_residuos'];?>" onclick="hdn_btn.value='radio';		cambiarSubmit();"/><?php
				echo "</td>
							<td class='$nom_clase' align='center'>$cont</td>
							<td class='$nom_clase' align='center'>$datos[area]</td>	
							<td class='$nom_clase' align='center'>$datos[clasificacion_solido]</td>	
							<td class='$nom_clase' align='center'>$datos[cantidad]</td>
							<td class='$nom_clase' align='center'>$datos[tipo_unidad]</td>
							<td class='$nom_clase' align='center'>$datos[nom_firm_recibe]</td>						
							<td class='$nom_clase' align='left'>".modFecha($datos['fecha_ingreso'],1)."</td>
							<td class='$nom_clase' align='left'>".modFecha($datos['fecha_salida'],1)."</td>
							<td class='$nom_clase' align='center'>$datos[razon_social]</td>	
							<td class='$nom_clase' align='center'>$datos[num_manifiesto]</td>
							<td class='$nom_clase' align='center'>$datos[num_autorizacion]</td>
							<td class='$nom_clase' align='center'>$datos[nom_transportista]</td>";
							if($datos['pel_corrosivo']=='1'){
								echo"<td class='$nom_clase' align='center'> <label  class='msje_correcto'><strong>&radic;</strong></label></td>";
							}
							else{
								echo"<td class='$nom_clase' align='center'> <label  class='msje_incorrecto'><strong>X</strong></label></td>";
							}
							if($datos['pel_reactivo']=='1'){
								echo"<td class='$nom_clase' align='center'> <label  class='msje_correcto'><strong>&radic;</strong></label></td>";
							}
							else{
								echo"<td class='$nom_clase' align='center'> <label  class='msje_incorrecto'><strong>X</strong></label></td>";
							}
							if($datos['pel_explosivo']=='1'){
								echo"<td class='$nom_clase' align='center'> <label  class='msje_correcto'><strong>&radic;</strong></label></td>";
							}
							else{
								echo"<td class='$nom_clase' align='center'> <label  class='msje_incorrecto'><strong>X</strong></label></td>";
							}
							if($datos['pel_toxico']=='1'){
								echo"<td class='$nom_clase' align='center'> <label  class='msje_correcto'><strong>&radic;</strong></label></td>";
							}
							else{
								echo"<td class='$nom_clase' align='center'> <label  class='msje_incorrecto'><strong>X</strong></label></td>";
							}
							if($datos['pel_inflamable']=='1'){
								echo"<td class='$nom_clase' align='center'> <label  class='msje_correcto'><strong>&radic;</strong></label></td>";
							}
							else{
								echo"<td class='$nom_clase' align='center'> <label  class='msje_incorrecto'><strong>X</strong></label></td>";
							}
						echo "<td class='$nom_clase' align='center'>$datos[fase_salida]</td>					
							<td class='$nom_clase' align='left'>$datos[responsable_bit]</td>";
							?>
					<?php
						echo "</tr>";			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";	
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</table>";
			if($_SESSION["depto"]=="Seguridad")
				$area="SEGURIDAD INDUSTRIAL";
				else
			$area="SEGURIDAD AMBIENTAL";
			//Extraer el RFC del encargado de departamento
			$usuario=obtenerDato("bd_recursos","organigrama","empleados_rfc_empleado","departamento",$area);
			//Con el RFC, traer el nombre completo del encargado del depto
			$usuario=obtenerNombreEmpleado($usuario);?>
			<input type='hidden' name="hdn_consulta" id="hdn_consulta" value="<?php echo $stm_sql;?>"/>
			<input type='hidden' name="hdn_tipoResiduo" id="hdn_tipoResiduo" value="<?php echo $_POST['cmb_residuo'];?>"/>
			<input type='hidden' name="hdn_tipoReporte" id="hdn_tipoReporte" value="reporteBitacoraResiduos"/>
			<input type='hidden' name="hdn_nomReporte" id="hdn_nomReporte" 
			value="ReporteBitacoraResiduos_<?php echo $tipoResiduo;?>_de_<?php echo $fechaIni;?>_a_<?php echo $fechaFin;?>"/>
			<input type='hidden' name="hdn_msg" id="hdn_msg" value="REGISTROS DE LA BIT&Aacute;CORA PARA EL RESIDUO <strong><?php echo $tipoResiduo;?></strong> DE <em> <?php echo $_POST['txt_fechaIni']?> </em> A <em> <?php echo $_POST['txt_fechaFin'];?>"/>
			<input type="hidden" name="hdn_nombre" id="hdn_nombre" value="<?php echo $usuario;?>"/>
			<?php 
			return 1;
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<p class='msje_correcto' align='center'></br></br></br></br>No existen Registros de la Bit&aacute;cora Para El Residuo <strong>$tipoResiduo</strong> de <em>".$_POST['txt_fechaIni']."</em> a <em>".$_POST['txt_fechaFin']."</em></p>";
			return 0;
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion
	
	 //Funcion para guardar la informacion de la Bitacora 
	function modificarBitacora(){
		//Iniciamos la sesion
		session_start();
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");

		//Conectar se a la Base de Datos
		$conn = conecta("bd_seguridad");
		
		//Recuperar la informacion del post		
		$residuo = strtoupper($_POST['cmb_residuo']);
		$clasificacion = strtoupper($_POST['txt_clasificacionSol']);
		$area = strtoupper($_POST['txt_area']);
		$cantidad = strtoupper($_POST['txt_cantGenerada']);
		$unidad = str_replace(",","",$_POST['txt_unidad']);
		$nomEntrega = strtoupper($_POST['txt_nomEntrega']);
		$nomRecibe = strtoupper($_POST['txt_nomRecibe']);
		$fechaIngreso = modFecha($_POST['txt_fechaIng'],3);
		$fechaSalida = modFecha($_POST['txt_fechaSal'],3);
		$razSocial = strtoupper($_POST['txt_razSocial']);
		$numMan = $_POST['txt_numManifiesto'];
		$numAut =strtoupper($_POST['txt_numAutorizacion']);
		$desBit = strtoupper($_POST['txa_descripcion']);
		$resBit = strtoupper($_POST['txt_responsableBit']);
		$nomTrans = strtoupper($_POST['txt_nomTransportista']);
		$clave = $_POST['txt_claveBitacora'];
		
		//Revisamos los checkBox pra verificar cuales fueron seleccionados
		if(isset($_POST['ckb_peligrosidadC']))
		 	$ckb_peligrosidadC = 1;
		else
			$ckb_peligrosidadC = 0;
		if(isset($_POST['ckb_peligrosidadR']))
		 	$ckb_peligrosidadR = 1;
		else
			$ckb_peligrosidadR = 0;
		if(isset($_POST['ckb_peligrosidadE']))
		 	$ckb_peligrosidadE = 1;
		else
			$ckb_peligrosidadE = 0;
		if(isset($_POST['ckb_peligrosidadT']))
		 	$ckb_peligrosidadT = 1;
		else
			$ckb_peligrosidadT = 0;
    	if(isset($_POST['ckb_peligrosidadI']))
		 	$ckb_peligrosidadI = 1;
		else
			$ckb_peligrosidadI = 0;
    
 		//Recuperamos el id original del registro; en caso de que este haya sido modificado
		$claveOriginal = $_POST['hdn_original'];
	
		//Crear la Sentencia SQL para Alamcenar los materiales agregados 
		$stm_sql= "UPDATE bitacora_residuos SET id_bitacora_residuos='$clave', tipo_residuo='$residuo', clasificacion_solido='$clasificacion', 
				   nom_firm_entrega='$nomEntrega', nom_firm_recibe='$nomRecibe', fecha_ingreso='$fechaIngreso', fecha_salida='$fechaSalida',
				   razon_social='$razSocial', num_manifiesto='$numMan', num_autorizacion='$numAut', nom_transportista='$nomTrans', pel_corrosivo='$ckb_peligrosidadC',
				   pel_reactivo='$ckb_peligrosidadR', pel_explosivo='$ckb_peligrosidadE', pel_toxico='$ckb_peligrosidadT', pel_inflamable='$ckb_peligrosidadI',
				   fase_salida='$desBit', area='$area', cantidad='$cantidad', tipo_unidad='$unidad', responsable_bit='$resBit'
				   WHERE id_bitacora_residuos='$claveOriginal'";
		
		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);
		//Verificar Resultado
		if ($rs){
			//Guardar la operacion realizada
			registrarOperacion("bd_seguridad",$clave,"ModificarBitacora",$_SESSION['usr_reg']);
			$conn = conecta("bd_seguridad");
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	 }// Fin function registrarBitacora()	
?>