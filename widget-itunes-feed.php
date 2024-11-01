<?php
/*
Plugin Name: Widget iTunes Feed
Plugin URI: https://wordpress.org/plugins/widget-itunes-feed/
Description: Display itunes feed on your site
Version: 1.1
Author: Mr. Meo
Author URI: https://funfis.com
*/

defined('WPINC') or die('error');

class WidgetItunesFeed extends WP_Widget
{
	public $args = array(
		'before_title'  => '<h4 class="widgettitle">',
		'after_title'   => '</h4>',
		'before_widget' => '<div class="widget-wrap">',
		'after_widget'  => '</div></div>'
	);
	
	protected $layoutPaths = array();

	function __construct()
	{
		$this->layoutPaths[] = get_template_directory() . '/widget-itunes-feed/';
		$this->layoutPaths[] = __DIR__ . '/layouts/';

		wp_enqueue_style('widget-itunes-feed-css', plugins_url('assets/itunes-feed.css', __FILE__));
		parent::__construct(
			'widget-itunes-feed',
			'Widget Itunes Feed'
		);
	}

	protected function getListLayout()
	{
		$layouts = array();
		foreach ($this->layoutPaths as $path) {
			if (!is_dir($path)) {
				continue;
			}

			$items = list_files($path, 1);
			foreach ($items as $item) {
				$info = pathinfo($item);
				if (isset($info['extension']) 
					&& $info['extension'] === 'php' 
					&& !in_array($info['filename'], $layouts)) {

					$layouts[] = $info['filename'];
				}
			}
		}

		sort($layouts);

		return $layouts;
	}

	protected function getLayoutPath($layout)
	{
		foreach ($this->layoutPaths as $path) {
			$file = $path . $layout . '.php';
			if (is_file($file)) {
				return $file;
			}
		}

		return __DIR__ . '/layouts/default.php';
	}

	public function widget($args, $instance)
	{
		require_once __DIR__ . '/helper.php';
		$layout = !empty($instance['layout']) ? $instance['layout'] : 'default';

		include $this->getLayoutPath($layout);
	}

	public function form($instance)
	{
		include __DIR__ . '/admin/form.php';
	}

	public function update($new_instance, $old_instance)
	{
		$instance = array();
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
		$instance['feed_url'] = (!empty($new_instance['feed_url'])) ? $new_instance['feed_url'] : '';
		$instance['update_time'] = (!empty($new_instance['update_time'])) ? $new_instance['update_time'] : '';
		$instance['number_item'] = (!empty($new_instance['number_item'])) ? $new_instance['number_item'] : '';
		$instance['layout'] = (!empty($new_instance['layout'])) ? $new_instance['layout'] : 'default';

		return $instance;
	}
}

add_action('widgets_init', function () {
	register_widget('WidgetItunesFeed');
});

function widget_itunes_feed_update_cache() {
	require_once __DIR__ . '/helper.php';
	ItunesFeedHelper::updateCache();
	die('done');
}

add_action('wp_ajax_nopriv_update_itunes_feed_cache', 'widget_itunes_feed_update_cache');
add_action('wp_ajax_update_itunes_feed_cache', 'widget_itunes_feed_update_cache');
add_action('wp_footer', function() {
	wp_enqueue_script( "widget_itunes_feed_js", plugins_url('/assets/itunes-feed.js', __FILE__), array('jquery') );
	wp_localize_script( 'widget_itunes_feed_js', 'widgetItuneFeedData', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
});
