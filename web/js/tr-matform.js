function SetMaxNumberByMaterial() {
    if ($("#trmat-id_mattraffic").val() == "") {
        $("#mattraffic_number_max").text('');
    } else {
        $.ajax({
            url: baseUrl + "Fregat/tr-mat/max-number-material-by-mol",
            type: "post",
            data: {mattraffic_id: $("#trmat-id_mattraffic").val()},
            success: function (obj) {
                $("#mattraffic_number_max").text("Не более " + obj.mattraffic_number);
            },
            error: function (data) {
                console.error("Ошибка SetTipMaterial()");
            }
        });
    }
}

function UnSetMaxNumberByMaterial() {
    $("#mattraffic_number_max").text('');
}

function SetParentMaterial() {
    $("#trmat-id_mattraffic").val(null).trigger("change");
    $("#trmat-id_mattraffic").prop("disabled", false);
    $("#trmat-id_mattraffic").nextAll("div.input-group-btn").children("a").removeClass("disabled");

    $("#trmat-id_mattraffic").nextAll("div.input-group-btn").children("a").attr('href', function (i, a) {
        return a.match(/(id_parent=)\d+/) ? a.replace(/(id_parent=)\d+/, '$1' + $("#trmat-id_parent").val()) : a + "&id_parent=" + $("#trmat-id_parent").val();
    });
}

function UnSetParentMaterial() {
    $("#trmat-id_mattraffic").prop("disabled", true);
    $("#trmat-id_mattraffic").nextAll("div.input-group-btn").children("a").addClass("disabled");
    $("#trmat-id_mattraffic").val(null).trigger("change");
    $("#mattraffic_number_max").text('');
}

$(document).ready(function () {
    SetMaxNumberByMaterial();
});