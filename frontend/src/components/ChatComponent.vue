<script setup>
import { ref } from "vue";

const props = defineProps(['messages'])

const newMessage = ref("");

async function sendmessage()
{

}

function scrollToBottom() {
  if (chatContainer.value) {
    chatContainer.value.scrollTop = chatContainer.value.scrollHeight;
  }
}

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
