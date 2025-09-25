/**
  * Nombre del Módulo: Dirección General
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 13/Marzo/2012
  * Descripción: Este archivo contiene funciones que sirven para interactuar de forma adecuada en cuestiones de presentacion con el módulo de Dirección General
  */

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

/***************************************************************************************************************************************************************/
/***************************************************************MODULO DE PRODUCCION****************************************************************************/
/***************************************************************************************************************************************************************/
/*Esta funcion se encarga de mostrar en pantalla el detalle de Avance de la Produccion*/
function mostrarDetalleAvanceProd(){
	//Ocultar elementos
	document.getElementById("form-selecPeriodo").style.visibility="hidden";
	document.getElementById("consultarDetalle").style.visibility='hidden';
	document.getElementById("resultados").style.visibility='hidden';
	document.getElementById("cmb_periodo").style.visibility='hidden';
	//Mostrar elementos
	document.getElementById("tabla").style.visibility='visible';
	document.getElementById("parrila-volver").style.visibility='visible';
}
/*Esta funcion se encarga de ocultar en pantalla el detalle de Avance de la Produccion*/
function ocultarDetalleAvanceProd(){
	//Ocultar elementos
	document.getElementById("tabla").style.visibility='hidden';
	document.getElementById("parrila-volver").style.visibility='hidden';
	//Mostrar elementos
	document.getElementById("form-selecPeriodo").style.visibility="visible";
	document.getElementById("consultarDetalle").style.visibility='visible';
	document.getElementById("resultados").style.visibility='visible';
	document.getElementById("cmb_periodo").style.visibility='visible';
}

/***************************************************************************************************************************************************************/
/******************************************************************MODULO DE ALMACÉN****************************************************************************/
/***************************************************************************************************************************************************************/

function mostrarDetalleSalidas(fechaI,fechaF,combo){
	fechaI=document.getElementById(fechaI).value;
	fechaF=document.getElementById(fechaF).value;
	combo=document.getElementById(combo).value;
	window.open('verSalidasDetalle.php?fechaI='+fechaI+'&fechaF='+fechaF+'&combo='+combo,'detalleAlm','top=100,left=100,width=860,height=645,status=no,menubar=no,resizable=yes,scrollbars=yes,toolbar=no,location=no, directories=no');
}

/***************************************************************************************************************************************************************/
/*************************************************************MODULO DE RECURSOS HUMANOS************************************************************************/
/***************************************************************************************************************************************************************/

//funcion que muestra ek Detalle de Altas, Bajas y Cambios de Puesto
function mostrarDetalleRH(fechaIni,fechaFin,comboDep){
	fechaI=document.getElementById(fechaIni).value;
	fechaF=document.getElementById(fechaFin).value;
	depto=document.getElementById(comboDep).value;
	window.open('verRHDetalle.php?fechaI='+fechaI+'&fechaF='+fechaF+'&depto='+depto,'detalleRec','top=100,left=100,width=1035,height=645,status=no,menubar=no,resizable=yes,scrollbars=yes,toolbar=no,location=no, directories=no');
}

/***************************************************************************************************************************************************************/
/********************************************************SECCION DE DIRECCION GENERAL***************************************************************************/
/***************************************************************************************************************************************************************/

function restablecerFormulario(){
	document.getElementById("cmb_tipoMov").value="";
	document.getElementById("txt_cantidad").value="";
	document.getElementById("txa_concepto").value="";
	document.getElementById("txt_responsable").value=""
	document.getElementById("txt_fecha").value=document.getElementById("txt_fecha").defaultValue;
}