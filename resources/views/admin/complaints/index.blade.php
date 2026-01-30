<!DOCTYPE html>
@include('admin.main.html')
<head>
    <title>الشكاوى - NTRA</title>
    @include('admin.main.meta')
</head>
<body>
    <div class="wrapper">
        @include('admin.main.topbar')
        @include('admin.main.sidebar')
        <div class="content-page">
            <div class="container-fluid">
                @include('admin.complaints.header')
                @include('admin.complaints.search-bar')
                @include('admin.complaints.table')
            </div>
        </div>
    </div>
    @can('complaints.delete')
        @include('admin.complaints.delete-modal')
    @endcan
    @can('complaints.edit')
        @include('admin.complaints.resolve-modal')
    @endcan
    @include('admin.complaints.scripts')
    @include('admin.main.scripts')
</body>
</html>
