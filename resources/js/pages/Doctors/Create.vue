<script lang="ts" setup>
import { Head, router } from '@inertiajs/vue3'
import { toast } from 'vue-sonner'
import { ref } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import DoctorForm from './Partials/DoctorForm.vue'
import type { 
  BreadcrumbItem, 
  User, 
  DoctorFormData 
} from '@/types'

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Doctors',
    href: '/doctors',
  },
  {
    title: 'Add Doctor',
    href: '/doctors/create',
  },
]

const props = defineProps<{
  users: User[]
}>()

const form = ref<DoctorFormData>({
  user_id: null,
  specialty: '',
  phone: '',
})

function submit() {
  router.post('/doctors', form.value, {
    onSuccess: () => {
      toast.success('Doctor created successfully')
    },
    onError: (errors) => {
      toast.error('Failed to create doctor', {
        style: { background: 'red', color: 'white' },
        description: Object.values(errors).flat().join('\n')
      })
    }
  })
}
</script>

<template>
  <Head title="Create Doctor" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="max-w-xl mx-auto p-6">
      <h1 class="text-2xl font-bold mb-4">Add Doctor</h1>
      <DoctorForm
        :form="form"
        :users="users"
        @update:form="form = $event"
        @submit="submit"
      />
    </div>
  </AppLayout>
</template>
