<?php

	/**
	  * Nombre del Módulo: Aseguramiento Calidad                                                
	  * Nombre Programador: Daisy Ariana Martínez Fernández
	  * Fecha: 21/Noviembre/2011
	  * Descripción: Archivo que permite cargar las fotos al Servidor, validando que solo sea un registro
	  **/  
	//Titulo de la ventana emergente
	echo "<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>"; 
	//Inlcuimos archivo que contiene las operaciones necesarias para el registro
	include ("op_registrarPlanAcciones.php");
	//Archivo de validacion
	echo "<script type='text/javascript' src='../../includes/validacionAseguramiento.js'></script>";
	//Archivo de Estilo
	echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";
	//Iniciamos la sesión para las operaciones necesarias en la pagina
	session_start();?>
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
	<style type="text/css">
		<!--
		#titulo-agregar-documentos { position:absolute; left:30px; top:22px; width:200px; height:20px; z-index:11;}
		#tabla-agregarDepartamentos{position:absolute;left:30px;top:30px;width:265px;height:170px;z-index:13;}
		.Estilo1 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 12px;
		}
		-->
    </style>
	<?php 
		//Verificamos si esta definido el boton finalizar
		if(isset($_POST["sbt_finalizar"])){
			//window.opener.document.getElementById("txt_ubicacion").value=<?php echo $_POST["txt_ubicacion"]; carga el valor a la caja de texto deseada; 
			//tomando en cuenta  la ventana de la cual fue lanzada(abierta)
			//window.opener.focus(); Enfoca la ventana de apertura
			//window.close(); Cierra el pop-up
			$participantes="";
			$contad = 1;
			//Recorremos el foreach  para almacenar el valor contenido en el Post en una variable y enviarla a una caja de texto
			foreach($_POST as $key => $valor){
				if($contad==1){
					$participantes = $valor;
				}
				if($contad>1&&$valor!="Finalizar"&&$_POST[$key]!=$_POST["hdn_cant"]){
					$participantes .= ",".$valor;
				}
				$contad++;
			}
			//Eliminamos Finalizar para que unicamente sean almacenados los nombres de los departamentos
			$participantes=str_replace(",Finalizar","",$participantes);
			$participantes=str_replace("ON,","",strtoupper($participantes));
				?>
				<script type="text/javascript" language="javascript">
					window.opener.document.getElementById("txt_paticipantesAu").value="<?php echo $participantes;?>"; 				
					window.opener.focus();
					window.close();
				</script>
			<?php	
			}?>
		
	
		<legend class="titulo_etiqueta">Seleccionar Participantes Auditoria</legend>	
		<form  onsubmit="return valFormPart(this);"name="frm_verPart" id="frm_verPart" method="post" action="verParticipantesAuditoria.php">
			<?php mostrarParticipantes();?>
			<table width="100%" border="0" cellpadding="5" cellspacing="5">
				<tr align="center">
					<td colspan="4">
						<input name="sbt_finalizar" type="submit" class="botones" value="Finalizar" 
						title="Finalizar y Continuar con el Registro"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;		
						<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar los campos"/>
					</td>
				</tr>
			</table>
		</form>