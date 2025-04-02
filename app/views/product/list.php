<?php include 'C:\laragon\www\webbanhang\app\views\shares\header.php'; ?>
<?php include 'C:\laragon\www\webbanhang\app\views\shares\banner.php'; ?>
<link rel="stylesheet" href="/webbanhang/styles.css">

<div class="container mt-4">
    <!-- Tiêu đề -->
    <div class="text-center mb-4">
        <h1 class="text-white bg-primary py-3 rounded">Danh Sách Sản Phẩm</h1>
    </div>

    <!-- Bộ lọc và sắp xếp -->
    <div class="row mb-4">
        <div class="col-md-6">
            <label for="category-filter" class="form-label">Lọc theo danh mục:</label>
            <select id="category-filter" class="form-select">
                <option value="">Tất cả danh mục</option>
                <!-- Danh mục sẽ được tải từ API -->
            </select>
        </div>
        <div class="col-md-6">
            <label for="sort-price" class="form-label">Sắp xếp theo giá:</label>
            <select id="sort-price" class="form-select">
                <option value="">Mặc định</option>
                <option value="asc">Giá tăng dần</option>
                <option value="desc">Giá giảm dần</option>
            </select>
        </div>
    </div>

    <!-- Danh sách sản phẩm dạng lưới -->
    <div class="row" id="product-list">
        <!-- Sản phẩm sẽ được tải từ API và hiển thị tại đây -->
    </div>

    <!-- Nút Thêm Sản Phẩm (Chỉ admin mới thấy) -->
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <div class="text-center mt-4">
            <a href="/webbanhang/Product/add" class="btn btn-success btn-lg shadow">+ Thêm sản phẩm mới</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'C:\laragon\www\webbanhang\app\views\shares\footer.php'; ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const token = localStorage.getItem('jwtToken');
    if (!token) {
        alert('Vui lòng đăng nhập');
        location.href = '/webbanhang/account/login';
        return;
    }

    // Lấy vai trò người dùng từ PHP
    const userRole = '<?php echo isset($_SESSION['role']) ? $_SESSION['role'] : ''; ?>';

    // Tải danh sách danh mục
    fetch('/webbanhang/api/category', {
        headers: {
            'Authorization': 'Bearer ' + token
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Không thể tải danh mục');
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            const categoryFilter = document.getElementById('category-filter');
            data.data.forEach(category => {
                const option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.name;
                categoryFilter.appendChild(option);
            });
        }
    })
    .catch(error => {
        console.error('Lỗi tải danh mục:', error);
    });

    // Hàm tải danh sách sản phẩm
    function loadProducts(categoryId = '', sortOrder = '') {
        let url = '/webbanhang/api/product';
        const params = [];
        if (categoryId) params.push(`category_id=${categoryId}`);
        if (sortOrder) params.push(`sort_price=${sortOrder}`);
        if (params.length > 0) url += '?' + params.join('&');

        fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Unauthorized or error: ' + response.status);
            }
            return response.json();
        })
        .then(responseData => {
            if (responseData.status !== 'success') {
                console.error("Lỗi khi tải dữ liệu sản phẩm", responseData.message);
                return;
            }

            const data = responseData.data;
            const productList = document.getElementById('product-list');
            productList.innerHTML = '';

            data.forEach(product => {
                const productCard = document.createElement('div');
                productCard.className = 'col-md-4 mb-4';
                productCard.innerHTML = `
                    <div class="card shadow-sm h-100">
                        <div class="d-flex justify-content-center align-items-center" style="height: 250px; overflow: hidden;">
                            <img src="/webbanhang/${product.image ? product.image : 'images/no-image.png'}" 
                                alt="Product Image" class="card-img-top img-fluid">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="/webbanhang/Product/show/${product.id}" class="text-decoration-none text-dark">
                                    ${product.name}
                                </a>
                            </h5>
                            <p class="text-muted">${product.description}</p>
                            <p class="fw-bold text-danger">💰 ${Number(product.price).toLocaleString()} VND</p>
                            <p><span class="badge bg-info text-white">📚 ${product.category_name}</span></p>
                        </div>
                        <div class="card-footer text-center">
                            ${userRole === 'admin' ? `
                                <a href="/webbanhang/Product/edit/${product.id}" class="btn btn-warning btn-sm me-2">✏️ Sửa</a>
                                <button class="btn btn-danger btn-sm" onclick="deleteProduct(${product.id})">🗑️ Xóa</button>
                            ` : `
                                <a href="/webbanhang/Product/addToCart/${product.id}" class="btn btn-primary btn-sm">🛒 Thêm vào giỏ</a>
                            `}
                        </div>
                    </div>
                `;
                productList.appendChild(productCard);
            });
        })
        .catch(error => {
            console.error("Lỗi khi tải dữ liệu từ API:", error);
            alert('Không thể tải danh sách sản phẩm. Vui lòng đăng nhập lại.');
            location.href = '/webbanhang/account/login';
        });
    }

    // Tải sản phẩm ban đầu
    loadProducts();

    // Sự kiện lọc theo danh mục
    document.getElementById('category-filter').addEventListener('change', function() {
        const categoryId = this.value;
        const sortOrder = document.getElementById('sort-price').value;
        loadProducts(categoryId, sortOrder);
    });

    // Sự kiện sắp xếp theo giá
    document.getElementById('sort-price').addEventListener('change', function() {
        const sortOrder = this.value;
        const categoryId = document.getElementById('category-filter').value;
        loadProducts(categoryId, sortOrder);
    });

    // Hàm xóa sản phẩm
    window.deleteProduct = function(id) {
        if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
            fetch(`/webbanhang/api/product/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + token
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    location.reload();
                } else {
                    alert('Xóa sản phẩm thất bại: ' + data.message);
                }
            })
            .catch(error => {
                console.error("Lỗi khi xóa sản phẩm:", error);
                alert('Không thể xóa sản phẩm.');
            });
        }
    };
});
</script>