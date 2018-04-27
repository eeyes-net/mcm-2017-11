<?php

return [
    'recruit_tags' => [
        '招募建模',
        '招募算法',
        '招募写作',
    ],
    'team_users_count_limit' => 3,
    'team_recruits_count_limit' => 1,
    'team_number_format' => '%d',
    'user_teams_count_limit' => 5,
    'log_visit' => (bool)env('MCM_LOG_VISIT', true),
    'disallow_leader_leave_team' => false,
];
