<script setup>
import { ref, onMounted } from "vue";
import { fetchEvents } from "@/api/events";
import EventList from "@/components/EventList.vue";
import EventForm from "@/components/EventForm.vue";
import Button from 'primevue/button';

const events = ref([]);

const visible = ref(false);
const prefill = ref({});

async function loadEvents() {
  visible.value=false;
  const { data } = await fetchEvents();
  events.value = data.data ?? data; // supports resource or raw JSON
}


async function editEvent(data) {
  prefill.value = data
  visible.value = true
}


onMounted(loadEvents);
</script>

<template>
  <div class="my-8">
    <h1 class="text-4xl">Your events</h1>
  </div>


  <Button icon="pi pi-plus" label="Add event" variant="outlined" class="text-right" @click="visible = true" />
  <EventForm @event-updated="loadEvents" @update:visible="visible = false" :visible="visible" :prefill="prefill"/>

  <EventList @event-updated="loadEvents" @edit-event="editEvent" :events="events" class="mt-2"/>
</template>
