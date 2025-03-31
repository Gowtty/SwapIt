<?php
// Notification types: success, error, warning, info
function showNotification($message, $type = 'success') {
    echo "<div class='notification notification-{$type}' id='notification'>
            <div class='notification-content'>
                <i class='fas " . getNotificationIcon($type) . "'></i>
                <span>{$message}</span>
            </div>
            <button class='notification-close' onclick='closeNotification()'>
                <i class='fas fa-times'></i>
            </button>
          </div>";
}

function getNotificationIcon($type) {
    switch ($type) {
        case 'success':
            return 'fa-check-circle';
        case 'error':
            return 'fa-exclamation-circle';
        case 'warning':
            return 'fa-exclamation-triangle';
        case 'info':
            return 'fa-info-circle';
        default:
            return 'fa-info-circle';
    }
}
?>

<style>
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        gap: 1rem;
        min-width: 300px;
        max-width: 400px;
        animation: slideIn 0.3s ease-out;
    }

    .notification-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .notification-error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .notification-warning {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeeba;
    }

    .notification-info {
        background-color: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }

    .notification-content {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .notification-content i {
        font-size: 1.25rem;
    }

    .notification-close {
        background: none;
        border: none;
        color: inherit;
        cursor: pointer;
        padding: 0.25rem;
        margin-left: auto;
    }

    .notification-close:hover {
        opacity: 0.8;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    /* Confirmation Dialog */
    .confirmation-dialog {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 10000;
        justify-content: center;
        align-items: center;
    }

    .confirmation-content {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        max-width: 400px;
        width: 90%;
        text-align: center;
    }

    .confirmation-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #212529;
    }

    .confirmation-message {
        color: #6c757d;
        margin-bottom: 1.5rem;
    }

    .confirmation-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    .confirmation-button {
        padding: 0.5rem 1.5rem;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .confirmation-button.confirm {
        background-color:rgb(63, 172, 1);
        color: white;
    }

    .confirmation-button.confirm:hover {
        background-color:rgb(10, 113, 27);
    }

    .confirmation-button.cancel {
        background-color: #6c757d;
        color: white;
    }

    .confirmation-button.cancel:hover {
        background-color: #5a6268;
    }
</style>

<script>
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas ${getNotificationIcon(type)}"></i>
                <span>${message}</span>
            </div>
            <button class="notification-close" onclick="closeNotification(this)">
                <i class="fas fa-times"></i>
            </button>
        `;
        document.body.appendChild(notification);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            closeNotification(notification);
        }, 5000);
    }

    function closeNotification(element) {
        if (element) {
            element.closest('.notification').style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => {
                element.closest('.notification').remove();
            }, 300);
        }
    }

    function getNotificationIcon(type) {
        switch (type) {
            case 'success':
                return 'fa-check-circle';
            case 'error':
                return 'fa-exclamation-circle';
            case 'warning':
                return 'fa-exclamation-triangle';
            case 'info':
                return 'fa-info-circle';
            default:
                return 'fa-info-circle';
        }
    }

    function showConfirmationDialog(title, message, onConfirm, onCancel = null) {
        const dialog = document.createElement('div');
        dialog.className = 'confirmation-dialog';
        dialog.innerHTML = `
            <div class="confirmation-content">
                <h3 class="confirmation-title">${title}</h3>
                <p class="confirmation-message">${message}</p>
                <div class="confirmation-buttons">
                    <button class="confirmation-button confirm">Confirmar</button>
                    <button class="confirmation-button cancel">Cancelar</button>
                </div>
            </div>
        `;
        document.body.appendChild(dialog);
        dialog.style.display = 'flex';

        const confirmButton = dialog.querySelector('.confirm');
        const cancelButton = dialog.querySelector('.cancel');

        confirmButton.addEventListener('click', () => {
            dialog.remove();
            if (onConfirm) onConfirm();
        });

        cancelButton.addEventListener('click', () => {
            dialog.remove();
            if (onCancel) onCancel();
        });
    }
</script> 