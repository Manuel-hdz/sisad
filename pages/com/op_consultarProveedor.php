<?php

	/**
	  * Nombre del Módulo: Compras
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 10/Noviembre/2010                                      			
	  * Descripción: Este archivo contiene funciones desplegar la informacion de un proveedor dado
	  **/

	//Aqui especificamos el parametro a recibir para la busqueda, ademas del parametro $tipo, que indica si es busqueda por razon social o material
	// 1 -> Razon Social o nombre
	// 2 -> Material que se oferta
	// 3 -> Todos los proveedores
	function mostrarProveedores($buscar,$tipo){
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_compras");		
		//Comparamos el tipo de busqueda a hacer
		if ($tipo==1)
			//Escribimos la consulta a realizarse por nombre
			$stm_sql = "SELECT * FROM proveedores WHERE razon_social='$buscar'";
		if ($tipo==2)
			//Escribimos la consulta a realizarse por Servicio o Material
			$stm_sql = "SELECT * FROM proveedores WHERE mat_servicio='$buscar'";
		if ($tipo==3)
			//Escribimos la consulta a realizarse por cada Proveedor
			$stm_sql = "SELECT * FROM proveedores";
		if ($tipo==4)
			//Escribimos la consulta a realizarse por cada Proveedor
			$stm_sql = "SELECT * FROM proveedores WHERE relevancia = '$buscar'";
		//Ejecutar la Sentencia para obtener los datos del proveedor seleccionado
		$rs = mysql_query($stm_sql);
		$rfc="";
		if($datos = mysql_fetch_array($rs)){		
			if ($tipo==1)
				echo "				
				<table cellpadding='5' width='2000'> 
				<caption class='titulo_etiqueta'>Datos del Proveedor $buscar</caption>";
			if ($tipo==2)
				echo "				
				<table cellpadding='5' width='1200'> 
				<caption class='titulo_etiqueta'>Proveedores que suministran $buscar</caption>";
			if ($tipo==3)
				echo "				
				<table cellpadding='5' width='1200'> 
				<caption class='titulo_etiqueta'>Lista de Proveedores</caption>";
			if ($tipo==4)
				echo "				
				<table cellpadding='5' width='1200'> 
				<caption class='titulo_etiqueta'>Proveedores de Tipo $buscar</caption>";
			
			
			echo "	<tr>
						<td class='nombres_columnas' align='center'>RFC</td>
						<td class='nombres_columnas' align='center'>RAZÓN SOCIAL</td>
						<td class='nombres_columnas' align='center'>CALLE</td>
						<td class='nombres_columnas' align='center'>NÚMERO EXTERNO</td>
						<td class='nombres_columnas' align='center'>NÚMERO INTERNO</td>
						<td class='nombres_columnas' align='center'>COLONIA</td>
						<td class='nombres_columnas' align='center'>CÓDIGO POSTAL</td>
						<td class='nombres_columnas' align='center'>CIUDAD</td>
						<td class='nombres_columnas' align='center'>ESTADO</td>
						<td class='nombres_columnas' align='center'>TELÉFONO</td>
						<td class='nombres_columnas' align='center'>TELÉFONO 2 </td>
						<td class='nombres_columnas' align='center'>FAX</td>
						<td class='nombres_columnas' align='center'>RELEVANCIA</td>
						<td class='nombres_columnas' align='center'>CORREO</td>
						<td class='nombres_columnas' align='center'>CORREO  2 </td>
						<td class='nombres_columnas' align='center'>CONTACTO</td>
						<td class='nombres_columnas' align='center'>MATERIAL DE SERVICIO </td>
						<td class='nombres_columnas' align='center'>OBSERVACIONES</td>
						<td class='nombres_columnas' align='center'>DOCUMENTACIÓN</td>
						<td class='nombres_columnas' align='center'>CAR&Aacute;TULA</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{										
				echo "	<tr>					
						<td class='nombres_filas' align='center'>$datos[rfc]</td>					
						<td class='$nom_clase' align='left'>$datos[razon_social]</td>
						<td class='$nom_clase' align='left'>$datos[calle]</td>
						<td class='$nom_clase' align='center'>$datos[numero_ext]</td>
						<td class='$nom_clase' align='center'>$datos[numero_int]</td>					
						<td class='$nom_clase' align='left'>$datos[colonia]</td>
						<td class='$nom_clase' align='center'>$datos[cp]</td>
						<td class='$nom_clase' align='left'>$datos[ciudad]</td>
						<td class='$nom_clase' align='left'>$datos[estado]</td>					
						<td class='$nom_clase' align='center'>$datos[telefono]</td>
						<td class='$nom_clase' align='center'>$datos[telefono2]</td>
						<td class='$nom_clase' align='center'>$datos[fax]</td>
						<td class='$nom_clase' align='center'>$datos[relevancia]</td>
						<td class='$nom_clase' align='left'>$datos[correo]</td>
						<td class='$nom_clase' align='left'>$datos[correo2]</td>
						<td class='$nom_clase' align='left'>$datos[contacto]</td>
						<td class='$nom_clase' align='left'>$datos[mat_servicio]</td>
						<td class='$nom_clase' align='left'>$datos[observaciones]</td>
						<td class='$nom_clase' align='center'>
							<form name='frm_doctos' method='post' action='frm_modificarProveedor.php?btn=btn_modificarDoc&org=consulta'>
								<input type='hidden' name='txt_rfc' id='txt_rfc' value='$datos[rfc]'/>
								<input type='hidden' name='txt_nombre' id='txt_nombre' value='$datos[razon_social]'/>
								<input type='submit' class='botones' name='sbt_docs' id='sbt_docs' value='Revisar' ";?>onmouseover="window.status='';return true" <?php 
								echo"/>
							</form>
						</td>";
						?>
						<td>
						<input type="button" name="btn_caratula" class="botones" value="Ver Car&aacute;tula" onMouseOver="window.estatus='';return true" 
                        title="Generar Car&aacute;tula de <?php echo $datos['razon_social'];?>" onclick="window.open('../../includes/generadorPDF/expProveedor.php?rfc=<?php echo $datos['rfc']; ?>', '_blank','top=100, left=100, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')"/>
						</td>
						<?php
					echo "</tr>";			
				$rfc=$datos["rfc"];
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</tr>
			</table>";
			
			if ($tipo!=3&&$tipo!=2){
				//Consultar si el proveedor tiene Convenios con la Empresa
				$stm_sql="SELECT id_convenio FROM convenios WHERE proveedores_rfc='$rfc'";
				//Ejecutar la Sentencia para obtener los convenios del proveedor seleccionado
				$rs = mysql_query($stm_sql);
				if($datos = mysql_fetch_array($rs)){
					echo "<form name='frm_verConvenios' onsubmit='return valFormconsultaConvenios(this);' method='post' action='frm_consultarConvenios.php'>";
					echo "<br/><label>Convenios&nbsp;&nbsp;&nbsp;</label>";
					echo cargarComboEspecifico("cmb_convenios","id_convenio","convenios","bd_compras","$rfc","proveedores_rfc","Seleccionar...","");
					echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='sbt_conv' class='botones_largos' id='sbt_conv' value='Consultar Convenio' title='Consultar Convenio del Proveedor' ";?>
                    onmouseover="window.status='';return true" <?php echo"/>
						<input type='hidden' name='hdn_rfc' id='hdn_rfc' value='$rfc'/>
						</form>";
				}
				else{
					echo "<p class='msje_correcto'>No existen convenios con el proveedor seleccionado</p>";
				}
			}
			echo "</form>";
		}else{
			echo "<label class='msje_correcto'>No se encontr&oacute; ning&uacute;n resultado con el dato: <u><em>".$buscar."</u></em></label>";
		}
		if ($tipo==3)
			//Cerrar la conexion con la BD solo si el tipo es 3, ya que de otra forma, la conexion a la bd_compras se cierra en la funcion cargarComboEspecifico
			mysql_close($conn);
	}
	
	//Funcion para mostrar los documentos registrados de los proveedores
	function mostrarDocumentos(){
		$rfc = $_POST["txt_rfc"]; 
		//Conectar a la BD de Compras
		$conn=conecta("bd_compras");
		//Crear sentencia SQL
		$stm_sql="SELECT nombre_docto,estatus,ubicacion FROM expediente_proveedor WHERE proveedores_rfc='$rfc'";
		//Ejecutar la Sentencia para obtener los datos del proveedor seleccionado
		$rs = mysql_query($stm_sql);		            						
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='850' align='center'> 
				<caption class='titulo_etiqueta'>Documentaci&oacute;n Registrada de ".$_POST["txt_nombre"]."</caption></br>";
			echo "	<tr>
						<td class='nombres_columnas' align='center'>DOCUMENTO</td>
						<td class='nombres_columnas' align='center'>ESTATUS</td>
						<td class='nombres_columnas' align='center'>UBICACI&Oacute;N</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{										
				echo "	<tr>					
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
			
			</table>	
			</form>";
			return $res="";
		}else
		{
			echo "</br></br></br></br></br></br></br></br></br><p align='center' class='msje_correcto'>EL PROVEEDOR <u>".$_POST["txt_nombre"]."</u>
			 NO TIENE NINGÚN DOCUMENTO REGISTRADO</p>";
			return $res="disabled='true'";
		}
		//Cerar conexion a BD
		mysql_close($conn);
	}
?>