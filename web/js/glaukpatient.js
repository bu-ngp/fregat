function FillCity() {
    $('select[name="Patient[id_fias]"]').val('');
    $.ajax({
        url: "?r=Base%2Ffias%2Fcheckstreets",
        type: "post",
        data: {city_AOGUID: $('select[name="Fias[AOGUID]"]').val()},
        success: function (data) {
            $('select[name="Patient[id_fias]"]').prop("disabled", data == '0');
        },
        error: function (data) {
            console.error("Ошибка FillCity()");
        }
    });
}

function ClearCity() {
    $('select[name="Patient[id_fias]"]').val('');
    $('select[name="Patient[id_fias]"]').prop("disabled", true);
}