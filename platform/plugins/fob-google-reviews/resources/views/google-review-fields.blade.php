<div class="mb-3">
    <div class="form-check">
        <input type="hidden" name="google_reviews_enabled" value="0">
        <input type="checkbox"
               class="form-check-input"
               id="google_reviews_enabled"
               name="google_reviews_enabled"
               value="1"
               {{ $review && $review->is_enabled ? 'checked' : '' }}>
        <label class="form-check-label" for="google_reviews_enabled">
            {{ trans('plugins/fob-google-reviews::google-reviews.enable_google_reviews') }}
        </label>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">{{ trans('plugins/fob-google-reviews::google-reviews.custom_place_id') }}</label>
    <input type="text"
           class="form-control"
           name="google_reviews_custom_place_id"
           value="{{ $review ? $review->custom_place_id : '' }}"
           placeholder="{{ setting('google_reviews_place_id') }}">
    <small class="form-text text-muted">{{ trans('plugins/fob-google-reviews::google-reviews.custom_place_id_help') }}</small>
</div>
