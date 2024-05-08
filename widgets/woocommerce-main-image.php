<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Elementor_Woocommerce_Main_Image extends Widget_Base {

    public function get_name() {
        return 'woocommerce-main-image';
    }

    public function get_title() {
        return __('MMH - Aðalvörumynd', 'mmh-elementor-addon');
    }

    public function get_icon() {
        return 'eicon-product-images';
    }

    public function get_categories() {
        return ['basic'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_main_image',
            [
                'label' => __('Main Image Settings', 'mmh-elementor-addon'),
            ]
        );

        $this->add_control(
            'image_size',
            [
                'label' => __('Image Size', 'mmh-elementor-addon'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'thumbnail' => __('Thumbnail', 'mmh-elementor-addon'),
                    'medium' => __('Medium', 'mmh-elementor-addon'),
                    'large' => __('Large', 'mmh-elementor-addon'),
                    'full' => __('Full', 'mmh-elementor-addon'),
                ],
                'default' => 'large',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        global $product;

        $product = wc_get_product();

        if (!$product) {
            return;
        }

        $settings = $this->get_settings_for_display();
        $image_size = $settings['image_size'];

        // Display main product image with a unique ID
        if (has_post_thumbnail($product->get_id())) {
            echo '<div class="woocommerce-main-product-image">';
            echo get_the_post_thumbnail($product->get_id(), $image_size, ['id' => 'main-product-image']);
            echo '</div>';
        }

        // JavaScript for receiving update event from thumbnails widget
        ?>
        <script>
            jQuery(document).ready(function() {
                jQuery(document).on('click', '.product-thumbnail', function() {
                    var fullImage = jQuery(this).data('full');
                    jQuery('#main-product-image').attr('src', fullImage).attr('srcset', '').attr('sizes', '');
                });
            });
        </script>
        <?php
    }

    public function get_script_depends() {
        return ['jquery'];
    }

    function add_elementor_widget_categories( $elements_manager ) {
        // Adding new MMH category
        $elements_manager->add_category(
            'mmh-category',
            [
                'title' => esc_html__( 'MMH', 'textdomain' ),
                'icon' => 'fa fa-plug',
            ]
        );
    }
    
    // Hooking the function to Elementor's category registration action
    
}
