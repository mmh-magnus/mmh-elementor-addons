<?php
class Elementor_Woocommerce_Products_List extends \Elementor\Widget_Base {

	public function get_name()
	{
		return 'woocommerce-products-list';
	}

	public function get_title() {
		return __( 'MMH - Vörur síaðar eftir flokkum', 'text-domain' );
	}

	public function get_icon() {
		return 'eicon-posts-grid';
	}

	public function get_categories() {
		return [ 'basic' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'text-domain' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
	
		// Query for getting product categories
		$categories = get_terms('product_cat', array('hide_empty' => false));
		$options = [];
		if (!empty($categories) && !is_wp_error($categories)) {
			foreach ($categories as $category) {
				$options[$category->term_id] = $category->name;
			}
		}
	
		$this->add_control(
			'product_categories',
			[
				'label' => __( 'Select Categories', 'text-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $options,
				'description' => __( 'Select categories to show products from.', 'text-domain' ),
			]
		);
	
		$this->end_controls_section();
	}
	

	protected function render() {
		$settings = $this->get_settings_for_display();
		$selected_categories = $settings['product_categories'];
	
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => -1,
			'tax_query' => array(
				array(
					'taxonomy' => 'product_cat',
					'field' => 'term_id',
					'terms' => $selected_categories,
					'operator' => 'IN',
				),
			),
		);
	
		$query = new WP_Query($args);
	
		// Add custom styles
		echo '<style>
			.woocommerce ul.products li.product, .woocommerce-page ul.products li.product {
				box-shadow: 0 4px 10px rgba(0,0,0,0.15);
				border-radius: 5px;
				overflow: hidden;
			}
		</style>';
	
		if ($query->have_posts()) {
			woocommerce_product_loop_start();
	
			while ($query->have_posts()) {
				$query->the_post();
				wc_get_template_part('content', 'product');
			}
	
			woocommerce_product_loop_end();
			wp_reset_postdata();
		} else {
			do_action('woocommerce_no_products_found');
		}
	}
	
}
