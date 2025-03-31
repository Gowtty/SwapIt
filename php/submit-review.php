<?php
session_start();
include 'connectDB.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Debes iniciar sesión para enviar una reseña.',
        'redirect' => '../pages/login.php'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reviewer_id = $_SESSION['user_id'];
    $reviewed_id = $_POST['reviewed_id'] ?? null;
    $rating = $_POST['rating'] ?? null;
    $comment = $_POST['comment'] ?? '';

    if (!$reviewed_id || !$rating) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: Faltan datos requeridos para la reseña.',
            'redirect' => '../pages/view-profile.php?id=' . $reviewed_id
        ]);
        exit;
    }

    // Verificar que el usuario no está haciendo una reseña de sí mismo
    if ($reviewer_id == $reviewed_id) {
        echo json_encode([
            'success' => false,
            'message' => 'No puedes hacer una reseña de ti mismo.',
            'redirect' => '../pages/view-profile.php?id=' . $reviewed_id
        ]);
        exit;
    }

    // Verificar que el usuario no ha hecho una reseña previa
    $check_query = "SELECT id FROM reviews WHERE reviewer_id = ? AND reviewed_id = ?";
    $stmt = mysqli_prepare($conn, $check_query);
    if (!$stmt) {
        echo json_encode([
            'success' => false,
            'message' => 'Error al preparar la consulta: ' . mysqli_error($conn),
            'redirect' => '../pages/view-profile.php?id=' . $reviewed_id
        ]);
        exit;
    }

    mysqli_stmt_bind_param($stmt, 'ii', $reviewer_id, $reviewed_id);
    if (!mysqli_stmt_execute($stmt)) {
        echo json_encode([
            'success' => false,
            'message' => 'Error al ejecutar la consulta: ' . mysqli_stmt_error($stmt),
            'redirect' => '../pages/view-profile.php?id=' . $reviewed_id
        ]);
        exit;
    }

    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Ya has realizado una reseña para este usuario.',
            'redirect' => '../pages/view-profile.php?id=' . $reviewed_id
        ]);
        exit;
    }

    // Insertar la reseña
    $query = "INSERT INTO reviews (reviewer_id, reviewed_id, rating, comment) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        echo json_encode([
            'success' => false,
            'message' => 'Error al preparar la consulta: ' . mysqli_error($conn),
            'redirect' => '../pages/view-profile.php?id=' . $reviewed_id
        ]);
        exit;
    }

    mysqli_stmt_bind_param($stmt, 'iiis', $reviewer_id, $reviewed_id, $rating, $comment);
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            'success' => true,
            'message' => '¡Reseña enviada exitosamente!',
            'redirect' => '../pages/view-profile.php?id=' . $reviewed_id
        ]);
        exit;
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error al enviar la reseña: ' . mysqli_stmt_error($stmt),
            'redirect' => '../pages/view-profile.php?id=' . $reviewed_id
        ]);
        exit;
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido',
        'redirect' => '../pages/index.php'
    ]);
    exit;
}
?> 