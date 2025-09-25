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
		//Archivo que permite la correcta ejecución del objeto calendario
		echo "<link type='text/css' rel='stylesheet' href='../../includes/estiloCalendario.css?random=20051112' media='screen'>	";
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
			#tabla-agregarRegistro {position:absolute;left:30px;top:10px;width:707px;height:84px;z-index:12;}
			#tabla-mostrarRegistros {position:absolute;left:30px;top:200;width:720px;height:250px;z-index:16;padding:15px;padding-top:0px; overflow:scroll}
			#calendario{position:absolute;left:272px;top:90px;width:30px;height:26px;z-index:14;}
			#calendario2{position:absolute;left:272px;top:50px;width:30px;height:26px;z-index:14;}
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
			unset($_SESSION["recorridos"][$_GET["noRegistro"]]);
			//Verificamos que exista la session y el parametro que contiene la ubicacion del registro dentro de la sesion
			if(isset($_SESSION["recorridos"]) && isset($_GET["noRegistro"]))
				//Reacomodamos el Arreglo
				$_SESSION['recorridos'] = array_values($_SESSION['recorridos']);
			
			//Verificamos si exista la sesion
			if(isset($_SESSION["recorridos"])){
				//Si el arreglo de lista_maestra esta vacio, retirarlo de la SESSION
				if(count($_SESSION["recorridos"])==0){
					//Liberamos la sesion
					unset($_SESSION["recorridos"]);
				}
			}
			
		}//Cierre if(isset($_GET["noRegistro"]))
		//Verificamos que exista el boton agregar para poder agregar los datos en la session
		if(isset($_POST["sbt_agregar"])){
			//Esta variable indica si el registro esta repetido o no
			$repetido = 0;
			if(isset($_SESSION['recorridos'])){
				//Verificar que el registro no este repetido en el Arreglo de SESSION
				foreach($_SESSION["recorridos"] as $ind => $registro){
					if(strtoupper($_POST["txt_actoInseguro"])==$registro["actoInseguro"]){
						$repetido = 1;
						break;
					}
				}	
			}
			if($repetido!=1){		
				//Verificar que en el POST esten los datos definidos a fin de agregarlos al arreglo de Sesion
				//Si ya esta definido el arreglo $lista_maestra, entonces agregar el siguiente registro a el
				if(isset($_SESSION['recorridos'])){
					//Guardar los datos en el arreglo
					$recorridos[] = array("fechaCumplida"=>$_POST['txt_fechaCumplida'],"fechaLimite"=>$_POST['txt_fechaLimite'],
					"responsable"=>strtoupper($_POST['txt_responsable']),"actoInseguro"=>strtoupper($_POST['txt_actoInseguro']));
				}
				//Si no esta definido el arreglo $lista_maestra definirlo y agregar el primer registro
				else{
					$cont=0;
					//Guardar los datos en el arreglo
					$recorridos = array(array("fechaCumplida"=>$_POST['txt_fechaCumplida'],"fechaLimite"=>$_POST['txt_fechaLimite'],
					"responsable"=>strtoupper($_POST['txt_responsable']),"actoInseguro"=>strtoupper($_POST['txt_actoInseguro'])));
					$_SESSION['recorridos'] = $recorridos;
				}
			}
			else{?>
				<script type="text/javascript" language="javascript">
					setTimeout("alert('El Acto Inseguro Ingresado, ya se encuentra Registrado; Intente Nuevamente')", 500);
				</script><?php
			}	
		}
		
		//Verificar que este definido el Arreglo de produccion, si es asi, lo mostramos en el formulario
		if (isset($_SESSION["recorridos"])){
			echo "<div id='tabla-mostrarRegistros' class='borde_seccion2'>";
				mostrarRegistrosRecorridos($recorridos);
			echo "</div>";
		}
	
	?>
	<body onUnload="verificarParametros();">
	<p>&nbsp;</p>
	<form onSubmit="return valFormRecVer(this);" method="post"name="frm_agregarRegistro" id="frm_agregarRegistro" action="verRegRecorridosVerificacion.php">
	<fieldset class="borde_seccion" id="tabla-agregarRegistro" name="tabla-agregarRegistro">
    <legend class="titulo_etiqueta">Ingresar Informaci&oacute;n de Recorridos de Verificaci&oacute;n </legend>
    <br />
    	<table width="689" height="43"  cellpadding="5" cellspacing="5" class="tabla_frm">
        	<tr>
          		<td width="113" height="-7"><label></label>          		  <div align="right">*Fecha Cumplida</div></td>
          		<td width="121">
					<input name="txt_fechaCumplida" type="text" id="txt_fechaCumplida" size="10" maxlength="15" value="<?php echo date("d/m/Y");?>" 
			 		readonly="readonly" style="background-color:#999999; color:#FFFFFF" class="caja_de_texto"/></td>
          		<td width="117"><div align="right">          		  
       		    *Responsable</div></td>
       	        <td width="271">
					<input type="text" name="txt_responsable" id="txt_responsable" value="" size="45" maxlength="60" onKeyPress="return permite(event,'car', 1);" 
					class="caja_de_texto"/>
				</td>
        	</tr>
        	<tr>
        		<td height="-2"><div align="right">*Fecha L&iacute;mite</div></td>
        	  	<td height="-2">
			  		<input name="txt_fechaLimite" type="text" id="txt_fechaLimite" size="10" maxlength="15" value="<?php echo date("d/m/Y");?>" 
			  		readonly="readonly" style="background-color:#999999; color:#FFFFFF" class="caja_de_texto"/></td>
        	  	<td><div align="right">*Acto Inseguro </div></td>
      	      	<td>
			  		<input type="text" name="txt_actoInseguro" id="txt_actoInseguro" value=""  size="45" maxlength="80"  onkeypress="return permite(event,'car', 0);" 
					class="caja_de_texto"/>
				</td>
       	  	</tr>
			<tr>
          		<td colspan="4">
					<div align="center">
				  		<input type="hidden" name="hdn_nomCheckBox" id="hdn_nomCheckBox" value="ckb_recVer"/>
				  		<input type="hidden" name="hdn_botonSel" id="hdn_botonSel" value="x"/>
						<input type="hidden" name="hdn_datosSesion" id="hdn_datosSesion" 
						<?php if(isset($_SESSION['recorridos'])){ ?> value="si" <?php } else {?> value="no" <?php } ?>/><?php
				  		if(isset($_SESSION['recorridos'])){?>
							<input name="btn_finalizar" type="button" class="botones" value="Finalizar" 
							title="Finalizar el Registro de los Recorridos de Verificaci&oacute;n" 
							onmouseover="window.status='';return true"  onclick="hdn_botonSel.value='finalizar';verificarParametros();" />
							&nbsp;&nbsp;&nbsp;
						<?php }?>
						<input name="sbt_agregar" type="submit" class="botones" id= "sbt_agregar" value="Agregar" 
						title="Agregar Registro De Recorridos de Verificaci&oacute;n" 
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
	<div id="calendario">
		<input name="calendario" type="image" id="calendario4" onClick="displayCalendar (document.frm_agregarRegistro.txt_fechaLimite,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png"  title="Seleccione una fecha" align="absbottom" width="25" height="25" border="0" />
	</div>
	<div id="calendario2">
    	<input name="calendario2" type="image" id="calendario3" onClick="displayCalendar (document.frm_agregarRegistro.txt_fechaCumplida,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png"  title="Seleccione una fecha" align="absbottom" width="25" height="25" border="0" /></div>
</body>