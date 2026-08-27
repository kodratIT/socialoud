<div class="mb-3">
    <label for="widget-title" class="form-label">{{ trans('plugins/fob-google-reviews::google-reviews.widget_config.title') }}</label>
    <input
        class="form-control"
        id="widget-title"
        name="title"
        type="text"
        value="{{ $config['title'] ?? '' }}"
        placeholder="{{ trans('plugins/fob-google-reviews::google-reviews.widget_config.title_placeholder') }}"
    >
    <span class="form-text text-muted">{{ trans('plugins/fob-google-reviews::google-reviews.widget_config.title_help') }}</span>
</div>
