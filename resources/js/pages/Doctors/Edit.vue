<script lang="ts" setup>
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import DoctorForm from './Partials/DoctorForm.vue'
import { ref } from 'vue'

interface User {
  id: number
  name: string
  email: string
}

interface Doctor {
  user_id: number | null
  specialty: string
  phone: string
}

const props = defineProps<{
  users: User[]
  doctor: Doctor
}>()

const form = ref({ ...props.doctor })

function submit() {
  router.put(`/doctors/${props.doctor.user_id}`, form.value)
}
</script>

<template>
  <Head title="Edit Doctor" />

  <AppLayout>
    <div class="max-w-xl mx-auto p-6">
      <h1 class="text-2xl font-bold mb-4">Edit Doctor</h1>
      <DoctorForm
        :form="form"
        :users="props.users"
        @update:form="form = $event"
        @submit="submit"
      />
    </div>
  </AppLayout>
</template>
