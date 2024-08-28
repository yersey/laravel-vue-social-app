<script>
import Comments from "./Comments.vue";

export default {
    components: {
        Comments,
    },
    props: ["post"],
    emits: ["postDeleted"],
    methods: {
        async deletePost() {
            await axios
                .delete(`/api/v1/posts/${this.post.id}`)
                .then((response) => {
                    this.$emit("postDeleted", this.post);
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        async likePost() {
            await axios
                .post(`/api/v1/posts/${this.post.id}/likes`)
                .then((response) => {
                    this.post.likes_count++;
                    this.post.is_liked = true;
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        async unlikePost() {
            await axios
                .delete(`/api/v1/posts/${this.post.id}/likes`)
                .then((response) => {
                    this.post.likes_count--;
                    this.post.is_liked = false;
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        async togglePostLike() {
            if (this.post.is_liked) {
                this.unlikePost();
            } else {
                this.likePost();
            }
        },
    },
};
</script>

<template>
    <v-card class="mb-4">
        <v-card-title class="card-title">
            <v-avatar size="40px" class="mr-3">
                <img
                    :src="post.user.avatar"
                    alt="User Avatar"
                    class="avatar-image"
                />
            </v-avatar>
            <div class="user-info">
                <div class="font-weight-bold">
                    {{ post.user.name }} {{ post.user.surname }}
                </div>
                <div class="text-subtitle-2">
                    {{ $formatDate(post.created_at) }}
                </div>
            </div>
        </v-card-title>

        <v-card-text>
            <div class="ml-12">
                {{ post.content }}
            </div>
        </v-card-text>

        <v-card-actions>
            <v-btn text @click="togglePostLike">
                <v-icon>{{
                    post.is_liked ? "mdi-thumb-up" : "mdi-thumb-up-outline"
                }}</v-icon>
                {{ post.is_liked ? "Unlike" : "Like" }}
                ({{ post.likes_count }})
            </v-btn>
            <v-btn text @click="deletePost">
                <v-icon>mdi-trash-can</v-icon>
                Delete
            </v-btn>
        </v-card-actions>

        <v-divider></v-divider>
        <v-card-subtitle>Comments</v-card-subtitle>
        <div class="ml-12">
            <comments :comments="post.comments" :post="post" />
        </div>
    </v-card>
</template>

<style scoped>
.card-title {
    display: flex;
    align-items: center;
}
</style>
