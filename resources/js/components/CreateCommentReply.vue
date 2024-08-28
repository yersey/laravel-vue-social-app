<script>
export default {
    props: ["comment"],
    emits: ["replyAdded"],
    data() {
        return {
            showReplyInput: false,
            newReplyBody: "",
        };
    },
    methods: {
        async addReply() {
            await axios
                .post(`/api/v1/comments/${this.comment.id}/comments`, {
                    content: this.newReplyBody,
                })
                .then((response) => {
                    this.newReplyBody = "";
                    (this.showReplyInput = false),
                        this.$emit("replyAdded", response.data.data);
                })
                .catch((error) => {
                    console.error(error);
                });
        },
    },
};
</script>

<template>
    <div v-if="!showReplyInput" class="mb-2">
        <v-btn @click="showReplyInput = !showReplyInput" size="small"
            >Odpowiedz</v-btn
        >
    </div>
    <div v-else>
        <v-row>
            <v-col cols="5">
                <v-textarea
                    v-model="newReplyBody"
                    class="mx-2"
                    label="Comment"
                    prepend-inner-icon="mdi-comment"
                    rows="1"
                ></v-textarea>
            </v-col>
            <v-btn @click="addReply">Dodaj</v-btn>
        </v-row>
    </div>
</template>
