<template>
    <div class="py-6 ml-10">
        <div v-if="! signedIn">
            <p class="text-center text-sm text-grey-dark">
                Please <a href="/login" @click.prevent="$modal.show('login')" class="text-blue link">sign in</a> to participate in this
                discussion.
            </p>
        </div>

        <div v-else-if="! confirmed">
            To participate in this thread, please check your email and confirm your account.
        </div>

        <div v-else>
            <div class="mb-3">
                <at :members="usernames">
                    <wysiwyg name="body" v-model="body" placeholder="Have something to say?"></wysiwyg>
                </at>
            </div>

            <button type="submit"
                    class="btn is-green"
                    :class="loading ? 'loader' : ''"
                    @click="addReply">Post</button>
        </div>
    </div>
</template>

<script>
// import "jquery.caret";
// import "at.js";
import At from 'vue-at'

export default {
    components: { At },

    data() {
        return {
            body: "",
            usernames: [],
            loading: false
        };
    },

    computed: {
        confirmed() {
            return window.App.user.confirmed;
        }
    },

    mounted() {
        axios
            .get("/api/users")
            .then(({data}) => {
                    data.forEach(response => {
                        this.usernames.push(response.username);
                    });
                })
                .catch(error => {
                    console.log('Sorry, An error occcured while fetching users!');
                });
    },

    methods: {
        addReply() {
            this.loading = true;

            axios
                .post(location.pathname + "/replies", { body: this.body })
                .catch(error => {
                    flash(error.response.data, "danger");
                })
                .then(({ data }) => {
                    this.loading = false;
                    this.body = "";

                    flash("Your reply has been posted.");

                    this.$emit("created", data);
                });
        }
    }
};
</script>

<style scoped>
.new-reply {
    background-color: #fff;
}
</style>
