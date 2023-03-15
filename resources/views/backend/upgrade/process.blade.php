@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active">Updating the engine to the latest version</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 border-0">
                    <h6 class="m-0 font-weight-bold text-primary">Music Engine Update Wizard</h6>
                </div>
                <div class="card-body">
                    <div class="box-body">
                        <div>
                            <pre class="output-body" id="terminal-box"><div class="pre-update backup item text-danger">Do not close this window until the upgrade progress has been done. This could take 5 minutes or more to finish.</div></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function preUpdateBackup() {
            $.ajax({
                url: '{{ route('backend.help.terminal.artisan.post') }}',
                method: 'post',
                data: {
                    c: 'backup:run',
                },
                beforeSend: function(){
                    $('#terminal-box').append('<div class="pre-update backup item text-loading">Backup your main files and database</div>');
                    $('#terminal-box')
                        .append('<div class="item"><small class="label label-default"> > artisan backup:run<\/small><\/div>');
                },
                success: function (response) {
                    $('#terminal-box').append('<div class="item">'+response+'<\/div>');
                    $(".output-body").animate({ scrollTop: $('.output-body').prop("scrollHeight")}, 1000);
                    $('.pre-update.backup').removeClass('text-loading');
                    preUpdateBackupList();
                },
                error: function () {
                    $('#terminal-box').append('<div class="text-danger">Backup failed.</div>');
                }
            });
        };

        function preUpdateBackupList() {
            $.ajax({
                url: '{{ route('backend.help.terminal.artisan.post') }}',
                method: 'post',
                data: {
                    c: 'backup:list',
                },
                beforeSend: function(){
                    $('#terminal-box').append('<div class="pre-update backup item">Listing the backup</div>');
                    $('#terminal-box')
                        .append('<div class="item"><small class="label label-default"> > artisan backup:list<\/small><\/div>');
                },
                success: function (response) {
                    $('#terminal-box').append('<div class="item">'+response+'<\/div>');
                    $('#terminal-box').append('<div class="pre-update download item text-loading">Download the patch and get your site up to date</div>');
                    $(".output-body").animate({ scrollTop: $('.output-body').prop("scrollHeight")}, 1000);
                    downloadAndUpgrade();
                },
            });
        };

        function downloadAndUpgrade() {
            $.ajax({
                url: '{{ route('backend.help.terminal.artisan.post') }}',
                method: 'post',
                data: {
                    c: 'upgrade:latest',
                },
                beforeSend: function(){
                    $('#terminal-box')
                        .append('<div class="item"><small class="label label-default"> > artisan update:latest<\/small><\/div>');
                },
                success: function (response) {
                    $('.pre-update.download').removeClass('text-loading');
                    $('#terminal-box').append('<div class="item">'+response+'<\/div>');
                    $('#terminal-box').append('<div class="post-update backup item">Awesome. Upgrade completed successfully</div>');
                    $(".output-body").animate({ scrollTop: $('.output-body').prop("scrollHeight")}, 1000);
                },
                error: function () {
                    $('#terminal-box').append('<div class="text-danger">Update failed.</div>');
                }
            });
        };

        $(document).ready(function () {
            setTimeout(function () {
                preUpdateBackup();
            }, 2000)
        });
    </script>
@endsection