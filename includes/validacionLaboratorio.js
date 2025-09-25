/**
  * Nombre del Módulo: Laboratorio                                               
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 17/Junio/2011                                      			
  * Descripción: Este archivo contiene funciones para validar los diferentes formularios del Módulo Laboratorio
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

/*Esta funcion solicita la confirmación del usuario antes de salir de la pagina*/
function confirmarSalida(pagina){
	if(confirm("¿Estas Seguro que Quieres Salir?\nToda la información no Guardada se Perderá"))
		location.href = pagina;	
}



/***************************************************************************************************************************************/
/****************************************COMIENZAN LAS FUNCIONES DE CADA FORMULARIO*****************************************************/
/***************************************************************************************************************************************/

/***************************************************************************************************************************************/
/*******************************************************AGREGAR PRUEBAS*****************************************************************/
/***************************************************************************************************************************************/
/*Esta función valida que sean completos los datos del formulario de Agregar Pruebas*/
function valFormAgregarPrueba(frm_agregarPrueba){
	//Variable de control de validacion
	var band=0;
	if (frm_agregarPrueba.txt_norma.value==""){
		if(!confirm("El Sistema Registrará N/A como Norma. ¿Es Correcto?"))
			band=1;
	}
	if (frm_agregarPrueba.txt_nombre.value==""&&band==0){
		alert("Ingrese el Nombre de la Prueba");
		band=1;
	}
	if (frm_agregarPrueba.cmb_tipo.value==""&&frm_agregarPrueba.txt_nuevoTipo.value==""&&band==0){
		alert("Es Necesario Proporcionar el Tipo de Prueba");
		band=1;
	}
	if (band==1)
		return false;
	else
		return true;
}

//Esta funcion solicita al usuario la nueva cantidad y  limpia el combo cantidad
function nuevoTipo(){
	//Si el checkbox para el nuevo Tipo esta seleccionado, pedir el Tipo
	if (document.getElementById("ckb_nuevoTipo").checked){
		do{
			//Variable de control de mostrar mensajes
			var condicion=false
			var tipo = prompt("¿Nuevo Tipo?","Ingrese el Tipo de Prueba...");	
			if(tipo!=null && tipo!="Ingrese el Tipo de Prueba..." && tipo!=""){
				document.getElementById("txt_nuevoTipo").value=tipo;
				document.getElementById("txt_nuevoTipo").readOnly=true;
				document.getElementById("txt_nuevoTipo").disabled=false;
				document.getElementById("cmb_tipo").value="";
				document.getElementById("cmb_tipo").disabled=true;
				condicion=true;
			}else
				document.getElementById("ckb_nuevoTipo").checked=false;
			//Si no se asigna un Tipo y se presiona Cancelar, salir del Ciclo
			if (tipo==null){
				condicion=true;
				document.getElementById("ckb_nuevoTipo").checked=false;
			}
		}while(!condicion);
	}
	else{
		document.getElementById("txt_nuevoTipo").value="";
		document.getElementById("txt_nuevoTipo").readOnly=false;
		document.getElementById("txt_nuevoTipo").disabled=true;
		document.getElementById("cmb_tipo").disabled=false;
	}
}

/***************************************************************************************************************************************/
/******************************************************ELIMINAR PRUEBAS*****************************************************************/
/***************************************************************************************************************************************/
/*Esta función valida que se seleccione una prueba en el primer formulario de Borrar Pruebas*/
function valFormEliminarPrueba1(frm_eliminarPruebaXTipo){
	if (frm_eliminarPruebaXTipo.cmb_tipo.value==""){
		alert("Seleccionar un Tipo de Prueba");
		return false;
	}
	else
		return true;
}

/*Esta función valida que se seleccione una norma en el segundo formulario de Borrar Pruebas*/
function valFormEliminarPrueba2(frm_eliminarPruebaXNorma){
	if (frm_eliminarPruebaXNorma.cmb_norma.value==""){
		alert("Seleccionar una Norma de Prueba");
		return false;
	}
	else
		return true;
}

/*Esta función valida que se seleccione una prueba en el formulario Resultados encontrados*/
function valFormEliminarPrueba(frm_eliminarPrueba){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_eliminarPrueba.rdb_id.length==undefined && !frm_eliminarPrueba.rdb_id.checked){
		alert("Seleccionar la Prueba para Borrarla");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_eliminarPrueba.rdb_id.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_eliminarPrueba.rdb_id.length;i++){
			if(frm_eliminarPrueba.rdb_id[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar la Prueba a Borrar");			
	}
	
	if(res==1)
		return true;
	else
		return false;
}

/***************************************************************************************************************************************/
/******************************************************MODIFICAR PRUEBAS****************************************************************/
/***************************************************************************************************************************************/
/*Esta función valida que se seleccione una prueba en el primer formulario de Borrar Pruebas*/
function valFormModificarPrueba1(frm_modificarPruebaXTipo){
	if (frm_modificarPruebaXTipo.cmb_tipo.value==""){
		alert("Seleccionar un Tipo de Prueba");
		return false;
	}
	else
		return true;
}

/*Esta función valida que se seleccione una norma en el segundo formulario de Borrar Pruebas*/
function valFormModificarPrueba2(frm_modificarPruebaXNorma){
	if (frm_modificarPruebaXNorma.cmb_norma.value==""){
		alert("Seleccionar una Norma de Prueba");
		return false;
	}
	else
		return true;
}

/*Esta función valida que se seleccione una prueba en el formulario Resultados encontrados*/
function valFormSeleccionarPruebaModificar(frm_modificarPrueba){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_modificarPrueba.rdb_id.length==undefined && !frm_modificarPrueba.rdb_id.checked){
		alert("Seleccionar la Prueba para Modificarla");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_modificarPrueba.rdb_id.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_modificarPrueba.rdb_id.length;i++){
			if(frm_modificarPrueba.rdb_id[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar la Prueba a Modificar");			
	}
	
	if(res==1)
		return true;
	else
		return false;
}

/*Funcion que se encarga de validar los datos que seran modificados*/
function valFormModificarPrueba(frm_modificarPrueba){
	//Variable de control de validacion
	var band=0;
	if (frm_modificarPrueba.txt_norma.value==""){
		if(!confirm("El Sistema Registrará N/A como Norma. ¿Es Correcto?"))
			band=1;
	}
	if (frm_modificarPrueba.cmb_tipo.value==""&&frm_modificarPrueba.txt_nuevoTipo.value==""&&band==0){
		alert("Es necesario Proporcionar el Tipo de Prueba");
		band=1;
	}
	if (band==1)
		return false;
	else
		return true;
}

/***************************************************************************************************************************************/
/*****************************************************CONSULTAR PRUEBAS*****************************************************************/
/***************************************************************************************************************************************/
/*Esta función valida que se seleccione una prueba en el primer formulario de Borrar Pruebas*/
function valFormConsultarPrueba1(frm_consultarPruebaXTipo){
	if (frm_consultarPruebaXTipo.cmb_tipo.value==""){
		alert("Seleccionar un Tipo de Prueba");
		return false;
	}
	else
		return true;
}

/*Esta función valida que se seleccione una norma en el segundo formulario de Borrar Pruebas*/
function valFormConsultarPrueba2(frm_consultarPruebaXNorma){
	if (frm_consultarPruebaXNorma.cmb_norma.value==""){
		alert("Seleccionar una Norma de Prueba");
		return false;
	}
	else
		return true;
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

/***************************************************************************************************************************************************************/
/*****************************************************FORMULARIO AGREGAR MEZCLA************************************************************************/
/***************************************************************************************************************************************************************/
/*Esta función valida que los campos necesarios para el formulario frm_agregarMezcla esten completados*/
function valFormAgregarMezcla(frm_agregarMezcla){
	//Variable bandera que permite revisar si la validacion fue exitosa
	var band=1;
		
		
	if (frm_agregarMezcla.txt_idMezcla.value==""){
		alert("Introducir el ID de la Mezcla");
		band=0;
	}
		
	if (frm_agregarMezcla.txt_nombreMezcla.value==""&&band==1){
		alert("Introducir el Nombre de la Mezcla");
		band=0;
	}
	
	if (frm_agregarMezcla.txt_expediente.value==""&&band==1){
		alert("Introducir el Número de Expediente");
		band=0;
	}
	
	if(band==1){
		if(!validarEntero(frm_agregarMezcla.txt_expediente.value,"El Expediente"))
			band=0;
	}
		
	if (frm_agregarMezcla.txt_eqMezclado.value==""&&band==1){
		alert("Introducir el Equipo de Mezclado");
		band=0;
	}				
	
	//Verificar que la clave introducida no este repetida.
	if(frm_agregarMezcla.hdn_claveValida.value=='no'&& band==1){
		alert("Id de Mezcla Duplicado");
		band=0;
	}
	
	if (band==1)
		return true;
	else
		return false;
}


/***************************************************************************************************************************************************************/
/*****************************************************FORMULARIO AGREGAR MEZCLA2************************************************************************/
/***************************************************************************************************************************************************************/
/*Esta función valida que los campos necesarios para el formulario frm_agregarMezcla2 esten completados*/
function valFormAgregarMezcla2(frm_agregarMezcla2){
	//Variable bandera que permite revisar si la validacion fue exitosa
	var band = 1;
	
	if(frm_agregarMezcla2.hdn_botonSel.value=='agregar'){
	
		if (frm_agregarMezcla2.cmb_categoria.value==""){
			alert("Seleccionar una categoria");
			band=0;
		}
		
		if (frm_agregarMezcla2.cmb_nombre.value==""&&band==1){
			alert("Seleccionar un Material");
			band=0;
		}
			
		if (frm_agregarMezcla2.txt_cantidad.value==""&&band==1){
			alert("Introducir la Cantidad de Material Seleccionado");
			band=0;
		}
						
		if(band==1){
			if(!validarEntero(frm_agregarMezcla2.txt_cantidad.value.replace(/,/g,''),"El Cantidad"))
				band=0;
		}
		
		if (frm_agregarMezcla2.txt_unidadMedida.value==""&&band==1){
			alert("Introducir la Unidad de Medida del Material Seleccionado");
			band=0;
		}
				
	}//Cierre if(frm_agregarMezcla2.hdn_botonSel.value=='agregar')

	if (band==1)
		return true;
	else
		return false;
}

/***************************************************************************************************************************************************************/
/*****************************************************FORMULARIO ELIMINAR MEZCLA************************************************************************/
/***************************************************************************************************************************************************************/
//funcion para validar el formulario frm_reporteFecha cuando se selecciona la opcion por fecha
function valFormEliminarMezclaFecha(frm_eliminarMezcla2){
	//Variable que permite revisar si la validación fue exitosa
	var band=1;
	//Verificamos que el combo area este definido
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_eliminarMezcla2.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_eliminarMezcla2.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_eliminarMezcla2.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_eliminarMezcla2.txt_fechaFin.value.substr(0,2);
	var finMes=frm_eliminarMezcla2.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_eliminarMezcla2.txt_fechaFin.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	
	//Verificar que la fecha de inicio no sea mayor a la de cierre
	if(fechaIni>fechaFin){
		band=0;
		alert ("La Fecha de Inicio no puede ser Mayor a la Fecha de Fin");
	}
	if (band==1)
		return true;
	else
		return false;
	
}

/*Esta función valida que los campos necesarios para el formulario frm_eliminarMezcla esten completados eliminar por clave*/
function valFormEliminarMezclaClave(frm_eliminarMezcla){
	//Variable bandera que permite revisar si la validacion fue exitosa
	var band=1;
		
	if (frm_eliminarMezcla.cmb_claveMezcla.value==""){
		alert("Seleccionar una Mezcla");
		band=0;
	}
			
	if (band==1)
		return true;
	else
		return false;
}

/*Esta funcion se ecnarga de Validar el Formulario donde se selecciona la estimación para borrar*/
function valFormEliminarMezcla(frm_eliminarMezcla){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_eliminarMezcla.rdb.length==undefined && !frm_eliminarMezcla.rdb.checked){
		alert("Seleccionar la Mezcla a Borrar");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_eliminarMezcla.rdb.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_eliminarMezcla.rdb.length;i++){
			if(frm_eliminarMezcla.rdb[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar la Mezcla a Borrar");			
	}
	
	if (res==1){
		
		if (!confirm("¿Estas Seguro que Quieres Borrar la Mezcla?\nToda la información relacionada se Borrará")){
			res=0;
		}
	}
	
	if(res==1)
		return true;
	else
		return false;
}

/***************************************************************************************************************************************************************/
/*****************************************************FORMULARIO MODIFICAR MEZCLA************************************************************************/
/***************************************************************************************************************************************************************/
//funcion para validar el formulario frm_reporteFecha cuando se selecciona la opcion por fecha
function valFormModificarMezclaFecha(frm_modificarMezcla2){
	//Variable que permite revisar si la validación fue exitosa
	var band=1;
	//Verificamos que el combo area este definido
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_modificarMezcla2.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_modificarMezcla2.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_modificarMezcla2.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_modificarMezcla2.txt_fechaFin.value.substr(0,2);
	var finMes=frm_modificarMezcla2.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_modificarMezcla2.txt_fechaFin.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	
	//Verificar que la fecha de inicio no sea mayor a la de cierre
	if(fechaIni>fechaFin){
		band=0;
		alert ("La Fecha de Inicio no puede ser Mayor a la Fecha de Fin");
	}
	if (band==1)
		return true;
	else
		return false;
	
}




/*Esta función valida que los campos necesarios para el formulario frm_modificarMezcla esten completados modificar por clave*/
function valFormModificarMezclaClave(frm_modificarMezcla){
	//Variable bandera que permite revisar si la validacion fue exitosa
	var band=1;
		
	if (frm_modificarMezcla.cmb_claveMezcla.value==""){
		alert("Seleccionar una Mezcla");
		band=0;
	}
			
	if (band==1)
		return true;
	else
		return false;
}

/*Esta funcion se ecnarga de Validar el Formulario donde se selecciona la mezcla para modificar*/
function valFormModificarMezcla(frm_modificarMezcla){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_modificarMezcla.rdb.length==undefined && !frm_modificarMezcla.rdb.checked){
		alert("Seleccionar la Mezcla a Modificar");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_modificarMezcla.rdb.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_modificarMezcla.rdb.length;i++){
			if(frm_modificarMezcla.rdb[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar la Mezcla a Modificar");			
	}
	
	if(res==1)
		return true;
	else
		return false;
}


/*Esta funcion se ecnarga de Validar el Formulario donde se selecciona la mezcla para modificar*/
function valFormModificarMat(frm_modificarMezcla){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
	
	if(frm_modificarMezcla.hdn_botonSel.value!='regresar'){
	
		//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
		if(frm_modificarMezcla.rdb.length==undefined && !frm_modificarMezcla.rdb.checked){
			alert("Seleccionar el Material a Borrar");
			res = 0;
		}
		//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
		if(frm_modificarMezcla.rdb.length>=2){
			//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
			res = 0; 
			//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
			for(i=0;i<frm_modificarMezcla.rdb.length;i++){
				if(frm_modificarMezcla.rdb[i].checked)
					res = 1;
			}
			if(res==0)
				alert("Seleccionar el Material a Borrar");			
		}
	}
	
	if(res==1)
		return true;
	else
		return false;
}


/***************************************************************************************************************************************************************/
/********************************************************FORMULARIO CONSULTAR MEZCLA****************************************************************************/
/***************************************************************************************************************************************************************/
//funcion para validar el formulario frm_reporteFecha cuando se selecciona la opcion por fecha
function valFormConsultarMezclaFecha(frm_consultarMezclaFecha){
	//Variable que permite revisar si la validación fue exitosa
	var band=1;
	//Verificamos que el combo area este definido
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_consultarMezclaFecha.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_consultarMezclaFecha.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_consultarMezclaFecha.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_consultarMezclaFecha.txt_fechaFin.value.substr(0,2);
	var finMes=frm_consultarMezclaFecha.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_consultarMezclaFecha.txt_fechaFin.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	
	//Verificar que la fecha de inicio no sea mayor a la de cierre
	if(fechaIni>fechaFin){
		band=0;
		alert ("La Fecha de Inicio no puede ser Mayor a la Fecha de Fin");
	}
	if (band==1)
		return true;
	else
		return false;
	
}


/*Esta función valida que los campos necesarios para el formulario frm_consultarMezcla esten completados  por clave*/
function valFormConsultarMezclaClave(frm_consultarMezclaClave){
	//Variable bandera que permite revisar si la validacion fue exitosa
	var band=1;
		
	//Verificar que el usuario seleccione Código/Localización y despues una muestra 	
	if (frm_consultarMezclaClave.cmb_claveMezcla.value==""){
		alert("Seleccionar una Mezcla");
		band=0;
	}
			
	if (band==1)
		return true;
	else
		return false;
}


/***************************************************************************************************************************************************************/
/**************************************************************GESTIONAR MUESTRAS*******************************************************************************/
/***************************************************************************************************************************************************************/
/*Esta función redirecciona a la pagina correspondiente según la opcion seleccionada*/
function valFormSeleccionarOpc(frm_seleccionarOpc){
	//Si el valor de la variable "validacion" permanece en 1, el proceso de validación fue satisfactorio
	var validacion = 1;
	
	if(frm_seleccionarOpc.cmb_opcion.value==""){
		alert("Seleccionar Operación a Realizar");
		validacion = 0;
	}
	
	if(validacion==1){
		if(frm_seleccionarOpc.cmb_opcion.value=="registrar"){
			frm_seleccionarOpc.action = "frm_registrarMuestras.php";
		}
		else if(frm_seleccionarOpc.cmb_opcion.value=="editar"){
			frm_seleccionarOpc.action = "frm_editarMuestras.php";
		}
	}
	
	if(validacion==1)
		return true;
	else
		return false;
}//Cierre de la función valFormSeleccionarOpc(frm_seleccionarOpc)


/*Esta funcion valida el formulario de agregar muestra*/
function valFormRegistrarMuestra(frm_registrarMuestra){
	//Si el valor de la variable "validacion" permanece en 1, el proceso de validación fue satisfactorio
	var validacion = 1;
	
	//Verificar que sea seleccionada una Mezcla
	if(frm_registrarMuestra.cmb_idMezcla.value==""){
		alert("Seleccionar Mezcla");
		validacion = 0;
	}
	
	//Verificar que sea seleccionado un tipo de prueba
	if(frm_registrarMuestra.cmb_tipoPrueba.value=="" && validacion==1){
		alert("Seleccionar Tipo de Prueba");
		validacion = 0;
	}
	
	//Si las opciones seleccionadas en el tipo de prueba son Obra de Zarpeo u Obra Externa, solicitar el No. de Muestra
	if((frm_registrarMuestra.cmb_tipoPrueba.value=="OBRA DE ZARPEO" || frm_registrarMuestra.cmb_tipoPrueba.value=="OBRA EXTERNA") && validacion==1){
		if(frm_registrarMuestra.txt_noMuestra.value==""){
			alert("Introducir el No. de Muestra");
			validacion = 0;
		}
	}	
	
	//Solicitar el dato de Revenimiento y validar que sea mayor que cero
	if(frm_registrarMuestra.txt_revenimiento.value=="" && validacion==1){
		alert("Introducir la Cantidad de Revenimiento");
		validacion = 0;
	}
	if(validacion==1){
		if(!validarEntero(frm_registrarMuestra.txt_revenimiento.value.replace(/,/g,''),"El Revenimiento"))
			validacion = 0;
	}
	
	//Solicitar la Resitencia de la Muestra y verificar que sea un numero mayor a 0
	if(frm_registrarMuestra.txt_fProyecto.value=="" && validacion==1){
		alert("Introducir el Valor de F' c Proyecto");
		validacion = 0;
	}
	if(validacion==1){
		if(!validarEntero(frm_registrarMuestra.txt_fProyecto.value.replace(/,/g,''),"F' c Proyecto"))
			validacion = 0;
	}
	
	//Si las opciones seleccionadas en el tipo de prueba son Obra de Zarpeo u Obra Externa, solicitar la Localización
	if((frm_registrarMuestra.cmb_tipoPrueba.value=="OBRA DE ZARPEO" || frm_registrarMuestra.cmb_tipoPrueba.value=="OBRA EXTERNA") && validacion==1){
		if(frm_registrarMuestra.cmb_localizacion.value==""){
			alert("Seleccionar la Localización de Donde se Obtuvo la Muestra");
			validacion = 0;
		}
	}
	
	
	if(validacion==1)
		return true;
	else
		return false;
}//Cierre de la función valFormRegistrarMuestra(frm_registrarMuestra)


/* Esta funcion crea el ID de la Muestra a partir de la Localización de la muetsra o el Código de Concreto
 * origen 1: El ID sera calculado con la Localización y el No. de Muestra.
 * origen 2: El ID será calculado con el Codigo de CONCRETO(ID de la Mezcla) y el Consecutivo según los registros en la BD
 */
function calcularIdMuestra(origen){
	//Calcular el ID para las Pruebas de Obras de Zarpeo u Obras Externas 
	if(origen==1){
		//Obtener los valores necesarios para crear la Clave	
		var clave = "";
		var localizacion = document.getElementById("cmb_localizacion").value.toUpperCase();
		var noMuestra = parseInt(document.getElementById("txt_noMuestra").value);
		
		//Si fueron proporcionados el Código o la Ubicacion y el numero de muestra proceder a crear la clave
		if(noMuestra!="" && localizacion!=""){
			if(parseInt(noMuestra)<10)
				clave = localizacion+"-0"+noMuestra;
			else
				clave = localizacion+"-"+noMuestra;
		}				
		//Asignar la clave creada a la caja de texto que la mostrará.
		document.getElementById("txt_idMuestra").value = clave;
		
		//Una vez Creado el ID de la Muestra verificar que no este registrado en la Base de Datos de Laboratorio
		verificarIdMuestra(clave);
		
	}
	else if(origen==2){
		//Obtener el código de CONCRETO
		var codigo = document.getElementById("txt_codigo").value;
		//Buscar en la Base de Datos para ver si hay registros previos para esta Mezcla y asignar el No. de Muestra correspondiente
		obtenerIdMuestraConcreto(codigo);//Funcion AJAX		
	}
}//Cierre de la función calcularIdMuestra()


/*Esta funcion activa los campos requeridos según la opción seleccionada*/
function activarCampos(combo){	
	//Activar los campos requeridos para registrar una muestra de Concreto
	if(combo.value=="CONCRETO"){
		//Verificar si ha sido seleccionada una Mezcla para asignar el Codigo de la Muestra al Codigo de Concreto
		if(document.getElementById("cmb_idMezcla").value!=""){
			document.getElementById("txt_codigo").value = document.getElementById("cmb_idMezcla").value;
			//Calcular el ID para la muestra de CONCRETO que será probada
			calcularIdMuestra(2);
		}
		else{
			alert("Seleccionar Mezcla Para Asignar Código");
		}
				
		//Deshabilitar el ComboBox de Localización y la caja de texto de No Muestra y borrar el contenido previo de Localización
		document.getElementById("txt_noMuestra").readOnly = true;
		document.getElementById("cmb_localizacion").disabled = true;
		document.getElementById("cmb_localizacion").value = "";
		
		//Borrar la clave previa calculada, ya que cambio el valor del combo de Tipo Prueba
		document.getElementById("txt_idMuestra").value = "";
	}
	//Activar los campos requeridos para registrar una muestra de Obras de Zarpeo y Externas
	else if (combo.value!=""){		
		//Activar el ComboBox de Localización y la caja de texto de No. Muestra y borrar el valor previo del combo de Localización
		document.getElementById("txt_noMuestra").readOnly = false;
		document.getElementById("txt_noMuestra").value = "";
		document.getElementById("cmb_localizacion").disabled = false;
		document.getElementById("cmb_localizacion").value = "";
		
		//Borrar el contenido previo de la caja de texto de Código
		document.getElementById("txt_codigo").value = "";
		
		//Borrar la clave previa calculada, ya que cambio el valor del combo de Tipo Prueba
		document.getElementById("txt_idMuestra").value = "";
	}
	//Regresar los campos a su estado original cuando la opcion seleccionada sea vacia
	else if (combo.value==""){
		//Desactivar los campos de No. Muestra, Código y Localización y borrar el contenido previo
		document.getElementById("txt_noMuestra").readOnly = false;
		document.getElementById("txt_noMuestra").value = "";
		document.getElementById("txt_codigo").value = "";
		document.getElementById("cmb_localizacion").disabled = true;
		document.getElementById("cmb_localizacion").value = "";
		
		//Borrar la clave previa calculada, ya que cambio el valor del combo de Tipo Prueba
		document.getElementById("txt_idMuestra").value = "";
	}
}//Cierre de la función activarCampos(combo)


/*Esta funcion asigna el codigo de la mezcla como codigo de la muestra cuando el tipo de prueba es CONCRETO*/
function asignarCodigo(){
	//Verificar que la opcion seleccionado en el Tipo de Prueba sea CONCRETO
	if(document.getElementById("cmb_tipoPrueba").value=="CONCRETO"){
		//Asignar el ID de la Mezcla al codigo de la Muestra
		document.getElementById("txt_codigo").value = document.getElementById("cmb_idMezcla").value;
		//Calcular el ID para la muestra de CONCRETO que será probada
		calcularIdMuestra(2);
	}
}//Cierre de la funcion asignarCodigo()


//Funcion que permite agregar una nueva opcion, no existente a un combo box (Combo de Localización de la pagina de Registrar Muestras)
function agregarNvoLugar(comboBox){
	//Si la opcion seleccionada es agregar nueva unidad ejecutar el siguiete codigo
	if(comboBox.value=="NUEVA"){
		var nvoLugar = "";
		var condicion = false;
		do{
			nvoLugar = prompt("Introducir Nueva Localización","Nueva Localización...");
			if(nvoLugar=="Nueva Localización..." ||  nvoLugar=="")
				condicion = true;	
			else
				condicion = false;
		}while(condicion);
		
		//Si el usuario presiono calncelar no se relaiza ninguan actividad de lo contrario asignar la nueva opcion al combo
		if(nvoLugar!=null){
			//Convertir a mayusculas la opcion dada
			nvoLugar = nvoLugar.toUpperCase();
			//variable que nos ayudara a saber si la nueva opcion ya esta registrada en el combo
			var existe = 0;
			
			for(i=0; i<comboBox.length; i++){
				//verificar que la nueva opcion no se encuentre dentro de las opciones actuales del combo
				if(comboBox.options[i].value==nvoLugar)
					existe = 1;
			} //FIN for(i=0; i<comboBox.length; i++)
			
			//Si la nva opcion no esta registrada agregarla como una adicional y preseleccionarla
			if(existe==0){
				//Agregar al final la nueva opcion seleccionada
				comboBox.length++;
				comboBox.options[comboBox.length-1].text = nvoLugar;
				comboBox.options[comboBox.length-1].value = nvoLugar;
				//Preseleccionar la opcion agregada
				comboBox.options[comboBox.length-1].selected = true;
			} // FIN if(existe==0)
			
			else{
				alert("La Localización Ingresada ya esta Registrada \n en las Opciones de la Lista de Localización");
				comboBox.value = nvoLugar;
			}
		}// FIN if(nvaMedida!= null)
		
		else if(nvoLugar== null){
			comboBox.value = "";	
		}
	}// FIN if(comboBox.value=="NUEVA")
}


/*Esta funcion valida las opciones seleccionadas en la pagina de Editar Muestras*/
function valFormSeleccionarMuestra(frm_seleccionarMuestra){
	//Si el valor de la variable "validacion" permanece en 1, el proceso de validación fue satisfactorio
	var validacion = 1;
	
	//Validar que al menos sea seleccionado Código/Localización para consultar los datos de las muestras
	if(frm_seleccionarMuestra.hdn_botonClic.value=='consultar'){
		if(frm_seleccionarMuestra.cmb_codLocalizacion.value==""){
			alert("Seleccionar Código/Localización para Consultar");
			validacion = 0;
		}
	}
	//Validar que sea seleccionado Código/Localización y luego una muestra para poder editar los datos.
	else if(frm_seleccionarMuestra.hdn_botonClic.value=='modificar'){
		if(frm_seleccionarMuestra.cmb_codLocalizacion.value==""){
			alert("Seleccionar Código/Localización");
			validacion = 0;
		}
		if(frm_seleccionarMuestra.cmb_idMuestra.value=="" && validacion==1){
			alert("Seleccionar una Muestra para Modificar");
			validacion = 0;
		}
	}
	
	
	if(validacion==1)
		return true;
	else
		return false;
}//Cierre de la función valFormSeleccionarMuestra(frm_seleccionarMuestra)


/*Esta funcion valida que los datos del fomulario de modificar muestra, sean proporcionados*/
function valFormModificarMuestra(frm_modificarMuestra){
		//Si el valor de la variable "validacion" permanece en 1, el proceso de validación fue satisfactorio
	var validacion = 1;
	
	//Verificar que sea seleccionada una Mezcla
	if(frm_modificarMuestra.cmb_idMezcla.value==""){
		alert("Seleccionar Mezcla");
		validacion = 0;
	}
	
	//Verificar que sea seleccionado un tipo de prueba
	if(frm_modificarMuestra.cmb_tipoPrueba.value=="" && validacion==1){
		alert("Seleccionar Tipo de Prueba");
		validacion = 0;
	}
	
	//Si las opciones seleccionadas en el tipo de prueba son Obra de Zarpeo u Obra Externa, solicitar el No. de Muestra
	if((frm_modificarMuestra.cmb_tipoPrueba.value=="OBRA DE ZARPEO" || frm_modificarMuestra.cmb_tipoPrueba.value=="OBRA EXTERNA") && validacion==1){
		if(frm_modificarMuestra.txt_noMuestra.value==""){
			alert("Introducir el No. de Muestra");
			validacion = 0;
		}
	}	
	
	//Solicitar el dato de Revenimiento y validar que sea un numero mayor a 0
	if(frm_modificarMuestra.txt_revenimiento.value=="" && validacion==1){
		alert("Introducir la Cantidad de Revenimiento");
		validacion = 0;
	}
	if(validacion==1){
		if(!validarEntero(frm_modificarMuestra.txt_revenimiento.value.replace(/,/g,''),"El Revenimiento"))
			validacion = 0;
	}
	//Solicitar la Resitencia de la Muestra y verificar que sea un numero mayor a 0
	if(frm_modificarMuestra.txt_fProyecto.value=="" && validacion==1){
		alert("Introducir el Valor de F' c Proyecto");
		validacion = 0;
	}
	if(validacion==1){
		if(!validarEntero(frm_modificarMuestra.txt_fProyecto.value.replace(/,/g,''),"F' c Proyecto"))
			validacion = 0;
	}
	
	//Si las opciones seleccionadas en el tipo de prueba son Obra de Zarpeo u Obra Externa, solicitar la Localización
	if((frm_modificarMuestra.cmb_tipoPrueba.value=="OBRA DE ZARPEO" || frm_modificarMuestra.cmb_tipoPrueba.value=="OBRA EXTERNA") && validacion==1){
		if(frm_modificarMuestra.cmb_localizacion.value==""){
			alert("Seleccionar la Localización de Donde se Obtuvo la Muestra");
			validacion = 0;
		}
	}
	
	
	if(validacion==1)
		return true;
	else
		return false;
}//Cierre de la funcion valFormModificarMuestra(frm_modificarMuestra)


/***************************************************************************************************************************************************************/
/****************************************FORMULARIO CONSULTAR MEZCLA Y REGISTRAR PRUEBAS MUESTRAS***************************************************************/
/***************************************************************************************************************************************************************/
//funcion para validar el formulario frm_reporteFecha cuando se selecciona la opcion por fecha
function valFormConsultarMuestrasFecha(frm_consultarMuestrasFecha){
	//Variable que permite revisar si la validación fue exitosa
	var band=1;
	//Verificamos que el combo area este definido
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_consultarMuestrasFecha.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_consultarMuestrasFecha.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_consultarMuestrasFecha.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_consultarMuestrasFecha.txt_fechaFin.value.substr(0,2);
	var finMes=frm_consultarMuestrasFecha.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_consultarMuestrasFecha.txt_fechaFin.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	
	//Verificar que la fecha de inicio no sea mayor a la de cierre
	if(fechaIni>fechaFin){
		band=0;
		alert ("La Fecha de Inicio no puede ser Mayor a la Fecha de Fin");
	}
	if (band==1)
		return true;
	else
		return false;
	
}

/*Esta función valida que los campos necesarios para el formulario frm_consultarMezcla esten completados  por clave*/
function valFormConsultarMuestraClave(frm_consultarMuestraClave){
	//Variable bandera que permite revisar si la validacion fue exitosa
	var band=1;
		
	//Verificar que el usuario seleccione Código/Localización y despues una muestra 	
	if (frm_consultarMuestraClave.cmb_codLocalizacion.value==""){
		alert("Seleccionar Código/Localización");
		band=0;
	}
	if (frm_consultarMuestraClave.cmb_idMuestra.value=="" && band==1){
		alert("Seleccionar una Muestra");
		band=0;
	}
			
	if (band==1)
		return true;
	else
		return false;
}


/***************************************************************************************************************************************************************/
/*********************************************************FORMULARIO PRUEBAS MUESTRAS***************************************************************************/
/***************************************************************************************************************************************************************/
//Funcion que permite validar la Fecha seleccionada
function valFormProgPruebasMuestras(frm_progPruebasMuestras){
	//Variable que permite revisar si la validación fue exitosa
	var band = 1;
	//Verificamos que el combo area este definido
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var diaActual = frm_progPruebasMuestras.hdn_fecha.value.substr(0,2);
	var mesActual = frm_progPruebasMuestras.hdn_fecha.value.substr(3,2);
	var anioActual = frm_progPruebasMuestras.hdn_fecha.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var diaCampo = frm_progPruebasMuestras.txt_fechaProg.value.substr(0,2);
	var mesCampo = frm_progPruebasMuestras.txt_fechaProg.value.substr(3,2);
	var anioCampo = frm_progPruebasMuestras.txt_fechaProg.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaActual=mesActual+"/"+diaActual+"/"+anioActual;
	var fechaCampo=mesCampo+"/"+diaCampo+"/"+anioCampo;
	
	//Convertir la cadena a formato valido para JS
	fechaActual=new Date(fechaActual);
	fechaCampo=new Date(fechaCampo);

	//Verificar que la fecha de inicio no sea mayor a la de cierre
	if(fechaActual>fechaCampo){
		band=0;
		alert ("La Fecha Introducida ya Pasó.\nIntroducir Fecha Válida");
		frm_progPruebasMuestras.txt_fechaProg.value=frm_progPruebasMuestras.hdn_fecha.value;
	}
	
	if (band==1)
		return true;
	else
		return false;
}


/*Esta funcion se encarga de Validar el Formulario donde se selecciona la Muestra segun la consulta realizada (por fecha o por id)*/
function valFormConsultarDetMuestra(frm_consultarDetMuestra){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;

	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_consultarDetMuestra.rdb_idMuestra.length==undefined && !frm_consultarDetMuestra.rdb_idMuestra.checked){
		alert("Seleccionar una Muestra para Continuar");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_consultarDetMuestra.rdb_idMuestra.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_consultarDetMuestra.rdb_idMuestra.length;i++){
			if(frm_consultarDetMuestra.rdb_idMuestra[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar una Muestra para Continuar");			
	}
	
	if(res==1)
		return true;
	else
		return false;
}



/***************************************************************************************************************************************************************/
/**************************************************************RESULTADOS PRUEBAS*******************************************************************************/
/***************************************************************************************************************************************************************/
//Funcion que permite elegir a que pagina el tipo de registro a realizar (Pruebas a Agregados, Rendimientos y Resistencia a la Compresión
function valFormRegistrarPruebas(frm_registrarPruebas){
	
	var band = 1;
	
	if(frm_registrarPruebas.cmb_origen.value==""){
		alert("Seleccionar el Origen de la Prueba");
		band = 0;
	}
	
	if(frm_registrarPruebas.cmb_registro.value=="" && band==1 && frm_registrarPruebas.cmb_origen.value!="AGREGADOS"){
		alert("Seleccionar el Tipo de Registro");
		band = 0;
	}
	
	
	if(band==1){	
		if(frm_registrarPruebas.cmb_origen.value=="AGREGADOS"){
			document.frm_registrarPruebas.action="frm_registrarPruebasAgregados.php";			
		}
		else if(frm_registrarPruebas.cmb_origen.value=="MEZCLAS")
			document.frm_registrarPruebas.action="frm_registrarPruebasMezclas.php";
	}
	
	if(frm_registrarPruebas.cmb_registro.value=="RENDIMIENTO"){
		document.frm_registrarPruebas.action="frm_registrarResultadoRendimiento.php";			
	}

	if(frm_registrarPruebas.cmb_registro.value=="RESISTENCIAS"){
		document.frm_registrarPruebas.action="frm_registrarPruebasMuestras.php";			
	}
		
	if (band==1)
		return true;
	else
		return false;
}//Cierre de la funcion valFormRegistrarPruebas(frm_registrarPruebas)


//Funcion que sirve para para ocultar o mostrar LA CAJA DE TEXTO	
function activarCampo(campo){
	if(campo.options[campo.selectedIndex].text=="MEZCLAS"){
		//Si el valor es seleccionado como MEZCLAS mostramos el boton
		document.getElementById("cmb_registro").style.visibility="visible";
		document.getElementById("div_registro").style.visibility="visible";
	}
	//De lo contrario se oculta
	else if(campo.options[campo.selectedIndex].text=="AGREGADOS"){
		//Si el valor es seleccionado MEZCLAS mostramos el COMBO
		document.getElementById("cmb_registro").style.visibility="hidden";
		document.getElementById("div_registro").style.visibility="hidden";
	}
}//Cierre de la funcion activarCampo(campo)


/************************************************************RESULTADOS PRUEBAS AGREGADOS***********************************************************************/
/*Esta función valida que los campos necesarios para el formulario buscar agregado por fecha esten completados  por fecha*/
function valFormBuscarAgFecha(frm_registrarPruebasAgregados){
	//Variable que permite revisar si la validación fue exitosa
	var band=1;
	//Verificamos que el combo area este definido
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_registrarPruebasAgregados.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_registrarPruebasAgregados.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_registrarPruebasAgregados.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_registrarPruebasAgregados.txt_fechaFin.value.substr(0,2);
	var finMes=frm_registrarPruebasAgregados.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_registrarPruebasAgregados.txt_fechaFin.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	
	//Verificar que la fecha de inicio no sea mayor a la de cierre
	if(fechaIni>fechaFin){
		band=0;
		alert ("La Fecha de Inicio no puede ser Mayor a la Fecha de Fin");
	}
	if (band==1)
		return true;
	else
		return false;
	
}


/*Esta función valida que los campos necesarios para el formulario buscar agregados por id esten completados  por clave*/
function valFormBuscarAgNombre(frm_registrarPruebasAgregados2){
	//Variable bandera que permite revisar si la validacion fue exitosa
	var band=1;
		
	if (frm_registrarPruebasAgregados2.cmb_agregado.value==""){
		alert("Seleccionar un Agregado");
		band=0;
	}
			
	if (band==1)
		return true;
	else
		return false;
}//Cierre de la funcion valFormBuscarAgNombre(frm_registrarPruebasAgregados2)


/*Esta funcion se encarga de Validar el Formulario donde se selecciona el agragado*/
function valFormRegistrar(frm_registrarPruebasAgregados){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_registrarPruebasAgregados.rdb_nomMat.length==undefined && !frm_registrarPruebasAgregados.rdb_nomMat.checked){
		alert("Seleccionar una Agregado para Continuar");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_registrarPruebasAgregados.rdb_nomMat.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_registrarPruebasAgregados.rdb_nomMat.length;i++){
			if(frm_registrarPruebasAgregados.rdb_nomMat[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar un Agregado para Continuar");			
	}
	
	if(res==1)
		return true;
	else
		return false;
}//Cierre de la función valFormRegistrar(frm_registrarPruebasAgregados)


/*Esta función valida que los campos necesarios para el formulario buscar agregados por id esten completados  por clave*/
function valFormRegAgregados(frm_registrarAgregados){
	//Variable bandera que permite revisar si la validacion fue exitosa
	var band=1;
	
	if (frm_registrarAgregados.cmb_norma.value==""){
		alert("Seleccionar Norma");
		band=0;
	}
	
	if (frm_registrarAgregados.txt_origen.value==""&&band==1){
		alert("Ingresar el Origen del Agregado");
		band=0;
	}
	
	if (frm_registrarAgregados.txt_wmPvss.value=="" && band==1){
		alert("Ingresar el Wm del PVSS");
		band=0;
	}
	
	if(band==1){
		if(!validarEnteroConCero(frm_registrarAgregados.txt_wmPvss.value.replace(/,/g,''),"Wm del PVSS"))
		band=0;
	}
	
	if (frm_registrarAgregados.txt_wmPvsc.value=="" && band==1){
		alert("Ingresar el Wm del PVSC");
		band=0;
	}
	
	if(band==1){
		if(!validarEnteroConCero(frm_registrarAgregados.txt_wmPvsc.value.replace(/,/g,''),"Wm del PVSC"))
		band=0;
	}

	
	if (frm_registrarAgregados.txt_msssDensidad.value=="" && band==1){
		alert("Ingresar el Msss de la Densidad");
		band=0;
	}
	
	if(band==1){
		if(!validarEnteroConCero(frm_registrarAgregados.txt_msssDensidad.value.replace(/,/g,''),"el Msss de la Densidad"))
		band=0;
	}

	if (frm_registrarAgregados.txt_msssAbosrcion.value=="" && band==1){
		alert("Ingresar el Msss de Absorción");
		band=0;
	}
	
	if(band==1){
		if(!validarEnteroConCero(frm_registrarAgregados.txt_msssAbosrcion.value.replace(/,/g,''),"el Msss de Absorción"))
		band=0;
	}

	if (frm_registrarAgregados.txt_finura.value=="" && band==1){
		alert("Ingresar la Finura");
		band=0;
	}
	
	if(band==1 && frm_registrarAgregados.txt_finura.value!= 'N/A'){
		if(!validarEnteroConCero(frm_registrarAgregados.txt_finura.value.replace(/,/g,'')," la Finura"))
		band=0;
	}

	if (frm_registrarAgregados.txt_granulometria.value=="" && band==1){
		alert("Ingresar la Granulometría");
		band=0;
	}

	if (frm_registrarAgregados.txt_vmPvss.value=="" && band==1){
		alert("Ingresar el Vm del PVSS");
		band=0;
	}
	
	if(band==1){
		if(!validarEnteroConCero(frm_registrarAgregados.txt_vmPvss.value.replace(/,/g,''),"el Vm del PVSS"))
		band=0;
	}

	if (frm_registrarAgregados.txt_vmPvsc.value=="" && band==1){
		alert("Ingresar el Vm del PVSC");
		band=0;
	}
	
	if(band==1){
		if(!validarEnteroConCero(frm_registrarAgregados.txt_vmPvsc.value.replace(/,/g,''),"el Vm del PVSC"))
		band=0;
	}

	if (frm_registrarAgregados.txt_va.value=="" && band==1){
		alert("Ingresar el Va de la Densidad");
		band=0;
	}

	if(band==1){
		if(!validarEnteroConCero(frm_registrarAgregados.txt_va.value.replace(/,/g,''),"el Va de la Densidad"))
		band=0;
	}

	if (frm_registrarAgregados.txt_ws.value=="" && band==1){
		alert("Ingresar el Ws de la Absorción");
		band=0;
	}

	if(band==1){
		if(!validarEnteroConCero(frm_registrarAgregados.txt_ws.value.replace(/,/g,''),"el Ws de la Absorción"))
		band=0;
	}
	
	if (frm_registrarAgregados.cmb_pruebaEjecutada.value=="" && band==1){
		alert("Seleccionar la Prueba Ejecutada");
		band=0;
	}	

	if (band==1)
		return true;
	else
		return false;
}//Cierre de la funcion valFormRegAgregados(frm_registrarAgregados)


//Funcion que permite realizar los distintos calculos de los agregados
function calculosAgregados(dato1,dato2,operacion,txtGuardar){
	if(dato1 != "" && dato2 != ""){
		
		var dato1 = dato1.replace(/,/g,'');
		var dato2 = dato2.replace(/,/g,'');

		switch(operacion){
			case 1: 
				var  pvss = (dato1/dato2)*1000;	
				formatCurrency(pvss,txtGuardar.id);	
			break;
			case 2:
				var  pvsc = (dato1/dato2)*1000;	
				formatCurrency(pvsc,txtGuardar.id);	
			break;
			case 3:
				var  densidad = (dato1/dato2);	
				formatCurrency(densidad,txtGuardar.id);	
			break;
			case 4:
				var  absorcion = ((dato1-dato2)/ dato2)*100;	
				formatCurrency(absorcion,txtGuardar.id);	
			break;
			case 5:
				var  pl = ((dato1-dato2)/ dato2)*100;	
				formatCurrency(pl,txtGuardar.id);	
			break;
		}
	}
}


/*Esta función valida que los campos necesarios para el formulario buscar agregados por id esten completados  por clave*/
function valFormRegAgregados2(frm_registrarAgregados2){
	//Variable bandera que permite revisar si la validacion fue exitosa
	var band=1;
	
	if (frm_registrarAgregados2.cmb_concepto.value==""){
		alert("Ingresar el Concepto");
		band=0;
	}
	
	
	if (frm_registrarAgregados2.txt_retenido.value=="" && band==1){
		alert("Ingresar el Retenido");
		band=0;
	}
	
	if(band==1){
		if(!validarEnteroConCero(frm_registrarAgregados2.txt_retenido.value.replace(/,/g,''),"el Retenido"))
		band=0;
	}

	if (band==1)
		return true;
	else
		return false;
}


/*************************************************************REGISTRAR RENDIMIENTOS DE MEZCLAS*****************************************************************/
/*Esta función valida que los campos necesarios para el formulario buscar mezcla por fecha esten completos para buscar las muestras por fecha*/
//funcion para validar el formulario frm_reporteFecha cuando se selecciona la opcion por fecha
function valFormBuscarMezclaFecha(frm_buscarMezclaFecha){
	//Variable que permite revisar si la validación fue exitosa
	var band=1;
	//Verificamos que el combo area este definido
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_buscarMezclaFecha.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_buscarMezclaFecha.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_buscarMezclaFecha.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_buscarMezclaFecha.txt_fechaFin.value.substr(0,2);
	var finMes=frm_buscarMezclaFecha.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_buscarMezclaFecha.txt_fechaFin.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	
	//Verificar que la fecha de inicio no sea mayor a la de cierre
	if(fechaIni>fechaFin){
		band=0;
		alert ("La Fecha de Inicio no puede ser Mayor a la Fecha de Fin");
	}
	if (band==1)
		return true;
	else
		return false;
	
}

/*Esta función valida que los campos necesarios para el formulario buscar mezcla por id esten completados  por clave*/
function valFormBuscarMezclaClave(frm_buscarMezclaClave){
	//Variable bandera que permite revisar si la validacion fue exitosa
	var band=1;
	
	//Verificar que el usuario seleccione Código/Localización y despues una muestra 	
	if (frm_buscarMezclaClave.cmb_claveMezcla.value==""){
		alert("Seleccionar Mezcla");
		band=0;
	}
			
			
	if (band==1)
		return true;
	else
		return false;
}//Cierre de la funcion valFormBuscarMezclaClave(frm_buscarMezclaClave)


/*Esta funcion se ecnarga de Validar el Formulario donde se selecciona la mezcla a la cual se le va a registrar el Rendimiento*/
function valFormSeleccionarMezcla(frm_seleccionarMezcla){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_seleccionarMezcla.rdb_idMezcla.length==undefined && !frm_seleccionarMezcla.rdb_idMezcla.checked){
		alert("Seleccionar una Mezcla para Continuar");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_seleccionarMezcla.rdb_idMezcla.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_seleccionarMezcla.rdb_idMezcla.length;i++){
			if(frm_seleccionarMezcla.rdb_idMezcla[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar una Mezcla para Continuar");			
	}
	
	if(res==1)
		return true;
	else
		return false;
}//Cierre de la funcion valFormSeleccionarMezcla(frm_seleccionarMezcla)


//Funcion para validar la Hora en el registro del rendimiento
function validarHoras(campo){
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
			document.getElementById("txt_hora").value = "";
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
					document.getElementById("txt_hora").value = "00:00";
				else
					document.getElementById("txt_hora").value = horas.toString()+":00";
				
			}
			else{
				//Verificamos si horas estan en 0 
				if(horas==0){
					//Si horas =0 y minutos <9; esto para poner un cero mas y que conserve el formato 00:09 y no 00:9
					if(parseInt(minutos)<=9)
						document.getElementById("txt_hora").value = "00:0"+minutos.toString();
					//Si horas =0 y minutos >9 entonces los minutos tomaran el formato 00:10		
					else
						document.getElementById("txt_hora").value = "00:"+minutos.toString();
				//Si las horas y minutos mayores a 0 entonces toma formato 10:25
				}
				else
					document.getElementById("txt_hora").value = horas.toString()+":"+minutos.toString();			
			}
		}			
	}
	else{
		//Enviaos mensaje
		alert("La Cantidad de Dígitos Excede el Tamaño Permitido");
		//Ponemos la caja de Texto como vacia
		document.getElementById("txt_hora").value = "";
	}						
}


//Funcion que permite validar el formulario de Registro de Rendimiento 2
function valFormRegistrarRendimiento(frm_registrarResultadoRendimiento){
	//Variable que permite conocer si existen campos vacios; si esta se conserva en uno todos los campos han sido llenados
	var band = 1;
	
	//Verificar que sea proporcionada la Lozalizacion
	if(frm_registrarResultadoRendimiento.txt_localizacion.value==""){
		alert("Ingresar la Localización");
		band = 0;
	}
	
	//Verificar que el no. de muestra haya sido ingresada
	if(frm_registrarResultadoRendimiento.txt_numMuestra.value=="" && band==1){
		alert("Ingresar el Numero de Muestra");
		band = 0;
	}
	
	
	//Verificar que la temperatura haya sido ingresada
	if(frm_registrarResultadoRendimiento.txt_temperatura.value=="" && band==1){
		alert("Ingresar la Temperatura");
		band = 0;
	}
	
	//Verificar que la temperatura sea un numero valido
	if(band==1){
		if(!validarEnteroConCero(frm_registrarResultadoRendimiento.txt_temperatura.value.replace(/,/g,''),"La Temperatura")){			
			frm_registrarResultadoRendimiento.txt_temperatura.value = "";
			band = 0;
		}
	}
	
	//Verificar que el Revenimiento haya sido ingresado
	if(frm_registrarResultadoRendimiento.txt_revenimiento.value=="" && band==1){
		alert("Ingresar el Revenimiento");
		band = 0;
	}
	
	//Verificar que el revenimiento sea un numero valido
	if(band==1){
		if(!validarEntero(frm_registrarResultadoRendimiento.txt_revenimiento.value.replace(/,/g,''),"El Revenimiento")){			
			frm_registrarResultadoRendimiento.txt_revenimiento.value = "";
			band = 0;
		}
	}
	
	//Verificar que la hora haya sido ingresada
	if(frm_registrarResultadoRendimiento.txt_hora.value==""&&band==1){
		alert("Ingresar la Hora");
		band = 0;
	}
	
	
	//Regresar el resultado final
	if (band==1)
		return true;
	else
		return false;
}//Cierre de la funcion valFormRegistrarRendimiento(frm_registrarResultadoRendimiento)


//Funcion que permite validar el formulario de Registro de Rendimiento 2
function valFormRegistrarRendimiento2(frm_registrarResultadoRendimiento2){
	//Variable que permite conocer si existen campos vacios; si esta se conserva en uno todos los campos han sido llenados
	var band = 1;
	
	//Verificar que el volumen bruto haya sido ingresado
	if(frm_registrarResultadoRendimiento2.txt_pvolBruto.value==""){
		alert("Ingresar Peso Bruto");
		band = 0;
	}
	
	//Verificar que el peso bruto haya sido ingresado y sea diferente de cero
	if(frm_registrarResultadoRendimiento2.txt_pvolBruto.value!=""&&frm_registrarResultadoRendimiento2.txt_pvolBruto.value==0){
		alert("El Peso Bruto no puede ser igual a (0) cero");
		frm_registrarResultadoRendimiento2.txt_pvolBruto.value="";
		band = 0;
	}
	
	//Verificar que el volumen del Molde haya sido ingresado
	if(frm_registrarResultadoRendimiento2.txt_pvolMolde.value==""&&band==1){
		alert("Ingresar Peso Molde");
		band = 0;
	}
	
	//Verificar que el peso Molde haya sido ingresado y sea diferente de cero
	if(frm_registrarResultadoRendimiento2.txt_pvolMolde.value!=""&&frm_registrarResultadoRendimiento2.txt_pvolMolde.value==0){
		alert("El Peso Molde no puede ser igual a (0) cero");
		frm_registrarResultadoRendimiento2.txt_pvolMolde.value="";
		band = 0;
	}
		
	
	//Verificar que el factor recipiente haya sido ingresado
	if(frm_registrarResultadoRendimiento2.txt_factorRec.value==""&&band==1){
		alert("Ingresar Factor Recipiente");
		band = 0;
	}
	
	//Verificar que el factor recipiente haya sido ingresado y sea diferente de cero
	if(frm_registrarResultadoRendimiento2.txt_factorRec.value!=""&&frm_registrarResultadoRendimiento2.txt_factorRec.value==0){
		alert("El Factor Recipiente no puede ser igual a (0) cero");
		frm_registrarResultadoRendimiento2.txt_factorRec.value="";
		band = 0;
	}	
				
	//Verificar que el valor del Contenido Real de Aire sea proporcionado
	if(frm_registrarResultadoRendimiento2.txt_caireReal.value==""&&band==1){
		alert("Ingresar el Valor del Contenido Real de Aire");
		band = 0;
	}
	
	//Verificar que el peso teórico del aire haya sido ingresado y sea diferente de cero
	if(frm_registrarResultadoRendimiento2.txt_caireReal.value!="" && frm_registrarResultadoRendimiento2.txt_caireReal.value==0){
		alert("El Valor del Contenido Real de Aire NO Puede Ser Igual a Cero (0)");
		frm_registrarResultadoRendimiento2.txt_caireReal.value="";
		band = 0;
	}
	
	
	//Verificar si las pruebas aplicadas (Normas) fueron agregadas para proceder a guardar los datos
	if (frm_registrarResultadoRendimiento2.hdn_pruebasCargadas.value=="no"&&band==1){
		alert("Seleccionar las Pruebas Aplicadas");
		band=0;
	}
	
	
	
	if (band==1)
		return true;
	else
		return false;
}


//Funcion que permite calcular el peso Unitario
function calcularPesoUnitario(){
	var band=1;
	
	//Obtener los datos requeridos para el calculo
	var pesoBruto = document.getElementById("txt_pvolBruto").value;
	parseFloat(pesoBruto.replace(/,/g,''));
	var pesoMolde = document.getElementById("txt_pvolMolde").value;
	parseFloat(pesoMolde.replace(/,/g,''));
	var factorRecipiente = document.getElementById("txt_factorRec").value;
	parseFloat(factorRecipiente.replace(/,/g,''));
	
	
	//PESO BRUTO
	//Verificar que el volumen bruto haya sido ingresado
	if(pesoBruto==""){
		band = 0;
	}	
	//Verificar que el peso bruto haya sido ingresado y sea diferente de cero
	if(pesoBruto!=""&&pesoBruto==0){
		pesoBruto="";
		band = 0;
	}
	
	
	//PESO MOLDE
	//Verificar que el volumen del Molde haya sido ingresado
	if(pesoMolde==""&&band==1){
		band = 0;
	}	
	//Verificar que el peso Molde haya sido ingresado y sea diferente de cero
	if(pesoMolde!=""&&pesoMolde==0){
		pesoMolde="";
		band = 0;
	}
	
	
	//FACTOR RECIPIENTE
	//Verificar que el factor recipiente haya sido ingresado
	if(factorRecipiente==""&&band==1){
		band = 0;
	}	
	//Verificar que el factor recipiente haya sido ingresado y sea diferente de cero
	if(factorRecipiente!=""&&factorRecipiente==0){
		factorRecipiente="";
		band = 0;
	}
	
	
	//Si los datos fueron proporcionados de forma adecuada proceder a realziar los calculos
	if (band==1){
		//Realizar la Operacion para Obtener el PESO UNITARIO
		var result = (pesoBruto-pesoMolde) * factorRecipiente;				
		
		
		//Asignar el resultado al PESO UNITARIO		
		formatNumDecimalLab(result,"txt_pvolUnitario");
		//Asignar el resultado al PESO VOLUMEN de la Sección de RENDIMIENTO
		formatNumDecimalLab(result,"txt_volRend");
		//Asignar el resultado al PESO VOLUMEN de la Sección de CONTENIDO DE AIRE(%)
		formatNumDecimalLab(result,"txt_pvolAire");
		
		
		//Obtener el valor de R en el Contenido Real de Cemento
		var rendVolTeorico = parseFloat(document.getElementById("txt_pvolTeoricoRend").value.replace(/,/g,''));
		var rendVol = parseFloat(document.getElementById("txt_volRend").value.replace(/,/g,''));		
		var result2 = rendVolTeorico/rendVol;

		//Asignar el resultado a la caja de texto que lo mostrará
		formatNumDecimalLab(result2,"txt_r");
	}
	else{//Si no se pueden hacer los calculos, vaciar los datos anteriores
		document.getElementById("txt_pvolUnitario").value = "";
		document.getElementById("txt_volRend").value = "";
		document.getElementById("txt_pvolAire").value = "";
		document.getElementById("txt_r").value = "";
	}
	
}//Cierre de la funcion calcularPesoUnitario()


/*Esta funcion valida que las cantidades de los materiales de la mezcla seleccionada no vayan vacios o con cero(0) */
function valFormMostrarMaterialesMezcla(frm_mostrarMaterialesMezcla){
	//Si el valor de validacion permanece en 1, el proceso de validación fue exitoso
	var validacion = 1;
	//Obtener la cantidad de registros a ser validados
	var noRegs = parseInt(document.getElementById("hdn_tam").value);
	
	var datosCompletos = false;
	var nomMat = "";
	//Recorrer cada registro para verificar que no esten vacios o su valor sea 0	
	for(i=1;i<=noRegs;i++){
		//Obtener el nombre del material que esta siendo validado
		nomMat = frm_mostrarMaterialesMezcla["hdn_nomMaterial"+i].value;
		//No se valida que el campo este vacio, ya que la funcion formatNumDecimalLab(valor, elemento) devuelve cero(0) cuando se introducen datos erroneos o vacios
		if(frm_mostrarMaterialesMezcla["txt_matCant"+i].value==0){//Verificar que el valor del material sea mayor a 0
			alert("La Cantidad para el Material =>"+nomMat+" \nNO Puede Ser Igual a Cero(0)");
			validacion = 0;
			break;//Romper el ciclo para no seguir buscando cuando se haya encontrado Cero (0) como valor del campo
		}
	}
	
	
	if(validacion==1)
		return true;
	else
		return false;
	
}//Cierre de la función valFormMostrarMaterialesMezcla(frm_mostrarMaterialesMezcla)


/*Esta funcion suma las cantidades de los materiales cuando es modificado el Diseño*/
function sumarCantMateriales(){
	//Obtener la cantidad de registros a ser sumados
	var noRegs = parseInt(document.getElementById("hdn_tam").value);
	var volTotal = 0.00;
	
	//Sumar el valor de cada registro
	for(i=1;i<=noRegs;i++){		
		volTotal += parseFloat(frm_mostrarMaterialesMezcla["txt_matCant"+i].value.replace(/,/g,''));		
	}			
	
	//Asignar el valor obtenido a la caja de texto que muestra el Total
	formatNumDecimalLab(volTotal,'txt_volTotal');
}


/*******************************************************REGISTRAR RESULTADOS PRUEBAS DE RESISTENCIA*************************************************************/
/*Esta función valida que los campos necesarios para el formulario buscar mezcla por fecha esten completados  por fecha*/
function valFormBuscarMuestrasFecha(frm_registrarPruebasMuestras2){
	//Variable que permite revisar si la validación fue exitosa
	var band=1;
	//Verificamos que el combo area este definido
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_registrarPruebasMuestras2.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_registrarPruebasMuestras2.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_registrarPruebasMuestras2.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_registrarPruebasMuestras2.txt_fechaFin.value.substr(0,2);
	var finMes=frm_registrarPruebasMuestras2.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_registrarPruebasMuestras2.txt_fechaFin.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	
	//Verificar que la fecha de inicio no sea mayor a la de cierre
	if(fechaIni>fechaFin){
		band=0;
		alert ("La Fecha de Inicio no puede ser Mayor a la Fecha de Fin");
	}
	if (band==1)
		return true;
	else
		return false;
	
}



/*Esta función valida que los campos necesarios para el formulario buscar mezcla por id esten completados  por clave*/
function valFormBuscarMuestrasClave(frm_registrarPruebasMuestras){
	//Variable bandera que permite revisar si la validacion fue exitosa
	var band=1;
	
	//Verificar que el usuario seleccione Código/Localización y despues una muestra 	
	if (frm_registrarPruebasMuestras.cmb_codLocalizacion.value==""){
		alert("Seleccionar Código/Localización");
		band=0;
	}
	if (frm_registrarPruebasMuestras.cmb_idMuestra.value=="" && band==1){
		alert("Seleccionar una Muestra");
		band=0;
	}
			
			
	if (band==1)
		return true;
	else
		return false;
}


/*Esta funcion se ecnarga de Validar el Formulario donde se selecciona la mezcla*/
function valFormBuscarDetMuestra(frm_registrarPruebasMuestras){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_registrarPruebasMuestras.rdb_idMuestra.length==undefined && !frm_registrarPruebasMuestras.rdb_idMuestra.checked){
		alert("Seleccionar una Mezcla para Continuar");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_registrarPruebasMuestras.rdb_idMuestra.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_registrarPruebasMuestras.rdb_idMuestra.length;i++){
			if(frm_registrarPruebasMuestras.rdb_idMuestra[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar una Muestra para Continuar");			
	}
	
	if(res==1)
		return true;
	else
		return false;
}


/*Esta funcion se ecnarga de Validar el Formulario donde se selecciona la mezcla para registrar resultado de las pruebas*/
function valFormAgregarPruebaLab(frm_registrarPruebasMezclas2){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var band = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if (frm_registrarPruebasMezclas2.txt_fc.value==""){
		alert("Ingresar la F c");
		band=0;
	}
		
	if (frm_registrarPruebasMezclas2.txt_cargaRuptura.value==""&&band==1){
		alert("Ingresar la Carga de Ruptura");
		band=0;
	}

	if (frm_registrarPruebasMezclas2.txt_porcentaje.value==""&&band==1){
		alert("Ingresar el Porcentaje");
		band=0;
	}

	if (frm_registrarPruebasMezclas2.txt_edad.value==""&&band==1){
		alert("Ingresar la Edad");
		band=0;
	}

	if (frm_registrarPruebasMezclas2.txt_kgcm.value==""&&band==1){
		alert("Ingresar Los Kg por cm2;");
		band=0;
	}
	
	if (frm_registrarPruebasMezclas2.txt_diametro.value==""&&band==1){
		alert("Ingresar el Diámetro");
		band=0;
	}
	
	
	//Verificar si las pruebas aplicadas (Normas) fueron agregadas para proceder a guardar los datos
	if (frm_registrarPruebasMezclas2.hdn_pruebasCargadas.value=="no"&&band==1){
		alert("Seleccionar las Pruebas Aplicadas");
		band=0;
	}

	
	if(band==1)
		return true;
	else
		return false;
}


//Funcion que permite realizar el calculo del area
function calcularArea(diametro){
		
	if(diametro != ""){
		
		var diametro = diametro.replace(/,/g,'');
		//Elevar el diámetro al cuadrado se hizo de esta forma ya que el pow no funciono
		diametro= diametro*diametro;
		
		var pi = 3.1416;	
		var  area = (pi*diametro)/4;	
		formatCurrency(area,"txt_area");
		
		//Una vez obtenido el área proceder a calcular kg/cm2
		if(document.getElementById("txt_cargaRuptura").value!= "")
			calcularKgCm(document.getElementById("txt_cargaRuptura").value,area.toString());
			
		//Una vez obtenido el área proceder a calcular kg/calcularPorcentaje
		if(document.getElementById("txt_cargaRuptura").value!= "" && document.getElementById("txt_fc").value!="")
			calcularPorcentaje(document.getElementById("txt_cargaRuptura").value,area.toString(),document.getElementById("txt_fc").value);
		
	}
}


//Funcion que permite realizar el calculo del kg/cm2
function calcularKgCm(ruptura,area){

	if(ruptura != "" && area != "" && area>0){
		
		var ruptura = ruptura.replace(/,/g,'');
		var area = area.replace(/,/g,'');

		var kgCm = (ruptura/area);
		
		document.getElementById("txt_kgcm").value = Math.round(kgCm);
	}
}


//Funcion que permite realizar el calculo del porcentaje
function calcularPorcentaje(ruptura,area,fc){

	if(ruptura !="" && area!="" && fc !="" && area>0){
		
		var ruptura = ruptura.replace(/,/g,'');
		var area = area.replace(/,/g,'');
		var fc = fc.replace(/,/g,'');

		var porcentaje = ((ruptura/area)/fc)*100;	
		formatCurrency(porcentaje,"txt_porcentaje");
	}
}


/*Esta funcion permitira evaluar si un documento o archivo cargado tiene el formato válido*/
function validarExtensionFoto(campo){
	//Obtener la longitud de la ruta del archivo	
	var tam = campo.value.length;
	//Obtener la cadena de la ruta del archivo
	var cadena = campo.value;
	//Obtener el ID del campo
	var id=campo.id;
	//Variable para recuperar el nombre
	var nombre="";
	//Variable para controlar la Etapa de Fotografia cargada
	var etapa="";
	if (id=="txt_fotografiaP"){
		nombre="Presentada";
		etapa="P";
	}
	if (id=="txt_fotografiaE"){
		nombre="Ensayada";
		etapa="E";
	}
	if (id=="txt_fotografiaO"){
		nombre="Obtenida";
		etapa="O";
	}
	//Si el usuario decide quitar el documento, entonces la longitud de la ruta del archivo es 0 colocamos el valor "si" en la variable hdn_tipoValido para que proceda
	//la operacion correspondiente
	if(tam!=0){
		//Obtener la extension del archivo
		var extension = cadena.charAt(tam-3)+cadena.charAt(tam-2)+cadena.charAt(tam-1);
		//var extension = campo.value.substring(tam-3,tam);
		//Pasar la extension de la imagen a minusculas para evaluarla como tal
		extension = extension.toLowerCase();
		//Comparar la extension contra las extensiones de los archivos permitidos
		if(!(extension=="jpg" || extension=="jpeg")){
			alert("Formato de Foto "+nombre+" no Soportado, Formatos Validos: 'jpg' y 'jpeg'");
			document.getElementById("hdn_tipoValido"+etapa).value = "no";
		}
		else
			document.getElementById("hdn_tipoValido"+etapa).value = "si";
	}
	else{
		document.getElementById("hdn_tipoValido"+etapa).value = "si";
	}
}


function envioDatosGet(){
	var edad = document.getElementById("txt_edad").value; 
	window.open('verPruebasFotos.php?edad='+edad, 
				'_blank','top=100, left=100, width=700, height=300, status=no, menubar=no, resizable=no, scrollbars=no, toolbar=no, location=no, directories=no');
}


function activarFotos(campo){
	if (campo.value!=""){
		document.getElementById("btn_cargaFotos").disabled=false;
		document.getElementById("btn_cargaFotos").title="Cargar Fotos en la Edad Indicada";
	}
	else{
		document.getElementById("btn_cargaFotos").disabled=true;
		document.getElementById("btn_cargaFotos").title="Para cargar Fotos indique la Edad";
	}
}


/*Esta función valida que se seleccione una prueba en el formulario Resultados encontrados*/
function valSeleccionarPruebas(frm_relacionarPrueba){
	var band=1;
	var registros=0;
	var cant=frm_relacionarPrueba.hdn_tam.value-1;

	for (i=1;i<=cant;i++){
		if (!document.getElementById("ckb_id"+i).checked)
			registros++;
	}

	//Si registros es igual a cant, significa que no se ha seleccionado ningun trabajador
	if (registros==cant && band!=0){
		alert("Seleccionar una Prueba por lo Menos");
		band=0;
	}
	
	//Si el valor de hdn_band es "si", pedir al usuario si desea sustituir el Arreglo de Sesion
	if (frm_relacionarPrueba.hdn_band.value=="si"){
		if (!confirm("Ya tiene Pruebas Agregadas, ¿Desea sustituir las Pruebas Seleccionadas y Agregar las Nuevas?")){
			frm_relacionarPrueba.hdn_band.value=="no";
			band==0
		}
	}

	if (band==1)
		return true;
	else
		return false;
}


function valSeleccionarFotoPruebas(frm_cargarFotoPrueba){
	var band=1;
	
	if (frm_cargarFotoPrueba.txt_fotografiaP.value==""){
		alert("Cargar Fotografía del Estado Presentada");
		band=0;
	}
	
	if(frm_cargarFotoPrueba.hdn_tipoValidoP.value=="no"&&band==1){
		alert("Formato de Foto Presentada no Soportado, Formatos Validos: 'jpg' y 'jpeg'");
		band=0;
	}

	if (frm_cargarFotoPrueba.txt_fotografiaE.value==""&&band==1){
		alert("Cargar Fotografía del Estado Ensayada");
		band=0;
	}
	
	if(frm_cargarFotoPrueba.hdn_tipoValidoE.value=="no"&&band==1){
		alert("Formato de Foto Ensayada no Soportado, Formatos Validos: 'jpg' y 'jpeg'");
		band=0;
	}

	if (frm_cargarFotoPrueba.txt_fotografiaO.value==""&&band==1){
		alert("Cargar Fotografía del Estado Obtenida");
		band=0;
	}
	
	if(frm_cargarFotoPrueba.hdn_tipoValidoO.value=="no"&&band==1){
		alert("Formato de Foto Obtenida no Soportado, Formatos Validos: 'jpg' y 'jpeg'");
		band=0;
	}
	
	if(frm_cargarFotoPrueba.hdn_fotoAdd.value=="si"&&band==1){
		if(!confirm("Ya hay Fotos Cargadas. ¿Desea Eliminarlas?")){
			band=0;
			window.close();
		}
	}
	
	if (band==1)
		return true;
	else
		return false;
}


/*Esta funcion verifica que sea seleccionada un fecha de realizacion de Prueba por Adelantado*/
function valSeleccionarAdelantoPrueba(frm_seleccionarAdelantoPrueba){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_seleccionarAdelantoPrueba.rdb_prueba.length==undefined && !frm_seleccionarAdelantoPrueba.rdb_prueba.checked){
		alert("Seleccionar la Prueba a Adelantar");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_seleccionarAdelantoPrueba.rdb_prueba.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_seleccionarAdelantoPrueba.rdb_prueba.length;i++){
			if(frm_seleccionarAdelantoPrueba.rdb_prueba[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar la Prueba a Adelantar");			
	}
	
	if(res==1)
		return true;
	else
		return false;
}



/***************************************************************************************************************************************************************/
/**********************************************************************EQUIPOS**********************************************************************************/
/***************************************************************************************************************************************************************/


/********************************************************************AGREGAR EQUIPOS****************************************************************************/
/*Esta función valida que los campos necesarios para el formulario frm_agregarEquipo esten completados*/
function valFormAgregarEquipo(frm_agregarEquipo){
	//Variable bandera que permite revisar si la validacion fue exitosa
	var band=1;
	
	//Verificamos el el numero del Equipo haya sido ingresado
	if (frm_agregarEquipo.txt_noInterno.value==""){
		alert("Introducir el Número Interno del Equipo");
		band=0;
	}
		
	//Verificamos que el nombre del equipo haya sido ingresado
	if (frm_agregarEquipo.txt_nomEquipo.value==""&&band==1){
		alert("Introducir el Nombre del Equipo");
		band=0;
	}
	
	//Verificamos que la aplicación del Equipo haya sido ingresada
	if (frm_agregarEquipo.txa_aplicacion.value==""&&band==1){
		alert("Introducir la Aplicación del Equipo");
		band=0;
	}
	
	//Verificamos que el responsable del equipo haya sido ingresado
	if (frm_agregarEquipo.txt_responsable.value==""&&band==1){
		alert("Introducir el Responsable del Equipo");
		band=0;
	}
	
	//Verificamos que el combo calibrable tenga opcion seleccionada
	if (frm_agregarEquipo.cmb_calibrable.value==""&&band==1){
		alert("Seleccionar Si el Equipo es Calibrable");
		band=0;
	}
	
	if(frm_agregarEquipo.hdn_claveValida.value=='no'&& band==1){
		alert("Número Interno de Equipo Duplicado");
		band=0;
	}
	
	if (band==1)
		return true;
	else
		return false;
}


/********************************************************************ELIMINAR EQUIPOS****************************************************************************/
//Formulario que valida que una marca del combo haya sido seleccionada
function valFormeliminarMarca(frm_eliminarEquipoMarca){
	//Variable bandera que permite revisar si la validacion fue exitosa
	var band=1;	
	//Verificamos que el combo marca tenga opcion seleccionada
	if (frm_eliminarEquipoMarca.cmb_marca.value==""&&band==1){
		alert("Seleccionar Marca");
		band=0;
	}
	
	if (band==1)
		return true;
	else
		return false;
}


//Formulario que valida que una marca del combo haya sido seleccionada
function valFormeliminarClave(frm_eliminarEquipoClave){
	//Variable bandera que permite revisar si la validacion fue exitosa
	var band=1;	
	//Verificamos que el  numero interno haya sido ingresado
	if (frm_eliminarEquipoClave.txt_noInterno.value==""&&band==1){
		alert("Ingresar Número Interno");
		band=0;
	}
	
	if (band==1)
		return true;
	else
		return false;
}



/*Esta funcion se ecnarga de Validar el Formulario donde se selecciona el equipo para borrar*/
function valFormEliminar(frm_eliminarEquipo){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_eliminarEquipo.rdb_equipo.length==undefined && !frm_eliminarEquipo.rdb_equipo.checked){
		alert("Seleccionar el Equipo a Borrar");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_eliminarEquipo.rdb_equipo.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_eliminarEquipo.rdb_equipo.length;i++){
			if(frm_eliminarEquipo.rdb_equipo[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar el Equipo a Borrar");			
	}
	
	if (res==1){
		
		if (!confirm("¿Estas Seguro que Quieres Borrar el Equipo?\nToda la información relacionada se Borrará")){
			res=0;
		}
	}
	
	if(res==1)
		return true;
	else
		return false;
}


/********************************************************************CONSULTAR EQUIPOS****************************************************************************/
//Formulario que valida que una marca del combo haya sido seleccionada
function valFormconsultarMarca(frm_consultarEquipoMarca){
	//Variable bandera que permite revisar si la validacion fue exitosa
	var band=1;	
	//Verificamos que el combo marca tenga opcion seleccionada
	if (frm_consultarEquipoMarca.cmb_marca.value==""&&band==1){
		alert("Seleccionar Marca");
		band=0;
	}
	
	if (band==1)
		return true;
	else
		return false;
}


//Formulario que valida que una marca del combo haya sido seleccionada
function valFormconsultarClave(frm_consultarEquipoClave){
	//Variable bandera que permite revisar si la validacion fue exitosa
	var band=1;	
	//Verificamos que el  numero interno haya sido ingresado
	if (frm_consultarEquipoClave.txt_noInterno.value==""&&band==1){
		alert("Ingresar Número Interno");
		band=0;
	}
	
	if (band==1)
		return true;
	else
		return false;
}

/********************************************************************MODIFICAR EQUIPOS****************************************************************************/
//Formulario que valida que una marca del combo haya sido seleccionada
function valFormModificarMarca(frm_modificarEquipoMarca){
	//Variable bandera que permite revisar si la validacion fue exitosa
	var band=1;	
	//Verificamos que el combo marca tenga opcion seleccionada
	if (frm_modificarEquipoMarca.cmb_marca.value==""&&band==1){
		alert("Seleccionar Marca");
		band=0;
	}
	
	if (band==1)
		return true;
	else
		return false;
}


//Formulario que valida que una marca del combo haya sido seleccionada
function valFormModificarClave(frm_modificarEquipoClave){
	//Variable bandera que permite revisar si la validacion fue exitosa
	var band=1;	
	//Verificamos que el  numero interno haya sido ingresado
	if (frm_modificarEquipoClave.txt_noInterno.value==""&&band==1){
		alert("Ingresar Número Interno");
		band=0;
	}
	
	if (band==1)
		return true;
	else
		return false;
}


/*Esta funcion se ecnarga de Validar el Formulario donde se selecciona el equipo para modificar*/
function valFormModificar(frm_modificarEquipo){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_modificarEquipo.rdb_equipo.length==undefined && !frm_modificarEquipo.rdb_equipo.checked){
		alert("Seleccionar el Equipo a Modificar");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_modificarEquipo.rdb_equipo.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_modificarEquipo.rdb_equipo.length;i++){
			if(frm_modificarEquipo.rdb_equipo[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar el Equipo a Modificar");			
	}	
	
	if(res==1)
		return true;
	else
		return false;
}

/*Esta función valida que los campos necesarios para el formulario frm_modificarEquipo esten completados*/
function valFormRegMod(frm_modificarEquipo){
	//Variable bandera que permite revisar si la validacion fue exitosa
	var band=1;
	
	//Verificamos el el numero del Equipo haya sido ingresado
	if (frm_modificarEquipo.txt_noInterno.value==""){
		alert("Introducir el Número Interno del Equipo");
		band=0;
	}
		
	//Verificamos que el nombre del equipo haya sido ingresado
	if (frm_modificarEquipo.txt_nomEquipo.value==""&&band==1){
		alert("Introducir el Nombre del Equipo");
		band=0;
	}
	
	//Verificamos que la aplicación del Equipo haya sido ingresada
	if (frm_modificarEquipo.txa_aplicacion.value==""&&band==1){
		alert("Introducir la Aplicación del Equipo");
		band=0;
	}
	
	//Verificamos que el responsable del equipo haya sido ingresado
	if (frm_modificarEquipo.txt_responsable.value==""&&band==1){
		alert("Introducir el Responsable del Equipo");
		band=0;
	}
	
	//Verificamos que el combo calibrable tenga opcion seleccionada
	if (frm_modificarEquipo.cmb_calibrable.value==""&&band==1){
		alert("Seleccionar Si el Equipo es Calibrable");
		band=0;
	}
	
	if (band==1)
		return true;
	else
		return false;
}
/***************************************************************************************************************************************************************/
/********************************************FORMULARIO PROGRAMAR MANTENIMIENTO A LOS EQUIPOS DE LABORTORIO*****************************************************/
/***************************************************************************************************************************************************************/
/*Esta función valida que los campos necesarios para el formulario frm_programarMttoEquipos por Marca esten completados*/
function valFormConsultarNombreEquipo(frm_consultarNombreEquipo){
	//Variable bandera que permite revisar si la validacion fue exitosa
	var band=1;
		
	
	if (frm_consultarNombreEquipo.cmb_marca.value==""&&band==1){
		alert("Seleccionar la Marca del Equipo");
		band=0;
	}

	if (band==1)
		return true;
	else
		return false;
}



/*Esta función valida que los campos necesarios para el formulario frm_programarMttoEquipos  por Clave esten completados*/
function valFormConsultarClaveEquipo(frm_consultarClaveEquipo){
	//Variable bandera que permite revisar si la validacion fue exitosa
	var band=1;
		
	
	if (frm_consultarClaveEquipo.txt_claveEquipo.value==""&&band==1){
		alert("Agregar la Clave del Equipo de Laboratorio");
		band=0;
	}

	if (band==1)
		return true;
	else
		return false;
}

/*Esta funcion se ecnarga de Validar el Formulario donde se selecciona el equipo de laboratorio al cual se le programara el servicio de mantenimiento*/
function valFormSeleccionarEquipoLab(frm_seleccionarEquipo){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_seleccionarEquipo.rdb_noEquipo.length==undefined && !frm_seleccionarEquipo.rdb_noEquipo.checked){
		alert("Seleccionar el Equipo de Laboratorio");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_seleccionarEquipo.rdb_noEquipo.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_seleccionarEquipo.rdb_noEquipo.length;i++){
			if(frm_seleccionarEquipo.rdb_noEquipo[i].checked)			
				res = 1;
		}					
		
		if(res==0)
			alert("Seleccionar al Menos un Equipo para Programar Mantenimiento");
	}
					
	if(res==1)
		return true;
	else
		return false;
}


/*Esta función valida que los campos necesarios para elsegundo  formulario frm_programarMttoEquipos esten completados*/
function valFormAgregarInformacionEquipo(frm_agregarInformacionEquipo){
	//Variable bandera que permite revisar si la validacion fue exitosa
	var band=1;
	if(frm_agregarInformacionEquipo.hdn_botonSeleccionado.value=="sbt_agregar"){
	
		if (frm_agregarInformacionEquipo.cmb_Mes.value==""){
			alert("Seleccionar el Mes");
			band=0;
		}
	
		if (frm_agregarInformacionEquipo.cmb_Anio.value=="" && band==1){
			alert("Seleccionar el Año");
			band=0;
		}								
		
		if(band==1){
			//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
			msgSelecRadio = 0;
			for(i=0;i<frm_agregarInformacionEquipo.rdb_tipoServicio.length;i++){
				if(frm_agregarInformacionEquipo.rdb_tipoServicio[i].checked){			
					msgSelecRadio = 1;
					band = 1;
					break;
				}
			}
		
		
			if(msgSelecRadio==0){				
				alert("Seleccionar un Tipo de Servicio para el Mantenimiento");
				band=0;
			}
		}
						
		
	}//Cierre if(frm_agregarInformacionEquipo.hdn_botonSeleccionado.value=="sbt_agregar")
	
	if (band==1)
		return true;
	else
		return false;
}


/***************************************************************************************************************************************************************/
/********************************************FORMULARIO REGISTRAR MANTENIMIENTO A LOS EQUIPOS DE LABORTORIO*****************************************************/
/***************************************************************************************************************************************************************/
/*Esta función valida que los campos necesarios para el formulario frm_programarMttoEquipos por Marca esten completados*/
function valFormSeleccionarNombreEquipo(frm_seleccionarNombreEquipo){
	//Variable bandera que permite revisar si la validacion fue exitosa
	var band=1;
		
	
	if (frm_seleccionarNombreEquipo.cmb_marca.value==""&&band==1){
		alert("Seleccionar la Marca del Equipo");
		band=0;
	}

	if (band==1)
		return true;
	else
		return false;
}



/*Esta función valida que los campos necesarios para el formulario frm_programarMttoEquipos  por Clave esten completados*/
function valFormSeleccionarClaveEquipo(frm_consultarClaveEquipo){
	//Variable bandera que permite revisar si la validacion fue exitosa
	var band=1;
		
	
	if (frm_consultarClaveEquipo.txt_claveEquipo.value==""&&band==1){
		alert("Agregar la Clave del Equipo de Laboratorio");
		band=0;
	}

	if (band==1)
		return true;
	else
		return false;
}

/*Esta funcion se encarga de Validar el Formulario donde se selecciona el equipo de laboratorio al cual se le programara el servicio de mantenimiento*/
function valFormRegistrarEquipoLab(frm_registrarEquipo){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_registrarEquipo.rdb_idServicio.length==undefined && !frm_registrarEquipo.rdb_idServicio.checked){
		alert("Seleccionar el Equipo de Laboratorio");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_registrarEquipo.rdb_idServicio.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_registrarEquipo.rdb_idServicio.length;i++){
			if(frm_registrarEquipo.rdb_idServicio[i].checked)			
				res = 1;
		}					
		
		if(res==0)
			alert("Seleccionar al Menos un Equipo para Registrar Mantenimiento");
	}
					
	if(res==1)
		return true;
	else
		return false;
}


/*Esta función valida que los campos necesarios para elsegundo  formulario frm_programarMttoEquipos esten completados*/
function valFormRegistrarInformacionMtto(frm_registrarInformacionMtto){
	//Variable bandera que permite revisar si la validacion fue exitosa
	var band=1;
if(frm_registrarInformacionMtto.hdn_botonSeleccionado.value=="sbt_agregar"){
	
	if (frm_registrarInformacionMtto.txt_encargadoMtto.value=="" && band==1){
		alert("Introducir el Encargado de Mantenimiento");
		band=0;
	}
		if (frm_registrarInformacionMtto.cmb_servicioMtto.value=="" && band==1){
		alert("Seleccionar el Tipo de Mantenimiento");
		band=0;
	}

	if (frm_registrarInformacionMtto.txa_detalleServicio.value=="" && band==1){
		alert("Introducir el Detalle del Servicio");
		band=0;
	}
}
	
	if (band==1)
		return true;
	else
		return false;
}

/****************************************************************************************************************************************************************/
/******************************************CARGAR FOTOGRAFIAS DEL SERVICIO DE MTTO AL EQUIPO DE LABORATORIO******************************************************/
/****************************************************************************************************************************************************************/
/*Esta funcion se ecnarga de Validar el Formulario donde se selecciona el equipo para registrar Mantenimiento*/
function valFormCargarFotoLaboratorio(frm_cargarFotoLab){
	//Variable bandera que permite revisar si la validacion fue exitosa
	var band=1;	
	if (frm_cargarFotoLab.txt_fotografiaAntes.value==""){
		alert("Cargar la Fotografia correspondiente a Antes del Servicio de Mtto");
		band=0;
	}
		if (frm_cargarFotoLab.txt_fotografiaDespues.value=="" && band==1){
		alert("Cargar la Fotografia correspondiente Despues del Servicio de Mtto");
		band=0;
	}
	
	//Verificar que la imagen proporcionada tenga el formato y tamaño soportado por el Sistema
	if(band==1){
		if(document.getElementById("hdn_imgValida_antes").value=="no" || document.getElementById("hdn_imgValida_despues").value=="no"){
			alert("Verificar el Formato y Tamaño de las Imágenes Proporcionadas");
			band = 0;
		}
	}
	
	if (band==1)
		return true;
	else
		return false;
}

/****************************************************************************************************************************************************************/
/********************************************************************ALERTAS MANTENIMIENTO***********************************************************************/
/****************************************************************************************************************************************************************/
/*Esta funcion se ecnarga de Validar el Formulario donde se selecciona el equipo para registrar Mantenimiento*/
function valFormValidarAlertaMtto(frm_resultadosMtto){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_resultadosMtto.rdb_equipo.length==undefined && !frm_resultadosMtto.rdb_equipo.checked){
		alert("Seleccionar el Equipo para Registrar Mantenimiento");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_resultadosMtto.rdb_equipo.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_resultadosMtto.rdb_equipo.length;i++){
			if(frm_resultadosMtto.rdb_equipo[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar el Equipo para Registrar Mantenimiento");			
	}
		
	if(res==1)
		return true;
	else
		return false;
}


/****************************************************************************************************************************************************************/
/********************************************************************ALERTAS PRUEAS******************************************************************************/
/****************************************************************************************************************************************************************/
/*Esta funcion se ecnarga de Validar el Formulario donde se selecciona la prueba para realizar las pruebas*/
function valFormValidarAlertaPruebas(frm_resultadosPruebas){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_resultadosPruebas.rdb_prueba.length==undefined && !frm_resultadosPruebas.rdb_prueba.checked){
		alert("Seleccionar la Prueba para registrar Resultados");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_resultadosPruebas.rdb_prueba.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_resultadosPruebas.rdb_prueba.length;i++){
			if(frm_resultadosPruebas.rdb_prueba[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar la Prueba para registrar Resultados");			
	}
		
	if(res==1)
		return true;
	else
		return false;
}

/****************************************************************************************************************************************************************/
/*********************************************************************REPORTE AGREGADOS**************************************************************************/
/****************************************************************************************************************************************************************/
//Función para validar el formulario de reportes de agregados y fecha
function valFormRptAgregados(frm_consultarAgregado){
	//Variable que permite revisar si la validación fue exitosa
	var band=1;
	//Verificamos que el combo area este definido
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_consultarAgregado.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_consultarAgregado.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_consultarAgregado.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_consultarAgregado.txt_fechaFin.value.substr(0,2);
	var finMes=frm_consultarAgregado.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_consultarAgregado.txt_fechaFin.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	
	if (frm_consultarAgregado.cmb_agregado.value==""&&band==1){
		alert("Seleccionar Agregado");
		band=0;
	}
	
	//Verificar que la fecha de inicio no sea mayor a la de cierre
	if(fechaIni>fechaFin){
		band=0;
		alert ("La Fecha de Inicio no puede ser Mayor a la Fecha de Fin");
	}
	
	if (band==1)
		return true;
	else
		return false;
	
}


//Función para validar el formulario de reportes de agregados unicamente por fecha
function valFormRptAgregadosFecha(frm_reporteFecha){
	//Variable que permite revisar si la validación fue exitosa
	var band=1;
	//Verificamos que el combo area este definido
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_reporteFecha.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_reporteFecha.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_reporteFecha.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_reporteFecha.txt_fechaFin.value.substr(0,2);
	var finMes=frm_reporteFecha.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_consultarAgregado.txt_fechaFin.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	//Verificar que la fecha de inicio no sea mayor a la de cierre
	if(fechaIni>fechaFin){
		band=0;
		alert ("La Fecha de Inicio no puede ser Mayor a la Fecha de Fin");
	}
	if (band==1)
		return true;
	else
		return false;	
}


/****************************************************************************************************************************************************************/
/******************************************************************REPORTE RESISTENCIAS**************************************************************************/
/****************************************************************************************************************************************************************/

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


/*Esta funcion se ecnarga de Validar el Formulario donde se selecciona un tipo de prueba y clave para generar el reporte fotográfico en PDF*/
function valFormReporteResistencias(frm_reporteResistencias){
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


/****************************************************************************************************************************************************************/
/*********************************************************************REPORTES MANTENIMIENTO*********************************************************************/
/****************************************************************************************************************************************************************/

/*Esta funcion valida el rango de fechas seleccionadas*/
function valFormReporteEquipoLabFecha(frm_reporteEquipoLabFecha){
	
	//Variable que permite revisar si la validación fue exitosa
	var band=1;
	//Verificamos que el combo area este definido
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_reporteEquipoLabFecha.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_reporteEquipoLabFecha.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_reporteEquipoLabFecha.txt_fechaIni.value.substr(6,4);
												
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_reporteEquipoLabFecha.txt_fechaFin.value.substr(0,2);
	var finMes=frm_reporteEquipoLabFecha.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_reporteEquipoLabFecha.txt_fechaFin.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	//Verificar que la fecha de inicio no sea mayor a la de cierre
	if(fechaIni>fechaFin){
		band=0;
		alert ("La Fecha de Inicio no puede ser Mayor a la Fecha de Fin");
	}
	
	
	if (band==1)
		return true;
	else
		return false;	
}


/*Esta funcion valida el rango de fechas seleccionadas*/
function valFormReporteEquipoLabNombre(frm_reporteEquipoLabNombre){
	
	//Variable que permite revisar si la validación fue exitosa
	var band=1;
	//Verificamos que el combo area este definido
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_reporteEquipoLabNombre.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_reporteEquipoLabNombre.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_reporteEquipoLabNombre.txt_fechaIni.value.substr(6,4);
												
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_reporteEquipoLabNombre.txt_fechaFin.value.substr(0,2);
	var finMes=frm_reporteEquipoLabNombre.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_reporteEquipoLabNombre.txt_fechaFin.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	//Verificar que la fecha de inicio no sea mayor a la de cierre
	if(fechaIni>fechaFin){
		band=0;
		alert ("La Fecha de Inicio no puede ser Mayor a la Fecha de Fin");
	}
	
	//Verificar que sea seleccionado el nombre de un equipo
	if(frm_reporteEquipoLabNombre.cmb_nombreEquipoLab.value=="" && band ==1){
		alert("Seleccionar el Nombre de un Equipo");
		band = 0;
	}
	
	if (band==1)
		return true;
	else
		return false;	
}



/*Esta funcion solicita el nombre de la persona que Elabora el Reporte de Mantenimiento*/
function complementarRptMtto(){
	//Variable de control para cuando aparezca el formulario	
	var ctrlForm = 0;
	
	var elaboro="";
	while(elaboro=="Quien Elabora..." || elaboro==""){
		elaboro = prompt('Ingresar Nombre de Quien Elabora el Reporte','Quien Elabora...' );

		if(elaboro==null)
			ctrlForm = 1;	
	}
	
	if(ctrlForm==0){
		//Asignar el valor obtenido a la caja de texto que lo mostrara
		document.getElementById("hdn_nombreElaboro").value=elaboro;
	}
	
	
	//Evaluar el resultado final
	if(ctrlForm==0)
		return true;
	else
		return false;
}



/****************************************************************************************************************************************************************/
/*********************************************************************REPORTES RENDIMIENTO*********************************************************************/
/****************************************************************************************************************************************************************/
/*Esta funcion valida el Rango de Fechas seleccionado y que el nombre del equipo sea proporcionado*/
function valFormRepRendimiento(frm_reporteRendimiento){
	//Si la variable permanece en 1, el proceso de validacion fue exitoso
	var band = 1;
	
	//Verificar que sea seleccionado una mezcla
	if(frm_reporteRendimiento.cmb_idMezcla.value==""){
		alert("Seleccionar Una Mezcla");
		band = 0;
	}
	
	//Evaluar el resultado final
	if(band==1)
		return true;
	else
		return false;
}

/*Esta funcion valida el rango de fechas seleccionadas*/
function valFormRepRendimientoFecha(frm_reporteFecha){
	
	//Variable que permite revisar si la validación fue exitosa
	var band=1;
	//Verificamos que el combo area este definido
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_reporteFecha.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_reporteFecha.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_reporteFecha.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_reporteFecha.txt_fechaFin.value.substr(0,2);
	var finMes=frm_reporteFecha.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_reporteFecha.txt_fechaFin.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	//Verificar que la fecha de inicio no sea mayor a la de cierre
	if(fechaIni>fechaFin){
		band=0;
		alert ("La Fecha de Inicio no puede ser Mayor a la Fecha de Fin");
	}
	if (band==1)
		return true;
	else
		return false;	
}

/*Esta funcion pide los datos del Destinatario, Puesto y Empresa del Reporte de Rendimientos y Genera dicho reporte en archivo de Excel*/
function solicitarDatos(frm_exportarDatos){
	//Pedir Datos para ser enviados por la URL(especificada en el atributo action del formulario)
	var nombre="";
	while(nombre=="Destinatario..." || nombre==""){
		var nombre = prompt("Introducir Nombre del Destinatario","Destinatario...");
	}
		
	var puesto="";
	//Solicitar el Puesto cuando el usuario no haya cancelado el proceso
	if(nombre!=null){
		while(puesto=="Puesto..." || puesto==""){
			var puesto = prompt("Introducir Puesto","Puesto...");
		}
	}
	
	var empresa="";
	//Solicitar la Empresa cuando el usuario no haya cancelado el proceso
	if(nombre!=null && puesto!=null){
		while(empresa=="Empresa..." || empresa==""){	
			var empresa = prompt("Introducir Nombre de la Empresa","Empresa...");	
		}
	}
	
	//verificar que no se haya cancelado la solicitud de algun dato
	if(nombre!=null && puesto!=null && empresa!=null){			
		//Colocar en la url en el atributo action del formulario
		var url = "guardar_reporte.php?nombre="+nombre+"&puesto="+puesto+"&empresa="+empresa;
		frm_exportarDatos.action = url;
		
		//Retornar verdadero para que el formulario sea enviado con los datos en la URL
		return true;
	}
	else{
		return false;
	}
	
	
}


/****************************************************************************************************************************************************************/
/*********************************************************************CATALOGO DE NORMAS*************************************************************************/
/****************************************************************************************************************************************************************/
/*Esta funcion valida el Rango de Fechas seleccionado y que el nombre del equipo sea proporcionado*/
function valFormOpcionesCatalogo(frm_opcionesCatalogo){
	//Si la variable permanece en 1, el proceso de validacion fue exitoso
	var band = 1;

	if(frm_opcionesCatalogo.hdn_botonSeleccionado.value=="sbt_modificar"||frm_opcionesCatalogo.hdn_botonSeleccionado.value=="sbt_eliminar"&&band==1)
		//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
		if(frm_opcionesCatalogo.rdb_norma.length==undefined && !frm_opcionesCatalogo.rdb_norma.checked){
			alert("Seleccionar la Norma a Borrar/Modificar");
			band = 0;
		}
		//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
		if(frm_opcionesCatalogo.rdb_norma.length>=2){
			//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
			band = 0; 
			//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
			for(i=0;i<frm_opcionesCatalogo.rdb_norma.length;i++){
				if(frm_opcionesCatalogo.rdb_norma[i].checked)
					band = 1;
		}
		if(band==0)
			alert("Seleccionar la Norma a Borrar/Modificar");			
	}
	if (band==1 &&frm_opcionesCatalogo.hdn_botonSeleccionado.value=="sbt_eliminar"){
		if (!confirm("¿Estas Seguro que Quieres Borrar el Concepto?\nToda la información relacionada se Borrará")){
			band=0;
		}
	}
	
	//Evaluar el resultado final
	if(band==1)
		return true;
	else
		return false;
}


/*Esta funcion valida el Rango de Fechas seleccionado y que el nombre del equipo sea proporcionado*/
function valFormCatalogo(frm_catalogo){
	//Si la variable permanece en 1, el proceso de validacion fue exitoso
	var band = 1;

	if(frm_catalogo.hdn_botonSeleccionado.value=="sbt_agregar"){
		//Verificar que sea seleccionado una mezcla
		if(frm_catalogo.cmb_norma.value==""){
			alert("Seleccionar Una Norma");
			band = 0;
		}
		
		//Verificar que sea seleccionado una mezcla
		if(frm_catalogo.cmb_agregado.value==""&&band==1){
			alert("Seleccionar Un Agregado");
			band = 0;
		}
		
		//Verificar que sea seleccionado una mezcla
		if(frm_catalogo.txt_concepto.value==""&&band==1){
			alert("Introducir Concepto");
			band = 0;
		}
		
		//Verificar que sea seleccionado una mezcla
		if(frm_catalogo.txt_limSup.value==""&&band==1){
			alert("Introducir Límite Superior");
			band = 0;
		}
		if(band==1){
			if(!validarEnteroConCero(frm_catalogo.txt_limSup.value.replace(/,/g,''),"el Límite Superior"))
			band=0;
		}
		
		//Verificar que sea seleccionado una mezcla
		if(frm_catalogo.txt_limInf.value==""&&band==1){
			alert("Introducir Límite Inferior");
			band = 0;
		}
		if(band==1){
			if(!validarEnteroConCero(frm_catalogo.txt_limInf.value.replace(/,/g,''),"el Límite Inferior"))
			band=0;
		}
		
	}
	if(frm_catalogo.hdn_botonSeleccionado.value=="sbt_finalizar"){
		if(confirm("¿Estas Seguro que Quieres Finalizar el Registro?\nNo se podran Agregar mas Registros a la Norma y Agregado Seleccionado")&&band==1)
			band=1;
		else
			band=0;
	}
	//Evaluar el resultado final
	if(band==1)
		return true;
	else
		return false;
}


/*Esta función valida que los campos necesarios para el formulario buscar agregados por id esten completados  por clave*/
function valFormModificarAgregados(frm_catalogoModificar){
	//Variable bandera que permite revisar si la validacion fue exitosa
	var band=1;
	
	//Verificar que se haya ingresado el concepto
	if (frm_catalogoModificar.txt_concepto.value==""){
		alert("Ingresar el Concepto");
		band=0;
	}

	//Verificar que haya ingresado Límite Superior
	if(frm_catalogoModificar.txt_limSup.value==""&&band==1){
		alert("Introducir Límite Superior");
		band = 0;
	}
	if(band==1){
		if(!validarEnteroConCero(frm_catalogoModificar.txt_limSup.value.replace(/,/g,''),"el Límite Superior"))
		band=0;
	}
	//Verificar que se hay ingresado el Límite Inferior
	if(frm_catalogoModificar.txt_limInf.value==""&&band==1){
		alert("Introducir Límite Inferior");
		band = 0;
	}
	if(band==1){
		if(!validarEnteroConCero(frm_catalogoModificar.txt_limInf.value.replace(/,/g,''),"el Límite Inferior"))
		band=0;
	}

	if (band==1)
		return true;
	else
		return false;
}
























