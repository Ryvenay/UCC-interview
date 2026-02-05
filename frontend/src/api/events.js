import api from './client';

export function fetchEvents() {
  return api.get('/events');
}

export function createEvent(data) {
  return api.post('/events', data);
}

export function updateEvent(id, data) {
  return api.put(`/events/${id}`, data);
}

export function deleteEvent(id) {
  return api.delete(`/events/${id}`);
}
