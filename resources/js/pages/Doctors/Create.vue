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

const props = defineProps<{
  users: User[]
}>()

const form = ref({
  user_id: null,
  specialty: '',
  phone: '',
})

function submit() {
  router.post('/doctors', form.value)
}
</script>

<template>
  <Head title="Create Doctor" />

  <AppLayout>
    <div class="max-w-xl mx-auto p-6">
      <h1 class="text-2xl font-bold mb-4">Create Doctor</h1>
      <DoctorForm
        :form="form"
        :users="props.users"
        @update:form="form = $event"
        @submit="submit"
      />
    </div>
  </AppLayout>
</template>
