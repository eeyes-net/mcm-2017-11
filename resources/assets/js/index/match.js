function pjaxRefresh() {
    $.pjax.reload({container: '#pjax-container'});
}

let vmData = {
    errors: [],
    match: {
        id: 0,
        title: ''
    },
    modalShow: false
};

let vm = new Vue({
    el: '#main',
    data: vmData,
    methods: {
        onApplyOk() {
            pjaxRefresh();
        }
    }
});

let registerLinkListener = function () {
    $('.mcm-match-btn-available').on('click', function () {
        vmData.match.id = parseInt($(this).attr('data-match-id'));
        vmData.match.title = $(this).attr('data-match-title');
        vmData.modalShow = true;
    });
    $('.mcm-match-btn-cancel').on('click', function () {
        if (confirm('确认取消报名 《' + $(this).attr('data-match-title') + '》')) {
            axios.post('/api/match/' + $(this).attr('data-match-id') + '/cancel').then(response => {
                if (response.data.data) {
                    alert('取消报名成功');
                    pjaxRefresh();
                } else {
                    vmData.errors = response;
                }
            }).catch(error => {
                vmData.errors = error;
            });
        }
    });
    $(document).pjax('.pagination a', '#pjax-container', {
        scrollTo: $('#main').offset().top - $('.navbar').outerHeight() - 32
    });
};
$(document).on('pjax:complete', function () {
    registerLinkListener();
});
registerLinkListener();
