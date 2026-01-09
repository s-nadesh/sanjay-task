<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm } from '@inertiajs/vue3'

defineProps({
    roles: Array,
})

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'user',
})
</script>

<template>
    <AdminLayout title="Create User">

        <div class="row">
            <div class="col-md-6">

                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Create User</h3>
                    </div>

                    <form @submit.prevent="form.post(route('users.store'))">
                        <div class="card-body">

                            <div class="form-group mb-3">
                                <label>Name</label>
                                <input v-model="form.name" class="form-control" />
                                <span class="text-danger">{{ form.errors.name }}</span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Email</label>
                                <input v-model="form.email" class="form-control" />
                                <span class="text-danger">{{ form.errors.email }}</span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Password</label>
                                <input type="password" v-model="form.password" class="form-control" />
                                <span class="text-danger">{{ form.errors.password }}</span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Confirm Password</label>
                                <input type="password" v-model="form.password_confirmation" class="form-control" />
                            </div>

                            <div class="form-group mb-3">
                                <label>Role</label>
                                <select v-model="form.role" class="form-control">
                                    <option v-for="role in roles" :key="role" :value="role">
                                        {{ role }}
                                    </option>
                                </select>
                                <span class="text-danger">{{ form.errors.role }}</span>
                            </div>

                        </div>

                        <div class="card-footer">
                            <button class="btn btn-primary" :disabled="form.processing">
                                Create User
                            </button>
                        </div>
                    </form>

                </div>

            </div>
        </div>

    </AdminLayout>
</template>