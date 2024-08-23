<script>
import { useUserStore } from "@/stores/UserStore";

export default {
    data() {
        return {
            email: "test@example.com",
            password: "daniel12",
        };
    },
    setup() {
        const userStore = useUserStore();
        return { userStore };
    },
    methods: {
        async login() {
            this.userStore.login(this.email, this.password).then(() => {
                this.$router.push({ path: "/" });
            });
        },
    },
};
</script>

<template>
    <div>
        <v-card
            class="mx-auto pa-12 pb-8"
            elevation="8"
            max-width="448"
            rounded="lg"
        >
            <div class="text-subtitle-1 text-medium-emphasis">Account</div>

            <v-text-field
                v-model="email"
                density="compact"
                placeholder="Email address"
                prepend-inner-icon="mdi-email-outline"
                variant="outlined"
            ></v-text-field>

            <div
                class="text-subtitle-1 text-medium-emphasis d-flex align-center justify-space-between"
            >
                Password
            </div>

            <v-text-field
                v-model="password"
                density="compact"
                placeholder="Enter your password"
                prepend-inner-icon="mdi-lock-outline"
                variant="outlined"
                type="password"
            ></v-text-field>

            <v-btn
                class="mb-8"
                color="blue"
                size="large"
                variant="tonal"
                block
                @click="login"
            >
                Log In
            </v-btn>
        </v-card>
    </div>
</template>
