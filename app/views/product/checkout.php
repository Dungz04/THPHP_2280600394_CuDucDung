<?php include 'C:\laragon\www\webbanhang\app\views\shares\header.php'; ?>
<link rel="stylesheet" href="/webbanhang/styles.css">

<div class="container mt-4">
    <!-- Tiêu đề -->
    <div class="text-center mb-4">
        <h1 class="text-white bg-primary py-3 rounded">💳 Thanh toán đơn hàng</h1>
    </div>

    <div class="card shadow-lg p-4">
        <form method="POST" action="/webbanhang/Product/processCheckout">
            <!-- Họ tên -->
            <div class="mb-3">
                <label for="name" class="form-label fw-bold">👤 Họ tên:</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Nhập họ tên của bạn" required>
            </div>

            <!-- Số điện thoại -->
            <div class="mb-3">
                <label for="phone" class="form-label fw-bold">📞 Số điện thoại:</label>
                <input type="text" id="phone" name="phone" class="form-control" placeholder="Nhập số điện thoại" required>
            </div>

            <!-- Địa chỉ -->
            <div class="mb-3">
                <label for="address" class="form-label fw-bold">🏠 Địa chỉ giao hàng:</label>
                <textarea id="address" name="address" class="form-control" rows="3" placeholder="Nhập địa chỉ giao hàng" required></textarea>
            </div>

            <!-- Phương thức thanh toán -->
            <div class="mb-3">
                <label class="form-label fw-bold">💰 Phương thức thanh toán:</label>
                <select name="payment_method" class="form-select">
                    <option value="cash">🪙 Thanh toán khi nhận hàng</option>
                    <option value="bank">🏦 Chuyển khoản ngân hàng</option>
                </select>
            </div>

            <!-- Nút thanh toán -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success btn-lg">✅ Xác nhận thanh toán</button>
                <a href="/webbanhang/Product/cart" class="btn btn-secondary btn-lg ms-2">🔙 Quay lại giỏ hàng</a>
            </div>
        </form>
    </div>
</div>

<?php include 'C:\laragon\www\webbanhang\app\views\shares\footer.php'; ?>
