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
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $state = trim($data[0]);       // Estado
        $city = trim($data[1]);         // Ciudad
        $postalCode = trim($data[2]);   // Código Postal

        // Verificar si el estado ya existe en el array
        if (!isset($locations[$state])) {
            $locations[$state] = [];
        }
        
        // Agregar la ciudad y el código postal al array del estado
        $locations[$state][] = [
            'city' => $city,
            'postal_code' => $postalCode
        ];
    }
    fclose($handle);
}
// Se convierte el resultado a json para su lectura
header('Content-Type: application/json');
echo json_encode($locations);
?>