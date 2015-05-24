$("#username_search").on("keyup", function() {
    var value = $(this).val();
    value = value.turkishToLower();

    $("table tr").each(function(index) {
        if (index > 1) {
            $row = $(this);
            var id = $row.find("td:first").text();
            id = id.turkishToLower();
            if (id.indexOf(value) !== 0) {
                $row.hide();
            }else {
                $row.show();
            }
        }
    });
});

$("#name_search").on("keyup", function() {
    var value = $(this).val();
    value = value.turkishToLower();

    $("table tr").each(function(index) {
        if (index > 1) {
            $row = $(this);
            var id = $row.find("td:nth-child(2)").text();
            id = id.turkishToLower();
            if (id.indexOf(value) !== 0) {
                $row.hide();
            }else {
                $row.show();
            }
        }
    });
});

$("#email_search").on("keyup", function() {
    var value = $(this).val();
    value = value.turkishToLower();

    $("table tr").each(function(index) {
        if (index > 1) {
            $row = $(this);
            var id = $row.find("td:nth-child(3)").text();
            id = id.turkishToLower();
            if (id.indexOf(value) !== 0) {
                $row.hide();
            }else {
                $row.show();
            }
        }
    });
});