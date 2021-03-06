function FillInstaledMat() {
    if ($("#osmotrakt-id_tr_osnov").val() != "undefined") {
        $.ajax({
            url: baseUrl + "Fregat/tr-osnov/fillinstalledmat",
            type: "post",
            data: {id_tr_osnov: $("#osmotrakt-id_tr_osnov").val()},
            success: function (obj) {
                if (obj != "") {
                    $("#material-material_name").val(obj.material_name);
                    $("#material-material_inv").val(obj.material_inv);
                    $("#material-material_serial").val(obj.material_serial);
                    $("#build-build_name").val(obj.build_name);
                    $("#cabinet-cabinet_name").val(obj.cabinet_name);
                    $("#authuser-auth_user_fullname").val(obj.auth_user_fullname);
                    $("#dolzh-dolzh_name").val(obj.dolzh_name);
                }
            },
            error: function (data) {
                console.error("Ошибка FillInstaledMat()");
            }
        });
    }
}

function ClearInstaledMat() {
    $("#material-material_name").val('');
    $("#material-material_inv").val('');
    $("#material-material_serial").val('');
    $("#build-build_name").val('');
    $("#cabinet-cabinet_name").val('');
    $("#authuser-auth_user_fullname").val('');
    $("#dolzh-dolzh_name").val('');
}

function FillNewinstallakt() {
    if ($("#installtrosnov-id_mattraffic").val() != "") {
        $.ajax({
            url: baseUrl + "Fregat/osmotrakt/fillnewinstallakt",
            type: "post",
            data: {id_mattraffic: $("#installtrosnov-id_mattraffic").val()},
            success: function (obj) {
                if (obj != "") {

                    if (obj.build_name) {
                        $("#installtrosnov-id_cabinet").prop("disabled", false);
                        $("#installtrosnov-id_cabinet").next("span").next(".input-group-btn").find("a").removeClass("disabled");

                        var href = $("#installtrosnov-id_cabinet").next("span").next(".input-group-btn").find("a").attr("href");
                        href = href.match(/(&id_mattraffic=.*)/)
                            ? href.replace(/(&id_mattraffic=.*)/, '&id_mattraffic=' + $("#installtrosnov-id_mattraffic").val())
                            : href + '&id_mattraffic=' + $("#installtrosnov-id_mattraffic").val();
                        $("#installtrosnov-id_cabinet").next("span").next(".input-group-btn").find("a").attr("href", href);
                    } else {
                        $("#installtrosnov-id_cabinet").prop("disabled", true);
                        $("#installtrosnov-id_cabinet").next("span").next(".input-group-btn").find("a").addClass("disabled");
                    }

                    $("#material-material_id").val(obj.material_id);
                    $("#material-material_name.newinstallakt").val(obj.material_name);
                    $("#material-material_writeoff.newinstallakt").val(obj.material_writeoff);
                    $("#authuser-auth_user_fullname.newinstallakt").val(obj.auth_user_fullname);
                    $("#dolzh-dolzh_name.newinstallakt").val(obj.dolzh_name);
                    $("#build-build_name.newinstallakt").val(obj.build_name);
                    $("#mattraffic-mattraffic_number.newinstallakt").val(obj.mattraffic_number);
                }
            },
            error: function (data) {
                console.error("Ошибка FillNewinstallakt()");
            }
        });
    }
}

function ClearNewinstallakt() {
    $("#installtrosnov-id_cabinet").val('').trigger('change');
    $("#installtrosnov-id_cabinet").prop("disabled", true);
    $("#installtrosnov-id_cabinet").next("span").next(".input-group-btn").find("a").addClass("disabled");
    $("#material-material_id").val('');
    $("#material-material_name.newinstallakt").val('');
    $("#material-material_writeoff.newinstallakt").val('');
    $("#authuser-auth_user_fullname.newinstallakt").val('');
    $("#dolzh-dolzh_name.newinstallakt").val('');
    $("#build-build_name.newinstallakt").val('');
    $("#mattraffic-mattraffic_number.newinstallakt").val('');
}

function RedirectToChangeMol() {
    if ($("#material-material_id").val() == "")
        bootbox.alert("Выберете материальную ценность, для которой будет изменено материально-ответственное лицо.");
    else
        window.location.href = baseUrl + "Fregat/mattraffic/create?id=" + $("#material-material_id").val();
}

$(document).ready(function () {
    FillNewinstallakt();
});