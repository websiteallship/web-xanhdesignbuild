<?php
/**
 * Template Part: Section — Blog Detail Header.
 *
 * Renders breadcrumb, category tag, title (H1), author/date/reading-time meta,
 * and the featured image. Must be called inside the WP Loop.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── Category data ──
$categories = get_the_category();
$cat_name   = '';
$cat_link   = '';
if ( ! empty( $categories ) ) {
	$cat_name = $categories[0]->name;
	$cat_link = get_category_link( $categories[0]->term_id );
}

// ── Reading time ──
$content    = get_the_content();
$word_count = str_word_count( wp_strip_all_tags( $content ) );
$read_time  = max( 1, ceil( $word_count / 200 ) );
?>

<header class="article-header pb-8 md:pb-12 pt-6 md:pt-10">
	<div class="content-text max-w-[1280px] mx-auto px-6 md:px-8 lg:px-12 text-center">

		<!-- Breadcrumb -->
		<nav class="breadcrumb breadcrumb--blog mb-6 flex justify-center" aria-label="Breadcrumb">
			<ol class="breadcrumb__list flex items-center text-[13px] text-dark/60 font-medium">
				<li class="breadcrumb__item flex items-center">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-primary transition-colors">Trang Chủ</a>
					<span class="mx-2"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg></span>
				</li>
				<li class="breadcrumb__item flex items-center">
					<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="hover:text-primary transition-colors">Tin Tức</a>
					<?php if ( $cat_name ) : ?>
						<span class="mx-2"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg></span>
					<?php endif; ?>
				</li>
				<?php if ( $cat_name ) : ?>
					<li class="breadcrumb__item flex items-center">
						<a href="<?php echo esc_url( $cat_link ); ?>" class="hover:text-primary transition-colors"><?php echo esc_html( $cat_name ); ?></a>
					</li>
				<?php endif; ?>
			</ol>
		</nav>

		<!-- Category Tag -->
		<?php if ( $cat_name ) : ?>
			<a href="<?php echo esc_url( $cat_link ); ?>" class="tag !rounded-none bg-primary/10 text-primary hover:bg-primary hover:text-white transition-colors uppercase tracking-widest text-[10px] font-bold px-3 py-1 mb-6 inline-block">
				<?php echo esc_html( $cat_name ); ?>
			</a>
		<?php endif; ?>

		<!-- Title (H1) -->
		<h1 class="font-heading text-3xl md:text-4xl lg:text-5xl font-bold leading-tight md:leading-[1.15] tracking-tight text-dark mb-6" itemprop="headline">
			<?php the_title(); ?>
		</h1>

		<!-- Meta -->
		<div class="flex flex-wrap items-center justify-center gap-4 text-xs md:text-sm text-dark/60 font-medium">
			<div class="flex items-center gap-1.5" itemprop="author" itemscope itemtype="https://schema.org/Person">
				<i data-lucide="user" class="w-4 h-4"></i>
				<span class="uppercase tracking-widest text-[10px] font-bold" itemprop="name"><?php echo esc_html( get_the_author() ); ?></span>
			</div>
			<span class="w-1 h-1 rounded-full bg-dark/20"></span>
			<div class="flex items-center gap-1.5">
				<i data-lucide="calendar" class="w-4 h-4"></i>
				<time itemprop="datePublished" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
					<?php echo esc_html( get_the_date( 'j \T\h\á\n\g n, Y' ) ); ?>
				</time>
			</div>
			<span class="w-1 h-1 rounded-full bg-dark/20"></span>
			<div class="flex items-center gap-1.5">
				<i data-lucide="clock" class="w-4 h-4"></i>
				<span><?php echo esc_html( $read_time ); ?> phút đọc</span>
			</div>
		</div>

	</div>
</header>

<!-- Featured Image -->
<?php if ( has_post_thumbnail() ) : ?>
	<div class="article-featured-img site-container mb-12 md:mb-20">
		<div class="w-full h-[300px] md:h-[450px] lg:h-[600px] overflow-hidden rounded-[2px] relative group">
			<?php
			the_post_thumbnail( 'large', [
				'class'         => 'w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105',
				'loading'       => 'eager',
				'fetchpriority' => 'high',
				'decoding'      => 'async',
				'itemprop'      => 'image',
			] );
			?>
		</div>
	</div>
<?php else : ?>
	<div class="article-featured-img site-container mb-12 md:mb-20">
		<div class="w-full h-[300px] md:h-[450px] lg:h-[600px] overflow-hidden rounded-[2px] relative group">
			<img src="<?php echo esc_url( XANH_THEME_URI . '/assets/images/placeholder-project.png' ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105" loading="eager" fetchpriority="high" decoding="async" itemprop="image" />
		</div>
	</div>
<?php endif; ?>
