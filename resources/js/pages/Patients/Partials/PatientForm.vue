<script lang="ts" setup>
import { defineProps, defineEmits, computed, ref } from 'vue'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Button } from '@/components/ui/button'
import { Check, ChevronsUpDown, Search, CalendarIcon } from 'lucide-vue-next'
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
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import { Calendar } from '@/components/ui/calendar'
import { CalendarDate, parseDate, today, getLocalTimeZone } from '@internationalized/date'
import { cn } from '@/lib/utils'

interface PatientFormData {
  id?: number
  user_id: number | null
  gender: string
  dob: string
  phone: string
  address: string
  user?: {
    id: number
    name: string
    email: string
  }
}

const props = defineProps<{
  form: PatientFormData
  users: Array<{
    id: number
    name: string
    email: string
  }>
}>()

const emit = defineEmits<{
  (e: 'update:form', value: PatientFormData): void
  (e: 'submit'): void
}>()

function updateField(field: string, value: any) {
  const newValue = field === 'user_id' ? (value !== null ? Number(value) : null) : value
  emit('update:form', { ...props.form, [field]: newValue })
}

const selectedUser = computed(() => {
  const uid = Number(props.form.user_id)
  return props.users.find(user => user.id === uid)
})

const emailValue = computed(() => selectedUser.value?.email || '')

const formatDate = (date: CalendarDate | undefined) => {
  if (!date) return 'Pick a date'
  try {
    // Format as YYYY-MM-DD
    return `${date.year}-${String(date.month).padStart(2, '0')}-${String(date.day).padStart(2, '0')}`
  } catch (e) {
    console.warn('Date formatting error:', e)
    return 'Pick a date'
  }
}

const dobValue = computed({
  get: () => {
    if (!props.form.dob) return undefined
    try {
      return parseDate(props.form.dob)
    } catch (e) {
      console.warn('Invalid date format:', props.form.dob)
      return undefined
    }
  },
  set: (val) => {
    emit('update:form', { 
      ...props.form, 
      dob: val ? formatDate(val) : '' 
    })
  }
})

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
        <Label for="dob">Date of Birth</Label>
        <div class="mt-2">
          <Popover>
            <PopoverTrigger as-child>
              <Button
                variant="outline"
                :class="cn(
                  'w-full text-left font-normal',
                  !dobValue && 'text-muted-foreground'
                )"
              >
                <span>{{ dobValue ? formatDate(dobValue) : 'Pick a date' }}</span>
                <CalendarIcon class="ms-auto h-4 w-4 opacity-50" />
              </Button>
            </PopoverTrigger>
            <PopoverContent class="w-auto p-0">
              <Calendar
                v-model="dobValue"
                calendar-label="Date of birth"
                initial-focus
                :min-value="new CalendarDate(1900, 1, 1)"
                :max-value="today(getLocalTimeZone())"
              />
            </PopoverContent>
          </Popover>
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

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <Label for="gender">Gender</Label>
        <div class="mt-2">
          <Select
            :model-value="form.gender"
            @update:model-value="value => updateField('gender', value)"
            required
          >
            <SelectTrigger id="gender" class="w-full">
              <SelectValue :placeholder="'Select gender'" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="male">Male</SelectItem>
              <SelectItem value="female">Female</SelectItem>
              <SelectItem value="other">Other</SelectItem>
            </SelectContent>
          </Select>
        </div>
      </div>

      <div>
        <Label for="address">Address</Label>
        <Input
          id="address"
          v-model="form.address"
          class="mt-2"
          placeholder="Enter address"
        />
      </div>
    </div>

    <div>
      <Button type="submit">Submit</Button>
    </div>
  </form>
</template>
