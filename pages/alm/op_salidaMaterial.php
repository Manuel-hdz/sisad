<?php

/**
 * Nombre del M�dulo: Almac�n                                               
 * Nombre Programador: Miguel Angel Garay Castro                            
 * Fecha: 12/Octubre/2010                                      			
 * Descripci�n: Este archivo contiene funciones para salida de  la informaci�n relacionada con el formulario de salidaMaterial en la BD
 **/




function agregarMaterialASalida($txt_clave, $txt_cantSalida, $cmb_idEquipo,$txt_existencia, $cmb_tipoMoneda = "PESOS")
{
	//Quitar la coma en el costo unitario del material, para poder realziar la operaciones requeridas.
				$txt_clave = strtoupper($txt_clave);
				$conexion = conecta("bd_almacen");
				$stm_sql_ent = "SELECT T1.`fecha_entrada` , T2.`materiales_id_material` , T2.`nom_material` , T2.`unidad_material` , T2.`costo_unidad` , T2.`tipo_moneda` , ROUND( SUM(  `cant_restante` ) , 2 ) AS cantidad_existente, GROUP_CONCAT( CAST(  `entradas_id_entrada` AS CHAR ) ) AS entradas, GROUP_CONCAT( CAST(  ROUND(`cant_restante`,2) AS CHAR ) ) AS cantidades_restantes
						FROM  `entradas` AS T1
						JOIN  `detalle_entradas` AS T2 ON  `id_entrada` =  `entradas_id_entrada` 
						WHERE  `materiales_id_material` =  '$txt_clave'
						AND ROUND(  `cant_restante` , 2 ) >0
						GROUP BY  `tipo_moneda` ,  `costo_unidad` ,  `unidad_material` 
						ORDER BY  `T1`.`fecha_entrada` DESC ,  `T2`.`cant_restante` DESC ";
				$rs_ent = mysql_query($stm_sql_ent);
				if ($rs_ent) {
					while ($datos_ent = mysql_fetch_array($rs_ent)) {
						$cant_intro = 0;
						if ($datos_ent['cantidad_existente'] <= $txt_cantSalida) {
							$cant_intro = $datos_ent['cantidad_existente'];
							$txt_cantSalida -= $cant_intro;
						} else {
							$cant_intro = $txt_cantSalida;
							$txt_cantSalida = 0;
						}
						if ($cant_intro > 0) {
							if (isset($_SESSION['datosSalida'])) {
								//Obtener el nombre del material para agregarlo al arreglo
								$nombre = obtenerDato("bd_almacen", "materiales", "nom_material", "id_material", $txt_clave, "costo_unidad");
								$categoria = obtenerDato("bd_almacen", "materiales", "categoria", "id_material", $txt_clave);
								//Verificar que las cantidads registradas de salidad de un solo material no excedan su existencia, en el caso de que no se alcance a cubrir la demanda, no se agrega el registro y se manda un msg de alerta
								if (revExistenciaMaterial($datosSalida, "clave", $txt_clave, $cant_intro, $nombre)) {
									$band = 0;
									$cont = 0;
									foreach ($_SESSION['datosSalida'] as $ind => $materiales) {
										if ($materiales["clave"] == $txt_clave && $materiales["tipoMoneda"] == $datos_ent['tipo_moneda'] && $materiales["costoUnidad"] == $datos_ent['costo_unidad'] && $materiales["idEquipo"] == $cmb_idEquipo) {
											$band = 1;
											if ($datos_ent['cantidad_existente'] != $_SESSION['datosSalida'][$ind]["cantSalida"]) {
												$txt_cantSalida += $cant_intro - ($datos_ent['cantidad_existente'] - $_SESSION['datosSalida'][$ind]["cantSalida"]);
												if ($cant_intro + $_SESSION['datosSalida'][$ind]["cantSalida"] > $datos_ent['cantidad_existente'])
													$cant_intro = $datos_ent['cantidad_existente'] - $_SESSION['datosSalida'][$ind]["cantSalida"];
												$_SESSION['datosSalida'][$ind]["cantSalida"] = $cant_intro + $_SESSION['datosSalida'][$ind]["cantSalida"];
												$_SESSION['datosSalida'][$ind]["costoTotal"] = number_format($_SESSION['datosSalida'][$ind]["cantSalida"] * $_SESSION['datosSalida'][$ind]["costoUnidad"], 2);
											} else {
												$txt_cantSalida += $cant_intro;
											}
										}
										$cont++;
									}
									if ($band == 0) {
										//Guardar los datos en el arreglo
										$datosSalida[] = array(
											"clave" => $txt_clave,
											"nombre" => $nombre,
											"existencia" => $txt_existencia,
											"cantSalida" => $cant_intro,
											"costoUnidad" => number_format($datos_ent['costo_unidad'], 2),
											"costoTotal" => number_format(($cant_intro * $datos_ent['costo_unidad']), 2),
											"idEquipo" => $cmb_idEquipo,
											"catMaterial" => $categoria,
											"tipoMoneda" => $datos_ent['tipo_moneda'],
											"cantRestante" => $datos_ent['cantidad_existente'],
											"idEntradas" => $datos_ent['entradas'],
											"cantidadEntradas" => $datos_ent['cantidades_restantes']
										);
										$_SESSION['datosSalida'] = $datosSalida;
									}
								}
							}
							//Si no esta definido el arreglo $datosEntrada definirlo y agregar el primer registro
							else {
								//Obtener el nombre del material para agregarlo al arreglo
								$nombre = obtenerDato("bd_almacen", "materiales", "nom_material", "id_material", $txt_clave, "costo_unidad");
								$categoria = obtenerDato("bd_almacen", "materiales", "categoria", "id_material", $txt_clave);
								$datosSalida = array(array(
									"clave" => $txt_clave,
									"nombre" => $nombre,
									"existencia" => $txt_existencia,
									"cantSalida" => $cant_intro,
									"costoUnidad" => number_format($datos_ent['costo_unidad'], 2),
									"costoTotal" => number_format(($cant_intro * $datos_ent['costo_unidad']), 2),
									"idEquipo" => $cmb_idEquipo,
									"catMaterial" => $categoria,
									"tipoMoneda" => $datos_ent['tipo_moneda'],
									"cantRestante" => $datos_ent['cantidad_existente'],
									"idEntradas" => $datos_ent['entradas'],
									"cantidadEntradas" => $datos_ent['cantidades_restantes']
								));
								$_SESSION['datosSalida'] = $datosSalida;
								//Crear el ID de la Entrada de Material
								$_SESSION['id_salida'] = obtenerIdSalida();
							}
						}
					}
				}
}
//Esta funcion genera la ID de la salida del material en base a las salidas registradas en la BD
function obtenerIdSalida()
{
	//Realizar la conexion a la BD de Almacen
	$conn = conecta("bd_almacen");

	//Definir las dos letras en la Id de la Salida
	$id_cadena = "SM";

	//Obtener el mes y el a�o
	$fecha = date("m-Y");
	$id_cadena .= substr($fecha, 0, 2) . substr($fecha, 5, 2);

	//Obtener el mes actual y el a�o actual para ser agregados en la consulta y asi obtener las entradas del mes y a�o en curso
	$mes = substr($fecha, 0, 2);
	$anio = substr($fecha, 5, 2);

	//Crear la sentencia para obtener el numero de entradas registradas en la BD
	$stm_sql = "SELECT COUNT(id_salida) AS cant FROM salidas WHERE id_salida LIKE 'SM$mes$anio%'";
	$rs = mysql_query($stm_sql);
	if ($datos = mysql_fetch_array($rs)) {
		$cant = $datos['cant'] + 1;
		if ($cant > 0 && $cant < 10)
			$id_cadena .= "000" . $cant;
		if ($cant > 9 && $cant < 100)
			$id_cadena .= "00" . $cant;
		if ($cant > 99 && $cant < 1000)
			$id_cadena .= "0" . $cant;
		if ($cant >= 1000)
			$id_cadena .= $cant;
	}
	//Cerrar la conexion con la BD		
	mysql_close($conn);

	return $id_cadena;
} //Fin de la Funcion obtenerIdEntrada()

function descontarEntradas($material, $entrada, $idEntradas, $cantidadEntrada)
{
	$idEntr = explode(",", $idEntradas);
	$cantidades = explode(",", $cantidadEntrada);
	for ($i = 0; $i < count($cantidades); $i++) {
		$cant_intro = 0;
		if ($cantidades[$i] <= $entrada) {
			$cant_intro = $cantidades[$i];
			$entrada -= $cant_intro;
		} else {
			$cant_intro = $entrada;
			$entrada = 0;
		}
		$sql_upd = "UPDATE detalle_entradas SET cant_restante = ROUND(cant_restante,2) - $cant_intro 
						WHERE entradas_id_entrada = '$idEntr[$i]' AND materiales_id_material = '$material' and ROUND(cant_restante,2) = '$cantidades[$i]' 
						LIMIT 1";
		$rs_upd = mysql_query($sql_upd);
		if (!$rs_upd) {
			echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>" . mysql_error();
			break;
		}
	}
}

//Agregar el registro de la Salida de Materiales a las tablas de detalle_salidas y salidas
function guardarCambios($txt_deptoSolicitante, $txt_solicitante, $txt_destino, $txt_fechaSalida, $cmb_turno, $txt_noVale, $cmb_cuentas, $cmb_sc, $moneda)
{
	//Si la bandera se activa significa que hubo errores
	$band = 0;
	//Realizar la conexion a la BD de Almacen
	$conn = conecta("bd_almacen");
	//Registrar todos los materiales dados de alta en el arreglo $datosSalida
	foreach ($_SESSION['datosSalida'] as $ind => $material) {
		//Registrar la salida a la existencia del Material mediante el Id del mismo
		$cond = registrarSalida($material['clave'], $material['cantSalida']);
		cambiarCategoria($material['clave'], $material['catMaterial']);
		$_SESSION['id_salida'] = obtenerIdSalida();
		$txt_noVale = $_SESSION['id_salida'];
		if ($cond) {
			$nom_material = obtenerDato("bd_almacen", "materiales", "nom_material", "id_material", $material["clave"]);
			$unidad_medida = obtenerDato("bd_almacen", "unidad_medida", "unidad_despacho", "materiales_id_material", $material["clave"]);
			//Realizar la conexion a la BD de Almacen
			$conn = conecta("bd_almacen");
			$costoUnidad = str_replace(",", "", $material['costoUnidad']);
			$costoTotal = str_replace(",", "", $material['costoTotal']);

			//Crear la sentencia para realizar el registro de los datos del detalle de la Salida de Material
			$stm_sql = "INSERT INTO detalle_salidas (salidas_id_salida,materiales_id_material, nom_material, unidad_material, cant_salida, 
							costo_unidad, costo_total, id_equipo_destino, moneda, entradas) 
							VALUES('$_SESSION[id_salida]','$material[clave]','$nom_material','$unidad_medida','$material[cantSalida]',
							'$costoUnidad','$costoTotal','$material[idEquipo]','$material[tipoMoneda]','$material[idEntradas]')";

			//Ejecutar la sentencia previamente creada para agregar cada material a la tabla de detalle_salida
			$rs = mysql_query($stm_sql);
			if (!$rs)
				$band = 1;
			else
				descontarEntradas($material['clave'], $material['cantSalida'], $material['idEntradas'], $material['cantidadEntradas']);
		} else {
			$band = 1;
		}

		//Romper el proceso de registro del detalle de la salida en el caso de que existan errores	
		if ($band == 1)
			break;
	}

	//Pasar a Mayusculas los datos de la Salida
	$txt_solicitante = strtoupper($txt_solicitante);
	$txt_destino = strtoupper($txt_destino);
	$txt_deptoSolicitante = strtoupper($txt_deptoSolicitante);
	$cmb_cuentas = strtoupper($cmb_cuentas);
	$cmb_sc = strtoupper($cmb_sc);

	if ($band == 0) {
		//Obtener el costo total de los registros en la Salida de Material
		$costoTotalSalida = obtenerSumaRegistrosES($_SESSION['datosSalida'], "costoTotal");
		//Crear la sentencia para almacenar los datos de la entrada en la BD
		$stm_sql = "INSERT INTO salidas (id_salida,fecha_salida,solicitante,destino,cuentas,subcuentas,depto_solicitante,turno,costo_total,no_vale,moneda)
						VALUES('$_SESSION[id_salida]','" . modFecha($txt_fechaSalida, 3) . "','$txt_solicitante','$txt_destino','$cmb_cuentas','$cmb_sc','$txt_deptoSolicitante',
						'$cmb_turno',$costoTotalSalida,'$txt_noVale','$moneda')";

		if (isset($_POST["id_kiosco"])) {
			$id_kiosco = $_POST["id_kiosco"];
			$stm_sql = "INSERT INTO salidas (id_salida,fecha_salida,solicitante,destino,cuentas,subcuentas,depto_solicitante,turno,costo_total,no_vale,moneda,id_vale_kiosco)
							VALUES('$_SESSION[id_salida]','" . modFecha($txt_fechaSalida, 3) . "','$txt_solicitante','$txt_destino','$cmb_cuentas','$cmb_sc','$txt_deptoSolicitante',
							'$cmb_turno',$costoTotalSalida,'$txt_noVale','$moneda','$id_kiosco')";
		}
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);

		//Confirmar que la insercion de datos fue realizada con exito.
		if ($rs) {
			//Registrar la Operacion en la Bit�cora de Movimientos
			registrarOperacion("bd_almacen", $_SESSION['id_salida'], "SalidaMaterial", $_SESSION['usr_reg']);
			if (isset($_POST["id_kiosco"])) {
				$id_kiosco = $_POST["id_kiosco"];
				entregarVale($id_kiosco);
			}
?>
			<script type='text/javascript' language='javascript'>
				setTimeout("window.open('../../includes/generadorPDF/valeSalida.php?id=<?php echo $_SESSION['id_salida']; ?>', '_blank','top=0, left=0, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')", 4000);
			</script>
		<?php
			//Vaciar la informaci�n almacenada en la SESSION
			unset($_SESSION['datosSalida']);
			unset($_SESSION['id_salida']);
			echo "<meta http-equiv='refresh' content='5;url=exito.php'>";
		} else {
			//Redireccionar a una pagina de error
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	} else {
		//Redireccionar a una pagina de error
		$error = "No se pudieron almacenar todos los registros del Detalle de Salidas";
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
	}
	//Cerrar la conexion con la BD		
	//La conexion a la Bd se cierra en la funcion registrarOperacion("bd_almacen",$_SESSION['id_salida'],"salida",$_SESSION['usr_reg']);
}


//Esta funci�n se encarga de registrar la cant. de salida a la existencia de los materiales
function registrarSalida($clave, $cantSalida)
{
	//Crear la sentencia para actualizar la existencia del material con la entrada
	$stm_sql = "UPDATE materiales SET existencia=existencia-$cantSalida WHERE id_material='$clave'";
	//Ejecutar la sentencia
	$rs = mysql_query($stm_sql);
	//Comprobar el resultado de la actualizacion
	if ($rs)
		return true;
	else
		return false;
} //Cierre de la funcion registrarSalida($clave,$cantSalida)

function cambiarCategoria($clave, $cat)
{
	//Crear la sentencia para actualizar la existencia del material con la entrada
	$stm_sql = "UPDATE materiales SET categoria='$cat' WHERE id_material='$clave'";
	//Ejecutar la sentencia
	$rs = mysql_query($stm_sql);
	//Comprobar el resultado de la actualizacion
	if ($rs)
		return true;
	else
		return false;
}

//Desplegar los materiales registrados en la Salida de Material
function mostrarRegistros($datosSalida, $opc)
{
	echo "				
		<table cellpadding='5' width='100%'>";
	if (isset($_SESSION['msg_salida'])) {
		echo "<caption><font color='#FF0000'><strong>$_SESSION[msg_salida]</strong></font></caption>";
		unset($_SESSION['msg_salida']);
	}

	echo "      			
			<tr>
				<td class='nombres_columnas' align='center'>CLAVE</td>
        		<td class='nombres_columnas' align='center'>NOMBRE (DESCRIPCION)</td>
			    <td class='nombres_columnas' align='center'>EXISTENCIA</td>
				<td class='nombres_columnas' align='center'>CANT. SALIDA</td>
				<td class='nombres_columnas' align='center'>COSTO UNIDAD</td>
				<td class='nombres_columnas' align='center'>SUBTOTAL</td>
				<td class='nombres_columnas' align='center'>ID EQUIPO</td>
				<td class='nombres_columnas' align='center'>CATEGORIA</td>
				<td class='nombres_columnas' align='center'>TIPO MONEDA</td>";

	//Si la Solicitud viene de la primera pagina de Salida de Material mostrar el icono que permite editar el Registro
	if ($opc == 1 || $opc == 0)
		echo "<td class='nombres_columnas' align='center'>EDITAR</td>";
	echo "</tr>";
	$nom_clase = "renglon_gris";
	$cont = 1;
	foreach ($datosSalida as $ind => $material) {
		echo "<tr>";
		foreach ($material as $key => $value) {
			switch ($key) {
				case "clave":
					echo "<td class='nombres_filas' align='center'>$value</td>";
					break;
				case "nombre":
					echo "<td class='$nom_clase'>$value</td>";
					break;
				case "existencia":
					echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
				case "cantSalida":
					echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
				case "costoUnidad":
					echo "<td class='$nom_clase' align='center'>$" . $value . "</td>";
					break;
				case "costoTotal":
					echo "<td class='$nom_clase' align='center'>$" . $value . "</td>";
					break;
				case "idEquipo":
					if ($value != "")
						echo "<td class='$nom_clase' align='center'>$value</td>";
					else
						echo "<td class='$nom_clase' align='center'>N/A</td>";
					break;
				case "catMaterial":
					if ($opc == 2) {
						$cat = strtoupper(obtenerDatoTabla("categorias_mat", "descripcion", "id_categoria", $value, "bd_almacen"));
						echo "<td class='$nom_clase' align='center'>$cat</td>";
					} else {
						$clave_cat = strtoupper(obtenerDatoTabla("materiales", "categoria", "id_material", $material['clave'], "bd_almacen"));
						if ($clave_cat != "SIN CATEGORIA") {
							$cat = strtoupper(obtenerDatoTabla("categorias_mat", "descripcion", "id_categoria", $clave_cat, "bd_almacen"));
							echo "<td class='$nom_clase' align='center'>$cat</td>";
							echo "<input type='hidden' name='cmb_catMat$cont' id='cmb_catMat$cont' value='1'/>";
						} else {
							echo "
								<td class='$nom_clase' align='center'>
									<select name='cmb_catMat$cont' id='cmb_catMat$cont'>
										<option value=''>Categorias</option>
								";
							$conn1 = conecta("bd_almacen");
							$rs_cat = mysql_query("SELECT * FROM categorias_mat WHERE habilitado='SI' ORDER BY descripcion");
							if ($catMat = mysql_fetch_array($rs_cat)) {
								do {
									if ($value == $catMat["id_categoria"]) {
										echo "
												<option value='$catMat[id_categoria]' selected='selected' >$catMat[descripcion]</option>
												";
									} else {
										echo "
												<option value='$catMat[id_categoria]'>$catMat[descripcion]</option>
												";
									}
								} while ($catMat = mysql_fetch_array($rs_cat));
							}
							echo "
									</select>
								</td>
								";
						}
					}
					break;
				case "tipoMoneda":
					echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
			}
		}

		if (isset($_POST["id_empl"])) {
			if (isset($_POST["vale_kiosco"])) {
				$id_empl = explode(",", $_POST["vale_kiosco"]);
				$id_kiosco = $id_empl[0];
				$id_empl = $id_empl[1];
			} else {
				$id_empl = $_POST["id_empl"];
				$id_kiosco = $_POST["id_kiosco"];
			}
		}

		//Si la Solicitud viene de la primera pagina de Salida de Material mostrar el icono que permite editar el Registro
		if ($opc == 1) {
			//Colocar la Imagen para permitir la Edicion del registro seleccionado
		?><td class="<?php echo $nom_clase; ?>">
				<?php if (isset($_POST["id_empl"])) { ?>
					<img src="../../images/editar.png" width="30" height="25" border="0" title="Modificar Registro"
						onclick="location.href='frm_editarRegistros.php?origen=salida&pos=<?php echo $cont - 1; ?>&id_empl=<?php echo $id_empl; ?>&id_kiosco=<?php echo $id_kiosco; ?>'" />
				<?php } else { ?>
					<img src="../../images/editar.png" width="30" height="25" border="0" title="Modificar Registro"
						onclick="location.href='frm_editarRegistros.php?origen=salida&pos=<?php echo $cont - 1; ?>'" />
				<?php } ?>
			</td><?php
				}
				//Si la Solicitud viene de la primera pagina de Salida de Material mostrar el icono que permite editar el Registro
				if ($opc == 0) {
					//Colocar la Imagen para permitir la Edicion del registro seleccionado
					?><td class="<?php echo $nom_clase; ?>">
				<img src="../../images/editar.png" width="30" height="25" border="0" title="Modificar Registro"
					onclick="location.href='frm_editarRegistros.php?origen=salida&pos=<?php echo $cont - 1; ?>&cb=1'" />
			</td><?php
				}

				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if ($cont % 2 == 0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
				echo "</tr>";
			}
			echo "</table>";
		} //Fin de la funcion mostrarRegistros($datosSalida)


		//Esta funci�n verifica que la cantidad de salida de un material no exceda la existencia del mismo
		function revExistenciaMaterial($arr, $ind, $valBuscar, $cant, $nombre)
		{
			$res = 1;
			$cond = false;

			$cantTotal = 0;
			$existencia = 0;
			//Busca mediante la clave en todo el arreglo
			foreach ($arr as $indice => $material) {
				if ($material[$ind] == $valBuscar) {
					$cond = true;
					$cantTotal += $material['cantSalida'];
					$existencia = $material['existencia'];
				}
			}

			if ($cond) {
				$cantTotal += $cant;
				if ($cantTotal > $existencia) {
					$_SESSION['msg_salida'] = "El material " . $nombre . " no alcanza a cubrir la cantidad de salida solicitada";
					$res = 0;
				}
			}

			if ($res == 1)
				return true;
			else
				return false;
			//Regeresa TRUE cuando las cantidades de salida de un solo material no exceden su existencia. y regresa FALSO cuando se exceden y se despliega el mensaje
		}


		//Esta funci�n obtiene la cantidad acumulada de salida de un material para saber si no excede la existencia del mismo
		function obtenerExistenciaMaterial($arr, $ind, $valBuscar)
		{
			$cantTotal = 0;
			//Busca mediante la clave en todo el arreglo
			foreach ($arr as $indice => $material) {
				if ($material[$ind] == $valBuscar)
					$cantTotal += $material['cantSalida'];
			}
			return $cantTotal;
		}


		function regSalidaMatSeguridad()
		{
			//Variable para comprobar la insercion de datos
			$res = 1;
			//Obtenemos el ID de la salida que corresponde
			$salida = obtenerIdSalida();
			//Recuperar los datos del POST
			$noVale = $salida;
			$fechaE = modFecha($_POST['txt_fechaEntrega'], 3);
			$rfc = strtoupper($_POST['cmb_nombre']);
			$destino = strtoupper($_POST['txt_destino']);
			$turno = strtoupper($_POST['cmb_turno']);
			$departamento = strtoupper($_POST["hdn_categoria"]);
			//Obtener el Nombre y la CURP del empleado de la Base de Datos de Recursos Humanos para agregarlos a la BD de Almacen
			$nombre = obtenerNombreEmpleado($rfc);
			$curp = obtenerDato("bd_recursos", "empleados", "curp", "rfc_empleado", $rfc);
			//Conectarse a la BD de Almacen
			/*
		if(isset($_POST["id_kiosco"])){
			$cc = centrocostosVale($_POST["id_kiosco"],"id_control_costos");
			$cuenta = centrocostosVale($_POST["id_kiosco"],"id_cuentas");
			$subcuenta = centrocostosVale($_POST["id_kiosco"],"id_subcuentas");
		}
		*/
			$conn = conecta("bd_almacen");
			//Preparamos el registro de salida en la Tabla Salidas con la siguiente sentencia

			if (isset($_POST["id_kiosco"])) {
				$id_kiosco = $_POST["id_kiosco"];
				$stm_sql = "INSERT INTO salidas (id_salida,fecha_salida,solicitante,destino,cuentas,subcuentas,depto_solicitante,turno,costo_total,no_vale,moneda,id_vale_kiosco, tipo) 
						VALUES ('$salida','$fechaE','$nombre','$destino','','','$departamento','$turno',0,'$noVale','','$id_kiosco', 'EPP')";
			} else {
				$stm_sql = "INSERT INTO salidas (id_salida,fecha_salida,solicitante,destino,cuentas,subcuentas,depto_solicitante,turno,costo_total,no_vale,moneda,tipo) 
						VALUES ('$salida','$fechaE','$nombre','$destino','','','$departamento','$turno',0,'$noVale','','EPP')";
			}
			//Ejecutamos la sentencia previa
			$rs = mysql_query($stm_sql);

			//Contador de control sobre los registros
			$cont = 1;
			//Variable que tendra el total de la suma del costo de los materiales
			$total = 0;
			for ($i = 1; $i < $_POST['num_mat']; $i++) {
				if (isset($_POST["ckb" . $i]) && $_POST["ckb" . $i] != "no_valido") {
					$idMaterial = $_POST["ckb" . $i];
					$catMat = $_POST["cmb_catMat" . $i];
					//Obtener el nombre del Material asignado a la clave
					$nombreMat = obtenerDato("bd_almacen", "materiales", "nom_material", "id_material", $idMaterial);
					//Obtener la Unidad de Medida del Material asignado a la clave
					$unidadMedida = obtenerDato("bd_almacen", "unidad_medida", "unidad_despacho", "materiales_id_material", $idMaterial);
					//Obtenemos el costo individual del material que esta saliendo
					$costo = obtenerSeguridad($idMaterial);
					//Obtener el id del check que indica si es cambio
					//$ckbCambio=str_replace("ckb","ckb_",$ind);
					//Verificar si el Material sale con o sin cambio
					if (isset($_POST["ckb_" . $i])) {
						//Crear la sentencia para registrar la salida del equipo de Seguridad en la tabla de Detalles ES
						$stm_sql = "INSERT INTO detalle_es (empleados_rfc_empleado,empleados_curp,no_vale,fecha_entrega,materiales_id_material,nom_material,c_cambio,destino,turno) 
								VALUES ('$rfc','$curp','$noVale','$fechaE','$idMaterial','$nombreMat','SI','$destino','$turno')";
					} else {
						//Crear la sentencia para registrar la salida del equipo de Seguridad en la tabla de Detalles ES
						$stm_sql = "INSERT INTO detalle_es (empleados_rfc_empleado,empleados_curp,no_vale,fecha_entrega,materiales_id_material,nom_material,destino,turno) 
								VALUES ('$rfc','$curp','$noVale','$fechaE','$idMaterial','$nombreMat','$destino','$turno')";
					}
					//Crear la sentencia para registrar la salida del equipo de Seguridad en la tabla de Detalles Salidas
					if (isset($_POST["id_kiosco"])) {
						$id_kiosco = $_POST["id_kiosco"];
						$cantidad = consultarDetalleVale($id_kiosco, "canidad_pedida", $idMaterial);
					} else {
						$cantidad = 1;
					}
					$conn = conecta("bd_almacen");
					$costoT = $costo * $cantidad;
					$stm_sql_salida = "INSERT INTO detalle_salidas (salidas_id_salida,materiales_id_material,nom_material,unidad_material,cant_salida,costo_unidad,costo_total,id_equipo_destino,moneda) VALUES 
								  ('$salida','$idMaterial','$nombreMat','$unidadMedida',$cantidad,'$costo','$costoT','N/A','')";

					//Acumulamos el costo del material
					$total += $costoT;
					//Ejecutar la sentencias de registro de Salida
					$rs_salida = mysql_query($stm_sql_salida);
					$rs_salida_es = mysql_query($stm_sql);
					//Si la sentencia se ejecuto correctamente, disminuir la cantidad del Material
					if ($rs_salida && $rs_salida_es) {
						//Disminuimos la existencia de Material en 1
						registrarSalida($idMaterial, $cantidad);
						cambiarCategoria($idMaterial, $catMat);
					}
				}
			}
			//Verificar el resultado del proceso
			if ($res == 1) {
				//Actualizar el precio total de la Salida
				mysql_query("UPDATE salidas SET costo_total='$total' WHERE id_salida='$salida'");
				//Actualizar el estado de las posibles alertas de RH
				mysql_query("UPDATE alertas SET estado = 2 WHERE rfc_empleado = '$rfc' AND estado = 1 AND origen = 'RH'");
				//Cerrar la BD de Almacen
				mysql_close($conn);
				//Registrar la Operacion en la Bit�cora de Movimientos
				registrarOperacion("bd_almacen", $salida, "SalidaMaterial", $_SESSION['usr_reg']);
				if (isset($_POST["id_kiosco"])) {
					$id_kiosco = $_POST["id_kiosco"];
					entregarVale($id_kiosco);
				}
					?>
		<script type='text/javascript' language='javascript'>
			setTimeout("window.open('../../includes/generadorPDF/valeSalida.php?id=<?php echo $salida; ?>', '_blank','top=0, left=0, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')", 4000);
		</script>
<?php
				echo "<meta http-equiv='refresh' content='5;url=exito.php'>";
			} else {
				//Cerrar la BD de Almacen
				mysql_close($conn);
				//Redireccionar a una pagina de error
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}
		}

		//Funcion que obtiene el costo del material de Seguridad que esta saliendo
		function obtenerSeguridad($material)
		{
			//Realizar la conexion a la BD de Almacen
			$conexion = conecta("bd_almacen");
			$stm_sql = "SELECT costo_unidad FROM materiales WHERE id_material='$material'";
			$rs = mysql_query($stm_sql);
			if ($datos = mysql_fetch_array($rs))
				$costo = $datos['costo_unidad'];
			else
				$costo = 0.00;
			return $costo;
		}

		function partidasSalidaKiosco($id_vale_kiosco)
		{
			if (isset($_SESSION["datosSalida"])) {
				unset($_SESSION["datosSalida"]);
			}
			$conn = conecta("bd_kiosco");
			$stm_sql = "SELECT * 
					FROM  `detalle_vale_kiosco` 
					WHERE  `id_vale_kiosco` LIKE  '$id_vale_kiosco'";
			$rs = mysql_query($stm_sql);
			if ($datos = mysql_fetch_array($rs)) {
				do {
					$txt_clave = strtoupper($datos['id_material']);
					$txt_cantSalida = strtoupper($datos['canidad_pedida']);
					$txt_existencia = strtoupper($datos['existencia']);
					$conexion = conecta("bd_almacen");
					$stm_sql_ent = "SELECT T1.`fecha_entrada` , T2.`materiales_id_material` , T2.`nom_material` , T2.`unidad_material` , T2.`costo_unidad` , T2.`tipo_moneda` , ROUND( SUM(  `cant_restante` ) , 2 ) AS cantidad_existente, GROUP_CONCAT( CAST(  `entradas_id_entrada` AS CHAR ) ) AS entradas, GROUP_CONCAT( CAST(  ROUND(`cant_restante`,2) AS CHAR ) ) AS cantidades_restantes
								FROM  `entradas` AS T1
								JOIN  `detalle_entradas` AS T2 ON  `id_entrada` =  `entradas_id_entrada` 
								WHERE  `materiales_id_material` =  '$txt_clave'
								AND ROUND(  `cant_restante` , 2 ) >0
								GROUP BY  `tipo_moneda` ,  `costo_unidad` ,  `unidad_material` 
								ORDER BY  `T1`.`fecha_entrada` DESC ,  `T2`.`cant_restante` DESC ";
					$rs_ent = mysql_query($stm_sql_ent);

					if ($rs_ent) {
						while ($datos_ent = mysql_fetch_array($rs_ent)) {
							$cant_intro = 0;
							if ($datos_ent['cantidad_existente'] <= $txt_cantSalida) {
								$cant_intro = $datos_ent['cantidad_existente'];
								$txt_cantSalida -= $cant_intro;
							} else {
								$cant_intro = $txt_cantSalida;
								$txt_cantSalida = 0;
							}
							if ($cant_intro > 0) {
								if (isset($_SESSION['datosSalida'])) {
									$nombre = obtenerDato("bd_almacen", "materiales", "nom_material", "id_material", $txt_clave, "costo_unidad");
									$categoria = obtenerDato("bd_almacen", "materiales", "categoria", "id_material", $txt_clave);
									$band = 0;
									foreach ($_SESSION['datosSalida'] as $ind => $materiales) {
										if ($materiales["clave"] == $txt_clave && $materiales["tipoMoneda"] == $datos_ent['tipo_moneda'] && $materiales["costoUnidad"] == $datos_ent['costo_unidad']) {
											$band = 1;
											if ($datos_ent['cantidad_existente'] != $_SESSION['datosSalida'][$ind]["cantSalida"]) {
												$txt_cantSalida += $cant_intro - ($datos_ent['cantidad_existente'] - $_SESSION['datosSalida'][$ind]["cantSalida"]);
												if ($cant_intro + $_SESSION['datosSalida'][$ind]["cantSalida"] > $datos_ent['cantidad_existente'])
													$cant_intro = $datos_ent['cantidad_existente'] - $_SESSION['datosSalida'][$ind]["cantSalida"];
												$_SESSION['datosSalida'][$ind]["cantSalida"] = $cant_intro + $_SESSION['datosSalida'][$ind]["cantSalida"];
												$_SESSION['datosSalida'][$ind]["costoTotal"] = number_format($_SESSION['datosSalida'][$ind]["cantSalida"] * $_SESSION['datosSalida'][$ind]["costoUnidad"], 2);
											} else {
												$txt_cantSalida += $cant_intro;
											}
										}
									}
									if ($band == 0) {
										$datosSalida[] = array(
											"clave" => $txt_clave,
											"nombre" => $nombre,
											"existencia" => $txt_existencia,
											"cantSalida" => $cant_intro,
											"costoUnidad" => number_format($datos_ent['costo_unidad'], 2),
											"costoTotal" => number_format(($cant_intro * $datos_ent['costo_unidad']), 2),
											"idEquipo" => "N/A",
											"catMaterial" => $categoria,
											"tipoMoneda" => $datos_ent['tipo_moneda'],
											"cantRestante" => $datos_ent['cantidad_existente'],
											"idEntradas" => $datos_ent['entradas'],
											"cantidadEntradas" => $datos_ent['cantidades_restantes']
										);
										$_SESSION['datosSalida'] = $datosSalida;
									}
								} else {
									$nombre = obtenerDato("bd_almacen", "materiales", "nom_material", "id_material", $txt_clave, "costo_unidad");
									$categoria = obtenerDato("bd_almacen", "materiales", "categoria", "id_material", $txt_clave);
									$datosSalida = array(
										array(
											"clave" => $txt_clave,
											"nombre" => $nombre,
											"existencia" => $txt_existencia,
											"cantSalida" => $cant_intro,
											"costoUnidad" => number_format($datos_ent['costo_unidad'], 2),
											"costoTotal" => number_format(($cant_intro * $datos_ent['costo_unidad']), 2),
											"idEquipo" => "N/A",
											"catMaterial" => $categoria,
											"tipoMoneda" => $datos_ent['tipo_moneda'],
											"cantRestante" => $datos_ent['cantidad_existente'],
											"idEntradas" => $datos_ent['entradas'],
											"cantidadEntradas" => $datos_ent['cantidades_restantes']
										)
									);
									$_SESSION['datosSalida'] = $datosSalida;
								}
							}
						}
					}
				} while ($datos = mysql_fetch_array($rs));
			}
		}

		function entregarVale($id)
		{
			$conex = conecta("bd_kiosco");
			$stm_sql = "UPDATE alertas SET estado =2 WHERE id_vale_kiosco =  '$id'";
			$rs = mysql_query($stm_sql);
			mysql_close($conex);
		}

		function comprobarEPPVale($id_vale, $id_mat)
		{
			$resp = false;
			$conex_kiosco = conecta("bd_kiosco");
			$stm_sql_kiosco =  "SELECT * 
							FROM  `detalle_vale_kiosco` 
							WHERE  `id_vale_kiosco` LIKE  '$id_vale'
							AND  `id_material` LIKE  '$id_mat'";
			$rs_kiosco = mysql_query($stm_sql_kiosco);
			if ($rs_kiosco) {
				if ($datos_kiosco = mysql_fetch_array($rs_kiosco))
					$resp = true;
			}
			return $resp;
			mysql_close($conex_kiosco);
		}

		function centrocostosVale($id_vale, $campo)
		{
			$resp = "";
			$conex_kiosco = conecta("bd_kiosco");
			$stm_sql_kiosco =  "SELECT  `$campo` 
							FROM  `vale_kiosco` 
							WHERE  `id_vale_kiosco` LIKE  '$id_vale'";
			$rs_kiosco = mysql_query($stm_sql_kiosco);
			if ($rs_kiosco) {
				if ($datos_kiosco = mysql_fetch_array($rs_kiosco))
					$resp = $datos_kiosco[0];
			}
			return $resp;
			mysql_close($conex_kiosco);
		}

		function consultarDetalleVale($id_vale, $campo, $idMaterial)
		{
			$resp = "";
			$conex_kiosco = conecta("bd_kiosco");
			$stm_sql_kiosco =  "SELECT  `$campo` 
							FROM  `detalle_vale_kiosco` 
							WHERE  `id_vale_kiosco` LIKE  '$id_vale'
							AND `id_material` = '$idMaterial'";
			$rs_kiosco = mysql_query($stm_sql_kiosco);
			if ($rs_kiosco) {
				if ($datos_kiosco = mysql_fetch_array($rs_kiosco))
					$resp = $datos_kiosco[0];
			}
			return $resp;
			mysql_close($conex_kiosco);
		}

		function obtenerDatoTabla($tabla, $campo, $cond, $valor, $bd)
		{
			$dat = "N/A";
			$con = conecta("$bd");
			$stm_sql = "SELECT $campo
					FROM  `$tabla` 
					WHERE  `$cond` LIKE  '$valor'";
			$rs = mysql_query($stm_sql);
			if ($rs) {
				if ($datos = mysql_fetch_array($rs)) {
					$dat = $datos[0];
				}
			}
			mysql_close($con);
			return $dat;
		}

		function modificarCategoriaMat($datosSalida)
		{
			$cont = 1;
			foreach ($datosSalida as $ind => $material) {
				if (isset($_POST["cmb_catMat$cont"]) && ($_POST["cmb_catMat$cont"] != "" && $_POST["cmb_catMat$cont"] != "1")) {
					$_SESSION['datosSalida'][$ind]['catMaterial'] = $_POST["cmb_catMat$cont"];
				}
				$cont++;
			}
		}
?>