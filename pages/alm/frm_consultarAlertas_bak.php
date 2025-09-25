<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Almacén
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Identificar si es Requisición u Órden de Compra para evitar problemas con funciones de nombres iguales en alguno de los 2 archivos de operaciones
		if ($hdn_org=="Requisición")
			include_once("op_generarRequisicion.php");
		else
			include_once("op_generarOC.php");
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>	
	<script type="text/javascript" src="../../includes/validacionAlmacen.js"></script>	
	<script type="text/javascript" src="includes/ajax/cargarComboCuentas.js" ></script>
	
    <style type="text/css">
		<!--
		#form-datos-alertas { position:absolute; left:30px; top:190px; width:990px; height:480px; z-index:12; overflow:auto;}
		#titulo-alertas { position:absolute; left:30px; top:146px; width:436px; height:19px; z-index:11; }
		#botones { position:absolute; left:30px; top:680px; width:990px; height:40px; z-index:12; }
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-alertas">Material con Existencia Menor o Igual al Punto de Reorden</div>


<?php 
	$control=false;
	foreach($_POST as $clave=>$valor){
		if (substr($clave,0,3)=='ckb')
			$control=true;
	}
	if(!$control) { ?>	
		<div id="form-datos-alertas">
		 <p align="center" class="titulo_etiqueta">Completar la información para Generar la <?php echo $hdn_org; ?></p>
		 <form onSubmit="return valFormVerMateriales(this);" name="frm_verMateriales" action="frm_consultarAlertas_bak.php" method="post">
		 <table border="0" align="center" cellpadding="5" class="tabla_frm">
			 <tr>
				 <td align="center" class="nombres_columnas">Seleccione</td>
				 <td align="center" class="nombres_columnas">Clave</td>
				 <td align="center" class="nombres_columnas">Material</td>
				 <td align="center" class="nombres_columnas">Unidad Medida</td>
				 <td align="center" class="nombres_columnas">Cantidad</td>
				 <td align="center" class="nombres_columnas">Categor&iacute;a</td>
				 <?php if ($hdn_org=="Requisición")
					 echo "<td align='center' class='nombres_columnas'>Aplicaci&oacute;n</td>";
					 echo "<td align='center' class='nombres_columnas'>Centro de Costos</td>";
					 echo "<td align='center' class='nombres_columnas'>Cuenta</td>";
					 echo "<td align='center' class='nombres_columnas'>Subcuenta</td>";
				 ?>
			</tr>
			<?php 
				//Conectarse con la BD de Almacen y mantener la conexion para utilizar las funciones de monitorearMateriales() y mostrarAlertas($id_material)
				$conn = conecta("bd_almacen");
				//Evaluamos si el origen desde se llega a esta ventana es una Solicitud de Requisicion o de Orden de compra y se elabora la consulta propicia a cada una de ellas
				if ($hdn_org=="Requisición"){
					//Crear la sentencia para obtener las alertas registradas en la BD que aun no han sido atendidas y que corresponden a Requisiciones
					$stm_sql = "SELECT id_material, nom_material, unidad_medida, linea_articulo, costo_unidad, moneda FROM materiales 
								JOIN alertas ON id_material=materiales_id_material AND estado=1 AND origen='REQ'
								JOIN unidad_medida ON unidad_medida.materiales_id_material=id_material";	
				}else{
					//Crear la sentencia para obtener las alertas registradas en la BD que aun no han sido atendidas y que corresponden a Órdenes de Compra
					$stm_sql = "SELECT id_material, nom_material, unidad_medida, linea_articulo FROM materiales 
								JOIN alertas ON id_material=materiales_id_material AND estado=1 AND origen='OC'
								JOIN unidad_medida ON unidad_medida.materiales_id_material=id_material";	
				}
				//Ejecutar la sentencia previamente creada
				$rs = mysql_query($stm_sql);
				//Confirmar que la consulta de datos fue realizada con exito.
				if($datos=mysql_fetch_array($rs)){
					$nom_clase = "renglon_gris";
					$cont = 1;
					do{
						echo "<tr>
								<td class='nombres_filas' align='center'><input type='checkbox' name='ckb_select$cont' id='ckb_select$cont' value='$cont' onchange='activarMatAlertReq($cont)'/></td>
								<td class='$nom_clase' align='center'>$datos[id_material]<input type='hidden' name='hdn_clave$cont' id='hdn_clave$cont' value='$datos[id_material]'/></td>
								<td class='$nom_clase' align='left'>$datos[nom_material]<input type='hidden' name='hdn_nombre$cont' id='hdn_nombre$cont' value='$datos[nom_material]'/></td>
								<td class='$nom_clase' align='center'>$datos[unidad_medida]<input type='hidden' name='hdn_um$cont' id='hdn_um$cont' value='$datos[unidad_medida]'/></td>
								<input type='hidden' name='txt_costoU$cont' id='txt_costoU$cont' value='$datos[costo_unidad]'/>
								<input type='hidden' name='txt_moneda$cont' id='txt_moneda$cont' value='$datos[moneda]'/>
								";?>
								<td class="<?php echo $nom_clase;?>" align="center">
									<input name="txt_cantidad<?php echo $cont;?>" type="text" class="caja_de_num" id="txt_cantidad<?php echo $cont;?>" size="2" maxlength="5" onkeypress="return permite(event,'num');"/>
								</td>
								<?php echo "<td class='$nom_clase' align='left'>$datos[linea_articulo]</td>";
								if ($hdn_org=="Requisición"){
									?>
									<td>
										<?php
										$conn1 = conecta("bd_mantenimiento");//Conectarse con la BD de Mantenimiento
										$rs_equipos = mysql_query("SELECT DISTINCT id_equipo FROM equipos WHERE estado='ACTIVO' ORDER BY id_equipo");
										if($equipos=mysql_fetch_array($rs_equipos)){?>
											<select name="txt_aplicacion<?php echo $cont;?>" id="txt_aplicacion<?php echo $cont;?>" class="combo_box" onchange="cargarCuentas_Equipo(this.value,'cmb_con_cos<?php echo $cont;?>','cmb_cuenta<?php echo $cont;?>','cmb_subcuenta<?php echo $cont;?>');" disabled="true">
												<option value="">Equipos</option><?php
												do{
													echo "<option value='$equipos[id_equipo]'>$equipos[id_equipo]</option>";
												}while($equipos=mysql_fetch_array($rs_equipos));?>
											</select><?php
										} else {
											echo "<label class='msje_correcto'><u><strong> NO</u></strong> Hay Equipos Registrados</label>";
										}
										?>
									</td>
								<?php 
								}
								?>
								<td>
									<?php 
									$conn2 = conecta("bd_recursos");		
									$cc_sql = "SELECT * FROM control_costos WHERE habilitado = 'SI' ORDER BY descripcion";
									$cc_rs = mysql_query($cc_sql);
									//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
									if($cc_datos = mysql_fetch_array($cc_rs)){?>
										<select name="cmb_con_cos<?php echo $cont;?>" id="cmb_con_cos<?php echo $cont;?>" class="combo_box" onchange="cargarCuentas(this.value,'cmb_cuenta<?php echo $cont;?>')" disabled="true" required="required">
											<?php //Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
											echo "<option value=''>Centro de Costos</option>";
											do{
												echo "<option value='$cc_datos[id_control_costos]'>$cc_datos[descripcion]</option>";
											}while($cc_datos = mysql_fetch_array($cc_rs));?>
										</select>
									<?php
									} else {
										echo "<label class='msje_correcto'> No actualmente centro de costos</label>
										<input type='hidden' name='cmb_area' id='cmb_area'/>";
									}
									?>
								</td>
								<td>
									<span id="datosCuenta">
										<select name="cmb_cuenta<?php echo $cont;?>" id="cmb_cuenta<?php echo $cont;?>" class="combo_box" onchange="cargarSubCuentas(cmb_con_cos<?php echo $cont;?>.value,this.value,'cmb_subcuenta<?php echo $cont;?>')" disabled="true"  required="required">
											<option value="">Cuentas</option>
										</select>
									</span>
								</td>
								<td>
									<span id="datosSubCuenta">
										<select name="cmb_subcuenta<?php echo $cont;?>" id="cmb_subcuenta<?php echo $cont;?>" class="combo_box" disabled="true">
											<option value="">SubCuentas</option>
										</select>
									</span>
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
				}
			mysql_close($conn);
			?>
		</table>
	</div>
	<div id="botones" align="center">
		<input type="hidden" name="cant_ckbs" id="cant_ckbs" value="<?php echo $cont; ?>"/>
		<?php //Es necesario guardar nuevamente el origen, puesto que es reenviado mediante un nuevo POST ?>
		<input type="hidden" name="hdn_org" id="hdn_org" value="<?php echo $hdn_org; ?>"/>
		<input type="submit" class="botones" value="Registrar" onMouseOver="window.status='';return true" title="Registrar Materiales Seleccionados"/>
		&nbsp;&nbsp;&nbsp;
		<input type="reset" class="botones" value="Limpiar" title="Limpiar Datos del Formulario"/>
		&nbsp;&nbsp;&nbsp;
		<input type="button"  onclick="location.href='inicio_almacen.php'" class="botones" value="Cancelar" title="Regresar al Inicio de Almacén"/>
	</div>
	</form>
	<?php }
	else{
		//Contar los CheckBox para saber cuantos registros fueron seleccionados
		$cantReg = 0;
		//Guardar el numero del CheckBox que fue seleccionado
		$posiciones = array();
		foreach($_POST as $clave=>$valor){
			if (substr($clave,0,3)=='ckb'){
				$cantReg++;
				$posiciones[] = $valor;
			}
		}
		//Evaluar el origen para poder definir que datos tomara que arreglo y a su vez, la pagina a donde será redireccionado.
		if ($hdn_org=="Requisición"){
			//Extraer los datos del arreglo $_POST y pasarlos al Arreglo $datosRequisicion
			$cont = 1;
			$datosRequisicion = array();
			while($cont<=$cantReg){
				$pos = $posiciones[$cont-1];
				$id_material = $_POST["hdn_clave$pos"];
				$nombre = $_POST["hdn_nombre$pos"]; 
				$unidad = $_POST["hdn_um$pos"];
				$cantReq = $_POST["txt_cantidad$pos"];
				$aplicacion = $_POST["txt_aplicacion$pos"];
				$cc = $_POST["cmb_con_cos$pos"];
				$cuenta = $_POST["cmb_cuenta$pos"];
				$subcuenta = $_POST["cmb_subcuenta$pos"];
				$costoU = $_POST["txt_costoU$pos"];
				$moneda = $_POST["txt_moneda$pos"];
				if($cont==1){
					$datosRequisicion = array(
										array(
											"clave"=>$id_material,
											"material"=>$nombre, 
											"unidad"=>$unidad, 
											"cantReq"=>$cantReq, 
											"aplicacionReq"=>$aplicacion,
											"cc"=>$cc,
											"cuenta"=>$cuenta,
											"subcuenta"=>$subcuenta,
											"costoU"=>$costoU,
											"moneda"=>$moneda,
											"nuevo_con_clave"=>1
										)
									);
				}
				else{
					$datosRequisicion[] = array(
											"clave"=>$id_material, 
											"material"=>$nombre, 
											"unidad"=>$unidad, 
											"cantReq"=>$cantReq, 
											"aplicacionReq"=>$aplicacion,
											"cc"=>$cc,
											"cuenta"=>$cuenta,
											"subcuenta"=>$subcuenta,
											"costoU"=>$costoU,
											"moneda"=>$moneda,
											"nuevo_con_clave"=>1
										  );
				}
				$cont++;
			}		
			//Crear el ID de la requisicion
			$_SESSION['id_requisicion'] = obtenerIdRequisicion();
			//Guardar el arreglo datosRequisicion en una variable de Sesion que se enviará al fornmulario para Generar la Requisicion correspondiente
			$_SESSION['datosRequisicion']=$datosRequisicion;
			echo $hdn_org;
			echo "<meta http-equiv='refresh' content='0;url=frm_generarRequisicion.php'>";
		}
		else{
			//Extraer los datos del arreglo $_POST y pasarlos al Arreglo $datosOC
			$cont = 1;
			$datosOC = array();
			while($cont<=$cantReg){
				$pos = $posiciones[$cont-1];
				$clave = $_POST["hdn_clave$pos"];
				$nombre = $_POST["hdn_nombre$pos"]; 
				$cantidad = $_POST["txt_cantidad$pos"];
				if($cont==1)
					$datosOC = array(array("clave"=>$clave, "descripcion"=>$nombre, "cantidad"=>$cantidad, "org"=>"cat"));
				else
					$datosOC[] = array("clave"=>$clave, "descripcion"=>$nombre, "cantidad"=>$cantidad, "org"=>"cat");
				
				$cont++;
			}		
			//Crear el ID de la requisicion
			$_SESSION['id_ordenOC'] = obtenerIdOC();
			//Guardar el arreglo datosRequisicion en una variable de Sesion que se enviará al fornmulario para Generar la Requisicion correspondiente
			$_SESSION['datosOC']=$datosOC;
			echo "<meta http-equiv='refresh' content='0;url=frm_generarOC.php'>";
		}
	} 
?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>