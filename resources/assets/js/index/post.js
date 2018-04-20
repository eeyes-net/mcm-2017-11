function redirectToPostWithTimeout(postId) {
    setTimeout(function () {
        location.href = '/post/' + postId;
    }, 1000);
}

function getPost(postId) {
    axios.get('/api/post/' + postId)
        .then(response => {
            vmData.errors = [];
            if (response.data.data) {
                let data = response.data.data;
                vmData.post.title = data.title;
                vmData.post.created_at.str = data.created_at.str;
                vmData.post.content = data.content;
                vm.$forceUpdate();
                vmData.modalShow = true;
            } else {
                vmData.errors = response;
                redirectToPostWithTimeout(postId);
            }
        })
        .catch(error => {
            vmData.errors = error;
            redirectToPostWithTimeout(postId);
        });
}

let vmData = {
    errors: [],
    post: {
        title: '',
        created_at: {
            str: ''
        },
        content: ''
    },
    modalShow: false
};

let vm = new Vue({
    el: '#main',
    data: vmData
});

let registerLinkListener = function () {
    $('.mcm-post #pjax-container .list-group .list-group-item a').on('click', function (event) {
        event.preventDefault();
        vmData.errors = [];
        let postId = $(this).attr('data-post-id');
        getPost(postId);
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
