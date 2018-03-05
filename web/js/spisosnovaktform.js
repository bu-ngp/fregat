function AddMattraffic(spisosnovakt_id) {
    if ($("#mattraffic-mattraffic_id").val() != "" && $("#mattraffic-mattraffic_id").val() != null) {
        $.ajax({
            url: baseUrl + "Fregat/spisosnovmaterials/addmattraffic",
            type: "post",
            data: {id_mattraffic: $("#mattraffic-mattraffic_id").val(), id_spisosnovakt: spisosnovakt_id},
            success: function (data) {
                if (data.status) {
                    $('select[name="Mattraffic[mattraffic_id]"]').select2('val', '');
                    $("#spisosnovmaterialsgrid_gw").yiiGridView("applyFilter");
                }
            },
            error: function (data) {
                console.error("Ошибка AddMattraffic()");
            }
        });
    } else
        bootbox.alert("Необходимо ввести инвентарный номер материальной ценности в поле \"Для быстрого добавления материальных ценностей\"!");
}