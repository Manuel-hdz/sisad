<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Compras
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/validacionCompras.js"></script>	
	<script type="text/javascript" src="includes/ajax/validarEstado.js"></script>
	
    <style type="text/css">
		<!--
		#form-datos-alertas { position:absolute; left:30px; top:190px; width:940px; height:210px; z-index:12; }
		#titulo-alertas { position:absolute; left:30px; top:146px; width:436px; height:19px; z-index:11; }
		-->
    </style>
</head>
<body>
	
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-alertas">Requisiciones que No Han Sido Atendidas</div>

		
	<div id="form-datos-alertas">
	<p align="center" class="titulo_etiqueta">Seleccionar la Requisici&oacute;n de la Cual Ser&aacute; Generado un Pedido</p>
	<form name="frm_datosRequisicionPedido" action="" method="post">
	<table border="0" align="center" cellpadding="5" class="tabla_frm">
		 <tr>
			 <td align="center" class="nombres_columnas">SELECCIONAR</td>
			 <td align="center" class="nombres_columnas">NO. REQUISICI&Oacute;N</td>
			 <td align="center" class="nombres_columnas">&Aacute;REA</td>
			 <td align="center" class="nombres_columnas">FECHA DE LA REQUISICI&Oacute;N</td>
			 <td align="center" class="nombres_columnas">SOLICITANTE</td>
			 <td align="center" class="nombres_columnas">PRIORIDAD</td>
		</tr><?php
		
		//Arreglo que contendra los Departamentos como clave y el nombre de la BD correspondiente a cada uno como valor, Falta agregar los Deptos que faltan por desarrollar
		$deptos = array("ALM"=>"bd_almacen", "MAN"=>"bd_mantenimiento", "MAC"=>"bd_mantenimiento", "MAM"=>"bd_mantenimiento", "REC"=>"bd_recursos", "TOP"=>"bd_topografia",
						"LAB"=>"bd_laboratorio", "PRO"=>"bd_produccion", "GER"=>"bd_gerencia", "DES"=>"bd_desarrollo", "SEG"=>"bd_seguridad", "ASE"=>"bd_aseguramiento",
						"PAI"=>"bd_paileria","MAE"=>"bd_mantenimientoE","USO"=>"bd_clinica");			
		
		//Conectarse a la BD de Compras
		$conn = conecta("bd_compras");
		//Crear y ejecutar la consulta para obtener las requisicones registradas en las alertas
		$rs_reqs = mysql_query("SELECT requisiciones_id_requisicion, depto FROM alertas ORDER BY requisiciones_id_requisicion");
						
		
		if($datos_reqs=mysql_fetch_array($rs_reqs)){
			//Guardar el nombre del primer departamento para detectar cuando cambie y asi conectar a la BD correspondiente
			$deptoActual = $datos_reqs['depto'];						
			
			
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{
				
				//Obtener el Id de Cada Requisición
				$idRequisicion = $datos_reqs['requisiciones_id_requisicion'];
							
				//Verificar si el Depto ha cambiado
				if($deptoActual!=$datos_reqs['depto'])
					$deptoActual = $datos_reqs['depto'];				
				
				//Obtener los datos de la Requisicion que será Mostrada
				$rs_datosReq = mysql_query("SELECT area_solicitante, fecha_req, hora, solicitante_req, estado, prioridad  FROM ".$deptos[$deptoActual].".requisiciones 
				JOIN ".$deptos[$deptoActual].".bitacora_movimientos ON id_requisicion= id_operacion WHERE id_requisicion ='$idRequisicion'");
				
				//Verificar que haya datos para mostrar
				if($datosReq=mysql_fetch_array($rs_datosReq)){?>
					<tr>
						<td align="center" class="<?php echo $nom_clase; ?>">
							<input type="radio" name="rdb_idRequisicion" id="rdb_idRequisicion" value="<?php echo $idRequisicion; ?>" onclick="verificarEstado(this);" />
						</td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $idRequisicion; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosReq['area_solicitante']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datosReq['fecha_req'],1)." - ".modHora($datosReq['hora']); ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosReq['solicitante_req']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosReq['prioridad']; ?></td>
					</tr><?php					
				}
				
				
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
				
			}while($datos_reqs=mysql_fetch_array($rs_reqs));
		}//Cierre if($datos_reqs=mysql_fetch_array($rs_reqs))
		
		//Cerrar la Conexion con la BD Actual
		mysql_close($conn);
		
		/*Las suiguientes cajas de texto ocultas nos ayudara a mandar los datos necesarios para registrar los preciosa de una Requisicion Existente, la cual se accesada
		desde esta pagina y no desde consultar Requisiones */?>	
    	<tr>
    		<td colspan="6">
				<input type="hidden" name="hdn_numero" id="hdn_numero" value="" />
        		<input type="hidden" name="hdn_bd" id="hdn_bd" value="" />
        		<input type="hidden" name="hdn_estado" id="hdn_estado" value="" />			
			</td>
		</tr>
	 </table>
	 </form>
	</div>
			    
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>