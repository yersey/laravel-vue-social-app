<script>
import { useUserStore } from "@/stores/UserStore";

export default {
    props: ["user"],
    setup() {
        const userStore = useUserStore();
        return { userStore };
    },
    methods: {
        async sendFriendRequest() {
            await axios
                .post(`/api/v1/users/${this.user.id}/friend-requests`)
                .then((response) => {
                    this.user.friend_request = {
                        id: response.data.data.id,
                        sender_id: this.userStore.user.id,
                    };
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        async cancelFriendRequest() {
            await axios
                .delete(
                    `/api/v1/users/${this.user.id}/friend-requests/${this.user.friend_request.id}`
                )
                .then((response) => {
                    this.user.friend_request = null;
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
                v-if="user.avatar"
                :src="user.avatar"
                alt="User Avatar"
                class="avatar-image"
            />
            <v-icon v-else>mdi-account-circle</v-icon>
        </v-avatar>
        <div>
            <div class="font-weight-bold">
                {{ user.name }} {{ user.surname }}
            </div>
            <div v-if="!user.is_friend">
                <v-btn
                    v-if="!user.friend_request"
                    @click="sendFriendRequest"
                    size="x-small"
                    >Send friend Request</v-btn
                >
                <v-btn
                    v-if="
                        user.friend_request &&
                        user.friend_request.sender_id == userStore.user.id
                    "
                    @click="cancelFriendRequest"
                    size="x-small"
                >
                    Cancel friend request
                </v-btn>
            </div>
        </div>
    </div>
</template>
