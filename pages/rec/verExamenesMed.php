 <?php

	/**
	  * Nombre del M�dulo: Unidad de Salud Ocupacional                                               
	  * Nombre Programador: Nadia Madah� L�pez Hern�ndez
	  * Fecha: 26/Septiembre/2012
	  * Descripci�n: Archivo que permite cargar los examenes medicos que se practicaran por trabajador
	  **/  
	
	
	//Titulo de la ventana emergente
	echo "<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>"; 
	//Inlcuimos archivo que contiene las operaciones necesarias para el registro
	include ("op_elaborarSolicitud.php");

	//Archivo de validacion
	echo "<script type='text/javascript' src='../../includes/validacionClinica.js'></script>";
	echo "<script type='text/javascript' src='../../includes/formatoNumeros.js'></script>";

	//Archivo de Estilo
	echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";
	//Archivo para desabilitar boton regresar del teclado?>
	<script language="javascript" type="text/javascript" src="../../includes/disableKeys.js"></script><?php 
	//Iniciamos la sesi�n para las operaciones necesarias en la pagina
	session_start();?>
	<script language="javascript" type="text/javascript">
		<!--
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, �CONCRETO LANZADO DE FRESNILLO MARCA');
			}
		}
		document.onmousedown=click;
		//-->
	</script>
	<style type="text/css">
		<!--
		#titulo-agregar-documentos { position:absolute; left:30px; top:22px; width:200px; height:20px; z-index:11;}
		#tabla-agregarDepartamentos{position:absolute;left:30px;top:30px;width:265px;height:170px;z-index:13;}
		.Estilo1 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 12px;
		}
		-->
    </style>
	<?php 
		//Verificamos si esta definido el boton finalizar
		if(isset($_POST["sbt_finalizar"])){
			$exa="";
			$contad = 1;
			$cantCKB = $_POST['hdn_cant'];
			$idExa = "";
			
			//Variable utilizada para guardar el costo total de los examenes seleccionados, esta para pasarla a la ventana principal(padre)
			$costo = $_POST['txt_total'];
			//Recorremos el foreach  para almacenar el valor contenido en el Post en una variable y enviarla a una caja de texto
			do{
				if(isset($_POST['ckb_examen'.$contad])){
					$arrExa = explode("�",$_POST['ckb_examen'.$contad]);
					//$exa.=$_POST['ckb_examen'.$contad].", ";
					$exa .= $arrExa[1].", ";
					$idExa .= $arrExa[0].", ";
					
				}
				
				$contad++;
			}while($contad<$cantCKB);
			//Retirar la ultima coma de la variable que guarda los nombres de los examamenes
			$tam = strlen($exa);
			$exa = substr($exa,0,$tam-2);
			
			//Retirar la ultima coma de la variable que guarda los  claves de los examamenes
			$tam = strlen($idExa);
			$idExa = substr($idExa,0,$tam-2);
			
			//Eliminamos Finalizar para que unicamente sean almacenados los nombres de los examenes
			//$exa=str_replace(",txt_exaPracticados","",$exa);
			//$costo=str_replace(",txt_cosTotal","",$costo);
			?>
				<script type="text/javascript" language="javascript">
					window.opener.document.getElementById("txt_exaPracticados").value="<?php echo $exa;?>"; 				
					window.opener.document.getElementById("txt_cosTotal").value="<?php echo  $costo;?>"; 				
					window.opener.document.getElementById("hdn_idExamenes").value="<?php echo  $idExa;?>"; 				
					window.opener.focus();
					window.close();
				</script>
			<?php	
			}?>
		
		<legend class="titulo_etiqueta">Seleccionar Ex&aacute;menes M&eacute;dicos a Pr&aacute;cticar</legend>	
		<form  onsubmit="return valFormVerExamenesMedicos(this);" name="frm_verExamenes" id="frm_verExamenes" method="post" action="verExamenesMed.php">
			<?php mostrarExamenesMedicos();?>
			<table width="100%" border="0" cellpadding="5" cellspacing="5">
				<tr align="center">
					<td colspan="4">
						<input name="sbt_finalizar" type="submit" class="botones" value="Finalizar" 
						title="Finalizar y Continuar con el Registro"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;		
						<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar los campos"/>
					</td>
				</tr>
			</table>
		</form>
	<?php 
	
	
		//Funci�n que permite mostrar los Departamento para agregarlos al registro
	function mostrarExamenesMedicos(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD de la Clinica
		$conn = conecta("bd_clinica");
		
		//Creamos la sentencia SQL
		$stm_sql ="SELECT * FROM catalogo_examen";
					
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='100%'>";
			echo "	<tr>
						<td colspan='2' class='nombres_columnas' align='center'>SELECCIONAR TODO</td>
						<td colspan='2' class='nombres_columnas' align='center'>
							<input type='checkbox' id='ckb_exaMedTodos' name='ckb_exaMedTodos' onclick='seleccionarTodo(this); sumarExaTodos(this);'/>
						</td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center'>SELECCIONAR</td>
						<td class='nombres_columnas' align='center'>N&Uacute;MERO </td>
						<td class='nombres_columnas' align='center'>NOMBRE EXAMEN </td>
						<td class='nombres_columnas' align='center'>COSTO EXAMEN </td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			//Variable utilizada para guardar el total acumulado de los examenes cuando la opcion de SELECCIONAR TODOS haya sido seleccionada por el usuario
			$acumulado=0;
			do{	
			echo "	<tr>
						<td class='nombres_filas' align='center'>
							<input type='checkbox' id='ckb_examen$cont' name='ckb_examen$cont' value='$datos[id_examen]�$datos[nom_examen]'
							 onclick='quitar(this); sumarTotalExaMedicos(this,txt_totalExa$cont);'/>
						</td>				
						<td class='$nom_clase' align='center'>$cont.-</td>					
						<td class='$nom_clase' align='left'>$datos[nom_examen]</td>"; ?>						
						<td align="center" class='<?php echo $nom_clase ?>'>
							<input type="text" name="txt_totalExa<?php echo $cont; ?>" id="txt_totalExa<?php echo $cont;?>" 
							maxlength="10" size="10" class="caja_de_texto" readonly="readonly" 
							value="<?php echo number_format($datos['costo_exa'],2,".",",");?>" style="background:#666666;color:#FFFFFF"  />
						</td>
				<?php echo "</tr>";	
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				//Sumar el acumulado y colocarlo en el campo de costo total que se encuentra dentro de la ventana emergente
				$acumulado=$acumulado+$datos["costo_exa"];
			}while($datos=mysql_fetch_array($rs)); 	
			echo "<tr>
					<td colspan = '3' align = 'right'>
						COSTO TOTAL
					</td>
					<td>
						<input type ='text' class ='caja_de_num' readonly = 'readonly' size = '10' name = 'txt_total' id='txt_total' value ='0.00' />
					</td>
			</tr>";
			echo "</table>";	
			?>
				<input type="hidden" name="hdn_cant" id="hdn_cant" value="<?php echo $cont;?>"/>
				<input type="hidden" name="hdn_acumulado" id="hdn_acumulado" value="<?php echo $acumulado;?>"/>
			<?php 
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'>  No existen Examenes Medicos Registrados </label>";
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion 						  <td class='$nom_clase'>".strtoupper($nomExa)."</td>"
?>