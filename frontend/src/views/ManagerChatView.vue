<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import { useRoute } from 'vue-router';
import { fetchChatMessages, sendManagerChatMessage } from "@/api/chat";
import ChatComponent from "@/components/ChatComponent.vue";

const route = useRoute();
const chat_id = route.params.id;


const messages = ref([]);
const error = ref(null);
const loading = ref(false);

let pollingInterval = null;

// Load chat messages
async function loadMessages() {
  const latest = messages.value.length ? messages.value[messages.value.length - 1].id : 0;

  try {
    loading.value = true;
    const { data } = await fetchChatMessages(chat_id, latest);
    messages.value = data.data.messages;
  } catch (e) {
    error.value = "Failed to load messages.";
  } finally {
    loading.value = false;
  }
}


async function sendMessage(message) {
  if (!message.trim()) return;
  try {
    const { data } = await sendManagerChatMessage(chat_id, message);
    loadMessages();
  } catch (e) {
    error.value = "Failed to send message.";
  }
}


async function prepare() {
  loadMessages();
}

onMounted(() => {
  prepare()

  pollingInterval = setInterval(loadMessages, 1000); // poll every second
});

onBeforeUnmount(() => {
  clearInterval(pollingInterval);
});

</script>

<template>
  <div class="chat-wrapper flex flex-col border rounded-lg w-full mt-8">
    <!-- Chat header -->
    <div class="chat-header px-4 py-2 font-bold flex items-center justify-between">
      <div>Manager Helpdesk Chat</div>
    </div>

    <ChatComponent :messages="messages" @message:send="sendMessage" />
  </div>

</template>

<style scoped>
.chat-wrapper {
  height: 80vh;
}
</style>