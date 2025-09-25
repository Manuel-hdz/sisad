<?php
	/**
	  * Nombre del Módulo: Mantenimiento
	  * Nombre Programador: Maurilio Hernandez Correa
	  * Fecha: 09/Marzo/2011                                      			
	  * Descripción: Este archivo contiene funciones para el registro de Horometro/ Odometro
	  **/

	// Funcion que se encarga de mostrar solo el encabezado de la tabla esto con el fin de que si hay muchos registros no se recorra el encabezado
	function mostrarTituloHorometro(){
		//Desplegar los encabezados tabla
		echo "				
		<table cellpadding='5' class='tabla_frm'>      			
			<tr>
				<td width='70' class='nombres_columnas'>EDITAR</td>
				<td width='70' class='nombres_columnas'>ID EQUIPO</td>
				<td width='100' class='nombres_columnas'>HORAS ACUMULADAS</td>
				<td width='100' class='nombres_columnas'>FECHA &Uacute;LTIMO REGISTRO</td>
				<td width='100' class='nombres_columnas'>HOR&Oacute;METRO INICIAL</td>
				<td width='100' class='nombres_columnas'>HOR&Oacute;METRO FINAL</td>
				<td width='100' class='nombres_columnas'>HRS. EFECTIVAS</td>
				<td width='100' class='nombres_columnas'>HRS. MANTENIMIENTO<BR>PREVENTIVO</td>
			</tr>
		</table>";
	}//	fin function mostrarTituloHorometro(){
	
	
	// Funcion que se encarga de mostrar los equipos de acuerdo a la area y familia seleccionados
	function mostrarEquiposHorometro(){
		
		//Conectar a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
		
		//Traer el valor de los combos para poder realizar la consulta de acuerdo a los criterios seleccionados
		$area= $_POST['cmb_area'];
		$familia= $_POST['cmb_familia'];
		
		//Esta variable ayudara a notificar si hubo registros o no en la pantalla de Registrar Horómetro
		$resultado = false;
						
		//Crear sentencia SQL
		$sql_stm = "SELECT id_equipo FROM equipos WHERE  area='$area' AND familia='$familia' AND metrica='HOROMETRO' AND estado='ACTIVO' ORDER BY id_equipo";
	
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_alerta = "	<label class='msje_correcto' align='center'>La Familia <em><u>$_POST[cmb_familia]</u></em> no cuenta con Equipos que Manejen <em><u>HOR&Oacute;METRO</u></em>
		</label>";										

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			$nom_clase = "renglon_gris";
			$cont = 1;			
			do{			
				//Obtener el ultimo registro para el Horómetro de cada Vehiculo
				$rs_reg_final = mysql_query("SELECT MAX(fecha) AS fecha,hrs_acum FROM horometro_odometro JOIN acumulado_servicios ON
											horometro_odometro.equipos_id_equipo=acumulado_servicios.equipos_id_equipo
											WHERE acumulado_servicios.equipos_id_equipo = '$datos[id_equipo]'");											 
											
				$reg_final = "No Hay Registro";
				$fecha = "No Hay Registro";
				
				if($datos_reg_final = mysql_fetch_array($rs_reg_final)){
					if($datos_reg_final['hrs_acum']!=NULL)
						$reg_final = number_format($datos_reg_final['hrs_acum'],2,".",",")." Hrs.";
					if($datos_reg_final['fecha']!=NULL)
						$fecha = modFecha($datos_reg_final['fecha'],1);
				}
					
				//Mostrar todos los registros que han sido completados
				echo "
				<table cellpadding='5'>      			
				<tr>
					<td width='70' class='$nom_clase'>"; ?>
						<input type="checkbox" name="ckb_equipo<?php echo $cont;?>" id="ckb_equipo<?php echo $cont;?>" 
						value="<?php echo $datos["id_equipo"]?>" onClick="activarCamposMttoMina(this, <?php echo $cont; ?>)" 
						onkeypress="return permite(event,'num', 2);"/><?php
					echo "</td>	
					<td width='70' class='$nom_clase'><span id='$datos[id_equipo]'>$datos[id_equipo]</span></td>
					<td width='100' class='$nom_clase'>
						$reg_final
						<input type='hidden' name='hdn_regFinal$cont' id='hdn_regFinal$cont' value='$reg_final' />
					</td>
					<td width='100' class='$nom_clase'>$fecha</td>
					<td width='100' class='$nom_clase'>"; ?>
						<input type="hidden" name="hdn_nombre<?php echo $cont; ?>"  value="<?php echo $datos["id_equipo"];?>" id="hdn_nombre<?php echo $cont; ?>"/>
						
						<input type="text" name="txt_horoIni<?php echo $cont;?>" id="txt_horoIni<?php echo $cont;?>" size="10" disabled="disabled"
						onkeypress="return permite(event,'num', 2);" class="caja_de_num"
						onchange="formatCurrency(value,'txt_horoIni<?php echo $cont;?>'); calcularHrsServicio(<?php echo $cont;?>);" /><?php 
						 
					echo "</td>
					<td width='100' class='$nom_clase'>"; ?>
						<input type="text" name="txt_horoFin<?php echo $cont;?>" id="txt_horoFin<?php echo $cont;?>" size="10" disabled="disabled"
						onkeypress="return permite(event,'num', 2);" class="caja_de_num"
						onchange="formatCurrency(value,'txt_horoFin<?php echo $cont;?>'); calcularHrsServicio(<?php echo $cont;?>);" /><?php 
					echo "</td>	
					<td width='100' class='$nom_clase'>"; ?>
						<input type="text" name="txt_hrsEfectivas<?php echo $cont;?>" id="txt_hrsEfectivas<?php echo $cont;?>" size="10" disabled="disabled"
						onchange="formatCurrency(value,'txt_hrsEfectivas<?php echo $cont;?>');" class="caja_de_num"/><?php 
					echo "</td>	
					<td width='100' class='$nom_clase'>"; ?>
						<input type="text" name="txt_hrsMttoPrev<?php echo $cont;?>" id="txt_hrsMttoPrev<?php echo $cont;?>" size="10" disabled="disabled"
						onkeypress="return permite(event,'num', 2);" class="caja_de_num"
						onchange="formatCurrency(value,'txt_hrsMttoPrev<?php echo $cont;?>')" /><?php 
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
			
			$resultado = true;
		}// fin  if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_alerta;
			$resultado = false;
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);	
		
		return $resultado;
	}//fin function mostrarEquiposHorometro(){


	//Verificamos si viene definido sbt_registrar en el post 
	if(isset($_POST["sbt_registrar"])){
		guardarRegHorometro();
	}
	
	//Funcion que guarda los cambios en los registros seleccionados
	function guardarRegHorometro(){
		//Se incluye el archivo de conexión para manejo de bd
		include_once("../../includes/conexion.inc");		
		include_once("../../includes/op_operacionesBD.php");//Manejo de fechas
		include_once ("../../includes/func_fechas.php");
		
		//Variable bandera para la insercion de datos
		$flag=0;
		//Variable para almacenar el error en caso de generarse
		$error="";
		//Creamos la variable cantidad de la function mostrarEquiposHorometro() para saber el numero de registros
		$cantidad=$_POST["hdn_cant"]-1;
		//Iniciamos la variable de control interna
		$ctrl=0;
		session_start();		
		do{
			//Incrementamos el contador interno; para saber el numero de registro
			$ctrl++;
			//Verificamos que este definido el horometro inicial y agregamos variable ctrl para saber que registro sera insertado en la BD
			if(isset($_POST["txt_horoIni$ctrl"])){
				//Conectamos con la BD
				$conn = conecta("bd_mantenimiento");
				//Creamos variables para guardar lo que viene el el post
				$cmb_turno=$_POST["cmb_turno"];
				$txa_observaciones=$_POST["txa_comentarios"];
				//Tomamos la fecha
				$fecha = modFecha($_POST['txt_fechaHorometro'],3);
				$ckb_equipo = $_POST["ckb_equipo$ctrl"];
				$txt_horoIni = str_replace(",","",$_POST["txt_horoIni$ctrl"]);
				$txt_horoFin = str_replace(",","",$_POST["txt_horoFin$ctrl"]);
				$txt_hrsEfectivas = str_replace(",","",$_POST["txt_hrsEfectivas$ctrl"]); 
				$txt_hrsMttoPreventivo = str_replace(",","",$_POST["txt_hrsMttoPrev$ctrl"]); 
				//Creamos la sentencia SQL
				$stm_sql="INSERT INTO horometro_odometro (equipos_id_equipo,fecha,reg_inicial,reg_final,hrs_efectivas,turno,observaciones,mtto_prev)
				VALUES('$ckb_equipo','$fecha','$txt_horoIni','$txt_horoFin','$txt_hrsEfectivas','$cmb_turno','$txa_observaciones','$txt_hrsMttoPreventivo')";

				//Ejecutar la sentencia previamente creada
				$rs=mysql_query($stm_sql);
				
				/***************************/
				//Verificar si el registro de Horas se debe almacenar en la tabla de Horas acumuladas para la generacion de las Alertas
				registrarHorasAcumuladas($ckb_equipo,$txt_hrsEfectivas,$fecha);
				/***************************/
				
				//Cerrar la BD de Mtto
				mysql_close($conn);
				//Guardar el registro de movimientos
				registrarOperacion("bd_mantenimiento",$ckb_equipo,"RegistrarHorometro",$_SESSION['usr_reg']);
				//verificamos que la sentencia sea ejecutada con exito
				if (!$rs){
					$flag=1;
					//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
					$error="**** Error : ".mysql_error();
					break;
				}
			}
		//Mientras que control sea menor a la cantidad se registraran los datos en la BD	
		}while($ctrl<=$cantidad);
		//verificamos que la sentencia sea ejecutada con exito
		if ($flag==0){
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}// Fin de la funcion 

	//Funcion que actualiza las horas acumuladas en la tabla correspondiente a fin de generar la alerta que corresponde
	function registrarHorasAcumuladas($equipo,$horas,$fecha){
		//Obtener las Horas Acumuladas y la fecha del Mtto de la Tabla de control de alertas por Equipo
		$sql="SELECT hrs_acum,fecha_mtto FROM acumulado_servicios WHERE equipos_id_equipo='$equipo'";
		//Ejecutar la sentencia
		$rs=mysql_query($sql);
		if ($datos=mysql_fetch_array($rs)){
			//Extraer la Fecha de Mtto y las Horas Acumuladas en variables para un mas facil manejo
			$fechaMtto=$datos["fecha_mtto"];
			$hrs_acum=$datos["hrs_acum"];
			//definir Fecha 1
			$ano1 = substr($fecha,0,4);
			$mes1 = substr($fecha,5,2);
			$dia1 = substr($fecha,8,2);
			//definir Fecha 2
			$ano2 = substr($fechaMtto,0,4);
			$mes2 = substr($fechaMtto,5,2);
			$dia2 = substr($fechaMtto,8,2);
			//calculo timestamp de las dos fechas
			$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1);
			$timestamp2 = mktime(4,12,0,$mes2,$dia2,$ano2);
			//resto a una fecha la otra
			$segundos_diferencia = $timestamp1 - $timestamp2;
			//convierto segundos en días
			$dias = $segundos_diferencia / (60 * 60 * 24);
			//quito los decimales a los días de diferencia
			$dias = floor($dias);
			
			//Si dias es menor igual a -1 quiere decir que se debe actualizar la cantidad de Horas, se habla del mismo dia o dias siguientes
			if($dias>=-1){
				//Acumular las horas
				$horas+=$hrs_acum;
				//Actualizar la horas acumuladas en la BD
				$sql="UPDATE acumulado_servicios SET hrs_acum='$horas' WHERE equipos_id_equipo='$equipo'";
				//Ejecutar la sentencia
				$rs=mysql_query($sql);
			}
		}
		else{
			//No hay registro de horas, ingresar las horas directamente a la BD
			$sql="INSERT INTO acumulado_servicios (equipos_id_equipo,hrs_acum) VALUES ('$equipo','$horas')";
			//Ejecutar la sentencia
			$rs=mysql_query($sql);
		}
	}//Fin de function registrarHorasAcumuladas($equipo,$horas,$fecha)
	
//*******************************************************************************************************************************//
//******************************** COMIENZAN LAS FUNCIONES PARA  EL REGISTRO DE ODOMETRO ****************************************//
//*******************************************************************************************************************************//


	// Funcion que se encarga de mostrar solo el encabezado de la tabla esto con el fin de que si hay muchos registros no se recorra el encabezado
	function mostrarTituloOdometro(){
		//Desplegar los encabezados tabla
		echo "				
		<table cellpadding='5' class='tabla_frm'>      			
			<tr>
				<td width='70' class='nombres_columnas'>EDITAR</td>
				<td width='70' class='nombres_columnas'>ID EQUIPO</td>
				<td width='100' class='nombres_columnas'>ULTIMO OD&Oacute;METRO</td>
				<td width='100' class='nombres_columnas'>FECHA</td>				
				<td width='100' class='nombres_columnas'>OD&Oacute;METRO INICIAL</td>
				<td width='100' class='nombres_columnas'>OD&Oacute;METRO FINAL</td>
				<td width='100' class='nombres_columnas'>OD&Oacute;METRO TOTAL</td>
			</tr>
		</table>";
	}//	fin function mostrarTituloOdometro(){
	
	
	// Funcion que se encarga de mostrar los equipos de acuerdo a la area y familia seleccionados
	function mostrarEquiposOdometro(){
	
		//Conectar a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
		
		//Traer el valor de los combos para poder realizar la consulta de acuerdo a los criterios seleccionados
		$area= $_POST['cmb_area'];
		$familia= $_POST['cmb_familia'];				
		
		//Esta variable ayudara a notificar si hubo registros o no en la pantalla de Registrar Horómetro
		$resultado = false;
		
		//Crear sentencia SQL
		$sql_stm = "SELECT id_equipo FROM equipos WHERE area='$area' AND familia='$familia' AND metrica='ODOMETRO' AND estado='ACTIVO' ORDER BY id_equipo";
	
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_alerta = "	<label class='msje_correcto' align='center'>La Familia <em><u>$_POST[cmb_familia]</u></em> no cuenta con Equipos que Manejen <em><u>OD&Oacute;METRO</u></em>
		</label>";									

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			$nom_clase = "renglon_gris";
			$cont = 1;			
			do{			
				//Obtener el ultimo registro para el Horómetro de cada Vehiculo
				$rs_reg_final = mysql_query("SELECT fecha, reg_final FROM horometro_odometro WHERE equipos_id_equipo = '$datos[id_equipo]' AND fecha = (SELECT MAX(fecha) 
										     FROM horometro_odometro WHERE equipos_id_equipo = '$datos[id_equipo]') ORDER BY reg_final DESC");											 
				$reg_final_view = "No Hay Registro";
				$reg_final = "";
				$fecha = "";
				if($datos_reg_final = mysql_fetch_array($rs_reg_final)){
					$reg_final_view = $datos_reg_final['reg_final']." Kms.";
					$reg_final = $datos_reg_final['reg_final'];
					$fecha = modFecha($datos_reg_final['fecha'],1);
				}
					
				//Mostrar todos los registros que han sido completados
				echo "
				<table cellpadding='5'>      			
				<tr>
					<td  width='70' class='$nom_clase'>"; ?>
						<input type="checkbox" name="ckb_equipo<?php echo $cont;?>" id="ckb_equipo<?php echo $cont;?>" 
						value="<?php echo $datos["id_equipo"];?>" onClick="activarCamposOdo(this, <?php echo $cont; ?>)"
						onkeypress="return permite(event,'num_car');" /><?php 
					echo "</td>	
					<td width='70' class='$nom_clase'>$datos[id_equipo]</td>					
					<td width='100' class='$nom_clase'>
						$reg_final_view
						<input type='hidden' name='hdn_regFinal$cont' id='hdn_regFinal$cont' value='$reg_final' />
					</td>
					<td width='100' class='$nom_clase'>$fecha</td>
					<td width='100' class='$nom_clase'>"; ?>
						<input type="hidden" name="hdn_nombre<?php echo $cont; ?>"  value="<?php echo $datos["id_equipo"];?>" id="hdn_nombre<?php echo $cont; ?>"/>
						<input type="text" name="txt_odoIni<?php echo $cont;?>" id="txt_odoIni<?php echo $cont;?>" size="10" disabled="disabled"
						onkeypress="return permite(event,'num', 2);"
						onchange="formatCurrency(value,'txt_odoIni<?php echo $cont;?>'); calcularOdometro(this,<?php echo $cont;?>);" /><?php 
					echo "</td>	
					<td width='100' class='$nom_clase'>"; ?>
						<input type="text" name="txt_odoFin<?php echo $cont;?>" id="txt_odoFin<?php echo $cont;?>" size="10" disabled="disabled"
						onkeypress="return permite(event,'num', 2);"
						onchange="formatCurrency(value,'txt_odoFin<?php echo $cont;?>'); calcularOdometro(this,<?php echo $cont;?>);" /><?php 
					echo "</td>	
					<td width='100' class='$nom_clase'>"; ?>
						<input type="text" name="txt_total<?php echo $cont;?>" id="txt_total<?php echo $cont;?>" size="10" disabled="disabled"
						onkeypress="return permite(event,'num', 2);"/><?php 
					echo "</td>
				</tr>";
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "<input type='hidden' name='hdn_cant' id='hdn_cant' value='$cont'/>";
			echo "	</table>";
			
			$resultado = true;			
		}// fin  if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_alerta;
			$resultado = false;					
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);	
		
		return $resultado;
	}//	 fin function mostrarEquipos(){


	//Verificamos si viene definido sbt_registrarOdo 
	if(isset($_POST["sbt_registrarOdo"])){
		guardarRegOdometro();
	}
	
	//Funcion que guarda los cambios en los registros seleccionados
	function guardarRegOdometro(){
		//Se incluye el archivo de conexión para manejo de bd
		include_once("../../includes/conexion.inc");
		include_once("../../includes/op_operacionesBD.php");
		include_once ("../../includes/func_fechas.php");
		//Conectamos con la BD
		$conn = conecta("bd_mantenimiento");
		//Creamos la variable cantidad de la function mostrarEquiposOdometro() para saber el numero de registros
		$cantidad=$_POST["hdn_cant"]-1;
		//Iniciamos la variable de control interna
		$ctrl=0;
		//Variable bandera para la insercion de datos
		$flag=0;
		//Variable para almacenar el error en caso de generarse
		$error="";
		session_start();		
		do{
			//Incrementamos el contador interno; para saber el numero de registro
			$ctrl++;
			//Verificamos que este definido el Odometro inicial y agregamos variable ctrl para saber que registro sera insertado en la BD
			if(isset($_POST["txt_odoIni$ctrl"])){
				//Creamos variables para guardar lo que viene el el post
				$cmb_turno=$_POST["cmb_turno"];
				$txa_observaciones=$_POST["txa_comentarios"];
				//creamos la variable para almacenar la fecha
				$fecha = modFecha ($_POST["txt_fechaOrometro"],3);
				$ckb_equipo = $_POST["ckb_equipo$ctrl"];
				$txt_odoIni = str_replace(",","",$_POST["txt_odoIni$ctrl"]);
				$txt_odoFin = str_replace(",","",$_POST["txt_odoFin$ctrl"]);
				$txt_total = $txt_odoFin-$txt_odoIni;
				//Creamos la sentencia SQL
				$stm_sql="INSERT INTO horometro_odometro (equipos_id_equipo,fecha, reg_inicial,reg_final,turno,observaciones, km_servicio)
				VALUES('$ckb_equipo','$fecha','$txt_odoIni','$txt_odoFin','$cmb_turno','$txa_observaciones','$txt_total' )";
		
				//Ejecutar la sentencia previamente creada
				$rs=mysql_query($stm_sql);
				//Guardar el registro de movimientos
				registrarOperacion("bd_mantenimiento",$ckb_equipo,"RegistrarOrometro",$_SESSION['usr_reg']);
				//Conectamos con la BD
				$conn = conecta("bd_mantenimiento");
				//verificamos que la sentencia sea ejecutada con exito
				if (!$rs){
					$flag=1;
					//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
					$error="**** Error : ".mysql_error();
					break;
				}
			}
		//Mientras que control sea menor a la cantidad se registraran los datos en la BD	
		}while($ctrl<=$cantidad);
			
		//verificamos que la sentencia sea ejecutada con exito
		if ($flag==0){
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}	
	
		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);	
	
	}// Fin de la funcion 

?>