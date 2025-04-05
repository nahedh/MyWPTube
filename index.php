<?php get_header(); ?>

<div class="grid-container">
    <?php
    // عرض المقاطع المثبتة أولاً
    $sticky_posts = get_option('sticky_posts');
    if (!empty($sticky_posts)) {
        $sticky_query = new WP_Query(array(
            'post__in' => $sticky_posts,
            'ignore_sticky_posts' => 1
        ));
        
        if ($sticky_query->have_posts()) : while ($sticky_query->have_posts()) : $sticky_query->the_post();
            get_template_part('template-parts/content', 'video-card');
        endwhile; endif;
        
        wp_reset_postdata();
    }

    // عرض باقي المقاطع
    if (have_posts()) : while (have_posts()) : the_post();
        if (!in_array(get_the_ID(), $sticky_posts)) {
            get_template_part('template-parts/content', 'video-card');
        }
    endwhile; endif;
    ?>
</div>

<div class="videos-grid">
    <?php
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    
    // إعداد استعلام المقاطع المميزة للصفحة الرئيسية فقط
    if (is_home() && $paged == 1) {
        $sticky_posts = get_option('sticky_posts');
        if (!empty($sticky_posts)) {
            $sticky_query = new WP_Query(array(
                'post_type' => 'post',
                'posts_per_page' => -1,
                'post__in' => $sticky_posts,
                'ignore_sticky_posts' => 1
            ));
            
            if ($sticky_query->have_posts()) {
                while ($sticky_query->have_posts()) {
                    $sticky_query->the_post();
                    get_template_part('template-parts/video-card');
                }
            }
            wp_reset_postdata();
        }
    }
    
    // إعداد استعلام المقاطع العادية
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 12,
        'paged' => $paged,
        'post__not_in' => is_home() && $paged == 1 ? get_option('sticky_posts') : array()
    );
    
    $query = new WP_Query($args);
    
    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            get_template_part('template-parts/video-card');
        endwhile;
    ?>
</div>

<div class="pagination">
    <?php
    $big = 999999999;
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format' => '?paged=%#%',
        'current' => max(1, $paged),
        'total' => $query->max_num_pages,
        'prev_text' => '&#x2190; السابق',
        'next_text' => 'التالي &#x2192;',
        'mid_size' => 0,
        'type' => 'list',
        'show_all' => false
    ));
    ?>
</div>

<?php
    wp_reset_postdata();
    endif;
?>

<?php get_footer(); ?>