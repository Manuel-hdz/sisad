<?php

	/**
	  * Nombre del Módulo: Aseguramiento Calidad                                                
	  * Nombre Programador: Daisy Ariana Martínez Fernández
	  * Fecha: 21/Noviembre/2011
	  * Descripción: Archivo que permite cargar las fotos al Servidor, validando que solo sea un registro
	  **/  
	//Titulo de la ventana emergente
	echo "<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>"; 
	//Inlcuimos archivo que contiene las operaciones necesarias para el registro
	include ("op_registrarListaMaestraRegCal.php");
	//Archivo de validacion
	echo "<script type='text/javascript' src='../../includes/validacionAseguramiento.js'></script>";
	//Archivo de Estilo
	echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";
	//Iniciamos la sesión para las operaciones necesarias en la pagina
	session_start();?>
	<?php //Archivos que permtien desabilitar teclas especificas, así como desabilitar el clic derecho?>
	<script language="javascript" type="text/javascript" src="../../includes/disableKeys.js"></script>
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
	if(!isset($_GET['idAlerta'])){
		//Verificamos si esta definido el boton finalizar
		if(isset($_POST["sbt_finalizar"])){
			//window.opener.document.getElementById("txt_ubicacion").value=<?php echo $_POST["txt_ubicacion"]; carga el valor a la caja de texto deseada; 
			//tomando en cuenta  la ventana de la cual fue lanzada(abierta)
			//window.opener.focus(); Enfoca la ventana de apertura
			//window.close(); Cierra el pop-up
			$deptos="";
			$contad = 1;
			//Recorremos el foreach  para almacenar el valor contenido en el Post en una variable y enviarla a una caja de texto
			foreach($_POST as $key => $valor){
				if($contad==1){
					$deptos = $valor;
				}
				if($contad>1&&$valor!="Finalizar"&&$_POST[$key]!=$_POST["hdn_cant"]){
					$deptos .= ",".$valor;
				}
				$contad++;
			}
			//Eliminamos Finalizar para que unicamente sean almacenados los nombres de los departamentos
			$deptos=str_replace(",Finalizar","",$deptos);
			$deptos=str_replace("ON,","",strtoupper($deptos));
				?>
				<script type="text/javascript" language="javascript">
					window.opener.document.getElementById("txt_ubicacion").value="<?php echo $deptos;?>"; 				
					window.opener.focus();
					window.close();
				</script>
			<?php	
			}?>
		
	
		<legend class="titulo_etiqueta">Seleccionar Departamentos</legend>	
		<form  onsubmit="return valFormDeptos(this);" name="frm_verDepto" id="frm_verDepto" method="post" action="verDepartamentos.php">
			<?php mostrarDepartamentos();?>
			<table width="100%" border="0" cellpadding="5" cellspacing="5">
				<tr align="center">
					<td colspan="4">
						<input name="sbt_finalizar" type="submit" class="botones" value="Finalizar" 
						title="Vuelve al Registro de Lista Maestra"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;		
						<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar los campos"/>
					</td>
				</tr>
			</table>
		</form>
	<?php 
	}
	//Comprobamos que exista el GET para mostrar los departamentos al modificar el archivo
	if(isset($_GET['idAlerta'])){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD de Aseguramiento
		$conn = conecta("bd_aseguramiento");
		
		//Guardamos el id de la Alerta en la siguiente Variable
		$idAlerta = $_GET['idAlerta'];
		
		//Creamos la sentencia SQL
		$stm_sql ="SELECT * FROM detalle_alertas_generales WHERE alertas_generales_id_alerta='$idAlerta'";
					
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='100%'>
					<caption class='titulo_etiqueta'>DEPARTAMENTOS REGISTRADOS</caption>";
			echo "	<tr>
						<td class='nombres_columnas' align='center'>NO.</td>
						<td class='nombres_columnas' align='center'>NOMBRE</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				$nomDepto = obtenerDato("bd_usuarios", "usuarios", "depto", "no_depto", $datos['catalogo_departamentos_id_departamento']);
				//Mostrar todos los registros que han sido completados
				echo "
				<tr>
					<td align='center' class='$nom_clase'>$cont</td>
     		       	<td class='$nom_clase'>".strtoupper($nomDepto)."</td>
				</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;	
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</tbody>";
			echo "</table>";	
			return 1;
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'>  No existen Documentos Registrados </label>";
			return 0;
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}
	?>