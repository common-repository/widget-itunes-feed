<?php
defined('WPINC') or die('error');

$layouts = $this->getListLayout();
$currentLayout = !empty($instance['layout']) ? $instance['layout'] : 'default';
$title = !empty($instance['title']) ? $instance['title'] : esc_html__('', 'text_domain');
$feed_url = !empty($instance['feed_url']) ? $instance['feed_url'] : 'https://rss.itunes.apple.com/api/v1/us/apple-music/coming-soon/all/100/explicit.json';
$update_time = !empty($instance['update_time']) ? $instance['update_time'] : '3600';
$number_item = !empty($instance['number_item']) ? $instance['number_item'] : '10';
?>
<p>
    <label for="">Like my work?</label>
    <br>
    <br>
    <a href='https://ko-fi.com/I3I71FSC5' target='_blank'><img height='36' style='border:0px;height:36px;' src='https://az743702.vo.msecnd.net/cdn/kofi1.png?v=2' border='0' alt='Buy Me a Coffee at ko-fi.com' /></a>
</p>
<p>
    <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php echo esc_html__('Title:', 'text_domain'); ?></label>
    <input 
        class="widefat" 
        id="<?php echo esc_attr($this->get_field_id('title')); ?>" 
        name="<?php echo esc_attr($this->get_field_name('title')); ?>" 
        type="text" 
        value="<?php echo esc_attr($title); ?>">
</p>
<p>
    <label for="<?php echo esc_attr($this->get_field_id('feed_url')); ?>"><?php echo'Feed Url:'; ?></label>
    <input 
        class="widefat" 
        id="<?php echo esc_attr($this->get_field_id('feed_url')); ?>" 
        name="<?php echo esc_attr($this->get_field_name('feed_url')); ?>" 
        type="text" 
        value="<?php echo esc_attr($feed_url); ?>">
</p>
<p>
    <label for="<?php echo esc_attr($this->get_field_id('update_time')); ?>"><?php echo'Update Time (second):'; ?></label>
    <input 
        class="widefat" 
        id="<?php echo esc_attr($this->get_field_id('update_time')); ?>" 
        name="<?php echo esc_attr($this->get_field_name('update_time')); ?>" 
        type="number" 
        value="<?php echo esc_attr($update_time); ?>">
</p>
<p>
    <label for="<?php echo esc_attr($this->get_field_id('number_item')); ?>"><?php echo'Number Item:'; ?></label>
    <input 
        class="widefat" 
        id="<?php echo esc_attr($this->get_field_id('number_item')); ?>" 
        name="<?php echo esc_attr($this->get_field_name('number_item')); ?>" 
        type="number" 
        value="<?php echo esc_attr($number_item); ?>">
</p>
<p>
    <label for="<?php echo esc_attr($this->get_field_id('layout')); ?>"><?php echo esc_html__('Layout:', 'widget-itunes-feed'); ?></label>
    <select 
        class="widefat"
        name="<?php echo esc_attr($this->get_field_name('layout')); ?>" 
        id="<?php echo esc_attr($this->get_field_id('layout')); ?>">
        <?php foreach ($layouts as $layout): ?>
            <option value="<?php echo $layout ?>" <?php selected($currentLayout, $layout) ?>>
                <?php echo $layout ?>
            </option>
        <?php endforeach ?>
    </select>
</p>