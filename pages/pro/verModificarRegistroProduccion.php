<?php
	/**
	  * Nombre del Módulo: Laboratorio                                               
	  * Nombre Programador: Daisy Ariana Martínez Fernández
	  * Fecha: 21/Julio/2011
	  * Descripción: Este archivo contiene funciones para Ver la produccion cuando se selecciona el destino colados 
	  **/  

	//Inlcuimos archivo que contiene las operaciones necesarias para el registro
	include ("op_modificarProduccion.php");
	//Incluimos archivo de conexión
	include_once("../../includes/conexion.inc");
	//Funcion que permite modificar las fechas
	include_once("../../includes/func_fechas.php");
	//Archivo de Estilo
	echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";
	//Archivo de validacion
	echo "<script type='text/javascript' src='../../includes/validacionProduccion.js'></script>";
	//Archivo para validar el numero de caracteres que puede contener un area de texto 
	echo "<script type='text/javascript' src='../../includes/maxLength.js'></script>";
	//Archivo que permite dar formato tipo float a los numeros
	echo "<script type='text/javascript' src='../../includes/formatoNumeros.js'></script>";
	//Titulo de la ventana emergente
	echo "<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>";?>
	<script type="text/javascript" src="../../includes/disableKeys.js"></script>
	<script type="text/javascript" language="javascript">
		<!--
		//Funcion para desabilitar el clic derecho en la ventana pop-up
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
			#tabla-mostrarProduccion {position:absolute;left:30px;top:330;width:820px;height:250px;z-index:12;padding:15px;padding-top:0px; overflow:scroll}
		-->
    </style>
	<?php 
		//Iniciamos la sesion; esto porque en una ventana pop-up no se tiene incluido el head-menu por lo tanto no permite inciar la sesion automaticamente
		session_start();	
		//Verificamos que exista el boton finalizar
		if(isset($_POST["sbt_finalizar"])){
			//window.opener.document.getElementById("txt_volProducido").value=<?php echo $_POST["txt_volMaximo"]; //carga el valor a la caja de texto deseada; 
			//tomando en cuenta  la ventana de la cual fue lanzada(abierta)
			//window.opener.focus(); Enfoca la ventana de apertura
			//window.close(); Cierra el pop-up
			?>
			<script type="text/javascript" language="javascript">
				window.opener.document.getElementById("txt_volProducido").value="<?php echo $_POST["txt_volMaximo"]?>"; 				
				window.opener.focus();
				window.close();
			</script>
		<?php	
		}
		
		//Verificamos que no exista en el GET la variable noRegistro
		if(!isset($_SESSION["produccion"])&&!isset($_POST["sbt_agregar"])){
			//Conectar a la BD de bd_produccion
				$conn = conecta("bd_produccion");
				//Guardamos la fecha que viene en el GET en una variable para realizar las modificaciones necesarias
				$fecha=modFecha($_GET["fecha"],3);
				$destino=$_GET['destino'];
				
				//Crear sentencia SQL
				$sql_stm ="SELECT * FROM detalle_colados WHERE bitacora_produccion_fecha = '$fecha' AND catalogo_destino_id_destino = '$destino'";
		
				//Ejecutar la sentencia previamente creada
				$rs = mysql_query($sql_stm);
			
				//Creamos el arreglo para guardar el resultado del a consulta
				$datosModificar = array();
				
				//Verificamos la existencia de datos
				if($datos=mysql_fetch_array($rs)){
					//Recorremos para guardar los registros en las posiciones indicadas
					do{	
						$datosModificar[]=array("cliente"=>$datos['cliente'], "volumen"=>$datos['volumen'], "colado"=>$datos['colado'], 
												"observaciones"=>$datos['observaciones'], "factura"=>$datos['factura'], "tipo"=>$datos['tipo_colado'], "remision"=>$datos['no_remision'],
												"pagado"=>$datos['pagado'],"costo"=>$datos['costo']);
					}while($datos=mysql_fetch_array($rs));
					//Guardamos en la session el arreglo previamente creado
					$_SESSION["produccion"]=$datosModificar;//Cierre if($datos=mysql_fetch_array($rs)
				}
			}//Cierre if(!isset($_GET["noRegistro"]))
		
		//Comprobamos si existe en el GET la variable noRegistro si es asi quiere decir que se presiono la imagen-boton borrar
		if(isset($_GET["noRegistro"])&&!isset($_POST["txt_cliente"])){
			//Si es asi liberar la sesion
			unset($_SESSION["produccion"][$_GET["noRegistro"]]);
			//Verificamos que exista la session y el parametro que contiene la ubicacion del registro dentro de la sesion
			if(isset($_SESSION["produccion"])&&isset($_GET["noRegistro"]))
				//Reacomodamos el Arreglo
				$_SESSION['produccion'] = array_values($_SESSION['produccion']);
		
			//Verificamos si exista la sesion
			if(isset($_SESSION["produccion"])){
				//Si el arreglo de produccion esta vacio, retirarlo de la SESSION
				if(count($_SESSION["produccion"])==0)
					//Liberamos la sesion
					unset($_SESSION["produccion"]);
			}
		}//Cierre if(isset($_GET["noRegistro"]))		
		
		//Verificar que en el POST se haya presionado el boton agregar
		if(isset($_POST["sbt_agregar"])){
			//Verificar que en el POST esten los datos definidos a fin de agregarlos al arreglo de Sesion
			//Si ya esta definido el arreglo $produccion, entonces agregar el siguiente registro a el
			if(isset($_SESSION['produccion'])){
				//Guardar los datos en el arreglo
				$produccion[] = array("cliente"=>strtoupper($_POST['txt_cliente']), "volumen"=>str_replace(",","",$_POST['txt_volumen']), "colado"=>strtoupper($_POST['txa_colado']),
								"observaciones"=>strtoupper($_POST['txa_observaciones']), 
								"factura"=>strtoupper($_POST['txt_factura']), "tipo"=>$_POST['cmb_tipo'], "remision"=>$_POST['txt_noRemision'], 
								"pagado"=>$_POST["cmb_pagado"], "costo"=>str_replace(",","",$_POST["txt_costo"]));
			}
			//Si no esta definido el arreglo $produccion definirlo y agregar el primer registro
			else{
				//Guardar los datos en el arreglo
				$produccion = array(array("cliente"=>strtoupper($_POST['txt_cliente']), "volumen"=>str_replace(",","",$_POST['txt_volumen']), 
							  "colado"=>strtoupper($_POST['txa_colado']), "observaciones"=>strtoupper($_POST['txa_observaciones']), 
							  "factura"=>strtoupper($_POST['txt_factura']),"tipo"=>$_POST['cmb_tipo'], "remision"=>$_POST['txt_noRemision'],
							  "pagado"=>$_POST["cmb_pagado"], "costo"=>str_replace(",","",$_POST["txt_costo"])));
				$_SESSION['produccion'] = $produccion;
			}
		}
		
		//Calculamos volumen Maxicomo con la funcion icalcularVolumen que se encuentra en el op correspondiente
		if(isset($_POST["txt_volumen"]))
			$volMaximo=calcularVolumen();
		if(!isset($_POST["txt_volumen"])){
			//Conectar a la BD de bd_produccion
			$conn = conecta("bd_produccion");
			$fecha=modFecha($_GET["fecha"],3);
			//De lo contrario lo ponemos comoc vacio; se ah entrado al formulario por primera vs
			$volumenes=mysql_fetch_array(mysql_query("SELECT SUM(volumen) AS volumen FROM detalle_colados WHERE bitacora_produccion_fecha='$fecha'"));
			if($volumenes!="")
				$volMaximo=$volumenes["volumen"];
			
		}
		if(!isset($_SESSION["produccion"])&&isset($_GET["noRegistro"]))
				$volMaximo=0;
		
		
		//Verificamos que exista el arreglo produccion para asignarle el valor acumulado
		if(isset($_SESSION["produccion"]))
			$volMaximo=calcularVolumen();
			
		if(isset($_SESSION["produccion"])||!isset($_GET["noRegistro"])&&!isset($_SESSION["produccion"]))
			echo "<div id='tabla-mostrarProduccion' class='borde_seccion2'>";
			
		//Verificar que este definido el Arreglo de produccion, si es asi, lo mostramos en el formulario
		if (isset($_SESSION["produccion"])){	
			mostrarProduccionVentana($_SESSION['produccion'], $_GET["fecha"],$_GET["volumen"], $_GET["concepto"], $_GET["destino"]);	
		}
		
		//Verificar que no se encuentre definido el arreglo de sesion asi como verificar que exista en el get la variable noRegistro indicando que se presiono 
		//la imagen-boton borrar
		if(!isset($_SESSION["produccion"])&&isset($_GET["noRegistro"])){?>
			<p class="msje_correcto" align="center">No Hay Datos Registrados</p>
			<?php	
		}
		//Verificar que no se encuentre la variable noRegistro lo que nos dice que no se ah presionado el boton-imagen borrar y que no exista la sesion; esto para 
		//mostrar los datos registrados en la bd
		if(!isset($_GET["noRegistro"])&&!isset($_SESSION["produccion"])){
			mostrarProduccionBD(modFecha($_GET["fecha"],3), $_GET["concepto"]);
		}
		echo "</div>";
		
		?>
		
	<form name="frm_produccion" method="post"  id="frm_produccion" onsubmit="return valFormVerProduccion(this); " a
	ction="verModificarRegistroProduccion.php?fecha=<?php echo $_GET["fecha"]?>&volumen=<?php echo $_GET["volumen"]?>&concepto=<?php echo $_GET["concepto"]?>">
	<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
		  <td width="120"><div align="right">Volumen M&aacute;ximo </div></td>
			<td colspan="3">
		  		<input type="text" name="txt_volMaximo" id="txt_volMaximo"   value="<?php echo number_format($volMaximo,2,".",",");?>"maxlength="10" size="10" class="caja_de_texto"  
				readonly="readonly" style="background-color:#999999; color:#FFFFFF"  /> m&sup3;			</td>
			<td width="130"><div align="right">Destino Producci&oacute;n </div></td>
		  	<td width="280" >
				<input name="txt_destinoProduccion" id="txt_destinoProduccion" readonly="readonly" type="text" value="COLADOS" size="10"  width="90" 
				style="background-color:#999999; color:#FFFFFF"/>			</td>
		</tr>
		<tr>
		  	<td width="120"><div align="right">*Cliente</div></td>
		  	<td colspan="3">
				<input type="text" name="txt_cliente" id="txt_cliente" maxlength="60" size="40" class="caja_de_texto" value="" 
				onkeypress="return permite(event,'car',1);"/>			</td>
			<td rowspan="2"><div align="right">Observaciones</div></td>
			<td rowspan="2">
				<textarea name="txa_observaciones" id="txa_observaciones" class="caja_de_texto" cols="40" rows="3" maxlength="120"
				onkeyup="return ismaxlength(this)" onkeypress="return permite(event,'num_car',0);"></textarea>			</td>
		</tr>
		<tr>
		  	<td width="120"><div align="right">*Volumen</div></td>
		  	<td colspan="3">
				<input type="text" name="txt_volumen" id="txt_volumen" maxlength="10" size="10" class="caja_de_texto" 
				onchange="formatCurrency(value,'txt_volumen');" onkeypress="return permite(event,'num',2);"/>	m&sup3;			</td>
	  	</tr>
		<tr>
			<td><div align="right">*Factura</div></td>
		  	<td width="91">
				<select name="cmb_factura" id="cmb_factura" size="1" class="combo_box" onchange="habilitarFactura(this);">
					<option value="">Factura</option>
					<option value="SI">SI</option>
					<option value="NO">NO</option>
				</select>		  </td>
	        <td width="83">No. Factura</td>
          	<td width="65">
		  		<input type="text" name="txt_factura" id="txt_factura" maxlength="10" size="10" readonly="readonly" class="caja_de_texto" value="N/A" 
				style="background-color:#999999; color:#FFFFFF" onkeypress="return permite(event,'num_car',1);"/>			</td>
			<td rowspan="2"><div align="right">*Colado</div></td>
			<td rowspan="2">
				<textarea name="txa_colado" id="txa_colado" class="caja_de_texto" cols="40" rows="3" maxlength="120" onkeyup="return ismaxlength(this)"
				onkeypress="return permite(event,'num_car',0);"></textarea>			</td>
	  	</tr>
		<tr>
		 <td><div align="right">*Tipo</div></td>
		 	<td colspan="3">
		  		<select name="cmb_tipo" id="cmb_tipo" size="1" class="combo_box">
					<option value="">Tipo</option>
					<option value="BOMBEO">BOMBEO</option>
					<option value="TIRO DIRECTO">TIRO DIRECTO</option>
				</select></td>
		</tr>
		<tr>
			<td><div align="right">*N&uacute;mero Remisi&oacute;n </div></td>
			<td>
				<input type="text" name="txt_noRemision" id="txt_noRemision" maxlength="35" size="20" class="caja_de_texto" value=""
				onkeypress="return permite(event,'num_car',1);"/>
			</td>
			<td><div align="right">*Pagado</div></td>
			<td>
				<select name="cmb_pagado" id="cmb_pagado" class="combo_box">
					<option value="NO" selected="selected">NO</option>
					<option value="SI">SI</option>
				</select>
			</td>
			<td><div align="right">Costo</div></td>
			<td>$<input type="text" name="txt_costo" id="txt_costo" maxlength="10" size="10" class="caja_de_texto" value="0.00" 
				onchange="formatCurrency(value,'txt_costo');" onkeypress="return permite(event,'num',2);"/>
			</td>
		</tr>
		
		<tr><td colspan="7"><strong>* Datos marcados con asterisco son <u>obligatorios</u></strong></td></tr>
		<tr>
			<td colspan="6">
				<div align="center">
					<input type="hidden" name="hdn_botonSeleccionado" id="hdn_botonSeleccionado" value="" />
					<?php if(isset($_SESSION['produccion'])){?>
						<input name="sbt_finalizar" type="submit" class="botones" id="sbt_finalizar"  value="Finalizar" title="Finalizar Agregado de Registros" 
						onmouseover="window.status='';return true"  onclick="hdn_botonSeleccionado.value='sbt_finalizar'" />   
						&nbsp;&nbsp;&nbsp;
					<?php }?>
					<input name="sbt_agregar" type="submit" class="botones" id="sbt_agregar"  value="Agregar" title="Agregar Registros" 
					onmouseover="window.status='';return true" onclick="hdn_botonSeleccionado.value='sbt_agregar'"/>   
					&nbsp;&nbsp;&nbsp;
					<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onclick="restablecerVentEmg();" 
					onMouseOver="window.status='';return true"/>    	    	
				&nbsp;&nbsp;&nbsp;</div>			</td>
		</tr>
	</table>
	</form>	
	
</body>
</html>