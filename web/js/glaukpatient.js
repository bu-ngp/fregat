function FillCity() {
    $('select[name="Patient[id_fias]"]').select2('val', '');
    $.ajax({
        url: "?r=Base%2Ffias%2Fcheckstreets",
        type: "post",
        data: {city_AOGUID: $('select[name="Fias[AOGUID]"]').val()},
        success: function (data) {
            $('select[name="Patient[id_fias]"]').prop("disabled", data == '0');
            if (data == '0')
                $('div.form-group.field-patient-id_fias').removeClass("required");
            else
                $('div.form-group.field-patient-id_fias').addClass("required");

            $('div.form-group.field-patient-patient_dom').addClass("required");
            $('div.form-group.field-patient-patient_kvartira').addClass("required");
        },
        error: function (data) {
            console.error("Ошибка FillCity()");
        }
    });
}

function ClearCity() {
    $('select[name="Patient[id_fias]"]').prop("disabled", true);
    $('div.form-group.field-patient-id_fias').removeClass("required has-success has-error").children("p").html("");
    $('select[name="Patient[id_fias]"]').select2('val', '');

    $('div.form-group.field-patient-patient_dom').removeClass("required has-success has-error").children("p").html("");
    $('input[name="Patient[patient_dom]').val("");

    $('div.form-group.field-patient-patient_korp').removeClass("has-success has-error").children("p").html("");
    $('input[name="Patient[patient_korp]').val("");

    $('input[name="Patient[patient_kvartira]').val("");
    $('div.form-group.field-patient-patient_kvartira').removeClass("required has-success has-error").children("p").html("");

    SetSessionEach([
        $('select[name="Patient[id_fias]"]'),
        $('input[name="Patient[patient_dom]'),
        $('input[name="Patient[patient_korp]'),
        $('input[name="Patient[patient_kvartira]')
    ]);
}