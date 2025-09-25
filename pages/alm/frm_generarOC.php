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
		//Manejo de la funciones para registrar la Entrada de Materiales en la BD 
		include ("op_generarOC.php");
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/validacionAlmacen.js" ></script>
	
    <style type="text/css">
		<!--
		#titulo-generar { position:absolute; left:30px; top:146px; width:192px; height:20px; z-index:11; }
		#tabla-otrosMat { position:absolute; left:590px; top:190px; width:380px; height:180px; z-index:14; }
		#datos-gral { position:absolute; left:30px; top:407px; width:940px; height:119px; z-index:15; }
		#tabla-material { position:absolute; left:30px; top:190px; width:507px; height:180px; z-index:16; }
		#material-orden { position:absolute; left:30px; top:569px; width:940px; height:200px; z-index:14; }
		#procesando {position:absolute; left:406px; top:274px; width:133px; height:86px; z-index:17; }
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-generar">Generar Orden de Compra </div>

<?php //Si la variable $txt_areaSolicitante aun no esta definida, desplegar los formularios para solicitar la informacion de la Orden de Compra
	if(!isset($_POST['txt_areaSolicitante'])){ ?>
    <fieldset id="tabla-material" class="borde_seccion">
	<legend class="titulo_etiqueta">Material del Cat&aacute;logo de Minera Fresnillo</legend>
	<br>
	<form onsubmit="return valFormGenerarOC(this);" name="frm_generarOrdenC" method="post" action="frm_generarOC.php" >
	<table width="100%" border="0" cellpadding="5" class="tabla_frm" >
		<caption></caption>
		<tr>
			<td width="70"><div align="right">Material</div></td>
			<td width="276"><?php
					$descripcion = "";
					if(isset($_POST['nom_mat'])){
						$descripcion = $nom_mat;					
					} 
					$conn = conecta("bd_almacen");
					$result1 = mysql_query("SELECT codigo_mf,descripcion FROM catalogo_mf ORDER BY descripcion");	
					if($row1=mysql_fetch_array($result1)){?>
					<select name="cmb_codigoMF" size="1" class="combo_box">
					<option value="" selected="selected">Material</option><?php	
						do{
							if ($row1['descripcion'] == $descripcion){
								echo "<option value='$row1[codigo_mf]' selected='selected' title='$row1[codigo_mf]'>$row1[descripcion]</option>";
							}	
							else{
								echo "<option value='$row1[codigo_mf]' title='$row1[codigo_mf]'>$row1[descripcion]</option>";
							}	
						}while ($row1=mysql_fetch_array($result1))?>
                    </select><?php 
						$aux=1;
					}
					else { 
                        echo "<label class='msje_correcto'><u><strong>NO</strong></u> Hay Materiales Registrados</label>";
						$aux=0;
					}	
					//Cerrar la conexion con la BD		
					mysql_close($conn);	?>								
								
		  </td>
	  </tr>
		<tr>
			<td><div align="right">Cantidad</div></td>
			<td><input name="txt_cantidad" type="text" class="caja_de_texto" id="txt_cantidad"  size="10" maxlength="10" onkeypress="return permite(event,'num');" /></td>
		</tr>
		<tr>
			<td colspan="2"><div align="center"><?php 
			if($aux==1){?>
                  <input type="submit" name="btn_agregarOtro" class="botones" value="Agregar Otro" onmouseover="window.status='';return true" 
                  title="Agregar Material al Registro de la Orden de Compra" /><?php 
			  }?>
			  </div></td>
		</tr>
	</table>
	</form>
</fieldset>
	
	
	<fieldset id="tabla-otrosMat" class="borde_seccion">
	<legend class="titulo_etiqueta">Material no Registrado en el Cat&aacute;logo de Minera Fresnillo </legend>
	<br>
	<form onsubmit="return valFormMaterialesOC(this);" name="frm_MaterialesOC" method="post" action="frm_generarOC.php">
 	<table width="100%" border="0" cellpadding="5">
		<tr>
   		  <td><div align="right">Clave</div></td>
	  	  <td><input name="txt_clave" type="text"  class="caja_de_texto" id="txt_clave" onkeypress="return permite(event,'num_car');" size="10" maxlength="10" /></td>
		</tr>
		<tr>	
      		<td><div align="right">Material</div></td>
			<td><input name="txt_descripcion" type="text"  class="caja_de_texto" id="txt_descripcion" onkeypress="return permite(event,'num_car');" size="30" maxlength="60" /></td>
		</tr>
		<tr>
			<td><div align="right">Cantidad</div></td>
			<td><input name="txt_cantidad2" type="text" class="caja_de_texto" id="txt_cantidad2" onkeypress="return permite(event,'num');" size="10" maxlength="10" /></td>
		</tr>
		<tr>	       
			<td colspan="2"><div align="center">
			  <input type="submit" name="btn_agregarOtro2" class="botones" value="Agregar Otro" onmouseover="window.status='';return true" title="Agregar Material al Registro de la Orden de Compra" />
			  </div></td>
		</tr>
	</table>
	</form>
</fieldset>


	<fieldset id="datos-gral" class="borde_seccion">
	<legend class="titulo_etiqueta">Informaci&oacute;n Complementaria de la Orden de Compra</legend>
	<br>
	<form onsubmit="return valFormInformacionOC(this);" name="frm_InformacionOC" method="post" action="frm_generarOC.php">
  	<table width="100%" border="0" cellpadding="5" class="tabla_frm">
		<tr>
		 	<td><div align="right">&Aacute;rea Solicitante</div></td>
		 	<td><input name="txt_areaSolicitante" type="text" id="txt_areaSolicitante" onkeypress="return permite(event,'num_car');" size="30" maxlength="45" /></td>
	    <td><div align="right">Fecha
	      </div>
	    <td><input name="txt_fecha" type="text"  disabled="disabled" id="txt_fecha" value="<?php echo verFecha(4);?>" size="10" maxlength="10" />
	      <input type="hidden" name="hdn_fecha" value="<?php echo verFecha(3);?>"  />
	    <td><div align="right">Solicit&oacute;        		        
	      </div>
	    <td><input name="txt_solicitanteOC" type="text" id="txt_solicitanteOC" onkeypress="return permite(event,'num_car');" size="40" maxlength="60" /></td>
		</tr>
    	<tr>
      		<td colspan="6" align="center">
				<input type="hidden" name="hdn_materialOC" id="hdn_materialOC" <?php if(isset($_POST['cmb_codigoMF']) || isset($_POST['txt_clave']) || isset($_SESSION['datosOC'])) echo "value='si'"; else echo "value='no'"; ?> />
   		    	<input name="sbt_generar" type="submit" class="botones" id="sbt_generar" value="Generar" onmouseover="window.status='';return true" title="Generar Orden de Compra"/>
				&nbsp;&nbsp;&nbsp;
	        	<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Generar Orden de Compra" onclick="location.href='menu_ordenCompra.php'" />
			</td>	        
    	</tr>
	</table>
	</form>
</fieldset>
	
	
	<div id="material-orden">
	<?php 			
		//Aquí se registran en el arreglo de datos de la orden de los materiales agregados desde el catalogo de Minera Fresnillo
		if(isset($_POST['cmb_codigoMF']) && isset($_POST['txt_cantidad'])){			
			//Si ya esta definido el arreglo $datosOC en la SESSION, entonces agregar el siguiente registro a &eacute;l
			if(isset($_SESSION['datosOC'])){						
				//Verificar que el registro que se quiere agregar no exista en el arreglo
				if(!verRegDuplicado($datosOC, "clave", $cmb_codigoMF)){
					//Obtener el nombre del material para agregarlo al arreglo
					$descripcion = obtenerDato("bd_almacen","catalogo_mf", "descripcion", "codigo_mf", $cmb_codigoMF);								
					//Guardar los datos en el arreglo
					$datosOC[] = array("clave"=>$cmb_codigoMF, "descripcion"=>$descripcion, "cantidad"=>$txt_cantidad, "org"=>"cat");		
				}													
			}
			//Si no esta definido el arreglo $datosOC en la SESSION definirlo y agregar el primer registro
			else{		
				//Obtener el nombre del material para agregarlo al arreglo
				$descripcion = obtenerDato("bd_almacen","catalogo_mf", "descripcion", "codigo_mf", $cmb_codigoMF);				
				//Crear el arreglo con el primer registro
				$datosOC = array(array("clave"=>$cmb_codigoMF, "descripcion"=>$descripcion, "cantidad"=>$txt_cantidad, "org"=>"cat"));
				//Guardar el arreglo en la SESSION
				$_SESSION['datosOC'] = $datosOC;	
				//Crear el ID de la Entrada de Material
				$_SESSION['id_ordenOC'] = obtenerIdOC();
			}				
		}	
	
		//Aquí se registran en el arreglo de datos de la orden de los materiales agregados desde el formulario de nuevos Materiales
		if(isset($_POST['txt_clave']) && isset($_POST['txt_descripcion']) && isset($_POST['txt_cantidad2']) ){			
			//Si ya esta definido el arreglo $datosRequisicion en la SESSION, entonces agregar el siguiente registro a &eacute;l
			if(isset($_SESSION['datosOC'])){									
				//Verificar que el registro que se quiere agregar no exista en el arreglo
				if(!verRegDuplicado($datosOC, "descripcion", $txt_descripcion)){
					//Convertir a mayusculas el texto de la descripcion del material
					$txt_descripcion = strtoupper($txt_descripcion); $txt_clave = strtoupper($txt_clave);
					//Guardar los datos en el arreglo
					$datosOC[] = array("clave"=>$txt_clave, "descripcion"=>$txt_descripcion, "cantidad"=>$txt_cantidad2, "org"=>"frm");															
				}
			}
			//Si no esta definido el arreglo $datosOC en la SESSION definirlo y agregar el primer registro
			else{		
				//Convertir a mayusculas el texto de la descripcion del material
				$txt_descripcion = strtoupper($txt_descripcion); $txt_clave = strtoupper($txt_clave);	
				//Crear el arreglo con el primer registro
				$datosOC = array(array("clave"=>$txt_clave, "descripcion"=>$txt_descripcion, "cantidad"=>$txt_cantidad2, "org"=>"frm"));
				//Guardar el arreglo en la SESSION
				$_SESSION['datosOC'] = $datosOC;	
				//Crear el ID de la Entrada de Material
				$_SESSION['id_ordenOC'] = obtenerIdOC();
			}				
		}
		
		
		//Verificar que el arreglo de datos haya sido definido en la SESSION
		if(isset($_SESSION['datosOC']) && isset($_SESSION['id_ordenOC'])){
			?>
	<p align="center" class="titulo_etiqueta">Registro de la Orden de Compra No. <?php echo $_SESSION['id_ordenOC']; ?></p>
    	 	<?php mostrarRegistros($datosOC);				
		}
	
	}//Cierre if(!isset($_POST['txt_areaSolicitante']))
	else{
		guardarOrdenCompra($hdn_fecha,$txt_areaSolicitante,$txt_solicitanteOC);
		?>
		<div class="titulo_etiqueta" id="procesando">
			<div align="center">
				<p><img src="../../images/loading.gif" width="70" height="70"></p>
				<p>Procesando...</p>
			</div>
		</div>		
		<?php
	}		
	?>
</div>
    
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>