<!DOCTYPE html>
@include('front.main.html')
<head>
    <title>تسجيل الجهاز - NTRA</title>
    @include('front.main.meta')
    @include('front.imei.partials.styles')
    <script src="{{ asset('js/webfxscan-sdk.js') }}"></script>
    <script src="{{ asset('js/passport-scanner.js') }}"></script>
</head>
<body>
    <div class="kiosk-page">
        @include('front.imei.partials.header')

        <!-- Main Content -->
        <main class="py-3 kiosk-content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-10 col-12">
                        <div class="shadow-lg card wizard-card">
                            <div class="card-body">
                                @include('front.imei.partials.progress-steps')

                                <form action="{{ route('imei.register.submit', ['device' => $device->id]) }}" method="POST" id="registrationForm" enctype="multipart/form-data">
                                    @csrf

                                    @include('front.imei.partials.step1-device')
                                    @include('front.imei.partials.step2-photo')
                                    @include('front.imei.partials.step3-passport')
                                    @include('front.imei.partials.step4-arrival')
                                    @include('front.imei.partials.step5-boarding')
                                    @include('front.imei.partials.step6-recap')

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        @include('front.imei.partials.footer')
    </div>

    @include('front.main.scripts')

    <!-- IMEI Registration Scripts -->
    <script src="{{ asset('js/imei/wizard.js') }}"></script>
    <script src="{{ asset('js/imei/validation.js') }}"></script>
    <script src="{{ asset('js/imei/camera.js') }}"></script>
    <script src="{{ asset('js/imei/scanner.js') }}"></script>
    <script src="{{ asset('js/imei/recap.js') }}"></script>
    <script src="{{ asset('js/imei/debug.js') }}"></script>
    <script src="{{ asset('js/imei/init.js') }}"></script>

    @include('front.imei.partials.error-modal')
    @include('front.imei.partials.debug-panel')
</body>
</html>
