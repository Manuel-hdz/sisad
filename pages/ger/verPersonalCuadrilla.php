<?php
	include ("../../includes/conexion.inc");
	include("../../includes/op_operacionesBD.php");
	include("op_modificarCuadrilla.php");?>
	
	
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css"/>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionGerencia.js"></script>
	<script type="text/javascript" src="../../includes/disableKeys.js"></script>
	<script type="text/javascript" language="javascript">
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, ©Concreto Lanzado de Fresnillo S.A. de C.V.');
			}
		}
		document.onmousedown=click;						
	</script>
	<style type="text/css">
		<!--
		#res-spider {position:absolute;z-index:15;left:120px;top:85px;}
		-->
    </style>
	<?php
	if(isset($_GET['idCuadrilla'])){
		$idCuadrilla = $_GET["idCuadrilla"];
		
		if(isset($_POST["sbt_borrar"])){
			$rfc = $_POST["rdb_rfcPersona"];
			borrarPersonal($idCuadrilla,$rfc);
		}
		
		if (isset($_POST["sbt_agregar"])){
			agregarPersonal();
		}
		
		mostrarFormulario($idCuadrilla);
		?>
		<form action="verPersonalCuadrilla.php?idCuadrilla=<?php echo $idCuadrilla; ?>" method="post" onSubmit="return valFormBorrarPersonalMod(this);" name="frm_modificarPersonalCuadrilla">
			<?php
			mostrarPersonal($idCuadrilla);
			?>
		</form>
		<?php
	}
	?>