<?php

/**
 * front-end Myslideshow short code
 * @author Shailesh Gajare
 */

/**
 * function for Display Slideshow shortcode and related functionality.
 * @author Shailesh Gajare
 * @param None
 * @return HTML design of slideshow shortcode
 */
add_shortcode('myslideshow', 'myslideshow_function');

function myslideshow_function()
{
    global $wpdb;
    $slideshow = $wpdb->prefix . "slideshow";
    ob_start();
?>
    <link href="<?php echo SLIDESHOW_PLUGIN_DRI_URL; ?>front/inc/css/slideshow-style.css" rel="stylesheet">
    <!-- Slideshow container -->
    <div class="slideshow-container">
    <?php 
             $slide_sql = "SELECT * FROM $slideshow WHERE status = '1' ORDER BY `slider_position`;";
             $slide_result = $wpdb->get_results($slide_sql);
             if ($slide_result){
                foreach ($slide_result as $slide_value) {

                   if(isset($slide_value->image) && !empty($slide_value->image)) {
                        $imageExist = wp_get_attachment_metadata($slide_value->image);
                        if(isset($imageExist) && !empty($imageExist)) {
                            $img_url = wp_get_attachment_url($slide_value->image);
                            $slider_name = $slide_value->name;
                            $slider_description = $slide_value->description;
                            if(!empty($img_url)) {
                                $url = $img_url;
                            } else {
                                $url = SLIDESHOW_PLUGIN_PLACEHOLDER_IMAGE_URL;
                            }

                        }else{
                            $url = SLIDESHOW_PLUGIN_PLACEHOLDER_IMAGE_URL;
                        }   
                    }else{
                        $url = SLIDESHOW_PLUGIN_PLACEHOLDER_IMAGE_URL;
                    }   
        ?>
        <!-- Full-width images with number and caption text -->
        <div class="mySlides fade">
            <img src="<?php echo $url; ?>" style="width:100%">
            <?php if( !empty($slider_name)){ ?>
            <div class="slider_name"><h2><?php echo $slider_name; ?></h2></div>
            <?php } if( !empty($slider_description)) { ?>
            <div class="slider_description"><?php echo $slider_description; ?></div>
            <?php } ?>
        </div>

        <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
        <a class="next" onclick="plusSlides(1)">&#10095;</a>
        <?php 
                }
            } 
        ?>
    </div>
    <script src="<?php echo SLIDESHOW_PLUGIN_DRI_URL; ?>front/inc/js/slideshow-script.js"></script>
<?php
    return ob_get_clean();
}
?>