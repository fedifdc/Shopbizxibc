<?php
/**
 * Custom Slider
 *
 * @package       CUSTOMSLID
 * @author        Novan Noviansyah Pratama
 * @version       1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:   Custom Slider
 * Plugin URI:    https://mydomain.com
 * Description:   Custom slider untuk dengan 2 row
 * Version:       1.0.0
 * Author:        Novan Noviansyah Pratama
 * Author URI:    https://your-author-domain.com
 * Text Domain:   custom-slider
 * Domain Path:   /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

// Add Script and CSS
function custom_enqueue_swiper() {
    // Enqueue Swiper CSS
    wp_enqueue_style('swiper-css', '//cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), null);

    // Enqueue Swiper JS
    wp_enqueue_script('swiper-js', '//cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'custom_enqueue_swiper');

function custom_swiper_carousel_with_custom_cards($atts) { ?>
    <style>
        .swiper-container {
            background-color: white;
			overflow: hidden;
            padding-bottom: 30px; /* Space for pagination */
			width: 100%;
        }
        
        .swiper-slide {
			position:relative;
			border-radius: 14px;
			margin-bottom: 10px;
        }

		.swiper-slide a {
			border-radius: inherit;
		}
		.swiper-slide a img {
			display: block;
			width: 100%;
			height: 300px;
			object-fit: cover;
			border-radius: inherit;
			user-select: none;
		}
		
		.product-title-card{
			padding: 5px;
			position: absolute;
			left: 50%;
			bottom: 50px;
			transform:translate(-50%,-20%);
			background: darkorange;
			height: 35px;
			width: 100%;
			overflow: hidden;
			text-overflow: ellipsis;
			-webkit-box-shadow: 10px 10px 72px 0px #ebebeb;
			-moz-box-shadow: 10px 10px 72px 0px #ebebeb;
			box-shadow: 10px 10px 72px 0px #ebebeb;
			transition: all 0.5s linear;
			text-align: center;
		}
		.product-title-card span{
			font-weight: bold;
			font-family: arial;
			text-align: center;
			font-size: 1.1em;
			text-overflow: ellipsis;
			color: white;
			height: 35px;
		}

        
        
        
        .swiper-button-next, .swiper-button-prev {
            color: #0073aa;
            height: 60px;
            width: 60px;
        }

        .swiper-pagination-bullet {
            background-color: #0073aa;
        }
    </style>

<?php
           // Extract shortcode attributes and set default values
        $atts = shortcode_atts(array(
            'type' => 'latest', // Type of products to display: latest, featured, etc.
            'posts_per_page' => 15, // Number of products to display
        ), $atts, 'swiper_carousel');
        
        // Set up the product query arguments
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => $atts['posts_per_page'],
            'orderby'        => 'date', // Default order is by date (latest products)
            'tax_query'      => array(
                'relation' => 'AND', // Menggabungkan semua kondisi
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => array('challenge'), // Kategori yang disembunyikan
                    'operator' => 'NOT IN',
                ),
                array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => array('exclude-from-catalog'), // Menyembunyikan produk yang disembunyikan dari katalog
                    'operator' => 'NOT IN',
                ),
            ),
        );
        
        // Jika ingin menampilkan hanya produk unggulan
        if ($atts['type'] == 'featured') {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured',
            );
        }
        
        // Get products
        $products = new WP_Query($args);
    ob_start();

    if ($products->have_posts()) : ?>
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php while ($products->have_posts()) : $products->the_post();
                    // Get the product object from the post ID
                    $product = wc_get_product(get_the_ID());
					$image_id = $product->get_image_id(); 
					$image_url = wp_get_attachment_image_url($image_id, 'extra_large'); 
					
					if (!$image_url) {
                        $image_url = wc_placeholder_img_src('extra_large');
                    }
                    if ($product) : ?>
                        <div class="swiper-slide">
							<a href="<?php the_permalink(); ?>">
								<img src="<?php echo $image_url?>"/>
								<div class="product-title-card">
									<span><?php the_title(); ?></span>
								</div>
							</a>
                        </div>
                    <?php endif;
                endwhile; ?>
            </div>

            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>

            <!-- Add Navigation -->
<!--             <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div> -->

        </div>

        <script type="text/javascript">
            jQuery(document).ready(function($) {
                var swiper = new Swiper('.swiper-container', {
                    autoplay: false,
					centeredSlides : false,
					initialSlides: 5,
					slidesPerView: 5,
					grabCursor: true,
					spaceBetween: 20,
					grid: {
						rows:2,
						fill: 'row'
					},
					pagination: {
						el: ".swiper-pagination",
						clickable: true,
					  },
					breakpoints: {
						200: {
							slidesPerView: 2,
							spaceBetween: 20,
						},
						768: {
							slidesPerView: 3,
							spaceBetween: 20,
						},
						1024: {
							slidesPerView: 5,
							spaceBetween: 20,
						},
					}
                  
                });
            });
        </script>
    <?php
    endif;

    wp_reset_postdata();

    return ob_get_clean();
}

add_shortcode('swiper_carousel', 'custom_swiper_carousel_with_custom_cards');
