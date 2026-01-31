<?php
/**
 * Dashboard Template
 */

$dashboard = new HireSmart_Dashboard();
$user_id = get_current_user_id();
echo $dashboard->get_dashboard_content($user_id);
?>
