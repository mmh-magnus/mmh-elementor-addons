<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Elementor_Woocommerce_Thumbnails extends Widget_Base {

    public function get_name() {
        return 'woocommerce-thumbnails';
    }

    public function get_title() {
        return __('MMH - Vörumyndir smáar', 'mmh-elementor-addon');
    }

    public function get_icon() {
        return 'eicon-product-images';
    }

    public function get_categories() {
        return ['basic'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_thumbnails',
            [
                'label' => __('Thumbnails Settings', 'mmh-elementor-addon'),
            ]
        );
    
        $this->add_control(
            'max_thumbnails',
            [
                'label' => __('Maximum Thumbnails', 'mmh-elementor-addon'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 0, // 0 to show all images
            ]
        );
    
        $this->add_control(
            'thumbnail_size',
            [
                'label' => __('Thumbnail Size', 'mmh-elementor-addon'),
                'type' => Controls_Manager::SELECT,
                'default' => 'thumbnail',
                'options' => [
                    'thumbnail' => __('Thumbnail', 'mmh-elementor-addon'),
                    'medium' => __('Medium', 'mmh-elementor-addon'),
                    'large' => __('Large', 'mmh-elementor-addon'),
                    'full' => __('Full', 'mmh-elementor-addon'),
                ],
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
        $max_thumbnails = $settings['max_thumbnails'];
        $thumbnail_size = $settings['thumbnail_size'];
    
        // Add inline styles
        echo '<style>
            .woocommerce-product-gallery-thumbnails {
                display: flex;
                flex-wrap: wrap;
                gap: 10px; /* Adjust gap between thumbnails */
            }
    
            .woocommerce-product-gallery-thumbnails img {
                max-width: 100px; /* Adjust as needed */
                max-height: 100px; /* Adjust as needed */
            }
        </style>';
    
        // Display thumbnail images in a row with a limit
        $attachment_ids = $product->get_gallery_image_ids();
        if (!empty($attachment_ids)) {
            echo '<div class="woocommerce-product-gallery-thumbnails">';
            $counter = 0;
            foreach ($attachment_ids as $attachment_id) {
                if ($max_thumbnails > 0 && $counter >= $max_thumbnails) {
                    break;
                }
                $thumbnail_url = wp_get_attachment_image_url($attachment_id, $thumbnail_size);
                $full_url = wp_get_attachment_image_url($attachment_id, 'full');
                echo '<img src="' . esc_url($thumbnail_url) . '" class="product-thumbnail" data-full="' . esc_url($full_url) . '" style="cursor: pointer;">';
                $counter++;
            }
            echo '</div>';
        }
    }
    
    

    public function get_script_depends() {
        return ['jquery'];
    }
}
