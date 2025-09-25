<?php
	/**
	  * Nombre del Módulo: Mantenimiento
	  * Nombre Programador: Daisy Adriana Martinez Fernandez
	  * Fecha: 30/Marzo/2011
	  * Descripción: Permite la importacion de un archivo CSV
	**/
	//Verificamos que el boton sbt_subir haya sido presionado
	if(isset($_POST["sbt_subir"])){
		//De ser asi llamamos la funcion de subir CSV
		subirArchivoCSV();		
	}
		
	//Función que permite subir a la BD la información de un archivo CSV
	function subirArchivoCSV(){
		//Agregamos includes para las conexiones 
		include_once("../../includes/conexion.inc");
		$flag=0;
		//Conectamos con la BD
		$conn = conecta("bd_recursos");
		//Creamos la variable ruta  para poder manejar los directorios
		$ruta='';
		//Asignamos a la variable carpeta; es decir asignamos el nombre de la misma
		$carpeta='temporal';
		//Creamos la variable $dir que permite abrir la ruta del archivo
		$dir = opendir($ruta);
		//Se verifica que el archivo sea updated
		if (is_uploaded_file($_FILES['upfile']['tmp_name'])){ 
			//Se comprueba que exista la carpeta
			if (!file_exists($carpeta."/"))
				//Si no existe se crea la carpeta; es creada con mkdir
				mkdir($carpeta."/", 0777); 
				$rs=false;
				//De lo contrario lo guarda en la carpeta existente
			if (!file_exists($_FILES['upfile']['name'])){
				//Mueve el archivo a la carpeta creada
				move_uploaded_file($_FILES['upfile']['tmp_name'], $carpeta."/".$_FILES['upfile']['name']); 
				//Declaramos la variable para el control de renglones o lineas
				$row = 1;
				//Declaramos la variable fecha que permite guardar el dia en que fue updated el archivo
				$fecha=date("Y");
				$fechaInsercion=date("Y-m-d");
				//Abrimos el archivo
				$fp = fopen ($carpeta."/".$_FILES['upfile']['name'],"r");
				//Guardamos el archivo en $data y es recorrido
				while ($data = fgetcsv ($fp, 1000, ",")) {	
					//Contamos el arreglo $data
					$num = count ($data);
					//Creamos la consulta
					if($_POST["hdn_sentencia"]=="U"){
						 $stm_sql="UPDATE nomina_bancaria set mes='$data[0]', semana='$data[1]', num='$data[2]', nombre_trabajador='$data[3]', rfc_trabajador='$data[4]', 
								  imss='$data[5]', curp='$data[6]', jornada='$data[7]', fecha_ingreso='$data[8]', tipo_salario='$data[9]', hrs_laboradas='$data[10]', 
								  dias_trabajados='$data[11]', septimo_dia='$data[12]', hrs_tiempo_extra='$data[13]', dias_domingos='$data[14]', dias_descanso='$data[15]',
								  dias_festivos='$data[16]', dias_vacacion='$data[17]', sueldo_diario='$data[18]', sueldo_integrado='$data[19]',
								  percepcion_normal='$data[20]', importe_septimo_dia='$data[21]', tiempo_extra='$data[22]', prima_dominical='$data[23]',
								  p_comision='$data[24]', trabajo_dias_descanso='$data[25]', trabajo_dias_festivos='$data[26]', prima_vacacional='$data[27]',
								  aguinaldo='$data[28]', ptu='$data[29]', premio_asistencia='$data[30]', premio_puntualidad='$data[31]', despensas='$data[32]',
								  prima_antiguo='$data[33]', anios_antiguo='$data[34]', otras_percepciones='$data[35]', clave_op='$data[36]', 
								  total_percepciones='$data[37]', retencion_imss='$data[38]', retencion_ispt='$data[39]', neto_percepciones='$data[40]',
								  abono_infonavit='$data[41]', otras_retenciones='$data[42]', fonacot='$data[43]', clave_or='$data[44]', total_retenido='$data[45]',
								  neto_salarios='$data[46]', subsidio_empleo='$data[47]', neto_pagar='$data[48]', numero='$data[49]', ingravado='$data[50]', 
								  depto='$data[51]', anio_insercion='$fecha', fecha_insercion='$fechaInsercion'WHERE fecha_insercion='$fechaInsercion' 
								  AND nombre_trabajador='$data[3]'" ;
					}
					else{
						$stm_sql="INSERT INTO nomina_bancaria (mes,semana,num,nombre_trabajador, rfc_trabajador, imss, curp, jornada,fecha_ingreso, tipo_salario,
								hrs_laboradas, dias_trabajados, septimo_dia, hrs_tiempo_extra, dias_domingos, dias_descanso, dias_festivos, dias_vacacion, sueldo_diario,
								sueldo_integrado, percepcion_normal, importe_septimo_dia, tiempo_extra, prima_dominical, p_comision, trabajo_dias_descanso, 
								trabajo_dias_festivos,
								prima_vacacional, aguinaldo, ptu, premio_asistencia, premio_puntualidad, despensas, prima_antiguo, anios_antiguo, 
								otras_percepciones,clave_op, 
								total_percepciones, retencion_imss, retencion_ispt, neto_percepciones, abono_infonavit, otras_retenciones, fonacot,
								clave_or, total_retenido,
								neto_salarios, subsidio_empleo, neto_pagar, numero, ingravado, depto, anio_insercion, fecha_insercion) 
								VALUES($data[0],$data[1],$data[2],'$data[3]','$data[4]','$data[5]','$data[6]','$data[7]','$data[8]','$data[9]',
								'$data[10]','$data[11]','$data[12]','$data[13]','$data[14]','$data[15]','$data[16]','$data[17]','$data[18]','$data[19]',		
								'$data[20]','$data[21]','$data[22]','$data[23]','$data[24]','$data[25]','$data[26]','$data[27]','$data[28]','$data[29]','$data[30]'
								,'$data[31]','$data[32]','$data[33]','$data[34]','$data[35]','$data[36]','$data[37]','$data[38]','$data[39]','$data[40]'
								,'$data[41]','$data[42]','$data[43]','$data[44]','$data[45]','$data[46]','$data[47]','$data[48]','$data[49]',
								'$data[50]','$data[51]', '$fecha', '$fechaInsercion')";
					}
						
					//Ejecutar la sentencia previamente creada
					$rs=mysql_query($stm_sql);
					//Incrementamos row
					$row++;
				}//Cierre del While	
				//Cerramos $fp 
				fclose ($fp); 
				$flag=1;
			}
			if ($rs){
				registrarOperacion("bd_recursos","File Up","SubioArchivoCSV",$_SESSION['usr_reg']);
				echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
			}
			else{
				$error=mysql_error();
				echo "<meta http-equiv='refresh' content='7;url=error.php?error=error'>";
				mysql_close($conn);
			}
		//Cerramos la conexion con la Base de Datos
		//mysql_close($conn);	
			//Liberar Archivo
				@unlink($carpeta."/".$_FILES['upfile']['name']);
				//Quitar el vinculo con la carpeta
				//unlink($carpeta);
				//Borrar Archivo
				rmdir($carpeta);
		}
	}
?>		