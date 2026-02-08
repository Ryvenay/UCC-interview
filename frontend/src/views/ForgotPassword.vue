<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { forgotPassword } from '@/auth/auth.js';

import Card from "primevue/card";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import Message from "primevue/message";

const router = useRouter();

const email = ref("")
const error = ref(null)

async function submit() {
  error.value = null;
  try {
    await forgotPassword(email.value);
    router.push("/reset-link-sent");
  } catch (e) {
    error.value = e.response.data.message;
  }
}
</script>

<template>
  <div class="wrapper">
    <Card class="border border-gray-600 py-4">
      <template #title>
        <div class="text-center text-4xl font-extrabold">Reset password</div>
      </template>

      <template #content>
        <form @submit.prevent="submit" class="w-100 grid grid-cols-1 gap-4 pt-4">
          <div class="field">
            <InputText
              id="email"
              type="email"
              placeholder="Email"
              v-model="email"
              class="w-full"
            />
          </div>

          <Button label="Sent email" type="submit" class="w-full" />
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