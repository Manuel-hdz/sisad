<?php

	/**
	  * Nombre del Módulo: Recursos Humanos                                               
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 14/Junio/2011
	  * Descripción: Este archivo contiene funciones para Ver la caratula del empleado
	  **/ 

	include ("../../includes/conexion.inc");
	include ("../../includes/op_operacionesBD.php");
	include ("../../includes/func_fechas.php");


	if(isset($_GET['id_empleado']))
		mostrarCapacitaciones();

function mostrarCapacitaciones(){	
	//Hoja de estilo para la ventana emergente
	echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";

	//Ubicacion de las imagenes que estan contenidas en los encabezados
	define("HOST", $_SERVER['HTTP_HOST']);
	define("SISAD","Sisad-v0.01-alfa");		
	
	//Esribir datos de la pagina en excel
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=CaratulaEmpleado.xls");
	
	//Recuperar el ID del empleado
	$rfc_empleado=$_GET["id_empleado"];

		//Realizar la conexion a la BD de Recursos Humanos
		$conn = conecta("bd_recursos");
		
		//Creamos la sentencia SQL para mostrar los datos de la caratula del empleado
		$stm_sql="SELECT   CONCAT(empleados.nombre,' ',ape_pat,' ',ape_mat) AS nombre, fecha_ingreso, nacionalidad, edo_civil, no_ss, rfc_empleado, curp, puesto,
			sueldo_diario, CONCAT (calle,' ',num_ext,' ',num_int,' ',colonia) AS direccion, tipo_sangre, id_empleados_empresa FROM empleados  
			WHERE  rfc_empleado= '$rfc_empleado'";
						
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set) 
		$rs = mysql_query($stm_sql);				

		//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
		<head>
			<style>					
				<!--
				body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
				.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
				border-top-width: medium; border-right-width: thin;
				border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: 
				solid; border-left-style: none; 
				border-top-color: #000000; border-bottom-color: #000000; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
				.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; } 
				#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
				.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
				.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
				.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
				.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
				.borde_firma { border-top:3px; border-top-color:#000000; border-top-style:solid;}
				-->
			</style>
		</head>											
		<body><?php
  		//Verificar que la consulta tenga datos
		if($datos=mysql_fetch_array($rs)){
		
			//Obtener los datos del Contacto en caso de Accidente
			$contactoAccidete = "";
			$telCasa = "";
			$telCelular = "";			
			
			//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs1 (result set)
			$rs_contacto = mysql_query("SELECT nom_accidente, tel_accidente, tel_accidente FROM empleados WHERE rfc_empleado='$rfc_empleado'");
			if($datosContacto=mysql_fetch_array($rs_contacto)){
				$contactoAccidete = $datosContacto['nom_accidente'];
				$telCasa = $datosContacto['tel_accidente'];
				$telCelular = $datosContacto['tel_accidente'];
			}?>			

            <div id="tabla">	
            	<!--<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>../../images/logo.png" width="299" height="118" align="absbottom" />-->
                <img src="logo.png" width="299" height="118" align="absbottom" />
                <br><br><br>			
                <table>					                    
                    <tr>
                        <td colspan="4">&nbsp;&nbsp;</td>
                    </tr>
		    <tr>
			<td colspan="6" align="center" style="font-size:13px; vertical-align:middle">F. 6.2.2-05 REGISTRO DE PERSONAL<br/></td>
		    </tr>
		    
                    <tr>
                        <td colspan="6" rowspan="3" align="center" style="font-size:13px; border:solid; vertical-align:middle">CONCRETO LANZADO DE FRESNILLO</td>
                    </tr>	
                    </table>
                    <table>
                    <tr>
                        <td colspan="6" style="font-size:10px; border:solid;"><div align="center">REGISTRO DE PERSONAL</div></td>
                    </tr>
                    <tr>
                        <td colspan="4">&nbsp;&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="6" style="font-size:9px; border:solid;">
                        	<div align="left">
                            	ADVERTIDO QUE DEBE CONDUCIRSE CON VERDAD, EL INTERESADO PROPORCION&Oacute; LOS SIGUIENTES DATOS
                            </div> 
                        </td>
                    </tr>
				</table> 
                <table>               
                    <tr>
                        <td width="197" style="font-size:9px; border-left:solid; border-top:solid; border-bottom:solid;"><div align="left">NOMBRE</div> </td>
                  <td width="48" style="font-size:9px; border-top:solid; border-right:solid; border-bottom:solid;"><div align="left">
							<?php echo $datos['nombre'];?></div>
                        </td>
                      <td width="149" style="font-size:9px; border-left:solid; border-top:solid; border-bottom:solid;"><div align="left">NO. EMPLEADO</div> </td>
                  <td width="22" style="font-size:9px; border-top:solid; border-right:solid; border-bottom:solid;"><div align="left">
							<?php echo $datos['id_empleados_empresa']; ?></div>
                        </td>
                  </tr>
                    <tr>
                        <td style="font-size:9px; border-left:solid; border-top:solid; border-bottom:solid;"><div align="left">NACIONALIDAD</div> </td>
                        <td style="font-size:9px; border-top:solid; border-right:solid; border-bottom:solid;"><div align="left">
							<?php echo $datos['nacionalidad'];?></div>
                        </td>
                        <td width="149" style="font-size:9px; border-left:solid; border-top:solid; border-bottom:solid;"><div align="left">FECHA INGRESO</div> </td>
                  <td width="22" style="font-size:9px; border-top:solid; border-right:solid; border-bottom:solid;"><div align="left">
							<?php echo modFecha($datos['fecha_ingreso'],2); ?></div>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:9px; border-left:solid; border-top:solid; border-bottom:solid;"><div align="left">EDAD</div> </td>
                        <td style="font-size:9px; border-top:solid; border-right:solid; border-bottom:solid;"><div align="left"></div></td>
                    </tr>
                    <tr>
                        <td style="font-size:9px; border-left:solid; border-top:solid; border-bottom:solid;"><div align="left">ESTADO CIVIL</div> </td>
                        <td style="font-size:9px; border-top:solid; border-right:solid; border-bottom:solid;"><div align="left">
							<?php echo $datos['edo_civil'];?></div>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:9px; border-left:solid; border-top:solid; border-bottom:solid;"><div align="left">NSS</div> </td>
                        <td style="font-size:9px; border-top:solid; border-right:solid; border-bottom:solid;"><div align="left">
							<?php echo $datos['no_ss'];?></div>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:9px; border-left:solid; border-top:solid; border-bottom:solid;"><div align="left">RFC</div> </td>
                        <td style="font-size:9px; border-top:solid; border-right:solid; border-bottom:solid;"><div align="left">
							<?php echo $datos['rfc_empleado'];?></div>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:9px; border-left:solid; border-top:solid; border-bottom:solid;"><div align="left">CURP</div> </td>
                        <td style="font-size:9px; border-top:solid; border-right:solid; border-bottom:solid;"><div align="left">
							<?php echo $datos['curp'];?></div>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:9px; border-left:solid; border-top:solid; border-bottom:solid;"><div align="left">LUGAR DE NACIMIENTO</div> </td>
                        <td style="font-size:9px; border-top:solid; border-right:solid; border-bottom:solid;"><div align="left"></div></td>
                    </tr>
                    <tr>
                        <td style="font-size:9px; border-left:solid; border-top:solid; border-bottom:solid;"><div align="left">CATEGOR&Iacute;A</div> </td>
                        <td style="font-size:9px; border-top:solid; border-right:solid; border-bottom:solid;"><div align="left">
							<?php echo $datos['puesto'];?></div>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:9px; border-left:solid; border-top:solid; border-bottom:solid;"><div align="left">SUELDO</div> </td>
                        <td style="font-size:9px; border-top:solid; border-right:solid; border-bottom:solid;">$<div align="left"></div></td>
                    </tr>
                    <tr>
                        <td colspan="4">&nbsp;&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="font-size:9px; border-left:solid; border-top:solid; border-bottom:solid;"><div align="left">DIRECCI&Oacute;N</div> </td>
                        <td style="font-size:9px; border-top:solid; border-right:solid; border-bottom:solid;"><div align="left">
	                        <?php echo $datos['direccion'];?></div></td>
                    </tr>
                    <tr>
                        <td style="font-size:9px; border-left:solid; border-top:solid; border-bottom:solid;"><div align="left">TEL.</div> </td>
                        <td style="font-size:9px; border-top:solid; border-bottom:solid;"><div align="left"><?php echo $telCasa;?></div></td>
                        <td style="font-size:9px; border-top:solid; border-bottom:solid;"><div align="left">TEL. CEL.</div> </td>
                        <td style="font-size:9px; border-top:solid; border-right:solid; border-bottom:solid;"><div align="left"><?php echo $telCelular;?></div></td>
                    </tr>
                    <tr>
                        <td style="font-size:9px; border-left:solid; border-top:solid; border-bottom:solid;"><div align="left">TIPO SANGRE</div> </td>
                        <td style="font-size:9px; border-top:solid; border-right:solid; border-bottom:solid;"><div align="left">
							<?php echo $datos['tipo_sangre'];?></div>
                        </td>
                        <td style="font-size:9px; border-left:solid; border-top:solid; border-bottom:solid;">
                        	<div align="left">ALERGIA ALG&Uacute;N MEDICAMENTO</div> 
                        </td>
                        <td style="font-size:9px; border-left:solid; border-top:solid; border-right:solid; border-bottom:solid;"><div align="left"></div></td>
                        <td width="29" style="font-size:9px; border-left:solid; border-top:solid; border-bottom:solid;">
                       	  <div align="left">CUAL</div> 
                        </td>
                      <td width="10" style="font-size:9px; border-left:solid; border-top:solid; border-right:solid; border-bottom:solid;"><div align="left"></div></td>
                  </tr>
                    <tr>
                        <td style="font-size:9px; border-left:solid; border-top:solid; border-bottom:solid;"><div align="left">EN CASO DE ACCIDENTE AVISAR A:</div></td>
                        <td style="font-size:9px; border-top:solid; border-right:solid; border-bottom:solid;"><div align="left">
							<?php echo $contactoAccidete;?></div>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:9px; border-left:solid; border-top:solid; border-bottom:solid;"><div align="left">CUENTA N&Oacute;MINA BANCOMER</div></td>
                        <td style="font-size:9px; border-top:solid; border-right:solid; border-bottom:solid;"><div align="left"></div></td>
                    </tr>
                    <tr>
                        <td style="font-size:9px; border-left:solid; border-top:solid; border-right:solid; border-bottom:solid;" colspan="3">
                        	<div align="left">DOCUMENTOS QUE PRESENT&Oacute;</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:9px; border:solid;"><div align="left">ACTA NACIMIENTO:</div></td>
                        <td style="font-size:9px; border:solid;"><div align="left"></div></td>
                        <td style="font-size:9px; border:solid;"><div align="left"></div></td>
                    </tr>
                    <tr>
                        <td style="font-size:9px; border:solid;"><div align="left">ACOMPROBANTE DOMICILIO:</div></td>
                        <td style="font-size:9px; border:solid;"><div align="left"></div></td>
                        <td style="font-size:9px; border:solid;"><div align="left"></div></td>
                    </tr>
                    <tr>
                        <td style="font-size:9px; border:solid;"><div align="left">CURP:</div></td>
                        <td style="font-size:9px; border:solid;"><div align="left"></div></td>
                        <td style="font-size:9px; border:solid;"><div align="left"></div></td>
                    </tr>
                    <tr>
                        <td style="font-size:9px; border:solid;"><div align="left">CARTILLA MILITAR:</div></td>
                        <td style="font-size:9px; border:solid;"><div align="left"></div></td>
                        <td style="font-size:9px; border:solid;"><div align="left"></div></td>
                    </tr>
                    <tr>
                        <td style="font-size:9px; border:solid;"><div align="left">CARTA DE NO ANTECEDENTES PENALES:</div></td>
                        <td style="font-size:9px; border:solid;"><div align="left"></div></td>
                        <td style="font-size:9px; border:solid;"><div align="left"></div></td>
                    </tr>
                    <tr>
                        <td style="font-size:9px; border:solid;"><div align="left">LICENCIA DE MANEJO:</div></td>
                        <td style="font-size:9px; border:solid;"><div align="left"></div></td>
                        <td style="font-size:9px; border:solid;"><div align="left"></div></td>
                    </tr>
                    <tr>
                        <td style="font-size:9px; border:solid;"><div align="left">COMPRONBANTE DE ESTUDIOS:</div></td>
                        <td style="font-size:9px; border:solid;"><div align="left"></div></td>
                        <td style="font-size:9px; border:solid;"><div align="left"></div></td>
                    </tr>
                    <tr>
                    	<td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="font-size:9px;"><div align="left">CONTRATO PARA DESARROLLO POR</div></td>
                        <td style="font-size:9px; border-bottom:solid;"><div align="left">300 METROS</div></td>
                        <td style="font-size:9px; border-bottom:solid;"><div align="left">500 METROS</div></td>
                        <td style="font-size:9px; border-bottom:solid;"><div align="left">700 METROS</div></td>
                    </tr>
                    <tr>
                        <td style="font-size:9px;"><div align="left">CONTRATO PARA ZARPEO POR</div></td>
                        <td style="font-size:9px; border-bottom:solid;"><div align="left">30 D&Iacute;AS</div></td>
                        <td style="font-size:9px; border-bottom:solid;"><div align="left">60 D&Iacute;AS</div></td>
                        <td style="font-size:9px; border-bottom:solid;"><div align="left">90 D&Iacute;AS</div></td>
                    </tr>
                    <tr>
                        <td style="font-size:9px;"><div align="left">SOLICITO</div></td>
                    </tr>
                    <tr>
                        <td style="font-size:9px;"><div align="left">ELABOR&Oacute;</div></td>
                        <td style="font-size:9px;"><div align="left"></div></td>
                        <td style="font-size:9px;"><div align="left">AUTORIZ&Oacute;: LIC. JOS&Eacute; DE JES&Uacute;S CARRILLO SANTACRUZ</div></td>
                        <td style="font-size:9px;"><div align="left"> ING. GUILLERMO MARTINEZ ROMAN </div></td>
                    </tr>
					<?php
                $nom_clase = "renglon_gris";
                $cont = 1;
				
                do{	
                    //Mostrar todos los registros que han sido completados ?>
                  <?php
                        
                    //Determinar el color del siguiente renglon a dibujar
                    $cont++;
                    if($cont%2==0)
                        $nom_clase = "renglon_blanco";
                    else
                        $nom_clase = "renglon_gris";				
         		}while($datos=mysql_fetch_array($rs));
                //Fin de la tabla donde se muestran los resultados de la consulta?>            
                </table><?php
		}//fin ($datos=mysql_fetch_array($rs))	
	}
?>
