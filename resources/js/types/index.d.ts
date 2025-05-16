import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    role: string 
}

export interface PaginationLinks {
    url: string | null
    label: string
    active: boolean
}

export interface PaginationMeta {
    current_page: number
    from: number
    last_page: number
    links: PaginationLinks[]
    path: string
    per_page: number
    to: number
    total: number
}

// Doctor Interfaces
export interface Doctor {
    id: number
    specialty: string
    phone: string
    user?: User
    user_id?: number | null
}

export interface DoctorFormData {
    id?: number
    user_id: number | null
    specialty: string
    phone: string
    user?: User
}

export interface DoctorListResponse {
    data: Doctor[]
    links: PaginationLinks[]
    meta: PaginationMeta
}

// Patient Interfaces
export interface Patient {
    id: number
    dob: string
    gender: string
    address: string
    phone: string
    user: User
}

export interface PatientFormData {
    id?: number
    user_id: number | null
    gender: string
    symptoms?: string
    dob: string
    phone: string
    address: string
    user?: User
}

export interface PatientListResponse {
    data: Patient[]
    links: PaginationLinks[]
    meta: PaginationMeta
}

// Appointment Interfaces
export interface Appointment {
    id: number
    date: string
    time_slot: string
    status: 'pending' | 'confirmed' | 'completed' | 'cancelled'
    notes: string | null
    doctor: {
        name: string
        email: string
    }
    patient: {
        name: string
        email: string
    }
}

export interface AppointmentFormData {
    id?: number
    doctor_id: number | null
    patient_id: number | null
    date: string
    time_slot: string
    status: string
    notes: string
    doctor?: {
        id: number
        name: string
        email: string
    }
    patient?: {
        id: number
        name: string
        email: string
    }
}

export interface AppointmentListResponse {
    data: Appointment[]
    links: PaginationLinks[]
    meta: PaginationMeta
}

export interface ExistingAppointment {
    id?: number
    doctor_id: number
    date: string
    time_slot: string
}

export interface AppointmentFormProps {
    form: AppointmentFormData
    doctors: User[]
    patients: User[]
    statuses: string[]
    disabledFields: AppointmentDisabledFields
    existingAppointments: ExistingAppointment[]
}

export interface AppointmentDisabledFields {
    doctor: boolean
    patient: boolean
    date: boolean
    timeSlot: boolean
    status: boolean
    notes: boolean
}

export type BreadcrumbItemType = BreadcrumbItem;


