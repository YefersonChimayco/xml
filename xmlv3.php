<?php
$conexion = new mysqli("localhost", "root", "root", "insertar");
if ($conexion->connect_errno) {
    echo "Fallo al conectar a MySQL: (" . $conexion->connect_errno . ") " . $conexion->connect_error;
}
$xml = simplexml_load_file('ies2.xml') or die('ERROR NO SE CARGÓ EL XML. escribe correctamente el nombre del archivo');
/*echo $xml->pe_1->nombre. "<br>"; 
echo $xml->pe_2->nombre;*/

foreach ($xml as $i_pe => $pe) {
    echo '---' . $pe->nombre . '<br>';
    echo '---' . $pe->codigo . '<br>';
    echo '---' . $pe->tipo . '<br>';
   $stmtPE = $conexion->prepare(
    "INSERT INTO sigi_programa_estudios (codigo, tipo, nombre) VALUES (?, ?, ?)"
);
    foreach ($pe->planes_estudio[0] as $i_ple => $plan) {
        echo $plan->nombre;
            echo '-----' . $plan->nombre.'<br>';
            echo '-----' . $plan->resolucion.'<br>';
            echo '-----' . $plan->fecha_registro.'<br>';
         

        foreach ($plan->modulos_formativos[0] as $id_mod => $modulo) {
            echo '-----' . $modulo->descripcion.'<br>';
            echo '-----' . $modulo->nro_modulo.'<br>';

            foreach ($modulo->periodos[0] as $id_per => $per) {
            echo '-----' . $per->descripcion.'<br>';
            foreach ($per->unidades_didacticas[0] as $id_ud => $ud) {
            echo '-----' . $ud->nombre.'<br>';
            echo '-----' . $ud->creditos_teorico.'<br>';
            echo '-----' .      $ud->creditos_practico.'<br>';
            echo '-----' . $ud->tipo.'<br>';
            echo '-----' . $ud->horas_semanal.'<br>';
            echo '-----' . $ud->horas_semestral.'<br>';
            }
            }
        }
    }
}
