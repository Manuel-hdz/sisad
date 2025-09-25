/**
  * Nombre del Módulo: Topografía                                               
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 24/Mayo/2011                                      			
  * Descripción: Este archivo contiene funciones para validar los diferentes formularios del Módulo Topografía
  */
/*****************************************************************************************************************************************************************************************/
/************************************************************************VALIDAR CARACTERES***********************************************************************************************/
/*****************************************************************************************************************************************************************************************/
/*Esta función se encarga de que el usuario no pueda ingresar caracteres invalidos en los campos de los diferentes formulario del Módulo de Topografía*/
function permite(elEvento, permitidos, te) {
	//te = 0 ==> Teclas Especiales General, te = 1 ==> Teclas Especiales Restringidas, te = 2 ==> Teclas Especiales Completamente Restringidas
	//Variables que definen los caracteres permitidos
	var numeros = "0123456789";
	var caracteres = " abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ";
	var numeros_caracteres = numeros + caracteres;
	
	//Determinar que Teclas Especiales seran Permitidas segun el campo de texto que se este llenando
	if(te==0){//Campos mas generales como comentarios, observaciones y nombres		
		var teclas_especiales = [8,33,34,35,36,37,38,40,41,42,43,44,45,46,47,58,59,60,61,62,63,64,91,93,95,123,124,125,161,176,191];		
		//8=BackSpace, 33=Admiración Cierre, 34=Comillas, 35=Gato, 36=Signo Moneda, 37=Porcentaje, 38=Amperson, 40=Parentesis Apertura, 41=Parentesis Cierre, 42=Asterisco, 43=Simbolo Mas,
		//44=Coma, 45=Guion medio, 46=Punto, 47=Diagonal, 58=Dos Puntos, 59=Punto y Coma, 60=Menor Que, 61=Simbolo Igual, 62=Mayor Que, 63=Interrogacion Cierre, 64=Arroba, 91=Parentesis Cuad Apertura, 
		//93=Parentesis Cuad Cierre, 95=Guion Bajo, 123=Llave Apertura, 124=|, 125=Llave Cierre, 161=Admiracion Apertura, 176=°Grados, 191=Interregacion Aperura
	}
	if(te==1){//Campos que contengan claves que puedan contener guion medio, punto o diagonal
		var teclas_especiales = [8, 45, 46, 47];
		//8 = BackSpace, 45 = Guion medio, 46 = Punto, 47 = Diagonal
	}
	if(te==2){//Para cajas de texto que contengan valores tipo moneda, solo acepta numeros y el punto
		var teclas_especiales = [8, 46];		
		//8 = BackSpace, 46 = Punto
	}
	if(te==3){//Campo RFC, numero telefónico, solo acepta numeros o letras o ambos, no permite ningun caracter especial
		var teclas_especiales = [8];		
		//8 = BackSpace
	}
	if(te==4){//Campos que se utilizan para manejar la Busqueda Sphider, Razon Social del Cliente y del Proveedor y el campo de Material o Servicio del Proveedor
		var teclas_especiales = [8,33,35,36,37,38,40,41,42,43,44,45,46,47,58,59,60,61,62,63,64,91,93,95,123,124,125,161,176,191];		
		//8=BackSpace, 33=Admiración Cierre, 35=Gato, 36=Signo Moneda, 37=Porcentaje, 38=Amperson, 40=Parentesis Apertura, 41=Parentesis Cierre, 42=Asterisco, 43=Simbolo Mas,
		//44=Coma, 45=Guion medio, 46=Punto, 47=Diagonal, 58=Dos Puntos, 59=Punto y Coma, 60=Menor Que, 61=Simbolo Igual, 62=Mayor Que, 63=Interrogacion Cierre, 64=Arroba, 91=Parentesis Cuad Apertura, 
		//93=Parentesis Cuad Cierre, 95=Guion Bajo, 123=Llave Apertura, 124=|, 125=Llave Cierre, 161=Admiracion Apertura, 176=°Grados, 191=Interregacion Aperura
	}
	if(te==5){//Para cajas de texto que contengan valores tipo hora
		var teclas_especiales = [8, 58];		
		//8 = BackSpace, 58 = Dos Puntos
	}
	if(te==6){
		var teclas_especiales = [43, 45, 46, 47, 88, 120];
		//43=Signo de Mas, 45=Guion Medio, 46=Punto, 47=Diagonal, 88=letra X, 120=x;
	}
	
	// Seleccionar los caracteres a partir del parámetro de la función
	switch(permitidos) {
		case 'num':
			permitidos = numeros;
		break;
		case 'car':
			permitidos = caracteres;
		break;
		case 'num_car':
			permitidos = numeros_caracteres;
		break;
	}
	
	// Obtener la tecla pulsada
	var evento = elEvento || window.event;
	var codigoCaracter = evento.charCode || evento.keyCode;
	var caracter = String.fromCharCode(codigoCaracter);
	
	// Comprobar si la tecla pulsada es alguna de las teclas especiales
	// (teclas de borrado y flechas horizontales)
	var tecla_especial = false;
	for(var i in teclas_especiales) {
		if(codigoCaracter == teclas_especiales[i]) {
			tecla_especial = true;
			break;
		}
	}
	// Comprobar si la tecla pulsada se encuentra en los caracteres permitidos
	// o si es una tecla especial
	return permitidos.indexOf(caracter) != -1 || tecla_especial;
}


/*****************************************************************************************************************************************************************************************/
/************************************************************************FUNCIONES GENERALES**********************************************************************************************/
/*****************************************************************************************************************************************************************************************/
/*Esta función verifica que el dato proporcionado sea un numero valido y que a su vez este sea mayor que 0*/
function validarEntero(valor,campo){ 
	var cond = true;
	//Comprobar si es un valor numérico 
	if (isNaN(valor)) { 			
		//Numero invalido
		alert ("El Dato: '"+valor+"' es Incorrecto, Solo se Aceptan Numeros");
		cond = false;
	}
	//Comproba que el numero sea mayor que 0
	if(cond){
		if(valor<=0){
			//Numero invalido
			alert(campo+" Debe Ser Mayor a 0")
			cond = false;
		}
	}	
	return cond;
}

/*Esta función verifica que el dato proporcionado sea un numero valido en esta ocasion si puede ser de valor 0*/
function validarEnteroConCero(valor,campo){ 
	var cond = true;
	//Comprobar si es un valor numérico 
	if (isNaN(valor)) { 			
		//Numero invalido
		alert ("El Dato: '"+valor+"' es Incorrecto, Solo se Aceptan Numeros");
		cond = false;
	}	
	return cond;
}

/*Esta funcion solicita la confirmación del usuario antes de salir de la pagina*/
function confirmarSalida(pagina){
	if(confirm("¿Estas Seguro que Quieres Salir?\nToda la información no Guardada se Perderá"))
		location.href = pagina;	
}

//Esta funcion solicita al usuario el nuevo tipo y limpia el combo tipo
function agregarNuevoTipo(ckb_tipo, txt_tipo, cmb_tipo){
	//Si el checkbox para el nuevo tipo esta seleccionado, pedir el nombre de dicho tipo
	if (ckb_tipo.checked){
		var tipo = prompt("¿Nombre del Nuevo Tipo?","Nombre del Tipo...");	
		if(tipo!=null && tipo!="Nombre del Tipo..." && tipo!=""){
			//Asignar el valor obtenido a la caja de texto que lo mostrara
			document.getElementById(txt_tipo).value = tipo;
			//Verificar que el combo este definido para poder deshabilitarlo
			if (document.getElementById(cmb_tipo)!=null)
				//Deshabilitar el ComboBox para que el usuario no lo pueda modificar
				document.getElementById(cmb_tipo).disabled = true;				
		}
		else
			//Regresar False si se presiona el botón cancelar o se asigna un valor equivocado
			ckb_tipo.checked = false;
	}
	//Si el checkbox para nuevo tipo se deselecciona, borrar el dato escrito en la caja de texto y reactivar el combo de tipo
	else{
		document.getElementById(txt_tipo).value = "";
		//Verificar que el combo este definido para poder Habilitarlo
		if (document.getElementById(cmb_tipo)!=null)
			//Habilitar el ComboBox y el deseleccionar el CheckBox para que el usuario pueda agregar nueva información
			document.getElementById(cmb_tipo).disabled = false;				
	}
}

/***************************************************************************************************************************************************************/
/*****************************************COMIENZO DE FUNCIONES UTILIZADAS EN LAS PAGINAS***********************************************************************/
/***************************************************************************************************************************************************************/

/***************************************************************************************************************************************/
/*************************************************************GENERAR REQUISICION*******************************************************/
/***************************************************************************************************************************************/
/*Esta función valida que sea selecionada una Categoría y un Material, asi como la Cantida y la Aplicación para ser agregados a la Requisición*/
function valFormGenerarRequisicion(frm_generarRequisicion){
	//Si el valor se mantiene en 1, entonces el proceso de validacion fue satisfactorio
	var res = 1;		
	
	if(frm_generarRequisicion.cmb_material.value=="" && frm_generarRequisicion.txt_clave.value==""){
		alert("Seleccionar una Categoría y Después un Material o Ingresar Clave del Material para Registrar Requisicion");
		res = 0;
	}
	else{
		if(frm_generarRequisicion.txt_cantReq.value==""){
			alert("Introducir Cantidad del Material a Solicitar");
			res = 0;
		}
		else{
			if(!validarEntero(frm_generarRequisicion.txt_cantReq.value,"La Cantidad del Material")){
				res = 0;
			}
			else{
				if(frm_generarRequisicion.txt_aplicacionReq.value==""){
					alert("Introducir la Aplicación del Material que Esta Siendo Solicitado");
					res = 0;
				}
			}
		}
	}

	if(res==1)
		return true;
	else
		return false;
}


/*Esta funcion valida los datos del material agregado a la requisición, cuando éste no está registrado en el Catálodo de Almacén*/
function  valFormMaterialesRequisicion(frm_MaterialesRequisicion){
//Si el valor se mantiene en 1, entonces el proceso de validacion fue satisfactorio
	var res = 1;		
	
	if(frm_MaterialesRequisicion.txt_matReq.value==""){
		alert("Introducir Nombre del Material");
		res = 0;
	}
	else{
		if(frm_MaterialesRequisicion.txt_unidadMedida.value==""){
			alert("Introducir la Unidad de Medida");
			res = 0;
		}
		else{
			if(frm_MaterialesRequisicion.txt_cantReq2.value==""){
				alert("Introducir la Cantidad del Material a Solicitar");
				res = 0;
			}	
			else{
				if(!validarEntero(frm_MaterialesRequisicion.txt_cantReq2.value,"La Cantidad del Material")){
					res = 0;
				}
				else{
					if(frm_MaterialesRequisicion.txt_aplicacionReq2.value==""){
						alert("Introducir la Aplicación del Material que Esta Siendo Solicitado");
						res = 0;
					}
				}
			}
		}
	}
	
	if(res==1&&frm_MaterialesRequisicion.hdn_claveValida.value=="no"){
		alert("La Clave escrita pertenece a otro Material");
		res = 0;
	}
	
	if(res==1)
		return true;
	else
		return false;
}


/*Esta función valida la información complementaria de la Requisición que esta siendo generada*/
function valFormInformacionRequisicion(frm_InformacionRequisicion){
//Si el valor se mantiene en 1, entonces el proceso de validacion fue satisfactorio
	var res = 1;	
		
	
	if(frm_InformacionRequisicion.hdn_materialAgregado.value == "si"){						
		if(frm_InformacionRequisicion.txa_justificacionReq.value==""){
			alert("Introducir la Justificación del Material que Esta Siendo Solicitado");
			res = 0;
		}
		else{
			if(frm_InformacionRequisicion.txt_areaSolicitante.value==""){
				alert("Introducir el Área que Solicita el Material");
				res = 0;
			}
			else{
				if(frm_InformacionRequisicion.txt_solicitanteReq.value==""){
					alert("Introducir Nombre de la Persona que Solicita el Mateial");
					res = 0;
				}
				else{
					if(frm_InformacionRequisicion.cmb_prioridad.value==""){
						alert("Seleccionar la Prioridad");
						res = 0;
					}
					else{
						if(frm_InformacionRequisicion.txt_elaboradorReq.value==""){
						alert("Introducir el Nombre de la Persona que Elabora la Requisición");
						res = 0;
						}
					}
				}
			}
		}
	}
	else{
		alert("Al Menos se Debe Agregar un Material al Registro de la Requisicion");
		res = 0;
	}	
		
	
	if(res==1)
		return true;
	else
		return false;
}

//Funcion para validar que se haya ingresado una foto
function valFormFoto(frm_agregarFoto){
	var band=1;
	//Se verifica que el sistema haya sido ingresado
	if (frm_agregarFoto.file_documento.value==""){
		alert ("Introducir Fotografía");
		band=0;
	}
	
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}
/***************************************************************************************************************************************************************/
/*****************************************************FORMULARIO CONSULTAR REQUISICIONES************************************************************************/
/***************************************************************************************************************************************************************/

//Funcion que permite habilitar la area de texto de la descripcion
function activarBusqReq(checkbox){
	if(checkbox.checked){
		//Activar los campos de Buscar Por y Notas
		document.getElementById("cmb_buscarPor").disabled = false;
		document.getElementById("txa_notas").readOnly = false;
	}
	else{
		//Vaciar los datos de los campos de Buscar Por y Notas
		document.getElementById("cmb_buscarPor").value = "";
		document.getElementById("cmb_buscarPor").disabled = true;
		document.getElementById("txa_notas").value = "";
		document.getElementById("txa_notas").readOnly = true;
	}
}

/*Esta funcion valida que las fechas elegidas sean correctas*/
function valFormFechasReq(fechaIni, fechaFin){
	var res=1;
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=fechaIni.substr(0,2);
	var iniMes=fechaIni.substr(3,2);
	var iniAnio=fechaIni.substr(6,4);
	
	//Extraer los datos de la fecha de Cierre, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=fechaFin.substr(0,2);
	var finMes=fechaFin.substr(3,2);
	var finAnio=fechaFin.substr(6,4);
	
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaI=new Date(fechaIni);
	fechaF=new Date(fechaFin);

	//Verificar que el año de Fin sea mayor al de Inicio
	if(fechaI>fechaF){
		res=0;
		alert ("La fecha de Inicio no puede ser mayor a la Fecha de Cierre");
	}
	
	if(res==1)
		return true;
	else
		return false;
}
/**********************************************************************************************************************************************************/
/*****************************************************FORMULARIO GENERAR ESTIMACION************************************************************************/
/**********************************************************************************************************************************************************/
/*Esta función se encarga de validar el formulario de generar estimación*/

function valFormBuscarObra(frm_generarEstimacion){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	band = 1;

	//Verificar se haya seleccionado una obra en el combo
	if(frm_generarEstimacion.cmb_obra.value==""){
		alert("Seleccionar el Tipo de Obra");
		band = 0;
	}

	//Verificar se haya seleccionado una nombre de la obra en el combo
	if(frm_generarEstimacion.cmb_nomObra.value==""&& band==1){
		alert("Seleccionar el Nombre de la Obra");
		band = 0;
	}
		
	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}

function valFormBuscarObraSpider(frm_generarEstimacion){
	if(frm_generarEstimacion.txt_nombreObra.value==""){
		alert("Ingresar el Nombre de la Obra");
		return false;
	}
	else
		return true;
}

function valFormRegEstimacion(frm_generarEstimacion){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	band = 1;
	
	//Verificar que el campo de cantidad no este vacío
	if(frm_generarEstimacion.txt_cantidad.value=="" && band==1){
		alert("Introducir la Cantidad");
		band = 0;
	}
	
	if(band==1){
		if(!validarEntero(frm_generarEstimacion.txt_cantidad.value.replace(/,/g,''),"La Cantidad"))
			band = 0;		
	}

	//Verificar que el campo de tasa de cambio no este vacío
	if(frm_generarEstimacion.txt_tasaCambio.value=="" && band==1){
		alert("Introducir la Tasa de Cambio");
		band = 0;
	}
	
	if(band==1){
		if(!validarEntero(frm_generarEstimacion.txt_tasaCambio.value.replace(/,/g,''),"La Tasa de Cambio"))
			band = 0;		
	}
	
	//Verificar que el combo de numero de quincena no este vacío
	if(frm_generarEstimacion.cmb_noQuincena.value=="" && band==1){
		alert("Seleccionar el Número de la Quincena");
		band = 0;
	}
	//Verificar que el combo de mes  no este vacío
	if(frm_generarEstimacion.cmb_Mes.value=="" && band==1){
		alert("Seleccionar el Mes de la Quincena");
		band = 0;
	}
	//Verificar que el combo de año no este vacío
	if(frm_generarEstimacion.cmb_Anio.value=="" && band==1){
		alert("Seleccionar el Año de la Quincena");
		band = 0;
	}

	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}


/**********************************************************************************************************************************************************/
/*****************************************************FORMULARIO ELIMINAR ESTIMACION************************************************************************/
/**********************************************************************************************************************************************************/
/*Esta función se encarga de validar el formulario de eliminar estimación  por obra*/
function valFormEliminarEstpObra(frm_eliminarEstimacion){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	band = 1;
	
	//Verificar que combo de tipo de obra no este vacío
	if(frm_eliminarEstimacion.cmb_obra.value=="" && band==1){
		alert("Seleccionar el Tipo de Obra");
		band = 0;
	}

	//Verificar que combo de nombre de obra no este vacío
	if(frm_eliminarEstimacion.cmb_nombreObra.value=="" && band==1){
		alert("Seleccionar el Nombre de una Obra");
		band = 0;
	}

	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}

/*Esta función se encarga de validar el formulario de eliminar estimación por mes*/
function valFormEliminarEstMes(frm_eliminarEstimacion){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	band = 1;
	
	//Verificar que combo de mes no este vacío
	if(frm_eliminarEstimacion.cmb_mes.value=="" && band==1){
		alert("Seleccionar un Mes");
		band = 0;
	}

	//Verificar que combo de año no este vacío
	if(frm_eliminarEstimacion.cmb_anios.value=="" && band==1){
		alert("Seleccionar un Año");
		band = 0;
	}

	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}

/*Esta función se encarga de validar el formulario de eliminar estimación por quincena*/
function valFormEliminarEstQuin(frm_eliminarEstimacion){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	band = 1;
	
	//Verificar que combo de tipo de obra no este vacío
	if(frm_eliminarEstimacion.cmb_tipoObra.value=="" && band==1){
		alert("Seleccionar el Tipo de Obra");
		band = 0;
	}

	//Verificar que combo de nombre de obra no este vacío
	if(frm_eliminarEstimacion.cmb_nomObra.value=="" && band==1){
		alert("Seleccionar el Nombre de una Obra");
		band = 0;
	}

	//Verificar que combo de no quincena no este vacío
	if(frm_eliminarEstimacion.cmb_numQuincena.value=="" && band==1){
		alert("Seleccionar una Quincena");
		band = 0;
	}

	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}

/*Esta funcion se ecnarga de Validar el Formulario donde se selecciona la estimación para borrar*/
function valFormEliminarEst(frm_eliminarEst){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_eliminarEst.rdb.length==undefined && !frm_eliminarEst.rdb.checked){
		alert("Seleccionar la Estimación a Borrar");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_eliminarEst.rdb.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_eliminarEst.rdb.length;i++){
			if(frm_eliminarEst.rdb[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar la Estimación a Borrar");			
	}
	
	if (res==1){
		
		if (!confirm("¿Estas Seguro que Quieres Borrar la Estimación?\nToda la información relacionada se Borrará")){
			res=0;
		}
	}
	
	if(res==1)
		return true;
	else
		return false;
}

/**********************************************************************************************************************************************************/
/*****************************************************FORMULARIO CONSULTAR ESTIMACION************************************************************************/
/**********************************************************************************************************************************************************/
/*Esta función se encarga de validar el formulario de consultar estimación*/
function valFormConsultarEstimacion(frm_consultarEstimacion){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	band = 1;
	
	//verificar que la fecha de inicio no sea mayor que la fecha fin
	if(!valFormFechasReq(frm_consultarEstimacion) && band==1)
		band=0;
			
	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}


/**********************************************************************************************************************************************************/
/*****************************************************FORMULARIO MODIFICAR ESTIMACION************************************************************************/
/**********************************************************************************************************************************************************/
/*Esta función se encarga de validar el formulario de modificar estimación por quincena*/
function valFormModificarEstQuin(frm_modificarEstimacion){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	band = 1;
	

	//Verificar que combo de tipo de obra no este vacío
	if(frm_modificarEstimacion.cmb_tipoObra.value=="" && band==1){
		alert("Seleccionar el Tipo de Obra");
		band = 0;
	}

	//Verificar que combo de nombre de obra no este vacío
	if(frm_modificarEstimacion.cmb_nomObra.value=="" && band==1){
		alert("Seleccionar el Nombre de una Obra");
		band = 0;
	}

	//Verificar que combo de no quincena no este vacío
	if(frm_modificarEstimacion.cmb_numQuincena.value=="" && band==1){
		alert("Seleccionar una Quincena");
		band = 0;
	}

	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}

/*Esta función se encarga de validar el formulario de modificar estimación por mes*/
function valFormModificarEstMes(frm_modificarEstimacion){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	band = 1;
	

	//Verificar que combo de mes no este vacío
	if(frm_modificarEstimacion.cmb_mes.value=="" && band==1){
		alert("Seleccionar un Mes");
		band = 0;
	}

	//Verificar que combo de año no este vacío
	if(frm_modificarEstimacion.cmb_anios.value=="" && band==1){
		alert("Seleccionar un Año");
		band = 0;
	}

	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}

/*Esta funcion se ecnarga de Validar el Formulario donde se selecciona la estimación para modificar*/
function valFormModificarEst(frm_modificarEst){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_modificarEst.rdb.length==undefined && !frm_modificarEst.rdb.checked){
		alert("Seleccionar la Estimación a Modificar");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_modificarEst.rdb.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_modificarEst.rdb.length;i++){
			if(frm_modificarEst.rdb[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar la Estimación a Modificar");			
	}
	
	if(res==1)
		return true;
	else
		return false;
}



/***************************************************************************************************************************************************************/
/***********************************************************************PLANOS**********************************************************************************/
/***************************************************************************************************************************************************************/

/*********************************************************************REGISTRAR PLANOS**************************************************************************/

/*Esta función se encarga de validar el formulario de Registro de Planos*/
function valFormRegPlanos(frm_agregarPlano){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	band = 1;
	
	//Verificar que el campo de txt_nom_plano no este vacío
	if(frm_agregarPlano.txt_nomPlano.value==""){
		alert("Introducir el Nombre del Plano");
		band = 0;
	}
	//Verificar que el campo frm_agregarPlano no este vacío
	if(frm_agregarPlano.file_documento.value=="" && band==1){
		alert("Introducir el Plano a Registrar");
		band = 0;
	}
		
	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}


/*********************************************************************ELIMINAR PLANOS**************************************************************************/
/*Esta funcion valida que las fechas elegidas sean correctas*/
function valFormEliminarPlano(frm_eliminarPlanoFecha){
	var res=1;
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_eliminarPlanoFecha.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_eliminarPlanoFecha.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_eliminarPlanoFecha.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Cierre, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_eliminarPlanoFecha.txt_fechaFin.value.substr(0,2);
	var finMes=frm_eliminarPlanoFecha.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_eliminarPlanoFecha.txt_fechaFin.value.substr(6,4);
	
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	//Verificar que el año de Fin sea mayor al de Inicio
	if(fechaIni>fechaFin){
		res=0;
		alert ("La fecha de Inicio no puede ser mayor a la Fecha de Cierre");
	}
	
	if(res==1)
		return true;
	else
		return false;
}




/*Esta funcion se ecnarga de Validar el Formulario donde se selecciona el plano para borrar*/
function valFormEliminar(frm_eliminarPlano){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_eliminarPlano.rdb_plano.length==undefined && !frm_eliminarPlano.rdb_plano.checked){
		alert("Seleccionar el Plano a Borrar");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_eliminarPlano.rdb_plano.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_eliminarPlano.rdb_plano.length;i++){
			if(frm_eliminarPlano.rdb_plano[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar el Plano a Borrar");			
	}
	
	if (res==1){
		
		if (!confirm("¿Estas Seguro que Quieres Borrar el Plano?\nToda la información relacionada se Borrará")){
			res=0;
		}
	}
	
	if(res==1)
		return true;
	else
		return false;
}


/*********************************************************************CONSULTAR PLANOS**************************************************************************/
/*Esta funcion valida que las fechas elegidas sean correctas*/
function valFormConsultarPlano(frm_consultarPlanoFecha){
	var res=1;
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_consultarPlanoFecha.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_consultarPlanoFecha.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_consultarPlanoFecha.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Cierre, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_consultarPlanoFecha.txt_fechaFin.value.substr(0,2);
	var finMes=frm_consultarPlanoFecha.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_consultarPlanoFecha.txt_fechaFin.value.substr(6,4);
	
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	//Verificar que el año de Fin sea mayor al de Inicio
	if(fechaIni>fechaFin){
		res=0;
		alert ("La fecha de Inicio no puede ser mayor a la Fecha de Cierre");
	}
	
	if(res==1)
		return true;
	else
		return false;
}


/**********************************************************************************************************************************************************/
/*********************************************************************REGISTRAR TRASPALEO******************************************************************/
/**********************************************************************************************************************************************************/
/*Esta función valida la Seleccion de una Obra para registrar los datos de Traspaleo*/
function valFormElegirObraTraspaleo(frm_elegirObraTraspaleo){
	//Si estado permanece en 1, el proceso de validacion fue satisfactorio
	var status = 1;
	
	if(frm_elegirObraTraspaleo.cmb_tipoObra.value==""){
		alert("Elegir un Tipo de Obra");
		status = 0;
	}
	//Validar que una obra sea seleccionada cuando el tipo de obra seleccionado sea diferente de "Obra No Registrada"
	else if(frm_elegirObraTraspaleo.cmb_tipoObra.value!="OBRA_NR"){
		if(frm_elegirObraTraspaleo.cmb_nomObra.value==""){
			alert("Seleccionar una Obra");
			status = 0;
		}
	}
	//Validar que una Categoría Obra sea seleccionada cuando el tipo de obra seleccionado sea "Obra No Registrada"
	else if(frm_elegirObraTraspaleo.cmb_tipoObra.value=="OBRA_NR"){
		if(frm_elegirObraTraspaleo.cmb_categoriaObra.value==""){
			alert("Seleccionar una Categoría de Obra");
			status = 0;
		}
	}
	
	if(status==1)
		return true;
	else
		return false;
}


/*Agregar opcion adicional al combo de Tipo de Obra en el Registro de Traspaleo*/
function agregarOpcionCombo(){
	//Agregar un espacio para la Opcion
	document.getElementById("cmb_tipoObra").length++;
	//Agregar las propiedades de VALUE y TEXT
	document.getElementById("cmb_tipoObra").options[document.getElementById("cmb_tipoObra").length-1].value="OBRA_NR";//Obra No Registrada
	document.getElementById("cmb_tipoObra").options[document.getElementById("cmb_tipoObra").length-1].text="Obra No Registrada";
}//Cierre de la funcion agregarOpcionCombo()


/*Esta funcion muestra la etiqueta y el comboBox para indicar la categoria de la obra (Costos o Amortizables)*/
function mostrarElementos(opcSeleccionada){
	if(opcSeleccionada=="OBRA_NR"){
		//Ocultar la etiqueta y el combo para seleccionar la Obra
		document.getElementById("etq_obra").style.visibility = "hidden";
		document.getElementById("cmb_nomObra").style.visibility = "hidden";
		
		//Mostrar la etiqueta y el combo para seleccionar Categoría de Obra
		document.getElementById("etq_categoria").style.visibility = "visible";
		document.getElementById("cmb_categoriaObra").style.visibility = "visible";
	}
	else if(opcSeleccionada!="OBRA_NR"){
		//Ocultar la etiqueta y el combo para seleccionar la Obra
		document.getElementById("etq_obra").style.visibility = "visible";
		document.getElementById("cmb_nomObra").style.visibility = "visible";
		
		//Mostrar la etiqueta y el combo para seleccionar Categoría de Obra
		document.getElementById("etq_categoria").style.visibility = "hidden";
		document.getElementById("cmb_categoriaObra").style.visibility = "hidden";
	}
	
}//Cierre de la funcion mostrarElementos()


/*Esta funcion define el orden de los campos que seran introducidos segun el Tipo de Obra seleccionada en la Pantalla de Registrar Datos de Traspaleo*/
function colocarOrdenCampos(){
	//Obtener el tipo de obra seleccionado
	var tipoObra = document.getElementById("txt_tipoObra").value;
	
	if(tipoObra=="OBRA_NR"){
		//Definir el orden del Tabulador cuando la opcion seleccionada es un tipo de obra registrado en la BD de Topografia
		document.getElementById("txt_nombreObra").tabIndex = 1;
		document.getElementById("txt_volumen").tabIndex = 2;
		document.getElementById("txt_tasaCambio").tabIndex = 3;
		document.getElementById("cmb_noQuincena").tabIndex = 4;
		document.getElementById("cmb_Mes").tabIndex = 5;
		document.getElementById("cmb_Anio").tabIndex = 6;
		document.getElementById("sbt_registrarDatos").tabIndex = 7;
		document.getElementById("rst_limpiar").tabIndex = 8;
		document.getElementById("btn_cancelar").tabIndex = 9;
		//Colocar el foco en el campo inicial
		document.getElementById("txt_nombreObra").focus();					
	}
	else if(tipoObra!="OBRA_NR"){
		//Definir el orden del Tabulador cuando la opcion seleccionada es un tipo de obra registrado en la BD de Topografia
		document.getElementById("txt_acumuladoQuincena").tabIndex = 1;
		document.getElementById("txt_tasaCambio").tabIndex = 2;
		document.getElementById("cmb_noQuincena").tabIndex = 3;
		document.getElementById("cmb_Mes").tabIndex = 4;
		document.getElementById("cmb_Anio").tabIndex = 5;
		document.getElementById("sbt_registrarDatos").tabIndex = 6;
		document.getElementById("rst_limpiar").tabIndex = 7;
		document.getElementById("btn_cancelar").tabIndex = 8;
		//Colocar el foco en el campo inicial
		document.getElementById("txt_acumuladoQuincena").focus();	
	}		
}//Cierre de la funcion colocarOrdenCampos()


/*Esta funcion define el orden de los campos que seran introducidos segun el Tipo de Obra seleccionada en la Pantalla de Registrar Detalle Movientos de Traspaleo*/
function colocarOrdenCampos2(){
	//Obtener el tipo de obra seleccionado
	var tipoObra = document.getElementById("txt_tipoObra2").value;
	
	if(tipoObra=="OBRA_NR"){
		//Definir el orden del Tabulador cuando la opcion seleccionada es un tipo de obra registrado en la BD de Topografia
		document.getElementById("txt_origen").tabIndex = 1;
		document.getElementById("txt_destino").tabIndex = 2;
		document.getElementById("txt_distancia").tabIndex = 3;
		document.getElementById("cmb_listaPrecios").tabIndex = 4;
		document.getElementById("ckb_regConCosto").tabIndex = 5;
		if(document.getElementById("sbt_guardarTraspaleo")!=null)
			document.getElementById("sbt_guardarTraspaleo").tabIndex = 6;
		document.getElementById("sbt_registrarDetalle").tabIndex = 7;
		document.getElementById("btn_cancelar").tabIndex = 8;
		//Colocar el foco en el campo inicial
		document.getElementById("txt_origen").focus();
	}
	else if(tipoObra!="OBRA_NR"){
		//Definir el orden del Tabulador cuando la opcion seleccionada es un tipo de obra registrado en la BD de Topografia
		document.getElementById("txt_origen").tabIndex = 1;
		document.getElementById("txt_destino").tabIndex = 2;
		document.getElementById("txt_distancia").tabIndex = 3;
		document.getElementById("ckb_regConCosto").tabIndex = 4;
		if(document.getElementById("sbt_guardarTraspaleo")!=null)
			document.getElementById("sbt_guardarTraspaleo").tabIndex = 5;
		document.getElementById("sbt_registrarDetalle").tabIndex = 6;
		document.getElementById("btn_cancelar").tabIndex = 7;
		//Colocar el foco en el campo inicial
		document.getElementById("txt_origen").focus();	
	}		
}//Cierre de la funcion colocarOrdenCampos2()


/*Esta funcion valida el Formulario donde se registran los datos del Traspaleo*/
function valFormRegistrarDatosTraspaleo(frm_registrarDatosTraspaleo){
	//Si estado permanece en 1, el proceso de validacion fue satisfactorio
	var status = 1;
	
	//Validar los datos de una Obra NO Registrada
	if(frm_registrarDatosTraspaleo.txt_tipoObra.value=="OBRA_NR"){	
		if(frm_registrarDatosTraspaleo.txt_nombreObra.value==""){
			alert("Introducir el Nombre de la Obra");
			status = 0;
		}		
		if(frm_registrarDatosTraspaleo.txt_volumen.value=="" && status==1){
			alert("Introducir el Volumen");
			status = 0;
		}
		if(status==1){
			if(parseInt(frm_registrarDatosTraspaleo.txt_volumen.value)==0){
				alert("El Volumen Debe Ser Mayor a 0");
				status = 0;
			}
		}
		if(frm_registrarDatosTraspaleo.txt_tasaCambio.value=="" && status==1){
			alert("Introducir la Tasa de Cambio");
			status = 0;
		}
		if((frm_registrarDatosTraspaleo.cmb_noQuincena.value==""||frm_registrarDatosTraspaleo.cmb_Mes.value==""||frm_registrarDatosTraspaleo.cmb_Anio.value=="")&&status==1){
			alert("Introducir los Datos del Numero de la Quincena");
			status = 0;
			
		}
		
	}//Cierre if(frm_registrarDatosTraspaleo.txt_acumuladoQuincena.txt_tipoObra=="OBRA_NR")
	//Validar los datos de una Obra Registrada
	else if(frm_registrarDatosTraspaleo.txt_tipoObra.value!="OBRA_NR"){
		if(frm_registrarDatosTraspaleo.txt_acumuladoQuincena.value==""){
			alert("Introducir el Valor Acumulado de la Quincena");
			status = 0;
		}		
		if(frm_registrarDatosTraspaleo.txt_tasaCambio.value=="" && status==1){
			alert("Introducir la Tasa de Cambio");
			status = 0;
		}
		if((frm_registrarDatosTraspaleo.cmb_noQuincena.value==""||frm_registrarDatosTraspaleo.cmb_Mes.value==""||frm_registrarDatosTraspaleo.cmb_Anio.value=="")&&status==1){
			alert("Introducir los Datos del Numero de la Quincena");
			status = 0;
			
		}
	}//Cierre else if(frm_registrarDatosTraspaleo.txt_acumuladoQuincena.txt_tipoObra!="OBRA_NR")

					
	if(status==1)
		return true;
	else
		return false;
}


/*Esta funcion valida los datos del Detalle de los Movimientos del Traspaleo*/
function valFormRegistrarDetalleTraspaleo(frm_registrarDetalleTraspaleo){
	//Si estado permanece en 1, el proceso de validacion fue satisfactorio
	var status = 1;
	
	//Si el valor de hdn_botonSeleccionado es AGREGAR, entonces realizamos la validación, de lo contrario no se hace
	if(frm_registrarDetalleTraspaleo.hdn_botonSeleccionado.value=="AGREGAR"){	
		//Validar los datos de una Obra NO Registrada
		if(frm_registrarDetalleTraspaleo.txt_tipoObra2.value=="OBRA_NR"){	
			if(frm_registrarDetalleTraspaleo.txt_origen.value==""){
				alert("Ingresar el Origen");
				status = 0;
			}
			if(frm_registrarDetalleTraspaleo.txt_destino.value=="" && status==1){
				alert("Ingresar el Destino");
				status = 0;
			}
			if(frm_registrarDetalleTraspaleo.txt_distancia.value=="" && status==1){
				alert("Ingresar la Distancia");
				status = 0;
			}
			if(frm_registrarDetalleTraspaleo.cmb_listaPrecios.value=="" && status==1){
				alert("Seleccionar Lista de Precios");
				status = 0;
			}
		}//Cierre if(frm_registrarDatosTraspaleo.txt_acumuladoQuincena.txt_tipoObra=="OBRA_NR")
		//Validar los datos de una Obra Registrada
		else if(frm_registrarDetalleTraspaleo.txt_tipoObra2.value!="OBRA_NR"){
			if(frm_registrarDetalleTraspaleo.txt_origen.value==""){
				alert("Ingresar el Origen");
				status = 0;
			}
			if(frm_registrarDetalleTraspaleo.txt_destino.value=="" && status==1){
				alert("Ingresar el Destino");
				status = 0;
			}
			if(frm_registrarDetalleTraspaleo.txt_distancia.value=="" && status==1){
				alert("Ingresar la Distancia");
				status = 0;
			}	
		}//Cierre else if(frm_registrarDatosTraspaleo.txt_tipoObra.value!="OBRA_NR")
	}//Cierre if(frm_registrarDetalleTraspaleo.hdn_botonSeleccionado.value=="AGREGAR")
	
	
	
	//Si el boton seleccionado es finalizar, notificar al usuario para que confirme el registro de los datos
	if(frm_registrarDetalleTraspaleo.hdn_botonSeleccionado.value=="FINALIZAR"){
		var res = confirm("Presione 'Aceptar' para Guardar los Registros Actuales \nPresine 'Cancelar' para Agregar más Registros");
		
		if(res)//Sí el usuario presiono 'Aceptar', proceder a guardar los registros
			status = 1;
		else//Sí el usuario presiono 'Cancelar', permitir agregar mas registros
			status = 0;
	}
	
	
	if(status==1)
		return true;
	else
		return false;
}


/*Esta funcion valida que cuando es el primer registro y no tiene costo, la distancia no sea mayor a 200*/
function validarDistancia(cajaTxtDistancia){
	var incluirPrecio = document.getElementById("hdn_incluirPrecio").value;
	if(incluirPrecio=="no"){
		var distancia = parseFloat(cajaTxtDistancia.value);
		if(distancia>200){
			alert("Solo se contemplaran 200 Mts para el primer registro\nEl Resto Debe Incluirse en un Nuevo Registro con Precios");
			cajaTxtDistancia.value = 200;
		}
	}
}


/*Esta funcion coloca la Leyenda 'N/A' en las cajas para precios, ya que el primer registro no incluye precio en la mayoria de los casos*/
function colocarLeyenda(){
	document.getElementById("txt_pumn").value="N/A";
	document.getElementById("txt_puusd").value="N/A";
	document.getElementById("txt_totalMN").value="N/A";
	document.getElementById("txt_totalUSD").value="N/A";
	document.getElementById("txt_importeTotal").value="N/A";
}


/*Esta funcion reactiva los campos de Precios en caso que esten deshabilitados y modifica el valor de la variable hdn_incluirPrecio*/
function activarCamposPrecios(checkBox){	
	//Si el CheckBox es seleccionado, activar los campos
	if(checkBox.checked){
		document.getElementById("txt_pumn").value="";
		document.getElementById("txt_puusd").value="";
		document.getElementById("txt_totalMN").value="";
		document.getElementById("txt_totalUSD").value="";
		document.getElementById("txt_importeTotal").value="";
		
		document.getElementById("hdn_incluirPrecio").value="si";
		
		//Resetear los campos donde se ingresan datos para que el usuario los Vuelva a introducir
		document.getElementById("txt_origen").value = document.getElementById("txt_origen").defaultValue;
		document.getElementById("txt_destino").value = document.getElementById("txt_destino").defaultValue;
		document.getElementById("txt_distancia").value = document.getElementById("txt_distancia").defaultValue;
	}
	else{
		document.getElementById("txt_pumn").value="N/A";
		document.getElementById("txt_puusd").value="N/A";
		document.getElementById("txt_totalMN").value="N/A";
		document.getElementById("txt_totalUSD").value="N/A";
		document.getElementById("txt_importeTotal").value="N/A";
	
		document.getElementById("hdn_incluirPrecio").value="no";
		
		
		//Resetear los campos donde se ingresan datos para que el usuario los Vuelva a introducir
		document.getElementById("txt_origen").value = document.getElementById("txt_origen").defaultValue;
		document.getElementById("txt_destino").value = document.getElementById("txt_destino").defaultValue;
		document.getElementById("txt_distancia").value = document.getElementById("txt_distancia").defaultValue;
	}	
}


/*Esta funcion se encarga de calcular el Volumen de acuerdo al Valor Acumulado en la quincena y el Area de la Sección*/
function calcularVolumen(acumulado){
	//Obtener el valor del Acumulado de la Quincena
	var acumQuincena = acumulado.value.replace(/,/g,'');
	
	//Verificar que el valor acumulado sea un numero valido
	if(validarEntero(acumQuincena,"El Acumulado de la Quincena")){
		//Convertir el valor acumulado y el area a numero decimales
		var area = parseFloat(document.getElementById("txt_area").value.replace(/,/g,''));
		var valorAcumulado = parseFloat(acumQuincena);
		
		//Obtener el volumen
		var volumen = area * valorAcumulado;
		
		//Colocar el volumen obtenido en la caja de texto de Volumen y darle formato de miles
		formatCurrency(volumen,"txt_volumen");
	}
	else{
		//Borrar los datos que pudieran estar en las cajas de texto de valor Acumulado y Volumen
		acumulado.value = "";
		document.getElementById("txt_volumen").value = "";
	}
}

/*Esta funcion se encarga de validar de que un campo tipo numero solo contenga numeros validos*/
function validarCampoNumerico(campoNumerico,msg){
	if(!validarEntero(campoNumerico.value.replace(/,/g,''),msg))
		campoNumerico.value = "";
	else
		formatCurrency(campoNumerico.value,campoNumerico.id);
}


/*Esta funcion realiza los calculos de los totales en el Registro del Traspaleo y en la Modificacion del Mismo*/
function calcularTotales(noRegistro){
	//Verificar que los campos necesarios para realizar los calculos no esten vacios
	var volumen = parseFloat(document.getElementById("txt_volumen2").value.replace(/,/g,''));
	var tasaCambio = parseFloat(document.getElementById("txt_tasaCambio2").value.replace(/,/g,'')); 		
	
	//Si ambos campos son proporcionados realizar los calculos correspondientes
	if(volumen!="" && tasaCambio!=""){
		//Obtener el resto de datos necesarios para relizar los calculos
		var pumn = parseFloat(document.getElementById("txt_pumn"+noRegistro).value.replace(/,/g,''));
		var puusd = parseFloat(document.getElementById("txt_puusd"+noRegistro).value.replace(/,/g,''));	
		
		//Calcular el Total en M.N.
		var totalMN = volumen * pumn;
		
		//Calcular el Total en USD
		var totalUSD = volumen * puusd * tasaCambio
		
		//Calcular el Importe Total
		var importeTotal = totalMN + totalUSD;
		
		//Asignar los valores a las variables correspondientes con fomato de moneda
		formatCurrency(totalMN,"txt_totalMN"+noRegistro);
		formatCurrency(totalUSD,"txt_totalUSD"+noRegistro);
		formatCurrency(importeTotal,"txt_importeTotal"+noRegistro);
	}
	
}

/**********************************************************************************************************************************************************/
/*****************************************************FORMULARIO CONSULTAR TRASPALEO************************************************************************/
/**********************************************************************************************************************************************************/
/*Esta función se encarga de validar el formulario de consultar traspaleo por obra*/
function valFormConsultarTraspObra(frm_consultarTraspaleo){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	band = 1;
	
	//Verificar que el campo de cmb_obra no este vacío
	if(frm_consultarTraspaleo.cmb_obra.value=="" && band==1){
		alert("Seleccionar el Tipo de Obra");
		band = 0;
	}
		//Verificar que el campo de cmb_nombreObra no este vacío
	if(frm_consultarTraspaleo.cmb_nombreObra.value=="" && band==1){
		alert("Seleccionar el Nombre de la Obra");
		band = 0;
	}
			
	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}


/*Esta función se encarga de validar el formulario de consultar traspaleo por mes*/
function valFormConsultarTraspMes(frm_consultarTraspaleo){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	band = 1;
	
	//Verificar que el campo de cmb_mes no este vacío
	if(frm_consultarTraspaleo.cmb_mes.value=="" && band==1){
		alert("Seleccionar el Mes");
		band = 0;
	}
		//Verificar que el campo de cmb_año no este vacío
	if(frm_consultarTraspaleo.cmb_anios.value=="" && band==1){
		alert("Seleccionar el Año");
		band = 0;
	}
			
	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}


/*Esta función se encarga de validar el formulario de consultar traspaleo por quincena*/
function valFormConsultarTraspQuin(frm_consultarTraspaleo){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	band = 1;
	
	//Verificar que el campo de cmb_tipoObra no este vacío
	if(frm_consultarTraspaleo.cmb_tipoObra.value=="" && band==1){
		alert("Seleccionar el Tipo de Obra");
		band = 0;
	}
	
	//Verificar que el campo de cmb_nomObra no este vacío
	if(frm_consultarTraspaleo.cmb_nomObra.value=="" && band==1){
		alert("Seleccionar el Nombre de la Obra");
		band = 0;
	}
		
	//Verificar que el campo de cmb_numQuincena no este vacío
	if(frm_consultarTraspaleo.cmb_numQuincena.value=="" && band==1){
		alert("Seleccionar el Número de Quincena");
		band = 0;
	}

	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}

/**********************************************************************************************************************************************************/
/*****************************************************FORMULARIO ELIMINAR TRASPALEO************************************************************************/
/**********************************************************************************************************************************************************/
/*Esta función se encarga de validar el formulario de eliminar traspaleo por obra*/
function valFormEliminarTraspObra(frm_eliminarTraspaleo){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	band = 1;
	
	//Verificar que el campo de cmb_obra no este vacío
	if(frm_eliminarTraspaleo.cmb_obra.value=="" && band==1){
		alert("Seleccionar el Tipo de Obra");
		band = 0;
	}
		//Verificar que el campo de cmb_nombreObra no este vacío
	if(frm_eliminarTraspaleo.cmb_nombreObra.value=="" && band==1){
		alert("Seleccionar el Nombre de la Obra");
		band = 0;
	}
			
	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}


/*Esta función se encarga de validar el formulario de eliminar traspaleo por mes*/
function valFormEliminarTraspMes(frm_eliminarTraspaleo){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	band = 1;
	
	//Verificar que el campo de cmb_mes no este vacío
	if(frm_eliminarTraspaleo.cmb_mes.value=="" && band==1){
		alert("Seleccionar el Mes");
		band = 0;
	}
		//Verificar que el campo de cmb_año no este vacío
	if(frm_eliminarTraspaleo.cmb_anios.value=="" && band==1){
		alert("Seleccionar el Año");
		band = 0;
	}
			
	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}


/*Esta función se encarga de validar el formulario de eliminar traspaleo por quincena*/
function valFormEliminarTraspQuin(frm_eliminarTraspaleo){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	band = 1;
	
	//Verificar que el campo de cmb_tipoObra no este vacío
	if(frm_eliminarTraspaleo.cmb_tipoObra.value=="" && band==1){
		alert("Seleccionar el Tipo de Obra");
		band = 0;
	}
	
	//Verificar que el campo de cmb_nomObra no este vacío
	if(frm_eliminarTraspaleo.cmb_nomObra.value=="" && band==1){
		alert("Seleccionar el Nombre de la Obra");
		band = 0;
	}
		
	//Verificar que el campo de cmb_numQuincena no este vacío
	if(frm_eliminarTraspaleo.cmb_numQuincena.value=="" && band==1){
		alert("Seleccionar el Número de Quincena");
		band = 0;
	}
			
	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}


/*Esta funcion se ecnarga de Validar el Formulario donde se selecciona el traspaleo para borrar*/
function valFormElimTrasp(frm_eliminarDetTraspaleo){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_eliminarDetTraspaleo.rdb.length==undefined && !frm_eliminarDetTraspaleo.rdb.checked){
		alert("Seleccionar el Traspaleo a Borrar");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_eliminarDetTraspaleo.rdb.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_eliminarDetTraspaleo.rdb.length;i++){
			if(frm_eliminarDetTraspaleo.rdb[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar el Traspaleo a Borrar");			
	}
	
	if (res==1){
		
		if (!confirm("¿Estas Seguro que Quieres Borrar el Traspaleo?\nToda la información relacionada se Borrará")){
			res=0;
		}
	}
	
	if(res==1)
		return true;
	else
		return false;
}


/**********************************************************************************************************************************************************/
/***********************************************************MODIFICAR TRASPALEO****************************************************************************/
/**********************************************************************************************************************************************************/
/*Esta función Valida el formulario de Seleccionar Obra para modificar el Traspaleo asociado a esa Obra*/
function valFormSeleccionarQuincena(frm_seleccionarQuincena){
	//Si el valor se mantiene en 1, el proceso de validación fue correcto
	var status = 1; 
	
	if(frm_seleccionarQuincena.cmb_tipoObra.value==""){
		alert("Seleccionar un Tipo de Obra");
		status = 0;
	}
	if(frm_seleccionarQuincena.cmb_idObra.value=="" && status==1){
		alert("Seleccionar una Obra");
		status = 0;
	}
	if(frm_seleccionarQuincena.cmb_numQuincena.value=="" && status==1){
		alert("Seleccionar el No. de Quincena");
		status = 0;
	}
	
	
	if(status==1)
		return true;
	else
		return false;
}


/*Esta función valida el formulario de Modificar Datos Generales del Traspaleo*/
function valFormModificarDatosTraspaleo(frm_modificarDatosTraspaleo){
	//Si el valor se mantiene en 1, entonces el proceso de validación fue correcto
	var status = 1;
	
	//Validar que el Valor Acumulado de la Quincena sea proporcionado
	if(frm_modificarDatosTraspaleo.txt_acumuladoQuincena.value==""){
		alert("Ingresar el Acumulado de la Quincena");
		status = 0;
	}
	
	//Validar que sea proporcionado el Tipo de Cambio
	if(frm_modificarDatosTraspaleo.txt_tasaCambio.value=="" && status==1){
		alert("Ingresar el Tipo de Cambio");
		status = 0;
	}
	
	if( (frm_modificarDatosTraspaleo.cmb_noQuincena.value=="" || frm_modificarDatosTraspaleo.cmb_Mes.value=="" || frm_modificarDatosTraspaleo.cmb_Anio.value=="") && status==1){
		alert("Introducir los Datos del Numero de la Quincena");
		status = 0;
	}
	
	
	//Verificar si Alguno de los datos Cambio su valor Inicial
	if(status==1){
		//Verrificar el Acumulado del Mes
		if(frm_modificarDatosTraspaleo.txt_acumuladoQuincena.value!=frm_modificarDatosTraspaleo.hdn_deafaultAcumulado.value)
			frm_modificarDatosTraspaleo.hdn_cambioVolumen.value = "si";
			
		//Verrificar el Tipo de Cambio
		if(frm_modificarDatosTraspaleo.txt_tasaCambio.value!=frm_modificarDatosTraspaleo.hdn_deafaultTipoCambio.value)
			frm_modificarDatosTraspaleo.hdn_cambioTipoCambio.value = "si";		
	}
	
	
	if(status==1)
		return true;
	else
		return false;
}


/*Esta funcion valida el Formulario donde se modifican los registros del Traspaleo de la Obra Seleccionada*/
function valFormModDetalleTraspaleo(frm_modDetalleTraspaleo){
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var res = 1;
	//Variable para saber si fueron enviados mensajes de solicitud de Datos al Usuario
	var msg = 0;
	//Variable para saber si al menos un Registro fue seleccionado
	var status = 0;
	//Variable para controlar la cantidad de registros
	var ctrl= 1;		
	
	//Obtener la Cantidad de registros
	var cantidad = frm_modDetalleTraspaleo.hdn_cantRegistros.value;

					
	//Variables que almacenara el nombre de cada CheckBox Seleccionado y las cajas de texto de Origen, Destino y Cantidad
	var idCheckBox = "";
	var idTxtOrigen = "";
	var idTxtDestino = "";
	var idTxtDistancia = "";
	
	while(ctrl<cantidad){		
		//Crear el id del CheckBox que se quiere verificar
		idCheckBox="ckb_modRegistro"+ctrl.toString();
		
		//Verificar que el Origen, el Destino y la Cantidad del Registro seleccionado no esten vacias
		if(document.getElementById(idCheckBox).checked){
			status = 1;
			//Crear el id de la Caja de Texto del Origen
			idTxtOrigen = "txt_origen"+ctrl.toString();
			//Crear el id de la Caja de Texto del Destino
			idTxtDestino = "txt_destino"+ctrl.toString();
			//Crear el Id de la Caja de Texto de la Cantidad
			idTxtDistancia = "txt_distancia"+ctrl.toString();
			
			
			//Validar que el Origen sea proporcionado
			if(document.getElementById(idTxtOrigen).value==""){				
				alert("Ingresar el Origen para el Registro No. "+ctrl);
				msg = 1;
			}
			
			//Validar que el Destino sea proporcionado
			if(document.getElementById(idTxtDestino).value=="" && msg==0){
				alert("Ingresar el Destino para el Registro No. "+ctrl);
				msg = 1;
			}
			
			//Validar que la Distancia sea Proporcionada
			if(document.getElementById(idTxtDistancia).value=="" && msg==0){
				alert("Ingresar la Distancia para el Registro No. "+ctrl);
				msg = 1;
			}
		}
		
		//Aumentar la variable ctrl para verificar el siguiente registro
		ctrl++;
	}//Fin del While	
	
	
	
	//Verificar que al menos un material haya sido seleccionado, si la variable status vale 1, quiere decir que al menos un material fue seleccionado
	if(status==1){
		//Si hubo algun mensaje de que falta ingresar un datos, no se cumplio con el proceso de validacion 
		if(msg==1)
			res = 0;
	}
	else{//Indicar que los cambios derivados de la Actulización del Acumulado de la Quincena y del Tipo de Cambio serán guardado aunque no sea seleccionado ningun registro para su edición
		var respuesta = confirm("Ningún Registro Fue Seleccionado, \nLos cambios Derivados de la Actualización del Acumulado de la Quincena y del Tipo de Cambio Serán Guardados\n¿Desea Continuar?");
		if(respuesta)
			res = 1;
		else{
			alert("Seleccionar al Menos un Registro para Editar");
			res = 0;
		}
	}
	
	
	if(res==1)
		return true;
	else
		return false;		
}//valFormModDetalleTraspaleo(frm_modDetalleTraspaleo)


/*Activar y Desacrivar los campos para modificar los registros del detalle de Traspaleo*/
function activarRegistros(checkBox){
	if(checkBox.checked){
		//Activar los campos para que puedan ser editados
		document.getElementById("txt_origen"+checkBox.value).readOnly = false;
		document.getElementById("txt_destino"+checkBox.value).readOnly = false;
		document.getElementById("txt_distancia"+checkBox.value).readOnly = false;
	}
	else {
		//Regresar a su valor por defecto los campos
		document.getElementById("txt_origen"+checkBox.value).value = document.getElementById("txt_origen"+checkBox.value).defaultValue;
		document.getElementById("txt_destino"+checkBox.value).value = document.getElementById("txt_destino"+checkBox.value).defaultValue;
		document.getElementById("txt_distancia"+checkBox.value).value = document.getElementById("txt_distancia"+checkBox.value).defaultValue;
		document.getElementById("txt_pumn"+checkBox.value).value = document.getElementById("txt_pumn"+checkBox.value).defaultValue;
		document.getElementById("txt_puusd"+checkBox.value).value = document.getElementById("txt_puusd"+checkBox.value).defaultValue;
		//Realizar los calculos con los valores por defecto
		calcularTotales(checkBox.value);
		
		//Desactivar los campos para que no puedan ser editados
		document.getElementById("txt_origen"+checkBox.value).readOnly = true;
		document.getElementById("txt_destino"+checkBox.value).readOnly = true;
		document.getElementById("txt_distancia"+checkBox.value).readOnly = true;				
	}	
}


/*Esta función obtiene la Suma total de los registros del Detalle de Traspaleo*/
function obtenerSumaTotal(){
	//Obtener la Cantidad de Registros 
	var cantRegs = document.getElementById("hdn_cantRegistros").value;
	
	//Sumar el subtotal de cada Registro
	var total = 0;
	for(var i=1;i<cantRegs;i++){
		total += parseFloat(document.getElementById("txt_importeTotal"+i).value.replace(/,/g,''));
	}
	
	//Asignar el Total a la caja de Texto de Suma Total
	formatCurrency(total,'txt_sumaTotal');
}

/**********************************************************************************************************************************************************/
/*****************************************************FORMULARIO LISTA PRECIOS************************************************************************/
/**********************************************************************************************************************************************************/
//Funcion que valida el formulario de lista de precios 
function valFormAgregarPrecios(frm_listaPrecios){
	
	var band=1;
	if(frm_listaPrecios.hdn_botonSel.value=='Agregar'){
	
		if (frm_listaPrecios.txt_nuevoTipoTraspaleo.value==""){
			alert("Ingresar Nombre Nueva Lista de Precios");
			band=0;
		}
		
		if (frm_listaPrecios.txa_descripcion.value=="" && band==1){
			alert("Introducir la Descripción de Lista de Precios");
			band=0;
		}
	}
	
	if(frm_listaPrecios.hdn_botonSel.value=='Modificar'){
		frm_listaPrecios.action="frm_modificarListaPrecios.php";	
	}
	
	if(frm_listaPrecios.hdn_botonSel.value=='Consultar'){
		frm_listaPrecios.action="frm_consultarListaPrecios.php";	
	}

	if(frm_listaPrecios.hdn_claveValida.value=='no'){
		alert("Ya exite una Lista de Precios con Mismo Nombre");
		band=0;
	}

	
	if (band==1)
		return true;
	else
		return false;
}

//Funcion que valida el formulario de lista de precios al reguistrar el detalle
function valFormAgregarDetPrecios(frm_listaPrecios){
	
	var band=1;
	
	if (frm_listaPrecios.txt_distancia_inicio.value==""){
		alert("Ingresar la Distancia de Inicio");
		band=0;
	}
	
	if(band==1){
		if(!validarEnteroConCero(frm_listaPrecios.txt_distancia_inicio.value.replace(/,/g,''),"La Distancia de Inicio"))
			band = 0;		
	}

	if (frm_listaPrecios.txt_distancia_fin.value=="" && band==1){
		alert("Introducir la Distancia Final");
		band=0;
	}

	if(band==1){
		if(!validarEntero(frm_listaPrecios.txt_distancia_fin.value.replace(/,/g,''),"La Distancia Final"))
			band = 0;		
	}

	if (frm_listaPrecios.txt_distanciaIntervalo.value=="" && band==1){
		alert("Introducir la Distancia del Intervalo");
		band=0;
	}

	if(band==1){
		if(!validarEntero(frm_listaPrecios.txt_distanciaIntervalo.value.replace(/,/g,''),"El Intervalo"))
			band = 0;		
	}

	if (band==1)
		return true;
	else
		return false;
}

//funcion que valida que la distancia final no sea menor que la distancia inicial
function validaDistanciaFinal(){

	 var dist_inicial= document.getElementById("txt_distancia_inicio").value;
	 var dist_final=  document.getElementById("txt_distancia_fin").value;
	 
	 if(dist_inicial!="" && dist_final!=""){
		
		var inicial= parseInt(dist_inicial.replace(/,/g,''));
		var final= parseInt(dist_final.replace(/,/g,''));

		if(inicial==final){
			alert("La Distancia Final no Puede ser Igual a la Inicial");
			document.getElementById("txt_distancia_inicio").value="";
			document.getElementById("txt_distancia_fin").value="";
		 }
		 
		if(inicial>final){
			alert("La Distancia Inicial no Puede ser Mayor a la Final");
			document.getElementById("txt_distancia_inicio").value="";
			document.getElementById("txt_distancia_fin").value="";
		 }
	 }
}

//funcion que valida que la que la distancia del intervalo no sea mayor que la distancia final
function validaIntervalo(){

 	 var dist_inicial= document.getElementById("txt_distancia_inicio").value;	 
	 var dist_final=  document.getElementById("txt_distancia_fin").value;
	 var dist_intervalo= document.getElementById("txt_distanciaIntervalo").value;
	 
	 //obtener la diferencia entre la distancia inicial y la final para validar si la dist_intervalo es correcta en el rango de diferencia entre ambas distancias
	 var amplitud= (dist_final-dist_inicial);
	 
	 var band=1;
	 
	 if(dist_final!="" && dist_intervalo!=""){
		
		var final= parseInt(dist_final.replace(/,/g,''));
		var intervalo= parseInt(dist_intervalo.replace(/,/g,''));
	
		if(final<intervalo){
			alert("El Intervalo no Puede ser Mayor a la Distacia Final");
			document.getElementById("txt_distanciaIntervalo").value="";
			band=0;
		 }

		if(amplitud<dist_intervalo&&band==1){
			alert("El Intervalo indicado es Mayor al Rango existente  \n entre la Distancia Inicial y la Distancia Final");
			document.getElementById("txt_distanciaIntervalo").value="";
		 }
	 }
}


function activarBotones(combo){
	
	if(combo.value=="NuevoRegistro"){
		//Desactivar los botones de consultar y modificar en caso de que esten activos
		document.getElementById("sbt_modificar").disabled=true;
		document.getElementById("sbt_consultar").disabled=true;
		
		//Activar el botón de agregar, activar la caja de texto, activar el area de texto he indicar a que pagina se redirecciona el formulario
		document.getElementById("sbt_agregar").disabled=false;
		document.frm_listaPrecios.action="frm_listaPrecios.php";
		document.getElementById("txt_nuevoTipoTraspaleo").readOnly=false;
		document.getElementById("txa_descripcion").disabled=false;
	}
	else if (combo.value!=""){
		
		//Desactivar el botón de agregar en caso de que este activo, la caja de texto y el area de texto 
		document.getElementById("sbt_agregar").disabled=true;
		document.getElementById("txt_nuevoTipoTraspaleo").readOnly=true;
		document.getElementById("txa_descripcion").disabled=true;

		//Activar botones de modificar y consultar cuando se seleccione una lista existente
		document.getElementById("sbt_modificar").disabled=false;
		document.getElementById("sbt_consultar").disabled=false;
		
	}
	else if (combo.value==""){
		//Desactivar los botones de agregar, modificar, consultar, la caja de texto y el area de texto
		document.getElementById("sbt_agregar").disabled=true;
		document.getElementById("txt_nuevoTipoTraspaleo").readOnly=true;
		document.getElementById("sbt_modificar").disabled=true;
		document.getElementById("sbt_consultar").disabled=true;
		document.getElementById("txa_descripcion").disabled=true;
	}
}


/*Esta función valida  el check box para modificar una lista de precios*/
function activarCampos (campo, noRegistro){
	if (campo.checked){
		document.getElementById("txt_precioMN" + noRegistro).disabled=false;
		document.getElementById("txt_precioUSD" + noRegistro).disabled=false;
		document.getElementById("txt_color" + noRegistro).disabled=false;
	}
	else{
		document.getElementById("txt_precioMN" + noRegistro).disabled=true;
		//Reestablecer el valor por default a los campos 
		document.getElementById("txt_precioMN" + noRegistro).value=document.getElementById("txt_precioMN" + noRegistro).defaultValue;
		
		document.getElementById("txt_precioUSD" + noRegistro).disabled=true;
		//Reestablecer el valor por default a los campos 
		document.getElementById("txt_precioUSD" + noRegistro).value=document.getElementById("txt_precioUSD" + noRegistro).defaultValue;
		
		document.getElementById("txt_color" + noRegistro).disabled=true;
		//Reestablecer el valor por default a los campos 
		document.getElementById("txt_color" + noRegistro).value=document.getElementById("txt_color" + noRegistro).defaultValue;
		//Colocar el color por default
		document.getElementById("txt_color" + noRegistro).style.backgroundColor="#"+document.getElementById("txt_color" + noRegistro).value;		
	}
}


//Esta funcion se encarga de validar los datos de cada lista de precios, cuando quieren ser modificados
function valFormModificarPrecios(frm_modificarListaPrecios){
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var res = 1;	
	//Variable para manejar el mensaje de validación satisfactoria
	var msg = 0;
	//Variable para saber si al menos un precio fue seleccionado
	var status = 0;
	//Variable para controlar la cantidad de registros
	var ctrl= 1;
	
	//Obtener la Cantidad de Registros desplegados al Usuario		
	var cantidad = frm_modificarListaPrecios.cant_ckbs.value;	
					
	//Variables que almacenara el nombre de cada CheckBox Seleccionado y las cajas de texto de cprecio MN y precio USD relacionada a el
	var idCheckBox = "";
	var txt_precioMN = "";
	var txt_precioUSD = "";
	
	while(ctrl<=cantidad){		
		//Crear el id del CheckBox que se quiere verificar
		idCheckBox="ckb_precio"+ctrl.toString();
		
		//Si el CheckBox est seleccionado, validar los campos del registro del traspaleo
		if(document.getElementById(idCheckBox).checked){
			status = 1;
						
			//Crear los Id de las Cajas de Texto que se quieren validar
			txt_precioMN = "txt_precioMN"+ctrl.toString();
			txt_precioUSD = "txt_precioUSD"+ctrl.toString();
			
			//Validar que el precio   UMN  no este vacio
			if(document.getElementById(txt_precioMN).value==""){				
				alert("Ingresar Precio Unitario Moneda Nacional");
				msg = 1;
			}
			//Validar que el precio   UMN sea un numero entero valido
			if(msg==0){
				if(!validarEntero(document.getElementById(txt_precioMN).value.replace(/,/g,''),"El Precio Moneda Nacional"))
					msg = 1;
			}																		
			//Validar que la cantiad del precio USD no este vacia
			if(document.getElementById(txt_precioUSD).value=="" && msg==0){				
				alert("Ingresar Precio Unitario en Dolares");
				msg = 1;
			}
			//Validar que el precio   USD sea un numero entero valido
			if(msg==0){
				if(!validarEnteroConCero(document.getElementById(txt_precioUSD).value.replace(/,/g,''),"El Precio de Dolares"))
					msg = 1;
			}															

		}//Cierre if(document.getElementById(idCheckBox).checked)
		//Esta variable indica el renglon en el que se esta trabajando
		ctrl++;
	}//Fin del While	
	
	//Verificar que al menos un precio haya sido seleccionado
	if(status==1){//Si la variable status vale 1, quiere decir que al menos un precio fue seleccionado		
		if(msg==1)//Si hubo algun mensaje de que falta ingresar un dato, no se cumplio con el proceso de validacion 
			res = 0;
	}
	else{
		alert("Seleccionar al Menos un Registro para ser Modificado");
		res = 0;
	}
	
	if(res==1)
		return true;
	else
		return false;
}

//Funcion que verifica que todos los campos esten llenos para 
function valFormPrecios(frm_listaPrecios){
	//Variable que controla el ciclo que verifica que los datos esten completos
	var cont=frm_listaPrecios.hdn_contador.value;
	var band=1;
	//variable que nos indicara en que registro falta completar algun dato
	var ctrl=0;
	//variable que indica en que columna falta un dato si es 1=> unidad,2=> precioMN,3=> precioUSD
	var error=0;

	//Recorrer el arreglo 
	for(var i=0; i<cont; i++){
		if (document.getElementById('txt_unidad'+(i+1)).value==""){
			band=0;
			ctrl=i+1;
			error=1;
			break;
		}
		if (document.getElementById('txt_precioMN'+(i+1)).value==""){
			band=0;
			ctrl=i+1;
			error=2;
			break;
		}
		if (document.getElementById('txt_precioUSD'+(i+1)).value==""){
			band=0;
			ctrl=i+1;
			error=3;
			break;
		}		
	}
	
	if (band==0 && error ==1)
		alert("Ingresar el Tipo de Unidad de la Distancia Inicial "+ document.getElementById('txt_inicial'+ ctrl).value);
	if (band==0 && error==2)
		alert("Ingresar el Precio MN de la Distancia Inicial "+ document.getElementById('txt_inicial'+ ctrl).value);
	if (band==0 && error==3)
		alert("Ingresar el Precio USD de la Distancia Inicial "+ document.getElementById('txt_inicial'+ ctrl).value);
	
	if(band==1)
		return true;	
	else
		return false;	
}


/**********************************************************************************************************************************************************/
/********************************************************FORMULARIO REGISTRAR OBRAS************************************************************************/
/**********************************************************************************************************************************************************/
/*Esta función se encarga de validar el formulario de generar Obra*/
function valFormGenerarObra(frm_registrarObra){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	band = 1;
	
	//Verificar si el nombre de la obra ya se encuentra registrado en la BD de Topografia
	if(frm_registrarObra.hdn_claveValida.value=="no"){
		//Notificar al usuario sobre nombre repetido
		alert("El Nombre de la Obra ya se Encuentra Registrado \nIntroducir Otro Nombre");
		//Borrar los datos que indican que el nombre de la obra esta repetido para forzar al usuario a introducir otro
		frm_registrarObra.txt_nombreObra.value = "";
		frm_registrarObra.hdn_claveValida.value = "si";
		document.getElementById("error").style.visibility = "hidden";
		
		band = 0;
	}
	else if(frm_registrarObra.hdn_claveValida.value=="si"){
	
		//Verificar que el campo de txt_obra no este vacío
		if(frm_registrarObra.cmb_idPrecios.value=="" && band==1){
			alert("Seleccionar la Categoria de Precios");
			band = 0;
		}
		//Verificar que sea seleccionada una Categoria para la Obra
		if(frm_registrarObra.cmb_categoria.value=="" && band==1){
			alert("Seleccionar la Categoría en la que Será Incluida la Obra");
			band = 0;
		}
		//Verificar que el campo de txt_obra no este vacío
		if(frm_registrarObra.cmb_tipoObra.value=="" && band==1){
			alert("Seleccionar o Introducir el Tipo de Obra");
			band = 0;
		}
		//Verificar que el combo de cmb_subtipo no este vacío
		if(frm_registrarObra.cmb_subtipo.value=="" && band==1){
			alert("Seleccionar el Subtipo de Obra");
			band = 0;
		}
		//Verificar que el campo de precio MN de la Estimacion no este vacío
		if(frm_registrarObra.txt_precioEstimacionMN.value=="" && band==1){
			alert("Introducir el Precio Unitario en Moneda Nacional de la Estimación");
			band = 0;
		}
		
		if(band==1){
			if(!validarEntero(frm_registrarObra.txt_precioEstimacionMN.value.replace(/,/g,''),"El Precio Unitario en M.N. de la Estimación"))
				band = 0;		
		}
		
		//Verificar que el campo de precio USD de la estimación no este vacío
		if(frm_registrarObra.txt_precioEstimacionUSD.value=="" && band==1){
			alert("Introducir el Precio Unitario en USD de la Estimación");
			band = 0;
		}
		if(band==1){
			if(!validarEnteroConCero(frm_registrarObra.txt_precioEstimacionUSD.value.replace(/,/g,''),"El Precio Unitario en USD de la Estimación"))
				band = 0;		
		}
		//Verificar que haya sido ingresado la seccion
		if(frm_registrarObra.txt_seccion.value=="" && band==1){
			alert("Introducir la Sección");
			band = 0;
		}
		//Verificar que el campo de unidad no este vacío
		if(frm_registrarObra.txt_unidad.value=="" && band==1){
			alert("Introducir la Unidad");
			band = 0;
		}
		//Verificar que haya sido ingresado el nombre de la seccion
		if(frm_registrarObra.txt_nombreObra.value=="" && band==1){
			alert("Introducir el Nombre de la Obra");
			band = 0;
		}
	}//Cierre else if(frm_registrarObra.hdn_claveValida.value=="si")
	
	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}//Cierre de la funcion valFormGenerarObra(frm_registrarObra)


//Funcion que permite calcular el area de una seccion al registra la obra
function calcularArea(){
	//variable que almacena el valor contenido en la caja de texto, para realizar operaciones de manera mas rapida
	var seccion = document.getElementById("txt_seccion").value.toUpperCase();
	//Variable que guarda el valor antes del signo
	var numIni = "";
	//Variable que guarda el valor despues del signo
	var numFin = "";
	//Variable que verifica cuando se cambia de variable para acumular el numero
	var band = 0;
	//Variable que guarda el tipo de operacion a realizar
	var op = "";	
	//Variable que almacena el caracter en la posición especificada 
	var car = '';	 	
	//Variable para verificar si hubo errores
	var error = 0;
	
	//Recorremos la cadena para definir las variables necesarias para la operacion
	for(i=0;i<seccion.length;i++){
		//Igualamos el valor de seccion a car para su facil manejo
		car = seccion.charAt(i);
		
		//Verificamos si viene alguno de los caracteres para realizar operacion
		if(car=='X' || car=='/' || car=='+' || car=='-'){
			//Validar que no haya mas de dos operadores en la Seccion
			if(band==1){
				error = 1;
				break;//Romper el 'for'
			}
			
			//De ser asi guardamos el caracter en la variable op
			op = car;
			//Cambiamos la bandera a 1 indicando que se procedera a darle valor a numFin
			band = 1;									
		}
		else{
			//Si band viene en 0 aun no se a encontrado un signo por lo cual almacenamos en numIni los valores antes de el
			if(band==0)
				numIni += car;
			
			//Si band viene en 1 entonces encontramos el signo y es neceario guardar el valor en la segunda variable
			if(band==1)			
				numFin += car;
			
		}	
	}//Cierre for(i=0;i<seccion.length;i++)
	
	
	//Revisar que los numeros obtenidos sean validos
	if(band==0){
		if(!validarEntero(numIni,"El Primer Número"))	
			error = 1;
	}
	
	if(band==1&&error==0){
		if( !(validarEntero(numIni,"El Primer Numero") && validarEntero(numFin,"El Segundo Número")) )
			error = 1;
	}
		
	
	//Si band permanece en 0 quiere decir que solo se ah ingresado un numero y area tomara el valor de este
	if(band==0){
		formatCurrency(numIni,"txt_area");
	}				
	
	//Variable que controla el total de la operacion segun corresponda
	var total = "";
	//Verificamos que la bandera se encuentre en 1 si es asi podemos realiza operacion, pues existe numIni, numFin
	if(band==1&&error==0){
		if(op=="X"){
			//Converimos a float los numeros y guardamos el valor en total
			total=parseFloat(numIni)*parseFloat(numFin);
		}
		if(op=="/"){
			total=parseFloat(numIni)/parseFloat(numFin);
		}
		if(op=="+"){
			total=parseFloat(numIni)+parseFloat(numFin);
		}
		if(op=="-"){
			total=parseFloat(numIni)-parseFloat(numFin);
		}
		
		//Asignar el valor obtenido
		formatCurrency(total,"txt_area");
	}
	else{ //Mandar Mensaje
		if(error==1){
			alert("La Sección no Cuenta con el Formato Requerido");
			document.getElementById("txt_area").value = "";
			document.getElementById("txt_seccion").value = "";
		}
	}		
}//Cierre de la funcion calcularArea()


//Funcion que permite agregar una nueva opcion, no existente a un combo box (Combo de Tipo Obra de la pagina de Registrar Obras)
function agregarNvoTipoObra(comboBox){
	//Si la opcion seleccionada es agregar nueva unidad ejecutar el siguiete codigo
	if(comboBox.value=="NUEVA"){
		var nvoTipo = "";
		var condicion = false;
		do{
			nvoTipo = prompt("Introducir Nuevo Tipo Obra","Nuevo Tipo...");
			if(nvoTipo=="Nuevo Tipo..." ||  nvoTipo=="")
				condicion = true;	
			else
				condicion = false;
		}while(condicion);
		
		//Si el usuario presiono calncelar no se relaiza ninguan actividad de lo contrario asignar la nueva opcion al combo
		if(nvoTipo!=null){
			//Convertir a mayusculas la opcion dada
			nvoTipo = nvoTipo.toUpperCase();
			//variable que nos ayudara a saber si la nueva opcion ya esta registrada en el combo
			var existe = 0;
			
			for(i=0; i<comboBox.length; i++){
				//verificar que la nueva opcion no se encuentre dentro de las opciones actuales del combo
				if(comboBox.options[i].value==nvoTipo)
					existe = 1;
			} //FIN for(i=0; i<comboBox.length; i++)
			
			//Si la nva opcion no esta registrada agregarla como una adicional y preseleccionarla
			if(existe==0){
				//Agregar al final la nueva opcion seleccionada
				comboBox.length++;
				comboBox.options[comboBox.length-1].text = nvoTipo;
				comboBox.options[comboBox.length-1].value = nvoTipo;
				//Preseleccionar la opcion agregada
				comboBox.options[comboBox.length-1].selected = true;
			} // FIN if(existe==0)
			
			else{
				alert("El Tipo de Obra Ingresado ya esta Registrado \n en las Opciones de la Lista de Tipos de Obra");
				comboBox.value = nvoTipo;
			}
		}// FIN if(nvaMedida!= null)
		
		else if(nvoTipo== null){
			comboBox.value = "";	
		}
	}// FIN if(comboBox.value=="NUEVA")
}//Cierre de la función function agregarNvoTipoObra(comboBox)


/************************************************************************************************************************************************************************/
/****************************************************************MODIFICAR OBRA*******************************************************************/
/************************************************************************************************************************************************************************/


/*Esta función se encarga de validar el primer formulario de Modificar Obra*/
function valFormSeleccionarObra(frm_modificarObra){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	band = 1;
	
	//Verificar que el campo de cmb_tipoObra no este vacío
	if(frm_modificarObra.cmb_tipoObra.value=="" && band==1){
		alert("Seleccionar el Tipo de Obra a Modificar");
		band = 0;
	}
		//Verificar que el campo de cmb_nomObra no este vacío
	if(frm_modificarObra.cmb_nomObra.value=="" && band==1){
		alert("Seleccionar el Nombre de la Obra");
		band = 0;
	}		
	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}


/*Esta función se encarga de validar el segundo formulario de modificar Obra*/
function valFormModificarObra(frm_modificarObra){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	band = 1;
	
	//Verificar si el nombre de la obra ya se encuentra registrado en la BD de Topografia
	if(frm_modificarObra.hdn_claveValida.value=="no"){
		//Notificar al usuario sobre nombre repetido
		alert("El Nombre de la Obra ya se Encuentra Registrado \nIntroducir Otro Nombre");
		//Borrar los datos que indican que el nombre de la obra esta repetido para forzar al usuario a introducir otro
		frm_modificarObra.txt_nombreObra.value = "";
		frm_modificarObra.hdn_claveValida.value = "si";
		document.getElementById("error").style.visibility = "hidden";
		
		band = 0;
	}
	else if(frm_modificarObra.hdn_claveValida.value=="si"){
		//Verificar que el campo de txt_obra no este vacío
		if(frm_modificarObra.cmb_idPrecios.value=="" && band==1){
			alert("Seleccionar la Categoria de Precios");
			band = 0;
		}
		//Verificar que sea seleccionada una Categoria para la Obra
		if(frm_modificarObra.cmb_categoria.value=="" && band==1){
			alert("Seleccionar la Categoría en la que Será Incluida la Obra");
			band = 0;
		}
		//Verificar que el campo de cmb_tipoObra no este vacío
		if(frm_modificarObra.cmb_tipoObra.value=="" && band==1){
			alert("Seleccionar o Introducir el Tipo de Obra");
			band = 0;
		}
		//Verificar que el combo de cmb_subtipo no este vacío
		if(frm_modificarObra.cmb_subtipo.value=="" && band==1){
			alert("Seleccionar el Subtipo de Obra");
			band = 0;
		}
		//Verificar que el campo de precio MN de la Estimacion no este vacío
		if(frm_modificarObra.txt_precioEstimacionMN.value=="" && band==1){
			alert("Introducir el Precio Unitario en Moneda Nacional de la Estimación");
			band = 0;
		}
		if(band==1){
			if(!validarEntero(frm_modificarObra.txt_precioEstimacionMN.value.replace(/,/g,''),"El Precio Unitario en M.N. de la Estimación"))
				band = 0;		
		}
		//Verificar que el campo de precio USD de la estimación no este vacío
		if(frm_modificarObra.txt_precioEstimacionUSD.value=="" && band==1){
			alert("Introducir el Precio Unitario en USD de la Estimación");
			band = 0;
		}
		if(band==1){
			if(!validarEnteroConCero(frm_modificarObra.txt_precioEstimacionUSD.value.replace(/,/g,''),"El Precio Unitario en USD de la Estimación"))
				band = 0;		
		}
		//Verificar que haya sido ingresado la seccion
		if(frm_modificarObra.txt_seccion.value=="" && band==1){
			alert("Ingresar la Sección");
			band = 0;
		}
		//Verificar que el campo de unidad no este vacío
		if(frm_modificarObra.txt_unidad.value=="" && band==1){
			alert("Introducir la Unidad");
			band = 0;
		}
		//Verificar que haya sido ingresado el nombre de la seccion
		if(frm_modificarObra.txt_nombreObra.value=="" && band==1){
			alert("Introducir el Nombre de la Obra");
			band = 0;
		}
	}//Cierre else if(frm_modificarObra.hdn_claveValida.value=="si")
		
		
	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}
/**********************************************************************************************************************************************************/
/*********************************************************FORMULARIO CONSULTAR OBRA************************************************************************/
/**********************************************************************************************************************************************************/

/*Esta funcion valida que las fechas elegidas sean correctas*/
function valFormFechasObras(frm_consultarObraFecha){
	var res=1;
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_consultarObraFecha.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_consultarObraFecha.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_consultarObraFecha.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Cierre, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_consultarObraFecha.txt_fechaFin.value.substr(0,2);
	var finMes=frm_consultarObraFecha.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_consultarObraFecha.txt_fechaFin.value.substr(6,4);
	
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	//Verificar que el año de Fin sea mayor al de Inicio
	if(fechaIni>fechaFin){
		res=0;
		alert ("La fecha de Inicio no puede ser mayor a la Fecha de Cierre");
	}
	
	if(res==1)
		return true;
	else
		return false;
}


/*Esta función se encarga de validar el formulario de consultar obra por medio del tipo y Nombre de la Obra*/
function valFormconsultarObraTipo(frm_consultarObraTipo){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	band = 1;
	
	//Verificar se haya seleccionado una obra en el combo
	 if(frm_consultarObraTipo.cmb_nomObra.value==""){
		alert("Seleccionar el Tipo y Posteriormente el Nombre de la Obra");
		band = 0;
	}

	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}


/**********************************************************************************************************************************************************/
/*****************************************************FORMULARIO CONSULTAR CONCILIACIÓN************************************************************************/
/**********************************************************************************************************************************************************/
/*Esta función se encarga de validar el formulario de consultar conciliación*/
function valFormConsultarConciliacion(frm_consultarConciliacion){
	//Si estado permanece en 1, el proceso de validacion fue satisfactorio
	var status = 1;
	
	if( (frm_consultarConciliacion.cmb_noQuincena.value=="" || frm_consultarConciliacion.cmb_mes.value=="" || frm_consultarConciliacion.cmb_anio.value=="") && status==1){
		alert("Introducir los Datos del Numero de la Quincena Que se Desea Consultar");
		status = 0;
	}

					
	if(status==1)
		return true;
	else
		return false;
}



function complementarConciliacion(){
	var ctrlForm = 0;//Variable de control para cuando aparezca el formulario	
	var contratista="";//Se tiene que declarar esta variable como nula para que se pueda cancelar en el momento que desee el usuario
	while(contratista=="Nombre del Contratista..." || contratista==""){
		var contratista = prompt('Ingrese Nombre del Contratista','Nombre del Contratista...' );

		if(contratista==null)
			ctrlForm = 1;	
	}
	
	if(ctrlForm==0){
		//Asignar el valor obtenido a la caja de texto que lo mostrara
		document.getElementById("hdn_contratista").value=contratista;
	}

	if(ctrlForm==0){
		jefeSeccion="";		
		while(jefeSeccion=="Nombre del Jefe de Sección..." || jefeSeccion==""){
			var jefeSeccion = prompt('Ingrese Nombre del Jefe de Sección','Nombre del Jefe de Sección...');
			
			if(jefeSeccion==null)
				ctrlForm = 1;	
		}
		
		if(ctrlForm==0){
			//Asignar el valor obtenido a la caja de texto que lo mostrara
			document.getElementById("hdn_jefeSeccion").value=jefeSeccion;
		}
	}
	
	
	if(ctrlForm==0){
		revisor="";		
		while(revisor=="Nombre de la Persona que Revisó..." || revisor==""){
			var revisor = prompt('Ingrese Nombre del Revisor','Nombre de la Persona que Revisó...');
			
			if(revisor==null)
				ctrlForm = 1;	
		}
				
		if(ctrlForm==0){
			//Asignar el valor obtenido a la caja de texto que lo mostrara
			document.getElementById("hdn_revisor").value=revisor;	
		}
	}
	
	
	if(ctrlForm==0)
		return true;
	else
		return false;
}
/**********************************************************************************************************************************************************/
/**********************************************************ESTIMACION EQUIPO PESADO************************************************************************/
/**********************************************************************************************************************************************************/
/*Funcion que valida el formulario para ingresar una obra con Equipo Pesado*/
function valFormRegObraEP(frm_registrarObraEP){
	var res=1;
	
	if(frm_registrarObraEP.cmb_familia.value==""){
		alert("Seleccionar la Familia del Equipo");
		res=0;
	}
	
	if(res==1 && frm_registrarObraEP.txt_unidad.value==""){
		alert("Ingresar la Unidad de Medida");
		res=0;
	}
	
	if(res==1 && frm_registrarObraEP.txt_precioEstimacionMN.value=="0.00"){
		alert("El Precio en Moneda Nacional debe ser Mayor a $0.00");
		res=0;
	}
	
	if(res==1 && frm_registrarObraEP.txt_nombreObraEqP.value==""){
		alert("Ingresar el Nombre de la Obra de Equipo Pesado");
		res=0;
	}
	
	if(res==1 && frm_registrarObraEP.hdn_claveValida.value!="si"){
		alert("Ingresar un Nombre Válido para la Obra de Equipo Pesado");
		res=0;
	}
	
	if(res==1)
		return true;
	else
		return false;
}//Fin de function valFormRegObraEP(frm_registrarObraEP)

//Funcion que valida el formulario de modificacion de Obras de Equipo Pesado
function valFormSeleccionarObraEq(frm_modificarObraEqP){
	if(frm_modificarObraEqP.cmb_tipoObraEqP.value==""){
		alert("Seleccionar el Tipo de Equipo");
		return false;
	}
	if(frm_modificarObraEqP.cmb_nomObraEq.value==""){
		alert("Seleccionar la Obra con Equipo Pesado");
		return false;
	}
}

/**********************************************************************************************************************************************************/
/************************************************************REGISTRO EQUIPO PESADO************************************************************************/
/**********************************************************************************************************************************************************/
function valFormElimBitEqP(frm_eliminarEquipoPesado){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_eliminarEquipoPesado.rdb_idRegistro.length==undefined && !frm_eliminarEquipoPesado.rdb_idRegistro.checked){
		alert("Seleccionar el Registro a Borrar");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_eliminarEquipoPesado.rdb_idRegistro.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_eliminarEquipoPesado.rdb_idRegistro.length;i++){
			if(frm_eliminarEquipoPesado.rdb_idRegistro[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar el Registro a Borrar");			
	}
	if (res==1){
		if (!confirm("¿Esta Seguro que Quiere Borrar el Registro?\nToda la información relacionada se Borrará")){
			res=0;
		}
	}
	if(res==1)
		return true;
	else
		return false;
}

/*Validacion para modificar un registro*/
function valFormModBitEqP(frm_eliminarEquipoPesado){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_eliminarEquipoPesado.rdb_idRegistro.length==undefined && !frm_eliminarEquipoPesado.rdb_idRegistro.checked){
		alert("Seleccionar el Registro a Modificar");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_eliminarEquipoPesado.rdb_idRegistro.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_eliminarEquipoPesado.rdb_idRegistro.length;i++){
			if(frm_eliminarEquipoPesado.rdb_idRegistro[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar el Registro a Modificar");			
	}
	
	if(res==1)
		return true;
	else
		return false;
}

//Funcion que obtiene el total en dolares y el total de importe segun la bitacora de equipos
function obtenerTotalUSD(tasa){
	//Precio Unitario en Dolares
	var precioUUSD=document.getElementById("txt_precioUUSD").value.replace(/,/g,'');
	//Cantidad Total
	var total=document.getElementById("txt_cantidadTotal").value.replace(/,/g,'');
	//Obtener el Total en USD
	var precioUSD=(precioUUSD*total*tasa);
	//Asignar el precio total en Dolares
	formatCurrency(precioUSD,"txt_totalUSD");
	//Obtener el Importe
	var importe=(precioUSD*1)+(document.getElementById("txt_totalMN").value.replace(/,/g,'')*1);
	//Asignar el importe
	formatCurrency(importe,"txt_totalImporte");
}

//Funcion que valida el formulario de horas empleadas por equipo
function vslFormSeleccionarEquipo(frm_registrarDatosEquipo){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
	
	if(frm_registrarDatosEquipo.cmb_equipo.value==""){
		alert("Seleccionar el Equipo");
		res=0;
		frm_registrarDatosEquipo.cmb_equipo.focus();
	}
	if(res==1 && frm_registrarDatosEquipo.txt_cantidad.value==""){
		alert("Ingresar la Cantidad de "+frm_registrarDatosEquipo.txt_unidad.value);
		res=0;
		frm_registrarDatosEquipo.txt_cantidad.focus();
	}
	
	if(res==1)
		return true;
	else
		return false;
}

function valFormRegBitEquipo(formulario){
	var res=1;
	
	if(formulario.cmb_noQuincena.value==""){
		alert("Seleccionar el Número de Quincena");
		res=0;
		formulario.cmb_noQuincena.focus();
	}
	
	if(res==1 && formulario.cmb_Mes.value==""){
		alert("Seleccionar el Mes");
		res=0;
		formulario.cmb_Mes.focus();
	}
	
	if(res==1 && formulario.cmb_Anio.value==""){
		alert("Seleccionar el Año");
		res=0;
		formulario.cmb_Anio.focus();
	}
	
	if(res==1)
		return true;
	else
		return false;
}



/**********************************************************************************************************************************************************/
/***************************************************************EDICION DE SUBTIPOS************************************************************************/
/**********************************************************************************************************************************************************/
function valFormSubtipos(frm_actualizarSubtipos){
	//Recorrer el arreglo de Elementos del Formulario
	for (ind=0;ind<frm_actualizarSubtipos.elements.length;ind++){ 
		//Verificar si los elementos son de tipo Texto
		if(frm_actualizarSubtipos.elements[ind].type=="text"){
			//Obtener el ID del Elemento en caso de ser Texto
			idElemento=frm_actualizarSubtipos.elements[ind].id;
			//Verificar si cumple con el nombre del ID
			if(idElemento.substring(0,9)=="txt_orden"){
				var orden=document.getElementById(idElemento).value;
				for (busq2=0;busq2<frm_actualizarSubtipos.elements.length;busq2++){
					if(frm_actualizarSubtipos.elements[busq2].id.substring(0,9)=="txt_orden" && frm_actualizarSubtipos.elements[busq2].id!=idElemento){
						if(orden==document.getElementById(frm_actualizarSubtipos.elements[busq2].id).value){
							flag=1;
							alert("Valores para Órden Repetidos. Verificarlos");
							document.getElementById(idElemento).style.background="#ff0";
							document.getElementById(frm_actualizarSubtipos.elements[busq2].id).style.background="#ff0";
							return false;
						}
						else{
							document.getElementById(idElemento).style.background="#FFF";
							document.getElementById(frm_actualizarSubtipos.elements[busq2].id).style.background="#FFF";
						}
					}
				}
			}//if(idElemento!="")
		}//if(document.forms[i].elements[i].type=="text")
	}//Fin del For
	//Recorrer el arreglo de Elementos del Formulario
	for (ind=0;ind<frm_actualizarSubtipos.elements.length;ind++){ 
		//Verificar si los elementos son de tipo Texto
		if(frm_actualizarSubtipos.elements[ind].type=="text"){
			//Obtener el ID del Elemento en caso de ser Texto
			idElemento=frm_actualizarSubtipos.elements[ind].id;
			//Verificar si cumple con el nombre del ID
			if(idElemento.substring(0,9)=="txt_orden"){
				if(document.getElementById(idElemento).value==""){
					alert("Ingresar el Número de Órden");
					document.getElementById(idElemento).focus();
					return false;
				}
			}//if(idElemento.substring(0,9)=="txt_orden")
			if(idElemento.substring(0,22)=="txt_nombreSubcategoria"){
				if(document.getElementById(idElemento).value==""){
					alert("Ingresar el Nombre de Subcategoría");
					document.getElementById(idElemento).focus();
					return false;
				}
			}//if(idElemento.substring(0,22)=="txt_nombreSubcategoria")
			if(idElemento.substring(0,22)=="txt_precioEstimacionMN"){
				if(document.getElementById(idElemento).value=="0.00"){
					alert("Ingresar el Precio Unitario en Pesos");
					document.getElementById(idElemento).focus();
					return false;
				}
			}//if(idElemento.substring(0,22)=="txt_precioEstimacionMN")
		}//if(frm_actualizarSubtipos.elements[i].type=="text")
	}//Fin del For
	return true;
}

//Funcion que restablece el formulario de subtipos
function restablecerFormSubtipos(){
	//Recorrer el arreglo de Elementos del Formulario
	for (ind=0;ind<frm_actualizarSubtipos.elements.length;ind++){ 
		//Verificar si los elementos son de tipo Texto
		if(frm_actualizarSubtipos.elements[ind].type=="text"){
			//Obtener el ID del Elemento en caso de ser Texto
			idElemento=frm_actualizarSubtipos.elements[ind].id;
			//Regresar a blanco el color de fondo
			document.getElementById(idElemento).style.background="#FFF";
		}//if(frm_actualizarSubtipos.elements[i].type=="text")
	}//Fin del For
}

function valFormEditarSubtipos(frm_editarSubtipos){
	var res=1;
	
	if(frm_editarSubtipos.txt_nombreSubtipo.value==""){
		alert("Ingresar el Nombre del Subtipo");
		frm_editarSubtipos.txt_nombreSubtipo.focus();
		res=0;
	}
	
	if(res==1 && frm_editarSubtipos.txt_precioEstimacionMN.value=="0.00"){
		alert("El Precio en Moneda Nacional debe ser Mayor a 0.00");
		frm_editarSubtipos.txt_precioEstimacionMN.focus();
		res=0;
	}
	
	if(res==1)
		return true;
	else
		return false;
}