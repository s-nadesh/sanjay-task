<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm, usePage, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

defineProps({
    mustVerifyEmail: Boolean,
    status: String,
})

const user = usePage().props.auth.user

/* Profile form */
const profileForm = useForm({
    name: user.name,
    email: user.email,
})

/* Password form */
const passwordInput = ref(null)
const currentPasswordInput = ref(null)

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
})

const updatePassword = () => {
    passwordForm.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => passwordForm.reset(),
        onError: () => {
            if (passwordForm.errors.password) {
                passwordForm.reset('password', 'password_confirmation')
                passwordInput.value.focus()
            }
            if (passwordForm.errors.current_password) {
                passwordForm.reset('current_password')
                currentPasswordInput.value.focus()
            }
        },
    })
}
</script>

<template>
    <AdminLayout title="Profile">

        <div class="row">

            <!-- ================= PROFILE INFO ================= -->
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Profile Information</h3>
                    </div>

                    <form @submit.prevent="profileForm.patch(route('profile.update'))">
                        <div class="card-body">

                            <div class="form-group mb-3">
                                <label>Name</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="profileForm.name"
                                />
                                <span class="text-danger" v-if="profileForm.errors.name">
                                    {{ profileForm.errors.name }}
                                </span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Email</label>
                                <input
                                    type="email"
                                    class="form-control"
                                    v-model="profileForm.email"
                                />
                                <span class="text-danger" v-if="profileForm.errors.email">
                                    {{ profileForm.errors.email }}
                                </span>
                            </div>

                            <div
                                v-if="mustVerifyEmail && user.email_verified_at === null"
                                class="alert alert-warning"
                            >
                                Your email address is not verified.
                                <Link
                                    :href="route('verification.send')"
                                    method="post"
                                    as="button"
                                    class="btn btn-sm btn-warning mt-2"
                                >
                                    Re-send verification email
                                </Link>

                                <div
                                    v-if="status === 'verification-link-sent'"
                                    class="text-success mt-2"
                                >
                                    Verification link sent.
                                </div>
                            </div>

                        </div>

                        <div class="card-footer">
                            <button
                                class="btn btn-primary"
                                :disabled="profileForm.processing"
                            >
                                Save Changes
                            </button>

                            <span
                                v-if="profileForm.recentlySuccessful"
                                class="text-success ms-3"
                            >
                                Saved
                            </span>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ================= PASSWORD UPDATE ================= -->
            <div class="col-md-6">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Update Password</h3>
                    </div>

                    <form @submit.prevent="updatePassword">
                        <div class="card-body">

                            <div class="form-group mb-3">
                                <label>Current Password</label>
                                <input
                                    type="password"
                                    class="form-control"
                                    ref="currentPasswordInput"
                                    v-model="passwordForm.current_password"
                                />
                                <span
                                    class="text-danger"
                                    v-if="passwordForm.errors.current_password"
                                >
                                    {{ passwordForm.errors.current_password }}
                                </span>
                            </div>

                            <div class="form-group mb-3">
                                <label>New Password</label>
                                <input
                                    type="password"
                                    class="form-control"
                                    ref="passwordInput"
                                    v-model="passwordForm.password"
                                />
                                <span
                                    class="text-danger"
                                    v-if="passwordForm.errors.password"
                                >
                                    {{ passwordForm.errors.password }}
                                </span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Confirm Password</label>
                                <input
                                    type="password"
                                    class="form-control"
                                    v-model="passwordForm.password_confirmation"
                                />
                                <span
                                    class="text-danger"
                                    v-if="passwordForm.errors.password_confirmation"
                                >
                                    {{ passwordForm.errors.password_confirmation }}
                                </span>
                            </div>

                        </div>

                        <div class="card-footer">
                            <button
                                class="btn btn-secondary"
                                :disabled="passwordForm.processing"
                            >
                                Update Password
                            </button>

                            <span
                                v-if="passwordForm.recentlySuccessful"
                                class="text-success ms-3"
                            >
                                Password updated
                            </span>
                        </div>
                    </form>
                </div>
            </div>

        </div>

    </AdminLayout>
</template>
