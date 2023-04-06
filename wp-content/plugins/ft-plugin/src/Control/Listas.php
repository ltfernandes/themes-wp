<?php

namespace PluginFrameticket\Control;

use PluginFrameticket\Middleware\Api;
use PluginFrameticket\Control\Config;
use PluginFrameticket\Core\Controller;

class Listas extends Controller
{

	public $_api;
	public function __construct()
	{
		$this->_api = new Api();
		$this->_config = new Config();
	}



	/**
	 * Retorna lista de eventos do organizador, que possuam configuração para lista
	 *
	 * @param array $params
	 * @return void
	 */
	public function showEventos($params = [])
	{
		if ($params['force_id_org']) {
			$id_organizer = $params['force_id_org'];
		} else {
			$id_organizer = get_query_var('id_org');
		}

		$this->_api->get('/site/events-lists/' . $id_organizer);
		$events = $this->_api->ret;

		$dados = [
			'site_url' => get_site_url(),
			'events' => $events['events'],
		];
		//print_r($events);
		return $this->renderTemplate('events-lists', $dados, 'S');

	}



	/**
	 * Exibe o formulário para inserir os nomes na lista
	 *
	 * @param array $params
	 * @return void
	 */
	public function showForm($params = [])
	{

		if ($params['force_id_event']) {
			$id_event = $params['force_id_event'];
			$type = $params['type'];
		} else {
			$id_event = get_query_var('id');
			$type = get_query_var('tipo');
		}		

		$this->_api->get('/site/event-list/' . strtolower($type) . '/' . $id_event);
		$ret = $this->_api->ret;

		$dados = [
			'site_url' => get_site_url(),
			'event' => $ret['event'],
			'lists' => $ret['list'],
		];

		return $this->renderTemplate('form-lists', $dados, 'S');

	}



	/**
	 * Salva a lista de nomes inseridos
	 * @return [type] [description]
	 */
	public function eventListSave($post = [])
	{
        $post['tokenFt'] = $_SESSION['tokenPost'];
        $this->_api->postJson($post, '/site/event-list-save');
        $ret = $this->_api->ret;
 		return $ret;
	}

}
