<template>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="notifications" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <slot></slot>
        </a>
        <ul class="dropdown-menu dropdown-menu-right">
            <li v-for="notification in notifications">
                <a
                    :id="notification.id"
                    :href="notification.data.link"
                    class="dropdown-item">
                    {{ notification.data.message }}
                </a>
                <hr class="dropdown-divider">
            </li>
        </ul>
    </li>
</template>

<script>
import axios from 'axios';

    export default {
        data(){
            return{
                notifications: []
            }
        },
        mounted(){
            axios.get('/notifications')
                .then(res => {
                    this.notifications = res.data;
                })
        }
    }
</script>

<style>

</style>
