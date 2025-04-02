<?php include 'app/views/shares/header.php'; ?>
<link rel="stylesheet" href="/webbanhang/styles.css">

<section class="vh-100 d-flex align-items-center justify-content-center bg-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card bg-glass text-white shadow-lg border-0 p-4">
                    <div class="card-body">
                        <h2 class="fw-bold text-uppercase text-center mb-3">Đăng ký</h2>
                        <p class="text-center text-light mb-4">Vui lòng nhập thông tin của bạn.</p>

                        <!-- Hiển thị lỗi -->
                        <?php if (isset($errors) && count($errors) > 0): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $err): ?>
                                        <li><?= htmlspecialchars($err) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="/webbanhang/account/save" method="post">
                            <!-- Username & Fullname -->
                            <div class="mb-3 row">
                                <div class="col-md-6">
                                    <label class="form-label">Tên đăng nhập</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" name="username" class="form-control" placeholder="Nhập tên đăng nhập" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Họ và tên</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                        <input type="text" name="fullname" class="form-control" placeholder="Nhập họ và tên" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Password & Confirm Password -->
                            <div class="mb-3 row">
                                <div class="col-md-6">
                                    <label class="form-label">Mật khẩu</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Xác nhận mật khẩu</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                        <input type="password" name="confirmpassword" class="form-control" placeholder="Nhập lại mật khẩu" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Nút đăng ký -->
                            <div class="text-center mt-3">
                                <button class="btn btn-primary w-100 py-2">Đăng ký</button>
                            </div>

                            <!-- Đã có tài khoản -->
                            <div class="text-center mt-3">
                                <p class="mb-0">Đã có tài khoản? <a href="/webbanhang/account/login" class="text-light fw-bold">Đăng nhập</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'app/views/shares/footer.php'; ?>
