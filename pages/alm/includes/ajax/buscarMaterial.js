/**
  * Nombre del Módulo: Almacén                                               
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 18/Enero/2011                                       			
  * Descripción: Este archivo contiene las funciones para obtener los datos que serán registrados en la Salida de Material de manera Asincrona.
  */

	var READY_STATE_UNINITIALIZED=0;
	var READY_STATE_LOADING=1;
	var READY_STATE_LOADED=2;
	var READY_STATE_INTERACTIVE=3;
	var READY_STATE_COMPLETE=4;
	var peticionHTTP;
	//Esta variable ayuda a identificar si la petición de busqueda de un material viene de las operaciones de (1)Entrada de Material, (2)Salida de Material y (3)Generar Requisición
	var opc;

	/*Esta función obtendrá el dato que se quiere validar y realizará la Petición Asincrona al Servidor */
	function buscarMaterialBD(campo,opcion){
		//Verificar que el dato que se esta buscando sea diferente de vacío
		if(campo.value!=""){
			//Guardar el origen de la solicitud de busqueda
			opc = opcion;
			//Obtener el datos que se quiere validar
			var clave = campo.value.toUpperCase();
			//Ocultar el mensaje que indica si la clave fue encontrada o no
			document.getElementById("mensaje").style.visibility = "hidden";
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript(validarDatoBD.js)
 			var url = "includes/ajax/buscarMaterial.php?claveMaterial="+clave;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. Como cada petición
			 *variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoMaterial(url, "GET", procesarDatosMaterial);
		}
	}//Fin de la Funcion verificarDatoBD(campo)
				

	/*Procesar la respuesta del servidor y obtener los resultados de la petición */
	function procesarDatosMaterial(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){					
					//Obtener los datos del material del Archivo XML
					var clave = respuesta.getElementsByTagName("clave").item(0).firstChild.data;
					var nombre = respuesta.getElementsByTagName("nombre").item(0).firstChild.data;
					var existencia = respuesta.getElementsByTagName("existencia").item(0).firstChild.data;
					var unidad = respuesta.getElementsByTagName("unidad").item(0).firstChild.data;
					var costo = respuesta.getElementsByTagName("costo").item(0).firstChild.data;
					var categoria = respuesta.getElementsByTagName("categoria").item(0).firstChild.data;
					
					if(opc==1){//Entrada de Material
						//Colocar los datos en el Formulario de Entrada de Material
						document.getElementById("cmb_material").value = "";
						document.getElementById("txt_existencia").value = existencia;
						document.getElementById("txt_unidadMedida").value = unidad;						
						//document.getElementById("cmb_categoria").value = "";
						alert("Categoria: "+categoria+"\nMaterial: "+nombre);
					}//Cierre if(opc==1){//Entrada de Material										
					if(opc==2){//Salida de Material
						//Colocar los datos en el Formulario de Salida de Material
						document.getElementById("cmb_material").value = "";
						document.getElementById("txt_existencia").value = existencia;
						document.getElementById("txt_unidadMedida").value = unidad;
						formatCurrency(costo,'txt_costoUnidad');
						//document.getElementById("cmb_categoria").value = "";
						alert("Categoria: "+categoria+"\nMaterial: "+nombre);
						//Verificar si el material es equipo de Seguridad
						verificarEqSeg(categoria,1);
					}//Cierre if(opc==2){//Salida de Material
					if(opc==3){//Generar Requisicion													
						//Colocar los datos en el Formulario de Entrada de Material
						//document.getElementById("cmb_categoria").value = "";						
						document.getElementById("cmb_material").value = "";
						document.getElementById("txt_clave").value = clave;																								
						
						alert("Material: "+nombre+"\nCategoria: "+categoria);
					}//Cierre if(opc==3){//Generar Requisicion
				}
				else{					
					if(opc==1){//Entrada de Material
						document.getElementById("mensaje").style.visibility = "visible";
						//Quitar los posibles datos que existan en el Formulario de Entrada de Material cuando una clave no esta registrada
						if(document.getElementById("cmb_material")!=null) document.getElementById("cmb_material").value = "";
						document.getElementById("txt_existencia").value = "";
						document.getElementById("txt_unidadMedida").value = "";
						document.getElementById("txt_costoUnidad").value = "";
						//if(document.getElementById("cmb_categoria")!=null) document.getElementById("cmb_categoria").value = "";
					}//Cierre if(opc==1){//Entrada de Material					
					if(opc==2){//Salida de Material
						document.getElementById("mensaje").style.visibility = "visible";
						//Quitar los posibles datos que existan en el Formulario de Salida de Material cuando una clave no esta registrada
						if(document.getElementById("cmb_material")!=null) document.getElementById("cmb_material").value = "";
						document.getElementById("txt_existencia").value = "";
						document.getElementById("txt_unidadMedida").value = "";
						document.getElementById("txt_costoUnidad").value = "";
						//if(document.getElementById("cmb_categoria")!=null) document.getElementById("cmb_categoria").value = "";
					}//Cierre if(opc==2){//Salida de Material
					if(opc==3){//Generar Requisicion
						document.getElementById("mensaje").style.visibility = "visible";
						//Quitar los posibles datos que existan en el Formulario de Salida de Material cuando una clave no esta registrada
						if(document.getElementById("cmb_material")!=null) document.getElementById("cmb_material").value = "";
						//if(document.getElementById("cmb_categoria")!=null) document.getElementById("cmb_categoria").value = "";
						document.getElementById("txt_clave").value = "";							
					}//Cierre if(opc==3){//Generar Requisicion
					
				}
			}//If if(peticion_http.status==200)
		}//If if(peticion_http.readyState==READY_STATE_COMPLETE)
	}//Fin de la Funcion procesarRespuesta()
	
	/*Esta función obtendrá el dato que se quiere validar y realizará la Petición Asincrona al Servidor */
	function extraerInfoSalida(codigoBarras,cantidad,equipo,codigoBarras_ant){
		var long_ini = codigoBarras_ant.length;
		var long_fin = codigoBarras.length;
		var cadena = codigoBarras.slice(long_ini, long_fin);
		
		if(cadena == "RRR"){
			document.getElementById("txt_codBar").value=codigoBarras_ant;
			document.getElementById("cmb_idEquipo").focus();
			document.getElementById("cmb_idEquipo").onfocus();
		}
		else if(cadena == "BBB"){
			document.getElementById("txt_codBar").value="";
			document.getElementById("txt_codBar").focus();
			document.getElementById("txt_codBar").onfocus();
		}
		else if(cadena == "AAA"){
			document.getElementById("txt_codBar").value=codigoBarras_ant.slice(0, (long_ini-1));
			document.getElementById("txt_codBar").focus();
			document.getElementById("txt_codBar").onfocus();
		}
		else if(cadena == "CACA"){
			document.location.href="menu_entrada_salida.php";
		}
		else if(cadena == "CCC"){
			document.getElementById("hdn_validar").value = 0;
			document.getElementById("frm_salidaDetalle").action='frm_salidaMaterial2.php?cb=1';
			document.getElementById("frm_salidaDetalle").submit();
		}
		else{
			document.getElementById("txt_codBar").value=codigoBarras_ant;
			//codigoBarras=codigoBarras_ant;
			if(cantidad!="" && equipo!="" && document.getElementById('cmb_tipoMoneda').value!=""){
				//Verificar que el dato que se esta buscando sea diferente de vacío
				if(codigoBarras!=""){
					//Quitar todas las apariciones del caracter Apostrofe por <>, esto por la forma en que el lector de codigo de barras toma la informacion
					codigoBarras=codigoBarras.replace(/'/g,"<>");
					//Obtener el datos que se quiere validar
					codigoBarras = codigoBarras.toUpperCase();
					//Ocultar el mensaje que indica si la clave fue encontrada o no
					document.getElementById("mensaje").style.visibility = "hidden";
					//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript(validarDatoBD.js)
					var url = "includes/ajax/buscarMaterial.php?codigoBarras="+codigoBarras+"&equipoID="+equipo;
					/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. Como cada petición
					*variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al servidor y no utilizar su cache*/
					url += "&nocache=" + Math.random();
					//Hacer la Peticion al servidor de forma Asincrona
					cargaContenidoMaterial(url, "GET", procesarDatosCodBar);
				}
				else{
					document.getElementById("txt_codBar").focus();
					document.getElementById("txt_codBar").onfocus();
				}
			}
			else{
				if(cantidad=="" || cantidad==0){
					document.getElementById("sonido_alertas_incorrecto").play();
					alert("Ingresar la Cantidad de Salida. \nLa Cantidad debe ser Mayor a 0");
					document.getElementById('txt_cantSalida').focus();
					document.getElementById('txt_cantSalida').onfocus();
				}
				if(equipo=="" && cantidad!=""){
					document.getElementById("sonido_alertas_incorrecto").play();
					alert("Seleccionar un equipo o la opción NO APLICA");
					document.getElementById('cmb_idEquipo').focus();
					document.getElementById('cmb_idEquipo').onfocus();
				}
				//else if(document.getElementById('cmb_tipoMoneda').value=="" && cantidad!=""){
					//document.getElementById("sonido_alertas_incorrecto").play();
					//alert("Seleccionar el tipo de moneda");
					//document.getElementById('cmb_tipoMoneda').focus();
					//document.getElementById('cmb_tipoMoneda').onfocus();
				//}
				document.getElementById("txt_codBar").focus();
				document.getElementById("txt_codBar").onfocus();
			}
		}
	}//Fin de la Funcion extraerInfoSalida(campo)
	
	function procesarDatosCodBar(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){					
					//Obtener los datos del material del Archivo XML
					var clave = respuesta.getElementsByTagName("clave").item(0).firstChild.data;
					var nombre = respuesta.getElementsByTagName("nombre").item(0).firstChild.data;
					var existencia = respuesta.getElementsByTagName("existencia").item(0).firstChild.data;
					var unidad = respuesta.getElementsByTagName("unidad").item(0).firstChild.data;
					var costo = respuesta.getElementsByTagName("costo").item(0).firstChild.data;
					var categoria = respuesta.getElementsByTagName("categoria").item(0).firstChild.data;
					
					//Colocar los datos en el Formulario de Salida de Material
					document.getElementById("hdn_clave").value=clave;
					document.getElementById("hdn_material").value = nombre;
					document.getElementById("hdn_existencia").value = existencia;
					document.getElementById("hdn_unidadMedida").value = unidad;
					formatCurrency(costo,'hdn_costoUnidad');
					//Mostrar un Mensaje con la información del Material Encontrado
					//alert("Categoria: "+categoria+"\nMaterial: "+nombre);
					//Usar la variable Salida para extraer la cantidad de salida en un mejor manejo
					var salida=document.getElementById('txt_cantSalida').value;
					salida=parseInt(salida);
					//Verificar la existencia del material contra la cantidad de salida solicitada
					if(existencia>0 && existencia>=salida){
						//Buscar la palabra SEG en el nombre de categoria
						var pos = categoria.indexOf('SEG');
						//Si pos es diferente de -1, indicar que puede ser Equipo de Seguridad
						if(pos==-1){
							document.getElementById("sonido_alertas_correcto").play();
							//Si todo se puede realizar correctamente, se envia el formulario
							setTimeout("frm_salidaDetalle.submit()",500);
						}
						else{
							document.getElementById("sonido_alertas_incorrecto").play();
							//Dejar la respuesta en manos del usuario
							if(confirm("Se detectó una Categoría que es similar a 'Equipo de Seguridad'.\nPresione Aceptar para registrar la Salida como Equipo de Seguridad. \n\n**Nota: Los Materiales Registrados se Perderán"))
								location.href="frm_equipoSeguridad.php";
							else
								//Si todo se puede realizar correctamente, se envia el formulario
								frm_salidaDetalle.submit();
						}
					}
					else{
						document.getElementById("sonido_alertas_incorrecto").play();
						var aux = parseInt(document.getElementById('txt_cantSalida').value);
						//Si la existencia es menor a 1, indicar que no hay Material en Almacen
						if(existencia<1)
							alert("No Hay Material "+nombre+" en el Stock de Almacén");
						//Si se pide mas cantidad de la que hay en almacen, indicarlo
						else if(aux>existencia)
							alert("La Existencia del Material no Alcanza a Cubrir la Demanda de Salida.\n\nSolo se cuenta con " + existencia + " de existencia en el Stock de Almacén");
						//Para cualquier caso que no se haya podido extraer la informacion o no se cumpla con materiales, restablecer el formulario
						document.getElementById('txt_codBar').value="";
						document.getElementById('txt_codBar').focus();
						document.getElementById('txt_codBar').onfocus();
						//document.getElementById("txt_codBar").value="";
						//Eliminar los valores asignados a los elementos Hidden
						document.getElementById("hdn_clave").value="";
						document.getElementById("hdn_material").value = "";
						document.getElementById("hdn_existencia").value = "";
						document.getElementById("hdn_unidadMedida").value = "";
						document.getElementById("hdn_costoUnidad").value = "";
					}
				}
				//Si el material no existe en almacen, mostrar un mensaje
				else{
					document.getElementById("sonido_alertas_incorrecto").play();
					alert("El Código No Esta Registrado para Ningún Material del Almacén");
					document.getElementById("mensaje").style.visibility="visible";
					document.getElementById("txt_codBar").value="";
					document.getElementById("txt_codBar").focus();
					document.getElementById("txt_codBar").onfocus();
				}
			}
		}
	}
	function validadCantidadEquipo(cantidad,cantidad_ant){
		var long_ini = cantidad_ant.length;
		var long_fin = cantidad.length;
		var cadena = cantidad.slice(long_ini, long_fin);
		
		if(cadena == "BRINC"){
			document.getElementById("txt_cantSalida").value=cantidad_ant;
			document.getElementById("cmb_idEquipo").focus();
			document.getElementById("cmb_idEquipo").onfocus();
			document.getElementById("cmb_idEquipo").value=document.getElementById("cmb_idEquipo").value;
		}
		else if(cadena == "RRR"){
			document.getElementById("txt_cantSalida").value=cantidad_ant;
			document.getElementById("txt_cantSalida").focus();
			document.getElementById("txt_cantSalida").onfocus();
		}
		else if(cadena == "BBB"){
			document.getElementById("txt_cantSalida").value="";
			document.getElementById("txt_cantSalida").focus();
			document.getElementById("txt_cantSalida").onfocus();
		}
		else if(cadena == "AAA"){
			document.getElementById("txt_cantSalida").value=cantidad_ant.slice(0, (long_ini-1));
			document.getElementById("txt_cantSalida").focus();
			document.getElementById("txt_cantSalida").onfocus();
		}
		else if(cadena == "CACA"){
			document.location.href="menu_entrada_salida.php";
		}
		else if(cadena == "CCC"){
			document.getElementById("hdn_validar").value = 0;
			document.getElementById("frm_salidaDetalle").action='frm_salidaMaterial2.php?cb=1';
			document.getElementById("frm_salidaDetalle").submit();
		}
		else{
			document.getElementById("txt_cantSalida").focus();
			document.getElementById("txt_cantSalida").onfocus();
		}
	}
	/*Esta función obtendrá el dato que se quiere validar y realizará la Petición Asincrona al Servidor */
	function extraerInfoEquipo(equipo,equipo_ant){
		var long_ini = equipo_ant.length;
		var long_fin = equipo.length;
		var cadena = equipo.slice(long_ini, long_fin);
		
		if(cadena == "RRR"){
			document.getElementById("cmb_idEquipo").value=equipo_ant;
			document.getElementById("txt_cantSalida").focus();
			document.getElementById("txt_cantSalida").onfocus();
		}
		else if(cadena == "BBB"){
			document.getElementById("cmb_idEquipo").value="";
			document.getElementById("cmb_idEquipo").focus();
			document.getElementById("cmb_idEquipo").onfocus();
		}
		else if(cadena == "AAA"){
			document.getElementById("cmb_idEquipo").value=equipo_ant.slice(0, (long_ini-1));
			document.getElementById("cmb_idEquipo").focus();
			document.getElementById("cmb_idEquipo").onfocus();
		}
		else if(cadena == "CACA"){
			document.location.href="menu_entrada_salida.php";
		}
		else if(cadena == "CCC"){
			document.getElementById("hdn_validar").value = 0;
			document.getElementById("frm_salidaDetalle").action='frm_salidaMaterial2.php?cb=1';
			document.getElementById("frm_salidaDetalle").submit();
		}
		else if(cadena == "N-A"){
			document.getElementById("cmb_idEquipo").value=equipo_ant.slice(0, long_ini) + "N/A";
			document.getElementById("cmb_idEquipo").focus();
			document.getElementById("cmb_idEquipo").onfocus();
		}
		else if(cadena == "BRINC"){
			document.getElementById("cmb_idEquipo").value=equipo_ant;
			equipo=equipo_ant;
			if(equipo!=""){
				//Verificar que el dato que se esta buscando sea diferente de vacío
				if(equipo!=""){
					//Quitar todas las apariciones del caracter Apostrofe por <>, esto por la forma en que el lector de codigo de barras toma la informacion
					equipo=equipo.replace(/'/g,"<>");
					//Obtener el datos que se quiere validar
					equipo = equipo.toUpperCase();
					//Ocultar el mensaje que indica si la clave fue encontrada o no
					document.getElementById("mensaje").style.visibility = "hidden";
					//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript(validarDatoBD.js)
					var url = "includes/ajax/buscarMaterial.php?equipo="+equipo;
					/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. Como cada petición
					*variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al servidor y no utilizar su cache*/
					url += "&nocache=" + Math.random();
					//Hacer la Peticion al servidor de forma Asincrona
					cargaContenidoMaterial(url, "GET", procesarDatosEquipo);
				}
			}
			else{
				if(equipo==""){
					document.getElementById("sonido_alertas_incorrecto").play();
					alert("Seleccionar un equipo");
					document.getElementById('cmb_idEquipo').focus();
					document.getElementById('cmb_idEquipo').onfocus();
				}
				document.getElementById("cmb_idEquipo").value="";
			}
		}
		else{
			document.getElementById('cmb_idEquipo').focus();
			document.getElementById('cmb_idEquipo').onfocus();
		}
	}//Fin de la Funcion extraerInfoEquipo(campo)
	
	function procesarDatosEquipo(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){					
					//Obtener los datos del material del Archivo XML
					var equipo = respuesta.getElementsByTagName("equipo").item(0).firstChild.data;
					
					//Colocar los datos en el Formulario de Salida de Material
					document.getElementById("cmb_idEquipo").value=equipo;
					document.getElementById("txt_codBar").focus();
					document.getElementById("txt_codBar").onfocus();
					document.getElementById("txt_codBar").value=document.getElementById("txt_codBar").value;
				}
				//Si el material no existe en almacen, mostrar un mensaje
				else{
					document.getElementById("sonido_alertas_incorrecto").play();
					alert("El equipo no se encuentra registrado");
					document.getElementById("cmb_idEquipo").value="";
					document.getElementById("cmb_idEquipo").focus();
					document.getElementById("cmb_idEquipo").onfocus();
				}
			}
		}
	}
	
	function procesarDatosMoneda(moneda,moneda_ant){
		var long_ini = moneda_ant.length;
		var long_fin = moneda.length;
		var cadena = moneda.slice(long_ini, long_fin);
		
		if(cadena == "RRR"){
			document.getElementById("cmb_tipoMoneda").value=moneda_ant;
			document.getElementById("cmb_idEquipo").focus();
			document.getElementById("cmb_idEquipo").onfocus();
		}
		else if(cadena == "BBB"){
			document.getElementById("cmb_tipoMoneda").value="";
			document.getElementById("cmb_tipoMoneda").focus();
			document.getElementById("cmb_tipoMoneda").onfocus();
		}
		else if(cadena == "AAA"){
			document.getElementById("cmb_tipoMoneda").value=moneda_ant.slice(0, (long_ini-1));
			document.getElementById("cmb_tipoMoneda").focus();
			document.getElementById("cmb_tipoMoneda").onfocus();
		}
		else if(cadena == "CACA"){
			document.location.href="menu_entrada_salida.php";
		}
		else if(cadena == "CCC"){
			document.getElementById("hdn_validar").value = 0;
			document.getElementById("frm_salidaDetalle").action='frm_salidaMaterial2.php?cb=1';
			document.getElementById("frm_salidaDetalle").submit();
		}
		else{
			document.getElementById("cmb_tipoMoneda").value=moneda_ant;
			//moneda=moneda_ant;
			if(moneda!=""){
				if(cadena == "BRINC" && (moneda_ant=="PESOS" || moneda_ant=="DOLARES" || moneda_ant=="EUROS")){
					document.getElementById("cmb_tipoMoneda").value=moneda_ant;
					document.getElementById("txt_codBar").focus();
					document.getElementById("txt_codBar").onfocus();
					document.getElementById("txt_codBar").value=document.getElementById("txt_codBar").value;
				}
				else if(moneda!="PESOS" && moneda!="DOLARES" && moneda!="EUROS"){
					document.getElementById("sonido_alertas_incorrecto").play();
					alert("El tipo de moneda debe ser PESOS, DOLARES o EUROS");
					document.getElementById("cmb_tipoMoneda").value="";
					document.getElementById('cmb_tipoMoneda').focus();
					document.getElementById('cmb_tipoMoneda').onfocus();
				}
				else{
					document.getElementById('txt_codBar').focus();
					document.getElementById('txt_codBar').onfocus();
					document.getElementById("txt_codBar").value=document.getElementById("txt_codBar").value;
					document.getElementById("cmb_tipoMoneda").value=moneda;
				}
			}
			else{
				if(moneda==""){
					document.getElementById("sonido_alertas_incorrecto").play();
					alert("Seleccionar un tipo de moneda");
					document.getElementById('cmb_tipoMoneda').focus();
					document.getElementById('cmb_tipoMoneda').onfocus();
				}
				document.getElementById("cmb_tipoMoneda").value="";
			}
		}
	}
	
	/*Esta función obtendrá el dato que se quiere validar y realizará la Petición Asincrona al Servidor */
	function extraerInfoPedido(pedido){
		if(pedido!=""){
			//Verificar que el dato que se esta buscando sea diferente de vacío
			if(pedido!=""){
				//Quitar todas las apariciones del caracter Apostrofe por <>, esto por la forma en que el lector de codigo de barras toma la informacion
				pedido=pedido.replace(/'/g,"<>");
				//Obtener el datos que se quiere validar
				pedido = pedido.toUpperCase();
				//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript(validarDatoBD.js)
				var url = "includes/ajax/buscarMaterial.php?pedido="+pedido;
				/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. Como cada petición
				 *variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al servidor y no utilizar su cache*/
				url += "&nocache=" + Math.random();
				//Hacer la Peticion al servidor de forma Asincrona
				cargaContenidoMaterial(url, "GET", procesarDatosPedido);
			}
		}
		else{
			if(pedido==""){
				alert("Seleccionar un pedido");
				document.getElementById('txt_pedido').focus();
			}
			document.getElementById("txt_pedido").value="";
		}
	}//Fin de la Funcion extraerInfoEquipo(campo)
	
	function procesarDatosPedido(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					//Obtener los datos del material del Archivo XML
					var pedido = respuesta.getElementsByTagName("pedido").item(0).firstChild.data;
					
					//Colocar los datos en el Formulario de Salida de Material
					document.getElementById("txt_pedido").value=pedido;
					document.getElementById("txt_pedido").focus();
				}
				//Si el material no existe en almacen, mostrar un mensaje
				else{
					document.getElementById("txt_pedido").value="";
					document.getElementById("txt_pedido").focus();
					alert("El pedido no se encuentra registrado");
				}
			}
		}
	}
	
	/*Esta función obtendrá el dato que se quiere validar y realizará la Petición Asincrona al Servidor */
	function extraerInfoReq(requisicion,depto){
		if(requisicion!=""){
			//Verificar que el dato que se esta buscando sea diferente de vacío
			if(requisicion!=""){
				//Quitar todas las apariciones del caracter Apostrofe por <>, esto por la forma en que el lector de codigo de barras toma la informacion
				requisicion=requisicion.replace(/'/g,"<>");
				//Obtener el datos que se quiere validar
				requisicion = requisicion.toUpperCase();
				//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript(validarDatoBD.js)
				var url = "includes/ajax/buscarMaterial.php?requisicion="+requisicion+"&depto="+depto;
				/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. Como cada petición
				 *variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al servidor y no utilizar su cache*/
				url += "&nocache=" + Math.random();
				//Hacer la Peticion al servidor de forma Asincrona
				cargaContenidoMaterial(url, "GET", procesarDatosReq);
			}
		}
		else{
			if(requisicion==""){
				alert("Seleccionar un requisicion");
				document.getElementById('txt_req').focus();
			}
			document.getElementById("txt_req").value="";
		}
	}//Fin de la Funcion extraerInfoEquipo(campo)
	
	function procesarDatosReq(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					//Obtener los datos del material del Archivo XML
					var requisicion = respuesta.getElementsByTagName("requisicion").item(0).firstChild.data;
					
					//Colocar los datos en el Formulario de Salida de Material
					document.getElementById("txt_req").value=requisicion;
					document.getElementById("txt_req").focus();
				}
				//Si el material no existe en almacen, mostrar un mensaje
				else{
					document.getElementById("txt_req").value="";
					document.getElementById("txt_req").focus();
					alert("La requisicion no se encuentra registrada");
				}
			}
		}
	}
	
	/*Esta función verificará que el vale indicado no se repita en el registro de salidas del mismo día*/
	function verificarVale(noVale, fecha){
		//Verificar que el dato que se esta buscando sea diferente de vacío
		if(noVale!=""){						
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript(buscarMaterial.js)
 			var url = "includes/ajax/buscarMaterial.php?vale="+noVale+"&fechaActual="+fecha;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. Como cada petición
			 *variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoMaterial(url, "GET", procesarVerificarVale);
		}
	}
	
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petición */
	function procesarVerificarVale(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					//Obtener el No. del vale y el No. de la salida para notificar al usuario
					var noVale = respuesta.getElementsByTagName("noVale").item(0).firstChild.data;					
					var noSalida = respuesta.getElementsByTagName("noSalida").item(0).firstChild.data;
					
					alert("El No. de Vale "+noVale+" Ya fue registrado en la Salida No. "+noSalida);
					
					//Vaciar la caja con el No. del vale del formulario de Salida de Material
					document.getElementById("txt_noVale").value = "";
				}
			}//Cierre if(peticionHTTP.status==200)
		}//Cierre if(peticionHTTP.readyState==READY_STATE_COMPLETE)
	}//Cierre de la funcion procesarDatosMaterial()
	
	
		/*Esta función verificará que el vale indicado no permita dar el mismo equipo de seguridad al mismo empleado*/
	function verificarValEquipoSeguridad(equipoSeg){
		//Variables para almacenar el no de vale y el nombre del empleado
		var noVale = document.getElementById("txt_noVale").value;
		var rfcEmp = document.getElementById("cmb_nombre").value;
		var equipoSegVal = equipoSeg.value;
		//Verificar que el dato que se esta buscando sea diferente de vacío
		if(noVale!=""&&rfcEmp!=""&&equipoSegVal!=""){						
			//Crear la URL, la cual será solicitada al Servidor, el directorio inicia desde la ubicacion del archivo donde esta siendo incluido este archivo JavaScript(buscarMaterial.js)
 			var url = "includes/ajax/buscarMaterial.php?noVale="+noVale+"&equipoSeg="+equipoSegVal+"&rfcEmp="+rfcEmp+"&nomCkb="+equipoSeg.name;
			/*Añadir un parámetro adicional a las peticiones GET y POST es una de las estrategias más utilizadas para evitar problemas con la caché del navegador. Como cada petición
			 *variará al menos en el valor de uno de los parámetros, el navegador estará obligado siempre a realizar la petición directamente al servidor y no utilizar su cache*/
			url += "&nocache=" + Math.random();
			//Hacer la Peticion al servidor de forma Asincrona
			cargaContenidoMaterial(url, "GET", procesarVerificarValeEquipoSeg);
		}
	}
	
	
	/*Procesar la respuesta del servidor y obtener los resultados de la petición */
	function procesarVerificarValeEquipoSeg(){
		//Verificar que la peticion HTTP se haya realizado correctamente
 		if(peticionHTTP.readyState==READY_STATE_COMPLETE){
			if(peticionHTTP.status==200){
				//Recuperar la respuesta del Servidor
				respuesta = peticionHTTP.responseXML;
				//Obtener el resultado de la comparacion del datos ingresado en el Formulario y el dato registrado en la BD
				var existe = respuesta.getElementsByTagName("valor").item(0).firstChild.data;
				if (existe=="true"){
					//Obtener el No. del vale y el No. de la salida para notificar al usuario
					var noVale = respuesta.getElementsByTagName("noVale").item(0).firstChild.data;					
					var claveMat = respuesta.getElementsByTagName("claveMat").item(0).firstChild.data;
					var idSalida = respuesta.getElementsByTagName("salida").item(0).firstChild.data;
					var ckb = respuesta.getElementsByTagName("nomCkb").item(0).firstChild.data;
					
					alert("Este Material Ya Fue Entregado al Empleado Seleccionado en el Vale "+noVale);
					
					//Vaciar la caja con el No. del vale del formulario de Salida de Material
					document.getElementById(ckb).checked=false;
					document.getElementById(ckb).disabled=true;
					document.getElementById(ckb).title="Este Material Ya Fue Entregado al Empleado Seleccionado en el Vale "+noVale;
					//Obtenemos el id del ckb para los materiales con cambio
					ckb=ckb.replace("ckb","ckb_");
					document.getElementById(ckb).checked=false;
					document.getElementById(ckb).disabled=true;
				}
			}//Cierre if(peticionHTTP.status==200)
		}//Cierre if(peticionHTTP.readyState==READY_STATE_COMPLETE)
	}//Cierre de la funcion procesarDatosMaterial()
	
	
	/*Esta función recibe 3 parámetros: la URL del contenido que se va a cargar, el método HTTP mediante el que se carga y una referencia a la función que procesa la respuesta
	 *del servidor. Primero inicializa el objeto XMLHttpRequest, luego indica que funcion procesara la respuesta del Servidor y despues hace la peticion*/
	function cargaContenidoMaterial(url, metodo, funcion) {
		peticionHTTP = inicializarObjetoXHR();
		if(peticionHTTP) {
			peticionHTTP.onreadystatechange = funcion;
			peticionHTTP.open(metodo, url, true);
			peticionHTTP.send(null);
		}
	}//Cierre de la función cargaContenidoMaterial(url, metodo, funcion)
	
	/*Esta funcion encapsula la creación del objeto XMLHttpRequest*/
	function inicializarObjetoXHR() {		
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			return new XMLHttpRequest();
		}else if (window.ActiveXObject) { // IE
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	}//Cierre de la funcion inicializarObjetoXHR()