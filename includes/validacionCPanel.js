/**
  * Nombre del Módulo: Panel de Control
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 15/Agosto/2011                                      			
  * Descripción: Este archivo contiene funciones para validar los diferentes formularios del Módulo Panel de Control
  */
/*****************************************************************************************************************************************************************************************/
/************************************************************************VALIDAR CARACTERES***********************************************************************************************/
/*****************************************************************************************************************************************************************************************/
/*Esta función se encarga de que el usuario no pueda ingresar caracteres invalidos en los campos de los diferentes formulario del Módulo de Almacén*/
function permite(elEvento, permitidos) {
	//Variables que definen los caracteres permitidos
	var numeros = "0123456789";
	var caracteres = " abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ";
	var numeros_caracteres = numeros + caracteres;
	var teclas_especiales = [8, 34, 37, 44, 45, 46, 47];//8 = BackSpace, 34 = Comillas Dobles, 37 = Signo Porcentaje, 44 = Coma, 45 = Guion medio, 46 = Punto, 47 = Diagonal
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

/*********************************************************************************************************************************************************************************/
/******************************************************************AGREGAR USUARIOS***********************************************************************************************/
/*********************************************************************************************************************************************************************************/
//Funcion que valida los datos ingresados en el formulario de Usuarios
function valFormUsuarios(frm_agregarUsuario){
	//Variable para controlar la validacion
	var band=1;
	
	if(frm_agregarUsuario.txt_nombre.value==""){
		alert("Introducir el Nombre del Trabajador al que se le Asigna el Usuario");
		band=0;
	}
	
	if(frm_agregarUsuario.txt_usuario.value=="" && band==1){
		alert("Introducir el Nombre de Usuario");
		band=0;
	}
	
	if(frm_agregarUsuario.txt_pass.value=="" && band==1){
		alert("Introducir la Contraseña de Usuario");
		band=0;
	}
	
	if(frm_agregarUsuario.txt_passConfirm.value=="" && band==1){
		alert("Introducir la Confirmación de Contraseña de Usuario");
		band=0;
	}
	
	if(frm_agregarUsuario.cmb_tipo.value=="" && band==1){
		alert("Seleccionar el Tipo de Usuario");
		band=0;
	}
	
	if(frm_agregarUsuario.cmb_depto.value=="" && band==1){
		alert("Seleccionar el Departamento al que Pertenece el Usuario");
		band=0;
	}
	
	if (band==1 && frm_agregarUsuario.hdn_fortaleza.value=="b"){
		if(!confirm("La Fortaleza de la Contraseña es Baja.\n¿Desea Continuar?"))
		band=0;
	}
	
	if (band==1 && frm_agregarUsuario.hdn_fortaleza.value=="m"){
		if(!confirm("La Fortaleza de la Contraseña es Media.\n¿Desea Continuar?"))
			band=0;
	}
	
	if(band==1)
		return true;
	else
		return false;
}

//Funciones que validan las contraseñas introducidas y su fortaleza
function validarPass(pass1,pass2){
	if(pass1.value!=pass2.value && pass1.value!=""){
		alert("Las Contraseñas Introducidas NO Coinciden");
		pass2.value="";
	}
}

function validarFortaleza(pass){
	if (pass.value.length<6){
		alert("La Contraseña Debe Tener por lo Menos 6 Caractéres");
		pass.value="";
		document.getElementById("fortaleza").innerHTML ="";
	}
	else{
		var largo=pass.value.length;
		var cont=0;
		//Arreglo con los numeros
		var numeros=["0","1","2","3","4","5","6","7","8","9"];
		//Contador de la cantidad de Numeros
		var fortalezaN=0;
		//Arreglo con las Mayusculas
		var mayusculas=["A","B","C","D","E","F","G","H","I","J","K","L","M","N","Ñ","O","P","Q","R","S","T","U","V","W","X","Y","Z"];
		//Contador de la cantidad de Mayusculas
		var fortalezaMa=0;
		//Arreglo con las Minusculas
		var minusculas=["a","b","c","d","e","f","g","h","i","j","k","l","m","n","ñ","o","p","q","r","s","t","u","v","w","x","y","z"];
		//Contador de la cantidad de Minusuculas
		var fortalezaMi=0;
		//Contador de la cantidad de Caracteres Especiales
		var fortalezaE=0;
		
		while(cont<largo){
			//Variables para verificar caracteres especiales
			var banderaN=fortalezaN;
			var banderaMa=fortalezaMa;
			var banderaMi=fortalezaMi;
			
			//Contador para evaluar con cada arreglo
			var contador=0;
			//Verificar si hay numeros en la contraseña
			while(contador<numeros.length){
				if (pass.value.charAt(cont)==numeros[contador]){
					fortalezaN++;
					break;
				}
				contador++;
			}
			
			//Restablecer el contador a 0
			contador=0;
			//Verificar si hay mayusculas en la contraseña
			while(contador<mayusculas.length){
				if (pass.value.charAt(cont)==mayusculas[contador]){
					fortalezaMa++;
					break;
				}
				contador++;
			}
			
			//Restablecer el contador a 0
			contador=0;
			//Verificar si hay minusculas en la contraseña
			while(contador<minusculas.length){
				if (pass.value.charAt(cont)==minusculas[contador]){
					fortalezaMi++;
					break;
				}
				contador++;
			}
			//Si los arreglos no incrementaron su tamaño, quiere decir que es un caracter especial
			//incrementar el arreglo correspondiente
			if (banderaN==fortalezaN && banderaMa==fortalezaMa && banderaMi==fortalezaMi)
				fortalezaE++;
			//Incrementar el contador de la palabra
			cont++;
		}
		//Verificar si cada contador de arreglo es mayor a 0, en dicho caso
		//dibujar la imagen de fortaleza alta
		if (fortalezaN>0 && fortalezaMa>0 && fortalezaMi>0 &&fortalezaE>0){
			document.getElementById("fortaleza").innerHTML ="<img src='images/fort-alta.png' width='47' height='15' border=0 title='Fortaleza Alta'>";
			document.getElementById("hdn_fortaleza").value="a";
		}
		else{
			//Variable para identificar si la fortaleza es baja o media
			band=0;
			/*Segmento para verificar si la fortaleza es baja, esto comprobando si solo un contador es mayor a 0
			y verificando que todos los demas sean igual a 0
			*/
			if (fortalezaN>0 && fortalezaMa==0 && fortalezaMi==0 &&fortalezaE==0){
				band=1;
			}
			if (fortalezaN==0 && fortalezaMa>0 && fortalezaMi==0 &&fortalezaE==0){
				band=1;
			}
			if (fortalezaN==0 && fortalezaMa==0 && fortalezaMi>0 &&fortalezaE==0){
				band=1;
			}
			if (fortalezaN==0 && fortalezaMa==0 && fortalezaMi==0 &&fortalezaE>0){
				band=1;
			}
			//Si band es igual a 1, quiere decir que la fortaleza es baja
			if (band==1){
				document.getElementById("fortaleza").innerHTML ="<img src='images/fort-baja.png' width='15' height='15' border=0 title='Fortaleza Baja'>";
				document.getElementById("hdn_fortaleza").value="b";
			}
			//Si band es igual a 0, la fortaleza es media
			else{
				document.getElementById("fortaleza").innerHTML ="<img src='images/fort-media.png' width='30' height='15' border=0 title='Fortaleza Media'>";
				document.getElementById("hdn_fortaleza").value="m";
			}
		}
	}
}

/*********************************************************************************************************************************************************************************/
/*******************************************************************BORRAR USUARIOS***********************************************************************************************/
/*********************************************************************************************************************************************************************************/
//Funcion que valida los datos seleccionados en el formulario de Borrar Usuarios
function valFormBorrarUsuarios(frm_borrarUsuario){
	//Variable para controlar la validacion
	var band=1;
	
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_borrarUsuario.rdb_usuario.length==undefined && !frm_borrarUsuario.rdb_usuario.checked){
		alert("Seleccionar el Usuario a Borrar");
		band = 0;
	}
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_borrarUsuario.rdb_usuario.length>=2){
		//Colocar 0 a la variable "res" suponiendo que ningun elemento fue seleccionado
		band = 0; 
		//Si algun valor fue seleccionado la variable "res" cambiara su estado a 1
		for(i=0;i<frm_borrarUsuario.rdb_usuario.length;i++){
			if(frm_borrarUsuario.rdb_usuario[i].checked){
				band = 1;
				var usuario=frm_borrarUsuario.rdb_usuario[i].value;
			}
		}
		if(band==0)
			alert("Seleccionar el Usuario a Borrar");			
	}
	
	if (band==1){
		if(!confirm("Esta Seguro que Desea Borrar al Usuario "+usuario+"?"))
			band=0;
		else{
			if(!confirm("De Borrar al Usuario, se Perderá todo Acceso al Sistema por parte del Mismo, Una Vez Borrado No Hay Marcha Atrás.\n¿Seguro que Desea Borrarlo del Sistema?"))
				band=0;
		}
	}
	
	if(band==1)
		return true;
	else
		return false;
}

/*********************************************************************************************************************************************************************************/
/******************************************************************MODIFICAR USUARIOS*********************************************************************************************/
/*********************************************************************************************************************************************************************************/
//Funcion que valida los datos seleccionados en el formulario de Modificar Usuarios
function valFormModificarUsuarios(frm_modificarUsuario){
	//Variable para controlar la validacion
	var band=1;
	
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene una sola opción
	if(frm_modificarUsuario.rdb_usuario.length==undefined && !frm_modificarUsuario.rdb_usuario.checked){
		alert("Seleccionar el Usuario a Modificar");
		band = 0;
	}
	
	//Verificar que un elemeto haya sido seleccionado cuando el RadioButton tiene dos o mas opciones
	if(frm_modificarUsuario.rdb_usuario.length>=2){
		//Colocar 0 a la variable "band" suponiendo que ningun elemento fue seleccionado
		band = 0; 
		//Si algun valor fue seleccionado la variable "band" cambiara su estado a 1
		for(i=0;i<frm_modificarUsuario.rdb_usuario.length;i++){
			if(frm_modificarUsuario.rdb_usuario[i].checked){
				band = 1;
			}
		}
		if(band==0)
			alert("Seleccionar el Usuario a Modificar");			
	}
	
	if(band==1)
		return true;
	else
		return false;
}


/*********************************************************************************************************************************************************************************/
/*******************************************************************CONSULTAR BITACORA********************************************************************************************/
/*********************************************************************************************************************************************************************************/
//Funcion que valida los datos seleccionados en el formulario de Consultar Bitacora
function valFormConsultarBitacora(frm_consultarBitacora){
	//Variable para controlar la validacion
	var band=1;
	
	if (frm_consultarBitacora.cmb_modulo.value==""){
		alert("Seleccionar Módulo a Consultar");
		band=0;
	}
	
	if(band==1){
		//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
		var iniDia=frm_consultarBitacora.txt_fechaIni.value.substr(0,2);
		var iniMes=frm_consultarBitacora.txt_fechaIni.value.substr(3,2);
		var iniAnio=frm_consultarBitacora.txt_fechaIni.value.substr(6,4);
		
		//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
		var finDia=frm_consultarBitacora.txt_fechaFin.value.substr(0,2);
		var finMes=frm_consultarBitacora.txt_fechaFin.value.substr(3,2);
		var finAnio=frm_consultarBitacora.txt_fechaFin.value.substr(6,4);		
		
		//Unir los datos para crear la cadena de Fecha leida por Javascript
		var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
		var fechaFin=finMes+"/"+finDia+"/"+finAnio;
		
		//Convertir la cadena a formato valido para JS
		fechaIni=new Date(fechaIni);
		fechaFin=new Date(fechaFin);
	
		//Verificar que el año de Fin sea mayor al de Inicio
		if(fechaIni>fechaFin){
			band=0;
			alert ("La Fecha de Inicio no Puede ser Mayor a la Fecha de Fin");
		}
	}
	
	if(band==1)
		return true;
	else
		return false;
}

/*********************************************************************************************************************************************************************************/
/*******************************************************************MODIFICAR PASSWORD********************************************************************************************/
/*********************************************************************************************************************************************************************************/
//Funcion que valida los datos ingresados en el formulario de modificar Password
function valFormPassword(frm_modificarPassword){
	//Variable para controlar la validacion
	var band=1;
	
	if (frm_modificarPassword.txt_passAct.value==""){
		alert("Ingresar la Contraseña Actual");
		band=0;
	}
	
	if (frm_modificarPassword.txt_pass.value=="" && band==1){
		alert("Ingresar la Nueva Contraseña");
		band=0;
	}
	
	if (frm_modificarPassword.txt_passConfirm.value=="" && band==1){
		alert("Ingresar la Confirmación de Contraseña");
		band=0;
	}
	
	if (frm_modificarPassword.hdn_claveValida.value=="no" && band==1){
		alert("La Contraseña Introducida No Corresponde con la Actual");
		band=0;
	}
	
	if (band==1 && frm_modificarPassword.hdn_fortaleza.value=="b"){
		alert("La Fortaleza de la Contraseña es Baja. Ingrese Otra");
		band=0;
	}
	
	if (band==1 && frm_modificarPassword.hdn_fortaleza.value=="m"){
		if(!confirm("La Fortaleza de la Contraseña es Media.\n¿Desea Continuar?"))
			band=0;
	}
	
	if(band==1)
		return true;
	else
		return false;
}


/*********************************************************************************************************************************************************************************/
/******************************************************************CONSULTA DE PERMISOS*******************************************************************************************/
/*********************************************************************************************************************************************************************************/
function valFormConsultarPermisos(frm_consultarPermisos){
	//Variable para controlar la validacion
	var band=1;
	
	if (frm_consultarPermisos.cmb_depto.value==""){
		alert("Seleccionar un Módulo del sistema");
		band=0;
	}
	
	if (frm_consultarPermisos.cmb_usuario.value=="" && band==1){
		alert("Seleccionar un Usuario para Verificar sus Permisos");
		band=0;
	}
	
	if(band==1)
		return true;
	else
		return false;
}

/*********************************************************************************************************************************************************************************/
/********************************************************************MODIFICAR PERMISOS*******************************************************************************************/
/*********************************************************************************************************************************************************************************/

function desbloquearAcceso(imagen){
	var archivo=imagen.name;
	archivo=archivo.replace("img","",archivo);
	document.getElementById("hdn_seccion").value=archivo;
	//Indicar que la accion que se tomara sera un permiso a una seccion del modulo
	document.getElementById("hdn_accion").value="desbloqueo";
	document.frm_modificarPermisos.submit();
}

function bloquearAcceso(imagen){
	var archivo=imagen.name;
	archivo=archivo.replace("img","",archivo);
	document.getElementById("hdn_seccion").value=archivo;
	//Indicar que la accion que se tomara sera quitar un permiso a una seccion del modulo
	document.getElementById("hdn_accion").value="bloqueo";
	document.frm_modificarPermisos.submit();
}