<?php 
defined('WPINC') or die('error');

class ItunesFeedHelper
{
    public static function getFeed($widget, $settings)
    {
        $feed = array();
        $cachePath = __DIR__ . '/cache';
        $url = $settings['feed_url'];

        $updateTime = +$settings['update_time'];
        $updateTimeFile = $cachePath . '/lastUpdate-' . md5($url) . '-' . $widget->number . '.txt';
        $lastUpdate = file_exists($updateTimeFile) ? +file_get_contents($updateTimeFile) : 0;
        $now = time();

        $cacheFile = $cachePath . '/cache-' . md5($url) . '-' . $widget->number . '.txt';
        
        if ($lastUpdate + $updateTime < $now && file_exists($cacheFile)) {
            $feed['needUpdate'] = true;
        }

        if (file_exists($cacheFile)) {
            $feed['items'] =  @json_decode(@file_get_contents($cacheFile));
        } else {
            $feed['items'] = self::setCache($cacheFile, $updateTimeFile, $url);
        }
        
        return $feed;
    }

    protected static function setCache($cacheFile, $updateTimeFile, $url)
    {
        $cachePath = __DIR__ . '/cache';

        if (!is_dir($cachePath)) {
            wp_mkdir_p($cachePath);
        }

        file_put_contents($updateTimeFile, time());

        $res = wp_remote_get($url);

        if ($res['response']['code'] !== 200) {
            throw new Exception("Could not get data from itune feed", 500);
        }

        file_put_contents($cacheFile, $res['body']);
        file_put_contents($updateTimeFile, time());

        return @json_decode($res['body']);
    }

    public static function updateCache()
    {
        $cachePath = __DIR__ . '/cache';

        if (!is_dir($cachePath)) {
            wp_mkdir_p($cachePath);
        }

        $id = filter_input(INPUT_GET, 'id');
        if (!$id) {
            die('missing id');
        }

        $widgets = get_option('widget_widget-itunes-feed');
        if (empty($widgets[$id])) {
            die('widget not found');
        }

        $widget = $widgets[$id];

        $url = $widget['feed_url'];

        $updateTime = (int) $widget['update_time'];
        $updateTimeFile = $cachePath . '/lastUpdate-' . md5($url) . '-' . $id . '.txt';
        $lastUpdate = file_exists($updateTimeFile) ? +file_get_contents($updateTimeFile) : 0;
        $now = time();

        $cacheFile = $cachePath . '/cache-' . md5($url) . '-' . $id . '.txt';

        if ($lastUpdate + $updateTime < $now) {
            self::setCache($cacheFile, $updateTimeFile, $url);
        }
    }
}