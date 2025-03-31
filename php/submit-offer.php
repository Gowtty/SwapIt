<?php
ob_start();
session_start();
include 'connectDB.php';
include 'checkSession.php';
include 'notifications.php';

// Clear any previous output
ob_clean();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $item_id = $_POST['item_id'] ?? null;
    $offered_item_id = $_POST['offered_item_id'] ?? null;
    $user_id = $_SESSION['user_id'];


    if (!$item_id || !$offered_item_id) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Error: Faltan datos requeridos para la oferta.', 'redirect' => '../pages/make-offer.php?id=' . $item_id]);
        exit;
    }

    // Verificar que el usuario no está haciendo una oferta por su propio artículo
    $check_query = "SELECT user_id FROM item WHERE id = ?";
    $stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt, 'i', $item_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $item = mysqli_fetch_assoc($result);

    if ($item['user_id'] == $user_id) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'No puedes hacer una oferta por tu propio artículo.', 'redirect' => '../pages/item.php?id=' . $item_id]);
        exit;
    }

    // Verificar que el artículo ofrecido pertenece al usuario
    $check_offered_query = "SELECT id, status FROM item WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $check_offered_query);
    mysqli_stmt_bind_param($stmt, 'ii', $offered_item_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $offered_item = mysqli_fetch_assoc($result);

    if (!$offered_item) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'El artículo que intentas ofrecer no te pertenece.', 'redirect' => '../pages/make-offer.php?id=' . $item_id]);
        exit;
    }

    if ($offered_item['status'] !== 'Disponible') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'El artículo que intentas ofrecer no está disponible.', 'redirect' => '../pages/make-offer.php?id=' . $item_id]);
        exit;
    }

    // Verificar que no existe una oferta previa
    $check_existing_offer = "SELECT id FROM offers WHERE item_id = ? AND offered_item_id = ? AND status = 'Pendiente'";
    $stmt = mysqli_prepare($conn, $check_existing_offer);
    mysqli_stmt_bind_param($stmt, 'ii', $item_id, $offered_item_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Ya has realizado una oferta con este artículo.', 'redirect' => '../pages/make-offer.php?id=' . $item_id]);
        exit;
    }

    // Insertar la oferta
    $query = "INSERT INTO offers (item_id, offered_item_id, offered_by, status) VALUES (?, ?, ?, 'Pendiente')";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'iii', $item_id, $offered_item_id, $user_id);

    // Execute the query and check for errors
    if (mysqli_stmt_execute($stmt)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => '¡Oferta enviada exitosamente!', 'redirect' => '../pages/item.php?id=' . $item_id]);
        exit;
    } else {
        $error_message = mysqli_stmt_error($stmt);
        error_log("SQL Error: " . $error_message);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false, 
            'message' => 'Error al enviar la oferta: ' . $error_message,
            'redirect' => '../pages/make-offer.php?id=' . $item_id
        ]);
        exit;
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'redirect' => '../pages/index.php']);
    exit;
}
?>