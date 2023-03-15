@if(isset($error))
    <script type="text/javascript">
        try {
            var opener = window.opener;
            if (opener) {
                opener.Connect.thirdParty.error("{{ $provider }}");
                window.close();
            }
        } catch(e) {

        }

        setTimeout(function () {
            window.location.href = "{{ config('settings.deeplink_scheme', 'musicengine') }}://engine/login/failed";
        }, 1000);
    </script>
@else
    <script type="text/javascript">

        try {
            var opener = window.opener;
            if(opener) {
                opener.Connect.thirdParty.callback({!! json_encode($service) !!}, "{{ $provider }}");
                window.close();
            }
        } catch(e) {

        }

        window.onload = function(){
            setTimeout(function () {
                window.location.href = "{{ config('settings.deeplink_scheme', 'musicengine') }}://engine/login/success/{!! $token !!}";
            }, 1000);
        };
    </script>
@endif