<?php


	/**
	  * Nombre del Módulo: Aseguramiento Calidad                                                
	  * Nombre Programador: Daisy Ariana Martínez Fernández
	  * Fecha: 06/Diciembre/2011
	  * Descripción: Archivo que permite modificar el registro de los documentos
	  **/  
		//Titulo de la ventana emergente
		echo "<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>"; 
		//Inlcuimos archivo que contiene las operaciones necesarias para el registro
		include ("op_modificarListaMaestraDoc.php");
		//Incluimos archivo de conexión
		include_once("../../includes/conexion.inc");
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
			#tabla-agregarRegistro {position:absolute;left:30px;top:43px;width:650px;height:121px;z-index:12;}
			#tabla-agregarRegistro2 {position:absolute;left:30px;top:191px;width:650px;height:121px;z-index:13;}
			#calendario{position:absolute;left:280px;top:143px;width:30px;height:26px;z-index:14;}
			#calendario2{position:absolute;left:274px;top:67px;width:30px;height:26px;z-index:15;}
			#tabla-mostrarListaMaestra {position:absolute;left:30px;top:430;width:670px;height:230px;z-index:16;padding:15px;padding-top:0px; overflow:scroll}
			.Estilo1 {
				font-family: Arial, Helvetica, sans-serif;
				font-size: 12px;
			}
			-->
	    </style>
	<?php
	//Verificamos que no exista en el GET la variable noRegistro
	if(!isset($_POST['sbt_agregar'])&&!isset($_SESSION['lista_maestra'])&&isset($_GET['manual'])){
		//Ponemos los valores del GET en variables para la manipulacion en la sentencia SQL
		$id_manual=$_GET['manual'];
		$id_clau=$_GET['clausula'];
		$id_proc=$_GET['proc'];

		//Conectar a la BD de bd_produccion
		$conn = conecta("bd_aseguramiento");
								
		//Crear sentencia SQL
		$sql_stm ="SELECT * FROM (catalogo_procedimientos JOIN lista_maestra_documentos ON id_procedimiento=catalogo_procedimientos_id_procedimiento)
				   WHERE lista_maestra_documentos.manual_calidad_id_manual='$id_manual' AND catalogo_clausulas_id_clausula='$id_clau' AND id_procedimiento='$id_proc'";
	
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);
		
		//Creamos el arreglo para guardar el resultado del a consulta
		$lista_maestra = array();
			
		//Verificamos la existencia de datos
		if($datos=mysql_fetch_array($rs)){
			//Recorremos para guardar los registros en las posiciones indicadas
			do{	
				$lista_maestra[]=array("cveProc"=>$datos['id_procedimiento'], "tituloProc"=>$datos['nombre_procedimiento'],
								"fecha1"=>modFecha($datos['entrada_vigor'],1), "noRev"=>$datos['no_rev'], "noFormatoProc"=>$datos['no_forma_instructivo'],
								"nombreForma"=>$datos['nombre_forma_instructivo'], "noRevision"=>$datos['rev_forma_instructivo'],
								"fecha2"=>modFecha($datos['entrada_vigor_forma_instructivo'],1));
			}while($datos=mysql_fetch_array($rs));
			//Guardamos en la session el arreglo previamente creado
			$_SESSION["lista_maestra"]=$lista_maestra;//Cierre if($datos=mysql_fetch_array($rs)
		}//Cierre (($datos=mysql_fetch_array($rs))
	}//Cierre (!isset($_GET['sbt_agregar'])&&!isset($_SESSION['lista_maestra']))
	//Comprobamos si existe en el GET la variable noRegistro si es asi quiere decir que se presiono la imagen-boton borrar
	if(isset($_GET["noRegistro"])){
		//Si es asi liberar la sesion
		unset($_SESSION["lista_maestra"][$_GET["noRegistro"]]);
		//Verificamos que exista la session y el parametro que contiene la ubicacion del registro dentro de la sesion
		if(isset($_SESSION["lista_maestra"]) && isset($_GET["noRegistro"]))
		//Reacomodamos el Arreglo
		$_SESSION['lista_maestra'] = array_values($_SESSION['lista_maestra']);
		
		//Verificamos si exista la sesion
		if(isset($_SESSION["lista_maestra"])){
			//Si el arreglo de lista_maestra esta vacio, retirarlo de la SESSION
			if(count($_SESSION["lista_maestra"])==0){
				//Liberamos la sesion
				unset($_SESSION["lista_maestra"]);
			}
		}		
	}//Cierre if(isset($_GET["noRegistro"]))
	
	//Verificamos que exista el boton agregar para poder agregar los datos en la session
	if(isset($_POST["sbt_agregar"])){
		//Esta variable indica si el registro esta repetido o no
		$repetido = 0;
		if(isset($_SESSION['lista_maestra'])){
			//Verificar que el registro no este repetido en el Arreglo de SESSION
			foreach($_SESSION["lista_maestra"] as $ind => $registro){
				if($_POST["txt_cveProc"]==$registro["cveProc"]&&$_POST["txt_noFormatoProc"]==$registro["noFormatoProc"]){
					$repetido = 1;
					break;
				}
			}	
		}
		//Si repetido es diferente de cero se permite la inserción en el arreglo
		if($repetido!=1){		
			//Verificar que en el POST esten los datos definidos a fin de agregarlos al arreglo de Sesion
			//Si ya esta definido el arreglo $lista_maestra, entonces agregar el siguiente registro a el
			if(isset($_SESSION['lista_maestra'])){
				//Guardar los datos en el arreglo
				$lista_maestra[] = array("cveProc"=>strtoupper($_POST['txt_cveProc']), "tituloProc"=>strtoupper($_POST['txa_tituloProc']),
								"fecha1"=>strtoupper($_POST['txt_fecha']), "noRev"=>$_POST['txt_noRevision'],
								"noFormatoProc"=>strtoupper($_POST['txt_noFormatoProc']),"nombreForma"=>strtoupper($_POST['txt_nombreForma']), 
								"noRevision"=>$_POST['txt_noRevisionForma'],"fecha2"=>$_POST['txt_fecha2']);
			}//Cierre (isset($_SESSION['lista_maestra']))
			//Si no esta definido el arreglo $lista_maestra definirlo y agregar el primer registro
			else{
				$cont=0;
				//Guardar los datos en el arreglo
				$lista_maestra = array(array("cveProc"=>strtoupper($_POST['txt_cveProc']), "tituloProc"=>strtoupper($_POST['txa_tituloProc']),
								"fecha1"=>strtoupper($_POST['txt_fecha']),"noRev"=>$_POST['txt_noRevision'],
								"noFormatoProc"=>strtoupper($_POST['txt_noFormatoProc']),"nombreForma"=>strtoupper($_POST['txt_nombreForma']),
								"noRevision"=>$_POST['txt_noRevisionForma'],"fecha2"=>$_POST['txt_fecha2']));
				$_SESSION['lista_maestra'] = $lista_maestra;
			}
		}//Cierre if($repetido!=1)
		else{?>
			<script type="text/javascript" language="javascript">
				setTimeout("alert('Procedimiento <?php echo $_POST["txt_cveProc"];?> con Clausula <?php echo $_POST["txt_noFormatoProc"];?>; ya se encuentra Registrado')", 500);
			</script><?php
		}	
	}//Cierre isset($_POST["sbt_agregar"]))
		
	//Verificar que este definido el Arreglo de produccion, si es asi, lo mostramos en el formulario
	if(isset($_SESSION["lista_maestra"])){
		echo "<div id='tabla-mostrarListaMaestra' class='borde_seccion2'>";
			mostrarListaMaestra($lista_maestra);
		echo "</div>";
	}
	
	//Si existe la sesion ponemos los valores en variables para manipularlo
	if(isset($_SESSION['lista_maestra'])){
		$contador=count($_SESSION['lista_maestra'])-1;
		$clave=$_SESSION['lista_maestra'][$contador]["cveProc"];
		$titulo=$_SESSION['lista_maestra'][$contador]["tituloProc"];
		$rev=$_SESSION['lista_maestra'][$contador]["noRev"];
		$fecha=$_SESSION['lista_maestra'][$contador]["fecha1"];
	}
	else{
		$clave="";
		$titulo="";
		$rev="";
		$fecha=date("d/m/Y");
	}
	?>
	<p>&nbsp;</p>
	<form onsubmit="return valFormRegForm(this);"method="post"name="frm_agregarRegistro" id="frm_agregarRegistro" action="verModFormatos.php">
	<fieldset class="borde_seccion" id="tabla-agregarRegistro" name="tabla-agregarRegistro">
    <legend class="titulo_etiqueta">Ingrese la Informaci&oacute;n del Procedimiento</legend>
    <br />
    	<table width="634" height="89"  cellpadding="5" cellspacing="5" class="tabla_frm">
        	<tr>
          		<td width="103" height="31"><div align="right">*Clave Procedimiento </div></td>
          		<td width="96">
					<input name="txt_cveProc" id="txt_cveProc" type="text" class="caja_de_texto" size="20" onkeypress="return permite(event,'num_car', 1);" 
					value="<?php echo $clave; ?>"/>
				</td>
          		<td><div align="right">*Titulo Procedimiento </div></td>
          		<td width="222">
					<textarea name="txa_tituloProc" id="txa_tituloProc" maxlength="120" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" 
					cols="30"onkeypress="return permite(event,'num_car', 0);"><?php echo $titulo; ?></textarea>
				</td>
        	</tr>
        	<tr>
          		<td><div align="right">*No de Revisi&oacute;n </div></td>
          		<td>
					<input name="txt_noRevision" id="txt_noRevision" type="text" class="caja_de_texto" size="3" maxlength="3" 
					onkeypress="return permite(event,'num', 2);" value="<?php echo $rev; ?>"/>
				</td>
          		<td width="137"><div align="right">*Fecha</div></td>
          		<td width="222">
					<input name="txt_fecha" type="text" id="txt_fecha" size="10" maxlength="15" value="<?php echo $fecha;?>" readonly="readonly" 
					style="background-color:#999999; color:#FFFFFF"/>
				</td>
        	</tr>
    	</table>
	</fieldset>
	<fieldset class="borde_seccion" id="tabla-agregarRegistro2" name="tabla-agregarRegistro2">
	<legend class="titulo_etiqueta">Ingrese la Informaci&oacute;n de la Forma </legend>
    <br />
    	<table width="634" height="134"  cellpadding="5" cellspacing="5" class="tabla_frm">
        	<tr>
          		<td width="118" height="13"><div align="right">*No. Forma </div></td>
          		<td width="100">
					<input name="txt_noFormatoProc" id="txt_noFormatoProc" type="text" class="caja_de_texto" size="15" 
					onkeypress="return permite(event,'num_car', 1);"/>
				</td>
          		<td width="157"><div align="right">*Nombre de la Forma  </div></td>
          		<td width="198">
					<textarea name="txt_nombreForma" id="txt_nombreForma" maxlength="120" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" 
					cols="30"onkeypress="return permite(event,'num_car', 0);"></textarea>
				</td>
        	</tr>
        	<tr>
          		<td height="13"><div align="right">*No. Revisi&oacute;n de la Forma</div></td>
         		<td width="100">
					<input name="txt_noRevisionForma" id="txt_noRevisionForma" type="text" class="caja_de_texto" size="3" maxlength="3" 
					onkeypress="return permite(event,'num', 2);"/>
				</td>
          		<td width="157"><div align="right">*Entrada en Vigor </div></td>
          		<td width="198">
					<input name="txt_fecha2" type="text" id="txt_fecha2" size="10" maxlength="15" value="<?php echo date("d/m/Y");?>" readonly="readonly" 
					style="background-color:#999999; color:#FFFFFF" />
				</td>
        	</tr>
        	<tr>
          		<td colspan="3"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
        	</tr>
        	<tr>
          		<td colspan="4">
				  <div align="center">
				  		<?php if(isset($_SESSION['lista_maestra'])){?>
							<input name="btn_finalizar" type="button" class="botones" value="Finalizar" title="Finalizar al Registro" 
							onmouseover="window.status='';return true"  onclick="window.close();" />
							&nbsp;&nbsp;&nbsp;
						<?php }?>
						<input name="sbt_agregar" type="submit" class="botones" id= "sbt_agregar" value="Agregar" title="Guardar Registro De Lista Maestra" 
						onmouseover="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;
						<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onmouseover="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" class="botones" value="Cerrar" title="Regresar al Registro de la Lista Maestra" 
						onmouseover="window.status='';return true"  onclick="window.close();" />
          		</div>
			</td>
        </tr>
    </table>
	</fieldset>   
	</form>
	<div id="calendario">
		<input name="calendario" type="image" id="calendario" onclick="displayCalendar (document.frm_agregarRegistro.txt_fecha2,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png"  title="Seleccione una fecha" align="absbottom" width="25" height="25" border="0" />
	</div>
	<div id="calendario2">
	  <input name="calendario2" type="image" id="calendario2" onclick="displayCalendar (document.frm_agregarRegistro.txt_fecha,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png"  title="Seleccione una fecha" align="absbottom" width="25" height="25" border="0" />
	</div>
	