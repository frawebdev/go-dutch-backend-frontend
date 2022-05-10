<template>
    <q-page>
        <div class="row">
        <div class="col"></div>
            <q-card class="my-card col-12 col-sm-8 col-md-6">
                <q-card-section>
                    <q-form
                    class="q-gutter-sm"
                    >
                    <q-input 
                    filled 
                    v-model="roomName" 
                    label="Write your room's name" 
                    lazy-rules
                    :rules="
                    [ 
                    val => val && val.length > 8  || 'Your name should be at least 8 characters long'
                    ]"
                    />
                    <q-input filled v-model="search" label="Search for a user" />
                    <q-list separator v-if="searchResults">
                            <q-separator spaced />
                            <q-item 
                            clickable
                            v-ripple
                            v-for="user in searchResults" :key="user.id"
                            @click="storeUser(user)"
                            >
                                <q-item-section top avatar>
                                <q-avatar rounded>
                                    <img src="https://cdn.quasar.dev/img/boy-avatar.png">
                                </q-avatar>
                                </q-item-section>

                                <q-item-section>
                                <q-item-label>{{ user.name }}</q-item-label>
                                <q-item-label caption>{{ user.email }}</q-item-label>
                                </q-item-section>
                            </q-item>
                            <q-separator spaced />
                            <q-expansion-item
                            v-if="storedUser.length > 0"
                            expand-separator
                            label="Your selected users"
                            >
                            <q-list>
                                <q-item v-for="user in storedUser" :key="user.id">
                                    <q-item-section>
                                        {{ user.name }}
                                    </q-item-section>
                                    <q-item-section side>
                                        <q-icon 
                                        @click="removeStoredUser(user)"
                                        name="close" 
                                        />
                                    </q-item-section>
                                </q-item>
                            </q-list>
                            </q-expansion-item>
                            <div v-if="noTitleError" class="text-body1 text-negative">
                                Remember to write your room's name
                            </div>
                            <q-btn 
                            color="primary" 
                            class="full-width" 
                            label="Create room" 
                            @click="createRoom"
                            />
                    </q-list>
                    <q-spinner 
                    v-if="!searchResults && search.length >= 3"
                    color="dark"
                    size="3em"
                    />
                    <div v-if="!searchResults && search.length < 3">
                        Type 3 characters at least to search for a user
                    </div>
                    </q-form>
                </q-card-section>
            </q-card>
        <div class="col"></div>
        </div>
    </q-page>
</template>

<script>
import { ref, watch, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { Cookies } from 'quasar'

export default {
    setup() {
        const router = useRouter()

        const roomName = ref('')
        const search = ref('')
        const searchResults = ref(null)
        const storedUser = ref([])
        const noTitleError = ref(false)

        const storeUser = (user) => {
            if(!storedUser.value.includes(user) || storedUser.value.length < 1) {
                storedUser.value.push(user)
            }
            else {
                const userIndex = storedUser.value.indexOf(user)
                storedUser.value.splice(userIndex, 1)
            }
        }

        const createRoom = () => {
            storedUser.value.push(Cookies.get('user'))
            if(!roomName.value) {
                noTitleError.value = true
                return
            }
            const storedUserIds = storedUser.value.map(item => item['id'])
            const config = {
                headers: { Authorization: `Bearer ${Cookies.get('token')}` }
            }

            const data = {
                title: roomName.value,
                users: storedUserIds,
                admin: Cookies.get('user')
            }

            axios.post('http://127.0.0.1:8000/api/create-room', data, config)
                .then(res => {
                    console.log(res.data)
                    // router.push({ name: 'home', params: { message: 'Your new room was successfully created!' } })
                })
                .catch(err => console.log(err))
        }

        const removeStoredUser = (user) => {
            const userIndex = storedUser.value.indexOf(user)
            storedUser.value.splice(userIndex, 1)
        }

        watch(search, async(newVal, oldVal) => {
            const config = {
                headers: { Authorization: `Bearer ${Cookies.get('token')}` }
            }

            if(newVal.length >= 3) {
                searchResults.value = await axios.get('http://127.0.0.1:8000/api/search/' + newVal, config)
                    .then(res => {
                        return res.data
                    })
                    .catch(err => console.log(err))
            }
            else {
                    searchResults.value = null
            }

            console.log(searchResults.value)
        })

        return {
            search,
            searchResults,
            storeUser,
            roomName,
            storedUser,
            createRoom,
            removeStoredUser,
            noTitleError
        }
    }
}
</script>