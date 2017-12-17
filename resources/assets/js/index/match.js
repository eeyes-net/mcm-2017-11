jQuery(function ($) {
    if (!$('.mcm-match')) {
        return;
    }
    var registerLinkListener = function () {
        $(document).pjax('.pagination a', '#pjax-container', {
            scrollTo: $('#main').offset().top - $('.navbar').height()
        });
        $(document).on('pjax:complete', function () {
            registerLinkListener();
        });
    };
    registerLinkListener();
});