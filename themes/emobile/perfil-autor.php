<?php
/**
 * Template Name: Perfil autor
 * Description: Página personalizada de ejemplo
 *
 * @package WordPress
 * @subpackage EMobile
 * @since EMobile 1.0
 */

get_header(); ?>

		<div id="primary-full">
			<div id="content" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php $perfil_autor = perfil_autor( get_query_var('id_perfil_autor') );?>

				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>