import api from '@/api/client';

export async function loginRequest(email, password) {
  const { data } = await api.post('/auth/login', { email, password });

  return {
    token: data.token,
    user: data.user,
  };
}

export async function fetchMe() {
  const { data } = await api.get('/user');
  return data;
}
