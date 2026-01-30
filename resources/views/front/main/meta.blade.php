    <meta charset="utf-8">
    <title>الجهاز القومي لتنظيم الاتصالات</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    <script src="{{ asset('dashboard/assets/js/config.js') }}"></script>
    <link href="{{ asset('dashboard/assets/css/vendors.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('dashboard/assets/css/app.min.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('dashboard/assets/plugins/lucide/lucide.min.js') }}"></script>
    <style>
        /* Kiosk Layout Rules: 100vh, no scroll, no overflow */
        html, body {
            height: 100vh;
            max-height: 100vh;
            overflow: hidden;
            margin: 0;
            padding: 0;
        }
        .kiosk-page {
            display: flex;
            flex-direction: column;
            height: 100vh;
            max-height: 100vh;
            overflow: hidden;
        }
        .kiosk-header {
            flex-shrink: 0;
            height: 70px;
        }
        .kiosk-content {
            flex: 1;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .kiosk-footer {
            flex-shrink: 0;
            height: 50px;
        }
    </style>

