<div id="footer-barra-social" class="group">  
    <div id="footer-compartir">
    	<div class="recomienda">Compartir:</div>            
        
        <!-- FACEBOOK --> 
        <div class="bt-share">        
            <a href="<?php the_permalink(); ?>" 
              onclick="
                window.open(
                  'https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(location.href), 
                  'facebook-share-dialog', 
                  'width=626,height=436'); 
                return false;">
                <img src="<?php echo get_bloginfo('template_url'). '/images/social-share-fb.png'; ?>" /> 
          	</a>
        </div><!-- .fb-share -->
                         
       	<!-- TWITTER -->
        <div class="bt-share">
        	<a href="#"
            	onclick="popUp=window.open(
				'https://twitter.com/share?text=<?php the_title(); ?>&url=<?php the_permalink(); ?>', 
                'popupwindow', 
                'scrollbars=yes,width=630,height=440');
                popUp.focus();
                return false">
                <img src="<?php echo get_bloginfo('template_url'). '/images/social-share-tw.png'; ?>"/>
             </a>
        </div><!-- .tw-share -->
        
        <!-- GOOGLE +1 -->
        <div class="bt-share">
        	<a href="#" 
                onclick="popUp=window.open(
                            'https://plus.google.com/share?url=<?php the_permalink(); ?>', 
                            'popupwindow', 
                            'scrollbars=yes,width=630,height=440');
                            popUp.focus();
                            return false">
                <img src="<?php echo get_bloginfo('template_url'). '/images/social-share-gp.png'; ?>">
            </a>
    	</div>
                
        <!-- LINKEDIN -->
        <div class="bt-share">
        	<a href="#"
        	onclick="popUp=window.open(
					'http://www.linkedin.com/shareArticle?mini=true&url=<?php the_permalink(); ?>&title=<?php the_title(); ?>&summary=&source=', 
                    'popupwindow', 
                    'scrollbars=yes,width=630,height=440');
                     popUp.focus();
                     return false">
                    	<img src="<?php echo get_bloginfo('template_url'). '/images/social-share-ld.png'; ?>"/>
        	</a>
        </div><!-- .tw-share -->
        
        <!-- LINKEDIN -->
        <?php //obtengo el url de la imagen para hacer el PIN 
		$image_obt = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail' );
		$pin_image = $image_obt['0']; ?>        
        <div class="bt-share">
        	<a href="#"
        	onclick="popUp=window.open(
					'//www.pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&media=<?php echo $pin_image; ?>&description=<?php the_title(); ?>', 
                    'popupwindow', 
                    'scrollbars=yes,width=630,height=440');
                     popUp.focus();
                     return false">
                    	<img src="<?php echo get_bloginfo('template_url'). '/images/social-share-pt.png'; ?>"/>
        	</a>
        </div><!-- .tw-share -->
		<script type="text/javascript" async src="//assets.pinterest.com/js/pinit.js"></script>
                
	</div><!-- #compartir -->  
</div><!-- #footer-barra-social -->   