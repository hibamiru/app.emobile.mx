<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package WordPress
 * @subpackage EMobile
 * @since EMobile 1.0
 */
?>

	</div><!-- #main -->
	<footer id="colophon" role="contentinfo">
        <div id="footer-content" class="container">
            
            <div id="creditos" class="group">	
            	
            	<div class="col-sm-6">
            		<p id="site-name-ft"> &copy; <?php bloginfo('name'); ?> <?php echo date('Y'); ?></p>
            	</div>			
				<div class="col-sm-6">
					<h3 id="sign-sh"><a href="http://www.solucioneshipermedia.com/">Soluciones Hipermedia | Desarrollo web</a></h3>
                </div>

			</div><!-- #creditos -->
            
		</div><!-- #footer-content -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>


</body>
</html>