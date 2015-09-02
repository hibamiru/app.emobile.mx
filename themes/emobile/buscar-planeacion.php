<?php
/**
 * Template Name: Buscar planeación
 * Description: Página para el escritorio de docentes
 *
 * @package WordPress
 * @subpackage EMobile
 * @since EMobile 1.0
 */

//suser_redirect();
get_header(); ?>
<?php show_errors(); ?>
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
            	<option value="Preescolar">Preescolar</option>
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
	<?php $meta_query = array(
			//query preescolar
			'meta_key' => 'pl_nivel',
			'meta_value' => $level,
    );
    $planeaciones = get_plans($meta_query); ?>
    <?php print_array($planeaciones); ?>

<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>