<?php
function resizeImage($sourcePath, $destinationPath, $maxWidth, $maxHeight) {
    // Obtener la información de la imagen
    list($originalWidth, $originalHeight, $imageType) = getimagesize($sourcePath);

    // Calcular las nuevas dimensiones
    $aspectRatio = $originalWidth / $originalHeight;
    if ($originalWidth > $originalHeight) {
        $newWidth = $maxWidth;
        $newHeight = round($maxWidth / $aspectRatio);
    } else {
        $newHeight = $maxHeight;
        $newWidth = round($maxHeight * $aspectRatio);
    }

    // Crear la imagen a partir del archivo original
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $srcImage = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $srcImage = imagecreatefrompng($sourcePath);
            break;
        case IMAGETYPE_GIF:
            $srcImage = imagecreatefromgif($sourcePath);
            break;
        default:
            return false; // Si el tipo de imagen no es válido, devolvemos false
    }

    // Crear una nueva imagen vacía con las nuevas dimensiones
    $dstImage = imagecreatetruecolor($newWidth, $newHeight);

    // Redimensionar la imagen original y copiarla a la nueva imagen
    imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

    // Guardar la nueva imagen en el archivo de destino
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            imagejpeg($dstImage, $destinationPath, 90); // 90 es la calidad de la imagen
            break;
        case IMAGETYPE_PNG:
            imagepng($dstImage, $destinationPath, 9); // 9 es la calidad de la imagen
            break;
        case IMAGETYPE_GIF:
            imagegif($dstImage, $destinationPath);
            break;
    }

    // Liberar la memoria
    imagedestroy($srcImage);
    imagedestroy($dstImage);

    return true;
}
?>