<?php
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

if (!isConnect()) {
    throw new Exception('401 Unauthorized');
}

if (init('path') == '') {
    throw new Exception('Aucun widget fourni');
}
$widget = widget::byPath(init('path'));
if (!is_object($widget)) {
    throw new Exception('Widget non trouvé');
}
?>

<div style="display: none;" id="md_applyWidgetAlert"></div>

<a class="btn btn-default" id="bt_applyWidgetToogle" state="0">Basculer</a>
<a class="btn btn-success pull-right bt_applyWidgetToCmd" path="<?php echo $widget->getPath() ?>" style="color : white;" version="">Valider</a>
<a class="btn btn-warning pull-right bt_applyWidgetToCmd" path="default" style="color : white;" version="<?php echo $widget->getVersion() ?>">Remise à défaut</a>

<br/><br/>

<table class="table table-bordered table-condensed tablesorter" id="table_applyWidget">
    <thead>
        <tr>
            <th data-sorter="false"></th><th>Object</th><th>Equipement</th><th>Commande</th><th>Unité</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach (cmd::byTypeSubType($widget->getType(), $widget->getSubType()) as $cmd) {
            $eqLogic = $cmd->getEqLogic();
            if (is_object($eqLogic)) {
                $object = $eqLogic->getObject();
            } else {
                $object = null;
            }
            echo '<tr cmd_id="' . $cmd->getId() . '">';
            echo '<td><input class="applyWidget" type="checkbox" /></td>';

            echo '<td>';
            if (is_object($object)) {
                echo $object->getName();
            }
            echo '</td>';
            echo '<td>';
            if (is_object($eqLogic)) {
                echo $eqLogic->getName();
            }
            echo '</td>';
            echo '<td>';
            echo $cmd->getName();
            echo '</td>';
            echo '<td>';
            echo $cmd->getUnite();
            echo '</td>';
            echo '</tr>';
        }
        ?>
    </tbody>
</table>


<script>
    initTableSorter();
    $('#bt_applyWidgetToogle').on('click', function() {
        var state = false;
        if ($(this).attr('state') == 0) {
            state = true;
            $(this).attr('state', 1);
        } else {
            state = false;
            $(this).attr('state', 0);
        }
        $('#table_applyWidget tbody tr').each(function() {
            if ($(this).is(':visible')) {
                $(this).find('.applyWidget').prop('checked', state);
            }
        });
    });

    $('.bt_applyWidgetToCmd').on('click', function() {
        var cmds = [];
        $('#table_applyWidget tbody tr').each(function() {
            if ($(this).find('.applyWidget').prop('checked')) {
                cmds.push($(this).attr('cmd_id'));
            }
        });
        var path = $(this).attr('path');
        var version = $(this).attr('version');
        $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "/modules/widget/core/ajax/widget.ajax.php", // url du fichier php
            data: {
                action: "applyWidget",
                path: path,
                cmds: json_encode(cmds),
                version: version
            },
            dataType: 'json',
            error: function(request, status, error) {
                handleAjaxError(request, status, error);
            },
            success: function(data) { // si l'appel a bien fonctionné
                if (data.state != 'ok') {
                    $('#md_applyWidgetAlert').showAlert({message: data.result, level: 'danger'});
                    return;
                }
                $('#md_applyWidgetAlert').showAlert({message: "Widget appliqué avec succès", level: 'success'});
            }
        });
    });

</script>