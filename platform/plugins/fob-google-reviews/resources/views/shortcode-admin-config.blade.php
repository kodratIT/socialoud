<div class="alert alert-info d-block mb-3">
    <p><strong>{{ trans('plugins/fob-google-reviews::google-reviews.shortcode_name') }}</strong></p>
    <p>{{ trans('plugins/fob-google-reviews::google-reviews.shortcode_usage_info') }}</p>
    <ul>
        <li>{{ trans('plugins/fob-google-reviews::google-reviews.shortcode_info_1') }}</li>
        <li>{{ trans('plugins/fob-google-reviews::google-reviews.shortcode_info_2') }}</li>
        <li>{{ trans('plugins/fob-google-reviews::google-reviews.shortcode_info_3') }}</li>
    </ul>
    <p class="mb-0">
        <a href="{{ route('google-reviews.settings') }}" target="_blank">
            {{ trans('plugins/fob-google-reviews::google-reviews.configure_settings') }}
        </a>
    </p>
</div>

<div class="mb-3">
    <label for="shortcode-title" class="form-label">{{ trans('plugins/fob-google-reviews::google-reviews.shortcode_config.title') }}</label>
    <input
        type="text"
        name="title"
        id="shortcode-title"
        class="form-control"
        value="{{ Arr::get($attributes, 'title') }}"
        placeholder="{{ trans('plugins/fob-google-reviews::google-reviews.shortcode_config.title_placeholder') }}"
    >
    <span class="form-text text-muted">{{ trans('plugins/fob-google-reviews::google-reviews.shortcode_config.title_help') }}</span>
</div>
