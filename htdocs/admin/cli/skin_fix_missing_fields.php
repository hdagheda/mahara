<?php

/**
 * Sorting out old skins when upgrading.
 *
 * @package    mahara
 * @subpackage core
 * @author     Catalyst IT Ltd
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL version 3 or later
 * @copyright  For copyright information on Mahara, please see the README file distributed with this software.
 *
 */

define('CLI', 1);
define('INTERNAL', 1);

require(dirname(dirname(dirname(__FILE__))) . '/init.php');
require(get_config('libroot') . 'cli.php');
require(get_config('libroot') . 'upgrade.php');

$cli = get_cli();

$options = array();

$settings = new stdClass();
$settings->options = $options;
$settings->info = get_string('cliclearcachesdescription', 'admin');

$cli->setup($settings);

log_info('Start: Fixing skins for new format options');
if ($skins = get_column('skin', 'id')) {
    require_once('skin.php');
    safe_require('artefact', 'file');
    foreach ($skins as $skinid) {
        $skinobj = new Skin($skinid);
        $viewskin = $skinobj->get('viewskin');
        if (!isset($viewskin['view_block_header_font'])) {
            $viewskin['view_block_header_font'] = '';
        }
        if (!isset($viewskin['view_block_header_font_color'])) {
            $viewskin['view_block_header_font_color'] = '';
        }
        $skinobj->set('viewskin', $viewskin);
        $skinobj->commit();
    }
}
log_info('End: Fixing skins for new format options');
