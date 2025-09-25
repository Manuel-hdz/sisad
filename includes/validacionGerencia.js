/**
  *	Armando Ayala Alvarado
  * Nombre del Módulo: Gerencia                                               
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.                                			
  * Descripción: Este archivo contiene funciones para validar los diferentes formularios del Módulo Gerencia Técnica
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

/******************************************************VALIDAR CLAVE DE LOS MATERIALES**************************************************************************/
/*Esta función se encarga de que el usuario no pueda ingresar caracteres invalidos en los campos de los diferentes formulario del Módulo de Almacén*/
function permiteClavesMaterial(elEvento, permitidos) {
	//Variables que definen los caracteres permitidos
	var numeros = "0123456789";
	var caracteres = " abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ";
	var numeros_caracteres = numeros + caracteres;
	var teclas_especiales = [8, 37, 44, 45, 46, 47];//8 = BackSpace, 37 = Signo Porcentaje, 44 = Coma, 45 = Guion medio, 46 = Punto, 47 = Diagonal
	//Seleccionar los caracteres a partir del parámetro de la función
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

/*Esta funcion solicita la confirmación del usuario antes de salir de la pagina*/
function confirmarSalida(pagina){
	if(confirm("¿Estas Seguro que Quieres Salir?\nToda la información no Guardada se Perderá"))
		location.href = pagina;	
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
/***************************************************************************************************************************************/
/************************************************************VALIDACION CUADRILLAS******************************************************/
/***************************************************************************************************************************************/

/*Esta función valida que sea selecionada una Ubicación o sea ingresada una nueva*/
function valFormCuadrillas1(frm_agregarCuadrilla){
	var band = 1;//Si el valor se mantiene en 1 la validacion será satisfactoria
	
	if(frm_agregarCuadrilla.txt_IDCuadrilla.value==""){
		alert("Seleccionar una Ubicación o Ingresar una Nueva");
		band = 0;
	}
	
	if(band==1){
		if( !frm_agregarCuadrilla.ckb_zarpeoViaSeca.checked && !frm_agregarCuadrilla.ckb_instalacionMalla.checked && !frm_agregarCuadrilla.ckb_zarpeoViaHumeda.checked){
			alert("Seleccionar una al Menos una Aplicación para la Cuadrilla");
			band = 0;
		}
	}
	
	
	if(band==1)
		return true;
	else
		return false;	
}


/*Esta función valida que sea selecionada una Ubicación o sea ingresada una nueva*/
function valFormCuadrillas2(frm_agregarCuadrilla2){
	var res=1
	if (frm_agregarCuadrilla2.hdn_validar.value=="si"){
		if(frm_agregarCuadrilla2.txt_nombre.value==""){
			alert("Ingresar el Nombre de un Trabajador");
			frm_agregarCuadrilla2.txt_nombre.focus();
			res=0;
		}
		if(frm_agregarCuadrilla2.cmb_puesto.value=="" && res==1){
			alert("Seleccionar el Puesto del Trabajador en la Cuadrilla");
			frm_agregarCuadrilla2.cmb_puesto.focus();
			res=0;
		}
	}
	if(res==1)
		return true;
	else
		return false;
}

/************************************************************MODIFICAR CUADRILLAS******************************************************/
//funcion que valida se haya introducido un dato en el campo de busqueda de Cuadrillas
function valFormModificarCuadrilla1(frm_modificarCuadrillaXClave){
	if(frm_modificarCuadrillaXClave.txt_IDCuadrilla.value==""){
		alert("Ingrese el ID de la Cuadrilla a Buscar");
		return false;
	}
	else{
		return true;
	}
}

//funcion que valida se haya introducido un dato en el campo de busqueda de Cuadrillas
function valFormModificarCuadrilla2(frm_modificarCuadrillaXUbicacion){
	if(frm_modificarCuadrillaXUbicacion.cmb_ubicacion.value==""){
		alert("Seleccione la Ubicación a Buscar");
		return false;
	}
	else{
		return true;
	}
}

/*Esta función valida que se seleccione una prueba en el formulario Resultados encontrados*/
function valFormSeleccionarCuadrillaMod(frm_seleccionarCuadrilla){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_seleccionarCuadrilla.rdb_idCuadrilla.length==undefined && !frm_seleccionarCuadrilla.rdb_idCuadrilla.checked){
		alert("Seleccionar la Cuadrilla a "+frm_seleccionarCuadrilla.hdn_accion.value);
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_seleccionarCuadrilla.rdb_idCuadrilla.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_seleccionarCuadrilla.rdb_idCuadrilla.length;i++){
			if(frm_seleccionarCuadrilla.rdb_idCuadrilla[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar la Cuadrilla a "+frm_seleccionarCuadrilla.hdn_accion.value);
	}
	
	if(res==1)
		return true;
	else
		return false;
}

/*Esta función valida que se seleccione una prueba en el formulario Resultados encontrados*/
function valFormBorrarPersonalMod(frm_modificarPersonalCuadrilla){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_modificarPersonalCuadrilla.rdb_rfcPersona.length==undefined && !frm_modificarPersonalCuadrilla.rdb_rfcPersona.checked){
		//alert("Seleccionar la Cuadrilla a "+frm_seleccionarCuadrilla.hdn_accion.value);
		alert("Seleccionar Integrante a Borrar");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_modificarPersonalCuadrilla.rdb_rfcPersona.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_modificarPersonalCuadrilla.rdb_rfcPersona.length;i++){
			if(frm_modificarPersonalCuadrilla.rdb_rfcPersona[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar Integrante a Borrar");
	}
	
	if(res==1)
		return true;
	else
		return false;
}

/***************************************************************************************************************************************/
/*************************************************************GENERAR REQUISICION*******************************************************/
/***************************************************************************************************************************************/
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


/***************************************************************************************************************************************/
/***********************************************************REGISTRAR PEDIDO************************************************************/
/***************************************************************************************************************************************/
//Funcion que suma los Importes en la seccion de complementar Pedidos
function sumaImporte(){
	var a=0;
	var b=document.getElementsByName("txt_importe").length;
	for (var i=0;i<b;i++){
		a=a+parseFloat(document.getElementsByName("txt_importe")[i].value.replace(/,/g,''));
		if (!isNaN(a))
			document.getElementById("txt_subtotal").value=a;
	}
	if (!isNaN(document.getElementById("txt_subtotal").value)&&document.getElementById("txt_subtotal").value!=0)
		formatCurrency(document.getElementById("txt_subtotal").value,'txt_subtotal');
}

//Funcion que revisa que los datos de un pedido a partir de una requisicion sean complementados
function valFormComplementoPedido(formulario){
	var band=1;
	//Obtener la cantidad de cajas agregadas
	var num=document.getElementsByName("txt_importe").length;
	for (var i=0;i<num;i++){
		if (document.getElementsByName("txt_importe")[i].value==""){
			band=0;
			alert("Introducir el Precio Unitario del Concepto No. "+(i+1));
			break;
		}
			if (document.getElementsByName("txt_importe")[i].value==0.00){
			band=0;
			alert("El Precio Unitario del Concepto No. "+(i+1) +"  "+ "No puede ser cero");
			break;
		}
	}
	
	if (document.getElementById("txt_subtotal")==""||document.getElementById("txt_subtotal")==0.00 && band==1){
		band=0;
		alert("Falta calcular el costo subtotal, seleccionar la caja Subtotal");
	}
	
	if(band==1)
		return true;
	else
		return false;	
}
//Funcion que agrega el IVA a los precios registrados en el Pedido
function sumarIva(iva,estadoIva){
	if (estadoIva=="no"){
		//Obtener el valor de la caja subtotal
		var subtotal=document.getElementById("txt_subtotal").value.replace(/,/g,'');
		//Calcular el iva correspondiente al subtotal
		iva=(parseFloat(iva))/100;
		//Obtener el valor del subtotal con el IVA
		subtotal_iva=subtotal*iva;
		//Obtener el valor en $ del IVA
		total=subtotal_iva+subtotal;
		//Calcular el total de la suma del subtotal mas el valor en $ del IVA
		total=parseFloat(subtotal_iva)+parseFloat(subtotal);
		//Asignar el valor del IVA en $ a la caja correspondiente
		document.getElementById("txt_iva").value=subtotal_iva;
		//Asignar el valor del Total a la caja correspondiente
		document.getElementById("txt_total").value=total;
		//Aplicar el formato a los valores de las cajas de Texto del IVA y el TOTAL
		formatCurrency(document.getElementById("txt_iva").value,'txt_iva');
		formatCurrency(document.getElementById("txt_total").value,'txt_total');
	}
	else{
		//Recuperar el Valor del TOTAL
		var total = document.getElementById("txt_total").value.replace(/,/g,'');
		//Calcular el iva correspondiente al subtotal
		divisor = parseFloat((iva/100)+1);
		//Calcular el valor en $ del IVA
		subtotal = total / divisor;
		//Calcular el subtotal
		cantIVA = total - subtotal;

		//Aplicar el formato a los valores de las cajas de Texto del IVA y el SUBTOTAL
		formatCurrency(cantIVA,'txt_iva');
		formatCurrency(subtotal,'txt_subtotal');
	}
}


//Validar los datos del formulario de Registrar Pedido
function valFormRegistrarPedido(frm_registrarPedido){
	//Si el valor se mantiene en 1, entonces todos los campos fueron proporcionados por el usuario
	var band = 1;

	//Verificar primero que la clave del pedido no se encuentre vacio
	if(frm_registrarPedido.txt_noPedido.value==""){
		band = 0;		
		alert ("Introducir la Clave del Pedido");
	}
	else{
		//Luego verificar que el plazo de entrega no este vacia
		if(frm_registrarPedido.txt_plazo.value==""){
			band = 0;
			alert ("Introducir el Plazo de Entrega");
		}
		else{
			//Verificar que el tiempo de Plazo  no este vacio
			if(frm_registrarPedido.cmb_plazo.value==""){
				band = 0;
				alert ("Introducir el tiempo de Plazo de Entrega");
			}
			else{
				//Verificar que las condiciones de Entrega no este vacio
				if(frm_registrarPedido.txa_condEnt.value==""){
					band = 0;	
					alert ("Introducir  las Condiciones de Entrega");
				}
				else{
					//Verificar que las condiciones de pago no esten vacias
					if(frm_registrarPedido.cmb_condPago.value==""){
						band = 0;			
						alert ("Seleccionar la Condición de Pago");
					}
					else{
						//Verificar que la via del pedido haya sido seleccionado
						if(frm_registrarPedido.cmb_viaPed.value==""){
							band = 0;	
							alert ("Seleccionar la Vía del Pedido");
						}
						else{
							if(frm_registrarPedido.cmb_proveedor.value==""){
								band = 0;
								alert ("Selecciona el Nombre del Proveedor");
							}
							
							else{
								//Verificar que el Campo de IVA no se encuentra vacio, debera tener el valor de IVA de los materiales de lo contrario un valor 0.00
								if(frm_registrarPedido.txt_lblIVA.value==""){
									band = 0;	
									alert ("Introducir el IVA a los Materiales, de lo Contrario Colocar un Valor Cero, en caso de que los Materiales No Contengan IVA ");
								}
							
								else{
									//Verificar que el Subtotal no este vacio
									if(frm_registrarPedido.txt_subtotal.value==""){
										band = 0;	
										alert ("Introducir el Subtotal");
									}
									else{
										//Verificar que el IVA no este vacio
										if(frm_registrarPedido.txt_iva.value==""){
											band = 0;	
											alert ("Introducir el IVA");
										}
										else{
											//Verificar que el Total no este vacio
											if(frm_registrarPedido.txt_total.value==""){
												band = 0;	
												alert ("Introducir el Tiempo de Llegada");
											}
											else{
												//Verificar que el Comprador no este vacio
												if(frm_registrarPedido.txt_solicito.value==""){
													band = 0;	
													alert ("Ingresar el Nombre de la persona que Solicitó");
												}
												else{
													//Verificar que el Revisor no este vacio
													if(frm_registrarPedido.txt_reviso.value==""){
														band = 0;	
														alert ("Introducir el Nombre de quien Reviso");	
													}
													else{
														//Verificar que el Autorizo no este vacio
														if(frm_registrarPedido.txt_autorizo.value==""){
															band = 0;	
															alert ("Introducir el nombre de quien Autorizo");	
														}//Else Revisor
														else{
															if(frm_registrarPedido.cmb_tipoMoneda.value==""){
																band=0;
																alert("Seleccionar el Tipo de Moneda");
																}
														}//Else Autoriza
													}//Else Revisor
												}//Else Solicitante
											}//Else Total
										}//Else IVA
									}//Else del Subtotal
								}//Else de la seccion donde se registra el % del IVA
			   				}//Else del Proveedor
						}//Else de la Via del Pedido
					}//Else Condiciones de Pago
				}//Else de Condiciones de Entrega
			}//Else del combo de Plazo
		}//Else de la Caja de Texto de Plazo
	}//Else de la Clave del Pedido
				
	if(band==1)
		return true;
	else
		return false;
}

/*Esta funcion agregara los terminos de las condiciones de pago en el caso de que CONTADO o CREDITO no sean suficientes para describir los terminos de pago*/
function agregarDescripcion(comboBox){
	//Si la opcion seleccionada es agregar nueva descripcion ejecutar el siguiete codigo
	if(comboBox.value=="NUEVA"){
		var condicionPago = "";
		var condicion = false;
		do{
			condicionPago = prompt("Introducir Descripción de la Condición de Pago","Condición de Pago...");
			if(condicionPago=="Condición de Pago..." ||  condicionPago=="")
				condicion = true;	
			else
				condicion = false;
		}while(condicion);
		
		
		//Si el usuario presiono calncelar no se relaiza ninguan actividad de lo contrario asignar la nueva opcion al combo
		if(condicionPago!=null){
			//Convertir a mayusculas la opcion dada
			condicionPago = condicionPago.toUpperCase();
			//Variable que nos ayudara a saber si la nueva opcion ya esta registrada en el combo
			var existe = 0;
			
			for(i=0; i<comboBox.length; i++){
				//Verificar que la nueva opcion no se encuentre dentro de las opciones actuales del combo
				if(comboBox.options[i].value==condicionPago)
					existe = 1;
			}//Cierre for(i=0; i<comboBox.length; i++)
			
			//Si la nueva opcion no esta registrada agregarla como una adicional y preseleccionarla
			if(existe==0){
				//Agregar al final la nueva opcion ingresada
				comboBox.length++;
				comboBox.options[comboBox.length-1].text = condicionPago.substring(0,20)+" ...";
				comboBox.options[comboBox.length-1].value = condicionPago;
				comboBox.options[comboBox.length-1].title = condicionPago;
				//Preseleccionar la opcion agregada
				comboBox.options[comboBox.length-1].selected = true;
			}//Cierre if(existe==0)
			
			else{
				alert("La Descripción Ingresada ya esta Registrada \n en las Opciones de la Lista de Condiciones de Pago");
				comboBox.value = condicionPago;
			}
		}//Cierre if(nvaMedida!= null)
		else if(condicionPago==null){
			comboBox.value = "";	
		}
	}//Cierre if(comboBox.value=="NUEVA")
	
}//Cierre de la funcion agregarDescripcion(comboBox)

/***************************************************************************************************************************************/
/**********************************************************CONSULTAR PEDIDOS************************************************************/
/***************************************************************************************************************************************/
/*Esta funcion valida que las fechas elegidas sean correctas*/
function valFormFechasPedidos(formulario){
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
		//Verificar que los días laborales sean proporcionados		
		if (frm_registrarPresupuesto.txt_diasLaborales.value==""&&band==1){
			alert ("Seleccionar un Rango de Fechas o Ingresar la Cantidad de Días Laborales");
			band=0;
		}
		
		if(band==1){
			if(!validarEntero(frm_registrarPresupuesto.txt_diasLaborales.value.replace(/,/g,''),"La Cantidad de Días Laborales"))
				band = 0;		
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
		
		if (!frm_registrarPresupuesto.ckb_nuevaUbicacion.checked && frm_registrarPresupuesto.cmb_ubicacion.value==""  && band==1){
			alert("Ingresar la Ubicación");
			band=0;
		}
		
		
		if(frm_registrarPresupuesto.ckb_nuevaUbicacion.checked  && band==1){
			if(frm_registrarPresupuesto.txt_nuevaUbicacion.value.toUpperCase()=="COLADO" || frm_registrarPresupuesto.txt_nuevaUbicacion.value.toUpperCase()=="COLADOS"){
				alert("No Puede Registrar esta Ubicación");
				//Limpiar la caja de texto
				frm_registrarPresupuesto.txt_nuevaUbicacion.value = "";
				//des seleccionar el check box
				frm_registrarPresupuesto.ckb_nuevaUbicacion.checked =false;
				//Activar el combo box
				frm_registrarPresupuesto.cmb_ubicacion.disabled =false;
				band = 0;
			}
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
	
		
		if(band==1&&frm_registrarPresupuesto.hdn_fechas.value=="1"){
			alert("Ambas Fechas ya se Encuentran Registradas  \nSeleccionar Otras Fechas");	
			band = 0;		
		}
		
		if(band==1&&frm_registrarPresupuesto.hdn_fechas.value=="2"){
			alert("La Fecha de Inicio se Encuentra Registrada en Otro Presupuesto \nSeleccionar Otra Fecha");	
			band = 0;		
		}
		
		if(band==1&&frm_registrarPresupuesto.hdn_fechas.value=="3"){
			alert("La Fecha de Fin ya se Encuentra Registrada en Otro Presupuesto\nSeleccionar Otra Fecha");	
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

//Esta funcion solicita al usuario la nueva ubicacion  y desabilita el combo de Ubicacion
function agregarNuevaUbicacion(ckb_nuevaUbicacion, txt_nuevaUbicacion, cmb_ubicacion){
	var band=0;
	var valor = ""; //Variable utilizada para que cuando el nombre del la nueva ubicacion ingresado ya se encuentra dentro del combo de ubicacion, se muestre
	//Si el checkbox para el nuevo cliente esta seleccionado, pedir el nombre de dicha ubicacion
	if (ckb_nuevaUbicacion.checked){
		var ubicacion = prompt("¿Nombre de la Nueva Ubicación?","Nombre de la Ubicación...");	
		if(ubicacion!=null && ubicacion!="Nombre de la Ubicación..." && ubicacion!=""){
			ubicacion = ubicacion.toUpperCase();			
			if(ubicacion.length<=40){			
				for(i=0; i<document.getElementById("cmb_ubicacion").length; i++){
					//verificar que la nueva opcion no se encuentre dentro de las opciones actuales del combo
					if(document.getElementById("cmb_ubicacion").options[i].text==ubicacion){
						valor = document.getElementById("cmb_ubicacion").options[i].value;
						band = 1;
					}
				}//Cierre for(i=0;i<seccion.length;i++)
				
				if(band==1){ 
					alert("La Ubicación Ingresada ya Existe ");
					document.getElementById("cmb_ubicacion").value=valor;
					//Dechecar el check de la Nueva Ubicacion
					document.getElementById("ckb_nuevaUbicacion").checked = false;
				}//Fin del if(band==1){
					
					if(band==0){
						//Asignar el valor obtenido a la caja de texto que lo mostrara
						document.getElementById(txt_nuevaUbicacion).value = ubicacion.toUpperCase();
						//Verificar que el combo este definido para poder deshabilitarlo
						if(document.getElementById(cmb_ubicacion)!=null)
							//Deshabilitar el ComboBox para que el usuario no lo pueda modificar
							document.getElementById(cmb_ubicacion).disabled = true;				
					}// Fin del if(band==0){
			}
			
			else{
				alert("El Nombre de la Ubicación Excede el Número de Caracteres Permitidos");
				//Regresar False si se presiona el botón cancelar o se asigna un valor equivocado
				ckb_nuevaUbicacion.checked = false;
				band=0;
			}
		}
		else{
			//Regresar False si se presiona el botón cancelar o se asigna un valor equivocado
			ckb_nuevaUbicacion.checked = false;
		}		
	}
	//Si el checkbox para un nuevo destino se deselecciona, borrar el dato escrito en la caja de texto y reactivar el combo de Destino
	else{
		document.getElementById(txt_nuevaUbicacion).value = "";
		//Verificar que el combo este definido para poder Habilitarlo
		if (document.getElementById(cmb_ubicacion)!=null){
			//Habilitar el ComboBox y el deseleccionar el CheckBox para que el usuario pueda agregar nueva información
			document.getElementById(cmb_ubicacion).disabled = false;
			//Darle un valor vacio por default
			document.getElementById(cmb_ubicacion).value = "";
		}	
	}
	
			
}


/*Esta funcion habilita las cajas o combos que son deshabilitados*/
function restablecePresupuesto(){
	document.getElementById("cmb_ubicacion").disabled = false;
	document.getElementById("txt_nuevaUbicacion").disabled = false;
}

/***************************************************************************************************************************************/
/********************************************************MODIFICAR PRESUPUESTO**********************************************************/
/***************************************************************************************************************************************/
//Funcion para Evaluar los datoas del formularo modificar Presupuesto
function valFormBusqPresupuesto(frm_modificarPresupuesto){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;
	
	
	
	//Se verifica quese haya seleccionado una Ubicacion
	if (frm_modificarPresupuesto.cmb_ubicacion.value==""&&band==1){
		alert ("Seleccionar una Ubicación");
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



/***************************************************************************************************************************************************************/
/*************************************************VALIDAR FECHAS EN FRM_REGISTRARPRESUPUESTO***********************************************************/
/***************************************************************************************************************************************************************/

/*//Función que permite calcular los dias laborales en un periodo de fechas seleccionadas
function calcularDiasLaborales(){
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
	
	//Verificar que el año de Fin sea mayor al de Inicio
	if(fechaIni<fechaFin){

		//Declaramos las variables necesarias para el script
		var diasSemanaEntrada;
		var diasSemanaSalida;
		var diasNoLaborados;
		var totalDiasMes;
		var diasLaborales;
		var diasLaborados;
		var e;
		var f;
		
	
		//Obtiene el numero seleccionado de la semana en formato numero; por ejemplo sabado = 6
		diaSemanaEntrada= fechaIni.getDay();
		
		//Si diaSemana es igual a cero quiere decir que el dia seleccionado es domingo 
		if(diaSemanaEntrada == 0)
			//Le damos el valor a diaSemana de 7 porque el getDay considera al domingo como 0
			diaSemanaEntrada = 7;
			
		//Se resta 8-diaSemana para considerar el dia actual
		diaSemanaEntrada = 8 - diaSemanaEntrada;
		
		//Si la resta de 8-diaSemana es mayor o igual a 1 entonces se toma el valor de 1 para considerar que hay 1 dia que no se labora
		if (diaSemanaEntrada >= 1) 
			diasNoLaborados = 1;
		else diasNoLaborados = 0;
						
		
		// es considerado como el dia de salida o el segundo parametro de la fecha y se obtiene el dia de la semana en formato numero
		diaSemanaSalida = fechaFin.getDay()
		//Si diaSemanaSalida es igual a cero quiere decir que se selecciono el domingo por lo tanto se iguala a 7
		if(diaSemanaSalida == 0)
			diaSemanaSalida = 7;
		
		//Genera el numero de dias segun el parametro seleccionado
		totalDiasMes = Math.round(((fechaFin - fechaIni) / 86400000) + 1);
	
		//Calcula los dias laborales c=dias totales del mes, a= dias laborales y b sera igual a 0 o 7 segun corresponda
		diasLaborales = totalDiasMes - diaSemanaEntrada - diaSemanaSalida;
		
		//Si entonces se dividira el total entre 7 que son los dias totales y se multiplicara por uno que es el dia no laboral
		if(diasLaborales > 1){
			e = diasLaborales / 7;
			f = e * 1;
		}
		else{
			e = 0;
			f = 0;
		}
	
		//si d es -7 quiere decir que existe un domingo
		if(diasLaborados == -7){
			//z tomara el valor de 0
			diasNoLaborados = 0;
			//si b==6 existe un sabado por lo tanto z debe valer 0 para no afectar la resta de los dias
			if(diaSemanaSalida == 6)
				diasNoLaborados = 0;
			//Si b==7 quiere decir que existe un domingo entonces z tomara el valor de 1
			if(diaSemanaSalida == 7)
				diasNoLaborados = 1;
		}
		
		//Calculamos el total de los dias laborales y lo asignamos a la caja de texto
		var totalDiasLaborales = totalDiasMes-f-diasNoLaborados;
		document.getElementById("txt_diasLaborales").value=totalDiasLaborales;
	}
}*/

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
	 	verificarRangoValido(document.frm_registrarPresupuesto.txt_fechaIni.value,document.frm_registrarPresupuesto.txt_fechaFin.value,document.frm_registrarPresupuesto.hdn_claveDefinida.value,document.frm_registrarPresupuesto.txt_ubicacion.value);
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

//Funcion que permtie enviar a la pagina correspondiente de acuerdo al reporte de Laboratorio consultado
function selTipoReporteLab(){
	var combo = document.getElementById("cmb_tipoReporteLab").value;
	if(combo=='AGREGADOS'){
		location.href = 'frm_reporteAgregados.php';	
	}
	if(combo=='RENDIMIENTO'){
		location.href = 'frm_reporteRendimiento.php';	
	}
	if(combo=='RESISTENCIA'){
		location.href = 'frm_consultarLaboratorio.php';	
	}
}



/************************************************************************************************************************************************************************************/
/*********************************************************************AGREGAR MATERIAL***********************************************************************************************/
/************************************************************************************************************************************************************************************/
/*Esta función pregunta al usuario si el material que va a ser agregado debe tener como existencia solo 1 */
function definirTipoMat(){
	var respuesta = confirm("¿El Material que va a ingresar debe tener de existencia solo 1?");
	if(respuesta){
		//Colocar uno a los campos de Cantidad, Nivel Mínimo, Nivel Máximo y Punto de Reorden
		document.getElementById("txt_cantidad").value = 1; document.getElementById("txt_cantidad").readOnly = true;
		document.getElementById("txt_nivelMinimo").value = 1; document.getElementById("txt_nivelMinimo").readOnly = true;
		document.getElementById("txt_nivelMaximo").value = 1; document.getElementById("txt_nivelMaximo").readOnly = true;
		document.getElementById("txt_puntoReorden").value = 1; document.getElementById("txt_puntoReorden").readOnly = true;
		//Colocar el valor de 'si' a la variable de hdn_matEspecial para dar otra validacion a dicho material.
		document.getElementById("hdn_matEspecial").value = "si";
	}
}


/*Esta funcion indica que valores deben ser considerados para los movimientos que seran registrados en los materiales*/
function definirNivelesMovimiento(comboRelevancia){
	//Si la opcion seleccionada es STOCK, preguntar si el material tendra existencia de 1
	if(comboRelevancia.value=="STOCK"){
		definirTipoMat();
	}//Si se selecciona cualquiera de las otras dos opciones, colocar valor 0 en los campos de nivel minimo, maximo y punto de reorden
	else if(comboRelevancia.value!=""){
		//Activar y vaciar el campo de Cantidad en caso de que este ocupado
		document.getElementById("txt_cantidad").value = ""; document.getElementById("txt_cantidad").readOnly = false;
		//Colocar valor 0 a los campos y dejarlos como readonly
		document.getElementById("txt_nivelMinimo").value = 0; document.getElementById("txt_nivelMinimo").readOnly = true;
		document.getElementById("txt_nivelMaximo").value = 0; document.getElementById("txt_nivelMaximo").readOnly = true;
		document.getElementById("txt_puntoReorden").value = 0; document.getElementById("txt_puntoReorden").readOnly = true;		
	}//Si se selecciona la opcion vacia, vaciar y activart los campos
	else if(comboRelevancia.value==""){		
		document.getElementById("txt_cantidad").value = ""; document.getElementById("txt_cantidad").readOnly = false;		
		document.getElementById("txt_nivelMinimo").value = ""; document.getElementById("txt_nivelMinimo").readOnly = false;
		document.getElementById("txt_nivelMaximo").value = ""; document.getElementById("txt_nivelMaximo").readOnly = false;
		document.getElementById("txt_puntoReorden").value = ""; document.getElementById("txt_puntoReorden").readOnly = false;		
	}
}//Cierre de la funcion definirNivelesMovimiento(comboRelevancia)


/*Validar los datos del formulario Agregar Material que no esten vacios y que los datos numericos sean validos */
function valFormAgregarMaterial(frm_agregarMaterial){
	
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var res = 1;				
	
	//Primero se debe validar que los campos del formulario de Agregar Material no se encuentren vacíos
	var cond = verContFormAgregarMaterial(frm_agregarMaterial);	
	
	
	//Si todos los campos fueron proporcionados, proceder a validar su contenido
	if(cond){
		//Si se trata de un material de STOCK y NO es un Material Especial validar que los datos de Nivel Mínimo, Nivel Máximo y Punto de Reorden sean correctos
		if(frm_agregarMaterial.cmb_relevancia.value=="STOCK" && frm_agregarMaterial.hdn_matEspecial.value=="no"){									


			//Verificar que los datos numericos del formulario sean numeros: Cantidad, Nivel Mínimo, Nivel Máximo, Punto de Reorden, Costo Unitario y el Factor de Conversión
			//if(validarEntero(frm_agregarMaterial.txt_cantidad.value,"La Cantidad del Material")){
				if(validarEnteroConCero(frm_agregarMaterial.txt_nivelMinimo.value)){
					if(validarEntero(frm_agregarMaterial.txt_nivelMaximo.value,"El Nivel Máximo del Material")){
						if(validarEnteroConCero(frm_agregarMaterial.txt_puntoReorden.value)){
						}else{ res = 0; }
					}else{ res = 0; }
				}else{ res = 0; }
			//}else{ res = 0;	}
		}//Cierre if(frm_agregarMaterial.cmb_relevancia.value=="STOCK")
	
				
		if(validarEntero(frm_agregarMaterial.txt_costoUnidad.value.replace(/,/g,''),"El Costo Unitario del Material")){
			if(!validarEntero(frm_agregarMaterial.txt_factor.value,"El Factor de Conversión del Material")){
				res = 0;
			}
		}else{ res = 0; }
		
		if(validarEntero(frm_agregarMaterial.txt_cantidad.value.replace(/,/g,''),"La Cantidad del Material")){

		}else{ res = 0; }

		
	}else{res = 0; }//Cierre else if(cond)



	//Si se trata de un material de STOCK y NO es un Material Especial validar que la congruncia entre el Nivel Mínimo, Nivel Máximo y Punto de Reorden sea correcta
	if(frm_agregarMaterial.cmb_relevancia.value=="STOCK" && frm_agregarMaterial.hdn_matEspecial.value=="no" && res==1){
		//Obtener los datos numericos de la Cantidad, Nivel Minimo, Nivel Maximo y Punto de Reorden
		var minimo = parseInt(frm_agregarMaterial.txt_nivelMinimo.value);
		var maximo = parseInt(frm_agregarMaterial.txt_nivelMaximo.value);
		var reorden = parseInt(frm_agregarMaterial.txt_puntoReorden.value);	
	
		//Validar que el Punto de Reorden no sea igual o menor que el Nivel Mínimo
		if(res==1){
			if(minimo>reorden){
				alert("El Nivel Mínimo no Puede Ser Mayor al Punto de Reorden");
				res = 0;
			}			
		}	
	
		//Validar que el Nivel Máximo no sea igual o menor que el punto de reorden y que no sea igual o menor que el Nivel Mínimo
		if(res==1){
			if(reorden==maximo || reorden>maximo){
				alert("El Punto de Reorden NO Puede Ser Igual o Mayor que el Nivel Máximo");
				res = 0;
			}
		}						
	}//Cierre if(frm_agregarMaterial.hdn_matEspecial.value=="no")
	

				
	//Validar que cuando el factor de conversion sea 1, la unidad de medida y la unidad de despacho tienen que ser las mismas
	if(res==1){
		if(frm_agregarMaterial.txt_factor.value==1){
			var unidad_medida;
			if(frm_agregarMaterial.cmb_unidadMedida.disabled==false)
				unidad_medida = frm_agregarMaterial.cmb_unidadMedida.value;
			else
				unidad_medida = frm_agregarMaterial.hdn_unidadMedida.value.toUpperCase();
				
			var unidad_despacho = frm_agregarMaterial.txt_unidadDespacho.value.toUpperCase();
			
			if(unidad_medida!=unidad_despacho){
				alert("Cuando el Factor de Conversión es Igual a 1,\nla Unidad de Medida y la Unidad de Despacho Deben Ser Iguales ");
				res = 0;
			}
		}			
	}		
	

	
	//Verificar que la Clave ingresada no este repetida
	if(document.getElementById("hdn_claveValida").value!="si" && res==1){
		alert("Verificar la Clave Proporcionada para el Material");
		res = 0;
	}
	
	
	//Verificar que la imagen proporcionada tenga el formato y tamaño soportado por el Sistema
	if(document.getElementById("hdn_imgValida").value!="si" && res==1){
		alert("Verificar el Formato y Tamaño de la de la Imagen Proporcionada\nTamaño de la Imagen Seleccionada: "+parseInt(document.getElementById("hdn_tamImg").value/1024)+" Kb");
		res = 0;
	}
	

	
	//Regresar el resultado final de la validacion
	if(res==1)		
		return true;	
	else
		return false;	
	
}//Cierre de la funcion valFormAgregarMaterial(frm_agregarMaterial)


/* Esta función se encarga de verificar que todos los datos obligatorios del formulario de Agregar Material no esten vacíos*/
function verContFormAgregarMaterial(frm_agregarMaterial){		
	//Si el valor se mantiene en 1, entonces todos los campos fueron proporcionados por el usuario
	var band = 1;
	
	//Verificar primero que la Clave no este vacía
	if(frm_agregarMaterial.txt_clave.value==""){
		band = 0;		
		alert ("Introducir una Clave para el Material");
	}
		
	//Luego verificar que el Nombre no este vacío
	if(frm_agregarMaterial.txt_nombre.value=="" && band==1){
		band = 0;
		alert ("Introducir un Nombre para el Material");
	}
	
	if (frm_agregarMaterial.cmb_relevancia.value=="" && band==1){
		band = 0;	
		alert ("Seleccionar la Relevancia del Material");
	}
	
	//Verificar que la Cantidad no este vacía
	if(frm_agregarMaterial.txt_cantidad.value=="" && band==1){
		band = 0;
		alert ("Introducir una Cantidad de Inicio para el Material");
	}

	//Verificar que el Nivel Mínimo no este vacío
	if(frm_agregarMaterial.txt_nivelMinimo.value=="" && band==1){
		band = 0;	
		alert ("Introducir el Nivel Mínimo del Material");					
	}
	
	//Verificar que el Nivel Máximo no este vacío
	if(frm_agregarMaterial.txt_nivelMaximo.value=="" && band==1){
		band = 0;
		alert ("Introducir el Nivel Máximo del Material");
	}

	//Verificar que el Punto de Reorden no este vacío
	if(frm_agregarMaterial.txt_puntoReorden.value=="" && band==1){
		band = 0;
		alert ("Introducir el Punto de Reorden del Material");	
	}

	//Verificar que la Línea del Artículo no este vacía
	if(frm_agregarMaterial.cmb_lineaArticulo.value==""  && frm_agregarMaterial.hdn_lineaArticulo.value=="" && band==1){		
		band = 0;
		alert ("Introducir la Línea del Material");
	}

	//Verificar que la Unidad de Medida no este vacía
	if(frm_agregarMaterial.cmb_unidadMedida.value=="" && frm_agregarMaterial.hdn_unidadMedida.value=="" && band==1){
		band = 0;			
		alert ("Introducir una Unidad de Medida para el Material");
	}
	
	//Verificar que haya sido seleccionado un Proveedor
	if(frm_agregarMaterial.cmb_proveedor.value=="" && band==1){
		band = 0;	
		alert ("Seleccionar el Proveedor del Material");
	}	

	//Verificar que la Ubicacion no este vacío
	if(frm_agregarMaterial.txt_ubicacion.value=="" && band==1){
		band = 0;	
		alert ("Introducir la Ubicación del Material");
	}

	//Verificar que el Factor de Conversión no este vacía
	if(frm_agregarMaterial.txt_factor.value=="" && band==1){
		band = 0;	
		alert ("Introducir el Factor de Conversión del Material");
	}

	//Verificar que la Unidad de Despacho no este vacío
	if(frm_agregarMaterial.txt_unidadDespacho.value=="" && band==1){
		band = 0;	
		alert ("Introducir la Unidad de Despacho del Material");
	}
	
	//Verificar que el Costo de la Unidad no este vacío
	if(frm_agregarMaterial.txt_costoUnidad.value=="" && band==1){
		band = 0;
		alert ("Introducir el Costo Unitario del Material");	
	}
	

	if(band==1)
		return true;
	else
		return false;				
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
	setTimeout("if(tam>0&&tam<10240000){ document.getElementById('"+bandera+"').value='si'; return true}; else {alert('Introducir una Imágen Válida'); document.getElementById('"+bandera+"').value='no'; return false;}",900);	
}


/******************Funciones para pedir datos, activar y desactivar elementos en el formulario de Agregar Material*****************************/
function obtenerLineaArticulo(){		
	var linea = prompt("¿Nombre de la Nueva Línea para el Material?","Nombre de la Línea...");	
	if(linea!=null && linea!="Nombre de la Línea..." && linea!=""){
		//Asignar el valor obtenido a la caja de texto que lo mostrara
		document.getElementById("txt_lineaArticulo").value = linea;
		//Deshabilitar el ComboBox y el CheckBox para que el usuario no los pueda modificar 			
		document.getElementById("cmb_lineaArticulo").disabled = true;
		document.getElementById("ckb_lineaArticulo").disabled = true;						
		//Asignar el valor de la línea obtenida al elemento Hidden para enviar el nuevo dato a la BD			
		document.getElementById("hdn_lineaArticulo").value = linea;
	}
	else
		document.getElementById("ckb_lineaArticulo").checked = false;
}	
function obtenerUnidadMedia(){
	var unidad = prompt("¿Cuál es la Nueva Unidad de Medida?","Unidad de Medida...");
	if(unidad!=null && unidad!="Unidad de Medida..." && unidad!=""){
		//Asignar el valor obtenido a la caja de texto que lo mostrara
		document.getElementById("txt_unidadMedida").value = unidad;
		//Deshabilitar el ComboBox y el CheckBox para que el usuario no lso pueda modificar 			
		document.getElementById("cmb_unidadMedida").disabled = true;
		document.getElementById("ckb_unidadMedida").disabled = true;						
		//Asignar el valor de la unidad obtenida al elemento Hidden para enviar el nuevo dato a la BD			
		document.getElementById("hdn_unidadMedida").value = unidad;
	}
	else
		document.getElementById("ckb_unidadMedida").checked = false;
}

function hablitarElementos(){
	//Cuando el usuario de clic en el boton Limpiar se activarán los ComboBox y los CheckBox de Línea del Artículo, Unidad de Medida y Grupo
	document.getElementById("cmb_lineaArticulo").disabled = false;
	document.getElementById("ckb_lineaArticulo").disabled = false;
	document.getElementById("cmb_unidadMedida").disabled = false;
	document.getElementById("ckb_unidadMedida").disabled = false;
	//document.getElementById("cmb_grupo").disabled = false;
	//document.getElementById("ckb_grupo").disabled = false;
	document.getElementById("error").style.visibility = "hidden";
	
	//Habilitar los elementos ReadOnly en caso de que tengan esa propiedad asignada
	document.getElementById("txt_cantidad").readOnly = false;
	document.getElementById("txt_nivelMinimo").readOnly = false;
	document.getElementById("txt_nivelMaximo").readOnly = false;
	document.getElementById("txt_puntoReorden").readOnly = false;
	
	//Colocar el valor de 'si' a la variable de hdn_matEspecial para dar otra validacion a dicho material.
	document.getElementById("hdn_matEspecial").value = "no";
}		
/************************************************************************************************************************************************************************************/
/******************************************************************FIN DE AGREGAR  MATERIAL*****************************************************************************************/
/************************************************************************************************************************************************************************************/



/**************************************************************************************************************************************************************************************/
/*********************************************************************INICIO ELIMINAR MATERIAL*****************************************************************************************/
/***********************************************************************************************************************************************************************************/
/*Validar que el formulario de Seleccionar Material a Eliminar no este vacío*/
function valFormEliminar(frm_eliminar){
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var res = 1;		
	
	//Validar que los campos no se encuentren vacíos
	var cond = verContFormEliminar(frm_eliminar);
	if(cond){
		//Verificar que la Existencia sea diferente de 0
		if(frm_eliminar.hdn_existencia.value!=0){
			alert ("Para Eliminar el Material su Existencia Debe Ser 0");
			res = 0;
		}
	}
	else{
		res = 0;
	}
	
	if(res==1)
		return true;
	else
		return false;
}


/*Esta función verifica que haya sido seleccionada una Categoría y un Material en el formulario de Seleccionar Material a Eliminar*/
function verContFormEliminar(frm_eliminar){
	//Si el valor se mantiene en 1, entonces todos los campos fuero proporcionados por el usuario
	var band = 1;
	
	//Verificar primero que la Clave no este vacia
	if(frm_eliminar.hdn_clave.value==""){
		band = 0;		
		alert ("Seleccionar una Categoría y Después un Material para Mostrar su Existencia");
	}	
	
	//Emitir el resultado en base al análisis realizado
	if(band==1)
		return true;
	else
		return false;
}


/*Esta función verifica que el formulario de Buscar Material a Eliminar no este vacío*/
function valFormBuscar(frm_buscar){
	//Si el valor se mantiene en 1, entonces todos los campos fuero proporcionados por el usuario
	var band = 1;
	
	if(frm_buscar.hdn_param.value==""){
		band = 0;
		alert ("Seleccionar un Parámetro de Búsqueda");
	}
	else{
		if(frm_buscar.cmb_datoBuscar.value==""){
			band = 0;
			alert ("Seleccionar Dato Mediante el Cual se Realizará la Búsqueda");
		}
	}
	
	//Emitir el resultado en base al análisis realizado
	if(band==1)
		return true;
	else
		return false;
		
}


/*Esta función verifica que sea seleccionado un material en la tabla de resultados de busqueda de materiales a eliminar y verifica si la existencia del material seleccionado es igual a 0*/
function valFormEliminar2(frm_eliminar2){
	//Si el valor de la variable "res" se mantiene en 0, entonces el formulario no paso el proceso de validacion
	var res = 0;
	var pos;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_eliminar2.rdb_clave.checked){
		//Separar la existencia de la clave del material para verificar si es diferente de 0
		var clave = frm_eliminar2.rdb_clave.value;
		var partes = clave.split("-");
		if(parseInt(partes[1])!=0){
			alert ("Para Eliminar el Material su Existencia Debe Ser 0");
			res = 0;
		}
		else{
			res = 1;
			//Reescribir la clave original en el RadioButton
			frm_eliminar2.rdb_clave.value = partes[0];
		}
	}
	else{
		//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
		for(i=0;i<frm_eliminar2.rdb_clave.length;i++){
			if(frm_eliminar2.rdb_clave[i].checked){
				res = 1;
				pos = i;
			}
		}
	
		
		if(res==0)
			alert("Seleccionar un Material para Eliminar");
		else{
			//Separar la existencia de la clave del material para verificar si es diferente de 0
			var clave = frm_eliminar2.rdb_clave[pos].value;
			var partes = clave.split("-");
			if(parseInt(partes[1])!=0){
				alert ("Para Eliminar el Material su Existencia Debe Ser 0");
				res = 0;
			}
			else{
				res = 1;
				//Reescribir la clave original en el RadioButton
				frm_eliminar2.rdb_clave[pos].value = partes[0];
			}
		}
		
		
	}
	
	if(res==1)
		return true;
	else
		return false;
}

function valFormEliminarXClave(frm_eliminarXClave){
	//Si el valor permanece en 1, la validacion se paso correctamente
	var res=1;
	if(frm_eliminarXClave.txt_clave.value==""){
		res = 0;
		alert ("Ingresar una Clave de Material");
	}	
	
	//Verificar si existe el material y solicitar al usuario autorizacion para borrarlo
	if(frm_eliminarXClave.hdn_nomMaterial.value!=""){
		if(!confirm("Esta seguro que Desea Eliminar el Material "+frm_eliminarXClave.hdn_nomMaterial.value)){
			res=0;	
		}
		
	}
	
	//Vaciar la caja de texto oculta para que no se muestre el mensaje de Notificacion para el usuario 2 veces
	frm_eliminarXClave.hdn_nomMaterial.value = "";
	
	if(res==1)
		return true;
	else
		return false;
}

/**************************************************************************************************************************************************************************************/
/*********************************************************************FIN ELIMINAR MATERIAL*****************************************************************************************/
/***********************************************************************************************************************************************************************************/


/*************************************************************************************************************************************************************************************/
/********************************************************************** INICIO MODIFICAR MATERIAL***************************************************************************************/
/************************************************************************************************************************************************************************************/
/*Esta función verifica que sea seleccionada una categoría y después un material para ser modificado en el formulario de Modificar Material por Artículo*/
function valFormElegirParams(frm_elegirParams){
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var band = 1;	
	//Validar que una categoría haya sido seleccionda
	if(frm_elegirParams.hdn_categoria.value==""){
		band = 0;
		alert ("Seleccionar una Categoría");
	}
	
	else{
		//Validar que un material haya sido seleccionado
		if(frm_elegirParams.cmb_material.value==""){
			band = 0;
			alert ("Seleccionar un Material");
		}		
	}
	
	if(band==1)
		return true;
	else
		return false;
}

	
/*Esta funcion indica que valores deben ser considerados para los movimientos que seran registrados en los materiales*/
function definirNivelesMovModificar(comboRelevancia){
	//Si se selecciona STOCK, Activar y vacias las cajas de texto de Punto de Reorden, Nivel Maximo, Nivel Minimo
	if(comboRelevancia.value=="STOCK"){
		document.getElementById("txt_nivelMinimo").value = ""; document.getElementById("txt_nivelMinimo").readOnly = false;
		document.getElementById("txt_nivelMaximo").value = ""; document.getElementById("txt_nivelMaximo").readOnly = false;
		document.getElementById("txt_puntoReorden").value = ""; document.getElementById("txt_puntoReorden").readOnly = false;
	}//Si se selecciona la opcion de Consignacion o Lento Movimiento, colocar 0 a las cajas de texto y colocarlas como ReadOnly
	else if(comboRelevancia.value=="CONSIGNACION" || comboRelevancia.value=="LENTO MOVIMIENTO"){		
		//Colocar valor 0 a los campos y dejarlos como readonly
		document.getElementById("txt_nivelMinimo").value = 0; document.getElementById("txt_nivelMinimo").readOnly = true;
		document.getElementById("txt_nivelMaximo").value = 0; document.getElementById("txt_nivelMaximo").readOnly = true;
		document.getElementById("txt_puntoReorden").value = 0; document.getElementById("txt_puntoReorden").readOnly = true;		
	}//Si se selecciona la opcion vacia, vaciar y activart los campos
	else if(comboRelevancia.value==""){		
		document.getElementById("txt_nivelMinimo").value = ""; document.getElementById("txt_nivelMinimo").readOnly = false;
		document.getElementById("txt_nivelMaximo").value = ""; document.getElementById("txt_nivelMaximo").readOnly = false;
		document.getElementById("txt_puntoReorden").value = ""; document.getElementById("txt_puntoReorden").readOnly = false;		
	}
}//Cierre de la funcion definirNivelesMovModificar(comboRelevancia)


/*Este función valida que en el formulario de Modificar Material no se encuentren vacíos los campos obligatorios
  y verifica que los datos numericos contengan numeros validos y mayores a 0 */
function valFormModificarMaterial(frm_modificarMaterial){
	
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var res = 1;		
	
	//Primero validar que los campos no se encuentren vacios
	var cond = verContFormModificarMaterial(frm_modificarMaterial);
		
	
		
	//Sí todos los materiales fueron proporcionados proceder a validar su contenido
	if(cond){
		if(frm_modificarMaterial.cmb_relevancia.value=="STOCK"){
			//Verificar que los datos numericos del formulario sean numeros: Cantidad, Nivel Mínimo, Nivel Máximo, Punto de Reorden, Costo Unitario y el Factor de Conversión
			if(validarEntero(frm_modificarMaterial.txt_cantidad.value,"La Cantidad del Material")){
				if(validarEnteroConCero(frm_modificarMaterial.txt_nivelMinimo.value)){
					if(validarEntero(frm_modificarMaterial.txt_nivelMaximo.value,"El Nivel Máximo del Material")){
						if(validarEnteroConCero(frm_modificarMaterial.txt_puntoReorden.value)){	
						}else{ res = 0; }
					}else{ res = 0; }
				}else{ res = 0; }
			}else{ res = 0;	}		
		}//Cierre if(frm_modificarMaterial.cmb_relevancia.value=="STOCK")
	
	
		if(validarEntero(frm_modificarMaterial.txt_costoUnidad.value.replace(/,/g,''),"El Costo Unitario del Material")){
			if(!validarEntero(frm_modificarMaterial.txt_factor.value,"El Factor de Conversión del Material"))
				res = 0;
		}else{ res = 0; }
	
	}else{ res=0; }//Cierre else if(cond)
	
	
	
	//Si el tipo de Relevancia es igual a STOCK, validar los niveles Maximo, Minimo y Punto de Reorden
	if(frm_modificarMaterial.cmb_relevancia.value=="STOCK"){
		//Obtener los datos numericos de Nivel Minimo, Nivel Maximo y Punto de Reorden
		var minimo = parseInt(frm_modificarMaterial.txt_nivelMinimo.value);
		var maximo = parseInt(frm_modificarMaterial.txt_nivelMaximo.value);
		var reorden = parseInt(frm_modificarMaterial.txt_puntoReorden.value);	
		
		//Validar que el Punto de Reorden no sea igual o menor que el Nivel Mínimo
		if(res==1){
			if(minimo>reorden){
				alert("El Nivel Mínimo no Puede Ser Mayor al Punto de Reorden");
				res = 0;
			}			
		}	
		
		//Validar que el Nivel Máximo no sea igual o menor que el punto de reorden y que no sea igual o menor que el Nivel Mínimo
		if(res==1){
			if(reorden==maximo || reorden>maximo){
				alert("El Punto de Reorden NO Puede Ser Igual o Mayor que el Nivel Máximo");
				res = 0;
			}			
		}
	}

	//Validar que cuando el factor de conversion sea 1, la unidad de medida y la unidad de despacho tienen que ser las mismas
	if(res==1){
		if(frm_modificarMaterial.txt_factor.value==1){
			var unidad_medida;
			if(frm_modificarMaterial.cmb_unidadMedida.disabled==false)
				unidad_medida = frm_modificarMaterial.cmb_unidadMedida.value;
			else
				unidad_medida = frm_modificarMaterial.txt_unidadMedida.value.toUpperCase();
				
			if(unidad_medida!=frm_modificarMaterial.txt_unidadDespacho.value.toUpperCase()){
				alert("Cuando el Factor de Conversión es Igual a 1,\nla Unidad de Medida y la Unidad de Despacho Deben Ser Iguales");
				res = 0;
			}
		}			
	}

	//Verificar que el tamaño de la imagen proporcionada sea el soportado por el Sistema
	if(res==1){
		//Verificar el tamaño de la imagen
		if(document.getElementById("hdn_imgValida").value!="si"){
			alert("Verificar el Formato y Tamaño de la de la Imagen Proporcionada\nTamaño de la Imagen Seleccionada: "+parseInt(document.getElementById("hdn_tamImg").value/1024)+" Kb");
			res = 0;
		}
	}
		
	
	if(res==1)
		return true;	
	else
		return false;	
}


/*Esta función verifica que los campos obligatorios del formulario de Modificar Material no esten vacíos*/
function verContFormModificarMaterial(frm_modificarMaterial){
	//Si el valor se mantiene en 1, entonces todos los campos fuero proporcionados por el usuario
	var band = 1;
	
	//Verificar primero que la Clave no este vacía
	if(frm_modificarMaterial.txt_clave.value==""){
		band = 0;		
		alert ("Introducir una Clave del Material");
	}
	
	//Verificar que la relevancia sea Proporcionada
	if (frm_modificarMaterial.cmb_relevancia.value=="" && band==1){
		band = 0;	
		alert ("Seleccionar la Relevancia del Material");
	}
			
	//Luego verificar que el Nombre no este vacío
	if(frm_modificarMaterial.txt_nombre.value=="" && band==1){
		band = 0;
		alert ("Introducir un Nombre del Material");
	}
	
	//Verificar que el Nivel Mínimo no este vacío
	if(frm_modificarMaterial.txt_nivelMinimo.value=="" && band==1){
		band = 0;	
		alert ("Introducir un Nivel Mínimo de Material");
	}

	//Verificar que el Nivel Máximo no este vacío
	if(frm_modificarMaterial.txt_nivelMaximo.value=="" && band==1){
		band = 0;	
		alert ("Introducir un Nivel Máximo de Material");
	}

	//Verificar que el Punto de Reorden no este vacío
	if(frm_modificarMaterial.txt_puntoReorden.value=="" && band==1){
		band = 0;	
		alert ("Introducir un  Punto de Reorden de Material");
	}	

	//Verificar que el ComboBox de la Línea del Artículo no este vacía
	if(frm_modificarMaterial.cmb_lineaArticulo.disabled==false && frm_modificarMaterial.cmb_lineaArticulo.value=="" && band==1){
		band = 0;
		alert ("Seleccionar una Línea del Material");
	}

	//Verificar que la Caja de Texto de la Línea del Artículo no este vacía
	if(frm_modificarMaterial.cmb_lineaArticulo.disabled==true && frm_modificarMaterial.txt_lineaArticulo.value=="" && band==1){
		band = 0;
		alert ("Introducir una Línea del Material en la Cuadro de Texo");
	}																																														

	//Verificar que el ComboBox de la Unidad de Medida no este vacía
	if(frm_modificarMaterial.cmb_unidadMedida.disabled==false && frm_modificarMaterial.cmb_unidadMedida.value=="" && band==1){
		band = 0;
		alert ("Seleccionar una Unidad de Medida");
	}

	//Verificar que la Caja de Texto de la Unidad de Medida no este vacía
	if(frm_modificarMaterial.cmb_unidadMedida.disabled==true && frm_modificarMaterial.txt_unidadMedida.value=="" && band==1){
		band = 0;
		alert ("Introducir una Unidad de Medida en el Cuadro de Texto");
	}																																																																																																																																				

	//Verificar que el el campo de Proveedor no se encuentre vacío
	if(frm_modificarMaterial.txt_ubicacion.value=="" && band==1){
		band = 0;	
		alert ("Introducir la Ubicación del Material");
	}

	//Verificar que el el campo de Ubicación no se encuentre vacío
	if(frm_modificarMaterial.txt_factor.value=="" && band==1){
		band = 0;	
		alert ("Introducir el Factor de Conversión del Material");
	}
														
	//Verificar que el el campo del Factor de Conversión no se encuentre vacío
	if(frm_modificarMaterial.txt_unidadDespacho.value=="" && band==1){
		band = 0;	
		alert ("Introducir la Unidad de Despacho del Material");
	}

	//Verificar que el el campo de Unidad de Despacho no se encuentre vacío
	if(frm_modificarMaterial.cmb_proveedor.value=="" && band==1){
		band = 0;	
		alert ("Introducir el Nombre del Proveedor");
	}							
	
	//Verificar que el Costo de la Unidad no este vacío
	if(frm_modificarMaterial.txt_costoUnidad.value=="" && band==1){
		band = 0;
		alert ("Introducir el Costo Unitario del Material");	
	}
	
	if(band==1)
		return true;
	else
		return false;
}


/******************Funciones para activar y editar el contenido de los elementos en el formulario de Modificar Material*****************************/
function editarLinea(){	
	//Permitir editar el contenido de la caja de texto que contiene la Línea del Artículo
	document.getElementById("txt_lineaArticulo").disabled = false;
	//Deshabilitar el CheckBox que permite al usuario editar el campo que contiene el valor de la Línea del Artículo
	document.getElementById("ckb_editarLinea").disabled = true;	
	//Deshabilitar el ComboBox de la Línea del Artículo
	document.getElementById("cmb_lineaArticulo").disabled = true;
}
function editarUnidad(){
	//Permitir editar el contenido de la caja de texto que contiene la Unidad de Medida
	document.getElementById("txt_unidadMedida").disabled = false;
	//Deshabilitar el CheckBox que permite al usuario editar el campo que contiene el valor de la Unidad de Medida
	document.getElementById("ckb_editarUnidad").disabled = true;
	//Deshabilitar el ComboBox de la Unidad de Medida
	document.getElementById("cmb_unidadMedida").disabled = true;	
}
		
function deshabilitarElementos(){
	//Cuando el usuario de clic en el boton Limpiar se desactivarán los CheckBox que permiten editar los campos de Línea del Artículo, la Unidad de Medida y el Grupo
	document.getElementById("txt_lineaArticulo").disabled = true;
	document.getElementById("txt_unidadMedida").disabled = true;
	//document.getElementById("txt_grupo").disabled = true;
	//Reactivar los ComboBox
	document.getElementById("cmb_lineaArticulo").disabled = false;
	document.getElementById("cmb_unidadMedida").disabled = false;
	//document.getElementById("cmb_grupo").disabled = false;
	//Reactivar los CheckBox
	document.getElementById("ckb_editarLinea").disabled = false;	
	document.getElementById("ckb_editarUnidad").disabled = false;
	//document.getElementById("ckb_editarGrupo").disabled = false;
	
}		
/******************Funciones para activar y editar el contenido de los elementos en el formulario de Modificar Material*****************************/

function valFormModificarXClave(frm_modificarXClave){
	//Si la variable permanece en 1, no se activo ningun error
	var band=1;
	
	if(frm_modificarXClave.txt_claveMod.value==""){
		alert("Introducir una Clave de Materiales");
		band = 0;
	}
	
	if(band==1)
		return true;	
	else
		return false;
	
}

/*************************************************************************************************************************************************************************************/
/********************************************************************** FIN MODIFICAR MATERIAL***************************************************************************************/
/************************************************************************************************************************************************************************************/


/**************************************************************************************************************************************************************************************/
/********************************************************************INICIO  CONSULTAR MATERIAL*****************************************************************************************/
/*****************************************************************************************************************************************************************************************/
/*Esta función valida que sea seleccionada un Categoría y un Material en el formulario de Consultar Material por Artículo*/
function valFormConsultarMaterial(frm_consultarMaterial){
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var band = 1;		
	
	//Validar que una categoría haya sido seleccionda
	if(frm_consultarMaterial.hdn_categoria.value==""){
		band = 0;
		alert ("Seleccionar una Categoría");
	}
	
	else{
		//Validar que un material haya sido seleccionado
		if(frm_consultarMaterial.cmb_material.value==""){
			band = 0;
			alert ("Seleccionar un Material");
		}		
	}
	
	if(band==1)
		return true;
	else
		return false;
}


/*Esta función valida que sea seleccionada una Categoría en el formulario de Consultar Material por Categoría*/
function valFormConsultarCategoria(frm_consultarCategoria){
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var band = 1;
	
	if(frm_consultarCategoria.cmb_lineaArticulo.value==""){
		band = 0;
		alert ("Seleccionar una Categoría");
	}
	
	
	if(band==1)
		return true;
	else
		return false;
}


/*Esta función valida que sea seleccionado el parámetro de búsqueda y el dato por el cual se realizará la búsqueda*/
function valFormConsultarMixta(frm_consultarMixta){
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var band = 1;
	
	if(frm_consultarMixta.hdn_param.value==""){
		band = 0;
		alert ("Seleccionar un Parámetro");
	}
	else{
		if(frm_consultarMixta.cmb_param2.value==""){
			band = 0;
			alert ("Seleccionar una Opción");
		}
	}
	
	
	if(band==1)
		return true;
	else
		return false;
}

/*Esta función valida que sea seleccionada la clave del Material a buscar*/
function valFormConsultarClave(frm_consultarClave){
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var band = 1;
	
	if(frm_consultarClave.txt_clave.value==""){
		band = 0;
		alert ("Escribir una Clave");
	}
	
	if(band==1)
		return true;
	else
		return false;
}

/**************************************************************************************************************************************************************************************/
/********************************************************************FIN  CONSULTAR MATERIAL*****************************************************************************************/
/****************************************************************************************************************************************************************************************/


/***************************************************************************************************************************************/
/***************************************************CONSULTAR REGISTRO DE PRODUCCION****************************************************/
/***************************************************************************************************************************************/
//Funcion para Evaluar los datoas del formularo para consultar el registro de produccion
function valFormConsProMes(frm_consultarProduccion){
	//Si el valor permanece en 1, la validacion se paso correctamente
	var band=1;
	
	//Verificar que se haya seleccionado un elemento del combo box para realizar al consulta
	if(frm_consultarProduccion.cmb_periodo.value==""){
		band = 0;
		alert ("Seleccionar una Periodo");
	}
	
	if(band==1)
		return true;
	else
		return false;
}

/***************************************************************************************************************************************/
/**************************************************CONSULTAR COSTOS DE MANTENIMIENTO****************************************************/
/***************************************************************************************************************************************/
//Funcion para Evaluar los datoas del formularo para consultar el costo del mantenimiento de algun equipo
function valFormConsultarEq(frm_consultarMantenimiento){
	//Si el valor permanece en 1, la validacion se paso correctamente
	var band=1;
	
	
	//verificar que la fecha de inicio no sea mayor que la fecha fin
	if(!valFormFechasReq(frm_consultarMantenimiento) && band==1)
		band=0;
	
	//Verificar que se haya seleccionado un elemento del combo box para realizar al consulta
	if(frm_consultarMantenimiento.cmb_familia.value==""&&band==1){
		band = 0;
		alert ("Seleccionar una Familia");
	}
	
	//Verificar que se haya seleccionado un elemento del combo box para realizar al consulta
	if(frm_consultarMantenimiento.cmb_equipo.value==""&&band==1){
		band = 0;
		alert ("Seleccionar un Equipo");
	}

	if(band==1)
		return true;
	else
		return false;
}

/***********************************************************************************************************************************************************/
/***********************************************************************************************************************************************************/
/************************************************************CONSULTAR ASISTENCIAS DE PERSONAL**************************************************************/
/***********************************************************************************************************************************************************/
/***********************************************************************************************************************************************************/

//funcion para validar el formulario frm_reporteFecha cuando se selecciona la opcion por fecha
function valFormRptAsistenciaFecha(frm_reporteFecha){
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


/***************************************************************************************************************************************/
/******************************************************    REPORTE MENSUAL   ***********************************************************/
/***************************************************************************************************************************************/
/*Esta funcion valida que sea seleccionado un periodo para ser consultado*/
function valFormPeriodoRptMensual(frm_periodoRptMensual){
	var band = 1;
	
	if(frm_periodoRptMensual.cmb_periodo.value==""){
		alert("Seleccionar un Periodo para ser Consultado");
		band = 0;
	}
		
	if(band==1)
		return true;
	else
		return false;

}


/***************************************************************************************************************************************/
/**************************************************    REPORTE COMPARATIVO MENSUAL   ***************************************************/
/***************************************************************************************************************************************/
//Funcion para Evaluar los datoas del formularo para consultar reporte comparativo mensual
function valFormRepCompMens(frm_reporteComparativoMensual){
	//Si el valor permanece en 1, la validacion se paso correctamente
	var band=1;
	
	//Verificar que se haya seleccionado un elemento del combo box para realizar al consulta
	if(frm_reporteComparativoMensual.cmb_periodo.value==""&&band==1){
		band = 0;
		alert ("Seleccionar un Periodo");
	}

	if(band==1)
		return true;
	else
		return false;
}

//Funcion que permite validar que se haya ingresado el numero de empleados para las operaciones necesarias en el formulario del reporte comparativo mensual
function complementarReporteComparativo(){
	var noEmpleados;
	var numValido = true;
	do{
		
		noEmpleados = prompt('Ingrese Cantidad de Empleados','Cantidad de Empleados Involucrados en la Producción...' );
		
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




/***************************************************************************************************************************************/
/**************************************************    REPORTE COMPARATIVO MINA   ***************************************************/
/***************************************************************************************************************************************/
//Funcion para Evaluar los datoas del formularo para consultar reporte comparativo minas seccion consultar por fecha
function valFormGenRepoCompMinFech(frm_reporteFecha){
	//Si el valor permanece en 1, la validacion se paso correctamente
	var band=1;
	
	if(!validarFechas(frm_reporteFecha.txt_fechaIni.value,frm_reporteFecha.txt_fechaFin.value))
		band = 0;				

	//Verificar que se haya seleccionado un elemento del combo box para realizar al consulta
	if(frm_reporteFecha.cmb_ubicacion.value==""&&band==1){
		band = 0;
		alert ("Seleccionar una Ubicación");
	}

	if(band==1)
		return true;
	else
		return false;
}

//Funcion para Evaluar los datoas del formularo para consultar reporte comparativo minas seccion consultar por anio
function valFormGenRepoCompMinAnio(frm_reporteAnio){
	//Si el valor permanece en 1, la validacion se paso correctamente
	var band=1;
	
	//Verificar que se haya seleccionado un elemento del combo box para realizar al consulta
	if(frm_reporteAnio.cmb_ubicacion.value==""&&band==1){
		band = 0;
		alert ("Seleccionar una Ubicación");
	}

	//Verificar que se haya seleccionado un elemento del combo box para realizar al consulta
	if(frm_reporteAnio.cmb_anios.value==""&&band==1){
		band = 0;
		alert ("Seleccionar una Año");
	}

	if(band==1)
		return true;
	else
		return false;
}

/***************************************************************************************************************************************/
/*****************************************************************    REPORTE ANUAL  ***************************************************/
/***************************************************************************************************************************************/

//Funcion para Evaluar los datoas del formularo para consultar reporte ANUAL
function valFormSelAnioTrab(frm_reporteAnual){
	//Si el valor permanece en 1, la validacion se paso correctamente
	var band=1;
	
	//Verificar que se haya seleccionado un elemento del combo box para realizar al consulta
	if(frm_reporteAnual.cmb_anios.value==""&&band==1){
		band = 0;
		alert ("Seleccionar un Año");
	}

	if(band==1)
		return true;
	else
		return false;
}

/***************************************************************************************************************************************/
/********************************************************    BITACORA   ****************************************************************/
/***************************************************************************************************************************************/
//Funcion para Evaluar los datos del formularo para seleccionar el tipo de registro de la bitacora 
function valFormSeleccionarBit(frm_selRegistroBitacora){
	//Si el valor permanece en 1, la validacion se paso correctamente
	var band=1;
	
	//Verificar que se haya seleccionado un nombre
	if(frm_selRegistroBitacora.cmb_tipoBit.value==""&&band==1){
		band = 0;
		alert ("Seleccionar el Tipo de Registro");
	}

	//Verificar el valor del combo ya que segun su valor será a la pagina a la cual nos estará direccionando
	if(frm_selRegistroBitacora.cmb_tipoBit.value=='TRANSPORTE'){
		frm_selRegistroBitacora.action="frm_registroBitTransporte.php";	
	}
	
	//Verificar el valor del combo ya que segun su valor será a la pagina a la cual nos estará direccionando
	if(frm_selRegistroBitacora.cmb_tipoBit.value=='ZARPEO'){
		frm_selRegistroBitacora.action="frm_agregarRegistroBitacora.php";	
	}

	if(band==1)
		return true;
	else
		return false;
}


//Funcion para Evaluar los datos del formularo para registro de bitácora de transporte
function valFormRegBitTrans(frm_registroBitTransporte){
	//Si el valor permanece en 1, la validacion se paso correctamente
	var band=1;
	
	//Verificar que se haya seleccionado un nombre
	if(frm_registroBitTransporte.txt_nombre.value==""&&band==1){
		band = 0;
		alert ("Seleccionar un Nombre");
	}

	//Verificar que se haya ingresado una cantidad
	if(frm_registroBitTransporte.txt_cantidad.value==""&&band==1){
		band = 0;
		alert ("Ingresar una Cantidad");
	}	

	if(band==1){
		if(!validarEntero(frm_registroBitTransporte.txt_cantidad.value.replace(/,/g,''),"La Cantidad"))
			band = 0;		
	}

	//Verificar que se haya seleccionado una ubicacion
	if(frm_registroBitTransporte.cmb_ubicacion.value==""&&band==1){
		band = 0;
		alert ("Seleccionar una Ubicación");
	}	

	//Verificar que se haya seleccionado al cargo
	if(frm_registroBitTransporte.cmb_choferSup.value==""&&band==1){
		band = 0;
		alert ("Seleccionar el Cargo");
	}	

	if(band==1)
		return true;
	else
		return false;
}

/***************************************************************************************************************************************/
/**************************************************AGREGAR REGISTRO A LA BITACOTA*******************************************************/
/***************************************************************************************************************************************/
//Funcion para Evaluar los datos del formularo para registro de bitácora
function valFormAgregarRegistroBitacora(frm_agregarRegistroBitacora){
	//Si el valor permanece en 1, la validacion se paso correctamente
	var band=1;
	
	//Verificar que el ComboBox de ubicacion no este vacía
	if(frm_agregarRegistroBitacora.cmb_ubicacion.value=="" && band==1){
		band = 0;
		alert ("Seleccionar una Ubicación");
	}	

	//Verificar que se haya seleccionado un periodo
	if(frm_agregarRegistroBitacora.cmb_periodo.value==""&&band==1){
		band = 0;
		alert ("Seleccionar un Periodo");
	}
	
	if(band==1)
		return true;
	else
		return false;
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


/*************************************************CARGAR COMBO APARTIR DE UNO YA DECLARADO****************************************************/


/*Esta funcion se encarga de cargar los combos de Aplicacion e Integrantes de la Cuadrilla seleccionada*/
function cargarCombos(comboCuadrilla){
	
	//Esta funcion carga el Combo con los integrantes de la Cuadrilla, colocando el nombre del empleado en el texo visible y el puesto en la propiedad 'value' y 'title' de Combo
	//cargarComboConId(comboCuadrilla.value,'bd_gerencia','integrantes_cuadrilla','nom_trabajador','puesto','cuadrillas_id_cuadrillas','cmb_aplicadorLanzamiento','Trabajador','');
	
	cargarComboConId2(comboCuadrilla.value,'bd_gerencia','integrantes_cuadrilla','nom_trabajador','puesto','cuadrillas_id_cuadrillas','cmb_aplicadorLanzamiento','Trabajador','');
	
	var idCuadrilla = comboCuadrilla.value;
	var codigo = "cargarComboDeUnaCadena('"+idCuadrilla+"','bd_gerencia','cuadrillas','aplicacion','id_cuadrillas','cmb_aplicacionLanzamientos','Aplicación','');";
	//Esta funcion carga las Aplicaciones de la Cuadrilla seleccionada
	setTimeout(codigo,500);

}

/*Esta funcion se encarga de cargar los combos de Aplicacion e Integrantes de la Cuadrilla seleccionada*/
function cargarCombosAplic(cuadrilla,aplicacion,aplicador){
	//Esta funcion carga el Combo con los integrantes de la Cuadrilla
	cargarCombo(cuadrilla,'bd_gerencia','integrantes_cuadrilla','nom_trabajador','cuadrillas_id_cuadrillas','cmb_aplicadorLanzamiento','Trabajador',aplicador);
	var idCuadrilla = cuadrilla;
	var codigo = "cargarComboDeUnaCadena('"+idCuadrilla+"','bd_gerencia','cuadrillas','aplicacion','id_cuadrillas','cmb_aplicacionLanzamientos','Aplicación','"+aplicacion+"');";
	//Esta funcion carga las Aplicaciones de la Cuadrilla seleccionada
	setTimeout(codigo,500);
}

/***************************************************************************************************************************************/
/***************************************COMPLEMENTAR REGISTRO DE LOS LANZAMIENTOS EN LA BITACORA***************************************/
/***************************************************************************************************************************************/
//Funcion para Evaluar los datos del formularo para registro de bitácora de Lanzamientos
function valFormRegistroLanzamientoBitacora(frm_agregarLanzamientoBitacora){
	//Si el valor permanece en 1, la validacion se paso correctamente
	var band=1;
	
	//Verificar que se haya ingresado una cantidad
	if(frm_agregarLanzamientoBitacora.cmb_cuadrillas.value==""&&band==1){
		band = 0;
		alert ("Seleccionar la Cuadrilla");
	}	

	//Verificar que se haya seleccionado un nombre
	if(frm_agregarLanzamientoBitacora.cmb_aplicacionLanzamientos.value==""&&band==1){
		band = 0;
		alert ("Seleccionar la Aplicación");
	}	

	//Verificar que se haya ingresado una cantidad
	if(frm_agregarLanzamientoBitacora.txt_cantidad.value==""&&band==1){
		band = 0;
		alert ("Agregar la Cantidad");
	}
	
	if(band==1){
		if(!validarEntero(frm_agregarLanzamientoBitacora.txt_cantidad.value.replace(/,/g,''),"La Cantidad"))
			band = 0;		
	}
	
	if (!frm_agregarLanzamientoBitacora.ckb_nuevoSuplente.checked && frm_agregarLanzamientoBitacora.cmb_aplicadorLanzamiento.value==""  && band==1){
		alert("Seleccionar el Trabajador");
		band=0;
	}


	if(band==1)
		return true;
	else
		return false;
}


/*Esta funcion valida que la Fecha Seleccionada se encuentre dentro del Rango de Fechas del Periodo Seleccionado*/
function verificarFecha(txtFechaActual){
	
	//Obtener los valores de las cajas de texto ocultas que contienen las fechas 
	var fecha1 = document.getElementById("hdn_fechaIni").value;
	var fecha2 = document.getElementById("hdn_fechaFin").value;
	var fechaSeleccionada = txtFechaActual.value;
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia = fecha1.substr(0,2);
	var iniMes = fecha1.substr(3,2);
	var iniAnio = fecha1.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia = fecha2.substr(0,2);
	var finMes = fecha2.substr(3,2);
	var finAnio = fecha2.substr(6,4);		
	
	//Extraer los datos de la fecha que sera validada dentro del rango del Periodo seleccionado
	var actualDia = fechaSeleccionada.substr(0,2);
	var actualMes = fechaSeleccionada.substr(3,2);
	var actualAnio = fechaSeleccionada.substr(6,4);	
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni = iniMes + "/" + iniDia + "/" + iniAnio;
	var fechaFin = finMes + "/" + finDia + "/" + finAnio;
	var fechaActual = actualMes + "/" + actualDia + "/" + actualAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni = new Date(fechaIni);
	fechaFin = new Date(fechaFin);
	fechaActual = new Date(fechaActual);
	

	//Verificar que la fecha de inicio no sea mayor a la de cierre
	/*if(fechaActual<fechaIni || fechaActual>fechaFin){		
		alert ("La Fecha Seleccionada no esta Dentro del Periodo Actual \nPeriodo: "+document.getElementById("hdn_periodoActual").value );
		txtFechaActual.value = fecha1;
	}*/		
			
}//Cierre verificarFecha(fechaSeleccionada)


//Funcion que permite saber el valor de la etiqueta de cantidad si son metros2  o metros3
function verificarEtiqueta(aplicacion){
	 
	if(aplicacion!=""){				
		
		if(aplicacion=="INSTALACION MALLA"){ 
			document.getElementById("m2").style.visibility = "visible";
			document.getElementById("m3").style.visibility = "hidden";
		}
		else if(aplicacion!="INSTALACION MALLA"){ 
			document.getElementById("m3").style.visibility = "visible";	
			document.getElementById("m2").style.visibility = "hidden";
		}
	}
}

/*Funcion que verifica el filtro del empleado, trabaja en conjunto con la funcion valFormSeleccionarKardex1*/
function verificarFiltroEmpleado(checkbox,cajaFiltro){
	if(checkbox.checked){
		cajaFiltro.readOnly=false;
	}
	else{
		cajaFiltro.readOnly=true;
		cajaFiltro.value="";
	}
}

//Funcion que sirve para activar la caja de texto para agregar un suplente y deshabilida el combo box de aplicador
function activarCajaTexto(){
	//verificar si el check box esta activo
	if (document.getElementById("ckb_nuevoSuplente").checked){
		//Activar la caja de texto de suplente,poner deshabilitar el combo box y/o recetearlo en caso que tenga un valor ya seleccionado
		document.getElementById("txt_nomSuplente").readOnly=false;
		document.getElementById("cmb_aplicadorLanzamiento").disabled=true;
		document.getElementById("cmb_aplicadorLanzamiento").value="";
	}
	else{
		//Desactivar la caja de texto de suplente y/o recetearla en caso que tenga un valor ya ingresado, habilitar el combo box 
		document.getElementById("txt_nomSuplente").readOnly=true;
		document.getElementById("txt_nomSuplente").value="";
		document.getElementById("cmb_aplicadorLanzamiento").disabled=false;
	}
}

/***************************************************************************************************************************************/
/***************************************************MODIFICAR REGISTRO A LA BITACOTA********************************************************/
/***************************************************************************************************************************************/
//Funcion para Evaluar los datos del formularo para seleccionar el tipo de registro de la bitacora 
function valFormSeleccionarBitModificar(frm_selRegistroBitacoraMod){
	//Si el valor permanece en 1, la validacion se paso correctamente
	var band=1;
	
	//Verificar que se haya seleccionado un tipo de bitácora
	if(frm_selRegistroBitacoraMod.cmb_tipoBit.value==""&&band==1){
		band = 0;
		alert ("Seleccionar el Tipo de Registro");
	}

	//Verificar el valor del combo ya que segun su valor será a la pagina a la cual nos estará direccionando
	if(frm_selRegistroBitacoraMod.cmb_tipoBit.value=='TRANSPORTE'){
		frm_selRegistroBitacoraMod.action="frm_modificarRegistroBitacoraTransp.php";	
	}
	
	//Verificar el valor del combo ya que segun su valor será a la pagina a la cual nos estará direccionando
	if(frm_selRegistroBitacoraMod.cmb_tipoBit.value=='ZARPEO'){
		frm_selRegistroBitacoraMod.action="frm_modificarRegistroBitacora.php";	
	}

	if(band==1)
		return true;
	else
		return false;
}

//Funcion para Evaluar los datos del formularo para MODIFICAR de bitácora
function valFormModRegBitZarp(frm_modificarRegistroBitacora){
	//Si el valor permanece en 1, la validacion se paso correctamente
	var band=1;
	var etiqueta=0;
	
	//Verificar que el ComboBox de periodo no este vacío
	if(frm_modificarRegistroBitacora.cmb_ubicacion.value=="" && band==1){
		band = 0;
		alert ("Seleccionar una Ubicación");
	}
	
	//Verificar que el ComboBox de periodo no este vacío
	if(frm_modificarRegistroBitacora.cmb_cuadrilla.value==""&&band==1){
		band = 0;
		alert ("Seleccionar una Cuadrilla");
	}
	
	//Verificar que el ComboBox de periodo no este vacío
	if(frm_modificarRegistroBitacora.cmb_periodo.value==""&&band==1){
		band = 0;
		alert ("Seleccionar un Periodo");
	}
	
	if(band==1)
		return true;
	else
		return false;
}


/*Esta funcion se ecnarga de Validar el Formulario donde se selecciona el registro para borrar*/
function valFormEliminarReg(frm_modificarRegistroBitacora){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_modificarRegistroBitacora.rdb_idBitacora.length==undefined && !frm_modificarRegistroBitacora.rdb_idBitacora.checked){
		alert("Seleccionar el Registro a "+frm_modificarRegistroBitacora.hdn_accion.value);
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_modificarRegistroBitacora.rdb_idBitacora.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_modificarRegistroBitacora.rdb_idBitacora.length;i++){
			if(frm_modificarRegistroBitacora.rdb_idBitacora[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar el Registro a "+frm_modificarRegistroBitacora.hdn_accion.value);
	}
		
	if (res==1 && frm_modificarRegistroBitacora.hdn_accion.value=="Eliminar"){
		
		if (!confirm("¿Estas Seguro que Quieres Borrar el Registro?\nToda la información relacionada se Borrará")){
			res=0;
		}
	}
	
	if(res==1)
		return true;
	else
		return false;	
}

//Funcion para Evaluar los datos del formularo para modificacion de bitácora de Lanzamientos
function valFormModLanzamientoBitacora(frm_modificarLanzamientoBitacora){
	//Si el valor permanece en 1, la validacion se paso correctamente
	var band=1;

	//Verificar que se haya ingresado una cantidad
	if(frm_modificarLanzamientoBitacora.txt_cantidad.value==""&&band==1){
		band = 0;
		alert ("Agregar la Cantidad");
	}

	if(band==1){
		if(!validarEntero(frm_modificarLanzamientoBitacora.txt_cantidad.value.replace(/,/g,''),"La Cantidad"))
			band = 0;		
	}

	//Verificar que se haya seleccionado un nombre
	if(frm_modificarLanzamientoBitacora.cmb_aplicacionLanzamientos.value==""&&band==1){
		band = 0;
		alert ("Seleccionar la Aplicación");
	}	
	
	if (!frm_modificarLanzamientoBitacora.ckb_nuevoSuplente.checked && frm_modificarLanzamientoBitacora.cmb_aplicadorLanzamiento.value==""  && band==1){
		alert("Seleccionar el Aplicador/Suplente");
		band=0;
	}


	if(band==1)
		return true;
	else
		return false;
}

/***************************************************************************************************************************************/
/*********************************************MODIFICAR REGISTRO A LA BITACORA TRANS****************************************************/
/***************************************************************************************************************************************/

//Funcion para Evaluar los datos del formularo para modificacion de bitácora de transporte
function valFormModRegBitTransp(frm_modificarRegistroBitacora){
	//Si el valor permanece en 1, la validacion se paso correctamente
	var band=1;

	//Verificar que se haya ingresado una cantidad
	if(frm_modificarRegistroBitacora.cmb_ubicacion.value==""&&band==1){
		band = 0;
		alert ("Seleccionar una Ubicación");
	}

	if(band==1)
		return true;
	else
		return false;
}


/*Esta funcion se ecnarga de Validar el Formulario donde se selecciona el registro para borrar*/
function valFormEliminarRegTrans(frm_modificarRegistroBitacoraTransp){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_modificarRegistroBitacoraTransp.rdb_idBitacora.length==undefined && !frm_modificarRegistroBitacoraTransp.rdb_idBitacora.checked){
		alert("Seleccionar el Registro a "+frm_modificarRegistroBitacoraTransp.hdn_accion.value);
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_modificarRegistroBitacoraTransp.rdb_idBitacora.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_modificarRegistroBitacoraTransp.rdb_idBitacora.length;i++){
			if(frm_modificarRegistroBitacoraTransp.rdb_idBitacora[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar el Registro a "+frm_modificarRegistroBitacoraTransp.hdn_accion.value);
	}
		
	if (res==1 && frm_modificarRegistroBitacoraTransp.hdn_accion.value=="Eliminar"){
		
		if (!confirm("¿Estas Seguro que Quieres Borrar el Registro?\nToda la información relacionada se Borrará")){
			res=0;
		}
	}
	
	if(res==1)
		return true;
	else
		return false;	
}

//Funcion para Evaluar los datos del formularo para modificacion de bitácora de transporte
function valFormActRegBitTransp(frm_modificarBitTransporte){
	//Si el valor permanece en 1, la validacion se paso correctamente
	var band=1;

	//Verificar que se haya seleccionado al empleado
	if(frm_modificarBitTransporte.txt_nombre.value==""&&band==1){
		band = 0;
		alert ("Seleccionar un Nombre");
	}

	//Verificar que se haya ingresado una cantidad
	if(frm_modificarBitTransporte.txt_cantidad.value==""&&band==1){
		band = 0;
		alert ("Ingresar la Cantidad");
	}

	if(band==1){
		if(!validarEntero(frm_modificarBitTransporte.txt_cantidad.value.replace(/,/g,''),"La Cantidad"))
			band = 0;		
	}

	//Verificar que se haya sleccionado el cargo 
	if(frm_modificarBitTransporte.cmb_choferSup.value==""&&band==1){
		band = 0;
		alert ("Seleccionar el Cargo");
	}

	if(band==1)
		return true;
	else
		return false;
}
/*************************************************************************************************************************************************/
/**********************************************************REGISTRAR NOMINA***********************************************************************/
/*************************************************************************************************************************************************/

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

//Funcion que al deseleccionar un chek, realiza los calculos correspondintes al sueldo base
function agregarBonificacion(no,elemento){
	//Obtener a referencia para cada elemento
	check=document.getElementById(""+elemento);
	sueldo_base=document.getElementById("txt_sb"+no);
	sueldo_diario=document.getElementById("txt_sd"+no);
	total=document.getElementById("txt_total"+no);
	bonif=document.getElementById("txt_bonificacion"+no);
	bonific=document.getElementById("txt_bonificaciones"+no);
	if(check.id==("txt_he"+no)){
		//calcular el bono a aumentar por horas extra
		he = (parseFloat(sueldo_diario.value / 8 * check.value)) * 2;
		//recalcular el total
		total.value = parseFloat(sueldo_base.value) + parseFloat(he) + parseFloat(bonif.value) + parseFloat(bonific.value);
		total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
		////recalcula el total del empleado
		recalcularTotal(document.getElementById("ckb_8hrs"+no),no,0);
		//recalcula el total del empleado
		recalcularTotal(document.getElementById("ckb_12hrs"+no),no,0);
		if(document.getElementById("ckb_8hrs"+no).checked == false && document.getElementById("ckb_12hrs"+no).checked == false){
			total.value = parseFloat(sueldo_base.value) + parseFloat(he) + parseFloat(bonif.value) + parseFloat(bonific.value);
			total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
		}
	} else{
		//recalcula el total del empleado
		recalcularTotal(check,no,1);
	}
	
	if(document.getElementById("ckb_juevesAL"+no).checked || document.getElementById("ckb_viernesAL"+no).checked || document.getElementById("ckb_sabadoAL"+no).checked || document.getElementById("ckb_domingoAL"+no).checked || document.getElementById("ckb_lunesAL"+no).checked || document.getElementById("ckb_martesAL"+no).checked || document.getElementById("ckb_miercolesAL"+no).checked || document.getElementById("txt_alcohol"+no).value == 1){
		total.value=parseFloat(sueldo_base.value);
		total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
	}
}//Fin de function agregarBonificacion(no,elemento)

//Funcion que recalcula el total del empleado
function recalcularTotal(objeto,num,continuar){
	//Entra si esta seleccionado la guardia de 8 horas
	if(objeto.id==("ckb_8hrs"+num)){
		//si esta activada la casilla le suma la cantidad indicada al total
		if(objeto.checked){
			//recalcular el total
			total.value=parseFloat(350) + parseFloat(total.value);
			total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
			if(document.getElementById("ckb_12hrs"+num).checked){
				document.getElementById("ckb_12hrs"+num).checked = false;
				//recalcular el total
				total.value=parseFloat(total.value) - parseFloat(500);
				total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
			}
		}
		//si se paso el parametro de continuar se le resta la cantidad indicada al total
		else if(continuar == 1){
			//recalcular el total
			total.value=parseFloat(total.value) - parseFloat(350);
			total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
		}
	} 
	//Entra si esta seleccionado la guardia de 12 horas
	else if(objeto.id==("ckb_12hrs"+num)){
		//si esta activada la casilla le suma la cantidad indicada al total
		if(objeto.checked){
			//recalcular el total
			total.value=parseFloat(500) + parseFloat(total.value);
			total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
			if(document.getElementById("ckb_8hrs"+num).checked){
				document.getElementById("ckb_8hrs"+num).checked = false;
				//recalcular el total
				total.value=parseFloat(total.value) - parseFloat(350);
				total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
			}
		}
		//si se paso el parametro de continuar se le resta la cantidad indicada al total
		else if(continuar == 1){
			//recalcular el total
			total.value=parseFloat(total.value) - parseFloat(500);
			total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
		}
	}
	if(document.getElementById("ckb_juevesAL"+num).checked || document.getElementById("ckb_viernesAL"+num).checked || document.getElementById("ckb_sabadoAL"+num).checked || document.getElementById("ckb_domingoAL"+num).checked || document.getElementById("ckb_lunesAL"+num).checked || document.getElementById("ckb_martesAL"+num).checked || document.getElementById("ckb_miercolesAL"+num).checked || document.getElementById("txt_alcohol"+num).value == 1){
		total.value=parseFloat(sueldo_base.value);
		total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
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
		desbloquear.value="";
		//llama la funcion de onchange
		desbloquear.onchange();
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
	total=document.getElementById("txt_total"+no);
	
	//Si el checkbox esta checado, se calculan los totales
	if(incapacidad == 0){
		if(check.checked){
			//Se quita el sueldo base al total a pagar
			total.value=parseFloat(total.value) - parseFloat(sueldo_base.value);
			//aumenta dias al sueldo base
			sueldo_base.value=parseFloat(sueldo_base.value) + parseFloat(sueldo_diario.value);
			sueldo_base.value = parseFloat(Math.round(sueldo_base.value * 100) / 100).toFixed(2);
			//recalcula el total a pagar
			total.value=parseFloat(total.value) + parseFloat(sueldo_base.value);
			check2.checked=false;
			check3.checked=false;
		}
		//Si se quita el check, se calculan los totales
		else{
			//Se quita el sueldo base al total a pagar
			total.value=parseFloat(total.value) - parseFloat(sueldo_base.value);
			//descuenta dias al sueldo base
			sueldo_base.value=parseFloat(sueldo_base.value) - parseFloat(sueldo_diario.value);
			sueldo_base.value = parseFloat(Math.round(sueldo_base.value * 100) / 100).toFixed(2);
			//recalcula el total a pagar
			total.value=parseFloat(total.value) + parseFloat(sueldo_base.value);
		}
		agregarBonificacion(no,"txt_he"+no);
	} else if(incapacidad == 1){
		if(check.checked && check2.checked != false){
			//Se quita el sueldo base al total a pagar
			total.value=parseFloat(total.value) - parseFloat(sueldo_base.value);
			//descuenta dias al sueldo base
			sueldo_base.value=parseFloat(sueldo_base.value) - parseFloat(sueldo_diario.value);
			sueldo_base.value = parseFloat(Math.round(sueldo_base.value * 100) / 100).toFixed(2);
			//recalcula el total a pagar
			total.value=parseFloat(total.value) + parseFloat(sueldo_base.value);
			check2.checked=false;
			check3.checked=false;
		}
		else if(check3.checked != false){
			check2.checked=false;
			check3.checked=false;
			agregarBonificacion(no,"txt_he"+no);
		}
		agregarBonificacion(no,"txt_he"+no);
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
		agregarBonificacion(no,"txt_he"+no);
	}
	
	if(document.getElementById("ckb_juevesAL"+no).checked || document.getElementById("ckb_viernesAL"+no).checked || document.getElementById("ckb_sabadoAL"+no).checked || document.getElementById("ckb_domingoAL"+no).checked || document.getElementById("ckb_lunesAL"+no).checked || document.getElementById("ckb_martesAL"+no).checked || document.getElementById("ckb_miercolesAL"+no).checked || document.getElementById("txt_alcohol"+no).value == 1){
		total.value=parseFloat(sueldo_base.value);
		total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
	}
	
}//Fin de function establecerAsistencia(no,elemento,incapacidad,elementModif,elementoModif2)

function establecerCumplimientos(no,bon,m_av,cal,m_tot){
	cumplimiento=document.getElementById("txt_cumplimiento"+no);
	calidad=document.getElementById("txt_calidadObra"+no);
	bonif=document.getElementById("txt_bonificacion"+no);
	total=document.getElementById("txt_total"+no);
	sueldo_base=document.getElementById("txt_sb"+no);
	
	calidad.value = parseFloat(cumplimiento.value) * parseFloat(bon);
	calidad.value = parseFloat(Math.round(calidad.value * 100) / 100).toFixed(2);
	
	if(m_av >= m_tot){
		bonif.value = parseFloat(calidad.value) + parseFloat(cal);
		bonif.value = parseFloat(Math.round(bonif.value * 100) / 100).toFixed(2);
	} else {
		bonif.value = calidad.value;
		bonif.value = parseFloat(Math.round(bonif.value * 100) / 100).toFixed(2);
	}
	
	/*bonif.value = parseFloat(calidad.value) + parseFloat(cal);
	bonif.value = parseFloat(Math.round(bonif.value * 100) / 100).toFixed(2);*/
	
	total.value=parseFloat(sueldo_base.value) + parseFloat(bonif.value);
	total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
	agregarBonificacion(no,"txt_he"+no);
	
	if(document.getElementById("ckb_juevesAL"+no).checked || document.getElementById("ckb_viernesAL"+no).checked || document.getElementById("ckb_sabadoAL"+no).checked || document.getElementById("ckb_domingoAL"+no).checked || document.getElementById("ckb_lunesAL"+no).checked || document.getElementById("ckb_martesAL"+no).checked || document.getElementById("ckb_miercolesAL"+no).checked  || document.getElementById("txt_alcohol"+no).value == 1){
		total.value = parseFloat(sueldo_base.value);
		total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
	}
}

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
	
	if(res==1)
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
	
	if(frm_generarReporteMes.txt_numEmpleados.value=="" && band==1){
		alert("Ingresar el Número de Empleados");
		band = 0;
	}

	if(band==1){
		if(!validarEntero(frm_generarReporteMes.txt_numEmpleados.value.replace(/,/g,''),"El Número de Empleados"))
			band = 0;		
	}
	
	if(band==1)
		return true;
	else
		return false
}


/*Esta funcion revisa que sea seleccionado un periodo para ver el reporte*/
function valFormGenerarRepoFecha(frm_generarReporteFecha){
	//Si el valor de la variable se mantiene en 1, e proceso de validación será satisfactorio
	var band = 1;
		
	if(frm_generarReporteFecha.txt_numEmpleados.value=="" && band==1){
		alert("Ingresar el Número de Empleados");
		band = 0;
	}

	if(band==1){
		if(!validarEntero(frm_generarReporteFecha.txt_numEmpleados.value.replace(/,/g,''),"El Número de Empleados"))
			band = 0;		
	}
	
	if(band==1)
		return true;
	else
		return false
	
}

/*****************************************************************************************************************************************************************/
/*******************************************************FUNCIONES AGREGAR CUADRRILLA******************************************************************************/
/*****************************************************************************************************************************************************************/

function agregarAreaCuadrilla(ckb_nuevaUbicacion, cmb_nuevaUbicacion, cmb_ubicacion){
	if (ckb_nuevaUbicacion.checked){
		cmb_nuevaUbicacion.disabled=false;
		cmb_nuevaUbicacion.required="required";
		cmb_nuevaUbicacion.value="";
		cmb_nuevaUbicacion.focus();
		cmb_ubicacion.disabled=true;
		cmb_ubicacion.required="";
		cmb_ubicacion.value="";
	} else {
		cmb_ubicacion.disabled=false;
		cmb_ubicacion.required="required";
		cmb_ubicacion.value="";
		cmb_ubicacion.focus();
		cmb_nuevaUbicacion.disabled=true;
		cmb_nuevaUbicacion.required="";
		cmb_nuevaUbicacion.value="";
	}
}

function valFormCuadrilla(frm_agregarCuadrilla){
	var band = 1;
	
	if( !frm_agregarCuadrilla.ckb_zarpeoViaSeca.checked && !frm_agregarCuadrilla.ckb_zarpeoViaHumeda.checked){
		alert("Seleccionar al Menos una Aplicación para la Cuadrilla");
		band = 0;
	}
	
	if(band==1)
		return true;
	else
		return false;	
}

function agregarNvoPuesto(comboBox){
	if(comboBox.value=="NUEVO"){
		var nvoPuesto = "";
		var condicion = false;
		do{
			nvoPuesto = prompt("Introducir Nuevo Puesto","Nuevo Puesto...");
			if(nvoPuesto=="Nuevo Puesto..." ||  nvoPuesto=="" || (nvoPuesto!=null && nvoPuesto.length>15)){
				condicion = true;
				if (nvoPuesto!=null && nvoPuesto.length>15)
					alert("Solo se permite un máximo de 15 carácteres");
			}
			else
				condicion = false;
		}while(condicion);
		
		if(nvoPuesto!=null){
			nvoPuesto = nvoPuesto.toUpperCase();
			var existe = 0;
			
			for(i=0; i<comboBox.length; i++){
				if(comboBox.options[i].value==nvoPuesto)
					existe = 1;
			}
			
			if(existe==0){
				comboBox.length++;
				comboBox.options[comboBox.length-1].text = nvoPuesto;
				comboBox.options[comboBox.length-1].value = nvoPuesto;
				comboBox.options[comboBox.length-1].selected = true;
			}
			
			else{
				alert("El Puesto "+nvoPuesto+" ya esta Registrado \nen las Opciones de la Lista de Puestos");
				comboBox.value = nvoPuesto;
			}
		}
		
		else if(nvoPuesto== null){
			comboBox.value = "";	
		}
	}
}

function comprobarFecha(){
	
	var iniDia=document.getElementById("txt_fechaIni").value.substr(0,2);
	var iniMes=document.getElementById("txt_fechaIni").value.substr(3,2);
	var iniAnio=document.getElementById("txt_fechaIni").value.substr(6,4);
	
	var finDia=document.getElementById("txt_fechaFin").value.substr(0,2);
	var finMes=document.getElementById("txt_fechaFin").value.substr(3,2);
	var finAnio=document.getElementById("txt_fechaFin").value.substr(6,4);		
	
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
		
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);
	
	if(fechaIni>fechaFin){
		alert("La Fecha Inicio no Puede ser Mayor a la Fecha Fin");
		document.getElementById("txt_fechaIni").value = document.getElementById("txt_fechaIni").defaultValue;
		document.getElementById("txt_fechaFin").value = document.getElementById("txt_fechaFin").defaultValue;
		return false;
	}
}

/**************************************************************************************************************************************************************/
/*******************************************************FUNCIONES CALCULAR NOMINA******************************************************************************/
/**************************************************************************************************************************************************************/

function calcularTotalNomina(obj_total,sueldo,dias,obj_he,obj_bono,cont){
	var total = 0;
	var sueldo_empl = 0;
	var guardia = 0;
	var alcohol = false;
	var he = parseFloat(document.getElementById(obj_he).value);
	var bono = document.getElementById(obj_bono).value;
	var obj_8hrs = document.getElementById("chk_8hrs_"+cont);
	var obj_12hrs = document.getElementById("chk_12hrs_"+cont);
	bono = parseFloat(bono.replace(",",""));
	
	for(var i=0; i<dias; i++){
		var obj_asis = document.getElementById("chk_asistencia_"+cont+"_"+i);
		var obj_desc = document.getElementById("chk_descanso_"+cont+"_"+i);
		var obj_alcohol = document.getElementById("chk_alcohol_"+cont+"_"+i);
		
		if(obj_asis.checked || obj_desc.checked){
			sueldo_empl += sueldo;
		}
		
		if(obj_alcohol.checked)
			alcohol = true;
	}
	
	if(obj_8hrs.checked)
		guardia = 350;
	else if(obj_12hrs.checked)
		guardia = 500;
	
	he = ((sueldo / 8)*2)*he;
	total = total + he + bono + sueldo_empl + guardia;
	if(alcohol)
		total = sueldo_empl;
	total = parseFloat(Math.round(total * 100) / 100).toFixed(2);
	
	document.getElementById(obj_total).value = total;
}

function checarAsistencia(seleccion,cont,dia){
	var asistencia = document.getElementById("chk_asistencia_"+cont+"_"+dia);
	var incapacidad = document.getElementById("chk_incapacidad_"+cont+"_"+dia);
	var descanso = document.getElementById("chk_descanso_"+cont+"_"+dia);
	var alcohol = document.getElementById("chk_alcohol_"+cont+"_"+dia);
	
	if(seleccion.name == "chk_asistencia_"+cont+"_"+dia && seleccion.checked){
		incapacidad.checked = false;
		descanso.checked = false;
		alcohol.checked = false;
	}
	else if(seleccion.name == "chk_incapacidad_"+cont+"_"+dia && seleccion.checked){
		asistencia.checked = false;
		descanso.checked = false;
		alcohol.checked = false;
	}
	else if(seleccion.name == "chk_descanso_"+cont+"_"+dia && seleccion.checked){
		incapacidad.checked = false;
		asistencia.checked = false;
		alcohol.checked = false;
	}
	else if(seleccion.name == "chk_alcohol_"+cont+"_"+dia && seleccion.checked){
		incapacidad.checked = false;
		descanso.checked = false;
		asistencia.checked = false;
	}
}

function checarGuardia(seleccion,cont){
	var obj_8hrs = document.getElementById("chk_8hrs_"+cont);
	var obj_12hrs = document.getElementById("chk_12hrs_"+cont);
	
	if(seleccion.name == "chk_8hrs_"+cont && seleccion.checked){
		obj_12hrs.checked = false;
	}
	else if(seleccion.name == "chk_12hrs_"+cont && seleccion.checked){
		obj_8hrs.checked = false;
	}
}

function comprobarFechas(fechaI,fechaF){
	
	var iniDia=document.getElementById(fechaI).value.substr(0,2);
	var iniMes=document.getElementById(fechaI).value.substr(3,2);
	var iniAnio=document.getElementById(fechaI).value.substr(6,4);
	
	var finDia=document.getElementById(fechaF).value.substr(0,2);
	var finMes=document.getElementById(fechaF).value.substr(3,2);
	var finAnio=document.getElementById(fechaF).value.substr(6,4);		
	
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
		
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);
	
	if(fechaIni>fechaFin){
		alert("La Fecha Inicio no Puede ser Mayor a la Fecha Fin");
		document.getElementById(fechaI).value = document.getElementById(fechaI).defaultValue;
		document.getElementById(fechaF).value = document.getElementById(fechaF).defaultValue;
		return false;
	}
}