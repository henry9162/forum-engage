<template>
    <a href="#" class="rounded hover:bg-blue-light hover:text-white ml-2 py-1 px-2 border-l" :class="isActive ? 'font-bold bg-blue-dark text-white': ''" @click.prevent="subscribe" v-text="isActive ? 'Subscribed' : 'Subscribe'"></a>
</template>

<script>
export default {
    props: ["active"],

    data() {
        return {
            isActive: this.active
        };
    },

    methods: {
        subscribe() {
            this.isActive ? this.deleteSubscriptions() : this.postSubscriptions();

            this.isActive = !this.isActive;

            if (this.isActive) {
                flash("Okay, we'll notify you when this thread is updated!");
            }
        },
        deleteSubscriptions() {
            axios.post(location.pathname + "/delete-subscriptions");
        },
        postSubscriptions() {
            axios.post(location.pathname + "/subscriptions");
        }
    }
};
</script>
