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
                :status="status"
                :key="status.id"
            ></like-btn>

            <div class="text-secondary me-2">
                <i class="far fa-thumbs-o-up me-1"></i>
                <span dusk="likes-count">{{ status.likes_count }}</span>
            </div>

            <form @submit.prevent="addComment">
                <textarea name="comment" v-model="newComment"></textarea>
                <button dusk="comment-btn">Enviar</button>
            </form>

            <div v-for="comment in comments">{{ comment.body }}</div>
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
            }
        }
    }
</script>
