<?php
/**
 * Template Name: Publicar planeación
 * Description: Página para el escritorio de docentes
 *
 * @package WordPress
 * @subpackage EMobile
 * @since EMobile 1.0
 */

user_redirect();
acf_form_head();
$user_ID = get_current_user_id();
get_header();
$allow_form_validation = allow_form_validation();
?>
<?php //echo '<pre style="display:block;">'; print_r($allow_form_validation); echo '</pre>'; // PRINT_R?>
	<?php while ( have_posts() ) : the_post(); ?>

    	<section id="completar-registro"  class="container content">

    		<header class="entry-header">
				<h1 class="entry-title"><?php the_title(); ?></h1>
			</header><!-- .entry-header -->

	    	<?php $options = array(
								'post_id'		=> get_query_var('id_planeacion'),
								'post_title'	=> false,
								'field_groups'	=> array(
													1069,
													),
								'updated_message' => 'Información actualizada',
								'submit_value'	=> 'Publicar',
								);
			?>
			<div class="group">
				<?php acf_form($options); ?>
			</div>

		</section>
	<?php endwhile; // end of the loop. ?>
<?php get_footer(); ?>