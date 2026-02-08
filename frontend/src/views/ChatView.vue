<script setup>
import { ref, onMounted, onBeforeUnmount, nextTick } from "vue";
import { fetchChatMessages, sendChatMessage, getChat, transferToHuman } from "@/api/chat";
import Button from "primevue/button";
import InputText from "primevue/inputtext";

const chat_id = ref(null);

const messages = ref([]);
const newMessage = ref("");
const error = ref(null);
const loading = ref(false);
const chatContainer = ref(null);

let pollingInterval = null;

// Load chat messages
async function loadMessages() {
  const latest = messages.value.length ? messages.value[messages.value.length - 1].id : 0;

  try {
    loading.value = true;
    const { data } = await fetchChatMessages(chat_id.value, latest);
    messages.value = data.data.messages;
    await nextTick();
    scrollToBottom();
  } catch (e) {
    error.value = "Failed to load messages.";
  } finally {
    loading.value = false;
  }
}

function scrollToBottom() {
  if (chatContainer.value) {
    chatContainer.value.scrollTop = chatContainer.value.scrollHeight;
  }
}

async function sendMessage() {
  if (!newMessage.value.trim()) return;

  try {
    const { data } = await sendChatMessage(chat_id.value, newMessage.value);
    messages.value.push(data);
    newMessage.value = "";
    await nextTick();
    scrollToBottom();
  } catch (e) {
    error.value = "Failed to send message.";
  }
}

async function requestHuman() {
  try {
    const { data } = await transferToHuman(chat_id.value)
    alert(data.message);
  }
  catch (e) {
    console.log(e)
    alert('Failed to transfer chat');
  }
}

async function prepare() {
  const { data } = await getChat();
  chat_id.value = data.chat_id
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
    <div class="chat-header px-4 py-2 font-bold">Helpdesk Chat</div>

    <!-- Chat messages -->
    <div class="chat-messages flex-1 overflow-y-auto p-4 space-y-2" ref="chatContainer">
      <div v-for="(msg, index) in messages" :key="index" :class="msg.role === 'user' ? 'text-right' : 'text-left'">
        <div :class="msg.role === 'user' ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-900'"
          class="inline-block px-3 py-2 rounded-lg max-w-[70%] break-words">
          {{ msg.content }}
        </div>
      </div>
    </div>

    <!-- Input field -->
    <div class="chat-input flex border-t border-gray-200 p-2 gap-2">
      <InputText v-model="newMessage" placeholder="Type your message..." class="flex-1" />
      <Button label="Send" icon="pi pi-send" @click="sendMessage" />
      <Button label="Request human" icon="pi pi-user" @click="requestHuman" />
    </div>

    <div v-if="error" class="text-red-500 text-sm p-2">{{ error }}</div>
  </div>
</template>

<style scoped>
.chat-wrapper {
  height: 80vh;
}

.chat-messages::-webkit-scrollbar {
  width: 6px;
}

.chat-messages::-webkit-scrollbar-thumb {
  background-color: rgba(0, 0, 0, 0.2);
  border-radius: 3px;
}
</style>
