<template>
    <b-card>
        <h3 class="card-title">队伍编号：{{ recruit.team.number }}
            <span v-for="tag in recruit.tags"><span class="badge badge-info">{{ tag }}</span>&nbsp;</span>
            <span class="badge badge-light float-right mcm-recruit-card-created-at" :title="recruit.created_at.str">{{ recruit.created_at.diff }}</span>
        </h3>
        <div class="mcm-recruit-card-content">
            <div class="d-md-flex">
                <div class="flex-shrink-0">当前队员</div>
                <div>{{ recruit.members }}</div>
            </div>
            <div class="d-md-flex">
                <div class="flex-shrink-0">队伍描述</div>
                <div>{{ recruit.description }}</div>
            </div>
            <div class="d-md-flex">
                <div class="flex-shrink-0">联系方式</div>
                <div>{{ recruit.contact }}</div>
            </div>
        </div>
        <div class="home-recruit-edit-cover">
            <button class="home-recruit-btn-edit" @click="$emit('edit', recruit)">编辑</button>
            <button class="home-recruit-btn-delete" @click="$emit('destroy', recruit)">删除</button>
        </div>
    </b-card>
</template>

<script>
    export default {
        props: {
            recruit: {
                default() {
                    return {
                        id: 0,
                        team: {
                            number: 0
                        },
                        tags: [],
                        created_at: {
                            str: '',
                            diff: ''
                        },
                        members: '',
                        description: '',
                        contact: ''
                    };
                }
            }
        }
    };
</script>