
$(document).ready(function () {

    $(document.body).on('click', '.slot_items', function () {

        var slot = $(this).attr('slot');
        var weekday = $(this).attr('weekday');
        var day = $(this).attr('day');
        var date = $(this).attr('date');
        if ($(this).is(":checked")) {
            var status = 0;
            changeslot(day, slot, status, weekday, date);
        } else {
            var status = 1;
            changeslot(day, slot, status, weekday, date);
        }
    });
    function changeslot(day, slot, status, weekday, date) {
        $.ajax({
            type: "POST",
            url: baseurl + "/users/merchant/change-slot",
            data: {date: date, day: day, slot: slot, merchant_id: merchant_id, status: status}
        }).done(function (data) {
            var obj = JSON.parse(data);
            if (obj.status == 200) {
                if (date == "") {
                    $.pjax.reload({container: '#slot_list', async: false, timeout: false}).done(function () {
                        $('.slot-tab .tab-content .tab-pane').removeClass('active');
                        $('.slot-tab .tab-content .tab-pane').removeClass('show');
                        $('#pills-' + weekday).addClass('show');
                        $('#pills-' + weekday).addClass('active');
                    });

                }
            } else if (obj.status == 411) {
                $(this).closest('.week_day_row').find('.week_slots_error').html(obj.error);
            }
        });

    }

    $(document.body).on('click', '.day_avail_btn', function () {
        $('.loader-wrapp').show();
        var week_day_id = $(this).attr('dayid');
        var el = $(this);
        var week_day = $(this).attr('day');
        var date = $(this).closest('.week_day_row').find('.week_date').val();
        var week_day_availability = $(this).closest('.week_day_row').find('.week_day_availability').val();
        var week_day_available_from = $(this).closest('.week_day_row').find('.week_day_available_from').val();
        var week_day_available_to = $(this).closest('.week_day_row').find('.week_day_available_to').val();
        var week_day_interval = $(this).closest('.week_day_row').find('.week_day_interval').val();
        $.ajax({
            type: "POST",
            url: baseurl + "/users/merchant/manage-slots",
            data: {date: date, merchant_id: merchant_id, week_day_id: week_day_id, week_day: week_day, week_day_availability: week_day_availability, week_day_available_from: week_day_available_from, week_day_available_to: week_day_available_to, week_day_interval: week_day_interval}
        }).done(function (data) {
            var obj = JSON.parse(data);
            if (obj.status == 200) {
//                   el.closest('.week_day_row').find('.week_slots').html(obj.message);

                if (week_day_availability == 1) {

                    $.pjax.reload({container: '#slot_list', async: false, timeout: false});
                    $.pjax.reload({container: '#slot_list', async: false, timeout: false}).done(function () {
                        $('.slot-tab .tab-content .tab-pane').removeClass('active');
                        $('.slot-tab .tab-content .tab-pane').removeClass('show');
                        $('#pills-' + week_day).addClass('show');
                        $('#pills-' + week_day).addClass('active');
                    });
                    $('.loader-wrapp').hide();

                } else {
                    el.closest('.week_day_panel').find('.week_slots').html(obj.message);
                    $('.loader-wrapp').hide();

                }
//        $.pjax.reload('#slot_list', {timeout : false});

            } else if (obj.status == 411) {
                $(this).closest('.week_day_row').find('.week_slots_error').html(obj.message);
            }
        });
    });


    $(document.body).on('click', '.date_avail_btn', function () {
        $('.loader-wrapp').show();

        var week_day_id = $(this).attr('dayid');
        var el = $(this);
        var week_day = $(this).attr('day');
        var week_date = $(this).closest('.week_day_row').find('.week_date').val();
        var week_day_availability = $(this).closest('.week_day_row').find('.week_day_availability').val();
        var week_day_available_from = $(this).closest('.week_day_row').find('.week_day_available_from').val();
        var week_day_available_to = $(this).closest('.week_day_row').find('.week_day_available_to').val();
        var week_day_interval = $(this).closest('.week_day_row').find('.week_day_interval').val();
        $.ajax({
            type: "POST",
            url: baseurl + "/users/merchant/manage-slots",
            data: {week_date: week_date, merchant_id: merchant_id, week_day_id: week_day_id, week_day: week_day, week_day_availability: week_day_availability, week_day_available_from: week_day_available_from, week_day_available_to: week_day_available_to, week_day_interval: week_day_interval}
        }).done(function (data) {
            var obj = JSON.parse(data);
            if (obj.status == 200) {
                el.closest('.week_day_row').find('.week_slots').html(obj.message);
                $('.loader-wrapp').hide();
            } else if (obj.status == 411) {
                $(this).closest('.week_day_row').find('.week_slots_error').html(obj.message);
                $('.loader-wrapp').hide();
            }

        });
    });

    $('.map_location').click(function () {
        $('#us2').locationpicker({
            location: {
                latitude: $('#us2-lat').val(),
                longitude: $('#us2-lon').val()
            },
            radius: 20,
            inputBinding: {
                latitudeInput: $('#us2-lat'),
                longitudeInput: $('#us2-lon'),
                radiusInput: $('#us2-radius'),
                locationNameInput: $('#us2-address')
            },
            enableAutocomplete: true,
            onchanged: function (currentLocation, radius, isMarkerDropped) {
            },
            onlocationnotfound: function (locationName) {},
            oninitialized: function (component) {
            }

        });
        $('#mapModal').modal('show');
    });
    $('.save_location').click(function () {

        var lat = $('#us2-lat').val();
        var lon = $('#us2-lon').val();
        if (lat != '' && lon != '') {
            $('#merchant-location').val(lat + ',' + lon);
            $('#mapModal').modal('hide');

        }
    });
    $('.select_country').change(function () {
        var country_id = $(this).val();
        $.ajax({
            type: "POST",
            url: baseurl + "/ajax/get-states",
            data: {country_id: country_id}
        }).done(function (data) {
            $('.select_state').html(data);
        });

    });
    $('.select_state').change(function () {
        var country_id = $('.select_country').val();
        var state_id = $(this).val();
        $.ajax({
            type: "POST",
            url: baseurl + "/ajax/get-city",
            data: {country_id: country_id, state_id: state_id}
        }).done(function (data) {
            $('.select_city').html(data);
        });

    });
    $('.select_city').change(function () {
        var city_id = $(this).val();
        $.ajax({
            type: "POST",
            url: baseurl + "/ajax/get-area",
            data: {city_id: city_id}
        }).done(function (data) {
            $('.select_area').html(data);
        });

    });
});



