<script>
export default {
    props: ["reply"],
    emits: ["replyDeleted"],
    methods: {
        async deleteReply() {
            await axios
                .delete(`/api/comments/${this.reply.id}`)
                .then((response) => {
                    this.$emit("replyDeleted", this.reply);
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        async likeReply() {
            await axios
                .post(`/api/comments/${this.reply.id}/likes`)
                .then((response) => {
                    this.reply.likes_count++;
                    this.reply.is_liked = true;
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        async unlikeReply() {
            await axios
                .delete(`/api/comments/${this.reply.id}/likes`)
                .then((response) => {
                    this.reply.likes_count--;
                    this.reply.is_liked = false;
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        async toggleReplyLike() {
            if (this.reply.is_liked) {
                this.unlikeReply();
            } else {
                this.likeReply();
            }
        },
    },
};
</script>

<template>
    <div class="card-title">
        <v-avatar size="40px" class="mr-3">
            <img
                :src="reply.user.avatar"
                alt="User Avatar"
                class="avatar-image"
            />
        </v-avatar>
        <div class="user-info">
            <div class="font-weight-bold">
                {{ reply.user.name }}
                {{ reply.user.surname }}
            </div>
            <div class="text-subtitle-2">
                {{ $formatDate(reply.created_at) }}
            </div>
        </div>
    </div>
    <div>
        <div class="ml-12">
            {{ reply.content }}
        </div>
    </div>
    <v-card-actions>
        <v-btn text @click="toggleReplyLike">
            <v-icon>{{
                reply.is_liked ? "mdi-thumb-up" : "mdi-thumb-up-outline"
            }}</v-icon>
            {{ reply.is_liked ? "Unlike" : "Like" }}
            ({{ reply.likes_count }})
        </v-btn>
        <v-btn text @click="deleteReply">
            <v-icon>mdi-trash-can</v-icon>
            Delete
        </v-btn>
    </v-card-actions>
</template>

<style scoped>
.card-title {
    display: flex;
    align-items: center;
}
</style>
