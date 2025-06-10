<template>
  <div class="container my-4">
    <h1 class="mb-4">Crear nuevo formulario</h1>

    <form @submit.prevent="handleSubmit" novalidate>
      <!-- Nombre del formulario -->
      <div class="mb-4">
        <label for="formName" class="form-label fw-semibold">
          Nombre del formulario <span class="text-danger">*</span>
        </label>
        <input
          id="formName"
          v-model="form.name"
          type="text"
          required
          class="form-control"
          :class="{ 'is-invalid': touched.name && errors.name }"
          @blur="touched.name = true; validateField('name', form.name, true)"
          placeholder="Introduce el nombre del formulario"
        />
        <div v-if="touched.name && errors.name" class="invalid-feedback">
          {{ errors.name }}
        </div>
      </div>

      <!-- Campos del formulario -->
      <div
        v-for="(field, index) in form.fields"
        :key="index"
        class="mb-4 p-3 border rounded"
        :class="{ 'bg-light-danger': touched.fields[index] && hasFieldErrors(index) }"
      >
        <div class="mb-3">
          <label :for="'label-' + index" class="form-label">
            Etiqueta (label) <span class="text-danger">*</span>
          </label>
          <input
            :id="'label-' + index"
            v-model="field.label"
            type="text"
            required
            class="form-control"
            :class="{ 'is-invalid': touched.fields[index]?.label && errors.fields[index]?.label }"
            @blur="markFieldTouched(index, 'label')"
            placeholder="Etiqueta del campo"
          />
          <div v-if="touched.fields[index]?.label && errors.fields[index]?.label" class="invalid-feedback">
            {{ errors.fields[index].label }}
          </div>
        </div>

        <div class="mb-3">
          <label :for="'name-' + index" class="form-label">
            Nombre (name) <span class="text-danger">*</span>
          </label>
          <input
            :id="'name-' + index"
            v-model="field.name"
            type="text"
            required
            class="form-control"
            :class="{ 'is-invalid': touched.fields[index]?.name && errors.fields[index]?.name }"
            @blur="markFieldTouched(index, 'name')"
            placeholder="Nombre del campo"
          />
          <div v-if="touched.fields[index]?.name && errors.fields[index]?.name" class="invalid-feedback">
            {{ errors.fields[index].name }}
          </div>
        </div>

        <div class="mb-3">
          <label :for="'type-' + index" class="form-label">
            Tipo (type) <span class="text-danger">*</span>
          </label>
          <select
            :id="'type-' + index"
            v-model="field.type"
            required
            class="form-select"
            :class="{ 'is-invalid': touched.fields[index]?.type && errors.fields[index]?.type }"
            @blur="markFieldTouched(index, 'type')"
            @change="field.type === 'select' ? markFieldTouched(index, 'options') : null"
          >
            <option value="text">Texto</option>
            <option value="number">Número</option>
            <option value="email">Email</option>
            <option value="textarea">Área de texto</option>
            <option value="select">Selección</option>
          </select>
          <div v-if="touched.fields[index]?.type && errors.fields[index]?.type" class="invalid-feedback">
            {{ errors.fields[index].type }}
          </div>
        </div>

        <div v-if="field.type === 'select'" class="mb-3">
          <label :for="'options-' + index" class="form-label">
            Opciones (separadas por coma) <span class="text-danger">*</span>
          </label>
          <input
            :id="'options-' + index"
            v-model="field.optionsString"
            type="text"
            class="form-control"
            :class="{ 'is-invalid': touched.fields[index]?.options && errors.fields[index]?.options }"
            @blur="markFieldTouched(index, 'options')"
            placeholder="opción1, opción2, opción3"
          />
          <div v-if="touched.fields[index]?.options && errors.fields[index]?.options" class="invalid-feedback">
            {{ errors.fields[index].options }}
          </div>
        </div>
        
        <div class="form-check mb-2">
          <input
            type="checkbox"
            class="form-check-input"
            v-model="field.required"
            :id="'required-' + index"
          />
          <label class="form-check-label" :for="'required-' + index">
            Requerido
          </label>
        </div>
        
        <button
          type="button"
          class="btn btn-outline-danger btn-sm"
          @click="removeField(index)"
        >
          Eliminar campo
        </button>
      </div>

      <div class="mb-3">
        <button
          type="button"
          class="btn btn-success"
          @click="addField"
        >
          <i class="bi bi-plus-lg me-2"></i> Agregar campo
        </button>
      </div>

      <button type="submit" class="btn btn-primary">
        <i class="bi bi-check-lg me-2"></i> Crear formulario
      </button>
    </form>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import axios from 'axios'


const form = reactive({
  name: '',
  fields: [
    {
      label: '',
      name: '',
      type: 'text',
      optionsString: '',
      required: false
    }
  ]
})

const errors = reactive({
  name: '',
  fields: []
})

const touched = reactive({
  name: false,
  fields: []
})

// Inicializar estados
form.fields.forEach((_, index) => {
  errors.fields[index] = {
    label: '',
    name: '',
    type: '',
    options: ''
  }
  touched.fields[index] = {
    label: false,
    name: false,
    type: false,
    options: false
  }
})

function hasFieldErrors(index) {
  return (
    (touched.fields[index]?.label && errors.fields[index]?.label) ||
    (touched.fields[index]?.name && errors.fields[index]?.name) ||
    (touched.fields[index]?.type && errors.fields[index]?.type) ||
    (touched.fields[index]?.options && errors.fields[index]?.options)
  )
}

function markFieldTouched(index, field) {
  touched.fields[index][field] = true
  validateAllFields()
}

function addField() {
  form.fields.push({
    label: '',
    name: '',
    type: 'text',
    optionsString: '',
    required: false
  })
  
  // Inicializar estados para el nuevo campo
  errors.fields.push({
    label: '',
    name: '',
    type: '',
    options: ''
  })
  touched.fields.push({
    label: false,
    name: false,
    type: false,
    options: false
  })
}

function removeField(index) {
  if (form.fields.length > 1) {
    form.fields.splice(index, 1)
    errors.fields.splice(index, 1)
    touched.fields.splice(index, 1)
  } else {
    alert('Debe haber al menos un campo en el formulario')
  }
}

function validateField(fieldName, value, isRequired) {
  if (isRequired && !value.trim()) {
    errors[fieldName] = 'Este campo es requerido'
    return false
  }
  errors[fieldName] = ''
  return true
}

function validateAllFields() {
  // Validar nombre del formulario
  if (touched.name) {
    validateField('name', form.name, true)
  }
  
  // Validar cada campo
  form.fields.forEach((field, index) => {
    if (touched.fields[index]?.label) {
      if (!field.label.trim()) {
        errors.fields[index].label = 'La etiqueta es requerida'
      } else {
        errors.fields[index].label = ''
      }
    }
    
    if (touched.fields[index]?.name) {
      if (!field.name.trim()) {
        errors.fields[index].name = 'El nombre del campo es requerido'
      } else {
        errors.fields[index].name = ''
      }
    }
    
    if (touched.fields[index]?.type) {
      if (!field.type) {
        errors.fields[index].type = 'El tipo es requerido'
      } else {
        errors.fields[index].type = ''
      }
    }
    
    if (field.type === 'select' && touched.fields[index]?.options) {
      if (!field.optionsString.trim()) {
        errors.fields[index].options = 'Las opciones son requeridas para campos de selección'
      } else {
        errors.fields[index].options = ''
      }
    }
  })
}

function validateForm() {
  touched.name = true
  form.fields.forEach((_, index) => {
    touched.fields[index] = {
      label: true,
      name: true,
      type: true,
      options: true
    }
  })
  
  validateAllFields()
  
  // Verificar si hay errores
  if (errors.name) return false
  
  for (const fieldErrors of errors.fields) {
    if (fieldErrors.label || fieldErrors.name || fieldErrors.type || 
        (fieldErrors.options && form.fields[errors.fields.indexOf(fieldErrors)].type === 'select')) {
      return false
    }
  }
  
  return true
}

function prepareFields() {
  return form.fields.map(f => {
    const fieldData = {
      label: f.label,
      name: f.name,
      type: f.type,
      required: f.required || false
    }
    
    if (f.type === 'select' && f.optionsString) {
      fieldData.options = f.optionsString.split(',').map(s => s.trim()).filter(s => s.length > 0)
    }
    
    return fieldData
  })
}

async function handleSubmit() {
  if (!validateForm()) {
    return
  }

  try {
    const payload = {
      name: form.name.trim(),
      fields: prepareFields()
    }
    
    const res = await axios.post(`/api/form-templates`, payload)
    alert(`Formulario creado con ID: ${res.data.id}`)
    
    // Resetear formulario
    form.name = ''
    form.fields = [{
      label: '',
      name: '',
      type: 'text',
      optionsString: '',
      required: false
    }]
    
    // Resetear estados
    errors.name = ''
    errors.fields = [{
      label: '',
      name: '',
      type: '',
      options: ''
    }]
    touched.name = false
    touched.fields = [{
      label: false,
      name: false,
      type: false,
      options: false
    }]
    
  } catch (error) {
    let errorMessage = 'Error al crear formulario'
    if (error.response?.data?.message) {
      errorMessage = error.response.data.message
    }
    alert(errorMessage)
    console.error(error)
  }
}
</script>

<style scoped>
.bg-light-danger {
  background-color: rgba(255, 0, 0, 0.03);
  border: 1px solid rgba(220, 53, 69, 0.3);
}

.invalid-feedback {
  display: block;
  color: #dc3545;
  font-size: 0.875em;
}

.text-danger {
  color: #dc3545;
}

.is-invalid {
  border-color: #dc3545;
}
</style>