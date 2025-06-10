<template>
  <div class="container my-5" v-if="form">
    <h1 class="mb-4">Editar Formulario</h1>

    <form @submit.prevent="handleUpdate">
      <!-- Nombre del formulario -->
      <div class="mb-4">
        <label class="form-label fw-semibold">Nombre del formulario</label>
        <input
          v-model="form.name"
          type="text"
          class="form-control"
          required
        />
      </div>

      <!-- Campos del formulario -->
      <div v-for="(field, index) in form.fields" :key="index" class="mb-4 border p-3 rounded">
        <div class="mb-2">
          <label class="form-label">Etiqueta</label>
          <input v-model="field.label" class="form-control" required />
        </div>

        <div class="mb-2">
          <label class="form-label">Nombre (identificador)</label>
          <input v-model="field.name" class="form-control" required />
        </div>

        <div class="mb-2">
          <label class="form-label">Tipo</label>
          <select v-model="field.type" class="form-select" required>
            <option value="text">Texto</option>
            <option value="number">Número</option>
            <option value="email">Correo</option>
            <option value="textarea">Área de texto</option>
            <option value="select">Selector</option>
          </select>
        </div>

        <div v-if="field.type === 'select'" class="mb-2">
          <label class="form-label">Opciones (separadas por coma)</label>
          <input
            v-model="field.optionsText"
            class="form-control"
            placeholder="Ej: Opción1, Opción2"
          />
        </div>

        <div class="form-check mt-2">
          <input
            class="form-check-input"
            type="checkbox"
            v-model="field.required"
          />
          <label class="form-check-label">Requerido</label>
        </div>

        <button
          class="btn btn-outline-danger btn-sm mt-3"
          @click.prevent="removeField(index)"
        >
          Eliminar campo
        </button>
      </div>

      <button class="btn btn-secondary mb-4" @click.prevent="addField">
        Agregar campo
      </button>

      <button type="submit" class="btn btn-primary">Guardar cambios</button>
    </form>
  </div>

  <div v-else class="container my-5">
    <div class="alert alert-info">Cargando formulario para edición...</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

// Obtenemos el id a través de la URL
const pathParts = window.location.pathname.split('/')
const formId = ref(pathParts[pathParts.length - 1])

const form = ref(null)

onMounted(async () => {
  try {
    const response = await axios.get(`/api/form-templates/${formId.value}`)
    form.value = response.data

    // Convertir options en texto (para edición)
    form.value.fields.forEach(field => {
      if (field.type === 'select') {
        field.optionsText = field.options?.join(', ') || ''
      }
    })
  } catch (error) {
    alert('Error al cargar el formulario')
    console.error(error)
  }
})

const addField = () => {
  form.value.fields.push({
    label: '',
    name: '',
    type: 'text',
    required: false,
    optionsText: ''
  })
}

const removeField = (index) => {
  form.value.fields.splice(index, 1)
}

const handleUpdate = async () => {
  try {
    // Validar campos
    form.value.fields.forEach(field => {
      if (field.type === 'select') {
        field.options = field.optionsText.split(',').map(opt => opt.trim()).filter(Boolean)
      }
    })

    const payload = {
      name: form.value.name,
      fields: form.value.fields.map(({ label, name, type, required, options }) => ({
        label,
        name,
        type,
        required,
        ...(type === 'select' ? { options } : {})
      }))
    }

    await axios.put(`/api/form-templates/${formId.value}`, payload)

    alert('Formulario actualizado correctamente')
    window.location.href = '/'
  } catch (error) {
    alert('Error al actualizar el formulario')
    console.error(error)
  }
}
</script>
