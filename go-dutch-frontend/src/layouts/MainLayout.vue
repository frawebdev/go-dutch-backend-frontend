<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>

        <q-toolbar-title>
          Go Dutch
        </q-toolbar-title>

        <div>
          <q-btn 
          flat
          dense
          round
          icon="arrow_back"
          @click="$router.go(-1)"
          />
        </div>
      </q-toolbar>
    </q-header>

    <q-page-container class="q-px-sm q-my-lg">
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { defineComponent, ref } from 'vue'
import { useRouter } from 'vue-router'
import EssentialLink from 'components/EssentialLink.vue'
import axios from 'axios'
import { Cookies } from 'quasar'

const linksList = [
  {
    title: 'Docs',
    caption: 'quasar.dev',
    icon: 'school',
    link: '/auth'
  }
]

export default defineComponent({
  name: 'MainLayout',

  components: {
    EssentialLink
  },

  setup () {
    const leftDrawerOpen = ref(false)
    const router = useRouter()

    const logout = async () => {
      const token = await Cookies.get('token')

      const config = {
          headers: { Authorization: `Bearer ${token}` }
      }

      axios.post('http://127.0.0.1:8000/api/logout', {}, config)
        .then(() => {
          Cookies.remove('user')
          Cookies.remove('token')
          router.push('/auth')
        })
        .catch(err => console.log(err))
    }

    return {
      essentialLinks: linksList,
      leftDrawerOpen,
      toggleLeftDrawer () {
        leftDrawerOpen.value = !leftDrawerOpen.value
      },
      logout
    }
  }
})
</script>
