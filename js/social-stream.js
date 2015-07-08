function load_social_stream() {
    var data = {
        'action': 'load_ss_template'
    };

    jQuery.post('/wp-admin/admin-ajax.php', data, function(data) {
        jQuery('.ss-container').html(data);
    });
}
