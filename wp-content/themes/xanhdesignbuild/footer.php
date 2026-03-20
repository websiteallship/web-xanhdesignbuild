<?php
/**
 * Footer Template — 4-column mega-footer with contact, social, newsletter.
 *
 * Converted from: wireframes/_shared/footer.html
 * Preserves: BEM classes (footer-*), inline SVGs, aria-labels.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── Data from ACF Options ──
$xanh_logo_option   = xanh_get_option_image( 'xanh_logo_footer' );
$xanh_logo_fallback = XANH_THEME_URI . '/assets/images/logo-white.svg';
$xanh_footer_desc   = xanh_get_option( 'xanh_footer_desc', __( 'Kiến tạo tổ ấm bình yên — minh bạch từ viên gạch đầu tiên. Thiết kế & xây dựng nhà ở bền vững cho gia đình Việt.', 'xanh' ) );
$xanh_badges        = xanh_get_option( 'xanh_footer_badges' );
$xanh_hotline       = xanh_get_hotline();
$xanh_email         = xanh_get_email();
$xanh_address       = xanh_get_address();
$xanh_socials       = xanh_get_social_links();

// Bottom bar
$xanh_copyright   = xanh_get_option( 'xanh_footer_copyright' );
$xanh_privacy_url = xanh_get_option( 'xanh_legal_privacy_url', home_url( '/chinh-sach-bao-mat/' ) );
$xanh_terms_url   = xanh_get_option( 'xanh_legal_terms_url', home_url( '/dieu-khoan-su-dung/' ) );
?>

<!-- =========================================== -->
<!-- FOOTER — Comprehensive Mega-Footer          -->
<!-- ============================================= -->
<footer id="site-footer" class="site-footer">

	<!-- ── Main Footer Grid ── -->
	<div class="site-container">
		<div class="footer-grid">

			<!-- Column 1: Brand -->
			<div class="footer-col footer-col--brand">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"
				   class="footer-logo"
				   aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
					<?php if ( $xanh_logo_option ) : ?>
						<?php echo wp_get_attachment_image( $xanh_logo_option['ID'], 'full', false, [
							'class'   => 'footer-logo__img',
							'loading' => 'lazy',
							'alt'     => esc_attr( get_bloginfo( 'name' ) ),
						] ); ?>
					<?php else : ?>
						<img src="<?php echo esc_url( $xanh_logo_fallback ); ?>"
						     alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
						     class="footer-logo__img"
						     width="200" height="36"
						     loading="lazy" />
					<?php endif; ?>
				</a>
				<p class="footer-brand-desc">
					<?php echo esc_html( $xanh_footer_desc ); ?>
				</p>
				<!-- Certifications / Badges -->
				<?php if ( $xanh_badges && is_array( $xanh_badges ) ) : ?>
					<div class="footer-badges">
						<?php foreach ( $xanh_badges as $badge ) : ?>
							<?php if ( ! empty( $badge['text'] ) ) : ?>
								<span class="footer-badge"><?php echo esc_html( $badge['text'] ); ?></span>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				<?php else : ?>
					<div class="footer-badges">
						<span class="footer-badge"><?php esc_html_e( 'ISO 9001:2015', 'xanh' ); ?></span>
						<span class="footer-badge"><?php esc_html_e( '10+ Năm', 'xanh' ); ?></span>
					</div>
				<?php endif; ?>
			</div>

			<!-- Column 2: Navigation (WP Menu) -->
			<div class="footer-col">
				<h4 class="footer-col__title"><?php esc_html_e( 'Khám Phá', 'xanh' ); ?></h4>
				<?php
				if ( has_nav_menu( 'footer_col_2' ) ) {
					wp_nav_menu( [
						'theme_location' => 'footer_col_2',
						'container'      => false,
						'menu_class'     => 'footer-links',
						'depth'          => 1,
						'fallback_cb'    => false,
					] );
				} else {
					// Fallback: hiển thị links mặc định
					?>
					<ul class="footer-links">
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Trang Chủ', 'xanh' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/gioi-thieu/' ) ); ?>"><?php esc_html_e( 'Giới Thiệu', 'xanh' ); ?></a></li>
						<li><a href="<?php echo esc_url( get_post_type_archive_link( 'xanh_project' ) ); ?>"><?php esc_html_e( 'Dự Án Tiêu Biểu', 'xanh' ); ?></a></li>
						<li><a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>"><?php esc_html_e( 'Blog & Cảm Hứng', 'xanh' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/lien-he/' ) ); ?>"><?php esc_html_e( 'Liên Hệ', 'xanh' ); ?></a></li>
					</ul>
					<?php
				}
				?>
			</div>

			<!-- Column 3: Services (WP Menu) -->
			<div class="footer-col">
				<h4 class="footer-col__title"><?php esc_html_e( 'Dịch Vụ', 'xanh' ); ?></h4>
				<?php
				if ( has_nav_menu( 'footer_col_3' ) ) {
					wp_nav_menu( [
						'theme_location' => 'footer_col_3',
						'container'      => false,
						'menu_class'     => 'footer-links',
						'depth'          => 1,
						'fallback_cb'    => false,
					] );
				} else {
					// Fallback: hiển thị links mặc định
					?>
					<ul class="footer-links">
						<li><a href="<?php echo esc_url( home_url( '/dich-vu/' ) ); ?>"><?php esc_html_e( 'Thiết Kế Kiến Trúc', 'xanh' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/dich-vu/' ) ); ?>"><?php esc_html_e( 'Thiết Kế Nội Thất', 'xanh' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/dich-vu/' ) ); ?>"><?php esc_html_e( 'Thi Công Xây Dựng Trọn Gói', 'xanh' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/dich-vu/' ) ); ?>"><?php esc_html_e( 'Sản Xuất Nội Thất', 'xanh' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/dich-vu/' ) ); ?>"><?php esc_html_e( 'Cải Tạo & Nâng Cấp', 'xanh' ); ?></a></li>
					</ul>
					<?php
				}
				?>
			</div>

			<!-- Column 4: Contact + Newsletter -->
			<div class="footer-col footer-col--contact">
				<h4 class="footer-col__title"><?php esc_html_e( 'Liên Hệ', 'xanh' ); ?></h4>
				<ul class="footer-contact">
					<?php if ( $xanh_address ) : ?>
						<li>
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
								<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
								<circle cx="12" cy="10" r="3" />
							</svg>
							<span><?php echo esc_html( $xanh_address ); ?></span>
						</li>
					<?php endif; ?>
					<?php if ( $xanh_hotline ) : ?>
						<li>
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
								<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.73a16 16 0 0 0 6 6l.52-.93a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21 16.92z" />
							</svg>
							<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $xanh_hotline ) ); ?>">
								<?php echo esc_html( $xanh_hotline ); ?>
							</a>
						</li>
					<?php endif; ?>
					<?php if ( $xanh_email ) : ?>
						<li>
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
								<rect width="20" height="16" x="2" y="4" rx="2" />
								<path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
							</svg>
							<a href="mailto:<?php echo esc_attr( $xanh_email ); ?>">
								<?php echo esc_html( $xanh_email ); ?>
							</a>
						</li>
					<?php endif; ?>
				</ul>

				<!-- Newsletter -->
				<div class="footer-newsletter">
					<p class="footer-newsletter__label">
						<?php esc_html_e( 'Nhận Cẩm Nang & Cảm Hứng Kiến Tạo', 'xanh' ); ?>
					</p>
					<form class="footer-newsletter__form" action="#" method="post">
						<?php wp_nonce_field( 'xanh_newsletter', 'xanh_newsletter_nonce' ); ?>
						<input type="email"
						       name="email"
						       placeholder="<?php esc_attr_e( 'Email của bạn', 'xanh' ); ?>"
						       class="footer-newsletter__input"
						       aria-label="<?php esc_attr_e( 'Nhập email để đăng ký nhận bản tin', 'xanh' ); ?>"
						       required />
						<button type="submit"
						        class="footer-newsletter__btn"
						        aria-label="<?php esc_attr_e( 'Đăng ký nhận bản tin', 'xanh' ); ?>">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M5 12h14" />
								<path d="m12 5 7 7-7 7" />
							</svg>
						</button>
					</form>
				</div>
			</div>

		</div><!-- .footer-grid -->
	</div>

	<!-- ── Bottom Bar ── -->
	<div class="footer-bottom">
		<div class="site-container">
			<div class="footer-bottom__inner">
				<!-- Copyright -->
				<p class="footer-copyright">
					<?php
					if ( $xanh_copyright ) {
						echo esc_html( $xanh_copyright );
					} else {
						echo '&copy; ' . esc_html( date_i18n( 'Y' ) ) . ' ' . esc_html( get_bloginfo( 'name' ) ) . '. ';
						esc_html_e( 'All rights reserved.', 'xanh' );
					}
					?>
				</p>

				<!-- Legal links -->
				<div class="footer-legal">
					<a href="<?php echo esc_url( $xanh_privacy_url ); ?>">
						<?php esc_html_e( 'Chính sách bảo mật', 'xanh' ); ?>
					</a>
					<span class="footer-legal__sep">&middot;</span>
					<a href="<?php echo esc_url( $xanh_terms_url ); ?>">
						<?php esc_html_e( 'Điều khoản sử dụng', 'xanh' ); ?>
					</a>
				</div>

				<!-- Social Icons -->
				<div class="footer-social">
					<?php if ( ! empty( $xanh_socials['facebook'] ) ) : ?>
						<a href="<?php echo esc_url( $xanh_socials['facebook'] ); ?>"
						   class="footer-social__link"
						   aria-label="Facebook" target="_blank" rel="noopener noreferrer">
							<svg fill="currentColor" viewBox="0 0 24 24">
								<path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
							</svg>
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $xanh_socials['instagram'] ) ) : ?>
						<a href="<?php echo esc_url( $xanh_socials['instagram'] ); ?>"
						   class="footer-social__link"
						   aria-label="Instagram" target="_blank" rel="noopener noreferrer">
							<svg fill="currentColor" viewBox="0 0 24 24">
								<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
							</svg>
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $xanh_socials['youtube'] ) ) : ?>
						<a href="<?php echo esc_url( $xanh_socials['youtube'] ); ?>"
						   class="footer-social__link"
						   aria-label="YouTube" target="_blank" rel="noopener noreferrer">
							<svg fill="currentColor" viewBox="0 0 24 24">
								<path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
							</svg>
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $xanh_socials['zalo'] ) ) : ?>
						<a href="<?php echo esc_url( $xanh_socials['zalo'] ); ?>"
						   class="footer-social__link"
						   aria-label="Zalo" target="_blank" rel="noopener noreferrer">
							<svg fill="currentColor" viewBox="0 0 24 24">
								<path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.568 8.16c-.18-.186-.375-.375-.6-.563a7.03 7.03 0 00-.938-.656c-.656-.375-1.312-.563-1.968-.656-.375-.094-.75-.094-1.125-.094H8.25c-.281 0-.563.094-.75.281-.188.188-.281.469-.281.75v7.5c0 .281.094.563.281.75.188.188.469.281.75.281h1.5V12.75l3.375 3.375c.188.188.375.281.656.281h1.781c.188 0 .375-.094.469-.281a.596.596 0 000-.563L12.75 12.75l2.625-2.625c.188-.188.188-.469 0-.656-.094-.094-.188-.094-.281-.094h-1.781c-.188 0-.375.094-.563.281L10.5 11.906V8.438h2.438c.281 0 .563 0 .844.094.375.094.75.188 1.125.375.375.188.656.375.938.656.188.188.375.375.563.563.094.094.188.094.281.094.188 0 .375-.094.469-.281a.596.596 0 00.094-.469c-.094-.375-.281-.844-.656-1.312z" />
							</svg>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

</footer>

<!-- Back to Top (global) -->
<button id="back-to-top" aria-label="<?php esc_attr_e( 'Về đầu trang', 'xanh' ); ?>">
	<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
	     fill="none" stroke="currentColor" stroke-width="2"
	     stroke-linecap="round" stroke-linejoin="round">
		<path d="m18 15-6-6-6 6"/>
	</svg>
</button>

<?php
/**
 * Hook: xanh_before_body_close
 *
 * Third-party widgets (Zalo, analytics) go here via add_action.
 */
do_action( 'xanh_before_body_close' );

wp_footer();
?>
</body>
</html>
