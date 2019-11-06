<?php

define('DOCROOT', __DIR__);
require_once DOCROOT.'/functions.inc.php';

$do = isset($_GET['do']) ? $_GET['do'] : 'index';
$folders = get_folders();
$list = get_list();

ob_start();
switch($do)
{
	/*
	 * Выводим список групп и адресов
	 */
	case 'index':
		require_once DOCROOT.'/templates/index.view.php';
		break;
	
	/*
	 * Пинг IP и сохрание результата
	 */
	case 'ping_ip':
		$id = isset($_GET['id']) ? $_GET['id'] : NULL;
		if ( is_null($id) OR !preg_match('/^[0-9]+\.[0-9]+$/', $id) )
			die('...');
		
		list($folder_id, $ip_id) = explode('.', $id);
		$row = $list['data'][$folder_id][$ip_id];
		
		exec ('ping -c 3 '.$row['ip'], $out);
		echo '<pre>'.print_r($out, true).'</pre>';
		$result = date('Y-m-d H:i:s')."\r\n".implode("\r\n", $out)."\r\n\r\n";
		file_put_contents(DOCROOT.'/data/stats.'.$folder_id.'.'.$ip_id.'.txt', $result, FILE_APPEND);
		break;
	
	/*
	 * Выводим статистику по IP
	 */
	case 'stats_ip':
		$id = isset($_GET['id']) ? $_GET['id'] : NULL;
		if ( is_null($id) OR !preg_match('/^[0-9]+\.[0-9]+$/', $id) )
			die('...');
		
		list($folder_id, $ip_id) = explode('.', $id);
		$row = $list['data'][$folder_id][$ip_id];
		
		$out = file_get_contents(DOCROOT.'/data/stats.'.$folder_id.'.'.$ip_id.'.txt');
		$out = str_replace("\n", '<br />', $out);
		echo '<h2>'.$row['ip'].'</h2>';
		echo $out;
		break;
	
	/*
	 * Редактирование группы
	 */
	case 'edit_folder':
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$last = end($folders);
		$new_id = !$last ? 1:++$last['id'];
		
		if ( !empty($_POST) )
		{
			$_folder = clear_str($_POST['folder']);
			$folders[$new_id] = array(
				'id' => $new_id,
				'name' => $_folder,
				'created' => date('Y-m-d H:i:s')
				);
			save_folders($folders);
			
			echo '<script type="text/javascript">
				window.parent.opener.location.reload();
				window.close();
			</script>';
		}
		
		require_once DOCROOT.'/templates/edit_folder.view.php';
		break;
	
	/*
	 * Редактирование IP-адреса
	 */
	case 'edit_ip':
		$id = isset($_GET['id']) ? $_GET['id'] : NULL;
		if ( is_null($id) OR !preg_match('/^[0-9]+\.[0-9]+$/', $id) )
			die('...');
		
		list($folder_id, $ip_id) = explode('.', $id);
		$new_id = ++$list['last_id'];
		
		if ( !empty($_POST) )
		{
			$_ip = clear_str($_POST['ip']);
			$list['data'][$folder_id][$new_id] = array(
				'id' => $new_id,
				'ip' => $_ip,
				);
			save_list($list);
			
			echo '<script type="text/javascript">
				window.parent.opener.location.reload();
				window.close();
			</script>';
		}
		
		require_once DOCROOT.'/templates/edit_ip.view.php';
		break;
}
$content = ob_get_clean();

?>

<html>
<head>
	<title>Ping app</title>
	<style type="text/css">
	#wrapper {margin: 0 auto; width: 640px;}
	#header {text-align: center;}
	#footer {text-align: center;}
	#topnav {text-align: right;}
	#folders {}
	#folders .item {margin-bottom: 1em;}
	#folders .item-name {font-weight: 600;}
	</style>
</head>
<div id="wrapper">
	<div id="header"><h1>Ping app</h1></div>
		<?= $content?>
	<div id="footer">&copy 2019</div>
</div>