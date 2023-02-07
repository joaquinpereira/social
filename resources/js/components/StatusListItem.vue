<template>
    <div class="card border-0 mb-3 shadow-sm" >
        <div class="card-body d-flex flex-column">
            <div class="d-flex align-items-center mb-3">
                <img class="rounded me-3 shadow" width="40" src="http://social/avatar.png" alt="">
                <div class="">
                    <h5 class="mb-1" v-text="status.user_name"></h5>
                    <div class="small text-muted" v-text="status.ago"></div>
                </div>
            </div>
            <p class="card-text text-secondary" v-text="status.body"></p>

        </div>
        <div class="car-footer p-2 d-flex justify-content-between align-items-center">

            <like-btn
                dusk="like-btn"
                :url="`/statuses/${status.id}/likes`"
                :model="status"
                :key="status.id"
            ></like-btn>

            <div class="text-secondary me-2">
                <i class="far fa-thumbs-o-up me-1"></i>
                <span dusk="likes-count">{{ status.likes_count }}</span>
            </div>
        </div>
        <div class="card-footer">
            <div v-for="comment in comments" class="mb-3 d-flex justify-content-between align-items-center">
                <img class="avatar_comment rounded shadow-sm float-left me-2" :src="comment.user_avatar" :alt="comment.user_name"/>

                <div class="card border-0 shadow-sm info_comment">
                    <div class="card-body p-2 text-secondary">
                        <a href="#"><strong>{{ comment.user_name }}</strong></a>
                        {{ comment.body }}
                    </div>
                </div>
                <span dusk="comment-likes-count">{{comment.likes_count}}</span>
                <like-btn
                    dusk="comment-like-btn"
                    :url="`/comments/${comment.id}/likes`"
                    :model="comment"
                    :key="comment.id"
                ></like-btn>
            </div>
            <form v-if="isAutenticated" @submit.prevent="addComment">
                <div class="d-flex align-items-center">

                    <img class="avatar_comment rounded shadow-sm float-left me-2" src="http://social/avatar.png" :alt="currentUser.name"/>

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
    export default {
        components: { LikeBtn },
        props:{
            status: {
                type: Object,
                required: true
            }
        },
        data() {
            return {
                newComment: '',
                comments: this.status.comments
            }
        },
        methods:{
            addComment(){
                axios.post(`/statuses/${this.status.id}/comments`, {body: this.newComment})
                    .then(res => {
                        this.newComment = '';
                        this.comments.push(res.data.data);
                    })
                    .catch(err => {
                        console.log(err.response.data)
                    })
            }
        }
    }
</script>

<style lang="scss" scoped>

</style><style lang="scss" scoped>
    .avatar_comment{
        width: 32px;
    }

    .info_comment{
        width: 100%;
    }
</style>
