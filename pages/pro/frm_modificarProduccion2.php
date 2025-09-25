<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Produccion
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){		
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_modificarProduccion.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionProduccion.js" ></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css"/>

    <style type="text/css">
		<!--
			#titulo-modificar {	position:absolute;	left:30px;	top:146px;	width:262px;	height:20px;	z-index:11;}
			#tabla-escogerModificar {position:absolute;left:30px;top:190px;width:450px;height:149px;z-index:12;padding:15px;padding-top:0px;}
			#tabla-modificarProduccion {position:absolute;left:30px;top:100px;width:804px;height:249px;z-index:12;padding:15px;padding-top:0px;}
			#tabla-resultadosBitacora {position:absolute;left:30px;top:190px;width:930px;height:439px;z-index:12;padding:15px;padding-top:0px; overflow:scroll}
			#botones-resultados-bitacora {position:absolute;left:199px;top:670px;width:506px;height:30px;z-index:12;padding:15px;padding-top:0px;}
			#tabla-modificarEquipos {position:absolute;left:30px;top:190px;width:934px;height:371px;z-index:12;padding:15px;padding-top:0px;}
			#tabla-encabezadoEquipos {position:absolute;left:30px;top:30px;width:934px;height:371px;z-index:12;padding:15px;padding-top:0px;}
			#tabla-modificarSeguridad {position:absolute;left:30px;top:190px;width:600px;height:216px;z-index:12;padding:15px;padding-top:0px;}
			#tabla-resultadosSeguridad {position:absolute;left:30px;top:450px;width:600px;height:200px;z-index:12;padding:15px;padding-top:0px; overflow:scroll}
			#botones-equipos {position:absolute;left:200px;top:414px;width:506px;height:30px;z-index:12;padding:15px;padding-top:0px;}
			#titulo-tabla {	position:absolute; left:29px; top:70px;width:919px; height:295px; z-index:8; overflow:scroll}
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-modificar">Modificar Producci&oacute;n </div><?php
	
	//Verificamos si Viene el boron guardar Modificar de la Bitacora
	if(isset($_POST["sbt_guardar"])){
		guardarModificacionBitacora();
	}
	
	
	if(!isset($_POST["cmb_tipoModificar"])){
	//Liberamos sessiones en caso de que existan
		if(isset($_SESSION["produccion"]))
			unset($_SESSION["produccion"]);
		if(isset($_SESSION["seguridad"]))
			unset($_SESSION["seguridad"]);
			
		if(isset($_GET['destino']));
			$destino = "";
		
		if(isset($_GET["fecha"]))
			$fecha=modFecha($_GET["fecha"],1);
		else
			$fecha="";?>
		
        <fieldset class="borde_seccion" id="tabla-escogerModificar" name="tabla-escogerModificar">
        <legend class="titulo_etiqueta">Seleccionar Tipo de Modificaci&oacute;n de Registro</legend>	
        <br>
        <form name="frm_Modificar" id="frm_Modificar" method="post"   >
        <table width="491" height="108" cellpadding="5" cellspacing="5" class="tabla_frm">
        	<tr>
            	<td width="69"><div align="right">Modificar</div></td>
                <td width="156">
                 	<div align="left">
                    <p>
                    	<select name="cmb_tipoModificar" id="cmb_tipoModificar" onchange="javascript:document.frm_Modificar.submit();" >
                        	<option selected="selected" value="">Seleccionar</option>
                        	<option value="produccion">PRODUCCI&Oacute;N</option>
                        	<option value="equipos">EQUIPOS</option>
                        	<option value="seguridad">SEGURIDAD</option>
                      	</select>
                    </p>
               	  	</div>				
			  	</td>
			  	<td width="65"><div align="right">Fecha</div></td>
				<td width="134">
					<input name="txt_fecha" id="txt_fecha" readonly="readonly" type="text" 	value="<?php echo $fecha; ?>" size="10" 
					width="90"/>
			  	</td>
			</tr>
          	<tr>
            	<td colspan="4">
                    <div align="center"> 
						<input type="hidden" name="hdn_destino" value="<?php echo $_GET['destino'];?>"/>
                        <input name="btn_regresarMenu" id="btn_regresarMenu"type="button" class="botones" value="Regresar" 
                        title="Regresar al Men&uacute; Opciones de Registro"
                        onmouseover="window.status='';return true" onclick="location.href='frm_modificarProduccion.php';"/>					
                    </div>				
				</td>
			</tr>
        </table>
        </form>
		</fieldset>
<?php }
	else{
		if(isset($_POST["cmb_tipoModificar"])&& $_POST["cmb_tipoModificar"]=="produccion"){
			if(isset($_POST["sbt_modificarProduccion"])){?>
			<form name="frm_verPro" id="frm_verPro" method="post">
				<div  id="tabla-modificarProduccion" name="tabla-modificarProduccion">
				<?php
					//Verificamos que exista la sesion de ser asi eliminarla para poder realizar con exito el proceso de registrar la produccion	
					if(isset($_SESSION["produccion"]))
						unset($_SESSION["produccion"]);
					//Llamamos la funcion correspondiente para poder mostrar los registros que se pudeden modificar
					modificarProduccion();?>
				</div>
				</form>
				<?php 
			}
			else{?>
			<form onsubmit="return valFormModificarProduccion(this);"  name="frm_consultarProduccion" id="frm_consultarProduccion"
			action="frm_modificarProduccion2.php?fecha=<?php echo $_GET["fecha"];?>&destino=<?php echo $_GET['destino'];?>"  method="post" >
				<div align='center' id='tabla-resultadosBitacora' class='borde_seccion2'>
			 		<?php
					 $comprobarBit=mostrarBitacora();
			 		if(isset($_GET["fecha"])){
			 			$fecha=$_GET["fecha"];
					}
					else
						$fecha="";?> 
			 	</div>     	
				<div id="botones-resultados-bitacora" align="center">
					<input type="hidden" name="cmb_tipoModificar" id="cmb_tipoModificar" value="produccion"/>
					<?php if($comprobarBit!=0){?>
						<input name="sbt_eliminarProduccion" type="submit" class="botones" id="sbt_eliminarProduccion"  value="Eliminar" title="Eliminar Registro" 
						onmouseover="window.status='';return true "/>   
						&nbsp;&nbsp;&nbsp;
						<input name="sbt_modificarProduccion" type="submit" class="botones" id="sbt_modificarProduccion"  value="Modificar" 
						title="Modificar Registro" onmouseover="window.status='';return true" />   
						&nbsp;&nbsp;&nbsp;
					<?php }?>
					<input name="btn_cancelar" type="button" class="botones" value="Cancelar" 
					title="Regresar a P&aacute;gina Principal Modificar de Producci&oacute;n" 
					onMouseOver="window.status='';return true" onclick="location.href='frm_modificarProduccion2.php?fecha=<?php echo $fecha;?>&destino=<?php echo $_GET['destino'];?>'" />							
				</div>			
			</form>
	<?php }
	 	}
		elseif(isset($_POST["cmb_tipoModificar"])&& $_POST["cmb_tipoModificar"]=="equipos"){?>
			<div id="tabla-modificarEquipos" name="tabla-modificarEquipos">
			<br>
			<form onsubmit="return valFormEquipos(this);" name="frm_modificarEquipos" id="frm_modificarEquipos" method="post">
				<table width="812" height="41" cellpadding="5" cellspacing="5" class="tabla_frm">
					<tr>
					  <td width="682" align="right"><div align="right">Fecha</div></td>
					  <td width="93" align="right"> 
						<input name="txt_fecha" id="txt_fecha" readonly="readonly" type="text" value="<?php echo modFecha($_GET["fecha"],1);?>" size="10"  width="90"/>
					</td>
					</tr>
				</table>
				<div align="center" id="tabla-encabezadoEquipos"><?php 
					// Llamada a la funcion donde solo se desplega el titulo de la tabla
					mostrarEncabezadoModificar();?>
				</div>
				<div id="titulo-tabla" align="center" class="borde_seccion2"><?php 
					$comprobar=mostrarEquiposModificar();?>
			  </div>
				 <table width="812" height="41" cellpadding="5" cellspacing="5" class="tabla_frm">
					<tr>
						<td width="50%" align="center">
							<div align="center" id="botones-equipos">
								<?php if($comprobar==1){?>
									<input name="sbt_guardarModEquipo" type="submit" class="botones" id="sbt_guardarModEquipo"  value="Guardar" title="Guardar las Modificaciones" 
									onmouseover="window.status='';return true"/>   
									&nbsp;&nbsp;&nbsp;
									<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" 
									onMouseOver="window.status='';return true"  onclick="desabilitar();"/>    	    	
									&nbsp;&nbsp;&nbsp;
								<?php }?>
								<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar a la P&aacute;gina Principal de Modificar Equipos" 
								onMouseOver="window.status='';return true" onclick="confirmarSalida('frm_modificarProduccion2.php?fecha=<?php echo $_GET["fecha"];?>')" />	
							</div>			
						</td>
					</tr>
				</table>
			</form>
			</div><?php 
		}
		elseif(isset($_POST["cmb_tipoModificar"])&& $_POST["cmb_tipoModificar"]=="seguridad"){
			if(isset($_POST["hdn_ban"])&&$_POST["hdn_ban"]=="si"){
				if(isset($_POST["sbt_agregarSeg"])){
					//Verificar que en el POST esten los datos definidos a fin de agregarlos al arreglo de Sesion
					if (isset($_POST["sbt_agregarSeguridad"])){
						//Si ya esta definido el arreglo $seguridad, entonces agregar el siguiente Modificar a el
						if(isset($_SESSION['seguridad'])){			
							//Guardar los datos en el arreglo
							$seguridad[] = array("partida"=>$_POST["txt_partida"], "tipo"=>$_POST["cmb_tipo"],"observaciones"=>strtoupper($_POST["txa_observaciones"]), 
											"fecha"=>$_POST["txt_fecha"]);
						}
						//Si no esta definido el arreglo $seguridad definirlo y agregar el primer Modificar
						else{			
							//Guardar los datos en el arreglo
							$seguridad = array(array("partida"=>$_POST["txt_partida"], "tipo"=>$_POST["cmb_tipo"],"observaciones"=>strtoupper($_POST["txa_observaciones"]),
													 "fecha"=>$_POST["txt_fecha"]));
							$_SESSION['seguridad'] = $seguridad;	
						}	
					}
				
					//Verificar que este definido el Arreglo de seguridad, si es asi, lo mostramos en el formulario
					if(isset($_SESSION["seguridad"])){
						echo "<div id='tabla-resultadosSeguridad' class='borde_seccion2'>";
							mostrarResultados($seguridad);
						echo "</div>";
					}
					//Verificamos que el arreglo de sesion no venga definido si es asi el contador tomara el valor de 1
					if(!isset($_SESSION["seguridad"])){
						if(!isset($_POST["txt_fecha"]))			
							$cont=generarIdSeguridad($_GET["fecha"]);	
					}
					else if(isset($_SESSION["seguridad"])&&!isset($_POST["sbt_guardarSeguridad"])){
						//De lo contrario si se vuelve a entrar a agregar otro registro, para conservar la partida se cuenta el arreglo y se le agrega uno
						$cont=$_SESSION["seguridad"][0]["partida"]+1;
					}
					//Si el boton viene definido se incrementa la partida
					if(isset($_POST["txt_partida"])){
						$cont=$_POST["txt_partida"]+1;
					}
					if(isset($_SESSION["seguridad"]))
						$fecha=$_SESSION["seguridad"][0]["fecha"];
					if(isset($_POST["rdb_seguridad"])){
						$datosSeg=split("/",$_POST["rdb_seguridad"]);
						$fecha=modFecha($datosSeg[0],1);
					}
					if(!isset($_SESSION["seguridad"])&&!isset($_POST["rdb_seguridad"])){
						$fecha=modFecha($_GET["fecha"],1);
					}?>
					<fieldset class="borde_seccion" id="tabla-modificarSeguridad" name="tabla-modificarSeguridad">
					<legend class="titulo_etiqueta">Ingresar los Datos Referentes a la Seguridad</legend>	
					<br>
					
					<form onsubmit="return valFormSeguridadMod(this);" name="frm_modificarSeguridad" id="frm_modificarSeguridad"  method="post"
					action="frm_modificarProduccion2.php?fecha=<?php echo $_GET["fecha"];?>" >
					<table width="100%" height="201" cellpadding="5" cellspacing="5" class="tabla_frm">
						<tr>
							<td width="130"><div align="right">N&uacute;mero</div></td>
							<td width="217">
								<input type="text" name="txt_partida"  readonly="readonly" id="txt_partida" maxlength="3" size="3" class="caja_de_texto" 
								value="<?php echo $cont;?>"/>
							</td>
							<td width="156"><div align="right">Fecha </div></td>
							<td width="242">
								<input name="txt_fecha" id="txt_fecha" readonly="readonly" type="text" value="<?php echo $fecha;?>" size="10"  width="90"/>
							</td>
						</tr>
						<tr>
							<td width="130"><div align="right">*Tipo</div></td>
							<td width="217">
								<select name="cmb_tipo" id="cmb_tipo" size="1" class="combo_box">
									<option value="">Tipo</option>
									<option value="INCIDENTE">INCIDENTE</option>
									<option value="ACCIDENTE">ACCIDENTE</option>
								</select>
							</td>
							<td ro><div align="right">*Observaciones</div></td>
							<td>
								<textarea name="txa_observaciones" id="txa_observaciones" class="caja_de_texto" cols="40" rows="3" maxlength="120"
								onkeyup="return ismaxlength(this)"></textarea>
							</td>
						<tr>
							<td colspan="5"><strong>* Datos marcados con asterisco son <u>obligatorios</u></strong></td>
						</tr>
						<tr>
							<td colspan="5">
								<div align="center">
									<input type="hidden" name="hdn_botonSeleccionado" id="hdn_botonSeleccionado" value="" />
									<input type="hidden" name="hdn_ban" id="hdn_ban" value="si" />
									<input type="hidden" name="cmb_tipoModificar" id="cmb_tipoModificar" value="seguridad"/>
									<?php if(isset($_SESSION['seguridad'])){?>
										<input name="sbt_finalizarSeguridad" type="submit" class="botones" id="sbt_finalizarSeguridad"  value="Finalizar" 
										title="Guardar Modificars" onmouseover="window.status='';return true" 
										onclick="hdn_botonSeleccionado.value='sbt_finalizarSeguridad'"/>									   
										&nbsp;&nbsp;&nbsp;
									<?php }?>
									<input type="hidden" name="sbt_agregarSeg" id="sbt_agregarSeg"/>
									<input name="sbt_agregarSeguridad" type="submit" class="botones" id="sbt_agregarSeguridad"  value="Agregar" 
									title="Agregar Modificaciones" onmouseover="window.status='';return true" onclick="hdn_botonSeleccionado.value='sbt_agregarSeg'" />   
									&nbsp;&nbsp;&nbsp;
									<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" 
									onMouseOver="window.status='';return true"/>    	    	
									&nbsp;&nbsp;&nbsp;
									<input name="sbt_cancelarSeg" type="submit" class="botones" value="Cancelar" title="Regresar al Men&uacute;" 
									onMouseOver="window.status='';return true" 
									onclick="hdn_botonSeleccionado.value='sbt_cancelarSeg';hdn_ban.value='no';location.href='frm_modificarProduccion2.php?fecha=<?php echo $_GET["fecha"];?>'" />																	
								</div>			
							</td>
						</tr>
					</table>
					</form>
					</fieldset>			
	<?php }//Fin if(isset($_POST["sbt_agregarSeg"])){
	}//Finif(isset($_POST["hdn_ban"])&&$_POST["hdn_ban"]=="si")
	else{?>
		<form onsubmit="return valFormModificarSeguridad(this);"  name="frm_modificarSeguridad2" id="frm_modificarSeguridad2" method="post" 
		action="frm_modificarProduccion2.php?fecha=<?php echo $_GET["fecha"];?>">
			<div align='center' id='tabla-resultadosBitacora' class='borde_seccion2'>
				<?php 
				if(isset($_SESSION['seguridad']))
					unset($_SESSION['seguridad']);
				$comprobarSeguridad=mostrarSeguridad();?> 
		  </div>      	
			<div id="botones-resultados-bitacora" align="center">
				<input type="hidden" name="hdn_botonSeleccionado" id="hdn_botonSeleccionado" value="" />
				<input type="hidden" name="cmb_tipoModificar" id="cmb_tipoModificar" value="seguridad" />
				<input type="hidden" name="hdn_ban" id="hdn_ban" value="si" />
					<input type="hidden" name="hdn_fecha" id="hdn_fecha" value="<?php echo $_GET["fecha"];?>" />
					<?php if($comprobarSeguridad==1){?>
						<input name="sbt_eliminarSeguridad" type="submit" class="botones" id="sbt_eliminarSeguridad"  value="Eliminar" title="Eliminar Registro" 
						onmouseover="window.status='';return true" onclick="hdn_botonSeleccionado.value='sbt_eliminarSeguridad'"/>   
						&nbsp;&nbsp;&nbsp;
					<?php }?>
				<input name="sbt_agregarSeg" type="submit" class="botones" id="sbt_agregarSeg"  value="Agregar" 
				title="Agregar Registro" onmouseover="window.status='';return true" onclick="hdn_botonSeleccionado.value='sbt_agregarSeg'"/>   
						&nbsp;&nbsp;&nbsp;
					
					<input name="btn_cancelar" type="button" class="botones" value="Cancelar" 
					title="Regresar a P&aacute;gina Principal Modificar de Seguridad" 
					onMouseOver="window.status='';return true"  onclick="location.href='frm_modificarProduccion2.php?fecha=<?php echo $_GET["fecha"];?>'" />							
		  </div>									
		</form><?php 
	}			
		
}
}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>