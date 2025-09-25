/**
  * Nombre del M�dulo: Mantenimiento                                               
  * �Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 21/Febrero/2011                                      			
  * Descripci�n: Este archivo contiene funciones para validar los diferentes formularios del M�dulo Mantenimiento
  */
/**************************************************************************************************************************************************************************/
/*************************************************************************** VALIDAR CARACTERES ***************************************************************************/
/**************************************************************************************************************************************************************************/
/*Esta funci�n se encarga de que el usuario no pueda ingresar caracteres invalidos en los campos de los diferentes formulario del M�dulo de Mantenimiento*/
function permite(elEvento, permitidos, te) {
	//te = 0 ==> Teclas Especiales General, te = 1 ==> Teclas Especiales Restringidas, te = 2 ==> Teclas Especiales Completamente Restringidas
	//Variables que definen los caracteres permitidos
	var numeros = "0123456789";
	var caracteres = " abcdefghijklmn�opqrstuvwxyzABCDEFGHIJKLMN�OPQRSTUVWXYZ����������";
	var numeros_caracteres = numeros + caracteres;
	
	//Determinar que Teclas Especiales seran Permitidas segun el campo de texto que se este llenando
	if(te==0){//Campos mas generales como comentarios, observaciones y nombres		
		var teclas_especiales = [8,33,34,35,36,37,38,40,41,42,43,44,45,46,47,58,59,60,61,62,63,64,91,93,95,123,124,125,161,176,191];		
		//8=BackSpace, 33=Admiraci�n Cierre, 34=Comillas, 35=Gato, 36=Signo Moneda, 37=Porcentaje, 38=Amperson, 40=Parentesis Apertura, 41=Parentesis Cierre, 
		//42=Asterisco, 43=Simbolo Mas, 44=Coma, 45=Guion medio, 46=Punto, 47=Diagonal, 58=Dos Puntos, 59=Punto y Coma, 60=Menor Que, 61=Simbolo Igual, 62=Mayor Que,
		//63=Interrogacion Cierre, 64=Arroba, 91=Parentesis Cuad Apertura, 93=Parentesis Cuad Cierre, 95=Guion Bajo, 123=Llave Apertura, 124=|, 125=Llave Cierre, 
		//161=Admiracion Apertura, 176=�Grados, 191=Interregacion Aperura
	}
	if(te==1){//Campos que contengan claves que puedan contener guion medio, punto o diagonal
		var teclas_especiales = [8, 45, 46, 47];
		//8 = BackSpace, 45 = Guion medio, 46 = Punto, 47 = Diagonal
	}
	if(te==2){//Para cajas de texto que contengan valores tipo moneda, solo acepta numeros y el punto
		var teclas_especiales = [8, 46];		
		//8 = BackSpace, 46 = Punto
	}
	if(te==3){//Campo RFC, numero telef�nico, solo acepta numeros o letras o ambos, no permite ningun caracter especial
		var teclas_especiales = [8];		
		//8 = BackSpace
	}
	if(te==4){//Campos que se utilizan para manejar la Busqueda Sphider, Razon Social del Cliente y del Proveedor y el campo de Material o Servicio del Proveedor
		var teclas_especiales = [8,33,35,36,37,38,40,41,42,43,44,45,46,47,58,59,60,61,62,63,64,91,93,95,123,124,125,161,176,191];		
		//8=BackSpace, 33=Admiraci�n Cierre, 35=Gato, 36=Signo Moneda, 37=Porcentaje, 38=Amperson, 40=Parentesis Apertura, 41=Parentesis Cierre, 42=Asterisco, 43=Simbolo Mas,
		//44=Coma, 45=Guion medio, 46=Punto, 47=Diagonal, 58=Dos Puntos, 59=Punto y Coma, 60=Menor Que, 61=Simbolo Igual, 62=Mayor Que, 63=Interrogacion Cierre, 64=Arroba,
		//91=Parentesis Cuad Apertura, 93=Parentesis Cuad Cierre, 95=Guion Bajo, 123=Llave Apertura, 124=|, 125=Llave Cierre, 161=Admiracion Apertura, 176=�Grados,
		//191=Interregacion Aperura
	}
	
	
	// Seleccionar los caracteres a partir del par�metro de la funci�n
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

//Esta funcion valida que una imagen sea valida, tomando en cuenta el tama�o de 1 Kb hasta 10Mb
function validarImagen(campo,bandera) { 
	//Verificar que el campo tenga foto agregada, de lo contrario no hacer la validacion
	if (campo.value!=""){
		//Creamos un elemento DIV
		div = document.createElement("DIV"); 
		//Le damos la propiedad hidden al DIV
		div.style.visibility = "hidden"; 
		//Le asignamos la propiedad scroll al Div para que no se ajuste al tama�o del formulario
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
		//Ocultamos IMG a fin de que no se muestre en la pagina o formulario, Si el archivo subido no es una imagen, el tama�o asignadop sera de 0, 
		//lo cual lo hace una imagen invalida
		img.style.visibility = "hidden"; 
		
		//Le asignamos al div que creamos al inicio, la imagen que acabamos de cargar a fin de poder evaluarla
		div.appendChild(img);
		//Definimos la variable que indicara el tama�o de la Im�gen
		var tam=0;
		//Cargamos en un setTimeout la imagen medida en Kb para que a los 500 milisegundos revise el tama�o de la misma, es en un setTimeout para que permite cargar y revisar
		setTimeout("tam=Math.round(div.lastChild.fileSize/1024);",700);
		//Despues de haber obtenido el tama�o de la imagen, la quitamos del div que creamos, para esto damos un espacio de 100 milisegundos
		setTimeout("div.removeChild(div.lastChild);",800);
		//Una vez que ya obtuvimos el tama�o de la imagen comparamos que sea mayor a 0 y menor a 10240000, si se cumple, 
		//al elemento bandera que le pasamos a la funcion le asigna el valor de SI, este elemento bandera es un elemento tipo hidden en el formulario a fin de 
		//poder hacer validacion
		//en caso que el tama�o no se cumpla, muestra una alerta de Im�gen no v�lida y al elemento bandera le asigna el valor NO
		setTimeout("if (tam>0&&tam<10240000){ document.getElementById('"+bandera+"').value='si'; return true}; else {alert('Introducir una Im�gen V�lida'); document.getElementById('"+bandera+"').value='no'; return false;}",900);
	}
	else
		document.getElementById(bandera).value="si";
}


/**************************************************************************************************************************************************************************/
/****************************************************************** AGREGAR EQUIPOS ***************************************************************************************/
/**************************************************************************************************************************************************************************/

//Funcion para escribir directamente la Familia a la que pertenece algun Equipo
function agregarNuevaFamilia(){
	//Si el checkbox para nueva Famlia esta checado, pedir el nombre
	if (document.getElementById("ckb_nuevaFamilia").checked){
		var linea = prompt("�Nombre de la Nueva Familia para el Equipo?","Nombre de la Familia...");	
		if(linea!=null && linea!="Nombre de la Familia..." && linea!=""){
			//Asignar el valor obtenido a la caja de texto que lo mostrara
			document.getElementById("txt_nuevaFamilia").value = linea;
			//Verificar que el combo este definido para poder deshabilitarlo
			if (document.getElementById("cmb_familia")!=null)
				//Deshabilitar el ComboBox para que el usuario no lo pueda modificar
				document.getElementById("cmb_familia").disabled = true;				
		}
		else
			//Regresar False si se presiona el bot�n cancelar o se asigna un valor equivocado
			document.getElementById("ckb_nuevaFamilia").checked = false;
	}
	//Si el checkbox para nueva Familia se de-selecciona, borrar el dato escrito en la caja de texto y reactivar el combo de Familia
	else{
		//Asignar el valor obtenido a la caja de texto que lo mostrara
		document.getElementById("txt_nuevaFamilia").value = "";
		//Verificar que el combo este definido para poder Habilitarlo
		if (document.getElementById("cmb_familia")!=null)
			//Deshabilitar el ComboBox y el CheckBox para que el usuario no los pueda modificar 			
			document.getElementById("cmb_familia").disabled = false;				
	}
}

//Funcion para Evaluar los datoas del formularo de Agregar Equipos
function valFormAgregarEquipo(frm_agregarEquipo){
	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;

	//Se verifica que la clave haya sido introducida
	if (frm_agregarEquipo.txt_clave.value==""){
		alert ("Introducir la Clave del Equipo");
		band=0;
	}
	
	//Se verifica que la clave sea valida
	if (frm_agregarEquipo.hdn_claveValida.value!="si"&&band==1){
		alert ("La clave ya esta Asignada a otro Equipo");
		band=0;
	}
	
	//Se verifica que el nombre haya sido introducido
	if (frm_agregarEquipo.txt_nombre.value==""&&band==1){
		alert ("Introducir el Nombre del Equipo");
		band=0;
	}
	
	//Se verifica que la marca/modelo haya sido introducida
	if (frm_agregarEquipo.txt_marcaModelo.value==""&&band==1){
		alert ("Introducir la Marca/Modelo del Equipo");
		band=0;
	}
	
	//Se verifica que el modelo haya sido introducido
	if (frm_agregarEquipo.txt_modelo.value==""&&band==1){
		alert ("Introducir el Modelo del Equipo");
		band=0;
	}
	
	//Se verifica que el numero de serie haya sido introducido
	if (frm_agregarEquipo.txt_serie.value==""&&band==1){
		alert ("Introducir el N�mero de Serie del Equipo");
		band=0;
	}
	
	//Se verifica que el tipo de motor haya sido introducida
	if (frm_agregarEquipo.txt_motor.value==""&&band==1){
		alert ("Introducir el Tipo de Motor del Equipo");
		band=0;
	}
	
	//Se verifica que se haya seleccionado un Area para el Equipo
	/*if (frm_agregarEquipo.cmb_area.value==""&&band==1){
		alert ("Seleccionar el �rea del Equipo");
		band=0;
	}*/
	
	//Verificar si se checo el checkbox para agregar una nueva familia
	if (!frm_agregarEquipo.ckb_nuevaFamilia.checked){
		//Si no se checo para agregar una nueva familia, verificar que se haya seleccionado una familia
		if (frm_agregarEquipo.cmb_familia.value==""&&band==1){
			alert ("Seleccionar la Familia del Equipo");
			band=0;
		}
	}
	
	//Verificar que se haya introducido la poliza de garantia
	if (frm_agregarEquipo.txt_poliza.value==""&&band==1){
		alert ("Introducir el N�mero de P�liza del Equipo");
		band=0;
	}
	
	//Verificar que se haya introducido el control de costos
	if (frm_agregarEquipo.cmb_con_cos.value==""&&band==1){
		alert ("Introducir el Control de Costos del Equipo del Equipo");
		band=0;
	}
	
	//Verificar que se haya introducido la cuenta del control de costos
	if (frm_agregarEquipo.cmb_cuenta.value==""&&band==1){
		alert ("Introducir la cuenta del Equipo");
		band=0;
	}
	
	//Verificar que se haya introducido el nombre de la persona quien tiene asignado el equipo
	if (frm_agregarEquipo.txt_asignado.value==""&&band==1){
		alert ("Introducir el Nombre a quien est� Asignado el Equipo");
		band=0;
	}
	
	//Verificar que se haya introducido el nombre del proveedor del Equipo
	if (frm_agregarEquipo.txt_proveedor.value==""&&band==1){
		alert ("Introducir el Nombre del Proveedor del Equipo");
		band=0;
	}
	
	//Verificar que se haya seleccionado una M�trica
	if (frm_agregarEquipo.cmb_metrica.value==""&&band==1){
		alert ("Seleccionar M�trica Hor�metro u Od�metro");
		band=0;
	}

	//Verificar que se haya seleccionado una imagen
	if (frm_agregarEquipo.hdn_foto.value!=""&&band==1){
		//Verificar que la imagen introducida sea valida, este valor lo obtiene de la funcion validarImagen()
		if (frm_agregarEquipo.hdn_foto.value=="no"){
			alert ("Introducir una Im�gen V�lida");
			band=0;
		}
	}

	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}


/************************************************************************* AGREGAR DOCUMENTACION **************************************************************************/
/*Esta funcion valida el formulario de Registrar Documentacion para un Equipo*/
function valFormAgregarDocumento(frm_equipoAgregado){
	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;
	
	//Verificar que se haya escrito el nombre del Documento
	if (frm_equipoAgregado.txa_documento.value==""){
		alert("Introducir el Nombre del Documento");	
		band=0;
	}
	
	//Si el documento es entregado pero sin ubicacion, preguntar si esta bien
	if (frm_equipoAgregado.txa_ubicacion.value==""&&band==1&&frm_equipoAgregado.cmb_estatus.value=="ENTREGADO"){
		if (confirm("No se ha Escrito la Ubicaci�n del Documento '"+frm_equipoAgregado.txa_documento.value+"'. �Es esto Correcto?"))
			band=1;
		else
			band=0;
	}
	
	//Si el documento tiene estado NO ENTREGADO y se escribe ubicacion, preguntar si esta bien, en caso que si, asignarle el estado ENTREGADO
	if (frm_equipoAgregado.txa_ubicacion.value!=""&&band==1&&frm_equipoAgregado.cmb_estatus.value=="NO ENTREGADO"){
		if (confirm("El estado NO ENTREGADO se le ha asignado al documento '"+frm_equipoAgregado.txa_documento.value+"'. �Asignar Estado ENTREGADO?")){
			frm_equipoAgregado.cmb_estatus.value="ENTREGADO";
		}
	}
	
	//Verificar que se haya introducido un Documento Valido
	if (frm_equipoAgregado.hdn_docValido.value=="no"){
		alert("Formato de Archivo no Soportado, Formatos Validos: 'png', 'jpg', 'jpeg', 'gif', 'pdf' y 'doc'");
		band=0;
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

/*Esta funcion permitira evaluar si un documento o archivo cargado tiene el formato v�lido*/
function validarDocumento(campo){
	//Obtener la longitud de la ruta del archivo	
	var tam = campo.value.length;
	//Obtener la cadena de la ruta del archivo
	var cadena = campo.value;
	
	//Si el usuario decide quitar el documento, entonces la longitud de la ruta del archivo es 0 colocamos el valor "si" en la variable hdn_docValido para que proceda
	//la operacion correspondiente
	if(tam!=0){
		//Obtener la extension del archivo
		var extension = cadena.charAt(tam-3)+cadena.charAt(tam-2)+cadena.charAt(tam-1);
		//var extension = campo.value.substring(tam-3,tam);
		//Pasar la extension de la imagen a minusculas para evaluarla como tal
		extension = extension.toLowerCase();
		//Comparar la extension contra las extensiones de los archivos permitidos
		if(!(extension=="png" || extension=="jpg" || extension=="jpeg" || extension=="gif" || extension=="pdf" || extension=="doc")){
			alert("Formato de Archivo no Soportado, Formatos Validos: 'png', 'jpg', 'jpeg', 'gif', 'pdf' y 'doc'");
			document.getElementById("hdn_docValido").value = "no";
		}
		else{
			document.getElementById("hdn_docValido").value = "si";
		}
	}
	else{
		document.getElementById("hdn_docValido").value = "si";
	}
}

/**************************************************************************************************************************************************************************/
/**++++++++++++++******************************************** ELIMINAR EQUIPOS ********************************************************************************************/
/**************************************************************************************************************************************************************************/
/*Esta funcion valida si en el primer formulario de EliminarEquipos se han seleccionado datos de los combobox*/
function valFormEliminaEquipo(frm_elegirEquipo){
	//Si el valor se mantiene en 1, el proceso de validaci�n fue satisfactorio
	band = 1;
	
	//Verificar que se haya seleccionado un Area
	if (frm_elegirEquipo.cmb_area.value==""){
		alert("Seleccionar el �rea del Equipo");
		band=0;
	}
	
	//Verificar que se haya seleccionado una Familia
	if (frm_elegirEquipo.cmb_familia.value=="" && band==1){
		alert("Seleccionar la Familia del Equipo");
		band=0;
	}
	
	//Verificar que se haya seleccionado una Clave
	if (frm_elegirEquipo.cmb_claveEquipo.value=="" && band==1){
		alert("Seleccionar la Clave del Equipo");
		band=0;
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

/*Esta funcion valida que se haya introducido la clave del Equipo a Eliminar*/
function valFormEliminaEquipoXClave(frm_elegirEquipoClave){
	//Si el valor se mantiene en 1, el proceso de validaci�n fue satisfactorio
	band = 1;
	
	//Verificar que la clave se haya introducido
	if(frm_elegirEquipoClave.txt_clave.value==""){
		alert("Introducir la Clave del Equipo");
		band=0;
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}


/**************************************************************************************************************************************************************************/
/****************************************************************** CONSULTAR EQUIPO **************************************************************************************/
/**************************************************************************************************************************************************************************/
/*Esta funci�n se encarga de validar el formulario de consultar equipo por Area*/
function valFormConsultaEquipoXArea(frm_consultarEquipoArea){
	//Si el valor se mantiene en 1, el proceso de validaci�n fue satisfactorio
	band = 1;
	
	//Verificar que la clave se haya introducido
	if(frm_consultarEquipoArea.cmb_area.value==""){
		alert("Seleccionar el �rea del Equipo");
		band=0;
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}


/*Esta funci�n se encarga de validar el formulario de consultar equipo por clave*/
function valFormConsultaEquipoXClave(frm_consultarEquipoClave){
	//Si el valor se mantiene en 1, el proceso de validaci�n fue satisfactorio
	band = 1;
	
	//Verificar que la clave se haya introducido
	if(frm_consultarEquipoClave.txt_clave.value==""){
		alert("Introducir la Clave del Equipo");
		band=0;
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

/*Esta funci�n se encarga de validar el formulario de consultar equipo por Familia*/
function valFormConsultaEquipoXFamilia(frm_consultarEquipoFamilia){
	//Si el valor se mantiene en 1, el proceso de validaci�n fue satisfactorio
	band = 1;
	
	//Verificar que la clave se haya introducido
	if(frm_consultarEquipoFamilia.cmb_familia.value==""){
		alert("Seleccionar la Familia del Equipo");
		band=0;
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;

}

/*Esta funci�n se encarga de validar el formulario de cambiar la disponibilidad*/
function valFormCambiarDisponibilidad(frm_consultarDisponibilidadEq){
	//Si el valor se mantiene en 1, el proceso de validaci�n fue satisfactorio
	band = 1;
	
	//Verificar que la clave se haya introducido
	if(frm_consultarDisponibilidadEq.cmb_disponibilidad.value==""){
		alert("Seleccionar la Disponibilidad del Equipo");
		band=0;
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;

}


/**************************************************************************************************************************************************************************/
/********************************************************************** MODIFICAR EQUIPO **********************************************************************************/
/**************************************************************************************************************************************************************************/
/*Esta funci�n se encarga de validar el formulario de seleccionar Equipos para modificar mediante el combobox*/
function valFormModificarEquipo(frm_seleccionarEquipo){
	//Si el valor se mantiene en 1, el proceso de validaci�n fue satisfactorio
	band = 1;
	
	//Verificar que se haya seleccionado un Area
	//if (frm_seleccionarEquipo.cmb_area.value==""){
		//alert("Seleccionar el �rea del Equipo");
		//band=0;
	//}
	
	//Verificar que se haya seleccionado una Familia
	if (frm_seleccionarEquipo.cmb_familia.value=="" && band==1){
		alert("Seleccionar la Familia del Equipo");
		band=0;
	}
	
	//Verificar que se haya seleccionado una Clave
	if (frm_seleccionarEquipo.cmb_claveEquipo.value=="" && band==1){
		alert("Seleccionar la Clave del Equipo");
		band=0;
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

/*Esta funci�n se encarga de validar el formulario de seleccionar Equipos para modificar mediante la clave escrita*/
function valFormModificarEquipoXClave(frm_seleccionarEquipoClave){
	//Si el valor se mantiene en 1, el proceso de validaci�n fue satisfactorio
	band = 1;
	
	//Verificar que la clave se haya introducido
	if(frm_seleccionarEquipoClave.txt_clave.value==""){
		alert("Introducir la Clave del Equipo");
		band=0;
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}


/*Esta funci�n se encarga de validar los datos del Formulario donde se modifican los datos de los Equipos*/
function valFormModificarEquipoDatos(frm_modificarEquipo){
	//Si el valor se mantiene en 1, el proceso de validaci�n fue satisfactorio
	band = 1;
	
	//Si el elemento hdn_validar vale SI, entonces se evalua el formulario, de lo contrario NO
	if (frm_modificarEquipo.hdn_validar.value=="si"){
		//Se verifica que la clave haya sido introducida
		if (frm_modificarEquipo.txt_clave.value==""){
			alert ("Introducir la Clave del Equipo");
			band=0;
		}
	
		//Se verifica que el nombre haya sido introducido
		if (frm_modificarEquipo.txt_nombre.value==""&&band==1){
			alert ("Introducir el Nombre del Equipo");
			band=0;
		}
	
		//Se verifica que la marca/modelo haya sido introducida
		if (frm_modificarEquipo.txt_marcaModelo.value==""&&band==1){
			alert ("Introducir la Marca/Modelo del Equipo");
			band=0;
		}
	
		//Se verifica que el modelo haya sido introducido
		if (frm_modificarEquipo.txt_modelo.value==""&&band==1){
			alert ("Introducir el Modelo del Equipo");
			band=0;
		}
	
		//Se verifica que el numero de serie haya sido introducido
		if (frm_modificarEquipo.txt_serie.value==""&&band==1){
			alert ("Introducir el N�mero de Serie del Equipo");
			band=0;
		}
	
		//Se verifica que el tipo de motor haya sido introducida
		if (frm_modificarEquipo.txt_motor.value==""&&band==1){
			alert ("Introducir el Tipo de Motor del Equipo");
			band=0;
		}
	
		//Se verifica que se haya seleccionado un Area para el Equipo
		if (frm_modificarEquipo.cmb_area.value==""&&band==1){
			alert ("Seleccionar el �rea del Equipo");
			band=0;
		}
	
		//Verificar si se checo el checkbox para agregar una nueva familia
		if (!frm_modificarEquipo.ckb_nuevaFamilia.checked){
			//Si no se checo para agregar una nueva familia, verificar que se haya seleccionado una familia
			if (frm_modificarEquipo.cmb_familia.value==""&&band==1){
				alert ("Seleccionar la Familia del Equipo");
				band=0;
			}
		}
	
		//Verificar que se haya introducido la poliza de garantia
		if (frm_modificarEquipo.txt_poliza.value==""&&band==1){
			alert ("Introducir el N�mero de P�liza del Equipo");
			band=0;
		}
		
		//Verificar que se haya introducido el control de costos
		if (frm_modificarEquipo.cmb_con_cos.value==""&&band==1){
			alert ("Introducir el Control de Costos del Equipo del Equipo");
			band=0;
		}
		
		//Verificar que se haya introducido la cuenta del control de costos
		if (frm_modificarEquipo.cmb_cuenta.value==""&&band==1){
			alert ("Introducir la cuenta del Equipo");
			band=0;
		}
	
		//Verificar que se haya introducido el nombre de la persona quien tiene asignado el equipo
		if (frm_modificarEquipo.txt_asignado.value==""&&band==1){
			alert ("Introducir el Nombre a quien est� Asignado el Equipo");
			band=0;
		}
	
		//Verificar que se haya introducido el nombre del proveedor del Equipo
		if (frm_modificarEquipo.txt_proveedor.value==""&&band==1){
			alert ("Introducir el Nombre del Proveedor del Equipo");
			band=0;
		}
		
		//Verificar que se haya introducido el nombre del proveedor del Equipo
		if (frm_modificarEquipo.cmb_metrica.value==""&&band==1){
			alert ("Seleccionar M�trica Hor�metro u Od�metro");
			band=0;
		}

		//Verificar que se haya seleccionado una imagen
		if (frm_modificarEquipo.hdn_foto.value!=""&&band==1){
			//Verificar que la imagen introducida sea valida, este valor lo obtiene de la funcion validarImagen()
			if (frm_modificarEquipo.hdn_foto.value=="no"){
				alert ("Introducir una Im�gen V�lida");
				band=0;
			}
		}
	}//Fin de la comprobacion del elemento hdn_validar
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

//Funcion que se encarga de validar el formulario que permite eliminar Documentos de la Base de Datos en Modificar Documentacion de los Equipos
function valFormModificaDocumentos(frm_modificarDocumentos){
	//Si el valor se mantiene en 1, el proceso de validaci�n fue satisfactorio
	band = 1;
	
	//Si se le dio click al boton de regesar, no realizar la validacion de los datos
	if(frm_modificarDocumentos.hdn_bandera.value=="si"){
		//Variable que verifica que se haya seleccionado un radiobutton
		var flag=0;
		var cantidad=document.getElementsByName("rdb_documentos").length;
		for (var i=0;i<cantidad;i++){
			if (document.getElementById("rdb_documentos"+(i+1)).checked==true){
				flag=1;
			}
		}
		
		if (flag==0){
			alert("Seleccionar un Documento para Eliminar");
			band=0;
		}
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}


/**************************************************************************************************************************/
/***************************************************AGREGAR GAMA**********************************************************/
/**************************************************************************************************************************/
/*Esta funci�n se encarga de validar el formulario de agregar gama*/
function valFormAgregarGama(frm_agregarGama){
	//Si el valor se mantiene en 1, el proceso de validaci�n fue satisfactorio
	band = 1;
	
	//Verificar que el campo de Clave de la Gama no este vac�o
	if(frm_agregarGama.txt_claveGama.value==""){
		alert("Introducir la Clave de la Gama");
		band = 0;
	}
	//Verificar que el campo del Nombre de la Gama no este vac�o
	if(frm_agregarGama.txt_nombreGama.value=="" && band==1){
		alert("Introducir el Nombre de la Gama");
		band = 0;
	}
	//Verificar que haya sido seleccionada una Area de Aplicacion
	/*if(frm_agregarGama.cmb_areaGama.value=="" && band==1){
		alert("Seleccionar una �rea de Aplicaci�n");
		band = 0;
	}*/
	//Verificar que haya sido seleccionada una Familia
	if(frm_agregarGama.cmb_familiaGama.value=="" && band==1){
		alert("Seleccionar una Familia");
		band = 0;
	}
	//Verificar que el campo de Descripci�n no este vac�o
	if(frm_agregarGama.txa_descripcionGama.value=="" && band==1){
		alert("Introducir la Descripci�n de la Gama");
		band = 0;
	}
	//Verificar que el campo de Ciclo de Servicio no este vac�o
	if(frm_agregarGama.txt_cicloServ.value=="" && band==1){
		alert("Introducir el Ciclo de Aplicaci�n de la Gama");
		band = 0;
	}
	
	if(band==1){
		if(!validarEntero(frm_agregarGama.txt_cicloServ.value.replace(/,/g,''),"El Ciclo de Aplicaci�n"))
			band = 0;		
	}
		
	
	//Verificar que la Clave no este repetida
	if(frm_agregarGama.hdn_claveValida.value=="no" && band==1){
		alert("Verificar la Clave Proporcionada");
		band = 0;
	}
	
	//Devolver el resultado de la validaci�n, TRUE = Validaci�n Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}


/*Esta funcion solicita la confirmaci�n del usuario antes de salir de la pagina*/
function confirmarSalida(pagina){
	if(confirm("�Estas Seguro que Quieres Salir?\nToda la informaci�n no Guardada se Perder�"))
		location.href = pagina;	
}


/*Esta funcion Activa y desactiva el comboBox en las paginas de Sistemas, Aplicaciones y Actividades de Agregar Gama, 
 * dependiendo del CheckBox que permite ingresar un nuevo Sistema, Apliacion o Actividad*/
function activarCkbNuevo(campo,comboBox,cajaTexto){
	//Cuando el CheckBox es activado, desactivar el comboBox y permitir Escribir en el Cuadro de Texto
	if(campo.checked){
		//Vaciar el comboBox y despues desactivarlo
		document.getElementById(comboBox).value = "";
		document.getElementById(comboBox).disabled = true;
		//Activar la Caja de Texto
		document.getElementById(cajaTexto).disabled = false;
	}
	else{
		//Cuando el CheckBox es desactivado, activar el comboBox y borrar el contenido del Cuadro de Texto y desactivarlo
		document.getElementById(cajaTexto).value = "";
		document.getElementById(cajaTexto).disabled = true;
		//Activar el comboBox
		document.getElementById(comboBox).disabled = false;
	}
}


/*Esta funcion valida que un dato sea agregado desde el comboBox o desde la Caja de Texto de las paginas de Sistemas, 
 * Aplicaciones y Actividades de Agregar Gama, recibiendo como parametro el formulario, el mensaje a desplegar y las
 * posiciones del comboBox y la Caja de Texto*/
function valFormAgregarDatoGama(form,mensaje,posComboBox,posCajaTexto){			
	//Si la band se mantiene en 1, el proceso de validaci�n se llevo a cabo con �xito
	band = 1;
	
	//alert(form[posComboBox].name+" => "+form[posComboBox].value+"\n"+form[posCajaTexto].name+" => "+form[posCajaTexto].value);
	//Verificar que sea proporcionado un sistema para ser agregado a la Gama
	if(form[posComboBox].value=="" && form[posCajaTexto].value==""){
		alert(mensaje);
		band = 0;
	}
			
	if(band==1)
		return true;
	else
		return false;
}


/*Esta funcion coloca la etiqueta de Horas o Kilometros delante del campo Ciclo de Aplicaci�n*/
function colocarMensaje(campo){
	if(campo.value!=""){
		if(document.getElementById('txt_tipoMetrica').value=="HOROMETRO"){
			document.getElementById('txt_msjMetrica').value = "Horas";
		}
		else if(document.getElementById('txt_tipoMetrica').value=="ODOMETRO"){
			document.getElementById('txt_msjMetrica').value = "Kil�metros";					
		}
	}
	else
		document.getElementById('txt_msjMetrica').value = "";	
}


/**************************************************************************************************************************/
/*******************************************AGREGAR SISTEMAS A LA GAMA*****************************************************/
/**************************************************************************************************************************/
/*Esta funcion se ecnarga de Validar el Formulario donde se selecciona el Sistema que ser� Editado o Borrado*/
function valFormTablaSistema(frm_tablaSistemas){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
	var pos;	
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opci�n
	if(frm_tablaSistemas.rdb_sistema.length==undefined && !frm_tablaSistemas.rdb_sistema.checked){
		alert("Seleccionar un Sistema para ser "+frm_tablaSistemas.hdn_boton.value);
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_tablaSistemas.rdb_sistema.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_tablaSistemas.rdb_sistema.length;i++){
			if(frm_tablaSistemas.rdb_sistema[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar un Sistema para ser "+frm_tablaSistemas.hdn_boton.value);				
	}
	
	if(res==1)
		return true;
	else
		return false;
}


/**************************************************************************************************************************/
/****************************************AGREGAR APLICACIONES AL SISTEMA***************************************************/
/**************************************************************************************************************************/
/*Esta funcion se ecnarga de Validar el Formulario donde se selecciona la Aplicaci�n que ser� Editada o Borrada*/
function valFormTablaAplicaciones(frm_tablaAplicaciones){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
	var pos;	
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opci�n
	if(frm_tablaAplicaciones.rdb_aplicacion.length==undefined && !frm_tablaAplicaciones.rdb_aplicacion.checked){
		alert("Seleccionar una Aplicaci�n para ser "+frm_tablaAplicaciones.hdn_boton.value);
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_tablaAplicaciones.rdb_aplicacion.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_tablaAplicaciones.rdb_aplicacion.length;i++){
			if(frm_tablaAplicaciones.rdb_aplicacion[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar una Aplicaci�n para ser "+frm_tablaAplicaciones.hdn_boton.value);
	}
	
	if(res==1)
		return true;
	else
		return false;
}


/**************************************************************************************************************************/
/***************************************AGREGAR ACTIVIDADES A LA APLICACION************************************************/
/**************************************************************************************************************************/
/*Esta funcion se ecnarga de Validar el Formulario donde se selecciona la Actividad que ser� Agregada o Borrada*/
function valFormTablaActividades(frm_tablaActividades){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
	var pos;	
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opci�n
	if(frm_tablaActividades.rdb_actividad.length==undefined && !frm_tablaActividades.rdb_actividad.checked){
		alert("Seleccionar una Actividad para ser Borrada");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_tablaActividades.rdb_actividad.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_tablaActividades.rdb_actividad.length;i++){
			if(frm_tablaActividades.rdb_actividad[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar una Actividad para ser Borrada");			
	}
	
	if(res==1)
		return true;
	else
		return false;
}

/*Funcion que revisa si el campo de tiempos tiene datos correctos*/
function validarTiempoServicios(cajaTiempo){
	tiempo=cajaTiempo.value;
	//Si el tiempo es vacio, indicarlo y restablecer
	if(tiempo==""){
		alert("Tiempo Ingresado NO v�lido");
		cajaTiempo.value=cajaTiempo.defaultValue;
	}
	else{
		//quitar los posibles Dos Puntos : para la Hora
		tiempo=tiempo.replace(/:/g,'');
		if(tiempo.length==1){
			cajaTiempo.value="00:0"+tiempo;
		}
		if(tiempo.length==2){
			cajaTiempo.value="00:"+tiempo;
		}
		if(tiempo.length==3){
			var horas=tiempo.substring(0,1);
			var minutos=tiempo.substring(1,3);
			cajaTiempo.value="0"+horas+":"+minutos;
		}
		if(tiempo.length==4){
			var horas=tiempo.substring(0,2);
			var minutos=tiempo.substring(2,4);
			cajaTiempo.value=horas+":"+minutos;
		}
		if(tiempo.length==5){
			var horas=tiempo.substring(0,3);
			var minutos=tiempo.substring(3,5);
			cajaTiempo.value=horas+":"+minutos;
		}
	}
}

/**************************************************************************************************************************/
/**********************************************ELIMINAR GAMAS**************************************************************/
/**************************************************************************************************************************/
function valFormEliminarGama(valFormEliminarGama){
	//Si la bandera se mantiene en 1, el proceso de valiaci�n fue exitoso	
	var band = 1;	
	if(valFormEliminarGama.txa_descripcionGama.value==""){
		alert("Seleccionar una Gama para Eliminar");
		band = 0;
	}
	else{
		if(!confirm("�Estas Seguro que Quieres Eliminar la Gama Seleccionada?\nToda la Informaci�n Relacionada con la Gama Ser� Eliminada"))
			band = 0;
			
	}
	
	
	if(band==1)
		return true;
	else
		return false;
}


/**************************************************************************************************************************/
/*********************************************CONSULTAR GAMAS**************************************************************/
/**************************************************************************************************************************/
function valFormConsultarGama(frm_consultarGama){
	//Si la bandera se mantiene en 1, el proceso de valiaci�n fue exitoso	
	var band = 1;	
	if(frm_consultarGama.txa_descripcionGama.value==""){
		alert("Seleccionar una Gama para Consultar");
		band = 0;
	}	
	
	
	if(band==1)
		return true;
	else
		return false;
}


/**************************************************************************************************************************/
/*********************************************MODIFICAR GAMAS**************************************************************/
/**************************************************************************************************************************/
/*Esta funcion validad el Formulario donde se selecciona una Gama para ser Modificada*/
function valFormSeleccionarGama(frm_seleccionarGama){
	//Si la bandera se mantiene en 1, el proceso de valiaci�n fue exitoso	
	var band = 1;	
	if(frm_seleccionarGama.cmb_claveGama.value==""){
		alert("Seleccionar una Gama para Modificar");
		band = 0;
	}	
	
	
	if(band==1)
		return true;
	else
		return false;
}


/*Esta funci�n se encarga de validar el formulario de Modificar Gama*/
function valFormModificarGama(frm_modificarGama){
	//Si el valor se mantiene en 1, el proceso de validaci�n fue satisfactorio
	band = 1;
	
	//Verificar que el campo de Clave de la Gama no este vac�o
	if(frm_modificarGama.txt_claveGama.value==""){
		alert("Introducir la Clave de la Gama");
		band = 0;
	}
	//Verificar que el campo del Nombre de la Gama no este vac�o
	if(frm_modificarGama.txt_nombreGama.value=="" && band==1){
		alert("Introducir el Nombre de la Gama");
		band = 0;
	}
	//Verificar que haya sido seleccionada una Area de Aplicacion
	if(frm_modificarGama.cmb_areaGama.value=="" && band==1){
		alert("Seleccionar una �rea de Aplicaci�n");
		band = 0;
	}
	//Verificar que haya sido seleccionada una Familia
	if(frm_modificarGama.cmb_familiaGama.value=="" && band==1){
		alert("Seleccionar una Familia");
		band = 0;
	}
	//Verificar que el campo de Descripci�n no este vac�o
	if(frm_modificarGama.txa_descripcionGama.value=="" && band==1){
		alert("Introducir la Descripci�n de la Gama");
		band = 0;
	}
	
		//Verificar que el campo de Ciclo de Servicio no este vac�o
	if(frm_modificarGama.txt_cicloServ.value=="" && band==1){
		alert("Introducir el Ciclo de Aplicaci�n de la Gama");
		band = 0;
	}
	
	if(band==1){
		if(!validarEntero(frm_modificarGama.txt_cicloServ.value.replace(/,/g,''),"El Ciclo de Aplicaci�n"))
			band = 0;		
	}
	
	
	//Devolver el resultado de la validaci�n, TRUE = Validaci�n Exitosa, FALSE = Existen Errores
	if(band==1)
		return true;
	else
		return false;
}


/**************************************************************************************************************************************************************************/
/**************************************************************** ORDEN DE TRABAJO ****************************************************************************************/
/**************************************************************************************************************************************************************************/

//Funcion que permite cambiar el valor de la caja de texto donde se coloca el proveedor y redirecciona al registro de la OTSE en caso de seleccioanr la opci�n EXTERNO
function varificarTipoServicio(){
	var combo=document.getElementById("cmb_servicio").value;
	if(combo=="INTERNO"||combo==""){
		document.getElementById("txt_proveedor").readOnly = true;
		document.getElementById("txt_proveedor").value = "N/A";
	}
	if(combo=="EXTERNO"){
		location.href="frm_generarOrdenServiciosE.php";
	}
}

//Funcion para Evaluar los datoas del formularo Generar Orden de Trabajo
function valFormGenerarOrdenTrabajo(frm_generarOrdenTrabajo){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;

	//Se verifica que el servicio haya sido ingresado
	if (frm_generarOrdenTrabajo.cmb_servicio.value==""){
		alert ("Seleccionar un Servicio");
		band=0;
	}
	
	//Se verifica que el area haya sido ingresado
	/*if (frm_generarOrdenTrabajo.cmb_area.value==""&&band==1){
		alert ("Seleccionar �rea");
		band=0;
	}*/
	
	//Se verifica que la familia haya sido ingresado
	if (frm_generarOrdenTrabajo.cmb_familia.value==""&&band==1){
		alert ("Seleccionar Familia");
		band=0;
	}
	
	//Se verifica que la clave del equipo haya sido ingresado
	if (frm_generarOrdenTrabajo.cmb_claveEquipo.value==""&&band==1){
		alert ("Seleccionar Clave del Equipo");
		band=0;
	}
	
	//Se verifica que fecha programada haya sido ingresado
	if (frm_generarOrdenTrabajo.txt_fechaProgramada.value==""&&band==1){
		alert ("Seleccionar la Fecha Programada");
		band=0;
	}

	//Se verifica que el operador haya sido ingresado
	/*if (frm_generarOrdenTrabajo.txt_operadorEquipo.value==""&&band==1){
		alert ("Ingresar Operador del Equipo");
		band=0;
	}*/
	
	//Se verifica que el turno haya sido ingresado
	if (frm_generarOrdenTrabajo.cmb_turno.value==""&&band==1){
		alert ("Seleccionar  el Turno");
		band=0;
	}
	
	//Se verifica que el tipo de metrica haya sido ingresado
	if (frm_generarOrdenTrabajo.cmb_metrica.value==""&&band==1){
		alert ("Seleccionar Tipo de M�trica");
		band=0;
	}
		
	//Se verifica que la cantidad de metrica haya sido ingresado
	if (frm_generarOrdenTrabajo.txt_cantidadMetrica.value==""&&band==1){
		alert ("Introducir la cantidad de la M�trica");
		band=0;
	}

	if(band==1){
		if(!validarEntero(frm_generarOrdenTrabajo.txt_cantidadMetrica.value.replace(/,/g,''),"La Cantida de la Metrica"))
			band = 0;		
	}

	//Se verifica que se haya ingresado a la persona que autorizo la orden de trabajo
	if (frm_generarOrdenTrabajo.cmb_autorizoOT.value==""&&band==1){
		alert ("Indicar la Persona que Autoriza la Orden de Trabajo");
		band=0;
	}
	
	//Se verifica que se haya ingresado el proveedor del servicio que autorizo la orden de trabajo
	if (frm_generarOrdenTrabajo.txt_proveedor.value==""&&band==1){
		alert ("Ingresar Proveedor");
		band=0;
	}
	
	//Verificar si estan definidos el supervisor, revisor y generador de la orden de trabajo, esto para la �rden de Trabajo de
	//Mantenimiento Mina y como tal no forma parte de la Orden de Trabajo de Mantenimiento Concreto
	if (band==1 && frm_generarOrdenTrabajo.txt_supervisor!=undefined){
		if (frm_generarOrdenTrabajo.txt_supervisor.value==""){
			alert ("Ingresar Supervisor a Cargo de la �rden de Trabajo");
			band=0;
		}
		if (band==1 && frm_generarOrdenTrabajo.txt_generador.value==""){
			alert ("Ingresar Nombre de Quien Genera la �rden de Trabajo");
			band=0;
		}
		if (band==1 && frm_generarOrdenTrabajo.txt_revisor.value==""){
			alert ("Ingresar El Nombre del Revisor de la �rden de Trabajo");
			band=0;
		}
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}


//Funcion para Evaluar los datos del frm_gamaOT
function valFormGamaOT(frm_gamaOT){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;

	//Se verifica que se haya seleccionado al menos una gama
	if (frm_gamaOT.cmb_metrica.value==""){
		alert ("Introducir al Menos una Gama");
		band=0;
	}
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}


/*Esta funcion se ecnarga de Validar el Formulario donde se selecciona la Actividad que ser� Agregada o Borrada*/
function valFormTablaGamas(frm_tablaGamas){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opci�n
	if(frm_tablaGamas.rdb_gama.length==undefined && !frm_tablaGamas.rdb_gama.checked){
		alert("Seleccionar la Gama a Borrar");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_tablaGamas.rdb_gama.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_tablaGamas.rdb_gama.length;i++){
			if(frm_tablaGamas.rdb_gama[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar Gama a Borrar");			
	}
	
	if(res==1)
		return true;
	else
		return false;
}


//Funcion para Evaluar los datos del frm_consultarOrdenTrabajo
function valFormConsultarOrdenTrabajo(frm_consultarOrdenTrabajo){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;

	//Se verifica que el servicio haya sido ingresado
	if (frm_consultarOrdenTrabajo.cmb_servicio.value==""){
		alert ("Seleccionar un Servicio");
		band=0;
	}
	
	//Se verifica que el area haya sido ingresado
	/*if (frm_consultarOrdenTrabajo.cmb_area.value==""&&band==1){
		alert ("Seleccionar el �rea");
		band=0;
	}*/
	
	//Se verifica que el destino haya sido ingresado
	if (frm_consultarOrdenTrabajo.cmb_familia.value==""&&band==1){
		alert ("Seleccionar una Familia");
		band=0;
	}
	
	//Se verifica que la fecha de inicio haya sido ingresado
	if (frm_consultarOrdenTrabajo.txt_fechaInicio.value==""&&band==1){
		alert ("Seleccionar la Fecha de Inicio");
		band=0;
	}
	
	//Se verifica que la fecha de fin haya sido ingresado
	if (frm_consultarOrdenTrabajo.txt_fechaFin.value==""&&band==1){
		alert ("Seleccionar la Fecha de Fin");
		band=0;
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}


function valFormVerEquipos(frm_verEquipos){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
	var pos;	
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opci�n
	if(frm_verEquipos.rdb_equipoSelect.length==undefined && !frm_verEquipos.rdb_equipoSelect.checked){
		alert("Seleccionar un Equipo para Generar Orden de Trabajo");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_verEquipos.rdb_equipoSelect.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_verEquipos.rdb_equipoSelect.length;i++){
			if(frm_verEquipos.rdb_equipoSelect[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar un Equipo para Generar Orden de Trabajo");				
	}
	
	if(res==1)
		return true;
	else
		return false;
}


/**************************************************************************************************************************************************************************/
/********************************************************* ORDEN DE TRABAJO PARA SERVICIOS EXTERNOS ***********************************************************************/
/**************************************************************************************************************************************************************************/
/*Esta funcion coloca la pagina a la cual se va a direccionar y envia el formulario*/
function direccionarPagina(opc){
	//Verificar que la opci�n seleccionada no este vacia
	if(opc!=""){
		if(opc=="REGISTRAR"){
			document.frm_seleccionarOperacion.action = "frm_generarOrdenServiciosE.php";//Colocar la pagina a donde ser� enviado el formulario
			document.frm_seleccionarOperacion.submit();//Enviar el formulario			
		}
		else if(opc=="CONSULTAR"){
			document.frm_seleccionarOperacion.action = "frm_consultarOrdenServiciosE.php";//Colocar la pagina a donde ser� enviado el formulario
			document.frm_seleccionarOperacion.submit();//Enviar el formulario			
		}
	}
}//Cierre de la funci�n direccionarPagina(opc)


//Esta funci�n valida los datos del Formulario de registro de la Orden de Trabajo para Servicios Externos
function valFormGenerarOTSE(frm_generarOTSE){
	//Esta variable indicar� si el proceso de validaci�n fue exitoso
	var validacion = 1;
	
	if(frm_generarOTSE.hdn_botonSelect.value=="generarOrden"){
		//Verificar que los datos obligatorios sean proporcionados
		if(frm_generarOTSE.cmb_clasificacion.value==""){
			alert("Seleccionar la Clasificaci�n del Trabajo");
			validacion = 0;
			frm_generarOTSE.cmb_clasificacion.focus()//Regresar el foco de la pagina al elemento que esta emitiendo el mensaje
		}
		
		
		//Validar los datos del proveedor
		if(frm_generarOTSE.cmb_proveedor.value=="" && validacion==1){
			alert("Seleccionar Proveedor");
			validacion = 0;
			frm_generarOTSE.cmb_proveedor.focus()//Regresar el foco de la pagina al elemento que esta emitiendo el mensaje
		}
		//Si hay un proveedor seleccionado y la opci�n es NVO_PROVEEDOR, validar los campos de txt_proveedor y txt_direcccion
		if(validacion==1){
			if(frm_generarOTSE.cmb_proveedor.value=="NVO_PROVEEDOR"){
				if(frm_generarOTSE.txt_proveedor.value==""){
					alert("Introducir el Nombre del Proveedor");
					validacion = 0;
					frm_generarOTSE.txt_proveedor.focus()//Regresar el foco de la pagina al elemento que esta emitiendo el mensaje
				}
				if(frm_generarOTSE.txt_direccion.value=="" && validacion==1){
					alert("Introducir la Direcci�n del Proveedor");
					validacion = 0;
					frm_generarOTSE.txt_direccion.focus()//Regresar el foco de la pagina al elemento que esta emitiendo el mensaje
				}
			}//Cierre if(frm_generarOTSE.cmb_proveedor.value=="NVO_PROVEEDOR")
		}
		
		
		//Validar resto de los campos
		if(frm_generarOTSE.txt_repProveedor.value=="" && validacion==1){
			alert("Introducir el Nombre del Representante del Proveedor");
			validacion = 0;
			frm_generarOTSE.txt_repProveedor.focus()//Regresar el foco de la pagina al elemento que esta emitiendo el mensaje
		}
		if(frm_generarOTSE.txt_encCompras.value=="" && validacion==1){
			alert("Introducir el Nombre del Encargado de Compras");
			validacion = 0;
			frm_generarOTSE.txt_encCompras.focus()//Regresar el foco de la pagina al elemento que esta emitiendo el mensaje
		}
		if(frm_generarOTSE.txt_solicito.value=="" && validacion==1){
			alert("Introducir el Nombre del Solicitante de Esta Orden de Trabajo para Servicios Externos");
			validacion = 0;
			frm_generarOTSE.txt_solicito.focus()//Regresar el foco de la pagina al elemento que esta emitiendo el mensaje
		}
		if(frm_generarOTSE.txt_autorizo.value=="" && validacion==1){
			alert("Introducir el Nombre de Quien Autoriza Esta Orden de Trabajo para Servicios Externos");
			validacion = 0;
			frm_generarOTSE.txt_autorizo.focus()//Regresar el foco de la pagina al elemento que esta emitiendo el mensaje
		}
		
		
		//Verificar si fueron agregados los materiales
		if(frm_generarOTSE.hdn_materialesAgregados.value=="no" && validacion==1){
			//Notificar al usuario cuando no se hayan agregado materiales
			if(!confirm("�No se Registraron Materiales!\nPresinar 'Aceptar' para Continuar � \nPresionar Cancelar para Realizar el Registro de Materiales")){
				validacion = 0;
			}
		}
	}//Cierre if(frm_generarOTSE.hdn_botonSelect.value=="generarOrden")
	
	
	if(validacion==1)
		return true;
	else
		return false;
}//Cierre de la funci�n valFormGenerarOTSE(frm_generarOTSE)


//Esta funci�n agrega una nueva opcion al combo de proveedores de la pagina de registrar orden de trabajo para servicios externos
function agregarNvaOpcion(cmbProveedor){
	
	//Agregar un espacio al combo de proveedores
	cmbProveedor.length++;
	cmbProveedor.options[cmbProveedor.length-1].value = "NVO_PROVEEDOR";
	cmbProveedor.options[cmbProveedor.length-1].text = "AGREGAR PROVEEDOR";		

}//Cierre de la funci�n agregarNvaOpcion(cmbProveedor)


/*Esta funcion activa los campos para agregar los datos de un proveedor no registrado*/
function ingresarNvoProveedor(cmbProveedor){
	//Si la opcion seleccionada es 'NVO_PROVEEDOR', habilitar los campos para registrar un proveedor
	if(cmbProveedor.value=="NVO_PROVEEDOR"){
		//Vaciar los campos
		document.getElementById("txt_proveedor").value = "";		
		document.getElementById("txt_direccion").value = "";
		
		//Colocar la propiedad tabIndex a los campos
		document.getElementById("txt_proveedor").tabIndex = 5;
		document.getElementById("txt_direccion").tabIndex = 6;
		
		//Desactivar la propiedad ReadOnly
		document.getElementById("txt_proveedor").readOnly = false;
		document.getElementById("txt_direccion").readOnly = false;
		document.getElementById("txt_proveedor").focus();
	}
	else{
		//Vaciar los campos
		document.getElementById("txt_proveedor").value = "";		
		document.getElementById("txt_direccion").value = "";
		
		//Quitar la propiedad tabIndex a los campos
		document.getElementById("txt_proveedor").tabIndex = "";
		document.getElementById("txt_direccion").tabIndex = "";
		
		//Activar la propiedad readOnly
		document.getElementById("txt_proveedor").readOnly = true;
		document.getElementById("txt_direccion").readOnly = true;
	}
}//Cierre de la funci�n ingresarNvoProveedor(cmbProveedor)


//Funcion para Evaluar los datos del frm_complementarBitacora en apartado Actividades
function valFormActividadesRealizar(frm_actividadesRealizar,area){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;


	//El campo de Sistema es el unico que cambia en la Pantalla de Registrar actividades para la OTSE en las areas de Mina y Concreto
	if(area=="CONCRETO"){
		//Se verifica que el sistema haya sido ingresado
		if (frm_actividadesRealizar.cmb_sistema.value==""){
			alert ("Seleccionar Sistema");
			band=0;
			frm_actividadesRealizar.cmb_sistema.focus();
		}
	}
	else if(area=="MINA"){
		//Se verifica que el sistema haya sido ingresado
		if (frm_actividadesRealizar.txt_sistema.value==""){
			alert ("Introducir Sistema");
			band=0;
			frm_actividadesRealizar.txt_sistema.focus();
		}
	}
	

	//Se verifica que  la aplicaci�n haya sido ingresada
	if (frm_actividadesRealizar.txt_aplicacion.value==""&&band==1){
		alert ("Introducir Aplicaci�n");
		band=0;
		frm_actividadesRealizar.txt_aplicacion.focus();
	}
	
	//Se verifica que la actividad hayasido ingresada
	if (frm_actividadesRealizar.txa_actividad.value==""&&band==1){
		alert ("Introducir Actividad");
		band=0;
		frm_actividadesRealizar.txa_actividad.focus();
	}
	
	//Verificar que sea seleccionada una Familia
	if (frm_actividadesRealizar.cmb_familia.value==""&&band==1){
		alert ("Seleccionar una Familia de Equipos");
		band=0;
		frm_actividadesRealizar.cmb_familia.focus();
	}
	
	//Verificar que sea seleccionada una Equipo
	if (frm_actividadesRealizar.cmb_claveEquipo.value==""&&band==1){
		alert ("Seleccionar un Equipo");
		band=0;
		frm_actividadesRealizar.cmb_claveEquipo.focus();
	}
	

	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}//Cierre valFormActividadesRealizar(frm_actividadesRealizar)


/*Esta funcion agregar la opci�n de STOCK al combo de equipos en la pantalla de registro de actividades de la OTSE */
function nvaOpcComboEquipo(){
	//Recuperar la referencia del ComboBox que carga las claves de los Equipo
	var comboEquipo = document.getElementById("cmb_claveEquipo");
	
	comboEquipo.length++;
	comboEquipo.options[comboEquipo.length-1].text="STOCK";
	comboEquipo.options[comboEquipo.length-1].value="STOCK";
	comboEquipo.options[comboEquipo.length-1].title="STOCK";
}//Cierre nvaOpcComboEquipo()


/*Esta funci�n valida el formulario de registrar materiales*/
function valFormMaterialesUtilizar(frm_materialesUtilizar){
	var validacion = 1;//Esta variable ayudara a determinar si el proceso de validaci�n fue exitoso
	
	
	//Verificar si fue introducido el nombre del material
	if(frm_materialesUtilizar.txa_material.value==""){
		alert("Introducir el Nombre del Material");
		validacion = 0;
		frm_materialesUtilizar.txa_material.focus();
	}
	
	//Verificar que sea introducida la cantidad
	if(frm_materialesUtilizar.txt_cantidad.value=="" && validacion==1){
		alert("Introducir la Cantidad del Material");
		validacion = 0;
		frm_materialesUtilizar.txt_cantidad.focus();
	}
	
	//Validar que la cantidad sea un numero entero valido
	if(validacion==1){
		if(!validarEntero(frm_materialesUtilizar.txt_cantidad.value.replace(/,/g,''),"La Cantidad")){
			validacion = 0;		
			frm_materialesUtilizar.txt_cantidad.focus();
		}
	}
	
	if(validacion==1)	
		return true;	
	else
		return false;
		
}//Cierre de la funci�n valFormMaterialesUtilizar(frm_materialesUtilizar)


/*Esta funci�n validar� si las fechas seleccionadas son validas*/
function valFormConsultarOTSE(frm_consultarOTSE){
	if(validarFechas(frm_consultarOTSE.txt_fechaInicio.value,frm_consultarOTSE.txt_fechaFin.value))
		return true;
	else
		return false;
}//Cierre de la funci�n valFormConsultarOTSE(frm_consultarOTSE)


/*Esta funci�n abrir� una ventana emergente para complementar los Materiales de la Orden de Trabajo para Servicios Externos Seleccionada*/
function materialesOTSE(idOrdenTrabajo){
	//Abrir una ventana emergente con los Materiales de Orden Seleccionada
	window.open("verComplementarMaterialesOTSE.php?idOrden="+idOrdenTrabajo,
				"_blank", "top=100, left=100, width=850, height=620, status=no, menubar=yes, resizable=yes, scrollbars=yes,toolbar=no, location=no, directories=no");		
}//Cierre de la funci�n complementarOTSE(idOrdenTrabajo)


/**************************************************************************************************************************/
/***************************************************GENERAR  REPORTES******************************************************/
/**************************************************************************************************************************/

/*Esta funcion valida que las fechas elegidas en los Reportes sean correctas*/
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
		alert ("La Fecha de Inicio no puede ser mayor a la Fecha de Cierre");
	}		
	
	if(band==1)
		return true;
	else
		return false;
}



/***********************************************PREVENTIVOS*********************************************************/
/*Esta funcion se encarga de validar los formularios para Generar el Reporte de Mantenimientos preventivod en las diferentes opciones proporcionadas*/
function verFormReportesPreventivos(form,opc){
	//Si la variable band se mantiene en 1, el proceso de validaci�n se llevo a cabo con �xito
	band = 1;
	
	switch(opc){			
		case 1://Generar Reporte por �rea
			if(form.cmb_area.value==""){
				alert("Seleccionar el �rea");
				band = 0;
			}
			else{
				if(!validarFechas(form.txt_fechaIni.value,form.txt_fechaFin.value))
					band = 0;				
			}
		break;
		
		case 2://Generar Reporte por Familia
			if(form.cmb_familia.value==""){
				alert("Seleccionar la Familia de Equipos");
				band = 0;
			}
			else{
				if(!validarFechas(form.txt_fechaIni.value,form.txt_fechaFin.value))
					band = 0;				
			}
		break;
		
		case 3: 
			if(form.cmb_familia.value==""){
				alert("Seleccionar una Familia");
				band = 0;
			}
			else{
				if(form.cmb_equipo.value==""){
					alert("Seleccionar un Equipo");
					band = 0;				
				}
			}
		break;
		
		case 4:
			if(form.txt_nivelInf.value==""){
				alert("Introducir Valor Para el Nivel Inferior");
				band = 0;
			}
			else{
				if(form.txt_nivelSup.value==""){
					alert("Introducir Valor Para el Nivel Superior");
					band = 0;
				}
				else{
					cantMayor = parseInt(form.txt_nivelSup.value.replace(/,/g,''));
					cantMenor = parseInt(form.txt_nivelInf.value.replace(/,/g,''));
					if(cantMenor>cantMayor){
						alert("La Cantidad del Nivel Inferior no Puede ser Mayor al Nivel Superior");
						band = 0;
					}
				}
			}			
		break;
	}//Cierre Switch
	
	
	if(band==1)
		return true;
	else
		return false;	
}

/***********************************************CORRECTIVOS*********************************************************/
function verFormReportesCorrectivos(form,opc){
	//Si la variable band se mantiene en 1, el proceso de validaci�n se llevo a cabo con �xito
	band = 1;
	
	switch(opc){
		
		case 1://Generar Reporte Correctivo por �rea
			if(form.cmb_area.value==""){
				alert("Seleccionar el �rea");
				band = 0;
			}
			else{
				if(!validarFechas(form.txt_fechaIni.value,form.txt_fechaFin.value))
					band = 0;				
			}
		break;
		
		case 2://Generar Reporte Correctivo por �rea
			if(!validarFechas(form.txt_fechaIni.value,form.txt_fechaFin.value))
				band = 0;				
		break;
		
		}//Cierre Switch
	
	
	if(band==1)
		return true;
	else
		return false;	
}

/***********************************************ORDEN TRABAJO*********************************************************/
function verFormReportesOrdenTrabajo(form,opc){
	//Si la variable band se mantiene en 1, el proceso de validaci�n se llevo a cabo con �xito
	band = 1;
	
	switch(opc){
		
		case 1://Generar Reporte por fechas
			if(!validarFechas(form.txt_fechaIni.value,form.txt_fechaFin.value))
				band = 0;	
		break;
		
		case 2://Generar Reporte Correctivo por Tipo de Servicio
			if(form.cmb_servicio.value==""){
				alert("Seleccionar Tipo de Servicio");
				band = 0;
			}
			else{
				if(!validarFechas(form.txt_fechaIni.value,form.txt_fechaFin.value))
					band = 0;				
			}
		break;
	}
		
	if(band==1)
		return true;
	else
		return false;	
}

/***********************************************PREVENTIVO//CORRECTIVO*********************************************************/
function verFormReportePreventivoCorrectivo(frm_reportePreventivoCorrectivo){
	//Si la variable band se mantiene en 1, el proceso de validaci�n se llevo a cabo con �xito
	band = 1;
	
		if(!validarFechas(frm_reportePreventivoCorrectivo.txt_fechaIni.value,frm_reportePreventivoCorrectivo.txt_fechaFin.value)){
			band = 0;				
		}

	if(band==1)
		return true;
	else
		return false;	
}

/*********************************************REPORTE ESTADISTICO***********************************************************/
function valFormRepEstadistico(frm_reporteEstadistico){
	band=1;
	if(frm_reporteEstadistico.cmb_anios.value==""){
		alert("Seleccionar el A�o");
		band=0;
	}
	if(band==1 && frm_reporteEstadistico.cmb_meses.value==""){
		alert("Seleccionar el Mes");
		band=0;
	}
	if(band==1 && frm_reporteEstadistico.cmb_equipo.value==""){
		if (!confirm("No se ha Seleccionado Un Equipo.\n�Desea Continuar?"))
			band=0;
	}
	
	if(band==1)
		return true;
	else
		return false;	
}

function valFormRepEstadisticoDetalle(frm_reporteEstadistico2){
	var res=1;
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_reporteEstadistico2.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_reporteEstadistico2.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_reporteEstadistico2.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Cierre, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_reporteEstadistico2.txt_fechaFin.value.substr(0,2);
	var finMes=frm_reporteEstadistico2.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_reporteEstadistico2.txt_fechaFin.value.substr(6,4);
	
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	//Verificar que el a�o de Fin sea mayor al de Inicio
	if(fechaIni>fechaFin){
		res=0;
		alert ("La fecha de Inicio no puede ser mayor a la Fecha de Cierre");
	}
	
	if(res==1 && frm_reporteEstadistico2.cmb_familia.value==""){
		alert("Seleccionar por lo menos la Familia");
		res=0;
	}
	
	if(res==1)
		return true;
	else
		return false;
}

/*********************************************REPORTE DISPONIBILIDAD***********************************************************/
function valFormRepDisponibilidad(frm_reporteDisponibilidad){
	band=1;
	if(frm_reporteDisponibilidad.cmb_anios.value==""){
		alert("Seleccionar el A�o");
		band=0;
	}
	if(band==1 && frm_reporteDisponibilidad.cmb_meses.value==""){
		alert("Seleccionar el Mes");
		band=0;
	}
	if(band==1)
		return true;
	else
		return false;	
}

function valFormSelEquipoFiltro(frm_seleccionarEquipoFiltro){
	var res=1;
	var cantidad=frm_seleccionarEquipoFiltro.hdn_cantEquipos.value;
	var ctrl=1;
	
	if(!document.getElementById("ckbTodo").checked){
		while(ctrl<cantidad){		
			//Crear el id del CheckBox que se quiere verificar
			idCheckBox="ckb_equipo"+ctrl.toString();
			//Verificar que la cantidad y la aplicaci�n del Checkbox seleccionado no esten vacias
			if(document.getElementById(idCheckBox).checked)
				status = 1;
			ctrl++;
		}//Fin del While	
		
		//Verificar que al menos un equipo haya sido seleccionado, si la variable status vale 1, quiere decir que al menos un material fue seleccionado
		if(status!=1){
			alert("Seleccionar al Menos un Equipo para Generar el Reporte de Disponibilidad");
			res = 0;
		}
	}
	
	if(res==1)
		return true;
	else
		return false;		

}

/*Estan funci�n activa todos lo CheckBox del formulario de seleccion de Equipos para filtrar el reporte de Disponibilidad*/
function checarTodos(chkbox){
	for(var i=0;i<document.frm_seleccionarEquipoFiltro.elements.length;i++){
		//Variable
		elemento=document.frm_seleccionarEquipoFiltro.elements[i];
		if (elemento.type=="checkbox")
			elemento.checked=chkbox.checked;
	}	
}


/*Esta funcion desactiva el CheckBox de Seleccionar el Check ->Todo cuando un CheckBox de equipos es desseleccionado*/
function desSeleccionar(checkbox){
	if (!checkbox.checked){
		document.getElementById("ckbTodo").checked=false;
		if(checkbox.name.substr(0,3)=="ckb"&&checkbox.name.substr(0,4)!="ckb_")
			document.getElementById(checkbox.name.substr(0,3)+"_"+checkbox.name.substr(3)).checked=false;
	}
	else{
		if(checkbox.name.substr(0,4)=="ckb_")
			document.getElementById(checkbox.name.substr(0,4)+checkbox.name.substr(4)).checked=true;
	}
}

/**********************************************************************COSTOS************************************************************/
//Funcion que permite validar el formulario de costos por Familia
function valFormCostosFamilia(formulario){
	var band=1;  
	//Tomamos  las fechas iniciales
	var fechaIni = document.getElementById("txt_fechaIni").value;
	var fechaFin = document.getElementById("txt_fechaFin").value;
	//Validar el rango de fechas
	if(validarFechas(fechaIni, fechaFin)){
		if(formulario.cmb_familia.value==""){
			alert("Seleccionar Familia");
			band=0;
		}
	}
	else{
		band=0;
	}
	if(band==1)
		return true;
	else
		return false;
}

//Funcion que permite validar el formulario de costos por Equipo
function valFormCostosEquipo(formulario){
	var band=1;  
	//Tomamos las fechas iniciales
	var fechaIni = document.getElementById("txt_fechaIni2").value;
	var fechaFin = document.getElementById("txt_fechaFin2").value;
	//Validar las fechas
	if(validarFechas(fechaIni, fechaFin)){
		if(formulario.cmb_familia.value==""){
			alert("Seleccionar Familia");
			band=0;
		}
		if(formulario.cmb_equipo.value==""&&band==1){
			alert("Seleccionar Equipo");
			band=0;
		}
	}
	else{
		band=0;
	}
	if(band==1)
		return true;
	else
		return false;
}

/**************************************************************************************************************************/
/***************************************************BIT�CORA***************************************************************/
/**************************************************************************************************************************/
//Funcion para Evaluar los datos del frm_agregarFotoEquipo en Bitacoras
function valForAgregarFotoBitacora(frm_agregarFotoEquipo){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;

	//Se verifica que  el estatus de la fotograf�a haya sido seleccionado
	if (frm_agregarFotoEquipo.cmb_estatus.value==""){
		alert ("Seleccionar Estatus");
		band=0;
	}
	
	//Se verifica que el sistema haya sido ingresado
	if (frm_agregarFotoEquipo.file_documento.value==""&&band==1){
		alert ("Introducir Archivo");
		band=0;
	}
	
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}


/*Esta funcion valida que las fechas elegidas sean correctas*/
function valFormFechasBit(frm_consultarBitacora){
	var res=1;
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_consultarBitacora.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_consultarBitacora.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_consultarBitacora.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Cierre, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_consultarBitacora.txt_fechaFin.value.substr(0,2);
	var finMes=frm_consultarBitacora.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_consultarBitacora.txt_fechaFin.value.substr(6,4);
	
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	//Verificar que el a�o de Fin sea mayor al de Inicio
	if(fechaIni>fechaFin){
		res=0;
		alert ("La fecha de Inicio no puede ser mayor a la Fecha de Cierre");
	}
	
	if(res==1)
		return true;
	else
		return false;
}


//Funcion para validar la Hora en Bitacora Manteniniento Correctivo y el Preventivo
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
		alert("La Cantidad de D�gitos Excede el Tama�o Permitido");
		//Ponemos la caja de Texto como vacia
		document.getElementById("txt_tiempoTotal").value = "";
	}						
}


//Funcion para Evaluar los datos del frm_complementarBitacora en apartado Actividades
function valFormComplementarBitacora(frm_complementarBitacora){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;

	//Se verifica que el sistema haya sido ingresado
	if (frm_complementarBitacora.txt_sistema.value==""){
		alert ("Introducir Sistema");
		band=0;
	}

	//Se verifica que  la aplicaci�n haya sido ingresada
	if (frm_complementarBitacora.txt_aplicacion.value==""&&band==1){
		alert ("Introducir Aplicaci�n");
		band=0;
	}
	
	//Se verifica que la actividad hayasido ingresada
	if (frm_complementarBitacora.txa_actividad.value==""&&band==1){
		alert ("Introducir Actividad");
		band=0;
	}
	

	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

/*Funcion que agrega un nuevo elemento al combo de sistemas*/
function agregarSistema(comboBox){
	//Si la opcion seleccionada es agregar nueva unidad ejecutar el siguiete codigo
	if(comboBox.value=="AgregarNuevo"){
		var nvaMedida = "";
		var condicion = false;
		do{
			nvaMedida = prompt("Introducir Nuevo Sistema","Nuevo Sistema...");
			if(nvaMedida=="Nuevo Sistema..." ||  nvaMedida=="")
				condicion = true;	
			else
				condicion = false;
		}while(condicion);
		//Si el usuario presiono calncelar no se relaiza ninguan actividad de lo contrario asignar la nueva opcion al combo
		if(nvaMedida!=null){
			//Convertir a mayusculas la opcion dada
			nvaMedida = nvaMedida.toUpperCase();
			//variable que nos ayudara a saber si la nueva opcion ya esta registrada en el combo
			var existe = 0;
			
			for(i=0; i<comboBox.length; i++){
				//verificar que la nueva opcion no se encuentre dentro de las opciones actuales del combo
				if(comboBox.options[i].value==nvaMedida)
					existe = 1;
			} //FIN for(i=0; i<comboBox.length; i++)
			
			//Si la nva opcion no esta registrada agregarla como una adicional y preseleccionarla
			if(existe==0){
				//Agregar al final la nueva opcion seleccionada
				comboBox.length++;
				comboBox.options[comboBox.length-1].text = nvaMedida;
				comboBox.options[comboBox.length-1].value = nvaMedida;
				//Preseleccionar la opcion agregada
				comboBox.options[comboBox.length-1].selected = true;
			} // FIN if(existe==0)
			
			else{
				alert("El Sistema ya esta Registrado en las Opciones");
				comboBox.value = nvaMedida;
			}
		}// FIN if(nvaMedida!= null)
		
		else if(nvaMedida== null){
			comboBox.value = "";	
		}
	}// FIN if(comboBox.value=="NUEVA")
}
	
//Funcion para Evaluar los datos del frm_complementarBitacora en apartado Mecanicos
function valFormComplementarMecanico(frm_complementarMecanico){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;

		//Se verifica que el nombre del  mecanico haya sido ingresado
	if (frm_complementarMecanico.txt_mecanico.value==""){
		alert ("Introducir Nombre del Mec�nico");
		band=0;
	}
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}


/*Esta funcion Genera la fecha del prox mantenimiento*/
function valFormFecha(nomCajaTexto){
//Extraer los datos de la fecha Mantenimiento, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var dia=document.getElementById(nomCajaTexto).value.substr(0,2);
	var mes=document.getElementById(nomCajaTexto).value.substr(3,2);
	var anio=document.getElementById(nomCajaTexto).value.substr(6,4);
	
		
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fecha=mes+"/"+dia+"/"+anio;
	
	//Convertir la cadena a formato valido para JS
	fecha=new Date(fecha);
	
	//Sumarle los dias en horas
	fecha.setHours(750);
	
	//Agrega el 0 para el formato
	dia = fecha.getDate(); 
	if(dia<10)
		dia="0"+dia;
	//Agrega el 0 a los meses
	mes = parseInt(fecha.getMonth()) + 1;
	if(mes<10)
		mes="0"+mes;
	ano = fecha.getFullYear();
	//Almacena la fecha en el formato elegido
	fecha=dia+"/"+mes+"/"+ano;
	//Mandamos  al cuadro de texto txt_proxMant
	document.getElementById("txt_proxMant").value=fecha;
}


//Funcion para Evaluar los datos del frm_complementarBitacora Mantenimiento Preventivo
function valFormRegistrar(frm_registrarBitacora){
	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;	
	//Se verifica que el tiempo total haya sido ingresado
	if (frm_registrarBitacora.txt_horometro.value==0.00||frm_registrarBitacora.txt_horometro.value==""){
		alert ("El Hor�metro no puede ser igual a Cero (0)");
		band=0;			
	}
	//Se verifica que el tiempo total haya sido ingresado
	if (frm_registrarBitacora.txt_odometro.value==0.00||frm_registrarBitacora.txt_odometro.value==""){
		alert ("El Od�metro no puede ser igual a Cero (0)");
		band=0;			
	}


	//Se verifica que el tiempo total haya sido ingresado
	if (frm_registrarBitacora.txt_horometro.value==0&&frm_registrarBitacora.txt_horometro.value==0.00){
		alert ("El Hor�metro no puede ser igual a Cero(0)");
		band=0;			
	}
	//Se verifica que el tiempo total haya sido ingresado
	if (frm_registrarBitacora.txt_tiempoTotal.value==""&&band==1){
		alert ("Ingresar Tiempo Total");
		band=0;			
	}
	//Se verifica que el tiempo total haya sido ingresado
	if (band==1){
		//Declaramos variable tiempo para guardar el valor de txt_tiempoTotal y quitarle los dos puntos 00:00
		var tiempo = parseInt(frm_registrarBitacora.txt_tiempoTotal.value.replace(/:/g, ''));
		if(tiempo==0){
			alert ("El Tiempo Total no Puede Ser Igual a Cero(0)");
			band=0;
		}

	}
	//Se verifica que el costo Mano de Obra haya sido ingresado
	if (frm_registrarBitacora.txt_costoManoObra.value==0.00&&band==1){
		alert ("El Costo de Mano de Obra no Puede Ser Igual a Cero(0)");
		band=0;
	}
	
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
	
}


//Funcion para Evaluar los datos del Mantenimiento Correctivo
function valFormBitacoraMttoCorr(frm_registrarBitacora){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;	

	//Se verifica que la clave de la orden de trabajo haya sido ingresada
	if (frm_registrarBitacora.cmb_area.value==""){
		alert ("Seleccionar �rea");
		band=0;
	}

	if(frm_registrarBitacora.cmb_familia.value==""&&band==1){
		alert ("Seleccionar Familia");
		band=0;
	}
		
	if (frm_registrarBitacora.cmb_claveEquipo.value==""&&band==1){
		alert ("Seleccionar Clave Equipo");
		band=0;
	}
	
	if (frm_registrarBitacora.txt_cantMet.value==0&&band==1){
		alert ("La cantidad de M�trica no puede ser igual a Cero (0)");
		band=0;
	}	
	
	//Se verifica que el odometro haya sido ingresado
	if (frm_registrarBitacora.txt_cantMet.value=="" && band==1){
		alert ("Ingresar Cantidad de la M�trica");
		band=0;
	}
	
	if(band==1){
		if(!validarEntero(frm_registrarBitacora.txt_cantMet.value.replace(/,/g,''),"La Cantidad de la M�trica"))
			band = 0;		
	}
				
	//Se verifica que el tiempo total haya sido ingresado
	if (frm_registrarBitacora.txt_tiempoTotal.value=="" && band==1){
		alert ("Ingresar Tiempo Total");
		band=0;
	}
	
	//Se verifica que el tiempo sea mayor a 0
	if(band==1){
		//Declaramos variable tiempo para guardar el valor de txt_tiempoTotal y quitarle los dos puntos 00:00
		var tiempo=parseInt(frm_registrarBitacora.txt_tiempoTotal.value.replace(/:/g, ''));
		if (tiempo==0){			
			alert ("El Tiempo Total no Puede Ser Igual a Cero(0)");
			band=0;
		}
	}
	
	//Se verifica que el turno haya sido ingresado
	if (frm_registrarBitacora.cmb_turno.value=="" && band==1){
		alert ("Seleccionar Turno");
		band=0;
	}
	
	//Se verifica que el costo Mano de Obra haya sido ingresado
	if (frm_registrarBitacora.txt_costoManoObra.value==0 && band==1){
		alert ("Ingresar Costo de Mano de Obra");
		band=0;
	}
	
	
	//Verificar que el Costo de la Mano de Obra no sea igual a 0
	if(band==1){
		if(!validarEntero(frm_registrarBitacora.txt_costoManoObra.value.replace(/,/g,''),"El Costo de la Mano de Obra"))
			band = 0;
	}
	
	
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

//Funcion para Evaluar los datos del formulario registrar material del matenimiento Preventivo y correctivo
function valFormRegMatMtto(frm_materiales){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var flag=1;
 
	//Se verifica que  la clave del vale haya sido ingresada
	
	if(frm_materiales.txt_claveVale.value==""){
		alert ("Introducir Clave del Vale");
		flag=0;
	}
	
	if(frm_materiales.hdn_claveValida.value=="no"){
		alert ("El Vale Ya ha Sido Registrado a Este Equipo Previamente");
		flag=0;
	}
			
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (flag==1)
		return true;
	else
		return false;
}



//Funcion para Evaluar los datos del frm_complementarBitacora en apartado Mecanicos
function valFormComplementarMecanico(frm_complementarMecanico){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;

		//Se verifica que el nombre del  mecanico haya sido ingresado
	if (frm_complementarMecanico.txt_mecanico.value==""){
		alert ("Introducir Nombre del Mec�nico");
		band=0;
	}
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}


//Funcion para validar el formulario de consulta
function valFormConsultarBitacora(frm_consultarBitacora){
	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;
	
	//Se verifica que  la clave del vale haya sido ingresada
	if (frm_consultarBitacora.txt_bitacora.value==""){
		alert ("Seleccionar Orden de Trabajo");
		band=0;
	}

	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}


//Funcion para validar el formulario de consulta
function valFormRegistrarBitacora(frm_elegirOrdenTrabajo){
	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;
	
	//Se verifica que  la clave del vale haya sido ingresada
	if (frm_elegirOrdenTrabajo.cmb_OT.value==""){
		alert ("Seleccionar Orden de Trabajo");
		band=0;
	}

	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

function consultarOTEBitacora(idOrdenServicio){
	if(idOrdenServicio!="")
		window.open('../../includes/generadorPDF/ordenServicioExterno.php?id_orden='+idOrdenServicio+'&nom_depto=MINA&fecha_reg=01/06/2012', '_blank', 'top=100, left=100, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no');
	else
		alert("Seleccionar �rden de Trabajo Externa");

}

/***************************************************************************************************************************************/
/*************************************************************GENERAR REQUISICION*******************************************************/
/***************************************************************************************************************************************/
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

/***************************************************************************************************************************************************************/
/*****************************************************FORMULARIO REGISTRAR HOROMETRO************************************************************************/
/***************************************************************************************************************************************************************/
//Funci�n que permite desabilitar comboBox y cajas de texto al presionar el bot�n limpiar en el formulario
function desabilitar(tipo){
	//Verificamos la cantidad de registros esta variable es tomada del op_registrarHoroOdometro
	var cantReg=document.getElementById("hdn_cant").value-1;
	if(tipo=="HORO"){
		//Recorremos los resultados
		for(i=1;i<=cantReg;i++){
			//Ponemos las cajas de texto como disable
			document.getElementById("txt_horoIni" + i).disabled=true;
			document.getElementById("txt_horoFin" + i).disabled=true;
			document.getElementById("txt_hrsEfectivas" + i).disabled=true;
			document.getElementById("txt_hrsEfectivas" + i).readOnly=false;
		}
	}
	if(tipo=="ODO"){
		//Recorremos los resultados
		for(i=1;i<=cantReg;i++){
			//Ponemos las cajas de texto como disable
			document.getElementById("txt_odoIni" + i).disabled=true;
			document.getElementById("txt_odoFin" + i).disabled=true;
			document.getElementById("txt_total" + i).disabled=true;
		}
	}
}


/*Esta funci�n valida  el check box de Mtto Concreto*/
function activarCampos (campo, noRegistro){
	if (campo.checked){
		document.getElementById("txt_horoIni" + noRegistro).disabled=false;
		document.getElementById("txt_horoFin" + noRegistro).disabled=false;
		document.getElementById("txt_hrsEfectivas" + noRegistro).disabled=false;
		document.getElementById("txt_hrsEfectivas" + noRegistro).readOnly=true;
	}
	else{
		document.getElementById("txt_horoIni" + noRegistro).value=document.getElementById("txt_horoIni" + noRegistro).defaultValue;
		document.getElementById("txt_horoIni" + noRegistro).disabled=true;
		document.getElementById("txt_horoFin" + noRegistro).value=document.getElementById("txt_horoFin" + noRegistro).defaultValue;
		document.getElementById("txt_horoFin" + noRegistro).disabled=true;
		document.getElementById("txt_hrsEfectivas" + noRegistro).value=document.getElementById("txt_hrsEfectivas" + noRegistro).defaultValue;
		document.getElementById("txt_hrsEfectivas" + noRegistro).disabled=true;
		document.getElementById("txt_hrsEfectivas" + noRegistro).readOnly=false;
	}
}

/*Esta funci�n valida  el check box de Mtto Concreto*/
function activarCamposMttoMina(campo, noRegistro){
	if (campo.checked){
		document.getElementById("txt_horoIni" + noRegistro).disabled=false;
		document.getElementById("txt_horoFin" + noRegistro).disabled=false;
		document.getElementById("txt_hrsEfectivas" + noRegistro).disabled=false;
		document.getElementById("txt_hrsEfectivas" + noRegistro).readOnly=true;
		document.getElementById("txt_hrsMttoPrev" + noRegistro).disabled=false;
	}
	else{
		document.getElementById("txt_horoIni" + noRegistro).value=document.getElementById("txt_horoIni" + noRegistro).defaultValue;
		document.getElementById("txt_horoIni" + noRegistro).disabled=true;
		document.getElementById("txt_horoFin" + noRegistro).value=document.getElementById("txt_horoFin" + noRegistro).defaultValue;
		document.getElementById("txt_horoFin" + noRegistro).disabled=true;
		document.getElementById("txt_hrsEfectivas" + noRegistro).value=document.getElementById("txt_hrsEfectivas" + noRegistro).defaultValue;
		document.getElementById("txt_hrsEfectivas" + noRegistro).disabled=true;
		document.getElementById("txt_hrsEfectivas" + noRegistro).readOnly=false;
		document.getElementById("txt_hrsMttoPrev" + noRegistro).value=document.getElementById("txt_hrsMttoPrev" + noRegistro).defaultValue;
		document.getElementById("txt_hrsMttoPrev" + noRegistro).disabled=true;
	}
}



/*Esta funci�n valida los datos en el horometro*/
function valFormHoro(frm_registrarHorometroEquipo){	
	//Si el valor se mantiene en 1, entonces el proceso de validaci�n fue satisfactorio
	var res = 1;
	//Variable para manejar el mensaje de validaci�n satisfactoria
	var msg = 0;
	//Variable para saber si al menos un equipo fue seleccionado
	var status = 0;
	//Variable para controlar la cantidad de registros
	var ctrl= 1;
	//Variable para almacenar la cantidad de registros
	var cantidad = document.getElementById("hdn_cant").value-1;
	//Variables que almacenara el nombre de cada CheckBox Seleccionado y las cajas de texto de cantidad y aplicaci�n relacionada a el
	var idCheckBox = "";
	var idTxtHoroIni = "";
	var idTxtHoroFin = "";
	var idTxtHrsServ = "";
	var idTxtHrsMuer = "";
	var idTxtHrsEfect = "";
	var id_cmbTurno="";
	var idHdnNombre = "";
	
	while(ctrl<=cantidad){		
		//Crear el id del CheckBox que se quiere verificar
		idCheckBox="ckb_equipo"+ctrl.toString();
		
		//Verificar que la cantidad y la aplicaci�n del Checkbox seleccionado no esten vacias
		if(document.getElementById(idCheckBox).checked){
			status = 1;
			//Crear el id del Caja de Texto Oculta de Nombre
			idHdnNombre = "hdn_nombre"+ctrl.toString();
			var nombre = document.getElementById(idHdnNombre).value;
			//Crear el id de la Caja de Texto del Horometro Inicial
			idTxtHoroIni = "txt_horoIni"+ctrl.toString();
			//Crear el id de la Caja de Texto del Horometro Final
			idTxtHoroFin = "txt_horoFin"+ctrl.toString();
			//Crear el id de la Caja de Texto de las Horas de Servicio
			idTxtHrsServ = "txt_hrsServicio"+ctrl.toString();			
			//Crear el id de la Caja de Texto de las Horas Muertas 
			idTxtHrsMuer = "txt_hrsMuertas"+ctrl.toString();
			//Crear el id de la Caja de Texto de las Horas Efectivas
			idTxtHrsEfect = "txt_hrsEfectivas"+ctrl.toString();
			//Crear el id del combo del turno
			id_cmbTurno="cmb_turno";
					
			if(document.getElementById(idTxtHoroIni).value==""){				
				alert("Ingresar Horometro Inicial para el Equipo "+ nombre);
				msg = 1;
			}
			//Validar que se haya ingresado el horometro final
			if(document.getElementById(idTxtHoroFin).value=="" && msg!=1){
				msg = 1;
				alert("Ingresar Hor�metro Final: "+nombre);
			}
			//Validar que las horas Efectivas sean mayores a 0
			if(parseInt(document.getElementById(idTxtHrsEfect).value)==0&& msg!=1){
				msg = 1;
				alert("Las Horas Efectivas no Pueden ser 0 para el Equipo: "+nombre);
			}	
			//Validar que el turno haya sido seleccionado
			if(document.getElementById(id_cmbTurno).value==""&& msg!=1){
				msg = 1;
				alert("Seleccione Turno");
			}
		}
		ctrl++;
	}//Fin del While	
	
	
	//Verificar que al menos un equipo haya sido seleccionado, si la variable status vale 1, quiere decir que al menos un material fue seleccionado
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

//Funcion para calcular las horas de servicio del Horometro
function calcularHrsServicio(noRegistro){
	var regIni=document.getElementById("txt_horoIni"+noRegistro).value;
	var regFin=document.getElementById("txt_horoFin"+noRegistro).value;
	
	//Realizamos la operacion correspondiente para obterner las horas de servicio
	var diferencia = regFin.replace(/,/g,'') - regIni.replace(/,/g,'');
	//Verificamos que el registro final sea mayor al inicial
	if(regIni!="" && regFin!=""){
		if(diferencia<0){
			//Si es asi enviamos alerta y vaciamos los campos
			alert("El Registro Inicial no Puede Ser Mayor que el Registro Final");
			document.getElementById("txt_horoIni" + noRegistro).value=document.getElementById("txt_horoIni" + noRegistro).defaultValue;
			document.getElementById("txt_horoFin" + noRegistro).value=document.getElementById("txt_horoFin" + noRegistro).defaultValue;
			document.getElementById("txt_hrsEfectivas" + noRegistro).value=document.getElementById("txt_hrsEfectivas" + noRegistro).defaultValue;
		}
		else{
			formatCurrency(diferencia,"txt_hrsEfectivas"+noRegistro.toString());
		}
	}
}//Cierre de la funcion calcularHrsServicio(campo,noRegistro)


function calcularHrsEfectivas(campo,noRegistro){
	var nomCampoActual = campo.name;
	var cond = false;
	var hrs_serv;
	var hrs_dead;
	
	//Llamada de la funcion desde el campo de txt_horoIni
	if(nomCampoActual=="txt_hrsServicio"+noRegistro.toString()){
		cond = nomCampoActual!="" && document.getElementById("txt_hrsMuertas"+noRegistro.toString()).value!="";
		if(cond){
			hrs_serv = campo.value;
			hrs_dead = document.getElementById("txt_hrsMuertas"+noRegistro.toString()).value;
		}
	}
	else{//Llamada de la funcion desde el campo de txt_horoFin
		cond = nomCampoActual!="" && document.getElementById("txt_hrsServicio"+noRegistro.toString()).value!="";
		if(cond){
			hrs_dead = campo.value;
			hrs_serv = document.getElementById("txt_hrsServicio"+noRegistro.toString()).value;
		}
	}
	if(cond){
		//Realizamos las operaciones correspondientes para obtener las horas efectivas
		var diferencia = hrs_serv.replace(/,/g,'') - hrs_dead.replace(/,/g,'');
		//Verificamos que las horas de servicio sean mayores a las horas muertas	
		if(diferencia<0){
			//Si es asi enviamos alerta y vaciamos los cuadtros de texto
			alert("El Registro de Horas Muertas no Puede Ser Mayor que el Registro de Horas de Servicio");
			document.getElementById("txt_hrsMuertas" + noRegistro).value="";
			document.getElementById("txt_hrsEfectivas" + noRegistro).value="";
		}
		else{
			formatCurrency(diferencia,"txt_hrsEfectivas"+noRegistro.toString());
		}
	}					
	
}
/***************************************************************************************************************************************************************/
/*****************************************************FORMULARIO REGISTRAR ODOMETRO************************************************************************/
/***************************************************************************************************************************************************************/
//Funci�n que permite desabilitar comboBox y cajas de texto al presionar el bot�n limpiar en el formulario
function desabilitarOro(){
	//Verificamos la cantidad de registros esta variable es tomada del op_registrarHoroOdometro
	var cantReg=document.getElementById("hdn_cant").value-1;;
	//Recorremos los resultados
	for(i=1;i<=cantReg;i++){
		//Ponemos las cajas de texto como disable
		document.getElementById("txt_odoIni" + i).disabled=true;
		document.getElementById("txt_odoFin" + i).disabled=true;
	}
}


/*Esta funci�n valida  el check box*/
function activarCamposOdo (campo, noRegistro){
	if (campo.checked){
		document.getElementById("txt_odoIni" + noRegistro).disabled=false;
		document.getElementById("txt_odoFin" + noRegistro).disabled=false;
	}
	else{
		document.getElementById("txt_odoIni" + noRegistro).value="";
		document.getElementById("txt_odoIni" + noRegistro).disabled=true;
		document.getElementById("txt_odoFin" + noRegistro).value="";
		document.getElementById("txt_odoFin" + noRegistro).disabled=true;
	}
}


/*Esta funci�n valida los datos en el odometro*/
function valFormOdo(frm_registrarOdometroEquipo){	
	//Si el valor se mantiene en 1, entonces el proceso de validaci�n fue satisfactorio
	var res = 1;
	//Variable para manejar el mensaje de validaci�n satisfactoria
	var msg = 0;
	//Variable para saber si al menos un equipo fue seleccionado
	var status = 0;
	//Variable para controlar la cantidad de registros
	var ctrl= 1;
	//Variable para almacenar la cantidad de registros
	var cantidad =document.getElementById("hdn_cant").value-1;
	//Variables que almacenara el nombre de cada CheckBox Seleccionado y las cajas de texto de cantidad y aplicaci�n relacionada a el
	var idCheckBox = "";
	idTxtOdoIni = "";
	idTxtOdoFin = "";
	id_cmbTurno="";
	var idHdnNombre = "";
	
	while(ctrl<=cantidad){		
		//Crear el id del CheckBox que se quiere verificar
		idCheckBox="ckb_equipo"+ctrl.toString();
		
		//Verificar que la cantidad y la aplicaci�n del Checkbox seleccionado no esten vacias
		if(document.getElementById(idCheckBox).checked){
			status = 1;
			//Crear el id del Caja de Texto Oculta de Nombre
			idHdnNombre = "hdn_nombre"+ctrl.toString();
			var nombre = document.getElementById(idHdnNombre).value;
			//Crear el id de la Caja de Texto del Odometro Inicial
			idTxtOdoIni = "txt_odoIni"+ctrl.toString();
			//Crear el id de la Caja de Texto del Odometro Final
			idTxtOdoFin = "txt_odoFin"+ctrl.toString();
			//Crear el id de del combo de turno 
			id_cmbTurno="cmb_turno";
			
			//Verificamos que la el odometro inicial haya sido ingresado
			if(document.getElementById(idTxtOdoIni).value==""){				
				alert("Ingresar Od�metro Inicial para el Equipo "+ nombre);
				msg = 1;
			}
			else{
				//Validar que la cantidad sea un numero entero valido
				if(validarEntero(document.getElementById(idTxtOdoIni).value.replace(/,/g,''),"EL Od�metro Inicial para "+nombre)){
					//Validar que se haya ingresado el Odometro final
					if(document.getElementById(idTxtOdoFin).value==""){
						msg = 1;
						alert("Ingresar Od�metro Final: "+nombre);
					}
					//Validar que el turno haya sido seleccionado
					if(document.getElementById(id_cmbTurno).value==""&& msg!=1){
						msg = 1;
						alert("Seleccione Turno");
					}
				}
				else{
					msg = 1;
				}
			}
		}
		ctrl++;
	}//Fin del While	
	
	
	//Verificar que al menos un equipo haya sido seleccionado, si la variable status vale 1, quiere decir que al menos un material fue seleccionado
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

//Funcion para caluclar el total del odometro
function calcularOdometro(campo,noRegistro){
	//Se crean las variables para obtener el campo seleccionado
	var nomCampoActual = campo.name;
	var cond = false;
	var regIni;
	var regFin;
	
	//Llamada de la funcion desde el campo de txt_odoIni
	if(nomCampoActual=="txt_odoIni"+noRegistro.toString()){
		cond = nomCampoActual!="" && document.getElementById("txt_odoFin"+noRegistro.toString()).value!="";
		if(cond){
			regIni = campo.value;
			regFin = document.getElementById("txt_odoFin"+noRegistro.toString()).value;
		}
	}
	else{//Llamada de la funcion desde el campo de txt_odoFin
		cond = nomCampoActual!="" && document.getElementById("txt_odoIni"+noRegistro.toString()).value!="";
		if(cond){
			regFin = campo.value;
			regIni = document.getElementById("txt_odoIni"+noRegistro.toString()).value;
		}
	}
			
	
	//Verificamos que el registro inicial sea menor al registro final	
	if(cond){//Primera Condicion
		//Antes de obtener la diferencia, verificar si el registro inicial no es menor que el ultio registro realizado para el vehiculo en turno
		if(document.getElementById("hdn_regFinal"+noRegistro.toString()).value!=""){
			var regAnterior = parseInt(document.getElementById("hdn_regFinal"+noRegistro.toString()).value);
			var regIniActual = parseInt(regIni.replace(/,/g,''));
			if(regIniActual<regAnterior){
				//Notificar al usuario sobre la inconsistencia de los datos y vaciar los campos
				alert("El Registro Inicial del Od�metro no Puede \nSer Menor que el Registro de la Fecha Anterior");
				document.getElementById("txt_odoIni" + noRegistro).value="";
				document.getElementById("txt_odoFin" + noRegistro).value="";
				document.getElementById("txt_total" + noRegistro).value="";
				cond = false;
			}
		}
		
		
		if(cond){//Segunda Considicion
			//De lo contrario se realiza la operacion de resta para obtener el odometro total
			var diferencia = regFin.replace(/,/g,'') - regIni.replace(/,/g,'');
			if(diferencia<0){
				//Si es asi se envia alerta y se vacian los campos
				alert("El Registro Inicial no Puede Ser Mayor que el Registro Final");
				document.getElementById("txt_odoIni" + noRegistro).value="";
				document.getElementById("txt_odoFin" + noRegistro).value="";
				document.getElementById("txt_total" + noRegistro).value="";
			}
			else{
				formatCurrency(diferencia,"txt_total"+noRegistro.toString());
			}
		}//Fin if(cond){//Segunda Considicion
	}//FIn if(cond){//Primera Condicion					
	
}

/***************************************************************************************************************************************************************/
/**************************************************FORMULARIO CONSULTAR HOROMETRO/ODOMETRO**********************************************************************/
/***************************************************************************************************************************************************************/
//Esta funci�n valida que hayan sido ingresados los datos necesarios para la consulta del horometro/odometro
function valFormConsultarMetrica(frm_consultarHoroOdometro){
	//Si el valor se mantiene en 1, entonces el proceso de validacion fue satisfactorio
	var res = 1;
	
	if (frm_consultarHoroOdometro.cmb_area.value==""){
		alert ("Seleccionar una �rea");
		res=0;
	}
	
	if (frm_consultarHoroOdometro.cmb_familia.value=="" && res==1){
		alert ("Seleccionar una Familia");
		res=0;
	}

	if (frm_consultarHoroOdometro.cmb_claveEquipo.value=="" && res==1){
		alert ("Seleccionar un Equipo");
		res=0;
	}
 
 	if(!validarFechas(frm_consultarHoroOdometro.txt_fechaIni.value,frm_consultarHoroOdometro.txt_fechaFin.value))
		res = 0;				

	
	if(res==1)
		return true;
	else
		return false;
}

//Esta funcion valida los datos que se ingresan en el formulario para modificar Metricas
function valFormModHoro(frm_modHoroMetrica){	
	//Si el valor se mantiene en 1, entonces el proceso de validaci�n fue satisfactorio
	var res = 1;
	//Variable para manejar el mensaje de validaci�n satisfactoria
	var msg = 0;
	//Variable para saber si al menos un equipo fue seleccionado
	var status = 0;
	//Variable para controlar la cantidad de registros
	var ctrl= 1;
	//Variable para almacenar la cantidad de registros
	var cantidad = document.getElementById("hdn_cant").value-1;
	//Variables que almacenara el nombre de cada CheckBox Seleccionado y las cajas de texto de cantidad y aplicaci�n relacionada a el
	var idCheckBox = "";
	var idTxtHoroIni = "";
	var idTxtHoroFin = "";
	var idTxtHrsServ = "";
	
	while(ctrl<=cantidad){		
		//Crear el id del CheckBox que se quiere verificar
		idCheckBox="ckb_editarMetrica"+ctrl.toString();
		
		//Verificar que la cantidad y la aplicaci�n del Checkbox seleccionado no esten vacias
		if(document.getElementById(idCheckBox).checked){
			status = 1;
			//Crear el id de la Caja de Texto del Horometro Inicial
			idTxtHoroIni = "txt_horoIni"+ctrl.toString();
			//Crear el id de la Caja de Texto del Horometro Final
			idTxtHoroFin = "txt_horoFin"+ctrl.toString();
			//Crear el id de la Caja de Texto de las Horas de Servicio
			idTxtHrsServ = "txt_hrsEfectivas"+ctrl.toString();			
					
			if(document.getElementById(idTxtHoroIni).value==""){				
				alert("Ingresar Horometro Inicial en el Registro "+ ctrl);
				msg = 1;
			}
			//Validar que se haya ingresado el horometro final
			if(document.getElementById(idTxtHoroFin).value=="0.00" && msg!=1){
				msg = 1;
				alert("Ingresar Hor�metro Final en el Registro: "+ctrl);
			}
			//Validar que las horas Efectivas sean mayores a 0
			if(parseInt(document.getElementById(idTxtHrsServ).value)==0&& msg!=1){
				msg = 1;
				alert("Las Horas Efectivas no Pueden ser 0 en el Registro: "+ctrl);
			}	
		}
		ctrl++;
	}//Fin del While	
	
	
	//Verificar que al menos un equipo haya sido seleccionado, si la variable status vale 1, quiere decir que al menos un material fue seleccionado
	if(status==1){
		//Si hubo algun mensaje de que falta ingresar un datos, no se cumplio con el proceso de validacion 
		if(msg==1)
			res = 0;
	}
	else{
		alert("Seleccionar al Menos un Registro para Editar");
		res = 0;
	}
	if(res==1)
		return true;
	else
		return false;		
}

/***************************************************************************************************************************************************************/
/**************************************************FORMULARIO AGREGAR REFACCIONES**********************************************************************/
/***************************************************************************************************************************************************************/
//Esta funci�n valida que hayan sido ingresados los datos necesarios para agregar refacciones de los equipos
function valFormAgregarRefacciones(frm_agregarRefacciones){
	//Si el valor se mantiene en 1, entonces el proceso de validacion fue satisfactorio
	var res = 1;
	
	if (frm_agregarRefacciones.txt_nomRefaccion.value==""){
		alert ("Ingresar el Nombre de la Refacci�n");
		res=0;
	}

	if (frm_agregarRefacciones.txa_descripcion.value=="" && res==1){
		alert ("Ingresar la Desripci�n de la Refacci�n");
		res=0;
	}

	if(res==1)
		return true;
	else
		return false;
}


//Funcion que se encarga de validar el formulario que permite eliminar refacciones de la Base de Datos en Modificar Documentacion de los Equipos
function valFormModificaRefacciones(frm_modificarRefacciones){
	//Si el valor se mantiene en 1, el proceso de validaci�n fue satisfactorio
	band = 1;
	
	//Si se le dio click al boton de regesar, no realizar la validacion de los datos
	if(frm_modificarRefacciones.hdn_bandera.value=="si"){
		//Variable que verifica que se haya seleccionado un radiobutton
		var flag=0;
		var cantidad=document.getElementsByName("rdb_refacciones").length;
		for (var i=0;i<cantidad;i++){
			if (document.getElementById("rdb_refacciones"+(i+1)).checked==true){
				flag=1;
			}
		}
		
		if (flag==0){
			alert("Seleccionar una Refaccion para Eliminar");
			band=0;
		}
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

/***************************************************************************************************************************************************************/
/**************************************************************SECCION DE ACEITES*******************************************************************************/
/***************************************************************************************************************************************************************/
//Funcion para agregar un nuevo Aceite
function agregarNuevoAceite(check){
	if(check.checked){
		document.getElementById("cmb_aceite").value="";
		document.getElementById("cmb_aceite").disabled=true;
		document.getElementById("txt_cantidad").value="";
		document.getElementById("txt_cantidad").readOnly=false;
		document.getElementById("txt_nuevoAceite").readOnly=false;
		document.getElementById("txt_nuevoAceite").focus();
		document.getElementById("etiquetaInc").style.visibility="hidden";
		document.getElementById("campoInc").style.visibility="hidden";
	}
	else{
		document.getElementById("cmb_aceite").disabled=false;
		document.getElementById("txt_nuevoAceite").value="";
		document.getElementById("txt_nuevoAceite").readOnly=true;
		if(document.getElementById("cmb_aceite").type!="hidden")
			document.getElementById("cmb_aceite").focus();
		document.getElementById("txt_cantidad").value="";
	}
}

//Funcion que aplica sobre el boton Limpiar
function restablecerAceites(){
	document.getElementById("txt_nuevoAceite").readOnly=true;
	document.getElementById("cmb_aceite").disabled=false;
	document.getElementById("txt_cantidad").readOnly=false;;
	document.getElementById("etiquetaInc").style.visibility="hidden";
	document.getElementById("campoInc").style.visibility="hidden";
}

function valFormSelEquipoAceite(frm_bitacoraAceites){
	res=1;
	
	if(frm_bitacoraAceites.cmb_turno.value==""){
		alert("Seleccionar el Turno");
		res=0;
	}
	
	if(res==1 && frm_bitacoraAceites.cmb_supervisor.value==""){
		alert("Seleccionar el Supervisor en Turno");
		res=0;
	}
	
	if(res==1 && frm_bitacoraAceites.cmb_familia.value==""){
		alert("Seleccionar la Familia");
		res=0;
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (res==1)
		return true;
	else
		return false;
}

//Funcion que se encarga de validar el formulario que permite agregar o actualizar los Aceites
function valFormCatalogoAceites(frm_gestionAceites){
	//Si el valor se mantiene en 1, el proceso de validaci�n fue satisfactorio
	band = 1;
	
	//Empezar a validar por el checkbox de nuevoAceite
	if(frm_gestionAceites.ckb_nuevoAceite.checked){
		if(frm_gestionAceites.txt_nuevoAceite.value==""){
			alert("Ingresar el Nombre del nuevo Aceite");
			band=0;
			frm_gestionAceites.txt_nuevoAceite.focus();
		}
		if(band==1 && frm_gestionAceites.txt_cantidad.value==""){
			alert("Ingresar la Cantidad de Aceite");
			band=0;
			frm_gestionAceites.txt_cantidad.focus();
		}
	}
	else{
		if(frm_gestionAceites.cmb_aceite.type!="hidden"){
			if(frm_gestionAceites.cmb_aceite.value==""){
				alert("Seleccionar el Aceite");
				band=0;
				frm_gestionAceites.cmb_aceite.focus();
			}
			if(band==1 && frm_gestionAceites.txt_incremento.value==""){
				alert("Ingresar la Cantidad de Incremento del Aceite");
				band=0;
				frm_gestionAceites.txt_incremento.focus();
			}
		}
		else{
			alert("Seleccionar la Opci�n 'Agregar Nuevo Aceite'");
			band=0;
			frm_gestionAceites.ckb_nuevoAceite.focus();
		}
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

//Funcion que se encargar de validar que un aceite agregado no exista ya en la BD
function validarAceiteRepetido(caja){
	//Obtener el valor de la Caja para ver el nombre del Aceite
	var nuevoAceite=caja.value.toUpperCase();
	//Variable que permite verificar si existe un dato o no en el combo de referencia
	var existe=0;
	for(i=0; i<document.getElementById("cmb_aceite").length; i++){
		//verificar que la nueva opcion no se encuentre dentro de las opciones actuales del combo
		if(document.getElementById("cmb_aceite").options[i].text==nuevoAceite){
			existe = 1;
			//Obtener el valor de la opcion seleccionada
			aceiteSel=document.getElementById("cmb_aceite").options[i].value;
			//Romper el ciclo
			break;
		}
	} //FIN for(i=0; i<comboBox.length; i++)
	if (existe==1){
		alert("El Aceite "+nuevoAceite+" ya existe");
		document.getElementById("cmb_aceite").value=aceiteSel;
		//Dechecar el check de Nuevo Puesto
		document.getElementById("ckb_nuevoAceite").checked = false;
		//Restablecer el formato de la caja de Texto
		caja.value="";
		caja.readOnly=true;
		//Habilitar el combo
		document.getElementById("cmb_aceite").disabled=false;
		//Ejecutar la funcion JavaScript
		obtenerAceite(document.getElementById("cmb_aceite"));
	}
}

//Function que activa o desactiva los campos de Texto de los registros de Aceite segun se seleccione o no, un checkbox
function activarDesactivarRegBitAceite(checkbox,no){
	if(checkbox.checked){
		document.getElementById("txt_cantidad"+no).readOnly=false;
		document.getElementById("txt_metrica"+no).readOnly=false;
		if(document.getElementById("cmb_aceite"+no)!=undefined)
			document.getElementById("cmb_aceite"+no).disabled=false;
	}
	else{
		document.getElementById("txt_cantidad"+no).readOnly=true;
		document.getElementById("txt_cantidad"+no).value="";
		document.getElementById("txt_metrica"+no).readOnly=true;
		document.getElementById("txt_metrica"+no).value="";
		if(document.getElementById("cmb_aceite"+no)!=undefined){
			document.getElementById("cmb_aceite"+no).value="";
			document.getElementById("cmb_aceite"+no).disabled=true;
		}
	}
}

//Function que activa o desactiva los campos de Texto de los registros de Aceite segun se seleccione o no, un checkbox
function activarDesactivarRegBitAceiteMina(checkbox,no){
	if(checkbox.checked){
		document.getElementById("txt_cantidad"+no).readOnly=false;
		if(document.getElementById("cmb_aceite"+no)!=undefined)
			document.getElementById("cmb_aceite"+no).disabled=false;
		document.getElementById("txa_comentarios"+no).disabled=false;
	}
	else{
		document.getElementById("txt_cantidad"+no).readOnly=true;
		document.getElementById("txt_cantidad"+no).value="";
		if(document.getElementById("cmb_aceite"+no)!=undefined){
			document.getElementById("cmb_aceite"+no).value="";
			document.getElementById("cmb_aceite"+no).disabled=true;
		}
		document.getElementById("txa_comentarios"+no).value="";
		document.getElementById("txa_comentarios"+no).disabled=true;
	}
}

//Funcion para validar que los campos del formulario frm_regBitAceite esten completos
function valFormGastoAceite(frm_regBitAceite){
	var res=1;
	var cantidad=frm_regBitAceite.hdn_cantidad.value;
	var ctrl=1;
	
	while(ctrl<=cantidad){		
		//Crear el id del CheckBox que se quiere verificar
		idCheckBox="ckb_equipo"+ctrl.toString();
		//Verificar que la cantidad y la aplicaci�n del Checkbox seleccionado no esten vacias
		if(document.getElementById(idCheckBox).checked){
			status = 1;
			//Crear el ID del combo a validar
			idAceite="cmb_aceite"+ctrl.toString();
			//Crear el ID de la caja de Consumo a validar
			idCantidad="txt_cantidad"+ctrl.toString();
			if(document.getElementById(idAceite)!=undefined){
				if(document.getElementById(idAceite).value==""){
					alert("Ingresar el Tipo de Aceite Consumido para el registro: "+ctrl.toString());
					res=0;
					break;
				}
			}
			else{
				alert("�NO Hay Aceites en Existencia o NO se tienen Registrados!");
				res=0;
				break;
			}
			if(res==1 && (document.getElementById(idCantidad).value=="" || document.getElementById(idCantidad).value=="0.00")){
				var index=document.getElementById(idAceite).selectedIndex;
				var texto=document.getElementById(idAceite).options[index].text;
				alert("Ingresar la Cantidad de Aceite '"+texto+"' Consumido para el Registro: "+ctrl.toString()+" \nNota: Debe ser Mayor a 0.00");
				res=0;
				break;
			}
		}
		ctrl++;
	}//Fin del While	
	
	//Verificar que al menos un equipo haya sido seleccionado, si la variable status vale 1, quiere decir que al menos un material fue seleccionado
	if(status!=1){
		alert("Seleccionar al Menos un Equipo para Registrar su Consumo de Aceite");
		res = 0;
	}
	
	if(res==1)
		return true;
	else
		return false;		

}

//Funcion para validar que las cantidades de aceite solicitadas no excedan el existente
function valRegistrarAceites(aceite,cantidad,no){
	if(aceite.value!="" && (cantidad.value!="" && cantidad.value!="0.00")){
		//Crear el ID para verificar el aceite seleccionado contra la variable hidden con el valor del total de Aceite
		idCombo="hdn_"+aceite.value.toString();
		cantidad=parseFloat(cantidad.value.replace(/,/g,''));
		if(document.getElementById(idCombo).value<cantidad){
			//Crear el ID del combo a validar
			idAceite="cmb_aceite"+no.toString();
			var index=document.getElementById(idAceite).selectedIndex;
			var texto=document.getElementById(idAceite).options[index].text;
			alert("No hay suficiente Aceite '"+texto+"' para despachar. \nCantidad Aceite Restante: '"+document.getElementById(idCombo).value+" LTS'\nCantidad Aceite Solicitada: '"+cantidad+" LTS'");
			document.getElementById("txt_cantidad"+no).value="0.00";
		}
		else{
			var cantCombos=document.getElementById("hdn_cantidad").value;
			var cont=1;
			var acumAceite=0;
			do{
				//Crear el ID del combo a validar
				idAceite="cmb_aceite"+cont.toString();
				if(document.getElementById(idAceite).value==aceite.value){
					//Crear el ID de la caja de Texto con la cantidad saliente de Aceite
					var cantAceite="txt_cantidad"+cont.toString();
					//Acumular la cantidad de Aceite del mismo tipo de diferentes secciones
					acumAceite+=parseFloat(document.getElementById(cantAceite).value.replace(/,/g,''));
				}
				cont++;
			}while(cont<=cantCombos);
			if(document.getElementById(idCombo).value<acumAceite){
				var index=document.getElementById("cmb_aceite"+no.toString()).selectedIndex;
				var texto=document.getElementById("cmb_aceite"+no.toString()).options[index].text;
				alert("No hay suficiente Aceite '"+texto+"' para despachar. \nCantidad Aceite Restante: '"+document.getElementById(idCombo).value+" LTS'\nCantidad Aceite Solicitada por Todos los Equipos: '"+acumAceite+" LTS'");
				document.getElementById("txt_cantidad"+no).value="0.00";
			}
		}
	}
}

//Funcion para restablecer los campos disabled y readonly a su estado original
function restablecerBitacoraAceite(){
	var cantCombos=document.getElementById("hdn_cantidad").value;
	var cont=1;
	do{
		//Verificar si se encuentra definido el primer combo, de ser asi, proceder con la deshabilitacion
		if(document.getElementById("cmb_aceite1")!=undefined){
			//Crear el ID del combo a deshabilitar
			var idAceite="cmb_aceite"+cont.toString();
			document.getElementById(idAceite).disabled=true;
		}
		//Crear el ID de la caja de Texto a establecer en Modo ReadOnly
		var cantAceite="txt_cantidad"+cont.toString();
		document.getElementById(cantAceite).readOnly=true;
		cont++;
	}while(cont<=cantCombos);
}

//Funcion para restablecer los campos disabled y readonly a su estado original
function restablecerBitacoraAceiteMina(){
	var cantCombos=document.getElementById("hdn_cantidad").value;
	var cont=1;
	do{
		//Verificar si se encuentra definido el primer combo, de ser asi, proceder con la deshabilitacion
		if(document.getElementById("cmb_aceite1")!=undefined){
			//Crear el ID del combo a deshabilitar
			var idAceite="cmb_aceite"+cont.toString();
			document.getElementById(idAceite).disabled=true;
		}
		//Crear el ID de la caja de Texto a establecer en Modo ReadOnly
		var cantAceite="txt_cantidad"+cont.toString();
		document.getElementById(cantAceite).readOnly=true;
		var cajaComentarios="txa_comentarios"+cont.toString();
		document.getElementById(cajaComentarios).disabled=true;
		cont++;
	}while(cont<=cantCombos);
}

//Funcion que valida los datos seleccionado del reporte de Aceites
function valFormRepAceites(frm_reporteAceites){
	//Si el valor se mantiene en 1, el proceso de validaci�n fue satisfactorio
	var band = 1;
	
	if(frm_reporteAceites.cmb_anios!=undefined){
		//Empezar a validar por el combo de A�os
		if(frm_reporteAceites.cmb_anios.value==""){
			alert("Seleccionar el A�o");
			band=0;
		}
	}
	//Validar el tipo de Reporte
	if(band==1 && frm_reporteAceites.cmb_tipo.value==""){
		alert("Seleccionar el Tipo de Reporte");
		band=0;
	}
	//Validar el Combo de Meses
	if(band==1 && frm_reporteAceites.cmb_meses.value==""){
		alert("Seleccionar el Mes");
		band=0;
	}
	//Validar el Combo de Meses
	if(band==1 && frm_reporteAceites.cmb_tipo.value=="S"){
		//Indicar al usuario si no desea seleccionar un Equipo como Filtro
		if(frm_reporteAceites.cmb_equipo.value==""){
			if(!confirm("No se ha Seleccionado Ning�n Equipo como Filtro. \n�Desea Continuar?"))
				band=0;
		}
	}
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

//Funcion que muestra los criterios para el Reporte de Consumo de Aceites
function valMostrarEquipos(tipoRep){
	if(tipoRep=="S"){
		document.getElementById("labelEquipo").style.visibility="visible";
		document.getElementById("cmb_equipo").style.visibility="visible";
	}
	else{
		document.getElementById("labelEquipo").style.visibility="hidden";
		document.getElementById("cmb_equipo").style.visibility="hidden";
	}
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

	//Verificar que el a�o de Fin sea mayor al de Inicio
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
	bonific=document.getElementById("txt_bonificaciones"+no);
	total=document.getElementById("txt_total"+no);
	if(check.id==("txt_he"+no)){
		//calcular el bono a aumentar por horas extra
		he = (parseFloat(sueldo_diario.value / 8 * check.value)) * 2;
		//recalcular el total
		total.value = parseFloat(sueldo_base.value) + parseFloat(he) + parseFloat(bonific.value);
		total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
		////recalcula el total del empleado
		recalcularTotal(document.getElementById("ckb_8hrs"+no),no,0);
		//recalcula el total del empleado
		recalcularTotal(document.getElementById("ckb_12hrs"+no),no,0);
		if(document.getElementById("ckb_8hrs"+no).checked == false && document.getElementById("ckb_12hrs"+no).checked == false){
			total.value = parseFloat(sueldo_base.value) + parseFloat(he) + parseFloat(bonific.value);
			total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
		}
	} else{
		//recalcula el total del empleado
		recalcularTotal(check,no,1);
	}
	if(document.getElementById("ckb_juevesAL"+no).checked || document.getElementById("ckb_viernesAL"+no).checked || document.getElementById("ckb_sabadoAL"+no).checked || document.getElementById("ckb_domingoAL"+no).checked || document.getElementById("ckb_lunesAL"+no).checked || document.getElementById("ckb_martesAL"+no).checked || document.getElementById("ckb_miercolesAL"+no).checked){
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
	if(document.getElementById("ckb_juevesAL"+num).checked || document.getElementById("ckb_viernesAL"+num).checked || document.getElementById("ckb_sabadoAL"+num).checked || document.getElementById("ckb_domingoAL"+num).checked || document.getElementById("ckb_lunesAL"+num).checked || document.getElementById("ckb_martesAL"+num).checked || document.getElementById("ckb_miercolesAL"+num).checked){
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
	if(incapacidad != 1){
		check4=document.getElementById(elemento+"D");
	} else {
		check4=document.getElementById(elementModif+no+"D");
	}
	sueldo_base=document.getElementById("txt_sb"+no);
	sueldo_diario=document.getElementById("txt_sd"+no);
	total=document.getElementById("txt_total"+no);
	
	
	
	//Si el checkbox esta checado, se calculan los totales
	if(incapacidad == 0){
		if(check.checked && check4.checked == false){
			//Se quita el sueldo base al total a pagar
			total.value=parseFloat(total.value) - parseFloat(sueldo_base.value);
			//aumenta dias al sueldo base
			sueldo_base.value=parseFloat(sueldo_base.value) + parseFloat(sueldo_diario.value);
			sueldo_base.value = parseFloat(Math.round(sueldo_base.value * 100) / 100).toFixed(2);
			//recalcula el total a pagar
			total.value=parseFloat(total.value) + parseFloat(sueldo_base.value);
			check2.checked=false;
			check3.checked=false;
			check4.checked=false;
		} else if(check4.checked){
			check2.checked=false;
			check3.checked=false;
			check4.checked=false;
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
		if(check.checked && (check2.checked || check4.checked)){
			//Se quita el sueldo base al total a pagar
			total.value=parseFloat(total.value) - parseFloat(sueldo_base.value);
			//descuenta dias al sueldo base
			sueldo_base.value=parseFloat(sueldo_base.value) - parseFloat(sueldo_diario.value);
			sueldo_base.value = parseFloat(Math.round(sueldo_base.value * 100) / 100).toFixed(2);
			//recalcula el total a pagar
			total.value=parseFloat(total.value) + parseFloat(sueldo_base.value);
			check2.checked=false;
			check3.checked=false;
			check4.checked=false;
		}
		else if(check3.checked != false){
			check2.checked=false;
			check3.checked=false;
			check4.checked=false;
		}
		agregarBonificacion(no,"txt_he"+no);
	} else if(incapacidad == 2){
		if(check3.checked && (check.checked || check4.checked)){
			check.checked=false;
			check2.checked=false;
			check4.checked=false;
			//descuenta dias al sueldo base
			sueldo_base.value=parseFloat(sueldo_base.value) - parseFloat(sueldo_diario.value);
			sueldo_base.value = parseFloat(Math.round(sueldo_base.value * 100) / 100).toFixed(2);
			//recalcula el total a pagar
			total.value=parseFloat(sueldo_base.value);
			total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
		} else if(check3.checked && check2.checked){
			check.checked=false;
			check2.checked=false;
			check4.checked=false;
			//recalcula el total a pagar
			total.value=parseFloat(sueldo_base.value);
			total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
		} else if(check3.checked == false){
			agregarBonificacion(no,"txt_he"+no);
		}
	} else if(incapacidad == 3){
		if(check4.checked && check.checked == false){
			//Se quita el sueldo base al total a pagar
			total.value=parseFloat(total.value) - parseFloat(sueldo_base.value);
			//aumenta dias al sueldo base
			sueldo_base.value=parseFloat(sueldo_base.value) + parseFloat(sueldo_diario.value);
			sueldo_base.value = parseFloat(Math.round(sueldo_base.value * 100) / 100).toFixed(2);
			//recalcula el total a pagar
			total.value=parseFloat(total.value) + parseFloat(sueldo_base.value);
			check.checked=false;
			check2.checked=false;
			check3.checked=false;
		} else if(check.checked){
			check.checked=false;
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
	}
	
	if(document.getElementById("ckb_juevesAL"+no).checked || document.getElementById("ckb_viernesAL"+no).checked || document.getElementById("ckb_sabadoAL"+no).checked || document.getElementById("ckb_domingoAL"+no).checked || document.getElementById("ckb_lunesAL"+no).checked || document.getElementById("ckb_martesAL"+no).checked || document.getElementById("ckb_miercolesAL"+no).checked){
		total.value=parseFloat(sueldo_base.value);
		total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
	}
	
}//Fin de function establecerAsistencia(no,elemento,incapacidad,elementModif,elementoModif2)

//Funcion que valida los datos para generar el Reporte de N�mina
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

	//Verificar que el a�o de Fin sea mayor al de Inicio
	if(fechaIni>fechaFin){
		res=0;
		alert ("La fecha de Inicio no puede ser mayor a la Fecha de Fin");
	}
	
	if(res==1)
		return true;
	else
		return false;
}

/****************************************************************************************************************************************/
/*********************************************REGISTRAR STATUS DE EQUIPOS****************************************************************/
/****************************************************************************************************************************************/
function valFormRegEstatusEquipos(frm_gestionEstatus){
	var res=1;
	var cantidad=frm_gestionEstatus.hdn_cantidad.value;
	var ctrl=1;
	
	while(ctrl<cantidad){		
		//Crear el id del CheckBox que se quiere verificar
		idCheckBox="ckb_equipo"+ctrl.toString();
		//Verificar que la cantidad y la aplicaci�n del Checkbox seleccionado no esten vacias
		if(document.getElementById(idCheckBox).checked)
			status = 1;
		ctrl++;
	}//Fin del While	
	
	//Verificar que al menos un equipo haya sido seleccionado, si la variable status vale 1, quiere decir que al menos un material fue seleccionado
	if(status!=1){
		alert("Seleccionar al Menos un Equipo para Registrar su Status");
		res = 0;
	}
	
	if(res==1)
		return true;
	else
		return false;
}

function activarRegEstatus(check,num,equipo){
	if(check.checked){
		//Activar Combos
		document.getElementById("cmb_disponible"+num+"�1�"+equipo).disabled=false;
		document.getElementById("cmb_disponible"+num+"�2�"+equipo).disabled=false;
		document.getElementById("cmb_disponible"+num+"�3�"+equipo).disabled=false;
		//Activar Areas de Texto
		document.getElementById("txa_observaciones"+num+"�1�"+equipo).disabled=false;
		document.getElementById("txa_observaciones"+num+"�2�"+equipo).disabled=false;
		document.getElementById("txa_observaciones"+num+"�3�"+equipo).disabled=false;
	}
	else{
		//Restablecer a estado original Combos
		document.getElementById("cmb_disponible"+num+"�1�"+equipo).value="DISPONIBLE";
		document.getElementById("cmb_disponible"+num+"�2�"+equipo).value="DISPONIBLE";
		document.getElementById("cmb_disponible"+num+"�3�"+equipo).value="DISPONIBLE";
		//Restablecer a estado original Areas de Texto
		document.getElementById("txa_observaciones"+num+"�1�"+equipo).value="";
		document.getElementById("txa_observaciones"+num+"�2�"+equipo).value="";
		document.getElementById("txa_observaciones"+num+"�3�"+equipo).value="";
		//Desactivar Combos
		document.getElementById("cmb_disponible"+num+"�1�"+equipo).disabled=true;
		document.getElementById("cmb_disponible"+num+"�2�"+equipo).disabled=true;
		document.getElementById("cmb_disponible"+num+"�3�"+equipo).disabled=true;
		//Desactivar Areas de Texto
		document.getElementById("txa_observaciones"+num+"�1�"+equipo).disabled=true;
		document.getElementById("txa_observaciones"+num+"�2�"+equipo).disabled=true;
		document.getElementById("txa_observaciones"+num+"�3�"+equipo).disabled=true;
	}
}

//Funcion que deshabilita los campos del formulario para registrar el Status de un Equipo
function restablerFormularioStatus(){
	//Contar la cantidad de Formularios que hay en la pagina
	cantForms=document.forms.length;
	//Contador para recorrer TODOS los Formularios
	i=0;
	//Recorrer todos los formularios declarados en la pagina actual
	do{
		//Verificar si el formulario tiene el mismo nombre del que queremos restablecer
		if(document.forms[i].name=="frm_gestionEstatus"){
			//Recorrer el arreglo de Elementos del Formulario
			for (ind=0;ind<document.forms[i].elements.length;ind++){ 
				//Si los elementos son de tipo diferente a checkbox y texto, deshabilitarlos
				if(document.forms[i].elements[ind].type!="checkbox" && document.forms[i].elements[ind].type!="text"){
					//Obtener el ID de cada Elemento
					idElemento=document.forms[i].elements[ind].id;
					//Verificar que el IdElemento sea diferente de vacio y deshabilitarlo
					if(idElemento!=""){
						document.getElementById(idElemento).disabled=true;
					}//if(idElemento!="")
				}//if(document.forms[i].elements[i].type=="text")
			}//Fin del For
		}
		i++;
	}while(i<cantForms);
}//Fin de function restablerFormularioStatus()

//Funcion que valida el formulario de seleccion de datos para los Mttos Programados
function valFormSelDatosProgMtto(frm_seleccionarDatos){
	var band=1;
	
	if(frm_seleccionarDatos.cmb_familia.value==""){
		band=0;
		alert("Seleccionar la Familia");
	}
	
	if(frm_seleccionarDatos.cmb_Mes.value=="" && band==1){
		band=0;
		alert("Seleccionar el Mes");
	}
	
	if(frm_seleccionarDatos.cmb_Anio.value=="" && band==1){
		band=0;
		alert("Seleccionar el A�o");
	}
	
	if(band==1)
		return true;
	else
		return false;
}

/****************************************************************************************************************************************/
/*****************************************************GESTION DE LLANTAS*****************************************************************/
/****************************************************************************************************************************************/
/*Esta funci�n agrega marcas de Llantas en la gestion de las mismas*/
function agregarMarcaLlantas(combo){
	if (combo.value!=""){
		if(combo.value=="NUEVAMARCA"){
			//Asignar el valor por default a Agregar Llanta
			document.getElementById("cmb_marca").value="NUEVAMARCA";
			//Linea que muestra un mensaje donde guardar la nueva Area
			var linea = prompt("Ingresar Marca de la Llanta","Nombre de la Marca...");
			//Verificar si el dato introducido es valido
			if(linea!=null && linea!="Nombre de la Marca..." && linea!="" && linea.length<=30){
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
					//Variable que permite verificar si existe un dato o no en el combo de referencia
					var existe=0;
					for(i=0; i<document.getElementById("cmb_marca").length; i++){
						//verificar que la nueva opcion no se encuentre dentro de las opciones actuales del combo
						if(document.getElementById("cmb_marca").options[i].value==linea)
							existe = 1;
					} //FIN for(i=0; i<comboBox.length; i++)
					if (existe==1){
						alert("La Marca ya existe");
						document.getElementById("cmb_marca").value=linea;
					}
					//Si el area existe, no continuar con el proceso
					if(existe!=1){
						//Agregar al final la nueva opcion seleccionada
						combo.length++;
						combo.options[combo.length-1].text = linea;
						//Ingresar un value aleatorio a la Llanta, solo para el manejo de la misma
						combo.options[combo.length-1].value = linea;
						//Preseleccionar la opcion agregada
						combo.options[combo.length-1].selected = true;
						//Mover el foco al siguiente Elemento
						document.getElementById("cmb_ubicacion").focus();
					}
				}
				else{
					alert("El Dato "+linea+" Ingresado No Es V�lido");
					document.getElementById("cmb_marca").value = "";
				}
			}
			else{
				//Deshabilitar los elementos del formulario
				document.getElementById("cmb_marca").value="";
				if(linea!=null && linea.length>30)
					alert("La Marca de la Llanta No puede ser Mayor a 20 Caracteres");
				else
					alert("Dato Ingresado No V�lido");
				document.getElementById("cmb_llanta").value = "";
			}
		}
	}
}//Fin de la Funcion agregarMarcaLlantas(combo)	

/*Funcion que valida el formulario de Gestion de Llantas*/
function valFormGestionLlantas(frm_gestionLlantas){
	var band=1;
	
	if(frm_gestionLlantas.cmb_llanta.value==""){
		band=0;
		alert("Seleccionar o Ingresar la Llanta");
	}
	
	if(band==1 && frm_gestionLlantas.cmb_marca.value==""){
		band=0;
		alert("Seleccionar o Ingresar la Marca de la Llanta");
	}

	if(band==1 && frm_gestionLlantas.cmb_equipos.value==""){
		band=0;
		alert("Ingresar la Ubicaci�n donde se Encuentra la Llanta");
	}
	
	/*if(band==1 && frm_gestionLlantas.txt_nuevas.value==""){
		band=0;
		alert("Ingresar el numero de Llantas Nuevas");
	}
	
	if(band==1 && frm_gestionLlantas.txt_reuso.value==""){
		band=0;
		alert("Ingresar el numero de Llantas Reusadas");
	}
	
	if(band==1 && frm_gestionLlantas.txt_deshecho.value==""){
		band=0;
		alert("Ingresar el numero de Llantas Desechadas");
	}*/
	
	if(band==1 && frm_gestionLlantas.txt_medida.value==""){
		band=0;
		alert("Ingresar la Medida de la Llanta");
	}
	
	if(band==1 && frm_gestionLlantas.txt_medidaRin.value==""){
		band=0;
		alert("Ingresar la Medida del Rin");
	}
	
	/*if(band==1 && frm_gestionLlantas.txt_costo.value==""){
		band=0;
		alert("Ingresar el costo de la llanta");
	}*/
	
	if(band==1)
		return true;
	else
		return false;
}

/*Funcion que valida el formulario de Gestion de Llantas*/
function valFormGestionDetalleLlanta(frm_gestionDetalleLlantas){
	var band=1;
	
	if(frm_gestionDetalleLlantas.cmb_tipo.value==""){
		band=0;
		alert("Seleccionar el Tipo de la Llanta");
		document.getElementById("cmb_tipo").focus();
	}
	
	if(band==1 && frm_gestionDetalleLlantas.txt_llanta.value==""){
		band=0;
		alert("Ingresar la clave de la Llanta");
		document.getElementById("txt_llanta").focus();
	}

	if(band==1 && frm_gestionDetalleLlantas.txt_posicion.value==""){
		band=0;
		alert("Ingresar la posicion de la Llanta");
		document.getElementById("txt_posicion").focus();
	}
	
	if(band==1 && frm_gestionDetalleLlantas.txt_metrica.value==""){
		band=0;
		alert("Ingresar el Odometro de la Llanta");
		document.getElementById("txt_metrica").focus();
	}
	
	if(band==1 && frm_gestionDetalleLlantas.cmb_estado.value==""){
		band=0;
		alert("Ingresar el Estado de la Llanta");
		document.getElementById("cmb_estado").focus();
	}
	
	if(band==1 && frm_gestionDetalleLlantas.txt_costoUni.value==""){
		band=0;
		alert("Ingresar el Costo de la Llanta");
		document.getElementById("txt_costoUni").focus();
	}
	
	if(band==1)
		return true;
	else
		return false;
}

/*Funcion que verifica la cantidad de Llantas para darles Salida en la bitacora*/
function verificarExistSalida(cajaSalida,cantOriginal,etiqueta){
	cantSalida = parseInt(cajaSalida.value);
	cantOriginal = parseInt(cantOriginal);
	if(cantSalida>cantOriginal){
		alert("La Existencia Actual NO alcanza a cubrir la Demanda de Llantas Ingresada, en Stock existen "+cantOriginal+" Llantas "+etiqueta);
		cajaSalida.value=0;
	}
}

/*Funcion que valida el formulario de la Bit�cora de Uso de Llantas*/
function valFormBitacoraLlantas(frm_bitacoraLlantas){
	var band=1;
	
	/*if(frm_bitacoraLlantas.cmb_llanta.value==""){
		band=0;
		alert("Seleccionar la Llanta");
		frm_bitacoraLlantas.cmb_llanta.focus();
	}*/
	
	if(band==1 && frm_bitacoraLlantas.cmb_turno.value==""){
		band=0;
		alert("Seleccionar el Turno");
		frm_bitacoraLlantas.cmb_turno.focus();
	}
	
	if(band==1 && frm_bitacoraLlantas.cmb_equipo.value==""){
		band=0;
		alert("Seleccionar el Equipo");
		frm_bitacoraLlantas.cmb_equipo.focus();
	}
	
	if(band==1 && frm_bitacoraLlantas.txt_odometro.value==""){
		band=0;
		alert("Ingresar el Odometro del equipo");
		frm_bitacoraLlantas.txt_odometro.focus();
	}
	
	if(band==1 && frm_bitacoraLlantas.txt_horometro.value==""){
		band=0;
		alert("Ingresar el Horometro del equipo");
		frm_bitacoraLlantas.txt_horometro.focus();
	}
	
	if(band==1 && frm_bitacoraLlantas.txt_codigo.value==""){
		band=0;
		alert("Ingresar el codigo del trabajador");
		frm_bitacoraLlantas.txt_codigo.focus();
	}
	
	for(var i=1; i<=frm_bitacoraLlantas.txt_existente.value; i++){
		if(band==1 && frm_bitacoraLlantas.elements["txt_existente"+i].value==""){
			band=0;
			alert("Ingresar la " + i +"� Llanta existente");
			frm_bitacoraLlantas.elements["txt_existente"+i].focus();
		}
	}
	
	for(var i=1; i<=frm_bitacoraLlantas.txt_sinCodigo.value; i++){
		if(band==1 && frm_bitacoraLlantas.elements["txt_sinCodigo"+i].value==""){
			band=0;
			alert("Ingresar la " + i +"� Llanta sin codigo");
			frm_bitacoraLlantas.elements["txt_sinCodigo"+i].focus();
		}
		if(band==1 && frm_bitacoraLlantas.elements["cmb_tipo"+i].value==""){
			band=0;
			alert("Ingresar el tipo de la " + i +"� Llanta sin codigo");
			frm_bitacoraLlantas.elements["cmb_tipo"+i].focus();
		}
	}
	
	for(var i=1; i<=frm_bitacoraLlantas.txt_desechadas.value; i++){
		if(band==1 && frm_bitacoraLlantas.elements["txt_desechadas"+i].value==""){
			band=0;
			alert("Ingresar la " + i +"� Llanta desechada");
			frm_bitacoraLlantas.elements["txt_desechadas"+i].focus();
		}
	}
	
	if(band==1)
		return true;
	else
		return false;
}

/*Funcion que valida los criterior de eleccion de datos segun el reporte de Llantas*/
function valFormRptLlantas(frm_reporteLlantas){
	var band=1;
	
	if(frm_reporteLlantas.cmb_anios.value==""){
		band=0;
		alert("Seleccionar el A�o");
		frm_reporteLlantas.cmb_anios.focus();
	}
	
	if(band==1 && frm_reporteLlantas.cmb_meses.value==""){
		band=0;
		alert("Seleccionar el Mes");
		frm_reporteLlantas.cmb_meses.focus();
	}
	
	if(band==1)
		return true;
	else
		return false;
}