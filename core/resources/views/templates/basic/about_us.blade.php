@extends($activeTemplate.'layouts.frontend')
@php
$about = getContent('about.content', true);
$abouts = getContent('about.element');
@endphp
@section('content')
    <!-- Start About Us -->
    <section class="section-wrapper about-us-bg">
        <div class="about-us">
            <div class="about-us-text">
                <h1>@lang('ABOUT US')</h1>
                <p>
                  @lang( "M-Cultivo provides coffee producers technology services to digitalize their supply chains, offering them better opportunities to compete more fully in the global marketplace. The primary ways we partner with coffee producers are through supply chain management technologies tailored specifically to coffee production, and access-to-market solutions that provide commerce enablement like our Marketplaces.")
                </p>
            </div>
            <div class="about-us-team">
                <div class="team-cards-row">
                    <div class="team-member-card">
                        <div class="team-member-overlay"></div>
                        <div class="team-member-info">
                            <p class="member-name">
                                David Paparelli
                            </p>
                            <p class="member-role">
                                CEO
                            </p>
                        </div>
                    </div>
                    <div class="team-member-card">
                        <div class="team-member-overlay"></div>
                        <div class="team-member-info">
                            <p class="member-name">
                                Amanda Paparelli
                            </p>
                            <p class="member-role">
                                @lang('Communications')
                            </p>
                        </div>
                    </div>
                    <div class="team-member-card">
                        <div class="team-member-overlay"></div>
                        <div class="team-member-info">
                            <p class="member-name">
                                Melanie Robb
                            </p>
                            <p class="member-role">
                                @lang('Operations')
                            </p>
                        </div>
                    </div>
                    <div class="team-member-card">
                        <div class="team-member-overlay"></div>
                        <div class="team-member-info">
                            <p class="member-name">
                                Jamie White
                            </p>
                            <p class="member-role">
                                @lang('Technology')
                            </p>
                        </div>
                    </div>
                    <div class="team-member-card">
                        <div class="team-member-overlay"></div>
                        <div class="team-member-info">
                            <p class="member-name">
                                O Tyler Pearson
                            </p>
                            <p class="member-role">
                                Supply Chain
                            </p>
                        </div>
                    </div>
                </div>
                <div class="team-cards-row">
                    <div class="team-member-card">
                        <div class="team-member-overlay"></div>
                        <div class="team-member-info">
                            <p class="member-name">
                                Laura Gómez
                            </p>
                            <p class="member-role">
                                @lang('Product Development')
                            </p>
                        </div>
                    </div>
                    <div class="team-member-card">
                        <div class="team-member-overlay"></div>
                        <div class="team-member-info">
                            <p class="member-name">
                                Pablo Vasquez
                            </p>
                            <p class="member-role">
                                @lang('Business Development')
                            </p>
                        </div>
                    </div>
                    <div class="team-member-card">
                        <div class="team-member-overlay"></div>
                        <div class="team-member-info">
                            <p class="member-name">
                                Paulo Henrique
                            </p>
                            <p class="member-role">
                                @lang('Marketing')
                            </p>
                        </div>
                    </div>
                    <div class="team-member-card">
                        <div class="team-member-overlay"></div>
                        <div class="team-member-info">
                            <p class="member-name">
                                Amanda Eastwood
                            </p>
                            <p class="member-role">
                                @lang('Strategic Partnerships')
                            </p>
                        </div>
                    </div>
                    <div class="team-member-card d-none d-xl-block">
                        <!-- <div class="team-member-overlay"></div>
                        <div class="team-member-info">
                            <p class="member-name">
                                New team member name
                            </p>
                            <p class="member-role">
                                New team member role
                            </p>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End About Us -->

    <!-- Start Our History -->
    <section class="section-wrapper">
        <div class="our-history">
            <div class="our-histroy-image">
                <img src="{{asset('assets/about_us')}}/images/history/computer-on-coffee-drying.jpg" alt="Computer on coffee drying" />
            </div>

            <div class="our-history-text">
                <h1>@lang('OUR STORY')</h1>
                <div>
                    <p>@lang( "Founded in 2020, our mission to cultivate better livelihoods for coffee farmers is born out of a deep love for the industry and a desire to see everyone in it flourish. We are coffee professionals and compassionate problem solvers with decades of experience in coffee supply chains, technology, and impact evaluation. We think the best way to improve coffee is to create a supply chain that actually works for producers.")
                    </p>
                    <p> @lang("For decades, coffee producers have been excluded and disconnected from fully competing in the global coffee market. Limited pricing information forces farmers to accept a low price mandated by a select few buyers. Millions of farmers are stuck in this cycle that barely keeps them in business one more day.")
                    </p>
                    <p>@lang("Our solutions have been built alongside coffee producers through hundreds of conversations and interviews. The feedback from these producers has been our guide to ensure we are building a solution that meets real needs. M-Cultivo is not just a technology company. We are fostering a global community of producers strengthened by the information and relationships they have access to through our platform.")
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- End Our History -->

    <!-- Start Partnerships -->
    <section class="section-wrapper">
        <div class="partnerships">
            <div class="partnerships-text">
                <h1>@lang('PARTNERSHIPS')</h1>
                <div>
                    <p>
                        {!! __("We've partnered with the <a class=\"custom-link\" href=\"https://allianceforcoffeeexcellence.org/\" target=\"_blank\">Alliance for Coffee Excellence</a>to host their prestigious Cup of Excellence auctions, and to expand their auction programming beyond their Cup of Excellence, National Winner's, and ACE Private Collection auctions. We're excited to expand our partnership with ACE to offer marketplaces for coffees vetted through the National Juries in the Cup of Excellence process. In addition to our partnership with ACE and COE, we'll be offering private auctions for producers, as well as an ongoing offer list of coffees available to buy now. You can learn more about the different types of auctions and marketplaces <a class=\"custom-link\" href=\"https://help.mcultivo.com/category/cup-of-excellence\" target=\"_blank\">here</a>.",
                                ['link'=>'<a class="custom-link" href="https://help.mcultivo.com/category/cup-of-excellence" target="_blank">'.__('here').'</a>','cupOfExcellenceLink'=>'<a class="custom-link" href="https://allianceforcoffeeexcellence.org/" target="_blank">'.__('Alliance for Coffee Excellence').'</a>']) !!}
                    </p>
                </div>
            </div>
            <div class="partnerships-image">
                <img src="{{asset('assets/about_us')}}/images/partnerships/ALLIANCE-LOGO.jpg" alt="ALLIANCE - M-CULTIVO partnerships" />
            </div>
        </div>
    </section>
    <!-- End Partnerships -->

    <!-- Start NEWS AND NOTEWORTHY -->
    <section class="section-wrapper news-bg">
        <div class="news">
            <h1>@lang('NEWS AND NOTEWORTHY')</h1>

            <div class="awards">
                <img src="{{asset('assets/about_us')}}/images/news/LSE-Generate.svg" alt="LSE-Generate" />
                <img src="{{asset('assets/about_us')}}/images/news/LSE-Enterpreneur.svg" alt="LSE-Enterpreneur" />
                <img src="{{asset('assets/about_us')}}/images/news/Santander-X.svg" alt="Santander-X" />
            </div>

            <div class="divider"></div>

            <div class="news-agencies">
                <a href="https://dailycoffeenews.com/2021/11/02/online-marketplace-brazil-select-to-launch-with-vetted-86-point-microlots/"
                   target="_blank"><img src="{{asset('assets/about_us')}}/images/news/Daily-Coffee-News.svg" alt="Daily-Coffee-News" /></a>
                <a href=" https://www.gcrmag.com/new-marketplace-gives-buyers-direct-access-to-brazils-coe-national-jury-vetted-lots/"
                   target="_blank"><img src="{{asset('assets/about_us')}}/images/news/Global-Coffee-Report.svg" alt="Global-Coffee-Report" /></a>
                <a href="https://www.teaandcoffee.net/news/28267/new-e-commerce-marketplace-gives-buyers-direct-access-to-brazilian-micro-lots/"
                   target="_blank"><img src="{{asset('assets/about_us')}}/images/news/Tea-Coffee.svg" alt="Tea-Coffee" /></a>
                <a href="https://www.beanscenemag.com.au/tag/m-cultivo/" target="_blank"><img
                        src="{{asset('assets/about_us')}}/images/news/Bean-Scene.svg" alt="Bean-Scene" /></a>
                <a href="https://www.baristamagazine.com/buy-high-scoring-cup-of-excellence-coffees-in-just-one-click/"
                   target="_blank"><img src="{{asset('assets/about_us')}}/images/news/Barista-Magazine-Online.svg"
                                        alt="Barista-Magazine-Online" /></a>
            </div>
        </div>
    </section>
    <!-- End NEWS AND NOTEWORTHY -->

    <!-- Start B Corporation -->
    <section class="section-wrapper">
        <div class="b-corporation">
            <div class="b-corporation-image"><img src="{{asset('assets/about_us')}}/images/corporation/certified-b-corporation.svg"
                                                  alt="Certified B Corporation" /></div>

            <p>
                {!! __("In order to provide accountability within our organization and to our stakeholders, we have chosen to become a B Corporation. As a B Corporation, we are legally required to consider the impact our decisions have on our employees, customers, suppliers, community, and environment. We chose to pursue this certification because it provides us with a framework to measure and assess our impact in the communities we serve. Learn more about our B-Corp certification <a class=\"custom-link\" href=\"https://www.bcorporation.net/en-us/find-a-b-corp/company/m-cultivo\" target=\"_blank\">here</a>.",
                               ['link'=>'<a class="custom-link" href="https://www.bcorporation.net/en-us/find-a-b-corp/company/m-cultivo" target="_blank">'.__('here').'</a>']) !!}



            </p>
        </div>
    </section>
    <!-- End B Corporation -->

    <!-- Start SD Goals -->
    <section class="section-wrapper">
        <div class="sd-goals">
            <div class="sd-goals-text">
                <div class="sd-goals-image"><img src="{{asset('assets/about_us')}}/images/goals/E-SDG-logo.svg" alt="E SDG logo" /></div>
                <p>

                    {!!  __("Our goals and impact evaluation align with several of the United Nations' Sustainable Development Goals (SDGs). These goals address the global challenges including poverty, inequality, climate change, environmental degradation, peace and justice. While many of the SDG targets and indicators are aimed at governments and institutions, as a for-profit social enterprise, we have identified areas where we can contribute. Learn more about the United Nation's Sustainable Development Goals <a class=\"custom-link\" href=\"https://sdgs.un.org/\" target=\"_blank\">here</a>.", [
                        'link'=>'<a class="custom-link" href="https://sdgs.un.org/" target="_blank">'.__('here').'</a>']) !!}
                </p>
            </div>

            <div class="sd-goals-cards">
                <div class="sd-goals-card">
                    <a href="https://sdgs.un.org/goals/goal1" class="goals-card-logo" target="_blank">
                        <img src="{{asset('assets/about_us')}}/images/goals/no-poverty.jpg" alt="no-poverty" />
                    </a>
                    <ul class="goals-card-list">
                        <li>@lang("Improve livelihoods of farmers and their families")</li>
                        <li>@lang("Benchmark against international and national poverty lines")</li>
                        <li>@lang("Improve access to technology for first mile producers")</li>
                        <li>@lang("Track land ownership")</li>
                        <li>@lang("Build resilience of coffee farmers to shocks from global warming")</li>
                    </ul>
                </div>
                <div class="sd-goals-card">
                    <a href="https://sdgs.un.org/goals/goal8" class="goals-card-logo" target="_blank">
                        <img src="{{asset('assets/about_us')}}/images/goals/decent-work-and-economic-growth.jpg"
                             alt="decent-work-and-economic-growth" />
                    </a>
                    <ul class="goals-card-list">
                        <li> @lang("Increase incomes and improve the operations of farmers, producers and exporters")</li>
                        <li>@lang("Provide technological upgrading and innovation, increasing the value of the coffee sold")</li>
                        <li>@lang("Provide opportunities for individuals through an operator model")</li>
                        <li>@lang("Track land ownership")</li>
                        <li>@lang("Track changes in employment at production facilities as a result M-Cultivo")</li>
                    </ul>
                </div>
                <div class="sd-goals-card">
                    <a href="https://sdgs.un.org/goals/goal10" class="goals-card-logo" target="_blank">
                        <img src="{{asset('assets/about_us')}}/images/goals/reduced-inequalities.jpg" alt="reduced-inequalities" />
                    </a>
                    <ul class="goals-card-list">
                        <li>@lang("Increase farmer income, and benchmark against income percentile and median income")</li>
                        <li>@lang("Track changes in well-being for farmers")</li>
                        <li>@lang("mprove visibility of payments in the first mile of the supply chain for regulators")</li>
                    </ul>
                </div>
                <div class="sd-goals-card">
                    <a href="https://sdgs.un.org/goals/goal15" class="goals-card-logo" target="_blank">
                        <img src="{{asset('assets/about_us')}}/images/goals/life-on-land.jpg" alt="life-on-land" />
                    </a>
                    <ul class="goals-card-list">
                        <li>@lang("Track shade trees and erosion prevention practices, and in the future incentivize these practices")</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- End SD Goals -->
@endsection

@push('style')
<link rel="stylesheet" href="{{asset('assets/about_us')}}/css/about-us.min.css">
@endpush
