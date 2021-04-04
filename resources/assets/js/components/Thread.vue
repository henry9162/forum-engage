<script>
export default {
    data(){
        return {
            form: {
                title : "",
                channel_id: "",
                body: "",
                images: []
            },
            imageInputs: [],
            loading: false,
            feedback: ""
        }
    },
    computed: {
        enabled() {
            if (this.imageInputs.length < 3){
                return true;
            }
        }
    },
    methods: {
        postThread() {
            this.loading = true;

            axios
                .post("/threads", this.form, {
                    headers:{
                        'Content-Type':'application/json',
                        'Accept':'application/json'
                    }
                })
                .then(({data: {redirect}}) => {
                    //location.assign(redirect);
                    window.location.replace('/');
                    this.loading = false;
                    this.$modal.hide("new-thread");
                })
                .catch(error => {
                    this.feedback = error.response.data;
                    this.loading = false;
                });
        },
        onAddImage() {
            const newImage = {
                id: Math.random() * Math.random() * 1000
            }
            this.imageInputs.push(newImage)
        },
        onDeleteImage(id) {
            this.imageInputs = this.imageInputs.filter(image => image.id !== id);
            //this.form.images = this.form.images.filter(obj => obj.image !== ); 
        },
        imageType(file){
            const acceptedImageTypes = ['image/svg', 'image/jpeg', 'image/png'];

            var type;

            switch (acceptedImageTypes.includes(file)) {

                case acceptedImageTypes[0] == file:
                    type = 'svg';
                    break;
                case acceptedImageTypes[1] == file :
                    type = 'jpeg';
                    break;
                case acceptedImageTypes[2] == file:
                    type = 'png';
                    break;
                default:
                    type = '';
            }
            return type;
        },
        onChange(e) {
            if (! e.target.files.length ) return;

            let file = e.target.files[0];

            // if (file.size > 102400){
            //     alert("A file upload should be of size 100kb or less, please resize!");
            //     this.imageInputs = [];
            //     this.form.images = [];
            //     return;
            // } 

            let reader = new FileReader();

                reader.readAsDataURL(file);

                reader.onload = e => {
                    let src = e.target.result;

                    let imageType = this.imageType(file.type);

                    const newImageInput = { image: src, type: imageType };

                    this.form.images.push(newImageInput);
            };
        }
      
    }
}
</script>

