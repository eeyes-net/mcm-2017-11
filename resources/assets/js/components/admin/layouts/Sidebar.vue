<template>
    <div>
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <router-link class="nav-link must-exact" to="/admin">概况</router-link>
            </li>
            <li class="nav-item">
                <router-link class="nav-link" to="/admin/post">公告</router-link>
            </li>
            <li class="nav-item">
                <router-link class="nav-link" to="/admin/user">用户</router-link>
            </li>
            <li class="nav-item">
                <router-link class="nav-link" to="/admin/team">队伍</router-link>
            </li>
            <li class="nav-item">
                <router-link class="nav-link" to="/admin/match">竞赛</router-link>
            </li>
            <li class="nav-item">
                <router-link class="nav-link" to="/admin/recruit">招募</router-link>
            </li>
        </ul>
    </div>
</template>