/**
  * Nombre del Módulo: Compras                                               
  * ®Concreto Lanzado de Fresnillo S.A. de C.V.
  * Fecha: 30/Octubre/2010                                      			
  * Descripción: Este archivo contiene funciones para validar los diferentes formularios del Módulo Compras
  */  
/*****************************************************************************************************************************************************************************************/
/************************************************************************VALIDAR CARACTERES***********************************************************************************************/
/*****************************************************************************************************************************************************************************************/
/*Esta función se encarga de que el usuario no pueda ingresar caracteres invalidos en los campos de los diferentes formulario del Módulo de Compras*/
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
	if(te==5){//Campo RFC, numero telefónico, solo acepta numeros o letras o ambos, no permite ningun caracter especial
		var teclas_especiales = [8, 44];		
		//8 = BackSpace, 44 = coma
	}
	if(te==6){//Campo Numero Telefónico con Caracteres Especiales como Parentesis de Cierre y Apertura, espacio en blanco y guion medio
		var teclas_especiales = [8, 32, 40, 41, 42, 45];		
		//8 = BackSpace, 32=espacion Blanco, 40=Parentesis Apertura, 41=Parentesis Cierre, 42=Asterisco, 45=Guion medio
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
	
	//Si el caracter tecleado no esta dentro de los permitidos y ademas no esta en los caracteres especiales, significa que dicho caracter mo es aceptado en el campo
	if(permitidos.indexOf(caracter) == -1 && !tecla_especial) 
		alert("El Carácter '"+ caracter +"' no esta permitido en este campo");
	
	// Comprobar si la tecla pulsada se encuentra en los caracteres permitidos
	// o si es una tecla especial
	return permitidos.indexOf(caracter) != -1 || tecla_especial;
	
}

/*Esta funcion solicita la confirmación del usuario antes de salir de la pagina*/
function confirmarSalida(pagina){
	if(confirm("¿Estas Seguro que Quieres Salir?\nToda la información no Guardada se Perderá"))
		location.href = pagina;	
}

/*****************************************************************************************************************************************************************************************/
/*************************************************************************AGREGAR PROVEEDOR***********************************************************************************************/
/*****************************************************************************************************************************************************************************************/
function valFormAgregarProveedor(frm_agregarProveedor){
	//Si el valor se mantiene en 1, entonces el proceso de validacion fue satisfactorio
	var res = 1;		
	
	//Validar que los campos no se encuentren vacios
	var cond = verContFormAgregarProveedor(frm_agregarProveedor);
	
	if(cond){
		if(frm_agregarProveedor.txt_telefono.value!=""){
			//Validar el telefono
			if(!validarTelefono(frm_agregarProveedor.txt_telefono.value)){
				//Desplegar Mensaje en caso de que el telefono sea incorrecto
				alert("El número de Teléfono '"+frm_agregarProveedor.txt_telefono.value+"' no es valido");
				res = 0;							
			}	
		}
		
		
		if(frm_agregarProveedor.txt_correo.value!=""){
			//Validar correo, el telefono
			if(!validarCorreo(frm_agregarProveedor.txt_correo.value)){
				//Desplegar Mensaje en caso de que el telefono sea incorrecto
				alert("El correo-e '"+frm_agregarProveedor.txt_correo.value+"' no es valido");
				res = 0;							
			}	
		}
		
	}
	else
		res = 0;
	
	
	if(res==1)
		return true;	
	else
		return false;
}


/*Esta función valida que los datos del formulario de Agregar Proveedor no esten vacios*/
function verContFormAgregarProveedor(frm_agregarProveedor){
	//Si el valor se mantiene en 1, entonces todos los campos fuero proporcionados por el usuario
	var band = 1;
	//Verificar primero el campo de rfc  no este vacio
	if(frm_agregarProveedor.txt_rfc.value==""){
		band = 0;		
		alert ("Introducir el RFC del Proveedor");
	}
	else{
		//Verificar el tamaño del RFC
		if(frm_agregarProveedor.txt_rfc.value.length!=13){
			if (!confirm("El tamaño del RFC no es correcto. ¿Continuar?")){
				band = 0;
				frm_agregarProveedor.txt_rfc.value="";
			}
		}
		if (band!=0){
			// verificar que el campo de razon social no este vaio
			if(frm_agregarProveedor.txt_razonSoc.value==""){
				band = 0;
				alert ("Introducir la Razón Social del Proveedor");
			}
			else{
				//Verificar que el campo calle no este vacio
				if(frm_agregarProveedor.txt_calle.value==""){
					band = 0;
					alert ("Introducir Nombre de la Calle");
				}				
				else{
					//Verificar que el campo numero externo no este vacio
					if(frm_agregarProveedor.txt_numExt.value==""){		
						band = 0;
						alert ("Introducir Número Exterior");
					}							
					else{
						//Verificar que el campo colonia no esta vacio
						if(frm_agregarProveedor.txt_col.value==""){
							band = 0;	
							alert ("Introducir Nombre de la Colonia");
						}									
						else{
							//Verificar que el campo codigo postal no esta vacio
							if(frm_agregarProveedor.txt_cp.value==""){
								band = 0;	
								alert ("Introducir Código Postal");
							}
							else{
								//Verificar que el campo ciudad no esta vacio
								if(frm_agregarProveedor.txt_ciudad.value==""){
									band = 0;	
									alert ("Introducir Nombre de la Ciudad");
								}
								else{
									//Verificar que el campo Estado no esta vacio
									if(frm_agregarProveedor.txt_estado.value==""){
										band = 0;	
										alert ("Introducir Nombre del Estado");
									}
									else{
										//Verificar que el campo telefono no esta vacio
										if(frm_agregarProveedor.txt_tel.value==""){
											band = 0;	
											alert ("Introducir Número de Teléfono");
										}
										else{
											//Verificar que el campo correo no esta vacio
											if(frm_agregarProveedor.txt_contacto.value==""){
												band = 0;	
												alert ("Intoducir Nombre del Contacto");
											}
											else{
												//Verificar que el campomaterialServicios no esta vacio
												if(frm_agregarProveedor.txa_matServ.value==""){
													band = 0;	
													alert ("Intoducir Material y/o Servicios");
												}
											}//Else Contacto								
										}//Else Telefono																																			
									}//Else Estado
								}//Else Ciudad
							}//Else Colonia
						}//Else Codigo Postal
			 		}//Else Numero Externo
				}//Else Calle
			}//Else Rezon Social
		}//IF de band activa
	}//Else de RFC
	
	//Verificar que El RFC ingresado no este repetida
	if(band==1){
		if(document.getElementById("hdn_claveValida").value!="si"){
			alert("Verificar la Clave Proporcionada");
			band = 0;
		}		
	}

	if(band==1){
		if (document.getElementById("hdn_validaBoton").value=="si")
			frm_agregarProveedor.sbt_registrarDoc.disabled=false;
		return true;
	}
	else
		return false;
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

/*Esta funcion valida que los campos en la seccion de Registrar documentos esten completos*/
function valTablaRegDoc(form){
	var band=1;
	if (form.txa_documento.value==""){
		alert("Introducir el nombre del Documento");	
		band=0;
	}
	if (form.txa_ubicacion.value==""&&band==1&&form.cmb_estatus.value!="NO ENTREGADO"){
		if (confirm("No se ha escrito la ubicación del documento '"+form.txa_documento.value+"'. ¿Es esto Correcto?"))
			band=1;
		else
			band=0;
		}
		
	if(band==1)
		return true;
	else
		return false;	
	
}

/*Función para validar que el correo electronico sea valido*/
function validarCorreo(correo){
	var email = correo.value;	
	//Verificar el correo cuando la caja de texto sea diferentte de vacia ("")
	if(email!=""){
		if( !(/\w{1,}[@][\w\-]{1,}([.]([\w\-]{1,})){1,3}$/.test(email)) ){
			alert("El Correo Electrónico "+email+" NO es Valido");				
			correo.value = "";
		}
	}
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

/*****************************************************************************************************************************************************************************************/
/******************************************************************************CONSULTAR PROVEEDOR****************************************************************************************/
/*****************************************************************************************************************************************************************************************/
//Validar los datos del formulario Consultar Proveedor
function valFormconsultarProveedor(frm_consultarProveedor){
	
	//Si el valor se mantiene en 1, entonces el proceso de validacion fue satisfactorio
	var res = 1;		
	
	//Validar que los campos no se encuentren vacios
	var cond = verContFormConsultarProveedor(frm_consultarProveedor);
	if(!cond)
		res = 0;
		
	if(res==1)
		return true;	
	else
		return false;	
}


function verContFormConsultarProveedor(frm_consultarProveedor){
	//Si el valor se mantiene en 1, entonces todos los campos fueron proporcionados por el usuario
	var band = 1;
	
	//Verificar primero el campo de Nombre no este vacio
	if(frm_consultarProveedor.txt_nombre.value==""){
		band = 0;		
		alert ("Introducir el Nombre del Proveedor");
	}
	if(band==1)
		return true;
	else
		return false;
}

function valFormconsultarProveedor2(frm_consultarProveedor2){
	
	//Si el valor se mantiene en 1, entonces todos los campos fueron proporcionados por el usuario
	var band = 1;
	
	//Verificar primero el campo de Nombre no este vacio
	if(frm_consultarProveedor2.txt_matServ.value==""){

		band = 0;		
		alert ("Introducir el Servicio a buscar");
	}
	if(band==1)
		return true;
	else
		return false;
}

function valFormConsultarRelevancia(frm_consultarRelevancia){
	
	//Si el valor se mantiene en 1, entonces todos los campos fueron proporcionados por el usuario
	var band = 1;
	
	//Verificar primero el campo de Nombre no este vacio
	if(frm_consultarRelevancia.cmb_relevancia.value==""){
		band = 0;		
		alert ("Seleccionar el Tipo de Relevancia a Consultar");
	}
	if(band==1)
		return true;
	else
		return false;
}


//Validar que se haya seleccionado un convenio en la pantalla de frm_consultarProveedores, en la sección inferior
function valFormconsultaConvenios(formulario){
	//Si el valor se mantiene en 1, entonces el proceso de validacion fue satisfactorio
	var band = 1;		
	//Validar que se haya seleccionado un elemento del Combo
	if(formulario.cmb_convenios.value==""){
		band = 0;		
		alert ("Seleccionar un Convenio");
	}
	
	if(band==1)
		return true;
	else
		return false;
}



/*****************************************************************************************************************************************************************************************/
/*****************************************************************************MODIFICAR PROVEEDOR*****************************************************************************************/
/*****************************************************************************************************************************************************************************************/
//Validar los datos del formulario Modificar Proveedor
function valFormmodificarProveedor(frm_modificarProveedor){
	
	//Si el valor se mantiene en 1, entonces el proceso de validacion fue satisfactorio
	var res = 1;		
	
	//Validar que los campos no se encuentren vacios
	var cond = verContFormModificarProveedor(frm_modificarProveedor);
	if(!cond)
		res = 0;
		
	if(res==1)
		return true;	
	else
		return false;	
}


function verContFormModificarProveedor(frm_modificarProveedor){
	//Si el valor se mantiene en 1, entonces todos los campos fuero proporcionados por el usuario
	var band = 1;
	
	//Verificar primero el campo de Nombre no este vacio
	if(frm_modificarProveedor.txt_razonSoc.value==""){
		band = 0;		
		alert ("Selecciona el  Nombre");
	}
	if(band==1)
		return true;
	else
		return false;
}

		
//Validar los datos del formulario Modificar Proveedor
function valFormmodificarProveedor2(frm_modificarProveedor2){
	
	//Si el valor se mantiene en 1, entonces el proceso de validacion fue satisfactorio
	var res = 1;		
	
	//Validar que los campos no se encuentren vacios
	var cond = verContFormModificarProveedor2(frm_modificarProveedor2);
	if(!cond)
		res = 0;
		
	if(res==1)
		return true;	
	else
		return false;	
}


function verContFormModificarProveedor2(frm_modificarProveedor2){
	//Si el valor se mantiene en 1, entonces todos los campos fuero proporcionados por el usuario
	var band = 1;
	
	//Verificar primero el campo de Razon Social no este vacio
	if(frm_modificarProveedor2.txt_razonSocial.value==""){
		band = 0;		
		alert ("Introducir Razon Social");
	}		
	else{
		// verificar que el campo de rfc  no este vaio
		if(frm_modificarProveedor2.txt_razonSocial.value==""){
			band = 0;
			alert ("Introducir  Razon Social");
		}
		else{
			//Verificar que el campo calle no este vacio
			if(frm_modificarProveedor2.txt_calle.value==""){
				band = 0;
				alert ("Introducir la Calle  ");
			}				
			else{
				//Verificar que el campo numero externo no este vacio
				if(frm_modificarProveedor2.txt_numeroExt.value==""){		
					band = 0;
					alert ("Introducir Numero Externo ");
				}							
				else{
					//Verificar que el campo relevancia no esta vacio
					if(frm_modificarProveedor2.cmb_relevancia.value==""){
						band = 0;	
						alert ("Selecciona la Relevancia");
					}									
					else{
						//Verificar que el campo colonia no esta vacio
						if(frm_modificarProveedor2.txt_colonia.value==""){
							band = 0;	
							alert ("Introducir Colonia");
						}
						else{
							//Verificar que el campo codigo Postal no esta vacio
							if(frm_modificarProveedor2.txt_cp.value==""){
								band = 0;	
								alert ("Introducir Codigo postal");
							}
							else{
								//Verificar que el campo Ciudad no esta vacio
								if(frm_modificarProveedor2.txt_ciudad.value==""){
									band = 0;	
									alert ("Introducir Ciudad");
								}
							 	else{
									//Verificar que el campo Estado no esta vacio
									if(frm_modificarProveedor2.txt_estado.value==""){
										band = 0;	
										alert ("Introducir Estado");
									}
								 	else{
										//Verificar que el campo telefono no esta vacio
										if(frm_modificarProveedor2.txt_telefono.value==""){
											band = 0;	
											alert ("Intoducir Telefono");
										}
										else{
											//Verificar que el contacto no esta vacio
											if(frm_modificarProveedor2.txt_contacto.value==""){
												band = 0;	
												alert ("Intoducir Contacto");
											}
										 	else{
												//Verificar que el Material o servicios no esta vacio
												if(frm_modificarProveedor2.txt_materialServicios.value==""){
													band = 0;	
													alert ("Intoducir Material o Servicios");
												}	
											 	else{
													//Verificar que el observaciones no esta vacio
													if(frm_modificarProveedor2.txa_obseravciones.value==""){
														band = 0;	
														alert ("Intoducir Observaciones");
													}														
												}//else observaciones
											}//else Material o Servicios
										}//else Contacto
									}//else Telefono																																				
								}//else Estado
							}//Else Ciudad
						}//Else Codigo postal
					}//Else Colonia
				}//Else Relevancia
			}//Else Numero Externo
		}//Else Calle
	}//Else Razon Social 	
	
	if(band==1)
		return true;
	else
		return false;
}

/*****************************************************************************************************************************************************************************************/
/**************************************************************************REGISTRAR CONVENIO*********************************************************************************************/
/*****************************************************************************************************************************************************************************************/
//Validar los datos del formulario Registrar Convenio
function valFormRegistrarConvenio(frm_registrarConvenio){
	//Si el valor se mantiene en 1, entonces todos los campos fuero proporcionados por el usuario
	var band = 1;
	
	//Verificar que se haya seleccionado un Proveedor
	if(frm_registrarConvenio.txt_nombre.value==""){
		band = 0;		
		alert ("Introducir el nombre del Proveedor ");
	}
	else{
		// verificar que el campo de Convenio social no este vaio
		if(frm_registrarConvenio.txt_convenio.value==""){
			band = 0;
			alert ("Introducir el Numero del Convenio");
		}
		else{
			//Verificar que el campo Responsable no este vacio
			if(frm_registrarConvenio.txt_responsable.value==""){
				band = 0;
				alert ("Introducir Responsable  ");
			}				
			else{
				//Verificar que el campo Autoriza no este vacio
				if(frm_registrarConvenio.txt_autoriza.value==""){
					band = 0;	
					alert ("Introducir quien Autoriza");
				}
				else{
					//Verificar que el campo subtotal no este vacio
					if(frm_registrarConvenio.txt_subtotal.value==""){		
						band = 0;
						alert ("Introducir los detalles de convenio para obtener el Subtotal");
					}							
					else{
						//Verificar que el campo iva no esta vacio
						if(frm_registrarConvenio.txt_iva.value==""){
							band = 0;	
							alert ("Introducir los detalles de convenio para obtener el IVA");
						}
						else{
							//Verificar que el campo Total no esta vacio
							if(frm_registrarConvenio.txt_total.value==""){
								band = 0;	
								alert ("Introducir los detalles de convenio para obtener Total");
							}
							else{
								//Verificar que el campo Total no esta vacio
								if(frm_registrarConvenio.cmb_estado.value==""){
									band = 0;	
									alert ("Seleccionar un Estado");
								}
							}//Else Total
						}//Else IVA														
					}//Else Subtotal												
				}//Else Autoriza									
			}//Else Responsable
		}//Else Convenio
	}//Else del Proveeodor
	var fechas=false;
	if(band==1){
		fechas=valFormFechas(frm_registrarConvenio,band);
		return fechas;
		}
	else
		return fechas;
}

/*Esta funcion valida que las fechas elegidas en los Reportes sean correctas*/
function valFormFechas(formulario,band){
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=formulario.txt_fechaInicio.value.substr(0,2);
	var iniMes=formulario.txt_fechaInicio.value.substr(3,2);
	var iniAnio=formulario.txt_fechaInicio.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=formulario.txt_fechaFin.value.substr(0,2);
	var finMes=formulario.txt_fechaFin.value.substr(3,2);
	var finAnio=formulario.txt_fechaFin.value.substr(6,4);
	
	var elabDia=formulario.txt_fechaElaboracion.value.substr(0,2);
	var elabMes=formulario.txt_fechaElaboracion.value.substr(3,2);
	var elabAnio=formulario.txt_fechaElaboracion.value.substr(6,4);
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	var fechaElab=elabMes+"/"+elabDia+"/"+elabAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);
	fechaElab=new Date(fechaElab);

	//Verificar que el año de Fin sea mayor al de Inicio
	if(fechaIni>fechaFin){
		band=0;
		alert ("La Fecha de Inicio no puede ser mayor a la Fecha de Cierre");
	}
	
	if(fechaElab>fechaIni&&band==1){
		if (confirm ("La Fecha de Elaboracion es mayor a la Fecha de Inicio del Convenio. ¿Es esto correcto?"))
			band=1;
		else
			band=0;
	}
	
	if(fechaElab<fechaIni&&band==1){
		if (confirm ("La Fecha de Elaboracion es menor a la Fecha de Inicio del Convenio. ¿Es esto correcto?"))
			band=1;
		else
			band=0;
	}
	
	if(band==1)
		return true;
	else
		return false;
}

//Validar los datos en el formulario de Registrar Detalles de convenios
function valFormDetalleConvenio(frm_registrarConvenioDet){
	//Si el valor se mantiene en 1, los datos estan completos
	var band = 1;
	//Comprobar que el boton que genera la validacion haya sido el de Guardar
	if (frm_registrarConvenioDet.hdn_boton.value=="guardar"){
		if (document.getElementById("sbt_guardar")){
			//Verificar el material o servicio a agregarse
			if(frm_registrarConvenioDet.txa_material.value==""){
				band = 0;		
				alert ("Introducir el Material y/o Servicio");
			}
			//Verificar el material o servicio a agregarse
			if(frm_registrarConvenioDet.txt_unidad.value==""&&band==1){
				band = 0;		
				alert ("Introducir la Unidad del Material y/o Servicio");
			}
			//Verificar el precio
			if(frm_registrarConvenioDet.txt_precio.value==""&&band==1){
				band = 0;		
				alert ("Introducir el Costo Unitario");
			}
			//Verificar la cantidad
			if(frm_registrarConvenioDet.txt_cantidad.value==""&&band==1){
				band = 0;		
				alert ("Introducir la cantidad");
			}
			//Verificar el importe
			if(frm_registrarConvenioDet.txt_importe.value==""&&band==1){
				band = 0;		
				alert ("Seleccionar la casilla de Importe");
			}
		}
	}
	
	if(band==1)
		return true;
	else
		return false;
	
}

//Validar Termino seleccionado de un convenio para Eliminar
function valTerminoSeleccionadoConvenio(frm_eliminaDetalleConv){
	//Variable para controlar la validacion, de cambiar de valor, el formulario no pasa la validación
	var band = 1;
	
	//Si se le dio click al boton de regesar, no realizar la validacion de los datos
	if(frm_eliminaDetalleConv.hdn_bandera.value!="regresar"){
		//Variable que verifica que se haya seleccionado un radiobutton
		var flag=0;
		var cantidad=document.getElementsByName("rdb_termino").length;
		for (var i=0;i<cantidad;i++){
			if (document.getElementById("rdb_termino"+(i+1)).checked==true){
				flag=1;
			}
		}
		
		if (flag==0){
			alert("Seleccionar un Término para Eliminar del Convenio");
			band=0;
		}
	}
		
	if(band==1)
		return true;
	else
		return false;
}

//Validar estado asignado en la pantalla de modificar Terminos de un Convenio
function valEstadoModConvenio(frm_modificarProvTerminCon){
	//Si el valor se mantiene en 1, entonces todos los campos fueron proporcionados por el usuario
	var band = 1;
	
	//Verificar que sea seleccionado un año en el combo
	if(frm_modificarProvTerminCon.cmb_estado.value==""){		
		band = 0;
		alert ("Seleccionar un Estado");
	}
	
	if(band==1)
		return true;
	else
		return false;
}

//Validar Datos completos de Terminos de un Convenio en modificar Convenio-Agregar
function valModConvenioAgregar(frm_agregaConv){
	//Si el valor se mantiene en 1, entonces todos los campos fueron proporcionados por el usuario
	var band = 1;
	//Esta variable se encarga de verificar que el boton presionado no haya sido agregar
	var flag=frm_agregaConv.hdn_bandera.value;
	
	if(flag!="finalizar"){
		//Verificar que sea escrito un Material y/o Servicio a agregar
		if(frm_agregaConv.txa_material.value==""){		
			band = 0;
			alert ("Introducir un Material y/o Servicio");
		}
		//Verificar que sea escrito una Unidad del Material
		if(frm_agregaConv.txt_unidad.value==""&&band==1){		
			band = 0;
			alert ("Introducir una Unidad de Medida");
		}
		//Verificar que sea escrito una Unidad del Material
		if(frm_agregaConv.txt_precio.value==""&&band==1){		
			band = 0;
			alert ("Introducir el Precio Unitario");
		}
		//Verificar que sea escrita la Cantidad a emplearse
		if(frm_agregaConv.txt_cantidad.value==""&&band==1){		
			band = 0;
			alert ("Introducir la Cantidad");
		}
		//Verificar que se ha generado el Importe
		if(frm_agregaConv.txt_importe.value==""&&band==1){		
			band = 0;
			alert ("Verificar el Importe");
		}
	}
	
	if(band==1)
		return true;
	else
		return false;
}



/*****************************************************************************************************************************************************************************************/
/********************************************************************************EVALUAR PROVEEDOR****************************************************************************************/
/*****************************************************************************************************************************************************************************************/
//Validar los datos del formulario Evaluar Proveedor
function valFormevaluarProveedor(frm_evaluarProveedor){
	//Si el valor se mantiene en 1, entonces todos los campos fuero proporcionados por el usuario
	var band = 1;
	
	//Verificar primero el campo de Nombre no este vacio
	if(frm_evaluarProveedor.txt_nombre.value==""){
		band = 0;		
		alert ("Introducir el Nombre del Proveedor");
	}
	if(band==1)
		return true;
	else
		return false;	
}

//Esta función se encargara de cuantificar los puntos en la evaluacion de Proveedores de acuerdo a los parametros seleccionados por el usuario. 
function acumularPuntos(elementSelect,elementHidden){
	//Asignar a la caja de texto oculta el valor del RadioButton seleccionado
	document.getElementById(elementHidden).value = elementSelect.value;
	sumarEvaluacion();
}

function sumarEvaluacion(){		
	var suma = 	parseInt(document.getElementById("cant_tiempoEntrega").value) 
				+ parseInt(document.getElementById("cant_prodServicio").value)
				+ parseInt(document.getElementById("cant_entCertificado").value);
				
	document.getElementById("txt_total").value = suma;
	document.getElementById("hdn_totalPuntos").value = suma;
}

function valFormEvaluarProveedor(frm_evaluarProveedor2){
	//Variable para controlar la validacion
	var band = 1;
	var flag=0;
	var cantidadTE=document.getElementsByName("rdb_tiempoEntrega").length;
	var cantidadPS=document.getElementsByName("rdb_ProdServicio").length;
	var cantidadEC=document.getElementsByName("rdb_entCertificado").length;

	for (var i=0;i<cantidadTE;i++){
		if (document.getElementById("rdb_tiempoEntrega"+(i+1)).checked==true){
			flag=0;
			break;
		}
		else{
			flag=2;
		}
	}

	if (flag==0){
		for (var i=0;i<cantidadPS;i++){
			if (document.getElementById("rdb_ProdServicio"+(i+1)).checked==true){
				flag=0;
				break;
			}
			else
				flag=3;
		}
	}
	
	if (flag==0){
		for (var i=0;i<cantidadEC;i++){
			if (document.getElementById("rdb_entCertificado"+(i+1)).checked==true){
				flag=0;
				break;
			}
			else
				flag=4;
		}
	}
	
	if (flag==2){
		alert("Seleccionar Calificación del Tiempo de Entrega");
		band=0;
	}
	if (flag==3){
		alert("Seleccionar Calificación del Producto/Servicio");
		band=0;
	}
	if (flag==4){
		alert("Seleccionar Si se entrego el Certificado de Calidad/Producto");
		band=0;
	}
	
	if(band==1)
		return true;
	else
		return false;
}



/*****************************************************************************************************************************************************************************************/
/***************************************************************************AGREGAR CLIENTE***********************************************************************************************/
/*****************************************************************************************************************************************************************************************/
/*Validar los datos del formulario de Agregar Cliente*/
function verContFormAgregarCliente(frm_agregarCliente){
	//Si el valor se mantiene en 1, entonces todos los campos fueron proporcionados por el usuario
	var band = 1;
	if (!frm_agregarCliente.ckb_factura.checked){
		//Verificar primero que el RFC no este vacío
		if(frm_agregarCliente.txt_rfc.value==""){
			band = 0;		
			alert ("Introducir el RFC del Cliente");
		}
		//Luego verificar que la razon o el Nombre no este vacía
		if(band==1&&frm_agregarCliente.txt_razon.value==""){
			band = 0;
			alert ("Introducir la Razón Social");
		}
		//Verificar que la Calle no este vacía
		/*if(band==1&&frm_agregarCliente.txt_calle.value==""){
			band = 0;
			alert ("Introducir el Nombre de la Calle");
		}
		//Verificar que el Numero Externo no este vacío
		if(band==1&&frm_agregarCliente.txt_numeroExt.value==""){
			band = 0;	
			alert ("Introducir el Numero Externo");
		}
		//Verificar que la Colonia no este vacía
		if(band==1&&frm_agregarCliente.txt_colonia.value==""){
			band = 0;			
			alert ("Introducir Nombre de la Colonia");
		}
		//Verificar que la Id Fiscal no este vacía
		if(band==1&&frm_agregarCliente.txt_idFiscal.value==""){
			band = 0;			
			alert ("Introducir Id Fiscal del Cliente");
		}
		//Verificar que la Ciudad no este vacía
		if(band==1&&frm_agregarCliente.txt_ciudad.value==""){
			band = 0;	
			alert ("Introducir Nombre de la Ciudad");
		}
		//Verificar que el Estado no este vacía
		if(band==1&&frm_agregarCliente.txt_estado.value==""){
			band = 0;	
			alert ("Introducir Nombre del Estado");														
		}
		
		if (band==1&&frm_agregarCliente.txt_municipio.value=="")
		{
			band = 0;	
			alert ("Introducir Nombre del Municipio");
		}
		
		if (band==1&&frm_agregarCliente.txt_cp.value=="")
		{
			band = 0;	
			alert ("Introducir Código Postal");
		}
		
		if (band==1&&frm_agregarCliente.txt_apPat.value=="")
		{
			band = 0;	
			alert ("Introducir Apellido Paterno del Contacto");
		}
		
		if (band==1&&frm_agregarCliente.txt_apMat.value=="")
		{
			band = 0;	
			alert ("Introducir Apellido Materno del Contacto");
		}
		
		if (band==1&&frm_agregarCliente.txt_nomContacto.value=="")
		{
			band = 0;	
			alert ("Introducir Nombre(s) del Contacto");
		}
		
		if (band==1&&frm_agregarCliente.txt_curp.value=="")
		{
			band = 0;	
			alert ("Introducir la CURP del Contacto");
		}*/
	}
	else{
		//Luego verificar que la razon o el Nombre no este vacía
		if(frm_agregarCliente.txt_razon.value==""){
			band = 0;
			alert ("Introducir la Razón Social");
		}
		//Verificar que la Calle no este vacía
		/*if(band==1&&frm_agregarCliente.txt_calle.value==""){
			band = 0;
			alert ("Introducir el Nombre de la Calle");
		}
		//Verificar que el Numero Externo no este vacío
		if(band==1&&frm_agregarCliente.txt_numeroExt.value==""){
			band = 0;	
			alert ("Introducir el Numero Externo");
		}
		//Verificar que la Colonia no este vacía
		if(band==1&&frm_agregarCliente.txt_colonia.value==""){
			band = 0;			
			alert ("Introducir Nombre de la Colonia");
		}
		//Verificar que la Ciudad no este vacía
		if(band==1&&frm_agregarCliente.txt_ciudad.value==""){
			band = 0;	
			alert ("Introducir Nombre de la Ciudad");
		}
		if (band==1&&frm_agregarCliente.txt_municipio.value=="")
		{
			band = 0;	
			alert ("Introducir Nombre del Municipio");
		}
		if (band==1&&frm_agregarCliente.txt_cp.value==""){
			band = 0;	
			alert ("Introducir Código Postal");
		}
		//Verificar que el Estado no este vacía
		if(band==1&&frm_agregarCliente.txt_estado.value==""){
			band = 0;	
			alert ("Introducir Nombre del Estado");														
		}*/
	}
	//Verificar que el RFC del Cliente no este repetida en la BD
	if(band==1){
		if(document.getElementById("hdn_claveValida").value!="si"){
			alert("Verificar el RFC Proporcionado para el Cliente");
			band = 0;
		}
	}
				
				
	if(band==1)
		return true;
	else
		return false;
}

/*****************************************************************************************************************************************************************************************/
/*******************************************************************************CONSULTAR CLIENTE*****************************************************************************************/
/*****************************************************************************************************************************************************************************************/
/*Esta función verifica que no este vacio el campo que contiene el nombre del cliente antes dar clic en el boton de consultar*/
function verContFormConsultarCliente(frm_consultarCliente){
	//Si el valor se mantiene en 1, entonces todos los campos fuero proporcionados por el usuario
	var band = 1;
	
	//Verificar primero el campo de Nombre no este vacío
	if(frm_consultarCliente.txt_nombre.value==""){
		band = 0;		
		alert ("Introduce el Nombre del Cliente");
	}
	
	if(band==1)
		return true;
	else
		return false;
}

/*****************************************************************************************************************************************************************************************/
/********************************************************************************MODIFICAR CLIENTE****************************************************************************************/
/*****************************************************************************************************************************************************************************************/
/*Esta función valida que un nombre sea introducido en el campo de texto para buscarlo y despues mostrar su información para ser modificada */
function verContFormSelectCliente(frm_seleccionarCliente){
	//Si el valor se mantiene en 1, entonces todos los campos fuero proporcionados por el usuario
	var band = 1;
	
	//Verificar primero el campo de Nombre no este vacío
	if(frm_seleccionarCliente.txt_nombre.value==""){
		band = 0;		
		alert ("Introducir el Nombre del Cliente");
	}
	
	if(band==1)
		return true;
	else
		return false;
}

		
/*Validar los datos del formulario Modificar Cliente*/
function verContFormModificarCliente(frm_modificarCliente){
	//Si el valor se mantiene en 1, entonces todos los campos fueron proporcionados por el usuario
	var band = 1;
	if (!frm_modificarCliente.ckb_factura.checked){
		//Verificar primero que el RFC no este vacío
		if(frm_modificarCliente.txt_rfc.value==""){
			band = 0;		
			alert ("Introducir el RFC del Cliente");
		}
		//Luego verificar que la razon o el Nombre no este vacía
		if(band==1&&frm_modificarCliente.txt_razon.value==""){
			band = 0;
			alert ("Introducir la Razón Social");
		}
		//Verificar que la Calle no este vacía
		/*if(band==1&&frm_modificarCliente.txt_calle.value==""){
			band = 0;
			alert ("Introducir el Nombre de la Calle");
		}
		//Verificar que el Numero Externo no este vacío
		if(band==1&&frm_modificarCliente.txt_numeroExt.value==""){
			band = 0;	
			alert ("Introducir el Numero Externo");
		}
		//Verificar que la Colonia no este vacía
		if(band==1&&frm_modificarCliente.txt_colonia.value==""){
			band = 0;			
			alert ("Introducir Nombre de la Colonia");
		}
		//Verificar que la Id Fiscal no este vacía
		if(band==1&&frm_modificarCliente.txt_idFiscal.value==""){
			band = 0;			
			alert ("Introducir Id Fiscal del Cliente");
		}
		//Verificar que la Ciudad no este vacía
		if(band==1&&frm_modificarCliente.txt_ciudad.value==""){
			band = 0;	
			alert ("Introducir Nombre de la Ciudad");
		}
		//Verificar que el Estado no este vacía
		if(band==1&&frm_modificarCliente.txt_estado.value==""){
			band = 0;	
			alert ("Introducir Nombre del Estado");														
		}
		
		if (band==1&&frm_modificarCliente.txt_municipio.value=="")
		{
			band = 0;	
			alert ("Introducir Nombre del Municipio");
		}
		
		if (band==1&&frm_modificarCliente.txt_cp.value=="")
		{
			band = 0;	
			alert ("Introducir Código Postal");
		}
		
		if (band==1&&frm_modificarCliente.txt_apPat.value=="")
		{
			band = 0;	
			alert ("Introducir Apellido Paterno del Contacto");
		}
		
		if (band==1&&frm_modificarCliente.txt_apMat.value=="")
		{
			band = 0;	
			alert ("Introducir Apellido Materno del Contacto");
		}
		
		if (band==1&&frm_modificarCliente.txt_nomContacto.value=="")
		{
			band = 0;	
			alert ("Introducir Nombre(s) del Contacto");
		}
		
		if (band==1&&frm_modificarCliente.txt_curp.value=="")
		{
			band = 0;	
			alert ("Introducir la CURP del Contacto");
		}*/
	}
	else{
		//Luego verificar que la razon o el Nombre no este vacía
		if(frm_modificarCliente.txt_razon.value==""){
			band = 0;
			alert ("Introducir la Razón Social");
		}
		//Verificar que la Calle no este vacía
		/*if(band==1&&frm_modificarCliente.txt_calle.value==""){
			band = 0;
			alert ("Introducir el Nombre de la Calle");
		}
		//Verificar que el Numero Externo no este vacío
		if(band==1&&frm_modificarCliente.txt_numeroExt.value==""){
			band = 0;	
			alert ("Introducir el Numero Externo");
		}
		//Verificar que la Colonia no este vacía
		if(band==1&&frm_modificarCliente.txt_colonia.value==""){
			band = 0;			
			alert ("Introducir Nombre de la Colonia");
		}
		//Verificar que la Ciudad no este vacía
		if(band==1&&frm_modificarCliente.txt_ciudad.value==""){
			band = 0;	
			alert ("Introducir Nombre de la Ciudad");
		}
		if (band==1&&frm_modificarCliente.txt_municipio.value=="")
		{
			band = 0;	
			alert ("Introducir Nombre del Municipio");
		}
		if (band==1&&frm_modificarCliente.txt_cp.value==""){
			band = 0;	
			alert ("Introducir Código Postal");
		}
		//Verificar que el Estado no este vacía
		if(band==1&&frm_modificarCliente.txt_estado.value==""){
			band = 0;	
			alert ("Introducir Nombre del Estado");														
		}*/
	}			
				
	if(band==1)
		return true;
	else
		return false;
}

/*Funcion que organiza los campos de texto se mostraran activos o no, dependiendo si el cliente es facturable*/
function validarFacturable(check){
	if(check.checked){
		document.getElementById("txt_rfc").value=document.getElementById("txt_rfc").defaultValue;
		document.getElementById("txt_idFiscal").value=document.getElementById("txt_idFiscal").defaultValue;
		document.getElementById("txt_telefono2").value=document.getElementById("txt_telefono2").defaultValue;
		document.getElementById("txt_apPat").value=document.getElementById("txt_apPat").defaultValue;
		document.getElementById("txt_apMat").value=document.getElementById("txt_apMat").defaultValue;
		document.getElementById("txt_nomContacto").value=document.getElementById("txt_nomContacto").defaultValue;
		document.getElementById("txt_curp").value=document.getElementById("txt_curp").defaultValue;
		document.getElementById("txa_referencia").value=document.getElementById("txa_referencia").defaultValue;
		
		document.getElementById("txt_rfc").readOnly=true;
		document.getElementById("txt_idFiscal").readOnly=true;
		document.getElementById("txt_telefono2").readOnly=true;
		document.getElementById("txt_apPat").readOnly=true;
		document.getElementById("txt_apMat").readOnly=true;
		document.getElementById("txt_nomContacto").readOnly=true;
		document.getElementById("txt_curp").readOnly=true;
		document.getElementById("txa_referencia").readOnly=true;
	}
	else{
		document.getElementById("txt_rfc").readOnly=false;
		document.getElementById("txt_idFiscal").readOnly=false;
		document.getElementById("txt_telefono2").readOnly=false;
		document.getElementById("txt_apPat").readOnly=false;
		document.getElementById("txt_apMat").readOnly=false;
		document.getElementById("txt_nomContacto").readOnly=false;
		document.getElementById("txt_curp").readOnly=false;
		document.getElementById("txa_referencia").readOnly=false;
	}
}

/*Funcion que restablece los campos de los clientes NO facturables*/
function restablecerFormularioClientes(){
	document.getElementById("txt_rfc").readOnly=false;
	document.getElementById("txt_idFiscal").readOnly=false;
	document.getElementById("txt_telefono2").readOnly=false;
	document.getElementById("txt_apPat").readOnly=false;
	document.getElementById("txt_apMat").readOnly=false;
	document.getElementById("txt_nomContacto").readOnly=false;
	document.getElementById("txt_curp").readOnly=false;
	document.getElementById("txa_referencia").readOnly=false;
}

/*****************************************************************************************************************************************************************************************/
/*******************************************************************************CAJA CHICA************************************************************************************************/
/*****************************************************************************************************************************************************************************************/
/*Esta funcion se ecnarga de validadr el contenido del formulario de la Caja Chica*/
function verContFormCajaChica(frm_cajaChica){
	//Si el valor se mantiene en 1, entonces todos los campos fuero proporcionados por el usuario
	var band = 1;
	
	//Verificar primero el campo de Presupuesto Inicial  no este vaci
	if(frm_cajaChica.txa_descripcion.value==""){
		band = 0;		
		alert ("Introducir Descripción del Movimiento");
	}
	else{
		// verificar que el campo de Numero social no este vaio
		if(frm_cajaChica.cant_entregada.value==""){
			band = 0;
			alert ("Introducir la Cantidad Entregada");
		}
		else{			
			//Verificar que el campo esponzableR no este vacio
			if(frm_cajaChica.txt_responsable.value==""){
				band = 0;	
				alert ("Introducir Nombre del Responsable");
			}			
		else{			
			//Verificar que el campo esponzableR no este vacio
			if(frm_cajaChica.cmb_depto.value==""){
				band = 0;	
				alert ("Seleccionar el Nombre del Departamento");
			}			
		}//else 
	}//else Descripción	
}
	

	//Validar que el costo sea un numero valido
	if(band==1){
		if(!validarEntero(frm_cajaChica.cant_entregada.value.replace(/,/g,''),"La Cantidad Entregada"))
			band = 0;
	}
			
	if(band==1)
		return true;
	else
		return false;
}

/*Esta función valida que el incremento al presupuesto sea un numero valido y mayor a cero*/
function valFormIncrementar(frm_incrementar){
	//Si el valor se mantiene en 1, entonces todos los campos fuero proporcionados por el usuario
	var band = 1;
	
	//Verificar que el importe del incremento no este vacio y no sea 0 para considerarlo
	if(frm_incrementar.txt_inPresupuesto.value==""){
		band = 0;
		alert ("Introducir el Monto del Incremento");
	}
	
	//Validar que el costo sea un numero valido
	if(band==1){
		if(!validarEntero(frm_incrementar.txt_inPresupuesto.value.replace(/,/g,''),"El Monto del Incremento"))
			band = 0;
	}
	
	if(band==1)
		return true;
	else
		return false;	
}


/*Esta funcion valida los combos de año y mes en la consulta de caja chica*/
function valFormConsultarCajaChica(frm_consultarCajaChica){
	var band=1;
	
	//Verificar que sea seleccionado un año en el combo
	if(frm_consultarCajaChica.cmb_anio.value==""){		
		band = 0;
		alert ("Seleccionar un Año");
	}
	else{
		//Verificar que sea seleccionado un mes en el combo
		if(frm_consultarCajaChica.cmb_mes.value==""){
			band = 0;	
			alert ("Seleccionar un Mes");
		}
	}
	
	
	if(band==1)
		return true;
	else
		return false;			
}


/*Esta funcion valida que el monto de la cantidad entregada no sea mayor a 2000 y que no exeda el monto del presupuesto y por ultimo le da formato de moneda*/
function verificarCant(campo){
	//Validar que la cantidad introducida sea un numero valido y mayor a 0
	var cond = validarEntero(campo.value,"La Cantidad Introducida")
	
	//Si es un numero valido, validar que no sea mayor a 2000 y si lo es, preguntar si la cantidad es correcta
	if(cond){	
		var cant = parseFloat(campo.value);					
		if(cant>2000)
			cond = confirm("La Cantidad Introducida Excede los $2,000, ¿Desea Continuar?");	
		
		//Si la cantidad es menor a 2000 o en el caso de ser mayor y que el usuario lo haya aprovado, verificar que no exceda el monto del presupuesto.
		if(cond){					
			var presupuesto = document.getElementById("txt_presupuestoInicial").value.replace(/,/g,'');
			if(cant>presupuesto){
				alert("La Cantidad Introducida Excede el Monto del Presupuesto Actual!");
				campo.value = "";
			}
			else{			
				//Regresar el numero con el formato de moneda al campo de texto indicado
				formatCurrency(cant,"cant_entregada");	
			}
		}
		else
			campo.value = "";
	}
	else
		campo.value = "";
	
}

/*Esta función se encarga de validar que sea dado el monto inicial del presupuesto cuando ningun registro de la Caja CHica existe en la BD*/
function valFormPreInicial(frm_preInicial){
	//Si el valor se mantiene en 1, entonces todos los campos fuero proporcionados por el usuario
	var band = 1;
	
	//Verificar que el importe del incremento no este vacio y no sea 0 para considerarlo
	if(frm_preInicial.txt_iniPresupuesto.value==""){
		band = 0;
		alert ("Introducir el Monto Inical del Presupuesto");
	}
	
	//Validar que el costo sea un numero valido
	if(band==1){
		if(!validarEntero(frm_preInicial.txt_iniPresupuesto.value.replace(/,/g,''),"El Monto Inicial del Presupuesto"))
			band = 0;
	}
	
	if(band==1)
		return true;
	else
		return false;	
}

/*Esta función valida que el presupuesto inicial del mes y el remanatente se sumen o en su defecto que sea ingresado el monto inicial del mes y que este sea un numero valido y mayor a 0*/
function valFormPreMensual(frm_preMensual){
	//Si el valor se mantiene en 1, entonces todos los campos fuero proporcionados por el usuario
	var band = 1;
	
	//Verificar que el importe del incremento no este vacio y no sea 0 para considerarlo
	if(frm_preMensual.txt_Presupuesto.value==""){
		band = 0;
		alert ("Introducir el Monto del Presupuesto Para el Mes Actual");
	}
	
	//Validar que el costo sea un numero valido
	if(band==1){
		if(!validarEntero(frm_preMensual.txt_Presupuesto.value.replace(/,/g,''),"El Monto del Presupuesto Para el Mes Actual"))
			band = 0;
	}
	
	if(band==1)
		return true;
	else
		return false;
}

/*Esta funcion suma el nuevo presupuesto para el mes actual con el del remanente del mes anterior y saca el total del presupuesto para el mes actual*/
function sumar(campo){
	//Obtener el valor del presupuesto y quitar la coma que trae
	var presupuesto = campo.value.replace(/,/g,'');
	//Obtener el valor el remanente y quitar la coma que trae
	var remanente = document.getElementById('txt_remanente').value.replace(/,/g,'');
	//Sumar el presupuesto ingresado con el remanente
	var suma = parseInt(presupuesto) + parseInt(remanente);
	//Regresar el numero con el formato de moneda al campo de texto indicado
	formatCurrency(suma,'txt_Presupuesto');
}

/*Esta funcion quita el mensaje colocado en una caja de texto cuando se le da clic*/
function borrarDato(campo){
	campo.value = "";
}

/*Esta función calcula la diferencia entre la cantidad entregada y el gasto total*/
function calcDiferencia(campo,num){
	var totalGastos = parseInt(campo.value.replace(/,/g,''));
	var cantEntregada = parseInt(document.getElementById("hdn_cantEntregada"+num).value.replace(/,/g,''));
	var diferencia = cantEntregada - totalGastos;
	//Regresar el numero con el formato de moneda al campo de texto indicado
	formatCurrency(diferencia,'txt_dif'+num);
}

/*Esta función valida que el no. de factura y la cantidad del gasto total sea ingresada y que sea mayor a 0*/
function verContFormEditarMovimiento(frm_editarMovimiento,num){
	//6 es el numero del boton de opciones dentro del formulario
	if(frm_editarMovimiento[7].value=="Guardar"){		
		//Si el valor se mantiene en 1, entonces todos los campos fuero proporcionados por el usuario
		var band = 1;
	
		//Verificar que la Factura sea Proporcionada, 3 es la ubicacion de la caja de texto txt_factura dentro del formulario
		if(frm_editarMovimiento[3].value==""){			
			band = 0;
			alert ("Introducir el No. de Factura");
		}
		else{
			//Verificar que la Descripcion del Movimiento no este vacio, 4 es la ubicacion del area de texto txa_descripcion dentro del formulario
			if(frm_editarMovimiento[4].value==""){
				band = 0;
				alert ("Introducir la Descrición del Movimiento");
			}
			else{			
				//Verificar que el importe del gasto total no este vacio, 6 es la ubicacion de la caja de texto txt_totalGastos dentro del formulario
				if(frm_editarMovimiento[5].value=="" || frm_editarMovimiento[6].value=="Total Gastos..."){
					band = 0;
					alert ("Introducir el Monto Total de los Gastos");
				}
			}
		}
	
	
		//Validar que el costo sea un numero valido y mayor a 0, 5 es la ubicacion de la caja de texto txt_totalGastos dentro del formulario
		if(band==1){
			if(!validarEnteroValorCero(frm_editarMovimiento[6].value.replace(/,/g,''),"El Monto del Gasto Total"))
				band = 0;
		}
	
		if(band==1)
			return true;
		else
			return false;
	}//Cierre if(frm_editarMovimiento.sbt_opciones.value=="guardar")
	else{	
		document.getElementById("txt_dif"+num).disabled = false;
		document.getElementById("txt_totalGastos"+num).disabled = false;
		document.getElementById("txt_factura"+num).disabled = false;
		document.getElementById("txa_descripcion"+num).disabled = false;
		document.getElementById("txt_totalGastos"+num).value = "Total Gastos...";
		document.getElementById("sbt_opciones"+num).value = "Guardar";
		return false;//Regresar falso cuando el boton se llame editar y le sea dado un clic	
	}
}


/*****************************************************************************************************************************************************************************************/
/******************************************************************************REGISTRAR VENTA********************************************************************************************/
/*****************************************************************************************************************************************************************************************/
//Validar los datos del formulario Registrar Venta
function valFormRegistrarDetalleVenta(frm_detallesVenta){	
	
	//Si el valor se mantiene en 1, entonces todos los campos fueron proporcionados por el usuario
	var band = 1;
	
	if(frm_detallesVenta.txt_unidad.value==""){
		band=0;
		alert("Introducir la Unidad del Concepto");
	}
	else{
		if(frm_detallesVenta.txt_cantidad.value==""){
			band=0;
			alert("Introducir la Cantidad del Concepto");
		}
		else{
			if(frm_detallesVenta.txa_descripcion.value==""){
				band=0;
				alert("Introducir la Descripcion de la Venta");
			}
			else{
				if(frm_detallesVenta.txt_precio.value==""){
					band=0;
					alert("Introducir el Precio Unitario");
				}								
			}//If del Descripcion
		}//If de la Cantidad
	}//If de la Unidad
 	
	
	if(band==1){
		if(validarEntero(frm_detallesVenta.txt_cantidad.value.replace(/,/g,''),"La Cantidad Entregada")){
			if(!validarEntero(frm_detallesVenta.txt_precio.value.replace(/,/g,''),"El Precio Unitario"))
				band = 0;
		}
		else
			band = 0;
	}
	
	
	if(band==1)
		return true;
	else
		return false;
	
}	


//Validar los datos del formulario Registrar Venta
function valFormRegistrarVenta(frm_registrarVenta){

	//Si el valor se mantiene en 1, entonces todos los campos fuero proporcionados por el usuario
	var band = 1;
	//Verificar que sea seleccionado un valor del campo Cliente
	if(frm_registrarVenta.cmb_factura.value==""){		
		band = 0;
		alert ("Seleccionar si el Cliente Requiere Factura");
	}							
	else{
		//Verificar que el campo de quien Reviso no esta vacio
		if(frm_registrarVenta.cmb_cliente.value==""){		
			band = 0;
			alert ("Seleccionar un Cliente");
		}
		else{
			//Verificar que el campo de quien Reviso no esta vacio
			if(frm_registrarVenta.txt_vendio.value==""){
				band = 0;	
				alert ("Introducir el Nombre de la Persona que Realizo la Venta");
			}
			else{
				//Verificar que sea seleccionado el Medio de Venta
				if(frm_registrarVenta.cmb_medioVenta.value==""){
					band = 0;	
					alert ("Seleccione el Medio por el que fue realizada la Venta");
				}
				else{
					//Verificar que el campo de quien Autorizo no esta vacio
					if(frm_registrarVenta.txt_autorizo.value==""){
						band = 0;	
						alert ("Introducir el Nombre de la Persona que Autorizó");									
					}											
				}//Else de Cliente
			}//Else de Vendio
		}//Else de Medio de Venta 
	}//Else  de la Autorización
	

	if(frm_registrarVenta.txt_nomCliente.disabled==false && band==1){
		//Verificar que el campo de Cliente no este vacio
		if(frm_registrarVenta.txt_nomCliente.value==""){		
			band = 0;
			alert ("Ingresar Nombre del Cliente");
		}							
		else{
			//Verificar que el campo de la Direccion no esta vacio
			if(frm_registrarVenta.txt_direccion.value==""){		
				band = 0;
				alert ("Ingresar la Dirección del Cliente");
			}
		}//Else de la Direccion
	}

	if(band==1)
		return true;
	else
		return false;
}


function habilitarCampos(campo){
	if(campo.value=="PUBLICOGRAL"){
		document.getElementById("lbl_nomCliente").style.visibility="visible";
		document.getElementById("lbl_dir").style.visibility="visible";
		document.getElementById("txt_nomCliente").style.visibility="visible";
		document.getElementById("txt_direccion").style.visibility="visible";
		document.getElementById("txt_nomCliente").disabled=false;
		document.getElementById("txt_direccion").disabled=false;
	}
	else{
		document.getElementById("lbl_nomCliente").style.visibility="hidden";
		document.getElementById("lbl_dir").style.visibility="hidden";
		document.getElementById("txt_nomCliente").style.visibility="hidden";
		document.getElementById("txt_direccion").style.visibility="hidden";
		document.getElementById("txt_nomCliente").disabled=true;
		document.getElementById("txt_direccion").disabled=true;	
	}
}
/*****************************************************************************************************************************************************************************************/
/*********************************************************************************REGISTRAR PEDIDO****************************************************************************************/
/*****************************************************************************************************************************************************************************************/

//Validar que los datos de detalle sean introducidos correctamente
function valFormRegistrarDetallePedido(frm_detallePedido){
	//Si el valor se mantiene en 1, entonces todos los campos fueron proporcionados por el usuario
	var band = 1;
	
	//Verificar que ningún campo del formulario este vacío
	if(frm_detallePedido.txt_pedido.value==""){
		band=0;
		alert("Introducir la Clave del Pedido");
	}
	
	if(frm_detallePedido.txa_descripcion.value==""&&band==1){
		band=0;
		alert("Introducir la Descripcion del Pedido");
	}
	
	if(frm_detallePedido.txt_requisicion.value==""&&band==1){
		band=0;
		alert("Introducir la Requisición del Pedido");
	}
	
	if(frm_detallePedido.txt_preciouni.value==""&&band==1){
		band=0;
		alert("Introducir el Precio Unitario");
	}
	
	if(frm_detallePedido.cmb_unidad.value==""&&band==1){
		band=0;
		alert("Introducir la Unidad del Concepto");
	}
	
	if(frm_detallePedido.txt_cantidad.value==""&&band==1){
		band=0;
		alert("Introducir la Cantidad del Concepto");
	}		
	
	
	//Verificar que los datos numericos sean datos numericos validos
	if(band==1){
		if(!validarEntero(frm_detallePedido.txt_preciouni.value.replace(/,/g,''),"El Precio Unitario"))
			band=0;
	}
	if(band==1){
		if(!validarEntero(frm_detallePedido.txt_cantidad.value.replace(/,/g,''),"La Cantidad"))
			band=0;
	}
	
	//Verificar que el numero de Requisicion introducido sea Valido
	if(band==1){
		if(document.getElementById("hdn_claveValida").value=="no"){
			alert("Verificar la Clave de la Requisición Proporcionada");
			band=0;
		}
	}
	
	if(frm_detallePedido.txt_descto.value=="0.00"&&band==1){
		if(confirm("El Porcentaje de Descuento es %0.00. \nSi los precios Incluyen Descuento es Necesario Especificarlo ahora. \nPresionar Aceptar para Ingresar Porcentaje de Descuento"))
			band=0;
	}
	
	if(band==1)
		return true;
	else
		return false;
	
}

//Validar los datos del formulario de Registrar Pedido
function valFormRegistrarPedido(frm_registrarPedido){
	//Si el valor se mantiene en 1, entonces todos los campos fueron proporcionados por el usuario
	var band = 1;
	
	if(frm_registrarPedido.txt_plazo.value==""){
		band = 0;
		alert ("Introducir el Plazo del Pedido");
		frm_registrarPedido.txt_plazo.focus();
	}
	if(frm_registrarPedido.cmb_plazo.value=="" && band==1){
		band = 0;
		alert ("Seleccionar el Plazo del Pedido");
		frm_registrarPedido.cmb_plazo.focus();
	}
	if(frm_registrarPedido.cmb_solicito.value=="" && band==1){
		band = 0;
		alert ("Seleccionar el Solicitante del Pedido");
		frm_registrarPedido.cmb_solicito.focus();
	}
	if(frm_registrarPedido.txt_reviso.value=="" && band==1){
		band = 0;
		alert ("Introducir quien revisa el Pedido");
		frm_registrarPedido.txt_reviso.focus();
	}
	if(frm_registrarPedido.cmb_condPago.value=="" && band==1){
		band = 0;
		alert ("Seleccionar la condicion de Pago del Pedido");
		frm_registrarPedido.cmb_condPago.focus();
	}
	if(frm_registrarPedido.txt_autorizo.value=="" && band==1){
		band = 0;
		alert ("Introducir quien autorizo el Pedido");
		frm_registrarPedido.txt_autorizo.focus();
	}
	if(frm_registrarPedido.cmb_viaPed.value=="" && band==1){
		band = 0;
		alert ("Selecionar el medio por el que se realizo el Pedido");
		frm_registrarPedido.cmb_viaPed.focus();
	}
	if(frm_registrarPedido.cmb_tipoMoneda.value=="" && band==1){
		band = 0;
		alert ("Selecionar el tipo de moneda del Pedido");
		frm_registrarPedido.cmb_tipoMoneda.focus();
	}
	if(frm_registrarPedido.cmb_proveedor.value=="" && band==1){
		band = 0;
		alert ("Selecionar el Proveedor del Pedido");
		frm_registrarPedido.cmb_proveedor.focus();
	}
	
	if(band == 1){
		if(confirm(
					//var prov = frm_registrarPedido.cmb_proveedor;
					"EL PEDIDO CONTIENE LOS SIGUIENTES DATOS:\n\n"+
					"--------------------------------------------------------------------------------\n"+
					"SUBTOTAL: "+frm_registrarPedido.txt_subtotal.value+"\n"+
					"IVA: "+frm_registrarPedido.txt_iva.value+"\n"+
					"TOTAL: "+frm_registrarPedido.txt_total.value+"\n"+
					"PLAZO DE ENTREGA: "+frm_registrarPedido.txt_plazo.value+" "+frm_registrarPedido.cmb_plazo.value+"\n"+
					"SOLICITO: "+frm_registrarPedido.cmb_solicito.value+"\n"+
					"REVISO: "+frm_registrarPedido.txt_reviso.value+"\n"+
					"CONDICION DE PAGO: "+frm_registrarPedido.cmb_condPago.value+"\n"+
					"AUTORIZO: "+frm_registrarPedido.txt_autorizo.value+"\n"+
					"VIA DEL PEDIDO: "+frm_registrarPedido.cmb_viaPed.value+"\n"+
					"PROVEEDOR: "+frm_registrarPedido.cmb_proveedor.options[frm_registrarPedido.cmb_proveedor.selectedIndex].text+"\n"+
					"MONEDA: "+frm_registrarPedido.cmb_tipoMoneda.value+"\n"+
					"--------------------------------------------------------------------------------\n\n"+
					"¿DESEA CONTINUAR CON EL REGISTRO DEL PEDIDO?"
				  )
		){ band = 1; } else { band = 0; }
	}
	
	if(band==1)
		return true;
	else
		return false;
}

//Funcion que valida que al consultar un pedido, se hayan seleccionado los datos
function valFormConsultaPedido(formulario){
	var band = 1;

	if(formulario.cmb_departamento.value==""){
		band=0;
		alert ("Seleccionar un Departamento");
	}
	
	if (band==1){
		//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
		var iniDia=formulario.txt_fechaPed1.value.substr(0,2);
		var iniMes=formulario.txt_fechaPed1.value.substr(3,2);
		var iniAnio=formulario.txt_fechaPed1.value.substr(6,4);
	
		//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
		var finDia=formulario.txt_fechaPed2.value.substr(0,2);
		var finMes=formulario.txt_fechaPed2.value.substr(3,2);
		var finAnio=formulario.txt_fechaPed2.value.substr(6,4);

		//Unir los datos para crear la cadena de Fecha leida por Javascript
		var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
		var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
		//Convertir la cadena a formato valido para JS
		fechaIni=new Date(fechaIni);
		fechaFin=new Date(fechaFin);

		//Verificar que el año de Fin sea mayor al de Inicio
		if(fechaIni>fechaFin){
			band=0;
			alert ("La Fecha de Inicio no puede ser mayor a la Fecha de Fin");
		}
	}	
	
	if(band==1)
		return true;
	else
		return false;
}


/*Esta funcion validad el formulario de Consultar Lista de Pedidos*/
function valFormConsultaPedido2(frm_consultadePedido2){
	var band = 1;
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_consultadePedido2.txt_fechaPed3.value.substr(0,2);
	var iniMes=frm_consultadePedido2.txt_fechaPed3.value.substr(3,2);
	var iniAnio=frm_consultadePedido2.txt_fechaPed3.value.substr(6,4);

	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_consultadePedido2.txt_fechaPed4.value.substr(0,2);
	var finMes=frm_consultadePedido2.txt_fechaPed4.value.substr(3,2);
	var finAnio=frm_consultadePedido2.txt_fechaPed4.value.substr(6,4);

	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;

	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	//Verificar que el año de Fin sea mayor al de Inicio
	if(fechaIni>fechaFin){
		band = 0;
		alert ("La Fecha de Inicio no puede ser mayor a la Fecha de Fin");
	}
	
	
	if(band==1)
		return true;
	else
		return false;
}

//Funcion que revisa que los datos de un pedido a partir de una requisicion sean complementados
function valFormDetallesPedido1(formulario){
	var band=1;
	cant=formulario.cant_ckbs.value;
	var registros=0;
	for (i=1;i<=cant;i++){
		if (document.getElementById("ckb_pieza"+i).checked){
			/*if (document.getElementById("cmb_con_cos"+i).value==""){
				alert("Introducir el Control de Costos: "+i);
				band=0;
				break;
			}
			if (document.getElementById("cmb_cuenta"+i).value==""){
				alert("Introducir la Cuenta: "+i);
				band=0;
				break;
			}
			if (document.getElementById("cmb_subcuenta"+i).value==""){
				alert("Introducir la Subcuenta: "+i);
				band=0;
				break;
			}*/
			if (document.getElementById("hdn_cantReq"+i).value=="" || document.getElementById("hdn_cantReq"+i).value==0 && band==1){
				alert("Introducir Cantidad de Material del Registro: "+i);
				band=0;
				document.getElementById("hdn_cantReq"+i).focus();
				break;
			}
			if (document.getElementById("descMat"+i).value=="" && band==1){
				alert("Introducir Descripcion de Material del Registro: "+i);
				band=0;
				document.getElementById("descMat"+i).focus();
				break;
			}
			if (document.getElementById("txt_precio"+i).value=="" || document.getElementById("txt_precio"+i).value=="0.00" && band==1){
				alert("Introducir Precio Unitario del Registro: "+i);
				band=0;
				document.getElementById("txt_precio"+i).focus();
				break;
			}
		}
		else{
			registros++;
		}
	}

	//Si registros es igual a cant, significa que no se ha seleccionado ningun trabajador
	if (registros==cant && band==1){
		alert("Seleccionar un Material");
		band=0;
	}
	
	if(band==1){
		if(confirm('Presione Aceptar SI los Precios Introducidos Incluyen IVA, \nDe lo Contrario Presione Cancelar')) 
			formulario.hdn_iva.value = 'SI'; 
		else 
			formulario.hdn_iva.value='NO';
	}
	
	//Verificar que el proceso de validacion se haya cumplido satisfactoriamente
	if (band==1)
		return true;
	else
		return false;
}//Cierre de la funcion valFormDetallesPedido1(formulario)


//Funcion que permite agregar una nueva opcion, no existente a un combo box (Combo de Unidad de Medida de la Pagina de Detalle del Pedido)
function agregarNvaUnidad(comboBox){
	//Si la opcion seleccionada es agregar nueva unidad ejecutar el siguiete codigo
	if(comboBox.value=="NUEVA"){
		var nvaMedida = "";
		var condicion = false;
		do{
			nvaMedida = prompt("Introducir Nueva Unidad de Medida","Nueva Unidad de Medida...");
			if(nvaMedida=="Nueva Unidad de Medida..." ||  nvaMedida=="")
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
				alert("La Unidad Ingresada ya esta Registrada \n en las Opciones de la Lista de Unidades");
				comboBox.value = nvaMedida;
			}
		}// FIN if(nvaMedida!= null)
		
		else if(nvaMedida== null){
			comboBox.value = "";	
		}
	}// FIN if(comboBox.value=="NUEVA")
}

//Funcion que permite agregar una nueva opcion, no existente a un combo box (Combo de Equipos de la Pagina de Detalle del Pedido)
function agregarNvoEquipo(comboBox){
	//Si la opcion seleccionada es agregar nueva unidad ejecutar el siguiente codigo
	if(comboBox.value=="NUEVO"){
		var nvoEquipo = "";
		var condicion = false;
		do{
			nvoEquipo = prompt("Introducir Nuevo Equipo","Nuevo Equipo...");
			if(nvoEquipo=="Nuevo Equipo..." ||  nvoEquipo=="" || (nvoEquipo!=null && nvoEquipo.length>15)){
				condicion = true;
				if (nvoEquipo!=null && nvoEquipo.length>15)
					alert("Solo se permite un máximo de 15 carácteres");
			}
			else
				condicion = false;
		}while(condicion);
		
		//Si el usuario presiono cancelar no se relaiza ninguna actividad de lo contrario asignar la nueva opcion al combo
		if(nvoEquipo!=null){
			//Convertir a mayusculas la opcion dada
			nvoEquipo = nvoEquipo.toUpperCase();
			//variable que nos ayudara a saber si la nueva opcion ya esta registrada en el combo
			var existe = 0;
			
			for(i=0; i<comboBox.length; i++){
				//verificar que la nueva opcion no se encuentre dentro de las opciones actuales del combo
				if(comboBox.options[i].value==nvoEquipo)
					existe = 1;
			} //FIN for(i=0; i<comboBox.length; i++)
			
			//Si la nva opcion no esta registrada agregarla como una adicional y preseleccionarla
			if(existe==0){
				//Agregar al final la nueva opcion seleccionada
				comboBox.length++;
				comboBox.options[comboBox.length-1].text = nvoEquipo;
				comboBox.options[comboBox.length-1].value = nvoEquipo;
				//Preseleccionar la opcion agregada
				comboBox.options[comboBox.length-1].selected = true;
			} // FIN if(existe==0)
			
			else{
				alert("El Equipo "+nvoEquipo+" Ingresado ya esta Registrado \nen las Opciones de la Lista de Equipos");
				comboBox.value = nvoEquipo;
			}
		}// FIN if(nvoEquipo!= null)
		
		else if(nvoEquipo== null){
			comboBox.value = "";	
		}
	}// FIN if(comboBox.value=="NUEVO")
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

//Funcion que al seleccionar un check, activa el registro para ingresar los datos
function elegirMaterial(no){
	//Obtener a referencia para cada elemento
	check=document.getElementById("ckb_pieza"+no);
	campo_cantidad=document.getElementById("hdn_cantReq"+no);
	unidad=document.getElementById("txt_uniMat"+no);
	descripcion=document.getElementById("descMat"+no);
	equipo=document.getElementById("cmb_equipos"+no);
	precio=document.getElementById("txt_precio"+no);
	importe=document.getElementById("txt_importe"+no);
	control=document.getElementById("cmb_con_cos"+no);
	cuenta=document.getElementById("cmb_cuenta"+no);
	subcuenta=document.getElementById("cmb_subcuenta"+no);
	//Si el checkbox esta checado, habilitar los campos que corresponden al registro
	if(check.checked){
		equipo.disabled=false;
		campo_cantidad.readOnly=false;
		unidad.readOnly=false;
		descripcion.readOnly=false;
		control.disabled=false;
		cuenta.disabled=false;
		subcuenta.disabled=false;
		precio.readOnly=false;
		importe.readOnly=false;
		cantidad=document.getElementById("hdn_cantidad").value;
		sumaImporte=0;
		
		/*****Inicio Calcular la nueva suma del importe*****/
		cantidad=document.getElementById("hdn_cantidad").value;
		sumaImporte=0;
		ctrl=1;
		do{
			if(document.getElementById("ckb_pieza"+ctrl).checked)
				sumaImporte+=parseFloat(document.getElementById("txt_importe"+ctrl).value.replace(/,/g,''));
			ctrl++;
		}while(ctrl<cantidad);
		formatCurrency(sumaImporte,'txt_subtotal');
		precio.focus();
		precio.select();
	}
	//Si se quita el check, deshabilitar y vaciar los valores
	else{
		/*****Inicio Calcular la nueva suma del importe*****/
		cantidad=document.getElementById("hdn_cantidad").value;
		sumaImporte=0;
		ctrl=1;
		do{
			if(document.getElementById("ckb_pieza"+ctrl).checked)
				sumaImporte+=parseFloat(document.getElementById("txt_importe"+ctrl).value.replace(/,/g,''));
			ctrl++;
		}while(ctrl<cantidad);
		formatCurrency(sumaImporte,'txt_subtotal');
		
		equipo.disabled=true;
		campo_cantidad.readOnly=true;
		unidad.readOnly=true;
		descripcion.readOnly=true;
		control.disabled=true;
		cuenta.disabled=true;
		subcuenta.disabled=true;
		precio.readOnly=true;
		importe.readOnly=true;
		//Quitar las comas "," de la caja de subtotal y de importe
		//Formatear el resultado y asignarlo a la caja del subtotal
		//formatCurrency((subtotal-imp),'txt_subtotal');
	}
}//Fin de function elegirMaterial(no)

function elegirMaterialMod(no){
	//Obtener a referencia para cada elemento
	check=document.getElementById("ckb_pieza"+no);
	cantidad=document.getElementById("hdn_cantReq"+no);
	descripcion=document.getElementById("descMat"+no);
	equipo=document.getElementById("cmb_equipos"+no);
	precio=document.getElementById("txt_precio"+no);
	importe=document.getElementById("txt_importe"+no);
	control=document.getElementById("cmb_con_cos"+no);
	cuenta=document.getElementById("cmb_cuenta"+no);
	subcuenta=document.getElementById("cmb_subcuenta"+no);
	//Si el checkbox esta checado, habilitar los campos que corresponden al registro
	if(check.checked){
		equipo.disabled=false;
		cantidad.readOnly=false;
		descripcion.readOnly=false;
		control.disabled=false;
		cuenta.disabled=false;
		subcuenta.disabled=false;
		precio.readOnly=false;
		importe.readOnly=false;
	}
	//Si se quita el check, deshabilitar y vaciar los valores
	else{
		equipo.disabled=true;
		cantidad.readOnly=true;
		descripcion.readOnly=true;
		control.disabled=true;
		cuenta.disabled=true;
		subcuenta.disabled=true;
		precio.readOnly=true;
		importe.readOnly=true;
	}
}//Fin de function elegirMaterial(no)

//Funcion que valida el dato de Descuento Ingresado
function validarDescto(cajaDescto){
	//obtener el valor del Descuento
	descto=cajaDescto.value;
	if(descto>100){
		alert("Ingresar Porcentaje de Descuento Válido");
		cajaDescto.value="0.00";
	}
	else
		cajaDescto.readOnly=true;
}//Fin de function validarDescto(cajaDescto)

//Funcion que calcula el Descuento sobre el pedido, ingresando primero los precios unitarios y por ultimo el valor de Descuento
function calcularDesctoSobrePedido(noReg){
	cantidad=document.getElementById("hdn_cantidad").value;
	pctjeDescto=document.getElementById("txt_descto").value.replace(/,/g,'');
	cantidad--;
	sumaImporte=0;
	ctrl=1;
	do{
		if(document.getElementById("ckb_pieza"+ctrl).checked){
			precioU=document.getElementById("txt_precio"+ctrl).value.replace(/,/g,'');
			descto=precioU*(pctjeDescto/100);
			//Formatear el resultado y asignarlo a la caja correspondiente por partida
			formatCurrency((precioU-descto),'txt_precio'+ctrl);
			//obtener la cantidad Requisitada
			cantReq=document.getElementById("hdn_cantReq"+ctrl).value.replace(/,/g,'');
			//Multiplicar el precio de la partida por la cantidad requisitada
			formatCurrency(((precioU-descto)*cantReq),'txt_importe'+ctrl);
			//Acumular la Suma del Importe
			sumaImporte+=((precioU-descto)*cantReq);
		}
		ctrl++;
	}while(ctrl<=cantidad);
	//Formatear el total de Importe y asignarlo a la caja de Importe
	formatCurrency(sumaImporte,'txt_subtotal');
}//Fin de function calcularDesctoSobrePedido(noReg)

//Funcion que calcula el descuento partida a partida de forma individual
//ingresando primero el descuento y luego los precios unitarios por partida
function operacionesPedido(noReg,tipoOp){
	//obtener la cantidad Requisitada
	cantReq=document.getElementById("hdn_cantReq"+noReg).value.replace(/,/g,'');
	//Obtener el porcentaje de Descuento
	pctjeDescto=document.getElementById("txt_descto").value.replace(/,/g,'');
	//Verificar si es por Precio Unitario o por Importe
	if(tipoOp=="uni"){
		//obtener el precio unitario
		precioU=document.getElementById("txt_precio"+noReg).value.replace(/,/g,'');
		//Calcular cuanto se debe descontar segun el porcentaje de descuento
		descto=precioU*(pctjeDescto/100);
		//Asignar el nuevo valor de Precio Unitario con el descuento calculado
		precioU=(precioU-descto);
		formatCurrency(precioU,'txt_precio'+noReg);
		//Asignar el nuevo valor de Importe con el descuento calculado
		importe=precioU*cantReq;
		formatCurrency(importe,'txt_importe'+noReg);
	}
	else{
		//obtener el precio unitario
		importe=document.getElementById("txt_importe"+noReg).value.replace(/,/g,'');
		subtotal=importe/cantReq;
		formatCurrency(subtotal,'txt_precio'+noReg);
		/*
		//Calcular cuanto se debe descontar segun el porcentaje de descuento
		descto=1+(pctjeDescto/100);
		*/
	}
	/*****Inicio Calcular la nueva suma del importe*****/
	//Cantidad de Cajas de Texto
	cantidad=document.getElementById("hdn_cantidad").value;
	sumaImporte=0;
	ctrl=1;
	do{
		if(document.getElementById("ckb_pieza"+ctrl).checked)
			sumaImporte+=parseFloat(document.getElementById("txt_importe"+ctrl).value.replace(/,/g,''));
		ctrl++;
	}while(ctrl<cantidad);
	/*****Fin Calcular la nueva suma del importe*****/
	//Reasignar el Subtotal
	formatCurrency(sumaImporte,'txt_subtotal');
}//Cierre de function operacionesPedido(noReg)

//Funcion para sumar el Importe en el formulario Detalles Pedido
function sumaImporte(cant_ckbs,cajaImporte){
	//Obtener la cantidad de checkbox
	var cant=cant_ckbs.value;
	//Variable para recorrer cada check
	var cont=1;
	//Variable para acumular el total de los importes
	var subtotal=0;
	do{
		//Obtener el valor de cada caja de Importe
		importe=document.getElementById("txt_importe"+cont).value;
		//Si el importe es diferente de vacio, sumarlo, de lo contrario, no hacer nada
		if(importe!=""){
			//Extraer el valor del Precio Unitario como numero sin comas
			importe=parseFloat(importe.replace(/,/g,''));
			//Acumular el Precio Unitario en la variable del subtotal
			subtotal+=importe;
		}
		//Incrementar el contador
		cont++;
	}while(cont<=cant);
	//Formatear el resultado y asignarlo a la caja del subtotal
	formatCurrency(subtotal,"txt_subtotal");
}//Cierre de function sumaImporte()

//Funcion que restablece los campos del detalle Pedido a READ y DISABLED
function restablecerCamposPedidoRead(cant_ckbs){
	//Obtener la cantidad de checkbox
	var cant=cant_ckbs.value;
	//Variable para recorrer cada elemento a deshabilitar
	var cont=1;
	do{
		//Restablecer cada campo de Precio a modo Read
		document.getElementById("txt_precio"+cont).readOnly=true;
		//Restablecer cada combo de Equipos a modo Disabled
		document.getElementById("cmb_equipos"+cont).disabled=true;
		//Restablecer cada campo de Importe a modo Read
		document.getElementById("txt_importe"+cont).readOnly=true;
		//Incrementar el contador
		cont++;
	}while(cont<=cant);
	document.getElementById("txt_descto").readOnly=false;
}

//Funcion que asigna o no el IVA dependiendo de lo seleccionado en la ventana de dialogo de confirmacion
function verIVA(){
	if (confirm('Presione Aceptar SI los Precios Introducidos Incluyen IVA, \nDe lo Contrario Presione Cancelar')){
		document.frm_detallePedido.action='frm_registrarPedido.php?btn=sbt_finalizar&hdn_iva=SI';
		document.frm_detallePedido.submit();
	}
	else{
		document.frm_detallePedido.action='frm_registrarPedido.php?btn=sbt_finalizar&hdn_iva=NO';
		document.frm_detallePedido.submit();
	}
}//Fin de function verIVA

//Funcion que asigna o no el IVA dependiendo de lo seleccionado en la ventana de dialogo de confirmacion
function verIVA2(){
	if (confirm('Presione Aceptar SI los Precios Introducidos Incluyen IVA, \nDe lo Contrario Presione Cancelar')){
		document.frm_detallePedido.action='frm_registrarPedido2.php?btn=sbt_finalizar&hdn_iva=SI';
		document.frm_detallePedido.submit();
	}
	else{
		document.frm_detallePedido.action='frm_registrarPedido2.php?btn=sbt_finalizar&hdn_iva=NO';
		document.frm_detallePedido.submit();
	}
}//Fin de function verIVA

/*****************************************************************************************************************************************************************************************/
/*********************************************************************************CONSULTAR PEDIDO****************************************************************************************/
/*****************************************************************************************************************************************************************************************/
//Validar los datos del formulario Registrar Compras
function valFormConsultaDetallePedido(frm_consultaDetallePedido){
	//Variable para controlar la validacion
	var band = 1;
	var flag=0;
	var cantidad=document.getElementsByName("rdb_idPedido").length;
	for (var i=0;i<cantidad;i++){
		if (document.getElementById("rdb_idPedido"+(i+1)).checked==true){
			flag=1;
		}
	}
	
	if (flag==0){
		alert("Seleccionar un Pedido para Consultar su Detalle");
		band=0;
	}
	
	if(band==1)
		return true;
	else
		return false;
}

/*****************************************************************************************************************************************************************************************/
/******************************************************************************COMPLEMENTAR PEDIDO****************************************************************************************/
/*****************************************************************************************************************************************************************************************/
function valComplementarDatos(frm_complementarDatos){
	//Variable para controlar la validacion
	var band = 1;
	
	if (document.getElementById("txt_horaE").value==""){
			alert ("Especificar la Hora");
			band=0;
	}
	
	if(band==1)
		return true;
	else
		return false;
}

function mostrarOcultar(){
	if (document.getElementById("cmb_estado").value!="NO PAGADO"){
		document.getElementById("txt_fechaP").style.visibility='visible';
		document.getElementById("calendario_fin").style.visibility='visible';
		document.getElementById("fecha_pago").style.visibility='visible';
		
		if (document.getElementById("hdn_control").value!="")
			document.getElementById("txt_fechaP").value=document.getElementById("hdn_fecha").value;
		else
			document.getElementById("txt_fechaP").value=document.getElementById("txt_fechaE").value;
	}
	else{
		document.getElementById("txt_fechaP").style.visibility='hidden';
		document.getElementById("txt_fechaP").value="";
		document.getElementById("calendario_fin").style.visibility='hidden';
		document.getElementById("fecha_pago").style.visibility='hidden';
	}
}

function habilitarEnviar(form){
	if (form.cmb_estado.value!="NO PAGADO")
		form.sbt_guardar.disabled=false;
	if (form.cmb_estado.value=="NO PAGADO")
		form.sbt_guardar.disabled=true;
}

/*****************************************************************************************************************************************************************************************/
/*********************************************************************************REGISTRAR COMPRA****************************************************************************************/
/*****************************************************************************************************************************************************************************************/
//Validar los datos del formulario Registrar Compras
function valFormRegistrarCompras(frm_registrarCompras){
	var band = 1;
	//Verificar primero el campo de Nombre de la Compra no este vacio
	if(frm_registrarCompras.txt_claveCompra.value==""){
		band = 0;		
		alert ("Introducir la Clave de la Compra");
	}
	else{
		// verificar que el campo de Pedido no este vacio
		if(frm_registrarCompras.cmb_pedidos.value==""){
			band = 0;
			alert ("Seleccionar la Clave del Pedido");
		}
		else{
			// verificar que el campo de Requisición pedido no este vacio
			if(frm_registrarCompras.cmb_requisicion.value==""){
				band = 0;
				alert ("Seleccionar la Clave de la Requisición");
			}
			else{
				//Verificar que el campo Estado no este vacio
				if(frm_registrarCompras.cmb_pagado.value==""){
					band = 0;
					alert ("Seleccionar si la Compra registrada esta Pagada");
				}											
				else{
					//Verificar que el campo Hora de Entrega no este vacio
					if(frm_registrarCompras.txt_hora.value==""){
						band = 0;	
						alert ("Introducir la Hora");
					}									
					else{
						//Verificar que el campo del Meridiano no este vacio
						if(frm_registrarCompras.cmb_meridiano.value==""){
							band = 0;	
							alert ("Selecciona A.M ó P.M ");
						}
						else{
							//Verificar que el campo de Cantidad no este vacio
							if(frm_registrarCompras.txt_unidadMedida.value==""){
								band = 0;	
								alert ("Introducir la Cantidad del Producto");
							}
							else{
								//Verificar que sea seleccionada una opcion del campo Unidad de Medida 
								if(frm_registrarCompras.cmb_medida.value==""){
									band = 0;	
									alert ("Selecciona la Unidad de Medida");
								}
								else{
									//Verificar que el campo Nombre del Chofer no este vacio
									if(frm_registrarCompras.txt_nomChofer.value==""){
										band = 0;	
										alert ("Introducir el Nombre del Chofer");
									}
									else{
										//Verificar que el campo Nombre del Plantero no este vacio
										if(frm_registrarCompras.txt_nomPlantero.value==""){
											band = 0;	
											alert ("Introducir el Nombre del Plantero");
										}
										else{
											//Verificar que el campo Precios con Iva  no este vacio
											if(frm_registrarCompras.txt_precioconIva.value==""){
												band = 0;	
												alert ("Introducir Precios con Iva");
											}		
											else{
												//Verificar que el campo Precios sin Iva  no este vacio
												if(frm_registrarCompras.txt_preciosinIva.value==""){
													band = 0;	
													alert ("Introducir Precios sin Iva");
												}//Else Precio sin IVA
											}//Else Precio con IVA
							 			}//Else Nombre Plantero
								 	}//Else Nombre Chofer
								}//Else de la Medida
							}//Else Cantidad
						}//Else meridiano
					}//Else Hora de Llegada
				}//Else del Estado
			}//Else de al Requisición
		}//Else Clave del Pedido
	}//Else Clave de la Compra
		
	if(band==1)
		return true;
	else
		return false;
}
/*****************************************************************************************************************************************************************************************/
/************************************************************************************CONSULTAR REA****************************************************************************************/
/*****************************************************************************************************************************************************************************************/
//Validar los datos del formulario para consultar Reportes REA
function valFormConsultarREA(frm_datosReporteREA){
	var band = 1;
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_datosReporteREA.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_datosReporteREA.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_datosReporteREA.txt_fechaIni.value.substr(6,4);

	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_datosReporteREA.txt_fechaFin.value.substr(0,2);
	var finMes=frm_datosReporteREA.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_datosReporteREA.txt_fechaFin.value.substr(6,4);

	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;

	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	//Verificar que el año de Fin sea mayor al de Inicio
	if(fechaIni>fechaFin){
		band = 0;
		alert ("La Fecha de Inicio no puede ser mayor a la Fecha de Fin");
	}
	
	if(band==1)
		return true;
	else
		return false;
}

//Validar los datos del formulario para consultar los detalles de Reportes REA
function valFormConsultarREADetalle(frm_consultardetalleReA){
	//Variable para controlar la validacion
	var band = 1;
	var flag=0;
	var cantidad=document.getElementsByName("rdb_idREA").length;
	for (var i=0;i<cantidad;i++){
		if (document.getElementById("rdb_idREA"+(i+1)).checked==true){
			flag=1;
		}
	}
	
	if (flag==0){
		alert("Seleccionar un Reporte REA para Consultar su Detalle");
		band=0;
	}
	
	if(band==1)
		return true;
	else
		return false;
}

//Validar los datos del formulario para consultar los detalles de Reportes REA
function valFormConsultarREADetallePro(frm_consultardetalleReAPro){
	//Variable para controlar la validacion
	var band = 1;
	var flag=0;
	var cantidad=document.getElementsByName("rdb_idREAProveedor").length;
	for (var i=0;i<cantidad;i++){
		if (document.getElementById("rdb_idREAProveedor"+(i+1)).checked==true){
			flag=1;
		}
	}
	
	if (flag==0){
		alert("Seleccionar un Concepto para Consultar su Detalle");
		band=0;
	}
	
	if(band==1)
		return true;
	else
		return false;
}


/*****************************************************************************************************************************************************************************************/
/******************************************************************************GENERAR REPORTES*******************************************************************************************/
/*****************************************************************************************************************************************************************************************/
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

/*Esta funcion se ecarga de validar los formularios para Generar el Reporte de Compras en las diferentes opciones proporcionadas*/
function verFormReportesCompras(form,opc){
	//Si la variable band se mantiene en 1, el proceso de validación se llevo a cabo con éxito
	band = 1;
	
	switch(opc){			
		case 1://Generar Reporte por Proveedor
			if(form.txt_razon.value==""){
				alert("Introducir el Nombre de un Proveedor");
				band = 0;
			}
			else{
				if(!validarFechas(form.txt_fechaIni.value,form.txt_fechaFin.value))
					band = 0;				
			}
		break;
		case 2://Generar Reporte por Fecha
			if(!validarFechas(form.txt_fechaIni.value,form.txt_fechaFin.value))
				band = 0;
		break;
		case 3://Generar Reporte por Costo
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
						alert("El Nivel Inferior no Puede ser Mayor al Nivel Superior");
						band = 0;
					}
				}
			}						
		break;
		case 4://Generar Reporte por Departamento
			if(form.cmb_departamento.value==""){
				alert("Seleccionar un Departamento");
				band = 0;
			}			
		break;
		case 5://Generar Reporte por Equipos
			if(form.cmb_familia.value==""){
				alert("Seleccionar una Familia");
				band = 0;
			}
			if (band==1 && !form.ckb_todo.checked){
				if(band==1 && form.cmb_equipos.value==""){
					alert("Seleccionar un Equipo");
					band = 0;
				}
			}
		break;
	}//Cierre Switch
	
		
	if(band==1)
		return true;
	else
		return false;	
}

function reporteEquipos(checkbox){
	if (checkbox.checked)
		document.getElementById("cmb_equipos").disabled=true;
	else
		document.getElementById("cmb_equipos").disabled=false;
}

/*Esta funcion se encarga de verificar cuando ha sido seleccionado el combo de publico general, para deshabilitar el campo de txt_cliente */
function valPublico(frm_rptClientes,texto){
	if  (document.frm_rptClientes.ckb_publicoGral.checked){
		document.getElementById("txt_cliente").value="";
		document.getElementById("txt_cliente").readOnly=true;
	}
	else{
		document.getElementById("txt_cliente").value="";
		document.getElementById("txt_cliente").readOnly=false;
	}
}



/*Esta funcion se encarga de validar los formularios para Generar el Reporte de Ventas en las diferentes opciones proporcionadas*/
function verFormReportesVentas(form,opc){
	//Si la variable band se mantiene en 1, el proceso de validación se llevo a cabo con éxito
	band = 1;
	
	switch(opc){			
		case 1://Generar Reporte por Proveedor
			if(!form.ckb_publicoGral.checked){
				if(form.txt_cliente.value==""){
					alert("Introducir el Nombre de un Cliente");
					band = 0;
				}
				else{
					if(!validarFechas(form.txt_fechaIni.value,form.txt_fechaFin.value))
						band = 0;				
				}
			}
			else{
				if(!validarFechas(form.txt_fechaIni.value,form.txt_fechaFin.value))
					band = 0;	
			}
		break;
		case 2://Generar Reporte por Fecha
			if(!validarFechas(form.txt_fechaIni.value,form.txt_fechaFin.value))
				band = 0;
		break;
		case 3://Generar Reporte por Costo
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
		case 4://Generar Reporte por Facturas
			if(form.cmb_factura.value==""){
				alert("Seleccionar como se Requiere el Reporte de Ventas ");
				band = 0;
			}
			else{
				if(!validarFechas(form.txt_fechaIni.value,form.txt_fechaFin.value))
					band = 0;				
			}
		break;
	}//Cierre Switch
	
	
	if(band==1)
		return true;
	else
		return false;	
}


/*Esta funcion valida el contenido del Formulario para Generar el Reporte de Compra-Venta*/
function verContFormCompraVenta(frm_rptCompraVenta){
	//Si la variable band se mantiene en 1, el proceso de validación se llevo a cabo con éxito
	band = 1;
	
	if(!validarFechas(frm_rptCompraVenta.txt_fechaIni.value,frm_rptCompraVenta.txt_fechaFin.value))
		band = 0;
	
	
	if(band==1)
		return true;
	else
		return false;
}



/*****************************************************************************************************************************************************************************************/
/******************************************************************************EXPORTAR CSV*******************************************************************************************/
/*****************************************************************************************************************************************************************************************/

function seleccionarTodo(checkbox){
	for(var i=0;i<document.frm_verClientes.elements.length;i++){
		//Variable
		elemento=document.frm_verClientes.elements[i];
		if (elemento.type=="checkbox")
			elemento.checked=checkbox.checked;
	}
}

function quitar(checkbox){
	if(!checkbox.checked)
		document.getElementById("ckb_todo").checked=false;
}

//Esta funcion sirve para verificar que se hayan seleccionado clientes para Exportar en el CSV
function valFormSeleccionarClientes(frm_verClientes){
	var band=1;
	var cantidad=0;
	var ctrl= 1;
	if(!document.getElementById("ckb_todo").checked){
		//Funcion For para obtener todos los elementos que son Checkbox en el formulario
		for(var i=0;i<document.frm_verClientes.elements.length;i++){
			//Variable
			elemento=document.frm_verClientes.elements[i];
			if (elemento.type=="checkbox")
				cantidad++;
		}
		while(ctrl<cantidad){	
			//Crear el id del CheckBox que se quiere verificar
			idCheckBox="ckb_"+ctrl.toString();
			//Verificar que de los checkbox al menos uno este seleccionado
			if(document.getElementById(idCheckBox).checked)
				status = 1;
			ctrl++;
		}//Fin del While	
	}
	else
		status=1;
	
	if (status!=1){
		band=0;
		alert ("Seleccionar uno o más Clientes");
	}
	
	if(band==1)	
		return true;
	else
		return false;
}


/*Esta funcion valida las Fechas del formulario de Exportar CSV*/
function valFormExportarCSV(frm_fechaCSV){
	var band = 1;
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_fechaCSV.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_fechaCSV.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_fechaCSV.txt_fechaIni.value.substr(6,4);

	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_fechaCSV.txt_fechaFin.value.substr(0,2);
	var finMes=frm_fechaCSV.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_fechaCSV.txt_fechaFin.value.substr(6,4);

	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;

	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	//Verificar que el año de Fin sea mayor al de Inicio
	if(fechaIni>fechaFin){
		band=0;
		alert ("La Fecha de Inicio no puede ser mayor a la Fecha de Fin");
	}
	
	
	if(band==1)
		return true;
	else
		return false;
}


/*************************************************************************************************************************************************/
/*******************************************************************REQUISICIONES*****************************************************************/
/*************************************************************************************************************************************************/
//Funcion que permite validar el formulario de consulta de las requisiciones por fecha
function valFormReqFecha(frm_buscarRequisiciones){
	//Variable bandera que permite revisar si la validación fue exitosa.
	var band = 1;
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_buscarRequisiciones.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_buscarRequisiciones.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_buscarRequisiciones.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_buscarRequisiciones.txt_fechaFin.value.substr(0,2);
	var finMes=frm_buscarRequisiciones.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_buscarRequisiciones.txt_fechaFin.value.substr(6,4);		
	
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
	if (band==1)
		return true;
	else
		return false;

}



//Validar los datos del formulario para consultar las requisiciones por estado
function valFormReqEdo(frm_buscarRequisiciones2){
	//Variable para controlar la validacion
	var band = 1;
	
	if (frm_buscarRequisiciones2.cmb_estadoBuscar.value==""&&band==1){
		alert("Seleccionar Estado");
		band=0;
	}
	
	if(band==1)
		return true;
	else
		return false;
}


/*************************************************************************************************************************************************/
/************************************************************** CONSULTAS DOCK *******************************************************************/
/*************************************************************************************************************************************************/

/*******************************************************************CONSULTA EMPLEADOS DOCK*******************************************************/
//Funcion que permite validar el formulario de consulta de empleados
function valFormConsultarEmpleado(frm_consultarEmpleados){
	//Variable bandera que permite revisar si la validación fue exitosa.
	var band = 1;
	
	//Extraer los datos de la fecha inicial, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var iniDia=frm_consultarEmpleados.txt_fechaIni.value.substr(0,2);
	var iniMes=frm_consultarEmpleados.txt_fechaIni.value.substr(3,2);
	var iniAnio=frm_consultarEmpleados.txt_fechaIni.value.substr(6,4);
	
	//Extraer los datos de la fecha de Fin, no se uso el replace porque en las fechas vienen diagonales, cosa que no se permite incluir en la sintaxis de replace
	var finDia=frm_consultarEmpleados.txt_fechaFin.value.substr(0,2);
	var finMes=frm_consultarEmpleados.txt_fechaFin.value.substr(3,2);
	var finAnio=frm_consultarEmpleados.txt_fechaFin.value.substr(6,4);		
	
	//Unir los datos para crear la cadena de Fecha leida por Javascript
	var fechaIni=iniMes+"/"+iniDia+"/"+iniAnio;
	var fechaFin=finMes+"/"+finDia+"/"+finAnio;
	
	//Convertir la cadena a formato valido para JS
	fechaIni=new Date(fechaIni);
	fechaFin=new Date(fechaFin);

	if (frm_consultarEmpleados.cmb_area.value==""){
		alert("Seleccionar Área");
		band=0;
	}
	if (frm_consultarEmpleados.cmb_consulta.value==""&&band==1){
		alert("Seleccionar Consulta");
		band=0;
	}
	//Verificar que la fecha de inicio no sea mayor a la de cierre
	if(fechaIni>fechaFin){
		band=0;
		alert ("La Fecha de Baja no puede ser Anterior a la Fecha de Ingreso");
	}
	if (band==1)
		return true;
	else
		return false;
}//Cierre valFormConsultarEmpleado(frm_consultarEmpleados)



/************************************************ CONSULTA OTSE DOCK *******************************************************/
//Función para validar las Fechas seleccionadas en el formulario
function valFormConsultarOTSE(frm_consultarOTSE){
	
	//Verificar si el CheckBox de Incluir Fechas en la Consulta esta seleccionado
	if(frm_consultarOTSE.ckb_incluirFechas.checked){
		if(validarFechas(frm_consultarOTSE.txt_fechaIni.value, frm_consultarOTSE.txt_fechaFin.value))
			return true
		else
			return false;
	}
	else{
		return true;//Regresar verdadero, ya que no hay mas elementos que validar
	}
	
}//Cierre de la función valFormConsultarOTSE(frm_consultarOTSE)


//Esta función abrirá una ventana emergente para complementar las Actividades de la Orden de Trabajo para Servicios Externos Seleccionada
function actividadesOTSE(idOrdenTrabajo){
	//Abrir una ventana emergente con las Actividades de Orden Seleccionada
	vtnRegCostoActividades = window.open("verComplementarCostosOTSE.php?idOrden="+idOrdenTrabajo,
				"_blank", "top=100, left=100, width=850, height=620, status=no, menubar=yes, resizable=yes, scrollbars=yes,toolbar=no, location=no, directories=no");		
}//Cierre de la función complementarOTSE(idOrdenTrabajo)

//Esta función abrirá una ventana emergente para complementar los Materiales de la Orden de Trabajo para Servicios Externos Seleccionada
function materialesOTSE(idOrdenTrabajo){
	//Abrir una ventana emergente con los Materiales de Orden Seleccionada
	window.open("verComplementarMaterialesOTSE.php?idOrden="+idOrdenTrabajo,
				"_blank", "top=100, left=100, width=850, height=620, status=no, menubar=yes, resizable=yes, scrollbars=yes,toolbar=no, location=no, directories=no");		
}//Cierre de la función complementarOTSE(idOrdenTrabajo)

//Esta función recibe los parametros necesarios para abrir el PDF que muestra la Orden de Trabajo para Servicios Externos
function abrirPdfOtse(idOrdenTrabajo,area,fechaRegistro){	
	//Abrir el PDF de la Orden Seleccionada
	window.open("../../includes/generadorPDF/ordenServicioExterno.php?id_orden="+idOrdenTrabajo+"&nom_depto="+area+"&fecha_reg="+fechaRegistro,
				"_blank", "top=100, left=100, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no");
	
}//Cierre de la función abrirPdfOtse(idOrdenTrabajo,area,fechaRegistro)


//Esta función activara la caja de texto donde se colocará el costo de la actividad seleccionada en la sección de Complementar OTSE
function activarCajaCosto(checkBox,nomCajaTexto){
	
	//Si el Checkbox esta seleccionado, activamos la caja de texto
	if(checkBox.checked){
		//Cambiar el atributo de la caja de texto indicada
		document.getElementById(nomCajaTexto).readOnly = false;
		document.getElementById(nomCajaTexto).focus();
	}
	else{//Sí el checkBox es deseleccionado, vaciamos la caja de texto y activamos el atributo ReadOnly de la caja de texto
		document.getElementById(nomCajaTexto).value = "0.00";
		document.getElementById(nomCajaTexto).readOnly = true;
	}
	
}


//Esta función valida el formulario donde se registarn las cantidades de las actividades d ela OTSE
function valFormRegCostosActividades(frm_regCostoActividades){	
	//Esta variable almacenará la cantidad de Registros que deben ser validados
	var cantRegistros = parseInt(document.getElementById("hdn_cantRegistros").value);		
	//Si el valor se mantiene en 1, entonces el proceso de validación fue satisfactorio
	var validacion = 1;
	//Variable para manejar el mensaje de validación satisfactoria
	var msg = 0;
	//Variable para saber si al menos un material fue seleccionado
	var status = 0;
	//Variable para controlar la cantidad de registros
	var ctrl= 1;
	
					
	//Variables que almacenara el nombre de cada CheckBox Seleccionado y las cajas de texto de cantidad y aplicacion relacionada a el
	var idCheckBox = "";
	var idTxtCantidad = "";
	
	while(ctrl<=cantRegistros){
		//Crear el id del CheckBox que se quiere verificar
		idCheckBox="ckb_reg"+ctrl.toString();
		
		//Verificar que la cantidad  del Checkbox seleccionado no este vacia
		if(document.getElementById(idCheckBox).checked){
			//Esta variable indicará que al menos un checkBox fue seleccionado						
			status = 1;
			//Crear el id de la Caja de Texto de Cantidad
			idTxtCantidad = "txt_costoAct"+ctrl.toString();
			
			//Validar que la cantidad no este vacía
			if(document.getElementById(idTxtCantidad).value==""){				
				alert("Ingresar Cantidad Para el Registro No. "+ctrl);
				msg = 1;
			}
			else{
				//Validar que la cantidad sea un numero entero valido y que sea mayor a 0
				
				//Para este caso no se requiere validar que el numero sea mayor a 0, ya que algunos registros pueden ir en 0, respecto del numer valido,
				//eso se verifica en la funcion formatCurrency asociada a la caja de texto que se valida aqui.

			}
		}
		
		//Aumentar el control de registros
		ctrl++;
	}//Fin del while(ctrl<cantRegistros)
	
	
	
	//Verificar que al menos un material haya sido seleccionado, si la variable status vale 1, quiere decir que al menos un material fue seleccionado
	if(status==1){
		//Si hubo algun mensaje de que falta ingresar un datos, no se cumplio con el proceso de validacion 
		if(msg==1)
			validacion = 0;
	}
	else{
		alert("Seleccionar al Menos un Registro para Complementar");
		validacion = 0;
	}
	
	//Verificar que la factura sea proporcionada
	if(frm_regCostoActividades.txt_factura.value=="" && validacion==1){
		alert("Introducir el No. de Factura");
		validacion = 0;
		frm_regCostoActividades.txt_factura.focus();
	}
	
	if(frm_regCostoActividades.cmb_con_cos.value=="" && validacion==1){
		alert("Introducir el Centro de Costos");
		validacion = 0;
		frm_regCostoActividades.cmb_con_cos.focus();
	}
	
	if(frm_regCostoActividades.cmb_cuenta.value=="" && validacion==1){
		alert("Introducir la Cuenta");
		validacion = 0;
		frm_regCostoActividades.cmb_cuenta.focus();
	}
	
	if(frm_regCostoActividades.cmb_subcuenta.value=="" && validacion==1){
		alert("Introducir la Subcuenta");
		validacion = 0;
		frm_regCostoActividades.cmb_subcuenta.focus();
	}
	
	
	//Confirmar la Inserción de datos con el Usuario
	if(validacion==1){
		if(!confirm("NOTA: ¡Una vez Guardados los Datos, Estos ya NO se Podran Modificar!\nPresione Aceptar para Guardar los Datos\nPresione Cancelar para Poder Revisarlos"))
			validacion = 0;
	}
	
	
	if(validacion==1)
		return true;
	else
		return false;
	
}//Cierre de la función valFormRegCostosActividades(frm_regCostoActividades)


/*Esta función valida el formulario de registrar materiales*/
function valFormMaterialesUtilizar(frm_materialesUtilizar){
	var validacion = 1;//Esta variable ayudara a determinar si el proceso de validación fue exitoso
	
	
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
		
}//Cierre de la función valFormMaterialesUtilizar(frm_materialesUtilizar)

/*************************************************************************************************************************************************/
/********************************************************VALIDACION Y GENERACION DE VALES*********************************************************/
/*************************************************************************************************************************************************/
//Funcion que valida que se ingresen los materiales al Vale
function valFormMaterialVale(frm_generarVale){
	var res=1;
	//Variable que verifica que se haya seleccionado un radiobutton
	var flag=0;
	var cantidad=document.getElementsByName("rdb_material").length;
	for (var i=0;i<cantidad;i++){
		if (document.getElementById("rdb_material"+(i+1)).checked==true){
			flag=1;
			break;
		}
	}		
	if (flag==0){
		alert("Seleccionar si es un Material Registrado en Almacén o es un Material Nuevo");
		res=0;
	}
	else{
		//Verificar si es Material de Almacen
		if(document.getElementById("rdb_material"+(i+1)).value=="true"){
			if(document.getElementById("cmb_categoria").value==""){
				alert("Seleccionar la Categoría del Material");
				res=0;
				document.getElementById("cmb_categoria").focus();
			}
			if(res==1 && document.getElementById("cmb_material").value==""){
				alert("Seleccionar El Material");
				res=0;
				document.getElementById("cmb_material").focus();
			}
			if(res==1 && document.getElementById("txt_cantidad").value==""){
				alert("Ingresar la Cantidad");
				res=0;
				document.getElementById("txt_cantidad").focus();
			}
		}
		else{
			if(document.getElementById("txt_matNuevo").value==""){
				alert("Ingresar el Material");
				res=0;
				document.getElementById("txt_matNuevo").focus();
			}
			if(res==1 && document.getElementById("txt_cantidadNuevo").value==""){
				alert("Ingresar la Cantidad");
				res=0;
				document.getElementById("txt_cantidadNuevo").focus();
			}
			if(res==1 && document.getElementById("cmb_unidad").value==""){
				alert("Seleccionar la Unidad de Medida");
				res=0;
				document.getElementById("cmb_unidad").focus();
			}
		}
	}
	
	if(res==1)
		return true;
	else
		return false;
	}
	
/*Funcion que valida el formulario para complementar la bitacora*/
function valFormComplementarBit(frm_guardarVale){
	var res=1;
	
	if(document.getElementById("txt_noVale").value==""){
		alert("Ingresar el Número de Vale");
		res=0;
		document.getElementById("txt_noVale").focus();
	}
	if(res==1 && document.getElementById("txt_obra").value==""){
		alert("Ingresar la Obra de Destino");
		res=0;
		document.getElementById("txt_obra").focus();
	}
	if(res==1 && document.getElementById("txt_autorizo").value==""){
		alert("Ingresar el Nombre de la Persona que Autoriza");
		res=0;
		document.getElementById("txt_autorizo").focus();
	}
	
	if(res==1)
		return true;
	else
		return false;		
	}
	
//Seccion de Pagos
function calculoIvaPago(total,tasaIva){
	//Obtener el Total como numero sin comas ","
	total=parseFloat(total.replace(/,/g,""));
	//Obtener el importe como numero
	var importe = total/(1 + (tasaIva/100));
	//Obtener el Iva como numero
	var iva = total-importe;
	//Asignar los valores de iva y de Subtotal
	formatCurrency(iva,'txt_iva');
	formatCurrency(importe,'txt_subtotal');
}

function consultarPedido(idPedido){
	//Obtener el ancho y alto disponibles en la Pantalla
	var ancho=screen.width;
	var alto=screen.height;
	//Variables con el alto y ancho de la ventana a abrir por el usuario
//	var altoVentana=723;
	var altoVentana=(alto/2)+(alto/4);
	var anchoVentana=(ancho/2)+(ancho/4);
//	var anchoVentana=1035;
	//Calcular la sangria de arriba e izquierda con respecto del tamaño a abrir de la ventana
	var sangriaAlto=(alto-altoVentana)/2;
	var sangriaIzq=(ancho-anchoVentana)/2;
	//Obtener el ID del Pedido en Mayusculas
	idPedido=idPedido.toUpperCase();
	//Abrir la ventana del Pedido
	window.open('../../includes/generadorPDF/pedido2.php?id='+idPedido+'', 'verPedidoPDF','top='+sangriaAlto+',left='+sangriaIzq+',width='+anchoVentana+',height='+altoVentana+',status=no,menubar=yes,resizable=yes,scrollbars=yes,toolbar=no,location=no,directories=no')
}

function valFormRegPagos(frm_registrarPago){
	var res=1;
	
	if(frm_registrarPago.hdn_validar.value=="si"){	
		if(frm_registrarPago.txt_nombre.value==""){
			res=0;
			alert("Ingresar el Nombre del Proveedor");
			frm_registrarPago.txt_nombre.focus();
		}
		
		if(res==1 && frm_registrarPago.txt_responsable.value==""){
			res=0;
			alert("Ingresar el Nombre del Responsable");
			frm_registrarPago.txt_responsable.focus();
		}
		
		if(res==1 && frm_registrarPago.txt_subtotal.value=="0.00"){
			res=0;
			alert("Ingresar el Subtotal Sin IVA");
			frm_registrarPago.txt_subtotal.focus();
		}
	}
	if(res==1)
		return true;
	else
		return false;
}

function valFormConsultarPago(frm_seleccionarDatosPago){
	var res=1;
	
	if(frm_seleccionarDatosPago.cmb_anio.value==""){
		res=0;
		alert("Seleccionar el Año");
		frm_seleccionarDatosPago.cmb_anio.focus();
	}
	/*if(res==1 && frm_seleccionarDatosPago.cmb_mes.value==""){
		res=0;
		alert("Seleccionar el Mes");
		frm_seleccionarDatosPago.cmb_mes.focus();
	}*/
	
	/*if(res==1 && frm_seleccionarDatosPago.cmb_tipo.value==""){
		res=0;
		alert("Seleccionar el Tipo de Consulta");
		frm_seleccionarDatosPago.cmb_tipo.focus();
	}*/
	
	
	if(res==1)
		return true;
	else
		return false;
}



//Funcion que valida dentro del formulario las opciones de registros de pagos dentro del departamento de compras
function filtroTipoPago(opcCombo){

	document.getElementById("etiqueta").innerHTML = "Seleccionar Dato";

	if(opcCombo=="PROVEEDOR"){
		document.getElementById("componenteHTML").innerHTML = "<input type=\"text\" name=\"txt_nombre\" id=\"txt_nombre\" onkeyup=\"lookup(this,'bd_compras','proveedores','razon_social','1');\" value=\"\" size=\"40\" maxlength=\"75\"  onkeypress=\"return permite(event,'car',0);\" tabindex=\"1\"/><div id=\"res-spider\"><div align=\"left\" class=\"suggestionsBox\" id=\"suggestions1\" style=\"display: none;\"><img src=\"../../images/upArrow.png\" style=\"position: relative; top: -12px; left: 10px;\" alt=\"upArrow\" /><div class=\"suggestionList\" id=\"autoSuggestionsList1\">&nbsp;</div></div></div>";

}	
	
	if(opcCombo=="RESPONSABLE"){
		document.getElementById("componenteHTML").innerHTML = "<input type=\"text\" name=\"txt_nombre\" id=\"txt_nombre\" onkeyup=\"lookupEmp(this,'1');\"  value=\"\" size=\"40\" maxlength=\"75\" onkeypress=\"return permite(event,'car',0);\" tabindex=\"1\"/><div id=\"res-spider\"><div align=\"left\" class=\"suggestionsBox\" id=\"suggestions1\" style=\"display: none;\"><img src=\"../../images/upArrow.png\" style=\"position: relative; top: -12px; left: 10px;\" alt=\"upArrow\" /><div class=\"suggestionList\" id=\"autoSuggestionsList1\">&nbsp;</div></div></div>";

}

if(opcCombo=="BAJAS"){
		document.getElementById("componenteHTML").innerHTML = "<input type=\"text\" name=\"txt_nombre\" id=\"txt_nombre\" onkeyup=\"lookupBajasEmp(this,'1');\"  value=\"\" size=\"30\" maxlength=\"80\" onkeypress=\"return permite(event,'car',0);\" tabindex=\"1\"/><div id=\"res-spider\"><div align=\"left\" class=\"suggestionsBox\" id=\"suggestions1\" style=\"display: none;\"><img src=\"../../images/upArrow.png\" style=\"position: relative; top: -12px; left: 10px;\" alt=\"upArrow\" /><div class=\"suggestionList\" id=\"autoSuggestionsList1\">&nbsp;</div></div></div>";

}

	if(opcCombo=="CANTIDAD"){
		document.getElementById("etiqueta").style.visibility="hidden";
		document.getElementById("componenteHTML").style.visibility="hidden";

		document.getElementById("etiquetaInf").innerHTML = "Cantidad Inferior";
		document.getElementById("componenteHTMLInf").innerHTML = "$<input type=\"text\" name=\"txt_cantInf\" id=\"txt_cantInf\"  value=\"\" size=\"15\" maxlength=\"15\" onkeypress=\"return permite(event,'num',2);\" onchange=\"formatCurrency(value,'txt_cantInf');\"  tabindex=\"1\"/>";

		document.getElementById("etiquetaSup").innerHTML = "Cantidad Superior";
		document.getElementById("componenteHTMLSup").innerHTML = "$<input type=\"text\" name=\"txt_cantSup\" id=\"txt_cantSup\"  value=\"\" size=\"15\" maxlength=\"15\" onkeypress=\"return permite(event,'num',2);\"  onchange=\"formatCurrency(value,'txt_cantSup');\" tabindex=\"1\"/>";

}	
	if(opcCombo==""){
		document.getElementById("etiqueta").innerHTML = "&nbsp;";
		document.getElementById("componenteHTML").innerHTML = "&nbsp;";
		
		document.getElementById("etiquetaInf").innerHTML = "&nbsp;";
		document.getElementById("componenteHTMLInf").innerHTML = "&nbsp;";
		
		document.getElementById("etiquetaSup").innerHTML = "&nbsp;";
		document.getElementById("componenteHTMLSup").innerHTML = "&nbsp;";
		
	}
}


		

//Funcion que escribe en el formulario de Registro de los pagos  que se realizan dentro del departamento de compras
function filtroRegPago(opcCombo){

	document.getElementById("etiqueta").innerHTML = "Seleccionar";

	if(opcCombo=="PROVEEDOR"){
		document.getElementById("componenteHTML").innerHTML = "<input type=\"text\" name=\"txt_nombre\" id=\"txt_nombre\" onkeyup=\"lookup(this,'bd_compras','proveedores','razon_social','1');\" value=\"\" size=\"40\" maxlength=\"80\"  onkeypress=\"return permite(event,'car',0);\" tabindex=\"1\"/><div id=\"res-spider\"><div align=\"left\" class=\"suggestionsBox\" id=\"suggestions1\" style=\"display: none;\"><img src=\"../../images/upArrow.png\" style=\"position: relative; top: -12px; left: 10px;\" alt=\"upArrow\" /><div class=\"suggestionList\" id=\"autoSuggestionsList1\">&nbsp;</div></div></div>";

}	
	
	if(opcCombo=="TRABAJADOR"){
		document.getElementById("componenteHTML").innerHTML = "<input type=\"text\" name=\"txt_nombre\" id=\"txt_nombre\" onkeyup=\"lookupEmp(this,'1');\"  value=\"\" size=\"40\" maxlength=\"80\" onkeypress=\"return permite(event,'car',0);\" tabindex=\"1\"/><div id=\"res-spider\"><div align=\"left\" class=\"suggestionsBox\" id=\"suggestions1\" style=\"display: none;\"><img src=\"../../images/upArrow.png\" style=\"position: relative; top: -12px; left: 10px;\" alt=\"upArrow\" /><div class=\"suggestionList\" id=\"autoSuggestionsList1\">&nbsp;</div></div></div>";

}

if(opcCombo=="BAJAS"){
		document.getElementById("componenteHTML").innerHTML = "<input type=\"text\" name=\"txt_nombre\" id=\"txt_nombre\" onkeyup=\"lookupBajasEmp(this,'1');\"  value=\"\" size=\"40\" maxlength=\"80\" onkeypress=\"return permite(event,'car',0);\" tabindex=\"1\"/><div id=\"res-spider\"><div align=\"left\" class=\"suggestionsBox\" id=\"suggestions1\" style=\"display: none;\"><img src=\"../../images/upArrow.png\" style=\"position: relative; top: -12px; left: 10px;\" alt=\"upArrow\" /><div class=\"suggestionList\" id=\"autoSuggestionsList1\">&nbsp;</div></div></div>";

}
	
	if(opcCombo==""){
		document.getElementById("etiqueta").innerHTML = "&nbsp;";
		document.getElementById("componenteHTML").innerHTML = "&nbsp;";
		
	}
}



//Funcion que escribe en el formulario de Registro de los pagos  que se realizan dentro del departamento de compras
function pedidoPagos(opcCombo){
	if(opcCombo=="PROVEEDOR"){
		document.getElementById("txt_pedido").value ="PED";
		document.getElementById("txt_pedido").readOnly=false;
	}
	else{
		document.getElementById("txt_pedido").value ="";
		document.getElementById("txt_pedido").readOnly=true;
		document.getElementById("img_verPedido").style.visibility="hidden";
		document.getElementById("txt_responsable").value ="";
	}
}