<div class="social-stream">
    <?php foreach($posts as $post) : ?>

        <?php if(is_twitter_post($post)) : ?>
            <div class="social-stream__post social-stream__post--twitter">
                <?php echo SS_Twitter::parse($post['text']) ?>
            </div>
        <?php elseif(is_facebook_post($post)) : ?>
            <div class="social-stream__post social-stream__post--facebook">
                <?php echo SS_Facebook::parse($post['message']) ?>
            </div>
        <?php else : ?>
            <div class="social-stream__post social-stream__post--post">
                <?php echo $post['content'] ?>
            </div>
        <?php endif ?>

    <?php endforeach ?>
</div>
