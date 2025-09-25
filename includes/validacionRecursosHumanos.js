/**
  * Nombre del M�dulo: Mantenimiento                                               
  * �Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 21/Febrero/2011                                      			
  * Descripci�n: Este archivo contiene funciones para validar los diferentes formularios del M�dulo Mantenimiento
  */
/*****************************************************************************************************************************************************************************************/
/************************************************************************VALIDAR CARACTERES***********************************************************************************************/
/*****************************************************************************************************************************************************************************************/
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
		//8=BackSpace, 33=Admiraci�n Cierre, 34=Comillas, 35=Gato, 36=Signo Moneda, 37=Porcentaje, 38=Amperson, 40=Parentesis Apertura, 41=Parentesis Cierre, 42=Asterisco, 43=Simbolo Mas,
		//44=Coma, 45=Guion medio, 46=Punto, 47=Diagonal, 58=Dos Puntos, 59=Punto y Coma, 60=Menor Que, 61=Simbolo Igual, 62=Mayor Que, 63=Interrogacion Cierre, 64=Arroba, 91=Parentesis Cuad Apertura, 
		//93=Parentesis Cuad Cierre, 95=Guion Bajo, 123=Llave Apertura, 124=|, 125=Llave Cierre, 161=Admiracion Apertura, 176=�Grados, 191=Interregacion Aperura
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
		//44=Coma, 45=Guion medio, 46=Punto, 47=Diagonal, 58=Dos Puntos, 59=Punto y Coma, 60=Menor Que, 61=Simbolo Igual, 62=Mayor Que, 63=Interrogacion Cierre, 64=Arroba, 91=Parentesis Cuad Apertura, 
		//93=Parentesis Cuad Cierre, 95=Guion Bajo, 123=Llave Apertura, 124=|, 125=Llave Cierre, 161=Admiracion Apertura, 176=�Grados, 191=Interregacion Aperura
	}
	if(te==5){//Para cajas de texto que contengan valores tipo hora
		var teclas_especiales = [8, 58];		
		//8 = BackSpace, 58 = Dos Puntos
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


/*****************************************************************************************************************************************************************************************/
/************************************************************************FUNCIONES GENERALES**********************************************************************************************/
/*****************************************************************************************************************************************************************************************/
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
			alert("El Numero "+telefono.value+", NO es un Numero Telef�nico Valido");		
			telefono.value = "";
		}
	}
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
		//Ocultamos IMG a fin de que no se muestre en la pagina o formulario, Si el archivo subido no es una imagen, el tama�o asignadop sera de 0, lo cual lo hace una imagen invalida
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
		//al elemento bandera que le pasamos a la funcion le asigna el valor de SI, este elemento bandera es un elemento tipo hidden en el formulario a fin de poder hacer validacion
		//en caso que el tama�o no se cumpla, muestra una alerta de Im�gen no v�lida y al elemento bandera le asigna el valor NO
		setTimeout("if (tam>0&&tam<10240000){ document.getElementById('"+bandera+"').value='si'; return true}; else {alert('Introducir una Im�gen V�lida'); document.getElementById('"+bandera+"').value='no'; return false;}",900);
	}
	else
		document.getElementById(bandera).value="si";
}



/*Estan funci�n activa  y desactiva todos lo CheckBox de registrar asistencia a capacitaciones*/
function checarTodos(chkbox,nomForm){
	for(var i=0;i<document[nomForm].elements.length;i++){
		//Variable
		elemento=document[nomForm].elements[i];
		if (elemento.type=="checkbox")
			elemento.checked=chkbox.checked;
	}	
}


/*Esta funcion desactiva el CheckBox de Seleccionar Todo cuando un CheckBox de registrar asistencia a capacitaciones*/
function desSeleccionar(checkbox){	
	//Realizar Accion cuando el checkbox no esta seleccionado
	if (!checkbox.checked){
		//Cuando sea Deseleccionado un checkbox, hacer lo mismo con el Checkbox que selecciona todo
		document.getElementById("ckbTodo").checked=false;			
	}	
}


//Funcion para escribir directamente el �rea Recomendada a la que se le registrara un aspirante
function agregarNuevaArea(ckb_area, ckb_puesto, txt_area, txt_puesto, cmb_area, cmb_puesto){
	//Si el checkbox para nueva �rea Recomendada esta seleccionado, pedir la nueva area
	if (ckb_area.checked){
		//Solicitar al usuario el nombre de la Nueva Area
		var area = prompt("�Nombre de la Nueva �rea?","Nombre del �rea...");	
		//Verificar que el dato proporcionado sea valido
		if(area!=null && area!="Nombre del �rea..." && area!=""){
			//Asignar el valor obtenido a la caja de texto que lo mostrara
			document.getElementById(txt_area).value = area;
			//Activar el checkbox de seleccionar puesto
			document.getElementById(ckb_puesto).checked=true;
			//Solicitar el Puesto
			var puesto = prompt("�Nombre del Nuevo Puesto?","Nombre del Puesto...");	
			if(puesto!=null && puesto!="Nombre del Puesto..." && puesto!=""){
				//Asignar el valor obtenido a la caja de texto que lo mostrara
				document.getElementById(txt_puesto).value = puesto;
				//Verificar que el combo este definido para poder deshabilitarlo
				if (document.getElementById(cmb_area)!=null){
					//Deshabilitar el ComboBox para que el usuario no lo pueda modificar
					document.getElementById(cmb_area).disabled = true;
					//Deshabilitar el ComboBox para que el usuario no lo pueda modificar
					document.getElementById(cmb_puesto).disabled = true;
				}
			}
			else{//Regresar False si se presiona el bot�n cancelar o se asigna un valor equivocado
				ckb_area.checked = false;				
				//Borrar el valor previo de la caja de Texto
				document.getElementById(txt_area).value = "";
				//Deseleccionar el Checkbox de puesto
				document.getElementById(ckb_puesto).checked = false;
			}
		}
		else//Regresar False si se presiona el bot�n cancelar o se asigna un valor equivocado
			ckb_area.checked = false;
	}
	//Si el checkbox para nueva �rea se de-selecciona, borrar el dato escrito en la caja de texto y reactivar el combo del �rea Recomendada
	else{
		document.getElementById(ckb_puesto).checked=false;
		//Borrar los valores previos de las cajas de Texto
		document.getElementById(txt_area).value = "";						
		document.getElementById(txt_puesto).value = "";
		//Verificar que el combo este definido para poder Habilitarlo
		if (document.getElementById(cmb_area)!=null){
			//Deshabilitar el ComboBox para que el usuario no lo pueda modificar
			document.getElementById(cmb_area).disabled = false;
			//Deshabilitar el ComboBox para que el usuario no lo pueda modificar
			document.getElementById(cmb_puesto).disabled = false;		
		}
	}
	
}//Cierre de la funcion agregarAreaRecomendada(ckb_areaRecomendada)


//Esta funcion solicita al usuario el nuevo puesto y limpia las cajas de texto de area y puesto cuando es deseleccionado y activa los combos de area y puesto
function agregarNuevoPuesto(ckb_puesto, ckb_area, txt_area, txt_puesto, cmb_area, cmb_puesto){
	//Si el checkbox para el nuevo Puesto esta seleccionado, pedir el nombre de dicho puesto
	if (ckb_puesto.checked){
		var puesto = prompt("�Nombre del Nuevo Puesto?","Nombre del Puesto...");	
		if(puesto!=null && puesto!="Nombre del Puesto..." && puesto!=""){
			//Asignar el valor obtenido a la caja de texto que lo mostrara
			document.getElementById(txt_puesto).value = puesto;
			//Verificar que el combo este definido para poder deshabilitarlo
			if (document.getElementById(cmb_puesto)!=null)
				//Deshabilitar el ComboBox para que el usuario no lo pueda modificar
				document.getElementById(cmb_puesto).disabled = true;				
		}
		else
			//Regresar False si se presiona el bot�n cancelar o se asigna un valor equivocado
			ckb_puesto.checked = false;
	}
	//Si el checkbox para nuevo Puesto se deselecciona, borrar el dato escrito en la caja de texto y reactivar el combo de Puesto
	else{
		//Verificar si el Checkbox de Area esta seleccionado para deseleccionarlo y borrar la caja de texto de area
		if(document.getElementById(ckb_area).checked){
			document.getElementById(ckb_area).checked = false;
			document.getElementById(txt_area).value = "";
			document.getElementById(cmb_area).disabled = false;
		}
		
		document.getElementById(txt_puesto).value = "";
		//Verificar que el combo este definido para poder Habilitarlo
		if (document.getElementById(cmb_puesto)!=null)
			//Habilitar el ComboBox y el deseleccionar el CheckBox para que el usuario pueda agregar nueva informaci�n
			document.getElementById(cmb_puesto).disabled = false;				
	}
}


//Esta funcion solicita al usuario el nuevo pr�stamo y limpia el combos de prestamo
function agregarNuevoPrestamo(ckb_nuevoPrestamo, txt_nuevoPrestamo, cmb_nomPrestamo){
	//Si el checkbox para el nuevo prestamo esta seleccionado, pedir el nombre de dicho prestamo
	if(ckb_nuevoPrestamo.checked){
		var prestamo = prompt("�Nombre del Nuevo Pr�stamo?","Nombre del Pr�stamo...");	
		if(prestamo!=null && prestamo!="Nombre del Bono..." && prestamo!=""){
			//Asignar el valor obtenido a la caja de texto que lo mostrara
			document.getElementById(txt_nuevoPrestamo).value = prestamo;
			
			//Vaciar y Deshabilitar el ComboBox para que el usuario no lo pueda modificar
			document.getElementById(cmb_nomPrestamo).value = "";
			document.getElementById(cmb_nomPrestamo).disabled = true;
			
			//Vaciar el Area de Texto de Descripci�n en el caso que contenga algo
			document.getElementById("txa_descripcion").value = "";
		}//Cierre if(prestamo!=null && prestamo!="Nombre del Bono..." && prestamo!="")
		else{
			//Regresar False si se presiona el bot�n cancelar o se asigna un valor equivocado
			ckb_nuevoPrestamo.checked = false;
		}
	}//Cierre if(ckb_nuevoPrestamo.checked)	
	else{//Si el checkbox para nuevo Bono se deselecciona, borrar el dato escrito en la caja de texto y reactivar el combo de prestamo
		document.getElementById(txt_nuevoPrestamo).value = "";
		//Verificar que el combo este definido para poder Habilitarlo
		if (document.getElementById(cmb_nomPrestamo)!=null)
			//Habilitar el ComboBox y el deseleccionar el CheckBox para que el usuario pueda agregar nueva informaci�n
			document.getElementById(cmb_nomPrestamo).disabled = false;				
	}
}

//Esta funcion solicita al usuario la nueva deduccion y limpia el combos descuento
function agregarNuevaDed(ckb_nuevaDed, txt_nuevaDeduccion, cmb_tipoDeduccion){
	//Si el checkbox para el nuevo deduccion esta seleccionado, pedir el nombre de dicho deduccion
	if (ckb_nuevaDed.checked){
		var deduccion = prompt("�Nombre de la Nueva Deducci�n?","Nombre de la Deducci�n...");	
		if(deduccion!=null && deduccion!="Nombre de la Deducci�n..." && deduccion!=""){
			//Asignar el valor obtenido a la caja de texto que lo mostrara
			document.getElementById(txt_nuevaDeduccion).value = deduccion;
			//Verificar que el combo este definido para poder deshabilitarlo
			if (document.getElementById(cmb_tipoDeduccion)!=null)
				//Deshabilitar el ComboBox para que el usuario no lo pueda modificar
				document.getElementById(cmb_tipoDeduccion).disabled = true;				
		}
		else
			//Regresar False si se presiona el bot�n cancelar o se asigna un valor equivocado
			ckb_nuevaDed.checked = false;
	}
	//Si el checkbox para nuevo Bono se deselecciona, borrar el dato escrito en la caja de texto y reactivar el combo de bono
	else{
		document.getElementById(txt_nuevaDeduccion).value = "";
		//Verificar que el combo este definido para poder Habilitarlo
		if (document.getElementById(cmb_tipoDeduccion)!=null)
			//Habilitar el ComboBox y el deseleccionar el CheckBox para que el usuario pueda agregar nueva informaci�n
			document.getElementById(cmb_tipoDeduccion).disabled = false;				
	}
}
/************************************************************************************************************************************************************************/
/**********************************************************************AGREGAR EMPLEADO**********************************************************************************/
/************************************************************************************************************************************************************************/
/*Funcion que permite validar los datos personales de un Empleado*/
function valFormAgregarEmpleado(frm_agregarEmpleado1){
	//Variable bandera que permite revisar si la validacion fue exitosa
	var band=1;
	
	if (frm_agregarEmpleado1.txt_rfc.value==""){
		alert("Introducir el RFC del Trabajador");
		frm_agregarEmpleado1.txt_rfc.focus();
		band=0;
	}
	
	if (frm_agregarEmpleado1.txt_rfc.value.length!=13 && band==1){
		alert("El Tama�o del RFC debe ser de 13 caracteres.\nTama�o del RFC Introducido: "+frm_agregarEmpleado1.txt_rfc.value.length);
		frm_agregarEmpleado1.txt_rfc.focus();
		band=0;
	}
	
	if (frm_agregarEmpleado1.txt_curp.value==""&&band==1){
		alert("Introducir la CURP del Trabajador");
		frm_agregarEmpleado1.txt_curp.focus();
		band=0;
	}
	
	if (frm_agregarEmpleado1.txt_curp.value.length!=18 && band==1){
		alert("El Tama�o de la CURP debe ser de 18 caracteres.\nTama�o de la CURP Introducida: "+frm_agregarEmpleado1.txt_curp.value.length);
		frm_agregarEmpleado1.txt_curp.focus();
		band=0;
	}
	
	if (frm_agregarEmpleado1.txt_nss.value==""&&band==1){
		alert("Introducir el N�mero de Seguro de Social del Trabajador");
		frm_agregarEmpleado1.txt_nss.focus();
		band=0;
	}
	
	if (frm_agregarEmpleado1.txt_nombre.value==""&&band==1){
		alert("Introducir el Nombre del Trabajador");
		frm_agregarEmpleado1.txt_nombre.focus();
		band=0;
	}
	
	if (frm_agregarEmpleado1.txt_apePat.value==""&&band==1){
		alert("Introducir el Apellido Paterno del Trabajador");
		frm_agregarEmpleado1.txt_apePat.focus();
		band=0;
	}
	
	if (frm_agregarEmpleado1.txt_apeMat.value==""&&band==1){
		alert("Introducir el Apellido Materno del Trabajador");
		frm_agregarEmpleado1.txt_apeMat.focus();
		band=0;
	}
	
	if (frm_agregarEmpleado1.txt_calle.value==""&&band==1){
		alert("Introducir la Calle de Vivienda del Trabajador");
		frm_agregarEmpleado1.txt_calle.focus();
		band=0;
	}
	
	if (frm_agregarEmpleado1.txt_numExt.value==""&&band==1){
		alert("Introducir por lo Menos el N�mero Exterior de Vivienda del Trabajador");
		frm_agregarEmpleado1.txt_numExt.focus();
		band=0;
	}
	
	if (frm_agregarEmpleado1.txt_colonia.value==""&&band==1){
		alert("Introducir la Colonia de Vivienda del Trabajador");
		frm_agregarEmpleado1.txt_colonia.focus();
		band=0;
	}
	
	if (frm_agregarEmpleado1.txt_munLoc.value==""&&band==1){
		alert("Introducir el Municipio/Localidad del Trabajador");
		frm_agregarEmpleado1.txt_munLoc.focus();
		band=0;
	}
	
	if (frm_agregarEmpleado1.txt_estado.value==""&&band==1){
		alert("Introducir el Estado del Trabajador");
		frm_agregarEmpleado1.txt_estado.focus();
		band=0;
	}
	
	if (frm_agregarEmpleado1.txt_pais.value==""&&band==1){
		alert("Introducir el Pa�s del Trabajador");
		frm_agregarEmpleado1.txt_pais.focus();
		band=0;
	}
	
	if (frm_agregarEmpleado1.txt_nacionalidad.value==""&&band==1){
		alert("Introducir la Nacionalidad del Trabajador");
		frm_agregarEmpleado1.txt_nacionalidad.focus();
		band=0;
	}
	
	if (frm_agregarEmpleado1.cmb_estado.value==""&&band==1){
		alert("Seleccionar Estado Civil");
		frm_agregarEmpleado1.cmb_estado.focus();
		band=0;
	}
	
	if (frm_agregarEmpleado1.txt_sangre.value==""&&band==1){
		alert("Introducir el Tipo de Sangre del Trabajador");
		frm_agregarEmpleado1.txt_sangre.focus();
		band=0;
	}
	
	if (frm_agregarEmpleado1.cmb_tipoDisc.value==""&&band==1){
		alert("Seleccionar una Opci�n de Discapacidad");
		frm_agregarEmpleado1.cmb_tipoDisc.focus();
		band=0;
	}
	
	if (frm_agregarEmpleado1.txt_depEco.value==""&&band==1)
		frm_agregarEmpleado1.txt_depEco.value=0;
	
	if (frm_agregarEmpleado1.cmb_nivEstudios.value==""&&band==1){
		alert("Seleccionar la Escolaridad M�xima");
		frm_agregarEmpleado1.cmb_nivEstudios.focus();
		band=0;
	}
	
	if (frm_agregarEmpleado1.cmb_docObtenido.value==""&&band==1){
		alert("Seleccionar el Documento Obtenido");
		frm_agregarEmpleado1.cmb_docObtenido.focus();
		band=0;
	}
	
	if (frm_agregarEmpleado1.txt_carrera.value==""&&band==1){
		alert("Ingresar el Estudio/Carrera");
		frm_agregarEmpleado1.txt_carrera.focus();
		band=0;
	}
	
	if (frm_agregarEmpleado1.cmb_institucion.value==""&&band==1){
		alert("Seleccionar el Tipo de Instituci�n");
		frm_agregarEmpleado1.cmb_institucion.focus();
		band=0;
	}
	
	if (frm_agregarEmpleado1.cmb_institucion.value==""&&band==1){
		alert("Seleccionar el Tipo de Instituci�n");
		frm_agregarEmpleado1.cmb_institucion.focus();
		band=0;
	}
	
	if (frm_agregarEmpleado1.cmb_con_cos.value==""&&band==1){
		alert("Seleccionar el Control de Costos");
		frm_agregarEmpleado1.cmb_con_cos.focus();
		band=0;
	}
	
	if (frm_agregarEmpleado1.cmb_cuenta.value==""&&band==1){
		alert("Seleccionar la Cuenta");
		frm_agregarEmpleado1.cmb_cuenta.focus();
		band=0;
	}
	
	//Verificar que la clave empresarial y la de area no esten repetidas
	if (frm_agregarEmpleado1.hdn_claveValida.value=="no" && band==1){
		alert("El RFC del Trabajador esta Duplicado");
		frm_agregarEmpleado1.txt_rfc.focus();
		band=0;
	}
	
	if (band==1)
		return true;
	else
		return false;

}

/*Funcion que valida el formulario de agregarEmpleado para los datos laborales*/
function valFormAgregarEmpleado2(frm_agregarEmpleado2){
	
	var band=1;

	if (frm_agregarEmpleado2.txt_cveEmp.value==""){
		alert("Introducir la Clave Empresarial del Trabajador");
		band=0;
	}

	if (frm_agregarEmpleado2.txt_cveArea.value==""&&band==1){
		alert("Introducir la Clave de �rea del Trabajador");
		band=0;
	}
	
	if (frm_agregarEmpleado2.txt_numCta.value==""&&band==1){
		alert("Introducir el N�mero de Cuenta del Trabajador");
		band=0;
	}
	
	/*if (!frm_agregarEmpleado2.ckb_nuevaArea.checked && frm_agregarEmpleado2.cmb_area.value=="" && band==1){
		alert("Seleccionar el �rea del Trabajador");
		band=0;
	}*/
	
	/*if (!frm_agregarEmpleado2.ckb_nuevaArea.checked && frm_agregarEmpleado2.cmb_area.value=="" && band==1 && !frm_agregarEmpleado2.ckb_nuevaArea.checked){
		alert("Introducir el Puesto del Trabajador");
		band=0;
	}*/
	
	if (!frm_agregarEmpleado2.ckb_nuevoPuesto.checked && frm_agregarEmpleado2.cmb_puesto.value=="" && band==1){
		alert("Seleccionar el Puesto del Trabajador");
		band=0;
	}

	//Verificar que se haya seleccionado una imagen
	if (frm_agregarEmpleado2.hdn_foto.value!=""&&band==1){
		//Verificar que la imagen introducida sea valida, este valor lo obtiene de la funcion validarImagen()
		if (frm_agregarEmpleado2.hdn_foto.value=="no"){
			alert ("Introducir una Im�gen V�lida");
			band=0;
		}
	}
	
	//Verificar que la clave empresarial y la de area no esten repetidas
	if (frm_agregarEmpleado2.hdn_claveValida.value=="no" && band==1){
		alert("La Clave Empresarial esta Duplicada");
		band=0;
	}
	
	if (band==1)
		return true;
	else
		return false;
}

//Funcion que obtiene el Numero del Area Seleccionada
function ordDato(datoSel,cajaArea){
	var dato=datoSel.split(";");
	document.getElementById("hdn_claveDepto").value=dato[0];
	cargarCombo(dato[1],'bd_recursos','empleados','puesto','area','cmb_puesto','Puesto','');
	obtenerClaveArea(dato[1],cajaArea);
}

/************************************************************************************************************************************************************************/
/********************************************************************ELIMINAR EMPLEADO**********************************************************************************/
/************************************************************************************************************************************************************************/
//Funcion que permite verificar si se ha seleccionado al Trabajador encontrado para poder darlo de Baja
function valFormEliminarTrabajador1(frm_eliminarTrabajador1){

	if (!frm_eliminarTrabajador1.rdb_rfc.checked){
		alert ("Seleccionar al Trabajador para Continuar");
		return false;
	}
	else
		return true;
}

//Funcion que valida si se han ingresado o no Observaciones, pemitiendo al usuario rectificar sobre si desea cambiar de opinion
function valFormEliminarTrabajador2(frm_completarEliminado){

	var band = 1;
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_completarEliminado.txt_fechaIng.value.substr(0,2);
	var iniMes=frm_completarEliminado.txt_fechaIng.value.substr(3,2);
	var iniAnio=frm_completarEliminado.txt_fechaIng.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_completarEliminado.txt_fechaBaja.value.substr(0,2);
	var finMes=frm_completarEliminado.txt_fechaBaja.value.substr(3,2);
	var finAnio=frm_completarEliminado.txt_fechaBaja.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	//Verificar que la fecha de inicio no sea mayor a la de cierre
	if(fechaIni>fechaFin){
		band=0;
		alert ("La Fecha de Baja no puede ser Anterior a la Fecha de Ingreso");
	}

	if (frm_completarEliminado.txa_observaciones.value=="" && band==1){
		if (confirm ("Es Altamente Recomendable Ingresar Observaciones.\n �Desea Agregar Observaciones?"))
			band=0;
	}

	if (band==1)
		return true;
	else
		return false;

}

/************************************************************************************************************************************************************************/
/********************************************************************CONSULTAR EMPLEADO**********************************************************************************/
/************************************************************************************************************************************************************************/
//Funcion que permite verificar si se ha escrito el nombre del Empleado, no se verifica el combo, ya que de no especificarse area, se busca en toda la nomina
function valFormconsultarEmpleado1(frm_consultarEmpleado1){
	if (frm_consultarEmpleado1.txt_nombre.value==""){
		alert ("Introducir el Nombre del Trabajador");
		return false;
	}
	else
		return true;
}
//Funcion que valida si se ha seleccionado el �rea de un Trabajador
function valFormconsultarEmpleado3(frm_consultarEmpleado3){
	if (frm_consultarEmpleado3.cmb_area.value==""){
		alert ("Seleccionar el �rea del Trabajador");
		return false;
	}
	else
		return true;
}

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

/************************************************************************************************************************************************************************/
/********************************************************************MODIFICAR EMPLEADO**********************************************************************************/
/************************************************************************************************************************************************************************/

//Funcion que permite verificar si se ha escrito el nombre del Empleado, no se verifica el combo, ya que de no especificarse area, se busca en toda la nomina
function valFormconsultarEmpleadoMod(frm_modificarEmpleado){
	if (frm_modificarEmpleado.txt_nombreBuscar.value==""){
		alert ("Introducir el Nombre del Trabajador");
		return false;
	}
	else
		return true;
}

//Function que valida los campos del formulario de datos personales en la pantalla de Modificacion
function valFormModificarEmpleado(){
	//Variable bandera que permite revisar si la validacion fue exitosa
	var band=1;
	
	if (frm_modificarEmpleado1.txt_rfc.value==""){
		alert("Introducir el RFC del Trabajador");
		frm_modificarEmpleado1.txt_rfc.focus();
		band=0;
	}
	
	if (frm_modificarEmpleado1.txt_rfc.value.length!=13 && band==1){
		alert("El Tama�o del RFC debe ser de 13 caracteres.\nTama�o del RFC Introducido: "+frm_modificarEmpleado1.txt_rfc.value.length);
		frm_modificarEmpleado1.txt_rfc.focus();
		band=0;
	}
	
	if (frm_modificarEmpleado1.txt_curp.value==""&&band==1){
		alert("Introducir la CURP del Trabajador");
		frm_modificarEmpleado1.txt_curp.focus();
		band=0;
	}
	
	if (frm_modificarEmpleado1.txt_curp.value.length!=18 && band==1){
		alert("El Tama�o de la CURP debe ser de 18 caracteres.\nTama�o de la CURP Introducida: "+frm_modificarEmpleado1.txt_curp.value.length);
		frm_modificarEmpleado1.txt_curp.focus();
		band=0;
	}
	
	if (frm_modificarEmpleado1.txt_nss.value==""&&band==1){
		alert("Introducir el N�mero de Seguro de Social del Trabajador");
		frm_modificarEmpleado1.txt_nss.focus();
		band=0;
	}
	
	if (frm_modificarEmpleado1.txt_nombre.value==""&&band==1){
		alert("Introducir el Nombre del Trabajador");
		frm_modificarEmpleado1.txt_nombre.focus();
		band=0;
	}
	
	if (frm_modificarEmpleado1.txt_apePat.value==""&&band==1){
		alert("Introducir el Apellido Paterno del Trabajador");
		frm_modificarEmpleado1.txt_apePat.focus();
		band=0;
	}
	
	if (frm_modificarEmpleado1.txt_apeMat.value==""&&band==1){
		alert("Introducir el Apellido Materno del Trabajador");
		frm_modificarEmpleado1.txt_apeMat.focus();
		band=0;
	}
	
	if (frm_modificarEmpleado1.txt_calle.value==""&&band==1){
		alert("Introducir la Calle de Vivienda del Trabajador");
		frm_modificarEmpleado1.txt_calle.focus();
		band=0;
	}
	
	if (frm_modificarEmpleado1.txt_numExt.value==""&&band==1){
		alert("Introducir por lo Menos el N�mero Exterior de Vivienda del Trabajador");
		frm_modificarEmpleado1.txt_numExt.focus();
		band=0;
	}
	
	if (frm_modificarEmpleado1.txt_colonia.value==""&&band==1){
		alert("Introducir la Colonia de Vivienda del Trabajador");
		frm_modificarEmpleado1.txt_colonia.focus();
		band=0;
	}
	
	if (frm_modificarEmpleado1.txt_munLoc.value==""&&band==1){
		alert("Introducir el Municipio/Localidad del Trabajador");
		frm_modificarEmpleado1.txt_munLoc.focus();
		band=0;
	}
	
	if (frm_modificarEmpleado1.txt_estado.value==""&&band==1){
		alert("Introducir el Estado del Trabajador");
		frm_modificarEmpleado1.txt_estado.focus();
		band=0;
	}
	
	if (frm_modificarEmpleado1.txt_pais.value==""&&band==1){
		alert("Introducir el Pa�s del Trabajador");
		frm_modificarEmpleado1.txt_pais.focus();
		band=0;
	}
	
	if (frm_modificarEmpleado1.txt_nacionalidad.value==""&&band==1){
		alert("Introducir la Nacionalidad del Trabajador");
		frm_modificarEmpleado1.txt_nacionalidad.focus();
		band=0;
	}
	
	if (frm_modificarEmpleado1.cmb_estado.value==""&&band==1){
		alert("Seleccionar Estado Civil");
		frm_modificarEmpleado1.cmb_estado.focus();
		band=0;
	}
	
	if (frm_modificarEmpleado1.txt_sangre.value==""&&band==1){
		alert("Introducir el Tipo de Sangre del Trabajador");
		frm_modificarEmpleado1.txt_sangre.focus();
		band=0;
	}
	
	if (frm_modificarEmpleado1.cmb_tipoDisc.value==""&&band==1){
		alert("Seleccionar una Opci�n de Discapacidad");
		frm_modificarEmpleado1.cmb_tipoDisc.focus();
		band=0;
	}
	
	if (frm_modificarEmpleado1.txt_depEco.value==""&&band==1)
		frm_modificarEmpleado1.txt_depEco.value=0;
	
	if (frm_modificarEmpleado1.cmb_nivEstudios.value==""&&band==1){
		alert("Seleccionar la Escolaridad M�xima");
		frm_modificarEmpleado1.cmb_nivEstudios.focus();
		band=0;
	}
	
	if (frm_modificarEmpleado1.cmb_docObtenido.value==""&&band==1){
		alert("Seleccionar el Documento Obtenido");
		frm_modificarEmpleado1.cmb_docObtenido.focus();
		band=0;
	}
	
	if (frm_modificarEmpleado1.txt_carrera.value==""&&band==1){
		alert("Ingresar el Estudio/Carrera");
		frm_modificarEmpleado1.txt_carrera.focus();
		band=0;
	}
	
	if (frm_modificarEmpleado1.cmb_institucion.value==""&&band==1){
		alert("Seleccionar el Tipo de Instituci�n");
		frm_modificarEmpleado1.cmb_institucion.focus();
		band=0;
	}
	
	if (frm_modificarEmpleado1.cmb_institucion.value==""&&band==1){
		alert("Seleccionar el Tipo de Instituci�n");
		frm_modificarEmpleado1.cmb_institucion.focus();
		band=0;
	}
	
	if (frm_modificarEmpleado1.cmb_con_cos.value==""&&band==1){
		alert("Seleccionar el Control de Costos");
		frm_modificarEmpleado1.cmb_con_cos.focus();
		band=0;
	}
	
	if (frm_modificarEmpleado1.cmb_cuenta.value==""&&band==1){
		alert("Seleccionar la Cuenta");
		frm_modificarEmpleado1.cmb_cuenta.focus();
		band=0;
	}
	
	//Verificar que la clave empresarial y la de area no esten repetidas
	/*if (frm_modificarEmpleado1.hdn_claveValida.value=="no" && band==1){
		alert("El RFC del Trabajador esta Duplicado");
		frm_modificarEmpleado1.txt_rfc.focus();
		band=0;
	}*/
		
	if (band==1)
		return true;
	else
		return false;
}

//Function que valida los campos del formulario de datos laborales en la pantalla de Modificacion
function valFormModificarEmpleado2(frm_modificarEmpleado2){
	var band=1;

	if (frm_modificarEmpleado2.txt_cveEmp.value==""){
		alert("Introducir la Clave Empresarial del Trabajador");
		band=0;
	}

	/*if (frm_modificarEmpleado2.txt_cveArea.value==""&&band==1){
		alert("Introducir la Clave de �rea del Trabajador");
		band=0;
	}*/
	
	if (frm_modificarEmpleado2.txt_numCta.value==""&&band==1){
		alert("Introducir el N�mero de Cuenta del Trabajador");
		band=0;
	}
	
	/*if (!frm_modificarEmpleado2.ckb_nuevaArea.checked && frm_modificarEmpleado2.cmb_area.value=="" && band==1){
		alert("Seleccionar el �rea del Trabajador");
		band=0;
	}
	
	if (!frm_modificarEmpleado2.ckb_nuevaArea.checked && frm_modificarEmpleado2.cmb_area.value=="" && band==1 && !frm_modificarEmpleado2.ckb_nuevaArea.checked){
		alert("Introducir el Puesto del Trabajador");
		band=0;
	}*/

	
	if (!frm_modificarEmpleado2.ckb_nuevoPuesto.checked && frm_modificarEmpleado2.cmb_puesto.value=="" && band==1){
		alert("Seleccionar el Puesto del Trabajador");
		band=0;
	}

	//Verificar que se haya seleccionado una imagen
	if (frm_modificarEmpleado2.hdn_foto.value!=""&&band==1){
		//Verificar que la imagen introducida sea valida, este valor lo obtiene de la funcion validarImagen()
		if (frm_modificarEmpleado2.hdn_foto.value=="no"){
			alert ("Introducir una Im�gen V�lida");
			band=0;
		}
	}
	
	if (band==1)
		return true;
	else
		return false;
}

//Funcion que permite verificar si se ha seleccionado a un Beneficiario para ser eliminado
function valFormModBeneficiarios(frm_modificarBeneficiarios){
	var band=1;
	if (frm_modificarBeneficiarios.hdn_bandera.value=="si"){
		band=0;
		if(frm_modificarBeneficiarios.rdb_rfc.length==undefined){
			if(frm_modificarBeneficiarios.rdb_rfc.checked)
				band = 1;
		}
		else{
			//Si algun valor fue seleccionado la variable "band" se activar�
			for(i=0;i<frm_modificarBeneficiarios.rdb_rfc.length;i++){
				if(frm_modificarBeneficiarios.rdb_rfc[i].checked)
					band = 1;
			}
		}
		if(band==0)
			alert("Seleccionar el Beneficiario a Borrar");
	}
	if (band==1)
		return true;
	else
		return false;
}

/************************************************************************************************************************************************************************/
/********************************************************************REGISTRAR BENEFICIARIO******************************************************************************/
/************************************************************************************************************************************************************************/
function valFormConsultarEmpleadoBeneficiario(frm_obtenerEmpleado){
	var band=1;
	
	if (frm_obtenerEmpleado.txt_nombre.value=="" && band==1){
		alert ("Introducir el Nombre del Trabajador");
		band=0;
	}
	
	if (band==1)
		return true;
	else
		return false;
}

//Funcion que valida si un porcentaje es mayor a 100
function validarPorcentaje(caja_texto){
	var valor=caja_texto.value;
	if (valor>100){
		alert("El Porcentaje No Puede ser Mayor al 100%");
		caja_texto.value="";
	}
}

//Funcion que valida si los datos del Beneficiario han sido complementados
function valFormBeneficiarios(frm_regBeneficiarios){
	var band=1;
	
	if (frm_regBeneficiarios.hdn_validar.value=="si")
	{
		if (frm_regBeneficiarios.txt_nombreBen.value==""){
			alert ("Introducir el Nombre del Beneficiario");
			band=0;
		}
		
		if (frm_regBeneficiarios.txt_parentesco.value=="" && band==1){
			alert ("Introducir el Parentesco");
			band=0;
		}
		
		if(frm_regBeneficiarios.txt_edad.value=="" && band==1){
			alert ("Introducir la Edad");
			band=0;
		}
		
		if(band==1){
			if(!validarEntero(frm_regBeneficiarios.txt_edad.value,"La Edad"))
				band=0;
		}
		
		if (frm_regBeneficiarios.txt_porcentaje.value=="" && band==1){
			alert ("Introducir el Porcentaje");
			band=0;
		}
		if(band==1){
			if(!validarEntero(frm_regBeneficiarios.txt_porcentaje.value,"El Porcentaje"))
				band=0;
		}
		
		if(band==1){
			if(frm_regBeneficiarios.txt_porcentaje.value.charAt(frm_regBeneficiarios.txt_porcentaje.value.length-1)=="."){
				frm_regBeneficiarios.txt_porcentaje.value=frm_regBeneficiarios.txt_porcentaje.value.substring(0,frm_regBeneficiarios.txt_porcentaje.value.length-1);
			}
		}
		
		
	}

	if (band==1)
		return true;
	else
		return false;
}


/************************************************************************************************************************************************************************/
/********************************************************************REGISTRAR BECARIO***********************************************************************************/
/************************************************************************************************************************************************************************/
//Funcion que valida si los datos del Becario han sido complementados
function valFormBecarios(frm_regBecarios){
	var band=1;
	
	if (frm_regBecarios.hdn_validar.value=="si")
	{
		if (frm_regBecarios.txt_nombreBec.value==""){
			alert ("Introducir el Nombre del Becario");
			band=0;
		}
		
		if (frm_regBecarios.txt_parentesco.value=="" && band==1){
			alert ("Introducir el Parentesco");
			band=0;
		}
		
		if (frm_regBecarios.txt_promedio.value=="" && band==1){
			alert ("Introducir el Promedio");
			band=0;
		}
		
		if(band==1){
			if(!validarEntero(frm_regBecarios.txt_promedio.value,"El Promedio"))
				band=0;
		}
			
		
		if (frm_regBecarios.txt_grado.value=="" && band==1){
			alert ("Introducir el Grado de Estudios");
			band=0;
		}
		
		if(band==1){
			if(!validarEntero(frm_regBecarios.txt_grado.value,"El Grado"))
				band=0;
		}
				
		if (frm_regBecarios.cmb_grado.value=="" && band==1){
			alert ("Seleccionatr el Nivel de Estudios");
			band=0;
		}
	}

	if (band==1)
		return true;
	else
		return false;
}

/************************************************************************************************************************************************************************/
/********************************************************************MODIFICAR BECARIO***********************************************************************************/
/************************************************************************************************************************************************************************/
//Funcion que permite verificar si se ha seleccionado a un Beneficiario para ser eliminado
function valFormModBecarios(frm_modificarBecarios){
	var band=1;
	if (frm_modificarBecarios.hdn_bandera.value=="si"){
		band=0;
		if(frm_modificarBecarios.rdb_rfc.length==undefined){
			if(frm_modificarBecarios.rdb_rfc.checked)
				band = 1;
		}
		else{
			//Si algun valor fue seleccionado la variable "band" se activar�
			for(i=0;i<frm_modificarBecarios.rdb_rfc.length;i++){
				if(frm_modificarBecarios.rdb_rfc[i].checked)
					band = 1;
			}
		}
		if(band==0)
			alert("Seleccionar el Becario a Borrar");
	}
	if (band==1)
		return true;
	else
		return false;
}


/************************************************************************************************************************************************************************/
/*********************************************************************REGISTRAR KARDEX***********************************************************************************/
/************************************************************************************************************************************************************************/
/*Funciones que abre la ventana emergente de Karde Individual*/
function asignarEstadoKardex(cajaTexto,trabajador,anio){
	//Extraer el valor Original de la Caja
	var valOr=cajaTexto.value;
	//Extraer el nombre de la Caja de Texto
	var nom=cajaTexto.name;
	//Abrir la ventana emrgente con los parametros recogidos y adecuado al tipo de Division
	window.open('verEstadoKardex.php?div=indiv&valOr='+valOr+'&nom='+nom+'&trab='+trabajador+'&anio='+anio, '_blank','top=100, left=100, width=860, height=300, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
 }
	 
/*Funciones que abre la ventana emergente de Karde Individual*/
function asignarEstadoKardexArea(cajaTexto){
	//Extraer el valor Original de la Caja
	var valOr=cajaTexto.value;
	//Extraer el nombre de la Caja de Texto
	var caja=cajaTexto.name;
	//Extraer el Titulo de la Caja de Texto
	var titulo=cajaTexto.title;
	//Guardar en una variable el titulo sin el primer contenido a fin de obtener de el, solo los datos necesarios
	var nom=titulo.replace("Asignar la Incidencia del ","");
	//Extraer en un arreglo el resultante del titulo divido por el texto " para " de forma de extraer el nombre del Trabajador
	var datos=nom.split(" para ");
	//Abrir la ventana emrgente con los parametros recogidos y adecuado al tipo de Division
	window.open('verEstadoKardex.php?div=area&valOr='+valOr+'&nom='+caja+'&trab='+datos[1], '_blank','top=100, left=100, width=900, height=300, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
 }

/*Funcion que valida el formulario de seleccion de Kardex por Ejercicio y Nombre del Trabajador*/
function valFormSeleccionarKardex1(frm_consultarKardex1){
	var band = 1;
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_consultarKardex1.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_consultarKardex1.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_consultarKardex1.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_consultarKardex1.txt_fechaFin.value.substr(0,2);
	var finMes=frm_consultarKardex1.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_consultarKardex1.txt_fechaFin.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	//Verificar que la fecha de inicio no sea mayor a la de cierre
	if(fechaIni>fechaFin){
		band=0;
		alert ("La Fecha de Inicio debe ser Menor o Igual a la Fecha de Fin");
	}
	
	if(!frm_consultarKardex1.ckb_filtroTrab.checked){
		if (frm_consultarKardex1.cmb_area.value=="" && band==1){
			band=0;
			alert ("Seleccionar el �rea");
		}
	}
	else{
		if (frm_consultarKardex1.txt_nombreK.value=="" && band==1){
			band=0;
			alert ("Ingresar Nombre del Trabajador");
		}
	}
	
	if(band==1)
		return true;
	else
		return false;
}

/*Funcion que verifica el filtro del empleado, trabaja en conjunto con la funcion valFormSeleccionarKardex1*/
function verificarFiltroEmpleado(checkbox,cajaFiltro,comboArea){
	if(checkbox.checked){
		cajaFiltro.readOnly=false;
		comboArea.value="";
		comboArea.disabled=true;
	}
	else{
		cajaFiltro.readOnly=true;
		cajaFiltro.value="";
		comboArea.disabled=false;
	}
}

//Funcion que valida las fecha introducidas en el Kardex
function valFormSeleccionarKardex2(frm_consultarKardex2){
	var band=1;
	
	if (frm_consultarKardex2.cmb_ejercicio.value==""){
		alert("Seleccionar el Ejercicio");
		band=0;
	}
	
	if (frm_consultarKardex2.txt_nombre.value=="" && band==1){
		alert("Ingresar el Nombre del Trabajador");
		band=0;
	}
	
	if(band==1)
		return true;
	else
		return false;
}


//Funcion que valida las incidencias ingresadas en el kardex
function valFormAsignarEstado(frm_asignarEstado){
	var band=1;
	
	if (frm_asignarEstado.hdn_validar.value=="si"){
		if(!frm_asignarEstado.ckb_activarEntrada.checked && !frm_asignarEstado.ckb_activarSalida.checked){
			alert("Seleccionar una Opci�n de Entrada, Salida o Ambas");
			band=0;
		}
		
		if(band==1 && frm_asignarEstado.ckb_activarEntrada.checked && frm_asignarEstado.txt_horaE.value==""){
			alert("Ingresar la Hora de Entrada");
			band=0;
		}
		
		if(band==1 && frm_asignarEstado.ckb_activarEntrada.checked && frm_asignarEstado.cmb_estado.value==""){
			alert("Seleccionar la Incidencia/Estado de Entrada");
			band=0;
		}
		
		if(band==1 && frm_asignarEstado.ckb_activarSalida.checked && frm_asignarEstado.txt_horaS.value==""){
			alert("Ingresar la Hora de Salida");
			band=0;
		}
	}
	
	if(band==1)
		return true;
	else
		return false;
}


/**************************************************************************************************************************/
/***************************************************CAPACITACIONES**********************************************************/
/**************************************************************************************************************************/

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

//Funcion para Evaluar los datoas del formularo Agregar Capacitaciones
function valFormAgregarCapacitacion(frm_agregarCapacitacion){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;

	if(frm_agregarCapacitacion.txt_hrsCapacitacion.value.replace(/,/g,'')=="" || frm_agregarCapacitacion.txt_hrsCapacitacion.value.replace(/,/g,'')=="0"){
		alert ("Ingresar Duraci�n de Capacitaci�n en Horas");
		frm_agregarCapacitacion.txt_hrsCapacitacion.focus();
		band = 0;
	}
	
	if(band==1){
		if(!validarFechas(frm_agregarCapacitacion.txt_fechaIni.value,frm_agregarCapacitacion.txt_fechaFin.value))
			band = 0;
	}

	//Se verifica que el Tema de la capacitacion haya sido ingresado
	if (frm_agregarCapacitacion.txt_tema.value==""&&band==1){
		alert ("Ingresar Tema de Capacitaci�n");
		frm_agregarCapacitacion.txt_tema.focus();
		band=0;
	}

	//Se verifica que el nombre de la capacitacion haya sido ingresado
	if (frm_agregarCapacitacion.txt_normaCapacitacion.value==""&&band==1){
		alert ("Ingresar Norma de Capacitaci�n");
		frm_agregarCapacitacion.txt_normaCapacitacion.focus();
		band=0;
	}
	
	//Se verifica que el nombre de la capacitacion haya sido ingresado
	if (frm_agregarCapacitacion.txt_nomCapacitacion.value==""&&band==1){
		alert ("Ingresar Nombre de Capacitaci�n");
		frm_agregarCapacitacion.txt_nomCapacitacion.focus();
		band=0;
	}
	
	//Se verifica que el nombre de la capacitacion haya sido ingresado
	if (frm_agregarCapacitacion.cmb_modo.value==""&&band==1){
		alert ("Seleccionar Modalidad de Capacitaci�n");
		frm_agregarCapacitacion.cmb_modo.focus();
		band=0;
	}
	
	//Se verifica que descripcion haya sido ingresado
	if (frm_agregarCapacitacion.txa_descripcion.value==""&&band==1){
		alert ("Ingresar Descripci�n de Capacitaci�n");
		frm_agregarCapacitacion.txa_descripcion.focus();
		band=0;
	}
	
	//Se verifica que el nombre de la capacitacion haya sido ingresado
	if (frm_agregarCapacitacion.cmb_objetivo.value==""&&band==1){
		alert ("Seleccionar El Objetivo de Capacitaci�n");
		frm_agregarCapacitacion.cmb_objetivo.focus();
		band=0;
	}
	
	//Se verifica que el nombre del instructor haya sido ingresado
	if (frm_agregarCapacitacion.txt_instructor.value==""&&band==1){
		alert ("Ingresar el Nombre del Instructor");
		frm_agregarCapacitacion.txt_instructor.focus();
		band=0;
	}
	
	//Se verifica que el nombre del instructor haya sido ingresado
	if (frm_agregarCapacitacion.rdb_tipoIns[1].checked&&band==1&&frm_agregarCapacitacion.txt_numRegSTPS.value==""){
		alert ("Ingresar el N�mero de Registro en el STPS del Capacitador Externo");
		frm_agregarCapacitacion.txt_numRegSTPS.focus();
		band=0;
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

/*Esta funcion solicita la confirmaci�n del usuario antes de salir de la pagina*/
function confirmarSalida(pagina){
	if(confirm("�Estas Seguro que Quieres Salir?\nToda la informaci�n no Guardada se Perder�"))
		location.href = pagina;	
}

//Funcion para Evaluar los datoas del formularo Eliminar Capacitaciones
function valFormEliminarCapacitacionClave(frm_eliminarCapacitacion){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;

	//Se verifica que se haya seleccionado un id de la capacitacion
	if (frm_eliminarCapacitacion.cmb_claveCapacitacion.value==""){
		alert ("Seleccionar una Capacitaci�n");
		band=0;
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

//Funcion para Evaluar los datoas del formularo Eliminar Capacitaciones
function valFormEliminarCapacitacionFecha(frm_eliminarCapacitacion2){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;


	if(!validarFechas(frm_eliminarCapacitacion2.txt_fechaIni.value,frm_eliminarCapacitacion2.txt_fechaFin.value))
		band = 0;				
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

/*Esta funcion se ecnarga de Validar el Formulario donde se selecciona la capacitacion para borrar*/
function valFormEliminarCap(frm_eliminarCap){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opci�n
	if(frm_eliminarCap.rdb.length==undefined && !frm_eliminarCap.rdb.checked){
		alert("Seleccionar la Capacitaci�n a Borrar");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_eliminarCap.rdb.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_eliminarCap.rdb.length;i++){
			if(frm_eliminarCap.rdb[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar la Capacitaci�n a Borrar");			
	}
	
	if (res==1){
		
		if (!confirm("�Estas Seguro que Quieres Borrar la Capacitaci�n?\nToda la informaci�n relacionada se Borrar�")){
			res=0;
		}
	}
	
	if(res==1)
		return true;
	else
		return false;
}

//Funcion para Evaluar los datos del formularo Registrar asistencia a capacitaciones
function valFormregistrarCapacitacionClave(frm_registrarCapacitacion){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;

	//Se verifica que se haya seleccionado una capacitacion 
	if (frm_registrarCapacitacion.cmb_claveCapacitacion.value==""){
		alert ("Seleccionar una Capacitaci�n");
		band=0;
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

function valFormGuardarAsistencia(frm_guardarAsistencia){		
	//Obtener la cantidad de registros
	var cantRegs = frm_guardarAsistencia.hdn_cantRegistros.value;
	var numRen = 1;
	var res = 0;//Si el valor permanece en 0, significa que ningun registro fue seleccionado
	
	var idCheckBox = "";
	
	while(numRen<cantRegs){
		idCheckBox = "ckb_"+numRen.toString();
		if(frm_guardarAsistencia[idCheckBox].checked)
			res = 1;		
		numRen++;
	}
	
	if(res==0)
		alert("Seleccionar al Menos un Empleado para ser Registrado en la Capacitaci�n");
	
	
	if(res==1)
		return true;
	else		
		return false;				
}


//Funcion para Evaluar los datoas del formularo Registrar asistencia a capacitaciones
function valFormregistrarCapacitacionFecha(frm_registrarCapacitacion2){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;


	if(!validarFechas(frm_registrarCapacitacion2.txt_fechaIni.value,frm_registrarCapacitacion2.txt_fechaFin.value))
		band = 0;				
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

//Funcion que permite verificar si se ha escrito el nombre del Empleado, no se verifica el combo, ya que de no especificarse area, se busca en toda la nomina
function valFormRegAsistEmp(frm_valFormRegAsistEmp){
	if (frm_valFormRegAsistEmp.txt_nombre.value==""){
		alert ("Introducir el Nombre del Trabajador");
		return false;
	}
	else
		return true;
}

//Funcion que valida si se ha seleccionado el �rea de un Trabajador
function valFormRegAsistEmp2(frm_valFormRegAsistEmp2){
	if (frm_valFormRegAsistEmp2.cmb_area.value==""){
		alert ("Seleccionar el �rea del Trabajador");
		return false;
	}
	else
		return true;
}


/*Esta funcion se ecnarga de Validar el Formulario donde se selecciona la capacitacion a la que se registraran asistencias*/
function valFormregAsistencia(frm_valFormregAsistencia){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opci�n
	if(frm_valFormregAsistencia.rdb_rfc.length==undefined && !frm_valFormregAsistencia.rdb_rfc.checked){
		alert("Seleccionar una Capacitaci�n Para Agregar Asistencias");
		res = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_valFormregAsistencia.rdb_rfc.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_valFormregAsistencia.rdb_rfc.length;i++){
			if(frm_valFormregAsistencia.rdb_rfc[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar una Capacitaci�n Para Agregar Asistencias");			
	}
	
	if(res==1)
		return true;
	else
		return false;
}


//Funcion para Evaluar los datoas del formularo modificar Capacitaciones
function valFormmodificarCapacitacionClave(frm_modificarCapacitacion){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;

	//Se verifica que se haya seleccionado un id de la capacitacion
	if (frm_modificarCapacitacion.cmb_claveCapacitacion.value==""){
		alert ("Seleccionar una Capacitaci�n");
		band=0;
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

//Funcion para Evaluar los datoas del formularo Modificar Capacitaciones
function valFormmodificarCapacitacionFecha(frm_modificarCapacitacion2){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;


	if(!validarFechas(frm_modificarCapacitacion2.txt_fechaIni.value,frm_modificarCapacitacion2.txt_fechaFin.value))
		band = 0;				
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}


//Funcion para Evaluar los datoas del formularo consultar Capacitaciones
function valFormconsultarCapacitacionClave(frm_consultarCapacitacion){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;

	//Se verifica que se haya seleccionado un id de la capacitacion
	if (frm_consultarCapacitacion.cmb_claveCapacitacion.value==""){
		alert ("Seleccionar una Capacitaci�n");
		band=0;
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

//Funcion para Evaluar los datoas del formularo consultar Capacitaciones
function valFormconsultarCapacitacionFecha(frm_consultarCapacitacion2){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;


	if(!validarFechas(frm_consultarCapacitacion2.txt_fechaIni.value,frm_consultarCapacitacion2.txt_fechaFin.value))
		band = 0;				
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

//Funcion para Evaluar los datoas del formularo modificar Capacitaciones
function valFormModCapacitacion2(frm_modCapacitacion2){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;

	if(frm_modCapacitacion2.txt_hrsCapacitacion.value.replace(/,/g,'')=="" || frm_modCapacitacion2.txt_hrsCapacitacion.value.replace(/,/g,'')=="0"){
		alert ("Ingresar Duraci�n de Capacitaci�n en Horas");
		frm_modCapacitacion2.txt_hrsCapacitacion.focus();
		band = 0;
	}
	
	if(band==1){
		if(!validarFechas(frm_modCapacitacion2.txt_fechaIni.value,frm_modCapacitacion2.txt_fechaFin.value))
			band = 0;
	}

	//Se verifica que el Tema de la capacitacion haya sido ingresado
	if (frm_modCapacitacion2.txt_tema.value==""&&band==1){
		alert ("Ingresar Tema de Capacitaci�n");
		frm_modCapacitacion2.txt_tema.focus();
		band=0;
	}

	//Se verifica que el nombre de la capacitacion haya sido ingresado
	if (frm_modCapacitacion2.txt_normaCapacitacion.value==""&&band==1){
		alert ("Ingresar Norma de Capacitaci�n");
		frm_modCapacitacion2.txt_normaCapacitacion.focus();
		band=0;
	}
	
	//Se verifica que el nombre de la capacitacion haya sido ingresado
	if (frm_modCapacitacion2.txt_nomCapacitacion.value==""&&band==1){
		alert ("Ingresar Nombre de Capacitaci�n");
		frm_modCapacitacion2.txt_nomCapacitacion.focus();
		band=0;
	}
	
	//Se verifica que el nombre de la capacitacion haya sido ingresado
	if (frm_modCapacitacion2.cmb_modo.value==""&&band==1){
		alert ("Seleccionar Modalidad de Capacitaci�n");
		frm_modCapacitacion2.cmb_modo.focus();
		band=0;
	}
	
	//Se verifica que descripcion haya sido ingresado
	if (frm_modCapacitacion2.txa_descripcion.value==""&&band==1){
		alert ("Ingresar Descripci�n de Capacitaci�n");
		frm_modCapacitacion2.txa_descripcion.focus();
		band=0;
	}
	
	//Se verifica que el nombre de la capacitacion haya sido ingresado
	if (frm_modCapacitacion2.cmb_objetivo.value==""&&band==1){
		alert ("Seleccionar El Objetivo de Capacitaci�n");
		frm_modCapacitacion2.cmb_objetivo.focus();
		band=0;
	}
	
	//Se verifica que el nombre del instructor haya sido ingresado
	if (frm_modCapacitacion2.txt_instructor.value==""&&band==1){
		alert ("Ingresar el Nombre del Instructor");
		frm_modCapacitacion2.txt_instructor.focus();
		band=0;
	}
	
	//Verificar los radiobuttones
	if(band==1 && (!frm_modCapacitacion2.rdb_tipoIns[0].checked && !frm_modCapacitacion2.rdb_tipoIns[0].checked)){
		alert ("Seleccionar el Tipo de Instructor");
		frm_modCapacitacion2.rdb_tipoIns[0].focus();
		band=0;
	}
	
	//Se verifica que el nombre del instructor haya sido ingresado
	if (frm_modCapacitacion2.rdb_tipoIns[1].checked&&band==1&&frm_modCapacitacion2.txt_numRegSTPS.value==""){
		alert ("Ingresar el N�mero de Registro en el STPS del Capacitador Externo");
		frm_modCapacitacion2.txt_numRegSTPS.focus();
		band=0;
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}


/************************************************************************************************************************************************************/
/************************************************************************************************************************************************************/
/********************************************************************N�MINA BANCARIA*************************************************************************/
/************************************************************************************************************************************************************/
/************************************************************************************************************************************************************/

/*************************************************************REGISTRAR N�MINA BANCARIA**********************************************************************/
/*Esta funcion permitira evaluar si un documento o archivo cargado tiene el formato v�lido*/
function validarCSV(campo){
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
		if(extension!="csv" ){
			alert("Formato de Archivo no Soportado, Formatos Validos: 'csv'");
		}
		else
			document.getElementById("hdn_docValido").value = "si";
	}
}

//Funci�n para para validar el tipo de archivo en importarCSV en nomina Bancaria
function validarArchivo(frm_importarCSV){
	if(frm_importarCSV.hdn_docValido.value=="no"){
		alert("Seleccionar Archivo V�lido");
		return false;
	}
	else
		return true;
}

/*Esta funcion se Encarga de Validar el Formulario donde se seleccionan los registros de los los empleados que seran registrados en la nomina Bancaria*/
function valFormResultadosNomBancaria(frm_resultadosNomina){
	var res = 1;
	//Variable para saber si al menos un equipo fue seleccionado
	var empleado = 0;
	var columna = 0;
	//Variable para controlar la cantidad de registros
	var ctrl= 1;
	//Variable para almacenar la cantidad de registros
	var cantidad = document.getElementById("hdn_cant").value-1;
	var idCheckBox = "";
	while(ctrl<=cantidad){		
		//Crear el id del CheckBox que se quiere verificar
		idCheckBox="ckb_emp"+ctrl.toString();
		
		//Verificar que la cantidad y la aplicaci�n del Checkbox seleccionado no esten vacias
		if(document.frm_resultadosNomina(idCheckBox).checked){
			empleado = 1;
		}
		ctrl++;
	}//Fin del While	
	
	ctrl = 1;
	while(ctrl<=53){		
		//Crear el id del CheckBox que se quiere verificar
		idCheckBox="ckb_concepto"+ctrl.toString();
		
		//Verificar que la cantidad y la aplicaci�n del Checkbox seleccionado no esten vacias
		if(document.frm_resultadosNomina(idCheckBox).checked){
			columna = 1;
		}
		ctrl++;
	}//Fin del While	
	
	//Verificar que al menos un empleado haya sido seleccionado
	if(empleado==1)
		res = 1;
	else{
		alert("Seleccionar al Menos un Empleado");
		res = 0;
	}
	
	if(columna==1)
		res = 1;
	else if(res==1){
		alert("Seleccionar al Menos un Concepto");
		res = 0;
	}
	
	if(res==1)
		return true;
	else
		return false;		
}

/*Esta funci�n valida  el check box para registrar */
function activarCamposNomina(ckb, noRegistro){
	if (ckb.checked){
		document.getElementById("txt_fecha" + noRegistro).disabled=false;
		document.getElementById("txt_sueldo" + noRegistro).disabled=false;
		document.getElementById("txt_ss" + noRegistro).disabled=false;
	}
	else{
		document.getElementById("txt_fecha" + noRegistro).disabled=true;
		document.getElementById("txt_fecha" + noRegistro).value=document.getElementById("txt_fecha" + noRegistro).defaultValue;
		document.getElementById("txt_sueldo" + noRegistro).disabled=true;
		document.getElementById("txt_sueldo" + noRegistro).value=document.getElementById("txt_sueldo" + noRegistro).defaultValue;
		document.getElementById("txt_ss" + noRegistro).disabled=true;
		document.getElementById("txt_ss" + noRegistro).value=document.getElementById("txt_ss" + noRegistro).defaultValue;;
	}
}


/*************************************************************CONSULTAR N�MINA BANCARIA**********************************************************************/
/*Esta funcion se Encarga de Validar el Formulario donde se seleccionan los registros de los aspirantes que seran exportados*/
function valFormNomBanc(frm_resultadosNomina){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Contar los CheckBox
	var cantCkbs = 0;
	for(i=0;i<document.frm_resultadosNomina.length;i++){
		if(document.frm_resultadosNomina[i].name.substring(0,4)=="ckb_")
			cantCkbs++;
	}
	
	//Verificar que un elemeto haya sido seleccionado  de los checkBox
	var ctrl = 0;
	for(i=0;i<cantCkbs;i++){
		if(document.frm_resultadosNomina["ckb_emp"+(i+1)].checked){
			ctrl = 1;
			break;
		}		
	}
	
	if(ctrl==0){
		alert("Seleccionar al Menos un Registro para ser Exportado");
		res = 0;
	}
	
	
	if(res==1)
		return true;
	else
		return false;
}

/*Estan funci�n activa  y desactiva todos lo CheckBox  de consultar N�mina Bancaria*/
function checarTodosNB(chkbox,nomForm){
	if(chkbox.checked){
		for(var i=0;i<document[nomForm].elements.length;i++){
			//Variable
			elemento=document[nomForm].elements[i];
			if (elemento.type=="checkbox")
				elemento.checked=chkbox.checked;
			if(elemento.type=="text")
				elemento.disabled=false;
		}
	}
}


/*Esta funcion desactiva el CheckBox de Seleccionar Todo cuando un CheckBox de consultar Nomina Bancaria*/
function desSeleccionarNB(checkbox){	
	//Realizar Accion cuando el checkbox no esta seleccionado
	if (!checkbox.checked){
		//Cuando sea Deseleccionado un checkbox, hacer lo mismo con el Checkbox que selecciona todo
		document.getElementById("ckbTodo").checked=false;	
	}	
}


//Funcion que permite validar las fechas el formulario Consultar de la N�mina Bancaria
function valFormConsultarEmpleadoNom(frm_nominaBancaria){
	//Variable bandera que permite revisar si la validaci�n fue exitosa.
	var band = 1;
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_nominaBancaria.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_nominaBancaria.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_nominaBancaria.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_nominaBancaria.txt_fechaFin.value.substr(0,2);
	var finMes=frm_nominaBancaria.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_nominaBancaria.txt_fechaFin.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	
	if (frm_nominaBancaria.cmb_area.value==""&&band==1){
		alert("Seleccionar �rea");
		band=0;
	}
	//Verificar que la fecha de inicio no sea mayor a la de cierre
	if(fechaIni>fechaFin && band==1){
		band=0;
		alert ("La Fecha de Baja no puede ser Anterior a la Fecha de Ingreso");
	}
	if (band==1)
		return true;
	else
		return false;

}

/**************************************************************EXPORTAR N�MINA *****************************************************************************/	
//Funcion para Evaluar los datoas del formularo al exportar la nomina
function valFormFechaConNomBanc(frm_resultadosNomina){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;

	//Se verifica que el a�o haya sido seleccionado
	if (frm_resultadosNomina.cmb_anio.value==""){
		alert ("Seleccionar A�o");
		band=0;
	}
	
	//Se verifica que el mes haya sido seleccionado
	if (frm_resultadosNomina.cmb_mes.value==""&&band==1){
		alert ("Seleccionar Mes");
		band=0;
	}
	
	//Se verifica que el nombre del instructor haya sido ingresado
	if (frm_resultadosNomina.cmb_semana.value==""&&band==1){
		alert ("Seleccionar Semana");
		band=0;
	}
	
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

/*******************************************************************************************************************************************************/
/*********************************************************REGISTRAR NOMINA DESARROLLO*******************************************************************/
/*******************************************************************************************************************************************************/

//Funcion que al deseleccionar un chek, realiza los calculos correspondintes al sueldo base
function establecerAsistenciaDesRec(no,elemento,incapacidad,elementModif,elementoModif2){
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
	destajo=document.getElementById("txt_destajo"+no);
	bonif=document.getElementById("txt_bonificaciones"+no);
	horas_extras=document.getElementById("txt_he"+no);
	total=document.getElementById("txt_total"+no);
	
	//Si el checkbox esta checado, se calculan los totales
	if(incapacidad == 0){
		if(check.checked && check4.checked == false){
			//aumenta dias al sueldo base
			sueldo_base.value=parseFloat(sueldo_base.value) + parseFloat(sueldo_diario.value);
			sueldo_base.value = parseFloat(Math.round(sueldo_base.value * 100) / 100).toFixed(2);
			//recalcula el total a pagar
			total.value=parseFloat(destajo.value) + parseFloat(sueldo_base.value);
			total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
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
			//descuenta dias al sueldo base
			sueldo_base.value=parseFloat(sueldo_base.value) - parseFloat(sueldo_diario.value);
			sueldo_base.value = parseFloat(Math.round(sueldo_base.value * 100) / 100).toFixed(2);
			//recalcula el total a pagar
			total.value=parseFloat(destajo.value) + parseFloat(sueldo_base.value);
			total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
		}
	} else if(incapacidad == 1){
		if(check.checked && (check2.checked || check4.checked)){
			//descuenta dias al sueldo base
			sueldo_base.value=parseFloat(sueldo_base.value) - parseFloat(sueldo_diario.value);
			sueldo_base.value = parseFloat(Math.round(sueldo_base.value * 100) / 100).toFixed(2);
			//recalcula el total a pagar
			total.value=parseFloat(destajo.value) + parseFloat(sueldo_base.value);
			total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
			check2.checked=false;
			check3.checked=false;
			check4.checked=false;
		}
		else if(check3.checked != false){
			check2.checked=false;
			check3.checked=false;
			check4.checked=false;
		}
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
			total.value=parseFloat(destajo.value) + parseFloat(sueldo_base.value);
			total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
		}
	} else if(incapacidad == 3){
		if(check4.checked && check.checked == false){
			//aumenta dias al sueldo base
			sueldo_base.value=parseFloat(sueldo_base.value) + parseFloat(sueldo_diario.value);
			sueldo_base.value = parseFloat(Math.round(sueldo_base.value * 100) / 100).toFixed(2);
			//recalcula el total a pagar
			total.value=parseFloat(destajo.value) + parseFloat(sueldo_base.value);
			total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
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
			//descuenta dias al sueldo base
			sueldo_base.value=parseFloat(sueldo_base.value) - parseFloat(sueldo_diario.value);
			sueldo_base.value = parseFloat(Math.round(sueldo_base.value * 100) / 100).toFixed(2);
			//recalcula el total a pagar
			total.value=parseFloat(destajo.value) + parseFloat(sueldo_base.value);
			total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
		}
	}
	he = (parseFloat(sueldo_diario.value / 8 * horas_extras.value)) * 2;
	agregarBonificacionDesRec(no,"txt_he"+no,destajo.value-he,1);
	total.value=parseFloat(bonif.value) + parseFloat(sueldo_base.value) + parseFloat(destajo.value);
	total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
	
	if(document.getElementById("ckb_juevesAL"+no).checked || document.getElementById("ckb_viernesAL"+no).checked || document.getElementById("ckb_sabadoAL"+no).checked || document.getElementById("ckb_domingoAL"+no).checked || document.getElementById("ckb_lunesAL"+no).checked || document.getElementById("ckb_martesAL"+no).checked || document.getElementById("ckb_miercolesAL"+no).checked){
		total.value=parseFloat(sueldo_base.value);
		total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
	}
	
}//Fin de function establecerAsistenciaDesRec(no,elemento,incapacidad,elementModif,elementoModif2)

//Funcion que al deseleccionar un chek, realiza los calculos correspondintes al sueldo base
function agregarBonificacionDesRec(no,elemento,dest,opc){
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
		if(opc != 1){
			recalcularDestajoDesRec(document.getElementById("ckb_8hrs"+no),no,0);
			//recalcula el destajo del empleado
			recalcularDestajoDesRec(document.getElementById("ckb_12hrs"+no),no,0);
		}
	} else{
		//recalcula el destajo del empleado
		recalcularDestajoDesRec(check,no,1);
	}
	
	total.value=parseFloat(bonif.value) + parseFloat(sueldo_base.value) + parseFloat(destajo.value);
	total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
	
	if(document.getElementById("ckb_juevesAL"+no).checked || document.getElementById("ckb_viernesAL"+no).checked || document.getElementById("ckb_sabadoAL"+no).checked || document.getElementById("ckb_domingoAL"+no).checked || document.getElementById("ckb_lunesAL"+no).checked || document.getElementById("ckb_martesAL"+no).checked || document.getElementById("ckb_miercolesAL"+no).checked){
		total.value=parseFloat(sueldo_base.value);
		total.value = parseFloat(Math.round(total.value * 100) / 100).toFixed(2);
	}
}//Fin de function agregarBonificacionDesRec(no,elemento,dest)

//Funcion que recalcula el destajo del empleado
function recalcularDestajoDesRec(objeto,num,continuar){
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
function desbloquearCamposNominaDesRec(elemento,no){
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

/***********************************************************************************************************************************************************/
/*********************************************************REGISTRAR NOMINA ADMINISTRACION*******************************************************************/
/***********************************************************************************************************************************************************/

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

/***********************************************************************************************************************************************************/
/***********************************************************N�MINA INTERNA**********************************************************************************/
/***********************************************************************************************************************************************************/


/***********************************************************REGISTRAR N�MINA INTERNA************************************************************************/
function valFormSeleccionarEmpleados(frm_seleccionarEmpleados){
	//Esta varible indicar� al final si la validaci�n fue satisfactoria
	var validacion = 1;
	
	//Verificar que sea seleccionada una �rea
	if(frm_seleccionarEmpleados.cmb_area.value==""){
		alert("Seleccionar �rea");
		validacion = 0;
	}
	
	//Verificar que el rango de fechas sea valido
	if(validacion==1){
		//Si la funci�n validarPeriodoNomina significa que el periodo es valido, negamos el resultado para cambiar el valor a la variable 'validacion'
		if(!validarPeriodoNomina(frm_seleccionarEmpleados.txt_fechaIni.value,frm_seleccionarEmpleados.txt_fechaFin.value)){
			validacion = 0;
		}
	}
	
	
	//Regresar Verdadero cuando la validaci�n haya sido exitosa
	if(validacion==1)
		return true;
	else
		return false;	
}//Cierre de la funci�n valFormSeleccionarEmpleados(frm_seleccionarEmpleados)

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
	
	if(frm_registrarNomina.cmb_nomina.value=="" && res==1){
		res=0;
		alert ("Seleccionar una nomina");
	}
		
	if(res==1)
		return true;
	else
		return false;
}

/*Esta funcion valida que las fechas elegidas sean correctas*/
function validarPeriodoNomina(fecha1,fecha2){
	var band = 1;
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=fecha1.substr(0,2);
	var iniMes=fecha1.substr(3,2);
	var iniAnio=fecha1.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=fecha2.substr(0,2);
	var finMes=fecha2.substr(3,2);
	var finAnio=fecha2.substr(6,4);		
	
	//Armar la Fecha de Inicio y la Fecha de Fin para poder validar el Periodo Seleccionado
	var fechaIni = iniAnio+"/"+iniMes+"/"+iniDia+" 00:00:00";
	var fechaFin = finAnio+"/"+finMes+"/"+finDia+" 23:59:59";
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	//Verificar que la fecha de inicio no sea mayor a la de cierre
	if(fechaIni>fechaFin){		
		alert ("La Fecha de Fin NO Puede Ser Menor a la Fecha de Inicio");
		band = 0;
	}	
	else{
		/* Verificar que el periodo seleccionado coinsida con 7, 15 y 16 D�as, 
		 * S� el periodo seleccionado es menor a 16 y no coinside con algunos de los anteriores, notificar al usuario para que lo valide
		 * No permitir periodos mayores a 16 dias. */
		var diferencia = fechaFin-fechaIni;
		
		//En Javascript la diferencia devuelta entre fechas esta dada en Milisegundos, por lo cual 1 d�a corresponde a 86,400,000 Milisegundos
		//Redondeamos el resultado para obtener el numero de dias en forma entera
		var dias = Math.round(diferencia / 86400000);
		
		//Si el periodo seleccionado excede los 16 d�as notificar al usuario y no permitir el registro
		if(dias>16){
			band = 0;
			alert("El Periodo Seleccionado no Puede ser Mayor a 16 D�as\nSeleccionar Otro Periodo");
			//Resetear los campos de Fecha del Formulario de Seleccionar Empleados (frm_seleccionarEmpleados)
			document.frm_seleccionarEmpleados.txt_fechaIni.value = document.frm_seleccionarEmpleados.txt_fechaIni.defaultValue;
			document.frm_seleccionarEmpleados.txt_fechaFin.value = document.frm_seleccionarEmpleados.txt_fechaFin.defaultValue;
			
			document.frm_seleccionarEmpleados.hdn_cantDias.value = document.frm_seleccionarEmpleados.hdn_cantDias.defaultvalue;			
		}//Cierre if(dias>16)
		else if(dias!=7 && dias!=15 && dias!=16){
			//Si el usuario presiona 'cancelar' negamos el resultado y cambiamos el valor de la 'band'
			if(!confirm("El Periodo Seleccionado es de "+dias+" dias, �Esto es Correcto?")){
				band = 0;
				alert("Seleccionar Otro Periodo");
				//Resetear los campos de Fecha del Formulario de Seleccionar Empleados (frm_seleccionarEmpleados)
				document.frm_seleccionarEmpleados.txt_fechaIni.value = document.frm_seleccionarEmpleados.txt_fechaIni.defaultValue;
				document.frm_seleccionarEmpleados.txt_fechaFin.value = document.frm_seleccionarEmpleados.txt_fechaFin.defaultValue;				
				
				document.frm_seleccionarEmpleados.hdn_cantDias.value = document.frm_seleccionarEmpleados.hdn_cantDias.defaultvalue;
			}
			else{//S� el usuario dio clic en el boton 'Aceptar' colocamos la cantidad de d�as en la variable 'hdn_cantDias'
				document.frm_seleccionarEmpleados.hdn_cantDias.value = dias;
			}
			
		}//Cierre else if(dias!=7 && dias!=15 && dias!=16)
		else if(dias==7 || dias==15 || dias==16){//Colocar la cantidad de d�as en la variable 'hdn_cantDias' cuando el periodo sea de 7, 15 o 16 d�as
			document.frm_seleccionarEmpleados.hdn_cantDias.value = dias;
		}
		
	}//Cierre else if(fechaIni>fechaFin)
	
	if(band==1)
		return true;
	else
		return false;
}//Cierre de la funci�n function validarPeriodoNomina(fecha1,fecha2)


/*Esta funci�n realizar el Calculo del Sueldo total, cuando son modificados los conceptos que lo integran*/
function calcularSueldoTotal(noReg){
	
	//Recuperar los datos que integran el Sueldo Total, no se verifica si estan vacios o si son numeros validos, ya que se tiene la certeza de que los datos son correctos
	//Quitar las posbles comas y los signos de pesos presentes en las cantidades
	var txtSueldo = document.getElementById("txt_sueldo"+noReg).value.replace('$','');
	txtSueldo = txtSueldo.replace(',','');
	var txtTiempoExtra = document.getElementById("txt_tiempoExtra"+noReg).value.replace('$','');
	txtTiempoExtra = txtTiempoExtra.replace(',','');
	var txtDescansoTrabajado = document.getElementById("txt_descansoTrabajado"+noReg).value.replace('$','');
	txtDescansoTrabajado = txtDescansoTrabajado.replace(',','');
	var txtBonificacion = document.getElementById("txt_bonificacion"+noReg).value.replace('$','');
	txtBonificacion = txtBonificacion.replace(',','');
		
	//Convertir los datos obtenidos en numeros con punto flotante
	var sueldo = parseFloat(txtSueldo);
	var tiempoExtra = parseFloat(txtTiempoExtra);
	var descansoTrabajado = parseFloat(txtDescansoTrabajado);
	var bonificacion = parseFloat(txtBonificacion); 	
	
	var sueldoTotal = sueldo + tiempoExtra + descansoTrabajado + bonificacion; 
	
	//Asignar el sueldo total a la caja de texto que lo mostrar� con formato tipo moneda $0,000.00
	formatCurrencySing(sueldoTotal,"txt_sueldoTotal"+noReg);
				
}//Cierre de la funci�n calcularSueldoTotal(noReg)


//Esta funci�n se encargar� de abrir una venta emergente donde se podran seleccionar los bonos que apliquen al empleado
function abriVtnBonos(nomEmpleado,noReg,sueldoTotal){
	//Abri la Ventana para registrar la obra y guardar la referencia de la misma
	window.open("verAsignarBonos.php?nomEmpleado="+nomEmpleado+"&noReg="+noReg+"&sueldoTotal="+sueldoTotal,
	"asignarBonos","top=10, left=10, width=860, height=655, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no");
}


//Esta funci�n realizar� la suma de los bonos que sean seleccionados para asignar al trabajador
function sumarBono(checkBox,cant){
	//Obtener la cantidad total actual de la bonificaci�n
	var totalBono = parseFloat(document.getElementById("txt_totalBono").value.replace(/,/g,''));
	
	//Si el checkbox es seleccionado, sumar la cantidad recibida como parametro a la caja de texto que contiene el total de la Bonificaci�n
	if(checkBox.checked){
		totalBono = totalBono + cant;
	}
	else{//restar la cantidad recibida como parametro al total de la bonificaci�n
		totalBono = totalBono - cant;
	}
	
	//Reasignar la cantidad obtenida dando formato de numero
	formatCurrency(totalBono,'txt_totalBono');
}//Cierre sumarBono(checkBox,cant)


//Esta funci�n se encarga de verificar de que al menos sea seleccionado 1 bono en la Asignaci�n de Bonos a los Empleados
function valFormSeleccionarBonos(frm_seleccionarBonos){
	
	//Si el valor de validaci�n permanece en 1, significa que el proceso de validaci�n fue exitoso.
	var validacion = 1;
	
	//Obtener la cantidad de CheckBox dibujados en la p�gina
	var cantBonos = frm_seleccionarBonos.hdn_cantBonos.value;
	
	//Ciclo para verificar cada bono desplegado
	var chkSeleccioandos = 0;
	for(var i=1;i<=cantBonos;i++){
		if(frm_seleccionarBonos["chk_idBono"+i].checked)
			chkSeleccioandos++;
	}
	
	//Si ning�n CheckBox fue seleccionado, cambiamos el valor de la variable validacion
	if(chkSeleccioandos==0){
		alert("Seleccionar al Menos un Bono Para Ser Asignado");
		validacion = 0;
	}
	
	
	if(validacion==1)
		return true;
	else
		return false;
		
}//Cierre valFormSeleccionarBonos(frm_seleccionarBonos)

/***********************************************************CONSULTAR N�MINA INTERNA************************************************************************/

//Funcion que permite validar el formulario de consulta nomina interna por area, puesto y fecha
function valFormConsultaNomInterna(frm_consultarNominaInterna){
	//Variable bandera que permite revisar si la validaci�n fue exitosa.
	var band = 1;
	
	if (frm_consultarNominaInterna.cmb_anio.value==""&&band==1){
		alert("Seleccionar A�o");
		band=0;
	}
	if (frm_consultarNominaInterna.cmb_mes.value==""&&band==1){
		alert("Seleccionar Mes");
		band=0;
	}
	if (frm_consultarNominaInterna.cmb_area.value==""&&band==1){
		alert("Seleccionar �rea");
		band=0;
	}
	if (frm_consultarNominaInterna.cmb_periodo.value==""&&band==1){
		alert("Seleccionar Periodo");
		band=0;
	}
		
	if (band==1)
		return true;
	else
		return false;

}//Cierre de la funci�n valFormConsultaNomInter(frm_consultarNomina)


/************************************************************************************************************************************************************************/
/**********************************************************************BOLSA DE TRABAJO-AGREGAR ASPIRANTE****************************************************************/
/************************************************************************************************************************************************************************/
//Funcion que permite validar los datos del Aspirante a Empleo
function valFormRegistrarAspirante(frm_registrarAspirante){
	//Variable bandera que permite revisar si la validaci�n fue exitosa.
	var band = 1;
		
	if (frm_registrarAspirante.txt_folioAspirante.value==""&&band==1){
		alert("Introducir el Folio del Aspirante");
		band=0;
	}
	
	if (frm_registrarAspirante.txt_nombre.value==""&&band==1){
		alert("Introducir el Nombre del Aspirante");
		band=0;
	}
	
	if (frm_registrarAspirante.txt_apePat.value==""&&band==1){
		alert("Introducir el Apellido Paterno");
		band=0;
	}
	
	if (frm_registrarAspirante.txt_apeMat.value==""&&band==1){
		alert("Introducir el Apellido Materno");
		band=0;
	}
	
	if (frm_registrarAspirante.txt_curp.value==""&&band==1){
		alert("Introducir la CURP del Aspirante");
		band=0;
	}
	
	if (frm_registrarAspirante.txt_edad.value==""&&band==1){
		alert("Introducir la Edad");
		band=0;
	}
	
	if (frm_registrarAspirante.txt_edoCivil.value==""&&band==1){
		alert("Introducir el Estado Civil");
		band=0;
	}
	
	if (frm_registrarAspirante.txt_lugarNac.value==""&&band==1){
		alert("Introducir el Lugar de Nacimiento");
		band=0;
	}
	
	if (frm_registrarAspirante.txt_nacionalidad.value==""&&band==1){
		alert("Introducir la Nacionalidad");
		band=0;
	}
	
	if (band==1)
		return true;
	else
		return false;

}
/************************************************************************************************************************************************************************/
/*****************************************BOLSA DE TRABAJO-AGREGAR �REAS Y PUESTOS RECOMENDADOS PARA EL ASPIRANTE********************************************************/
/************************************************************************************************************************************************************************/
//Funcion que permite validar los datos del del �rea y Puestos Recomendados para el Aspirante a Empleo
function valFormRegistrarPuestoAspirante(frm_puestoAspirante){
	//Variable bandera que permite revisar si la validaci�n fue exitosa.
	var band = 1;
	
	//Hacer la Validacion cuando se le de click al boton de sbt_registrarPuesto
	if(frm_puestoAspirante.hdn_botonSeleccionado.value=="registrarAreaPuesto"){
		if (frm_puestoAspirante.cmb_area.value=="" && frm_puestoAspirante.txt_areaRecomendada.value=="" && band==1){
			alert("Seleccionar o Introducir el �rea Recomendada para el Aspirante");
			band=0;
		}
		
		if (frm_puestoAspirante.cmb_puesto.value=="" && frm_puestoAspirante.txt_puestoRecomendado.value=="" && band==1){
			alert("Seleccionar o Introducir el Puesto Recomendado para el Aspirante");
			band=0;
		}
	}

	if(band==1)
		return true;
	else
		return false;

}


/************************************************************************************************************************************************************************/
/*********************************************************BOLSA DE TRABAJO-AGREGAR CONTACTOS AL ASPIRANTE****************************************************************/
/************************************************************************************************************************************************************************/
//Funcion que permite validar los datos del Aspirante a Empleo
function valFormContactoAspirantes(frm_contactoAspirante){
	//Variable bandera que permite revisar si la validaci�n fue exitosa.
	var band = 1;
	
	if(frm_contactoAspirante.hdn_botonSeleccionado.value=="registrarContacto"){
		if (frm_contactoAspirante.txt_nombreCont.value==""&&band==1){
			alert("Introducir el Nombre del Contacto");
			band=0;
		}
		
		if (frm_contactoAspirante.txt_calle.value==""&&band==1){
			alert("Introducir el Nombre de la Calle");
			band=0;
		}
		
		if (frm_contactoAspirante.txt_numExt.value==""&&band==1){
			alert("Introducir el Numero Externo");
			band=0;
		}
		
		if (frm_contactoAspirante.txt_colonia.value==""&&band==1){
			alert("Introducir Nombre de la Colonia");
			band=0;
		}
		
		if (frm_contactoAspirante.txt_estado.value==""&&band==1){
			alert("Introducir el Estado");
			band=0;
		}
		
		if (frm_contactoAspirante.txt_pais.value==""&&band==1){
			alert("Introducir el Pais");
			band=0;
		}
		
		if (frm_contactoAspirante.txt_tel.value==""&&band==1){
			alert("Introducir el Telefono");
			band=0;
		}
	}


	if (band==1)
		return true;
	else
		return false;

}
/************************************************************************************************************************************************************************/
/**********************************************************************BOLSA DE TRABAJO-ELIMINAR ASPIRANTE****************************************************************/
/************************************************************************************************************************************************************************/

//Funcion que permite validar los datos del Aspirante a Empleo
function valFormEliminarAspirantePuesto(frm_eliminarAspirantePuesto){
	//Variable bandera que permite revisar si la validaci�n fue exitosa.
	var band = 1;
		
	if(!validarFechas(frm_eliminarAspirantePuesto.txt_fechaPuestoIni.value,frm_eliminarAspirantePuesto.txt_fechaPuestoFin.value))
		band = 0;
	
	if (frm_eliminarAspirantePuesto.cmb_puesto.value==""&&band==1){
		alert("Seleccionar el Puesto");
		band=0;
	}

	if (band==1)
		return true;
	else
		return false;

}

/*Esta funcion se Encarga de Validar el Formulario donde se seleccionan los registros de los aspirantes que seran eliminados*/
function valFormResultadosAspirante(frm_resultadosAspirante){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Contar los CheckBox
	var cantCkbs = 0;
	for(i=0;i<document.frm_resultadosAspirante.length;i++){
		if(document.frm_resultadosAspirante[i].name.substring(0,4)=="ckb_")
			cantCkbs++;
	}
	
	//Verificar que un elemeto haya sido seleccionado  de los checkBox
	var ctrl = 0;
	for(i=0;i<cantCkbs;i++){
		if(frm_resultadosAspirante["ckb_"+(i+1)].checked){
			ctrl = 1;
			break;
		}		
	}
	
	if(ctrl==0){
		alert("Seleccionar al Menos un Registro para ser Eliminado");
		res = 0;
	}
	//Esta se le coloco de ultimo momento porque no notificaba al usuario cuando seleccionaba registros para aspirante para posteriormente eliminar estos registros
	if (ctrl==1){
		if(!confirm("�Estas Seguro que Quieres Borrar el Registro del Aspirante?\nToda la informaci�n relacionada se Borrar�")){
			res=0;
		}
	}
	//Termina instruccion
	if(res==1)
		return true;
	else
		return false;
}



/************************************************************************************************************************************************************************/
/****************************************************************BOLSA DE TRABAJO-CONSULTAR ASPIRANTES*******************************************************************/
/************************************************************************************************************************************************************************/
//Funcion que permite validar los datos del Aspirante a Empleo para consultar  todos los aspirantes registrados de acuerod al puesto seleccionado
function valFormConsultarAspirantePuesto(frm_consultarAspirantePuesto){
	//Variable bandera que permite revisar si la validaci�n fue exitosa.
	var band = 1;
		
	if(!validarFechas(frm_consultarAspirantePuesto.txt_fechaPuestoIni.value,frm_consultarAspirantePuesto.txt_fechaPuestoFin.value))
		band = 0;
	
	
	if (band==1)
		return true;
	else
		return false;

}
/************************************************************************************************************************************************************************/
/****************************************************************BOLSA DE TRABAJO-MODIFICAR ASPIRANTES*******************************************************************/
/************************************************************************************************************************************************************************/
//Funcion que permite validar los datos del Aspirante a Empleo cuando es Modificado
function valFormModificarAspirante(frm_modificarAspirante){
	//Variable bandera que permite revisar si la validaci�n fue exitosa.
	var band = 1;
	
	if (frm_modificarAspirante.txt_nombre.value==""&&band==1){
		alert("Introducir el Nombre del Aspirante");
		band=0;
	}
	
	if (frm_modificarAspirante.txt_apePat.value==""&&band==1){
		alert("Introducir el Apellido Paterno");
		band=0;
	}
	
	if (frm_modificarAspirante.txt_apeMat.value==""&&band==1){
		alert("Introducir el Apellido Materno");
		band=0;
	}
	
	if (frm_modificarAspirante.txt_curp.value==""&&band==1){
		alert("Introducir la CURP del Aspirante");
		band=0;
	}
	
	if (frm_modificarAspirante.txt_edad.value==""&&band==1){
		alert("Introducir la Edad");
		band=0;
	}
	
	if (frm_modificarAspirante.txt_edoCivil.value==""&&band==1){
		alert("Introducir el Estado Civil");
		band=0;
	}
	
	if (frm_modificarAspirante.txt_lugarNac.value==""&&band==1){
		alert("Introducir el Lugar de Nacimiento");
		band=0;
	}
	
	if (frm_modificarAspirante.txt_nacionalidad.value==""&&band==1){
		alert("Introducir la Nacionalidad");
		band=0;
	}
	
	if (band==1)
		return true;
	else
		return false;

}

/*******************************************************************MODIFICAR �REA Y PUESTO DEL ASPIRANTE*********************************************************************/

//Funcion que permite validar los datos del del �rea y Puestos Recomendados para el Aspirante a Empleo cuando es Modificado
function valFormModificarPuestoAspirante(frm_modificarPuestoAspirante){
	//Variable bandera que permite revisar si la validaci�n fue exitosa.
	var band = 1;
	
	//Hacer la Validacion cuando se le de click al boton de sbt_registrarPuesto
	if(frm_modificarPuestoAspirante.hdn_botonSeleccionado.value=="registrarAreaPuesto"){
		if (frm_modificarPuestoAspirante.cmb_area.value=="" && frm_modificarPuestoAspirante.txt_areaRecomendada.value=="" && band==1){
			alert("Seleccionar o Introducir el �rea Recomendada para el Aspirante");
			band=0;
		}
		
		if (frm_modificarPuestoAspirante.cmb_puesto.value=="" && frm_modificarPuestoAspirante.txt_puestoRecomendado.value=="" && band==1){
			alert("Seleccionar o Introducir el Puesto Recomendado para el Aspirante");
			band=0;
		}
	}

	if(band==1)
		return true;
	else
		return false;

}

/*******************************************************************MODIFICAR CONTACTOS DEL ASPIRANTE*********************************************************************/

//Funcion que permite validar los datos del Aspirante a Empleo en caso de que sea modificado algun registro
function valFormModificarContactoAspirante(frm_modificarContactoAspirante){
	//Variable bandera que permite revisar si la validaci�n fue exitosa.
	var band = 1;
	
	if(frm_modificarContactoAspirante.hdn_botonSeleccionado.value=="registrarContacto"){
		if (frm_modificarContactoAspirante.txt_nombreCont.value==""&&band==1){
			alert("Introducir el Nombre del Contacto");
			band=0;
		}
		
		if (frm_modificarContactoAspirante.txt_calle.value==""&&band==1){
			alert("Introducir el Nombre de la Calle");
			band=0;
		}
		
		if (frm_modificarContactoAspirante.txt_numExt.value==""&&band==1){
			alert("Introducir el Numero Externo");
			band=0;
		}
		
		if (frm_modificarContactoAspirante.txt_colonia.value==""&&band==1){
			alert("Introducir Nombre de la Colonia");
			band=0;
		}
		
		if (frm_modificarContactoAspirante.txt_estado.value==""&&band==1){
			alert("Introducir el Estado");
			band=0;
		}
		
		if (frm_modificarContactoAspirante.txt_pais.value==""&&band==1){
			alert("Introducir el Pais");
			band=0;
		}
		
		if (frm_modificarContactoAspirante.txt_tel.value==""&&band==1){
			alert("Introducir el Telefono");
			band=0;
		}
	}


	if (band==1)
		return true;
	else
		return false;

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
/************************************************************GENERAR NOMBRAMIENTO*******************************************************************************/
/***************************************************************************************************************************************************************/
//Funcion que valida el formulario de generar nombramiento 
function valFormGenerarNombramiento(frm_generarNombramiento){
	
	var band=1;		
	
	if (frm_generarNombramiento.txt_nombre.value==""){
		alert("Introducir el Nombre del Trabajador");
		band=0;
	}
	
	if (frm_generarNombramiento.txt_RFCEmpleado.value==""  && band==1){
		alert("La Persona Seleccionada no Puede Recibir Nombramiento");
		band=0;
	}

	if (!frm_generarNombramiento.ckb_nuevaArea.checked && frm_generarNombramiento.cmb_area.value=="" && band==1){
		alert("Seleccionar el �rea del Trabajador");
		band=0;
	}
	
	if (!frm_generarNombramiento.ckb_nuevaArea.checked && frm_generarNombramiento.cmb_area.value=="" && band==1 && !frm_generarNombramiento.ckb_nuevaArea.checked &&
		band==1){
		alert("Introducir el Puesto del Trabajador");
		band=0;
	}
	
	if (!frm_generarNombramiento.ckb_nuevoPuesto.checked && frm_generarNombramiento.cmb_puesto.value=="" && !frm_generarNombramiento.ckb_nuevaArea.checked && band==1){
		alert("Seleccionar el Puesto del Trabajador");
		band=0;
	}

	if (frm_generarNombramiento.txa_objetivo.value=="" && band==1){
		alert("Introducir el Objetivo del Puesto");
		band=0;
	}
	
	if (band==1)
		return true;
	else
		return false;
}

/***************************************************************************************************************************************************************/
/*************************************************************** GESTIONAR BONOS *******************************************************************************/
/***************************************************************************************************************************************************************/
//Funcion que valida el formulario de generar nombramiento 
function valFormGestionarBonos(frm_gestionarBonos){
		
	var band = 1;
	
	if(frm_gestionarBonos.hdn_botonSelect.value!="eliminar"){
	
		//Verificar que haya sido ingresado un bono o haya sido seleccionado uno
		if(frm_gestionarBonos.cmb_bono.value==""){
			alert("Seleccionar Bono o Ingresar Nuevo Bono");
			band = 0;
		}
		//Verificar que la Descripci�n del Bono sea Ingresadas
		if(frm_gestionarBonos.txa_descripcion.value=="" && band==1){
			alert("Introducir la Descripci�n del Bono");
			band = 0;
		}
		//Verificar que la Cantidad del Bono Sea Ingresada
		if(frm_gestionarBonos.txt_cantidadBono.value=="" && band==1){
			alert("Ingresar el Monto del Bono");
			band = 0;
		}
		if(band==1){
			if(!validarEntero(frm_gestionarBonos.txt_cantidadBono.value.replace(/,/g,''),"La Cantidad del Bono"))
				band = 0;		
		}	
		//Verficar que sea ingresada la fecha del bono
		if(frm_gestionarBonos.txt_fecha.value=="" && band==1){
			alert("Introducir la Fecha de Registro del Bono");
			band = 0;
		}	
		//Verificar que se haya ingresado el nombre de quien autrizo
		if(frm_gestionarBonos.txt_autorizo.value=="" && band==1){
			alert("Introducir Quien Autoriz� el Bono");
			band=0;
		}
	}//cierre del if(frm_gestionarBonos.hdn_botonSelect.value!="eliminar")
		
	
	if(band==1)
		return true;
	else
		return false;
		
}//Cierre de la funci�n valFormGestionarBonos(frm_gestionarBonos)


//Funcion que permite agregar una nueva opcion, no existente a un combo box (Combo de Ubicacion en el Registro de Rezagado)
function verificarOpcSelect(comboBox){
	
	//Si la opci�n es vac�a, reseteamos el formulario
	if(comboBox.value==""){		
		//Dar el valor de los campos por defecto
		document.frm_gestionarBonos.reset();
		
		//Desactivar los botones de Guardar, Modificar y Eliminar Bono
		document.frm_gestionarBonos.sbt_guardarBono.disabled = true;
		document.frm_gestionarBonos.sbt_modificarBono.disabled = true;
		document.frm_gestionarBonos.sbt_eliminarBono.disabled = true;		
	}//Cierre if(comboBox.value=="")
	
	
	//Si la opcion seleccionada es agregar nueva unidad ejecutar el siguiete codigo
	if(comboBox.value=="NUEVO"){				
		
		var nvaOpcion = "";
		var valCajaPrompt = "Nueva Bono...";
		do{
			var condicion = false;
			
			nvaOpcion = prompt("Introducir Nombre del Bono",valCajaPrompt);			
			
			if(nvaOpcion=="Nueva Bono..." ||  nvaOpcion=="" || nvaOpcion=="NUEVO")
				condicion = true;
			else if(nvaOpcion!=null){								
				//Verificar que el tama�o de la nueva opci�n no exceda los 20 caracteres
				if(nvaOpcion.length>20){
					alert("El Nombre del Bono Excede el Tama�o de 20 Caracteres Permitidos");
					valCajaPrompt = nvaOpcion.substring(0,20);
					condicion = true;
				}
				else
					condicion = false;
			}
		}while(condicion);
		
		//Si el usuario presiono cancelar no se relaiza ninguan actividad de lo contrario asignar la nueva opcion al combo
		if(nvaOpcion!=null){
			//Convertir a mayusculas la opcion dada
			nvaOpcion = nvaOpcion.toUpperCase();
			//Variable que nos ayudara a saber si la nueva opcion ya esta registrada en el combo
			var existe = 0;
			var pos = 0;
			
			//Verificar que la nueva opcion no se encuentre dentro de las opciones actuales del combo
			for(i=0;i<comboBox.length;i++){				
				if(comboBox.options[i].text==nvaOpcion){					
					existe = 1;
					pos = comboBox.options[i].value;
				}
			}//Cierre for(i=0;i<comboBox.length;i++)
			
			
			//Si la nva opcion no esta registrada agregarla como una adicional y preseleccionarla
			if(existe==0){
				//Activar solo el boton de guardar y desactivar los botones de Modificar y Borrar cuando se agrega un nuevo bono
				document.frm_gestionarBonos.sbt_guardarBono.disabled = false;
				document.frm_gestionarBonos.sbt_modificarBono.disabled = true;
				document.frm_gestionarBonos.sbt_eliminarBono.disabled = true;
		
				//Agregar al final la nueva opcion seleccionada
				comboBox.length++;
				comboBox.options[comboBox.length-1].text = nvaOpcion;
				comboBox.options[comboBox.length-1].value = nvaOpcion;
				
				//Guardar el nombre del nuevo Bono en la caja de texto oculta
				document.frm_gestionarBonos.hdn_nomBonoNvo.value = nvaOpcion;
				
				//Deshabilitar el Combo cuando sea un Bono nuevo para que no permita la selecci�n de otra opci�n
				comboBox.disabled = true;
				
				//Preseleccionar la opcion agregada
				comboBox.options[comboBox.length-1].selected = true;
				
			}//Cierre if(existe==0)			
			else{
				alert("El Bono Ingresado ya esta Registrado \n en las Opciones de la Lista de Bonos");
				comboBox.value = pos;
				
				//Activar los botones de Modificar y Borrar y desactivar el boton de guardar
				document.frm_gestionarBonos.sbt_modificarBono.disabled = true;
				document.frm_gestionarBonos.sbt_eliminarBono.disabled = true;
				document.frm_gestionarBonos.sbt_guardarBono.disabled = false;
				
				//Guardar el nombre del nuevo Bono en la caja de texto oculta
				document.frm_gestionarBonos.hdn_nomBonoNvo.value = comboBox.options[pos].text;
			}
			
		}//Cierre if(nvaOpcion!=null)		
		else if(nvaOpcion==null){
			//Deshabilitar los botones cuando el usuario cancele el registro de un nuevo bono
			document.frm_gestionarBonos.sbt_modificarBono.disabled = true;
			document.frm_gestionarBonos.sbt_eliminarBono.disabled = true;
			document.frm_gestionarBonos.sbt_guardarBono.disabled = true;
			
			comboBox.value = "";
		}
	}//Cierre if(comboBox.value=="NUEVO")
	
	
	if(comboBox.value!="NUEVO" && comboBox.value!="" && !comboBox.disabled){		
		//Activar los botones de Modificar y Borrar y desactivar el boton de guardar
		document.frm_gestionarBonos.sbt_modificarBono.disabled = false;
		document.frm_gestionarBonos.sbt_eliminarBono.disabled = false;
		document.frm_gestionarBonos.sbt_guardarBono.disabled = true;
		
		//Guardar el nombre del nuevo Bono en la caja de texto oculta
		document.frm_gestionarBonos.hdn_nomBonoNvo.value = comboBox.options[comboBox.selectedIndex].text;
		
		//Implementar funcion AJAX, para obtener los datos del bono seleccionado
		obtenerDatosBono(comboBox.value);				
	}
	
		
}//Cierre de la funci�n agregarNvaUbicacion(comboBox)


//Esta funci�n desactiva los botones y reactiva los elementos del formulario
function limpiarFrm(){
	//Recargar la pagina para borrar los datos no guardados en la BD en el combo de Bonos	
	location.reload();
}


/************************************************************************************************************************************************************/
/************************************************************EQUIPO SEGURIDAD********************************************************************************/
/************************************************************************************************************************************************************/
//Funci�n para validar el formulario de consulta de equipo de seguridad
function valFormConsultarEquipo(frm_consultarEquipo){
	//Variable que permite revisar si la validaci�n fue exitosa
	var band=1;
	//Verificamos que el combo area este definido
	if (frm_consultarEquipo.txt_nombre.value==""&&band==1){
		alert("Introducir Nombre Empleado");
		band=0;
	}
	
	if (band==1)
		return true;
	else
		return false;
}


/************************************************************************************************************************************************************/
/************************************************************MODIFICAR ORGANIGRAMA***************************************************************************/
/************************************************************************************************************************************************************/
/*Esta funcion valida el formulario de Modificar Organigrama de la pagina frm_modificarOrganigrama.php*/
function valFormModificarOrganigrama(frm_modificarOrganigrama){
	//Si el valor de band permanece en 1, el proceso de validaci�n fue satisfactorio
	var band = 1;
	
	//Hacer la validaci�n cuando se quiere Insertar o Actualizar un Departamento
	if(frm_modificarOrganigrama.hdn_tipoSentencia.value=="INSERT" || frm_modificarOrganigrama.hdn_tipoSentencia.value=="UPDATE"){
		//Revisar que el Departamento sea Proporcionado, ya sea nuevo o uno existente
		if(frm_modificarOrganigrama.cmb_departamento.value=="" && frm_modificarOrganigrama.txt_departamento.value==""){
			alert("Seleccionar un Departamento o Ingresar uno Nuevo");
			band = 0;
		}
		
		//Verificar que un empleado haya sido selecionado mediante su RFC
		if(frm_modificarOrganigrama.txt_RFCEmpleado.value=="" && band==1){
			alert("Seleccionar el Empleado que Ser� el Encargado del Departamento");
			band = 0;
		}
	}
	//Hacer la validaci�n cuando se quiere eliminar un Departamento
	else if(frm_modificarOrganigrama.hdn_tipoSentencia.value=="DELETE"){
		//Verificar haya sido seleccionado un Departamento para ser eliminado
		if(frm_modificarOrganigrama.cmb_departamento.value==""){
			alert("Seleccionar un Departamento para Ser Eliminado");
			band = 0;
		}	
		
		if(band==1){
			if(!confirm("�Estas Seguro que Quieres Eliminar el Departamento?")){
				band = 0;
			}
		}
	}
	
	
	//Si el valor de band permanece en 1, el proceso de validaci�n fue satisfactorio
	if(band==1)		
		return true;
	else
		return false;	
}


//Esta funcion solicita al usuario el nuevo departamento para ser agregado al Organigrama de la Empresa
function solicitarNuevoDepto(ckb_depto,txt_depto,cmb_depto){
	//Si el checkbox para el nuevo Departamento esta seleccionado, pedir el nombre de dicho Departamento
	if (ckb_depto.checked){
		var depto = prompt("�Nombre del Nuevo Departamento?","Nombre del Departamento...");	
		if(depto!=null && depto!="Nombre del Departamento..." && depto!=""){
			//Asignar el valor obtenido a la caja de texto que lo mostrara
			document.getElementById(txt_depto).value = depto;
			//Verificar que el combo este definido para poder deshabilitarlo
			if (document.getElementById(cmb_depto)!=null){
				//Quitar el valor seleccionado
				document.getElementById(cmb_depto).value = "";				
				//Deshabilitar el ComboBox para que el usuario no lo pueda modificar
				document.getElementById(cmb_depto).disabled = true;				
			}
				
			//Desactivar la Caja de Texto donde se coloca el encargado e indicar al usuario que debe asignar un responsable al nuevo Depto
			document.getElementById("txt_encargadoDepto").readOnly = true;
			document.getElementById("txt_encargadoDepto").value = "Asignar Encargado del Nuevo Departamento";
			
			//Indicar que la Operacion a Realizar es un INSERT en la BD para e Nuevo Puesto
			document.getElementById("hdn_tipoSentencia").value = "INSERT";
			//Modificar la leyenda del boton
			document.getElementById("sbt_modificar").value = "Agregar";
		}
		else
			//Regresar False si se presiona el bot�n cancelar o se asigna un valor equivocado
			ckb_depto.checked = false;
	}
	//Si el checkbox para nuevo Departamento se deselecciona, borrar el dato escrito en la caja de texto y reactivar el combo de Departamento
	else{				
		document.getElementById(txt_depto).value = "";
		//Verificar que el combo este definido para poder Habilitarlo
		if (document.getElementById(cmb_depto)!=null)
			//Habilitar el ComboBox y el deseleccionar el CheckBox para que el usuario pueda agregar nueva informaci�n
			document.getElementById(cmb_depto).disabled = false;				
		
		//Activar la Caja de Texto donde se coloca el encargado y borrar su contenido
		document.getElementById("txt_encargadoDepto").readOnly = false;
		document.getElementById("txt_encargadoDepto").value = "";
		
		//Regresar el valor original de la variable para indicar que se trata de una Actulizacion en la BD
		document.getElementById("hdn_tipoSentencia").value = "UPDATE";
		//Modificar la leyenda del boton
		document.getElementById("sbt_modificar").value = "Modificar";
	}
}


function limpiarCampos(cajaTexto){
	if(cajaTexto.value=="")
		document.getElementById("txt_RFCEmpleado").value = "";
}


/************************************************************************************************************************************************************/
/************************************************************AGREGAR  PRESTAMO*******************************************************************************/
/************************************************************************************************************************************************************/
/*Esta funcion valida el formulario de agregar prestamo de la pagina frm_agregarPrestamo.php*/
function valFormAgregarPrestamo(frm_agregarPrestamo){
	//Si el valor de band permanece en 1, el proceso de validaci�n fue satisfactorio
	var band = 1;
	
	//Verificar que el Empleado sea un canditato a recibir un Prestamo
	if(frm_agregarPrestamo.hdn_prestamoAutorizado.value=="no"){
		alert("El Empleado Seleccionado NO Puede Recibir un Prestamo");
		band = 0;
	}
		
	//Revisar que el nombre del empleado haya sido ingresado
	if(frm_agregarPrestamo.txt_RFCEmpleado.value==""  && band==1){
		alert("Seleccionar el Empleado que Recibir� el Pr�stamo");
		band = 0;
	}
	
	if (frm_agregarPrestamo.cmb_nomPrestamo.value=="" && frm_agregarPrestamo.txt_nuevoPrestamo.value=="" && band==1){
		alert("Seleccionar o Ingresar el Nombre del Pr�stamo");
		band=0;
	}
	
	//Revisar el nombre de la persona que autoriza el prestamo se haya ingresado
	if(frm_agregarPrestamo.txt_autorizo.value=="" && band==1){
		alert("Ingresar el Nombre de Qui�n Autoriz� el Pr�stamo");
		band = 0;
	}
		
	//Revisar que la cantidad haya sido ingresado
	if(frm_agregarPrestamo.txt_cantidadPrestamo.value=="" && band==1){
		alert("Ingresar la Cantidad del Pr�stamo");
		band = 0;
	}

	//Verificar que la cantidad del prestamo sea diferente de cero (0)
	if(frm_agregarPrestamo.txt_cantidadPrestamo.value=="0.00" && band==1){
		alert("El Valor del Pr�stamo Debe ser Mayor a Cero(0)");
		band = 0;
	}
	
	//Verificar que sea seleccionado el periodo de pago
	if(frm_agregarPrestamo.cmb_periodo.value=="" && band==1){
		alert("Seleccionar el Periodo de Pago del Pr�stamo");
		band = 0;
	}
		
	//Verificar que sea seleccionado el periodo de pago
	if(frm_agregarPrestamo.txt_pagoPorPeriodo.value=="" && band==1){
		alert("Ingresar la Cantidad a Pagar por Periodo");
		band = 0;
	}
	
	//Verificar que la cantidad a pagar por periodo sea diferente de cero (0)
	if(frm_agregarPrestamo.txt_pagoPorPeriodo.value=="0.00" && band==1){
		alert("El Valor del Pago por Periodo Debe ser Mayor a Cero(0)");
		band = 0;
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}//Cierre valFormAgregarPrestamo(frm_agregarPrestamo)


/*Esta funcion envia un mensaje de confirmaci�n al Usuario, cuando el prestamo excede de $2,000.00 pesos*/
function confirmarCantPrestamo(txtCajaPrestamo){	
	//Enviar un mensaje de confirmacion al Usuario cuando el prestamo excede de $2,000.00
	if(txtCajaPrestamo.value>2000){
		if(confirm("El Pr�stamo es Superior a $2,000.00 \n�Desea Continuar?")) 
			formatCurrency(txtCajaPrestamo.value,txtCajaPrestamo.name);//Dar formato a los numeros introducidos
		else//Si el Usuario cancela, entonces borramos el contenido de la caja		
			txtCajaPrestamo.value="";
	}
	else
		formatCurrency(txtCajaPrestamo.value,txtCajaPrestamo.name);//Dar formato a los numeros introducidos
}//Cierre de la funci�n confirmarCantPrestamo(txtCajaPrestamo)



/***************************************************************************************************************************************************************/
/***************************************************************CONSULTAR PRESTAMO*******************************************************************************/
/***************************************************************************************************************************************************************/
//Funcion para Evaluar los datos del formularo consultar Prestamos por fecha
function valFormConsultarPrestamoFecha(frm_consultarPrestamoFecha){
	
	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;

	if(!validarFechas(frm_consultarPrestamoFecha.txt_fechaIni.value,frm_consultarPrestamoFecha.txt_fechaFin.value))
		band = 0;				
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
	

}

//Funcion para Evaluar los datos del formularo consultar Prestamos por Area
function valFormConsultarPrestamoArea(frm_consultarPrestamoArea){
	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;

	//Se verifica que se haya seleccionado un id de la capacitacion
	if (frm_consultarPrestamoArea.cmb_area.value==""){
		alert ("Seleccionar el �rea");
		band=0;
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
	
}

//Funcion para Evaluar los datos del formularo consultar Prestamos por Area
function valFormConsultarPrestamosEmpleado(frm_consultarPrestamosEmpleado){
	
	var band=1;
	
	// verificar que se haya ingresado el nombre del trabajador
	if (frm_consultarPrestamosEmpleado.txt_RFCEmpleado.value==""){
		alert("Seleccionar un Empleado");
		band=0;
	}
	
	if (band==1)
		return true;
	else
		return false;
}


/***************************************************************************************************************************************************************/
/************************************************************ELIMINAR PRESTAMO*******************************************************************************/
/***************************************************************************************************************************************************************/
//Funcion que valida el formulario de eliminar prestamo 
function valFormEliminarPrestamo(frm_eliminarPrestamo){
	
	var band=1;
	
	// verificar que se haya ingresado el nombre del trabajador
	if (frm_eliminarPrestamo.txt_nomEmpleado.value==""){
		alert("Seleccionar un Trabajador");
		band=0;
	}
	
	if (band==1)
		return true;
	else
		return false;
}


/*Esta funcion se ecnarga de Validar el Formulario donde se selecciona el prestamo para borrar*/
function valFormEliminarPrestamo2(frm_eliminarPrestamo2){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opci�n
	if(frm_eliminarPrestamo2.rdb_idDecuccion.length==undefined && !frm_eliminarPrestamo2.rdb_idDecuccion.checked){
		alert("Seleccionar el Pr�stamo a Borrar");
		res = 0;
	}
	
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_eliminarPrestamo2.rdb_idDecuccion.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_eliminarPrestamo2.rdb_idDecuccion.length;i++){
			if(frm_eliminarPrestamo2.rdb_idDecuccion[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar el Pr�stamo a Borrar");			
	}
	
	if (res==1){
		
		if (!confirm("�Estas Seguro que Quieres Borrar el Pr�stamo?\nToda la informaci�n relacionada se Borrar�")){
			res=0;
		}
	}
	
	if(res==1){		
		var justificacion = "";	
		//Solicitar la Justificacion del porque se esta cancelando el prestamo
		while(justificacion==null || justificacion=="Justificaci�n de la Cancelaci�n..." || justificacion==""){
			//Ciclar la Solicitud de la informaci�n hasta que el Usuario ingrese la Justificaci�n de forma correcta
			justificacion = prompt("Escribir la Justiicaci�n para Cancelar el Pr�stamo Seleccionado","Justificaci�n de la Cancelaci�n...");	
			
			//Notificar al Usuario que es necesaria la Justificaci�n para cancelar la deduccion
			if(justificacion==null)
				alert("Es Neceario Ingresar una Justificaci�n para Poder \nCancelar el Pr�stamo Selecciondo");
		}
				
		//Asignar la justificaci�n obtenida a la variable que nos ayudar� a guardarla en la BD de Recusroso
		document.getElementById("txt_justificacion").value = justificacion;		
	}
	
	if(res==1)
		return true;
	else
		return false;
}


/***********************************************************************************************************************************************************/
/***********************************************************************************************************************************************************/
/************************************************************************REPORTE ASISTENCIA*****************************************************************/
/***********************************************************************************************************************************************************/
/***********************************************************************************************************************************************************/

//Funci�n para validar el formulario de reportes de asistencia por fecha
function valFormRptAsistencia(frm_reporteFechaArea){
	//Variable que permite revisar si la validaci�n fue exitosa
	var band=1;
	//Verificamos que el combo area este definido
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_reporteFechaArea.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_reporteFechaArea.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_reporteFechaArea.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_reporteFechaArea.txt_fechaFin.value.substr(0,2);
	var finMes=frm_reporteFechaArea.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_reporteFechaArea.txt_fechaFin.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	
	if (frm_reporteFechaArea.cmb_area.value==""&&band==1){
		alert("Seleccionar �rea");
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
	
	if (band==1)
		return true;
	else
		return false;
}


//funcion para validar el formulario frm_reporteFecha cuando se selecciona la opcion por fecha
function valFormRptAsistenciaFecha(frm_reporteFecha){
	//Variable que permite revisar si la validaci�n fue exitosa
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

//Funci�n que permite conocer el numero de domingos entre dos fechas dadas
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
		//Obtenemos el a�o para mandarlo como parametro a la funcion diasMes que obtiene el numero de dias del mes; esto para saber si el a�o seleccionado es bisiesto
		var anioIni = fechaIniSeccionada[5];	
		//Obtenemos los dias que tiene el mes de inicio
		var diasMesIni = diasMes(mesIni, anioIni);
		
		//Variable que almacenara el total de domingos de los meses seleccionados
		var contDomingosMes = 0;
		
		//Seccionamos las fechas y convertimos a String
		var fechaFinSeccionada = fechaFin.toString().split(" ");
		//Contiene el dia final para establecerlo como limite de la busqueda
		var diaFin = fechaFinSeccionada[2];
		//Obtenemos el mes inicial y obtenemos el numero de mes 
		var mesFin = obtenerNumeroMes(fechaFinSeccionada[1]);
		//Obtenemos el a�o de la fecha seccionada
		var anioFin = fechaFinSeccionada[5];	
		
		
		
		//OBTENER LA CANTIDAD DE DOMINGOS DEL RANGO DE FECHAS PERTENECIENTE AL MISMO A�O
		if(iniAnio==finAnio){
			//Verificar si el Rango de fechas esta dentro del mismo mes, contar los domingos entre las fechas dadas
			if(iniMes==finMes){
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
				}		
						
			}//Cierre del ELSE de if(iniMes==finMes)	
			
			
		}//Cierre if(iniAnio==finAnio)
		
		
		//OBTENER LOS DOMINGOS DEL RANGO DE FECHAS CUANDO ABARCA A�OS DIFERENTES
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
			
			//Sumar 1 al a�o de inicio para saber si el siguiente a�o es consecutivo o no
			var anioActual = parseInt(anioIni); 
			var noAnioIni =  anioActual + 1;
			var noAnioFin = parseInt(anioFin);
			//Sumar 1 al mes de inicio para saber si el siguiente mes es consecutivo o no
			var noMesIni = mesInicial + 1;
			//Sin el mes inicial es Diciembre, entonces el valor obtenido sera 13 que equivale al primer mes del siguiente a�o (1 = Enero)
			if(noMesIni==13) noMesIni = 1;
						
					
			//Verificar que los meses no sean consecutivos, as� como los a�os, para considerar los meses intermedios
			if( (noMesIni!=mesFinal) || (noAnioIni!=noAnioFin) ){							
				
				//Obtener la cantidad de meses entre las Fechas seleccionadas, descartando el mes Inicial y el Final
				//Obtener los Meses del A�o Inicial
				var cantMeses = 12 - mesInicial;			
				
				//Obtener la diferencia de A�os, menos 1 para descartar el A�o Final
				var anios = (noAnioFin - anioActual) - 1;
				if(anios>=1)
					cantMeses += (anios*12);				
				
				//Obtener los mes del A�o Final
				cantMeses += mesFinal-1;
												
				//Obtener el mes actual
				var mesActual = mesInicial + 1;												
				
												
				//Ciclo para obtener los domingos de cada mes entre las fechas dadas
				for(var j=0;j<cantMeses;j++){															
					//Verificar el reinicio de meses y el aumento de a�o
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
			}
		
		}//Cierre del ELSE if(iniAnio==finAnio)
				
		//Pasamos el total de los domingos a la caja de texto correspondiente
		document.getElementById("domingos").value = contDomingosMes;
		
		
	}//Cierre if(fechaIni<fechaFin)
	else{
		alert("La Fecha de Inicio no Puede ser Mayor a la Fecha de Cierre");
	}
	
}

//Funci�n que permite obtener el numero de mes
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

//Funci�m que permite saber el numero de dias que contiene cada mes
function diasMes(mes, anio){
	var dias = 0;
	
	//Identificar mes y asignar numero de dias perteneciente a el
	switch(mes){
		case "01": dias = 31;	break;	
		case "02"://Verificamos si el a�o es bisiesto para enviar el numero de dias correspondiente			
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


/***********************************************************************************************************************************************************/
/***********************************************************************************************************************************************************/
/************************************************************************REPORTE INCAPACIDADES***************************************************************/
/***********************************************************************************************************************************************************/
/***********************************************************************************************************************************************************/

//Funci�n para validar el formulario de reportes de incapacidades por fecha
function valFormRptIncapacidades(frm_reporteFechaArea){
	//Variable que permite revisar si la validaci�n fue exitosa
	var band=1;
	//Verificamos que el combo area este definido
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_reporteFechaArea.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_reporteFechaArea.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_reporteFechaArea.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_reporteFechaArea.txt_fechaFin.value.substr(0,2);
	var finMes=frm_reporteFechaArea.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_reporteFechaArea.txt_fechaFin.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	
	if (frm_reporteFechaArea.cmb_area.value==""&&band==1){
		alert("Seleccionar �rea");
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
	
	if (band==1)
		return true;
	else
		return false;
}


//funcion para validar el formulario frm_reporteFecha cuando se selecciona la opcion por fecha
function valFormRptIncapacidadFecha(frm_reporteFecha){
	//Variable que permite revisar si la validaci�n fue exitosa
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



/***********************************************************************************************************************************************************/
/***********************************************************************************************************************************************************/
/************************************************************************REPORTE AUSENTISMO*****************************************************************/
/***********************************************************************************************************************************************************/
/***********************************************************************************************************************************************************/

//Funci�n para validar el formulario de reportes de ausentismo por fecha
function valFormRptAusentismo(frm_reporteFechaArea){
	//Variable que permite revisar si la validaci�n fue exitosa
	var band=1;
	//Verificamos que el combo area este definido
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_reporteFechaArea.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_reporteFechaArea.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_reporteFechaArea.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_reporteFechaArea.txt_fechaFin.value.substr(0,2);
	var finMes=frm_reporteFechaArea.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_reporteFechaArea.txt_fechaFin.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	
	if (frm_reporteFechaArea.cmb_area.value==""&&band==1){
		alert("Seleccionar �rea");
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
	
	if (band==1)
		return true;
	else
		return false;
}

//Funci�n para validar el formulario de registrar bonos
function valFormRegBonoProd(frm_regBonProd){
	//Variable que permite revisar si la validaci�n fue exitosa
	var band=1;
	//Verificamos que el combo area este definido
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_regBonProd.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_regBonProd.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_regBonProd.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_regBonProd.txt_fechaFin.value.substr(0,2);
	var finMes=frm_regBonProd.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_regBonProd.txt_fechaFin.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaI=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaF=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaI);
	fechaFin=new Date(fechaF);
	var dias = diasFechas(fechaI,fechaF);
	
	if (frm_regBonProd.cmb_con_cos.value=="" && band==1){
		alert("Seleccionar Centro de Costos");
		band=0;
	}
	
	if (frm_regBonProd.cmb_cuenta.value=="" && band==1){
		alert("Seleccionar Cuenta");
		band=0;
	}
	
	if (frm_regBonProd.txt_semana.value=="" && band==1){
		alert("Ingresar el numero de semana");
		band=0;
	}
	
	//Verificar que la fecha de inicio no sea mayor a la de cierre
	if(fechaIni>fechaFin && band==1){
		band=0;
		alert ("La Fecha de Inicio no puede ser Mayor a la Fecha de Fin");
	}
	
	if(dias>=7 && band==1){
		band=0;
		alert ("La diferencia entre fechas no puede ser mayor a 7 dias");
	}
	
	if (band==1)
		return true;
	else
		return false;
}

//Funcion utilizada para obtener la diferencia de dias entre fechas
function diasFechas(f1,f2){
	var aFecha1 = f1.split('/'); 
	var aFecha2 = f2.split('/'); 
	var fFecha1 = Date.UTC(aFecha1[2],aFecha1[0],aFecha1[1]); 
	var fFecha2 = Date.UTC(aFecha2[2],aFecha2[0],aFecha2[1]); 
	var dif = fFecha2 - fFecha1;
	var dias = Math.floor(dif / (1000 * 60 * 60 * 24)); 
	return dias;
}

//funcion para validar el formulario frm_reporteFecha cuando se selecciona la opcion por fecha
function valFormRptAusentismoFecha(frm_reporteFecha){
	//Variable que permite revisar si la validaci�n fue exitosa
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


/***********************************************************************************************************************************************************/
/***********************************************************************************************************************************************************/
/************************************************************************REPORTE RECLUTAMIENTO**************************************************************/
/***********************************************************************************************************************************************************/
/***********************************************************************************************************************************************************/

//Funci�n para validar el formulario de reportes de reclutamiento por fecha
function valFormRptReclutamiento(frm_reporteFechaArea){
	//Variable que permite revisar si la validaci�n fue exitosa
	var band=1;
	//Verificamos que el combo area este definido
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_reporteFechaArea.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_reporteFechaArea.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_reporteFechaArea.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_reporteFechaArea.txt_fechaFin.value.substr(0,2);
	var finMes=frm_reporteFechaArea.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_reporteFechaArea.txt_fechaFin.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	
	if (frm_reporteFechaArea.cmb_area.value==""&&band==1){
		alert("Seleccionar �rea");
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
	
	if (band==1)
		return true;
	else
		return false;
}


//funcion para validar el formulario frm_reporteFecha cuando se selecciona la opcion por fecha
function valFormRptReclutamientoFecha(frm_reporteFecha){
	//Variable que permite revisar si la validaci�n fue exitosa
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


/***********************************************************************************************************************************************************/
/***********************************************************************************************************************************************************/
/************************************************************************REPORTE ALTAS BAJAS****************************************************************/
/***********************************************************************************************************************************************************/
/***********************************************************************************************************************************************************/

//Funci�n para validar el formulario de reportes de altas vs Bajas por fecha
function valFormRptAltasBajasFecha(frm_reporteFechaArea){
	//Variable que permite revisar si la validaci�n fue exitosa
	var band=1;
	//Verificamos que el combo area este definido
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_reporteFechaArea.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_reporteFechaArea.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_reporteFechaArea.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_reporteFechaArea.txt_fechaFin.value.substr(0,2);
	var finMes=frm_reporteFechaArea.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_reporteFechaArea.txt_fechaFin.value.substr(6,4);		
	
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
	
	if (band==1)
		return true;
	else
		return false;
}


/***********************************************************************************************************************************************************/
/***********************************************************************************************************************************************************/
/************************************************************************REPORTE DE PRESTAMOS****************************************************************/
/***********************************************************************************************************************************************************/
/***********************************************************************************************************************************************************/
//Funci�n para validar el formulario de reportes de Prestamos que tienen registrados los empleados
function valFormRptPrestamos(frm_reportePrestamos){
	//Variable que permite revisar si la validaci�n fue exitosa
	var band=1;
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_reportePrestamos.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_reportePrestamos.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_reportePrestamos.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_reportePrestamos.txt_fechaFin.value.substr(0,2);
	var finMes=frm_reportePrestamos.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_reportePrestamos.txt_fechaFin.value.substr(6,4);		
	
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
	
	if (band==1)
		return true;
	else
		return false;
}


/***********************************************************************************************************************************************************/
/***********************************************************************************************************************************************************/
/************************************************************************REPORTE CAPACITACIONES**************************************************************/
/***********************************************************************************************************************************************************/
/***********************************************************************************************************************************************************/

//Funci�n para validar el formulario de reportes de capacitaciones por fecha
function valFormRptCapacitaciones(frm_reporteFechaArea){
	//Variable que permite revisar si la validaci�n fue exitosa
	var band=1;
	//Verificamos que el combo area este definido
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_reporteFechaArea.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_reporteFechaArea.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_reporteFechaArea.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_reporteFechaArea.txt_fechaFin.value.substr(0,2);
	var finMes=frm_reporteFechaArea.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_reporteFechaArea.txt_fechaFin.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	
	if (frm_reporteFechaArea.cmb_area.value==""&&band==1){
		alert("Seleccionar �rea");
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
	
	if (band==1)
		return true;
	else
		return false;
}


//funcion para validar el formulario frm_reporteFecha cuando se selecciona la opcion por fecha
function valFormRptCapacitacionesFecha(frm_reporteFecha){
	//Variable que permite revisar si la validaci�n fue exitosa
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


//funcion para validar el formulario frm_reporteFecha cuando se selecciona la opcion por fecha
function valFormRptCapacitacionesCap(frm_reporteCapacitacion){
	//Comparar el valor del combo de las capacitaciones
	if (frm_reporteCapacitacion.cmb_capacitaciones.value==""){
		alert("Seleccionar Capacitaci�n");
		return false;
	}
	else
		return true;	
}




/***********************************************************************************************************************************************************/
/***********************************************************************************************************************************************************/
/************************************************************************REPORTE N�MINA*********************************************************************/
/***********************************************************************************************************************************************************/
/***********************************************************************************************************************************************************/

//Funci�n para validar el formulario de reportes de nomina por fecha
function valFormRptNomina(frm_reporteFechaArea){
	//Variable que permite revisar si la validaci�n fue exitosa
	var band=1;
	//Verificamos que el combo area este definido
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_reporteFechaArea.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_reporteFechaArea.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_reporteFechaArea.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_reporteFechaArea.txt_fechaFin.value.substr(0,2);
	var finMes=frm_reporteFechaArea.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_reporteFechaArea.txt_fechaFin.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	
	if (frm_reporteFechaArea.cmb_area.value==""&&band==1){
		alert("Seleccionar �rea");
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
	
	if (band==1)
		return true;
	else
		return false;
}


//funcion para validar el formulario frm_reporteFecha cuando se selecciona la opcion por fecha
function valFormRptNominaFecha(frm_reporteFecha){
	//Variable que permite revisar si la validaci�n fue exitosa
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
	
	/*if(res==1 && frm_reporteNomina.cmb_area.value==""){
		res=0;
		alert ("Seleccionar el �rea");
	}*/
	
	if(res==1)
		return true;
	else
		return false;
}

/***********************************************************************************************************************************************************/
/***********************************************************************************************************************************************************/
/************************************************************************REPORTE PAGO SEGURO SOCIAL*********************************************************/
/***********************************************************************************************************************************************************/
/***********************************************************************************************************************************************************/

//Funci�n para validar el formulario de reportes de pago del seguro social por fecha
function valFormRptSegSocFecha(frm_reporteFecha){
	//Variable que permite revisar si la validaci�n fue exitosa
	var band=1;

	//Verificamos que el combo area este definido
	if (frm_reporteFecha.cmb_anio.value==""&&band==1){
		alert ("Seleccionar el A�o");
		band=0;
	}
	
		//Verificamos que el combo area este definido
	if (frm_reporteFecha.cmb_mes.value==""&&band==1){
		alert ("Seleccionar el Mes");
		band=0;
	}
	
		//Verificamos que el combo area este definido
	if (frm_reporteFecha.cmb_semana.value==""&&band==1){
		alert ("Seleccionar la Semana");
		band=0;
	}

	if (band==1)
		return true;
	else
		return false;
}

/***********************************************************************************************************************************************************/
/***********************************************************************************************************************************************************/
/************************************************************************REPORTE KARDEX*********************************************************************/
/***********************************************************************************************************************************************************/
/***********************************************************************************************************************************************************/

//Funci�n para validar el formulario de reportes de Kardex por fecha
function valFormRptKardexArea(frm_reporteFechaArea){
	//Variable que permite revisar si la validaci�n fue exitosa
	var band=1;
	//Verificamos que el combo area este definido
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_reporteFechaArea.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_reporteFechaArea.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_reporteFechaArea.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_reporteFechaArea.txt_fechaFin.value.substr(0,2);
	var finMes=frm_reporteFechaArea.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_reporteFechaArea.txt_fechaFin.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	
	if (frm_reporteFechaArea.cmb_area.value==""&&band==1){
		alert("Seleccionar �rea");
		band=0;
	}
	//Verificar que la fecha de inicio no sea mayor a la de cierre
	if((fechaIni>fechaFin)&&band==1){
		band=0;
		alert ("La Fecha de Inicio no puede ser Mayor a la Fecha de Fin");
	}
	if (band==1)
		return true;
	else
		return false;
	
	if (band==1)
		return true;
	else
		return false;
}


//Funci�n para validar el formulario de reportes de Kardex por fecha
function valFormRptKardexFecha(frm_reporteFecha){
	//Variable que permite revisar si la validaci�n fue exitosa
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
	if((fechaIni>fechaFin)&&band==1){
		band=0;
		alert ("La Fecha de Inicio no puede ser Mayor a la Fecha de Fin");
	}
	if (band==1)
		return true;
	else
		return false;
	
	if (band==1)
		return true;
	else
		return false;
}


//Funci�n para validar el formulario de reportes de Kardex por fecha
function valFormRptKardexFecha(frm_reporteFecha){
	//Variable que permite revisar si la validaci�n fue exitosa
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
	if((fechaIni>fechaFin)){
		band=0;
		alert ("La Fecha de Inicio no puede ser Mayor a la Fecha de Fin");
	}
	
	if (band==1)
		return true;
	else
		return false;
	
	if (band==1)
		return true;
	else
		return false;
}


//Funci�n para validar el formulario de reportes de Kardex por nombre
function valFormRptNombre(frm_consultarEmpleado){
	//Variable que permite revisar si la validaci�n fue exitosa
	var band=1;
	//Verificamos que el combo area este definido
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_consultarEmpleado.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_consultarEmpleado.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_consultarEmpleado.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_consultarEmpleado.txt_fechaFin.value.substr(0,2);
	var finMes=frm_consultarEmpleado.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_consultarEmpleado.txt_fechaFin.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	if (frm_consultarEmpleado.txt_nombre.value==""&&band==1){
		alert("Introducir Nombre del Empleado ");
		band=0;
	}
	//Verificar que la fecha de inicio no sea mayor a la de cierre
	if((fechaIni>fechaFin)&&band==1){
		band=0;
		alert ("La Fecha de Inicio no puede ser Mayor a la Fecha de Fin");
	}

	if (band==1)
		return true;
	else
		return false;
	
	if (band==1)
		return true;
	else
		return false;
}


/***************************************************************************************************************************************************************/
/************************************************************ELIMINAR DEDUCCION*******************************************************************************/
/***************************************************************************************************************************************************************/
//Funcion que valida el formulario de eliminar deduccion 
function valFormEliminarDeduccion(frm_eliminarDeduccion){
	
	var band=1;
	
	// verificar que se haya ingresado el nombre del trabajador
	if (frm_eliminarDeduccion.txt_nomEmpleado.value==""){
		alert("Seleccionar un Trabajador");
		band=0;
	}
	
	if (band==1)
		return true;
	else
		return false;
}


/*Esta funcion se ecnarga de Validar el Formulario donde se selecciona la deduccion para borrar*/
function valFormEliminarDeduccionSel(frm_eliminarDeduccionSel){
	//Si el valor de la variable "res" se mantiene en 1, entonces el formulario paso el proceso de validacion
	var res = 1;
		
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opci�n
	if(frm_eliminarDeduccionSel.rdb_idDecuccion.length==undefined && !frm_eliminarDeduccionSel.rdb_idDecuccion.checked){
		alert("Seleccionar la Deducci�n a Borrar");
		res = 0;
	}
	
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_eliminarDeduccionSel.rdb_idDecuccion.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		res = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_eliminarDeduccionSel.rdb_idDecuccion.length;i++){
			if(frm_eliminarDeduccionSel.rdb_idDecuccion[i].checked)
				res = 1;
		}
		if(res==0)
			alert("Seleccionar la Deducci�n a Borrar");			
	}
	
	if (res==1){
		
		if (!confirm("�Estas Seguro que Quieres Borrar la Deducci�n?\nToda la informaci�n relacionada se Borrar�")){
			res=0;
		}
	}
	
	if(res==1){		
		var justificacion = "";	
		//Solicitar la Justificacion del porque se esta cancelando la Deduccion
		while(justificacion==null || justificacion=="Justificaci�n de la Cancelaci�n..." || justificacion==""){
			//Ciclar la Solicitud de la informaci�n hasta que el Usuario ingrese la Justificaci�n de forma correcta
			justificacion = prompt("Escribir la Justiicaci�n para Cancelar la Deducci�n Seleccionada","Justificaci�n de la Cancelaci�n...");	
			
			//Notificar al Usuario que es necesaria la Justificaci�n para cancelar la deduccion
			if(justificacion==null)
				alert("Es Neceario Ingresar una Justificaci�n para Poder \nCancelar la Deducci�n Seleccionda");
		}
				
		//Asignar la justificaci�n obtenida a la variable que nos ayudar� a guardarla en la BD de Recusroso
		document.getElementById("txt_justificacion").value = justificacion;		
	}
	
	if(res==1)
		return true;
	else
		return false;
}

/***************************************************************************************************************************************************************/
/************************************************************CONSULTAR  DEDUCCION*******************************************************************************/
/***************************************************************************************************************************************************************/
//Funcion para Evaluar los datoas del formularo consultar deducciones
function valFormconsultarDeduccionesFecha(frm_consultarDeduccionesFecha){

	//Variable para controlar la evaluacion del formulario, si permanece en 1, la validacion fue exitosa
	var band=1;


	if(!validarFechas(frm_consultarDeduccionesFecha.txt_fechaIni.value,frm_consultarDeduccionesFecha.txt_fechaFin.value))
		band = 0;				
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

//Funcion que valida el formulario de consultar deducciones 
function valFormConsultarDeduccionesNom(frm_consultarDeduccionesNom){
	
	var band=1;
	
	// verificar que se haya ingresado el nombre del trabajador
	if (frm_consultarDeduccionesNom.txt_nomEmpleado.value==""){
		alert("Seleccionar un Trabajador");
		band=0;
	}
	
	if (band==1)
		return true;
	else
		return false;
}

/************************************************************************************************************************************************************/
/************************************************************AGREGAR  DEDUCCION   **************************************************************************/
/************************************************************************************************************************************************************/
/*Esta funcion valida el formulario de agregar deduccion de la pagina frm_agregarDeduccion.php*/
function valFormAgregarDeduccion(frm_agregarDeduccion){
	//Si el valor de band permanece en 1, el proceso de validaci�n fue satisfactorio
	var band = 1;
	
	//Revisar que el nombre del empleado haya sido ingresado
	if(frm_agregarDeduccion.txt_nomEmpleado.value==""){
		alert("Seleccionar el Empleado al que Registr� la Deducci�n");
		band = 0;
	}
	
	if (frm_agregarDeduccion.txt_RFCEmpleado.value==""  && band==1){
		alert("A La Persona Seleccionada no Puede Agregarle Deducci�n");
		band=0;
	}
	
	if (!frm_agregarDeduccion.ckb_nuevaDed.checked && frm_agregarDeduccion.cmb_tipoDeduccion.value==""  && band==1){
		alert("Ingresar el Nombre de la Deducci�n");
		band=0;
	}

	//Revisar que la cantidad haya sido ingresado
	if(frm_agregarDeduccion.txt_totalDed.value==0 && band==1){
		alert("Ingresar la Cantidad de la Deducci�n");
		band = 0;
	}

	if(band==1){
		if(!validarEntero(frm_agregarDeduccion.txt_totalDed.value.replace(/,/g,'')))
			band = 0;		
	}
	
	//Revisar la clave de la deduccion
	if(frm_agregarDeduccion.txt_claveDed.value=="" && band==1){
		alert("Ingresar la Clave de la Deducci�n");
		band = 0;
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}

/************************************************************************************************************************************************************/
/************************************************************ REGISTRAR  ABONO ******************************************************************************/
/************************************************************************************************************************************************************/
/*Esta funcion valida el formulario de agregar deduccion de la pagina frm_agregarDeduccion.php*/
function valFormRegAbonos(frm_registrarAbonos){
	//Si el valor de band permanece en 1, el proceso de validaci�n fue satisfactorio
	var band = 1;
			
	//Revisar que sea seleccionado un empleado.
	if(frm_registrarAbonos.cmb_idDeduccion.value==""){
		alert("Seleccionar el Empleado al Cual se le va a Registrar el Abono");
		band = 0;
	}
	
	//Revisar que el id de deduccion haya sido seleccioado
	if(frm_registrarAbonos.txt_abono.value==""  && band==1){
		alert("Ingresar la Cantidad del Abono");
		band = 0;
	}
	
	//Verificar que la fecha de registro del Abono no sea menor a la fecha de alta del prestamo o la fecha
	if(band==1){
		//Obtener la fecha de Registro del Ultimo Abono o la fecha de registro del pr�stamo en formato (aaaa-mm-dd)
		var pFechaRegistro = document.getElementById("txt_fechaRegPrestamo").value.split("/");
		//Obtener la fecha de Registro del abono en formato (dd/mm/aaaa)
		var pFechaRegAbono = document.getElementById("txt_fechaAbono").value.split("/");
						
		//Unir los datos para crear la cadena de Fecha leida por Javascript (a�o, mes, dia)
 		var fechaRegPrestamo = new Date(pFechaRegistro[2],pFechaRegistro[1],pFechaRegistro[0]);
		var fechaRegAbono = new Date(pFechaRegAbono[2],pFechaRegAbono[1],pFechaRegAbono[0]);				
		
		if(fechaRegAbono<fechaRegPrestamo){
			alert("La Fecha de Registro del Abono no Puede ser Anterior a la \nFecha de Registro del Pr�stamo o del �ltimo Abono Registrado del Pr�stamo");
			band = 0;
		}
		
	}//Cierre if(band==1)
		

	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if(band==1)
		return true;
	else
		return false;
		
}//Cierre de la funci�n valFormRegAbonos(frm_registrarAbonos)


//Esta funci�n resta el abono ingresado al saldo actual
function restarAbono(abono){	
	//Verificar que el abono sea diferente de vac�o
	if(abono!="" && abono!="0.00"){
		//Quitar la coma y convertir a numero el valor del abono
		var cantAbono = parseFloat(abono.replace(/,/g,''));				
		//Obtener el valor del Saldo Actual
		var sActual = document.getElementById("txt_saldoActual").value;
		
		//Verificar que el Saldo Actual sea diferente de vac�o
		if(sActual!=""){
			var saldoActual = parseFloat(sActual.replace(/,/g,''));
			
			//Verificar que la cantidad del Abono no exceda el total del pr�stamo
			if(cantAbono>saldoActual){
				alert("EL Abono no Puede Ser Mayor a al Saldo Actual del Pr�stamo");
				document.getElementById("txt_abono").value = "";
			}
			else {
				var nuevoSaldo = saldoActual - cantAbono;			
				//Colocar el nuevo saldo en la caja de texto correspondiente
				formatCurrency(nuevoSaldo, 'txt_nuevoSaldo');
			}
		}//Cierre if(sActual!="")		
	
	}//Cierre if(abono!="" && abono!="0.00")
	else{
		//Cuando no exista abono, borrar los datos de Nuevo Saldo y abono
		document.getElementById("txt_abono").value = "";
		document.getElementById("txt_nuevoSaldo").value = "";
	}
	
	
}//Cierre restarAbono(abono)

/**********************************************************************************************************************************************************/
/**************************************************ACTUALIZACIONES DEL CATALOGO DE TURNOS******************************************************************/
/**********************************************************************************************************************************************************/
/*Esta funci�n valida que sea selecionada un Nuevo Puesto*/
function agregarNuevoTurno(){
	if (document.getElementById("ckb_nuevoTurno").checked){
		//Recoger el nombre del puesto para el �rea
		var turno = prompt("�Nombre del Nuevo Turno de Trabajo?","Nombre del Turno...");
		if(turno!=null && turno!="Nombre del Turno..." && turno!="" && turno.length<=30){
			turno=turno.toUpperCase();
			//Variable que permite verificar si existe un dato o no en el combo de referencia
			var existe=0;
			for(i=0; i<document.getElementById("cmb_turnos").length; i++){
				//verificar que la nueva opcion no se encuentre dentro de las opciones actuales del combo
				if(document.getElementById("cmb_turnos").options[i].value==turno)
					existe = 1;
			} //FIN for(i=0; i<comboBox.length; i++)
			if (existe==1){
				alert("El Turno ya existe");
				document.getElementById("cmb_turnos").value=turno;
				//Dechecar el check de Nuevo Puesto
				document.getElementById("ckb_nuevoTurno").checked = false;
				cargarTurno(turno);
			}
			else{
				//Asignar el valor obtenido a la caja de texto que lo mostrara
				document.getElementById("txt_nuevoTurno").value = turno;
				//Deshabilitar el ComboBox para que el usuario no los pueda modificar
				document.getElementById("cmb_turnos").disabled = true;
				//Restablecer el valor del campo Accion a Agregar
				document.getElementById("hdn_estado").value="Agregar";
				//Deshabilitar el Boton de Eliminar
				document.getElementById("btn_eliminar").disabled=true;
				document.getElementById("btn_eliminar").title="Solo se puede Eliminar un Turno de la Lista de Turnos";
			}
		}
		else{
			if(turno!=null && turno.length>30)
				alert("La Longitud del Turno, No puede ser Mayor a 30 Caracteres");
			else
				alert("Dato Ingresado No V�lido");
			document.getElementById("ckb_nuevoTurno").checked = false;
		}
	}
	else{
		//Asignar el valor obtenido a la caja de texto que lo mostrara
		document.getElementById("txt_nuevoTurno").value = "";
		//Habilitar el combo de Turnos
		document.getElementById("cmb_turnos").disabled = false;
		//Habilitar el Boton de Eliminar
		document.getElementById("btn_eliminar").disabled=false;
		document.getElementById("btn_eliminar").title="Borrar el Turno Seleccionado en el Combo";
	}
}

function valFormCatalogoTurnos(frm_catalogoTurnos){
	var band=1;
	
	if (frm_catalogoTurnos.cmb_turnos.value=="" && frm_catalogoTurnos.txt_nuevoTurno.value==""){
		alert("Seleccionar un Turno Existente o Ingresar uno Nuevo");
		band=0;
	}

	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)	
		return true;
	else
		return false;
}

function borrarTurno(){
	if (confirm("�Advertencia!\n Si Borra el Turno, se Borrar� el Registro de los Trabajadores en dicho Turno. �Desea Continuar?")){
		var reg=document.getElementById("cmb_turnos").value;
		location.href='frm_catalogoTurnos.php?borrar='+reg;
	}
}

function activarBotonBorrarTurno(valor){
	if(valor!=""){
		//Habilitar el Boton de Eliminar
		document.getElementById("btn_eliminar").disabled=false;
		document.getElementById("btn_eliminar").title="Borrar el Turno Seleccionado en el Combo";
	}
	else{
		document.getElementById("btn_eliminar").disabled=true;
		document.getElementById("btn_eliminar").title="Seleccione un Turno de la Lista para poder Borrarlo";
	}
}

/**********************************************************************************************************************************************************/
/*************************************************ACTUALIZACIONES DE ASIGNACION DE TURNOS******************************************************************/
/**********************************************************************************************************************************************************/
//Funcion que activa los combos respondientes a los check seleccionados
function activarComboTurnos(check,combo){
	if (check.checked)
		combo.disabled=false;
	else{
		//Encontrar el valor por defecto del combo
		var pos=0;
		for(var i=0;i<combo.length;i++){
			if (combo.options[i].defaultSelected){
				pos=i;
				break;
			}
		}
		combo.value=combo.options[pos].value;
		combo.disabled=true;
	}
}

function valFormAsignacionRoles(frm_asignarRoles){
	var band=1;
	cant=frm_asignarRoles.hdn_cantidad.value;
	cant--;
	var registros=0;
	for (i=1;i<=cant;i++){
		if (document.getElementById("ckb_seleccionar"+i).checked){
			if (document.getElementById("cmb_turno"+i).value==""){
				var nombre=document.getElementById("hdn_nombre"+i).value;
				alert("Asignar Turno al Trabajador:\n"+nombre);
				band=0;
				break;
			}
		}
		else{
			registros++;
		}
	}

	//Si registros es igual a cant, significa que no se ha seleccionado ningun trabajador
	if (registros==cant && band==1){
		alert("Seleccionar un Trabajador");
		band=0;
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)	
		return true;
	else
		return false;
}

/************************************************************************************************************************************************************/
/******************************************************VALIDAR FORMULARIO DE BORRADO DE CAPACITADOS**********************************************************/
/************************************************************************************************************************************************************/
function valFormBorrarCapacitados(frm_detalleCapacitados){
	var band=1;
	
	if(frm_detalleCapacitados.hdn_validar.value=="si"){
		//Obtener la cantidad check que existen
		cant=frm_detalleCapacitados.hdn_cant.value;
		//Variable que checa que se haya seleccionado un trabajador
		var flag=0;
		//Recorrer los elementos check
		for (i=1;i<=cant;i++){
			if (document.getElementById("ckb_selEmpleado"+i).checked){
				flag=1;
				break;
			}
		}
		//Si no se activo la bandera, mostrar msje de error
		if (flag==0){
			alert("Seleccionar el Trabajador a Eliminar");
			band=0;
		}
	}
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)	
		return true;
	else
		return false;
}