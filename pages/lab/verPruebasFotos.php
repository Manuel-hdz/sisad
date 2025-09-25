<?php

	/**
	  * Nombre del Módulo: Laboratorio                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 04/Julio/2011
	  * Descripción: Este archivo contiene funciones para registrar las fotos a las Mezclas
	  **/
	   
	//Incluimos arrchivo de conexion
	include ("../../includes/conexion.inc");
	//Incluimos el archivo para modificar las fechas para la consulta
	include ("../../includes/func_fechas.php");
	//Titulo de la ventana emergente
	echo "<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>";
	//Hoja de estilo para la ventana emergente
	echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css'/>";
	//Archivo de validacion para indicar pruebas seleccionadas
	echo "<script type='text/javascript' src='../../includes/validacionLaboratorio.js'></script>";?>
	<script type="text/javascript" src="../../includes/disableKeys.js"></script>
	<script type="text/javascript" language="javascript">
		<!--
		//Funcion para desabilitar el clic derecho en la ventana pop-up
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, ©Concreto Lanzado de Fresnillo S.A. de C.V.');
			}
		}
		document.onmousedown=click;						
		//-->
	</script>
	<style type="text/css">
		input[readonly="readonly"]{ font-family: Arial, Helvetica, sans-serif; font-size: 12px; background-color:#808080; color:#FFFFFF; }
	</style><?php	

 
	//Verificamos si viene la edad en el get; de ser asi llamar la funcion seleccionarPruebas()
	if(isset($_GET['edad'])){
		session_start();
		seleccionarFotos();
	}
	
	//Si esta definido el boton de Asignar, pasar las claves a la Session
	if(isset($_POST["sbt_cargaFotos"])){
		session_start();
		//Borrar el arreglo de memoriaFoto en caso de estar definido
		if (isset($_SESSION["memoriaFoto"])){
			unset($_SESSION["memoriaFoto"]);
			include_once("op_registrarPruebas.php");
			borrarFotosLab();
		}
		//Verificar si esta definido el id de Carpeta, de lo contrario calcularlo y pasarlo a la Sesion
		if(!isset($_SESSION['idCarpeta'])){
			include_once("../../includes/op_operacionesBD.php");
			//Verificar si ya hay un registro existente de la prueba
			$result = obtenerDato("bd_laboratorio","prueba_calidad","muestras_id_muestra","muestras_id_muestra",$_SESSION['idMuestraSel']);
			if($result==""){
				include_once("op_registrarPruebas.php");
				$_SESSION['idCarpeta']=obtenerIdPruebaCalidad();
			}
			else{
				$_SESSION['idCarpeta']=obtenerDato("bd_laboratorio","prueba_calidad","id_prueba_calidad","muestras_id_muestra",$_SESSION['idMuestraSel']);
			}
		}
		$band = 0;
		$edad = $_POST["txt_edad"];
		do{
			//Variable para controlar el ciclo de carga de Fotos
			$band++;
			//Variable para almacenar la etapa y registrar la foto adecuada en el sistema
			$etapa="";
			if ($band==1)
				$etapa="P";
			if ($band==2)
				$etapa="E";
			if ($band==3)
				$etapa="O";
			include_once("op_registrarPruebas.php");
			//funcion que guardara las fotografias
			$res = cargarFotosLab($etapa,$edad);
			//si el valor de res es -1 la no es una imagen lo que se esta intentando guardar
			if($res==-1){?>
				<script language="javascript" type="text/javascript">
					setTimeout("alert('No se pudo Cargar el Archivo. No es Imágen Válida');", 1000);
				</script><?php
			}
			//si el valor de res es 2 quiere decir que se esta tratando de cargar una imagen repetida
			if($res==2){?>
				<script language="javascript" type="text/javascript">
					setTimeout("alert('La Imágen ya esta Cargada');", 1000);
				</script><?php
			}
			//si el valor de res es igual a 1 realizar las operaciones correspondientes
			if($res==1){
				//Obtner el nombre de la fotografía
				$foto="Sin Foto";
				if(isset($_FILES['txt_fotografia'.$etapa]['name']) && $_FILES['txt_fotografia'.$etapa]['name'] !='')
					$foto=$_FILES['txt_fotografia'.$etapa]['name'];			
				//Crear el arreglo con los datos 
				$memoriaFoto[] = array("etapa"=>$_POST['txt_etapa'.$etapa],"edad"=>$_POST['txt_edad'],"foto"=>$foto);
				//Guardar los datos en la SESSION
				$_SESSION['memoriaFoto'] = $memoriaFoto;
			}//FIN if($res==1)				
		//El ciclo se repite 3 veces por la cantidad de Fotos que se evaluan
		}while($band<3);
		?>
		<script type="text/javascript" language="javascript">
			window.opener.document.getElementById("sbt_guardar").disabled = false; 
			window.opener.document.getElementById("sbt_guardar").title = "Guardar Resultados de Pruebas";
			window.opener.focus();
			window.close();
		</script>
		<?php
	}
		
	//Función que permite mostrar las Pruebas
	function seleccionarFotos(){
		//Recuperar el ID de Mezcla
		$edad=$_GET["edad"];
		?>
		<br>
		<p>
		<form name="frm_cargarFotoPrueba" onsubmit="return valSeleccionarFotoPruebas(this);" method="post" action="verPruebasFotos.php" enctype="multipart/form-data">
			<table cellpadding='5' width='100%' align='center' class="tabla_frm"> 
			<caption class='titulo_etiqueta'>Seleccione las Fotos a Cargar</caption>
			<tr>
				<td><div align="right">Edad</div></td>
				<td colspan="3">
					<input type="text" name="txt_edad" id="txt_edad" value="<?php echo $edad;?>" size="2" readonly="readonly" 
					onkeypress="return permite(event, 'num',2); " style="background-color:#999999; color:#FFFFFF"/>D&iacute;as
				</td>	
			</tr>
			<tr>
				<td><div align="right">*Fotografia</div></td>
	            <td>
					<input type="file" name="txt_fotografiaP" id="txt_fotografiaP" value="" onchange="validarExtensionFoto(this);"/>
					<input type="hidden" name="hdn_tipoValidoP" id="hdn_tipoValidoP" value="si"/>
				</td>
				<td><div align="right">Etapa</div></td>
				<td><input type="text" name="txt_etapaP" id="txt_etapaP" value="PRESENTADA" size="15" readonly="readonly" style="background-color:#999999; color:#FFFFFF"/></td>
			</tr>
			<tr>
				<td><div align="right">*Fotografia</div></td>
	            <td>
					<input type="file" name="txt_fotografiaE" id="txt_fotografiaE" value="" onchange="validarExtensionFoto(this);"/>
					<input type="hidden" name="hdn_tipoValidoE" id="hdn_tipoValidoE" value="si"/>
				</td>
				<td><div align="right">Etapa</div></td>
				<td><input type="text" name="txt_etapaE" id="txt_etapaE" value="ENSAYADA" size="15" readonly="readonly" style="background-color:#999999; color:#FFFFFF"/></td>
			</tr> 
			<tr>
				<td><div align="right">*Fotografia</div></td>
	            <td>
					<input type="file" name="txt_fotografiaO" id="txt_fotografiaO" value="" onchange="validarExtensionFoto(this);"/>
					<input type="hidden" name="hdn_tipoValidoO" id="hdn_tipoValidoO" value="si"/>
				</td>
				<td><div align="right">Etapa</div></td>
				<td><input type="text" name="txt_etapaO" id="txt_etapaO" value="OBTENIDA" size="15" readonly="readonly" style="background-color:#999999; color:#FFFFFF"/>
				</td>
			</tr>
			<tr>
				<td colspan="6" align="center"><?php
				$fotosCargadas="no";
				if (isset($_SESSION["memoriaFoto"]))
					$fotosCargadas="si";?>
				<input type="hidden" name="hdn_fotoAdd" id="hdn_fotoAdd" value="<?php echo $fotosCargadas;?>"/>
				<input type="hidden" name="hdn_tipoValido" id="hdn_tipoValido" value="si"/>
				<input type="submit" name="sbt_cargaFotos" value="Cargar Fotos" class="botones" title="Cargar la Foto Seleccionada" onMouseOver="window.estatus='';return true"/>
				&nbsp;&nbsp;&nbsp;
				<input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cancela la carga de Fotos y Cierra la Operaci&oacute;n" 
				onMouseOver="window.estatus='';return true"  onclick="window.close();"/>
				</td>
			</tr>
			</table>
		</form>
		</p><?php
	}//fin de la funcion seleccionarPruebas
	
	
?>