
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
    $("#admin_tab").delegate("a", 'click', function(event) {
        $(this).tab('show');
        $.hideAlert();
    })

    if (tab != '') {
        $('#admin_tab a[href=#' + tab + ']').click();
    }

    printUsers();

    $("#bt_addUser").on('click', function(event) {
        $.hideAlert();
        $('#in_newUserLogin').value('');
        $('#in_newUserMdp').value('');
        $('#md_newUser').modal('show');
    });

    $("#bt_newUserSave").on('click', function(event) {
        $.hideAlert();
        saveUser('');
    });

    $("#bt_genKeyAPI").on('click', function(event) {
        $.hideAlert();
        genKeyAPI();
    });

    $("#bt_nodeJsKey").on('click', function(event) {
        $.hideAlert();
        genNodeJsKey();
    });

    $("#bt_flushMemcache").on('click', function(event) {
        $.hideAlert();
        flushMemcache();
    });

    $("#bt_saveGeneraleConfig").on('click', function(event) {
        $.hideAlert();
        saveGeneraleConfig();
    });

    $("#bt_updateJeedom").on('click', function(event) {
        bootbox.confirm('Etez-vous sûr de vouloir mettre à jour Jeedom ?', function(result) {
            if (result) {
                $.ajax({
                    type: 'POST',
                    url: 'core/ajax/git.ajax.php',
                    data: {
                        action: 'update',
                    },
                    dataType: 'json',
                    error: function(request, status, error) {
                        handleAjaxError(request, status, error);
                    },
                    success: function(data) {
                        if (data.state != 'ok') {
                            $('#div_alert').showAlert({message: data.result, level: 'danger'});
                            return;
                        }
                        $('#div_alert').showAlert({message: 'Mise à jour en cours...', level: 'success'});
                        getUpdateLog(true);
                    }
                });
            }
        });



    });

    $("#bt_refreshUpdateLog").on('click', function(event) {
        getUpdateLog();
    });

    $("#bt_testLdapConnection").on('click', function(event) {
        $.hideAlert();
        $.ajax({
            type: 'POST',
            url: 'core/ajax/user.ajax.php',
            data: {
                action: 'testLdapConneciton',
            },
            dataType: 'json',
            error: function(request, status, error) {
                handleAjaxError(request, status, error);
            },
            success: function(data) {
                if (data.state != 'ok') {
                    $('#div_alert').showAlert({message: 'Connection échoué : ' + data.result, level: 'danger'});
                    return;
                }
                $('#div_alert').showAlert({message: 'Connection réussie', level: 'success'});
            }
        });
        return false;
    });

    $("#table_user").delegate(".sup_user", 'click', function(event) {
        $.hideAlert();
        var id = $(this).attr('id');
        bootbox.confirm('Etez-vous sûr de vouloir supprimer cet utilisateur ?', function(result) {
            if (result) {
                delUser(id);
            }
        });
    });

    $("#table_user").delegate(".change_mdp_user", 'click', function(event) {
        $.hideAlert();
        var id = $(this).attr('id');
        $('#bt_mdpUserSave').undelegate();
        $("#div_mainContainer").delegate("#bt_mdpUserSave", 'click', function(event) {
            saveUser(id);
            return false;
        });
        $('#md_mdpUser').modal('show');
        return false;
    });


    $('#bt_addColorConvert').on('click', function() {
        addConvertColor();
    });

    printConvertColor();

    loadGeneraleConfig();
});
/********************Log************************/
function getUpdateLog(_autoUpdate) {
    $.ajax({
        type: 'POST',
        url: 'core/ajax/git.ajax.php',
        data: {
            action: 'getUpdateLog',
        },
        dataType: 'json',
        global: false,
        error: function(request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function(data) {
            if (data.state != 'ok') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            var log = '';
            for (var i in data.result.reverse()) {
                log += data.result[i][2];
            }
            if (_autoUpdate != 0 && log == $('#pre_updateInfo').text()) {
                _autoUpdate++;
                alert('no change');
            }
            if (_autoUpdate > 30) {
                _autoUpdate = 0;
            }
            $('#pre_updateInfo').text(log);
            if (init(_autoUpdate, 0) != 0) {
                setTimeout(getUpdateLog(_autoUpdate), 1000);
            }
        }
    });
}


/********************Utilisateurs************************/

function printUsers() {
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "core/ajax/user.ajax.php", // url du fichier php
        data: {
            action: "all"
        },
        dataType: 'json',
        error: function(request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function(data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            $('#table_user tbody').empty();
            $.each(data.result, function(i) {
                var ligne = '<tr><td class="login">';
                ligne += data.result[i].login;
                ligne += '</td>';
                ligne += '<td>';
                if (ldapEnable != '1') {
                    ligne += '<a class="btn btn-danger pull-right sup_user" id="' + data.result[i].id + '"><i class="fa fa-trash-o" style="position:relative;left:-5px;top:1px"></i>Supprimer</a>'
                    ligne += '<a class="btn btn-warning pull-right change_mdp_user" id="' + data.result[i].id + '"><i class="fa fa-pencil" style="position:relative;left:-5px;top:1px"></i>Changer le mot de passe</a>'
                }
                ligne += '</td>';
                ligne += '</tr>';
                $('#table_user tbody').append(ligne);
            });
        }
    });
}


function delUser(_id) {
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "core/ajax/user.ajax.php", // url du fichier php
        data: {
            action: "delUser",
            id: _id
        },
        dataType: 'json',
        error: function(request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function(data) { // si l'appel a bien fonctionné
            printUsers();
            if (data.state != 'ok') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            $('#div_alert').showAlert({message: 'L\'utilisateur a bien été supprimé', level: 'success'});
        }
    });
}


function saveUser(_id) {
    try {
        if (_id == '') {
            var login = $('#in_newUserLogin').value();
            var password = $('#in_newUserMdp').value();
        } else {
            var password = $('#in_mdpUserNewMdp').value();
            var login = '';
        }

        if (_id == '') {
            if (login == '') {
                throw('Le nom d\'utilisateur ne peut être vide');
            }
            if (password == '') {
                throw('Le mot de passe ne peut être vide');
            }
        } else {
            if (password == '') {
                throw('Le mot de passe ne peut être vide');
            }
        }
    } catch (e) {
        $('#div_newUserAlert').showAlert({message: e, level: 'danger'});
        return false;
    }

    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "core/ajax/user.ajax.php", // url du fichier php
        data: {
            action: "editUser",
            login: login,
            password: password,
            id: _id
        },
        dataType: 'json',
        error: function(request, status, error) {
            handleAjaxError(request, status, error, $('#div_newUserAlert'));
        },
        success: function(data) { // si l'appel a bien fonctionné
            printUsers();
            if (data.state != 'ok') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
                if (login == '') {
                    $('#div_alert').showAlert({message: data.result, level: 'danger'});
                } else {
                    $('#div_newUserAlert').showAlert(data.result);
                }
                return;
            }
            if (_id != '') {
                $('#div_alert').showAlert({message: 'L\'utilisateur ' + login + ' a bien été mise à jour', level: 'success'});
                $('#md_mdpUser').modal('hide');
            } else {
                $('#div_alert').showAlert({message: 'L\'utilisateur ' + login + ' a bien été crée', level: 'success'});
                $('#md_newUser').modal('hide');
            }
        }
    });
}

/********************Administation************************/

function genKeyAPI() {
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "core/ajax/config.ajax.php", // url du fichier php
        data: {
            action: "genKeyAPI"
        },
        dataType: 'json',
        error: function(request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function(data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            $('#in_keyAPI').value(data.result);
        }
    });
}

function genNodeJsKey() {
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "core/ajax/config.ajax.php", // url du fichier php
        data: {
            action: "genNodeJsKey"
        },
        dataType: 'json',
        error: function(request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function(data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            $('#in_nodeJsKey').value(data.result);
        }
    });
}

function flushMemcache() {
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "core/ajax/mc.ajax.php", // url du fichier php
        data: {
            action: "flush"
        },
        dataType: 'json',
        error: function(request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function(data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            $('#div_alert').showAlert({message: 'Cache vidé', level: 'success'});
        }
    });
}

function saveGeneraleConfig() {
    saveConvertColor();
    try {
        var configuration = $('#div_administration').getValues('.configKey');
        configuration = configuration[0];
    } catch (e) {
        $('#div_alert').showAlert({message: e, level: 'danger'});
        return false;
    }
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "core/ajax/config.ajax.php", // url du fichier php
        data: {
            action: "addKey",
            value: json_encode(configuration)
        },
        dataType: 'json',
        error: function(request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function(data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            $('#div_alert').showAlert({message: 'Sauvegarde effetuée', level: 'success'});
        }
    });
}

function loadGeneraleConfig() {
    var configuration = $('#div_administration').getValues('.configKey');
    configuration = configuration[0];
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "core/ajax/config.ajax.php", // url du fichier php
        data: {
            action: "getKey",
            module: 'core',
            key: json_encode(configuration)
        },
        dataType: 'json',
        error: function(request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function(data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            $('#div_administration').setValues(data.result, '.configKey');
        }
    });
}


/********************Convertion************************/
function printConvertColor() {
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "core/ajax/config.ajax.php", // url du fichier php
        data: {
            action: "getKey",
            key: 'convertColor'
        },
        dataType: 'json',
        error: function(request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function(data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
                return;
            }

            $('#table_convertColor tbody').empty();
            for (var color in data.result) {
                addConvertColor(color, data.result[color]);
            }
        }
    });
}

function addConvertColor(_color, _html) {
    var tr = '<tr>';
    tr += '<td>';
    tr += '<input class="color form-control" value="' + init(_color) + '"/>';
    tr += '</td>';
    tr += '<td>';
    tr += '<input class="html form-control" value="' + init(_html) + '"/>';
    tr += '<div class="colorpicker" style="display : none"></div>';
    tr += '</td>';
    tr += '</tr>';
    $('#table_convertColor tbody').append(tr);
    var input = $('#table_convertColor tbody tr:last .html');
    var div = input.closest('td').find('.colorpicker');
    div.farbtastic(input);
    input.focus(function() {
        if (!$(this).parent().find('.colorpicker').is(':visible')) {
            $(this).parent().find('.colorpicker').show();
        }
    });

    input.blur(function() {
        if ($(this).parent().find('.colorpicker').is(':visible')) {
            $(this).parent().find('.colorpicker').hide();
        }
    });
}

function saveConvertColor() {
    var values = [];
    var value = {};
    value.key = 'convertColor';
    var colors = {};
    $('#table_convertColor tbody tr').each(function() {
        colors[$(this).find('.color').value()] = $(this).find('.html').value();
    });
    value.value = colors;
    values.push(value);
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "core/ajax/config.ajax.php", // url du fichier php
        data: {
            action: 'addKey',
            value: json_encode(values)
        },
        dataType: 'json',
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
}