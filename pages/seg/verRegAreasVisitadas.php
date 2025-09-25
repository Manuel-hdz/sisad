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
		echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css'/>";
		//Archivo que permite controlar el numero de caracteres ingresados en las cajas de texto (textarea)
		echo "<script type='text/javascript' src='../../includes/maxLength.js' ></script>";
		//Archivo que contiene la funcion de validacion de la sesion para activar o no el boton de guardar
		echo "<script type='text/javascript' src='includes/ajax/verificarSesiones.js'></script>";
		//Archivo para desabilitar boton regresar del teclado?>
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
			#tabla-agregarRegistro {position:absolute;left:30px;top:43px;width:635px;height:84px;z-index:12;}
			#tabla-mostrarRegistros {position:absolute;left:30px;top:220;width:675px;height:220px;z-index:16;padding:15px;padding-top:0px; overflow:scroll}
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
			unset($_SESSION["visitas"][$_GET["noRegistro"]]);
			//Verificamos que exista la session y el parametro que contiene la ubicacion del registro dentro de la sesion
			if(isset($_SESSION["visitas"]) && isset($_GET["noRegistro"]))
				//Reacomodamos el Arreglo
				$_SESSION['visitas'] = array_values($_SESSION['visitas']);
			
			//Verificamos si exista la sesion
			if(isset($_SESSION["visitas"])){
				//Si el arreglo de lista_maestra esta vacio, retirarlo de la SESSION
				if(count($_SESSION["visitas"])==0){
					//Liberamos la sesion
					unset($_SESSION["visitas"]);
				}
			}
			
		}//Cierre if(isset($_GET["noRegistro"]))
		//Verificamos que exista el boton agregar para poder agregar los datos en la session
		if(isset($_POST["sbt_agregar"])){
			//Esta variable indica si el registro esta repetido o no
			$repetido = 0;
			if(isset($_SESSION['visitas'])){
				//Verificar que el registro no este repetido en el Arreglo de SESSION
				foreach($_SESSION["visitas"] as $ind => $registro){
					if(strtoupper($_POST["txa_area"])==$registro["area"]){
						$repetido = 1;
						break;
					}
				}	
			}
			if($repetido!=1){		
				//Verificar que en el POST esten los datos definidos a fin de agregarlos al arreglo de Sesion
				//Si ya esta definido el arreglo $lista_maestra, entonces agregar el siguiente registro a el
				if(isset($_SESSION['visitas'])){
					//Guardar los datos en el arreglo
					$visitas[] = array("area"=>strtoupper($_POST['txa_area']));
				}
				//Si no esta definido el arreglo $lista_maestra definirlo y agregar el primer registro
				else{
					$cont=0;
					//Guardar los datos en el arreglo
					$visitas = array(array("area"=>strtoupper($_POST['txa_area'])));
					$_SESSION['visitas'] = $visitas;
				}
			}
			else{?>
				<script type="text/javascript" language="javascript">
					setTimeout("alert('El Área Ingresada ya se encuentra Registrada; Intente Nuevamente')", 500);
				</script><?php
			}	
		}
		
		//Verificar que este definido el Arreglo de produccion, si es asi, lo mostramos en el formulario
		if (isset($_SESSION["visitas"])){
			echo "<div id='tabla-mostrarRegistros' class='borde_seccion2'>";
				mostrarRegistrosAreasVisitadas($visitas);
			echo "</div>";
		}
	
	?>
	<body onUnload="verificarParametros();">
	<p>&nbsp;</p>
	<form onSubmit="return valFormAreasVisitadas(this);" method="post"name="frm_agregarRegistro" id="frm_agregarRegistro" action="verRegAreasVisitadas.php">
	<fieldset class="borde_seccion" id="tabla-agregarRegistro" name="tabla-agregarRegistro">
    <legend class="titulo_etiqueta">Ingresar &Aacute;reas Visitadas </legend>
    <br />
    	<table width="634" height="43"  cellpadding="5" cellspacing="5" class="tabla_frm">
        	<tr>
          		<td height="31"><label></label>          		  <div align="right">*&Aacute;reas Visitadas </div></td>
          		<td width="296">
					<textarea name="txa_area" id="txa_area" maxlength="250" onKeyUp="return ismaxlength(this)" class="caja_de_texto" rows="3"
				 	cols="80" onKeyPress="return permite(event,'num_car', 0);"></textarea>
				</td>
        	</tr>
			<tr>
          		<td colspan="2">
					<div align="center">
				  		<input type="hidden" name="hdn_nomCheckBox" id="hdn_nomCheckBox" value="ckb_areasVisitadas"/>
				  		<input type="hidden" name="hdn_botonSel" id="hdn_botonSel" value="x"/>
						<input type="hidden" name="hdn_datosSesion" id="hdn_datosSesion" 
						<?php if(isset($_SESSION['visitas'])){ ?> value="si" <?php } else {?> value="no" <?php } ?>/><?php
				  		if(isset($_SESSION['visitas'])){?>
							<input name="btn_finalizar" type="button" class="botones" value="Finalizar" title="Finalizar el Registro de &Aacute;reas Visitadas" 
							onMouseOver="window.status='';return true"  onclick="hdn_botonSel.value='finalizar';verificarParametros();" />
							&nbsp;&nbsp;&nbsp;
						<?php }?>
						<input name="sbt_agregar" type="submit" class="botones" id= "sbt_agregar" value="Agregar" title="Agregar Registro De &Aacute;reas Visitadas" 
						onMouseOver="window.status='';return true" onClick="hdn_botonSel.value='agregar';"/>
						&nbsp;&nbsp;&nbsp;
						<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onMouseOver="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" class="botones" value="Cerrar" title="Regresar al Registro de la Acta Seguridad e Higiene" 
						onMouseOver="window.status='';return true"  onclick="hdn_botonSel.value='cerrar';verificarParametros();" />
          			</div>
				</td>
    	   	</tr>
    	</table>
	</fieldset>
	</form>
	</body>