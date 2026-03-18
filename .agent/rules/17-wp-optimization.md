---
description: WordPress WP bloat removal and asset optimization rules. Apply when configuring functions.php or enqueue.php to minimize unnecessary resources.
globs: wp-content/themes/xanhdesignbuild/inc/**/*.php
---

# WP Bloat Removal & Asset Optimization

## Remove Unnecessary WP Output (inc/theme-setup.php)
```php
function xanh_remove_wp_bloat() {
    // Emoji — saves ~20KB
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');

    // oEmbed — not needed
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');

    // Meta tags — security + cleanup
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'feed_links_extra', 3);
}
add_action('after_setup_theme', 'xanh_remove_wp_bloat');
```

## Dequeue Block Styles (inc/enqueue.php)
```php
function xanh_remove_block_styles() {
    // Classic theme — no Gutenberg frontend styles needed
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('classic-theme-styles');
    wp_dequeue_style('global-styles');
}
add_action('wp_enqueue_scripts', 'xanh_remove_block_styles', 100);
```

## Disable XML-RPC (Security + Performance)
```php
add_filter('xmlrpc_enabled', '__return_false');
```

## Optimize Heartbeat
```php
function xanh_heartbeat_settings($settings) {
    $settings['interval'] = 60; // 60s instead of 15s
    return $settings;
}
add_filter('heartbeat_settings', 'xanh_heartbeat_settings');

// Disable heartbeat on frontend entirely
function xanh_disable_frontend_heartbeat() {
    if (!is_admin()) {
        wp_deregister_script('heartbeat');
    }
}
add_action('init', 'xanh_disable_frontend_heartbeat', 1);
```

## Limit Post Revisions
```php
// wp-config.php
define('WP_POST_REVISIONS', 5);
```

## Transient Caching for Heavy Queries
```php
function xanh_get_featured_projects() {
    $cached = get_transient('xanh_featured_projects');
    if ($cached !== false) return $cached;

    $projects = get_field('featured_projects', get_option('page_on_front'));
    set_transient('xanh_featured_projects', $projects, HOUR_IN_SECONDS);
    return $projects;
}

// Clear transient when page is saved
function xanh_clear_transients($post_id) {
    if (get_post_type($post_id) === 'page' || get_post_type($post_id) === 'xanh_project') {
        delete_transient('xanh_featured_projects');
    }
}
add_action('save_post', 'xanh_clear_transients');
```

## Third-Party Script Delay
```php
function xanh_delayed_third_party() {
    ?>
    <script>
    // Zalo Widget — delay 3s after load
    window.addEventListener('load', function() {
        setTimeout(function() {
            var s = document.createElement('script');
            s.src = 'https://sp.zalo.me/plugins/sdk.js';
            s.async = true;
            document.body.appendChild(s);
        }, 3000);
    });
    </script>
    <?php
}
add_action('wp_footer', 'xanh_delayed_third_party', 99);
```

## .htaccess Performance (Production)
```apache
# Browser Caching
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/webp              "access plus 1 year"
    ExpiresByType image/avif              "access plus 1 year"
    ExpiresByType image/jpeg              "access plus 1 year"
    ExpiresByType image/png               "access plus 1 year"
    ExpiresByType image/svg+xml           "access plus 1 year"
    ExpiresByType font/woff2              "access plus 1 year"
    ExpiresByType text/css                "access plus 1 month"
    ExpiresByType application/javascript  "access plus 1 month"
</IfModule>

# Gzip
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/css text/javascript
    AddOutputFilterByType DEFLATE application/javascript application/json
    AddOutputFilterByType DEFLATE image/svg+xml
</IfModule>
```

## Enqueue Optimization Checklist
- [ ] ALL scripts: `defer` + `in_footer: true` (except Alpine.js → head)
- [ ] Conditional: Swiper only on Home + Portfolio Detail
- [ ] Conditional: GLightbox only on Portfolio Detail
- [ ] Conditional: filter.js only on Archives
- [ ] NO jQuery loaded (unless plugin forces it)
- [ ] Vendor scripts from CDN with pinned versions
- [ ] Custom JS ≤ 12KB gzip per page
- [ ] Page-specific CSS loaded conditionally

## Reference
- `docs/implement/PERFORMANCE_SEO.md` §9 — WordPress backend optimization
- `.agent/rules/06-performance.md` — Performance targets
