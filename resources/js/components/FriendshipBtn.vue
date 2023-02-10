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
            },
            friendshipStatus:{
                type: String,
                required: true
            }
        },
        data(){
            return {
                friendship_status: this.friendshipStatus,
            }
        },
        methods:{
            toggleFriendshipRequest(){
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
                if(this.friendship_status === 'pending')
                    return 'delete';
                return 'post';
            }
        },
        computed:{
            getText(){
                if(this.friendship_status === 'pending')
                    return 'Cancelar solicitud';
                return 'Solicitar amistad';
            }
        }
    }
</script>

<style scoped>

</style>
