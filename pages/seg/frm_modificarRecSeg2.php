<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Seguridad Industrial
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");	
		//Manejo de la funciones para d
		include ("op_modificarRecSeg.php");?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/validacionSeguridad.js" ></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>

    <style type="text/css">
		<!--
		#titulo-regBitacora { position:absolute; left:30px; top:146px; width:268px; height:19px; z-index:11; }
		#tabla-recorridos2 { position:absolute; left:14px; top:324px; width:940px; height:132px; z-index:12; }
		#tabla-recorridos3 { position:absolute; left:20px; top:532px; width:940px; height:132px; z-index:13; overflow:scroll}
		#tabla-recorridos { position:absolute; left:14px; top:192px; width:940px;	height:97px; z-index:16; }
		#fechaIngreso { position:absolute; left:928px; top:220px; width:30px; height:26px; z-index:14; }
		#fechaSalida { position:absolute; left:973px; top:293px; width:30px; height:26px; z-index:15; }
		#botonesBit {position:absolute;left:9px;top:166px;width:971px;height:37px;z-index:17;}
		-->
    </style>
</head>
<body>
	<?php
		//Verificamos que no exista en el GET la variable noRegistro
		if(isset($_POST['rdb_id'])&&!isset($_POST['sbt_guardar'])){
			
			//Conectar a la BD de de seguridad
			$conn = conecta("bd_seguridad");
								
			//Crear sentencia SQL
			$sql_stm ="SELECT * FROM (recorridos_seguridad JOIN detalle_recorridos_seguridad ON recorridos_seguridad_id_recorrido=id_recorrido)
					  WHERE id_recorrido='$_POST[rdb_id]'";
	
			//Ejecutar la sentencia previamente creada
			$rs = mysql_query($sql_stm);
		
			//Creamos el arreglo para guardar el resultado del a consulta
			$recorridosSeg = array();
		
		//Verificamos la existencia de datos
		if($datos=mysql_fetch_array($rs)){
	
			//Consulta que permite seleccionar los departamentos para despues proceder a concatenarlos
			$stm_sqlDep="SELECT catalogo_departamentos_id_departamento FROM alertas_recorridos_seguridad WHERE recorridos_seguridad_id_recorrido='$_POST[rdb_id]'";
				
			//Ejecutar la sentencia previamente creada
			$rsDep = mysql_query($stm_sqlDep);
				
			//Confirmar que la consulta de datos fue realizada con exito.
			$datosDep=mysql_fetch_array($rsDep);
			
			//Variable que permite controlar el agregado de archivos
			$contad = 1;
			do{	
				if($contad==1){
					$nomDepto = obtenerDato("bd_usuarios", "usuarios", "depto", "no_depto", $datosDep['catalogo_departamentos_id_departamento']);
					$deptos = strtoupper($nomDepto);
				}
				if($contad>1){	
					$nomDepto = obtenerDato("bd_usuarios", "usuarios", "depto", "no_depto", $datosDep['catalogo_departamentos_id_departamento']);
					$deptos .= ",".strtoupper($nomDepto);
				}
				$contad++;
			}while($datosDep=mysql_fetch_array($rsDep));
			//Eliminamos PANEL de la variable departamentos
			$deptos=str_replace("PANEL,,","",$deptos);
			
		
			//Guardamos los valores resultantes de la primer consulta para trabajar posteriormente con ellos
			$claveReg = $datos['id_recorrido'];
			$responsable = $datos['responsable'];
			$fecha = modFecha($datos['fecha'],1);
			$observaciones = $datos['observaciones'];
			$departamentos = $deptos;
			$atributo = "";
			$_SESSION['recorridosPrinc']=array('claveReg'=>$claveReg,'responsable'=>$responsable,'fecha'=>$fecha,'observaciones'=>$observaciones,'departamentos'=>$departamentos,'atributo'=>$atributo);
			//Recorremos para guardar los registros en las posiciones indicadas
			do{	
				$recorridosSeg[]=array("area"=>$datos['area'], "anomaliaDet"=>$datos['anomalia'], "anomaliaCor"=>$datos['correccion_anomalia'], 
								"lugar"=>$datos['lugar'], "noAn"=>$datos['id_detalle_recorrido_seguridad']);
			}while($datos=mysql_fetch_array($rs));
			//Guardamos en la session el arreglo previamente creado
			$_SESSION["recorridosSeg"]=$recorridosSeg;//Cierre if($datos=mysql_fetch_array($rs)
		}//Cierre (($datos=mysql_fetch_array($rs))
		
	}//Cierre (!isset($_GET['sbt_agregar'])&&!isset($_SESSION['lista_maestra']))
		
	//Comprobamos si existe en el GET la variable noRegistro si es asi quiere decir que se presiono la imagen-boton borrar
	if(isset($_GET["noRegistro"])){
		//Funcion que nos permite eliminar el registro seleccionado
		eliminarFoto($_GET['claveReg'], $_GET['noAn']);
		//Si es asi liberar la sesion en la posicion del registro indicado en el get
		unset($_SESSION["recorridosSeg"][$_GET["noRegistro"]]);

		//Verificamos que exista la session y el parametro que contiene la ubicacion del registro dentro de la sesion
		if(isset($_SESSION["recorridosSeg"]) && isset($_GET["noRegistro"]))
			//Reacomodamos el Arreglo
			$_SESSION['recorridosSeg'] = array_values($_SESSION['recorridosSeg']);
		
		//Verificamos si exista la sesion
		if(isset($_SESSION["recorridosSeg"])){
			//Si el arreglo de lista_maestra esta vacio, retirarlo de la SESSION
			if(count($_SESSION["recorridosSeg"])==0){
				//Liberamos la sesion
					unset($_SESSION["recorridosSeg"]);
				}
			}
			
		}//Cierre if(isset($_GET["noRegistro"]))
		
		//Verificar que el boton agregar esta definido
		if(isset($_POST["sbt_agregar"])){	
			//Si ya esta definido el arreglo, entonces agregar el siguiente registro a el
			if(isset($_SESSION['recorridosSeg'])){		
				//Contamos el arreglo para conocer el numero de actividad
				$tam = count($_SESSION['recorridosSeg'])+1;	
				//Guardar los datos en el arreglo
				$recorridosSeg[] = array("area"=>strtoupper($_POST['txa_area']), "anomaliaDet"=>strtoupper($_POST['txa_anomaliaDet']),
					"anomaliaCor"=>strtoupper($_POST['txa_anomaliaCor']), "lugar"=>strtoupper($_POST['txt_lugar']),"noAn"=>$tam);
			}
			//Si no esta definido el arreglo definirlo y agregar el primer registro
			else{	
				$tam=1;		
				//Guardar los datos en el arreglo
				$recorridosSeg = array( array("area"=>strtoupper($_POST['txa_area']), "anomaliaDet"=>strtoupper($_POST['txa_anomaliaDet']),
					"anomaliaCor"=>strtoupper($_POST['txa_anomaliaCor']), "lugar"=>strtoupper($_POST['txt_lugar']),"noAn"=>$tam));
				$_SESSION['recorridosSeg'] = $recorridosSeg;	
			}	
		}
		

		//Guardamos los valores en caso de que exista el post
		if(isset($_POST['sbt_agregar'])||isset($_POST['sbt_guardar'])){
			$claveReg = $_POST['txt_clave'];
			$responsable = strtoupper($_POST['txt_responsable']);
			$observaciones = strtoupper($_POST['txa_observaciones']);
			$departamentos = $_POST['txt_ubicacion'];
			$atributo = 1;
			$fecha = $_POST['txt_fecha'];
			//En caso de que existan en el get
		}elseif(isset($_GET['noRegistro'])){
			$claveReg = $_SESSION['recorridosPrinc']['claveReg'];
			$responsable = strtoupper($_SESSION['recorridosPrinc']['responsable']);
			$observaciones = strtoupper($_SESSION['recorridosPrinc']['observaciones']);
			$departamentos = $_SESSION['recorridosPrinc']['departamentos'];
			$atributo = $_SESSION['recorridosPrinc']['atributo'];
			$fecha = $_SESSION['recorridosPrinc']['fecha'];
		}
	
		//Variable que nos permite controlar el nunmero de anomalia
		$idAnom=0;
		//Verificamos que no exista el boton agregar para asi definir el no de anomalia correspondiente
		
		if(!isset($_POST['sbt_agregar'])){
			//Conectar a la BD de de seguridad
			$conn = conecta("bd_seguridad");
			//Creamos la sentencia SQL
			$stm_sqlAn=("SELECT MAX(id_detalle_recorrido_seguridad) AS cant FROM detalle_recorridos_seguridad  WHERE recorridos_seguridad_id_recorrido='$claveReg'");
			//Ejecutamos la sentencia previamente creada
			$rsAnom=mysql_query($stm_sqlAn);
			//Guardamos los datos resultantes en el arreglo $datos Anom
			$datosAnom=mysql_fetch_array($rsAnom);
			//Obtenemos el numero de anomalias
			$idAnom = $datosAnom['cant']; 
		}
		else{
			//De lo contrario sera igual a la anomalia contenida en el hidden en la seccion de botones
			$idAnom=$_POST['hdn_idAnom'];
		}
		
		//Verificar que este definido el Arreglo de fotos, si es asi, lo mostramos en el formulario
		if (isset($_SESSION["recorridosSeg"])){
			echo "<div id='tabla-recorridos3' class='borde_seccion2'>";
			mostrarRegRecorridos($recorridosSeg, $claveReg);
			echo "</div>";
		}
		
		
		?>
		<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
		<div class="titulo_barra" id="titulo-regBitacora">Modificar Recorridos Seguridad </div>

		<form onsubmit="return valFormRegRecorridosSeguridad(this);"name="frm_regRecSeg" method="post" action="frm_modificarRecSeg2.php">
		<fieldset id="tabla-recorridos" class="borde_seccion">
		<legend class="titulo_etiqueta">Ingresar Informaci&oacute;n de los Recorridos </legend>	
		<table width="94%" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="7%"><div align="right">Clave</div></td>
				<td width="12%">
					<input name="txt_clave" class="caja_de_texto" id="txt_clave" size="10" maxlength="10" value="<?php echo $claveReg;?>" readonly="readonly" 
					type="text"  />
				</td>
				<td width="12%"><div align="right">*Responsable</div></td>
				<td width="35%">
		 			<input name="txt_responsable" class="caja_de_texto" id="txt_responsable"  size="60" maxlength="60"  value="<?php echo $responsable;?>"
		  			onkeypress="return permite(event,'car',2);"  type="text"  <?php if($atributo==1){ ?>readonly="readonly"<?php }?>/>
				</td>
				<td width="11%"><div align="right">*Fecha</div></td>
				<td width="23%">
					<input name="txt_fecha" id="txt_fecha" class="caja_de_texto" size="10" value="<?php echo $fecha; ?>" readonly="readonly"  type="text"  />
				</td>
			</tr>
			<tr><td><div align="right">*Observaciones</div></td>
				<td>
					<textarea name="txa_observaciones" id="txa_observaciones" maxlength="120" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" 
					cols="30" onkeypress="return permite(event,'num_car', 0);"
					<?php if($atributo==1){ ?>readonly="readonly"<?php }?>><?php  echo $observaciones;?></textarea>
				</td>
				<td><div align="right">*Departamentos</div></td>
				<td colspan="3">
					<input name="txt_ubicacion" id="txt_ubicacion" type="text" class="caja_de_texto" size="60" readonly="readonly" 
					onclick="window.open('verDepartamentos.php','_blank','top=50, left=50, width=380, height=500, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" title="De Click Sobre La Caja De Texto Para Agregar Departamentos" value="<?php echo  $departamentos;?>"/>
	  			</td>
			</tr>
		</table>
	</fieldset>
	
	<fieldset id="tabla-recorridos2" class="borde_seccion">
	<legend class="titulo_etiqueta">Registrar Informaci&oacute;n de los Recorridos de Seguridad </legend>
 	<table width="946" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td width="73"><div align="right">*&Aacute;rea</div></td>
		  	<td width="214">
				<textarea name="txa_area" id="txa_area" maxlength="80" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" cols="30"
				onkeypress="return permite(event,'num_car', 0);"></textarea>
			</td>
		  	<td width="122"><div align="right">*Anomal&iacute;a Detectada </div></td>
		 	<td rowspan="3">
		  		<textarea name="txa_anomaliaDet" id="txa_anomaliaDet" maxlength="700" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="5" cols="30"
				onkeypress="return permite(event,'num_car', 0);"></textarea>
			</td>
		  	<td width="86"><div align="right">*Correcci&oacute;n Anomal&iacute;a </div></td>
		  	<td rowspan="3">
				<textarea name="txa_anomaliaCor" id="txa_anomaliaCor" maxlength="700" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="5" cols="30"
				onkeypress="return permite(event,'num_car', 0);"></textarea>
			</td>
		</tr>	
		<tr>
			<td width="73"><div align="right">*Lugar</div></td>
		  	<td width="214">
				<input name="txt_lugar" class="caja_de_texto" id="txt_lugar"  size="40" maxlength="80"  
		  		onkeypress="return permite(event,'num_car',8);"  type="text"/>
			</td>
		</tr>
		<tr>
			<td colspan="3"><strong>* Datos marcados con asterisco son <u>obligatorios</u></strong></td>
		</tr>	
			<tr>
				<td colspan="6">
					<div align="center" id="botonesBit">
						<input type="hidden" name="hdn_botonSel" id="hdn_botonSel" value=""/>
						<input type="hidden" name="hdn_idAnom" id="hdn_idAnom" value="<?php echo $idAnom+1;?>"/>
						<?php if(isset($_SESSION['recorridosSeg'])){?>
							<input name="sbt_guardar" type="submit" class="botones" id="sbt_guardar"  value="Finalizar" 
							title="Agregar Registro Recorridos de Seguridad" onmouseover="window.status='';return true" onclick="hdn_botonSel.value='finalizar'" />
							&nbsp;&nbsp;&nbsp;
						<?php }?>
						<input name="sbt_agregar" type="submit" class="botones" id="sbt_agregar"  value="Agregar" 
						title="Guardar Registro Recorridos de Seguridad" onmouseover="window.status='';return true" onclick="hdn_botonSel.value='agregar'"/>
						&nbsp;&nbsp;&nbsp;
						<input name="btn_limpiar" type="button" class="botones" value="Limpiar" id="btn_limpiar" title="Limpia el Formulario" 
						onmouseover="window.status='';return true"/>
						<?php if(isset($_SESSION['recorridosSeg'])){?>
							&nbsp;&nbsp;&nbsp;
							<input name="btn_regresar" type="button" class="botones" value="Cancelar" 
							title="Cancelar Registro  y Regresar a Seleccionar Otro Registro" 
							onclick="confirmarSalida('frm_modificarRecSeg.php?cancel=<?php echo $claveReg;?>');" onmouseover="window.status='';return true" />
						<?php }?>
				  </div>			
				</td>
			</tr>
	</table>
	</fieldset>


    </form>
	<?php if(!isset($_SESSION['recorridosSeg'])){?>
		<div id="fechaIngreso">
       		<input type="image" name="txt_fecha" id="txt_fecha" src="../../images/calendar.png"
	        onclick="displayCalendar(document.frm_regRecSeg.txt_fecha,'dd/mm/yyyy',this)" 
    	    onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
        	title="Seleccionar Fecha de Ingreso"/> 
		</div>
	<?php }?>
</body>
<?php  }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>