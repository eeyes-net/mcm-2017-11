<template>
    <b-modal :title="post.title" v-model="show" size="lg" hide-footer :no-enforce-focus="true">
        <slot slot="modal-header">
            <div class="modal-title">
                <h5>{{ post.title }}</h5>
                <span class="post-date">{{ post.created_at.str }}</span>
            </div>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </slot>
        <div class="post-content" v-html="post.content"></div>
    </b-modal>
</template>

<script>
    export default {
        props: [
            'post',
            'show'
        ]
    };
</script>
