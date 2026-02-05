<script setup>
import { ref } from "vue";
import { useAuthStore } from "@/stores/auth";
import { useRouter } from "vue-router";
import Card from "primevue/card";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import Message from "primevue/message";

const auth = useAuthStore();

const email = ref("");
const password = ref("");
const error = ref(null);
const router = useRouter();

async function submit() {
  error.value = null;
  try {
    await auth.login(email.value, password.value);
    router.push("/");
  } catch (e) {
    error.value = "Invalid credentials";
  }
}
</script>

<template>
  <div class="login-wrapper">
    <Card class="login-card border border-gray-600 py-4">
      <template #title>
        <div class="text-center text-4xl font-extrabold">Login</div>
      </template>

      <template #content>
        <form @submit.prevent="submit" class="login-form w-100 grid grid-cols-1 gap-4 pt-4">
          <div class="field">
            <InputText
              id="email"
              type="email"
              placeholder="Email"
              v-model="email"
              class="w-full"
            />
          </div>

          <div class="field">
            <InputText
              id="password"
              type="password"
              placeholder="Password"
              v-model="password"
              class="w-full"
            />
          </div>

          <Button label="Login" type="submit" class="w-full" />

          <Message v-if="error" severity="error" class="mt-3">
            {{ error }}
          </Message>
        </form>
      </template>
    </Card>
  </div>
</template>

<style scoped>
.login-wrapper {
  min-height: 50vh;
  display: flex;
  justify-content: center;
  align-items: center;
}


</style>
