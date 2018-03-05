function LoadImages(data) {
    typefile = (typeof (data) !== "undefined") ? data.files[0].type : '';

    if (typefile == 'image/jpeg' || typefile == 'image/png' || typefile === '')
        $.ajax({
            url: baseUrl + "Fregat/material-docfiles/get-images",
            type: "post",
            data: {id_material: $("#material-material_id").val()},
            success: function (obj) {
                var fotoramaDiv = $('#material_fotorama').fotorama();
                var fotorama = fotoramaDiv.data('fotorama');
                if (obj.length == 0)
                    fotorama.pop();
                else
                    fotorama.load(obj);
            },
            error: function (data) {
                console.error("Ошибка LoadImages()");
            }
        });
}