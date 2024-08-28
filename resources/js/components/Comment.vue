<script>
import CommentReplies from "./CommentReplies.vue";

export default {
    components: {
        CommentReplies,
    },
    props: ["comment"],
    emits: ["commentDeleted"],
    data() {
        return {
            showReplyInput: false,
            newReplyContent: "",
        };
    },
    methods: {
        async deleteComment() {
            await axios
                .delete(`/api/v1/comments/${this.comment.id}`)
                .then((response) => {
                    this.$emit("commentDeleted", this.comment);
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        async likeComment() {
            await axios
                .post(`/api/v1/comments/${this.comment.id}/likes`)
                .then((response) => {
                    this.comment.likes_count++;
                    this.comment.is_liked = true;
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        async unlikeComment() {
            await axios
                .delete(`/api/v1/comments/${this.comment.id}/likes`)
                .then((response) => {
                    this.comment.likes_count--;
                    this.comment.is_liked = false;
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        async toggleCommentLike() {
            if (this.comment.is_liked) {
                this.unlikeComment();
            } else {
                this.likeComment();
            }
        },
        async addReply() {
            await axios
                .post(`/api/v1/comments/${this.comment.id}/comments`, {
                    content: this.newReplyContent,
                })
                .then((response) => {
                    this.comment.comments.push(response.data.data);
                    this.newReplyContent = "";
                    this.showReplyInput = false;
                })
                .catch((error) => {
                    console.error(error);
                });
        },
    },
};
</script>

<template>
    <div class="card-title">
        <v-avatar size="40px" class="mr-3">
            <img
                :src="comment.user.avatar"
                alt="User Avatar"
                class="avatar-image"
            />
        </v-avatar>
        <div class="user-info">
            <div class="font-weight-bold">
                {{ comment.user.name }}
                {{ comment.user.surname }}
            </div>
            <div class="text-subtitle-2">
                {{ $formatDate(comment.created_at) }}
            </div>
        </div>
    </div>
    <div>
        <div class="ml-12">
            {{ comment.content }}
        </div>
    </div>
    <v-card-actions>
        <v-btn text @click="toggleCommentLike">
            <v-icon>{{
                comment.is_liked ? "mdi-thumb-up" : "mdi-thumb-up-outline"
            }}</v-icon>
            {{ comment.is_liked ? "Unlike" : "Like" }}
            ({{ comment.likes_count }})
        </v-btn>
        <v-btn text @click="deleteComment">
            <v-icon>mdi-trash-can</v-icon>
            Delete
        </v-btn>
    </v-card-actions>

    <div class="ml-12">
        <comment-replies :replies="comment.comments" :comment="comment" />
    </div>
</template>

<style scoped>
.card-title {
    display: flex;
    align-items: center;
}
</style>
