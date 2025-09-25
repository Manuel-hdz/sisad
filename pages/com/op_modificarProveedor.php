<?php
	/**
	  * Nombre del Módulo: Compras                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 27/Noviembre/2010                                      			
	  * Descripción: Este archivo contiene funciones para  modificar informacion  relacionada con el formulario de Modificar Proveedor en la BD
	  **/		 
	  
	//Esta funcion se encarga de guardar los cambios realizados al Provedor seleccionado por el usuario
	function guardarCambios(){	
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_compras");	
		
		//Convertir los caracteres de los campos de texto en Mayúsculas
		$rfcOriginal = strtoupper($_POST["hdn_rfc"]); 
		$rfc = strtoupper($_POST["txt_rfc"]); 
		$razonSocial = strtoupper($_POST["txt_razonSoc"]); 
		$calle=strtoupper($_POST["txt_calle"]);
		$numeroExt = $_POST["txt_numExt"];
		$numeroInt = $_POST["txt_numInt"];
		$colonia = strtoupper($_POST["txt_col"]); 
		$cp=strtoupper($_POST["txt_cp"]); 
		$ciudad = strtoupper($_POST["txt_ciudad"]);
		$estado = strtoupper($_POST["txt_estado"]);
		$tel = $_POST["txt_tel"]; 
		$tel2 = $_POST["txt_tel2"]; 
		$fax = $_POST["txt_fax"]; 
		$relevancia = $_POST["cmb_relevancia"]; 
		$correo = $_POST["txt_correo"]; 
		$correo2 = $_POST["txt_correo2"]; 
		$contacto = strtoupper($_POST["txt_contacto"]);
		$materialServicios = strtoupper($_POST["txa_matServ"]);
		$observaciones = strtoupper($_POST["txa_observaciones"]);
		
		
		//Crear la sentencia para modificar los  Proveedores en la BD de comprasen la tabla de Proveedores
		$stm_sql = "UPDATE proveedores SET rfc='$rfc',razon_social='$razonSocial',calle='$calle',numero_ext='$numeroExt',numero_int='$numeroInt', 
					colonia='$colonia',cp='$cp',ciudad='$ciudad',estado='$estado',telefono='$tel',telefono2='$tel2',fax='$fax',relevancia='$relevancia',
					correo='$correo',correo2='$correo2',contacto='$contacto',mat_servicio='$materialServicios',observaciones='$observaciones' WHERE rfc='$rfcOriginal'";
				
		//Ejecutar la sentencia previamente creada 
		$rs = mysql_query($stm_sql);									
										
		//Confirmar que la insercion de datos fue realizada con exito.
		if($rs){
			registrarOperacion("bd_compras",$rfc,"ModificarProveedor",$_SESSION['usr_reg']);
			actualizarDocumentos($rfcOriginal,$rfc);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";											
		}
		else{
			if (mysql_errno()=="1062")
				$error="La Clave $rfcOriginal esta Asignada a Otro Proveedor";
			else
				$error = mysql_error();
				
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";			
		}		
		//Cerrar la conexion con la BD		
		//La Conexion a la BD se cierra en la funcion registrarOperacion("bd_compras",$txt_rfc,"AgregarCliente",$_SESSION['usr_reg']);	
	}
	
	function mostrarProveedor($nombre){
		//Conectar a la BD de compras
		$conn=conecta("bd_compras");
		//Crear sentencia SQL
		$stm_sql="SELECT * FROM proveedores WHERE razon_social='$nombre'";
		//Ejecutar la consulta
		$rs=mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){?>			
			<form name="frm_modificarProveedor" onsubmit="return verContFormAgregarProveedor(this);" method="post" action="frm_modificarProveedor.php">
				<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
                    <tr>
                      <td><div align="right">*RFC</div></td>
                        <td>
                            <input name="txt_rfc" type="text" class="caja_de_texto" size="15" maxlength="13" onkeypress="return permite(event,'num_car',3);"
                            value="<?php echo $datos["rfc"]; ?>" /><span id="error" class="msj_error">RFC Duplicado</span>
                            <input type="hidden" name="hdn_rfc" id="hdn_rfc" value="<?php echo $datos["rfc"]; ?>"/>
                        </td>
                        <td><div align="right">*Tel&eacute;fono</div></td>
                        <td>
                            <input name="txt_tel" type="text" class="caja_de_texto" id="txt_telefono" size="15" maxlength="20" 
                            onkeypress="return permite(event,'num',6);" value="<?php echo $datos["telefono"]; ?>" onblur="validarTelefono(this);"/>
                        </td>
                    </tr>
                    <tr>
                        <td><div align="right">*Raz&oacute;n Social </div></td>
                        <td>
                            <input name="txt_razonSoc" id="txt_razonSoc" type="text" class="caja_de_texto" size="40" maxlength="80" onkeypress="return permite(event,'num_car',4);" 
                            value="<?php echo $datos["razon_social"]; ?>"/>
                        </td>
                        <td><div align="right">Tel&eacute;fono 2 </div></td>
                        <td>
                            <input name="txt_tel2" type="text" class="caja_de_texto" id="txt_tel2" onkeypress="return permite(event,'num',6);" size="15" 
                            maxlength="20" value="<?php echo $datos["telefono2"]; ?>" onblur="validarTelefono(this);"/>
                        </td>
                    </tr>
                    <tr>
                        <td><div align="right">*Calle</div></td>
                        <td>
                            <input name="txt_calle" type="text" class="caja_de_texto" size="30" maxlength="40" onkeypress="return permite(event,'num_car',0);" 
                            value="<?php echo $datos["calle"]; ?>"/>
                        </td>
                        <td><div align="right">Fax</div></td>
                        <td>
                            <input name="txt_fax" type="text" class="caja_de_texto" size="15" maxlength="20" onkeypress="return permite(event,'num',6);" 
                            value="<?php echo $datos["fax"]; ?>" onblur="validarTelefono(this);" />
                        </td>
                    </tr>
                    <tr>
                        <td><div align="right">*N&uacute;mero Ext.</div></td>
                        <td align="left">	
                            <input name="txt_numExt" type="text" class="caja_de_texto" size="5" maxlength="10" onkeypress="return permite(event,'num_car',1);" 
                            value="<?php echo $datos["numero_ext"]; ?>"/>&nbsp;Int.&nbsp;
                            <input name="txt_numInt" type="text" class="caja_de_texto" size="5" maxlength="10" onkeypress="return permite(event,'num_car',1);" 
                            value="<?php echo $datos["numero_int"]; ?>"/>			
                        </td>
                        <td><div align="right">*Relevancia </div></td>
                        <td>
                            <select name="cmb_relevancia" class="combo_box">
                            <?php
                                if ($datos["relevancia"]=='CRITICO'){
                                    echo "<option value='CRITICO'>CRITICO</option>";
                                    echo "<option value='NO CRITICO'>NO CRITICO</option>";
                                }
                                else{
                                    echo "<option value='CRITICO'>CRITICO</option>";
                                    echo "<option selected='selected' value='NO CRITICO'>NO CRITICO</option>";
                                }
                            ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><div align="right">*Colonia </div></td>
                        <td>
                            <input name="txt_col" type="text" class="caja_de_texto" id="txt_colonia" size="40" maxlength="60"
                             onkeypress="return permite(event,'num_car',0);" value="<?php echo $datos["colonia"]; ?>"/>
                         </td>
                        <td><div align="right">Correo</div></td>
                        <td><input name="txt_correo" type="text" class="caja_de_texto" size="30" maxlength="40" value="<?php echo $datos["correo"]; ?>" onblur=
                        "validarCorreo(this);"/></td>
                    </tr>
                    <tr>
                        <td><div align="right">*C&oacute;digo Postal </div></td>
                        <td>
                            <input name="txt_cp" type="text" class="caja_de_texto" size="5" maxlength="5" onkeypress="return permite(event,'num',3);"
                             value="<?php echo $datos["cp"]; ?>"/>
                         </td>
                        <td><div align="right">Correo 2 </div></td>
                        <td><input name="txt_correo2" type="text" class="caja_de_texto" size="30" maxlength="40" value="<?php echo $datos["correo2"]; ?>" onblur=
                        "validarCorreo(this);"/></td>
                    </tr>
                    <tr>
                        <td><div align="right">*Ciudad</div></td>
                        <td>
                            <input name="txt_ciudad" type="text" class="caja_de_texto" size="30" maxlength="40" onkeypress="return permite(event,'num_car',2);"
                             value="<?php echo $datos["ciudad"]; ?>"/>
                        </td>
                        <td><div align="right">*Contacto</div></td>
                        <td>
                            <input name="txt_contacto" type="text" class="caja_de_texto" size="30" maxlength="40" onkeypress="return permite(event,'num_car',2);"
                             value="<?php echo $datos["contacto"]; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td><div align="right">*Estado</div></td>
                        <td>
                            <input name="txt_estado" type="text" class="caja_de_texto" size="30" maxlength="40" onkeypress="return permite(event,'num_car', 2);
                            "value="<?php echo $datos["estado"]; ?>"/>
                        </td>
                        <td align="right">&nbsp;
                        </td>
                        <td>&nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td><div align="right">*Material y/o Servicio</div></td>
                        <td>
                            <textarea name="txa_matServ" id="txa_matServ" cols="30" rows="3" maxlength="120" onkeypress="return permite(event,'num_car',4);"
                             onkeyup="return ismaxlength(this)" class="caja_de_texto"><?php echo $datos["mat_servicio"]; ?></textarea>
                        </td>
                        <td><div align="right">Observaciones </div></td>
                        <td>
                            <textarea name="txa_observaciones" id="txa_observaciones" cols="30" rows="3" maxlength="120" onkeypress="return permite(event,'num_car',0);" 
                            onkeyup="return ismaxlength(this)" class="caja_de_texto"><?php echo $datos["observaciones"];?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td></tr>
                    <tr>
                        <td colspan="5">		  	
                            <div align="center">
                                <input type="hidden" name="hdn_claveValida" id="hdn_claveValida" value="si" />
								<input type="hidden" name="hdn_validaBoton" id="hdn_validaBoton" value="no" />
                                <input name="btn_modificarDoc" type="button" class="botones_largos" value="Documentos Registrados"
                                title="Registrar la Documentación del Proveedor" onclick="document.frm_modificarProveedor.action=
                                'frm_modificarProveedor.php?btn=btn_modificarDoc';document.frm_modificarProveedor.submit();"/>
                                &nbsp;
                                <input name="btn_Modificar" type="submit" class="botones"  value="Modificar" title="Modificar los Datos del Proveedor" 
                                onMouseOver="window.status='';return true"/>
                                &nbsp;
                               <input name="rst_limpiar" type="reset" class="botones"  value="Restablecer" title="Restablecer el Formulario"/> 
                                &nbsp;
                                <input name="btn_cancelar" type="button" value="Cancelar" class="botones" title="Regresar al Men&uacute; Anterior"
                               onclick="location.href='frm_modificarProveedor.php'" />
                            </div>			
                        </td>
                    </tr>
    			</table>
  			</form>			
		<?php 
		}//Cierre if($datos=mysql_fetch_array($rs))
		else{
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			echo  "<p class='msje_correcto' align='center'>No se Encontr&oacute; el Proveedor: <em><u> $nombre</u></em> </p>";?>
				<table width="100%">	
					<tr>
						<td align ="center">							
							<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar a la Pantalla de Modificar Proveedor"
                            onclick="location.href='frm_modificarProveedor.php'" />                              
						</td>
					</tr>
				</table>						
		<?php	
		}
		//Cerrar la conexión con la BD
		mysql_close($conn);
	}
	
	function seleccionarDocumentos(){
		$rfc = $_POST["hdn_rfc"];
		$nombre= $_POST["hdn_nombre"];
		//Conectar a la BD de Compras
		$conn=conecta("bd_compras");
		//Crear sentencia SQL
		$stm_sql="SELECT nombre_docto,estatus,ubicacion FROM expediente_proveedor WHERE proveedores_rfc='$rfc'";
		//Ejecutar la Sentencia para obtener los datos del proveedor seleccionado
		$rs = mysql_query($stm_sql);		            						
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='850' align='center'> 
				<caption class='titulo_etiqueta'>Seleccionar el documento a eliminar de ".$nombre."</caption></br>";
			echo "	<tr>
						<td class='nombres_columnas' align='center'>SELECCIONAR</td>
						<td class='nombres_columnas' align='center'>DOCUMENTO</td>
						<td class='nombres_columnas' align='center'>ESTATUS</td>
						<td class='nombres_columnas' align='center'>UBICACI&Oacute;N</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{										
				echo "	<tr>
						<td class='$nom_clase' align='center'><input type='radio' name='rdb_documentos' value='$datos[nombre_docto]'/></td>			
						<td class='$nom_clase' align='center'>$datos[nombre_docto]</td>					
						<td class='$nom_clase' align='center'>$datos[estatus]</td>
						<td class='$nom_clase' align='center'>$datos[ubicacion]</td>
					</tr>";			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</tr>
			</table>";
			return "";
		}else
		{
			echo "</br></br></br></br></br></br></br></br></br><p align='center' class='msje_correcto'>EL PROVEEDOR <u>".$nombre."</u> 
			NO TIENE NINGÚN DOCUMENTO REGISTRADO</p>";
			return "disabled='disabled'";
		}
		//Cerar conexion a BD
		mysql_close($conn);
	}
	
	function eliminarDocumento(){
		//Obtener el nombre del radiobutton seleccionado
		$rdb_documentos=$_POST["rdb_documentos"];
		//Conectar a la BD de Compras
		$conn=conecta("bd_compras");
		//Crear sentencia SQL
		$stm_sql="DELETE FROM expediente_proveedor WHERE nombre_docto='$rdb_documentos'";
		//Ejecutar la Sentencia para obtener los datos del proveedor seleccionado
		$rs = mysql_query($stm_sql);		            						
		//Cerar conexion a BD
		mysql_close($conn);
	}			
	
	//Desplegar los documentos registrados en el apartado de Agregar Documentos
	function mostrarDocumentosReg($documentos){
		echo "<table cellpadding='5' width='590'>";
		echo "<caption><p class='msje_correcto'><strong>Documentos agregados al registro de ".$_POST['hdn_rfc']."</strong></p></caption>";
		echo "      			
			<tr>
				<td class='nombres_columnas' align='center'>DOCUMENTO</td>
        		<td class='nombres_columnas' align='center'>ESTADO</td>
			    <td class='nombres_columnas' align='center'>UBICACI&Oacute;N</td>
      		</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		foreach ($documentos as $ind => $material) {
			echo "<tr>";
			foreach ($material as $key => $value) {
				switch($key){
					case "nombre":
						echo "<td class='nombres_filas'>$value</td>";
					break;
					case "estatus":
						echo "<td class='$nom_clase'>$value</td>";
					break;
					case "ubicacion":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
				}				
			}
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
			echo "</tr>";			
		}
		echo "</table>";
	}//Fin de la funcion mostrarRegistros($datosSalida)				
	
	function actualizarDocumentos($rfcOriginal,$rfcNuevo){
		//Conectar a la BD de Compras
		$conn=conecta("bd_compras");
		//Crear sentencia SQL
		$stm_sql="UPDATE expediente_proveedor SET proveedores_rfc='$rfcNuevo' WHERE proveedores_rfc='$rfcOriginal'";
		//Ejecutar la Sentencia para obtener los datos del proveedor seleccionado
		$rs = mysql_query($stm_sql);		            						
		//Cerar conexion a BD
		mysql_close($conn);
	}
?> 