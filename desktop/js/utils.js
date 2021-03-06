
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

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

    activateTooltips();

    // Ajax Loading Screen
    $(document).ajaxStart(function() {
        $.showLoading();
    });
    $(document).ajaxStop(function() {
        $.hideLoading();
    });

    /************************Help*************************/

    //Display help
    $("#md_pageHelp").dialog({
        autoOpen: false,
        modal: true,
        height: (jQuery(window).height() - 150),
        width: 1000
    });

    $("#md_modal").dialog({
        autoOpen: false,
        modal: true,
        height: (jQuery(window).height() - 150),
        width: 1500,
        position: {my: 'center', at: 'center', of: window},
        open: function() {
            if ((jQuery(window).width() - 50) < 1500) {
                $('#md_modal').dialog({width: jQuery(window).width() - 50});
            }
        }
    });

    $("#md_modal2").dialog({
        autoOpen: false,
        modal: true,
        height: (jQuery(window).height() - 250),
        width: 1200,
        position: {my: 'center', at: 'center', of: window},
        open: function() {
            if ((jQuery(window).width() - 50) < 1500) {
                $('#md_modal2').dialog({width: jQuery(window).width() - 50});
            }
        }
    });

    $('#bt_jeedomAbout').on('click', function() {
        $('#md_modal').load('index.php?v=d&modal=about').dialog('open');
    });

    /******************Gestion mode expert**********************/

    $('#bt_expertMode').on('click', function() {
        if ($(this).attr('state') == 1) {
            var value = {options: {expertMode: 0}};
            $(this).attr('state', 0);
            $(this).find('i').removeClass('fa-check-square-o').addClass('fa-square-o');
            $('.expertModeDisable').attr('disabled', false);
            $('.expertModeHidden').hide();
        } else {
            var value = {options: {expertMode: 1}};
            $(this).attr('state', 1);
            $(this).find('i').removeClass('fa-square-o').addClass('fa-check-square-o');
            $('.expertModeDisable').attr('disabled', true);
            $('.expertModeHidden').show();
        }

        $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "core/ajax/user.ajax.php", // url du fichier php
            data: {
                action: "saveUser",
                user: json_encode(value)
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
            }
        });
    });

    $('.bt_pluginUpdate').on('click', function() {
        $('#md_modal2').dialog({title: "Market Jeedom Display"});
        $('#md_modal2').load('index.php?v=d&modal=market.display&type=plugin&logicalId=' + $(this).attr('data-logicalId')).dialog('open');
    });
    
    $('body').delegate('.bt_pageHelp','click',function(){
        showHelpModal($(this).attr('data-name'), $(this).attr('data-plugin'));
    });

    initTableSorter();
    initExpertMode();
});

function initExpertMode() {
    if ($('#bt_expertMode').attr('state') == 1) {
        $('.expertModeDisable').attr('disabled', true);
        $('.expertModeHidden').show();
    } else {
        $('.expertModeDisable').attr('disabled', false);
        $('.expertModeHidden').hide();
    }
}

function initTableSorter() {
    $(".tablesorter").tablesorter({
        theme: "bootstrap",
        widthFixed: true,
        headerTemplate: '{content} {icon}',
        widgets: ["uitheme", "filter", "zebra"],
        widgetOptions: {
            zebra: ["even", "odd"],
        }
    });
}

function showHelpModal(_name, _plugin) {
    var plugin = '';
    if (init(_plugin) != '' && _plugin != undefined) {
        plugin = init(_plugin);
    }
    $('#md_pageHelp').load('index.php?v=d&plugin=' + plugin + '&modal=help.' + init(_name)).dialog('open');
}

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
            $('#span_nbMessage').html('<i class="fa fa-envelope icon-white"></i> ' + data.result + ' message(s)');
            $('#span_nbMessage').show();
        }
    });
}

function notify(_title, _text, _class_name, _cleanBefore) {
    if(_title == '' && _text == ''){
        return true;
    }
    if (init(_cleanBefore, false)) {
        $.gritter.removeAll();
    }
    if (isset(_class_name) != '') {
        $.gritter.add({
            title: _title,
            text: _text,
            class_name: _class_name
        });
    } else {
        $.gritter.add({
            title: _title,
            text: _text
        });
    }
}


jQuery.fn.findAtDepth = function(selector, maxDepth) {
    var depths = [], i;

    if (maxDepth > 0) {
        for (i = 1; i <= maxDepth; i++) {
            depths.push('> ' + new Array(i).join('* > ') + selector);
        }

        selector = depths.join(', ');
    }
    return this.find(selector);
};