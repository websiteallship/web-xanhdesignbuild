# **ĐẶC TẢ UX/UI & CONTENT: TRANG DỰ ÁN (PORTFOLIO)** {#đặc-tả-uxui-content-trang-dự-án-portfolio}

## **1. HERO SECTION (Khu vực mở đầu trang)** {#hero-section-khu-vực-mở-đầu-trang}

**Mục tiêu:** Định khung tư duy cho khách hàng ngay khi bước vào trang. Xanh không khoe mảng miếng thiết kế, Xanh khoe \"Sự thật\".

- **Copywriting:**

  - **Headline chính:** Tác Phẩm Thực Tế. Giá Trị Khởi Nguồn Từ Sự Thật.

  - **Sub-headline:** Chúng tôi không đánh giá sự thành công qua những bản vẽ 3D lộng lẫy. Một dự án của Xanh chỉ thực sự hoàn hảo khi nó bước ra đời thực, đúng ngân sách, đúng tiến độ và mang lại sự bình yên cho người ở.

- **UI/UX & Dev Notes:**

  - **Background:** Sử dụng nền màu sáng, Minimalist (tối giản) để làm nổi bật hình ảnh dự án bên dưới. Không dùng ảnh nền chằng chịt.

  - **Typography:** Tiêu đề to, rõ ràng, căn giữa.

## **2. NAVIGATION & FILTER (Thanh điều hướng & Lọc dự án)** {#navigation-filter-thanh-điều-hướng-lọc-dự-án}

**Mục tiêu:** Giúp người dùng dễ dàng tìm kiếm đúng loại hình nhà ở họ đang quan tâm.

- **Copywriting (Các Tab Lọc):**

  - Tất cả \| Đã bàn giao \| Đang thi công \| Concept 3D

  - *Lọc theo loại hình:* Biệt thự \| Nhà phố \| Căn hộ \| Nghỉ dưỡng

- **UI/UX & Dev Notes:**

  - **UI:** Thanh Filter thiết kế dạng \"Sticky\" (dính lại trên cùng khi cuộn chuột) để user luôn có thể chuyển đổi danh mục.

  - **Dev (Interaction):** Sử dụng kỹ thuật **AJAX / Isotope filtering**. Khi click vào một bộ lọc, các dự án sẽ sắp xếp lại với hiệu ứng transition mượt mà (không tải lại toàn bộ trang).

## **3. PROJECT GRID VIEW (Giao diện thẻ dự án bên ngoài)** {#project-grid-view-giao-diện-thẻ-dự-án-bên-ngoài}

**Mục tiêu:** Thu hút click vào xem chi tiết bằng các thông số minh bạch.

- **Nội dung trên mỗi Card (Thẻ):**

  - **Hình ảnh Thumbnail:** Bắt buộc là **Ảnh chụp thực tế** (nếu là dự án đã bàn giao).

  - **Tên dự án:** VD: *Biệt thự phố - Mr. Hoàng (Nha Trang)*

  - **Tagline nhỏ:** *Hoàn thiện sát 3D 98% \| 0% Phát sinh chi phí*

- **UI/UX & Dev Notes:**

  - **Layout:** Cấu trúc Masonry Grid (các thẻ lưới xếp so le tự nhiên) hoặc Grid 3 cột tiêu chuẩn.

  - **Hover Effect (Hiệu ứng di chuột):** Khi người dùng hover chuột vào thẻ dự án, hình ảnh sẽ từ từ Zoom in nhẹ (scale 1.05), đồng thời hiện lên một lớp overlay tối màu hiển thị nút **\[Xem câu chuyện dự án\]**.

## **4. PROJECT DETAIL PAGE (TRANG CHI TIẾT TỪNG DỰ ÁN)** {#project-detail-page-trang-chi-tiết-từng-dự-án}

*Lưu ý cho Team Dev & UI: Đây là trang quan trọng nhất. Mỗi dự án là một Landing Page thu nhỏ kể về hành trình hiện thực hóa ngôi nhà.*

### **4.1. Thông số minh bạch (Project Stats Bar)** {#thông-số-minh-bạch-project-stats-bar}

- **Copywriting:**

  - Vị trí: \[\...\] \| Diện tích: \[\...\] m2 \| Quy mô: \[\...\] tầng

  - **Thời gian thi công:** \[\...\] ngày (Cam kết: Trễ phạt x%)

  - **Ngân sách quyết toán:** \[\...\] Tỷ VNĐ (Cam kết: 0% phát sinh so với dự toán ban đầu).

- **UI/UX:** Thiết kế thành một thanh ngang (Bar) hoặc một khối Box nổi bật ngay dưới ảnh Hero của chi tiết dự án. Dùng Icon sắc nét cho từng thông số.

### **4.2. Câu chuyện dự án (Bài toán & Lời giải)** {#câu-chuyện-dự-án-bài-toán-lời-giải}

- **Copywriting:**

  - **Bài toán của gia chủ:** (VD: \"Anh Hoàng tìm đến Xanh với một khu đất hướng Tây cực nóng và ngân sách tối đa 2.5 tỷ. Anh muốn không gian mở nhưng phải mát mẻ\...\")

  - **Giải pháp \"Xanh\":** (VD: \"Ứng dụng gạch bông gió mặt tiền kết hợp hệ lam chắn nắng. Sử dụng vật liệu cách nhiệt X XPS. Bố trí giếng trời thông tầng tạo luồng gió đối lưu\...\")

- **UI/UX:** Trình bày dạng Text block chia 2 cột (Cột trái: Bài toán / Cột phải: Giải pháp) hoặc trình bày dạng Timeline kể chuyện.

### **4.3. Tính năng cốt lõi: Interactive Image Slider (So sánh 3D vs Thực tế)** {#tính-năng-cốt-lõi-interactive-image-slider-so-sánh-3d-vs-thực-tế}

**Mục tiêu:** Bằng chứng đắt giá nhất cho năng lực thi công và sự minh bạch của Xanh.

- **UI/UX & Dev Notes:**

  - Dev tích hợp thư viện **Before/After Image Slider** (ví dụ: twentyseven hoặc img-comparison-slider).

  - **Cách hoạt động:** Hiển thị 1 khung ảnh lớn. Bức ảnh được chia làm 2 nửa. Bên trái là \"Bản vẽ Concept 3D\", bên phải là \"Ảnh chụp Thực tế nghiệm thu\".

  - Người dùng có thể dùng chuột/ngón tay **kéo thanh trượt qua lại** để tự đối chiếu sự giống nhau đến kinh ngạc giữa bản vẽ và thực tế. Có text nhỏ \"Kéo để so sánh\".

### **4.4. Thư viện Vật liệu xanh đã sử dụng (Material Board)** {#thư-viện-vật-liệu-xanh-đã-sử-dụng-material-board}

- **Copywriting:** Hiển thị hình ảnh/logo các vật liệu chính (Sơn thân thiện môi trường, Gỗ công nghiệp đạt chuẩn E0, Hệ thống điện thông minh tiết kiệm năng lượng).

- **UI/UX:** Thiết kế dạng Carousel (thanh trượt ngang) hoặc các Icon nhỏ xinh xắn. Khi hover vào sẽ hiện tool-tip giải thích vì sao vật liệu này \"Xanh\".

### **4.5. Thư viện ảnh thực tế (Real Gallery) & Video (Nếu có)** {#thư-viện-ảnh-thực-tế-real-gallery-video-nếu-có}

- **UI/UX:** Lưới ảnh (Grid) full-width (tràn viền). Có tính năng **Lightbox** (click vào ảnh để phóng to, vuốt sang trái/phải để xem ảnh tiếp theo). Tối ưu hóa dung lượng ảnh (WebP) để load trang siêu tốc.

### **4.6. Lời chứng thực (Testimonial)** {#lời-chứng-thực-testimonial}

- **Copywriting:** Lời nhận xét thực tế của chủ nhà sau thời gian dọn vào ở (Tập trung vào trải nghiệm vận hành: nhà mát, không thấm dột, điện nước rẻ, độ bền cao).

- **UI/UX:** Đặt trong một Block màu xám nhạt hoặc màu Xanh thương hiệu, font chữ italic (in nghiêng) thể hiện trích dẫn. Kèm hình ảnh thật của gia đình gia chủ tại công trình.

## **5. CALL TO ACTION (Khóa sổ ở cuối mỗi dự án)** {#call-to-action-khóa-sổ-ở-cuối-mỗi-dự-án}

**Mục tiêu:** Chuyển đổi cảm xúc ngưỡng mộ thành hành động để lại thông tin.

- **Copywriting:**

  - **Headline:** Bạn cũng muốn một không gian sống trọn vẹn và minh bạch như thế này?

  - **Sub-text:** Hãy cho Xanh biết diện tích và nhu cầu của bạn, chúng tôi sẽ đưa ra con số dự toán sơ bộ chính xác đến 90% chỉ sau vài phút.

- **UI/UX & Dev Notes:**

  - Nút CTA 1 (Nổi bật): **\[Sử Dụng Công Cụ Dự Toán Xanh\]** -\> Link trỏ về trang Dự toán.

  - Nút CTA 2 (Phụ): **\[Chat với Kỹ sư trưởng\]** -\> Mở pop-up Zalo OA hoặc Live chat.
