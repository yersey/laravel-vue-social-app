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
            isLoading: false,
            nextCursor: null,
        };
    },
    mounted() {
        this.fetchPosts();
        window.addEventListener('mousewheel', this.handleScroll);
    },
    methods: {
        async fetchPosts() {
            this.isLoading = true;

            await axios
                .get(`/api/v1/posts/?cursor=${this.nextCursor}`)
                .then((response) => {
                    this.posts.push(...response.data.data)
                    this.nextCursor = response.data.meta.next_cursor;
                })
                .catch((error) => {
                    console.error(error);
                });

            this.isLoading = false;
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
        handleScroll() {
            if (
                window.innerHeight + window.scrollY >= document.body.offsetHeight - 1
                && !this.isLoading
                && this.nextCursor
            ) {
                this.fetchPosts();
            }
        },
    },
    beforeUnmount() {
        window.removeEventListener('mousewheel', this.handleScroll);
    }
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

            <div v-if="isLoading" class="ajax-load text-center">
                <v-progress-circular
                    :size="50"
                    color="primary"
                    indeterminate
                ></v-progress-circular>
            </div>
        </v-col>
        <v-col cols="3">
            <users />
        </v-col>
    </v-row>
</template>
