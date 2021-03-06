<?php
/**
 * DebugKitEx Cache Panel View
 *
 * Copyright (c) 2012, Wan Chen aka Kamisama
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author 		Wan Qi Chen <kami@kamisama.me>
 * @copyright 	Copyright 2012, Wan Qi Chen <kami@kamisama.me>
 * @link 		https://github.com/kamisama/DebugKitEx
 * @package 	DebugKitEx
 * @subpackage 	DebugKitEx.View.Elements
 * @since 		2.2.0
 * @license 	MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

	$configs = Cache::configured();
	$content = array();
	$stats = Cache::getLogs();
	foreach($configs as $config)
	{
		$engine = Cache::settings($config);
		$logs = Cache::getLogs($config);
		$content[$config] = array('settings' => $engine, 'logs' => $logs['logs']);
	}

?>
<style type="text/css">
	.debug_ex-table .type{width: 20%;font-weight: bold}
	.read{color: #999}
	.set{color:#999;font-style:italic}
	.delete{color: #e8665e}
	.missed td{background-color: #ff6167!important;color:#fff}
	.meter{width: 200px; display:block; height: 5px; background:green;position:relative;border:0;}
	.meter .cache-writes{background:red;display:block; height:5px;position:absolute; right:0;}
	h3 small {font-size: 80%; color: gray;}
</style>

<h2><?php echo __d('debug_kit_ex', 'Cache Logs')?></h2>
<?php if (!empty($content)) : ?>

	<h3><?php
		echo __d('debug_kit_ex', 'Total queries : %s, in %s ms', $stats['count']['total'], $stats['time']);
		echo '<span class="meter"><span class="cache-writes" style="width:' . ($stats['count']['write']/$stats['count']['read']*100) .'%;"></span></span>';
		echo __d('debug_kit_ex', ' <small>(%s reads/%s writes)</small>', $stats['count']['read'], $stats['count']['write']); ?></h3>

	<?php foreach ($content as $name => $datas): ?>
	<div class="sql-log-panel-query-log">

		<h4><?php echo $name ?> <span class="set">(<?php echo $datas['settings']['engine']; ?>)</span></h4>
		<?php

			if (!empty($datas['logs']))
			{
				echo '<table class="debug-table debug_ex-table">';
				echo '<tr>';
				echo '<th>'.__d('debug_kit_ex', 'Type').'</th>';
				echo '<th>'.__d('debug_kit_ex', 'Keyname').'</th>';
				echo '<th>'.__d('debug_kit_ex', 'Took (ms)').'</th>';
				echo '</tr>';

				foreach($datas['logs'] as $log)
				{
					echo '<tr class="' . (!$log['success'] ? 'missed' : '') . ' ' . $log['type'] . '">';
					echo '<td class="type">'.$log['type']. (!$log['success'] ? ' (missed)' : '') .'</td>';
					echo '<td>'.$log['key']. '</td>';
					echo '<td>'.$log['time']. '</td>';
					echo '</tr>';
				}

				echo '</table>';
			}
			else echo '<p class="info">' .__d('debug_kit_ex', 'No cache activities') . '</p>';
		 ?>

	</div>
	<?php endforeach; ?>
<?php else:
	echo $this->Toolbar->message('Warning', __d('debug_kit_ex', 'No configured cache'));
endif; ?>