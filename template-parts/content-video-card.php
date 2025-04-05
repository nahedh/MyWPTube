<?php
$is_sticky = is_sticky();
$card_class = 'video-card' . ($is_sticky ? ' sticky' : '');
?>
<article class="<?php echo esc_attr($card_class); ?>">
    <a href="<?php the_permalink(); ?>">
        <div class="video-thumbnail">
            <?php 
            if (has_post_thumbnail()) {
                the_post_thumbnail('medium_large');
            } else {
                echo '<img src="' . get_template_directory_uri() . '/images/placeholder.jpg" alt="صورة افتراضية">';
            }
            
            // عرض مدة الفيديو
            $duration = get_post_meta(get_the_ID(), 'video_duration', true);
            if (!$duration) {
                $duration = '00:00';
            }
            echo '<span class="video-duration">' . esc_html($duration) . '</span>';
            ?>
        </div>
        <div class="video-meta">
            <h2 class="video-title"><?php the_title(); ?></h2>
            <div class="video-info">
                <?php
                $categories = get_the_category();
                if (!empty($categories)) {
                    echo '<div class="video-category">';
                    foreach($categories as $category) {
                        if (!empty($category->name)) {
                            echo '<a href="' . esc_url(get_category_link($category->term_id)) . '">' 
                                 . esc_html($category->name) . '</a>';
                        }
                    }
                    echo '</div>';
                }
                ?>
                <div class="video-stats">
                    <span class="views"><?php echo number_format(get_post_meta(get_the_ID(), 'views', true) ?: 0); ?> مشاهدة</span>
                    <span class="separator">•</span>
                    <span class="date">منذ <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')); ?></span>
                </div>
            </div>
        </div>
    </a>
</article> 