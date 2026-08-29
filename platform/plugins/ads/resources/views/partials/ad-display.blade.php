@php($imageIndex = 0)
@foreach($data as $item)
    @if ($item->ads_type === 'google_adsense' && $item->google_adsense_slot_id)
        <div {!! Html::attributes($attributes) !!}>
            @include('plugins/ads::partials.google-adsense.unit-ads-slot', ['slotId' => $item->google_adsense_slot_id])
        </div>
        @continue
    @endif

    @continue(! $item->image)

    @php($isFirstImage = $imageIndex === 0)
    @php($imageIndex++)
    @php($isWideSlot = in_array($attributes['data-ad-context'] ?? null, ['before-featured-posts', 'top-single-page'], true))
    <div {!! Html::attributes($attributes) !!}>
        @if ($item->url)
            <a href="{{ $item->click_url }}" @if ($item->open_in_new_tab) target="_blank" @endif title="{{ trans('plugins/ads::ads.banner') }}">
        @endif
                <picture>
                    <source
                        @if ($isFirstImage) srcset="{{ $item->image_url }}" @else data-srcset="{{ $item->image_url }}" @endif
                        media="(min-width: 1200px)"
                    />
                    <source
                        @if ($isFirstImage) srcset="{{ $item->tablet_image_url }}" @else data-srcset="{{ $item->tablet_image_url }}" @endif
                        media="(min-width: 768px)"
                    />
                    <source
                        @if ($isFirstImage) srcset="{{ $item->mobile_image_url }}" @else data-srcset="{{ $item->mobile_image_url }}" @endif
                        media="(max-width: 767px)"
                    />
                    <img
                        @if ($isFirstImage) src="{{ $item->image_url }}" @else data-src="{{ $item->image_url }}" @endif
                        @if ($isWideSlot)
                            width="1200"
                            height="400"
                        @endif
                        alt="{{ $item->name }}"
                        loading="lazy"
                        decoding="async"
                        style="max-width: 100%"
                    >
                </picture>
        @if($item->url)
            </a>
        @endif
    </div>
@endforeach
