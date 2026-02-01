<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="HireSmart - AI-Powered Job Portal and Career Builder with ATS. Connect job seekers, employers, and agencies with neural AI insights.">
    <title><?php bloginfo('name'); ?> - <?php bloginfo('description'); ?></title>
    <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header class="site-header">
    <div class="container">
        <div class="header-content">
            <a href="<?php echo home_url(); ?>" class="logo">
                Hire<span>Smart</span>
            </a>
            
            <nav class="main-nav">
                <ul>
                    <li><a href="<?php echo home_url(); ?>#features">Features</a></li>
                    <li><a href="<?php echo home_url(); ?>#use-cases">Use Cases</a></li>
                    <li><a href="<?php echo home_url(); ?>#differentiators">Why Us</a></li>
                    <li><a href="<?php echo home_url(); ?>#pricing">Pricing</a></li>
                    <?php if (is_user_logged_in()): ?>
                        <li><a href="<?php echo site_url('/dashboard'); ?>">Dashboard</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            
            <div class="header-actions">
                <?php if (is_user_logged_in()): ?>
                    <a href="<?php echo wp_logout_url(home_url()); ?>" class="btn-secondary">Logout</a>
                <?php else: ?>
                    <a href="<?php echo site_url('/login'); ?>" class="btn-secondary">Sign In</a>
                    <a href="<?php echo site_url('/register'); ?>" class="cta-button">Get Started</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>
