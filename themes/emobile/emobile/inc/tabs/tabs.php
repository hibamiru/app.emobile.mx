    <link href="<?php echo $blog_title = get_bloginfo('template_url'); ?>/tabs/css/jquery.smooth_tabs.css" rel="stylesheet" type="text/css" media="all" />

    <script type="text/javascript" src="<?php echo $blog_title = get_bloginfo('template_url'); ?>/tabs/js/jquery-1.3.2.min.js"></script>
    <script type="text/javascript" src="<?php echo $blog_title = get_bloginfo('template_url'); ?>/tabs/js/jquery.smooth_tabs.js"></script>
    <script type="text/javascript">
    	$(document).ready(function(){
            $('.smoothTabs').smoothTabs(180);
    	}); 
    </script>

 	  <div id="wrapper-tabs" class="smoothTabs">
	  <ul class="option-tabs">
          
           <?php while ( have_posts() ) : the_post(); 
   			 $titulo = the_title("", "", false); 
			 echo '<li>' . $titulo . '</li>';
			endwhile;  
            ?>
     </ul>
	  <?php  while ( have_posts() ) : the_post();  ?>
	  <div>
            <?php the_content(); ?>
      </div>
				

      <?php endwhile; ?>
    
    
        
        
    </div><!-- end div.smoothTabs -->           
