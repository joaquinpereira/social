<template>
    <div>
        <form v-if="isAutenticated"  @submit.prevent="submit">
            <div class="card-body">
                <textarea v-model="body"
                    class="form-control border-0 bg-light" name="body"
                    :placeholder="`¿Qué estas pensando ${currentUser.name}?`"></textarea>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary" type="submit" id="create-status">
                    <i class="fa fa-paper-plane mr-1"></i>
                    Publicar
                </button>
            </div>
        </form>
        <div v-else class="card-body">
            <a href="/login">Debes hacer login.</a>
        </div>
    </div>
</template>

<script>

    export default {
        data(){
            return{
                body: ''
            }
        },
        methods:{
            submit(){
                axios.post('/statuses',{body: this.body})
                    .then(res => {
                        this.emitter.emit("status-created", res.data.data);
                        this.body = '';
                    })
                    .catch(err => {
                        console.log(err.response.data)
                    })
            }
        }

    }
</script>

<style lang="scss" scoped>

</style>
