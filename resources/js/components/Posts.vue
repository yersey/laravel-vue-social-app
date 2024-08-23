<script>
import Post from "./Post.vue";
import Users from "./Users.vue";
import CreatePost from "./CreatePost.vue";

export default {
    components: {
        Post,
        Users,
        CreatePost,
    },
    data() {
        return {
            posts: [],
        };
    },
    mounted() {
        this.fetchPosts();
    },
    methods: {
        async fetchPosts() {
            await axios
                .get("/api/posts")
                .then((response) => (this.posts = response.data.data))
                .catch((error) => {
                    console.error(error);
                });
        },
        handlePostAdded(newPost) {
            this.posts.push(newPost);
        },
        handlePostDeleted(deletedPost) {
            const index = this.posts.findIndex(
                (post) => post.id === deletedPost.id
            );

            if (index !== -1) {
                this.posts.splice(index, 1);
            }
        },
    },
};
</script>

<template>
    <v-row>
        <v-col cols="9">
            <create-post @postAdded="handlePostAdded" class="mb-5" />
            <post
                v-for="post in posts"
                :post="post"
                :key="post.id"
                @postDeleted="handlePostDeleted"
            />
        </v-col>
        <v-col cols="3">
            <users />
        </v-col>
    </v-row>
</template>
