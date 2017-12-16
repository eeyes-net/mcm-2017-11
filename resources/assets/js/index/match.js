jQuery(function ($) {
    if (!$('.mcm-match')) {
        return;
    }
    var registerLinkListener = function () {
        $(document).pjax('.pagination a', '#pjax-container', {
            scrollTo: false
        });
        $(document).on('pjax:complete', function () {
            registerLinkListener();
        });
    };
    registerLinkListener();
});