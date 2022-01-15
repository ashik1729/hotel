
$(document).ready(function () {
//    START

    var attr_value_html_content = $('.attribute_value_item').html();
    $('.temp_value').html(attr_value_html_content);
    $('.temp_value').find(".attr_value_title h3>span").html("Attribute Value");
    $('.temp_value input').each(function (key, value) {
        $(this).val('');
        $(this).attr('value', '');
    });
    var attr_value_html = $('.temp_value').html();
    $('.temp_value').empty();


    var attr_html_content = $('.attribute_item').html();
    $('.temp_name').html(attr_html_content);
    $('.temp_name').find(".attr_title h3>span").html("Attribute Name");
    $('.temp_name .attribute_value_item').each(function (key, value) {
        $(this).remove();
        $(this).attr('value', '');
    });
    $('.temp_name input').each(function (key, value) {
        $(this).val('');
        $(this).attr('value', '');
    });
    var result_attr_value_html = "<div class='attribute_value_item'>" + attr_value_html + "</div>";
    $('.temp_name .attr_contents').append(result_attr_value_html);
    var attr_html = $('.temp_name').html();
    $('.temp_name').empty();
    $('.attribute_item input').each(function (key, value) {
        var name = $(this).attr('name');
        var count = $(this).closest('.attribute_item').attr('key');
//        alert(count);
        var newname = name.replace("attcnt", count);
        $(this).attr('name', newname);
    });
//    END

// START Delete Attr Item And Value
    $(document).on("click", ".delete_attr_item", function () {
        $(".loader-wrapp").show();

        if (confirm('Are You Sure Want Delet this value')) {

            var product_attr_id = $(this).attr('get_product_attribute');
            if (product_attr_id != "") {
                $.ajax({
                    url: basepath + "/products/products-services/delete-product-attribute-main",
                    type: "POST",
                    data: {product_attr_id: product_attr_id},

                    success: function (data)
                    {
                        var obj = JSON.parse(data);
                        if (obj.status == 200) {
                            //   $.pjax.reload({container: '#attribute_section', async: true});
                            $.pjax.reload('#attribute_section', {timeout: false, async: true});
                            $(".loader-wrapp").hide();
                            return true;
                        } else {
                            $(this).closest(".attribute_item").remove();

                            $('.attr_error').html(obj.message);
                        }
                    },
                    error: function (e) {
                        console.log(e);
                    }
                });

            } else {
                $(this).closest(".attribute_item").remove();
                $(".loader-wrapp").hide();

            }
        }
    });
    $(document).on("click", ".delete_attr_value", function () {
        $(".loader-wrapp").show();
        if (confirm('Are You Sure Want Delet this value')) {

            var product_attr_id = $(this).attr('product_attr_id');
            if (product_attr_id != "") {
                $.ajax({
                    url: basepath + "/products/products-services/delete-product-attribute",
                    type: "POST",
                    data: {product_attr_id: product_attr_id},

                    success: function (data)
                    {
                        var obj = JSON.parse(data);
                        if (obj.status == 200) {
                            //   $.pjax.reload({container: '#attribute_section', async: true});
                            $.pjax.reload('#attribute_section', {timeout: false, async: true});
                            $(".loader-wrapp").hide();
                            return true;
                        } else {
                            $('.attr_error').html(obj.message);
                        }
                    },
                    error: function (e) {
                        console.log(e);
                    }
                });

            } else {
                $(this).closest(".attribute_value_item").remove();
                $(".loader-wrapp").hide();

            }
        }
//        $(this).closest(".attribute_value_item").remove();
    });
//END
    $(document.body).on("mouseenter", ".attribute_item", function () {
        $(this).find('.delete_attr_item').show();
    });
    $(document.body).on("mouseleave", ".attribute_item", function () {
        $(this).find('.delete_attr_item').hide();
    });
    $(document.body).on("mouseenter", ".attribute_value_item", function () {
        $(this).find('.delete_attr_value').show();
    });
    $(document.body).on("mouseleave", ".attribute_value_item", function () {
        $(this).find('.delete_attr_value').hide();
    });
    $(document.body).on("click", ".btn_add_attr_value", function (e) {
        var result_attr_value_html = "<div class='attribute_value_item'>" + attr_value_html + "</div>";
        $(this).closest('.attribute_item').find(".attr_contents").append(result_attr_value_html);
        var count = $(this).closest('.attribute_item').attr('key');
        $('[key=' + count + '] input').each(function (key, value) {
            var name = $(this).attr('name');
            var newname = name.replace("attcnt", count);
            $(this).attr('name', newname);
        });
    });
    $(document.body).on("click", ".btn_add_attr", function (e) {
        var currentCount = $(".attribute_item").length;
        var count = Number(currentCount);
        var name = "ProductAttributesValue[attribute_id][" + count + "][en][]";
        var result_attr_html = "<div key='" + count + "' class='attribute_item'>" + attr_html + "</div>";
        $(".attribute_area").append(result_attr_html);
        $('[key=' + count + '] input').each(function (key, value) {
            var name = $(this).attr('name');
            var newname = name.replace("attcnt", count);
            $(this).attr('name', newname);
        });
    });

    $(window).click(function () {
        //Hide the menus if visible
        $('.pop_over_content').hide();
    });

//$('.pop_over_content').click(function(event){
//  event.stopPropagation();
//});

    $(document.body).on("click", ".pop_over_content ul>.attr_item", function (e) {
        var text_en = $(this).attr('text_en');
        var text_ar = $(this).attr('text_ar');
        var lang = $(this).attr('lang');

        if (text_ar != 'null') {
            $(this).closest('.attr_data').find(".attr_ar").val(text_ar);
        }
        if (text_en != 'null') {
            $(this).closest('.attribute_item').find(".attr_title h3>span").html(text_en);

            $(this).closest('.attr_data').find(".attr_en").val(text_en);
        }
        $(this).closest('.attr_parent').find('.pop_over_content').hide();

    });
    $(document.body).on("input", ".change_attr_name", function (e) {
        var value = $(this).val().replace(/^\s+|\s+$/g, "");
        var lang = $(this).attr("lang");
        var el = $(this);
        if (lang == "1") {
            $(this).closest('.attribute_item').find(".attr_title h3>span").html(value.toUpperCase());
        }
        if (value != "") {
            if (value.length >= 0) {
                $(this).closest('.attr_parent').find('.pop_over_content').show();
                $(this).closest('.attr_parent').find('.pop_over_content .loader_wrapper').show();
                $.ajax({
                    type: "POST",
                    url: baseurl + "/ajax/get-attributes-list?lang=" + lang + "&q=" + value
                }).done(function (data) {
                    el.closest('.attr_parent').find('.pop_over_content ul').empty();
                    if (data.length > 0) {
                        el.closest('.attr_parent').find('.pop_over_content .loader_wrapper').hide();
                        $.each(data, function (key, val) {
                            var html = "<li class='attr_item' lang='" + lang + "' text_en='" + val.text_en + "' text_ar='" + val.text_ar + "'>" + val.text + "</li>";
                            el.closest('.attr_parent').find('.pop_over_content ul').append(html);
                        });
                        el.closest('.attr_parent').find('.pop_over_content ul').show();
                    } else {
                        el.closest('.attr_parent').find('.pop_over_content ul').empty();
                        el.closest('.attr_parent').find('.pop_over_content').hide();
                    }


                });
            } else {
                $(this).closest('.attribute_item').find(".attr_title h3>span").html("Attribute Name");
                el.closest('.attr_val_parent').find('.pop_over_content').hide();
            }
        } else {
            $(this).closest('.attribute_item').find(".attr_title h3>span").html("Attribute Name");
            console.log("empty");

        }
    });
    $(document.body).on("click", ".pop_over_content ul>.attr_val_item", function (e) {
        var text_en = $(this).attr('text_en');
        var text_ar = $(this).attr('text_ar');
        var lang = $(this).attr('lang');
        if (text_ar !== 'null') {

            $(this).closest('.attr_val_parent').find(".attr_value_ar").val(text_ar);
        }
        if (text_en !== 'null') {
            $(this).closest('.attribute_value_item').find(".attr_value_title h3>span").html(text_en);

            $(this).closest('.attr_val_parent').find(".attr_value_en").val(text_en);
        }
        $(this).closest('.attr_val_parent').find('.pop_over_content').hide();

    });
    $(document.body).on("input", ".change_attr_value_name", function (e) {
        var value = $(this).val().replace(/^\s+|\s+$/g, "");
        var lang = $(this).attr("lang");
        var el = $(this);
        if (lang == "1") {
            $(this).closest('.attribute_value_item').find(".attr_value_title h3>span").html(value.toUpperCase());
        }
        if (value != "") {
            if (value.length >= 0) {
                $(this).closest('.attr_val_parent').find('.pop_over_content').show();
                $(this).closest('.attr_val_parent').find('.pop_over_content .loader_wrapper').show();
                $.ajax({
                    type: "POST",
                    url: baseurl + "/ajax/get-attributes-value-list?lang=" + lang + "&q=" + value
                }).done(function (data) {
                    el.closest('.attr_val_parent').find('.pop_over_content ul').empty();
                    if (data.length > 0) {
                        el.closest('.attr_val_parent').find('.pop_over_content .loader_wrapper').hide();
                        $.each(data, function (key, val) {
                            var html = "<li class='attr_val_item' lang='" + lang + "' text_en='" + val.text_en + "' text_ar='" + val.text_ar + "'>" + val.text + "</li>";
                            el.closest('.attr_val_parent').find('.pop_over_content ul').append(html);
                        });
                        el.closest('.attr_val_parent').find('.pop_over_content ul').show();
                    } else {
                        el.closest('.attr_val_parent').find('.pop_over_content ul').empty();
                        el.closest('.attr_val_parent').find('.pop_over_content').hide();
                    }


                });
            } else {
                $(this).closest('.attribute_value_item').find(".attr_value_title h3>span").html("Attribute Values");
                el.closest('.attr_val_parent').find('.pop_over_content').hide();
            }
        } else {
            $(this).closest('.attribute_value_item').find(".attr_value_title h3>span").html("Attribute Values");
            console.log("empty");

        }
    });
    $(document.body).on("change", "#attr_id_english", function () {

        var values = $(this).val();
        $(this).closest('.attribute_item').find('.attr_title h3>span').text(values);
        $.ajax({
            type: "POST",
            url: baseurl + "/ajax/get-attributes-list?lang=1&id=" + values,
        }).done(function (data) {
            var text = data.items.text_ar;

            if (text != "") {
                var newOption = $("<option selected='selected'></option>").val(text).text(text);
                $("#attr_id_arabic").append(newOption).trigger('change');
            }

        });

    });

    $(document.body).on("click", ".change_attr_name .choose_attr", function () {

        var value_en = $(this).attr('name_en');
        var value_ar = $(this).attr('name_ar');
        $('#attr_id_english').val(value_en).trigger('change');
        $('#attr_id_arabic').val(value_ar).trigger('change');
    });


//        Get Category and discount based on merchant

    $(document.body).on("change", ".merchant_change", function () {
//        $(".change_category").select2('data', null)
        //      $("#productsservices-category_id").empty();
        var merchant_id = $(this).val();
        $.ajax({
            type: "POST",
            url: baseurl + "/products/products-services/get-discounts",
            data: {merchant_id: merchant_id}
        }).done(function (data) {
            var obj = JSON.parse(data);
            if (obj.status == 200) {
                $('.discount_change').html(obj.message.discount);
//            $(".change_category").select2({
//                'data':obj.message.category,
//                placeholder: "Select a  Category.",
//                'allowClear':true,
//                'closeOnSelect':true,
//                'tokenSeparators': [','],
//                'maximumInputLength': 255,'theme':'material'});
            }
            //   $('#productsservices-category_id').val('').trigger('change');

        });

    });

    $(document).on("click", ".edit_attr", function () {
        var update_attr = $(this).closest('.parent_attr').find(".update_attr").html();
        var update_attr_aid = $(this).closest('.parent_attr').find(".update_attr").attr('attr_id');
        var update_attr_value = $(this).closest('.parent_attr').find(".update_attr_value").html();
        var update_attr_value_id = $(this).closest('.parent_attr').find(".update_attr_value_id").html();
        var update_attr_id = $(this).closest('.parent_attr').find(".update_attr_id").html();
        var update_attr_qty = $(this).closest('.parent_attr').find(".update_attr_qty").html();
        var update_attr_price = $(this).closest('.parent_attr').find(".update_attr_price").html();
        var update_attr_price_status = $(this).closest('.parent_attr').find(".update_attr_price_status").html();
        var update_attr_sort = $(this).closest('.parent_attr').find(".update_attr_sort").html();
        $('#input_update_attr').val(update_attr_aid).trigger('change', update_attr_value_id);
        $('#input_update_attr_qty').val(update_attr_qty);
        $('#input_update_attr_id').val(update_attr_id);
        $('#input_update_attr_price').val(update_attr_price);
        if (update_attr_price_status == 1) {

            $('#input_update_attr_price_status').prop("checked", true);
        } else {
            $('#input_update_attr_price_status').prop("checked", false);
        }
        //  $('#input_update_attr_price_status').val(update_attr_price_status);
        $('#input_update_attr_sort').val(update_attr_sort);
        $('#editAttributeValueModal').modal('show');

    });
    $(document).on("change", ".attr_name", function () {
        var attribute = $(this).val();
        var el = $(this);
        if (attribute == '') {
            $(this).closest('.form-group').find('.help-block').html('Choose One Attribute');
        } else {
            $('.attr_name').closest('.form-group').find('.help-block').html('');
        }
        $.ajax({
            type: "POST",
            url: baseurl + "/products/products-services/get-attr-values",
            data: {attribute: attribute}
        }).done(function (data) {

            var obj = JSON.parse(data);
            console.log(obj);
            if (obj.status == 200) {
                el.closest('tr').find('.attr_value').html(obj.message);

//            $.pjax.reload({container: '#attribute_section', async: true});
            }


            el.closest('tr').find(".attribute").select2({'tags': true,
                'allowClear': true,
                'closeOnSelect': true,
                'tokenSeparators': [','],
                'maximumInputLength': 40, 'theme': 'material'});
        });

    });
    $(document).on("change", ".update_attr_name", function (index, value) {

        var attribute = $(this).find("option:selected").text();
//            var attribute = $( "#update_attr_name option:selected" ).text();
        var el = $(this);
        if (attribute == '') {
            $(this).closest('.form-group').find('.help-block').html('Choose One Attribute');
        } else {
            $('.attr_name').closest('.form-group').find('.help-block').html('');
        }
        $.ajax({
            type: "POST",
            url: baseurl + "/products/products-services/get-attr-values",
            data: {attribute: attribute}
        }).done(function (data) {

            var obj = JSON.parse(data);
            console.log(obj);
            if (obj.status == 200) {
                el.closest('.update_attr_modal').find('.update_attr_value').html(obj.message);




//            $.pjax.reload({container: '#attribute_section', async: true});
            }


            el.closest('.update_attr_modal').find(".update_attr_value").select2({
                'allowClear': true,
                'closeOnSelect': true,
                'maximumInputLength': 40, 'theme': 'material'});
            if (value) {

                el.closest('.update_attr_modal').find('.update_attr_value').val(value).trigger('change');
            }

        });

    });

//    $(".attribute").select2({
//        'tags': true,
//        'allowClear': true,
//        'closeOnSelect': true,
//        'tokenSeparators': [',', ' '],
//        'maximumInputLength': 40, 'theme': 'material'
//    });
//    $(".attribute_update").select2({
////            'tags' : true,
//        'allowClear': true,
//        'closeOnSelect': true,
////            'tokenSeparators': [',', ' '],
//        'maximumInputLength': 40, 'theme': 'material'
//    });

    $(".add_cat").on('click', function () {
        $('#addcatModal').modal('show');

        // $.pjax.reload({container: '#product_service', async: true});
    });
    $(".add_cat").on('click', function () {
        $('#addcatModal').modal('show');

        // $.pjax.reload({container: '#product_service', async: true});
    });
    $(".save_cat").on('click', function () {
        var parentcat = $('.parentcat').val();
        var cat_name = $('.cat_name').val();
        if (parentcat == '') {
            $('.parentcat').closest('.form-group').find('.help-block').html('Parent Category Required');
        } else {
            $('.parentcat').closest('.form-group').find('.help-block').html('');

        }
        if (cat_name == '') {
            $('.cat_name').closest('.form-group').find('.help-block').html('Category Name Required');
        } else {
            $('.cat_name').closest('.form-group').find('.help-block').html('');
        }

        $.ajax({
            url: basepath + "/products-services/add-category",
            type: "POST",
            data: {parentcat: parentcat, cat_name: cat_name},
            success: function (data)
            {
                var obj = JSON.parse(data);
                console.log(obj);
                if (obj.status == 200) {
                    $('.parentcat').val("");
                    $('.cat_name').val("");
                    $.pjax.reload({container: '#product_service', async: true});
                    $('#addcatModal').modal('hide');
                } else {
                    $('.cat_error').html(obj.message);
                }
            },
            error: function (e) {
                console.log(e);
            }
        });
    });
});



var actions = $(".actions").html();
$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();

//	var form_content = $("table tbody tr:last-child").html();
    var form_content = actions;
    // Append table with add row form on add new button click
    $(".add-new").click(function () {

        //$(this).attr("disabled", "disabled");
        var index = $("table tbody tr:last-child").index();
        var row = form_content;
        var row = '<tr>' + form_content +
                '</tr>';
        console.log(form_content);
        $("table tbody").append(row);
        $(".table tbody tr:last-child .attr_name").prop("id", "attr_child_row_name_" + index);
        $(".table tbody tr:last-child .attr_value").prop("id", "attr_child_row_value_" + index);
//      $("#ashikali").select2();
        $(".attribute").select2({'tags': true,
            'allowClear': true,
            'closeOnSelect': true,

            'tokenSeparators': [',', ' '],
            'maximumInputLength': 40, 'theme': 'material'});
        $("table tbody tr").eq(index + 1).find(".add, .edit").toggle();
        $('[data-toggle="tooltip"]').tooltip();
    });
    // Add row on add button click
    $(document).on("click", ".add", function () {
        var empty = false;
        var input = $(this).parents("tr").find('input[type="text"]');
        input.each(function () {
            if (!$(this).val()) {
                $(this).addClass("error");
                empty = true;
            } else {
                $(this).removeClass("error");
            }
        });
        $(this).parents("tr").find(".error").first().focus();
        if (!empty) {
            input.each(function () {
                $(this).parent("td").html($(this).val());
            });
            $(this).parents("tr").find(".add, .edit").toggle();
            $(".add-new").removeAttr("disabled");
        }
    });
    // Edit row on edit button click
    $(document).on("click", ".edit", function () {
        $(this).parents("tr").find("td:not(:last-child)").each(function () {
            $(this).html('<input type="text" class="form-control" value="' + $(this).text() + '">');
        });
        $(this).parents("tr").find(".add, .edit").toggle();
        $(".add-new").attr("disabled", "disabled");
    });
    // Delete row on delete button click
    $(document).on("click", ".delete", function () {
        $(this).parents("tr").remove();
        $(".add-new").removeAttr("disabled");
    });
});


$(".save_update_attr").on('click', function () {
    var attr_name = $('#input_update_attr').val();
    var attr_value = $('#input_update_attr_value').val();
    var attr_qty = $('#input_update_attr_qty').val();
    var attr_price = $('#input_update_attr_price').val();
    var attr_price_status = $('#input_update_attr_price_status').val();
    var attr_sort = $('#input_update_attr_sort').val();
    var attr_id = $('#input_update_attr_id').val();
    if (attr_name == '') {
        $('.update_attr_name').closest('.form-group').find('.help-block').html('Attribute Required');
    } else {
        $('.update_attr_name').closest('.form-group').find('.help-block').html('');
    }
    if (attr_value == '') {
        $('.update_attr_value').closest('.form-group').find('.help-block').html('Attribute Value Required');
    } else {
        $('.update_attr_value').closest('.form-group').find('.help-block').html('');
    }

    $.ajax({
        url: basepath + "/products/products-services/update-product-attribute",
        type: "POST",
        data: $("#update_attr_form").serialize(),
        success: function (data)
        {
            var obj = JSON.parse(data);
            console.log(obj);
            if (obj.status == 200) {
                $('.add_attr_name').val("");
                $('.add_attr_value').val("");
                $.pjax.reload({container: '#attribute_section', async: true});
                $('#editAttributeValueModal').modal('hide');
                return true;
            } else {
                $('.attr_error').html(obj.message);
            }
        },
        error: function (e) {
            console.log(e);
        }
    });
});
$(".delete_delete_attr").on('click', function () {

    var product_attr_id = $(this).attr('product_attr_id');

//alert(product_attr_id);
    $.ajax({
        url: basepath + "/products/products-services/delete-product-attribute",
        type: "POST",
        data: {product_attr_id: product_attr_id},

        success: function (data)
        {
            var obj = JSON.parse(data);
            if (obj.status == 200) {
                $.pjax.reload({container: '#attribute_section', async: true});
                return true;
            } else {
                $('.attr_error').html(obj.message);
            }
        },
        error: function (e) {
            console.log(e);
        }
    });
});

$(".save_attr").on('click', function () {
    var attr_name = $('.add_attr_name').val();
    var attr_value = $('.add_attr_value').val();
    if (attr_name == '') {
        $('.add_attr_name').closest('.form-group').find('.help-block').html('Attribute Required');
    } else {
        $('.add_attr_name').closest('.form-group').find('.help-block').html('');

    }
    if (attr_value == '') {
        $('.add_attr_value').closest('.form-group').find('.help-block').html('Attribute Value Required');
    } else {
        $('.add_attr_value').closest('.form-group').find('.help-block').html('');
    }

    $.ajax({
        url: basepath + "/products/products-services/add-attribute",
        type: "POST",
        data: {attr_name: attr_name, attr_value: attr_value},
        success: function (data)
        {
            var obj = JSON.parse(data);
            console.log(obj);
            if (obj.status == 200) {
                $('.add_attr_name').val("");
                $('.add_attr_value').val("");
                $.pjax.reload({container: '#attribute_section', async: true});
                $('#addattributeModal').modal('hide');
                return true;
            } else {
                $('.attr_error').html(obj.message);
            }
        },
        error: function (e) {
            console.log(e);
        }
    });
});

var shipmentItem = $('.shipment_method_items').html();
var shipmentItemHtml = "<div class='row shipment_method_items mb-3'>" + shipmentItem + "</div>";
$(document.body).on("click", ".add_item", function () {
    var total = $(this).attr('total');
    var numItems = $('.shipment_method_items').length;
    if (numItems < total) {
        $('.shipment_method_data').append(shipmentItemHtml);
    }
});
$(document).on("click", ".delete_item", function () {
    var total = $(this).attr('total');
    var numItems = $('.shipment_method_items').length;
    $(this).closest('.shipment_method_items').remove();
    if (numItems == 1) {
        $('.shipment_method_data').append(shipmentItemHtml);
    }
});