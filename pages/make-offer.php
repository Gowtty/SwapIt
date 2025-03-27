<?php
session_start();
include '../php/connectDB.php';
include '../php/checkSession.php';
include '../php/login-verify.php';

$user_id = $_SESSION['user_id'];
$item_id = $_GET['id'] ?? null; // El ID del artículo al que se le hace la oferta

if (!$item_id) {
    echo "ID del artículo no proporcionado.";
    exit;
}

// Obtener el artículo al que se le está haciendo la oferta
$query = "SELECT * FROM item WHERE id = ? AND user_id != ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'ii', $item_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$item = mysqli_fetch_assoc($result);

if (!$item) {
    echo "Artículo no encontrado o no puedes hacer una oferta por este artículo.";
    exit;
}

// Obtener los artículos del usuario para ofrecer
$query_offered_items = "SELECT * FROM item WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $query_offered_items);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$offered_items_result = mysqli_stmt_get_result($stmt);

?>
<form action="../php/submit-offer.php" method="POST">
    <h2>Hacer oferta por: <?php echo htmlspecialchars($item['title']); ?></h2>

    <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
    <label for="offered_item_id">Selecciona el artículo que vas a ofrecer:</label>
    <select name="offered_item_id" required>
        <?php while ($offered_item = mysqli_fetch_assoc($offered_items_result)): ?>
            <option value="<?php echo $offered_item['id']; ?>"><?php echo htmlspecialchars($offered_item['title']); ?></option>
        <?php endwhile; ?>
    </select>
    <br>
    <input type="submit" value="Enviar oferta">
</form>
<script src="../js/offer-actions.js"></script>