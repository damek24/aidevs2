<script setup lang="ts">
import {ref} from "vue";

const api_key= ref('')
const base_url = 'https://zadania.aidevs.pl/'
const auth_response = ref('')

const doRequest = async (url: string, data: object) => {
    const request = new Request(base_url + url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    return await fetch(request).then(r => r.json())
}

const getTask = async () => {
    const data = {
        apikey: api_key.value
    }
    auth_response.value = await doRequest('token/helloapi', data)
}
</script>

<template>
    <div class="container bg-secondary-subtle rounded p-5">
        <label>
            <p>Api token</p>
            <input class="form-control form-control-lg" v-model="api_key">
        </label>
        <button @click="getTask"  class="btn btn-primary">Authorize</button>
        <p>Auth response:</p>
        <p>{{ auth_response }}</p>
    </div>
</template>

<style scoped>

</style>
