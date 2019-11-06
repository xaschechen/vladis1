<div id="topnav">
	<a href="javascript:void()" onclick="window.open('<?= query_var(array('do'=>'edit_folder', 'id' => 0))?>', 'popup', 'height=320,width=640');">Добавить группу</a>
</div>
<div id="folders">
	<?php foreach($folders as $folder): ?>
	<div class="item">
		<div class="item-name"><?= $folder['name']?> <a href="<?= query_var(array('do'=>'stats_folder', 'id' => $folder['id']))?>">stats (csv)</a></div>
		<div class="item-list">
			<?php if ( isset($list['data'][$folder['id']]) ): ?>
				<?php foreach($list['data'][$folder['id']] as $item): ?>
					<span><?= $item['ip']?></span>
					<a href="javascript:void()" onclick="window.open('<?= query_var(array('do'=>'ping_ip', 'id' => $folder['id'].'.'.$item['id']))?>', 'popup', 'height=320,width=640');">ping</a>
					<a href="javascript:void()" onclick="window.open('<?= query_var(array('do'=>'stats_ip', 'id' => $folder['id'].'.'.$item['id']))?>', 'popup', 'height=320,width=640');">stats</a>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
		<div class="item-addip"><a href="javascript:void()" onclick="window.open('<?= query_var(array('do'=>'edit_ip', 'id' => $folder['id'].'.0'))?>', 'popup', 'height=320,width=640');">Добавить IP</a></div>
	</div>
	<?php endforeach; ?>
</div>