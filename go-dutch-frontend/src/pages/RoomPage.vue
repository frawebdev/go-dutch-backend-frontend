<template>
    <q-page>
        <div class="row">
        <div class="col"></div>
        <q-card 
        clickable
        v-ripple
        class="my-card col-12 col-sm-8 col-md-6"
        >
            <q-card-section>
            <q-list separator v-if="room">
                <q-item v-for="participant in room.participants" :key="participant.id" class="q-py-md">
                <q-item-section top avatar>
                    <q-avatar rounded>
                        <img src="https://cdn.quasar.dev/img/boy-avatar.png">
                    </q-avatar>
                </q-item-section>
                <q-item-section>
                    <q-item-label>{{ participant.name }}</q-item-label>
                </q-item-section>
                <q-item-section side>
                    <q-badge color="teal" :label="participant.payback + ' payback'"/>
                    <q-badge color="teal" :label="participant.money_owed + ' owed'" class="q-my-sm"/>
                    <q-badge color="teal" :label="participant.money_spent + ' spent'" />
                </q-item-section>
                </q-item>
            </q-list>
            </q-card-section>
        </q-card>
        <div class="col"></div>
        </div>
        <q-page-sticky 
        position="bottom-right" 
        :offset="[18, 18]"
        @click="addExpense = true"
        >
        <q-btn fab icon="add" color="primary" />
        </q-page-sticky>
        <q-page-sticky 
        v-if="currentParticipant"
        position="bottom" 
        :offset="[18, 18]"
        @click="$router.push({ name: 'transaction', params: { id: currentParticipant.id, roomId: $route.params.id  } })"
        >
        <q-btn 
        fab icon="list" 
        color="primary" 
        />
        </q-page-sticky>
        <q-dialog v-model="addExpense">
            <q-card style="min-width: 350px;">
                <q-card-section class="q-gutter-y-md">
                    <q-input filled type="number" v-model="expense" label="Add your expense" />
                    <q-btn color="primary" class="full-width" label="Add" @click="registerExpense"/>
                </q-card-section>
            </q-card>
        </q-dialog>
    </q-page>
</template>

<script>
import axios from 'axios'
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { Cookies } from 'quasar'

export default {
    setup() {
        const route = useRoute()
        const room = ref(null)
        const currentParticipant = ref(null)
        const expense = ref(0)
        const user = Cookies.get('user')

        onMounted( async () => {
            const config = {
                headers: { Authorization: `Bearer ${Cookies.get('token')}` }
            }

            room.value = await axios.get('http://127.0.0.1:8000/api/room/' + route.params.id, config)
                .then(res => res.data)
                .catch(err => console.log(err))

            currentParticipant.value = room.value.participants.filter(part => part.user_id == user.id)
            currentParticipant.value = currentParticipant.value[0]
        })

        const registerExpense = () => {
            const config = {
                headers: { Authorization: `Bearer ${Cookies.get('token')}` }
            }

            const data = {
                user_id: user.id,
                room: room.value,
                expense: expense.value
            }

            axios.put('http://127.0.0.1:8000/api/participant-add', data, config)
                .then(res => console.log(res))
                .catch(err => console.log(err))
        }

        return {
            room,
            currentParticipant,
            addExpense: ref(false),
            expense,
            registerExpense
        }
    }
}
</script>