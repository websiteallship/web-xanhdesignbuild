<?php
/**
 * Template Part: Section — Blog Lead Magnet (Ebook CTA).
 *
 * Displays the ebook lead magnet section with form,
 * 3D book mockup, and trust signals.
 * ACF Options fields: blog_lm_* on ACF Options page.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── ACF fields with fallbacks ──
$lm_headline  = xanh_get_option( 'blog_lm_headline', 'Xây Nhà Lần Đầu?<br /><em>Đừng Bỏ Qua Cuốn Cẩm Nang Này.</em>' );
$lm_subtext   = xanh_get_option( 'blog_lm_subtext', 'Ebook: <strong>"Bí Quyết Xây Nhà Không Phát Sinh Chi Phí &amp; Tối Ưu Vận Hành"</strong>' );
$lm_book_title = xanh_get_option( 'blog_lm_book_title', 'Bí Quyết Xây Nhà Không Phát Sinh' );
$lm_trust     = xanh_get_option( 'blog_lm_trust_text', 'Không spam — XANH cam kết bảo mật thông tin của bạn.' );
?>

<section id="lead-magnet" class="lead-magnet" aria-labelledby="lead-magnet-title">
	<div class="site-container">
		<div class="lead-magnet__inner">

			<!-- ── Left: Text + Form ── -->
			<div class="lead-magnet__content anim-fade-up">
				<span class="section-eyebrow text-white/50 block mb-4">Ebook Miễn Phí</span>
				<h2 id="lead-magnet-title" class="lead-magnet__headline">
					<?php echo wp_kses_post( $lm_headline ); ?>
				</h2>
				<p class="lead-magnet__subtext">
					<?php echo wp_kses_post( $lm_subtext ); ?>
				</p>

				<!-- Form — Fluent Forms -->
				<div class="lead-magnet__form-wrap">
					<?php echo do_shortcode( '[fluentform id="6"]' ); ?>
				</div>

				<!-- Trust signals -->
				<p class="lead-magnet__trust">
					<i data-lucide="shield-check" class="w-3.5 h-3.5 inline-block mr-1 opacity-60"></i>
					<?php echo esc_html( $lm_trust ); ?>
				</p>
			</div>

			<!-- ── Right: 3D Book Mockup ── -->
			<div class="lead-magnet__visual anim-fade-up">
				<div class="lead-magnet__book-wrapper" id="lead-magnet-book-wrapper" aria-hidden="true">
					<div class="lead-magnet__book" id="lead-magnet-book">
						<!-- Book Spine -->
						<div class="lead-magnet__book-spine">
							<span>XANH</span>
						</div>
						<!-- Book Front Cover -->
						<div class="lead-magnet__book-cover">
							<!-- Cover decoration -->
							<div class="lead-magnet__book-cover-top">
								<span class="lead-magnet__book-label">Ebook Độc Quyền</span>
								<div class="lead-magnet__book-logo">XANH</div>
							</div>
							<div class="lead-magnet__book-cover-body">
								<h3 class="lead-magnet__book-title">
									<?php echo esc_html( $lm_book_title ); ?>
									<strong>Chi Phí</strong>
								</h3>
								<p class="lead-magnet__book-subtitle">&amp; Tối Ưu Vận Hành</p>
							</div>
							<div class="lead-magnet__book-cover-bottom">
								<span>Design &amp; Build</span>
								<!-- Book pages illusion -->
								<div class="lead-magnet__book-pages"></div>
							</div>
						</div>
					</div>
					<!-- Glow effect under book -->
					<div class="lead-magnet__book-glow" aria-hidden="true"></div>
				</div>
			</div>

		</div><!-- /lead-magnet__inner -->
	</div><!-- /site-container -->
</section>
