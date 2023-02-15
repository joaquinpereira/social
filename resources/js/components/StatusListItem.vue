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
        <div class="card-footer">
            <comment-list
                :comments="status.comments"
                :status-id="status.id"
            ></comment-list>
            <form v-if="isAutenticated" @submit.prevent="addComment">
                <div class="d-flex align-items-center">

                    <img class="avatar_comment rounded shadow-sm float-left me-2" src='http://social/avatar.png' :alt="currentUser.name"/>

                    <textarea
                        v-model="newComment"
                        class="form-control border-0 me-2 shadow-sm"
                        name="comment"
                        placeholder="Escribe un comentario..."
                        rows="1"
                        required
                    ></textarea>

                    <button class="btn btn-primary" dusk="comment-btn">Enviar</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import LikeBtn from './LikeBtn.vue';
    import CommentList from './CommentList.vue';

    export default {
        components: { LikeBtn, CommentList },
        props:{
            status: {
                type: Object,
                required: true
            }
        },
        data() {
            return {
                newComment: '',
            }
        },
        methods:{
            addComment(){
                axios.post(`/statuses/${this.status.id}/comments`, {body: this.newComment})
                    .then(res => {
                        this.newComment = '';
                        this.emitter.emit(`statuses.${this.status.id}.comments`, res.data.data);
                    })
                    .catch(err => {
                        console.log(err.response.data)
                    })
            }
        }
    }
</script>

<style lang="scss" scoped>
    .avatar_comment{
        width: 32px;
        height: 32px;
    }

</style>
