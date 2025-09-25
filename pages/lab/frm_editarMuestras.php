<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml"><?php 

	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Laboratorio
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Inlcuir el archivo que contiene las funciones para almacenar los datos en la BD de Laboratorio
		include ("op_gestionarMuestras.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionLaboratorio.js" ></script>
	<script type="text/javascript" src="includes/ajax/clavesConcreto.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>	    
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-gestionar {position:absolute;left:32px;top:147px;	width:210px;height:20px;z-index:11;}
		#seleccionar-muestra {position:absolute;left:30px;top:190px;width:450px;height:149px;z-index:12;}
		#tabla-editarMuestra {position:absolute;left:30px;top:190px;width:740px;height:370px;z-index:11;}
		#div-calendario {position:absolute; left:634px; top:335px; width:30px; height:26px; z-index:12; }
		#consulta-muetras { position:absolute; left:30px; top:190px; width:940px; height:400px; z-index:11; overflow:scroll; }
		#boton-regresar-consulta { position:absolute; left:30px; top:640px; width:940px; height:40px; z-index:12; }
		-->
    </style>
</head>
<body>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-gestionar">Editar/Consultar Muestras</div><?php
	
	
	//Mostrar el Formulario donde se seleccionan las muestras, cuando los botones de Consultar(sbt_consultar) y Modificar(sbt_modificar) no esten definidos en el POST
	if(!isset($_POST['sbt_consultar']) && !isset($_POST['sbt_modificar']) && !isset($_POST['sbt_modificarMuestra'])){?>
		<fieldset class="borde_seccion" id="seleccionar-muestra" name="seleccionar-muestra">
		<legend class="titulo_etiqueta">Seleccionar Muestra a Editar</legend>	
		<br>
		<form onSubmit="return valFormSeleccionarMuestra(this);" name="frm_seleccionarMuestra" method="post" action="frm_editarMuestras.php">
			<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td align="right">Codigo/Localizaci&oacute;n</td>
					<td><?php 
						$res = cargarComboConId("cmb_codLocalizacion","codigo_localizacion","codigo_localizacion","muestras","bd_laboratorio","Seleccionar","",
						"cargarCombo(this.value,'bd_laboratorio','muestras','id_muestra','codigo_localizacion','cmb_idMuestra','Muestra','')");
						if($res==0){?>
							<span class="msje_correcto">No Hay Datos Registrados</span><?php
						}
					?></td>
				</tr>
				<tr>
					<td align="right">Muestra</td>
					<td>
						<select name="cmb_idMuestra" id="cmb_idMuestra" class="combo_box">
							<option value="">Muestra</option>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center"> 
						<input type="hidden" name="hdn_botonClic" id="hdn_botonClic" value="" />
						<input type="submit" name="sbt_consultar" value="Consultar" title="Consultar la Muestra Seleccionada" class="botones"
						onmouseover="window.status='';return true" onclick="hdn_botonClic.value='consultar'" />
						&nbsp;&nbsp;&nbsp;
						<input type="submit" name="sbt_modificar" value="Modificar" title="Modificar los Registros Seleccionados" class="botones"
						onmouseover="window.status='';return true" onclick="hdn_botonClic.value='modificar'" />
						&nbsp;&nbsp;&nbsp;					
						<input name="btn_regresar" id="btn_regresar" type="button" class="botones" value="Regresar" 
						title="Regresar a la Selecci&oacute;n de Operaci&oacute;n a Realizar" onclick="location.href='frm_gestionarMuestras.php'"/>
					</td>
				</tr>
			</table>
		</form>
		</fieldset><?php
	}//Cierre if(!isset($_POST['sbt_consultar'])) 
	
	

	
	//Si esta definido el boton Modificar(sbt_modificar) en el POST, mostrar los datos de la muestra seleccionada en el formulario para su edición
	if(isset($_POST['sbt_modificar'])){		
		//Obtener los de la muestra seleccionada
		$conn = conecta("bd_laboratorio");
		//Ejecutar la consulta para obtener los datos
		$rs = mysql_query("SELECT * FROM muestras WHERE id_muestra = '".$_POST['cmb_idMuestra']."'");
		//Obtener los datos del ResultSet sin comprobación ya que se tiene la certeza de que existen datos en la consulta realizada
		$datos_muestra = mysql_fetch_array($rs);
		//Cerrar la conexion con la BD de Laboratorio
		mysql_close($conn);
		
		
		//Desplegar los datos en el Formulario?>		
		<fieldset class="borde_seccion" id="tabla-editarMuestra" name="tabla-editarMuestra">
		<legend class="titulo_etiqueta">Ingrese la Informaci&oacute;n de la Mezcla</legend>	
		<br>
		<form onSubmit="return valFormModificarMuestra(this);" name="frm_modificarMuestra" method="post" action="frm_editarMuestras.php">
		<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td><div align="right">*Mezcla</div></td>
				<td colspan="3"><?php
					$res = cargarComboConId("cmb_idMezcla","nombre","id_mezcla","mezclas","bd_laboratorio","Mezclas","$datos_muestra[mezclas_id_mezcla]","asignarCodigo();");
					if($res==0){?>
						<span class="msje_correcto">No Hay Mezclas Registrads</span><?php
					}?>			
				</td>
			</tr>
			<tr>
				<td><div align="right">*Id Muestra </div></td>
				<td colspan="3">
					<input name="txt_idMuestra" type="text" class="caja_de_texto" id="txt_idMuestra" value="<?php echo $datos_muestra['id_muestra']; ?>" 
					size="50" maxlength="2" readonly="readonly" />			
					<input type="hidden" name="hdn_idMuestraOriginal" value="<?php echo $datos_muestra['id_muestra']; ?>" />
				</td>
			</tr>
			<tr>
				<td><div align="right">*Tipo de Prueba</div></td>
				<td>
					<select name="cmb_tipoPrueba" id="cmb_tipoPrueba" class="combo_box" onchange="activarCampos(this)">
						<option value="">Seleccionar</option>
						<option value="CONCRETO" <?php if($datos_muestra['tipo_prueba']=="CONCRETO"){?> selected="selected" <?php }?>>CONCRETO</option>
						<option value="OBRA DE ZARPEO" <?php if($datos_muestra['tipo_prueba']=="OBRA DE ZARPEO"){?> selected="selected" <?php }?>>OBRA DE ZARPEO</option>
						<option value="OBRA EXTERNA" <?php if($datos_muestra['tipo_prueba']=="OBRA EXTERNA"){?> selected="selected" <?php }?>>OBRA EXTERNA</option>
					</select>			
				</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>            
			</tr>
			<tr>
				<td width="25%" align="right">*N° de Muestra</td>
				<td width="25%">
					<input type="text" name="txt_noMuestra" id="txt_noMuestra" value="<?php echo $datos_muestra['num_muestra'];?>" size="5" maxlength="2" 
					onkeypress="return permite(event, 'num',2);" class="caja_de_texto" onblur="calcularIdMuestra(1);"
					<?php if($datos_muestra['tipo_prueba']=="CONCRETO"){?>  readonly="readonly"<?php }?> />			
				</td>
				<td width="25%" align="right">*Fecha Colado</td>
				<td width="25%">
					<input type="text" name="txt_fechaColado" id="txt_fechaColado" value="<?php echo modFecha($datos_muestra['fecha_colado'],1); ?>" size="10" 
					readonly="readonly" class="caja_de_texto" />
				</td>
			</tr>		
			<tr>
				<td align="right">*Revenimiento</td>
				<td>
					<input type="text" name="txt_revenimiento" id="txt_revenimiento" value="<?php echo number_format($datos_muestra['revenimiento'],2,".",","); ?>" 
					size="10" maxlength="10" class="caja_de_texto" onkeypress="return permite(event, 'num',2);" 
					onchange="formatCurrency(txt_revenimiento.value,'txt_revenimiento');"/>&nbsp;cm.
				</td>
				<td align="right">*F' c Proyecto</td>
				<td>
					<input type="text" name="txt_fProyecto" id="txt_fProyecto" value="<?php echo number_format($datos_muestra['fprimac_proyecto'],2,".",","); ?>" 
					size="10" maxlength="10" class="caja_de_texto" onkeypress="return permite(event, 'num_car',4);" 
					onchange="formatCurrency(txt_fProyecto.value,'txt_fProyecto');" />&nbsp;Kg./cm&sup2;			
				</td>
			</tr>        
			<tr>
				<td><div align="right">**Localizaci&oacute;n</div></td>
				<td>
					<select name="cmb_localizacion" id="cmb_localizacion" class="combo_box" onchange="agregarNvoLugar(this); calcularIdMuestra(1);" 
					<?php if($datos_muestra['tipo_prueba']=="CONCRETO"){?> disabled="disabled"<?php }?>>
						<option value="">Localizaci&oacute;n</option><?php 
						$conn = conecta("bd_laboratorio");//Conectarse con la BD de Laboratorio
						//Ejecutar la Sentencia para Obtener las Unidades de Medida de la Tabla de Detalles del Pedido de la BD de Compras
						$rs_lugares = mysql_query("SELECT DISTINCT codigo_localizacion FROM muestras WHERE tipo_prueba!='CONCRETO' ORDER BY codigo_localizacion");
						if($lugares=mysql_fetch_array($rs_lugares)){
							//Colocar los lugares encontrados
							do{
								if($lugares['codigo_localizacion']==$datos_muestra['codigo_localizacion'])
									echo "<option value='$lugares[codigo_localizacion]' selected='selected'>$lugares[codigo_localizacion]</option>";							
								else
									echo "<option value='$lugares[codigo_localizacion]'>$lugares[codigo_localizacion]</option>";
							}while($lugares=mysql_fetch_array($rs_lugares));
						}					
						mysql_close($conn);?>
						<option value="NUEVA">Agregar Nueva</option>
					</select>					
				</td>
				<td><div align="right">**C&oacute;digo</div></td>
				<td colspan="3">
					<input type="text" name="txt_codigo" id="txt_codigo" value="<?php echo $datos_muestra['codigo_localizacion']; ?>" size="40" maxlength="50" 
					readonly="readonly" onkeypress="return permite(event, 'num_car',4);" class="caja_de_texto" />			
				</td>
			</tr>
			<tr>
				<td colspan="4"><strong>* Datos marcados con asterisco son <u>obligatorios</u></strong></td>
			</tr>
				<tr>
				<td colspan="4"><strong>** Datos marcados con doble asterisco son <u>obligatorios</u> dependiendo del tipo de prueba</strong></td>
			</tr>
			<tr>
				<td colspan="4">
					<div align="center">
						<input name="sbt_modificarMuestra" id="sbt_modificarMuestra" type="submit" class="botones" value="Modificar" title="Modificar Datos de la Muestra" 
						onmouseover="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;
						<input name="rst_restablecer" type="reset" class="botones" id="rst_restablecer"  value="Restablecer" title="Restablecer los Datos del Formulario" 
						onmouseover="window.status='';return true" />
						&nbsp;&nbsp;&nbsp;
						<input name="btn_regresar" id="btn_regresar" type="button" class="botones" value="Regresar" 
						title="Regresar a la Selecci&oacute;n de Muestras" onclick="location.href='frm_editarMuestras.php'"/>
					</div>			
				</td>
			</tr>
		</table>
		</form>
		</fieldset>
		
		<div id="div-calendario">
		  <input type="image" name="txt_imgFechaColado" id="txt_imgFechaColado" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_modificarMuestra.txt_fechaColado,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
			title="Seleccionar Fecha de Colado"/>
		</div><?php
		
	}//Cierre if(isset($_POST[['sbt_modificar']))
	
	
	//Si esta definido el boton sbt_modificarMuestra en el POST, proceder a modificar los datos de la muestra seleccionada en la BD de Laboratorio
	if(isset($_POST['sbt_modificarMuestra'])){		
		modificarDatosMuestra();
	}
	
	
	
	//Si esta definido el boton Consultar(sbt_consultar) en el POST, mostrar la muesttra seleccionada o las muestras de la Localizaación/Codigo seleccionado
	if(isset($_POST['sbt_consultar'])){
		//Obtener los de la muestra seleccionada
		$conn = conecta("bd_laboratorio");
		
		//Crear la Senetcnia SQL, dependiendo de los parametros seleccionados
		$sql_stm = "";
		$msg = "";
		if($_POST['cmb_idMuestra']==""){
			$sql_stm = "SELECT * FROM muestras WHERE codigo_localizacion = '".$_POST['cmb_codLocalizacion']."'";
			$msg = "Datos de las Muestras de ".$_POST['cmb_codLocalizacion'];
		}
		else if($_POST['cmb_idMuestra']!=""){
			$sql_stm = "SELECT * FROM muestras WHERE codigo_localizacion = '".$_POST['cmb_codLocalizacion']."' AND id_muestra = '".$_POST['cmb_idMuestra']."'";
			$msg = "Datos de la Muestra ".$_POST['cmb_idMuestra'];
		}
		
		//Ejecutar la consulta para obtener los datos
		$rs = mysql_query($sql_stm);
		//Obtener los datos del ResultSet
		if($datos_muestras=mysql_fetch_array($rs)){
			//Colocar el Encabezado de la Tabla donde se mostraran los datos?>
			<div id="consulta-muetras" class="borde_seccion2" align="center">
			<table width="150%" cellpadding="5" class="tabla_frm">
				<caption class="titulo_etiqueta"><?php echo $msg; ?></caption>
				<tr>
					<td class="nombres_columnas">ID MUESTRA</td>
					<td class="nombres_columnas">MEZCLA</td>
					<td class="nombres_columnas">NO. MUESTRA</td>
					<td class="nombres_columnas">TIPO DE PRUEBA</td>
					<td class="nombres_columnas">CODIGO/LOCALIZACION</td>
					<td class="nombres_columnas">FECHA COLADO</td>
					<td class="nombres_columnas">REVENIMIENTO</td>
					<td class="nombres_columnas">F' C PROYECTO</td>
					<td class="nombres_columnas">DIAMETRO</td>
					<td class="nombres_columnas">AREA</td>
				</tr><?php
			
			//Manipular el Diseño de la Tabla
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{
				//Desplegar el contenido con los datos de las muestras seleccionadas
				echo "
				<tr>
					<td class='$nom_clase'>$datos_muestras[id_muestra]</td>
					<td class='$nom_clase'>$datos_muestras[mezclas_id_mezcla]</td>
					<td class='$nom_clase'>$datos_muestras[num_muestra]</td>
					<td class='$nom_clase'>$datos_muestras[tipo_prueba]</td>
					<td class='$nom_clase'>$datos_muestras[codigo_localizacion]</td>
					<td class='$nom_clase'>".modFecha($datos_muestras['fecha_colado'],1)."</td>
					<td class='$nom_clase'>".number_format($datos_muestras['revenimiento'],2,".",",")." CM</td>
					<td class='$nom_clase'>".number_format($datos_muestras['fprimac_proyecto'],2,".",",")." KG./CM&sup2;</td>
					<td class='$nom_clase'>$datos_muestras[diametro] CM</td>
					<td class='$nom_clase'>$datos_muestras[area] CM&sup2;</td>
				</tr>";		
				
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
							
			}while($datos_muestras=mysql_fetch_array($rs));?>
			</table>
			</div>
			<div id="boton-regresar-consulta" align="center">
				<input name="btn_regresar" id="btn_regresar" type="button" class="botones" value="Regresar" 
				title="Regresar a la Selecci&oacute;n de Muestras" onclick="location.href='frm_editarMuestras.php'"/>
			</div><?php
		}//Cierre if($datos_muestras=mysql_fetch_array($rs))
		
		//Cerrar la conexion con la BD de Laboratorio
		mysql_close($conn);		
	}//Cierre if(isset($_POST[['sbt_consultar']))?>	            
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>