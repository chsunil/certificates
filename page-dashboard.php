<?php
/* Template Name: Dashboard */
get_header();
?>

<div class="container-fluid p-0">
    <div class="d-flex flex-column flex-md-row wrapper">
        <aside class="sidebar p-0 rounded shadow-sm position-sticky">
            <?php get_sidebar('custom'); ?>
        </aside>

        <main id="content" class="flex-fill my-4">
            <div class="container my-4">
                <h3>Welcome, <?php echo esc_html(wp_get_current_user()->display_name); ?>!</h3>
                <div class="row">

                    <!-- 1) Assigned vs All Clients -->
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-header">My Clients vs. All Clients</div>
                            <div class="card-body">
                                <canvas id="assignedClientsChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- 2) New Clients in Last 30 Days -->
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-header">New Clients (Last 30 Days)</div>
                            <div class="card-body">
                                <canvas id="newClientsChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- 3) Recent Activity Feed -->
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-header">Recent Client Activity</div>
                            <ul id="recentActivityList" class="list-group list-group-flush m-0"></ul>
                        </div>
                    </div>
                    <!-- 4) Stage Distribution Pie -->
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-header">Clients by Stage</div>
                            <div class="card-body">
                                <canvas id="stageDistChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- 5) Expiring Certificates (Next 30d) -->
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-header">Certificates Expiring Next 30 Days</div>
                            <div class="card-body">
                                <h2 id="expiringCount" class="text-center">–</h2>
                            </div>
                        </div>
                    </div>

                    <!-- 6) Clients per Employee -->
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-header">Clients per Employee</div>
                            <div class="card-body">
                                <canvas id="clientsPerEmpChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- 7) Total Certificates -->
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-header">Total Certificates</div>
                            <div class="card-body">
                                <h2 id="totalCerts" class="text-center">–</h2>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>
</div>

<?php get_footer(); ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // 1) Assigned vs All
        fetch('<?php echo admin_url("admin-ajax.php?action=get_assigned_clients_stats"); ?>', {
            credentials: 'same-origin'
        }).then(r => r.json()).then(r => {
            if (!r.success) return;
            new Chart(
                document.getElementById('assignedClientsChart').getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: ['My Clients', 'All Clients'],
                        datasets: [{
                            label: 'Count',
                            data: [r.data.mine, r.data.total],
                            backgroundColor: ['#28a745', '#007bff']
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                }
            );
        });

        // 2) New Clients 30d
        fetch('<?php echo admin_url("admin-ajax.php?action=get_new_clients_30d"); ?>', {
            credentials: 'same-origin'
        }).then(r => r.json()).then(r => {
            if (!r.success) return;
            const labels = Object.keys(r.data),
                data = Object.values(r.data);
            new Chart(
                document.getElementById('newClientsChart').getContext('2d'), {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [{
                            label: 'New Clients',
                            data
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                }
            );
        });

        // 3) Recent Activity
        fetch('<?php echo admin_url("admin-ajax.php?action=get_recent_client_activity"); ?>', {
            credentials: 'same-origin'
        }).then(r => r.json()).then(r => {
            if (!r.success) return;
            const list = document.getElementById('recentActivityList');
            r.data.forEach(item => {
                const li = document.createElement('li');
                li.className = 'list-group-item';
                li.innerHTML = `
        <a href="${item.link}" target="_blank">${item.title}</a>
        <br><small>${item.stage} — ${item.when}</small>
      `;
                list.appendChild(li);
            });
        });

        // 4) Stage Distribution Pie
        fetch('<?php echo admin_url("admin-ajax.php?action=get_stage_distribution"); ?>', {
                credentials: 'same-origin'
            })
            .then(r => r.json()).then(r => {
                if (!r.success) return;
                new Chart(document.getElementById('stageDistChart'), {
                    type: 'pie',
                    data: {
                        labels: r.data.labels,
                        datasets: [{
                            data: r.data.data,
                            backgroundColor: [
                                '#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6f42c1', '#fd7e14'
                            ]
                        }]
                    }
                });
            });

        // 5) Expiring Certificates Count
        fetch('<?php echo admin_url("admin-ajax.php?action=get_expiring_certificates"); ?>', {
                credentials: 'same-origin'
            })
            .then(r => r.json()).then(r => {
                if (!r.success) return;
                document.getElementById('expiringCount').textContent = r.data.count;
            });

        // 6) Clients per Employee Horizontal Bar
        fetch('<?php echo admin_url("admin-ajax.php?action=get_clients_per_employee"); ?>', {
                credentials: 'same-origin'
            })
            .then(r => r.json()).then(r => {
                if (!r.success) return;
                new Chart(document.getElementById('clientsPerEmpChart').getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: r.data.labels,
                        datasets: [{
                            label: 'Clients',
                            data: r.data.data,
                            backgroundColor: '#17a2b8'
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        scales: {
                            x: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            });

        // 7) Total Certificates
        fetch('<?php echo admin_url("admin-ajax.php?action=get_total_certificates"); ?>', {
                credentials: 'same-origin'
            })
            .then(r => r.json()).then(r => {
                if (!r.success) return;
                document.getElementById('totalCerts').textContent = r.data.total;
            });


    });
</script>