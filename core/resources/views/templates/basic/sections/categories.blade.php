 @php
     $category = getContent('categories.content', true);
     $categories = \App\Models\Category::where('status', 1)->latest()->limit(10)->get();
 @endphp
 <section class="categories-section pt-60 pb-120">
    <div class="container">
        <div class="section__header">
            <h3 class="section__title">{{ $category->data_values->heading }}</h3>
            <p class="section__txt">
                
            </p>
            <!--<div class="progress progress--bar">
                <div class="progress-bar bg--base progress-bar-striped progress-bar-animated"></div>
            </div>-->
        </div>
        <div class="categories__wrapper bg--body">
            <div class="inner__grp">
                @foreach ($categories as $category)
                    <a href="{{ route('category.events', [$category->id]) }}" class="category__item">
                        <div class="category__item-icon">
                            <!-- @php
                                echo $category->icon;
                            @endphp -->
                            <img src="{{asset('assets/images/frontend/categories/'.$category->icon)}}" height="100px">
                        </div>
                        <h6 class="category__item-title">{{ $category->name }}</h6>
                    </a>
                @endforeach
            </div>
        </div>
        <div class="text-center mt-4 mt-sm-5">
            <a href="{{ route('categories') }}" class="cmn--btn">@lang('View all Categories')</a>
        </div>
    </div>
</section>
