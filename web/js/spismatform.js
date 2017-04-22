function checkMaterialsCount() {
    if ($("#spismat-id_mol").val() && parseDateControl("spismat-period_beg") && parseDateControl("spismat-period_end")) {

        $.ajax({
            url: baseUrl + "Fregat/spismat/check-materials",
            type: "post",
            data: {
                params: JSON.stringify({
                    id_mol: $("#spismat-id_mol").val(),
                    period_beg: parseDateControl("spismat-period_beg"),
                    period_end: parseDateControl("spismat-period_end"),
                    spisinclude: $("#spismat-spismat_spisinclude")
                })
            },
            success: function (data) {
                if (data != "") {
                    var obj = JSON.parse(data);

                    $("#spismat_alert").html("<strong>Доступно материалов для добавления: </strong>" + obj.count);
                }
                spismatCreateDisabled(false);
            },
            error: function (err) {
                spismatCreateDisabled(true);
            }
        });
    }

}

function spismatCreateDisabled(operation) {
    if (operation) {
        $("#spismat_create").prop("disabled", true);
        $("#spismat_create").addClass("disabled");
        $("#spismat_alert").hide();
    } else {
        $("#spismat_create").prop("disabled", false);
        $("#spismat_create").removeClass("disabled");
        $("#spismat_alert").show();
    }
}

function parseDateControl(id) {
    str = $("#" + id).prev("div").children("input").val();
    return str.replace(/^(\d{1,2})\.(\d{1,2})\.(\d{4})$/, "$3-$2-$1");
}

function AddMattraffic(spismat_id) {
    if ($("#mattraffic-mattraffic_id").val() != "" && $("#mattraffic-mattraffic_id").val() != null) {
        $.ajax({
            url: baseUrl + "Fregat/spismatmaterials/addmattraffic",
            type: "post",
            data: {id_mattraffic: $("#mattraffic-mattraffic_id").val(), id_spismat: spismat_id},
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj.status) {
                    $('select[name="Mattraffic[mattraffic_id]"]').select2('val', '');
                    $("#spismatmaterialsgrid_gw").yiiGridView("applyFilter");
                }
            },
            error: function (data) {
                console.error("Ошибка AddMattraffic()");
            }
        });
    } else
        bootbox.alert("Необходимо ввести инвентарный номер материальной ценности или ее наименование в поле \"Для быстрого добавления материальных ценностей\"!");
}