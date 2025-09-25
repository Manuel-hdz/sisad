/**
  * Nombre del M�dulo: Compras
  * �Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 27/Enero/2015                                      			
  * Descripci�n: Este archivo se encarga de llenar un comboBox con la informaci�n solicitada sobre un control de costos
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticion_equipo_mtto;
	var fecha_ini;
	var fecha_fin;
	var nomina;
	var prod;
	
	function cargarTxtFechas(nominaC,fechaI,fechaF,bd){
		fecha_fin=fechaF;
		fecha_ini=fechaI;
		nomina=nominaC;
		if(nominaC!=""){			
			//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript
			var url = "includes/ajax/cargarFechasTxt.php?nominaC="+nominaC+"&bd="+bd;
			/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. 
			 *Como cada petici�n variar� al menos en el valor de uno de los par�metros, el navegadorestar� obligado siempre a realizar la petici�n directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoFechas(url, "GET", cargarDatosFechas);
		}
	}//Fin de la Funcion
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n*/
	function cargarDatosFechas(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_equipo_mtto.readyState==READY_STATE_COMPLETE){
			if(peticion_equipo_mtto.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_equipo_mtto.responseXML;
				//Obtener el resultado de la comparacion del dato ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					//Recuperar datos del Archivo XML					
					var tam = respuesta.getElementsByTagName("tam").item(0).firstChild.data;
					
					var fecI;
					var fecF;
					var area;
					//Obtener la referencia de los elementos que seran cargados con los datos
					objeto = document.getElementById(fecha_ini);	
					objeto2 = document.getElementById(fecha_fin);
					objeto3 = document.getElementById("hdn_area");
										
					//Recorrer la respuesta XML para colocar los valores
					for(var i=0;i<tam;i++){												
						//Obtener cada uno de los datos que ser�n cargados
						fecI = respuesta.getElementsByTagName("fechaI"+(i+1)).item(0).firstChild.data;
						fecF = respuesta.getElementsByTagName("fechaF"+(i+1)).item(0).firstChild.data;
						area = respuesta.getElementsByTagName("area"+(i+1)).item(0).firstChild.data;
						
						//Agregar el valor del atributo value
						objeto.value = fecI;
						objeto2.value = fecF;
						objeto3.value = area;
					}
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion cargarDatosFechas()
	
	function cargarProdFechas(prodC,fechaI,fechaF){
		fecha_fin=fechaF;
		fecha_ini=fechaI;
		prod=prodC;
	
		//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
		var iniDia=fechaI.substr(0,2);
		var iniMes=fechaI.substr(3,2);
		var iniAnio=fechaI.substr(6,4);
	
		//Extraer los datos de la fecha de Cierre, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
		var finDia=fechaF.substr(0,2);
		var finMes=fechaF.substr(3,2);
		var finAnio=fechaF.substr(6,4);
		
		
		//Unir los datos para crear la cadena de Fecha leida por Javascript
		var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
		var fechaFin=finMes+"/"+finDia+"/"+finAnio;
		
		//Convertir la cadena a formato valido para JS
		fechaIni=new Date(fechaIni);
		fechaFin=new Date(fechaFin);
		
		//Verificar que el a�o de Fin sea mayor al de Inicio
		if(fechaIni>fechaFin){
			document.getElementById("btn_reset").form.reset();
			cargarProdFechas('1',document.getElementById("txt_fechaIni").value,document.getElementById("txt_fechaFin").value);
			alert ("La fecha de Inicio no puede ser mayor a la Fecha de Fin");
		}
		else if(prodC!=""){			
			//Crear la URL, la cual ser� solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo 
			//incluido este archivo JavaScript
			var url = "includes/ajax/cargarFechasTxt.php?prodC="+prodC+"&fechaI="+fechaI+"&fechaF="+fechaF;
			/*A�adir un par�metro adicional a las peticiones GET y POST es una de las estrategias m�s utilizadas para evitar problemas con la cach� del navegador. 
			 *Como cada petici�n variar� al menos en el valor de uno de los par�metros, el navegadorestar� obligado siempre a realizar la petici�n directamente al 
			 *servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();	
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoFechas(url, "GET", cargarDatosProdFechas);
		}
	}//Fin de la Funcion
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petici�n*/
	function cargarDatosProdFechas(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticion_equipo_mtto.readyState==READY_STATE_COMPLETE){
			if(peticion_equipo_mtto.status==200){
				//Recuperar la respuesta del Servidor
				var respuesta = peticion_equipo_mtto.responseXML;
				//Obtener el resultado de la comparacion del dato ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					//Recuperar datos del Archivo XML					
					
					var prod_fre;
					var prod_sau;
					//Obtener la referencia de los elementos que seran cargados con los datos
					objeto = document.getElementById("txt_produccion_fre");	
					objeto2 = document.getElementById("txt_produccion_sau");
					
					//Obtener cada uno de los datos que ser�n cargados
					prod_fre = respuesta.getElementsByTagName("pro_fre"+(1)).item(0).firstChild.data;
					prod_sau = respuesta.getElementsByTagName("pro_sau"+(1)).item(0).firstChild.data;
					
					//Agregar el valor del atributo value
					objeto.value = prod_fre;
					objeto2.value = prod_sau;
					
					objeto.value = parseFloat(Math.round(objeto.value * 100) / 100).toFixed(2);
					objeto2.value = parseFloat(Math.round(objeto2.value * 100) / 100).toFixed(2);
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion cargarDatosProdFechas()
	
	/*Esta funci�n recibe 3 par�metros: la URL del contenido que se va a cargar, el m�todo HTTP mediante el que se carga y una referencia a la funci�n que procesa la
	 *respuesta del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaContenidoFechas(url, metodo, funcion) {
		peticion_equipo_mtto = iniciar_xhr_req();
		if(peticion_equipo_mtto){
			peticion_equipo_mtto.onreadystatechange = funcion;
			peticion_equipo_mtto.open(metodo, url, true);
			peticion_equipo_mtto.send(null);
		}
	}
	
	function cargarCmbNomina(nominaC,fechaI,fechaF,bd,area,stat){
		nomCmb=nominaC;
		fecha_fin=fechaF;
		fecha_ini=fechaI;
		if(area!=""){			
			var url = "includes/ajax/cargarFechasTxt.php?area="+area+"&bd="+bd+"&fecha_fin="+fecha_fin+"&fecha_ini="+fecha_ini+"&stat="+stat;
			url += "&nocache=" + Math.random();	
			cargaContenidoFechas(url, "GET", cargarDatosCmbNomina);
		}
		else{
			objeto = document.getElementById(nomCmb);
			objeto.length = 0;
			objeto.length++;
			objeto.options[objeto.length-1].text="N�mina";
			objeto.options[objeto.length-1].value="";
		}
	}
	
	function cargarDatosCmbNomina(){	
		if(peticion_equipo_mtto.readyState==READY_STATE_COMPLETE){
			if(peticion_equipo_mtto.status==200){
				var respuesta = peticion_equipo_mtto.responseXML;
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					var tam = respuesta.getElementsByTagName("tam").item(0).firstChild.data;
					
					var valor;
					var texto;
					objeto = document.getElementById(nomCmb);					
					objeto.length = 0;
					objeto.length++;
					objeto.options[objeto.length-1].text = "N�mina";
					objeto.options[objeto.length-1].value = "";
					
					for(var i=0;i<tam;i++){												
						valor = respuesta.getElementsByTagName("id"+(i+1)).item(0).firstChild.data;
						texto = respuesta.getElementsByTagName("descripcion"+(i+1)).item(0).firstChild.data;
						objeto.length++;
						objeto.options[objeto.length-1].text = texto;
						objeto.options[objeto.length-1].value = valor;
						objeto.options[objeto.length-1].title = texto;
					}
				}
				else{
					objeto = document.getElementById(nomCmb);					
					objeto.length = 0;
					objeto.length++;
					objeto.options[objeto.length-1].text = "No hay N�minas entre las fechas seleccionadas";
					objeto.options[objeto.length-1].value = "";
				}
			}
		}
	}
	
	/*Esta funcion encapsula la creaci�n del objeto XMLHttpRequest*/
	function iniciar_xhr_req() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	
	