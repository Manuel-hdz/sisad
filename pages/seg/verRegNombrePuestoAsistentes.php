<?php

	/**
	  * Nombre del Módulo: Seguridad Industrial                                                
	  * Nombre Programador: Daisy Ariana Martínez Fernández
	  * Fecha: 07/Febrero/2012
	  * Descripción: Archivo que permite cargar el nombre y puesto de los asistentes en el acta de seguridad
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
		<script language="javascript" type="text/javascript" src="../../includes/disableKeys.js"></script>
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
			#tabla-mostrarRegistros {position:absolute;left:30px;top:200;width:710px;height:230px;z-index:16;padding:15px;padding-top:0px; overflow:scroll}
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
			unset($_SESSION["asistentes"][$_GET["noRegistro"]]);
			//Verificamos que exista la session y el parametro que contiene la ubicacion del registro dentro de la sesion
			if(isset($_SESSION["asistentes"]) && isset($_GET["noRegistro"]))
				//Reacomodamos el Arreglo
				$_SESSION['asistentes'] = array_values($_SESSION['asistentes']);
			
			//Verificamos si exista la sesion
			if(isset($_SESSION["asistentes"])){
				//Si el arreglo de lista_maestra esta vacio, retirarlo de la SESSION
				if(count($_SESSION["asistentes"])==0){
					//Liberamos la sesion
					unset($_SESSION["asistentes"]);
				}
			}
			
		}//Cierre if(isset($_GET["noRegistro"]))
		//Verificamos que exista el boton agregar para poder agregar los datos en la session
		if(isset($_POST["sbt_agregar"])){
			$registro = 0;
			if(isset($_SESSION['asistentes'])){
				foreach($_SESSION["asistentes"] as $ind => $registro){
					if($registro['puesto']==$_POST['cmb_puestoAsistente']&&$registro['puesto']=="COORDINADOR"){
						$registro = 1;
						break;
					}
					if($registro['puesto']==$_POST['cmb_puestoAsistente']&&$registro['puesto']=="SECRETARIO"){
						$registro = 2;
						break;
					}
				}
			}
			if($registro!=1&&$registro!=2){		
				//Verificar que en el POST esten los datos definidos a fin de agregarlos al arreglo de Sesion
				//Si ya esta definido el arreglo $lista_maestra, entonces agregar el siguiente registro a el
				if(isset($_SESSION['asistentes'])){
					//Guardar los datos en el arreglo
					$asistentes[] = array("puesto"=>strtoupper($_POST['cmb_puestoAsistente']), "nombre"=>strtoupper($_POST['txt_nombreAsistente']));
				}
				//Si no esta definido el arreglo $lista_maestra definirlo y agregar el primer registro
				else{
					$cont=0;
					//Guardar los datos en el arreglo
					$asistentes = array(array("puesto"=>strtoupper($_POST['cmb_puestoAsistente']), "nombre"=>strtoupper($_POST['txt_nombreAsistente'])));
					$_SESSION['asistentes'] = $asistentes;
				}
			}
			else{?>
				<script type="text/javascript" language="javascript">
					setTimeout("alert('Solo Se Permite un Registro del Puesto <?php echo $_POST["cmb_puestoAsistente"];?> Intente Con Otro Puesto')", 500);
				</script><?php
			}	
		}
		
		//Verificar que este definido el Arreglo de produccion, si es asi, lo mostramos en el formulario
		if (isset($_SESSION["asistentes"])){
			echo "<div id='tabla-mostrarRegistros' class='borde_seccion2'>";
				mostrarRegistrosAsistentes($asistentes);
			echo "</div>";
		}
	
	?>
	<body onUnload="verificarParametros();">
	<p>&nbsp;</p>
	<form onSubmit="return valFormPuestoAsist(this);" method="post"name="frm_agregarRegistro" id="frm_agregarRegistro" action="verRegNombrePuestoAsistentes.php">
	<fieldset class="borde_seccion" id="tabla-agregarRegistro" name="tabla-agregarRegistro">
    <legend class="titulo_etiqueta">Ingresar Puesto y Nombre de los Asistentes </legend>
    <br />
    	<table width="634" height="43"  cellpadding="5" cellspacing="5" class="tabla_frm">
        	<tr>
          		<td width="61" height="31"><div align="right">*Puesto</div></td>
          		<td width="140">
          			<select name="cmb_puestoAsistente" id="cmb_puestoAsistente" class="combo_box">
				  		<option value="">PUESTO</option>	
          		    	<option value="COORDINADOR">COORDINADOR</option>
						<option value="SECRETARIO">SECRETARIO</option>
						<option value="VOCAL">VOCAL</option>
						<option value="INVITADO">INVITADO</option>
       		      	</select>
          		</td>
          		<td width="70"><div align="right">*Nombre </div></td>
          		<td width="296">
					<input type="text" name="txt_nombreAsistente" id="txt_nombreAsistente" maxlength="60" size="60" class="caja_de_texto" 
					onkeypress="return permite(event,'num_car', 1);"/>
				</td>
        	</tr>
			<tr>
          		<td colspan="4">
					<div align="center">
				  		<input type="hidden" name="hdn_nomCheckBox" id="hdn_nomCheckBox" value="ckb_nomPuestoAsist"/>
				  		<input type="hidden" name="hdn_botonSel" id="hdn_botonSel" value="x"/>
					<input type="hidden" name="hdn_datosSesion" id="hdn_datosSesion" 
					<?php if(isset($_SESSION['asistentes'])){ ?> value="si" <?php } else {?> value="no" <?php } ?>/><?php
					if(isset($_SESSION['asistentes'])){?>						
						<input name="btn_finalizar" type="button" class="botones" value="Finalizar" title="Finalizar el Registro de Nombre y Puesto de Asistentes" 
						onmouseover="window.status='';return true"  onclick="hdn_botonSel.value='finalizar';verificarParametros();" />
						&nbsp;&nbsp;&nbsp;<?php 
					}?>
					<input name="sbt_agregar" type="submit" class="botones" id= "sbt_agregar" value="Agregar" title="Agregar Registro De Asistentes" 
					onmouseover="window.status='';return true" onClick="hdn_botonSel.value='agregar';"/>
					&nbsp;&nbsp;&nbsp;
					<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onMouseOver="window.status='';return true"/>
					&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Cerrar" title="Regresar al Registro de la Acta Seguridad e Higiene" 
					onmouseover="window.status='';return true"  onclick="hdn_botonSel.value='cerrar';verificarParametros();" />
          		</div>
			</td>
        </tr>
    </table>
	</fieldset>
	</form>
	</body>