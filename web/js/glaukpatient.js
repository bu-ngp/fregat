function InitAddress() {
    if (!$('select[name="Fias[AOGUID]"]').prop("disabled"))
        $.ajax({
            url: "?r=Base%2Ffias%2Fcheckstreets",
            type: "post",
            data: {city_AOGUID: $('select[name="Fias[AOGUID]"]').val()},
            success: function (data) {
                $('select[name="Patient[id_fias]"]').prop("disabled", !(data && data != '0'));

                if (data && data != '0')
                    $('div.form-group.field-patient-id_fias').addClass("required");
                else
                    $('div.form-group.field-patient-id_fias').removeClass("required");

                if (data || data == '0') {
                    $('div.form-group.field-patient-patient_dom').addClass("required");
                    $('div.form-group.field-patient-patient_kvartira').addClass("required");

                } else {
                    $('div.form-group.field-patient-patient_dom').removeClass("required");
                    $('div.form-group.field-patient-patient_kvartira').removeClass("required");
                }

                $('input[name="Patient[patient_dom]').prop("disabled", !(data || data == '0'));
                $('input[name="Patient[patient_korp]').prop("disabled", !(data || data == '0'));
                $('input[name="Patient[patient_kvartira]').prop("disabled", !(data || data == '0'));

            },
            error: function (data) {
                console.error("Ошибка InitAddress()");
            }
        });
}

function FillCity() {
    $('select[name="Patient[id_fias]"]').select2('val', '');
    InitAddress();
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

    $('input[name="Patient[patient_dom]').prop("disabled", true);
    $('input[name="Patient[patient_korp]').prop("disabled", true);
    $('input[name="Patient[patient_kvartira]').prop("disabled", true);

    SetSessionEach([
        $('select[name="Patient[id_fias]"]'),
        $('input[name="Patient[patient_dom]'),
        $('input[name="Patient[patient_korp]'),
        $('input[name="Patient[patient_kvartira]')
    ]);
}

$(document).ready(function () {
    InitAddress();
})