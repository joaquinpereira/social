<template>
    <div v-if="friendship_status === 'pending'">
        <span v-text="sender.name"></span> te ha enviado una solicitud de amistad
        <button id="accept-friendship" @click="acceptFriendshipRequest">Aceptar solicitud</button>
    </div>
    <div v-else>
        Tú y <span v-text="sender.name"></span> son amigos
    </div>
</template>

<script>
    export default {
        props:{
            sender:{
                type: Object,
                required: true
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
            acceptFriendshipRequest(){
                axios.post(`/accept-friendships/${this.sender.name}`)
                    .then(res => {
                        this.friendship_status = 'accepted';
                    })
                    .catch(err => {
                        console.log(err.response.data);
                    })
            },
        }
    }
</script>

<style scoped>

</style>
