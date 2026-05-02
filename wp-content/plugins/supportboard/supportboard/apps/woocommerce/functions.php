<?php

/*
 * ==========================================================
 * WOOCOMMERCE APP
 * ==========================================================
 *
 * WooCommerce app. © 2017-2025 board.support. All rights reserved.
 *
 */

define('SB_WOOCOMMERCE', '1.1.5');

/*
 * -----------------------------------------------------------
 * PANEL DATA
 * -----------------------------------------------------------
 *
 * Return the user details for the conversations panel
 *
 */

function sb_woocommerce_get_conversation_details($user_id) {
    $response = ['products' => 0, 'total' => 0, 'orders_count' => 0, 'currency_symbol' => sb_get_setting('wc-currency-symbol', ''), 'cart' => 0, 'orders' => []];
    $session_key = sb_woocommerce_get_session_key($user_id);
    if ($session_key === false) {
        return $response;
    }
    $session = sb_woocommerce_get_session($session_key);
    $total = 0;
    $orders = sb_woocommerce_get_user_orders($user_id);
    for ($i = 0; $i < count($orders); $i++) {
        $total += floatval($orders[$i]['total']);
    }
    $response['total'] = round($total, 2);
    $response['orders'] = $orders;
    $response['orders_count'] = count($orders);
    if ($session && sb_isset($session, 'cart')) {
        $products_cart = [];
        foreach ($session['cart'] as $value) {
            $product = sb_woocommerce_get_product($value['product_id']);
            $product['price'] = $value['line_subtotal'];
            $product['quantity'] = $value['quantity'];
            array_push($products_cart, $product);
        }
        $response['cart'] = $products_cart;
    }
    return $response;
}

/*
 * -----------------------------------------------------------
 * USERS
 * -----------------------------------------------------------
 *
 * 1. Send a chat message if returning visitor
 * 2. Return the session user
 *
 */

function sb_woocommerce_returning_visitor() {
    $user = sb_get_active_user();
    if ($user) {
        $message = sb_get_multi_setting('wc-returning-visitor', 'wc-returning-visitor-message');
        $conversation_id = sb_get_last_conversation_id_or_create($user['id'], 3);
        if (sb_is_error($conversation_id)) {
            return $conversation_id;
        }
        return sb_send_message(sb_get_bot_id(), $conversation_id, sb_woocommerce_merge_fields($message, ['coupon-discount' => sb_get_multi_setting('wc-returning-visitor', 'wc-returning-visitor-coupon-discount'), 'coupon-expiration' => sb_get_multi_setting('wc-returning-visitor', 'wc-returning-visitor-coupon-expiration') . ' seconds', 'user-id' => $user['id']]), [], -1, '{ "event": "open-chat" }');
    }
    return false;
}

function sb_woocommerce_get_customer($session_key = false) {
    $session = sb_woocommerce_get_session($session_key);
    if ($session) {
        $session = $session['customer'];
        $query = '';
        if (sb_isset($session, 'id') && $session['id'] != '0') {
            $query = 'customer_id = "' . $session['id'] . '"';
        } else if (sb_isset($session, 'email')) {
            $query = 'email = "' . $session['email'] . '"';
        }
        return $query ? sb_db_get('SELECT * FROM ' . SB_WP_PREFIX . 'wc_customer_lookup WHERE ' . $query . ' LIMIT 1') : false;
    }
    return false;
}

/*
 * ----------------------------------------------------------
 * CART, ORDERS, AND CHECKOUT
 * ----------------------------------------------------------
 *
 * 1. Recover abandoned carts
 * 2. Return the orders of the user
 * 3. Return the last order of the user
 * 4. Return the order details
 *
 */

function sb_woocommerce_abandoned_carts($cart_item = false) {
    $notifications = [sb_get_multi_setting('wc-abandoned-cart', 'wc-abandoned-cart-1'), intval(sb_get_multi_setting('wc-abandoned-cart', 'wc-abandoned-cart-2'))];
    if (!empty($notifications[0])) {
        $carts = sb_db_get('SELECT * FROM ' . SB_WP_PREFIX . 'woocommerce_sessions', []);
        if ($cart_item)
            array_push($carts, $cart_item);
        if (is_array($carts) && count($carts)) {
            $now = time();
            $index = empty($notifications[1]) ? 1 : 2;
            $notifications = [($notifications[0] == 'now' ? 0 : (intval($notifications[0]) * 3600)), (empty($notifications[1]) ? 0 : ($notifications[1] * 3600))];
            $emails = sb_get_external_setting('wc-emails');
            $admin_notification = sb_get_multi_setting('wc-abandoned-cart', 'wc-abandoned-cart-notify-admin');
            $admin_notification_code = '';
            $users = [];

            // History
            $history = sb_get_external_setting('wc-abandoned-carts');
            $history_updated = [];
            $history_users = [];
            $notification_sent = false;
            if (!$history || $history == false) {
                $history = [];
            }
            for ($i = 0; $i < count($carts); $i++) {
                $user = sb_db_get('SELECT A.* FROM sb_users A, sb_users_data B WHERE B.slug = "woocommerce_session_key" AND B.value = "' . $carts[$i]['session_key'] . '" AND B.user_id = A.id');
                if (empty($user['email']) && isset($carts[$i]['customer'])) {
                    $user['email'] = sb_isset(unserialize($carts[$i]['customer']), 'email', '');
                }
                array_push($users, $user);
                if (!empty($user))
                    array_push($history_updated, sb_isset($user, 'id', sb_isset($user, 'email')));
            }
            sb_save_external_setting('wc-abandoned-carts', $history_updated);

            // Process abandoned carts
            for ($i = 0; $i < count($carts); $i++) {
                $user = $users[$i];
                if (empty($user) || in_array($user['email'], $history) || in_array($user['id'], $history))
                    continue;
                $creation_time = intval($carts[$i]['session_expiry']) - 259200;
                for ($j = 0; $j < $index; $j++) {
                    if ($now > ($creation_time + $notifications[$j])) {
                        $cart = unserialize($carts[$i]['session_value']);
                        $cart_products = isset($cart['cart']) ? unserialize($cart['cart']) : [];
                        if (count($cart_products)) {
                            $customer_email = sb_isset($user, 'email');
                            $user_name = sb_get_user_name($user);
                            $email_subject = false;
                            $email_content = false;
                            $chat_message = sb_get_multi_setting('wc-abandoned-cart', 'wc-abandoned-cart-message-' . ($j + 1));
                            $discount = sb_get_multi_setting('wc-abandoned-cart', 'wc-abandoned-cart-coupon-discount');
                            $parameters = [];
                            $notification_sent = true;
                            $products = [];
                            $parameters['user-name'] = $user_name;

                            if (count($emails) && !empty($emails['wc-email-1'][0]['wc-email-1-subject'][0]) && !empty($emails['wc-email-1'][0]['wc-email-1-content'][0])) {
                                $email_subject = $emails['wc-email-' . ($j + 1)][0]['wc-email-' . ($j + 1) . '-subject'][0];
                                $email_content = $emails['wc-email-' . ($j + 1)][0]['wc-email-' . ($j + 1) . '-content'][0];
                            }

                            // Coupon
                            if (strpos($email_content, '{coupon}') !== false || strpos($chat_message, '{coupon}') !== false) {
                                $coupon = sb_woocommerce_coupon($discount, sb_get_multi_setting('wc-abandoned-cart', 'wc-abandoned-cart-coupon-expiration') . ' days');
                                $parameters['coupon'] = is_array($coupon) ? $coupon[0] : $coupon;
                                $parameters['coupon-discount'] = $discount;
                            }

                            // Cart products
                            foreach ($cart_products as $value) {
                                $product = sb_woocommerce_get_product($value['product_id']);
                                $product['price'] = $value['line_subtotal'];
                                $product['quantity'] = $value['quantity'];
                                array_push($products, $product);
                            }
                            $parameters['products'] = $products;
                            if ($admin_notification) {
                                $admin_notification_code .= '<p style="display:block;line-height:25px;font-size:16px;color:#222;font-weight:600;font-size:12px;letter-spacing:.3px;margin:15px 0;">' . $user_name . ' | <a href="#" style="text-decoration:none;border:none;color:#666;">' . $customer_email . '</a></p>' . sb_woocommerce_merge_fields_html('list', $parameters);
                            }

                            // Send email
                            if (!empty($customer_email)) {
                                sb_email_send($customer_email, sb_woocommerce_merge_fields($email_subject, $parameters), sb_woocommerce_merge_fields($email_content, $parameters));
                                array_push($history_users, $customer_email);
                            }

                            // Send chat message
                            if ($chat_message != '' && sb_isset($user, 'id')) {
                                sb_send_message(sb_get_bot_id(), sb_get_last_conversation_id_or_create($user['id'], 3), sb_woocommerce_merge_fields($chat_message, $parameters), [], 1, '{ "event": "open-chat" }');
                                array_push($history_users, $user['id']);
                            }
                        }
                    }
                }
            }

            // Send admin notification
            if ($notification_sent && isset($emails['wc-email-admin'])) {
                $email_admin = $emails['wc-email-admin'][0]['wc-email-admin-email'][0];
                if ($admin_notification && $email_admin != '') {
                    $email_subject = $emails['wc-email-admin'][0]['wc-email-admin-subject'][0];
                    $email_content = str_replace('{carts}', $admin_notification_code, $emails['wc-email-admin'][0]['wc-email-admin-content'][0]);
                    sb_email_send($email_admin, $email_subject, $email_content);
                }
            }
        }
    }
}

function sb_woocommerce_get_user_orders($user_id) {
    return sb_db_get('SELECT A.id, A.post_status `status`, A.post_date_gmt `date`, C.meta_value `total` FROM ' . SB_WP_PREFIX . 'posts A, ' . SB_WP_PREFIX . 'postmeta B, ' . SB_WP_PREFIX . 'postmeta C WHERE A.id = B.post_id AND A.post_type = "shop_order" AND B.post_id = C.post_id AND C.meta_key = "_order_total" AND B.meta_key = "sb-user" AND B.meta_value = ' . $user_id . ' ORDER BY A.post_date DESC', false);
}

function sb_woocommerce_get_order($order_id) {
    $order = sb_db_get('SELECT A.id, A.post_status `status`, A.post_date_gmt `date`, B.meta_value `total` FROM ' . SB_WP_PREFIX . 'posts A, ' . SB_WP_PREFIX . 'postmeta B WHERE A.id = ' . $order_id . ' AND A.post_type = "shop_order" AND B.post_id = A.id AND B.meta_key = "_order_total"');
    if (!empty($order) && !sb_is_error($order)) {

        // Products
        $order['products'] = [];
        $products = sb_db_get('SELECT A.order_item_name, B.meta_key, B.meta_value FROM ' . SB_WP_PREFIX . 'woocommerce_order_items A, ' . SB_WP_PREFIX . 'woocommerce_order_itemmeta B WHERE A.order_id = ' . $order_id . ' AND A.order_item_id = B.order_item_id AND (B.meta_key = "_product_id" OR B.meta_key = "_qty" OR B.meta_key = "_line_subtotal") ORDER BY order_item_name', false);
        $count = count($products);
        $checks = [];
        for ($i = 0; $i < $count; $i++) {
            $name = $products[$i]['order_item_name'];
            if (!in_array($name, $checks)) {
                $product = ['name' => $name];
                for ($j = 0; $j < $count; $j++) {
                    if ($name == $products[$j]['order_item_name']) {
                        $key;
                        switch ($products[$j]['meta_key']) {
                            case '_product_id':
                                $key = 'id';
                                break;
                            case '_qty':
                                $key = 'quantity';
                                break;
                            case '_line_subtotal':
                                $key = 'price';
                                break;
                        }
                        $product[$key] = $products[$j]['meta_value'];
                    }
                }
                array_push($checks, $name);
                array_push($order['products'], $product);
            }
        }

        // Order details
        for ($i = 0; $i < 2; $i++) {
            $key = $i == 0 ? 'billing' : 'shipping';
            $order_details = sb_db_get('SELECT meta_key, meta_value FROM ' . SB_WP_PREFIX . 'postmeta WHERE post_id = ' . $order['id'] . ' AND (meta_key = "_' . $key . '_address_1" OR meta_key = "_' . $key . '_address_2" OR meta_key = "_' . $key . '_city" OR meta_key = "_' . $key . '_state" OR meta_key = "_' . $key . '_postcode" OR meta_key = "_' . $key . '_country" OR meta_key = "_' . $key . '_first_name" OR meta_key = "_' . $key . '_last_name")', false);
            $order_details_2 = ['_' . $key . '_address_1' => '', '_' . $key . '_address_2' => '', '_' . $key . '_city' => '', '_' . $key . '_state' => '', '_' . $key . '_postcode' => '', '_' . $key . '_country' => '', '_' . $key . '_first_name' => '', '_' . $key . '_last_name' => ''];
            for ($j = 0; $j < count($order_details); $j++) {
                $order_details_2[$order_details[$j]['meta_key']] = $order_details[$j]['meta_value'];
            }
            $order[$key . '_address'] = empty($order_details_2['_' . $key . '_first_name']) ? '' : trim(str_replace('\n\n', '\n', $order_details_2['_' . $key . '_first_name'] . ' ' . $order_details_2['_' . $key . '_last_name'] . '\n' . $order_details_2['_' . $key . '_address_1'] . '\n' . $order_details_2['_' . $key . '_address_2'] . '\n' . $order_details_2['_' . $key . '_postcode'] . ' ' . $order_details_2['_' . $key . '_city'] . ' ' . $order_details_2['_' . $key . '_state'] . ' ' . $order_details_2['_' . $key . '_country']));
        }
        $order['currency_symbol'] = sb_get_setting('wc-currency-symbol');
    }
    return $order;
}

function sb_woocommerce_get_last_order($user_id) {
    $orders = sb_woocommerce_get_user_orders($user_id);
    if (!empty($orders) && count($orders)) {
        return sb_woocommerce_get_order($orders[0]['id']);
    }
    return false;
}

/*
 * ----------------------------------------------------------
 * PRODUCTS
 * ----------------------------------------------------------
 *
 * 1. Return a product
 * 2. Return the products of a category or tag
 * 3. Search products
 * 4. Get and assign additional details to the product
 * 5. Return the product tags and categories
 * 6. Return the product attributes
 * 7. Return the product ID by the name
 * 8. Return the product images
 * 9. Return the taxonomies(tags, categories, attribute terms) of a product
 * 10. Return the parent attribute name of a term
 * 11. Check if a product is in of stock
 * 12. Out of stock notification
 * 13. Fix WooCommerce product images
 *
 */

function sb_woocommerce_get_product($product_id) {
    if (!is_numeric($product_id)) {
        $product_id = sb_woocommerce_get_product_id_by_name($product_id);
    }
    $product = sb_db_get('SELECT p.id, p.post_title name, p.post_excerpt description, m.meta_value price FROM ' . SB_WP_PREFIX . 'posts p, ' . SB_WP_PREFIX . 'postmeta m WHERE p.id = ' . $product_id . ' AND m.meta_key = "_price" AND m.post_id = p.id AND p.post_type = "product"');
    if (!sb_is_error($product) && !empty($product)) {
        $image = sb_db_get('SELECT guid FROM ' . SB_WP_PREFIX . 'posts, ' . SB_WP_PREFIX . 'postmeta WHERE post_id = ' . $product_id . ' AND id = meta_value AND meta_key = "_thumbnail_id"');
        $rating = sb_db_get('SELECT meta_value FROM ' . SB_WP_PREFIX . 'postmeta WHERE post_id = ' . $product_id . ' AND meta_key = "_wc_rating_count" AND meta_value <> "" AND meta_value <> "a:0:{}"');
        $product['image'] = isset($image['guid']) ? sb_woocommerce_fix_image($image['guid']) : SB_URL . '/media/thumb.png';
        $product['rating'] = isset($rating['meta_value']) ? unserialize($rating['meta_value']) : '';
        $product['url'] = sb_wp_site_url() . '?p=' . $product['id'];
        $product['taxonomies'] = sb_woocommerce_get_product_taxonomies($product_id);
    }
    return $product;
}

function sb_woocommerce_get_products($filters = [], $pagination = false, $language = '') {
    $taxonomy = '';
    $date = '';
    $discounted = '';
    $price = '';
    $attribute = '';

    // Categories and tags
    if (sb_isset($filters, 'taxonomy')) {
        $taxonomy = $filters['taxonomy'];
        if (!is_numeric($taxonomy) && $taxonomy != 'Uncategorized') {
            $taxonomy = sb_db_get('SELECT term_id FROM ' . SB_WP_PREFIX . 'terms WHERE name = "' . $taxonomy . '" || slug = "' . $taxonomy . '" LIMIT 1');
            if (isset($taxonomy['term_id'])) {
                $taxonomy = $taxonomy['term_id'];
            } else {
                return [];
            }
        }
        $taxonomy = ' AND p.id IN (SELECT object_id FROM ' . SB_WP_PREFIX . 'term_relationships WHERE term_taxonomy_id = "' . $taxonomy . '")';
    }

    // Attribute
    if (sb_isset($filters, 'attribute')) {
        $attribute = ' AND p.ID IN (SELECT object_id FROM ' . SB_WP_PREFIX . 'terms, ' . SB_WP_PREFIX . 'term_relationships WHERE term_taxonomy_id = term_id AND name = "' . sb_db_escape($filters['attribute']) . '")';
    }

    // Date
    if (sb_isset($filters, 'date')) {
        if (is_array($filters['date'])) {
            $date = ' AND post_date > "' . gmdate('Y-m-d H:i:s', strtotime($filters['date']['startDate'])) . '" AND post_date < "' . gmdate('Y-m-d H:i:s', strtotime($filters['date']['endDate'])) . '"';
        } else {
            $date = ' AND post_date > "' . gmdate('Y-m-d', strtotime($filters['date'])) . '" AND post_date < "' . gmdate('Y-m-d', strtotime($filters['date']) + 86400) . '"';
        }
    }

    // Price
    if (sb_isset($filters, 'max-price')) {
        $price = ' AND m.meta_value <= ' . $filters['max-price'];
    }
    if (sb_isset($filters, 'min-price')) {
        $price = ' AND m.meta_value >= ' . $filters['min-price'];
    }

    // Discounted
    if (sb_isset($filters, 'discounted')) {
        $discounted = ' AND (p.id IN (SELECT post_id FROM ' . SB_WP_PREFIX . 'postmeta WHERE meta_key = "_sale_price") OR p.id IN (SELECT post_parent FROM ' . SB_WP_PREFIX . 'posts, ' . SB_WP_PREFIX . 'postmeta WHERE post_type = "product_variation" AND meta_key = "_sale_price" AND id = post_id))';
    }

    // Get the products
    $query = ['SELECT p.id, p.post_title name, p.post_excerpt description, p.post_date_gmt `date`, m.meta_value price FROM ' . SB_WP_PREFIX . 'posts p, ' . SB_WP_PREFIX . 'postmeta m', ' WHERE p.post_type = "product" AND m.meta_key = "_price" AND m.post_id = p.id AND p.post_status = "publish"' . $taxonomy . $date . $discounted . $price . $attribute, (sb_isset_num($pagination) || $pagination === 0 ? ' LIMIT ' . (intval($pagination) * 100) . ',100' : '')];
    $language = sb_wp_language_get_data('product', 'post-types', $language);
    $language_link = false;
    $language_link_type = false;
    if ($language) {
        $active_language = $language['language'];
        switch ($language['settings']['plugin']) {
            case 'wpml':
                $products = sb_db_get($query[0] . $query[1] . ' AND p.id IN (SELECT element_id FROM ' . SB_WP_PREFIX . 'icl_translations WHERE language_code = "' . $active_language . '") GROUP BY p.id' . $query[2], false);
                break;
            case 'polylang':
                $products = sb_db_get($query[0] . ', ' . SB_WP_PREFIX . 'terms t, ' . SB_WP_PREFIX . 'term_relationships r' . $query[1] . ' AND t.term_id = r.term_taxonomy_id AND r.object_id = p.id AND t.slug = "' . $active_language . '" GROUP BY p.id' . $query[2], false);
                break;
        }
        if ($active_language != $language['settings']['default']) {
            $language_link = $active_language;
            $language_link_type = $language['settings']['link-type'];
        }
    } else {
        $products = sb_db_get($query[0] . $query[1] . ' GROUP BY p.id' . $query[2], false);
    }
    return sb_woocommerce_finalize_products($products, $language_link, $language_link_type);
}

function sb_woocommerce_search_products($search) {
    $search = sb_db_escape(mb_strtolower($search));
    $products = sb_db_get('SELECT p.id, p.post_title name, p.post_excerpt description, m.meta_value price FROM ' . SB_WP_PREFIX . 'posts p, ' . SB_WP_PREFIX . 'postmeta m WHERE (p.post_title LIKE "%' . $search . '%" || m.meta_value LIKE "%' . $search . '%" || p.post_excerpt LIKE "%' . $search . '%") AND m.meta_key = "_price" AND m.post_id = p.id AND p.post_status = "publish" GROUP BY p.id', false);
    return sb_woocommerce_finalize_products($products);
}

function sb_woocommerce_finalize_products($products, $language = false, $language_type = false) {
    $count = count($products);
    if ($count) {
        $ids = '';
        $site_url = sb_wp_site_url();
        $currency_symbol = sb_get_setting('wc-currency-symbol');
        for ($i = 0; $i < $count; $i++) {
            $ids .= $products[$i]['id'] . ',';
            $products[$i]['title'] = $products[$i]['name'];
            $products[$i]['image'] = '';
            $products[$i]['rating'] = '';
            $products[$i]['price'] .= ' ' . $currency_symbol;
            $products[$i]['url'] = $site_url . '?p=' . $products[$i]['id'];
            if ($language) {
                $products[$i]['url'] = $language_type == 1 ? str_replace('?', $language . '/?', $products[$i]['url']) : $products[$i]['url'] . '&lang=' . $language;
            }
        }
        $ids = substr($ids, 0, -1);
        $images = sb_db_get('SELECT post_id, guid FROM ' . SB_WP_PREFIX . 'posts, ' . SB_WP_PREFIX . 'postmeta WHERE post_id IN (' . $ids . ') AND id = meta_value AND meta_key = "_thumbnail_id"', false);
        $ratings = sb_db_get('SELECT post_id, meta_value FROM ' . SB_WP_PREFIX . 'postmeta WHERE post_id IN (' . $ids . ') AND meta_key = "_wc_rating_count" AND meta_value <> "" AND meta_value <> "a:0:{}"', false);
        for ($i = 0; $i < count($images); $i++) {
            for ($j = 0; $j < $count; $j++) {
                if ($products[$j]['id'] == $images[$i]['post_id']) {
                    $products[$j]['image'] = sb_woocommerce_fix_image($images[$i]['guid']);
                    break;
                }
            }
        }
        for ($i = 0; $i < count($ratings); $i++) {
            for ($j = 0; $j < $count; $j++) {
                if ($products[$j]['id'] == $ratings[$i]['post_id']) {
                    $products[$j]['rating'] = unserialize($ratings[$i]['meta_value']);
                    break;
                }
            }
        }
    }
    return $products;
}

function sb_woocommerce_get_taxonomies($type, $language = '') {
    $is_category = $type == 'category';
    $query = 'SELECT a.term_id id, a.name, a.slug FROM ' . SB_WP_PREFIX . 'terms a, ' . SB_WP_PREFIX . 'term_taxonomy b WHERE a.term_id = b.term_id AND b.taxonomy = ' . ($is_category ? '"product_cat" AND a.slug <> "uncategorized"' : '"product_tag"');
    $language = sb_wp_language_get_data($is_category ? 'product_cat' : 'product_tag', 'taxonomies', $language);
    if ($language) {
        $active_language = $language['language'];
        switch ($language['settings']['plugin']) {
            case 'wpml':
                if ($is_category) {
                    return sb_db_get($query . ' AND a.term_id IN (SELECT element_id FROM ' . SB_WP_PREFIX . 'icl_translations WHERE element_type = "tax_product_cat" AND language_code = "' . $active_language . '")', false);
                } else {
                    return sb_db_get($query . ' AND a.term_id IN (SELECT element_id FROM ' . SB_WP_PREFIX . 'icl_translations WHERE element_type = "tax_product_tag" AND language_code = "' . $active_language . '")', false);
                }
            case 'polylang':
                $translations = sb_db_get('SELECT description FROM ' . SB_WP_PREFIX . 'term_taxonomy WHERE taxonomy = "term_translations"', false);
                $ids = '';
                for ($i = 0; $i < count($translations); $i++) {
                    $translation = unserialize($translations[$i]['description']);
                    if (isset($translation[$active_language])) {
                        $ids .= $translation[$active_language] . ',';
                    }
                }
                return $ids ? sb_db_get($query . ' AND a.term_id IN (' . substr($ids, 0, -1) . ')', false) : [];
        }
    } else {
        return sb_db_get($query, false);
    }
    return false;
}

function sb_woocommerce_get_attributes($type = false, $language = '') {
    $response = [];
    $attributes = sb_db_get('SELECT attribute_id id, attribute_label name, attribute_name slug FROM ' . SB_WP_PREFIX . 'woocommerce_attribute_taxonomies', false);
    $language = sb_wp_language_get_data('pa_' . $attributes[0]['slug'], 'taxonomies', $language);
    $count = count($attributes);
    if ($count) {
        $attribute_terms = [];
        $attribute_slugs = '';
        $attribute_names = '';

        // Attributes
        for ($i = 0; $i < $count; $i++) {
            $slug = $attributes[$i]['slug'];
            $attributes[$i]['slug'] = 'pa_' . $attributes[$i]['slug'];
            $attribute_slugs .= '"' . $attributes[$i]['slug'] . '",';
            $response[$slug] = $attributes[$i];
            $response[$slug]['terms'] = [];
        }
        $attribute_slugs = substr($attribute_slugs, 0, -1);

        // Attribute terms
        $query = 'SELECT a.*, b.* FROM ' . SB_WP_PREFIX . 'terms a, ' . SB_WP_PREFIX . 'term_taxonomy b WHERE b.taxonomy IN (' . $attribute_slugs . ') AND b.term_taxonomy_id = a.term_id';
        if ($language) {
            $active_language = $language['language'];
            switch ($language['settings']['plugin']) {
                case 'wpml':

                    // Attributes translations
                    $attribute_names = '';
                    for ($i = 0; $i < $count; $i++) {
                        $attribute_names .= '"' . $attributes[$i]['name'] . '","Product ' . $attributes[$i]['name'] . '",';
                    }
                    $language_strings = sb_db_get('SELECT A.value, B.value original FROM ' . SB_WP_PREFIX . 'icl_string_translations A, ' . SB_WP_PREFIX . 'icl_strings B WHERE B.value IN (' . substr($attribute_names, 0, -1) . ') AND A.string_id = B.id AND A.`language` = "' . $active_language . '"', false);
                    foreach ($response as $key => $value) {
                        for ($i = 0; $i < count($language_strings); $i++) {
                            if ($value['name'] == $language_strings[$i]['original']) {
                                $response[$key]['name'] = $language_strings[$i]['value'];
                                break;
                            } else if (('Product ' . $value['name']) == $language_strings[$i]['original']) {
                                $response[$key]['name_plural'] = $language_strings[$i]['value'];
                            }
                        }
                    }

                    // Attribute terms translations
                    $attribute_terms = sb_db_get($query . ' AND a.term_id IN (SELECT element_id FROM ' . SB_WP_PREFIX . 'icl_translations WHERE element_type IN (' . str_replace('pa_', 'tax_pa_', $attribute_slugs) . ') AND language_code = "' . $active_language . '")', false);
                    break;
                case 'polylang':

                    // Attributes translations
                    $mo_id = sb_isset($language['settings']['extra']['mo'], $active_language);
                    if ($mo_id) {
                        $language_strings = sb_db_get('SELECT meta_value FROM ' . SB_WP_PREFIX . 'postmeta WHERE post_id = "' . $mo_id . '" AND meta_key = "_pll_strings_translations"');
                        if (!empty($language_strings)) {
                            $language_strings = unserialize($language_strings['meta_value']);
                            for ($i = 0; $i < count($language_strings); $i++) {
                                foreach ($response as $key => $value) {
                                    if ($value['name'] == $language_strings[$i][0]) {
                                        $response[$key]['name'] = $language_strings[$i][1];
                                        break;
                                    }
                                }
                            }
                        }
                    }

                    // Attribute terms translations
                    $translations = sb_db_get('SELECT description FROM ' . SB_WP_PREFIX . 'term_taxonomy WHERE taxonomy = "term_translations"', false);
                    $ids = '';
                    for ($i = 0; $i < count($translations); $i++) {
                        $translation = unserialize($translations[$i]['description']);
                        if (isset($translation[$active_language])) {
                            $ids .= $translation[$active_language] . ',';
                        }
                    }
                    $attribute_terms = $ids ? sb_db_get($query . ' AND a.term_id IN (' . substr($ids, 0, -1) . ')', false) : [];
                    break;
            }
        } else {
            $attribute_terms = sb_db_get($query, false);
        }
        for ($i = 0; $i < count($attribute_terms); $i++) {
            $response[substr($attribute_terms[$i]['taxonomy'], 3)]['terms'][$attribute_terms[$i]['slug']] = $attribute_terms[$i]['name'];
        }
    }
    switch ($type) {
        case 'terms':
            $names = [];
            foreach ($response as $value) {
                foreach ($value['terms'] as $term) {
                    array_push($names, ['name' => $term]);
                }
            }
            return $names;
        case 'attributes':
            $names = [];
            foreach ($response as $value) {
                array_push($names, ['id' => $value['id'], 'name' => $value['name'], 'slug' => $value['slug']]);
            }
            return $names;
        default:
            return $response;
    }
}

function sb_woocommerce_get_product_id_by_name($name) {
    $product_id = sb_db_get('SELECT id FROM ' . SB_WP_PREFIX . 'posts WHERE post_title = "' . sb_db_escape($name) . '" LIMIT 1');
    if (sb_isset($product_id, 'id')) {
        return $product_id['id'];
    }
    return false;
}

function sb_woocommerce_get_product_images($product_id) {
    if (!is_numeric($product_id)) {
        $product_id = sb_woocommerce_get_product_id_by_name($product_id);
    }
    $images = sb_db_get('SELECT guid FROM ' . SB_WP_PREFIX . 'posts, ' . SB_WP_PREFIX . 'postmeta WHERE (post_id = ' . $product_id . ' AND meta_key = "_product_image_gallery" AND FIND_IN_SET(id, meta_value) > 0) OR (id = meta_value AND post_id = ' . $product_id . ' AND meta_key = "_thumbnail_id")', false);
    for ($i = 0; $i < count($images); $i++) {
        $images[$i] = sb_woocommerce_fix_image($images[$i]['guid']);
    }
    return $images;
}

function sb_woocommerce_get_product_taxonomies($product_id) {
    if (!is_numeric($product_id)) {
        $product_id = sb_woocommerce_get_product_id_by_name($product_id);
    }
    $response = [];
    $attributes = sb_woocommerce_get_attributes('attributes');
    $attributes_terms = sb_db_get('SELECT A.*, C.taxonomy FROM ' . SB_WP_PREFIX . 'terms A, ' . SB_WP_PREFIX . 'term_relationships B, ' . SB_WP_PREFIX . 'term_taxonomy C WHERE B.object_id = ' . $product_id . ' AND B.term_taxonomy_id = A.term_id AND C.term_id = A.term_id', false);
    for ($i = 0; $i < count($attributes); $i++) {
        $attributes[$i] = $attributes[$i]['slug'];
    }
    array_push($attributes, 'product_tag', 'product_cat');
    for ($i = 0; $i < count($attributes_terms); $i++) {
        if (in_array($attributes_terms[$i]['taxonomy'], $attributes)) {
            array_push($response, $attributes_terms[$i]);
        }
    }
    return $response;
}

function sb_woocommerce_get_attribute_by_term($term_name) {
    $attributes = sb_woocommerce_get_attributes();
    $term_name = strtolower($term_name);
    foreach ($attributes as $value) {
        foreach ($value['terms'] as $term) {
            if (strtolower($term) == $term_name) {
                return ['id' => $value['id'], 'name' => $value['name'], 'slug' => $value['slug']];
            }
        }
    }
    return false;
}

function sb_woocommerce_get_attribute_by_name($name) {
    $attributes = sb_woocommerce_get_attributes();
    $name = strtolower($name);
    foreach ($attributes as $value) {
        if (strtolower($value['name']) == $name) {
            return ['id' => $value['id'], 'name' => $value['name'], 'slug' => $value['slug']];
        }
    }
    return false;
}

function sb_woocommerce_is_in_stock($product_id) {
    return empty(sb_db_get('SELECT * FROM ' . SB_WP_PREFIX . 'postmeta WHERE post_id = ' . $product_id . ' AND meta_value = "outofstock" LIMIT 1'));
}

function sb_woocommerce_fix_image($url) {
    if (substr_count($url, '.jpg') > 1) {
        $url = substr($url, 0, strpos($url, '.jpg') + 4);
    }
    if (substr_count($url, '.png') > 1) {
        $url = substr($url, 0, strpos($url, '.png') + 4);
    }
    return $url;
}

/*
 * -----------------------------------------------------------
 * COUPON
 * -----------------------------------------------------------
 *
 * 1. Create a coupon and return the code
 * 2. Delete expired coupons
 * 3. Check if the user has active coupons
 *
 */

function sb_woocommerce_coupon($discount, $expiration, $product_id = '', $user_id = '') {
    $coupon_code = substr(md5(uniqid(rand(), true)), 0, 15);
    $post_id = sb_wp_post($coupon_code, '', 'shop_coupon');
    if (!sb_is_error($post_id) && is_numeric($post_id)) {
        $response = sb_db_query('INSERT INTO ' . SB_WP_PREFIX . 'postmeta (post_id, meta_key, meta_value) VALUES (' . $post_id . ', "discount_type", "percent"), (' . $post_id . ', "coupon_amount", "' . $discount . '"), (' . $post_id . ', "individual_use", "yes"), (' . $post_id . ', "usage_limit", "1"), (' . $post_id . ', "usage_limit_per_user", "0"), (' . $post_id . ', "limit_usage_to_x_items", "0"), (' . $post_id . ', "usage_count", "0"), (' . $post_id . ', "date_expires", "' . strtotime('+' . $expiration) . '"), (' . $post_id . ', "free_shipping", "no"), (' . $post_id . ', "exclude_sale_items", "no"), (' . $post_id . ', "product_ids", "' . $product_id . '")' . ($user_id ? ', (' . $post_id . ', "user_id", "' . $user_id . '")' : ''));
        if (sb_is_error($response))
            return $response;
        return [$coupon_code, $discount];
    }
    return $post_id;
}

function sb_woocommerce_coupon_check($user_id) {
    return intval(sb_isset(sb_db_get('SELECT COUNT(*) count FROM ' . SB_WP_PREFIX . 'postmeta WHERE meta_key = "coupon_amount" AND post_id = (SELECT post_id FROM ' . SB_WP_PREFIX . 'postmeta WHERE meta_value = "' . $user_id . '" AND meta_key = "user_id")'), 'count')) > 0;
}

function sb_woocommerce_coupon_delete_expired() {
    $expired_coupons_ids = sb_db_get('SELECT p.id FROM ' . SB_WP_PREFIX . 'posts p, ' . SB_WP_PREFIX . 'postmeta m WHERE p.post_type = "shop_coupon" AND p.ID = m.post_id AND m.meta_key = "date_expires" AND m.meta_value < ' . strtotime('now'), false);
    if (!sb_is_error($expired_coupons_ids) && is_array($expired_coupons_ids)) {
        $expired_coupons_ids_string = '';
        for ($i = 0; $i < count($expired_coupons_ids); $i++) {
            $expired_coupons_ids_string .= $expired_coupons_ids[$i]['id'] . ',';
        }
        $expired_coupons_ids_string = substr($expired_coupons_ids_string, 0, -1);
        if ($expired_coupons_ids_string) {
            sb_db_query('DELETE FROM ' . SB_WP_PREFIX . 'posts WHERE id IN (' . $expired_coupons_ids_string . ')');
            sb_db_query('DELETE FROM ' . SB_WP_PREFIX . 'postmeta WHERE post_id IN (' . $expired_coupons_ids_string . ')');
        }
    }
    return true;
}

function sb_woocommerce_waiting_list($product_id, $conversation_id = false, $user_id = false, $action = 'request', $token = -1) {
    $settings = sb_get_setting('wc-waiting-list');
    $user = $user_id === false ? sb_get_active_user() : sb_get_user($user_id);
    if (!$user) {
        return sb_error('user-not-found', 'sb_woocommerce_waiting_list');
    }
    if (sb_isset($settings, 'wc-waiting-list-active')) {
        $product = sb_woocommerce_get_product($product_id);
        if (empty($conversation_id)) {
            $conversation_id = sb_get_last_conversation_id_or_create(sb_isset($user, 'id'), 3);
        }
        switch ($action) {
            case 'request':
                if (!empty($product)) {
                    $message = sb_(sb_woocommerce_merge_fields($settings['wc-waiting-list-message'], ['products' => [$product]]));
                    $rich_message = sb_isset($settings, 'wc-waiting-list-button-text') ? '[chips id="sb-waiting-list" message="' . sb_rich_value($message, false, false) . '" options="' . sb_($settings['wc-waiting-list-button-text']) . ',' . sb_($settings['wc-waiting-list-button-text-cancel']) . '"]' : '';
                    return sb_send_message(sb_get_bot_id(), $conversation_id, $rich_message ? $rich_message : $message, [], 1, '{ "event": "open-chat" }');
                }
                return sb_error('product-not-found', 'sb_woocommerce_waiting_list');
            case 'submit':
                $message = '';
                if ($user['email']) {
                    $waiting_list = sb_get_external_setting('wc-waitig-list');
                    $waiting_list_product = sb_isset($waiting_list, $product_id, []);
                    if (!in_array($user['email'], $waiting_list_product)) {
                        array_push($waiting_list_product, $user['email']);
                        $waiting_list[$product_id] = $waiting_list_product;
                        sb_save_external_setting('wc-waitig-list', $waiting_list);
                    }
                    $message = sb_woocommerce_merge_fields(sb_($settings['wc-waiting-list-message-success']), ['products' => [$product]]);
                } else {
                    $follow = sb_get_setting('follow-message');
                    $message = '[email id="sb-waiting-list-email" message="' . sb_woocommerce_merge_fields(sb_rich_value($settings['wc-waiting-list-message-email']), ['products' => [$product]]) . '" placeholder="' . sb_rich_value($follow['follow-placeholder'], false) . '" name="' . $follow['follow-name'] . '" last-name="' . sb_isset($follow, 'follow-last-name') . '"]';
                }
                return sb_send_message(sb_get_bot_id(), $conversation_id, $message);
            case 'send':
                $waiting_list = sb_get_external_setting('wc-waitig-list');
                foreach ($waiting_list as $key => $value) {
                    if ($key == $product_id) {
                        if (sb_woocommerce_is_in_stock($product_id)) {
                            $emails = sb_isset(sb_get_external_setting('wc-emails'), 'wc-waiting-list-email', []);
                            $subject = sb_woocommerce_merge_fields($emails[0]['wc-waiting-list-email-subject'][0], ['products' => [$product]]);
                            $content = sb_woocommerce_merge_fields($emails[0]['wc-waiting-list-email-content'][0], ['products' => [$product]]);
                            for ($i = 0; $i < count($value); $i++) {
                                sb_email_send($value[$i], $subject, $content);
                            }
                            unset($waiting_list[$key]);
                            return sb_save_external_setting('wc-waitig-list', $waiting_list);
                        }
                        break;
                    }
                }
                break;
        }
    }
    return false;
}

/*
 * -----------------------------------------------------------
 * WOOCOMMERCE CRON JOBS
 * -----------------------------------------------------------
 *
 * Execute the WooCommerce cron jobs
 *
 */

function sb_woocommerce_cron_jobs($cron_functions = []) {
    $cron_jobs = ['product-removed', 'follow-up'];
    $emails = sb_get_external_setting('wc-emails');
    $now = strtotime('now');
    for ($i = 0; $i < count($cron_jobs); $i++) {
        $job = $cron_jobs[$i];
        $scheduled = sb_get_external_setting('cron-wc-' . $job);
        $scheduled_updated = [];
        if (!empty($scheduled)) {
            $email = sb_isset($emails, 'wc-' . $job . '-email', [false])[0];
            $email_subject = sb_isset($email, 'wc-' . $job . '-email-subject', [''])[0];
            $email_content = sb_isset($email, 'wc-' . $job . '-email-content', [''])[0];
            if (!empty($email_subject) && !empty($email_content) && !empty($email)) {
                foreach ($scheduled as $key => $value) {
                    $scheduled_updated[$key] = $value;
                    if ($now > $value[1]) {
                        $user = sb_get_user($key);
                        if ($user['email'] != '') {
                            $products = [];
                            $parameters = ['coupon-discount' => $email['wc-' . $job . '-email-coupon-discount'][0], 'coupon-expiration' => $email['wc-' . $job . '-email-coupon-expiration'][0] . ' days', 'user-name' => sb_get_user_name($user), 'user-id' => $user['id']];
                            for ($i = 0; $i < count($value[0]); $i++) {
                                array_push($products, sb_woocommerce_get_product($value[0][$i]));
                            }
                            $parameters['products'] = $products;
                            sb_email_send($user['email'], sb_woocommerce_merge_fields($email_subject, $parameters), sb_woocommerce_merge_fields($email_content, $parameters));
                        }
                        unset($scheduled_updated[$key]);
                    }
                }
                sb_save_external_setting('cron-wc-' . $job, $scheduled_updated);
            }
        }
    }
    sb_woocommerce_abandoned_carts();
    sb_woocommerce_coupon_delete_expired();
}

/*
 * ----------------------------------------------------------
 * MERGE FIELDS
 * ----------------------------------------------------------
 *
 * Replace the merge fields with the real values
 *
 */

function sb_woocommerce_merge_fields($message, $parameters = [], $language = '') {
    if (strpos($message, '{') === false) {
        return $message;
    }
    $extra = [0, '', ''];
    $products = sb_isset($parameters, 'products', []);
    $products_count = count($products);
    $replace = '';
    $marge_fields = ['purchase_button', 'original_price', 'product_names', 'html_products_list', 'product_price', 'product_rating', 'product_link', 'product_images', 'html_product_card', 'product_card', 'product_name', 'product_description', 'product_link', 'product_price', 'product_image', 'user_name', 'coupon', 'product_card', 'products_slider', 'shop_link', 'cart_link', 'cart', 'payment_methods', 'shipping_locations', 'order_status', 'order_details'];

    // Set merge fields
    if (isset($parameters['coupon-discount'])) {
        array_push($marge_fields, 'discount_price');
    }
    if (isset($parameters['woocommerce-products'])) {
        $products = [sb_woocommerce_get_product($parameters['woocommerce-products'])];
        $products_count = 1;
    }
    if (isset($parameters['woocommerce-categories'])) {
        array_push($marge_fields, 'category_link');
    }
    if (isset($parameters['woocommerce-tags'])) {
        array_push($marge_fields, 'tag_link');
    }

    // Set extra data
    for ($i = 0; $i < $products_count; $i++) {
        $extra[0] += sb_isset($products[$i], 'price');
        $extra[1] .= sb_isset($products[$i], 'name') . ', ';
        $extra[2] .= sb_isset($products[$i], 'id') . ',';
    }

    // Process merge fields
    for ($i = 0; $i < count($marge_fields); $i++) {
        if (strpos($message, '{' . $marge_fields[$i]) !== false) {
            $merge_field = '{' . $marge_fields[$i] . '}';
            $shortcode = sb_get_shortcode($message, $marge_fields[$i], true);
            $product = isset($shortcode['id']) ? sb_woocommerce_get_product($shortcode['id']) : ($products_count ? $products[0] : '');
            switch ($marge_fields[$i]) {
                case 'coupon':
                    if (isset($parameters['coupon'])) {
                        $replace = $parameters['coupon'];
                    } else {
                        $is_shortcode = !sb_isset($parameters, 'coupon-discount');
                        $discount = $is_shortcode ? $shortcode['discount'] : $parameters['coupon-discount'];
                        $expiration = $is_shortcode ? $shortcode['expiration'] : $parameters['coupon-expiration'];
                        $product_ids = $is_shortcode ? sb_isset($shortcode, 'product-ids', '') : sb_isset($parameters, 'coupon-product-ids', '');
                        $merge_field = $is_shortcode ? $shortcode['shortcode'] : $merge_field;
                        $coupon = sb_woocommerce_coupon($discount, $expiration, $product_ids, sb_isset($parameters, 'user-id', ''));
                        $replace = $coupon[0];
                        $parameters['coupon-discount'] = $coupon[1];
                    }
                    break;
                case 'discount_price':
                    $replace = round($extra[0] * ((100 - intval($parameters['coupon-discount'])) / 100), 2);
                    break;
                case 'original_price':
                    $replace = round($extra[0], 2);
                    break;
                case 'product_names':
                    $replace = mb_substr($extra[1], 0, -2);
                    break;
                case 'product_name':
                    $replace = $product['name'];
                    break;
                case 'product_image':
                    $replace = $product['image'];
                    break;
                case 'product_description':
                    $replace = $product['description'];
                    break;
                case 'product_card':
                    $link_type = sb_isset($shortcode, 'link-type', 'link');
                    $merge_field = $shortcode['shortcode'];
                    if (!empty($product)) {
                        $catalog_id = sb_get_multi_setting('whatsapp-catalog', 'whatsapp-catalog-id');
                        $replace = '[card ' . ($catalog_id ? 'whatsapp-catalog-id="' . $catalog_id . '"' : '') . ' product-id="' . $product['id'] . '" image="' . $product['image'] . '" header="' . sb_rich_value($product['name']) . '"  description="' . str_replace([PHP_EOL, "\r", "\n"], ' ', sb_rich_value(strlen($product['description']) > 130 ? mb_substr($product['description'], 0, 130) : $product['description'])) . '..." link-text="' . sb_rich_value(sb_isset($shortcode, 'link-text', 'More details'), false) . '" extra="' . sb_get_setting('wc-currency-symbol') . $product['price'] . '" link="' . ($link_type == 'link' ? $product['url'] : '#') . '" settings="link-type:' . $link_type . ',id:' . $product['id'] . '"]';
                    }
                    break;
                case 'product_price':
                    if ($product) {
                        $currency_symbol = sb_get_setting('wc-currency-symbol');
                        $prices = sb_db_get('SELECT * FROM ' . SB_WP_PREFIX . 'postmeta WHERE (meta_key = "_price" OR meta_key = "_regular_price") AND post_id IN (SELECT id FROM ' . SB_WP_PREFIX . 'posts WHERE post_parent = ' . $product['id'] . ') ORDER BY post_id', false);
                        $min_price = floatval($product['price']);
                        $max_price = 0;
                        $count = count($prices);
                        for ($j = 0; $j < $count; $j++) {
                            $price = floatval($prices[$j]['meta_value']);
                            if ($min_price > $price) {
                                $min_price = $price;
                            } else {
                                $price = $j < ($count - 1) && $prices[$j + 1]['post_id'] == $prices[$j]['post_id'] && $prices[$j + 1]['meta_key'] == '_price' ? floatval($prices[$j + 1]['meta_value']) : $price;
                                if ($max_price < $price) {
                                    $max_price = $price;
                                }
                            }
                        }
                        $replace = $currency_symbol . ($min_price != $max_price && $max_price != 0 ? $min_price . ' - ' . $currency_symbol . $max_price : $min_price);
                    }
                    break;
                case 'product_rating':
                    if (!$product['rating']) {
                        $message = str_replace('{R}', $product['name'], sb_('{R} has no ratings yet.'));
                    } else {
                        $replace = sb_woocommerce_rating($product['rating']);
                    }
                    break;
                case 'product_link':
                    $replace = $product['url'];
                    break;
                case 'purchase_button':
                    $replace = '[woocommerce_button name="' . sb_(sb_isset($parameters, 'button-text', isset($parameters['checkout']) ? 'Purchase now' : 'Add to cart')) . '" ids="' . ($products_count ? substr($extra[2], 0, -1) : $parameters['id']) . '" coupon="' . sb_isset($parameters, 'coupon', '') . '" checkout="' . sb_isset($parameters, 'checkout') . '"]';
                    break;
                case 'shop_link':
                    $replace = sb_woocommerce_get_url('shop', '', $language);
                    break;
                case 'cart_link':
                    $replace = sb_woocommerce_get_url('cart', '', $language);
                    break;
                case 'cart':
                    $session = sb_woocommerce_get_session();
                    $products = [];
                    $extra_values = [];
                    if ($session) {
                        foreach ($session['cart'] as $value) {
                            array_push($products, sb_woocommerce_get_product($value['product_id']));
                            array_push($extra_values, [$value['quantity'], $value['line_total']]);
                        }
                    }
                    if (count($products)) {
                        $currency_symbol = sb_get_setting('wc-currency-symbol');
                        $replace = '[list-image values="';
                        for ($y = 0; $y < count($products); $y++) {
                            $replace .= $products[$y]['image'] . ':' . $extra_values[$y][0] . ' X ' . str_replace(',', '', sb_rich_value($products[$y]['name'], false, false)) . ':' . $currency_symbol . $extra_values[$y][1] . ',';
                        }
                        $replace = substr($replace, 0, -1) . '"]';
                    } else {
                        $message = sb_('The cart is empty.');
                    }
                    break;
                case 'order_status':
                case 'order_details':
                    $order = sb_woocommerce_get_last_order(sb_isset(sb_get_active_user(), 'id'));
                    if (!empty($order)) {
                        $order_status = sb_(str_replace('wc-', '', sb_isset($order, 'status', 'processing')));
                        if ($marge_fields[$i] == 'order_status') {
                            $replace = $order_status;
                        } else {
                            $date = strtotime($order['date']);
                            $replace = '[list values="' . sb_('Order ID') . ':' . $order['id'] . ',' . sb_('Date') . ':' . date('l', $date) . ' ' . date('d', $date) . ' ' . date('F', $date) . ',' . sb_('Total') . ':' . sb_get_setting('wc-currency-symbol') . $order['total'] . ',' . sb_('Status') . ':' . ucfirst($order_status) . (sb_isset($order, 'shipping_address') ? ',' . sb_('Shipping address') . ':' . sb_rich_value($order['shipping_address'], false, false) : '') . (sb_isset($order, 'billing_address') ? ',' . sb_('Billing address') . ':' . sb_rich_value($order['billing_address'], false, false) : '') . '"]';
                        }
                    } else {
                        $message = sb_('You haven\'t placed an order yet.');
                    }
                    break;
                case 'products_slider':
                    $shortcode = sb_get_shortcode($message, 'products_slider', true);
                    $merge_field = $shortcode['shortcode'];
                    $products = [];
                    $filters = [];
                    if (sb_isset($shortcode, 'ids')) {
                        $product_ids = explode(',', $shortcode['ids']);
                        $count = count($product_ids);
                        if ($count > 15)
                            $count = 15;
                        for ($y = 0; $y < $count; $y++) {
                            $product = sb_woocommerce_get_product($product_ids[$y]);
                            if (!empty($product)) {
                                array_push($products, sb_woocommerce_get_product($product_ids[$y]));
                            }
                        }
                    } else {
                        if (sb_isset($parameters, 'woocommerce-tags') || sb_isset($shortcode, 'tag')) {
                            $filters = ['taxonomy' => sb_isset($parameters, 'woocommerce-tags', sb_isset($shortcode, 'tag'))];
                        } else if (sb_isset($parameters, 'woocommerce-categories') || sb_isset($shortcode, 'category')) {
                            $filters = ['taxonomy' => sb_isset($parameters, 'woocommerce-categories', sb_isset($shortcode, 'category'))];
                        }
                        if (!empty($filters['taxonomy']) && is_array($filters['taxonomy'])) {
                            $filters['taxonomy'] = $filters['taxonomy'][0];
                        }
                        if (sb_isset($parameters, 'sys-date-time')) {
                            $filters['date'] = $parameters['sys-date-time'];
                        }
                        if (sb_isset($shortcode, 'discounted')) {
                            $filters['discounted'] = true;
                        }
                        if (sb_isset($shortcode, 'min-price')) {
                            $filters['min-price'] = sb_isset($parameters, 'min-price', sb_isset($shortcode, 'min-price'));
                        }
                        if (sb_isset($parameters, 'sys-unit-currency')) {
                            $filters['max-price'] = $parameters['sys-unit-currency']['amount'];
                        }
                        if (sb_isset($parameters, 'woocommerce-attribute-terms') || sb_isset($shortcode, 'attribute')) {
                            $filters['attribute'] = sb_isset($parameters, 'woocommerce-attribute-terms', sb_isset($shortcode, 'attribute'));
                        }
                        $products = sb_woocommerce_get_products($filters, 0, $language);
                        if (sb_isset($shortcode, 'rating')) {
                            $products_update = [];
                            $rating = intval($shortcode['rating']) - 0.5;
                            for ($y = 0; $y < count($products); $y++) {
                                if (sb_woocommerce_rating($products[$y]['rating']) > $rating) {
                                    array_push($products_update, $products[$y]);
                                }
                            }
                            $products = $products_update;
                        }
                    }
                    $count = count($products);
                    if ($count) {
                        $count = $count > 15 ? 15 : $count;
                        $link_type = sb_isset($shortcode, 'link-type', 'link');
                        $link_text = sb_rich_value(sb_isset($shortcode, 'link-text', 'More details'), false, true);
                        $currency_symbol = sb_get_setting('wc-currency-symbol');
                        $catalog_id = sb_get_multi_setting('whatsapp-catalog', 'whatsapp-catalog-id');
                        $replace = '[slider';
                        $ids = '';
                        for ($y = 0; $y < count($products); $y++) {
                            $index = $y + 1;
                            $description = $products[$y]['description'];
                            $description = str_replace([PHP_EOL, "\r", "\n"], ' ', sb_rich_value(strlen($description) > 130 ? substr($description, 0, 130) . ' ...' : $description));
                            $replace .= ' image-' . $index . '="' . $products[$y]['image'] . '" header-' . $index . '="' . sb_rich_value($products[$y]['name']) . '"  description-' . $index . '="' . $description . '" link-' . $index . '="' . ($link_type == 'link' ? $products[$y]['url'] : '#') . '" link-text-' . $index . '="' . $link_text . '" extra-' . $index . '="' . $currency_symbol . $products[$y]['price'] . '"';
                            $ids .= $products[$y]['id'] . '|';
                        }
                        $ids = substr($ids, 0, -1);
                        $replace .= (isset($filters) ? ' filters="' . sb_rich_value(implode(' ', $filters)) . '"' : '') . ($catalog_id ? ' whatsapp-catalog-id="' . $catalog_id . '"' : '') . ' product-id="' . $ids . '" settings="link-type:' . $link_type . ',id:' . $ids . '"]';
                    } else {
                        $message = sb_('No results found.');
                    }
                    break;
                case 'payment_methods':
                    $replace = implode(', ', sb_woocommerce_payment_methods());
                    break;
                case 'product_images':
                    if ($product) {
                        $images = sb_woocommerce_get_product_images($product['id']);
                        $count = count($images);
                        if ($count) {
                            $replace = '[slider-images images="';
                            for ($y = 0; $y < count($images); $y++) {
                                $replace .= $images[$y] . ',';
                            }
                            $replace = substr($replace, 0, -1) . '"]';
                        } else {
                            $message = sb_('No results found.');
                        }
                    } else {
                        $message = sb_('No results found.');
                    }
                    break;
                case 'shipping_locations':
                    $replace = sb_woocommerce_shipping_locations()[0];
                    break;
                case 'shipping_location_check':
                    $location = $parameters['sys-geo-country-code'];
                    $replace = (sb_woocommerce_shipping_locations($location['alpha-2']) ? 'Yes, we ship in' : 'No, we don\'t ship in') . ' ' . $location['name'];
                    break;
                case 'html_product_card':
                    $replace = sb_woocommerce_merge_fields_html('product_card', $parameters);
                    break;
                case 'html_products_list':
                    $replace = sb_woocommerce_merge_fields_html('list', $parameters);
                    break;
            }
            $message = str_replace(sb_isset($shortcode, 'shortcode', $merge_field), $replace, $message);
        }
    }
    return $message;
}

function sb_woocommerce_merge_fields_html($name, $parameters = []) {
    $code = '';
    $html = ['table' => '<table cellspacing="0" border="0" cellpadding="0" bgcolor="transparent" style="border:none;border-collapse:separate;border-spacing:0;margin:0;table-layout:fixed">', 'td' => '<td valign="middle" width="50" align="left" style="border:none;padding:0;vertical-align:middle">', 'td2' => 'style="border:none;font-family:Helvetica,Arial,sans-serif;padding:0;vertical-align:middle"', 'a' => ' style="outline:none;text-decoration:none;border:none"', 'a2' => 'style="display:block;color:#222;outline:none;text-decoration:none;border:none;font-weight:600;font-size:12px;letter-spacing:.3px"', 'img' => 'style="border-radius:3px;margin:5px 15px 5px 0;outline:none;text-decoration:none;border:none"', 'text-gray' => 'style="display:block;line-height:25px;color:#666;font-size:14px;"', 'button' => 'style="outline:none;text-decoration:none;border:none;border-radius:3px;background:#404040;color:#FFF;line-height:20px;height:20px;white-space:nowrap;font-size:13px;margin-top:15px;padding:5px 10px;display:inline-block;font-weight:500;"'];
    switch ($name) {
        case 'list':
            $discount = sb_isset($parameters, 'coupon-discount');
            $products = sb_isset($parameters, 'products', sb_isset($parameters, 'items', []));
            if ($products) {
                $currency_symbol = sb_get_setting('wc-currency-symbol');
                $code = $html['table'];
                for ($i = 0; $i < count($products); $i++) {
                    $code .= '<tr>' . $html['td'] . '<a href="' . $products[$i]['url'] . '" ' . $html['a'] . '><img width="50" height="50" src="' . $products[$i]['image'] . '" ' . $html['img'] . '></a></td><td valign="middle" align="left" ' . $html['td2'] . '><a href="' . $products[$i]['url'] . '" ' . $html['a2'] . '>' . $products[$i]['name'] . (isset($products[$i]['quantity']) ? (' X ' . $products[$i]['quantity']) : '') . '</a><span ' . $html['text-gray'] . '>' . ($discount > 0 ? '<span style="text-decoration:line-through;opacity:.8">' : '') . $currency_symbol . round($products[$i]['price'], 2) . ($discount > 0 ? '</span> ' . $currency_symbol . round($products[$i]['price'] * ((100 - $discount) / 100), 2) : '') . '</span></td></tr>';
                }
                $code .= '</table>';
            }
            break;
        case 'product_card':
            $products = sb_isset($parameters, 'products');
            $currency_symbol = sb_get_setting('wc-currency-symbol');
            $description = $products[0]['description'];
            if (strlen($description) > 200) {
                $description = mb_substr($description, 0, 200) . ' ...';
            }
            if ($products) {
                $code = $html['table'];
                $code .= '<tr>' . $html['td'] . '<a href="' . $products[0]['url'] . '" ' . $html['a'] . '><img width="150" height="150" src="' . $products[0]['image'] . '" ' . $html['img'] . '></a></td><td valign="middle" align="left" ' . $html['td2'] . '><a href="' . $products[0]['url'] . '" ' . $html['a2'] . '><span style="font-size:15px">' . $products[0]['name'] . '</span></a><span ' . $html['text-gray'] . '>' . $currency_symbol . $products[0]['price'] . '</span><div style="line-height:20px;color:#666;font-size:12px;margin-top:15px;max-width: 500px;">' . $products[0]['description'] . '</div><a ' . $html['button'] . ' href="' . $products[0]['url'] . '">' . sb_('More details') . '</a></td></tr>';
                $code .= '</table>';
            }
            break;
    }
    return $code;
}

/*
 * ----------------------------------------------------------
 * OPEN AI
 * ----------------------------------------------------------
 *
 * 1. Function calling
 *
 */

function sb_woocommerce_open_ai_function() {
    return [
        ['type' => 'function', 'function' => ['name' => 'sb-woocommerce-checkout-redirect', 'description' => 'The user wants to visit the checkout page. For example: "I want to checkout".', 'parameters' => ['type' => 'object', 'properties' => json_decode('{}'), 'required' => []]]],
        ['type' => 'function', 'function' => ['name' => 'sb-woocommerce-payment', 'description' => 'Retrieve information about accepted payment methods. For example: "Do you accept PayPal?".', 'parameters' => ['type' => 'object', 'properties' => json_decode('{}'), 'required' => []]]],
        ['type' => 'function', 'function' => ['name' => 'sb-woocommerce-shipment', 'description' => 'Retrieve information about the shipment locations. For example: "Do you ship in Australia?".', 'parameters' => ['type' => 'object', 'properties' => json_decode('{}'), 'required' => []]]],
        ['type' => 'function', 'function' => ['name' => 'sb-woocommerce-cart', 'description' => 'Retrieve information about the user current cart. For example: "Display the items in my cart".', 'parameters' => ['type' => 'object', 'properties' => json_decode('{}'), 'required' => []]]],
        ['type' => 'function', 'function' => ['name' => 'sb-woocommerce-order', 'description' => 'Retrieve information about a previous order. For example: "What is the shipping address of my order?", "What is my last order?".', 'parameters' => ['type' => 'object', 'properties' => json_decode('{}'), 'required' => []]]],
        ['type' => 'function', 'function' => ['name' => 'sb-woocommerce-cart-update', 'description' => 'Add or remove an item from the cart. For example: "Add Nike shoes to my cart".', 'parameters' => ['type' => 'object', 'properties' =>
            [
                'product_name' => [
                    'type' => 'string',
                    'description' => 'The name of the product the user is asking about.'
                ],
                'action' => [
                    'type' => 'string',
                    'description' => 'The action, It can be adding an item, or removing it.',
                    'enum' => ['Add', 'Remove']
                ]
            ], 'required' => ['product_name', 'action']]]
        ],
        ['type' => 'function', 'function' => ['name' => 'sb-woocommerce-single', 'description' => 'Retrieve a specific information about a specific product in our store. For example: "What is the price of the PlayStation?", "Do you have the Nike Air Force in XL size?".', 'parameters' => ['type' => 'object', 'properties' =>
            [
                'product_name' => [
                    'type' => 'string',
                    'description' => 'The name of the product the user is asking about.'
                ],
                'information' => [
                    'type' => 'string',
                    'description' => 'The product information the user is asking about.',
                    'enum' => array_merge(array_column(sb_woocommerce_get_attributes('attributes'), 'name'), ['Price', 'Photos', 'Rating'])
                ]
            ], 'required' => ['product_name', 'information']]]
        ],
        ['type' => 'function', 'function' => ['name' => 'sb-woocommerce', 'description' => 'Search for products in our store that meet the user\'s criteria. For example: "Do you sell monitors for less than 100 USD?", "I want to see some red t-shirt".', 'parameters' => ['type' => 'object', 'properties' =>
            [
                'product_name' => [
                    'type' => 'string',
                    'description' => 'The name of the product the user is asking about.'
                ],
                'category' => [
                    'type' => 'string',
                    'description' => 'The category of the products.',
                    'enum' => array_column(sb_woocommerce_get_taxonomies('category'), 'name')
                ],
                'tag' => [
                    'type' => 'string',
                    'description' => 'The tags of the products.',
                    'enum' => array_column(sb_woocommerce_get_taxonomies('tag'), 'name')
                ],
                'attribute' => [
                    'type' => 'string',
                    'description' => 'A specific product attribute.',
                    'enum' => array_column(sb_woocommerce_get_attributes('terms'), 'name')
                ],
                'term' => [
                    'type' => 'string',
                    'description' => 'A specific product term.',
                    'enum' => array_column(sb_woocommerce_get_taxonomies('terms'), 'name')
                ],
                'max-price' => [
                    'type' => 'string',
                    'description' => 'A max price.'
                ],
                'min-price' => [
                    'type' => 'string',
                    'description' => 'A minimum price.'
                ],
                'discounted' => [
                    'type' => 'boolean',
                    'description' => 'The user is asking specifically for discounted products.'
                ]
            ], 'required' => []]]
        ]
    ];
}

function sb_woocommerce_open_ai_function_calling($function_name, $id, $arguments, $query_tools) {
    $tag = sb_isset($arguments, 'tag');
    $term = sb_isset($arguments, 'term');
    if ($query_tools) {
        for ($i = 0; $i < count($query_tools); $i++) {
            $query_tools_function = $query_tools[$i]['function'];
            if ($query_tools_function['name'] == $function_name) {
                $properties = $query_tools_function['parameters']['properties'];
                $enum_tag = sb_isset(sb_isset($properties, 'tag'), 'enum');
                $enum_terms = sb_isset(sb_isset($properties, 'term'), 'enum');
                if ($tag && in_array($tag, $enum_terms)) {
                    $term = $tag;
                    $tag = false;
                }
                if ($term && in_array($term, $enum_tag) && !in_array($term, $enum_terms)) {
                    $tag = $term;
                    $term = false;
                }
                break;
            }
        }
    }
    $single_product_information = sb_isset($arguments, 'information');
    $response = sb_woocommerce_open_ai_message(sb_isset($arguments, 'product_name'), sb_isset($arguments, 'category'), $tag, $term, sb_isset($arguments, 'attribute'), sb_isset($arguments, 'max-price'), sb_isset($arguments, 'min-price'), $single_product_information, sb_isset($arguments, 'discounted'));
    return $response ? ($function_name == 'sb-woocommerce' || $single_product_information == 'Photos' ? ['sb-shortcode', $id, $response] : [$function_name, $id, $response]) : [$function_name, $id, []];
}

function sb_woocommerce_open_ai_function_calling_2($function_name, $id, $arguments) {
    $is_shortcode = true;
    switch ($function_name) {
        case 'sb-woocommerce-order':
            $response = sb_woocommerce_merge_fields('{order_details}');
            break;
        case 'sb-woocommerce-cart':
            $response = sb_woocommerce_merge_fields('{cart}');
            break;
        case 'sb-woocommerce-shipment':
            $response = sb_woocommerce_shipping_locations()[1];
            $is_shortcode = false;
            break;
        case 'sb-woocommerce-payment':
            $response = sb_woocommerce_payment_methods();
            $is_shortcode = false;
            break;
        case 'sb-woocommerce-cart-update':
            $product_id = sb_woocommerce_get_product_id_by_name($arguments['product_name']);
            $is_add = $arguments['action'] == 'Add';
            $message = false;
            if ($product_id) {
                if ($is_add) {
                    if (!sb_woocommerce_is_in_stock($product_id)) {
                        $message = 'Sorry, the product is out of stock.';
                    }
                } else {
                    $is_in_cart = false;
                    $session_key = sb_woocommerce_get_session_key(sb_isset(sb_get_active_user(), 'id', -1));
                    if ($session_key !== false) {
                        $session = sb_woocommerce_get_session($session_key);
                        if ($session && sb_isset($session, 'cart')) {
                            foreach ($session['cart'] as $value) {
                                if (sb_isset($value, 'product_id') == $product_id) {
                                    $is_in_cart = true;
                                    break;
                                }
                            }
                        }
                    }
                    if (!$is_in_cart) {
                        $message = 'The product is not in the cart.';
                    }
                }
            } else {
                $message = 'The product was not found.';
            }
            return ['payload', $id, $message ? $message : $arguments['product_name'] . ' has been ' . ($is_add ? 'added to' : 'removed from') . ' the cart.', $message ? [] : ['event' => 'woocommerce-update-cart', 'action' => $is_add ? 'cart-add' : 'cart-remove', 'id' => $product_id]];
        case 'sb-woocommerce-checkout-redirect':
            return ['payload', $id, 'You are being redirected to the checkout page.', ['event' => 'woocommerce-checkout']];
    }
    return [$is_shortcode ? 'sb-shortcode' : $function_name, $id, $response];
}

function sb_woocommerce_open_ai_message($title = false, $category = false, $tag = false, $term = false, $attribute = false, $max_price = false, $min_price = false, $single_product_information = false, $discounted = false) {
    $products = $title && !$category && !$tag && !$attribute && !$discounted ? sb_woocommerce_search_products($title) : sb_woocommerce_get_products(['title' => $title, 'attribute' => $attribute, 'taxonomy' => $tag ? $tag : ($category ? $category : $term), 'max-price' => $max_price, 'min-price' => $min_price, 'discounted' => $discounted]);
    if (!empty($products)) {
        if ($single_product_information) {
            $ids = array_column($products, 'id');
            sort($ids);
            $product = sb_woocommerce_get_product($ids[0]);
            if ($single_product_information == 'Photos') {
                $detail = sb_woocommerce_get_product_images($product['id']);
            } else {
                $detail = sb_isset($product, strtolower($single_product_information));
                if ($detail && $single_product_information == 'Rating') {
                    $detail_ = [];
                    foreach ($detail as $key => $value) {
                        array_push($detail_, $value . ' users rated it ' . $key . ' out of 5');
                    }
                    $detail = $detail_;
                }
            }
            return $detail ? $detail : $product;
        }
        return $products;
    }
    return false;
}

function sb_woocommerce_open_ai_check_function_name($function_name, $index = 1) {
    $functions = $index == 1 ? ['sb-woocommerce', 'sb-woocommerce-single'] : ['sb-woocommerce-order', 'sb-woocommerce-cart', 'sb-woocommerce-shipment', 'sb-woocommerce-payment', 'sb-woocommerce-cart-update', 'sb-woocommerce-checkout-redirect'];
    return in_array($function_name, $functions);
}

/*
 * ----------------------------------------------------------
 * MORE FUNCTIONS
 * ----------------------------------------------------------
 *
 * 1. Popup of the admin area products list
 * 2. Return the average rating of a product
 * 3. Return the url of category, tag, shop
 * 4. Return the user saved by WooCommerce
 * 5. Return the session object containing cart and customer details
 * 6. Return the session key of the user cookie
 * 7. Save the session key of the user cookie
 * 8. Return the active payment methods
 * 9. Return the shipment countries
 * 10. Assign orders to another Support Board user
 *
 */

function sb_woocommerce_products_popup() {
    echo '<div class="sb-popup sb-woocommerce-products"><div class="sb-header"><div class="sb-select"><p data-value="">' . sb_('All') . '</p><ul class="sb-scroll-area"></ul></div><div class="sb-search-btn"><i class="sb-icon sb-icon-search"></i><input type="text" placeholder="' . sb_('Search ...') . '" /></div></div><div class="sb-woocommerce-products-list sb-list-thumbs sb-scroll-area"><ul class="sb-loading"></ul></div><i class="sb-icon-close sb-popup-close"></i></div>';
}

function sb_woocommerce_rating($ratings) {
    $total = 0;
    $count = 0;
    if (empty($ratings))
        return false;
    foreach ($ratings as $key => $value) {
        $count += intval($value);
        $total += intval($key) * intval($value);
    }
    return round($total / $count, 2);
}

function sb_woocommerce_get_url($type, $name = '', $language = '') {
    $site_url = sb_wp_site_url();
    $url_slug = '';
    $url_parameter = '';
    $multilingual_plugin = '';
    $language_settings = [];
    if (is_array($name))
        $name = $name[0];
    if ($language != '') {
        $multilingual_plugin = sb_get_setting('wp-multilingual-plugin');
        if ($multilingual_plugin != '') {
            $language_settings = sb_wp_language_settings();
            if (in_array($language, $language_settings['languages']) && $language != $language_settings['default']) {
                $url_slug = $language_settings['link-type'] == 1 ? '/' . $language : '';
                $url_parameter = $language_settings['link-type'] == 1 ? '' : '?lang=' . $language;
            }
        }
    }
    switch ($type) {
        case 'tag':
        case 'category':
            $link = sb_db_get('SELECT slug FROM ' . SB_WP_PREFIX . 'terms WHERE name = "' . sb_db_escape($name) . '" LIMIT 1');
            if (sb_isset($link, 'slug')) {
                return $site_url . $url_slug . ($type == 'category' ? '/product-category/' : '/product-tag/') . $link['slug'] . $url_parameter;
            }
            break;
        case 'cart':
        case 'shop':
            $url = sb_get_multi_setting('wc-urls', 'wc-urls-' . $type);
            if ($url)
                return $url;
            $id = sb_db_get('SELECT option_value FROM ' . SB_WP_PREFIX . 'options WHERE option_name = "' . ($type == 'shop' ? 'woocommerce_shop_page_id' : 'woocommerce_cart_page_id') . '"');
            if (sb_isset($id, 'option_value')) {
                $id = $id['option_value'];
                if ($language && $language != sb_isset($language_settings, 'default')) {
                    $id = sb_wp_language_get_page_id($id, $language, $multilingual_plugin);
                }
                return $site_url . $url_slug . ($url_parameter ? $url_parameter . '&p=' : '?p=') . $id;
            }
            break;
        case 'checkout':
            $url = sb_get_multi_setting('wc-urls', 'wc-urls-checkout');
            return $url ? $url : wc_get_checkout_url();
    }
    return '';
}

function sb_woocommerce_get_session($session_key = false) {
    if (empty($session_key)) {
        $session_key = sb_woocommerce_get_session_key();
    }
    $session = sb_db_get('SELECT session_value FROM ' . SB_WP_PREFIX . 'woocommerce_sessions WHERE session_key = "' . $session_key . '"');
    if (sb_isset($session, 'session_value')) {
        $session = unserialize($session['session_value']);
        $session['cart'] = isset($session['cart']) ? unserialize($session['cart']) : [];
        $session['customer'] = isset($session['customer']) ? unserialize($session['customer']) : [];
        $session['cart_totals'] = isset($session['cart_totals']) ? unserialize($session['cart_totals']) : [];
        return $session;
    }
    return false;
}

function sb_woocommerce_get_session_key($user_id = false) {
    if ($user_id !== false) {
        return sb_isset(sb_db_get('SELECT value FROM sb_users_data WHERE slug = "woocommerce_session_key" AND user_id = ' . $user_id), 'value');
    } else if (!empty($_COOKIE)) {
        foreach ($_COOKIE as $key => $value) {
            if (strpos($key, 'wp_woocommerce_session_') !== false) {
                return explode('||', urldecode($value))[0];
            }
        }
    }
    return false;
}

function sb_woocommerce_save_session_key($session_key = false) {
    if ($session_key === false) {
        $session_key = sb_woocommerce_get_session_key();
    }
    if (!empty($session_key)) {
        $active_user = sb_get_active_user();
        if ($active_user && !sb_is_agent($active_user) && is_string($session_key) && $session_key != sb_woocommerce_get_session_key($active_user['id'])) {
            return sb_update_user_value($active_user['id'], 'woocommerce_session_key', $session_key, 'Woo Session Key');
        }
    }
    return false;
}

function sb_woocommerce_payment_methods() {
    $active_methods = [];
    $all_methods = sb_db_get('SELECT option_value FROM ' . SB_WP_PREFIX . 'options WHERE option_name = "woocommerce_gateway_order"');
    if (sb_isset($all_methods, 'option_value')) {
        $all_methods = unserialize($all_methods['option_value']);
        $query = '';
        foreach ($all_methods as $key => $value) {
            $query .= 'option_name = "woocommerce_' . $key . '_settings" OR ';
        }
        $methods = sb_db_get('SELECT option_name, option_value FROM ' . SB_WP_PREFIX . 'options WHERE ' . substr($query, 0, -4), false);
        for ($i = 0; $i < count($methods); $i++) {
            $method = unserialize($methods[$i]['option_value']);
            if (sb_isset($method, 'enabled') == 'yes') {
                $name = sb_isset($method, 'title');
                if (!$name) {
                    $name = str_replace(['woocommerce_', '_settings'], '', $methods[$i]['option_name']);
                    switch ($name) {
                        case 'bacs':
                            $name = 'Direct bank transfer';
                            break;
                        case 'cod':
                            $name = 'Cash on delivery';
                            break;
                        default:
                            $name = strtoupper($name);
                    }
                }
                array_push($active_methods, sb_($name));
            }
        }
    }
    return $active_methods;
}

function sb_woocommerce_shipping_locations($country_code = false) {
    $settings = sb_db_get('SELECT option_name, option_value FROM ' . SB_WP_PREFIX . 'options WHERE (option_name = "woocommerce_allowed_countries" OR option_name = "woocommerce_all_except_countries" OR option_name = "woocommerce_specific_allowed_countries" OR option_name = "woocommerce_ship_to_countries" OR option_name = "woocommerce_specific_ship_to_countries") AND option_value <> "" AND option_value <> "a:0:{}"', false);
    $sell_rule = '';
    $ship_rule = '';
    $countries_allowed = [];
    $countries_ship_allowed = [];
    $countries_excluded = [];
    for ($i = 0; $i < count($settings); $i++) {
        $name = $settings[$i]['option_name'];
        $value = $settings[$i]['option_value'];
        switch ($name) {
            case 'woocommerce_allowed_countries':
                $sell_rule = $value;
                break;
            case 'woocommerce_ship_to_countries':
                $ship_rule = $value;
                break;
            case 'woocommerce_specific_allowed_countries':
                $countries_allowed = unserialize($value);
                break;
            case 'woocommerce_specific_ship_to_countries':
                $countries_ship_allowed = unserialize($value);
                break;
            case 'woocommerce_all_except_countries':
                $countries_excluded = unserialize($value);
                break;
        }
    }
    if ($ship_rule == 'all' || ($sell_rule == 'all' && !$ship_rule)) {
        return $country_code ? true : [sb_('all countries'), 'all', true];
    } else {
        $countries = $ship_rule == 'specific' ? $countries_ship_allowed : ($sell_rule == 'all_except' ? $countries_excluded : $countries_allowed);
        $countries_string = '';
        $exclude = $sell_rule == 'all_except' && $ship_rule != 'specific';
        $country_code = strtoupper($country_code);
        if ($country_code) {
            $included = false;
            for ($i = 0; $i < count($countries); $i++) {
                if ($countries[$i] == $country_code) {
                    $included = true;
                    break;
                }
            }
            return (!$exclude && $included) || ($exclude && !$included);
        }
        $country_names = sb_get_json_resource('json/country_codes.json');
        for ($i = 0; $i < count($countries); $i++) {
            $countries[$i] = [sb_(sb_isset($country_names, $countries[$i], $countries[$i])), $countries[$i]];
            $countries_string .= sb_($countries[$i][0]) . ', ';
        }
        return [($exclude ? sb_('all countries except') . ' ' : '') . substr($countries_string, 0, -2), $countries, $exclude];
    }
}

function sb_woocommerce_assign_orders($user_id, $old_user_id) {
    return sb_db_query('UPDATE ' . SB_WP_PREFIX . 'postmeta SET meta_value = ' . sb_db_escape($user_id) . ' WHERE meta_key = "sb-user" AND meta_value = ' . sb_db_escape($old_user_id));
}

/*
 * ----------------------------------------------------------
 * ACTIONS
 * ----------------------------------------------------------
 *
 * WordPress actions and hooks.
 *
 */

function sb_woocommerce_actions() {
    add_action('woocommerce_order_status_changed', 'sb_woocommerce_order_status_completed', 10, 1);
    add_action('woocommerce_order_status_processing', 'sb_woocommerce_order_status_completed', 10, 1);
    add_action('woocommerce_order_status_pending', 'sb_woocommerce_order_status_completed', 10, 1);
    add_action('woocommerce_order_status_completed', 'sb_woocommerce_order_status_completed', 10, 1);
    add_action('woocommerce_new_order', 'sb_woocommerce_order_status_completed', 10, 1);
    add_action('woocommerce_add_to_cart_validation', 'sb_woocommerce_on_cart_add', 99, 1);
    add_action('woocommerce_add_cart_item', 'sb_woocommerce_save_session_key_action', 99, 1);
    add_action('woocommerce_update_product', 'sb_woocommerce_product_updated', 10, 1);
    if (sb_get_multi_setting('wc-product-removed', 'wc-product-removed-active')) {
        add_action('woocommerce_remove_cart_item', 'sb_woocommerce_product_removed', 10, 2);
    }
}

function sb_woocommerce_order_status_completed($order_id) {
    $active_user_id = false;
    $active_user = sb_get_active_user();
    $is_follow_up = sb_get_multi_setting('wc-follow-up', 'wc-follow-up-active');
    $is_social_share = sb_get_multi_setting('wc-share', 'wc-share-active');
    $is_update_user = !$active_user || $active_user['user_type'] != 'user';
    $email_delay = sb_get_multi_setting('wc-follow-up', 'wc-follow-up-email-delay');
    $order = $is_update_user || $is_follow_up ? wc_get_order($order_id) : false;
    if (get_post_meta($order_id, 'sb-user')) {
        return;
    }

    // Link the order to the Support Board user
    if ($active_user) {
        $active_user_id = $active_user['id'];
        add_post_meta($order_id, 'sb-user', $active_user_id);
    }

    // Update user details
    if ($is_update_user) {
        $user_details = [['first_name' => '', 'last_name' => '', 'email' => ''], ['address' => '', 'phone' => '', 'company' => '', 'address_1' => '', 'address_2' => '', 'city' => '', 'state' => '', 'postcode' => '', 'country' => '']];
        for ($i = 0; $i < 2; $i++) {
            foreach ($user_details[$i] as $key => $value) {
                $user_details[$i][$key] = $order->data['billing'][$key] ? $order->data['billing'][$key] : $order->data['shipping'][$key];
            }
        }
        $user_details[1]['address'] .= $user_details[1]['address_1'] . ($user_details[1]['address_2'] != '' ? ', ' . $user_details[1]['address_2'] : '');
        unset($user_details[1]['address_1']);
        unset($user_details[1]['address_2']);
        foreach ($user_details[1] as $key => $value) {
            $user_details[1][$key] = [$value, ucfirst($key)];
        }
        if ($user_details[0]['first_name']) {
            if ($active_user) {
                sb_update_user($active_user_id, $user_details[0], $user_details[1]);
            } else {
                sb_add_user_and_login($user_details[0], $user_details[1]);
            }
        }
    }

    // Follow-up, social share, and email
    if ($order && ($is_follow_up || !empty($email_delay) || $is_social_share)) {
        $active_user = sb_get_active_user();
        $products = [];
        $product_ids = [];
        foreach ($order->get_items() as $value) {
            $data = $value->get_data();
            array_push($products, ['name' => $data['name']]);
            array_push($product_ids, $data['product_id']);
        }
        if ($is_follow_up) {
            $message = sb_get_multi_setting('wc-follow-up', 'wc-follow-up-message');
            if ($active_user && $message != '' && (strpos($message, '{coupon}') === false || !sb_woocommerce_coupon_check($active_user_id))) {
                sb_send_message(sb_get_bot_id(), sb_get_last_conversation_id_or_create($active_user_id, 3), sb_woocommerce_merge_fields($message, ['products' => $products, 'coupon-discount' => sb_get_multi_setting('wc-follow-up', 'wc-follow-up-coupon-discount'), 'coupon-expiration' => sb_get_multi_setting('wc-follow-up', 'wc-follow-up-coupon-expiration') . ' seconds', 'user-id' => $active_user_id]), [], 1, '{ "event": "open-chat" }');
            }
        }
        if ($is_social_share) {
            $title = sb_get_multi_setting('wc-share', 'wc-share-title');
            $message = sb_get_multi_setting('wc-share', 'wc-share-message');
            if ($title && $message) {
                $message = '[share title="' . sb_rich_value($title) . '" message="' . sb_rich_value($message) . '" link="' . get_permalink($product_ids[0]) . '" channels="fb,tw,li,pi,wa"]';
                sb_send_message(sb_get_bot_id(), sb_get_last_conversation_id_or_create($active_user_id, 3), sb_woocommerce_merge_fields($message, ['products' => [$products[0]]]), [], 1, '{ "event": "open-chat" }');
            }
        }
        if (!empty($email_delay)) {
            sb_cron_jobs_add('wc-follow-up', $product_ids, $email_delay . ' hours');
        }
    }
    return $order_id;
}

function sb_woocommerce_product_removed($key, $cart) {
    $removed_products = [];
    $removed_product_ids = [];
    $active_user = sb_get_active_user();
    $message = sb_get_multi_setting('wc-product-removed', 'wc-product-removed-message');
    $email_delay = sb_get_multi_setting('wc-product-removed', 'wc-product-removed-email-delay');
    foreach ($cart->removed_cart_contents as $product) {
        array_push($removed_product_ids, $product['product_id']);
        array_push($removed_products, sb_woocommerce_get_product($product['product_id']));
    }
    if ($active_user && $message != '' && (strpos($message, '{coupon}') === false || !sb_woocommerce_coupon_check($active_user['id']))) {
        sb_send_message(sb_get_bot_id(), sb_get_last_conversation_id_or_create($active_user['id'], 3), sb_woocommerce_merge_fields($message, ['products' => $removed_products, 'coupon-discount' => sb_get_multi_setting('wc-product-removed', 'wc-product-removed-coupon-discount'), 'coupon-expiration' => sb_get_multi_setting('wc-product-removed', 'wc-product-removed-coupon-expiration') . ' seconds', 'coupon-product-ids' => json_encode($removed_product_ids), 'user-id' => $active_user['id']]), [], 1, '{ "event": "open-chat" }');
    }
    if (!empty($email_delay)) {
        sb_cron_jobs_add('wc-product-removed', $removed_product_ids, $email_delay . ' hours');
    }
    return [$key, $cart];
}

function sb_woocommerce_product_updated($post_id) {
    $post = get_post($post_id);
    if ($post->post_type == 'product') {
        if (sb_get_multi_setting('wc-waiting-list', 'wc-waiting-list-active')) {
            sb_woocommerce_waiting_list($post_id, false, false, 'send');
        }
    }
    return $post_id;
}

function sb_woocommerce_save_session_key_action($cart_item) {
    $session_key = WC()->session->get_customer_id();
    sb_woocommerce_save_session_key($session_key);
    if (sb_get_multi_setting('wc-abandoned-cart', 'wc-abandoned-cart-1') == 'now' && sb_get_active_user()) {
        $cart = $cart_item['data']->get_data();
        $cart = [['product_id' => $cart_item['product_id'], 'line_subtotal' => $cart['price'], 'quantity' => $cart_item['quantity']]];
        $cart_item_serialized = ['session_value' => serialize(['cart' => serialize($cart)]), 'session_expiry' => time() - 1, 'session_id' => false, 'session_key' => sb_get_user_extra(sb_get_active_user()['id'], 'woocommerce_session_key')];
        sb_woocommerce_abandoned_carts($cart_item_serialized);
    }
    return $cart_item;
}

function sb_woocommerce_on_cart_add($parameters) {

    // Follow-up
    if (sb_get_setting('wc-follow-up-cart')) {
        $active_user = sb_get_active_user();
        if ($active_user && empty($active_user['email'])) {
            $settings = sb_get_setting('follow-message');
            sb_send_message(sb_get_bot_id(), sb_get_last_conversation_id_or_create($active_user['id'], 3), ' [email title="' . sb_rich_value($settings['title']) . '" message="' . sb_rich_value($settings['message']) . '" placeholder="' . sb_rich_value($settings['placeholder']) . '" name="' . $settings['name'] . '" last-name="' . $settings['last-name'] . '" success="' . sb_rich_value($settings['success']) . '"]');
        }
    }

    sb_woocommerce_save_session_key();
    return $parameters;
}

/*
 * ----------------------------------------------------------
 * WORDPRESS ENVIRONMENT FUNCTIONS
 * ----------------------------------------------------------
 *
 * Functions that require WordPress to work.
 *
 */

function sb_woocommerce_inline() {
    $code = '';
    $page_id = get_the_ID();
    if (sb_get_multi_setting('wc-waiting-list', 'wc-waiting-list-active') && is_product() && !sb_woocommerce_is_in_stock($page_id)) {
        $code = 'var SB_WP_WAITING_LIST = true;';
    }
    return $code;
}

function sb_woocommerce_update_cart($product_id, $type, $quantity = 1) {
    if (!is_numeric($product_id)) {
        $product_id = sb_woocommerce_get_product_id_by_name($product_id);
    }
    if (!empty($product_id)) {
        if ($type == 'cart-add') {
            $is_in_stock = sb_woocommerce_is_in_stock($product_id);
            if (!$is_in_stock) {
                sb_woocommerce_waiting_list($product_id);
                return 'out-of-stock';
            }
            return WC()->cart->add_to_cart($product_id, $quantity);
        } else if ($type == 'cart-remove') {
            foreach (WC()->cart->get_cart() as $key => $item) {
                if ($item['product_id'] == $product_id) {
                    return WC()->cart->remove_cart_item($key);
                }
            }
        }
    }
    return false;
}

function sb_woocommerce_apply_coupon($coupon) {
    global $woocommerce;
    return $woocommerce->cart->add_discount($coupon);
}

function sb_woocommerce_purchase_button($product_ids, $coupon = false, $checkout = true) {
    for ($i = 0; $i < count($product_ids); $i++) {
        if (sb_woocommerce_update_cart($product_ids[$i], 'cart-add') == 'out-of-stock')
            return 'out-of-stock';
    }
    if (!empty($coupon)) {
        sb_woocommerce_apply_coupon($coupon);
    }
    return $checkout == 'true' ? wc_get_checkout_url() : true;
}

?>