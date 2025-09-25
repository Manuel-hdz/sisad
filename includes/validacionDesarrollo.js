/**
  * Nombre del Módulo: Desarrollo                                               
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 17/Junio/2011                                      			
  * Descripción: Este archivo contiene funciones para validar los diferentes formularios del Módulo Desarrollo
  */
/*****************************************************************************************************************************************************************/
/************************************************************VALIDAR CARACTERES***********************************************************************************/
/*****************************************************************************************************************************************************************/
/*Esta función se encarga de que el usuario no pueda ingresar caracteres invalidos en los campos de los diferentes formulario del Módulo de Mantenimiento*/
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


/********************************************************************************************************************************************************************/
/*****************************************************************FUNCIONES GENERALES********************************************************************************/
/********************************************************************************************************************************************************************/
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


/*Esta función verifica que el dato proporcionado sea un numero valido */
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

//Esta funcion valida que una imagen sea valida, tomando en cuenta el tamaño de 1 Kb hasta 10Mb
function validarImagen(campo,bandera) { 
	//Creamos un elemento DIV
	div = document.createElement("DIV"); 
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
	setTimeout("if (tam>0&&tam<10240000){ document.getElementById('"+bandera+"').value='si'; return true}; else {alert('Introducir una Imágen Válida'); document.getElementById('"+bandera+"').value='no'; return false;}",900);
}


/*Esta funcion valida que las fechas elegidas sean correctas*/
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


/*Esta funcion solicita la confirmación del usuario antes de salir de la pagina*/
function confirmarSalida(pagina){
	if(confirm("¿Estas Seguro que Quieres Salir?\nToda la información no Guardada se Perderá"))
		location.href = pagina;	
}

/*Esta funcion solicita la confirmación del usuario antes de salir de la pagina*/
function confirmarRegreso(pagina){
	if(confirm("¿Estas Seguro que Quieres Regresar?\nToda la información no Guardada se Perderá"))
		location.href = pagina;	
}

/*Esta funcion valida que el valor de un porcentaje no exceda el 100%*/
function validarPorcentaje(campo){
	if(campo.value>100){
		alert("El Porcentaje Introducido no puede Exceder el 100%");
		campo.value="";
	}
}

/***************************************************************************************************************************************/
/****************************************COMIENZAN LAS FUNCIONES DE CADA FORMULARIO*****************************************************/
/***************************************************************************************************************************************/

/***************************************************************************************************************************************/
/*************************************************************CATALOGO SUELDOS**********************************************************/
/***************************************************************************************************************************************/

/*Esta funcion habilita o deshabilita campos de incentivos segun aplique el caso*/
function activarDesactivarCampos(cmb_area){
	cmb_area=cmb_area.value;
	if (cmb_area=="JUMBO" || cmb_area=="VOLADURAS" || cmb_area=="SCOOP" || cmb_area==""){
		document.getElementById("txt_porcActividad").readOnly=false;
		document.getElementById("txt_porcMetro").readOnly=false;
	}
	else{
		document.getElementById("txt_porcActividad").readOnly=true;
		document.getElementById("txt_porcMetro").readOnly=true;
		document.getElementById("txt_porcActividad").value="";
		document.getElementById("txt_porcMetro").value="";
	}
}

/*Esta función valida que sea selecionada una Nueva Area*/
function agregarNuevaArea(){
	if (document.getElementById("ckb_nuevaArea").checked){
		//Restablecer el formulario por completo
		frm_catalogoSueldos.reset();
		document.getElementById("ckb_nuevaArea").checked=true;
		document.getElementById("cmb_puestos").length = 1;
		document.getElementById("txt_porcActividad").readOnly=false;
		document.getElementById("txt_porcMetro").readOnly=false;
		//Linea que muestra un mensaje donde guardar la nueva Area
		var linea = prompt("¿Nombre de la Nueva Área del Trabajador?","Nombre del Área...");
		//Verificar si el dato introducido es valido
		if(linea!=null && linea!="Nombre del Área..." && linea!="" && linea.length<=20){
			linea=linea.toUpperCase();
			//Variable para revisar los caracteres de error
			var error=0;
			//Recorrer el dato ingresado buscando caracteres prohibidos
			for(i=0;i<linea.length;i++){
				//Igualamos el valor de seccion a car para su facil manejo
				car = linea.charAt(i);
				if(car=='%'||car=='&'||car=='"'){
					error=1;
					break;
				}
			}//Cierre for(i=0;i<linea.length;i++)
			if(error==0){
				if (linea!="JUMBO" || linea!="VOLADURAS" || linea!="SCOOP"){
					document.getElementById("txt_porcActividad").readOnly=true;
					document.getElementById("txt_porcMetro").readOnly=true;
				}
				//Variable que permite verificar si existe un dato o no en el combo de referencia
				var existe=0;
				for(i=0; i<document.getElementById("cmb_area").length; i++){
					//verificar que la nueva opcion no se encuentre dentro de las opciones actuales del combo
					if(document.getElementById("cmb_area").options[i].value==linea)
						existe = 1;
				} //FIN for(i=0; i<comboBox.length; i++)
				if (existe==1){
					alert("El Área ya existe");
					document.getElementById("cmb_area").value=linea;
					if (linea=="JUMBO" || linea=="VOLADURAS" || linea=="SCOOP"){
						document.getElementById("txt_porcActividad").readOnly=false;
						document.getElementById("txt_porcMetro").readOnly=false;
					}
					//Dechecar el check de Nuevo Puesto
					document.getElementById("ckb_nuevaArea").checked = false;
					cargarCombo(linea,'bd_desarrollo','catalogo_salarios','puesto','area','cmb_puestos','Puestos','');
				}
				else{			
					//Asignar el valor obtenido a la caja de texto que lo mostrara
					document.getElementById("txt_nuevaArea").value = linea;
					//Deshabilitar el ComboBox para que el usuario no los pueda modificar
					document.getElementById("cmb_area").disabled = true;
				}
				//Si el area existe, no continuar con el proceso
				if(existe!=1){
					//Deshabilitar el ComboBox para que el usuario no los pueda modificar
					document.getElementById("cmb_puestos").disabled = true;
					do{
						//Checar el check del nuevo Puesto
						document.getElementById("ckb_nuevoPuesto").checked=true;
						band=1;
						//Recoger el nombre del puesto para el área
						var puesto = prompt("¿Nombre del Nuevo Puesto del Trabajador?","Nombre del Puesto...");
						if(puesto!=null && puesto!="Nombre del Puesto..." && puesto!="" && puesto.length<=30){
							puesto=puesto.toUpperCase();
							//Recorrer el dato ingresado buscando caracteres prohibidos
							for(i=0;i<puesto.length;i++){
								//Igualamos el valor de seccion a car para su facil manejo
								car = puesto.charAt(i);
								if(car=='%'||car=='&'||car=='"'){
									error=1;
									break;
								}
							}//Cierre for(i=0;i<linea.length;i++)
							if(error==0){
								//Variable que permite verificar si existe un dato o no en el combo de referencia
								existe=0;
								for(i=0; i<document.getElementById("cmb_puestos").length; i++){
									//verificar que la nueva opcion no se encuentre dentro de las opciones actuales del combo
									if(document.getElementById("cmb_puestos").options[i].value==puesto)
										existe = 1;
								} //FIN for(i=0; i<comboBox.length; i++)
								if (existe==1){
									alert("El Puesto ya existe");
									document.getElementById("cmb_puestos").value=puesto;
									document.getElementById("cmb_puestos").disabled=false;
									//Dechecar el check de Nuevo Puesto
									document.getElementById("ckb_nuevoPuesto").checked = false;
									obtenerSueldo(document.getElementById("cmb_puestos"),document.getElementById("cmb_area"));
									}
								else{
									//Asignar el valor obtenido a la caja de texto que lo mostrara
									document.getElementById("txt_nuevoPuesto").value = puesto;
									//Deshabilitar el ComboBox para que el usuario no los pueda modificar
									document.getElementById("cmb_puestos").disabled = true;
								}
							}
							else{
								alert("El Dato "+puesto+" Ingresado No Es Válido");
								document.getElementById("ckb_nuevoPuesto").checked = false;
							}
						}
						else{
							if(puesto!=null && puesto.length>30)
								alert("La Longitud del Puesto, No puede ser Mayor a 30 Caracteres");
							else{
								alert("Dato Ingresado No Válido");
								if(puesto==null){
									document.getElementById("ckb_nuevoPuesto").checked = false;
									document.getElementById("ckb_nuevaArea").checked = false;
									document.getElementById("cmb_puestos").disabled=false;
									document.getElementById("cmb_area").disabled = false;
									document.getElementById("txt_nuevaArea").value = "";
									break;
								}
							}
							document.getElementById("ckb_nuevoPuesto").checked = false;
							band=0;
						}
					}while(band==0);
				}
			}else{
				alert("El Dato "+linea+" Ingresado No Es Válido");
				document.getElementById("ckb_nuevaArea").checked = false;
			}
		}
		else{
			if(linea!=null && linea.length>20)
				alert("La Longitud del Área, No puede ser Mayor a 20 Caracteres");
			else
				alert("Dato Ingresado No Válido");
			document.getElementById("ckb_nuevaArea").checked = false;
		}
	}
	else{
		//Asignar el valor obtenido a la caja de texto que lo mostrara
		document.getElementById("txt_nuevaArea").value = "";
		//Deshabilitar el ComboBox y el CheckBox para que el usuario no los pueda modificar 			
		document.getElementById("cmb_area").disabled = false;
		//Asignar el valor obtenido a la caja de texto que lo mostrara
		document.getElementById("txt_nuevoPuesto").value = "";
		//Deshabilitar el ComboBox y el CheckBox para que el usuario no los pueda modificar 			
		document.getElementById("cmb_puestos").disabled = false;
		//Deshabilitar el check de Nuevo Puesto
		document.getElementById("ckb_nuevoPuesto").checked = false;
	}
	//activarDesactivarCampos(document.getElementById("cmb_area"));
}

/*Esta función valida que sea selecionada un Nuevo Puesto*/
function agregarNuevoPuesto(){
	if (document.getElementById("ckb_nuevoPuesto").checked){
		if (document.getElementById("cmb_area").value!="" || document.getElementById("txt_nuevaArea").value != ""){
			//Recoger el nombre del puesto para el área
			var puesto = prompt("¿Nombre del Nuevo Puesto del Trabajador?","Nombre del Puesto...");
			if(puesto!=null && puesto!="Nombre del Puesto..." && puesto!="" && puesto.length<=30){
				puesto=puesto.toUpperCase();
				//Variable para revisar los caracteres de error
				var error=0;
				//Recorrer el dato ingresado buscando caracteres prohibidos
				for(i=0;i<puesto.length;i++){
					//Igualamos el valor de seccion a car para su facil manejo
					car = puesto.charAt(i);
					if(car=='%'||car=='&'||car=='"'){
						error=1;
						break;
					}
				}//Cierre for(i=0;i<linea.length;i++)
				if(error==0){
					//Variable que permite verificar si existe un dato o no en el combo de referencia
					var existe=0;
					for(i=0; i<document.getElementById("cmb_puestos").length; i++){
						//verificar que la nueva opcion no se encuentre dentro de las opciones actuales del combo
						if(document.getElementById("cmb_puestos").options[i].value==puesto)
							existe = 1;
					} //FIN for(i=0; i<comboBox.length; i++)
					if (existe==1){
						alert("El Puesto ya existe");
						document.getElementById("cmb_puestos").value=puesto;
						//Dechecar el check de Nuevo Puesto
						document.getElementById("ckb_nuevoPuesto").checked = false;
						obtenerSueldo(document.getElementById("cmb_puestos"),document.getElementById("cmb_area"));
						}
					else{
						//Asignar el valor obtenido a la caja de texto que lo mostrara
						document.getElementById("txt_nuevoPuesto").value = puesto;
						//Deshabilitar el ComboBox para que el usuario no los pueda modificar
						document.getElementById("cmb_puestos").disabled = true;
					}
				}
				else{
					alert("El Dato "+puesto+" Ingresado No Es Válido");
					document.getElementById("ckb_nuevoPuesto").checked = false;
				}
			}
			else{
				if(puesto!=null && puesto.length>30)
					alert("La Longitud del Puesto, No puede ser Mayor a 30 Caracteres");
				else
					alert("Dato Ingresado No Válido");
				document.getElementById("ckb_nuevoPuesto").checked = false;
			}
		}
		else{
			alert("Debe Seleccionar o Ingresar un Área Primero");
			//Deshabilitar el check de Nuevo Puesto
			document.getElementById("ckb_nuevoPuesto").checked = false;
		}
	}
	else{
		//Asignar el valor obtenido a la caja de texto que lo mostrara
		document.getElementById("txt_nuevoPuesto").value = "";
		//Deshabilitar el ComboBox y el CheckBox para que el usuario no los pueda modificar 			
		document.getElementById("cmb_puestos").disabled = false;
	}
}

/*Esta funcion habilita las cajas o combos que son deshabilitados*/
function restablece(){
	document.getElementById("cmb_area").disabled = false;
	document.getElementById("cmb_puestos").disabled = false;
	document.getElementById("txt_porcActividad").readOnly = false;
	document.getElementById("txt_porcMetro").readOnly = false;
	document.getElementById("cmb_puestos").length = 1;
}

/*Esta función se encarga de validar el formulario de actualizar Sueldos frm_catalogoSueldos*/
function valFormSueldos(frm_catalogoSueldos){
	//Variable bandera de control de validacion
	band=1;
	if (frm_catalogoSueldos.cmb_area.value=="" && frm_catalogoSueldos.txt_nuevaArea.value==""){
		alert("Seleccionar o Ingresar un Área");
		band=0;
	}
	if (frm_catalogoSueldos.cmb_puestos.value=="" && frm_catalogoSueldos.txt_nuevoPuesto.value=="" && band==1){
		alert("Seleccionar o Ingresar un Puesto");
		band=0;
	}
	if (frm_catalogoSueldos.cmb_area.value=="JUMBO" || frm_catalogoSueldos.cmb_area.value=="VOLADURAS" || frm_catalogoSueldos.cmb_area.value=="SCOOP" && band==1){
		if (frm_catalogoSueldos.txt_sueldoBase.value=="" && band==1){
			alert("Ingresar Cantidad del Sueldo Base");
			band=0;
		}
		if (frm_catalogoSueldos.txt_porcActividad.value=="" || frm_catalogoSueldos.txt_porcActividad.value==0 && band==1){
			alert("Ingresar Porcentaje Correspondiente por Bono de Actividades");
			band=0;
		}
		if (frm_catalogoSueldos.txt_porcMetro.value=="" || frm_catalogoSueldos.txt_porcMetro.value==0 && band==1){
			alert("Ingresar Porcentaje Correspondiente por Bono de Metros");
			band=0;
		}
	}
	if (frm_catalogoSueldos.cmb_area.value!="JUMBO" && frm_catalogoSueldos.cmb_area.value!="VOLADURAS" && frm_catalogoSueldos.cmb_area.value!="SCOOP" && band==1){
		if (frm_catalogoSueldos.txt_sueldoBase.value=="" || frm_catalogoSueldos.txt_sueldoBase.value=="0.00" && band==1){
			alert("Ingresar Cantidad del Sueldo Base");
			band=0;
		}
	}
	if (band==1)
		return true;
	else
		return false;
}

/***************************************************************************************************************************************/
/**********************************************************CATALOGO INCENTIVOS**********************************************************/
/***************************************************************************************************************************************/

/*Esta función valida que sea selecionada un Nuevo Estandar*/
function agregarNuevoEstandar(){
	if (document.getElementById("ckb_nuevoEstandar").checked){
		if (document.getElementById("cmb_area").value!=""){
			//Recoger el nombre del puesto para el área
			var estandar = prompt("¿Nombre del Nuevo Estándar del Área?","Nombre del Estándar...");
			if(estandar!=null && estandar!="Nombre del Estándar..." && estandar!="" && estandar.length==1){
				estandar=estandar.toUpperCase();
				//Variable que permite verificar si existe un dato o no en el combo de referencia
				var existe=0;
				for(i=0; i<document.getElementById("cmb_estandar").length; i++){
					//verificar que la nueva opcion no se encuentre dentro de las opciones actuales del combo
					if(document.getElementById("cmb_estandar").options[i].value==estandar)
						existe = 1;
				} //FIN for(i=0; i<comboBox.length; i++)
				if (existe==1){
					alert("El Estándar "+estandar+" ya existe");
					document.getElementById("cmb_estandar").value=estandar;
					//Dechecar el check de Nuevo Puesto
					document.getElementById("ckb_nuevoEstandar").checked = false;
					}
				else{
					//Asignar el valor obtenido a la caja de texto que lo mostrara
					document.getElementById("txt_nuevoEstandar").value = estandar;
					//Deshabilitar el ComboBox para que el usuario no los pueda modificar
					document.getElementById("cmb_estandar").disabled = true;
					//Restablecer los campos caso de tener datos
					document.getElementById("cmb_estandar").value ="";
					//Dibujar Boton de agregar actividades al estandar
					document.getElementById("boton").innerHTML="<input type=\"submit\" class=\"botones_largos\" name=\"sbt_agregar\" id=\"sbt_agregar\" value=\"Agregar Actividad\" title=\"Agregar Actividades al Estándar\" onmouseover=\"window.status='';return true;\"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					//Deshabilitar el Botón de consultar
					document.getElementById("sbt_consultar").disabled = true;
				}
			}
			else{
				if(estandar!=null && estandar.length>1)
					alert("La Longitud del Estándar, Debe ser de 1 Caracter");
				else
					alert("Dato Ingresado No Válido");
				document.getElementById("ckb_nuevoEstandar").checked = false;
			}
		}
		else{
			alert("Debe Seleccionar un Área Primero");
			//Deshabilitar el check de Nuevo Puesto
			document.getElementById("ckb_nuevoEstandar").checked = false;
		}
	}
	else{
		//Asignar el valor obtenido a la caja de texto que lo mostrara
		document.getElementById("txt_nuevoEstandar").value = "";
		//Deshabilitar el ComboBox y el CheckBox para que el usuario no los pueda modificar 			
		document.getElementById("cmb_estandar").disabled = false;
		//Rehabilitar el boton de consultar
		document.getElementById("sbt_consultar").disabled = false;
		//Quitar el boton de Agregar
		document.getElementById("boton").innerHTML = "";
	}
}

/*Esta funcion habilita las cajas o combos que son deshabilitados*/
function restableceIncentivos(){
	document.getElementById("cmb_area").disabled = false;
	document.getElementById("cmb_estandar").disabled = false;
	document.getElementById("sbt_consultar").disabled = false;
	document.getElementById("boton").innerHTML = "";
}


/*Esta función se encarga de validar el formulario de actualizar Incentivos frm_catalogoIncentivos*/
function valFormIncentivos(frm_catalogoSueldos){
	//Variable bandera de control de validacion
	band=1;
	if (frm_catalogoSueldos.cmb_area.value==""){
		alert("Seleccionar un Área");
		band=0;
	}
	if (frm_catalogoSueldos.cmb_estandar.value=="" && frm_catalogoSueldos.txt_nuevoEstandar.value=="" && band==1){
		alert("Seleccionar o Ingresar un Estándar");
		band=0;
	}
	if (band==1)
		return true;
	else
		return false;
}

/*Esta funcion valida el formulario frm_catalogoActividades*/
function valFormActividades(frm_catalogoActividades){
	//Recuperar la accion del boton seleccionado
	var accion=frm_catalogoActividades.hdn_accion.value;
	//Si el boton es agregar, evitar toda la validacion
	if (accion=="Agregar")
		return true;
		
	//Variable bandera de control de validacion
	band=1;
	
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_catalogoActividades.rdb_actividad.length==undefined && !frm_catalogoActividades.rdb_actividad.checked){
		alert("Seleccionar la Actividad a "+accion);
		band = 0;
	}
	//Confirmar que se desea borrar el Registro seleccionado teniendo en cuenta que es el Radiobutton tiene solo una opcion
	if(frm_catalogoActividades.rdb_actividad.length==undefined && accion=="Eliminar" && band==1){
		if(!confirm("De Borrar la Actividad Seleccionada ya no se podrá Recuperar. \nPresione Aceptar para Borrarla Definitivamente"))
			band = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_catalogoActividades.rdb_actividad.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		band = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_catalogoActividades.rdb_actividad.length;i++){
			if(frm_catalogoActividades.rdb_actividad[i].checked)
				band = 1;
		}
		if(band==0)
			alert("Seleccionar la Actividad a "+accion);
	}
	//Confirmar que se desea borrar el Registro seleccionado teniendo en cuenta que es el Radiobutton tiene mas de una opcion
	if(frm_catalogoActividades.rdb_actividad.length>=2 && accion=="Eliminar" && band==1){
		if(!confirm("De Borrar la Actividad Seleccionada ya no se podrá Recuperar. \nPresione Aceptar para Borrarla Definitivamente"))
			band = 0;
	}

	if (band==1)
		return true;
	else
		return false;

}

/*Funcion que se encarga de Verificar los datos en el formulario de Agregar Incentivos*/
function valFormAgregarIncentivos(frm_agregarIncentivos){
	//Variable bandera de control de validacion
	band=1;
	//Verificar si el campo de Actividad esta vacio
	if(frm_agregarIncentivos.txa_actividad.value==""){
		alert("Ingresar la Actividad");
		band=0;
	}
	//Verificar si el campo de Actividad esta vacio
	if(frm_agregarIncentivos.txt_costo.value=="0.00" && band==1){
		alert("El Costo de la Actividad debe ser Mayor a $0.00");
		band=0;
	}

	if (band==1)
		return true;
	else
		return false;
}

/***************************************************************************************************************************************/
/***************************************************************GENERAR NOMINA**********************************************************/
/***************************************************************************************************************************************/
/*Esta funciona valida los datos seleccionados en el Formulario frm_registrarNomina*/
function valSeleccionarNomina(frm_registrarNomina){
	var res=1;
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_registrarNomina.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_registrarNomina.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_registrarNomina.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Cierre, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_registrarNomina.txt_fechaFin.value.substr(0,2);
	var finMes=frm_registrarNomina.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_registrarNomina.txt_fechaFin.value.substr(6,4);
	
	
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

/*Estan función activa todos lo CheckBox del Equipo de Seguridad*/
function checarTodos(chkbox){
	for(var i=0;i<document.frm_bonoNomina.elements.length;i++){
		//Variable
		elemento=document.frm_bonoNomina.elements[i];
		if (elemento.type=="checkbox")
			elemento.checked=chkbox.checked;
	}	
}


/*Esta funcion desactiva el CheckBox de Seleccionar Todo cuando un CheckBox del equipo de seguridad es desseleccionado*/
function desSeleccionar(checkbox){
	if (!checkbox.checked){
		document.getElementById("ckbTodo").checked=false;
		if(checkbox.name.substr(0,3)=="ckb"&&checkbox.name.substr(0,4)!="ckb_")
			document.getElementById(checkbox.name.substr(0,3)+"_"+checkbox.name.substr(3)).checked=false;
	}
	else{
		if(checkbox.name.substr(0,4)=="ckb_")
			document.getElementById(checkbox.name.substr(0,3)+checkbox.name.substr(4)).checked=true;
	}
}

//Funcion que al deseleccionar un chek, realiza los calculos correspondintes al sueldo base
function establecerAsistencia(no,elemento,incapacidad,elementModif,elementoModif2){
	//Obtener a referencia para cada elemento
	check=document.getElementById(""+elemento);
	check2=document.getElementById(""+elementModif+no);
	check3=document.getElementById(""+elementoModif2+no);
	sueldo_base=document.getElementById("txt_sb"+no);
	sueldo_diario=document.getElementById("txt_sd"+no);
	destajo=document.getElementById("txt_destajo"+no);
	bonif=document.getElementById("txt_bonificaciones"+no);
	total=document.getElementById("txt_total"+no);
	
	//Si el checkbox esta checado, se calculan los totales
	if(incapacidad == 0){
		if(check.checked){
			//aumenta dias al sueldo base
			sueldo_base.value=parseFloat(sueldo_base.value) + parseFloat(sueldo_diario.value);
			sueldo_base.value = parseFloat(Math.round(sueldo_base.value * 100) / 100).toFixed(2);
			//recalcula el total a pagar
			total.value=parseFloat(destajo.value) + parseFloat(sueldo_base.value);
			total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
			check2.checked=false;
			check3.checked=false;
		}
		//Si se quita el check, se calculan los totales
		else{
			//descuenta dias al sueldo base
			sueldo_base.value=parseFloat(sueldo_base.value) - parseFloat(sueldo_diario.value);
			sueldo_base.value = parseFloat(Math.round(sueldo_base.value * 100) / 100).toFixed(2);
			//recalcula el total a pagar
			total.value=parseFloat(destajo.value) + parseFloat(sueldo_base.value);
			total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
		}
	} else if(incapacidad == 1){
		if(check.checked && check2.checked != false){
			//descuenta dias al sueldo base
			sueldo_base.value=parseFloat(sueldo_base.value) - parseFloat(sueldo_diario.value);
			sueldo_base.value = parseFloat(Math.round(sueldo_base.value * 100) / 100).toFixed(2);
			//recalcula el total a pagar
			total.value=parseFloat(destajo.value) + parseFloat(sueldo_base.value);
			total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
			check2.checked=false;
			check3.checked=false;
		}
		else if(check3.checked != false){
			check2.checked=false;
			check3.checked=false;
		}
	} else if(check3.checked && check.checked != false){
		check.checked=false;
		check2.checked=false;
		//descuenta dias al sueldo base
		sueldo_base.value=parseFloat(sueldo_base.value) - parseFloat(sueldo_diario.value);
		sueldo_base.value = parseFloat(Math.round(sueldo_base.value * 100) / 100).toFixed(2);
		//recalcula el total a pagar
		total.value=parseFloat(sueldo_base.value);
		total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
	} else if(check3.checked && check2.checked != false){
		check.checked=false;
		check2.checked=false;
		//recalcula el total a pagar
		total.value=parseFloat(sueldo_base.value);
		total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
	} else if(check3.checked == false){
		total.value=parseFloat(destajo.value) + parseFloat(sueldo_base.value);
		total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
	}
	
	total.value=parseFloat(bonif.value) + parseFloat(sueldo_base.value) + parseFloat(destajo.value);
	total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
	
	if(document.getElementById("ckb_juevesAL"+no).checked || document.getElementById("ckb_viernesAL"+no).checked || document.getElementById("ckb_sabadoAL"+no).checked || document.getElementById("ckb_domingoAL"+no).checked || document.getElementById("ckb_lunesAL"+no).checked || document.getElementById("ckb_martesAL"+no).checked || document.getElementById("ckb_miercolesAL"+no).checked){
		total.value=parseFloat(sueldo_base.value);
		total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
	}
	
}//Fin de function establecerAsistencia(no,elemento,incapacidad,elementModif,elementoModif2)

//Funcion que al deseleccionar un chek, realiza los calculos correspondintes al sueldo base
function agregarBonificacion(no,elemento,dest){
	//Obtener a referencia para cada elemento
	check=document.getElementById(""+elemento);
	sueldo_base=document.getElementById("txt_sb"+no);
	sueldo_diario=document.getElementById("txt_sd"+no);
	destajo=document.getElementById("txt_destajo"+no);
	bonif=document.getElementById("txt_bonificaciones"+no);
	total=document.getElementById("txt_total"+no);
	
	if(check.id==("txt_he"+no)){
		//calcular el bono a aumentar por horas extra
		he = (parseFloat(sueldo_diario.value / 8 * check.value)) * 2;
		//recalcular el destajo
		destajo.value=parseFloat(dest) + parseFloat(he);
		destajo.value = parseFloat(Math.round(destajo.value * 100) / 100).toFixed(2);
		//recalcula el total a pagar
		total.value=parseFloat(sueldo_base.value) + parseFloat(destajo.value);
		total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
		////recalcula el destajo del empleado
		recalcularDestajo(document.getElementById("ckb_8hrs"+no),no,0);
		//recalcula el destajo del empleado
		recalcularDestajo(document.getElementById("ckb_12hrs"+no),no,0);
	} else{
		//recalcula el destajo del empleado
		recalcularDestajo(check,no,1);
	}
	
	total.value=parseFloat(bonif.value) + parseFloat(sueldo_base.value) + parseFloat(destajo.value);
	total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
	
	if(document.getElementById("ckb_juevesAL"+no).checked || document.getElementById("ckb_viernesAL"+no).checked || document.getElementById("ckb_sabadoAL"+no).checked || document.getElementById("ckb_domingoAL"+no).checked || document.getElementById("ckb_lunesAL"+no).checked || document.getElementById("ckb_martesAL"+no).checked || document.getElementById("ckb_miercolesAL"+no).checked){
		total.value=parseFloat(sueldo_base.value);
		total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
	}
}//Fin de function agregarBonificacion(no,elemento,dest)

function agregarBonifRH(no){
	sueldo_base=document.getElementById("txt_sb"+no);
	destajo=document.getElementById("txt_destajo"+no);
	bonif=document.getElementById("txt_bonificaciones"+no);
	total=document.getElementById("txt_total"+no);
	
	total.value=parseFloat(bonif.value) + parseFloat(sueldo_base.value) + parseFloat(destajo.value);
	total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
	
	if(document.getElementById("ckb_juevesAL"+no).checked || document.getElementById("ckb_viernesAL"+no).checked || document.getElementById("ckb_sabadoAL"+no).checked || document.getElementById("ckb_domingoAL"+no).checked || document.getElementById("ckb_lunesAL"+no).checked || document.getElementById("ckb_martesAL"+no).checked || document.getElementById("ckb_miercolesAL"+no).checked){
		total.value=parseFloat(sueldo_base.value);
		total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
	}
}

//Funcion que recalcula el destajo del empleado
function recalcularDestajo(objeto,num,continuar){
	//Entra si esta seleccionado la guardia de 8 horas
	if(objeto.id==("ckb_8hrs"+num)){
		//si esta activada la casilla le suma la cantidad indicada al destajo
		if(objeto.checked){
			//recalcular el destajo
			destajo.value=parseFloat(350) + parseFloat(destajo.value);
			destajo.value = parseFloat(Math.round(destajo.value * 100) / 100).toFixed(2);
			//recalcula el total a pagar
			total.value=parseFloat(sueldo_base.value) + parseFloat(destajo.value);
			total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
			if(document.getElementById("ckb_12hrs"+num).checked){
				document.getElementById("ckb_12hrs"+num).checked = false;
				//recalcular el destajo
				destajo.value=parseFloat(destajo.value) - parseFloat(500);
				destajo.value = parseFloat(Math.round(destajo.value * 100) / 100).toFixed(2);
				//recalcula el total a pagar
				total.value=parseFloat(sueldo_base.value) + parseFloat(destajo.value);
				total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
			}
		}
		//si se paso el parametro de continuar se le resta la cantidad indicada al destajo
		else if(continuar == 1){
			//recalcular el destajo
			destajo.value=parseFloat(destajo.value) - parseFloat(350);
			destajo.value = parseFloat(Math.round(destajo.value * 100) / 100).toFixed(2);
			//recalcula el total a pagar
			total.value=parseFloat(sueldo_base.value) + parseFloat(destajo.value);
			total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
		}
	} 
	//Entra si esta seleccionado la guardia de 12 horas
	else if(objeto.id==("ckb_12hrs"+num)){
		//si esta activada la casilla le suma la cantidad indicada al destajo
		if(objeto.checked){
			//recalcular el destajo
			destajo.value=parseFloat(500) + parseFloat(destajo.value);
			destajo.value = parseFloat(Math.round(destajo.value * 100) / 100).toFixed(2);
			//recalcula el total a pagar
			total.value=parseFloat(sueldo_base.value) + parseFloat(destajo.value);
			total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
			if(document.getElementById("ckb_8hrs"+num).checked){
				document.getElementById("ckb_8hrs"+num).checked = false;
				//recalcular el destajo
				destajo.value=parseFloat(destajo.value) - parseFloat(350);
				destajo.value = parseFloat(Math.round(destajo.value * 100) / 100).toFixed(2);
				//recalcula el total a pagar
				total.value=parseFloat(sueldo_base.value) + parseFloat(destajo.value);
				total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
			}
		}
		//si se paso el parametro de continuar se le resta la cantidad indicada al destajo
		else if(continuar == 1){
			//recalcular el destajo
			destajo.value=parseFloat(destajo.value) - parseFloat(500);
			destajo.value = parseFloat(Math.round(destajo.value * 100) / 100).toFixed(2);
			//recalcula el total a pagar
			total.value=parseFloat(sueldo_base.value) + parseFloat(destajo.value);
			total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
		}
	}
}

//Funcion que desbloquea los campos requeridos para nomina cuando se selecciona un elemento
function desbloquearCamposNomina(elemento,no){
	//Obtener a referencia para cada elemento
	check=document.getElementById(""+elemento);
	desbloquear=document.getElementById("txt_he"+no);
	//si se esta activado el elemento se desbloquea el campo que se requiere
	if(check.checked){
		desbloquear.readOnly=false;
	} 
	//si no esta seleccionado el elemento
	else{
		//bloque el campo
		desbloquear.readOnly=true;
		//reinicial el valor
		desbloquear.value=0;
		//llama la funcion de onchange
		desbloquear.onchange();
	}
}

//Esta funcion sirve para mostrar en el formulario frm_registrarBono las opciones de bonificación
function activarBono(area){
	if (area=="SCOOP" || area=="JUMBO" || area=="VOLADURAS"){
		document.getElementById("txt_bono").readOnly=true;
		document.getElementById("txt_bono").value="Click Bono ->";
		document.getElementById("txt_bono").title="Seleccionar el elemento de la Derecha '[_]' para Agregar Bonos";
		document.getElementById("ckb_catalogoBonos").style.visibility="visible";
	}else{
		//document.getElementById("txt_bono").readOnly=false;
		document.getElementById("txt_bono").value="0.00";
		document.getElementById("ckb_catalogoBonos").checked=false;
		document.getElementById("txt_bono").title="";
		document.getElementById("ckb_catalogoBonos").style.visibility="hidden";
	}
}

//Esta funcion retorna el formulario frm_registrarBono a su estado original
function resetFormNomina(){
	document.getElementById("cmb_puestos").length=1;
	document.getElementById("ckb_catalogoBonos").style.visibility="hidden";
	document.getElementById("etiqMetrosAvance").innerHTML="";
}

//Funcion que abre la ventana emergente del Bono a agregar segun las áreas de Jumbo, Scoop, y Voladuras
function abrirVentBono(checkbox,area,nombre,fechaI,fechaF,puesto,sueldoBase,pctje,bonoBasico){
	if (checkbox.checked){
		var band=0;
		if (nombre==""){
			alert("Debe Ingresar el Nombre del Trabajador Primero");
			band=1;
		}
		if (sueldoBase=="0.00" && band==0){
			alert("Debe Seleccionar el Puesto del Trabajador Primero");
			band=1;
		}
		if (band==0){
			window.open('verBonoNomina.php?area='+area+'&nombre='+nombre+'&fechaI='+fechaI+'&fechaF='+fechaF+'&puesto='+puesto+'&sueldoBase='+sueldoBase+'&pctje='+pctje+'&bonoBasico='+bonoBasico, '_blank','top=100, left=100, width=700, height=610, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
			document.getElementById("ckb_catalogoBonos").style.visibility = "hidden";
			//alert("Area: "+area+"\nNombre: "+nombre+"\nFecha Inicio: "+fechaI+"\nFecha Fin: "+fechaF+"\nPuesto: "+puesto+"\nSueldo Base: "+sueldoBase);
		}
		else
			document.getElementById("ckb_catalogoBonos").checked=false;
	}
}

/*Esta funcion suma todos los checkbox */
function sumarTodosBono(checkbox){
	if(checkbox.checked){
		bono=document.getElementById("hdn_acumulado").value;
		formatCurrency(bono,'txt_bono');
	}else{
		bono=0;
		formatCurrency(bono,'txt_bono');
	}
}

/*Esta funcion se encarga de acumular el valor de los bonos seleccionados*/
function sumaBono(ckb){
	if(document.getElementById("ckb_sumaTodo").checked)
		document.getElementById("ckb_sumaTodo").checked=false;
	bono=parseFloat(document.getElementById("txt_bono").value.replace(/,/g,''));
	if(ckb.checked){
		bono=bono+parseFloat(ckb.value);
	}else{
		bono=bono-parseFloat(ckb.value);
	}
	formatCurrency(bono,'txt_bono');
}

//Funcion que valida los datos ingresados en el formulario frm_registrarBono
function validarFormBonoEspecial(frm_registrarPresupuesto){
	//Si el valor se mantiene en 1, entonces el proceso de validacion fue satisfactorio
	var res = 1;
	//Si el valor de hdn_accion es Guardar, evitar el proceso de Guardado
	if(frm_registrarPresupuesto.hdn_accion.value!="guardar"){
		if(frm_registrarPresupuesto.cmb_area.value==""){
			alert("Seleccionar un Área");
			res=0;
		}
		if(frm_registrarPresupuesto.txt_nombre.value=="" && res!=0){
			alert("Ingresar un Nombre");
			res=0;
		}
		if(frm_registrarPresupuesto.cmb_puestos.value=="" && res!=0){
			alert("Seleccionar un Puesto");
			res=0;
		}
		if(frm_registrarPresupuesto.txt_sueldoBase.value=="" && res!=0){
			alert("Para Calcular El Sueldo Base, Seleccionar un Puesto");
			res=0;
		}
	}
	
	if (res==1)
		return true;
	else
		return false;
}

//Funcion que suma el sueldo Bonificado al Base
function sumarSueldoBono(){
	document.getElementById("txt_sueldoTotal").value="0.00";
	document.getElementById("txt_bonoBasico").value="0.00";
	document.getElementById("txt_bonoMetros").value="0.00";
	document.getElementById("txt_bono").value="0.00";
}

//Funcion que suma el bono y el sueldo a traves de un evento Onchange en el Elemento Text -> Bono
function sumarBonoSueldo(sueldoBase,bono,bonoBasico,bonoMetros){
	sueldoBase=parseFloat(sueldoBase.replace(/,/g,''));
	bonoMetros=parseFloat(bonoMetros.replace(/,/g,''));
	bonoBasico=parseFloat(bonoBasico.replace(/,/g,''));
	if(bono!="Click Bono ->")
		bono=parseFloat(bono.replace(/,/g,''));
	else
		bono=0;
	formatCurrency(sueldoBase+bono+bonoMetros+bonoBasico,'txt_sueldoTotal');
}

//Funcion que al cambiar de valor el Combo Área, restablece los valores de sueldo Base y Total
function quitarSueldoBase(){
	document.getElementById("txt_sueldoBase").value="0.00";
	document.getElementById("txt_sueldoTotal").value="0.00";
	document.getElementById("txt_bonoBasico").value="0.00";
	document.getElementById("txt_bonoMetros").value="0.00";
}

//funcion que en la ventana Emergente, verifica el avance para asignar un Bono
function valFormBonoEspecial(frm_bonoNomina){
	var band=1;
	
	if (frm_bonoNomina.hdn_accion.value=="Add"){
		//Variable para almacenar la cantidad de registros
		var cantidad = frm_bonoNomina.cant_ckbs.value;
		band=0;
		ctrl=1;
		while(ctrl<cantidad){		
			//Crear el id del CheckBox que se quiere verificar
			idCheckBox="ckb_actividad"+ctrl.toString();
			//Verificar que la cantidad y la aplicación del Checkbox seleccionado no esten vacias
			if(document.getElementById(idCheckBox).checked){
				band=1;
			}
			ctrl++;
		}//Fin del While
		
		if (band==0){
			alert("Seleccionar por lo menos una Actividad para Bonificar");
		}
		
		if (frm_bonoNomina.hdn_avance.value<18 && band==1){
			if (!confirm("El Avance es de "+frm_bonoNomina.hdn_avance.value+" Mts. el Mínimo Requerido para Bonificar es de 18 Mts.\nPresione Aceptar para Asignar el Bono. En caso Contrario presione Cancelar."))
				band=0
		}
	}
	if(band==1)
		return true;
	else
		return false;
}

function consultarObservaciones(radio,bitacora,avance,area){
	if(radio.checked){
		cont=1;
		ar=area.charAt(0).toUpperCase();
		while(cont<area.length){
			ar+=area.charAt(cont).toLowerCase();
			cont++;
		}
		if(bitacora!="" && avance!=""){
			tam=bitacora.length-1;
			bitacora=bitacora.substring(0,tam);
			tam=avance.length-1;
			avance=avance.substring(0,tam);
			alert("-Observaciones Bitácora "+ar+":\n"+bitacora+"\n-Observaciones Bitácora Avance:\n"+avance);
		}
		if(bitacora!="" && avance==""){
			tam=bitacora.length-1;
			bitacora=bitacora.substring(0,tam);
			alert("-Observaciones Bitácora:\n"+bitacora);
		}
		if(bitacora=="" && avance!=""){
			tam=avance.length-1;
			avance=avance.substring(0,tam);
			alert("-Observaciones Bitácora Avance:\n"+avance);
		}
		if(bitacora=="" && avance==""){
			alert("-No Hay Observaciones Registradas");
		}
	}
	radio.checked=false;
}

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
/************************************************************** REGISTRAR BITACORAS ****************************************************************************/
/***************************************************************************************************************************************************************/
/*Esta funcion abre una ventana donde se capturaran los datos necesarios para registrar Fallas y Consumo de cualquiera de las Bitácoras (Avance y Retro/Bull)*/
function abrirVentana(tipoBitacora,tipoOper){
		
	var pagina = "";
	var msg = "";
	
	//Identificar cual Bitacora es la que guardará los registros (Fallas o Consumos)
	if(tipoBitacora=="fallas"){
		if(tipoOper=="agregar"){
			pagina = "verRegistrarFallas.php";
			msg = "Seleccionar un Equipo para Poder Registrar las Fallas Ocurridas";		
		}
		else if(tipoOper=="modificar"){
			pagina = "verModificarFallas.php";
			msg = "Seleccionar un Equipo para Poder Modificar las Fallas Registradas";
		}
	}
	else if(tipoBitacora=="consumos"){
		if(tipoOper=="agregar"){
			pagina = "verRegistrarConsumos.php";
			msg = "Seleccionar un Equipo para Poder Registrar los Consumos";		
		}
		else if(tipoOper=="modificar"){
			pagina = "verModificarConsumos.php";
			msg = "Seleccionar un Equipo para Poder Modificar los Consumos Registrados";
		}
	}
	
	
	//Antes de abrir la ventana para registrar una Falla o un Consumo, verificar que haya sido seleccionado un equipo
	if(document.getElementById("cmb_equipo").value!=""){
		//Recuperar el ID de la Bitacora y el Tipo de Bitacora y el Tipo de Registro desde la Bitacora que registrará una Falla o un Consumo
		var idBit = document.getElementById("hdn_idBitacora").value;
		var tipoBit = document.getElementById("hdn_tipoBitacora").value;
		var tipoReg = document.getElementById("hdn_tipoRegistro").value;
		
		
		//Deshabilitar el boton de acuerdo a la Bitacora que va a ser registrada, tanto en las paginas de registrar y modificar cada bitacora los botones de 
		//Fallas y Consumos se llamaran igual
		if(tipoBitacora=="fallas")			
			document.getElementById("btn_regFallas").disabled = true;//Deshabilitar el boton de registrar falla
		else if(tipoBitacora=="consumos")			
			document.getElementById("btn_regConsumos").disabled = true;//Deshabilitar el boton de registrar consumos
		
		
		/* Abrir la ventana emergente para registrar o modificar las Fallas y/o los Consumos segun corresponda
		 * La variable 'vtnAbierta' debe estar declara en todas las paginas(formularios) desde donde se abren las ventanas de Registrar y Modificar los registros de 
		 * Fallas y Consumos (frm_regBarrJumbo.php, frm_regBarrMP.php, frm_regVoladura.php, frm_regRezagado.php, frm_modBarrJumbo.php, frm_modBarrMP.php, 
		 * frm_modVoladura.php y frm_modRezagado.php) */
		vtnAbierta = window.open(pagina+"?idBitacora="+idBit+"&tipoBitacora="+tipoBit+"&tipoRegistro="+tipoReg,
					"_blank","top=10, left=10, width=860, height=645, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no");
	}
	else{
		//Notificar al usuario acerca de la falta de selección de un equipo para registrar la falla o el consumo
		alert(msg);
	}
}//Cierre de la funcion abrirVentana(tipoBitacora,tipoOper)


/*Esta funcion advierte al usuario sobre abandonar la ventana de Fallas o de Consumos y de la perdidad de información que esto puede generar*/
function cerrarVentana(nomTabla){
	if(confirm("¿Estas Seguro que Quieres Salir?")){
		//Obtener los datos necesarios para proceder a borrar los registros cancelados en la Bitácora
		var idBitacora = window.opener.document.getElementById("hdn_idBitacora").value;
		var tipoBitacora = window.opener.document.getElementById("hdn_tipoBitacora").value; 				
		var tipoRegistro = window.opener.document.getElementById("hdn_tipoRegistro").value; 				
		
				
		//Antes de Borrar los registros, preguntar al Usuario si quiere descartarlos
		var cantRegistros = document.getElementById("hdn_cantRegistros").value;
		if(cantRegistros>0){
			//Mostrar la Notificacion para la Bitacora de Fallas
			if(nomTabla=="fallas"){
				var equipo = document.getElementById("txt_equipo").value;
				if(!confirm("Existen "+cantRegistros+" Registros de Fallas para el Equipo "+equipo+"\nPresione 'Aceptar' para Conservar los Registros o 'Cancelar' para Borrarlos")){
					//Mandar llamar la funcion que se encargará de borrar los registros guardados antes de cancelar la operacion
					borrarFallasConsumosTNT(idBitacora,tipoBitacora,nomTabla,tipoRegistro);				
					//Cuando se borran los registros, vaciar la caja de texto oculta, la cual puede tener el nombre de un equipo
					window.opener.document.getElementById('hdn_fallasEquipo').value = "";
				}
				else{
					//Si el Usuario decide conservar los registros, guardar el nombre del equipo en la caja oculta de la pagina desde la cual se abrio la ventana para el registro de fallas
					window.opener.document.getElementById('hdn_fallasEquipo').value = document.getElementById('txt_equipo').value;
				}
			}//Cierre if(tipoRegistro=="fallas")
			else if(nomTabla=="consumos"){//Mostrar la Notificacion para la Bitacora de Consumos
				var equipo = document.getElementById("txt_equipo").value;
				if(!confirm("Existen "+cantRegistros+" Registros de Consumos para el Equipo "+equipo+"\nPresione 'Aceptar' para Conservar los Registros o 'Cancelar' para Borrarlos")){
					//Mandar llamar la funcion que se encargará de borrar los registros guardados antes de cancelar la operacion
					borrarFallasConsumosTNT(idBitacora,tipoBitacora,nomTabla,tipoRegistro);		
					//Desactivar la varibale en la Ventana de a Bitacora para indicar que fueron registrados los consumos
					window.opener.document.getElementById('hdn_regBitConsumos').value = "no"; 
				}
				else{
					//Activar la varibale en la Ventana de a Bitacora para indicar que fueron registrados los consumos
					window.opener.document.getElementById('hdn_regBitConsumos').value = "si"; 
				}
			}//Cierre else if(tipoRegistro=="consumos")												
			else if(nomTabla=="explosivos"){
				if(!confirm("Existen "+cantRegistros+" Registros de Explosivos!\nPresione 'Aceptar' para Conservar los Registros o 'Cancelar' para Borrarlos")){
					//Mandar llamar la funcion que se encargará de borrar los registros guardados antes de cancelar la operacion
					borrarFallasConsumosTNT(idBitacora,tipoBitacora,nomTabla,tipoRegistro);					 
				}				
			}//Cierre else if(nomTabla=="explosivos")
		}//Cierre if(cantRegistros>0)
		
		
				
		if(nomTabla=="fallas"){
			//Habilitar el boton de registrar falla
			window.opener.document.getElementById("btn_regFallas").disabled = false;
		}
		else if(nomTabla=="consumos"){
			//Habilitar el boton de registrar consumos
			window.opener.document.getElementById("btn_regConsumos").disabled = false;
		}
		else if(nomTabla=="explosivos"){
			//Habilitar el boton de registrar explosivos
			window.opener.document.getElementById("btn_regExplosivos").disabled = false;
		}
		
				
		//Cerrar la Ventana donde se registran las Fallas de los Equipos
		window.close();
	}
}//Cierre de la funcion cerrarVentana(tipoRegistro)



/*Esta funcion verifica si la Ventana donde se Registran o Modifican los Consumos y la Fallas ha sido cerrada*/
function verificarCierreVtn(){
	//Verificar si la ventana fue cerrada
	if(vtnAbierta.closed){
		/*Si la ventana fue cerrada habilitar los botones de Registrar y Modificar Fallas, Consumos y Explosivos de las diferentes paginas, los botones se llaman igual en 
		 * las paginas de Registro y de Modificación */
		document.getElementById("btn_regFallas").disabled = false;
		
		//Los botones de Consumos y Explosivos no son comunes, entonces verificamos si existen antes de Activarlos
		if(document.getElementById("btn_regConsumos")!=null)
			document.getElementById("btn_regConsumos").disabled = false;
		if(document.getElementById("btn_regExplosivos")!=null)
			document.getElementById("btn_regExplosivos").disabled = false;
	}
}


/*Esta función se encargará revertir los registros realizados en la BD para las Bitácoras de Barrenación, Voladura y Rezagado cuando el usuario de clic en el boton de Cancelar */
function cancelarOperacion(idBitacora,tipoBitacora,tipoRegistro,pagina){
	if(confirm("¿Estas Seguro que Quieres Salir?\nLos Registros de Fallas y Consumos Serán Borrados")){
		//Guardar el codigo que será ejecutado
		var borrarBitConsumos = "borrarFallasConsumosTNT('"+idBitacora+"','"+tipoBitacora+"','consumos','"+tipoRegistro+"');";
		var redireccionar  = "location.href = '"+pagina+"';";
				
		//Borrar Primero la Bitácora de Fallas
		borrarFallasConsumosTNT(idBitacora,tipoBitacora,"fallas",tipoRegistro);		
				
		//1 segundo después borrar la bitacora de Consumos
		setTimeout(borrarBitConsumos,1000);
		
		//1.5 segundos después redireccionar a la pagina indicada cuando la operación sea cancelada
		setTimeout(redireccionar,1500);
	}
}//Cierre de la función cancelarOperacion(idBitacora,tipoBitacora,tipoRegistro,pagina)


/*Esta funcion se encarga de actividar y pasar datos a la ventana desde la cual se abrio la pagina para el regitro de fallas*/
function finalizar(tipoRegistro){
	
	//Activiar el boton correspondiente al tipo de Registro (Fallas o Consumos)
	if(tipoRegistro=="fallas"){
		//Activar el boton de registrar fallas en la ventana desde la cual se abrio la ventana para el registro de Fallas
		window.opener.document.getElementById('btn_regFallas').disabled = false; 
		//Guardar el nombre del equipo en la caja oculta de la pagina desde la cual se abrio la ventana para el registro de fallas
		window.opener.document.getElementById('hdn_fallasEquipo').value = document.getElementById('txt_equipo').value;
	} 
	else if(tipoRegistro=="consumos"){
		//Activar el boton de registrar consumos en la ventana desde la cual se abrio la ventana para el registro de Consumos
		window.opener.document.getElementById('btn_regConsumos').disabled = false; 
		//Activar la varibale en la Ventana de a Bitacora para indicar que fueron registrados los consumos
		window.opener.document.getElementById('hdn_regBitConsumos').value = "si"; 
	}
	else if(tipoRegistro=="explosivos"){
		//Activar el boton de registrar consumos en la ventana desde la cual se abrio la ventana para el registro de Consumos
		window.opener.document.getElementById('btn_regExplosivos').disabled = false; 
		//Activar la variable en la Ventana de la Bitacora para indicar que fueron registrados los consumos
		window.opener.document.getElementById('hdn_regBitExplosivos').value = "si"; 
	}
	
	
	//Cerrar la ventana de regitro de fallas
	window.close();
}//Cierre de la funcion finalizar(tipoRegistro)


/*Esta funcion calcular las horas totales de trabajo del equipo registrado en la Bitacoras de BArrenación con Jumbo y MP, Voladura, Rezagado y Equipo Utilitario*/
function calcularHorasTotales(HI,HF,HT){
	
	//Obtener los valores para realizar el calculo, quitar la coma en el caso de que exista 
	var horoIni = document.getElementById(HI).value.replace(/,/g,'');
	var horoFin = document.getElementById(HF).value.replace(/,/g,'');
		
	//Verificar que los datos necesarios para realizar el calculo sean porporcionados
	var totalHoras = 0;
	if(horoIni!="" && horoFin!=""){
		//Verificar que el Horometro Inicial sea un numero valido
		var horoIniValido = validarEntero(horoIni,"El Horómetro Inicial");
		if(horoIniValido){
			//Verificar que el Horometro Final sea un numero valido
			var horoFinValido = validarEntero(horoFin,"El Horómetro Final");
			if(horoFinValido){
				//Convertir en datos numericos los valores de los Horometros
				hIni = parseFloat(horoIni);
				hFin = parseFloat(horoFin);
				
				//Verificar que el Horómetro Final no sea menor que el Inicial.
				if(hFin<=hIni){
					alert("El Horómetro Final no puede ser menor o igual al Horómetro Inicial");
					//Limpiar los datos introducidos
					document.getElementById(HI).value = "";
					document.getElementById(HF).value = "";
					document.getElementById(HT).value = "";	
				}
				else{
					//Cuando los datos del Horometro Inicial y Final hayan pasado las pruebas, obtenemos la diferencia
					var total = hFin - hIni;
					//Asignar el valor del total en el campo de texto que lo va a mostrar y le damos formato numerico
					formatCurrency(total,HT);
				}
			}//Cierre if(horoFinValido)
			else{
				document.getElementById(HT).value = "";
				document.getElementById(HF).value = "";
			}
		}//Cierre if(horoIniValido)								
		else{
			document.getElementById(HT).value = "";
			document.getElementById(HI).value = "";
		}
	}//Cierre if(horoIni!="" && horoFin!="")'
	else{
		document.getElementById(HT).value = "";
	}
}//Cierre de la funcion calcularHorasTotales()


/*Esta funcion restablecerá los botones del formulario de Modificar Fallas y Consumos cuando se le de click al boton de Restablecer*/
function restBotones(tipoBit){	
	//Deshabilitar los botones de Modificar y Borrar
	document.getElementById("sbt_modificar").disabled = true;
	document.getElementById("sbt_borrar").disabled = true;
	
	//Hablitar el boton de Guardar
	document.getElementById("sbt_guardar").disabled = false;
	
	if(tipoBit=="consumos"){
		//Habilitar los Combos de Categoría y Material y el CheckBox de agregar nuevo
		document.getElementById("cmb_categoria").disabled = false;
		document.getElementById("cmb_idMaterial").disabled = false;
		document.getElementById("chk_nvoMaterial").disabled = false;								
	}
}//Cierre de la función restBotones()


/**************************************************************BITACORA FALLAS*********************************************************************************/
/*Esta funcion definira los valores del ComboBox que mostrar el tipo de Falla segun la Bitácora que vaya a ser registrada*/
function definirCombo(){	
	
	/* Obtener el nombre del Equipo de la caja de Texto oculta 'hdn_equipoRegBD' en la pagina verRegistrarFallas.php, la cual obtiene su valor cuando se 
	 * ejecuta la funcion 'verRegistroFallas' ubicada' en el archivo op_bitFallasConsumosTNT.php
	 */
	var equipo = document.getElementById("hdn_equipoRegBD").value;
	if(equipo==""){//Si el equipo esta vacio significa que no hay regiatros en la BD y por lo tanto tomamos el nombre del equipo de la pagina de Registrar Bitacora Rezagado
		//Obtener el nombre del equipo de la pagina que abre la ventana donde se registran las fallas
		equipo = window.opener.document.getElementById("cmb_equipo").value; 		
	}
	//Asignar el equipo a la caja de texto que lo mostrara en la pagina de Registrar Fallas			
	document.getElementById("txt_equipo").value = equipo;
	
	
	//Obtener la referencia del combo que guardará los tipos
	var combo = document.getElementById("cmb_tipo");	
	//Obtener el valor de la variable que contiene el tipo de equipo al cual se le registrará una falla
	var tipoEquipo = window.opener.document.getElementById("hdn_tipoEquipo").value;
	
	//Antes de Cargar el Combo, lo vaciamos en el caso de que tenga opciones previas y colocamos la opcion de mensaje
	combo.length = 0;
	combo.length++;
	combo.options[combo.length-1].text="Tipo";
	combo.options[combo.length-1].value="";
	
	//Recargar el combo dependiendo del tipo de equipo que va a ser registrado en la bitacora de fallas
	switch(tipoEquipo){
		case "JUMBO":
			//Agregar las opciones al Combo Box
			combo.length++;
			combo.options[combo.length-1].text="MECANICA";
			combo.options[combo.length-1].value="MECANICA";			
			combo.length++;
			combo.options[combo.length-1].text="ELECTRICA";
			combo.options[combo.length-1].value="ELECTRICA";
			combo.length++;
			combo.options[combo.length-1].text="OPERATIVA";
			combo.options[combo.length-1].value="OPERATIVA";			
		break;
		case "MP":
			//Agregar las opciones al Combo Box
			combo.length++;
			combo.options[combo.length-1].text="MECANICA";
			combo.options[combo.length-1].value="MECANICA";			
			combo.length++;
			combo.options[combo.length-1].text="ELECTRICA";
			combo.options[combo.length-1].value="ELECTRICA";
			combo.length++;
			combo.options[combo.length-1].text="OPERATIVA";
			combo.options[combo.length-1].value="OPERATIVA";			
		break;
		case "SCOOP":
			//Agregar las opciones al Combo Box
			combo.length++;
			combo.options[combo.length-1].text="MECANICA";
			combo.options[combo.length-1].value="MECANICA";			
			combo.length++;
			combo.options[combo.length-1].text="OPERATIVA";
			combo.options[combo.length-1].value="OPERATIVA";
		break;
		case "VOLADURAS":
			//Agregar las opciones al Combo Box
			combo.length++;
			combo.options[combo.length-1].text="MECANICA";
			combo.options[combo.length-1].value="MECANICA";			
			combo.length++;
			combo.options[combo.length-1].text="OPERATIVA";
			combo.options[combo.length-1].value="OPERATIVA";
		break;
		case "RETRO-BULL":
			//Agregar las opciones al Combo Box
			combo.length++;
			combo.options[combo.length-1].text="MECANICA";
			combo.options[combo.length-1].value="MECANICA";			
			combo.length++;
			combo.options[combo.length-1].text="ELECTRICA";
			combo.options[combo.length-1].value="ELECTRICA";
			combo.length++;
			combo.options[combo.length-1].text="OPERATIVA";
			combo.options[combo.length-1].value="OPERATIVA";
		break;
	}
	
	
}//Cierre de la funcion definirCombo()


/*Esta función valida el formulario donde se registran las fallas de los equipos*/
function valFormRegBitFallas(frm_regBitFallas){
	//Si el valor de la variable se mantiene en 1, el proceso de validación fue exitoso
	var validacion = 1;
	
	//Verificar que sea seleccionado un tipo de Falla
	if(frm_regBitFallas.cmb_tipo.value==""){
		alert("Seleccionar el Tipo de Falla");
		validacion = 0;
	}
	
	//Revisar que hayan sido introducidas las observaciones de la falla
	if(frm_regBitFallas.txa_observaciones.value=="" && validacion==1){
		alert("Introducir la Descripción de la Falla");
		validacion = 0;
	}
	
	//Verificar que sean introducidas las horas invertidas n resolver la falla
	if(frm_regBitFallas.txt_tiempoHrs.value=="" && validacion==1){
		alert("Introducir la Cantidad de Horas Invertidas en la Solución de la Falla");
		validacion = 0;
	}
	
	//verificar que la cantidad de horas sea un numero valido
	if(validacion==1){
		//Si el numero no es valido cambiar el valor de la variable 'validacion'
		if(!validarEntero(frm_regBitFallas.txt_tiempoHrs.value,"La Cantidad de Horas"))
			validacion = 0;
	}
	
	
	if(validacion==1)
		return true;
	else
		return false;
}//Cierre de la función valFormRegBitFallas(frm_regBitFallas)



/**************************************************************BITACORA CONSUMOS*********************************************************************************/
/*Esta funcion valida que el formulario de Agregar Consumos contenga los datos necesarios para el registro*/
function valFormRegBitConsumos(frm_regBitConsumos){
	//Si el valor de la variable se mantiene en 1, el proceso de validación fue exitoso
	var validacion = 1;
	
	//Verificar cual de los botones fue seleccionado (Guardar, Modificar y Borrar)
	if(frm_regBitConsumos.hdn_botonSelect.value=="guardar"){	
		//Revisar si el CheckBox esta seleccionado para realizar la validación de los datos del nuevo material
		if(frm_regBitConsumos.chk_nvoMaterial.checked){
			//Verificar que sea seleccionado un material
			if(frm_regBitConsumos.txt_material.value==""){
				alert("Introducir el Nombre del Nuevo Material Para el Registro");
				validacion = 0;
			}
			//Verificar que sea seleccionado un material
			if(frm_regBitConsumos.txt_unidadMedida.value=="" && validacion==1){
				alert("Introducir la Unidad de Medida");
				validacion = 0;
			}		
			
			//Verificar que sea seleccionado un material
			if(frm_regBitConsumos.txt_cant.value=="" && validacion==1){
				alert("Introducir la Cantidad");
				validacion = 0;
			}
			//verificar que la cantidad sea un numero valido
			if(validacion==1){
				//Si el numero no es valido cambiar el valor de la variable 'validacion'
				if(!validarEntero(frm_regBitConsumos.txt_cant.value.replace(/,/g,''),"La Cantidad"))
					validacion = 0;
			}
		}//Cierre if(frm_regBitConsumos.chk_nvoMaterial.checked)
		else{			
			//Verificar que sea seleccionado un material
			if(frm_regBitConsumos.cmb_idMaterial.value==""){
				alert("Seleccionar una Categoría y Después un Material Para el Registro");
				validacion = 0;
			}
			//Verificar que sea seleccionado un material
			if(frm_regBitConsumos.txt_cantidad.value=="" && validacion==1){
				alert("Introducir la Cantidad");
				validacion = 0;
			}
			
			//verificar que la cantidad sea un numero valido
			if(validacion==1){
				//Si el numero no es valido cambiar el valor de la variable 'validacion'
				if(!validarEntero(frm_regBitConsumos.txt_cantidad.value.replace(/,/g,''),"La Cantidad"))
					validacion = 0;
			}
		}//Cierre else
		
	}//Cierre if(frm_regBitConsumos.hdn_botonSelect.value=="guardar")
	
	
	//Si el boton seleccionado es el de Modificar, verificar que sea proporcionaa la cantidad
	if(frm_regBitConsumos.hdn_botonSelect.value=="modificar"){
		//Verificar que sea seleccionado un material
		if(frm_regBitConsumos.txt_cantidad.value=="" && validacion==1){
			alert("Introducir la Cantidad");
			validacion = 0;
		}
		//verificar que la cantidad sea un numero valido
		if(validacion==1){
			//Si el numero no es valido cambiar el valor de la variable 'validacion'
			if(!validarEntero(frm_regBitConsumos.txt_cantidad.value.replace(/,/g,''),"La Cantidad"))
				validacion = 0;
		}
	}
	
	
	if(validacion==1)
		return true;
	else
		return false;
}//Cierre de la funcion valFormRegBitConsumos(frm_regBitConsumos)


/*Esta funcion activa los campos para agregar un nuevo material*/
function activarCamposConsumos(checkBox){
	//Revisar que el Checkbox este seleccionado
	if(checkBox.checked){
		//Activar los campos para agregar un nuevo material
		document.getElementById("txt_material").readOnly = false;
		document.getElementById("txt_unidadMedida").readOnly = false;
		document.getElementById("txt_cant").readOnly = false;
		//Asignarles el No. de Indice a los campos activados
		document.getElementById("txt_material").tabIndex = 5;
		document.getElementById("txt_material").focus();//Colocar el Foco en la caja de Texto de Material
		document.getElementById("txt_unidadMedida").tabIndex = 6;
		document.getElementById("txt_cant").tabIndex = 7;
		
		//Vacia y Desactivar los campos para agregar material desde el catalogo
		document.getElementById("cmb_categoria").value = "";
		document.getElementById("cmb_categoria").disabled = true;
		document.getElementById("cmb_idMaterial").value = "";
		document.getElementById("cmb_idMaterial").disabled = true;
		document.getElementById("txt_cantidad").value = "";
		document.getElementById("txt_cantidad").disabled = true;
		
	}
	else{//Si el CheckBox no esta seleccionado, activar los campos para agregar los datos del Material desde el Catalogo
		//Vaciar los campos para colocarlos como ReadOnly nuevamente
		document.getElementById("txt_material").value = "";
		document.getElementById("txt_unidadMedida").value = "";
		document.getElementById("txt_cant").value = "";
		//Quitar el No. de Indice a los campos activados
		document.getElementById("txt_material").tabIndex = "";
		document.getElementById("txt_unidadMedida").tabIndex = "";
		document.getElementById("txt_cant").tabIndex = "";
		//desactivar los campos para agregar un nuevo material
		document.getElementById("txt_material").readOnly = true;
		document.getElementById("txt_unidadMedida").readOnly = true;
		document.getElementById("txt_cant").readOnly = true;
		
		//Activar los campos para agregar material desde el catalogo		
		document.getElementById("cmb_categoria").disabled = false;
		document.getElementById("cmb_idMaterial").disabled = false;
		document.getElementById("txt_cantidad").disabled = false;
		//Colocar el foco en el ComboBox de Categoria
		document.getElementById("cmb_categoria").focus();
	}
	
}//Cierre de la funcion activarCamposConsumos(checkBox)


/**************************************************************BITACORA EXPLOSIVOS*********************************************************************************/
/*Esta funcion abre una ventana emergente para captura los datos de los Explosivos de la Bitacora de Voladura*/
function abrirVentanaTNT(operacion){
			
	//Recuperar el ID de la Bitacora y el Tipo de Bitacora y el Tipo de Registro desde la Bitacora que registrará una Falla o un Consumo
	var idBit = document.getElementById("hdn_idBitacora").value;
			
	//Deshabilitar el boton de Registrar Explosivos	
	document.getElementById("btn_regExplosivos").disabled = true;
	
	//Definir la pagina que será abierta dependiendo de la operación a realizar (Registrar ó Modificar)
	var pagina = "";
	if(operacion=="registrar"){
		pagina = "verRegistrarExplosivos.php";
	}
	else if(operacion=="modificar"){
		pagina = "verModificarExplosivos.php";
	}
	
	
	/* Abrir la ventana emergente para registrar los Explosivos Utilizados
	 * La variable 'vtnAbierta' debe estar declara en todas las paginas(formularios) desde donde se abre la ventana de  
	 * Registrar Explosivos (frm_regVoladura.php y frm_modVoladura.php) */
	vtnAbierta = window.open(pagina+"?idBitacora="+idBit,
				"_blank","top=10, left=10, width=860, height=645, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no");
}//Cierre de la funcion abrirVentanaTNT()


/*Esta funcion valida el formulario para registrar los Explosivos Utilizados*/
function valFormRegBitExplosivos(frm_regBitExplosivos){
	//Esta variable ayudará a determinar si el proceso de validación fue realizado con éxito
	var validacion = 1;
	
	//Verificar cual de los botones fue seleccionado (Guardar, Modificar y Borrar)
	if(frm_regBitExplosivos.hdn_botonSelect.value=="guardar"){
	
		if(frm_regBitExplosivos.cmb_explosivo.value==""){
			alert("Seleccionar el Explosivo Empleado");
			validacion = 0;
		}
		if(frm_regBitExplosivos.txt_cantidad.value=="" && validacion==1){
			alert("Introducir la Cantidad de Explosivo Empleado");
			validacion = 0;
		}
		if(validacion==1){
			if(!validarEntero(frm_regBitExplosivos.txt_cantidad.value.replace(/,/g,''),"La Cantidad Empleada"))
				validacion = 0;
		}
	}//Cierre if(frm_regBitExplosivos.hdn_botonSelect.value=="guardar")
	
	
	//Si el boton seleccionado es el de Modificar, verificar que sea proporcionaa la cantidad
	if(frm_regBitExplosivos.hdn_botonSelect.value=="modificar"){
		//Verificar que sea seleccionado un material
		if(frm_regBitExplosivos.txt_cantidad.value=="" && validacion==1){
			alert("Introducir la Cantidad");
			validacion = 0;
		}
		if(validacion==1){
			if(!validarEntero(frm_regBitExplosivos.txt_cantidad.value.replace(/,/g,''),"La Cantidad Empleada"))
				validacion = 0;
		}
	}
	
	
	
	if(validacion==1)
		return true;
	else
		return false;
	
}//Cierre de la funcion valFormRegBitExplosivos(frm_regBitExplosivos)


/**************************************************************BITACORA AVANCE*********************************************************************************/
/*Esta función se encarga de dirección la petición http segun el boton que sea presionado en el formulario de Registrar Bitácora de Avance*/
function direccionarPagina(nomBoton){
	switch(nomBoton){
		
		//Acciones en la Sección de Registrar la Bitácora de Avance
		case "sbt_guardar":
			document.frm_regBitAvance.action = "frm_regAvance.php";
		break;
		case "sbt_regBarrenacion":
			document.frm_regBitAvance.action = "frm_tipoBarrenacion.php";
		break;
		case "sbt_regVoladura":
			document.frm_regBitAvance.action = "frm_regVoladura.php";
		break;
		case "sbt_regRezagado":
			document.frm_regBitAvance.action = "frm_regRezagado.php";
		break;
		
		
		//Acciones en la Sección de Modificar la Bitácora de Avance
		case "sbt_actualizar":
			document.frm_modBitAvance.action = "frm_modAvance2.php";
		break;
		case "sbt_modBarrJumbo":
			document.frm_modBitAvance.action = "frm_modBarrJumbo.php";
		break;
		case "sbt_modBarrMP":
			document.frm_modBitAvance.action = "frm_modBarrMP.php";
		break;
		case "sbt_modVoladura":
			document.frm_modBitAvance.action = "frm_modVoladura.php";
		break;
		case "sbt_modRezagado":
			document.frm_modBitAvance.action = "frm_modRezagado.php";
		break;
	}//Cierre switch(nomBoton)
	
}//Cierre de la función direccionarPagina(nomBoton)


/*Esta funcion valida que los datos del Formulario de la Bitácora de Avance sean proporcionados*/
function valFormRegBitAvance(frm_regBitAvance){
	//Esta variable ayudará a determinar si el proceso de validación fue realizado con éxito
	var validacion = 1;
	
	//Verificar que el boton seleccionado se el de GUARDAR
	if(frm_regBitAvance.hdn_btnClick.value=="guardar"){													
		
		if(frm_regBitAvance.cmb_lugar.value==""){
			alert("Seleccionar el Lugar");
			validacion = 0;
		}
		
		//Validar la cantidad del Machote
		if(frm_regBitAvance.txt_machote.value=="" && validacion==1){
			alert("Introducir la Cantidad del Machote");
			validacion = 0;
		}
		if(validacion==1){
			if(!validarEntero(frm_regBitAvance.txt_machote.value.replace(/,/g,''),"La Cantidad del Machote"))
				validacion = 0;
		}		
		
		//Validar la Medida
		if(frm_regBitAvance.txt_medida.value=="" && validacion==1){
			alert("Introducir la Medida");
			validacion = 0;
		}
		if(validacion==1){
			if(!validarEntero(frm_regBitAvance.txt_medida.value.replace(/,/g,''),"La Medida"))
				validacion = 0;
		}
				
		//Validar el avance
		if(frm_regBitAvance.txt_avance.value=="" && validacion==1){
			alert("Introducir el Avance");
			validacion = 0;
		}	
		if(validacion==1){
			if(!validarEntero(frm_regBitAvance.txt_avance.value.replace(/,/g,''),"El Avance"))
				validacion = 0;
		}
		
		
		
		/* Verificar cuales bitacoras faltan por registrar y notificar al usuario, para saber cual bitacora falta se verificará el estado del boton de cada una, sí el boton
		 * esta deshabilitado significa que la bitacora ya fue registrada */
		if(validacion==1){
			var verMensaje = false;
			var mensaje = "Faltan de Registrar las Siguientes Bitácoras:";
			if(!frm_regBitAvance.sbt_regBarrenacion.disabled){//Bitácora de Barrenación
				mensaje += "\n * Bitácora de Barrenación";
				verMensaje = true;
			}
			if(!frm_regBitAvance.sbt_regVoladura.disabled){//Bitácora de Voladura
				mensaje += "\n * Bitácora de Voladura";
				verMensaje = true;
			}
			if(!frm_regBitAvance.sbt_regRezagado.disabled){//Bitácora de Rezagado
				mensaje += "\n * Bitácora de Rezagado";
				verMensaje = true;
			}
			
			//Notificar al Usuario
			if(verMensaje){
				if(!confirm(mensaje+"\nPresione ACEPTAR para Continuar o CANCELAR para Registrar las Bitácoras Faltantes"))
					validacion = 0;
				
			}
		}//Cierre de if(validacion==1)
		
		
		
	}//Cierre if(frm_regBitAvance.hdn_btnClick.value=="guardar")
								
	
	if(validacion==1)
		return true;
	else
		return false;
}//Cierre de la función valFormRegBitAvance(frm_regBitAvance)


/*Esta función calcula el avance respecto del machote y la medida*/
function calcularAvance(){
	//Obtener los datos necesarios para el calculo
	var txtMachote = document.getElementById("txt_machote").value;
	var txtMedida = document.getElementById("txt_medida").value;
	
	if(txtMachote!="" && txtMedida!=""){
		
		//Obtener los datos necesarios para el calculo
		var machote = parseFloat(txtMachote.replace(/,/g,''));
		var medida = parseFloat(txtMedida.replace(/,/g,''));
			
		//Verificar que los datos necesarios para el calculo sean proporcionados
		if(machote!=0 && medida!=0){
			//Si la media es mayor que el Machote, notificar al usuario y pedir confirmación para continuar con el calculo
			if(medida<machote){
				if(confirm("El Machote es Mayor que la Medida, ¿Esto es Correcto?")){
					//Obtener el Avance
					var avance = medida - machote;
					//Colocar el avance en la Caja de Texto
					formatCurrency(avance,'txt_avance');
				}
				else{
					//Vaciar los campos para que vuelvan a ser ingresados y colocar el foco en la caja de machote
					document.getElementById("txt_machote").value = "";
					document.getElementById("txt_machote").focus();
					document.getElementById("txt_medida").value = "";
					document.getElementById("txt_avance").value = "";
				}
			}//Cierre if(media>machote){
			else{
				//Obtener el Avance
				var avance = medida - machote;
				//Colocar el avance en la Caja de Texto
				formatCurrency(avance,'txt_avance');
			}
		}//Cierre if(machote!=0 && medida!=0)			
	}//Cierre if(txtMachote!="" && txtMedida!="")
}//Cierre de la función calcularAvance()


/*Esta funcion notifica al usuario sobre el abandono del registro de la Bitacora de Avance y la perdida de información que esto puede generar*/
function salirRegBitacora(idBitacora,tipoBitacora,pagina){
	//Notificar al usuario sobre la salida de la pagina y posible perdida de información
	if(confirm("¿Estas Seguro que Quieres Salir?\nLa Información Registrada en las Bitácoras de Barrenación,\nVoladura y Rezagado Será Eliminada")){
		//Llamar a la funcion que borrara los registros hechos en las Bitácoras de Barrención, Voladura y Rezagado
		borrarRegistrosBitacoras(idBitacora,tipoBitacora);
		
		//redireccionar a la pagina indicada
		location.href = pagina;
	}	
}//Cierre de la función salirRegBitacora()


/*******************************************BITACORA BARRENACION CON JUMBO Y MAQUINA DE PIERNA********************************************************************/
/*Esta funcion verifica que sea seleccionado un tipo de equipo y redireciona a la pagina correspondiente*/
function valFormSeleccionarEquipo(frm_seleccionarEquipo){	
	//Verificar el tipo de equipo no este vacio
	if(frm_seleccionarEquipo.cmb_tipoEquipo.value!=""){
		//Dependiendo del equipo seleccionado, redireccionar a la página correspondiente
		if(frm_seleccionarEquipo.cmb_tipoEquipo.value=="JUMBO"){
			document.frm_seleccionarEquipo.action = "frm_regBarrJumbo.php";
		}
		else if(frm_seleccionarEquipo.cmb_tipoEquipo.value=="MAQUINA DE PIERNA"){
			document.frm_seleccionarEquipo.action = "frm_regBarrMP.php";
		}
		
		return true;
	}
	else{
		alert("Seleccionar el Tipo de Equipo para Registrar la Barrenación");
		return false;
	}
	
}//Cierre de la función valFormSeleccionarEquipo(frm_seleccionarEquipo)


/*Esta funcion valida los datos del Formulario de Registrar Barrenación con Jumbo*/
function valFormBarrenacionJumbo(frm_barrenacionJumbo){
	var aux = 0;
	//Contar la cantidad de elementos del form
	var num = frm_barrenacionJumbo.elements.length;
	//Esta variable ayudara a detectar cuando un campo obligatorio no tenga datos o el contenido del mismo no sea el correcto
	var band = 1;
	
	
	//Validar datos del Jumbero y el Ayudante
	if(frm_barrenacionJumbo.txt_jumbero.value==""){
		alert("Ingresar el Nombre del Jumbero");
		band = 0;
		frm_barrenacionJumbo.txt_jumbero.focus();
	}
	if(frm_barrenacionJumbo.cmb_turno.value=="" && band==1){
		alert("Seleccionar Turno");
		band = 0;
		frm_barrenacionJumbo.cmb_turno.focus();
	}
	if(frm_barrenacionJumbo.txt_ayudante.value=="" && band==1){
		alert("Ingresar el Nombre del Ayudante");
		band = 0;
		frm_barrenacionJumbo.txt_ayudante.focus();
	}
	
	if(frm_barrenacionJumbo.ckb_ayudante.checked){
		if(frm_barrenacionJumbo.txt_ayudante2.value=="" && band==1){
			alert("Ingresar el Nombre del Ayudante 2");
			band = 0;
			frm_barrenacionJumbo.txt_ayudante2.focus();
		}
	}
	//Validar los Datos del Equipo
	if(frm_barrenacionJumbo.cmb_equipo.value=="" && band==1){
		band = 0;
		alert("Seleccionar Equipo");
		frm_barrenacionJumbo.cmb_equipo.focus();
	}
	//Revisar si fue registrada alguna falla en el equipo	
	if(frm_barrenacionJumbo.hdn_fallasEquipo.value!="" && band==1){
		//Verificar si el Equipo seleccionado coicide con los registros hechos en la Bitacora de Fallas
		if(frm_barrenacionJumbo.cmb_equipo.value!=frm_barrenacionJumbo.hdn_fallasEquipo.value){
			band = 0;
			var msg = "El Equipo Seleccionado '"+frm_barrenacionJumbo.cmb_equipo.value+"' no Coincide con el Equipo '"+frm_barrenacionJumbo.hdn_fallasEquipo.value;
			msg += "' Registrado en la Bitacora de Fallas \nSeleccionar el Equipo Indicado para Poder Guardar el Registro ";
			
			alert(msg);	
		}
	}	
	if(frm_barrenacionJumbo.txt_HIEquipo.value=="" && band==1){
		alert("Introducir el Horómetro Inicial del Equipo");
		band = 0;
		frm_barrenacionJumbo.txt_HIEquipo.focus();
	}
	if(frm_barrenacionJumbo.txt_HFEquipo.value=="" && band==1){
		alert("Introducir el Horómetro Final del Equipo");
		band = 0;
		frm_barrenacionJumbo.txt_HFEquipo.focus();
	}
	
	
	//Validar datos del Brazo 1 del Equipo
	if(frm_barrenacionJumbo.txt_HIB1.value=="" && band==1){
		alert("Ingresar el Horómetro Inical del Brazo 1");
		band = 0;
		frm_barrenacionJumbo.txt_HIB1.focus();
	}
	if(frm_barrenacionJumbo.txt_HFB1.value=="" && band==1){
		alert("Ingresar el Horómetro Final del Brazo 1");
		band = 0;
		frm_barrenacionJumbo.txt_HFB1.focus();
	}
	
	//Validar datos del Brazo 2 del Equipo, cuando el CheckBox Brazo 2 este seleccionado
	if(frm_barrenacionJumbo.ckb_brazo2.checked){
		if(frm_barrenacionJumbo.txt_HIB2.value=="" && band==1){
			alert("Ingresar el Horómetro Inical del Brazo 2");
			band = 0;
			frm_barrenacionJumbo.txt_HIB2.focus();
		}
		if(frm_barrenacionJumbo.txt_HFB2.value=="" && band==1){
			alert("Ingresar el Horómetro Final del Brazo 2");
			band = 0;
			frm_barrenacionJumbo.txt_HFB2.focus();
		}	
	}
	for(var i = 0; i<num; i++){
		var chek = frm_barrenacionJumbo.elements[i];
		var bd = frm_barrenacionJumbo.elements[i+1];var bdbr = frm_barrenacionJumbo.elements[i+2];
		var benc = frm_barrenacionJumbo.elements[i+3];var bdes = frm_barrenacionJumbo.elements[i+4];
		var rean = frm_barrenacionJumbo.elements[i+9];var anc = frm_barrenacionJumbo.elements[i+10];
		var esca = frm_barrenacionJumbo.elements[i+11];var tb = frm_barrenacionJumbo.elements[i+12];
		for(var j = 0; j<num; j++){
			
			if(chek.name==("ckb_activarBarr"+j)){
				if(band==1 && chek.checked){
					aux++;
					//Validar la cantidad de Barrenos Dados
					if(bd.value=="" && band==1){
						alert("Ingresar el " + (j+1) + "° No. de Barrenos Dados");
						band = 0;
						bd.focus();
					}
					if(band==1){
						if(!validarEnteroConCero(bd.value,"Los " + (j+1) + "° Barrenos Dados"))
							band = 0;
					}
					//Validar la cantidad de Barrenos de Desborde
					if(bdbr.value=="" && band==1){
						alert("Ingresar el " + (j+1) + "° No. de Barrenos de Desborde");
						band = 0;
						bdbr.focus();
					}
					if(band==1){
						if(!validarEnteroConCero(bdbr.value,"Los " + (j+1) + "° Barrenos de Desborde"))
							band = 0;
					}
					//Validar la cantidad de Barrenos de Encapille
					if(benc.value=="" && band==1){
						alert("Ingresar el " + (j+1) + "° No. de Barrenos de Encapille");
						band = 0;
						benc.focus();
					}
					if(band==1){
						if(!validarEnteroConCero(benc.value,"Los " + (j+1) + "° Barrenos de Encapille"))
							band = 0;
					}
					//Validar la cantidad de Barrenos de Despate
					if(bdes.value=="" && band==1){
						alert("Ingresar el " + (j+1) + "° No. de Barrenos de Despate");
						band = 0;
						bdes.focus();
					}
					if(band==1){
						if(!validarEnteroConCero(bdes.value,"Los " + (j+1) + "° Barrenos de Despate"))
							band = 0;
					}
					//Validar la cantidad de Reanclaje
					if(rean.value=="" && band==1){
						alert("Ingresar la " + (j+1) + "° Cantidad de Reanclaje");
						band = 0;
						rean.focus();
					}
					if(band==1){
						if(!validarEnteroConCero(rean.value,"Los " + (j+1) + "° Reanclaje"))
							band = 0;
					}
					//Validar la cantidad de Anclas
					if(anc.value=="" && band==1){
						alert("Ingresar la " + (j+1) + "° Cantidad de Anclas");
						band = 0;
						anc.focus();
					}
					if(band==1){
						if(!validarEnteroConCero(anc.value,"Las " + (j+1) + "° Anclas"))
							band = 0;
					}
					//Validar la cantidad de Escareado
					if(esca.value=="" && band==1){
						alert("Ingresar la " + (j+1) + "° Cantidad de Escareado");
						band = 0;
						esca.focus();
					}
					if(band==1){
						if(!validarEnteroConCero(esca.value,"La " + (j+1) + "° Cantidad de Escareado"))
							band = 0;
					}
					//Validar la cantidad de Topes Barrenados
					if(tb.value=="" && band==1){
						alert("Ingresar la " + (j+1) + "° Cantidad de Topes Barrenados");
						band = 0;
						tb.focus();
					}
					if(band==1){
						if(!validarEnteroConCero(tb.value,"La " + (j+1) + "° Cantidad de Topes Barrenados"))
							band = 0;
					}
				}
				
				else if(frm_barrenacionJumbo.cmb_equipo.value==""){
					//Limpiar los campos para evitar el registro erroneos en la BD.
					bd.value = ""; bdbr.value = "";
					benc.value = ""; bdes.value = "";
					rean.value = ""; anc.value = "";
					esca.value = ""; tb.value = "";
				}
			}
		}
	}
	//Validar la cantidad de Barrenos Dados
	/*if(frm_barrenacionJumbo.txt_barrDados.value=="" && band==1){
		alert("Ingresar el No. de Barrenos Dados");
		band = 0;
	}
	if(band==1){
		if(!validarEnteroConCero(frm_barrenacionJumbo.txt_barrDados.value,"Los Barrenos Dados"))
			band = 0;
	}
	//Validar la cantidad de Disparos
	if(frm_barrenacionJumbo.txt_disparos.value=="" && band==1){
		alert("Ingresar el No. de Disparos");
		band = 0;
	}
	if(band==1){
		if(!validarEnteroConCero(frm_barrenacionJumbo.txt_disparos.value,"Los Disparos"))
			band = 0;
	}
	//Validar la cantidad de la longitud
	if(frm_barrenacionJumbo.txt_longitud.value=="" && band==1){
		alert("Ingresar la Longitud");
		band = 0;
	}
	if(band==1){
		if(!validarEnteroConCero(frm_barrenacionJumbo.txt_longitud.value,"La Longitud"))
			band = 0;
	}
	//Validar la cantidad de Barrenos de Desborde
	if(frm_barrenacionJumbo.txt_barrDesborde.value=="" && band==1){
		alert("Ingresar la Cantidad de Barrenos de Desborde");
		band = 0;
	}
	if(band==1){
		if(!validarEnteroConCero(frm_barrenacionJumbo.txt_barrDesborde.value,"Los Barrenos de Desborde"))
			band = 0;
	}
	//Validar la cantidad de Barrenos de Encapille
	if(frm_barrenacionJumbo.txt_barrEncapille.value=="" && band==1){
		alert("Ingresar la Cantidad de Barrenos de Encapille");
		band = 0;
	}
	if(band==1){
		if(!validarEnteroConCero(frm_barrenacionJumbo.txt_barrEncapille.value,"Los Barrenos de Encapille"))
			band = 0;
	}
	//Validar la cantidad de Barrenos de Despate
	if(frm_barrenacionJumbo.txt_barrDespate.value=="" && band==1){
		alert("Ingresar la Cantidad de Barrenos de Despate");
		band = 0;
	}
	if(band==1){
		if(!validarEnteroConCero(frm_barrenacionJumbo.txt_barrDespate.value,"Los Barrenos de Despate"))
			band = 0;
	}
	//Validar la cantidad de Reanclaje
	if(frm_barrenacionJumbo.txt_reanclaje.value=="" && band==1){
		alert("Ingresar la Cantidad de Reanclaje");
		band = 0;
	}
	if(band==1){
		if(!validarEnteroConCero(frm_barrenacionJumbo.txt_reanclaje.value,"La Cantidad de Reanclaje"))
			band = 0;
	}
	//Validar la cantidad de Coples
	if(frm_barrenacionJumbo.txt_coples.value=="" && band==1){
		alert("Ingresar la Cantidad de Coples");
		band = 0;
	}
	if(band==1){
		if(!validarEnteroConCero(frm_barrenacionJumbo.txt_coples.value,"La Cantidad de Coples"))
			band = 0;
	}
	//Validar la cantidad de Zancos
	if(frm_barrenacionJumbo.txt_zancos.value=="" && band==1){
		alert("Ingresar la Cantidad de Zancos");
		band = 0;
	}
	if(band==1){
		if(!validarEnteroConCero(frm_barrenacionJumbo.txt_zancos.value,"La Cantidad de Zancos"))
			band = 0;
	}
	//Validar la cantidad de Anclas
	if(frm_barrenacionJumbo.txt_anclas.value=="" && band==1){
		alert("Ingresar la Cantidad de Anclas");
		band = 0;
	}
	if(band==1){
		if(!validarEnteroConCero(frm_barrenacionJumbo.txt_anclas.value,"La Cantidad de Anclas"))
			band = 0;
	}
	//Validar la cantidad de Brocas Nuevas
	if(frm_barrenacionJumbo.txt_brocasNuevas.value=="" && band==1){
		alert("Ingresar la Cantidad de Brocas Nuevas");
		band = 0;
	}
	if(band==1){
		if(!validarEnteroConCero(frm_barrenacionJumbo.txt_brocasNuevas.value,"La Cantidad de Brocas Nuevas"))
			band = 0;
	}
	//Validar la cantidad de Brocas Afiladas
	if(frm_barrenacionJumbo.txt_brocasAfiladas.value=="" && band==1){
		alert("Ingresar la Cantidad de Brocas Afiladas");
		band = 0;
	}
	if(band==1){
		if(!validarEnteroConCero(frm_barrenacionJumbo.txt_brocasAfiladas.value,"La Cantidad de Brocas Afiladas"))
			band = 0;
	}*/
	
	if(band==1 && aux==0){
		alert("Se Debe Seleccionar al menos un registro de Barrenacion");
		band = 0;
	}
	
	//Si no se realizo el registro de la bitacora de Consumos, notificar al usuario y preguntar si se puede continuar.
	if(frm_barrenacionJumbo.hdn_regBitConsumos.value=="no" && band==1){
		if(!confirm("¡No se Registró la Bitácora de Consumos! \n Presione el Botón 'Aceptar' para continuar o 'Cancelar' para Agregar Registros a la Bitácora de Consumos"))
			band = 0;
	}
	
	if(band==1)
		return true;
	else
		return false;
}//Cierre de la función valFormBarrenacionJumbo(frm_barrenacionJumbo)



/*Esta funcion activa o desactiva los campos para registrar el Horómetro del Brazo No. 2 en el formulario de Bitácora de Barrenación con Jumbo*/
function activarCampos(checkBox){
	//Si 
	if(checkBox.checked){
		//Quitar el atributo de ReadOnly a las cajas de texto para el Horómetro Inicial y Final del Brazo 2.
		document.getElementById("txt_HIB2").readOnly = false;
		document.getElementById("txt_HFB2").readOnly = false;
		
		//Colocar el orden de seleccion a los campos
		document.getElementById("txt_HIB2").tabIndex = "10";
		document.getElementById("txt_HFB2").tabIndex = "11";
		
	}
	else{
		//Reactiar el atributo de ReadOnly a las cajas de texto para el Horómetro Inicial y Final del Brazo 2.
		document.getElementById("txt_HIB2").readOnly = true;
		document.getElementById("txt_HFB2").readOnly = true;
		
		//Colocar el orden de seleccion a los campos
		document.getElementById("txt_HIB2").tabIndex = "";
		document.getElementById("txt_HFB2").tabIndex = "";
	}

}//Cierre de la función activarCampos(checkBox)

/*Esta funcion activa o desactiva los campos para registrar el Horómetro del Brazo No. 2 en el formulario de Bitácora de Barrenación con Jumbo*/
function activarCamposForm(checkBox,cajaTexto){
	if(checkBox.checked){
		document.getElementById(""+cajaTexto).readOnly = false;
		document.getElementById(""+cajaTexto).value = '';
	}
	else{
		document.getElementById(""+cajaTexto).readOnly = true;
		document.getElementById(""+cajaTexto).value = '';
	}
}//Cierre de la función activarCampos(checkBox)


function valFormBarrenacionMP(frm_barrenacionMP){
	//Esta variable ayudara a detectar cuando un campo obligatorio no tenga datos o el contenido del mismo no sea el correcto
	var validacion = 1;
	
	
	//Validar los datos del personal
	if(frm_barrenacionMP.txt_perforista.value==""){
		alert("Seleccionar el Nombre del Perforista");
		validacion = 0;
	}
	if(frm_barrenacionMP.cmb_turno.value=="" && validacion==1){
		alert("Seleccionar el Turno");
		validacion = 0;
	}
	if(frm_barrenacionMP.txt_ayudante.value=="" && validacion==1){
		alert("Seleccionar el Nombre del Ayudante");
		validacion = 0;
	}
	
	
	//Validar los Datos del Equipo
	if(frm_barrenacionMP.cmb_equipo.value=="" && validacion==1){
		validacion = 0;
		alert("Seleccionar Equipo");
	}
	//Revisar si fue registrada alguna falla en el equipo	
	if(frm_barrenacionMP.hdn_fallasEquipo.value!="" && validacion==1){
		//Verificar si el Equipo seleccionado coicide con los registros hechos en la Bitacora de Fallas
		if(frm_barrenacionMP.cmb_equipo.value!=frm_barrenacionMP.hdn_fallasEquipo.value){
			validacion = 0;
			var msg = "El Equipo Seleccionado '"+frm_barrenacionMP.cmb_equipo.value+"' no Coincide con el Equipo '"+frm_barrenacionMP.hdn_fallasEquipo.value;
			msg += "' Registrado en la Bitacora de Fallas \nSeleccionar el Equipo Indicado para Poder Guardar el Registro ";
			
			alert(msg);	
		}
	}	
	if(frm_barrenacionMP.txt_HIEquipo.value=="" && validacion==1){
		alert("Introducir el Horómetro Inicial del Equipo");
		validacion = 0;
	}
	if(frm_barrenacionMP.txt_HFEquipo.value=="" && validacion==1){
		alert("Introducir el Horómetro Final del Equipo");
		validacion = 0;
	}
	
	
	//Validar los datos correspondientes a la Barrenación
	if(frm_barrenacionMP.txt_barrDados.value=="" && validacion==1){
		alert("Introducir los Barrenos Dados");
		validacion = 0;
	}
	if(validacion==1){
		if(!validarEntero(frm_barrenacionMP.txt_barrDados.value,"Los Barrenos Dados"))
			validacion = 0;
	}
	if(frm_barrenacionMP.txt_disparos.value=="" && validacion==1){
		alert("Introducir los Disparos Dados ");
		validacion = 0;
	}
	if(validacion==1){
		if(!validarEntero(frm_barrenacionMP.txt_disparos.value,"Los Disparos Dados"))
			validacion = 0;
	}
	if(frm_barrenacionMP.txt_longitud.value=="" && validacion==1){
		alert("Introducir la Cantidad de la Longitud");
		validacion = 0;
	}
	if(validacion==1){
		if(!validarEntero(frm_barrenacionMP.txt_longitud.value,"La Longitud"))
			validacion = 0;
	}
	if(frm_barrenacionMP.txt_brocasNuevas.value=="" && validacion==1){
		alert("Introducir la Cantidad de Brocas Nuevas");
		validacion = 0;
	}
	if(validacion==1){
		if(!validarEntero(frm_barrenacionMP.txt_brocasNuevas.value,"Las Brocas Nuevas"))
			validacion = 0;
	}
	if(frm_barrenacionMP.txt_brocasAfiladas.value=="" && validacion==1){
		alert("Introducir la Cantidad de Brocas Afiladas");
		validacion = 0;
	}
	if(validacion==1){
		if(!validarEntero(frm_barrenacionMP.txt_brocasAfiladas.value,"Las Brocas Afiladas"))
			validacion = 0;
	}
	if(frm_barrenacionMP.txt_barras6.value=="" && validacion==1){
		alert("Introducir la Cantidad de Barras 6");
		validacion = 0;
	}
	if(validacion==1){
		if(!validarEntero(frm_barrenacionMP.txt_barras6.value,"Las Barras 6"))
			validacion = 0;
	}
	if(frm_barrenacionMP.txt_barras8.value=="" && validacion==1){
		alert("Introducir la Cantidad de Barras 8");
		validacion = 0;
	}
	if(validacion==1){
		if(!validarEntero(frm_barrenacionMP.txt_barras6.value,"Las Barras 8"))
			validacion = 0;
	}
	if(frm_barrenacionMP.txt_anclas.value=="" && validacion==1){
		alert("Introducir la Cantidad de Anclas");
		validacion = 0;
	}
	if(validacion==1){
		if(!validarEntero(frm_barrenacionMP.txt_anclas.value,"Las Anclas"))
			validacion = 0;
	}
	
	
	
	//Si no se realizo el registro de la bitacora de Consumos, notificar al usuario y preguntar si se puede continuar.
	if(frm_barrenacionMP.hdn_regBitConsumos.value=="no" && validacion==1){
		if(!confirm("¡No se Registró la Bitácora de Consumos! \n Presione el Botón 'Aceptar' para continuar o 'Cancelar' para Agregar Registros a la Bitácora de Consumos"))
			validacion = 0;
	}
								
	
	//Regresar el resultado d ela validación
	if(validacion==1)
		return true;
	else
		return false;
				
}//Cierre de la función valFormBarrenacionMP(frm_barrenacionMP)


/**************************************************************BITACORA VOLADURA*********************************************************************************/
/*Esta funcion valida el formalio de captura de datos de la voladura*/
function valFormVoladura(frm_voladura){
	var aux = 0;
	//Contar la cantidad de elementos del form
	var num = frm_voladura.elements.length;
	//Esta variable ayudara a detectar cuando un campo obligatorio no tenga datos o el contenido del mismo no sea el correcto
	var validacion = 1;
	
	//Validar datos de los empleados
	if(frm_voladura.txt_volador.value==""){
		alert("Seleccinar el Nombre del Operador de Voladura");
		validacion = 0;
	}
	if(frm_voladura.cmb_turno.value=="" && validacion==1){
		alert("Seleccinar el Turno");
		validacion = 0;
	}
	if(frm_voladura.txt_ayudante.value=="" && validacion==1){
		alert("Seleccinar el Nombre del Ayudante de Voladura");
		validacion = 0;
	}
	
	if(frm_voladura.ckb_ayudante.checked){
		if(frm_voladura.txt_ayudante2.value=="" && validacion==1){
			alert("Ingresar el Nombre del Ayudante 2");
			validacion = 0;
		}
	}
	
	//Verificar si el usuario selecciono un equipo
	if(frm_voladura.cmb_equipo.value!="" && validacion==1){
		//Revisar si fue registrada alguna falla en el equipo	
		if(frm_voladura.hdn_fallasEquipo.value!="" && validacion==1){
			//Verificar si el Equipo seleccionado coicide con los registros hechos en la Bitacora de Fallas
			if(frm_voladura.cmb_equipo.value!=frm_voladura.hdn_fallasEquipo.value){
				validacion = 0;
				var msg = "El Equipo Seleccionado '"+frm_voladura.cmb_equipo.value+"' no Coincide con el Equipo '"+frm_voladura.hdn_fallasEquipo.value;
				msg += "' Registrado en la Bitacora de Fallas \nSeleccionar el Equipo Indicado para Poder Guardar el Registro ";
			
				alert(msg);	
			}
		}	
		
		//Verificar que sean ingresados el resto de los datos para el equipo
		if(frm_voladura.txt_HIEquipo.value=="" && validacion==1){
			alert("Ingresar el Horómetro Inicial del Equipo");
			validacion = 0;
		}
		
		if(frm_voladura.txt_HFEquipo.value=="" && validacion==1){
			alert("Ingresar el Horómetro Final del Equipo");
			validacion = 0;
		}
	} else if(frm_voladura.cmb_equipo.value=="" && validacion==1){
		alert("Seleccionar el Equipo");
		validacion = 0;
	}
	if(frm_voladura.cmb_equipo.value=="" && validacion==0){
		//Limpiar los campos para evitar el registro erroneos en la BD.
		frm_voladura.txt_HIEquipo.value = "";
		frm_voladura.txt_HFEquipo.value = "" ;
		frm_voladura.txt_HTEquipo.value = "" ;
	}
	
	for(var i = 0; i<num; i++){
		var chek = frm_voladura.elements[i];
		var disp = frm_voladura.elements[i+1];
		var dispNicho = frm_voladura.elements[i+2];
		var tc = frm_voladura.elements[i+5];
		for(var j = 0; j<num; j++){
			if(chek.name==("ckb_activarVol"+j)){
				if(validacion==1 && chek.checked){
					aux++;
					//Comparar si tiene datos Longitud de Barreno Cargado
					if(disp.value=="" && validacion==1){
						alert("Introducir la cantidad de los " + (j+1) + "° Disparos");
						validacion = 0;
					}
					if(validacion==1){
						if(!validarEntero(disp.value,"La cantidad de los " + (j+1) + "° Disparos"))
						validacion = 0;
					}
					//Comparar si tiene datos Factor de Carga
					if(dispNicho.value=="" && validacion==1){
						alert("Introducir la cantidad de los " + (j+1) + "° Disparos Nicho");
						validacion = 0;
					}
					if(validacion==1){
						if(!validarEntero(disp.value,"La cantidad de los " + (j+1) + "° Disparos Nicho"))
						validacion = 0;
					}
					//Comparar si tiene datos Tope Cargado
					if(tc.value=="" && validacion==1){
						alert("Introducir los " + (j+1) + "° Topes Cargados");
						validacion = 0;
					}
				}
				
				else if(frm_voladura.cmb_equipo.value==""){
					//Limpiar los campos para evitar el registro erroneos en la BD.
					lbc.value = "";
					fc.value = "" ;
					tc.value = "" ;
				}
			}
		}
	}
	
	//Validar datos de la Voladura
	/*if(frm_voladura.txt_longBarreno0.value=="" && validacion==1){
		alert("Introducir la Longitud del Barreno Cargado");
		validacion = 0;
	}
	if(validacion==1){
		if(!validarEntero(frm_voladura.txt_longBarreno0.value,"La Longitud del Barreno Cargado"))
			validacion = 0;
	}
	if(frm_voladura.txt_factorCarga0.value=="" && validacion==1){
		alert("Introducir el Factor de Carga");
		validacion = 0;
	}
	if(validacion==1){
		if(!validarEntero(frm_voladura.txt_factorCarga0.value,"El Factor de Carga"))
			validacion = 0;
	}
	if(frm_voladura.txt_TopesCarg0.value=="" && validacion==1){
		alert("Introducir los Topes Cargados");
		validacion = 0;
	}*/
	
	if(validacion==1 && aux==0){
		alert("Se Debe Seleccionar al menos un registro de Voladura");
		validacion = 0;
	}
	
	//Si no se realizo el registro de los Explosivos, notificar al usuario y preguntar si se puede continuar.
	if(frm_voladura.hdn_regBitExplosivos.value=="no" && validacion==1){
		if(!confirm("¡No se Registraron los Explosivos! \n Presione el Botón 'Aceptar' para continuar o 'Cancelar' para Agregar Registros de los Explosivos Utilizados"))
			validacion = 0;
	}
	
	//Regresar el resultado d ela validación
	if(validacion==1)
		return true;
	else
		return false;
				
}//Cierre de la función valFormVoladura(frm_voladura)


/*Esta función Activa las Cajas de Texto para ingresar los datos del Horómetro*/
function habilitarCajaTxt(comboBox){
	
	//Verificar que la opción seleccionada del combo sea diferente de vacia
	if(comboBox.value!=""){
		document.getElementById("txt_HIEquipo").readOnly = false;
		document.getElementById("txt_HFEquipo").readOnly = false;
	}
	else if(comboBox.value==""){//Si la opción seleccionada esta vacia, deshabilitar las cajas de Texto
		document.getElementById("txt_HIEquipo").readOnly = true;
		document.getElementById("txt_HFEquipo").readOnly = true;
	}
}


/**************************************************************BITACORA REZAGADO*********************************************************************************/
//Funcion que de forma vistosa edita el formulario para agregar o quitar informacion del mismo
function mostrarObligatorio(checkbox,tipo,num){
	if(checkbox.checked){
		if(tipo=="Tep"){
			document.getElementById("tepC").style.visibility='visible';
			document.getElementById("tepV").style.visibility='visible';
			document.getElementById("tepCu").style.visibility='visible';
			if(document.getElementById("hdn_orgTep")!=null){
				document.getElementById("cmb_origenTepetate").value=document.getElementById("hdn_orgTep").value;
				document.getElementById("cmb_destinoTepetate").value=document.getElementById("hdn_desTepetate").value;
				document.getElementById("txt_cucharonesTep").value=document.getElementById("hdn_cuchTepetate").value;
			}
			else{
				document.getElementById("cmb_origenTepetate").value="";
				document.getElementById("cmb_destinoTepetate").value="";
				document.getElementById("txt_cucharonesTep").value="";
			}
		}
		if(tipo=="Min"){
			document.getElementById("minC"+num).style.visibility='visible';
			document.getElementById("minV"+num).style.visibility='visible';
			document.getElementById("minCu"+num).style.visibility='visible';
			if(document.getElementById("hdn_orgTep")!=null){
				document.getElementById("cmb_origenMineral"+num).value=document.getElementById("hdn_orgMineral").value;
				document.getElementById("cmb_destinoMineral"+num).value=document.getElementById("hdn_desMineral").value;
				document.getElementById("txt_cucharonesMin"+num).value=document.getElementById("hdn_cuchMineral").value;
			}
			else{
				document.getElementById("cmb_origenMineral"+num).value="";
				document.getElementById("cmb_destinoMineral"+num).value="";
				document.getElementById("txt_cucharonesMin"+num).value="";
			}
		}
	}
	else{
		if(tipo=="Tep"){
			document.getElementById("tepC").style.visibility='hidden';
			document.getElementById("tepV").style.visibility='hidden';
			document.getElementById("tepCu").style.visibility='hidden';
			document.getElementById("cmb_origenTepetate").value="";
			document.getElementById("cmb_destinoTepetate").value="";
			document.getElementById("txt_cucharonesTep").value="";
		}
		if(tipo=="Min"){
			document.getElementById("minC"+num).style.visibility='hidden';
			document.getElementById("minV"+num).style.visibility='hidden';
			document.getElementById("minCu"+num).style.visibility='hidden';
			document.getElementById("cmb_origenMineral"+num).value="";
			document.getElementById("cmb_destinoMineral"+num).value="";
			document.getElementById("txt_cucharonesMin"+num).value="";
		}
	}
}

function establecerTras_Limp(no,elemento,tl,elementModif){
	//Obtener a referencia para cada elemento
	check=document.getElementById(""+elemento);
	check2=document.getElementById(""+elementModif+no);
	cucharon=document.getElementById("txt_cucharonesMin"+no);
	//Si el checkbox esta checado, se calculan los totales
	if(tl == 0){
		if(check.checked){
			check2.checked=false;
		}
	} else{
		if(cucharon.value >= 0){	
			if(check.checked && check2.checked != false){
				check2.checked=false;
			}
		} else{
			check.checked=false;
			alert("El limite de cucharones para tope limpio son 0");
		}
	}
}//Fin de function estbalecerTras_Limp(no,elemento,tl,elementModif)

/*Esta funcion valida el formulario para registrar la bitacora de rezagado*/
function valFormRegBitRezagado(frm_regBitRezagado){
	var aux = 0;
	//Contar la cantidad de elementos del form
	var num = frm_regBitRezagado.elements.length;
	//Si la variable validación mantiene su valor en 1, el proceso de validación fue satisfactorio
	var validacion = 1;
	
		
	//Validar datos del Operador
	if(frm_regBitRezagado.cmb_operador.value==""){
		validacion = 0;
		alert("Seleccionar el Operador del Equipo");
	}
	if(frm_regBitRezagado.cmd_turno.value=="" && validacion==1){
		validacion = 0;
		alert("Seleccionar el Turno");
	}
	
	
	
	//Validar los Datos del Equipo
	if(frm_regBitRezagado.cmb_equipo.value=="" && validacion==1){
		validacion = 0;
		alert("Seleccionar Equipo");
	}
	//Revisar si fue registrada alguna falla en el equipo	
	if(frm_regBitRezagado.hdn_fallasEquipo.value!="" && validacion==1){
		//Verificar si el Equipo seleccionado coicide con los registros hechos en la Bitacora de Fallas
		if(frm_regBitRezagado.cmb_equipo.value!=frm_regBitRezagado.hdn_fallasEquipo.value){
			validacion = 0;
			var msg = "El Equipo Seleccionado '"+frm_regBitRezagado.cmb_equipo.value+"' no Coincide con el Equipo '"+frm_regBitRezagado.hdn_fallasEquipo.value;
			msg += "' Registrado en la Bitacora de Fallas \nSeleccionar el Equipo Indicado para Poder Guardar el Registro ";
			
			alert(msg);	
		}
	}	
	if(frm_regBitRezagado.txt_horoIni.value=="" && validacion==1){
		validacion = 0;
		alert("Ingresar el Horómetro Inicial para el Equipo "+frm_regBitRezagado.cmb_equipo.value);
	}		
	if(frm_regBitRezagado.txt_horoFin.value=="" && validacion==1){
		validacion = 0;
		alert("Ingresar el Horómetro Final para el Equipo "+frm_regBitRezagado.cmb_equipo.value);
	}	
	
	
	/*if(validacion==1 && frm_regBitRezagado.ckb_activarTep.checked){
		//Validar los datos del Acarreo de Tepetate
		if(frm_regBitRezagado.cmb_origenTepetate.value=="" && validacion==1){
			validacion = 0;
			alert("Seleccionar el Origen del Acarreo de Tepetate");
		}
		if(frm_regBitRezagado.cmb_destinoTepetate.value=="" && validacion==1){
			validacion = 0;
			alert("Seleccionar el Destino del Acarreo de Tepetate");
		}	
		if(frm_regBitRezagado.txt_cucharonesTep.value=="" && validacion==1){
			validacion = 0;
			alert("Ingresar la Cantidad de Cucharones de Tepetate");
		}	
		if(validacion==1){
			//Verificar que el Horometro Inicial sea un numero valido
			if(!validarEntero(frm_regBitRezagado.txt_cucharonesTep.value,"La Cantidad de Cucharones de Tepetate"))
				validacion = 0;
		}
	}*/
	for(var i = 0; i<num; i++){
		var objeto = frm_regBitRezagado.elements[i];
		//var origen = frm_regBitRezagado.elements[i+1];
		//var destino = frm_regBitRezagado.elements[i+2];
		var cucharon = frm_regBitRezagado.elements[i+1];
		var trasp = frm_regBitRezagado.elements[i+2];
		var tlimp = frm_regBitRezagado.elements[i+3];
		for(var j = 0; j<num; j++){
			if(objeto.id==("ckb_activarMin"+j)){
				if(validacion==1 && objeto.checked){
					aux++;
					//Validar los datos del Acarreo de Mineral
					/*if(origen.value=="" && validacion==1){
						validacion = 0;
						alert("Seleccionar el " + (j+1) + "° Origen del Acarreo");
					}
					if(destino.value=="" && validacion==1){
						validacion = 0;
						alert("Seleccionar el " + (j+1) + "° Destino del Acarreo");
					}*/
					if(cucharon.value=="" && validacion==1){
						validacion = 0;
						alert("Ingresar la " + (j+1) + "° Cantidad de Cucharones");
					}
					if(validacion==1){
						//Verificar que el Horometro Inicial sea un numero valido
						if(!validarEntero(cucharon.value,"La " + (j+1) + "° Cantidad de Cucharones"))
							validacion = 0;
					}
					
					if((trasp.checked==false && tlimp.checked==false) && validacion==1){
						validacion = 0;
						alert("Seleccionar Traspaleo o T. Limpio para el " + (j+1) + "° registro");
					}
				}
			}
		}
	}
	/*if(validacion==1 && frm_regBitRezagado.ckb_activarMin.checked){
		//Validar los datos del Acarreo de Mineral
		if(frm_regBitRezagado.cmb_origenMineral.value=="" && validacion==1){
			validacion = 0;
			alert("Seleccionar el Origen del Acarreo de Mineral");
		}
		if(frm_regBitRezagado.cmb_destinoMineral.value=="" && validacion==1){
			validacion = 0;
			alert("Seleccionar el Destino del Acarreo de Mineral");
		}
		if(frm_regBitRezagado.txt_cucharonesMin.value=="" && validacion==1){
			validacion = 0;
			alert("Ingresar la Cantidad de Cucharones de Mineral");
		}
		if(validacion==1){
			//Verificar que el Horometro Inicial sea un numero valido
			if(!validarEntero(frm_regBitRezagado.txt_cucharonesMin.value,"La Cantidad de Cucharones de Mineral"))
				validacion = 0;
		}
	}*/
	
	/*if(validacion==1 && !frm_regBitRezagado.ckb_activarMin.checked && !frm_regBitRezagado.ckb_activarTep.checked){
		alert("Se Debe Seleccionar si es un Registro de Acarreo de Tepetate, Mineral, o Ambos");
		validacion = 0;
	}*/
	
	if(validacion==1 && aux==0){
		alert("Se Debe Seleccionar al menos un registro de Acarreo");
		validacion = 0;
	}
	
	//Si no se realizo el registro de la bitacora de Consumos, notificar al usuario y preguntar si se puede continuar.
	if(frm_regBitRezagado.hdn_regBitConsumos.value=="no" && validacion==1){
		if(!confirm("¡No se Registró la Bitácora de Consumos! \n Presione el Botón 'Aceptar' para continuar o 'Cancelar' para Agregar Registros a la Bitácora de Consumos"))
			validacion = 0;
	}
	
	
	//Emitir resultado de la validacion
	if(validacion==1)
		return true;
	else
		return false;
		
}//Cierre de la funcion valFormRegBitRezagado(frm_regBitRezagado)


/*Esta funcion verifica que las obras de origen y destino en el registro de Tepetate y Mineral, no sean las mismas, en caso de serlo notificar al usuario y pedir confirmación*/
function verificarObras(cmbOrigen,cmbDestino,msg){
	//Verificar que hayan sido seleccionados el origen y el destino
	if(cmbOrigen.value!="" && cmbDestino.value!=""){
		//Notificar al usuario cuando el Origen y el Destino sean iguales
		if(cmbOrigen.value==cmbDestino.value){
			//Si el Usuario cancela, regresamos los combos a su valor vacio
			if(!confirm("El Origen y Destino en el registro de "+msg+" son el Mismo\nPresione 'Aceptar' para Continuar\nPresione 'Cancelar' para Seleccionar Otras Ubicaciones")){
				//Vaciar los valores de los combos				
				cmbOrigen.value = "";
				cmbDestino.value = "";
			}
		}
	}
}//Cierre verificarObras(cmbOrigen,cmbDestino,msg)


//Funcion que permite agregar una nueva opcion, no existente a un combo box (Combo de Ubicacion en el Registro de Rezagado)
function agregarNvaUbicacion(comboBox){
	//Si la opcion seleccionada es agregar nueva unidad ejecutar el siguiete codigo
	if(comboBox.value=="NUEVA"){
		var nvaOpcion = "";		
		var valCajaPrompt = "Nueva Ubicación...";
		do{
			var condicion = false;
			
			nvaOpcion = prompt("Introducir Nueva Ubicación",valCajaPrompt);			
			
			if(nvaOpcion=="Nueva Ubicación..." ||  nvaOpcion=="")
				condicion = true;
			else if(nvaOpcion!=null){								
				//Verificar que el tamaño de la nueva opción no exceda los 30 caracteres
				if(nvaOpcion.length>40){
					alert("El Nombre de la Obra Excede el Tamaño de 40 Caracteres Permitidos");
					valCajaPrompt = nvaOpcion.substring(0,40);
					condicion = true;
				}
				else
					condicion = false;
			}
		}while(condicion);
		
		//Si el usuario presiono calncelar no se relaiza ninguan actividad de lo contrario asignar la nueva opcion al combo
		if(nvaOpcion!=null){
			//Convertir a mayusculas la opcion dada
			nvaOpcion = nvaOpcion.toUpperCase();
			//variable que nos ayudara a saber si la nueva opcion ya esta registrada en el combo
			var existe = 0;
			
			
			//verificar que la nueva opcion no se encuentre dentro de las opciones actuales del combo
			for(i=0; i<comboBox.length; i++){
				
				if(comboBox.options[i].value==nvaOpcion)
					existe = 1;
			} //FIN for(i=0; i<comboBox.length; i++)
			
			
			//Si la nva opcion no esta registrada agregarla como una adicional y preseleccionarla
			if(existe==0){
				//Agregar al final la nueva opcion seleccionada
				comboBox.length++;
				comboBox.options[comboBox.length-1].text = nvaOpcion;
				comboBox.options[comboBox.length-1].value = nvaOpcion;
				//Preseleccionar la opcion agregada
				comboBox.options[comboBox.length-1].selected = true;
			} // FIN if(existe==0)
			
			else{
				alert("La Ubicación Ingresada ya esta Registrada \n en las Opciones de la Lista de Ubicaciones");
				comboBox.value = nvaOpcion;
			}
			
		}// FIN if(nvaMedida!= null)
		
		else if(nvaOpcion==null){
			comboBox.value = "";	
		}
	}// FIN if(comboBox.value=="NUEVA")
	
}//Cierre de la función agregarNvaUbicacion(comboBox)


/***************************************************************************************************************************************************************/
/************************************************************** MODIFICAR BITACORAS ****************************************************************************/
/***************************************************************************************************************************************************************/


/***********************************************************MODIFICAR BITACORA AVANCE***************************************************************************/
/*Esta función valida que los campos necesarios para el formulario frm_consultarMezcla esten completados  por fecha*/
function valFormSeleccionarRegBitAvance(frm_seleccionarRegBitAvance){
	//Variable bandera que permite revisar si la validacion fue exitosa
	var band=1;
		
	//verificar que la fecha de inicio no sea mayor que la fecha fin
	if(!valFormFechasReq(frm_seleccionarRegBitAvance.txt_fechaIni.value,frm_seleccionarRegBitAvance.txt_fechaFin.value) && band==1)
		band=0;
	
	if (band==1)
		return true;
	else
		return false;
}


/*Esta funcion valida que sea seleccionad un registro de la Bitácora de Avance para ser modificado o complementado*/
function valFormSelecRegistroBitAvance(frm_selecRegistroBitAvance){
	//Si el valor de la variable "validacion" se mantiene en 0, entonces el formulario no paso el proceso de validacion
	var validacion = 0;	
			
	//Si no esta definido el tamaño del RadioButton, significa que solo tiene una opcion
	if(frm_selecRegistroBitAvance.rdb_idBitAvance.length==undefined){
		//Revisar si la única opcion del RadioButton fue seleccionada
		if(frm_selecRegistroBitAvance.rdb_idBitAvance.checked){
			//Activiar la varibale para indicar que una opcion fue seleccionada
			validacion = 1;
		}
	}
	//Evaluar el RadioButton cuando tenga 2 o mas opciones
	else if(frm_selecRegistroBitAvance.rdb_idBitAvance.length>=2){
		//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
		for(i=0;i<frm_selecRegistroBitAvance.rdb_idBitAvance.length;i++){
			if(frm_selecRegistroBitAvance.rdb_idBitAvance[i].checked){
				//Activiar la variable para indicar que una opcion fue seleccionada
				validacion = 1;
			}
		}
	}		
	
	//Si no fue seleccionado ninguna opcion, notificar al usuario
	if(validacion==0)
		alert("Seleccionar un Registro para Poder Modificarlo o Complementarlo");
	
	
	if(validacion==1)
		return true;
	else
		return false;
}//Cierre de la funcion valFormSelecRegistroBitAvance(frm_selecRegistroBitAvance)


/*Esta funcion valida que los datos del Formulario de la Bitácora de Avance sean proporcionados cuando ésta es actualizada*/
function valFormModBitAvance(frm_modBitAvance){
	//Esta variable ayudará a determinar si el proceso de validación fue realizado con éxito
	var validacion = 1;
	
	//Verificar que el boton seleccionado sea el de ACTUALIZAR para validar los datos del Formulario
	if(frm_modBitAvance.hdn_btnClick.value=="actualizar"){
		
		if(frm_modBitAvance.cmb_lugar.value==""){
			alert("Seleccionar el Lugar");
			validacion = 0;
		}
		
		//Validar la cantidad del Machote
		if(frm_modBitAvance.txt_machote.value=="" && validacion==1){
			alert("Introducir la Cantidad del Machote");
			validacion = 0;
		}
		if(validacion==1){
			if(!validarEntero(frm_modBitAvance.txt_machote.value.replace(/,/g,''),"La Cantidad del Machote"))
				validacion = 0;
		}		
		
		//Validar la Medida
		if(frm_modBitAvance.txt_medida.value=="" && validacion==1){
			alert("Introducir la Medida");
			validacion = 0;
		}
		if(validacion==1){
			if(!validarEntero(frm_modBitAvance.txt_medida.value.replace(/,/g,''),"La Medida"))
				validacion = 0;
		}
				
		//Validar el avance
		if(frm_modBitAvance.txt_avance.value=="" && validacion==1){
			alert("Introducir el Avance");
			validacion = 0;
		}	
		if(validacion==1){
			if(!validarEntero(frm_modBitAvance.txt_avance.value.replace(/,/g,''),"El Avance"))
				validacion = 0;
		}
											
	}//Cierre if(frm_modBitAvance.hdn_btnClick.value=="actualizar")
								
	
	if(validacion==1)
		return true;
	else
		return false;
}//Cierre de la función valFormModBitAvance(frm_modBitAvance)


/*********************************************************MODIFICAR BITACORA BARRENACION***************************************************************************/
/*Esta funcion valida los datos del Formulario de Modificar Barrenación con Jumbo*/
function valFormModBitBarrenacionJumbo(frm_modBitBarrenacionJumbo){
	/*//Esta variable ayudara a detectar cuando un campo obligatorio no tenga datos o el contenido del mismo no sea el correcto
	var band = 1;
	
	
	//Validar datos del Jumbero y el Ayudante
	if(frm_modBitBarrenacionJumbo.txt_jumbero.value==""){
		alert("Ingresar el Nombre del Jumbero");
		band = 0;
	}
	if(frm_modBitBarrenacionJumbo.cmb_turno.value=="" && band==1){
		alert("Seleccionar Turno");
		band = 0;
	}	
	if(frm_modBitBarrenacionJumbo.txt_ayudante.value=="" && band==1){
		alert("Ingresar el Nombre del Ayudante");
		band = 0;
	}
	
			
	//Validar los Datos del Equipo
	if(frm_modBitBarrenacionJumbo.cmb_equipo.value=="" && band==1){
		band = 0;
		alert("Seleccionar Equipo");
	}		
	if(frm_modBitBarrenacionJumbo.txt_HIEquipo.value=="" && band==1){
		alert("Introducir el Horómetro Inicial del Equipo");
		band = 0;
	}
	if(frm_modBitBarrenacionJumbo.txt_HFEquipo.value=="" && band==1){
		alert("Introducir el Horómetro Final del Equipo");
		band = 0;
	}
	
	
	//Validar datos del Brazo 1 del Equipo
	if(frm_modBitBarrenacionJumbo.txt_HIB1.value=="" && band==1){

		alert("Ingresar el Horómetro Inical del Brazo 1");
		band = 0;
	}
	if(frm_modBitBarrenacionJumbo.txt_HFB1.value=="" && band==1){
		alert("Ingresar el Horómetro Final del Brazo 1");
		band = 0;
	}
	
	//Validar datos del Brazo 2 del Equipo, cuando el CheckBox Brazo 2 este seleccionado
	if(frm_modBitBarrenacionJumbo.ckb_brazo2.checked){
		if(frm_modBitBarrenacionJumbo.txt_HIB2.value=="" && band==1){
			alert("Ingresar el Horómetro Inical del Brazo 2");
			band = 0;
		}
		if(frm_modBitBarrenacionJumbo.txt_HFB2.value=="" && band==1){
			alert("Ingresar el Horómetro Final del Brazo 2");
			band = 0;
		}	
	}
	
	
	//Validar la cantidad de Barrenos Dados
	if(frm_modBitBarrenacionJumbo.txt_barrDados.value=="" && band==1){
		alert("Ingresar el No. de Barrenos Dados");
		band = 0;
	}
	if(band==1){
		if(!validarEnteroConCero(frm_modBitBarrenacionJumbo.txt_barrDados.value,"Los Barrenos Dados"))
			band = 0;
	}
	//Validar la cantidad de Disparos
	if(frm_modBitBarrenacionJumbo.txt_disparos.value=="" && band==1){
		alert("Ingresar el No. de Disparos");
		band = 0;
	}
	if(band==1){
		if(!validarEnteroConCero(frm_modBitBarrenacionJumbo.txt_disparos.value,"Los Disparos"))
			band = 0;
	}
	//Validar la cantidad de la longitud
	if(frm_modBitBarrenacionJumbo.txt_longitud.value=="" && band==1){
		alert("Ingresar la Longitud");
		band = 0;
	}
	if(band==1){
		if(!validarEnteroConCero(frm_modBitBarrenacionJumbo.txt_longitud.value,"La Longitud"))
			band = 0;
	}
	//Validar la cantidad de Barrenos de Desborde
	if(frm_modBitBarrenacionJumbo.txt_barrDesborde.value=="" && band==1){
		alert("Ingresar la Cantidad de Barrenos de Desborde");
		band = 0;
	}
	if(band==1){
		if(!validarEnteroConCero(frm_modBitBarrenacionJumbo.txt_barrDesborde.value,"Los Barrenos de Desborde"))
			band = 0;
	}
	//Validar la cantidad de Barrenos de Encapille
	if(frm_modBitBarrenacionJumbo.txt_barrEncapille.value=="" && band==1){
		alert("Ingresar la Cantidad de Barrenos de Encapille");
		band = 0;
	}
	if(band==1){
		if(!validarEnteroConCero(frm_modBitBarrenacionJumbo.txt_barrEncapille.value,"Los Barrenos de Encapille"))
			band = 0;
	}
	//Validar la cantidad de Barrenos de Despate
	if(frm_modBitBarrenacionJumbo.txt_barrDespate.value=="" && band==1){
		alert("Ingresar la Cantidad de Barrenos de Despate");
		band = 0;
	}
	if(band==1){
		if(!validarEnteroConCero(frm_modBitBarrenacionJumbo.txt_barrDespate.value,"Los Barrenos de Despate"))
			band = 0;
	}
	//Validar la cantidad de Reanclaje
	if(frm_modBitBarrenacionJumbo.txt_reanclaje.value=="" && band==1){
		alert("Ingresar la Cantidad de Reanclaje");
		band = 0;
	}
	if(band==1){
		if(!validarEnteroConCero(frm_modBitBarrenacionJumbo.txt_reanclaje.value,"La Cantidad de Reanclaje"))
			band = 0;
	}
	//Validar la cantidad de Coples
	if(frm_modBitBarrenacionJumbo.txt_coples.value=="" && band==1){
		alert("Ingresar la Cantidad de Coples");
		band = 0;
	}
	if(band==1){
		if(!validarEnteroConCero(frm_modBitBarrenacionJumbo.txt_coples.value,"La Cantidad de Coples"))
			band = 0;
	}
	//Validar la cantidad de Zancos
	if(frm_modBitBarrenacionJumbo.txt_zancos.value=="" && band==1){
		alert("Ingresar la Cantidad de Zancos");
		band = 0;
	}
	if(band==1){
		if(!validarEnteroConCero(frm_modBitBarrenacionJumbo.txt_zancos.value,"La Cantidad de Zancos"))
			band = 0;
	}
	//Validar la cantidad de Anclas
	if(frm_modBitBarrenacionJumbo.txt_anclas.value=="" && band==1){
		alert("Ingresar la Cantidad de Anclas");
		band = 0;
	}
	if(band==1){
		if(!validarEnteroConCero(frm_modBitBarrenacionJumbo.txt_anclas.value,"La Cantidad de Anclas"))
			band = 0;
	}
	//Validar la cantidad de Brocas Nuevas
	if(frm_modBitBarrenacionJumbo.txt_brocasNuevas.value=="" && band==1){
		alert("Ingresar la Cantidad de Brocas Nuevas");
		band = 0;
	}
	if(band==1){
		if(!validarEnteroConCero(frm_modBitBarrenacionJumbo.txt_brocasNuevas.value,"La Cantidad de Brocas Nuevas"))
			band = 0;
	}
	//Validar la cantidad de Brocas Afiladas
	if(frm_modBitBarrenacionJumbo.txt_brocasAfiladas.value=="" && band==1){
		alert("Ingresar la Cantidad de Brocas Afiladas");
		band = 0;
	}
	if(band==1){
		if(!validarEnteroConCero(frm_modBitBarrenacionJumbo.txt_brocasAfiladas.value,"La Cantidad de Brocas Afiladas"))
			band = 0;
	}
	
	//Si no se realizo el registro de la bitacora de Consumos, notificar al usuario y preguntar si se puede continuar.
	if(frm_modBitBarrenacionJumbo.hdn_regBitConsumos.value=="no" && band==1){
		if(!confirm("¡No se Modificó la Bitácora de Consumos! \n Presione el Botón 'Aceptar' para continuar o 'Cancelar' para Modificar Registros a la Bitácora de Consumos"))
			band = 0;
	}
	
	if(band==1)
		return true;
	else
		return false;*/
	
	var aux = 0;
	//Contar la cantidad de elementos del form
	var num = frm_modBitBarrenacionJumbo.elements.length;
	//Esta variable ayudara a detectar cuando un campo obligatorio no tenga datos o el contenido del mismo no sea el correcto
	var band = 1;
	
	
	//Validar datos del Jumbero y el Ayudante
	if(frm_modBitBarrenacionJumbo.txt_jumbero.value==""){
		alert("Ingresar el Nombre del Jumbero");
		band = 0;
	}
	if(frm_modBitBarrenacionJumbo.cmb_turno.value=="" && band==1){
		alert("Seleccionar Turno");
		band = 0;
	}
	if(frm_modBitBarrenacionJumbo.txt_ayudante.value=="" && band==1){
		alert("Ingresar el Nombre del Ayudante");
		band = 0;
	}
	
	//Validar los Datos del Equipo
	if(frm_modBitBarrenacionJumbo.cmb_equipo.value=="" && band==1){
		band = 0;
		alert("Seleccionar Equipo");
	}
	
	if(frm_modBitBarrenacionJumbo.txt_HIEquipo.value=="" && band==1){
		alert("Introducir el Horómetro Inicial del Equipo");
		band = 0;
	}
	if(frm_modBitBarrenacionJumbo.txt_HFEquipo.value=="" && band==1){
		alert("Introducir el Horómetro Final del Equipo");
		band = 0;
	}
	
	
	//Validar datos del Brazo 1 del Equipo
	if(frm_modBitBarrenacionJumbo.txt_HIB1.value=="" && band==1){

		alert("Ingresar el Horómetro Inical del Brazo 1");
		band = 0;
	}
	if(frm_modBitBarrenacionJumbo.txt_HFB1.value=="" && band==1){
		alert("Ingresar el Horómetro Final del Brazo 1");
		band = 0;
	}
	
	//Validar datos del Brazo 2 del Equipo, cuando el CheckBox Brazo 2 este seleccionado
	if(frm_modBitBarrenacionJumbo.ckb_brazo2.checked){
		if(frm_modBitBarrenacionJumbo.txt_HIB2.value=="" && band==1){
			alert("Ingresar el Horómetro Inical del Brazo 2");
			band = 0;
		}
		if(frm_modBitBarrenacionJumbo.txt_HFB2.value=="" && band==1){
			alert("Ingresar el Horómetro Final del Brazo 2");
			band = 0;
		}	
	}
	for(var i = 0; i<num; i++){
		var chek = frm_modBitBarrenacionJumbo.elements[i];
		var bd = frm_modBitBarrenacionJumbo.elements[i+1];var bdes = frm_modBitBarrenacionJumbo.elements[i+2];
		var benc = frm_modBitBarrenacionJumbo.elements[i+3];var bdtp = frm_modBitBarrenacionJumbo.elements[i+4];
		var reanc = frm_modBitBarrenacionJumbo.elements[i+9];var anc = frm_modBitBarrenacionJumbo.elements[i+10];
		var esca = frm_modBitBarrenacionJumbo.elements[i+11];var tb = frm_modBitBarrenacionJumbo.elements[i+12];
		for(var j = 0; j<num; j++){
			
			if(chek.name==("ckb_activarBarr"+j)){
				if(band==1 && chek.checked){
					aux++;
					//Validar la cantidad de Barrenos Dados
					if(bd.value=="" && band==1){
						alert("Ingresar el " + (j+1) + "° No. de Barrenos Dados");
						band = 0;
					}
					if(band==1){
						if(!validarEnteroConCero(bd.value,"Los " + (j+1) + "° Barrenos Dados"))
							band = 0;
					}
					//Validar la cantidad de Barrenos de Desborde
					if(bdes.value=="" && band==1){
						alert("Ingresar la " + (j+1) + "° Cantidad de Barrenos de Desborde");
						band = 0;
					}
					if(band==1){
						if(!validarEnteroConCero(bdes.value,"Los " + (j+1) + "° Barrenos de Desborde"))
							band = 0;
					}
					//Validar la cantidad de Barrenos de Encapille
					if(benc.value=="" && band==1){
						alert("Ingresar la " + (j+1) + "° Cantidad de Barrenos de Encapille");
						band = 0;
					}
					if(band==1){
						if(!validarEnteroConCero(benc.value,"Los " + (j+1) + "° Barrenos de Encapille"))
							band = 0;
					}
					//Validar la cantidad de Barrenos de Despate
					if(bdtp.value=="" && band==1){
						alert("Ingresar la " + (j+1) + "° Cantidad de Barrenos de Despate");
						band = 0;
					}
					if(band==1){
						if(!validarEnteroConCero(bdtp.value,"Los " + (j+1) + "° Barrenos de Despate"))
							band = 0;
					}
					//Validar la cantidad de Reanclaje
					if(reanc.value=="" && band==1){
						alert("Ingresar la " + (j+1) + "° Cantidad de Reanclaje");
						band = 0;
					}
					if(band==1){
						if(!validarEnteroConCero(reanc.value,"La " + (j+1) + "° Cantidad de Reanclaje"))
							band = 0;
					}
					//Validar la cantidad de Anclas
					if(anc.value=="" && band==1){
						alert("Ingresar la " + (j+1) + "° Cantidad de Anclas");
						band = 0;
					}
					if(band==1){
						if(!validarEnteroConCero(anc.value,"La " + (j+1) + "° Cantidad de Anclas"))
						band = 0;
					}
					//Validar la cantidad de Escareado
					if(esca.value=="" && band==1){
						alert("Ingresar la " + (j+1) + "° Cantidad de Escareado");
						band = 0;
					}
					if(band==1){
						if(!validarEnteroConCero(esca.value,"La " + (j+1) + "° Cantidad de Escareado"))
							band = 0;
					}
					//Validar la cantidad de Topes Barrenados
					if(tb.value=="" && band==1){
						alert("Ingresar la " + (j+1) + "° Cantidad de Topes Barrenados");
						band = 0;
					}
					if(band==1){
						if(!validarEnteroConCero(tb.value,"La " + (j+1) + "° Cantidad de Topes Barrenados"))
							band = 0;
					}
				}
				
				else if(frm_modBitBarrenacionJumbo.cmb_equipo.value==""){
					//Limpiar los campos para evitar el registro erroneos en la BD.
					bd.value = ""; disp.value = "";
					lo.value = ""; bdes.value = "";
					benc.value = ""; bdtp.value = "";
					reanc.value = ""; cop.value = "";
					zan.value = ""; anc.value = "";
					esca.value = ""; tb.value = "";
				}
			}
		}
	}
	
	if(band==1 && aux==0){
		alert("Se Debe Seleccionar al menos un registro de Barrenacion");
		band = 0;
	}
	
	//Si no se realizo el registro de la bitacora de Consumos, notificar al usuario y preguntar si se puede continuar.
	if(frm_modBitBarrenacionJumbo.hdn_regBitConsumos.value=="no" && band==1){
		if(!confirm("¡No se Registró la Bitácora de Consumos! \n Presione el Botón 'Aceptar' para continuar o 'Cancelar' para Agregar Registros a la Bitácora de Consumos"))
			band = 0;
	}
	
	if(band==1)
		return true;
	else
		return false;
}//Cierre de la función valFormModBitBarrenacionJumbo(frm_modBitBarrenacionJumbo)


/*Esta función valida el formulario donde se modifica la información de la Bitácora de Barrecación con Maquina de Pierna*/
function valFormModBarrMP(frm_modBarrMP){
	//Esta variable ayudara a detectar cuando un campo obligatorio no tenga datos o el contenido del mismo no sea el correcto
	var validacion = 1;
	
	
	//Validar los datos del personal
	if(frm_modBarrMP.txt_perforista.value==""){
		alert("Seleccionar el Nombre del Perforista");
		validacion = 0;
	}
	if(frm_modBarrMP.cmb_turno.value=="" && validacion==1){
		alert("Seleccionar el Turno");
		validacion = 0;
	}
	if(frm_modBarrMP.txt_ayudante.value=="" && validacion==1){
		alert("Seleccionar el Nombre del Ayudante");
		validacion = 0;
	}
	
	
	//Validar los Datos del Equipo
	if(frm_modBarrMP.cmb_equipo.value=="" && validacion==1){
		validacion = 0;
		alert("Seleccionar Equipo");
	}
		
	if(frm_modBarrMP.txt_HIEquipo.value=="" && validacion==1){
		alert("Introducir el Horómetro Inicial del Equipo");
		validacion = 0;
	}
	if(frm_modBarrMP.txt_HFEquipo.value=="" && validacion==1){
		alert("Introducir el Horómetro Final del Equipo");
		validacion = 0;
	}
	
	
	//Validar los datos correspondientes a la Barrenación
	if(frm_modBarrMP.txt_barrDados.value=="" && validacion==1){
		alert("Introducir los Barrenos Dados");
		validacion = 0;
	}
	if(validacion==1){
		if(!validarEntero(frm_modBarrMP.txt_barrDados.value,"Los Barrenos Dados"))
			validacion = 0;
	}
	if(frm_modBarrMP.txt_disparos.value=="" && validacion==1){
		alert("Introducir los Disparos Dados ");
		validacion = 0;
	}
	if(validacion==1){
		if(!validarEntero(frm_modBarrMP.txt_disparos.value,"Los Disparos Dados"))
			validacion = 0;
	}
	if(frm_modBarrMP.txt_longitud.value=="" && validacion==1){
		alert("Introducir la Cantidad de la Longitud");
		validacion = 0;
	}
	if(validacion==1){
		if(!validarEntero(frm_modBarrMP.txt_longitud.value,"La Longitud"))
			validacion = 0;
	}
	if(frm_modBarrMP.txt_brocasNuevas.value=="" && validacion==1){
		alert("Introducir la Cantidad de Brocas Nuevas");
		validacion = 0;
	}
	if(validacion==1){
		if(!validarEntero(frm_modBarrMP.txt_brocasNuevas.value,"Las Brocas Nuevas"))
			validacion = 0;
	}
	if(frm_modBarrMP.txt_brocasAfiladas.value=="" && validacion==1){
		alert("Introducir la Cantidad de Brocas Afiladas");
		validacion = 0;
	}
	if(validacion==1){
		if(!validarEntero(frm_modBarrMP.txt_brocasAfiladas.value,"Las Brocas Afiladas"))
			validacion = 0;
	}
	if(frm_modBarrMP.txt_barras6.value=="" && validacion==1){
		alert("Introducir la Cantidad de Barras 6");
		validacion = 0;
	}
	if(validacion==1){
		if(!validarEntero(frm_modBarrMP.txt_barras6.value,"Las Barras 6"))
			validacion = 0;
	}
	if(frm_modBarrMP.txt_barras8.value=="" && validacion==1){
		alert("Introducir la Cantidad de Barras 8");
		validacion = 0;
	}
	if(validacion==1){
		if(!validarEntero(frm_modBarrMP.txt_barras6.value,"Las Barras 8"))
			validacion = 0;
	}
	if(frm_modBarrMP.txt_anclas.value=="" && validacion==1){
		alert("Introducir la Cantidad de Anclas");
		validacion = 0;
	}
	if(validacion==1){
		if(!validarEntero(frm_modBarrMP.txt_anclas.value,"Las Anclas"))
			validacion = 0;
	}
	
	
	
	//Si no se realizo el registro de la bitacora de Consumos, notificar al usuario y preguntar si se puede continuar.
	if(frm_modBarrMP.hdn_regBitConsumos.value=="no" && validacion==1){
		if(!confirm("¡No se Modificó la Bitácora de Consumos! \n Presione el Botón 'Aceptar' para continuar o 'Cancelar' para Agregar Registros a la Bitácora de Consumos"))
			validacion = 0;
	}
								
	
	//Regresar el resultado de la validación
	if(validacion==1)
		return true;
	else
		return false;
				
}//Cierre de la función valFormModBarrMP(frm_modBarrMP)


/*********************************************************MODIFICAR BITACORA VOLADURA***************************************************************************/
/*Esta funcion valida el formalio donde se modifican los datos de la voladura*/
function valFormModVoladura(frm_modVoladura){
	//Esta variable ayudara a detectar cuando un campo obligatorio no tenga datos o el contenido del mismo no sea el correcto
	/*var validacion = 1;
	
	//Validar datos de los empleados
	if(frm_modVoladura.txt_volador.value==""){
		alert("Seleccinar el Nombre del Operador de Voladura");
		validacion = 0;
	}
	if(frm_modVoladura.cmb_turno.value=="" && validacion==1){
		alert("Seleccinar el Turno");
		validacion = 0;
	}
	if(frm_modVoladura.txt_ayudante.value=="" && validacion==1){
		alert("Seleccinar el Nombre del Ayudante de Voladura");
		validacion = 0;
	}
	
	//Verificar si el usuario selecciono un equipo
	if(frm_modVoladura.cmb_equipo.value!="" && validacion==1){		
		
		//Verificar que sean ingresados el resto de los datos para el equipo
		if(frm_modVoladura.txt_HIEquipo.value=="" && validacion==1){
			alert("Ingresar el Horómetro Inicial del Equipo");
			validacion = 0;
		}
		
		if(frm_modVoladura.txt_HFEquipo.value=="" && validacion==1){
			alert("Ingresar el Horómetro Final del Equipo");
			validacion = 0;
		}
	}
	else if(frm_modVoladura.cmb_equipo.value==""){
		//Limpiar los campos para evitar el registro erroneos en la BD.
		frm_modVoladura.txt_HIEquipo.value = "";
		frm_modVoladura.txt_HFEquipo.value = "" ;
		frm_modVoladura.txt_HTEquipo.value = "" ;
	}
	
	//Validar datos de la Voladura
	if(frm_modVoladura.txt_longBarreno.value=="" && validacion==1){
		alert("Introducir la Longitud del Barreno Cargado");
		validacion = 0;
	}
	if(validacion==1){
		if(!validarEntero(frm_modVoladura.txt_longBarreno.value,"La Longitud del Barreno Cargado"))
			validacion = 0;
	}
	if(frm_modVoladura.txt_factorCarga.value=="" && validacion==1){
		alert("Introducir el Factor de Carga");
		validacion = 0;
	}
	if(validacion==1){
		if(!validarEntero(frm_modVoladura.txt_factorCarga.value,"El Factor de Carga"))
			validacion = 0;
	}
	
	
	//Si no se realizo el registro de los Explosivos, notificar al usuario y preguntar si se puede continuar.
	if(frm_modVoladura.hdn_regBitExplosivos.value=="no" && validacion==1){
		if(!confirm("¡No se Registraron los Explosivos! \n Presione el Botón 'Aceptar' para continuar o 'Cancelar' para Agregar Registros de los Explosivos Utilizados"))
			validacion = 0;
	}
				
	
	//Regresar el resultado d ela validación
	if(validacion==1)
		return true;
	else
		return false;*/
	
	var aux = 0;
	//Contar la cantidad de elementos del form
	var num = frm_modVoladura.elements.length;
	//Esta variable ayudara a detectar cuando un campo obligatorio no tenga datos o el contenido del mismo no sea el correcto
	var validacion = 1;
	
	//Validar datos de los empleados
	if(frm_modVoladura.txt_volador.value==""){
		alert("Seleccinar el Nombre del Operador de Voladura");
		validacion = 0;
	}
	if(frm_modVoladura.cmb_turno.value=="" && validacion==1){
		alert("Seleccinar el Turno");
		validacion = 0;
	}
	if(frm_modVoladura.txt_ayudante.value=="" && validacion==1){
		alert("Seleccinar el Nombre del Ayudante de Voladura");
		validacion = 0;
	}
		
	for(var i = 0; i<num; i++){
		var chek = frm_modVoladura.elements[i];
		var disp = frm_modVoladura.elements[i+1];
		var dispNicho = frm_modVoladura.elements[i+2];
		var tc = frm_modVoladura.elements[i+5];
		for(var j = 0; j<num; j++){
			if(chek.name==("ckb_activarVol"+j)){
				if(validacion==1 && chek.checked){
					aux++;
					//Comparar si tiene datos Longitud de Barreno Cargado
					if(disp.value=="" && validacion==1){
						alert("Introducir la cantidad de los " + (j+1) + "° Disparos");
						validacion = 0;
					}
					if(validacion==1){
						if(!validarEntero(disp.value,"La cantidad de los " + (j+1) + "° Disparos"))
						validacion = 0;
					}
					//Comparar si tiene datos Factor de Carga
					if(dispNicho.value=="" && validacion==1){
						alert("Introducir la cantidad de los " + (j+1) + "° Disparos Nicho");
						validacion = 0;
					}
					if(validacion==1){
						if(!validarEntero(disp.value,"La cantidad de los " + (j+1) + "° Disparos Nicho"))
						validacion = 0;
					}
					//Comparar si tiene datos Tope Cargado
					if(tc.value=="" && validacion==1){
						alert("Introducir los " + (j+1) + "° Topes Cargados");
						validacion = 0;
					}
				}
			}
		}
	}
	
	if(validacion==1 && aux==0){
		alert("Se Debe Seleccionar al menos un registro de Voladura");
		validacion = 0;
	}
	
	//Si no se realizo el registro de los Explosivos, notificar al usuario y preguntar si se puede continuar.
	if(frm_modVoladura.hdn_regBitExplosivos.value=="no" && validacion==1){
		if(!confirm("¡No se Registraron los Explosivos! \n Presione el Botón 'Aceptar' para continuar o 'Cancelar' para Agregar Registros de los Explosivos Utilizados"))
			validacion = 0;
	}
	
	//Regresar el resultado d ela validación
	if(validacion==1)
		return true;
	else
		return false;
}//Cierre de la función valFormModVoladura(frm_modVoladura)


/*********************************************************MODIFICAR BITACORA REZAGADO***************************************************************************/
/*Esta función valida el formulario de Modificar Datos del Rezagado */
function valFormModBitRezagado(frm_modBitRezagado){
	//Si la variable validación mantiene su valor en 1, el proceso de validación fue satisfactorio
	/*var validacion = 1;
	
		
	//Validar datos del Operador
	if(frm_modBitRezagado.cmb_operador.value==""){
		validacion = 0;
		alert("Seleccionar el Operador del Equipo");
	}
	if(frm_modBitRezagado.cmd_turno.value=="" && validacion==1){
		validacion = 0;
		alert("Seleccionar el Turno");
	}
	
	
	
	//Validar los Datos del Equipo
	if(frm_modBitRezagado.cmb_equipo.value=="" && validacion==1){
		validacion = 0;
		alert("Seleccionar Equipo");
	}		
	if(frm_modBitRezagado.txt_horoIni.value=="" && validacion==1){
		validacion = 0;
		alert("Ingresar el Horómetro Inicial para el Equipo "+frm_modBitRezagado.cmb_equipo.value);
	}		
	if(frm_modBitRezagado.txt_horoFin.value=="" && validacion==1){
		validacion = 0;
		alert("Ingresar el Horómetro Final para el Equipo "+frm_modBitRezagado.cmb_equipo.value);
	}	
	
	
	if(validacion==1 && frm_modBitRezagado.ckb_activarTep.checked){
		//Validar los datos del Acarreo de Tepetate
		if(frm_modBitRezagado.cmb_origenTepetate.value=="" && validacion==1){
			validacion = 0;
			alert("Seleccionar el Origen del Acarreo de Tepetate");
		}
		if(frm_modBitRezagado.cmb_destinoTepetate.value=="" && validacion==1){
			validacion = 0;
			alert("Seleccionar el Destino del Acarreo de Tepetate");
		}	
		if(frm_modBitRezagado.txt_cucharonesTep.value=="" && validacion==1){
			validacion = 0;
			alert("Ingresar la Cantidad de Cucharones de Tepetate");
		}	
		if(validacion==1){
			//Verificar que el Horometro Inicial sea un numero valido
			if(!validarEntero(frm_modBitRezagado.txt_cucharonesTep.value,"La Cantidad de Cucharones de Tepetate"))
				validacion = 0;
		}
	}
		
		
	if(validacion==1 && frm_modBitRezagado.ckb_activarMin.checked){
		//Validar los datos del Acarreo de Mineral
		if(frm_modBitRezagado.cmb_origenMineral.value=="" && validacion==1){
			validacion = 0;
			alert("Seleccionar el Origen del Acarreo de Mineral");
		}
		if(frm_modBitRezagado.cmb_destinoMineral.value=="" && validacion==1){
			validacion = 0;
			alert("Seleccionar el Destino del Acarreo de Mineral");
		}
		if(frm_modBitRezagado.txt_cucharonesMin.value=="" && validacion==1){
			validacion = 0;
			alert("Ingresar la Cantidad de Cucharones de Mineral");
		}
		if(validacion==1){
			//Verificar que el Horometro Inicial sea un numero valido
			if(!validarEntero(frm_modBitRezagado.txt_cucharonesMin.value,"La Cantidad de Cucharones de Mineral"))
				validacion = 0;
		}
	}
	
	
	//Si no se realizo modificación alguna en la bitácora de Consumos, notificar al usuario y preguntar si se puede continuar.
	if(frm_modBitRezagado.hdn_regBitConsumos.value=="no" && validacion==1){
		if(!confirm("¡No se Modificó la Bitácora de Consumos! \n Presione el Botón 'Aceptar' para continuar o 'Cancelar' para Revisar los Registros a la Bitácora de Consumos"))
			validacion = 0;
	}
	
	
	//Emitir resultado de la validacion
	if(validacion==1)
		return true;
	else
		return false;*/
	
	var aux = 0;
	//Contar la cantidad de elementos del form
	var num = frm_modBitRezagado.elements.length;
	//Si la variable validación mantiene su valor en 1, el proceso de validación fue satisfactorio
	var validacion = 1;
	
		
	//Validar datos del Operador
	if(frm_modBitRezagado.cmb_operador.value==""){
		validacion = 0;
		alert("Seleccionar el Operador del Equipo");
	}
	if(frm_modBitRezagado.cmd_turno.value=="" && validacion==1){
		validacion = 0;
		alert("Seleccionar el Turno");
	}
	
	
	
	//Validar los Datos del Equipo
	if(frm_modBitRezagado.cmb_equipo.value=="" && validacion==1){
		validacion = 0;
		alert("Seleccionar Equipo");
	}	
	if(frm_modBitRezagado.txt_horoIni.value=="" && validacion==1){
		validacion = 0;
		alert("Ingresar el Horómetro Inicial para el Equipo "+frm_modBitRezagado.cmb_equipo.value);
	}		
	if(frm_modBitRezagado.txt_horoFin.value=="" && validacion==1){
		validacion = 0;
		alert("Ingresar el Horómetro Final para el Equipo "+frm_modBitRezagado.cmb_equipo.value);
	}	
	
	for(var i = 0; i<num; i++){
		var objeto = frm_modBitRezagado.elements[i];
		var cucharon = frm_modBitRezagado.elements[i+1];
		var trasp = frm_modBitRezagado.elements[i+2];
		var tlimp = frm_modBitRezagado.elements[i+3];
		for(var j = 0; j<num; j++){
			if(objeto.id==("ckb_activarMin"+j)){
				if(validacion==1 && objeto.checked){
					aux++;
					if(cucharon.value=="" && validacion==1){
						validacion = 0;
						alert("Ingresar la " + (j+1) + "° Cantidad de Cucharones");
					}
					if(validacion==1){
						//Verificar que el Horometro Inicial sea un numero valido
						if(!validarEntero(cucharon.value,"La " + (j+1) + "° Cantidad de Cucharones"))
							validacion = 0;
					}
					
					if((trasp.checked==false && tlimp.checked==false) && validacion==1){
						validacion = 0;
						alert("Seleccionar Traspaleo o T. Limpio para el " + (j+1) + "° registro");
					}
				}
			}
		}
	}
	
	if(validacion==1 && aux==0){
		alert("Se Debe Seleccionar al menos un registro de Rezagado");
		validacion = 0;
	}
	
	//Si no se realizo el registro de la bitacora de Consumos, notificar al usuario y preguntar si se puede continuar.
	if(frm_modBitRezagado.hdn_regBitConsumos.value=="no" && validacion==1){
		if(!confirm("¡No se Registró la Bitácora de Consumos! \n Presione el Botón 'Aceptar' para continuar o 'Cancelar' para Agregar Registros a la Bitácora de Consumos"))
			validacion = 0;
	}
	
	
	//Emitir resultado de la validacion
	if(validacion==1)
		return true;
	else
		return false;
	
}//Cierre de la funcion valFormModBitRezagado(frm_modBitRezagado)


/***************************************************************************************************************************************************************/
/************************************************************* REGISTRAR BITACORA RETRO-BULL *******************************************************************/
/***************************************************************************************************************************************************************/
/*Esta función valida el formulario de registro de la Bitacora para Retros y Bulldozer*/
function valFormRegistroBitUtilitario(frm_registroBitUtilitario){
	//Si la variable validación mantiene su valor en 1, el proceso de validación fue satisfactorio
	var validacion = 1;

	//Verificar los datos del Operador
	if(frm_registroBitUtilitario.cmb_operador.value==""){
		alert("Seleccionar el Operador del Equipo");
		validacion = 0;
	}
	if(frm_registroBitUtilitario.cmb_turno.value=="" && validacion==1){
		alert("Seleccionar el Turno");
		validacion = 0;
	}
	
	
	//Validar los Datos del Equipo
	if(frm_registroBitUtilitario.cmb_equipo.value=="" && validacion==1){
		validacion = 0;
		alert("Seleccionar Equipo");
	}
	//Revisar si fue registrada alguna falla en el equipo	
	if(frm_registroBitUtilitario.hdn_fallasEquipo.value!="" && validacion==1){
		//Verificar si el Equipo seleccionado coicide con los registros hechos en la Bitacora de Fallas
		if(frm_registroBitUtilitario.cmb_equipo.value!=frm_registroBitUtilitario.hdn_fallasEquipo.value){
			validacion = 0;
			var msg = "El Equipo Seleccionado '"+frm_registroBitUtilitario.cmb_equipo.value+"' no Conicide con el Equipo '"+frm_registroBitUtilitario.hdn_fallasEquipo.value;
			msg += "' Registrado en la Bitacora de Fallas \nSeleccionar el Equipo Indicado para Poder Guardar el Registro ";
			alert(msg);	
		}
	}	
	if(frm_registroBitUtilitario.txt_horoIni.value=="" && validacion==1){
		validacion = 0;
		alert("Ingresar el Horómetro Inicial para el Equipo "+frm_registroBitUtilitario.cmb_equipo.value);
	}		
	if(frm_registroBitUtilitario.txt_horoFin.value=="" && validacion==1){
		validacion = 0;
		alert("Ingresar el Horómetro Final para el Equipo "+frm_registroBitUtilitario.cmb_equipo.value);
	}	
	
	
	//Validar los datos del Tepetate
	if(validacion==1){
		if(frm_registroBitUtilitario.cmb_lugarAmacizado.value=="" && frm_registroBitUtilitario.cmb_limpiaAcequia.value=="" && frm_registroBitUtilitario.cmb_lugarBalastreo.value==""){
			alert("Se debe Seleccionar por lo menos uno de los Siguientes Conceptos: \n *Lugar Amacizado\n *Lugar de la Limpia de Acequia\n *Lugar de Balastreo");
			validacion = 0;
		}
	}
	
	//Si no se realizo el registro de la bitacora de Consumos, notificar al usuario y preguntar si se puede continuar.
	if(frm_registroBitUtilitario.hdn_regBitConsumos.value=="no" && validacion==1){
		if(!confirm("¡No se Registró la Bitácora de Consumos! \n Presione el Botón 'Aceptar' para continuar o 'Cancelar' para Agregar Registros a la Bitácora de Consumos"))
			validacion = 0;
	}
	
	
	//Emitir resultado de la validacion
	if(validacion==1)
		return true;
	else
		return false;
}//Cierre de la función valFormRegistroBitUtilitario(frm_registroBitUtilitario)


/***************************************************************************************************************************************************************/
/************************************************************* MODIFICAR BITACORA RETRO-BULL *******************************************************************/
/***************************************************************************************************************************************************************/
/*Esta función valida el formulario de registro de la Bitacora para Retros y Bulldozer*/
function valFormModRegistroBitUtilitario(frm_modificarBitUtilitario){
	//Si la variable validación mantiene su valor en 1, el proceso de validación fue satisfactorio
	var validacion = 1;

	//Verificar los datos del Operador
	if(frm_modificarBitUtilitario.cmb_operador.value==""){
		alert("Seleccionar el Operador del Equipo");
		validacion = 0;
	}
	if(frm_modificarBitUtilitario.cmb_turno.value=="" && validacion==1){
		alert("Seleccionar el Turno");
		validacion = 0;
	}
	
	
	//Validar los Datos del Equipo
	if(frm_modificarBitUtilitario.cmb_equipo.value=="" && validacion==1){
		validacion = 0;
		alert("Seleccionar Equipo");
	}

	if(frm_modificarBitUtilitario.txt_horoIni.value=="" && validacion==1){
		validacion = 0;
		alert("Ingresar el Horómetro Inicial");
	}		
	if(frm_modificarBitUtilitario.txt_horoFin.value=="" && validacion==1){
		validacion = 0;
		alert("Ingresar el Horómetro Final");
	}	
	
	//Validar los datos del Tepetate
	if(validacion==1){
		if(frm_modificarBitUtilitario.cmb_lugarAmacizado.value=="" && frm_modificarBitUtilitario.cmb_limpiaAcequia.value=="" &&frm_modificarBitUtilitario.cmb_lugarBalastreo.value==""){
			alert("Se debe Seleccionar por lo menos uno de los Siguientes Conceptos: \n *Lugar Amacizado\n *Lugar de la Limpia de Acequia\n *Lugar de Balastreo");
			validacion = 0;
		}
	}

	//Emitir resultado de la validacion
	if(validacion==1)
		return true;
	else
		return false;
}//Cierre de la función valFormModRegistroBitUtilitario(frm_modificarBitUtilitario)


/***************************************************************************************************************************************************************/
/**************************************************************GESTIONAR OBRAS**********************************************************************************/
/***************************************************************************************************************************************************************/

/*Esta funcion verifica cual boton fue seleccionado y a partir de él realiza la operación correspondiente*/
function identificarOperacion(operacion){
	//Identificar cual operación debe ser ejecutada
	switch(operacion){
		case "registrar":
			//Ocultar el DIV que muestra los datos de la obra en el caso que este visible
			document.getElementById("consulta-datosObra").style.visibility = "hidden";
			
			//Abri la Ventana para registrar la obra y guardar la referencia de la misma
			vntRegObra = window.open("verRegistrarObra.php",
			"regObra","top=10, left=10, width=860, height=655, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no");									
		break;
		case "modificar":
			//Antes la ventana donde se modificarán los datos de la obra, verificar que al menos una haya sido seleccionada
			if(document.getElementById("cmb_obra").value==""){
				alert("Seleccionar una Obra para Modificar");
			}
			else{
				//Verificar que la opción seleccionada sea diferente de "TODAS"
				if(document.getElementById("cmb_obra").value=="TODAS"){
					alert("Esta Opción no Aplica Para la Operación de Modificación");
				}
				else{
					//Ocultar el DIV que muestra los datos de la obra en el caso que este visible
					document.getElementById("consulta-datosObra").style.visibility = "hidden";
					
					
					//Obtener el ID de la Obra seleccionada
					var idObra = document.getElementById("cmb_obra").value;
					//Abrir la Ventana para modificar la obra y guardar la referencia de la misma
					vntModObra = window.open("verModificarObra.php?idObra="+idObra,
					"regObra","top=10, left=10, width=860, height=655, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no");										
				}
			}
		break;
		case "consultar":
			//Antes la ventana donde se modificarán los datos de la obra, verificar que al menos una haya sido seleccionada
			if(document.getElementById("cmb_obra").value==""){
				alert("Seleccionar una Obra para Modificar");
			}
			else{
				var idObra = document.getElementById("cmb_obra").value;				
				//Obtener los datos de la Obra y mostrarlos en el DIV con el id "consulta-datosObra", el cual se encuentra oculto al cargar la pagina
				consultarObra(idObra);
			}
		break;
	}
}//Cierre de la función identificarOperacion(operacion)


/*Esta funcion valida el formulario de Agregar Obra*/
function valFormRegObra(frm_regObra){
	//Si el valor de validación permanece en 1, el proceso de validación fue exitoso
	var validacion = 1;
	
	//Verificar que sea seleccionado el Cliente
	if(frm_regObra.cmb_idCliente.value==""){
		alert("Seleccionar Cliente");
		validacion = 0;
	}
	//Verificar que sea seleccionado el Área
	if(frm_regObra.cmb_area.value=="" && validacion==1){
		alert("Seleccionar Área");
		validacion = 0;
	}
	//Verificar que sea seleccionado el Bloque
	if(frm_regObra.cmb_bloque.value=="" && validacion==1){
		alert("Seleccionar Bloque");
		validacion = 0;
	}	
	//Verificar que sea Introducido el nombre de la obra
	if(frm_regObra.txt_nomObra.value=="" && validacion==1){
		alert("Introducir el Nombre de la Obra");
		validacion = 0;
	}
	
	
	//Verificar que la obra no se encuentre registrada
	if(validacion==1){
		if(frm_regObra.hdn_claveValida.value!=""){
			alert("La Obra "+frm_regObra.txt_nomObra.value+" se Encuentra Registrada Con la Clave "+frm_regObra.hdn_claveValida.value+"\nIntroducir Otro Nombre");			
			validacion = 0;
		}
	}
	
	if(validacion==1)
		return true;
	else
		return false;
}

//Esta función agrega la Opcion de agregar nuevo elemento a la listas desplegables de Area y Bloque con un retraso de medio segundo en la ventana emergente de Registrar Obra
function agregarNvasOpciones(){
	//Arreglo con los nombres de los combos a los cuales se les agregará una nueva opcion
	var nombres = Array("cmb_area","cmb_bloque");
	
	//Agregar la opcion de Agregar Nuevo a la Lista de Area		
	for(i=0;i<nombres.length;i++){
		//Obtener la referencia de la Lista desplegable
		var objeto = document.getElementById(nombres[i])
		//Aumentar el numero de opciones
		objeto.length++;
		//Colocar los atributos de value y text
		objeto.options[objeto.length-1].text="Agregar Nuevo(a)";
		objeto.options[objeto.length-1].value="NUEVA";
	}
	
}//Cierre de la función agregarNvasOpciones()


//Esta funcion agrega una nueva opción a los combos de Area y Bloque en las paginas de Registrar y Modificar Obra
function agregarNvaOpcion(comboBox){
	//Si la opcion seleccionada es agregar nueva unidad ejecutar el siguiete codigo
	if(comboBox.value=="NUEVA"){
		var nvaOpcion = "";
		var condicion = false;
		do{
			nvaOpcion = prompt("Introducir Nueva Opción","Nueva Opción...");
			if(nvaOpcion=="Nueva Opción..." ||  nvaOpcion=="")
				condicion = true;	
			else
				condicion = false;
		}while(condicion);
		
		//Si el usuario presiono calncelar no se relaiza ninguan actividad de lo contrario asignar la nueva opcion al combo
		if(nvaOpcion!=null){
			//Convertir a mayusculas la opcion dada
			nvaOpcion = nvaOpcion.toUpperCase();
			//variable que nos ayudara a saber si la nueva opcion ya esta registrada en el combo
			var existe = 0;
			
			for(i=0; i<comboBox.length; i++){
				//verificar que la nueva opcion no se encuentre dentro de las opciones actuales del combo
				if(comboBox.options[i].value==nvaOpcion)
					existe = 1;
			} //FIN for(i=0; i<comboBox.length; i++)
			
			//Si la nueva opcion no esta registrada agregarla como una adicional y preseleccionarla
			if(existe==0){
				//Agregar al final la nueva opcion seleccionada
				comboBox.length++;
				comboBox.options[comboBox.length-1].text = nvaOpcion;
				comboBox.options[comboBox.length-1].value = nvaOpcion;
				//Preseleccionar la opcion agregada
				comboBox.options[comboBox.length-1].selected = true;
			} // FIN if(existe==0)
			
			else{
				alert("La Opción Ingresada ya esta Registrada \n en las Opciones de la Lista de Desplegable");
				comboBox.value = nvaOpcion;
			}
		}// FIN if(nvaOpcion!= null)
		
		else if(nvaOpcion== null){
			comboBox.value = "";	
		}
	}// FIN if(comboBox.value=="NUEVA")
}//Cierre de la función agregarNvaOpcion(comboBox)


/***************************************************************************************************************************************************************/
/******************************************************************SERVICIOS************************************************************************************/
/***************************************************************************************************************************************************************/

/**************************************************************************REGISTRAR SERVICIOS*****************************************************************/

function activarTurnosAdmon(combo){
	if(combo==""){
		document.getElementById("txt_turnosOf").readOnly=true;
		document.getElementById("hdn_revisarOf").value="no";
		document.getElementById("txt_turnosAy").readOnly=true;
		document.getElementById("hdn_revisarAy").value="no";
		document.getElementById("txt_turnosOf").value=0;
		document.getElementById("txt_turnosAy").value=0;
	}
	if (combo=="AYUDANTE GENERAL"){
		document.getElementById("txt_turnosAy").readOnly=false;
		document.getElementById("hdn_revisarAy").value="si";
		document.getElementById("txt_turnosOf").readOnly=true;
		document.getElementById("hdn_revisarOf").value="no";
		document.getElementById("txt_turnosOf").value=0;
	}
	if(combo=="OFICIAL"){
		document.getElementById("txt_turnosOf").readOnly=false;
		document.getElementById("hdn_revisarOf").value="si";
		document.getElementById("txt_turnosAy").readOnly=true;
		document.getElementById("hdn_revisarAy").value="no";
		document.getElementById("txt_turnosAy").value=0;
	}
	if(combo=="AMBOS"){
		document.getElementById("txt_turnosOf").readOnly=false;
		document.getElementById("hdn_revisarOf").value="si";
		document.getElementById("txt_turnosAy").readOnly=false;
		document.getElementById("hdn_revisarAy").value="si";
	}
}

//Funcion que permite asegurar que todos los elementos del formulario se encuentren llenos
function valFormRegServicios(frm_registrarServicios){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
	
	if(frm_registrarServicios.hdn_validar.value=="si"){
		if(frm_registrarServicios.txa_actividad.value==""){
			res=0;
			alert("Ingresar Actividades");
		}
		//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
		if(frm_registrarServicios.cmb_categoria.value==""&&res==1){
			alert("Seleccionar Categoría");
			res = 0;
		}
	
	//Verificamos que los turnos hayan sido ingresados	
		if(frm_registrarServicios.hdn_revisarOf.value=="si"&&res==1){
			if (frm_registrarServicios.txt_turnosOf.value==0 || frm_registrarServicios.txt_turnosOf.value==""){
				res=0;
				alert("Ingresar Número Válido de Turnos del Oficial");
			}
		}
	
	//Verificamos que los turnos hayan sido ingresados	
		if(frm_registrarServicios.hdn_revisarAy.value=="si"&&res==1){
			if (frm_registrarServicios.txt_turnosAy.value==0 || frm_registrarServicios.txt_turnosAy.value==""){
				res=0;
				alert("Ingresar Número Válido de Turnos del Ayudante");
			}
		}
	}
	if(res==1)
		return true;
	else
		return false;
	
}//Cierre Funcion valFormRegServicios()

/**************************************************************************MODIFICAR SERVICIOS*****************************************************************/
//Funcion que valida los datos para consultar los servicios 
function valFormModServ(frm_modificarRegistros){
	var res=1;
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_modificarRegistros.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_modificarRegistros.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_modificarRegistros.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Cierre, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_modificarRegistros.txt_fechaFin.value.substr(0,2);
	var finMes=frm_modificarRegistros.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_modificarRegistros.txt_fechaFin.value.substr(6,4);
	
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	//Verificar que el año de Fin sea mayor al de Inicio
	if(fechaIni>fechaFin){
		res=0;
		alert ("La fecha de Inicio no puede ser mayor a la Fecha de Fin");
	}
	
	
	if(res==1)
		return true;
	else
		return false;
}

//Funcion que permite activar o desactivar los campos dependiendo del valor seleccionado
function valFormConsultaServicios(frm_modificarServicios){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_modificarServicios.rdb_id.length==undefined && !frm_modificarServicios.rdb_id.checked){
		alert("Seleccionar Categoría");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_modificarServicios.rdb_id.length>=2&&res==1){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_modificarServicios.rdb_id.length;i++){
			if(frm_modificarServicios.rdb_id[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar un Registro");			
	}
	
	if(res==1)
		return true;
	else
		return false;
	
}//Cierre Funcion valFormConsultaServicios()

//Funcion que permite validar que los datos del formulario se encuentren completos para posteriormente almacenarlos
function valFormModServicios(frm_modificarServicio){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
	if(frm_modificarServicio.hdn_botonSeleccionado.value!='sbt_cancelar'){	
		if(frm_modificarServicio.txa_actividad.value==""){
			res=0;
			alert("Ingresar Actividades");
		}
		//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
		if(frm_modificarServicio.rdb_categoria.length==undefined && !frm_modificarServicio.rdb_categoria.checked&&res==1){
			alert("Seleccionar Categoría");
			res = 0;
		}
		//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
		if(frm_modificarServicio.rdb_categoria.length>=2&&res==1){
			//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
			res = 0; 
			//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
			for(i=0;i<frm_modificarServicio.rdb_categoria.length;i++){
				if(frm_modificarServicio.rdb_categoria[i].checked)
					res = 1;
			}
			if(res==0)
				alert("Seleccionar Categoría");			
		}
		
		//Verificamos que los turnos hayan sido ingresados	
		if(frm_modificarServicio.txt_turnos.value==""&&res==1){
			res=0;
			alert("Ingresar Número de Turnos");
		}
	}
		
	if(res==1)
		return true;
	else
		return false;
	
}//Cierre Funcion valFormModServicios()


/***************************************************************************************************************************************************************/
/******************************************************************REPORTES************************************************************************************/
/***************************************************************************************************************************************************************/

//Funcion que valida los datos para generar el Reporte de Nómina
function valGenerarNomina(frm_reporteNomina){
	var res=1;
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_reporteNomina.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_reporteNomina.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_reporteNomina.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Cierre, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_reporteNomina.txt_fechaFin.value.substr(0,2);
	var finMes=frm_reporteNomina.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_reporteNomina.txt_fechaFin.value.substr(6,4);
	
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	//Verificar que el año de Fin sea mayor al de Inicio
	if(fechaIni>fechaFin){
		res=0;
		alert ("La fecha de Inicio no puede ser mayor a la Fecha de Fin");
	}
	
	if(res==1 && frm_reporteNomina.cmb_area.value==""){
		res=0;
		alert ("Seleccionar el Área");
	}
	
	if(res==1)
		return true;
	else
		return false;
}

//Funcion que valida los datos para generar los diferentes Reportes de Desarrollo
function valFormConsultarReporte(formulario){
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
		alert ("La fecha de Inicio no puede ser mayor a la Fecha de Fin");
	}
	//Verificar si esta definido el combo de Equipos
	if(formulario.cmb_equipos!=undefined){
		if(res==1 && formulario.cmb_equipos.value==""){
			res=0;
			alert ("Seleccionar el Equipo");
		}
	}
	
	if(res==1)
		return true;
	else
		return false;
}

//Funcion que valida los datos para generar los diferentes Reportes de Desarrollo
function valFormConsultarReporteCombo(formulario){
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
		alert ("La fecha de Inicio no puede ser mayor a la Fecha de Fin");
	}
	
	if(res==1 && formulario.cmb_equipo.value==""){
		res=0;
		alert ("Seleccionar El Tipo de Equipo");
	}
	
	if(res==1)
		return true;
	else
		return false;
}

/*Funcion que valida los datos que se agregan al Reporte de Servicios*/
function valFormReporteServicios(formulario){
	var res=1;
	
	if (formulario.txt_dirigido.value==""){
		alert("Introducir a quien va Dirigido el Reporte");
		res=0;
	}
	
	if (formulario.txt_puesto.value=="" && res==1){
		alert("Introducir el Puesto a quien va Dirigido el Reporte");
		res=0;
	}
	
	if (formulario.txt_empresa.value=="" && res==1){
		alert("Introducir la Empresa de a quien va Dirigido el Reporte");
		res=0;
	}
	
	if (formulario.txt_contratista.value=="" && res==1){
		alert("Introducir el Nombre del Contratista");
		res=0;
	}
	
	if (formulario.txt_smina.value=="" && res==1){
		alert("Introducir el Nombre del Superintendente de Mina");
		res=0;
	}
	
	if (formulario.txt_jmina.value=="" && res==1){
		alert("Introducir el Nombre del Jefe de Sección de Mina");
		res=0;
	}
	
	if(res==1)
		return true;
	else
		return false;
}

//Funcion que valida el reporte grafico
function valFormRptAvanceGrafico(frm_reportePptoAvance){
	if(frm_reportePptoAvance.cmb_periodo.value==""){
		alert("Seleccionar el Periodo");
		return false;
	}
	if(frm_reportePptoAvance.cmb_cliente.value==""){
		alert("Seleccionar el Cliente");
		return false;
	}
}
/***************************************************************************************************************************************************************/
/*************************************************VALIDAR FECHAS EN FRM_REGISTRARPRESUPUESTO***********************************************************/
/***************************************************************************************************************************************************************/
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
	 	verificarRangoValido(document.frm_registrarPresupuesto.txt_fechaIni.value,document.frm_registrarPresupuesto.txt_fechaFin.value,document.frm_registrarPresupuesto.hdn_claveDefinida.value,document.frm_registrarPresupuesto.cmb_cliente.value);
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
	var presupuesto = document.getElementById("txt_mtsPresupuestados").value;	
	var diasLaborales = document.getElementById("txt_diasLaborales").value;
	var diasNoLaborales = document.getElementById("txt_domingos").value;
	var pptoDiario = "";
	
	//Si estan diponibles los datos necesarios para obtener el Ptto Diario, proceder a realizar los calculos
	if(presupuesto!="" && diasLaborales!=""){
		pptoDiario = parseFloat(presupuesto.replace(/,/g,'')) / (parseFloat(diasLaborales)+parseFloat(diasNoLaborales));
		formatTasaCambio(pptoDiario,'txt_mtsPresupuestadosDiarios');
	}	                
}//Cierre de la funcion calcularPptoDiario()



/***************************************************************************************************************************************/
/********************************************************REGISTRAR PRESUPUESTO**********************************************************/
/***************************************************************************************************************************************/
//Esta funcion formatea las Fechas a valores posibles y permitidos
function formatCero(){
	var valor = document.getElementById("txt_diasLaborales").value;
			
	if (valor=="00" || valor=="0" || valor =="000"){
		alert ("El Valor Introducido No es el Correcto");
		document.getElementById("txt_diasLaborales").value = "";
		document.getElementById("txt_mtsPresupuestadosDiarios").value = "";
		
	}	
	if (valor==""){
		alert ("Agregar un Registro Válido");
		document.getElementById("txt_diasLaborales").value = "";
		document.getElementById("txt_mtsPresupuestadosDiarios").value = "";
	}
}

//Funcion para Evaluar los datoas del formularo Registrar Presupuesto
function valFormRegPresupuesto(frm_registrarPresupuesto){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;
		
	if(frm_registrarPresupuesto.hdn_band.value=="si"){


		if (frm_registrarPresupuesto.txt_diasLaborales.value==""&&band==1){
			alert ("Ingresar Los Días Laborales o Seleccionar el Rango de Fechas para el Presupuesto");
			band=0;
		}
		if(band==1){
			if(!validarEntero(frm_registrarPresupuesto.txt_diasLaborales.value.replace(/,/g,''),"Días Laborales"))
				band = 0;		
		}
		
		if (frm_registrarPresupuesto.txt_domingos.value==""&&band==1){
			alert ("Seleccionar las Fechas del Presupuesto, para Obtener los Días NO Laborales");
			band=0;
		}
		
		
		//Verificar que la Ubicacion sea seleccionada desde el combo ====!frm_registrarPresupuesto.ckb_nuevaObra.checked && 
		if (!frm_registrarPresupuesto.ckb_nuevoCliente.checked&&frm_registrarPresupuesto.cmb_cliente.value==""&&band==1){
			alert ("Seleccionar el Cliente para el Presupuesto ó Agregar uno Nuevo");
			band=0;
		}
		
		//Se verifica que el presupuesto hayan sido ingresado
		if (frm_registrarPresupuesto.txt_mtsPresupuestados.value==""&&band==1){
			alert ("Ingresar los Metros Presupuestados");
			band=0;
		}
		
		if(band==1){
			if(!validarEntero(frm_registrarPresupuesto.txt_mtsPresupuestados.value.replace(/,/g,''),"Los Metros Presupuestados"))
				band = 0;		
		}
				
		//Se verifica que el presupuesto hayan sido ingresado
		if (frm_registrarPresupuesto.txt_mtsPresupuestadosDiarios.value==""&&band==1){
			alert ("Ingresar los Metros Presupuestados Diarios");
			band=0;
		}
		if(band==1){
			if(!validarEntero(frm_registrarPresupuesto.txt_mtsPresupuestadosDiarios.value.replace(/,/g,''),"Los Metros Presupuestados Diarios"))
				band = 0;		
		}	
				
		//Se verifica que el presupuesto hayan sido ingresado
		if (frm_registrarPresupuesto.txt_mtsQuincena1.value==""&&band==1){
			alert ("Ingresar los Metros de la Quincena 1");
			band=0;
		}
		if(band==1){
			if(!validarEntero(frm_registrarPresupuesto.txt_mtsQuincena1.value.replace(/,/g,''),"Los Metros de la Quincena 1"))
				band = 0;		
		}				
		//Se verifica que el presupuesto hayan sido ingresado
		if (frm_registrarPresupuesto.txt_mtsQuincena2.value==""&&band==1){
			alert ("Ingresar los Metros de la Quincena 2");
			band=0;
		}
		if(band==1){
			if(!validarEntero(frm_registrarPresupuesto.txt_mtsQuincena2.value.replace(/,/g,''),"Los Metros de la Quincena 2"))
				band = 0;		
		}
		//Se verifica que el presupuesto hayan sido ingresado
		if (frm_registrarPresupuesto.txt_disparosDia.value==""&&band==1){
			alert ("Ingresar los Disparos Diarios");
			band=0;
		}
		if(band==1){
			if(!validarEntero(frm_registrarPresupuesto.txt_disparosDia.value.replace(/,/g,''),"Los Disparos por Dia"))
				band = 0;		
		}		
		//Se verifica que el presupuesto hayan sido ingresado
		if (frm_registrarPresupuesto.txt_disparosTurno.value==""&&band==1){
			alert ("Ingresar los Disparos por Turno");
			band=0;
		}
		if(band==1){
			if(!validarEntero(frm_registrarPresupuesto.txt_disparosTurno.value.replace(/,/g,''),"Los Disparos por Turno"))
				band = 0;		
		}
		if(band==1&&frm_registrarPresupuesto.hdn_fechas.value=="1"){
			alert("Ambas Fechas ya se Encuentran Registradas, para el Cliente Seleccionado  \nElegir Otras Fechas");	
			band = 0;		
		}
		
		if(band==1&&frm_registrarPresupuesto.hdn_fechas.value=="2"){
			alert("La Fecha de Inicio se Encuentra Registrada en Otro Presupuesto, para el Cliente Seleccionado \nElegir Otra Fecha");	
			band = 0;		
		}
		
		if(band==1&&frm_registrarPresupuesto.hdn_fechas.value=="3"){
			alert("La Fecha de Fin ya se Encuentra Registrada en Otro Presupuesto, para el Cliente Seleccionado\nElegir Otra Fecha");	
			band = 0;		
		}
		
		if(band==1&&frm_registrarPresupuesto.hdn_fechas.value=="4"){
			alert("Las Fechas Seleccionadas Abarca un Rango de Fecha ya Registrado, para el Cliente Seleccionado\nElegir Otras Fechas");	
			band = 0;		
		}
	}
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

//Esta funcion solicita al usuario el nuevo cliente  y desabilita el combo de clientes
function agregarNuevoCliente(ckb_nuevoCliente, txt_nuevoCliente, cmb_cliente){
	var band=0;
	var valor = ""; //Variable utilizada para que cuando el nombre del nuevo cliente ingresado ya se encuentra dentro del combo de clientes, se muestre
	//Si el checkbox para el nuevo cliente esta seleccionado, pedir el nombre de dicha cliente
	if (ckb_nuevoCliente.checked){
		var cliente = prompt("¿Nombre del Nuevo cliente?","Nombre del Cliente...");	
		if(cliente!=null && cliente!="Nombre del Cliente..." && cliente!=""){
			cliente = cliente.toUpperCase();			
			if(cliente.length<=40){			
				for(i=0; i<document.getElementById("cmb_cliente").length; i++){
					//verificar que la nueva opcion no se encuentre dentro de las opciones actuales del combo
					if(document.getElementById("cmb_cliente").options[i].text==cliente){
						valor = document.getElementById("cmb_cliente").options[i].value;
						band = 1;
					}
				}//Cierre for(i=0;i<seccion.length;i++)
				
				if(band==1){ 
					alert("El cliente Ingresado ya Existe ");
					document.getElementById("cmb_cliente").value=valor;
					//Dechecar el check de Nuevo Cliente
					document.getElementById("ckb_nuevoCliente").checked = false;
				}//Fin del if(band==1){
					
					if(band==0){
						//Asignar el valor obtenido a la caja de texto que lo mostrara
						document.getElementById(txt_nuevoCliente).value = cliente.toUpperCase();
						//Verificar que el combo este definido para poder deshabilitarlo
						if(document.getElementById(cmb_cliente)!=null)
							//Deshabilitar el ComboBox para que el usuario no lo pueda modificar
							document.getElementById(cmb_cliente).disabled = true;				
					}// Fin del if(band==0){
			}
			else{
				alert("El Nombre del Cliente Excede de 40 Caracteres Permitidos");
				//Regresar False si se presiona el botón cancelar o se asigna un valor equivocado
				ckb_nuevoCliente.checked = false;
				band=0;
			}
		}
		else{
			//Regresar False si se presiona el botón cancelar o se asigna un valor equivocado
			ckb_nuevoCliente.checked = false;
		}
	}
	//Si el checkbox para un nuevo cliente se deselecciona, borrar el dato escrito en la caja de texto y reactivar el combo de clientes
	else{
		document.getElementById(txt_nuevoCliente).value = "";
		//Verificar que el combo este definido para poder Habilitarlo
		if (document.getElementById(cmb_cliente)!=null){
			//Habilitar el ComboBox y el deseleccionar el CheckBox para que el usuario pueda agregar nueva información
			document.getElementById(cmb_cliente).disabled = false;
			//Darle un valor vacio por default
			document.getElementById(cmb_cliente).value = "";
		}	
	}
}


//Funcion que activa y desactiva los campos dentro del formulario REGISTRAR PRESUPUESTO, cuando es agregada una nueva obra
function activarCamposPpto(checkBox){
	//Si 
	if(checkBox.checked){
		//Quitar el atributo de ReadOnly a las cajas de texto para almacenar el nuevo cliente
		document.getElementById("txt_nuevoCliente").readOnly = false;
	}
	else{
		//Reactivar el atributo de ReadOnly a las cajas de texto que contienen el cliente.
		document.getElementById("txt_nuevoCliente").readOnly = true;
	}

}//Cierre de la función activarCampos(checkBox)


/***************************************************************************************************************************************/
/********************************************************MODIFICAR PRESUPUESTO**********************************************************/
/***************************************************************************************************************************************/
//Funcion para Evaluar los datoas del formularo modificar Presupuesto
function valFormBusqPresupuesto(frm_modificarPresupuesto){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;
	
	//Se verifica quese haya seleccionado una Ubicacion
	if (frm_modificarPresupuesto.cmb_cliente.value==""&&band==1){
		alert ("Seleccionar un Cliente");
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


//Funcion para Evaluar los datoas del formularo Registrar Presupuesto
function valFormModPresupuesto(frm_modificarPresupuesto){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;
	
	if(frm_modificarPresupuesto.hdn_band.value=="si"){
		
		if (frm_modificarPresupuesto.txt_diasLaborales.value==""&&band==1){
			alert ("Ingresar Los Días Laborales o Seleccionar el Rango de Fechas para el Presupuesto");
			band=0;
		}
		if(band==1){
			if(!validarEntero(frm_modificarPresupuesto.txt_diasLaborales.value.replace(/,/g,''),"Días Laborales"))
				band = 0;		
		}
		
		//Verificar que la Ubicacion sea seleccionada desde el combo ====!frm_registrarPresupuesto.ckb_nuevoCliente.checked && 
		if (!frm_modificarPresupuesto.ckb_nuevoCliente.checked&&frm_modificarPresupuesto.cmb_cliente.value==""&&band==1){
			alert ("Seleccionar el Cliente para Registrarle el Presupuesto ó Agregar uno Nuevo");
			band=0;
		}
		//Verificar que algun equipo sea seleccionado
		if (frm_modificarPresupuesto.ckb_nuevoCliente.checked && frm_modificarPresupuesto.txt_nuevoCliente.value==""&&band==1){
			alert ("Agregar el Nuevo Cliente");
			band=0;
		}
		
		//Se verifica que el presupuesto hayan sido ingresado
		if (frm_modificarPresupuesto.txt_mtsPresupuestados.value==""&&band==1){
			alert ("Ingresar los Metros Presupuestados");
			band=0;
		}
		
		if(band==1){
			if(!validarEntero(frm_modificarPresupuesto.txt_mtsPresupuestados.value.replace(/,/g,''),"Los Metros Presupuestados"))
				band = 0;		
		}
				
		//Se verifica que el presupuesto hayan sido ingresado
		if (frm_modificarPresupuesto.txt_mtsPresupuestadosDiarios.value==""&&band==1){
			alert ("Ingresar los Metros Presupuestados Diarios");
			band=0;
		}
		if(band==1){
			if(!validarEntero(frm_modificarPresupuesto.txt_mtsPresupuestadosDiarios.value.replace(/,/g,''),"Los Metros Presupuestados Diarios"))
				band = 0;		
		}
						
		//Se verifica que el presupuesto hayan sido ingresado
		if (frm_modificarPresupuesto.txt_mtsQuincena1.value==""&&band==1){
			alert ("Ingresar los Metros de la Quincena 1");
			band=0;
		}
		if(band==1){
			if(!validarEntero(frm_modificarPresupuesto.txt_mtsQuincena1.value.replace(/,/g,''),"Los Metros de la Quincena 1"))
				band = 0;		
		}
						
		//Se verifica que el presupuesto hayan sido ingresado
		if (frm_modificarPresupuesto.txt_mtsQuincena2.value==""&&band==1){
			alert ("Ingresar los Metros de la Quincena 2");
			band=0;
		}
		if(band==1){
			if(!validarEntero(frm_modificarPresupuesto.txt_mtsQuincena2.value.replace(/,/g,''),"Los Metros de la Quincena 2"))
				band = 0;		
		}
		
		//Se verifica que el presupuesto hayan sido ingresado
		if (frm_modificarPresupuesto.txt_disparosDia.value==""&&band==1){
			alert ("Ingresar los Disparos Diarios");
			band=0;
		}
		if(band==1){
			if(!validarEntero(frm_modificarPresupuesto.txt_disparosDia.value.replace(/,/g,''),"Los Disparos por Dia"))
				band = 0;		
		}
						
		//Se verifica que el presupuesto hayan sido ingresado
		if (frm_modificarPresupuesto.txt_disparosTurno.value==""&&band==1){
			alert ("Ingresar los Disparos por Turno");
			band=0;
		}
		if(band==1){
			if(!validarEntero(frm_modificarPresupuesto.txt_disparosTurno.value.replace(/,/g,''),"Los Disparos por Turno"))
				band = 0;		
		}
	}

//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}
/********************************************************FIN DE MODIFICAR PRESUPUESTO**********************************************************/

/*Esta funcion habilita las cajas o combos que son deshabilitados*/
function restablecePresupuesto(){
	document.getElementById("cmb_cliente").disabled = false;
	document.getElementById("txt_nuevoCliente").disabled = false;
}


/***************************************************************************************************************************************************************/
/*************************************************FIN DE LA SECCION VALIDAR FECHAS EN FRM_REGISTRARPRESUPUESTO**************************************************/
/***************************************************************************************************************************************************************/