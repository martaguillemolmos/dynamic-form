<template>
  <div class="container my-5">
    <h1 class="mb-4">Registros del formulario</h1>

    <div v-if="loading" class="text-center my-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Cargando...</span>
      </div>
      <p class="mt-2">Cargando registros...</p>
    </div>

    <div v-if="error" class="alert alert-danger">
      Error al cargar los registros: {{ error }}
      <button class="btn btn-sm btn-outline-danger ms-3" @click="fetchResponses">
        Reintentar
      </button>
    </div>

    <table v-if="responses.length && !loading" class="table table-striped table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>Datos del registro</th>
          <th>Fecha creación</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(response, index) in responses" :key="response._id">
          <td>{{ index + 1 }}</td>
          <td>
            <pre>{{ formatResponse(response) }}</pre>
          </td>
          <td>{{ formatDate(response.created_at) }}</td>
        </tr>
      </tbody>
    </table>

    <p v-if="!loading && !responses.length" class="text-muted">No hay registros para este formulario.</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const responses = ref([])
const loading = ref(false)
const error = ref(null)

// Obtenemos el id a través de la URL

const urlSegments = window.location.pathname.split('/')
const formId = urlSegments[urlSegments.length - 1]

function formatResponse(response) {
  // Muestra todos los campos excepto id, form_id, created_at en formato JSON
  const { id, form_id, created_at, ...data } = response
  return JSON.stringify(data, null, 2)
}

function formatDate(dateString) {
  return new Date(dateString).toLocaleString()
}

async function fetchResponses() {
  loading.value = true
  error.value = null

  try {
    const res = await axios.get(`/api/form-templates/${formId}/responses`)
    responses.value = res.data || []
  } catch (err) {
    error.value = err.response?.data?.message || err.message || 'Error al conectar con el servidor'
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchResponses()
})
</script>

<style scoped>
pre {
  background-color: #f8f9fa;
  padding: 0.5em;
  border-radius: 0.25rem;
  white-space: pre-wrap;
  word-break: break-word;
  max-width: 100%;
}
</style>
