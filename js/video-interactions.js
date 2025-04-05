jQuery(document).ready(function($) {
    // معالجة الإعجابات
    $('.like-button').on('click', function() {
        var button = $(this);
        var postId = button.closest('article').data('post-id');
        
        $.ajax({
            url: videoAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'handle_like',
                post_id: postId,
                nonce: videoAjax.nonce
            },
            success: function(response) {
                if (response.success) {
                    button.html('👍 ' + response.data.likes);
                    button.addClass('liked');
                }
            }
        });
    });

    // معالجة المشاركة
    $('.share-button').on('click', function() {
        var url = window.location.href;
        var title = $('.video-title').text();
        
        if (navigator.share) {
            navigator.share({
                title: title,
                url: url
            }).catch(console.error);
        } else {
            // نسخ الرابط إلى الحافظة
            var tempInput = document.createElement('input');
            document.body.appendChild(tempInput);
            tempInput.value = url;
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            
            // إظهار رسالة تأكيد
            var button = $(this);
            var originalText = button.text();
            button.text('تم النسخ!');
            setTimeout(function() {
                button.text(originalText);
            }, 2000);
        }
    });
}); 