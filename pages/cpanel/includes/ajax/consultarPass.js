/**
 * Nombre del M�dulo: Panel de Control                                               
 * �Concreto Lanzado de Fresnillo S.A. de C.V.
 * Fecha: 03/Mayo/2012
 * Descripci�n: Este archivo contiene las funciones para validar las claves de los datos que ser�n registrados en la BD de manera Asincrona y de ese modo indicar al usuario cuando una
 * clave esta repetida en la BD antes de que envie los datos para su registro.
 */

var READY_STATE_UNINITIALIZED = 0;
var READY_STATE_LOADING = 1;
var READY_STATE_LOADED = 2;
var READY_STATE_INTERACTIVE = 3;
var READY_STATE_COMPLETE = 4;
//Guardar la Petici�n HTTP para validar los Dato que se quieren guardar en la BD
var peticion_http_usuario;


/*Esta funci�n obtendr� el dato que se quiere validar*/
function consultarUsuario(user) {
	if (user != "") {
		//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript(validarDatoBD.js)
		var url = "includes/ajax/consultarPass.php?user=" + user;
		/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. Como cada petici�n
		 *variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al servidor y no utilizar su cache*/
		url += "&nocache=" + Math.random();
		//Hacer la Peticion al servidor de forma Asincrona
		cargaContenidoUsuario(url, "GET", procesarRespuestaUser);
	} else
		alert("Seleccionar un Usuario");
} //Fin de la Funcion verificarDatoBD(campo)		


/*Procesar la respuesta del servidor y obtener los resultados de la petici�n*/
function procesarRespuestaUser() {
	//Verificar que la peticion HTTP se haya realizado correctamente
	if (peticion_http_usuario.readyState == READY_STATE_COMPLETE) {
		if (peticion_http_usuario.status == 200) {
			//Recuperar la respuesta del Servidor
			respuesta = peticion_http_usuario.responseXML;
			var tabla = respuesta.getElementsByTagName("tabla").item(0).firstChild.data;
			//Se remplazan todas las apariciones del caracter separador por el caracter <
			var tablaMod = tabla.replace(/�/g, "<");
			document.getElementById("tabla-resultados").style.visibility = 'visible';
			document.getElementById("tabla-resultados").innerHTML = tablaMod;
		} //If if(peticion_http_password.status==200)
	} //If if(peticion_http_password.readyState==READY_STATE_COMPLETE)
} //Fin de la Funcion procesarRespuestaVal()

/*Funcion para verificar el Password del Panel*/
function verificarPasswordCP(pass) {
	if (pass.value != "") {
		//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript(validarDatoBD.js)
		var url = "includes/ajax/consultarPass.php?pass=" + pass.value;
		/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. Como cada petici�n
		 *variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al servidor y no utilizar su cache*/
		url += "&nocache=" + Math.random();
		//Hacer la Peticion al servidor de forma Asincrona
		cargaContenidoUsuario(url, "GET", procesarRespuestaPassCP);
	} else
		alert("Ingresar la Contrase�a del Administrador del Panel de Control");
}

function procesarRespuestaPassCP() {
	//Verificar que la peticion HTTP se haya realizado correctamente
	if (peticion_http_usuario.readyState == READY_STATE_COMPLETE) {
		if (peticion_http_usuario.status == 200) {
			//Recuperar la respuesta del Servidor
			respuesta = peticion_http_usuario.responseXML;
			//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
			var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
			if (existe == "true") {
				document.getElementById("tablaconsultarUsuario").innerHTML = "<legend class=\"titulo_etiqueta\">Seleccionar Usuario</legend><br><table width=\"100%\" cellpadding=\"5\" cellspacing=\"5\" class=\"tabla_frm\"><tr><td width=\"35%\"><div align=\"right\">M&oacute;dulo</div></td><td width=\"65%\"><select name=\"cmb_depto\" id=\"cmb_depto\" size=\"1\" class=\"combo_box\" onchange=\"cargarCombo(this.value);\"><option value=\"\" selected=\"selected\">Departamento</option><option value=\"Almacen\">ALMACEN</option><option value=\"Calidad\">ASEGURAMIENTO CALIDAD</option><option value=\"Compras\">COMPRAS</option><option value=\"Desarrollo\">DESARROLLO</option><option value=\"GerenciaTecnica\">GERENCIA TECNICA</option><option value=\"Laboratorio\">LABORATORIO</option><option value=\"Lampisteria\">LAMPISTERIA</option><option value=\"MttoConcreto\">MANTENIMIENTO CONCRETO</option><option value=\"MttoMina\">MANTENIMIENTO MINA</option><option value=\"MttoElectrico\">MANTENIMIENTO EL&Eacute;CTRICO</option><option value=\"Paileria\">PAILERIA</option><option value=\"Produccion\">PRODUCCION</option><option value=\"RecursosHumanos\">RECURSOS HUMANOS</option><option value=\"SeguridadAmbiental\">SEGURIDAD AMBIENTAL</option><option value=\"Seguridad\">SEGURIDAD INDUSTRIAL</option><option value=\"Topografia\">TOPOGRAFIA</option><option value=\"Clinica\">UNIDAD DE SALUD OCUPACIONAL</option><option value=\"Comaro\">COMARO</option><option value=\"Sistemas\">SISTEMAS</option><option value=\"SupervisionDes\">SUPERVISION DESARROLLO</option></select></td></tr><tr><td><div align=\"right\">Nombre de Usuario</div></td><td><select name=\"cmb_usuario\" id=\"cmb_usuario\"><option value=\"\">Usuario</option></select></td></tr><tr><td colspan=\"2\"><div align=\"center\"><input name=\"btn_continuar\" type=\"button\" class=\"botones\" value=\"Continuar\" title=\"Verificar datos del Usuario\" onmouseover=\"window.status='';return true\" onclick=\"consultarUsuario(cmb_usuario.value)\"/>&nbsp;&nbsp;&nbsp;&nbsp;<input name=\"btn_regresar\" type=\"button\" class=\"botones\" value=\"Regresar\" title=\"Regresar al Inicio\" onMouseOver=\"window.status='';return true\" onclick=\"location.href='frm_consultarPassword.php';\" /></div></td></tr></table>";
			} else {
				alert("La Contrase�a Introducida No Corresponde con la Actual");
			}
		} //If if(peticion_http_password.status==200)
	} //If if(peticion_http_password.readyState==READY_STATE_COMPLETE)
}

/*Esta funci�n recibe 3 par�metros: la URL del contenido que se va a cargar, el m�todo HTTP mediante el que se carga y una referencia a la funci�n que procesa la respuesta
 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
function cargaContenidoUsuario(url, metodo, funcion) {
	peticion_http_usuario = inicializa_xhr_usuario();
	if (peticion_http_usuario) {
		peticion_http_usuario.onreadystatechange = funcion;
		peticion_http_usuario.open(metodo, url, true);
		peticion_http_usuario.send(null);
	}
}

/*Esta funcion encapsula la creaci�n del objeto XMLHttpRequest*/
function inicializa_xhr_usuario() {
	if (window.XMLHttpRequest) { // Mozilla, Safari, ...
		return new XMLHttpRequest();
	} else if (window.ActiveXObject) { // IE
		return new ActiveXObject("Microsoft.XMLHTTP");
	}
}