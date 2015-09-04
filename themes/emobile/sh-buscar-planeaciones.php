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
<?php //print_array($_POST); ?>
<?php //show_errors(); ?>
<?php
$nivel 		= FALSE;
$grado		= FALSE;
$asignatura = FALSE;
$bloque 	= FALSE;
if (isset($_POST)) {
	$nivel 		= $_POST['nivel'];
	$grado		= $_POST['grado'];
	$asignatura = $_POST['asignatura'];
	$bloque 	= $_POST['bloque'];
}
?>

	<div id="primary-full">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>
				<section id="escritorio"  class="container content">
					<form action="" method="post">
			        	<label for="nivel">Nivel</label>
			        	<select name="nivel">
							<option value=""></option>
			            	<option <?php echo ($nivel == 'Preescolar' ? 'selected' : ''); ?> value="Preescolar">Preescolar</option>
			            	<option <?php echo ($nivel == 'Primaria' ? 'selected' : '')?> value="Primaria">Primaria</option>
			            	<option <?php echo ($nivel == 'Secundaria' ? 'selected' : ''); ?> value="Secundaria">Secundaria</option>
			            </select>
			            <label for="grado">Grado</label>
			        	<select name="grado">
				            <option value=""></option>
			                <option <?php echo ($grado == '1°') ? 'selected' : '' ?> value="1°">1°</option>
			                <option <?php echo ($grado == '2°') ? 'selected' : '' ?> value="2°">2°</option>
			                <option <?php echo ($grado == '3°') ? 'selected' : '' ?> value="3°">3°</option>
			                <option <?php echo ($grado == '4°') ? 'selected' : '' ?> value="4°">4°</option>
			                <option <?php echo ($grado == '5°') ? 'selected' : '' ?> value="5°">5°</option>
			                <option <?php echo ($grado == '6°') ? 'selected' : '' ?> value="6°">6°</option>
			            </select>
			            <label for="asignatura">Asignatura</label>
			        	<select name="asignatura">
			            	<option value=""></option>
			                <option <?php echo ($asignatura == 'Español') ? 'selected' : '' ?> value="Español">Español</option>
			                <option <?php echo ($asignatura == 'Matemáticas') ? 'selected' : '' ?> value="Matemáticas">Matemáticas</option>
			            </select>
			            <label for="bloque">Bloque</label>
			        	<select name="bloque">
			            	<option value=""></option>
			                <option <?php echo ($bloque == 'I') ? 'selected' : '' ?> value="I">I</option>
			                <option <?php echo ($bloque == 'II') ? 'selected' : '' ?> value="II">II</option>
			                <option <?php echo ($bloque == 'III') ? 'selected' : '' ?> value="III">III</option>
			                <option <?php echo ($bloque == 'IV') ? 'selected' : '' ?> value="IV">IV</option>
			                <option <?php echo ($bloque == 'V') ? 'selected' : '' ?> value="V">V</option>
			            </select>
						<input type="submit" name="consultar" value="Consultar">
			        </form>
				</section>
				
				<?php

				if (isset($_POST['consultar'])) {

					// Meta keys
					$nivel			= $_POST['nivel'];
					$grado			= $_POST['grado'];
					$asignatura 	= $_POST['asignatura'];
					$bloque 		= $_POST['bloque'];
					$meta_grado		= FALSE;
					$meta_asignatura = FALSE;
					$meta_bloque	= FALSE;

					// Meta vars
					if ($grado) {
						
						if ($nivel == 'Primaria')
							$suffix = 'pri';
						
						if ($nivel == 'Secundaria')
							$suffix = 'sec';
						
						$meta_grado = array(
						
							'key' => 'pl_grado_'.$suffix,
							'value' => $grado,
							'compare' => '=',
						
						);

					}

					if ($asignatura) {
						
						$meta_asignatura = array(
						
							'key' => 'pl_asignatura',
							'value' => $asignatura,
							'compare' => '=',
						
						);

					}

					if ($bloque) {
						
						$meta_bloque = array(
						
							'key' => 'pl_bloque',
							'value' => $bloque,
							'compare' => '=',
						
						);

					}

					if ($grado OR $asignatura OR $bloque) {

						$meta_query = array(
							'meta_query' => array( 
									'relation' => 'AND',
									array(
										'key' => 'pl_nivel',
										'value' => $nivel,
										'compare' => '=',
									),
								)
							);

						if ($grado) {
							$meta_query['meta_query'][] = $meta_grado;
						}

						if ($asignatura) {
							$meta_query['meta_query'][] = $meta_asignatura;
						}

						if ($bloque) {
							$meta_query['meta_query'][] = $meta_bloque;
						}

					} else {
						$meta_query = array(
							//query preescolar
							'meta_key' => 'pl_nivel',
							'meta_value' => $nivel,
			    			);
					}
				    
				    //print_array($meta_query);
				    $planeaciones = get_plans($meta_query);
				    
				    // HE AQUÍ EL RESULTADO.
				    if ($planeaciones) {
				    	echo 'Se encontraron: ' . count($planeaciones) . ' planeaciones para tu búsqueda.';
				    	print_array($planeaciones);
				    } else {
				    	echo 'No se encontraron planeaciones para tus criterios de búsqueda.';
				    }
				
				} //if (isset($_POST['consultar'])) { ?>


			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>