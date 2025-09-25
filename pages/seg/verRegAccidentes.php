<?php

	/**
	  * Nombre del Módulo: Seguridad Industrial                                                
	  * Nombre Programador: Daisy Ariana Martínez Fernández
	  * Fecha: 07/Febrero/2012
	  * Descripción: Archivo que permite cargar el registro de las areas visitadas
	  **/  
		//Titulo de la ventana emergente
		echo "<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>"; 
		//Inlcuimos archivo que contiene las operaciones necesarias para el registro
		include ("op_registrarActaSeguridadHigiene.php");
		//Archivo de validacion
		echo "<script type='text/javascript' src='../../includes/validacionSeguridad.js'></script>";
		//Archivo de Estilo
		echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";
		//Archivo que permite la correcta ejecución de los objetos calendario contenidos en esta pantalla
		echo "<script type='text/javascript' src='../../includes/calendario.js?random=20060118'></script>";
		//Archivo que permite controlar el numero de caracteres ingresados en las cajas de texto (textarea)
		echo "<script type='text/javascript' src='../../includes/maxLength.js' ></script>";
		//Archivo para desabilitar boton regresar del teclado?>
		<script language="javascript" type="text/javascript" src="../../includes/disableKeys.js"></script><?php 
		//Archivo que permite la correcta ejecución del objeto calendario
		echo "<link type='text/css' rel='stylesheet' href='../../includes/estiloCalendario.css?random=20051112' media='screen'>	";
		//Archivo que contiene la funcion de validacion de la sesion para activar o no el boton de guardar
		echo "<script type='text/javascript' src='includes/ajax/verificarSesiones.js'></script>";
		//Iniciamos la sesión para las operaciones necesarias en la pagina
		session_start();
		//Incluimos archivo para modificar fechas segun sea requerido	
		include_once("../../includes/func_fechas.php");?>
		<script language="javascript" type="text/javascript">
			<!--
			function click() {
				if (event.button==2) {
					alert ('Contenido Protegido, ©Concreto Lanzado de Fresnillo S.A. de C.V.');
				}
			}
			document.onmousedown=click;
			//-->
		</script>
		<style type="text/css">
			<!--
			#titulo-agregar-registros { position:absolute; left:30px; top:22px; width:200px; height:20px; z-index:11;}
			#tabla-agregarRegistro {position:absolute;left:30px;top:10px;width:707px;height:84px;z-index:12;}
			#tabla-mostrarRegistros {position:absolute;left:30px;top:270;width:720px;height:170px;z-index:16;padding:15px;padding-top:0px; overflow:scroll}
			#calendario{position:absolute;left:273px;top:104px;width:30px;height:26px;z-index:14;}
			.Estilo1 {
				font-family: Arial, Helvetica, sans-serif;
				font-size: 12px;
			}
			-->
	    </style>
	<?php
	
		//Comprobamos si existe en el GET la variable noRegistro si es asi quiere decir que se presiono la imagen-boton borrar
		if(isset($_GET["noRegistro"])){
			//Si es asi liberar la sesion
			unset($_SESSION["accidentes"][$_GET["noRegistro"]]);
			//Verificamos que exista la session y el parametro que contiene la ubicacion del registro dentro de la sesion
			if(isset($_SESSION["accidentes"]) && isset($_GET["noRegistro"]))
				//Reacomodamos el Arreglo
				$_SESSION['accidentes'] = array_values($_SESSION['accidentes']);
			
			//Verificamos si exista la sesion
			if(isset($_SESSION["accidentes"])){
				//Si el arreglo de lista_maestra esta vacio, retirarlo de la SESSION
				if(count($_SESSION["accidentes"])==0){
					//Liberamos la sesion
					unset($_SESSION["accidentes"]);
				}
			}
			
		}//Cierre if(isset($_GET["noRegistro"]))
		//Verificamos que exista el boton agregar para poder agregar los datos en la session
		if(isset($_POST["sbt_agregar"])){
			//Esta variable indica si el registro esta repetido o no
			$repetido = 0;
			if(isset($_SESSION['accidentes'])){
				//Verificar que el registro no este repetido en el Arreglo de SESSION
				foreach($_SESSION["accidentes"] as $ind => $registro){
					if($_POST["txt_causasAcc"]==$registro["cauAcc"]){
						$repetido = 1;
						break;
					}
				}	
			}
			if($repetido!=1){		
				//Verificar que en el POST esten los datos definidos a fin de agregarlos al arreglo de Sesion
				//Si ya esta definido el arreglo $lista_maestra, entonces agregar el siguiente registro a el
				if(isset($_SESSION['accidentes'])){
					//Guardar los datos en el arreglo
					$accidentes[] = array("noAcc"=>$_POST['txt_noAcc'],"cauAcc"=>strtoupper($_POST['txt_causasAcc']),"fechAcc"=>$_POST['txt_fechaAcc'],"accPrev"=>strtoupper($_POST['txa_accPrev']),"nomAcc"=>strtoupper($_POST['txt_nomAcc']));
				}
				//Si no esta definido el arreglo $lista_maestra definirlo y agregar el primer registro
				else{
					$cont=0;
					//Guardar los datos en el arreglo
					$accidentes = array( array("noAcc"=>$_POST['txt_noAcc'],"cauAcc"=>strtoupper($_POST['txt_causasAcc']),"fechAcc"=>$_POST['txt_fechaAcc'],"accPrev"=>strtoupper($_POST['txa_accPrev']),"nomAcc"=>strtoupper($_POST['txt_nomAcc'])));
					$_SESSION['accidentes'] = $accidentes;
				}
			}
			else{?>
				<script type="text/javascript" language="javascript">
					setTimeout("alert('La Acción Ingresada <?php echo $_POST["txa_accPrev"];?> ya se encuentra Registrada')", 500);
				</script><?php
			}	
		}
		
		//Verificar que este definido el Arreglo de produccion, si es asi, lo mostramos en el formulario
		if (isset($_SESSION["accidentes"])){
			echo "<div id='tabla-mostrarRegistros' class='borde_seccion2'>";
				mostrarRegistrosAccidentes($accidentes);
			echo "</div>";
		}
		if(!isset($_SESSION['accidentes'])){
			$numero =1;
		}
		else{
			$numero = count($_SESSION['accidentes'])+1;
		}
	
	?>
	<body onUnload="verificarParametros();">
	<p>&nbsp;</p>
	<form  onSubmit="return valFormRegAccidentes(this);"method="post"name="frm_agregarRegistro" id="frm_agregarRegistro" action="verRegAccidentes.php">
	<fieldset class="borde_seccion" id="tabla-agregarRegistro" name="tabla-agregarRegistro">
    <legend class="titulo_etiqueta">Ingresar Informaci&oacute;n de Accidentes Investigados </legend>
    <br />
    	<table width="689" height="43"  cellpadding="5" cellspacing="5" class="tabla_frm">
        	<tr>
          		<td width="121" height="-7"><label></label>          		  <div align="right">*No. Accidente </div></td>
          		<td width="61">
					<input type="text" name="txt_noAcc" id="txt_noAcc" value="<?php echo $numero;?>" readonly="readonly" size="5" maxlength="10"
				 	style="background-color:#999999; color:#FFFFFF"/></td>
          		<td width="167"><div align="right">*Causas del Accidente </div></td>
       	      	<td width="273">
					<input type="text" name="txt_causasAcc" id="txt_causasAcc" value=""  size="45" maxlength="80" class="caja_de_texto"  
					onKeyPress="return permite(event,'num_car', 2);"/>
				</td>
        	</tr>
        	<tr>
        		<td height="-2"><div align="right">*Fecha Accidente </div></td>
        	  	<td height="-2">
					<input name="txt_fechaAcc" type="text" id="txt_fechaAcc" size="10" maxlength="15" value="<?php echo date("d/m/Y");?>" 
			  		readonly="readonly" style="background-color:#999999; color:#FFFFFF"/>
				</td>
        	  	<td><div align="right">*Acciones Preventivas </div></td>
      	      	<td>
					<textarea name="txa_accPrev" id="txa_accPrev" maxlength="120" onKeyUp="return ismaxlength(this);" class="caja_de_texto" rows="3"
				 	cols="40" onKeyPress="return permite(event,'num_car', 0);"></textarea>
				</td>
       	  	</tr>
        	<tr>
        		<td height="0"><div align="right">*Nombre Accidente </div></td>
        	  	<td height="0" colspan="3">
					<input type="text" name="txt_nomAcc" id="txt_nomAcc" value=""  size="45" maxlength="60" class="caja_de_texto" 
					onKeyPress="return permite(event,'num_car', 2);"/>
				</td>
       	  	</tr>
			<tr>
          		<td colspan="4">
					<div align="center">
				  		<input type="hidden" name="hdn_nomCheckBox" id="hdn_nomCheckBox" value="ckb_accidentes"/>
				  		<input type="hidden" name="hdn_botonSel" id="hdn_botonSel" value="x"/>
						<input type="hidden" name="hdn_datosSesion" id="hdn_datosSesion" 
						<?php if(isset($_SESSION['accidentes'])){ ?> value="si" <?php } else {?> value="no" <?php } ?>/><?php
				  		if(isset($_SESSION['accidentes'])){?>
							<input name="btn_finalizar" type="button" class="botones" value="Finalizar" title="Finalizar el Registro de los Accidentes Investigados" 
							onMouseOver="window.status='';return true"  onclick="hdn_botonSel.value='finalizar';verificarParametros();"/>
							&nbsp;&nbsp;&nbsp;
						<?php }?>
						<input name="sbt_agregar" type="submit" class="botones" id= "sbt_agregar" value="Agregar" 
						title="Agregar Registro de los Accidentes Investigados"
						onMouseOver="window.status='';return true" onClick="hdn_botonSel.value='agregar';"/>
						&nbsp;&nbsp;&nbsp;
						<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onMouseOver="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" class="botones" value="Cerrar" title="Regresar al Registro de la Acta Seguridad e Higiene" 
						onMouseOver="window.status='';return true"  onclick="hdn_botonSel.value='cerrar';verificarParametros();"/>
          			</div>
				</td>
        	</tr>
    	</table>
	</fieldset>
	</form>
	<div id="calendario">
		<input name="calendario" type="image" id="calendario2" onClick="displayCalendar (document.frm_agregarRegistro.txt_fechaAcc,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png"  title="Seleccione una fecha" align="absbottom" width="25" height="25" border="0" />
	</div>
</body>