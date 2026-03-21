# PLUGIN_AI_API — Gemini API Integration

> **Plugin:** XANH AI Content Generator
> **APIs:** Gemini 2.5 Flash (text) + Gemini 3.1 Flash Image (image)
> **Cập nhật:** 2026-03-20

---

## 1. Text Generation — Gemini 2.5 Flash

### Endpoint
```
POST https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent
```

### Headers
```
x-goog-api-key: {GEMINI_API_KEY}
Content-Type: application/json
```

### Request Body
```json
{
  "contents": [
    {
      "role": "user",
      "parts": [
        {
          "text": "{system_prompt}\n\n---\n\nTopic: {topic}\nPrimary Keyword: {keyword}\nSecondary: {secondary}\nNotes: {notes}"
        }
      ]
    }
  ],
  "generationConfig": {
    "temperature": 0.7,
    "maxOutputTokens": 4000,
    "responseMimeType": "application/json"
  }
}
```

### Response Structure
```json
{
  "candidates": [
    {
      "content": {
        "parts": [
          {
            "text": "{JSON string với title, slug, meta, content_html, tags, faq, image_prompt}"
          }
        ]
      },
      "finishReason": "STOP"
    }
  ],
  "usageMetadata": {
    "promptTokenCount": 1200,
    "candidatesTokenCount": 3500,
    "totalTokenCount": 4700
  }
}
```

### PHP Implementation Pattern
```php
function xanh_ai_call_gemini_text( string $prompt, array $config = [] ): array|WP_Error {
    $api_key = xanh_ai_decrypt_key( get_option( 'xanh_ai_gemini_key' ) );
    if ( empty( $api_key ) ) {
        return new WP_Error( 'no_api_key', 'Gemini API key chưa được cấu hình.' );
    }

    $model = get_option( 'xanh_ai_text_model', 'gemini-2.5-flash' );
    $url   = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent";

    $body = [
        'contents' => [
            [
                'role'  => 'user',
                'parts' => [ [ 'text' => $prompt ] ],
            ],
        ],
        'generationConfig' => [
            'temperature'      => (float) get_option( 'xanh_ai_temperature', 0.7 ),
            'maxOutputTokens'  => 4000,
            'responseMimeType' => 'application/json',
        ],
    ];

    $response = wp_remote_post( $url, [
        'headers' => [
            'Content-Type'   => 'application/json',
            'x-goog-api-key' => $api_key,
        ],
        'body'    => wp_json_encode( $body ),
        'timeout' => 60,
    ] );

    if ( is_wp_error( $response ) ) {
        return $response;
    }

    $code = wp_remote_retrieve_response_code( $response );
    if ( $code !== 200 ) {
        return new WP_Error( 'api_error', "Gemini API error: HTTP {$code}" );
    }

    $data = json_decode( wp_remote_retrieve_body( $response ), true );
    $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
    $tokens = $data['usageMetadata']['totalTokenCount'] ?? 0;

    $parsed = json_decode( $text, true );
    if ( json_last_error() !== JSON_ERROR_NONE ) {
        return new WP_Error( 'parse_error', 'Không thể parse response từ AI.' );
    }

    $parsed['_tokens'] = $tokens;
    return $parsed;
}
```

---

## 2. Image Generation — Gemini 3.1 Flash Image

### Endpoint
```
POST https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-flash-image-preview:generateContent
```

### Request Body
```json
{
  "contents": [
    {
      "parts": [
        { "text": "{image_prompt}" }
      ]
    }
  ],
  "generationConfig": {
    "responseModalities": ["Image"],
    "imageConfig": {
      "aspectRatio": "16:9",
      "imageSize": "2K"
    }
  }
}
```

### Aspect Ratios hỗ trợ

| Ratio | Use Case |
|---|---|
| `1:1` | Social media, thumbnail |
| `16:9` | Featured image blog (default) |
| `4:3` | Standard landscape |
| `3:4` | Portrait |
| `9:16` | Story/Vertical |

### Image Sizes (Gemini 3.1)

| Size | Resolution |
|---|---|
| `1K` | 1024px |
| `2K` | 2048px (recommended) |
| `4K` | 4096px |

### Response — Image in Base64
```json
{
  "candidates": [
    {
      "content": {
        "parts": [
          {
            "inlineData": {
              "mimeType": "image/png",
              "data": "{base64_encoded_image}"
            }
          }
        ]
      }
    }
  ]
}
```

### PHP Implementation Pattern
```php
function xanh_ai_generate_image( string $prompt ): int|WP_Error {
    $api_key = xanh_ai_decrypt_key( get_option( 'xanh_ai_gemini_key' ) );
    $model   = get_option( 'xanh_ai_image_model', 'gemini-3.1-flash-image-preview' );
    $url     = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent";

    $body = [
        'contents' => [
            [ 'parts' => [ [ 'text' => $prompt ] ] ],
        ],
        'generationConfig' => [
            'responseModalities' => [ 'Image' ],
            'imageConfig' => [
                'aspectRatio' => get_option( 'xanh_ai_image_aspect', '16:9' ),
                'imageSize'   => get_option( 'xanh_ai_image_size', '2K' ),
            ],
        ],
    ];

    $response = wp_remote_post( $url, [
        'headers' => [
            'Content-Type'   => 'application/json',
            'x-goog-api-key' => $api_key,
        ],
        'body'    => wp_json_encode( $body ),
        'timeout' => 120,
    ] );

    if ( is_wp_error( $response ) ) {
        return $response;
    }

    $data      = json_decode( wp_remote_retrieve_body( $response ), true );
    $image_b64 = $data['candidates'][0]['content']['parts'][0]['inlineData']['data'] ?? '';
    $mime      = $data['candidates'][0]['content']['parts'][0]['inlineData']['mimeType'] ?? 'image/png';

    if ( empty( $image_b64 ) ) {
        return new WP_Error( 'no_image', 'API không trả về hình ảnh.' );
    }

    return xanh_ai_upload_base64_image( $image_b64, $mime );
}

function xanh_ai_upload_base64_image( string $base64, string $mime ): int|WP_Error {
    $image_data = base64_decode( $base64 );
    $ext   = ( $mime === 'image/png' ) ? 'png' : 'webp';
    $fname = 'xanh-ai-' . wp_generate_uuid4() . '.' . $ext;

    $upload = wp_upload_bits( $fname, null, $image_data );
    if ( ! empty( $upload['error'] ) ) {
        return new WP_Error( 'upload_error', $upload['error'] );
    }

    $attachment_id = wp_insert_attachment( [
        'post_mime_type' => $mime,
        'post_title'     => sanitize_file_name( $fname ),
        'post_status'    => 'inherit',
    ], $upload['file'] );

    require_once ABSPATH . 'wp-admin/includes/image.php';
    $meta = wp_generate_attachment_metadata( $attachment_id, $upload['file'] );
    wp_update_attachment_metadata( $attachment_id, $meta );

    return $attachment_id;
}
```

### Image Prompt Builder
```php
function xanh_ai_build_image_prompt( string $title, string $angle_id ): string {
    $angle  = Xanh_AI_Angles::get( $angle_id );
    $style  = $angle['image_style'] ?? 'professional interior design';

    $prompt = sprintf(
        'Professional %s photograph, editorial style, warm ambient lighting, '
        . 'cream and emerald green color palette, natural warm tones, '
        . 'topic: %s, high quality architectural photography, '
        . 'no text overlay, no watermark, clean composition, luxury feel',
        $style,
        $title
    );

    return apply_filters( 'xanh_ai_image_prompt', $prompt, $title, $angle_id );
}
```

---

## 3. In-Content Images [Phase 2]

Tạo thêm 2-3 ảnh minh họa xen trong bài viết, tại mỗi H2 section chính.

### Logic
1. Parse `content_html` → extract H2 headings
2. Chọn 2-3 H2 sections quan trọng nhất
3. Build image prompt cho mỗi section (context = H2 title + paragraph đầu)
4. Call Gemini Imagen API cho mỗi ảnh
5. Insert `<figure><img /><figcaption /></figure>` sau mỗi H2 tương ứng
6. Alt text = H2 title + primary keyword

### Giới hạn
- Max 3 in-content images (tránh API cost quá cao)
- Lazy load: `loading="lazy"` cho in-content images
- Featured image vẫn dùng `fetchpriority="high"`

---

## 4. Supported Languages

Gemini 3.1 Flash Image hỗ trợ tiếng Việt (`vi-VN`) cho cả text prompt.

> **Lưu ý:** Ảnh AI generate có SynthID watermark (invisible). Theo `GOV_BRAND_VOICE.md` §5:
> ảnh AI chỉ dùng minh họa blog, KHÔNG dùng cho portfolio/showcase.

---

## 5. Error Handling

| Error | Code | User Message |
|---|---|---|
| No API key | `no_api_key` | "Vui lòng cấu hình Gemini API Key trong Cài Đặt." |
| HTTP error | `api_error` | "Lỗi kết nối API. Vui lòng thử lại." |
| Parse error | `parse_error` | "AI trả về dữ liệu không hợp lệ. Vui lòng thử lại." |
| No image | `no_image` | "Không thể tạo hình ảnh. Vui lòng thử prompt khác." |
| Rate limited | `rate_limited` | "Vui lòng đợi 30 giây trước khi tạo tiếp." |
| Upload fail | `upload_error` | "Không thể lưu hình ảnh. Kiểm tra quyền thư mục uploads." |

---

## Tài Liệu Liên Quan

- [Gemini API Image Generation Docs](https://ai.google.dev/gemini-api/docs/image-generation)
- `PLUGIN_AI_ARCHITECTURE.md` — Data flow
- `PLUGIN_AI_SECURITY.md` — API key encryption
