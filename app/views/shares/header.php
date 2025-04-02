<!DOCTYPE html>
<html lang="vi">

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
echo "Quyền hiện tại của bạn: " . ($_SESSION['role'] ?? 'Chưa có quyền');
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dBuuks - Cửa hàng sách</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="/webbanhang/styles.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .product-image {
            max-width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .card {
            transition: transform 0.2s;
        }

        .card:hover {
            transform: scale(1.05);
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">dBuuks</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/webbanhang/Product/">Danh sách sản phẩm</a>
                    </li>
                </ul>

                <!-- Ô tìm kiếm căn giữa -->
                <div class="search-container mx-auto">
                    <form class="d-flex w-100 justify-content-center" id="search-form">
                        <input class="form-control search-input me-2" type="search" name="query"
                            placeholder="Tìm kiếm sản phẩm..." required>
                        <button class="btn btn-outline-success" type="submit">Tìm kiếm</button>
                    </form>
                </div>

                <ul class="navbar-nav ms-3">
                    <!-- Giỏ hàng -->
                    <li class="nav-item">
                        <a class="nav-link" href="/webbanhang/Product/cart">
                            🛒 Giỏ hàng <span class="badge bg-danger"></span>
                        </a>
                    </li>

                    <!-- Hiển thị user đã đăng nhập -->
                    <?php if (isset($_SESSION['username'])): ?>
                        <li class="nav-item">
                            <a class="nav-link">👤 <?= htmlspecialchars($_SESSION['username']) ?></a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item" id="nav-login" <?php echo isset($_SESSION['username']) ? 'style="display: none;"' : ''; ?>>
                        <a class="nav-link" href="/webbanhang/account/login">Login</a>
                    </li>
                    <li class="nav-item" id="nav-logout" <?php echo !isset($_SESSION['username']) ? 'style="display: none;"' : ''; ?>>
                        <a class="nav-link" href="#" onclick="logout()">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div id="search-results" class="row"></div>
    </div>

    <script>
        function logout() {
            localStorage.removeItem('jwtToken');
            location.href = '/webbanhang/account/logout';
        }

        $(document).ready(function() {
            const token = localStorage.getItem('jwtToken');
            if (token) {
                $('#nav-login').hide();
                $('#nav-logout').show();
            } else {
                $('#nav-login').show();
                $('#nav-logout').hide();
            }

            // Xử lý form tìm kiếm
            $('#search-form').on('submit', function(e) {
                e.preventDefault();
                const query = $(this).find('input[name="query"]').val();
                const token = localStorage.getItem('jwtToken');

                if (!token) {
                    alert('Vui lòng đăng nhập để tìm kiếm!');
                    location.href = '/webbanhang/account/login';
                    return;
                }

                $.ajax({
                    url: 'http://localhost:8080/webbanhang/api/product/search?query=' + encodeURIComponent(query),
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + token
                    },
                    success: function(response) {
                        const $results = $('#search-results');
                        $results.empty();

                        if (response.status === 'success' && response.data.length > 0) {
                            response.data.forEach(product => {
                                const productHtml = `
                            <div class="col-md-4 mb-4">
                                <div class="card shadow-sm h-100">
                                    <img src="/webbanhang/${product.image || 'images/no-image.png'}" 
                                         alt="${product.name}" class="card-img-top product-image">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <a href="/webbanhang/Product/show/${product.id}" 
                                               class="text-decoration-none text-dark">${product.name}</a>
                                        </h5>
                                        <p>${product.description}</p>
                                        <p class="text-danger">💰 ${Number(product.price).toLocaleString()} VND</p>
                                        <p><span class="badge bg-info">📚 ${product.category_name}</span></p>
                                    </div>
                                    <div class="card-footer text-center">
                                        <a href="/webbanhang/Product/addToCart/${product.id}" 
                                           class="btn btn-primary btn-sm">🛒 Thêm vào giỏ</a>
                                    </div>
                                </div>
                            </div>
                        `;
                                $results.append(productHtml);
                            });
                        } else {
                            $results.html('<p class="text-center">Không tìm thấy sản phẩm nào phù hợp.</p>');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 401) {
                            alert('Phiên đăng nhập hết hạn. Vui lòng đăng nhập lại!');
                            localStorage.removeItem('jwtToken');
                            location.href = '/webbanhang/account/login';
                        } else {
                            alert('Đã xảy ra lỗi khi tìm kiếm sản phẩm!');
                        }
                    }
                });
            });
        });
    </script>

</body>

</html>