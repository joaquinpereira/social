<template>
    <div @click="redirectIfGuest">
        <div class="card border-0 mb-3 shadow-sm" v-for="status in statuses">
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
            <div class="car-footer p-2">
                <button
                    v-if="status.is_liked"
                    @click="unlike(status)"
                    class="btn btn-link btn-sm"
                    dusk="unlike-btn" >
                    <i class="fa fa-thumbs-up text-primary mr-1"></i>
                    <strong>TE GUSTA</strong>
                </button>
                <button
                    v-else
                    @click="like(status)"
                    class="btn btn-link btn-sm"
                    dusk="like-btn">
                    <i class="far fa-thumbs-o-up text-primary mr-1"></i>
                    ME GUSTA
                </button>
                <span dusk="likes-count">{{ status.likes_count }}</span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                statuses: []
            }
        },
        mounted(){
            axios.get('/statuses')
                .then(res =>{
                    this.statuses = res.data.data;
                })
                .catch(err => {
                    console.log(err.response.data);
                })

            this.emitter.on('status-created', status => {
                this.statuses.unshift(status);
            });

        },
        methods: {
            like(status){
                axios.post(`/statuses/${status.id}/likes`)
                .then(res =>{
                    status.is_liked = true;
                    status.likes_count++;
                })
            },
            unlike(status){
                axios.delete(`/statuses/${status.id}/likes`)
                .then(res =>{
                    status.is_liked = false;
                    status.likes_count--;
                })
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>
