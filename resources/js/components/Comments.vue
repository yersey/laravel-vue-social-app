<script>
import Comment from "./Comment.vue";
import CreateComment from "./CreateComment.vue";

export default {
    props: ["post", "comments"],
    components: {
        Comment,
        CreateComment,
    },
    methods: {
        handleCommentAdded(newComment) {
            this.comments.push(newComment);
        },
        handleCommentDeleted(deletedComment) {
            const index = this.comments.findIndex(
                (comment) => comment.id === deletedComment.id
            );

            if (index !== -1) {
                this.comments.splice(index, 1);
            }
        },
    },
};
</script>

<template>
    <div v-if="comments.length">
        <div v-for="comment in comments" :key="comment.id">
            <comment
                :comment="comment"
                @commentDeleted="handleCommentDeleted"
            />
        </div>
    </div>
    <div v-if="!comments.length">
        <div>No comments yet</div>
    </div>

    <create-comment :post="post" @commentAdded="handleCommentAdded" />
</template>
