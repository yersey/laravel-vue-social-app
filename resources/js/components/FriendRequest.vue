<script>
export default {
    props: ["friendRequest"],
    emits: ["accepted", "declined"],
    methods: {
        async acceptFriendRequest() {
            await axios
                .patch(`/api/friend-requests/${this.friendRequest.id}`)
                .then((response) => {
                    this.$emit("accepted", this.friendRequest);
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        async declineFriendRequest() {
            await axios
                .delete(`/api/friend-requests/${this.friendRequest.id}`)
                .then((response) => {
                    this.$emit("declined", this.friendRequest);
                })
                .catch((error) => {
                    console.error(error);
                });
        },
    },
};
</script>

<template>
    <div class="d-flex mt-2">
        <v-avatar size="40px" class="mr-3">
            <img
                v-if="friendRequest.sender.avatar"
                :src="friendRequest.sender.avatar"
                alt="User Avatar"
                class="avatar-image"
            />
            <v-icon v-else>mdi-account-circle</v-icon>
        </v-avatar>
        <div>
            <div class="font-weight-bold">
                {{ friendRequest.sender.name }}
                {{ friendRequest.sender.surname }}
            </div>
            <v-btn size="x-small" @click="acceptFriendRequest">Accept</v-btn>
            <v-btn size="x-small" @click="declineFriendRequest">Decline</v-btn>
        </div>
    </div>
</template>
