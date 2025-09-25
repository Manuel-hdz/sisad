<?php

	/**
	  * Nombre del Módulo: Seguridad Industrial                                                
	  * Nombre Programador: Daisy Ariana Martínez Fernández
	  * Fecha: 07/Febrero/2012
	  * Descripción: Archivo que permite cargar los puntos tratados en la agenda
	  **/  
		//Titulo de la ventana emergente
		echo "<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>"; 
		//Inlcuimos archivo que contiene las operaciones necesarias para el registro
		include ("op_registrarActaSeguridadHigiene.php");
		//Archivo de validacion
		echo "<script type='text/javascript' src='../../includes/validacionSeguridad.js'></script>";
		//Archivo de Estilo
		echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css'/>";
		//Archivo que permite controlar el numero de caracteres ingresados en las cajas de texto (textarea)
		echo "<script type='text/javascript' src='../../includes/maxLength.js' ></script>";
		//Archivo que contiene la funcion de validacion de la sesion para activar o no el boton de guardar
		echo "<script type='text/javascript' src='includes/ajax/verificarSesiones.js'></script>";//Archivo para desabilitar boton regresar del teclado?>
		<script language="javascript" type="text/javascript" src="../../includes/disableKeys.js"></script><?php 
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
			#tabla-agregarRegistro {position:absolute;left:30px;top:43px;width:679px;height:56px;z-index:12;}
			#tabla-mostrarRegistros {position:absolute;left:30px;top:220;width:715px;height:230px;z-index:16;padding:15px;padding-top:0px; overflow:scroll}
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
			unset($_SESSION["agenda"][$_GET["noRegistro"]]);
			//Verificamos que exista la session y el parametro que contiene la ubicacion del registro dentro de la sesion
			if(isset($_SESSION["agenda"]) && isset($_GET["noRegistro"]))
				//Reacomodamos el Arreglo
				$_SESSION['agenda'] = array_values($_SESSION['agenda']);
			
			//Verificamos si exista la sesion
			if(isset($_SESSION["agenda"])){
				//Si el arreglo de lista_maestra esta vacio, retirarlo de la SESSION
				if(count($_SESSION["agenda"])==0){
					//Liberamos la sesion
					unset($_SESSION["agenda"]);
				}
			}
			
		}//Cierre if(isset($_GET["noRegistro"]))
		//Verificamos que exista el boton agregar para poder agregar los datos en la session
		if(isset($_POST["sbt_agregar"])){
			//Esta variable indica si el registro esta repetido o no
			$repetido = 0;
			if(isset($_SESSION['agenda'])){
				//Verificar que el registro no este repetido en el Arreglo de SESSION
				foreach($_SESSION["agenda"] as $ind => $registro){
					if(strtoupper($_POST["txa_puntoAgenda"])==$registro["punto"]){
						$repetido = 1;
						break;
					}
				}	
			}
			if($repetido!=1){		
				//Verificar que en el POST esten los datos definidos a fin de agregarlos al arreglo de Sesion
				//Si ya esta definido el arreglo $lista_maestra, entonces agregar el siguiente registro a el
				if(isset($_SESSION['agenda'])){
					//Guardar los datos en el arreglo
					$agenda[] = array("punto"=>strtoupper($_POST['txa_puntoAgenda']));
				}
				//Si no esta definido el arreglo $lista_maestra definirlo y agregar el primer registro
				else{
					$cont=0;
					//Guardar los datos en el arreglo
					$agenda = array(array("punto"=>strtoupper($_POST['txa_puntoAgenda'])));
					$_SESSION['agenda'] = $agenda;
				}
			}
			else{?>
				<script type="text/javascript" language="javascript">
					setTimeout("alert('El Punto ya se encuentra Registrado')", 500);
				</script><?php
			}	
		}
		
		//Verificar que este definido el Arreglo de produccion, si es asi, lo mostramos en el formulario
		if (isset($_SESSION["agenda"])){
			echo "<div id='tabla-mostrarRegistros' class='borde_seccion2'>";
				mostrarRegistrosPuntosAgenda($agenda);
			echo "</div>";
		}
	
	?>
	<body onUnload="verificarParametros();">
	<p>&nbsp;</p>
	<form  onSubmit="return valFormPuntosAgenda(this);"method="post"name="frm_agregarRegistro" id="frm_agregarRegistro" action="verPuntosAgenda.php">
	<fieldset class="borde_seccion" id="tabla-agregarRegistro" name="tabla-agregarRegistro">
    <legend class="titulo_etiqueta">Ingresar Puntos Tratados en la Agenda </legend>
    <br/>
    	<table width="677" height="43"  cellpadding="5" cellspacing="5" class="tabla_frm">
        	<tr>
          		<td width="135"><div align="right">*Puntos Agenda</div></td>
          		<td width="505">	
					<textarea name="txa_puntoAgenda" id="txa_puntoAgenda" maxlength="250" onKeyUp="return ismaxlength(this)" class="caja_de_texto" rows="3"
				 	cols="80" onKeyPress="return permite(event,'num_car', 0);"></textarea>
				</td>
        	</tr>
			<tr>
          		<td colspan="4">
				  <div align="center">
				 	<input type="hidden" name="hdn_nomCheckBox" id="hdn_nomCheckBox" value="ckb_puntosAgenda"/>
				  	<input type="hidden" name="hdn_botonSel" id="hdn_botonSel" value="x"/>
					<input type="hidden" name="hdn_datosSesion" id="hdn_datosSesion" 
					<?php if(isset($_SESSION['agenda'])){ ?> value="si" <?php } else {?> value="no" <?php } ?>/><?php
				  	if(isset($_SESSION['agenda'])){?>
						<input name="btn_finalizar" type="button" class="botones" value="Finalizar" title="Finalizar el Registro de los Puntos Tratados en la Agenda" 
						onMouseOver="window.status='';return true"  onclick="hdn_botonSel.value='finalizar';verificarParametros();"/>
						&nbsp;&nbsp;&nbsp;
					<?php }?>
						<input name="sbt_agregar" type="submit" class="botones" id= "sbt_agregar" value="Agregar" 
						title="Agregar Registro De Los Puntos de la Agenda" onMouseOver="window.status='';return true" onClick="hdn_botonSel.value='agregar';"/>
						&nbsp;&nbsp;&nbsp;
						<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onMouseOver="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" class="botones" value="Cerrar"  title="Regresar al Registro de la Acta Seguridad e Higiene"
						onMouseOver="window.status='';return true"  onclick="hdn_botonSel.value='cerrar';verificarParametros();"/>
          			</div>
				</td>
        	</tr>
    	</table>
	</fieldset>
	</form>
	</body>