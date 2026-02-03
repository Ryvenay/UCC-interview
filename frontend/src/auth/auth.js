import api from '@/api/client';

export async function login(email, password) {
  const { data } = await api.post('/auth/login', { email, password });
  localStorage.setItem('token', data.token);
  return data.user;
}

export function logout() {
  localStorage.removeItem('token');
}