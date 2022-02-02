$(document).ready(function() {
    $('.type_of_car').click(function() {
        $(this).toggleClass("checkedd");
    });
    $(".cart_sort_filter").change(function() {
        $('.sort_form').submit();
    });
    $(".car_enquiry").click(function() {
        var title = $(this).attr('title');
        var car_id = $(this).attr('car_id');
        var placeholderText = "I am interested in" + title + ". Please call me back";
        $(".rent-car-message").attr("placeholder", placeholderText);
        $(".rent-car-id").attr("value", car_id);
        // $("#exampleModalLabel").modal("show");

    });
    // var feed = new Instafeed({
    //     accessToken: '1884142681770933'
    // });
    // feed.run();
});

// $(document.body).on('click', '.register', function(e) {

//     $('.loader-wrapp').show();
//     var form = $(".form_update_product");
//     e.preventDefault();
//     if (form.find('.has-error').length) {
//         return false;
//     }
//     var formData = form.serialize();
//     $.ajax({
//         url: form.attr("action"),
//         type: "POST",
//         data: formData,
//         success: function(data) {
//             var obj = JSON.parse(data);
//             if (obj.status == 200) {
//                 $.pjax.reload({ container: '#order_section', async: false, timeout: false });
//                 md.showNotification('bottom', 'center', 'Order Updated', '3');
//                 $('#order_product_edit').modal('hide');
//                 $('.loader-wrapp').hide();
//                 $('body').removeClass('modal-open');
//                 $('.modal-backdrop').remove();
//             } else {
//                 $('.loader-wrapp').hide();
//             }
//         },
//         error: function() {

//             alert("Something went wrong");
//             $('.loader-wrapp').hide();

//         }

//     });

// }).on('submit', function(e) {

//     e.preventDefault();

// });

$('#reg-form').on('beforeSubmit', function(e) {

    var form = $(this);

    var formData = form.serialize();

    $.ajax({

        url: form.attr("action"),

        type: "POST",

        data: formData,

        success: function(data) {
            $('#reg-form').trigger("reset");

            $("#CreateAccount").modal("hide");
            $("#accountSuccess").modal("show");

        },

        error: function() {

            alert("Something went wrong. Please submit your form again");

        }

    });

}).on('submit', function(e) {

    e.preventDefault();

});

$('#log-form').on('beforeSubmit', function(e) {

    var form = $(this);

    var formData = form.serialize();

    $.ajax({

        url: form.attr("action"),

        type: "POST",

        data: formData,

        success: function(data) {
            if (data.status == 200) {
                $('#log-form').trigger("reset");
                $('.loginError').html("");
                $("#LoginEnquiry").modal("hide");
                location.reload();
            } else {
                $('.loginError').html(data.message);
            }
        },

        error: function() {

            alert("Something went wrong. Please submit your form again");

        }

    });

}).on('submit', function(e) {

    e.preventDefault();

});


$('#reset-form').on('beforeSubmit', function(e) {

    var form = $(this);

    var formData = form.serialize();

    $.ajax({

        url: form.attr("action"),

        type: "POST",

        data: formData,

        success: function(data) {
            if (data.status == 200) {
                $('#reset-form').trigger("reset");
                $("#resetPassword").modal("hide");
                $(".succes_body").html(data.meesage);
                $("#accountSuccess").modal("show");
            } else {
                $('.reesetError').html(data.message);
            }

            //location.reload();
        },

        error: function() {

            alert("Something went wrong. Please submit your form again");

        }

    });

}).on('submit', function(e) {

    e.preventDefault();

});

$(".period").change(function() {

    $("#time_sort").submit();
});