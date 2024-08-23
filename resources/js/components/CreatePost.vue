<script>
export default {
    emits: ["postAdded"],
    data() {
        return {
            newPostBody: "",
        };
    },
    methods: {
        async addPost() {
            await axios
                .post("/api/posts", { content: this.newPostBody })
                .then((response) => {
                    this.newPostBody = "";
                    this.$emit("postAdded", response.data.data);
                })
                .catch((error) => {
                    console.error(error);
                });
        },
    },
};
</script>

<template>
    <div>
        <v-textarea
            v-model="newPostBody"
            name="input-7-1"
            variant="filled"
            auto-grow
            rows="2"
        ></v-textarea>
        <v-btn
            @click="addPost"
            class="text-none"
            color="indigo-darken-3"
            variant="flat"
            block
        >
            Add post
        </v-btn>
    </div>
</template>
