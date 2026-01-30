<!DOCTYPE html>
@include('admin.main.html')
<head>
    <title>فحوصات IMEI - NTRA</title>
    @include('admin.main.meta')
</head>
<body>
    <div class="wrapper">
        @include('admin.main.topbar')
        @include('admin.main.sidebar')
        <div class="content-page">
            <div class="container-fluid">
                @include('admin.imei-checks.header')
                @include('admin.imei-checks.search-bar')
                @include('admin.imei-checks.table')
            </div>
        </div>
    </div>
    @can('imei-checks.delete')
        @include('admin.imei-checks.delete-modal')
    @endcan
    @include('admin.imei-checks.scripts')
    @include('admin.main.scripts')
</body>
</html>
