<script>
import Replies from "../components/Replies.vue";
import SubscribeButton from "../components/SubscribeButton.vue";
import Highlight from "../components/Highlight.vue";

export default {
    props: ["thread"],

    components: { Replies, SubscribeButton, Highlight },

    data() {
        return {
            repliesCount: this.thread.replies_count,
            locked: this.thread.locked,
            pinned: this.thread.pinned,
            title: this.thread.title,
            body: this.thread.body,
            form: {},
            editing: false,
            feedback: "",
            errors: false
        };
    },

    created() {
        this.resetForm();
    },

    watch: {
        editing(enabled) {
            if (enabled) {
                this.$modal.show("update-thread");
            } else {
                this.$modal.hide("update-thread");
            }
        }
    },

    computed: {
        lockUri(){
            return `/locked-threads/${this.thread.slug}`;
        },
        pinUri(){
            return `/pinned-threads/${this.thread.slug}`;
        }
    },

    methods: {
        toggleLock() {
            this.locked ? this.deleteLock() : this.postLock();

            this.locked = !this.locked;
        },

        togglePin() {
            let uri = `/pinned-threads/${this.thread.slug}`;

            this.pinned ? this.deletePin() : this.postPin();

            this.pinned = !this.pinned;
        },

        postLock() {
            axios.post(this.lockUri);
        },

        deleteLock() {
            axios.post(`/delete-locked-threads/${this.thread.slug}`);
        },

        postPin() {
            axios.post(this.pinUri);
        },

        deletePin() {
            axios.post(`/delete-pinned-threads/${this.thread.slug}`);
        },

        update() {
            let uri = `/threads/${this.thread.channel.slug}/${
                this.thread.slug
            }`;

            axios
                .patch(uri, this.form)
                .then(() => {
                    this.editing = false;
                    this.title = this.form.title;
                    this.body = this.form.body;

                    flash("Your thread has been updated.");
                })
                .catch(error => {
                    this.feedback = "Whoops, validation failed.";
                    this.errors = error.response.data.errors;
                });
        },

        resetForm() {
            this.form = {
                title: this.thread.title,
                body: this.thread.body
            };

            this.editing = false;

            this.$modal.hide("update-thread");
        }
    }
};
</script>
