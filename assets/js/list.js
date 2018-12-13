var myLatLng;
var map;
var markers = [];
var all_overlays = [];
var table
function initMap() {
    myLatLng = {lat: 36.1699, lng: -115.1398};

    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 10,
        center: myLatLng
    });

    var drawingManager = new google.maps.drawing.DrawingManager({

        drawingControl: true,
        drawingControlOptions: {
            position: google.maps.ControlPosition.TOP_CENTER,
            drawingModes: [ 'polyline']
        },
        markerOptions: {icon: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png'},
        circleOptions: {
            fillColor: '#ffff00',
            fillOpacity: 1,
            strokeWeight: 5,
            clickable: false,
            editable: true,
            zIndex: 1
        }
    });
    drawingManager.setMap(map);

    google.maps.event.addListener(drawingManager, 'overlaycomplete', function (event) {
        var polygonBounds = event.overlay.getPath();
        var testArray = [];

        for (var a = 0; a < polygonBounds.length; a++) {
            testArray.push([polygonBounds.getAt(a).lat(), polygonBounds.getAt(a).lng()]);
        }

        $.post("/ajax/get_polygon_all", {polygon: testArray}, function (data) {
            table.clear().draw();

            $.removeCookie("price_min");
            $.removeCookie("price_max");
            $.removeCookie("percentage_min");
            $.removeCookie("percentage_max");
            $.removeCookie("dom_min");
            $.removeCookie("dom_max");
            $.removeCookie("zip_code");
            $("#price_min").val("");
            $("#price_max").val("");
            $("#percentage_min").val("");
            $("#percentage_max").val("");
            $("#dom_min").val("");
            $("#dom_max").val("");
            $("#zip_code").val("");

            clear_markers();

            $.each(data.comps, function (index, value) {
                addMarker({lat: parseFloat(value.lat), lng: parseFloat(value.lng)});
                var new_row = table.row.add([
                    value.link,
                    value.address,
                    value.PostalCode,
                    value.OfferPriceToListPrice,
                    value.OfferPrice,
                    value.ListPrice,
                    value.BuildingDescription,
                    value.ApproxTotalLivArea,
                    value.BedsTotal,
                    value.BathsTotal,
                    value.Garage,
                    value.PvPool,
                    value.Spa,
                    value.YearBuilt,
                    value.DOM,
                    value.user,
                    value.internal_status,
                    value.lat,
                    value.lng,
                    value.user_id
                ]);

            });
            table.draw(false);

        }, "json");

        all_overlays.push(event);
    });

}


function clear_overlays() {
    for (var i = 0; i < all_overlays.length; i++) {
        all_overlays[i].overlay.setMap(null);
    }
    all_overlays = [];
}

function clear_markers() {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
    markers = [];
}


function showMarkers() {

    table.rows({ filter : 'applied'}).eq(0).each( function ( index ) {
        var row = table.row( index );
        addMarker({lat: parseFloat(row.data()[17]), lng: parseFloat(row.data()[18])}, $(this).attr("data-address"));
    } );


}

function clearMarkers() {
    setMapOnAll(null);
    markers = [];
}
function setMapOnAll(map) {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(map);
    }
}
function addMarker(location, address) {
    var marker = new google.maps.Marker({
        position: location,
        map: map,
        title: address
    });
    markers.push(marker);
}


<!-- Bootstrap core JavaScript -->
$.fn.digits = function () {
    return this.each(function () {
        $(this).text($(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
    })
}

$.fn.dataTable.ext.search.push(
    function (settings, data, dataIndex) {
        var min = parseInt($('#price_min').val(), 10);
        var max = parseInt($('#price_max').val(), 10);
        var age = parseFloat(data[5].replace(/[^0-9\.-]+/g, "")) || 0; // use data for the age column

        var dom_min = parseInt($('#dom_min').val(), 10);
        var dom_max = parseInt($('#dom_max').val(), 10);
        var dom_age = parseFloat(data[14].replace(/[^0-9\.-]+/g, "")) || 0; // use data for the age column

        var percentage_min = parseInt($('#percentage_min').val(), 10);
        var percentage_max = parseInt($('#percentage_max').val(), 10);
        var percentage_age = parseFloat(data[3].replace("%", "")) || 0; // use data for the age column

        var zip_code = parseInt($('#zip_code').val(), 10);
        var zip_code_age = parseFloat(data[2]) || 0;

        if (( ( isNaN(min) && isNaN(max) ) ||
            ( isNaN(min) && age <= max ) ||
            ( min <= age && isNaN(max) ) ||
            ( min <= age && age <= max ) ) &&

            ( ( isNaN(dom_min) && isNaN(dom_max) ) ||
                ( isNaN(dom_min) && dom_age <= dom_max ) ||
                ( dom_min <= dom_age && isNaN(dom_max) ) ||
                ( dom_min <= dom_age && dom_age <= dom_max ) ) &&

            ( ( isNaN(percentage_min) && isNaN(percentage_max) ) ||
                ( isNaN(percentage_min) && percentage_age <= percentage_max ) ||
                ( percentage_min <= percentage_age && isNaN(percentage_max) ) ||
                ( percentage_min <= percentage_age && percentage_age <= percentage_max ) ) &&

            ( isNaN(zip_code) || zip_code == zip_code_age )


            ) {
            return true;
        }
        return false;
    }
);


$(document).ready(function () {

    var $loading = $('#loader-wrapper').hide();
    $(document)
        .ajaxStart(function () {
            $loading.show();
        })
        .ajaxStop(function () {
            $loading.hide();
        });

    table = $("#list").DataTable({
        fixedHeader: true,
        stateSave: true,
        order: [ 14, 'asc' ],
        paging: true,
        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            $(nRow).addClass("color-"+aData[19]);
        },
        dom: 'Bfrtip',
        buttons: [
            'csv', 'excel', 'pdf', 'print'
        ],
        columnDefs: [
            {
                "targets": [ 17,18,19 ],
                "visible": false
            }
        ],
        initComplete: function (settings, json) {
            if ($.cookie("price_min")) {
                $("#price_min").val($.cookie("price_min"));
                refresh = 1;
            }
            ;

            if ($.cookie("price_max")) {
                $("#price_max").val($.cookie("price_max"));
                refresh = 1;
            }
            ;

            if ($.cookie("dom_min")) {
                $("#dom_min").val($.cookie("dom_min"));
                refresh = 1;
            }
            ;

            if ($.cookie("dom_max")) {
                $("#dom_max").val($.cookie("dom_max"));
                refresh = 1;
            }
            ;

            if ($.cookie("percentage_min")) {
                $("#dom_min").val($.cookie("dom_min"));
                refresh = 1;
            }
            ;

            if ($.cookie("percentage_max")) {
                $("#percentage_max").val($.cookie("percentage_max"));
                refresh = 1;
            }
            ;

            if ($.cookie("zip_code")) {
                $("#zip_code").val($.cookie("zip_code"));
                refresh = 1;
            }
            ;

            $.get("/ajax/get_all_listings", function (data) {

                $.each(data.comps, function (index, value) {

                    var new_row = table.row.add([
                        value.link,
                        value.address,
                        value.PostalCode,
                        value.OfferPriceToListPrice,
                        value.OfferPrice,
                        value.ListPrice,
                        value.BuildingDescription,
                        value.ApproxTotalLivArea,
                        value.BedsTotal,
                        value.BathsTotal,
                        value.Garage,
                        value.PvPool,
                        value.Spa,
                        value.YearBuilt,
                        value.DOM,
                        value.user,
                        value.internal_status,
                        value.lat,
                        value.lng,
                        value.user_id
                    ]);

                });
                table.draw(false);


            }, "json");
            settings.oInstance.api().draw();
        }
    });


    $("#price_min").on("change", function () {
        $.cookie("price_min", $(this).val(), {
            expires: new Date(+new Date() + (4 * 60 * 60 * 1000))
        });
        table.draw();
        clearMarkers();
        showMarkers();
    });

    $("#price_max").on("change", function () {
        $.cookie("price_max", $(this).val(), {
            expires: new Date(+new Date() + (4 * 60 * 60 * 1000))
        });
        table.draw();
        clearMarkers();
        showMarkers();
    });

    $("#dom_min").on("change", function () {
        $.cookie("dom_min", $(this).val(), {
            expires: new Date(+new Date() + (4 * 60 * 60 * 1000))
        });
        table.draw();
        clearMarkers();
        showMarkers();
    });

    $("#dom_max").on("change", function () {
        $.cookie("dom_max", $(this).val(), {
            expires: new Date(+new Date() + (4 * 60 * 60 * 1000))
        });
        table.draw();
        clearMarkers();
        showMarkers();
    });

    $("#percentage_min").on("change", function () {
        $.cookie("percentage_min", $(this).val(), {
            expires: new Date(+new Date() + (4 * 60 * 60 * 1000))
        });
        table.draw();
        clearMarkers();
        showMarkers();
    });

    $("#percentage_max").on("change", function () {
        $.cookie("percentage_max", $(this).val(), {
            expires: new Date(+new Date() + (4 * 60 * 60 * 1000))
        });
        table.draw();
        clearMarkers();
        showMarkers();
    });

    $("#zip_code").on("change", function () {
        $.cookie("zip_code", $(this).val(), {
            expires: new Date(+new Date() + (4 * 60 * 60 * 1000))
        });
        table.draw();
        clearMarkers();
        showMarkers();
    });

    $("#clear_filters").on("click", function (e) {
        e.preventDefault();
        $.removeCookie("price_min",{ path: '/users/home' });
        $.removeCookie("price_max",{ path: '/users/home' });
        $.removeCookie("percentage_min",{ path: '/users/home' });
        $.removeCookie("percentage_max",{ path: '/users/home' });
        $.removeCookie("dom_min",{ path: '/users/home' });
        $.removeCookie("dom_max",{ path: '/users/home' });
        $.removeCookie("zip_code",{ path: '/users/home' });
        $("#price_min").val("");
        $("#price_max").val("");
        $("#percentage_min").val("");
        $("#percentage_max").val("");
        $("#dom_min").val("");
        $("#dom_max").val("");
        $("#zip_code").val("");
        table.draw();
        clearMarkers();

    });

    $("#clear_map").on("click", function (e) {
        e.preventDefault();
        $.removeCookie("price_min",{ path: '/users/home' });
        $.removeCookie("price_max",{ path: '/users/home' });
        $.removeCookie("percentage_min",{ path: '/users/home' });
        $.removeCookie("percentage_max",{ path: '/users/home' });
        $.removeCookie("dom_min",{ path: '/users/home' });
        $.removeCookie("dom_max",{ path: '/users/home' });
        $.removeCookie("zip_code",{ path: '/users/home' });
        $("#price_min").val("");
        $("#price_max").val("");
        $("#percentage_min").val("");
        $("#percentage_max").val("");
        $("#dom_min").val("");
        $("#dom_max").val("");
        $("#zip_code").val("");
        table.clear();
        clear_overlays();
        clearMarkers();
        $.get("/ajax/get_all_listings", function (data) {

            $.each(data.comps, function (index, value) {

                var new_row = table.row.add([
                    value.link,
                    value.address,
                    value.PostalCode,
                    value.OfferPriceToListPrice,
                    value.OfferPrice,
                    value.ListPrice,
                    value.BuildingDescription,
                    value.ApproxTotalLivArea,
                    value.BedsTotal,
                    value.BathsTotal,
                    value.Garage,
                    value.PvPool,
                    value.Spa,
                    value.YearBuilt,
                    value.DOM,
                    value.user,
                    value.internal_status,
                    value.lat,
                    value.lng,
                    value.user_id
                ]);

            });

            table.draw(false);

        }, "json");
    });

});
