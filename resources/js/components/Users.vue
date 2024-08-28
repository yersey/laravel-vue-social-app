<script>
import User from "./User.vue";
import { useUserStore } from "@/stores/UserStore";

export default {
    components: {
        User,
    },
    data() {
        return {
            users: [],
        };
    },
    setup() {
        const userStore = useUserStore();
        return { userStore };
    },
    mounted() {
        this.fetchUsers();
    },
    methods: {
        async fetchUsers() {
            await axios
                .get("/api/v1/users")
                .then((response) => {
                    this.users = response.data.data.filter(
                        (user) => user.id !== this.userStore.user.id
                    );
                })
                .catch((error) => {
                    console.error(error);
                });
        },
    },
};
</script>

<template>
    <h2>Users:</h2>
    <user v-for="user in users" :user="user" :key="user.id" />
</template>
