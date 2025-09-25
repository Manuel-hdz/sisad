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
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y
		//da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Este archivo muestra los pedidos registrados en la BD
		//include ("op_registrarVenta.php");
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/actualizarIVA.js"></script>
    <style type="text/css">
		<!--
		#titulo-registrarVenta {position:absolute; left:30px; top:146px; width:154px; height:22px; z-index:11; }
		#tabla-registrarVenta { position:absolute; left:30px; top:190px; width:690px; height:396px;	z-index:12;	}
		<!---->
		#calendario_venta {	position:absolute;left:230px;top:254px; width:30px;	height:26px;z-index:15;}
		#editar-iva { position:absolute; left:345px; top:288px; width:35px;	height:30px;z-index:16;}
		-->
    </style>
</head>
<body>	

	<?php
	if (isset($_SESSION['detalleVenta'])){
		$id_venta = $_SESSION['detalleVenta'][0]['clave_venta'];
		$subtotal = $_SESSION['totalVenta'];
		$iva=($subtotal*  $_SESSION['porcentajeIVA'])/100;
		$total=$subtotal+$iva;;		
	}
	?>
	
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-registrarVenta">Registrar Venta </div>


	<fieldset class="borde_seccion" id="tabla-registrarVenta" name="tabla-registrarVenta">
		<legend class="titulo_etiqueta"> Registar  Ventas</legend>
		<form onsubmit="return valFormRegistrarVenta(this);" name="frm_registrarVenta" method="post" action="op_registrarVenta.php">
			<?php
			if (isset($_POST["sbt_continuar"]))
			obtenerPrecioUnitReq();
			?>
	
			<table width="690" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
                  <td width="90"><div align="right">Clave </div></td>
                    <td width="188">
                  <input name="txt_noVenta" type="text" class="caja_de_texto" readonly="readonly" id="txt_noVenta" 
                        onkeypress="return permite(event,'num_car',1);" value="<?php echo $id_venta; ?>" size="10" maxlength="10" />                 	</td>			
               	  <td width="92"><div align="right">*Cliente</div></td>
               	    <td width="215">
						<?php  
                           $cmb_cliente="";
                           $conn = conecta("bd_compras");
                           $result=mysql_query("SELECT DISTINCT rfc,razon_social FROM clientes ORDER BY razon_social");?>
                           <select name="cmb_cliente" size="1" onChange="document.getElementById('txt_rfc').value = this.value; habilitarCampos(this);" class="combo_box">
                               <option value="">Cliente</option>
							   <option value="PUBLICOGRAL">PUBLICO GENERAL</option>
                               <?php while ($row=mysql_fetch_array($result)){
                                   if ($row['rfc'] == $cmb_cliente){
                                       echo "<option value='$row[rfc]' selected='selected'>$row[razon_social]</option>";
                                   }
                                   else{
                                       echo "<option value='$row[rfc]'>$row[razon_social]</option>";
                                   }
                               } 
                          		 //Cerrar la conexion con la BD		
                           		mysql_close($conn);
                           	?>
       		      </select>                   	</td>
			  </tr>
    			<tr>
					<td><div align="right">Fecha</div></td>
					<td>
            			<input name="txt_fechaVenta" type="text" id="txt_fechaVenta" value=<?php echo date("d/m/Y"); ?> size="10" maxlength="15" readonly=true  width=
                        "90" />           			</td>		
					<td><div align="right">RFC</div></td>
        			<td>
            			<input name="txt_rfc" id="txt_rfc" type="text" class="caja_de_texto" size="15" maxlength="20" value="" 
               			readonly="true" title="Seleccionar un Cliente para mostrar su RFC"/>           			 </td>
    			</tr>
				<tr>
					<td><div align="right">Subtotal</div></td>
        			<td>
                        $<input name="txt_subtotal" type="text" class="caja_de_texto" id="txt_subtotal" onkeypress="return permite(event,'num',2);"
                        value=" <?php echo number_format($subtotal,2,".",",");?>" size="15" maxlength="20" readonly="true"/>
                        <input type="text" name="txt_lblIVA" id="txt_lblIVA" class="caja_de_num" onclick="alert ('IVA calculado en base al '+this.value);"
                         value="<?php echo 	$_SESSION['porcentajeIVA'];?>%" size="4" maxlength="10" 
                        readonly="true" />                    </td>
                    <td><div align="right">*Vendi&oacute;</div></td>
                    <td>
						<?php
						//Conectarse con la BD indicada
						$conn = conecta("bd_recursos");
						$stm_sql = "SELECT nombre,ape_pat,ape_mat,area FROM empleados ORDER BY area,nombre";
						$rs = mysql_query($stm_sql);
						//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
						if($datos = mysql_fetch_array($rs)){			
							//Declarar el ComboBox con el nombre especificado en el parametro $nom_combo
							echo "<select name='txt_vendio' id='txt_vendio' class='combo_box'>";
							//Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
							echo "<option value=''>Vendi&oacute;</option>";
							//Obtener el area inicial y desplegarla dentro del combo
							$area = $datos['area'];
							echo "
								<option value=''></option>
								<option value=''>----- $area -----</option>
								<option value=''></option>
							";
							do{
								//Verificar cuando se cambia de area y desplar el nombre dento del ComboBox
								if($area!=$datos['area']){
									$area = $datos['area'];
									echo "
										<option value=''></option>
										<option value=''>----- $area -----</option>
										<option value=''></option>
									";
								}
								echo "<option value='$datos[nombre] $datos[ape_pat] $datos[ape_mat]'>$datos[nombre] $datos[ape_pat] $datos[ape_mat]</option>";
							}while($datos = mysql_fetch_array($rs));
							echo "</select>";
						}
						else{
							echo "<label class='msje_correcto'>No Hay Personal, consulte a Recursos Humanos</label>";
						}
						//Cerrar la conexion con la BD		
						mysql_close($conn);	
						?>
						<!--
                        <input name="txt_vendio" type="text" class="caja_de_texto" id="txt_vendio" 
                        onkeypress="return permite(event,'num_car', 2);" size="40" maxlength="60" />
						-->
					</td>
                </tr>
                <tr>
                    <td><div align="right">IVA</div></td>
                    <td>
                        $<input name="txt_iva" type="text" class="caja_de_texto" id="txt_iva" onkeypress="return permite(event,'num', 2);"
                        value="<?php echo number_format($iva,2,".",",");?>" size="15" maxlength="20" readonly="readonly"  />                    </td>
                    <td><div align="right">*Medio de Venta </div></td>
        		 <td><select name="cmb_medioVenta" class="combo_box">
                   <option value="">Medio de Venta</option>
                   <option value="ELECTRONICA">ELECTR&Oacute;NICA</option>
                   <option value="TELEFONICA">TELEFONICA</option>
                 </select></td>
                </tr>
           		<tr>
                    <td><div align="right">Total</div></td>
                    <td>
                        $<input name="txt_total" type="text"  class="caja_de_texto" id="txt_total" onkeypress="return permite(event,'num', 2);" 
                        value="<?php echo number_format($total,2,".",",");?>" size="15" maxlength="20" readonly="readonly"/>                    </td>
                    <td><div align="right">*Autoriz&oacute;</div></td>
                    <td>
						<?php
						//Conectarse con la BD indicada
						$conn = conecta("bd_recursos");
						$stm_sql = "SELECT nombre,ape_pat,ape_mat,area FROM empleados ORDER BY area,nombre";
						$rs = mysql_query($stm_sql);
						//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
						if($datos = mysql_fetch_array($rs)){			
							//Declarar el ComboBox con el nombre especificado en el parametro $nom_combo
							echo "<select name='txt_autorizo' id='txt_autorizo' class='combo_box'>";
							//Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
							echo "<option value=''>Autoriz&oacute;</option>";
							//Obtener el area inicial y desplegarla dentro del combo
							$area = $datos['area'];
							echo "
								<option value=''></option>
								<option value=''>----- $area -----</option>
								<option value=''></option>
							";
							do{
								//Verificar cuando se cambia de area y desplar el nombre dento del ComboBox
								if($area!=$datos['area']){
									$area = $datos['area'];
									echo "
										<option value=''></option>
										<option value=''>----- $area -----</option>
										<option value=''></option>
									";
								}
								echo "<option value='$datos[nombre] $datos[ape_pat] $datos[ape_mat]'>$datos[nombre] $datos[ape_pat] $datos[ape_mat]</option>";
							}while($datos = mysql_fetch_array($rs));
							echo "</select>";
						}
						else{
							echo "<label class='msje_correcto'>No Hay Personal, consulte a Recursos Humanos</label>";
						}
						//Cerrar la conexion con la BD		
						mysql_close($conn);	
						?>
						<!--
						<input name="txt_autorizo" type="text" class="caja_de_texto" size="30" maxlength="60" 
                        onkeypress="return permite(event,'num_car',2);"  />
						-->
					</td>
                </tr>
                <tr>
                    <td><div align="right">*Factura</div></td>
                    <td>
						<select name="cmb_factura" class="combo_box" id="cmb_factura">
                      		<option value="">Factura de Venta</option>
                      		<option value="SI">SI</option>
                      		<option value="NO">NO</option>
                    	</select>					</td>
                    <td><div align="right">Comentarios</div></td>
                    <td><textarea name="txa_comentarios" cols="30" rows="2" class="caja_de_texto" maxlength="120" onkeypress="return permite(event,'num_car',0);"
                        onkeyup="return ismaxlength(this);"></textarea></td>
                </tr>
				<tr> 
					<td><div align="right" style="visibility:hidden;" id="lbl_nomCliente">Nombre Cliente</div></td>
					<td><input name="txt_nomCliente" type="text" class="caja_de_texto" id="txt_nomCliente" 
						size="30" maxlength="40" disabled="disabled" style="visibility:hidden;" onkeypress="return permite (event,'car',0);"></td>

					<td><div align="right" style="visibility:hidden;" id="lbl_dir">Direcci&oacute;n</div></td>
					<td><input name="txt_direccion" type="text" class="caja_de_texto" id="txt_direccion" 
                        size="30" maxlength="120" disabled="disabled" style="visibility:hidden;" onkeypress="return permite(event,'num_car',0);"></td>
				</tr>
				<tr>
				  <td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
		      </tr>
			  <tr>

                    <td height="53" colspan="4">
                        <div align="center">
							<input type="hidden" name="hdn_ivaIncluido" id="hdn_ivaIncluido" value="NO"/>
                            <input name="sbt_registrarDet" type="submit" class="botones" title="Registrar Venta" id="sbt_registrar" value="Registrar" 
                            onmouseover="window.status='';return true;"/>
                            &nbsp;&nbsp;
                            <input name="rst_limpiar" type="reset" class="botones" value="Limpiar" 
                            title="Limpia el formulario de Registrar Ventas"/>&nbsp;&nbsp;&nbsp;
                            <?php if (!isset($_POST["sbt_registrar"])){?>
                            <input name="btn_cancelar" type="button" class="botones" id="btn_cancelar" title="Regresar a Detalles de Venta"
                            value="Cancelar" onclick="location.href='frm_detallesVenta.php'"/>
                            <?php }?>
                        </div>                    </td>
    			</tr>
		  </table>
		</form>
</fieldset>
    
    <div id="calendario_venta">
	  <input name="calendario_ven" type="image" id="calendario_ven" onclick="displayCalendar
      (document.frm_registrarVenta.txt_fechaVenta,'dd/mm/yyyy',this)"onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"
       width="25" height="25" border="0" />
</div>
    
    <div id="editar-iva">
      <input type="image" src="../../images/editar.png" width="30" height="25" border="0" onclick="actualizarIVA('txt_subtotal','txt_iva','txt_total');" 
      title="Modificar la Tasa de IVA" />
</div>
    
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>