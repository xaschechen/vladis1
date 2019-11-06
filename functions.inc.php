<?php

function clear_str($str, $pattern = 'a-zA-Zа-яА-Я0-9 \.')
{
	$str = preg_replace('/[^'.$pattern.']/u', '', $str);
	return $str;
}

function query_var($data = NULL, $value = NULL, $url = NULL)
{
	$uri = explode('?', $_SERVER['REQUEST_URI']);
	if ( !isset($uri[1]) ) $uri[1] = '';
	parse_str($uri[1], $vars);
	unset($vars['page']);
	
	if ( !is_null($data) )
	{
		if ( !is_array($data) ) {
			$data = array($data => $value);
		}
		foreach($data as $name => $value)
		{
			if ( ! is_null($value) ) {
				$vars[$name] = $value;
			} else {
				unset($vars[$name]);
			}
		}
	}
	
	$uri = is_null($url) ? $uri[0] : $url;
	if ( !empty($vars) ) {
		$uri .= '?'.http_build_query($vars);
	}
	
	return $uri;
}

function get_folders()
{
	$folders = array();
	
	if ( !file_exists(DOCROOT.'/folders.db') )
		return $folders;
	
	$db = file(DOCROOT.'/folders.db');
	
	foreach($db as $row) {
		$row = explode("\t", trim($row));
		$folders[$row[0]] = array(
			'id' => $row[0],
			'name' => $row[1],
			'created' => $row[2],
			);
	}
	return $folders;
}

function save_folders($folders)
{
	$result = '';
	foreach($folders as $row) {
		$result .= "{$row['id']}\t{$row['name']}\t{$row['created']}\r\n";
	}
	file_put_contents(DOCROOT.'/folders.db', $result);
}

function get_list()
{
	$list = array(
		'data' => array(),
		'last_id' => 0
		);
	
	if ( !file_exists(DOCROOT.'/list.db') )
		return array();
	
	$db = file(DOCROOT.'/list.db');
	foreach($db as $row) {
		$row = explode("\t", trim($row));
		$list['data'][$row[1]][$row[0]] = array(
			'id' => $row[0],
			'ip' => $row[2],
			);
		if ( $list['last_id'] < $row[0] ) $list['last_id'] = $row[0];
	}
	return $list;
}

function save_list($list)
{
	$result = '';
	foreach($list['data'] as $folder_id => $items) {
		foreach($items as $item) {
			$result .= "{$folder_id}\t{$item['id']}\t{$item['ip']}\r\n";
		}
	}
	file_put_contents(DOCROOT.'/list.db', $result);
}
