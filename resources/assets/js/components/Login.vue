<script>
export default {
    data() {
        return {
            form: { email: "", password: "" },
            feedback: "",
            loading: false
        };
    },

    methods: {
        login() {
            this.loading = true;

            axios
                .post("/signin", this.form, {
                    headers:{
                        'Content-Type':'application/json',
                        'Accept':'application/json'
                    }
                })
                .then(({data: {redirect}}) => {
                    //location.assign(redirect);
                    window.location.replace('/');
                    this.loading = false;
                })
                .catch(error => {
                    this.feedback =
                        "The login details are incorrect. Please try again.";
                    this.loading = false;
                });
        },

        register() {
            this.$modal.hide("login");
            this.$modal.show("register");
        }
    }
};
</script>
