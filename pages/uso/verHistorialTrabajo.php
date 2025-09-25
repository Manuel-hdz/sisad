<?php

	/**
	  * Nombre del M�dulo: Unidad de Salud Ocupacional
	  * Nombre Programador:Nadia Madah� L�pez Hern�ndez
	  * Fecha: 19/Julio/2012
	  * Descripci�n: Archivo que permite registrar el historial de trabajo del empleado dentro del Historial Clinico
	  **/  
		session_start();	  
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
			/*var boton = document.getElementById("hdn_finalizar").value;
			
			if(window.closed){
				//Dentro de la funcion se declara una variable la cual contendra el valor del CKB
				window.opener.document.getElementById(nomCkb).checked = false;
				<?php $bandera = 1;?>		
			}
			if(!boton){
				window.opener.document.getElementById(nomCkb).checked = true;
			}*/
			var presionado = document.getElementById("hdn_botonSeleccionado").value;
			
			if(presionado == "finalizar"){
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
			#tabla-agregarRegistro {position:absolute;left:22px;top:48px;width:829px;height:209px;z-index:12;}
			#tabla-mostrarRegistros {position:absolute;left:23px;top:304px;width:829px;height:288px;z-index:16;padding:15px;padding-top:0px; overflow:scroll}
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
			unset($_SESSION["HisTrabajo"][$_GET["noRegistro"]]);
			//Verificamos que exista la session y el parametro que contiene la ubicacion del registro dentro de la sesion
			if(isset($_SESSION["HisTrabajo"]) && isset($_GET["noRegistro"]))
				//Reacomodamos el Arreglo
				$_SESSION['HisTrabajo'] = array_values($_SESSION['HisTrabajo']);
			
			//Verificamos si exista la sesion
			if(isset($_SESSION["HisTrabajo"])){
				//Si el arreglo del Historial de Trabajo esta vacio, retirarlo de la SESSION
				if(count($_SESSION["HisTrabajo"])==0){
					//Liberamos la sesion
					unset($_SESSION["HisTrabajo"]);
				}
			}
			
		}//Cierre if(isset($_GET["noRegistro"]))
		//Verificamos que exista el boton agregar para poder agregar los datos en la session
		if(isset($_POST["sbt_agregar"])){
			//Esta variable indica si el registro esta repetido o no
			$repetido = 0;
			if(isset($_SESSION['HisTrabajo'])){
				//Verificar que el registro no este repetido en el Arreglo de SESSION
				foreach($_SESSION["HisTrabajo"] as $ind => $value){
					if(strtoupper($_POST["txt_lugar"])==$value["lugar"]){
						$repetido = 1;
						break;
					}
				}	
			}
			if($repetido!=1){
			//Definimos 6 variables para cada una de las opciones que tenemos dentro de lod CKB
				$condEsp1 = "";
				//Verificamos si cada una de estas opciones se encuentran o vienen definidas dentro del $_POST[], 
				if(isset($_POST['ckb_ergonomia']))
					//Si se encuentran definidas colocamos el valor de los CKB, dentro de cada una de las variables que fueron declaradas.
					$condEsp1 = $_POST['ckb_ergonomia'];
				$condEsp2 ="";
				if(isset($_POST['ckb_luz']))
					$condEsp2 = $_POST['ckb_luz'];
				$condEsp3 ="";
				if(isset($_POST['ckb_polvo']))
					$condEsp3 = $_POST['ckb_polvo'];
				$condEsp4 ="";
				if(isset($_POST['ckb_ruido']))
					$condEsp4 = $_POST['ckb_ruido'];
				$condEsp5 ="";
				if(isset($_POST['ckb_sedentarismo']))
					$condEsp5 = $_POST['ckb_sedentarismo'];
				$condEsp6 ="";							
				if(isset($_POST['ckb_vibracion']))
					$condEsp6 = $_POST['ckb_vibracion'];
				
				if(isset($_SESSION['HisTrabajo'])){																			
					//Verificar que en el POST esten los datos definidos a fin de agregarlos al arreglo de Sesion
					//Si ya esta definido el arreglo $trabajo, entonces agregar el siguiente registro a el
					//Guardar los datos en el arreglo
					$HisTrabajo[] = array("lugar"=>strtoupper($_POST['txt_lugar']), "tipoTrab"=>strtoupper($_POST['txt_tipoTrab']), "tiempo"=>strtoupper($_POST['txt_tiempo']), 
					"condEsp1"=>strtoupper($condEsp1), "condEsp2"=>strtoupper($condEsp2), "condEsp3"=>strtoupper($condEsp3),
					"condEsp4"=>strtoupper($condEsp4), "condEsp5"=>strtoupper($condEsp5), "condEsp6"=>strtoupper($condEsp6));
				}
				//Si no esta definido el arreglo $trabajo definirlo y agregar el primer registro
				else{
					$cont=0;
					//Guardar los datos en el arreglo
					$HisTrabajo = array(array("lugar"=>strtoupper($_POST['txt_lugar']), "tipoTrab"=>strtoupper($_POST['txt_tipoTrab']), "tiempo"=>strtoupper($_POST['txt_tiempo']), 
					"condEsp1"=>strtoupper($condEsp1), "condEsp2"=>strtoupper($condEsp2), "condEsp3"=>strtoupper($condEsp3),
					"condEsp4"=>strtoupper($condEsp4), "condEsp5"=>strtoupper($condEsp5), "condEsp6"=>strtoupper($condEsp6)));
					$_SESSION['HisTrabajo'] = $HisTrabajo;
				}
			}
			else{?>
				<script type="text/javascript" language="javascript">
					setTimeout("alert('El Lugar de Trabajo ya se encuentra Registrado')", 500);
				</script><?php
			}
		}

		//Verificar que este definido el Arreglo de HisTrabajo, si es asi, lo mostramos en el formulario
		if (isset($_SESSION["HisTrabajo"])){
			echo "<div id='tabla-mostrarRegistros' class='borde_seccion2'>";
				mostrarRegistrosHisTrabajo($HisTrabajo);
			echo "</div>";
		}?>
	<?php
		//Verificamos que exista el boton agregar para poder agregar los datos en la session
		if(isset($_POST["btn_finalizar"])){
			$respuesta = registrarHisTrabajo();
			//Liberamos la session
			unset($_SESSION['HisTrabajo']);
			if($respuesta){
				echo "HISTORIAL DE TRABAJO AGREGADO CORRECTAMENTE";
				?>
				<script>
					setTimeout("document.getElementById('hdn_botonSeleccionado').value = 'finalizar';",600);
					setTimeout("window.close();",1000);
				</script>
				<?php
			} else {
				echo "HUBO PROBLEMAS AL REGISTRAR EL HISTORIAL DE TRABAJO";
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
	<form  onSubmit="return valFormHistorialTrabajo(this);" method="post"name="frm_registrarHisTrabajo" id="frm_registrarHisTrabajo" action="verHistorialTrabajo.php">
	<fieldset class="borde_seccion" id="tabla-agregarRegistro" name="tabla-agregarRegistro">
    <legend class="titulo_etiqueta">Ingresar el Historial de Trabajo del Empleado</legend>
	<br>
    	<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
        	<tr>
				<td><div align="right">*Lugar</div></td>
          		<td>
			  		<input name="txt_lugar" type="text" class="caja_de_texto" id="txt_lugar" onKeyPress="return permite(event,'num_car',8);" value=""  size="50" maxlength="70" />			
		  	  	</td>	
			  	<td width="153"><div align="right">*Tipo Trabajo</div></td>
          		<td width="296">
				  	<input name="txt_tipoTrab" type="text" class="caja_de_texto" id="txt_tipoTrab" 
					  onKeyPress="return permite(event,'num_car',8);" value="" size="50" maxlength="60"/>		  
			  	</td>	
        	</tr>
			<tr>
				<td><div align="right">*Tiempo</div></td>
			  	<td>
					<input name="txt_tiempo" type="text" class="caja_de_texto" id="txt_tiempo" onKeyPress="return permite(event,'num_car',8);" value="" size="20" maxlength="20"/>
			  	</td>	
				<td colspan="2">	
					<table width="100%" border="0" cellpadding="3" cellspacing="3" cols="4" class="tabla_frm">
						<caption align="center" style="border:medium"  class='titulo_etiqueta'>
					 	 *Condiciones de Trabajo
					  	</caption>	 				
							<tr>
								<td align="center"class='nombres_columnas'>Ergonomia</td>
								<td align="center" class='nombres_columnas'>Luz Intensa</td>
								<td align="center" class='nombres_columnas'>Polvo</td>
								<td align="center" class='nombres_columnas'>Ruido</td>
								<td align="center" class='nombres_columnas'>Sedentarismo</td>
								<td align="center" class='nombres_columnas'>Vibraciones</td>
							</tr>
							<tr>
								<td class='nombres_filas' align='center'>
									<input type="checkbox" id="ckb_ergonomia" name="ckb_ergonomia" value="Ergonomia"/>
								</td>	
								<td class='nombres_filas' align='center'>
									<input type="checkbox" id="ckb_luz" name="ckb_luz" value="Luz Intensa"/>
								</td>
								<td class='nombres_filas' align='center'>
									<input type="checkbox" id="ckb_polvo" name="ckb_polvo" value="Polvo"/>
								</td>
								<td class='nombres_filas' align='center'>
									<input type="checkbox" id="ckb_ruido" name="ckb_ruido" value="Ruido"/>
								</td>
								<td class='nombres_filas' align='center'>
									<input type="checkbox" id="ckb_sedentarismo" name="ckb_sedentarismo" value="Sedentarismo"/>
								</td>	
								<td class='nombres_filas' align='center'>
									<input type="checkbox" id="ckb_vibracion" name="ckb_vibracion" value="Vibracion"/>
								</td>	
					  	</tr>
					</table>
				</td>			
			</tr>
			<tr>
          		<td colspan="4">
				   <div align="center">
				 	<input type="hidden" name="hdn_nomCheckBox" id="hdn_nomCheckBox" value="ckb_hisTrabajo"/>
				  	<input type="hidden" name="hdn_botonCancelar" id="hdn_botonCancelar" value=""/>
				  	<input type="hidden" name="hdn_botonSeleccionado" id="hdn_botonSeleccionado" value=""/>
			  		<input type="hidden" name="hdn_finalizar" id="hdn_finalizar" value="btn_finalizar"/>
				
					<?php
					if(isset($_SESSION['HisTrabajo'])){?>
						<input name="btn_finalizar" type="submit" class="botones" value="Finalizar" title="Finalizar el Registro del Historial de Trabajo del Empleado" 
						onMouseOver="window.status='';return true" onClick="hdn_botonSeleccionado.value='finalizar'; " />
						&nbsp;&nbsp;&nbsp;
					<?php }?>
						<input name="sbt_agregar" type="submit" class="botones" id= "sbt_agregar" value="Agregar" 
						title="Agregar Registro del Historial de Trabajo del Empleado" onMouseOver="window.status='';return true" onClick="hdn_botonSeleccionado.value='agregar'" />
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