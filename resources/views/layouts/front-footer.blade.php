<footer class="site-footer">
    <div class="container">
        <div class="footer-row">
            <div class="footer-col footer-link">
                <div class="footer-widget">
                    <div class="footer-logo">
                        <a href="{{ route('home') }}" tabindex="0">
                            <img src="{{ Storage::url(Utility::getsettings('app_logo')) ? Utility::getpath('logo/app-logo.png') : asset('assets/images/logo/app-logo.png') }}"
                                class="footer-light-logo">
                            <img src="{{ Utility::getsettings('app_dark_logo') ? Utility::getpath('logo/app-dark-logo.png') : asset('assets/images/logo/app-dark-logo.png') }}"
                                class="footer-dark-logo">
                        </a>
                    </div>
                    <p>{{ Utility::getsettings('footer_description')
                        ? Utility::getsettings('footer_description')
                        : 'A feature is a unique quality or characteristic that something has. Real-life examples: Elaborately colored tail feathers are peacocks most well-known feature.' }}
                    </p>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="row align-items-center">
                <div class="col-12">
                    <p> © Him Soft Solution Saas</p>
                </div>
            </div>
        </div>
    </div>
</footer>
