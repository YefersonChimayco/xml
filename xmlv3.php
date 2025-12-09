<?php
$conexion = new mysqli("localhost", "root", "root");
if ($conexion->connect_errno) {
    echo "Fallo al conectar a MySQL: (" . $conexion->connect_errno . ") " . $conexion->connect_error;
}

$nombreBD = "version3";

$conexion->query("DROP DATABASE IF EXISTS $nombreBD");
$conexion->query("CREATE DATABASE $nombreBD CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci");
$conexion->select_db($nombreBD);


$conexion->query("CREATE TABLE sigi_programa_estudios (
    id INT NOT NULL AUTO_INCREMENT,
    codigo VARCHAR(10) COLLATE utf8mb3_spanish2_ci NOT NULL,
    tipo VARCHAR(20) COLLATE utf8mb3_spanish2_ci NOT NULL,
    nombre VARCHAR(100) COLLATE utf8mb3_spanish2_ci NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci");

$conexion->query("CREATE TABLE sigi_planes_estudio (
    id INT NOT NULL AUTO_INCREMENT,
    id_programa_estudios INT NOT NULL,
    nombre VARCHAR(20) COLLATE utf8mb3_spanish2_ci NOT NULL,
    resolucion VARCHAR(100) COLLATE utf8mb3_spanish2_ci NOT NULL,
    fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    perfil_egresado VARCHAR(3000) COLLATE utf8mb3_spanish2_ci NOT NULL,
    PRIMARY KEY (id),
    KEY fk_planes_programa (id_programa_estudios),
    CONSTRAINT fk_planes_programa FOREIGN KEY (id_programa_estudios) 
    REFERENCES sigi_programa_estudios(id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci");

$conexion->query("CREATE TABLE sigi_modulo_formativo (
    id INT NOT NULL AUTO_INCREMENT,
    descripcion VARCHAR(1000) COLLATE utf8mb3_spanish2_ci NOT NULL,
    nro_modulo INT NOT NULL,
    id_plan_estudio INT NOT NULL,
    PRIMARY KEY (id),
    KEY fk_modulo_plan (id_plan_estudio),
    CONSTRAINT fk_modulo_plan FOREIGN KEY (id_plan_estudio) 
    REFERENCES sigi_planes_estudio(id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci");

$conexion->query("CREATE TABLE sigi_semestre (
    id INT NOT NULL AUTO_INCREMENT,
    descripcion VARCHAR(5) COLLATE utf8mb3_spanish2_ci NOT NULL,
    id_modulo_formativo INT NOT NULL,
    PRIMARY KEY (id),
    KEY id_modulo_formativo (id_modulo_formativo),
    CONSTRAINT sigi_semestre_ibfk_1 FOREIGN KEY (id_modulo_formativo) 
    REFERENCES sigi_modulo_formativo(id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci");

$conexion->query("CREATE TABLE sigi_unidad_didactica (
    id INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(200) COLLATE utf8mb3_spanish2_ci NOT NULL,
    id_semestre INT NOT NULL,
    creditos_teorico INT NOT NULL,
    creditos_practico INT NOT NULL,
    tipo VARCHAR(20) COLLATE utf8mb3_spanish2_ci NOT NULL,
    orden INT NOT NULL,
    PRIMARY KEY (id),
    KEY id_semestre (id_semestre),
    CONSTRAINT sigi_unidad_didactica_ibfk_1 FOREIGN KEY (id_semestre) 
    REFERENCES sigi_semestre(id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci");

$xml = simplexml_load_file('ies2.xml') or die('ERROR NO SE CARGÓ EL XML. escribe correctamente el nombre del archivo');

foreach ($xml as $i_pe => $pe) {
    echo '---Nombre del program de estudios : ' . $pe->nombre . '<br>';
    echo '--- Codigo : ' . $pe->codigo . '<br>';
    echo '---Tipo : ' . $pe->tipo . '<br>';
    
    $consulta = "INSERT INTO sigi_programa_estudios (codigo, tipo, nombre) VALUES ('$pe->codigo', '$pe->tipo', '$pe->nombre')";
    $conexion->query($consulta);
    $id_pe = $conexion->insert_id;

    foreach ($pe->planes_estudio[0] as $i_ple => $plan) {
        echo $plan->nombre;
        echo '-----nombre del plan : ' . $plan->nombre.'<br>';
        echo '-----Nombre de la resolucion: ' . $plan->resolucion.'<br>';
        echo '-----LA Fecha del registro : ' . $plan->fecha_registro.'<br>';
        $fecha = date('Y-m-d H:i:s', strtotime($plan->fecha_registro));

        $consulta = "INSERT INTO sigi_planes_estudio (id_programa_estudios, nombre, resolucion, fecha_registro, perfil_egresado) 
                     VALUES ($id_pe, '$plan->nombre', '$plan->resolucion', '$fecha', '')";
        $conexion->query($consulta);
        $id_plan = $conexion->insert_id;

        foreach ($plan->modulos_formativos[0] as $id_mod => $modulo) {
            echo '----- Descripcion : ' . $modulo->descripcion.'<br>';
            echo '----- NUmero del modulo : ' . $modulo->nro_modulo.'<br>';
            
            $consulta = "INSERT INTO sigi_modulo_formativo (descripcion, nro_modulo, id_plan_estudio) 
                           VALUES ('$modulo->descripcion', $modulo->nro_modulo, $id_plan)";
            $conexion->query($consulta);
            $id_modulo = $conexion->insert_id;

            foreach ($modulo->periodos[0] as $id_per => $per) {
                echo '----- Descricipcion del periodo : ' . $per->descripcion.'<br>';
                
                $consulta = "INSERT INTO sigi_semestre (descripcion, id_modulo_formativo) 
                            VALUES ('$per->descripcion', $id_modulo)";
                $conexion->query($consulta);
                $id_periodo = $conexion->insert_id;
                
                $orden = 1;
                foreach ($per->unidades_didacticas[0] as $id_ud => $ud) {
                    echo '-----Nombre ud: ' . $ud->nombre.'<br>';
                    echo '----- Creditos teoricos : ' . $ud->creditos_teorico.'<br>';
                    echo '-----Creditos practicos : ' . $ud->creditos_practico.'<br>';
                    echo '-----Tipo : ' . $ud->tipo.'<br>';
                    echo '-----Hora semanal : ' . $ud->horas_semanal.'<br>';
                    echo '-----Hora semestral : ' . $ud->horas_semestral.'<br>';
                    
                    $sql_ud = "INSERT INTO sigi_unidad_didactica 
                               (nombre, id_semestre, creditos_teorico, creditos_practico, tipo, orden) 
                               VALUES ('$ud->nombre', $id_periodo, $ud->creditos_teorico, 
                                       $ud->creditos_practico, '$ud->tipo', $orden)";
                    $conexion->query($sql_ud);
                    $orden++;
                }
            }
        }
    }
}

$conexion->close();
?>