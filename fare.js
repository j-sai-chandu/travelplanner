var a = document.getElementById("Pickup"),
    b = document.getElementById("Dropoff"),
    options = {
        types: ["geocode"],
        componentRestrictions: {
            country: "au"
        }
    },
    autocomplete = new google.maps.places.Autocomplete(a, options),
    autocomplete = new google.maps.places.Autocomplete(b, options);
$("#GetQuote").on("click", function() {
    var e = $.trim($("#Pickup").val()),
        t = $.trim($("#Dropoff").val()),
        r = $.trim($("#riders").val());
    if (0 == r) $(".result").removeClass("error").html("Please enter Pickup, Dropoff locations  &amp; number of riders to get a quote.");
    else if (e.length > 1 && t.length > 1) {
        $(".result").removeClass("error").html("Please wait while we are processing your request.");
        var o = $("#Pickup").val().replace(/ /g, "+"),
            s = $("#Dropoff").val().replace(/ /g, "+"),
            i = new google.maps.DistanceMatrixService;
        i.getDistanceMatrix({
            origins: [o, s],
            destinations: [o, s],
            travelMode: google.maps.TravelMode.DRIVING,
            unitSystem: google.maps.UnitSystem.METRIC,
            durationInTraffic: !0,
            avoidHighways: !1,
            avoidTolls: !1
        }, function(e, t) {
            if (t !== google.maps.DistanceMatrixStatus.OK) console.log("Error:", t);
            else {
                var r = parseInt(e.rows[1].elements[0].distance.text.replace(" km", "")),
                    o = r.toFixed(1),
                    s = e.rows[1].elements[0].duration.text,
                    i = 1.5,
                    n = (o * i).toFixed(2),
                    a = "Distance: " + o + " Km | Price: <strong>$" + n + "</strong> | Estimated Driving Duration: " + s;
                if (5 >= o) {
                    var l = new Array(0, 0, 5, 10, 15);
                    $(function() {
                        $("select[name=riders]").change(function() {
                            priceRiders = l[$(this).val()]
                        }), $("select[name=riders]").change()
                    });
                    var n = 15 + priceRiders,
                        a = "Distance: " + o + " Km | Price: <strong>$" + n + "</strong> | Estimated Driving Duration: " + s;
                    setTimeout(function() {
                        $(".result").removeClass("error").html(a)
                    }, 3e3), document.getElementById("price").value = n
                } else {
                    var l = new Array(0, 0, 5, 10, 15);
                    $(function() {
                        $("select[name=riders]").change(function() {
                            priceRiders = l[$(this).val()]
                        }), $("select[name=riders]").change()
                    });
                    var c = 15,
                        n = ((o - 5) * i + c + priceRiders).toFixed(2),
                        a = "Distance: <strong>" + o + " Km </strong> | Price: <strong>$" + n + "</strong> | Estimated Driving Duration: <strong>" + s + "</strong>";
                    setTimeout(function() {
                        $(".result").removeClass("error").html(a)
                    }, 3e3), document.getElementById("price").value = n
                }
            }
        })
    } else $(".result").addClass("error").html("Please enter Pickup, Dropoff locations  &amp; number of riders to get a quote.")
});