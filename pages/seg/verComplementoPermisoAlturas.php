<?php
	/**
	  * Nombre del Módulo: Seguridad Industrial                                                
	  * Nombre Programador: Nadia Madahí López Hernandez
	  * Fecha: 
	  * Descripción: Seleccion donde se com,plementa el registro de los permisos de alturas 
	  **/  
	  	//Incluimos arrchivo de conexion
	include("../../includes/conexion.inc");
	//Inlcuimos archivo que contiene las operaciones necesarias para el registro
	include ("op_seleccionarPermiso.php");
	
	//Titulo de la ventana emergente
	echo "<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>"; 
	//Archivo de validacion
	echo "<script type='text/javascript' src='../../includes/validacionSeguridad.js'></script>";
	//Archivo de Estilo
	echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";
	//Iniciamos la sesión para las operaciones necesarias en la pagina
	//session_start();
	echo "<script type='text/javascript' src='includes/ajax/verificarComplementoPerAlturas.js'></script>";
	//Funcion para validar que el maximo tamaño no sea excedido
	echo "<script type='text/javascript' src='../../includes/maxLength.js'></script>";?>
	<script language="javascript" type="text/javascript" src="../../includes/disableKeys.js"></script>
	<script language="javascript" type="text/javascript">
		<!--
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, ©Concreto Lanzado de Fresnillo S.A. de C.V.');
			}
		}
		document.onmousedown=click;
		//-->
	</script>
	<script language="javascript" type="text/javascript">
		/*Variable de referncia que indica si se cierra la ventana donde se registran las nuevas condiciones para actualizar 
		la ventana que muestran las condiciones generales de seguridad*/
		var vntRegNvaCond = "";	
	</script>
	
	<style type="text/css">
		<!--
		.Estilo1 { font-family: Arial, Helvetica, sans-serif;font-size: 12px;}
		-->
    </style>
	<?php 
	if(isset($_POST['sbt_guardarDetalleAct'])){
		//Mandamos una alerta notificandole al usuario que los registros se han guardado correctamente y posteriormente cerramos la ventana emergente ?>
		<script language="javascript" type="text/javascript">
			window.close();			
		</script><?php
	}?><script language="javascript" type="text/javascript">	
		//Funcion que permite conocer cual boton fue seleccionado para permitir la ejecucion de la funcion AJAX que valida que se deben de complemetar el permiso de alturas
		if(window.closed){
			function verificarComplementoPer(){
				window.opener.document.getElementById("sbt_continuar").disabled = false;
				window.opener.document.getElementById("btn_regCondicionesSeg").disabled = false;
			}
		}
		</script><?php 
		//Verificamos si esta definido el boton fin|alizar
		if(isset($_GET["noAct"])){
			//Se hace la conexion con la BD de Seguridad
			$conn =conecta("bd_seguridad");
			//Se declara la varoiable que guardara la clave del permiso que viene en el $_GET[]
			$idClave = $_GET['clavePermiso'];
			//Se crea y ejecuta la consulta para verificar el total de condiciones de seguridad que existen almacenadas en la BD.
			$contar = mysql_num_rows(mysql_query("SELECT num_actividad FROM pasos_permiso WHERE permisos_secundarios_id_permiso_secundario = '$idClave'"));
			//Se condiciona para verificar que se existen mas de una condicion, esta se pueda borrar ó de lo contrario cuando exista solo una condicion que mande una alerta
			if($contar!=1){
				eliminarActividad($_GET['noAct'],$_GET['clavePermiso']);	
			}
			else{?>
				<script type="text/javascript" language="javascript">
					setTimeout("alert('La Descripción de la última Condicion de Seguridad, No se puede Borrar, Registre una Nueva para Borrar la Anterior')", 500);
				</script><?php
			}//Cierre del else		
		} //Cierre del if(isset($_GET["noAct"])){?>

		<legend align="center" class="titulo_etiqueta">Seleccionar Condiciones de Seguridad</legend>	
		<body onUnload="verificarComplementoPer();" onFocus="if(vntRegNvaCond.closed){ location.reload(); }">
				
		<form onSubmit="return valFormVerCondicionesSeg(this);"  name="frm_verCondSeg" method="post" action="verComplementoPermisoAlturas.php">
			<?php mostrarCondicionesSeguridad();?>			
				<table width="100%" border="0" cellpadding="5" cellspacing="5">
					<tr align="center">
						<td colspan="4">
							<input name="btn_agregar" type="button"  id="btn_agregar" class="botones_largos" value="Registrar Condición" 
							title="Registra Nuevas Condiciones de Seguridad" onClick="nuevasCondiciones();"/ >
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	
							<input name="sbt_guardarDetalleAct" type="submit"  id="sbt_guardarDetalleAct" class="botones" value="Guardar" 
							title="Guarda el Complemento del Permiso de Alturas"   />
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	
							<input name="btn_finalizar" type="button"  id="btn_finalizar" class="botones" value="Finalizar" 
							title="Finalizar y Cierra la Ventana donde se Complementa el Permiso de Alturas" onClick="window.close();"/>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;		
							<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar los Opciones de Selección"/>
						</td>
					</tr>
				</table>
		</form>
	</body>
