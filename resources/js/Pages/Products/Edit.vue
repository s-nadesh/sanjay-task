<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    product: Object,
    categories: Array,
})

const form = useForm({
    category_id: props.product.category_id,
    name: props.product.name,
    price: props.product.price,
    description: props.product.description,
    image: null,
    status: props.product.status ? 1 : 0,
})

const submit = () => {
    form.put(route('products.update', props.product.id), {
        forceFormData: true,
        _method: 'put'
    })
}
</script>

<template>
    <AdminLayout title="Edit Product">
        <div class="card">
            <div class="card-body">
                <form @submit.prevent="submit">

                    <div class="mb-3">
                        <label>Category</label>
                        <select v-model="form.category_id" class="form-select">
                            <option value="">Select Category</option>
                            <option v-for="cat in categories"
                                    :key="cat.id"
                                    :value="cat.id">
                                {{ cat.name }}
                            </option>
                        </select>
                        <div class="text-danger">{{ form.errors.category_id }}</div>
                    </div>

                    <div class="mb-3">
                        <label>Product Name</label>
                        <input v-model="form.name" class="form-control" />
                        <div class="text-danger">{{ form.errors.name }}</div>
                    </div>

                    <div class="mb-3">
                        <label>Price</label>
                        <input type="number" step="0.01"
                               v-model="form.price"
                               class="form-control" />
                        <div class="text-danger">{{ form.errors.price }}</div>
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea v-model="form.description"
                                class="form-control"
                                rows="4"
                                placeholder="Enter product description"></textarea>
                        <div class="text-danger">{{ form.errors.description }}</div>
                    </div>

                    <div v-if="product.image" class="mb-2">
                        <img :src="`/storage/${product.image}`"
                            style="max-height: 80px" />
                    </div>
                    <div class="mb-3">
                        <label>Image</label>
                        <input type="file"
                            class="form-control"
                            @change="e => form.image = e.target.files[0]" />
                        <div class="text-danger">{{ form.errors.image }}</div>
                    </div>

                    <div class="mb-3">
                        <label>Status</label>
                        <select v-model="form.status" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <button class="btn btn-success">Update</button>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
