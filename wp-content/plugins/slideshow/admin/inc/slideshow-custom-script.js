$ = jQuery;
var ajax_url = '../wp-admin/admin-ajax.php';

$(document).ready(function ($){
    auto_hide_alerts();

    // Apply DataTable to 
    $('#slide_table').DataTable({
        "order": [[ 1, "asc" ]]
    });

    // Remove sorting Class
    $(".remove_sorting").removeClass("sorting, sorting_asc");

    // Enable Slide
    $("#slide_table tbody tr td").each(function(){
        $(this).children().find('.mydemo').change(function() {
			if ($(this).is(":checked")) {
				$(".status_updated.disable").hide();
				$(".status_updated.enable").show();
				$(".status_updated.enable").fadeOut(3000);
			}else{
				$(".status_updated.disable").show();
				$(".status_updated.disable").fadeOut(3000);
				$(".status_updated.enable").hide();
			}	
            var checked = $(this).prev().prev().val();
            var device_id = $(this).prev().val();
            $.ajax({
                method: "POST",
                type: "jsonp",
                url: ajax_url,
                data: {'id':device_id ,'status': checked, action: 'slideshow_status'},
                success: function(data) {
					console.log("sucess");
                }
            });
        });
    });

    // Delete Slide
    $("input[name='all_slide_del']").on("click",function () {
        $("td.del-slide-check input[type='checkbox']").attr('checked', this.checked);
    });
    $(".del-slide-btn").click(function(){
        var id = [];
        $('.del-slide-check :checkbox:checked').each(function(i){
            id[i] = $(this).val();
        });
		if(id.length === 0) {
            alert("Please Select atleast one checkbox");
        } else {
            $.ajax({
                method: "POST",
                type: "jsonp",
                url: ajax_url,
                data: {'id':id, action: 'delete_multiple_slide'},
                success: function(data) {
                    $('.wrap').prepend('<div class="notice notice-success is-dismissible"><p> Slide Deleted successfully </p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>');
                    $("#slide_table").load(' #slide_table > *');
                    auto_hide_alerts();
                }
            });
        }
    });
});

// Hide Pop-up function
function hidePopup(){
    $(".popup-wrapper, .popup-overlay").hide();
    $('body').removeClass('modal-open');
}

// Get image URL
function image_url(id){
    if(id){
        var str = '&action=slideshow_image_url&id='+id;
        $.ajax({
            type: "POST",
            dataType: "html",
            url: ajax_url,
            data: str,
            success: function(data){
                if(data){
                    $('#image-preview').attr('src',data);
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                console.log(jqXHR + " :: " + textStatus + " :: " + errorThrown);
            }
        });
    }
}

// Delete Function
function slideshow_remove(tbl,id, model_id = ''){
	
	if ( tbl == 'slideshow'){
		var message = "this slide remove?";
	}
	var result = confirm("Do you want to delete " + message );
	if(result){
        var redirect = '';
		if(tbl == 'slideshow') {
			redirectTo = 'admin.php?page=slideshow_option&deleted';
	    }

        var str = '&action=slideshow_remove&tbl='+tbl+'&id='+id;
        $.ajax({
            type: "POST",
            dataType: "html",
            url: ajax_url,
            data: str,
            success: function(data){
               if(data == 1){
                    window.location.replace(redirectTo);
                } else {
                    alert('Issue, Please Try After Some Time');
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                console.log(jqXHR + " :: " + textStatus + " :: " + errorThrown);
            }
        });
	}
}

function auto_hide_alerts() {
    window.setTimeout(function () { 
        $(".notice-success").remove(); 
    }, 3000);
}

$(document).on('keypress', 'input[type="search"]',function(event) {
    var key = event.keyCode;
    if (key === 32) {
        event.preventDefault();
    }
});

$(document).ready(function() {  
    var fixHelper = function(e, ui) {
        ui.children().each(function() {
            $(this).width($(this).width());
        });
        return ui;
    };
  $('table#slide_table tbody').sortable({   
     delay: 150,
     stop: function() {
            var selectedData = new Array();
            $('tbody > tr').each(function() {
                selectedData.push({
                    id: $(this).attr('id'),                        
                });
            });
      var table_name =  $(this).parent().attr('id');      
           jQuery.ajax({ 
                url:ajax_url,
                type: 'post',  
                dataType: 'json',                 
                data: {
                    position: selectedData, 
                    action:'change_order' ,
                    table_name: table_name,                  
                },
                success : function(response) {
                    if ( response.status == true ) {
                        jGrowlAlert("Re-Arrange successfully", 'success');
                    } else {
                        // jGrowlAlert("Please try again", 'error');
                    }
                }
             });
       }
    });
});

