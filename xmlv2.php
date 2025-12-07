<?php

use Pdo\Mysql;

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
    //---------------------------PLANES DE ESTUDIO-------------
    $et_plan = $xml->createElement('planes_estudio');
    $consulta_plan = "SELECT * FROM sigi_planes_Estudio WHERE id_programa_estudios=" . $pe['id'];
    $resultado_plan = $conexion->query($consulta_plan);
    while ($plan = mysqli_fetch_assoc($resultado_plan)) {
        echo $plan ['nombre'] . "<br>";
        $num_plan = $xml->createElement('_plan' . $plan['id']);

        $nombre_plan = $xml->createElement('nombre', $plan['nombre']);
        $num_plan->appendChild($nombre_plan);

        $resolucion_plan = $xml->createElement('resolucion', $plan['resolucion']);
        $num_plan->appendChild($resolucion_plan);

        $fecha_registro = $xml->createelement('fecha_registro', $fecha_registro['recha_registro']);
        $num_plan->appendChild($fecha_registro);

        $et_modulos = $xml->createElement('modulos_formativos');
        $consulta_mod = " SELECT * FROM sigi_modulo_formativo WHERE id_plan_estudio=" . $plan['id'];
        $resultado_mod = $conexion->query($consulta_mod);
        while ($modulo = mysqli_fetch_assoc($resultado_mod)) {
            echo "------" . $modulo['descripcion'] ."<br>";
            $num_modulo = $xml->createElement('')
        }
    }
    $num_pe->appendchild($et_plan);
    $et1->appendChild($num_pe);
}

$archivo = "ies2.xml";
$xml->save($archivo);