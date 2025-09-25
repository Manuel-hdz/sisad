	//Este script permite deshabilitar:
	// El boton derecho del mouse,
	// El F5 y F11
	// El Ctrl + F5
	// El Ctrl + N y Ctrl + U
	// El Ctrl + R
	// El Ctrl + [<-] y el Ctrl + [->]
	// El Backspace fuera de los formularios. y dentro de algunos campos	
	//En este script se utilizan los eventos: onkeydown y onkeyup 
	
	var controlPresionado = 0;
	var altPresionado = 0;
	
	/*Esta funcion deshabilita las combinados de las teclas Ctrl y Alt*/
	function desactivarCtrlAlt(teclaActual){
	   	var desactivar = false;
	   	//Ctrl + 
	   	if (controlPresionado==17){
		  	if (teclaActual==78 || teclaActual==85 ){//Ctrl+N y Ctrl+U deshabilitado
				desactivar=true;
		  	}         
		  	if (teclaActual==82){//Ctrl+R deshabilitado
				desactivar=true;
		  	}             
		  	if (teclaActual==116){//Ctrl+F5 deshabilitado
				desactivar=true;
		  	}          
		  	if (teclaActual==114){//Ctrl+F3 deshabilitado
				desactivar=true;
		  	}  
			if (teclaActual==115){//Ctrl+F4 deshabilitado
				desactivar=true;
			}
		}
	   	//Alt +
	   	if (altPresionado==18){
		  	if (teclaActual==37){//Alt+ [<-] deshabilitado
				desactivar=true;
		  	} 
		  	if (teclaActual==39){//Alt+ [->] deshabilitado
				desactivar=true;
			}     
			if (teclaActual==115){//Alt+ F4 deshabilitado
				desactivar=true;
			}     
	   	}
		
	   	if (teclaActual==17)
			controlPresionado=teclaActual;
	   	if (teclaActual==18)
			altPresionado=teclaActual;  
	   	
		return desactivar;
	}
	 
	document.onkeyup = function(){ 
		if (window.event && window.event.keyCode==17){
	 		controlPresionado = 0;
	   	}
	   	if (window.event && window.event.keyCode==18){
	 		altPresionado = 0;
	   	}  
	}


	//Esta Funcion Deshabilita las teclas de funciones: f3, f5, f6, f11, f12, backspace
	document.onkeydown = function(){ 
		//114	->f3	115 ->f4		116	->f5
		//117	->f6	122	->f11
		//123	->f12	8	->Backspace
		if (window.event && desactivarCtrlAlt(window.event.keyCode)){
			return false;
	   	}
		if (window.event && (window.event.keyCode == 114 || window.event.keyCode == 115 || window.event.keyCode == 116 || window.event.keyCode == 117 || window.event.keyCode == 122 || window.event.keyCode == 123)){
			window.event.keyCode = 505; 
		}

		if (window.event.keyCode == 505){ 
			return false; 
		}

		if (window.event && (window.event.keyCode == 8)){
			valor = document.activeElement.value;
			if (valor==undefined) { 
				return false; 
			} //Evita Back en página.
			else{
				//No permitir el Backespace en las cajas de texto con la propiedad ReadOnly
				if (document.activeElement.getAttribute('type')=='text' && document.activeElement.getAttribute('readonly')=='readonly'){
					return false;
				} //Evita Back en readOnly.
				if (document.activeElement.getAttribute('type')=='select-one'){
					return false;
				} //Evita Back en select.
				if (document.activeElement.getAttribute('type')=='button'){
					return false; 
				} //Evita Back en button.
				if (document.activeElement.getAttribute('type')=='radio'){
					return false;
				} //Evita Back en radio.
				if (document.activeElement.getAttribute('type')=='checkbox'){
					return false;
				} //Evita Back en checkbox.
				if (document.activeElement.getAttribute('type')=='file'){
					return false;
				} //Evita Back en file.
				if (document.activeElement.getAttribute('type')=='reset'){
					return false;
				} //Evita Back en reset.
				if (document.activeElement.getAttribute('type')=='submit'){
					return false;
				} //Evita Back en submit.
				else{ //Text, textarea o password				
    				if (document.activeElement.value.length==0){
						return false;
					} //No realiza el backspace(largo igual a 0).
    				else{
						document.activeElement.value.keyCode = 8;
					} //Realiza el backspace.
				}
			}
		}
	}//Fin desabilitar teclas