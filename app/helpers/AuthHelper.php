<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function checkRole($requiredRole) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $requiredRole) {
        $_SESSION['error_message'] = "Bạn không có quyền truy cập trang này!";
        header("Location: /webbanhang/Product");
        exit();
    }
}
?>
