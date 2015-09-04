<?php
/**
 * Template Name: Buscar planeaciones
 * Description: Página personalizada de ejemplo
 *
 * @package WordPress
 * @subpackage EMobile
 * @since EMobile 1.0
 */

get_header(); ?>
<?php //show_errors(); ?>

	<div id="primary-full">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>
				<section id="escritorio"  class="container content">
					<form action="" method="post">
			        	<label for="nivel">Nivel</label>
			        	<select name="nivel">
							<option value=""></option>
			            	<option value="Preescolar">Preescolar</option>
			            	<option value="Primaria">Primaria</option>
			            	<option value="Secundaria">Secundaria</option>
			            </select>
			            <label for="grado">Grado</label>
			        	<select name="grado">
				            <option value=""></option>
			                <option value="1°">1°</option>
			                <option value="2°">2°</option>
			                <option value="3°">3°</option>
			                <option value="4°">4°</option>
			                <option value="5°">5°</option>
			                <option value="6°">6°</option>
			            </select>
			            <label for="asignatura">Asignatura</label>
			        	<select name="asignatura">
			            	<option value=""></option>
			                <option value="Español">Español</option>
			                <option value="Matemáticas">Matemáticas</option>
			            </select>
			            <label for="bloque">Bloque</label>
			        	<select name="bloque">
			            	<option value=""></option>
			                <option value="I">I</option>
			                <option value="II">II</option>
			                <option value="III">III</option>
			                <option value="IV">IV</option>
			                <option value="V">V</option>
			            </select>
			        </form>
				</section>
				
				<?php $level = 'Secundaria'; ?>
				
				<?php

				// Meta keys
				$field_nivel		= $_POST['nivel'];
				$field_grado		= $_POST['grado'];
				$field_asignatura 	= $_POST['asignatura'];
				$field_Bloque 		= $_POST['bloque'];

				//Meta values
				$nivel;
				$grado;
				$grupo;
				$bloque;
				
				?>
				
				<?php $meta_query = array(
					//query preescolar
					'meta_key' => 'pl_nivel',
					'meta_value' => $level,
		    		);
			    
			    $planeaciones = get_plans($meta_query); ?>
			    <?php print_array($planeaciones); ?>


			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>