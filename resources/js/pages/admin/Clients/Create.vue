<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref, watch, onMounted } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import InputError from '@/components/InputError.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { store } from '@/routes/admin/clients';

// PSGC API types
interface Region {
    code: string;
    name: string;
    regionName: string;
}

interface Province {
    code: string;
    name: string;
}

interface CityMunicipality {
    code: string;
    name: string;
    isCity: boolean;
}

interface Barangay {
    code: string;
    name: string;
}

// Address data
const regions = ref<Region[]>([]);
const provinces = ref<Province[]>([]);
const citiesMunicipalities = ref<CityMunicipality[]>([]);
const barangays = ref<Barangay[]>([]);

// Loading states
const loadingRegions = ref(false);
const loadingProvinces = ref(false);
const loadingCities = ref(false);
const loadingBarangays = ref(false);

// Selected codes - Default to Cagayan Valley > Cagayan > Tuguegarao
const selectedRegion = ref('020000000'); // Cagayan Valley
const selectedProvince = ref('021500000'); // Cagayan
const selectedCityMunicipality = ref('021529000'); // Tuguegarao City
const selectedBarangay = ref('');
const streetAddress = ref('');

const form = useForm({
    fullname: '',
    email: '',
    number: '',
    address: '',
});



// Fetch regions on mount and load defaults
onMounted(async () => {
    loadingRegions.value = true;
    try {
        const response = await fetch('https://psgc.gitlab.io/api/regions.json');
        regions.value = await response.json();

        // Load default province (Cagayan)
        if (selectedRegion.value) {
            loadingProvinces.value = true;
            const provResponse = await fetch(`https://psgc.gitlab.io/api/regions/${selectedRegion.value}/provinces.json`);
            provinces.value = await provResponse.json();
            loadingProvinces.value = false;

            // Load default city (Tuguegarao)
            if (selectedProvince.value) {
                loadingCities.value = true;
                const cityResponse = await fetch(`https://psgc.gitlab.io/api/provinces/${selectedProvince.value}/cities-municipalities.json`);
                citiesMunicipalities.value = await cityResponse.json();
                loadingCities.value = false;

                // Load barangays for default city
                if (selectedCityMunicipality.value) {
                    loadingBarangays.value = true;
                    const brgyResponse = await fetch(`https://psgc.gitlab.io/api/cities-municipalities/${selectedCityMunicipality.value}/barangays.json`);
                    barangays.value = await brgyResponse.json();
                    loadingBarangays.value = false;
                }
            }
        }
    } catch (error) {
        console.error('Failed to fetch regions:', error);
    } finally {
        loadingRegions.value = false;
    }
});

// Watch region changes to fetch provinces
watch(selectedRegion, async (regionCode) => {
    // Reset dependent fields
    selectedProvince.value = '';
    selectedCityMunicipality.value = '';
    selectedBarangay.value = '';
    provinces.value = [];
    citiesMunicipalities.value = [];
    barangays.value = [];

    if (!regionCode) return;

    loadingProvinces.value = true;
    try {
        const response = await fetch(`https://psgc.gitlab.io/api/regions/${regionCode}/provinces.json`);
        provinces.value = await response.json();
    } catch (error) {
        console.error('Failed to fetch provinces:', error);
    } finally {
        loadingProvinces.value = false;
    }
});

// Watch province changes to fetch cities/municipalities
watch(selectedProvince, async (provinceCode) => {
    // Reset dependent fields
    selectedCityMunicipality.value = '';
    selectedBarangay.value = '';
    citiesMunicipalities.value = [];
    barangays.value = [];

    if (!provinceCode) return;

    loadingCities.value = true;
    try {
        const response = await fetch(`https://psgc.gitlab.io/api/provinces/${provinceCode}/cities-municipalities.json`);
        citiesMunicipalities.value = await response.json();
    } catch (error) {
        console.error('Failed to fetch cities/municipalities:', error);
    } finally {
        loadingCities.value = false;
    }
});

// Watch city/municipality changes to fetch barangays
watch(selectedCityMunicipality, async (cityMunCode) => {
    // Reset dependent fields
    selectedBarangay.value = '';
    barangays.value = [];

    if (!cityMunCode) return;

    loadingBarangays.value = true;
    try {
        const response = await fetch(`https://psgc.gitlab.io/api/cities-municipalities/${cityMunCode}/barangays.json`);
        barangays.value = await response.json();
    } catch (error) {
        console.error('Failed to fetch barangays:', error);
    } finally {
        loadingBarangays.value = false;
    }
});

// Build full address string when any address field changes
watch(
    [selectedRegion, selectedProvince, selectedCityMunicipality, selectedBarangay, streetAddress],
    () => {
        const parts: string[] = [];

        if (streetAddress.value) parts.push(streetAddress.value);

        const barangay = barangays.value.find(b => b.code === selectedBarangay.value);
        if (barangay) parts.push(`Brgy. ${barangay.name}`);

        const cityMun = citiesMunicipalities.value.find(c => c.code === selectedCityMunicipality.value);
        if (cityMun) parts.push(cityMun.name);

        const province = provinces.value.find(p => p.code === selectedProvince.value);
        if (province) parts.push(province.name);

        const region = regions.value.find(r => r.code === selectedRegion.value);
        if (region) parts.push(region.name);

        form.address = parts.join(', ');
    }
);

const submit = () => {
    form.post(store.url(), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <AdminLayout>
        <Head title="Create Client" />

        <div class="max-w-8xl mx-auto px-6 py-10 space-y-6">
            <div>
                <h1 class="text-3xl font-bold tracking-tight">Create Client</h1>
                <p class="text-muted-foreground">Add a new client to your system</p>
            </div>

            <div class="rounded-lg border bg-white p-8">
                <form @submit.prevent="submit" class="space-y-6 max-w-2xl">
                    <div class="grid gap-2">
                        <Label for="fullname">Full Name</Label>
                        <Input
                            id="fullname"
                            v-model="form.fullname"
                            type="text"
                            placeholder="John Doe"
                            required
                        />
                        <InputError :message="form.errors.fullname" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">Email</Label>
                        <Input
                            id="email"
                            v-model="form.email"
                            type="email"
                            placeholder="john@example.com"
                            required
                        />
                        <InputError :message="form.errors.email" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="number">Phone Number</Label>
                        <Input
                            id="number"
                            v-model="form.number"
                            type="text"
                            maxlength="11"
                            placeholder="09.."
                            required
                        />
                        <InputError :message="form.errors.number" />
                    </div>

                    <!-- Address Section -->
                    <div class="space-y-4 border-t pt-6">
                        <h3 class="text-lg font-semibold">Address</h3>

                        <div class="grid gap-4 md:grid-cols-2">
                            <!-- Region -->
                            <div class="grid gap-2">
                                <Label for="region">Region</Label>
                                <select
                                    id="region"
                                    v-model="selectedRegion"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                    :disabled="loadingRegions"
                                >
                                    <option value="">{{ loadingRegions ? 'Loading...' : 'Select Region' }}</option>
                                    <option v-for="region in regions" :key="region.code" :value="region.code">
                                        {{ region.name }}
                                    </option>
                                </select>
                            </div>

                            <!-- Province -->
                            <div class="grid gap-2">
                                <Label for="province">Province</Label>
                                <select
                                    id="province"
                                    v-model="selectedProvince"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                    :disabled="!selectedRegion || loadingProvinces"
                                >
                                    <option value="">{{ loadingProvinces ? 'Loading...' : 'Select Province' }}</option>
                                    <option v-for="province in provinces" :key="province.code" :value="province.code">
                                        {{ province.name }}
                                    </option>
                                </select>
                            </div>

                            <!-- City/Municipality -->
                            <div class="grid gap-2">
                                <Label for="cityMunicipality">City/Municipality</Label>
                                <select
                                    id="cityMunicipality"
                                    v-model="selectedCityMunicipality"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                    :disabled="!selectedProvince || loadingCities"
                                >
                                    <option value="">{{ loadingCities ? 'Loading...' : 'Select City/Municipality' }}</option>
                                    <option v-for="city in citiesMunicipalities" :key="city.code" :value="city.code">
                                        {{ city.name }}
                                    </option>
                                </select>
                            </div>

                            <!-- Barangay -->
                            <div class="grid gap-2">
                                <Label for="barangay">Barangay</Label>
                                <select
                                    id="barangay"
                                    v-model="selectedBarangay"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                    :disabled="!selectedCityMunicipality || loadingBarangays"
                                >
                                    <option value="">{{ loadingBarangays ? 'Loading...' : 'Select Barangay' }}</option>
                                    <option v-for="barangay in barangays" :key="barangay.code" :value="barangay.code">
                                        {{ barangay.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Street Address -->
                        <div class="grid gap-2">
                            <Label for="streetAddress">Street Address / House No. / Building</Label>
                            <Input
                                id="streetAddress"
                                v-model="streetAddress"
                                type="text"
                                placeholder="123 Main St, Building A"
                            />
                        </div>

                        <!-- Full Address Preview -->
                        <div class="grid gap-2">
                            <Label for="address">Full Address (Generated)</Label>
                            <Input
                                id="address"
                                v-model="form.address"
                                type="text"
                                placeholder="Full address will be generated automatically"
                                readonly
                                class="bg-muted"
                            />
                            <InputError :message="form.errors.address" />
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <Button type="submit" :disabled="form.processing">
                            <Spinner v-if="form.processing" />
                            Create Client
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
