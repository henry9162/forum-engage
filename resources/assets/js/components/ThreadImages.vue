<template>
    <div class="container">
        <gallery :images="images" :index="index" @close="index = null"></gallery>

        <div class="image"
            v-for="(image, imageIndex) in images"
            :key="imageIndex" 
            @click="index = imageIndex" 
            :style="{ backgroundImage: 'url(' + image + ')', width: '200px', height: '200px' }">
        </div>
    </div>
</template>

<script>
    import VueGallery from 'vue-gallery';

    export default {
        props: ['files'],

        components: {
            'gallery': VueGallery
        },

        data(){
            return {
                images: [],
                index: null
            }
        },

        created() {
            this.files.forEach(file => {
                this.images.push(file.url);
            })
        }
    }
</script>

<style scoped>
  .image {
    float: left;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center center;
    border: 1px solid #ebebeb;
    margin: 5px;
  }
</style> 