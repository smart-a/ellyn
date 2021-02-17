state = ["ABUJA FCT","ABIA","ADAMAWA","AKWA IBOM","ANAMBRA","BAUCHI","BAYELSA","BENUE","BORNO","CROSS RIVER","DELTA",
                    "EBONYI","EDO","EKITI","ENUGU","GOMBE","IMO","JIGAWA","KADUNA","KANO","KATSINA","KEBBI","KOGI","KWARA","LAGOS",
                    "NASSARAWA","NIGER","OGUN","ONDO","OSUN","OYO","PLATEAU","RIVERS","SOKOTO","TARABA","YOBE","ZAMFARA"];

$("#signup").on("click", function(){
    $(this).children(".sp").addClass("spinner-border spinner-border-sm mr-2");

    setTimeout(function(){
        if(check_field()){
            var email = $("#email").val();
            if(email.indexOf('@')<1 || email.indexOf('.')<3)
                alert("Invalid email address");
            else
                $("#set-password").modal("show");
        }

       $('#signup').children(".sp").removeClass("spinner-border spinner-border-sm mr-2");
    },500)
});

$('.edit-input').on('click',function(){
    var id = $(this).attr('data-id');
    $('#'+id).attr('disabled',false);
    $('#'+id).focus();
});

$('.input-group>input,.input-group>select').focusout(function(){
    $(this).attr('disabled',true);
});

$("#update").on("click", function(){
    //alert('here');
    $(this).children(".sp").addClass("spinner-border spinner-border-sm mr-2");
    var fullname = $("#fullname").val();
    var country = $("#country").prop('value');
    var state = $("#state").prop('value');
    var address = $("#address").val();
    var city = $("#city").prop('value');
    var phone = $("#phone").val();
    var email = $("#email").val();
    setTimeout(function(){
        if(check_field()){
            var form_data = new FormData();
            form_data.append("username",$.trim($('.user-id').text()));
            form_data.append("fullname",fullname);
            form_data.append("country",country);
            form_data.append("state",state);
            form_data.append("address",address);
            form_data.append("city",city);
            form_data.append("email",email);
            form_data.append("phone",phone);
            form_data.append("action",'update');
            $.ajax({
                type: "POST",
                url: "account_backend.php",
                data: form_data,
                contentType: false,
                processData: false,
                success: function(e){
                    alert(e);
                    if(e > 0){
                        $(".msg-alert").children('span').text(' Update succeed');
                        $(".msg-alert").children('i').remove();
                        $(".msg-alert").children('a').append('<i class="fa fa-check-circle" style="color:green"></i>');
                        $(".msg-alert").fadeIn(1000);
                    }else{
                        $(".msg-alert").children('i').remove();
                        $(".msg-alert").children('a').append('<i class="fa fa-exclamation-triangle" style="color:red"></i>');
                        $(".msg-alert").children('span').text(' Invalid user details');
                        $(".msg-alert").fadeIn(1000);
                    }
                    setTimeout(function(){
                        $(".msg-alert").fadeOut(2000);
                    },4000);
                }
            }); 
        }

       $('#update').children(".sp").removeClass("spinner-border spinner-border-sm mr-2");
    },500)
});

$("#confirm-acct").on("click", function(){
    //alert('here');
    $(this).children(".sp").addClass("spinner-border spinner-border-sm mr-2");

    setTimeout(function(){
        if(check_field()){
            var email = $("#email").val();
            if(email.indexOf('@')<1 || email.indexOf('.')<3)
                alert("Invalid email address");
            else{
                var form_data = new FormData();
                form_data.append("user",$('#user_check').val());
                form_data.append("email",email);
                form_data.append("phone",$('#phone').val());
                form_data.append("action",'check-details');
                $.ajax({
                    type: "POST",
                    url: "account_backend.php",
                    data: form_data,
                    contentType: false,
                    processData: false,
                    success: function(e){
                        //alert(e);
                        if(e > 0){
                            $('#user').val($("#user_check").val());
                            $("#set-password").modal("show");
                        }
                        else{
                            $(".msg-alert").children('span').text(' Invalid user details');
                            $(".msg-alert").fadeIn(1000);
                            setTimeout(function(){
                                $(".msg-alert").fadeOut(2000);
                            },4000);
                            return true
                        }
                    }
                });
                
            }
                
        }

       $('#confirm-acct').children(".sp").removeClass("spinner-border spinner-border-sm mr-2");
    },500)
});

$("#create").on("click", function(){
    $(this).children(".sp").addClass("spinner-border spinner-border-sm mr-2");
    setTimeout(function(){
        var user = $("#user").val();
        var pass = $("#password").val();
        var pass2 = $('#password2').val();

        $('#create').children(".sp").removeClass("spinner-border spinner-border-sm mr-2");
        if(!check_user(user))
            return;
        
        if(user=='' || user.length < 5){
            $("#user").focus();
            $('#user').css('border-color','red');
            $('.user-msg').addClass('short')
            $('.user-msg').text('Username must be at least 5 letters!');
            return;
        } 

        if(pass==''){
            $("#password").focus();
            $('#password').css('border-color','red');
            return;
        }

        var val = $('#create').val();
        if(val=='Good' || val=='Strong'){
            if(pass != pass2){
                $('#p2-msg').removeClass('d-none');
                $('#password2').css('border-color','red');
                $('#password2').focus();
                return false;
            }else{
                $(".username").val(user);
                $(".password").val(pass);
                $("#account_form").submit();
            }
        }  
        else{
            $("#password").focus();
            $('#password').css('border-color','red');
            return;
        }
       
    },500)
});

$('#user').keyup(function(){
    var val = $(this).val();
    $('.user-msg').text('');
    check_user(val);   
});

$('.menu-opt').on('click',function(){
    var menu = $(this).attr('menu');
    menu_display(menu);
});

$(document).ready(function(){
    var menu = $(location).attr('search').split('=')[1];
    menu_display(menu);

    if($(window).width() <= 987){
        $('.menu-opt,.acct-menu').attr('data-target','#navmenu');
    }else{
        $('.menu-opt,.acct-menu').attr('data-target','');
    }
});

function menu_display(menu){
    $('.menu-container>section').each(function(){
        $(this).addClass('d-none');
    });
    switch (menu) {
        case 'acct-details':
            $('#acct-details').removeClass('d-none');
            history.replaceState('',"Account Details","?menu=acct-details")
            var state = $('#st')
            break;
        case 'wishlist':
            wishlist();
            break;
        case 'change-password':
            history.replaceState('',"Change Passowrd","?menu=change-password")
            $('#change-password').removeClass('d-none');
            break;
    
        default:
            break;
    }
}

function setStateCity(action,field, value){
    var data = '<option value="" disabled selected>Select option</option>';
    
    if(action == 'set'){
        if(field=='state'){
            state.forEach(val => {
                data += '<option>'+val+'</option>';
            });
        }else{
            var opt = state.indexOf(field);
        }
    }
    $("#"+field).html(data);
    $("#"+field).val(value);
}

function wishlist(){
    var user_id = $.trim($('.user-id').text());
    var form_data = new FormData();
    form_data.append("user_id",user_id);
    form_data.append("action","liked-item");
    $.ajax({
        type: "POST",
        url: "index_backend.php",
        data: form_data,
        contentType: false,
        processData: false,
        success: function(e){
            //alert(e);
            if(e!=''){
                $('#like-body').html(e);
                $('#item-like').removeClass('d-none');
                history.replaceState('',"Wishlist","?menu=wishlist") //{page: 1}
            }
                
        }
    }); 
}

function check_user(val){
    var form_data = new FormData();
    form_data.append("value",val);
    form_data.append("action",'check-user');
    if(val.length >= 5){
        $.ajax({
            type: "POST",
            url: "account_backend.php",
            data: form_data,
            contentType: false,
            processData: false,
            success: function(e){
                //alert(e);
                if(e > 0 && !$("#create").hasClass('reset')){
                    $('#user').css('border-color','red');
                    $('.user-msg').addClass('short')
                    $('.user-msg').text('Username already exist, try again!');
                    $('#user').focus();
                    $('#password').val('');
                    $('#password2').val('');
                    $('#progress').removeClass().addClass('progress-bar');
                    $('#strength').html('');
                    return false;
                }
                else{
                    $('.user-msg').text('');
                    $('.user-msg').removeClass('short')
                    return true
                }
            }
        });
    }
    return true;
}

var timer;
function check_field(){
    var fullname = $("#fullname").val();
    var country = $("#country").prop('value');
    var state = $("#state").prop('value');
    var address = $("#address").val();
    var city = $("#city").prop('value');
    var phone = $("#phone").val();
    var email = $("#email").val();
    var user_check = $("#user-check").val();
    var status = true;

    if(fullname=='' || country=='' || state=='' || address=='' || city=='' || phone=='' || email=='' || user_check==''){
        $(".msg-alert").children('span').text(' All fields are required');
        $(".msg-alert").fadeIn(1000);
        status = false;
        timer = setTimeout(function(){
            $(".msg-alert").fadeOut(2000);
        },4000);
    }
    return status;
}