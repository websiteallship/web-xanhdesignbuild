# PLUGIN_AI_DATA_INTEGRITY — Chống AI Bịa Số Liệu

> **Plugin:** XANH AI Content Generator
> **Vấn đề:** AI hallucination — bịa số liệu thuyết phục nhưng sai
> **Giải pháp:** Verified Data Registry + Citation System + Guardrails
> **Cập nhật:** 2026-03-20

---

## 1. Vấn Đề Cốt Lõi

AI (Gemini, GPT, Claude) có xu hướng:
- **Bịa số liệu** rất thuyết phục: "theo khảo sát 2025, 73% gia chủ..."
- **Bịa nguồn**: "Theo Bộ Xây dựng..." (nhưng không có nguồn thật)
- **Bịa tiêu chuẩn**: "Theo TCVN 1234:2024..." (mã không tồn tại)
- **Phóng đại data XANH**: "200+ dự án" (thực tế 47+)

**Hậu quả:**
- Google phát hiện → mất E-E-A-T, penalize ranking
- Khách hàng kiểm tra → mất uy tín thương hiệu
- Vi phạm pháp luật quảng cáo nếu số liệu sai

---

## 2. Giải Pháp: 4 Tầng Bảo Vệ

```
Tầng 1: VERIFIED DATA REGISTRY    → Pre-load data thật vào prompt
Tầng 2: REFERENCE SOURCES         → Upload file / URL bài báo → AI đọc và trích dẫn
Tầng 3: CITATION RULES            → Buộc AI gắn tag nguồn cho MỖI con số
Tầng 4: POST-GENERATION SCANNER   → Tự động detect & cảnh báo số liệu đáng ngờ
```

---

## 3. Tầng 1: Verified Data Registry

### Concept

Thay vì để AI tự nghĩ ra số liệu, **CẤP SẴN data đã xác minh** vào prompt.
Plugin sẽ có một trang Settings cho phép user nhập/quản lý data thật.

### Data Registry — Lưu trong wp_options hoặc ACF

#### A. Data Nội Bộ XANH (Editable trong Settings)

```php
$xanh_verified_data = [
    // Company Stats
    'projects_completed'     => '47+',
    'years_experience'       => '15',
    'accuracy_rate'          => '98%',
    'accuracy_source'        => 'Đánh giá từ gia chủ sau bàn giao',

    // Price Ranges (update quarterly)
    'price_basic_per_sqm'    => '3.5 triệu/m²',
    'price_standard_per_sqm' => '5.5 triệu/m²',
    'price_premium_per_sqm'  => '8-12 triệu/m²',
    'price_updated_date'     => 'Q1/2026',
    'price_location'         => 'Nha Trang, Khánh Hòa',

    // Technical Data
    'aac_temp_reduction'     => '4°C',
    'aac_price_premium'      => '15%',
    'aac_projects_used'      => '23',
    'construction_timeline'  => '90-120 ngày',

    // Team
    'team_size'              => '...',
    'lead_architect'         => '...',
];
```

#### B. Data Dự Án Thật (Editable — danh sách dự án)

```php
$xanh_projects = [
    [
        'name'     => 'Biệt thự Anh Hoàng',
        'area'     => '120m²',
        'type'     => 'Biệt thự 2 tầng',
        'location' => 'Vĩnh Hải, Nha Trang',
        'duration' => '90 ngày',
        'year'     => '2025',
        'usable'   => true, // cho phép AI đề cập
    ],
    [
        'name'     => 'Nhà phố Anh Đức',
        'area'     => '85m²',
        'type'     => 'Nhà phố 3 tầng',
        'location' => 'Phước Hải, Nha Trang',
        'duration' => '75 ngày',
        'year'     => '2025',
        'usable'   => true,
    ],
    // ... thêm dự án thật
];
```

#### C. Data Ngành (External — có nguồn)

```php
$industry_data = [
    [
        'fact'   => 'Giá thép xây dựng Q1/2026 trung bình 15.500 VNĐ/kg',
        'source' => 'Hiệp hội Thép Việt Nam (VSA)',
        'url'    => 'https://vsa.com.vn/...',
        'date'   => '2026-01',
    ],
    [
        'fact'   => 'Tỷ lệ phát sinh chi phí xây nhà dân dụng: 60-80%',
        'source' => 'Báo cáo xây dựng dân dụng 2025, Bộ Xây dựng',
        'url'    => '',
        'date'   => '2025',
    ],
    // Thêm data ngành đã verify
];
```

### Admin UI — Data Registry Page

```
┌─────────────────────────────────────────────────┐
│ 📊 Data Registry — Số Liệu Đã Xác Minh         │
├─────────────────────────────────────────────────┤
│                                                 │
│  Tab: [XANH Stats] [Dự Án] [Data Ngành]        │
│                                                 │
│  ┌─ XANH Stats ─────────────────────────────┐   │
│  │  Số dự án hoàn thành:  [47+        ]     │   │
│  │  Năm kinh nghiệm:     [15         ]     │   │
│  │  Tỷ lệ sát 3D:        [98%        ]     │   │
│  │  Giá basic/m²:         [3.5 triệu  ]     │   │
│  │  Cập nhật lần cuối:    [Q1/2026    ]     │   │
│  └───────────────────────────────────────────┘   │
│                                                 │
│  ┌─ Dự Án Thật ─────────────────────────────┐   │
│  │  [+ Thêm Dự Án]                          │   │
│  │  ☑ Biệt thự Anh Hoàng — 120m² — 2025   │   │
│  │  ☑ Nhà phố Anh Đức — 85m² — 2025        │   │
│  │  ☐ Villa Chị Mai — 200m² — 2024 (ẩn)    │   │
│  └───────────────────────────────────────────┘   │
│                                                 │
│  [ 💾 Lưu ]                                     │
└─────────────────────────────────────────────────┘
```

### Inject vào Prompt

```
═══ DỮ LIỆU ĐÃ XÁC MINH — CHỈ DÙNG NHỮNG SỐ LIỆU NÀY ═══

XANH - Design & Build:
• Số dự án: {projects_completed}
• Năm kinh nghiệm: {years_experience}
• Tỷ lệ sát 3D: {accuracy_rate} ({accuracy_source})
• Giá tham khảo: Basic {price_basic_per_sqm} | Standard {price_standard_per_sqm}
  | Premium {price_premium_per_sqm} (cập nhật {price_updated_date}, tại {price_location})

Dự án có thể đề cập:
{foreach $project: "• {name} — {area}, {type}, {location}, {year}"}

Data ngành đã xác minh:
{foreach $industry: "• {fact} (Nguồn: {source}, {date})"}
```

---

## 4. Tầng 2: Reference Sources — Upload Tệp & URL Bài Báo

### Concept

Cho phép user **cung cấp nguồn tham khảo thật** cho AI:
- Upload file: PDF, DOCX, TXT, CSV, XLS, MD
- Paste URL: bài báo, nghiên cứu, trang web uy tín
- AI đọc, trích xuất data, và cite nguồn THẬT trong bài viết

```
User upload "bao-cao-vat-lieu-xay-dung-q1-2026.pdf"
    ↓
Plugin extract text → gửi vào Gemini prompt context
    ↓
AI viết: "Theo Báo cáo Vật liệu Xây dựng Q1/2026, giá thép xây dựng
trung bình 15.500 VNĐ/kg, tăng 3% so với cùng kỳ."
    ↓
Citation: nguồn THẬT, số liệu THẬT, có thể verify
```

---

### Loại Sources Hỗ Trợ

| Loại | Formats | Cách xử lý |
|---|---|---|
| **📄 Tệp văn bản** | PDF, DOCX, TXT, MD | Extract text → inject vào prompt |
| **📊 Bảng số liệu** | CSV, XLS, XLSX | Parse → convert sang text table |
| **🔗 URL bài báo** | Any URL | Scrape content → extract main text |
| **📝 Ghi chú thủ công** | Plain text input | User paste trực tiếp |

---

### Reference Sources — 2 Cấp Độ

#### A. Source Library — Thư Viện Nguồn (Cấp Global)

Sources lưu lâu dài, dùng cho NHIỀU bài viết:

```php
// Custom table: {prefix}xanh_ai_sources
$source_schema = [
    'id'           => 'BIGINT AUTO_INCREMENT',
    'title'        => 'VARCHAR(255)',     // "Báo cáo Vật liệu XD Q1/2026"
    'type'         => 'ENUM',             // file / url / note
    'source_url'   => 'TEXT',             // URL gốc hoặc attachment URL
    'attachment_id' => 'BIGINT',          // WP Media Library ID (nếu file)
    'extracted_text' => 'LONGTEXT',       // Nội dung đã extract
    'summary'      => 'TEXT',             // AI summary (auto-generated)
    'key_data'     => 'JSON',             // Số liệu trích xuất [{fact, value, context}]
    'publisher'    => 'VARCHAR(255)',      // "Bộ Xây dựng", "VSA"
    'publish_date' => 'DATE',             // Ngày xuất bản nguồn
    'expiry_date'  => 'DATE',             // Hết hạn (data cũ → cảnh báo)
    'tags'         => 'VARCHAR(255)',      // "vật liệu, giá thép, Q1/2026"
    'is_active'    => 'TINYINT(1)',        // 1 = active, 0 = archived
    'created_at'   => 'DATETIME',
];
```

#### B. Per-Post References — Nguồn Cho Từng Bài (Cấp Bài Viết)

Sources dùng cho 1 bài cụ thể, attach lúc tạo bài:

```
Generator Page → Step 2: Chi Tiết
    ↓
[📎 Thêm Nguồn Tham Khảo]
├── [📄 Upload File] — PDF, DOCX, CSV...
├── [🔗 Paste URL]   — Link bài báo / nghiên cứu
├── [📝 Ghi Chú]     — Paste text trực tiếp
└── [📚 Chọn Từ Thư Viện] — Source Library đã có
```

---

### Admin UI — Source Library

```
┌─────────────────────────────────────────────────────────────────┐
│ 📚 Thư Viện Nguồn Tham Khảo                    [+ Thêm Nguồn] │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  🔍 Tìm kiếm...                   Filter: [Tất cả ▾] [Active] │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │ 📄 Báo cáo Vật liệu Xây dựng Q1/2026                  │    │
│  │    Nguồn: Bộ Xây dựng | Ngày: 01/2026                  │    │
│  │    Tags: vật liệu, giá thép, xi măng                   │    │
│  │    📊 12 số liệu đã trích xuất                          │    │
│  │    [Xem chi tiết] [Sửa] [Archive]                      │    │
│  ├─────────────────────────────────────────────────────────┤    │
│  │ 🔗 Xu hướng nội thất 2026 — Tạp chí Kiến Trúc         │    │
│  │    URL: https://kientruc.com.vn/xu-huong-2026           │    │
│  │    Ngày: 12/2025 | ⚠️ Hết hạn sau 3 tháng              │    │
│  │    📊 5 số liệu đã trích xuất                           │    │
│  │    [Xem chi tiết] [Cập nhật] [Archive]                  │    │
│  ├─────────────────────────────────────────────────────────┤    │
│  │ 📊 Bảng giá nhân công Nha Trang Q1/2026.csv            │    │
│  │    Nguồn: Nội bộ XANH | Ngày: 01/2026                  │    │
│  │    📊 28 dòng dữ liệu                                   │    │
│  │    [Xem chi tiết] [Re-upload]                           │    │
│  └─────────────────────────────────────────────────────────┘    │
│                                                                 │
│  📈 Tổng: 15 sources | 89 số liệu | 3 hết hạn sắp tới        │
└─────────────────────────────────────────────────────────────────┘
```

### Thêm Nguồn — Modal

```
┌─ Thêm Nguồn Tham Khảo ─────────────────────────────────┐
│                                                          │
│  Loại:  (●) Upload File  ( ) URL  ( ) Ghi chú           │
│                                                          │
│  ┌──────────────────────────────────────────────────┐    │
│  │  📤 Kéo thả file hoặc click để chọn             │    │
│  │  PDF, DOCX, TXT, CSV, XLS — Max 10MB            │    │
│  └──────────────────────────────────────────────────┘    │
│                                                          │
│  Tiêu đề:     [Báo cáo Vật liệu XD Q1/2026    ]       │
│  Nhà xuất bản: [Bộ Xây dựng                     ]       │
│  Ngày xuất bản: [01/2026                         ]       │
│  Tags:         [vật liệu, giá thép              ]       │
│                                                          │
│  [ Huỷ ]                            [ 📤 Upload & Xử Lý ]│
└──────────────────────────────────────────────────────────┘
```

---

### Technical Implementation

#### File Processing Pipeline

```php
/**
 * Xử lý file upload → extract text → AI summarize → store
 */
function xanh_ai_process_source_file( int $attachment_id ): array|WP_Error {
    $file_path = get_attached_file( $attachment_id );
    $mime      = get_post_mime_type( $attachment_id );

    // 1. Extract text từ file
    $text = xanh_ai_extract_text( $file_path, $mime );
    if ( is_wp_error( $text ) ) {
        return $text;
    }

    // 2. Truncate nếu quá dài (Gemini context limit)
    $text = xanh_ai_truncate_context( $text, 8000 ); // max ~8000 chars

    // 3. AI summarize + extract key data points
    $analysis = xanh_ai_analyze_source( $text );

    return [
        'extracted_text' => $text,
        'summary'        => $analysis['summary'],
        'key_data'       => $analysis['key_data'], // [{fact, value, context}]
    ];
}

/**
 * Extract text theo format
 */
function xanh_ai_extract_text( string $path, string $mime ): string|WP_Error {
    switch ( $mime ) {
        case 'application/pdf':
            // Dùng pdftotext (nếu có) hoặc PHP library
            if ( function_exists( 'shell_exec' ) ) {
                return shell_exec( "pdftotext " . escapeshellarg( $path ) . " -" );
            }
            // Fallback: gửi file lên Gemini với mime type
            return xanh_ai_gemini_extract_pdf( $path );

        case 'text/csv':
        case 'application/vnd.ms-excel':
            return xanh_ai_parse_csv( $path );

        case 'text/plain':
            return file_get_contents( $path );

        case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
            return xanh_ai_extract_docx( $path );

        default:
            return new WP_Error( 'unsupported', "File type {$mime} không được hỗ trợ." );
    }
}

/**
 * CSV → readable text table
 */
function xanh_ai_parse_csv( string $path ): string {
    $rows   = array_map( 'str_getcsv', file( $path ) );
    $header = array_shift( $rows );
    $text   = "Bảng dữ liệu:\n";
    $text  .= implode( ' | ', $header ) . "\n";
    $text  .= str_repeat( '─', 60 ) . "\n";

    foreach ( $rows as $row ) {
        $text .= implode( ' | ', $row ) . "\n";
    }

    return $text;
}
```

#### URL Scraping

```php
/**
 * Scrape URL → extract main content
 */
function xanh_ai_process_source_url( string $url ): array|WP_Error {
    // 1. Validate URL
    if ( ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
        return new WP_Error( 'invalid_url', 'URL không hợp lệ.' );
    }

    // 2. Fetch page
    $response = wp_remote_get( $url, [
        'timeout'    => 30,
        'user-agent' => 'XANH-AI-Bot/1.0',
    ] );

    if ( is_wp_error( $response ) ) {
        return $response;
    }

    $html = wp_remote_retrieve_body( $response );

    // 3. Extract main content (strip nav, footer, ads, scripts)
    $text = xanh_ai_extract_article_text( $html );

    // 4. Truncate
    $text = xanh_ai_truncate_context( $text, 6000 );

    // 5. AI analyze
    $analysis = xanh_ai_analyze_source( $text );

    // 6. Extract meta
    preg_match( '/<title>(.+?)<\/title>/i', $html, $title_match );

    return [
        'title'          => $title_match[1] ?? parse_url( $url, PHP_URL_HOST ),
        'extracted_text' => $text,
        'summary'        => $analysis['summary'],
        'key_data'       => $analysis['key_data'],
    ];
}

/**
 * Strip HTML → lấy article text chính
 */
function xanh_ai_extract_article_text( string $html ): string {
    // Remove script, style, nav, footer, sidebar
    $html = preg_replace( '/<(script|style|nav|footer|aside|header)[^>]*>.*?<\/\1>/si', '', $html );
    // Remove tags, keep text
    $text = wp_strip_all_tags( $html );
    // Clean whitespace
    $text = preg_replace( '/\s+/', ' ', $text );

    return trim( $text );
}
```

#### AI Source Analyzer — Trích Xuất Số Liệu Tự Động

```php
/**
 * Gửi text nguồn → AI trả về summary + key data points
 */
function xanh_ai_analyze_source( string $raw_text ): array {
    $prompt = <<<PROMPT
Đọc văn bản sau và thực hiện 2 việc:

1. SUMMARY: Tóm tắt ngắn gọn (2-3 câu) nội dung chính.
2. KEY_DATA: Trích xuất TẤT CẢ số liệu, thống kê, con số cụ thể.
   Mỗi data point gồm: fact (mô tả), value (giá trị), context (ngữ cảnh).

Văn bản:
---
{$raw_text}
---

Trả về JSON:
{
  "summary": "...",
  "key_data": [
    {"fact": "Giá thép xây dựng", "value": "15.500 VNĐ/kg", "context": "Q1/2026, trung bình cả nước"},
    {"fact": "Tăng giá so với cùng kỳ", "value": "3%", "context": "So với Q1/2025"}
  ]
}
PROMPT;

    $result = xanh_ai_call_gemini_text( $prompt );

    if ( is_wp_error( $result ) ) {
        return [ 'summary' => '', 'key_data' => [] ];
    }

    return $result;
}
```

---

### Inject Sources Vào AI Prompt

Khi generate bài viết, sources được inject như context:

```
═══ NGUỒN THAM KHẢO — SỬ DỤNG LÀM CĂN CỨ ═══

📄 Nguồn 1: "Báo cáo Vật liệu Xây dựng Q1/2026" (Bộ Xây dựng, 01/2026)
Tóm tắt: Giá vật liệu xây dựng tăng nhẹ 2-3% so với cùng kỳ...
Số liệu chính:
• Giá thép xây dựng: 15.500 VNĐ/kg (trung bình cả nước)
• Giá xi măng: 1.850 VNĐ/kg (PCB40)
• Giá gạch AAC: 1.250.000 VNĐ/m³

🔗 Nguồn 2: "Xu hướng nội thất 2026" (Tạp chí Kiến Trúc, 12/2025)
URL: https://kientruc.com.vn/xu-huong-2026
Tóm tắt: Minimalism bền vững dẫn đầu xu hướng...
Số liệu chính:
• 67% dự án cao cấp sử dụng vật liệu tái chế
• Tone màu chủ đạo: warm neutral (cream, beige, sage green)

📊 Nguồn 3: "Bảng giá nhân công Nha Trang Q1/2026" (Nội bộ XANH)
Số liệu chính:
• Thợ xây: 400.000-500.000 VNĐ/ngày
• Thợ điện: 450.000-600.000 VNĐ/ngày
• Thợ sơn: 350.000-450.000 VNĐ/ngày

QUY TẮC:
- Khi dùng số liệu từ nguồn → PHẢI cite: "Theo [tên nguồn]..."
- Nếu nguồn có URL → tạo hyperlink trong bài
- Được phép diễn giải nhưng KHÔNG thay đổi con số
- Nếu 2 nguồn conflict → dùng nguồn mới hơn + ghi chú
```

---

### Generator Page — Updated UI

```
┌─ Step 2: Chi Tiết ──────────────────────────────────────┐
│  Topic:        [Chi phí xây nhà phố 2026         ]      │
│  Keyword:      [chi phí xây nhà phố              ]      │
│  Keywords phụ: [giá xây nhà nha trang            ]      │
│                                                          │
│  📎 Nguồn Tham Khảo                                     │
│  ┌───────────────────────────────────────────────────┐   │
│  │ 📄 Báo cáo Vật liệu XD Q1/2026          [✕ Xoá] │   │
│  │    Bộ Xây dựng | 12 số liệu | [Xem ▾]           │   │
│  │ 🔗 kientruc.com.vn/xu-huong-2026         [✕ Xoá] │   │
│  │    Tạp chí Kiến Trúc | 5 số liệu | [Xem ▾]      │   │
│  └───────────────────────────────────────────────────┘   │
│  [📄 Upload File] [🔗 Thêm URL] [📚 Từ Thư Viện]      │
│                                                          │
│  Ghi chú:      [Focus vào giá Nha Trang         ]      │
└──────────────────────────────────────────────────────────┘
```

---

### Source Expiry — Tự Động Cảnh Báo Data Cũ

```php
/**
 * Check sources hết hạn (> 6 tháng hoặc custom expiry)
 */
function xanh_ai_check_expired_sources(): array {
    global $wpdb;
    $table = $wpdb->prefix . 'xanh_ai_sources';

    return $wpdb->get_results( $wpdb->prepare(
        "SELECT id, title, publish_date, expiry_date FROM {$table}
         WHERE is_active = 1
         AND (expiry_date IS NOT NULL AND expiry_date < %s
              OR (expiry_date IS NULL AND publish_date < %s))",
        current_time( 'mysql' ),
        gmdate( 'Y-m-d', strtotime( '-6 months' ) )
    ) );
}
```

Admin notification: "⚠️ 3 nguồn tham khảo đã quá 6 tháng — cần cập nhật."

---

### Security — File Upload

```php
// Chỉ cho phép formats an toàn
$allowed_mimes = [
    'pdf'  => 'application/pdf',
    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'txt'  => 'text/plain',
    'csv'  => 'text/csv',
    'xls'  => 'application/vnd.ms-excel',
    'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
];

// Max file size: 10MB
$max_size = 10 * MB_IN_BYTES;

// Sanitize filename
$filename = sanitize_file_name( $uploaded['name'] );

// Capability check
if ( ! current_user_can( 'upload_files' ) ) {
    wp_die( 'Không có quyền upload file.' );
}
```

---

## 5. Tầng 3: Citation Rules — Buộc AI Gắn Tag

### Prompt Rule

```
═══ QUY TẮC SỐ LIỆU — TUYỆT ĐỐI TUÂN THỦ ═══

1. CHỈ sử dụng số liệu từ "DỮ LIỆU ĐÃ XÁC MINH" ở trên.
2. Nếu KHÔNG có data thật → KHÔNG bịa số. Thay bằng:
   - Miêu tả định tính: "đáng kể", "rõ rệt"
   - Hoặc ghi [CẦN BỔ SUNG DATA] để editor thêm sau
3. Mỗi con số trong bài PHẢI thuộc 1 trong 3 loại:
   ✅ [XANH] — Data nội bộ đã xác minh: "47+ dự án", "98% sát 3D"
   ✅ [NGUỒN] — Data ngành có nguồn: "15.500 VNĐ/kg (VSA, Q1/2026)"
   ⚠️ [ƯỚC TÍNH] — Ước tính hợp lý, ghi rõ: "khoảng", "ước tính"
4. TUYỆT ĐỐI KHÔNG:
   ❌ Bịa % (VD: "73% gia chủ cho rằng...")
   ❌ Bịa nguồn (VD: "Theo khảo sát ABC 2025...")
   ❌ Bịa tiêu chuẩn (VD: "Theo TCVN 9999:2025...")
   ❌ Phóng đại data XANH (VD: "200+ dự án" khi thực tế 47+)
```

### Output Tagging

AI trả content_html với hidden data tags:

```html
<!-- Trong JSON output, thêm field citation_map -->
{
  "content_html": "...",
  "citation_map": [
    { "number": "47+",  "type": "xanh",    "source": "Internal project count" },
    { "number": "98%",  "type": "xanh",    "source": "Client feedback surveys" },
    { "number": "4°C",  "type": "xanh",    "source": "AAC field measurement" },
    { "number": "15%",  "type": "estimate", "source": "Based on recent quotes" },
    { "number": "15.500 VNĐ/kg", "type": "external", "source": "VSA Q1/2026" }
  ]
}
```

---

## 6. Tầng 4: Post-Generation Scanner

### Tự Động Detect Số Liệu Đáng Ngờ

Plugin scan content sau khi generate, TRƯỚC khi hiển thị preview:

```php
function xanh_ai_scan_data_integrity( string $content, array $verified ): array {
    $warnings = [];

    // 1. Tìm tất cả số liệu và % trong content
    preg_match_all( '/(\d+[\.,]?\d*)\s*(%|triệu|tỷ|m²|°C|VNĐ|kg)/u', $content, $matches );

    foreach ( $matches[0] as $number_str ) {
        // 2. Check xem number có trong verified data không
        $is_verified = xanh_ai_is_number_verified( $number_str, $verified );

        if ( ! $is_verified ) {
            $warnings[] = [
                'number'  => $number_str,
                'type'    => 'unverified',
                'message' => "Số liệu '{$number_str}' không nằm trong Data Registry. Cần xác minh.",
            ];
        }
    }

    // 3. Check "Theo/Nguồn" citations
    preg_match_all( '/[Tt]heo\s+([^,\.]+)/u', $content, $sources );
    foreach ( $sources[1] as $source ) {
        if ( ! xanh_ai_is_source_verified( $source, $verified ) ) {
            $warnings[] = [
                'source'  => $source,
                'type'    => 'unverified_source',
                'message' => "Nguồn '{$source}' chưa được xác minh. Có thể AI bịa.",
            ];
        }
    }

    // 4. Check TCVN/TCXDVN codes
    preg_match_all( '/TC(?:VN|XDVN)\s*\d+/u', $content, $standards );
    foreach ( $standards[0] as $std ) {
        $warnings[] = [
            'standard' => $std,
            'type'     => 'verify_standard',
            'message'  => "Tiêu chuẩn '{$std}' — cần xác minh mã có tồn tại không.",
        ];
    }

    return $warnings;
}
```

### UI — Warning Panel Trong Preview

```
┌─ ⚠️ Kiểm Tra Số Liệu ────────────────────────────────┐
│                                                        │
│  ✅ "47+ dự án" — [XANH] Đã xác minh                  │
│  ✅ "98% sát 3D" — [XANH] Đã xác minh                 │
│  ✅ "15.500 VNĐ/kg" — [NGUỒN] VSA, Q1/2026            │
│                                                        │
│  ⚠️ "73% gia chủ" — KHÔNG CÓ TRONG REGISTRY           │
│     → [Xác minh] [Xoá] [Đổi thành ước tính]           │
│                                                        │
│  ⚠️ "Theo khảo sát của Bộ Xây dựng 2025"              │
│     — Nguồn chưa xác minh. Có thể AI bịa.             │
│     → [Thêm nguồn thật] [Xoá nguồn] [Sửa lại]        │
│                                                        │
│  ⚠️ "TCVN 9394:2012" — Cần xác minh mã tiêu chuẩn    │
│     → [Đã kiểm tra ✓] [Mã sai, sửa lại]              │
│                                                        │
│  Tổng: 3 ✅ verified | 3 ⚠️ cần kiểm tra              │
└────────────────────────────────────────────────────────┘
```

---

## 7. Chiến Lược Khi Không Có Data

### Khi AI không có số liệu thật — 5 cách viết thay thế:

| Tình huống | Thay vì bịa số... | Viết thế này ✅ |
|---|---|---|
| Không biết % chính xác | ❌ "73% gia chủ cho rằng..." | ✅ "Phần lớn gia chủ XANH đã bàn giao đều chia sẻ rằng..." |
| Không có data so sánh | ❌ "Gạch AAC rẻ hơn 20%" | ✅ "Theo báo giá gần nhất tại Nha Trang, gạch AAC có giá cao hơn gạch đỏ — nhưng bù lại bằng tiết kiệm năng lượng dài hạn." |
| Không có giá chính xác | ❌ "Chi phí từ 2.5 tỷ" | ✅ "Chi phí phụ thuộc vào diện tích, vật liệu, và mức hoàn thiện. [Khám phá dự toán riêng →](/du-toan/)" |
| Không có survey | ❌ "Theo khảo sát 2025..." | ✅ "Từ kinh nghiệm {N} công trình, chúng tôi nhận thấy..." |
| Không có tiêu chuẩn cụ thể | ❌ "Theo TCVN 9999..." | ✅ "Theo quy chuẩn xây dựng hiện hành" (chung, không bịa mã) |

### Template cho AI

```
KHI KHÔNG CÓ DATA:
• Dùng trải nghiệm nội bộ: "Từ {projects_completed} công trình đã bàn giao..."
• Dùng định tính: "Đáng kể", "Rõ rệt", "Phần lớn"
• Dùng CTA thay data: "Để biết chi phí chính xác cho trường hợp của bạn → [Dự toán riêng]"
• Ghi placeholder: "[CẦN BỔ SUNG: số liệu giá vật liệu Q1/2026]"

KHÔNG BAO GIỜ:
• Bịa %: "73%", "85%", "92%" — nếu không có nguồn
• Bịa nguồn: "Theo báo cáo ABC...", "Nghiên cứu XYZ cho thấy..."
• Bịa mã tiêu chuẩn: "TCVN 1234:2025"
• Dùng "theo thống kê" khi không có thống kê nào
```

---

## 8. Quy Trình Editor Review

### Trước khi publish — Editor phải:

```
1. Mở Preview → Xem panel "Kiểm Tra Số Liệu"
2. Mỗi ⚠️ warning:
   ├── Nếu có source thật → Click [Thêm nguồn thật] → nhập URL/nguồn
   ├── Nếu số đúng nhưng approximate → Click [Đổi thành ước tính]
   ├── Nếu AI bịa → Click [Xoá] hoặc [Sửa lại]
   └── Nếu là tiêu chuẩn → Google verify mã → Click [Đã kiểm tra ✓]
3. Khi TẤT CẢ warnings resolved → mới cho phép "Lưu Draft"
```

### Enforce trong Plugin

```php
// Nếu có warnings unresolved → disable Save Draft button
function xanh_ai_can_save_draft( array $warnings ): bool {
    $unresolved = array_filter( $warnings, function ( $w ) {
        return ! isset( $w['resolved'] ) || ! $w['resolved'];
    } );

    return empty( $unresolved );
}
```

### Settings Option: Strict Mode

| Mode | Behavior |
|---|---|
| **Strict** (mặc định) | Không cho Save Draft nếu còn warning unresolved |
| **Moderate** | Cho Save Draft nhưng gắn flag `_needs_data_review` vào post meta |
| **Lenient** | Chỉ hiển thị warning, không block |

---

## 9. Kết Hợp Với Content Score

Thêm tiêu chí data integrity vào Content Score (`PLUGIN_AI_SEO.md` §6):

| Tiêu chí mới | Điểm | Check |
|---|---|---|
| Không có số liệu bịa | 10 | 0 unverified warnings |
| Mọi nguồn đều verified | 5 | Tất cả "Theo..." đã xác minh |

**Score impact:** Nếu có ≥ 1 unverified number → max score bị cap ở 70/100.

---

## Tài Liệu Liên Quan

- `PLUGIN_AI_BRAND_VOICE.md` §6 — E-E-A-T embedding
- `PLUGIN_AI_PROMPTS.md` §3 — Full system prompt
- `PLUGIN_AI_SEO.md` §6 — Content Score
- `PLUGIN_AI_ADMIN.md` §1 — Settings page
