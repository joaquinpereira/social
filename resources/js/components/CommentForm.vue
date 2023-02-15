<template>
    <div v-if="isAutenticated" class="mb-3">
        <form @submit.prevent="addComment">
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
    <div v-else class="text-secondary text-center mb-3">
        Debes <a href="/login">autenticarte</a> para poder comentar
    </div>
</template>

<script>
    export default {
        props:{
            statusId: {
                type: Number,
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
                axios.post(`/statuses/${this.statusId}/comments`, {body: this.newComment})
                    .then(res => {
                        this.newComment = '';
                        this.emitter.emit(`statuses.${this.statusId}.comments`, res.data.data);
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
