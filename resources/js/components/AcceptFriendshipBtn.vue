<template>
    <div v-if="friendship_status === 'pending'">
        <span v-text="sender.name"></span> te ha enviado una solicitud de amistad
        <button id="accept-friendship" @click="acceptFriendshipRequest">Aceptar solicitud</button>
        <button id="deny-friendship" @click="denyFriendshipRequest">Denegar solicitud</button>
    </div>
    <div v-else-if="friendship_status === 'accepted'">
        Tú y <span v-text="sender.name"></span> son amigos
    </div>
    <div v-else-if="friendship_status === 'denied'">
        Solicitud denegada de <span v-text="sender.name"></span>
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
                        this.friendship_status = res.data.friendship_status;
                    })
                    .catch(err => {
                        console.log(err.response.data);
                    })
            },
            denyFriendshipRequest(){
                axios.delete(`/accept-friendships/${this.sender.name}`)
                    .then(res => {
                        this.friendship_status = res.data.friendship_status;
                    })
                    .catch(err => {
                        console.log(err.response.data);
                    })
            }
        }
    }
</script>

<style scoped>

</style>
