<?php
/*
Plugin Name: Theme Blvd News Scroller Widget
Description: This plugin is a simple widget with slider that rotates through posts.
Version: 1.0.4
Author: Jason Bobich
Author URI: http://jasonbobich.com
License: GPL2
*/

/*
Copyright 2012 JASON BOBICH

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/**
 * Include CSS
 */
 
function themeblvd_news_scroller_css() {		
	wp_register_style( 'themeblvd_news_scroller', plugins_url( 'assets/style.css', __FILE__ ), false, '1.0' );
	wp_enqueue_style( 'themeblvd_news_scroller' );
}
add_action( 'wp_print_styles', 'themeblvd_news_scroller_css' );

/**
 * Include JS
 */

function themeblvd_news_scroller_scripts() {
	wp_register_script( 'flexslider', plugins_url( 'assets/flexslider.js', __FILE__ ), array('jquery'), '1.8' );
	wp_register_script( 'themeblvd_news_scroller', plugins_url( 'assets/scripts.js', __FILE__ ), array('flexslider'), '1.0' );
	wp_enqueue_script( 'flexslider' );
	wp_enqueue_script( 'themeblvd_news_scroller' );
}
add_action( 'wp_enqueue_scripts', 'themeblvd_news_scroller_scripts' );

/**
 * Limit excerpt by number of words
 * 
 * @param $string string Excerpt to limit
 * @param $word_limit int Number of words to limit excerpt by
 */
 
function themeblvd_new_scroller_excerpt( $string, $word_limit ) {
	$words = explode(' ', $string, ($word_limit + 1));
	if(count($words) > $word_limit)
	array_pop($words);
	return implode(' ', $words).'...';
}

/**
 * Theme Blvd New Scroller Widget
 * 
 * @package Theme Blvd WordPress Framework
 * @author Jason Bobich
 */

class TB_Widget_News_Scroller extends WP_Widget {
	
	/* Constructor */
	
	function __construct() {
		$widget_ops = array(
			'classname' => 'tb-news_scroller_widget', 
			'description' => 'This will scroll through posts from a category.'
		);
		$control_ops = array(
			'width' => 400, 
			'height' => 350
		);
        $this->WP_Widget( 'themeblvd_news_scroller_widget', 'Theme Blvd News Scroller', $widget_ops, $control_ops );
	}
	
	/* Widget Options Form */
	
	function form($instance) {
		$defaults = array(
			'title' => '',
            'category' => '',
            'date' => 'show',
            'excerpt' => 'show',
            'image' => 'show',
            'excerpt_limit' => 20,
            'scroll_timeout' => 5,
            'scroll_direction' => 'vertical',
            'num' => 10,
            'height' => 300
        );        
        $instance = wp_parse_args( (array) $instance, $defaults );
        ?>
		<!-- Title -->
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'themeblvd'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>
		
		<!-- Category -->
		<p>
			<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e( 'Category', 'themeblvd' ); ?> </label>
			<select class="widefat" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>">
				<?php 
				$list = null;
				$categories = get_categories();
				$options = array( '' => __( 'All Categories', 'themeblvd' ) );
				foreach ( $categories as $category ) $options[$category->term_id] = $category->name;
				foreach ( $options as $id => $name ) {
					$selected = '';
					if($id == $instance['category']) $selected = 'selected="selected"';
					$list .= '<option '.$selected.' value="'.$id.'">'.$name.'</option>';
				}
				echo $list;
				?>
			</select>
		</p>
		
		<!-- Show/Hide Date -->
		<p>
			<label for="<?php echo $this->get_field_id('date'); ?>"><?php _e( 'Show post dates?', 'themeblvd' ); ?> </label>
			<select class="widefat" id="<?php echo $this->get_field_id('date'); ?>" name="<?php echo $this->get_field_name('date'); ?>">
				<?php 
				$list = null;
				$options = array( 'show', 'hide' );
				foreach ( $options as $option ) {
					$selected = "";
					if($option == $instance['date']) $selected = 'selected="selected"';
					$list .= "<option $selected value='$option'>$option</option>";
				}
				echo $list;
				?>
			</select>
		</p>
		
		<!-- Show/Hide Excerpt -->
		<p>
			<label for="<?php echo $this->get_field_id('excerpt'); ?>"><?php _e( 'Show post excerpts?', 'themeblvd' ); ?> </label>
			<select class="widefat" id="<?php echo $this->get_field_id('excerpt'); ?>" name="<?php echo $this->get_field_name('excerpt'); ?>">
				<?php 
				$list = null;
				$options = array( 'show', 'hide' );
				foreach ( $options as $option ) {
					$selected = "";
					if($option == $instance['excerpt']) $selected = 'selected="selected"';
					$list .= "<option $selected value='$option'>$option</option>";
				}
				echo $list;
				?>
			</select>
		</p>
		
		<!-- Show/Hide Featured Image -->
		<p>
			<label for="<?php echo $this->get_field_id('image'); ?>"><?php _e( 'Show featured images?', 'themeblvd' ); ?> </label>
			<select class="widefat" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>">
				<?php 
				$list = null;
				$options = array( 'show', 'hide' );
				foreach ( $options as $option ) {
					$selected = "";
					if($option == $instance['image']) $selected = 'selected="selected"';
					$list .= "<option $selected value='$option'>$option</option>";
				}
				echo $list;
				?>
			</select>
		</p>
		
		<!-- Scroll Direction -->
		<p>
			<label for="<?php echo $this->get_field_id('scroll_direction'); ?>"><?php _e( 'How to transition?', 'themeblvd' ); ?> </label>
			<select class="widefat" id="<?php echo $this->get_field_id('scroll_direction'); ?>" name="<?php echo $this->get_field_name('scroll_direction'); ?>">
				<?php 
				$list = null;
				$options = array( 'vertical' => __('Scroll Vertical', 'themeblvd'), 'fade' => __('Fade', 'themeblvd') );
				foreach ( $options as $key => $name ) {
					$selected = "";
					if($key == $instance['scroll_direction']) $selected = 'selected="selected"';
					$list .= "<option $selected value='$key'>$name</option>";
				}
				echo $list;
				?>
			</select>
		</p>
				
		<!-- Scroll Timeout -->
		<p>
			<label for="<?php echo $this->get_field_id('scroll_timeout'); ?>"><?php _e('Scroll Timeout', 'themeblvd'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('scroll_timeout'); ?>" name="<?php echo $this->get_field_name('scroll_timeout'); ?>" type="text" value="<?php echo esc_attr($instance['scroll_timeout']); ?>" />
			<span style="display:block;padding:5px 0" class="description"><?php _e( 'Enter in the number of seconds in between the posts scrolling. Set this number to 0 if you don\'t want the posts to auto scroll.', 'themeblvd' ); ?></span>
		</p>
		
		<!-- Excerpt Limit -->
		<p>
			<label for="<?php echo $this->get_field_id('excerpt_limit'); ?>"><?php _e('Excerpt Limit', 'themeblvd'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('excerpt_limit'); ?>" name="<?php echo $this->get_field_name('excerpt_limit'); ?>" type="text" value="<?php echo esc_attr($instance['excerpt_limit']); ?>" />
			<span style="display:block;padding:5px 0" class="description"><?php _e( 'If you have the excerpts set to show you can enter in a number of total <strong>words</strong> to automatically limit the excerpts by. Note that WordPress limits excerpts to 55 words by default. To allow excerpts to show normally, simply leave this blank.', 'themeblvd' ); ?></span>
		</p>
		
		<!-- Number of Posts -->
		<p>
			<label for="<?php echo $this->get_field_id('num'); ?>"><?php _e('Maximum Number of Posts', 'themeblvd'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('num'); ?>" name="<?php echo $this->get_field_name('num'); ?>" type="text" value="<?php echo esc_attr($instance['num']); ?>" />
			<span style="display:block;padding:5px 0" class="description"><?php _e( 'Enter in a maximum number of posts for the scroller. Leave blank to pull all posts from specified category.', 'themeblvd' ); ?></span>
		</p>
		
		<!-- Height -->
		<p>
			<label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Scroller Height', 'themeblvd'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo esc_attr($instance['height']); ?>" />
			<span style="display:block;padding:5px 0" class="description"><?php _e( 'Enter in a number of pixels to make the height of each visible section. Depending on the different settings you\'ve chosen, your scroller will have varying amounts of content, however it\'s important that the each visible section have the same fixed height for the scroller to animate properly. So feel free to play with this number until it looks how you want.', 'themeblvd' ); ?></span>
		</p>
        <?php
	}
	
	/* Update Widget Settings */
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['category'] = strip_tags($new_instance['category']);
        $instance['date'] = strip_tags($new_instance['date']);
        $instance['excerpt'] = strip_tags($new_instance['excerpt']);
        $instance['image'] = strip_tags($new_instance['image']);
        $instance['excerpt_limit'] = strip_tags($new_instance['excerpt_limit']);
        $instance['scroll_timeout'] = strip_tags($new_instance['scroll_timeout']);
        $instance['scroll_direction'] = strip_tags($new_instance['scroll_direction']);
        $instance['num'] = strip_tags($new_instance['num']);
        $instance['height'] = strip_tags($new_instance['height']);
        return $instance;
	}
	
	/* Display Widget */
	
	function widget($args, $instance) {
		global $post;
		extract( $args );
		// Setup args
		$i = 1;
		$height = $instance['height'] ? $instance['height'] : 130;
		$excerpt_limit = $instance['excerpt_limit'] ? $instance['excerpt_limit'] : 55;
		$number_posts = $instance['num'] ? $instance['num'] : 10;
		$category = $instance['category'] ? $instance['category'] : '';
		$scroll_timeout = strval( $instance['scroll_timeout'] );
		$args = array(
			'category' => $category,
			'numberposts' => $number_posts,
		);
		// Get Posts
		$posts = get_posts( $args );
		$count = count($posts);
		// Start output
		echo $before_widget;
		$title = apply_filters( 'widget_title', $instance['title'] );
		if ( $title )
			echo $before_title . $title . $after_title;
		?>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			$(window).load(function() {
				$('#<?php echo $widget_id; ?> .flexslider').flexslider({
					<?php if( $instance['scroll_direction'] == 'fade' ) : ?>
					animation: 'fade',
					<?php else : ?>
					animation: 'slide',
					slideDirection: '<?php echo $instance['scroll_direction']; ?>', // vertical or horizontal ... removed horizontal from selections because can't figure out bug.
					<?php endif; ?>
					controlsContainer: '#<?php echo $widget_id; ?> .scroller-nav',
					controlNav: false,
					animationDuration: 800,
					<?php if( $scroll_timeout == 0 ) : ?>
					slideshow: false,
					<?php else : ?>
					slideshow: true,
					slideshowSpeed: <?php echo $scroll_timeout;?>000,
					<?php endif; ?>
					start: function(slider){
						var num = 2, // account for "clone" slide plugin adds
							date = slider.container.find('li:nth-child('+num+')').find('.scroller-date').text();
						slider.closest('.themeblvd-news-scroller').find('.scroller-nav span').text(date).fadeIn('fast');
						$('#<?php echo $widget_id; ?> .themeblvd-news-scroller').fadeIn();
					},
					before: function(slider){
						slider.closest('.themeblvd-news-scroller').find('.scroller-nav span').slideUp();
					},
					after: function(slider){
						var num = slider.currentSlide+2, // account for "clone" slide plugin adds
							date = slider.container.find('li:nth-child('+num+')').find('.scroller-date').text();
						slider.closest('.themeblvd-news-scroller').find('.scroller-nav span').text(date).fadeIn('fast');
					}
				});
			});
		});
		</script>
		<div class="themeblvd-news-scroller flex-container">
			<div class="scroller-nav scroller-nav-<?php echo $instance['scroll_direction']; ?>"><span></span><!-- nav inserted here --></div>
			<div class="scroller-wrap">
				<div class="flexslider">
					<ul class="slides">
						<?php foreach( $posts as $post ) : ?>
							<?php setup_postdata($post); ?>
							<li class="scroller-post" style="height:<?php echo $height; ?>px;">
								<div class="scroller-header">
									<h4><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h4>
								</div><!-- .scroller-header (end) -->
								<?php if( $instance['image'] == 'show' ) : ?>
									<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'grid_6' ); ?>
									<div class="scroller-image">
										<img src="<?php echo $image[0]; ?>" />
									</div><!-- .scroller-image (end) -->
								<?php endif; ?>
								<?php if( $instance['excerpt'] == 'show' ) : ?>
									<div class="scroller-content">
										<p><?php echo themeblvd_new_scroller_excerpt( get_the_excerpt(), $excerpt_limit ); ?></p>
									</div><!-- .scroller-content (end) -->
								<?php endif; ?>
								<?php if( $instance['date'] == 'show' ) : ?>
									<span class="scroller-date"><?php the_time( get_option('date_format') ); ?></span>
								<?php endif; ?>
							</li>
							<?php $i++; ?>
						<?php endforeach; ?>	
					</ul>
				</div><!-- .flexslider (end) -->
			</div><!-- .scroller-wrap (end) -->
		</div><!-- .themeblvd-news-scroller (end) -->
		<?php
		echo $after_widget;		
	}

}
add_action( 'widgets_init', create_function( '', 'register_widget("TB_Widget_News_Scroller");' ) );