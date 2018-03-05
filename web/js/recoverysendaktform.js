function AddOsmotrakt(recoverysendakt_id) {
    if ($("#osmotrakt-osmotrakt_id").val() != "" && $("#osmotrakt-osmotrakt_id").val() != null) {
        $.ajax({
            url: baseUrl + "Fregat/recoveryrecieveakt/addosmotrakt",
            type: "post",
            data: {id_osmotrakt: $("#osmotrakt-osmotrakt_id").val(), id_recoverysendakt: recoverysendakt_id},
            success: function (obj) {
                if (obj.status) {
                    $('select[name="Osmotrakt[osmotrakt_id]"]').select2('val', '');
                    $("#recoveryrecieveaktgrid_gw").yiiGridView("applyFilter");
                }
            },
            error: function (data) {
                console.error("Ошибка AddOsmotrakt()");
            }
        });
    } else
        bootbox.alert("Необходимо ввести инвентарный номер материальной ценности в поле \"Для быстрого добавления материальных ценностей\"!");
}