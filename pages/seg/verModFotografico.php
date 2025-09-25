<?php

	/**
	  * Nombre del Módulo: Seguridad Industrial                                                
	  * Nombre Programador: Daisy Ariana Martínez Fernández
	  * Fecha: 07/Febrero/2012
	  * Descripción: Archivo que permite cargar el registro de las areas visitadas
	  **/  
		//Titulo de la ventana emergente
		echo "<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>"; 
		//Inlcuimos archivo que contiene las operaciones necesarias para el registro
		include ("op_modificarRecSeg.php");
		//Archivo de validacion
		echo "<script type='text/javascript' src='../../includes/validacionSeguridad.js'></script>";
		//Archivo de Estilo
		echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";
		//Archivo que permite controlar el numero de caracteres ingresados en las cajas de texto (textarea)
		echo "<script type='text/javascript' src='../../includes/maxLength.js' ></script>";
		//Archivo que contiene la funcion de validacion de la sesion para activar o no el boton de guardar
		echo "<script type='text/javascript' src='includes/ajax/activarBoton.js'></script>";
		//Archivo para desabilitar boton regresar del teclado?>
		<script language="javascript" type="text/javascript" src="../../includes/disableKeys.js"></script><?php 
		//Iniciamos la sesión para las operaciones necesarias en la pagina
		session_start();
		//Incluimos archivo para modificar fechas segun sea requerido	
		include_once("../../includes/func_fechas.php");
		//Incluimos archivo para conexion	
		include_once("../../includes/conexion.inc");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");?>
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
			#titulo-agregar-documentos { position:absolute; left:30px; top:146px; width:200px; height:20px; z-index:11;}
			#tabla-agregarFotos { position:absolute; left:31px; top:22px; width:672px; height:149px; z-index:12; padding:15px; padding-top:0px;}
			#tabla-fotos{position:absolute;left:30px;top:235px;width:720px;height:200px;z-index:13;overflow:scroll;}
			.Estilo1 {
				font-family: Arial, Helvetica, sans-serif;
				font-size: 12px;
			}
			-->
	    </style>
	<body>
	<?php
		//Si viene el id del registro los datos se tomaran del GET
		if(isset($_GET['idReg'])){
			$clave=$_GET["idReg"];
			$anomalia = $_GET['idAn'];
		}
		//Si vienen definidos enel POST estos seran tomados del antes mencionado
		if(isset($_POST["sbt_agregar"])){
			$clave=$_POST["txt_claveRegFot"];
			$anomalia = $_POST['hdn_carpeta'];
		}
		//Obtenemos el idAn y el IdReg
		if(isset($_GET['idAn'])){
			//Variable que nos permitira conocer si la anomalia tiene fotografias ya cargadas
			$idAn=$_GET['idAn'];
			$idReg=$_GET['idReg'];
			//Verificamos que tenga un registro en la Base de DAtos para evitar que pasen por el proceso registros sin registro fotografico
			$bandAn=obtenerDato("bd_seguridad", "registro_fotografico", "recorridos_seguridad_id_recorrido", 		
			"detalle_recorridos_seguridad_id_detalle_recorrido_seguridad", $_GET['idAn']);
			//Contador que nos permite saber cuando se entro ya a la consulta por primera vez; si el contador vale cero no se a entrado a dicha consulta		
			$contador = 0;
			//Comprobamos que exista definida la sesion banderas 
			if(isset($_SESSION["banderas"])){
				//Recorremos el arreglo en busca de un valor como el que sta almaenado; su existe uno igual ya no se puede entrar
				foreach($_SESSION["banderas"] as $key =>$value){
					if($value ==$anomalia){
						$contador =1;
					}
				}
			}
			//Comprobamos que se encuentre diferente de vacio; ya que esto nos indica que contiene un registro
			if($bandAn!=""&&$contador==0){
				//Conectar a la BD de de seguridad
				$conn = conecta("bd_seguridad");
						
				//Crear sentencia SQL
				$sql_stm ="SELECT * FROM registro_fotografico  WHERE recorridos_seguridad_id_recorrido='$idReg' 
					AND detalle_recorridos_seguridad_id_detalle_recorrido_seguridad='$idAn'";

				//Ejecutar la sentencia previamente creada
				$rs = mysql_query($sql_stm);
			
				//Contador que nos permimte controlar el numero de registros dentros de una consulta
				$cont=1;
	
				//Verificamos la existencia de datos
				if($datos=mysql_fetch_array($rs)){
					//Recorremos para guardar los registros en las posiciones indicadas
					do{	
						//Guardamos el contenido de la consulta en el arreglo	
						$registroFotografico[]=array("archivo"=>$datos['nom_archivo'], "clave"=>$datos['recorridos_seguridad_id_recorrido'], 
								"anomalia"=>$datos['detalle_recorridos_seguridad_id_detalle_recorrido_seguridad'], "tam"=>$cont, "band"=>1);
						$anomaliaReg = $datos['detalle_recorridos_seguridad_id_detalle_recorrido_seguridad'];
					}while($datos=mysql_fetch_array($rs));
					//Guardamos el contenido del arreglo en la sesion
					$_SESSION["registroFotografico"]=$registroFotografico;
					//Guardamos la anomalia para indicar que ya entro a realizar la consulta y no supla los datos
					$_SESSION["banderas"][$anomaliaReg] = $anomaliaReg;
				}//Cierre (($datos=mysql_fetch_array($rs)		
			}
		}//Cierre 
		
		//Verificar que el combo estatus esta definido
		if(isset($_POST["sbt_agregar"])){
			//Variable que almacena el nombre del Archivo Fisico en caso que este haya sido cargado
			$archivo="";
			//Variable que indica si el archivo fue subido con éxito
			$resSubirArch="";
						
			//Verificar si esta definido el Arreglo de Archivos y tiene registro agregado
			if (isset($_FILES["file_documento"]["name"])&&$_FILES["file_documento"]["name"]!=""){
				$archivo=$_FILES["file_documento"]["name"];
				if(isset($_SESSION['registroFotografico'])){
					$tam = 1;
					//Ciclo que nos permite contar las fotos registradas para dicha anomalia
					foreach($_SESSION['registroFotografico'] as $key => $arrVale){
						//Comparamos loq ue viene en la sesión con la anomalia tomada del get
						if($arrVale['anomalia']==$anomalia){					
							$tam++;
						}
					}
				}
				else{
					$tam = 1;
				}
				//$resSubirAcrh(Indicara si el archivo es valido
				$resSubirArch = subirFotos($_POST['txt_claveRegFot'],$anomalia, $tam);
			}
			
			
			//Si ya esta definido el arreglo $fotos, entonces agregar el siguiente registro a el
			if(isset($_SESSION['registroFotografico'])){			
				if($resSubirArch){
					$tam = 1;
					//Ciclo que nos permite contar las fotos registradas para dicha anomalia
					foreach($_SESSION['registroFotografico'] as $key => $arrVale){
						//Comparamos loq ue viene en la sesión con la anomalia tomada del get
						if($arrVale['anomalia']==$anomalia){					
							$tam++;
						}
					}
					//Guardar los datos en el arreglo
					$registroFotografico[] = array("archivo"=>$anomalia."_".$tam."_".$archivo, "clave"=>$_POST['txt_claveRegFot'], "anomalia"=>$anomalia, 
						"tam"=>$tam,"band"=>0);
				}
			}
			//Si no esta definido el arreglo $fotos definirlo y agregar el primer registro
			else{
				if($resSubirArch){			
					//Guardar los datos en el arreglo
					$registroFotografico = array(array("archivo"=>$anomalia."_".$tam."_".$archivo, "clave"=>$_POST['txt_claveRegFot'], "anomalia"=>$anomalia,
					"tam"=>$tam, "band"=>0));
					$_SESSION['registroFotografico'] = $registroFotografico;	
				}
			}	
		}//Cierre if(isset($_POST["sbt_agregar"])){
		
		
		//Verificar que este definido el Arreglo de fotos, si es asi, lo mostramos en el formulario
		if(isset($_SESSION["registroFotografico"])){
			//Contador que nos permitira conocer si hay mas de un registro en la sesion con la anomalia registrada
			$contAux=1;
			//Variable bandera quie nos permite conmocer si se ah ingresado por lo menos una foto para ese registro
			$band=0;
			//Ciclo que nos permite contar las fotos registradas para dicha anomalia
			foreach($_SESSION['registroFotografico'] as $key => $arrVale){
				//Comparamos loq ue viene en la sesión con la anomalia tomada del get
				if($arrVale['anomalia']==$anomalia){					
					$contAux++;
				}
			}
			//Si contador auxiliar es diferente de 1; quiere decir que por lo menos tiene un registro y se puede agregar los otros sin ningun inconveniente
			//Esto se hizo para evitar que se mostraran los encabezados de la funcion mostrar fotos reg; ya que aparece cuando se encuentra ddefinida la sesion
			if($contAux!=1){
				//Contamos el arreglo para conocer el numero correspondiente a la foto		
				if(isset($_SESSION['registroFotografico'])){
					$tam=count($_SESSION['registroFotografico']);
				}
				else{
					$tam = 1;
				}
				//Funcion que nos muestra el registro de las fotografias
				mostrarFotossReg($registroFotografico,$anomalia, $tam);
			}
		}
				
	?>
	<fieldset class="borde_seccion" id="tabla-agregarFotos" name="tabla-agregarFotos">
	<legend class="titulo_etiqueta">Registro Fotogr&aacute;fico Recorrido de Seguridad <?php echo $clave; ?></legend>	
	<br>
	<form onSubmit="return valFormCargarFotoRec(this);" name="frm_agregarFotoRecorrido" method="post" action="verModFotografico.php" enctype="multipart/form-data">
	<table width="678" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td width="116" ><div align="right">Clave</div></td>
			<td width="612">
				<input type="text" id="txt_claveRegFot" name="txt_claveRegFot" value="<?php echo $clave;?>" size="10" 
				style="background-color:#999999; color:#FFFFFF" class="caja_de_texto"
				readonly="readonly"/>
			</td>
		</tr>
		<tr>
	        <td valign="top"><div align="right">Cargar Archivo</div></td>
		    <td valign="top">
				<input type="file" name="file_documento" id="file_documento" size="36" value=""/>
				<input type="hidden" name="hdn_docValido" id="hdn_docValido" value="si" />				
			</td>
		</tr>
    	<tr align="center">
			<td colspan="4">
				<input type="hidden" name="hdn_botonSel" id="hdn_botonSel" value="x"/>
				<input type="hidden" name="hdn_carpeta" id="hdn_carpeta" value="<?php echo $anomalia;?>"/>
				<input type="hidden" name="hdn_datosSesion" id="hdn_datosSesion" 
					<?php if(isset($_SESSION['registroFotografico'])){ ?> value="si" <?php } else {?> value="no" <?php } ?>/>
					<?php if(isset($_SESSION["registroFotografico"])){?>
						<input name="btn_finalizar" type="button" class="botones" value="Finalizar" 
						onclick="window.close();" title="Terminar de guardar el Registro Fotogr&aacute;fico"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?php }?>
				<input name="sbt_agregar" type="submit" class="botones" value="Cargar" onMouseOver="window.status='';return true;" 
                title="Registrar los datos del Documento Actual"/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input name="ctb_cerrar" type="button" class="botones" value="Cerrar" onClick="window.close();" 
    	        title="Vuelve al Registro de Recorridos de Seguridad"/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar los campos"/>
			</td>
   		</tr>
	</table>
	</form>
</fieldset>