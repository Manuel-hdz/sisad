

//Esta funcion da formato de modena al numero indicado con 2 decimales. Ej. 1,234.56
function formatCurrency(num,element) {
	num = num.toString().replace(/$|,/g,'');
	if(isNaN(num))
		num = "0";
	sign = (num == (num = Math.abs(num)));
	num = Math.floor(num*100+0.50000000001);
	cents = num%100;
	num = Math.floor(num/100).toString();
	if(cents<10)
		cents = "0" + cents;
	for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
		num = num.substring(0,num.length-(4*i+3))+','+num.substring(num.length-(4*i+3));
	cadena=( (((sign)?'':'-') + num + '.' + cents));
	document.getElementById(element).value = cadena;
}


//Esta funcion da formato de milesimas a numeros enteros, no considera decimales. Ej. 2,345
function formatMiles(num,element) {
	num = num.toString().replace(/$|,/g,'');
	if(isNaN(num))
		num = "0";
	sign = (num == (num = Math.abs(num)));
	num = Math.floor(num*100+0.50000000001);
			
	num = Math.floor(num/100).toString();
		
	for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
		num = num.substring(0,num.length-(4*i+3))+','+num.substring(num.length-(4*i+3));
	
	cadena=( (((sign)?'':'-') + num ));
	
	document.getElementById(element).value = cadena;
}


//Esta funcion da formato de modena al numero indicado con 4 decimales. Ej. 13.4567
function formatTasaCambio(num,element) {
	num = num.toString().replace(/$|,/g,'');
	if(isNaN(num))
		num = "0";
		
	//Separar el numero y obtener los decimales	
	var partesNum = num.split(".");
	
	//Verificar si Existen Decimales en el Numero y Procesarlos
	var cents = "";
	if(partesNum[1]!="" && partesNum[1]!=undefined){		
		
		//Separ el numero dado en Enteros y decimales
		var numSeparado = num.split(".");
		
		//Asignar la Parte entera del numero a la variable 'num' para evitar el redondeo
		num = numSeparado[0];
		
		//Asignar los centavos a la variable 'cents'
		switch(numSeparado[1].length){
			case 1:
				cents = "."+numSeparado[1]+"000";
			break;
			case 2:
				cents = "."+numSeparado[1]+"00";
			break;
			case 3:
				cents = "."+numSeparado[1]+"0";
			break;
			case 4:
				cents = "."+numSeparado[1];
			break;
			default://Contemplar los casos en que los centavos sean mayores a 5
				cents = "."+numSeparado[1].substring(0,4);
			break;
		}
	}		
	
	//Retirar el punto '.'
	sign = (num == (num = Math.abs(num)));
	num = Math.floor(num*100+0.50000000001);		
	
	//Retirar los Decimales
	num = Math.floor(num/100).toString();		
		
	//Agrega las ',' para la separacion de milesimas	
	for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
		num = num.substring(0,num.length-(4*i+3))+','+num.substring(num.length-(4*i+3));
	
	
	cadena = ( (((sign)?'':'-') + num + cents));
	
	document.getElementById(element).value = cadena;
}


//Esta funcion da formato a los numeros con 5 decimales en los calculos realizados en el Modulo de Laboratorio, Ej. 2,456.89765
function formatNumDecimalLab(num,element){
	num = num.toString().replace(/$|,/g,'');
	if(isNaN(num))
		num = "0";
		
	//Separar el numero y obtener los decimales	
	var partesNum = num.split(".");
	
	//Verificar si Existen Decimales en el Numero y Procesarlos
	var decimales = "";
	if(partesNum[1]!="" && partesNum[1]!=undefined){		
		
		//Separ el numero dado en Enteros y decimales
		var numSeparado = num.split(".");
		
		//Asignar la Parte entera del numero a la variable 'num' para evitar el redondeo
		num = numSeparado[0];
						
		/* Procesar los decimales de la siguiente forma:
		 * 1. Si el numero tiene 5 o menos decimales, dejarlos tal cual.
		 * 2. Si el numero tiene mas de cinco decimales, tomar 6 decimales y redondearlos
		 */
		 if(numSeparado[1].length>5){
			//Obtener 6 digitos decimales y redondearlos
			numTemp = numSeparado[1].substring(0,5)+"."+numSeparado[1].substring(5,6);					
			
			//Guardar los Ceros (0) que puedan estar a la izquierda del numero obtenido
			var ceros = "";
			for(i=0;i<numTemp.length;i++){
				if(numTemp.charAt(i)=="0"){
					ceros += "0";
				}
				else if(numTemp.charAt(i)!="0"){
					break;
				}							
			}
			
			//Redondear el Numero Obtenido
			decimales = "."+ceros+Math.round(numTemp);
		 }
		 else{
		 	decimales = "."+numSeparado[1];
		 }
		
		
	}//Cierre if(partesNum[1]!="" && partesNum[1]!=undefined)		
	
	
	//Retirar el punto '.'
	sign = (num == (num = Math.abs(num)));
	num = Math.floor(num*100+0.50000000001);		
	
	//Retirar los Decimales
	num = Math.floor(num/100).toString();		
		
	//Agrega las ',' para la separacion de milesimas	
	for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
		num = num.substring(0,num.length-(4*i+3))+','+num.substring(num.length-(4*i+3));
	
	//Formar el numero con su parte entera y decimal en el caso que la tenga
	cadena = ( (((sign)?'':'-') + num + decimales));
	
	//Asignar el numero formateado al elemento HTML indicado
	document.getElementById(element).value = cadena;
}//Cierre de la funcion formatNumDecimalLab(num,element)



//Esta funcion da formato de modena al numero indicado con 2 decimales. Ej. $1,234.56
function formatCurrencySing(num,element) {
	num = num.toString().replace(/$|,/g,'');
	if(isNaN(num))
		num = "0";
	sign = (num == (num = Math.abs(num)));
	num = Math.floor(num*100+0.50000000001);
	cents = num%100;
	num = Math.floor(num/100).toString();
	if(cents<10)
		cents = "0" + cents;
	for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
		num = num.substring(0,num.length-(4*i+3))+','+num.substring(num.length-(4*i+3));
	cadena = ( (((sign)?'':'-') + num + '.' + cents));
	document.getElementById(element).value = "$"+cadena;
}