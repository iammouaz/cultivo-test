@php
    if(isset($seoContents) && count($seoContents)){
        $seoContents        = json_decode(json_encode($seoContents, true));
        $socialImageSize    = explode('x', $seoContents->image_size);
    }elseif($seo){
        $seoContents        = $seo;
        $socialImageSize    = explode('x', imagePath()['seo']['size']);
        $seoContents->image = getImage(imagePath()['seo']['path'] .'/'. $seo->image);
    }else{
        $seoContents = null;
    }

@endphp

<meta name="title" Content="{{ $general->sitename($pageTitle) }}">

@if($seoContents)
    <meta name="description" content="{{ getSiteDescription() }}">
    <meta name="keywords" content="{{ implode(',',$seoContents->keywords) }}">
    <link rel="shortcut icon" href="{{ getImage(imagePath()['logoIcon']['path'] .'/favicon.png') }}"
          type="image/x-icon">

    {{--<!-- Apple Stuff -->--}}
    <link rel="apple-touch-icon" href="{{ getImage(imagePath()['logoIcon']['path'] .'/logo.png') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="{{ $general->sitename($pageTitle) }}">
    {{--<!-- Google / Search Engine Tags -->--}}
    <meta itemprop="name" content="{{ $general->sitename($pageTitle) }}">
    <meta itemprop="description" content="{{ $seoContents->description }}">
    <meta itemprop="image" content="{{ getSocialImage() }}">
    {{--<!-- Facebook Meta Tags -->--}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{  getSiteTitle() }}">
    <meta property="og:description" content="{{ getSiteDescription() }}">
    <meta property="og:image" content="{{ getSocialImage() }}"/>
    {{-- @if(is_array(pathinfo($seoContents->image)) && isset(pathinfo($seoContents->image)['extension']))
        <meta property="og:image:type" content="{{ pathinfo($seoContents->image)['extension'] }}"/>
    @endif
    <meta property="og:image:width" content="{{ $socialImageSize[0] }}"/>
    <meta property="og:image:height" content="{{ $socialImageSize[1] }}"/> --}}
    <meta property="og:url" content="{{ url()->current() }}">
    {{--<!-- Twitter Meta Tags -->--}}
    <meta name="twitter:card" content="summary_large_image">
@endif
