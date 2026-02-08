import api from "@/api/client";

export function fetchChatMessages(chatId, lastId = 0) {
  return api.get(`/chats/${chatId}`, {
    params: { last_id: lastId }
  });
}

export function sendChatMessage(chatId, message) {
  return api.post(`/chats/${chatId}/messages`, { message });
}


export function getChat() {
  return api.get(`/chat/`);
}

export function transferToHuman() {
  return api.post(`/chats/${chatId}/transfer`);
}