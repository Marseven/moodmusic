<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Music Engine - Control Panel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Custom fonts for this template-->
    <link href="{{ asset('backend/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('backend/vendor/fontawesome-free/css/brands.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('backend/vendor/fileinput/css/fileinput.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/vendor/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/vendor/jqueryui/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/vendor/fronteed/skins/all.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/vendor/jsoneditor/jsoneditor.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/vendor/nestable/jquery-nestable.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/vendor/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet">

    @if(config('settings.admin_dark_mode'))
        <link href="{{ asset('backend/css/style_dark.css') }}" rel="stylesheet">
    @else
        <link href="{{ asset('backend/css/style.css') }}" rel="stylesheet">
    @endif
    <!-- Custom style for both light and dark theme -->
    <link href="{{ asset('backend/css/custom.css?version=' . env('APP_VERSION')) }}" rel="stylesheet">
    <script>
        var darkMode = {{ config('settings.admin_dark_mode') ? true : false }};
        var api_tester_handle = '{{ route('backend.api-tester-handle') }}';
        var scheduling_run_url = '{{ route('backend.scheduling-run') }}';
        var admin_path = '{{ route('backend.dashboard', [], false) }}';
    </script>
</head>
<body id="page-top">
<!-- Page Wrapper -->
<div id="wrapper">
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion @if(isset($_COOKIE['sidebar']) &&  $_COOKIE['sidebar'] == 'small') toggled @endif " id="accordionSidebar">
        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('backend.dashboard') }}">
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-music"></i>
            </div>
            <div class="sidebar-brand-text mx-2">Music Engine</div>
        </a>
        <hr class="sidebar-divider my-0">
        @include('backend.commons.sidebar-menu', ['name' => 'Dashboard', 'icon' => 'fa-tachometer-alt', 'permission' => 'admin_access', 'route' => 'backend.dashboard'])
        @if(\App\Models\Role::getValue('admin_settings'))
            <hr class="sidebar-divider">
        @endif
        @include('backend.commons.sidebar-menu', ['name' => 'Settings', 'icon' => 'fa-cog', 'permission' => 'admin_settings', 'route' => 'backend.settings'])
        @include('backend.commons.sidebar-menu', ['name' => 'Email templates', 'icon' => 'fa-mail-bulk', 'permission' => 'admin_email', 'route' => 'backend.email'])
        @include('backend.commons.sidebar-menu', ['name' => 'SEO meta tags', 'icon' => 'fa-link', 'permission' => 'admin_metatags', 'route' => 'backend.metatags'])

        @if(\App\Models\Role::getValue('admin_settings') || \App\Models\Role::getValue('admin_email'))
            <hr class="sidebar-divider">
        @endif

        @if(\App\Models\Role::getValue('admin_posts'))
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBlog" aria-expanded="false" aria-controls="collapseBlog">
                <i class="fas fa-fw fa-blog"></i>
                <span>Blog</span>
            </a>
            <div id="collapseBlog" class="collapse" aria-labelledby="headingBlog" data-parent="#accordionSidebar">
                <div class="@if(config('settings.admin_dark_mode')) bg-dark @else bg-white @endif py-2 collapse-inner rounded">
                    @include('backend.commons.sidebar-sub-menu', ['name' => 'Categories', 'icon' => 'fa-tasks', 'permission' => 'admin_categories', 'route' => 'backend.categories'])
                    @include('backend.commons.sidebar-sub-menu', ['name' => 'Add news article', 'icon' => 'fa-newspaper', 'permission' => 'admin_posts', 'route' => 'backend.posts.add'])
                    @include('backend.commons.sidebar-sub-menu', ['name' => 'Edit news article', 'icon' => 'fa-edit', 'permission' => 'admin_posts', 'route' => 'backend.posts'])

                </div>
            </div>
        </li>
        @endif
        <hr class="sidebar-divider">
        @include('backend.commons.sidebar-menu', ['name' => 'Manage genres', 'icon' => 'fa-tags', 'permission' => 'admin_genres', 'route' => 'backend.genres'])
        @include('backend.commons.sidebar-menu', ['name' => 'Manage moods', 'icon' => 'fa-smile', 'permission' => 'admin_moods', 'route' => 'backend.moods'])
        <hr class="sidebar-divider">
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMusic" aria-expanded="false" aria-controls="collapseMusic">
                <i class="fas fa-fw fa-play"></i>
                <span>Music</span>
            </a>
            <div id="collapseMusic" class="collapse" aria-labelledby="headingMusic" data-parent="#accordionSidebar">
                <div class="@if(config('settings.admin_dark_mode')) bg-dark @else bg-white @endif py-2 collapse-inner rounded">
                    @include('backend.commons.sidebar-sub-menu', ['name' => 'Artist Requests', 'icon' => 'fa-theater-masks', 'permission' => 'admin_artist_claim', 'route' => 'backend.artist.access'])
                    @include('backend.commons.sidebar-sub-menu', ['name' => 'Artists', 'icon' => 'fa-microphone', 'permission' => 'admin_artists', 'route' => 'backend.artists'])
                    @include('backend.commons.sidebar-sub-menu', ['name' => 'Albums', 'icon' => 'fa-compact-disc', 'permission' => 'admin_albums', 'route' => 'backend.albums'])
                    @include('backend.commons.sidebar-sub-menu', ['name' => 'Songs', 'icon' => 'fa-music', 'permission' => 'admin_songs', 'route' => 'backend.songs'])
                    @include('backend.commons.sidebar-sub-menu', ['name' => 'Playlists', 'icon' => 'fa-stream', 'permission' => 'admin_playlists', 'route' => 'backend.playlists'])
                    @include('backend.commons.sidebar-sub-menu', ['name' => 'Channels', 'icon' => 'fa-grip-horizontal', 'permission' => 'admin_channels', 'route' => 'backend.channels.overview'])
                    @include('backend.commons.sidebar-sub-menu', ['name' => 'Slide Show', 'icon' => 'fa-clone', 'permission' => 'admin_slideshow', 'route' => 'backend.slideshow.overview'])
                    @include('backend.commons.sidebar-sub-menu', ['name' => 'Bulk upload', 'icon' => 'fa-upload', 'permission' => 'admin_songs', 'route' => 'backend.bulk'])
                    @if(env('IMPORT_MUSIC_MODULE') == 'true')
                        @include('backend.commons.sidebar-sub-menu', ['name' => 'Import Music', 'icon' => 'fa-file-import', 'permission' => 'admin_songs', 'route' => 'backend.import'])
                    @endif
                </div>
            </div>
        </li>
        @if(env('VIDEO_MODULE') == 'true')
            <hr class="sidebar-divider">
            @if(\App\Models\Role::getValue('admin_songs'))
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseVideo" aria-expanded="false" aria-controls="collapseVideo">
                        <i class="fas fa-video"></i>
                        <span>Video</span>
                    </a>
                    <div id="collapseVideo" class="collapse" aria-labelledby="headingRadio" data-parent="#accordionSidebar">
                        <div class="@if(config('settings.admin_dark_mode')) bg-dark @else bg-white @endif py-2 collapse-inner rounded">
                            @include('backend.commons.sidebar-sub-menu', ['name' => 'Add New Video', 'icon' => 'fa-radiation-alt', 'permission' => 'admin_songs', 'route' => 'backend.videos.add'])
                            @include('backend.commons.sidebar-sub-menu', ['name' => 'Edit Videos', 'icon' => 'fa-satellite-dish', 'permission' => 'admin_songs', 'route' => 'backend.videos.overview'])
                        </div>
                    </div>
                </li>
            @endif
        @endif
        <hr class="sidebar-divider">
        @if(\App\Models\Role::getValue('admin_radio'))
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseRadio" aria-expanded="false" aria-controls="collapseRadio">
                    <i class="fas fa-broadcast-tower"></i>
                    <span>Radio</span>
                </a>
                <div id="collapseRadio" class="collapse" aria-labelledby="headingRadio" data-parent="#accordionSidebar">
                    <div class="@if(config('settings.admin_dark_mode')) bg-dark @else bg-white @endif py-2 collapse-inner rounded">
                        @include('backend.commons.sidebar-sub-menu', ['name' => 'Radio category', 'icon' => 'fa-radiation-alt', 'permission' => 'admin_radio', 'route' => 'backend.radios'])
                        @include('backend.commons.sidebar-sub-menu', ['name' => 'Edit stations', 'icon' => 'fa-satellite-dish', 'permission' => 'admin_radio', 'route' => 'backend.stations'])
                        @include('backend.commons.sidebar-sub-menu', ['name' => 'Countries', 'icon' => 'fa-flag', 'permission' => 'admin_radio', 'route' => 'backend.countries'])
                        @include('backend.commons.sidebar-sub-menu', ['name' => 'Region', 'icon' => 'fa-globe-americas', 'permission' => 'admin_radio', 'route' => 'backend.regions'])
                        @include('backend.commons.sidebar-sub-menu', ['name' => 'Cities', 'icon' => 'fa-city', 'permission' => 'admin_radio', 'route' => 'backend.cities'])
                        @include('backend.commons.sidebar-sub-menu', ['name' => 'Country\'s Languages', 'icon' => 'fa-globe-americas', 'permission' => 'admin_radio', 'route' => 'backend.country.languages'])
                    </div>
                </div>
            </li>
        @endif
        <hr class="sidebar-divider">
        @if(\App\Models\Role::getValue('admin_radio'))
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePodcast" aria-expanded="false" aria-controls="collapsePodcast">
                    <i class="fas fa-broadcast-tower"></i>
                    <span>Podcast</span>
                </a>
                <div id="collapsePodcast" class="collapse" aria-labelledby="headingPodcast" data-parent="#accordionSidebar">
                    <div class="@if(config('settings.admin_dark_mode')) bg-dark @else bg-white @endif py-2 collapse-inner rounded">
                        @include('backend.commons.sidebar-sub-menu', ['name' => 'Podcast category', 'icon' => 'fa-radiation-alt', 'permission' => 'admin_radio', 'route' => 'backend.podcast-categories'])
                        @include('backend.commons.sidebar-sub-menu', ['name' => 'Edit Podcast', 'icon' => 'fa-satellite-dish', 'permission' => 'admin_radio', 'route' => 'backend.podcasts'])
                        @include('backend.commons.sidebar-sub-menu', ['name' => 'Countries', 'icon' => 'fa-flag', 'permission' => 'admin_radio', 'route' => 'backend.countries'])
                        @include('backend.commons.sidebar-sub-menu', ['name' => 'Region', 'icon' => 'fa-globe-americas', 'permission' => 'admin_radio', 'route' => 'backend.regions'])
                        @include('backend.commons.sidebar-sub-menu', ['name' => 'Cities', 'icon' => 'fa-city', 'permission' => 'admin_radio', 'route' => 'backend.cities'])
                        @include('backend.commons.sidebar-sub-menu', ['name' => 'Country\'s Languages', 'icon' => 'fa-globe-americas', 'permission' => 'admin_radio', 'route' => 'backend.country.languages'])
                    </div>
                </div>
            </li>
        @endif
        @if(\App\Models\Role::getValue('admin_earnings'))
            <hr class="sidebar-divider">
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseEarnings" aria-expanded="false" aria-controls="collapseEarnings">
                    <i class="fas fa-fw fa-money-check-alt"></i>
                    <span>Earnings</span>
                </a>
                <div id="collapseEarnings" class="collapse" aria-labelledby="headingPlans" data-parent="#accordionSidebar">
                    <div class="@if(config('settings.admin_dark_mode')) bg-dark @else bg-white @endif py-2 collapse-inner rounded">
                        @include('backend.commons.sidebar-sub-menu', ['name' => 'Recent orders', 'icon' => 'fa-cart-arrow-down', 'permission' => 'admin_earnings', 'route' => 'backend.orders'])
                        @include('backend.commons.sidebar-sub-menu', ['name' => 'Payment requests', 'icon' => 'fa-university', 'permission' => 'admin_earnings', 'route' => 'backend.withdraws'])
                    </div>
                </div>
            </li>
        @endif
        @if(\App\Models\Role::getValue('admin_subscriptions'))
            <hr class="sidebar-divider">
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePlan" aria-expanded="false" aria-controls="collapsePlan">
                    <i class="fas fa-fw fa-money-check-alt"></i>
                    <span>Membership</span>
                </a>
                <div id="collapsePlan" class="collapse" aria-labelledby="headingPlans" data-parent="#accordionSidebar">
                    <div class="@if(config('settings.admin_dark_mode')) bg-dark @else bg-white @endif py-2 collapse-inner rounded">
                        @include('backend.commons.sidebar-sub-menu', ['name' => 'Plans', 'icon' => 'fa-star', 'permission' => 'admin_subscriptions', 'route' => 'backend.services'])
                        @include('backend.commons.sidebar-sub-menu', ['name' => 'Subscribers', 'icon' => 'fa-shopping-cart', 'permission' => 'admin_subscriptions', 'route' => 'backend.subscriptions'])
                        @include('backend.commons.sidebar-sub-menu', ['name' => 'Reports', 'icon' => 'fa-chart-line', 'permission' => 'admin_subscriptions', 'route' => 'backend.reports'])

                    </div>
                </div>
            </li>
        @endif
        <hr class="sidebar-divider">
        @if(\App\Models\Role::getValue('admin_users'))
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUser" aria-expanded="false" aria-controls="collapseUser">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Users</span>
                </a>
                <div id="collapseUser" class="collapse" aria-labelledby="headingBlog" data-parent="#accordionSidebar">
                    <div class="@if(config('settings.admin_dark_mode')) bg-dark @else bg-white @endif py-2 collapse-inner rounded">
                        @include('backend.commons.sidebar-sub-menu', ['name' => 'Edit users', 'icon' => 'fa-user', 'permission' => 'admin_users', 'route' => 'backend.users'])
                        @include('backend.commons.sidebar-sub-menu', ['name' => 'Setting up user profiles', 'icon' => 'fa-user', 'permission' => 'admin_users', 'route' => 'backend.users.add'])
                        @include('backend.commons.sidebar-sub-menu', ['name' => 'Configure user groups', 'icon' => 'fa-users-cog', 'permission' => 'admin_roles', 'route' => 'backend.roles'])
                    </div>
                </div>
            </li>
        @endif
        <hr class="sidebar-divider">
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="false" aria-controls="collapseUtilities">
                <i class="fas fa-fw fa-wrench"></i>
                <span>Utilities</span>
            </a>
            <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                <div class="@if(config('settings.admin_dark_mode')) bg-dark @else bg-white @endif py-2 collapse-inner rounded">
                    @if(\App\Models\Role::getValue('admin_earnings'))
                        @include('backend.commons.sidebar-sub-menu', ['name' => 'Coupons System', 'icon' => 'fa-gift', 'permission' => 'admin_earnings', 'route' => 'backend.coupons'])
                    @endif
                    @include('backend.commons.sidebar-sub-menu', ['name' => 'Advertising materials', 'icon' => 'fa-ad', 'permission' => 'admin_banners', 'route' => 'backend.banners'])
                    @include('backend.commons.sidebar-sub-menu', ['name' => 'Languages', 'icon' => 'fa-language', 'permission' => 'admin_languages', 'route' => 'backend.languages'])
                    @include('backend.commons.sidebar-sub-menu', ['name' => 'Pages', 'icon' => 'fa-pager', 'permission' => 'admin_pages', 'route' => 'backend.pages'])
                    @include('backend.commons.sidebar-sub-menu', ['name' => 'Comments', 'icon' => 'fa-comment', 'permission' => 'admin_comments', 'route' => 'backend.comments'])
                    @include('backend.commons.sidebar-sub-menu', ['name' => 'Media Manager', 'icon' => 'fa-hdd', 'permission' => 'admin_media_manager', 'route' => 'backend.media-index'])
                    @include('backend.commons.sidebar-sub-menu', ['name' => 'Sitemap', 'icon' => 'fa-sitemap', 'permission' => 'admin_sitemap', 'route' => 'backend.sitemap'])
                    @include('backend.commons.sidebar-sub-menu', ['name' => 'Backup', 'icon' => 'fa-clock', 'permission' => 'admin_backup', 'route' => 'backend.backup-list'])
                    @include('backend.commons.sidebar-sub-menu', ['name' => 'API tester', 'icon' => 'fa-globe', 'permission' => 'admin_api_tester', 'route' => 'backend.api-tester-index'])
                    @include('backend.commons.sidebar-sub-menu', ['name' => 'System logs', 'icon' => 'fa-file-alt', 'permission' => 'admin_system_logs', 'route' => 'backend.log-viewer-index'])
                    @include('backend.commons.sidebar-sub-menu', ['name' => 'Scheduled task', 'icon' => 'fa-calendar', 'permission' => 'admin_scheduled', 'route' => 'backend.scheduling-index'])
                    @include('backend.commons.sidebar-sub-menu', ['name' => 'Terminal', 'icon' => 'fa-terminal', 'permission' => 'admin_terminal', 'route' => 'backend.help.terminal.artisan'])
                </div>
            </div>
        </li>
        <hr class="sidebar-divider d-none d-md-block mb-5">
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light @if(! config('settings.admin_dark_mode')) bg-white @endif topbar mb-4 static-top shadow">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <!-- Topbar Search -->
                <!-- <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form> -->

                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">

                    <!-- Nav Item - Search Dropdown (Visible Only XS)
                    <li class="nav-item dropdown no-arrow d-sm-none">
                        <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-search fa-fw"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                            <form class="form-inline mr-auto w-100 navbar-search">
                                <div class="input-group">
                                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="button">
                                            <i class="fas fa-search fa-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </li>
                    -->
                    <!-- Nav Item - Messages -->
                    <li class="nav-item dropdown no-arrow mx-1">
                        <a class="nav-link dropdown-toggle" href="#" id="postsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-exclamation-circle"></i>
                            <!-- Counter - Messages -->
                            <span class="badge badge-danger badge-counter">{{ \App\Models\Report::withoutGlobalScopes()->where('reportable_type', 'App\\Models\\Song')->orWhere('reportable_type', 'App\\Models\\Podcast')->orWhere('reportable_type', 'App\\Models\\Episode')->count() }}</span>
                        </a>
                        <!-- Dropdown - Messages -->
                        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="postsDropdown">
                            <h6 class="dropdown-header">
                                Report are waiting for moderation
                            </h6>
                            @foreach(\App\Models\Report::withoutGlobalScopes()->where('reportable_type', 'App\\Models\\Song')->orWhere('reportable_type', 'App\\Models\\Podcast')->orWhere('reportable_type', 'App\\Models\\Episode')->latest()->limit(5)->get() as $index => $report)
                                @if(isset($report->message) && isset($report->user) && isset($report->user->id))
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('backend.problems') }}">
                                        <div class="font-weight-bold">
                                            <div class="text-truncate"><span class="badge badge-pill badge-warning">{{ str_replace('App\\Models\\', '', $report->reportable_type) }}</span> {{ $report->message }}</div>
                                            <div class="small text-gray-500">{{ $report->user->name }} · {{ timeElapsedString( $report->created_at) }}</div>
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                            <a class="dropdown-item text-center small text-gray-500" href="{{ route('backend.problems') }}">Manage Report</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown no-arrow mx-1">
                        <a class="nav-link dropdown-toggle" href="#" id="postsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-newspaper fa-fw"></i>
                            <!-- Counter - Messages -->
                            <span class="badge badge-danger badge-counter">{{ \App\Models\Post::withoutGlobalScopes()->where('approved', 0)->count() }}</span>
                        </a>
                        <!-- Dropdown - Messages -->
                        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="postsDropdown">
                            <h6 class="dropdown-header">
                                Posts are waiting for moderation
                            </h6>
                            @foreach(\App\Models\Post::withoutGlobalScopes()->where('approved', 0)->latest()->limit(5)->get() as $index => $post)
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('backend.posts.edit', ['id' => $post->id]) }}">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="{{ $post->user->artwork_url }}" alt="">
                                    </div>
                                    <div class="font-weight-bold">
                                        <div class="text-truncate">{{ $post->title }}</div>
                                        <div class="small text-gray-500">{{ $post->user->name }} · {{ timeElapsedString( $post->created_at) }}</div>
                                    </div>
                                </a>
                            @endforeach
                            <a class="dropdown-item text-center small text-gray-500" href="{{ route('backend.posts') }}?not_approved=true">Edit Article</a>
                        </div>
                    </li>
                    @if(\App\Models\Role::getValue('admin_comments'))
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="commentsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-comment fa-fw"></i>
                                <span class="badge badge-danger badge-counter">{{ DB::table('comments')->where('approved', '=', 0)->count() }}</span>
                            </a>
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="commentsDropdown">
                                <h6 class="dropdown-header">
                                    Comments awaiting for moderation
                                </h6>
                                @foreach(\App\Models\Comment::withoutGlobalScopes()->where('approved', 0)->latest()->limit(5)->get() as $index => $comment)
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('backend.comments.edit', ['id' => $comment->id]) }}">
                                        <div class="dropdown-list-image mr-3">
                                            <img class="rounded-circle" src="{{ $comment->user->artwork_url }}" alt="">
                                        </div>
                                        <div class="font-weight-bold">
                                            <div class="text-truncate">{{ $comment->content }}</div>
                                            <div class="small text-gray-500">{{ $comment->user->name }} · {{ timeElapsedString( $comment->created_at) }}</div>
                                        </div>
                                    </a>
                                @endforeach
                                <a class="dropdown-item text-center small text-gray-500" href="{{ route('backend.comments') }}">Comments Manager</a>
                            </div>
                        </li>
                    @endif
                    @if(\App\Models\Role::getValue('admin_songs'))
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="commentsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-music"></i>
                                <span class="badge badge-danger badge-counter">{{ DB::table('songs')->where('approved', 0)->count() }}</span>
                            </a>
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="commentsDropdown">
                                <h6 class="dropdown-header">
                                    Songs awaiting for moderation
                                </h6>
                                @foreach(\App\Models\Song::withoutGlobalScopes()->where('approved', 0)->limit(5)->get() as $index => $song)
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('backend.songs.edit', ['id' => $song->id]) }}">
                                        <div class="dropdown-list-image mr-3">
                                            <img class="rounded-circle" src="{{ $song->artwork_url }}" alt="">
                                        </div>
                                        <div class="font-weight-bold">
                                            <div class="text-truncate">{!! $song->title !!}</div>
                                            <div class="small text-gray-500">@foreach($song->artists as $artist){!! $artist->name !!}@if(!$loop->last), @endif @endforeach · {{ timeElapsedString( $song->created_at) }}</div>
                                        </div>
                                    </a>
                                @endforeach
                                <a class="dropdown-item text-center small text-gray-500" href="{{ route('backend.songs') }}?not_approved=true">Songs Manager</a>
                            </div>
                        </li>
                    @endif
                    @if(\App\Models\Role::getValue('admin_albums'))
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="commentsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-compact-disc fa-fw"></i>
                                <span class="badge badge-danger badge-counter">{{ DB::table('albums')->where('approved', 0)->count() }}</span>
                            </a>
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="commentsDropdown">
                                <h6 class="dropdown-header">
                                    Albums awaiting for moderation
                                </h6>
                                @foreach(\App\Models\Album::withoutGlobalScopes()->where('approved', 0)->limit(5)->get() as $index => $album)
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('backend.albums.edit', ['id' => $album->id]) }}">
                                        <div class="dropdown-list-image mr-3">
                                            <img class="rounded-circle" src="{{ $album->artwork_url }}" alt="">
                                        </div>
                                        <div class="font-weight-bold">
                                            <div class="text-truncate">{!! $album->title !!}</div>
                                            <div class="small text-gray-500">@foreach($album->artists as $artist){!! $artist->name !!}@if(!$loop->last), @endif @endforeach · {{ timeElapsedString( $album->created_at) }}</div>
                                        </div>
                                    </a>
                                @endforeach
                                <a class="dropdown-item text-center small text-gray-500" href="{{ route('backend.albums') }}?not_approved=true">Albums Manager</a>
                            </div>
                        </li>
                    @endif
                    @if(\App\Models\Role::getValue('admin_artists'))
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="commentsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-microphone-alt"></i>
                                <span class="badge badge-danger badge-counter">{{ DB::table('artist_requests')->where('approved', 0)->count() }}</span>
                            </a>
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="commentsDropdown">
                                <h6 class="dropdown-header">
                                    Artist requests awaiting for moderation
                                </h6>
                                @foreach(\App\Models\ArtistRequest::withoutGlobalScopes()->where('approved', 0)->limit(5)->get() as $index => $request)
                                    @if(isset($request->artist) || isset($request->user))
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('backend.artist.access.edit', ['id' => $request->id]) }}">
                                        <div class="dropdown-list-image mr-3">
                                            <img class="rounded-circle" src="{{ isset($request->artist->id) ? $request->artist->artwork_url : $request->user->artwork_url }}" alt="">
                                        </div>
                                        <div class="font-weight-bold">
                                            <div class="text-truncate">{{ $request->artist_name }}</div>
                                            <div class="small text-gray-500">{{ $request->message }}</div>
                                            <div class="small text-gray-500">{{ $request->user->name }} · {{ timeElapsedString( $request->created_at) }}</div>
                                        </div>
                                    </a>
                                    @endif
                                @endforeach
                                <a class="dropdown-item text-center small text-gray-500" href="{{ route('backend.artist.access') }}">Artist requests Manager</a>
                            </div>
                        </li>
                    @endif
                    <div class="topbar-divider d-none d-sm-block"></div>
                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->name }}</span>
                            <img class="img-profile rounded-circle" src="{{ auth()->user()->artwork_url  }}">
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="{{ route('backend.profile') }}">
                                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                Profile
                            </a>
                            @if(\App\Models\Role::getValue('admin_settings'))
                                <a class="dropdown-item" href="{{ route('backend.settings') }}">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                            @endif
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('backend.logout') }}" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                        </div>
                    </li>

                </ul>

            </nav>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid" id="pjax-container">
                @if (session('status') && session('status') == 'success')
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @elseif (session('status') && session('status') == 'failed')
                    <div class="alert alert-danger">
                        {{ session('message') }}
                    </div>
                @endif
                @if($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger">
                            {{ $error }}
                        </div>
                @endforeach
            @endif
            @yield('content')
            <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; <a href="https://ninacoder.info" target="_blank">ninacoder.info</a> {{ now()->year }}</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="{{ route('backend.logout') }}">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->

    <script src="{{ asset('backend/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/jqueryui/jquery-ui.js') }}"></script>

    <script src="{{ asset('backend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('backend/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Page level plugins -->
    <script src="{{ asset('backend/vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/fileinput/js/fileinput.js') }}"></script>
    <script src="{{ asset('backend/js/jquery-file-upload.js?version=' . env('APP_VERSION')) }}"></script>

    <script src="{{ asset('backend/vendor/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/malsup/jquery.form.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/tinymce/js/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/fronteed/icheck.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/jquery-pjax/jquery.pjax.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/jsoneditor/jsoneditor.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/nestable/jquery.nestable.js') }}"></script>
    <script src="{{ asset('backend/vendor/datetimepicker/jquery.datetimepicker.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/bootbox/bootboxjs.min.js') }}"></script>
    @yield('script')
    <!-- Custom scripts for all pages-->
    <script src="{{ asset('backend/js/admin.js?version=' . env('APP_VERSION')) }}"></script>
</body>
</html>