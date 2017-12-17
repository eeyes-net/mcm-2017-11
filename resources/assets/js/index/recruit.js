jQuery(function ($) {
    if (!$('.mcm-recruit')) {
        return;
    }
    var registerLinkListener = function () {
        $(document).pjax('.dropdown a, .pagination a', '#pjax-container', {
            scrollTo: $('#main').offset().top - $('.navbar').height()
        });
        $(document).on('pjax:complete', function () {
            registerLinkListener();
        });
    };
    registerLinkListener();

    let $modal = $('.mcm-recruit .modal');
    $modal.find('.alert').hide();

    var getTeams = (function () {
        let loaded = false;
        let teams = [];
        return function (callback) {
            if (loaded) {
                callback(teams);
            } else {
                axios.get('/api/team')
                    .then(response => {
                        loaded = true;
                        teams = response.data;
                        callback(teams);
                    })
                    .catch(error => {
                        loaded = true;
                        teams = error.response.data;
                        callback(teams);
                    });
            }
        };
    })();

    $('.create-recruit').on('click', function (event) {
        $modal.modal('show');
        let $select = $modal.find('form select[name="team_id"]');
        getTeams(function (teams) {
            if (teams.message) {
                $modal.find('.alert').text(teams.message);
                $modal.find('.alert').show();
            } else {
                $select.empty();
                for (let i = 0; i < teams.length; ++i) {
                    let team = teams[i];
                    let $option = $('<option></option>');
                    $option.val(team.id);
                    $option.text('队伍编号：' + team.team_id + '（' + team.users.map(user => user.name).join() + '）');
                    if (team.users.length >= 3) {
                        $option.prop('disabled', true);
                        $option.text($option.text() + '已满3人');
                    }
                    $select.append($option);
                }
            }
        });
    });

    $modal.find('form').on('submit', function (event) {
        event.preventDefault();
        let $this = $(this);
        let tags = [];
        let $tags = $this.find('[name="tags"]');
        for (let i = 0; i < $tags.length; ++i) {
            let $tag = $($tags[i]);
            if ($tag.is(':checked')) {
                tags.push($tag.val());
            }
        }
        $modal.find('.alert').hide();
        axios.post('/api/recruit', {
            team_id: $this.find('[name="team_id"]').val(),
            tags: tags,
            description: $this.find('[name="description"]').val(),
            contact: $this.find('[name="contact"]').val()
        }).then(response => {
            if (response.data.message) {
                $modal.find('.alert').text(response.data.message);
                $modal.find('.alert').show();
            } else {
                $modal.modal('hide');
            }
        }).catch(error => {
            $modal.find('.alert').text(error.response.data.message);
            $modal.find('.alert').show();
        });
        return true;
    });
});