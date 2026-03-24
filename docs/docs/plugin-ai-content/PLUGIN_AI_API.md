# PLUGIN_AI_API — Gemini API Integration

> **Plugin:** XANH AI Content Generator
> **APIs:** Gemini 2.5 Flash/Pro (text) + Gemini 3.1 Flash Image + Imagen 4 (image)
> **Cập nhật:** 2026-03-24

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

### Request Body (Thinking Models — gemini-2.5-*)

> **QUAN TRỌNG:** Gemini 2.5 là thinking model — KHÔNG hỗ trợ `temperature` và `responseSchema`.
> Phải dùng `thinkingConfig` thay thế.

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
    "maxOutputTokens": 16384,
    "responseMimeType": "application/json",
    "thinkingConfig": { "thinkingBudget": 8192 }
  }
}
```

### Thinking Model Constraints

| Parameter | gemini-2.5-* | Non-thinking models |
|---|---|---|
| `temperature` | ❌ NOT supported | ✅ 0.0 - 2.0 |
| `responseSchema` | ❌ NOT supported | ✅ Structured output |
| `thinkingConfig` | ✅ Required | ❌ N/A |
| `thinkingBudget: 0` | Disable thinking (fast utility calls) | — |
| `thinkingBudget: 8192` | Normal content generation | — |

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
// In Xanh_AI_API::generate_text()
$gen_config = [
    'maxOutputTokens' => 16384,
    'responseMimeType' => $mime_type,
];

// Thinking models (gemini-2.5-*) don't support temperature or responseSchema.
if (str_starts_with($model, 'gemini-2.5')) {
    $gen_config['thinkingConfig'] = ['thinkingBudget' => 8192];
} else {
    $gen_config['temperature'] = (float) get_option('xanh_ai_temperature', 0.7);
    if ('application/json' === $mime_type) {
        $gen_config['responseSchema'] = self::get_content_schema();
    }
}

// For text/plain responses, strip markdown code fences:
$text = preg_replace('/^\s*```(?:html|htm)?\s*\n?/i', '', $text);
$text = preg_replace('/\n?\s*```\s*$/i', '', $text);
```

### Available Text Models

| Model ID | Tên hiển thị | Ghi chú |
|---|---|---|
| `gemini-2.5-flash` | Gemini 2.5 Flash | ✅ Khuyến nghị — cân bằng tốc độ/chi phí |
| `gemini-2.5-flash-lite` | Gemini 2.5 Flash Lite | Nhanh, rẻ, output ngắn hơn |
| `gemini-2.5-pro` | Gemini 2.5 Pro | Chất lượng cao nhất, chậm hơn |

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

### Vietnamese Context Suffix (Auto-appended)

Mọi image prompt được **tự động append Vietnamese context** ở code level:

```php
// In Xanh_AI_API::generate_image()
$vn_suffix = ', set in Vietnam, Vietnamese people, Nha Trang coastal city, '
           . 'bright airy natural sunlight, fresh clean luminous tone, '
           . 'warm luxury, editorial photography';
if (stripos($prompt, 'Vietnam') === false) {
    $prompt .= $vn_suffix;
}
```

Đảm bảo **100% ảnh** có bối cảnh Việt Nam, tone tươi sáng dù AI "quên" instructions.

### Available Image Models

| Model ID | Tên hiển thị |
|---|---|
| `gemini-3.1-flash-image-preview` | Gemini 3.1 Flash Image (khuyến nghị) |
| `imagen-4.0-generate-001` | Imagen 4.0 |
| `imagen-4.0-fast-generate-001` | Imagen 4.0 Fast |

---

## 3. In-Content Images (section_images) — ĐÃ TRIỂN KHAI

AI tự tạo image prompt cho **mỗi H2 section** trong output JSON:

```json
"section_images": [
  {"after_h2": "Tiêu đề H2 section 1", "prompt": "Editorial photography prompt..."},
  {"after_h2": "Tiêu đề H2 section 2", "prompt": "..."}
]
```

### Image Prompt Requirements (BẮT BUỘC)
- Bối cảnh: Vietnam, Nha Trang coastal city, tropical climate
- Con người: Vietnamese people, Vietnamese family, Vietnamese homeowner
- Kiến trúc: Vietnamese modern home, local materials
- Tone ảnh: bright, airy, fresh, natural sunlight, clean and luminous
- KHÔNG: dark moody, dramatic shadows, Western/European setting
- Style: editorial photography, architectural photography

### UI Flow
- Nút "🖼 Tạo Ảnh" xuất hiện cạnh mỗi H2 trong preview
- Click → modal hiển thị prompt → user có thể chỉnh → Generate
- Ảnh tải lên Media Library → insert vào `<figure>` sau H2

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
| Rate limited (429) | `api_error` | "API quá tải. Vui lòng đợi 1 phút rồi thử lại." |
| Image timeout | `api_error` | "Tạo ảnh quá thời gian (>120s). API có thể đang quá tải." |
| Upload fail | `upload_error` | "Không thể lưu hình ảnh. Kiểm tra quyền thư mục uploads." |

---

## Tài Liệu Liên Quan

- [Gemini API Image Generation Docs](https://ai.google.dev/gemini-api/docs/image-generation)
- `PLUGIN_AI_ARCHITECTURE.md` — Data flow
- `PLUGIN_AI_SECURITY.md` — API key encryption
