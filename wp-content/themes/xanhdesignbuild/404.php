<?php
/**
 * 404 Not Found Template.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

get_header();
?>

<main id="main-content" class="site-main" role="main">
	<section class="min-h-[60vh] flex items-center justify-center px-4">
		<div class="text-center max-w-lg">
			<p class="text-8xl font-bold text-primary/20 mb-4">404</p>
			<h1 class="text-3xl lg:text-4xl font-bold text-dark mb-4" style="letter-spacing: -0.02em;">
				<?php esc_html_e( 'Trang Không Tồn Tại', 'xanh' ); ?>
			</h1>
			<p class="text-gray-600 mb-8">
				<?php esc_html_e( 'Xin lỗi, trang bạn đang tìm kiếm không tồn tại hoặc đã được di chuyển.', 'xanh' ); ?>
			</p>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"
			   class="inline-flex items-center gap-2 bg-primary text-white px-8 py-4 font-semibold hover:bg-primary/90 transition-colors duration-300">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
				     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
					<polyline points="9 22 9 12 15 12 15 22"/>
				</svg>
				<?php esc_html_e( 'Về Trang Chủ', 'xanh' ); ?>
			</a>
		</div>
	</section>
</main>

<?php
get_footer();
