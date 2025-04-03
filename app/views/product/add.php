<?php include 'C:\laragon\www\webbanhang\app\views\shares\header.php'; ?>
<link rel="stylesheet" href="/webbanhang/styles.css">

<div class="container mt-5">
    <div class="card shadow-lg p-4 mx-auto" style="max-width: 800px;">
        <h2 class="text-center text-primary">Thêm Sản Phẩm Mới</h2>

        <div id="error-message" class="alert alert-danger d-none"></div>

        <form id="add-product-form" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Tên sản phẩm:</label>
                <input type="text" id="name" name="name" class="form-control border-primary" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Mô tả:</label>
                <textarea id="description" name="description" class="form-control border-primary" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Giá:</label>
                <input type="number" id="price" name="price" class="form-control border-primary" step="0.01" min="0" required>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Danh mục:</label>
                <select id="category_id" name="category_id" class="form-select border-primary" required>
                    <option value="">Đang tải danh mục...</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Ảnh sản phẩm:</label>
                <input type="file" id="image" name="image" class="form-control border-primary" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 shadow">Thêm sản phẩm</button>
        </form>

        <a href="/webbanhang/Product/list" class="btn btn-secondary w-100 mt-3 shadow">Quay lại danh sách sản phẩm</a>
    </div>
</div>

<?php include 'C:\laragon\www\webbanhang\app\views\shares\footer.php'; ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const token = localStorage.getItem('jwtToken');

    if (!token) {
        alert('Vui lòng đăng nhập để thêm sản phẩm!');
        window.location.href = '/webbanhang/account/login';
        return;
    }

    // Tải danh mục
    loadCategories();

    function loadCategories() {
        fetch("/webbanhang/api/category", {
            headers: {
                'Authorization': 'Bearer ' + token
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Không thể tải danh mục');
            return response.json();
        })
        .then(data => {
            if (data.status === "success" && data.data) {
                const categorySelect = document.getElementById("category_id");
                categorySelect.innerHTML = '<option value="">Chọn danh mục</option>';
                data.data.forEach(category => {
                    const option = document.createElement("option");
                    option.value = category.id;
                    option.textContent = category.name;
                    categorySelect.appendChild(option);
                });
            } else {
                throw new Error(data.message || "Không thể tải danh mục");
            }
        })
        .catch(error => {
            console.error("Lỗi tải danh mục:", error);
            document.getElementById("category_id").innerHTML = '<option value="">Không thể tải danh mục</option>';
        });
    }

    document.getElementById("add-product-form").addEventListener("submit", function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        // Debug dữ liệu gửi đi
        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }

        // Kiểm tra dữ liệu trước khi gửi
        if (!formData.get("name")) return showError("Tên sản phẩm không được để trống");
        if (!formData.get("description")) return showError("Mô tả không được để trống");
        if (!formData.get("price") || parseFloat(formData.get("price")) <= 0) return showError("Giá sản phẩm phải lớn hơn 0");
        if (!formData.get("category_id")) return showError("Vui lòng chọn danh mục");
        if (!formData.get("image")) return showError("Vui lòng chọn ảnh sản phẩm");

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/webbanhang/api/product', true);
        xhr.setRequestHeader('Authorization', 'Bearer ' + token);

        xhr.onload = function() {
            console.log("Response status:", xhr.status);
            console.log("Response text:", xhr.responseText);
            if (xhr.status >= 200 && xhr.status < 300) {
                const data = JSON.parse(xhr.responseText);
                if (data.status === "success") {
                    alert("Thêm sản phẩm thành công!");
                    window.location.href = "/webbanhang/Product/list";
                } else {
                    showError(data.message || "Thêm sản phẩm thất bại");
                }
            } else {
                const data = JSON.parse(xhr.responseText);
                showError(`Lỗi: ${data.message || 'Unknown error'}, Chi tiết: ${JSON.stringify(data.errors || [])}`);
            }
        };

        xhr.onerror = function() {
            showError("Lỗi mạng khi thêm sản phẩm");
        };

        xhr.send(formData);
    });

    function showError(message) {
        const errorMessage = document.getElementById("error-message");
        errorMessage.textContent = message;
        errorMessage.classList.remove("d-none");
        setTimeout(() => errorMessage.classList.add("d-none"), 5000);
    }
});
</script>