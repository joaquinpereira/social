<template>
    <div class="d-flex justify-content-between bg-light p-3 rounded mb-3 shadow-sm">
        <div>
            <div v-if="friendship_status === 'pending'">
                <span v-text="sender.name"></span> te ha enviado una solicitud de amistad
            </div>
            <div v-if="friendship_status === 'accepted'">
                Tú y <span v-text="sender.name"></span> son amigos
            </div>
            <div v-if="friendship_status === 'denied'">
                Solicitud denegada de <span v-text="sender.name"></span>
            </div>
            <div v-if="friendship_status === 'deleted'">
                Solicitud eliminada de <span v-text="sender.name"></span>
            </div>
        </div>
        <div>
            <button class="btn btn-sm btn-primary me-1" v-if="friendship_status === 'pending'" id="accept-friendship" @click="acceptFriendshipRequest">Aceptar solicitud</button>
            <button class="btn btn-sm btn-warning me-1" v-if="friendship_status === 'pending'" id="deny-friendship" @click="denyFriendshipRequest">Denegar solicitud</button>
            <button class="btn btn-sm btn-danger me-1" v-if="friendship_status !== 'deleted'" id="delete-friendship" @click="deleteFriendship">Eliminar</button>
        </div>
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
            },
            deleteFriendship(){
                axios.delete(`/friendships/${this.sender.name}`)
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
