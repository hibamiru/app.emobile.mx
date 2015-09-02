<?php
/**
 * Template Name: Alta participante
 * Description: Página para el preregistro con email
 *
 * @package WordPress
 * @subpackage EMobile
 * @since EMobile 1.0
 */

get_header(); ?>

</div><!-- fin #main -->
<div class="alta-container">
	<section class="container">
		<?php while ( have_posts() ) : the_post(); ?>
			<div class="alta-participante-block group">
				<?php get_template_part( 'content', 'page' ); ?>
			</div>
		<?php endwhile; // end of the loop. ?>
	</section>
</div>
<?php get_footer(); ?>