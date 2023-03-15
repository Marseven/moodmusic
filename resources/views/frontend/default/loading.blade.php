<script id="loading-infoBox" type="text/x-tmpl">
    <div class="wrapper">
        <div class="wrapper-cell song">
            <div class="image"></div>
            <div class="text">
                <div class="text-line"> </div>
                <div class="text-line"></div>
                <div class="text-line"></div>
            </div>
        </div>
        <div class="wrapper-cell song">
            <div class="image"></div>
            <div class="text">
                <div class="text-line"> </div>
                <div class="text-line"></div>
                <div class="text-line"></div>
            </div>
        </div>
        <div class="wrapper-cell song">
            <div class="image"></div>
            <div class="text">
                <div class="text-line"> </div>
                <div class="text-line"></div>
                <div class="text-line"></div>
            </div>
        </div>
        <div class="wrapper-cell song">
            <div class="image"></div>
            <div class="text">
                <div class="text-line"> </div>
                <div class="text-line"></div>
                <div class="text-line"></div>
            </div>
        </div>
    </div>
</script>
<script id="loading-radio" type="text/x-tmpl">
    <div class="wrapper">
        {% for (var i=0; i < parseInt($(window).width()/320 + 1) ; i++) { %}
            <div class="wrapper-cell slide">
                <div class="image"></div>
            </div>
        {% } %}
    </div>
     {% for (var e=0; e < parseInt($(window).height()/180) ; e++) { %}
        <div class="wrapper">
            {% for (var i=0; i < parseInt($(window).width()/140 + 1) ; i++) { %}
                <div class="wrapper-cell swiper">
                    <div class="image"></div>
                    <div class="text">
                        <div class="text-line"> </div>
                        <div class="text-line"></div>
                    </div>
                </div>
            {% } %}
        </div>
    {% } %}
</script>
<script id="loading-trending" type="text/x-tmpl">
    <div class="wrapper">
        {% for (var i=0; i < parseInt($(window).width()/320 + 1) ; i++) { %}
            <div class="wrapper-cell slide">
                <div class="image"></div>
            </div>
        {% } %}
    </div>
    <div class="container">
        <div class="wrapper block">
            {% for (var e=0; e < parseInt($(window).height()/76) ; e++) { %}
                <div class="wrapper-cell song">
                    <div class="image"></div>
                    <div class="text">
                        <div class="text-line"> </div>
                        <div class="text-line"></div>
                    </div>
                </div>
            {% } %}
        </div>
    </div>
</script>
<script id="loading-profile-card" type="text/x-tmpl">
    <div class="container">
        <div class="wrapper">
            <div class="wrapper-cell profile-card">
                <div class="image"></div>
                <div class="text">
                    <div class="text-line"> </div>
                    <div class="text-line"></div>
                </div>
                </div>
            </div>
            <div class="wrapper block">
            {% for (var e=0; e < parseInt($(window).height()/76) ; e++) { %}
                <div class="wrapper-cell song">
                    <div class="image"></div>
                    <div class="text">
                        <div class="text-line"> </div>
                        <div class="text-line"></div>
                    </div>
                </div>
            {% } %}
        </div>
    </div>
</script>
<script id="loading-community" type="text/x-tmpl">
    <div class="wrapper">
        {% for (var i=0; i < parseInt($(window).width()/320 + 1) ; i++) { %}
            <div class="wrapper-cell slide">
                <div class="image"></div>
            </div>
        {% } %}
    </div>
    <div class="wrapper block">
        {% for (var e=0; e < parseInt($(window).height()/76) ; e++) { %}
            <div class="wrapper-cell community">
                <div class="image"></div>
                <div class="text">
                    <div class="text-line"> </div>
                    <div class="text-line"></div>
                </div>
            </div>
        {% } %}
    </div>
</script>
<script id="loading-discover" type="text/x-tmpl">
    <div class="wrapper">
        {% for (var i=0; i < parseInt($(window).width()/320 + 1) ; i++) { %}
            <div class="wrapper-cell slide">
                <div class="image"></div>
            </div>
        {% } %}
    </div>
     {% for (var e=0; e < parseInt($(window).height()/110) ; e++) { %}
        <div class="wrapper discover">
            {% for (var i=0; i < parseInt($(window).width()/160) ; i++) { %}
                <div class="wrapper-cell genre">
                    <div class="image"></div>
                </div>
            {% } %}
        </div>
    {% } %}
</script>
<script id="loading-other" type="text/x-tmpl">
    <div class="container">
        <div class="wrapper block">
            {% for (var e=0; e < parseInt($(window).height()/76) ; e++) { %}
                <div class="wrapper-cell song">
                    <div class="image"></div>
                    <div class="text">
                        <div class="text-line"> </div>
                        <div class="text-line"></div>
                    </div>
                </div>
            {% } %}
        </div>
    </div>
</script>