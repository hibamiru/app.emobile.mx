<?php
/**
 * Template Name: Registro
 * Description: Página para el escritorio de docentes
 *
 * @package WordPress
 * @subpackage EMobile
 * @since EMobile 1.0
 */

user_redirect();
acf_form_head();
$user_ID = get_current_user_id();
get_header(); ?>

	<?php while ( have_posts() ) : the_post(); ?>

    	<section id="completar-registro"  class="container">

    		<header class="entry-header">
				<h1 class="entry-title"><?php the_title(); ?></h1>
			</header><!-- .entry-header -->

			<div class="entry-content">
				<?php the_content(); ?>
			</div><!-- .entry-content -->	


	    	<?php $options = array(
								'post_id'		=> 'user_' . $user_ID,
								'post_title'	=> false,
								'field_groups'	=> array(
													24,
													),
								'submit_value'	=> 'Guardar avance',
								);
			?>

			<?php acf_form($options); ?>

			<?php $options = array(
								'post_id'		=> 'user_' . $user_ID,
								'post_title'	=> false,
								'field_groups'	=> array(
													21,
													),
								'submit_value'	=> 'Validar formulario',
								);
			?>		
			<?php acf_form($options); ?>

		</section>
	<?php endwhile; // end of the loop. ?>
<?php if (allow_form_validation()) {
	echo 'Yes';
}?>
<?php get_footer(); ?>