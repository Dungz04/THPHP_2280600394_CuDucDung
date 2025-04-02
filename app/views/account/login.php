<?php include 'app/views/shares/header.php'; ?>
<link rel="stylesheet" href="/webbanhang/styles.css">

<section class="vh-100 d-flex align-items-center justify-content-center bg-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card bg-glass text-white shadow-lg border-0 p-4">
                    <div class="card-body">
                        <h2 class="fw-bold text-uppercase text-center mb-3">Đăng nhập</h2>
                        <p class="text-center text-light mb-4">Vui lòng nhập thông tin đăng nhập của bạn.</p>

                        <form id="login-form">
                            <!-- Tên đăng nhập -->
                            <div class="mb-3 position-relative">
                                <label class="form-label">Tên đăng nhập:</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" name="username" class="form-control" placeholder="Nhập tên đăng nhập" required>
                                </div>
                            </div>

                            <!-- Mật khẩu -->
                            <div class="mb-3 position-relative">
                                <label class="form-label">Mật khẩu:</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
                                </div>
                            </div>

                            <!-- Quên mật khẩu -->
                            <div class="text-end">
                                <a href="#" class="text-light small">Quên mật khẩu?</a>
                            </div>

                            <!-- Nút đăng nhập -->
                            <button class="btn btn-primary w-100 py-2 mt-3" type="submit">Đăng nhập</button>

                            <!-- Mạng xã hội -->
                            <div class="d-flex justify-content-center mt-4">
                                <a href="#" class="btn btn-outline-light mx-2"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="btn btn-outline-light mx-2"><i class="fab fa-google"></i></a>
                                <a href="#" class="btn btn-outline-light mx-2"><i class="fab fa-twitter"></i></a>
                            </div>

                            <!-- Chuyển hướng đăng ký -->
                            <div class="text-center mt-3">
                                <p class="mb-0">Bạn chưa có tài khoản? <a href="/webbanhang/account/register" class="text-light fw-bold">Đăng ký</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'app/views/shares/footer.php'; ?>

<script>
    document.getElementById('login-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        const jsonData = {};
        formData.forEach((value, key) => {
            jsonData[key] = value;
        });
        jsonData['api'] = true;

        fetch('/webbanhang/account/checkLogin', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(jsonData)
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('Server error: ' + response.status);
                }
                return response.text();
            })
            .then(text => {
                console.log('Raw response:', text);
                const data = JSON.parse(text);
                if (data.token) {
                    localStorage.setItem('jwtToken', data.token);
                    location.href = '/webbanhang/product';
                } else {
                    alert('Đăng nhập thất bại: ' + (data.message || 'Lỗi không xác định'));
                }
            })
            .catch(error => {
                console.error('Lỗi:', error);
                alert('Đã xảy ra lỗi khi đăng nhập');
            });
    });
</script>