<script>
import FriendRequest from "./FriendRequest.vue";

export default {
    components: {
        FriendRequest,
    },
    data() {
        return {
            friendRequests: [],
        };
    },
    mounted() {
        this.fetchFriendRequests();
    },
    methods: {
        async fetchFriendRequests() {
            await axios
                .get("/api/friend-requests")
                .then((response) => {
                    this.friendRequests = response.data.data;
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        handleAccepted(acceptedFriendRequest) {
            const index = this.friendRequests.findIndex(
                (friendRequest) => friendRequest.id === acceptedFriendRequest.id
            );

            if (index !== -1) {
                this.friendRequests.splice(index, 1);
            }
        },
        handleDeclined(declinedFriendRequest) {
            const index = this.friendRequests.findIndex(
                (friendRequest) => friendRequest.id === declinedFriendRequest.id
            );

            if (index !== -1) {
                this.friendRequests.splice(index, 1);
            }
        },
    },
};
</script>

<template>
    <h2>Friend Requests:</h2>
    <friend-request
        v-for="friendRequest in friendRequests"
        :friendRequest="friendRequest"
        :key="friendRequest.id"
        @accepted="handleAccepted"
        @declined="handleDeclined"
    />
</template>
