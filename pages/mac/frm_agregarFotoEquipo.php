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
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo 				op_operacionesBD.php
		include ("head_menu.php");
		//Este archivo contiene las operaciones para Agregar las fotos de los equipos y permitir registrarlos en la bitacora preventiva
		include ("op_registrarBitacora.php");
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>

    <style type="text/css">
		<!--
		#titulo-agregar-documentos { position:absolute; left:30px; top:146px; width:200px; height:20px; z-index:11;}
		#tabla-agregarEquipoFotos { position:absolute; left:30px; top:190px; width:746px; height:206px; z-index:12; padding:15px; padding-top:0px;}
		#tabla-fotos{position:absolute;left:30px;top:440px;width:746px;height:170px;z-index:13;overflow:scroll;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-agregar-documentos">Registro Fotográfico </div>
	<?php 
		if(isset($_SESSION["bitacoraPrev"]))
			$clave=$_SESSION["bitacoraPrev"]["txt_claveBitacora"];
		else 
			$clave=$_SESSION["bitacoraCorr"]["txt_claveBitacora"];
		 ?>
	<fieldset class="borde_seccion" id="tabla-agregarEquipoFotos" name="tabla-agregarEquipoFotos">
	<legend class="titulo_etiqueta">Registrar Fotogr&aacute;fico Bit&aacute;cora <?php echo $clave; ?></legend>	
	<br>
	<form name="frm_agregarFotoEquipo" method="post" action="frm_agregarFotoEquipo.php"  onsubmit=" return valForAgregarFotoBitacora(this);" enctype="multipart/form-data">
		<table width="763" border="0" cellpadding="5" cellspacing="5">
			<tr>
			  <td width="76" align="right"><div align="right">Bit&aacute;cora</div>
			    <div align="right"></div></td>
    			<td width="167">
					<input type="text" id="txt_claveBitacora" name="txt_claveBitacora" value="<?php echo $clave;?>" 
					readonly="readonly"/>
				</td>
			</tr>
			<tr>
				<td align="right"><div align="right">Estatus</div></td>
        		<td>
                	<select name="cmb_estatus" id="cmb_estatus" class="combo_box">
						<option selected="selected" value=""> Estatus</option>
						<option value="ANTES">ANTES SERVICIO</option>
						<option value="DESPUES">DESPUES SERVICIO</option>
					</select>				
				</td>
			</tr>
			<tr>
	    	    <td valign="top"><div align="right">Cargar Archivo</div></td>
			    <td valign="top">
					<input type="file" name="file_documento" id="file_documento" size="36" value="" onchange="validarDocumento(this);"/>
					<input type="hidden" name="hdn_docValido" id="hdn_docValido" value="si" />				</td>
			</tr>
    		<tr align="center">
				<td colspan="4">
					<input name="sbt_registrar" type="submit" class="botones" value="Registrar" onmouseover="window.status='';return true;" 
                    title="Registrar los datos del Documento Actual"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php if(isset($_SESSION["bitacoraPrev"])){
						if(isset($_POST["cmb_estatus"])){?>
							<input name="btn_finalizar" type="button" class="botones" value="Finalizar"
                   			onclick="location.href='frm_bitacoraMttoPreventivo.php?guardar=si'"; 
                    		title="Terminar de guardar el Registro Fotogr&aacute;fico"/>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?php }?>
						<input name="btn_cancelar" type="button" class="botones" value="Cancelar" 
						onclick="location.href='frm_bitacoraMttoPreventivo.php?id_bit=<?php echo $clave;?>&cancelar';" 
    	                title="Guarda el Equipo y Vuelve al Men&uacute; de Bit&aacute;cora"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?php }
						else{
							if(isset($_POST["cmb_estatus"])){?>
							<input name="btn_finalizar" type="button" class="botones" value="Finalizar"
							onclick="location.href='frm_bitacoraMttoCorrectivo.php?guardar=si'"; 
							title="Finalizar el guardado del Registro Fotogr&aacute;fico"/>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<?php }?>
						<input name="btn_cancelar" type="button" class="botones" value="Cancelar" 
						onclick="location.href='frm_bitacoraMttoCorrectivo.php?id_bit=<?php echo $clave;?>&cancelar';" 
    	                title="Vuelve al Registro de Bit&aacute;cora"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?php }?>
                    <input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar los campos"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</td>
   			</tr>
	</table>
			</form>
</fieldset>
	<?php
		//Verificar que el combo estatus esta definido
		if (isset($_POST["cmb_estatus"])){
			//Variable que almacena el nombre del Archivo Fisico en caso que este haya sido cargado
			$archivo="";
			//Variable que indica si el archivo fue subido con éxito
			$resSubirArch="";
						
			//Verificar si esta definido el Arreglo de Archivos y tiene registro agregado
			if (isset($_FILES["file_documento"]["name"])&&$_FILES["file_documento"]["name"]!=""){
				$archivo=$_FILES["file_documento"]["name"];
				$resSubirArch = subirFotos($clave);
			}
			
			
			//Si ya esta definido el arreglo $fotos, entonces agregar el siguiente registro a el
			if(isset($_SESSION['fotos'])){			
				
				if($resSubirArch){
					//Guardar los datos en el arreglo
					$fotos[] = array("archivo"=>$archivo, "estatus"=>$_POST["cmb_estatus"]);
				}
			}
			//Si no esta definido el arreglo $fotos definirlo y agregar el primer registro
			else{			
				//Guardar los datos en el arreglo
				$fotos = array(array("archivo"=>$archivo, "estatus"=>$_POST["cmb_estatus"]));
				$_SESSION['fotos'] = $fotos;	
			}	
		}
		
		
		//Verificar que este definido el Arreglo de fotos, si es asi, lo mostramos en el formulario
		if (isset($_SESSION["fotos"])){
			echo "<div id='tabla-fotos' class='borde_seccion2'>";
			mostrarFotossReg($fotos);
			echo "</div>";
		}
				
	?>
</body>
<?php  }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>