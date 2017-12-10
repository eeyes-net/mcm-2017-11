<template>
    <div>
        <b-form @submit.prevent="$emit('submit', form)">
            <b-form-group label="招募标签" horizontal :label-cols="3">
                <b-form-input type="text" v-model="form.tags" required></b-form-input>
            </b-form-group>
            <b-form-group label="队伍成员" horizontal :label-cols="3">
                <b-form-input type="text" v-model="form.members" required></b-form-input>
            </b-form-group>
            <b-form-group label="队伍描述" horizontal :label-cols="3">
                <b-form-textarea type="text" :rows="3" :max-rows="6"
                                 v-model="form.description" required></b-form-textarea>
            </b-form-group>
            <b-form-group label="队伍联系方式" horizontal :label-cols="3">
                <b-form-input type="text" v-model="form.contact" required></b-form-input>
            </b-form-group>
            <b-form-group label="发布时间" horizontal :label-cols="3">
                <b-form-input type="text" v-model="form.created_at"></b-form-input>
            </b-form-group>
            <b-button type="submit" variant="primary">更新</b-button>
        </b-form>
    </div>
</template>

<script>
    export default {
        props: [
            'recruit'
        ],
        data() {
            return {
                form: {
                    id: 0,
                    tags: '',
                    members: '',
                    description: '',
                    contact: '',
                    created_at: ''
                }
            }
        },
        watch: {
            recruit() {
                this.form.id = this.recruit.id;
                this.form.tags = this.recruit.tags;
                this.form.members = this.recruit.members;
                this.form.description = this.recruit.description;
                this.form.contact = this.recruit.contact;
                this.form.created_at = this.recruit.created_at;
            }
        }
    }
</script>