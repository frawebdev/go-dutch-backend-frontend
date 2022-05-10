<template>
    <q-page>
        <div class="row" v-if="participant">
            <div class="col"></div>
                <q-list
                separator
                bordered
                class="my-card col-12 col-sm-8 col-md-6"
                v-for="transaction in participant.transactions" :key="transaction.id"
                >
                    <q-item>
                        <q-item-section>
                            <q-item-label>{{ transaction.category }}</q-item-label>
                            <q-item-label>{{ transaction.amount }}</q-item-label>
                        </q-item-section>
                        <q-item-section side top
                        @click="removeTransaction(transaction.id, transaction.amount)"
                        >
                            <q-icon name="close"/>
                        </q-item-section>
                    </q-item>
                </q-list>
            <div class="col"></div>
        </div>
    </q-page>
</template>

<script>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import { api } from 'boot/axios'
import { Cookies } from 'quasar'

export default {
    setup() {   
        const participant = ref(null)
        const router = useRoute()

        onMounted( async () => {
            const config = {
                headers: { Authorization: `Bearer ${Cookies.get('token')}` }
            }

            participant.value = await axios.get( api.defaults.baseURL  + '/transactions/' + router.params.id, config)
                .then(res => res.data)
                .catch(err => console.log(err))
        })

        const removeTransaction = async (transactionId, transaction) => {
            const config = {
                headers: { Authorization: `Bearer ${Cookies.get('token')}` }
            }

            const data = {
                id: router.params.id,
                room_id: router.params.roomId,
                transaction_id: transactionId,
                transaction: transaction
            }

            await axios.put('http://127.0.0.1:8000/api/participant-remove', data, config)
                .then(res => console.log(res))
                .catch(err => console.log(err))

            // await axios.delete('http://127.0.0.1:8000/api/transaction/' + router.params.id, config)
            //     .then(res => console.log(res))
            //     .catch(err => console.log(err))
        }

        return {
            participant,
            removeTransaction
        }
    }
}
</script>