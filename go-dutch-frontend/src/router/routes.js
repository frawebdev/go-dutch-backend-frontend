import { Cookies } from 'quasar'

const requireAuth = (to, from, next) => {
  if(!Cookies.has('user')) {
    next({ name: 'auth' })
  }
  else {
    next()
  }
}

const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { 
        path: '', 
        component: () => import('pages/IndexPage.vue'),
        beforeEnter: requireAuth
      },
      { 
        path: '/auth', 
        component: () => import('pages/AuthPage.vue'),
        name: 'auth'
      },
      { 
        path: '/create-room', 
        component: () => import('pages/CreateRoomPage.vue'),
        name: 'create-room',
        beforeEnter: requireAuth
      },
      {
        path: '/room/:id',
        component: () => import('pages/RoomPage.vue'),
        name: 'room',
        beforeEnter: requireAuth
      },
      {
        path: '/transactions/:id',
        component: () => import('pages/TransactionPage.vue'),
        name: 'transaction',
        beforeEnter: requireAuth,
        props: true
      }
    ]
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes
