<?php
	/**
	  * Nombre del M�dulo: Unidad de Salud Ocupacional                                              
	  * Nombre Programador: Antonio de Jes�s Jim�nez Cuevas                            
	  * Fecha: 29/Junio/2012                                     			
	  * Descripci�n: Este archivo contiene funciones para almacenar la informaci�n relacionada con el formulario de Guardar Bitacora de Radiografias 
	  **/
	
	//Esta funci�n se encarga de generar el Id de la Bitacora de Radiografias
	function obtenerIdBitRadio(){
		//Realizar la conexion a la BD de USO
		$conn = conecta("bd_clinica");
		//Definir las  letras en la Id de la Bitacora
		$id_cadena = "BRA";
		//Obtener el mes y el a�o
		$fecha = date("m-Y");
		//Concatenar al id de la bitacora la fecha segun a�o y mes
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);	
		//Obtener el mes actual y el a�o actual para ser agregado en la consulta y asi obtener las requisiciones del mes en curso del a�o en curso
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		//Crear la sentencia para obtener el numero de registros en la Bitacora
		$stm_sql = "SELECT MAX(id_bit_radiografias) AS clave FROM bitacora_radiografias WHERE id_bit_radiografias LIKE 'BRA$mes$anio%'";
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
	}//Fin de la Funcion obtenerIdBitRadio()
	
	//Funcion que guarda el registro en la bitacora de Radiografias
	function guardarRegBitRadiografias(){
		$idBit=$_POST["txt_idBit"];
		$idEmpresa=$_POST["hdn_empresa"];
		//$idHistClinico=$_POST["txt_clinico"];
		$categoria=$_POST["hdn_categoria"];
		$idEmpleado=strtoupper($_POST["txt_numE"]);
		$nombre=strtoupper($_POST["txt_nombre"]);
		$area=strtoupper($_POST["txt_area"]);
		$puesto=strtoupper($_POST["txt_puesto"]);
		$fecha=modFecha($_POST["txt_fecha"],3);
		$lugar=strtoupper($_POST["txt_lugar"]);
		$cantProy=$_POST["txt_cantProy"];
		$solicitante=strtoupper($_POST["txt_nomSolicitante"]);
		$responsable=strtoupper($_POST["txt_nomResponsable"]);
		//Realizar la conexion a la BD de USO
		$conn = conecta("bd_clinica");
		//Sentencia SQL para guardar el registro de Bitacora
		$sql_stm="INSERT INTO bitacora_radiografias (id_bit_radiografias,catalogo_empresas_id_empresa,historial_clinico_id_historial,categoria,id_empleados_empresa,nom_empleado,area,puesto,fecha,lugar_practicado,cant_proyeccion,nom_solicitante,nom_responsable) VALUES ('$idBit','$idEmpresa','','$categoria','$idEmpleado','$nombre','$area','$puesto','$fecha','$lugar','$cantProy','$solicitante','$responsable')";
		$rs=mysql_query($sql_stm);
		if($rs){
			//Guardar en la bitacora el registro de radiografias practicadas
			$error=guardarRadiografiasPracticadas($idBit);
			//Cerrar la conexion con la BD		
			mysql_close($conn);
			if($error!=""){
				$sql_stm="DELETE FROM bitacora_radiografias WHERE id_bit_radiografias='$idBit'";
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}else{
				//Guardar el Movimiento realizado en la tabla de Movimientos
				registrarOperacion("bd_clinica","$idBit","RegistroBitRadiografias",$_SESSION['usr_reg']);
				//Redireccionar a la pagina de exito
				echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
			}
		}else{
			//Cerrar la conexion con la BD		
			mysql_close($conn);
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}//Fin de function guardarRegBitRadiografias() 
	
	//Funcion que guarda las radiografias practicadas por bitacora
	function guardarRadiografiasPracticadas($idBit){
		//Variable que capturara el posible error
		$error="";
		foreach($_SESSION["radiografias"] as $ind => $value){
			$sql_stm="INSERT INTO detalle_radiografia (catalogo_radiografias_id_proyeccion,bitacora_radiografias_id_bit_radiografias) VALUES ('$value','$idBit')";
			$rs=mysql_query($sql_stm);
			if(!$rs){
				$error=mysql_error();
				break;
			}
		}
		if($error!=""){
			$sql_stm="DELETE FROM detalle_radiografia WHERE bitacora_radiografias_id_bit_radiografias='$idBit'";
			$rs=mysql_query($sql_stm);
		}
		//Borrar el Arreglo de Radiografias
		unset($_SESSION["radiografias"]);
		return $error;
	}//Fin de function guardarRadiografiasPracticadas($idBit)
	
	//Funcion que muestra los registros en la bit�cora de Radiografias
	function mostrarBitacoraRadiografias($fechaIni,$fechaFin){
		$fechaI=modFecha($fechaIni,3);
		$fechaF=modFecha($fechaFin,3);
		$conn=conecta("bd_clinica");
		//Sentencia SQL para guardar el registro de Bitacora
		$sql_stm="SELECT id_bit_radiografias,catalogo_empresas_id_empresa,categoria,nom_empleado,area,puesto,fecha,cant_proyeccion FROM bitacora_radiografias WHERE fecha BETWEEN '$fechaI' AND '$fechaF' ORDER BY catalogo_empresas_id_empresa,nom_empleado";
		$rs=mysql_query($sql_stm);
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "				
				<table cellpadding='5' width='100%' id='tabla-resultadosRegBitacora'>
				<caption class='titulo_etiqueta'>Registros de la Bit&aacute;cora de Radiograf&iacute;as del $fechaIni al $fechaFin</caption>
				<thead>
					<tr>
						<th class='nombres_columnas' align='center'>SELECCIONAR</th>
						<th class='nombres_columnas' align='center'>EMPRESA</th>
        				<th class='nombres_columnas' align='center'>NOMBRE TRABAJADOR</th>
				        <th class='nombres_columnas' align='center'>&Aacute;REA</th>
        				<th class='nombres_columnas' align='center'>PUESTO</th>
						<th class='nombres_columnas' align='center'>FECHA</th>
						<th class='nombres_columnas' align='center'>CANTIDAD DE PROYECCIONES</th>
        				<th class='nombres_columnas' align='center'>RADIOGRAF&Iacute;AS REALIZADAS</th>
      				</tr>
				</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			echo "<tbody>";
			do{
				if($datos["catalogo_empresas_id_empresa"]==0)
					$empresa="CONCRETO LANZADO DE FRESNILLO MARCA";
				else
					$empresa=obtenerDato("bd_clinica","catalogo_empresas", "nom_empresa", "id_empresa", $datos['catalogo_empresas_id_empresa']);
				echo "	<tr>
						<td class='nombres_filas' align='center'>";
						?>
						<input type="radio" name="rdb_bitacora" id="rdb_bitacora" value="<?php echo $datos['id_bit_radiografias']?>"/>
						<?php
				echo "</td>
						<td class='$nom_clase' align='center'>$empresa</td>
						<td class='$nom_clase' align='center'>$datos[nom_empleado]</td>
						<td class='$nom_clase' align='center'>$datos[area]</td>
						<td class='$nom_clase' align='center'>$datos[puesto]</td>
						<td class='$nom_clase' align='center'>".modFecha($datos["fecha"],1)."</td>
						<td class='$nom_clase' align='center'>$datos[cant_proyeccion]</td>";?>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verRadApp<?php echo $cont?>" id="btn_verRadApp<?php echo $cont?>" class="botones" value="Proyecciones" 
							onMouseOver="window.estatus='';return true" title="Ver Radiograf&iacute;as Practicadas" 
							onClick="javascript:window.open('verRadiografiasApp.php?id_bitacora=<?php echo $datos['id_bit_radiografias'];?>&btn=<?php echo "btn_verRadApp$cont"?>',
							'_blank','top=50, left=50, width=800, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');this.disabled=true;"/>							
						</td>						
					</tr>
				<?php
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";					
			}while($datos=mysql_fetch_array($rs));
			echo "</tbody>";
			echo "</table>";
		}
		else
			echo "<meta http-equiv='refresh' content='0;url=frm_modBitacoraRadiografias.php?noResults'>";
		mysql_close($conn);
	}
	
	//Funcion que borra los registros de la bitacora de Radiografias
	function borrarRegBitacora($idBit){
		//Abrir la conexion a la BD
		$conn=conecta("bd_clinica");
		//Sentencia SQL para borrar el registro de Bitacora
		$sql_stm="DELETE FROM bitacora_radiografias WHERE id_bit_radiografias='$idBit'";
		$rs=mysql_query($sql_stm);
		if($rs){
			//Sentencia SQL para borrar el registro de Bitacora
			$sql_stm="DELETE FROM detalle_radiografia WHERE bitacora_radiografias_id_bit_radiografias='$idBit'";
			$rs=mysql_query($sql_stm);
			//Cerrar la conexion con la BD
			mysql_close($conn);
			if($rs){
				//Guardar el Movimiento realizado en la tabla de Movimientos
				registrarOperacion("bd_clinica","$idBit","BorrarRegBitRadiografias",$_SESSION['usr_reg']);
				//Redireccionar a la pagina de exito
				echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
			}
			else{
				$error=mysql_error();
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}
		}
		else{
			//Cerrar la conexion con la BD
			mysql_close($conn);
			$error=mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}
	
	//Funcion que borra los registros de la bitacora de Radiografias
	function modificarRegBitacora($idBit){
		$conn=conecta("bd_clinica");
		//Sentencia SQL para guardar el registro de Bitacora
		$sql_stm="SELECT nom_empleado,id_empleados_empresa,area,puesto,fecha,lugar_practicado,cant_proyeccion,nom_solicitante,nom_responsable FROM bitacora_radiografias WHERE id_bit_radiografias='$idBit'";
		$rs=mysql_query($sql_stm);
		$datos=mysql_fetch_array($rs);
		$fecha=modFecha($datos["fecha"],1);
		$empleado=$datos["nom_empleado"];
		$lugar=$datos["lugar_practicado"];
		$num=$datos["id_empleados_empresa"];
		$proy=$datos["cant_proyeccion"];
		$area=$datos["area"];
		$puesto=$datos["puesto"];
		$solicitante=$datos["nom_solicitante"];
		$responsable=$datos["nom_responsable"];
		?>
		<fieldset id="tabla-complementar-radiografia" class="borde_seccion">
		<legend class="titulo_etiqueta">Seleccionar el Tipo de Registro a Realizar</legend>
		<br>
		<form name="frm_registrarRadiografia" method="post" onsubmit="return valFormGuardarRadio(this);" action="frm_modBitacoraRadiografias.php">
		<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
			<tr>
			  <td><div align="right">Id Bit&aacute;cora</div></td>
				<td>
					<input type="text" name="txt_idBit" id="txt_idBit" value="<?php echo $idBit;?>" size="10" maxlength="10" readonly="readonly" class="caja_de_texto"/>
				</td>
				<td><div align="right">Fecha</div></td>
				<td>
					<input name="txt_fecha" type="text" id="txt_fecha" value=<?php echo $fecha;?> size="10" maxlength="15" readonly="readonly" class="caja_de_texto"/>
				</td>
			</tr>
			<tr>
				<td><div align="right">*Nombre Empleado</div></td>
				<td>
					<input type="text" name="txt_nombre" id="txt_nombre" value="<?php echo $empleado;?>" size="50" maxlength="75" onkeypress="return permite(event,'car',0);" class="caja_de_texto"/>
				</td>
				<td><div align="right">*Lugar donde se Practic&oacute;</div></td>
				<td>
					<input type="text" name="txt_lugar" id="txt_lugar" value="<?php echo $lugar;?>" size="35" maxlength="30" onkeypress="return permite(event,'car',0);" class="caja_de_texto"/>
				</td>
			</tr>
			<tr>
				<td><div align="right">*N&uacute;mero Empleado</div></td>
				<td>
					<input type="text" name="txt_numE" id="txt_numE" value="<?php echo $num;?>" size="10" maxlength="10" onkeypress="return permite(event,'num',0);" class="caja_de_num"/>
				</td>
				<td><div align="right">*Cantidad de Proyecciones</div></td>
				<td>
					<input type="text" name="txt_cantProy" id="txt_cantProy" value="<?php echo $proy;?>" size="5" readonly="readonly" class="caja_de_num"/>
				</td>
			</tr>
			<tr>
				<td><div align="right">*&Aacute;rea</div></td>
				<td>
					<input type="text" name="txt_area" id="txt_area" value="<?php echo $area;?>" size="20" maxlength="20" onkeypress="return permite(event,'num_car',0);" class="caja_de_texto"/>
				</td>
				<td><div align="right">*Nombre Solicitante</div></td>
				<td>
					<input type="text" name="txt_nomSolicitante" id="txt_nomSolicitante" value="<?php echo $solicitante;?>" size="40" maxlength="75" onkeypress="return permite(event,'car',0);" class="caja_de_texto"/>
				</td>
			</tr>
			<tr>
				<td><div align="right">*Puesto</div></td>
				<td>
					<input type="text" name="txt_puesto" id="txt_puesto" value="<?php echo $puesto;?>" size="30" maxlength="30" onkeypress="return permite(event,'num_car',0);" class="caja_de_texto"/>
				</td>
				<td><div align="right">*Nombre Responsable</div></td>
				<td>
					<input type="text" name="txt_nomResponsable" id="txt_nomResponsable" value="<?php echo $responsable;?>" size="40" maxlength="75" 
					onkeypress="return permite(event,'car',0);" class="caja_de_texto"/>
				</td>
			</tr>
			<tr>
				<td colspan="4">
					<strong>*Datos marcados con asterisco (*) son obligatorios</strong>
				</td>
			</tr>
			<tr>
				<td colspan="4" align="center">
					<input type="submit" name="sbt_guardar" id="sbt_guardar" value="Modificar" class="botones" title="Modificar el Registro" onmouseover="window.status='';return true;"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_regRadiografias" id="btn_regRadiografias" value="Registrar Radiograf&iacute;as" class="botones_largos" 
					title="Registrar las Radiograf&iacute;as realizadas" onclick="abrirVentanaRadiografias(this);"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_limpiar" id="btn_limpiar" value="Limpiar" class="botones" title="Limpiar el Formulario" onclick="restablecerFormularioBitRadio();"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_cancelar" id="btn_cancelar" value="Cancelar" class="botones" title="Cancela el Guardado y Regresa a la Secci&oacute;n Anterior" 
					onclick="location.href='frm_modBitacoraRadiografias.php?cancel&fechaI=<?php echo $_POST["hdn_fechaI"];?>&fechaF=<?php echo $_POST["hdn_fechaF"];?>'"/>
				</td>
			</tr>
		</table>
		</form>
		</fieldset>
		
		<div id="calendario">
			<input name="calendario_iniRep" type="image" id="calendario_iniRep" onclick="displayCalendar(document.frm_registrarRadiografia.txt_fecha,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
		</div>
		<?php
	}
	
	//Funcion que actualiza el registro de la bitacora de Radiografias
	function actualizarRegBitRadiografias(){
		$idBit=$_POST["txt_idBit"];
		//$idHistClinico=$_POST["txt_clinico"];
		$idEmpleado=strtoupper($_POST["txt_numE"]);
		$nombre=strtoupper($_POST["txt_nombre"]);
		$area=strtoupper($_POST["txt_area"]);
		$puesto=strtoupper($_POST["txt_puesto"]);
		$fecha=modFecha($_POST["txt_fecha"],3);
		$lugar=strtoupper($_POST["txt_lugar"]);
		$cantProy=$_POST["txt_cantProy"];
		$solicitante=strtoupper($_POST["txt_nomSolicitante"]);
		$responsable=strtoupper($_POST["txt_nomResponsable"]);
		//Realizar la conexion a la BD de USO
		$conn = conecta("bd_clinica");
		//Sentencia SQL para guardar el registro de Bitacora
		$sql_stm="UPDATE bitacora_radiografias SET id_empleados_empresa='$idEmpleado',nom_empleado='$nombre',area='$area',puesto='$puesto',
					fecha='$fecha',lugar_practicado='$lugar',cant_proyeccion='$cantProy',nom_solicitante='$solicitante',nom_responsable='$responsable' WHERE id_bit_radiografias='$idBit'";
		$rs=mysql_query($sql_stm);
		if($rs){
			//Si esta definido el arreglo de las radiografias, remplazar las existentes por las nuevas
			if(isset($_SESSION["radiografias"])){
				//Funcion que actualiza el registro de radiografias practicadas
				actualizarRadiografiasPracticadas($idBit);
			}
			//Cerrar la conexion con la BD		
			mysql_close($conn);
			//Guardar el Movimiento realizado en la tabla de Movimientos
			registrarOperacion("bd_clinica","$idBit","ModificarRegBitRadiografias",$_SESSION['usr_reg']);
			//Redireccionar a la pagina de exito
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}else{
			//Cerrar la conexion con la BD		
			mysql_close($conn);
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}
	
	function actualizarRadiografiasPracticadas($idBit){
		//Borrar las radiografias previamente guardadas
		mysql_query("DELETE FROM detalle_radiografia WHERE bitacora_radiografias_id_bit_radiografias='$idBit'");
		//Obtener las proyecciones registradas en el arreglo de Sesion
		foreach($_SESSION["radiografias"] as $ind => $value){
			$sql_stm="INSERT INTO detalle_radiografia (catalogo_radiografias_id_proyeccion,bitacora_radiografias_id_bit_radiografias) VALUES ('$value','$idBit')";
			$rs=mysql_query($sql_stm);
		}
		//Borrar el Arreglo de Radiografias
		unset($_SESSION["radiografias"]);
	}
?>