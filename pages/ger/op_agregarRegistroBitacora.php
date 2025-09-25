<?php
	function mostrarCuadrilla(){
		$id_cuad = $_SESSION['infoBitacora']['idUbicacion'];
		$id_presupuesto = $_SESSION['infoBitacora']['periodo'];
		
		$vol_diario = obtenerDatosTabla("presupuesto","vol_ppto_dia","id_presupuesto",$id_presupuesto,"bd_gerencia");
		?>
		<table width="100%"  cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td class='nombres_columnas' align='center'>EMPLEADO</td>
        		<td class='nombres_columnas' align='center'>PUESTO</td>
				<td class='nombres_columnas' align='center'>AVANCE</td>
			    <td class='nombres_columnas' align='center'>COMENTARIOS</td>
			    <td class='nombres_columnas' align='center'>TIPO</td>
      		</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			$total = 0;
			$id_cuadrilla = $_POST["cmb_cuadrillas"];
			
			$con = conecta("bd_gerencia");
			
			$stm_sql = "SELECT * 
						FROM  `integrantes_cuadrilla` 
						WHERE  `id_cuadrilla` LIKE  '$id_cuadrilla'";
			$rs = mysql_query($stm_sql);
			if($rs){
				while($datos = mysql_fetch_array($rs)){
					echo "
					<tr>
						<td class='$nom_clase' align='center'>";
							?>
							<input type="text" name="txt_integrante<?php echo $cont; ?>" id="txt_integrante<?php echo $cont; ?>" class="caja_de_texto" required="required" size="40" autocomplete="off"
							value="<?php echo $datos['nombre_emp']; ?>" onkeyup="lookup(this,'empleados','<?php echo $cont; ?>','hdn_rfc<?php echo $cont; ?>','<?php echo $id_cuad; ?>');"/>
							<div id="res-spider<?php echo $cont; ?>" style="position:absolute; z-index:19;">
								<div align="left" class="suggestionsBox" id="suggestions<?php echo $cont; ?>" style="display: none;">
									<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
									<div class="suggestionList" id="autoSuggestionsList<?php echo $cont; ?>">&nbsp;</div>
								</div>
							</div>
							<input type="hidden" name="hdn_rfc<?php echo $cont; ?>" id="hdn_rfc<?php echo $cont; ?>" value="<?php echo $datos["rfc_trabajador"]; ?>"/>
						<?php
					echo "	
						</td>
						<td class='$nom_clase' align='center'>
							<input type='text' name='txt_puesto$cont' id='txt_puesto$cont' class='caja_de_texto' value='$datos[puesto]' readonly='readonly' size='10'/>
						</td>
						<td class='$nom_clase' align='center'>";
							?>
							<input name="txt_cantidad<?php echo $cont; ?>" id="txt_cantidad<?php echo $cont; ?>" type="text" class="caja_de_num" maxlength="6" placeholder="<?php echo $vol_diario; ?>"
							onkeypress="return permite(event,'num', 0);" size="7" onchange="formatCurrency(this.value,'txt_cantidad<?php echo $cont; ?>');" required="required" autocomplete="off"/>
							<?php
						echo "
						</td>
						<td class='$nom_clase' align='center'>
							<textarea name='txa_comentarios$cont' cols='50' rows='3' class='caja_de_texto' id='txa_comentarios$cont' maxlength='120' style='resize: none;' placeholder='INTRODUCIR AQUI COMENTARIOS'></textarea>
						</td>
						<td class='$nom_clase' align='center'>
							<input type='radio' name='chktipo$cont' id='chktipo$cont' value='ZARPEO' checked='checked'/>ZARPEO
							<input type='radio' name='chktipo$cont' id='chktipo$cont' value='PISO'/>PISO
						</td>
					</tr>
					";
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
				}
			}
			mysql_close($con);
			?>
		</table>
		<?php
		$num_reg = $cont - 1;
		return $num_reg;
	}
	
	function obtenerDatosTabla($tabla,$retorno,$comp,$valor,$bd){
		$dato = "";
		$conec = conecta($bd);
		$stm_sql = "SELECT  `$retorno` 
					FROM  `$tabla` 
					WHERE  `$comp` LIKE  '$valor'";
		$rs = mysql_query($stm_sql);
		if($rs){
			if($datos = mysql_fetch_array($rs)){
				$dato = $datos[0];
			}
		}
		mysql_close($conec);
		return $dato;
	}
	
	function obtenerIdBitZarpeo(){
		$conn = conecta("bd_gerencia");
		$id_cadena = "BITZAR";
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		$stm_sql = "SELECT MAX( CAST( SUBSTR( id_bitacora, 11 ) AS UNSIGNED ) ) AS cant
					FROM bitacora
					WHERE id_bitacora LIKE  'BITZAR$mes$anio%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant = $datos['cant'] + 1;
			if($cant>0 && $cant<10)
				$id_cadena .= "000".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "00".$cant;
			if($cant>99 && $cant<1000)
				$id_cadena .= "0".$cant;
			if($cant>=1000)
				$id_cadena .= $cant;
		}
			
		mysql_close($conn);
		
		return $id_cadena;
	}
	
	function guardarBitacora(){
		$id_bit = obtenerIdBitZarpeo();
		$destino = $_SESSION['infoBitacora']['idUbicacion'];
		$presupuesto = $_SESSION['infoBitacora']['periodo'];
		$id_cuad = $_POST["txt_idCuadrilla"];
		$fecha = modFecha($_POST["txt_fechaRegistro"],3);
		
		$conn = conecta("bd_gerencia");
		
		$stm_sql = "INSERT INTO  bitacora (
						`id_bitacora` ,
						`id_control_costos` ,
						`id_presupuesto` ,
						`id_cuadrilla` ,
						`fecha`
					) VALUES (
						'$id_bit',  
						'$destino',  
						'$presupuesto',  
						'$id_cuad',  
						'$fecha'
					);";
		$rs = mysql_query($stm_sql);
		if($rs){
			guardarDetalleBitacora($id_bit);
		} else {
			?>
			<script>
				setTimeout("alert('HUBO UN ERROR AL MOMENTO DE REALIZAR EL REGISTRO DE LA BITACORA');",1000);
			</script>
			<?php
		}
		mysql_close($conn);
	}
	
	function guardarDetalleBitacora($id_bit){
		$correcto = true;
		$num_reg = $_POST["num_registros"];
		for($i = 1; $i <= $num_reg; $i++){
			$rfc_empleado = $_POST["hdn_rfc".$i];
			$empleado = $_POST["txt_integrante".$i];
			$puesto = $_POST["txt_puesto".$i];
			$avance = str_replace(",","",$_POST["txt_cantidad".$i]);
			$comentarios = $_POST["txa_comentarios".$i];
			$tipo = $_POST["chktipo".$i];
			
			$stm_sql = "INSERT INTO  detalle_bitacora (
							`id_bitacora` ,
							`rfc_trabajador` ,
							`nombre_emp` ,
							`puesto` ,
							`avance` ,
							`comentarios` ,
							`tipo`
						) VALUES (
							'$id_bit',  
							'$rfc_empleado',  
							'$empleado',  
							'$puesto',  
							'$avance',  
							'$comentarios',  
							'$tipo'
						);";
			$rs = mysql_query($stm_sql);
			if(!$rs)
				$correcto = false;
		}
		if($correcto){
			registrarOperacion("bd_gerencia",$id_bit,"RegBitacoraZarp",$_SESSION['usr_reg']);
			?>
			<script>
				setTimeout("alert('REGISTRO DE LA BITACORA REALIZADO CORRECTAMENTE');",1000);
			</script>
			<?php
		} else {
			mysql_query("DELETE FROM bitacora WHERE id_bitacora = '$id_bit'");
			mysql_query("DELETE FROM detalle_bitacora WHERE id_bitacora = '$id_bit'");
			?>
			<script>
				setTimeout("alert('HUBO UN ERROR AL MOMENTO DE REALIZAR EL REGISTRO DE LA BITACORA');",1000);
			</script>
			<?php
		}
	}
	
	//Funcion que se encarga de desplegar los registros agregados
	function mostrarRegBit(){
		echo "<table cellpadding='5' width='900'>";
		echo "  
			<tr>
				<td class='nombres_columnas' align='center'>APLICACI&Oacute;N</td>
        		<td class='nombres_columnas' align='center'>FECHA</td>
				<td class='nombres_columnas' align='center'>CUADRILLA</td>
			    <td class='nombres_columnas' align='center'>APLICADOR</td>
        		<td class='nombres_columnas' align='center'>SUPLENTE</td>
			    <td class='nombres_columnas' align='center'>CANTIDAD</td>
			    <td class='nombres_columnas' align='center'>COMENTARIOS</td>
      		</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		$total = 0;

		foreach ($_SESSION['bitZarpeo'] as $ind => $info) {
			echo "<tr>";
			foreach ($info as $key => $value) {
				switch($key){
					case "aplicacion":
						echo "<td class='nombres_filas'>$value</td>";
					break;
					case "fecha":
						echo "<td class='$nom_clase' align='center'>".modFecha($value,1)."</td>";
					break;
					case "idCuadrilla":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "aplicador":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "suplente":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "cantidad";
						echo "<td class='$nom_clase' align='center'>".number_format($value, 2,".",",")."</td>";
					break;
					case "comentarios";
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
	}//Fin de la funcion mostrarRegBit()	
	
		
	//Funcion que guarda los registros de la bitacora de de zarpeo
	function guardarRegistroLanzamientoBit(){
		//Obtener el id de la bitacora
		//$idBitacora = obtenerIdBitacora();
		//Recuperar el destino del arreglo de session		
		$destino = $_SESSION['infoBitacora']['destino'];
		//Recuperar la informacion de la session
		$concepto_bit = $_SESSION['infoBitacora']['concepto'];
		$periodo = $_SESSION['infoBitacora']['periodo'];
		
		//Conectar se a la Base de Datos
		$conn = conecta("bd_gerencia");
		$band = "";
		
		//Recorrer el arreglo que contiene los registros de la bitacora
		foreach($_SESSION['bitZarpeo'] as $ind => $concepto){
			
			//Obtener el id de la bitacora
			$idBitacora = obtenerIdBitacora();
			
			//Cargar datos de los integrantes de la Cuadrilla que va a ser Registrada
			$cuadrilla = cargarDatosCuadrilla($concepto['idCuadrilla']);
						
			//Obtener el Nombre de la Persona que hizo el Lanzamiento, ya sea un Integrante de la Cuadrilla o un Suplente
			$realizado = "";
			//Esta variable nos ayudara a saber si el Suplente debe ser agregado dentro de las Cuadrillas de Zarpeo para la creación de reportes.
			$suplente = "";			
			if($concepto['aplicador']!="N/A"){
				//Guardar el nombre del empleado que hizo el lanzamiento
				$realizado = $concepto['aplicador'];
			}
			else if($concepto['suplente']!="N/A"){
				//Guardar el nombre del suplente que hizo el lanzamiento
				$realizado = $concepto['suplente'];			
				$suplente = $concepto['suplente'];				
			}															
			
			//Crear la Sentencia SQL para almacenar el registro en la Bitacora de Zarpeo
			$stm_sql = "INSERT INTO bitacora_zarpeo(cuadrillas_id_cuadrillas,bitacora_id_bitacora,fecha,destino,aplicacion,realizado,cantidad,comentarios)			
			VALUES('$concepto[idCuadrilla]','$idBitacora','$concepto[fecha]','$destino','$concepto[aplicacion]','$realizado',$concepto[cantidad],'$concepto[comentarios]')";
			
			//Ejecutar la Sentencia
			$rs=mysql_query($stm_sql);
			
			//Verificar Resultado
			if ($rs){
				//Guardar los nombres de los Integrantes de la Cuadrilla que realizaron el Lanzamiento en la tabla de cuadriilas_zarpeo en la BD de Gerencia Tecnica
				guardarIntegrantes($concepto['idCuadrilla'],$idBitacora,$suplente,$cuadrilla);
				$band = 1;	
			}			
			else{
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
				unset($_SESSION['infoBitacora']);
				unset($_SESSION['bitZarpeo']);
			}
			
			if($band==1){
				//Llamar la funcion que guardara los datos en la tabla de bitacora
				guardarDatosBit($idBitacora,$concepto_bit,$periodo);
			}
			
		}// Fin foreach($_SESSION['bitZarpeo'] as $ind => $concepto)
		
		/*if($band==1){
			//Llamar la funcion que guardara los datos en la tabla de bitacora
			guardarDatosBit($idBitacora);
		}*/
		
	 }// Fin function guardarRegistroLanzamientoBit()	
	 
	 
	 //Esta funcion cargará los datos de la cuadrilla Indicada en un Arreglo, colocando el puesto como clave de la posicion y el nombre como valor.
	 function cargarDatosCuadrilla($idCuadrilla){
	 	
		//Declarar el Arreglo que contendra los datos de la Cuadrilla
		$cuadrilla = array();
		
		//Ejecutar la Sentencia SQL para obtener los datos de la cuadrilla con el ID proporcionado
		$rs_cuadrilla = mysql_query("SELECT nom_trabajador,puesto FROM integrantes_cuadrilla WHERE cuadrillas_id_cuadrillas = '$idCuadrilla'");
		
		//Verificar si hay datos para extraer del ResultSet
		if($datos_cuadrilla=mysql_fetch_array($rs_cuadrilla)){
			do{
				//Colocar el puesto como clave dentro del arreglo y el nombre del trabajador como valor de cada posicion
				$cuadrilla[] = array("nombre"=>$datos_cuadrilla['nom_trabajador'],"puesto"=>$datos_cuadrilla['puesto']);					
			}while($datos_cuadrilla=mysql_fetch_array($rs_cuadrilla));		
		}//Cierre if($datos_cuadrilla=mysql_fetch_array($rs_cuadrilla))
		
		
		//Retornar los datos encontrados
		return $cuadrilla;					 	
	 }
	 
	 
	 /*Esta función guardará los nombres y puestos de los Integrantes de Cuadrilla de cada registro realizado en la Bitacora de ZArpeo*/
	 function guardarIntegrantes($idCuadrilla,$idBitacora,$suplente,$integrantesCuadrilla){
	 
	 	//Recorrer en arreglo que contiene los Integrantes de la CUadrilla y guardarlos uno por uno en la tabla de cuadrillas_zarpeo		
	 	foreach($integrantesCuadrilla as $ind => $datosEmpleado){
			//Hacer la Inserción directa de datos, sin comprobación de errores, ya que la disponibilidad de datos esta garantizada
			mysql_query("INSERT INTO cuadrillas_zarpeo (id_cuadrilla, id_bitacora, nom_empleado, puesto) VALUES('$idCuadrilla',$idBitacora,'$datosEmpleado[nombre]','$datosEmpleado[puesto]')");
		}
		
		//Verificar si viene el nombre del suplente para ser insertado en la tabla de cuadrillas_zarpeo
		if($suplente!=""){
			mysql_query("INSERT INTO cuadrillas_zarpeo (id_cuadrilla, id_bitacora, nom_empleado, puesto) VALUES('$idCuadrilla',$idBitacora,'$suplente','SUPLENTE')");
		}
	 	
	 }//Cierre guardarIntegrantes($idCuadrilla,$idBitacora,$suplente,$integrantesCuadrilla)
	 
	
	/*Esta funcion genera el id de la bitacora deacuerdo a los registros en la BD*/
	function obtenerIdBitacora(){
		//Realizar la conexion a la BD de gerencia
		$conn = conecta("bd_gerencia");		
		$id="";
		//Crear la sentencia para obtener el presupuesto Reciente
		$stm_sql = "SELECT COUNT(id_bitacora) AS num, MAX(id_bitacora)+1 AS cant FROM bitacora";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			if($datos['num']>0)
				$id .= ($datos['cant']);
			else
				$id .= "1";
		}
		else 
			echo"error".mysql_error();		
		return $id;
	}//Fin de la function obtenerIdBitacora()
	
	
	//Funcion que guarda los registros de la bitacora
	function guardarDatosBit($idBitacora,$concepto,$periodo){

		//Recuperar la informacion de la session
		//$concepto = $_SESSION['infoBitacora']['concepto'];
		//$periodo = $_SESSION['infoBitacora']['periodo'];
			
		//Crear la Sentencia SQL para Alamcenar los materiales agregados 
		$stm_sql= "INSERT INTO bitacora(id_bitacora,concepto,periodo)
		VALUES ('$idBitacora','$concepto','$periodo')";		
		
		//Ejecutar la Sentencia
		$rs = mysql_query($stm_sql);
		
		//Verificar Resultado
		if ($rs){
			//Guardar la operacion realizada
			registrarOperacion("bd_gerencia",$idBitacora,"RegBitacoraZarp",$_SESSION['usr_reg']);
			$conn = conecta("bd_gerencia");
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
			unset($_SESSION['infoBitacora']);
			unset($_SESSION['bitZarpeo']);
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			unset($_SESSION['infoBitacora']);
			unset($_SESSION['bitZarpeo']);
		}
	 }// Fin function guardarDatosBit()	


	//Función que nos permitira revisar que no se registre un zarpeo en el mismo dia
	function revisarRegAsignados(){
		//Conectar a la BD de gerencia técnica
		$conn = conecta("bd_gerencia");
		
		//Obtener los datos necesarios para la comprobacion del $_POST		
		$idCuadrilla = $_POST['cmb_cuadrillas'];
		$fecha = modFecha($_POST['txt_fechaRegistro'],3);				
		$destino = $_POST['txt_destino'];
		
		//Crear sentencia SQL
		$sql_stm ="SELECT cuadrillas_id_cuadrillas FROM bitacora_zarpeo WHERE cuadrillas_id_cuadrillas='$idCuadrilla' AND fecha='$fecha' AND destino='$destino'";
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);
		
		if($datos=mysql_fetch_array($rs))
			return false;
		else
			return true;		
	}
	 
	//******************************************************************************************************//
	//*******************OPERACIONES PARA REGISTRAR LOS DATOS DE BITACORA DE TRANSPORTE*********************//
	//******************************************************************************************************//
	
	 //Funcion para guardar la informacion de la bitacora de transporte
	function guardarBitTrans(){
		//Conectar se a la Base de Datos
		$conn = conecta("bd_gerencia");
		
		//Recorrer el arreglo que contiene los registros de la bitacora
		foreach($_SESSION['RegBitTransp'] as $ind => $concepto){
			//Obtener el id de la bitacora
			$idBitacora= obtenerIdBitacoraTransp();
				
			//Crear la Sentencia SQL para Alamcenar la informacion en la tabla de bitacora transporte
			$stm_sql= "INSERT INTO bitacora_transporte(id_bitacora_transporte,fecha,nombre,puesto,destino,cantidad,comentarios,ver_comentario)
			VALUES ('$idBitacora','$concepto[fecha]','$concepto[nombre]','$concepto[cargo]','$concepto[ubicacion]',$concepto[cantidad],'$concepto[comentarios]',$concepto[verComent])";		

			//Ejecutar la Sentencia
			$rs=mysql_query($stm_sql);
			
			//Verificar Resultado
			if ($rs){
				$band=1;	
			}
				
			else{
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
				unset($_SESSION['RegBitTransp']);
			}	
		}// Fin foreach($_SESSION['RegBitTransp'] as $ind => $concepto)
		
		if($band==1){
			//Guardar la operacion realizada
			registrarOperacion("bd_gerencia","N/A","RegBitacoraTransp",$_SESSION['usr_reg']);
			$conn = conecta("bd_gerencia");
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
			unset($_SESSION['RegBitTransp']);			
		}//FIN if($band==1)
	 }// Fin function guardarBitTrans()	
	 	

	//Funcion que se encarga de desplegar los registros agregados
	function mostrarRegBitTransp(){
		echo "<table cellpadding='5' width='900'>";
		echo "  
			<tr>
        		<td class='nombres_columnas' align='center'>FECHA</td>
				<td class='nombres_columnas' align='center'>NOMBRE</td>
			    <td class='nombres_columnas' align='center'>CANTIDAD</td>
        		<td class='nombres_columnas' align='center'>UBICACI&Oacute;N</td>
			    <td class='nombres_columnas' align='center'>CARGO</td>
			    <td class='nombres_columnas' align='center'>COMENTARIOS</td>
      		</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		$total = 0;

		foreach ($_SESSION['RegBitTransp'] as $ind => $info) {
			echo "<tr>";
			foreach ($info as $key => $value) {
				switch($key){
					case "fecha":
						echo "<td class='$nom_clase' align='center'>".modFecha($value,1)."</td>";
					break;
					case "nombre":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "cantidad":
						echo "<td class='$nom_clase' align='center'>".number_format($value, 2,".",","). " m&sup3;</td>";
					break;
					case "ubicacion":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "cargo":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "comentarios";
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
	}//Fin de la funcion mostrarRegBitTransp()	


	/*Esta funcion genera el id de la bitacora deacuerdo a los registros en la BD*/
	function obtenerIdBitacoraTransp(){
		//Realizar la conexion a la BD de gerencia
		$conn = conecta("bd_gerencia");		
		$id="";
		//Crear la sentencia para obtener el id Reciente
		$stm_sql = "SELECT COUNT(id_bitacora_transporte) AS num, MAX(id_bitacora_transporte)+1 AS cant FROM bitacora_transporte";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			if($datos['num']>0)
				$id .= ($datos['cant']);
			else
				$id .= "1";
		}
		else 
			echo"error".mysql_error();		
		return $id;
	}//Fin de la function obtenerIdBitacora()
	

?>