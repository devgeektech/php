<?php
class ControllerCheckoutCart extends Controller {
	public function index() {
// Clear Thinking: MailChimp Integration Pro
				if (isset($this->request->get['c'])) {
					foreach ($this->request->get['c'] as $product) {
						$options = (!empty($product['o'])) ? $product['o'] : array();
						$recurring = (!empty($product['r'])) ? $product['r'] : 0;
						$this->cart->add($product['p'], $product['q'], $options, $recurring);
					}
					$this->response->redirect($this->url->link('checkout/cart'));
				}
				// end
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('checkout/cart', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('checkout/cart');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home'),
			'text' => $this->language->get('text_home')
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('checkout/cart'),
			'text' => $this->language->get('heading_title')
		);

		if ($this->cart->hasProducts() || !empty($this->session->data['vouchers'])) {
			if (!$this->cart->hasStock() && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
				$data['error_warning'] = $this->language->get('error_stock');
			} elseif (isset($this->session->data['error'])) {
				$data['error_warning'] = $this->session->data['error'];

				unset($this->session->data['error']);
			} else {
				$data['error_warning'] = '';
			}

			if ($this->config->get('config_customer_price') && !$this->customer->isLogged()) {
				$data['attention'] = sprintf($this->language->get('text_login'), $this->url->link('account/login'), $this->url->link('account/register'));
			} else {
				$data['attention'] = '';
			}

			if (isset($this->session->data['success'])) {
				$data['success'] = $this->session->data['success'];

				unset($this->session->data['success']);
			} else {
				$data['success'] = '';
			}

			$data['action'] = $this->url->link('checkout/cart/edit', '', true);

			if ($this->config->get('config_cart_weight')) {
				$data['weight'] = $this->weight->format($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
			} else {
				$data['weight'] = '';
			}

			$this->load->model('tool/image');
			$this->load->model('tool/upload');

			$data['products'] = array();

			$products = $this->cart->getProducts();


                /***check mandatory products***/
                $this->load->model('catalog/product');
                $man_product = $this->model_catalog_product->satisfied_mandatory_purchases_all($this->customer->harvestId());
                if(!empty($man_product['mandatory_product_id']) && empty($man_product['found_in_past_order'])) {
                    $this->response->redirect($this->url->link('product/product', 'product_id=' . $man_product['mandatory_product_id']));
                }
                /***check mandatory products***/
            
			foreach ($products as $product) {
				$product_total = 0;

				foreach ($products as $product_2) {
					if ($product_2['product_id'] == $product['product_id']) {
						$product_total += $product_2['quantity'];
					}
				}

				if ($product['minimum'] > $product_total) {
					$data['error_warning'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
				}

				if ($product['image']) {
					$image = $this->model_tool_image->resize($product['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_cart_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_cart_height'));
				} else {
					$image = '';
				}

				$option_data = array();

				foreach ($product['option'] as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$value = $upload_info['name'];
						} else {
							$value = '';
						}
					}

					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
				}

				// Display prices
				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$unit_price = $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'));
					
					$price = $this->currency->format($unit_price, $this->session->data['currency']);
					$total = $this->currency->format($unit_price * $product['quantity'], $this->session->data['currency']);
				} else {
					$price = false;
					$total = false;
				}

				$recurring = '';

				if ($product['recurring']) {
					$frequencies = array(
						'day'        => $this->language->get('text_day'),
						'week'       => $this->language->get('text_week'),
						'semi_month' => $this->language->get('text_semi_month'),
						'month'      => $this->language->get('text_month'),
						'year'       => $this->language->get('text_year')
					);

					if ($product['recurring']['trial']) {
						$recurring = sprintf($this->language->get('text_trial_description'), $this->currency->format($this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration']) . ' ';
					}

					if ($product['recurring']['duration']) {
						$recurring .= sprintf($this->language->get('text_payment_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
					} else {
						$recurring .= sprintf($this->language->get('text_payment_cancel'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
					}
				}

				$data['products'][] = array(
					'cart_id'   => $product['cart_id'],
					'thumb'     => $image,
					'name'      => $product['name'],
					'model'     => $product['model'],
					'option'    => $option_data,
					'recurring' => $recurring,
					'quantity'  => $product['quantity'],
					'stock'     => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
					'reward'    => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
					'price'     => $price,
					'total'     => $total,
					'href'      => $this->url->link('product/product', 'product_id=' . $product['product_id'])
				);
			}

			// Gift Voucher
			$data['vouchers'] = array();

			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $key => $voucher) {
					$data['vouchers'][] = array(
						'key'         => $key,
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount'], $this->session->data['currency']),
						'remove'      => $this->url->link('checkout/cart', 'remove=' . $key)
					);
				}
			}

			// Totals
			$this->load->model('setting/extension');

			$totals = array();
			$taxes = $this->cart->getTaxes();
			$total = 0;
			
			// Because __call can not keep var references so we put them into an array. 			
			$total_data = array(
				'totals' => &$totals,
				'taxes'  => &$taxes,
				'total'  => &$total
			);
			
			// Display prices
			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				$sort_order = array();

				$results = $this->model_setting_extension->getExtensions('total');

				foreach ($results as $key => $value) {
					$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
				}

				array_multisort($sort_order, SORT_ASC, $results);

				foreach ($results as $result) {
					if ($this->config->get('total_' . $result['code'] . '_status')) {
						$this->load->model('extension/total/' . $result['code']);
						
						// We have to put the totals in an array so that they pass by reference.
						$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
					}
				}

				$sort_order = array();

				foreach ($totals as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}

				array_multisort($sort_order, SORT_ASC, $totals);
			}

			$data['totals'] = array();

			foreach ($totals as $total) {
				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $this->session->data['currency'])
				);
			}

			$data['continue'] = $this->url->link('common/home');

			$data['checkout'] = $this->url->link('checkout/checkout', '', true);

			$this->load->model('setting/extension');

			$data['modules'] = array();
			
			$files = glob(DIR_APPLICATION . '/controller/extension/total/*.php');

			if ($files) {
				foreach ($files as $file) {
					$result = $this->load->controller('extension/total/' . basename($file, '.php'));
					
					if ($result) {
						$data['modules'][] = $result;
					}
				}
			}

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('checkout/cart', $data));
		} else {
			$data['text_error'] = $this->language->get('text_empty');
			
			$data['continue'] = $this->url->link('common/home');

			unset($this->session->data['success']);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}


                 public function checkout_thanks() {
                    $this->load->model('tool/image');
                    $data['breadcrumbs'] = array();

                    $data['breadcrumbs'][] = array(
                        'text' => $this->language->get('text_home'),
                        'href' => $this->url->link('common/home')
                    );
                    $data['breadcrumbs'][] = array(
                        'text' => 'Checkout Thanks',
                        'href' => $this->url->link('checkout/cart/checkout_thanks')
                    );
                    $this->load->model('tool/upload');
                    $data['heading_title'] = 'Checkout Thanks';
                    $this->load->language('checkout/cart');
                    $cart_array = $this->cart->getProducts();

                    /***check mandatory products***/
                    $this->load->model('catalog/product');
                    $man_product = $this->model_catalog_product->satisfied_mandatory_purchases_all($this->customer->harvestId());
                    if(!empty($man_product['mandatory_product_id']) && empty($man_product['found_in_past_order'])) {
                        $this->response->redirect($this->url->link('product/product', 'product_id=' . $man_product['mandatory_product_id']));
                    }
                    /***check mandatory products***/
                    if ($cart_array) {
                        //$data['cart_array'] = $cart_array;

                        foreach ($cart_array as $product) {
                            $product_total = 0;

                            foreach ($cart_array as $product_2) {
                                if ($product_2['product_id'] == $product['product_id']) {
                                    $product_total += $product_2['quantity'];
                                }
                            }

                            if ($product['minimum'] > $product_total) {
                                $data['error_warning'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
                            }

                            if ($product['image']) {
                                $image = $this->model_tool_image->resize($product['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_cart_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_cart_height'));
                            } else {
                                $image = '';
                            }

                            $option_data = array();

                            foreach ($product['option'] as $option) {
                                if ($option['type'] != 'file') {
                                    $value = $option['value'];
                                } else {
                                    $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                                    if ($upload_info) {
                                        $value = $upload_info['name'];
                                    } else {
                                        $value = '';
                                    }
                                }

                                $option_data[] = array(
                                    'name' => $option['name'],
                                    'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                                );
                            }

                            // Display prices
                            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                                $unit_price = $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'));

                                $price = $this->currency->format($unit_price, $this->session->data['currency']);
                                $total = $this->currency->format($unit_price * $product['quantity'], $this->session->data['currency']);
                            } else {
                                $price = false;
                                $total = false;
                            }

                            $recurring = '';

                            if ($product['recurring']) {
                                $frequencies = array(
                                    'day' => $this->language->get('text_day'),
                                    'week' => $this->language->get('text_week'),
                                    'semi_month' => $this->language->get('text_semi_month'),
                                    'month' => $this->language->get('text_month'),
                                    'year' => $this->language->get('text_year')
                                );

                                if ($product['recurring']['trial']) {
                                    $recurring = sprintf($this->language->get('text_trial_description'), $this->currency->format($this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration']) . ' ';
                                }

                                if ($product['recurring']['duration']) {
                                    $recurring .= sprintf($this->language->get('text_payment_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
                                } else {
                                    $recurring .= sprintf($this->language->get('text_payment_cancel'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
                                }
                            }

                            $data['cart_products'][] = array(
                                'cart_id' => $product['cart_id'],
                                'thumb' => $image,
                                'name' => $product['name'],
                                'model' => $product['model'],
                                'option' => $option_data,
                                'recurring' => $recurring,
                                'quantity' => $product['quantity'],
                                'stock' => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
                                'reward' => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
                                'price' => $price,
                                'total' => $total,
                                'href' => $this->url->link('product/product', 'product_id=' . $product['product_id'])
                            );
                        }

                        if (isset($this->session->data['success'])) {
                            $data['cart_success'] = $this->session->data['success'];

                            unset($this->session->data['success']);
                        } else {
                            $data['cart_success'] = '';
                        }
                        $data['cart_action'] = $this->url->link('checkout/cart/edit', '', true);
                        // Totals
                        $this->load->model('setting/extension');

                        $totals = array();
                        $taxes = $this->cart->getTaxes();
                        $total = 0;

                        // Because __call can not keep var references so we put them into an array.
                        $total_data = array(
                            'totals' => &$totals,
                            'taxes' => &$taxes,
                            'total' => &$total
                        );

                        // Display prices
                        if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                            $sort_order = array();

                            $results = $this->model_setting_extension->getExtensions('total');

                            foreach ($results as $key => $value) {
                                $sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
                            }

                            array_multisort($sort_order, SORT_ASC, $results);

                            foreach ($results as $result) {
                                if ($this->config->get('total_' . $result['code'] . '_status')) {
                                    $this->load->model('extension/total/' . $result['code']);

                                    // We have to put the totals in an array so that they pass by reference.
                                    $this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
                                }
                            }

                            $sort_order = array();

                            foreach ($totals as $key => $value) {
                                $sort_order[$key] = $value['sort_order'];
                            }

                            array_multisort($sort_order, SORT_ASC, $totals);
                        }

                        $data['totals'] = array();

                        foreach ($totals as $total) {
                            $data['totals'][] = array(
                                'title' => $total['title'],
                                'text' => $this->currency->format($total['value'], $this->session->data['currency'])
                            );
                        }

                        $data['continue'] = $this->url->link('product/category', 'path=96', true);
                        $data['checkout'] = $this->url->link('checkout/checkout', '', true);
                    }
                    $data['column_left'] = $this->load->controller('common/column_left');
                    $data['column_right'] = $this->load->controller('common/column_right');
                    $data['content_top'] = $this->load->controller('common/content_top');
                    $data['content_bottom'] = $this->load->controller('common/content_bottom');
                    $data['footer'] = $this->load->controller('common/footer');
                    $data['header'] = $this->load->controller('common/header');

                    $this->response->setOutput($this->load->view('checkout/checkout_thanks', $data));
                }
            
	public function add() {
		$this->load->language('checkout/cart');

		$json = array();

		if (isset($this->request->post['product_id'])) {
			$product_id = (int)$this->request->post['product_id'];
		} else {
			$product_id = 0;
		}

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);

		if ($product_info) {
			if (isset($this->request->post['quantity'])) {
				$quantity = (int)$this->request->post['quantity'];
			} else {
				$quantity = 1;
			}

			if (isset($this->request->post['option'])) {
				$option = array_filter($this->request->post['option']);
			} else {
				$option = array();
			}

			$product_options = $this->model_catalog_product->getProductOptions($this->request->post['product_id']);

			foreach ($product_options as $product_option) {
				if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
					$json['error']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
				}
			}

			if (isset($this->request->post['recurring_id'])) {
				$recurring_id = $this->request->post['recurring_id'];
			} else {
				$recurring_id = 0;
			}

			$recurrings = $this->model_catalog_product->getProfiles($product_info['product_id']);

			if ($recurrings) {
				$recurring_ids = array();

				foreach ($recurrings as $recurring) {
					$recurring_ids[] = $recurring['recurring_id'];
				}

				if (!in_array($recurring_id, $recurring_ids)) {
					$json['error']['recurring'] = $this->language->get('error_recurring_required');
				}
			}

			if (!$json) {
				$this->cart->add($this->request->post['product_id'], $quantity, $option, $recurring_id);
// Clear Thinking: MailChimp Integration Pro
				$prefix = (version_compare(VERSION, '3.0', '<')) ? '' : 'module_';
				if ($this->config->get($prefix . 'mailchimp_integration_sendcarts') && ($this->customer->isLogged() || !empty($this->session->data['mailchimp_signup_email']))) {
					$customer_id = ($this->customer->isLogged()) ? (int)$this->customer->getId() : $this->session->data['mailchimp_signup_email'];
					if (version_compare(VERSION, '2.1', '<')) $this->load->library('mailchimp_integration');
					$mailchimp_integration = new MailChimp_Integration($this->registry);
					$mailchimp_integration->sendCart($this->cart, $customer_id);
				}
				// end

				$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('checkout/cart'));

				$json['cart_div'] = '<div class="block-heading">Your Shopping Cart</div>
                <form action="{{ cart_action }}" method="post" enctype="multipart/form-data">
                <ul class="cart-list">
                {% for cart in cart_products %}
                <li class="cart-list-item">
                    <h4>{{ cart.name }} <button type="button" data-toggle="tooltip" title="{{ button_remove }}" class="btn btn-danger" onclick="cart.remove("{{ cart.cart_id }});"><i class="fa fa-times-circle"></i></button></h4>
                    <p>{{ cart.model }}</p>
                    <div class="form-group">
                    <input type="text" name="quantity[{{ cart.cart_id }}]" value="{{ cart.quantity }}" size="1" class="form-control" />
                    <strong>{{ cart.total }}</strong>
                  <input type="submit" data-toggle="tooltip" title="{{ button_update }}" value="Update" style="background: none; border: none; padding: 0px; text-decoration: underline; float: right; padding-top: 7px;">
                    </div>
                </li>
                {% endfor %}
                    </ul>
                </form>
                <div class="cartTotal">
                {% for total in totals %}
                <p class="{{ total.title }}"><span>{{ total.title }}:</span> {{ total.text }}</p>
            {% endfor %}
            </div>
            <br/>
            <a href="{{ checkout }}" class="primary-cta" style="width: 100%;">{{ button_checkout }}</a><br/><br/>
            <a href="{{ continue }}" class="primary-cta" style="width: 100%;">{{ button_shopping }}</a>
                ';
				// Unset all shipping and payment methods
				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);

				// Totals
				$this->load->model('setting/extension');

				$totals = array();
				$taxes = $this->cart->getTaxes();
				$total = 0;
		
				// Because __call can not keep var references so we put them into an array. 			
				$total_data = array(
					'totals' => &$totals,
					'taxes'  => &$taxes,
					'total'  => &$total
				);

				// Display prices
				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$sort_order = array();

					$results = $this->model_setting_extension->getExtensions('total');

					foreach ($results as $key => $value) {
						$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
					}

					array_multisort($sort_order, SORT_ASC, $results);

					foreach ($results as $result) {
						if ($this->config->get('total_' . $result['code'] . '_status')) {
							$this->load->model('extension/total/' . $result['code']);

							// We have to put the totals in an array so that they pass by reference.
							$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
						}
					}

					$sort_order = array();

					foreach ($totals as $key => $value) {
						$sort_order[$key] = $value['sort_order'];
					}

					array_multisort($sort_order, SORT_ASC, $totals);
				}

				$json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total, $this->session->data['currency']));
			} else {
				$json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']));
			}
		}


                /***check mandatory products***/
                if ($this->customer->isLogged()) { // if customer is logged in then check else show them description page
                    $man_product = $this->model_catalog_product->satisfied_mandatory_purchases_all($this->customer->harvestId());
                } else {
                    $man_product = array('mandatory_products' => []);
                }
                $man_product['mandatory_products'];
                $json['next_product_id'] = '';
                if (empty($man_product['found_in_past_order'])) { //mandatory product case
                    foreach ($man_product['mandatory_products'] as $key => $mandatory_product) {
                        if ($product_id == $mandatory_product['product_id']) {
                            if (isset($man_product['mandatory_products'][$key + 1]['product_id'])) {
                                $json['next_product_id'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $man_product['mandatory_products'][$key + 1]['product_id']));
                                break;
                            }
                        }
                    }
                    if(empty($json['next_product_id'])) { //if all suggested products is added or continue then open checkout thanks
                        $json['next_product_id'] = $this->url->link('checkout/cart/checkout_thanks', '', true);
                    }
                }
                /***check mandatory products***/
            
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function edit() {
		$this->load->language('checkout/cart');

		$json = array();

		// Update
		if (!empty($this->request->post['quantity'])) {
			foreach ($this->request->post['quantity'] as $key => $value) {
				$this->cart->update($key, $value);
			}
// Clear Thinking: MailChimp Integration Pro
				$prefix = (version_compare(VERSION, '3.0', '<')) ? '' : 'module_';
				if ($this->config->get($prefix . 'mailchimp_integration_sendcarts') && ($this->customer->isLogged() || !empty($this->session->data['mailchimp_signup_email']))) {
					$customer_id = ($this->customer->isLogged()) ? (int)$this->customer->getId() : $this->session->data['mailchimp_signup_email'];
					if (version_compare(VERSION, '2.1', '<')) $this->load->library('mailchimp_integration');
					$mailchimp_integration = new MailChimp_Integration($this->registry);
					$mailchimp_integration->sendCart($this->cart, $customer_id);
				}
				// end

			$this->session->data['success'] = $this->language->get('text_remove');

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['reward']);

			$this->response->redirect($this->url->link('checkout/cart'));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function remove() {
		$this->load->language('checkout/cart');

		$json = array();

		// Remove
		if (isset($this->request->post['key'])) {
			$this->cart->remove($this->request->post['key']);
// Clear Thinking: MailChimp Integration Pro
				$prefix = (version_compare(VERSION, '3.0', '<')) ? '' : 'module_';
				if ($this->config->get($prefix . 'mailchimp_integration_sendcarts') && ($this->customer->isLogged() || !empty($this->session->data['mailchimp_signup_email']))) {
					$customer_id = ($this->customer->isLogged()) ? (int)$this->customer->getId() : $this->session->data['mailchimp_signup_email'];
					if (version_compare(VERSION, '2.1', '<')) $this->load->library('mailchimp_integration');
					$mailchimp_integration = new MailChimp_Integration($this->registry);
					$mailchimp_integration->sendCart($this->cart, $customer_id);
				}
				// end

			unset($this->session->data['vouchers'][$this->request->post['key']]);

			$json['success'] = $this->language->get('text_remove');

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['reward']);

			// Totals
			$this->load->model('setting/extension');

			$totals = array();
			$taxes = $this->cart->getTaxes();
			$total = 0;

			// Because __call can not keep var references so we put them into an array. 			
			$total_data = array(
				'totals' => &$totals,
				'taxes'  => &$taxes,
				'total'  => &$total
			);

			// Display prices
			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				$sort_order = array();

				$results = $this->model_setting_extension->getExtensions('total');

				foreach ($results as $key => $value) {
					$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
				}

				array_multisort($sort_order, SORT_ASC, $results);

				foreach ($results as $result) {
					if ($this->config->get('total_' . $result['code'] . '_status')) {
						$this->load->model('extension/total/' . $result['code']);

						// We have to put the totals in an array so that they pass by reference.
						$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
					}
				}

				$sort_order = array();

				foreach ($totals as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}

				array_multisort($sort_order, SORT_ASC, $totals);
			}

			$json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total, $this->session->data['currency']));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
