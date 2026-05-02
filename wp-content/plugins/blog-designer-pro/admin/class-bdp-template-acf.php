<?php
/**
 * The admin-facing functionality of the plugin.
 *
 * @link       https://www.solwininfotech.com/
 * @since      1.0.0
 *
 * @package    Blog_Designer_PRO
 * @subpackage Blog_Designer_PRO/admin
 * @author     Solwin Infotech <info@solwininfotech.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Blog Designer PRO Backend Functions Class.
 *
 * @class   Bdp_Template_Acf
 * @version 1.0.0
 */
class Bdp_Template_Acf {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		/**
		 * Add ACF Google map api key
		 *
		 * @since 2.5.1
		 * @param $api
		 */
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		if ( is_plugin_active( 'advanced-custom-fields/acf.php' ) ) {
			add_action( 'bdp_after_single_post_content_data', array( $this, 'display_acf_fields' ), 10, 2 );
			add_action( 'bdp_after_blog_post_content_data', array( $this, 'blog_display_acf_fields' ), 20, 2 );
		}
		add_action( 'init', array( $this, 'is_acf_plugin' ) );
		add_action( 'wp_ajax_nopriv_get_acf_field_list', array( $this, 'get_acf_field_list' ) );
		add_action( 'wp_ajax_get_acf_field_list', array( $this, 'get_acf_field_list' ) );
	}
	/**
	 * Check Advance custom field plugin active
	 *
	 * @since 2.5.1
	 */
	public static function is_acf_plugin() {
		if ( is_plugin_active( 'advanced-custom-fields/acf.php' ) ) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Add script for ACF plugin
	 *
	 * @since 2.5.1
	 * @param array $bdp_settings settings.
	 * @param int   $post_id post id.
	 * @return void
	 */
	public function display_acf_fields( $bdp_settings, $post_id ) {
		$groups       = acf_get_field_groups( array( 'post_id' => $post_id ) );
		$all_acf_data = array();
		foreach ( $groups as $group ) {
			$group_id                                 = $group['ID'];
			$group_title                              = $group['title'];
			$all_acf_data[ $group_id ]                = array();
			$all_acf_data[ $group_id ]['group_id']    = $group_id;
			$all_acf_data[ $group_id ]['group_title'] = $group_title;
			$fields                                   = acf_get_fields( $group_id );
			if ( $fields ) {
				$all_acf_data[ $group_id ]['fields'] = array();
				$val_fields                          = 0;
				foreach ( $fields as $field ) {
					$field_id              = $field['ID'];
					$field_label           = $field['label'];
					$field_key             = $field['key'];
					$field_value           = get_field( $field_key );
					$field_type            = get_field_object( $field_key );
					$bdp_all_acf_field     = isset( $bdp_settings['bdp_acf_field'] ) ? $bdp_settings['bdp_acf_field'] : array();
					$bdp_acf_field_display = '';
					if ( ! empty( $bdp_all_acf_field ) ) {
						foreach ( $bdp_all_acf_field as $bdp_acf_field ) {
							$bdp_acf_field_display = $bdp_acf_field;
							if ( $field_id == $bdp_acf_field_display ) {
								if ( '' != $field_value || 'true_false' === $field_type['type'] || 'color_picker' === $field_type['type'] || 'message' === $field_type['type'] || 'accordion' === $field_type['type'] || 'google_map' === $field_type['type'] || 'group' === $field_type['type'] || 'password' === $field_type['type'] ) {
									$val_fields                                       = 1;
									$all_acf_data[ $group_id ]['fields'][ $field_id ] = array();
									$all_acf_data[ $group_id ]['fields'][ $field_id ]['field_label'] = $field_label;
									$all_acf_data[ $group_id ]['fields'][ $field_id ]['field_value'] = $field_value;
									$all_acf_data[ $group_id ]['fields'][ $field_id ]['field_type']  = $field_type;
								} else {
									unset( $all_acf_data[ $group_id ]['fields'] );
								}
							}
						}
					} elseif ( '' != $field_value || 'true_false' === $field_type['type'] || 'color_picker' === $field_type['type'] || 'message' === $field_type['type'] || 'accordion' === $field_type['type'] || 'google_map' === $field_type['type'] || 'group' === $field_type['type'] || 'password' === $field_type['type'] ) {
							$val_fields                                       = 1;
							$all_acf_data[ $group_id ]['fields'][ $field_id ] = array();
							$all_acf_data[ $group_id ]['fields'][ $field_id ]['field_label'] = $field_label;
							$all_acf_data[ $group_id ]['fields'][ $field_id ]['field_value'] = $field_value;
							$all_acf_data[ $group_id ]['fields'][ $field_id ]['field_type']  = $field_type;
					} else {
						unset( $all_acf_data[ $group_id ]['fields'] );
					}
				}
				if ( 0 == $val_fields ) {
					unset( $all_acf_data[ $group_id ] );
				}
			} else {
				unset( $all_acf_data[ $group_id ] );
			}
		}
		?>
		<script type="text/javascript">
			(function($) {
				function new_map($el){var $markers=$el.find('.marker');var args={zoom:16,center:new google.maps.LatLng(0,0),mapTypeId :google.maps.MapTypeId.ROADMAP};var map=new google.maps.Map($el[0],args);map.markers=[];$markers.each(function(){add_marker($(this),map)});center_map(map);return map;}
				function add_marker($marker,map){var latlng=new google.maps.LatLng( $marker.attr('data-lat'), $marker.attr('data-lng') );var marker=new google.maps.Marker({position:latlng,map:map});map.markers.push(marker);if( $marker.html()){var infowindow=new google.maps.InfoWindow({content:$marker.html()});google.maps.event.addListener(marker,'click',function(){infowindow.open(map,marker)})}}
				function center_map(map){var bounds = new google.maps.LatLngBounds();$.each( map.markers,function(i,marker){var latlng=new google.maps.LatLng(marker.position.lat(),marker.position.lng());bounds.extend(latlng)});if(map.markers.length==1){map.setCenter(bounds.getCenter());map.setZoom(4)}else{map.fitBounds(bounds)}}
				var map=null;$(document).ready(function(){$('.acf-map').each(function(){map=new_map($(this));google.maps.event.trigger(map,'resize')})});
			})(jQuery);
		</script>
		<style type="text/css">.acf-map {width:100%;height:400px;border:#ccc solid 1px;margin:20px 0}.acf-map img {max-width:inherit !important}</style>
		<?php
		foreach ( $all_acf_data as $all_acf ) {
			$group_title        = $all_acf['group_title'];
			$all_acf_field_data = array();
			$all_acf_field_data = $all_acf['fields'];
			?>
			<table class="asf-display-table table">
				<thead>
					<tr><td colspan="2"><h4><?php echo esc_html( $group_title ); ?></h4></td></tr>
				</thead>
				<tbody>
					<?php
					foreach ( $all_acf_field_data as $all_final_acf_field_data ) {
						$acf_field_data_type  = $all_final_acf_field_data['field_type']['type'];
						$acf_field_data_value = $all_final_acf_field_data['field_value'];
						if ( 'image' === $acf_field_data_type ) {
							$acf_image_return_data = $all_final_acf_field_data['field_type']['return_format'];
							if ( 'url' === $acf_image_return_data ) {
								$display_field_data = "<image src='" . $acf_field_data_value . "'>";
							} elseif ( 'id' === $acf_image_return_data ) {
								$display_field_data = wp_get_attachment_image( $acf_field_data_value );
							} elseif ( 'array' === $acf_image_return_data ) {
								$display_field_data = "<image src='" . $acf_field_data_value['url'] . "'>";
							}
						} elseif ( 'file' === $acf_field_data_type ) {
							$acf_file_return_data = $all_final_acf_field_data['field_type']['return_format'];
							if ( 'url' === $acf_file_return_data ) {
								$display_field_data = "<a href='" . $acf_field_data_value . "'>Download File</a>";
							} elseif ( 'id' === $acf_file_return_data ) {
								$acf_field_data_value_url = $acf_field_data_value['url'];
								$url                      = wp_get_attachment_url( $acf_field_data_value_url );
								$display_field_data       = "<a href='" . $url . "' >Download File</a>";
							} elseif ( 'array' === $acf_file_return_data ) {
								$display_field_data = "<a href='" . $acf_field_data_value['url'] . "'>Download File</a>";
							}
						} elseif ( 'url' === $acf_field_data_type ) {
							$display_field_data = "<a href='" . $acf_field_data_value . "' target='_blank'>" . $acf_field_data_value . '</a>';
						} elseif ( 'select' === $acf_field_data_type ) {
							$acf_select_multipal_data = $all_final_acf_field_data['field_type']['multiple'];
							$acf_select_return_data   = $all_final_acf_field_data['field_type']['return_format'];
							if ( count( $acf_field_data_value ) <= 1 ) {
								if ( 'array' === $acf_select_return_data ) {
									foreach ( $acf_field_data_value as $acf_checkbox_field_data ) {
										$acf_select_field_data_value = $acf_checkbox_field_data['value'];
										$acf_select_field_data_label = $acf_checkbox_field_data['label'];
										$display_field_data          = $acf_select_field_data_value . ' : ' .
										$acf_select_field_data_label;
									}
								} elseif ( '1' == $acf_select_multipal_data ) {
										$display_field_data = implode( ', ', $acf_field_data_value );
								} else {
									$display_field_data = $acf_field_data_value;
								}
							} elseif ( 'array' === $acf_select_return_data ) {
									$acf_select_field_data_value = $acf_field_data_value['value'];
									$acf_select_field_data_label = $acf_field_data_value['label'];
									$display_field_data          = $acf_select_field_data_value . ' : ' .
									$acf_select_field_data_label;
							} elseif ( '1' == $acf_select_multipal_data ) {
									$display_field_data = implode( ', ', $acf_field_data_value );
							} else {
								$display_field_data = $acf_field_data_value;
							}
						} elseif ( 'link' === $acf_field_data_type ) {
							$acf_link_return_data = $all_final_acf_field_data['field_type']['return_format'];
							$acf_link_data        = $all_final_acf_field_data['field_value'];
							if ( 'array' === $acf_link_return_data ) {
								$acf_field_data_link_url    = $acf_field_data_value['url'];
								$acf_field_data_link_title  = $acf_field_data_value['title'];
								$acf_field_data_link_target = $acf_field_data_value['target'] ? $acf_field_data_value['target'] : 'self';
								$display_field_data         = '<a class="button" href="' . esc_url( $acf_field_data_link_url ) . '" target="' . esc_attr( $acf_field_data_link_target ) . '">' . esc_html( $acf_field_data_link_title ) . '</a>';
							} else {
								$display_field_data = '<a class="button" href="' . $acf_link_data . '" target="_blank">Continue Reading</a>';
							}
						} elseif ( 'button_group' === $acf_field_data_type ) {
							$acf_button_group_return_data = $all_final_acf_field_data['field_type']['return_format'];
							if ( 'value' === $acf_button_group_return_data || 'label' === $acf_button_group_return_data ) {
								$display_field_data = $acf_field_data_value;
							} else {
								$acf_button_group_field_data_value = $acf_field_data_value['value'];
								$acf_button_group_field_data_label = $acf_field_data_value['label'];
								$display_field_data                = $acf_button_group_field_data_value . ' : ' . $acf_button_group_field_data_label;
							}
						} elseif ( 'radio' === $acf_field_data_type ) {
							$acf_radio_button_return_data = $all_final_acf_field_data['field_type']['return_format'];
							if ( 'value' === $acf_radio_button_return_data || 'label' === $acf_radio_button_return_data ) {
								$display_field_data = $acf_field_data_value;
							} else {
								$acf_radio_button_field_data_value = $acf_field_data_value['value'];
								$acf_radio_button_field_data_label = $acf_field_data_value['label'];
								$display_field_data                = $acf_radio_button_field_data_value . ' : ' . $acf_radio_button_field_data_label;
							}
						} elseif ( 'true_false' === $acf_field_data_type ) {
							if ( '' == $acf_field_data_value ) {
								$display_field_data = 0;
							} else {
								$display_field_data = $acf_field_data_value;
							}
						} elseif ( 'message' === $acf_field_data_type ) {
							$acf_message_return_data = $all_final_acf_field_data['field_type']['message'];
							$display_field_data      = $acf_message_return_data;
						} else {
							$display_field_data = $acf_field_data_value;
						}
						?>
						<tr>
							<td><?php echo esc_html( $all_final_acf_field_data['field_label'] ); ?></td>
							<?php
							if ( 'checkbox' === $acf_field_data_type ) {
								$acf_checkbox_return_data = $all_final_acf_field_data['field_type']['return_format'];
								$display_field_data_list  = $all_final_acf_field_data['field_value'];
								?>
									<td>
									<?php
									foreach ( $display_field_data_list as $acf_checkbox_field_data ) {
										if ( 'value' === $acf_checkbox_return_data || 'label' === $acf_checkbox_return_data ) {
											$display_field_data = $acf_checkbox_field_data;
											echo esc_html( $display_field_data );
										}
										if ( 'array' === $acf_checkbox_return_data ) {
											$display_checkbox_field_value = $acf_checkbox_field_data['value'];
											$display_checkbox_field_lable = $acf_checkbox_field_data['label'];
											echo esc_html( $display_checkbox_field_value ) . ' : ' . esc_html( $display_checkbox_field_lable );
										}
									}
									?>
									</td>
									<?php
							} elseif ( 'post_object' === $acf_field_data_type ) {
								$acf_post_object_return_data   = $all_final_acf_field_data['field_type']['return_format'];
								$acf_post_object_multiple_data = $all_final_acf_field_data['field_type']['multiple'];
								$display_field_data            = $acf_field_data_value;
								setup_postdata( $display_field_data );
								?>
								<td>
									<?php
									if ( 1 == $acf_post_object_multiple_data ) {
										?>
										<ul>
											<?php
											foreach ( $display_field_data as $post ) {
												if ( 'object' === $acf_post_object_return_data ) {
													$post_object_title = get_the_title( $post->ID );
													echo '<li>' . esc_html( $post_object_title ) . '</<li>>';
												} else {
													$post_object_title = get_the_title( $post );
													echo '<li>' . esc_html( $post_object_title ) . '</<li>>';
												}
											}
											?>
										</ul>
										<?php
									} else {
										the_title();
									}
									?>
								</td>
								<?php
							} elseif ( 'page_link' === $acf_field_data_type ) {
								$acf_page_link_multiple_data = $all_final_acf_field_data['field_type']['multiple'];
								$display_field_data          = $acf_field_data_value;
								setup_postdata( $display_field_data );
								?>
								<td>
									<?php
									if ( 1 == $acf_page_link_multiple_data ) {
										?>
										<ul>
											<?php
											foreach ( $display_field_data as $post ) {
												echo '<li>' . esc_html( $post ) . '</li>';
											}
											?>
										</ul>
										<?php
									} else {
										echo esc_html( $display_field_data );
									}
									?>
								</td>
								<?php
							} elseif ( 'relationship' === $acf_field_data_type ) {
								$acf_relationship_return_data = $all_final_acf_field_data['field_type']['return_format'];
								$display_field_data           = $acf_field_data_value;
								setup_postdata( $display_field_data );
								?>
								<td>
									<ul>
										<?php
										foreach ( $display_field_data as $post ) {
											?>
											<li>
												<?php
												$post_type_attachment = $post->post_type;
												if ( 'object' === $acf_relationship_return_data ) {
													if ( 'attachment' === $post_type_attachment ) {
														$relationship_title = '<img src="' . $post->guid . '">';
													} else {
														$relationship_title = get_the_title( $post->ID );
													}
													echo esc_html( $relationship_title ) . '</br>';
												} else {
													if ( 'attachment' === $post_type_attachment ) {
														$relationship_title = '<img src="' . $post->guid . '">';
													} else {
														$relationship_title = get_the_title( $post );
													}
													echo esc_html( $relationship_title ) . '</br>';
												}
												?>
											</li>
											<?php
										}
										?>
									</ul>
								</td>
								<?php
							} elseif ( 'taxonomy' === $acf_field_data_type ) {
								$display_field_data = $acf_field_data_value;
								?>
								<td>
									<ul>
										<?php
										foreach ( $display_field_data as $term ) {
											$term_data     = get_term( $term );
											$taxonomy_name = $term_data->name;
											echo '<li>' . esc_html( $taxonomy_name ) . '</li>';
										}
										?>
									</ul>
								</td>
								<?php
							} elseif ( 'user' === $acf_field_data_type ) {
								$acf_user_return_data   = $all_final_acf_field_data['field_type']['return_format'];
								$acf_user_multiple_data = $all_final_acf_field_data['field_type']['multiple'];
								$display_field_data     = $acf_field_data_value;
								?>
								<td>
									<ul>
										<?php
										if ( 1 == $acf_user_multiple_data ) {
											foreach ( $display_field_data as $user ) {
												if ( 'id' === $acf_user_return_data ) {
													$user_data = get_user_by( 'ID', $user );
													$user_name = $user_data->user_login;
													echo '<li>' . esc_html( $user_name ) . '</li>';
												} elseif ( 'object' === $acf_user_return_data ) {
													$user_name = $user->user_login;
													echo '<li>' . esc_html( $user_name ) . '</li>';
												} elseif ( 'array' === $acf_user_return_data ) {
													$user_name = $user['display_name'];
													echo '<li>' . esc_html( $user_name ) . '</li>';
												}
											}
										} elseif ( 'id' === $acf_user_return_data ) {
												$user_data = get_user_by( 'ID', $display_field_data );
												$user_name = $user_data->user_login;
												echo '<li>' . esc_html( $user_name ) . '</li>';
										} elseif ( 'object' === $acf_user_return_data ) {
											$user_name = $display_field_data->user_login;
											echo '<li>' . esc_html( $user_name ) . '</li>';
										} elseif ( 'array' === $acf_user_return_data ) {
											$user_name = $display_field_data['display_name'];
											echo '<li>' . esc_html( $user_name ) . '</li>';
										}
										?>
									</ul>
								</td>
								<?php
							} elseif ( 'google_map' === $acf_field_data_type ) {
								?>
								<td>
									<?php
									$lat = $all_final_acf_field_data['field_value']['lat'];
									$lng = $all_final_acf_field_data['field_value']['lng'];
									?>
									<div class="acf-map"><div class="marker" data-lat="<?php echo esc_attr( $lat ); ?>" data-lng="<?php echo esc_attr( $lng ); ?>"></div></div>
								</td>
								<?php
							} elseif ( 'group' === $acf_field_data_type ) {
								$display_field_type_data_list = $all_final_acf_field_data['field_type']['sub_fields'];
								$acf_group_field_data_value   = $all_final_acf_field_data['field_value'];
								?>
								<td>
									<ul>
										<?php
										foreach ( $display_field_type_data_list as $field_type_data_list ) {
											$field_type_data_name      = $field_type_data_list['name'];
											$display_group_field_label = $field_type_data_list['label'];
											?>
											<li>
												<h5><?php echo esc_html( $display_group_field_label ); ?></h5>
												<?php
												$asf_group_field_type_data_type = $field_type_data_list['type'];
												if ( 'image' === $asf_group_field_type_data_type ) {
													$asf_group_field_return_value     = $field_type_data_list['return_format'];
													$acf_group_image_field_data_value = $acf_group_field_data_value[ $field_type_data_name ];
													if ( 'url' === $asf_group_field_return_value ) {
														?>
														<img src="<?php echo esc_url( $acf_group_image_field_data_value ); ?>">
														<?php
													} elseif ( 'id' === $asf_group_field_return_value ) {
														$acf_group_image_field_id_data = wp_get_attachment_image( $acf_group_image_field_data_value );
														echo esc_html( $acf_group_image_field_id_data );
													} elseif ( 'array' === $asf_group_field_return_value ) {
														?>
														<img src="<?php echo esc_url( $acf_group_image_field_data_value['url'] ); ?>">
														<?php
													}
												} elseif ( 'file' === $asf_group_field_type_data_type ) {
													$asf_group_file_field_return_value = $field_type_data_list['return_format'];
													$acf_group_file_field_data_value   = $acf_group_field_data_value[ $field_type_data_name ];
													if ( 'url' === $asf_group_file_field_return_value ) {
														?>
														<a href='<?php echo esc_url( $acf_group_file_field_data_value ); ?>'><?php esc_html_e( 'Download File', 'blog-designer-pro' ); ?></a>
														<?php
													} elseif ( 'id' === $asf_group_file_field_return_value ) {
														$acf_group_file_field_id_data = wp_get_attachment_url( $acf_group_file_field_data_value );
														?>
														<a href='<?php echo esc_url( $acf_group_file_field_id_data ); ?>'><?php esc_html_e( 'Download File', 'blog-designer-pro' ); ?></a>
														<?php
													} elseif ( 'array' === $asf_group_file_field_return_value ) {
														?>
														<a href='<?php echo esc_url( $acf_group_file_field_data_value['url'] ); ?>'><?php esc_html_e( 'Download File', 'blog-designer-pro' ); ?></a>
														<?php
													}
												} elseif ( 'url' === $asf_group_field_type_data_type ) {
													?>
													<a href='<?php echo esc_url( $acf_group_field_data_value[ $field_type_data_name ] ); ?>'><?php echo esc_html( $acf_group_field_data_value[ $field_type_data_name ] ); ?></a>
													<?php
												} elseif ( 'select' === $asf_group_field_type_data_type ) {
													$acf_group_select_field_multiple_value = $field_type_data_list['multiple'];
													$asf_group_select_field_return_value   = $field_type_data_list['return_format'];
													if ( 'array' === $asf_group_select_field_return_value ) {
														$acf_group_select_field_data_value       = $acf_group_field_data_value[ $field_type_data_name ];
														$acf_group_final_select_field_data_value = $acf_group_select_field_data_value['value'];
														$acf_group_final_select_field_data_label = $acf_group_select_field_data_value['label'];
														$display_group_field_data                = $acf_group_final_select_field_data_value . ' : ' . $acf_group_final_select_field_data_label;
														echo esc_html( $display_group_field_data );
													} elseif ( '1' == $acf_group_select_field_multiple_value ) {
															$acf_group_select_field_data_value = $acf_group_field_data_value[ $field_type_data_name ];
															$acf_group_select_field_data       = implode( ', ', $acf_group_select_field_data_value );
															echo esc_html( $acf_group_select_field_data );
													} else {
														$acf_group_select_field_data_value = $acf_group_field_data_value[ $field_type_data_name ];
														echo esc_html( $acf_group_select_field_data_value );
													}
												} elseif ( 'link' === $asf_group_field_type_data_type ) {
													$asf_group_link_field_return_value = $field_type_data_list['return_format'];
													if ( 'array' === $asf_group_link_field_return_value ) {
														$acf_field_data_group_link_url    = $acf_group_field_data_value[ $field_type_data_name ]['url'];
														$acf_field_data_group_link_title  = $acf_group_field_data_value[ $field_type_data_name ]['title'];
														$acf_field_data_group_link_target = $acf_group_field_data_value[ $field_type_data_name ]['target'] ? $acf_group_field_data_value[ $field_type_data_name ]['target'] : 'self';
														?>
														<a class="button" href="<?php echo esc_url( $acf_field_data_group_link_url ); ?>" target="<?php echo esc_attr( $acf_field_data_group_link_target ); ?>"><?php echo esc_html( $acf_field_data_group_link_title ); ?></a>
														<?php
													} else {
														?>
														<a class="button" href="<?php echo esc_url( $acf_group_field_data_value[ $field_type_data_name ]['value'] ); ?>" target="_blank"><?php esc_html_e( 'Continue Reading', 'blog-designer-pro' ); ?></a>
														<?php
													}
												} elseif ( 'button_group' === $asf_group_field_type_data_type ) {
													$asf_group_button_group_field_return_value = $field_type_data_list['return_format'];
													if ( 'value' === $asf_group_button_group_field_return_value || 'label' === $asf_group_button_group_field_return_value ) {
														echo esc_html( $acf_group_field_data_value[ $field_type_data_name ] );
													} else {
														$acf_field_data_group_button_group_value = $acf_group_field_data_value[ $field_type_data_name ]['value'];
														$acf_field_data_group_button_group_label = $acf_group_field_data_value[ $field_type_data_name ]['label'];
														$display_group_field_data                = $acf_field_data_group_button_group_value . ' : ' . $acf_field_data_group_button_group_label;
														echo esc_html( $display_group_field_data );
													}
												} elseif ( 'radio' === $asf_group_field_type_data_type ) {
													$asf_group_radio_field_return_value = $field_type_data_list['return_format'];
													if ( 'value' === $asf_group_radio_field_return_value || 'label' === $asf_group_radio_field_return_value ) {
														echo esc_attr( $acf_group_field_data_value[ $field_type_data_name ] );
													} else {
														$acf_field_data_group_radio_value = $acf_group_field_data_value[ $field_type_data_name ]['value'];
														$acf_field_data_group_radio_label = $acf_group_field_data_value[ $field_type_data_name ]['label'];
														$display_group_field_data         = $acf_field_data_group_radio_value . ' : ' . $acf_field_data_group_radio_label;
														echo esc_attr( $display_group_field_data );
													}
												} elseif ( 'true_false' === $asf_group_field_type_data_type ) {
													$display_group_true_false_field_data = $acf_group_field_data_value[ $field_type_data_name ];
													if ( '' == $display_group_true_false_field_data ) {
														$display_true_false_field_data = 0;
													} else {
														$display_true_false_field_data = $display_group_true_false_field_data;
													}
													echo esc_html( $display_true_false_field_data );
												} elseif ( 'message' === $asf_group_field_type_data_type ) {
													$asf_group_message_field_return_value = $field_type_data_list['message'];
													echo esc_html( $asf_group_message_field_return_value );
												} elseif ( 'checkbox' === $asf_group_field_type_data_type ) {
													$asf_group_checkbox_field_return_value = $field_type_data_list['return_format'];
													if ( 'value' === $asf_group_checkbox_field_return_value || 'label' === $asf_group_checkbox_field_return_value ) {
														$asf_group_checkbox_values = $acf_group_field_data_value[ $field_type_data_name ];
														foreach ( $asf_group_checkbox_values as $asf_group_checkbox_value ) {
															echo esc_html( $asf_group_checkbox_value );
														}
													} else {
														$asf_group_checkbox_values = $acf_group_field_data_value[ $field_type_data_name ];
														foreach ( $asf_group_checkbox_values as $asf_group_checkbox_value ) {
															$asf_group_final_checkbox_value = $asf_group_checkbox_value['value'];
															$asf_group_final_checkbox_label = $asf_group_checkbox_value['label'];
															$asf_group_checkbox_value       = $asf_group_final_checkbox_value . ' : ' . $asf_group_final_checkbox_label;
															echo esc_html( $asf_group_checkbox_value );
														}
													}
												} elseif ( 'post_object' === $asf_group_field_type_data_type ) {
													$asf_group_post_object_field_return_value = $field_type_data_list['return_format'];
													$asf_group_post_object_field_multiple     = $field_type_data_list['multiple'];
													$display_group_post_object_field_data     = $acf_group_field_data_value[ $field_type_data_name ];
													if ( 1 == $asf_group_post_object_field_multiple ) {
														?>
														<ul>
															<?php
															foreach ( $display_group_post_object_field_data as $group_post ) {
																$post_object_title = get_the_title( $group_post );
																?>
																<li><?php echo esc_html( $post_object_title ); ?></li>
																<?php
															}
															?>
														</ul>
														<?php
													} else {
														$post_object_title = get_the_title( $display_group_post_object_field_data );
														echo esc_html( $post_object_title );
													}
												} elseif ( 'page_link' === $asf_group_field_type_data_type ) {
													$asf_group_page_link_field_multiple = $field_type_data_list['multiple'];
													$display_group_page_link_field_data = $acf_group_field_data_value[ $field_type_data_name ];
													if ( 1 == $asf_group_page_link_field_multiple ) {
														?>
														<ul>
															<?php
															foreach ( $display_group_page_link_field_data as $group_post ) {
																$page_link_title = $group_post;
																?>
																<li><?php echo esc_html( $page_link_title ); ?></li>
																<?php
															}
															?>
														</ul>
														<?php
													} else {
														echo esc_html( $display_group_page_link_field_data );
													}
												} elseif ( 'relationship' === $asf_group_field_type_data_type ) {
													$asf_group_relationship_field_return_value = $field_type_data_list['return_format'];
													$display_group_relationship_field_data     = $acf_group_field_data_value[ $field_type_data_name ];
													setup_postdata( $display_group_relationship_field_data );
													?>
													<ul>
														<?php
														foreach ( $display_group_relationship_field_data as $relationship_post ) {
															?>
															<li>
																<?php
																if ( 'object' === $asf_group_relationship_field_return_value ) {
																	$relationship_post_type_attachment = $relationship_post->post_type;
																	if ( 'attachment' === $relationship_post_type_attachment ) {
																		?>
																		<img src="<?php echo esc_url( $relationship_post->guid ); ?>">
																		<?php
																	} else {
																		$relationship_group_title = get_the_title( $relationship_post->ID );
																		echo esc_html( $relationship_group_title );
																	}
																} else {
																	$relationship_group_title = get_the_title( $relationship_post );
																	echo esc_html( $relationship_group_title );
																}
																?>
															</li>
															<?php
														}
														?>
													</ul>
													<?php
												} elseif ( 'taxonomy' === $asf_group_field_type_data_type ) {
													$display_group_taxonomy_field_data = $acf_group_field_data_value[ $field_type_data_name ];
													?>
													<ul>
														<?php
														foreach ( $display_group_taxonomy_field_data as $taxonomy_field ) {
															$term_data     = get_term( $taxonomy_field );
															$taxonomy_name = $term_data->name;
															?>
															<li><?php echo esc_html( $taxonomy_name ); ?></li>
															<?php
														}
														?>
													</ul>
													<?php
												} elseif ( 'user' === $asf_group_field_type_data_type ) {
													$asf_group_user_field_return_value  = $field_type_data_list['return_format'];
													$asf_group_user_link_field_multiple = $field_type_data_list['multiple'];
													$display_group_user_field_data      = $acf_group_field_data_value[ $field_type_data_name ];
													?>
													<ul>
														<?php
														if ( 1 == $asf_group_user_link_field_multiple ) {
															foreach ( $display_group_user_field_data as $group_user ) {
																?>
																<li>
																	<?php
																	if ( 'id' === $asf_group_user_field_return_value ) {
																		$user_data = get_user_by( 'ID', $group_user );
																		$user_name = $user_data->user_login;
																		echo esc_html( $user_name );
																	} elseif ( 'object' === $asf_group_user_field_return_value ) {
																		$user_name = $group_user->user_login;
																		echo esc_html( $user_name );
																	} elseif ( 'array' === $asf_group_user_field_return_value ) {
																		$user_name = $group_user['display_name'];
																		echo esc_html( $user_name );
																	}
																	?>
																</li>
																<?php
															}
														} else {
															?>
															<li>
																<?php
																if ( 'id' === $asf_group_user_field_return_value ) {
																	$user_data = get_user_by( 'ID', $display_group_user_field_data );
																	$user_name = $user_data->user_login;
																	echo esc_html( $user_name );
																} elseif ( 'object' === $asf_group_user_field_return_value ) {
																	$user_name = $display_group_user_field_data->user_login;
																	echo esc_html( $user_name );
																} else {
																	$user_name = $display_group_user_field_data['display_name'];
																	echo esc_html( $user_name );
																}
																?>
															</li>
															<?php
														}
														?>
													</ul>
													<?php
												} elseif ( 'google_map' === $asf_group_field_type_data_type ) {
													$display_group_field_data = $acf_group_field_data_value[ $field_type_data_name ];
													$group_lat                = $display_group_field_data['lat'];
													$group_lng                = $display_group_field_data['lng'];
													?>
													<div class="acf-map"><div class="marker" data-lat="<?php echo esc_attr( $group_lat ); ?>" data-lng="<?php echo esc_url( $group_lng ); ?>"></div></div>
													<?php
												} else {
													$display_group_field_data = $acf_group_field_data_value[ $field_type_data_name ];
													echo esc_html( $display_group_field_data );
												}
												echo '</br>';
												?>
											</li>
											<?php
										}
										?>
									</ul>
								</td>
								<?php
							} else {
								?>
								<td><?php echo esc_html( $display_field_data ); ?></td>
								<?php
							}
							?>
						</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php
		}
	}
	/**
	 * Display ACf filed in blog page
	 *
	 * @since 2.5.1
	 * @param array $bdp_settings setting.
	 * @param int   $post_id post id.
	 * @return void
	 */
	public function blog_display_acf_fields( $bdp_settings, $post_id ) {
		$groups       = acf_get_field_groups( array( 'post_id' => $post_id ) );
		$all_acf_data = array();
		foreach ( $groups as $group ) {
			$group_id                                 = $group['ID'];
			$group_title                              = $group['title'];
			$all_acf_data[ $group_id ]                = array();
			$all_acf_data[ $group_id ]['group_id']    = $group_id;
			$all_acf_data[ $group_id ]['group_title'] = $group_title;
			$fields                                   = acf_get_fields( $group_id );
			if ( $fields ) {
				$all_acf_data[ $group_id ]['fields'] = array();
				$val_fields                          = 0;
				foreach ( $fields as $field ) {
					$field_id          = $field['ID'];
					$field_label       = $field['label'];
					$field_key         = $field['key'];
					$field_value       = get_field( $field_key );
					$field_type        = get_field_object( $field_key );
					$bdp_all_acf_field = isset( $bdp_settings['bdp_acf_field'] ) ? $bdp_settings['bdp_acf_field'] : array();
					if ( ! empty( $bdp_all_acf_field ) ) {
						foreach ( $bdp_all_acf_field as $bdp_acf_field ) {
							$bdp_acf_field_display = $bdp_acf_field;
							if ( $field_id == $bdp_acf_field_display ) {
								if ( '' != $field_value || 'true_false' === $field_type['type'] || 'color_picker' === $field_type['type'] || 'message' === $field_type['type'] || 'accordion' === $field_type['type'] || 'google_map' === $field_type['type'] || 'group' === $field_type['type'] || 'password' === $field_type['type'] ) {
									$val_fields                                       = 1;
									$all_acf_data[ $group_id ]['fields'][ $field_id ] = array();
									$all_acf_data[ $group_id ]['fields'][ $field_id ]['field_label'] = $field_label;
									$all_acf_data[ $group_id ]['fields'][ $field_id ]['field_value'] = $field_value;
									$all_acf_data[ $group_id ]['fields'][ $field_id ]['field_type']  = $field_type;
								} else {
									unset( $all_acf_data[ $group_id ]['fields'] );
								}
							}
						}
					} elseif ( '' != $field_value || 'true_false' === $field_type['type'] || 'color_picker' === $field_type['type'] || 'message' === $field_type['type'] || 'accordion' === $field_type['type'] || 'google_map' === $field_type['type'] || 'group' === $field_type['type'] || 'password' === $field_type['type'] ) {
							$val_fields                                       = 1;
							$all_acf_data[ $group_id ]['fields'][ $field_id ] = array();
							$all_acf_data[ $group_id ]['fields'][ $field_id ]['field_label'] = $field_label;
							$all_acf_data[ $group_id ]['fields'][ $field_id ]['field_value'] = $field_value;
							$all_acf_data[ $group_id ]['fields'][ $field_id ]['field_type']  = $field_type;
					} else {
						unset( $all_acf_data[ $group_id ]['fields'] );
					}
				}
				if ( 0 == $val_fields ) {
					unset( $all_acf_data[ $group_id ] );
				}
			} else {
				unset( $all_acf_data[ $group_id ] );
			}
		}
		?>
		<script type="text/javascript">
			(function($) {
				function new_map($el){var $markers = $el.find('.marker');var args={zoom:16,center:new google.maps.LatLng(0,0),mapTypeId:google.maps.MapTypeId.ROADMAP};var map=new google.maps.Map( $el[0],args);map.markers=[];$markers.each(function(){add_marker($(this),map)});center_map(map);return map}
				function add_marker( $marker, map ) {
					var latlng=new google.maps.LatLng( $marker.attr('data-lat'),$marker.attr('data-lng'));var marker=new google.maps.Marker({position:latlng,map:map});map.markers.push(marker);
					if( $marker.html()){var infowindow=new google.maps.InfoWindow({content:$marker.html()});google.maps.event.addListener(marker,'click',function(){infowindow.open(map,marker)})}
				}
				function center_map(map){var bounds=new google.maps.LatLngBounds();$.each(map.markers,function(i,marker){var latlng=new google.maps.LatLng(marker.position.lat(),marker.position.lng());bounds.extend(latlng)});if(1==map.markers.length){map.setCenter( bounds.getCenter());map.setZoom(4)}else{map.fitBounds(bounds)}}
				var map=null;$(document).ready(function(){$('.acf-map').each(function(){map=new_map($(this));google.maps.event.trigger(map,'resize')})});
			})(jQuery);
		</script>
		<style type="text/css">.acf-map {width: 100%;height: 400px;border: #ccc solid 1px;margin: 20px 0}.acf-map img {max-width: inherit !important}</style>
		<?php
		foreach ( $all_acf_data as $all_acf ) {
			$group_title        = $all_acf['group_title'];
			$all_acf_field_data = array();
			$all_acf_field_data = $all_acf['fields'];
			foreach ( $all_acf_field_data as $all_final_acf_field_data ) {
				$acf_field_data_type  = $all_final_acf_field_data['field_type']['type'];
				$acf_field_data_value = $all_final_acf_field_data['field_value'];
				if ( 'image' === $acf_field_data_type ) {
					$acf_image_return_data = $all_final_acf_field_data['field_type']['return_format'];
					if ( 'url' === $acf_image_return_data ) {
						$display_field_data = "<image src='" . $acf_field_data_value . "'>";
					} elseif ( 'id' === $acf_image_return_data ) {
						$display_field_data = wp_get_attachment_image( $acf_field_data_value );
					} elseif ( 'array' === $acf_image_return_data ) {
						$display_field_data = "<image src='" . $acf_field_data_value['url'] . "'>";
					}
				} elseif ( 'file' === $acf_field_data_type ) {
					$acf_file_return_data = $all_final_acf_field_data['field_type']['return_format'];
					if ( 'url' === $acf_file_return_data ) {
						$display_field_data = "<a href='" . $acf_field_data_value . "'>Download File</a>";
					} elseif ( 'id' === $acf_file_return_data ) {
						$acf_field_data_value_url = $acf_field_data_value['url'];
						$url                      = wp_get_attachment_url( $acf_field_data_value_url );
						$display_field_data       = "<a href='" . $url . "' >Download File</a>";
					} elseif ( 'array' === $acf_file_return_data ) {
						$display_field_data = "<a href='" . $acf_field_data_value['url'] . "'>Download File</a>";
					}
				} elseif ( 'url' === $acf_field_data_type ) {
					$display_field_data = "<a href='" . $acf_field_data_value . "' target='_blank'>" . $acf_field_data_value . '</a>';
				} elseif ( 'select' === $acf_field_data_type ) {
					$acf_select_multipal_data = $all_final_acf_field_data['field_type']['multiple'];

					$acf_select_return_data = $all_final_acf_field_data['field_type']['return_format'];
					if ( count( $acf_field_data_value ) <= 1 ) {
						if ( 'array' === $acf_select_return_data ) {
							foreach ( $acf_field_data_value as $acf_checkbox_field_data ) {
								$acf_select_field_data_value = $acf_checkbox_field_data['value'];
								$acf_select_field_data_label = $acf_checkbox_field_data['label'];
								$display_field_data          = $acf_select_field_data_value . ' : ' .
								$acf_select_field_data_label;
							}
						} elseif ( '1' == $acf_select_multipal_data ) {
								$display_field_data = implode( ', ', $acf_field_data_value );
						} else {
							$display_field_data = $acf_field_data_value;
						}
					} elseif ( 'array' === $acf_select_return_data ) {
							$acf_select_field_data_value = $acf_field_data_value['value'];
							$acf_select_field_data_label = $acf_field_data_value['label'];
							$display_field_data          = $acf_select_field_data_value . ' : ' .
							$acf_select_field_data_label;
					} elseif ( '1' == $acf_select_multipal_data ) {
							$display_field_data = implode( ', ', $acf_field_data_value );
					} else {
						$display_field_data = $acf_field_data_value;
					}
				} elseif ( 'link' === $acf_field_data_type ) {
					$acf_link_return_data = $all_final_acf_field_data['field_type']['return_format'];
					$acf_link_data        = $all_final_acf_field_data['field_value'];
					if ( 'array' === $acf_link_return_data ) {
						$acf_field_data_link_url    = $acf_field_data_value['url'];
						$acf_field_data_link_title  = $acf_field_data_value['title'];
						$acf_field_data_link_target = $acf_field_data_value['target'] ? $acf_field_data_value['target'] : 'self';
						$display_field_data         = '<a class="button" href="' . esc_url( $acf_field_data_link_url ) . '" target="' . esc_attr( $acf_field_data_link_target ) . '">' . esc_html( $acf_field_data_link_title ) . '</a>';
					} else {
						$display_field_data = '<a class="button" href="' . $acf_link_data . '" target="_blank">Continue Reading</a>';
					}
				} elseif ( 'button_group' === $acf_field_data_type ) {
					$acf_button_group_return_data = $all_final_acf_field_data['field_type']['return_format'];
					if ( 'value' === $acf_button_group_return_data || 'label' === $acf_button_group_return_data ) {
						$display_field_data = $acf_field_data_value;
					} else {
						$acf_button_group_field_data_value = $acf_field_data_value['value'];
						$acf_button_group_field_data_label = $acf_field_data_value['label'];
						$display_field_data                = $acf_button_group_field_data_value . ' : ' . $acf_button_group_field_data_label;
					}
				} elseif ( 'radio' === $acf_field_data_type ) {
					$acf_radio_button_return_data = $all_final_acf_field_data['field_type']['return_format'];
					if ( 'value' === $acf_radio_button_return_data || 'label' === $acf_radio_button_return_data ) {
						$display_field_data = $acf_field_data_value;
					} else {
						$acf_radio_button_field_data_value = $acf_field_data_value['value'];
						$acf_radio_button_field_data_label = $acf_field_data_value['label'];
						$display_field_data                = $acf_radio_button_field_data_value . ' : ' . $acf_radio_button_field_data_label;
					}
				} elseif ( 'true_false' === $acf_field_data_type ) {
					if ( '' == $acf_field_data_value ) {
						$display_field_data = 0;
					} else {
						$display_field_data = $acf_field_data_value;
					}
				} elseif ( 'message' === $acf_field_data_type ) {
					$acf_message_return_data = $all_final_acf_field_data['field_type']['message'];
						$display_field_data  = $acf_message_return_data;
				} else {
					$display_field_data = $acf_field_data_value;
				}
				echo '<div class="bdp_acf_link">';
				echo '<span class="link-lable"><i class="fas fa-plus-square"></i> ' . esc_html( $all_final_acf_field_data['field_label'] ) . ' : </span>';
				if ( 'checkbox' === $acf_field_data_type ) {
					$acf_checkbox_return_data = $all_final_acf_field_data['field_type']['return_format'];
					$display_field_data_list  = $all_final_acf_field_data['field_value'];
					foreach ( $display_field_data_list as $acf_checkbox_field_data ) {
						if ( 'value' === $acf_checkbox_return_data || 'label' === $acf_checkbox_return_data ) {
							echo esc_html( $acf_checkbox_field_data );
						}
						if ( 'array' === $acf_checkbox_return_data ) {
							$display_checkbox_field_value = $acf_checkbox_field_data['value'];
							$display_checkbox_field_lable = $acf_checkbox_field_data['label'];
							echo esc_html( $display_checkbox_field_value ) . ' : ' . esc_html( $display_checkbox_field_lable );
						}
					}
				} elseif ( 'post_object' === $acf_field_data_type ) {
					$acf_post_object_return_data   = $all_final_acf_field_data['field_type']['return_format'];
					$acf_post_object_multiple_data = $all_final_acf_field_data['field_type']['multiple'];
					$display_field_data_list       = $acf_field_data_value;
					setup_postdata( $display_field_data_list );
					if ( 1 == $acf_post_object_multiple_data ) {
						foreach ( $display_field_data_list as $post ) {
							if ( 'object' === $acf_post_object_return_data ) {
								$post_object_title = get_the_title( $post->ID );
								echo esc_html( $post_object_title );

							} else {
								$post_object_title = get_the_title( $post );
								echo esc_html( $post_object_title );
							}
						}
					} else {
						the_title();
					}
				} elseif ( 'page_link' === $acf_field_data_type ) {
					$acf_page_link_multiple_data = $all_final_acf_field_data['field_type']['multiple'];
					$display_field_data_list     = $acf_field_data_value;
					setup_postdata( $display_field_data_list );
					if ( 1 == $acf_page_link_multiple_data ) {
						foreach ( $display_field_data_list as $post ) {
							echo esc_html( $post );
						}
					} else {
						echo esc_html( $display_field_data_list );
					}
				} elseif ( 'relationship' === $acf_field_data_type ) {
					$acf_relationship_return_data = $all_final_acf_field_data['field_type']['return_format'];
					$display_field_data_list      = $acf_field_data_value;
					setup_postdata( $display_field_data_list );
					foreach ( $display_field_data_list as $post ) {
						$post_type_attachment = $post->post_type;
						if ( 'object' === $acf_relationship_return_data ) {
							if ( 'attachment' === $post_type_attachment ) {
								$relationship_title = '<img src="' . $post->guid . '">';
							} else {
								$relationship_title = get_the_title( $post->ID );
							}
						} elseif ( 'attachment' === $post_type_attachment ) {
								$relationship_title = '<img src="' . $post->guid . '">';
						} else {
							$relationship_title = get_the_title( $post );
						}
						echo esc_html( $relationship_title );
					}
				} elseif ( 'taxonomy' === $acf_field_data_type ) {
					$display_field_data_list = $acf_field_data_value;
					foreach ( $display_field_data_list as $term ) {
						$term_data     = get_term( $term );
						$taxonomy_name = $term_data->name;
						echo esc_html( $taxonomy_name );
					}
				} elseif ( 'user' === $acf_field_data_type ) {
					$acf_user_return_data    = $all_final_acf_field_data['field_type']['return_format'];
					$acf_user_multiple_data  = $all_final_acf_field_data['field_type']['multiple'];
					$display_field_data_list = $acf_field_data_value;
					if ( 1 == $acf_user_multiple_data ) {
						foreach ( $display_field_data_list as $user ) {
							if ( 'id' === $acf_user_return_data ) {
								$user_data = get_user_by( 'ID', $user );
								$user_name = $user_data->user_login;
							} elseif ( 'object' === $acf_user_return_data ) {
								$user_name = $user->user_login;
							} elseif ( 'array' === $acf_user_return_data ) {
								$user_name = $user['display_name'];
							}
							echo esc_html( $user_name );
						}
					} else {
						if ( 'id' === $acf_user_return_data ) {
							$user_data = get_user_by( 'ID', $display_field_data_list );
							$user_name = $user_data->user_login;
						} elseif ( 'object' === $acf_user_return_data ) {
							$user_name = $display_field_data_list->user_login;
						} elseif ( 'array' === $acf_user_return_data ) {
							$user_name = $display_field_data_list['display_name'];
						}
						echo esc_html( $user_name );
					}
				} elseif ( 'google_map' === $acf_field_data_type ) {
					$lat = $all_final_acf_field_data['field_value']['lat'];
					$lng = $all_final_acf_field_data['field_value']['lng'];
					?>
					<div class="acf-map"><div class="marker" data-lat="<?php echo esc_attr( $lat ); ?>" data-lng="<?php echo esc_attr( $lng ); ?>"></div></div>
					<?php
				} elseif ( 'group' === $acf_field_data_type ) {
					$display_field_type_data_list = $all_final_acf_field_data['field_type']['sub_fields'];
					$acf_group_field_data_value   = $all_final_acf_field_data['field_value'];
					foreach ( $display_field_type_data_list as $field_type_data_list ) {
						$field_type_data_name      = $field_type_data_list['name'];
						$display_group_field_label = $field_type_data_list['label'];
						echo esc_html( $display_group_field_label );
						$asf_group_field_type_data_type = $field_type_data_list['type'];
						if ( 'image' === $asf_group_field_type_data_type ) {
							$asf_group_field_return_value     = $field_type_data_list['return_format'];
							$acf_group_image_field_data_value = $acf_group_field_data_value[ $field_type_data_name ];
							if ( 'url' === $asf_group_field_return_value ) {
								?>
								<img src="<?php echo esc_url( $acf_group_image_field_data_value ); ?>">
								<?php
							} elseif ( 'id' === $asf_group_field_return_value ) {
								$acf_group_image_field_id_data = wp_get_attachment_image( $acf_group_image_field_data_value );
								echo esc_html( $acf_group_image_field_id_data );
							} elseif ( 'array' === $asf_group_field_return_value ) {
								?>
								<img src="<?php echo esc_url( $acf_group_image_field_data_value['url'] ); ?>">
								<?php
							}
						} elseif ( 'file' === $asf_group_field_type_data_type ) {
							$asf_group_file_field_return_value = $field_type_data_list['return_format'];
							$acf_group_file_field_data_value   = $acf_group_field_data_value[ $field_type_data_name ];
							if ( 'url' === $asf_group_file_field_return_value ) {
								?>
								<a href='<?php echo esc_url( $acf_group_file_field_data_value ); ?>'><?php esc_html_e( 'Download File', 'blog-designer-pro' ); ?></a>
								<?php
							} elseif ( 'id' === $asf_group_file_field_return_value ) {
								$acf_group_file_field_id_data = wp_get_attachment_url( $acf_group_file_field_data_value );
								?>
								<a href='<?php echo esc_url( $acf_group_file_field_id_data ); ?>'><?php esc_html_e( 'Download File', 'blog-designer-pro' ); ?></a>
								<?php
							} elseif ( 'array' === $asf_group_file_field_return_value ) {
								?>
								<a href='<?php echo esc_url( $acf_group_file_field_data_value['url'] ); ?>'><?php esc_html_e( 'Download File', 'blog-designer-pro' ); ?></a>
								<?php
							}
						} elseif ( 'url' === $asf_group_field_type_data_type ) {
							?>
							<a href='<?php echo esc_url( $acf_group_field_data_value[ $field_type_data_name ] ); ?>'><?php echo esc_html( $acf_group_field_data_value[ $field_type_data_name ] ); ?></a>
							<?php
						} elseif ( 'select' === $asf_group_field_type_data_type ) {
							$acf_group_select_field_multiple_value = $field_type_data_list['multiple'];
							$asf_group_select_field_return_value   = $field_type_data_list['return_format'];
							if ( 'array' === $asf_group_select_field_return_value ) {
								$acf_group_select_field_data_value       = $acf_group_field_data_value[ $field_type_data_name ];
								$acf_group_final_select_field_data_value = $acf_group_select_field_data_value['value'];
								$acf_group_final_select_field_data_label = $acf_group_select_field_data_value['label'];
								$display_group_field_data                = $acf_group_final_select_field_data_value . ' : ' . $acf_group_final_select_field_data_label;
								echo esc_html( $display_group_field_data );
							} elseif ( '1' == $acf_group_select_field_multiple_value ) {
									$acf_group_select_field_data_value = $acf_group_field_data_value[ $field_type_data_name ];
									$acf_group_select_field_data       = implode( ', ', $acf_group_select_field_data_value );
									echo esc_html( $acf_group_select_field_data );
							} else {
								$acf_group_select_field_data_value = $acf_group_field_data_value[ $field_type_data_name ];
								echo esc_html( $acf_group_select_field_data_value );
							}
						} elseif ( 'link' === $asf_group_field_type_data_type ) {
							$asf_group_link_field_return_value = $field_type_data_list['return_format'];
							if ( 'array' === $asf_group_link_field_return_value ) {
								$acf_field_data_group_link_url    = $acf_group_field_data_value[ $field_type_data_name ]['url'];
								$acf_field_data_group_link_title  = $acf_group_field_data_value[ $field_type_data_name ]['title'];
								$acf_field_data_group_link_target = $acf_group_field_data_value[ $field_type_data_name ]['target'] ? $acf_group_field_data_value[ $field_type_data_name ]['target'] : 'self';
								?>
								<a class="button" href="<?php echo esc_url( $acf_field_data_group_link_url ); ?>" target="<?php echo esc_attr( $acf_field_data_group_link_target ); ?>"><?php echo esc_html( $acf_field_data_group_link_title ); ?></a>
								<?php
							} else {
								?>
								<a class="button" href="<?php echo esc_url( $acf_group_field_data_value[ $field_type_data_name ]['value'] ); ?>" target="_blank"><?php esc_html_e( 'Continue Reading', 'blog-designer-pro' ); ?></a>
								<?php
							}
						} elseif ( 'button_group' === $asf_group_field_type_data_type ) {
							$asf_group_button_group_field_return_value = $field_type_data_list['return_format'];
							if ( 'value' === $asf_group_button_group_field_return_value || 'label' === $asf_group_button_group_field_return_value ) {
								echo esc_html( $acf_group_field_data_value[ $field_type_data_name ] );
							} else {
								$acf_field_data_group_button_group_value = $acf_group_field_data_value[ $field_type_data_name ]['value'];
								$acf_field_data_group_button_group_label = $acf_group_field_data_value[ $field_type_data_name ]['label'];
								$display_group_field_data                = $acf_field_data_group_button_group_value . ' : ' . $acf_field_data_group_button_group_label;
								echo esc_html( $display_group_field_data );
							}
						} elseif ( 'radio' === $asf_group_field_type_data_type ) {
							$asf_group_radio_field_return_value = $field_type_data_list['return_format'];
							if ( 'value' === $asf_group_radio_field_return_value || 'label' === $asf_group_radio_field_return_value ) {
								echo esc_html( $acf_group_field_data_value[ $field_type_data_name ] );
							} else {
								$acf_field_data_group_radio_value = $acf_group_field_data_value[ $field_type_data_name ]['value'];
								$acf_field_data_group_radio_label = $acf_group_field_data_value[ $field_type_data_name ]['label'];
								$display_group_field_data         = $acf_field_data_group_radio_value . ' : ' . $acf_field_data_group_radio_label;
								echo esc_attr( $display_group_field_data );
							}
						} elseif ( 'true_false' === $asf_group_field_type_data_type ) {
							$display_group_true_false_field_data = $acf_group_field_data_value[ $field_type_data_name ];
							if ( '' === $display_group_true_false_field_data ) {
								$display_true_false_field_data = 0;
							} else {
								$display_true_false_field_data = $display_group_true_false_field_data;
							}
							echo esc_html( $display_true_false_field_data );
						} elseif ( 'message' === $asf_group_field_type_data_type ) {
							$asf_group_message_field_return_value = $field_type_data_list['message'];
							echo esc_html( $asf_group_message_field_return_value );
						} elseif ( 'checkbox' === $asf_group_field_type_data_type ) {
							$asf_group_checkbox_field_return_value = $field_type_data_list['return_format'];
							if ( 'value' === $asf_group_checkbox_field_return_value || 'label' === $asf_group_checkbox_field_return_value ) {
								$asf_group_checkbox_values = $acf_group_field_data_value[ $field_type_data_name ];
								foreach ( $asf_group_checkbox_values as $asf_group_checkbox_value ) {
									echo esc_html( $asf_group_checkbox_value );
								}
							} else {
								$asf_group_checkbox_values = $acf_group_field_data_value[ $field_type_data_name ];
								foreach ( $asf_group_checkbox_values as $asf_group_checkbox_value ) {
									$asf_group_final_checkbox_value = $asf_group_checkbox_value['value'];
									$asf_group_final_checkbox_label = $asf_group_checkbox_value['label'];
									$asf_group_checkbox_value       = $asf_group_final_checkbox_value . ' : ' . $asf_group_final_checkbox_label;
									echo esc_html( $asf_group_checkbox_value );
								}
							}
						} elseif ( 'post_object' === $asf_group_field_type_data_type ) {
							$asf_group_post_object_field_return_value = $field_type_data_list['return_format'];
							$asf_group_post_object_field_multiple     = $field_type_data_list['multiple'];
							$display_group_post_object_field_data     = $acf_group_field_data_value[ $field_type_data_name ];
							if ( 1 == $asf_group_post_object_field_multiple ) {
								foreach ( $display_group_post_object_field_data as $group_post ) {
									$post_object_title = get_the_title( $group_post );
									echo esc_html( $post_object_title );
								}
							} else {
								$post_object_title = get_the_title( $display_group_post_object_field_data );
								echo esc_html( $post_object_title );
							}
						} elseif ( 'page_link' === $asf_group_field_type_data_type ) {
							$asf_group_page_link_field_multiple = $field_type_data_list['multiple'];
							$display_group_page_link_field_data = $acf_group_field_data_value[ $field_type_data_name ];
							if ( 1 == $asf_group_page_link_field_multiple ) {
								foreach ( $display_group_page_link_field_data as $group_post ) {
									$page_link_title = $group_post;
									echo esc_html( $page_link_title );
								}
							} else {
								echo esc_html( $display_group_page_link_field_data );
							}
						} elseif ( 'relationship' === $asf_group_field_type_data_type ) {
							$asf_group_relationship_field_return_value = $field_type_data_list['return_format'];
							$display_group_relationship_field_data     = $acf_group_field_data_value[ $field_type_data_name ];
							setup_postdata( $display_group_relationship_field_data );
							foreach ( $display_group_relationship_field_data as $relationship_post ) {
								if ( 'object' === $asf_group_relationship_field_return_value ) {
									$relationship_post_type_attachment = $relationship_post->post_type;
									if ( 'attachment' === $relationship_post_type_attachment ) {
										?>
										<img src="<?php echo esc_url( $relationship_post->guid ); ?>">
										<?php
									} else {
										$relationship_group_title = get_the_title( $relationship_post->ID );
										echo esc_html( $relationship_group_title );
									}
								} else {
										$relationship_group_title = get_the_title( $relationship_post );
										echo esc_html( $relationship_group_title );
								}
							}
						} elseif ( 'taxonomy' === $asf_group_field_type_data_type ) {
							$display_group_taxonomy_field_data = $acf_group_field_data_value[ $field_type_data_name ];
							foreach ( $display_group_taxonomy_field_data as $taxonomy_field ) {
								$term_data     = get_term( $taxonomy_field );
								$taxonomy_name = $term_data->name;
								echo esc_html( $taxonomy_name );
							}
						} elseif ( 'user' === $asf_group_field_type_data_type ) {
							$asf_group_user_field_return_value  = $field_type_data_list['return_format'];
							$asf_group_user_link_field_multiple = $field_type_data_list['multiple'];
							$display_group_user_field_data      = $acf_group_field_data_value[ $field_type_data_name ];
							if ( 1 == $asf_group_user_link_field_multiple ) {
								foreach ( $display_group_user_field_data as $group_user ) {
									if ( 'id' === $asf_group_user_field_return_value ) {
										$user_data = get_user_by( 'ID', $group_user );
										$user_name = $user_data->user_login;
										echo esc_html( $user_name );
									} elseif ( 'object' === $asf_group_user_field_return_value ) {
										$user_name = $group_user->user_login;
										echo esc_html( $user_name );
									} elseif ( 'array' === $asf_group_user_field_return_value ) {
										$user_name = $group_user['display_name'];
										echo esc_html( $user_name );
									}
								}
							} elseif ( 'id' === $asf_group_user_field_return_value ) {
									$user_data     = get_user_by( 'ID', $display_group_user_field_data );
										$user_name = $user_data->user_login;
										echo esc_html( $user_name );
							} elseif ( 'object' === $asf_group_user_field_return_value ) {
									$user_name = $display_group_user_field_data->user_login;
									echo esc_html( $user_name );
							} else {
								$user_name = $display_group_user_field_data['display_name'];
								echo esc_html( $user_name );
							}
						} elseif ( 'google_map' === $asf_group_field_type_data_type ) {
							$display_group_field_data = $acf_group_field_data_value[ $field_type_data_name ];
							$group_lat                = $display_group_field_data['lat'];
							$group_lng                = $display_group_field_data['lng'];
							?>
							<div class="acf-map"><div class="marker" data-lat="<?php echo esc_attr( $group_lat ); ?>" data-lng="<?php echo esc_attr( $group_lng ); ?>"></div></div>
							<?php
						} else {
							$display_group_field_data = $acf_group_field_data_value[ $field_type_data_name ];
							echo wp_kses( $display_group_field_data, Bdp_Admin_Functions::args_kses() );
						}
					}
					echo '</br>';
				} else {
					echo wp_kses( $display_field_data, Bdp_Admin_Functions::args_kses() );
				}
				echo '</div>';
			}
		}
	}
	/**
	 * Select Acf field on change post type
	 *
	 * @since 2.5.1
	 * @return void
	 */
	public function get_acf_field_list() {
		ob_start();
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			if ( isset( $_POST['posttype'] ) && is_plugin_active( 'advanced-custom-fields/acf.php' ) ) {
				$posttype = esc_attr( sanitize_text_field( wp_unslash( $_POST['posttype'] ) ) );
				$post_id  = get_posts(
					array(
						'fields'         => 'ids',
						'posts_per_page' => -1,
					)
				);
				$groups   = acf_get_field_groups( array( 'post_type' => $posttype ) );
				?>
				<div class="bdp-left"><span class="bdp-key-title"><?php esc_attr_e( 'Select ACF Field', 'blog-designer-pro' ); ?></span></div>
				<div class="bdp-right">
					<?php
					$bdp_acf_field = isset( $bdp_settings['bdp_acf_field'] ) ? $bdp_settings['bdp_acf_field'] : array();
					?>
					<select data-placeholder="<?php esc_attr_e( 'Choose acf field', 'blog-designer-pro' ); ?>" class="chosen-select" multiple style="width:220px;" name="bdp_acf_field[]" id="bdp_acf_field">
						<?php
						foreach ( $groups as $group ) {
							$group_id                                 = $group['ID'];
							$group_title                              = $group['title'];
							$all_acf_data[ $group_id ]                = array();
							$all_acf_data[ $group_id ]['group_id']    = $group_id;
							$all_acf_data[ $group_id ]['group_title'] = $group_title;
							$fields                                   = acf_get_fields( $group_id );
							if ( $fields ) {
								$all_acf_data[ $group_id ]['fields'] = array();
								$val_fields                          = 0;
								foreach ( $fields as $field ) {
									$field_id    = $field['ID'];
									$field_label = $field['label'];
									$field_key   = $field['key'];
									?>
									<option value="<?php echo esc_attr( $field_id ); ?>"><?php echo esc_html( $field_label ); ?></option>
									<?php
								}
							}
						}
						?>
					</select>
				</div>
				<?php
				$data = ob_get_clean();
				echo $data;
				die();
			}
		}
	}
}
new Bdp_Template_Acf();
