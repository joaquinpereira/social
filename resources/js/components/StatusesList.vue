<template>
    <div>
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
                <button v-if="status.is_liked" dusk="unlike-btn" @click="unlike(status)">TE GUSTA</button>
                <button v-else dusk="like-btn" @click="like(status)">ME GUSTA</button>
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
                })
            },
            unlike(status){
                axios.delete(`/statuses/${status.id}/likes`)
                .then(res =>{
                    status.is_liked = false;
                })
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>
