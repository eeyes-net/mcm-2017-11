jQuery(function ($) {
    if (!$('.mcm-match')) {
        return;
    }

    let $modal = $('.mcm-match .modal');
    let $alert = $modal.find('.alert');
    $alert.hide();
    let $form = $modal.find('form');
    let $matchId = $form.find('input[name="match_id"]');
    let $select = $form.find('select[name="team_id"]');

    let registerLinkListener = function () {
        $(document).pjax('.pagination a', '#pjax-container', {
            scrollTo: $('#main').offset().top - $('.navbar').height() - 30
        });
        $('.mcm-match .panel-body .btn').on('click', function () {
            $modal.find('.modal-title').text($(this).closest('.panel').find('.panel-title').text());
            $matchId.val($(this).attr('data-match-id'));
        });
        $(document).on('pjax:complete', function () {
            registerLinkListener();
        });
    };
    registerLinkListener();

    let getTeams = (function () {
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

    $modal.on('show.bs.modal', function (event) {
        $alert.hide();
        $modal.find();
        getTeams(function (teams) {
            if (teams.message) {
                $alert.text(teams.message);
                $alert.show();
            } else {
                $select.empty();
                for (let i = 0; i < teams.length; ++i) {
                    let team = teams[i];
                    let $option = $('<option></option>');
                    $option.val(team.id);
                    $option.text('队伍编号：' + team.team_id + '（' + team.users.map(user => user.name).join() + '）');
                    $select.append($option);
                }
                let teams_key_by_id = _.keyBy(teams, 'id');
                const position_map = {
                    leader: '队长',
                    member: '队员'
                };
                $select.on('change', function () {
                    let team = teams_key_by_id[this.value];
                    let $team_info = $('.mcm-match-modal-team-info');
                    $team_info.empty();
                    for (let i = 0; i < team.users.length; ++i) {
                        let user = team.users[i];
                        let $tr = $('<tr></tr>');
                        $tr.append($('<td></td>').text(position_map[user.position]));
                        $tr.append($('<td></td>').text(user.name));
                        $tr.append($('<td></td>').text(user.stu_id));
                        $team_info.append($tr);
                    }
                });
                $select.trigger('change');
            }
        });
    });

    $form.on('submit', function () {
        event.preventDefault();
        let $this = $(this);
        $alert.hide();
        axios.post('/api/match/' + $matchId.val() + '/apply', {
            team_id: $this.find('[name="team_id"]').val()
        }).then(response => {
            if (response.data.message) {
                $alert.text(response.data.message);
                $alert.show();
            } else {
                $modal.modal('hide');
                $.pjax.reload({container: '#pjax-container'});
            }
        }).catch(error => {
            $alert.text(error.response.data.message);
            $alert.show();
        });
        return true;
    });
});