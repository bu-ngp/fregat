function SetTipMaterial() {
    if ($("#material-material_tip").val() == "1") {
        $("#material-material_number").prop("disabled", true);
    } else if ($("#material-material_tip").val() == "2") {
        $("#material-material_number").prop("disabled", false);
    }
}

function UnsetTipMaterial() {
    $("#material-material_number").prop("disabled", false);
}

$(document).ready(function () {
    SetTipMaterial();
});