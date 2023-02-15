<template>
    <div @click="redirectIfGuest">
        <status-list-item
            v-for="status in statuses"
            :status="status"
            :key="status.id"
        ></status-list-item>
    </div>
</template>

<script>
import StatusListItem from './StatusListItem.vue';
    export default {
        components:{ StatusListItem },
        props: {
            url: String
        },
        data(){
            return {
                statuses: []
            }
        },
        mounted(){
            axios.get(this.getUrl)
                .then(res =>{
                    this.statuses = res.data.data;
                })
                .catch(err => {
                    console.log(err.response.data);
                })

            this.emitter.on('status-created', status => {
                this.statuses.unshift(status);
            });

            window.Echo.channel('statuses').listen('StatusCreated', e => {
                this.statuses.unshift(e.status);
            });

        },
        computed:{
            getUrl(){
                return this.url ? this.url : '/statuses';
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>
