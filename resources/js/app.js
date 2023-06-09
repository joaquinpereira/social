/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({});

import mitt from 'mitt';
const emitter = mitt();

import StatusForm from './components/StatusForm.vue';
import StatusesList from './components/StatusList.vue';
import StatusListItem from './components/StatusListItem.vue';
import FriendshipBtn from './components/FriendshipBtn.vue';
import AcceptFriendshipBtn from './components/AcceptFriendshipBtn.vue';
import NotificationList from './components/NotificationList.vue';
import ProfilePictureBtn from './components/ProfilePictureBtn.vue';
app.component('status-form', StatusForm);
app.component('status-list', StatusesList);
app.component('status-list-item', StatusListItem);
app.component('friendship-btn', FriendshipBtn);
app.component('accept-friendship-btn', AcceptFriendshipBtn);
app.component('notification-list', NotificationList);
app.component('profile-picture-btn', ProfilePictureBtn);

import auth from './mixins/auth';
app.mixin(auth);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

app.config.globalProperties.emitter = emitter;

app.mount('#app');
