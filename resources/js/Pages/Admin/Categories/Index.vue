<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { ref, computed } from 'vue';
import { useToast } from 'vue-toastification';

const toast = useToast();
const props = defineProps({
    categories: Object,   // paginated
    attributes: Array,    // all global attributes for multi-select
});

// ── Form ─────────────────────────────────────────────────────
const isEditing    = ref(false);
const editId       = ref(null);
const imagePreview = ref(null);

const form = useForm({
    _method:         'POST',
    name:            '',
    description:     '',
    image:           null,
    remove_image:    false,
    visible_in_menu: true,
    menu_position:   0,
    attribute_ids:   [],
});

const save = () => {
    if (isEditing.value) {
        form.post(route('admin.categories.update', editId.value), {
            //onSuccess: () => { toast.success('Category updated!'); resetForm(); },
        });
    } else {
        form.post(route('admin.categories.store'), {
            //onSuccess: () => { toast.success('Category created!'); resetForm(); },
        });
    }
};

const editMode = (category) => {
    isEditing.value      = true;
    editId.value         = category.id;
    form._method         = 'PUT';
    form.name            = category.name;
    form.description     = category.description || '';
    form.image           = null;
    form.remove_image    = false;
    imagePreview.value   = category.image ? '/storage/' + category.image : null;
    form.visible_in_menu = category.visible_in_menu;
    form.menu_position   = category.menu_position;
    form.attribute_ids   = category.attributes?.map(a => a.id) ?? [];
    form.clearErrors();
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

const resetForm = () => {
    isEditing.value    = false;
    editId.value       = null;
    imagePreview.value = null;
    form.reset();
    form.clearErrors();
    form._method       = 'POST';
    form.image         = null;
    form.remove_image  = false;
    form.visible_in_menu = true;
    form.menu_position   = 0;
    form.attribute_ids   = [];
};

const handleImageUpload = (e) => {
    form.image = e.target.files[0];
    if (form.image) {
        imagePreview.value = URL.createObjectURL(form.image);
        form.remove_image = false;
    }
};

const removeImage = () => {
    form.image = null;
    imagePreview.value = null;
    form.remove_image = true;
};

// Toggle attribute in selection
const toggleAttr = (id) => {
    const idx = form.attribute_ids.indexOf(id);
    if (idx === -1) form.attribute_ids.push(id);
    else form.attribute_ids.splice(idx, 1);
};

const deleteCategory = (id) => {
    if (!confirm('Delete this category? Products will lose their category.')) return;
    useForm({}).delete(route('admin.categories.destroy', id), {
        onSuccess: () => toast.success('Category deleted.'),
    });
};
</script>

<template>
    <Head title="Manage Categories" />
    <AdminLayout>
        <div class="max-w-7xl mx-auto px-4 py-8 text-black dark:text-white">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold">Manage Categories</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Control which categories appear in the navigation menu and assign filterable attributes.</p>
                </div>
                <Link :href="route('admin.attributes.index')"
                    class="text-sm font-semibold text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1">
                    ⚙️ Manage Attributes
                </Link>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- ── Form Panel ────────────────────────────── -->
                <div class="w-full lg:w-2/5">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow border dark:border-gray-700 sticky top-28">
                        <h2 class="text-lg font-bold mb-5">{{ isEditing ? '✏️ Edit Category' : '➕ Add New Category' }}</h2>
                        <form @submit.prevent="save" class="space-y-4">
                            <!-- Name -->
                            <div>
                                <label class="block text-sm font-semibold mb-1">Name *</label>
                                <input v-model="form.name" type="text" required
                                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 focus:ring-2 focus:ring-blue-500"/>
                                <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-sm font-semibold mb-1">Description</label>
                                <textarea v-model="form.description" rows="2"
                                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                            </div>

                            <!-- Category Image -->
                            <div>
                                <label class="block text-sm font-semibold mb-1">Category Image</label>
                                <div class="flex items-center gap-4">
                                    <div v-if="imagePreview" class="relative w-16 h-16 rounded overflow-hidden border">
                                        <img :src="imagePreview" class="w-full h-full object-cover" />
                                        <button type="button" @click="removeImage" class="absolute top-0 right-0 bg-red-500 text-white w-5 h-5 flex items-center justify-center text-xs">x</button>
                                    </div>
                                    <div v-else class="w-16 h-16 rounded border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center text-gray-400 text-xs">
                                        No Image
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" accept="image/*" @change="handleImageUpload" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                                        <p v-if="form.errors.image" class="text-red-500 text-xs mt-1">{{ form.errors.image }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Visible in Menu + Position -->
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-semibold mb-1">Visible in Menu</label>
                                    <div class="flex items-center gap-2 mt-2">
                                        <button type="button" @click="form.visible_in_menu = !form.visible_in_menu"
                                            class="relative inline-flex h-6 w-11 items-center rounded-full transition"
                                            :class="form.visible_in_menu ? 'bg-blue-600' : 'bg-gray-300 dark:bg-gray-600'">
                                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition"
                                                :class="form.visible_in_menu ? 'translate-x-6' : 'translate-x-1'"/>
                                        </button>
                                        <span class="text-sm font-medium" :class="form.visible_in_menu ? 'text-blue-600' : 'text-gray-400'">
                                            {{ form.visible_in_menu ? 'Shown' : 'Hidden' }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-1">Menu Position</label>
                                    <input v-model.number="form.menu_position" type="number" min="0"
                                        placeholder="0 = first"
                                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 focus:ring-2 focus:ring-blue-500"/>
                                    <p class="text-xs text-gray-400 mt-1">Lower = appears first</p>
                                </div>
                            </div>

                            <!-- Filterable Attributes -->
                            <div v-if="attributes && attributes.length">
                                <label class="block text-sm font-semibold mb-2">Filterable Attributes</label>
                                <p class="text-xs text-gray-400 mb-2">Select attributes that customers can use to filter products in this category.</p>
                                <div class="flex flex-wrap gap-2">
                                    <button
                                        v-for="attr in attributes" :key="attr.id"
                                        type="button"
                                        @click="toggleAttr(attr.id)"
                                        :class="form.attribute_ids.includes(attr.id)
                                            ? 'bg-blue-600 text-white border-blue-600'
                                            : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:border-blue-400'"
                                        class="px-3 py-1.5 rounded-lg border text-sm font-medium transition">
                                        {{ attr.name }}
                                        <span class="ml-1 text-xs opacity-70">({{ attr.type }})</span>
                                    </button>
                                </div>
                                <p v-if="form.attribute_ids.length" class="text-xs text-blue-500 mt-1.5">
                                    {{ form.attribute_ids.length }} selected
                                </p>
                            </div>
                            <div v-else class="text-xs text-gray-400 bg-gray-50 dark:bg-gray-700 rounded-lg px-3 py-2">
                                No attributes yet.
                                <Link :href="route('admin.attributes.index')" class="text-blue-500 hover:underline">Create some first →</Link>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-3 pt-1">
                                <button type="submit" :disabled="form.processing"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2 rounded-lg transition disabled:opacity-60">
                                    {{ isEditing ? 'Update' : 'Create' }} Category
                                </button>
                                <button v-if="isEditing" type="button" @click="resetForm"
                                    class="px-5 py-2 rounded-lg border dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition font-medium">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- ── Table ─────────────────────────────────── -->
                <div class="w-full lg:w-3/5">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow border dark:border-gray-700 overflow-hidden">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 dark:bg-gray-900 border-b dark:border-gray-700 text-xs uppercase text-gray-500 dark:text-gray-400">
                                <tr>
                                    <th class="px-4 py-3 font-semibold">Pos</th>
                                    <th class="px-4 py-3 font-semibold">Name</th>
                                    <th class="px-4 py-3 font-semibold text-center">Menu</th>
                                    <th class="px-4 py-3 font-semibold text-center">Products</th>
                                    <th class="px-4 py-3 font-semibold">Attributes</th>
                                    <th class="px-4 py-3 font-semibold text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="cat in categories.data" :key="cat.id"
                                    class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-700 text-xs font-bold">
                                            {{ cat.menu_position }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ cat.name }}</p>
                                        <p v-if="cat.description" class="text-xs text-gray-400 truncate max-w-[160px]">{{ cat.description }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold"
                                            :class="cat.visible_in_menu
                                                ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'
                                                : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400'">
                                            {{ cat.visible_in_menu ? '✓ Visible' : '✕ Hidden' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded text-xs font-bold">{{ cat.products_count }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap gap-1">
                                            <span v-for="attr in cat.attributes" :key="attr.id"
                                                class="text-xs bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-1.5 py-0.5 rounded">
                                                {{ attr.name }}
                                            </span>
                                            <span v-if="!cat.attributes?.length" class="text-xs text-gray-400">None</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <button @click="editMode(cat)" class="text-blue-500 hover:text-blue-700 text-sm font-semibold mr-3">Edit</button>
                                        <button @click="deleteCategory(cat.id)" class="text-red-500 hover:text-red-700 text-sm font-semibold">Delete</button>
                                    </td>
                                </tr>
                                <tr v-if="!categories.data?.length">
                                    <td colspan="6" class="px-4 py-10 text-center text-gray-400">No categories yet.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <Pagination v-if="categories.data?.length" :paginator="categories" class="mt-5" />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
