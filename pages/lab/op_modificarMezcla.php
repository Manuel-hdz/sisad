<?php
	/**
	  * Nombre del Módulo: Laboratorio
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 20/Junio/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de Modificar Mezcla en la BD
	**/
	
	
	//Funcion que se encarga de desplegar las mezclas en el rango de fechas
	function mostrarMezclas(){

		//Conectar a la BD de laboratorio
		$conn = conecta("bd_laboratorio");
		
		//Si viene sbt_consultar la buqueda de las mezclas proviene de un rango de fechas
		if(isset($_POST["sbt_consultar"])){ 
		
			//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
			$f1 = modFecha($_POST['txt_fechaIni'],3);
			$f2 = modFecha($_POST['txt_fechaFin'],3);
			
			//Crear sentencia SQL
			$sql_stm ="SELECT * FROM mezclas WHERE fecha_registro BETWEEN '$f1' AND '$f2' AND estado='1' ORDER BY id_mezcla";
					
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Mezclas Registradas en el Periodo del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ninguna Mezcla en las Fechas del <em><u>$_POST[txt_fechaIni]
			</u></em> al <em><u>$_POST[txt_fechaFin]</u></em></label>";										
		}
		
		//Si viene sbt_consultar2 la buqueda de la mezcla proviene el combo box
		else if(isset($_POST["sbt_consultar2"])){
			//Crear sentencia SQL
			$sql_stm = "SELECT * FROM mezclas WHERE id_mezcla = '$_POST[cmb_claveMezcla]' AND estado='1'";
			
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Datos de la Mezcla <em><u> $_POST[cmb_claveMezcla]  </u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ninguna Mezcla </label>";										
		}

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
	
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='100%'>
				<tr>
					<td colspan='6' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center' width='10%'>MODIFICAR</td>
					<td class='nombres_columnas' align='center' width='15%'>ID MEZCLA</td>
					<td class='nombres_columnas' align='center' width='25%'>NOMBRE MEZCLA</td>
					<td class='nombres_columnas' align='center' width='10%'>EXPEDIENTE</td>
					<td class='nombres_columnas' align='center' width='25%'>EQUIPO MEZCLADO</td>
					<td class='nombres_columnas' align='center' width='15%'>FECHA REGISTRO</td>					
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='nombres_filas' align='center'><input type='radio' name='rdb' value='$datos[id_mezcla]'/>
						</td>
						<td class='$nom_clase'>$datos[id_mezcla]</td>
						<td class='$nom_clase'>$datos[nombre]</td>
						<td class='$nom_clase'>$datos[expediente]</td>
						<td class='$nom_clase'>$datos[equipo_mezclado]</td>
						<td class='$nom_clase'>".modFecha($datos['fecha_registro'],1)."</td>
						
					</tr>";
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "</table>";
			return 1;
			
		}// fin  if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;		
			return 0;		
		}
	}//Fin de la function mostrarMezclas()
	

	//Funcion que se encarga de cargar los datos de la mezcla seleccionada en el formulario para poderla modificar	
 	function modificarMezclaSeleccionada(){
		//Relizar la consulta con el id de la mezcla seleccionada para poder precargar los datos 
		//Conectar a la BD de laboratorio
		$conn = conecta("bd_laboratorio");
		//Crear sentencia SQL
		$sql_stm ="SELECT * FROM mezclas WHERE id_mezcla='".$_SESSION['datosMezcla']['idMezcla']."' AND estado = '1'";
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
		//Confirmar que la consulta de datos fue realizada con exito.
		$datos=mysql_fetch_array($rs);?>
        <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
        <fieldset class="borde_seccion" id="tabla-agregarMezcla" name="tabla-agregarMezcla">
        <legend class="titulo_etiqueta">Ingrese la Informaci&oacute;n de la Mezcla</legend>	
        <br>
        <form onSubmit="return valFormAgregarMezcla(this);" name="frm_agregarMezcla" method="post" action="frm_modificarMezcla.php">
		<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">		
            <tr>
                <td><div align="right"> *Id Mezcla</div></td>
                <td colspan="3">
					<input name="txt_idMezcla" type="text" class="caja_de_texto" id="txt_idMezcla"  size="40" maxlength="30" 
                    onkeypress="return permite(event,'num_car',4);"  value="<?php echo $datos['id_mezcla'];?>" readonly="readonly" />
                    <input type="hidden" name="hdn_idOriginal" id="hdn_idOriginal" value="<?php echo $datos['id_mezcla'];?>" />				
				</td>
			</tr>
            <tr>
                <td><div align="right">*Nombre de Mezcla</div></td>
                <td colspan="3">
					<input type="text" name="txt_nombreMezcla" id="txt_nombreMezcla" value="<?php echo $datos['nombre'];?>" size="40" maxlength="90" 
                    onkeypress="return permite (event, 'num_car',4);"/>				
				</td>
            </tr>
            <tr>
                <td width="25%"><div align="right">*Expediente</div></td>
                <td width="25%">
					<input type="text" name="txt_expediente" id="txt_expediente" value="<?php echo $datos['expediente'];?>" size="5" maxlength="4" 
                    onkeypress="return permite (event, 'num',2);"/>				
				</td>
                <td width="25%" align="right">Fecha de Registro</td>
            	<td width="25%">
					<input type="text" name="txt_fechaRegistro" id="txt_fechaRegistro" class="caja_de_texto" value="<?php echo modFecha($datos['fecha_registro'],1); ?>" 
					readonly="readonly" size="10" />
				</td>
            </tr>
            <tr>
                <td><div align="right">*Equipo de Mezclado</div></td>
              	<td>
					<input type="text" name="txt_eqMezclado" id="txt_eqMezclado" value="<?php echo $datos['equipo_mezclado'];?>" size="30" maxlength="30"
                    onkeypress="return permite (event, 'num_car',0);"/>
				</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>            
            <tr>
                <td colspan="4"><strong>* Datos marcados con asterisco son <u>obligatorios</u></strong></td>
            <tr>
                <td colspan="4">
                    <div align="center">
						<input name="btn_modComponentes" type="submit" class="botones_largos" id="btn_modComponentes" value="Modificar Componentes" 
                    	title="Modificar Componentes" onmouseover="window.status='';return true"/>
						&nbsp;&nbsp;
                        <input type="hidden" name="hdn_claveValida" id="hdn_claveValida" value="si"/>
                        <input name="sbt_modificar" type="submit" class="botones" id="sbt_modificar"  value="Modificar" title="Guardar Modificaciones Realizadas" 
                        onmouseover="window.status='';return true"/>
                        &nbsp;&nbsp;
                        <input name="rst_limpiar" type="reset" class="botones" id="rst_limpiar"  value="Restablecer" title="Restablecer Formulario" 
                        onmouseover="window.status='';return true"/>
						&nbsp;&nbsp;<?php
						if(isset($_POST["sbt_consultar"])){ ?>
							<input type="hidden" name="txt_fechaIni" value="<?php echo $_POST['txt_fechaIni'];?>" />
							<input type="hidden" name="txt_fechaFin" value="<?php echo $_POST['txt_fechaFin'];?>" />
							<input type="hidden" name="sbt_consultar" value="Consultar" /><?php
						}
						if(isset($_POST["sbt_consultar2"])){ ?>
							<input type="hidden" name="cmb_claveMezcla" value="<?php echo $_SESSION['datosMezcla']['idMezcla'];?>" />
							<input type="hidden" name="sbt_consultar2" value="Consultar"/><?php
						} ?>					
                        &nbsp;&nbsp;
                        <input name="btn_cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" 
                        title="Cancelar y Regresar al Men&uacute; de Mezclas " 
                        onmouseover="window.status='';return true" onclick="confirmarSalida('menu_mezclas.php');"/>
						<input type="hidden" name="hdn_validar" id="hdn_validar"/>
                    </div>				
				</td>
            </tr>
        </table>
        </form>
        </fieldset>
		
		<div id="div-calendario">
      		<input type="image" name="txt_fechaRegistro" id="txt_fechaRegistro" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_agregarMezcla.txt_fechaRegistro,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
			title="Seleccionar Fecha de Colado" tabindex="4" />
		</div><?php
	}//Fin de la function modificarMezclaSeleccionada()
	
	
	//Si viene sbt_modificar 
	if(isset($_POST["sbt_modificar"]))
		guardarModificacion();
		
		
	//Funcion que se encarga de  guardar las modificaciones
	function guardarModificacion(){
		//Conectar se a la Base de Datos
		$conn = conecta("bd_laboratorio");

		//Recuperar la informacion de la sesion		
		$idMezcla = strtoupper($_POST["txt_idMezcla"]);
		$nomMezcla = strtoupper($_POST["txt_nombreMezcla"]);
		$expediente = strtoupper($_POST["txt_expediente"]);
		$eqMezclado = strtoupper($_POST["txt_eqMezclado"]);
		$fechaRegistro = modFecha($_POST["txt_fechaRegistro"],3);			
		//ID original
		$id = $_POST['hdn_idOriginal'];	

		//Crear la Sentencia SQL para Alamcenar los materiales agregados 
		$stm_sql = "UPDATE mezclas SET id_mezcla = '$idMezcla', nombre = '$nomMezcla', expediente = '$expediente', equipo_mezclado = '$eqMezclado', 
					fecha_registro = '$fechaRegistro' WHERE id_mezcla='$id'";
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		
		//Verificar Resultado
		if ($rs){
			//Guardar el registro de movimientos
			registrarOperacion("bd_laboratorio",$id,"ModificarMezcla",$_SESSION['usr_reg']);
			verificarDatos($id, $idMezcla);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}//Cierre de la funcion guardarModificacion()
	
	
	/*******************************Cuando se registren las Muestras Verificar la Utilidad de esta funcion************************************/
	
	//Funcion que verifica  si exiten id iguales para tambien modificarlos
	function verificarDatos($idOriginal, $idNuevo){
		$conn= conecta('bd_laboratorio');
		$stm_sql="";
		
		//Comprobar tabla materiales_de_mezclas
		if(obtenerDato("bd_laboratorio","materiales_de_mezclas","mezclas_id_mezcla","mezclas_id_mezcla", $idOriginal)!=''){
			$stm_sql="UPDATE materiales_de_mezclas SET mezclas_id_mezcla='$idNuevo' WHERE mezclas_id_mezcla='$idOriginal'";
			$rs=(mysql_query($stm_sql));
		}
		
		/*
		//Comprobar tabla plan_pruebas
		if(obtenerDato("bd_laboratorio","plan_pruebas","mezclas_id_mezcla","mezclas_id_mezcla", $idOriginal)!=''){
			$stm_sql="UPDATE plan_pruebas SET mezclas_id_mezcla='$idNuevo' WHERE mezclas_id_mezcla='$idOriginal'";
			$rs=(mysql_query($stm_sql));
		}

		//Comprobar tabla pruebas_calidad
		if(obtenerDato("bd_laboratorio","prueba_calidad","mezclas_id_mezcla","mezclas_id_mezcla", $idOriginal)!=''){
			$stm_sql="UPDATE prueba_calidad SET mezclas_id_mezcla='$idNuevo' WHERE mezclas_id_mezcla='$idOriginal'";
			$rs=(mysql_query($stm_sql));
		}*/
		
		mysql_close($conn);
	}//Cierre de la function verificarDatos($id, $idMezcla){
	

	//function que muestra los componentes de la mezcla seleccionada para poder añadir otro o en su caso elimiar uno existente
	function mostrarMatMezcla(){
		
		if(isset($_POST['hdn_botonSel'])){
			unset($_SESSION['materiales']); 	
		}
		
		//Conectar se a la Base de Datos
		$conn = conecta("bd_laboratorio");
		
		//Crear la Sentencia SQL para Alamcenar los materiales agregados 
		$stm_sql = "SELECT * FROM materiales_de_mezclas WHERE mezclas_id_mezcla='$_POST[txt_idMezcla]'";
		
		//Crear el mensaje que se mostrara en el titulo de la tabla
		$msg= "Componentes de la Mezcla  con Clave<em><u> $_POST[txt_idMezcla]  </u></em>";
		
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "	<label class='msje_correcto' align='center'>No Hay Materiales de la Mezcla </label>";								

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
	
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='100%'>				
				<tr>
					<td colspan='18' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center' width='10%'>SELECCIONAR</td>
					<td class='nombres_columnas' align='center' width='10%'>CLAVE MATERIAL</td>
					<td class='nombres_columnas' align='center' width='25%'>CATEGOR&Iacute;A</td>
					<td class='nombres_columnas' align='center' width='25%'>NOMBRE</td>
					<td class='nombres_columnas' align='center' width='10%'>CANTIDAD</td>
					<td class='nombres_columnas' align='center' width='10%'>UNIDAD MEDIDA</td>
					<td class='nombres_columnas' align='center' width='10%'>VOLUMEN</td>
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				//Obtener el nombre del material de la bd de almacen							
				$nomMaterial=obtenerDato('bd_almacen', 'materiales', 'nom_material','id_material', $datos['catalogo_materiales_id_material']);
				//Obtener la categoria del material de la bd de almacen
				$categoriaMat=obtenerDato('bd_almacen', 'materiales', 'linea_articulo','id_material', $datos['catalogo_materiales_id_material']);
				
				//Obtener el numero de decimales de la cantidad del material
				$cantDecimales = contarDecimales($datos['cantidad']);
				
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='nombres_filas' align='center'><input type='radio' name='rdb' value= $datos[catalogo_materiales_id_material] />
						</td>
						<td class='$nom_clase'>$datos[catalogo_materiales_id_material]</td>
						<td class='$nom_clase'>$categoriaMat</td>
						<td class='$nom_clase'>$nomMaterial</td>
						<td class='$nom_clase' align='center'>".number_format($datos['cantidad'], $cantDecimales,".",",")."</td>
						<td class='$nom_clase'>$datos[unidad_medida]</td>
						<td class='$nom_clase' align='center'>".number_format($datos['volumen'], 2,".",",")." m&sup3;</td>						
					</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "</table>";
			return 1;
			
		}// fin  if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;		
			return 0;		
		}
	}//Cierre de la function mostrarMatMezcla()
	
	
	//Funcion que se encarga de eliminar el material seleccionada
	function eliminarMaterialSeleccionado(){
		
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_laboratorio");
		
		//Creamos la sentencia SQL para borrar el material de la bd
		$stm_sql="DELETE FROM materiales_de_mezclas WHERE catalogo_materiales_id_material = '$_POST[rdb]'";
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);									
									
		//Verificar si la sentencia ejecutada se genero con exito
		if ($rs){
			//Guardar la operacion realizada
			registrarOperacion("bd_laboratorio",$_POST['rdb'],"EliminarMaterial",$_SESSION['usr_reg']);	
																					
			//Direccionar a la pantalla de exito
			//echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			$error=mysql_error();
			//Cerrar la conexion con la BD de Laboratorio
			mysql_close($conn);
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}//Cierre de la funcion eliminarMezclaSeleccionada
	
	
	//Funcion que permite añadir una nuevo material a la mezcla
	function agregarMatMezcla(){
		
		$idMezcla = $_SESSION['datosMezcla']['idMezcla']; ?>
		<fieldset class="borde_seccion" id="tabla-agregarMezcla2" name="tabla-agregarMezcla2">
		<legend class="titulo_etiqueta">Ingrese los Materiales que Componen la Mezcla</legend>	
		<br>
		<form onSubmit="return valFormAgregarMezcla2(this);" name="frm_agregarMezcla2" method="post" action="frm_modificarMezcla.php">
			<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td width="15%"><div align="right">Id Mezcla</div></td>
					<td width="35%"> 
						<input type="text" name="txt_idMezcla" id="txt_idMezcla" value="<?php echo $idMezcla;?>" readonly="readonly" size="30"/>					</td> 
					<td width="15%">&nbsp;</td>
					<td width="35%">&nbsp;</td>
				</tr>
				<tr>
					<td><div align="right">*Categoria</div></td>
					<td>
						<select name="cmb_categoria" id="cmb_categoria" class="combo_box"
							onchange="cargarComboConId(this.value,'bd_almacen','materiales','nom_material','id_material','linea_articulo','cmb_nombre','Material','');">
							<option value="">Categor&iacute;a</option>
						</select>                
						<script type="text/javascript" language="javascript">
							cargarComboConId('PLANTA','bd_almacen','materiales','linea_articulo','linea_articulo','grupo','cmb_categoria','Categoría','');
						</script>					</td>
					<td><div align="right">*Nombre</div></td>
					<td>
						<select name="cmb_nombre" id="cmb_nombre" class="combo_box"
						onchange="obtenerDatoBD(this.value,'bd_almacen','unidad_medida','unidad_medida','materiales_id_material','txt_unidadMedida')">
							<option value="">Material</option>
						</select>					
					</td>
				</tr>
				<tr>
					<td><div align="right">*Cantidad</div></td>
					<td>
						<input type="text" name="txt_cantidad" id="txt_cantidad" value="" class="caja_texto" maxlength="10" size="10" 
						onchange="formatNumDecimalLab(this.value,'txt_cantidad')" />
						&nbsp;&nbsp;&nbsp;					
						<input type="text" name="txt_unidadMedida" id="txt_unidadMedida" class="caja_de_texto" value="" size="15" />
					</td>
					<td align="right">*Volumen</td>
					<td>
						<input type="text" name="txt_volumen" id="txt_volumen" value="1" readonly="readonly" size="3" maxlength="3" class="caja_texto" 
						onkeypress="return permite(event, 'num',2)"/>m&sup3;
					</td>
				</tr>				
				<tr>
					<td colspan="6"><strong>* Datos marcados con asterisco son <u>obligatorios</u></strong></td>
				</tr>
				<tr>
					<td colspan="4"><div align="center">
	                    <input type="hidden" name="btn_modComponentes"/>
						<input type="hidden" name="hdn_botonSel" id="hdn_botonSel" value="" />
						<input name="sbt_agregar" type="submit" class="botones" id="sbt_agregar"  value="Agregar" title="Agregar otro Material" 
						onmouseover="window.status='';return true" onclick="hdn_botonSel.value='agregar'" />
						&nbsp;&nbsp;&nbsp;<?php
						 if (isset($_SESSION['materiales'])){?>
							<input name="sbt_finalizarMat" type="submit" class="botones" id="sbt_finalizarMat"  value="Finalizar" title="Finalizar Registro de Materiales" 
							onmouseover="window.status='';return true" onclick="hdn_botonSel.value='finalizar'"/>
							&nbsp;&nbsp;&nbsp;<?php
						  } ?>
						<input name="rst_limpiar" type="reset" class="botones" id="rst_limpiar"  value="Limpiar" title="Limpiar Formulario" 
						onmouseover="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;
						<input name="sbt_regresarFin"  id="sbt_regresarFin" type="submit" class="botones" value="Regresar"
						title="Regresar a Materiales Registrados de la Mezcla" onmouseover="window.status='';return true" 
                        onclick="hdn_botonSel.value='regresar'" />
						&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" 
                        title="Cancelar y Regresar al Men&uacute; de Mezclas " 
						onmouseover="window.status='';return true" onclick="confirmarSalida('menu_mezclas.php');"/></div>					</td>   	
				</tr>        
		  </table>
		</form>
		</fieldset><?php 
		//Si esta inicializado el arreglo de Sesion, mostrar los conceptos agregados
		if(isset($_SESSION['materiales'])){?>
			<div id='materialesAgregados' class='borde_seccion2'><?php
				mostrarMatAdd();?>
			</div>
			<?php
		}
	}//Cierre de la function agregarMatMezcla()
	
	
	//Funcion que se encarga de desplegar los materiales agregados
	function mostrarMatAdd(){
		echo "<table cellpadding='5' width='900'>";
		echo "  
			<tr>
				<td class='nombres_columnas' align='center'>CLAVE MATERIAL</td>
        		<td class='nombres_columnas' align='center'>CATEGOR&Iacute;A</td>
				<td class='nombres_columnas' align='center'>NOMBRE</td>
			    <td class='nombres_columnas' align='center'>CANTIDAD</td>
      		</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		$total = 0;

		foreach ($_SESSION['materiales'] as $ind => $datosMat) {
			$nomMaterial=obtenerDato('bd_almacen', 'materiales', 'nom_material','id_material', $datosMat['claveMat']);
			echo "<tr>			
					<td class='nombres_filas' align='center'>$datosMat[claveMat]</td>
					<td class='$nom_clase' align='center'>$datosMat[categoria]</td>
					<td class='$nom_clase' align='center'>$nomMaterial</td>
					<td class='$nom_clase' align='center'>$datosMat[cantidad] $datosMat[unidad]</td>
			</tr>";			
			
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
		}
		echo "</table>";
	}//Cierre de la funcion mostrarMatAdd()	


	//Funcion para guardar las modificaciones hechas en los materiales de la mezcla seleccionada para su edición
	function guardarMateriales(){
		//Conectar se a la Base de Datos
		$conn = conecta("bd_laboratorio");
		$idMezcla= $_SESSION['datosMezcla']['idMezcla']; 
		
		//Recorrer el arreglo que contiene los materiales
		foreach($_SESSION['materiales'] as $ind => $concepto){
			
			//Al momento de agregar los componenetes Eliminar la posible como(,) y redondear el numero hasta 5 decimales
			$cant = round(str_replace(",","",$concepto['cantidad']),5);
			
			//Crear la Sentencia SQL para Alamcenar los materiales agregados 
			$stm_sql = "INSERT INTO materiales_de_mezclas (mezclas_id_mezcla, catalogo_materiales_id_material, cantidad, unidad_medida, volumen)
			VALUES ('$idMezcla', '$concepto[claveMat]', $cant, '$concepto[unidad]', 1)";
			//Aqui el Volumen se colaca en 1 ya que es la medida base, pero puede cambiarse por una variable en el caso de que la mezcla que esta siendo registrada de como
			//mas de 1 metro cubico.
			
			
			//Ejecutar la Sentencia 
			$rs=mysql_query($stm_sql);
			
			//Verificar Resultado
			if ($rs){
				$band=1;
			}
			else{
				$band=0;
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
				//liberar los datos del arreglo de sesion
				unset($_SESSION['materiales']);
			}
		}// Fin foreach($_SESSION['materiales'] as $ind => $concepto)
		if($band==1){
			//Guardar la operacion realizada
			registrarOperacion("bd_laboratorio",$idMezcla,"AgregarMatMezcla",$_SESSION['usr_reg']);
			$conn = conecta("bd_laboratorio");
			unset($_SESSION['materiales']);
			//echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}		
	}//Cierre de la function guardarMateriales()


?>