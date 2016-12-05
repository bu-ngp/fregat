function SetMaxNumberByMaterial() {
    if ($("#trmat-id_mattraffic").val() == "") {
        $("#mattraffic_number_max").text('');
    } else {
        $.ajax({
            url: baseUrl + "Fregat/tr-mat/max-number-material-by-mol",
            type: "post",
            data: {mattraffic_id: $("#trmat-id_mattraffic").val()},
            success: function (data) {
                var obj = JSON.parse(data);
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

$(document).ready(function () {
    SetMaxNumberByMaterial();
});