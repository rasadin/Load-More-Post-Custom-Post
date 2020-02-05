/**
* Load More custom post portfolio
* @author 
* @since 1.0.0
*/
function loadmore_portfolios() {
	if(isset($_POST['per_page']) && $_POST['offset'] ) {
		$args = array(
			'post_type' 		=> 'portfolio',
			'posts_per_page'	=> $_POST['per_page'],
			'offset'			=> $_POST['offset'],
			'post_status'		=> 'publish',
		);
		$wp_posts = wp_count_posts('portfolio');
		$total_posts = $wp_posts->publish;
		$query = new \WP_Query($args); \wp_reset_postdata();
		$results = [];
		foreach($query->posts as $post) {
			$results[] = array(
				'id'			=> $post->ID,
				'title'         => get_the_title($post->ID),
				'permalink'     => get_the_permalink($post->ID),
				'thumbanil_url' => get_the_post_thumbnail_url($post->ID),
			);
		}	
		$data = array(
			'portfolios' 	        => $results,
			'totalPortfolios'    	=> $total_posts,
		);
	}else {
		return $data = false;
	}    
	echo wp_json_encode($data);
	die();
}
add_action( 'wp_ajax_loadmore_portfolios', 'loadmore_portfolios');
add_action( 'wp_ajax_nopriv_loadmore_portfolios','loadmore_portfolios');


/**
 * Initial Dispaly 
 * @author 
 * @since 1.0.0
 * @usecase []
 */
function portfolio_grid($atts, $content=null) {
	$options = extract(shortcode_atts(array(
		'items' => get_option('posts_per_page') //this is declared in options under functions.php
	), $atts));

	$args = array(
		'post_type'         => 'portfolio',
		'post_status'       => 'publish',
		'posts_per_page'    =>  $items,
	);

	$query = new \WP_Query($args); wp_reset_query();
	$html = '<div class="row">';
	if( !empty($query->posts) ) {
		foreach($query->posts as $post) {
			$html .= '
			<div class="col-sm-6 col-md-6 col-lg-4">
				<div class="portfolio-item">
					<div class="portfolio-img">
						'.get_the_post_thumbnail( $post->ID, 'full' ).'
						<div class="p-hover">
							<a href="#">Preview</a>
						</div>
					</div>
					<div class="portfolio-content">
						<h4>'.get_the_title($post->ID).'</h4>
						<p>'.get_the_excerpt($post->ID).'</p>
					</div>
				</div>
			</div>';
		}
	}
	$html .= '</div>';
	return $html;
}
add_shortcode( 'wcc_portfolios', 'portfolio_grid');



/**
* first_show_custom_post_item_with_scripts
* @author Rasadin
* @since 1.0.0
*/
function first_show_custom_post_item_with_scripts() { ?>
	<div class="container container-lg">
    <div class="row">
        <div class="col-md-12">
            <div id="primary" class="webalive-content-area">
				<main id="main" class="webalive-site-main">
                    <?php echo do_shortcode('[wcc_portfolios]'); ?>
                     <!-- Appned Ajax Rendered Data -->
                    <div class="row js-portfolio-appender">
                        <script type="text/html" id="tmpl-load-portfolios">
                            <# _.each( data.portfolios, function( portfolio, index ) { #>
                                <div class="col-sm-6 col-md-6 col-lg-4">
                                    <div class="portfolio-item">
                                        <div class="portfolio-img">
                                            <img src="{{portfolio.thumbanil_url}}" alt="{{portfolio.title}}">
                                            <div class="p-hover">
                                                <a href="#">Preview</a>
                                            </div>
                                        </div>
                                        <div class="portfolio-content">
                                            <h4>{{portfolio.title}}</h4>
                                        </div>
                                    </div>
                                </div>
                            <# }) #>
                        </script>
                    </div>
                    <!-- Load more button -->
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button class="btn btn-info btn-lg load-btn js-load-more-portfolio">Load More</button>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
</div>
<?php }
add_shortcode( 'first_show_custom_post_item_with_scripts_shortcode', 'first_show_custom_post_item_with_scripts');


////////////functions.php//////////////
wp_enqueue_script( 'webalive', get_template_directory_uri() . '/assets/js/theme.js', array('jquery', 'wp-util'), rand(), true );
je file e js likhbo tar vithore wp-util dite hbe ei vabe. 


//vivinno value ei vabe ante hbe functions.php te......
	$options = array(
        'home_url'         	=> home_url('/'),
        'admin_url'         => admin_url(''),
        'ajax_url'          => admin_url('admin-ajax.php'),
		'ajax_nonce'        => wp_create_nonce('ah3jhlk(765%^&ksk!@45'),
		'posts_per_page' 	=> get_option( 'posts_per_page' ), //this get the value form admin panel settings>readings
    );
	wp_localize_script('webalive', 'theme_localizer', $options);
 


