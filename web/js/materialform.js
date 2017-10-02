function SetTipMaterial() {
    if (["1", "4", "6"].indexOf($("#material-material_tip").val()) >= 0) {
        $("#material-material_number").prop("disabled", true);
    }

    if (["2", "3", "5"].indexOf($("#material-material_tip").val()) >= 0) {
        $("#material-material_number").prop("disabled", false);
    }

    if (["6"].indexOf($("#material-material_tip").val()) >= 0) {
        $("#material-material_inv").prop("disabled", true);
    }

    if (["1", "2", "3", "4", "5"].indexOf($("#material-material_tip").val()) >= 0) {
        $("#material-material_inv").prop("disabled", false);
    }
}

function UnsetTipMaterial() {
    $("#material-material_number").prop("disabled", false);
}

$(document).ready(function () {
    SetTipMaterial();
});