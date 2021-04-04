<template>
    <div>
        <div class="level text-center">
            <img :src="avatar" width="200" height="200" class="mt-8 rounded-full">

            <h1 class="text-xl py-4 capitalize text-blue-darkest" style="color: #714ca3">
                {{ user.username }} 
                (<small style="color: #d98c3e">{{ reputation }}<span class="text-sm">XP</span></small>)
            </h1>
        </div>

        <div class="text-center mt-3">
            <form v-if="canUpdate" method="POST" enctype="multipart/form-data">
                <image-upload name="avatar" class="rounded mr-1 text-sm text-white bg-blue-darkest" @loaded="onLoad"></image-upload>
            </form>
        </div>

    </div>
</template>

<script>
    import ImageUpload from './ImageUpload.vue';

    export default {
        props: ['user', 'image'],

        components: { ImageUpload },

        data() {
            return {
                avatar: this.image ? this.image.url : ''
            };
        },

        computed: {
            canUpdate() {
                return this.authorize(user => user.id === this.user.id);
            },

            reputation() {
                return this.user.reputation;
            }
        },

        methods: {
            onLoad(avatar) {
                this.avatar = avatar.src;

                //this.persist(avatar.file);
                this.persist(avatar.src);
            },

            persist(avatar) {
                let data = new FormData();

                data.append('image', avatar);

                axios.post(`/api/users/${this.user.username}/avatar`, data)
                    .then(() => flash('Avatar uploaded!'))
                    .catch(error => {
                        console.log(error);
                    });
            }
        }
    }
</script>
