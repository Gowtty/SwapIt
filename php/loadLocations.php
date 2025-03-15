<?php
$file = "../src/locations.csv";
$locations = [];

if (!file_exists($file)) {
    echo json_encode(["error" => "Archivo no encontrado"]);
    exit;
}
//Se lee el archivo csv linea por linea saltandose la primera para extraer estado, ciudad
if (($handle = fopen($file, "r")) !== FALSE){
    fgetcsv($handle);
    while(($data = fgetcsv($handle, 1000, ",")) !== FALSE){
        $state = trim($data[0]);
        $city = trim($data[1]);

        if(!isset($locations[$state])){
            $locations[$state] = [];
        }

        $locations[$state][] = $city;
    }
    fclose($handle);
}
// Se convierte el resultado a json para su lectura
header('Content-Type: application/json');
echo json_encode($locations);
?>