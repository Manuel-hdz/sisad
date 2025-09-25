<?php
	/**
	  * Nombre del Módulo: Unidad de Salud Ocupacional                                              
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas                            
	  * Fecha: 29/Junio/2012                                     			
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de Guardar Bitacora de Radiografias 
	  **/
	
	//Esta función se encarga de generar el Id de la Bitacora de Medicamentos
	function obtenerIdBitMedicamentos(){
		//Realizar la conexion a la BD de USO
		$conn = conecta("bd_clinica");
		//Definir las  letras en la Id de la Bitacora
		$id_cadena = "BMD";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		//Concatenar al id de la bitacora la fecha segun año y mes
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);	
		//Obtener el mes actual y el año actual para ser agregado en la consulta y asi obtener las requisiciones del mes en curso del año en curso
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		//Crear la sentencia para obtener el numero de registros en la Bitacora
		$stm_sql = "SELECT MAX(id_bit_medicamento) AS clave FROM bitacora_medicamentos WHERE id_bit_medicamento LIKE 'BMD$mes$anio%'";
		$rs = mysql_query($stm_sql);		
		if($datos=mysql_fetch_array($rs)){
			$cant = intval(substr($datos['clave'],7,3));
			$cant += 1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
		//Retornar el id de la Bitacora
		return $id_cadena;
	}//Fin de la Funcion obtenerIdBitMedicamentos()
	
	//Esta función se encarga de generar el Id de la Bitacora de Radiografias
	function obtenerIdMedicamento(){
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_clinica");
		//Sentencia SQl para extraer el id
		$sql="SELECT MAX(id_med)+1 AS id FROM catalogo_medicamento";
		//Ejecutar la sentencia
		$rs=mysql_query($sql);
		if($datos=mysql_fetch_array($rs)){
			if($datos["id"]==NULL)
				$id=1;
			else
				$id=$datos["id"];
		}
		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);
		//Retornar el ID calculado
		return $id;
	}//Fin de la Funcion obtenerIdBitRadio()
	
	//Funcion que muestra los registros en la bitácora de Radiografias
	function mostrarMedicamentos(){
		$conn=conecta("bd_clinica");
		//Sentencia SQL para guardar el registro de Bitacora
		$sql_stm="SELECT id_med,codigo_med,nombre_med,descripcion_med,presentacion,clasificacion_med,existencia_actual,unidad_despacho FROM catalogo_medicamento ORDER BY codigo_med,nombre_med";
		$rs=mysql_query($sql_stm);
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "				
				<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'>Cat&aacute;logo De Medicamentos</caption>
					<tr>
						<th class='nombres_columnas' align='center'>C&Oacute;DIGO<br>MEDICAMENTO</th>
						<th class='nombres_columnas' align='center'>NOMBRE<br>MEDICAMENTO</th>
        				<th class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N MEDICAMENTO</th>
				        <th class='nombres_columnas' align='center'>PRESENTACI&Oacute;N</th>
        				<th class='nombres_columnas' align='center'>CLASIFICACI&Oacute;N</th>
						<th class='nombres_columnas' align='center'>EXISTENCIA UNITARIA</th>
						<th class='nombres_columnas' align='center'>EDITAR</th>
      				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{
				echo " 
					<tr>
						<td class='$nom_clase' align='center'>$datos[codigo_med]</td>
						<td class='$nom_clase' align='center'>$datos[nombre_med]</td>
						<td class='$nom_clase' align='center'>$datos[descripcion_med]</td>
						<td class='$nom_clase' align='center'>$datos[presentacion]</td>
						<td class='$nom_clase' align='center'>$datos[clasificacion_med]</td>
						<td class='$nom_clase' align='center'>$datos[existencia_actual] $datos[unidad_despacho](S)</td>";
						?>
						<td class='<?php echo $nom_clase?>' align='center'>
							<input type="image" src="../../images/editar.png" width="30" height="25" onclick="location.href='frm_regBitacoraMedicamentosUpd.php?idMed=<?php echo $datos["id_med"]?>'"/>
						</td>
						<?php
				echo "		
					</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";					
			}while($datos=mysql_fetch_array($rs));
			echo "</table>";
		}
		else{
			echo mysql_error();
		}
		mysql_close($conn);
	}
	
	//funcion que incrementa la cantidad de Medicamentos en el catalogo
	function actualizarMedicamentos(){
		//Obtener el ID y la cantidad a incrementar
		$idMedicamento=$_POST["cmb_medicamento"];
		$nuevoTotal=$_POST["txt_total"];
		$existencia=$_POST["txt_existencia"];
		$cantEntrada=$_POST["txt_surtido"];
		//Conectar a la BD de la clinica
		$conn=conecta("bd_clinica");
		//Sentencia SQL para guardar el registro de Bitacora
		$sql_stm="UPDATE catalogo_medicamento SET existencia_actual='$nuevoTotal' WHERE id_med='$idMedicamento'";
		$rs=mysql_query($sql_stm);
		//Cerrar la conexion con la BD
		mysql_close($conn);
		if($rs){
			$res=registrarMovimientoBitacoraMed($idMedicamento,"ENTRADA",$cantEntrada);
			if($res==1){
				//Guardar el Movimiento realizado en la tabla de Movimientos
				registrarOperacion("bd_clinica","$idMedicamento","ActualizarCantMedicamento",$_SESSION['usr_reg']);
				//Obtener el nombre del medicamento
				$nomMed=obtenerDato("bd_clinica","catalogo_medicamento","nombre_med","id_med",$idMedicamento);
				?>
					<script type="text/javascript" language="javascript">
						setTimeout("alert(\"Medicamento: '<?php echo $nomMed?>' Actualizado de <?php echo $existencia?> a <?php echo $nuevoTotal?>\");",1000);
					</script>
				<?php
			}
		}
	}
	
	//funcion que registra el movimiento en la bitacora de medicamento
	function registrarMovimientoBitacoraMed($idMedicamento,$tipoMov,$cantidad){
		$idBit=obtenerIdBitMedicamentos();
		if($tipoMov=="REGISTRO" || $tipoMov=="ENTRADA"){
			//Fecha actual
			$fecha=date("Y-m-d");
			//Obtener la cantidad por presentacion
			$cantPresentacion=obtenerDato("bd_clinica","catalogo_medicamento","cant_presentacion","id_med",$idMedicamento);
			//Obtener la cantidad de envases que estan entrando
			$cantEnvases=$cantidad/$cantPresentacion;
			//Obtener la cantidad actual de medicamento, (a este punto, el medicamento ya se incremento)
			$cantMedicamento=obtenerDato("bd_clinica","catalogo_medicamento","existencia_actual","id_med",$idMedicamento);
			//Restar la cantidad de entrada a la cantidad Actual para saber cuanto medicamento habia antes y poderlo registrar en la bitacora de medicamentos
			$cantMedicamento=$cantMedicamento-$cantidad;
			//Obtener la cantidad de envases que existian antes de incrementar
			$cant_med=round($cantMedicamento/$cantPresentacion,0);
			//Sentencia SQL para guardar el registro de Bitacora
			$sql_stm="INSERT INTO bitacora_medicamentos (id_bit_medicamento,catalogo_medicamento_id_med,tipo_movimiento,fecha,cant_med,total_med,surtido,total_med_surtido) 
						VALUES ('$idBit','$idMedicamento','$tipoMov','$fecha','$cant_med','$cantMedicamento','$cantEnvases','$cantidad')";
		}
		//Reconectar a la BD de la clinica (se cierra con la llamada a la funcion obtenerIdBitMedicamentos())
		$conn=conecta("bd_clinica");
		$rs=mysql_query($sql_stm);
		mysql_close($conn);
		if($rs)
			return 1;
		else
			return mysql_error();
	}
	
	//Funcion que guarda la informacion del medicamento
	function guardarInfoMedicamento(){
		//Obtener el ID del Medicamento
		$idMedicamento=obtenerIdMedicamento();
		//Obtener los datos del medicamento
		$codigo=$_POST["txt_codigo"];
		//Si no esta definido el check de clasificacion en el POST, el valor viene en la clasificacion
		if(!isset($_POST["ckb_nuevaClasificacion"]))
			$clasificacion=$_POST["cmb_clasificacion"];
		else
			$clasificacion=strtoupper($_POST["txt_nuevaClasificacion"]);
		$descripcion=strtoupper($_POST["txa_descripcion"]);
		$presentacion=strtoupper($_POST["txa_presentacion"]);
		$nombre=strtoupper($_POST["txt_nomMed"]);
		$cantPorPresentacion=$_POST["txt_cantPres"];
		$cantMed=$_POST["txt_cantMed"];
		//Si no esta definido el check de clasificacion en el POST, el valor viene en la clasificacion
		if(!isset($_POST["ckb_nuevaUnidadMedida"]))
			$unidadMedida=$_POST["cmb_unidadMed"];
		else
			$unidadMedida=strtoupper($_POST["txt_nuevaUnidadMedida"]);
		//Si no esta definido el check de clasificacion en el POST, el valor viene en la clasificacion
		if(!isset($_POST["ckb_nuevaPresentacion"]))
			$tipoPresentacion=$_POST["cmb_tipoPres"];
		else
			$tipoPresentacion=strtoupper($_POST["txt_nuevaPresentacion"]);
		//Si no esta definido el check de clasificacion en el POST, el valor viene en la clasificacion
		if(!isset($_POST["ckb_nuevaUnidadDesp"]))
			$unidadDespacho=$_POST["cmb_unidadDesp"];
		else
			$unidadDespacho=strtoupper($_POST["txt_nuevaUnidadDesp"]);
		$cantTotal=$_POST["txt_cantMedTotal"];
		//Conectar a la BD de la Clinica
		$conn=conecta("bd_clinica");
		//Sentencia SQL
		$sql_stm="INSERT INTO catalogo_medicamento (id_med,clasificacion_med,nombre_med,descripcion_med,codigo_med,tipo_presentacion,presentacion,cant_presentacion,unidad_medida,unidad_despacho,existencia_actual)
				VALUES ('$idMedicamento','$clasificacion','$nombre','$descripcion','$codigo','$tipoPresentacion','$presentacion','$cantPorPresentacion','$unidadMedida','$unidadDespacho','$cantTotal')";
		$rs=mysql_query($sql_stm);
		if($rs){
			//Cerrar la conexion con la BD
			mysql_close($conn);
			//Ejecutar la funcion que registra el movimiento en la Bitacora de Medicamentos
			$res=registrarMovimientoBitacoraMed($idMedicamento,"REGISTRO",$cantTotal);
			//Si res es igual a 1, el guardado se realizo correctamente
			if($res==1){
				//Guardar el Movimiento realizado en la tabla de Movimientos
				registrarOperacion("bd_clinica","$idMedicamento","AgregarMedicamento",$_SESSION['usr_reg']);
				//Redireccionar a la pantalla de Exito
				echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
			}
		}
		else{
			//Capturar el error
			$error=mysql_error();
			//Redireccionar a la pantalla de Error
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			//Cerrar la conexion con la BD
			mysql_close($conn);
		}
	}//Fin de function guardarInfoMedicamento()
	
	//Funcion que actualiza la informacion del medicamento
	function actualizarInfoMedicamento(){
		//Obtener el ID del Medicamento
		$idMedicamento=$_POST["hdn_accion"];
		//Obtener los datos del medicamento
		$codigo=$_POST["txt_codigo"];
		//Si no esta definido el check de clasificacion en el POST, el valor viene en la clasificacion
		if(!isset($_POST["ckb_nuevaClasificacion"]))
			$clasificacion=$_POST["cmb_clasificacion"];
		else
			$clasificacion=strtoupper($_POST["txt_nuevaClasificacion"]);
		$descripcion=strtoupper($_POST["txa_descripcion"]);
		$presentacion=strtoupper($_POST["txa_presentacion"]);
		$nombre=strtoupper($_POST["txt_nomMed"]);
		$cantPorPresentacion=$_POST["txt_cantPres"];
		$cantMed=$_POST["txt_cantMed"];
		//Si no esta definido el check de clasificacion en el POST, el valor viene en la clasificacion
		if(!isset($_POST["ckb_nuevaUnidadMedida"]))
			$unidadMedida=$_POST["cmb_unidadMed"];
		else
			$unidadMedida=strtoupper($_POST["txt_nuevaUnidadMedida"]);
		//Si no esta definido el check de clasificacion en el POST, el valor viene en la clasificacion
		if(!isset($_POST["ckb_nuevaPresentacion"]))
			$tipoPresentacion=$_POST["cmb_tipoPres"];
		else
			$tipoPresentacion=strtoupper($_POST["txt_nuevaPresentacion"]);
		//Si no esta definido el check de clasificacion en el POST, el valor viene en la clasificacion
		if(!isset($_POST["ckb_nuevaUnidadDesp"]))
			$unidadDespacho=$_POST["cmb_unidadDesp"];
		else
			$unidadDespacho=strtoupper($_POST["txt_nuevaUnidadDesp"]);
		$cantTotal=$_POST["txt_cantMedTotal"];
		//Conectar a la BD de la Clinica
		$conn=conecta("bd_clinica");
		//Sentencia SQL
		$sql_stm="UPDATE catalogo_medicamento SET clasificacion_med='$clasificacion',nombre_med='$nombre',descripcion_med='$descripcion',codigo_med='$codigo',
				tipo_presentacion='$tipoPresentacion',presentacion='$presentacion',cant_presentacion='$cantPorPresentacion',unidad_medida='$unidadMedida',unidad_despacho='$unidadDespacho',
				existencia_actual='$cantTotal' WHERE id_med='$idMedicamento'";
		$rs=mysql_query($sql_stm);
		if($rs){
			//Cerrar la conexion con la BD
			mysql_close($conn);
			//Guardar el Movimiento realizado en la tabla de Movimientos
			registrarOperacion("bd_clinica","$idMedicamento","ActualizarInforMedicamento",$_SESSION['usr_reg']);
			//Redireccionar a la pantalla de Exito
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			//Capturar el error
			$error=mysql_error();
			//Redireccionar a la pantalla de Error
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			//Cerrar la conexion con la BD
			mysql_close($conn);
		}
	}//Fin de function guardarInfoMedicamento()
?>