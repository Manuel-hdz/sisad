//Variable que recibe el contador de tiempo para determinar cuando la sesion debe expirar
var tiempo;
//Funcion que ejecuta la sentencia para cerrar la sesion tras 1 hora de inactividad
function inicio(){
	//1 Hora de Sesion=3600000
	tiempo=setTimeout('alert("Tu Sesión ha Expirado"),location="../salir.php"',3600000);
}
//Funcion que detiene la sentencia para cerrar la sesion tras 1 hora de inactividad y despues reiniciar
function parar(){
	//Resetea el valor de la variable tiempo
	clearTimeout(tiempo);
	//1 Hora de Sesion=3600000
	tiempo=setTimeout('alert("Tu Sesión ha Expirado"),location="../salir.php"',3600000);
}

//Esta funcion formatea las Fechas a valores posibles y permitidos
function formatHora(campo,comboAmPm){
	var hora=campo.value;
	var band=0;
	var h;
	var m;
			
	hora=hora.replace(/:/g,'');
		
	if (hora.length==4){
		h=hora.substr(0,2);
		m=hora.substr(2,2);
	}
	if (hora.length==3){
		h=hora.substr(0,1);
		h=0+h;
		m=hora.substr(1,2);
	}
	if (hora.length==3||hora.length==4){
		if (h>24)
			band=1;
		if (h>12&&h<=24){
			switch (h){
				case "13":
					h="01";
					break;
				case "14":
					h="02";
					break;
				case "15":
					h="03";
					break;
				case "16":
					h="04";
					break;
				case "17":
					h="05";
					break;
				case "18":
					h="06";
					break;
				case "19":
					h="07";
					break;
				case "20":
					h="08";
					break;
				case "21":
					h="09";
					break;
				case "22":
					h="10";
					break;
				case "23":
					h="11";
					break;
				case "24":
					h="12";
					break;
			}
			if (h=="12")
				document.getElementById(comboAmPm).value="AM";
			else
				document.getElementById(comboAmPm).value="PM";
		}
		if (m>=60&&band==0)
			band=1;
	}
	else
		band=1;
					
	if (band==0){
		hora=h+":"+m;
		campo.value=hora;
	}
	else{
		alert ("La hora introducida no es correcta");
		campo.value=campo.defaultValue;
	}
}

/*Funcion que valida el formato de una fecha*/
function formatFecha(campo){

	var fecha=campo.value.replace(/\//g,'');
	var dia;
	var mes;
	var anio;
	var band=0;

	switch (fecha.length){
		case 8:
			dia=fecha.substr(0,2);
			mes=fecha.substr(2,2);
			anio=fecha.substr(4,4);
			if (dia<1 || dia>31){
				band=1;
			}
			if (mes<1 || mes>12){
				band=1;
			}
			if (anio<2005 || anio>3000){
				band=1;
			}
			break;
		case 7:
			dia=fecha.substr(0,1);
			mes=fecha.substr(1,2);
			anio=fecha.substr(3,4);
			if (dia<1 || dia>31){
				band=1;
			}
			else{
				if (dia<10)
					dia=0+dia;
			}
			if (mes<1 || mes>12){
				band=1;
			}
			if (anio<2005 || anio>3000){
				band=1;
			}
			break;
		case 6:
			dia=fecha.substr(0,1);
			mes=fecha.substr(1,1);
			anio=fecha.substr(2,4);
			if (dia<1 || dia>31){
				band=1;
			}
			else{
				if (dia<10)
					dia=0+dia;
			}
			if (mes<1 || mes>12){
				band=1;
			}
			else{
				if (mes<10)
					mes=0+mes;
			}
			if (anio<2005 || anio>3000){
				band=1;
			}
			break;
		default:
			band=1;
			break;
	}
	
	if (band==0){
		campo.value=dia+"/"+mes+"/"+anio;
	}
	else{
		alert ("La Fecha introducida no es correcta");
		campo.value=campo.defaultValue;
	} 
}