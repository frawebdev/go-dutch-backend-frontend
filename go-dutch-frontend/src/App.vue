<template>
  <router-view />
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'App'
})
</script>

<script setup>
import { onMounted } from 'vue'
import axios from 'axios'
import { Cookies } from 'quasar'

onMounted(() => {
  axios.get('http://127.0.0.1:8000/api/user', { headers: { Authorization: 'Bearer ' + Cookies.get('token') } })
    .then(res => {
      Cookies.set('user', res.data)
    })
    .catch(() => {
      if(Cookies.has('user')) {
        Cookies.remove('user')
      }
    })
})

</script>
