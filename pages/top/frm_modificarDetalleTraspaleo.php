<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Topografía
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");	
		include("op_modificarTraspaleo.php");?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<script type="text/javascript" src="../../includes/validacionTopografia.js" ></script>
	<script type="text/javascript" src="includes/ajax/obtenerPrecioTraspaleo.js"></script>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
    <style type="text/css">
		<!--
		#titulo-modificarDetalle { position:absolute; left:30px; top:146px; width:270px; height:20px; z-index:11; }
		#mostrar-registros { position:absolute; left:30px; top:190px; width:940px; height:399px; z-index:12; overflow:scroll; }
		#botones-modificar { position:absolute; left:30px; top:650px; width:980px; height:70px; z-index:13; }
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div id="titulo-modificarDetalle" class="titulo_barra">Modificar Registros de Traspaleo</div><?php
	
	if(!isset($_POST['sbt_guardarDetalle'])){ ?>
		<form onsubmit="return valFormModDetalleTraspaleo(this);" name="frm_modDetalleTraspaleo" action="frm_modificarDetalleTraspaleo.php" method="post">
		<div id="mostrar-registros" class="borde_seccion2" align="center">						
			<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td align="right">Tipo de Obra: </td>
					<td align="left"><strong><?php echo $_SESSION['datosTraspaleoMod']['tipoObra'];?></strong></td>
					<td align="right">Tasa Cambio: </td>
					<td align="left">
						<strong>$ <?php echo $_SESSION['datosTraspaleoMod']['tasaCambio'];?></strong>
						<input type="hidden" name="txt_tasaCambio2" id="txt_tasaCambio2" value="<?php echo $_SESSION['datosTraspaleoMod']['tasaCambio']?>" />
					</td>
					<td align="right">Vol. M&sup3;: </td>
					<td align="left">
						<strong><?php echo $_SESSION['datosTraspaleoMod']['volumen'];?></strong>
						<input type="hidden" name="txt_volumen2" id="txt_volumen2" value="<?php echo $_SESSION['datosTraspaleoMod']['volumen']; ?>" />
					</td>
				</tr>
				<tr>
					<td align="right">Obra: </td>
					<td align="left"><strong><?php echo $_SESSION['datosTraspaleoMod']['nomObra'];?></strong></td>
					<td align="right">Secci&oacute;n: </td>
					<td align="left"><strong><?php echo $_SESSION['datosTraspaleoMod']['seccion'];?></strong></td>
					<td align="right">No. Quincena: </td>
					<td align="left"><strong><?php echo $_SESSION['datosTraspaleoMod']['noQuincena'];?></strong></td>
				</tr>
				<tr>
					<td align="right">Acumulado Quincena: </td>
					<td align="left"><strong><?php echo $_SESSION['datosTraspaleoMod']['acumQuincena'];?></strong></td>
					<td align="right">&Aacute;rea: </td>
					<td align="left"><strong><?php echo $_SESSION['datosTraspaleoMod']['area'];?></strong></td>
					<td><input type="hidden" name="hdn_incluirPrecio" id="hdn_incluirPrecio" value="si" /></td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<br /><br />
			<label class="titulo_etiqueta">Modificar el Detalle de los Registros de Traspaleo</label>
			<table width="100%" cellpadding="5">
				<tr>
					<td class="nombres_columnas">EDITAR</td>
					<td class="nombres_columnas">NO.</td>
					<td class="nombres_columnas">FECHA</td>
					<td class="nombres_columnas">ORIGEN</td>
					<td class="nombres_columnas">DESTINO</td>
					<td class="nombres_columnas">DISTANCIA</td>
					<td class="nombres_columnas">P.U.M.N.</td>
					<td class="nombres_columnas">P.U.USD</td>
					<td class="nombres_columnas">TOTAL M.N.</td>
					<td class="nombres_columnas">TOTAL USD</td>
					<td class="nombres_columnas">IMPORTE TOTAL</td>					
				</tr><?php
				//Conectarse a la BD de Topografia para obtener los registros de Traspaleo
				$conn = conecta("bd_topografia");
				
				//Crear la Sentencia para obtener los registros
				$sql_stm = "SELECT * FROM detalle_traspaleos WHERE traspaleos_id_traspaleo = '".$_SESSION['datosTraspaleoMod']['idTraspaleo']."' ORDER BY no_registro";
				//Ejecutar la Consulta
				$rs = mysql_query($sql_stm);
				//Obtener la Cantidad de Registros
				$numRegistros = mysql_num_rows($rs);
				
				if($detalleTraspaleo=mysql_fetch_array($rs)){
					$nom_clase = "renglon_gris";
					$cont = 1;
					
					//Dibujar los registros del Traspaleo								
					do{?>
						<tr>
							<td class="nombres_filas">
								<input type="checkbox" name="ckb_modRegistro<?php echo $cont; ?>" id="ckb_modRegistro<?php echo $cont; ?>" 
								onclick="activarRegistros(this);" value="<?php echo $cont; ?>" />
							</td>
							<td class="<?php echo $nom_clase; ?>"><?php echo $cont; ?></td>
							<td class="<?php echo $nom_clase; ?>">
								<input type="text" name="txt_fechaReg<?php echo $cont; ?>" id="txt_fechaReg<?php echo $cont; ?>" class="caja_de_texto" 
                                readonly="readonly" 
								value="<?php echo modFecha($detalleTraspaleo['fecha_registro'],1); ?>" size="10" maxlength="15" />
							</td>
							<td class="<?php echo $nom_clase; ?>">
								<input type="text" name="txt_origen<?php echo $cont; ?>" id="txt_origen<?php echo $cont; ?>" class="caja_de_texto" readonly="readonly" 
								value="<?php echo $detalleTraspaleo['origen']; ?>" size="25" maxlength="30" onkeypress="return permite(event,'num_car',0);" />
							</td>
							<td class="<?php echo $nom_clase; ?>">
								<input type="text" name="txt_destino<?php echo $cont; ?>" id="txt_destino<?php echo $cont; ?>" class="caja_de_texto" readonly="readonly" 
								value="<?php echo $detalleTraspaleo['destino']; ?>" size="25" maxlength="30" onkeypress="return permite(event,'num_car',0);" 
								onchange="obtenerPrecio(txt_distancia<?php echo $cont; ?>.value,'<?php echo $_SESSION['datosTraspaleoMod']['idObra']; ?>','<?php echo $cont; ?>','hdn_incluirPrecio');" />
							</td>
							<td class="<?php echo $nom_clase; ?>">
								<input type="text" name="txt_distancia<?php echo $cont; ?>" id="txt_distancia<?php echo $cont; ?>" class="caja_de_num" 
                                readonly="readonly" 
								value="<?php echo $detalleTraspaleo['distancia']; ?>" size="10" maxlength="15" onkeypress="return permite(event,'num',2);"
								onchange="validarCampoNumerico(this,'La Distancia'); obtenerPrecio(this.value,'<?php echo $_SESSION['datosTraspaleoMod']['idObra']; ?>','<?php echo $cont; ?>','hdn_incluirPrecio');" />
							</td>
							<td class="<?php echo $nom_clase; ?>">
								<input type="text" name="txt_pumn<?php echo $cont; ?>" id="txt_pumn<?php echo $cont; ?>" class="caja_de_num" readonly="readonly" 
								value="<?php echo number_format($detalleTraspaleo['pu_mn'],2,".",","); ?>" size="10" maxlength="15" />
							</td>
							<td class="<?php echo $nom_clase; ?>">
								<input type="text" name="txt_puusd<?php echo $cont; ?>" id="txt_puusd<?php echo $cont; ?>" class="caja_de_num" readonly="readonly" 
								value="<?php echo number_format($detalleTraspaleo['pu_usd'],2,".",","); ?>" size="10" maxlength="15" />
							</td>
							<td class="<?php echo $nom_clase; ?>">
								<input type="text" name="txt_totalMN<?php echo $cont; ?>" id="txt_totalMN<?php echo $cont; ?>" class="caja_de_num" readonly="readonly" 
								value="<?php echo number_format($detalleTraspaleo['total_mn'],2,".",","); ?>" size="10" maxlength="15" />
							</td>
							<td class="<?php echo $nom_clase; ?>">
								<input type="text" name="txt_totalUSD<?php echo $cont; ?>" id="txt_totalUSD<?php echo $cont; ?>" class="caja_de_num" readonly="readonly" 
								value="<?php echo number_format($detalleTraspaleo['total_usd'],2,".",","); ?>" size="10" maxlength="15" />
							</td>
							<td class="<?php echo $nom_clase; ?>">
								<input type="text" name="txt_importeTotal<?php echo $cont; ?>" id="txt_importeTotal<?php echo $cont; ?>" class="caja_de_num" 
                                readonly="readonly" 
								value="<?php echo number_format($detalleTraspaleo['importe_total'],2,".",","); ?>" size="10" maxlength="15" />
							</td>
						</tr><?php
						//Determinar el color del siguiente renglon a dibujar
						$cont++;
						if($cont%2==0)
							$nom_clase = "renglon_blanco";
						else
							$nom_clase = "renglon_gris";
					}while($detalleTraspaleo=mysql_fetch_array($rs));
					
					//Colocar una caja de Texto al Final de la Tabla para Alamcenar el Total de los registros?>
					<tr>
						<td colspan="10" align="right"><strong>TOTAL</strong></td>
						<td><input type="text" name="txt_sumaTotal" id="txt_sumaTotal" class="caja_de_num" readonly="readonly" size="10" maxlength="15" /></td>
					</tr>
					<input type="hidden" name="hdn_cantRegistros" id="hdn_cantRegistros" value="<?php echo $cont; ?>" />
					
					
					<script type="text/javascript" language="javascript"><?php					
						//Si hubo cambios en el Tipo de Cambio o en el Volumne Hacer los calculos otra vez
						if($_SESSION['datosTraspaleoMod']['volumenMod']=="si" || $_SESSION['datosTraspaleoMod']['tasaCambioMod']=="si"){						
							for($i=0;$i<$numRegistros;$i++){?>
								calcularTotales('<?php echo ($i+1); ?>');<?php
							}
						}?>
						obtenerSumaTotal();
					</script><?php
					
				}
				else{?>
					<label class="msje_correcto">No Hay Registros para Mostrar en el Traspaleo de la Obra <u><em><?php echo $_SESSION['datosTraspaleoMod']['nomObra']; ?></em></u></label><?php
				}?>			
			</table>
		</div>	
		
		<div id="botones-modificar" align="center">			
			<input type="submit" name="sbt_guardarDetalle" value="Guardar Registros" class="botones_largos" title="Guardar Registros de Traspaleo Modificados" 
            onmouseover="window.status='';return true" />
			&nbsp;&nbsp;
			<input type="button" name="btn_cancelar" value="Cancelar" class="botones" title="Regresar a la Selecci&oacute;n de Obra" 
            onclick="confirmarSalida('frm_modificarTraspaleo.php')" />
		</div>
		</form><?php
	}
	
	
	if(isset($_POST['sbt_guardarDetalle'])){
		guardarDetalleTraspaleo();
	}?>
		
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>