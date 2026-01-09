<template>
    <AdminLayout :title="`Edit Category: ${form.name}`">
        <div class="card">
            <div class="card-body">
                <form @submit.prevent="submit">

                    <!-- Name -->
                    <div class="mb-3">
                        <label>Name</label>
                        <input v-model="form.name" class="form-control" />
                        <div class="text-danger">{{ form.errors.name }}</div>
                    </div>

                    <!-- Parent Category -->
                    <div class="mb-3">
                        <label>Parent Category</label>
                        <select v-model="form.parent_id" class="form-select">
                            <option value="">None</option>
                            <option 
                                v-for="option in categoryOptions" 
                                :key="option.id" 
                                :value="option.id"
                                :disabled="option.id === form.id">
                                {{ option.indentedName }}
                            </option>
                        </select>
                        <div class="text-danger">{{ form.errors.parent_id }}</div>
                    </div>

                    <!-- 🔹 Description (NEW) -->
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea v-model="form.description"
                                  class="form-control"
                                  rows="4"></textarea>
                        <div class="text-danger">{{ form.errors.description }}</div>
                    </div>

                    <!-- 🔹 Existing Image Preview (KEEP) -->
                    <div v-if="category.image" class="mb-2">
                        <img :src="`/storage/${category.image}`"
                             style="max-height: 80px" />
                    </div>

                    <!-- 🔹 Image Upload (NEW) -->
                    <div class="mb-3">
                        <label>Category Image</label>
                        <input type="file"
                               class="form-control"
                               @change="e => form.image = e.target.files[0]" />
                        <div class="text-danger">{{ form.errors.image }}</div>
                    </div>

                    <!-- Status -->
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
<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    category: Object,
    categories: Array
})

const form = useForm({
    id: props.category.id,
    name: props.category.name,
    parent_id: props.category.parent_id || '',
    description: props.category.description,
    image: null,
    status: props.category.status ? 1 : 0,
})

const submit = () => {
    form.put(route('categories.update', form.id), {
        forceFormData: true,
        _method: 'put',
    })
}

// Flatten categories recursively
function flattenCategories(categories, level = 0) {
    let result = []

    categories.forEach(cat => {
        if (cat.id !== form.id) {
            result.push({
                id: cat.id,
                indentedName: `${'— '.repeat(level)}${cat.name}`
            })

            if (cat.children && cat.children.length) {
                result = result.concat(flattenCategories(cat.children, level + 1))
            }
        }
    })

    return result
}

const categoryOptions = flattenCategories(props.categories)
</script>