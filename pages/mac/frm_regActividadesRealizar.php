<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Mantenimiento
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");		
		include ("op_gestionarServiciosExternos.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="includes/ajax/cargarComboEquipoMtto.js"></script>	
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar{position:absolute;left:30px;top:146px;width:330px;height:20px;z-index:11;}	
		#tabla-registrarActividades{position:absolute;left:30px;top:190px;width:904px;height:160px;z-index:13;padding:15px;	padding-top:0px;}
		#tabla-mostrarActividades{position:absolute;left:30px;top:390px;width:904px;height:270px;z-index:14;overflow:scroll;}		
		-->
    </style>
</head>
<body><?php

	/******************************** GUARDAR LOS DATOS DE LA PAGINA ANTERIOR EN LA SESSION PARA VOLVERLOS A MOSTRAR ****************/	
	//Agregamos los datos del post de la Orden de Tabajo para Servicios Externos al arreglo de SESSION
	if(isset($_POST["cmb_clasificacion"])){		
		$_SESSION['ordenServicioExterno'] = array("idOrdenTrabajo"=>$_POST['txt_ordenTrabajo'], "fechaRegistro"=>$_POST['txt_fechaRegistro'],
		"area"=>$_POST['txt_area'], "clasificacion"=>$_POST['cmb_clasificacion'], "fechaSolicitud"=>$_POST['txt_fechaSolicitud'], "fechaRecepcion"=>$_POST['txt_fechaRecepcion'],
		"rfcProveedor"=>$_POST['hdn_rfc'], "proveedor"=>strtoupper($_POST['txt_proveedor']), "direccion"=>strtoupper($_POST['txt_direccion']), 
		"repProveedor"=>strtoupper($_POST['txt_repProveedor']), "encCompras"=>strtoupper($_POST['txt_encCompras']), "solicito"=>strtoupper($_POST['txt_solicito']), 
		"autorizo"=>strtoupper($_POST['txt_autorizo']));
	}//Cierre if(isset($_POST["cmb_clasificacion"]))
	

	//Agregar los datos de las Actividades cuando se le de clic al boton de Agregar (sbt_agregarAct)
	if(isset($_POST["sbt_agregarAct"])){
		//Si ya esta definido el arreglo $actividadesRealizar, entonces agregar el siguiente registro a el
		if(isset($_SESSION['actividadesRealizar'])){			
			//Guardar los datos en el arreglo
			$_SESSION['actividadesRealizar'][] = array("partida"=>($txt_partida), "actividad"=>strtoupper($txa_actividad), "aplicacion"=>strtoupper($txt_aplicacion), 
			"sistema"=>strtoupper($cmb_sistema), "familia"=>$cmb_familia, "claveEquipo"=>$cmb_claveEquipo);
		}
		//Si no esta definido el arreglo $actividades definirlo y agregar el primer registro
		else{			
			//Guardar los datos en el arreglo
			$actividadesRealizar = array(array("partida"=>($txt_partida),"actividad"=>strtoupper($txa_actividad), "aplicacion"=>strtoupper($txt_aplicacion), 
			"sistema"=>strtoupper($cmb_sistema), "familia"=>$cmb_familia, "claveEquipo"=>$cmb_claveEquipo));
			
			//Guardar en la SESSION
			$_SESSION['actividadesRealizar'] = $actividadesRealizar;	
		}	
	}//Cierre if(isset($_POST["sbt_agregarAct"]))
	
	
	//Cuando se entra a esta página desde el registro de la Orden de Trabajo para Servicios Externos, obtenemos el No. de Actividad de la SESSION o 
	//colocamos 1 en el caso de que no exista
	$cont = 0;
	if(!isset($_POST["sbt_agregarAct"])){
		if(!isset($_SESSION["actividadesRealizar"]))
			$cont = 1;
		else//De lo contrario si el arreglo viene definido, es contado y se agrega uno mas, para formar el sig. numero de partida
			$cont = count($_SESSION["actividadesRealizar"])+1;
	}
			
	//Verificamos que el boton este definido; cada vez que se presione aumentara la partida
	if(isset($_POST["sbt_agregarAct"])){
		$cont = $_POST["txt_partida"]+1;
	}?>


	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-registrar">Registrar Actividades a Realizar</div>
	
	
	<fieldset class="borde_seccion" id="tabla-registrarActividades" name="tabla-registrarActividades">
	<legend class="titulo_etiqueta">Registrar Acciones o Actividades a Realizar </legend>
	
	<form onSubmit="return valFormActividadesRealizar(this,'<?php echo $_SESSION['ordenServicioExterno']['area']; ?>');" name="frm_actividadesRealizar" method="post">    
	<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
		  	<td width="46" valign="top" align="right">Partida</td>
			<td width="10" valign="top">
		  		<input name="txt_partida" id="txt_partida" type="text" class="caja_de_num" size="2" value="<?php echo $cont;?>" readonly="readonly" />
			</td>
      	    <td width="53" valign="top" align="center">*Sistema</td>
      	    <td width="196" valign="top"><?php
				//Cargar las opciones del archivo listaSistemas.txt ubicado en ..\pages\mac\includes
				$ruta = "includes/";
				$h = opendir($ruta);//Abrir el directorio indicado
				//Leer cada archivo en busca del archivo que contiene la lista de sistemas
				while($file=readdir($h)){
					//Sí el archivo actual es 'listaSistemas.txt', procedemos a leerlo
					if ($file=='listaSistemas.txt'){
						//Abrir el directorio inidicado con el archivo encontrado
						$fp = @fopen($ruta.$file, "r");
						if (!is_resource($fp))
							echo "No se Pudo Leer el Archivo '$file'";
						else{?>
							<select class="combo_box" name="cmb_sistema" id="cmb_sistema" onchange="agregarSistema(this);"><?php
								echo "<option value='' selected='selected'>Sistema</option>";
								//Leer el archivo linea por linea
								while ($line = fgets($fp)){
									$tamLinea=strlen($line)-2;
									if(substr($line,0,5)!="OTROS")
										echo "<option value='".substr($line,0,$tamLinea)."' title='".substr($line,0,$tamLinea)."'>".substr($line,0,$tamLinea)."</option>";
									else
										$ultimo="<option value='".substr($line,0,$tamLinea)."' title='".substr($line,0,$tamLinea)."'>".substr($line,0,$tamLinea)."</option>";
								}
								echo $ultimo;
								echo "<option value='AgregarNuevo'>Agregar Nuevo...</option>";?>
							</select><?php
						}
					}//Cierre if ($file=='listaSistemas.txt')
				}//Cierre while($file=readdir($h))
				
				//Cerrar el directorio abierto
				closedir($h);?>
		  	</td>
		 	<td width="84" valign="top"><div align="right">*Aplicaci&oacute;n</div></td>
			<td width="147" valign="top">
				<input name="txt_aplicacion" id="txt_aplicacion" type="text" class="caja_de_texto" size="17" maxlength="30" onkeypress="return permite(event,'num_car', 0);"/>
		  	</td>
      	    <td width="57" valign="top" align="right">*Actividad</td>
      	    <td width="184" valign="top" rowspan="3">
			  	<textarea name="txa_actividad" cols="35" rows="4" class="caja_de_texto"  maxlength="120" id="txa_actividad"  onkeyup="return ismaxlength(this)" 
				onkeypress="return permite(event,'num_car', 0);"></textarea>			
			</td>
  	  	</tr>
		<tr>
			<td colspan="2" align="right">Familia</td>
			<td colspan="2">
				<script type="text/javascript" language="javascript">
					cargarCombo("<?php echo $_SESSION['ordenServicioExterno']['area']; ?>","bd_mantenimiento","equipos","familia","area","cmb_familia","Familia","");
				</script>
				<select name="cmb_familia" id="cmb_familia" 
				onchange="cargarEquiposFamilia(this.value,'<?php echo $_SESSION['ordenServicioExterno']['area']; ?>','cmb_claveEquipo','Clave Equipo','');
							setTimeout('nvaOpcComboEquipo();',700);">
						<option value="">Familia</option>
			  </select>
			</td>
			<td align="right">Equipo</td>
			<td colspan="2">
				<select name="cmb_claveEquipo" id="cmb_claveEquipo" >
					<option value="">Clave Equipo</option>
				</select>
			</td>
		</tr>
	  	<tr>
	  	  	<td colspan="7"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
  	  	</tr>
      	<tr>
      		<td colspan="8" align="center"><?php
				
				if(isset($_SESSION["actividadesRealizar"])){?>
					<input type="button" name="btn_finalizarRegistro" class="botones" value="Finalizar" title="Finalizar Registro de Actividades a Realizar" 
					onclick="location.href='frm_generarOrdenServiciosE.php'" />
					&nbsp;&nbsp;<?php					
				}?>
						
				<input name="sbt_agregarAct" type="submit" class="botones"  value="Agregar" title="Registrar Actividad"
            	onmouseover="window.status='';return true" />
				&nbsp;&nbsp;
       	 		<input name="rst_limpiar" type="reset" class="botones" value="Limpiar" title="Limpiar Formulario" />
				&nbsp;&nbsp;
                <input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar Registro de la Orden de Trabajo" 
                onmouseover="window.status='';return true" onclick="location.href='frm_generarOrdenServiciosE.php?cancelar=actividades'" />
			</td>
		</tr>
	</table>
	</form>
	</fieldset><?php
				
	
	//Verificar que este definido el Arreglo de $actividadesRealizar, si es asi, Mostramos su contenido
	if(isset($_SESSION["actividadesRealizar"])){?>
		<div id="tabla-mostrarActividades" class="borde_seccion2"><?php
			mostrarActividadesRealizar($_SESSION["actividadesRealizar"]);?>
		</div>
		<?php
	}?>
	
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>