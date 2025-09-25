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
/*************************************************VALIDAR FECHAS EN FRM_REGISTRARPRESUPUESTO***********************************************************/
/***************************************************************************************************************************************************************/
/*
//Esta funcion suma 30 dias a la fecha seleccionada como Inicial
function sumarDiasMes(){
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=document.getElementById("txt_fechaIni").value.substr(0,2);
	var iniMes=document.getElementById("txt_fechaIni").value.substr(3,2);
	var iniAnio=document.getElementById("txt_fechaIni").value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=document.getElementById("txt_fechaFin").value.substr(0,2);
	var finMes=document.getElementById("txt_fechaFin").value.substr(3,2);
	var finAnio=document.getElementById("txt_fechaFin").value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
		
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);
	
	var fechaIniSeccionada = fechaIni.toString().split(" ");
	var mes=obtenerNumeroMes(fechaIniSeccionada[1]);
	var anio=fechaIniSeccionada[5];	
	
	//Obtenemos las fechas para realizar la resta
	var fechaTotal=fechaFin.getTime()-fechaIni.getTime();
	//Realizamos la resta entre dos fechas para que nos de el numero de dias
	var dias = Math.floor(fechaTotal / (1000 * 60 * 60 * 24)) ;
		
	var diasDelMes=diasMes(mes, anio);
	
	if(!dias<=(diasDelMes)){
		fechaFin.setTime(fechaIni.getTime()+(86400000*diasDelMes));
		var fechaSeccionada = fechaFin.toString().split(" ");
		if(parseInt(fechaSeccionada[2]) < 10) { fechaSeccionada[2] = '0' + fechaSeccionada[2]; }
		document.getElementById("txt_fechaFin").value=fechaSeccionada[2]+"/"+obtenerNumeroMes(fechaSeccionada[1])+"/"+fechaSeccionada[5];
	}
}


//Función que permite conocer el numero de domingos entre dos fechas dadas
function calcularDomingos(){
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=document.getElementById("txt_fechaIni").value.substr(0,2);
	var iniMes=document.getElementById("txt_fechaIni").value.substr(3,2);
	var iniAnio=document.getElementById("txt_fechaIni").value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=document.getElementById("txt_fechaFin").value.substr(0,2);
	var finMes=document.getElementById("txt_fechaFin").value.substr(3,2);
	var finAnio=document.getElementById("txt_fechaFin").value.substr(6,4);		
	
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);
	
	
	//Si la Fecha de inicio es menor que la de fin, proceder a contar los domingo
	if(fechaIni<fechaFin){
	
		//Seccionamos las fechas y convertimos a String 
		var fechaIniSeccionada = fechaIni.toString().split(" ");
		//Contiene el dia actual de la fecha para tomar como inicio para la busqueda de los domingos
		var diaIni = fechaIniSeccionada[2];
		//Obtenemos el mes Inicial y obtenemos el numero de mes del mismo para obtener despues el numero de dias
		var mesIni = obtenerNumeroMes(fechaIniSeccionada[1]);
		//Obtenemos el año para mandarlo como parametro a la funcion diasMes que obtiene el numero de dias del mes; esto para saber si el año seleccionado es bisiesto
		var anioIni = fechaIniSeccionada[5];	
		//Obtenemos los dias que tiene el mes de inicio
		var diasMesIni = diasMes(mesIni, anioIni);
		
		//Variable que almacenara el total de domingos de los meses seleccionados
		var contDomingosMes = 0;
		var diasLaborales = 0;
		
		//Seccionamos las fechas y convertimos a String
		var fechaFinSeccionada = fechaFin.toString().split(" ");
		//Contiene el dia final para establecerlo como limite de la busqueda
		var diaFin = fechaFinSeccionada[2];
		//Obtenemos el mes inicial y obtenemos el numero de mes 
		var mesFin = obtenerNumeroMes(fechaFinSeccionada[1]);
		//Obtenemos el año de la fecha seccionada
		var anioFin = fechaFinSeccionada[5];	
		
		
		
		//OBTENER LA CANTIDAD DE DOMINGOS DEL RANGO DE FECHAS PERTENECIENTE AL MISMO AÑO
		if(iniAnio==finAnio){
			//Verificar si el Rango de fechas esta dentro del mismo mes, contar los domingos entre las fechas dadas
			if(iniMes==finMes){
				//Recorremos en busqueda de los domingos
				
				if(diaIni<10){
					diaIni="0"+diaIni;
				}
				if(diaFin<10){
					diaFin="0"+diaFin;
				}
				for(var i=diaIni; i<=diaFin;  i++){
					//Crear la fecha para verificar si el dia es Domingo
					var fechaIni=iniMes+"/"+i+"/"+iniAnio;
					//Convertir la cadena a formato de fecha
					fechaIni = new Date(fechaIni);
					//Buscamos el domingo
					if(fechaIni.getDay()==0){
						//Aumentamos el contador
						contDomingosMes++;	
					}
					else
						diasLaborales++;
				}
			}	
			//De lo contrario contar los domingos de los menses existentes entre las fechas dadas
			else{		
				
				//CONTAR LOS DOMINGOS DEL MES INICIAL			
				for(var i=diaIni; i<=diasMesIni; i++){
					var fechaIni=iniMes+"/"+i+"/"+iniAnio;
					//Convertir la cadena a formato de fecha
					fechaIni=new Date(fechaIni);
					//Buscamos el domingo
					if(fechaIni.getDay()==0){
						//Aumentamos el contador
						contDomingosMes++;	
					}
					else
						diasLaborales++;
				}
					
				
				//CONTAR LOS DOMINGOS DE LOS MESES INTERMEDIOS
				
				//Determinar si los meses seleccionado son Consecutivos, caso contrario encontrar la cantidad de meses que existen entre el mes inicial y el mes final
				var mesInicial = 0;
				//Revisar si el mes inicial es '08' o '09'
				if(mesIni=="08")
					mesInicial = 8;					
				else if(mesIni=="09")
					mesInicial = 9;		
				else
					mesInicial = parseInt(mesIni);
				
				var mesFinal = 0;
				//Revisar si el mes final es '08' o '09'
				if(mesFin=="08")
					mesFinal = 8;					
				else if(mesFin=="09")
					mesFinal = 9;		
				else
					mesFinal = parseInt(mesFin);
				
				
				//Sumar 1 al mes de inicio para saber si son meses Consecutivos o no
				var noMesIni = mesInicial + 1;
						
				//Verificar que los meses no sean consecutivos para considerar los meses intermedios		
				if(noMesIni!=mesFinal){
					//Obtener la cantidad de meses entre las Fechas seleccionadas, descartando el mes Inicial y el Final
					var cantMeses = (mesFinal - mesInicial) - 1;
					//Obtener el mes actual
					var mesActual = mesInicial + 1;								
									
					
					//Ciclo para obtener los domingos de cada mes entre las fechas dadas
					for(var j=0;j<cantMeses;j++){															
						
						//Obtener los dias del mes actual
						if(mesActual<10) mesActualStr = "0"+mesActual.toString();
						else mesActualStr = mesActual.toString();
						var diasMesActual = diasMes(mesActualStr, anioIni);										
						
						
						//Recorrer el Mes para obtener los domingos
						for(var i=1; i<=diasMesActual; i++){
							var fechaActual = mesActual.toString()+"/"+i+"/"+iniAnio;
							//Convertir la cadena a formato de fecha
							fechaActual = new Date(fechaActual);
							//Buscamos el domingo
							if(fechaActual.getDay()==0){
								//Aumentamos el contador
								contDomingosMes++;	
							}
							else
								diasLaborales++;
						}//Cierre for(var i=1; i<=diasMesActual; i++)																				
						
						//Incrementar el mes Actual
						mesActual++;
					}//Cierre for(var i=0;i<cantMeses;i++)				
					
				}//Cierre if(noMesIni!=mesFinal)
				
				
				//CONTAR LOS DOMINGOS DEL MES FINAL
				//Recorremos en Busqueda de los domingos dentro del mes Final
				for(var i=1; i<=diaFin;  i++){
					var fechaFin=finMes+"/"+i+"/"+finAnio;
					//Convertir la cadena a formato valido para JS
					fechaFin=new Date(fechaFin);
					if(fechaFin.getDay()==0)
						contDomingosMes++;	
					else
						diasLaborales++;
				}		
						
			}//Cierre del ELSE de if(iniMes==finMes)	
			
			
		}//Cierre if(iniAnio==finAnio)
		
		
		//OBTENER LOS DOMINGOS DEL RANGO DE FECHAS CUANDO ABARCA AÑOS DIFERENTES
		else{
			
			//CONTAR DOMINGOS DEL MES INICIAL			
			for(var i=diaIni; i<=diasMesIni; i++){
				var fechaIni=iniMes+"/"+i+"/"+iniAnio;
				//Convertir la cadena a formato de fecha
				fechaIni=new Date(fechaIni);
				//Buscamos el domingo
				if(fechaIni.getDay()==0){
					//Aumentamos el contador
					contDomingosMes++;	
				}
				else
					diasLaborales++;
			}
				
			
			//CONTAR LOS DOMINGOS DE LOS MESES INTERMEDIOS
			
			//Determinar si los meses seleccionado son Consecutivos, caso contrario encontrar la cantidad de meses que existen entre el mes inicial y el mes final
			var mesInicial = 0;
			//Revisar si el mes inicial es '08' o '09'
			if(mesIni=="08")
				mesInicial = 8;					
			else if(mesIni=="09")
				mesInicial = 9;		
			else
				mesInicial = parseInt(mesIni);
			
			var mesFinal = 0;
			//Revisar si el mes final es '08' o '09'
			if(mesFin=="08")
				mesFinal = 8;					
			else if(mesFin=="09")
				mesFinal = 9;		
			else
				mesFinal = parseInt(mesFin);
			
			
			
			//Sumar 1 al año de inicio para saber si el siguiente año es consecutivo o no
			var anioActual = parseInt(anioIni); 
			var noAnioIni =  anioActual + 1;
			var noAnioFin = parseInt(anioFin);
			//Sumar 1 al mes de inicio para saber si el siguiente mes es consecutivo o no
			var noMesIni = mesInicial + 1;
			//Sin el mes inicial es Diciembre, entonces el valor obtenido sera 13 que equivale al primer mes del siguiente año (1 = Enero)
			if(noMesIni==13) noMesIni = 1;
						
					
			//Verificar que los meses no sean consecutivos, así como los años, para considerar los meses intermedios
			if( (noMesIni!=mesFinal) || (noAnioIni!=noAnioFin) ){							
				
				//Obtener la cantidad de meses entre las Fechas seleccionadas, descartando el mes Inicial y el Final
				//Obtener los Meses del Año Inicial
				var cantMeses = 12 - mesInicial;			
				
				//Obtener la diferencia de Años, menos 1 para descartar el Año Final
				var anios = (noAnioFin - anioActual) - 1;
				if(anios>=1)
					cantMeses += (anios*12);				
				
				//Obtener los mes del Año Final
				cantMeses += mesFinal-1;
												
				//Obtener el mes actual
				var mesActual = mesInicial + 1;												
				
												
				//Ciclo para obtener los domingos de cada mes entre las fechas dadas
				for(var j=0;j<cantMeses;j++){															
					//Verificar el reinicio de meses y el aumento de año
					if(mesActual==13){
						mesActual = 1;
						anioActual++;
					}
					
					
					//Obtener los dias del mes actual
					if(mesActual<10) mesActualStr = "0"+mesActual.toString();
					else mesActualStr = mesActual.toString();
					var diasMesActual = diasMes(mesActualStr, anioActual);
					
					
					//Recorrer el Mes para obtener los domingos
					for(var i=1; i<=diasMesActual; i++){
						var fechaActual = mesActualStr+"/"+i+"/"+anioActual;
						//Convertir la cadena a formato de fecha
						fechaActual = new Date(fechaActual);
						//Buscamos el domingo
						if(fechaActual.getDay()==0){
							//Aumentamos el contador
							contDomingosMes++;	
						}
						else
							diasLaborales++;
					}//Cierre for(var i=1; i<=diasMesActual; i++)																				
					
					//Incrementar el mes Actual
					mesActual++;
				}//Cierre for(var i=0;i<cantMeses;i++)
				
			}//Cierre if(noMesIni!=mesFinal)
			
			
			//CONTAR LOS DOMINGOS DEL MES FINAL
			//Recorremos en Busqueda de los domingos dentro del mes Final
			for(var i=1; i<=diaFin;  i++){
				var fechaFin=finMes+"/"+i+"/"+finAnio;
				//Convertir la cadena a formato valido para JS
				fechaFin=new Date(fechaFin);
				if(fechaFin.getDay()==0)
					contDomingosMes++;
				else
					diasLaborales++;		
			}
			
		
		}//Cierre del ELSE if(iniAnio==finAnio)
										
																
		//Pasamos el total de los domingos a la caja de texto correspondiente
		document.getElementById("txt_diasLaborales").value = diasLaborales;
		//Pasamos el total de los domingos a la caja de texto correspondiente
		document.getElementById("txt_domingos").value = contDomingosMes;
		
		
	}//Cierre if(fechaIni<fechaFin)
	else{
		alert("La Fecha de Inicio no Puede ser Mayor a la Fecha de Cierre");
	}
	
}

//Función que permite obtener el numero de mes
function obtenerNumeroMes(mes){		
	var noMes = "";
	//Identificar mes
	switch(mes){
		case "Jan":		noMes="01";		break;
		case "Feb":		noMes="02";		break;
		case "Mar":		noMes="03";		break;			
		case "Apr":		noMes="04";		break;
		case "May":		noMes="05";		break;
		case "Jun":		noMes="06";		break;
		case "Jul":		noMes="07";		break;
		case "Aug":		noMes="08";		break;
		case "Sep":		noMes="09";		break;
		case "Oct":		noMes="10";		break;
		case "Nov":		noMes="11"; 	break;
		case "Dec": 	noMes="12";		break;
	}
	return noMes;
}

//Funcióm que permite saber el numero de dias que contiene cada mes
function diasMes(mes, anio){
	var dias = 0;
	
	//Identificar mes y asignar numero de dias perteneciente a el
	switch(mes){
		case "01": dias = 31;	break;	
		case "02"://Verificamos si el año es bisiesto para enviar el numero de dias correspondiente			
			if(((anio%100!=0)&&(anio%400==0))||((anio%100)&&(anio%4==0)))
				dias = 29;			
			else
				dias = 28;			
		break;
		case "03": dias = 31;	break;	
		case "04": dias = 30;	break;	
		case "05": dias = 31;	break;	
		case "06": dias = 30;	break;	
		case "07": dias = 31;	break;	
		case "08": dias = 31;	break;	
		case "09": dias = 30;	break;	
		case "10": dias = 31;	break;	
		case "11": dias = 30;	break;	
		case "12": dias = 31;	break;			
	}
	
	return dias;
}
*/
//INICIO DE LA FUNCION DE GERENCIA

//Esta funcion suma 30 dias a la fecha seleccionada como Inicial
function sumarDiasMes(){
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=document.getElementById("txt_fechaIni").value.substr(0,2);
	var iniMes=document.getElementById("txt_fechaIni").value.substr(3,2);
	var iniAnio=document.getElementById("txt_fechaIni").value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=document.getElementById("txt_fechaFin").value.substr(0,2);
	var finMes=document.getElementById("txt_fechaFin").value.substr(3,2);
	var finAnio=document.getElementById("txt_fechaFin").value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
		
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);
	
	var fechaIniSeccionada = fechaIni.toString().split(" ");
	var mes=obtenerNumeroMes(fechaIniSeccionada[1]);
	var anio=fechaIniSeccionada[3];	
	
	//Obtenemos las fechas para realizar la resta
	var fechaTotal=fechaFin.getTime()-fechaIni.getTime();
	//Realizamos la resta entre dos fechas para que nos de el numero de dias
	var dias = Math.floor(fechaTotal / (1000 * 60 * 60 * 24)) ;
		
	var diasDelMes = diasMes(mes, anio) - 1;
	
	if(!dias<=(diasDelMes)){
		fechaFin.setTime(fechaIni.getTime()+(86400000*diasDelMes));
		var fechaSeccionada = fechaFin.toString().split(" ");
		//if(parseInt(fechaSeccionada[2]) < 10) { fechaSeccionada[2] = '0' + fechaSeccionada[2]; }
		document.getElementById("txt_fechaFin").value=fechaSeccionada[2]+"/"+obtenerNumeroMes(fechaSeccionada[1])+"/"+fechaSeccionada[3];
	}
}


//Función que permite conocer el numero de domingos entre dos fechas dadas
function calcularDomingos(){
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=document.getElementById("txt_fechaIni").value.substr(0,2);
	var iniMes=document.getElementById("txt_fechaIni").value.substr(3,2);
	var iniAnio=document.getElementById("txt_fechaIni").value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=document.getElementById("txt_fechaFin").value.substr(0,2);
	var finMes=document.getElementById("txt_fechaFin").value.substr(3,2);
	var finAnio=document.getElementById("txt_fechaFin").value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
		
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);
	
	//Si la Fecha de inicio es menor que la de fin, proceder a contar los domingo
	if(fechaIni<fechaFin){
	
		//Seccionamos las fechas y convertimos a String 
		var fechaIniSeccionada = fechaIni.toString().split(" ");
		//Contiene el dia actual de la fecha para tomar como inicio para la busqueda de los domingos
		var diaIni = fechaIniSeccionada[2];
		//Obtenemos el mes Inicial y obtenemos el numero de mes del mismo para obtener despues el numero de dias
		var mesIni = obtenerNumeroMes(fechaIniSeccionada[1]);
		//Obtenemos el año para mandarlo como parametro a la funcion diasMes que obtiene el numero de dias del mes; esto para saber si el año seleccionado es bisiesto
		var anioIni = fechaIniSeccionada[5];	
		//Obtenemos los dias que tiene el mes de inicio
		var diasMesIni = diasMes(mesIni, anioIni);
		
		//Variable que almacenara el total de domingos de los meses seleccionados
		var contDomingosMes = 0;
		var diasLaborales = 0;
		
		//Seccionamos las fechas y convertimos a String
		var fechaFinSeccionada = fechaFin.toString().split(" ");
		//Contiene el dia final para establecerlo como limite de la busqueda
		var diaFin = fechaFinSeccionada[2];
		//Obtenemos el mes inicial y obtenemos el numero de mes 
		var mesFin = obtenerNumeroMes(fechaFinSeccionada[1]);
		//Obtenemos el año de la fecha seccionada
		var anioFin = fechaFinSeccionada[5];	
		
		//OBTENER LA CANTIDAD DE DOMINGOS DEL RANGO DE FECHAS PERTENECIENTE AL MISMO AÑO
		if(iniAnio==finAnio){
			//Verificar si el Rango de fechas esta dentro del mismo mes, contar los domingos entre las fechas dadas
			if(iniMes==finMes){
				if(diaIni<10){
					diaIni="0"+diaIni;
				}
				if(diaFin<10){
					diaFin="0"+diaFin;
				}
				//Recorremos en busqueda de los domingos
				for(var i=diaIni; i<=diaFin;  i++){
					//Crear la fecha para verificar si el dia es Domingo
					var fechaIni=iniMes+"/"+i+"/"+iniAnio;
					//Convertir la cadena a formato de fecha
					fechaIni = new Date(fechaIni);
					//Buscamos el domingo
					if(fechaIni.getDay()==0){
						//Aumentamos el contador
						contDomingosMes++;	
					}
					else
						diasLaborales++;
				}
			}	
			//De lo contrario contar los domingos de los menses existentes entre las fechas dadas
			else{		
				
				//CONTAR LOS DOMINGOS DEL MES INICIAL			
				for(var i=diaIni; i<=diasMesIni; i++){
					var fechaIni=iniMes+"/"+i+"/"+iniAnio;
					//Convertir la cadena a formato de fecha
					fechaIni=new Date(fechaIni);
					//Buscamos el domingo
					if(fechaIni.getDay()==0){
						//Aumentamos el contador
						contDomingosMes++;	
					}
					else
						diasLaborales++;
				}
				
				//CONTAR LOS DOMINGOS DE LOS MESES INTERMEDIOS
				
				//Determinar si los meses seleccionado son Consecutivos, caso contrario encontrar la cantidad de meses que existen entre el mes inicial y el mes final
				var mesInicial = 0;
				//Revisar si el mes inicial es '08' o '09'
				if(mesIni=="08")
					mesInicial = 8;					
				else if(mesIni=="09")
					mesInicial = 9;		
				else
					mesInicial = parseInt(mesIni);
				
				var mesFinal = 0;
				//Revisar si el mes final es '08' o '09'
				if(mesFin=="08")
					mesFinal = 8;					
				else if(mesFin=="09")
					mesFinal = 9;		
				else
					mesFinal = parseInt(mesFin);
				
				//Sumar 1 al mes de inicio para saber si son meses Consecutivos o no
				var noMesIni = mesInicial + 1;
						
				//Verificar que los meses no sean consecutivos para considerar los meses intermedios		
				if(noMesIni!=mesFinal){
					//Obtener la cantidad de meses entre las Fechas seleccionadas, descartando el mes Inicial y el Final
					var cantMeses = (mesFinal - mesInicial) - 1;
					//Obtener el mes actual
					var mesActual = mesInicial + 1;								
									
					//Ciclo para obtener los domingos de cada mes entre las fechas dadas
					for(var j=0;j<cantMeses;j++){															
						
						//Obtener los dias del mes actual
						if(mesActual<10) mesActualStr = "0"+mesActual.toString();
						else mesActualStr = mesActual.toString();
						var diasMesActual = diasMes(mesActualStr, anioIni);										
						
						
						//Recorrer el Mes para obtener los domingos
						for(var i=1; i<=diasMesActual; i++){
							var fechaActual = mesActual.toString()+"/"+i+"/"+iniAnio;
							//Convertir la cadena a formato de fecha
							fechaActual = new Date(fechaActual);
							//Buscamos el domingo
							if(fechaActual.getDay()==0){
								//Aumentamos el contador
								contDomingosMes++;	
							}
							else
								diasLaborales++;
						}//Cierre for(var i=1; i<=diasMesActual; i++)																				
						
						//Incrementar el mes Actual
						mesActual++;
					}//Cierre for(var i=0;i<cantMeses;i++)				
					
				}//Cierre if(noMesIni!=mesFinal)
				
				//CONTAR LOS DOMINGOS DEL MES FINAL
				//Recorremos en Busqueda de los domingos dentro del mes Final
				for(var i=1; i<=diaFin;  i++){
					var fechaFin=finMes+"/"+i+"/"+finAnio;
					//Convertir la cadena a formato valido para JS
					fechaFin=new Date(fechaFin);
					if(fechaFin.getDay()==0)
						contDomingosMes++;	
					else
						diasLaborales++;
				}		
						
			}//Cierre del ELSE de if(iniMes==finMes)	
			
			
		}//Cierre if(iniAnio==finAnio)
		
		//OBTENER LOS DOMINGOS DEL RANGO DE FECHAS CUANDO ABARCA AÑOS DIFERENTES
		else{
			
			//CONTAR DOMINGOS DEL MES INICIAL			
			for(var i=diaIni; i<=diasMesIni; i++){
				var fechaIni=iniMes+"/"+i+"/"+iniAnio;
				//Convertir la cadena a formato de fecha
				fechaIni=new Date(fechaIni);
				//Buscamos el domingo
				if(fechaIni.getDay()==0){
					//Aumentamos el contador
					contDomingosMes++;	
				}
				else
					diasLaborales++;
			}
				
			//CONTAR LOS DOMINGOS DE LOS MESES INTERMEDIOS
			
			//Determinar si los meses seleccionado son Consecutivos, caso contrario encontrar la cantidad de meses que existen entre el mes inicial y el mes final
			var mesInicial = 0;
			//Revisar si el mes inicial es '08' o '09'
			if(mesIni=="08")
				mesInicial = 8;					
			else if(mesIni=="09")
				mesInicial = 9;		
			else
				mesInicial = parseInt(mesIni);
			
			var mesFinal = 0;
			//Revisar si el mes final es '08' o '09'
			if(mesFin=="08")
				mesFinal = 8;					
			else if(mesFin=="09")
				mesFinal = 9;		
			else
				mesFinal = parseInt(mesFin);
			
			//Sumar 1 al año de inicio para saber si el siguiente año es consecutivo o no
			var anioActual = parseInt(anioIni); 
			var noAnioIni =  anioActual + 1;
			var noAnioFin = parseInt(anioFin);
			//Sumar 1 al mes de inicio para saber si el siguiente mes es consecutivo o no
			var noMesIni = mesInicial + 1;
			//Sin el mes inicial es Diciembre, entonces el valor obtenido sera 13 que equivale al primer mes del siguiente año (1 = Enero)
			if(noMesIni==13) noMesIni = 1;
						
					
			//Verificar que los meses no sean consecutivos, así como los años, para considerar los meses intermedios
			if( (noMesIni!=mesFinal) || (noAnioIni!=noAnioFin) ){							
				
				//Obtener la cantidad de meses entre las Fechas seleccionadas, descartando el mes Inicial y el Final
				//Obtener los Meses del Año Inicial
				var cantMeses = 12 - mesInicial;			
				
				//Obtener la diferencia de Años, menos 1 para descartar el Año Final
				var anios = (noAnioFin - anioActual) - 1;
				if(anios>=1)
					cantMeses += (anios*12);				
				
				//Obtener los mes del Año Final
				cantMeses += mesFinal-1;
												
				//Obtener el mes actual
				var mesActual = mesInicial + 1;												
				
				//Ciclo para obtener los domingos de cada mes entre las fechas dadas
				for(var j=0;j<cantMeses;j++){															
					//Verificar el reinicio de meses y el aumento de año
					if(mesActual==13){
						mesActual = 1;
						anioActual++;
					}
					
					//Obtener los dias del mes actual
					if(mesActual<10) mesActualStr = "0"+mesActual.toString();
					else mesActualStr = mesActual.toString();
					var diasMesActual = diasMes(mesActualStr, anioActual);
					
					
					//Recorrer el Mes para obtener los domingos
					for(var i=1; i<=diasMesActual; i++){
						var fechaActual = mesActualStr+"/"+i+"/"+anioActual;
						//Convertir la cadena a formato de fecha
						fechaActual = new Date(fechaActual);
						//Buscamos el domingo
						if(fechaActual.getDay()==0){
							//Aumentamos el contador
							contDomingosMes++;	
						}
						else
							diasLaborales++;
					}//Cierre for(var i=1; i<=diasMesActual; i++)																				
					
					//Incrementar el mes Actual
					mesActual++;
				}//Cierre for(var i=0;i<cantMeses;i++)
				
			}//Cierre if(noMesIni!=mesFinal)
			
			
			//CONTAR LOS DOMINGOS DEL MES FINAL
			//Recorremos en Busqueda de los domingos dentro del mes Final
			for(var i=1; i<=diaFin;  i++){
				var fechaFin=finMes+"/"+i+"/"+finAnio;
				//Convertir la cadena a formato valido para JS
				fechaFin=new Date(fechaFin);
				if(fechaFin.getDay()==0)
					contDomingosMes++;
				else
					diasLaborales++;
			}
		
		}//Cierre del ELSE if(iniAnio==finAnio)
										
		
		//Colocar la cantidad de dias laborales, restar 1 cuando el mes de inicio tenga 32 dias
		if(diasMesIni==31){
			document.getElementById("txt_diasLaborales").value = diasLaborales - 1;
			calcularPptoDiario();
		}
		else{
			document.getElementById("txt_diasLaborales").value = diasLaborales;
			calcularPptoDiario();
		}
						
		//Pasamos el total de los domingos a la caja de texto correspondiente
		document.getElementById("txt_domingos").value = contDomingosMes;
		
		//Retornar verdadero para proceder a validar el rango de fechas en la BD con la funcion verificarRangoValido
		return true;
		
	}//Cierre if(fechaIni<fechaFin)
	else{
		//Notificar al usuario que el rango de fechas no es valido
		alert("La Fecha de Inicio no Puede ser Mayor a la Fecha de Cierre");
		//Reestablecer los campos involucrados con sus valores por Default
		document.getElementById("txt_fechaIni").value = document.getElementById("txt_fechaIni").defaultValue;		
		//Al mandar llamar estas funciones, los campos de Fecha Fin, Cantidad de Domingos y los días Laborales son Calculados.		
		sumarDiasMes(); 
		calcularDomingos();		
	 	verificarRangoValido(document.frm_registrarPresupuesto.txt_fechaIni.value,document.frm_registrarPresupuesto.txt_fechaFin.value,document.frm_registrarPresupuesto.hdn_claveDefinida.value,document.frm_registrarPresupuesto.cmb_destino.value);
		calcularPptoDiario();
								
		//Retornar falso para no validar el rango de fechas en la BD con la funcion verificarRangoValido
		return false;
	}
	
}

//Función que permite obtener el numero de mes
function obtenerNumeroMes(mes){		
	var noMes = "";
	//Identificar mes
	switch(mes){
		case "Jan":		noMes="01";		break;
		case "Feb":		noMes="02";		break;
		case "Mar":		noMes="03";		break;			
		case "Apr":		noMes="04";		break;
		case "May":		noMes="05";		break;
		case "Jun":		noMes="06";		break;
		case "Jul":		noMes="07";		break;
		case "Aug":		noMes="08";		break;
		case "Sep":		noMes="09";		break;
		case "Oct":		noMes="10";		break;
		case "Nov":		noMes="11"; 	break;
		case "Dec": 	noMes="12";		break;
	}
	return noMes;
}

//Funcióm que permite saber el numero de dias que contiene cada mes
function diasMes(mes, anio){
	var dias = 0;
	
	//Identificar mes y asignar numero de dias perteneciente a el
	switch(mes){
		case "01": dias = 31;	break;	
		case "02"://Verificamos si el año es bisiesto para enviar el numero de dias correspondiente			
			if(((anio%100!=0)&&(anio%400==0))||((anio%100)&&(anio%4==0)))
				dias = 29;			
			else
				dias = 28;			
		break;
		case "03": dias = 31;	break;	
		case "04": dias = 30;	break;	
		case "05": dias = 31;	break;	
		case "06": dias = 30;	break;	
		case "07": dias = 31;	break;	
		case "08": dias = 31;	break;	
		case "09": dias = 30;	break;	
		case "10": dias = 31;	break;	
		case "11": dias = 30;	break;	
		case "12": dias = 31;	break;			
	}
	
	return dias;
}

//Esta funcion calcula el presupuesto diario con la cantiad de días laborales y el volumen presupuestado del periodo
function calcularPptoDiario(){
	//Recuperar datos del Formulario para hacer los calculos
	var presupuesto = document.getElementById("txt_volPresupuestado").value;	
	var diasLaborales = document.getElementById("txt_diasLaborales").value;
	var pptoDiario = "";
	
	//Si estan diponibles los datos necesarios para obtener el Ptto Diario, proceder a realizar los calculos
	if(presupuesto!="" && diasLaborales!=""){
		pptoDiario = parseFloat(presupuesto.replace(/,/g,'')) / parseFloat(diasLaborales);
		formatTasaCambio(pptoDiario,'txt_presupuestoDiario');
	}	                
}//Cierre de la funcion calcularPptoDiario()


//FIN DE LA FUNCION DE GERENCIA



// NUEVA FUNCION PARA VALIDAR LA VENTANA SOBRE EL REGISTRO DE LOS COLADOS

//Funcion que permite validar el pop-up al seleccionar el boton agregar detalle en el registro de la produccion
function valFormVerProduccion(frm_produccion){
	var band=1;
	
	if(frm_produccion.hdn_botonSeleccionado.value=="sbt_agregar"){
		//Verificamos que se encuentre lleno el campo del cliente
		if(frm_produccion.txt_cliente.value==""&&band==1){
			alert("Introducir Nombre del Cliente");
			band = 0;
		}
	
		//Verificamos quie se haya ingresao el volumen
		if(frm_produccion.txt_volumen.value==""&&band==1){
			alert("Introducir el Volumen");
			band = 0;
		}
		
		//Verificamos que se encuentre lleno el campo del valor del volumen
		if(frm_produccion.txt_volumen.value==0&&band==1){
			alert("El Volumen  no puede ser igual a (0) cero");
			band = 0;
		}
		
		//Verificamos quie se haya seleccionado si existe la factura
		if(frm_produccion.cmb_factura.value==""&&band==1){
			alert("Seleccionar Si Existe Factura");
			band = 0;
		}
		
		//Verificamos quie si existe la factura Verificamos que se haya ingresado
		if(frm_produccion.txt_factura.value==""&&band==1){
			alert("Introducir Factura");
			band = 0;
		}
		
		//Verificamos que se haya ingresado la descripcion del colado
		if(frm_produccion.txa_colado.value==""&&band==1){
			alert("Introducir Descripción del Colado");
			band = 0;
		}
		
		//Verificamos que se haya ingresado la descripcion del colado
		if(frm_produccion.cmb_tipo.value==""&&band==1){
			alert("Seleccionar Tipo");
			band = 0;
		}
	}
	//Si la variable se encuentra en uno quiere decir que todos los campos contienen un valor
	if(band==1)
		return true;
	else
		return false;	
}


// FIN DE LA FUNCION PARA VALIDAR LA VENTANA SOBRE EL REGISTRO DE LOS COLADOS













//FIN DE LA FUNCION DE GERENCIA

/***************************************************************************************************************************************/
/*******************************************CONSULTAR REPORTES DE  LABORATORIO**********************************************************/
/***************************************************************************************************************************************/

/*Esta funcion se ecnarga de Validar el Formulario donde se selecciona un tipo de prueba y clave para generar el reporte fotográfico en PDF*/
function valFormGenRepPba(frm_reporteResistencias){
	//Variable bandera que permite revisar si la validacion fue exitosa
	var band=1;	
		if (frm_reporteResistencias.cmb_tipoPrueba.value==""){
			alert("Seleccionar el Tipo de Prueba");
			band=0;
		}
		if (frm_reporteResistencias.cmb_idMuestra.value=="" && band==1){
			alert("Seleccionar la Muestra");
			band=0;
		}
	
	if (band==1)
		return true;
	else
		return false;
}

/*Esta funcion pide los datos del Destinatario, Puesto y Empresa del Reporte de Mezclas y Genera dicho reporte en formato PDF*/
function recolectarDatos(){
	//Pedir Datos y guardarlos en campos hidden
	var nombre="";
	while(nombre=="Destinatario..." || nombre==""){
		var nombre = prompt("Introducir Nombre del Destinatario","Destinatario...");
	}
	
	var puesto="";
	while(puesto=="Puesto..." || puesto==""){
		var puesto = prompt("Introducir Puesto","Puesto...");
	}
	
	var empresa="";
	while(empresa=="Empresa..." || empresa==""){	
		var empresa = prompt("Introducir Nombre de la Empresa","Empresa...");	
	}
	
	var idPrueba = document.getElementById("hdn_id").value;
	if(nombre!=null && puesto!=null && empresa!=null)	
		
	//Abrir la nueva ventana
	window.open("../../includes/generadorPDF/reportePruebas.php?id="+idPrueba+"&nombre="+nombre+"&puesto="+puesto+"&empresa="+empresa+"","_blank","top=100, left=100, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no");
	
}


/***************************************************************************************************************************************/
/********************************************************REGISTRAR PRESUPUESTO**********************************************************/
/***************************************************************************************************************************************/


//Esta funcion formatea las Fechas a valores posibles y permitidos
function formatCero(){
	var valor = document.getElementById("txt_diasLaborales").value;
			
	if (valor=="00" || valor=="0" || valor =="000"){
		alert ("El Valor Introducido No es el Correcto");
		document.getElementById("txt_diasLaborales").value = "";
		document.getElementById("txt_presupuestoDiario").value = "";  
		
	}	
	if (valor==""){
		alert ("Agregar un Registro Válido");
		document.getElementById("txt_diasLaborales").value = "";
		document.getElementById("txt_presupuestoDiario").value = "";
	}
}





//Funcion para Evaluar los datoas del formularo Registrar Presupuesto
function valFormRegPresupuesto(frm_registrarPresupuesto){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;
	
	if(frm_registrarPresupuesto.hdn_band.value=="si"){

		//ESTA FECHA ESTABA COMENTADA 
		/*if(!validarFechas(frm_registrarPresupuesto.txt_fechaIni.value,frm_registrarPresupuesto.txt_fechaFin.value))
			band = 0;				
			alert(band);*/
			
			//Se verifica que el presupuesto hayan sido ingresado
		if (frm_registrarPresupuesto.txt_diasLaborales.value==""&&band==1){
			alert ("Seleccionar un Rango de fechas para Obtener los Días Laborales");
			band=0;
		}
			
	
		//Se verifica que el presupuesto hayan sido ingresado
		if (frm_registrarPresupuesto.txt_volPresupuestado.value==""&&band==1){
			alert ("Ingresar el Volumen Presupuestado");
			band=0;
		}
			
		if(band==1){
			if(!validarEntero(frm_registrarPresupuesto.txt_volPresupuestado.value.replace(/,/g,''),"El Volumen del Presupuesto"))
				band = 0;		
		}
		
		//Se verifica que el presupuesto diario hayan sido ingresado
		if (frm_registrarPresupuesto.txt_presupuestoDiario.value==""&&band==1){
			alert ("Ingresar el Volumen Diario");
			band=0;
		}
		
		if(band==1){
			if(!validarEntero(frm_registrarPresupuesto.txt_presupuestoDiario.value.replace(/,/g,''),"El Presupuesto Diario"))
				band = 0;		
		}
		
			if (!frm_registrarPresupuesto.ckb_nuevoDestino.checked && frm_registrarPresupuesto.cmb_destino.value==""  && band==1){
			alert("Seleccionar la Ubicación ó Registrar un Nuevo Destino");
			band=0;
		}
	
		
		if(band==1&&frm_registrarPresupuesto.hdn_fechas.value=="1"){
			alert("Ambas Fechas Se encuentran en Otro Presupuesto \n Seleccionar otras Fechas");	
			band = 0;		
		}
		
		if(band==1&&frm_registrarPresupuesto.hdn_fechas.value=="2"){
			alert("La Fecha de Inicio se encuentra en Otro Presupuesto \n Seleccionar otra Fecha");	
			band = 0;		
		}
		
		if(band==1&&frm_registrarPresupuesto.hdn_fechas.value=="3"){
			alert("La Fecha de Fin se encuentra en Otro Presupuesto \n Seleccionar otra Fecha");	
			band = 0;		
		}
		
		if(band==1&&frm_registrarPresupuesto.hdn_fechas.value=="4"){
			alert("Las Fechas Seleccionadas Abarca un Rango de Fecha ya Registrado\nSeleccionar Otras Fechas");	
			band = 0;		
		}	
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}



/***************************************************************************************************************************************/
/********************************************************MODIFICAR PRESUPUESTO**********************************************************/
/***************************************************************************************************************************************/
//Funcion para Evaluar los datoas del formularo modificar Presupuesto
function valFormBusqPresupuesto(frm_modificarPresupuesto){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;
	
	
	//Se verifica que se haya seleccionado un periodo
	if (frm_modificarPresupuesto.cmb_destino.value==""&&band==1){
		alert ("Seleccionar un Destino");
		band=0;
	}
	
	//Se verifica que se haya seleccionado un periodo
	if (frm_modificarPresupuesto.cmb_periodo.value==""&&band==1){
		alert ("Seleccionar un Periodo");
		band=0;
	}
		
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

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

/***************************************************************************************************************************************************************/
/***************************************************************************BITÁCORA****************************************************************************/
/***************************************************************************************************************************************************************/

//Funcion que permite habilitar el combo y desabilitar la caja de texto destino
function resetearFomulario(){
	document.getElementById("cmb_destino").disabled=false;
	//document.getElementById("txt_nuevoDestino").disabled=true;
	//document.getElementById("txt_nuevoDestino").readOnly=false;
}

/*********************************************************************************************************************
/*********************************VALIDAR SECCION DE PRESUPUESTOS**********************************************************
/**********************************************************************************************************************/



//Esta funcion solicita al usuario el nuevo destino  y desabilita el combo de Destino
function agregarNuevoDestino(ckb_nuevoDestino, txt_nuevoDestino, cmb_destino){
	var band=0;
	var valor = ""; //Variable utilizada para que cuando el nombre del nuevo cliente ingresado ya se encuentra dentro del combo de clientes, se muestre
	var condicion=false
	//Si el checkbox para el nuevo cliente esta seleccionado, pedir el nombre de dicha cliente
	if (ckb_nuevoDestino.checked){
		var destino = prompt("¿Nombre del Nuevo Destino?","Nombre del Destino...");	
		if(destino!=null && destino!="Nombre del Destino..." && destino!=""){
			destino = destino.toUpperCase();			
			if(destino.length<=40){			
				for(i=0; i<document.getElementById("cmb_destino").length; i++){
					//verificar que la nueva opcion no se encuentre dentro de las opciones actuales del combo
					if(document.getElementById("cmb_destino").options[i].text==destino){
						valor = document.getElementById("cmb_destino").options[i].value;
						band = 1;
					}
				}//Cierre for(i=0;i<seccion.length;i++)
				
				if(band==1){ 
					alert("El Destino Ingresado ya Existe ");
					document.getElementById("cmb_destino").value=valor;
					//Dechecar el check de Nuevo Destinno
					document.getElementById("ckb_nuevoDestino").checked = false;
				}//Fin del if(band==1){
					
					if(band==0){
						//Asignar el valor obtenido a la caja de texto que lo mostrara
						document.getElementById(txt_nuevoDestino).value = destino.toUpperCase();
						//Verificar que el combo este definido para poder deshabilitarlo
						if(document.getElementById(cmb_destino)!=null)
							//Deshabilitar el ComboBox para que el usuario no lo pueda modificar
							document.getElementById(cmb_destino).disabled = true;				
					}// Fin del if(band==0){
			}
			
			else{
				alert("El Nombre del Destino Excede el Número de Caracteres Permitidos");
				//Regresar False si se presiona el botón cancelar o se asigna un valor equivocado
				ckb_nuevoDestino.checked = false;
				band=0;
			}
		}
		else{
			//Regresar False si se presiona el botón cancelar o se asigna un valor equivocado
			ckb_nuevoDestino.checked = false;
		}		
	}
	//Si el checkbox para un nuevo destino se deselecciona, borrar el dato escrito en la caja de texto y reactivar el combo de Destino
	else{
		document.getElementById(txt_nuevoDestino).value = "";
		//Verificar que el combo este definido para poder Habilitarlo
		if (document.getElementById(cmb_destino)!=null){
			//Habilitar el ComboBox y el deseleccionar el CheckBox para que el usuario pueda agregar nueva información
			document.getElementById(cmb_destino).disabled = false;
			//Darle un valor vacio por default
			document.getElementById(cmb_destino).value = "";
		}	
	}
	
			
}


/*Esta funcion habilita las cajas o combos que son deshabilitados*/
function restablecePresupuesto(){
	document.getElementById("cmb_destino").disabled = false;
	document.getElementById("txt_nuevoDestino").disabled = false;
}



/**********************************************************************************************************************/

//Funcion que sirve para para ocultar o mostrar el boton segun el valor seleccionado en el combo
function activarBoton(campo){
	//Verificamos el valor del como
	if(campo.options[campo.selectedIndex].text=="COLADO" || campo.options[campo.selectedIndex].text=="COLADOS"){
		//Si el valor es seleccionado como COLADO o COLADOS mostramos el boton
		document.getElementById("btn_detalles").style.visibility="visible";
		//Si el valor es colado el campo de volumen aparecera como solo lectura; ya que este valor se tomara de la ventana pop-up donde se llena el detalle del colado
		document.getElementById("txt_volProducido").value="";
		document.getElementById("txt_volProducido").readOnly=true;
		document.getElementById("hdn_cmbTipo").value=campo.options[campo.selectedIndex].text;
	}
	//De lo contrario se oculta
	else{
		document.getElementById("btn_detalles").style.visibility="hidden";
		//Si el valor es colado el campo de volumen aparecera para ingresar el dato
		document.getElementById("txt_volProducido").readOnly=false;
		//Damos valor vacio a la caja de texto
		document.getElementById("txt_volProducido").value="";
		document.getElementById("hdn_cmbTipo").value=campo.options[campo.selectedIndex].text;
	}
}


//Funcion que sirve para para ocultar o mostrar el boton segun el valor ingresado en el nuevo destino
function activarCampo(campo){
	//Verificamos el valor del como
	if(document.getElementById("txt_nuevoDestino").value=="COLADO" || document.getElementById("txt_nuevoDestino").value=="COLADOS"){
		//Si el valor es seleccionado como COLADO o COLADOS mostramos el boton
		document.getElementById("btn_detalles").style.visibility="visible";
	}
	//De lo contrario se oculta
	else
		document.getElementById("btn_detalles").style.visibility="hidden";
}

//_Funcion para validar el registro de la produccion
function valFormRegistroProduccion(frm_registroProduccion){
	//Variable para verificar que todos los campos se encuentren llenos, si esta cambia de valor aun se encuentran campos vacios
	var band=1;
	
	//obtenerPresupuesto(cmb_destino.value,txt_fecha.value,'txt_volPresupuestado');
	
	//Verificamos que se encuentre lleno el campo del valor producido
	if(frm_registroProduccion.txt_volProducido.value==""){
		alert("Introducir Volumen Producido");
		band = 0;
	}
	//Verificamos que se la leyenda de SIN PRESUPUESTO se encuentra dentro de la Caja de txt_volPresupuestado No pueda realizar un registro de Produccion
	if(frm_registroProduccion.txt_volPresupuestado.value=="Sin Presupuesto"&&band==1){
		alert("No se puede Agregar Registro de Produccion si no Existe un Presupuesto Asociado");
		band = 0;
	}
		
	//Verificamos que se encuentre lleno el campo del valor producido
	if(frm_registroProduccion.txt_volProducido.value==0&&band==1){
		alert("El Volumen Producido no puede ser igual a (0) cero");
		frm_registroProduccion.txt_volProducido.value=="";
		band = 0;
	}
	
	//Si la variable se encuentra en uno quiere decir que todos los campos contienen un valor
	if(band==1)
		return true;
	else
		return false;	
}


//Función que permite enviar datos via Get asi como validar que se encuentrn llenos los mismos
function envioDatosGet(){
		//Ponemos el valor de la caja de texto que queremos enviar a la ventana emergente en la variable volumenPrespuestado
		var volumen = document.getElementById("txt_volProducido").value; 
		//Al llamar a la ventana le enviamos el valor de la variable por el GET
		window.open('verRegistroProduccion.php?vol='+volumen+'','_blank','top=50, left=50, width=900, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
}

//Funcion para habilitar el campo de factura en el registro de la produccion
function habilitarFactura(campo){
	//Verificamos el valor del combo
	if(campo.value=="SI"){
		//Si el valor del combo fue si desactivamos el readonly, lo ponemos como vacio, le ponemos color blanco de fondo y color negro en la escritura
		document.getElementById("txt_factura").readOnly=false;
		document.getElementById("txt_factura").value="SI";
		document.getElementById("txt_factura").style.background="#FFF";
		document.getElementById("txt_factura").style.color="#000";
	}
	else{
		//Si el valor del combo fue NO activamos el readonly, lo ponemos como N/A, le ponemos color gris de fondo y color blanco en la escritura
		document.getElementById("txt_factura").readOnly=true;
		document.getElementById("txt_factura").value="N/A";
		document.getElementById("txt_factura").style.background="#999999";
		document.getElementById("txt_factura").style.color="#FFFFFF";
	}
}

//Funcion que permite validar el pop-up al seleccionar el boton agregar detalle en el registro de la produccion
function valFormRegFormatos(frm_produccion){
	var band=1;
	
	if(frm_produccion.hdn_botonSeleccionado.value=="sbt_agregar"){
		//Verificamos que se encuentre lleno el campo del cliente
		if(frm_produccion.txt_cliente.value==""&&band==1){
			alert("Introducir Nombrel del Cliente");
			band = 0;
		}
	
		//Verificamos quie se haya ingresao el volumen
		if(frm_produccion.txt_volumen.value==""&&band==1){
			alert("Introducir el Volumen");
			band = 0;
		}
		
		//Verificamos que se encuentre lleno el campo del valor del volumen
		if(frm_produccion.txt_volumen.value==0&&band==1){
			alert("El Volumen  no puede ser igual a (0) cero");
			band = 0;
		}
		
		//Verificamos quie se haya seleccionado si existe la factura
		if(frm_produccion.cmb_factura.value==""&&band==1){
			alert("Seleccionar Si Existe Factura");
			band = 0;
		}
		
		//Verificamos quie si existe la factura Verificamos que se haya ingresado
		if(frm_produccion.txt_factura.value==""&&band==1){
			alert("Introducir Factura");
			band = 0;
		}
		
		//Verificamos que se haya ingresado la descripcion del colado
		if(frm_produccion.txa_colado.value==""&&band==1){
			alert("Introducir Descripción del Colado");
			band = 0;
		}
		
		//Verificamos que se haya ingresado la descripcion del colado
		if(frm_produccion.cmb_tipo.value==""&&band==1){
			alert("Seleccionar Tipo");
			band = 0;
		}
	}
	//Si la variable se encuentra en uno quiere decir que todos los campos contienen un valor
	if(band==1)
		return true;
	else
		return false;	
}

/******************************************************************REGISTRAR EQUIPOS*********************************************************/

/*Esta función valida  el check box*/
function activarCampos (campo, noRegistro){
	if (campo.checked){
		document.getElementById("txt_metros" + noRegistro).readOnly=false;
		document.getElementById("txt_observaciones" + noRegistro).readOnly=false;
	}
	else{
		document.getElementById("txt_metros" + noRegistro).value="";
		document.getElementById("txt_metros" + noRegistro).readOnly=true;
		document.getElementById("txt_observaciones" + noRegistro).value="";
		document.getElementById("txt_observaciones" + noRegistro).readOnly=true;
	}
}


//Función que permite desabilitar comboBox y cajas de texto al presionar el botón limpiar en el formulario
function desabilitar(){
	//Verificamos la cantidad de registros esta variable es tomada del op_registrarProduccion
	var cantReg=document.getElementById("hdn_cant").value-1;
	//Recorremos los resultados
	for(i=1;i<=cantReg;i++){
		//Ponemos las cajas de texto como disable
		document.getElementById("txt_metros" + i).readOnly=true;
		document.getElementById("txt_observaciones" + i).readOnly=true;
	}
}


/*Esta función valida los datos en el formulario Produccion por Equipo*/
function valFormEquipos(frm_registrarEquipos){	
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var res = 1;
	//Variable para manejar el mensaje de validación satisfactoria
	var msg = 0;
	//Variable para saber si al menos un equipo fue seleccionado
	var status = 0;
	//Variable para controlar la cantidad de registros
	var ctrl= 1;
	//Variable para almacenar la cantidad de registros
	var cantidad = document.getElementById("hdn_cant").value-1;
	//Variables que almacenara el nombre de cada CheckBox Seleccionado y las cajas de texto de cantidad y aplicación relacionada a el
	var idCheckBox = "";
	idTxtMetros = "";
	idTxtObserv = "";
	var idHdnNombre = "";
	
	while(ctrl<=cantidad){		
		//Crear el id del CheckBox que se quiere verificar
		idCheckBox="ckb_equipo"+ctrl.toString();
		
		//Verificar que la cantidad y la aplicación del Checkbox seleccionado no esten vacias
		if(document.getElementById(idCheckBox).checked){
			status = 1;
			//Crear el id del Caja de Texto Oculta de Nombre
			idHdnNombre = "hdn_nombre"+ctrl.toString();
			var nombre = document.getElementById(idHdnNombre).value;
			//Crear el id de la Caja de Texto Metros
			idTxtMetros = "txt_metros"+ctrl.toString();
			//Crear el id de la Caja de Texto Observaciones
			idTxtObserv = "txt_observaciones"+ctrl.toString();
		
			
			if(document.getElementById(idTxtMetros).value==""){				
				alert("Ingresar Metros para el Equipo "+ nombre);
				msg = 1;
			}
			else{
				//Validar que la cantidad sea un numero entero valido
				if(validarEntero(document.getElementById(idTxtMetros).value.replace(/,/g,''),"Cantidad de Metros para "+nombre)){
					//Validar que se hayan ingresado las observaciones
					if(document.getElementById(idTxtObserv).value==""){
						msg = 1;
						alert("Ingresar Observaciones: "+nombre);
					}
				}
				else{
					msg = 1;
				}
			}
		}
		ctrl++;
	}//Fin del While	
	
	
	//Verificar que al menos un equipo haya sido seleccionado, si la variable status vale 1, quiere decir que al menos uno fue seleccionado
	if(status==1){
		//Si hubo algun mensaje de que falta ingresar un datos, no se cumplio con el proceso de validacion 
		if(msg==1)
			res = 0;
	}
	else{
		alert("Seleccionar al Menos un Equipo");
		res = 0;
	}
	if(res==1)
		return true;
	else
		return false;		
}

/******************************************************************REGISTRAR SEGURIDAD*********************************************************/


function valFormSeguridad(frm_registrarSeguridad){
	var band=1;
	if(frm_registrarSeguridad.hdn_botonSeleccionado.value=="sbt_agregar"){
		//Verificamos que se haya seleccionado el tipo
		if(frm_registrarSeguridad.cmb_tipo.value==""){
			alert("Seleccionar Tipo");
			band = 0;
		}
	
		//Verificamos quie se hayan ingresado las observaciones
		if(frm_registrarSeguridad.txa_observaciones.value==""&&band==1){
			alert("Introducir Observaciones");
			band = 0;
		}
	}
	//Si la variable se encuentra en uno quiere decir que todos los campos contienen un valor
	if(band==1)
		return true;
	else
		return false;	
}


/*************************************************************************************************************************************************/
/********************************************************MODIFICAR PRODUCCIÓN*********************************************************************/
/*************************************************************************************************************************************************/



/***********************************************************MODIFICAR PRODUCCIÓN******************************************************************/

//Funcion que sirve para para ocultar o mostrar el boton segun el valor seleccionado en el combo
function activarBotonModificar(campo){
	//Verificamos el valor del como
	if(campo.options[campo.selectedIndex].text=="COLADO" || campo.options[campo.selectedIndex].text=="COLADOS"){
		//Si el valor es seleccionado como COLADO o COLADOS mostramos el boton
		document.getElementById("btn_detalles").style.visibility="visible";
		//Si el valor es colado el campo de volumen aparecera como solo lectura; ya que este valor se tomara de la ventana pop-up donde se llena el detalle del colado
		document.getElementById("txt_volProducido").readOnly=true;
		document.getElementById("txt_volProducido").value="";
		document.getElementById("hdn_cmbTipo").value=campo.options[campo.selectedIndex].text;
	}
	//De lo contrario se oculta
	else{
		document.getElementById("btn_detalles").style.visibility="hidden";
		//Si el valor es colado el campo de volumen aparecera para ingresar el dato
		document.getElementById("txt_volProducido").readOnly=false;
		document.getElementById("txt_volProducido").value="";
		//Damos valor vacio a la caja de texto
		document.getElementById("hdn_cmbTipo").value=campo.options[campo.selectedIndex].text;
	}
}
//Funcion para validar el formulario inicial de modificar produccion
function valFormModificarProduccionUno(frm_modificarProduccion){
	var band=1;
	//Verificar que un elemeto haya sido seleccionado 
		if(frm_modificarProduccion.cmb_destino.value==""){
			alert("Seleccionar el Destino a Consultar");
			band = 0;
		}
	//Verificar que un elemeto haya sido seleccionado 
		if(frm_modificarProduccion.cmb_periodo.value==""&& band==1){
			alert("Seleccionar el Periodo a Consultar");
			band = 0;
		}
	//Si la variable se encuentra en uno quiere decir que todos los campos contienen un valor
	if(band==1)
		return true;
	else
		return false;
}

//Funcion para validar el formulario inicial de modificar produccion
function valFormModificarProduccionFecha(frm_modificarFecha){
	var band=1;
	//Verificar que un elemeto haya sido seleccionado 
		if(frm_modificarFecha.cmb_destino.value==""){
			alert("Seleccionar el Destino a Consultar");
			band = 0;
		}
	//Si la variable se encuentra en uno quiere decir que todos los campos contienen un valor
	if(band==1)
		return true;
	else
		return false;
}

/*Esta funcion se ecnarga de Validar el Formulario donde se selecciona el registro*/
function valFormModificarProduccion(frm_modificarProduccion2){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
		//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
		if(frm_modificarProduccion2.rdb_produccion.length==undefined && !frm_modificarProduccion2.rdb_produccion.checked){
			alert("Seleccionar el Registro a Eliminar/Modificar");
			res = 0;
		}
		//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
		if(frm_modificarProduccion2.rdb_produccion.length>=2){
			//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
			res = 0; 
			//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
			for(i=0;i<frm_modificarProduccion2.rdb_produccion.length;i++){
				if(frm_modificarProduccion2.rdb_produccion[i].checked)
					res = 1;
			}
			if(res==0)
				alert("Seleccionar el Registro a Eliminar/Modificar");			
		}	
	if(res==1)
		return true;
	else
		return false;
}


function envioDatosGetModificar(){
	//Ponemos el valor de la caja de texto que queremos enviar a la ventana emergente en la variable volumenPrespuestado
		var volumenPresupuestado = document.getElementById("txt_volProducido").value;
		var concepto = document.getElementById("hdn_concepto").value;	
		//Ponemos el valor de la caja de texto que queremos enviar a la ventana emergente en la variable volumenPrespuestado
		var fecha = document.getElementById("txt_fecha").value; 
		var destino =document.getElementById("hdn_destino").value;
//Al llamar a la ventana le enviamos el valor de la variable por el GET
		window.open('verModificarRegistroProduccion.php?volumen='+volumenPresupuestado+'&fecha='+fecha+'&concepto='+concepto+'&destino='+destino+'','_blank','top=50, left=50, width=900, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
}

//_Funcion para validar el registro de la produccion
function valFormModificarProduccion1(frm_modificarProduccion){
	//Variable para verificar que todos los campos se encuentren llenos, si esta cambia de valor aun se encuentran campos vacios
	var band=1;
	if(frm_modificarProduccion.hdn_botonSeleccionado.value=="sbt_guardarProduccion"){
		//Verificamos que se encuentre lleno el campo del valor producido
		if(frm_modificarProduccion.txt_volProducido.value==""){
			alert("Introducir Volumen Producido");
			band = 0;
		}
	
		//Verificamos que se encuentre lleno el campo del valor producido
		if(frm_modificarProduccion.txt_volProducido.value==0){
			alert("El Volumen Producido no puede ser igual a (0) cero");
			band = 0;
		}

	}
	//Si la variable se encuentra en uno quiere decir que todos los campos contienen un valor
	if(band==1)
		return true;
	else
		return false;	
}

/*Esta función valida  el check box*/
function activarCamposMod(campo, noRegistro){
	if (campo.checked){
		document.getElementById("txt_metros" + noRegistro).readOnly=false;
		document.getElementById("txt_observaciones" + noRegistro).readOnly=false;
	}
	else{
		document.getElementById("txt_metros" + noRegistro).readOnly=true;
		document.getElementById("txt_observaciones" + noRegistro).readOnly=true;
	}
}


function valFormSeguridadMod(frm_modificarSeguridad){
	var band=1;
	if(frm_modificarSeguridad.hdn_botonSeleccionado.value!="sbt_cancelarSeguridad"){
		//Verificamos que se haya seleccionado el tipo
		if(frm_modificarSeguridad.cmb_tipo.value==""){
			alert("Seleccionar Tipo");
			band = 0;
		}
	
		//Verificamos quie se hayan ingresado las observaciones
		if(frm_modificarSeguridad.txa_observaciones.value==""&&band==1){
			alert("Introducir Observaciones");
			band = 0;
		}
	}
	//Si la variable se encuentra en uno quiere decir que todos los campos contienen un valor
	if(band==1)
		return true;
	else
		return false;	
}



/*Esta funcion se ecnarga de Validar el Formulario donde se selecciona el plano para borrar*/
function valFormModificarSeguridad(frm_modificarSeguridad2){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	if(frm_modificarSeguridad2.hdn_botonSeleccionado.value=="sbt_eliminarSeguridad"){
		//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
		if(frm_modificarSeguridad2.rdb_seguridad.length==undefined && !frm_modificarSeguridad2.rdb_seguridad.checked){
			alert("Seleccionar el Registro a Eliminar");
			res = 0;
		}
		//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
		if(frm_modificarSeguridad2.rdb_seguridad.length>=2){
			//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
			res = 0; 
			//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
			for(i=0;i<frm_modificarSeguridad2.rdb_seguridad.length;i++){
				if(frm_modificarSeguridad2.rdb_seguridad[i].checked)
					res = 1;
			}
			if(res==0)
				alert("Seleccionar el Registro a Eliminar");			
		}	
	}
	if(res==1)
		return true;
	else
		return false;
}

function valFormSeguridadMod(frm_modificarSeguridad){
	var band=1;
	if(frm_modificarSeguridad.hdn_botonSeleccionado.value=="sbt_agregarSeg"){
		//Verificamos que se haya seleccionado el tipo
		if(frm_modificarSeguridad.cmb_tipo.value==""){
			alert("Seleccionar Tipo");
			band = 0;
		}
	
		//Verificamos quie se hayan ingresado las observaciones
		if(frm_modificarSeguridad.txa_observaciones.value==""&&band==1){
			alert("Introducir Observaciones");
			band = 0;
		}
	}
	//Si la variable se encuentra en uno quiere decir que todos los campos contienen un valor
	if(band==1)
		return true;
	else
		return false;	
}


/*************************************************************************************************************************************************/
/********************************************************REPORTE POR PERIODO**********************************************************************/
/*************************************************************************************************************************************************/
/*Esta funcion revisa que sea seleccionado un periodo para ver el reporte*/
function valFormGenerarRepoMes(frm_generarReporteMes){
	//Si el valor de la variable se mantiene en 1, e proceso de validación será satisfactorio
	var band = 1;
	
	if(frm_generarReporteMes.cmb_periodo.value==""){
		alert("Seleccionar un Periodo para Generar Reporte");
		band = 0;
	}
		
	if(band==1)
		return true;
	else
		return false
}

/*************************************************************************************************************************************************/
/********************************************************CAMBIAR DE USUARIO**********************************************************************/
/*************************************************************************************************************************************************/

//Funcion que permite cambiar de usuario cuando el usuario sea gerencia; ya que el adminsitrador puede accesar a todas las paginas de produccion
function cambiarUsuario(user, modulo){
	var ruta="";
	if(user=="AdminGT"&&modulo=="Produccion"){
		ruta="../../pages/ger/inicio_gerencia.php";
	}
	if(user=="AdminGT"&&modulo=="Gerencia"){
		ruta="../../pages/pro/inicio_produccion.php";
	}
	//Abrir la nueva ventana
//window.open("../../pages/ger/inicio_gerencia.php","_self"," status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no");
window.location.href=ruta;
}


/*************************************************************************************************************************************************/
/*******************************************************VISTA EN VENTANA EMERGENTE****************************************************************/
/*************************************************************************************************************************************************/
function restablecerVentEmg(){
	//Campo de Factura
	document.getElementById("txt_factura").readOnly=true;
	document.getElementById("txt_factura").style.background="999";
	document.getElementById("txt_factura").style.color="FFF";
}