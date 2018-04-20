function getQueryVars(name) {
    let values = location.search.substr(1).split('&');
    for (let i = 0; i < values.length; ++i) {
        let pair = values[i].split('=', 2);
        if (pair[0] === name) {
            return decodeURIComponent(pair[1]);
        }
    }
    return null;
}

function pjaxRefresh() {
    $.pjax.reload({container: '#pjax-container'});
}

let vmData = {
    errors: [],
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
    $(document).pjax('.mcm-recruit .mcm-recruit-controls .dropdown-item, .mcm-recruit .pagination a', '#pjax-container', {
        scrollTo: $('#main').offset().top - $('.navbar').outerHeight() - 32
    });
    $('.mcm-recruit-btn-dropdown').text(getQueryVars('tags') || '全部');
};
$(document).on('pjax:complete', function () {
    registerLinkListener();
});
registerLinkListener();

$('.mcm-recruit-btn-create').on('click', function () {
    vmData.modalShow = true;
});