# TRACK_BLUEPRINT — Blueprint & Lộ Trình

> **Dự án:** Website XANH - Design & Build
> **Cập nhật:** 2026-03-12

---

## Sprint Roadmap

### Sprint 1 — Wireframe & UI Mockup 🔴
**⏱ Ước lượng: 2 tuần (10 ngày công)**

| Task | Output | Owner | Ngày |
|---|---|---|---|
| Wireframe tất cả 7 trang (+Global Elements) | Figma wireframes | UI/UX | 3d |
| UI Mockup Home + Portfolio | High-fidelity mockups | UI/UX | 4d |
| UI Mockup remaining pages | All pages mockup | UI/UX | 2d |
| Review & approve design | Sign-off | Stakeholder | 1d |
| Setup local dev (LocalWP) | Working WordPress | Dev | 0.5d |
| Custom theme boilerplate | `xanh-theme` scaffold | Dev | 0.5d |

### Sprint 2 — Front-end Custom Theme 🔴
**⏱ Ước lượng: 3 tuần (15 ngày công)**

| Task | Output | Ngày |
|---|---|---|
| Design tokens (CSS variables) | `variables.css` + `utilities.css` | 1d |
| Header + Footer + Navigation | `header.php`, `footer.php`, mobile nav | 2d |
| HomePage (10 sections) | `front-page.php` + 10 template-parts | 4d |
| UI Components build (27 items) | Template parts + CSS + JS | 4d |
| Responsive (mobile-first) | All breakpoints tested | 2d |
| Before/After Slider + Gallery | Slider + PhotoSwipe | 1d |
| Scroll animations + Preloader | IntersectionObserver + CSS | 1d |

### Sprint 3 — Back-end & Features 🟠
**⏱ Ước lượng: 3 tuần (15 ngày công)**

| Task | Output | Ngày |
|---|---|---|
| CPTs + ACF Fields setup | 3 CPTs + 5 field groups | 2d |
| Estimator plugin | `xanh-estimator` plugin | 3d |
| Fluent Form setup (5 forms) | Forms + SMTP + notifications | 1d |
| Thank-you pages + 404 | 3 pages | 0.5d |
| Portfolio page (grid + detail) | Templates + AJAX filter + skeleton | 3d |
| Blog page (list + detail) | Templates + ToC + search + progress bar | 2d |
| About, Green Solution, Contact | 3 page templates | 2d |
| Zalo OA + Floating CTA | Widget + mobile bar | 0.5d |
| Content entry (test data) | Min 3 projects, 5 posts | 1d |

### Sprint 4 — SEO, Security & Performance 🟠
**⏱ Ước lượng: 2 tuần (10 ngày công)**

| Task | Output | Ngày |
|---|---|---|
| Security hardening (.htaccess, headers) | `GOV_SECURITY.md` applied | 1d |
| LiteSpeed Cache config | Cache, minify, critical CSS | 1d |
| Smush image optimization | WebP conversion, lazy load | 0.5d |
| SEO: Schema markup | LocalBusiness, FAQ, Breadcrumb, Article | 1d |
| SEO: Meta tags + sitemap | All pages + sitemap.xml | 1d |
| SEO: Google Business Profile setup | GMB profile | 0.5d |
| PageSpeed audit + fixes | Score > 90 | 2d |
| Cross-browser + responsive testing | `OPS_TESTING.md` checklist | 2d |
| Accessibility audit | WCAG AA pass | 1d |

### Sprint 5 — Go-Live & Tracking 🟢
**⏱ Ước lượng: 1 tuần (5 ngày công)**

| Task | Output | Ngày |
|---|---|---|
| Google Analytics 4 setup | Events + conversions | 1d |
| Facebook Pixel setup | Events | 0.5d |
| CTA tracking tags | All CTA buttons tracked | 0.5d |
| DNS + SSL + domain config | Domain live | 0.5d |
| Final testing (all checklists) | `OPS_TESTING.md` complete | 1d |
| Go-live deploy | Production | 0.5d |
| Post-launch monitoring (48h) | Uptime, errors, forms | 1d |

---

## Tổng Timeline

| Sprint | Thời gian | Tổng ngày công |
|---|---|---|
| Sprint 1 | Tuần 1-2 | 10d |
| Sprint 2 | Tuần 3-5 | 15d |
| Sprint 3 | Tuần 6-8 | 15d |
| Sprint 4 | Tuần 9-10 | 10d |
| Sprint 5 | Tuần 11 | 5d |
| **Tổng** | **~11 tuần** | **~55 ngày công** |

> ⚠️ Timeline trên giả định 1 developer full-time. Nếu 2 người song song Sprint 2+3, rút ngắn ~3 tuần.

---

## Feature Priority Matrix

| Feature | Phase | Priority | Effort |
|---|---|---|---|
| 7 trang + Global Elements | 1 | ⭐ Critical | High |
| Before/After Slider | 1 | ⭐ Critical | Medium |
| Estimator Tool | 1 | ⭐ Critical | High |
| Lead Forms (Fluent Form) | 1 | 🔴 High | Low |
| AJAX Filtering + Skeleton | 1 | 🔴 High | Medium |
| Scroll Animations | 1 | 🟠 Medium | Medium |
| SEO + Security | 1 | 🟠 Medium | Medium |
| Zalo/Chat | 1 | 🟠 Medium | Low |
| Blog (ToC, Progress, Search) | 1 | 🟠 Medium | Medium |
| Client Portal | 2 | 🟡 Low | High |
| 360°/VR Tour | 2 | 🟡 Low | High |
| Multi-language | 2 | 🟡 Low | Medium |

---

## Tài Liệu Liên Quan

- `CORE_PROJECT.md` — Sprint roadmap overview
- `TRACK_DECISIONS.md` — Key decisions
- `OPS_DEPLOYMENT.md` — Go-live checklist
- `GOV_SEO_STRATEGY.md` — SEO implementation plan
- `GOV_SECURITY.md` — Security implementation plan
