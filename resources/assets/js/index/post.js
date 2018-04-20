let vmData = {
    postId: 0,
    modalShow: false
};

let vm = new Vue({
    el: '#main',
    data: vmData,
    method: {
        onError() {
            setTimeout(function () {
                location.href = '/post/' + postId;
            }, 1000);
        }
    }
});

let registerLinkListener = function () {
    $('.mcm-post #pjax-container .list-group .list-group-item a').on('click', function (event) {
        event.preventDefault();
        vmData.postId = parseInt($(this).attr('data-post-id'));
        vmData.modalShow = true;
        return true;
    });
    $(document).pjax('.pagination a', '#pjax-container', {
        scrollTo: $('#main').offset().top - $('.navbar').outerHeight() - 32
    });
};
$(document).on('pjax:complete', function () {
    registerLinkListener();
});
registerLinkListener();
