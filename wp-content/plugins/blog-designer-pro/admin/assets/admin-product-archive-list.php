<?php
/**
 * Admin Product Archive List
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
global $wpdb;
$paged = filter_input( INPUT_GET, 'paged' ) ? absint( filter_input( INPUT_GET, 'paged' ) ) : 1;
if ( ! is_numeric( $paged ) ) {
	$paged = 1;
}
$limit         = 10;
$user          = get_current_user_id();
$screen        = get_current_screen();
$screen_option = $screen->get_option( 'per_page', 'option' );
$limit         = get_user_meta( $user, $screen_option, true );
if ( empty( $limit ) || $limit < 1 ) {
	// get the default value if none is set.
	$limit = $screen->get_option( 'per_page', 'default' );
}
$offset = ( $paged - 1 ) * $limit;
$where  = '';
$search = '';
if ( isset( $_REQUEST['s'] ) && '' != $_REQUEST['s'] ) {
	$search = esc_attr( sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ) );
	$where  = "WHERE product_archive_name LIKE '%$search%'";
}

if ( isset( $_POST['btnSearchArchive'] ) || ( isset( $_POST['s'] ) && '' != $_POST['s'] ) ) {
	$delete_action = '';
	if ( isset( $_POST['take_action'] ) && isset( $_POST['bdp-action-top'] ) || isset( $_POST['take_action'] ) && isset( $_POST['bdp-archive-product-action'] ) ) {
		$delete_action = 'multiple_delete';
	}
	?>
	<script type="text/javascript">
		var paged = '<?php echo esc_attr( $paged ); ?>';
		var s = ['<?php echo esc_attr( $search ); ?>'];
		var action = ['<?php echo esc_attr( $delete_action ); ?>'];
		window.location.pushState({ path: document.location.href }, '', document.location.href + "&paged=" + paged + "&s=" + s + "&action=" + action);
	</script>
	<?php
}
$ord = 0;
if ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) && 0 == $_REQUEST['order'] ) {
	$order_by    = 'desc';
	$ord         = 1;
	$order_field = 'product_archive_name';
} elseif ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) && 1 == $_REQUEST['order'] ) {
	$order_by    = 'asc';
	$ord         = 0;
	$order_field = 'product_archive_name';
} else {
	$order_by    = 'desc';
	$order_field = 'id';
}

$condition             = '';
$product_archive_table = $wpdb->prefix . 'bdp_product_archives';
if ( isset( $_GET['list'] ) ) {
	if ( isset( $_REQUEST['s'] ) && '' != $_REQUEST['s'] ) {
		$condition = " or product_archive_template = '" . esc_attr( sanitize_text_field( wp_unslash( $_GET['list'] ) ) ) . "_template'";
	} else {
		$condition = " WHERE product_archive_template = '" . esc_attr( sanitize_text_field( wp_unslash( $_GET['list'] ) ) ) . "_template'";
	}
	if ( 'all' === $_GET['list'] ) {
		$condition = '';
	}
	$qry          = "SELECT COUNT('id') FROM $product_archive_table" . $where . $condition;// ignore:phpcs
	$total        = $wpdb->get_var( $qry );
	$num_of_pages = ceil( $total / $limit );
} else {
	$total        = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(`id`) FROM ' . $wpdb->prefix . 'bdp_product_archives %s", $where ) );
	$num_of_pages = ceil( $total / $limit );
}
$next_page = (int) $paged + 1;
if ( $next_page > $num_of_pages ) {
	$next_page = $num_of_pages;
}
$prev_page = (int) $paged - 1;
if ( $prev_page < 1 ) {
	$prev_page = 1;
}

// Get the archive information.
if ( isset( $_GET['list'] ) ) {
	if ( isset( $_REQUEST['s'] ) && '' != $_REQUEST['s'] ) {
		$condition = " or product_archive_template = '" . esc_attr( sanitize_text_field( wp_unslash( $_GET['list'] ) ) ) . "_template'";
	} else {
		$condition = " WHERE product_archive_template = '" . esc_attr( sanitize_text_field( wp_unslash( $_GET['list'] ) ) ) . "_template'";
	}
	if ( 'all' === $_GET['list'] ) {
		$archives = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}bdp_product_archives %1s order by %1s %1s limit %d, %d", $where, $order_field, $order_by, $offset, $limit ) );
	} else {
		$archives = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}bdp_product_archives %1s %1s order by %1s %1s limit %d, %d", $where, $condition, $order_field, $order_by, $offset, $limit ) );
	}
} else {
	$archives = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}bdp_product_archives %1s order by %1s %1s limit %d, %d", $where, $order_field, $order_by, $offset, $limit ) );
}
?>
<div class="bdp-admin wrap bdp-archive-list">
	<?php
	if ( isset( $_GET['action'] ) && 'delete' === $_GET['action'] && isset( $_GET['id'] ) && ! empty( $_GET['id'] ) && isset( $_GET['page'] ) && 'product_archive_layouts' === $_GET['page'] && isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) ) ) {
		?>
		<div class="updated">
			<p>
				<?php
				esc_html_e( 'Product archive layout has been deleted successfully.', 'blog-designer-pro' );
				?>
			</p>
		</div>
		<?php
	}
	if ( isset( $_POST['bdp-action-top'] ) && 'delete_pr' === esc_html( sanitize_text_field( wp_unslash( $_POST['bdp-action-top'] ) ) ) || isset( $_POST['bdp-archive-product-action'] ) && 'delete_pr' === esc_html( sanitize_text_field( wp_unslash( $_POST['bdp-archive-product-action'] ) ) ) ) {
		if ( isset( $_POST['chk_remove_all'] ) && ! empty( $_POST['chk_remove_all'] ) ) {
			?>
			<div class="updated">
				<p>
					<?php esc_html_e( 'Product archive layouts have been deleted successfully.', 'blog-designer-pro' ); ?>
				</p>
			</div>
			<?php
		}
	}
	if ( isset( $_GET['msg'] ) || ( isset( $_GET['action'] ) ) && 'multiple_delete' === $_GET['action'] ) {
		?>
		<div class="updated">
			<p>
				<?php
				if ( isset( $_GET['action'] ) && 'multiple_delete' === $_GET['action'] ) {
					esc_html_e( 'Product archive layouts have been deleted successfully.', 'blog-designer-pro' );
				}
				if ( isset( $_GET['msg'] ) && 'added' === $_GET['msg'] ) {
					esc_html_e( 'Designer Settings has been added.', 'blog-designer-pro' );
				}
				if ( isset( $_GET['msg'] ) && 'updated' === $_GET['msg'] ) {
					esc_html_e( 'Designer Settings has been updated.', 'blog-designer-pro' );
				}
				?>
			</p>
		</div>
		<?php
	}
	?>
	<!-- Create new Archive Layout button -->
	<h1>
		<?php esc_html_e( 'Product Archive Layouts', 'blog-designer-pro' ); ?>
		<a class="page-title-action" href="?page=bdp_add_product_archive_layout">
			<?php esc_html_e( 'Create New Archive Layout', 'blog-designer-pro' ); ?>
		</a>
	</h1>
	<form method="post">
		<ul class="subsubsub">
			<?php
			$total      = ( $total > 0 ) ? $total : '0';
			$curr_class = '';
			$curr_class = ( ( isset( $_GET['list'] ) ) && ( 'all' === $_GET['list'] ) || ( ! isset( $_GET['list'] ) ) ) ? 'current' : '';
			?>
			<li class="all">
				<?php $all_total = $wpdb->get_var( 'SELECT COUNT(`id`) FROM ' . $wpdb->prefix . 'bdp_product_archives ' ); ?>
				<a class="<?php echo esc_html( $curr_class ); ?>" href="?page=product_archive_layouts&list=all"><?php esc_html_e( 'All', 'blog-designer-pro' ); ?> <span class="count">(<?php echo esc_html( $all_total ); ?>)</span></a> |
			</li>
			<li class="category">
				<?php
				$category_total = $wpdb->get_var( 'SELECT COUNT(`id`) FROM ' . $wpdb->prefix . 'bdp_product_archives WHERE product_archive_template = "category_template"' );
				$curr_class     = ( ( isset( $_GET['list'] ) ) && ( 'category' === $_GET['list'] ) ) ? 'current' : '';
				?>
				<a class="<?php echo esc_attr( $curr_class ); ?>" href="?page=product_archive_layouts&list=category"><?php esc_html_e( 'Category', 'blog-designer-pro' ); ?> <span class="count">(<?php echo esc_html( $category_total ); ?>)</span></a> |
			</li>
			<li class="tag">
				<?php
				$tag_total  = $wpdb->get_var( 'SELECT COUNT(`id`) FROM ' . $wpdb->prefix . 'bdp_product_archives WHERE product_archive_template = "tag_template"' );
				$curr_class = ( ( isset( $_GET['list'] ) ) && ( 'tag' === $_GET['list'] ) ) ? 'current' : '';
				?>
				<a class="<?php echo esc_attr( $curr_class ); ?>" href="?page=product_archive_layouts&list=tag"><?php esc_html_e( 'Tag', 'blog-designer-pro' ); ?> <span class="count">(<?php echo esc_html( $tag_total ); ?>)</span></a>
			</li>
		   
		</ul>
		<p class="search-box">
			<input id="shortcode-search-input" type="search" value="<?php echo esc_attr( $search ); ?>" name="s">
			<input id="search-submit" class="button" type="submit" name="btnSearchArchive" value="<?php esc_attr_e( 'Search Layout', 'blog-designer-pro' ); ?>">
		</p>
		<div class="tablenav top">
			<select name="bdp-action-top">
				<option selected="selected" value="none"><?php esc_html_e( 'Bulk Actions', 'blog-designer-pro' ); ?></option>
				<option value="delete_pr"><?php esc_html_e( 'Delete Permanently', 'blog-designer-pro' ); ?></option>
				<option value="bdp_export"><?php esc_html_e( 'Export Layout', 'blog-designer-pro' ); ?></option>
			</select>
			<?php wp_nonce_field( 'bdp_take_action', 'bdp_take_action_nonce' ); ?>
			<input id="take_action" name="take_action" class="button action" type="submit" value="<?php esc_attr_e( 'Apply', 'blog-designer-pro' ); ?>" >
			<div class="tablenav-pages" 
			<?php
			if ( (int) $num_of_pages <= 1 ) {
				echo 'style="display:none;"';
			}
			?>
			>
				<?php $list = isset( $_GET['list'] ) ? '&list=' . esc_attr( sanitize_text_field( wp_unslash( $_GET['list'] ) ) ) : ''; ?>
				<span class="displaying-num"><?php echo esc_html( number_format_i18n( $total ) ) . ' ' . sprintf( esc_attr( _n( 'item', 'items', $total, 'blog-designer-pro' ) ), esc_html( number_format_i18n( $total ) ) ); ?></span>
				<span class="pagination-links">
					<?php if ( '1' == $paged ) { ?>
						<span class="tablenav-pages-navspan" aria-hidden="true">&laquo;</span>
						<span class="tablenav-pages-navspan" aria-hidden="true">&lsaquo;</span>
					<?php } else { ?>
						<a class="first-page" href="<?php echo '?page=product_archive_layouts' . esc_attr( $list ) . '&paged=1&s=' . esc_attr( $search ); ?>" title="<?php esc_attr_e( 'Go to the first page', 'blog-designer-pro' ); ?>">&laquo;</a>
						<a class="prev-page" href="<?php echo '?page=product_archive_layouts' . esc_attr( $list ) . '&paged=' . esc_attr( $prev_page ) . '&s=' . esc_attr( $search ); ?>" title="<?php esc_attr_e( 'Go to the previous page', 'blog-designer-pro' ); ?>">&lsaquo;</a>
					<?php } ?>
					<span class="paging-input">
						<span class="total-pages"><?php echo esc_html( $paged ); ?></span>
						<?php esc_html_e( 'of', 'blog-designer-pro' ); ?>
						<span class="total-pages"><?php echo esc_html( $num_of_pages ); ?></span>
					</span>
					<?php if ( $paged == $num_of_pages ) { ?>
						<span class="tablenav-pages-navspan" aria-hidden="true">&rsaquo;</span>
						<span class="tablenav-pages-navspan" aria-hidden="true">&raquo;</span>
					<?php } else { ?>
						<a class="next-page " href="<?php echo '?page=product_archive_layouts' . esc_attr( $list ) . '&paged=' . esc_attr( $next_page ) . '&s=' . esc_attr( $search ); ?>" title="<?php esc_attr_e( 'Go to the next page', 'blog-designer-pro' ); ?>">&rsaquo;</a>
						<a class="last-page " href="<?php echo '?page=product_archive_layouts' . esc_attr( $list ) . '&paged=' . esc_attr( $num_of_pages ) . '&s=' . esc_attr( $search ); ?>" title="<?php esc_attr_e( 'Go to the last page', 'blog-designer-pro' ); ?>">&raquo;</a>
					<?php } ?>
				</span>
			</div>
		</div>
		<table id="username" class="wp-list-table widefat fixed striped bdp-sliders-list bdp-table bdp-sliders-list bdp-table">
			<?php
			$list   = isset( $_GET['list'] ) ? '&list=' . esc_attr( sanitize_text_field( wp_unslash( $_GET['list'] ) ) ) : '';
			$paging = isset( $_GET['paged'] ) ? '&paged=' . esc_attr( sanitize_text_field( wp_unslash( $_GET['paged'] ) ) ) : '';
			?>
			<thead>
				<tr>
					<td class="manage-column check-column" id="cb"><input type="checkbox" name="delete-all-layouts-1" id="delete-all-layouts-1" value="0"></td>
					<th class="manage-column column-product_archive_name column-title sorted <?php echo esc_attr( $order_by ); ?>" scope="col" id="product_archive_name">
						<a href="?page=product_archive_layouts<?php echo esc_attr( $list ); ?>&orderby=product_archive_name&order=<?php echo esc_attr( $ord ); ?>">
							<span><?php esc_html_e( 'Archive Name', 'blog-designer-pro' ); ?></span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
					<th class="manage-column column-product_archive_template" id="product_archive_template"><?php esc_html_e( 'Archive Template', 'blog-designer-pro' ); ?></th>
					<th class="manage-column column-template_name" id="template_name"><?php esc_html_e( 'Template Name', 'blog-designer-pro' ); ?></th>
					<td class="manage-column column-categories"><?php esc_html_e( 'Categories', 'blog-designer-pro' ); ?></td>
					<td class="manage-column column-tags"><?php esc_html_e( 'Tags', 'blog-designer-pro' ); ?></td>
				</tr>
			</thead>
			<tbody id="the_list">
				<?php
				if ( ! $archives ) {
					echo '<tr>';
					echo '<td colspan="6" style="text-align: center;">';
					esc_html_e( 'No Archive layout found.', 'blog-designer-pro' );
					echo '</td>';
					echo '</tr>';
				} else {
					$archives_cnt = 0;
					foreach ( $archives as $archive ) {
						$allsettings = $archive->settings;
						if ( is_serialized( $allsettings ) ) {
							$bdp_settings = maybe_unserialize( $allsettings );
						}
						$cat = '—';
						$tag = '—';
						if ( isset( $bdp_settings['template_tags'] ) && ! empty( $bdp_settings['template_tags'] ) && 'tag_template' === $archive->product_archive_template ) {
							$tags = $bdp_settings['template_tags'];
							$tag  = array();
							foreach ( $tags as $t ) {
								$tag_name = get_tag( $t );
								$tagg     = get_term_by( 'id', $t, 'product_tag' );
								$tag[]    = $tagg->name;
							}
							$tag = join( ', ', $tag );
						}
						if ( isset( $bdp_settings['template_category'] ) && ! empty( $bdp_settings['template_category'] ) && 'category_template' === $archive->product_archive_template ) {
							$categories = $bdp_settings['template_category'];
							$cat        = array();
							foreach ( $categories as $t ) {
								$all_cat = get_term_by( 'id', $t, 'product_cat' );
								$cat[]   = $all_cat->name;
							}
							$cat = join( ', ', $cat );
						}
						$product_archive_name = $archive->product_archive_name;
						++$archives_cnt;

						echo '<tr>';
						?>
					<th class="check-column">
						<input type="checkbox" class="chk_remove_all" name="chk_remove_all[]" id="chk_remove_all" value="<?php echo esc_attr( $archive->id ); ?>">
					</th>
					<td class="title column-title">
						<strong>
							<a href="<?php echo '?page=bdp_add_product_archive_layout&action=edit&id=' . esc_attr( $archive->id ); ?>">
								<?php
								if ( ! empty( $product_archive_name ) ) {
									echo esc_html( stripslashes( $product_archive_name ) );
								} else {
									echo '(' . esc_html__( 'no title', 'blog-designer-pro' ) . ')';
								}
								?>
							</a>
						</strong>
						<div class="row-actions">
							<span class="edit">
								<a title="<?php esc_attr_e( 'Edit this item', 'blog-designer-pro' ); ?>" href="<?php echo '?page=bdp_add_product_archive_layout&action=edit&id=' . esc_attr( $archive->id ); ?>"><?php esc_html_e( 'Edit', 'blog-designer-pro' ); ?></a>
								|
							</span>
							<span class="duplicate">
								<a title="<?php esc_attr_e( 'Duplicate this item', 'blog-designer-pro' ); ?>" href="<?php echo esc_url( add_query_arg( 'action', 'duplicate_product_archive_in_edit', admin_url( 'admin.php?layout=' . $archive->id ) ) ); ?>"><?php esc_html_e( 'Duplicate', 'blog-designer-pro' ); ?></a>
								|
							</span>
							<span class="delete">
								<?php
								$list   = isset( $_GET['list'] ) ? '&list=' . esc_attr( sanitize_text_field( wp_unslash( $_GET['list'] ) ) ) : '';
								$paging = isset( $_GET['paged'] ) ? '&paged=' . esc_attr( sanitize_text_field( wp_unslash( $_GET['paged'] ) ) ) : '';
								?>
								<a title="<?php esc_attr_e( 'Delete this item', 'blog-designer-pro' ); ?>" href="<?php echo esc_url( wp_nonce_url( '?page=product_archive_layouts' . $list . $paging . '&action=delete&id=' . $archive->id ) ); ?>" onclick="return confirm('Do you want to delete this layout?');"><?php esc_html_e( 'Delete', 'blog-designer-pro' ); ?></a>
							</span>
						</div>
					</td>
					<td class="column-archive-template">
						<?php
						if ( isset( $archive->product_archive_template ) ) {
							if ( 'category_template' === $archive->product_archive_template ) {
								esc_attr_e( 'Category Archive Template', 'blog-designer-pro' );
							} elseif ( 'tag_template' === $archive->product_archive_template ) {
								esc_attr_e( 'Tag Archive Template', 'blog-designer-pro' );
							}
						}
						?>
					</td>
					<td class="template_name column-template_name"><?php echo esc_attr( ucwords( str_replace( '_', ' ', esc_html( $bdp_settings['template_name'] ) ) ) ); ?></td>
					<td class="categories column-categories"><?php echo esc_html( $cat ); ?></td>
					<td class="tags column-tags"><?php echo esc_html( $tag ); ?></td>
						<?php
						echo '</tr>';
					}
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<td class="manage-column check-column"><input type="checkbox" name="delete-all-shortcodes-2" id="delete-all-shortcodes-2" value="0"></td>
					<td class="manage-column column-product_archive_name"><?php esc_html_e( 'Archive Name', 'blog-designer-pro' ); ?></td>
					<th class="manage-column column-archive-template"><?php esc_html_e( 'Archive Template', 'blog-designer-pro' ); ?></th>
					<th class="manage-column column-template_name" id="template_name"><?php esc_html_e( 'Template Name', 'blog-designer-pro' ); ?></th>
					<td class="manage-column column-categories"><?php esc_html_e( 'Categories', 'blog-designer-pro' ); ?></td>
					<td class="manage-column column-tags"><?php esc_html_e( 'Tags', 'blog-designer-pro' ); ?></td>
				</tr>
			</tfoot>
		</table>
		<div class="bottom-delete-form">
			<select name="bdp-archive-product-action">
				<option selected="selected" value="none"><?php esc_html_e( 'Bulk Actions', 'blog-designer-pro' ); ?></option>
				<option value="delete_pr"><?php esc_html_e( 'Delete Permanently', 'blog-designer-pro' ); ?></option>
				<option value="bdp_export"><?php esc_html_e( 'Export Layout', 'blog-designer-pro' ); ?></option>
			</select>
			<input id="take_action" name="take_action" class="button action" type="submit" value="<?php esc_attr_e( 'Apply', 'blog-designer-pro' ); ?>" >
			<?php if ( $archives ) { ?>
				<div class="tablenav bottom">
					<div class="tablenav-pages" 
					<?php
					if ( (int) $num_of_pages <= 1 ) {
						echo 'style="display:none;"';
					}
					?>
					>
						<?php $list = isset( $_GET['list'] ) ? '&list=' . esc_attr( sanitize_text_field( wp_unslash( $_GET['list'] ) ) ) : ''; ?>
						<span class="displaying-num"><?php echo esc_html( number_format_i18n( $total ) ) . ' ' . sprintf( esc_attr( _n( 'item', 'items', $total, 'blog-designer-pro' ) ), esc_html( number_format_i18n( $total ) ) ); ?></span>
						<span class="pagination-links">
							<?php if ( '1' == $paged ) { ?>
								<span class="tablenav-pages-navspan" aria-hidden="true">&laquo;</span>
								<span class="tablenav-pages-navspan" aria-hidden="true">&lsaquo;</span>
							<?php } else { ?>
								<a class="first-page" href="<?php echo '?page=product_archive_layouts' . esc_attr( $list ) . '&paged=1&s=' . esc_attr( $search ); ?>" title="<?php esc_attr_e( 'Go to the first page', 'blog-designer-pro' ); ?>">&laquo;</a>
								<a class="prev-page" href="<?php echo '?page=product_archive_layouts' . esc_attr( $list ) . '&paged=' . esc_attr( $prev_page ) . '&s=' . esc_attr( $search ); ?>" title="<?php esc_attr_e( 'Go to the previous page', 'blog-designer-pro' ); ?>">&lsaquo;</a>
							<?php } ?>
							<span class="paging-input">
								<span class="total-pages"><?php echo esc_html( $paged ); ?></span>
								<?php esc_html_e( 'of', 'blog-designer-pro' ); ?>
								<span class="total-pages"><?php echo esc_html( $num_of_pages ); ?></span>
							</span>
							<?php if ( $paged == $num_of_pages ) { ?>
								<span class="tablenav-pages-navspan" aria-hidden="true">&rsaquo;</span>
								<span class="tablenav-pages-navspan" aria-hidden="true">&raquo;</span>
							<?php } else { ?>
								<a class="next-page " href="<?php echo '?page=product_archive_layouts' . esc_attr( $list ) . '&paged=' . esc_attr( $next_page ) . '&s=' . esc_attr( $search ); ?>" title="<?php esc_attr_e( 'Go to the next page', 'blog-designer-pro' ); ?>">&rsaquo;</a>
								<a class="last-page " href="<?php echo '?page=product_archive_layouts' . esc_attr( $list ) . '&paged=' . esc_attr( $num_of_pages ) . '&s=' . esc_attr( $search ); ?>" title="<?php esc_attr_e( 'Go to the last page', 'blog-designer-pro' ); ?>">&raquo;</a>
							<?php } ?>
						</span>
					</div>
				</div>
			<?php } ?>
		</div>
	</form>
</div>
