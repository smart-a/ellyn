$("[data-toggle='tooltip']").tooltip();
$("input[type='number']").inputSpinner();

$('.toggle-size').on('click',function(){
    var sel = $('#item_size');
    sel.empty();
    if(sel.hasClass('size-alpha')){
        sel.removeClass('size-alpha').addClass('size-num');
        for(var i=30;i<=45;i++){
            sel.append('<option>'+i+'</option>');
        }
    }
    else{
        sel.removeClass('size-num').addClass('size-alpha');
        var opt = ['S','M','L','XL','XXL','XXXL'];
        $.each(opt,function(key,val){
            sel.append('<option>'+val+'</option>');
        });
    }
    sel.multiselect('rebuild');
});

$(".add-item").on("click",function(){
    remove_dummy_images();
    var form_data = new FormData();
    form_data.append("action","item_id");
    
    $.ajax({
        type: "POST",
        url: "items_backend.php",
        data: form_data,
        contentType: false,
        processData: false,
        success: function(e){
            //alert(e);
            clear_modal();
            $("#add-item").attr("item-id",e);
            $("#item_id").val(e);
            fetch_column('item_brand','');
            fetch_column('item_category','');
            $(".modal-caption").text("Add Item");
            $("#action").val("add_item");
            $("#add-item").modal("show");
        }
    });

});

function remove_dummy_images(){
    var form_data = new FormData();
    form_data.append("action","item_img");
    $.ajax({
        type: "POST",
        url: "file_upload.php",
        data: form_data,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(e){
           if(e!=1)
                alert("Error removing image dummy");
        }
    });
}

$('#item_category').on('change',function(){
    var cat_id = $(this).val();
    var form_data = new FormData();
    form_data.append("action","fetch_SubcatByCat");
    form_data.append("cat_id",cat_id);
    $("#item_subcat").html('<option selected disabled value="">Please select option</option>');
    $.ajax({
        type: "POST",
        url: "items_backend.php",
        data: form_data,
        contentType: false,
        processData: false,
        success: function(e){
            //alert(e);
            $("#item_subcat").html(e);
        }
    });
});

$(".close-item").on("click",function(){
    var item_id = $("#add-item").attr("item-id");
    var form_data = new FormData();
    form_data.append("action","close_item");
    form_data.append("item_id",item_id);

    if(confirm("Close Item?")==true)
        $.ajax({
            type: "POST",
            url: "items_backend.php",
            data: form_data,
            contentType: false,
            processData: false,
            success: function(e){
                //alert(e);
                if(e==0)
                    delete_image(item_id);
                $("#add-item").modal("hide");
            }
        });
});

$("#item_amount").on("focusin",function(){
    var txt = $(this).val().replace(",","");
    $(this).val(txt);
});

$("#item_img").on("change",function() { 
    var path = $(this).val();
    var filename = path.split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(filename);
 
});

$(".add-item-img").on("click",function(){
    var item_id = $("#add-item").attr("item-id");
    var files = $("#item_img")[0].files[0];
    var form_data = new FormData();
    form_data.append("item_img",files);
    form_data.append("item_id",item_id);
    form_data.append("action","image_upload");
    
    if(files != null)
        $.ajax({
            type: "POST",
            url: "file_upload.php",
            data: form_data,
            contentType: false,
            processData: false,
            success: function(e){
                //alert(e);
                if(e == "error_exceed")
                    alert("You have reached the maximum image upload !");
                else if(e == "error_upload")
                    alert("Unable to upload Image!");
                else if (e == "error_ext") 
                    alert("Invalid Image file!");
                else{
                    var html = "<div class='list-group-item preview-container' style='border:1px solid rgba(0, 0, 0, 0.125);'>"+
                        "<a class='close remove-item' data-toggle='tooltip' title='Remove' image-name='"+e.split(".")[0]+"'>"+
                        "<i class='fa fa-trash' style='font-size:1rem;'></i></a>" + 
                        "<img src='../../image/"+e+"' class='preview-img' /></div>"; // 
                    $(html).appendTo(".img-preview");
                    $("[data-toggle='tooltip']").tooltip();
                } 

                $("#item_img")[0].files[0] = null;
                $("#item_img").val(null);
                $("#item_img").siblings(".custom-file-label").html("Choose file");
                
            }
        });
    else
        alert("Please select image");
   
});

$(document).on('click','.remove-item',function(){
    var image_name = $(this).attr("image-name");
    var form_data = new FormData();
    form_data.append("image_name",image_name);
    form_data.append("action","delete_img");
    var div_tag = $(this).parent("div");

    if(confirm("Delete Item image?")==true)
        $.ajax({
            type: "POST",
            url: "file_upload.php",
            data: form_data,
            contentType: false,
            processData: false,
            success: function(e){
                //alert(e);
                if(e == "1"){
                    div_tag.fadeOut(1000);
                    div_tag.remove();
                    var n = image_name.split("_")[0];
                    var c = 1;
                    $(".img-preview").children('div').each(function (){
                        var img = n+"_"+c;
                        $(this).children(".remove-item").attr("image-name",img);
                        c++;
                    });
                }  
                else
                    alert("Unable to remove image!");
            }
        });
});

$(document).on("click",".item-edit",function(){
    var item_id = $(this).attr("item-id");
    var form_data = new FormData();
    form_data.append("item_id",item_id);
    form_data.append("action","item_fetch");
    $.ajax({
        type: "POST",
        url: "items_backend.php",
        data: form_data,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(JSONdata){
            //alert(JSONdata['item_brand']);
            clear_modal();
            var brand = (JSONdata['item_brand']>0)?JSONdata['item_brand']:'';
            fetch_column('item_category',JSONdata['item_category']);
            fetch_column('item_subcat',JSONdata['item_subcat']);
            fetch_column('item_brand',brand);
            $("#add-item").attr("item-id",item_id)
            $("#item_id").val(item_id);
            $("#item_name").val(JSONdata['item_name']);
            $("#item_qty").val(JSONdata['item_qty']);
            $("#item_amount").val(JSONdata['item_amount']);
    
            if(JSONdata['item_size']!=''){
                var size = JSONdata['item_size'].split(",");
                $('#item_size').html('');
                if(!isNaN(size[0])){
                    $('#item_size').removeClass('size-alpha').addClass('size-num');
                    for(var i=30;i<=45;i++){
                        $('#item_size').append('<option>'+i+'</option>');
                    }
                }
                else{
                    $('#item_size').removeClass('size-num').addClass('size-alpha');
                    var opt = ['S','M','L','XL','XXL','XXXL'];
                    $.each(opt,function(key,val){
                        $('#item_size').append('<option>'+val+'</option>');
                    });
                }
                $('#item_size').multiselect('rebuild');
                $('#item_size').multiselect('select', size);
            }
            
            if(JSONdata['item_color']!=''){
                var color = JSONdata['item_color'].split(",");
                $('#item_color').multiselect('select', color);
            }

            $('.head-row').children(".new_item").show();
            load_image(item_id);
            $(".modal-caption").text("Edit Item");
            $("#action").val("update_item");
            $("#add-item").modal("show");
        }
    });
});

function fetch_column(column, value){
    var form_data = new FormData();
    form_data.append("action","fetch_column");
    form_data.append("column",column);
    $.ajax({
        type: "POST",
        url: "items_backend.php",
        data: form_data,
        contentType: false,
        processData: false,
        success: function(e){
            //alert(e);
            $("#"+column).html(e);
            $("#"+column).val(value);
        }
    });
}

$(document).on('click','.item-delete',function(){
    var item_id = $(this).attr("item-id");
    var form_data = new FormData();
    form_data.append("item_id",item_id);
    form_data.append("action","delete_item");
    var tr_tag = $("."+item_id);
    if(confirm("Delete Item?")==true)
        $.ajax({
            type: "POST",
            url: "items_backend.php",
            data: form_data,
            contentType: false,
            processData: false,
            success: function(e){
                //alert(e);
                if(e=="1"){
                    tr_tag.fadeOut(1500);
                    delete_image(item_id)
                    setTimeout(function(){
                        tr_tag.remove();
                        var c=1;
                        $(".item-table").children('tbody').children("tr").each(function(){
                            $(this).children("td:first-child").html(c);
                            c++;
                        });
                    },1500); 
                }
                else
                    alert("Error Occur, Unable to delete Item!");
            }
        });
});

function load_image(item_id){
    var check_data = new FormData();
    check_data.append("action","check_img");
    check_data.append("img_name",item_id);
    
    $.ajax({
        type: "POST",
        url: "file_upload.php",
        data: check_data,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(e){
            //alert(e);
            $.each(e, function(key, val) {
                var path = "../../image/"+val+".png";
                var html = "<div class='list-group-item preview-container' style='border:1px solid rgba(0, 0, 0, 0.125);'>"+
                    "<a class='close remove-item' data-toggle='tooltip' title='Remove' image-name='"+val+"'>"+
                    "<i class='fa fa-trash' style='font-size:1rem;'></i></a>" + 
                    "<img src='"+path+"' class='preview-img' /></div>";
                $(html).appendTo(".img-preview"); 
            }); 
            $("[data-toggle='tooltip']").tooltip();  
        }
    });
}

function delete_image(item_id){
    var form_data = new FormData();
    form_data.append("image_name",item_id);
    form_data.append("action","remove_all_image");
    $.ajax({
        type: "POST",
        url: "file_upload.php",
        data: form_data,
        contentType: false,
        processData: false,
        success: function(e){
            if(e != 1)
                alert("Error Occur, Unable to delete Item Image!");
        }
    });
}

$("#item_amount").on("focusin",function(){
    var txt = $(this).val().replace(",","");
    $(this).val(txt);
});

$(document).on("click",".sort",function(){
    $(".sort").css("color","white");
    var data = $(this).attr("data-sort");
    var sort_action = "";
    
    var icon = "alpha";
    if(data=="item_qty")
        icon = "numeric";
    else if(data=="item_amount")
        icon = "amount";

    if($(this).hasClass("ASC")){
        sort_action = "ASC";
        $(this).removeClass("ASC").addClass("DESC");
        $(this).empty().append("<i class='fa fa-sort-"+icon+"-up'></i>");
    }
    else{
        sort_action = "DESC";
        $(this).removeClass("DESC").addClass("ASC");
        $(this).empty().append("<i class='fa fa-sort-"+icon+"-down'></i>");
    }
    $(this).css("color","blue");
    var order = data+" "+sort_action+",";
    var search = $("input#search").val();

    var page = 1;
    $('.item-limit').children('li').each(function(){
        if($(this).hasClass('active'))
            page = $(this).children('a').text();
    })
    set_limit(search,order,"search",page);

});
var timer;
$("input#search").on("keyup",function(e){
    clearTimeout(timer);
    $(".sort").css("color","white");
    var search = $(this).val();
    var order = "";
    var page = 1;
    if(search != ''){
        $('.item-limit').children('li').each(function(){
            if($(this).hasClass('active'))
                page = $(this).children('a').attr('limit');
        });
    }
    timer = setTimeout(()=>{set_limit(search,order,"search",page)}, 500);
});

$("#item_row").on("change",function(e){
    $(".sort").css("color","white");
    var search = $('input#search').val();
    var order = "";
    var page = 1;
    set_limit(search,order,"search",page);
});

$(document).on('click','.page-item-opt',function(){
    var page = parseInt($(this).children('a').attr('limit'));
    var search = $('input#search').val();
    var order = "";
    set_limit(search,order,"search",page);
});

function set_limit(search, order, action, page){ //search, order, limit
    var form_data = new FormData();
    form_data.append("action","items_num");
    form_data.append("search",search);
    var page_row = parseInt($("#item_row").val());
    var limit_page = 0;
    var pg = parseInt(page);
    if(page>0) page = (page - 1)*page_row;
    var limit = ' LIMIT '+page+','+page_row;

    $.ajax({
        type: "POST",
        url: "items_backend.php",
        data: form_data,
        contentType: false,
        processData: false,
        success: function(e){
            var total_row = parseInt(e);
            //alert("Total: "+e+" ROW: "+page_row);
            
            limit_page = Math.ceil(total_row / page_row);
            var prev = (pg>1)?pg-1:1;
            var html = '<li class="page-item page-item-opt opt-prev" total-row="'+limit_page+'">'+
                        '<a class="page-link" limit="'+prev+'"><i class="fa fa-angle-left"></i></a>'+
                    '</li>';
            
            for(var i=1; i<=limit_page; i++){
                var active = "";
                if(pg==''){
                    if(i==1){
                        active = "active"; //"item-selected";
                        var next = (i<limit_page)?i+1:i;
                     }
                }
                else{
                    if(i==pg){
                        active = "active"; //"item-selected";
                        var next = (i<limit_page)?i+1:i;
                     }
                }
                
                html += '<li class="page-item page-item-opt '+active+'" total-row="'+limit_page+'">'+
                    '<a class="page-link " limit="'+i+'">'+i+'</a>'+
                '</li>';
            }
            html += '<li class="page-item page-item-opt opt-next" total-row="'+limit_page+'">'+
                '<a class="page-link" limit="'+next+'"><i class="fa fa-angle-right"></i></a>'+
            '</li>';

            $(".item-limit").html(html);
            $('.page_num').html("Page: " + pg+" of " + limit_page);
        }
    });
    
    search_item(search, order, action, limit);
}

function search_item(val, order, action, limit){
    var form_data = new FormData();
    form_data.append("search",val);
    form_data.append("order",order);
    form_data.append("limit",limit);
    form_data.append("action",action);
    $.ajax({
        type: "POST",
        url: "items_backend.php",
        data: form_data,
        contentType: false,
        processData: false,
        success: function(JSONdata){
            //alert(JSONdata);
            $(".item-table tbody").html(JSONdata);
        }
    });
}

$(".add-category").on("click",function(){
    $(".modal-caption").text("Add Category");
    $("#action").val("add_category");
    $("#add-category").modal("show");
});

$(document).on("click",".category-edit",function(){
    var cat_id = $(this).attr("cat-id");
    var form_data = new FormData();
    form_data.append("cat_id",cat_id);
    form_data.append("action","category_fetch");
    $.ajax({
        type: "POST",
        url: "classify.php",
        data: form_data,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(JSONdata){
            //alert(JSONdata);
            clear_modal();
            $("#add-category").attr("cat-id",cat_id)
            $("#cat_id").val(cat_id);
            $("#item_category").val(JSONdata['item_category']);
            $(".modal-caption").text("Edit Category");
            $("#action").val("update_category");
            $("#add-category").modal("show");
        }
    });
});

$(document).on('click','.category-delete',function(){
    var cat_id = $(this).attr("cat-id");
    var form_data = new FormData();
    form_data.append("cat_id",cat_id);
    form_data.append("action","delete_category");
    var tr_tag = $("."+cat_id);
    if(confirm("Delete Category?")==true)
        $.ajax({
            type: "POST",
            url: "classify.php",
            data: form_data,
            contentType: false,
            processData: false,
            success: function(e){
                //alert(e);
                if(e=="1"){
                    tr_tag.fadeOut(1500);
                    setTimeout(function(){
                        tr_tag.remove();
                        var c=1;
                        $(".category-table").children('tbody').children("tr").each(function(){
                            $(this).children("td:first-child").html(c);
                            c++;
                        });
                    },1500); 
                }
                else
                    alert("Error Occur, Unable to delete Item!");
            }
        });
});

$(".add-subcat").on("click",function(){
    $(".modal-caption").text("Add Sub-Category");
    $("#action").val("add_subcat");
    fetch_column('item_category','');
    $("#add-subcat").modal("show");
});

$(document).on("click",".subcat-edit",function(){
    var subcat_id = $(this).attr("subcat-id");
    var form_data = new FormData();
    form_data.append("subcat_id",subcat_id);
    form_data.append("action","subcat_fetch");
    $.ajax({
        type: "POST",
        url: "classify.php",
        data: form_data,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(JSONdata){
            //alert(JSONdata);
            clear_modal();
            $("#add-subcat").attr("subcat-id",subcat_id)
            fetch_column('item_category',JSONdata['cat_id']);
            $("#subcat_id").val(subcat_id);
            $("#item_subcat").val(JSONdata['item_subcat']);
            $(".modal-caption").text("Edit Sub-Category");
            $("#action").val("update_subcat");
            $("#add-subcat").modal("show");
        }
    });
});

$(document).on('click','.subcat-delete',function(){
    var subcat_id = $(this).attr("subcat-id");
    var form_data = new FormData();
    form_data.append("subcat_id",subcat_id);
    form_data.append("action","delete_subcat");
    var tr_tag = $("."+subcat_id);
    if(confirm("Delete Sub-Category?")==true)
        $.ajax({
            type: "POST",
            url: "classify.php",
            data: form_data,
            contentType: false,
            processData: false,
            success: function(e){
                //alert(e);
                if(e=="1"){
                    tr_tag.fadeOut(1500);
                    setTimeout(function(){
                        tr_tag.remove();
                        var c=1;
                        $(".subcat-table").children('tbody').children("tr").each(function(){
                            $(this).children("td:first-child").html(c);
                            c++;
                        });
                    },1500); 
                }
                else
                    alert("Error Occur, Unable to delete Item!");
            }
        });
});


$(".add-pickup").on("click",function(){
    $(".modal-caption").text("Add Pickup Station");
    $("#action").val("add_pickup");
    //fetch_column('item_category','');
    $("#add-pickup").modal("show");
});

$(document).on("click",".pickup-edit",function(){
    var pickup_id = $(this).attr("pickup-id");
    var form_data = new FormData();
    form_data.append("pickup_id",pickup_id);
    form_data.append("action","pickup_fetch");
    $.ajax({
        type: "POST",
        url: "classify.php",
        data: form_data,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(JSONdata){
            //alert(JSONdata);
            clear_modal();
            $("#add-pickup").attr("pickup-id",pickup_id)
            //fetch_column('item_category',JSONdata['cat_id']);
            $("#pickup_id").val(pickup_id);
            $("#address").val(JSONdata['address']);
            $("#fee").val(JSONdata['fee']); //.toLocalString()
            $(".modal-caption").text("Edit Pickup Station");
            $("#action").val("update_pickup");
            $("#add-pickup").modal("show");
        }
    });
});

$(document).on('click','.pickup-delete',function(){
    var pickup_id = $(this).attr("pickup-id");
    var form_data = new FormData();
    form_data.append("pickup_id",pickup_id);
    form_data.append("action","delete_pickup");
    var tr_tag = $("."+pickup_id);
    if(confirm("Delete Pickup Station?")==true)
        $.ajax({
            type: "POST",
            url: "classify.php",
            data: form_data,
            contentType: false,
            processData: false,
            success: function(e){
                //alert(e);
                if(e=="1"){
                    tr_tag.fadeOut(1500);
                    setTimeout(function(){
                        tr_tag.remove();
                        var c=1;
                        $(".pickup-table").children('tbody').children("tr").each(function(){
                            $(this).children("td:first-child").html(c);
                            c++;
                        });
                    },1500); 
                }
                else
                    alert("Error Occur, Unable to delete pickup station!");
            }
        });
});

$(".add-brand").on("click",function(){
    $(".modal-caption").text("Add Brand");
    $("#action").val("add_brand");
    $("#add-brand").modal("show");
});

$(document).on("click",".brand-edit",function(){
    var brand_id = $(this).attr("brand-id");
    var form_data = new FormData();
    form_data.append("brand_id",brand_id);
    form_data.append("action","brand_fetch");
    $.ajax({
        type: "POST",
        url: "classify.php",
        data: form_data,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(JSONdata){
            //alert(JSONdata);
            clear_modal();
            $("#add-brand").attr("brand-id",brand_id)
            $("#brand_id").val(brand_id);
            $("#item_brand").val(JSONdata['item_brand']);
            $(".modal-caption").text("Edit Brand");
            $("#action").val("update_brand");
            $("#add-brand").modal("show");
        }
    });
});

$(document).on('click','.brand-delete',function(){
    var brand_id = $(this).attr("brand-id");
    var form_data = new FormData();
    form_data.append("brand_id",brand_id);
    form_data.append("action","delete_brand");
    var tr_tag = $("."+brand_id);
    if(confirm("Delete Brand?")==true)
        $.ajax({
            type: "POST",
            url: "classify.php",
            data: form_data,
            contentType: false,
            processData: false,
            success: function(e){
                alert(e);
                if(e=="1" || e==1){
                    tr_tag.fadeOut(1500);
                    setTimeout(function(){
                        tr_tag.remove();
                        var c=1;
                        $(".brand-table").children('tbody').children("tr").each(function(){
                            $(this).children("td:first-child").html(c);
                            c++;
                        });
                    },1500); 
                }
                else
                    alert("Error Occur, Unable to delete Item!");
            }
        });
});


$("#save").on("click", function(){
    if(check_fields()){
        var page_row = parseInt($("#item_row").val());
        $('.item-limit').children('li').each(function(){
            if($(this).hasClass('active')){
                var page = $(this).children('a').attr('limit');
                var pg = parseInt(page);
                if(page>0) page = (page - 1)*page_row;
                var limit = page+','+page_row;
                $('#page_row').val(page_row);
                $('#limit').val(limit);
            }
        });
        $("#item_form").submit();
    }else{ return; } 
});

function check_fields(){
    var status = true;
    if($("#item_id").val() == "" || $("#item_name").val() == "" ||
        $("#item_qty").val() < 1 || $("#item_amount").val() < 1 ||
        $("#item_category").val() == "" || $("#item_brand").val() == "" ||
        $("#item_subcat").val() == "" || $("#state").val() == "" || $("#state").val() == null ||
        $("#city").val() == "" || $("#city").val() == null || $("#address").val() == "" ||
        parseFloat($("#fee").val()) < 0 ){
            alert("All fields are required");
            status = false;
    }

    if($('#item_size').val() == "" && $('#item_color').val() == "" && status==true ){
        if(!confirm("Item color and size not set!, Continue anyway?"))
            status = false;
    }
    else if($('#item_size').val() == "" && status==true ){
        if(!confirm("Item size not set!, Continue anyway?"))
            status = false;
    }
    else if($('#item_color').val() == "" && status==true ){
        if(!confirm("Item color not set!, Continue anyway?"))
            status = false;
    }

    if($(".img-preview").html() == "" && status==true)
        if(!confirm("Item image is empty!, Continue anyway?"))
            status = false;
    return status;
}

function clear_modal(){
    $("#add-item").attr("item-id","");
    $("#item_id").val("");
    $("#action").val("");
    $("#item_name").val("");
    $("#item_qty").val("1");
    $("#item_amount").val("");
    $("#item_category").val("");
    $("#item_subcat").val("");
    $("#item_brand").val("");
    $('#item_size').multiselect('deselectAll', false);
    $('#item_color').multiselect('deselectAll', false);
    $('.head-row').children(".new_item").hide();
    $('#new_item').prop("checked",false);
    $(".img-preview").empty();
    $("#state").val("");
    $("#city").val("");
    $("#address").val("");
    $("#fee").val("0");
}

state = ["ABUJA FCT","ABIA","ADAMAWA","AKWA IBOM","ANAMBRA","BAUCHI","BAYELSA","BENUE","BORNO","CROSS RIVER","DELTA",
                    "EBONYI","EDO","EKITI","ENUGU","GOMBE","IMO","JIGAWA","KADUNA","KANO","KATSINA","KEBBI","KOGI","KWARA","LAGOS",
                    "NASSARAWA","NIGER","OGUN","ONDO","OSUN","OYO","PLATEAU","RIVERS","SOKOTO","TARABA","YOBE","ZAMFARA"];

function loadBox(id, data, option){
    var opt = ['<option selected disabled value="">Select option</option>'];
    $(id).html(function(){
        $.each(data,function(key, val){
            var selected = "";
            if(option == val)
                selected = "selected";
            opt.push('<option '+selected+'>' + val + '</option>');
        });
        return opt.join('');
    });
}

loadBox("#state",state,"");;