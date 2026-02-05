<script setup>
import { ref, watch } from "vue";
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import DatePicker from 'primevue/datepicker';
import Textarea from 'primevue/textarea';
import Message from 'primevue/message';
import { createEvent, updateEvent } from "@/api/events";

const emit = defineEmits(['event-updated', 'update:visible'])
const props = defineProps(['visible', 'prefill'])

const id = ref(null);
const name = ref("");
const occurrence = ref(null);
const description = ref("");

const error = ref(null);


watch(
    () => props.prefill,
    (newValue) => {
        if (!newValue || !newValue.id) {
            reset();
            return;
        }

        id.value = newValue.id;
        name.value = newValue.name;
        occurrence.value = new Date(newValue.occurrence);
        description.value = newValue.description ?? '';
    },
    { immediate: true }
);


async function submit() {
    if (id.value) {
        modifyEvent();
    }
    else {
        addEvent();
    }
}


async function addEvent() {
    try {
        await createEvent({
            name: name.value,
            occurrence: occurrence.value,
            description: description.value,
        });
        reset();
        emit('event-updated')
    }
    catch (e) {
        console.log(e)
        error.value = "Error creating event";
    }
}

async function modifyEvent() {
    try {
        await updateEvent(id.value, {
            name: name.value,
            occurrence: occurrence.value,
            description: description.value,
        });
        reset();
        emit('event-updated')
    }
    catch (e) {
        console.log(e)
        error.value = "Error updating event";
    }
}

async function reset() {
    error.value = null;

    name.value = "";
    occurrence.value = null;
    description.value = "";
    id.value = null;
}

async function update_visible(value) {
    emit('update:visible', value)
}

</script>

<template>
    <Dialog :visible="visible" modal header="Edit Event" :style="{ width: '25rem' }" @update:visible="update_visible">
        <form @submit.prevent="submit">

            <div class="flex items-center gap-4 mb-4">
                <label for="name" class="font-semibold w-24">Name</label>
                <InputText v-model="name" id="name" class="flex-auto" autocomplete="off" required/>
            </div>

            <div class="flex items-center gap-4 mb-4">
                <label for="occurrence" class="font-semibold w-24">Occurrence</label>
                <DatePicker v-model="occurrence" showTime id="occurrence" class="flex-auto" autocomplete="off" required/>
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