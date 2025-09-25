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
		include ("op_modificarPlanAcciones.php");
		if(isset($_SESSION['referencias'])){
			unset($_SESSION['referencias']);
		}?><head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
		<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="../../includes/validacionAseguramiento.js" ></script>
		<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js" ></script>
		<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
		<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
		<script type="text/javascript" src="includes/ajax/verificarPlanAccionesRadio.js"></script>
		<script type="text/javascript" src="../../includes/jquery/dataTable/js/jquery.js"></script>
		<script type="text/javascript" src="../../includes/jquery/dataTable/js/jquery.dataTables.js"></script>
		<script type="text/javascript" src="../../includes/jquery/dataTable/paginarTabla.js"></script>>
		<script type="text/javascript">
		$(document).ready(function(){
				$("#tabla-resultados").dataTable({
					"sPaginationType": "scrolling"
				});
		});
	</script>

	
		<style type="text/css">
		<!--
			#titulo-modificar {position:absolute;left:30px;top:146px;	width:210px;height:20px;z-index:11;}
			#tabla-resultados1 { position:absolute; left:30px; top:190px; width:945px; height:340px; z-index:21; overflow:scroll; }
			#btns-regpdf { position: absolute; left:30px; top:600px; width:945px; height:40px; z-index:23; }
			#tabla-modificarDocumento {position:absolute;left:30px;top:190px;width:546px;height:128px;z-index:12;}
			#titulo-registrar {position:absolute;left:30px;top:146px;	width:352px;height:20px;z-index:11;}
			#tabla-agregarRegistro {position:absolute;left:30px;top:190px;width:764px;height:121px;z-index:12;}
			#tabla-agregarRegistro2 {position:absolute;left:32px;top:349px;width:764px;height:170px;z-index:12;}

		-->
    </style>
	<style type="text/css" title="currentStyle">
		@import "../../includes/jquery/dataTable/css/tabla.css";
	</style>
</head>
<body>
<?php
	if(!isset($_POST["sbt_consultar"])&&!isset($_POST['sbt_modificar'])&&!isset($_POST['sbt_eliminar'])&&!isset($_GET['depto'])){?>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-modificar">Modificar Plan Acciones </div>
	<fieldset class="borde_seccion" id="tabla-modificarDocumento" name="tabla-modificarDocumento">
	<legend class="titulo_etiqueta">Seleccionar Plan de Acciones por &Aacute;rea Auditada </legend>	
	<br>
    <form onsubmit="return valFormSelDpto(this);" name="frm_modificarListaDoc" method="post" action="frm_modificarPlanAcciones.php">
        <table width="545" height="47"  cellpadding="5" cellspacing="5" class="tabla_frm">
        	<tr>
          	<td width="108" height="32"><div align="right">*&Aacute;rea Auditada </div></td>
          	<td width="400"><?php  
						$cmb_depto="";
						$conn = conecta("bd_usuarios");
						$result=mysql_query("SELECT DISTINCT UPPER(depto) AS depto FROM usuarios WHERE depto != 'Panel' AND depto != 'DireccionGral' 
											ORDER BY depto");
						if($depto=mysql_fetch_array($result)){?>
						<select name="cmb_depto" id="cmb_depto" size="1" class="combo_box">
							<option value="">Departamentos</option>
							<?php 
							do{
								if ($depto['depto'] == $cmb_depto){
									echo "<option value='$depto[depto]' selected='selected'>$depto[depto]</option>";
								}
								else{
									echo "<option value='$depto[depto]'>$depto[depto]</option>";
								}
							}while($depto=mysql_fetch_array($result)); 
							//Cerrar la conexion con la BD		
							mysql_close($conn);?>
						</select>
					<?php }
						else{
							echo "<label class='msje_correcto'> No hay Departamentos Registrados</label>
							<input type='hidden' name='cmb_depto' id='cmb_depto'/>";?>
					<?php }?>
			  </td>
        </tr>
        <tr>  
		<tr>
        	<td colspan="2">
			  <div align="center">
					<input name="sbt_consultar" type="submit" class="botones" id= "sbt_consultar" value="Consultar" title="Consultar Plan de Acciones"
					onmouseover="window.status='';return true" />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Auditoria" 
					onmouseover="window.status='';return true"  onclick="location.href('menu_auditorias.php')" />
			  </div>			</td>
        </tr>
      </table>
	</form>
</fieldset>
    <?php
	}else					
	if(!isset($_POST["sbt_modificar"])||!isset($_POST['sbt_eliminar'])){
		if(isset($_GET['depto'])){
			$_POST['cmb_depto'] = $_GET['depto'];
		}?>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-modificar">Modificar Plan Acciones </div>
	<form onsubmit="return valFormPA(this);" name="frm_modificarPA" id="frm_modificarPA" method="post" action="op_modificarPlanAcciones.php"><?php 
		echo"<div id='tabla-resultados1' class='borde_seccion2' align='center'>";
		//Si $ band regresa 0 la consulta no trajo resultados por loc ual no se debe de mostrar el boton de eliminar
		$band=mostrarResultados();
		echo "</div>";?>
		<div id="btns-regpdf" align="center">
		<?php if($band!=0){?>
			<input type="submit" name="sbt_modificar" id="sbt_modificar" value="Complementar" class="botones" title="Complementar Registro Plan Acciones" 
			onMouseOver="window.estatus='';return true" onclick="hdn_botonSel.value='complementar'"/>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input  type="hidden" name="hdn_botonSel" id="hdn_botonSel" value=""/>
			<input type="submit" name="sbt_eliminar" id="sbt_eliminar" value="Eliminar" class="botones" title="Eliminar Registro Plan Acciones" 
			onMouseOver="window.estatus='';return true" onclick="hdn_botonSel.value='eliminar';"/>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="submit" name="sbt_modificar" id="sbt_modificar" value="Modificar" class="botones" title="Modificar Registro Plan Acciones" 
			onMouseOver="window.estatus='';return true" onclick="hdn_botonSel.value='modificar'"/>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Auditorias" 
			onMouseOver="window.status='';return true" onclick="location.href='frm_modificarPlanAcciones.php'"/>
			<input type="hidden" name="hdn_depto" value="<?php echo $_POST['cmb_depto'];?>"/>
  </div>
	<?php }
		else{?>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Auditoria" 
			onMouseOver="window.status='';return true" onclick="location.href='frm_modificarPlanAcciones.php'" />
			</div>
		<?php 
		}
	}
	?>
</form>

</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>