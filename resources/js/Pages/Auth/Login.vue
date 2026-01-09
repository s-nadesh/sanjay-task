<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'

defineProps({
    canResetPassword: Boolean,
    status: String,
})

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    })
}
</script>

<template>
    <Head title="Login" />

    <div class="login-page bg-body-secondary">
        <div class="login-box">
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <h1 class="h3"><b>Admin</b>LTE</h1>
                </div>

                <div class="card-body login-card-body">
                    <p class="login-box-msg">
                        Sign in to start your session
                    </p>

                    <!-- Status message -->
                    <div
                        v-if="status"
                        class="alert alert-success text-center"
                    >
                        {{ status }}
                    </div>

                    <!-- Login Form -->
                    <form @submit.prevent="submit">
                        <!-- Email -->
                        <div class="input-group mb-3">
                            <input
                                type="email"
                                class="form-control"
                                placeholder="Email"
                                v-model="form.email"
                                autofocus
                                autocomplete="username"
                            />
                            <div class="input-group-text">
                                <span class="bi bi-envelope"></span>
                            </div>
                        </div>
                        <div
                            v-if="form.errors.email"
                            class="text-danger small mb-2"
                        >
                            {{ form.errors.email }}
                        </div>

                        <!-- Password -->
                        <div class="input-group mb-3">
                            <input
                                type="password"
                                class="form-control"
                                placeholder="Password"
                                v-model="form.password"
                                autocomplete="current-password"
                            />
                            <div class="input-group-text">
                                <span class="bi bi-lock-fill"></span>
                            </div>
                        </div>
                        <div
                            v-if="form.errors.password"
                            class="text-danger small mb-2"
                        >
                            {{ form.errors.password }}
                        </div>

                        <!-- Row -->
                        <div class="row">
                            <div class="col-8">
                                <div class="form-check">
                                    <input
                                        id="remember"
                                        class="form-check-input"
                                        type="checkbox"
                                        v-model="form.remember"
                                    />
                                    <label
                                        class="form-check-label"
                                        for="remember"
                                    >
                                        Remember Me
                                    </label>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="d-grid gap-2">
                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                        :disabled="form.processing"
                                    >
                                        <span
                                            v-if="form.processing"
                                            class="spinner-border spinner-border-sm me-1"
                                        ></span>
                                        Sign In
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Links -->
                    <p class="mb-1 mt-3">
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                        >
                            I forgot my password
                        </Link>
                    </p>

                    <p class="mb-0">
                        <Link :href="route('register')" class="text-center">
                            Register a new membership
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
