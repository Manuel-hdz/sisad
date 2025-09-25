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
/*********************************************************************************AGREGAR PLATILLOS***************************************************************************************/
/*****************************************************************************************************************************************************************************************/
function valFormAgregarPlatillo(frm_aregarPlatillos){
	//Esta variable ayudara a detectar cuando un campo obligatorio no tenga datos o el contenido del mismo no sea el correcto
	var validacion = 1;
	
	//Validar datos de los empleados
	if(frm_aregarPlatillos.txa_descripcion.value.trim()==""){
		alert("Ingresar la descripcion del platillo");
		txa_descripcion.focus();
		validacion = 0;
	}
	if(frm_aregarPlatillos.txt_costo.value=="0.00" && validacion==1){
		alert("Ingresar el costo del platillo");
		txt_costo.focus();
		validacion = 0;
	}
	
	//Regresar el resultado d ela validación
	if(validacion==1)
		return true;
	else
		return false;
				
}//Cierre de la función valFormAgregarPlatillo(frm_aregarPlatillos)

/*****************************************************************************************************************************************************************************************/
/*********************************************************************************MODIFICAR PLATILLOS*************************************************************************************/
/*****************************************************************************************************************************************************************************************/
function valFormConsultaPlatillos(frm_consultaPlatillos){
	//Variable para controlar la validacion
	var band = 1;
	var flag=0;
	var cantidad=document.getElementsByName("rdb_idPlatillo").length;
	for (var i=0;i<cantidad;i++){
		if (document.getElementById("rdb_idPlatillo"+(i+1)).checked==true){
			flag=1;
		}
	}
	
	if (flag==0){
		alert("Seleccionar un Platillo a Modificar");
		band=0;
	}
	
	if(band==1)
		return true;
	else
		return false;
}

function valFormModificarPlatillo(frm_modificarPlatillos){
	//Esta variable ayudara a detectar cuando un campo obligatorio no tenga datos o el contenido del mismo no sea el correcto
	var validacion = 1;
	
	//Validar datos de los empleados
	if(frm_modificarPlatillos.txa_descripcion.value.trim()==""){
		alert("Ingresar la descripcion del platillo");
		txa_descripcion.focus();
		validacion = 0;
	}
	if(frm_modificarPlatillos.txt_costo.value=="0.00" && validacion==1){
		alert("Ingresar el costo del platillo");
		txt_costo.focus();
		validacion = 0;
	}
	
	//Regresar el resultado d ela validación
	if(validacion==1)
		return true;
	else
		return false;
				
}//Cierre de la función valFormModificarPlatillo(frm_modificarPlatillos)

/*******************************************************************************************************************************************************************************************/
/*********************************************************************************AGREGAR PLATILLOS DIA*************************************************************************************/
/*******************************************************************************************************************************************************************************************/
function valFormAgregarPlatilloDia(frm_agregarPlatillosDia){
	//Esta variable ayudara a detectar cuando un campo obligatorio no tenga datos o el contenido del mismo no sea el correcto
	var validacion = 1;
	
	//Validar datos de los empleados
	if(frm_agregarPlatillosDia.cmb_plat.value==""){
		alert("Seleccionar un platillo del menu");
		cmb_plat.focus();
		validacion = 0;
	}
	if(frm_agregarPlatillosDia.cmb_turno.value=="" && validacion==1){
		alert("Seleccionar el turno a asignar");
		cmb_turno.focus();
		validacion = 0;
	}
	if(frm_agregarPlatillosDia.txt_cantidad.value=="" && validacion==1){
		alert("Ingresar la cantidad a vender");
		txt_cantidad.focus();
		validacion = 0;
	}
	
	//Regresar el resultado d ela validación
	if(validacion==1)
		return true;
	else
		return false;
				
}//Cierre de la función valFormAgregarPlatilloDia(frm_agregarPlatillosDia)

/*******************************************************************************************************************************************************************************************/
/************************************************************************************AGREGAR BITACORA***************************************************************************************/
/*******************************************************************************************************************************************************************************************/
function valFormAgregarBitacora(frm_aregarBitacora){
	//Esta variable ayudara a detectar cuando un campo obligatorio no tenga datos o el contenido del mismo no sea el correcto
	var validacion = 1;
	
	//Validar datos de los empleados
	if(frm_aregarBitacora.txt_codBarTrabajador.value==""){
		alert("Seleccionar el codigo del empleado");
		txt_codBarTrabajador.focus();
		validacion = 0;
	}
	if(frm_aregarBitacora.cmb_turno.value=="" && validacion==1){
		alert("Seleccionar el turno");
		cmb_turno.focus();
		validacion = 0;
	}
	if(frm_aregarBitacora.cmb_plat.value=="" && validacion==1){
		alert("Seleccionar el platillo");
		cmb_plat.focus();
		validacion = 0;
	}
	if(frm_aregarBitacora.cmb_estado.value=="" && validacion==1){
		alert("Seleccionar el estado");
		cmb_estado.focus();
		validacion = 0;
	}
	if(frm_aregarBitacora.cmb_pag.value=="" && validacion==1){
		alert("Seleccionar si esta pagado o no");
		cmb_pag.focus();
		validacion = 0;
	}
	if(frm_aregarBitacora.txt_descuento.value=="" && validacion==1){
		alert("Ingresar el descuento del empleado");
		txt_descuento.focus();
		validacion = 0;
	}
	
	//Regresar el resultado d ela validación
	if(validacion==1)
		return true;
	else
		return false;
				
}//Cierre de la función valFormAgregarBitacora(frm_aregarBitacora)

/*********************************************************************************************************************************************************************************************/
/************************************************************************************MODIFICAR BITACORA***************************************************************************************/
/*********************************************************************************************************************************************************************************************/
function validarFechas(fecha1,fecha2){
	var band = 1;
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=fecha1.substr(0,2);
	var iniMes=fecha1.substr(3,2);
	var iniAnio=fecha1.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=fecha2.substr(0,2);
	var finMes=fecha2.substr(3,2);
	var finAnio=fecha2.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	//Verificar que la fecha de inicio no sea mayor a la de cierre
	if(fechaIni>fechaFin){
		band=0;
		alert ("La Fecha de Fin no puede ser menor a la Fecha de Inicio");
	}		
	
	if(band==1)
		return true;
	else
		return false;
}

function valFormModificarBitacoras(frm_modificarBitacoras){
	//Variable para controlar la validacion
	var band = 1;
	var flag=0;
	var cantidad=document.getElementsByName("rdb_idBitacora").length;
	for (var i=0;i<cantidad;i++){
		if (document.getElementById("rdb_idBitacora"+(i+1)).checked==true){
			flag=1;
		}
	}
	
	if (flag==0){
		alert("Seleccionar una Bitacora a Modificar");
		band=0;
	}
	
	if(band==1)
		return true;
	else
		return false;
}

function valFormModificarBitacora(frm_modificarBitacora){
	//Esta variable ayudara a detectar cuando un campo obligatorio no tenga datos o el contenido del mismo no sea el correcto
	var validacion = 1;
	
	if(frm_modificarBitacora.cmb_estado.value=="" && validacion==1){
		alert("Seleccionar el estado");
		cmb_estado.focus();
		validacion = 0;
	}
	if(frm_modificarBitacora.cmb_pag.value=="" && validacion==1){
		alert("Seleccionar si esta pagado o no");
		cmb_pag.focus();
		validacion = 0;
	}
	
	//Regresar el resultado d ela validación
	if(validacion==1)
		return true;
	else
		return false;
				
}//Cierre de la función valFormAgregarBitacora(frm_aregarBitacora)