<?php
class Cart {
	private $config;
	private $db;
	private $data = array();
	private $data_recurring = array();

	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->customer = $registry->get('customer');
		$this->session = $registry->get('session');
		$this->db = $registry->get('db');
		$this->tax = $registry->get('tax');
		$this->weight = $registry->get('weight');

		if (!isset($this->session->data['cart']) || !is_array($this->session->data['cart'])) {
			$this->session->data['cart'] = array();
		}
	}

	public function getCases() {
		if (!$this->data) {
			foreach ($this->session->data['cart'] as $key => $quantity) {
				$case = explode(':', $key);
				$case_id = $case[0];
				$stock = true;

				// Options
				if (!empty($case[1])) {
					$options = unserialize(base64_decode($case[1]));
				} else {
					$options = array();
				} 

				// Profile

				if (!empty($case[2])) {
					$profile_id = $case[2];
				} else {
					$profile_id = 0;
				}

				$case_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "`case` p LEFT JOIN " . DB_PREFIX . "case_description pd ON (p.case_id = pd.case_id) WHERE p.case_id = '" . (int)$case_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.date_available <= NOW() AND p.status = '1'");

				if ($case_query->num_rows) {
					$option_price = 0;
					$option_points = 0;
					$option_weight = 0;

					$option_data = array();

					foreach ($options as $case_option_id => $option_value) {
						$option_query = $this->db->query("SELECT po.case_option_id, po.option_id, od.name, o.type FROM " . DB_PREFIX . "case_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.case_option_id = '" . (int)$case_option_id . "' AND po.case_id = '" . (int)$case_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

						if ($option_query->num_rows) {
							if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'radio' || $option_query->row['type'] == 'image') {
								$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "case_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.case_option_value_id = '" . (int)$option_value . "' AND pov.case_option_id = '" . (int)$case_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

								if ($option_value_query->num_rows) {
									if ($option_value_query->row['price_prefix'] == '+') {
										$option_price += $option_value_query->row['price'];
									} elseif ($option_value_query->row['price_prefix'] == '-') {
										$option_price -= $option_value_query->row['price'];
									}

									if ($option_value_query->row['points_prefix'] == '+') {
										$option_points += $option_value_query->row['points'];
									} elseif ($option_value_query->row['points_prefix'] == '-') {
										$option_points -= $option_value_query->row['points'];
									}

									if ($option_value_query->row['weight_prefix'] == '+') {
										$option_weight += $option_value_query->row['weight'];
									} elseif ($option_value_query->row['weight_prefix'] == '-') {
										$option_weight -= $option_value_query->row['weight'];
									}

									if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $quantity))) {
										$stock = false;
									}

									$option_data[] = array(
										'case_option_id'       => $case_option_id,
										'case_option_value_id' => $option_value,
										'option_id'               => $option_query->row['option_id'],
										'option_value_id'         => $option_value_query->row['option_value_id'],
										'name'                    => $option_query->row['name'],
										'option_value'            => $option_value_query->row['name'],
										'type'                    => $option_query->row['type'],
										'quantity'                => $option_value_query->row['quantity'],
										'subtract'                => $option_value_query->row['subtract'],
										'price'                   => $option_value_query->row['price'],
										'price_prefix'            => $option_value_query->row['price_prefix'],
										'points'                  => $option_value_query->row['points'],
										'points_prefix'           => $option_value_query->row['points_prefix'],									
										'weight'                  => $option_value_query->row['weight'],
										'weight_prefix'           => $option_value_query->row['weight_prefix']
									);								
								}
							} elseif ($option_query->row['type'] == 'checkbox' && is_array($option_value)) {
								foreach ($option_value as $case_option_value_id) {
									$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "case_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.case_option_value_id = '" . (int)$case_option_value_id . "' AND pov.case_option_id = '" . (int)$case_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

									if ($option_value_query->num_rows) {
										if ($option_value_query->row['price_prefix'] == '+') {
											$option_price += $option_value_query->row['price'];
										} elseif ($option_value_query->row['price_prefix'] == '-') {
											$option_price -= $option_value_query->row['price'];
										}

										if ($option_value_query->row['points_prefix'] == '+') {
											$option_points += $option_value_query->row['points'];
										} elseif ($option_value_query->row['points_prefix'] == '-') {
											$option_points -= $option_value_query->row['points'];
										}

										if ($option_value_query->row['weight_prefix'] == '+') {
											$option_weight += $option_value_query->row['weight'];
										} elseif ($option_value_query->row['weight_prefix'] == '-') {
											$option_weight -= $option_value_query->row['weight'];
										}

										if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $quantity))) {
											$stock = false;
										}

										$option_data[] = array(
											'case_option_id'       => $case_option_id,
											'case_option_value_id' => $case_option_value_id,
											'option_id'               => $option_query->row['option_id'],
											'option_value_id'         => $option_value_query->row['option_value_id'],
											'name'                    => $option_query->row['name'],
											'option_value'            => $option_value_query->row['name'],
											'type'                    => $option_query->row['type'],
											'quantity'                => $option_value_query->row['quantity'],
											'subtract'                => $option_value_query->row['subtract'],
											'price'                   => $option_value_query->row['price'],
											'price_prefix'            => $option_value_query->row['price_prefix'],
											'points'                  => $option_value_query->row['points'],
											'points_prefix'           => $option_value_query->row['points_prefix'],
											'weight'                  => $option_value_query->row['weight'],
											'weight_prefix'           => $option_value_query->row['weight_prefix']
										);								
									}
								}						
							} elseif ($option_query->row['type'] == 'text' || $option_query->row['type'] == 'textarea' || $option_query->row['type'] == 'file' || $option_query->row['type'] == 'date' || $option_query->row['type'] == 'datetime' || $option_query->row['type'] == 'time') {
								$option_data[] = array(
									'case_option_id'       => $case_option_id,
									'case_option_value_id' => '',
									'option_id'               => $option_query->row['option_id'],
									'option_value_id'         => '',
									'name'                    => $option_query->row['name'],
									'option_value'            => $option_value,
									'type'                    => $option_query->row['type'],
									'quantity'                => '',
									'subtract'                => '',
									'price'                   => '',
									'price_prefix'            => '',
									'points'                  => '',
									'points_prefix'           => '',								
									'weight'                  => '',
									'weight_prefix'           => ''
								);						
							}
						}
					} 

					if ($this->customer->isLogged()) {
						$customer_group_id = $this->customer->getCustomerGroupId();
					} else {
						$customer_group_id = $this->config->get('config_customer_group_id');
					}

					$price = $case_query->row['price'];

					// Case Discounts
					$discount_quantity = 0;

					foreach ($this->session->data['cart'] as $key_2 => $quantity_2) {
						$case_2 = explode(':', $key_2);

						if ($case_2[0] == $case_id) {
							$discount_quantity += $quantity_2;
						}
					}

					$case_discount_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "case_discount WHERE case_id = '" . (int)$case_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND quantity <= '" . (int)$discount_quantity . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");

					if ($case_discount_query->num_rows) {
						$price = $case_discount_query->row['price'];
					}

					// Case Specials
					$case_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "case_special WHERE case_id = '" . (int)$case_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");

					if ($case_special_query->num_rows) {
						$price = $case_special_query->row['price'];
					}						

					// Reward Points
					$case_reward_query = $this->db->query("SELECT points FROM " . DB_PREFIX . "case_reward WHERE case_id = '" . (int)$case_id . "' AND customer_group_id = '" . (int)$customer_group_id . "'");

					if ($case_reward_query->num_rows) {	
						$reward = $case_reward_query->row['points'];
					} else {
						$reward = 0;
					}

					// Downloads		
					$download_data = array();     		

					$download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_to_download p2d LEFT JOIN " . DB_PREFIX . "download d ON (p2d.download_id = d.download_id) LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE p2d.case_id = '" . (int)$case_id . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

					foreach ($download_query->rows as $download) {
						$download_data[] = array(
							'download_id' => $download['download_id'],
							'name'        => $download['name'],
							'filename'    => $download['filename'],
							'mask'        => $download['mask'],
							'remaining'   => $download['remaining']
						);
					}

					// Stock
					if (!$case_query->row['quantity'] || ($case_query->row['quantity'] < $quantity)) {
						$stock = false;
					}

					$recurring = false;
					$recurring_frequency = 0;
					$recurring_price = 0;
					$recurring_cycle = 0;
					$recurring_duration = 0;
					$recurring_trial_status = 0;
					$recurring_trial_price = 0;
					$recurring_trial_cycle = 0;
					$recurring_trial_duration = 0;
					$recurring_trial_frequency = 0;
					$profile_name = '';

					if ($profile_id) {
						$profile_info = $this->db->query("SELECT * FROM `" . DB_PREFIX . "profile` `p` JOIN `" . DB_PREFIX . "case_profile` `pp` ON `pp`.`profile_id` = `p`.`profile_id` AND `pp`.`case_id` = " . (int)$case_query->row['case_id'] . " JOIN `" . DB_PREFIX . "profile_description` `pd` ON `pd`.`profile_id` = `p`.`profile_id` AND `pd`.`language_id` = " . (int)$this->config->get('config_language_id') . " WHERE `pp`.`profile_id` = " . (int)$profile_id . " AND `status` = 1 AND `pp`.`customer_group_id` = " . (int)$customer_group_id)->row;

						if ($profile_info) {
							$profile_name = $profile_info['name'];

							$recurring = true;
							$recurring_frequency = $profile_info['frequency'];
							$recurring_price = $profile_info['price'];
							$recurring_cycle = $profile_info['cycle'];
							$recurring_duration = $profile_info['duration'];
							$recurring_trial_frequency = $profile_info['trial_frequency'];
							$recurring_trial_status = $profile_info['trial_status'];
							$recurring_trial_price = $profile_info['trial_price'];
							$recurring_trial_cycle = $profile_info['trial_cycle'];
							$recurring_trial_duration = $profile_info['trial_duration'];
						}
					}

					$this->data[$key] = array(
						'key'                       => $key,
						'case_id'                => $case_query->row['case_id'],
						'name'                      => $case_query->row['name'],
						'model'                     => $case_query->row['model'],
						'shipping'                  => $case_query->row['shipping'],
						'image'                     => $case_query->row['image'],
						'option'                    => $option_data,
						'download'                  => $download_data,
						'quantity'                  => $quantity,
						'minimum'                   => $case_query->row['minimum'],
						'subtract'                  => $case_query->row['subtract'],
						'stock'                     => $stock,
						'price'                     => ($price + $option_price),
						'total'                     => ($price + $option_price) * $quantity,
						'reward'                    => $reward * $quantity,
						'points'                    => ($case_query->row['points'] ? ($case_query->row['points'] + $option_points) * $quantity : 0),
						'tax_class_id'              => $case_query->row['tax_class_id'],
						'weight'                    => ($case_query->row['weight'] + $option_weight) * $quantity,
						'weight_class_id'           => $case_query->row['weight_class_id'],
						'length'                    => $case_query->row['length'],
						'width'                     => $case_query->row['width'],
						'height'                    => $case_query->row['height'],
						'length_class_id'           => $case_query->row['length_class_id'],
						'profile_id'                => $profile_id,
						'profile_name'              => $profile_name,
						'recurring'                 => $recurring,
						'recurring_frequency'       => $recurring_frequency,
						'recurring_price'           => $recurring_price,
						'recurring_cycle'           => $recurring_cycle,
						'recurring_duration'        => $recurring_duration,
						'recurring_trial'           => $recurring_trial_status,
						'recurring_trial_frequency' => $recurring_trial_frequency,
						'recurring_trial_price'     => $recurring_trial_price,
						'recurring_trial_cycle'     => $recurring_trial_cycle,
						'recurring_trial_duration'  => $recurring_trial_duration,
					);
				} else {
					$this->remove($key);
				}
			}
		}

		return $this->data;
	}

	public function getRecurringCases(){
		$recurring_cases = array();

		foreach ($this->getCases() as $key => $value) {
			if ($value['recurring']) {
				$recurring_cases[$key] = $value;
			}
		}

		return $recurring_cases;
	}

	public function add($case_id, $qty = 1, $option, $profile_id = '') {
		$key = (int)$case_id . ':';

		if ($option) {
			$key .= base64_encode(serialize($option)) . ':';
		}  else {
			$key .= ':';
		}

		if ($profile_id) {
			$key .= (int)$profile_id;
		}

		if ((int)$qty && ((int)$qty > 0)) {
			if (!isset($this->session->data['cart'][$key])) {
				$this->session->data['cart'][$key] = (int)$qty;
			} else {
				$this->session->data['cart'][$key] += (int)$qty;
			}
		}

		$this->data = array();
	}

	public function update($key, $qty) {
		if ((int)$qty && ((int)$qty > 0)) {
			$this->session->data['cart'][$key] = (int)$qty;
		} else {
			$this->remove($key);
		}

		$this->data = array();
	}

	public function remove($key) {
		if (isset($this->session->data['cart'][$key])) {
			unset($this->session->data['cart'][$key]);
		}

		$this->data = array();
	}

	public function clear() {
		$this->session->data['cart'] = array();
		$this->data = array();
	}

	public function getWeight() {
		$weight = 0;

		foreach ($this->getCases() as $case) {
			if ($case['shipping']) {
				$weight += $this->weight->convert($case['weight'], $case['weight_class_id'], $this->config->get('config_weight_class_id'));
			}
		}

		return $weight;
	}

	public function getSubTotal() {
		$total = 0;

		foreach ($this->getCases() as $case) {
			$total += $case['total'];
		}

		return $total;
	}

	public function getTaxes() {
		$tax_data = array();

		foreach ($this->getCases() as $case) {
			if ($case['tax_class_id']) {
				$tax_rates = $this->tax->getRates($case['price'], $case['tax_class_id']);

				foreach ($tax_rates as $tax_rate) {
					if (!isset($tax_data[$tax_rate['tax_rate_id']])) {
						$tax_data[$tax_rate['tax_rate_id']] = ($tax_rate['amount'] * $case['quantity']);
					} else {
						$tax_data[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $case['quantity']);
					}
				}
			}
		}

		return $tax_data;
	}

	public function getTotal() {
		$total = 0;

		foreach ($this->getCases() as $case) {
			$total += $this->tax->calculate($case['price'], $case['tax_class_id'], $this->config->get('config_tax')) * $case['quantity'];
		}

		return $total;
	}

	public function countCases() {
		$case_total = 0;

		$cases = $this->getCases();

		foreach ($cases as $case) {
			$case_total += $case['quantity'];
		}		

		return $case_total;
	}

	public function hasCases() {
		return count($this->session->data['cart']);
	}

	public function hasRecurringCases(){
		return count($this->getRecurringCases());
	}

	public function hasStock() {
		$stock = true;

		foreach ($this->getCases() as $case) {
			if (!$case['stock']) {
				$stock = false;
			}
		}

		return $stock;
	}

	public function hasShipping() {
		$shipping = false;

		foreach ($this->getCases() as $case) {
			if ($case['shipping']) {
				$shipping = true;

				break;
			}
		}

		return $shipping;
	}

	public function hasDownload() {
		$download = false;

		foreach ($this->getCases() as $case) {
			if ($case['download']) {
				$download = true;

				break;
			}
		}

		return $download;
	}	
}
?>