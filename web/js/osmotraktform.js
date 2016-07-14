function FillInstaledMat() {
    if ($("#osmotrakt-id_tr_osnov").val() != "undefined") {
        $.ajax({
            url: "?r=Fregat%2Ftr-osnov%2Ffillinstalledmat",
            type: "post",
            data: {id_tr_osnov: $("#osmotrakt-id_tr_osnov").val()},
            success: function (data) {
                var obj = JSON.parse(data);
                $("#material-material_name").val(obj.material_name);
                $("#material-material_inv").val(obj.material_inv);
                $("#material-material_serial").val(obj.material_serial);
                $("#build-build_name").val(obj.build_name);
                $("#trosnov-tr_osnov_kab").val(obj.tr_osnov_kab);
                $("#authuser-auth_user_fullname").val(obj.auth_user_fullname);
                $("#dolzh-dolzh_name").val(obj.dolzh_name);
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
    $("#trosnov-tr_osnov_kab").val('');
    $("#authuser-auth_user_fullname").val('');
    $("#dolzh-dolzh_name").val('');
}

function FillNewinstallakt() {
    if ($("#trosnov-id_mattraffic").val() != "undefined") {
        $.ajax({
            url: "?r=Fregat%2Fosmotrakt%2Ffillnewinstallakt",
            type: "post",
            data: {id_mattraffic: $("#trosnov-id_mattraffic").val()},
            success: function (data) {
                var obj = JSON.parse(data);
                $("#material-material_name.newinstallakt").val(obj.material_name);
                $("#authuser-auth_user_fullname.newinstallakt").val(obj.auth_user_fullname);
                $("#dolzh-dolzh_name.newinstallakt").val(obj.dolzh_name);
                $("#build-build_name.newinstallakt").val(obj.build_name);
            },
            error: function (data) {
                console.error("Ошибка FillNewinstallakt()");
            }
        });
    }
}

function ClearNewinstallakt() {
    $("#material-material_name.newinstallakt").val('');
    $("#authuser-auth_user_fullname.newinstallakt").val('');
    $("#dolzh-dolzh_name.newinstallakt").val('');
    $("#build-build_name.newinstallakt").val('');
}