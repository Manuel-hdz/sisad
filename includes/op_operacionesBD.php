<?php
	/**
	  * Nombre del Módulo: Sistema de Gestion Empresarial, Produccion y Operacion
	  * Nombre Programador: Miguel Angel Garay Castro                            
	  * Fecha: 30/Septiembre/2010                                      			
	  * Descripción: Este archivo contiene funciones para obtener datos de la BD para cargarlos en diferentes componentes de HTML
	  **/	 	
	  
	 
	//Esta función se encarga de consultar la BD en busqueda de la información establecida en los parametros y regresarla en un ComboBox  
	function cargarCombo($nom_combo,$nom_campo,$nom_tabla,$nom_bd,$msj,$valSeleccionado){
		/*
		//$nom_combo		-> Nombre del Combo a definir
		//$nom_campo		-> Nombre del campo del cual se cargaran los datos
		//$nom_tabla		-> Nombre de la tabla donde hacer la consulta
		//$nom_bd			-> Nombre de la BD donde conectarse para buscar
		//$msj				-> Etiqueta que aparecera al inicio del ComboBox
		//$valSeleccionado	-> Valor por defecto que se desea apaezca como seleccionado
		*/
		
		//Conectarse con la BD indicada
		$conn = conecta("$nom_bd");		
		
		$stm_sql = "SELECT DISTINCT $nom_campo FROM $nom_tabla WHERE $nom_campo!='' ORDER BY $nom_campo";
		$rs = mysql_query($stm_sql);
		//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
		if($datos = mysql_fetch_array($rs)){			
			//Declarar el ComboBox con el nombre especificado en el parametro $nom_combo
			echo "<select name='$nom_combo' id='$nom_combo' class='combo_box'>";
			//Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
			echo "<option value=''>$msj</option>";
			do{
				if($datos[$nom_campo]==$valSeleccionado){//Colocar el valor preseleccionado
					if(substr($nom_campo,0,5)=="fecha")//Desplegar la Fecha en formato dd/mm/aaaa 24/12/2000
						echo "<option value='$datos[$nom_campo]'selected='selected'>".modFecha($datos[$nom_campo],1)."</option>";					
					else
						echo "<option value='$datos[$nom_campo]'selected='selected'>$datos[$nom_campo]</option>";					
				}
				else{
					if(substr($nom_campo,0,5)=="fecha")//Desplegar la Fecha en formato dd/mm/aaaa 24/12/2000
						echo "<option value='$datos[$nom_campo]'>".modFecha($datos[$nom_campo],1)."</option>";
					else
						echo "<option value='$datos[$nom_campo]'>$datos[$nom_campo]</option>";
				}
			}while($datos = mysql_fetch_array($rs));
			echo "</select>";	
			//Regresar 1, si la sentencia si regresa valores
			return 1;		
		}
		else{
			//Regresar 0, si la sentencia no regresa ningun valor
			return 0;
		}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);	
	}//Fin de la funcion cargarCombo($nom_combo,$nom_campo,$nom_tabla,$msj,$valSelecionado)
	
	//Esta función se encarga de consultar la BD en busqueda de la información establecida en los parametros y regresarla en un ComboBox  
	function cargarComboAreas($nom_combo,$nom_campo,$nom_tabla,$nom_bd,$msj,$valSeleccionado){
		/*
		//$nom_combo		-> Nombre del Combo a definir
		//$nom_campo		-> Nombre del campo del cual se cargaran los datos
		//$nom_tabla		-> Nombre de la tabla donde hacer la consulta
		//$nom_bd			-> Nombre de la BD donde conectarse para buscar
		//$msj				-> Etiqueta que aparecera al inicio del ComboBox
		//$valSeleccionado	-> Valor por defecto que se desea apaezca como seleccionado
		*/
		
		//Conectarse con la BD indicada
		$conn = conecta("$nom_bd");		
		
		$stm_sql = "SELECT DISTINCT $nom_campo FROM $nom_tabla WHERE $nom_campo!='' AND estado_actual = 'ALTA' ORDER BY $nom_campo";
		$rs = mysql_query($stm_sql);
		//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
		if($datos = mysql_fetch_array($rs)){			
			//Declarar el ComboBox con el nombre especificado en el parametro $nom_combo
			echo "<select name='$nom_combo' id='$nom_combo' class='combo_box'>";
			//Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
			echo "<option value=''>$msj</option>";
			do{
				if($datos[$nom_campo]==$valSeleccionado){//Colocar el valor preseleccionado
					if(substr($nom_campo,0,5)=="fecha")//Desplegar la Fecha en formato dd/mm/aaaa 24/12/2000
						echo "<option value='$datos[$nom_campo]'selected='selected'>".modFecha($datos[$nom_campo],1)."</option>";					
					else
						echo "<option value='$datos[$nom_campo]'selected='selected'>$datos[$nom_campo]</option>";					
				}
				else{
					if(substr($nom_campo,0,5)=="fecha")//Desplegar la Fecha en formato dd/mm/aaaa 24/12/2000
						echo "<option value='$datos[$nom_campo]'>".modFecha($datos[$nom_campo],1)."</option>";
					else
						echo "<option value='$datos[$nom_campo]'>$datos[$nom_campo]</option>";
				}
			}while($datos = mysql_fetch_array($rs));
			echo "</select>";	
			//Regresar 1, si la sentencia si regresa valores
			return 1;		
		}
		else{
			//Regresar 0, si la sentencia no regresa ningun valor
			return 0;
		}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);	
	}//Fin de la funcion cargarComboAreas($nom_combo,$nom_campo,$nom_tabla,$msj,$valSelecionado)
	
	//Esta función se encarga de consultar en la BD datos especificos y mostrarlos como un ComboBox
	function cargarComboEspecifico($nom_combo,$nom_campo,$nom_tabla,$nom_bd,$val_buscado,$columna_ref,$msj,$valSeleccionado){
		/*
		//$nom_combo		-> Nombre del Combo a definir
		//$nom_campo		-> Nombre del campo a desplegar
		//$nom_tabla		-> Nombre de la tabla donde hacer la consulta
		//$nom_bd			-> Nombre de la BD donde conectarse para buscar
		//$val_buscado		-> Nombre del valor que se busca
		//$columna_ref		-> Nombre de la columna que permitira mostrar los resultados
		//$msj				-> Mensaje a mostrarse de Inicio
		//$valSeleccionado	-> Valor por defecto que se desea apaezca como seleccionado
		*/

		//Defnir la conexión a la BD indicada
		$conn = conecta("$nom_bd");		
		
		$stm_sql = "SELECT DISTINCT $nom_campo FROM $nom_tabla WHERE $columna_ref='$val_buscado' ORDER BY $nom_campo";
		$rs = mysql_query($stm_sql);
		//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
		if($datos = mysql_fetch_array($rs)){			
			//Declarar el ComboBox con el nombre especificado en el parametro $nom_combo
			echo "<select name='$nom_combo' id='$nom_combo' class='combo_box'>";
			//Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
			echo "<option value=''>$msj</option>";
			do{
				if($datos[$nom_campo]==$valSeleccionado){//Colocar el valor preseleccionado
					if(substr($nom_campo,0,5)=="fecha")//Desplegar la Fecha en formato dd/mm/aaaa 24/12/2000
						echo "<option value='$datos[$nom_campo]'selected='selected'>".modFecha($datos[$nom_campo],1)."</option>";					
					else
						echo "<option value='$datos[$nom_campo]'selected='selected'>$datos[$nom_campo]</option>";					
				}
				else{
					if(substr($nom_campo,0,5)=="fecha")//Desplegar la Fecha en formato dd/mm/aaaa 24/12/2000
						echo "<option value='$datos[$nom_campo]'>".modFecha($datos[$nom_campo],1)."</option>";
					else
						echo "<option value='$datos[$nom_campo]'>$datos[$nom_campo]</option>";
				}
			}while($datos = mysql_fetch_array($rs));
			echo "</select>";	
			//Regresar 1, si la sentencia si regresa valores
			return 1;		
		}
		else{
			//Regresar 0, si la sentencia no regresa ningun valor
			return 0;
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);	
	}//Fin de la funcion cargarCombo($nom_combo,$nom_campo,$nom_tabla,$msj,$valSelecionado)
	
	
	//Esta función se encarga de consultar en la BD datos especificossin tomar en consideracion
	//un campo que buscamos evitar y cargar la info en un comboBox
	function cargarComboExcluyente($nom_combo,$nom_campo,$nom_tabla,$nom_bd,$val_buscado,$columna_ref,$msj,$valSeleccionado){
		/*
		//$nom_combo		-> Nombre del Combo a definir
		//$nom_campo		-> Nombre del campo a desplegar
		//$nom_tabla		-> Nombre de la tabla donde hacer la consulta
		//$nom_bd			-> Nombre de la BD donde conectarse para buscar
		//$val_buscado		-> Nombre del valor que se busca
		//$columna_ref		-> Nombre de la columna que permitira mostrar los resultados
		//$msj				-> Mensaje a mostrarse de Inicio
		//$valSeleccionado	-> Valor por defecto que se desea apaezca como seleccionado
		*/

		//Defnir la conexión a la BD indicada
		$conn = conecta("$nom_bd");		
		
		$stm_sql = "SELECT DISTINCT $nom_campo FROM $nom_tabla WHERE $columna_ref!='$val_buscado' ORDER BY $nom_campo";
		$rs = mysql_query($stm_sql);
		//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
		if($datos = mysql_fetch_array($rs)){			
			//Declarar el ComboBox con el nombre especificado en el parametro $nom_combo
			echo "<select name='$nom_combo' id='$nom_combo' class='combo_box'>";
			//Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
			echo "<option value=''>$msj</option>";
			do{
				if($datos[$nom_campo]==$valSeleccionado){//Colocar el valor preseleccionado
					if(substr($nom_campo,0,5)=="fecha")//Desplegar la Fecha en formato dd/mm/aaaa 24/12/2000
						echo "<option value='$datos[$nom_campo]'selected='selected'>".modFecha($datos[$nom_campo],1)."</option>";					
					else
						echo "<option value='$datos[$nom_campo]'selected='selected'>$datos[$nom_campo]</option>";					
				}
				else{
					if(substr($nom_campo,0,5)=="fecha")//Desplegar la Fecha en formato dd/mm/aaaa 24/12/2000
						echo "<option value='$datos[$nom_campo]'>".modFecha($datos[$nom_campo],1)."</option>";
					else
						echo "<option value='$datos[$nom_campo]'>$datos[$nom_campo]</option>";
				}
			}while($datos = mysql_fetch_array($rs));
			echo "</select>";	
			//Regresar 1, si la sentencia si regresa valores
			return 1;		
		}
		else{
			//Regresar 0, si la sentencia no regresa ningun valor
			return 0;
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);	
	}//Fin de la funcion cargarComboExcluyente($nom_combo,$nom_campo,$nom_tabla,$nom_bd,$val_buscado,$columna_ref,$msj,$valSeleccionado)
	
	
	//Esta funcion permite buscar resultados  una opcion a mostrar; así como dos condiciones
	function cargarComboBicondicional($nom_combo,$nom_campo,$nom_tabla,$nom_bd,$val_buscado,$columna_ref,$val_buscado2,$columna_ref2,$msj,$valSeleccionado, $opc_onchange){
		/*
		//$nom_combo		-> Nombre del Combo a definir
		//$nom_campo		-> Nombre del primer campo a desplegar
		//$nom_tabla		-> Nombre de la tabla donde hacer la consulta
		//$nom_bd			-> Nombre de la BD donde conectarse para buscar
		//$val_buscado		-> Nombre del primer valor que limita la busqueda
		//$columna_ref		-> Nombre de la primer columna que permitira mostrar los resultados
		//$val_buscado2		-> Nombre del segundo valor que limitará la busqueda
		//$columna_ref2		-> Nombre de la  segurnda columna que permitira mostrar los resultados
		//$msj				-> Mensaje a mostrarse de Inicio
		//$valSeleccionado	-> Valor por defecto que se desea apaezca como seleccionado
		//opc_onchange      -> Opcion que permitira cargar una funcion AJAX en el elemento onchange
		*/

		//Defnir la conexión a la BD indicada
		$conn = conecta("$nom_bd");		
		
		$stm_sql = "SELECT DISTINCT $nom_campo FROM $nom_tabla WHERE $columna_ref='$val_buscado' AND $columna_ref2='$val_buscado2' ORDER BY $nom_campo";
		$rs = mysql_query($stm_sql);
		//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
		if($datos = mysql_fetch_array($rs)){			
			//Declarar el ComboBox con el nombre especificado en el parametro $nom_combo
			echo "<select name='$nom_combo' id='$nom_combo' class='combo_box' onchange=\"$opc_onchange\">";
			//Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
			echo "<option value=''>$msj</option>";
			do{
				if($datos[$nom_campo]==$valSeleccionado){//Colocar el valor preseleccionado
					echo "<option value='$datos[$nom_campo]' selected='selected' title='$datos[$nom_campo]'>$datos[$nom_campo]</option>";
				}
				else{
					echo "<option value='$datos[$nom_campo]' title='$datos[$nom_campo]'>$datos[$nom_campo]</option>";
				}					
			}while($datos = mysql_fetch_array($rs));
			echo "</select>";	
			//Regresar 1, si la sentencia si regresa valores
			return 1;		
		}
		else{
			//Regresar 0, si la sentencia no regresa ningun valor
			return 0;
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);	
	}//Fin de la funcion cargarComboExcluyente($nom_combo,$nom_campo,$nom_tabla,$nom_bd,$val_buscado,$columna_ref,$msj,$valSeleccionado)
	
	
	
	
	
	//Esta función se encarga de consultar la BD en busqueda de la información establecida en los parametros y regresarla en un ComboBox  
	function cargarComboNombres($nom_combo,$nom_campo_nombre,$nom_campo_apepat,$nom_campo_apemat,$nom_tabla,$nom_bd,$msj,$opc,$nom_form,$valSeleccionado){
		//Conectarse con la BD indicada
		$conn = conecta("$nom_bd");		
		
		$stm_sql = "SELECT rfc_empleado,$nom_campo_nombre, $nom_campo_apepat, $nom_campo_apemat,area FROM $nom_tabla ORDER BY area,nombre";
		$rs = mysql_query($stm_sql);
		//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
		if($datos = mysql_fetch_array($rs)){			
			if ($opc==1)
				//Declarar el ComboBox con el nombre especificado en el parametro $nom_combo
				echo "<select name='$nom_combo' id='$nom_combo' onChange='javascript:document.$nom_form.submit();' class='combo_box'>";
			else
				//Declarar el ComboBox con el nombre especificado en el parametro $nom_combo
				echo "<select name='$nom_combo' id='$nom_combo' class='combo_box'>";
			//Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
			echo "<option value=''>$msj</option>";
			//Obtener el area inicial y desplegarla dentro del combo
			$area = $datos['area'];
			echo "
				<option value=''></option>
				<option value=''>----- $area -----</option>
				<option value=''></option>
			";
			do{
				//Verificar cuando se cambia de area y desplar el nombre dento del ComboBox
				if($area!=$datos['area']){
					$area = $datos['area'];
					echo "
						<option value=''></option>
						<option value=''>----- $area -----</option>
						<option value=''></option>
					";
				}
				if($datos['rfc_empleado']==$valSeleccionado)		
					echo "<option value='$datos[rfc_empleado]' selected='selected'>$datos[$nom_campo_nombre] $datos[$nom_campo_apepat] $datos[$nom_campo_apemat]</option>";
				else
					echo "<option value='$datos[rfc_empleado]'>$datos[$nom_campo_nombre] $datos[$nom_campo_apepat] $datos[$nom_campo_apemat]</option>";
			}while($datos = mysql_fetch_array($rs));
			echo "</select>";
			//Regresar 1, si la sentencia si regresa valores
			return 1;		
		}
		else{
			//Regresar 1, si la sentencia si regresa valores
			return 0;		
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);	
	}//Fin de la funcion cargarCombo($nom_combo,$nom_campo,$nom_tabla,$msj,$valSelecionado)		
	
	
	//Esta función se encarga de consultar la BD en busqueda de la información establecida en los parametros y regresarla en un ComboBox  
	function cargarComboRequisiciones($nom_combo,$nom_campo,$nom_tabla,$nom_bd,$msj,$valSeleccionado,$departamento,$fechaInicio,$fechaFin){
		//Conectarse con la BD indicada
		$conn = conecta("$nom_bd");		
		if ($fechaInicio!="" && $fechaFin!="")
			$stm_sql = "SELECT DISTINCT $nom_campo FROM $nom_tabla WHERE $nom_campo!='' AND $nom_campo LIKE '$departamento%' AND fecha_req>='".$fechaInicio."' AND fecha_req<='".$fechaFin."' ORDER BY $nom_campo";
		else
			$stm_sql = "SELECT DISTINCT $nom_campo FROM $nom_tabla WHERE $nom_campo!='' AND $nom_campo LIKE '$departamento%' ORDER BY $nom_campo";
		$rs = mysql_query($stm_sql);
		//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
		if($datos = mysql_fetch_array($rs)){			
			//Declarar el ComboBox con el nombre especificado en el parametro $nom_combo
			echo "<select name='$nom_combo' id='$nom_combo' class='combo_box'>";
			//Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
			echo "<option value=''>$msj</option>";
			do{
				if($datos[$nom_campo]==$valSeleccionado){
					echo "<option value='$datos[$nom_campo]'selected='selected'>$datos[$nom_campo]</option>";
				}
				else{
					echo "<option value='$datos[$nom_campo]'>$datos[$nom_campo]</option>";
				}
			}while($datos = mysql_fetch_array($rs));
			echo "</select>";			
			return 1;
		}
		else{
			return 0;
		}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);	
	}//Fin de la funcion cargarCombo($nom_combo,$nom_campo,$nom_tabla,$msj,$valSelecionado)
		
	
	//Esta función se encarga de consultar la BD en busqueda de la información establecida en los parametros y regresarla en un ComboBox  
	function cargarComboConId($nom_combo,$nom_campo,$nom_campoId,$nom_tabla,$nom_bd,$msj,$valSeleccionado,$paramOnChange){
		/*
		//$nom_combo		-> Nombre del Combo a definir
		//$nom_campo		-> Nombre del campo del cual se cargaran los datos
		//$nom_campoId		-> Nombre del campo Id que será mostrardo en el Tooltip de cada opcion cargada en el comboBox
		//$nom_tabla		-> Nombre de la tabla donde hacer la consulta
		//$nom_bd			-> Nombre de la BD donde conectarse para buscar
		//$msj				-> Etiqueta que aparecera al inicio del ComboBox
		//$valSeleccionado	-> Valor por defecto que se desea apaezca como seleccionado
		*/
		
		//Conectarse con la BD indicada
		$conn = conecta("$nom_bd");		
		
		//Crear la Sentencia SQL para obtener los datos para cargar el ComboBox
		$stm_sql = "SELECT DISTINCT $nom_campoId,$nom_campo FROM $nom_tabla WHERE $nom_campo!='' ORDER BY $nom_campo";
		//Ejecutar la Sentencia
		$rs = mysql_query($stm_sql);
		//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
		if($datos = mysql_fetch_array($rs)){			
			//Declarar el ComboBox con el nombre especificado en el parametro $nom_combo y el evento OnChange?>
			
			<select name="<?php echo $nom_combo;?>" id="<?php echo $nom_combo;?>" class="combo_box" onchange="<?php echo $paramOnChange;?>"><?php 
			
			//Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
			echo "<option value=''>$msj</option>";
			do{
				if($datos[$nom_campoId]==$valSeleccionado){//Colocar el valor preseleccionado
					echo "<option value='$datos[$nom_campoId]' selected='selected' title='$datos[$nom_campoId]'>$datos[$nom_campo]</option>";
				}
				else{
					echo "<option value='$datos[$nom_campoId]' title='$datos[$nom_campoId]'>$datos[$nom_campo]</option>";
				}
			}while($datos = mysql_fetch_array($rs));
			echo "</select>";	
			//Regresar 1, si la sentencia si regresa valores
			return 1;		
		}
		else{
			//Regresar 0, si la sentencia no regresa ningun valor
			return 0;
		}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);	
	}//Fin de la funcion cargarCombo($nom_combo,$nom_campo,$nom_tabla,$msj,$valSelecionado)	
	
	
	//Esta función se encarga de consultar la BD en busqueda de la información establecida en los parametros y regresarla en un ComboBox ordenada por un dato especifico
	function cargarComboOrdenado($nom_combo,$nom_campo,$nom_tabla,$nom_bd,$msj,$valSeleccionado,$campo_ordena){
		/*
		//$nom_combo		-> Nombre del Combo a definir
		//$nom_campo		-> Nombre del campo del cual se cargaran los datos
		//$nom_tabla		-> Nombre de la tabla donde hacer la consulta
		//$nom_bd			-> Nombre de la BD donde conectarse para buscar
		//$msj				-> Etiqueta que aparecera al inicio del ComboBox
		//$valSeleccionado	-> Valor por defecto que se desea apaezca como seleccionado
		//$campo_ordena	    -> Nombre del campo por el cual se quieren ordenar los datos cargados en el combo box
		*/
		
		//Conectarse con la BD indicada
		$conn = conecta("$nom_bd");		
		
		$stm_sql = "SELECT DISTINCT $nom_campo FROM $nom_tabla WHERE $nom_campo!='' ORDER BY $campo_ordena";
		$rs = mysql_query($stm_sql);
		//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
		if($datos = mysql_fetch_array($rs)){			
			//Declarar el ComboBox con el nombre especificado en el parametro $nom_combo
			echo "<select name='$nom_combo' id='$nom_combo' class='combo_box'>";
			//Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
			echo "<option value=''>$msj</option>";
			do{
				if($datos[$nom_campo]==$valSeleccionado){//Colocar el valor preseleccionado
					if(substr($nom_campo,0,5)=="fecha")//Desplegar la Fecha en formato dd/mm/aaaa 24/12/2000
						echo "<option value='$datos[$nom_campo]'selected='selected'>".modFecha($datos[$nom_campo],1)."</option>";					
					else
						echo "<option value='$datos[$nom_campo]'selected='selected'>$datos[$nom_campo]</option>";					
				}
				else{
					if(substr($nom_campo,0,5)=="fecha")//Desplegar la Fecha en formato dd/mm/aaaa 24/12/2000
						echo "<option value='$datos[$nom_campo]'>".modFecha($datos[$nom_campo],1)."</option>";
					else
						echo "<option value='$datos[$nom_campo]'>$datos[$nom_campo]</option>";
				}
			}while($datos = mysql_fetch_array($rs));
			echo "</select>";	
			//Regresar 1, si la sentencia si regresa valores
			return 1;		
		}
		else{
			//Regresar 0, si la sentencia no regresa ningun valor
			return 0;
		}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);	
	}//Fin de la funcion cargarComboOrdenado($nom_combo,$nom_campo,$nom_tabla,$nom_bd,$msj,$valSeleccionado,$campo_ordena)
	
	
	//Esta función se encarga de consultar la BD en busqueda de la información establecida en los parametros y regresarla en un ComboBox  
	function cargarComboTotal($nomCombo,$nomCampo,$nomCampoId,$nomTabla,$nomBD,$msjEtiqueta,$valSeleccionado,$paramOnChange,$campoOrdenar,$opcExtraVal,$opcExtraText){
		/*
		//$nomCombo			-> Nombre del Combo a definir
		//$nomCampo			-> Nombre del campo del cual se cargaran los datos
		//$nomCampoId		-> Nombre del campo Id que será mostrardo en el Tooltip de cada opcion cargada en el comboBox y en la propiedad "value"
		//$nomTabla			-> Nombre de la tabla donde hacer la consulta
		//$nomBD			-> Nombre de la BD donde conectarse para buscar
		//$msjEtiqueta		-> Etiqueta que aparecera al inicio del ComboBox
		//$valSeleccionado	-> Valor por defecto que se desea apaezca como seleccionado
		//$paramOnChange	-> Instrucciones que se desean agregar en el evento onChange del ComboBox
		//$campoOrdenar		-> Campo mediante el cual se ordenaran los datos que guardara el ComboBox
		//$opcExtraVal		-> Valor que se colocara en la propiedad "value" para una opcion adicional que se desea agregar en el Combo ademas de las Existentes en la BD
		//$opcExtraText		-> Valor que se colocara como texto para una opcion adicional que se desea agregar en el Combo ademas de las Existentes en la BD
		*/
		
		//Conectarse con la BD indicada
		$conn = conecta("$nomBD");
		
		//Crear la Sentencia SQL para obtener los datos para cargar el ComboBox
		$stm_sql = "SELECT DISTINCT $nomCampoId,$nomCampo FROM $nomTabla WHERE $nomCampo!='' ORDER BY $campoOrdenar";
		//Ejecutar la Sentencia
		$rs = mysql_query($stm_sql);
		//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
		if($datos = mysql_fetch_array($rs)){			
			//Declarar el ComboBox con el nombre especificado en el parametro $nom_combo y el evento OnChange?>			
			<select name="<?php echo $nomCombo;?>" id="<?php echo $nomCombo;?>" class="combo_box" onchange="<?php echo $paramOnChange;?>"><?php 
			
			//Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox?>
			<option value=""><?php echo $msjEtiqueta; ?></option><?php
			
			//Colocar la Opcion adicional en el caso de que los parametros $opcExtraVal y $opcExtraText sean diferentes de vacio
			if($opcExtraVal!="" && $opcExtraText!=""){?>
				<option value="<?php echo $opcExtraVal; ?>" title="<?php echo $opcExtraVal; ?>"><?php echo $opcExtraText; ?></option><?php
			}			
			
			//Colocar el resto de las ocpiones del ComboBox
			do{
				if($datos[$nomCampoId]==$valSeleccionado){//Colocar el valor preseleccionado?>
					<option value="<?php echo $datos[$nomCampoId];?>" selected="selected" title="<?php echo $datos[$nomCampoId];?>"><?php echo $datos[$nomCampo];?></option><?php
				}
				else{?>
					<option value="<?php echo $datos[$nomCampoId];?>" title="<?php echo $datos[$nomCampoId];?>"><?php echo $datos[$nomCampo];?></option><?php
				}
			}while($datos = mysql_fetch_array($rs));?>
			</select><?php			
			
			//Regresar 1, si la sentencia si regresa valores
			return 1;		
		}
		else{
			//Regresar 0, si la sentencia no regresa ningun valor
			return 0;
		}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);	
	}//Fin de la funcion cargarComboTotal($nomCombo,$nomCampo,$nomCampoId,$nomTabla,$nomBD,$msjEtiqueta,$valSeleccionado,$paramOnChange,$campoOrdenar,$opcExtraVal,$opcExtraText)
	
	
	//Esta funcion se encarga de obtener un dato especifico de una tabla especifica en la BD indicada
	function obtenerDato($nom_bd, $nom_tabla, $campo_bus, $param_bus, $dato_bus){
		//Conectarse con la BD indicada
		$conn = conecta("$nom_bd");
		
		$stm_sql = "SELECT $campo_bus FROM $nom_tabla WHERE $param_bus='$dato_bus'";
		$rs = mysql_query($stm_sql);
		if($datos = mysql_fetch_array($rs))		
			return $datos[0];
		else
			return "";

		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}//Fin de la funcion obtenerDato($nom_tabla, $campo_bus, $param_bus, $dato_bus)	
	
	
	//Esta funcion se encarga de obtener un dato especifico de una tabla especifica en la BD indicada mediante 2 clausulas "WHERE"
	function obtenerDatoBicondicional($nom_bd, $nom_tabla, $campo_bus, $param_bus, $dato_bus, $param_bus2, $dato_bus2){
		//Conectarse con la BD indicada
		$conn = conecta("$nom_bd");
		
		$stm_sql = "SELECT $campo_bus FROM $nom_tabla WHERE $param_bus='$dato_bus' AND $param_bus2='$dato_bus2'";
		$rs = mysql_query($stm_sql);
		if($datos = mysql_fetch_array($rs))		
			return $datos[0];
		else
			return "";

		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}//Fin de la funcion obtenerDato($nom_tabla, $campo_bus, $param_bus, $dato_bus)
	
	
	//Esta funcion se encarga  deobtener un valor entre dos fechas
	function obtenerDatoFechas($nom_bd, $nom_tabla, $campo_bus, $fechaIni, $fechaFin,  $fechaBusq){
		//Conectarse con la BD indicada
		$conn = conecta("$nom_bd");
		
		$stm_sql = "SELECT $campo_bus FROM $nom_tabla WHERE '$fechaBusq' BETWEEN $fechaIni AND $fechaFin";
		$rs = mysql_query($stm_sql);
		if($datos = mysql_fetch_array($rs))		
			return $datos[0];
		else
			return "";

		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}//Fin de la funcion obtenerDato($nom_tabla, $campo_bus, $param_bus, $dato_bus)
	
		
	//Registrar los movimiento realiados por el usuario
	function registrarOperacion($nom_bd,$id_operacion,$tipo_operacion,$nom_usuario){
		//Conectarse con la BD Indicada
		$conn = conecta("$nom_bd");		
		
		ini_set("date.timezone","America/Mexico_City");
		
		//Obtener la fecha y hora en la que se realiza la operacion
		$fecha = date("Y-m-d");
		$hora = date("H:i:s");
		//Crear la Sentencia SQL para ingresar los datos en la BD
		$stm_sql = "INSERT INTO bitacora_movimientos (id_operacion,tipo_operacion,usuario,fecha,hora) VALUES('$id_operacion','$tipo_operacion','$nom_usuario','$fecha','$hora')";
		//Ejecutar la Sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la función registrarOperacion($nom_bd,$id_operacion,$nom_usuario)
	
	//Registrar los movimiento realiados por el usuario
	function registrarOperacionAut($nom_bd,$id_operacion,$tipo_operacion,$nom_usuario){
		//Conectarse con la BD Indicada
		$conn = conecta("$nom_bd");		
		
		ini_set("date.timezone","America/Mexico_City");
		
		//Obtener la fecha y hora en la que se realiza la operacion
		$fecha = date("Y-m-d");
		$hora = date("H:i:s");
		//Crear la Sentencia SQL para ingresar los datos en la BD
		$stm_sql = "INSERT INTO bitacora_movimientos (id_operacion,tipo_operacion,usuario,fecha,hora) VALUES('$id_operacion','$tipo_operacion','$nom_usuario','$fecha','$hora')";
		//Ejecutar la Sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Cerrar la conexion con la BD		
		//mysql_close($conn);		
	}//Fin de la función registrarOperacionAut($nom_bd,$id_operacion,$nom_usuario)
	
	
	/*Esta funcion remueve del catalogo los ultimos materiales agregado cuando el proceso de registro no se llevo a cabo con exito*/
	function deshacerCambios($clavesRegistradasMat){
		//Conectarse con la BD de Almacen
		$conn = conecta("bd_almacen");	
		foreach($clavesRegistradasMat as $ind => $clave){
			if (!isset($_SESSION["clavesModificadasExistencia"])){
				//Borrar el material de la tabla de Materiales
				mysql_query("DELETE FROM materiales WHERE id_material='$clave'");
				//Borrar el Material de la tabla de Unidad de Medida
				mysql_query("DELETE FROM unidad_medida WHERE materiales_id_material='$clave'");
			}
			else{//Proceso para cuando un Material esta en el Stock y se le incremento su existencia
				//clavesModificadasExistenciaCantidad	<=	Array de Cantidades a descontar de Materiales en Stock
				//clavesModificadasExistencia			<=	Array de Claves a las que se debe descontar el Material
				foreach($_SESSION["clavesModificadasExistencia"] as $ind2 => $clave2){
					if ($clave2==$clave){
						//Recuperar la cantidad a reducir de Materiales en Almacen
						$cant=$_SESSION["clavesModificadasExistenciaCantidad"][$clave2];
						//Sentencia SQL que reduce la existencia en la cantidad especificada
						mysql_query("UPDATE materiales SET existencia=existencia-$cant WHERE id_material='$clave2'");
						//Quitar el par de Arreglos de Sesion
						unset($_SESSION["clavesModificadasExistencia"][$ind2]);
						unset($_SESSION["clavesModificadasExistenciaCantidad"][$clave2]);
					}
				}
			}
		}
		//En caso que siga existiendo Borrar el Arreglo con las Cantidades modificadas
		if (isset($_SESSION["clavesModificadasExistencia"])){
			if (count($_SESSION["clavesModificadasExistencia"])==0)
				unset($_SESSION["clavesModificadasExistencia"]);
		}
		//En caso que siga existiendo Borrar el Arreglo con las Cantidades modificadas
		if (isset($_SESSION["clavesModificadasExistenciaCantidad"])){
			if (count($_SESSION["clavesModificadasExistenciaCantidad"])==0)
				unset($_SESSION["clavesModificadasExistenciaCantidad"]);
		}
		//Vaciar la Informacion almacenada en la SESSION cuando el proceso de registro de nuevos materiales fue terminado con exito
		unset($_SESSION['procesoRegistroMat']);
		unset($_SESSION['clavesRegistradasMat']);
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);	
		
	}
	
	
	/*Esta funcion carga el porcentaje del IVA registrado en la BD a la SESSION Actual*/
	function cargarIVA(){
		//Conectarse con la BD de Almacen
		$conn = conecta("bd_compras");	
		
		$rs = mysql_query("SELECT porcentaje FROM impuestos WHERE nom_impuesto='iva'");
		if($datos=mysql_fetch_array($rs)){
			$_SESSION['porcentajeIVA'] = intval($datos['porcentaje']);
		}
				
		//Cerrar la conexion con la BD		
		mysql_close($conn);	
	}
 
 
 	/*Esta funcion regresa el nombre de los trabajadores concatenado en forma Nombre Apellido_Paterno Apellido Materno*/
	function obtenerNombreEmpleado($rfc_empleado){
		$conn=conecta("bd_recursos");
		$stm_sql="SELECT CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombreEmpleado FROM empleados WHERE rfc_empleado='$rfc_empleado'";
		$rs=mysql_query($stm_sql);
		$datos=mysql_fetch_array($rs);
		return $datos["nombreEmpleado"];
		mysql_close($conn);
	}
	
	
	/*Esta funcion regresa el nombre de los trabajadores concatenado en forma Nombre Apellido_Paterno Apellido Materno*/
	function obtenerDatoEmpleadoPorNombre($dato,$nombre){
		$conn=conecta("bd_recursos");
		$stm_sql="SELECT $dato FROM empleados WHERE CONCAT(nombre,' ',ape_pat,' ',ape_mat)='$nombre'";
		$rs=mysql_query($stm_sql);
		$datos=mysql_fetch_array($rs);
		return $datos["0"];
		mysql_close($conn);
	}
	
	/*Esta funcion regresa el domicilio de los trabajadores concatenado en forma Calle  Num_Ext Colonia Localidad*/
	function obtenerDomicilioEmpleado($nombre){
		$conn=conecta("bd_recursos");
		$stm_sql="SELECT CONCAT(calle,' ',num_ext,' ',colonia,' ',localidad) AS domicilioEmpleado FROM empleados WHERE CONCAT(nombre,' ',ape_pat,' ',ape_mat)='$nombre'";
		$rs=mysql_query($stm_sql);
		$datos=mysql_fetch_array($rs);
		return $datos["domicilioEmpleado"];
		mysql_close($conn);
	}
	
	/*Esta funcion carga el combo de los Años disponibles para las estimaciones y traspaleos en Topografia
	se coloca en los includes generales para poder ser accesado por Direccion General*/
	function cargarComboTopografia($nom_combo,$tabla,$msj){
		//Conectarse con la BD indicada
		$conn = conecta("bd_topografia");		
		
		$stm_sql = "SELECT DISTINCT(SUBSTRING($tabla.no_quincena,-4)) AS anio FROM $tabla ORDER BY anio";
		$rs = mysql_query($stm_sql);
		//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
		if($datos = mysql_fetch_array($rs)){
			//Declarar el ComboBox con el nombre especificado en el parametro $nom_combo
			echo "<select name='$nom_combo' id='$nom_combo' class='combo_box'>";
			//Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
			echo "<option value=''>$msj</option>";
			do{
				if($datos["anio"]==date("Y"))//Desplegar la Fecha en formato dd/mm/aaaa 24/12/2000
					echo "<option value='$datos[anio]'selected='selected'>$datos[anio]</option>";					
				else
					echo "<option value='$datos[anio]'selected='selected'>$datos[anio]</option>";					
			}while($datos = mysql_fetch_array($rs));
			echo "</select>";	
			//Regresar 1, si la sentencia si regresa valores
			return 1;
		}
		else{
			//Regresar 0, si la sentencia no regresa ningun valor
			return 0;
		}
		//Cerrar la conexion con la BD
		mysql_close($conn);	
	}


	/*Esta funcion respalda la informacion de una Tabla en Otra dentro de la misma Base de Datos, solo con el postfijo _backup P.E. "requisiciones_backup"*/
	function respaldaTabla($bd,$tabla){
		//Arreglo que contendra los nombres de las columnas de la Tabla
		$columnas=array();
		//Abrir la conexion con la Base de Datos
		$conn=conecta($bd);
		//Sentencia SQL para traer la informacion de la Tabla y el nombre de las columnas
		$stm_sql="SELECT * FROM $tabla";
		//Ejecutar la consulta
		$rs = mysql_query($stm_sql);
		//Variable que obtiene la cantidad de Registros Obtenidos tras la consulta
		$veces=mysql_num_rows($rs);
		//Verificar si se encontraron resultados segun la consulta
		if($resp=mysql_fetch_array($rs)){
			//Arreglo que contendra el valor del Registro segun la columna
			$cols=array();
			//Recorrer los Registros obtenidos
			do{
				//Obtener el nombre de las Columnas en un Arreglo
				$columnas=array_keys($resp);
				//Variable contador inicializada en 1 cada vez a fin de obtener los nombres de las columnas
				$cont=1;
				//Recorrer el arreglo de resultados para 1 registro y asi obtener el nombre de las columnas
				do{
					//Verificar el contador para averiguar si la posicion es un numero par o impar
					if($cont%2!=0){
						//Recoger el nombre de la columna
						$indice=$columnas[$cont];
						//Obtener el valor del registro que hay para la columna seleccionada
						$cols[]=$resp[$indice];
					}
					//Incrementar el contador
					$cont++;
				}while($cont<count($columnas));
			}while($resp=mysql_fetch_array($rs));
			//Ejecutar sentencia SQL para eliminar la Tabla de Respaldo si es que ya existe
			$res=mysql_query("DROP TABLE IF EXISTS ".$tabla."_backup");
			//Sentencia SQL para obtener las propiedades de la Tabla seleccionada
			$stm_sql="DESCRIBE $tabla";
			//Ejecutar sentencia SQL creada anteriormente para obtener las propiedades de la Tabla
			$rs=mysql_query($stm_sql);
			//Comenzar a crear la sentencia para crear la tabla de Respaldo
			$sentencia="CREATE TABLE ".$tabla."_backup(";
			//Verificar los resultados de la sentencia de propiedades de la Tabla
			if($datos=mysql_fetch_array($rs)){
				//Variable que almacena la cantidad de resultados en el resultado
				$cantidad=0;
				//Recorrer los datos de la consulta DESCRIBE
				do{	
					//Incrementat el contador
					$cantidad++;
					//Concatenar a la sentencia CREATE el nombre del Campo y el Tipo
					$sentencia.="$datos[Field] $datos[Type],";
				}while($datos=mysql_fetch_array($rs));
			}
			//Obtener el tamaño de la sentencia
			$tam=strlen($sentencia);
			//Obtener la cadena de la sentencia sin el ultimo caracter agregado, en este caso, una coma ','
			$sentencia=substr($sentencia,0,($tam-1));
			//Agregar al final de la sentencia el parentesis de Cierre
			$sentencia.=")";
			//Ejecutar la sentencia CREATE
			$rs=mysql_query($sentencia);
			//Verificar que la sentencia se haya ejecutado correctamente
			if ($rs){
				//Variable que permitira verificar el resultado de cada insercion de Datos
				$consulta=0;
				//Variable que permite controlar la cantidad de veces que se realiza el ciclo de obtencion de datos
				$bandera=0;
				//Variable que permite obtener el dato que corresponde a la posicion establecida
				$pos=0;
				//Ciclo para crear las sentencias de insercion
				do{
					//Crear la primera parte de la sentencia de insercion de datos
					$stm_sql="INSERT INTO ".$tabla."_backup VALUES(";
					//Inicializar esta variable en 0, para recorrrer el arreglo hasta la posicion de datos segun el numero de columnas
					$ctrl=0;
					//Ciclo para obtener uno a uno los valores a Insertar
					do{
						//Concatenar con la sentencia de Insercion el valor a ingresar
						$stm_sql.="'$cols[$pos]',";
						//Incrementar la variable de Ctrl
						$ctrl++;
						//Incrementar la posicion del apuntador
						$pos++;
					}while($ctrl<$cantidad);
					//Obtener el tamaño de la sentencia
					$tam=strlen($stm_sql);
					//Obtener la cadena de la sentencia sin el ultimo caracter agregado, en este caso, una coma ','
					$stm_sql=substr($stm_sql,0,($tam-1));
					//Agregar al final de la sentencia el parentesis de Cierre
					$stm_sql.=")";
					//Ejecutar la sentencia SQL de INSERT, recien creada
					$rs=mysql_query($stm_sql);
					//Si la inserción no se llevo a cabo correctamente, activar la variable consulta a 1
					if (!$rs)
						$consulta=1;
					//Incrementar la variable bandera para pasar al siguiente registro
					$bandera++;
				}while($bandera<$veces);
				//Si la variable consulta tiene valor de 0, el proceso se llevó a cabo exitosamente
				if ($consulta==0){
					//Cerrar la conexion a la BD
					mysql_close($conn);
					//Regresar "" en caso que se haya terminado correctamente
					return "";
				}
				else{
					//Cerrar la conexion a la BD
					mysql_close($conn);
					//Regresar el error en caso que se haya terminado con errores en la insercion
					return "Inserci&oacute;n: ".mysql_error;
				}
			}
			else{
				//Cerrar la conexion a la BD
				mysql_close($conn);
				//Regresar el error en caso que se haya terminado con errores en la creacion
				return "Creaci&oacute;n: ".mysql_error;
			}
		}
		else{
			//Cerrar la conexion a la BD
			mysql_close($conn);
			//Regresar el msje de no Resultados en caso que se pudiera generar
			return "No Hay Resultados";
		}
	}//Fin de Funcion respaldaTabla($bd,$tabla)
	
/****************************************************************************************************************************************************************/
/******************************************Estas Funciones No Realizan Operaciones Directamente en la Base de Datos**********************************************/
/****************************************************************************************************************************************************************/	
	
	
	/*Esta función verifica que no se duplique un registro dentro de un arreglo bidemensional (Arreglo de registros)
	 * TRUE: Cuando el registro proporcionado para buscar ya es registrado dentro del arreglo
	 * FALSE: Cuando no se encontro ninguna coincidencia.
	 */
	function verRegDuplicadoArr($arr,$campo_clave,$campo_ref){
		//Obtener la cantidad de Registros (arreglos) dentro del Arreglo Principal
		$tam = count($arr);		
		//Obtener el ultimo Registro (arreglo) del Arreglo
		$datos = $arr[$tam-1];
		//Comprobar si el dato proporcionado enta repetido o no en la ultima posicion
		if($datos[$campo_clave]==$campo_ref)
			return true;
		else 
			return false;
	}
	
	
	/*Esta función verifica que no se duplique un registro dentro de un arreglo, regresa:
	 * TRUE: Cuando el dato proporcionado ya se encuentra dentro del arreglo
	 * FALSE: Cuando no se encontro ninguna coincidencia.
	 */
	function valRegDuplicadoArr($arr,$datoBusq){
		$band = 0;
		//Recorrer el Arreglo
		foreach($arr as $key => $value){
			//Si el valor del registro actual es igual al dato que se esta buscando, activar la bandera
			if($value==$datoBusq)
				$band = 1;
		}
						
		//Si la bandera fue actividad el dato ya esta registrado en el arreglo
		if($band==1)
			return true;
		else
			return false;			
	}
	
	
	/*Esta funcion se encarga de sumar el subtotal de cada registro en la Entrada y Salida de Material*/
	function obtenerSumaRegistrosES($arrDatos,$campoClave){
		$total = 0;
		//Recorrer cada uno de los registros del arreglo
		foreach($arrDatos as $key => $registro){
			//Recorrer cada dato del registro
			foreach($registro as $clave => $valor){
				if($clave==$campoClave)
					$total += floatval($valor);
			}
		}
		//Regresar la Suma de los registros
		return $total;
	}	
	
	
	
	//Funcion que redimenciona imágenes
	/*
	/ $filename... Ubicación y nombre de la fotografía ya cargada
	/ $newfilename... Nuevo nombre del archivo o el mismo si no cambiará
	/ $path... Carpeta donde se guardará la nueva foto
	/ $newwidth... Nuevo ancho recomedable 100
	/ $newheight... Nuevo alto recomendable 100
	*/
	function redimensionarFoto($filename,$newfilename,$path,$newwidth,$newheight) {
	
		//SEARCHES IMAGE NAME STRING TO SELECT EXTENSION (EVERYTHING AFTER . )
		$image_type = strstr($filename, '.');
		
		//SWITCHES THE IMAGE CREATE FUNCTION BASED ON FILE EXTENSION
			switch($image_type) {
				case '.jpg':
					$source = imagecreatefromjpeg($filename);
					break;
				case '.JPG':
					$source = imagecreatefromjpeg($filename);
					break;
				case '.jpeg':
					$source = imagecreatefromjpeg($filename);
					break;
				case '.JPEG':
					$source = imagecreatefromjpeg($filename);
					break;
				case '.png':
					$source = imagecreatefrompng($filename);
					break;
				case '.PNG':
					$source = imagecreatefromjpeg($filename);
					break;
				case '.gif':
					$source = imagecreatefromgif($filename);
					break;
				case '.GIF':
					$source = imagecreatefromjpeg($filename);
					break;
				default:
					echo("Error Invalid Image Type");
					die;
					break;
			}
		
		//CREATES THE NAME OF THE SAVED FILE
		$file = $newfilename;
		
		//CREATES THE PATH TO THE SAVED FILE
		$fullpath = $path . $file;
		
		//FINDS SIZE OF THE OLD FILE
		list($width, $height) = getimagesize($filename);
		
		//CREATES IMAGE WITH NEW SIZES
		$thumb = imagecreatetruecolor($width, $height);
		
		//RESIZES OLD IMAGE TO NEW SIZES
		imagecopyresized($thumb, $source, 0, 0, 0, 0, $width, $height, $width, $height);
		
		//SAVES IMAGE AND SETS QUALITY || NUMERICAL VALUE = QUALITY ON SCALE OF 1-100
		imagejpeg($thumb, $fullpath, 60);
		
		//CREATING FILENAME TO WRITE TO DATABSE
		$filepath = $fullpath;
		
		//RETURNS FULL FILEPATH OF IMAGE ENDS FUNCTION
		return $filepath;
	}
	
	
	/***********************************************************************************************************************************/
	/*****************************************************MANIPULACION DE NUMEROS*******************************************************/
	/***********************************************************************************************************************************/
	/*Esta funcion devuelve la cantidad de decimales de un numero dado*/
	function contarDecimales($numDecimal){
		//Dividir el numero por el punto decimal
		$partes = explode(".",$numDecimal);
		
		//Verificar si existe parte decimal
		$cantDecimales = 0;
		if(isset($partes[1])){
			//Contar la cantidad de caracteres de la parte decimal del numero dado
			$cantDecimales = strlen($partes[1]);
		}
		//Regresar la cantidad de decimales enoontrados
		return $cantDecimales;
	}//Cierre de la función contarDecimales($numDecimal)
	
	
	
	/* Esta función obtiene la cantidad de OTSE(Orden de Trabajo para Servicio Externo) que faltan por complementar, esta funcion solo se implementa en el archivo
	 * head_menu del Módulo de Compras */
	function obtenerNumOTSE(){
		
		//Conectarse con la Base de datos de Mantenimiento
		$conn = conecta("bd_mantenimiento");
		
		//Crear la Sentencia SQL para obtener las OTSE que falta complementar
		$sql_stm = "SELECT COUNT(id_orden) AS cant FROM orden_servicios_externos WHERE complementada = 'NO'";
		
		//Ejecutar la Sentencia
		$rs = mysql_query($sql_stm);
		
		if($datos=mysql_fetch_array($rs)){
			return $datos['cant'];//Regresar la cantidad de OTSE pendientes
		}
		else{
			return 0;//Regresar 0 en el caso de que no haya datos
		}
	}//Cierre de la función obtenerNumOTSE()
	
	
	
	/***********************************************************************************************************************************/
	/*****************************************************CONVERSION DE NUMEROS EN TEXTO*******************************************************/
	/***********************************************************************************************************************************/
		
	//Esta función convertirá un numero dado en su descripción textual, el numero será recibido sin formato con 2 decimales (13956890.45 => 13,956,890.45)
	function convierteNumeroLetra($numero){
		/* Quitar las posibles comas (,) que contenga el numero dado y dar formato al numero en el caso que tenga mas de 2 decimales o en el caso que no tenga, 
		 * los decimales serán redondeados a 2 digitos. Ej. 
		 * 		- 1,234.5689 	=> 1234.57
		 * 		- 2356 			=> 2356.00 */
		$numero = str_replace(",","",$numero);
		$numero = number_format($numero,2,".","");
		
				
		/* Separar el numero en las diferentes secciones que lo pueden componer, esta tabla hace referencia a la cantidad de digitos del numero dado, 
		 * contando de derecha a izquierda
		 * - 1-2 digitos 	=> decimales
		 * - 3 digito 		=> punto decimal
		 * - 4-6 digitos 	=> centenas
		 * - 7-9 digitos 	=> milesimas
		 * - 10-12 digitos	=> millonesimas*/
		 
		 
		 //Si el numero tiene mas de 9 digitos, extraemos la seccion de Millones
		$millones = "";
		if(strlen($numero)>9){
			$millones = strrev(substr(strrev($numero),9,3));
		}
		
		//Si el numero tiene mas de 6 digitos, extraemos la seccion de Miles
		$miles = "";
		if(strlen($numero)>6){
			$miles = strrev(substr(strrev($numero),6,3));
		}
		
		//El numero dado siempre contendra la sección de Cientos, extraemos los digitos de Centenas
		$cientos = strrev(substr(strrev($numero),3,3));
		
		//El numero dado siempre contendra la sección de decimales, extraemos los digitos de decimales
		$decimales = strrev(substr(strrev($numero),0,2));
					
		
		//Verificar si el numero contiene la sección de millones para convertir el numero en letras
		$cadMillones = "";
		if($millones!=""){
			$cadMillones = convierteCifra($millones,3);//Nivel 3 => Millones
		}
		
		//Verificar si el numero contiene la sección de miles para convertir el numero en letras
		$cadMiles = "";
		if($miles!=""){
			$cadMiles = convierteCifra($miles,2);//Nivel 2 => Miles
		}
		
		//Convertir la cantidad de cientos en letra
		$cadCientos = convierteCifra($cientos,1);//Nivel 1 => Cientos
		
		
		//Reunir todas las secciones para regresar el numero dedo en su descripción textual
		$numeroCadena = "";
		if($cadMillones!="")
			$numeroCadena .= $cadMillones." ";
		if($cadMiles!="")
			$numeroCadena .= $cadMiles." ";
		//Agregar la sección de cientos
		$numeroCadena .= $cadCientos." PESOS ".$decimales."/100 M.N.";
		
		//Retornar la descripción textual del numero dado		
		return $numeroCadena;
		
	}//Cierre de la función convierteNumeroLetra($numero)


	/*Esta función transformará el numero pasado por parametros a su representancion textual*/
	function convierteCifra($num, $nivel){
		//Variables para obtener las tres partes del numero proporcionado (Centenas, Decenas y Unidades)
		$centena = "";
		$decena = "";
		$unidad = "";
		
		//Obtener las Centenas, Decenas y Unidades cuando el numero es de 3 digitos
		if(strlen($num)==3){
			$centena = substr($num,0,1);			
			$decena = substr($num,1,1);
			$unidad = substr($num,2,1);
		}
		else if(strlen($num)==2){
			$decena = substr($num,0,1);
			$unidad = substr($num,1,1);
		}
		else if(strlen($num)==1){
			$unidad = $num;
		}
				
		//Variables para almacenar la descripción textual de las Centenas, Decenas y Unidades
		$cadCentena = "";
		$cadDecena = "";
		$cadUnidad = "";				

		//Procesar las CENTENAS, siempre y cuando existan en el numero proporcionado
		if($centena!=""){
			
			switch($centena){
				case "1":
					$cadCentena = "CIEN";
					if($decena>0 || $unidad>0)
						$cadCentena = "CIENTO";
				break;
				case "2":
					$cadCentena = "DOSCIENTOS";
				break;
				case "3":
					$cadCentena = "TRESCIENTOS";
				break;
				case "4":
					$cadCentena = "CUATROCIENTOS";
				break;				
				case "5":
					$cadCentena = "QUINIENTOS";
				break;
				case "6":
					$cadCentena = "SEISCIENTOS";
				break;
				case "7":
					$cadCentena = "SETECIENTOS";
				break;
				case "8":
					$cadCentena = "OCHOCIENTOS";
				break;
				case "9":
					$cadCentena = "NOVECIENTOS";
				break;
				
			}//Cierre switch($centena)
			
		}//Cierre if($centena!="")



		//Procesar las DECENAS, siempre y cuando existan en el numero proporcionado
		if($decena!=""){
			
			switch($decena){				
				case "1":
					$cadDecena = "DIEZ";
					switch($unidad){
						case "1":
							$cadDecena = "ONCE";
						break;
						case "2":
							$cadDecena = "DOCE";
						break;
						case "3":
							$cadDecena = "TRECE";
						break;
						case "4":
							$cadDecena = "CATORCE";
						break;
						case "5":
							$cadDecena = "QUINCE";
						break;
						case "6":
							$cadDecena = "DIECISEIS";
						break;
						case "7":
							$cadDecena = "DIECISIETE";
						break;
						case "8":
							$cadDecena = "DIECIOCHO";
						break;
						case "9";
							$cadDecena = "DIECINUEVE";
						break;
					}//Cierre switch($unidad)
				break;								
				case "2":
					$cadDecena = "VEINTE";
					if($unidad>0)
						$cadDecena = "VEINTI";
				break;
				case "3":
					$cadDecena = "TREINTA";
					if($unidad>0)
						$cadDecena = "TREINTA Y ";
				break;
				case "4":
					$cadDecena = "CUARENTA";
					if($unidad>0)
						$cadDecena = "CUARENTA Y ";
				break;
				case "5":
					$cadDecena = "CINCUENTA";
					if($unidad>0)
						$cadDecena = "CINCUENTA Y ";
				break;
				case "6":
					$cadDecena = "SESENTA";		
					if($unidad>0)
						$cadDecena = "SESENTA Y ";
				break;
				case "7":
					$cadDecena = "SETENTA";
					if($unidad>0)
						$cadDecena = "SETENTA Y ";
				break;
				case "8":
					$cadDecena = "OCHENTA";
					if($unidad>0)
						$cadDecena = "OCHENTA Y ";
				break;
				case "9":
					$cadDecena = "NOVENTA";
					if($unidad>0)
						$cadDecena = "NOVENTA Y ";
				break;			
			}//Cierre switch($decena)
			
		}//Cierre if($decena!="")
			
			
		//Procesar las UNIDADES, cuando las decenas no esten presentes o cuando estas sean mayores a 1 o sea menor a 1
		if($decena=="" || $decena>1 || $decena<1){
			switch($unidad){
				case "1":
					//Manejar el numero 1 como "UNO", cuando las centenas esten precentes, las decenas sean mayores a 1 y cuando el numero sea del Nivel 1 => Cientos
					if($centena!="" || $decena>1 || $nivel==1)
						$cadUnidad = "UNO";
					else
						$cadUnidad = "UN";
					
					//Si el Nivel es 2 (MILES), el numero 1 debe ser UN en lugar de UNO
					if($nivel==2)
						$cadUnidad = "UN";
					//Si el Nivel es 3 (MILLONES), el numero 1 debe ser UN en lugar de UNO
					if($nivel==3)
						$cadUnidad = "UN";
				break;
				case "2":
					$cadUnidad = "DOS";
				break;
				case "3":
					$cadUnidad = "TRES";
				break;
				case "4":
					$cadUnidad = "CUATRO";
				break;
				case "5":
					$cadUnidad = "CINCO";
				break;
				case "6":
					$cadUnidad = "SEIS";
				break;
				case "7":
					$cadUnidad = "SIETE";
				break;
				case "8":
					$cadUnidad = "OCHO";
				break;
				case "9":
					$cadUnidad = "NUEVE";
				break;
			}//Cierre switch($unidad)
			
		}//Cierre if($decena>1)
		
		
		//Armar la cifra final
		$numConvertido = $cadCentena." ".$cadDecena." ".$cadUnidad;
		
		//Quitar los espacios en blanco a la izquierda del inicio de la cadena
		$numConvertido = ltrim($numConvertido);
		
		//Agregar las leyendas MILLON/MILLONES y MIL, Nivel 1 => CIENTOS, Nivel 2 => MILES y Nivel 3 => MILLONES
		if($nivel==3){
			if($unidad>1 || $decena>0 || $centena>0)
				$numConvertido .= " MILLONES";
			else
				$numConvertido .= " MILLON";
		}
		else if($nivel==2){
			$numConvertido .= " MIL";
		}
		
		
		//Retornar la descripción textual del numero dado
		return $numConvertido;
		 
			
	}//Cierre de la función function convierteCifra($num, $nivel)
	
	
?>	