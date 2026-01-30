<!DOCTYPE html>
@include('admin.main.html')
<head>
    <title>المسافرين - NTRA</title>
    @include('admin.main.meta')
</head>
<body>
    <div class="wrapper">
        @include('admin.main.topbar')
        @include('admin.main.sidebar')
        <div class="content-page">
            <div class="container-fluid">
                @include('admin.passengers.header')
                @include('admin.passengers.search-bar')
                @include('admin.passengers.table')
            </div>
        </div>
    </div>
    @can('passengers.create')
        @include('admin.passengers.add-modal')
    @endcan
    @can('passengers.edit')
        @include('admin.passengers.edit-modal')
    @endcan
    @can('passengers.delete')
        @include('admin.passengers.delete-modal')
    @endcan
    @include('admin.passengers.scripts')
    @include('admin.main.scripts')
</body>
</html>
