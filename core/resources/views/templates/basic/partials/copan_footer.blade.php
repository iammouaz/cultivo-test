<section class="contact-info d-flex flex-column align-items-center justify-content-center">
    <img src="{{ getImage(imagePath()['event']['path'] . '/copan-trade-logo.jpg', imagePath()['event']['thumb']) }}"
        alt="copan trade logo">
    <div class="d-flex align-items-center gap-4 mt-4 mb-2">
        <a class="facebook-icon" href="https://www.facebook.com/CopanTrade/" target="_blank">
            @include('templates.basic.svgIcons.facebook')
        </a>
        <a class="instagram-icon" href="https://www.instagram.com/COPANTRADE/" target="_blank">
            @include('templates.basic.svgIcons.instagram')
        </a>
        <a class="youtube-icon" href="https://www.youtube.com/watch?v=qdB7clpfPwk" target="_blank">
            @include('templates.basic.svgIcons.youtube')
        </a>
        <a class="linkedin-icon" href="https://www.linkedin.com/company/copantrade" target="_blank">
            @include('templates.basic.svgIcons.linkedin')
        </a>
    </div>
    <a class="support-email text-white" href="mailto:shipping@copantrade.com">shipping@copantrade.com</a>
</section>
