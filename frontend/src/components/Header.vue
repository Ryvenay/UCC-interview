<script setup>
import { computed } from 'vue';
import Menubar from "primevue/menubar";
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const auth = useAuthStore();
const router = useRouter();

const items = computed(() => {
  if (!auth.isAuthenticated) {
    return [
      {
        label: 'Login',
        icon: 'pi pi-sign-in',
        command: () => router.push('/login')
      }
    ];
  }

  return [
    {
      label: 'Events',
      icon: 'pi pi-calendar',
      command: () => router.push('/')
    },
    {
      label: 'Helpdesk',
      icon: 'pi pi-comments',
      command: () => router.push('/helpdesk')
    },
    {
      label: 'Logout',
      icon: 'pi pi-sign-out',
      command: () => {
        auth.logout();
        router.push('/login');
      }
    }
  ];
});
</script>

<template>
  <header>
    <Menubar :model="items">
      <template #start>
        <div class="text-primary font-bold text-xl mx-6">
          EventApp
        </div>
      </template>
    </Menubar>
  </header>
</template>

<style scoped>
header {
  line-height: 1.5;
}

@media (min-width: 1024px) {
  header {
    place-items: center;
  }
}
</style>
