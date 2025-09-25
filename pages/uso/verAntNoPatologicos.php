<?php

	/**
	  * Nombre del M�dulo: Unidad de Salud Ocupacional
	  * Nombre Programador:Nadia Madah� L�pez Hern�ndez
	  * Fecha: 19/Julio/2012
	  * Descripci�n: Archivo que permite cargar dle historial familiar del trabajador dentro del Historial Clinico
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
	 $bandera = "";?>
	<script language="javascript" type="text/javascript">
		//Dentro de un Script se define que si la ventana emergente se ha cerrado que se ejecute la siguiente funcion
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
				#tabla-agregarRegistro {position:absolute;left:30px;top:48px;width:762px;height:188px;z-index:12;}
				#tabla-mostrarRegistros {position:absolute;left:30px;top:220;width:715px;height:230px;z-index:16;padding:15px;padding-top:0px; overflow:scroll}
				.Estilo1 {
					font-family: Arial, Helvetica, sans-serif;
					font-size: 12px;
				}
				-->
	    </style>
	<?php
		
		//Verificamos que exista el boton agregar para poder agregar los datos en la session
		if(isset($_POST["sbt_guardarAntPato"])){
			$respuesta = registrarAntPatologicos();
			if($respuesta){
				echo "ANTECEDENTES NO PATOLOGICOS AGREGADOS CORRECTAMENTE";
				?>
				<script>
					setTimeout("document.getElementById('hdn_botonCancelar').value = 'guardar';",600);
					setTimeout("window.close();",1000);
				</script>
				<?php
			} else {
				echo "HUBO PROBLEMAS AL REGISTRAR LOS ANTECEDENTES NO PATOLOGICOS";
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
	<form  onSubmit="return valFormAntNoPatologicos(this);"method="post"name="frm_antPatologicos" id="frm_antPatologicos" action="verAntNoPatologicos.php">
	<fieldset class="borde_seccion" id="tabla-agregarRegistro" name="tabla-agregarRegistro">
    <legend class="titulo_etiqueta">Registrar los Antecedentes No Patologicos</legend>
	<br>
    	<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
        	<tr>
				<td><div align="right">*Actividad</div></td>
				<td>
					<select name="cmb_actividad" class="combo_box" id="cmb_actividad" >
						<option value="" selected="selected">Actividad</option>
						<option value="SEDENTARIO">SEDENTARIO</option>
						<option value="BAJA">BAJA</option>																					
						<option value="MEDIA">MEDIA</option>																					
						<option value="ALTA">ALTA</option>																																			
						<option value="ALTO RENDIMIENTO">ALTO RENDIMIENTO</option>																												
					</select>					
				</td>
				<td><div align="right">*Tabaquismo</div></td>
          		<td>
					<select name="cmb_tabaquismo" class="combo_box" id="cmb_tabaquismo" >
						<option value="" selected="selected">Tabaquismo</option>
						<option value="NEGATIVO">NEGATIVO</option>
						<option value="GRADO I">GRADO I</option>																					
						<option value="GRADO II">GRADO II</option>																					
						<option value="GRADO III">GRADO III</option>																																			
						<option value="GRADO IV">GRADO IV</option>																												
					</select>					
				</td>
        	</tr>
			<tr>
				<td><div align="right">*Etilismo</div></td>
          		<td>
					<select name="cmb_etilismo" class="combo_box" id="cmb_etilismo" >
						<option value="" selected="selected">Etilismo</option>
						<option value="NEGATIVO">NEGATIVO</option>
						<option value="GRADO I">GRADO I</option>																					
						<option value="GRADO II">GRADO II</option>																					
						<option value="GRADO III">GRADO III</option>																																			
						<option value="GRADO IV">GRADO IV</option>																												
					</select>					
				</td>
				<td><div align="right">*Otras Adicciones</div></td>
				<td>
					<input name="txt_otrasAdicciones" type="text" class="caja_de_texto" id="txt_otrasAdicciones" onKeyPress="return permite(event,'num_car',8);"
					 value="" size="30" maxlength="70"/>
				</td>
			</tr>
			<tr>
          		<td colspan="4">
					<div align="center">
						<input type="hidden" name="hdn_nomCheckBox" id="hdn_nomCheckBox" value="ckb_antPatologicos"/>
				  		<input type="hidden" name="hdn_botonCancelar" id="hdn_botonCancelar" value=""/>
						<input type="hidden" name="hdn_cerrar" id="hdn_cerrar" value="1"/>
				  		<input type="hidden" name="hdn_guardarAntPato" id="hdn_guardarAntPato" value="sbt_guardarAntPato"/>

						<input name="sbt_guardarAntPato" type="submit" class="botones" id= "sbt_guardarAntPato" value="Guardar" 
						title="Guardar los Registros de los Antecendentes No Pat�logicos" onMouseOver="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;

						<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onMouseOver="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" class="botones" value="Cancelar"  title="Regresar al Registro del Historial Clinico"
						onMouseOver="window.status='';return true" onClick="hdn_botonCancelar.value='cancelar';cancelarRegistrosHistorialClinico();"/>
					</div>				
				
				</td>
        	</tr>
   	</table>
	</fieldset>
	</form>
	</body>