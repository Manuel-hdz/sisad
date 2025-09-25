<?php
    function verificarExiste(){
        $fecha = date("Y-m-d");
        $conn = conecta("bd_compras");
        $stm_sql = "SELECT *
        FROM `tipo_cambio`
        WHERE `fecha` = '$fecha'";

        $rs = mysql_query($stm_sql);
        if (mysql_num_rows($rs) > 0) {
            return true;
        } else {
            return false;
        }
        mysql_close($conn);
    }

    function guardarTC(){
        $fecha = date("Y-m-d");
        $tc_dolar = $_POST['txt_tcdolar'];
        $tc_euro = $_POST['txt_tceuro'];

        $conn = conecta("bd_compras");
        if (!verificarExiste()) {
            $stm_sql_dolar = "INSERT INTO tipo_cambio (fecha, moneda, cambio) VALUES ('$fecha', 'DOLAR', $tc_dolar);";
            $stm_sql_euro = "INSERT INTO tipo_cambio (fecha, moneda, cambio) VALUES ('$fecha', 'EURO', $tc_euro);";
            
            mysql_query($stm_sql_dolar);
            mysql_query($stm_sql_euro);
        }
        mysql_close($conn);
    }

    function obtenerTC($moneda){
        $fecha = date("Y-m-d");
        $conn = conecta("bd_compras");
        $stm_sql = "SELECT cambio
        FROM `tipo_cambio`
        WHERE `fecha` = '$fecha'
        AND `moneda` LIKE '$moneda'";

        $rs = mysql_query($stm_sql);

        if($dato = mysql_fetch_array($rs)){
            return $dato[0];
        } else {
            return 0;
        }

        mysql_close($conn);
    }
?>