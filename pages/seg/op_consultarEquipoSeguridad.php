<?php
	/**
	  * Nombre del Módulo: Seguridad Industrial
	  * Nombre Programador: Daisy Adriana Martínez Fernández
	  * Fecha: 16/Marzo/2012
	  * Descripción: Este archivo permite mostrar los empleados; asi como el material (equipo de seguridad) que fue asignado a los mismos
	**/

	//Esta función se encarga de mostrar los empleados para verificar materiales de seguridad prestados al empleado 
	function mostrarEmpleados(){
		//Importamos el archivo que permite la conexión con la base de datos
		include_once("../../includes/conexion.inc");
		//Realizar la conexion a la BD de recursos humanos
		$conn = conecta("bd_recursos");
		//Creamos la consulta SQL
		$stm_sql ="SELECT rfc_empleado, CONCAT(nombre,' ', ape_pat,' ', ape_mat) AS nombre,area,puesto FROM empleados 
		           WHERE CONCAT(nombre,' ', ape_pat,' ', ape_mat)='$_POST[txt_nombre]'";
		//Ejecutamos la consulta SQL
		$rs = mysql_query($stm_sql);
		//Si la consulta trajo datos creamos la tabla para mostrar los mismos
		if($datos=mysql_fetch_array($rs)){						
			echo "								
			<table cellpadding='5'>
				<caption class='titulo_etiqueta'>EMPLEADO: <u><em>$_POST[txt_nombre]</em></u></caption>								    			
				<tr>
					<td width='70' class='nombres_columnas'>RFC</td>
					<td width='70' class='nombres_columnas'>NOMBRE</td>
					<td width='70' class='nombres_columnas'>&Aacute;rea</td>
					<td width='100' class='nombres_columnas'>PUESTO</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "<tr>	
						<td align='center' class='$nom_clase'>$datos[rfc_empleado]</td>
						<td align='center' class='$nom_clase'>$datos[nombre]</td>
						<td align='center' class='$nom_clase'>$datos[area]</td>
						<td align='center'class='$nom_clase'>$datos[puesto]</td>
					</tr>";									
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "</table>";
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'> No Existen Empleados con el nombre <em><u>$_POST[txt_nombre]</u></em></label>";
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion
	
	
	//Esta función se encarga de mostrar los empleados para verificar materiales de seguridad prestados al empleado 
	function mostrarMateriales(){
		//Incluimos el archivo para realizar la conexion
		include_once("../../includes/conexion.inc");
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		//Obtenemos el RFC del empleado
		$rfc_empleado = obtenerDatoEmpleadoPorNombre('rfc_empleado', $_POST['txt_nombre']);
		//Realizar la conexion a la BD de seguridad
		$conn = conecta("bd_almacen");
		//Creamos la sentencia SQL
		$stm_sql ="SELECT * FROM (detalle_es JOIN bd_recursos.empleados ON empleados_rfc_empleado=rfc_empleado) 
			WHERE bd_recursos.empleados.rfc_empleado='$rfc_empleado' ORDER BY nom_material";
		
		$stm_sql = "SELECT materiales_id_material, nom_material, MAX( fecha_entrega ) AS fecha_entrega, c_cambio, no_vale
					FROM (
						detalle_es
						JOIN bd_recursos.empleados ON empleados_rfc_empleado = rfc_empleado
					)
					WHERE bd_recursos.empleados.rfc_empleado =  '$rfc_empleado'
					GROUP BY materiales_id_material
					ORDER BY fecha_entrega DESC";
		//Ejecutamos la cosnulta SQL
		$rs = mysql_query($stm_sql);
		//Si la consulta trajo datos creamos la tabla para mostrar los mismos
		if($datos=mysql_fetch_array($rs)){			
			echo "								
			<table cellpadding='5' class='tala_frm' width='100%' cellspacing='5'>
				<caption class='titulo_etiqueta'>MATERIALES DE SEGURIDAD DEL EMPLEADO</caption>
				<tr>
					<td  class='nombres_columnas'>NO</td>
					<td class='nombres_columnas'>CLAVE MATERIAL</td>
					<td class='nombres_columnas'>NOMBRE EQUIPO SEGURIDAD</td>
					<td class='nombres_columnas'>FECHA DE ENTREGA</td>
					<td class='nombres_columnas'>CON CAMBIO</td>
					<td class='nombres_columnas'>NO. VALE</td>
					<td class='nombres_columnas'>TIEMPO DE VIDA</td>
					<td class='nombres_columnas'>TIEMPO DE VIDA RESTANTE</td>
					<td class='nombres_columnas'>CAMBIO NECESARIO</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				//Realizamos la conexión con la bd de seguridad
				$conn = conecta("bd_seguridad");
				//Creamos la sentencia pra obtener los datos  de la tabla de vida util del material de seguridad
				$stm_sqlTiempo = "SELECT * FROM vida_util_es WHERE materiales_id_material = '$datos[materiales_id_material]'";
				//Ejecutamos la sentencia previamente creada
				$rsTiempo = mysql_query($stm_sqlTiempo);
				//Guardamos el resultado en un arreglo de datos
				$datosTiempo = mysql_fetch_array($rsTiempo);
				echo "<tr>	
						<td align='center' class='$nom_clase'>$cont</td>
						<td align='center' class='$nom_clase'>$datos[materiales_id_material]</td>
						<td align='left' class='$nom_clase'>$datos[nom_material]</td>
						<td align='center' class='$nom_clase'>".modFecha($datos['fecha_entrega'],1)."</td>
						<td align='center' class='$nom_clase'>$datos[c_cambio]</td>
						<td align='center' class='$nom_clase'>$datos[no_vale]</td>";
						//Si el tiempo de vida viene vacio; el Departamento de SEG ind  no ha registrado dicho campo
						if($datosTiempo['tiempo_vida']==""){
							//Ponemos todas las variables con la misma etiqueta; ya que el tiempo de registro no fue registrado por el Departamento de Seguridad
							$tiempoVida = "<strong>NO REGISTRADO</strong>";
							$tipoTiempo = "<strong>NO REGISTRADO</strong>";
							$tiempoVidaRestante = "<strong>NO REGISTRADO</strong>";
							$cambio = "<strong>NO REGISTRADO</strong>";
						}
						else{
							//Almacenamos el tiempo de vida
							$tiempoVida = $datosTiempo['tiempo_vida'];
							//Almacenamos el Tipo de Tiempo de vida
							$tipoTiempo = $datosTiempo['tipo_tiempo'];
							//Comprobar el tipo de registro cuando el tipo de registro es DIAS
							if($tipoTiempo=="DIAS"){
								//Dejarlo como viene de la BD ya que se encuentra en dias
								$tiempoVida = $datosTiempo['tiempo_vida'];	
							} 
							//Comprobar el tipo de registro cuando el tipo de registro es SEMANAS
							else if($tipoTiempo =="SEMANAS"){
								//Multiplicar el registo por 7 ya que son los dias que contiene una semana
								$tiempoVida = $datosTiempo['tiempo_vida']*7;
							}
							//Comprobar el tipo de registro cuando el tipo de registro es MESES
							else if($tipoTiempo =="MESES"){
								//Multiplicar el registo por 30 ya que son los dias que contiene un mes
								$tiempoVida = $datosTiempo['tiempo_vida']*30;
							}
							//Obtenemos el numero de dias transcurrido hasta la fecha Actual
							$fechaActual = date("Y-m-d");
							//Obtenemos la fecha de Registro
							$fechaReg = $datos['fecha_entrega'];
							//Realizamos la operacion necesaria para saber el numero de dias restante
							$restanteDias = restarFechas($fechaReg,$fechaActual);
							//Obtenemos el tiempo restante del tiempo de vida
							$tiempoVidaRestante = $tiempoVida - $restanteDias;
							//Concatenarle a tiempo vida la palabra dias
							$tiempoVida = $tiempoVida." D&iacute;as";
							//Comprobamos si el equipo requiere cambio; cuando el tiempode vida restante sea igual o menor a cero (0)
							if($tiempoVidaRestante >=0){
								//Guardar el contenido en rojo para indicarle al usuario que es necesario el cambio
								$cambio = "<label class='msje_correcto'>NO</label>";
							}
							else{
								//De lo contrario mostrarle en la etiqueta el contenido de que no hace falta el cambio
								$cambio = "<label class='msje_incorrecto'>SI</label>";
							}
							//Comprobar si el dato de tiempo de vida restante es negativo
							if($tiempoVidaRestante<0){
								//Multiplicamos el tiempo restante por menos uno para obtener un numero positivo
								$tiempoVidaRestante = $tiempoVidaRestante * -1;
								//Concatenarle que es necesario el cambio
								$tiempoVidaRestante = "CAMBIO RECOMENDADO HACE ".$tiempoVidaRestante;
							}
							//Concatenarle al restante del tiempo de vidala palabra dias
							$tiempoVidaRestante = $tiempoVidaRestante." D&iacute;as";
						}
					echo "
						<td align='center' class='$nom_clase'>$tiempoVida</td>
						<td align='center' class='$nom_clase'>$tiempoVidaRestante</td>
						<td align='center' class='$nom_clase'>$cambio</td>
					</tr>";									
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "</table>";
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'> No Existen Materiales Asociados con el nombre <em><u>$_POST[txt_nombre]</u></em></label>";
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion
?>