<!DOCTYPE html>
@include('front.main.html')
<head>
    <title>استعلام عن جهاز - NTRA</title>
    @include('front.main.meta')
</head>
<body>
    <!-- Header -->
    <header class="px-4 py-3 bg-white shadow-sm">
        <div class="d-flex justify-content-between align-items-center">
            <div class="gap-3 d-flex align-items-center">
                <img src="{{ asset('ntra.webp') }}" alt="NTRA" height="50">
                <h5 class="mb-0 fw-bold" style="color: #1e3a5f;">الجهاز القومي لتنظيم الاتصالات</h5>
            </div>
            <div class="gap-3 d-flex align-items-center">
                <h5 class="mb-0 fw-bold" style="color: #1e3a5f;">وزارة الاتصالات وتكنولوجيا المعلومات</h5>
                <img src="{{ asset('ministry.webp') }}" alt="Ministry" height="50">
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="py-5 align-items-center d-flex" style="min-height: calc(100vh - 150px);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7 col-sm-10">
                    <div class="shadow-lg card">
                        <div class="p-5 card-body">
                            <div class="mb-4 auth-brand">
                                <p class="mt-3 text-center text-dark fs-4">استعلام عن حالة الجهاز في منظومة تليفوني</p>
                            </div>

                            <div class="mb-4 text-center">
                                <div class="mx-auto mb-3 rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                    <i data-lucide="smartphone" class="text-primary" style="width: 50px; height: 50px;"></i>
                                </div>
                                <h4 class="mb-1 fw-bold">تليفوني - الجمارك</h4>
                                <p class="text-muted">أدخل رقم IMEI للتحقق من حالة الجهاز</p>
                            </div>

                            <form action="{{ route('imei.check.submit') }}" method="POST">
                                @csrf

                                <div class="mb-4">
                                    <label for="imei" class="mb-2 form-label fs-5 fw-semibold">رقم المعدة (IMEI) <span class="text-danger">*</span></label>
                                    <input type="text" class="py-3 form-control form-control-lg text-center fs-4 @error('imei') is-invalid @enderror" id="imei" name="imei" placeholder="أدخل 15 رقم" maxlength="15" inputmode="numeric" style="letter-spacing: 3px;" value="{{ old('imei') }}" required autofocus>
                                    @error('imei')
                                        <div class="invalid-feedback fs-6">{{ $message }}</div>
                                    @enderror
                                    <div class="p-3 mt-3 text-center bg-success-subtle rounded-3">
                                        <i data-lucide="scan" class="text-success me-2" style="width: 20px; height: 20px;"></i>
                                        <span class="text-success fw-medium">يمكنك استخدام قارئ الباركود لمسح رقم IMEI مباشرة</span>
                                    </div>
                                    <small class="mt-2 text-center text-muted d-block">أو اكتب *#06# على الهاتف لمعرفة رقم IMEI</small>
                                </div>
                                <div class="gap-2 d-flex">
                                        <button type="submit" class="py-2 btn btn-primary fw-semibold w-50">
                                            <i data-lucide="search" class="me-1"></i>
                                            استعلام
                                        </button>
                                        <a href="{{ route('welcome') }}" class="py-2 btn btn-light w-50">
                                            <i data-lucide="arrow-right" class="me-1"></i>
                                            العودة
                                        </a>
                                </div>
                            </form>

                        </div>
                    </div>

                    <p class="mt-4 mb-0 text-center text-muted">
                        &copy; {{ date('Y') }} الجهاز القومي لتنظيم الاتصالات - جميع الحقوق محفوظة
                    </p>
                </div>
            </div>
        </div>
    </div>

    @include('front.main.scripts')
    <script>
        lucide.createIcons();
        document.getElementById('imei').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
</body>
</html>
