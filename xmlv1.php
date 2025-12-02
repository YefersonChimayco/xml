<?php
$ies = [];

//--------------------------------------UNIDADES DIDÁCTICAS-----------------------------------------

$udp1 = [
    'Analisis y diseño de sistemas web',
    'Funddamentos de programación',
    'Introducción a la base de datos',
    'Redes e internet',
    'Arquitectura de computadoras',
    'Comunicación oral',
    'Aplicaciones en internet'
];
$udp2 = [
    'Ofimática',
    'Interpretación y producción de textos',
    'Metodología de desarrollo de software',
    'Programación orientada a objetos',
    'Arquitectura de servidores web',
    'Aplicaciones sistematizadas',
    'Taller de base de datos'
];
$udp3 = [
    'Administración de base de datos',
    'Programación de aplicaciones web',
    'Diseño de interfaces web',
    'Pruebas de software',
    'Inglés para la comunicación oral'
];
$udp4 = [
    'Desarrollo de entornos web',
    'Programación de soluciones web',
    'Proyectos de software',
    'Seguridad en aplicaciones web',
    'Comprensión y redacción en inglés',
    'Comportamiento ético'
];
$udp5 = [
    'Programación de aplicaciones móviles',
    'Marketing digital',
    'Diseño de soluciones web',
    'Gestión y administración de sitios web',
    'Diagramación digital',
    'Solución de problemas',
    'Oportunidades de negocios'
];
$udp6 = [
    'Plataforma de servicios web',
    'Ilustración y gráfica digital',
    'Administración de servidores web',
    'Comercio electrónico',
    'Plan de negocios'
];

//--------------------------------------PERIODOS-----------------------------------------

$p1 = ['nombre' => 'I', 'unidades_didacticas' => $udp1];
$p2 = ['nombre' => 'II', 'unidades_didacticas' => $udp2];
$p3 = ['nombre' => 'III', 'unidades_didacticas' => $udp3];
$p4 = ['nombre' => 'IV', 'unidades_didacticas' => $udp4];
$p5 = ['nombre' => 'V', 'unidades_didacticas' => $udp5];
$p6 = ['nombre' => 'VI', 'unidades_didacticas' => $udp6];

//-------------------------MODULOS-----------------------------

$m1 = ['nombre' => 'ANÁLISIS Y DISEÑO DE SISTEMAS WEB', 'periodos' => [$p1, $p2]];
$m2 = ['nombre' => 'DESARROLLO DE APLICACIONES WEB', 'periodos' => [$p3, $p4]];
$m3 = ['nombre' => 'DISEÑO DE SERVICIOS WEB', 'periodos' => [$p5, $p6]];

//-------------------------PROGRAMAS DE ESTUDIO-----------------------------

$pe1 = ['nombre' => 'DISEÑO Y PROGRAMACION WEB', 'modulos' => [$m1, $m2, $m3]];
$pe2 = ['nombre' => 'ENFERMERIA TECNICA', 'modulos' => []];
$pe3 = ['nombre' => 'MECATRONICA AUTOMOTRIZ', 'modulos' => []];
$pe4 = ['nombre' => 'PRODUCCION AGROPECUARIA', 'modulos' => []];
$pe5 = ['nombre' => 'INDUSTRIAS DE ALIMENTOS Y BEBIDAS', 'modulos' => []];

$ies['nombre'] = "IE Público HUANTA";
$ies['programas_de_estudio'] = [$pe1, $pe2, $pe3, $pe4, $pe5];

//----------------------------- XML ----------------------------------

$xml = new DOMDocument('1.0', 'UTF-8');
$xml->formatOutput = true;

$root = $xml->createElement('institucion_educativa');
$xml->appendChild($root);

// Nombre de la institución
$root->appendChild($xml->createElement('nombre', $ies['nombre']));

// Contenedor principal
$programas = $xml->createElement('programas_de_estudio');
$root->appendChild($programas);

// Recorrer los programas
foreach ($ies['programas_de_estudio'] as $i => $pe) {

    $peNode = $xml->createElement('pe' . ($i + 1));
    $programas->appendChild($peNode);

    // Nombre del programa
    $peNode->appendChild($xml->createElement('nombre', $pe['nombre']));

    // Modulos
    foreach ($pe['modulos'] as $j => $mod) {

        $modNode = $xml->createElement('modulo' . ($j + 1));
        $peNode->appendChild($modNode);

        // Nombre del módulo
        $modNode->appendChild($xml->createElement('nombre', $mod['nombre']));

        // Periodos
        foreach ($mod['periodos'] as $k => $per) {

            $perNode = $xml->createElement('periodo' . ($k + 1));
            $modNode->appendChild($perNode);

            // Nombre del periodo
            $perNode->appendChild($xml->createElement('nombre', $per['nombre']));

            // Unidades
            $udsNode = $xml->createElement('unidades_didacticas');
            $perNode->appendChild($udsNode);

            foreach ($per['unidades_didacticas'] as $u => $ud) {

                $udNode = $xml->createElement('ud');
                $udsNode->appendChild($udNode);

                $udNode->appendChild($xml->createElement('nombre', $ud));
            }
        }
    }
}

// Guardar archivo
$archivo = "ies.xml";
$xml->save($archivo);

echo "XML generado correctamente: $archivo";