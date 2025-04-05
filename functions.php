<?php
function nahedhtech_theme_setup() {
  add_theme_support('post-thumbnails');
  add_theme_support('title-tag');
}
add_action('after_setup_theme', 'nahedhtech_theme_setup');

function nahedhtech_enqueue_styles() {
  wp_enqueue_style('nahedhtech-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'nahedhtech_enqueue_styles');

// إضافة حقول مخصصة
function add_video_meta_boxes() {
    add_meta_box(
        'video_stats',
        'إحصائيات الفيديو',
        'video_stats_callback',
        'post',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_video_meta_boxes');

function video_stats_callback($post) {
    $views = get_post_meta($post->ID, 'views', true);
    $likes = get_post_meta($post->ID, 'likes', true);
    ?>
    <p>
        <label for="views">عدد المشاهدات:</label>
        <input type="number" id="views" name="views" value="<?php echo esc_attr($views); ?>" />
    </p>
    <p>
        <label for="likes">عدد الإعجابات:</label>
        <input type="number" id="likes" name="likes" value="<?php echo esc_attr($likes); ?>" />
    </p>
    <?php
}

function save_video_meta($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['views'])) {
        update_post_meta($post_id, 'views', sanitize_text_field($_POST['views']));
    }
    if (isset($_POST['likes'])) {
        update_post_meta($post_id, 'likes', sanitize_text_field($_POST['likes']));
    }
}
add_action('save_post', 'save_video_meta');

// إضافة JavaScript للتعامل مع الإعجابات
function add_video_scripts() {
    wp_enqueue_script('video-interactions', get_template_directory_uri() . '/js/video-interactions.js', array('jquery'), '1.0', true);
    wp_localize_script('video-interactions', 'videoAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('video_interaction_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'add_video_scripts');

// معالجة الإعجابات عبر AJAX
function handle_like_action() {
    check_ajax_referer('video_interaction_nonce', 'nonce');
    
    $post_id = intval($_POST['post_id']);
    $current_likes = intval(get_post_meta($post_id, 'likes', true));
    $new_likes = $current_likes + 1;
    
    update_post_meta($post_id, 'likes', $new_likes);
    
    wp_send_json_success(array('likes' => $new_likes));
}
add_action('wp_ajax_handle_like', 'handle_like_action');
add_action('wp_ajax_nopriv_handle_like', 'handle_like_action');

// زيادة عدد المشاهدات
function increment_views() {
    if (is_single()) {
        $post_id = get_the_ID();
        $views = intval(get_post_meta($post_id, 'views', true));
        update_post_meta($post_id, 'views', $views + 1);
    }
}
add_action('wp_head', 'increment_views');

// إضافة حقل مدة الفيديو
function add_video_duration_meta_box() {
    add_meta_box(
        'video_duration',
        'مدة الفيديو',
        'video_duration_callback',
        'post',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'add_video_duration_meta_box');

function video_duration_callback($post) {
    $duration = get_post_meta($post->ID, 'video_duration', true);
    ?>
    <p>
        <label for="video_duration">المدة (مثال: 10:30):</label>
        <input type="text" 
               id="video_duration" 
               name="video_duration" 
               value="<?php echo esc_attr($duration); ?>" 
               pattern="^(?:(?:([01]?\d|2[0-3]):)?([0-5]?\d):)?([0-5]?\d)$"
               placeholder="00:00"
               style="direction: ltr; text-align: center; width: 100%;"
        />
    </p>
    <?php
}

function save_video_duration($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['video_duration'])) {
        $duration = sanitize_text_field($_POST['video_duration']);
        // تحقق من صحة تنسيق المدة
        if (preg_match('/^(?:(?:([01]?\d|2[0-3]):)?([0-5]?\d):)?([0-5]?\d)$/', $duration)) {
            update_post_meta($post_id, 'video_duration', $duration);
        }
    }
}
add_action('save_post', 'save_video_duration');
?>