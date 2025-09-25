<?php

	/**
	  * Nombre del Módulo: Aseguramiento Calidad                                                
	  * Nombre Programador: Daisy Ariana Martínez Fernández
	  * Fecha: 21/Noviembre/2011
	  * Descripción: Archivo que permite cargar las fotos al Servidor, validando que solo sea un registro
	  **/  
	//Titulo de la ventana emergente
	echo "<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>"; 
	//Archivo de validacion
	//Inlcuimos archivo que contiene las operaciones necesarias para el registro
	include ("op_registrarRecordatorio.php");
	echo "<script type='text/javascript' src='../../includes/validacionAseguramiento.js'></script>";
	//Archivo de Estilo
	echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";
	//Iniciamos la sesión para las operaciones necesarias en la pagina
	session_start();?>
	<script language="javascript" type="text/javascript" src="../../includes/disableKeys.js"></script>
	<script language="javascript" type="text/javascript">
		<!--
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, ©Concreto Lanzado de Fresnillo S.A. de C.V.');
			}
		}
		document.onmousedown=click;
		//-->
	</script>
	<style type="text/css">
		<!--
		#titulo-agregar-archivo { position:absolute; left:30px; top:22px; width:200px; height:20px; z-index:11;}
		#tabla-agregarArchivos{position:absolute;left:30px;top:30px;width:265px;height:170px;z-index:13;}
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
			//window.opener.document.getElementById("txt_volProducido").value=<?php echo $_POST["txt_volMaximo"]; carga el valor a la caja de texto deseada; 
			//tomando en cuenta  la ventana de la cual fue lanzada(abierta)
			//window.opener.focus(); Enfoca la ventana de apertura
			//window.close(); Cierra el pop-up
			$deptos="";
			$contad = 1;
			//Recorremos el foreach  para almacenar el valor contenido en el Post en una variable y enviarla a una caja de texto
			foreach($_POST as $key => $valor){
				if($contad==1){
					$archivos = $valor;
				}
				if($contad>1&&$valor!="Finalizar"&&$_POST[$key]!=$_POST["hdn_cant"]){
					$archivos .= ",".$valor;
				}
				$contad++;
			}
			//Eliminamos Finalizar para que unicamente sean almacenados los nombres de los departamentos
			$archivos=str_replace(",Finalizar","",$archivos);
			$archivos=str_replace("ON,","",strtoupper($archivos));
				?>
				<script type="text/javascript" language="javascript">
					window.opener.document.getElementById("txt_archivos").value="<?php echo $archivos;?>"; 				
					window.opener.focus();
					window.close();
				</script>
			<?php	
			}?>
		
	
		<form  onsubmit="return valArchivos(this);" name="frm_verArchivo" id="frm_verArchivo" method="post" action="verArchivos.php">
			<?php $band = mostrarArchivos();?>
			<table width="100%" border="0" cellpadding="5" cellspacing="5">
				<tr align="center">
					<td colspan="4">
					<?php if($band==1){?>
						<input name="sbt_finalizar" type="submit" class="botones" value="Finalizar" 
						title="Vuelve al Registro de Lista Maestra"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;		
						<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar los campos"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input name="btn_cerrar" type="button" class="botones"  value="Cerrar" title="Cerrar El Formulario" onclick="window.close();"/>
					<?php }
					else{
						echo "<p class='msje_correcto'>No existen Archivos Registrados</p>";
					}?>
					</td>
				</tr>
			</table>
		</form><?php 
	}
	//Comprobamos que exista el GET
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
		$stm_sql ="SELECT DISTINCT repositorio_documentos_id_documento FROM archivos_vinculados WHERE alertas_generales_id_alerta='$idAlerta'";
					
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			$nomArchivo = obtenerDato("bd_aseguramiento", "repositorio_documentos", "nombre", "id_documento", $datos['repositorio_documentos_id_documento']);
			echo "				
				<table cellpadding='5' width='100%'>
					<caption class='titulo_etiqueta'>ARCHIVOS REGISTRADOS</caption>";
			echo "	<tr>
						<td class='nombres_columnas' align='center'>NO.</td>
						<td class='nombres_columnas' align='center'>ID DOCUMENTO.</td>
						<td class='nombres_columnas' align='center'>ARCHIVO</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				echo "
				<tr>
					<td align='center' class='$nom_clase'>$cont</td>
     		       	<td class='$nom_clase'>$datos[repositorio_documentos_id_documento]</td>
					<td class='$nom_clase'>$nomArchivo</td>
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
			?>
			<script language="javascript" type="text/javascript">
				setTimeout("alert('No Existen Archivos Registrados; Agregue Archivos en el Menú Repositorio');window.close();",500);
			</script>
			<?php 		
			return 0;
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}