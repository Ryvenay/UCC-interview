<script setup>
import { ref } from "vue";
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import DatePicker from 'primevue/datepicker';
import Textarea from 'primevue/textarea';
import Message from 'primevue/message';
import { createEvent } from "@/api/events";

const emit = defineEmits(['event-updated'])

const name = ref("");
const occurrence = ref("");
const description = ref("");
const error = ref(null);

const visible = ref(false);


async function addEvent() {
    try {
        await createEvent({
            name: name.value,
            occurrence: occurrence.value,
            description: description.value,
        });
        visible.value = false; // fix here
        error.value = null;    // fix here


        name.value = "";
        occurrence.value = "";
        description.value = "";
    }
    catch (e) {
        console.log(e)
        error.value = "Error creating event";
    }

    emit('event-updated')
}

</script>

<template>
    <Button icon="pi pi-plus" label="Add event" variant="outlined" class="text-right" @click="visible = true" />

    <Dialog v-model:visible="visible" modal header="Edit Event" :style="{ width: '25rem' }">
        <form @submit.prevent="addEvent">

            <div class="flex items-center gap-4 mb-4">
                <label for="name" class="font-semibold w-24">Name</label>
                <InputText v-model="name" id="name" class="flex-auto" autocomplete="off" />
            </div>

            <div class="flex items-center gap-4 mb-4">
                <label for="occurrence" class="font-semibold w-24">Occurrence</label>
                <DatePicker v-model="occurrence" showTime id="occurrence" class="flex-auto" autocomplete="off" />
            </div>

            <div class="flex items-center gap-4 mb-4">
                <label for="description" class="font-semibold w-24">Description</label>
                <Textarea v-model="description" showTime id="description" class="flex-auto" autocomplete="off" />
            </div>


            <Message v-if="error" severity="error" class="mt-3">
                {{ error }}
            </Message>

            <Button icon="pi pi-plus" label="Save" class="w-full my-4" type="submit" />
        </form>
    </Dialog>

</template>

<style scoped></style>