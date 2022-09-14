<?php

namespace WPC\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit; // Exit if accessed directly


class Listing extends Widget_Base {
	public function get_name(){
		return 'featured-listing';
	}

	public function get_title(){
		return 'Featured Listing';
	}

	public function get_icon(){
		return 'eicon-image-box';
	}

	public function get_categories(){
		return ['general'];
	}
	
	
	protected function register_controls(){
		
		//Header
		$this->start_controls_section(
			'header_section',
			[
				'label' => 'Header'
			]
		);
		$this->add_control(
			'image',
			[
				'label' => 'Choose Image',
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],

			]
		);
		
		$this->end_controls_section();
		
		
		//Body
		$this->start_controls_section(
			'body_section',
			[
				'label' => 'Body'
			]
		);
		$this->add_control(
			'listing_title',
			[
				'label' => 'Listing Title',
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'Listing title',
				'label_block' => true
			]
		);
		$this->add_control(
			'listing_price',
			[
				'label' => 'Listing Price',
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '$500.000',
				'label_block' => true
			]
		);
		$this->add_control(
			'hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);
		$this->add_control(
			'listing_body_button_link',
			[
				'label' => esc_html__( 'Listing Link' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com' ),
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => false,
					'custom_attributes' => '',
				],
			]
		);
		$this->end_controls_section();
		
		//List
		$this->start_controls_section(
			'listing_section',
			[
				'label' => 'List'
			]
		);
		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'repeater_list_title', [
				'label' => __( 'Text' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'List Item' ),
				'default' => esc_html__( 'List Item' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);
					
		$repeater->add_control(
			'repeater_list_icon',
			[
				'label' => esc_html__( 'Icon' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-check',
					'library' => 'fa-solid',
				],
			]
		);
		
		$this->add_control(
			'icon_list',
			[
				'label' => esc_html__( 'Icon List' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'repeater_list_title' => esc_html__( 'Location' ),
						'repeater_list_icon' => [
							'value' => 'fas fa-map-marker-alt',
							'library' => 'fa-solid',
						],
					], 
					[
						'repeater_list_title' => esc_html__( 'Size' ),
						'repeater_list_icon' => [
							'value' => 'fas fa-vector-square',
							'library' => 'fa-solid',
						],
					], 
					[
						'repeater_list_title' => esc_html__( 'Bedrooms' ),
						'repeater_list_icon' => [
							'value' => 'fas fa-bed',
							'library' => 'fa-solid',
						],
					], 
					[
						'repeater_list_title' => esc_html__( 'Bathrooms' ),
						'repeater_list_icon' => [
							'value' => 'fas fa-shower',
							'library' => 'fa-solid',
						],
					], 
				],
				'title_field' => '{{{ elementor.helpers.renderIcon( this, repeater_list_icon, {}, "i", "panel" ) || \'<i class="{{ icon }}" aria-hidden="true"></i>\' }}} {{{ repeater_list_title }}}',
			]
		);
		$this->end_controls_section();
	}
    

	protected function render(){
		?>
		<style>
			.listing_container{background:#fff;border-radius:3px}.listing_container a.listing_button{background:#050f17;white-space:nowrap;padding:17px 35px;border-radius:3px;font-family:var( --e-global-typography-text-font-family ),Sans-serif;font-size:var( --e-global-typography-accent-font-size );text-transform:uppercase;font-weight:500;color:#e8e8e8;display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-align:center;-ms-flex-align:center;align-items:center;gap:10px;-webkit-transition:.3s ease;-o-transition:.3s ease;transition:.3s ease}.listing_container a.listing_button:hover{color:var(--e-global-color-d26d406);background-color:var(--e-global-color-primary)}.listing_container .listing_body,.listing_container .listing_list{padding:20px 30px}.listing_container .listing_header{position:relative;display:-webkit-box;display:-ms-flexbox;display:flex}.listing_container .listing_top{position:relative;display:-webkit-box;display:-ms-flexbox;display:flex}.listing_container .listing_top a.listing_button{position:absolute;margin:30px;padding:14px 35px}.listing_container .listing_image{height:373px;width:100%;-o-object-fit:cover;object-fit:cover}.listing_container h3.title_text{font-family:var( --e-global-typography-secondary-font-family ),Sans-serif;color:var(--e-global-color-d26d406);margin-bottom:20px;font-size:var( --e-global-typography-secondary-font-size )}.listing_container .listing_inner_section{display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-pack:justify;-ms-flex-pack:justify;justify-content:space-between;-ms-flex-wrap:wrap;flex-wrap:wrap;gap:15px 30px}.listing_container .listing_inner_section>div{display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-ms-flex-direction:column;flex-direction:column;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center}.listing_container .pricing{font-family:var( --e-global-typography-text-font-family ),Sans-serif;font-size:18px;color:var(--e-global-color-accent)}.listing_container .listing_list i{color:var(--e-global-color-accent);display:block}.listing_container .listing_list{font-family:var( --e-global-typography-text-font-family ),Sans-serif;font-size:14px;font-weight:300}.listing_container .listing_list{display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-align:flex-start;-ms-flex-align:flex-start;align-items:flex-start;-ms-flex-wrap:wrap;flex-wrap:wrap}.listing_container .icon-text{color:var(--e-global-color-d26d406)}@media screen and (max-width:767px){.listing_container .listing_body,.listing_container .listing_list,.listing_container .listing_top .listing_button{padding:20px}}@media screen and (max-width:480px){.listing_container .listing_inner_section>div{width:100%}.listing_container a.listing_button{display:block;text-align:center}}.listing_container .listing_body{padding-bottom:0}.listing_list_text{color:var(--e-global-color-d26d406);width:100%;padding-left:20px;font-size:var( --e-global-typography-text-font-size )}.listing_container .listing_list .listing_list_item{width:22.75%;margin-right:3%;position:relative}.listing_container .listing_list{width:100%}.listing_container .listing_list .listing_list_item:last-child{margin-right:0}.listing_list_icon{position:absolute;top:5px;left:0}
		</style>
		<?php
		
		
		$settings = $this->get_settings_for_display();
		
		// Get image HTML
		$this->add_render_attribute( 'image', 'src', $settings['image']['url'] );
		$this->add_render_attribute( 'image', 'alt', \Elementor\Control_Media::get_image_alt( $settings['image'] ) );
		$this->add_render_attribute( 'image', 'title', \Elementor\Control_Media::get_image_title( $settings['image'] ) );
		$this->add_render_attribute( 'image', 'class', 'listing_image' );

		$this->add_inline_editing_attributes('listing_title', 'basic');
		$this->add_render_attribute(
			'listing_title',
			[
				'class' => ['listing__listing-title'],
			]
		);		
		
		//View more button
		if ( ! empty( $settings['listing_body_button_link']['url'] ) ) {
			$this->add_link_attributes( 'listing_body_button_link', $settings['listing_body_button_link'] );
		}
		?>
		<!-- Frontend -->
		<div class="listing_container">
			<div class="listing_top">
				<img <?php echo $this->get_render_attribute_string('image'); ?> />
			</div>
			<div class="listing_body">
				<div class="listing_inner_section">
					<div class="text_container">
						<h3 class="title_text" <?php echo $this->get_render_attribute_string('listing_title'); ?>><?php echo $settings['listing_title']?></h3>
						<div class="pricing" <?php echo $this->get_render_attribute_string('listing_price'); ?>>
							<?php echo $settings['listing_price'] ?>
						</div>
					</div>
					<div class="button_container">
						<a class="listing_button" <?php echo $this->get_render_attribute_string( 'listing_body_button_link' ); ?>>View Listing <i class="fas fa-chevron-right"></i></a>
					</div>
				</div>
			</div>
			<div class="listing_list">

				<?php 
				if ( $settings['icon_list'] ) {
					foreach ( $settings['icon_list'] as $item ) {
				?>
				<div class="listing_list_item">
					<div class="listing_list_icon"><?php \Elementor\Icons_Manager::render_icon( $item['repeater_list_icon'], [ 'aria-hidden' => 'true' ] ); ?></div>
					<div class="listing_list_text"><?php echo $item['repeater_list_title']; ?></div>
				</div>
				<?php
					}
				}
				?>
			</div>
		</div>
		
		<?php
	}

    protected function content_template() { ?>
		<#
			if ( settings.image.url ) {
				var image = {
					id: settings.image.id,
					url: settings.image.url,
					size: settings.image_size,
					dimension: settings.image_custom_dimension,
					model: view.getEditModel()
				};

				var image_url = elementor.imagesManager.getImageUrl( image );

				if ( ! image_url ) {
					return;
				}
			}
		#>

		<!-- Backend -->
		<div class="listing_container">
			<div class="listing_top">
				<img src="{{ image_url }}" class="listing_image">
			</div>
			<div class="listing_body">
				<div class="listing_inner_section">
					<div class="text_container">
						<h3 class="title_text">{{{ settings.listing_title }}}</h3>
						<div class="pricing">{{{ settings.listing_price }}}</div>
					</div>
					<div class="button_container">
						<a href="{{settings.listing_body_button_link.url}}" class="listing_button">View More <i class="fas fa-chevron-right"></i></a>
					</div>
				</div>
			</div>	
			<div class="listing_list">
					<# var iconHTML = elementor.helpers.renderIcon( view, settings.repeater_list_icon, { 'aria-hidden': true }, 'i' , 'object' ); #>
					
					<# _.each( settings.icon_list, function( item ) { #>
						<div class="listing_list_item elementor-repeater-item-{{ item._id }}">
							<div class="listing_list_item">
								<div class="listing_list_icon"><i class="{{ item.repeater_list_icon.value }}"></i></div>
								<div class="listing_list_text">{{{ item.repeater_list_title }}}</div>
							</div>
						</div>
					<# }); #>
			</div>
		</div>
						
		<?php
	}
}
