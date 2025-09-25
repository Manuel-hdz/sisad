<?php
	/**
	  * Nombre del Módulo: Laboratorio
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 18/Junio/2011
	  * Descripción: Este archivo contiene funciones para Consultar una Prueba de la BD de Laboratorio y poder Modificarla
	**/

	/*
		Valores de Patron
		1 => Busqueda por Tipo de Prueba
		2 => Busqueda por Norma de Prueba
	*/
	//Funcion que muestra las Pruebas segun el Patron de Busqueda
	function mostrarPruebas($criterio,$patron){
		if ($patron==1){
			//Creamos la sentencia SQL para mostrar los datos de la Prueba segun el TIPO
			$stm_sql="SELECT * FROM catalogo_pruebas WHERE tipo='$criterio' AND estado='1'";	
			//Creamos el titulo de la tabla
			$titulo="Pruebas de Tipo <em>$criterio</em>";
		}
		if ($patron==2){
			//Creamos la sentencia SQL para mostrar los datos de la Prueba segun la NORMA
			$stm_sql="SELECT * FROM catalogo_pruebas WHERE norma='$criterio' AND estado='1'";	
			if ($criterio!="N/A")
				//Creamos el titulo de la tabla
				$titulo="Pruebas de la Norma <em>$criterio</em>";
			else
				//Creamos el titulo de la tabla
				$titulo="Pruebas Sin Norma de Referencia";
		}
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_laboratorio");
			
		//Ejecutamos la sentencia SQL
		$rs=mysql_query($stm_sql);
		
		if ($datos=mysql_fetch_array($rs)){
			echo "<br />";
			echo "<table class='tabla_frm' cellpadding='5' width='100%'>";
			echo "<caption class='titulo_etiqueta'>$titulo</caption>";
			echo "<br />";
			echo "	<tr>
						<td class='nombres_columnas' align='center'>SELECCIONAR</td>
						<td class='nombres_columnas' align='center'>NORMA</td>
						<td class='nombres_columnas' align='center'>TIPO</td>
						<td class='nombres_columnas' align='center'>NOMBRE</td>
						<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{										
				echo "	<tr>
							<td class='nombres_filas' align='center'><input type='radio' name='rdb_id' id='rdb_id' value='$datos[id_prueba]'/></td>			
							<td class='nombres_filas' align='center'>$datos[norma]</td>
							<td class='$nom_clase' align='left'>$datos[tipo]</td>
							<td class='$nom_clase' align='left'>$datos[nombre]</td>
							<td class='$nom_clase' align='center'>$datos[descripcion]</td>
						</tr>";			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs)); 
			echo "</table>";
		}
		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);
	}//Fin de la funcion de mostrarPruebas
	
	//Funcion que muestra la tabla con los datos de la Prueba para poder visualizarlos y modificarlos
	function mostrarPruebaDetalle(){
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_laboratorio");
		//Obtener mediante una consulta todos los datos a mostrar
		$stm_sql="SELECT * FROM catalogo_pruebas WHERE id_prueba='$_POST[rdb_id]'";
		$rs=mysql_query($stm_sql);
		$datos=mysql_fetch_array($rs);
		?>
			<fieldset class="borde_seccion" id="tabla-modificarPrueba">
			<legend class="titulo_etiqueta">Modificar Prueba</legend>	
			<br>
			<form name="frm_modificarPrueba" method="post" action="frm_modificarPrueba.php" onsubmit="return valFormModificarPrueba(this);">
			<table width="900" height="336" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<input type="hidden" name="hdn_id" id="hdn_id" value="<?php echo $datos["id_prueba"];?>"/>
					<td width="125"><div align="right">**Norma de Prueba</div></td>
					<td width="213"><input name="txt_norma" id="txt_norma" type="text" class="caja_de_texto" size="40" maxlength="40" onkeypress="return permite(event,'num_car', 0);" value="<?php echo $datos["norma"];?>"/></td>
					<td><div align="right">*Nombre de Prueba</div></td>
					<td><input name="txt_nombre" id="txt_nombre" type="text" class="caja_de_texto" size="40" maxlength="40" onkeypress="return permite(event,'num_car', 0);" value="<?php echo $datos["nombre"];?>"/></tr>
				</tr>
				<tr>
					<td><div align="right">*Tipo de Prueba</div></td>
					<td><?php 
						$grupo=cargarComboEspecifico("cmb_tipo","tipo","catalogo_pruebas","bd_laboratorio","1","estado","Tipo",$datos["tipo"]); 
						if($grupo==0){ 
							echo "<label class='msje_correcto'>Es Necesario Agregar Nuevo Tipo</label>";
						}?>
					</td>
					<td><div align="right"><input type="checkbox" name="ckb_nuevoTipo" id="ckb_nuevoTipo" onclick="nuevoTipo();"/>Agregar Tipo</div></td>
					<td><input name="txt_nuevoTipo" id="txt_nuevoTipo" type="text" class="caja_de_texto" size="50" disabled="disabled"/>
					</td>
				</tr>
				<tr>
					<td><div align="right">Descripci&oacute;n</div></td>
					<td colspan="3"><textarea name="txa_descripcion" id="txa_descripcion" class="caja_de_texto" cols="60" rows="3" maxlength="120" onkeyup="return ismaxlength(this)"><?php echo $datos["descripcion"];?></textarea>
					</td>
				</tr>
				<tr>
					<td colspan="4">
						<strong>* Los campos marcados con asterisco son <u>obligatorios</u>.</strong><br />
						<strong>**Se recomienda asignar la Norma de Referencia de la Prueba, en caso de no asignarla, el sistema asignará <u>N/A</u> autom&aacute;ticamente.</strong>
					</td></tr>
				<tr>
					<td colspan="6">
						<div align="center">       	    	
							<input name="sbt_modificar" type="submit" class="botones"  value="Modificar" title="Guardar los Datos" onMouseOver="window.status='';return true"/>
							&nbsp;&nbsp;&nbsp;
							<input name="rst_restablecer" type="reset" class="botones"  value="Restablecer" title="Restablecer Formulario" 
							onMouseOver="window.status='';return true" onclick="cmb_tipo.disabled=false;ckb_nuevoTipo.checked=false;txt_nuevoTipo.disabled=true;txt_nuevoTipo.readOnly=false;"/>
							&nbsp;&nbsp;&nbsp;
							<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Pruebas" 
							onMouseOver="window.status='';return true" onclick="confirmarSalida('frm_modificarPrueba.php')" />
						</div>
					</td>
				</tr>
			</table>
			</form>
			</fieldset>
		<?php
	}//Fin de la funcion mostrarPruebaDetalle
	
	//Funcion que modifica los datos en las tabla de Catalogo de Pruebas
	function modificarPrueba(){
		$conn=conecta("bd_laboratorio");
		//Convertir los caracteres de los campos de texto en Mayúsculas
		$txt_norma = strtoupper($_POST["txt_norma"]);
		//Si no se ha incluido la norma, asignamos el valor N/A automaticamente
		if ($txt_norma=="")
			$txt_norma="N/A";
		//Definir de donde se obtendra el valor de la categoria o tipo
		$txt_tipo="";
		//Si se encuentra definido un nuevo tipo, tomar el valor de la caja de texto correspondiente
		if (isset($_POST["txt_nuevoTipo"]))
			$txt_tipo=strtoupper($_POST["txt_nuevoTipo"]);
		else
			$txt_tipo=$_POST["cmb_tipo"];
		$txa_descripcion = strtoupper($_POST["txa_descripcion"]);
		
		//Crear sentencia SQL para actualizar los datos
		$stm_sql="UPDATE catalogo_pruebas SET norma='$txt_norma', tipo='$txt_tipo', descripcion='$txa_descripcion' WHERE id_prueba='$_POST[hdn_id]'";
		
		//Ejecutar la sentencia previamente creada		
		$rs = mysql_query($stm_sql);									
									
		//Confirmar que la insercion de datos fue realizada con exito.
		if($rs){
			//registrarOperacion("bd_laboratorio",$_POST['hdn_id'],"ModificarPrueba",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			$error = mysql_error();
			mysql_close($conn);
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";
		}
	}//Fin de la funcion modificarPrueba
?>