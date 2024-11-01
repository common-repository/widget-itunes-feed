<?php
defined('WPINC') or die('error');

$feed = ItunesFeedHelper::getFeed($this, $instance);
$data = $feed['items'];
$items = $data->feed->results;

echo $args['before_widget'];
if (!empty($instance['title'])) {
    echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
}
$number_item = (int) $instance['number_item'];
?>
<div class="widget-<?php echo $this->id ?> ff-feed-items">
    <?php foreach ($items as $key => $item): ?>
        <?php 
            if ($key >= $number_item ) {
                break;
            }

            $isLong = $key + 1 > 99 ? true : false;
        ?>
        <div class="ff-feed-item">
            <div class="ff-feed-item__rank <?php echo $isLong ? 'ff-feed-item__rank--long' : '' ?>">
                <?php echo $key + 1 ?>
            </div>
            <div class="ff-feed-item__image">
                <a href="<?php echo $item->url ?>" target="_blank">
                    <img src="<?php echo $item->artworkUrl100 ?>" alt="<?php echo $item->name ?>">
                </a>
            </div>
            <div class="ff-feed-item__detail">
                <div class="ff-feed-item__title">
                    <a href="<?php echo $item->url ?>" target="_blank">
                        <?php echo $item->name ?>
                    </a>
                </div>
                <div class="ff-feed-item__artist">
                    <?php echo $item->artistName ?>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>
<?php if (!empty($feed['needUpdate'])): ?>
<script>
    window.widget_itunes_feed_need_update = window.widget_itunes_feed_need_update || [];
    window.widget_itunes_feed_need_update.push(<?php echo $this->number ?>);
</script>
<?php endif ?>
<?php
echo $args['after_widget'];
