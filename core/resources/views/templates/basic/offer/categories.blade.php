@extends($activeTemplate.'layouts.frontend_commerce')

@section('content')
<section class="product-section pt-120 pb-120">
    <div class="container">
        <div class="categories__wrapper bg--body">
            <div class="inner__grp">
                @forelse ($categories as $category)
                    <a href="{{ route('category.events', [$category->id]) }}" class="category__item">
                        <div class="category__item-icon">
                            <!-- @php
                                echo $category->icon;
                            @endphp -->
                            <img src="{{asset('assets/images/frontend/categories/'.$category->icon)}}" height="100px">
                        </div>
                        <h6 class="category__item-title">{{ __($category->name) }}</h6>
                    </a>
                @empty
                    <div class="text-center my-3">
                        <p>{{ __($emptyMessage) }}</p>
                    </div>
                @endforelse
            </div>
        </div>
        {{ $categories->links() }}
    </div>
</section>
@endsection
