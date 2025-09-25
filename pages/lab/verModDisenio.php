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
<?php

	/**
	  * Nombre del Módulo: Laboratorio                                               
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 27/02/2012
	  * Descripción: Este archivo contiene el formulario para modificar el diseño de la mezcla a la cual se le va a registrar el Rendiemiento
	  **/
	   
	//Verificamos si viene el id de la mezcla en la URL ($_GET); de ser asi llamar la funcion mostrarMateriales()
	if(isset($_GET['idMezcla'])){		
		//Iniciar la SESSION para accesar a los datos guradados en ella
		session_start();
		mostrarMateriales($_GET['idMezcla']);
	}
	
	
	//Si esta definido el boton de Modificar, guardar los datos del Diseño en la SESSION
	if(isset($_POST["sbt_modificar"])){
		//Iniciar la SESSION para almcenar los datos en ella
		session_start();
		include ("../../includes/op_operacionesBD.php");
		
		
		//Obtener la cantidad de registros(materiales) desplegados
		$tam = $_POST["hdn_tam"];
		$registroDisenio = array();
		
		//Calcular el Peso Volumen Teorico del Diseño modificado y el valor de Cb(Cantidad de Cemento)
		$pesoVolTeorico = 0.00;
		$Cb = 0.00;
		//Guardar cada prueba seleccionada en el arreglo registroPruebas[]
		for($i=1;$i<=$tam;$i++){
			//Al momento de agregar los componenetes Eliminar la posible como(,) y redondear el numero hasta 5 decimales
			$cantMat = round(str_replace(",","",$_POST["txt_matCant$i"]),5);
			//Guardar cada uno de los datos en el arreglo
			$registroDisenio[] = array("idMat"=>$_POST["hdn_claveMat$i"],"cantMat"=>$cantMat,"unidad"=>$_POST["hdn_unidadMed$i"]);			
			$pesoVolTeorico += floatval($cantMat);
			
			//Verificar si esta presente el Material CEMENTO (en el Catalogo de materiales del almacen tiene la clave MATGT005) y darle formato
			if($_POST["hdn_claveMat$i"]=="MATGT005"){
				$cantDecimales = contarDecimales($cantMat);
				$Cb = number_format($cantMat, $cantDecimales,".",",");	
			}
		}//Cierre for($i=1;$i<=$tam;$i++)
		
		//Darle formato al Peso Volumen Teorico
		//Obtener la cantidad de decimales del numero renodeado de 5 digitos hacia abajo
		$cantDecimales = contarDecimales(round($pesoVolTeorico,5));
		$pesoVolTeorico = number_format($pesoVolTeorico, $cantDecimales,".",",");
		
		//Si la bandera vale 'si' vaciar los datos prexistentes en la SESSION, para guardar los nuevos
		if ($_POST["hdn_band"]=="si")
			unset($_SESSION["datosDisenio"]);
		
		//Guardar las pruebas seleccionadas en la SESSION
		$_SESSION["datosDisenio"] = $registroDisenio;?>
		
		<script type="text/javascript" language="javascript">		
			//Resetear el Formulario para obligar al Usuario a re introducirlos para que el programa vuelva a realziar los calculos
			window.opener.document.frm_registrarResultadoRendimiento2.reset();						
			
			window.opener.document.getElementById("txt_pvolTeoricoRend").value = "<?php echo $pesoVolTeorico; ?>";
			window.opener.document.getElementById("txt_pvolTeoricoAire").value = "<?php echo $pesoVolTeorico; ?>";
			window.opener.document.getElementById("txt_cb").value = "<?php echo $Cb; ?>";			
			//Indicar que el Diseño de la Mezcla fue modificado
			window.opener.document.getElementById("hdn_disenioMod").value = "si";
												
			window.close();
		</script><?php
	}//Cierre if(isset($_POST["sbt_asignar"]))
	
	
		
	//Función que permite mostrar las Pruebas
	function mostrarMateriales($idMezcla){	
		//Incluimos arrchivo de conexion
		include ("../../includes/conexion.inc");
		//Incluimos el archivo para modificar las fechas para la consulta
		include ("../../includes/func_fechas.php");
		include ("../../includes/op_operacionesBD.php");
		//Hoja de estilo para la ventana emergente
		echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css'/>";
		//Archivo de validacion para indicar pruebas seleccionadas
		echo "<script type='text/javascript' src='../../includes/validacionLaboratorio.js'></script>";
		//Archivo para formatera los datos numericos introducidos en el formulario
		echo "<script type='text/javascript' src='../../includes/formatoNumeros.js'></script>";
				
		//Conectar a la BD de Laboratorio
		$conn = conecta("bd_laboratorio");
						
		//Realizar la consulta para obtener el detalle de la mezcla seleccionada  
		$stm_sql = "SELECT * FROM materiales_de_mezclas WHERE mezclas_id_mezcla = '$idMezcla'";
						
		//Ejecutar la consulta y dibujar la tabla para mostrar el detalle
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){?>
			<br><br>								
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
			<form onsubmit="return valFormMostrarMaterialesMezcla(this);" name="frm_mostrarMaterialesMezcla" method="post" action="verModDisenio.php">
			<table cellpadding="5" width="100%" class="tabla_frm">
				<caption class="titulo_etiqueta">DETALLE DE MATERIALES DE LA MEZCLA <?php echo $idMezcla; ?></caption>
				<tr>
					<td class="nombres_columnas" align="center">NO.</td>
					<td class="nombres_columnas" align="center">NOMBRE DEL MATERIAL</td>
					<td class="nombres_columnas" align="center">PESO UNITARIO</td>
					<td class="nombres_columnas" align="center">UNIDAD DE MEDIDA</td>
					<td class="nombres_columnas" align="center">CLASIFICACI&Oacute;N</td>		
				</tr><?php
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				//Recuperar datos adicionales del los materiales de la mezcla seleccionada
				$nomMaterial = obtenerDato('bd_almacen', 'materiales', 'nom_material','id_material', $datos['catalogo_materiales_id_material']);				
				$categoriaMat = obtenerDato('bd_almacen', 'materiales', 'linea_articulo','id_material', $datos['catalogo_materiales_id_material']);
				
				//Guardar el valor del numero de registro y el nombre del material para el manejo de alertas por falta de datos ?>				
				<input type="hidden" name="hdn_noReg" id="hdn_noReg" value="<?php echo $cont;?>"/>
				<input type="hidden" name="hdn_claveMat<?php echo $cont;?>" id="hdn_claveMat<?php echo $cont;?>" value="<?php echo $datos['catalogo_materiales_id_material'];?>"/>
				<input type="hidden" name="hdn_nomMaterial<?php echo $cont;?>" id="hdn_nomMaterial<?php echo $cont;?>" value="<?php echo $nomMaterial;?>"/>
				<input type="hidden" name="hdn_unidadMed<?php echo $cont;?>" id="hdn_unidadMed<?php echo $cont;?>" value="KG" />
								
				<tr>	
					<td class="<?php echo $nom_clase; ?>" align="center"><?php echo $cont; ?></td>
					<td class="<?php echo $nom_clase; ?>" align="center"><?php echo $nomMaterial?></td>
					<td class="<?php echo $nom_clase; ?>" align="center"><?php
						//Obtener la cantidad de decimales del numero renodeado de 5 digitos hacia abajo
						$cantDecimales = contarDecimales(round($datos['cantidad'],5));?>
					
						<input type="text" name="txt_matCant<?php echo $cont;?>" id="txt_matCant<?php echo $cont;?>" size="10" maxlength="15" 
						onkeypress="return permite(event,'num', 2);" class="caja_de_num" 
						value="<?php echo number_format($datos['cantidad'], $cantDecimales,".",",");?>"
						onchange="formatNumDecimalLab(value,'txt_matCant<?php echo $cont;?>'); sumarCantMateriales();" />
					</td>
			    	<td class="<?php echo $nom_clase; ?>" align="center">KG</td>
					<td class="<?php echo $nom_clase; ?>" align="center"><?php echo $categoriaMat; ?></td>
				</tr><?php				
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));?>
				<tr>
					<td class="<?php echo $nom_clase; ?>">&nbsp;</td>
					<td align="right" class="<?php echo $nom_clase; ?>"><div class="titulo_etiqueta">TOTAL</div></td>
					<td class="<?php echo $nom_clase; ?>" align="center"><?php 						
						//Conectar a la BD de Laboratorio
						$conn = conecta("bd_laboratorio");
						$stm_sql2 = "SELECT SUM(cantidad) as total FROM materiales_de_mezclas WHERE mezclas_id_mezcla='$idMezcla'";
						$rs2 = mysql_query($stm_sql2);
						$volTotal = "0.00";
						if($datosVol=mysql_fetch_array($rs2)){
							//Obtener la cantidad de decimales del numero renodeado de 5 digitos hacia abajo
							$cantDecimales = contarDecimales(round($datosVol['total'],5));
							$volTotal = number_format($datosVol['total'],$cantDecimales,".",",");
						}?> 															
						<input type="text" name="txt_volTotal" id="txt_volTotal" class="caja_de_num" size="12" maxlength="15" readonly="readonly"
						value="<?php echo $volTotal; ?>" />
					</td>
					<td class="<?php echo $nom_clase; ?>">&nbsp;</td>
					<td class="<?php echo $nom_clase; ?>">&nbsp;</td>
				</tr>
				<tr><td colspan="5">&nbsp;</td></tr>
				<tr>
					<td colspan="5" align="center"><?php 
						//La variable bandera nos permitira indicar si ya estan definidos datos en la session, 
						//de modo que se pueda sustituir los datos siempre y cuando el usuario lo solicite
						$bandera = "no";
						if (isset($_SESSION["datosDisenio"])){
							$bandera="si";
						}?>
						<input type="hidden" name="hdn_tam" id="hdn_tam" value="<?php echo $cont-1;?>"/>
						<input type="hidden" name="hdn_band" id="hdn_band" value="<?php echo $bandera;?>"/>					
						
						<input type="submit" name="sbt_modificar" value="Modificar" class="botones" title="Modificar el Dise&ntilde;o de la Mezcla" 
						onMouseOver="window.estatus='';return true"/>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="reset" name="rst_restablecer" value="Restablecer" class="botones" title="Restablecer la Cantidad de los Materiales" />
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="button" name="btn_cerrar" value="Cerrar" class="botones" title="Cerrar" onMouseOver="window.estatus='';return true" 
						onclick="window.close();" />
					</td>
				</tr>						
			</table>
			</form>
			<br><br><br><?php
		}else{
			echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";
			echo "<p align='center' class='titulo_etiqueta'><b>No hay Materiales Asoicados a la Mezcla Seleccionada</p>";?>
			<br /><br />
			<p align="center">
				<input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cerrar" 
				onMouseOver="window.estatus='';return true"  onclick="window.close();"/>
			</p><?php
		}
		
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//fin de la funcion seleccionarPruebas
	
	
?>