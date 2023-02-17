<template>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle"
            dusk="notifications" href="#"
            :class="count ? 'text-primary fw-bold' : ''"
            role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <slot></slot> {{ count }}
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
            <li v-for="notification in notifications">
                <notification-list-item
                    :notification="notification"
                    ::key="notification.id"
                ></notification-list-item>
            </li>
        </ul>
    </li>
</template>

<script>
    import NotificationListItem from './NotificationListItem.vue';
    export default {
        components:{ NotificationListItem },
        data(){
            return{
                notifications: [],
                count: ''
            }
        },
        mounted(){
            axios.get('/notifications')
                .then(res => {
                    this.notifications = res.data;
                    this.unreadNotifications();
                })
            this.emitter.on('notification-read', () => {
                this.count--;
            });
            this.emitter.on('notification-unread', () => {
                this.count++;
            });
        },
        methods: {
            unreadNotifications(){
                this.count = this.notifications.filter(notification => {
                    return notification.read_at === null;
                }).length || '';
            }
        }
    }
</script>

<style>

</style>
