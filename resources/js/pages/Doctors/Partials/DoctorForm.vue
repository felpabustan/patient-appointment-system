<script lang="ts" setup>
import { defineProps, defineEmits, computed, ref } from 'vue'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Button } from '@/components/ui/button'
import { Check, ChevronsUpDown, Search } from 'lucide-vue-next'
import {
  Command,
  CommandEmpty,
  CommandGroup,
  CommandInput,
  CommandItem,
} from '@/components/ui/command'
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/components/ui/popover'
import {
  Select,
  SelectItem,
  SelectContent,
  SelectTrigger,
  SelectValue,
  SelectLabel,
  SelectGroup
} from '@/components/ui/select'
import { specialtyGroups } from '@/constants/specialties'
import { cn } from '@/lib/utils'
import type { User, DoctorFormData } from '@/types'

interface Props {
  form: DoctorFormData
  users: User[]
}

interface Emits {
  (e: 'update:form', value: DoctorFormData): void
  (e: 'submit'): void
}

const props = defineProps<Props>()
const emit = defineEmits<Emits>()

function updateField(field: keyof DoctorFormData, value: any) {
  const newValue = field === 'user_id' ? (value !== null ? Number(value) : null) : value
  emit('update:form', { ...props.form, [field]: newValue })
}

const selectedUser = computed(() => {
  const uid = Number(props.form.user_id)
  return props.users.find(user => user.id === uid)
})

const emailValue = computed(() => selectedUser.value?.email || '')

const openUser = ref(false)
const userSearch = ref('')

const filteredUsers = computed(() => {
  if (!userSearch.value) return props.users
  
  const search = userSearch.value.toLowerCase()
  return props.users.filter(user => 
    user.name.toLowerCase().includes(search) || 
    user.email.toLowerCase().includes(search)
  )
})
</script>

<template>
  <form @submit.prevent="emit('submit')" class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
      <div>
        <Label for="user">User</Label>
        <div class="mt-2">
          <Popover v-model:open="openUser">
            <PopoverTrigger as-child>
              <Button
                variant="outline"
                role="combobox"
                :aria-expanded="openUser"
                class="w-full justify-between"
              >
                {{ selectedUser?.name ?? "Select user..." }}
                <ChevronsUpDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
              </Button>
            </PopoverTrigger>
            <PopoverContent class="w-full p-0">
              <Command>
                <CommandInput 
                  placeholder="Search user..." 
                  v-model="userSearch"
                  class="h-9"
                >
                  <Search class="mr-2 h-4 w-4" />
                </CommandInput>
                <CommandEmpty>No user found.</CommandEmpty>
                <CommandGroup>
                  <CommandItem
                    v-for="user in filteredUsers"
                    :key="user.id"
                    :value="user.id.toString()"
                    @select="() => {
                      updateField('user_id', user.id)
                      openUser = false
                    }"
                    class="cursor-pointer"
                  >
                    <Check
                      :class="cn(
                        'mr-2 h-4 w-4',
                        selectedUser?.id === user.id ? 'opacity-100' : 'opacity-0'
                      )"
                    />
                    <div class="flex flex-col">
                      <span>{{ user.name }}</span>
                      <span class="text-sm text-gray-500">{{ user.email }}</span>
                    </div>
                  </CommandItem>
                </CommandGroup>
              </Command>
            </PopoverContent>
          </Popover>
        </div>
      </div>

      <div>
        <Label>Email</Label>
        <Input
          v-model="emailValue"
          readonly
          class="mt-2"
          :placeholder="emailValue || 'No email available'"
        />
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <Label for="specialty">Specialty</Label>
        <div class="mt-2">
          <Select
            :modelValue="form.specialty"
            @update:modelValue="value => updateField('specialty', value)"
          >
            <SelectTrigger id="specialty" class="w-full">
              <SelectValue :placeholder="'Select a specialty'" />
            </SelectTrigger>
            <SelectContent>
              <SelectGroup v-for="(specialties, group) in specialtyGroups" :key="group">
                <SelectLabel class="pl-4 font-bold">{{ group }}</SelectLabel>
                <SelectItem 
                  v-for="specialty in specialties" 
                  :key="specialty" 
                  :value="specialty"
                  class="pl-6"
                >
                  {{ specialty }}
                </SelectItem>
              </SelectGroup>
            </SelectContent>
          </Select>
        </div>
      </div>

      <div>
        <Label for="phone">Phone</Label>
        <Input
          id="phone"
          v-model="form.phone"
          class="mt-2"
          placeholder="Enter phone"
        />
      </div>
    </div>

    <div>
      <Button type="submit">Submit</Button>
    </div>
  </form>
</template>
