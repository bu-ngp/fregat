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
        })
        wall.fitWidth();
    });
});

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
