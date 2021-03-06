var SendOsmotraktDialog = "#SendOsmotraktDialog";

var modalSendShow = function () {
    console.debug('yeah');
    $(filtergrid).find('.osmotraktsend').click(function (e) {
        e.preventDefault(); //for prevent default behavior of <a> tag.
        $(SendOsmotraktDialog).modal('show').find('.modal-body').html('<div style="height: 150px; width: 100%; background: url(' + baseUrl + 'images/progress.svg) center center no-repeat; background-size: 20px;"></div>');
        $(SendOsmotraktDialog).modal('show').find('.modal-body').load($(this).attr('href'), function () {
            SetStyleFilterBehavior();
            GetScrollFilter("div.insideforms");

            $("div.insideforms").scroll(function () {
                SetScrollFilter(this);
            });

        });
    });
};

$(document).ready(function () {
    $(document).on('pjax:success', function () {
        modalSendShow();
    });

    $(document).on("click", SendOsmotraktDialog + "_apply", function () {
        if ($("form.gridview-filter-form").find(".has-error").length)
            return false;

        var dopparams;
        dopparams = {id: $("#osmotrakt-osmotrakt_id").val()};

        if ($("#organ-organ_id").val() != "")
            $.ajax({
                url: baseUrl + "Fregat/osmotrakt/osmotrakt-send",
                type: "post",
                data: {
                    osmotrakt_id: $("#osmotrakt-osmotrakt_id").val(),
                    organ_id: $("#organ-organ_id").val(),
                    dopparams: JSON.stringify(dopparams),
                    buttonloadingid: "SendOsmotraktDialog_apply"
                },
                success: function (data) {
                    $(SendOsmotraktDialog).modal("hide");
                },
                error: function (err) {
                    $("div.errordialog").show();
                    if ((err.responseText).indexOf("Internal Server Error (#500): ") >= 0)
                        $("div.errordialog").text((err.responseText).substring(30));
                    else
                        $("div.errordialog").text(err.responseText);
                }
            });
        else {
            $("div.errordialog").show();
            $("div.errordialog").text("Не выбрана организация");
        }

        return false;
    });

    $(document).on('click', SendOsmotraktDialog + "_close", function (event) {
        $(SendOsmotraktDialog).modal("hide");
    });

    modalSendShow();
});