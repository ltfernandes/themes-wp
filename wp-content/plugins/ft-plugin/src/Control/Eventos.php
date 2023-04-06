<?php

namespace PluginFrameticket\Control;

use PluginFrameticket\Middleware\Api;
use PluginFrameticket\Control\Config;
use PluginFrameticket\Control\Pedidos;
use PluginFrameticket\Core\Controller;

class Eventos extends Controller
{

	public $_api;
	public function __construct()
	{
		$this->_api = new Api();
		$this->_config = new Config();
	}

	/**
	 * Retorna lista de eventos
	 *
	 * @param array $params
	 * @return void
	 */
	public function getEventos($params = [])
	{
		$params['tokenFt'] = $_SESSION['tokenPost'];
		$this->_api->postJson($params, 'site/events');
		$ret = $this->_api->ret;
		$this->setOptionsWP($ret);
		return $ret;
	}

	/**
	 * Armazena no banco de dados do WP
	 *
	 * @param array $arr
	 * @return void
	 */
	function setOptionsWP($arr = [])
	{
		if (count($arr)) {
			foreach ($arr as $d) {
				update_option('FRAMETICKET_EVENT_' . $d['id'], $d['title']);
			}
		}
	}

	public function getCategorias()
	{
		$params['categorias'] = json_encode($this->_api->_categorias);
		$params = [
			'type' => '',
			'tokenFt' => $_SESSION['tokenPost']
		];
		$this->_api->postJson($params, '/site/categories');
		if (count($this->_api->ret)) {
			foreach ($this->_api->ret as $c) {
				$menu[$c['id']] = $c['categorie'];
			}
			$this->_api->setSession('menu-categorias', $menu);
		}
		return $this->_api->ret;
	}

	public function showCategoriasLista($args = [])
	{
		$this->_args = $args;
		$dados = [
			'categorias' => $this->getCategorias(),
			'site_url' => get_site_url()
		];
		return $this->renderTemplate('categorylist', $dados, 'S');
	}

	public function getCategoria($id)
	{
		return $_SESSION['menu-categorias'][$id];
	}

	public function get($id_evento = 0, $tipo = "", $slug = "", $paramns = [])
	{
		//Consultando o cupom:
		if ($id_evento && $tipo) {
			$uri = 'site/event/' . $tipo . '/' . $id_evento . (($slug) ? '/' . $slug : '/comprar');
			$paramns['tokenFt'] = $_SESSION['tokenPost'];
			$this->_api->postJson($paramns, $uri);
			$this->setPlans($id_evento, $this->_api->ret['plans']);
			return $this->_api->ret;
		}
	}

	function getTimetable($id_evento = 0, $data = '')
	{
		$this->_api->get('site/event-timetable/' . $id_evento . '/' . $data);
		return $this->_api->ret;
	}

	function getDataCalendar($id_event, $month, $year)
	{
		$this->_api->get('site/render-calendar/' . $id_event . '/' . $month . '/' . $year);
		return $this->_api->ret;
	}

	function consultaData($type = '', $id_evento = 0, $data_visita = '', $timetable = '', $cupom = '', $id_comissario = 0, $convenio_string = '')
	{
		if ($data_visita) {
			$data = $this->datagringa($data_visita);
			if ($timetable == 'S') {
				$res = $this->getTimetable($id_evento, $data);
				return [
					'type' => 'hours',
					'html' => $this->renderTemplate('selectionhour.inc', ['hours' => $res, 'date_visit' => $data_visita], 'S')
				];
			} else {
				return [
					'type' => 'plans',
					'html' => $this->showPlanos($type, $id_evento, $data_visita, $cupom, $id_comissario, 'not-timetable', $convenio_string)
				];
			}
		}
	}

	function showDestaques($args = [])
	{
		$this->_args = $args;
		$this->cleanPartner();
		$paramns = [
			'position' => 'DESTAQUE',
			'date_start' => date('Y-m-d'),
			'type' => $args['tipo'],
			'orderBy' => $args['ordem']
		];
		$events = $this->getEventos($paramns);
		$dados = [
			'site_url' => get_site_url(),
			'events' => $events['events'],
		];
		//print_r($events);
		return $this->renderTemplate('events-highlight', $dados, 'S');
	}

	function showEventosCategorias($args = [])
	{
		$this->_args = $args;
		$this->cleanPartner();
		if ($args['ids_categorias']) {
			$categorias = explode(',', $args['ids_categorias']);
		} else {
			$categorias = [get_query_var('id')];
		}

		$paramns = [
			'position' => '',
			'date_start' => date('Y-m-d'),
			'categories' => $categorias,
			'type' => '',
			'orderBy' => $args['ordem']
		];

		$events = $this->getEventos($paramns);
		$dados = [
			'site_url' => get_site_url(),
			'events' => $events['events'],
			'categoria_nome' => (count($categorias) == 1) ? $this->getCategoria($categorias[0]) : ''
		];
		return $this->renderTemplate('events-categories', $dados, 'S');
	}

	function setPartner($partner = [])
	{
		if ($partner) {
			$_SESSION['partner'] = $partner;
		}
	}

	function getPartner()
	{
		return ($_SESSION['partner']) ? $_SESSION['partner'] : [];
	}

	function cleanPartner()
	{
		unset($_SESSION['partner']);
	}

	function showEventosQRcode($args = [])
	{
		$this->_args = $args;
		$paramns = [
			'position' => '',
			'date_start' => date('Y-m-d'),
			'id_partner' => get_query_var('id_comissario'),
			'type' => ''
		];

		$events = $this->getEventos($paramns);
		$total_events = count($events['events']);
		if ($total_events >= 1) {

			//Salva os dados do partner:
			if ($events['partner']) {
				$this->setPartner($events['partner']);
			}

			//Renderiza direto a tela do evento ou unidade:
			if ($total_events == 1) {

				$paramns = [
					'force_id' => $events['events'][0]['id'],
					'force_calendario' => $args['force_calendario']
				];
				return ($events['events'][0]['type'] == 'UNIDADE') ? $this->showUnidadeNegocios($paramns) : $this->showEvento($paramns);
			} else {
				$dados = [
					'site_url' => get_site_url(),
					'events' => $events['events'],
					'partner' => $events['partner'],
				];
				return $this->renderTemplate('events-qrcode', $dados, 'S');
			}
		}
	}

	public function showResultBusca($args = [])
	{
		$this->_args = $args;
		$this->cleanPartner();
		$paramns = [
			'position' => '',
			'date_start' => date('Y-m-d'),
			'search' => $_GET['q'],
			'type' => '',
			'orderBy' => $args['ordem']
		];
		$events = $this->getEventos($paramns);

		$dados = [
			'events' => $events['events'],
			'busca' => $_GET['q'],
			'site_url' => get_site_url()
		];
		return $this->renderTemplate('events-resultsearch', $dados, 'S');
	}

	public function showFormBusca($args = [])
	{

		$this->_args = $args;
		$dados = [
			'busca' => $_GET['q'],
			'site_url' => get_site_url(),
			'args' => get_site_url()
		];
		return $this->renderTemplate('searchform', $dados, 'S');
	}

	function showSlider($args = [])
	{
		$this->_args = $args;
		$this->cleanPartner();
		$paramns = [
			'position' => 'SLIDER',
			'date_start' => date('Y-m-d'),
			'type' => $args['tipo'],
			'orderBy' => $args['ordem'],
			'limit_result' => $args['limite']
		];
		$events = $this->getEventos($paramns);
		$dados = [
			'site_url' => get_site_url(),
			'events' => $events['events'],
			'total' => count($events['events']),
		];
		return $this->renderTemplate('events-slider', $dados, 'S');
	}

	function showUnidadeNegocios($args = [])
	{
		$this->_args = $args;

		if ($args['force_data_visita']) {
			$date_visit = $args['force_data_visita'];
		} else if ($_GET['date']) {
			$date_visit = $_GET['date'];
		}

		if ($args['force_horario_visita']) {
			$time_visit = $args['force_horario_visita'];
		}

		if ($args['force_id']) {
			$id_evento = $args['force_id'];
		} else {
			$id_evento = get_query_var('id');
		}

		if ($id_evento) {
			$cupom = $_GET['cupom_url'] ? $_GET['cupom_url'] : get_query_var('cupom');
			$slug = get_query_var('slug');
			$paramns = [
				'date_visit' => $date_visit,
				'time_visit' => $time_visit,
				'coupon' => $cupom,
				'id_partner' => $this->getPartner()['id'],
				'origin' => 'SITE'
			];
			$event = $this->get($id_evento, 'unidade', $slug, $paramns);

			if ($event && $date_visit) {

				if (!$this->checkDate($event, $date_visit)) {
					return $this->renderTemplate('error-date', [], 'S');
				}
			}

			if ($event['required_scheduling'] == 'S' || $args['force_calendario'] == 'S') {

				if (!$date_visit) {
					$date_visit_default = $this->getDateVisitDefault($event['start_date'], $event['date_blocked']);
				} else {
					$date_visit_default = $date_visit;
				}
				$hours = $this->getTimetable($id_evento, $this->datagringa($date_visit_default));

				if ($event['timetable'] == 'N') {
					$event['plans'] = $this->getPlanos('unidade', $id_evento, $date_visit_default, $cupom, 0, 'not-timetable', [])['plans'];
				}
			} else {
				$date_visit_default = '';
				$hours = [];
			}

			if ($event['date_blocked']) {
				$date_blocked = function ($data) {
					return $this->dataBR($data);
				};
				$event['date_blocked'] = array_map($date_blocked, $event['date_blocked']);
			}

			if ($event['scripts']) {

				foreach ($event['scripts'] as $js) {
					$scripts[] = base64_decode($js['scripts']);
				}
				unset($event['scripts']);
			}
			$objPedido = new Pedidos();
			$dados = [
				'site_url' => get_site_url(),
				'ide' => $id_evento,
				'idp' => 0,
				'event' => $event,
				'partner' => $this->getPartner(),
				'scripts' => $scripts,
				'get' => $_GET,
				'date_visit' => $date_visit_default,
				'date_visit_raw' => $this->datagringa($date_visit_default),
				'date_visit_end' => date('d/m/Y', strtotime("+1 year")),
				'week_block' => $this->getWeekBlock($event['week_blocked']),
				'hours' => $hours,
				'paramns' => $paramns,
				'cupom' => $cupom,
				'force_calendar' => $args['force_calendario'],
				'vendas_off' => get_option('FRAMETICKET_VENDAS'),
				'vendas_carrinho' => get_option('FRAMETICKET_VENDAS_CARRINHO'),
				'template_directory' => get_template_directory_uri(),
				'carrinho' => $objPedido->getCarrinho(),
				'totais' => $objPedido->getTotalCarrinho(),
				'gallery' => $this->getGallery($id_evento)
			];
			return $this->renderTemplate('unit', $dados, 'S');
		}
	}

	/**
	 * Verifica se a data escolhida pode ser utilizada
	 *
	 * @param array $event
	 * @param string $date
	 *
	 * @return bool
	 */
	private function checkDate(array $event = [], string $date = ''): bool
	{
		if (in_array($this->datagringa($date), $event['date_blocked'])) {
			return false;
		}

		if ($event['week_blocked']) {
			$weekday_number = $this->weekDayToNumber($event['week_blocked']);

			if (in_array(date("N", strtotime($this->datagringa($date))), $weekday_number)) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Retorna os dias da semana em numeros
	 *
	 * @param array $week_blocked
	 *
	 * @return array
	 */
	private function weekDayToNumber(array $week_blocked): array
	{
		foreach ($week_blocked as $wb) {
			switch ($wb) {
				case 'SEG':
					$weekday_number[] = 1;
					break;
				case 'TER':
					$weekday_number[] = 2;
					break;
				case 'QUA':
					$weekday_number[] = 3;
					break;
				case 'QUI':
					$weekday_number[] = 4;
					break;
				case 'SEX':
					$weekday_number[] = 5;
					break;
				case 'SAB':
					$weekday_number[] = 6;
					break;
				case 'DOM':
					$weekday_number[] = 7;
					break;
			}
		}
		return $weekday_number;
	}

	/**
	 * Retorna a galeria Frameticket caso o evento tenha uma
	 */
	function getGallery($id_evento = 0)
	{
		$cc_args = array(
			'posts_per_page'   => 1,
			'post_type'        => 'galeria_frameticket',
			'meta_key'         => '_galeria_evento',
			'meta_value'       => $id_evento
		);
		$data = new \WP_Query($cc_args);
		$id_gallery = $data->posts[0]->ID;
		if (isset($id_gallery)) {
			return get_post_gallery($id_gallery, false)['src'];
		}
	}

	function getWeekBlock($week_blocked = [])
	{
		if ($week_blocked) {
			$date_blocked = function ($week) {
				switch ($week) {
					case "DOM":
						return '0';
						break;
					case "SEG":
						return '1';
						break;
					case "TER":
						return '2';
						break;
					case "QUA":
						return '3';
						break;
					case "QUI":
						return '4';
						break;
					case "SEX":
						return '5';
						break;
					case "SAB":
						return '6';
						break;
				}
			};
			return implode(',', array_map($date_blocked, $week_blocked));
		} else {
			return '';
		}
	}

	function getDateVisitDefault($start_days = 0, $date_blocked = [])
	{
		$date_visit_default = date('Y-m-d', strtotime("+" . $start_days . " days"));

		if ($date_blocked) {
			foreach ($date_blocked as $date) {
				if ($date_visit_default == $date) {
					$date_visit_default = date('Y-m-d', strtotime($date_visit_default . " +1 days"));
				}
			}
		}

		return date('d/m/Y', strtotime($date_visit_default));
	}

	function showEvento($args = [])
	{
		$this->_args = $args;
		if ($args['force_id']) {
			$id = $args['force_id'];
		} else {
			$id = get_query_var('id');
		}
		if ($id) {
			$cupom = get_query_var('cupom');
			$slug = get_query_var('slug');

			$paramns = [
				'coupon' => $cupom,
				'id_partner' => $this->getPartner()['id'],
				'origin' => 'SITE'
			];
			$event = $this->get($id, 'evento', $slug, $paramns);
			$id_evento = $event['id_evento'];

			if ($event['scripts']) {

				foreach ($event['scripts'] as $js) {
					$scripts[] = base64_decode($js['scripts']);
				}
				unset($event['scripts']);
			}
			$dados = [
				'site_url' => get_site_url(),
				'ide' => $id_evento,
				'idp' => $id,
				'event' => $event,
				'partner' => $this->getPartner(),
				'scripts' => $scripts,
				'date_visit' => $event['event_date'],
				'time_visit' => $event['event_hour'],
				'paramns' => $paramns,
				'vendas_off' => get_option('FRAMETICKET_VENDAS'),
				'vendas_carrinho' => get_option('FRAMETICKET_VENDAS_CARRINHO'),
				'desativar_cupom' => get_option('FRAMETICKET_DESATIVAR_CUPOM'),
				'gallery' => $this->getGallery($id)
			];

			return $this->renderTemplate('event', $dados, 'S');
		}
	}

	public function getPlanos($type = '', $id_event = 0, $date_visit = '', $cupom = "", $id_partner = 0, $hour_visit = '', $convenio = [])
	{
		$id_partner = ($id_partner) ? $id_partner : $this->getPartner()['id'];

		$post = [
			'tokenFt' => $_SESSION['tokenPost'],
			'date_visit' => ($date_visit) ? $this->datagringa($date_visit) : '',
			'time_visit' => $hour_visit,
			'id_partner' => $id_partner,
			'coupon' => $cupom,
			'origem' => 'SITE',
			'convenio' => $convenio
		];
		$this->_api->postJson($post, 'site/event-plans/' . $type . '/' . $id_event);
		$this->setPlans($id_event, $this->_api->ret['plans']);
		//echo $this->_api->puro;
		return $this->_api->ret;
	}

	public function getplacesPlan($id_plan = 0, $plan = '', $type = '', $id = 0)
	{
		if ($id_plan && $type && $id) {
			$this->_api->get('site/event-plan-places/' . $type . '/' . $id . '/' . $id_plan);
			$res = $this->_api->ret;
			if ($res) {
				foreach ($res as $i => $d) {
					$ret[$i] = $d;
					$ret[$i]['class'] = ($d['status_lugar'] == 'RESERVADO') ? "lugarmap-reservado" : "lugarmap-disponivel";
					$_SESSION['lugares'][$d['id_lugar']] = $d['nome'];
				}
			}
			return $ret;
		}
	}

	/*
	public function selectLugar($id_plan = 0, $index = 0, $type = '', $id = 0)
	{
		if ($id_plan && $type && $id) {
			$this->_api->get('site/event-plan-places/' . $type . '/' . $id . '/' . $id_plan);
			$res = $this->_api->ret;
			if ($res) {
				foreach ($res as $i => $d) {
					$ret[$i] = $d;
					$ret[$i]['sel'] = "";
					$ret[$i]['class'] = "lugarmap-disponivel lugarmap-click";
				}
			}
			return $ret;
		}
	}
	*/

	private function setPlans($id = 0, $plans = [])
	{
		if ($id && $plans) {
			$id_event = ($plans[0]['event_type'] == 'evento') ? $plans[0]['id_event'] : $id;
			$_SESSION['plans'][$id_event] = $plans;
			return $_SESSION['plans'][$id_event];
		}
	}

	function getPlansCart($id_event = 0)
	{
		if ($id_event) {
			return json_encode($_SESSION['plans'][$id_event]);
		}
	}

	public function showPlanos($type = '', $id_event = 0, $date_visit = '', $cupom = "", $id_partner = 0, $hour_visit = '', $convenio_string = '')
	{
		if ($convenio_string) {
			$conv = explode('|', $convenio_string);
			$convenio = [
				'id' => $conv[0],
				'valor' => $conv[1],
			];
		} else {
			$convenio = [];
		}
		$res = $this->getPlanos($type, $id_event, $date_visit, $cupom, $id_partner, $hour_visit, $convenio);
		$res['type'] = $type;
		$dados = [
			'event' => $res,
			'msg_validate' => $res['msg_validate'],
			'validate_status' => $res['validate_status'],
			'cupom' => $cupom,
			'date_visit' => $date_visit,
			'convenio' => $convenio['valor'],
			'desativar_cupom' => get_option('FRAMETICKET_DESATIVAR_CUPOM')
		];
		if ($res['type'] == 'evento') {
			return $this->renderTemplate('productlist-event.inc', $dados, 'S');
		} else {
			return $this->renderTemplate('productlist.inc', $dados, 'S');
		}
	}

	public function buscaCupom($cupom = '')
	{
		if ($cupom) {
			$msg = '<br><div class="alert alert-warning">Cupom <b>' . $cupom . '</b> não localizado!</div>';
		} else {
			$msg = '<br><div class="alert alert-danger">Informe o código do cupom!</div>';
		}
		return ['msg' => $msg];
	}

	public function desformataMoeda($valor = 0)
	{
		$valor = str_replace('.', '', $valor);
		$valor = str_replace(',', '.', $valor);
		return $valor;
	}

	public function showCapacidade($args = [])
	{
		$this->_args = $args;

		if ($args['data']) {
			$this->_api->get('/site/get-capacity/' . $args['data']);
		} else if ($_GET['data']) {
			$this->_api->get('/site/get-capacity/' . $_GET['data']);
		} else {
			$this->_api->get('/site/get-capacity');
		}
		$res = $this->_api->ret;
		$dados['date'] = $res['date'];
		
		if ($res['table']) {
		
			foreach ($res['table'] as $d) {
				$dados['table']['horarios'][$d['evento'] . $d['hora_visita']]['horario'] = $d['hora_visita'];
				$dados['table']['horarios'][$d['evento'] . $d['hora_visita']][$d['tipopag']]['total'] = $d['total'];
				$dados['table']['horarios'][$d['evento'] . $d['hora_visita']][$d['tipopag']]['entregue'] = $d['entregues'];
				$dados['table']['horarios'][$d['evento'] . $d['hora_visita']]['total'] += $d['total'];
				$dados['table']['horarios'][$d['evento'] . $d['hora_visita']]['entregue'] += $d['entregues'];
				$dados['table']['horarios'][$d['evento'] . $d['hora_visita']]['evento'] = $d['evento'];
			}
		}
		return $this->renderTemplate('capacity', $dados, 'S');
	}

	public function showComboboxEventosGaleria()
	{
		$params = [
			'position' => '',
			'date_start' => date('Y-m-d'),
			'type' => '',
			'orderBy' => '',
			'limit_result' => 0
		];
		$events = $this->getEventos($params);
		$id = get_post_meta(get_post()->ID, '_galeria_evento')[0];
		$dados = ['events' => $events['events'], 'id' => $id];
		return $this->renderTemplate('combobox-events', $dados);
	}
}
