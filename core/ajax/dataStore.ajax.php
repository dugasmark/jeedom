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

try {
    require_once dirname(__FILE__) . '/../../core/php/core.inc.php';
    include_file('core', 'authentification', 'php');

    if (!isConnect()) {
        throw new Exception('401 Unauthorized');
    }

    if (init('action') == 'remove') {
        $dataStore = dataStore::byId(init('id'));
        if (!is_object($dataStore)) {
            throw new Exception('Data store inconnu vérifer l\'id : ' . init('id'));
        }
        $dataStore->remove();
        ajax::success();
    }

    if (init('action') == 'save') {
        if (init('id') == '') {
            $dataStore = new dataStore();
            $dataStore->setKey(init('key'));
            $dataStore->setLink_id(init('link_id'));
            $dataStore->setType(init('type'));
        } else {
            $dataStore = dataStore::byId(init('id'));
        }
        if (!is_object($dataStore)) {
            throw new Exception('Data store inconnu vérifer l\'id : ' . init('id'));
        }
        $dataStore->setValue(init('value'));
        $dataStore->save();
        ajax::success();
    }

    if (init('action') == 'all') {
        ajax::success(utils::o2a(dataStore::byTypeLinkId(init('type'))));
    }

    throw new Exception('Aucune methode correspondante à : ' . init('action'));
    /*     * *********Catch exeption*************** */
} catch (Exception $e) {
    ajax::error(displayExeption($e), $e->getCode());
}
?>
