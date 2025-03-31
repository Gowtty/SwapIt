<?php
session_start();
include '../php/connectDB.php';
include '../php/checkSession.php';
include '../php/notifications.php';

// Verificar si se proporcionó un ID de usuario
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_GET['id'];
$current_user_id = $_SESSION['user_id'] ?? null;

// Obtener información del usuario
$query = "SELECT id, username, email, created_at FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    header('Location: index.php');
    exit;
}

// Obtener reseñas del usuario
$query_reviews = "SELECT r.*, u.username as reviewer_name 
                 FROM reviews r 
                 JOIN users u ON r.reviewer_id = u.id 
                 WHERE r.reviewed_id = ? 
                 ORDER BY r.created_at DESC";
$stmt = mysqli_prepare($conn, $query_reviews);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$reviews = mysqli_stmt_get_result($stmt);

// Calcular promedio de calificaciones
$query_avg = "SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews 
              FROM reviews 
              WHERE reviewed_id = ?";
$stmt = mysqli_prepare($conn, $query_avg);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$avg_result = mysqli_stmt_get_result($stmt);
$rating_stats = mysqli_fetch_assoc($avg_result);

// Verificar si el usuario actual ya hizo una reseña
$has_reviewed = false;
if ($current_user_id) {
    $check_query = "SELECT id FROM reviews WHERE reviewer_id = ? AND reviewed_id = ?";
    $stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt, 'ii', $current_user_id, $user_id);
    mysqli_stmt_execute($stmt);
    $has_reviewed = mysqli_num_rows(mysqli_stmt_get_result($stmt)) > 0;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de <?php echo htmlspecialchars($user['username']); ?> - SwapIt</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .profile-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .profile-header {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
            text-align: center;
        }
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 3rem;
            color: #6c757d;
            border: 3px solid #0d6efd;
        }
        .profile-title {
            font-size: 2rem;
            color: #333;
            margin-bottom: 1rem;
        }
        .profile-info {
            display: grid;
            gap: 1rem;
            max-width: 400px;
            margin: 0 auto;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 15px;
        }
        .profile-info p {
            margin: 0;
            color: #666;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .profile-info i {
            color: #0d6efd;
            font-size: 1.2rem;
        }
        .rating-stats {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 2rem;
            margin: 1.5rem 0;
        }
        .rating-stat {
            text-align: center;
        }
        .rating-number {
            font-size: 2rem;
            font-weight: 600;
            color: #0d6efd;
        }
        .rating-label {
            color: #666;
            font-size: 0.9rem;
        }
        .reviews-section {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .reviews-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
        }
        .reviews-title {
            font-size: 1.5rem;
            color: #333;
            margin: 0;
        }
        .review-form {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 2rem;
        }
        .review-form h3 {
            margin-bottom: 1rem;
            color: #333;
        }
        .rating-input {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        .star-input {
            display: none;
        }
        .star-label {
            font-size: 1.5rem;
            color: #dee2e6;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        .star-input:checked ~ .star-label,
        .star-label:hover,
        .star-label:hover ~ .star-label {
            color: #ffc107;
        }
        .review-textarea {
            width: 100%;
            padding: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 1rem;
            resize: vertical;
            min-height: 100px;
        }
        .submit-review-btn {
            background: #0d6efd;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .submit-review-btn:hover {
            background: #0b5ed7;
        }
        .reviews-list {
            display: grid;
            gap: 1.5rem;
        }
        .review-card {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 15px;
        }
        .review-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        .reviewer-name {
            font-weight: 600;
            color: #333;
        }
        .review-date {
            color: #666;
            font-size: 0.9rem;
        }
        .review-rating {
            color: #ffc107;
            margin-bottom: 0.5rem;
        }
        .review-comment {
            color: #666;
            line-height: 1.6;
        }
        .no-reviews {
            text-align: center;
            color: #666;
            padding: 2rem;
        }
        @media (max-width: 768px) {
            .rating-stats {
                flex-direction: column;
                gap: 1rem;
            }
            .reviews-header {
                flex-direction: column;
                gap: 1rem;
            }
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
    ?>
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="fas fa-user"></i>
            </div>
            <h1 class="profile-title"><?php echo htmlspecialchars($user['username']); ?></h1>
            <div class="profile-info">
                <p><i class="fas fa-calendar-alt"></i><strong>Miembro desde:</strong> <?php echo date('d/m/Y', strtotime($user['created_at'])); ?></p>
            </div>
            <div class="rating-stats">
                <div class="rating-stat">
                    <div class="rating-number"><?php echo number_format($rating_stats['avg_rating'] ?? 0, 1); ?></div>
                    <div class="rating-label">Calificación promedio</div>
                </div>
                <div class="rating-stat">
                    <div class="rating-number"><?php echo $rating_stats['total_reviews'] ?? 0; ?></div>
                    <div class="rating-label">Reseñas</div>
                </div>
            </div>
        </div>

        <div class="reviews-section">
            <div class="reviews-header">
                <h2 class="reviews-title">Reseñas</h2>
                <?php if ($current_user_id && !$has_reviewed && $current_user_id != $user_id): ?>
                    <button class="btn btn-primary" onclick="document.getElementById('reviewForm').style.display = 'block'">
                        <i class="fas fa-star"></i> Dejar una reseña
                    </button>
                <?php endif; ?>
            </div>

            <?php if ($current_user_id && !$has_reviewed && $current_user_id != $user_id): ?>
                <div id="reviewForm" class="review-form" style="display: none;">
                    <h3>Tu reseña</h3>
                    <form action="../php/submit-review.php" method="POST">
                        <input type="hidden" name="reviewed_id" value="<?php echo $user_id; ?>">
                        <div class="rating-input">
                            <input type="radio" name="rating" value="5" id="star5" class="star-input" required>
                            <label for="star5" class="star-label"><i class="fas fa-star"></i></label>
                            <input type="radio" name="rating" value="4" id="star4" class="star-input">
                            <label for="star4" class="star-label"><i class="fas fa-star"></i></label>
                            <input type="radio" name="rating" value="3" id="star3" class="star-input">
                            <label for="star3" class="star-label"><i class="fas fa-star"></i></label>
                            <input type="radio" name="rating" value="2" id="star2" class="star-input">
                            <label for="star2" class="star-label"><i class="fas fa-star"></i></label>
                            <input type="radio" name="rating" value="1" id="star1" class="star-input">
                            <label for="star1" class="star-label"><i class="fas fa-star"></i></label>
                        </div>
                        <textarea name="comment" class="review-textarea" placeholder="Escribe tu reseña..."></textarea>
                        <button type="submit" class="submit-review-btn">
                            <i class="fas fa-paper-plane"></i> Enviar reseña
                        </button>
                    </form>
                </div>
            <?php endif; ?>

            <div class="reviews-list">
                <?php if (mysqli_num_rows($reviews) > 0): ?>
                    <?php while ($review = mysqli_fetch_assoc($reviews)): ?>
                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-name"><?php echo htmlspecialchars($review['reviewer_name']); ?></div>
                                <div class="review-date"><?php echo date('d/m/Y', strtotime($review['created_at'])); ?></div>
                            </div>
                            <div class="review-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?php echo $i <= $review['rating'] ? 'text-warning' : 'text-muted'; ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <div class="review-comment"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-reviews">
                        <i class="fas fa-star fa-3x text-muted mb-3"></i>
                        <p>Este usuario aún no tiene reseñas.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const reviewForm = document.getElementById('reviewForm');
        if (reviewForm) {
            const form = reviewForm.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    
                    fetch('../php/submit-review.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        return response.text().then(text => {
                            console.log('Response text:', text);
                            try {
                                return JSON.parse(text);
                            } catch (e) {
                                console.error('JSON parse error:', e);
                                throw new Error('Invalid JSON response');
                            }
                        });
                    })
                    .then(data => {
                        console.log('Parsed data:', data);
                        if (data.success) {
                            showNotification(data.message, 'success');
                            setTimeout(() => {
                                window.location.href = data.redirect;
                            }, 1500);
                        } else {
                            showNotification(data.message || 'Error al enviar la reseña', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Error al enviar la reseña. Por favor, intenta nuevamente.', 'error');
                    });
                });
            }
        }
    });
    </script>

    <?php include '../php/footer.php'; ?>
</body>
</html> 