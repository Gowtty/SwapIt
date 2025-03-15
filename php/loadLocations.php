<?php
$file = __DIR__ . "/../src/locations.csv";
$locations = [];

if (!file_exists($file)) {
    echo json_encode(["error" => "Archivo no encontrado"]);
    exit;
}

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

header('Content-Type: application/json');
echo json_encode($locations);
?>