<template>
    <div class="card border-0 mb-3 shadow-sm" >
        <div class="card-body d-flex flex-column">
            <div class="d-flex align-items-center mb-3">
                <img class="rounded me-3 shadow" width="40" :src="status.user.avatar" :alt="status.user.name">
                <div class="">
                    <h5 class="mb-1"><a :href="status.user.link" v-text="status.user.name"></a></h5>
                    <div class="small text-muted" v-text="status.ago"></div>
                </div>
            </div>
            <p class="card-text text-secondary" v-text="status.body"></p>

        </div>
        <div class="car-footer p-2 d-flex justify-content-between align-items-center">

            <like-btn
                dusk="like-btn"
                :btn="`like-btn`"
                :url="`/statuses/${status.id}/likes`"
                :model="status"
            ></like-btn>

            <div class="text-secondary me-2">
                <i class="far fa-thumbs-o-up me-1"></i>
                <span dusk="likes-count">{{ status.likes_count }}</span>
            </div>
        </div>
        <div class="card-footer pb-0" v-if="isAutenticated || status.comments.length">
            <comment-list
                :comments="status.comments"
                :status-id="status.id"
            ></comment-list>
            <comment-form
                :status-id="status.id"
            ></comment-form>
        </div>
    </div>
</template>

<script>
    import LikeBtn from './LikeBtn.vue';
    import CommentForm from './CommentForm.vue';
    import CommentList from './CommentList.vue';

    export default {
        components: { LikeBtn, CommentForm, CommentList },
        props:{
            status: {
                type: Object,
                required: true
            }
        },
        mounted(){
            window.Echo.channel(`statuses.${this.status.id}.likes`).listen('ModelLiked', e => {
                this.status.likes_count++;
            });
            window.Echo.channel(`statuses.${this.status.id}.likes`).listen('ModelUnliked', e => {
                this.status.likes_count--;
            });
        }
    }
</script>

<style lang="scss" scoped>

</style>
