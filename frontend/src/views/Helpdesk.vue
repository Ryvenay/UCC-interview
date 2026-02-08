<script setup>
import { ref, onMounted } from "vue";
import { getChats } from "@/api/chat";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import Select from "primevue/select";
import { useRouter } from "vue-router";

const router = useRouter();

const chats = ref([]);
const statusFilter = ref(null);
const statusOptions = [
  { label: 'All', value: null },
  { label: 'Open', value: 'open' },
  { label: 'Human Requested', value: 'human_requested' },
  { label: 'Closed', value: 'closed' },
];

async function fetchChats() {
  try {
    const params = {};
    if (statusFilter.value) params.status = statusFilter.value;

    const { data } = await getChats(params);

    console.log(data)

    chats.value = data.data.map(chat => ({
      ...chat,
      lastMessage: chat.messages.length
        ? chat.messages[chat.messages.length - 1].message
        : '',
    }));
  } catch (e) {
    console.error('Failed to load chats', e);
  }
}

function viewChat(chatId) {
  router.push(`/manager/chats/${chatId}`);
}

onMounted(fetchChats);
</script>

<template>
  <div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Helpdesk Chats</h1>

    <div class="flex items-center gap-4 mb-4">
      <span>Status:</span>
      <Select 
        v-model="statusFilter" 
        :options="statusOptions" 
        optionLabel="label" 
        class="w-48"
        @change="fetchChats"
      />
      <Button label="Refresh" icon="pi pi-refresh" @click="fetchChats"/>
    </div>

    <DataTable :value="chats" stripedRows responsiveLayout="scroll" class="w-full">
      <Column field="id" header="Chat ID" style="width: 100px"/>
      <Column field="user.name" header="Customer"/>
      <Column field="agent.name" header="Assigned Agent"/>
      <Column field="status" header="Status"/>
      <Column field="lastMessage" header="Last Message" style="max-width: 300px">
        <template #body="{ data }">
          <div class="truncate" style="max-width: 300px">{{ data.lastMessage }}</div>
        </template>
      </Column>
      <Column header="Actions" style="width: 150px">
        <template #body="{ data }">
          <Button label="View" icon="pi pi-eye" class="p-button-text" @click="viewChat(data.id)" />
        </template>
      </Column>
    </DataTable>
  </div>
</template>

<style scoped>
.truncate {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>
