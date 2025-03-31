<?php
session_start();
include '../php/connectDB.php';
include '../php/checkSession.php';
include '../php/login-verify.php';

// Check database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$user_id = $_SESSION['user_id'];
$item_id = $_GET['id'] ?? null; // El ID del artículo al que se le hace la oferta

if (!$item_id) {
    echo "ID del artículo no proporcionado.";
    exit;
}

// Obtener el artículo al que se le está haciendo la oferta
$query = "SELECT i.*, c.name as category_name, u.username 
          FROM item i 
          LEFT JOIN categories c ON i.category_id = c.id 
          JOIN users u ON i.user_id = u.id
          WHERE i.id = ? AND i.user_id != ?";

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
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hacer Oferta - SwapIt</title>
    <?php include '../php/notifications.php'; ?>
    <style>
        .offer-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        .offer-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }
        .offer-header {
            padding: 2rem;
            border-bottom: 1px solid #dee2e6;
            text-align: center;
        }
        .offer-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: #212529;
            margin-bottom: 0.5rem;
        }
        .offer-subtitle {
            color: #6c757d;
            font-size: 1.1rem;
        }
        .offer-content {
            padding: 2rem;
        }
        .item-info {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        .item-info h3 {
            color: #212529;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }
        .item-details {
            display: flex;
            gap: 2rem;
            color: #6c757d;
            font-size: 0.9rem;
        }
        .item-details span {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #212529;
        }
        .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            font-size: 1rem;
            color: #212529;
            background-color: #fff;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            outline: none;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            width: 100%;
            justify-content: center;
        }
        .btn-primary {
            background: #0d6efd;
            color: white;
        }
        .btn-primary:hover {
            background: #0b5ed7;
            transform: translateY(-2px);
        }
        .btn-primary:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #0d6efd;
            text-decoration: none;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }
        .back-link:hover {
            color: #0a58ca;
            text-decoration: underline;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            background: #e9ecef;
            color: #495057;
        }
        .status-badge.reserved {
            background: #fff3cd;
            color: #856404;
        }
        .status-badge.exchanged {
            background: #e2e3e5;
            color: #383d41;
        }
        .items-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        .item-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
        }
        .item-image {
            width: 100%;
            height: 250px;
            object-fit: contain;
            background-color: #f8f9fa;
            padding: 1rem;
        }
        .item-info {
            padding: 1rem;
            border-top: 1px solid #dee2e6;
        }
        .item-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #212529;
        }
        .item-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: #6c757d;
            font-size: 0.9rem;
        }
        .item-meta span {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .exchange-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #0d6efd;
        }
    </style>
</head>
<body>
    <?php
    if (isset($_SESSION['notification'])) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('" . htmlspecialchars($_SESSION['notification']['message']) . "', '" . $_SESSION['notification']['type'] . "');
            });
        </script>";
        unset($_SESSION['notification']);
    }

    if ($item['status'] === 'Reservado' || $item['status'] === 'Intercambiado') {
        echo '<div class="offer-container">
                <div class="offer-card">
                    <div class="offer-header">
                        <h1 class="offer-title">No disponible para ofertas</h1>
                        <p class="offer-subtitle">Este artículo ya no está disponible para intercambio</p>
                    </div>
                    <div class="offer-content">
                        <div class="item-info">
                            <h3><i class="fas fa-exclamation-circle"></i> Estado del artículo</h3>
                            <div class="item-details">
                                <span class="status-badge ' . ($item['status'] === 'Reservado' ? 'reserved' : 'exchanged') . '">
                                    ' . htmlspecialchars($item['status']) . '
                                </span>
                            </div>
                        </div>
                        <a href="../pages/item.php?id=' . $item_id . '" class="back-link">
                            <i class="fas fa-arrow-left"></i>Volver al artículo
                        </a>
                    </div>
                </div>
            </div>';
        exit;
    }
    ?>

    <div class="offer-container">
        <div class="offer-card">
            <div class="offer-header">
                <h1 class="offer-title">Hacer oferta</h1>
                <p class="offer-subtitle">Selecciona el artículo que deseas ofrecer</p>
            </div>
            <div class="offer-content">
                <div class="items-container">
                    <!-- Artículo deseado -->
                    <div class="item-card">
                        <?php 
                        $images = json_decode($item['images'], true);
                        $firstImage = !empty($images) ? $images[0] : '../src/default.jpg';
                        ?>
                        <img src="<?php echo htmlspecialchars($firstImage); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="item-image">
                        <div class="item-info">
                            <h3 class="item-title"><?php echo htmlspecialchars($item['title']); ?></h3>
                            <div class="item-meta">
                                <span><i class="fas fa-layer-group"></i> <?php echo htmlspecialchars($item['category_name']); ?></span>
                                <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($item['username']); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Artículo a ofrecer -->
                    <div class="item-card">
                        <div class="item-info">
                            <h3 class="item-title">Tu artículo</h3>
                            <form action="../php/submit-offer.php" method="POST" id="offerForm">
                                <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
                                
                                <div class="form-group">
                                    <label for="offered_item_id" class="form-label">
                                        <i class="fas fa-exchange-alt"></i> Selecciona tu artículo
                                    </label>
                                    <select name="offered_item_id" id="offered_item_id" class="form-select" required onchange="updateOfferedItemImage(this)">
                                        <option value="">Selecciona un artículo para ofrecer</option>
                                        <?php 
                                        // Reset the result pointer
                                        mysqli_data_seek($offered_items_result, 0);
                                        while ($offered_item = mysqli_fetch_assoc($offered_items_result)): 
                                            $offered_images = json_decode($offered_item['images'], true);
                                            $offered_first_image = !empty($offered_images) ? $offered_images[0] : '../src/assets/default.png';
                                        ?>
                                            <option value="<?php echo $offered_item['id']; ?>" data-thumb="<?php echo htmlspecialchars($offered_first_image); ?>">
                                                <?php echo htmlspecialchars($offered_item['title']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i>Enviar oferta
                                </button>
                            </form>
                        </div>
                        <img id="offered-item-image" src="../src/assets/default.png" alt="Tu artículo" class="item-image">
                    </div>
                </div>

                <a href="../pages/item.php?id=<?php echo $item_id; ?>" class="back-link">
                    <i class="fas fa-arrow-left"></i>Volver al artículo
                </a>
            </div>
        </div>
    </div>

    <script>
        function updateOfferedItemImage(select) {
            const selectedOption = select.options[select.selectedIndex];
            const imageUrl = selectedOption.dataset.thumb || '../src/assets/default.png';
            document.getElementById('offered-item-image').src = imageUrl;
        }

        document.getElementById('offerForm').addEventListener('submit', function(event) {
            event.preventDefault();
            
            const formData = new FormData(this);
            
            // Debug: Log form data
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
            
            showConfirmationDialog(
                'Confirmar oferta',
                '¿Estás seguro de que deseas hacer esta oferta?',
                () => {
                    fetch(this.action, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            return response.json();
                        } else {
                            return response.text().then(text => {
                                console.error('Received non-JSON response:', text);
                                throw new Error('Server returned non-JSON response');
                            });
                        }
                    })
                    .then(data => {
                        console.log('Response data:', data);
                        if (data.success) {
                            showNotification(data.message || '¡Oferta enviada exitosamente!', 'success');
                            setTimeout(() => {
                                window.location.href = data.redirect;
                            }, 1000);
                        } else {
                            showNotification(data.message || 'Error al enviar la oferta', 'error');
                            if (data.redirect) {
                                setTimeout(() => {
                                    window.location.href = data.redirect;
                                }, 2000);
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Error al procesar la respuesta del servidor. Por favor, intenta nuevamente.', 'error');
                    });
                }
            );
        });
    </script>

    <?php include '../php/footer.php'; ?>
</body>
</html>