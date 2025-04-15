
<script lang="ts" setup>
import { Head, router } from '@inertiajs/vue3'
import { toast } from 'vue-sonner'
import { ref } from 'vue'
import { type BreadcrumbItem, type User, type PatientFormData } from '@/types'
import AppLayout from '@/layouts/AppLayout.vue'
import PatientForm from './Partials/PatientForm.vue'

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Patients',
    href: '/patients',
  },
  {
    title: 'Edit Patient',
    href: '#',
  },
]

const props = defineProps<{
  users: User[]
  patient: PatientFormData
}>()

const form = ref<PatientFormData>({
  id: props.patient.id,
  user_id: props.patient.user_id,
  gender: props.patient.gender,
  dob: props.patient.dob,
  phone: props.patient.phone,
  address: props.patient.address,
  user: props.patient.user
})

function submit() {
  router.put(`/patients/${props.patient.id}`, form.value, {
    onSuccess: () => {
      toast.success('Patient updated successfully')
    },
    onError: (errors) => {
      toast.error('Failed to update patient', {
        style: { background: 'red', color: 'white' },
        description: Object.values(errors).flat().join('\n')
      })
    }
  })
}
</script>

<template>
  <Head title="Edit Patient" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="max-w-xl mx-auto p-6">
      <h1 class="text-2xl font-bold mb-4">Edit Patient</h1>
      <PatientForm
        :form="form"
        :users="users"
        @update:form="(newForm) => form = newForm"
        @submit="submit"
      />
    </div>
  </AppLayout>
</template>

