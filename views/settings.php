<div class="wrap">
    <h2>Social Stream</h2>
    <form method="post" action="options.php">
    	<?php
    		 settings_fields('ss_group');
    		 do_settings_sections('social_stream');
    		 submit_button();
    	?>
    </form>
</div>
