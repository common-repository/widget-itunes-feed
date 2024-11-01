jQuery(document).ready(function($) {
    var needUpdate = window.widget_itunes_feed_need_update || [];
    var ajaxUrl = window.widgetItuneFeedData.ajaxurl;

    needUpdate.forEach(function(id) {
        $.ajax({
            url: ajaxUrl,
            data: {
                action: 'update_itunes_feed_cache',
                id: id,
            }
        });
    });
});