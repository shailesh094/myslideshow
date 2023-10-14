<?php
    global $wpdb;
    wp_enqueue_media();
    $slideshow_tbl = $wpdb->prefix."slideshow";

    $csql = "SELECT * FROM $slideshow_tbl ORDER BY `slider_position` ASC;";
    $cresult = $wpdb->get_results($csql);
?>
<div class="slide_wraper">
    <h1> SlideShow </h1><hr/>
    <button class="button button-primary"  onclick="open_popup()"> Add slide </button>
    <?php if(count($cresult) != 0) { ?>
    <button class="button button-primary button-delete del-slide-btn"> Delete slide </button>
    <?php } ?>
    <div class="wrap">
    <?php
        if(isset($_POST['btn_type_submit'])) {
            $error       = false;
            $error_arr   = array();
            $action      = $_POST['slide_action'];
            $slider_id   = $_POST['slider_id'];
            $slide_title        = $_POST['slide_title'];
            $slide_image      = $_POST['slide_image'];
            $description = $_POST['slide_desc'];

            if(!isset($slide_title) || empty($slide_title)) {
                $error = true;
                array_push($error_arr, 'Name is required');
            } else if(ctype_space($slide_title) == 1) {
                $error = true;
                array_push($error_arr, 'White space is not allowed.');
            } else if(is_numeric($slide_title)) {
                $error = true;
                array_push($error_arr, 'Name must not be numeric only.');
            } else if(strlen($slide_title) < 3 || strlen($slide_title) > 200) {
                $error = true;
                array_push($error_arr, 'Name must be between the length of 3 to 200.');
            } else if($slide_title != strip_tags($slide_title)) {
                $error = true;
                array_push($error_arr, 'Name must not contains HTML');
            }

            if($action == "slide_add") {
                $slide_get_row = $wpdb->get_row('SELECT * FROM '.$slideshow_tbl.' WHERE name = "'.$slide_title.'"');

                if(isset($slide_get_row) && !empty($slide_get_row)) {
                    $error = true;
                    array_push($error_arr, 'Name is exist. Please choose different name.');
                }
            } else if( $action == "slide_edit" ) {
                $slide_get_row = $wpdb->get_row('SELECT * FROM '.$slideshow_tbl.' WHERE name = "'.$slide_title.'" AND id NOT IN('.$slider_id.')');
                if(isset($slide_get_row) && !empty($slide_get_row)) {
                    $error = true;
                    array_push($error_arr, 'Name is exist. Please choose different name.');
                }
            }

            if(!isset($slide_image) || empty($slide_image)) {
                $error = true;
                array_push($error_arr, 'Image is required');
            }
            if(!isset($description) || strlen($description) < 3 || strlen($description) > 500) {
				$error = true;
                array_push($error_arr, 'Description must be between the length of 3 to 500.');
			}

            if($error == false) {
                if ( !empty($action) && $action == "slide_add" ) {
                    $tiresult = $wpdb->query("INSERT INTO $slideshow_tbl (name,image,description,status) VALUES('".$slide_title."','".$slide_image."','".$description."', '1')");
                    if( $tiresult == 1 ){
                        $url = admin_url('admin.php?page=slideshow_option&added');
                        echo '<script>window.location="'.$url.'";</script>';
                    }
                } else if( $action == "slide_edit" ){
                    $turesult = $wpdb->query("UPDATE $slideshow_tbl SET name = '".$slide_title."', image = '".$slide_image."', description = '".$description."' WHERE id = $slider_id");
                    if( $turesult == 1 ){
                        $url = admin_url('admin.php?page=slideshow_option&updated');
                        echo '<script>window.location="'.$url.'";</script>';
                    }
                }
            } else {
                show_errors($error_arr);
            }
        } else {
            if(isset($_GET['deleted'])) {
                echo '<div class="notice notice-success is-dismissible"><p> Slide deleted successfully </p></div>';
            } else if(isset($_GET['added'])) {
                echo '<div class="notice notice-success is-dismissible"><p> Slide added successfully </p></div>';
            } else if(isset($_GET['updated'])) {
                echo '<div class="notice notice-success is-dismissible"><p> Slide updated successfully </p></div>';
            }
        }
    ?>
        <!-- Add Slide Pop-up -->
        <div id="slide-popup" class="popup-overlay">
            <div class="slideshow_form_wraper popup-wrapper">
                <div class="popup-header button-primary">
                    <h3 class="popup-title">Add  Slide</h3>
                    <a class="close" onclick="hidePopup()">&times;</a>
                </div>
                <form name="slideshow_form" id="slideshow_form" action="#" method="post" enctype="multipart/form-data ">
                    <input type="hidden" name="slider_id" id="slider_id" value="">
                    <div class="popup-body">
                        <label>Name</label>
                        <input type="text" name="slide_title" id="slide_title" placeholder="Slide Title" required>

                        <label>Select Image</label>
                        <div class="image-section">
                            <input id="upload_image_button" type="button" class="button" value="<?php _e( 'Upload image' ); ?>" required/>
                            <input type="hidden" name="slide_image" id="image_attachment_id" value="">
                            <div class="image-preview-wrapper"><img id="image-preview" src=""></div>
                        </div>
                        <label>Description :</label>
                        <textarea name="slide_desc" required id="slide_desc" style="height: 100px;"></textarea>
                    </div>
                    <div class="popup-footer">
                        <input type="submit" name="btn_type_submit" id="btn_type_submit" class="button button-primary" value="Add Slide">
                        <input type="hidden" name="slide_action" id="slide_action" value="slide_add">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Slide Table -->
    <?php
        if($cresult) {
    ?>
    <div class="status_updated enable notice">
        <p>Enable Successfully..!</p>
    </div>
	
	<div class="status_updated disable notice" style="display:none;">
        <p>Disable Successfully..!</p>
    </div>
    <table id="slide_table" class="slide_table">
        <thead>
            <tr>
                <th class="all_slide_del"><input type="checkbox" name="all_slide_del"></th>
                <th> # </th>
                <th> Image </th>
                <th> Name </th>
                <th> Status </th>
                <th> Action </th>
            </tr>
        </thead>

        <tbody>
        <?php
            $i = 1;
            foreach ($cresult as $cvalue) {
                $slider_id = $cvalue->id;

                if(isset($cvalue->image) && !empty($cvalue->image)) {
                    $imageExist = wp_get_attachment_metadata($cvalue->image);

                    if(isset($imageExist) && !empty($imageExist)) {
                        $url = wp_get_attachment_url($cvalue->image);

                        if(!empty($url)) {
                            $url = $url;
                        } else {
                            $url = SLIDESHOW_PLUGIN_PLACEHOLDER_IMAGE_URL;
                        }
                    } else {
                        $url = SLIDESHOW_PLUGIN_PLACEHOLDER_IMAGE_URL;
                    }
                } else {
                    $url = SLIDESHOW_PLUGIN_PLACEHOLDER_IMAGE_URL;
                }
        ?>
            <tr id="<?php echo $slider_id; ?>">
                <td class="del-slide-check"><input type="checkbox" class="del-check-slide" name="device_del[]" value="<?php echo $slider_id; ?>"></td>
                <td><?php echo $i; ?></td>
                <td>
                    <img id="image-preview" src="<?php echo $url; ?>" class="slide_image">
                    <input type="hidden" name="image_attachment_id" id="image_attachment_id" value="<?php echo $cvalue->image; ?>">
                </td>
                <td><?php echo $cvalue->name; ?></td>
                <td class="slide_status"><?php $slide_status = $cvalue->status; ?>
                <?php if($slide_status == 1){ ?>
                    <label class="switch">
                        <input type="hidden" name="slide-status" value="<?php echo $slide_status; ?>">
                        <input type="hidden" name="slide-id" value="<?php echo $slider_id; ?>">
                        <input type="checkbox" checked class="mydemo">
                        <span class="slider round"></span>
                    </label>
                <?php } else { ?>
                    <label class="switch">
                        <input type="hidden" name="slide-status" value="<?php echo $slide_status; ?>">
                        <input type="hidden" name="device-id" value="<?php echo $slider_id; ?>">
                        <input type="checkbox" class="mydemo">
                        <span class="slider round"></span>
                    </label>
                <?php } ?>
                </td>
                <td>
                    <span class="slideshow_btn_edit" onclick="edit_slide('<?php echo $slider_id; ?>','<?php echo $cvalue->name; ?>','<?php echo $cvalue->image; ?>');">
                        <i class="fas fa-pencil-alt button-primary"></i>
                        <input type="hidden" id="<?php echo $slider_id; ?>-dt-desc" value="<?php echo $cvalue->description; ?>">
                    </span>
                    <span class="slide_btn_remove" onclick="slideshow_remove('slideshow_option',<?php echo $slider_id; ?>);">
                        <i class="fas fa-trash-alt button-primary"></i>
                    </span>
                </td>
            </tr>
        <?php
                $i++;
            }
        ?>
        </tbody>
    </table>
    <?php
        }

        $my_saved_attachment_post_id = get_option( 'media_selector_attachment_id', 0 );
    ?>
    </div>
<script type="text/javascript">
$ = jQuery;

// WordPress Default Media Selector
$(document).ready(function ($){
    var file_frame;
    var wp_media_post_id = wp.media.model.settings.post.id;
    var set_to_post_id = '<?php $my_saved_attachment_post_id; ?>';

    $('#upload_image_button').on('click', function( event ){
        event.preventDefault();
        if ( file_frame ) {
            file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
            file_frame.open();
            return;
        } else {
            wp.media.model.settings.post.id = set_to_post_id;
        }

        file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Select a image to upload',
            button: {
                text: 'Use this image',
            },
            library: {
                type: ['image' ]
            },
            multiple: false
        });

        file_frame.on( 'select', function() {
            attachment = file_frame.state().get('selection').first().toJSON();

            if(attachment.type == 'image') {            
                $('.error-message').remove();
                $('.image-preview-wrapper').css('display', 'block');
                $( '#image-preview' ).attr( 'src', attachment.url );
                $( '#image_attachment_id' ).val( attachment.id );
            } else {
                $('.image-preview-wrapper').css('display', 'none');
                $('.image-preview-wrapper').after('<p class="error-message">Please select image file only</p>');
            }

            wp.media.model.settings.post.id = wp_media_post_id;
        });
        
        file_frame.open();
    });

    $( 'a.add_media' ).on( 'click', function() {
        wp.media.model.settings.post.id = wp_media_post_id;
    });
});
// WordPress Default Media Selector

// Open Popup on button click event
$('.popup-wrapper, .popup-overlay').hide();
function open_popup() {
    $('body').addClass('modal-open');
    $("#slideshow_form")[0].reset();
    $("#image-preview").attr('src','');
    $('.popup-title').html('Add Slide');
    $('#slide_action').val('slide_add');
    $('#btn_type_submit').val('Add Slide');

    $(".popup-wrapper, .popup-overlay").show();
    $(".popup-wrapper, .popup-overlay").css('visibility','visible');
    $(".popup-wrapper, .popup-overlay").css('opacity','1');
}

// Open Edit Popup
function edit_slide(id,name,imageid){
    var desc = $('#' + id + '-dt-desc').val();

    $('.popup-title').html('Edit Slide');
    $('#slide_action').val('slide_edit');
    $('#btn_type_submit').val('Update');

    $('#slider_id').val(id);
    $('#slide_title').val(name);
    image_url(imageid);
    $('#image_attachment_id').val(imageid);
    $('#slide_desc').val(desc);

    $(".popup-wrapper, .popup-overlay").show();
    $(".popup-wrapper, .popup-overlay").css('visibility','visible');
    $(".popup-wrapper, .popup-overlay").css('opacity','1');
}
</script>
</div>