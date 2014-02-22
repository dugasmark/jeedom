<?php
if (!isConnect('admin')) {
    throw new Exception('401 Unauthorized');
}

try {
    if (init('logicalId') != '') {
        $market = market::byLogicalId(init('logicalId'));
    }
    if (is_object($market)) {
        if ($market->getApi_author() != config::byKey('market::apikey')) {
            throw new Exception('Vous n\'etez pas l\'autheur du plugin');
        }
    }
} catch (Exception $e) {
    
}
if (init('type') == 'plugin') {
    $plugin = new plugin(init('logicalId'));
    if (!is_object($plugin)) {
        throw new Exception('Le plugin : ' . init('logicalId') . ' est introuvable');
    }
}
?>

<div style="display: none;width : 100%" id="div_alertMarketSend"></div>


<a class="btn btn-success pull-right" style="color : white;" id="bt_sendToMarket"><i class="fa fa-cloud-upload"></i> Envoyer</a>

<br/><br/><br/>
<form class="form-horizontal" role="form" id="form_sendToMarket">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-lg-4 control-label">ID</label>
                <div class="col-lg-8">
                    <input class="form-control marketAttr" data-l1key="id" style="display: none;">
                    <input class="form-control marketAttr" data-l1key="logicalId" placeholder="ID" value="<?php echo $plugin->getId() ?>" disabled/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label">Nom</label>
                <div class="col-lg-6">
                    <input class="form-control marketAttr" data-l1key="name" placeholder="Nom" value="<?php echo $plugin->getName() ?>"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label">Type</label>
                <div class="col-lg-6">
                    <select class="form-control marketAttr" data-l1key="type" disabled>
                        <option value="plugin">Plugin</option>
                        <option value="widget">Widget</option>
                        <option value="zwave">[Zwave] Configuration module</option>
                        <option value="script">Script</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label">Statut</label>
                <div class="col-lg-6">
                    <select class="form-control marketAttr" data-l1key="status" >
                        <option>A valider</option>
                        <option>Validé</option>
                        <option>Refusé</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg-4 control-label">Catégorie</label>
                <div class="col-lg-6">
                    <input class="form-control marketAttr" data-l1key="categorie" placeholder="Catégorie" value="<?php echo $plugin->getCategory() ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label">Version</label>
                <div class="col-lg-6">
                    <input class="form-control marketAttr" data-l1key="version" placeholder="Version" value="<?php echo $plugin->getVersion() ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label">Dernière modification de l'archive</label>
                <div class="col-lg-6">
                    <span class="marketAttr label label-info" data-l1key="datetime"></span>
                </div>
            </div>
        </div> 
        <div class="col-md-6">
            <div class="form-group">

                <div class="form-group">
                    <label class="col-lg-4 control-label">Description</label>
                    <div class="col-lg-6">
                        <textarea class="form-control marketAttr" data-l1key="description" placeholder="Description" style="height: 150px;"><?php echo $plugin->getDescription() ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-4 control-label">Changelog</label>
                    <div class="col-lg-6">
                        <textarea class="form-control marketAttr" data-l1key="changelog" placeholder="Changelog" style="height: 150px;"></textarea>
                    </div>
                </div>
            </div>
        </div> 
    </div> 
</form>


<?php
if (is_object($market)) {
    sendVarToJS('market_display_info', utils::o2a($market));
} else {
    sendVarToJS('market_display_info', array());
}
?>
<script>
    $('body').setValues(market_display_info, '.marketAttr');
    $('.marketAttr[data-l1key=type]').value(market_type);

    $('#bt_sendToMarket').on('click', function() {
        var market = $('#form_sendToMarket').getValues('.marketAttr');
        market = market[0];
        $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "core/ajax/market.ajax.php", // url du fichier php
            data: {
                action: "save",
                market: json_encode(market),
            },
            dataType: 'json',
            error: function(request, status, error) {
                handleAjaxError(request, status, error, $('#div_alertMarketSend'));
            },
            success: function(data) { // si l'appel a bien fonctionné
                if (data.state != 'ok') {
                    $('#div_alertMarketSend').showAlert({message: data.result, level: 'danger'});
                    return;
                }
                $('#div_alertMarketSend').showAlert({message: 'Enregistrement réussi (recharger la page pour avoir toutes les fonctionalités)', level: 'success'});

            }
        });
    });
</script>