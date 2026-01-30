<!DOCTYPE html>
@include('front.main.html')
<head>
    <title>جهاز الاستعلام الإلكتروني - NTRA</title>
    @include('front.main.meta')
</head>
<style>
    body { background: linear-gradient(135deg, #f8fafc 0%, #eeeeee 100%); min-height: 100vh; }
    .kiosk-header { background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
    .kiosk-title { color: #1e3a5f; }
    .service-card {
        border-radius: 16px !important;
        transition: all 0.3s ease;
        cursor: pointer;
        min-height: 180px;
    }
    .service-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.12) !important;
    }
    .service-icon {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .service-icon svg { width: 40px; height: 40px; }
    .kiosk-footer { position: fixed; bottom: 0; left: 0; right: 0; background: #fff; }
</style>
<body>
    <!-- Header -->
    <header class="px-4 py-3 kiosk-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="gap-3 d-flex align-items-center">
                <img src="{{ asset('ntra.webp') }}" alt="NTRA" height="50">
                <h5 class="mb-0 fw-bold kiosk-title">الجهاز القومي لتنظيم الاتصالات</h5>
            </div>
            <div class="gap-3 d-flex align-items-center">
                <h5 class="mb-0 fw-bold kiosk-title">وزارة الاتصالات وتكنولوجيا المعلومات</h5>
                <img src="{{ asset('ministry.webp') }}" alt="Ministry" height="50">
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container py-1">
        <div class="mb-0 text-center">
            <div class="gap-5 d-flex justify-content-center align-items-center">
                <img src="{{ asset('ntra.webp') }}" alt="NTRA" height="250">
                <img src="{{ asset('ministry.webp') }}" alt="Ministry" height="150">
            </div>
            <h1 class="mb-2 display-5 fw-bold kiosk-title">جهاز الاستعلام الإلكتروني</h1>
            <p class="text-muted fs-5">اختر الخدمة المطلوبة</p>
        </div>

        <div class="row g-4 justify-content-center" style="max-width: 700px; margin: 0 auto;">
            <!-- تليفوني - الجمارك -->
            <div class="col-6">
                <a href="{{ route('imei.check') }}" class="text-decoration-none">
                    <div class="p-4 text-center border-0 shadow service-card card h-100 d-flex flex-column justify-content-center">
                        <div class="mx-auto mb-3 service-icon bg-success-subtle">
                            <i data-lucide="phone" class="text-success"></i>
                        </div>
                        <h5 class="mb-0 fw-semibold text-dark">تليفوني - الجمارك</h5>
                    </div>
                </a>
            </div>

            <!-- المقترحات -->
            <div class="col-6">
                <a href="#" class="text-decoration-none">
                    <div class="p-4 text-center border-0 shadow service-card card h-100 d-flex flex-column justify-content-center">
                        <div class="mx-auto mb-3 service-icon bg-primary-subtle">
                            <i data-lucide="lightbulb" class="text-primary"></i>
                        </div>
                        <h5 class="mb-0 fw-semibold text-dark">المقترحات</h5>
                    </div>
                </a>
            </div>

            <!-- الشكاوى الإلكترونية -->
            <div class="col-6">
                <a href="#" class="text-decoration-none">
                    <div class="p-4 text-center border-0 shadow service-card card h-100 d-flex flex-column justify-content-center">
                        <div class="mx-auto mb-3 service-icon bg-danger-subtle">
                            <i data-lucide="message-square-warning" class="text-danger"></i>
                        </div>
                        <h5 class="mb-0 fw-semibold text-dark">الشكاوى الإلكترونية</h5>
                    </div>
                </a>
            </div>

            <!-- المساعدة والدعم -->
            <div class="col-6">
                <a href="#" class="text-decoration-none">
                    <div class="p-4 text-center border-0 shadow service-card card h-100 d-flex flex-column justify-content-center">
                        <div class="mx-auto mb-3 service-icon bg-secondary-subtle">
                            <i data-lucide="help-circle" class="text-secondary"></i>
                        </div>
                        <h5 class="mb-0 fw-semibold text-dark">المساعدة والدعم</h5>
                    </div>
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-3 text-center border-top kiosk-footer">
        <p class="mb-0 text-muted">© {{ date('Y') }} الجهاز القومي لتنظيم الاتصالات - جميع الحقوق محفوظة</p>
    </footer>

    @include('front.main.scripts')
</body>
</html>
