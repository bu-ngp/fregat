$(function () {
    $(".menublock").each(function () {
        var wall = new Freewall(this);
        wall.reset({
            animate: true,
            delay: 100,
            selector: '.menubutton',
            cellW: 320,
            cellH: 100,
            gutterY: 10,
            gutterX: 20,
            onComplete: function () {
                $(".menubutton").css("display", "block");
                $(".menubutton").attr("data-state", "");
            },
        })
        wall.fitWidth();
    });
});

/* Главное меню системы */

$("#mb_fregat").click(function () {
    window.location.href = baseUrl + "Fregat/fregat/mainmenu";
});

$("#mb_glauk").click(function () {
    window.location.href = baseUrl + "Base/patient/glaukindex";
});

$("#mb_config").click(function () {
    window.location.href = baseUrl + "Config/config/index";
});

$("#mb_changepassword").click(function () {
    window.location.href = baseUrl + "Config/authuser/change-self-password";
});

/* Настройки портала */

$("#mb_usermanager").click(function () {
    window.location.href = baseUrl + "Config/authuser/index";
});

$("#mb_rolemanager").click(function () {
    window.location.href = baseUrl + "Config/authitem/index";
});

$("#mb_configuration").click(function () {
    window.location.href = baseUrl + "Config/config/configuration";
});

/* Система "Фрегат" */

$("#mb_prihod_j").click(function () {
    window.location.href = baseUrl + "Fregat/material/index";
});

$("#mb_install_j").click(function () {
    window.location.href = baseUrl + "Fregat/installakt/index";
});

$("#mb_remove_j").click(function () {
    window.location.href = baseUrl + "Fregat/removeakt/index";
});

$("#mb_osmotr_j").click(function () {
    window.location.href = baseUrl + "Fregat/osmotrakt/index";
});

$("#mb_osmotrmat_j").click(function () {
    window.location.href = baseUrl + "Fregat/osmotraktmat/index";
});

$("#mb_recovery_j").click(function () {
    window.location.href = baseUrl + "Fregat/recoverysendakt/index";
});

$("#mb_spisosnov_j").click(function () {
    window.location.href = baseUrl + "Fregat/spisosnovakt/index";
});

$("#mb_naklad_j").click(function () {
    window.location.href = baseUrl + "Fregat/naklad/index";
});

$("#mb_prihod_new").click(function () {
    window.location.href = baseUrl + "Fregat/material/create";
});

$("#mb_install_new").click(function () {
    window.location.href = baseUrl + "Fregat/installakt/create";
});

$("#mb_osmotr_new").click(function () {
    window.location.href = baseUrl + "Fregat/osmotrakt/create";
});

$("#mb_recovery_new").click(function () {
    window.location.href = baseUrl + "Fregat/recoverysendakt/create";
});

$("#mb_importdata").click(function () {
    window.location.href = baseUrl + "Fregat/fregat/import";
});

$("#mb_sprav").click(function () {
    window.location.href = baseUrl + "Fregat/fregat/sprav";
});


$("#mb_fregatoptions").click(function () {
    window.location.href = baseUrl + "Fregat/fregat/options";
});

/* Регистр глаукомных пациентов \ Настройки регистра глаукомных пациентов */

$("#mb_glauksprav").click(function () {
    window.location.href = baseUrl + "Fregat/fregat/sprav";
});

/* Справочники */

$("#mb_sp_matvid").click(function () {
    window.location.href = baseUrl + "Fregat/matvid/index";
});

$("#mb_sp_grupa").click(function () {
    window.location.href = baseUrl + "Fregat/grupa/index";
});

$("#mb_sp_organ").click(function () {
    window.location.href = baseUrl + "Fregat/organ/index";
});

$("#mb_sp_reason").click(function () {
    window.location.href = baseUrl + "Fregat/reason/index";
});

$("#mb_sp_schetuchet").click(function () {
    window.location.href = baseUrl + "Fregat/schetuchet/index";
});

$("#mb_sp_docfiles").click(function () {
    window.location.href = baseUrl + "Fregat/docfiles/index";
});

$("#mb_sp_employee").click(function () {
    window.location.href = baseUrl + "Config/authuser/index?emp=1";
});

$("#mb_sp_dolzh").click(function () {
    window.location.href = baseUrl + "Fregat/dolzh/index";
});

$("#mb_sp_podraz").click(function () {
    window.location.href = baseUrl + "Fregat/podraz/index";
});

$("#mb_sp_build").click(function () {
    window.location.href = baseUrl + "Fregat/build/index";
});

$("#mb_sp_preparat").click(function () {
    window.location.href = baseUrl + "Base/preparat/index";
});

$("#mb_sp_classmkb").click(function () {
    window.location.href = baseUrl + "Base/classmkb/index";
});

/* Система "Фрегат" \ Настройки системы "Фрегат" */

$("#mb_fregatimport").click(function () {
    window.location.href = baseUrl + "Fregat/fregat/import";
});

$("#mb_fregatsprav").click(function () {
    window.location.href = baseUrl + "Fregat/fregat/sprav";
});

$("#mb_fregatconfig").click(function () {
    window.location.href = baseUrl + "Fregat/fregat/settings";
});

/* Система "Фрегат" \ Настройки системы "Фрегат" \ Импорт данных */

$("#mb_fregatimp_conf_employee").click(function () {
    window.location.href = baseUrl + "Fregat/importemployee/index";
});

$("#mb_fregatimp_conf_material").click(function () {
    window.location.href = baseUrl + "Fregat/importmaterial/index";
});

$("#mb_fregatimp_reports").click(function () {
    window.location.href = baseUrl + "Fregat/logreport/index";
});

$("#mb_fregatimp_conf").click(function () {
    window.location.href = baseUrl + "Fregat/importconfig/update";
});