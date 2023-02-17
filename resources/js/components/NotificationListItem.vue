<template>
    <div class="dropdown-item d-flex align-items-center">
        <a
            :dusk="notification.id"
            :href="notification.data.link"
            class="dropdown-item">
            {{ notification.data.message }}
        </a>

        <button v-if="isRead"
            @click.stop="markAsUnread"
            :dusk="`mark-as-unread-${notification.id}`"
            class="btn btn-link"
            >
            <i class="fa fa-check-circle"></i>
            <span class="position-absolute bg-dark text-white ms-2 py-1 px-2 rounded">Marcar como No leída</span>
        </button>

        <button v-else
            @click.stop="markAsRead"
            :dusk="`mark-as-read-${notification.id}`"
            class="btn btn-link"
            >
            <i class="fa fa-circle-o"></i>
            <span class="position-absolute bg-dark text-white ms-2 py-1 px-2 rounded">Marcar como leída</span>
        </button>

        <hr class="dropdown-divider">
    </div>
</template>
<script>
    export default {
        props:{
            notification: Object
        },
        data(){
            return {
                isRead: !! this.notification.read_at
            }
        },
        methods: {
            markAsRead(){
                axios.post(`/read-notifications/${this.notification.id}`)
                    .then(res => {
                        this.isRead = true;
                        this.emitter.emit('notification-read');
                    })
            },
            markAsUnread(){
                axios.delete(`/read-notifications/${this.notification.id}`)
                    .then(res => {
                        this.isRead = false;
                        this.emitter.emit('notification-unread');
                    })
            }
        }
    }
</script>

<style lang="scss" scoped>
    button > span {
        display: none;
    }
    button i{
        &:hover {
            & + span{
                display: inline;
            }
        }
    }
</style>
