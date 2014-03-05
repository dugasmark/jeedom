<?php
if (!isConnect()) {
    throw new Exception('401 - Unauthorized access to page');
}
?>

<style>
    .viewZone{
        padding: 9.5px;
        margin: 0 0px 5px;
        font-size: 13px;
        line-height: 20px;
        background-color: #f5f5f5;
        border: 1px solid #ccc;
        border: 1px solid rgba(0,0,0,0.15);
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
    }

    .item .selectpicker {
        border-bottom-left-radius: 0;
        border-top-left-radius: 0;
    }

    .row .col-inner {
        margin-top:15px;
        margin-bottom:15px;
        height:250px;
    }


    .viewData{
        display:inline-block;
    }

    #div_viewZones {
        overflow: hidden;
    }

</style>

<div class="row">
    <div class="col-lg-2">
        <div class="bs-sidebar affix">
            <ul id="ul_view" class="nav nav-list bs-sidenav fixnav">
                <li class="nav-header">Liste des vues 
                    <i class="fa fa-plus-circle pull-right cursor" id="bt_addView" style="font-size: 1.5em;margin-bottom: 5px;"></i>
                </li>
                <li class="filter" style="margin-bottom: 5px;"><input class="filter" placeholder="Rechercher" style="width: 100%"/></li>
                <?php
                foreach (view::all() as $view) {
                    echo '<li class="cursor li_view" data-view_id="' . $view->getId() . '"><a>' . $view->getName() . '</a></li>';
                }
                ?>
            </ul>
        </div>
    </div>

    <div class="col-lg-10">
        <legend style="height: 35px;">Vue<a class="btn btn-default btn-xs pull-right" id="bt_addviewZone"><i class="fa fa-plus-circle"></i> Ajouter viewZone</a></legend>

        <div id="div_viewZones" class="row" style="margin-top: 10px;"></div>


        <div class="form-actions" style="margin-top: 10px;">
            <a class="btn btn-default" id="bt_editView"><i class="fa fa-pencil"></i> Editer</a>
            <a class="btn btn-danger" id="bt_removeView"><i class="fa fa-trash-o"></i> Supprimer</a>
            <a class="btn btn-success" id="bt_saveView"><i class="fa fa-save"></i> Enregistrer</a>
        </div>
    </div>

</div>

<div class="modal fade" id="md_addView">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Ajouter une vue</h4>
            </div>
            <div class="modal-body">
                <div style="display: none;" id="div_addViewAlert"></div>
                <input class="form-control" type="text" id="in_addViewId" style="display : none;"/>
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-lg-4 control-label" >Nom</label>
                        <div class="col-lg-8">
                            <input class="form-control" type="text" id="in_addViewName" placeholder="Nom de la vue"/>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a class="btn btn-danger" data-dismiss="modal"><i class="fa fa-minus-circle"></i> Annuler</a>
                <a class="btn btn-success" id="bt_addViewSave"><i class="fa fa-check-circle"></i> Enregistrer</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="md_addEditviewZone">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Ajouter/Editer viewZone</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger div_alert" style="display: none;" id="div_addEditviewZoneError"></div>
                <input id="in_addEditviewZoneEmplacement"  style="display : none;" />
                <form class="form-horizontal" onsubmit="return false;">
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Nom</label>
                        <div class="col-lg-5">
                            <input id="in_addEditviewZoneName" class="form-control" placeholder="Nom" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Type</label>
                        <div class="col-lg-5">
                            <select class="form-control" id="sel_addEditviewZoneType">
                                <option value="widget">Widget</option>
                                <option value="graph">Graphique</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a class="btn btn-danger" data-dismiss="modal">Annuler</a>
                <a class="btn btn-success" id="bt_addEditviewZoneSave"><i class="fa fa-save"></i> Enregistrer</a>
            </div>
        </div>
    </div>
</div>


<div id="md_addViewData" title="Ajouter widget/graph">
    <table id="table_addViewDataHidden" style="display: none;">
        <tbody></tbody>
    </table>
    <table class="table table-condensed table-bordered table-striped tablesorter" id="table_addViewData">
        <thead>
            <tr>
                <th style="width: 50px;">#</th>
                <th style="width: 150px;">Type</th>
                <th style="width: 150px;">Objet</th>
                <th style="width: 150px;">Nom</th>
                <th>Affichage</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach (cmd::all() as $cmd) {
                $eqLogic = $cmd->getEqLogic();
                if (!is_object($eqLogic)) {
                    continue;
                }
                if ($eqLogic->getIsVisible() == 1 && $cmd->getIsHistorized() == 1) {
                    $object = $cmd->getEqLogic()->getObject();
                    echo '<tr data-link_id="' . $cmd->getId() . '" data-type="graph" data-viewDataType="cmd">';
                    echo '<td>';
                    echo '<input type="checkbox" class="enable form-control" />';
                    echo '<input class="viewDataOption" data-l1key="link_id" value="' . $cmd->getId() . '" hidden/>';
                    echo '</td>';
                    echo '<td class="type">';
                    echo 'Commande';
                    echo '<input class="viewDataOption" data-l1key="type" value="cmd" hidden/>';
                    echo '</td>';
                    echo '<td class="object_name">';
                    if (is_object($object)) {
                        echo $object->getName();
                    }
                    echo '</td>';
                    echo '<td class="name">';
                    echo $eqLogic->getName() . '/';
                    echo $cmd->getName();
                    echo '</td>';
                    echo '<td class="display">';
                    echo '<div class="option">';
                    echo '<form class="form-inline">';
                    echo '<div class="form-group">';
                    echo '<label>Couleur :</label> <select class="viewDataOption form-control" data-l1key="configuration" data-l2key="graphColor" style="width : 110px;background-color:#4572A7;color:white;">';
                    echo '<option value="#4572A7" style="background-color:#4572A7;color:white;">Bleu</option>';
                    echo '<option value="#AA4643" style="background-color:#AA4643;color:white;">Rouge</option>';
                    echo '<option value="#89A54E" style="background-color:#89A54E;color:white;">Vert</option>';
                    echo '<option value="#80699B" style="background-color:#80699B;color:white;">Violet</option>';
                    echo '<option value="#00FFFF" style="background-color:#00FFFF;color:white;">Bleu ciel</option>';
                    echo '<option value="#DB843D" style="background-color:#DB843D;color:white;">Orange</option>';
                    echo '<option value="#FFFF00" style="background-color:#FFFF00;color:white;">Jaune</option>';
                    echo '<option value="#FE2E9A" style="background-color:#FE2E9A;color:white;">Rose</option>';
                    echo '<option value="#000000" style="background-color:#000000;color:white;">Noir</option>';
                    echo '<option value="#3D96AE" style="background-color:#3D96AE;color:white;">Vert/Bleu</option>';
                    echo '</select> ';
                    echo '</div> ';
                    echo '<div class="form-group">';
                    echo ' <label>Type :</label> <select class="viewDataOption form-control" data-l1key="configuration" data-l2key="graphType" style="width : 100px;">';
                    echo '<option value="line">Ligne</option>';
                    echo '<option value="area">Aire</option>';
                    echo '<option value="column">Colonne</option>';
                    echo '</select> ';
                    echo '</div> ';
                    echo '<div class="form-group">';
                    echo '';
                    echo ' <label>Escalier : <input type="checkbox" class="viewDataOption" data-l1key="configuration" data-l2key="graphStep">';
                    echo '</label>';
                    echo ' <label>Empiler : <input type="checkbox" class="viewDataOption" data-l1key="configuration" data-l2key="graphStack">';
                    echo '</label>';
                    echo ' <label>Echelle :</label> <select class="viewDataOption form-control" data-l1key="configuration" data-l2key="graphScale" style="width : 60px;">';
                    echo '<option value="0">0</option>';
                    echo '<option value="1">1</option>';
                    echo '</select>';

                    echo '</div>';
                    echo '</form>';
                    echo '</div>';
                    echo '</td>';
                    echo '</tr>';
                }
            }

            foreach (eqLogic::all() as $eqLogic) {
                if ($eqLogic->getIsVisible() == 1) {
                    $object = $eqLogic->getObject();
                    echo '<tr data-link_id="' . $eqLogic->getId() . '" data-type="widget" data-viewDataType="eqLogic">';
                    echo '<td>';
                    echo '<input type="checkbox" class="enable form-control" />';
                    echo '<input class="viewDataOption" data-l1key="type" value="eqLogic" hidden/>';
                    echo '<input class="viewDataOption" data-l1key="link_id" value="' . $eqLogic->getId() . '" hidden/>';
                    echo '</td>';
                    echo '<td class="type">';
                    echo 'Equipement';
                    echo '</td>';
                    echo '<td class="object_name">';
                    if (is_object($object)) {
                        echo $object->getName();
                    }
                    echo '</td>';
                    echo '<td class="name">';
                    echo $eqLogic->getName();
                    echo '</td>';
                    echo '<td></td>';
                    echo '</tr>';
                }
            }
            foreach (scenario::all() as $scenario) {
                echo '<tr data-link_id="' . $scenario->getId() . '" data-type="widget" data-viewDataType="scenario">';
                echo '<td>';
                echo '<input type="checkbox" class="enable form-control" />';
                echo '<input class="viewDataOption" data-l1key="type" value="scenario" hidden/>';
                echo '<input class="viewDataOption" data-l1key="link_id" value="' . $scenario->getId() . '" hidden/>';
                echo '</td>';
                echo '<td class="type">';
                echo 'Scénario';
                echo '</td>';
                echo '<td class="object_name"></td>';
                echo '<td class="name">';
                echo $scenario->getName();
                echo '</td>';
                echo '<td></td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<?php include_file('desktop', 'view_edit', 'js'); ?>