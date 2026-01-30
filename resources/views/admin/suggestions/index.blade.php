<!DOCTYPE html>
@include('admin.main.html')
<head>
    <title>الاقتراحات - NTRA</title>
    @include('admin.main.meta')
</head>
<body>
    <div class="wrapper">
        @include('admin.main.topbar')
        @include('admin.main.sidebar')
        <div class="content-page">
            <div class="container-fluid">
                @include('admin.suggestions.header')
                @include('admin.suggestions.search-bar')
                @include('admin.suggestions.table')
            </div>
        </div>
    </div>
    @can('suggestions.delete')
        @include('admin.suggestions.delete-modal')
    @endcan
    @include('admin.suggestions.scripts')
    @include('admin.main.scripts')
</body>
</html>
