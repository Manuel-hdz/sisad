<?php

	/**
	  * Nombre del M�dulo: Unidad de Salud Ocupacional
	  * Nombre Programador:Nadia Madah� L�pez Hern�ndez
	  * Fecha: 19/Julio/2012
	  * Descripci�n: Archivo que permite cargar los aspectos generales del trabajador dentro del Historial Clinico
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
		//Se declara la funcion correspondiente
		function actualizarPag(){
			var nomCkb = document.getElementById("hdn_nomCheckBox").value;
			/*var boton = document.getElementById("hdn_guardarPrueLab").value;
			
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
			#tabla-agregarRegistro {position:absolute;left:30px;top:48px;width:800px;height:307px;z-index:12;}
			#tabla-mostrarRegistros {position:absolute;left:30px;top:220;width:715px;height:230px;z-index:16;padding:15px;padding-top:0px; overflow:scroll}
			.Estilo1 {
				font-family: Arial, Helvetica, sans-serif;
				font-size: 12px;
			}
			-->
	    </style>
	<?php
		
		//Verificamos que exista el boton agregar para poder agregar los datos en la session
		if(isset($_POST["sbt_guardarPrueLab"])){
			$respuesta = registrarPruebasLaboratorio();
			if($respuesta){
				echo "PRUEBA DE LABORATORIO AGREGADA CORRECTAMENTE";
				?>
				<script>
					setTimeout("document.getElementById('hdn_botonCancelar').value = 'guardar';",600);
					setTimeout("window.close();",1000);
				</script>
				<?php
			} else {
				echo "HUBO PROBLEMAS AL REGISTRAR LA PRUEBA DE LABORATORIO";
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
	<form  onSubmit="return valFormPruebasLab(this);"method="post" name="frm_pruebasLab" id="frm_pruebasLab" 
	action="verPruebasLaboratorio.php">
	<fieldset class="borde_seccion" id="tabla-agregarRegistro" name="tabla-agregarRegistro">
    <legend class="titulo_etiqueta">Registrar las Pruebas del Laboratorio</legend>
	<br>
    	<table cellpadding="2" cellspacing="2" class="tabla_frm">
        	<tr>
			  <td><div align="right">VDRL</div></td>
          		<td>
	  		  		<input name="txt_vdrl" type="text" class="caja_de_num" id="txt_vdrl"  onKeyPress="return permite(event,'num_car',8);" value="" 
					size="15" maxlength="15" />				
			  </td>	
		  	  <td><div align="right">B.H.</div></td>
				<td>
	  		  		<input name="txt_bh" type="text" class="caja_de_texto" id="txt_bh"  onKeyPress="return permite(event,'num_car',8);" value="" size="15" 
					maxlength="15" />				
			  </td>	
        	</tr>
			<tr>
				<td><div align="right">*Glicemia</div></td>
          		<td><input name="txt_glicemia" type="text" class="caja_de_texto" id="txt_glicemia"  onKeyPress="return permite(event,'num_car',8);" 
					value="" size="10" maxlength="10" />
				</td>	
				<td><div align="right">PIE</div></td>
				<td>
				  	<input name="txt_pie" type="text" class="caja_de_texto" id="txt_pie" 
					  onKeyPress="return permite(event,'num_car',8);" value="" size="15" maxlength="15"/>				
				</td>
			<tr>
				<td><div align="right">Gral. Orina</div></td>
          		<td>
	  		  		<input name="txt_gralOrina" type="text" class="caja_de_texto" id="txt_gralOrina" onKeyPress="return permite(event,'num_car',8);" value=""  
					 size="15" maxlength="15" />					
				</td>	
				<td><div align="right">PB en Sang</div></td>
          		<td>
		  	  		<input name="txt_pbSang" type="text" class="caja_de_texto" id="txt_pbSang" 
					  onKeyPress="return permite(event,'num_car',8);" value="" size="15" maxlength="15"/>				
				</td>	
        	</tr>
			<tr>
				<td><div align="right">HIV</div></td>
          		<td>
	  		  		<input name="txt_hiv" type="text" class="caja_de_texto" id="txt_hiv" onKeyPress="return permite(event,'num_car',8);" 
					value=""   size="15" maxlength="15" />				
				</td>	
				<td><div align="right">Cadmio</div></td>
          		<td colspan="4">
					<input name="txt_cadmio" type="text" class="caja_de_texto" id="txt_cadmio" onKeyPress="return permite(event,'num_car',8);" 
					value=""   size="15" maxlength="15" />			
				</td>	
			</tr>
			<tr>
				<td><div align="right">Fosfata &Aacute;cida</div></td>
          		<td>
					<input name="txt_fosAcida" type="text" class="caja_de_texto" id="txt_fosAcida" onKeyPress="return permite(event,'num_car',8);" 
					value=""   size="15" maxlength="15" />				
				</td>	
				<td><div align="right">*TG</div></td>
       		  	<td><input name="txt_tg" type="text" class="caja_de_texto" id="txt_tg"   onKeyPress="return permite(event,'num_car',8);"
					 value="" size="10" maxlength="10" />
				</td>	
			</tr>
			<tr>
				<td><div align="right">Fosfata Alcalina</div></td>
       		  	<td><input name="txt_fosAlcalina" type="text" class="caja_de_texto" id="txt_fosAlcalina"   onKeyPress="return permite(event,'num_car',8);"
					 value="" size="15" maxlength="15" />
				</td>	
				<td><div align="right">*Colesterol</div></td>          		
				<td>
					<input name="txt_colesterol" type="text" class="caja_de_texto" id="txt_colesterol" 
					  onKeyPress="return permite(event,'num_car',8);" value="" size="10" maxlength="10" align="absmiddle"/>				
				</td>	
        	</tr>	
			<tr>
				<td><div align="right">Espirometria</div></td>
       		  	<td>
			  		<input name="txt_espirometria" type="text" class="caja_de_texto" id="txt_espirometria"   onKeyPress="return permite(event,'num_car',8);"
					 value="" size="25" maxlength="60" />				
				</td>	
				<td><div align="right">Tipo Sanguineo</div></td>          		
				<td>
					<input name="txt_tipoSanguineo" type="text" class="caja_de_texto" id="txt_tipoSanguineo" 
					  onKeyPress="return permite(event,'num_car',8);" value="" size="10" maxlength="10" align="absmiddle"/>				
				</td>	
			  	<td><div align="right">B Mglobulin</div></td>
          		<td>
				  	<input name="txt_bMglobulin" type="text" class="caja_de_texto" id="txt_bMglobulin" 
					  onKeyPress="return permite(event,'num_car',8);" value="" size="15" maxlength="15"/>				
				</td>
        	</tr>	
			<tr>
				<td><div align="right">FCR</div></td>
       		  	<td>
			  		<input name="txt_fcr" type="text" class="caja_de_texto" id="txt_fcr"   onKeyPress="return permite(event,'num_car',8);"
					 value="" size="25" maxlength="40" />				
				</td>	
				<td><div align="right">*Diag. Laboratorio</div></td>          		
				<td>
					<input name="txt_diagLab" type="text" class="caja_de_texto" id="txt_diagLab" 
					  onKeyPress="return permite(event,'num_car',8);" value="" size="30" maxlength="60" align="absmiddle"/>				
				</td>	
        	</tr>
			<tr>
				<td><div align="right">*Rx. T&oacute;rax</div></td>
          		<td>
	  		  		<input name="txt_rxTorax" type="text" class="caja_de_texto" id="txt_rxTorax" onKeyPress="return permite(event,'num_car',8);" value=""  
					size="25" maxlength="40" />				
				</td>	
				<td><div align="right">*Alcohol&iacute;metro</div></td>
          		<td colspan="4">
	  		  		<input name="txt_alcoholimetro" type="text" class="caja_de_texto" id="txt_alcoholimetro" onKeyPress="return permite(event,'num_car',8);" value=""  
					size="25" maxlength="40" />				
				</td>	
			</tr>	
			<tr>
				<td><div align="right">% Silicosis</div></td>
          		<td>
	  		  		<input name="txt_silicosis" type="text" class="caja_de_texto" id="txt_silicosis" onKeyPress="return permite(event,'num_car',8);"
					 value=""   size="10" maxlength="10" />				
				</td>	
				<td><div align="right">Fracc.</div></td>
          		<td colspan="4">
	  		  		<input name="txt_fracc" type="text" class="caja_de_texto" id="txt_fracc" onKeyPress="return permite(event,'num_car',8);" 
					value=""   size="10" maxlength="10" />				
				</td>	
			</tr>
			<tr>
				<td><div align="right">*Col. Lumbosacra</div></td>
          		<td>
	  		  		<input name="txt_colLum" type="text" class="caja_de_texto" id="txt_colLum" onKeyPress="return permite(event,'num_car',8);" value=""   
					size="25" maxlength="300" />				
				</td>
			</tr>
			<tr>
				<td><div align="right">Romberg</div></td>
          		<td>
	  		  		<input name="txt_romberg" type="text" class="caja_de_texto" id="txt_romberg" onKeyPress="return permite(event,'num_car',8);" value=""   
					size="25" maxlength="40" />				
				</td>
				<td><div align="right">Babinsky Weil</div></td>
          		<td colspan="4">
	  		  		<input name="txt_weil" type="text" class="caja_de_texto" id="txt_weil" onKeyPress="return permite(event,'num_car',8);" value=""   
					size="25" maxlength="40" />				
				</td>	
			</tr>
			<tr>
				<td><div align="right">*Diagnostico</div></td>
          		<td colspan="4">
	  		  		<input name="txt_diagnostico" type="text" class="caja_de_texto" id="txt_diagnostico" onKeyPress="return permite(event,'num_car',8);" value=""   
					size="60" maxlength="300" />				
				</td>	
			</tr>
			<tr>
				<td><div align="right">*Conclusiones</div></td>
          		<td colspan="4">
	  		  		<input name="txt_conclusiones" type="text" class="caja_de_texto" id="txt_conclusiones" onKeyPress="return permite(event,'num_car',8);" value=""   
					size="60" maxlength="60" />				
				</td>	
			</tr>
			<tr>
				<td><div align="right">*Edo. Salud</div></td>
          		<td colspan="4">
	  		  		<input name="txt_edoSalud" type="text" class="caja_de_texto" id="txt_edoSalud" onKeyPress="return permite(event,'num_car',8);" value=""   
					size="40" maxlength="300" />				
				</td>	
			</tr>
			<tr>
          		<td colspan="8">
				  <div align="center">
				 	<input type="hidden" name="hdn_nomCheckBox" id="hdn_nomCheckBox" value="ckb_pruebasLab"/>
				  	<input type="hidden" name="hdn_botonCancelar" id="hdn_botonCancelar" value=""/>
				  	<input type="hidden" name="hdn_guardarPrueLab" id="hdn_guardarPrueLab" value="sbt_guardarPrueLab"/>
				
						<input name="sbt_guardarPrueLab" type="submit" class="botones" id= "sbt_guardarPrueLab" value="Guardar" 
						title="Guardar los Registros de las Pruebas del Laboratorio" onMouseOver="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;
						<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onMouseOver="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" class="botones" value="Cerrar"  title="Regresar al Registro del Historial Clinico"
						onMouseOver="window.status='';return true"  onClick="hdn_botonCancelar.value='cancelar';cancelarRegistrosHistorialClinico();"/>
       			  </div>				</td>
        	</tr>
	</table>
	</fieldset>
	</form>
	</body>