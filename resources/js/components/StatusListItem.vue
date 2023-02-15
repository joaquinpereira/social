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
        mounted(){
            window.Echo.channel(`statuses.${this.status.id}.comments`).listen('CommentCreated', e => {
                this.comments.push(e.comment);
            });
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
    .avatar_comment{
        width: 32px;
        height: 32px;
    }

</style>
