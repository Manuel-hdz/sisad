// JavaScript Document

//Este archivo contiene las funciones para mostrar un reloj en formato de 12 horas dentro del Sistema
function muestraReloj() {
	var fechaHora = new Date();
	var horas = parseInt(fechaHora.getHours());
	var minutos = fechaHora.getMinutes();
	var segundos = fechaHora.getSeconds();
			
	var meridiano = "";		
	var noHora = 0;		
	if(horas==0){
		noHora = 12;
		meridiano = "a.m.";
	}
	if(horas>0 && horas<12){
		noHora = horas;
		meridiano = "a.m."; 
	}
	if(horas==12){
		noHora = 12;
		meridiano = "p.m."; 
	}
	if(horas>12 && horas<23){
		noHora = horas - 12;
		meridiano = "p.m."; 
	}
	if(horas==23){
		noHora = 11;
		meridiano = "p.m."; 
	}		
	
	//Colocar el '0' a la Izquierda cuando el numero este entre 1 y 9
	if(noHora < 10) { noHora = '0' + noHora; }
	if(minutos < 10) { minutos = '0' + minutos; }
	if(segundos < 10) { segundos = '0' + segundos; }
	
	
	//Desplegar el reloj en el DIV indicado
	document.getElementById("reloj").innerHTML = noHora+':'+minutos+':'+segundos+' '+meridiano;
}