var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};

function ChooseItemGrid(url, targetelement, fromgrid) {
    if (url !== undefined && targetelement !== undefined && fromgrid !== undefined) {
        console.debug(url)
        console.debug(targetelement)
        console.debug(fromgrid)

        // js script

        var $grid = $('#' + fromgrid);

        console.debug($('input:radio[name = "' + fromgrid + '_check"]:checked').val())

        var param = {
            selectelement: targetelement,
            idvalue: $('input:radio[name = "' + fromgrid + '_check"]:checked').val()
        };

        /* $grid.on('grid.radiochecked', function (ev, key, val) {
         console.debug("Key = " + key + ", Val = " + val);
         });*/

// keys is an array consisting of the keys associated with the selected rows

        window.location.href = url + '?' + $.param(param);
    } else
        console.error("Не переданы обязательные параметры в ChooseItemGrid");

}

function SetSession(thiselem) {
    var field = $(thiselem).attr("name");
    
    $.ajax({
        url: "?r=site%2Fsetsession",
        type: "post",
        data: {modelclass: field.substring(0, field.indexOf("[")), field: field.substring(field.indexOf("[") + 1, field.indexOf("]")), value: $(thiselem).val()},
        async: false,
        error: function (data) {
            console.error("Ошибка SetSession");
        }
    });
}

$(function () {
    $("input.form-control").focusout(function () {
        SetSession(this);
    });

    $("select.form-control").change(function () {
        SetSession(this);
    });
});