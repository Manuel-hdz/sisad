<?php

	/**
	  * Nombre del Módulo: Seguridad Industrial                                                
	  * Nombre Programador: Daisy Ariana Martínez Fernández
	  * Fecha: 05/Marzo/2012
	  * Descripción: Archivo que permite almacenar las acciones preventivas y correctivas
	  **/  
		//Titulo de la ventana emergente
		echo "<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>"; 
		//Inlcuimos archivo que contiene las operaciones necesarias para el registro
		include ("op_registrarActaIncidentesAccidentes.php");
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
			#tabla-mostrarRegistros {position:absolute;left:30px;top:230;width:725px;height:220px;z-index:16;padding:15px;padding-top:0px; overflow:scroll}
			#calendario{position:absolute;left:267px;top:118px;width:30px;height:26px;z-index:14;}
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
			unset($_SESSION["accionesPrevCorr"][$_GET["noRegistro"]]);
			//Verificamos que exista la session y el parametro que contiene la ubicacion del registro dentro de la sesion
			if(isset($_SESSION["accionesPrevCorr"]) && isset($_GET["noRegistro"]))
				//Reacomodamos el Arreglo
				$_SESSION['accionesPrevCorr'] = array_values($_SESSION['accionesPrevCorr']);
			
			//Verificamos si exista la sesion
			if(isset($_SESSION["accionesPrevCorr"])){
				//Si el arreglo de lista_maestra esta vacio, retirarlo de la SESSION
				if(count($_SESSION["accionesPrevCorr"])==0){
					//Liberamos la sesion
					unset($_SESSION["accionesPrevCorr"]);
				}
			}
			
		}//Cierre if(isset($_GET["noRegistro"]))
		//Verificamos que exista el boton agregar para poder agregar los datos en la session
		if(isset($_POST["sbt_agregar"])){
			//Esta variable indica si el registro esta repetido o no
			$repetido = 0;
			if(isset($_SESSION['accionesPrevCorr'])){
				//Verificar que el registro no este repetido en el Arreglo de SESSION
				foreach($_SESSION["accionesPrevCorr"] as $ind => $registro){
					if(strtoupper($_POST["txa_accPrevCorr"])==$registro["accPrevCorr"]){
						$repetido = 1;
						break;
					}
				}	
			}
			if($repetido!=1){		
				//Verificar que en el POST esten los datos definidos a fin de agregarlos al arreglo de Sesion
				//Si ya esta definido el arreglo $lista_maestra, entonces agregar el siguiente registro a el
				if(isset($_SESSION['accionesPrevCorr'])){
					//Guardar los datos en el arreglo
					$accionesPrevCorr[] = array("noAcc"=>$_POST['txt_noAcc'],"fechAcc"=>$_POST['txt_fechaAcc'],"accPrevCorr"=>strtoupper($_POST['txa_accPrevCorr']),
						"responsable"=>strtoupper($_POST['txt_responsable']));
				}
				//Si no esta definido el arreglo $lista_maestra definirlo y agregar el primer registro
				else{
					$cont=0;
					//Guardar los datos en el arreglo
					$accionesPrevCorr = array( array("noAcc"=>$_POST['txt_noAcc'],"fechAcc"=>$_POST['txt_fechaAcc'],"accPrevCorr"=>strtoupper($_POST['txa_accPrevCorr']),
						"responsable"=>strtoupper($_POST['txt_responsable'])));
					$_SESSION['accionesPrevCorr'] = $accionesPrevCorr;
				}
			}
			else{?>
				<script type="text/javascript" language="javascript">
					setTimeout("alert('La Acción Ingresada <?php echo strtoupper($_POST["txa_accPrevCorr"]);?> ya se encuentra Registrado')", 500);
				</script><?php
			}	
		}
		
		//Verificar que este definido el Arreglo de produccion, si es asi, lo mostramos en el formulario
		if (isset($_SESSION["accionesPrevCorr"])){
			echo "<div id='tabla-mostrarRegistros' class='borde_seccion2'>";
				mostrarAccionesPrevCorr($accionesPrevCorr);
			echo "</div>";
		}
		if(!isset($_SESSION['accionesPrevCorr'])){
			$numero =1;
		}
		else{
			$numero = count($_SESSION['accionesPrevCorr'])+1;
		}
	
	?>
	<body onUnload="cambiarBoton();">
	<p>&nbsp;</p>
	<form  onSubmit="return valFormRegAccPrevCorr(this);" method="post"name="frm_agregarRegistro" id="frm_agregarRegistro" action="verRegAccionesPrevCorr.php">
	<fieldset class="borde_seccion" id="tabla-agregarRegistro" name="tabla-agregarRegistro">
    <legend class="titulo_etiqueta">Ingresar Acciones Preventivas/Correctivas </legend>
    <br />
    	<table width="689" height="43"  cellpadding="5" cellspacing="5" class="tabla_frm">
        	<tr>
          		<td width="121" height="-7"><label></label>          		  <div align="right">*No. Acci&oacute;n </div></td>
          		<td width="61">
					<input type="text" name="txt_noAcc" id="txt_noAcc" value="<?php echo $numero;?>" readonly="readonly" size="5" maxlength="10"
				 	style="background-color:#999999; color:#FFFFFF"/></td>
          		<td width="167"><div align="right">*Acciones Preventivas/Correctivas  </div></td>
       	      	<td width="273"><textarea name="txa_accPrevCorr" id="txa_accPrev" maxlength="120" onKeyUp="return ismaxlength(this);" class="caja_de_texto" rows="3"
				 	cols="40" onKeyPress="return permite(event,'num_car', 2);"></textarea></td>
        	</tr>
        	<tr>
        		<td height="-2"><div align="right">*Fecha Accidente </div></td>
        	  	<td height="-2">
					<input name="txt_fechaAcc" type="text" id="txt_fechaAcc" size="10" maxlength="15" value="<?php echo date("d/m/Y");?>" 
			  		readonly="readonly" style="background-color:#999999; color:#FFFFFF"/>				</td>
        	  	<td><div align="right">*Responsable</div></td>
      	      	<td><input type="text" name="txt_responsable" id="txt_responsable" value=""  size="45" maxlength="60" class="caja_de_texto" 
					onKeyPress="return permite(event,'num_car', 2);"/></td>
       	  	</tr>
			<tr>
          		<td colspan="4">
					<div align="center">
					 <?php
				  		if(isset($_SESSION['accionesPrevCorr'])){?>
							<input name="btn_finalizar" type="button" class="botones" value="Finalizar" title="Finalizar Registro de Acciones Preventivas/Correctivas" 
							onMouseOver="window.status='';return true" onClick="window.close();" />
							&nbsp;&nbsp;&nbsp;
						<?php }?>
						<input name="sbt_agregar" type="submit" class="botones" id= "sbt_agregar" value="Agregar" 
						title="Agregar Registro de Acciones Preventivas/Correctivas"onMouseOver="window.status='';return true" />
						&nbsp;&nbsp;&nbsp;
						<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onMouseOver="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" class="botones" value="Cerrar" title="Cerrar y Regresar al Registro del Acta de Incidentes/Accidentes" 
						onMouseOver="window.status='';return true" onClick="window.close();" />
          			</div>				</td>
        	</tr>
    	</table>
	</fieldset>
	</form>
	<div id="calendario">
		<input name="calendario" type="image" id="calendario2" onClick="displayCalendar (document.frm_agregarRegistro.txt_fechaAcc,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png"  title="Seleccione una fecha" align="absbottom" width="25" height="25" border="0" />
	</div>
	<script language="javascript" type="text/javascript">
		<?php if(isset($_SESSION['accionesPrevCorr'])){?>
				function cambiarBoton(){
					window.opener.document.getElementById("sbt_continuar").disabled=false;
				}<?php 
			}else{?>
				function cambiarBoton(){
					window.opener.document.getElementById("sbt_continuar").disabled=true;
				}			
			<?php }?>
	</script>
</body>