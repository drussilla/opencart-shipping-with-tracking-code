<?php
class ControllerShippingWeightWithTracking extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('shipping/weight_with_tracking');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('weight_with_tracking', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_rate'] = $this->language->get('entry_rate');
		$data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['help_rate'] = $this->language->get('help_rate');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_shipping'),
			'href' => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('shipping/weight_with_tracking', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('shipping/weight_with_tracking', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');

		$this->load->model('localisation/geo_zone');

		$geo_zones = $this->model_localisation_geo_zone->getGeoZones();

		foreach ($geo_zones as $geo_zone) {
			if (isset($this->request->post['weight_with_tracking_' . $geo_zone['geo_zone_id'] . '_rate'])) {
				$data['weight_with_tracking_' . $geo_zone['geo_zone_id'] . '_rate'] = $this->request->post['weight_with_tracking_' . $geo_zone['geo_zone_id'] . '_rate'];
			} else {
				$data['weight_with_tracking_' . $geo_zone['geo_zone_id'] . '_rate'] = $this->config->get('weight_with_tracking_' . $geo_zone['geo_zone_id'] . '_rate');
			}

			if (isset($this->request->post['weight_with_tracking_' . $geo_zone['geo_zone_id'] . '_status'])) {
				$data['weight_with_tracking_' . $geo_zone['geo_zone_id'] . '_status'] = $this->request->post['weight_with_tracking_' . $geo_zone['geo_zone_id'] . '_status'];
			} else {
				$data['weight_with_tracking_' . $geo_zone['geo_zone_id'] . '_status'] = $this->config->get('weight_with_tracking_' . $geo_zone['geo_zone_id'] . '_status');
			}
		}

		$data['geo_zones'] = $geo_zones;

		if (isset($this->request->post['weight_with_tracking_tax_class_id'])) {
			$data['weight_with_tracking_tax_class_id'] = $this->request->post['weight_with_tracking_tax_class_id'];
		} else {
			$data['weight_with_tracking_tax_class_id'] = $this->config->get('weight_with_tracking_tax_class_id');
		}

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['weight_with_tracking_status'])) {
			$data['weight_with_tracking_status'] = $this->request->post['weight_with_tracking_status'];
		} else {
			$data['weight_with_tracking_status'] = $this->config->get('weight_with_tracking_status');
		}

		if (isset($this->request->post['weight_with_tracking_sort_order'])) {
			$data['weight_with_tracking_sort_order'] = $this->request->post['weight_with_tracking_sort_order'];
		} else {
			$data['weight_with_tracking_sort_order'] = $this->config->get('weight_with_tracking_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('shipping/weight_with_tracking.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/weight_with_tracking')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}