<?php

// 1B) AJAX: Assigned vs. All Clients
add_action('wp_ajax_get_assigned_clients_stats', 'ajax_get_assigned_clients_stats');
function ajax_get_assigned_clients_stats() {
    $uid = get_current_user_id();
    // my clients
    $mine = new WP_Query([
        'post_type'      => 'client',
        'post_status'    => 'publish',
        'meta_query'     => [[
            'key'   => 'assigned_employee',
            'value' => $uid,
        ]],
        'fields'         => 'ids',
        'posts_per_page' => -1,
    ]);
    // all clients
    $total = wp_count_posts('client')->publish;
    wp_send_json_success([
        'mine'  => intval($mine->found_posts),
        'total' => intval($total),
    ]);
}

// 1C) AJAX: New Clients Last 30 Days
add_action('wp_ajax_get_new_clients_30d', 'ajax_get_new_clients_30d');
function ajax_get_new_clients_30d() {
    $data = [];
    for ($i = 29; $i >= 0; $i--) {
        $day   = date_i18n('M j', strtotime("-{$i} days"));
        $count = new WP_Query([
            'post_type'      => 'client',
            'post_status'    => 'publish',
            'date_query'     => [[
                'year'  => date('Y', strtotime("-{$i} days")),
                'month' => date('m', strtotime("-{$i} days")),
                'day'   => date('d', strtotime("-{$i} days")),
            ]],
            'fields'         => 'ids',
            'posts_per_page' => -1,
        ]);
        $data[$day] = intval($count->found_posts);
    }
    wp_send_json_success($data);
}

// 1D) AJAX: Recent Client Activity (recently  modified)
add_action('wp_ajax_get_recent_client_activity', 'ajax_get_recent_client_activity');
function ajax_get_recent_client_activity() {
    $q = new WP_Query([
        'post_type'      => 'client',
        'post_status'    => 'publish',
        'orderby'        => 'modified',
        'order'          => 'DESC',
        'posts_per_page' => 4,
    ]);
    $items = [];
    while ($q->have_posts()) {
        $q->the_post();
        $items[] = [
            'title' => get_the_title(),
            // 'link'  => get_permalink(),
            'link' => get_home_url() . '/create-client/?new_post_id=' . intval(get_the_ID()),
            'stage' => get_field('client_stage', get_the_ID()) ?: '—',
            'when'  => human_time_diff(get_the_modified_time('U'), current_time('timestamp')) . ' ago',
        ];
    }
    wp_reset_postdata();
    wp_send_json_success($items);
}

// 4) Clients by Stage (Pie Chart)
add_action('wp_ajax_get_stage_distribution', function () {
    $stages = get_certification_stages()['ems']; // your scheme
    $labels = $data = [];
    foreach ($stages as $key => $cfg) {
        $count = new WP_Query([
            'post_type'      => 'client',
            'post_status'    => 'publish',
            'meta_query'     => [[
                'key'   => 'client_stage',
                'value' => $key,
            ]],
            'fields'         => 'ids',
            'posts_per_page' => -1
        ]);
        $labels[] = $cfg['title'];
        $data[]   = intval($count->found_posts);
    }
    wp_send_json_success(['labels' => $labels, 'data' => $data]);
});

// 5) Certificates Expiring Next 30 Days (Bar Chart)
add_action('wp_ajax_get_expiring_certificates', function () {
    $today = date('Y-m-d');
    $future = date('Y-m-d', strtotime('+30 days'));
    $q = new WP_Query([
        'post_type'      => 'certificates',
        'post_status'    => 'publish',
        'meta_query'     => [[
            'key'     => 'expiry_date',
            'value'   => [$today, $future],
            'compare' => 'BETWEEN',
            'type'    => 'DATE'
        ]],
        'fields'         => 'ids',
        'posts_per_page' => -1
    ]);
    wp_send_json_success(['count' => intval($q->found_posts)]);
});

// 6) Clients Per Employee (Horizontal Bar)
add_action('wp_ajax_get_clients_per_employee', function () {
    $users = get_users(['role__in' => ['administrator', 'manager', 'employee']]);
    $labels = [];
    $data = [];
    foreach ($users as $u) {
        $count = new WP_Query([
            'post_type'      => 'client',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'meta_query'     => [[
                'key' => 'assigned_employee',
                'value' => $u->ID
            ]]
        ]);
        $labels[] = $u->display_name;
        $data[]   = intval($count->found_posts);
    }
    wp_send_json_success(['labels' => $labels, 'data' => $data]);
});

// 7) Total Certificates (Simple Number)
add_action('wp_ajax_get_total_certificates', function () {
    $n = wp_count_posts('certificates')->publish;
    wp_send_json_success(['total' => (int)$n]);
});
