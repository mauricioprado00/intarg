<?php
class Base_Configuration{
	private static $data= array(
		'db_host'=>null,
		'db_user'=>null,
		'db_pass'=>null,
		'db_database'=>null,
		'conf_url_app'=>null,
		'conf_debug_environment'=>null,
	);
	/*
# classkit_ import
# classkit_ method_ add
# classkit_ method_ copy
# classkit_ method_ redefine
# classkit_ method_ remove
# classkit_ method_ rename
	*/
	public function Init(){
		if(0){
			self::makeGettersSetters();
		}
		self::setdb_host(DB_HOST);
		self::setdb_user(DB_USER);
		self::setdb_pass(DB_PASS);
		self::setdb_database(DB_DATABASE);
		self::setconf_url_app(CONF_URL_APP);
		self::setconf_debug_environment(CONF_DEBUG_ENVIRONMENT);
	}
	
	/**
		regenerar esto cuando sea necesario con makeGetterSetters
	**/
	public function makeGettersSetters(){
		foreach(self::$data as $key=>$value)
		{
			?> 
	public static function set<?=$key?>($<?=$key?>){
		self::$data['<?=$key?>'] = $<?=$key?>;
	} 
	public static function get<?=$key?>(){
		return(self::$data['<?=$key?>']);
	}<?
		}
	}
	/**
	codigo autogenerado
	*/
	public static function setdb_host($db_host){
		self::$data['db_host'] = $db_host;
	} 
	public static function getdb_host(){
		return(self::$data['db_host']);
	} 
	public static function setdb_user($db_user){
		self::$data['db_user'] = $db_user;
	} 
	public static function getdb_user(){
		return(self::$data['db_user']);
	} 
	public static function setdb_pass($db_pass){
		self::$data['db_pass'] = $db_pass;
	} 
	public static function getdb_pass(){
		return(self::$data['db_pass']);
	} 
	public static function setdb_database($db_database){
		self::$data['db_database'] = $db_database;
	} 
	public static function getdb_database(){
		return(self::$data['db_database']);
	} 
	public static function setconf_url_app($conf_url_app){
		self::$data['conf_url_app'] = $conf_url_app;
	} 
	public static function getconf_url_app(){
		return(self::$data['conf_url_app']);
	}
	public static function setconf_debug_environment($conf_debug_environment){
		self::$data['conf_debug_environment'] = $conf_debug_environment;
	} 
	public static function getconf_debug_environment(){
		return(self::$data['conf_debug_environment']);
	}
}
Base_Configuration::Init();
?>