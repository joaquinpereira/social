<template>
    <comment-list-item
        v-for="comment in comments"
        :comment="comment"
        :key="comment.id"
        class="mb-3"
    ></comment-list-item>
</template>

<script>
    import CommentListItem from './CommentListItem.vue';

    export default {
        components: {  CommentListItem },
        props:{
            comments:{
                type: Array,
                required: true
            },
            statusId: {
                type: Number,
                required: true
            }
        },
        mounted(){
            window.Echo.channel(`statuses.${this.statusId}.comments`).listen('CommentCreated', e => {
                this.comments.push(e.comment);
            });

            this.emitter.on(`statuses.${this.statusId}.comments`, comment => {
                this.comments.push(comment);
            });
        },
    }
</script>
