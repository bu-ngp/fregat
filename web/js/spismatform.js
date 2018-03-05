function checkMaterialsCount() {
    if ($("#spismat-id_mol").val()
        && parseDateControl("spismat-period_beg")
        && parseDateControl("spismat-period_end")
    ) {
        $.ajax({
            url: baseUrl + "Fregat/spismat/check-materials",
            type: "post",
            data: {
                params: JSON.stringify({
                    id_mol: $("#spismat-id_mol").val(),
                    period_beg: parseDateControl("spismat-period_beg"),
                    period_end: parseDateControl("spismat-period_end"),
                    spisinclude: $("#spismat-spismat_spisinclude").val()
                })
            },
            success: function (obj) {
                if (obj != "") {

                    $("#spismat_alert").html("<strong>Доступно материалов для добавления: </strong>" + obj.count);
                    $("#spismat_alert").show();

                    if (obj.count > 0)
                        spismatCreateDisabled(false);
                    else
                        spismatCreateDisabled(true, true);
                } else
                    spismatCreateDisabled(true);
            },
            error: function (err) {
                spismatCreateDisabled(true);
            }
        });
    }
}

$('#Spismatform').on('ajaxComplete', function (e, response, msg) {
    if (response.responseJSON.length === 0) {
        $('#Spismatform').yiiActiveForm('updateAttribute', 'spismat-period_beg', '');
        $('#Spismatform').yiiActiveForm('updateAttribute', 'spismat-period_end', '');
        checkMaterialsCount();
    } else {
        spismatCreateDisabled(true);
    }

    return true;
});

function clearDatePicker() {
    spismatCreateDisabled(true);
    $('#Spismatform').yiiActiveForm('validateAttribute', 'spismat-period_beg');
    $('#Spismatform').yiiActiveForm('validateAttribute', 'spismat-period_end');
}

function spismatCreateDisabled(operation, withAlert) {
    if (operation) {
        $("#spismat_create").prop("disabled", true);
        $("#spismat_create").addClass("disabled");
        $("#spismat_alert").hide();
    } else {
        $("#spismat_create").prop("disabled", false);
        $("#spismat_create").removeClass("disabled");
    }

    if (typeof withAlert !== "undefined") {
        if (withAlert == true)
            $("#spismat_alert").show();
        else
            $("#spismat_alert").hide();
    }
}

function parseDateControl(id) {
    str = $("#" + id).prev("div").children("input").val();
    if (typeof str !== "undefined")
        return str.replace(/^(\d{1,2})\.(\d{1,2})\.(\d{4})$/, "$3-$2-$1");
}

$(document).ready(function () {
    checkMaterialsCount();
});