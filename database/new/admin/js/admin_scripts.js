$(document).ready(function () {
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    let lang = $('html').attr('lang');


    let hash = window.location.hash;
    if($(hash).length) {
        $('a[href="'+hash+'"]').tab('show');
    }

    $(".logout").click(function (e) {
        e.preventDefault();
        $.ajax({
            url: '/admin/logout',
            type: 'POST',
            data: {_token: CSRF_TOKEN},
            dataType: 'JSON',
            success: function (data) {
                if(data.status === 1) {
                    window.location.href = data.text;
                }
                else if(data.status === 3) {
                    location.reload();
                }
            }
        });
    });



    let slider = $('#slider');
    if(slider.length) {
        slider.flexslider({
            animation: "slide",
            controlNav: false,
            animationLoop: false,
            slideshow: false,
            prevText: "",
            nextText: "",
        });
    }




    let datepicker_elem = $('.datepickerb');
    if(datepicker_elem.length > 0) {
        let options = {
            orientation: "bottom auto",
            format: "d MM yyyy",
            language: lang,
        };
        $(document).on('focus', '.datepickerb', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if(datepicker_elem.attr('name') === "birth_date") {
                options['startView'] = 2;
            }
            datepicker_elem.datepicker(options);
        });
    }

    let dataTableContainer = $('table[data-type="dataTable"]');
    if(dataTableContainer.length > 0) {
        dataTableContainer.each(function () {
            let data;
            let ths = $(this);
            let tabId = ths.parents('.tab-pane').attr('id');
            if(ths.attr('data-post-data')) {
                let dataToken = {_token: CSRF_TOKEN};
                let dataPost = JSON.parse(ths.attr('data-post-data'));
                data = {...dataToken, ...dataPost};
            }
            else {
                data = {_token: CSRF_TOKEN};
            }
            let columns = [];
            let parsedCols = JSON.parse($(this).attr('data-fields'));
            parsedCols.forEach(function (item) {
                columns.push({'data': item});
            });
            let columnDefs = JSON.parse($(this).attr('data-field-defs'));
            let order = JSON.parse($(this).attr('data-sort-order'));
            let dateDefs = JSON.parse($(this).attr('data-date-defs'));

            const cdLength = columnDefs.length;

            if($(this).attr('data-date-defs').length) {
                $.each(dateDefs, function (key, d) {
                    columnDefs[cdLength+key] = {
                        targets: d,
                        render: function (data) {
                            return moment(data).locale(lang).format('D\\ MMMM YYYY HH:mm');
                        },
                    };
                })
            }


            $(this).DataTable( {
                processing: true,
                serverSide: true,
                ajax: {
                    url: $(this).attr('data-url'),
                    type: 'POST',
                    data: data,
                    dataType: 'JSON',
                    dataSrc: function (d) {
                        $('a[href="#'+tabId+'"]').find('.upd').html(d.recordsTotal);
                        $('span[data-alias='+tabId+']').html(d.recordsTotal);
                        return d.data;
                    }
                },
                columns: columns,
                order: order,
                columnDefs: columnDefs,
                createdRow: function (row, data, index) {
                    $(row).attr('id', "trow_"+index);
                },
                language: {
                    url: "/js/dataTables."+lang+".lang"
                }
            } );
        });
    }


    $(document).on('click', 'a[data-action="ajax"]', function (e) {
        e.preventDefault();
        let ths = $(this);
        let data;
        if(ths.attr('data-post-data')) {
            let dataToken = {_token: CSRF_TOKEN};
            let dataPost = JSON.parse(ths.attr('data-post-data'));
            data = {...dataToken, ...dataPost};
        }
        else {
            data = {_token: CSRF_TOKEN};
        }
        alertify.confirm(ths.attr('data-ok'), ths.attr('data-confirm-msg'),
            function(){
                $.ajax({
                    url: ths.attr('href'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: data,
                    success: function (data) {
                        if(data.status === 1) {
                            alertify.success(data.text);
                        }
                        else {
                            alertify.error(data.text);
                        }
                    },
                    error:function () {
                        alertify.error();
                    }
                });
            },
            function(){ alertify.error()});
    });


    let ajaxForm = $('form[data-type="ajax"]');
    ajaxForm.submit(function (e) {
        e.preventDefault();
        let formData = new FormData($(this)[0]);
        if($(this).find('#birth_date').length) {
            let psdate = moment($(this).find('#birth_date').datepicker("getDate")).format("YYYY-MM-DD");
            formData.append("birth_date", psdate);
        }
        $.ajax({
            url: $(this).attr('data-action'),
            type: 'POST',
            cache: false,
            processData: false,
            async: true,
            context: this,
            contentType: false,
            data: formData,
            dataType: 'JSON',
            success: function (data) {
                if (data.status === 1) {
                    alertify.success(data.text);
                }
                else {
                    alertify.error(data.text);
                }
            },
            error: function (data) {
                alertify.error("Error");
            }
        });
    });


    let typeahead = $('input[data-provide="typeahead"]');
    if (typeahead.length) {
        typeahead.each(function () {
            console.log($(this));
            let bhd = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace($(this).attr('data-display')),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: {
                    url: $(this).attr('data-remote-url'),
                    wildcard: '%QUERY'
                }
            });
            $(this).typeahead(null, {
                display: $(this).attr('data-display'),
                source: bhd,
                minLength: 2
            });
        });
    }

    $('.vehicle-license-edit, .drivers-license-edit, .payout-edit').find('select[name="status"]').change(function () {
        let value = parseInt($(this).val());
        if(value === 3) {
            $(this).parent().append('<br><textarea name="reason" class="form-control" placeholder="Reason of rejection"></textarea>');
        }
    });

    let has_vehicle_scheme = $('.has-vehicle-scheme');
    let draggableSeat = $('.draggable-vehicle');
    if (draggableSeat.length > 0 && has_vehicle_scheme.length < 1) {
        draggableVehicle(draggableSeat, 4);
    }

    function draggableVehicle(vehicleSeat, row) {
        vehicleSeat.find('.vehicle-seat').each(function () {
            $(this).find('.fromtop').val(parseInt($(this).css('top')));
            $(this).find('.fromleft').val(parseInt($(this).css('left')));
        }).draggable({
            grid: [5, 5],
            drag: function (event, ui) {
                let max = 0;
                let frontEnd;
                let seatSeparator;
                if (row === 4) {
                    frontEnd = 350;
                    seatSeparator = -182;
                } else if (row === 5) {
                    frontEnd = 210;
                    seatSeparator = -85;
                }
                let vehicle_scheme = $('.seat-parent');
                $(this).find('.fromtop').val(ui.position.top);
                $(this).find('.fromleft').val(ui.position.left);
                $('.vehicle-seat').each(function () {
                    let value = parseInt($(this).css('left'));
                    max = (value > max) ? value : max;
                });
                if (ui.position.left >= max) {
                    vehicle_scheme.css('width', ui.position.left + seatSeparator + 'px');
                    vehicle_scheme.parents('.draggable-vehicle').css('width', ui.position.left + seatSeparator + frontEnd + 'px');
                }
            }
        });
    }

    function hasVehicleScheme(draggableSeat, hasVehicleScheme, seatParent) {
        if (hasVehicleScheme.length) {
            let frontEnd;
            let row;
            let seatSeparator;
            if (parseInt($('#type').val()) === 3) {
                $(document).off('click', '.remove_seat');
            } else {
                if (parseInt($('#type').val()) === 2) {
                    frontEnd = 210;
                    seatSeparator = -85;
                    row = 5;
                } else if (parseInt($('#type').val()) === 1) {
                    frontEnd = 350;
                    seatSeparator = -182;
                    row = 4;
                }

                if (draggableSeat.length > 0) {
                    draggableVehicle(draggableSeat, row);
                }
            }

            let max = 0;

            $('.vehicle-seat').each(function () {
                let value = parseInt($(this).css('left'));
                max = (value > max) ? value : max;
            });

            let toSeparate = max + seatSeparator;
            seatParent.css('width', toSeparate + 'px');
            draggableSeat.css('width', frontEnd + toSeparate + 'px');
            $('.vehicle-scheme-wrapper').css('display','block');

        }
    }

    //On edit page
    let seat_parent = $(".seat-parent");
    hasVehicleScheme(draggableSeat, has_vehicle_scheme, seat_parent);


});
