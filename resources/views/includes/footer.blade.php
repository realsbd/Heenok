<!-- Footer Section -->
<div class="footer py-5">
  <footer class="container">
    <div class="row">
      <!-- Company Logo and Social Links -->
      <div class="col-md-3">
        <a href="{{ url('/') }}">
          <img src="{{ auth()->check() && auth()->user()->dark_mode == 'on' ? url('public/img', $settings->logo) : url('public/img', $settings->logo_2) }}" alt="{{ $settings->title }}" class="max-w-125">
        </a>

        <!-- Social Media Links -->
        @if ($settings->facebook || $settings->twitter || $settings->instagram || $settings->pinterest || $settings->youtube)
          <div class="w-100 mt-3">
            <span>{{ trans('general.keep_connect_with_us') }} {{ trans('general.follow_us_social') }}</span>
            <ul class="list-inline list-social m-0">
              @if ($settings->twitter)<li class="list-inline-item"><a href="{{ $settings->twitter }}" target="_blank"><i class="bi-twitter-x"></i></a></li>@endif
              @if ($settings->facebook)<li class="list-inline-item"><a href="{{ $settings->facebook }}" target="_blank"><i class="fab fa-facebook"></i></a></li>@endif
              @if ($settings->instagram)<li class="list-inline-item"><a href="{{ $settings->instagram }}" target="_blank"><i class="fab fa-instagram"></i></a></li>@endif
              <!-- Add other social icons as needed -->
            </ul>
          </div>
        @endif
      </div>

      <!-- About Section -->
      <div class="col-md-3">
        <h6 class="text-uppercase">@lang('general.about')</h6>
        <ul class="list-unstyled">
          <li><a href="{{ url('/privacy-policy') }}">Privacy Policy</a></li>
          <li><a href="{{ url('/terms') }}">Terms of Service</a></li>
          <li><a href="{{ url('/about-us') }}">About Us</a></li>
          <li><a href="{{ url('/how-it-works') }}">How it Works</a></li>
          <li><a href="{{ url('/cookies-policy') }}">Cookies Policy</a></li>
          <li><a href="{{ url('/contact-us') }}">Contact Us</a></li>
        </ul>
      </div>

      <!-- Language and Web App Installation -->
      <div class="col-md-3">
        <h6 class="text-uppercase">Language</h6>
        <ul class="list-unstyled">
          <li><a href="#">English</a></li>
          <li><a href="#">Install Web App</a></li>
        </ul>
      </div>
    </div>
  </footer>
</div>

<!-- Copyright Section -->
<footer class="py-3 text-center">
  <div class="container">
    <div class="col-md-12">
      <p>&copy; 2024 Heenok, All rights reserved.</p>
    </div>
  </div>
</footer>
