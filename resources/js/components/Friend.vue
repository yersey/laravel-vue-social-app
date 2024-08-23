<script>
import { useUserStore } from "@/stores/UserStore";

export default {
    props: ["friend"],
    emits: ["unfriended"],
    setup() {
        const userStore = useUserStore();
        return { userStore };
    },
    methods: {
        async unfriend() {
            await axios
                .delete(
                    `/api/users/${this.userStore.user.id}/friends/${this.friend.id}`
                )
                .then((response) => {
                    this.$emit("unfriended", this.friend);
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
                v-if="friend.avatar"
                :src="friend.avatar"
                alt="User Avatar"
                class="avatar-image"
            />
            <v-icon v-else>mdi-account-circle</v-icon>
        </v-avatar>
        <div>
            <div class="font-weight-bold">
                {{ friend.name }} {{ friend.surname }}
            </div>
            <v-btn @click="unfriend" size="x-small">Unfriend</v-btn>
        </div>
    </div>
</template>
