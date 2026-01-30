<!DOCTYPE html>
@include('admin.main.html')
<head>
    <title>الأجهزة المحمولة - NTRA</title>
    @include('admin.main.meta')
</head>
<body>
    <div class="wrapper">
        @include('admin.main.topbar')
        @include('admin.main.sidebar')
        <div class="content-page">
            <div class="container-fluid">
                @include('admin.mobile-devices.header')
                @include('admin.mobile-devices.search-bar')
                @include('admin.mobile-devices.table')
            </div>
        </div>
    </div>
    @can('mobile-devices.create')
        @include('admin.mobile-devices.add-modal')
    @endcan
    @can('mobile-devices.edit')
        @include('admin.mobile-devices.edit-modal')
    @endcan
    @can('mobile-devices.delete')
        @include('admin.mobile-devices.delete-modal')
    @endcan
    @include('admin.mobile-devices.scripts')
    @include('admin.main.scripts')
</body>
</html>
