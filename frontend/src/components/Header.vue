<script setup>
import Menubar from "primevue/menubar";
import { useRouter } from 'vue-router';

const router = useRouter();

const items = [
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
      localStorage.removeItem('token');
      router.push('/login');
    }
  }
];
</script>

<template>
  <header>
    <Menubar :model="items">
      <template #start>
        <div class="text-primary font-bold text-xl mx-6">
          Event app
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
