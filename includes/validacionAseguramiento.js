/**
  * Nombre del Módulo: Desarrollo                                               
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 17/Junio/2011                                      			
  * Descripción: Este archivo contiene funciones para validar los diferentes formularios del Módulo Aeguramiento
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
	if(te==5){//Para cajas de texto tipo hora
		var teclas_especiales = [8, 58];		
		//8 = BackSpace, 58 = Dos Puntos
	}
	if(te==6){//Para cajas de texto con nombres de documentos
		var teclas_especiales = [8, 45, 46, 95];		
		//8 = BackSpace, 45 = Guion medio, 46 = Punto, 95 = guion bajo
	}
	
	if(te==7){//Para cajas de texto tipo fecha dd/mm/dddd
		var teclas_especiales = [47];		
		//47=Diagonal
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

/*Esta funcion solicita la confirmación del usuario antes de salir de la pagina*/
function confirmarSalida(pagina){
	if(confirm("¿Estas Seguro que Quieres Salir?\nToda la información no Guardada se Perderá"))
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
	var txtNorma = document.getElementById("txt_norma").value;
	var cmbNorma = document.getElementById("cmb_norma").value;
	if(txtNorma!=""||cmbNorma!=""){
		//Si el checkbox para la nueva clasificacion esta seleccionado, pedir el nombre de dicha clasificacion
		if (ckb_clasificacion.checked){
			var clasificacion = prompt("¿Nombre de Nueva Clasificación?","Nombre de Clasificación...");	
			if(clasificacion!=null && clasificacion!="Nombre de Clasificación..." && clasificacion!=""){
				if(clasificacion.length<=40){
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
						alert("Nombre No Valido. \nEl Nombre de Archivo No Puede Terminar En Punto.\nEjemplo 1.1.1 ");
						clasificacion.value="";
						ckb_clasificacion.checked = false;
					}
					if(band==2){
						alert("Nombre No Valido. \nNo Son Aceptados Caracteres / : * ? < > | y Comillas.\nEjemplo 1.1.1 ");
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
					alert("El Nombre de la Clasificación Excede el Número de Caracteres Permitidos");
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
	else{
		alert("Debe Seleccionar Primero Una Norma y Posteriormente una Clasificación");
		document.getElementById("ckb_clasificacion").checked = false
	}
}

//Esta funcion solicita al usuario la nueva Norma y desabilita el combo de norma
function agregarNuevaNorma(ckb_norma, txt_norma, cmb_norma){
	var band=0;
	//Si el checkbox para la nueva norma esta seleccionado, pedir el nombre de dicha norma
	if(ckb_norma.checked){
		var norma = prompt("¿Nombre de Nueva Norma?","Nombre de Norma...");	
		if(norma!=null && norma!="Nombre de Norma..." && norma!=""){
			if(norma.length<=40){
				for(i=0;i<norma.length;i++){
					//Igualamos el valor de seccion a car para su facil manejo
					car = norma.charAt(i);
					//Verificamos que se encuentre en la ultima posicion para ver que no termine en punto
					if(i==norma.length-1){
						if(car=='.'){
							band=1;
						}	
					}
					if(car=='/'||car==':'||car=='*'||car=='?'||car=='"'||car=='<'||car=='>'||car=='|'){
						band=2;
					}
				}//Cierre for(i=0;i<seccion.length;i++)
				if(band==1){
					alert("Nombre No Valido. \nEl Nombre de Archivo No Puede Terminar En Punto.\nEjemplo 1.1.1 ");
					norma.value="";
					ckb_norma.checked=false;
				}
				if(band==2){
					alert("Nombre No Valido. \nNo Son Aceptados Caracteres / : * ? < > | y Comillas.\nEjemplo 1.1.1 ");
					norma.value="";
					ckb_norma.checked=false;
				}
				if(band==0){			
					//Asignar el valor obtenido a la caja de texto que lo mostrara
					document.getElementById(txt_norma).value = norma.toUpperCase();
					//Verificar que el combo este definido para poder deshabilitarlo
					if (document.getElementById(cmb_norma)!=null)
						//Deshabilitar el ComboBox para que el usuario no lo pueda modificar
						document.getElementById(cmb_norma).disabled = true;				
				}
			}
			else{
				alert("El Nombre de la Norma Excede el Número de Caracteres Permitidos");
				//Regresar False si se presiona el botón cancelar o se asigna un valor equivocado
				ckb_norma.checked = false;			
				band=0;
			}
		}
		else{
			//Regresar False si se presiona el botón cancelar o se asigna un valor equivocado
			ckb_norma.checked = false;
		}
	}
	//Si el checkbox para nueva norma se deselecciona, borrar el dato escrito en la caja de texto y reactivar el combo de norma
	else{
		document.getElementById(txt_norma).value = "";
		//Verificar que el combo este definido para poder Habilitarlo
		if (document.getElementById(cmb_norma)!=null){
			//Habilitar el ComboBox y el deseleccionar el CheckBox para que el usuario pueda agregar nueva información
			document.getElementById(cmb_norma).disabled = false;
			//Darle un valor vacio por default
			document.getElementById(cmb_norma).value = "";
		}
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
		alert("Nombre No Valido. \nEl Nombre de Archivo No Puede Terminar En Punto.\nEjemplo 1.1.1 ");
		campo.value="";
	}
	if(band==2){
		alert("Nombre No Valido. \nNo Son Aceptados Caracteres / : * ? < > | y Comillas.\nEjemplo 1.1.1 ");
		campo.value="";
	}
}

/*Esta función se encarga de validar el formulario de Registro de Documentos*/
function valFormRegDocumentos(frm_agregarDocumento){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	band = 1;
	
	//Almacenamos el valor de la caja de texto
	var box = document.getElementById("hdn_claveValida").value;	
	
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
	
	//Verificar el valor de la caja de texto
	if(box!="si"&&band==1){
		alert("La Clave "+frm_agregarDocumento.txt_idDocumento.value+" ya se Encuentra Registrada; Verifique la Clave del Documento");
		band = 0;
	}
	
	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
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
			if(clasificacion.length<=40){
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
					alert("Nombre No Valido. \nEl Nombre de Archivo No Puede Terminar En Punto.\nEjemplo 1.1.1 ");
					clasificacion.value="";
					clasificacion.value=combo;
				}
				if(band==2){
					alert("Nombre No Valido. \nNo Son Aceptados Caracteres / : * ? < > | y Comillas.\nEjemplo 1.1.1 ");
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
				alert("El Nombre de la Clasificación Excede el Número de Caracteres Permitidos");
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
			document.getElementById(cmb_clasificacion).value = combo;
		}
	}
}

//Esta funcion solicita al usuario la nueva Norma y desabilita el combo de norma
function agregarNuevaNormaMod(ckb_norma, txt_norma, cmb_norma){
	//Variable que nos permite controlar si hubo caracteres no aceptados para la generacion de un archivo
	var band=0;
	//Creamos variable para guardar el valor original del combo
	var combo=document.getElementById("hdn_comboNorma").value;
	if(combo==""){
		combo="";
	}
	//Si el checkbox para la nueva clasificacion esta seleccionado, pedir el nombre de dicha norma
	if(ckb_norma.checked){
		var norma = prompt("¿Nombre de Nueva Norma?","Nombre de Norma...");	
		if(norma!=null && norma!="Nombre de Norma..." && norma!=""){
			if(norma.length<=40){
				for(i=0;i<norma.length;i++){
					//Igualamos el valor de seccion a car para su facil manejo
					car = norma.charAt(i);
					//Verificamos que se encuentre en la ultima posicion para ver que no termine en punto
					if(i==norma.length-1){
						if(car=='.'){
							band=1;
						}
					}	
					if(car=='/'||car==':'||car=='*'||car=='?'||car=='"'||car=='<'||car=='>'||car=='|'){
						band=2;
					}
				}//Cierre for(i=0;i<seccion.length;i++)
				if(band==1){
					alert("Nombre No Valido. \nEl Nombre de Archivo No Puede Terminar En Punto.\nEjemplo 1.1.1 ");
					norma.value="";
					norma.value=combo;
				}
				if(band==2){
					alert("Nombre No Valido. \nNo Son Aceptados Caracteres / : * ? < > | y Comillas.\nEjemplo 1.1.1 ");
					norma.value="";
					norma.value=combo;
				}
				if(band==0){
					//Asignar el valor obtenido a la caja de texto que lo mostrara
					document.getElementById(txt_norma).value = norma.toUpperCase();
					//Verificar que el combo este definido para poder deshabilitarlo
					if(document.getElementById(cmb_norma)!=combo){
						//Deshabilitar el ComboBox para que el usuario no lo pueda modificar
						document.getElementById(cmb_norma).disabled = true;	
						//Poner el combo como vacio
						document.getElementById(cmb_norma).value = "";	
					}
					else {
						//Regresar False si se presiona el botón cancelar o se asigna un valor equivocado
						ckb_norma.checked = false;
					}
				}
			}
			else{
				alert("El Nombre de la Norma Excede el Número de Caracteres Permitidos");
				//Regresar False si se presiona el botón cancelar o se asigna un valor equivocado
				ckb_norma.checked = false;
				band=0;
			}
		}
		else{
			//Regresar False si se presiona el botón cancelar o se asigna un valor equivocado
			ckb_norma.checked = false;
		}
	}
	//Si el checkbox para nueva norma se deselecciona, borrar el dato escrito en la caja de texto y reactivar el combo de clasificacion
	else{
		document.getElementById(txt_norma).value = "";
		//Verificar que el combo este definido para poder Habilitarlo
		if (document.getElementById(cmb_norma)!=null){
			//Habilitar el ComboBox y el deseleccionar el CheckBox para que el usuario pueda agregar nueva información
			document.getElementById(cmb_norma).disabled = false;
			//Darle un valor vacio por default
			document.getElementById(cmb_norma).value = combo;
		}
	}
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

//Funcion que permite desactivar el combo clasificacion en caso de que se haya seleccionado la norma como vacia
function desactivarCombo(){
	//varialble que permite almacenar el valor del combo para el facil manejo durante el proceso de validacion
	var comboNorma=document.getElementById("cmb_norma").value;
	var comboClasi=document.getElementById("cmb_clasificacion").value;
	var txtNorma=document.getElementById("txt_norma").value;
	var txtClasi=document.getElementById("txt_clasificacion").value;
	var bandera="";
	if(comboNorma==""&&txtNorma==""){
		bandera=1;
	}
	if(bandera==1){
		alert("Es Necesario Seleccionar Primero Una Norma y Posteriormente Una Clasificación");	
		if(txtClasi!=""){
			document.getElementById("ckb_clasificacion").checked=false;
			document.getElementById("txt_clasificacion").value="";
			document.getElementById("cmb_clasificacion").disabled=false;
		}
		if(comboClasi!=""){
			document.getElementById("cmb_clasificacion").value="";
		}
	}
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

/**************************************************************************************************************************************************************************************************************************/
/*****************************************************************************REGISTRAR LISTA MAESTRA REGISTROS CALIDAD****************************************************************************************************/
/**************************************************************************************************************************************************************************************************************************/

//Esta funcion solicita al usuario el nuevo Acceso y desabilita el combo de acceso
function agregarNuevoAcceso(ckb_acceso, txt_acceso, cmb_acceso){
	var band=0;
	//Si el checkbox para el nuevo acceso esta seleccionado, pedir el nombre de dicho acceso
	if (ckb_acceso.checked){
		var acceso = prompt("¿Nombre del Nuevo Acceso?","Nombre de Acceso...");	
		if(acceso!=null && acceso!="Nombre del Nuevo Acceso..." && acceso!=""){
			//Asignar el valor obtenido a la caja de texto que lo mostrara
			document.getElementById(txt_acceso).value = acceso.toUpperCase();
			//Verificar que el combo este definido para poder deshabilitarlo
			if(document.getElementById(cmb_acceso)!=null)
				//Deshabilitar el ComboBox para que el usuario no lo pueda modificar
				document.getElementById(cmb_acceso).disabled = true;				
		}
		else{
			//Regresar False si se presiona el botón cancelar o se asigna un valor equivocado
			ckb_acceso.checked = false;
		}
	}
	//Si el checkbox para nuevo accesi se deselecciona, borrar el dato escrito en la caja de texto y reactivar el combo de combo
	else{
		document.getElementById(txt_acceso).value = "";
		//Verificar que el combo este definido para poder Habilitarlo
		if (document.getElementById(cmb_acceso)!=null){
			//Habilitar el ComboBox y el deseleccionar el CheckBox para que el usuario pueda agregar nueva información
			document.getElementById(cmb_acceso).disabled = false;
			//Darle un valor vacio por default
			document.getElementById(cmb_acceso).value = "";
		}
	}
}


/*Esta función valida que se haya seleccionado un departamento en el pop-up verDepartamentos.php*/
function valFormDeptos(frm_verDepto){	
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
		idCheckBox="ckb_dpto"+ctrl.toString();
		
		//Verificar que la cantidad y la aplicación del Checkbox seleccionado no esten vacias
		if(document.getElementById(idCheckBox).checked){
			status = 1;
		}
		ctrl++;
	}//Fin del While	
	
	
	//Verificar que al menos un equipo haya sido seleccionado, si la variable status vale 1, quiere decir que al menos un departamento fue seleccionado
	if(status==0){
		alert("Seleccionar al Menos un Departamento");
		res = 0;
	}
	if(res==1)
		return true;
	else
		return false;		
}

/*Esta función valida que se hayan completado todos los registros en el formulario de registro de la lista maestra*/
function valFormLista(frm_agregarRegistro){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	var band = 1;
	
	//Verificar que el campo de cmb_depto no este vacío
	if(frm_agregarRegistro.cmb_depto.value==""){
		alert("Seleccionar Departamento Emisor");
		band = 0;
	}
	
	//Verificar que el campo de txt_indexacion no este vacío
	if(frm_agregarRegistro.txt_indexacion.value==""&&band==1){
		alert("Introducir el Método de Indexación");
		band = 0;
	}
	
	//Verificar que el campo de txt_noFormato no este vacío
	if(frm_agregarRegistro.txt_noFormato.value==""&&band==1){
		alert("Introducir Código de Forma");
		band = 0;
	}
	
	//Verificar que el campo de txt_perMtto no este vacío
	if(frm_agregarRegistro.txt_perMtto.value==""&&band==1){
		alert("Introducir Periodo de Mantenimiento");
		band = 0;
	}
	
	//Verificar que el campo de txt_noRevision no este vacío
	if(frm_agregarRegistro.txt_noRevision.value==""&&band==1){
		alert("Introducir Número de Revisión");
		band = 0;
	}
	
	//Verificar que el campo de txt_dispFinal no este vacío
	if(frm_agregarRegistro.txt_dispFinal.value==""&&band==1){
		alert("Introducir Disposición Final");
		band = 0;
	}
	
	//Verificar que el campo de txt_docAso no este vacío
	if(frm_agregarRegistro.txt_docAso.value==""&&band==1){
		alert("Introducir Documentos Asociados");
		band = 0;
	}
	
	//Verificar que el campo de txt_titulo no este vacío
	if(frm_agregarRegistro.txa_titulo.value==""&&band==1){
		alert("Introducir Titulo");
		band = 0;
	}
	
	//Verificar que el campo de txa_metColeccion no este vacío
	if(frm_agregarRegistro.txa_metColeccion.value==""&&band==1){
		alert("Introducir Método de Colección");
		band = 0;
	}
	
	//Verificar que el campo de cmb_acceso no este vacío
	if(frm_agregarRegistro.cmb_acceso.value==""&&band==1&&frm_agregarRegistro.txt_acceso.value==""&&band==1){
		alert("Introducir Acceso");
		band = 0;
	}
	
	//Verificar que el campo de txt_ubicacion no este vacío
	if(frm_agregarRegistro.txt_ubicacion.value==""&&band==1){
		alert("Introducir Ubicación");
		band = 0;
	}
		
	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}

//Función que permite seleccionar Todos los checkbox de el formulario verDepartamejtos
function seleccionarTodo(checkbox){
	for(var i=0;i<document.frm_verDepto.elements.length;i++){
		//Variable
		elemento=document.frm_verDepto.elements[i];
		if (elemento.type=="checkbox")
			elemento.checked=checkbox.checked;
	}
}

//Función que permite seleccionar Todos los checkbox de el formulario verDepartamejtos
function seleccionarTodoPart(checkbox){
	for(var i=0;i<document.frm_verPart.elements.length;i++){
		//Variable
		elemento=document.frm_verPart.elements[i];
		if (elemento.type=="checkbox")
			elemento.checked=checkbox.checked;
	}
}

//Función que permite deseleccionar Todos los checkbox de el formulario verDepartamejtos
function quitar(checkbox){
	if(!checkbox.checked)
		document.getElementById("ckb_todo").checked=false;
}


/********************************************************************************************************************************************************************/
/*************************************************MODIFICAR LISTA MAESTRA REGISTROS CALIDAD**************************************************************************/
/********************************************************************************************************************************************************************/
/*Esta función valida que se seleccione un archivo  en el formulario Resultados encontrados*/
function valFormModRC(frm_modificarLista){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
	
	//Boton que indica cual registro fue seleccionado
	var boton = document.getElementById("hdn_botonSelRC").value;
	if(boton=="modificar"){
		//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
		if(frm_modificarLista.rdb_id.length==undefined && !frm_modificarLista.rdb_id.checked){
			alert("Seleccionar Registro a Modificar");
			res = 0;
		}
		//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
		if(frm_modificarLista.rdb_id.length>=2){
			//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
			res = 0; 
			//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
			for(i=0;i<frm_modificarLista.rdb_id.length;i++){
				if(frm_modificarLista.rdb_id[i].checked)
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
	else if(boton=="exportar"){
		document.frm_modificarLista.action = "guardar_reporte.php";
	}
}



/**************************************************************************************************************************************************************************************************************************/
/*****************************************************************************REGISTRAR LISTA MAESTRA PIF****************************************************************************************************/
/**************************************************************************************************************************************************************************************************************************/

//Función que permite enviar datos via Get asi como validar que se encuentrn llenos los mismos
function envioDatosGet(){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	var band = 1;
	
	//Verificar que el campo de txt_claveManual no este vacío
	if(frm_agregarRegistro.txt_claveManual.value==""){
		alert("Ingresar Clave del Manual");
		band = 0;
	}
	
	//Verificar que el campo de txa_tituloManual no este vacío
	if(frm_agregarRegistro.txa_tituloManual.value==""&&band==1){
		alert("Introducir Titulo del Manual");
		band = 0;
	}
	
	//Verificar que el campo de txt_noRevManu no este vacío
	if(frm_agregarRegistro.txt_noRevManu.value==""&&band==1){
		alert("Introducir No. Revisión del Manual");
		band = 0;
	}
	
	//Verificar que el campo de txt_claveClausula no este vacío
	if(frm_agregarRegistro.txt_claveClausula.value==""&&band==1){
		alert("Introducir Clave de Clausula");
		band = 0;
	}
	
	if(band==1){
		//Ponemos el valor de la caja de texto que queremos enviar a la ventana emergente en la clasula
		var clausula = document.getElementById("txt_claveClausula").value; 
		//Al llamar a la ventana le enviamos el valor de la variable por el GET
		window.open('verRegFormatos.php?clausula='+clausula+'','_blank','top=50, left=50, width=740, height=680, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
	}
}

/*Esta función valida que se hayan completado todos los registros en el formulario de registro procedimientos y normas*/
function valFormRegForm(frm_agregarRegistro){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	var band = 1;
	
	//Verificar que el campo de txt_cveProc no este vacío
	if(frm_agregarRegistro.txt_cveProc.value==""){
		alert("Ingresar Clave del Procedimiento");
		band = 0;
	}
	
	//Verificar que el campo de txa_tituloProc no este vacío
	if(frm_agregarRegistro.txa_tituloProc.value==""&&band==1){
		alert("Introducir Titulo del Procedimiento");
		band = 0;
	}
	
	//Verificar que el campo de txt_noFormato no este vacío
	if(frm_agregarRegistro.txt_noFormatoProc.value==""&&band==1){
		alert("Introducir Clave de Forma");
		band = 0;
	}
	
	//Verificar que el campo de txt_noRevision no este vacío
	if(frm_agregarRegistro.txt_noRevision.value==""&&band==1){
		alert("Introducir Número de Revisión del Procedimiento");
		band = 0;
	}
	
	//Verificar que el campo de txt_noFormatoProc no este vacío
	if(frm_agregarRegistro.txt_noFormatoProc.value==""&&band==1){
		alert("Introducir Clave de la Forma");
		band = 0;
	}
	
	//Verificar que el campo de txt_nombreForma no este vacío
	if(frm_agregarRegistro.txt_nombreForma.value==""&&band==1){
		alert("Introducir Titulo de la Forma");
		band = 0;
	}
	
	//Verificar que el campo de txt_noRevisionForma no este vacío
	if(frm_agregarRegistro.txt_noRevisionForma.value==""&&band==1){
		alert("Introducir Número de Revisión de la Forma");
		band = 0;
	}
			
	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}

/*Esta función valida que se hayan completado todos los registros en el formulario de registro procedimientos y normas*/
function valFormRegFormLista(frm_agregarRegistro){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	var band = 1;
	
	//Verificar que el campo de txt_claveManual no este vacío
	if(frm_agregarRegistro.txt_claveManual.value==""){
		alert("Ingresar Clave del Manual");
		band = 0;
	}
	
	//Verificar que el campo de txa_tituloManual no este vacío
	if(frm_agregarRegistro.txa_tituloManual.value==""&&band==1){
		alert("Introducir Titulo del Manual");
		band = 0;
	}
	
	//Verificar que el campo de txt_noRevManu no este vacío
	if(frm_agregarRegistro.txt_noRevManu.value==""&&band==1){
		alert("Introducir No. Revisión del Manual");
		band = 0;
	}
	
	//Verificar que el campo de txt_claveClausula no este vacío
	if(frm_agregarRegistro.txt_claveClausula.value==""&&band==1){
		alert("Introducir Clave de Clausula");
		band = 0;
	}
	
	//Verificar que el campo de txt_claveClausula no este vacío
	if(frm_agregarRegistro.txa_tituloClausula.value==""&&band==1){
		alert("Introducir Título Clausula");
		band = 0;
	}
	
	if(frm_agregarRegistro.hdn_claveValida.value!="si"&&band==1){
		alert("Clave de Manual Duplicada; Verifique Id del Registro");
		band = 0;
	}
	
			
	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}



/**************************************************************************************************************************************************************************************************************************/
/*****************************************************************************MODIFICAR LISTA MAESTRA PIF****************************************************************************************************/
/**************************************************************************************************************************************************************************************************************************/

//Función que permite enviar datos via Get asi como validar que se encuentrn llenos los mismos
function envioDatosGetMod(){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	var band = 1;
	
	//Verificar que el campo de txt_claveManual no este vacío
	if(frm_modificarRegistro.txt_claveManual.value==""){
		alert("Ingresar Clave del Manual");
		band = 0;
	}
	
	//Verificar que el campo de txa_tituloManual no este vacío
	if(frm_modificarRegistro.txa_tituloManual.value==""&&band==1){
		alert("Introducir Titulo del Manual");
		band = 0;
	}
	
	//Verificar que el campo de txt_noRevManu no este vacío
	if(frm_modificarRegistro.txt_noRevManu.value==""&&band==1){
		alert("Introducir No. Revisión del Manual");
		band = 0;
	}
	
	//Verificar que el campo de txt_claveClausula no este vacío
	if(frm_modificarRegistro.txt_claveClausula.value==""&&band==1){
		alert("Introducir Clave de Clausula");
		band = 0;
	}
	
	if(band==1){
		//Ponemos el valor de la caja de texto que queremos enviar a la ventana emergente en la clasula
		var clausula = document.getElementById("txt_claveClausula").value; 
		var manual = document.getElementById("txt_claveManual").value;
		var proc = document.getElementById("hdn_procedimiento").value;
		//Al llamar a la ventana le enviamos el valor de la variable por el GET
		window.open('verModFormatos.php?clausula='+clausula+'&manual='+manual+'&proc='+proc+'','_blank','top=50, left=50, width=740, height=680, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
	}
}


/*Esta función valida que se hayan completado todos los registros en el formulario de registro procedimientos y normas*/
function valFormModForm(frm_modificarRegistro){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	var band = 1;
		
	//Verificar que el campo de txt_claveManual no este vacío
	if(frm_modificarRegistro.txt_claveManual.value==""){
		alert("Ingresar Clave del Manual");
		band = 0;
	}
	
	//Verificar que el campo de txa_tituloManual no este vacío
	if(frm_modificarRegistro.txa_tituloManual.value==""&&band==1){
		alert("Introducir Titulo del Manual");
		band = 0;
	}
	
	//Verificar que el campo de txt_noRevManu no este vacío
	if(frm_modificarRegistro.txt_noRevManu.value==""&&band==1){
		alert("Introducir No. Revisión del Manual");
		band = 0;
	}
	
	//Verificar que el campo de txt_claveClausula no este vacío
	if(frm_modificarRegistro.txt_claveClausula.value==""&&band==1){
		alert("Introducir Clave de Clausula");
		band = 0;
	}
	
	//Verificar que el campo de txt_claveClausula no este vacío
	if(frm_modificarRegistro.txa_tituloClausula.value==""&&band==1){
		alert("Introducir Título Clausula");
		band = 0;
	}
			
	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}

/*Esta función valida que se hayan completado todos los registros en el formulario de registro procedimientos y normas*/
function valFormSelecDoc(frm_modificarListaDoc){
	
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	var band = 1;
	
	//Verificar que el combo de cmb_manu haya sido seleccionado
	if(frm_modificarListaDoc.cmb_manu.value==""){
		alert("Seleccionar Manual");
		band = 0;
	}
	
	//Verificar que el combo de cmb_clausula se haya seleccionado
	if(frm_modificarListaDoc.cmb_clausula.value=="" && band==1){
		alert("Seleccionar Clausula");
		band = 0;
	}
	
	//Verificar que el campo de cmb_procedimiento haya sido seleccionado
	if(frm_modificarListaDoc.cmb_procedimiento.value=="" && band==1){
		alert("Seleccionar Procedimiento");
		band = 0;
	}
				
	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
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
	if(combo=='SISAD'){
		location.href = 'frm_consultarBitacoraSISAD.php';	
	}
	if(combo=='PRODUCCION'){
		location.href = 'frm_reporteProduccion.php';	
	}
	if(combo=='COMPRAS'){
		location.href = 'frm_reporteTiemposEntrega.php';	
	}
	if(combo=='GERENCIA'){
		location.href = 'frm_reporteComparativoMensual.php';	
	}
	if(combo=='RECURSOS'){
		location.href = 'frm_seleccionarConsultaRH.php';	
	}
	if(combo=='MANTENIMIENTO'){
		location.href = 'frm_seleccionarConsultaMtto.php';	
	}
}

//Funcion que permtie enviar a la pagina para realizar las consultas de Recursos Humanos
function selConsultaRH(){
	var combo = document.getElementById("cmb_consultaRH").value;
	if(combo=='AUSENTISMO'){
		location.href = 'frm_reporteAusentismo.php';	
	}
	if(combo=='CAPACITACION'){
		location.href = 'frm_consultarCapacitacion.php';	
	}
}

//Funcion que permtie enviar a la pagina para realizar las consultas de mantenimiento
function selConsultaMtto(){
	var combo = document.getElementById("cmb_consultaMtto").value;
	if(combo=='BITACORA'){
		location.href = 'frm_consultarBitacora.php';	
	}
	if(combo=='REALIZADOS'){
		location.href = 'frm_reportePreventivoCorrectivo.php';	
	}
	if(combo=='PROGRAMADOS'){
		location.href = 'frm_consultarOrdenTrabajo.php';	
	}
}

//Funcion que permite validar que se haya ingresado el numero de empleados para las operaciones necesarias en el formulario del reporte comparativo mensual
function complementarReporteComparativoAseguramiento(){
	var noEmpleados=document.getElementById("hdn_employ").value;
	var numValido = true;
	do{
		
		noEmpleados = prompt('Ingrese Cantidad de Empleados',noEmpleados);
		
		//Revisar que el numero sea valido
		if(noEmpleados!=null){
			if(validarEntero(noEmpleados,"El Numero de Empleados"))
				numValido = false;
		}
		else if(noEmpleados==null){
			numValido = false;
			document.getElementById("ckb_idPresupuesto").checked=false;
		}
			
	}while(numValido || noEmpleados=="Cantidad de Empleados Involucrados en la Producción..." || noEmpleados=="")
	
	//Verificar si el usuario cancelo la solicitud	
	if(noEmpleados!=null){
		//Asignar el valor obtenido a la caja de texto que lo mostrara
		document.getElementById("hdn_empleados").value=noEmpleados;
		//Enviar a la pagina donde se mustra el reporte
		document.frm_seleccionarPresupuesto.submit();
	}
			
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
	
	if(botonSel=="modificar"||botonSel=="eliminar"){
		//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
		if(frm_modificarRec.rdb_id.length==undefined && !frm_modificarRec.rdb_id.checked){
			alert("Seleccionar Registro a Modificar/Eliminar");
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
				alert("Seleccionar Registro a Modificar/Eliminar");			
		}
	}
	if(botonSel=="eliminar"&&res==1){
		if(!confirm("¿Estas Seguro que Quieres Eliminar El Registro?\nToda la información Relacionada con Dicho Registro Será Eliminada")){
			res = 0;
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

//Función que permite seleccionar Todos los checkbox de el formulario VerAchivo
function quitarArch(checkbox){
	if(!checkbox.checked){
		document.getElementById("ckb_todo").checked=false;
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


/***************************************************************************************************************************************************************/
/*****************************************************FORMULARIO REGISTRAR AUDITORIAS***************************************************************************/
/***************************************************************************************************************************************************************/

/*Esta función valida que se hayan completado todos los registros en el formulario registrar Plan Acciones*/
function valFormPlanAcciones(frm_agregarPA){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	var band = 1;
	
	//Verificar que el combo de area auditada haya sido seleccionado
	if(frm_agregarPA.cmb_depto.value==""){
		alert("Seleccionar Área Auditada");
		band = 0;
	}
	
	//Verificar que se haya introducido el creador
	if(frm_agregarPA.txt_creador.value=="" && band==1){
		alert("Introducir Creador");
		band = 0;
	}
	
	//Verificar que se haya introducido el aprobadir
	if(frm_agregarPA.txt_aprobado.value=="" && band==1){
		alert("Introducir Aprobador");
		band = 0;
	}
	
	//Verificar que se haya introducido el verificador
	if(frm_agregarPA.txt_verificado.value=="" && band==1){
		alert("Introducir Verificador");
		band = 0;
	}
	
	//Verificar que se hayan introducido los participantes de la auditoria
	if(frm_agregarPA.txt_paticipantesAu.value=="" && band==1){
		alert("Introducir Participantes Auditoria");
		band = 0;
	}
		
	//Verificar que se haya introdudico el No de Documento
	if(frm_agregarPA.txt_NoDoc.value=="" && band==1){
		alert("Introducir No. Documento");
		band = 0;
	}
	
	//Verificar que se haya introducido el numero de revision
	if(frm_agregarPA.txt_rev.value=="" && band==1){
		alert("Introducir No. de Revisión");
		band = 0;
	}
	
	//Verificar que se hayan introducido los departamentos
	if(frm_agregarPA.txt_ubicacion.value=="" && band==1){
		alert("Introducir Departamentos");
		band = 0;
	}
	
	//Verificar que se hayan introducido los departamentos
	if(frm_agregarPA.txt_referencias.value=="" && band==1){
		alert("Introducir Referencia");
		band = 0;
	}
	
	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}


//Funcion que permite agregar las referencias al Plan de Acciones
function abrirRegRef(){
	//Al llamar a la ventana le enviamos el valor de la variable por el GET
		window.open('verRegReferencias.php','_blank','top=50, left=50, width=740, height=450, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
}



/*Esta función valida que se haya seleccionado un departamento en el pop-up verDepartamentos.php*/
function valFormPart(frm_verPart){	
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
		idCheckBox="ckb_dpto"+ctrl.toString();
		
		//Verificar que la cantidad y la aplicación del Checkbox seleccionado no esten vacias
		if(document.getElementById(idCheckBox).checked){
			status = 1;
		}
		ctrl++;
	}//Fin del While	
	
	
	//Verificar que al menos un equipo haya sido seleccionado, si la variable status vale 1, quiere decir que al menos un departamento fue seleccionado
	if(status==0){
		alert("Seleccionar al Menos un Participante");
		res = 0;
	}
	if(res==1)
		return true;
	else
		return false;		
}


/***************************************************************************************************************************************************************/
/*****************************************************FORMULARIO MODIFICAR AUDITORIAS***************************************************************************/
/***************************************************************************************************************************************************************/

//Funcion que permite validar que todos los campos del formulario registrarReferencias esten llenos y correctos
function valFormRegRef(frm_agregarRegistro){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
	
	//Verificar que el campo de txt_claveClausula no este vacío
	if(frm_agregarRegistro.txt_cveRef.value==""){
		alert("Introducir Clave de Referencia");
		res = 0;
	}
	
	//Verificar que el campo de txt_claveClausula no este vacío
	if(frm_agregarRegistro.txa_referencia.value==""&&res==1){
		alert("Introducir Referencia");
		res = 0;
	}
	
	if(res==1)
		return true;	
	else
		return false;
}

//Funcion que permite agregar las referencias al Plan de Acciones
function abrirModRef(){
	//variable que en la cual se almacenara el id de el plan de acciones; esto para poder mostrar que referencias tiene registrado dicho plan
	var idPlan=document.getElementById("rdb_id").value;
	var idElemento=document.getElementById("txt_detallePA").value;
	if(idElemento=="SI"){
		if(confirm("Al Abrir Este Registro el Complemento  del Departamento se Perdera\n ¿Estas Seguro que Quieres Eliminar El Registro?")){
			//Al llamar a la ventana le enviamos el valor de la variable por el GET
				window.open('verModReferencias.php?idPlan='+idPlan+'&elemento='+idElemento+'','_blank','top=50, left=50, width=740, height=450, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');	
		}
	} 
	else{
		//Al llamar a la ventana le enviamos el valor de la variable por el GET
		window.open('verModReferencias.php?idPlan='+idPlan+'&elemento='+idElemento+'','_blank','top=50, left=50, width=740, height=450, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
	}
}

/*Esta función valida que se seleccione un archivo  en el formulario Resultados encontrados*/
function valFormPA(frm_modificarPA){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
	//VAriable donde se almacena el valor del boron seleccionado
	var botonSel=document.getElementById("hdn_botonSel").value;
	
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_modificarPA.rdb_id.length==undefined && !frm_modificarPA.rdb_id.checked){
		alert("Seleccionar Registro a Modificar/Eliminar/Complementar");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_modificarPA.rdb_id.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_modificarPA.rdb_id.length;i++){
			if(frm_modificarPA.rdb_id[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar Registro a Modificar/Eliminar/Complementar");			
	}
	if(botonSel=="modificar"&&res==1){
		document.frm_modificarPA.action = "frm_modificarPlanAcciones2.php";
	}
	
	if(botonSel=="complementar"&&res==1){
		document.frm_modificarPA.action = "frm_complementarPlanAcciones.php?band=1";
	}
	
	if(botonSel=="eliminar"&&res==1){ 
		if(!confirm("¿Estas Seguro que Quieres Eliminar El Registro?\nToda la información Relacionada con Dicho Registro Será Eliminada")){
			res=0;
		}
	}
	if(res==1)
		return true;	
	else
		return false;
}

//Funcion que permite validar el formulario de modificar plan de acciones 
function valFormSelDpto(){
	//Variable para controlar el proceso de validacion
	var band=1;
	
	//Variable que almacenara el valor del combo departamentos
	var combo=document.getElementById("cmb_depto").value;
	if(combo==""){
		alert("Seleccionar Departamento");
		band=0;
	}
	
	if(band==1)
		return true;	
	else
		return false;
}


/*Esta función valida los datos al complementar el plan de acciones*/
function valFormReferenciasDepto(frm_complementarPAE){	
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var res = 1;
	//Variable para controlar la cantidad de registros
	var ctrl= 1;
	//Variable para almacenar la cantidad de registros
	var cantidad = document.getElementById("hdn_cant").value-1;
	//Variables que almacenara el nombre de cada CheckBox Seleccionado y las cajas de texto de cantidad y aplicación relacionada a el
	idTxtJust = "";
	idTxtAccPla = "";
	idTxtFechPla = "";
	var idHdnNombre = "";
	
	while(ctrl<=cantidad){		
		//Crear el id del Caja de Texto Oculta de Nombre
		idHdnNombre = "hdn_nombre"+ctrl.toString();
		var nombre = document.getElementById(idHdnNombre).value;
		//Crear el id de la Caja de Texto de la justificacion
		idTxtJust = "txa_justificacion"+ctrl.toString();
		//Crear el id de la Caja de Texto del Horometro Final
		idTxtAccPla = "txa_accPla"+ctrl.toString();
		//Crear el id de la Caja de Texto de las Horas Muertas 
		idTxtFechPla = "txt_fechaPla"+ctrl.toString();
		
		if(document.getElementById(idTxtJust).value==""){				
			alert("Ingresar Justificacion para Referencia Número "+ nombre);
			res = 0;
			break;
		}
		//Validar que se haya ingresado el horometro final
		if(document.getElementById(idTxtAccPla).value==""&&res==1){
			alert("Ingresar Acción Planeada para Referencia Número "+nombre);
			res = 0;
			break;
		}
		//Validar que las horas muertas hayan sido ingresadas
		if(document.getElementById(idTxtFechPla).value==""&& res==1){
			alert("Ingresar Fecha Planeada para Referencia Número "+nombre);
			res = 0;
			break;
		}
		ctrl++;
	}//Fin del While	
	

	if(res==1)
		return true;
	else
		return false;		
}


/*Esta función valida los datos al complementar el plan de acciones*/
function valFormReferencias(frm_complementarPA){	
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var res = 1;
	//Variable para controlar la cantidad de registros
	var ctrl= 1;
	//Variable para almacenar la cantidad de registros
	var cantidad = document.getElementById("hdn_cant").value-1;
	//Variables que almacenara el nombre de cada CheckBox Seleccionado y las cajas de texto de cantidad y aplicación relacionada a el
	idTxtJust = "";
	idTxtAccPla = "";
	idTxtFechPla = "";
	idTxtFechReal = "";
	idTxtValAse = "";
	var idHdnNombre = "";
	
	while(ctrl<=cantidad){		
		//Crear el id del Caja de Texto Oculta de Nombre
		idHdnNombre = "hdn_nombre"+ctrl.toString();
		var nombre = document.getElementById(idHdnNombre).value;
		//Crear el id de la Caja de Texto de la justificacion
		idTxtJust = "txa_justificacion"+ctrl.toString();
		//Crear el id de la Caja de Texto del Horometro Final
		idTxtAccPla = "txa_accPla"+ctrl.toString();
		//Crear el id de la Caja de Texto de las Horas Muertas 
		idTxtFechPla = "txt_fechaPla"+ctrl.toString();
		//Crear el id de la Caja de Texto de las Horas Muertas 
		idTxtFechReal = "txt_fechaReal"+ctrl.toString();
		//Crear el id de la Caja de Texto de las Horas Muertas 
		idTxtValAse = "txa_valASE"+ctrl.toString();

		
		if(document.getElementById(idTxtJust).value==""){				
			alert("Ingresar Justificacion para Referencia Número "+ nombre);
			res = 0;
			break;
		}
		//Validar que se haya ingresado el horometro final
		if(document.getElementById(idTxtAccPla).value==""&&res==1){
			alert("Ingresar Acción Planeada para Referencia Número "+nombre);
			res = 0;
			break;
		}
		//Validar que las horas muertas hayan sido ingresadas
		if(document.getElementById(idTxtFechPla).value==""&& res==1){
			alert("Ingresar Fecha Planeada para Referencia Número "+nombre);
			res = 0;
			break;
		}
		if(document.getElementById(idTxtFechReal).value==""&& res==1){
			alert("Ingresar Fecha Real para Referencia Número "+nombre);
			res = 0;
			break;
		}
		
		if(document.getElementById(idTxtValAse).value==""&& res==1){
			alert("Ingresar Validación para la Referencia Número "+nombre);
			res = 0;
			break;
		}
		ctrl++;
	}//Fin del While	
	
	
	if(res==1)
		return true;
	else
		return false;		
}

/*Esta función valida los datos al complementar el plan de acciones*/
function valFormReferenciasConGet(frm_complementarPA){	
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var res = 1;
	//Variable para controlar la cantidad de registros
	var ctrl= 1;
	//Variable para almacenar la cantidad de registros
	var cantidad = document.getElementById("hdn_cant").value-1;
	//Variables que almacenara el nombre de cada CheckBox Seleccionado y las cajas de texto de cantidad y aplicación relacionada a el
	var idCheckBox = "";
	idTxtJust = "";
	idTxtAccPla = "";
	idTxtFechPla = "";
	idTxtFechReal = "";
	idTxtValAse = "";
	var idHdnNombre = "";
	
	while(ctrl<=cantidad){		
		//Crear el id del Caja de Texto Oculta de Nombre
		idHdnNombre = "hdn_nombre"+ctrl.toString();
		var nombre = document.getElementById(idHdnNombre).value;
		//Crear el id de la Caja de Texto de la justificacion
		idTxtJust = "txa_justificacion"+ctrl.toString();
		//Crear el id de la Caja de Texto del Horometro Final
		idTxtAccPla = "txa_accPla"+ctrl.toString();
		//Crear el id de la Caja de Texto de las Horas Muertas 
		idTxtFechPla = "txt_fechaPla"+ctrl.toString();
		//Crear el id de la Caja de Texto de las Horas Muertas 
		idTxtFechReal = "txt_fechaReal"+ctrl.toString();
		//Crear el id de la Caja de Texto de las Horas Muertas 
		idTxtValAse = "txa_valASE"+ctrl.toString();
			
		if(document.getElementById(idTxtJust).value==""){				
			alert("Ingresar Justificacion para Referencia Número "+ nombre);
			res = 0;
			break;
		}
		//Validar que se haya ingresado el horometro final
		if(document.getElementById(idTxtAccPla).value==""&&res==1){
			alert("Ingresar Acción Planeada para la Referencia Número "+nombre);
			res = 0;
			break;
		}
		//Validar que las horas muertas hayan sido ingresadas
		if(document.getElementById(idTxtFechPla).value==""&&res==1){
			alert("Ingresar Fecha Planeada para la Rereferencia Número "+nombre);
			res = 0;
			break;
		}
		if(document.getElementById(idTxtFechReal).value==""&& res==1){
			alert("Ingresar Fecha Real para la Referencia Número "+nombre);
			res = 0;
			break;
		}
		if(document.getElementById(idTxtValAse).value==""&& res==1){
			alert("Ingresar Validación para la Referencia Número "+nombre);
			res = 0;
			break;
		}
		ctrl++;
	}//Fin del While	
	
	if(res==1)
		return true;
	else
		return false;		
}


/***************************************************************************************************************************************************************/
/*****************************************************FORMULARIO COMPLEMENTAR AUDITORIAS***************************************************************************/
/***************************************************************************************************************************************************************/


/*Esta función valida que se seleccione un archivo  en el formulario Resultados encontrados*/
function valFormPlanAlertas(frm_verRec){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
	
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_verRec.rdb_id.length==undefined && !frm_verRec.rdb_id.checked){
		alert("Seleccionar Registro a Complementar");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_verRec.rdb_id.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_verRec.rdb_id.length;i++){
			if(frm_verRec.rdb_id[i].checked)
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

/*Esta función valida que se seleccione un archivo  en el formulario Resultados encontrados*/
function valFormCompPA(frm_complementarPA){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_complementarPA.rdb_id.length==undefined && !frm_complementarPA.rdb_id.checked){
		alert("Seleccionar Registro Para Complementar");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_complementarPA.rdb_id.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_complementarPA.rdb_id.length;i++){
			if(frm_complementarPA.rdb_id[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar Registro Para Complementar");			
	}
	
	if(res==1)
		return true;	
	else
		return false;
}


/***************************************************************************************************************************************************************/
/*****************************************************a***********ALERTAS***************************************************************************************/
/***************************************************************************************************************************************************************/

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

