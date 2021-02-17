

$("input#search").on("keyup focus",()=>{ 
    //alert('here');
    filter('','',''); 
    $("#item_category").val('');
    $("#item_brand").val('');
});

var size;
$(window).on('resize',function(){
    size = $(this).width();
    if(size <= 987){
        $('#filter-sidebar').css('display','none');
        $('.menu-opt,.acct-menu').attr('data-target','#navmenu');
    }else{
        $('.menu-opt,.acct-menu').attr('data-target','');
    }
        
});

$('#btn-filter,.cancel-search').on("click", ()=>{
    var cat = $("#item_category").val();
    var range = $('#amt_range').slider('values');
    var amt = range[0]+" AND "+range[1];
    var brand = $("#item_brand").val();
    filter(cat, amt, brand);
    if(size < 987)
        $('#filter-sidebar').css('display','none');
});

$('#btn-c-filter').on("click", ()=>{
    $("#item_category").val('');
    $("#item_brand").val('');
    filter('', '', '');
    if(size < 987)
        $('#filter-sidebar').css('display','none');
});


$('a.cat-item').on('click',function(){  //.children('a')
    $('a.cat-item').removeClass('selected');
    $(this).addClass('selected');

    var cat_id = $(this).attr('id');
    var form_data = new FormData();
    form_data.append("cat_id",cat_id);
    form_data.append("action","fetch-item-cat");
    $.ajax({
        type: "POST",
        url: "index_backend.php",
        data: form_data,
        contentType: false,
        processData: false,
        success: function(e){
            //alert(e);
            //$('#cat-body').html('');
            if(e!=''){
                $('#cat-body').removeClass('alert alert-light mt-5');
                $('#cat-body').html(e);
            }  
            else{
                $('#cat-body').addClass('alert alert-light mt-5');
                $('#cat-body').html('<h6 class="alert-heading" style="text-align:center">'+
                                '<i class="fa fa-frown"></i> '+
                                'No Item under this Categorty!'+
                            '</h6>');
            }
        }
    }); 
    
});

var timer;
function filter(cat, amt, brand){
    clearTimeout(timer);
    var search = $("input#search").val();

    var form_data = new FormData();
    form_data.append("search",search);
    form_data.append("cat",cat);
    form_data.append("amt",amt);
    form_data.append("brand",brand);
    form_data.append("action","fetch-item");
    $.ajax({
        type: "POST",
        url: "index_backend.php",
        data: form_data,
        contentType: false,
        processData: false,
        success: function(e){
            //alert(e);
            if(e!='')
                timer = setTimeout(()=>{ 
                    $('.item_body').html(e);
                    (cat!='')?$('.item_other>span').append(" in "+$("#item_category option:selected").text()):'';
                    (cat!='' && brand!='')?$('.item_other>span').append(" and "+$("#item_brand option:selected").text()):'';
                    (cat=='' && brand!='')?$('.item_other>span').append(" in "+$("#item_brand option:selected").text()):'';
                }, 500);
        }
    });  
}



$(document).on("click",".item-info", function(){
    var item_id = $(this).attr("id");
    var user_id = $.trim($('.user-id').text());
    $("#item-modal").modal('show');
    item_info(user_id,item_id);

    setFirstChild("item-size li");
    setFirstChild("item-color li");

});

$(".preview-img").on("click", function(){
    var item_id = $(this).attr("img-id");
    var user_id = $.trim($('.user-id').text());
    $("#item-modal").modal('show');
    item_info(user_id,item_id);
});

function setFirstChild(selector){
    $("ul."+selector).children("a").removeClass("item-selected");
    $("ul."+selector+":first-child").children("a").addClass("item-selected");
}

function item_info(user_id,item_id){
    var form_data = new FormData();
    form_data.append("item_id",item_id);
    form_data.append("user_id",user_id);
    form_data.append("action","item_fetch");
    load_carousel_image(item_id);
    var server = $('#server_root').val().split('/')[1];
    $.ajax({
        type: "POST",
        url: server+"/admin/items_backend.php",
        data: form_data,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(JSONdata){
            //alert(JSONdata);
            $(".add-cart").attr("item-id",item_id)
            $("#item_name").text(JSONdata['item_name']);
            $("#item_amount").html(JSONdata['item_amount']);
            
            
            var count = 1;
            var active = "";
            var li = "";
            if(JSONdata['item_size']!=''){
                var size = JSONdata['item_size'].split(",");
                li = '<span class="">Size: </span><ul class="pagination pagination-sm item-size d-flex flex-wrap ">';
                $.each(size,function(key,val){
                    active = "";
                    if(count==1)
                        active = "item-selected";
                    li += '<li class="page-item page-item-opt '+active+'" >'+
                                '<a class="page-link" style="font-size: 0.8rem">'+val+'</a>'+
                            '</li>';
                    count++;
                });
                li+= '</ul>';
            }
            $(".size-can").html(li);
            
            li = "";
            count = 1;
            if(JSONdata['item_color']!=''){
                var color = JSONdata['item_color'].split(",");
                li = '<span class="">Color: </span><ul class="pagination pagination-sm item-color d-flex flex-wrap ">';
                $.each(color,function(key,val){
                    active = "";
                    if(count==1)
                        active = "item-selected";
                    var b_color = "border:2px solid "+val+";";
                    if(val.toLowerCase() == "white")
                        b_color = "";
                    li += '<li class="page-item page-item-opt '+active+'">'+
                                '<a class="page-link" style="'+b_color+' font-size: 0.8rem">'+val+'</a>'+
                            '</li>';
                    count++;
                });
                li+= '</ul>';
            }
            $(".color-can").html(li);
            
            $('.item-like').attr("data-like",item_id);
            if(JSONdata['item_like']==1)
                $('.item-like').addClass('item-liked');
            else
                $('.item-like').removeClass('item-liked');
        }
    });
}

$(document).on("click",".item-color li",function(){
    $(".item-color li").removeClass("item-selected");
    $(this).addClass("item-selected");
});

$(document).on("click",".item-size li",function(){
    $(".item-size li").removeClass("item-selected");
    $(this).addClass("item-selected");
});

function load_carousel_image(item_id){
    var check_data = new FormData();
    check_data.append("action","check_img");
    check_data.append("img_name",item_id);
    var html = '<div class="carousel-inner inner-item-img">';
    var server = $('#server_root').val().split('/')[1];
    
    $.ajax({
        type: "POST",
        url: server+"/admin/file_upload.php",
        data: check_data,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(e){
            //alert(e);
            var c = 1;
            $.each(e, function(key, val) {
                var active = "";
                if(c==1)
                    active = "active";
                var path = "image/"+val+".png";
                html += '<div class="carousel-item item-sm '+active+'">'+
                            '<img class="item-img card-image-sm" src="'+path+'"/>'+
                        "</div>";
                c++;
            });  
            html += "</div>";
            if(c>2){
                html += '<a href="#itemSlide" class="carousel-control-prev " data-slide="prev">'+
                            '<span class="fa fa-angle-left" style="color:red;font-size: 2rem"></span>'+
                        '</a>'+
                        '<a href="#itemSlide" class="carousel-control-next" data-slide="next">'+
                            '<span class="fa fa-angle-right" style="color:red;font-size: 2rem"></span>'+
                        '</a>';
            }
            $("#itemSlide").html(html);
        }
    });
}

$(".item-like").on("click",function(){
    var item_like = $(this);
    var like = 1;
    if(item_like.hasClass("item-liked"))
        like = 0;
    //alert(like);
    var user = item_like.attr("data-user");
    var item_id = item_like.attr("data-like");
    var form_data = new FormData();
    form_data.append("user",user);
    form_data.append("item_id",item_id);
    form_data.append("like",like);
    form_data.append("action","item-like");
    $.ajax({
        type: "POST",
        url: "index_backend.php",
        data: form_data,
        contentType: false,
        processData: false,
        success: function(e){
            if(e==1)
                item_like.toggleClass("item-liked");
        }
    });
});

$("button.add-cart").on("click",function(){
    var count = $(".cart").text();
    var n = 0;
    var id = $(this).attr("item-id");
    var user = $.trim($('.user-id').text());
    var size = $('.item-size').children('li.item-selected').children('a').text();
    var color = $('.item-color').children('li.item-selected').children('a').text();
    
    var form_data = new FormData();
    form_data.append("user",user);
    form_data.append("item_id",id);
    form_data.append("size",size);
    form_data.append("color",color);
    form_data.append("action","add-cart");
    $.ajax({
        type: "POST",
        url: "index_backend.php",
        data: form_data,
        contentType: false,
        processData: false,
        success: function(e){
            //alert(e);
            $("#item-modal").modal('hide');
            if(e=="1" || e==1){
                n = parseInt(count) + 1;
                $(".cart").text(n);
            }
        }
    });
});

$(".cart-item .close").on("click", function(){
    var id = $(this).attr("item-id");
    var cart_id = $(this).attr("cart-id");
    $("#remove-cart").attr("item-id",id)
    $("#remove-cart").attr("cart-id",cart_id)
    $("#confirm").modal("show");
});

$("#remove-cart").on("click", function(){
    var id = "#"+$(this).attr("cart-id");
    var cart_id = $(this).attr("cart-id"); //.attr("cart-id");
    var user = $.trim($('.user-id').text());
    alert(cart_id);
    $("#confirm").modal("hide");
    var form_data = new FormData();
    form_data.append("user",user);
    form_data.append("cart_id",cart_id);
    form_data.append("action","remove-cart");
    $.ajax({
        type: "POST",
        url: "index_backend.php",
        data: form_data,
        contentType: false,
        processData: false,
        success: function(e){
           //alert(e);
            var count = $("input.cart").val();
            if(e=="1" || e==1){
                $(id).fadeOut(1500);
                setTimeout(function(){
                    $(id).remove();
                    totalAmount();

                    n = parseInt(count) - 1;
                    $("input.cart").val(n);
                    $(".cart-list").text((n>0)?(n==1)?'('+n+' Item)':'('+n+' Items)':'');
                },1500);
            }  
        }
    });
    
});

totalAmount();
$(".cart-item-opt input").on("change",function() {
    totalAmount();
});

function totalAmount(){
    var sum = 0;
    $("#cartList").children('div').each(function (){
        var amt_str = $(this).children(".item-amount").text();
        var amt = amt_str.replace(",","");
        var $opt = $(this).children(".row").children(".cart-item-opt");
        var num = $opt.children(".item-num:input").val();
        var tm = parseFloat(amt) * parseInt(num);
        sum += tm;
    });
    if(sum==0){
        $('#cart_sum').removeClass('shadow');
        $('#cart_sum').addClass('alert alert-light');
        $('#cart_sum').html('<h4 class="alert-heading" style="text-align:center">'+
                                '<i class="fa fa-frown"></i> '+
                                'Please add cart!'+
                            '</h4>');
    }
    else
        $("#total").text(sum.toLocaleString());
}

$("#checkout").on("click",function() {
    $("#cartList").children('div').each(function (){
        var item_id = $(this).attr("item-id");
        var size = $(this).children("div.size-can").html();//.attr('class'); //
        var color = $(this).children(".item-color").attr('data-color');
        var qty = $(this).children(".item-num").text();
        var amt_str = $(this).children(".item-amount").text();
        var amt = amt_str.replace(",","");
        var $opt = $(this).children(".row").children(".cart-item-opt");
        var num = $opt.children(".item-num:input").val();
        var tm = parseFloat(amt) * parseInt(num);
        alert(size);
        return;
    });

    var form_data = new FormData();
    form_data.append("add",search);
    form_data.append("cat",cat);
    form_data.append("amt",amt);
    form_data.append("brand",brand);
    form_data.append("action","fetch-item");
    $.ajax({
        type: "POST",
        url: "index_backend.php",
        data: form_data,
        contentType: false,
        processData: false,
        success: function(e){
            //alert(e);
            if(e!='')
                timer = setTimeout(()=>{ 
                    $('.item_body').html(e);
                    (cat!='')?$('.item_other>span').append(" in "+$("#item_category option:selected").text()):'';
                    (cat!='' && brand!='')?$('.item_other>span').append(" and "+$("#item_brand option:selected").text()):'';
                    (cat=='' && brand!='')?$('.item_other>span').append(" in "+$("#item_brand option:selected").text()):'';
                }, 500);
        }
    });  
    window.location.href = "checkout.php";
});


$("#skip").on("click",function(){ $("#login").fadeOut(); });

$(".custom-radio.d-method-opt").on("click",function(){
    $(".custom-radio.d-method-opt").children("input").attr("checked",false);
    //$(this).children("input").attr("checked",true);

    //alert($(this).children("input").prop("checked"));
/**/
    if($(this).hasClass("door")){
        $(".collapse.other-details").fadeIn(1000);
        $(this).children("input#door").attr("checked",true); 
    }
    else
        $(".collapse.other-details").fadeOut();

    $(".d-station").children("#ps_address").text("");
    $(".d-station").children("#ship_fee").fadeOut();
    $(".d-station").children("#ship_fee").text("0");

    var ship_fee = $(this).children("div").children(".item-amount").text();
    total_shipment(ship_fee);
});

$(".ps_address button").on("click", function() {
    $(".collapse.other-details").fadeOut();

    var id = $(this).parent().parent().attr("id");
    var add = $("#"+id).children("div.p_address").text();
    var fee = $("#"+id).children("span.p_fee").text();
    $("#p_station").modal("hide");
    $(".d-station").children("#ps_address").text(add);
    $(".d-station").children("#ship_fee").fadeIn();
    $(".d-station").children("#ship_fee").text(fee);

    $(".custom-radio.d-method-opt").children("input").attr("checked",false);
    $(".custom-radio.d-method-opt").children("input#station").attr("checked",true);  

    total_shipment(fee);
});

function total_shipment(ship_fee){
    var item = $(".summary").children("div").children(".item-total").text();
    var total = parseFloat(item.replace(",","")) + parseFloat(ship_fee.replace(",",""));
    $(".shipping-fee").text(ship_fee);
    $(".total-amount").text(total.toLocaleString());
}

$(".custom-radio.p-method-opt").on("click",function(){
    $(".custom-radio.p-method-opt").children("input").attr("checked",false);
    $(this).children("input").attr("checked",true);
});


$('.page-item').on("click", function(){
    //$(this).toggleClass("active");
});



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

loadBox("#state",state,"");