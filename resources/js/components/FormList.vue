<template>
  <div class="container my-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h1 class="fw-bold mb-0">Lista de Formularios</h1>
      <button
        type="button"
        class="btn btn-primary px-4"
        @click="navigateToFormCreation"
      >
        <i class="bi bi-file-earmark-plus-fill me-2"></i> Crear nuevo formulario
      </button>
    </div>

    <div v-if="loading" class="text-center my-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Cargando...</span>
      </div>
      <p class="mt-2">Cargando formularios...</p>
    </div>

    <div v-else-if="error" class="alert alert-danger mt-4">
      Error al cargar los formularios: {{ error }}
      <button @click="cargarFormularios" class="btn btn-sm btn-outline-danger ms-3">
        Reintentar
      </button>
    </div>

    <table v-else class="table table-hover align-middle">
      <thead class="table-light text-uppercase">
        <tr>
          <th>Nombre</th>
          <th class="text-center">Acciones</th>
          <th class="text-center">Registros</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="form in forms" :key="form.id">
          <td class="fw-semibold">{{ form.name }}</td>

          <td class="text-center">
            <button
              type="button"
              class="btn btn-outline-secondary btn-sm me-2"
              @click="navigateToFormView(form.id)"
              data-bs-toggle="tooltip"
              data-bs-placement="top"
              title="Visualizar formulario"
            >
              <i class="bi bi-eye-fill"></i>
            </button>

            <button
              type="button"
              class="btn btn-outline-primary btn-sm me-2"
              @click="navigateToFormEdit(form.id)"
              data-bs-toggle="tooltip"
              data-bs-placement="top"
              title="Editar formulario"
            >
              <i class="bi bi-pencil-fill"></i>
            </button>

            <button
              type="button"
              class="btn btn-outline-danger btn-sm"
              @click="eliminarFormulario(form.id)"
              data-bs-toggle="tooltip"
              data-bs-placement="top"
              title="Eliminar formulario"
            >
              <i class="bi bi-trash-fill"></i>
            </button>
          </td>

          <td class="text-center">
            <button
              type="button"
              class="btn btn-success btn-sm"
              @click="navigateToNewResponse(form.id)"
              data-bs-toggle="tooltip"
              data-bs-placement="top"
              title="Nuevo registro"
            >
              <i class="bi bi-plus-lg"></i> Nuevo
            </button>
          </td>
        </tr>
        <tr v-if="forms.length === 0">
          <td colspan="3" class="text-center text-muted py-4">
            No hay formularios disponibles
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue'
import axios from 'axios'
import * as bootstrap from 'bootstrap/dist/js/bootstrap.bundle.min.js'

const forms = ref([])
const loading = ref(true)
const error = ref(null)

// Navegación dentro de la vista: Detalle de las mismas en web.php
const navigateToFormCreation = () => {
  window.location.href = '/form-new/'
}

const navigateToFormView = (formId) => {
  window.location.href = `/form/${formId}`
}

const navigateToFormEdit = (formId) => {
  window.location.href = `/form-edit/${formId}`
}

const navigateToNewResponse = (formId) => {
  window.location.href = `/new-responses/${formId}`
}

// Métodos de api.php
async function cargarFormularios() {
  loading.value = true
  error.value = null
  
  try {
    const res = await axios.get('/api/form-templates')
    
    if (!res.data) throw new Error('La API no devolvió datos')
    forms.value = Array.isArray(res.data) ? res.data : []
    
    await nextTick()
    initializeTooltips()
  } catch (err) {
    console.error('Error al cargar formularios:', err)
    error.value = err.response?.data?.message || err.message || 'Error al conectar con el servidor'
    forms.value = []
  } finally {
    loading.value = false
  }
}

async function eliminarFormulario(id) {
  if (!confirm('¿Estás seguro de que quieres eliminar este formulario?')) return
  
  try {
    await axios.delete(`/api/form-templates/${id}`)
    forms.value = forms.value.filter(form => form.id !== id)
    alert('Formulario eliminado correctamente')
  } catch (error) {
    alert(`Error al eliminar formulario: ${error.message}`)
  }
}

function initializeTooltips() {
  try {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
      new bootstrap.Tooltip(el)
    })
  } catch (e) {
    console.error('Error inicializando tooltips:', e)
  }
}

onMounted(() => {
  cargarFormularios()
})
</script>

<style scoped>
.table-hover tbody tr:hover {
  background-color: #f5f5f5;
  transition: background-color 0.3s ease;
}

.spinner-border {
  width: 48px;   
  height: 48px;  
}

.btn-sm {
  padding: 4px 8px;      
  font-size: 14px;       
}
</style>