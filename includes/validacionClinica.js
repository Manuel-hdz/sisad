/**
  * Nombre del Módulo: Paileria                                               
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 13/Enero/2012
  * Descripción: Este archivo contiene funciones para validar los diferentes formularios del Módulo Paileria
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
	if(te==9){//Campo RFC, numero telefónico, solo acepta numeros o letras o ambos, no permite ningun caracter especial
		var teclas_especiales = [8,63];		
		//8 = BackSpace, 63 = Caracter Especial (?)
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

/********************************************Función que permite Validar que en las Claves del Material NO se puedan ingresar comillas(")*********************************/

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


/*Esta función verifica que el dato proporcionado sea un numero valido y que a su vez este pueda ser igual a 0*/
function validarEnteroValorCero(valor){ 
	var cond = true;
	//Comprobar si es un valor numérico 
	if (isNaN(valor)) { 			
		//Numero invalido
		alert ("El Dato: '"+valor+"' es Incorrecto, Solo se Aceptan Numeros");
		cond = false;
	}	
	return cond;
}

/****************************************************************************************************************************************************************************************/
/**************************************************************************BITACORA DE CONSULTAS*****************************************************************************************/
/****************************************************************************************************************************************************************************************/
//Funcion que activa los campos para indicar si el paciente es familiar del trabajador
function activarFamiliarTrabajador(check,cajaNom,cajaPar){
	if(check.checked){
		cajaNom.readOnly=false;
		cajaPar.readOnly=false;
	}
	else{
		cajaNom.value="";
		cajaPar.value="";
		cajaNom.readOnly=true;
		cajaPar.readOnly=true;
	}
}

//Funcion que restablece a modo readonly los campos de nombre y parentesco del trabajador
function restablecerFormConMedInterno(cajaNom,cajaPar){
	cajaNom.readOnly=true;
	cajaPar.readOnly=true;
}

//Funcion que abre la ventana emergente donde se reigstrará el medicamento a entregar al trabajador
function registrarMedicamento(boton){
	boton.disabled=true;
	boton=boton.name;
	window.open('verRegMedicamento.php?btn='+boton, '_blank','top=100, left=100, width=720, height=610, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
}

function actualizarMedicamento(boton,id){
	boton.disabled=true;
	boton=boton.name;
	window.open('verRegMedicamentoApp.php?id='+id+'&btn='+boton, '_blank','top=100, left=100, width=720, height=610, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
}


//Funcion que valida el formulario de registrar consulta medica Interna
function valFormConsMedInterna(frm_regBitConInterna){
	var res=1;
	
	if(frm_regBitConInterna.txt_hora.value==""){
		res=0;
		alert("Ingresar la Hora de la Consulta");
	}
	
	if(res==1 && frm_regBitConInterna.txt_nombre.value==""){
		res=0;
		alert("Ingresar y Seleccionar el Nombre del Trabajador");
	}
	
	if(res==1 && frm_regBitConInterna.ckb_familiar.checked){
		if(frm_regBitConInterna.txt_nomFamiliar.value==""){
			res=0;
			alert("Ingresar el Nombre del Familiar del Trabajador");
		}
		if(res==1 && frm_regBitConInterna.txt_parentesco.value==""){
			res=0;
			alert("Ingresar el Parentesco del Familiar con el Trabajador");
		}
	}
	
	if(res==1 && frm_regBitConInterna.txt_lugar.value==""){
		res=0;
		alert("Ingresar el Lugar donde se Practicó la Consulta Médica");
	}
	
	if(res==1 && frm_regBitConInterna.txa_diagnostico.value==""){
		res=0;
		alert("Ingresar el Diagnóstico");
	}
	
	if(res==1 && frm_regBitConInterna.txa_tratamiento.value==""){
		res=0;
		alert("Ingresar el Tratamiento");
	}
	
	if(res==1 && frm_regBitConInterna.hdn_medicamento.value==0){
		if(!confirm("No se ha Registrado Entrega/Aplicación de ningún Medicamento. ¿Desea Continuar a Guardar el Registro?"))
			res=0;
	}
	
	if(res==1)
		return true;
	else
		return false;
}

//Funcion que valida el formulario de registrar consulta medica Externa
function valFormConsMedExterna(frm_regBitConExterna){
	var res=1;
	
	if(frm_regBitConExterna.cmb_empresa.value==""){
		res=0;
		alert("Seleccionar la Empresa de Procedencia del Trabajador");
	}
	
	if(res==1 && frm_regBitConExterna.txt_hora.value==""){
		res=0;
		alert("Ingresar la Hora de la Consulta");
	}
	
	if(res==1 && frm_regBitConExterna.txt_nombre.value==""){
		res=0;
		alert("Ingresar el Nombre del Trabajador");
	}
	
	if(res==1 && frm_regBitConExterna.txt_rfc.value==""){
		res=0;
		alert("Ingresar el RFC del Trabajador");
	}
	
	if(res==1 && frm_regBitConExterna.txt_noEmpleado.value==""){
		res=0;
		alert("Ingresar el Número de Empleado del Trabajador");
	}
	
	if(res==1 && frm_regBitConExterna.ckb_familiar.checked){
		if(frm_regBitConExterna.txt_nomFamiliar.value==""){
			res=0;
			alert("Ingresar el Nombre del Familiar del Trabajador");
		}
		if(res==1 && frm_regBitConExterna.txt_parentesco.value==""){
			res=0;
			alert("Ingresar el Parentesco del Familiar con el Trabajador");
		}
	}
	
	if(res==1 && frm_regBitConExterna.txt_lugar.value==""){
		res=0;
		alert("Ingresar el Lugar donde se Practicó la Consulta Médica");
	}
	
	if(res==1 && frm_regBitConExterna.txt_area.value==""){
		res=0;
		alert("Ingresar el Área donde el Trabajador se desempeña");
	}
	
	if(res==1 && frm_regBitConExterna.txt_puesto.value==""){
		res=0;
		alert("Ingresar el Puesto del Trabajador");
	}
	
	if(res==1 && frm_regBitConExterna.txa_diagnostico.value==""){
		res=0;
		alert("Ingresar el Diagnóstico");
	}
	
	if(res==1 && frm_regBitConExterna.txa_tratamiento.value==""){
		res=0;
		alert("Ingresar el Tratamiento");
	}
	
	if(res==1 && frm_regBitConExterna.hdn_medicamento.value==0){
		if(!confirm("No se ha Registrado Entrega/Aplicación de ningún Medicamento. ¿Desea Continuar a Guardar el Registro?"))
			res=0;
	}
	
	if(res==1)
		return true;
	else
		return false;
}

/*****************************************************************************************************************************************************************************************/
/*************************************************************************BITACORA DE MEDICAMENTOS****************************************************************************************/
/*****************************************************************************************************************************************************************************************/
//Funcion que valida que se seleccione una clasificacion y luego un medicamento
function valFormIncMedicamento(frm_incrementarMedicamento){
	var res=1;
	if(frm_incrementarMedicamento.cmb_clasificacion.value==""){
		res=0;
		alert("Seleccionar la Clasificación");
	}
	if(res==1 && frm_incrementarMedicamento.cmb_medicamento.value==""){
		res=0;
		alert("Seleccionar el Medicamento");
	}
	if(res==1 && frm_incrementarMedicamento.txt_surtido.value==""){
		res=0;
		alert("Ingresar la Cantidad Unitaria que se esta Surtiendo de Medicamento");
	}
	if(res==1)
		return true;
	else
		return false;
}

//Funcion que permite agregar un nuevo Dato en una caja de Texto readonly, en caso de no tener la opcion en un combobox
function agregarNuevoDato(combo,check,cajaTexto){
	if(check.checked){
		combo.disabled=true;
		cajaTexto.readOnly=false;
		combo.value="";
		cajaTexto.value="";
	}
	else{
		combo.disabled=false;
		cajaTexto.readOnly=true;
		combo.value="";
		cajaTexto.value="";
	}
}

//Funcion que obtiene el total del medicamento de forma individual a partir de los envases y su existencia unitaria
function obtenerTotalMedicamento(cantPorPres,cantPres){
	if(cantPorPres!=undefined && cantPres!=undefined)
		document.getElementById("txt_cantMedTotal").value=cantPorPres*cantPres;
}

//Funcion que valida el formulario de agregar/actualizar los medicamentos
function valFormActualizarMedicamento(frm_registrarBitacoraMedicamentos){
	var res=1;
	
	if(frm_registrarBitacoraMedicamentos.txt_codigo.value==""){
		res=0;
		alert("Ingresar el Código del Medicamento");
	}
	
	if(res==1 && !frm_registrarBitacoraMedicamentos.ckb_nuevaClasificacion.checked){
		if(frm_registrarBitacoraMedicamentos.cmb_clasificacion.value=="" ){
			res=0;
			alert("Seleccionar la Clasificación del Medicamento");
		}
	}
	
	if(res==1 && frm_registrarBitacoraMedicamentos.ckb_nuevaClasificacion.checked){
		if(frm_registrarBitacoraMedicamentos.txt_nuevaClasificacion.value==""){
			res=0;
			alert("Ingresar la Clasificación del Medicamento");
		}
	}
	
	if(res==1 && frm_registrarBitacoraMedicamentos.txa_descripcion.value==""){
		res=0;
		alert("Ingresar la Descripción del Medicamento");
	}
	
	if(res==1 && frm_registrarBitacoraMedicamentos.txa_presentacion.value==""){
		res=0;
		alert("Ingresar la Presentación del Medicamento");
	}
	
	if(res==1 && frm_registrarBitacoraMedicamentos.txt_nomMed.value==""){
		res=0;
		alert("Ingresar el Nombre del Medicamento");
	}
	
	if(res==1 && frm_registrarBitacoraMedicamentos.txt_cantPres.value==""){
		res=0;
		alert("Ingresar la Cantidad por Presentación del Medicamento");
	}
	
	if(res==1 && frm_registrarBitacoraMedicamentos.txt_cantMed.value==""){
		res=0;
		alert("Ingresar la Cantidad del Medicamento (Cajas,Frascos,etc...)");
	}
	
	if(res==1 && !frm_registrarBitacoraMedicamentos.ckb_nuevaUnidadMedida.checked){
		if(frm_registrarBitacoraMedicamentos.cmb_unidadMed.value=="" ){
			res=0;
			alert("Seleccionar la Unidad de Medida del Medicamento");
		}
	}
	
	if(res==1 && frm_registrarBitacoraMedicamentos.ckb_nuevaUnidadMedida.checked){
		if(frm_registrarBitacoraMedicamentos.txt_nuevaUnidadMedida.value==""){
			res=0;
			alert("Ingresar la Unidad de Medida del Medicamento");
		}
	}
	
	if(res==1 && !frm_registrarBitacoraMedicamentos.ckb_nuevaPresentacion.checked){
		if(frm_registrarBitacoraMedicamentos.cmb_tipoPres.value=="" ){
			res=0;
			alert("Seleccionar el Tipo de Presentación del Medicamento");
		}
	}
	
	if(res==1 && frm_registrarBitacoraMedicamentos.ckb_nuevaPresentacion.checked){
		if(frm_registrarBitacoraMedicamentos.txt_nuevaPresentacion.value==""){
			res=0;
			alert("Ingresar el Tipo de Presentación del Medicamento");
		}
	}

	if(res==1 && !frm_registrarBitacoraMedicamentos.ckb_nuevaUnidadDesp.checked){
		if(frm_registrarBitacoraMedicamentos.cmb_unidadDesp.value=="" ){
			res=0;
			alert("Seleccionar la Unidad de Despacho del Medicamento");
		}
	}
	
	if(res==1 && frm_registrarBitacoraMedicamentos.ckb_nuevaUnidadDesp.checked){
		if(frm_registrarBitacoraMedicamentos.txt_nuevaUnidadDesp.value==""){
			res=0;
			alert("Ingresar la Unidad de Despacho del Medicamento");
		}
	}
	
	if(res==1)
		return true;
	else
		return false;
}

/*****************************************************************************************************************************************************************************************/
/*****************************************************************REGISTRAR BITACORA DE RADIOGRAFIAS**************************************************************************************/
/*****************************************************************************************************************************************************************************************/
//Funcion que escribe en el formulario de Registro de Radiografia segun la opcion elegida del combo, los campos a mostrar
function filtroBitacoraRadiografia(opcCombo){
	if(opcCombo=="EXTERNO"){
		document.getElementById("etiqueta").innerHTML = "Empresa Externa";
		document.getElementById("componenteHTML").innerHTML = "<select name='cmb_empresas' class='combo_box' id='cmb_empresas'><option value='' selected='selected'>Empresas</option></select>";
		cargarComboCompleto("bd_clinica","catalogo_empresas","id_empresa","nom_empresa","cmb_empresas","Empresas","");
	}
	if(opcCombo=="INTERNO"){
		document.getElementById("etiqueta").innerHTML = "Nombre Trabajador";
		document.getElementById("componenteHTML").innerHTML = "<input type=\"text\" name=\"txt_nombre\" id=\"txt_nombre\" onkeyup=\"lookup(this,'empleados','1');\" value=\"\" size=\"50\" maxlength=\"75\" onkeypress=\"return permite(event,'car',0);\" tabindex=\"1\"/><div id=\"res-spider\"><div align=\"left\" class=\"suggestionsBox\" id=\"suggestions1\" style=\"display: none;\"><img src=\"../../images/upArrow.png\" style=\"position: relative; top: -12px; left: 10px;\" alt=\"upArrow\" /><div class=\"suggestionList\" id=\"autoSuggestionsList1\">&nbsp;</div></div></div>";
	}
	if(opcCombo=="INGRESO"){
		document.getElementById("etiqueta").innerHTML = "Nombre Trabajador";
		document.getElementById("componenteHTML").innerHTML = "<input type=\"text\" name=\"txt_nombre\" id=\"txt_nombre\" value=\"\" size=\"50\" maxlength=\"75\" onkeypress=\"return permite(event,'car',0);\" tabindex=\"1\"/>";
	}
	if(opcCombo==""){
		document.getElementById("etiqueta").innerHTML = "&nbsp;";
		document.getElementById("componenteHTML").innerHTML = "&nbsp;";
	}
}

//Restablecer el formulario de seleccion de tipo de Clasificacion de registro de Radiografias
function limpiarBitRadiografia(){
	document.getElementById("etiqueta").innerHTML = "&nbsp;";
	document.getElementById("componenteHTML").innerHTML = "&nbsp;";
}

//Funcion que abre la ventana emergente con las opciones para mostrar las radiografias disponibles y asignarlas al empleado que se esta registrando
function abrirVentanaRadiografias(boton){
	num=document.getElementById("txt_numE").value;
	idBitacora=document.getElementById("txt_idBit").value;
	nombre=document.getElementById("txt_nombre").value;
	if(num!="" && nombre!=""){
		boton.disabled=true;
		boton=boton.name;
		window.open('verRadiografias.php?num='+num+'&nombre='+nombre+'&idBitacora='+idBitacora+'&btn='+boton, '_blank','top=100, left=100, width=720, height=610, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
	}
	else{
		alert("Ingresar los Datos de Número y/o Nombre de Empleado");
	}
}




//Funcion que restablece el formulario de registro de la Bitácora de Radiografia
function restablecerFormularioBitRadio(){
	document.getElementById("txt_fecha").value=document.getElementById("txt_fecha").defaultValue;
	document.getElementById("txt_nombre").value=document.getElementById("txt_nombre").defaultValue;
	document.getElementById("txt_lugar").value=document.getElementById("txt_lugar").defaultValue;
	document.getElementById("txt_numE").value=document.getElementById("txt_numE").defaultValue;
	document.getElementById("txt_area").value=document.getElementById("txt_area").defaultValue;
	document.getElementById("txt_nomSolicitante").value=document.getElementById("txt_nomSolicitante").defaultValue;
	document.getElementById("txt_puesto").value=document.getElementById("txt_puesto").defaultValue;
	document.getElementById("txt_nomResponsable").value=document.getElementById("txt_nomResponsable").defaultValue;
}

//Funcion que valida se seleccione un tipo de Clasificacion de Radiografias
function valFormSelRegBitRadio(frm_seleccionarRegBitRadio){
	//Variable que controla el proceso de validacion de formularios
	var band=1;
	
	if(frm_seleccionarRegBitRadio.cmb_clasificacion.value==""){
		band=0;
		alert("Seleccionar el Tipo de Clasificación");
	}
	else{
		if(frm_seleccionarRegBitRadio.cmb_clasificacion.value=="EXTERNO"){
			if(frm_seleccionarRegBitRadio.cmb_empresas.value==""){
				band=0;
				alert("Seleccionar la Empresa");
			}
		}
		if(frm_seleccionarRegBitRadio.cmb_clasificacion.value=="INGRESO"){
			if(frm_seleccionarRegBitRadio.txt_nombre.value==""){
				band=0;
				alert("Ingresar el Nombre del Trabajador");
			}
		}
		if(frm_seleccionarRegBitRadio.cmb_clasificacion.value=="INTERNO"){
			if(frm_seleccionarRegBitRadio.txt_nombre.value==""){
				band=0;
				alert("Ingresar el Nombre del Trabajador de la lista de Sugerencias");
			}
		}
	}
	
	if(band==0)
		return false;
	else
		return true;
}

//Funcion que valida el formulario que guarda el Registro de las Radiografias en la BD
function valFormGuardarRadio(frm_registrarRadiografia){
	res=1;
	
	if(frm_registrarRadiografia.txt_nombre.value==""){
		res=0;
		alert("Ingresar el Nombre del Trabajador");
	}
	
	if(res==1 && frm_registrarRadiografia.txt_lugar.value==""){
		res=0;
		alert("Ingresar el Lugar donde se Practicó");
	}
	
	if(res==1 && frm_registrarRadiografia.txt_numE.value==""){
		res=0;
		alert("Ingresar el Número de Empleado");
	}
	
	if(res==1 && frm_registrarRadiografia.txt_cantProy.value=="0"){
		res=0;
		alert("Registrar las Radiografías Aplicadas al Trabajador mediante el Botón 'Registrar Radiografías'");
		frm_registrarRadiografia.btn_regRadiografias.focus();
	}
	
	if(res==1 && frm_registrarRadiografia.txt_area.value==""){
		res=0;
		alert("Ingresar el Área del Empleado");
	}
	
	if(res==1 && frm_registrarRadiografia.txt_nomSolicitante.value==""){
		res=0;
		alert("Ingresar el Nombre del Solicitante");
	}
	
	if(res==1 && frm_registrarRadiografia.txt_puesto.value==""){
		res=0;
		alert("Ingresar el Puesto del Empleado");
	}
	
	if(res==1 && frm_registrarRadiografia.txt_nomResponsable.value==""){
		res=0;
		alert("Ingresar el Puesto del Empleado");
	}
	
	if(res==1)
		return true;
	else
		return false;
}

//Funcion que valida el formulario de seleccion de radiografias
function valFormSelRadiografias(frm_asignarRadiografia){
	var res=1;
	var cantidad=frm_asignarRadiografia.cant_ckbs.value;
	var ctrl=1;
	
	if(!document.getElementById("ckbTodo").checked){
		while(ctrl<cantidad){		
			//Crear el id del CheckBox que se quiere verificar
			idCheckBox="ckb_radiografia"+ctrl.toString();
			//Verificar que la cantidad y la aplicación del Checkbox seleccionado no esten vacias
			if(document.getElementById(idCheckBox).checked)
				status = 1;
			ctrl++;
		}//Fin del While	
		
		//Verificar que al menos un check haya sido seleccionado
		if(status!=1){
			alert("Seleccionar al Menos una Radiografía para Registrarla al Empleado");
			res = 0;
		}
	}
	if(res==1){
		document.getElementById("hdn_cerrar").value='0';
		return true;
	}
	else
		return false;
}

/*Estan función activa todos lo CheckBox del formulario de seleccion de Equipos para filtrar el reporte de Disponibilidad*/
function checarTodos(chkbox){
	for(var i=0;i<document.frm_asignarRadiografia.elements.length;i++){
		//Variable
		elemento=document.frm_asignarRadiografia.elements[i];
		if (elemento.type=="checkbox")
			elemento.checked=chkbox.checked;
	}	
}

//Funcion que valida el formulario de seleccion de Registros de la Bitacora
function valFormSelRegBitacora(frm_selRegModificarRadio){
	//Recuperar la accion del boton seleccionado
	var accion=frm_selRegModificarRadio.hdn_accion.value;
	//Variable bandera de control de validacion
	var band=1;
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_selRegModificarRadio.rdb_bitacora.length==undefined && !frm_selRegModificarRadio.rdb_bitacora.checked){
		alert("Seleccionar el Registro de la Bitácora a "+accion);
		band = 0;
	}
	//Confirmar que se desea borrar el Registro seleccionado teniendo en cuenta que es el Radiobutton tiene solo una opcion
	if(frm_selRegModificarRadio.rdb_bitacora.length==undefined && accion=="Eliminar" && band==1){
		if(!confirm("De Borrar el Registro de la Bitácora Seleccionado ya no se podrá Recuperar. \nPresione Aceptar para Borrarlo Definitivamente"))
			band = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_selRegModificarRadio.rdb_bitacora.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		band = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_selRegModificarRadio.rdb_bitacora.length;i++){
			if(frm_selRegModificarRadio.rdb_bitacora[i].checked)
				band = 1;
		}
		if(band==0)
			alert("Seleccionar el Registro de la Bitácora a "+accion);
	}
	//Confirmar que se desea borrar el Registro seleccionado teniendo en cuenta que es el Radiobutton tiene mas de una opcion
	if(frm_selRegModificarRadio.rdb_bitacora.length>=2 && accion=="Eliminar" && band==1){
		if(!confirm("De Borrar el Registro de la Bitácora Seleccionado ya no se podrá Recuperar. \nPresione Aceptar para Borrarlo Definitivamente"))
			band = 0;
	}
	if (band==1)
		return true;
	else
		return false;
}
/*****************************************************************************************************************************************************************************************/
/****************************************************************************GENERAR REQUISICION******************************************************************************************/
/*****************************************************************************************************************************************************************************************/
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

/*****************************************************************************************************************************************************************************************/
/************************************************************************EDITAR REGISTROS***********************************************************************************************/
/*****************************************************************************************************************************************************************************************/
/*Esta funcion valida que los datos ingresados en el formulario de editar registro de requisicion no esten vacios*/
function valFormEditarRegistroRequisicion(frm_editarRegistroRequisicion){
	//Si el valor se mantiene en 1 el proceso de validacion fue exitoso
	var band = 1;
	
	//Verificar que el dato de costo y cantidad no esten vacios
	if(frm_editarRegistroRequisicion.txt_cantReq.value==""){
		alert("Ingresar la Cantidad que Será Solicitada del Material");
		band = 0;
	}
	else{
		if(frm_editarRegistroRequisicion.txt_aplicacion.value==""){
			alert("Ingresar la Aplicación del Material");
			band = 0;
		}
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


/*******************************************************************************************************************************************/
/*************************************************************CATALOGO EMPRESAS EXTERNAS***************************************************/
/*****************************************************************************************************************************************/

//Funcion para agregar una nueva empresa externas a la BD de la Clinica en la tabla de catalogo_empresas
function agregarNuevaEmpresaExt(check){
	if(check.checked){
		//Primero: Al verificar que la opcion del check esta seleccionada, vaciar los campos del formulario en caso de que contengan datos
		document.getElementById("cmb_empresa").value="";
		document.getElementById("txt_nomEmpresa").value="";
		document.getElementById("txt_razonSocial").value="";
		document.getElementById("txt_tipoEmpresa").value="";
		document.getElementById("txt_calle").value="";
		document.getElementById("txt_colonia").value="";
		document.getElementById("txt_ciudad").value="";
		document.getElementById("txt_estado").value="";
		document.getElementById("txt_tel").value="";
		document.getElementById("txt_numExt").value="";
		document.getElementById("txt_numInt").value="";
		
		//Para despues desabilitar los campos y poder almacenar informacion dentro de ellos.
		document.getElementById("cmb_empresa").disabled=true;
		document.getElementById("txt_nomEmpresa").readOnly=false;
		document.getElementById("txt_razonSocial").readOnly=false;
		document.getElementById("txt_tipoEmpresa").readOnly=false;
		document.getElementById("txt_calle").readOnly=false;
		document.getElementById("txt_colonia").readOnly=false;
		document.getElementById("txt_ciudad").readOnly=false;
		document.getElementById("txt_estado").readOnly=false;
		document.getElementById("txt_tel").readOnly=false;
		document.getElementById("txt_numExt").readOnly=false;
		document.getElementById("txt_numInt").readOnly=false;
	}
	else{
		//De lo contrario habilitar y colocar los campos en readonly
		document.getElementById("cmb_empresa").disabled=false;
		document.getElementById("txt_nomEmpresa").readOnly=true;
		document.getElementById("txt_razonSocial").readOnly=true;
		document.getElementById("txt_tipoEmpresa").readOnly=true;
		document.getElementById("txt_calle").readOnly=true;
		document.getElementById("txt_colonia").readOnly=true;
		document.getElementById("txt_ciudad").readOnly=true;
		document.getElementById("txt_estado").readOnly=true;
		document.getElementById("txt_tel").readOnly=true;
		document.getElementById("txt_numExt").readOnly=true;
		document.getElementById("txt_numInt").readOnly=true;
	}
}


//Funcion que aplica sobre el boton Limpiar, dentro del formulario de Empresas Externas
function restablecerEmpresaExt(){
		document.getElementById("cmb_empresa").disabled=false;
		document.getElementById("txt_nomEmpresa").readOnly=true;
		document.getElementById("txt_razonSocial").readOnly=true;
		document.getElementById("txt_tipoEmpresa").readOnly=true;
		document.getElementById("txt_calle").readOnly=true;
		document.getElementById("txt_colonia").readOnly=true;
		document.getElementById("txt_ciudad").readOnly=true;
		document.getElementById("txt_estado").readOnly=true;
		document.getElementById("txt_tel").readOnly=true;
		document.getElementById("txt_numExt").readOnly=true;
		document.getElementById("txt_numInt").readOnly=true;
}


//Funcion que se encarga de validar el formulario donde se agrega los datos de las nuevas empresas externas
function valFormgestionEmpExternas(frm_gesEmpresasExternas){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	band = 1;
	
	//Verificar que el campo de Nombre de la Empresa no este vacío
	if(frm_gesEmpresasExternas.txt_nomEmpresa.value==""){
		alert("Introducir el Nombre de la Empresa");
		band = 0;
	}
	//Verificar que el campo del Nombre de la Razon Social no este vacío
	if(frm_gesEmpresasExternas.txt_razonSocial.value=="" && band==1){

		alert("Introducir la Razón Social");
		band = 0;
	}
	//Verificar que el campo del Nombre de la Razon Social no este vacío
	if(frm_gesEmpresasExternas.txt_color.value=="FFFFFF" && band==1){
		alert("Seleccionar el Color que Identifique la Empresa");
		band = 0;
	}
	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}


/*Funcion para validar que el el numero de telefono sea una cifra valida	*/
function validarTelefono(telefono){
	var numero = telefono.value;		
		
	//Verificar el numero cuando la caja de texto sea diferentte de vacia ("")
	if(numero!=""){
		
		//Retirar del Telefono los Caracteres Especiales
		var nvoNumero = "";
		//Recorrer la cadena y solo contemplar los numeros para evaluar
		for(var i=0;i<numero.length;i++){
			var car = numero.charAt(i);
			
			//Solo colocar los digitos que sean nuemeros en la variable nvoNumero
			if(car=='0' || car=='1' || car=='2' || car=='3' || car=='4' || car=='5' || car=='6' || car=='7' || car=='8' || car=='9')
				nvoNumero += car;				
		}
		
		//Validar la cantidad de digitos en el numero
		if( !(  (/^\d{7}$/.test(nvoNumero)) || (/^\d{9}$/.test(nvoNumero)) || (/^\d{10}$/.test(nvoNumero)) || (/^\d{11}$/.test(nvoNumero)) || (/^\d{12}$/.test(nvoNumero)) || (/^\d{13}$/.test(nvoNumero))  ) ){
			alert("El Numero "+telefono.value+", NO es un Numero Telefónico Valido");		
			telefono.value = "";
		}
	}
}


/*Esta funcion solicita la confirmación del usuario antes de salir de la pagina*/
function confirmarSalida(pagina){
	if(confirm("¿Estas Seguro que Quieres Salir?\nToda la información no Guardada se Perderá"))
		location.href = pagina;	
}



/*******************************************************************************************************************************************/
/*************************************************************CATALOGO EXAMENES MEDICOS****************************************************/
/*****************************************************************************************************************************************/

//Funcion para agregar una nueva empresa externas a la BD de la Clinica en la tabla de catalogo_empresas
function agregarNuevoExaMedico(check){
	if(check.checked){
		//Primero: Al verificar que la opcion del check esta seleccionada, vaciar los campos del formulario en caso de que contengan datos
		document.getElementById("cmb_examen").value="";
		document.getElementById("txt_nomExamen").value="";
		document.getElementById("txt_tipoExamen").value="";
		document.getElementById("txt_costoExamen").value="";
		document.getElementById("txa_comentarios").value="";
		
		//Para despues desabilitar los campos y poder almacenar informacion dentro de ellos.
		document.getElementById("cmb_examen").disabled=true;
		document.getElementById("txt_nomExamen").readOnly=false;
		document.getElementById("txt_tipoExamen").readOnly=false;
		document.getElementById("txt_costoExamen").readOnly=false;
		document.getElementById("txa_comentarios").readOnly=false;
		
	}
	else{
		//De lo contrario habilitar y colocar los campos en readonly
		document.getElementById("cmb_examen").disabled=false;
		document.getElementById("txt_nomExamen").readOnly=true;
		document.getElementById("txt_tipoExamen").readOnly=true;
		document.getElementById("txt_costoExamen").readOnly=true;
		document.getElementById("txa_comentarios").readOnly=true;
	}
}


//Funcion que aplica sobre el boton Limpiar, dentro del formulario de Empresas Externas
function restablecerExamenMedico(){
		document.getElementById("cmb_examen").disabled=false;
		document.getElementById("txt_nomExamen").readOnly=true;
		document.getElementById("txt_tipoExamen").readOnly=true;
		document.getElementById("txt_costoExamen").readOnly=true;
		document.getElementById("txa_comentarios").readOnly=true;
}


//Funcion que se encarga de validar el formulario donde se agrega los datos de las nuevas empresas externas
function valFormgestionExaMedicos(frm_gesExamenMedico){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	band = 1;
	
	//Verificar que el campo de Nombre del Examen no este vacío
	if(frm_gesExamenMedico.txt_nomExamen.value==""){
		alert("Registrar un Nuevo Examen ó Introducir el Nombre del Examen");
		band = 0;
	}
	//Verificar que el campo tipo de examen no este vacío
	if(frm_gesExamenMedico.txt_tipoExamen.value=="" && band==1){
		alert("Introducir el Tipo de Examen");
		band = 0;
	}
	//Verificar que el campo Costo del Examen no este vacío
	if(frm_gesExamenMedico.txt_costoExamen.value=="" && band==1){
		alert("Introducir el Costo del Examen");
		band = 0;
	}
	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}




/*******************************************************************************************************************************************/
/*************************************************************CATALOGO RADIOGRAFÍAS********************************************************/
/*****************************************************************************************************************************************/

//Funcion para agregar una nueva empresa externas a la BD de la Clinica en la tabla de catalogo_empresas
function agregarNuevaProyeccion(check){
	if(check.checked){
		//Primero: Al verificar que la opcion del check esta seleccionada, vaciar los campos del formulario en caso de que contengan datos
		document.getElementById("cmb_proyeccion").value="";
		document.getElementById("txt_nomProyeccion").value="";
		document.getElementById("txa_comentarios").value="";
		
		//Para despues desabilitar los campos y poder almacenar informacion dentro de ellos.
		document.getElementById("cmb_proyeccion").disabled=true;
		document.getElementById("txt_nomProyeccion").readOnly=false;
		document.getElementById("txa_comentarios").readOnly=false;
	}
	else{
		//De lo contrario habilitar y colocar los campos en readonly
		document.getElementById("cmb_proyeccion").disabled=false;
		document.getElementById("txt_nomProyeccion").readOnly=true;
		document.getElementById("txa_comentarios").readOnly=true;
	}
}

//Funcion que aplica sobre el boton Limpiar, dentro del formulario de Empresas Externas
function restablecerCatRadiografias(){
		document.getElementById("cmb_proyeccion").disabled=false;
		document.getElementById("txt_nomProyeccion").readOnly=true;		
		document.getElementById("txa_comentarios").readOnly=true;
}


//Funcion que se encarga de validar el formulario donde se agrega los datos de las nuevas empresas externas
function valFormgestionCatRadiografias(frm_gesCatRadiografias){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	band = 1;
	
	//Verificar que el campo de Nombre de la Proyeccion no este vacío
	if(frm_gesCatRadiografias.txt_nomProyeccion.value==""){
		alert("Registrar una Nueva Radiografía ó Introducir el Nombre de la Proyección");
		band = 0;
	}
	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}



/****************************************************************************************************************************************/
/***********************************************************HISTORIAL CLINICO************************************************************/
/****************************************************************************************************************************************/
//Funcion que permite complementar el Permiso de Altutras con las condiciones de seguridad a seguir dnetro de una Ventana Emrgente
function regNuevaEmpresa(){
	//Al llamar a la ventana le enviamos el valor de la variable por el GET
		window.open('verNuevaEmpresa.php','_blank','top=50, left=50, width=800, height=500, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
}


//Funcion que permite mostrar el cmb_tipoConsulta en caso de seleccionar el nombre de una empresa
function activarOpcionTipoCon(){
	var idEmpresa=document.getElementById("cmb_empresa").value;

	if(idEmpresa!=""){
		document.getElementById("cmb_tipoConsulta").style.visibility="visible";
		document.getElementById("tipoConsulta").innerHTML="*Tipo Consulta";
		document.getElementById("ckb_nuevaEmpresa").disabled=true;
		document.getElementById("ckb_nuevaEmpresa").checked=false;
	}
	if(idEmpresa==""){
		document.getElementById("cmb_tipoConsulta").style.visibility="hidden";
		document.getElementById("tipoConsulta").innerHTML="";
		document.getElementById("ckb_nuevaEmpresa").disabled=false;
	}
}

//Funcion que se encarga de validar el formulario donde se agrega los datos de las nuevas empresas externas
function valFormRegNvaEmpExt(frm_nvaEmpExt){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	band = 1;
	
	//Verificar que el campo de Nombre de la Proyeccion no este vacío
	if(frm_nvaEmpExt.txt_nomEmpresa.value==""){
		alert("Registrar el Nombre de la Empresa");
		band = 0;
	}
	//Verificar que el campo de Nombre de la Proyeccion no este vacío
	if(frm_nvaEmpExt.txt_razonSocial.value==""&& band==1){
		alert("Ingresar la Razón Social de la Nueva Empresa");
		band = 0;
	}
	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}

//Función que permite seleccionar Todos los exmanes medicos que se encuentran registrados dentro de la BD.
function seleccionarTodo(checkbox){
	for(var i=0;i<document.frm_verExamenes.elements.length;i++){
		//Variable
		elemento=document.frm_verExamenes.elements[i];
		if (elemento.type=="checkbox")
			elemento.checked=checkbox.checked;
	}
}


//Función que permite deseleccionar Todos los checkbox de el formulario verExamenesMed.php
function quitar(checkbox){
	if(!checkbox.checked)
		document.getElementById("ckb_exaMedTodos").checked=false;
}


/*Esta función valida que se haya seleccionado un examen en el pop-up verExamenesMed.php*/
function valFormVerExamenesMedicos(frm_verExamenes){	
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var res = 1;
	//Variable para saber si al menos un registro fue seleccionado
	var status = 0;
	//Variable para controlar la cantidad de registros
	var ctrl= 1;
	//Variable para almacenar la cantidad de registros
	var cantidad = document.getElementById("hdn_cant").value-1;
		
	while(ctrl<=cantidad){		
		//Crear el id del CheckBox que se quiere verificar
		idCheckBox="ckb_examen"+ctrl.toString();
		
		//Verificar que la cantidad y la aplicación del Checkbox seleccionado no esten vacias
		if(document.getElementById(idCheckBox).checked){
			status = 1;
		}
		ctrl++;
	}//Fin del While	
	
	
	//Verificar que al menos un equipo haya sido seleccionado, si la variable status vale 1, quiere decir que al menos un examen fue seleccionado
	if(status==0){
		alert("Seleccionar al Menos un Examen Medico");
		res = 0;
	}
	if(res==1)
		return true;
	else
		return false;		
}




/*Esta funcion se encarga de acumular el valor de los examenes medicos seleccionados*/
function sumarTotalExaMedicos(check,text){
	if(document.getElementById("ckb_exaMedTodos").checked)
		document.getElementById("ckb_exaMedTodos").checked=false;
	
	examen=parseFloat(document.getElementById("txt_total").value.replace(/,/g,''));
	
	if(check.checked){
		examen = examen+parseFloat(text.value);
	}else{
		examen = examen-parseFloat(text.value);
	}
	
	formatCurrency(examen,'txt_total');
}


/*Esta funcion suma todos los checkbox dentro de la ventana emergente donde se muestran los examenes medicos a practicarle a los trabajadotes externos */
function sumarExaTodos(checkbox){
	if(checkbox.checked){
		examen=document.getElementById("hdn_acumulado").value;
		formatCurrency(examen,'txt_total');
	}else{
		examen=0;
		formatCurrency(examen,'txt_total');
	}
}




//Funcion que se encarga de validar el formulario donde se agrega los datos de las nuevas empresas externas
function valFormElaborarSolicitudExaMedico(frm_elaborarSolExaMed){
	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	band = 1;
	
	
	if(frm_elaborarSolExaMed.hdn_botonSeleccionado.value=="agregar"){

		//Verificar que el campo de Nombre de la Proyeccion no este vacío
		if(frm_elaborarSolExaMed.txt_autorizo.value==""){
			alert("Registrar el Nombre de Quien Autorizo la Solicitud Médica");
			band = 0;
		}
		//Verificar que el campo de Nombre de la Proyeccion no este vacío
		if(frm_elaborarSolExaMed.txt_gerAdmin.value==""&& band==1){
			alert("Registrar el Nombre del Responsable de la Gerencia Administrativa");
			band = 0;
		}
		//Verificar que el campo de Nombre de la Proyeccion no este vacío
		if(frm_elaborarSolExaMed.txt_resUSO.value==""){
			alert("Registrar el Nombre del Responsable de la Unidad Medica");
			band = 0;
		}
		//Verificar que el campo de Nombre de la Proyeccion no este vacío
		if(frm_elaborarSolExaMed.txt_nomEmp.value==""&& band==1){
			alert("Ingresar el Nombre del Trabajador");
			band = 0;
		}
		
		//Se verifica que la forma de pago haya sido seleccionado
		if(frm_elaborarSolExaMed.rdb_formaPago.length>=2&&band==1){
			//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
			res = 0; 
			//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
			for(i=0;i<frm_elaborarSolExaMed.rdb_formaPago.length;i++){
				if(frm_elaborarSolExaMed.rdb_formaPago[i].checked)
					res = 1;
			}
			if(res==0){
				alert("Seleccionar la Forma de Pago");			
				band=0;
			}
		}
		//Verificar que el campo de Nombre de la Proyeccion no este vacío
		if(frm_elaborarSolExaMed.txt_exaPracticados.value==""&& band==1){
			alert("Seleccionar los Exámenes a Prácticar");
			band = 0;
		}
	}
	
	//Devolver el resultado de la validación, TRUE = Validación Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}


//Funcion que permite seleccionar la pagina que ah de mostrarse  dependiendo del valor seleccionado
/*function selConsulta(){
	var combo = document.getElementById("cmb_consulta").value;
	if(combo=='ALMACEN'){
		location.href = 'frm_consultarEquipoSeguridad.php';	
	}
}*/


/*************************************************************HISTORIAL MEDICO************************************************************/

//Funcion que escribe en el formulario de Registro del historial clinico segun la opcion elegida del combo, los campos a mostrar
function filtroClasificacionHistorialClinico(opcCombo){
	if(opcCombo=="EXTERNO"){
		document.getElementById("etiquetaEmp").innerHTML = "Empresa Externa";
		document.getElementById("componenteHTMLEmp").innerHTML = "<select name='cmb_empresas' class='combo_box' id='cmb_empresas'><option value='' selected='selected'>Empresas</option></select>";
		cargarComboCompleto("bd_clinica","catalogo_empresas","id_empresa","nom_empresa","cmb_empresas","Empresas","");
	}
	
	if(opcCombo=="INTERNO"){
		document.getElementById("etiqueta").innerHTML = "Buscar Nombre";
		document.getElementById("componenteHTML").innerHTML = "<input type=\"radio\" name=\"rdb_nomEmpleado\" id=\"rdb_nomEmpleado\" value=\"BUSCAR\" onclick=\"activarRadiosHisClinico(this.value)\"/>";
		document.getElementById("componenteHTML2").innerHTML = "<input type=\"text\" name=\"txt_nombre\" id=\"txt_nombre\" onkeyup=\"lookup(this,'empleados','1');\" value=\"\" size=\"40\" maxlength=\"75\" onkeypress=\"return permite(event,'car',0);\" tabindex=\"1\"/><div id=\"res-spider\"><div align=\"left\" class=\"suggestionsBox\" id=\"suggestions1\" style=\"display: none;\"><img src=\"../../images/upArrow.png\" style=\"position: relative; top: -12px; left: 10px;\" alt=\"upArrow\" /><div class=\"suggestionList\" id=\"autoSuggestionsList1\">&nbsp;</div></div></div>";

		document.getElementById("etiqueta2").innerHTML = "Ingresar Nombre";
		document.getElementById("componenteHTML3").innerHTML = "<input type=\"radio\" name=\"rdb_nomEmpleado\" id=\"rdb_nomEmpleado\" value=\"INGRESAR\" onclick=\"activarRadiosHisClinico(this.value)\" />";
		document.getElementById("componenteHTML4").innerHTML = "<input type=\"text\" name=\"txt_nombre2\" id=\"txt_nombre2\" value=\"\" size=\"40\" maxlength=\"75\" onkeypress=\"return permite(event,'car',0);\" tabindex=\"1\"/>";
	
	}
	
	
	
	if(opcCombo==""){
		document.getElementById("etiquetaEmp").innerHTML = "&nbsp;";
		document.getElementById("componenteHTMLEmp").innerHTML = "&nbsp;";

		document.getElementById("etiqueta").innerHTML = "&nbsp;";
		document.getElementById("componenteHTML").innerHTML = "&nbsp;";
		document.getElementById("componenteHTML2").innerHTML = "&nbsp;";

		document.getElementById("etiqueta2").innerHTML = "&nbsp;";
		document.getElementById("componenteHTML3").innerHTML = "&nbsp;";
		document.getElementById("componenteHTML4").innerHTML = "&nbsp;";		
	}
}



//Funcion que valida se seleccione un tipo de Clasificacion de Radiografias
function valFormSelTipoHisClinico(frm_seleccionarHistorialClinico){
	//Variable que controla el proceso de validacion de formularios
	var band=1;
	
	if(frm_seleccionarHistorialClinico.cmb_clasificacionExa.value==""){
		band=0;
		alert("Seleccionar la Clasificación del Examen");
	}
	if(frm_seleccionarHistorialClinico.cmb_tipoClasificacion.value==""&&band==1){
		band=0;
		alert("Seleccionar el Tipo de Clasificación");
	}
	else{
		if(frm_seleccionarHistorialClinico.cmb_tipoClasificacion.value=="EXTERNO"){
			if(frm_seleccionarHistorialClinico.cmb_empresas.value==""&&band==1){
				band=0;
				alert("Seleccionar el Nombre de la Empresa");
			}
		}
		if(frm_seleccionarHistorialClinico.cmb_tipoClasificacion.value=="INTERNO"){
			//if(frm_seleccionarHistorialClinico.txt_nombre.value==""){
			if(frm_seleccionarHistorialClinico.rdb_nomEmpleado.length>=2&&band==1){
				band=0;
				for(i=0;i<frm_seleccionarHistorialClinico.rdb_nomEmpleado.length;i++){
					if(frm_seleccionarHistorialClinico.rdb_nomEmpleado[i].checked)
						band = 1;
				}
				if(band==0){
					alert("Seleccionar la Opción");			
					band=0;
				}
			}
		}
	}
	if(band==0)
		return false;
	else
		return true;
}


//Función qu permite activar o desactivar una caja de texto en caso de que el radio button seleccionado sea la opcion asignada
function activarRadiosHisClinico(radio){
	//Verificamos el radio que haya sido seleccionado
	if(radio=="BUSCAR"){
		document.getElementById("componenteHTML4").style.visibility="hidden";
		document.getElementById("componenteHTML2").style.visibility="visible";
	}
	
	else if(radio=="INGRESAR"){
		document.getElementById("componenteHTML2").style.visibility="hidden";
		document.getElementById("componenteHTML4").style.visibility="visible";
	}
}


//Función qu permite activar o desactivar una caja de texto en caso de que el radio button seleccionado sea la opcion asignada
function activarCajaHisClinico(opcCombo){
	//Verificamos el radio que haya sido seleccionado
	if(opcCombo=="EXTERNO"){
		document.getElementById("etiqueta").style.visibility="hidden";
		document.getElementById("etiqueta2").style.visibility="hidden";
		document.getElementById("componenteHTML").style.visibility="hidden";
		document.getElementById("componenteHTML2").style.visibility="hidden";
		document.getElementById("componenteHTML3").style.visibility="hidden";		
		document.getElementById("componenteHTML4").style.visibility="hidden";
	}
	else {
		document.getElementById("etiqueta").style.visibility="visible";
		document.getElementById("etiqueta2").style.visibility="visible";
		document.getElementById("componenteHTML").style.visibility="visible";
		document.getElementById("componenteHTML2").style.visibility="visible";
		document.getElementById("componenteHTML3").style.visibility="visible";		
		document.getElementById("componenteHTML4").style.visibility="visible";	
	}
	 if(opcCombo=="INTERNO"){//Si la opcion del combo seleccionada es INTERNO
	 	//Que no muestre los elementos de la etiqueta y el componente
		document.getElementById("etiquetaEmp").style.visibility="hidden";
		document.getElementById("componenteHTMLEmp").style.visibility="hidden";	
	}
	else{//De los contrario que los muestre ya que la propiedad que tiene es "visible"
		document.getElementById("etiquetaEmp").style.visibility="visible";
		document.getElementById("componenteHTMLEmp").style.visibility="visible";		
		
	}
}


//Restablecer el formulario de seleccion de tipo de Clasificacion de registro de Radiografias
function limpiarRegHistorialClinico(){
	document.getElementById("etiqueta").innerHTML = "&nbsp;";
	document.getElementById("componenteHTML").innerHTML = "&nbsp;";	
}


//Funcion que permite abrir una ventana emergente para realizar los registros de loa antecendetes medicos del trabjador dentro del historial clinico
function abrirHistorialFamiliar(){
	//Al llamar a la ventana le enviamos el valor de la variable por el GET
		window.open('verHistorialFamiliar.php','_blank','top=50, left=50, width=1000, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
}

//Esta funcion calcula el IMC(Indice de Masa Corporal) la cual se calcula de la siguiente manera => (talla)*(talla) = res ==>Peso/Talla
function calcularIMC(){
	//Recuperar datos del Formulario para hacer los calculos
	var peso = document.getElementById("txt_peso").value;	
	var talla = document.getElementById("txt_talla").value;
	var imc = 0;
	//Calcular la talla al cuadrado y quitarle las (,) en caso de que las traiga
	talla=Math.pow(parseFloat(talla.replace(/,/g,'')),2);
	//Si estan diponibles los datos necesarios para obtener el IMC, proceder a realizar los calculos
	if(peso!="" && talla!=""){	
		//Calcular el imc y quitarle las comas(,) al peso en caso de que las traiga
		imc = parseFloat(peso.replace(/,/g,''))/ talla ;
		formatCurrency(imc,'txt_imc');
	}
}//Cierre de la funcion calcularIMC()


//Funcion que valida que cada uno de los campos con los que cuenta la ventana donde se ingresa el historial familiar se encuentren completos
function valFormHistorialFamiliar(frm_registrarHistorialFamiliar){
	//Variable que controla el proceso de validacion de formularios
	var band=1;
	//Variable utilizada para la validacion del Check que abre la ventana correspondiente
	var nomCkb = document.getElementById("hdn_nomCheckBox").value;
	
	if(frm_registrarHistorialFamiliar.txt_talla.value==""){
		band=0;
		alert("Ingresar la Talla del Trabajador");
	}
	if(frm_registrarHistorialFamiliar.txt_peso.value==""&&band==1){
		band=0;
		alert("Ingresar el Peso del Trabajador");
	}
	if(frm_registrarHistorialFamiliar.txt_pulso.value==""&&band==1){
		band=0;
		alert("Ingresar el Pulso del Trabajador");
	}
	if(frm_registrarHistorialFamiliar.txt_resp.value==""&&band==1){
		band=0;
		alert("Ingresar la Respiración del Trabajador");
	}
	if(frm_registrarHistorialFamiliar.txt_temp.value==""&&band==1){
		band=0;
		alert("Ingresar la Temperatura del Trabajador");
	}
	if(frm_registrarHistorialFamiliar.txt_presArt.value==""&&band==1){
		band=0;
		alert("Ingresar la Presión Arterial del Trabajador");
	}
	if(frm_registrarHistorialFamiliar.txa_hisFam.value==""&&band==1){
		band=0;
		alert("Registrar el Historial Familiar del Trabajador");
	}
	/*if(frm_registrarHistorialFamiliar.txa_ant.value==""&&band==1){
		band=0;
		alert("Registrar los Antecedentes del Trabajador");
	}*/
	if(frm_registrarHistorialFamiliar.txa_hisMedicaAnt.value==""&&band==1){
		band=0;
		alert("Registrar los Antecedentes Medicos del Trabajador");
	}
	if(frm_registrarHistorialFamiliar.txa_antPP.value==""&&band==1){
		band=0;
		alert("Registrar los Antecentes P.P del Trabajador");
	}
	if(frm_registrarHistorialFamiliar.txt_secuelas.value==""&&band==1){
		band=0;
		alert("Ingresar el Enf. Prof. y/o Secuelas del Trabajador");
	}
	if(frm_registrarHistorialFamiliar.txt_spo2.value==""&&band==1){
		band=0;
		alert("Ingresar el SpO2");
	}
		
	if(band==0){
	//Si el formulario que se encuentra dentro de la ventana emergente verAntPatologicos no pasa que deseleccione el check y que ademas la habilite
		document.getElementById("hdn_botonCancelar").value = "";
		return false;
	}
	else{
		return true;
	}
}

//Funcion que permite abrir una ventana emergente para realizar los registros de loa antecendetes NO patologicos del paciente
function abrirAntNoPatalogicos(){
	//Al llamar a la ventana le enviamos el valor de la variable por el GET
	window.open('verAntNoPatologicos.php','_blank','top=50, left=50, width=900, height=400, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
}

//Funcion que valida que cada uno de los campos con los que cuenta la ventana donde se ingresa el historial familiar se encuentren completos
function valFormAntNoPatologicos(frm_antPatologicos){
	//Variable que controla el proceso de validacion de formularios
	var band=1;
	//Variable utilizada para la validacion del Check que abre la ventana correspondiente
	var nomCkb = document.getElementById("hdn_nomCheckBox").value;

	
	if(frm_antPatologicos.cmb_actividad.value==""&&band==1){
		band=0;
		alert("Seleccionar el Grado de Actividad del Trabajador");
	}
	if(frm_antPatologicos.cmb_tabaquismo.value==""&&band==1){
		band=0;
		alert("Seleccionar el Grado de Tabaquismo del Trabajador");
	}
	if(frm_antPatologicos.cmb_etilismo.value==""&&band==1){
		band=0;
		alert("Seleccionar el Grado de Etilismo del Trabajador");
	}
	if(frm_antPatologicos.txt_otrasAdicciones.value==""&&band==1){
		band=0;
		alert("Ingresar si se tienen otra Adicciones");
	}
		
	if(band==0){
	//Si el formulario que se encuentra dentro de la ventana emergente verAntPatologicos no pasa que deseleccione el check y que ademas la habilite
		document.getElementById("hdn_botonCancelar").value = "";
		return false;
	}
	else{
		return true;
	}	
}




//Funcion que permite abrir una ventana emergente para realizar los registros de loa antecendetes medicos del trabjador dentro del historial clinico
function abrirHistorialTrabajo(){
	//Al llamar a la ventana le enviamos el valor de la variable por el GET
	window.open('verHistorialTrabajo.php','_blank','top=50, left=50, width=900, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
}


//Funcion que valida que cada uno de los campos con los que cuenta la ventana donde se ingresa el historial familiar se encuentren completos
function valFormHistorialTrabajo(frm_registrarHisTrabajo){
	//Variable que controla el proceso de validacion de formularios
	var band=1;
	
	if(frm_registrarHisTrabajo.hdn_botonSeleccionado.value=="agregar"){

	
		if(frm_registrarHisTrabajo.txt_lugar.value==""&&band==1){
			band=0;
			alert("Registrar el Lugar de Trabajo");
		}
		if(frm_registrarHisTrabajo.txt_tipoTrab.value==""&&band==1){
			band=0;
			alert("Registrar el Tipo de Trabajo Realizado");
		}
		if(frm_registrarHisTrabajo.txt_tiempo.value==""&&band==1){
			band=0;
			alert("Ingresar el Tiempo que Realizo el Trabajo");
		}
		if((!frm_registrarHisTrabajo.ckb_ergonomia.checked&&!frm_registrarHisTrabajo.ckb_luz.checked&&
		   !frm_registrarHisTrabajo.ckb_polvo.checked&&!frm_registrarHisTrabajo.ckb_ruido.checked&&
		   !frm_registrarHisTrabajo.ckb_sedentarismo.checked&&!frm_registrarHisTrabajo.ckb_vibracion.checked)&&band==1){
			band=0;
			alert("Seleccionar por lo Menos una Condición Especial");
		}
		if(band==0)
			return false;
		else
			return true;
	}
	if(frm_registrarHisTrabajo.hdn_botonSeleccionado.value=="finalizar"){
		if(band==0){
			//Si el formulario que se encuentra dentro de la ventana emergente verAntPatologicos no pasa que deseleccione el check y que ademas la habilite
			document.getElementById("hdn_botonSeleccionado").value = "";
			return false;
		}
		else{
			return true;
		}	
	}
}

function valFormModificarHistorialTrabajo(frm_registrarHisTrabajo){
	//Variable que controla el proceso de validacion de formularios
	var band=1;
	
	if(frm_registrarHisTrabajo.hdn_botonSeleccionado.value=="agregar"){

	
		if(frm_registrarHisTrabajo.txt_lugar.value==""&&band==1){
			band=0;
			frm_registrarHisTrabajo.txt_lugar.focus();
			alert("Registrar el Lugar de Trabajo");
		}
		if(frm_registrarHisTrabajo.txt_tipoTrab.value==""&&band==1){
			band=0;
			frm_registrarHisTrabajo.txt_tipoTrab.focus();
			alert("Registrar el Tipo de Trabajo Realizado");
		}
		if(frm_registrarHisTrabajo.txt_tiempo.value==""&&band==1){
			band=0;
			frm_registrarHisTrabajo.txt_tiempo.focus();
			alert("Ingresar el Tiempo que Realizo el Trabajo");
		}
		if((!frm_registrarHisTrabajo.ckb_ergonomia.checked&&!frm_registrarHisTrabajo.ckb_luz.checked&&
		   !frm_registrarHisTrabajo.ckb_polvo.checked&&!frm_registrarHisTrabajo.ckb_ruido.checked&&
		   !frm_registrarHisTrabajo.ckb_sedentarismo.checked&&!frm_registrarHisTrabajo.ckb_vibracion.checked)&&band==1){
			band=0;
			alert("Seleccionar por lo Menos una Condición Especial");
		}
		if(band==0)
			return false;
		else
			return true;
	}
}



//Funcion que permite abrir una ventana emergente para realizar los registros de loa antecendetes medicos del trabjador dentro del historial clinico
function abrirAspectosGrales1(){
	//Al llamar a la ventana le enviamos el valor de la variable por el GET
		window.open('verAspectosGenerales1.php','_blank','top=50, left=50, width=900, height=450, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
}


//Esta funcion calcula el HCB(Hipocucia Bilateral Combinada) la cual se calcula de la siguiente manera =
/*function calcularHCB(){
	//Recuperar datos del Formulario para hacer los calculos
	//var hcb = document.getElementById("txt_hbc").value;	
	var dato1 = document.getElementById("txt_audDer").value;	
	var dato2 = document.getElementById("txt_audIzq").value;	
	var hcb = 0;
	
	//Si estan diponibles los datos necesarios para obtener el HCB, proceder a realizar los calculos
	if(dato1!="" && dato2!=""){	
		//Si el datos1 es MAYOR que el dato2, entonces realizar las operaciones siguientes
		if(dato1>dato2){
			//El (dato2 * 7)+(dato1 / 8)=HBC(Hipocucia Bilaterial Combinada)
			hcb = parseFloat(dato2.replace(/,/g,'') * 7 + parseFloat(dato1.replace(/,/g,''))/8);
			formatCurrency(hcb,'txt_hbc');
		}
		//Si el datos1 es MENOR que el dato2, entonces realizar las operaciones siguientes
		if(dato1<dato2){
			//El (dato1 * 7)+(dato2 / 8)=HBC(Hipocucia Bilaterial Combinada)
			hcb = parseFloat(dato1.replace(/,/g,'') * 7 + parseFloat(dato2.replace(/,/g,''))/8);
			formatCurrency(hcb,'txt_hbc');
		}
		//Si el dato1 es igual al dato2, entonces el resultado siempre va ser el mismo resultado
		if(dato1==dato2){
			hcb = parseFloat(dato1.replace(/,/g,''));
			formatCurrency(hcb,'txt_hbc');
		}
	}
}//Cierre de la funcion calcularHCB()
*/


//Funcion que valida que cada uno de los campos con los que cuenta la ventana donde se ingresa el historial familiar se encuentren completos
function valFormAspectosGrales1(frm_regAspGrales1){
	//Variable que controla el proceso de validacion de formularios
	var band=1;
	//Variable utilizada para la validacion del Check que abre la ventana correspondiente
	var nomCkb = document.getElementById("hdn_nomCheckBox").value;
	
	if(frm_regAspGrales1.txt_tipoGral.value==""&&band==1){
		band=0;
		alert("Ingresar el Tipo General");
	}
	if(frm_regAspGrales1.txt_nutricion.value==""&&band==1){
		band=0;
		alert("Ingresar el Tipo de Nutrición");
	}
	if(frm_regAspGrales1.txt_piel.value==""&&band==1){
		band=0;
		alert("Ingresar el Tipo de Piel");
	}
	if(frm_regAspGrales1.cmb_lentes.value==""&&band==1){
		band=0;
		alert("Seleccionar si la Prueba se realizo con Lentes");
	}
	if(frm_regAspGrales1.txt_visionDer.value==""&&band==1){
		band=0;
		alert("Ingresar el Resultado de Visión del Ojo Derecho");
	}
	if(frm_regAspGrales1.txt_visionIzq.value==""&&band==1){
		band=0;
		alert("Ingresar el Resultado de Visión del Ojo Izquierdo");
	}
	if(frm_regAspGrales1.txt_refDer.value==""&&band==1){
		band=0;
		alert("Ingresar Reflejos del Ojo Derecho");
	}
	if(frm_regAspGrales1.txt_refIzq.value==""&&band==1){
		band=0;
		alert("Ingresar Reflejos del Ojo Izquierdo");
	}
	if(frm_regAspGrales1.txt_pterDer.value==""&&band==1){
		band=0;
		alert("Ingresar Resultados de Pterygiones del Ojo Derecho");
	}
	if(frm_regAspGrales1.txt_pterIzq.value==""&&band==1){
		band=0;
		alert("Ingresar Resultados de Pterygiones del Ojo Izquierdo");
	}
	if(frm_regAspGrales1.txt_otrosDer.value==""&&band==1){
		band=0;
		alert("Ingresar Otros Resultados del Ojo Derecho");
	}
	if(frm_regAspGrales1.txt_otrosIzq.value==""&&band==1){
		band=0;
		alert("Ingresar Otros Resultados del Ojo Izquierdo");
	}
	if(frm_regAspGrales1.txt_audDer.value==""&&band==1){
		band=0;
		alert("Ingresar Resultados de la Audición del Oído Derecho");
	}
	if(frm_regAspGrales1.txt_audIzq.value==""&&band==1){
		band=0;
		alert("Ingresar Resultados de la Audición del Oído Izquierdo");
	}
	if(frm_regAspGrales1.txt_canalDer.value==""&&band==1){
		band=0;
		alert("Ingresar Resultados del Canal Derecho");
	}
	if(frm_regAspGrales1.txt_canalIzq.value==""&&band==1){
		band=0;
		alert("Ingresar Resultados del Canal Izquierdo");
	}
	if(frm_regAspGrales1.txt_memDer.value==""&&band==1){
		band=0;
		alert("Ingresar Resultados de la Membrana Derecha");
	}
	if(frm_regAspGrales1.txt_memIzq.value==""&&band==1){
		band=0;
		alert("Ingresar Resultados de la Membrana Izquierdo");
	}
	if(frm_regAspGrales1.txt_hbc.value==""&&band==1){
		band=0;
		alert("Ingresar la HBC");
	}
	if(frm_regAspGrales1.cmb_tipo.value==""&&band==1){
		band=0;
		alert("Seleccionar el Tipo");
	}
	if(frm_regAspGrales1.txt_ipp.value==""&&band==1){
		band=0;
		alert("El Porcentaje del IPP no Puede ser Vacio");
	}
		
	if(band==0){
	//Si el formulario que se encuentra dentro de la ventana emergente verAntPatologicos no pasa que deseleccione el check y que ademas la habilite
		document.getElementById("hdn_botonCancelar").value = "";
		return false;
	}
	else{
		return true;
	}
}

//Funcion que permite abrir una ventana emergente para realizar los registros de loa antecendetes medicos del trabjador dentro del historial clinico
function abrirAspectosGrales2(){
	//Al llamar a la ventana le enviamos el valor de la variable por el GET
		window.open('verAspectosGenerales2.php','_blank','top=50, left=50, width=950, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
}


//Funcion que valida que cada uno de los campos con los que cuenta la ventana donde se ingresa el historial familiar se encuentren completos
function valFormAspectosGrales2(frm_regAspGrales2){
	//Variable que controla el proceso de validacion de formularios
	var band=1;
	//Variable utilizada para la validacion del Check que abre la ventana correspondiente
	var nomCkb = document.getElementById("hdn_nomCheckBox").value;
	
	if(frm_regAspGrales2.txt_nariz.value==""&&band==1){
		band=0;
		alert("Ingresar las Características de la Nariz del Trabajador");
	}
	if(frm_regAspGrales2.txt_obstruccion.value==""&&band==1){
		band=0;
		alert("Ingresar la Obstrucción Encontrada");
	}
	if(frm_regAspGrales2.txt_boca.value==""&&band==1){
		band=0;
		alert("Registrar el Diagnostico de la Boca");
	}
	if(frm_regAspGrales2.txt_encias.value==""&&band==1){
		band=0;
		alert("Ingresar el Diagnostico de las Encías");
	}
	if(frm_regAspGrales2.txt_dientes.value==""&&band==1){
		band=0;
		alert("Registrar el Diagnostico de los Dientes");
	}
	if(frm_regAspGrales2.txt_cuello.value==""&&band==1){
		band=0;
		alert("Ingresar el Diagnostico del Cuello");
	}
	if(frm_regAspGrales2.txt_linfaticos.value==""&&band==1){
		band=0;
		alert("Registrar el Diagnostico de los Linfáticos");
	}
	if(frm_regAspGrales2.txt_torax.value==""&&band==1){
		band=0;
		alert("Ingresar el Diagnostico del Torax");
	}
	if(frm_regAspGrales2.txt_corazon.value==""&&band==1){
		band=0;
		alert("Ingresar el Diagnostico del Corazón");
	}
	if(frm_regAspGrales2.txt_pulmones.value==""&&band==1){
		band=0;
		alert("Registrar el Diagnostico de los Pulmones");
	}
	if(frm_regAspGrales2.txt_abdomen.value==""&&band==1){
		band=0;
		alert("Ingresar el Diagnostico del Abdomen");
	}
	if(frm_regAspGrales2.txt_higado.value==""&&band==1){
		band=0;
		alert("Registrar el Diagnostico del Higado");
	}
	if(frm_regAspGrales2.txt_bazo.value==""&&band==1){
		band=0;
		alert("Ingresar el Diagnostico del Bazo");
	}
	if(frm_regAspGrales2.txt_pared.value==""&&band==1){
		band=0;
		alert("Registrar el Diagnostico de la Pared");
	}
	if(frm_regAspGrales2.txt_anillos.value==""&&band==1){
		band=0;
		alert("Ingresar el Diagnostico de los Anillos");
	}
	if(frm_regAspGrales2.txt_hernias.value==""&&band==1){
		band=0;
		alert("Registrar el Diagnostico de las Hernias");
	}
	if(frm_regAspGrales2.txt_genUri.value==""&&band==1){
		band=0;
		alert("Ingresar el Diagnostico del Gen. Uri.");
	}
	if(frm_regAspGrales2.txt_hidro.value==""&&band==1){
		band=0;
		alert("Registrar el Diagnostico del Hidrocele");
	}
	if(frm_regAspGrales2.txt_vari.value==""&&band==1){
		band=0;
		alert("Ingresar el Diagnostico de la Varicocele");
	}
	if(frm_regAspGrales2.txt_hemo.value==""&&band==1){
		band=0;
		alert("Ingresar el Diagnostico de las Hemorroides");
	}
	if(frm_regAspGrales2.txt_extSup.value==""&&band==1){
		band=0;
		alert("Registrar el Diagnostico del Extr. Suprs.");
	}
	if(frm_regAspGrales2.txt_extInf.value==""&&band==1){
		band=0;
		alert("Ingresar el Diagnostico del Extr. Infrs.");
	}
	if(frm_regAspGrales2.txt_reflejos.value==""&&band==1){
		band=0;
		alert("Registrar el Diagnostico de los Reflejos O.T.");
	}
	if(frm_regAspGrales2.txt_psiquismo.value==""&&band==1){
		band=0;
		alert("Ingresar el Diagnostico del Psiquismo");
	}
	if(frm_regAspGrales2.txt_sintoma.value==""&&band==1){
		band=0;
		alert("Registrar el Diagnostico del Sintomat. Actual");
	}
		
	if(band==0){
	//Si el formulario que se encuentra dentro de la ventana emergente verAntPatologicos no pasa que deseleccione el check y que ademas la habilite
		document.getElementById("hdn_botonCancelar").value = "";
		return false;
	}
	else{
		return true;
	}
}


//Funcion que permite abrir una ventana emergente para realizar los registros de loa antecendetes medicos del trabjador dentro del historial clinico
function abrirPruebasEsfuerzo(){
	//Al llamar a la ventana le enviamos el valor de la variable por el GET
		window.open('verPruebasEsfuerzo.php','_blank','top=50, left=50, width=800, height=400, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
}
	
//Funcion que valida que cada uno de los campos con los que cuenta la ventana donde se ingresa el historial familiar se encuentren completos
function valFormPruebasEsfuerzo(frm_pruebasEsfzo){
	//Variable que controla el proceso de validacion de formularios
	var band=1;
	//Variable utilizada para la validacion del Check que abre la ventana correspondiente
	var nomCkb = document.getElementById("hdn_nomCheckBox").value;
	
	if(frm_pruebasEsfzo.txt_pulsoRep.value==""&&band==1){
		band=0;
		alert("Ingresar los Resultados del Pulso en Reposo");
	}
	if(frm_pruebasEsfzo.txt_respRep.value==""&&band==1){
		band=0;
		alert("Registrar los Resultados de la Respiración en Reposo");
	}
	if(frm_pruebasEsfzo.txt_pulsoInm.value==""&&band==1){
		band=0;
		alert("Registrar los Resultados del Pulso Inmediatamente Después de la Prueba");
	}
	if(frm_pruebasEsfzo.txt_respInm.value==""&&band==1){
		band=0;
		alert("Ingresar los Resultados de la Repiración Inmediatamente Después de la Prueba");
	}
	if(frm_pruebasEsfzo.txt_pulso1Desp.value==""&&band==1){
		band=0;
		alert("Registrar los Resultados del Pulso 1 Minuto Después de la Prueba");
	}
	if(frm_pruebasEsfzo.txt_resp1Desp.value==""&&band==1){
		band=0;
		alert("Registrar los Resultados de la Respiración 1 Minuto Después de la Prueba");
	}
	if(frm_pruebasEsfzo.txt_pulso2Desp.value==""&&band==1){
		band=0;
		alert("Registrar los Resultados del Pulso 2 Minutos Después de la Prueba");
	}
	if(frm_pruebasEsfzo.txt_resp2Desp.value==""&&band==1){
		band=0;
		alert("Registrar los Resultados de la Respiración 2 Minutos Después de la Prueba");
	}
		
	if(band==0){
	//Si el formulario que se encuentra dentro de la ventana emergente verAntPatologicos no pasa que deseleccione el check y que ademas la habilite
		document.getElementById("hdn_botonCancelar").value = "";
		return false;
	}
	else{
		return true;
	}
}



//Funcion que permite abrir una ventana emergente para realizar los registros de loa antecendetes medicos del trabjador dentro del historial clinico
function abrirPruebasLaboratorio(){
	//Al llamar a la ventana le enviamos el valor de la variable por el GET
		window.open('verPruebasLaboratorio.php','_blank','top=50, left=50, width=900, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
}


//Funcion que valida que cada uno de los campos con los que cuenta la ventana donde se ingresa el historial familiar se encuentren completos
function valFormPruebasLab(frm_pruebasLab){
	//Variable que controla el proceso de validacion de formularios
	var band=1;
	//Variable utilizada para la validacion del Check que abre la ventana correspondiente
	var nomCkb = document.getElementById("hdn_nomCheckBox").value;
	
	if(frm_pruebasLab.txt_glicemia.value==""&&band==1){
		band=0;
		alert("Ingresar los Resultados de la Glicemia");
	}
	/*if(frm_pruebasLab.txt_hiv.value==""&&band==1){
		band=0;
		alert("Registrar los Resultados del HIV");
	}*/
	if(frm_pruebasLab.txt_tg.value==""&&band==1){
		band=0;
		alert("Ingresar los Resultados deLl TG");
	}
	if(frm_pruebasLab.txt_colesterol.value==""&&band==1){
		band=0;
		alert("Registrar los Resultados del Colesterol");
	}
	/*if(frm_pruebasLab.txt_espirometria.value==""&&band==1){
		band=0;
		alert("Ingresar los Resultados de la Espirometria");
	}*/
	if(frm_pruebasLab.txt_diagLab.value==""&&band==1){
		band=0;
		alert("Ingresar el Diagnostico de Laboratorio");
	}
	if(frm_pruebasLab.txt_rxTorax.value==""&&band==1){
		band=0;
		alert("Registrar los Resultados de los RX. del Tórax");
	}
	if(frm_pruebasLab.txt_alcoholimetro.value==""&&band==1){
		band=0;
		alert("Ingresar los Resultados del Alcoholimetro");
	}
	if(frm_pruebasLab.txt_colLum.value==""&&band==1){
		band=0;
		alert("Registrar los Resultados de la Col. Lumbrosaca");
	}
	if(frm_pruebasLab.txt_diagnostico.value==""&&band==1){
		band=0;
		alert("Ingresar el Diagnostico General");
	}
	if(frm_pruebasLab.txt_conclusiones.value==""&&band==1){
		band=0;
		alert("Registrar las Conclusiones");
	}
	if(frm_pruebasLab.txt_edoSalud.value==""&&band==1){
		band=0;
		alert("Registrar el Estado de Salud");
	}
		
	if(band==0){
	//Si el formulario que se encuentra dentro de la ventana emergente verAntPatologicos no pasa que deseleccione el check y que ademas la habilite
		document.getElementById("hdn_botonCancelar").value = "";
		return false;
	}
	else{
		return true;
	}
}


//Funcion que permite saber si se cancelo el regsitro de cada una de las secciones de las que consta el historial medico
function cancelarRegistrosHistorialClinico(){
	//Obtener el nombre del Boton seleccionada
	var boton = document.getElementById("hdn_botonCancelar").value;
	//Obtener el nombre del CheckBox que será deshabilitado
	var nomCkb = document.getElementById("hdn_nomCheckBox").value;
		
	if(boton=="cancelar"){
		window.opener.document.getElementById(nomCkb).checked = false;
		window.opener.document.getElementById(nomCkb).disabled = false;
		window.close();
	}
	if(boton=="guardar"){
		window.opener.document.getElementById(nomCkb).checked = true;
		window.opener.document.getElementById(nomCkb).disabled = true;
		window.close();
	}
	if(boton=="finalizar"){
		window.opener.document.getElementById(nomCkb).checked = true;
		window.opener.document.getElementById(nomCkb).disabled = true;
		window.close();
	}
}


//Funcion que se encarga de validar el formulario donde se agrega los datos generales del historial clinico
function valFormRegHistorialClinico(frm_historialClinico){

	//Si el valor se mantiene en 1, el proceso de validación fue satisfactorio
	var band = 1;

	//Extraer los datos de la fecha de Ingreso, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_historialClinico.txt_fechaNac.value.substr(0,2);
	var iniMes=frm_historialClinico.txt_fechaNac.value.substr(3,2);
	var iniAnio=frm_historialClinico.txt_fechaNac.value.substr(6,4);
	
	//Extraer los datos de la fecha de Salida, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_historialClinico.hdn_fechaAct.value.substr(0,2);
	var finMes=frm_historialClinico.hdn_fechaAct.value.substr(3,2);
	var finAnio=frm_historialClinico.hdn_fechaAct.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);


	//Verificar que el año de Fin sea mayor al de Inicio
	if(fechaIni>=fechaFin){
		alert ("La Fecha de Nacimiento no Puede ser Igual a la Fecha de Actual del Registro");
		band=0;
	}
	

	//Verificar que el campo de Nombre de la Proyeccion no este vacío
	/*if(frm_historialClinico.txt_numSS.value==""&& band==1){
		alert("Ingresar el Número del Seguro Social");
		band = 0;
	}*/
	
	//Verificar que el campo de Nombre de la Proyeccion no este vacío
	if(frm_historialClinico.txt_puesto.value==""&& band==1){
		alert("Ingresar el Puesto del Trabajador");
		band = 0;
	}
	//Verificar que el campo de Nombre de la Proyeccion no este vacío
	if(frm_historialClinico.txt_nombre.value==""&& band==1){
		alert("Ingresar el Nombre del Trabajador");
		band = 0;
	}
	//Verificar que el campo de Nombre de la Proyeccion no este vacío
	if(frm_historialClinico.cmb_sexo.value==""&& band==1){
		alert("Seleccionar el Sexo del Trabajador");
		band = 0;
	}
	//Verificar que el campo de Nombre de la Proyeccion no este vacío
	if(frm_historialClinico.txt_edad.value==""&& band==1){
		alert("Ingresar la Edad del Trabajador");
		band = 0;
	}
	if(band==1){
		if(!validarEntero(frm_historialClinico.txt_edad.value,"La Edad"))
			band=0;
	}
	
	//Verificar que el campo de Nombre de la Proyeccion no este vacío
	/*if(frm_historialClinico.txt_numEmp.value==""&& band==1){
		alert("Ingresar el Número del Empleado");
		band = 0;
	}*/
	//Verificar que el campo de Nombre de la Proyeccion no este vacío
	if(frm_historialClinico.txt_reside.value==""&& band==1){
		alert("Ingresar el Lugar donde Reside el Trabajador");
		band = 0;
	}
	//Verificar que el campo de Nombre de la Proyeccion no este vacío
	if(frm_historialClinico.txt_originario.value==""&& band==1){
		alert("Ingresar el Lugar de donde es Originario el Trabajador");
		band = 0;
	}
	//Verificar que el campo de Nombre de la Proyeccion no este vacío
	if(frm_historialClinico.cmb_edoCivil.value==""&& band==1){
		alert("Seleccionar el Estado Civil del Trabajador");
		band = 0;
	}
	//Verificar que el campo de Nombre de la Proyeccion no este vacío
	if(frm_historialClinico.txt_domicilio.value==""&& band==1){
		alert("Ingresar el Domicilio del Trabajador");
		band = 0;
	}
	//Verificar que el campo de Nombre de la Proyeccion no este vacío
	if(frm_historialClinico.txt_escolaridad.value==""&& band==1){
		alert("Ingresar la Escolaridad del Trabajador");
		band = 0;
	}
	//Verificar que el campo de Nombre de la Proyeccion no este vacío
	if(frm_historialClinico.cmb_claveEsc.value==""&& band==1){
		alert("Selecionar la Clave de Escolaridad correspondiente del Trabajador");
		band = 0;
	}
	//Verificar que el campo de Nombre de la Proyeccion no este vacío
	if(frm_historialClinico.txt_empresa.value==""&& band==1){
		alert("Ingresar la Razón Social de la Nueva Empresa");
		band = 0;
	}
	//Verificar que el campo de Nombre de la Proyeccion no este vacío
	if(frm_historialClinico.txt_razSocial.value==""&& band==1){
		alert("Ingresar la Razón Social de la Nueva Empresa");
		band = 0;
	}
	
	//Verificar que el campo de Nombre de la Proyeccion no este vacío
	/*if(frm_historialClinico.ckb_historialFam.checked=="" && band==1){
		alert("Registrar el Historial Familiar del Trabajador");
		band = 0;
	}
	
	//Verificar que el campo de Nombre de la Proyeccion no este vacío
	if(frm_historialClinico.ckb_historialFam.disabled==false&&band==1){
		alert("El Registro de los Datos del Historial Familiar NO esta Completo");
		frm_historialClinico.ckb_historialFam.checked=false;
		band = 0;
	}
		
	//Verificar que el campo de Nombre de la Proyeccion no este vacío
	if(frm_historialClinico.ckb_aspetosGrales1.checked=="" && band==1){
		alert("Registrar los Aspectos Generales/1 del Trabajador");
		band = 0;
	}
	
		//Verificar que el campo de Nombre de la Proyeccion no este vacío
	if(frm_historialClinico.ckb_aspetosGrales1.disabled==false&&band==1){
		alert("El Registro de los Datos de los Aspectos Grales I NO esta Completo");
		frm_historialClinico.ckb_aspetosGrales1.checked=false;
		band = 0;
	}
	
	//Verificar que el campo de Nombre de la Proyeccion no este vacío
	if(frm_historialClinico.ckb_aspetosGrales2.checked==""&& band==1){
		alert("Registrar los Aspectos Generales/2 del Trabajador");
		band = 0;
	}

	//Verificar que el campo de Nombre de la Proyeccion no este vacío
	if(frm_historialClinico.ckb_aspetosGrales2.disabled==false&&band==1){
		alert("El Registro de los Datos de los Aspectos Grales II NO esta Completo");
		frm_historialClinico.ckb_aspetosGrales2.checked=false;
		band = 0;
	}

	//Verificar que el campo de Nombre de la Proyeccion no este vacío
	if(frm_historialClinico.ckb_antPatologicos.checked==""&& band==1){
		alert("Registrar los Antecedentes No Patologicos del Trabajador");
		band = 0;
	}
	
		//Verificar que el campo de Nombre de la Proyeccion no este vacío
	if(frm_historialClinico.ckb_antPatologicos.disabled==false&&band==1){
		alert("El Registro de los Antecedentes No Patológicos NO esta Completo");
		frm_historialClinico.ckb_antPatologicos.checked=false;
		band = 0;
	}
	
	/*if(band==1 && !frm_historialClinico.ckb_antPatologicos.checked){
		//if(frm_historialClinico.ckb_antPatologicos.disabled ){
			band=0;
			alert("Seleccionar la Unidad de Despacho del Medicamento");
		//}
	}*/

//document.getElementById("ckb_nuevaEmpresa").disabled=true;

	//Verificar que el campo de Nombre de la Proyeccion no este vacío
	/*if(frm_historialClinico.ckb_pruebasEsfuerzo.checked==""&& band==1){
		alert("Registrar los Resultados de las Pruebas de Esfuerzo");
		band = 0;
	}
	
	//Verificar que el campo de Nombre de la Proyeccion no este vacío
	if(frm_historialClinico.ckb_pruebasEsfuerzo.disabled==false&&band==1){
		alert("El Registro de los Datos de las Pruebas de Esfuerzo NO esta Completo");
		frm_historialClinico.ckb_pruebasEsfuerzo.checked=false;
		band = 0;
	}*/
	
	//Verificar que el campo de Nombre de la Proyeccion no este vacío
	/*if(frm_historialClinico.ckb_hisTrabajo.checked==""&& band==1){
		alert("Registrar el Historial de Trabajo del Empleado");
		band = 0;
	}
	
	//Verificar que el campo de Nombre de la Proyeccion no este vacío
	if(frm_historialClinico.ckb_hisTrabajo.disabled==false&&band==1){
		alert("El Registro de los Datos del Historial de Trabajo NO esta Completo");
		frm_historialClinico.ckb_hisTrabajo.checked=false;
		band = 0;
	}
	
	//Verificar que el campo de Nombre de la Proyeccion no este vacío
	if(frm_historialClinico.ckb_pruebasLab.checked==""&& band==1){
		alert("Registrar Resultados de las Pruebas de Laboratorio");
		band = 0;
	}
	
		//Verificar que el campo de Nombre de la Proyeccion no este vacío
	if(frm_historialClinico.ckb_pruebasLab.disabled==false&&band==1){
		alert("El Registro de los Datos de las Pruebas de Laboratorio NO esta Completo");
		frm_historialClinico.ckb_pruebasLab.checked=false;
		band = 0;
	}*/
		
	if(band==1)
		return true;
	else
		return false;
}


//Funcion que valida si una Edad es mayor a 18
function validarEdad(caja_texto){
	var valor=caja_texto.value;
	if (valor<18){
		alert("La Edad Debe ser Mayor a 18 Años");
		caja_texto.value="";
	}
}
/***************************************************************************************************************************************************/
/*************************************************************CONSULTAR HISTORIAL MEDICO************************************************************/
/***************************************************************************************************************************************************/

//Funcion que valida que cada uno de los campos con los que cuenta la ventana donde se ingresa el historial familiar se encuentren completos
function valFormConExamenTipo(frm_consultarExamenTipo){
	//Variable que controla el proceso de validacion de formularios
	var band=1;
	
	if(frm_consultarExamenTipo.cmb_tipoClasificacion.value==""&&band==1){
		band=0;
		alert("Seleccionar el Tipo de Examen");
	}
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_consultarExamenTipo.txt_fechaTipoIni.value.substr(0,2);
	var iniMes=frm_consultarExamenTipo.txt_fechaTipoIni.value.substr(3,2);
	var iniAnio=frm_consultarExamenTipo.txt_fechaTipoIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Cierre, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_consultarExamenTipo.txt_fechaTipoFin.value.substr(0,2);
	var finMes=frm_consultarExamenTipo.txt_fechaTipoFin.value.substr(3,2);
	var finAnio=frm_consultarExamenTipo.txt_fechaTipoFin.value.substr(6,4);
	
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaI=new Date(fechaIni);
	fechaF=new Date(fechaFin);

	//Verificar que el año de Fin sea mayor al de Inicio
	if(fechaI>fechaF){
		band=0;
		alert ("La fecha de Inicio no puede ser mayor a la Fecha de Fin");
	}	

	if(band==0)
		return false;
	else
		return true;
}


//Funcion que valida que cada uno de los campos con los que cuenta la ventana donde se ingresa el historial familiar se encuentren completos
function valFormConExamenFecha(frm_consultarExamenFecha){
	//Variable que controla el proceso de validacion de formularios
	var band=1;

	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_consultarExamenFecha.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_consultarExamenFecha.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_consultarExamenFecha.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Cierre, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_consultarExamenFecha.txt_fechaFin.value.substr(0,2);
	var finMes=frm_consultarExamenFecha.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_consultarExamenFecha.txt_fechaFin.value.substr(6,4);
	
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaI=new Date(fechaIni);
	fechaF=new Date(fechaFin);

	//Verificar que el año de Fin sea mayor al de Inicio
	if(fechaI>fechaF){
		band=0;
		alert ("La fecha de Inicio no puede ser mayor a la Fecha de Fin");
	}	

	if(band==0)
		return false;
	else
		return true;
}


/***************************************************************************************************************************************/
/*********************************************************************ALERTAS***********************************************************/
/***************************************************************************************************************************************/

//Funcion para Validar que se seleccione un registro
function valFormResultadosExamenesMedicos(frm_consultarAlertasHisClinico){	
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_consultarAlertasHisClinico.rdb_examen.length==undefined && !frm_consultarAlertasHisClinico.rdb_examen.checked){
		alert("Seleccionar Registro a Complementar");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_consultarAlertasHisClinico.rdb_examen.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_consultarAlertasHisClinico.rdb_examen.length;i++){
			if(frm_consultarAlertasHisClinico.rdb_examen[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar el Trabajador al Cual se le Realizara un Nuevo Examen Clinico");			
	}
	
	if(res==1)
		return true;
	else
		return false;
}

/***************************************************************************************************************************************/
/*********************************************************SOLICITUDES MÉDICAS***********************************************************/
/***************************************************************************************************************************************/

function valFormConsultarSolicitud(frm_consultarSolExaMed){
	if(frm_consultarSolExaMed.cmb_empresa.value==""){
		alert("Seleccionar la Empresa");
		return false;
	}
	else
		return true;
}


//Función que envia los datos de los trabajadores externos de acuerdo a una solicitud medica para posteriormente generar el HISTORIAL CLINICO
function enviarDatosHC(empresa){
	//Primero verificamos que exista un radio seleciconado, esta opcion tambien es para cuando exista un solo radio dentro de la consulta
	if(frm_enviarDatosHCExterno.rdb_empExt.checked){
		//asignamos el valor del radio a la variable empleado
		empleado = frm_enviarDatosHCExterno.rdb_empExt.value;
	}
	else{//De lo contrario recorremos los radios que existan con un for(){}, para verificar cual es el que esta seleccionado.
		for(i=0;i<frm_enviarDatosHCExterno.rdb_empExt.length;i++){	
			if(frm_enviarDatosHCExterno.rdb_empExt[i].checked){
				empleado = 	frm_enviarDatosHCExterno.rdb_empExt[i].value;
				break;
			}
		}	
	}
	//Aqui partimos la cadena ya que el numero de solicitud y la clave del radio vienen concatenados dentro de la misma variable, al cual la separara un guion(_)
	datos = empleado.split("_");
	//Colocamos dentro de la variable empleado la posicion 1, ya que dentro de esta posicion viene la clave del radio
	empleado = datos[1];
	//Colocamos en la variable solicitud la posición 0, ya que dentro de esta posicion viene la clave de la solicitud.
	solicitud = datos[0];
	//Mandamos el valores de las variables a la pagina frm_generarHCExterno, para posteriormente guardar los datos correspondientes.	
	location.href = 'frm_generarHCExterno.php?idEmpleado='+empleado+'&idEmpresa='+empresa+'&solicitud='+solicitud;		

}

/***************************************************************************************************************************************/
/*********************************************************DOCK DE LA CLINICA************************************************************/
/***************************************************************************************************************************************/


//Funcion que permite seleccionar la pagina que ah de mostrarse  dependiendo del valor seleccionado
function selConsulta(){
	var combo = document.getElementById("cmb_tipoConsulta").value;
	if(combo=='DATOS GENERALES EMPLEADO'){		
		location.href = 'frm_consultarInfEmpRH.php';	
	}
	if(combo=='INCAPACIDADES EMPLEADO'){		
		location.href = 'frm_conIncapacidadesRH.php';	
	}
}


//Funcion que valida que el reporte de Actividades semanales se pueda consultar de forma correcta y de acuerdo al rango de fechas selecccionadas
function valFormDockClinica(frm_detalleIncapacidades){
	//Variable que controla el proceso de validacion de formularios
	var band=1;

	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_detalleIncapacidades.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_detalleIncapacidades.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_detalleIncapacidades.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Cierre, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_detalleIncapacidades.txt_fechaFin.value.substr(0,2);
	var finMes=frm_detalleIncapacidades.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_detalleIncapacidades.txt_fechaFin.value.substr(6,4);
	
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaI=new Date(fechaIni);
	fechaF=new Date(fechaFin);

	//Verificar que el año de Fin sea mayor al de Inicio
	if(fechaI>fechaF){
		band=0;
		alert ("La fecha de Inicio no puede ser mayor a la Fecha de Fin");
	}	

	if(band==0)
		return false;
	else
		return true;
}


/***************************************************************************************************************************************/
/*********************************************************REPORTES** CLINICA************************************************************/
/***************************************************************************************************************************************/

//Funcion que valida que el reporte de Actividades semanales se pueda consultar de forma correcta y de acuerdo al rango de fechas selecccionadas
function valFormRptActividadesSemanales(frm_reporteActSemanal){
	//Variable que controla el proceso de validacion de formularios
	var band=1;

	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_reporteActSemanal.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_reporteActSemanal.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_reporteActSemanal.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Cierre, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_reporteActSemanal.txt_fechaFin.value.substr(0,2);
	var finMes=frm_reporteActSemanal.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_reporteActSemanal.txt_fechaFin.value.substr(6,4);
	
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaI=new Date(fechaIni);
	fechaF=new Date(fechaFin);

	//Verificar que el año de Fin sea mayor al de Inicio
	if(fechaI>fechaF){
		band=0;
		alert ("La fecha de Inicio no puede ser mayor a la Fecha de Fin");
	}	

	if(band==0)
		return false;
	else
		return true;
}


//Funcion que valida que el reporte de Actividades semanales se pueda consultar de forma correcta y de acuerdo al rango de fechas selecccionadas
function valFormRepHistorialesClinicos(frm_detalleHistorialClinico){
	//Variable que controla el proceso de validacion de formularios
	var band=1;

	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_detalleHistorialClinico.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_detalleHistorialClinico.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_detalleHistorialClinico.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Cierre, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_detalleHistorialClinico.txt_fechaFin.value.substr(0,2);
	var finMes=frm_detalleHistorialClinico.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_detalleHistorialClinico.txt_fechaFin.value.substr(6,4);
	
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaI=new Date(fechaIni);
	fechaF=new Date(fechaFin);

	//Verificar que el año de Fin sea mayor al de Inicio
	if(fechaI>fechaF){
		band=0;
		alert ("La fecha de Inicio no puede ser mayor a la Fecha de Fin");
	}	

	if(band==0)
		return false;
	else
		return true;
}

//Funcion que valida que el reporte de Actividades semanales se pueda consultar de forma correcta y de acuerdo al rango de fechas selecccionadas
function valFormRepCensosConsultas(frm_detalleCensosConsultas){
	//Variable que controla el proceso de validacion de formularios
	var band=1;

	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_detalleCensosConsultas.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_detalleCensosConsultas.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_detalleCensosConsultas.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Cierre, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_detalleCensosConsultas.txt_fechaFin.value.substr(0,2);
	var finMes=frm_detalleCensosConsultas.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_detalleCensosConsultas.txt_fechaFin.value.substr(6,4);
	
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaI=new Date(fechaIni);
	fechaF=new Date(fechaFin);

	//Verificar que el año de Fin sea mayor al de Inicio
	if(fechaI>fechaF){
		band=0;
		alert ("La fecha de Inicio no puede ser mayor a la Fecha de Fin");
	}	

	if(band==0)
		return false;
	else
		return true;
}



function valFormSelResultadosExamen(frm_selResultadosExamen){
	var res=1;
	var cantidad=frm_selResultadosExamen.hdn_cantCkb.value;

	var ctrl=1;
	
	//if(document.getElementById("ckb_resRepTodos").checked){
		while(ctrl<=cantidad){		
			//Crear el id del CheckBox que se quiere verificar
			idCheckBox="ckb_resRep"+ctrl.toString();
			//Verificar que la cantidad y la aplicación del Checkbox seleccionado no esten vacias
			if(document.getElementById(idCheckBox).checked)
				status = 1;		
			ctrl++;
		}//Fin del While	
				
		//Verificar que al menos un check haya sido seleccionado
		if(status!=1){
			alert("Seleccionar al Menos un Registro");
			res = 0;
		}
	//}
	if(res==1){
		//document.getElementById("hdn_cerrar").value='0';
		return true;
	}
	else
		return false;
}

//Funcion que activa o desactiva los registros de los resultados de examenes medicos de acuerdo a la eleccion de estos
function activarCkbRep(ckb, num){
	
	if(ckb.checked){
		document.getElementById("txa_resultado"+num).disabled=false;
		document.getElementById("txa_recomendacion"+num).disabled=false;
		document.getElementById("txa_imss"+num).disabled=false;	
	}else{
		document.getElementById("txa_resultado"+num).disabled=true;
		document.getElementById("txa_recomendacion"+num).disabled=true;
		document.getElementById("txa_imss"+num).disabled=true;	
	}
	
	
	
}
