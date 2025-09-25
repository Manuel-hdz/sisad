<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion
html xmlns="http://www.w3.org/1999/xhtml">

<?php 
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	

	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de la Clinica
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){		
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_elaborarSolicitud.php");
		
		?>	
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionClinica.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css"/>

    <style type="text/css">
		<!--
			#titulo-seleccionar {	position:absolute;	left:30px;	top:146px;	width:477px;	height:20px;	z-index:11;}
			#tabla-exaMedico {position:absolute;left:14px;top:182px;width:916px;height:265px;z-index:12;padding:15px;padding-top:0px;}
			#tabla-empExt {position:absolute;left:15px;top:475px;width:923px;height:200px;z-index:12;padding:15px;padding-top:0px; overflow:scroll}			
			#botonesSM{position:absolute;left:128px;top:216px;width:627px;height:26px;z-index:12;padding:15px;padding-top:0px;}			
			#titulo-tabla {	position:absolute; left:29px; top:69px;	width:919px; height:295px; z-index:8;}
			#calendario{position:absolute; left:696px; top:214px; width:30px; height:26px; z-index:18; }
			#procesando { position:absolute; left:406px; top:274px; width:133px; height:86px; z-index:17; }
		-->
    </style>
</head>

<body>		
<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
<div class="titulo_barra" id="titulo-seleccionar">Seleccionar la Opci&oacute;n de las Solicitudes de los Ex&aacute;menes M&eacute;dicos</div>
<?php 
	if(isset($_GET["id_reg"])){
		//Recuperar el elemento a Borrar
		$borrar=$_GET["id_reg"];
		//Borrarlo del arreglo de Sesion
		unset($_SESSION["datosSolicitudMedica"][$borrar]);
		//Reorganizar los valores ingresados en el Arreglo de Sesion
		$_SESSION['datosSolicitudMedica'] = array_values($_SESSION['datosSolicitudMedica']);
		//Verificar si el arreglo de Sesion no tiene valores para borrarlo por completo
		if (count($_SESSION["datosSolicitudMedica"])==0)
			unset($_SESSION["datosSolicitudMedica"]);
	}

	//verificar que este definido las variables que vienen desde la pagina anterior
	if (isset($_GET["id_tipoCon"])){
		//Asignamos el id que viene en el Get a una variable para manejarlo mas facilmente, es decir las variables que vienen en la url
		$idConsulta = $_GET["id_tipoCon"];
	}
	if (isset($_GET["id_nomEmp"]) ){
		//Recuperamos la variable que se maneja dentro de la pagina frm_gestionarSolicitud.php
		$idEmpresa = $_GET["id_nomEmp"];
		/*Obtenermos el nombre de la empresa con la funcion obtenerDato, esto desde la BD 
		(y ya que con la variable que  pasamos x la URL, se trajo el id_empresa, con esta funcion obtenermos el nombre de la empresa)*/
		$nomEmpresa = obtenerDato("bd_clinica","catalogo_empresas", "nom_empresa", "id_empresa", $idEmpresa);
		//Tambien con esta misma funcion se obtiene la razon social 
		$razSocial = obtenerDato("bd_clinica","catalogo_empresas", "razon_social", "id_empresa", $idEmpresa);
	}

	if(!isset($_POST['sbt_finalizar'])){
		if(isset($_POST['sbt_agregar'])){
			//Recuperar la informacion del post
			$idEmpresa = $_POST['hdn_idEmp'];
			$nomEmpresa = $_POST['txt_nomEmpresa'];
			$razSocial = $_POST['txt_razSocial'];
			$fecha = modFecha($_POST['txt_fecha'],3);
			$autorizo = $_POST['txt_autorizo'];
			$gerAdmin = $_POST['txt_gerAdmin'];
			$resUSO = $_POST['txt_resUSO'];
			$obs = $_POST['txa_obs'];
			$numEmp = strtoupper($_POST['hdn_numEmpExt']);
			$nomEmp = strtoupper($_POST['txt_nomEmp']);
			$examenes = strtoupper($_POST['txt_exaPracticados']);
			$idExamenes = strtoupper($_POST['hdn_idExamenes']);		
			$costo = str_replace("," ,"",$_POST['txt_cosTotal']);
			$formaPago = strtoupper($_POST['rdb_formaPago']);
			//Si ya esta definido el arreglo $registroServicios, entonces agregar el siguiente registro a el
			if(isset($_SESSION['datosSolicitudMedica'])){
				//Guardar los datos en el arreglo
				$datosSolicitudMedica[] = array("numEmp"=>$numEmp,"nomEmp"=>$nomEmp,"examenes"=>$examenes,"idExamenes"=>$idExamenes,"costo"=>$costo,"formaPago"=>$formaPago);
			}
			//Si no esta definido el arreglo $datosSolicitudMedica definirlo y agregar el primer registro
			else{		
				//Guardar los datos en el arreglo
				$datosSolicitudMedica = array(array("numEmp"=>$numEmp,"nomEmp"=>$nomEmp,"examenes"=>$examenes, "idExamenes"=>$idExamenes,"costo"=>$costo,"formaPago"=>$formaPago));
				$_SESSION['datosSolicitudMedica'] = $datosSolicitudMedica;					
			}
		}
	}
	else{
		//Colocamos nuevamente las variables que se encuentran en el $_POST[], solo para no generar Errores
		$idEmpresa = $_POST['hdn_idEmp'];
		$nomEmpresa = $_POST['txt_nomEmpresa'];
		$razSocial = $_POST['txt_razSocial'];
		$obs = $_POST['txa_obs'];
		?>
		<div class="titulo_etiqueta" id="procesando">
      		<div align="center">
        		<p><img src="../../images/loading.gif" width="70" height="70"  /></p>
        		<p>Procesando...</p>
      		</div>
		</div>
		<?php
		//Guardar en la Base de Datos
		registrarSolicitud();
		
	}
	if(isset($_POST['sbt_agregar'])){
		$id = $_POST['hdn_numEmpExt']+1;
	}
	else
		$id = obtenerIdEmpleadosExt(); 
	//Si se borro un registro calcular el ID de Empleado de forma diferente
	if(isset($_GET["id_reg"])){
		if(isset($_SESSION["datosSolicitudMedica"])){
			$idTemp=0;
			foreach ($_SESSION['datosSolicitudMedica'] as $ind => $registro){
				foreach($registro as $key => $value){
					if($key=="numEmp"){
						if($value>$idTemp)
							$idTemp=$value;
					}
				}
			}
			$id=($idTemp+1);
		}
	}
	?>
	
	<?php if(!isset($_POST['sbt_finalizar'])){?>
		<fieldset class="borde_seccion" id="tabla-exaMedico" name="tabla-exaMedico">
		<legend class="titulo_etiqueta">Seleccionar los Datos de la Solicitud para Examen Medico</legend>
		<form  onsubmit="return valFormElaborarSolicitudExaMedico(this);"   name="frm_elaborarSolExaMed" method="post" action="frm_elaborarSolicitud.php" >
		<table width="100%" cellpadding="5" cellspacing="5"  class="tabla_frm">
			<tr>
				<td width="11%"><div align="center">Empresa</div></td>
				<td width="14%">
					<input name="txt_nomEmpresa" type="text" class="caja_de_texto" id="txt_nomEmpresa" 
					onkeypress="return permite(event,'num',1);" value="<?php echo $nomEmpresa; ?>" size="20" maxlength="80" readonly="readonly"/>
				</td>
				<td width="16%"><div align="center">Raz&oacute;n Social</div></td>
				<td width="15%">
					<input type="text" name="txt_razSocial" id="txt_razSocial" maxlength="80" size="20" class="caja_de_texto" 
					value="<?php echo $razSocial;?>" onkeypress="return permite(event,'num_car',1);" onkeyup="return ismaxlength(this)" readonly="readonly"/>
				</td>
				<td width="14%"><div  align="right">*Fecha</div></td>
				<td width="12%"><input name="txt_fecha" id="txt_fecha" readonly="readonly" type="text"  value="<?php echo date("d/m/Y")?>" size="10" /></td>
				<td width="18%" rowspan="1"><div align="center">Observaciones</div></td>
			</tr>
			<tr>
				<td ><div align="center">*Autoriz&oacute;</div></td>
				<td>
					<input name="txt_autorizo" type="text" id="txt_autorizo" value="<?php echo obtenerdato("bd_usuarios","credenciales","nombre","usuarios_usuario",$_SESSION["usr_reg"]);?>"
					size="20" maxlength="60"  width="90" />
				</td>
				<td><div align="center">*Gerencia Admin.</div></td>
				<td><input name="txt_gerAdmin"  type="text" id="txt_gerAdmin"  value="AURORA LEDESMA MACIAS" size="20" maxlength="60"  width="90" /></td>
				<td><div align="center">*Responsable USO</div></td>
				<td><input name="txt_resUSO"  type="text" id="txt_resUSO"  value="MALCO OBED GARCIA BORJON" size="20" maxlength="60"  width="90" /></td>
				<td><textarea name="txa_obs" cols="20" rows="2" id="txa_obs" ></textarea></td>
			</tr>
			<tr>
				<td colspan="3"><strong>Ingresar Informaci&oacute;n del Trabajador Externo</strong></td>
				<td>&nbsp;</td>
				<td>
					<input type="hidden" name="hdn_numEmpExt" id="hdn_numEmpExt" value="<?php echo $id;?>" />
					<input type="hidden" name="hdn_idExamenes" id="hdn_idExamenes" value="<?php //echo $idExamenes;?>"  />
				</td>
				<td><input type="hidden" name="hdn_idEmp" id="hdn_idEmp" value="<?php echo $idEmpresa?>" /></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><div align="center">*Trabajador</div></td>
				<td><input name="txt_nomEmp" type="text" id="txt_nomEmp"  value="" size="20" maxlength="75"  width="90" /></td>
				<td><div align="center">*Costo Total</div></td>
				<td><input name="txt_cosTotal" type="text" id="txt_cosTotal" value="0.0" size="10" maxlength="10" readonly="readonly"  width="90" /></td>
				<td><div align="center">*Forma de Pago</div></td>
				<td>
				<input type="radio"  name="rdb_formaPago" id="rdb_formaPago" value="CONTADO" />
				Contado
				<input type="radio" name="rdb_formaPago" id="rdb_formaPago" value="CREDITO" />
				Credito
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2"><div align="right">*Ex&aacute;menes  a Pr&aacute;cticar</div></td>
				<td colspan="5">
					<input name="txt_exaPracticados" type="text" id="txt_exaPracticados"  size="60" maxlength="350" readonly="readonly"  width="90" 
					onclick="window.open('verExamenesMed.php','_blank','top=50, left=50, width=700, height=500, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" 
					title="De Click Sobre La Caja De Texto Para Agregar los Exámenes Médicos a Prácticarle al Trabajador Externo" value=""/>
			  </td>
			</tr>
			<tr>
				<td colspan="7"><div align="center" >
					<input type="hidden" name="hdn_botonSeleccionado" id="hdn_botonSeleccionado" value="" />
					<?php 
						if(isset($_SESSION['datosSolicitudMedica'])){ //Si al menos un Paso se ha agregado al Plan de Contingencia que se muestre el boton de finalizar?>
						<input name="sbt_finalizar" type="submit" class="botones" value="Finalizar" 
						title="Finalizar el Registro de la Solicitud Medica"
						onmouseover="window.status='';return true" onclick="hdn_botonSeleccionado.value='finalizar';" />
						&nbsp;&nbsp;&nbsp;
					<?php } ?>
					<input name="sbt_agregar" type="submit" class="botones" id="sbt_agregar"  value="Agregar" title="Agregar Trabajadores a la Solicitud" 
					onmouseover="window.status='';return true" onclick="hdn_botonSeleccionado.value='agregar';"   />
					&nbsp;&nbsp;&nbsp;
					<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" 
					onmouseover="window.status='';return true" />
					&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Cancelar" 
					title="Regresar al Inicio de Recursos Humanos" onmouseover="window.status='';return true" 
					onclick="confirmarSalida('inicio_recursos.php');" />
				</td>
			</tr>
		  </table>
		</form>
		</fieldset>	
	
		<div id="calendario">
			<input name="fechaIni" type="image" id="fechaIni" onclick="displayCalendar(document.frm_elaborarSolExaMed.txt_fecha,'dd/mm/yyyy',this)"
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"  title="Seleccionar Fecha"
			width="25" height="25" border="0" />
		</div>
	
		<?php
		if (isset($_SESSION["datosSolicitudMedica"])){?><?php
			echo "<div id='tabla-empExt' class='borde_seccion2'>";
			echo "<form method='post' action='frm_elaborarSolicitud.php' name='frm_borrarRegistroSolicitudMed'>";
			echo "<input type='hidden' name='hdn_idEmp' id='hdn_idEmp' value='$_POST[hdn_idEmp]'/>";		
				mostrarEmpleadosExt($_SESSION['datosSolicitudMedica']);
			echo "</form>";
			echo "</div>";
		}
	}
	?>		
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>