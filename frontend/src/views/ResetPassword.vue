<script setup>
import { ref } from "vue";
import { useRouter, useRoute } from "vue-router";
import { resetPassword } from '@/auth/auth.js';

import Card from "primevue/card";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import Message from "primevue/message";

const router = useRouter();
const route = useRoute();

const password = ref("")
const confirm_password = ref("")

const token = route.query.token;
const email = route.query.email;

const error = ref(null)

async function submit() {
    error.value = null;
    console.log(token)
    console.log(email)

    if (!token || !email) {
        error.value = "Invalid reset link";
    }

    try {
        await resetPassword({
            token: token,
            email: email,
            password: password.value,
            password_confirmation: confirm_password.value,
        });



        router.push("/login");
    } catch (e) {
        error.value = e.response.data.message;
    }
}
</script>

<template>
    <div class="wrapper">
        <Card class="border border-gray-600 py-4">
            <template #title>
                <div class="text-center text-4xl font-extrabold">New password</div>
            </template>

            <template #content>
                <form @submit.prevent="submit" class="w-100 grid grid-cols-1 gap-4 pt-4">
                    <div class="field">
                        <InputText id="password" type="password" placeholder="Password" v-model="password"
                            class="w-full" />
                    </div>

                    <div class="field">
                        <InputText id="confirm_password" type="password" placeholder="Confirm password"
                            v-model="confirm_password" class="w-full" />
                    </div>

                    <Button label="Reset password" type="submit" class="w-full" />
                    <Message v-if="error" severity="error" class="mt-3">
                        {{ error }}
                    </Message>
                </form>
            </template>
        </Card>
    </div>

</template>

<style scoped>
.wrapper {
    min-height: 50vh;
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>