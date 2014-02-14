<?php
if (!isConnect('admin')) {
    throw new Exception('401 Unauthorized');
}

$market = market::byId(init('id'));
$update = '';

if (config::byKey('installVersionDate', $market->getName()) != '' && config::byKey('installVersionDate', $market->getName()) < $market->getDatetime()) {
    echo '<div style="width : 100%" class="alert alert-warning" id="div_pluginUpdate">Une mise à jour est disponible. Cliquez sur installer pour l\'effectuer</div>';
}
?>

<div style="display: none;width : 100%" id="div_alertMarketDisplay"><?php echo $update; ?></div>


<a class="btn btn-success pull-right" href="<?php echo config::byKey('market::address') . "/core/php/downloadFile.php?id=" . $market->getId() ?>" style="color : white;"><i class="fa fa-cloud-download"></i> Télécharger</a>
<a class="btn btn-warning pull-right" style="color : white;" id="bt_installFromMarket" data-market_id="<?php echo $market->getId(); ?> "><i class="fa fa-plus-circle"></i> Installer</a>

<?php if (config::byKey('installVersionDate', $market->getName()) != '') { ?>
    <a class="btn btn-danger pull-right" style="color : white;" id="bt_removeFromMarket" data-market_id="<?php echo $market->getId(); ?> "><i class="fa fa-minus-circle"></i> Supprimer</a>
<?php } ?>
<br/><br/><br/>
<form class="form-horizontal" role="form">
    <div class="row">
        <div class="col-md-5">

            <div class="form-group">
                <label class="col-lg-4 control-label">ID</label>
                <div class="col-lg-8">
                    <input class="form-control marketAttr" data-l1key="id" style="display: none;">
                    <span class="label label-success marketAttr" data-l1key="logicalId" placeholder="Nom"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label">Nom</label>
                <div class="col-lg-8">
                    <span class="label label-success marketAttr" data-l1key="name" placeholder="Nom"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label">Type</label>
                <div class="col-lg-8">
                    <select class="form-control marketAttr" data-l1key="type" disabled>
                        <option value="plugin">Plugin</option>
                        <option value="widget">Widget</option>
                        <option value="zwave_module">[Zwave] Configuration module</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label">Auteur</label>
                <div class="col-lg-8">
                    <span class="label label-success" ><?php echo $market->getAuthor() ?></span>
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg-4 control-label">Description</label>
                <div class="col-lg-8">
                    <span class="label label-primary marketAttr" data-l1key="description" placeholder="Description" style="height: 100px;"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label">Changelog</label>
                <div class="col-lg-8">
                    <span class="label label-default marketAttr" data-l1key="changelog" placeholder="Changelog" style="height: 100px;"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label">Status</label>
                <div class="col-lg-8">
                    <select class="form-control marketAttr" data-l1key="status" disabled>
                        <option>A valider</option>
                        <option>Validé</option>
                        <option>Refusé</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg-4 control-label">Catégorie</label>
                <div class="col-lg-8">
                    <span class="label label-warning marketAttr" data-l1key="categorie" placeholder="Catégorie"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label">Version</label>
                <div class="col-lg-8">
                    <span class="label label-success marketAttr" data-l1key="version" placeholder="Version" ></span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label">Derniere modification de l'archive</label>
                <div class="col-lg-6">
                    <span class="marketAttr label label-info" data-l1key="datetime"></span>
                </div>
            </div>
            <?php if (config::byKey('installVersionDate', $market->getName()) != '') { ?>
                <div class="form-group">
                    <label class="col-lg-4 control-label">Version utilisé actuelement</label>
                    <div class="col-lg-6">
                        <span class="marketAttr label label-info" ><?php echo config::byKey('installVersionDate', $market->getName()); ?></span>
                    </div>
                </div>
            <?php } ?>
            <div class="form-group">
                <label class="col-lg-4 control-label">Nombre de téléchargement</label>
                <div class="col-lg-8">
                    <span class="marketAttr label label-info" data-l1key="downloaded"></span>
                </div>
            </div>
        </div> 
        <div class="col-md-7">
            <div class="form-group">
                <div class="col-lg-12">
                    <img   src="<?php echo config::byKey('market::address') . '/market/' . $market->getType() . '/' . $market->getName() . '.jpg'; ?>"  class="img-responsive img-thumbnail" />
                </div>
            </div>
        </div> 
    </div> 
</form>

<script>
    $('body').setValues(json_decode('<?php echo json_encode(utils::o2a($market)) ?>'), '.marketAttr');

    $('#bt_installFromMarket').on('click', function() {
        var id = $(this).attr('data-market_id');
        $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "core/ajax/market.ajax.php", // url du fichier php
            data: {
                action: "install",
                id: id
            },
            dataType: 'json',
            error: function(request, status, error) {
                handleAjaxError(request, status, error);
            },
            success: function(data) { // si l'appel a bien fonctionné
                if (data.state != 'ok') {
                    $('#div_alertMarketDisplay').showAlert({message: data.result, level: 'danger'});
                    return;
                }
                $('#div_pluginUpdate').remove();
                $('#div_alertMarketDisplay').showAlert({message: 'Plugin installé. Rechargé la page pour mettre à jour', level: 'success'});
            }
        });
    });

    $('#bt_removeFromMarket').on('click', function() {
        var id = $(this).attr('data-market_id');
        $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "core/ajax/market.ajax.php", // url du fichier php
            data: {
                action: "remove",
                id: id
            },
            dataType: 'json',
            error: function(request, status, error) {
                handleAjaxError(request, status, error);
            },
            success: function(data) { // si l'appel a bien fonctionné
                if (data.state != 'ok') {
                    $('#div_alertMarketDisplay').showAlert({message: data.result, level: 'danger'});
                    return;
                }
                $('#div_alertMarketDisplay').showAlert({message: 'Plugin supprimé. Rechargé la page pour mettre à jour', level: 'success'});
            }
        });
    });
</script>