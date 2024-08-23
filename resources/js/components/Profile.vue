<script>
import UploadImage from "./UploadImage.vue";
import { useUserStore } from "@/stores/UserStore";

export default {
    components: {
        UploadImage,
    },
    data() {
        return {
            user: {},
            avatarPath: "",
            removeAvatar: false,
            isDialogOpen: false,
        };
    },
    setup() {
        const userStore = useUserStore();
        return { userStore };
    },
    mounted() {
        if (this.userStore.user) {
            Object.assign(this.user, this.userStore.user);
        }

        this.userStore.getUser().then(() => {
            Object.assign(this.user, this.userStore.user);
        });
    },
    methods: {
        async updateUser() {
            await axios
                .put(`/api/users/${this.user.id}`, {
                    name: this.user.name,
                    surname: this.user.surname,
                    date_of_birth: this.user.date_of_birth,
                    avatar_path: this.avatarPath,
                    remove_avatar: this.removeAvatar,
                })
                .then((response) => {
                    this.user = response.data.data;
                    this.userStore.user = response.data.data;
                })
                .catch((error) => {
                    console.error(error);
                })
                .finally((fn) => {
                    this.removeAvatar = false;
                });
        },
        handleImageUploaded(image) {
            this.avatarPath = image.path;
            this.user.avatar = image.url;
            this.isDialogOpen = false;
        },
    },
};
</script>

<template>
    <div>
        <v-avatar size="150px" class="mr-3">
            <img
                style="object-fit: cover; width: 100%; height: 100%"
                v-if="user.avatar && !removeAvatar"
                :src="user.avatar"
                alt="User Avatar"
                class="avatar-image"
            />
            <v-icon v-else>mdi-account-circle</v-icon>
        </v-avatar>
        <v-btn @click="isDialogOpen = true" size="x-small">Change avatar</v-btn>
        <v-btn @click="removeAvatar = true" size="x-small">Remove avatar</v-btn>

        <v-text-field
            class="mt-2"
            v-model="user.name"
            label="Name"
        ></v-text-field>
        <v-text-field v-model="user.surname" label="surname"></v-text-field>

        <div>
            Date of birth: <input type="date" v-model="user.date_of_birth" />
        </div>

        <v-btn @click="updateUser" class="mt-3">Update</v-btn>
    </div>

    <v-dialog v-model="isDialogOpen" max-width="500">
        <v-card title="Upload avatar">
            <v-card-text>
                <upload-image @imageUploaded="handleImageUploaded" />
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn text="Cancel" @click="isDialogOpen = false"></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
