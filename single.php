<?php get_header(); ?>
<main class="single-video-container">
  <div class="video-content">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <article class="video-article">
        <div class="video-player">
          <?php
          // استخراج رابط اليوتيوب من المحتوى
          $content = get_the_content();
          $youtube_url = '';
          if (preg_match('/(https?:\/\/)?(www\.)?(youtube\.com\/watch\?v=|youtu\.be\/)([^& \n<]+)/', $content, $matches)) {
              $youtube_id = $matches[4];
              echo '<iframe src="https://www.youtube.com/embed/' . esc_attr($youtube_id) . '" frameborder="0" allowfullscreen></iframe>';
          } else {
              // إذا لم يتم العثور على رابط يوتيوب، عرض الصورة المصغرة
              if (has_post_thumbnail()) {
                  the_post_thumbnail('large');
              }
          }
          ?>
        </div>
        
        <h1 class="video-title"><?php the_title(); ?></h1>
        
        <div class="video-meta-info">
          <div class="video-stats">
            <span class="views-count"><?php echo get_post_meta(get_the_ID(), 'views', true) ?: '0'; ?> مشاهدة</span>
            <span class="publish-date"><?php echo get_the_date(); ?></span>
          </div>
          <div class="video-actions">
            <button class="like-button">👍 <?php echo get_post_meta(get_the_ID(), 'likes', true) ?: '0'; ?></button>
            <button class="share-button">مشاركة</button>
          </div>
        </div>

        <div class="video-description">
          <?php
          // عرض المحتوى بدون رابط اليوتيوب
          $content_without_youtube = preg_replace('/(https?:\/\/)?(www\.)?(youtube\.com\/watch\?v=|youtu\.be\/)[^& \n<]+/', '', $content);
          echo wpautop($content_without_youtube);
          ?>
        </div>

        <?php
        $tags = get_the_tags();
        if ($tags) : ?>
          <div class="video-tags">
            <?php foreach ($tags as $tag) : ?>
              <a href="<?php echo get_tag_link($tag->term_id); ?>" class="tag">
                <?php echo $tag->name; ?>
              </a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </article>
    <?php endwhile; endif; ?>
  </div>
  
  <div class="related-videos">
    <h3>فيديوهات ذات صلة</h3>
    <?php
    $related = new WP_Query(array(
      'post_type' => 'post',
      'posts_per_page' => 4,
      'post__not_in' => array(get_the_ID()),
      'category__in' => wp_get_post_categories(get_the_ID())
    ));
    if ($related->have_posts()) :
      while ($related->have_posts()) : $related->the_post();
    ?>
      <div class="related-video-card">
        <a href="<?php the_permalink(); ?>">
          <?php if (has_post_thumbnail()) : ?>
            <div class="related-thumbnail">
              <?php the_post_thumbnail('medium'); ?>
            </div>
          <?php endif; ?>
          <h4><?php the_title(); ?></h4>
        </a>
      </div>
    <?php
      endwhile;
    endif;
    wp_reset_postdata();
    ?>
  </div>
</main>
<?php get_footer(); ?>