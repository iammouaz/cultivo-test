@extends($activeTemplate.'layouts.frontend')
@php
    $contact = getContent('contact_us.content', true);
@endphp
@section('content')
    <!-- Start Presence Details -->
    <section class="section-wrapper">
        <div class="presence-details">
            <div>
                <h1 class="title-level-1">@lang("WE'RE HERE TO HELP")</h1>

                <div class="details-list-item">
                    @include("templates.basic.svgIcons.chevron_double_right")

                    <p class="custom-paragraph">
                        @lang('During live auctions, you can contact our team directly through our') <a class="custom-link"
                                                                                               href="http://chat.mcultivo.com/" target="_blank">live chat</a> feature.
                    </p>
                </div>

                <div class="details-list-item">
                    @include("templates.basic.svgIcons.chevron_double_right")

                    <p class="custom-paragraph">
                        Outside of live events, you can contact us through our <a class="custom-link"
                                                                                  href="mailto:support@mcultivo.com" target="_blank">support email</a>, or you
                        can send us a
                        message through the chatbot.
                    </p>
                </div>

                <div class="details-list-item">
                    @include("templates.basic.svgIcons.chevron_double_right")

                    <p class="custom-paragraph">
                        If you need to get in touch with the Alliance for Coffee Excellence, you can do so <a
                            class="custom-link" href="https://allianceforcoffeeexcellence.org/contact-us/"
                            target="_blank">here</a>.
                    </p>
                </div>
            </div>

            <div>
                <img class="global-presence-image"  src="{{asset('assets/contact_us')}}/images/mcultivo-global-presence.png" />

                <h2 class="title-level-2">@lang("OUR GLOBAL PRESENCE")</h2>

                <div class="global-presence">
                    <div class="global-presence-item">
                        @include("templates.basic.svgIcons.pin")
                        <p>
                            Atlanta, GA
                            <span>USA</span>
                        </p>
                    </div>
                    <div class="global-presence-item">
                        @include("templates.basic.svgIcons.pin")
                        <p>
                            Toronto, ON
                            <span>Canada</span>
                        </p>
                    </div>
                    <div class="global-presence-item">
                        @include("templates.basic.svgIcons.pin")
                        <p>
                            London
                            <span>UK</span>
                        </p>
                    </div>
                    <div class="global-presence-item">
                        @include("templates.basic.svgIcons.pin")
                        <p>
                            Patos de Minas
                            <span>Brazil</span>
                        </p>
                    </div>
                    <div class="global-presence-item">
                        @include("templates.basic.svgIcons.pin")
                        <p>
                            Guatemala City
                            <span>Guatemala</span>
                        </p>
                    </div>
                    <div class="global-presence-item">
                        @include("templates.basic.svgIcons.pin")
                        <p>
                            Lima
                            <span>"Peru"</span>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- End Presence Details -->

    <!-- Start Contact Info -->
    <section class="section-wrapper contact-info-bg">
        <div class="contact-info">
            <div>
                <h2 class="title-level-2">@lang('GET IN TOUCH')</h2>
                <div class="contact-info-item">
                    <!-- <img  src="{{asset('assets/contact_us')}}/images/envelope.svg" alt="envelope-icon" /> -->

                    @include("templates.basic.svgIcons.envelope")

                    <a class="contact-info-link" href="https://mcultivo.com/">support@mcultivo.com</a>
                </div>
                <div class="contact-info-item">
                    <!-- <img  src="{{asset('assets/contact_us')}}/images/telephone.svg" alt="telephone-icon" /> -->
                    @include("templates.basic.svgIcons.telephone")
                    <a class="contact-info-link" href="tel:+1 (678) 404-9338">+1 (678) 404-9338</a>
                </div>
            </div>
            <div>
                <h2 class="title-level-2">@lang("FOLLOW US")</h2>
                <div class="contact-info-item">
                    <!-- <img  src="{{asset('assets/contact_us')}}/images/linkedin.svg" alt="linkedin-icon" /> -->
                    @include("templates.basic.svgIcons.linkedin")
                    <a class="contact-info-link" href="https://www.linkedin.com/company/m-cultivo/">@m-cultivo</a>
                </div>
                <div class="contact-info-item">
                    <!-- <img  src="{{asset('assets/contact_us')}}/images/instagram.svg" alt="instagram-icon" /> -->
                    @include("templates.basic.svgIcons.instagram")
                    <a class="contact-info-link" href="https://www.instagram.com/mcultivo.coffee">@mcultivo.coffee</a>
                </div>
            </div>
        </div>
    </section>
@endsection


@push('style')
    <link rel="stylesheet" href="{{asset('assets/contact_us')}}/css/contact-us.min.css">
@endpush
