<script lang="ts" setup>
import { Head, router } from '@inertiajs/vue3'
import { type BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue'
import DoctorForm from './Partials/DoctorForm.vue'
import { ref } from 'vue'

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Edit Doctor',
        href: '/doctors',
    },
];

interface User {
  id: number
  name: string
  email: string
}

interface Doctor {
  id: number 
  user_id: number | null
  specialty: string
  phone: string
  user?: {
    id: number
    name: string
    email: string
  }
}

const props = defineProps<{
  users: User[]
  doctor: Doctor
}>()

const form = ref({
  id: props.doctor.id,
  user_id: props.doctor.user_id,
  specialty: props.doctor.specialty,
  phone: props.doctor.phone,
  user: props.doctor.user
})


function submit() {
  router.put(`/doctors/${props.doctor.id}`, form.value)
}
</script>

<template>
  <Head title="Edit Doctor" />

  <AppLayout :breadcrumbs="breadcrumbs">
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