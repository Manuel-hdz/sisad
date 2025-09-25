/**
  * Nombre del Módulo: Seguridad Industrial                                               
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 24/Mayo/2011                                      			
  * Descripción: Este archivo contiene funciones para validar los diferentes formularios del Módulo Seguridad Industrial
  */
/*****************************************************************************************************************************************************************************************/
/************************************************************************VALIDAR CARACTERES***********************************************************************************************/
/*****************************************************************************************************************************************************************************************/
/*Esta función se encarga de que el usuario no pueda ingresar caracteres invalidos en los campos de los diferentes formulario del Módulo de Seguridad Industrial*/
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
	if(te==7){//Para cajas de texto con nombres de documentos
		var teclas_especiales = [8, 45, 46, 95];		
		//8 = BackSpace, 45 = Guion medio, 46 = Punto, 95 = guion bajo
	}
	if(te==8){//Para los campos de niveles y lugar
		var teclas_especiales = [45, 46, 47,];
		//45=Guion Medio, 46=Punto, 47=Diagonal;
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


/*Esta funcion valida que las fechas elegidas sean correctas*/
function valFormFechas(formulario){
	
	//Si el valor se mantiene en 1, el rango de fechas es valido
	var band = 1;
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=formulario.txt_fechaInicio.value.substr(0,2);
	var iniMes=formulario.txt_fechaInicio.value.substr(3,2);
	var iniAnio=formulario.txt_fechaInicio.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=formulario.txt_fechaFin.value.substr(0,2);
	var finMes=formulario.txt_fechaFin.value.substr(3,2);
	var finAnio=formulario.txt_fechaFin.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	//Verificar que el año de Fin sea mayor al de Inicio
	if(fechaIni>fechaFin){
		band=0;
		alert ("La Fecha de Inicio no Puede ser Mayor a la Fecha de Cierre");
	}
	
	if(band==1)
		return true;
	else
		return false;
}


/*Esta funcion solicita la confirmación del usuario antes de salir de la pagina*/
function confirmarSalida(pagina){
	if(confirm("¿Estas Seguro que Quieres Salir?\nToda la información no Guardada se Perderá"))
		location.href = pagina;	
}
/***************************************************************************************************************************************************************/
/*****************************************COMIENZO DE FUNCIONES UTILIZADAS EN LAS PAGINAS***********************************************************************/
/***************************************************************************************************************************************************************/

/***************************************************************************************************************************************************************/
/******************************************************************SECCIÓN DE BITACORA*************************************************************************/
/***************************************************************************************************************************************************************/

//Funcion que nos permite validar el formulario del registro de la bitacora
function valFormRegBitacora(frm_regRegBitacora){
	//Si el valor se mantiene en 1, entonces el proceso de validacion fue satisfactorio
	var res = 1;		
	
	if(frm_regRegBitacora.cmb_residuo.value==""){
		alert("Seleccionar Tipo de Residuo");
		res = 0;
	}
	if(frm_regRegBitacora.txt_clasificacionSol.value==""&&res==1){
		alert("Introducir Clasificación Solido");
		res = 0;
	}
	if(frm_regRegBitacora.txt_area.value==""&&res==1){
		alert("Introducir Área");
		res = 0;
	}	
	if(frm_regRegBitacora.txt_cantGenerada.value==""&&res==1){
		alert("Introducir Cantidad Generada del Residuo");
		res = 0;
	}
	if(frm_regRegBitacora.txt_unidad.value==""&&res==1){
		alert("Introducir Equivalencia del Residuo");
		res = 0;
	}
	if(frm_regRegBitacora.txt_unidad.value!=""&&res==1&&frm_regRegBitacora.txt_unidad.value==0.00){
		alert("La Equivalencia no puede ser Cero (0)");
		res = 0;
	}
	if(frm_regRegBitacora.txt_nomEntrega.value==""&&res==1){
		alert("Introducir El Nombre de Quien Entrega");
		res = 0;
	}
	if(frm_regRegBitacora.txt_nomRecibe.value==""&&res==1){
		alert("Introducir El Nombre de Quien Recibe");
		res = 0;
	}
	if(frm_regRegBitacora.txt_razSocial.value==""&&res==1){
		alert("Introducir Razón Social");
		res = 0;
	}
	if(frm_regRegBitacora.txt_numManifiesto.value==""&&res==1){
		alert("Introducir Número de Manifiesto");
		res = 0;
	}
	if(frm_regRegBitacora.txt_numAutorizacion.value==""&&res==1){
		alert("Introducir Número de Autorización");
		res = 0;
	}
	if(frm_regRegBitacora.txt_nomTransportista.value==""&&res==1){
		alert("Introducir Nombre del Transportista");
		res = 0;
	}
	if(frm_regRegBitacora.txa_descripcion.value==""&&res==1){
		alert("Introducir Manejo del Residuo");
		res = 0;
	}
	if(frm_regRegBitacora.txt_responsableBit.value==""&&res==1){
		alert("Introducir Responsable de la Bitácora");
		res = 0;
	}
	if((!frm_regRegBitacora.ckb_peligrosidadC.checked&&!frm_regRegBitacora.ckb_peligrosidadR.checked&&!frm_regRegBitacora.ckb_peligrosidadE.checked&&!frm_regRegBitacora.ckb_peligrosidadT.checked&&!frm_regRegBitacora.ckb_peligrosidadI.checked)&&res==1){
		alert("Seleccionar Por lo Menos un Caracteristica de Peligrosidad del Residuo");
		res = 0;
	}
	
	//Extraer los datos de la fecha de Ingreso, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_regRegBitacora.txt_fechaIng.value.substr(0,2);
	var iniMes=frm_regRegBitacora.txt_fechaIng.value.substr(3,2);
	var iniAnio=frm_regRegBitacora.txt_fechaIng.value.substr(6,4);
	
	//Extraer los datos de la fecha de Salida, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_regRegBitacora.txt_fechaSal.value.substr(0,2);
	var finMes=frm_regRegBitacora.txt_fechaSal.value.substr(3,2);
	var finAnio=frm_regRegBitacora.txt_fechaSal.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	//Verificar que el año de Fin sea mayor al de Inicio
	if(fechaIni>fechaFin){
		alert ("La Fecha de Salida no Puede ser Mayor a la Fecha de Ingreso");
		res=0;
	}
		
	if(res==1)
		return true;
	else
		return false;
}

/*************************************************************SECCIÓN DE MODIFICAR BITACORA*************************************************************************/

function valFormModBitacora(frm_modRegistro){
//Si el valor se mantiene en 1, el rango de fechas es valido
	var band = 1;
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_modRegistro.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_modRegistro.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_modRegistro.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_modRegistro.txt_fechaFin.value.substr(0,2);
	var finMes=frm_modRegistro.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_modRegistro.txt_fechaFin.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	if(fechaIni>fechaFin){
		band=0;
		alert ("La Fecha de Inicio no Puede ser Mayor a la Fecha de Cierre");
	}
	if(frm_modRegistro.cmb_residuo.value==""&&band==1){
		alert ("Seleccione el Tipo de Residuo");
		band = 0;
	}
	//Verificar que el año de Fin sea mayor al de Inicio
	
	
	if(band==1)
		return true;
	else
		return false;
}


//Funcion que permite modificar el valor de la cajad e texto dependiendo del valor selecionado en los residuos
function restablecerValor(){
	if(document.getElementById("hdn_resOriginal").value=="ACEITE"){
		document.getElementById("txt_clasificacionSol").value="N/A";
		document.getElementById("txt_clasificacionSol").readOnly=true;
	}
	else{
		document.getElementById("txt_clasificacionSol").value="";
		document.getElementById("txt_clasificacionSol").readOnly=false;
	}
}


//Funcion que permite cambiar el submit
function cambiarSubmit(){
	//Recuperamos la variable hidden para saber que submit corresponde
	var boton = document.getElementById("hdn_btn").value;
	if(boton=="sbt_exportar"){
		//Enviar a la pagina donde se mustra el reporte
		document.frm_verDetalle.action = "guardar_reporte.php";
	}
	else{
		//Enviar a la pagina donde se mustra el reporte
		document.frm_verDetalle.action = "frm_modificarBitacora2.php";
		document.frm_verDetalle.submit();
	}
}



/*Esta funcion Preselecciona el concepto segun la ubicación seleccionada*/
function crearConcepto(refComboUbicacion){
	//Obtener el valor de la propiedad 'text' de la opción seleccionada
	var texto = refComboUbicacion.options[refComboUbicacion.selectedIndex].text
	
	//Crear el concepto con el Prefijo "ZARPEO" cuando la opcion seleccionda sea diferente de vacia
	if(refComboUbicacion.value!=""){
		//Variable para crear el concepto
		var concepto = "";		
		//Seperar las palabras de la opcion seleccionada para buscar la palabra "ZARPEO"
		var partes = texto.split(" ");
		//Verificar si la palabra ZARPEO se encuentra dentro del nombre de la Ubicacion
		var existePalabra = false;
		for(i=0;i<partes.length;i++){
			if(partes[i]=="ZARPEO")
				existePalabra = true;
		}
		
		//Si la palabra existe en el nombre de la ubicación dejarlo igual
		if(existePalabra)
			concepto = texto;
		else
			concepto = "ZARPEO "+texto;
			
		//Colocar el concepto creado en la caja de texto de solo lectura
		document.getElementById("txt_concepto").value = concepto;
				
	}
	else
		document.getElementById("txt_concepto").value = "";
}//Cierre de la función crearConcepto(ubicacion)


/***************************************************************************************************************************************/
/*************************************************************GENERAR REQUISICION*******************************************************/
/***************************************************************************************************************************************/
/*Esta función valida que sea selecionada una Categoría y un Material, asi como la Cantidad y la Aplicación para ser agregados a la Requisición*/
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


/*Esta funcion valida que los datos ingresados en el formulario de editar registro de requisicion no esten vacios*/
function valFormEditarRegistroRequisicion(frm_editarRegistroRequisicion){
	//Si el valor se mantiene en 1 el proceso de validacion fue exitoso
	var band = 1;
	
	//Verificar que el dato de costo y cantidad no esten vacios
	if(frm_editarRegistroRequisicion.txt_cantReq.value==""){
		alert("Ingresar la Cantidad que Será Solicitada del Material");
		band = 0;
	}
	if(!validarEntero(frm_editarRegistroRequisicion.txt_cantReq.value,"La Cantidad Solicitada ")&&band==1){
		band=0;
	}
	if(frm_editarRegistroRequisicion.txt_aplicacion.value==""){
		alert("Ingresar la Aplicación del Material");
		band = 0;
	}
		
	//Validar que sean numeros validos
	if(band==1){
		if(!validarEntero(frm_editarRegistroRequisicion.txt_cantReq.value.replace(/,/g,''),"La Cantidad de Requisición del Material"))
			band = 0;
	}
	
	if(band==1)
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

/***************************************************************************************************************************************************************/
/*****************************************************FORMULARIO AGREGAR DOCUMENTOS*****************************************************************************/
/***************************************************************************************************************************************************************/

//Esta funcion solicita al usuario la nueva clasificacion y desabilita el combo de clasificacion
function agregarNuevaClasificacion(ckb_clasificacion, txt_clasificacion, cmb_clasificacion){
	var band=0;
	//Si el checkbox para la nueva clasificacion esta seleccionado, pedir el nombre de dicha clasificacion
	if (ckb_clasificacion.checked){
		var clasificacion = prompt("¿Nombre de Nueva Clasificación?","Nombre de Clasificación...");	
		if(clasificacion!=null && clasificacion!="Nombre de Clasificación..." && clasificacion!=""){
			if(clasificacion.length<=30){
				for(i=0;i<clasificacion.length;i++){
					//Igualamos el valor de seccion a car para su facil manejo
					car = clasificacion.charAt(i);
					//Verificamos que se encuentre en la ultima posicion para ver que no termine en punto
					if(i==clasificacion.length-1){
						if(car=='.'){
							band=1;
						}	
					}
					if(car=='/'||car==':'||car=='*'||car=='?'||car=='"'||car=='<'||car=='>'||car=='|'){
						band=2;
					}
				}//Cierre for(i=0;i<seccion.length;i++)
				if(band==1){
					alert("Nombre No Valido. \nEl Nombre de Archivo No Puede Terminar En Punto.\nEjemplo Valido 1.1.1 ");
					clasificacion.value="";
					ckb_clasificacion.checked = false;
				}
				if(band==2){
					alert("Nombre No Valido. \nNo Son Aceptados Caracteres / : * ? < > | y Comillas.\nEjemplo Valido 1.1.1 ");
					clasificacion.value="";
					ckb_clasificacion.checked = false;
				}
				if(band==0){
					//Asignar el valor obtenido a la caja de texto que lo mostrara
					document.getElementById(txt_clasificacion).value = clasificacion.toUpperCase();
					//Verificar que el combo este definido para poder deshabilitarlo
					if(document.getElementById(cmb_clasificacion)!=null)
						//Deshabilitar el ComboBox para que el usuario no lo pueda modificar
						document.getElementById(cmb_clasificacion).disabled = true;				
				}
			}
			else{
				alert("Alcanzaste el Máximo de 40 Caracteres Permitidos");
				//Regresar False si se presiona el botón cancelar o se asigna un valor equivocado
				ckb_clasificacion.checked = false;
				band=0;
			}
		}
		else{
			//Regresar False si se presiona el botón cancelar o se asigna un valor equivocado
			ckb_clasificacion.checked = false;
		}
	}
	//Si el checkbox para nueva clasificacion se deselecciona, borrar el dato escrito en la caja de texto y reactivar el combo de clasificacion
	else{
		document.getElementById(txt_clasificacion).value = "";
		//Verificar que el combo este definido para poder Habilitarlo
		if (document.getElementById(cmb_clasificacion)!=null){
			//Habilitar el ComboBox y el deseleccionar el CheckBox para que el usuario pueda agregar nueva información
			document.getElementById(cmb_clasificacion).disabled = false;
			//Darle un valor vacio por default
			document.getElementById(cmb_clasificacion).value = "";
		}
	}
}

//Esta funcion solicita al usuario la nueva carpeta y desabilita el combo de carpeta
function agregarNuevaCarpeta(ckb_carpeta, txt_carpeta, cmb_carpeta){
	var band=0;
	var txtCla = document.getElementById("txt_clasificacion").value;
	var cmbCla = document.getElementById("cmb_clasificacion").value;
	if(txtCla!=""|| cmbCla!=""){
		//Si el checkbox para la nueva carpeta esta seleccionado, pedir el nombre de dicha carpeta
		if (ckb_carpeta.checked){
			var carpeta = prompt("¿Nombre de Nueva Carpeta?","Nombre de Carpeta...");	
			if(carpeta!=null && carpeta!="Nombre de Carpeta..." && carpeta!=""){
				if(carpeta.length<=30){
					for(i=0;i<carpeta.length;i++){
						//Igualamos el valor de seccion a car para su facil manejo
						car = carpeta.charAt(i);
						//Verificamos que se encuentre en la ultima posicion para ver que no termine en punto
						if(i==carpeta.length-1){
							if(car=='.'){
								band=1;
							}	
						}
						if(car=='/'||car==':'||car=='*'||car=='?'||car=='"'||car=='<'||car=='>'||car=='|'){
							band=2;
						}
					}//Cierre for(i=0;i<seccion.length;i++)
					if(band==1){
						alert("Nombre No Valido. \nEl Nombre de Archivo No Puede Terminar En Punto.\nEjemplo Valido 1.- AUDITORIAS AMBIENTALES ");
						carpeta.value="";
						ckb_carpeta.checked=false;
					}
					if(band==2){
						alert("Nombre No Valido. \nNo Son Aceptados Caracteres / : * ? < > | y Comillas.\nEjemplo Valido 1.- AUDITORIAS AMBIENTALES ");
						carpeta.value="";
						ckb_carpeta.checked=false;
					}
					if(band==0){			
						//Asignar el valor obtenido a la caja de texto que lo mostrara
						document.getElementById(txt_carpeta).value = carpeta.toUpperCase();
						//Verificar que el combo este definido para poder deshabilitarlo
						if (document.getElementById(cmb_carpeta)!=null)
							//Deshabilitar el ComboBox para que el usuario no lo pueda modificar
							document.getElementById(cmb_carpeta).disabled = true;				
					}
				}
				else{
					alert("Alcanzaste el Máximo de 40 Caracteres Permitidos");
					band=0;
					//Regresar False si se presiona el botón cancelar o se asigna un valor equivocado
					ckb_carpeta.checked = false;
				}
			}
			else{
				//Regresar False si se presiona el botón cancelar o se asigna un valor equivocado
				ckb_carpeta.checked = false;
			}
		}
		//Si el checkbox para nueva carpeta se deselecciona, borrar el dato escrito en la caja de texto y reactivar el combo de carpeta
		else{
			document.getElementById(txt_carpeta).value = "";
			//Verificar que el combo este definido para poder Habilitarlo
			if (document.getElementById(cmb_carpeta)!=null){
				//Habilitar el ComboBox y el deseleccionar el CheckBox para que el usuario pueda agregar nueva información
				document.getElementById(cmb_carpeta).disabled = false;		
				//Darle un valor vacio por default
				document.getElementById(cmb_carpeta).value = "";
			}
		}
	}
	else{
		alert("Es Necesario Seleccionar Primero Una Clasificación");
		document.getElementById("ckb_carpeta").checked = false;
	}
}

//Funcion para validar caracteres al generar carpetas y archivos
function validarCaracteres(campo){
	//Creamos la variable caja para manipular mas facilemente el valor de la misma
	var caja=campo.value;
	//Variable que almacena el caracter en la posición especificada 
	var car = '';
	var band=0;
	//Variable para saber si hubo un punto al final de la cadena; ya que si lo hubo al momento de la generacion del nombre del archivo provocara errores y 
	//el archivo quedara no legible
	//Recorremos la cadena para definir las variables necesarias para la operacion
	for(i=0;i<caja.length;i++){
		//Igualamos el valor de seccion a car para su facil manejo
		car = caja.charAt(i);
		//Verificamos que se encuentre en la ultima posicion para ver que no termine en punto
		if(i==caja.length-1){
			if(car=='.'){
				band=1;
			}
		}
		if(car=='/'||car==':'||car=='*'||car=='?'||car=='"'||car=='<'||car=='>'||car=='|'){
			band=2;
		}
	}//Cierre for(i=0;i<seccion.length;i++)
	if(band==1){
		alert("Nombre No Valido. \nEl Nombre de Archivo No Puede Terminar En Punto.\nEjemplo Valido 1.1.1 ");
		campo.value="";
	}
	if(band==2){
		alert("Nombre No Valido. \nNo Son Aceptados Caracteres / : * ? < > | y Comillas.\nEjemplo Valido 1.1.1 ");
		campo.value="";
	}
}

/*Esta función se encarga de validar el formulario de Registro de Documentos*/
function valFormRegDocumentos(frm_agregarDocumento){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	band = 1;
	
	//Verificar que el campo de txt_nom_plano no este vacío
	if(frm_agregarDocumento.txt_idDocumento.value==""){
		alert("Introducir el Id. del Documento");
		band = 0;
	}
	
	//Verificar que el campo de txt_nom_plano no este vacío
	if(frm_agregarDocumento.txt_nomDoc.value==""&&band==1){
		alert("Introducir el Nombre del Documento");
		band = 0;
	}
	//Verificar que el campo frm_agregarPlano no este vacío
	if(frm_agregarDocumento.file_documento.value=="" && band==1){
		alert("Introducir el Documento a Registrar");
		band = 0;
	}
		
	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}

//Funcion que permite desactivar el combo clasificacion en caso de que se haya seleccionado la carpeta como vacia
function desactivarCombo(){
	//varialble que permite almacenar el valor del combo para el facil manejo durante el proceso de validacion
	var comboCarpeta=document.getElementById("cmb_carpeta").value;
	var comboClasi=document.getElementById("cmb_clasificacion").value;
	var txtCarpeta=document.getElementById("txt_carpeta").value;
	var txtClasi=document.getElementById("txt_clasificacion").value;
	var bandera=0;
	if(comboClasi==""&&txtClasi==""){
		bandera=1;
	}
	if(bandera==1){
		alert("Es Necesario Seleccionar Primero Una Clasificación y Posteriormente Una Carpeta");	
		if(txtCarpeta!=""){
			document.getElementById("ckb_carpeta").checked=false;
			document.getElementById("txt_carpeta").value="";
			document.getElementById("cmb_carpeta").disabled=false;
			document.getElementById("cmb_carpeta").value="";
		}
		if(comboCarpeta!=""){
			document.getElementById("cmb_carpeta").value="";
		}
	}
}

/***************************************************************************************************************************************************************/
/*****************************************************FORMULARIO MODIFICAR DOCUMENTOS***************************************************************************/
/***************************************************************************************************************************************************************/

//Esta funcion solicita al usuario la nueva clasificacion y desabilita el combo de clasificacion
function agregarNuevaClasificacionMod(ckb_clasificacion, txt_clasificacion, cmb_clasificacion){
	//Variable que nos permite controlar si hubo caracteres no aceptados para la generacion de un archivo
	var band=0;
	//Creamos variable para guardar el valor original del combo
	var combo=document.getElementById("hdn_comboClasificacion").value;
	if(combo==""){
		combo="";
	}
	//Si el checkbox para la nueva clasificacion esta seleccionado, pedir el nombre de dicha clasificacion
	if(ckb_clasificacion.checked){
		var clasificacion = prompt("¿Nombre de Nueva Clasificación?","Nombre de Clasificación...");	
		if(clasificacion!=null && clasificacion!="Nombre de Clasificación..." && clasificacion!=""){
			if(clasificacion.length<=30){
				for(i=0;i<clasificacion.length;i++){
					//Igualamos el valor de seccion a car para su facil manejo
					car = clasificacion.charAt(i);
					//Verificamos que se encuentre en la ultima posicion para ver que no termine en punto
					if(i==clasificacion.length-1){
						if(car=='.'){
							band=1;
						}
					}	
					if(car=='/'||car==':'||car=='*'||car=='?'||car=='"'||car=='<'||car=='>'||car=='|'){
						band=2;
					}
				}//Cierre for(i=0;i<seccion.length;i++)
				if(band==1){
					alert("Nombre No Valido. \nEl Nombre de Archivo No Puede Terminar En Punto.\nEjemplo Valido 1.1.1 ");
					clasificacion.value="";
					clasificacion.value=combo;
				}
				if(band==2){
	
					alert("Nombre No Valido. \nNo Son Aceptados Caracteres / : * ? < > | y Comillas.\nEjemplo Valido 1.1.1 ");
					clasificacion.value="";
					clasificacion.value=combo;
				}
				if(band==0){
					//Asignar el valor obtenido a la caja de texto que lo mostrara
					document.getElementById(txt_clasificacion).value = clasificacion.toUpperCase();
					//Verificar que el combo este definido para poder deshabilitarlo
					if(document.getElementById(cmb_clasificacion)!=combo){
						//Deshabilitar el ComboBox para que el usuario no lo pueda modificar
						document.getElementById(cmb_clasificacion).disabled = true;	
						//Poner el combo como vacio
						document.getElementById(cmb_clasificacion).value = "";	
					}
					else {
						//Regresar False si se presiona el botón cancelar o se asigna un valor equivocado
						ckb_clasificacion.checked = false;
					}
				}
			}
			else{
				alert("Alcanzaste el Máximo de 40 Caracteres Permitidos");
				band=0;
				//Regresar False si se presiona el botón cancelar o se asigna un valor equivocado
				ckb_clasificacion.checked = false;
			}
		}
		else{
			//Regresar False si se presiona el botón cancelar o se asigna un valor equivocado
			ckb_clasificacion.checked = false;
		}
	}
	//Si el checkbox para nueva clasificacion se deselecciona, borrar el dato escrito en la caja de texto y reactivar el combo de clasificacion
	else{
		document.getElementById(txt_clasificacion).value = "";
		//Verificar que el combo este definido para poder Habilitarlo
		if (document.getElementById(cmb_clasificacion)!=null){
			//Habilitar el ComboBox y el deseleccionar el CheckBox para que el usuario pueda agregar nueva información
			document.getElementById(cmb_clasificacion).disabled = false;
			//Darle un valor vacio por default
			document.getElementById(cmb_clasificacion).value = combo;
		}
	}
}

//Esta funcion solicita al usuario la nueva Norma y desabilita el combo de norma
function agregarNuevaCarpetaMod(ckb_carpeta, txt_carpeta, cmb_carpeta){
	//Variable que nos permite controlar si hubo caracteres no aceptados para la generacion de un archivo
	var band=0;
	//Creamos variable para guardar el valor original del combo
	var combo=document.getElementById("hdn_comboCarpeta").value;
	if(combo==""){
		combo="";
	}
	//Si el checkbox para la nueva clasificacion esta seleccionado, pedir el nombre de dicha norma
	if(ckb_carpeta.checked){
		var carpeta = prompt("¿Nombre de Nueva Carpeta?","Nombre de Carpeta...");	
		if(carpeta!=null && carpeta!="Nombre de Carpeta..." && carpeta!=""){
			if(carpeta.length<=30){
				for(i=0;i<carpeta.length;i++){
					//Igualamos el valor de seccion a car para su facil manejo
					car = carpeta.charAt(i);
					//Verificamos que se encuentre en la ultima posicion para ver que no termine en punto
					if(i==carpeta.length-1){
						if(car=='.'){
							band=1;
						}
					}	
					if(car=='/'||car==':'||car=='*'||car=='?'||car=='"'||car=='<'||car=='>'||car=='|'){
						band=2;
					}
				}//Cierre for(i=0;i<seccion.length;i++)
				if(band==1){
					alert("Nombre No Valido. \nEl Nombre de Archivo No Puede Terminar En Punto.\nEjemplo Valido 1.1.1 ");
					carpeta.value="";
					carpeta.value=combo;
				}
				if(band==2){
					alert("Nombre No Valido. \nNo Son Aceptados Caracteres / : * ? < > | y Comillas.\nEjemplo Valido 1.1.1 ");
					carpeta.value="";
					carpeta.value=combo;
				}
				if(band==0){
					//Asignar el valor obtenido a la caja de texto que lo mostrara
					document.getElementById(txt_carpeta).value = carpeta.toUpperCase();
					//Verificar que el combo este definido para poder deshabilitarlo
					if(document.getElementById(cmb_carpeta)!=combo){
						//Deshabilitar el ComboBox para que el usuario no lo pueda modificar
						document.getElementById(cmb_carpeta).disabled = true;	
						//Poner el combo como vacio
						document.getElementById(cmb_carpeta).value = "";	
					}
					else {
						//Regresar False si se presiona el botón cancelar o se asigna un valor equivocado
						ckb_carpeta.checked = false;
					}
				}
			}
			else{
				alert("Alcanzaste el Máximo de 40 Caracteres Permitidos");
				band=0;
				//Regresar False si se presiona el botón cancelar o se asigna un valor equivocado
	 			ckb_carpeta.checked = false;
			}
		}
		else{
			//Regresar False si se presiona el botón cancelar o se asigna un valor equivocado
			ckb_carpeta.checked = false;
		}
	}
	//Si el checkbox para nueva norma se deselecciona, borrar el dato escrito en la caja de texto y reactivar el combo de clasificacion
	else{
		document.getElementById(txt_carpeta).value = "";
		//Verificar que el combo este definido para poder Habilitarlo
		if (document.getElementById(cmb_carpeta)!=null){
			//Habilitar el ComboBox y el deseleccionar el CheckBox para que el usuario pueda agregar nueva información
			document.getElementById(cmb_carpeta).disabled = false;
			//Darle un valor vacio por default
			document.getElementById(cmb_carpeta).value = combo;
		}
	}
}

//Función que permite habilitar los combos despues de haber seleccionado el boton restablecer
function habilitarCombosDoc(){
	//Habilitamos el combo clasificacion
	document.getElementById("cmb_clasificacion").disabled=false;
	//Habiitamos el combo carpeta
	document.getElementById("cmb_carpeta").disabled=false;
}

/*Esta función se encarga de validar el formulario de Registro de Documentos*/
function valFormModDocumentos(frm_modificarDocumento){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	var band = 1;
	
	//Verificar que el campo de txt_idDocumento no este vacío
	if(frm_modificarDocumento.txt_idDocumento.value==""){
		alert("Introducir el Id. del Documento");
		band = 0;
	}
	
	//Verificar que el campo de txt_nomDoc no este vacío
	if(frm_modificarDocumento.txt_nomDoc.value==""&&band==1){
		alert("Introducir el Nombre del Documento");
		band = 0;
	}
		
	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}

/*Esta función valida que se seleccione un archivo  en el formulario Resultados encontrados*/
function valFormArchivo(frm_modificarDocumento){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_modificarDocumento.rdb_id.length==undefined && !frm_modificarDocumento.rdb_id.checked){
		alert("Seleccionar Registro a Modificar");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_modificarDocumento.rdb_id.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_modificarDocumento.rdb_id.length;i++){
			if(frm_modificarDocumento.rdb_id[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar Registro a Modificar");			
	}
	
	if(res==1)
		return true;
	else
		return false;
}


/***************************************************************************************************************************************************************/
/*****************************************************FORMULARIO REGISTRAR RECORDATORIOS************************************************************************/
/***************************************************************************************************************************************************************/

/*Esta función valida que se hayan completado todos los registros en el formulario de registro procedimientos y normas*/
function valFormRegRec(frm_registrarRecordatorio){
	
	var combo = document.getElementById("cmb_tipoAler").value;
	
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	var band = 1;
	
	//Verificar que el combo de cmb_manu haya sido seleccionado
	if(frm_registrarRecordatorio.txa_descripcion.value==""){
		alert("Introducir Descripción");
		band = 0;
	}
	
	//Verificar que el combo de cmb_clausula se haya seleccionado
	if(frm_registrarRecordatorio.cmb_tipoAler.value=="" && band==1){
		alert("Seleccionar Tipo Alerta");
		band = 0;
	}
	
	if(combo=='EXTERNA'){
		//Verificar que el campo de cmb_procedimiento haya sido seleccionado
		if(frm_registrarRecordatorio.txt_ubicacion.value=="" && band==1){
			alert("Introducir Departamento");
			band = 0;
		}
	}
	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}

//Funcion que permite mostrar y ocultar los campos
function activarCamposRegRec(){
	var combo = document.getElementById("cmb_tipoAler").value;
	if(combo=='INTERNA'){
		document.getElementById("txt_ubicacion").style.visibility="hidden";
		document.getElementById("div_agrDep").style.visibility="hidden";
		document.getElementById("div_agrArc").style.visibility="hidden";
		document.getElementById("txt_archivos").style.visibility="hidden";
		document.getElementById("txt_archivos").value="";
		document.getElementById("div_agrArc").value="";
	}
	if(combo=='EXTERNA'){
		document.getElementById("txt_ubicacion").style.visibility="visible";
		document.getElementById("div_agrDep").style.visibility="visible";
		document.getElementById("div_agrArc").style.visibility="visible";
		document.getElementById("txt_archivos").style.visibility="visible";
	}
}

/*Esta función valida que se haya seleccionado un departamento en el pop-up verArchivo.php*/
function valArchivos(frm_verArchivo){	
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var res = 1;
	//Variable para saber si al menos un equipo fue seleccionado
	var status = 0;
	//Variable para controlar la cantidad de registros
	var ctrl= 1;
	//Variable para almacenar la cantidad de registros
	var cantidad = document.getElementById("hdn_cant").value-1;
		
	while(ctrl<=cantidad){		
		//Crear el id del CheckBox que se quiere verificar
		idCheckBox="ckb_arch"+ctrl.toString();
		
		//Verificar que la cantidad y la aplicación del Checkbox seleccionado no esten vacias
		if(document.getElementById(idCheckBox).checked){
			status = 1;
		}
		ctrl++;
	}//Fin del While	
	
	
	//Verificar que al menos un equipo haya sido seleccionado, si la variable status vale 1, quiere decir que al menos un departamento fue seleccionado
	if(status==0){
		alert("Seleccionar al Menos un Archivo");
		res = 0;
	}
	if(res==1)
		return true;
	else
		return false;		
}

/*Esta función valida que se seleccione un archivo  en el formulario Resultados encontrados*/
function valFormRecordatorio(frm_modificarRec){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
	//VAriable donde se almacena el valor del boron seleccionado
	var botonSel=document.getElementById("hdn_botonSel").value;
	
	if(botonSel=="modificar"||botonSel =="eliminar"){
		//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
		if(frm_modificarRec.rdb_id.length==undefined && !frm_modificarRec.rdb_id.checked){
			alert("Seleccionar Registro a Eliminar/Modificar");
			res = 0;
		}
		//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
		if(frm_modificarRec.rdb_id.length>=2){
			//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
			res = 0; 
			//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
			for(i=0;i<frm_modificarRec.rdb_id.length;i++){
				if(frm_modificarRec.rdb_id[i].checked)
					res = 1;
			}
			if(res==0)
				alert("Seleccionar Registro a Eliminar/Modificar");			
		}
	}
	if(botonSel=="eliminar"){
		if(res==1){
			if(!confirm("¿Estas Seguro que Quieres Eliminar El Registro?\nToda la información Relacionada con Dicho Registro Será Eliminada")){
				res = 0;
			}
		}
	}
	
	if(res==1)
		return true;
	else
		return false;
}

//Función que permite seleccionar Todos los checkbox de el formulario VerAchivo
function seleccionarTodoArch(checkbox){
	for(var i=0;i<document.frm_verArchivo.elements.length;i++){
		//Variable
		elemento=document.frm_verArchivo.elements[i];
		if (elemento.type=="checkbox")
			elemento.checked=checkbox.checked;
	}
}

function valFormRecordatorioAlerta(frm_modificarRec){	
	var res=1; 
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_modificarRec.rdb_id.length==undefined && !frm_modificarRec.rdb_id.checked){
		alert("Seleccionar Registro a Eliminar");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_modificarRec.rdb_id.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_modificarRec.rdb_id.length;i++){
			if(frm_modificarRec.rdb_id[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar Registro a Eliminar");			
	}
	if(res==1){
		if(!confirm("¿Estas Seguro que Quieres Eliminar El Registro?\nToda la información Relacionada con Dicho Registro Será Eliminada")){
			res = 0;
		}
	}

	if(res==1)
		return true;
	else
		return false;
}

/*****************************************************************************************************************************************************************/
/*********************************************************************DOCK****************************************************************************************/
/*****************************************************************************************************************************************************************/

//Funcion que permite seleccionar la pagina que ah de mostrarse  dependiendo del valor seleccionado
function selConsulta(){
	var combo = document.getElementById("cmb_consulta").value;
	if(combo=='ALMACEN'){
		location.href = 'frm_consultarEquipoSeguridad.php';	
	}
	if(combo=='RECURSOS'){
		location.href = 'frm_consultarEmpleado.php';	
	}
}

function valFormConsultaES(frm_consultarEquipo){	
	var res=1; 
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_consultarEquipo.txt_nombre.value==""){
		alert("Seleccionar Empleado Para Consultar Equipo de Seguridad");
		res = 0;
	}

	if(res==1)
		return true;
	else
		return false;
}




/***************************************************************************************************************************************************************/
/*******************************************************************SECCIÓN PERMISOS*****************************************************************************/
/***************************************************************************************************************************************************************/


/******************************************************************* PERMISOS PELIGROSOS *************************************************************************/

//Funcion para Validar que se ingresen todos los datos dnetroi dle formulario donde se genera el permiso para trabajos peligrosos
function valFormPermisoTrabPeligroso(frm_permisoTrabPeligroso){
	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;

	//Se verifica que  la aplicación haya sido ingresada
	if (frm_permisoTrabPeligroso.txt_nomSolicitante.value==""){
		alert ("Introducir el Nombre del Solicitante");
		band=0;
	}
	//Se verifica que el sistema haya sido ingresado
	if (frm_permisoTrabPeligroso.txt_nomSupervisor.value==""&&band==1){
		alert ("Introducir el Nombre del Supervisor");
		band=0;
	}
	//Se verifica que la actividad hayasido ingresada
	if (frm_permisoTrabPeligroso.txt_nomResponsable.value==""&&band==1){
		alert ("Introducir Nombre del Responsable");
		band=0;
	}
	//Se verifica que  la aplicación haya sido ingresada
	if (frm_permisoTrabPeligroso.txt_nomContratista.value==""&&band==1){
		alert ("Introducir el Nombre del Contratista");
		band=0;
	}
	//Se verifica que el sistema haya sido ingresado
	if (frm_permisoTrabPeligroso.txt_encargadoTrab.value==""&&band==1){
		alert ("Introducir el Nombre del Encargado del Trabajo");
		band=0;
	}
	//Se verifica que la actividad hayasido ingresada
	if (frm_permisoTrabPeligroso.txt_operador.value==""&&band==1){
		alert ("Introducir Nombre del Operador");
		band=0;
	}
	//Se verifica que  la aplicación haya sido ingresada
	if (frm_permisoTrabPeligroso.txt_funResponsable.value==""&&band==1){
		alert ("Introducir el Nombre del Funcionario Responsable");
		band=0;
	}
	//Se verifica que el sistema haya sido ingresado
	if (frm_permisoTrabPeligroso.txt_supervisorObra.value==""&&band==1){
		alert ("Introducir el Nombre del Supervisor de la Obra");
		band=0;
	}
	//Se verifica que la actividad hayasido ingresada
	if (frm_permisoTrabPeligroso.txt_supervisor.value==""&&band==1){
		alert ("Introducir Nombre del Supervisor");
		band=0;
	}
	//Se verifica que  la aplicación haya sido ingresada
	if (frm_permisoTrabPeligroso.txt_aceptacion.value==""&&band==1){
		alert ("Introducir el Nombre de Quien Aceptara el Trabajo");
		band=0;
	}
	//Se verifica que el sistema haya sido ingresado
	if (frm_permisoTrabPeligroso.txa_desTrabajo.value==""&&band==1){
		alert ("Introducir la Descripción del Trabajo");
		band=0;
	}
	//Se verifica que el tipo de Trabajo haya sido seleccionado
	if(frm_permisoTrabPeligroso.rdb_tipoTrabajo.length>=2&&band==1){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_permisoTrabPeligroso.rdb_tipoTrabajo.length;i++){
			if(frm_permisoTrabPeligroso.rdb_tipoTrabajo[i].checked)
				res = 1;
		}
		if(res==0){
			alert("Seleccionar Tipo de Trabajo a Desarrollar");			
			band=0;
		}
	}
	//Se verifica que la actividad hayasido ingresada
	if (frm_permisoTrabPeligroso.txa_trabRealizar.value==""&&band==1){
		alert ("Introducir la Descripción del Trabajo a Realizar");
		band=0;
	}
	//Se verifica que  la aplicación haya sido ingresada
	if (frm_permisoTrabPeligroso.txa_trabEspecifico.value==""&&band==1){
		alert ("Introducir la Descripción del Trabajo Especifico a Realizar");
		band=0;
	}
	//Se verifica que el sistema haya sido ingresado
	if (frm_permisoTrabPeligroso.txt_horaIni.value==""&&band==1){
		alert ("Introducir la Hora de Inicio del Trabajo");
		band=0;
	}
	//Se verifica que la actividad hayasido ingresada
	if (frm_permisoTrabPeligroso.cmb_meridiano1.value==""&&band==1){
		alert ("Seleccionar el Meridiano de la Hora de Inicio");
		band=0;
	}
	//Se verifica que el sistema haya sido ingresado
	if (frm_permisoTrabPeligroso.txt_horaFin.value==""&&band==1){
		alert ("Ingresar la Hora de Fin para el Permiso");
		band=0;
	}
	
	//Se verifica que la actividad hayasido ingresada
	if (frm_permisoTrabPeligroso.cmb_meridiano2.value==""&&band==1){
		alert ("Seleccionar el Meridiano de la Hora de Fin");
		band=0;
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}
/******************************************************************* PERMISOS FLAMA ABIERTA *************************************************************************/

//Funcion que permite validar el formulario donde se genera el 
function valFormPermisoTrabIncendio(frm_permisoTrabFlama){
	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;

		//Se verifica que  el foilo del permiso haya sido ingresado
		if (frm_permisoTrabFlama.txt_folioPermiso.value==""){
			alert ("Introducir el Folio para el Permiso de Flama Abierta");
			band=0;
		}
		//Se verifica que el nomnre de quien ejecutara el trabajo haya sido ingresado
		if (frm_permisoTrabFlama.txt_nomEmpContratista.value==""&&band==1){
			alert ("Introducir el Nombre de Quien Desarrollara el Trabajo");
			band=0;
		}
		
		//Se verifica que el nomnre de quien ejecutara el trabajo haya sido ingresado
		if (frm_permisoTrabFlama.txt_encargadoTrab.value==""&&band==1){
			alert ("Introducir el Nombre del Encargado del Trabajo");
			band=0;
		}
		//Se verifica que el nomnre de quien ejecutara el trabajo haya sido ingresado
		if (frm_permisoTrabFlama.txt_lugarTrabajo.value==""&&band==1){
			alert ("Introducir el Lugar donde se Realizara el Trabajo");
			band=0;
		}
		//Se verifica que la hora de inicio haya sido ingresada
		if (frm_permisoTrabFlama.txt_horaIni.value==""&&band==1){
			alert ("Introducir la Hora de Expiración del Permiso");
			band=0;
		}
		//Se verifica que  el meridiano de la hora de inicio haya sido seleccionada
		if (frm_permisoTrabFlama.cmb_meridiano1.value==""&&band==1){
			alert ("Seleccionar el Meridiano de la Hora de Expiración");
			band=0;
		}
		//Se verifica que la descripcion del traabajo especifico haya sido ingresada
		if (frm_permisoTrabFlama.txa_trabEspecifico.value==""&&band==1){
			alert ("Introducir la Descripción del Trabajo Especifico");
			band=0;
		}
		//Se verifica que  el Nombre del Supervisor haya sido ingresada
		if (frm_permisoTrabFlama.txt_supObra.value==""&&band==1){
			alert ("Introducir el Nombre del Supervisor de la Obra");
			band=0;
		}
		//Se verifica que  el Funcionario Responsable haya sido ingresada
		if (frm_permisoTrabFlama.txt_funResponsable.value==""&&band==1){
			alert ("Introducir el Nombre del Funcionario Responsable");
			band=0;
		}	
		//Se verifica que  la Evidencia 2 haya sido ingresada
		if (frm_permisoTrabFlama.txt_foto1.value==""&&band==1){
			alert ("Introducir la Foto de la Primera Evidencia");
			band=0;
		}
		//Se verifica que  la Evidencia 2 haya sido ingresada
		if (frm_permisoTrabFlama.txt_foto2.value==""&&band==1){
			alert ("Introducir la Foto de la Segunda Evidencia");
			band=0;
		}
			
		//Verificar que la imagen proporcionada tenga el formato y tamaño soportado por el Sistema
	if(band==1){
		if(document.getElementById("hdn_imgValida_1").value=="no" || document.getElementById("hdn_imgValida_2").value=="no"){
			alert("Verificar el Formato y Tamaño de las Imágenes Proporcionadas");
			band = 0;
		}
	}
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

/******************************************************************* PERMISOS ALTURAS *************************************************************************/

//Funcion que permite validar el formulario donde se genera el 
function valFormPermisoTrabAlturas(frm_permisoTrabAlturas){
	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;
	var hidden =document.getElementById("hdn_boton").value;

		//Se verifica que  el mbre del trabajoador haya sido ingresado
		if (frm_permisoTrabAlturas.txt_nomTrabajador.value==""){
			alert ("Introducir el Nombre del Trabajador");
			band=0;
		}
		//Se verifica que el nomnre de quien autoriza haya sido ingresado
		if (frm_permisoTrabAlturas.txt_nomAutoriza.value==""&&band==1){
			alert ("Introducir el Nombre de Quien Autorizó el Permiso");
			band=0;
		}
		//Se verifica que el nombre del lider operativo haya sido ingresada
		if (frm_permisoTrabAlturas.txt_liderOper.value==""&&band==1){
			alert ("Introducir el Nombre del Líder Operativo");
			band=0;
		}
		//Se verifica que  la descripcion del tyrabajo a realizar haya sido ingresada
		if (frm_permisoTrabAlturas.txa_trabRealizar.value==""&&band==1){
			alert ("Introducir la Descripción del Trabajo a Realizar");
			band=0;
		}
		//Se verifica que el lugar haya sido ingresada
		if (frm_permisoTrabAlturas.txt_lugar.value==""&&band==1){
			alert ("Introducir el Lugar donde se Desarrollara el Trabajo");
			band=0;
		}
		//Se verifica que  la descripcion del trabajo haya sido ingresada
		if (frm_permisoTrabAlturas.txa_desTrabajo.value==""&&band==1){
			alert ("Introducir la Descripción del Trabajo");
			band=0;
		}
		//Se verifica que  los riesgos de trabajo haya sido ingresada
		if (frm_permisoTrabAlturas.txa_riesgosTrab.value==""&&band==1){
			alert ("Introducir los Riesgos de Trabajo");
			band=0;
		}	
		//Se verifica que  los riesgos de trabajo haya sido ingresada
		if (frm_permisoTrabAlturas.hdn_boton.value=="no"&&band==1){
			band=0;
		}	
		//Se verifica que  los riesgos de trabajo haya sido ingresada
		if (frm_permisoTrabAlturas.hdn_boton.value=="no"&&band==1){
			band=0;
		}


	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}



//Esta funcion valida que una imagen sea valida, tomando en cuenta el tamaño de 1 Kb hasta 10Mb
function validarImagen(campo,bandera) { 
	//Verificar que el campo tenga foto agregada, de lo contrario no hacer la validacion
	if (campo.value!=""){
		//Creamos un elemento DIV
		div = document.createElement("DIV-IMG"); 
		//Le damos la propiedad hidden al DIV
		div.style.visibility = "hidden"; 
		//Le asignamos la propiedad scroll al Div para que no se ajuste al tamaño del formulario
		div.overflow="scroll";
		//Le asignamos el ancho y largo al div creado
		div.width="10";
		div.height="10";
				
		//Creamos un elemento IMG
		img = document.createElement("IMG"); 
		//Asignamos a nuestro elemento recien creado la imagen que queremos sea evaluada, esta viene en el valor del campo que se pasa como parametro a la funcion
		img.src = campo.value; 
		//Asignamos un ID al elemento IMG
		img.id = "fotoFinal"; 
		//Ocultamos IMG a fin de que no se muestre en la pagina o formulario, Si el archivo subido no es una imagen, el tamaño asignadop sera de 0, lo cual lo hace una imagen invalida
		img.style.visibility = "hidden"; 
		
		//Le asignamos al div que creamos al inicio, la imagen que acabamos de cargar a fin de poder evaluarla
		div.appendChild(img);
		//Definimos la variable que indicara el tamaño de la Imágen
		var tam=0;
		//Cargamos en un setTimeout la imagen medida en Kb para que a los 500 milisegundos revise el tamaño de la misma, es en un setTimeout para que permite cargar y revisar
		setTimeout("tam=Math.round(div.lastChild.fileSize/1024);",700);
		//Despues de haber obtenido el tamaño de la imagen, la quitamos del div que creamos, para esto damos un espacio de 100 milisegundos
		setTimeout("div.removeChild(div.lastChild);",800);
		//Una vez que ya obtuvimos el tamaño de la imagen comparamos que sea mayor a 0 y menor a 10240000, si se cumple, 
		//al elemento bandera que le pasamos a la funcion le asigna el valor de SI, este elemento bandera es un elemento tipo hidden en el formulario a fin de poder hacer validacion
		//en caso que el tamaño no se cumpla, muestra una alerta de Imágen no válida y al elemento bandera le asigna el valor NO
		setTimeout("if (tam>0&&tam<10240000){ document.getElementById('"+bandera+"').value='si'; return true; } else { alert('Introducir una Imágen Válida'); document.getElementById('"+bandera+"').value='no'; return false; }",900);
	}
	else
		document.getElementById(bandera).value="si";
}



//Funcion para Validar que se ingresen todos los datos dnetroi dle formulario donde se genera el permiso para trabajos peligrosos
function valFormVerCondicionesSeg(frm_verCondSeg){	
	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var validacion = 1;
	
	//Obtener la cantidad de Condiciones de Seguridad que serán validadas
	var cantCondiciones = document.getElementById("hdn_totalCondiciones").value;

	//Recorrer cada una de las Condiciones de Seguridad registradas en el permiso
	for(var indCond=1;indCond<=cantCondiciones;indCond++){
		var valRespuesta = 0;
			//Recorrer cada una de las 2 Respuestas (Si/No)
			for(indResp=0;indResp<2;indResp++){
				if(frm_verCondSeg["rdb_"+indCond][indResp].checked){
					//Indicar que una respuesta de la Condición actual fue seleccionada
					valRespuesta = 1;
				}
			}				
		//Verificar si una respuesta fue seleccionada
		if(valRespuesta==0){
			alert("Seleccionar una Respuesta para la Condición de Seguridad No. "+indCond);			
			validacion = 0;
			break;
		}
		
	}//Cierre for(i=0;i<cantCondiciones;i++)
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (validacion==1)
		return true;
	else
		return false;
}




//Funcion que permite validar el formulario donde se genera el 
function valFormRegNvasCondiciones(frm_agregarRegistro){
	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;

		//Se verifica que  el mbre del trabajoador haya sido ingresado
		if (frm_agregarRegistro.txt_actividades.value==""&&band==1){
			alert ("Introducir la Nueva Condición de Seguridad");
			band=0;
		}

	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

function desabilitarVentana(){
//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;
	
	if(band==1){
		document.getElementById("btn_regCondicionesSeg").disabled = true;
	}	
}


/*****************************************************FORMULARIO ALERTAS************************************************************************/

//Funcion que permite complementar el Permiso de Altutras con las condiciones de seguridad a seguir dnetro de una Ventana Emrgente
function alertasPlanContingencia(){
	//Al llamar a la ventana le enviamos el valor de la variable por el GET
		window.open('verComplementoPlanContingencia.php','_blank','top=50, left=50, width=800, height=450, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
}


//Funcion que permite complementar el Permiso de Altutras con las condiciones de seguridad a seguir dnetro de una Ventana Emrgente
function permisoAlturas(){
	//Al llamar a la ventana le enviamos el valor de la variable por el GET
		window.open('verComplementoPermisoAlturas.php','_blank','top=50, left=50, width=800, height=500, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
}


//Funcion que permite complementar el Permiso de Altutras con las condiciones de seguridad a seguir dnetro de una Ventana Emrgente
function nuevasCondiciones(){
	var clave =document.getElementById('hdn_clave').value;
	//Al llamar a la ventana le enviamos el valor de la variable por el GET
	vntRegNvaCond =	window.open('verRegNuevasCondiciones.php?clave='+clave+'','_blank','top=50, left=50, width=800, height=200, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
}



/*****************************************************FIN FORMULARIO ALERTAS************************************************************************/

/*****************************************************CONSULTAR PERMISOS GENERADOS******************************************************************/

//Funcion para Validar que en la seccion de consultar los permisos generados
function valFormConsultarPermisos(frm_consultarPermisos){
	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;
	
	
	//Con este pequeño bloque de codigo se valida que la fecha de programacion no sea mayor a la fecha de registro
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_consultarPermisos.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_consultarPermisos.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_consultarPermisos.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_consultarPermisos.txt_fechaFin.value.substr(0,2);
	var finMes=frm_consultarPermisos.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_consultarPermisos.txt_fechaFin.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);
	//Fin donde se valida las fechas de registro y programacion
	
	//Con esta linea se valida que la fecha de programacion del simulacro no sea mayor a la fecha en la cual se esta realizando el registro
		if(fechaIni>fechaFin){
		band=0;
		alert ("La Fecha de Inicio NO Puede ser Mayor a la Fecha de Fin para la Consulta de Permisos");
		}	
		
	//Se verifica que el responsable haya sido ingresada
		if (frm_consultarPermisos.cmb_tipoPermiso.value==""&&band==1){
			alert ("Seleccionar el Tipo de Permiso a Consultar");
			band=0;
		}	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

/***************************************************FIN DE VALIDACIÓN CONSULTAR PERMISOS GENERADOS*****************************************************/


/***************************************************************************************************************************************************************/
/*****************************************************FORMULARIO GENERAR PLAN CONTINGENCIA**********************************************************************/
/***************************************************************************************************************************************************************/

//Funcion para Validar que se ingresen todos los datos dentro dle formulario donde se generan los planes de contingencia
function valFormPlanesContingenciaGenerados(frm_planesContingencia){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band = 1;
	var opcPC = document.getElementById("rdb_opcPlanContingencia");
	//Con este pequeño bloque de codigo se valida que la fecha de programacion no sea mayor a la fecha de registro
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_planesContingencia.txt_fechaReg.value.substr(0,2);
	var iniMes=frm_planesContingencia.txt_fechaReg.value.substr(3,2);
	var iniAnio=frm_planesContingencia.txt_fechaReg.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_planesContingencia.txt_fechaProg.value.substr(0,2);
	var finMes=frm_planesContingencia.txt_fechaProg.value.substr(3,2);
	var finAnio=frm_planesContingencia.txt_fechaProg.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaReg=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaProg=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaReg=new Date(fechaReg);
	fechaProg=new Date(fechaProg);
	//Fin donde se valida las fechas de registro y programacion
	
	if(frm_planesContingencia.hdn_botonSeleccionado.value=="agregar"){
		//Se verifica que el responsable haya sido ingresada
		if (frm_planesContingencia.txt_resSim.value==""){
			alert ("Introducir Nombre del Responsable del Simulacro");
			band=0;
		}	
		//Con esta linea se valida que la fecha de programacion del simulacro no sea mayor a la fecha en la cual se esta realizando el registro
		if(fechaReg>fechaProg){
		band=0;
		alert ("La Fecha de Programación NO Puede ser Mayor a la Fecha en la que se Registra el Plan de Contingencia");
		}		
		//Se verifica que  el area haya sido ingresada
		if (frm_planesContingencia.txt_area.value==""&&band==1){
			alert ("Introducir el Área donde se Ejecutara el Simulacro");
			band=0;
		}
		//Se verifica que el lugar haya sido ingresado
		if (frm_planesContingencia.txt_lugar.value==""&&band==1){
			alert ("Introducir el Lugar donde se Realizara el Simulacro");
			band=0;
		}
		//Se verifica que  el nombre del simulacro haya sido ingresada
		if (frm_planesContingencia.txt_nomSimulacro.value==""&&band==1){
			alert ("Introducir el Nombre del Simulacro");
			band=0;
		}
		//Se verifica que el tipo de simulacro haya sido ingresado
		if (frm_planesContingencia.txt_tipoSimulacro.value==""&&band==1){
			alert ("Introducir el Tipo del Simulacro");
			band=0;
		}
		
		if (frm_planesContingencia.txt_paso.value==""&&band==1){
			alert ("Introducir los Pasos del Plan de Contingencia");
			band=0;
		}	
		//Se verifica que el lugar haya sido ingresado
		if (frm_planesContingencia.txt_accion.value==""&&band==1){
			alert ("Introducir la Acción, correspondiente al Paso del Plan Registrado");
			band=0;
		}
		//Se verifica que el responsable haya sido ingresada
		if (frm_planesContingencia.txt_simulacro.value==""&&band==1){
			alert ("Introducir Observaciones u Sugerencias hacia el Paso del Plan Registrado");
			band=0;
		}
		//Se verifica que  el responsable de la accion del simulacro por cada paso registrado
		if (frm_planesContingencia.txt_resAccion.value==""&&band==1){
			alert ("Introducir el Responsable de Realizar la Acción Registrada");
			band=0;
		}
	}
		
	
	if(frm_planesContingencia.hdn_botonSeleccionado.value=="finalizarArchivo"){

		//Se verifica que el responsable haya sido ingresada
		if (frm_planesContingencia.txt_resSim.value==""){
			alert ("Introducir Nombre del Responsable del Simulacro");
			band=0;
		}	
		//Con esta linea se valida que la fecha de programacion del simulacro no sea mayor a la fecha en la cual se esta realizando el registro
		if(fechaReg>fechaProg){
		band=0;
		alert ("La Fecha de Programación NO Puede ser Mayor a la Fecha en la que se Registra el Plan de Contingencia");
		}		
		//Se verifica que  el area haya sido ingresada
		if (frm_planesContingencia.txt_area.value==""&&band==1){
			alert ("Introducir el Área donde se Ejecutara el Simulacro");
			band=0;
		}
		//Se verifica que el lugar haya sido ingresado
		if (frm_planesContingencia.txt_lugar.value==""&&band==1){
			alert ("Introducir el Lugar donde se Realizara el Simulacro");
			band=0;
		}
		//Se verifica que  el nombre del simulacro haya sido ingresada
		if (frm_planesContingencia.txt_nomSimulacro.value==""&&band==1){
			alert ("Introducir el Nombre del Simulacro");
			band=0;
		}
		//Se verifica que el tipo de simulacro haya sido ingresado
		if (frm_planesContingencia.txt_tipoSimulacro.value==""&&band==1){
			alert ("Introducir el Tipo del Simulacro");
			band=0;
		}
		//Se verifica que el tipo de simulacro haya sido ingresado
		if (frm_planesContingencia.txt_archivos.value==""&&band==1){
			alert ("Seleccionar el Archivo o Documento a Vincular para el Plan de Contingencia");
			band=0;
		}
	}
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}



//Funcion para validar la Hora dentro de los permisos de Flama Abierta o Pemrisos Peligrosos validarCantHorasPermisoPeligroso
function validarCantHoras(campo){
	//Obtener el valor del campo y etirar los ':' en caso de que los tenga
	var numero = campo.value.replace(/:/g,'');
	//Obtener la longitud del campo, la cantidad de digitos es 000:00
	var cantCars = numero.length;
	//alert("Cant. Caracteres: "+cantCars+" de: "+campo.value);
	//Validar solo datos que contengan 6 caracteres
	if(cantCars>=1&&cantCars<=5){				
		if(isNaN(numero)){
			alert("El Numero "+campo.value+" no es Valido");
			//Ponemos la caja de Texto como vacia
			document.getElementById("txt_tiempoTotal").value = "";
		}
		else{
			//Extraer y validar los minutos
			var tam = numero.length;
			//Tomamos los dos ultimos numeros insertados; es decir los minutos
			var minutos = numero.substring((tam-2));
			//Verificamos que los minutos sean menores a 59
			if(parseInt(minutos)>59)
				minutos = 0;
			//Extraer las Horas y validarlas
			var horas = numero.substring(0,(tam-2))
			//Tomamos los numeros desde 0 hasta los digitos de minutos; es decir las horas
			if(parseInt(horas)==0)
				horas = 0;		
			//Verificamos si minutos viene en 0 si es asi; poner el formato para las hoas
			if(minutos==0){
				if(cantCars<=2)
					document.getElementById("txt_tiempoTotal").value = "00:00";
				else
					document.getElementById("txt_tiempoTotal").value = horas.toString()+":00";
				
			}
			else{
				//Verificamos si horas estan en 0 
				if(horas==0){
					//Si horas =0 y minutos <9; esto para poner un cero mas y que conserve el formato 00:09 y no 00:9
					if(parseInt(minutos)<=9)
						document.getElementById("txt_tiempoTotal").value = "00:0"+minutos.toString();
					//Si horas =0 y minutos >9 entonces los minutos tomaran el formato 00:10		
					else
						document.getElementById("txt_tiempoTotal").value = "00:"+minutos.toString();
				//Si las horas y minutos mayores a 0 entonces toma formato 10:25
				}
				else
					document.getElementById("txt_tiempoTotal").value = horas.toString()+":"+minutos.toString();			
			}
		}			
	}
	else{
		//Enviaos mensaje
		alert("La Cantidad de Dígitos Excede el Tamaño Permitido");
		//Ponemos la caja de Texto como vacia
		document.getElementById("txt_tiempoTotal").value = "";
	}						
}


/*Esta funcion solicita la confirmación del usuario antes de salir de la pagina*/
function confirmarSalida(pagina){
	if(confirm("¿Estas Seguro que Quieres Salir?\nToda la información no Guardada se Perderá"))
		location.href = pagina;	
}



//Funcion para Validar que se seleccione un registro
function valFormResultadosPlanes(frm_resultadosPlanes){	
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_resultadosPlanes.rdb_plan.length==undefined && !frm_resultadosPlanes.rdb_plan.checked){
		alert("Seleccionar Registro a Complementar");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_resultadosPlanes.rdb_plan.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_resultadosPlanes.rdb_plan.length;i++){
			if(frm_resultadosPlanes.rdb_plan[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar Registro a Complementar");			
	}
	
	if(res==1)
		return true;
	else
		return false;
}


/*****************************************************MOSTRAR EL TIPO DE REGISTRO A REALIZAR************************************************************************/

//Funcion que permite mostrar o ocultar el DIV de acuerdo a la opcion seleccionada dentro del registro de los planes de contingencia
function activarOpcionPlanContingencia(opcPC){
	
	if(opcPC=='pasosPlan'){
		document.getElementById("tabla-pasosContingencia").style.visibility="visible";
		document.getElementById("tabla-archivoContingencia").style.visibility="hidden";
	}
		
	if (opcPC=='archivoPlan'){
		document.getElementById("tabla-archivoContingencia").style.visibility="visible";
		document.getElementById("tabla-pasosContingencia").style.visibility="hidden";
	}

	
}

//Funcion que permite mostrar o ocultar el DIV que contiene el boton de CANCELAR en cuanto sea seleeccionado la opcion del RADIO
function valorRadio(opc){

	if(opc=='pasosPlan'){
		document.getElementById("botones-TablaRes").style.visibility="hidden";
		document.getElementById("hdn_cancelar").style.visibility="hidden";
	}
		
	if (opc=='archivoPlan'){
		document.getElementById("botones-TablaRes").style.visibility="hidden";
	}
	
}


/*Esta función valida que se haya seleccionado un documento en el pop-up verDctoVinculadoPlanContingencia.php*/
function valArchivosPlanContingencia(frm_verArchivoPC){	
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var res = 1;

	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_verArchivoPC.rdb_opcDocumento.length==undefined && !frm_verArchivoPC.rdb_opcDocumento.checked){
		alert("Selecciona el Documento a Vincular para el Plan de Contingencia");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_verArchivoPC.rdb_opcDocumento.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_verArchivoPC.rdb_opcDocumento.length;i++){
			if(frm_verArchivoPC.rdb_opcDocumento[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar al Menos un Archivo");			
	}
	
	if(res==1)
		return true;
	else
		return false;		
}




/*****************************************************FIN DEL MOSTRAR EL TIPO DE REGISTRO A REALIZAR******************************************************************/




/*****************************************************FORMULARIO MODIFICAR PLAN CONTINGENCIA************************************************************************/

function valFormModPlaFechas(frm_consultarPlanesFecha){
//Si el valor se mantiene en 1, el rango de fechas es valido
	var band = 1;
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_consultarPlanesFecha.txt_fechaReg.value.substr(0,2);
	var iniMes=frm_consultarPlanesFecha.txt_fechaReg.value.substr(3,2);
	var iniAnio=frm_consultarPlanesFecha.txt_fechaReg.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_consultarPlanesFecha.txt_fechaProg.value.substr(0,2);
	var finMes=frm_consultarPlanesFecha.txt_fechaProg.value.substr(3,2);
	var finAnio=frm_consultarPlanesFecha.txt_fechaProg.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaReg=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaProg=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaReg=new Date(fechaReg);
	fechaProg=new Date(fechaProg);

	if(fechaReg>fechaProg){
		band=0;
		alert ("La Fecha de Inicio no Puede ser Mayor a la Fecha de Cierre");
	}
		
	if(band==1)
		return true;
	else
		return false;
}


//Funcion para Validar que en la seccion de consultar plan por ID sea seleccionada una clave correspondiente
function valFormConsultarIdPlan(frm_consultarIdPlan){
	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;
		
	//Se verifica que el responsable haya sido ingresada
		if (frm_consultarIdPlan.cmb_idPlan.value==""&&band==1){
			alert ("Seleccionar la Clave del Plan a Consultar");
			band=0;
		}
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}


//Funcion para Validar que se ingresen todos los datos Generales del Plan de Contingencia
function valFormDatosPlan(frm_modificarDatosPlan){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;
	
	//Con este pequeño bloque de codigo se valida que la fecha de programacion no sea mayor a la fecha de registro
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_modificarDatosPlan.txt_fechaReg.value.substr(0,2);
	var iniMes=frm_modificarDatosPlan.txt_fechaReg.value.substr(3,2);
	var iniAnio=frm_modificarDatosPlan.txt_fechaReg.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_modificarDatosPlan.txt_fechaProg.value.substr(0,2);
	var finMes=frm_modificarDatosPlan.txt_fechaProg.value.substr(3,2);
	var finAnio=frm_modificarDatosPlan.txt_fechaProg.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaReg=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaProg=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaReg=new Date(fechaReg);
	fechaProg=new Date(fechaProg);
	//Fin donde se valida las fechas de registro y programacion
	
	
	
	
	//Se verifica que el responsable haya sido ingresada
	if (frm_modificarDatosPlan.txt_resSim.value==""){
		alert ("Introducir Nombre del Responsable del Simuacro");
		band=0;
	}
	
	//Con esta linea se valida que la fecha de programacion del simulacro no sea mayor a la fecha en la cual se esta realizando el registro
		if(fechaReg>fechaProg){
		band=0;
		alert ("La Fecha de Programación NO Puede ser Mayor a la Fecha en la que se Registra el Plan de Contingencia");
		}		
	
	//Se verifica que  el area haya sido ingresada
	if (frm_modificarDatosPlan.txt_area.value==""&&band==1){
		alert ("Introducir el Área donde se Ejecutara el Simacro");
		band=0;
	}
	//Se verifica que el lugar haya sido ingresado
	if (frm_modificarDatosPlan.txt_lugar.value==""&&band==1){
		alert ("Introducir el Lugar donde se Realizara el Simulacro");
		band=0;
	}
	//Se verifica que  el nombre del simulacro haya sido ingresada
	if (frm_modificarDatosPlan.txt_nomSimulacro.value==""&&band==1){
		alert ("Introducir el Nombre del Simuacro");
		band=0;
	}
	//Se verifica que el tipo de simulacro haya sido ingresado
	if (frm_modificarDatosPlan.txt_tipoSimulacro.value==""&&band==1){
		alert ("Introducir el Tipo del Simulacro");
		band=0;
	}
	
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

//Funcion para Validar que se ingresen todos los datos de Detalle del Plan de Contingencia Modificado
function valFormPlanContingencia(frm_modificarPlanContingencia){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;
	
	//Verificar Cual Boton fue seleccionado (Agregar o Finalizar)
	if(frm_modificarPlanContingencia.hdn_botonSel.value=="agregar"){
		//Se verifica que el responsable haya sido ingresada
		if (frm_modificarPlanContingencia.txt_paso.value==""){
			alert ("Introducir los Pasos del Plan de Contingencia");
			band=0;
		}	
		//Se verifica que el lugar haya sido ingresado
		if (frm_modificarPlanContingencia.txt_accion.value==""&&band==1){
			alert ("Introducir la Acción, correspondiente al Paso del Plan Registrado");
			band=0;
		}
		//Se verifica que  el nombre del simulacro haya sido ingresada
		if (frm_modificarPlanContingencia.txt_resAccion.value==""&&band==1){
			alert ("Introducir el Responsable de Realizar la Acción Registrada");
			band=0;
		}
		//Se verifica que el responsable haya sido ingresada
		if (frm_modificarPlanContingencia.txt_simulacro.value==""&&band==1){
			alert ("Introducir Observaciones u Sugerencias hacia el Paso del Plan Registrado");
			band=0;
		}
	} //Fin 	if(frm_modificarPlanContingencia.hdn_botonSeleccionado.value=="agregar"){
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}


//Funcion para Validar que se ingresen todos los datos de Detalle del Plan de Contingencia Modificado
function valFormCargarArchivoPlanContingencia(frm_modificarArchivoPlanContingencia){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;
	
	//Verificar Cual Boton fue seleccionado (Agregar o Finalizar)
	if(frm_modificarArchivoPlanContingencia.hdn_botonSel.value=="finalizarArchivo"){
		//Se verifica que el responsable haya sido ingresada
		if (frm_modificarArchivoPlanContingencia.txt_archivos.value==""){
			alert ("Seleccionar el Archivo a Vincular para el Plan de Contingencia");
			band=0;
		}	
	} //Fin 	if(frm_modificarPlanContingencia.hdn_botonSeleccionado.value=="agregar"){
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}




//Funcion para Validar la ventana emergente donde se registran los tiempos ciando el plan de contingencia es ejecutado
function valFormTiemposPlanEjecutado(frm_regTiemposPlan){
	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;
		
	//Se verifica que el responsable haya sido ingresada
		if (frm_regTiemposPlan.txt_tiempoTotal.value==""){
			alert ("Introducirel Tiempo Total del Simulacro Ejecutado");
			band=0;
		}
		if (frm_regTiemposPlan.txt_tiempoTotal.value=="0:00"&&band==1){
			alert ("El Tiempo Total del Simulacro, NO Puede ser Igual a cero");
			band=0;
		}
		
		//Se verifica que  el area haya sido ingresada
		if (frm_regTiemposPlan.txa_comentarios.value==""&&band==1){
			alert ("Introducir los Comentarios");
			band=0;

		}
		//Se verifica que el lugar haya sido ingresado
		if (frm_regTiemposPlan.txa_observaciones.value==""&&band==1){
			alert ("Introducir las Observaciones al Simulacro Realizado");
			band=0;
		}
		if((frm_regTiemposPlan.txt_foto1.value==""&&frm_regTiemposPlan.txt_foto2.value==""&&frm_regTiemposPlan.txt_foto3.value==""&&
			frm_regTiemposPlan.txt_foto4.value==""&&frm_regTiemposPlan.txt_foto5.value=="")&&band==1){
			alert("Ingresar por lo Menos 1 Foto de Evidencia al Plan de Contingencia");
			band = 0;
		}	
		if(band==1){
		//Verificar que se haya seleccionado una imagen
		if ((frm_regTiemposPlan.txt_foto1.value!=""||frm_regTiemposPlan.txt_foto2.value!=""||frm_regTiemposPlan.txt_foto3.value!=""||
			frm_regTiemposPlan.txt_foto4.value!=""||frm_regTiemposPlan.txt_foto5.value!="")&&band==1){
			//Verificar que la imagen introducida sea valida, este valor lo obtiene de la funcion validarImagen()
			if (document.getElementById("hdn_imgValida_1").value=="no"&&band==1){
				alert ("Solo se pueden Guardar Fotografías, Introducir una Imágen ó Evidencia N° 1, Válida");
				band=0;
			}
			//Verificar que la imagen introducida sea valida, este valor lo obtiene de la funcion validarImagen()
			if (document.getElementById("hdn_imgValida_2").value=="no" &&band==1){
				alert ("Solo se pueden Guardar Fotografías, Introducir una Imágen ó Evidencia N° 2, Válida");
				band=0;
			}
			//Verificar que la imagen introducida sea valida, este valor lo obtiene de la funcion validarImagen()
			if (document.getElementById("hdn_imgValida_3").value=="no"&&band==1){
				alert ("Solo se pueden Guardar Fotografías, Introducir una Imágen ó Evidencia N° 3, Válida");
				band=0;
			}
			//Verificar que la imagen introducida sea valida, este valor lo obtiene de la funcion validarImagen()
			if (document.getElementById("hdn_imgValida_4").value=="no"&&band==1){
				alert ("Solo se pueden Guardar Fotografías, Introducir una Imágen ó Evidencia N° 4, Válida");
				band=0;
			}
			//Verificar que la imagen introducida sea valida, este valor lo obtiene de la funcion validarImagen()
			if (document.getElementById("hdn_imgValida_5").value=="no"&&band==1){
				alert ("Solo se pueden Guardar Fotografías, Introducir una Imágen ó Evidencia N° 5, Válida");
				band=0;
			}
		}
	}
		
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

//Funcion que permite cambiar el submit
function cambiarSubmitPlan(){
	//Recuperamos la variable hidden para saber que submit corresponde
	var boton = document.getElementById("hdn_btnCambiar").value;
	if(boton=="sbt_exportarRes"){
		//Enviar a la pagina donde se mustra el reporte
		document.frm_resultadosPlan.action = "guardar_reportePermisos.php";
	}
	else{
		//Enviar a la pagina donde se mustra la informcion del plan a modificar
		document.frm_resultadosPlan.action = "frm_modificarPlanContingencia2.php";
		document.frm_resultadosPlan.submit();
	}
}
/***************************************************************************************************************************************************************/
/*****************************************************FIN VALIDACION DE LA SECCION DE PLAN CONTINGENCIA********************************************************/
/***************************************************************************************************************************************************************/




/***************************************************************************************************************************************************************/
/*****************************************************VALIDACION DE LA SECCION TIEMPO VIDA UTIL EQUIPO SEGURIDAD************************************************/
/***************************************************************************************************************************************************************/
//Funcion para Evaluar el consultar Acta de incidentes accidentes
function valFormTiempoVidaUtilEquipoSeguridad(frm_tiempoVidaES){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;	
		
		//Se verifica que el sistema haya sido ingresado
		if (frm_tiempoVidaES.txt_nomMaterial.value==""){
			alert ("Ingresar el Nombre del Material de Seguridad");
			band=0;
		}
		if (frm_tiempoVidaES.txt_tiempoVida.value==""&&band==1){
			alert ("Ingresar el Tiempo de Vida");
			band=0;
		}
		if (frm_tiempoVidaES.txt_tiempoVida.value=="0"&&band==1){
			alert ("Tiempo de Vida, No Puede ser Cero");
			band=0;
		}
		//Se verifica que el sistema haya sido ingresado
		if (frm_tiempoVidaES.cmb_tipoTiempo.value==""&&band==1){
			alert ("Seleccionar el Tipo de Tiempo de Vida");
			band=0;
		}
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}



/*****************************************************************************************************************************************************/
/*************************************************************REGISTRAR ACTA SEGHURIDAD HIGIENE*******************************************************/
/*****************************************************************************************************************************************************/

//Función qu permite activar o desactivar una caja de texto en caso de que el radio button seleccionado sea extraordinario
function activarExtraOrdinario(radio){
	//Verificamos el radio que haya sido seleccionado
	if(radio=="ordinaria"){
		document.getElementById("txt_extraordinariaPor").value="N/A";
		document.getElementById("txt_extraordinariaPor").readOnly=true;
	}
	else if(radio=="extraordinaria"){
		document.getElementById("txt_extraordinariaPor").value="";
		document.getElementById("txt_extraordinariaPor").readOnly=false;
	}
}


//Funcion que permite agregar loa nombres y puestos de los Asistentes
function abrirRegNombrePuestoAsistentes(){
	//Al llamar a la ventana le enviamos el valor de la variable por el GET
		window.open('verRegNombrePuestoAsistentes.php','_blank','top=50, left=50, width=800, height=450, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
}

//Funcion que permite agregar los puntos tratados en la agenda
function abrirRegPuntosAgenda(){
	//Al llamar a la ventana le enviamos el valor de la variable por el GET
		window.open('verPuntosAgenda.php','_blank','top=50, left=50, width=800, height=450, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
}

//Funcion que permite agregar las areas visitadas
function abrirRegAreasVisitadas(){
	//Al llamar a la ventana le enviamos el valor de la variable por el GET
		window.open('verRegAreasVisitadas.php','_blank','top=50, left=50, width=800, height=450, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
}

//Funcion que permite agregar  los accidentes
function abrirRegAccidentes(){
	//Al llamar a la ventana le enviamos el valor de la variable por el GET
		window.open('verRegAccidentes.php','_blank','top=50, left=50, width=800, height=450, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
}

//Funcion que permite agregar los recorridos de verificacion
function abrirRegRecorridosVer(){
	//Al llamar a la ventana le enviamos el valor de la variable por el GET
		window.open('verRegRecorridosVerificacion.php','_blank','top=50, left=50, width=800, height=450, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
}


//Funcion para Validar que se ingresen todos los datos dnetroi dle formulario donde se genera el permiso para trabajos peligrosos
function valFormRegActaSH(frm_agregarActa){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;

	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_agregarActa.txt_periodoVer.value.substr(0,2);
	var iniMes=frm_agregarActa.txt_periodoVer.value.substr(3,2);
	var iniAnio=frm_agregarActa.txt_periodoVer.value.substr(6,4);
	
	//Extraer los datos de la fecha de Cierre, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_agregarActa.txt_al.value.substr(0,2);
	var finMes=frm_agregarActa.txt_al.value.substr(3,2);
	var finAnio=frm_agregarActa.txt_al.value.substr(6,4);
	
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	//Verificar que el año de Fin sea mayor al de Inicio
	if(fechaIni>fechaFin){
		band=0;
		alert ("La fecha de Inicio del Periodo de Verificación\nNo puede ser mayor a la Fecha de Cierre");
	}
	
	//Se verifica que  la descripcion haya sido ingresada
	if (frm_agregarActa.txa_descripcion.value==""){
		alert ("Introducir el Descripción del Acta");
		band=0;
	}
	//Se verifica que el tipo de verificacion hatya sido seleccionado
	if(frm_agregarActa.rbd_tipoVer.length>=2&&band==1){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_agregarActa.rbd_tipoVer.length;i++){
			if(frm_agregarActa.rbd_tipoVer[i].checked)
				res = 1;
		}
		if(res==0){
			alert("Seleccionar Tipo de Verificación");			
			band=0;
		}
	}
	//Se verifica que el tipo de verificacion hatya sido seleccionado
	if (frm_agregarActa.txt_extraordinariaPor.value==""&&band==1){
		alert ("Ingresar Quien Realizó El Tipo de Verificación");
		band=0;
	}
	//Se verifica que el tipo de verificacion hatya sido seleccionado
	if (frm_agregarActa.txt_horaInicio.value==""&&band==1){
		alert ("Ingresar Hora de Inicio");
		band=0;
	}
		//Se verifica que el tipo de verificacion hatya sido seleccionado
	if (frm_agregarActa.txt_horaTerminacion.value==""&&band==1){
		alert ("Ingresar Hora de Terminación");
		band=0;
	}
	
	if (frm_agregarActa.txt_gteGral.value==""&&band==1){
		alert ("Ingresar Gerente General");
		band=0;
	}
	
	if (frm_agregarActa.txt_representante.value==""&&band==1){
		alert ("Ingresar Representante");
		band=0;
	}

	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

//Funcion que permite conocer cual boton fue seleccionado para permitir la ejecucion de la funcion AJAX que valida que existan las sesiones
function verificarParametros(){
	//Obtener el nombre del Boton seleccionada
	var boton = document.getElementById("hdn_botonSel").value;
	//Obtener el valor de la variable que indica si hay datos en la SESSION
	var datosSesion = document.getElementById("hdn_datosSesion").value;
	//Obtener el nombre del CheckBox que será deshabilitado
	var nomCkb = document.getElementById("hdn_nomCheckBox").value;
		//QUITAR ESTA LINEA DE CODIGO
	//alert("Datos\nboton =>"+boton+"\nDatos Sesion => "+datosSesion+"\nCheckbox => "+nomCkb);
	if(boton=="finalizar"){
		verificarSesion();
		window.opener.document.getElementById(nomCkb).disabled = true;
	}
	if(((boton=="cerrar") && datosSesion=="si")||(boton=="x" && datosSesion=="si")){
		verificarSesion();
		window.opener.document.getElementById(nomCkb).disabled = true;
		window.opener.document.getElementById(nomCkb).checked = true;
		
	}
	if((boton=="cerrar") && datosSesion=="no"){
		window.opener.document.getElementById(nomCkb).checked = false;
		//Cerrar la Ventana
		window.close();
	}
}


//Funcion que permite validar el formulario de ver registro puesto y nombre de los asistentes
function valFormPuestoAsist(frm_agregarRegistro){
	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;

	//Se verifica que se haya seleccionado el puesto
	if (frm_agregarRegistro.cmb_puestoAsistente.value==""){
		alert ("Seleccionar Puesto");
		band=0;
	}
		//Se verifica que se haya ingreado el nombre
	if (frm_agregarRegistro.txt_nombreAsistente.value==""&&band==1){
		alert ("Ingresar el Nombre");
		band=0;
	}

	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}


//Funcion que permite validar el formulario de ver registro puesto y nombre de los asistentes
function valFormPuntosAgenda(frm_agregarRegistro){
	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;

	//Se verifica que se haya ingresado la descripcion 
	if (frm_agregarRegistro.txa_puntoAgenda.value==""){
		alert ("Ingresar Descripción del Punto de Agenda");
		band=0;
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}


//Funcion que permite validar el formulario de ver Areas Visitadas
function valFormAreasVisitadas(frm_agregarRegistro){
	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;

	//Se verifica que se haya ingresado la descripcion del área visitada 
	if (frm_agregarRegistro.txa_area.value==""){
		alert ("Ingresar Descripción del Área Visitada");
		band=0;
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}


//Funcion que permite validar el formulario de ver los Recorridos de Verificacion
function valFormRecVer(frm_agregarRegistro){
	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_agregarRegistro.txt_fechaCumplida.value.substr(0,2);
	var iniMes=frm_agregarRegistro.txt_fechaCumplida.value.substr(3,2);
	var iniAnio=frm_agregarRegistro.txt_fechaCumplida.value.substr(6,4);
	
	//Extraer los datos de la fecha de Cierre, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_agregarRegistro.txt_fechaLimite.value.substr(0,2);
	var finMes=frm_agregarRegistro.txt_fechaLimite.value.substr(3,2);
	var finAnio=frm_agregarRegistro.txt_fechaLimite.value.substr(6,4);
	
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	//Verificar que el año de Fin sea mayor al de Inicio
	if(fechaIni>fechaFin){
		band=0;
		alert ("La Fecha Límite del Recorrido de Verificación\nNo puede ser mayor a la Fecha Cumplida");
	}

	//Se verifica que se haya ingresado el responsable 
	if (frm_agregarRegistro.txt_responsable.value==""&&band==1){
		alert ("Ingresar Responsable Recorrido Seguridad");
		band=0;
	}
	
	//Se verifica que se haya ingresado el Acto Inseguro 
	if (frm_agregarRegistro.txt_actoInseguro.value==""&&band==1){
		alert ("Ingresar Acto Inseguro");
		band=0;
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

//Funcion que permite validar el formulario de ver el registro de accidentes
function valFormRegAccidentes(frm_agregarRegistro){
	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;

	//Se verifica que se hayan ingresado las causas del Accidente 
	if (frm_agregarRegistro.txt_causasAcc.value==""){
		alert ("Ingresar Las Causas del Accidente");
		band=0;
	}
	
	//Se verifica que se hayan ingresado las acciones preventivas 
	if (frm_agregarRegistro.txa_accPrev.value==""&&band==1){
		alert ("Ingresar Acciones Preventivas");
		band=0;
	}
	
	//Se verifica que se haya ingresado el Nombre del Accidente 
	if (frm_agregarRegistro.txt_nomAcc.value==""&&band==1){
		alert ("Ingresar Nombre del Accidente");
		band=0;
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

/*********************************************************CONSULTAR ACTA SEGURIDAD E HIGIENE*********************************************************/
//Funcion para Validar se seleccionen las fechas correctamente
function valFormConsASH(frm_consultarActa){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;

	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_consultarActa.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_consultarActa.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_consultarActa.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Cierre, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_consultarActa.txt_fechaFin.value.substr(0,2);
	var finMes=frm_consultarActa.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_consultarActa.txt_fechaFin.value.substr(6,4);
	
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	//Verificar que el año de Fin sea mayor al de Inicio
	if(fechaIni>fechaFin){
		band=0;
		alert ("La fecha de Inicio No puede ser mayor a la Fecha de Cierre");
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}


//Funcion que permite validar que una clave haya sido seleccionada
function valFormASHClave(formulario){
	//Variable que nos permite conocer que los campos que son obligatorios cuentan con un registro
	var band=1;
	
	if(formulario.cmb_id.value==""){
		alert("Seleccionar Clave del Acta de Seguridad e Higiene");
		band=0;
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if(band==1)
		return true;
	else
		return false;
}
/*****************************************************************************************************************************************************/
/*************************************************************REGISTRAR RECORRIDOS********************************************************************/
/*****************************************************************************************************************************************************/


function abrirRegFotografico(no, reg){
	//Guardamos el valr de la caja de texto
	var id=document.getElementById("txt_clave").value;
	//Al llamar a la ventana le enviamos el valor de la variable por el GET
	window.open("verRegFotografico.php?clave="+id+"&no="+no+"&regi="+reg,"_blank","top=50, left=50, width=800, height=450, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no");
}



//Funcion que permite validar el formulario de Registrar Recorridos de Seguridad
function valFormRegRecorridosSeguridad(frm_regRecSeg){
	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;

	if(frm_regRecSeg.hdn_botonSel.value=="agregar"){
		//Se verifica que se haya ingresado el responsable 
		if (frm_regRecSeg.txt_responsable.value==""){
			alert ("Ingresar Responsable Recorrido Seguridad");
			band=0;
		}
		
		//Se verifica que se hayan ingresado las observaciones 
		if (frm_regRecSeg.txa_observaciones.value==""&&band==1){
			alert ("Ingresar Observaciones");
			band=0;
		}
		
		//Se verifica que se hayan ingresado los departamentos
		if (frm_regRecSeg.txt_ubicacion.value==""&&band==1){
			alert ("Ingresar Departamentos");
			band=0;
		}
		
		//Se verifica que se haya ingresado el area
		if (frm_regRecSeg.txa_area.value==""&&band==1){
			alert ("Ingresar Área");
			band=0;
		}
		
		//Se verifica que se haya ingresado la anomalia detectada
		if (frm_regRecSeg.txa_anomaliaDet.value==""&&band==1){
			alert ("Ingresar Anomalía Detectada");
			band=0;
		}
		
		//Se verifica que se haya ingresado la correccion de la anomalia
		if (frm_regRecSeg.txa_anomaliaCor.value==""&&band==1){
			alert ("Ingresar Corrección Anomalía");
			band=0;
		}
		
		//Se verifica que se haya ingresado el lugar
		if (frm_regRecSeg.txt_lugar.value==""&&band==1){
			alert ("Ingresar Lugar Incidencia");
			band=0;
		}
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}


//Funcion para Evaluar los datos del formulario de agregar foto a los Recorridos de Seguridad
function valFormCargarFotoRec(frm_agregarFotoRecorrido){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;
	
	//Se verifica que el sistema haya sido ingresado
	if (frm_agregarFotoRecorrido.file_documento.value==""){
		alert ("Introducir Imagen");
		band=0;
	}
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}


/*****************************************************************************************************************************************************/
/*************************************************************MODIFICAR RECORRIDOS********************************************************************/
/*****************************************************************************************************************************************************/

//Funcion que permite ver las anomalias
function verAnomalias(id){
	//Al llamar a la ventana le enviamos el valor de la variable por el GET
	window.open('verAnomalias.php?idRegistro='+id+'','_blank','top=50, left=50, width=900, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
}

//Funcion que permite ver las fotografias registradas
function verFotografias(id){
	//Al llamar a la ventana le enviamos el valor de la variable por el GET
	window.open('verFotosRecSeg.php?idRegistro='+id+'','_blank','top=50, left=50, width=400, height=400, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
}

//Funcion que permite ver los departamentos registrados
function verDepartamentosRecSeg(id){
	//Al llamar a la ventana le enviamos el valor de la variable por el GET
	window.open('verDepartamentosRecSeg.php?idRegistro='+id+'','_blank','top=50, left=50, width=400, height=400, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
}

//VErmite abrir el registro de las fotos
function abrirModRegistroFot(idAn, idReg){
	//Al llamar a la ventana le enviamos el valor de la variable por el GET
	window.open('verModFotografico.php?idAn='+idAn+'&idReg='+idReg+'','_blank','top=50, left=50, width=800, height=450, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
}


//Funcion para Evaluar los datos del formulario de agregar foto a los Recorridos de Seguridad
function valFormModRec(frm_modRegistro2){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;
	
	//Se verifica que el sistema haya sido ingresado
	if (frm_modRegistro2.cmb_id.value==""){
		alert ("Seleccionar Clave del Registro de Recorrido de Seguridad");
		band=0;
	}
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}


/*Esta funcion valida que las fechas elegidas sean correctas*/
function valFormFechasRecSeg(formulario){
	var res=1;
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=formulario.txt_fechaIni.value.substr(0,2);
	var iniMes=formulario.txt_fechaIni.value.substr(3,2);
	var iniAnio=formulario.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Cierre, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=formulario.txt_fechaFin.value.substr(0,2);
	var finMes=formulario.txt_fechaFin.value.substr(3,2);
	var finAnio=formulario.txt_fechaFin.value.substr(6,4);
	
	
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

//Funcion que permite cambiar el submit
function cambiarSubmitRS(){
	//Recuperamos la variable hidden para saber que submit corresponde
	var boton = document.getElementById("hdn_btn").value;
	if(boton=="sbt_exportar"){
		//Enviar a la pagina donde se mustra el reporte
		document.frm_verDetalle.action = "guardar_reporte.php";
	}
	else{
		//Enviar a la pagina donde se mustra el reporte
		document.frm_verDetalle.action = "frm_modificarRecSeg2.php";
		document.frm_verDetalle.submit();
	}
}

/*****************************************************************************************************************************************************/
/******************************************************REGISTRAR INFORME ACCIDENTES INCIDENTES********************************************************/
/*****************************************************************************************************************************************************/

//Funcion para Evaluar los datos del formulario de la acta de incidentes y accidentes
function valFormActaIncAcc(frm_agregarActa){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;
	
	//Se verifica que el sistema haya sido ingresado
	if (frm_agregarActa.txt_lugar.value==""){
		alert ("Introducir Lugar");
		band=0;
	}
	
	//Se verifica que el sistema haya sido ingresado
	if (frm_agregarActa.cmb_turno.value==""&&band==1){
		alert ("Seleccionar Turno");
		band=0;
	}
	
	if (frm_agregarActa.cmb_tipoAccidente.value==""&&band==1){
		alert ("Seleccionar Tipo Accidente");
		band=0;
	}
	
	if (frm_agregarActa.txt_nivel.value==""&&band==1){
		alert ("Introducir Nivel");
		band=0;
	}
	
	if (frm_agregarActa.txt_horaIncidente.value==""&&band==1){
		alert ("Introducir Hora Incidente/Accidente");
		band=0;
	}
	
	if (frm_agregarActa.cmb_area.value==""&&band==1){
		alert ("Seleccionar Área");
		band=0;
	}
	
	if (frm_agregarActa.txt_areaAcc.value==""&&band==1){
		alert ("Introduccir Área Incidente/Accidente");
		band=0;
	}
	
	if (frm_agregarActa.txt_horaAviso.value==""&&band==1){
		alert ("Introduccir Hora de Aviso al Facilitador");
		band=0;
	}
	
	if (frm_agregarActa.cmb_area.value==""&&band==1){
		alert ("Seleccionar Área del Trabajador");
		band=0;
	}
	
	if (frm_agregarActa.txt_nombreFacilitador.value==""&&band==1){
		alert ("Introducir Nombre Facilitador");
		band=0;
	}
	
	if (frm_agregarActa.txt_horaLaborar.value==""&&band==1){
		alert ("Introducir Hora en la que Dejo de Laborar");
		band=0;
	}
	
	if (frm_agregarActa.txt_nombreAcc.value==""&&band==1){
		alert ("Introducir Nombre Accidentado");
		band=0;
	}
	
	if (frm_agregarActa.cmb_puesto.value==""&&band==1){
		alert ("Seleccionar Puesto del Trabajador");
		band=0;
	}
	
	/*if (frm_agregarActa.txt_ficha.value==""&&band==1){
		alert ("Introducir Ficha");
		band=0;
	}*/
	
	if (frm_agregarActa.txt_edad.value==""&&band==1){
		alert ("Introducir Edad");
		band=0;
	}
	
	if (frm_agregarActa.cmb_equipo.value==""&&band==1){
		alert ("Seleccionar Equipo");
		band=0;
	}
	
	/*if (frm_agregarActa.txt_antEmp.value==""&&band==1){
		alert ("Introducir Antigüedad del Empleado en la Empresa");
		band=0;
	}*/	
	if (frm_agregarActa.txt_antPue.value==""&&band==1){
		alert ("Introducir Antigüedad del Empleado en el Puesto");
		band=0;
	}
	if(frm_agregarActa.txt_antPue.value==0&&band==1){
		alert("La Antigüedad del Empleado no Puede ser Igual a Cero (0)");
		band=0;
	}
	
	if (frm_agregarActa.txa_actividadMomAcc.value==""&&band==1){
		alert ("Introducir Actividad en el Momento del Accidente");
		band=0;
	}
	
	if (frm_agregarActa.txa_actHab.value==""&&band==1){
		alert ("Introducir Actividad Habitual");
		band=0;
	}
	
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

//Permite ver el registro de Acciones en el informe de accidentes/incidentes
function abrirModRegAcc(){
	//Al llamar a la ventana le enviamos el valor de la variable por el GET
	window.open('verRegAccionesPrevCorr.php','_blank','top=50, left=50, width=800, height=450, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
}

//Funcion para Evaluar los datos de la ventana emergente
function valFormRegAccPrevCorr(frm_agregarRegistro){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;
	
	//Se verifica que el sistema haya sido ingresado
	if (frm_agregarRegistro.txa_accPrevCorr.value==""){
		alert ("Introducir Acciones Preventivas/Correctivas");
		band=0;
	}
	
	//Se verifica que el sistema haya sido ingresado
	if (frm_agregarRegistro.txt_responsable.value==""&&band==1){
		alert ("Introducir Responsable");
		band=0;
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}


//Funcion para Evaluar los datos de la ventana emergente
function valFormActaIncAcc2(frm_agregarActa){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;
	
	//Variable que nos permite conocer el valor del boton
	var boton = document.getElementById("hdn_boton").value;
	
	//Comprobamos que el boton presionado sea continuar
	if(boton=='continuar'){
		
		//Se verifica que el sistema haya sido ingresado
		if (frm_agregarActa.txa_descripcion.value==""){
			alert ("Introducir Descripción de los Hechos");
			band=0;
		}
		
		//Se verifica que el sistema haya sido ingresado
		if (frm_agregarActa.txa_porque.value==""&& band==1){
			alert ("Introducir  el Porque de los Hechos");
			band=0;
		}
	
		//Se verifica que el sistema haya sido ingresado
		if (frm_agregarActa.txa_lesion.value==""&& band==1){
			alert ("Introducir Tipo de Lesión");
			band=0;
		}
		
		//Se verifica que el sistema haya sido ingresado
		if (frm_agregarActa.txa_actosInseguros.value==""&& band==1){
			alert ("Introducir Actos Inseguros");
			band=0;
		}
		
		//Se verifica que el sistema haya sido ingresado
		if (frm_agregarActa.txa_condicionesInseguras.value==""&& band==1){
			alert ("Introducir Condiciones Inseguras");
			band=0;
		}
	}	
	if(boton=="continuar"&&band==1){
		//Enviar a la pagina donde se mustra el reporte
		document.frm_agregarActa.action = "frm_registrarActaIncidentesAccidentes3.php";
		document.frm_agregarActa.submit();
	}
	if(boton!="continuar"){
		//Enviar a la pagina donde se mustra la informcion del plan a modificar
		document.frm_agregarActa.action = "frm_registrarActaIncidentesAccidentes.php?regresar=1.php";
		document.frm_agregarActa.submit();
	}
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	//if (band==1)
		//return true;
	//else
		return false;
}


//Funcion para Evaluar los datos de la ventana emergente
function valFormActaIncAcc3(frm_agregarActa){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;
	
	//Se verifica que el sistema haya sido ingresado
	if (frm_agregarActa.txa_observaciones.value==""){
		alert ("Introducir Observaciones");
		band=0;
	}
	
	//Se verifica que el sistema haya sido ingresado
	if (frm_agregarActa.txt_jefeSeguridad.value==""&& band==1){
		alert ("Introducir Jefe de Seguridad");
		band=0;
	}

	//Se verifica que el sistema haya sido ingresado
	if (frm_agregarActa.txt_coordinadorCSH.value==""&& band==1){
		alert ("Introducir Coordinador");
		band=0;
	}
	
	//Se verifica que el sistema haya sido ingresado
	if (frm_agregarActa.txt_deptoSeguridad.value==""&& band==1){
		alert ("Introducir Departamento de Seguridad");
		band=0;
	}
	
	//Se verifica que el sistema haya sido ingresado
	if (frm_agregarActa.txt_secretarioCSH.value==""&& band==1){
		alert ("Introducir Secretario CSH");
		band=0;
	}
	
	//Se verifica que el sistema haya sido ingresado
	if (frm_agregarActa.txt_testigo.value==""&& band==1){
		alert ("Introducir Testigo");
		band=0;
	}
	
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}


/*****************************************************************************************************************************************************/
/******************************************************CONSULTAR INFORME ACCIDENTES INCIDENTES********************************************************/
/*****************************************************************************************************************************************************/

//Funcion para Evaluar el consultar Acta de incidentes accidentes
function valFormActaTipo(formulario){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;
	
	//Se verifica que el sistema haya sido ingresado
	if (formulario.cmb_tipo.value==""){
		alert ("Seleccionar el Tipo de Informe");
		band=0;
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

/***************************************************************************************************************************************************************/
/******************************************************************SECCIÓN DE REPORTES**************************************************************************/
/***************************************************************************************************************************************************************/

//Funcion que permite complementar el Permiso de Altutras con las condiciones de seguridad a seguir dnetro de una Ventana Emrgente
function graficaAccInc(imagen){
	//Al llamar a la ventana le enviamos el valor de la variable por el GET
		window.open('verGraficaAccInc.php?imagen='+imagen,'_blank','top=50, left=50, width=650, height=450, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
}

function valFomrAccIn(formulario){	
//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=formulario.txt_fechaIni.value.substr(0,2);
	var iniMes=formulario.txt_fechaIni.value.substr(3,2);
	var iniAnio=formulario.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Cierre, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=formulario.txt_fechaFin.value.substr(0,2);
	var finMes=formulario.txt_fechaFin.value.substr(3,2);
	var finAnio=formulario.txt_fechaFin.value.substr(6,4);
	
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	//Verificar que el año de Fin sea mayor al de Inicio
	if(fechaIni>fechaFin){
		band=0;
		alert ("La fecha de Inicio no puede ser mayor a la Fecha de Cierre");
	}
	
	//Se verifica que el sistema haya sido ingresado
	if (formulario.cmb_tipo.value==""&&band==1){
		alert ("Seleccionar el Tipo de Ordenamiento");
		band=0;
	}
	
	//Se verifica que el sistema haya sido ingresado
	if (formulario.cmb_opcion.value==""&&band==1){
		alert ("Seleccionar Elemento");
		band=0;
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;


}
/******************************************************************REPORTES PLAN CONTINGENCIA*****************************************************************/

/*Esta funcion valida que las fechas elegidas sean correctas*/
function valFormRptPlanesContingencia(formulario){
	var res=1;
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=formulario.txt_fechaIni.value.substr(0,2);
	var iniMes=formulario.txt_fechaIni.value.substr(3,2);
	var iniAnio=formulario.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Cierre, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=formulario.txt_fechaFin.value.substr(0,2);
	var finMes=formulario.txt_fechaFin.value.substr(3,2);
	var finAnio=formulario.txt_fechaFin.value.substr(6,4);
	
	
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

/******************************************************************REPORTES EMPLEADOS*****************************************************************/

//Funcion que valida que se seleccionen empleados para exportarlos a Excel
function valFormExportarEmpleados(frm_exportarEmpleados){
	var res=0;//Si el valor permanece en 0, significa que ningun registro fue seleccionado
	//Recorrer tdos los elementos del formulario
	for(var i=0;i<frm_exportarEmpleados.elements.length;i++){
		//Obtener cada elemento por separado
		elemento=frm_exportarEmpleados.elements[i];
		//Verificar el tipo para saber si son Checkbox
		if (elemento.type=="checkbox")
			if(elemento.checked)//Verificar si esta checado
				res=1;//Si esta checado, activar la variable
	}
	//Si se activo la variable, quiere decir que por lo menos se selecciono un checkbox
	if(res==1)
		return true;
	else{
		alert("Seleccionar al Menos Un Campo para Exportar");
		return false;
	}
}

/*Estan función activa  y desactiva todos lo CheckBox de registrar asistencia a capacitaciones*/
function checarTodos(chkbox,nomForm){
	for(var i=0;i<document[nomForm].elements.length;i++){
		//Variable
		elemento=document[nomForm].elements[i];
		if (elemento.type=="checkbox")
			elemento.checked=chkbox.checked;
	}	
}