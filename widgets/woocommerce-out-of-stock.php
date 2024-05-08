<?php
class Elementor_Woocommerce_Out_Of_Stock extends \Elementor\Widget_Base
{

	public function get_name()
	{
		return 'woocommerce-out-of-stock';
	}

	public function get_title()
	{
		return esc_html__('MMH - Sérpöntun form', 'elementor-addon');
	}

	public function get_icon()
	{
		return 'eicon-mail';
	}

	public function get_categories()
	{
		return ['basic'];
	}

	protected function _register_controls()
	{
		$this->start_controls_section(
			'content_section',
			[
				'label' => __('Settings', 'plugin-name'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'email_to',
			[
				'label' => __('To Email', 'plugin-name'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'email',
				'placeholder' => __('Enter recipient email', 'plugin-name'),
			]
		);

		$this->add_control(
			'email_subject',
			[
				'label' => __('Subject', 'plugin-name'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __('Enter email subject', 'plugin-name'),
			]
		);

		$this->add_control(
			'titel_header',
			[
				'label' => __('Header', 'plugin-name'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __('Title', 'plugin-name'),
			]
		);


		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$product = wc_get_product(get_queried_object_id());
	
		// Check if product is out of stock
		if (!$product || 'outofstock' !== $product->get_stock_status()) {
			return; // Exit the function if product is in stock or product does not exist
		}
	
		$form_submitted = false; // Flag to check if form was submitted
	
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_email'])) {
			$to = sanitize_email($settings['email_to']);
			$subject = sanitize_text_field($settings['email_subject']);
			$user_name = sanitize_text_field($_POST['name']);
			$user_email = sanitize_email($_POST['email']);
			$user_enquiry = sanitize_textarea_field($_POST['enquiry']);
			$message = "Nafn: " . $user_name . "<br>";
			$message .= "Netfang: " . $user_email . "<br>";
			$message .= "Vöruheiti: <a href='" . get_permalink($product->get_id()) . "'>" . $product->get_name() . "</a><br>"; // Including Product Link
			$message .= "Skilaboð: " . $user_enquiry . "<br>";
	
			$headers = ['Content-Type: text/html; charset=UTF-8'];
	
			if (wp_mail($to, $subject, $message, $headers)) {
				$form_submitted = true; // Set flag to true if email is sent successfully
				echo '<div style="margin-bottom: 25px;">Fyrirspurn hefur verið móttekin.</div>';
			}
		}
	
		if (!$form_submitted) { // Only display form if it hasn't been submitted yet
			echo '<div id="serpontun" style="margin-bottom: 25px;">';
			echo sanitize_text_field($settings['titel_header']);
			echo '</div>';
			echo '<form method="post">';
			echo '<input type="hidden" name="send_email" value="1">';
			echo '<div class="form-group" style="margin-bottom:15px;">';
			echo '<input type="text" name="name" placeholder="Nafn" class="form-control">';
			echo '</div>';
			echo '<div class="form-group" style="margin-bottom:15px;">';
			echo '<input type="email" name="email" placeholder="Netfang" class="form-control">';
			echo '</div>';
			echo '<div class="form-group" style="margin-bottom:15px;">';
			echo '<textarea name="enquiry" placeholder="Fyrirspurn" class="form-control" rows="3"></textarea>';
			echo '</div>';
			echo '<button type="submit">Senda</button>';
			echo '</form>';
		}
	}
	
	
}
