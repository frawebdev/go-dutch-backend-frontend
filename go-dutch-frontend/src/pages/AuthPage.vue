<template>
    <div class="row">
        <div class="col"></div>
        <q-card class="my-card col-12 col-sm-8 col-md-6 q-ma-sm">
        <q-card-section>
            <q-form
            @submit.prevent="auth"
            class="q-gutter-sm"
            >
                <q-input 
                v-if="!registered"
                filled
                type="text"
                label="Your name"
                v-model="name"
                lazy-rules
                :rules="
                [ 
                val => val && val.length > 0  || 'Type here your name',
                val => val && val.length > 8  || 'Your name should be at least 8 characters long'
                ]"
                />
                <q-input 
                filled
                type="email"
                label="Your email"
                v-model="email"
                lazy-rules
                :rules="[ 
                val => val && val.length > 0  || 'Type here your email'
                ]"
                />
                <q-input 
                filled
                type="password"
                label="Your password"
                v-model="password"
                lazy-rules
                :rules="[ 
                val => val && val.length > 0  || 'Type here your password' 
                ]"
                />
                <div>
                    <q-btn label="Submit" type="submit" color="primary" />
                </div>
            </q-form>
        </q-card-section>
        <q-separator />
        <q-card-actions align="around">
            <q-btn 
            v-if="!registered"
            @click="registered = true"
            >
                I'm already registered
            </q-btn>
            <q-btn
            v-else
            @click="registered = false"
            >
                I'm not registered yet
            </q-btn>
        </q-card-actions>
        </q-card>
        <div class="col"></div>
    </div>
</template>

<script setup>
//packages
import { ref } from 'vue'
import axios from 'axios'
import { Cookies } from 'quasar'
import { useRouter } from 'vue-router'

const router = useRouter()

const name = ref(null)
const email = ref(null)
const password = ref(null)
const registered = ref(false)

const auth = () => {
    let data
    let route
    
    if(!registered.value) {
        data = {
            name: name.value,
            email: email.value,
            password: password.value
        }
        route = '/api/register'
    } else {
        data = {
            email:email.value,
            password: password.value
        }
        route = '/api/login'
    }
    
    axios.post('http://127.0.0.1:8000' + route, data)
        .then(res => {
            Cookies.set('token', res.data.token, { expires: 7 })
            axios.get('http://127.0.0.1:8000/api/user', { headers: { Authorization: 'Bearer ' + Cookies.get('token') } })
            .then(res => {
            Cookies.set('user', res.data)
            router.push('/')
            })
        })
        .catch(err => { console.log(err) })
}

</script>