<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm } from '@inertiajs/vue3'

const { categories } = defineProps({
    categories: Array,
})


// flatten nested categories
const categoryOptions = flattenCategories(categories)


const form = useForm({
    category_id: '',
    name: '',
    price: '',
    description: '',
    image: null,
    status: 1,
})

const submit = () => {
    form.post(route('products.store'), {
        forceFormData: true
    })
}

function flattenCategories(categories, level = 0) {
    let result = []

    categories.forEach(cat => {
        result.push({
            id: cat.id,
            name: `${'— '.repeat(level)}${cat.name}`
        })

        if (cat.children_recursive?.length) {
            result = result.concat(
                flattenCategories(cat.children_recursive, level + 1)
            )
        }
    })

    return result
}

</script>

<template>
    <AdminLayout title="Create Product">
        <div class="card">
            <div class="card-body">
                <form @submit.prevent="submit">

                    <div class="mb-3">
                        <label>Category <span class="text-danger">*</span></label>
                        <select v-model="form.category_id" class="form-select">
                            <option value="">Select Category</option>
                            <option v-for="cat in categoryOptions"
                                    :key="cat.id"
                                    :value="cat.id">
                                {{ cat.name }}
                            </option>
                        </select>
                        <div class="text-danger">{{ form.errors.category_id }}</div>
                    </div>

                    <div class="mb-3">
                        <label>Product Name <span class="text-danger">*</span></label>
                        <input v-model="form.name" class="form-control" />
                        <div class="text-danger">{{ form.errors.name }}</div>
                    </div>

                    <div class="mb-3">
                        <label>Price <span class="text-danger">*</span></label>
                        <input type="number" step="0.01"
                               v-model="form.price"
                               class="form-control" />
                        <div class="text-danger">{{ form.errors.price }}</div>
                    </div>

                    <div class="mb-3">
                        <label>Description</label>
                        <textarea v-model="form.description"
                                class="form-control"
                                rows="4"></textarea>
                        <div class="text-danger">{{ form.errors.description }}</div>
                    </div>

                    <div class="mb-3">
                        <label>Product Image</label>
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

                    <button class="btn btn-success">Save</button>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
