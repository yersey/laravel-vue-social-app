<script>
export default {
    props: ["post"],
    emits: ["commentAdded"],
    data() {
        return {
            newCommentBody: "",
        };
    },
    methods: {
        async addComment() {
            await axios
                .post(`/api/v1/posts/${this.post.id}/comments`, {
                    content: this.newCommentBody,
                })
                .then((response) => {
                    this.newCommentBody = "";
                    this.$emit("commentAdded", response.data.data);
                })
                .catch((error) => {
                    console.error(error);
                });
        },
    },
};
</script>

<template>
    <v-row>
        <v-col cols="5">
            <v-textarea
                v-model="newCommentBody"
                class="mx-2"
                label="Comment"
                prepend-inner-icon="mdi-comment"
                rows="1"
            ></v-textarea>
        </v-col>
        <v-btn @click="addComment">Dodaj komentarz</v-btn>
    </v-row>
</template>
