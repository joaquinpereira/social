<template>
    <button
        @click="toggleFriendshipRequest"
    >
        {{ getText }}
    </button>
</template>

<script>
    export default{
        props:{
            recipient:{
                type: Object,
                required: true,
            }
        },
        data(){
            return {
                friendship_status: '',
            }
        },
        mounted() {
            axios.get(`/friendships/${this.recipient.name}`)
                .then(res => {
                    this.friendship_status = res.data.friendship_status;
                })
                .catch(err => {
                    console.log(err.response.data);
                })
        },
        methods:{
            toggleFriendshipRequest(){
                this.redirectIfGuest();
                let method = this.getMethod();
                axios[method](`friendships/${this.recipient.name}`)
                    .then(res => {
                        this.friendship_status = res.data.friendship_status;
                    })
                    .catch(err => {
                        console.log(err.response.data);
                    })
            },
            getMethod(){
                if(this.friendship_status === 'pending' || this.friendship_status === 'accepted')
                    return 'delete';
                return 'post';
            }
        },
        computed:{
            getText(){
                if(this.friendship_status === 'pending')
                    return 'Cancelar solicitud';
                if(this.friendship_status === 'accepted')
                    return 'Eliminar de mis amigos';
                if(this.friendship_status === 'denied')
                    return 'Solicitud denegada';
                return 'Solicitar amistad';
            }
        }
    }
</script>

<style scoped>

</style>
