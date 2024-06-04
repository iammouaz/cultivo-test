<footer class="ace-footer">
    <div class="container">
        <div class="footer-middle-wrapper justify-content-center justify-content-md-between pt-4 pb-4">
            <div class="d-flex align-items-center gap-3 powered-by">
                <p>Powered by:</p>
                <a href="{{ route('home') }}">
                    <img src="{{ getImage(imagePath()['logoIcon']['path'] .'/logo.png') }}" alt="logo">
                </a>
            </div>
            <div class="cont mt-3 mt-md-0 flex-column flex-md-row align-items-center align-items-md-baseline">
                <p>&copy; {{ date('Y') }} <a href="{{ route('home') }}">{{ $general->sitename  }}</a>. @lang('All Right Reserved')</p>
            </div>
        </div>
    </div>
</footer>