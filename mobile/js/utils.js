$(function() {
    /*********************Gestion de l'heure********************************/
    setInterval(function() {
        var date = new Date();
        date.setTime(date.getTime() + clientServerDiffDatetime);
        var hour = date.getHours();
        var minute = date.getMinutes();
        var seconde = date.getSeconds();
        var horloge = (hour < 10) ? '0' + hour : hour;
        horloge += ':';
        horloge += (minute < 10) ? '0' + minute : minute;
        horloge += ':';
        horloge += (seconde < 10) ? '0' + seconde : seconde;
        $('#horloge').text(horloge);
    }, 1000);

    // Ajax Loading Screen
    $(document).ajaxStart(function() {
        $.showLoading();
    });
    $(document).ajaxStop(function() {
        $.hideLoading();
    });
});

$("#leftpanel").panel({
    create: function(event, ui) {
        if ($('#rightpanel').length == 0) {
            $('a[href=#rightpanel]').remove();
            if (window.innerWidth > 800) {
                setTimeout(function() {
                    $("#leftpanel").panel("open")
                }, 10);
            }
        }
    },
});

$("#rightpanel").panel({
    create: function(event, ui) {
        if ($('#rightpanel').length != 0) {
            if (window.innerWidth > 800) {
                setTimeout(function() {
                    $("#rightpanel").panel("open")
                }, 10);
            }
        }
    },
});


function refreshMessageNumber() {
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "core/ajax/message.ajax.php", // url du fichier php
        data: {
            action: "nbMessage"
        },
        dataType: 'json',
        global: false,
        error: function(request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function(data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            $('#span_nbMessage').html(data.result);
            $(document).ajaxStart(function() {
                $.mobile.loading('show', {
                    text: 'Chargement...',
                    textVisible: true,
                });
            });
        }
    });
}

function notify(_title, _text) {
    if (_title == '' && _text == '') {
        return true;
    }
    $('#div_alert').html("<center><b>" + _title + "</b></center>" + _text).popup("open", {y: 0});
    setTimeout(function() {
        $('#div_alert').popup("close");
    }, 1000)
}
