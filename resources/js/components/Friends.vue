<script>
import Friend from "./Friend.vue";
import { useUserStore } from "@/stores/UserStore";

export default {
    components: {
        Friend,
    },
    setup() {
        const userStore = useUserStore();
        return { userStore };
    },
    mounted() {
        this.userStore.init();
        this.fetchFriends();
    },
    data() {
        return {
            friends: [],
        };
    },
    methods: {
        async fetchFriends() {
            await axios
                .get(`/api/v1/users/${this.userStore.user.id}/friends`)
                .then((response) => {
                    this.friends = response.data.data;
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        handleUnfriended(unfriended) {
            const index = this.friends.findIndex(
                (friend) => friend.id === unfriended.id
            );

            if (index !== -1) {
                this.friends.splice(index, 1);
            }
        },
    },
};
</script>

<template>
    <h2>Friends:</h2>
    <friend
        v-for="friend in friends"
        :friend="friend"
        :key="friend.id"
        @unfriended="handleUnfriended"
    />
</template>
