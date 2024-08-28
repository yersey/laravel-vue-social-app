<script>
export default {
    emits: ["imageUploaded"],
    data() {
        return {
            image: null,
        };
    },
    methods: {
        handleFileChange(event) {
            this.image = event.target.files[0];
        },
        async uploadImage() {
            const formData = new FormData();
            formData.append("image", this.image);

            await axios
                .post("/api/v1/images", formData)
                .then((response) => {
                    this.$emit("imageUploaded", response.data.data);
                    this.$refs.uploadedImage.src = response.data.data.url;
                })
                .catch((error) => {
                    console.error(error);
                });
        },
    },
};
</script>

<template>
    <p>
        <img width="100px" src="" ref="uploadedImage" />
        <v-file-input
            label="File input"
            @change="handleFileChange"
            accept="image/*"
        >
        </v-file-input>
        <v-btn @click="uploadImage">Upload image</v-btn>
    </p>
</template>
