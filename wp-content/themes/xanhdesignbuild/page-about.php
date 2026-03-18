<?php
/**
 * Template Name: Giới Thiệu
 * About Page Template.
 *
 * Displays the "Giới Thiệu" page with all sections.
 * Requires ACF field group: group_about_page.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

get_header();
?>

<main id="main-content" class="site-main" role="main">

	<?php
	/**
	 * Hook: xanh_before_hero
	 *
	 * @param int $post_id Current page ID.
	 */
	do_action( 'xanh_before_hero', get_the_ID() );
	?>

	<?php // Section 1: Hero Banner + Video Modal ?>
	<?php get_template_part( 'template-parts/hero/hero', 'about' ); ?>

	<?php // Section 2: The Pain — Nỗi Trăn Trở ?>
	<?php get_template_part( 'template-parts/sections/section', 'about-pain' ); ?>

	<?php // Section 3: Turning Point — Bước Ngoặt ?>
	<?php get_template_part( 'template-parts/sections/section', 'about-turning' ); ?>

	<?php // Section 4: The Promise — Sứ Mệnh ?>
	<?php get_template_part( 'template-parts/sections/section', 'about-promise' ); ?>

	<?php // Section 5: Philosophy "4 Xanh" ?>
	<?php get_template_part( 'template-parts/sections/section', 'about-philosophy' ); ?>

	<?php // Section 6: Core Values — Bản Sắc Cốt Lõi ?>
	<?php get_template_part( 'template-parts/sections/section', 'about-core-values' ); ?>

	<?php // Section 7: Team Members ?>
	<?php get_template_part( 'template-parts/sections/section', 'about-team' ); ?>

	<?php // Section 8: Final CTA — Khởi Đầu Hành Trình ?>
	<?php get_template_part( 'template-parts/sections/section', 'about-final-cta' ); ?>

	<?php
	/**
	 * Hook: xanh_after_content
	 */
	do_action( 'xanh_after_content' );
	?>

</main>

<?php
get_footer();
