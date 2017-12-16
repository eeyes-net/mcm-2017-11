jQuery(function ($) {
    if (!$('.mcm-post')) {
        return;
    }
    var $modal = $('.mcm-post .modal');
    var registerLinkListener = function () {
        $('.mcm-post .panel .list-group .list-group-item .title a').on('click', function (event) {
            event.preventDefault();
            axios.get('/api/post/' + $(this).attr('data-post-id'))
                .then(response => {
                    let post = response.data;
                    $modal.find('.modal-title').text(post.title);
                    $modal.find('.post-date').text(post.created_at);
                    $modal.find('.post-content').html(post.content);
                    $modal.modal('show');
                });
            return true;
        });
        $(document).pjax('.pagination a', '#pjax-container', {
            scrollTo: false
        });
        $(document).on('pjax:complete', function () {
            registerLinkListener();
        });
    };
    registerLinkListener();
});