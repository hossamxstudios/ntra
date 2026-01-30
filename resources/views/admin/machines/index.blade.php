<!DOCTYPE html>
@include('admin.main.html')
<head>
    <title>أجهزة الخدمة - NTRA</title>
    @include('admin.main.meta')
</head>
<body>
    <div class="wrapper">
        @include('admin.main.topbar')
        @include('admin.main.sidebar')
        <div class="content-page">
            <div class="container-fluid">
                @include('admin.machines.header')
                @include('admin.machines.search-bar')
                @include('admin.machines.table')
            </div>
        </div>
    </div>
    @can('machines.create')
        @include('admin.machines.add-modal')
    @endcan
    @can('machines.edit')
        @include('admin.machines.edit-modal')
    @endcan
    @can('machines.delete')
        @include('admin.machines.delete-modal')
    @endcan
    @include('admin.machines.scripts')
    @include('admin.main.scripts')
</body>
</html>
