<?php
/* ----- Admin Page & Menu Section ----- */
/**
 * Plugin Menu For Slideshow Plug-in Options
 * @author Shailesh Gajare
 * @return create a new page at admin side
 */
add_action('admin_menu','slideshow_plugin_menu');

function slideshow_plugin_menu() {

    add_menu_page('Slideshow', 'Slideshow', 'manage_options', 'slideshow_option', 'slideshow_function', 'dashicons-images-alt', 20);
}

function slideshow_function() {
    include 'slideshow_option.php';
}


add_action('admin_footer', 'admin_conditional_css');

function admin_conditional_css() {
    if(isset($_GET['page']) && !empty($_GET['page']) && $_GET['page'] == 'add_slide_model') {
?>
<script type="text/javascript">
    $('#toplevel_page_slideshow ul.wp-submenu li:nth-child(4)').addClass('current');
    $('#toplevel_page_slideshow ul.wp-submenu li:nth-child(4) a').addClass('current');
</script>
<?php
    }
}

/**
 * function for get image URL from attachment id
 * @author Shailesh Gajare
 * @param int : attachment id
 * @return string : Image URL
 */
add_action('wp_ajax_slideshow_image_url', 'slideshow_image_url');
add_action('wp_ajax_nopriv_slideshow_image_url', 'slideshow_image_url');

function slideshow_image_url(){
    $id = $_POST['id'];
    if( !empty($id) ){
        echo wp_get_attachment_url($id);
    } else {
        echo 0;
    }
    die();
}

// Update status of slideshow
add_action('wp_ajax_slideshow_status','slideshow_status');
add_action('wp_ajax_nopriv_slideshow_status','slideshow_status');
function slideshow_status(){
    global $wpdb;
    $slideshow_tbl = $wpdb->prefix."slideshow";

    $slider_id = $_POST['id'];
    $slide_status = $_POST['status'];

    if( !empty($slider_id) ) {
        if($slide_status == '0') {
            echo $wpdb->query("UPDATE $slideshow_tbl SET status = '1' WHERE id = $slider_id");
        } else {
            echo $wpdb->query("UPDATE $slideshow_tbl SET status = '0' WHERE id = $slider_id");
        }
    }
    die();
}


/**
 * function for delete data from database.
 * @author Shailesh Gajare
 * @param POST Data :- id(int), table name(string)
 * @return int :- 0 = Error, 1 = Success
 */
add_action('wp_ajax_nopriv_slideshow_remove', 'slideshow_remove');
add_action('wp_ajax_slideshow_remove', 'slideshow_remove');
function slideshow_remove() {
    global $wpdb;
    $slideshow_tbl = $wpdb->prefix."slideshow";
    if( !empty($_POST["id"]) ) {
        foreach($_POST["id"] as $id) {
            $wpdb->query("DELETE FROM $slideshow_tbl WHERE id = '".$id."'");
            die;
        }
    }
    die();
}

// Delete record of slide
add_action('wp_ajax_delete_multiple_slide','delete_multiple_slide');
add_action('wp_ajax_nopriv_delete_multiple_slide','delete_multiple_slide');
function delete_multiple_slide(){
    global $wpdb;
    $slideshow_tbl = $wpdb->prefix."slideshow";
    if( !empty($_POST["id"]) ) {
        foreach($_POST["id"] as $id) {
            $wpdb->query("DELETE FROM $slideshow_tbl WHERE id = '".$id."'");
        }
    }
    die();
}

// Rearrage Data for Slide chnage order
add_action('wp_ajax_change_order','change_order');
add_action('wp_ajax_nopriv_change_order','change_order');
function change_order(){
  global $wpdb;  
    $position = $_POST['position'];
    $table_name = $_POST['table_name'];
    if( $table_name == "slide_table" ){
        $table = $wpdb->prefix."slideshow";
        $position_name = 'slider_position';
    }

    foreach($position as $k=>$v){
      $wpdb->query("UPDATE  $table SET  $position_name = ".$k." WHERE id =".$v['id']);
    }
    echo "<pre>";
    print_r($wpdb->last_query);
    echo "</pre>";
    die();
}

function upload_file_by_url( $image_url ) {
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    $temp_file = download_url( $image_url );

    if( is_wp_error( $temp_file ) ) {
        return false;
    }

    $file = array(
        'name'     => basename( $image_url ),
        'type'     => mime_content_type( $temp_file ),
        'tmp_name' => $temp_file,
        'size'     => filesize( $temp_file ),
    );
    $sideload = wp_handle_sideload(
        $file,
        array(
            'test_form'   => false
        )
    );

    if( ! empty( $sideload[ 'error' ] ) ) {
        return false;
    }

    $attachment_id = wp_insert_attachment(
        array(
            'guid'           => $sideload[ 'url' ],
            'post_mime_type' => $sideload[ 'type' ],
            'post_title'     => basename( $sideload[ 'file' ] ),
            'post_content'   => '',
            'post_status'    => 'inherit',
        ),
        $sideload[ 'file' ]
    );

    if( is_wp_error( $attachment_id ) || ! $attachment_id ) {
        return false;
    }

    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata( $attachment_id, $sideload[ 'file' ] ));
    return $attachment_id;
}
?>