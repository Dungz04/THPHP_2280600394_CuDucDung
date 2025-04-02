<?php include 'C:\laragon\www\webbanhang\app\views\shares\header.php'; ?>
<link rel="stylesheet" href="C:\laragon\www\webbanhang\styles.css">

<div class="container mt-5 product-container">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center py-3">
            <h2 class="mb-0">📌 Chi Tiết Sản Phẩm</h2>
        </div>
        <div class="card-body">
            <?php if ($product): ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="product-image-container">
                            <?php if ($product->image): ?>
                                <img src="/webbanhang/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>"
                                    class="product-image"
                                    alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>">
                            <?php else: ?>
                                <img src="/webbanhang/images/no-image.png"
                                    class="product-image"
                                    alt="Không có ảnh">
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h3 class="product-title">
                            <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                        </h3>

                        <p class="product-description">
                            <?php echo nl2br(htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8')); ?>
                        </p>

                        <p class="product-price">
                            💰 <?php echo number_format($product->price, 0, ',', '.'); ?> VND
                        </p>

                        <p><strong>📂 Danh mục:</strong>
                            <span class="badge bg-info text-white">
                                <?php echo !empty($product->category_name) ?
                                    htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8') : 'Chưa có danh mục'; ?>
                            </span>
                        </p>

                        <div class="mt-4 action-buttons">
                            <a href="/webbanhang/Product/addToCart/<?php echo $product->id; ?>"
                                class="btn btn-add-cart px-4">➕ Thêm vào giỏ hàng</a>

                            <a href="/webbanhang/Product/list" class="btn btn-back px-4 ml-2">⬅️ Quay lại danh sách</a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-danger text-center">
                    <h4>❌ Không tìm thấy sản phẩm!</h4>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'C:\laragon\www\webbanhang\app\views\shares\footer.php'; ?>