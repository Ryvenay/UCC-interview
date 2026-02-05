<script setup>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import { deleteEvent } from "@/api/events";

const props = defineProps(['events'])
const emit = defineEmits(['event-updated', 'edit-event'])


async function removeEvent(id) {
  await deleteEvent(id);
  emit('event-updated')
}

async function editEvent(data) {
  emit('edit-event', data)
}

</script>

<template>
  <DataTable :value="events" stripedRows responsiveLayout="scroll">
    <Column field="name" header="Title" />
    <Column field="occurrence" header="Date & Time" />

    <Column header="Actions">
      <template #body="{ data }">
        <Button
          icon="pi pi-pencil"
          class="p-button-text"
          @click="editEvent(data)"
        />
        <Button
          icon="pi pi-trash"
          severity="danger"
          class="p-button-text"
          @click="removeEvent(data.id)"
        />
      </template>
    </Column>
  </DataTable>
</template>

<style scoped>
</style>