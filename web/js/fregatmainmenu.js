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
    window.location.href = "?r=Fregat%2Ffregat%2Fmainmenu";
});

$("#mb_glauk").click(function () {
    window.location.href = "?r=Base%2Fpatient%2Fglaukindex";
});

$("#mb_config").click(function () {
    window.location.href = "?r=Config%2Fconfig%2Findex";
});

$("#mb_changepassword").click(function () {
    window.location.href = "?r=Config%2Fauthuser%2Fchange-self-password";
});

/* Настройки портала */

$("#mb_usermanager").click(function () {
    window.location.href = "?r=Config%2Fauthuser%2Findex";
});

$("#mb_rolemanager").click(function () {
    window.location.href = "?r=Config%2Fauthitem%2Findex";
});

/* Система "Фрегат" */

$("#mb_prihod_j").click(function () {
    window.location.href = "?r=Fregat%2Fmaterial%2Findex";
});

$("#mb_install_j").click(function () {
    window.location.href = "?r=Fregat%2Finstallakt%2Findex";
});

$("#mb_remove_j").click(function () {
    window.location.href = "?r=Fregat%2Fremoveakt%2Findex";
});

$("#mb_osmotr_j").click(function () {
    window.location.href = "?r=Fregat%2Fosmotrakt%2Findex";
});

$("#mb_osmotrmat_j").click(function () {
    window.location.href = "?r=Fregat%2Fosmotraktmat%2Findex";
});

$("#mb_recovery_j").click(function () {
    window.location.href = "?r=Fregat%2Frecoverysendakt%2Findex";
});

$("#mb_spisosnov_j").click(function () {
    window.location.href = "?r=Fregat%2Fspisosnovakt%2Findex";
});

$("#mb_prihod_new").click(function () {
    window.location.href = "?r=Fregat%2Fmaterial%2Fcreate";
});

$("#mb_install_new").click(function () {
    window.location.href = "?r=Fregat%2Finstallakt%2Fcreate";
});

$("#mb_osmotr_new").click(function () {
    window.location.href = "?r=Fregat%2Fosmotrakt%2Fcreate";
});

$("#mb_recovery_new").click(function () {
    window.location.href = "?r=Fregat%2Frecoverysendakt%2Fcreate";
});

$("#mb_importdata").click(function () {
    window.location.href = "?r=Fregat%2Ffregat%2Fimport";
});

$("#mb_sprav").click(function () {
    window.location.href = "?r=Fregat%2Ffregat%2Fsprav";
});

/* Регистр глаукомных пациентов \ Настройки регистра глаукомных пациентов */

$("#mb_glauksprav").click(function () {
    window.location.href = "?r=Fregat%2Ffregat%2Fsprav";
});

/* Справочники */

$("#mb_sp_matvid").click(function () {
    window.location.href = "?r=Fregat%2Fmatvid%2Findex";
});

$("#mb_sp_grupa").click(function () {
    window.location.href = "?r=Fregat%2Fgrupa%2Findex";
});

$("#mb_sp_organ").click(function () {
    window.location.href = "?r=Fregat%2Forgan%2Findex";
});

$("#mb_sp_reason").click(function () {
    window.location.href = "?r=Fregat%2Freason%2Findex";
});

$("#mb_sp_schetuchet").click(function () {
    window.location.href = "?r=Fregat%2Fschetuchet%2Findex";
});

$("#mb_sp_docfiles").click(function () {
    window.location.href = "?r=Fregat%2Fdocfiles%2Findex";
});

$("#mb_sp_employee").click(function () {
    window.location.href = "?r=Config%2Fauthuser%2Findex&emp=1";
});

$("#mb_sp_dolzh").click(function () {
    window.location.href = "?r=Fregat%2Fdolzh%2Findex";
});

$("#mb_sp_podraz").click(function () {
    window.location.href = "?r=Fregat%2Fpodraz%2Findex";
});

$("#mb_sp_build").click(function () {
    window.location.href = "?r=Fregat%2Fbuild%2Findex";
});

$("#mb_sp_preparat").click(function () {
    window.location.href = "?r=Base%2Fpreparat%2Findex";
});

$("#mb_sp_classmkb").click(function () {
    window.location.href = "?r=Base%2Fclassmkb%2Findex";
});

/* Система "Фрегат" \ Настройки системы "Фрегат" */

$("#mb_fregatimport").click(function () {
    window.location.href = "?r=Fregat%2Ffregat%2Fimport";
});

$("#mb_fregatsprav").click(function () {
    window.location.href = "?r=Fregat%2Ffregat%2Fsprav";
});

$("#mb_fregatconfig").click(function () {
    window.location.href = "?r=Fregat%2Ffregat%2Fsettings";
});

/* Система "Фрегат" \ Настройки системы "Фрегат" \ Импорт данных */

$("#mb_fregatimp_conf_employee").click(function () {
    window.location.href = "?r=Fregat%2Fimportemployee%2Findex";
});

$("#mb_fregatimp_conf_material").click(function () {
    window.location.href = "?r=Fregat%2Fimportmaterial%2Findex";
});

$("#mb_fregatimp_reports").click(function () {
    window.location.href = "?r=Fregat%2Flogreport%2Findex";
});

$("#mb_fregatimp_conf").click(function () {
    window.location.href = "?r=Fregat%2Fimportconfig%2Fupdate";
});