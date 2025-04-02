<?php include 'C:\laragon\www\webbanhang\app\views\shares\header.php'; ?>
<link rel="stylesheet" href="/webbanhang/styles.css">

<div class="container mt-4">
    <!-- Tiêu đề -->
    <div class="text-center mb-4">
        <h1 class="text-white bg-primary py-3 rounded">🛒 Giỏ hàng của bạn</h1>
    </div>

    <?php if (!empty($cart)): ?>
        <table class="table table-bordered text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Hình ảnh</th>
                    <th>Sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = 0; // Tổng tiền
                foreach ($cart as $id => $item): 
                    $subtotal = $item['price'] * $item['quantity']; 
                    $total += $subtotal;
                ?>
                <tr data-id="<?php echo $id; ?>" data-price="<?php echo $item['price']; ?>">
                    <!-- Hình ảnh -->
                    <td>
                        <?php if ($item['image']): ?>
                            <img src="/webbanhang/<?php echo htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8'); ?>" 
                                alt="Product Image" class="cart-img">
                        <?php else: ?>
                            <img src="/webbanhang/images/no-image.png" 
                                alt="No Image" class="cart-img">
                        <?php endif; ?>
                    </td>

                    <!-- Tên sản phẩm -->
                    <td class="text-start">
                        <h5><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h5>
                    </td>

                    <!-- Giá tiền -->
                    <td class="text-danger fw-bold">
                        <?php echo number_format($item['price'], 0, ',', '.'); ?> VND
                    </td>

                    <!-- Số lượng -->
                    <td>
                        <input type="number" value="<?php echo $item['quantity']; ?>" 
                               class="form-control text-center cart-quantity" min="1">
                    </td>

                    <!-- Thành tiền -->
                    <td class="text-success fw-bold subtotal">
                        <?php echo number_format($subtotal, 0, ',', '.'); ?> VND
                    </td>

                    <!-- Nút Xóa -->
                    <td>
                        <a href="/webbanhang/Product/removeFromCart/<?php echo $id; ?>" 
                           class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                            ❌ Xóa
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Hiển thị tổng tiền -->
        <div class="text-end">
            <h4 class="text-danger fw-bold">Tổng cộng: <span id="total"><?php echo number_format($total, 0, ',', '.'); ?></span> VND</h4>
        </div>

        <!-- Nút thao tác -->
        <div class="text-center mt-4">
            <a href="/webbanhang/Product" class="btn btn-secondary btn-lg me-2">🔄 Tiếp tục mua sắm</a>
            <a href="/webbanhang/Product/checkout" class="btn btn-success btn-lg">💳 Thanh Toán</a>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center">
            <h4>🛒 Giỏ hàng của bạn đang trống!</h4>
            <a href="/webbanhang/Product" class="btn btn-primary mt-3">🛍️ Mua sắm ngay</a>
        </div>
    <?php endif; ?>
</div>

<!-- JavaScript -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    let cartQuantities = document.querySelectorAll(".cart-quantity");

    cartQuantities.forEach(input => {
        input.addEventListener("input", function() {
            let row = this.closest("tr");
            let productId = row.getAttribute("data-id");
            let price = parseFloat(row.getAttribute("data-price"));
            let quantity = parseInt(this.value);
            
            if (quantity < 1) {
                this.value = 1;
                quantity = 1;
            }

            // Cập nhật thành tiền của sản phẩm
            let subtotal = price * quantity;
            row.querySelector(".subtotal").textContent = new Intl.NumberFormat("vi-VN").format(subtotal) + " VND";

            // Cập nhật tổng tiền giỏ hàng
            updateTotal();

            // Gửi AJAX cập nhật số lượng trong session
            updateCart(productId, quantity);
        });
    });

    function updateTotal() {
        let total = 0;
        document.querySelectorAll(".subtotal").forEach(sub => {
            total += parseFloat(sub.textContent.replace(/[^0-9]/g, ""));
        });

        document.getElementById("total").textContent = new Intl.NumberFormat("vi-VN").format(total);
    }

    function updateCart(productId, quantity) {
        fetch("/webbanhang/Product/updateCart", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ id: productId, quantity: quantity })
        }).then(response => response.json())
          .then(data => console.log(data))
          .catch(error => console.error("Lỗi cập nhật giỏ hàng:", error));
    }
});
</script>

<?php include 'C:\laragon\www\webbanhang\app\views\shares\footer.php'; ?>
