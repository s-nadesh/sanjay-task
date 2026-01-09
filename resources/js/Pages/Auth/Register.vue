<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
})

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    })
}
</script>

<template>
    <Head title="Register" />

    <div class="register-page bg-body-secondary">
        <div class="register-box">
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <h1 class="h3"><b>Admin</b>LTE</h1>
                </div>

                <div class="card-body register-card-body">
                    <p class="register-box-msg">
                        Register a new membership
                    </p>

                    <!-- Register Form -->
                    <form @submit.prevent="submit">
                        <!-- Name -->
                        <div class="input-group mb-3">
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Full Name"
                                v-model="form.name"
                                autofocus
                                autocomplete="name"
                            />
                            <div class="input-group-text">
                                <span class="bi bi-person"></span>
                            </div>
                        </div>
                        <div
                            v-if="form.errors.name"
                            class="text-danger small mb-2"
                        >
                            {{ form.errors.name }}
                        </div>

                        <!-- Email -->
                        <div class="input-group mb-3">
                            <input
                                type="email"
                                class="form-control"
                                placeholder="Email"
                                v-model="form.email"
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
                                autocomplete="new-password"
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

                        <!-- Confirm Password -->
                        <div class="input-group mb-3">
                            <input
                                type="password"
                                class="form-control"
                                placeholder="Confirm Password"
                                v-model="form.password_confirmation"
                                autocomplete="new-password"
                            />
                            <div class="input-group-text">
                                <span class="bi bi-lock-fill"></span>
                            </div>
                        </div>
                        <div
                            v-if="form.errors.password_confirmation"
                            class="text-danger small mb-2"
                        >
                            {{ form.errors.password_confirmation }}
                        </div>

                        <!-- Row -->
                        <div class="row">
                            <div class="col-8">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        id="terms"
                                        required
                                    />
                                    <label
                                        class="form-check-label"
                                        for="terms"
                                    >
                                        I agree to the
                                        <a href="#">terms</a>
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
                                        Register
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Login Link -->
                    <p class="mb-0">
                        <Link :href="route('login')" class="text-center">
                            I already have a membership
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
