/**
  * Nombre del M�dulo: Unidad de Salud Ocupacional                                             
  * �Concreto Lanzado de Fresnillo MARCA 
  * Fecha: 21/Marzo/2012
  * Descripci�n: Este archivo contiene la funcion que valida que la informacion de la Empresa Externa ya se encuentre registrada en la BD.
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticionHTTP;
	
	var opc;

	/*Esta funci�n verifica que los datos de una empresa ya se encuentren registrados en la BD */
	function verificarRegistroEmpresasExt(clave){
		if(clave!=""){
			document.getElementById("txt_color").disabled=false;
			//Crear la URL, la cual ser� solicitada al Servidor 
			var url = "includes/ajax/verificarTipoRegistroEmpExt.php?clave="+clave;
			/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� 
			del navegador. Como cada petici�n
			*variar� al menos en el valor de uno de los par�metros, el navegador estar� obligado siempre a realizar la petici�n directamente al 
			servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			cargarTipoRegistro(url, "GET", procesarTipoReg);
		}
	}//Fin de la Funcion verificarDatoBD(campo)
	
	
	/*Esta funci�n recibe 3 par�metros: la URL del contenido que se va a cargar, el m�todo HTTP mediante el que se carga y una referencia a la funci�n que procesa la respuesta
	 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargarTipoRegistro(url, metodo, funcion) {
		peticionHTTP = inicializarObjetoXHR();
		if(peticionHTTP) {
			peticionHTTP.onreadystatechange = funcion;
			peticionHTTP.open(metodo, url, true);
			peticionHTTP.send(null);
		}
	}
	
	/*Esta funcion encapsula la creaci�n del objeto XMLHttpRequest*/
	function inicializarObjetoXHR() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}	

	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n */
	function procesarTipoReg(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Variable que contiene el tipo de registro de la empresa 
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if(existe=="true"){
					var claveEmp = respuesta.getElementsByTagName("claveEmp").item(0).firstChild.data;
					var nomEmp = respuesta.getElementsByTagName("nomEmp").item(0).firstChild.data;					
					var razSocial = respuesta.getElementsByTagName("razSocial").item(0).firstChild.data;
					var tipoEmp = respuesta.getElementsByTagName("tipoEmp").item(0).firstChild.data;
					var calle = respuesta.getElementsByTagName("calle").item(0).firstChild.data;	
					var colonia = respuesta.getElementsByTagName("colonia").item(0).firstChild.data;	
					var ciudad = respuesta.getElementsByTagName("ciudad").item(0).firstChild.data;	
					var estado = respuesta.getElementsByTagName("estado").item(0).firstChild.data;	
					var tel = respuesta.getElementsByTagName("tel").item(0).firstChild.data;	
					var numExt = respuesta.getElementsByTagName("numExt").item(0).firstChild.data;					
					var numInt = respuesta.getElementsByTagName("numInt").item(0).firstChild.data;
					var color = respuesta.getElementsByTagName("color").item(0).firstChild.data;
					
					if(tipoEmp=="�ND")
						tipoEmp="";
					if(calle=="�ND")
							calle="";
					if(colonia=="�ND")
						colonia="";
					if(ciudad=="�ND")
						ciudad="";
					if(estado=="�ND")
						estado="";
					if(tel=="�ND")
						tel="";
					if(numExt=="�ND")
						numExt="";
					if(numInt=="�ND")
						numInt="";
	
					//Se asignan los valores de los campos en sus respectivas cajas de texto dentro del formulario
					document.getElementById("hdn_claveEmpresa").value=claveEmp;
					document.getElementById("txt_nomEmpresa").value=nomEmp;
					document.getElementById("txt_razonSocial").value=razSocial;
					document.getElementById("txt_tipoEmpresa").value=tipoEmp;
					document.getElementById("txt_calle").value=calle;
					document.getElementById("txt_colonia").value=colonia;
					document.getElementById("txt_ciudad").value=ciudad;
					document.getElementById("txt_estado").value=estado;
					document.getElementById("txt_tel").value=tel;
					document.getElementById("txt_numExt").value=numExt;
					document.getElementById("txt_numInt").value=numExt;
				
					//Solo hasta que se el usuario haya seleccionado un opcion del combo se habilitaran los siguientes campos dentro del formulario
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
					
					//Establecer el color de relleno de la caja
					document.getElementById("txt_color").value=color;
					document.getElementById("txt_color").style.background=color;
				}	
				else{
					//De lo contrario las opciones se deben de mostrar desahabilitados
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
					
					//Restablecer la caja de Texto para el color
					document.getElementById("txt_color").style.background="FFF";
					document.getElementById("txt_color").disabled=true;
					document.getElementById("txt_color").value="FFFFFF";
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()--procesarTipoReg