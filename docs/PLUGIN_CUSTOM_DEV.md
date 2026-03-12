# PLUGIN_CUSTOM_DEV — Custom Plugins

> **Dự án:** Website XANH - Design & Build
> **Cập nhật:** 2026-03-12

---

## 1. Xanh Estimator Plugin

| Thuộc tính | Giá trị |
|---|---|
| **Slug** | `xanh-estimator` |
| **Mục tiêu** | Tính dự toán sơ bộ dựa trên input của khách hàng |
| **Phase** | Phase 1 (MVP) |

### Chức năng
- Hook vào Fluent Form submission → tính toán dự toán
- Đọc đơn giá từ ACF Options (`group_estimator`)
- Generate kết quả inline (AJAX response)
- Optional: Generate PDF báo giá sơ bộ
- Log dữ liệu vào custom DB table (cho analytics)

### File Structure
```
xanh-estimator/
├── xanh-estimator.php              # Plugin header, activation, deactivation
├── includes/
│   ├── class-estimator-loader.php  # Autoloader + hook registration
│   ├── class-calculator.php        # Core calculation logic (pure functions)
│   ├── class-pdf-generator.php     # PDF generation (TCPDF/DOMPDF)
│   ├── class-ajax-handler.php      # AJAX endpoints
│   └── class-admin-settings.php    # Admin settings page (nếu cần)
├── assets/
│   ├── css/estimator.css           # Estimator UI styles
│   └── js/estimator.js             # Client-side calculation + UX
├── templates/
│   └── result-template.php         # Output template (HTML partial)
├── languages/
│   └── xanh-estimator-vi.po        # Vietnamese translation
└── uninstall.php                   # Clean up on plugin deletion
```

### Chi tiết tính toán — Xem `FEATURE_ESTIMATOR.md`

---

## 2. Xanh Utilities Plugin

| Thuộc tính | Giá trị |
|---|---|
| **Slug** | `xanh-utilities` |
| **Mục tiêu** | Shortcodes, helper functions, custom widgets |
| **Phase** | Phase 1 |

### Shortcodes

| Shortcode | Mô tả | Sử dụng |
|---|---|---|
| `[xanh_counter]` | Animated counter section | Home, About, Portfolio, Contact |
| `[xanh_process_steps]` | Quy trình 6 bước | Home |
| `[xanh_before_after]` | Before/After slider | Portfolio Detail |
| `[xanh_faq]` | FAQ Accordion | Contact |
| `[xanh_partner_logos]` | Partner logos carousel | Home |
| `[xanh_cta_block]` | CTA section | Global |

---

## 3. Xanh Client Portal (Phase 2)

| Thuộc tính | Giá trị |
|---|---|
| **Slug** | `xanh-client-portal` |
| **Phase** | Phase 2 (sau go-live) |

### Chức năng dự kiến
- Custom login/register page (branded)
- Client dashboard: nhật ký thi công, ảnh hàng ngày
- Biên bản nghiệm thu online
- Email notification khi có cập nhật
- Role: `xanh_client` với capabilities hạn chế

### Architecture — Xem `ARCH_SCALABILITY.md` §5.1

---

## 4. Plugin Architecture Pattern ★

### Class-Based OOP Pattern

```php
<?php
/**
 * Plugin Name: XANH Estimator
 * Description: Công cụ dự toán chi phí xây dựng
 * Version: 1.0.0
 * Author: XANH - Design & Build
 * Text Domain: xanh
 */

defined('ABSPATH') || exit;

// Constants
define('XANH_ESTIMATOR_VERSION', '1.0.0');
define('XANH_ESTIMATOR_PATH', plugin_dir_path(__FILE__));
define('XANH_ESTIMATOR_URL', plugin_dir_url(__FILE__));

// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'Xanh_Estimator_';
    if (strpos($class, $prefix) !== 0) return;

    $file = XANH_ESTIMATOR_PATH . 'includes/class-'
          . strtolower(str_replace(['_', $prefix], ['-', ''], $class)) . '.php';

    if (file_exists($file)) require $file;
});

// Boot plugin
class Xanh_Estimator {
    private static $instance = null;

    public static function instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->init_hooks();
    }

    private function init_hooks() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_ajax_xanh_estimate', [$this, 'handle_estimate']);
        add_action('wp_ajax_nopriv_xanh_estimate', [$this, 'handle_estimate']);

        // Custom hooks for extensibility
        do_action('xanh_estimator_loaded');
    }

    public function enqueue_assets() {
        if (!is_page('du-toan') && !is_front_page()) return;

        wp_enqueue_style('xanh-estimator', XANH_ESTIMATOR_URL . 'assets/css/estimator.css',
            [], XANH_ESTIMATOR_VERSION);
        wp_enqueue_script('xanh-estimator', XANH_ESTIMATOR_URL . 'assets/js/estimator.js',
            [], XANH_ESTIMATOR_VERSION, true);
        wp_localize_script('xanh-estimator', 'xanhEstimator', [
            'url'   => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('xanh_estimate_nonce'),
        ]);
    }

    public function handle_estimate() {
        check_ajax_referer('xanh_estimate_nonce', 'nonce');

        $area    = absint($_POST['area'] ?? 0);
        $floors  = absint($_POST['floors'] ?? 1);
        $package = sanitize_text_field($_POST['package'] ?? 'standard');

        if ($area < 10 || $area > 10000) {
            wp_send_json_error(['message' => 'Diện tích không hợp lệ']);
        }

        $calculator = new Xanh_Estimator_Calculator();
        $result = $calculator->calculate($area, $floors, $package);

        // Allow plugins to modify price
        $result['total'] = apply_filters('xanh_estimator_price',
            $result['total'], $area, $floors, $package);

        wp_send_json_success($result);
    }
}

// Initialize
Xanh_Estimator::instance();
```

### Calculator Class (Clean Code)
```php
class Xanh_Estimator_Calculator {

    private $prices;

    public function __construct() {
        $this->prices = $this->load_prices();
    }

    public function calculate(int $area, int $floors, string $package): array {
        $price_per_sqm = $this->get_price_per_sqm($package);
        $total_area = $area * $floors;
        $base_cost = $total_area * $price_per_sqm;

        return [
            'area'         => $area,
            'floors'       => $floors,
            'total_area'   => $total_area,
            'package'      => $package,
            'price_per_sqm'=> $price_per_sqm,
            'base_cost'    => $base_cost,
            'total'        => $base_cost,
            'formatted'    => $this->format_currency($base_cost),
            'disclaimer'   => $this->get_disclaimer(),
        ];
    }

    private function load_prices(): array {
        return [
            'basic'    => (int) get_field('price_per_sqm_basic', 'option')    ?: 3500000,
            'standard' => (int) get_field('price_per_sqm_standard', 'option') ?: 5000000,
            'premium'  => (int) get_field('price_per_sqm_premium', 'option')  ?: 8000000,
        ];
    }

    private function get_price_per_sqm(string $package): int {
        return $this->prices[$package] ?? $this->prices['standard'];
    }

    private function format_currency(int $amount): string {
        if ($amount >= 1000000000) {
            return round($amount / 1000000000, 1) . ' Tỷ VNĐ';
        }
        return number_format($amount, 0, ',', '.') . ' VNĐ';
    }

    private function get_disclaimer(): string {
        return get_field('estimator_disclaimer', 'option')
            ?: 'Đây là ước tính tham khảo. Giá chính xác phụ thuộc vào thiết kế chi tiết.';
    }
}
```

---

## 5. Plugin Development Standards

| Rule | Giá trị |
|---|---|
| **Prefix** | `xanh_` cho functions, `Xanh_` cho classes |
| **Textdomain** | `xanh` |
| **Singleton** | `::instance()` pattern cho main class |
| **Autoloader** | `spl_autoload_register()` |
| **Min PHP** | 7.4 |
| **Min WP** | 6.0 |
| **Coding standard** | WordPress Coding Standards |
| **Security** | Nonce, capability checks, sanitize/escape |
| **Activation** | `register_activation_hook()` — create tables, flush rules |
| **Deactivation** | `register_deactivation_hook()` — clean transients |
| **Uninstall** | `uninstall.php` — remove options, tables, meta |
| **REST API** | `show_in_rest: true` cho CPTs, custom endpoints với schema |
| **Extensibility** | `do_action()` + `apply_filters()` tại integration points |

---

## Tài Liệu Liên Quan

- `FEATURE_ESTIMATOR.md` — Estimator specs
- `GOV_CODING_STANDARDS.md` — PHP standards, clean code
- `CORE_ARCHITECTURE.md` — Plugin integration points
- `ARCH_SCALABILITY.md` — Phase 2 architecture, hook system
- `GOV_SECURITY.md` — Security patterns
