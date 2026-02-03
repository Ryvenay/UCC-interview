<script setup>
import { ref, onMounted } from "vue";
import { fetchEvents } from "@/api/events";
import EventList from "@/components/EventList.vue";
import EventForm from "@/components/EventForm.vue";

const events = ref([]);

async function loadEvents() {
  const { data } = await fetchEvents();
  events.value = data.data ?? data; // supports resource or raw JSON
}

onMounted(loadEvents);
</script>

<template>
  <div class="my-8">
    <h1 class="text-4xl">Your events</h1>
  </div>


  <EventForm @event-updated="loadEvents()"/>

  <EventList @event-updated="loadEvents()" :events="events" class="mt-2"/>
</template>
