<script>
import { useUserStore } from "@/stores/UserStore";

export default {
    name: "App",
    setup() {
        const userStore = useUserStore();
        return { userStore };
    },
    mounted() {
        this.userStore.init();
    },
};
</script>

<template>
    <v-app>
        <v-app-bar app>
            <v-spacer></v-spacer>

            <v-toolbar-items>
                <v-btn text to="/">Posts</v-btn>
                <v-btn text to="/users">Users</v-btn>
                <v-btn text to="/friends">Friends</v-btn>
                <v-btn text to="/friend-requests">Friend Requests</v-btn>
                <v-btn text to="/profile">Profile</v-btn>
            </v-toolbar-items>

            <v-spacer></v-spacer>

            <div v-if="userStore.isAuthenticated" class="d-flex align-center">
                <v-btn icon>
                    <v-avatar size="32px">
                        <img
                            style="object-fit: cover; width: 100%; height: 100%"
                            :src="userStore.user.avatar"
                            alt="User Avatar"
                        />
                    </v-avatar>
                </v-btn>
                <v-btn text>
                    {{ userStore.user.name }} {{ userStore.user.surname }}
                </v-btn>
                <v-btn text @click="userStore.logout()"> Logout </v-btn>
            </div>

            <div v-else class="d-flex align-center">
                <v-btn color="primary" to="/login"> Demo user </v-btn>
            </div>
        </v-app-bar>

        <v-main>
            <v-container>
                <router-view />
            </v-container>
        </v-main>
    </v-app>
</template>
