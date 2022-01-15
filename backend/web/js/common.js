
$(document).ready(function () {

    $('.select_event_country').change(function () {
        var country_id = $(this).val();
        $.ajax({
            type: "POST",
            url: baseurl + "/ajax/get-city",
            data: {country_id: country_id}
        }).done(function (data) {
            $('.select_city').html(data);
        });

    });
});



