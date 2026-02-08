import { createRouter, createWebHistory } from 'vue-router';
import LoginView from '@/views/LoginView.vue';
import EventsView from '@/views/EventsView.vue';
import { useAuthStore } from '@/stores/auth';
import ForgotPassword from '@/views/ForgotPassword.vue';
import ResetLinkSent from '@/views/ResetLinkSent.vue';
import ResetPassword from '@/views/ResetPassword.vue';
import ChatView from '@/views/ChatView.vue';
import Helpdesk from '@/views/Helpdesk.vue';

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/login',
      component: LoginView,
      meta: { guestOnly: true }
    },
    {
      path: '/',
      component: EventsView,
      meta: { requiresAuth: true },
    },
    {
      path: '/forgot-password',
      component: ForgotPassword,
      meta: { guestOnly: true },
    },
    {
      path: '/reset-link-sent',
      component: ResetLinkSent,
      meta: { guestOnly: true },
    },
    {
      path: '/reset-password',
      component: ResetPassword,
      meta: { guestOnly: true },
    },
    {
      path: '/helpdesk',
      component: ChatView,
      meta: { requiresAuth: true },
    },
    {
      path: '/admin/helpdesk',
      component: Helpdesk,
      meta: { requiresAuth: true },
    },
  ],
});

router.beforeEach((to, from, next) => {
  const auth = useAuthStore();

  //   if (to.meta.requiresAuth && !localStorage.getItem('token')) {
  //   return '/login';
  // }

  if (to.meta.guestOnly && auth.isAuthenticated) {
    return next('/');
  }

  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    return next('/login');
  }

  next();
});

export default router;
