<?php

	/**
	  * Nombre del Módulo: Aseguramiento Calidad                                                
	  * Nombre Programador: Daisy Ariana Martínez Fernández
	  * Fecha: 11/Octubre/2011
	  * Descripción: Archivo que permite cargar las fotos al Servidor, validando que solo sea un registro
	  **/  
		//Titulo de la ventana emergente
		echo "<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>"; 
		//Inlcuimos archivo que contiene las operaciones necesarias para el registro
		include ("op_registrarPlanAcciones.php");
		//Archivo de validacion
		echo "<script type='text/javascript' src='../../includes/validacionAseguramiento.js'></script>";
		//Archivo de Estilo
		echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";
		//Archivo que permite la correcta ejecución de los objetos calendario contenidos en esta pantalla
		echo "<script type='text/javascript' src='../../includes/calendario.js?random=20060118'></script>";
		//Archivo que permite controlar el numero de caracteres ingresados en las cajas de texto (textarea)
		echo "<script type='text/javascript' src='../../includes/maxLength.js' ></script>";
		//Archivo que permite la correcta ejecución del objeto calendario
		echo "<link type='text/css' rel='stylesheet' href='../../includes/estiloCalendario.css?random=20051112' media='screen'>	";
		//Iniciamos la sesión para las operaciones necesarias en la pagina
		session_start();
		//Incluimos archivo para modificar fechas segun sea requerido	
		include_once("../../includes/func_fechas.php");?>
		<?php //Archivos que permtien desabilitar teclas especificas, así como desabilitar el clic derecho?>
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
			#titulo-agregar-documentos { position:absolute; left:30px; top:22px; width:200px; height:20px; z-index:11;}
			#tabla-agregarRegistro {position:absolute;left:30px;top:20px;width:650px;height:105px;z-index:12;}
			#tabla-mostrarListaMaestra {position:absolute;left:30px;top:180;width:670px;height:260px;z-index:16;padding:15px;padding-top:0px; overflow:scroll}
			-->
	    </style>
		<?php 
		 if(isset($_GET['idPlan'])&&!isset($_SESSION['referencias'])){
			//Importamos archivo para realizar la conexion con la BD
			include_once("../../includes/conexion.inc");
		
			//Incluimos archivo para modificar fechas
			include_once("../../includes/func_fechas.php");
		
			//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
			include_once("../../includes/op_operacionesBD.php");
		
			//Realizar la conexion a la BD de Aseguramiento
			$conn = conecta("bd_aseguramiento");
		
			//Verificamos que valor tiene la caja de texto que indica si fue registrado el complemento del 
			//Plan de Acciones
			if($_GET['elemento']=='SI'){?>
				<script language="javascript" type="text/javascript">
					setTimeout("alert('Las Referencias Mostradas Ya Cuentan Con Registro por Parte del Departamento; No se Recomienda Agregar, Modificar o Eliminar Referencias; Ya Que Se Eliminara Dicho Registro')",500);
				</script>		
			<?php
			} //Guardamos el id de la Alerta en la siguiente Variable
			$idPlan = $_GET['idPlan'];
			
			//Arreglo para guardar las referencias
			$referencias=array();
		
			//Creamos la sentencia SQL
			$stm_sql ="SELECT * FROM (referencias JOIN detalle_referencias ON id_referencia=referencias_id_referencia) 
				WHERE referencias.plan_acciones_id_plan_acciones='$idPlan'";
					
			//Ejecutamos la sentencia SQL
			$rs = mysql_query($stm_sql);
		
			$cont=1;
			//contador que nos permitira controlar el numero de posiciones del arreglo para realizar la insecion
			//Si la consulta trajo datos creamos la tabla para mostrarlos
			if($datos = mysql_fetch_array($rs)){		
				do{	
					$referencias[]=array("no_referencia"=>$cont,"clave"=>$datos['no_referencia'], "referencia"=>$datos['desv_obs_exp']);
					$cont++;
				}while($datos=mysql_fetch_array($rs)); 	
			}
		
			//Guardamos en la session el arreglo previamente creado
			$_SESSION["referencias"]=$referencias;
			//Cerrar la conexion con la BD
			mysql_close($conn);
		}?>
	<?php
		//Comprobamos si existe en el GET la variable noRegistro si es asi quiere decir que se presiono la imagen-boton borrar
		if(isset($_GET["noRegistro"])){
			//Si es asi liberar la sesion
			unset($_SESSION["referencias"][$_GET["noRegistro"]]);
			//Verificamos que exista la session y el parametro que contiene la ubicacion del registro dentro de la sesion
			if(isset($_SESSION["referencias"]) && isset($_GET["noRegistro"]))
				//Reacomodamos el Arreglo
				$_SESSION['referencias'] = array_values($_SESSION['referencias']);
			
			//Verificamos si exista la sesion
			if(isset($_SESSION["referencias"])){
				//Si el arreglo de lista_maestra esta vacio, retirarlo de la SESSION
				if(count($_SESSION["referencias"])==0){
					//Liberamos la sesion
					unset($_SESSION["referencias"]);
				}
			}
			
		}//Cierre if(isset($_GET["noRegistro"]))
		
		//Verificamos que exista el boton agregar para poder agregar los datos en la session
		if(isset($_POST["sbt_agregar"])){
			//Verificar que en el POST esten los datos definidos a fin de agregarlos al arreglo de Sesion
			//Si ya esta definido el arreglo $lista_maestra, entonces agregar el siguiente registro a el
			if(isset($_SESSION['referencias'])){
				$cont=count($_SESSION['referencias'])+1;
				//Guardar los datos en el arreglo
				$referencias[] = array("no_referencia"=>$cont,"clave"=>strtoupper($_POST['txt_cveRef']), "referencia"=>strtoupper($_POST['txa_referencia']));
			}
			//Si no esta definido el arreglo $lista_maestra definirlo y agregar el primer registro
			else{
				$cont=1;
				//Guardar los datos en el arreglo
				$referencias = array(array("no_referencia"=>$cont,"clave"=>strtoupper($_POST['txt_cveRef']), "referencia"=>strtoupper($_POST['txa_referencia'])));
				$_SESSION['referencias'] = $referencias;
			}
		}
		
		//Verificar que este definido el Arreglo de produccion, si es asi, lo mostramos en el formulario
		if (isset($_SESSION["referencias"])){
			echo "<div id='tabla-mostrarListaMaestra' class='borde_seccion2'>";
				mostrarReferencias($referencias);
			echo "</div>";
		}
		if(isset($_SESSION['referencias'])){
			$contador=count($_SESSION['referencias']);
			if($contador>1){
				$contador=count($_SESSION['referencias'])-1;
				$clave=$_SESSION['referencias'][$contador]["clave"];
			}
			else{
				$clave="";
			}
		}
		else{
			$clave="";
		}
	?>
	<p>&nbsp;</p>
	<form onsubmit="return valFormRegRef(this);"method="post"name="frm_agregarRegistro" id="frm_agregarRegistro" action="verRegReferencias.php">
	<fieldset class="borde_seccion" id="tabla-agregarRegistro" name="tabla-agregarRegistro">
    <legend class="titulo_etiqueta">Ingresar Referencias </legend>
    <br/>
    	<table width="634" height="43"  cellpadding="5" cellspacing="5" class="tabla_frm">
        	<tr>
          		<td width="120" height="31"><div align="right">*No . Referencia </div></td>
          		<td width="120">
					<input name="txt_cveRef" id="txt_cveRef" type="text" class="caja_de_texto" size="10"  maxlength="10" 
					onkeypress="return permite(event,'num_car', 1);" value="<?php echo $clave; ?>"/>
				</td>
          		<td width="108"><div align="right">*Desviaci&oacute;n </div></td>
          		<td width="219">
					<textarea name="txa_referencia" id="txa_referencia" maxlength="250" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" cols="30"
					onkeypress="return permite(event,'num_car', 0);"></textarea>
				</td>
        	</tr>
			<tr>
				<td colspan="4">
					<div align="center">
				  		<?php if(isset($_SESSION['referencias'])){?>
							<input name="btn_finalizar" type="button" class="botones" value="Finalizar" title="Finalizar al Registro" 
							onmouseover="window.status='';return true"  onclick="window.close();" />
							&nbsp;&nbsp;&nbsp;
						<?php }?>
							<input name="sbt_agregar" type="submit" class="botones" id= "sbt_agregar" value="Agregar" 
							title="Agregar Registro" onmouseover="window.status='';return true"/>
							&nbsp;&nbsp;&nbsp;
							<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" 
							onmouseover="window.status='';return true"/>
							&nbsp;&nbsp;&nbsp;
							<input name="btn_cancelar" type="button" class="botones" value="Cerrar" title="Regresar al Registro del Plan de Acciones" 
							onmouseover="window.status='';return true"  onclick="window.close();" />
          			</div>
				</td>
        	</tr>
    	</table>
	</fieldset>
	</form>
	