<template>
  <div v-if="form" class="container my-5">
    <h1 class="mb-4">{{ form.name }}</h1>

    <form @submit.prevent="handleSubmit" novalidate>
      <div v-for="field in form.fields" :key="field.name" class="mb-4">
        <label :for="field.name" class="form-label fw-semibold">{{ field.name }}</label>

        <!-- Inputs normales -->
        <input
          v-if="['text', 'number', 'email'].includes(field.type)"
          :type="field.type"
          :name="field.name"
          v-model="formData[field.name]"
          :required="field.required || false"
          class="form-control"
        />

        <!-- Textarea -->
        <textarea
          v-else-if="field.type === 'textarea'"
          :name="field.name"
          v-model="formData[field.name]"
          :required="field.required || false"
          class="form-control"
          rows="4"
        ></textarea>

        <!-- Select -->
        <select
          v-else-if="field.type === 'select'"
          :name="field.name"
          v-model="formData[field.name]"
          :required="field.required || false"
          class="form-select"
        >
          <option disabled value="">Seleccione una opción</option>
          <option v-for="opt in field.options" :key="opt" :value="opt">{{ opt }}</option>
        </select>
      </div>
    </form>
  </div>

  <div v-else class="container my-5">
    <div class="alert alert-info">Cargando formulario...</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const form = ref(null)
const formData = ref({})
const loading = ref(true)
const submitStatus = ref(null) 

// Extrae el ID de la ruta
const pathParts = window.location.pathname.split('/')
const formId = ref(pathParts[pathParts.length - 1])

onMounted(async () => {
  try {
    if (!formId.value) throw new Error('No se encontró ID en la URL')

    const response = await axios.get(`/api/form-templates/${formId.value}`)
    form.value = response.data
    
    formData.value = form.value.fields.reduce((acc, field) => {
      acc[field.name] = field.defaultValue || '' 
      return acc
    }, {})
    
  } catch (error) {
    console.error('Error:', error)
    alert(`Error al cargar el formulario: ${error.message}`)
  } finally {
    loading.value = false
  }
})

const handleSubmit = async () => {
  try {
    submitStatus.value = 'sending'
    
    // Validación básica
    if (!formId.value || !form.value) {
      throw new Error('Formulario no cargado correctamente')
    }

    const requiredFields = form.value.fields.filter(f => f.required)
    for (const field of requiredFields) {
      if (!formData.value[field.name]?.trim()) {
        throw new Error(`El campo ${field.name} es requerido`)
      }
    }

    const response = await axios.post(
      `/api/form-templates/${formId.value}/responses`,
      formData.value,
      {
        headers: {
          'Content-Type': 'application/json'
        }
      }
    )

    console.log('Respuesta del servidor:', response.data)
    submitStatus.value = 'success'
    alert('¡Formulario enviado con éxito!')
    
    // Reseteamos el formulario
    form.value.fields.forEach(field => {
      formData.value[field.name] = ''
    })
    
  } catch (error) {
    submitStatus.value = 'error'
    console.error('Error al enviar:', error)
    
    const errorMsg = error.response?.data?.message || 
                    error.message || 
                    'Error al enviar el formulario'
    
    alert(errorMsg)
  }
}
</script>

<style scoped>
label {
  margin-bottom: 0.5rem;
}
</style>
