<?php

/**
 * Класс Database
 * Класс для установки соединения с базой данных
 */

class Database
{
 
  protected $_pdo = null;
  
  /**
   * Устанавливает соединение с базой данных  
   * @return PDO - Объект класса PDO, предоставляющий соединение с базой данных
   */
  public function connect()
  {
     $paramsPath = ROOT . '/config/pdo_params.php';
     $params = include($paramsPath);
     $host_dbname = "mysql:host={$params['host']};dbname={$params['dbname']}";
     $this->_pdo = new PDO($host_dbname, $params['user'], $params['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
     $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     $this->_pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
     $this->_pdo->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
     return $this->_pdo;
  }
}