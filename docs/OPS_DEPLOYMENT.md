# OPS_DEPLOYMENT — Triển Khai

> **Dự án:** Website XANH - Design & Build
> **Ngày tạo:** 2026-03-12

---

## 1. Môi Trường

| Env | Tool | URL | Mục đích |
|---|---|---|---|
| **Local** | LocalWP | `xanhdesignbuild.local` | Development |
| **Staging** | Hosting staging subdomain | `staging.[domain]` | Testing |
| **Production** | Hosting chính | `[domain].vn` | Live |

---

## 2. Go-Live Checklist

### Trước khi deploy
- [ ] Tất cả pages hoạt động đúng (6 trang chính)
- [ ] Forms gửi thành công + email nhận được
- [ ] Responsive test (mobile, tablet, desktop)
- [ ] Cross-browser test (Chrome, Safari, Firefox, Edge)
- [ ] PageSpeed > 90 (cả mobile + desktop)
- [ ] SSL cài đặt + force HTTPS
- [ ] Favicon + meta tags
- [ ] robots.txt + sitemap.xml
- [ ] Google Analytics + FB Pixel hoạt động
- [ ] CTA tracking events hoạt động
- [ ] Cookie Consent banner
- [ ] 404 page custom
- [ ] Thank-you pages tạo xong
- [ ] Backup strategy configured

### Sau khi deploy
- [ ] DNS pointing đúng
- [ ] SSL hoạt động (kiểm tra https://)
- [ ] Google Search Console submitted
- [ ] Google My Business linked
- [ ] Test forms trên production
- [ ] Monitor uptime 24h đầu

---

## 3. Backup Strategy

| Loại | Tần suất | Giữ |
|---|---|---|
| Full backup (files + DB) | Weekly | 4 bản gần nhất |
| Database only | Daily | 7 bản |
| Pre-update backup | Before plugin/theme updates | 1 bản |

---

## 4. Domain & DNS

| Setting | Giá trị |
|---|---|
| Domain | `[domain].vn` |
| www redirect | `www` → non-www (301) |
| SSL | Let's Encrypt hoặc hosting SSL |
| HSTS | Enable |

---

## Tài Liệu Liên Quan

- `OPS_TESTING.md` — Testing procedures
- `OPS_MONITORING.md` — Tracking setup
- `ARCH_PERFORMANCE.md` — Performance targets
