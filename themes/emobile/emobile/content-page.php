<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage EMobile
 * @since EMobile 1.0
 */
?>

<article>
	
    <header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->	
   
</article><!-- #post-<?php the_ID(); ?> -->
