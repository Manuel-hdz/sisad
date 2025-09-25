<?php

	/**
	  * Nombre del M�dulo: Unidad de Salud Ocupacional
	  * Nombre Programador:Nadia Madah� L�pez Hern�ndez
	  * Fecha: 19/Julio/2012
	  * Descripci�n: Archivo que permite registrar el historial de trabajo del empleado dentro del Historial Clinico
	  **/  
		//Titulo de la ventana emergente
		echo "<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>"; 
		//Inlcuimos archivo que contiene las operaciones necesarias para el registro
		include ("op_generarHistorialClinico.php");
		//Archivo de validacion
		echo "<script type='text/javascript' src='../../includes/validacionClinica.js'></script>";
		//Archivo de Estilo
		echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css'/>";
		//Archivo que permite controlar el numero de caracteres ingresados en las cajas de texto (textarea)
		echo "<script type='text/javascript' src='../../includes/maxLength.js' ></script>";
		//Archivo que contiene la funcion de validacion de la sesion para activar o no el boton de guardar
		echo "<script type='text/javascript' src='includes/ajax/verificarSesiones.js'></script>";
		//Archivo para desabilitar boton regresar del teclado?>
		<script language="javascript" type="text/javascript" src="../../includes/disableKeys.js"></script><?php 

		//Incluimos archivo para modificar fechas segun sea requerido	
		include_once("../../includes/func_fechas.php");?>
		<script language="javascript" type="text/javascript">
			<!--
			function click() {
				if (event.button==2) {
					alert ('Contenido Protegido, �Concreto Lanzado de Fresnillo MARCA ');
				}
			}
			document.onmousedown=click;
			//-->
		</script>
		
		<?php
	//Funci�n que se encarga de verificar y validar en caso de que el usuario haya cerrado la ventana emergente desde la X
	 //Definimos una variable bandera
	// $bandera = "";?>
	<script language="javascript" type="text/javascript">
		//Dentro de un Script se define que si la ventana emergente se ha cerrado que se ejecute la siguiente funcion
		/*if(window.closed){
			//Se declara la funcion correspondiente
			function actualizarPag(){
				var nomCkb = document.getElementById("hdn_nomCheckBox").value;
				var boton = document.getElementById("hdn_guardarPrueEsfuerzo").value;
				
				if(window.closed){
					//Dentro de la funcion se declara una variable la cual contendra el valor del CKB
					window.opener.document.getElementById(nomCkb).checked = false;
					<?php //$bandera = 1;?>		
				}
				if(!boton){
					window.opener.document.getElementById(nomCkb).checked = true;
				}
		}
	}*/
		//Se declara la funcion correspondiente
		function actualizarPag(){
			var nomCkb = document.getElementById("hdn_nomCheckBox").value;
			/*var boton = document.getElementById("hdn_guardarAntPato").value;
			
			if(window.closed){
				//Dentro de la funcion se declara una variable la cual contendra el valor del CKB
				window.opener.document.getElementById(nomCkb).checked = false;
				<?php $bandera = 1;?>		
			}
			if(!boton){
				window.opener.document.getElementById(nomCkb).checked = true;
			}*/
			
			var presionado = document.getElementById("hdn_botonCancelar").value;
			
			if(presionado == "guardar"){
				window.opener.document.getElementById(nomCkb).checked = true;
				window.opener.document.getElementById(nomCkb).disabled = true;
			} else {
				window.opener.document.getElementById(nomCkb).checked = false;
				window.opener.document.getElementById(nomCkb).disabled = false;
			}
		}
	</script>
	
		<style type="text/css">
			<!--
			#titulo-agregar-registros { position:absolute; left:30px; top:22px; width:200px; height:20px; z-index:11;}
			#tabla-agregarRegistro {position:absolute;left:30px;top:48px;width:651px;height:178px;z-index:12;}
			#tabla-mostrarRegistros {position:absolute;left:30px;top:220;width:715px;height:230px;z-index:16;padding:15px;padding-top:0px; overflow:scroll}
			.Estilo1 {
				font-family: Arial, Helvetica, sans-serif;
				font-size: 12px;
			}
			-->
	    </style>
	<?php
		
		//Verificamos que exista el boton agregar para poder agregar los datos en la session
		if(isset($_POST["sbt_guardarPrueEsfuerzo"])){
			$respuesta = registrarPruebasEsfuerzo();
			if($respuesta){
				echo "PRUEBA DE ESFUERZO AGREGADA CORRECTAMENTE";
				?>
				<script>
					setTimeout("document.getElementById('hdn_botonCancelar').value = 'guardar';",600);
					setTimeout("window.close();",1000);
				</script>
				<?php
			} else {
				echo "HUBO PROBLEMAS AL REGISTRAR LA PRUEBA DE ESFUERZO";
				?>
				<script>
					setTimeout("window.close();",1000);
				</script>
				<?php
			}
		}
	
	?>
	<body onUnload="actualizarPag();">
	<p>&nbsp;</p>
	<form  onSubmit="return valFormPruebasEsfuerzo(this);" method="post"name="frm_pruebasEsfzo" id="frm_pruebasEsfzo" action="verPruebasEsfuerzo.php">
	<fieldset class="borde_seccion" id="tabla-agregarRegistro" name="tabla-agregarRegistro">
    <legend class="titulo_etiqueta">Registrar Pruebas de Esfuerzo</legend>
	<br>
    	<table width="99%" cellpadding="5" cellspacing="5" class="tabla_frm">
        	<tr>
				<td width="32%">&nbsp;</td>
			  <td width="33%"><div align="rigth"><strong>*Pulso</strong></div></td>
			  <td width="35%"><div align="rigth"><strong>*Respiraci&oacute;n</strong></div></td>
			</tr>
			<tr>
				<td><div align="right">*En Reposo</div></td>
          		<td>
			  		<input name="txt_pulsoRep" type="text" class="caja_de_num" id="txt_pulsoRep" onKeyPress="return permite(event,'num',2);" value="" size="10" maxlength="10" />	
				</td>	
          		<td>
					<input name="txt_respRep" type="text" class="caja_de_num" id="txt_respRep" 
					 onKeyPress="return permite(event,'num',2);" value="" size="10" maxlength="10" align="absmiddle"/>			 
				</td>	
        	</tr>
			<tr>
				<td><div align="right">*Inm. Desp Esfzo.</div></td>
          		<td>
				  	<input name="txt_pulsoInm" type="text" class="caja_de_num" id="txt_pulsoInm" 
					 onKeyPress="return permite(event,'num',2);" value="" size="10" maxlength="10"/>			  
				</td>	
				<td>
		  	  		<input name="txt_respInm" type="text" class="caja_de_num" id="txt_respInm" 
					onKeyPress="return permite(event,'num',2);" value="" size="10" maxlength="10"/>			  
				</td>			
			</tr>
			<tr>
				<td><div align="right">*1 Min. Despu&eacute;s</div></td>
          		<td>
				  	<input name="txt_pulso1Desp" type="text" class="caja_de_num" id="txt_pulso1Desp" 
					 onKeyPress="return permite(event,'num',2);" value="" size="10" maxlength="10"/>			  
				</td>	
				<td>
		  	  		<input name="txt_resp1Desp" type="text" class="caja_de_num" id="txt_resp1Desp" 
					  onKeyPress="return permite(event,'num',2);" value="" size="10" maxlength="10"/>			  
				</td>			
			</tr>
			<tr>
				<td><div align="right">*2 Min. Despu&eacute;s</div></td>
          		<td>
				  	<input name="txt_pulso2Desp" type="text" class="caja_de_num" id="txt_pulso2Desp" 
					 onKeyPress="return permite(event,'num',2);" value="" size="10" maxlength="10"/>			  
				</td>	
				<td>
		  	  		<input name="txt_resp2Desp" type="text" class="caja_de_num" id="txt_resp2Desp" 
					 onKeyPress="return permite(event,'num',2);" value="" size="10" maxlength="10"/>			  
				</td>			
			</tr>
			<tr>
          		<td colspan="4">
				  <div align="center">
				 	<input type="hidden" name="hdn_nomCheckBox" id="hdn_nomCheckBox" value="ckb_pruebasEsfuerzo"/>
				  	<input type="hidden" name="hdn_botonCancelar" id="hdn_botonCancelar" value=""/>
				  	<input type="hidden" name="hdn_guardarPrueEsfuerzo" id="hdn_guardarPrueEsfuerzo" value="sbt_guardarPrueEsfuerzo"/>
					
						<input name="sbt_guardarPrueEsfuerzo" type="submit" class="botones" id= "sbt_guardarPrueEsfuerzo" value="Guardar" 
						title="Guardar los Registros de las Pruebas de Esfuerzo" onMouseOver="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;
						<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onMouseOver="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" class="botones" value="Cerrar"  title="Regresar al Registro del Historial Clinica"
						onMouseOver="window.status='';return true"  onClick="hdn_botonCancelar.value='cancelar';cancelarRegistrosHistorialClinico();"/>
       			  </div>				</td>
        	</tr>
   	</table>
	</fieldset>
	</form>
	</body>