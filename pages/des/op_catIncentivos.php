<?php
	/**
	  * Nombre del Módulo: Desarrollo
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 20/Octubre/2011
	  * Descripción: Este archivo contiene funciones para Realizar consultas al Catalogo de Incentivos
	**/
	
	//Funcion que muestra las actividades de cada Estándar Manejado en Desarrollo
	//Valores de Retorno y significado
	// 0 -> Actividades encontradas
	// 1 -> No se encontraron Actividades
	// 2 -> Se acaba de crear, agregar actividades correspondientes
	function mostrarActividades(){
		if ((isset($_POST["txt_nuevoEstandar"]) && $_POST["txt_nuevoEstandar"]=="") || (isset($_POST["sbt_agregarNuevo"]) || isset($_POST["sbt_modificarActividad"])) ){
			//Variable que almacena el incentivo seleccionado
			$inc="";
			if (!isset($_POST["hdn_incentivo"]) && !isset($_POST["hdn_inc"])){
				//Realizar la conexion a la BD de Desarrollo
				$conn = conecta("bd_desarrollo");
				$estandar=$_POST["cmb_estandar"];
				$area=$_POST["cmb_area"];
				//Escribimos la consulta que rescata el id del incentivo
				$stm_sql = "SELECT id_incentivo FROM incentivos_actividades WHERE area='$area' AND estandar='$estandar'";
				//Ejecutar la Sentencia creada
				$rs = mysql_query($stm_sql);
				//Obtener el incentivo consultado en una variable para su mejor manejo
				$incentivo=mysql_fetch_array($rs);
				//Recoger el valor obtenido en la consulta en la variable de incentivos
				$inc=$incentivo["id_incentivo"];
				//Encabezado de la Tabla
				$msg="Actividades del Est&aacute;ndar $estandar para $area";
			}
			else{
				if (!isset($_POST["sbt_agregarNuevo"]) && !isset($_POST["sbt_modificarActividad"]))
					$inc=$_POST["hdn_incentivo"];
				else
					$inc=$_POST["hdn_inc"];
				$area=obtenerDato("bd_desarrollo", "incentivos_actividades", "area", "id_incentivo", $inc);
				$estandar=obtenerDato("bd_desarrollo", "incentivos_actividades", "estandar", "id_incentivo", $inc);
				$msg="Actividades del Est&aacute;ndar $estandar para $area";
				//Realizar la conexion a la BD de Desarrollo
				$conn = conecta("bd_desarrollo");
			}
			//Crear la sentencia SQL que obtendra los datos del detalle del estandar consultado
			$stm_sql = "SELECT numero,concepto,costo FROM detalle_incentivos WHERE incentivos_actividades_id_incentivo='$inc'";
			//Ejecutar la Sentencia para obtener los datos del proveedor seleccionado
			$rs = mysql_query($stm_sql);
			//Acumular costo por estandar
			$valorE=0;
			//Confirmar que la consulta de datos fue realizada con exito.
			if($datos=mysql_fetch_array($rs)){
				//Desplegar los resultados de la consulta en una tabla
				echo "
					<table cellpadding='5' width='100%'>
					<caption class='titulo_etiqueta'>$msg</caption>	
					<thead>
						<tr>
							<th class='nombres_columnas' align='center' width='15%'>SELECCIONAR</th>
							<th class='nombres_columnas' align='center'>ACTIVIDAD</th>
							<td class='nombres_columnas' align='center' width='15%'>VALOR</td>
						</tr>
					</thead>";
				$nom_clase = "renglon_gris";
				$cont = 1;
				do{	
					echo "	<tr>";
					$costo=number_format($datos["costo"],2,".",",");
					?>
					<td class="nombres_filas" align="center"><input type="radio" name="rdb_actividad" id="rdb_actividad" value="<?php echo $datos["numero"];?>" title="Seleccionar Actividad del Est&aacute;ndar"/></td>
					<?php
					echo "
							<td class='$nom_clase' align='left'>$datos[concepto]</td>
							<td class='$nom_clase' align='center'>$$costo</td>
							</tr>";
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					$valorE=$valorE+str_replace(",","",$costo);
				}while($datos=mysql_fetch_array($rs));
				$valorE=number_format($valorE,2,".",",");
				echo "
				<tr>
				<td colspan='2' align='right'>&nbsp;</td>
				<td class='nombres_columnas' align='center'>$$valorE</td>
				<tr>
				</table>
				<input type='hidden' name='hdn_incentivo' value='$inc'/>
				";
				$resultado=0;
			}
			else{
				if (isset($_POST["txt_nuevaArea"]) && $_POST["txt_nuevaArea"]!="")
					$txt_nuevaArea=strtoupper($_POST["txt_nuevaArea"]);
				else
					$txt_nuevaArea=$_POST["cmb_area"];
				if ($_POST["txt_nuevoEstandar"]!="")
					$txt_nuevoEstandar=strtoupper($_POST["txt_nuevoEstandar"]);
				else
					$txt_nuevoEstandar=$_POST["cmb_estandar"];
				echo "<input type='hidden' name='hdn_area' value='$txt_nuevaArea'/>";
				echo "<input type='hidden' name='hdn_estandar' value='$txt_nuevoEstandar'/>";
				//Si no hay provedores registrados por Compras, se indica esto al usuario
				echo "<br><br><br><br><p align='center' class='msje_correcto'>No hay Actividades Registradas en el Est&aacute;ndar Solicitado</u></em></p>";
				$resultado=1;
			}
			//Cerrar la conexion con la BD 
			mysql_close($conn);
		}
		else{
			if (isset($_POST["txt_nuevaArea"]) && $_POST["txt_nuevaArea"]!="")
				$txt_nuevaArea=strtoupper($_POST["txt_nuevaArea"]);
			else
				$txt_nuevaArea=$_POST["cmb_area"];
			if (isset($_POST["txt_nuevoEstandar"]) && $_POST["txt_nuevoEstandar"]!="")
				$txt_nuevoEstandar=strtoupper($_POST["txt_nuevoEstandar"]);
			else			
				$txt_nuevoEstandar=strtoupper($_POST["cmb_estandar"]);
			//Si no hay provedores registrados por Compras, se indica esto al usuario
			echo "<br><br><br><br><p align='center' class='msje_correcto'>Agregue las Actividades del Est&aacute;ndar Mediante el Bot&oacute;n Agregar</u></em></p>";
			$resultado=2;
			echo "<input type='hidden' name='hdn_area' value='$txt_nuevaArea'/>";
			echo "<input type='hidden' name='hdn_estandar' value='$txt_nuevoEstandar'/>";
			}
		return $resultado;
	}//Fin de la Funcion de mostrarActividades
	
	//Funcion que borra lac actividades del Estándar de Bonos
	function borrarActividad(){
		//Variable bandera para revisar el estado del proceso de borrado
		$band=0;
		//Realizar la conexion a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		//Recuperar las variables de actividades
		$incentivo=$_POST["hdn_incentivo"];
		$actividad=$_POST["rdb_actividad"];
		//Escribimos la consulta que rescata el id del incentivo
		$stm_sql = "DELETE FROM detalle_incentivos WHERE incentivos_actividades_id_incentivo='$incentivo' AND numero='$actividad'";
		//Ejecutar la Sentencia creada
		$rs = mysql_query($stm_sql);
		//Si el proceso de borrado se completo, indicarlo mediante la bandera
		if ($rs){
			$band=1;
			//Cerrar la conexion con la BD 
			mysql_close($conn);
			//Registrar la Operacion en la tabla de movimientos
			registrarOperacion("bd_desarrollo",$incentivo,"borrarActividadIncentivo",$_SESSION['usr_reg']);
		}
		if ($band==1)
			$band=verificarEstandar($incentivo);
		return $band;
	}//Fin de la funcion borrarActividad()
	
	//Funcion que modifica lac actividades del Estándar de Bonos
	function modificarActividad(){
		//Variable bandera para revisar el estado del proceso de modificacion
		$band=0;
		//Realizar la conexion a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		//Recuperar las variables de actividades
		$costo=str_replace(",","",$_POST["txt_costo"]);
		$actividad=strtoupper($_POST["txa_actividad"]);
		$incentivo=$_POST["hdn_inc"];
		$numero=$_POST["hdn_numero"];

		//Escribimos la consulta que rescata el id del incentivo
		$stm_sql = "UPDATE detalle_incentivos SET costo='$costo',concepto='$actividad' WHERE incentivos_actividades_id_incentivo='$incentivo' AND numero='$numero'";

		//Ejecutar la Sentencia creada
		$rs = mysql_query($stm_sql);
		//Si el proceso de borrado se completo, indicarlo mediante la bandera
		if ($rs){
			$band=1;
			//Cerrar la conexion con la BD 
			mysql_close($conn);
			//Registrar la Operacion en la tabla de movimientos
			registrarOperacion("bd_desarrollo",$incentivo,"modificarActividadIncentivo",$_SESSION['usr_reg']);
			//Redireccionar a la Pagina de exito despues de 5 segundos				
			//echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
			$error = mysql_error();
			//Cerrar la conexion con la BD, solo se cierra en este caso ya que en caso de Exito, la conexion la cierra el Registrar Operacion
			mysql_close($conn);
			echo "<meta http-equiv='refresh' content='5;url=error.php?err=$error'>";
		}
		return $band;
	}//Fin de la funcion modificarActividad()
	
	//Funcion que agregar la actividad nueva al estandar
	function agregarActividad(){
		//Recuperar las variables de actividades
		$costo=str_replace(",","",$_POST["txt_costo"]);
		$actividad=strtoupper($_POST["txa_actividad"]);
		$incentivo=$_POST["hdn_inc"];
		$numero=$_POST["hdn_numero"];
		//Verificar si la actividad es totalmente nueva, para agregarla al estandar
		$nuevo=$_POST["hdn_nuevo"];
		if ($nuevo=="si")
			agregarEstandar($incentivo,$_POST["hdn_est"],$_POST["hdn_area"]);
		//Realizar la conexion a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		//Escribimos la consulta que rescata el id del incentivo
		$stm_sql = "INSERT INTO detalle_incentivos(incentivos_actividades_id_incentivo,numero,concepto,costo) VALUES ('$incentivo',$numero,'$actividad',$costo)";
		//Ejecutar la Sentencia creada
		$rs = mysql_query($stm_sql);
		//Si el proceso de borrado se completo, indicarlo mediante la bandera
		if ($rs){
			//Registrar la Operacion en la tabla de movimientos
			registrarOperacion("bd_desarrollo",$incentivo,"agregarActividadIncentivo",$_SESSION['usr_reg']);
		}
		else{
			//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
			$error = mysql_error();
			//Cerrar la conexion con la BD, solo se cierra en este caso ya que en caso de Exito, la conexion la cierra el Registrar Operacion
			mysql_close($conn);
			//echo "<meta http-equiv='refresh' content='5;url=error.php?err=$error'>";
		}
	}//Fin de la funcion modificarActividad()
	
	//Funcion que calcula los nuevos ID
	function calculaIDNuevo($area){
		//Realizar la conexion a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		//Definir las  letras en la Id de la Requisicion
		$id_cadena = "INC";
		//Obtener el mes actual y el año actual para ser agregado en la consulta y asi obtener las requisiciones del mes en curso del año en curso
		$area = substr($area,0,3);
		//Concatenar el id de Cadena con el Area
		$id_cadena.=$area;
		//Crear la sentencia para obtener el numero de Requisicion registradas 
		$stm_sql = "SELECT COUNT(id_incentivo) AS cant FROM incentivos_actividades WHERE id_incentivo LIKE 'INC$area%'";
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
	}//Fin de calculaIDNuevo($area)
	
	//Funcion que agrega un nuevo Estandar
	function agregarEstandar($incentivo,$estandar,$area){
		//Realizar la conexion a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		//Escribimos la consulta que ingresa el idIncentivo,Area y Estandar
		$stm_sql = "INSERT INTO incentivos_actividades(id_incentivo,area,estandar) VALUES ('$incentivo','$area','$estandar')";
		//Ejecutar la Sentencia creada
		$rs = mysql_query($stm_sql);
		//Si el proceso de borrado se completo, indicarlo mediante la bandera
		if ($rs){
			//Registrar la Operacion en la tabla de movimientos
			registrarOperacion("bd_desarrollo",$incentivo,"agregarEstandarIncentivo",$_SESSION['usr_reg']);
		}
		else{
			//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
			$error = mysql_error();
			//Cerrar la conexion con la BD, solo se cierra en este caso ya que en caso de Exito, la conexion la cierra el Registrar Operacion
			mysql_close($conn);
			echo "<meta http-equiv='refresh' content='5;url=error.php?err=$error'>";
		}
	}//Fin de agregarEstandar($incentivo)
	
	//Funcion que verifica si hay estandares sin actividades para eliminarlos
	//3 -> Si el valor de regreso es 3, el incentivo no se borro por completo
	//4 -> Si el valor de regreso es 4, el incentivo se borro totalmente
	function verificarEstandar($incentivo){
		$flag=3;
		$conn=conecta("bd_desarrollo");
		$stm_sql="SELECT COUNT(incentivos_actividades_id_incentivo) AS existe FROM detalle_incentivos WHERE incentivos_actividades_id_incentivo='$incentivo'";
		$rs=mysql_query($stm_sql);
		$cantidad=mysql_fetch_array($rs);
		if ($cantidad["existe"]==0){
			$stm_sql="DELETE FROM incentivos_actividades WHERE id_incentivo='$incentivo'";
			$rs=mysql_query($stm_sql);
			if ($rs)
				$flag=4;
		}
		return $flag;
	}//Fin de verificarEstandar($incentivo)
?>