<script>
import CommentReply from "./CommentReply.vue";
import CreateCommentReply from "./CreateCommentReply.vue";

export default {
    props: ["comment", "replies"],
    components: {
        CommentReply,
        CreateCommentReply,
    },
    methods: {
        handleReplyAdded(newReply) {
            this.replies.push(newReply);
        },
        handleReplyDeleted(deletedReply) {
            const index = this.replies.findIndex(
                (reply) => reply.id === deletedReply.id
            );

            if (index !== -1) {
                this.replies.splice(index, 1);
            }
        },
    },
};
</script>

<template>
    <div v-for="reply in replies" :key="reply.id">
        <comment-reply :reply="reply" @replyDeleted="handleReplyDeleted" />
    </div>
    <create-comment-reply :comment="comment" @replyAdded="handleReplyAdded" />
</template>
