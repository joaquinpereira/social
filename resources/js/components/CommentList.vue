<template>
    <div>
        <div v-for="comment in comments" class="mb-3">
            <div class="d-flex">
                <img class="avatar_comment rounded shadow-sm me-2" :src="comment.user.avatar" :alt="comment.user.name"/>
                <div class="flex-grow-1">
                    <div class="card border-0 shadow-sm mb-1">
                        <div class="card-body p-2 text-secondary">
                            <a :href="comment.user.link"><strong>{{ comment.user.name }}</strong></a>
                            {{ comment.body }}
                        </div>
                    </div>
                    <small
                        class="badge badge-pill bg-primary py-1 px-2 mt-1 pull-right rounded-4"
                        dusk="comment-likes-count" id="comment-likes-count">
                            <i class="fa fa-thumbs-up"></i>
                            {{ comment.likes_count }}
                    </small>

                    <like-btn
                        dusk="comment-like-btn"
                        :btn="`comment-like-btn`"
                        :url="`/comments/${comment.id}/likes`"
                        :model="comment"
                        class="comments-like-btn"
                    ></like-btn>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import LikeBtn from './LikeBtn.vue';

    export default {
        components: { LikeBtn },
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

<style scoped>

    .avatar_comment{
        width: 32px;
        height: 32px;
    }

</style>
