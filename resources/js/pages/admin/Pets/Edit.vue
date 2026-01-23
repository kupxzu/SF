<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import InputError from '@/components/InputError.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { index, update } from '@/routes/admin/pets';

const props = defineProps<{
    pet: {
        id: number;
        client_id: number;
        petname: string;
        pet_type: string;
        breed: string;
        colormarkings: string;
    };
    clients: { id: number; fullname: string }[];
}>();

const form = useForm({
    client_id: props.pet.client_id.toString(),
    petname: props.pet.petname,
    pet_type: props.pet.pet_type,
    breed: props.pet.breed,
    colormarkings: props.pet.colormarkings,
});

// Search functionality for clients
const clientSearch = ref('');
const showClientDropdown = ref(false);
const dropdownRef = ref<HTMLElement | null>(null);

const filteredClients = computed(() => {
    if (!clientSearch.value) return props.clients;
    return props.clients.filter(client => 
        client.fullname.toLowerCase().includes(clientSearch.value.toLowerCase())
    );
});

const selectedClientName = computed(() => {
    const client = props.clients.find(c => c.id.toString() === form.client_id);
    return client ? client.fullname : '';
});

const selectClient = (client: any) => {
    form.client_id = client.id.toString();
    clientSearch.value = '';
    showClientDropdown.value = false;
};

const clearClient = () => {
    form.client_id = '';
    clientSearch.value = '';
};

// Close dropdown when clicking outside
const handleClickOutside = (event: MouseEvent) => {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target as Node)) {
        showClientDropdown.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});

const submit = () => {
    form.put(update.url(props.pet.id));
};
</script>

<template>
    <AdminLayout>
        <Head title="Edit Pet" />

        <div class="max-w-8xl mx-auto px-6 py-10 space-y-6">
            <div>
                <h1 class="text-3xl font-bold tracking-tight">Edit Pet</h1>
                <p class="text-muted-foreground">Update pet information</p>
            </div>

            <div class="rounded-lg border bg-white p-8">
                <form @submit.prevent="submit" class="space-y-6 max-w-2xl">
                    <div class="grid gap-2">
                        <Label for="client_id">Client</Label>
                        <div class="relative" ref="dropdownRef">
                            <!-- Selected Client Display -->
                            <div v-if="form.client_id" class="flex items-center justify-between h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                                <span>{{ selectedClientName }}</span>
                                <button type="button" @click="clearClient" class="text-muted-foreground hover:text-foreground">
                                    ✕
                                </button>
                            </div>
                            <!-- Search Input -->
                            <div v-else>
                                <Input
                                    id="client_search"
                                    v-model="clientSearch"
                                    type="text"
                                    placeholder="Search client by name..."
                                    @focus="showClientDropdown = true"
                                    @input="showClientDropdown = true"
                                    autocomplete="off"
                                />
                            </div>
                            <!-- Dropdown List -->
                            <div 
                                v-if="showClientDropdown && !form.client_id" 
                                class="absolute z-50 w-full mt-1 bg-white border border-input rounded-md shadow-lg max-h-60 overflow-auto"
                            >
                                <div v-if="filteredClients.length === 0" class="px-3 py-2 text-sm text-muted-foreground">
                                    No clients found
                                </div>
                                <div
                                    v-for="client in filteredClients"
                                    :key="client.id"
                                    @click="selectClient(client)"
                                    class="w-full px-3 py-2 text-left text-sm hover:bg-muted cursor-pointer"
                                >
                                    {{ client.fullname }}
                                </div>
                            </div>
                        </div>
                        <InputError :message="form.errors.client_id" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="petname">Pet Name</Label>
                        <Input
                            id="petname"
                            v-model="form.petname"
                            type="text"
                            placeholder="Fluffy"
                            required
                        />
                        <InputError :message="form.errors.petname" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="pet_type">Pet Type</Label>
                        <select 
                            id="pet_type"
                            v-model="form.pet_type"
                            required
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <option value="">Select pet type</option>
                            <option value="canine">Canine (Dog)</option>
                            <option value="feline">Feline (Cat)</option>
                        </select>
                        <InputError :message="form.errors.pet_type" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="breed">Breed</Label>
                        <Input
                            id="breed"
                            v-model="form.breed"
                            type="text"
                            placeholder="Golden Retriever"
                            required
                        />
                        <InputError :message="form.errors.breed" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="colormarkings">Color/Markings</Label>
                        <Input
                            id="colormarkings"
                            v-model="form.colormarkings"
                            type="text"
                            placeholder="Golden with white chest"
                            required
                        />
                        <InputError :message="form.errors.colormarkings" />
                    </div>

                    <div class="flex gap-4">
                        <Button type="submit" :disabled="form.processing">
                            <Spinner v-if="form.processing" />
                            Update Pet
                        </Button>
                        <Link :href="index.url()">
                            <Button type="button" variant="outline">Cancel</Button>
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
