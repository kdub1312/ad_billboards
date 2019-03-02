<?php
   /*
   Plugin Name: All District Billboards
   Plugin URI: http://alldistrict.net
   description: Easily add important notifications on your site
   Version: 1.0
   Author: Kevin Wagner
   Author URI: http://alldistrict.net
   License: GPL2
   */

function create_billboard_post_type() {
  register_post_type( 'ad_billboards',
    array(
      'labels' => array(
        'name' => __( 'Billboards' ),
        'singular_name' => __( 'Billboard' )
      ),
      'public' => true,
        'supports' => array('title', 'thumbnail')
    )
  );
}
add_action( 'init', 'create_billboard_post_type' );

function enqueue_billboard_styles() {
    wp_enqueue_style( 'billboardCSS', plugins_url( '/css/billboard-styles.css', __FILE__) );
}
add_action('wp_enqueue_scripts', 'enqueue_billboard_styles');

//display the billboard (another test comment)
function display_billboard($billboardName) {
//  echo 'This is my billboard';
    ?>
    
    <div class="billboard">
        
        <?php
    $custom_args = array(
            'post_type' => 'ad_billboards',
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'name' => $billboardName
        );

        $billboard_posts = new WP_Query($custom_args);

    if ( $billboard_posts->have_posts() ) {
        while( $billboard_posts->have_posts() ) {
            $billboard_posts->the_post();
            ?>
            
                <div class="billboard-messaging-grid">
                    <div class="grid-item">
                        <h2 class="billboard-title">
                           <span>
                            <?php the_field('primary'); ?>
                            </span>
                        </h2>
                    <div class="billboard-logins-wrapper">
                     
                        <!--CURRENT-MEMBER SIGN IN-->
                       <div class="grid-item">
                        <h3 class="billboard-heading">
                           <span>
                            Already a member? Sign in here:
                            </span>
                        </h3>
                        <button class="billboard-button btn"><a href="https://www.foodcoach.me/account/">Member Sign In</a></button>
                        </div>
                        
                        <!--NON-MEMBER SIGN UP-->
                        <div class="grid-item">
                        <h3 class="billboard-heading">
                           <span>
                            <?php the_field('heading'); ?>
                            </span>
                        </h3>
                        <button class="billboard-button btn"><a href="<?php the_field('href'); ?>"><?php the_field('cta'); ?></a></button>
                        </div>
                        
                    </div>
                        <p class="billboard-subheading">
                            <?php the_field('subheading'); ?>
                        </p>
                    </div>
                </div>
            
<?php
        }
        wp_reset_postdata();
    } 
    ?>
    
<div class="related-posts-grid">
    <?php display_related_posts(); ?>
</div>             
   </div><!--close billboard--> 

<?php
    
}

/*display related posts*/
function display_related_posts() {
    $currentID = get_the_ID();
    $tags = wp_get_post_terms( $currentID );
    if ( $tags ) {
        $tagcount = count( $tags );
        for ( $i = 0; $i < $tagcount; $i++ ) {
            $tagIDs[$i] = $tags[$i]->term_id;
        }
        $recipe_args = array(
            'post_type' => 'recipe',
            'tag__in' => $tagIDs,
            'post__not_in' => array( $currentID ),
            'posts_per_page' => 4,
            'ignore_sticky_posts' => 1,
            'category__not_in' => array( 79 )
        );
        $relatedPosts = new WP_Query( $recipe_args );
        if( $relatedPosts->have_posts() ) { 
            //loop thru related posts based on tag
            while ( $relatedPosts->have_posts() ) : $relatedPosts->the_post(); ?>
            
                <div class="related-posts-grid-item">
                    <?php $thumb_id = get_post_thumbnail_id();
            $thumb_url_array = wp_get_attachment_image_src($thumb_id, 'thumbnail-size', true);
            $thumb_url = $thumb_url_array[0]; ?>
                   <a href="<?php the_permalink(); ?>">
                       <img src="<?php echo $thumb_url; ?>" />
                    </a>
                    <p><a class="related-posts-text-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
                </div>

<?php   
        endwhile;
        }
        wp_reset_postdata();
    } 
}

?>




