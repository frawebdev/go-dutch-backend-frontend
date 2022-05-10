<template>
  <q-page>
    <div class="row" v-if="rooms">
      <div class="col"></div>
      <q-card 
      clickable
      v-ripple
      class="my-card col-12 col-sm-8 col-md-6" 
      v-for="room in rooms" 
      :key="room.id"
      >
        <q-card-section>
          <q-list>
            <q-item>
              <q-item-section top avatar>
                <q-avatar rounded>
                    <img src="https://cdn.quasar.dev/img/boy-avatar.png">
                </q-avatar>
              </q-item-section>
              <q-item-section>
                <q-item-label>{{ room.title }}</q-item-label>
                <q-item-label caption>Room's total: {{ room.total }}</q-item-label>
              </q-item-section>
              <q-item-section side>
                <q-btn
                :to="{ name: 'room', params: { id: room.id } }"
                >
                  Enter
                </q-btn>
              </q-item-section>
            </q-item>
          </q-list>
        </q-card-section>
      </q-card>
      <div class="col"></div>
    </div>
    <q-page-sticky position="bottom-right" :offset="[18, 18]">
      <q-btn 
      fab 
      icon="add" 
      color="primary" 
      label="Create room"
      :to="{ name: 'create-room' }"
      />
    </q-page-sticky>
  </q-page>
</template>

<script>
import { ref, defineComponent, onMounted } from 'vue'
import axios from 'axios'
import { Cookies } from 'quasar'

export default defineComponent({
  name: 'IndexPage',
  setup() {
    const rooms = ref(null)

    onMounted(async () => {
      const user = Cookies.get('user')

      const config = {
          headers: { Authorization: `Bearer ${Cookies.get('token')}` }
      }

      rooms.value = await axios.get('http://127.0.0.1:8000/api/rooms/' + user.id, config)
        .then(res => res.data)
        .catch(err => console.log(err))
    })

    return {
      rooms
    }
  }
})
</script>
