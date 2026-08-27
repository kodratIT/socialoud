# Changelog

All notable changes to this project will be documented in this file.

## [1.0.0] - 2025-01-15

### Added
- Initial release of FOB Google Reviews plugin
- Professional carousel widget for displaying Google reviews
- Manual mode for database-stored reviews
- Google API mode for fetching live reviews
- Multiple display types: Carousel, Grid, and List layouts
- Per-product Google Reviews configuration
- Shortcode support for placing reviews anywhere
- Multi-language support with 42 translations
- Comprehensive settings page with collapsible sections
- Smart caching system with configurable duration
- Responsive design adapting to all screen sizes

### Features
- **Dual Source Mode**: Choose between Manual (Database) or Google API
- **Display Types**: Carousel with autoplay, Grid with customizable columns, or List view
- **Per-Product Settings**: Override place ID for individual products
- **Shortcode Integration**: Place reviews anywhere with `[google-reviews]` shortcode
- **Rich Customization**: Extensive appearance and behavior settings
- **Smart Caching**: Configurable cache duration (5-1440 minutes)
- **Responsive Design**: Adapts from 1 to 4 columns based on screen size
- **Multi-language**: 42 language translations included
- **Accessibility**: ARIA labels and keyboard navigation support
- **Theme Independent**: Works seamlessly with all Botble themes

### Technical
- Built on Botble CMS 7.5.0+
- Webpack-based asset compilation (SCSS + JavaScript)
- Hook-based integration with Ecommerce plugin
- Database migrations for reviews and product associations
- Seeder for sample manual reviews
- PSR-12 code standards
- Clean, maintainable codebase

### Settings

**Review Source**
- Manual (Database) mode with admin interface
- Google API mode with Places API integration

**Display Options**
- Display Type: Carousel, Grid, or List
- Maximum Reviews: 1-20
- Minimum Rating filter: 0-5 stars
- Carousel-specific: Autoplay and speed controls
- Grid-specific: Reviews per row (1-4)

**Visibility**
- Show on product pages toggle
- "View All Reviews" button option
- Auto-refresh reviews setting
- Configurable cache duration

**Appearance**
- Custom widget title
- Toggle ratings summary
- Toggle author avatar
- Toggle author name
- Toggle review date
- Toggle star ratings
- Configurable text length
- "Read More" link option

### Database Tables
- `google_reviews`: Product-specific review settings
- `google_reviews_data`: Manual review storage

### Requirements
- PHP 8.2+
- Botble CMS 7.5.0+
- Ecommerce plugin activated (for product integration)

### Installation
1. Extract plugin to `platform/plugins/fob-google-reviews`
2. Run migrations: `php artisan migrate`
3. Activate plugin in admin panel
4. Configure settings in Settings > Google Reviews
5. (Optional) Run seeder for sample reviews
6. (Optional) Build assets: `npm run dev` or `npm run prod`

### Usage Examples

**Shortcode in Page Content:**
```
[google-reviews][/google-reviews]
```

**In Blade Templates:**
```php
{!! Shortcode::compile('[google-reviews][/google-reviews]') !!}
```

**Manual Review via Tinker:**
```php
FriendsOfBotble\GoogleReviews\Models\GoogleReviewData::create([
    'author_name' => 'John Doe',
    'rating' => 5,
    'text' => 'Great product!',
    'review_date' => now(),
    'is_active' => true,
    'order' => 1
]);
```

### Important Notes
- Google Places API usage may incur costs after free tier
- Cache duration recommended: 60+ minutes to reduce API calls
- Manual mode provides full control without API costs
- Per-product Place IDs allow showing different location reviews
- Widget automatically appears in product comment sections (if enabled)
