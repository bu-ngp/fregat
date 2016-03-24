jQuery(document).ready(function ($) {
    $(document).on('ready pjax:success', function () {
        $('.filter_button').click(function (e) {
            e.preventDefault(); //for prevent default behavior of <a> tag.
            var tagname = $(this)[0].tagName;
            //   $('#Authitemfilter').unbind('show.bs.modal');

            $('#Authitemfilter')/*.on('shown.bs.modal', function () {})*/.modal('show')
                    .find('.modal-body')
                    .load($(this).attr('href'), function () {
                        var formcontain = $("#Authitemfilter")[0].formcontain;

                        if (typeof formcontain !== "undefined") {
                            var obj = $.deserialize(formcontain);
                            $('[name = "AuthitemFilter[onlyrootauthitems]"]').prop('checked', obj.AuthitemFilter.onlyrootauthitems === "1" ? true : false);
                        }
                    });
        });
    });
});