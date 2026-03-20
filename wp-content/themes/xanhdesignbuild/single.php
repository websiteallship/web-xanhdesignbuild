<?php
/**
 * Single Post Template (Blog Detail).
 *
 * Displays a single blog article with reading progress bar,
 * article header, content with TOC, end-CTA, related articles,
 * and lead magnet section.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

get_header();
?>

<!-- Reading Progress Bar -->
<div id="reading-progress" class="reading-progress z-[9999]" aria-hidden="true">
	<div class="reading-progress__bar"></div>
</div>

<main id="main-content" class="site-main pt-[80px] lg:pt-[100px]" role="main">

	<?php while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="https://schema.org/NewsArticle">

			<?php get_template_part( 'template-parts/sections/section', 'blog-detail-header' ); ?>

			<div class="site-container">
				<?php get_template_part( 'template-parts/sections/section', 'blog-detail-content' ); ?>
				<?php get_template_part( 'template-parts/sections/section', 'blog-detail-end-cta' ); ?>
			</div>

		</article>

	<?php endwhile; ?>

	<?php get_template_part( 'template-parts/sections/section', 'blog-detail-related' ); ?>
	<?php get_template_part( 'template-parts/sections/section', 'blog-lead-magnet' ); ?>


</main>

<?php
get_footer();
