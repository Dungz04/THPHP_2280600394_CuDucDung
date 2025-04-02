    <!-- Banner Carousel -->
    <div id="bannerCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">

        <!-- Chỉ mục (Indicators) -->
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="2"></button>
        </div>

        <!-- Slide ảnh -->
        <div class="carousel-inner">
            <div class="carousel-item active" data-bs-interval="3000">
                <img src="/webbanhang/app/images/banner1.jpg" class="d-block w-100 banner-img" alt="banner 1">
            </div>
            <div class="carousel-item" data-bs-interval="3000">
                <img src="/webbanhang/app/images/banner2.jpg" class="d-block w-100 banner-img" alt="banner 2">
            </div>
            <div class="carousel-item" data-bs-interval="3000">
                <img src="/webbanhang/app/images/banner3.jpg" class="d-block w-100 banner-img" alt="banner 3">
            </div>
        </div>

        <!-- Nút điều hướng -->
        <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </button>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var myCarousel = new bootstrap.Carousel(document.querySelector("#bannerCarousel"), {
                interval: 3000, // 3 giây
                wrap: true
            });
        });
    </script>