jQuery(function ($) {
    if (!$('.mcm-recruit')) {
        return;
    }
    var registerLinkListener = function () {
        $(document).pjax('.dropdown a, .pagination a', '#pjax-container', {
            scrollTo: false
        });
        $(document).on('pjax:complete', function () {
            registerLinkListener();
        });
    };
    registerLinkListener();
});