<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Aseguramiento de Calidad
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_modificarListaMaestraDoc.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../../includes/validacionAseguramiento.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>

    <style type="text/css">
		<!--
			#titulo-modificar {position:absolute;left:30px;top:146px;	width:355px;height:20px;z-index:11;}
			#tabla-documentos { position:absolute; left:30px; top:190px; width:945px; height:340px; z-index:21; overflow:scroll; }
			#btns-regpdf { position: absolute; left:30px; top:600px; width:945px; height:40px; z-index:23; }
			#tabla-modificarDocumento {position:absolute;left:30px;top:190px;width:546px;height:194px;z-index:12;}
			#titulo-registrar {position:absolute;left:30px;top:146px;	width:352px;height:20px;z-index:11;}
			#tabla-agregarRegistro {position:absolute;left:30px;top:190px;width:764px;height:121px;z-index:12;}
			#tabla-agregarRegistro2 {position:absolute;left:32px;top:349px;width:764px;height:170px;z-index:12;}
			#calendario{position:absolute;left:687px;top:284px;width:30px;height:26px;z-index:13;}

		-->
    </style>
</head>
<body>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-modificar">Modificar Lista Maestra Documentos </div>
	<?php 
	if(!isset($_POST["sbt_consultar"])&&!isset($_POST['sbt_modificar'])){?>
	<fieldset class="borde_seccion" id="tabla-modificarDocumento" name="tabla-modificarDocumento">
	<legend class="titulo_etiqueta">Seleccionar Lista Maestra Control de Documentos </legend>	
	<br>
    <form onsubmit="return valFormSelecDoc(this);" name="frm_modificarListaDoc" method="post" action="frm_modificarListaMaestraDoc.php">
        <table width="545" height="168"  cellpadding="5" cellspacing="5" class="tabla_frm">
        	<tr>
          	<td width="108" height="32"><div align="right">Clave Manual </div></td>
          	<td width="400"><?php 
				$res=cargarComboTotal("cmb_manu","nombre","id_manual","manual_calidad","bd_aseguramiento","Manual","","cargarComboConId(this.value,'bd_aseguramiento','catalogo_clausulas','titulo_clausula','id_clausula','manual_calidad_id_manual','cmb_clausula','Clausula','');","nombre","","");
				if ($res==0){
					echo "<label class='msje_correcto'>Registre un Manual</label>";
					echo "<input type='hidden' id='cmb_manu' name='cmb_manu'/>";
					echo "<input type='hidden' id='cmb_clausula' name='cmb_clausula'/>";
				}
				?>		 	 	</td>
        </tr>
        <tr>
       	  <td width="108"><div align="right">Clausula</div></td>
          	<td><?php if ($res!=0) {?>
          	  <select name="cmb_clausula" id="cmb_clausula" class="combo_box" 
				onchange="cargarComboConId(this.value,'bd_aseguramiento','catalogo_procedimientos','nombre_procedimiento','id_procedimiento','catalogo_clausulas_id_clausula','cmb_procedimiento','Procedimiento','');">
                <option value="">Clausula</option>
              </select>
       	    <?php }
			else{
				echo "<label class='msje_correcto'>Registre Una Clausula</label>";
				echo "<input type='hidden' id='cmb_clausula' name='cmb_clausula'/>";
			}?>		</td>
        </tr>
		<tr>
       	  <td width="108"><div align="right">Procedimiento</div></td>
          	<td><?php if ($res!=0) {?>
			<select name="cmb_procedimiento" id="cmb_procedimiento" class="combo_box">
			  <option value="">Procedimiento</option>
			</select>
			<?php }
			else{
				echo "<label class='msje_correcto'>Registre Un Procedimiento</label>";
				echo "<input type='hidden' id='cmb_procedimiento' name='cmb_procedimiento'/>";
			}?>		</td>
        </tr>
        <tr>
        	<td colspan="2">
			  <div align="center">
			  	<?php if($res!=0){?>
					<input name="sbt_consultar" type="submit" class="botones" id= "sbt_consultar" value="Consultar" title="Consultar Lista Maestra"
					onmouseover="window.status='';return true" />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php }?>
				<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Lista Maestra" 
				onmouseover="window.status='';return true"  onclick="location.href('menu_listaDocumentos.php')" />
			  </div>			</td>
        </tr>
      </table>
	</form>
</fieldset>
    <?php
	}else					
	if(!isset($_POST["sbt_modificar"])){?>
<form  onsubmit="return valFormArchivo(this);" name="frm_modificarDocumento" id="frm_modificarDocumento" method="post" action="frm_modificarListaMaestraDoc2.php"><?php 
		echo"<div id='tabla-documentos' class='borde_seccion2' align='center'>";
		//Si $ band regresa 0 la consulta no trajo resultados por loc ual no se debe de mostrar el boton de eliminar
		$band=mostrarResultados();
		echo "</div>";?>
		<div id="btns-regpdf" align="center">
		<?php if($band!=0){?>
			<input type="submit" name="sbt_modificar" value="Modificar" class="botones" title="Modificar Documento" 
			onMouseOver="window.estatus='';return true"/>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Repositorio" 
			onMouseOver="window.status='';return true" onclick="location.href='frm_modificarListaMaestraDoc.php'" />
  </div>
	<?php }
		else{?>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Repositorio" 
			onMouseOver="window.status='';return true" onclick="location.href='frm_modificarListaMaestraDoc.php'" />
			</div>
		<?php 
		}
	}
	?>
</form>

</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>