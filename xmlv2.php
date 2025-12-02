<?php
$conexion = new mysqli("localhost", "root", "root", "para_xml");
if ($conexion->connect_errno) {
    echo "fallo la conexion a la base de dattos (" . $conexion->connect_errno . ")" . $conexion->connect_error;
}
$xml = new DOMDocument('1.0', 'UTF-8');
$xml->formatOutput = true;

$et1 = $xml->createElement('programas_estudio');
$xml->appendChild($et1);


$consulta = "SELECT * FROM sigi_programa_estudios";
$resultado = $conexion->query($consulta);
while ($pe = mysqli_fetch_assoc($resultado)) {
    echo $pe['nombre'] . "<br>";
    $num_pe = $xml->createElement('pe_' . $pe['id']);

    $codigo_pe = $xml->createElement('codigo', $pe['codigo']);
    $num_pe->appendChild($codigo_pe);

    $tipo_pe = $xml->createElement('tipo', $pe['tipo']);
    $num_pe->appendChild($tipo_pe);

    $nombre_pe = $xml->createElement('nombre', $pe['nombre']);
    $num_pe->appendChild($nombre_pe);
    //---------------------------
    $et_plan = $xml->createElement('planes_estudio');
    $consulta_plan = "SELECT*FROM sigi_planes_estudio WHERE id_programa_estudios=" . $pe['id'];
    $resultado_plan = $conexion->query($consulta_plan);
    while ($plan = mysqli_fetch_assoc($resultado_plan)) {
        echo $plan['nombre'] . "<br>";
        $num_plan = $xml->createElement('plan_' . $plan['id']);

        $nombre_plan = $xml->createElement('nombre', $plan['nombre']);
        $num_plan->appendChild($nombre_plan);

        $resolucion_plan = $xml->createElement('resolucion', $plan['resolucion']);
        $num_plan->appendChild($resolucion_plan);

        $fecha_plan = $xml->createElement('fecha_registro', $plan['fecha_registro']);
        $num_plan->appendChild($fecha_plan);

        //--------------------------
        $et_plan = $xml->createElement('modulos');
        $consulta_mod = "SELECT*FROM sigi_modulo_formativo WHERE id_plan_estudio=" . $plan['id'];
        $resultado_mod = $conexion->query($consulta_mod);
        while ($mod = mysqli_fetch_assoc($resultado_mod)) {
            echo $mod['descripcion'] . "<br>";
            $num_mod = $xml->createElement('mod_' . $mod['id']);

            $nro_mod = $xml->createElement('nro_modulo', $mod['nro_modulo']);
            $num_mod->appendChild($nro_mod);

            $descripcion_mod = $xml->createElement('descripcion', $mod['descripcion']);
            $num_mod->appendChild($descripcion_mod);

            //----------------
            $et_plan = $xml->createElement('semestres');
            $consulta_se = "SELECT*FROM sigi_semestre WHERE id_modulo_formativo=" . $plan['id'];
            $resultado_se = $conexion->query($consulta_se);
            while ($se = mysqli_fetch_assoc($resultado_se)) {
                echo $se['descripcion'] . "<br>";
                $num_se = $xml->createElement('se_' . $se['id']);

                $descripcion_se = $xml->createElement('descripcion', $se['descripcion']);
                $num_se->appendChild($descripcion_se);

                //-----------
                $et_plan = $xml->createElement('unidaddes_didacticas');
                $consulta_ud = "SELECT*FROM sigi_unidad_didactica WHERE id_semestre=" . $plan['id'];
                $resultado_ud = $conexion->query($consulta_ud);
                while ($ud = mysqli_fetch_assoc($resultado_ud)) {
                    echo $ud['nombre'] . "<br>";
                    $num_ud = $xml->createElement('se_' . $ud['id']);

                    $nombre_ud = $xml->createElement('nombre', $ud['nombre']);
                    $num_ud->appendChild($nombre_ud);
                    $creditos_teorico_ud = $xml->createElement('creditos_teorico', $ud['creditos_teorico']);
                    $num_ud->appendChild($creditos_teorico_ud);

                    $creditos_practico_ud = $xml->createElement('creditos_practico', $ud['creditos_practico']);
                    $num_ud->appendChild($creditos_practico_ud);

                    $tipo_ud = $xml->createElement('tipo', $ud['tipo']);
                    $num_ud->appendChild($tipo_ud);

                    $orden_ud = $xml->createElement('orden', $ud['orden']);
                    $num_ud->appendChild($orden_ud);


                    $num_se->appendChild($num_ud);
                }
                $num_mod->appendChild($num_se);
            

            $num_plan->appendChild($num_mod);
        }
        $et_plan->appendChild($num_plan);
    }
    $num_pe->appendChild($et_plan);
    $et1->appendChild($num_pe);
}





$archivo = "ies2.xml";
$xml->save($archivo);
}