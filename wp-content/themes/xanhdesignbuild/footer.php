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
							<?php
						$xanh_map_link = xanh_get_option( 'contact_map_link' );
						if ( $xanh_map_link ) : ?>
							<a href="<?php echo esc_url( $xanh_map_link ); ?>" target="_blank" rel="noopener noreferrer">
								<?php echo esc_html( $xanh_address ); ?>
							</a>
						<?php else : ?>
							<span><?php echo esc_html( $xanh_address ); ?></span>
						<?php endif; ?>
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
					<div class="footer-newsletter__form">
						<?php echo do_shortcode( '[fluentform id="5"]' ); ?>
					</div>
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
							<svg id="svg_zalo_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewBox="0 0 614.501 613.667" xml:space="preserve" fill="currentColor">
								<path d="M464.721,301.399c-13.984-0.014-23.707,11.478-23.944,28.312c-0.251,17.771,9.168,29.208,24.037,29.202   c14.287-0.007,23.799-11.095,24.01-27.995C489.028,313.536,479.127,301.399,464.721,301.399z" />
								<path d="M291.83,301.392c-14.473-0.316-24.578,11.603-24.604,29.024c-0.02,16.959,9.294,28.259,23.496,28.502   c15.072,0.251,24.592-10.87,24.539-28.707C315.214,313.318,305.769,301.696,291.83,301.392z" />
								<path d="M310.518,3.158C143.102,3.158,7.375,138.884,7.375,306.3s135.727,303.142,303.143,303.142   c167.415,0,303.143-135.727,303.143-303.142S477.933,3.158,310.518,3.158z M217.858,391.083   c-33.364,0.818-66.828,1.353-100.133-0.343c-21.326-1.095-27.652-18.647-14.248-36.583c21.55-28.826,43.886-57.065,65.792-85.621   c2.546-3.305,6.214-5.996,7.15-12.705c-16.609,0-32.784,0.04-48.958-0.013c-19.195-0.066-28.278-5.805-28.14-17.652   c0.132-11.768,9.175-17.329,28.397-17.348c25.159-0.026,50.324-0.06,75.476,0.026c9.637,0.033,19.604,0.105,25.304,9.789   c6.22,10.561,0.284,19.512-5.646,27.454c-21.26,28.497-43.015,56.624-64.559,84.902c-2.599,3.41-5.119,6.88-9.453,12.725   c23.424,0,44.123-0.053,64.816,0.026c8.674,0.026,16.662,1.873,19.941,11.267C237.892,379.329,231.368,390.752,217.858,391.083z    M350.854,330.211c0,13.417-0.093,26.841,0.039,40.265c0.073,7.599-2.599,13.647-9.512,17.084   c-7.296,3.642-14.71,3.028-20.304-2.968c-3.997-4.281-6.214-3.213-10.488-0.422c-17.955,11.728-39.908,9.96-56.597-3.866   c-29.928-24.789-30.026-74.803-0.211-99.776c16.194-13.562,39.592-15.462,56.709-4.143c3.951,2.619,6.201,4.815,10.396-0.053   c5.39-6.267,13.055-6.761,20.271-3.357c7.454,3.509,9.935,10.165,9.776,18.265C350.67,304.222,350.86,317.217,350.854,330.211z    M395.617,369.579c-0.118,12.837-6.398,19.783-17.196,19.908c-10.779,0.132-17.593-6.966-17.646-19.512   c-0.179-43.352-0.185-86.696,0.007-130.041c0.059-12.256,7.302-19.921,17.896-19.222c11.425,0.752,16.992,7.448,16.992,18.833   c0,22.104,0,44.216,0,66.327C395.677,327.105,395.828,348.345,395.617,369.579z M463.981,391.868   c-34.399-0.336-59.037-26.444-58.786-62.289c0.251-35.66,25.304-60.713,60.383-60.396c34.631,0.304,59.374,26.306,58.998,61.986   C524.207,366.492,498.534,392.205,463.981,391.868z" />
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

// Popup Modals — render all active popups.
get_template_part( 'template-parts/components/popup-modal' );

wp_footer();

// ACF: Custom scripts before </body> (chat widgets, analytics, etc.)
$xanh_body_scripts = xanh_get_option( 'seo_body_scripts' );
if ( $xanh_body_scripts ) {
	echo $xanh_body_scripts . "\n";
}
?>
</body>
</html>
