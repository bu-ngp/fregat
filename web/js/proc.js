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

function InitWindowGUID() {
    var guid = Math.floor(Math.random() * 0x10000 /* 65536 */).toString(16);
    window.name = guid;
    $.ajax({
        url: "?r=site%2Fsetwindowguid",
        type: "post",
        data: {guid: guid},
        error: function (data) {
            console.error("Ошибка setWindowGUID");
        }
    });
}

function ExportExcel(model, url, button) {
    var inputarr = $('input[name^="' + model + '"], select[name^="' + model + '"]');
    var inputdata = {};
    if (button == "undefined")
        button = "";

    if (inputarr.length || selectarr.length) {
        inputarr.each(function (index) {
            if ($(this).attr("name") !== "")
                inputdata[$(this).attr("name")] = $(this).val();
        });

        var selectvalues = {};
        $('select[name^="' + model + '"]').each(function () {
            var namekey = $(this).attr("name");
            selectvalues[namekey] = [];
            if ($(this).attr("name") !== "") {
                $(this).children("option").each(function () {
                    if ($(this).val() !== "")
                        selectvalues[namekey][$(this).val()] = $(this).text();
                });
            }
        });

        var data = {inputdata: JSON.stringify(inputdata), selectvalues: JSON.stringify(selectvalues)};

        $.ajax({
            url: url + '&' + $.param(data),
            type: "post",
            data: {buttonloadingid: button}, /* buttonloadingid - id кнопки, для дизактивации кнопки во время выполнения запроса */
            async: true,
            success: function (response) {
                /* response - Путь к новому файлу  */
                window.location.href = "files/" + response; /* Открываем файл */
                /* Удаляем файл через 5 секунд*/
                setTimeout(function () {
                    $.ajax({
                        url: "?r=site%2Fdelete-excel-file",
                        type: "post",
                        data: {filename: response},
                        async: true
                    });
                }, 5000);
            },
            error: function (data) {
                console.error('Ошибка');
            }
        });


    }
}

/**
 * Показываем индикатор ожидания на кнопке
 * 
 * param.buttonelem - Кнопка, над которой проводятся манипуляции
 * 
 */
function LoadingButtonShow(param) {
    if (typeof param !== "undefined" && ("buttonelem" in param)) {
        param.buttonelem.attr("disabled", true);
        param.buttonelem.html('<img src="images/progress.gif">');
    }
}

/**
 * Скрываем индикатор ожидания на кнопке
 * 
 * param.buttonelem - Кнопка, над которой проводятся манипуляции
 * param.text - Текст кнопки, который был до показа индикатора
 */
function LoadingButtonHide(param) {
    if (typeof param !== "undefined" && ("buttonelem" in param)) {
        param.buttonelem.attr("disabled", false);
        if (("text" in param))
            param.buttonelem.html(param.text);
    }
}

/* Событие до выполнения ajax запроса, изменить состояние кнопки на ожидание */
$(document).ajaxSend(function (event, xhr, settings) {
    if (("data" in settings) && Object.prototype.toString.call(settings.data) == '[object String]' && settings.data.indexOf('buttonloadingid') >= 0 && settings.data.match(/buttonloadingid=(\w+)(&|$)/i) !== null) {
        var buttonloadingid = "#" + settings.data.match(/buttonloadingid=(\w+)(&|$)/i)[1];
        if ($(buttonloadingid).length) {
            $(buttonloadingid)[0].label = $(buttonloadingid).html();
            LoadingButtonShow({buttonelem: $(buttonloadingid)});
        }
    }
});

/*  Событие после выполнения ajax запроса, изменить состояние кнопки из ожидания в обычное */
$(document).ajaxComplete(function (event, xhr, settings) {
    if (("data" in settings) && Object.prototype.toString.call(settings.data) == '[object String]' && settings.data.indexOf('buttonloadingid') >= 0 && settings.data.match(/buttonloadingid=(\w+)(&|$)/i) !== null) {
        var buttonloadingid = "#" + settings.data.match(/buttonloadingid=(\w+)(&|$)/i)[1];
        if ($(buttonloadingid).length) {
            if ($(buttonloadingid).hasClass("wait_label_load_after_ajax"))
                setTimeout(function () {
                    LoadingButtonHide({buttonelem: $(buttonloadingid), text: $(buttonloadingid)[0].label});
                }, 2000);
            else
                LoadingButtonHide({buttonelem: $(buttonloadingid), text: $(buttonloadingid)[0].label});
        }
    }
});

$(function () {
    $("input.form-control.setsession").focusout(function () {
        SetSession(this);
    });

    $("select.form-control.setsession").change(function () {
        SetSession(this);
    });

    // if (window.name === '')
    //   InitWindowGUID();
});