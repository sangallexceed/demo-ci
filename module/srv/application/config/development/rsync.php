<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * rsync同期用
 */
$config['rsync']  = [
	/**
	 * 共通APIサーバー1
	 */
	 'common_api_1' => [
		  // ユーザー名
		  'username' => 'websystem',
		  // ホスト名
		  'hostname' => '192.168.xxx.xxx',
		  // 同期先のディレクトリ
		  'directory' => '/var/www/html/...',
		  // 公開鍵のフルパス
		  'public_key' => 'websystem.pem'
	 ],
	/**
	 * 共通APIサーバー2
	 */
	 'common_api_2' => [

	 ],
	/**
	 * JDS Idpサーバー1
	 */
	 'common_api_2' => [

	 ],
];

/**
 * 管理者用設定
 */
$config['manage'] = [
		'acl' => [
				/**
				 * 管理者のID・パスワード
				 */
				'accounts' => [
						'user1' => '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', //123456
						'user2' => 'bcb15f821479b4d5772bd0ca866c00ad5f926e3580720659cc80d39c9d09802a', //111111
						'admin' => '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918' //admin
				],
				/**
				 * アクセス許可IPアドレス（IPv4）
				 */
				'ip_address' => [
						'192.168.1.28',
						'192.168.1.100',
						'172.17.0.1',
				]
		]
];
