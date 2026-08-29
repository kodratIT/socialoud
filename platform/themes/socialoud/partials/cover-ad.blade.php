<div class="socialoud-ad-modal" data-socialoud-cover-ad data-socialoud-popup-key="{{ $popupAdKey ?? 'default' }}" data-socialoud-popup-order="{{ $popupAdOrder ?? 0 }}" hidden role="dialog" aria-modal="true" aria-labelledby="socialoud-cover-ad-title">
    <div class="socialoud-ad-modal-backdrop" data-socialoud-ad-close></div>
    <div class="socialoud-ad-modal-card">
        <h2 id="socialoud-cover-ad-title" class="sr-only">Advertisement</h2>
        <button class="socialoud-ad-modal-close" type="button" aria-label="Tutup iklan" data-socialoud-ad-close>×</button>
        @if (!$popupAd)
            <span class="socialoud-ad-modal-label">ADVERTISEMENT</span>
        @endif
        @if ($popupAd)
            <div class="socialoud-ad-modal-creative">{!! $popupAd !!}</div>
        @else
            <div class="socialoud-ad-placeholder socialoud-cover-placeholder">
                <strong>Promote your brand on Socialoud</strong>
                <small>Reach readers with a focused editorial placement.</small>
                <span class="socialoud-ad-modal-cta">EXPLORE NOW</span>
            </div>
        @endif
        <span class="socialoud-ad-modal-countdown" data-socialoud-ad-countdown aria-live="polite"></span>
    </div>
</div>
