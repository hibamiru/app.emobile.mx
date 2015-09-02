<?php
/**
 * Template Name: Reporte participantes
 * Description: Imprime una lista de todos los participantes del proyecto
 *
 * @package WordPress
 * @subpackage EMobile
 * @since EMobile 1.0
 */

get_header(); ?>

<div id="primary-full">
	
	<div id="content" role="main">
	
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'content', 'page' ); ?>				
		<?php endwhile; // end of the loop. ?>
			
		<?php $emobileUsers = get_users( array( 'fields' => 'ID' ) ); ?>
		<!-- TABLA DE PARTICIPANTES -->
		<table class="table table-striped">
			<!-- // Array of WP_User objects. -->
			<?php foreach ( $emobileUsers as $user ) { ?>
				
				<?php // Preparo información por participante
					// Reseteo variables
					$cctUsuario = '';
					$escuelaUsuario = '';
					$localidadUsuario = ''; 
					$municipioUsuario = ''; 
					$nivelUsuario = ''; 
					$nombreUsuario = '';
					$gradosUsuario = ''; 
					$alumnosUsuario = ''; 

					// Convierto en array el objeto
					$emobileUser = (array) $user;
					// Asigno el ID a una variable
					$emobileUser = 'user_' . $emobileUser[0];
					// CCT
					$cctUsuario = get_field('p_clave_cct', $emobileUser );
					// Si el usuario tiene CCT
					if ($cctUsuario != '') :
						// Nombre CT
						$escuelaUsuario = get_field('p_nombre_ct', $emobileUser );
						// Licalidad CT
						$localidadUsuario = get_field('p_localidad', $emobileUser );
						// Municipio
						$municipioUsuario = get_field('p_municipio', $emobileUser );
						// Nivel
						$nivelUsuario = get_field('p_nivel_se', $emobileUser );
						// Nombre del participante
						$nombreUsuario = get_field('p_nombre', $emobileUser ) . ' ';
						$nombreUsuario .= get_field('p_apellido_p', $emobileUser ) . ' ';
						$nombreUsuario .= get_field('p_apellido_m', $emobileUser );
						// Grados
						$funcionesUsuario = get_field('p_funcion_grado', $emobileUser );
						$gradosUsuario = '';
						foreach ($funcionesUsuario as $funcionUsuario) {
						    $gradosUsuario .= $funcionUsuario . ', ';
						}					
						// Alumnos
						$alumnosUsuario = get_field('p_cantidad_alumnos', $emobileUser );
						// Lider creativo
						$liderUsuario = get_field('p_lider_c', $emobileUser );
					endif; 
					 get_userdata( $userid ); 

				?>
				

					<tr>
						<th><?= $cctUsuario;?></th>
						<th><?= $escuelaUsuario;?></th>
						<th><?= $localidadUsuario; ?></th>
						<th><?= $municipioUsuario; ?></th>
						<th><?= $nivelUsuario; ?></th>
						<th><?= $nombreUsuario; ?></th>
						<th><?= $gradosUsuario; ?></th>
						<th><?= $alumnosUsuario; ?></th>
					</tr>
			<?php } ?>
		</table>
	</div><!-- #content -->
</div><!-- #primary -->

<?php get_footer(); ?>