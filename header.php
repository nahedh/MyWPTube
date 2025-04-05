<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
  <header class="site-header">
    <div class="header-container">
      <!-- القسم الأيمن: الشعار واسم الموقع -->
      <div class="header-right">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="site-branding">
          <?php
          if (has_custom_logo()) {
            the_custom_logo();
          } else {
            echo '<span class="site-title">' . get_bloginfo('name') . '</span>';
          }
          ?>
          <span class="site-slogan"><?php echo get_bloginfo('description'); ?></span>
        </a>
      </div>

      <!-- القسم الأوسط: البحث -->
      <div class="header-center">
        <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
          <div class="search-container">
            <button type="submit" class="search-button">
              <i class="fas fa-search"></i>
            </button>
            <input type="search" 
                   class="search-input" 
                   placeholder="ابحث في الموقع..." 
                   value="<?php echo get_search_query(); ?>" 
                   name="s" />
          </div>
        </form>
      </div>

      <!-- القسم الأيسر: أيقونات التواصل -->
      <div class="header-left">
        <div class="social-icons">
          <a href="https://twitter.com/nahedh" target="_blank" class="social-icon">
            <i class="fab fa-twitter"></i>
          </a>
          <a href="https://youtube.com/@nahedh" target="_blank" class="social-icon">
            <i class="fab fa-youtube"></i>
          </a>
          <a href="https://linkedin.com/in/nahedh" target="_blank" class="social-icon">
            <i class="fab fa-linkedin"></i>
          </a>
        </div>
      </div>
    </div>
  </header>
  <main id="content" class="site-content"><?php // محتوى الموقع يبدأ هنا ?>