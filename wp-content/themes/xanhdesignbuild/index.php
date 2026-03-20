<?php
/**
 * The main template file (fallback).
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

get_header();
?>

<main id="main-content" class="site-main" role="main">
	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
				<div class="site-container">
					<div class="page-content__inner">
						<h1 class="page-content__title"><?php the_title(); ?></h1>
						<div class="prose-content">
							<?php the_content(); ?>
						</div>
					</div>
				</div>
			</article>
		<?php endwhile; ?>

		<?php the_posts_pagination( [
			'mid_size'  => 2,
			'prev_text' => '&laquo;',
			'next_text' => '&raquo;',
			'class'     => 'mt-12',
		] ); ?>

	<?php else : ?>

		<section class="max-w-4xl mx-auto px-4 py-20 text-center">
			<h1 class="text-3xl font-bold text-dark mb-4">
				<?php esc_html_e( 'Không tìm thấy nội dung', 'xanh' ); ?>
			</h1>
			<p class="text-gray-600">
				<?php esc_html_e( 'Xin lỗi, không có nội dung nào phù hợp với yêu cầu của bạn.', 'xanh' ); ?>
			</p>
		</section>

	<?php endif; ?>
</main>

<?php
get_footer();
