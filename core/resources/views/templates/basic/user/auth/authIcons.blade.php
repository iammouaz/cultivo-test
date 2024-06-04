<div class="login-social-icons d-flex align-items-center gap-4 pt-3">
                
    @if (!empty(getInstagramLink()))
        <a href="{{ getInstagramLink() }}" target="_blank">
            @include('templates.basic.svgIcons.instagram')
        </a>
    @endif

    @if (!empty(getFacebookLink()))
        <a style="margin-left: 0; margin-right: 0;" href="{{ getFacebookLink() }}" target="_blank">
            @include('templates.basic.svgIcons.facebook')
        </a>
    @endif

    @if (!empty(getTwitterLink()))
    <a style="margin-left: 0; margin-right: 0;" href="{{ getTwitterLink() }}" target="_blank">
        @include('templates.basic.svgIcons.twitter')
    </a>
@endif

    @if (!empty(getLinkedinLink()))
        <a href="{{ getLinkedinLink() }}" target="_blank">
            @include('templates.basic.svgIcons.linkedin')
        </a>
    @endif

    @if (!empty(getVimeoLink()))
    <a style="margin-left: 0; margin-right: 0;" href="{{ getVimeoLink() }}" target="_blank">
        @include('templates.basic.svgIcons.vimo')
    </a>
@endif

    @if (!empty(getEmail()))
        <a href="mailto: {{ getEmail() }}" target="_blank">
            @include('templates.basic.svgIcons.envelope')
        </a>
    @endif


</div>