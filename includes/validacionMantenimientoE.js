/**
  * Nombre del M�dulo: Paileria                                               
  * �Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 13/Enero/2012
  * Descripci�n: Este archivo contiene funciones para validar los diferentes formularios del M�dulo Paileria
  */
/*****************************************************************************************************************************************************************************************/
/************************************************************************VALIDAR CARACTERES***********************************************************************************************/
/*****************************************************************************************************************************************************************************************/
/*Esta funci�n se encarga de que el usuario no pueda ingresar caracteres invalidos en los campos de los diferentes formulario del M�dulo de Paileria*/
function permite(elEvento, permitidos) {
	//Variables que definen los caracteres permitidos
	var numeros = "0123456789";
	var caracteres = " abcdefghijklmn�opqrstuvwxyzABCDEFGHIJKLMN�OPQRSTUVWXYZ����������";
	var numeros_caracteres = numeros + caracteres;
	var teclas_especiales = [8, 34, 37, 44, 45, 46, 47];//8 = BackSpace, 34 = Comillas Dobles, 37 = Signo Porcentaje, 44 = Coma, 45 = Guion medio, 46 = Punto, 47 = Diagonal
	//Seleccionar los caracteres a partir del par�metro de la funci�n
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
	
	//Obtener la tecla pulsada
	var evento = elEvento || window.event;
	var codigoCaracter = evento.charCode || evento.keyCode;
	var caracter = String.fromCharCode(codigoCaracter);
	
	//Comprobar si la tecla pulsada es alguna de las teclas especiales
	//(teclas de borrado y flechas horizontales)
	var tecla_especial = false;
	for(var i in teclas_especiales) {
		if(codigoCaracter == teclas_especiales[i]) {
			tecla_especial = true;
			break;
		}
	}

	//Comprobar si la tecla pulsada se encuentra en los caracteres permitidos
	//o si es una tecla especial
	return permitidos.indexOf(caracter) != -1 || tecla_especial;
}

/********************************************Funci�n que permite Validar que en las Claves del Material NO se puedan ingresar comillas(")*********************************/

/*Esta funci�n se encarga de que el usuario no pueda ingresar caracteres invalidos en los campos de los diferentes formulario del M�dulo de Almac�n*/
function permiteClavesMaterial(elEvento, permitidos) {
	//Variables que definen los caracteres permitidos
	var numeros = "0123456789";
	var caracteres = " abcdefghijklmn�opqrstuvwxyzABCDEFGHIJKLMN�OPQRSTUVWXYZ����������";
	var numeros_caracteres = numeros + caracteres;
	var teclas_especiales = [8, 37, 44, 45, 46, 47];//8 = BackSpace, 37 = Signo Porcentaje, 44 = Coma, 45 = Guion medio, 46 = Punto, 47 = Diagonal
	//Seleccionar los caracteres a partir del par�metro de la funci�n
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
	
	//Obtener la tecla pulsada
	var evento = elEvento || window.event;
	var codigoCaracter = evento.charCode || evento.keyCode;
	var caracter = String.fromCharCode(codigoCaracter);
	
	//Comprobar si la tecla pulsada es alguna de las teclas especiales
	//(teclas de borrado y flechas horizontales)
	var tecla_especial = false;
	for(var i in teclas_especiales) {
		if(codigoCaracter == teclas_especiales[i]) {
			tecla_especial = true;
			break;
		}
	}

	//Comprobar si la tecla pulsada se encuentra en los caracteres permitidos
	//o si es una tecla especial
	return permitidos.indexOf(caracter) != -1 || tecla_especial;
}

/*Esta funci�n verifica que el dato proporcionado sea un numero valido y que a su vez este sea mayor que 0*/
function validarEntero(valor,campo){ 
	var cond = true;
	//Comprobar si es un valor num�rico 
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


/*Esta funci�n verifica que el dato proporcionado sea un numero valido y que a su vez este pueda ser igual a 0*/
function validarEnteroValorCero(valor){ 
	var cond = true;
	//Comprobar si es un valor num�rico 
	if (isNaN(valor)) { 			
		//Numero invalido
		alert ("El Dato: '"+valor+"' es Incorrecto, Solo se Aceptan Numeros");
		cond = false;
	}	
	return cond;
}

/*****************************************************************************************************************************************************************************************/
/****************************************************************************GENERAR REQUISICION******************************************************************************************/
/*****************************************************************************************************************************************************************************************/
/*Esta funci�n valida que sea selecionada una Categor�a y un Material, asi como la Cantida y la Aplicaci�n para ser agregados a la Requisici�n*/
function valFormGenerarRequisicion(frm_generarRequisicion){
	//Si el valor se mantiene en 1, entonces el proceso de validacion fue satisfactorio
	var res = 1;		
	
	if(frm_generarRequisicion.cmb_material.value=="" && frm_generarRequisicion.txt_clave.value==""){
		alert("Seleccionar una Categor�a y Despu�s un Material o Ingresar Clave del Material para Registrar Requisicion");
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
					alert("Introducir la Aplicaci�n del Material que Esta Siendo Solicitado");
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


/*Esta funcion valida los datos del material agregado a la requisici�n, cuando �ste no est� registrado en el Cat�lodo de Almac�n*/
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
						alert("Introducir la Aplicaci�n del Material que Esta Siendo Solicitado");
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


/*Esta funci�n valida la informaci�n complementaria de la Requisici�n que esta siendo generada*/
function valFormInformacionRequisicion(frm_InformacionRequisicion){
//Si el valor se mantiene en 1, entonces el proceso de validacion fue satisfactorio
	var res = 1;	
		
	
	if(frm_InformacionRequisicion.hdn_materialAgregado.value == "si"){						
		if(frm_InformacionRequisicion.txa_justificacionReq.value==""){
			alert("Introducir la Justificaci�n del Material que Esta Siendo Solicitado");
			res = 0;
		}
		else{
			if(frm_InformacionRequisicion.txt_areaSolicitante.value==""){
				alert("Introducir el �rea que Solicita el Material");
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
						alert("Introducir el Nombre de la Persona que Elabora la Requisici�n");
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
		alert ("Introducir Fotograf�a");
		band=0;
	}
	
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

/*****************************************************************************************************************************************************************************************/
/************************************************************************FORMULARIO CONSULTAR REQUISICIONES*******************************************************************************/
/*****************************************************************************************************************************************************************************************/
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

	//Verificar que el a�o de Fin sea mayor al de Inicio
	if(fechaI>fechaF){
		res=0;
		alert ("La fecha de Inicio no puede ser mayor a la Fecha de Cierre");
	}
	
	if(res==1)
		return true;
	else
		return false;
}

/*****************************************************************************************************************************************************************************************/
/************************************************************************EDITAR REGISTROS***********************************************************************************************/
/*****************************************************************************************************************************************************************************************/
/*Esta funcion valida que los datos ingresados en el formulario de editar registro de requisicion no esten vacios*/
function valFormEditarRegistroRequisicion(frm_editarRegistroRequisicion){
	//Si el valor se mantiene en 1 el proceso de validacion fue exitoso
	var band = 1;
	
	//Verificar que el dato de costo y cantidad no esten vacios
	if(frm_editarRegistroRequisicion.txt_cantReq.value==""){
		alert("Ingresar la Cantidad que Ser� Solicitada del Material");
		band = 0;
	}
	else{
		if(frm_editarRegistroRequisicion.txt_aplicacion.value==""){
			alert("Ingresar la Aplicaci�n del Material");
			band = 0;
		}
	}
	
	
	//Validar que sean numeros validos
	if(band==1){
		if(!validarEntero(frm_editarRegistroRequisicion.txt_cantReq.value.replace(/,/g,''),"La Cantidad de Requisici�n del Material"))
			band = 0;
	}
	
	if(band==1)
		return true;
	else
		return false;
	
}