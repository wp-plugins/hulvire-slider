<div class="wrap">
    <h2><img src="<?php echo plugin_dir_url( __FILE__ ). '../images/slider-icon3.png'; ?>" /> Hulvire Slider</h2>
    <form method="post" action="options.php"> 
        <?php @settings_fields('wp_hulvire_slider-group'); ?>
        <?php @do_settings_fields('wp_hulvire_slider-group'); ?>

        <?php do_settings_sections('wp_hulvire_slider'); ?>

        <?php @submit_button(); ?>
    </form>
</div>