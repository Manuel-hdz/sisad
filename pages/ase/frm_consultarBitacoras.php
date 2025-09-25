<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion 
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Este archivo contiene las funciones para Consultar La Bitacora seleccionada
		include ("op_consultarBitacora.php");
		include("op_consultarOrdenTrabajo.php")?>
		

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
    <script type="text/javascript" src=""></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
		<!--
		#titulo-reporte { position:absolute; left:30px; top:146px; width:136px; height:20px; z-index:11; }				
		#consultar {	position:absolute;	left:30px;	top:190px;	width:430px;height:192px;z-index:15;}
		#reporte { position:absolute; left:30px; top:190px; width:921px; height:250px; z-index:21; overflow:scroll; }
		#reporte2 { position:absolute; left:30px; top:190px; width:945px; height:80px; z-index:21; }
		#detalles { position:absolute; left:30px; top:375px; width:945px; height:250px; z-index:21; overflow:scroll; }
		#btn-cancelar {	position:absolute;	left:472px;	top:411px;	width:93px;	height:37px; z-index:22;}
		#btns-regpdf { position: absolute; left:319px; top:500px; width:400px; height:40px; z-index:23; }
		#btn-regresar { position: absolute; left:319px; top:670px; width:400px; height:40px; z-index:23; }
		#botonesConsultas { position:absolute; left:30px; top:320px; width:945px; height:40px; z-index:25;}		
		-->
    </style>
</head>
<body>

	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-reporte">Consulta Bit&aacute;cora </div><?php

	//Definimos variable para control de lo que se debe mostrar
	$band = 0;
	
	//Mostrar el Detalle de la bitacora
	if(isset($_POST['verDetalle'])){					
		$band = 1;		
		//Obtener el valor de la clave de la Entrada seleccionada
		$clave = "";
		$tam = count($_POST);
		$cont = 1;
		foreach($_POST as $nombre_campo => $valor){								
			if($cont==$tam)
				$clave = $valor;				
			$cont++;
		}?>
		
		<form action="frm_consultarBitacoras.php" name="frm_detalleBitacora" method="post"><?php 
			//Se crea la variable hidden para conservar el detalle y poder mostrar las consultas en la misma pagina?>
			<input type="hidden" name="verDetalle"/><?php 
			//Mostrar el detalle del Registro de la Bitacora Seleccionado
			mostrarDetalle($clave);?>
		</form><?php
		
		
		//Verificamos que vengan definidos los botones
		if(isset($_POST["sbt_consAct"])|| isset($_POST["sbt_consMec"])|| isset($_POST["sbt_consMat"])||(isset($_POST["sbt_consGam"]))||(isset($_POST["sbt_consFot"]))){
			?><div id="detalles" class="borde_seccion2" align="center"><?php
			//Mostramos la consulta dependiendo del boton presionado
			if(isset($_POST["sbt_consAct"])){
				mostrarDetalleActividades($clave);
			}
			if(isset($_POST["sbt_consMec"])){
				mostrarDetalleMecanico($clave);
			}
			if(isset($_POST["sbt_consMat"])){
				mostrarDetalleMateriales($clave);
			}
			if(isset($_POST["sbt_consGam"])){
				mostrarDetalleGamas($clave);
			}
			if(isset($_POST["sbt_consFot"])){
				mostrarRegistroFotos($clave);
			}
			?></div><?php
		}
	}//cierreif(isset($_POST['verDetalle']))
	
	
	//Si viene definida la orden de trabajo entonces se genera la consulta General
	if(isset($_POST['sbt_consultar']) || isset($_GET["ot"])){
		$band = 1;		
		generarConsulta();
	}		
	
	
	//Si la bandera viene en 0 mostrar las ordenes de trabajo
	if($band==0){ ?>
		<fieldset class="borde_seccion" id="consultar" name="consultar">
		<legend class="titulo_etiqueta">Consultar Bit&aacute;cora</legend>
		<br />
		<form name="frm_consultarBitacora" onSubmit="return valFormConsultarBitacora(this);" action="frm_consultarBitacoras.php" method="post">
		<table border="0" align="center" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td><div align="right">Orden de Trabajo</div></td>
				<td width="215"><?php 
					$conn = conecta("bd_mantenimiento");
					$dpto = "";
					$temp="";
					//Verificamos que usuario viene definido en la session para dar acceso a las ordenes de trabajo
					if($_SESSION["depto"]=='MttoMina')
						$dpto='MINA';
					if($_SESSION["depto"]=='MttoConcreto')
						$dpto='CONCRETO';
					//Creamos la consulta segun el usuario registrado
					if($_SESSION["depto"]=='MttoMina'||$_SESSION["depto"]=='MttoConcreto'){
						$result=mysql_query("SELECT id_orden_trabajo FROM ((orden_trabajo JOIN bitacora_mtto ON
						orden_trabajo_id_orden_trabajo=id_orden_trabajo)
						JOIN equipos ON bitacora_mtto.equipos_id_equipo=equipos.id_equipo)WHERE equipos.area='$dpto'  ORDER BY id_orden_trabajo");
						$temp=mysql_fetch_array($result);
					}
					else{
						$result=mysql_query("SELECT id_orden_trabajo FROM orden_trabajo  ORDER BY id_orden_trabajo");
						$temp=mysql_fetch_array($result);
					}
					if(!isset($_POST["cmb_OT"]))
						$cmb_OT="";
					if($temp!=""){?>
						<select name="cmb_OT" id="cmb_OT" size="1"  class="combo_box" onchange="javascript:document.frm_consultarBitacora.submit();">
							<option value="">Orden Trabajo</option><?php 
							while ($row=mysql_fetch_array($result)){
								if($row['id_orden_trabajo'] == $cmb_OT){
									echo "<option value='$row[id_orden_trabajo]' selected='selected'>$row[id_orden_trabajo]</option>";
								}
								else{
									echo "<option value='$row[id_orden_trabajo]'>$row[id_orden_trabajo]</option>";
						   		}	
							}
							//Cerrar la conexion con la BD		
						mysql_close($conn);?>
						</select> <?php
					}
					else{
						echo "<label class='msje_correcto'> No hay Ordenes de Trabajo</label>";
					}?>
              	</td>
			</tr>
			<?php if($temp!=""){?>
			<tr>
				<td><div align="right">Clave Bitacora</div></td>
				<td width="173">
					<?php //Obtenemos datos segun la orden de trabajo seleccionada
					$id_bitacora="";
					if(isset($_POST["cmb_OT"]))
						$id_bitacora=obtenerDato("bd_mantenimiento", "bitacora_mtto", "id_bitacora", "orden_trabajo_id_orden_trabajo", $_POST["cmb_OT"]);?>
					<input name="txt_bitacora" id="txt_bitacora" type="text" class="caja_de_texto" size="15" maxlength="15" value="<?php echo $id_bitacora;?>" readonly="readonly" /> 
				</td>
			</tr>
			<tr>
				<td><div align="right">Clave Equipo </div></td><?php 
					$txt_claveEquipo="";
					if(isset($_POST["cmb_OT"]))
						$txt_claveEquipo=obtenerDato("bd_mantenimiento", "bitacora_mtto", "equipos_id_equipo", "orden_trabajo_id_orden_trabajo",$_POST["cmb_OT"]);?>
				<td width="173">
					<input name="txt_equipo" id="txt_equipo" type="text" class="caja_de_texto" size="15" maxlength="15" readonly="readonly" 
                	value="<?php echo $txt_claveEquipo?>" /> 
				</td>
			</tr>
			<?php }?>
			<tr>                    
				<td align="center" colspan="2">
				<?php if($temp!=""){?>
					<input name="sbt_consultar" type="submit" class="botones" value="Consultar" 
                	onMouseOver="window.estatus='';return true" title="Consultar Bit&aacute;cora" />
                    &nbsp;&nbsp;
				<?php }?>
					<input type="button" name="btn_cancelar" value="Cancelar" class="botones" title="Regresar a Seleccionar Bit&aacute;cora" 
					onclick="location.href='frm_consultarBitacora.php'" onMouseOver="window.estatus='';return true" />   
                </td>                    
			</tr>
		</table>
		</form>
		</fieldset>			
<?php }//Cierre if($band==0) ?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>