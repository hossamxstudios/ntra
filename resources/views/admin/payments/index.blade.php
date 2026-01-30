<!DOCTYPE html>
@include('admin.main.html')
<head>
    <title>المدفوعات - NTRA</title>
    @include('admin.main.meta')
</head>
<body>
    <div class="wrapper">
        @include('admin.main.topbar')
        @include('admin.main.sidebar')
        <div class="content-page">
            <div class="container-fluid">
                @include('admin.payments.header')
                @include('admin.payments.search-bar')
                @include('admin.payments.table')
            </div>
        </div>
    </div>
    @include('admin.payments.scripts')
    @include('admin.main.scripts')
</body>
</html>
