<template>
    <div class="z-10" @mouseover="activate" @mouseout="deactivate">
        <div class="rounded-full bg-blue-darkest w-10 h-10 flex items-center justify-center mr-4 cursor-pointer relative z-10">
            <a href="/chats" class="text-blue-lightest flex items-center">
                <div class="rounded-full bg-red w-2 h-2 absolute pin-t pin-r mt-1" v-if="notifications.length"></div>

                <i class="far fa-comment-dots"></i>
            </a>
        </div>

        <div class="relative" v-show="active">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="glyphicon glyphicon-bell"></span>
            </a>

            <div class="bg-grey-light p-6 text-black absolute rounded"
                 style="border-top-right-radius: 28px 22px; width: 313px; top: -32px; right: 23px"
            >
                <h4 class="mb-4">Notifications</h4>

                <ul class="list-reset">
                    <li v-for="(notification, index) in notifications"
                        :key="notification.id"
                        :class="index === notifications.length - 1 ? '' : 'mb-4'"
                    >
                        <a :href="notification.data.link"
                           class="text-xs flex items-center pr-1 link"
                           
                        >
                            <img :src="notification.data.notifier.avatar_path"
                                 :alt="notification.data.notifier.username"
                                 class="w-8 mr-3">

                            <span v-text="notification.data.message"></span>
                        </a>
                    </li>

                    <li v-if="! notifications.length" class="text-xs">You have zero notifications.</li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>

    import activation from '../mixins/activation';

    export default {

        mixins: [ activation ],

        data() {
            return { 
                notifications: false,
                pictures: ''
            }
        },

        created() {
            this.fetchNotifications();
        },

        computed: {
            endpoint() {
                return `/profiles/notifications/chat`;
            }
        },

        methods: {
            fetchNotifications() {
                axios.get(this.endpoint)
                    .then(response => {
                        console.log(response)
                        this.notifications = response.data
                    });
            }
        }
    }
</script>
