<?php
	/**
	  * Nombre del Módulo: Producción
	  * Nombre Programador: Daisy Adriana Martínez Fernández
	  * Fecha: 20/Julio/2011
	  * Descripción: Este archivo contiene funciones para modificar la información relacionada con el formulario de modificar Produccion en la bitacora
	**/

	//Verificamos que exista en el post el boton y si es asi guardar los registros
	if(isset($_POST["sbt_guardarEquipo"]))
		guardarEquipo();
	
	//Verificamos si existe el boton de eliminar Produccion si es asi procedemos a eliminar el registro
	if(isset($_POST["sbt_eliminarProduccion"]))
		eliminarRegistroProduccion();
	
	//Verificamos que este definido el boton de modificar y asi guardamos la modificacion de la Bitacora	
	if(isset($_POST["sbt_guardarModificacionProduccion"])){
		guardarModificacionBitacora();
	
	}
	
	
	//Verificamos que exista el boton guardar de ser asi verificamos si existe el combo y el valor contenido
	if(isset($_POST["sbt_guardarModificacionProduccion"])){
		if(isset($_POST["cmb_destino"])){
			$dato=obtenerDato("bd_produccion", "catalogo_destino", "destino", "id_destino", $_POST["cmb_destino"]);
			if($dato=="COLADO" || $dato=="COLADOS" )
				guardarModificacionDetalleColado();
			else if($_POST["cmb_destino"]!=0)
				unset($_SESSION['produccion']);
		}
		
		//Verificamos que exista el valor colados en el txt nuevo destino
		if(isset($_POST["txt_nuevoDestino"])){
			if($_POST["txt_nuevoDestino"]=="COLADOS"||$_POST["txt_nuevoDestino"]=="COLADO")
				guardarModificacionDetalleColado();
			else
				unset($_SESSION['produccion']);
		}
		if(isset($_POST["hdn_destino"]))
			guardarModificacionDetalleColado();
		
	}
		
	//Funcion que permite consultar la Produccion Registrada
	function mostrarProduccion(){
		include_once("../../includes/func_fechas.php");
		
		//Conectar a la BD de Producción
		$conn = conecta("bd_produccion");
		
		if(isset($_POST["cmb_periodo"])){

			//Obtenemos la fecha inicial de la BD para tomarla como parametro al realizar las operaciones para realizar los calculos
			$stm_fechaInicial="SELECT fecha_inicio FROM presupuesto WHERE periodo='$_POST[cmb_periodo]' AND catalogo_destino_id_destino= '$_POST[cmb_destino]'";

			//Ejecutamos la Consulta
			$rs_fechaIni=mysql_query($stm_fechaInicial);
			
			//Pasamos a un arreglo la fecha
			$fechaIni=mysql_fetch_array($rs_fechaIni);

			//Guardamos el dato correspondiente
			$fechaIniBD = $fechaIni['fecha_inicio'];
		 	
			
			//Obtenemos la fecha final de la BD para tomarla como parametro al realizar las operaciones para realizar los calculos
			$stm_fechaFinal="SELECT fecha_fin FROM presupuesto WHERE periodo='$_POST[cmb_periodo]' AND catalogo_destino_id_destino= '$_POST[cmb_destino]'";

			//Ejecutamos la Consulta
			$rs_fechaFin=mysql_query($stm_fechaFinal);
			
			//Pasamos a un arreglo la fecha
			$fechaFin=mysql_fetch_array($rs_fechaFin);

			//Guardamos el dato correspondiente
			 $fechaFinBD = $fechaFin['fecha_fin'];
			
			//Seccionamos las fechas para mandarlas como parametro a las funciones correspondientes			
			$seccFechaIni = split("-",$fechaIniBD);
			$seccFechaFin = split("-",$fechaFinBD);
			$diaIni=$seccFechaIni[2];
			$mesIni=$seccFechaIni[1];
			$anioIni=$seccFechaIni[0];
			$diaFin=$seccFechaFin[2];
			$mesFin=$seccFechaFin[1];
			$anioFin=$seccFechaFin[0];
			
			//Obtenemos los dias del mes de inicio asi como de fin
			$obtDiasMesIni=diasMes($mesIni, $anioIni);
			$obtDiasMesFin=diasMes($mesFin, $anioFin);
					
			//Obtenemos los volumenes presupuestados para las fechas antes seleccionadas
			$volumenesPrespuestados=obtenerVolumenPresupuestadoDiario($fechaIniBD, $fechaFinBD, $_POST['cmb_destino']);
			
			//Obtenemos el volumen producido
			$vol= diaSemana($diaIni, $mesIni,  $obtDiasMesIni, $anioIni, $diaFin, $mesFin, $obtDiasMesFin, $anioFin);
			$secVol=split("/",$vol[0]);
			$compVolumen=$secVol[1];
						
			//Crear sentencia SQL
			$sql_stm = "SELECT bitacora_produccion_fecha, SUM(vol_producido) AS vol_producido FROM datos_bitacora  WHERE  bitacora_produccion_fecha>='$fechaIniBD' 
						AND bitacora_produccion_fecha<='$fechaFinBD' AND catalogo_destino_id_destino= '$_POST[cmb_destino]' GROUP BY bitacora_produccion_fecha  ";
						
			$stm_sql2="SELECT vol_ppto_dia FROM presupuesto WHERE fecha_inicio>='$fechaIniBD' AND fecha_fin>='$fechaFinBD' AND catalogo_destino_id_destino= '$_POST[cmb_destino]'";		
			
			$titulo=obtenerDato("bd_produccion", "presupuesto", "periodo", "id_presupuesto", $_POST['cmb_periodo']);
			
			$tituloDestino=obtenerDato("bd_produccion", "catalogo_destino", "destino", "id_destino", $_POST['cmb_destino']);
			
			//modificacion del titulo
			$titulo = "en el Destino  <em><u>   $tituloDestino     </u></em>    Perido   <em><u>   $_POST[cmb_periodo]    </u></em> ";

		}
		else if(isset($_POST["txt_fecha"])){		
			$fechaIniBdPres=obtenerDato("bd_produccion", "presupuesto", "fecha_inicio", "catalogo_destino_id_destino", $_POST['cmb_destino']);
			
			//Tomamos la fecha del post y la convertimos en formato necesario para la Base de Datos				
			$fechaConsulta=modFecha($_POST["txt_fecha"],3);
			
			//Obtenemos la fechaInicial del presupuesto para manejarla como fecha inicial
			$fechaIni=obtenerFechaPresupuesto(modFecha($_POST["txt_fecha"],3));

			//Seccionamos las fechas para mandarlas como parametro a las funciones correspondientes			
			$seccFechaIni = split("-",$fechaIni);
			$seccFechaFin = split("-",$fechaConsulta);
			$diaIni=$seccFechaIni[2];
			$mesIni=$seccFechaIni[1];
			$anioIni=$seccFechaIni[0];
			$diaFin=$seccFechaFin[2];
			$mesFin=$seccFechaFin[1];
			$anioFin=$seccFechaFin[0];
			
				$obtDiasMesIni=diasMes($mesIni, $anioIni);
				$obtDiasMesFin=diasMes($mesFin, $anioFin);
				
				//Obtenemos el volumen prespupuestadodiario para la fecha dad		
				$volumenesPrespuestados=obtenerVolumenPresupuestadoDiario($fechaIni, $fechaConsulta);
				
				$vol= diaSemana($diaIni, $mesIni,  $obtDiasMesIni, $anioIni, $diaFin, $mesFin, $obtDiasMesFin, $anioFin);
				$secVol=split("/",$vol[0]);
			 	$compVolumen=$secVol[1];

				$sql_stm = "SELECT DISTINCT bitacora_produccion_fecha, SUM(vol_producido) as vol_producido FROM datos_bitacora  
							WHERE  bitacora_produccion_fecha='$fechaConsulta' AND catalogo_destino_id_destino = '$_POST[cmb_destino]' ";
				$stm_sql2="SELECT vol_ppto_dia FROM presupuesto WHERE fecha_inicio<='$fechaConsulta' AND fecha_fin>='$fechaConsulta' 
						    AND catalogo_destino_id_destino = '$_POST[cmb_destino]' ";	
				//se obtiene la fecah y el destino del titulo dentro de la tabla que muestra los registros
				$titulo1=modFecha($_POST["txt_fecha"],3);			
				$tituloDestino=obtenerDato("bd_produccion", "catalogo_destino", "destino", "id_destino", $_POST['cmb_destino']);		
				//modificacion del titulo
				$titulo = "en el Destino  <em><u>   $tituloDestino     </u></em>     en la Fecha     <em><u>   $titulo1    </u></em> ";	
			
			
		}
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
		if($compVolumen!=0 && mysql_num_rows($rs)>0 && $datos=mysql_fetch_array($rs)){
			//Confirmar que la consulta de datos fue realizada con exito.
			if($datos["bitacora_produccion_fecha"]!=NULL){
				echo "<br>
				<table width='100%' cellpading='5'>
				<caption><strong>Registro Diario de la Producci&oacute;n  ". $titulo. "</strong></caption>
				<tr>
					<td class='nombres_columnas'>SELECCIONAR</td>
					<td class='nombres_columnas'>FECHA</td>
					<td class='nombres_columnas'>VOLUMEN<br>PRESUPUESTADO</td>
					<td class='nombres_columnas'>VOLUMEN<br>PRODUCIDO</td>
					<td class='nombres_columnas'>DIFERENCIA</td>
					<td class='nombres_columnas'>ACUMULADO<br>PRESUPUESTADO</td>
					<td class='nombres_columnas'>ACUMULADO<br>REAL</td>
					<td class='nombres_columnas'>DIFERENCIA</td>
				</tr>";
				$nom_clase = "renglon_gris";
				$cont = 1;
				//Ejecutamos la consulta que contiene la produccion
				$rs2=mysql_query($stm_sql2);
				$produccion=mysql_fetch_array($rs2);
				//Variable que almacenara el acumulado Real
				$acumuladoReal=0;
				//Variable que almacenara el acumulado presupuestado
				$acumuladoPpto=0;
				do{	
					//Igualamos la fecha Actual como la fecha de la BD
					$fechaActual=$datos['bitacora_produccion_fecha'];
						echo "     			
							<tr>
								<td class='nombres_filas' width='6%' align='center'>
									<input type='checkbox' name='ckb_fecha' id='ckb_fecha' value='$datos[bitacora_produccion_fecha]' 
									onClick='javascript:document.frm_consultarProduccion.submit();'/>
								</td>
								<td title='Fecha' class='$nom_clase'>".modFecha($datos['bitacora_produccion_fecha'],1)."</td>
								<td title='Volume Presupuestado Diario' class='$nom_clase'>".number_format(round($produccion['vol_ppto_dia'],1),2,".",",")." m&sup3;</td>
								<td title='Volumen Producido' class='$nom_clase'>".number_format(round($datos['vol_producido']),2,".",",")." m&sup3;</td>";
								//Recorremos el arreglo vol en busca de los valores para mostrarlos en la tabla
								foreach($vol as $key => $valor){
									//Dividimos el valor que se encuentre el vol en la posicion n para tomar cada dato por separado y poder realizar las operaciones nec
									$datoSec=split("/",$vol[$key]);
									//Tomamos la fecha que viene concatenada 
									 $fecha=$datoSec[0];
									//Tomamos el volumen
									$volumen=$datoSec[1];
									//Realizamos la operacion del volumen producido que es constante en un periodo menos el volumen presupuestado que viene en el arreglo
									$diferencia=$datos['vol_producido']-$produccion['vol_ppto_dia'];
									//Si la fecha del arreglo es igual a la fecha Actual para saber cuando cambiar de registro
									if($fecha==$fechaActual){
										//Si la diferencia es menor a cero ponemos color a la celda en rojo
										if($diferencia<0)
											echo"<td title='Diferencia' class='$nom_clase' align='center'><label  class='msje_incorrecto'>".$diferencia." m&sup3;</label></td>";
										//De lo contrario la ponemos en color azul
										else
											echo"<td title='Diferencia' class='$nom_clase' align='center'> <label  class='msje_correcto'>".$diferencia." m&sup3;</label></td>";
										echo "<td title='Acumulado Presupuestado' class='$nom_clase'>".round($datoSec[1],1)."m&sup3;</td>";
										$acumuladoPpto=	round($datoSec[1],1);
									}
								}
								//Si viene una sola fecha quiere decir que se tiene que proceder a realizar las operaciones para obtener el acumulado real de una
								//manera diferente
								
								if(isset($_POST["txt_fecha"])){	
									$fecha=modFecha($_POST["txt_fecha"],3);
									//Creamos la consulta y ejecutamos
									$stm_volProd="SELECT SUM(vol_producido) as vol_producido FROM datos_bitacora WHERE bitacora_produccion_fecha>='$fechaIniBdPres' 
												  AND bitacora_produccion_fecha<='$fecha' 
											 AND catalogo_destino_id_destino = '$_POST[cmb_destino]'";
									$rs3=mysql_query($stm_volProd);
									$volProducido=mysql_fetch_array($rs3);
									//Guardamos el valor arrojado por la consulta en la variable acumuladoReal
									 $acumuladoReal=$volProducido['vol_producido'];
									echo"<td title='Acumulado Real' class='$nom_clase' align='center'>".number_format($acumuladoReal,2,".",",")." m&sup3;</td>";
								}
								//De lo contrario vienen dos fechas y se procede a realizar las operaciones de manera dif
								if(!isset($_POST["txt_fecha"])){	
									//Si el contador es igual a 1 entonces el acumulado Real sera igual al valor de la BD
									if($cont==1){
										echo"<td title='Acumulado Real' class='$nom_clase' align='center'>".number_format($datos['vol_producido'],2,".",",")." m&sup3;</td>";
										$acumuladoReal=$datos['vol_producido'];
									}
									else{
										//De lo contrario lo acumularemos
										$acumuladoReal+=$datos['vol_producido'];
										echo"<td title='Acumulado Real' class='$nom_clase' align='center'>".number_format($acumuladoReal,2,".",",")." m&sup3;</td>";
										
									}
								}
								//Realizamos las oepraciones correspondientes
								$diferenciaAcumulados = $acumuladoReal-$acumuladoPpto;
								if($diferenciaAcumulados<0)
									echo"<td title='Diferencia' class='$nom_clase' align='center'> <label  class='msje_incorrecto'>".$diferenciaAcumulados." m&sup3;</label></td>";
								else
									echo"<td title='Diferencia' class='$nom_clase' align='center'> <label  class='msje_correcto'>".$diferenciaAcumulados." m&sup3;</label></td>";
							echo "</tr>";
					
						//Determinar el color del siguiente renglon a dibujar
						$cont++;
						if($cont%2==0)
							$nom_clase = "renglon_blanco";
						else
							$nom_clase = "renglon_gris";					
				}while($datos=mysql_fetch_array($rs));
				echo "<input type='hidden' name='hdn_cant' id='hdn_cant' value='$cont'/>";
				//Fin de la tabla donde se muestran los resultados de la consulta
				echo "	</table>";
				
			}
			else//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'>No se Encontraron resultados</u></em>";
		}
		else//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'>No se Encontraron resultados</u></em>";
		//Cerrar la conexion con la BD		
		mysql_close($conn);	
	}//fin function 



//Función para obtener el volumen presupuestado Diario de Dos Fechas
function obtenerVolumenPresupuestadoDiario($fechaIni, $fechaFin){
	if(isset($_POST["cmb_periodo"])){		
		$sql_periodo="SELECT periodo FROM presupuesto WHERE periodo = '$_POST[cmb_periodo]' AND catalogo_destino_id_destino = '$_POST[cmb_destino]'";
		
		$rs_periodo=mysql_query($sql_periodo);
		
		$periodoBase=mysql_fetch_array($rs_periodo);
		
		$periodo = $periodoBase['periodo'];
		
		//Creamo la consulta
		$vol_sql="SELECT vol_ppto_dia FROM presupuesto WHERE periodo='$periodo' AND catalogo_destino_id_destino = '$_POST[cmb_destino]'";		
	}
	else
		$vol_sql="SELECT vol_ppto_dia FROM presupuesto WHERE fecha_inicio<='$fechaIni' AND fecha_fin>='$fechaFin' AND catalogo_destino_id_destino = '$_POST[cmb_destino]'";

	//Ejecutar la sentencia previamente creada
	$rs = mysql_query($vol_sql);									
	
	//Declaramos el arreglo que contendra los volumenes prespuestados para el dia dado
	$presupuesto="";
	
	//Confirmar que la consulta de datos fue realizada con exito.
	if($datos=mysql_fetch_array($rs)){
		do{	
			$presupuesto=$datos['vol_ppto_dia'];
		}while($datos=mysql_fetch_array($rs));
	}
	else{
		$presupuesto="";
	}
	return $presupuesto;
}


//Función para obtener el volumen presupuestado Diario de una fecha especifica
function obtenerFechaPresupuesto($fecha){
	
	//Creamo la consulta
	$vol_sql="SELECT fecha_inicio FROM presupuesto WHERE fecha_inicio<='$fecha' AND fecha_fin>='$fecha' AND catalogo_destino_id_destino = '$_POST[cmb_destino]'";		

	//Ejecutar la sentencia previamente creada
	$rs = mysql_query($vol_sql);									
	
	//Declaramos el arreglo que contendra los volumenes prespuestados para el dia dado
	$presupuesto="";
	
	//Confirmar que la consulta de datos fue realizada con exito.
	if($datos=mysql_fetch_array($rs)){
		do{	
			$fecha=$datos['fecha_inicio'];
		}while($datos=mysql_fetch_array($rs));
	}
	return $fecha;
}

//Función que permite conocer el dia de la semana ademas de asignarle el volumen producido calculado
function diaSemana($diaIni, $noMesIni,  $noDiasMesIni, $anioIni, $diaFin, $noMesFin, $noDiasMesFin, $anioFin){
	//Variable que permite obtener el volumen presupuestado
	$presupuesto=obtenerVolumenPresupuestadoDiario($anioFin."-".$noMesIni."-".$diaIni, $anioFin."-".$noMesFin."-".$diaFin);
	//Obtenemos la longitud del dia para saber si es necesario concatenarle mas adelante el 0
	$contDiaIni=strlen($diaIni);
	//Arreglo que guardara los volumenes
	$volumenes=array();
	//Contador que permite controlar el numero interno de los arregloss
	$cont=1;
	//Verificamos que los años sean diferentes; si es asi realizamos el proceso
	if($anioIni!=$anioFin){
		for($i=$diaIni;$i<=$noDiasMesIni;$i++){
			//Creamos el objeto para manipular las fechas
			$diaSemana = date("wday", mktime(00, 00, 00, $noMesIni, $i, $anioIni));
			//Sustraemos el valor que indica que dia de la semana es(domingo-lunes)
			$diaSemana=substr($diaSemana,0,1);
			//Si es diferente de 0 almacenamos el valor en el arreglo volumenes
			$iAux=strlen($i);
			if($diaSemana!=0){
				//Si el valor es menor que 10 tenemos que agregar un 0 al dia para despues poder comprara con el resultado de la BD
				if($i<10&&$iAux<2){
					$volumenes[]=$anioIni."-".$noMesIni."-"."0".$i."/".$presupuesto*$cont;
				}
				else
					$volumenes[]=$anioIni."-".$noMesIni."-".$i."/".$presupuesto*$cont;
					//Incrementamos el contador
				$cont++;
			}
			else{
				//De lo contrario el dia seleccionado es un domingo
				//Contamos el arreglo y disminuimos una posicion para almacenar el valor del ultimo registro en la siguiente posicion
				$numReg=count($volumenes);
				if($numReg>0){
					$numReg=$numReg-1;
					//Seccionamos el arreglo para obtener unicamente el valor ya que de lo contrario se concatenan mas de los valores necesarios
					$volumenSec=split("/",$volumenes[$numReg]);
					$concepto=$volumenSec[1];
				}
				else{
					$concepto=$presupuesto*$cont;
				}
				if($i<10&&$iAux<2){
					$volumenes[]=$anioIni."-".$noMesIni."-"."0".$i."/".$concepto;
				}
				else
					$volumenes[]=$anioIni."-".$noMesIni."-".$i."/".$concepto;
			}
		}
		//Incrementamos el año para que al entrar en el siguiente proceso pueda encoentrarlo con el año
		$anioIni=$anioIni+1;
	}
	if($anioIni==$anioFin){
		//Recorremos el arreglo con el numero de dia inicial y el total de dias del mes
		for($i=$diaIni;$i<=$noDiasMesIni;$i++){
			//Creamos el objeto para manipular las fechas
			$diaSemana = date("wday", mktime(00, 00, 00, $noMesIni, $i, $anioFin));
			//Sustraemos el valor que indica que dia de la semana es(domingo-lunes)
			$diaSemana=substr($diaSemana,0,1);
			//Veriable auxiliar que permite conocer la longitud del dia
			$iAux=strlen($i);
			//Si es diferente de 0 almacenamos el valor en el arreglo volumenes
			if($diaSemana!=0){
				//Si el valor es menor que 10 tenemos que agregar un 0 al dia para despues poder comprara con el resultado de la BD
				if($i<10&&$iAux<2){
					$volumenes[]=$anioFin."-".$noMesIni."-"."0".$i."/".$presupuesto*$cont;
				}
				else
					$volumenes[]=$anioFin."-".$noMesIni."-".$i."/".$presupuesto*$cont;
					//Incrementamos el contador
					$cont++;
				}
				if($diaSemana==0 && $cont>1){
					//De lo contrario el dia seleccionado es un domingo
					//Contamos el arreglo y disminuimos una posicion para almacenar el valor del ultimo registro en la siguiente posicion
					$numReg=count($volumenes)-1;
					//Seccionamos el arreglo para obtener unicamente el valor ya que de lo contrario se concatenan mas de los valores necesarios
					$volumenSec=split("/",$volumenes[$numReg]);
					if($i<10&&$iAux<2){
						$volumenes[]=$anioFin."-".$noMesIni."-"."0".$i."/".$volumenSec[1];
					}
					else
						$volumenes[]=$anioFin."-".$noMesIni."-".$i."/".$volumenSec[1];
				}
			}
		//Creamos un nuevo contador para seguir con el contador de la siguiente
		$cont2=$cont-1;
		//Verificamos que el numero de mes sea diferente del mes de fin; si es igual solo se llevaraq a cabo el primer ciclo indicando que las fechas son del mismo mes
		if($noMesIni!=$noMesFin){
			for($j=1;$j<=$diaFin;$j++){
				$diaSemanaFin = date("wday", mktime(00, 00, 00, $noMesFin, $j, $anioFin));
				$diaSemanaFin=substr($diaSemanaFin,0,1);
				$jAux=strlen($j);
				if($diaSemanaFin!=0){
					if($j<10&&$jAux<2){
						$volumenes[]=$anioFin."-".$noMesFin."-"."0".$j."/".$presupuesto*($cont2+1);
					}
					else
						$volumenes[]=$anioFin."-".$noMesFin."-".$j."/".$presupuesto*($cont2+1);
					$cont2++;
			 	}
				else{
					//Contamos el arreglo
					$numReg=count($volumenes);
					if($numReg>0){
						$numReg=$numReg-1;
						//Seccionamos el arreglo para obtener unicamente el valor ya que de lo contrario se concatenan mas de los valores necesarios
						$volumenSec=split("/",$volumenes[$numReg]);
						$concepto=$volumenSec[1];
					}
					else{
						$concepto=$presupuesto*$cont;
					}
					if($j<10&&$jAux<2){
						$volumenes[]=$anioFin."-".$noMesFin."-"."0".$j."/".$concepto;
					}
					else{	
						$volumenes[]=$anioFin."-".$noMesFin."-".$j."/".$concepto;
					}
			 	}
			}
		}
	}
		return $volumenes;

}


	 //Funcion para almacenar la información de la Bitácora en la BD
	function guardarRegistroBitacora(){
	//Incluimos los archivos de conexión
		include_once("../../includes/conexion.inc");
		include_once("../../includes/func_fechas.php");
		//Conectar se a la Base de Datos
		$conn = conecta("bd_produccion");
		
		
		//Recuperar la informacion del post
		$fecha = modFecha($_POST['txt_fecha'],3);
		$volProducido = str_replace(",","",$_POST['txt_volProducido']);
		
		//Verificamos si viene el combo Activo de Destino o viene de la caja de texto para proceder a almacenarlo
		if (isset($_POST["cmb_destino"]))
			$destino=$_POST["cmb_destino"];
		else{
			$destino=strtoupper($_POST["txt_nuevoDestino"]);
			$idDestino=generarIdDestino();
			//Mandar llamar la funcion que guarda el nuevo destino
			guardarDestino($idDestino,$destino);
			$destino=$idDestino;
		}
		$observaciones=strtoupper($_POST['txa_observaciones']);
		
		//Crear la Sentencia SQL para Alamcenar los materiales agregados 
		$stm_sql= "UPDATE datos_bitacora SET (catalogo_destino_id_destino='$destino',bitacora_produccion_fecha='$fecha', vol_producido='$volProducido',
				  observaciones='$observaciones' WHERE no_concepto='$_POST[hdn_concepto]')";		
		
		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);
		
		//Verificar Resultado
		if ($rs){
			//Guardar la operacion realizada
			registrarOperacion("bd_produccion",$fecha,"RegistrarProduccion",$_SESSION['usr_reg']);
			$conn = conecta("bd_produccion");
			//echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			$error = mysql_error();
			//echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
 		//Cerrar la conexion con la BD		
		//mysql_close($conn); 
	 }// Fin function guardarMezcla()	
	

	//Funcion que permite mostrar los registros de la bitacora
	function mostrarBitacora(){
		//Inlcuimos archivo para modificar las fechas
		include_once("../../includes/func_fechas.php");

		//Conectar a la BD de Producción
		$conn = conecta("bd_produccion");
		$destino=$_GET['destino'];

		//Obtener la fecha segun corresponda
		if(isset($_GET["fecha"])){
			$fecha=$_GET["fecha"];
		}
		else{
			$fecha="";
		}

		//Crear sentencia SQL
		$sql_stm = "SELECT catalogo_destino_id_destino, destino, bitacora_produccion_fecha, vol_producido, observaciones, datos_bitacora.no_concepto FROM (datos_bitacora JOIN 
					catalogo_destino ON catalogo_destino_id_destino=id_destino) WHERE  bitacora_produccion_fecha='$fecha' AND catalogo_destino_id_destino = '$destino'";

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			echo "<caption><strong>Registro de Producci&oacute;n ".modFecha($fecha,1)."</strong></caption>";
			echo "<table width='100%' cellpading='5'>      			
			<tr>
				<td class='nombres_columnas'>SELECCIONAR</td>
				<td class='nombres_columnas'>VOLUMEN PRODUCIDO</td>
				<td class='nombres_columnas'>DESTINO</td>
				<td class='nombres_columnas'>OBSERVACIONES</td>
			</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{	
			echo "     			
				<tr>
					<td class='nombres_filas' width='6%' align='center'>
						<input type='radio' id='rdb_produccion' name='rdb_produccion'
						value='$datos[bitacora_produccion_fecha]/"."$datos[vol_producido]/"."$datos[destino]/"."$datos[no_concepto]"."'/>
					</td>
					<td class='$nom_clase'>".number_format($datos['vol_producido'],2,".",",")."</td>
					<td class='$nom_clase'>$datos[destino]</td>
					<td class='$nom_clase'>$datos[observaciones]</td>";			
				echo "</tr>";
					//Gurdar los datos en arreglo 
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			echo "<input type='hidden' name='hdn_cant' id='hdn_cant' value='$cont'/>";
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "	</table>";
			
			return 1;
		}
		else{//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'>No se Encontraron resultados </u></em>";
			return 0;
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);	
	}//fin function 
	
	
	//Función que permite eliminar el registro de produccion seleccionado
	function eliminarRegistroProduccion(){
	
		//Incluimos archivo de conexión
		include_once("../../includes/conexion.inc");
		
		//Realizar la conexion a la BD de Produccion
		$conn = conecta("bd_produccion");
		
		//REcuperamos los datos que enviamos en el radiobutton seleccionado anteriormente
		$datosProd=split("/",$_POST["rdb_produccion"]);
		$fecha=$datosProd[0];
		$produccion=$datosProd[1];
		$destino=$datosProd[2];
		$no_concepto=$datosProd[3];
		
		//Creamos la conslulta SQL que permite eliminar el Equipo de la BD
		$stm_sql2 ="DELETE FROM datos_bitacora WHERE bitacora_produccion_fecha='$fecha' AND vol_producido='$produccion' AND no_concepto='$no_concepto'";
		
		//Ejecutamos la consulta
		$rs2=mysql_query($stm_sql2);
			
		//Registramos la operación en la bitacora de movimientos
		registrarOperacion("bd_produccion",$_POST["rdb_produccion"],"EliminarRegProduccion",$_SESSION['usr_reg']);
		
		//Redireccionamos a la pantalla de éxito
		if($rs2){
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}	
		//Cerramos la conexion con la Base de Datos
		//mysql_close($conn);	
	}
	
	
	//Funcion que se encarga de modifica de modificar la informacion del registro seleccionado
	function modificarProduccion(){
	
		//Conectar a la BD de bd_produccion
		$conn = conecta("bd_produccion");
		
		
		//REcuperamos los datos del radio button seleccionado
		$datosProd=split("/",$_POST["rdb_produccion"]);
		$fecha=$datosProd[0];
		$produccion=$datosProd[1];
		$destino=$datosProd[2];
		$no_concepto=$datosProd[3];
		$id_destino=$_GET['destino'];
		
		//Crear sentencia SQL
		$sql_stm ="SELECT * FROM (datos_bitacora JOIN catalogo_destino ON catalogo_destino_id_destino=id_destino)  WHERE bitacora_produccion_fecha = '$fecha' 
				   AND vol_producido='$produccion' AND destino='$destino' AND no_concepto=$no_concepto AND catalogo_destino_id_destino='$id_destino'";

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);

		//Confirmar que la consulta de datos fue realizada con exito.
		$datos=mysql_fetch_array($rs);?>
		<fieldset class="borde_seccion" id="tabla-modificarProduccion" name="tabla-modificarProduccion">
		<legend class="titulo_etiqueta">Ingresar los Datos a Modificar</legend>	
		<br>
		<form onsubmit="return valFormModificarProduccion1(this);" name="frm_modificarProduccion" method="post"  id="frm_modificarProduccion"  
		action="frm_modificarProduccion2.php?fecha=<?php echo $fecha;?>">
		<table width="812" height="201" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="130"><div align="right">*Volumen Producido</div></td>
				<td width="217">
					<input type="text" name="txt_volProducido" id="txt_volProducido" maxlength="10" size="10" class="caja_de_texto" 
					value="<?php echo number_format($datos['vol_producido'],2,".",",");?>" onchange="formatCurrency(value,'txt_volProducido');" /> m&sup3;				</td>
				<td width="156"><div align="right">Fecha </div></td>
				<td width="242"> 
					<input name="txt_fecha" id="txt_fecha" readonly="readonly" type="text" value="<?php echo modFecha($datos['bitacora_produccion_fecha'],1);?>" size="10"
					width="90"/>
					<input type="hidden" name="hdn_concepto" value="<?php echo $datos['no_concepto'];?>"/>				</td>
			</tr>
			<tr>
				<td width="130"><div align="right">*Destino</div></td>
				<td width="217">
					<?php
					$band="";
					 if($datos["destino"]=="COLADO"||$datos["destino"]=="COLADOS"){?>
						<input name="txt_coladoFijo" id="txt_coladoFijo" type="text" class="caja_de_texto" size="40" readonly="readonly" 
						value="<?php echo $datos["destino"];?>" onchange="obtenerPresupuesto(cmb_destino.value,txt_fecha.value,'txt_volPresupuestado');" />
						<input type="hidden" name="ckb_nuevoDestino" id="ckb_nuevoDestino"/>
						<input type="hidden" name="cmb_destino" id="cmb_destino" value="0"/>
						<input type="hidden" name="hdn_destino" id="hdn_destino" value="<?php echo $datos["catalogo_destino_id_destino"];?>"/>
					<?php 
					$band=1;
					}
					else{
						$result=cargarComboConId("cmb_destino","destino","id_destino","catalogo_destino","bd_produccion","Seleccione","$datos[id_destino]","activarBotonModificar(this)"); 
						if($result==0) 
						echo "<label class='msje_correcto'>No hay Destinos Registrados</label>
						<input type='hidden' name='cmb_destino' id='cmb_destino' disabled='disabled'/>";
					}?>				</td>
					
					<td width="156"><div align="right">Volumen Presupuestado Diario</div></td>
				<td>
					<?php $presupuesto=obtenerDatoFechas("bd_produccion", "presupuesto", "vol_ppto_dia", "fecha_inicio", "fecha_fin", $datos['bitacora_produccion_fecha']);
					if($presupuesto!=""){?>
						<input type="text" name="txt_volPresupuestado" id="txt_volPresupuestado"  value="<?php echo $presupuesto;?>"readonly="readonly" 
						maxlength="10" size="10" class="caja_de_texto" onkeypress="return permite(event,'num',2);"/> m&sup3;
					<?php 
					}else{ 
						echo "<label class='msje_correcto'>Es Necesario Agregar un Presupuesto</label>";?>
						<input type="hidden" name="txt_volPresupustado" id="txt_volPresupustado"/>
					<?php }?>				
				</td>	
			</tr>
			<tr>
				<td><div align="right">Observaciones</div></td>
				<td>
					<textarea name="txa_observaciones" id="txa_observaciones" class="caja_de_texto" cols="40" rows="3" maxlength="120" 
					onkeyup="return ismaxlength(this)"><?php echo $datos["observaciones"];?></textarea>				</td>			
				
			</tr>
			<tr>
				<td colspan="5"><strong>* Datos marcados con asterisco son <u>obligatorios</u></strong></td>
			</tr>
			<tr>
				<td colspan="4">
					<div align="center">
						<?php 
							if(isset($_POST["rdb_produccion"])){
								$seccRadio=split("/", $_POST['rdb_produccion']);
								$concepto=$seccRadio[3];
							}
							else
								$concepto="";
						 ?>
						<input type="hidden" name="hdn_cmbTipo" id="hdn_cmbTipo"/>
						<input type="hidden" name="hdn_destino" id="hdn_destino" value="<?php echo $_GET['destino']; ?>"/>
						<input type="hidden" name="hdn_concepto" id="hdn_concepto" value="<?php echo $concepto;?>"/>
						<input type="hidden" name="cmb_tipoModificar" id="cmb_tipoModificar" value="produccion"/>
						<input type="hidden" name="hdn_botonSeleccionado" id="hdn_botonSeleccionado" value="" />
						<input name="sbt_guardarModificacionProduccion" type="submit" class="botones" id="sbt_guardarModificacionProduccion" 
						value="Guardar" title="Guardar Modificar" onmouseover="window.status='';return true" 
						onclick="hdn_botonSeleccionado.value='sbt_guardarProduccion'"/>   
						<?php if($datos["destino"]=="COLADO"||$datos["destino"]=="COLADOS"){?>
							&nbsp;&nbsp;&nbsp;
							<input name="rst_limpiar" type="reset" class="botones"  value="Restablecer" title="Restablecer Formulario" 
							onMouseOver="window.status='';return true"/>    
						<?php }
							else{?>	
							&nbsp;&nbsp;&nbsp;
							<input name="rst_limpiar" type="reset" class="botones"  value="Restablecer" title="Restablecer Formulario" 
							onMouseOver="window.status='';return true" onclick="btn_detalles.style.visibility='hidden';resetearFomulario();"/>   
						<?php }?> 	    	
						&nbsp;&nbsp;&nbsp;
						<input name="sbt_cancelar" type="submit" class="botones" value="Cancelar" 
						title="Regresar a P&aacute;gina Principal Modificar de Producci&oacute;n" 
						onMouseOver="window.status='';return true" onclick="hdn_botonSeleccionado.value='sbt_cancelarProduccion';location.href='frm_modificarProduccion2.php?fecha=<?php echo $_GET["fecha"];?>'"/>	
						&nbsp;&nbsp;&nbsp;
						<?php if($datos["destino"]=="COLADO"||$datos["destino"]=="COLADOS"){?>
						<input type="button" name="btn_detalles" id="btn_detalles" class="botones_largos" value="Modificar Detalles" 
						onMouseOver="window.estatus='';return true" title="Modificar Detalles de la Producci&oacute;n" 
						onClick="	window.open('verModificarRegistroProduccion.php?volumen=<?php echo $datos["vol_producido"];?>&fecha=<?php echo modFecha($fecha,1);?>&concepto=<?php echo $concepto;?>&destino=<?php echo $_GET['destino']; ?>','_blank','top=50, left=50, width=900, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>	
						<?php }?>	
						<input type="button" name="btn_detalles" id="btn_detalles" class="botones_largos" value="Modificar Detalles" 
						onMouseOver="window.estatus='';return true"  style="visibility:hidden"
						title="Modificar Detalles de la Producci&oacute;n" 
						onClick="envioDatosGetModificar();"/>															
					</div>				</td>
			</tr>
		</table>
		</form>
	</fieldset>	<?php
	}						


	//Funcion encargada de mostrar la produccion en una ventana pop up en caso de existir
	function mostrarProduccionVentana($produccion, $fecha, $volumen,$concepto, $destino){
		//Verificamos que exista la session
		if($_SESSION['produccion']){
			echo "<table cellpadding='5' width='100%' align='center'> 
					<caption class='titulo_etiqueta'>Detalle del Colado </caption>";
			echo "<tr>
						<td class='nombres_columnas' align='center'>CLIENTE</td>
						<td class='nombres_columnas' align='center'>M&sup3;</td>
						<td class='nombres_columnas' align='center'>COLADO</td>
						<td class='nombres_columnas' align='center'>OBSERVACIONES</td>
						<td class='nombres_columnas' align='center'>FACTURA</td>
						<td class='nombres_columnas' align='center'>TIPO</td>
						<td class='nombres_columnas' align='center'>NO. REMISI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>PAGADO</td>
						<td class='nombres_columnas' align='center'>COSTO</td>
						<td colspan='2' class='nombres_columnas' align='center'>BORRAR</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;		
			foreach($_SESSION['produccion'] as $key => $arrVale){
				echo "<tr>
						<td class='nombres_filas' align='center'>$arrVale[cliente]</td>					
						<td class='$nom_clase' align='center'>".number_format($arrVale['volumen'],2,".",",")."</td>	
						<td class='$nom_clase' align='center'>$arrVale[colado]</td>
						<td class='$nom_clase' align='center'>$arrVale[observaciones]</td>
						<td class='$nom_clase' align='center'>$arrVale[factura]</td>
						<td class='$nom_clase' align='center'>$arrVale[tipo]</td>
						<td class='$nom_clase' align='center'>$arrVale[remision]</td>
						<td class='$nom_clase' align='center'>$arrVale[pagado]</td>
						<td class='$nom_clase' align='center'>$".number_format($arrVale['costo'],2,".",",")."</td>";?>
						<td class="<?php echo $nom_clase;?>" align="center"	><input type="image" src="../../images/borrar.png" width="30" height="25"
						border="0" title="Borrar Registro" 
						onclick="location.href='verModificarRegistroProduccion.php?noRegistro=<?php echo $key;?>&volumen=<?php echo $volumen;?>&fecha=<?php echo $fecha;?>&concepto=<?php echo $concepto;?>&destino=<?php echo $destino;?>'"/>
					</td><?php					
			echo "</tr>";					
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			};
			echo " </table>";
		}
	}
	
	
	//Funcion que permite mostrar la informacion de la bd en una fecha especifica en la ventana pop up
	function mostrarProduccionBD($fecha, $concepto){

		//Conectar a la BD de bd_produccion
		$conn = conecta("bd_produccion");
						
		//Crear sentencia SQL
		$sql_stm ="SELECT * FROM detalle_colados WHERE bitacora_produccion_fecha = '$fecha' AND no_concepto='$concepto'";
	
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			echo "<table cellpadding='5' width='100%' align='center'> 
					<caption class='titulo_etiqueta'>COLADOS REGISTRADOS </caption>     			
				<tr>
					<td align='center' class='nombres_columnas'>CLIENTE</td>
					<td align='center' class='nombres_columnas'>M&sup3;</td>
					<td align='center'class='nombres_columnas'>COLADO</td>
					<td align='center' class='nombres_columnas'>OBSERVACIONES</td>
					<td align='center' class='nombres_columnas'>FACTURA</td>
					<td align='center' class='nombres_columnas'>TIPO</td>
					
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;			
			do{	
			echo "     			
				<tr>
					<td align='center' class='$nom_clase'>$datos[cliente]</td>
					<td align='center' class='$nom_clase'>".number_format($datos['volumen'],2,".",",")."</td>
					<td align='center' class='$nom_clase'>$datos[colado]</td>
					<td align='center' class='$nom_clase'>$datos[observaciones]</td>
					<td align='center' class='$nom_clase'>$datos[factura]</td>
					<td align='center' class='$nom_clase'>$datos[tipo_colado]</td>
				</tr>";
					//Gurdar los datos en arreglo 
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "	</table>";
			
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);	
	}//fin function 


	//Funcion que nos permite calcular los datos de la sesion 
	function calcularVolumen(){
		//Igualamos la variable volumen 0 para no encontrar la variable como indefinida
		$totalVolumen=0;
		//Verificamos que exista la sesion
		if(isset($_SESSION['produccion'])){
			//Recorremos el arreglo en busqueda del dato
			foreach ($_SESSION['produccion'] as $key => $value) {
				foreach ($_SESSION['produccion'][$key] as $ind => $valor) {
					switch($ind){
						case "volumen":
							$totalVolumen+=str_replace(",","",$valor);
						break;
					}
				}
			}
		}
		return $totalVolumen;
	}
	
	 //Funcion para almacenar la información modificada de la Bitácora en la BD
	function guardarModificacionBitacora(){

		//Incluimos los archivos de conexión
		include_once("../../includes/conexion.inc");
		include_once("../../includes/func_fechas.php");
	
		//Conectar se a la Base de Datos
		$conn = conecta("bd_produccion");
			
		//Recuperar la informacion del post
		$fecha = modFecha($_POST['txt_fecha'],3);
		$volProducido = str_replace(",","",$_POST['txt_volProducido']);
		
		 if(!isset($_POST["txt_coladoFijo"])){
			//Verificamos si viene el combo Activo de Destino o viene de la caja de texto para proceder a almacenarlo
			if (isset($_POST["cmb_destino"]))
				$destino=$_POST["cmb_destino"];
			else{
				$destino=strtoupper($_POST["txt_nuevoDestino"]);
				$idDestino=generarIdDestino();
			
				//Mandar llamar la funcion que guarda el nuevo destino
				guardarDestino($idDestino,$destino);
				$destino=$idDestino;
			}
		}
		else
			$destino=$_POST["hdn_destino"];
		
		//Cambiamos las observaciones a mayusculas
		$observaciones=strtoupper($_POST['txa_observaciones']);
		
		//Tomamos el concepto del POST
		$concepto=$_POST['hdn_concepto'];
		
		//Crear la Sentencia SQL para Alamcenar los materiales agregados 
		$stm_sql= "UPDATE datos_bitacora SET catalogo_destino_id_destino='$destino',bitacora_produccion_fecha='$fecha', vol_producido='$volProducido',
				  observaciones='$observaciones' WHERE bitacora_produccion_fecha='$fecha' AND no_concepto='$concepto'";		
		
		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);
		
		//Verificar Resultado
		if ($rs){
			//Guardar la operacion realizada
			registrarOperacion("bd_produccion",$fecha,"ModificarProduccion",$_SESSION['usr_reg']);
			$conn = conecta("bd_produccion");
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
 		//Cerrar la conexion con la BD		
		//mysql_close($conn); 
	 }// Fin function 	
	
	
	//Funcion quen nos permite guardar el Detalle del colado en la ventana emergente
	function guardarModificacionDetalleColado(){
		
		//Incluimos los archivos de conexión
		include_once("../../includes/conexion.inc");
		include_once("../../includes/func_fechas.php");
		
		//Conectamos con la BD
		$conn = conecta("bd_produccion");
		//Tomamos el concepto del POST
		$concepto=$_POST['hdn_concepto'];
		$fecha=modFecha($_POST["txt_fecha"],3);
		
		$stm_del="DELETE FROM detalle_colados WHERE bitacora_produccion_fecha='$fecha' AND no_concepto='$concepto'";
		$rsDel = mysql_query($stm_del);
		
		//Declaramos la variable bandera para control de la consulta
		$band=0;
		
		
		//Verificamos si viene el combo Activo de Destino o viene de la caja de texto para proceder a almacenarlo
		if (isset($_POST["cmb_destino"]))
			$destino=$_POST["cmb_destino"];
		else{
			$destino=strtoupper($_POST["txt_nuevoDestino"]);
		}
		if(isset($_POST["hdn_destino"]))
			$destino=$_POST["hdn_destino"];
		
		if(isset($_SESSION['produccion'])){
			//Recorremos el arreglo produccion para insertar en la BD los datos guardados en el mismo
			foreach($_SESSION['produccion'] as $ind => $produccion){
				//Creamos la sentencia SQL para insertar los datos en la tabla detalle colados
				$stm_sql="INSERT INTO detalle_colados (catalogo_destino_id_destino,bitacora_produccion_fecha, cliente, volumen, colado, observaciones, factura, tipo_colado, no_concepto, no_remision, pagado, costo)
				VALUES('$destino','$fecha','$produccion[cliente]', '$produccion[volumen]', '$produccion[colado]', 
				'$produccion[observaciones]', '$produccion[factura]', '$produccion[tipo]', '$concepto', '$produccion[remision]', '$produccion[pagado]', '$produccion[costo]')";
						
				//Ejecutar la sentencia previamente creadas
				$rs = mysql_query($stm_sql);
				if(!$rs)
					$band = 1;						
				//Romper el proceso de registro del detalle de la entrada en el caso de que existan errores	
				if($band==1)
					break;	
			}
		}		
		if ($band==1){
			echo "<meta http-equiv='refresh' content='0;url=error.php'>";
		}
		else{
			registrarOperacion("bd_produccion",$fecha,"ModRegistroDetalleCol",$_SESSION['usr_reg']);
			$conn = conecta("bd_produccion");								
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		//Cerrar la conexion con la BD		
		//mysql_close($conn);		
	}
	
	
	//Funcion que permite obtener el id del Destino
	function generarIdDestino(){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_produccion");
		
		//Declaramos la variable que nos servira para almacenar el id generado por la consulta
		$id="";
		
		//Crear la sentencia para obtener el maximo id registrado en el catalogo
		$stm_sql = "SELECT COUNT(id_destino) AS num, MAX(id_destino)+1 AS cant FROM catalogo_destino";

		//Ejecutamos la consulta	
		$rs = mysql_query($stm_sql);
		
		//Verificamos la existencia de Datos
		if($datos=mysql_fetch_array($rs)){
			//Si el resultado es menor que cero concatenamos la cantidad
			if($datos['num']>0){
				$id .= ($datos['cant']);
			}
			//De lo contrario concatenamos uno
			else {
				$id .= "1";
			}
		}
		else 
			echo"error".mysql_error();		
		return $id;
	}
	
	//Funcion que permite guardar el Destino en la bitacora de produccion
	function guardarDestino($id,$destino){

		//Conectar se a la Base de Datos
		$conn = conecta("bd_produccion");
		
		//Crear la Sentencia SQL para Alamcenar lel nuevo destino 
		$stm_sql= "INSERT INTO catalogo_destino(id_destino,destino)	VALUES ('$id','$destino')";		

		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);

		//Cerrar la conexion con la BD		
		//mysql_close($conn); 
	}	
	
	
	/**********************************************************************MODIFICAR EQUIPOS*******************************************************************/
	
	//Funcion que muestra el encabezado de la tabla equipos
	function mostrarEncabezadoModificar(){
		echo "<caption><strong>Ingresar los Datos del Equipo</strong></caption>";
			echo "<table  width='100%'>      			
			<tr>
				<td width='10%' class='nombres_columnas'>SELECCIONAR</td>
				<td width='20%' class='nombres_columnas'>EQUIPO</td>
				<td width='10%' class='nombres_columnas'>M&sup3;</td>
				<td width='60%' class='nombres_columnas'>OBSERVACIONES</td>
			</tr>
		</table>";
	}
	
	//Funcion que permite consultar los equipos
	function mostrarEquiposModificar(){
	
		//Conectar a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
						
		//Crear sentencia SQL
		$sql_stm = "SELECT id_equipo,bd_mantenimiento.equipos.nom_equipo, vol_producido, observaciones FROM (bd_mantenimiento.equipos JOIN bd_produccion.equipos 
					ON bd_mantenimiento.equipos.id_equipo=bd_produccion.equipos.nom_equipo) WHERE bitacora_produccion_fecha='$_GET[fecha]' 
					ORDER BY  bd_mantenimiento.equipos.nom_equipo ";
	
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_alerta = "	<label class='msje_correcto' align='center'>Ingresar los Datos del Equipo</em></label>";										

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			echo "<table  width='100%'>";
			$nom_clase = "renglon_gris";
			$cont = 1;			
			do{	
			echo "     			
				<tr>
					<td width='10%' class='$nom_clase'>"; ?>
						<input type="checkbox" name="ckb_equipo<?php echo $cont;?>" id="ckb_equipo<?php echo $cont;?>" 
						value="<?php echo $datos["nom_equipo"]?>" onClick="activarCamposMod(this, <?php echo $cont; ?>)" 
						onkeypress="return permite(event,'num', 2);"/><?php
					echo "</td>	
					<td width='20%' class='$nom_clase'>$datos[nom_equipo]</td>
					<td width='10%' class='$nom_clase'>"; ?>
						<input type="hidden" name="hdn_nombre<?php echo $cont; ?>"  value="<?php echo $datos["nom_equipo"];?>" id="hdn_nombre<?php echo $cont; ?>"/>
						<input type="text" name="txt_metros<?php echo $cont;?>" id="txt_metros<?php echo $cont;?>" size="10" readonly="readonly"
						onkeypress="return permite(event,'num', 2);" class="caja_de_texto" value="<?php echo $datos['vol_producido'];?>"
						onchange="formatCurrency(value,'txt_metros<?php echo $cont;?>');" /><?php 
						 
					echo "</td>
					<td width='60%' class='$nom_clase'>"; ?>
						<input type="text" name="txt_observaciones<?php echo $cont;?>" class="caja_de_texto" id="txt_observaciones<?php echo $cont;?>" size="80" 
						readonly="readonly" onkeypress="return permite(event,'num_car', 0);" value="<?php echo $datos['observaciones'];?>" /><?php 
					echo "</td>		
				</tr>";
					//Gurdar los datos en arreglo 
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			echo "<input type='hidden' name='hdn_cant' id='hdn_cant' value='$cont'/>";
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "	</table>";
			
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'>No Existen Equipos Registrados</label>";// fin  if($datos=mysql_fetch_array($rs))
			return 0;
		}
		return 1;
			
		//Cerrar la conexion con la BD		
		mysql_close($conn);	
	}//fin function 
	
	if(isset($_POST["sbt_guardarModEquipo"]))
		guardarEquipoModificado();
	
	//Funcion que guarda los cambios en los registros seleccionados
	function guardarEquipoModificado(){
		//Se incluye el archivo de conexión para manejo de bd
		include_once("../../includes/conexion.inc");
	
		//Se incluye el archivo de las operaciones BD	
		include_once("../../includes/op_operacionesBD.php");
		
		//Archivo para realizar las modificaciones en las fechas segun sea necesario
		include_once ("../../includes/func_fechas.php");
	
		//Conectamos con la BD
		$conn = conecta("bd_produccion");
	
		//Creamos la variable cantidad de la function mostrar Equipos para saber el numero de registros
		$cantidad=$_POST["hdn_cant"]-1;

		//Iniciamos la variable de control interna
		$ctrl=0;

		//Variable bandera para la insercion de datos
		$flag=0;

		//Variable para almacenar el error en caso de generarse
		$error="";
		do{
			//Incrementamos el contador interno; para saber el numero de registro
			$ctrl++;
			//Verificamos que este definido el Odometro inicial y agregamos variable ctrl para saber que registro sera insertado en la BD
			if(isset($_POST["ckb_equipo$ctrl"])&&$_POST["ckb_equipo$ctrl"]!=""){
				//creamos la variable para almacenar la fecha
				$fecha = modFecha ($_POST["txt_fecha"],3);
				$ckb_equipo = $_POST["ckb_equipo$ctrl"];
				$txt_metros = str_replace(",","",$_POST["txt_metros$ctrl"]);
				$txt_observaciones = strtoupper($_POST["txt_observaciones$ctrl"]);				
				//Creamos la sentencia SQL
				$stm_sql="UPDATE equipos SET vol_producido='$txt_metros',
								observaciones='$txt_observaciones' WHERE bitacora_produccion_fecha='$fecha' AND nom_equipo='$ckb_equipo'";
				//Ejecutar la sentencia previamente creada
				$rs=mysql_query($stm_sql);
				
				//Guardar el registro de movimientos
				registrarOperacion("bd_produccion",$ckb_equipo,"ModificarEquipo",$_SESSION['usr_reg']);
			}
		
			//Conectamos con la BD
			$conn = conecta("bd_produccion");
			//verificamos que la sentencia sea ejecutada con exito
			if (!$rs){
				$flag=1;
				//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
				$error="**** Error : ".mysql_error();
				break;
			}
			
		//Mientras que control sea menor a la cantidad se registraran los datos en la BD	
		}while($ctrl<=$cantidad);
			
		//verificamos que la sentencia sea ejecutada con exito
		if ($flag==0){
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			echo "<meta http-equiv='refresh' content='10;url=error.php?err=$error'>";
		}	
	
		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);	
	
	}// Fin de la funcion 


/********************************************************************************MODIFICAR SEGURIDAD*******************************************/
	
	//Verificamos que exista el boton eliminar si es asi procedemos a eliminar el registro seleccionado
	if(isset($_POST["sbt_eliminarSeguridad"]))
		eliminarRegistroSeguridad();
				
	//Si se encuentra definindo el boton sbt_finalizarRegistro procedemos a guardar el registro
	if(isset($_POST["sbt_finalizarSeguridad"]))
		guardarSeguridadMod();

	//Funcion que permite consultar los registros  de Seguridad
	function mostrarSeguridad(){
		include_once("../../includes/func_fechas.php");
		//Conectar a la BD de Producción
		$conn = conecta("bd_produccion");
						
		if(isset($_GET["fecha"]))				
			//Recuperamos la fecha contenida en el GET
			$fecha=$_GET["fecha"];				
		else
			$fecha="";

		//Crear sentencia SQL
		$sql_stm = "SELECT * FROM seguridad WHERE bitacora_produccion_fecha='$fecha' ORDER BY num ";

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			echo "<caption><strong>Seleccionar Registro de Seguridad a Modificar</strong></caption>";
			echo "<table  width='100%'>      			
					<tr>
						<td class='nombres_columnas'>SELECCIONAR</td>
						<td class='nombres_columnas'>N&Uacute;MERO</td>
						<td class='nombres_columnas'>FECHA</td>				
						<td class='nombres_columnas'>TIPO</td>
						<td class='nombres_columnas'>OBSERVACIONES</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{
				echo "     			
					<tr>
						<td class='nombres_filas' width='6%' align='center'>
						<input type='radio' id='rdb_seguridad' name='rdb_seguridad' 
						value='$datos[bitacora_produccion_fecha]/"."$datos[num]/"."$datos[tipo]/"."$datos[observaciones]"."'/>
					</td>
						<td class='$nom_clase'>$datos[num]</td>
						<td class='$nom_clase'>".modFecha($datos['bitacora_produccion_fecha'],1)."</td>
						<td class='$nom_clase' align='center'>$datos[tipo]</td>
						<td class='$nom_clase' align='center'>$datos[observaciones]</td>";				
					echo "</tr>";
			
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "	</table>";
			
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'>No se Encontraron resultados</u></em>";
			return 0;
		}
		return 1;
		//Cerrar la conexion con la BD		
		//mysql_close($conn);	
	}//fin function 


	//Función que permite eliminar el Registro segun sea seleccionado
	function eliminarRegistroSeguridad(){
		//Incluimos archivo de conexión
		include_once("../../includes/conexion.inc");
		
		//Realizar la conexion a la BD de Produccion
		$conn = conecta("bd_produccion");
		
		//Recuperamos del Registro cargados en el radio button
		$datosSeg=split("/",$_POST["rdb_seguridad"]);
		$fecha=$datosSeg[0];
		$num=$datosSeg[1];
		$tipo=$datosSeg[2];
		
		//Creamos la conslulta SQL que permite eliminar el Equipo de la BD
		$stm_sql2 ="DELETE FROM seguridad WHERE bitacora_produccion_fecha='$fecha' AND num='$num' AND tipo='$tipo'";
		
		//Ejecutamos la consulta
		$rs2=mysql_query($stm_sql2);
	
		if($rs2){
			//Registramos la operación en la bitacora de movimientos
			registrarOperacion("bd_produccion",$fecha,"EliminoRegSeguridad",$_SESSION['usr_reg']);
			//Redireccionamos a la pantalla de éxito
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}	
		//Cerramos la conexion con la Base de Datos
		//mysql_close($conn);	
	}	
	
	
	//Desplegar los registros de seguridad contenidos en el arreglo de sesion Seguridad
	function mostrarResultados($seguridad){
		echo "<table cellpadding='5' width='100%'>";
		echo "<caption><strong><label align='center'>INCIDENTES/ACCIDENTES DE SEGURIDAD</label></strong></caption>";
		echo "      			
			<tr>
				<td width='10%' class='nombres_columnas' align='center'>N&Uacute;MERO</td>
				<td width='20%' class='nombres_columnas' align='center'>TIPO</td>
				<td width='70%' class='nombres_columnas' align='center'>OBSERVACIONES</td>
        	</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		foreach ($seguridad as $ind => $registro) {
			echo "<tr>";
			foreach ($registro as $key => $value) {
				switch($key){
					case "partida":
						echo "<td align='center'  class='nombres_filas'>$value</td>";
					break;
					case "tipo":
						echo "<td align='center' class='$nom_clase'>$value</td>";
					break;
					case "observaciones":
						echo "<td align='center' class='$nom_clase'>$value</td>";
					break;
				}				
			}
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
			echo "</tr>";			
		}
		echo "</table>";
	}//Fin de la funcion mostrarSeguridad($seguridad)	
	

	//Funcion que permite guardar los registros de seguridad
	function guardarSeguridadMod(){
		//Incluimos los archivos de conexión
		include_once("../../includes/conexion.inc");
		
		//Inuimos el archivo necesrio para modificar las fechas segun corresponda
		include_once("../../includes/func_fechas.php");
		
		//Conectamos con la BD
		$conn = conecta("bd_produccion");
		
		//Declaramos la variable bandera para control de la consulta
		$band=0;
		
		//Guardamos la fecha en el formato necesario
		$fecha=modFecha($_POST["txt_fecha"],3);
		
		//Recorremos el arreglo mecanicos para insertar en la BD los datos guardados en el mismo
		foreach($_SESSION['seguridad'] as $ind => $seguridad){
			//Creamos la sentencia SQL para insertar los datos en la tabla Mecanico
			$stm_sql="INSERT INTO seguridad (bitacora_produccion_fecha,num, tipo,  observaciones)
			VALUES('$fecha','$seguridad[partida]','$seguridad[tipo]', '$seguridad[observaciones]')";
					
			//Ejecutar la sentencia previamente creadas
			$rs = mysql_query($stm_sql);
			if(!$rs)
				$band = 1;						
			//Romper el proceso de registro del detalle de la entrada en el caso de que existan errores	
			if($band==1)
				break;	
		}
		
		if ($band==1){
			echo "<meta http-equiv='refresh' content='0;url=error.php'>";
		}
		else{
			registrarOperacion("bd_produccion",$fecha,"RegistroSeguridad",$_SESSION['usr_reg']);
			$conn = conecta("bd_produccion");								
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		//Cerrar la conexion con la BD		
		//mysql_close($conn);		
	}
	
	//Funcion que permite obtener el id del Destino
	function generarIdSeguridad($fecha){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_produccion");
		$id="";
		//Crear la sentencia para obtener el maximo id registrado en el catalogo
		$stm_sql = "SELECT COUNT(num) AS num, MAX(num)+1 AS cant FROM seguridad WHERE bitacora_produccion_fecha='$fecha'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			//Si el resultado es menor que cero concatenamos la cantidad
			if($datos['num']>0){
				$id .= ($datos['cant']);
			}
			//De lo contrario concatenamos uno
			else {
				$id .= "1";
			}
		}
		else 
			echo"error".mysql_error();		
		return $id;
	}
?>