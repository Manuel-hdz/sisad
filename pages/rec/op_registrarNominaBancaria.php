<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Daisy Adriana Martínez Fernández
	  * Fecha: 30/Abril/2011
	  * Descripción: Este archivo permite mostrar los empleados registrados en la nómina bancaria
	**/

//Verificamos si viene definido sbt_guardar 
	if(isset($_POST["sbt_guardar"])){
		guardarNomina();
	}	
	//funcion que permita consultar los empleados registrados 
	function mostrarNominaBancaria(){
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_recursos");
		
		//Variable para verificar si hubo rRS en la consulta y poder mostrar los botones
		$flag=0;
		
		//Variable para guardar el area que esta definida en el post
		$area=$_POST["cmb_area"];
		
		//Creamos la sentencia sql
		$stm_sql="SELECT DISTINCT rfc_empleado,CONCAT(nombre,' ', ape_pat,' ', ape_mat) AS nombre,  puesto 
						FROM empleados WHERE area='$area' ORDER BY puesto";
		
		//Ejecutamos la sentencia SQL
		$rs=mysql_query($stm_sql);
		
		//Declaramos el titulo para mostrar en el encabezado
		$titulo="Empleados Registrados del &Aacute;rea <u><em>".$area."</u></em>";
		
		if($datos=mysql_fetch_array($rs)){
		//Guardamos las fechas para exportar el archivo de texto con los datos de la nómina
		$flag=1;
			echo "<table class='tabla_frm' width='100%'>";
			echo "<caption class='titulo_etiqueta'>$titulo</caption>
					<tr>";?>
						<td align='left' colspan='6' class='nombres_columnas'>
						<input name='ckbTodo' align='center' type='checkbox' id='ckbTodo' onclick="checarTodos(this,'frm_resultadosNomina');" value='Todos'/>SELECCIONAR TODOS	</td>					
					</tr>
		<?php echo" <tr>
						<td class='nombres_columnas' align='center'>SELECCIONAR </td>
						<td class='nombres_columnas' align='center'>RFC </td>
						<td class='nombres_columnas' align='center'>NOMBRE</td>
						<td class='nombres_columnas' align='center'>FECHA</td>
						<td class='nombres_columnas' align='center'>SEGURO SOCIAL</td>
						<td class='nombres_columnas' align='center'>SALARIO NETO</td>
						
					</tr>";					
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{								
			echo "	<tr>";?>
						<td class='nombres_filas' align='center'>
							<input type="checkbox" id"ckb_emp<?php echo $cont;?>" name="ckb_emp<?php echo $cont;?>" 
							onClick="activarCamposNomina(this, <?php echo $cont;?>); desSeleccionar(this);" 	value="<?php echo $datos['rfc_empleado']?>"/>
						</td>
			<?php echo "
						<td class='$nom_clase' align='center'>$datos[rfc_empleado]</td>
						<td class='$nom_clase' align='center'>$datos[nombre]</td>";?>
						<td  class="<?php echo $nom_clase;?>" align="center">
							<input type="hidden" name="hdn_nombre<?php echo $cont; ?>"  value="<?php echo $datos["rfc_empleado"];?>" 
							id="hdn_nombre<?php echo $cont; ?>"/>
							<input type="text" name="txt_fecha<?php echo $cont;?>" id="txt_fecha<?php echo $cont;?>"  value="<?php echo date("d/m/Y");?>" 
							class="caja_de_texto" disabled="disabled" size="10" onchange="formatFecha(this);"/>
						</td>
						<td  class="<?php echo $nom_clase;?>" align="center">$
							 <input name="txt_ss<?php echo $cont;?>" type="text"onkeypress="return permite(event,'num',0);" 
							 id="txt_ss<?php echo $cont;?>" onchange="formatCurrency(value,'txt_ss<?php echo $cont;?>');" size="10" 
							 maxlength="15" width="90" value="<?php echo number_format('txt_ss',2,".",",");?>" disabled="disabled"/>
						</td>
						<td  class="<?php echo $nom_clase;?>" align="center">$
							 <input name="txt_sueldo<?php echo $cont;?>" type="text"onkeypress="return permite(event,'num',0);" 
							 id="txt_sueldo<?php echo $cont;?>" onchange="formatCurrency(value,'txt_sueldo<?php echo $cont;?>');" size="10" 
							 maxlength="15" width="90" value="<?php echo number_format('txt_sueldo',2,".",",");?>" disabled="disabled"/>
						</td>
						
		<?php echo" </tr>";
						
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs)); 
			echo "<input type='hidden' name='hdn_cant' id='hdn_cant' value='$cont'/>";
			echo "</table>";	
			return 1;
		}
		else{	
			echo "<br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>Empleados Registrados</p>";
			return 0;
		} 
		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);
	}//Fin de la funcion de mostrarNomina _Bancaria


	//Funcion que guarda los cambios en los registros seleccionados
	function guardarNomina(){
		//Se incluye el archivo de conexión para manejo de bd
		include_once("../../includes/conexion.inc");		
		include_once ("../../includes/func_fechas.php");
		
		//Conectamos con la BD
		$conn = conecta("bd_recursos");
		//Variable bandera para la insercion de datos
		$flag=0;
		//Variable para almacenar el error en caso de generarse
		$error="";
		//Creamos la variable cantidad de la function mostrarNomina() para saber el numero de registros
		$cantidad=$_POST["hdn_cant"]-1;
		//Iniciamos la variable de control interna
		$ctrl=0;
		do{
			//Incrementamos el contador interno; para saber el numero de registro
			$ctrl++;
			//Verificamos que este definido el ckb y agregamos variable ctrl para saber que registro sera insertado en la BD
			if(isset($_POST["ckb_emp$ctrl"])){
				$area=$_POST["hdn_area"];
				$fecha=modFecha($_POST["txt_fecha$ctrl"],3);
				$ckb_emp = $_POST["ckb_emp$ctrl"];
				$txt_sueldo = str_replace(",","",$_POST["txt_sueldo$ctrl"]);
				$txt_ss = str_replace(",","",$_POST["txt_ss$ctrl"]);
				$stm_sql_verificar="SELECT fecha_nomina FROM nomina_bancaria WHERE empleados_rfc_empleado='$ckb_emp' AND fecha_nomina='$fecha'";
				//Ejecutamos la sentencia para verificar si la fecha de insercion de la nomina es correcta y evitar que se genere otro registro
				$rs2=mysql_query($stm_sql_verificar);	
				$fecha_comprobacion = mysql_fetch_array($rs2);
				if($fecha_comprobacion['fecha_nomina']==$fecha)
				{
					$stm_sql="UPDATE nomina_bancaria SET salario_neto='$txt_sueldo', pago_seguro='$txt_ss' WHERE empleados_rfc_empleado='$ckb_emp' 
					AND fecha_nomina='$fecha'";
				}
				else{
					//Creamos la sentencia SQL
					$stm_sql="INSERT INTO nomina_bancaria(empleados_rfc_empleado,fecha_nomina,salario_neto, pago_seguro)
					VALUES('$ckb_emp','$fecha','$txt_sueldo', '$txt_ss')";
				}
				//Ejecutar la sentencia previamente creada
				$rs=mysql_query($stm_sql);
				//Conectamos con la BD
				$conn = conecta("bd_recursos");
				//verificamos que la sentencia sea ejecutada con exito
				if (!$rs){
					$flag=1;
					//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
					$error=mysql_error();
					break;
				}
			}
		//Mientras que control sea menor a la cantidad se registraran los datos en la BD	
		}while($ctrl<=$cantidad);
			
		//verificamos que la sentencia sea ejecutada con exito
		if ($flag==0){
			//Guardar el registro de movimientos
			registrarOperacion("bd_recursos",$ckb_emp,"RegistroNominaBancaria",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";
		}	
		//Cerramos la conexion con la Base de Datos
		//mysql_close($conn);	
	
	}// Fin de la funcion 

?>
