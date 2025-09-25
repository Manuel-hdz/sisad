<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Compras
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Este archivo contiene las funciones para manejar los Movimientos en la Caja Chica
		include ("op_cajaChica.php");
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />	
    <style type="text/css">
		<!--				
		#barra-titulo { position:absolute; left:30px; top:145px; width:93px; height:20px; z-index:11; }
		#ingresar-presupuesto { position:absolute; left:30px; top:190px; width:460px; height:140px; z-index:12; }
		#pregunatr-presupuesto { position:absolute; left:287px; top:190px; width:460px; height:140px; z-index:13; }
		#presupuesto-mensual { position:absolute; left:30px; top:190px; width:423px; height:210px; z-index:14; }
		-->
	</style>
</head>
<body>	
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="barra-titulo">Caja Chica</div>
	
<?php 
	if( !isset($_POST['txt_iniPresupuesto']) && !isset($_POST['txt_Presupuesto']) ){
	
		if(isset($_GET['origen']) && $_GET['origen']=="preInicial"){
			//Obtener el mes actual
			$mesActual = obtenerMesActual();?>
			<fieldset class="borde_seccion" id="ingresar-presupuesto" name="ingresar-presupuesto">
			<legend class="titulo_etiqueta">Ingresar Presupuesto Inicial para la Caja Chica del Mes de <u><em><?php echo $mesActual; ?></em></u></legend>
			<br>
			<form onsubmit="return valFormPreInicial(this);" name="frm_preInicial" method="post" action="frm_preCajaChica.php">
				<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" align="center">
					<tr>
  	  			  		<td width="180"><div align="right">Presupuesto Inicial</div></td>
          				<td width="180">
							$
						 	<input name="txt_iniPresupuesto" id="txt_iniPresupuesto" type="text" class="caja_de_num" onkeypress="return permite(event,'num', 2);" 
							onchange="formatCurrency(value,'txt_iniPresupuesto');" size="15" maxlength="20"/>
					  		<input name="hdn_fechaPre" type="hidden" value=<?php echo verFecha(3); ?> />		  		  
                        </td>
        			</tr>
	        		<tr>
        	  			<td colspan="2" align="center"><input type="submit" name="sbt_guardar" class="botones" value="Guardar" 
                        	title="Guardar el Presupuesto" onmouseover="window.status='';return true" />
                        </td>
    	    		</tr>
    			</table>
			</form>
		</fieldset>	 
		<?php
		}//Cierre if(isset($_GET['origen']) && $_GET['origen']=="preInicial")
		
	
		if(isset($_GET['origen']) && $_GET['origen']=="preMensual"){
			//Obtener el Mes Actual
			$mesActual = obtenerMesActual();
			//Obtener el Remanente del mes anterior
			$remanente = obtenerDato("bd_compras","caja_chica","presupuesto","id_caja_chica","$claveAnterior");						
			?>   	
			<fieldset class="borde_seccion" id="pregunatr-presupuesto" name="pregunatr-presupuesto">
			<legend class="titulo_etiqueta">Ingresar Presupuesto para la Caja Chica del Mes de <u><em><?php echo $mesActual; ?></em></u></legend>
			<br>
			<form name="frm_preguntar" method="post" action="frm_preCajaChica.php">
			<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" align="center">
				<tr>
  	  		  		<td colspan="2" align="center">
						Existe un remanente del presupuesto del mes anterior de $<?php echo number_format($remanente,2,".",","); ?>, 
						desea incorporar esta cantidad al presupuesto del mes actual <?php echo $mesActual;?>?
						<input type="hidden" name="hdn_remanente" value="<?php echo $remanente; ?>"  />
					</td>
    	    	</tr>
        		<tr>
          			<td align="right">
          				<input type="submit" name="sbt_aceptar" class="botones" value="Aceptar" title="Agregar Remanente del Presupuesto Anterior al Actual"
                        onmouseover="window.status='';return true" />
        			</td>
					<td align="left">
						<input type="submit" name="sbt_rechazar" class="botones" value="Rechazar" title="No Agregar Remanente del Presupuesto Anterior al Actual"
                        onmouseover="window.status='';return true" />
					</td>
        		</tr>
    		</table>
			</form>													
		</fieldset>		
		<?php
		}//Cierre if(isset($_GET['origen']) && $_GET['origen']=="preMensual")
		
		if( isset($_POST['sbt_aceptar']) || isset($_POST['sbt_rechazar']) ){
			//Obtener el Mes Actual
			$mesActual = obtenerMesActual();
			?>
			<fieldset class="borde_seccion" id="presupuesto-mensual" align="center" name="presupuesto-mensual">
			<legend class="titulo_etiqueta">Ingresar Presupuesto para la Caja Chica del Mes de <u><em><?php echo $mesActual; ?></em></u></legend>				
			<br><br>
			<form onsubmit="return valFormPreMensual(this);" name="frm_preMensual" method="post" action="frm_preCajaChica.php">
			<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" align="center">
				<?php
				if(isset($_POST['sbt_aceptar'])){?>
					<tr>
						<td><div align="right">Remanente del Mes Anterior</div></td>
						<td>
                        	$<input name="txt_remanente" type="text" class="caja_de_num" id="txt_remanente" value="<?php 
							echo number_format($hdn_remanente,2,".",","); ?>" size="15" maxlength="20" readonly="true"  />
                        </td>
					</tr>				
					<tr>
						<td><div align="right">Presupuesto del Mes Actual</div></td>
						<td>
							$
							<input name="txt_totalPresupuesto" id="txt_totalPresupuesto" type="text" class="caja_de_num" 
                            onchange="formatCurrency(value,'txt_totalPresupuesto');" 
							onkeypress="return permite(event,'num', 2 );" size="15" maxlength="20" onblur="sumar(this);" />
						</td>
					</tr>
				<?php
				}?>
				<tr>
  	  		  		<td width="180"><div align="right">Presupuesto Total</div></td>
	          		<td width="180">
						$
						<input name="txt_Presupuesto" id="txt_Presupuesto" type="text" class="caja_de_num" onkeypress="return permite(event,'num',2);" 
						size="15" maxlength="20" <?php if(isset($_POST['sbt_aceptar'])){?> readonly="true" <?php } else { ?> 
                        onchange="formatCurrency(value,'txt_Presupuesto');" <?php } ?> />
						<input name="hdn_fechaPre" type="hidden" value=<?php echo verFecha(3); ?> />				  	
					</td>
    	    	</tr>
        		<tr>
          			<td colspan="2" align="center"><input type="submit" name="sbt_guardar" class="botones" value="Guardar" title="Guardar el Presupuesto" 
                    	onmouseover="window.status='';return true" />
                    </td>
        		</tr>
    		</table>
			</form>	
</fieldset>
		<?php 	
		}//Cierre if( isset($_POST['sbt_aceptar']) || isset($_POST['sbt_rechazar']) )
	}//Cierre if( !isset($_POST['txt_iniPresupuesto']) && !isset($_POST['txt_totalPresupuesto']) )
	
	//Guardar el presupuesto de la Caja Chica en la BD 
	else{		
		//Guardar el Presupuesto Inicial, cuando ningun registro de Caja Chica Existe
		if(isset($_POST['txt_iniPresupuesto'])){
			//Conectarse a la BD de Compras
			$conn = conecta("bd_compras");
			
			//Quitar la coma al presupuesto, para poder realziar la operaciones requeridas.
			$txt_iniPresupuesto=str_replace(",","",$txt_iniPresupuesto);
			
			//Obtener la clave de la Caja Chica del mes actual
			$clave_cajaChica = obtenerIdCCH();
			$rs = mysql_query("INSERT INTO caja_chica (id_caja_chica,presupuesto) VALUES('$clave_cajaChica',$txt_iniPresupuesto)");
			if($rs)
				echo "<meta http-equiv='refresh' content='0;url=frm_cajaChica.php'>";
			else{
				//Redireccionar a la página de error
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}				
			
			//Cerrar conexion con la BD
			mysql_close($conn);
		}
		
		//Guardar el Presupuesto del Mes Actual y el Incremento del Remanante del Mes Anterior
		if(isset($_POST['txt_Presupuesto'])){
			//Conectarse a la BD de Compras
			$conn = conecta("bd_compras");
			
			//Quitar la coma al presupuesto, para poder realziar la operaciones requeridas.
			$txt_Presupuesto=str_replace(",","",$txt_Presupuesto);
			
			//Obtener la clave de la Caja Chica del mes actual
			$clave_cajaChica = obtenerIdCCH();
			$rs = mysql_query("INSERT INTO caja_chica (id_caja_chica,presupuesto) VALUES('$clave_cajaChica',$txt_Presupuesto)");
			if($rs)
				echo "<meta http-equiv='refresh' content='0;url=frm_cajaChica.php'>";
			else{
				//Redireccionar a la página de error
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}
			
			//Cerrar conexion con la BD
			mysql_close($conn);		
		}			
	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>