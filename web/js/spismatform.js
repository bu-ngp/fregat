function checkMaterialsCount() {
    if ($("#spismat-id_mol").val() && parseDateControl("spismat-period_beg") && parseDateControl("spismat-period_end")
        && $("#spismat-id_mol").attr("aria-invalid", false) && $("#spismat-period_beg").attr("aria-invalid", false) && $("#spismat-period_end").attr("aria-invalid", false)
    ) {
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
                    $("#spismat_alert").show();
                    //  $('#Spismatform').yiiActiveForm('updateMessages', {}, true);

                    //   $('#Spismatform').yiiActiveForm('updateAttribute', 'spismat-period_beg', '');
                    //   $('#Spismatform').yiiActiveForm('updateAttribute', 'spismat-period_end', '');
                    if (obj.count > 0)
                        spismatCreateDisabled(false);
                    else
                        spismatCreateDisabled(true, false);
                } else
                    spismatCreateDisabled(true);
            },
            error: function (err) {
                spismatCreateDisabled(true);
            }
        });
    }
}

$('#Spismatform').on('afterValidateAttribute', function (e, attr, msg) {
    /* console.debug(e)
     console.debug(attr)
     console.debug(msg)*/
    if (msg.length === 0) {
        //  $('#Spismatform').yiiActiveForm('updateAttribute', 'spismat-period_beg', '');
        //  $('#Spismatform').yiiActiveForm('updateAttribute', 'spismat-period_end', '');
        checkMaterialsCount();
    }

    return true;
});

function spismatCreateDisabled(operation, withAlert) {
    if (operation) {
        $("#spismat_create").prop("disabled", true);
        $("#spismat_create").addClass("disabled");
        $("#spismat_alert").hide();
    } else {
        $("#spismat_create").prop("disabled", false);
        $("#spismat_create").removeClass("disabled");
        if (withAlert)
            $("#spismat_alert").show();
    }
}

function parseDateControl(id) {
    str = $("#" + id).prev("div").children("input").val();
    if (typeof str !== "undefined")
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
/* not used*/
function DownloadInstallakts(url, button, dopparams, removefile) {

    if ($.type(removefile) === "undefined")
        removefile = true;

    $.ajax({
        url: url,
        type: "post",
        data: {
            buttonloadingid: button,
            dopparams: JSON.stringify(dopparams)
        }, /* buttonloadingid - id кнопки, для дизактивации кнопки во время выполнения запроса */
        async: true,
        success: function (response) {
            /* response - Путь к новому файлу  */
            window.location.href = baseUrl + "files/" + response;
            /* Открываем файл */
            /* Удаляем файл через 5 секунд*/
            if (removefile)
                setTimeout(function () {
                    $.ajax({
                        url: baseUrl + "site/delete-excel-file",
                        type: "post",
                        data: {filename: response},
                        async: true
                    });
                }, 5000);
        },
        error: function (data) {
            console.error('Ошибка');
        }
    });
}

$(document).ready(function () {
    checkMaterialsCount();
});